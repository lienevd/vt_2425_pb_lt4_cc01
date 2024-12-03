$(document).ready(function() {
    // DOM Elements
    const startScreen = document.getElementById('start-screen');
    const initializationScreen = document.getElementById('initialization-screen');
    const gameScreen = document.getElementById('game-screen');
    const startGameButton = document.getElementById('start-game-button');
    const startGridButton = document.getElementById('start-grid-button');
    const backToStartButton = document.getElementById('back-to-start');
    const backToInitializationButton = document.getElementById('back-to-initialization');
    const categorySelect = document.getElementById('category-select');
    const gameLengthSelect = document.getElementById('game-length');
    const gridContainer = document.getElementById('grid-container');

    let selectedCategory = '';
    let gridSize = 3;

    // Show Initialization Screen
    startGameButton.addEventListener('click', () => {
        switchScreen(startScreen, initializationScreen);
    });

    // Show Game Screen
    startGridButton.addEventListener('click', () => {
        selectedCategory = categorySelect.value;
        gridSize = parseInt(gameLengthSelect.value);

        switchScreen(initializationScreen, gameScreen);
        fillHint(selectedCategory);
        generateGrid(gridSize, selectedCategory);
    });

    // Go Back to Start Screen
    backToStartButton.addEventListener('click', () => {
        switchScreen(initializationScreen, startScreen);
    });

    // Go Back to Initialization Screen
    backToInitializationButton.addEventListener('click', () => {
        switchScreen(gameScreen, initializationScreen);
    });

    // Switch between screens
    function switchScreen(current, next) {
        current.classList.remove('active');
        next.classList.add('active');
    }

    function fillHint(category) {

        $.get('/get-hint/' + category, function(data) {
            console.log(data);
            $("#hint").text(data);
        });

    }

    // Generate Grid
    function generateGrid(size, category) {
        gridContainer.style.gridTemplateColumns = `repeat(${size}, 1fr)`;
        gridContainer.innerHTML = '<div class="loader">Loading images...</div>';

        $.get('/get-images/' + category, function(data) {
            gridContainer.innerHTML = ''; // Clear previous grid
                
            let images = JSON.parse(data);

            for (let i = 1; i <= size * size; i++) {
                const button = document.createElement('button');
                button.className = 'grid-button';
                button.textContent = i; // Placeholder for button text
                
                // Zet de afbeelding om naar een <img> element
                let image = document.createElement('img');
                image.src = "data:image/jpg;base64," + images[i-1];
                image.className = 'grid-image';

                image.addEventListener('click', () => {
                    image.classList.toggle('active-image');
                });

                gridContainer.appendChild(image);
            }
        });

        // Get request om afbeeldingen op te halen
        // $.ajax({
        //     url: '/get-images/' + category,
        //     type: 'GET',
        //     beforeSend: function() {
                
        //         // Laat "Loading images..." zien totdat de afbeeldingen zijn geladen
        //         gridContainer.innerHTML = '<div class="loader">Loading images...</div>';

        //     },
        //     success: function(data) {

        //         gridContainer.innerHTML = ''; // Clear previous grid
                
        //         let images = JSON.parse(data);

        //         for (let i = 1; i <= size * size; i++) {
        //             const button = document.createElement('button');
        //             button.className = 'grid-button';
        //             button.textContent = i; // Placeholder for button text
                    
        //             // Zet de afbeelding om naar een <img> element
        //             let image = document.createElement('img');
        //             image.src = "data:image/jpg;base64," + images[i-1];
        //             image.className = 'grid-image';

        //             image.addEventListener('click', () => {
        //                 image.classList.toggle('active-image');
        //             });

        //             gridContainer.appendChild(image);
        //         }

        //     },
        //     error: function(error) {
        //         console.log(error);
        //     },
        // });

    }

    $("#hint-input-form").on("submit", function(event) {
        event.preventDefault();
        let hint = $("#hint-input").val();

        if (hint === "") {
            alert("Please enter a hint.");
            return;
        }

        $.post('/add-hint', { hint: hint, category: selectedCategory }, function(data) {});
    });
});