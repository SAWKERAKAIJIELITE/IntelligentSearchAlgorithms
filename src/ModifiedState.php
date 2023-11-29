<?php
declare(strict_types=1);

namespace src;

class ModifiedState
{
    public static array $targets;

    public static array $ground;

    public static array $costs;

    public function __construct(
        public int                     $cost,
        public readonly State          $state,
        public readonly ?ModifiedState $fatherState = null,
    )
    {

    }

    public function putPlayersTargets(array $target): void
    {
        self::$targets = $target;
        if (count(self::$targets) === $this->state->players)
            return;
        if (count(self::$targets) > $this->state->players) {
            self::$targets = array_slice(self::$targets, 0, $this->state->players);
            return;
        }
        $x = rand(0, $this->state->n - 1);
        $y = rand(0, $this->state->m - 1);
        if (
            !in_array(self::$ground[$x][$y], [0, -1]) &&
            !in_array([$x, $y], self::$targets)
        ) {
            self::$targets[] = [$x, $y];
            $this->putPlayersTargets(self::$targets);
        } else
            $this->putPlayersTargets(self::$targets);
    }

    public function putPlayersCosts(array $costs): void
    {
        self::$costs = $costs;
        if (count(self::$costs) === $this->state->players)
            return;
        if (count(self::$costs) > $this->state->players) {
            self::$costs = array_slice(self::$costs, 0, $this->state->players);
            return;
        }
        $cost = rand(1, 10);
        if (!in_array($cost, self::$costs)) {
            self::$costs[] = $cost;
            $this->putPlayersCosts(self::$costs);
        } else
            $this->putPlayersCosts(self::$costs);
    }

    public function validGrounds(): array
    {
        $validGrounds = [];
        for ($i = 0; $i < $this->state->players; $i++) {
            if ($this->state->canMove($i, State::UP)) {
                $validGrounds[] = $this->move($i, State::UP);
            }
            if ($this->state->canMove($i, State::DOWN)) {
                $validGrounds[] = $this->move($i, State::DOWN);
            }
            if ($this->state->canMove($i, State::RIGHT)) {
                $validGrounds[] = $this->move($i, State::RIGHT);
            }
            if ($this->state->canMove($i, State::LEFT)) {
                $validGrounds[] = $this->move($i, State::LEFT);
            }
        }
        return $validGrounds;
    }

    public function move(int $player, array $direction): ModifiedState
    {
        $nextState = $this->state->proceedMovement($player, $direction);
        $position = $nextState->currents[$player];
        $newCost = $this->cost + self::$ground[$position[0]][$position[1]] * self::$costs[$player];
        return new self($newCost, $nextState, $this);
    }

    public function win(): bool
    {
        return in_array(self::$targets, $this->state->currents);
    }

    public function isEqualTo(ModifiedState $modifiedState): bool
    {
        return $this->state->countPlayersNotInSamePositions($modifiedState->state) === 0;
    }

    public function heuristic(): int
    {
        foreach ($this->state->currents as $current) {
            $h[] = abs($current[0] - self::$targets[0]) + abs($current[1] - self::$targets[1]);
        }
        return min($h);
    }
}
