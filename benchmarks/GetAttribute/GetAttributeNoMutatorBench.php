<?php

namespace Benchmarks\GetAttribute;

use Benchmarks\UserNoMutator;

class GetAttributeNoMutatorBench extends GetAttributeBenchCase
{
    public function getSubject()
    {
        return UserNoMutator::first();
    }
}
