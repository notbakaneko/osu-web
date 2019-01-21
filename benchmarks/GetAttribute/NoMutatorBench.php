<?php

namespace Benchmarks\GetAttribute;

use Benchmarks\UserNoMutator;

class NoMutatorBench extends BenchCase
{
    public function getSubject()
    {
        return UserNoMutator::first();
    }
}
