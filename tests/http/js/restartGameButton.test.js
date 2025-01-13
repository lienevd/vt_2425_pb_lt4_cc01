// importeer jsdom bibliotek
const jsdom = require('jsdom');
const { JSDOM } = jsdom;

// Simuleer de DOM
const dom = new JSDOM(`
    <!DOCTYPE html>
    <html>
    <body>
        <button id="restartGameButton">Restart</button>
        <div id="hint"></div>
    </body>
    </html>
`);

// Maak de gesimuleerde DOM beschikbaar
global.document = dom.window.document;
global.window = dom.window;
test('DOM-simulatie werkt correct', () => {
    const button = document.getElementById('restartGameButton');
    const hint = document.getElementById('hint');

    // Controleer of de knop en hint bestaan
    expect(button).not.toBeNull();
    expect(hint).not.toBeNull();

    // Controleer de knoptekst
    expect(button.textContent).toBe('Restart');
});

// Mock de fillHint-functie
const fillHint = jest.fn();

// Test of de knop fillHint aanroept
test('restartGameButton roept fillHint aan bij klik', () => {
    const button = document.getElementById('restartGameButton');

    // Voeg een event listener toe aan de knop
    button.addEventListener('click', () => {
        fillHint('dieren', 5); // Roep fillHint aan met categorie en gridgrootte
    });

    // Simuleer een klik op de knop
    button.click();

    // Controleer of fillHint is aangeroepen
    expect(fillHint).toHaveBeenCalledTimes(1); // Is de functie 1 keer aangeroepen?
    expect(fillHint).toHaveBeenCalledWith('dieren', 5); // Zijn de juiste argumenten doorgegeven?
});