<?php

namespace Benchmarks\MagicGet;

use App\Models\User;
use Benchmarks\BenchCase as BaseBenchCase;

abstract class BenchCase extends BaseBenchCase
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
        return $this->subject->user_type === 1;
    }

    public function benchReadUsername()
    {
        $this->subject->username;
    }

    public function benchReadUserId()
    {
        $this->subject->user_id;
    }

    public function benchReadKeys()
    {
        foreach ($this->keys as $key) {
            $this->subject->$key;
        }
    }
}
