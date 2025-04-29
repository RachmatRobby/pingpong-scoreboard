<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Ping Pong Scoreboard</title>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;600;700&display=swap" rel="stylesheet">
    <style>
        :root {
            --primary-color: #3498db;
            --secondary-color: #2c3e50;
            --accent-color: #e74c3c;
            --success-color: #27ae60;
            --light-bg: #f5f7fa;
            --dark-text: #2c3e50;
            --light-text: #ecf0f1;
            --border-radius: 12px;
            --shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
        }

        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: 'Poppins', sans-serif;
            background-color: var(--light-bg);
            color: var(--dark-text);
            line-height: 1.6;
        }

        .container {
            max-width: 600px;
            margin: 40px auto;
            padding: 20px;
            background-color: white;
            border-radius: var(--border-radius);
            box-shadow: var(--shadow);
        }

        .header {
            text-align: center;
            margin-bottom: 30px;
        }

        .header h1 {
            color: var(--secondary-color);
            font-size: 32px;
            margin-bottom: 10px;
        }

        .header p {
            color: #7f8c8d;
            font-size: 16px;
        }

        /* Setup Form Styles */
        .setup-form {
            display: flex;
            flex-direction: column;
            gap: 15px;
        }

        .setup-form input, 
        .setup-form select {
            padding: 12px 15px;
            border: 1px solid #e0e0e0;
            border-radius: var(--border-radius);
            font-family: inherit;
            font-size: 16px;
        }

        .setup-form button {
            background-color: var(--primary-color);
            color: white;
            border: none;
            padding: 12px 15px;
            border-radius: var(--border-radius);
            font-size: 16px;
            font-weight: 600;
            cursor: pointer;
            transition: background-color 0.3s;
        }

        .setup-form button:hover {
            background-color: #2980b9;
        }

        /* Game Board Styles */
        .match-info {
            background-color: var(--secondary-color);
            color: var(--light-text);
            padding: 15px;
            border-radius: var(--border-radius);
            margin-bottom: 20px;
            text-align: center;
        }

        .sets-info {
            font-size: 14px;
            opacity: 0.8;
            margin-top: 5px;
        }

        .players {
            display: flex;
            justify-content: space-between;
            margin-bottom: 25px;
        }

        .player {
            flex: 1;
            text-align: center;
            padding: 10px;
        }

        .player-name {
            font-weight: 600;
            font-size: 18px;
            margin-bottom: 5px;
        }

        .sets {
            display: inline-block;
            background-color: var(--secondary-color);
            color: white;
            width: 30px;
            height: 30px;
            line-height: 30px;
            border-radius: 50%;
            font-weight: bold;
        }

        .score-container {
            display: flex;
            justify-content: space-between;
            margin-bottom: 25px;
        }

        .score-display {
            flex: 1;
            display: flex;
            flex-direction: column;
            align-items: center;
            gap: 15px;
            position: relative;
        }

        .score {
            font-size: 72px;
            font-weight: 700;
            background-color: var(--light-bg);
            width: 120px;
            height: 120px;
            display: flex;
            align-items: center;
            justify-content: center;
            border-radius: var(--border-radius);
            box-shadow: inset 0 2px 4px rgba(0, 0, 0, 0.1);
        }

        .point-button-container {
            position: relative;
            display: flex;
            align-items: center;
        }

        .point-button {
            background-color: var(--primary-color);
            color: white;
            border: none;
            padding: 10px 20px;
            border-radius: var(--border-radius);
            font-size: 16px;
            font-weight: 600;
            cursor: pointer;
            transition: transform 0.1s, background-color 0.3s;
            width: 120px;
        }

        .point-button:hover {
            background-color: #2980b9;
        }

        .point-button:active {
            transform: scale(0.98);
        }

        .controls {
            display: flex;
            justify-content: center;
            margin-top: 25px;
            gap: 15px;
        }

        .reset-button {
            background-color: var(--accent-color);
            color: white;
            border: none;
            padding: 10px 20px;
            border-radius: var(--border-radius);
            font-size: 16px;
            font-weight: 600;
            cursor: pointer;
            transition: background-color 0.3s;
            text-decoration: none;
        }

        .reset-button:hover {
            background-color: #c0392b;
        }

        .winner {
            text-align: center;
            font-size: 28px;
            font-weight: 700;
            color: var(--success-color);
            margin-top: 20px;
            padding: 15px;
            background-color: #e8f8f5;
            border-radius: var(--border-radius);
            animation: celebrate 1s ease-in-out;
        }

        /* Ping Pong Ball Icon */
        .ball-icon {
            width: 24px;
            height: 24px;
            background-color: #ff9800;
            border-radius: 50%;
            position: absolute;
            display: flex;
            justify-content: center;
            align-items: center;
            box-shadow: 0 2px 4px rgba(0,0,0,0.2);
            animation: bounce 1s infinite alternate;
        }

        .ball-icon::after {
            content: "";
            width: 12px;
            height: 6px;
            background-color: rgba(255, 255, 255, 0.7);
            border-radius: 50%;
            transform: rotate(-45deg);
            position: absolute;
            top: 5px;
            left: 7px;
        }

        .ball-left {
            left: -40px;
        }

        .ball-right {
            right: -40px;
        }

        /* Game History Styles */
        .history-section {
            margin-top: 40px;
            border-top: 1px solid #e0e0e0;
            padding-top: 20px;
        }

        .history-title {
            font-size: 20px;
            margin-bottom: 15px;
            text-align: center;
            color: var(--secondary-color);
        }

        .history-list {
            list-style: none;
            max-height: 300px;
            overflow-y: auto;
            border: 1px solid #e0e0e0;
            border-radius: var(--border-radius);
        }

        .history-item {
            padding: 15px;
            border-bottom: 1px solid #e0e0e0;
            display: flex;
            justify-content: space-between;
            align-items: center;
        }

        .history-item:last-child {
            border-bottom: none;
        }

        .history-info {
            flex: 1;
        }

        .history-match {
            font-weight: 600;
            font-size: 16px;
        }

        .history-result {
            font-size: 14px;
            color: #7f8c8d;
        }

        .history-date {
            font-size: 12px;
            color: #95a5a6;
        }

        .history-action {
            display: flex;
            gap: 10px;
        }

        .history-button {
            background-color: var(--primary-color);
            color: white;
            border: none;
            padding: 8px 12px;
            border-radius: var(--border-radius);
            font-size: 12px;
            cursor: pointer;
            text-decoration: none;
        }

        .history-button:hover {
            background-color: #2980b9;
        }

        .download-section {
            margin-top: 20px;
            text-align: center;
            padding: 15px;
            background-color: #e8f8f5;
            border-radius: var(--border-radius);
        }

        .download-button {
            display: inline-block;
            background-color: var(--success-color);
            color: white;
            border: none;
            padding: 10px 20px;
            border-radius: var(--border-radius);
            font-size: 16px;
            font-weight: 600;
            cursor: pointer;
            text-decoration: none;
            margin-top: 10px;
        }

        .download-button:hover {
            background-color: #219653;
        }

        .empty-history {
            text-align: center;
            padding: 20px;
            color: #7f8c8d;
        }

        @keyframes bounce {
            0% { transform: translateY(0); }
            100% { transform: translateY(-5px); }
        }

        @keyframes celebrate {
            0% { transform: scale(0.8); opacity: 0; }
            100% { transform: scale(1); opacity: 1; }
        }

        @media (max-width: 480px) {
            .container {
                margin: 20px 10px;
                padding: 15px;
            }

            .score {
                font-size: 48px;
                width: 100px;
                height: 100px;
            }

            .header h1 {
                font-size: 24px;
            }
            
            .ball-icon {
                width: 20px;
                height: 20px;
            }
            
            .ball-icon::after {
                width: 10px;
                height: 5px;
                top: 4px;
                left: 6px;
            }
            
            .ball-left {
                left: -30px;
            }
            
            .ball-right {
                right: -30px;
            }
            
            .history-action {
                flex-direction: column;
                gap: 5px;
            }
        }
    </style>
