<?php

namespace Benchmarks\Attribute;

use Benchmarks\BenchCase as BaseBenchCase;

class AttributeOverheadBench extends BaseBenchCase
{
    /**
     * @return Benchmarks\Attribute\Model
     */
    public function getSubject()
    {
        return new Model([
            'test' => 'value',
        ]);
    }

    public function benchAttribute()
    {
        $this->subject->test;
    }

    public function benchMethodDirect()
    {
        return $this->subject->getTestAttribute($this->subject->getAttributes()['test']);
    }
}
