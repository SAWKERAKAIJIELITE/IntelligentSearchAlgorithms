<?php

declare(strict_types=1);

namespace src;

class State
{
    public const UP = [-1, 0];

    public const DOWN = [1, 0];

    public const RIGHT = [0, 1];

    public const LEFT = [0, -1];

    public array $ground;

    public function __construct(
        public int   $n,
        public int   $m,
        public int   $players = 1,
        public array $currents = [[0, 0]],
        public int   $cost = 0,
        public int   $max = 0
    )
    {
    }

    /**
     * @return array
     * @todo Try to make it static
     */
    public function generateRandomGround(): array
    {
        for ($i = 0; $i < $this->n; $i++)
            for ($j = 0; $j < $this->m; $j++)
                $c[$i][$j] = (!in_array([$i, $j], $this->currents)) ? rand(-1, 3) : rand(0, 3);
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
        $position = $this->getPosition($player, $direction);
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

    public function getPosition(int $player, array $direction): array
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
        return $position;
    }

    /**
     * Summary of move
     *
     * @param int $player
     * @param array $direction
     * @return State
     * @todo try to use cloning
     *
     */
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
        $nextState = new State($this->n, $this->m, $this->players, $newCurrent, $this->calcNewCost($position));
        $nextState->ground = $this->ground;
        $nextState->ground[$position[0]][$position[1]]--;
        $nextState->max = max(array_merge(...$nextState->ground));
        return $nextState;
    }

    public function calcNewCost(array $position): int
    {
        return $this->cost + $this->max - $this->ground[$position[0]][$position[1]] + 1;
    }

    public function putPlayers(): void
    {
        if (count($this->currents) === $this->players)
            return;
        if (count($this->currents) > $this->players) {
            $this->currents = array_slice($this->currents, 0, $this->players);
            return;
        }
        $x = rand(0, $this->n - 1);
        $y = rand(0, $this->m - 1);
        if (!in_array([$x, $y], $this->currents)) {
            $this->currents[] = [$x, $y];
            $this->putPlayers();
        } else
            $this->putPlayers();
    }

    /**
     * @return bool
     */
    public function lose(): bool
    {
        return !$this->win() && $this->isFinal();
    }

    public function win(): bool
    {
        for ($i = 0; $i < $this->n; $i++) {
            for ($j = 0; $j < $this->m; $j++) {
                if (!in_array($this->ground[$i][$j], [0, -1]))
                    return false;
            }
        }
        return true;
    }

    public function isFinal(): bool
    {
        return !in_array(true, $this->playersCanMove(), true);
    }

    public function playersCanMove(): array
    {
        for ($i = 0; $i < $this->players; $i++)
            $playersCanMove[] = $this->playerCanMove($i);
        return $playersCanMove;
    }

    public function playerCanMove(int $player): bool
    {
        return $this->canMove($player, State::UP) ||
            $this->canMove($player, State::DOWN) ||
            $this->canMove($player, State::RIGHT) ||
            $this->canMove($player, State::LEFT);
    }

    /**
     * @param State $state
     * @return bool
     */
    public function isEqualTo(State $state): bool
    {
        return $this->countPlayersNotInSamePositions($state) === 0 &&
            $this->ground === $state->ground;
    }

    public function countPlayersNotInSamePositions(State $state): int
    {
        return count(
            array_filter(
                $this->currents,
                fn($value) => !in_array($value, $state->currents)
            )
        );
    }
}
