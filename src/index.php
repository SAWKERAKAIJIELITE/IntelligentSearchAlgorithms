<?php

declare(strict_types=1);
require_once '../vendor/autoload.php';

use src\Game;
use src\State;

$file = file_get_contents('initialGround.json');
$initialGround = json_decode($file);

$state1 = new State(
    count($initialGround),
    count($initialGround[0]),
    1,
    [[1, 1]],
    0,
    max(array_merge(...$initialGround))
);

$state1->putPlayers();
$state1->ground = $initialGround;

//Game::$states[0]->putPlayers();
//Game::$states[0]->ground = $initialGround;
//// Game::$states[0]->ground = Game::$states[0]->generateRandomGround();
//
//Game::draw(Game::$states);
Game::draw([$state1]);
//$validStates = $state1->validGrounds();
//Game::draw($validStates);
//
//foreach ($validStates as $validState) {
//    Game::draw($validState->validGrounds());
//}

/*//DFS
Game::$stack = new SplStack();
Game::$stack->push($state1);
Game::DFS($state1);

$s = count(Game::$states);
$v = count(Game::$visited);
$f = Game::$g;
echo "<h1>$s</h1>";
echo "<h1>$v</h1>";
echo "<h1>$f</h1>";
echo '<h1>result</h1>';
Game::draw(Game::$states);*/

/*//BFS
Game::$queue = new SplQueue();
Game::BFS($state1);
$s = count(Game::$states);
$v = count(Game::$visited);
$f = Game::$g;
echo "<h1>$s</h1>";
echo "<h1>$v</h1>";
echo "<h1>$f</h1>";
echo '<h1>result</h1>';
Game::draw(Game::$states);*/

// $state1 = new State(3, 3, 1, [[1, 1]]);
// $state1->ground = Game::$states[0]->ground;
// var_dump($state1->isEqualTo(Game::$states[0]));

/*if (!$state1->isFinal()) {
    echo "<form action=\"index.php\" method=\"post\">";
    echo '  <select name="Player">';
    for ($i = 0; $i < $state1->players; $i++) {
        echo "<option value=\"$i\"> $i </option>";
    }
    echo "  </select>
            <select name=\"Direction\">
                <option value=\"RIGHT\">right</option>
                <option value=\"LEFT\">left</option>
                <option value=\"UP\">up</option>
                <option value=\"DOWN\">down</option>
            </select>
            <br>
            <input type=\"submit\" value=\"save\">
            <input type=\"reset\" value=\"reset\">
        </form>";

    switch ($_POST['Direction']) {
        case 'RIGHT':
            $d = [0, 1];
            break;
        case 'LEFT':
            $d = [0, -1];
            break;
        case 'UP':
            $d = [-1, 0];
            break;
        case 'DOWN':
            $d = [1, 0];
            break;
    }

    if ($state1->canMove((int) $_POST['Player'], $d)) {
        Game::draw([$state1->move((int) $_POST['Player'], $d)]);
//        showPlayers($states[array_key_last($states)]);
    }
}*/
