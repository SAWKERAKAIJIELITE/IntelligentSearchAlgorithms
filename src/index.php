<?php

declare(strict_types=1);
require_once '../vendor/autoload.php';

use src\Game;
use src\State;

$file = file_get_contents('initialGround.json');
$initialGround = json_decode($file);

array_push(
    Game::$states,
    new State(
        count($initialGround),
        count($initialGround[0]),
        1,
        [[1, 1]]
    )
);

Game::$states[0]->putPlayers();
Game::$states[0]->ground = $initialGround;
// Game::$states[0]->ground = Game::$states[0]->generateRandomGround();

Game::draw(Game::$states[0]);
Game::showPlayers(Game::$states[0]);

// array_push(Game::$validStates, ...Game::$states[0]->validGrounds());
// $new = $validStates[array_key_last($validStates)]->validGrounds();
// $xx = array_filter($new, function (State $state) use ($validStates) {
//     foreach ($validStates as $value) {
//         return $state->isEqualTo($value);
//     }
// });
// if (count($xx) == 0) {
// }
// echo $s = serialize(Game::$states[0]);
// print_r(array_intersect($validStates,...$validStates[array_key_last($validStates)]->validGrounds()));

// $state1 = new State(3, 3, 1, [[1, 1]]);
// $state1->ground = Game::$states[0]->ground;
// $s1 = serialize($state1);
// echo $s0 = serialize($states[0]);
// echo $s0[2];
// var_dump($state1->isEqualTo(Game::$states[0]));
// var_dump($s1 === $s0);

/*if (!$states[array_key_last($states)]->isFinal()) {
    echo "<form action=\"index.php\" method=\"post\">";
    echo '  <select name="Player">';
    for ($i = 0; $i < $states[array_key_last($states)]->players; $i++) {
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

    if ($states[array_key_last($states)]->canMove((int) $_POST['Player'], $d)) {
        array_push($states, $states[array_key_last($states)]->move((int) $_POST['Player'], $d));
        draw($states[array_key_last($states)]);
        showPlayers($states[array_key_last($states)]);
    }
}*/

/*while ($states[array_key_last($states)]->canMove(1, State::RIGHT)) {
    array_push($states, $states[array_key_last($states)]->move(1, State::RIGHT));
    draw($states[array_key_last($states)]);
    showPlayers($states[array_key_last($states)]);
}*/

/*array_push($validStates, $states[0]->validGrounds());
for ($i = 0; $i < count($validStates[0]); $i++) {
    echo "<h1>";
    echo str_repeat('*******', $states[0]->n);
    echo "</h1>";
    draw($validStates[0][$i]);
    showPlayers($validStates[0][$i]);
}*/