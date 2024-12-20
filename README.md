# Clue Capture

## Overzicht

Clue Capture is een spel gebaseerd op het spel "Codenames". In dit spel krijg je een raster te zien met plaatjes, de plaatjes zijn een redelijk samenhangend thema zodat er veel punten in een keer geraden kunnen worden. Hier kies je een woord bij dat verschillende plaatjes aan elkaar linkt. Vervolgens krijg je een nieuw raster waarbij je met een hint van iemand anders de juiste plaatjes aanklikt

## Installatie

Volg deze stappen om Clue Capture te installeren:

1. Clone de repository:
    ```bash
    git clone https://jouw-gebruikersnaam@bitbucket.org/adsd-hu/vt_2425_pb_lt4_cc01.git
    ```

2. Navigeer naar de projectmap:
    ```bash
    cd clue-capture
    ```

3. Kopieer local-settings.dist.php naar local-settings.php:
    #### Linux:
    ```bash
    cp local-settings.dist.php local-settings.php
    ```

    #### Windows:
    ```bash
    copy local-settings.dist.php local-settings.php
    ```

4. Vul local-settings.php in met de gegevens van de MySQL database. (SQL bestand voor de database volgt nog)

Voor de applicatie moet [PHP](https://www.php.net/downloads.php) ook geïnstalleerd zijn.

## Gebruik

Start de applicatie lokaal met het volgende commando:
```bash
php -S localhost:8000
```

Bezoek vervolgens `http://localhost:8000` in je webbrowser om de applicatie te gebruiken.