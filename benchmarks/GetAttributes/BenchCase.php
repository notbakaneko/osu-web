<?php

namespace Benchmarks\GetAttributes;

use App\Models\User;
use Benchmarks\UserBenchCase;

/**
 * @Groups({"user-accessor"}, extend=true)
 */
abstract class BenchCase extends UserBenchCase
{
    public function getSubject()
    {
        return User::first();
    }

    public function benchIsBannedMethod()
    {
        $this->subject->isBanned();
    }

    public function benchIsBanned()
    {
        $this->subject->getAttributes()['user_type'] === 1;
    }

    public function benchReadUsername()
    {
        $this->subject->getAttributes()['username'];
    }

    public function benchReadUserId()
    {
        $this->subject->getAttributes()['user_id'];
    }

    public function benchReadKeys()
    {
        foreach ($this->keys as $key) {
            $this->subject->getAttributes()[$key];
        }
    }
}
