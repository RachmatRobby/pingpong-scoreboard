<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Match History</title>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;600;700&display=swap" rel="stylesheet">
    <style>
        :root {
            --primary-color: #3498db;
            --secondary-color: #2c3e50;
            --accent-color: #e74c3c;
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
            max-width: 800px;
            margin: 40px auto;
            padding: 20px;
            background-color: white;
            border-radius: var(--border-radius);
            box-shadow: var(--shadow);
        }

        .header {
            text-align: center;
            margin-bottom: 30px;
            padding-bottom: 20px;
            border-bottom: 1px solid #e0e0e0;
        }

        .header h1 {
            color: var(--secondary-color);
            font-size: 32px;
            margin-bottom: 10px;
        }

        .game-content {
            background-color: var(--light-bg);
            padding: 20px;
            border-radius: var(--border-radius);
            white-space: pre-wrap;
            font-family: monospace;
            font-size: 14px;
            line-height: 1.6;
            overflow-x: auto;
        }

        .controls {
            display: flex;
            justify-content: space-between;
            margin-top: 30px;
        }

        .button {
            display: inline-block;
            background-color: var(--primary-color);
            color: white;
            border: none;
            padding: 10px 20px;
            border-radius: var(--border-radius);
            font-size: 16px;
            font-weight: 600;
            cursor: pointer;
            text-decoration: none;
            transition: background-color 0.3s;
        }

        .button:hover {
            background-color: #2980b9;
        }

        .button.download {
            background-color: #27ae60;
        }

        .button.download:hover {
            background-color: #219653;
        }

        .button.back {
            background-color: var(--secondary-color);
        }

        .button.back:hover {
            background-color: #1e2b38;
        }

        @media (max-width: 480px) {
            .container {
                margin: 20px 10px;
                padding: 15px;
            }

            .header h1 {
                font-size: 24px;
            }

            .controls {
                flex-direction: column;
                gap: 10px;
            }

            .button {
                text-align: center;
            }
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="header">
            <h1>Match History</h1>
        </div>

        <div class="game-content">{{ $content }}</div>

        <div class="controls">
            <a href="{{ route('home') }}" class="button back">Back to Scoreboard</a>
            <a href="{{ route('download.game', $filename) }}" class="button download">Download Report</a>
        </div>
    </div>
</body>
</html>