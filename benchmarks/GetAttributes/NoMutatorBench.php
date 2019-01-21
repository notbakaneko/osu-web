<?php

namespace Benchmarks\GetAttributes;

use Benchmarks\UserNoMutator;

class NoMutatorBench extends BenchCase
{
    public function getSubject()
    {
        return UserNoMutator::first();
    }
}
