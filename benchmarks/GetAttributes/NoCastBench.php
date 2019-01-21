<?php

namespace Benchmarks\GetAttributes;

use Benchmarks\UserNoCast;

class NoCastBench extends BenchCase
{
    public function getSubject()
    {
        return UserNoCast::first();
    }
}
