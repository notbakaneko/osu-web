<?php

namespace Benchmarks\MagicGet;

use Benchmarks\UserNoCast;

class MagicGetNoCastBench extends MagicGetBenchCase
{
    public function getSubject()
    {
        return UserNoCast::first();
    }
}
