<?php

namespace Benchmarks;

use App\Models\User;

/**
 * override of User for the benchmark where the methods being called do not use __get or __set.
 */
class OverridenUser extends User
{
    public function isBanned()
    {
        return $this->attributes['user_type'] === 1;
    }

    public function isOld()
    {
        return preg_match('/_old(_\d+)?$/', $this->attributes['username']) === 1;
    }

    public function getUsername()
    {
        return $this->attributes['username'];
    }

    public function getA($name)
    {
        return $this->attributes[$name];
    }
}
