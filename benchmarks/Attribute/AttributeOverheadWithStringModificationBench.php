<?php

namespace Benchmarks\Attribute;

class AttributeOverheadWithStringModificationBench extends BenchCase
{
    public function getSubject()
    {
        return new ModelWithStringModification([
            'test' => 'value',
        ]);
    }
}
