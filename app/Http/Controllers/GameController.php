<?php
namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Storage;
use Carbon\Carbon;

class GameController extends Controller
{
    public function index()
    {
        // Get list of saved games
        $savedGames = $this->getSavedGames();
        
        return view('game', compact('savedGames'));
    }

    public function startGame(Request $request)
    {
        $request->validate([
            'player1' => 'required',
            'player2' => 'required',
            'bo' => 'required|in:3,5,7'
        ]);

        Session::put('player1', $request->player1);
        Session::put('player2', $request->player2);
        Session::put('bo', $request->bo);
        Session::put('score', [0, 0]);
        Session::put('sets', [0, 0]);
        Session::put('game_id', uniqid());
        Session::put('started_at', Carbon::now()->format('Y-m-d H:i:s'));
        Session::put('score_history', []);
        
        // Menentukan siapa yang serve pertama kali (player 0)
        Session::put('serve', 0);
        Session::put('total_points', 0);

        return redirect('/');
    }

    public function addPoint($player)
    {
        $score = Session::get('score', [0, 0]);
        $sets = Session::get('sets', [0, 0]);
        $bo = Session::get('bo', 3);
        $totalPoints = Session::get('total_points', 0);
        $scoreHistory = Session::get('score_history', []);
    
        $score[$player]++;
        $totalPoints++;
        
        // Catat perubahan skor ke dalam history
        $scoreHistory[] = [
            'time' => Carbon::now()->format('H:i:s'),
            'player' => $player,
            'score' => [$score[0], $score[1]],
            'sets' => [$sets[0], $sets[1]]
        ];
        
        Session::put('score_history', $scoreHistory);
        
        // Update siapa yang serve
        // Dalam table tennis, setiap 2 poin serve berganti
        if ($totalPoints % 2 == 0) {
            $serve = Session::get('serve', 0);
            $serve = 1 - $serve; // Toggle antara 0 dan 1
            Session::put('serve', $serve);
        }
        
        Session::put('total_points', $totalPoints);
    
        // Check for deuce
        if (max($score) >= 10 && abs($score[0] - $score[1]) < 2) {
            Session::put('score', $score);
            return back();
        }
    
        // Check set winner
        if ($score[$player] >= 11 && ($score[$player] - $score[1 - $player]) >= 2) {
            $sets[$player]++;
            $score = [0, 0];
            
            // Reset total points untuk set baru
            Session::put('total_points', 0);
            
            // Menentukan serve untuk set baru (berganti setiap set)
            $newServe = ($sets[0] + $sets[1]) % 2;
            Session::put('serve', $newServe);
    
            // Check game winner after updating sets
            $neededSets = ceil($bo / 2);
            if ($sets[$player] >= $neededSets) {
                $winnerName = Session::get('player' . ($player + 1));
                Session::flash('winner', $winnerName . ' Wins the Game!');
                
                // Simpan hasil pertandingan ke file
                $this->saveGameToFile($player);
                
                return back();
            }
        }
    
        Session::put('score', $score);
        Session::put('sets', $sets);
    
        return back();
    }
    
    protected function saveGameToFile($winnerIndex)
    {
        $player1 = Session::get('player1');
        $player2 = Session::get('player2');
        $sets = Session::get('sets');
        $bo = Session::get('bo');
        $startedAt = Session::get('started_at');
        $gameId = Session::get('game_id');
        $finishedAt = Carbon::now()->format('Y-m-d H:i:s');
        $scoreHistory = Session::get('score_history', []);
        
        $winnerName = Session::get('player' . ($winnerIndex + 1));
        $loserName = Session::get('player' . (1 - $winnerIndex + 1));
        
        // Format konten file
        $content = "Game ID: $gameId\n";
        $content .= "Date: " . Carbon::now()->format('Y-m-d') . "\n";
        $content .= "Started: $startedAt\n";
        $content .= "Finished: $finishedAt\n";
        $content .= "Format: Best of $bo\n";
        $content .= "Winner: $winnerName\n";
        $content .= "Final Score: {$player1} {$sets[0]} - {$sets[1]} {$player2}\n";
        $content .= "\n";
        $content .= "Score History:\n";
        $content .= "--------------\n";
        
        foreach ($scoreHistory as $index => $history) {
            $playerName = $history['player'] == 0 ? $player1 : $player2;
            $content .= "[{$history['time']}] Point for $playerName: {$history['score'][0]}-{$history['score'][1]} (Sets: {$history['sets'][0]}-{$history['sets'][1]})\n";
        }
        
        // Pastikan direktori storage/app/games ada
        if (!Storage::exists('games')) {
            Storage::makeDirectory('games');
        }
        
        // Simpan ke file
        $filename = "games/game_" . date('Ymd_His') . "_" . $player1 . "_vs_" . $player2 . ".txt";
        Storage::put($filename, $content);
        
        // Simpan nama file ke session untuk ditampilkan di view
        Session::put('last_game_file', $filename);
        Session::put('last_game_content', $content);
    }
    
    public function downloadGame($filename)
    {
        // Hapus prefiks 'games/' jika ada dalam parameter filename
        if (strpos($filename, 'games/') === 0) {
            $filename = substr($filename, 6); // Remove 'games/' prefix
        }
        
        // Path lengkap ke file
        $filePath = 'games/' . $filename;
        
        // Mengecek apakah file ada
        if (Storage::exists($filePath)) {
            return Storage::download($filePath);
        }
        
        return back()->with('error', 'File not found');
    }
    
    public function viewGameHistory($filename)
    {
        if (Storage::exists($filename)) {
            $content = Storage::get($filename);
            return view('game_history', compact('content', 'filename'));
        }
        
        return back()->with('error', 'File not found');
    }
    
    protected function getSavedGames()
    {
        if (!Storage::exists('games')) {
            return [];
        }
        
        $files = Storage::files('games');
        $games = [];
        
        foreach ($files as $file) {
            // Hanya ambil file .txt
            if (pathinfo($file, PATHINFO_EXTENSION) === 'txt') {
                $content = Storage::get($file);
                $lines = explode("\n", $content);
                
                // Parse informasi dasar
                $gameInfo = [
                    'filename' => $file,
                    'date' => '',
                    'players' => '',
                    'score' => '',
                    'winner' => ''
                ];
                
                foreach ($lines as $line) {
                    if (strpos($line, 'Date:') === 0) {
                        $gameInfo['date'] = trim(str_replace('Date:', '', $line));
                    } elseif (strpos($line, 'Final Score:') === 0) {
                        $gameInfo['score'] = trim(str_replace('Final Score:', '', $line));
                    } elseif (strpos($line, 'Winner:') === 0) {
                        $gameInfo['winner'] = trim(str_replace('Winner:', '', $line));
                    }
                }
                
                // Extract player names from filename
                $filename = pathinfo($file, PATHINFO_FILENAME);
                if (preg_match('/_(.+)_vs_(.+)$/', $filename, $matches)) {
                    $gameInfo['players'] = $matches[1] . ' vs ' . $matches[2];
                }
                
                $games[] = $gameInfo;
            }
        }
        
        // Sort by newest first
        usort($games, function($a, $b) {
            return strtotime($b['date']) - strtotime($a['date']);
        });
        
        return $games;
    }
    
    public function reset()
    {
        Session::flush();
        return redirect('/');
    }
}