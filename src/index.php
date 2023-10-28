<?php

declare(strict_types=1);
require_once '../vendor/autoload.php';

use src\State;

$states = [];
$validStates = [];

function draw(State $state): void
{
    for ($i = 0; $i < $state->n; $i++) {
        echo "<h1>";
        echo str_replace([',', '-1'], [' - - - ', 'X'], implode(',', $state->ground[$i])) . '<br>';
        echo "</h1>";
    }
}

function showPlayers(State $state): void
{
    for ($i = 0; $i < $state->players; $i++) {
        echo "Player$i location is " . $state->currents[$i][0] + 1 . ' , ' . $state->currents[$i][1] + 1 . '<br>';
    }
}

array_push($states, new State(10, 10, 2, [[0, 0], [1, 1]]));
$states[0]->putPlayers();
$states[0]->ground = $states[0]->generateRandomGround();
draw($states[0]);
showPlayers($states[0]);

// $state1 = new State(5, 8, 2, [[1, 1], [0, 0]]);
// $state1->ground = $states[0]->ground;
// var_dump($state1->isEqualTo($states[0]));

var_dump('Final', $states[0]->isFinal());
echo '<br>';

// while ($states[array_key_last($states)]->canMove(1, State::RIGHT)) {
//     array_push($states, $states[array_key_last($states)]->move(1, State::RIGHT));
//     draw($states[array_key_last($states)]);
//     showPlayers($states[array_key_last($states)]);
// }

// array_push($validStates, $states[0]->validGrounds());

// for ($i = 0; $i < count($validStates[0]); $i++) {
//     echo "<h1>";
//     echo str_repeat('*******', $states[0]->n);
//     echo "</h1>";
//     draw($validStates[0][$i]);
//     showPlayers($validStates[0][$i]);
// }

// var_dump(array_filter([[0, 11], [1, 1]], function ($value) {
//     // var_dump(
//     //     array_search(
//     //         $value,
//     //         [[11, 0], [0, 11]]
//     //     ) === false
//     // );
//     return array_search(
//         $value,
//         [[11, 0], [0, 11]]
//     ) === false;
// }));
