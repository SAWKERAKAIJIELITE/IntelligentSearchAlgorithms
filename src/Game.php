<?php

declare(strict_types=1);

namespace src;

use SplPriorityQueue;
use SplQueue;
use SplStack;

class Game
{
    public static SplStack $stack;

    public static SplQueue $queue;

    public static SplPriorityQueue $priorityQueue;

    public static int $g = 0;

    public static array $states = [];

    public static array $visited = [];

    public static array $loses = [];

    public function __construct(public State $state)
    {
    }

    public static function DFS(State $state): bool
    {
        if (!self::isNotVisited($state)) {
            self::$g++;
            self::$stack->pop();
            array_pop(self::$states);
            return false;
        }
        self::$visited[] = $state;

        if ($state->win()) {
            echo '<h1>Finish!!!</h1>';
            self::$g++;
            self::$stack->pop();
            return true;
        }
        if ($state->lose()) {
            echo '<h1>BackTrack </h1>';
            self::$g++;
            self::$stack->pop();
            array_pop(self::$states);
            echo '<h1>Lost </h1>';
            return false;
        }

        $validStates = $state->validGrounds();

        $lost = array_filter($validStates, fn($value) => $value->lose());
        if (count($lost) === count($validStates) && count($lost) !== 0) {
            self::$g++;
            self::$stack->pop();
            array_pop(self::$states);
            self::$loses[] = $state;
            echo '<h1>All Lost </h1>';
            return false;
        }

        foreach ($validStates as $validState) {
            self::$stack->push($validState);
            self::$states[] = $validState;
            self::draw([$validState]);
//            if(self::DFS($validState))
//                return true;
            self::DFS($validState);
        }
        self::$stack->pop();
        $validStates = array_filter(
            $validStates,
            fn($value) => self::doesntLose($value) && !in_array($value, $lost));
        if (count($validStates) === 0) {
            self::$loses[] = $state;
            array_pop(self::$states);
        }
        return false;
    }

    public static function isNotVisited($state): bool
    {
        if (empty(self::$visited))
            return true;
        foreach (self::$visited as $item)
            if ($state->isEqualTo($item)) {
                echo '<h1>Visited</h1>';
                return false;
            }
        return true;
    }

    public static function draw(array $states): void
    {
        foreach ($states as $item) {
            $cost = $item->cost ?? 0;
            echo "<h1>cost $cost</h1>";
            $distance = $item->heuristic() ?? 0;
            echo "<h1>distance $distance</h1>";
            if ($item instanceof ModifiedState)
                $item = $item->state;
            for ($i = 0; $i < $item->n; $i++) {
                echo '<h1>';
                echo str_replace([',', '-1'], [' - - - ', 'X'], implode(',', $item->ground[$i]));
                echo '</h1>';
            }
            self::showPlayers($item);
            echo '<hr>';
        }
    }

    private static function showPlayers(State $state): void
    {
        for ($i = 0; $i < $state->players; $i++)
            echo 'Player' . $i . ' location is ' . $state->currents[$i][0] + 1 . ' , ' . $state->currents[$i][1] + 1 . '<br>';
    }

    public static function doesntLose(State $state): bool
    {
        if (empty(self::$loses))
            return true;
        foreach (self::$loses as $lose)
            if ($state->isEqualTo($lose)) {
                echo '<h1>Loses</h1>';
                return false;
            }
        return true;
    }

    public static function aStar(ModifiedState $modifiedState): true
    {
        $validStates = $modifiedState->validGrounds();

        $validStates = array_filter($validStates, fn($validState) => self::isNotVisited($validState));

        $winState = array_filter($validStates, fn($validState) => $validState->win());
        if (!empty($winState)) {
            echo '<h1>Finish!!!</h1>';
            self::getPath(...$winState);
            return true;
        }

        foreach ($validStates as $validState) {
            self::draw([$validState]);
            self::$priorityQueue->insert($validState, -($validState->cost + $validState->heuristic()));
            self::$visited[] = $validState;
        }

        Game::$g++;
        return Game::aStar(Game::$priorityQueue->extract());
    }

    public static function getPath($state): void
    {
        $fatherKey = array_search($state->fatherState, self::$visited);
        while ($fatherKey !== false) {
            $father = self::$visited[$fatherKey];
            self::$states[] = $father;
            $fatherKey = array_search($father->fatherState, self::$visited);
        }
    }

    public static function UCS(ModifiedState $modifiedState): true
    {
        $validStates = $modifiedState->validGrounds();

        $validStates = array_filter($validStates, fn($validState) => self::isNotVisited($validState));

        $winState = array_filter($validStates, fn($validState) => $validState->win());
        if (!empty($winState)) {
            echo '<h1>Finish!!!</h1>';
            self::getPath(...$winState);
            return true;
        }

        foreach ($validStates as $validState) {
            self::draw([$validState]);
            self::$priorityQueue->insert($validState, -$validState->cost);
            self::$visited[] = $validState;
        }

        Game::$g++;
        return Game::UCS(Game::$priorityQueue->extract());
    }

    public static function hillClimbing(ModifiedState $modifiedState): true
    {
        $validStates = $modifiedState->validGrounds();

        $validStates = array_filter($validStates, fn($validState) => self::isNotVisited($validState));

        $winState = array_filter($validStates, fn($validState) => $validState->win());
        if (!empty($winState)) {
            echo '<h1>Finish!!!</h1>';
            self::getPath(...$winState);
            return true;
        }

        foreach ($validStates as $validState) {
            self::draw([$validState]);
            self::$priorityQueue->insert($validState, -$validState->heuristic());
            self::$visited[] = $validState;
        }

        Game::$g++;
        return Game::hillClimbing(Game::$priorityQueue->extract());
    }

    public static function BFS(State $state): void
    {
        self::$visited[] = $state;

        if ($state->win()) {
            echo '<h1>Finish!!!</h1>';
            array_pop(self::$states);
            return;
        }

        if ($state->lose()) {
            echo '<h1>Lost</h1>';
            array_pop(self::$states);
            return;
        }

        $validStates = $state->validGrounds();

        $lost = array_filter($validStates, fn($value) => $value->lose());
        if (count($lost) === count($validStates) && count($lost) !== 0) {
            array_pop(self::$states);
            self::$loses[] = $state;
            echo '<h1>All Lost</h1>';
            return;
        }

        foreach ($validStates as $validState) {
            if (!self::isNotVisited($validState))
                continue;
            self::draw([$validState]);
            self::$queue->enqueue($validState);
        }

        echo '<h1>BFS </h1>';
        while (!self::$queue->isEmpty()) {
            self::$g++;
            $newState = self::$queue->dequeue();
            self::$states[] = $newState;
            self::BFS($newState);
        }
    }
}
