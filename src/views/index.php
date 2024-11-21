<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Clue Catcher</title>
    <link rel="stylesheet" href="/public/style.css">
</head>
<body>
    <!-- Start Screen -->
    <div id="start-screen" class="screen active">
        <h1>Clue Capture</h1>
        <button id="start-game-button">Start spel</button>
    </div>

    <!-- Initialization Screen -->
    <div id="initialization-screen" class="screen">
        <h2>Stel je spel in</h2>
        <label for="category-select">Categorie:</label>
        <select id="category-select">
            <option value="dieren">Dieren</option>
            <option value="kunst">Kunst</option>
            <option value="technologie">Technologie</option>
        </select>
        <label for="game-length">Spellengte:</label>
        <select id="game-length">
            <option value="3">Kort (3x3)</option>
            <option value="5">Medium (5x5)</option>
            <option value="7">Lang (7x7)</option>
        </select>
        <button id="start-grid-button">Start spel</button>
        <button class="back-button" id="back-to-start">Terug naar startmenu</button>
    </div>

    <!-- Game Screen -->
    <div id="game-screen" class="screen">
        <button class="back-button" id="back-to-initialization">↰</button>
        <div id="grid-container"></div>
    </div>

    <script src="/public/script.js"></script>
</body>
</html>