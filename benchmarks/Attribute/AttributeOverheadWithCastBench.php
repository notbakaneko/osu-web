<?php

namespace Benchmarks\Attribute;

class AttributeOverheadWithCastBench extends BenchCase
{
    public function getSubject()
    {
        return new ModelWithDateCast([
            'test' => 1234567890,
        ]);
    }
}
