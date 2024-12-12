$(document).ready(function() {
    // DOM Elements
    const startScreen = document.getElementById('start-screen');
    const initializationScreen = document.getElementById('initialization-screen');
    const gameScreen = document.getElementById('game-screen');
    const hintScreen = document.getElementById('hint-screen');
    const startGameButton = document.getElementById('start-game-button');
    const startGridButton = document.getElementById('start-grid-button');
    const addHintButton = document.getElementById('add-hint-button');
    const backToStartButton = document.getElementById('back-to-start');
    const backToInitializationButton = document.getElementById('back-to-initialization');
    const backToStartFromHintButton = document.getElementById('back-to-start-from-hint');
    const categorySelect = document.getElementById('category-select');
    const hintCategorySelect = document.getElementById('hint-category-select');
    const gameLengthSelect = document.getElementById('game-length');
    const gridContainer = document.getElementById('grid-container');
    const hintGridContainer = document.getElementById('hint-grid-container');

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

    // Show Hint Screen
    addHintButton.addEventListener('click', () => {
        switchScreen(startScreen, hintScreen);
        loadHintImages(hintCategorySelect.value);
    });

    // Go Back to Start Screen from Initialization
    backToStartButton.addEventListener('click', () => {
        switchScreen(initializationScreen, startScreen);
    });

    // Go Back to Start Screen from Hint Screen
    backToStartFromHintButton.addEventListener('click', () => {
        switchScreen(hintScreen, startScreen);
    });

    // Go Back to Initialization Screen
    backToInitializationButton.addEventListener('click', () => {
        switchScreen(gameScreen, initializationScreen);
    });

    // Load images for hint screen when category changes
    hintCategorySelect.addEventListener('change', () => {
        loadHintImages(hintCategorySelect.value);
    });

    // Switch between screens
    function switchScreen(current, next) {
        current.classList.remove('active');
        next.classList.add('active');
    }

    function fillHint(category) {
        $.get(`/get-hint/${category}`, function(data) {
            $("#hint").text(data);
        }).fail(function() {
            alert("Failed to load hint.");
        });
    }

    // Generate Grid
    function generateGrid(size, category) {
        gridContainer.style.gridTemplateColumns = `repeat(${size}, 1fr)`;
        gridContainer.innerHTML = '<div class="loader">Loading images...</div>';

        $.get(`/get-images/${category}`, function(data) {
            gridContainer.innerHTML = ''; // Clear previous grid
                
            let images = JSON.parse(data);

            for (let i = 1; i <= size * size; i++) {
                const button = document.createElement('button');
                button.className = 'grid-button';
                button.textContent = i; // Placeholder for button text
                
                // Zet de afbeelding om naar een <img> element
                let image = document.createElement('img');
                image.src = `data:image/jpg;base64,${images[i-1]['image']}`;
                image.setAttribute('data-id', images[i-1]['id']);
                image.className = 'grid-image';

                image.addEventListener('click', () => {
                    image.classList.toggle('active-image');
                });

                gridContainer.appendChild(image);
            }
        }).fail(function() {
            alert("Failed to load images.");
        });
    }

    function loadHintImages(category) {
        hintGridContainer.innerHTML = '<div class="loader">Loading images...</div>';

        $.get(`/get-images/${category}`, function(data) {
            hintGridContainer.innerHTML = ''; // Clear previous grid
                
            let images = JSON.parse(data);

            for (let i = 0; i < images.length; i++) {
                let image = document.createElement('img');
                image.src = `data:image/jpg;base64,${images[i]['image']}`;
                image.setAttribute('data-id', images[i]['id']);
                image.className = 'grid-image';

                image.addEventListener('click', () => {
                    image.classList.toggle('active-image');
                });

                hintGridContainer.appendChild(image);
            }
        }).fail(function() {
            alert("Failed to load images.");
        });
    }

    $("#hint-input-form").on("submit", function(event) {
        event.preventDefault();
        let hint = $("#hint-input").val();

        if (hint === "") {
            alert("Please enter a hint.");
            return;
        }

        let imageIds = [];
        $(".grid-image").each(function() {
            if ($(this).hasClass("active-image")) {
                imageIds.push($(this).data('id'));
            }
        });

        if (imageIds.length < 2) {
            alert("Selecteer minimaal 2 afbeeldingen.");
            return;
        }

        $.post('/add-hint', { hint: hint, category: hintCategorySelect.value, imageIds: imageIds }, function() {
            $("#hint-input").val('');
            $(".grid-image").removeClass('active-image');
        }).fail(function() {
            alert("Failed to add hint.");
        });
    });
});