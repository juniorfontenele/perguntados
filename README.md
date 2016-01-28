# Perguntados API
An API to Preguntados Game by Etermax

This package provides an interface to interact with 'Preguntados' Game from Etermax

## Requirements
You'll need to have an open session with Preguntados Game and know your Preguntados' user_id.
You can access this information following [this tutorial](http://www.seguridadofensiva.com/2014/01/como-ganar-siempre-al-juego-preguntados.html). 

## Instalation
The easiest way to install this package is using composer:
```
composer require juniorfontenele/perguntados
```
## Usage
- Rename .env.example to .env
- Replace 'USER_ID' with your Preguntados' USER ID
- Replace 'APP_COOKIE' with your Preguntados' session cookie (ap_session)
- Import Perguntados Class to your file:
```php
require_once __DIR__ . '/../../vendor/autoload.php';
use Perguntados\Perguntados;
$Perguntados = new Perguntados();
```
### List All Pending and Active Games
```php
$games = $Perguntados->getGames();
foreach ($games as $game) {
    echo $game->toJson();
}
```
### Get Info on a specific game
```php
$gameId = '1234567890';
$game = $Perguntados->getGame($gameId);
echo $game->toJson();
```
### Win a Game
```php
try {
    $Perguntados->winGame($game);
} catch(Exception $e) {
    echo "Failed to win game: " . $e->getMessage();
}
```
### Play and Win a Random Duel
```php
try {
    $Perguntados->winRandomDuel();
} catch(Exception $e) {
    echo "Failed to win random game: " . $e->getMessage();
}
```

## License

The MIT License (MIT). Please see [License File](https://github.com/juniorfontenele/perguntados/blob/master/LICENSE) for more information.