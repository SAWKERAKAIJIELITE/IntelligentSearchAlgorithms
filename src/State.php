<?php

declare(strict_types=1);

namespace src;

class State
{
    const UP = [-1, 0];

    const DOWN = [1, 0];

    const RIGHT = [0, 1];

    const LEFT = [0, -1];

    public array $ground;

    public array $simpleGround;

    public function __construct(
        public int $n,
        public int $m,
        public int $players = 1,
        public array $currents = [[0, 0]]
    ) {

    }

    /**
     * @todo Try to make it static
     *
     * @return array
     */
    public function generateRandomGround(): array
    {
        for ($i = 0; $i < $this->n; $i++) {
            for ($j = 0; $j < $this->m; $j++) {
                $c[$i][$j] = (!in_array([$i, $j], $this->currents)) ? rand(-1, 3) : rand(0, 3);
            }
        }
        return $c;
    }

    public function validGrounds(): array
    {
        $validGrounds = [];
        for ($i = 0; $i < $this->players; $i++) {
            if ($this->canMove($i, State::UP)) {
                $validGrounds[] = $this->move($i, State::UP);
            }
            if ($this->canMove($i, State::DOWN)) {
                $validGrounds[] = $this->move($i, State::DOWN);
            }
            if ($this->canMove($i, State::RIGHT)) {
                $validGrounds[] = $this->move($i, State::RIGHT);
            }
            if ($this->canMove($i, State::LEFT)) {
                $validGrounds[] = $this->move($i, State::LEFT);
            }
        }
        return $validGrounds;
    }

    public function canMove(int $player, array $direction): bool
    {
        $position = $this->currents[$player];

        switch ($direction) {
            case State::UP:
                $position[0]--;
                break;
            case State::DOWN:
                $position[0]++;
                break;
            case State::RIGHT:
                $position[1]++;
                break;
            case State::LEFT:
                $position[1]--;
                break;
        }
        if (
            in_array(-1, $position) ||
            $position[0] >= $this->n ||
            $position[1] >= $this->m ||
            in_array($this->ground[$position[0]][$position[1]], [0, -1]) ||
            in_array($position, $this->currents)
        )
            return false;
        else
            return true;
    }

    public function move(int $player, array $direction): self
    {
        $newCurrent = $this->currents;
        switch ($direction) {
            case State::UP:
                $newCurrent[$player][0]--;
                break;
            case State::DOWN:
                $newCurrent[$player][0]++;
                break;
            case State::RIGHT:
                $newCurrent[$player][1]++;
                break;
            case State::LEFT:
                $newCurrent[$player][1]--;
                break;
        }
        $position = $newCurrent[$player];
        $nextState = new State($this->n, $this->m, $this->players, $newCurrent);
        $nextState->ground = $this->ground;
        $nextState->ground[$position[0]][$position[1]]--;
        return $nextState;
    }

    public function putPlayers(): void
    {
        if (count($this->currents) == $this->players)
            return;
        $x = rand(0, $this->n - 1);
        $y = rand(0, $this->m - 1);
        if (!in_array([$x, $y], $this->currents)) {
            $this->currents[] = [$x, $y];
        } else
            $this->putPlayers();
    }

    public function isFinal(): bool
    {
        for ($i = 0; $i < $this->n; $i++) {
            for ($j = 0; $j < $this->m; $j++) {
                if (!in_array($this->ground[$i][$j], [0, -1]))
                    return false;
            }
        }
        return true;
    }

    public function isEqualTo(State $state): bool
    {
        $c = count(
            array_filter(
                $this->currents,
                fn($value) => array_search($value, $state->currents) === false
            )
        );
        return $this->ground === $state->ground && $c === 0 ? true : false;
    }

    public function generateSimpleGround(array $ground, array $num): array
    {
        // for ($i = 0; $i < $this->n; $i++) {
        //     $ground[$i] = array_map(
        //         function () use ($ground, $i) {
        //             print_r($ground[$i]);
        //             // if (in_array($g, $num)) {
        //             //     print $g;
        //             //     // $g = null;
        //             //     // return $g;
        //             // }
        //         },
        //         $ground
        //     );
        //     // $c[] = array_filter($ground[$i], fn($value) => !in_array($value, $num));
        //     // print_r($c);
        // }
        // $c = array_map(function ($value) use ($num) {
        //     for ($i = 0; $i < $this->m; $i++) {
        //         if (in_array($value[$i], $num))
        //             // print($value[$i]);
        //             $value[$i] = null;
        //     }
        //     // print_r($value);
        // }, $ground);
        // echo '<pre>';
        // var_dump($c);
        // echo '</pre>';
        return $ground;
    }
}