<?php

namespace Benchmarks\Attribute;

class AttributeOverheadBench extends BenchCase
{
    public function getSubject()
    {
        return new Model([
            'test' => 'value',
        ]);
    }
}
