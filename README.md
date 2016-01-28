# Perguntados API
An API to Preguntados Game by Etermax

This package provides an interface to interact with 'Preguntados' Game from Etermax

## Instalation
The easiest way to install this package is using composer:
```
composer require juniorfontenele/perguntados
```
## Usage
Import Perguntados Class to your file:
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