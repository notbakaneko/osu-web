<?php

namespace Benchmarks\GetAttribute;

use Benchmarks\UserNoCast;

class GetAttributeNoCastBench extends GetAttributeBenchCase
{
    public function getSubject()
    {
        return UserNoCast::first();
    }
}
