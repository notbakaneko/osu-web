<?php

namespace Benchmarks\GetAttribute;

use Benchmarks\UserNoCast;

class NoCastBench extends BenchCase
{
    public function getSubject()
    {
        return UserNoCast::first();
    }
}
