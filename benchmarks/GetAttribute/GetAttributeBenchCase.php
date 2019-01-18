<?php

namespace Benchmarks\GetAttribute;

use App\Models\User;
use Benchmarks\BenchCase;

abstract class GetAttributeBenchCase extends BenchCase
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
        $this->subject->getAttribute('user_type') === 1;
    }

    public function benchReadUsername()
    {
        $this->subject->getAttribute('username');
    }

    public function benchReadUserId()
    {
        $this->subject->getAttribute('user_id');
    }

    public function benchReadKeys()
    {
        foreach ($this->keys as $key) {
            $this->subject->getAttribute($key);
        }
    }
}
