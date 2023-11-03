<?php

declare(strict_types=1);

namespace src;

class Game
{
    public static array $states = [];

    public static array $validStates = [];

    public function __construct(public State $state)
    {
    }

    public static function draw(State $state): void
    {
        for ($i = 0; $i < $state->n; $i++) {
            echo "<h1>";
            echo str_replace([',', '-1'], [' - - - ', 'X'], implode(',', $state->ground[$i])) . '<br>';
            echo "</h1>";
        }
    }

    public static function showPlayers(State $state): void
    {
        for ($i = 0; $i < $state->players; $i++) {
            echo "Player$i location is " . $state->currents[$i][0] + 1 . ' , ' . $state->currents[$i][1] + 1 . '<br>';
        }
    }
}