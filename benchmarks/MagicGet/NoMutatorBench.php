<?php

namespace Benchmarks\MagicGet;

use Benchmarks\UserNoMutator;

class NoMutatorBench extends BenchCase
{
    public function getSubject()
    {
        return UserNoMutator::first();
    }
}
