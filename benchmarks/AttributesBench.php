<?php

namespace Benchmarks;

use Benchmarks\OverridenUser as User;

/**
 * @Groups({"attributes"}, extend=true)
 */
class AttributesBench extends BenchCase
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
        return $this->subject->getAttributes()['user_type'] === 1;
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
