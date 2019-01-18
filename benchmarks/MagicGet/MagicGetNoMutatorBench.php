<?php

namespace Benchmarks\MagicGet;

use Benchmarks\UserNoMutator;

class MagicGetNoMutatorBench extends MagicGetBenchCase
{
    public function getSubject()
    {
        return UserNoMutator::first();
    }
}