</head>
<body>
<div class="container">
    @if (!session('player1'))
        <div class="header">
            <h1>Ping Pong Scoreboard</h1>
            <p>Enter player names and match format to start</p>
        </div>
        <form method="POST" action="{{ route('start.game') }}" class="setup-form">
            @csrf
            <input type="text" name="player1" placeholder="Player 1 Name" required>
            <input type="text" name="player2" placeholder="Player 2 Name" required>
            <select name="bo" required>
                <option value="3">Best of 3</option>
                <option value="5">Best of 5</option>
                <option value="7">Best of 7</option>
            </select>
            <button type="submit">Start Game</button>
        </form>
        
        @if(isset($savedGames) && count($savedGames) > 0)
            <div class="history-section">
                <h2 class="history-title">Previous Matches</h2>
                <ul class="history-list">
                    @foreach($savedGames as $game)
                        <li class="history-item">
                            <div class="history-info">
                                <div class="history-match">{{ $game['players'] }}</div>
                                <div class="history-result">{{ $game['score'] }}</div>
                                <div class="history-date">{{ $game['date'] }}</div>
                            </div>
                            <div class="history-action">
                                <a href="{{ route('view.game', basename($game['filename'])) }}" class="history-button">View</a>
                                <a href="{{ route('download.game', $game['filename']) }}" class="history-button">Download</a>
                            </div>
                        </li>
                    @endforeach
                </ul>
            </div>
        @endif
    @else
        <div class="header">
            <h1>Ping Pong Scoreboard</h1>
        </div>
        <div class="match-info">
            <div>{{ session('player1') }} vs {{ session('player2') }}</div>
            <div class="sets-info">Best of {{ session('bo') }} (First to win {{ ceil(session('bo') / 2) }} sets)</div>
        </div>
        
        <div class="players">
            <div class="player">
                <div class="player-name">{{ session('player1') }}</div>
                <div class="sets">{{ session('sets')[0] }}</div>
            </div>
            <div class="player">
                <div class="player-name">{{ session('player2') }}</div>
                <div class="sets">{{ session('sets')[1] }}</div>
            </div>
        </div>

        <div class="score-container">
            <div class="score-display">
                <div class="score">{{ session('score')[0] }}</div>
                <div class="point-button-container">
                    @if(session('serve') == 0)
                        <div class="ball-icon ball-right"></div>
                    @endif
                    <form method="POST" action="{{ route('add.point', 0) }}">
                        @csrf
                        <button class="point-button">+1 Point</button>
                    </form>
                </div>
            </div>
            <div class="score-display">
                <div class="score">{{ session('score')[1] }}</div>
                <div class="point-button-container">
                    @if(session('serve') == 1)
                        <div class="ball-icon ball-left"></div>
                    @endif
                    <form method="POST" action="{{ route('add.point', 1) }}">
                        @csrf
                        <button class="point-button">+1 Point</button>
                    </form>
                </div>
            </div>
        </div>

        <div class="controls">
            <a href="{{ route('game.reset') }}" class="reset-button">New Game</a>
        </div>

        @if (session('winner'))
            <div class="winner">{{ session('winner') }}</div>
            
            @if (session('last_game_file'))
                <div class="download-section">
                    <p>Game result has been saved!</p>
                    <a href="{{ route('download.game', session('last_game_file')) }}" class="download-button">Download Match Report</a>
                </div>
            @endif
        @endif
    @endif
</div>
</body>
</html>