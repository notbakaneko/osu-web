<?php

namespace Benchmarks\Attribute;

use Benchmarks\BenchCase as BaseBenchCase;

abstract class BenchCase extends BaseBenchCase
{
    public function benchAttribute()
    {
        $this->subject->test;
    }

    public function benchAttributeMultiple()
    {
        $this->subject->test;
        $this->subject->test;
        $this->subject->test;
        $this->subject->test;
        $this->subject->test;
    }

    public function benchMethodDirect()
    {
        $this->subject->getTestAttribute($this->subject->getAttributes()['test']);
    }

    public function benchMethodDirectMultiple()
    {
        $this->subject->getTestAttribute($this->subject->getAttributes()['test']);
        $this->subject->getTestAttribute($this->subject->getAttributes()['test']);
        $this->subject->getTestAttribute($this->subject->getAttributes()['test']);
        $this->subject->getTestAttribute($this->subject->getAttributes()['test']);
        $this->subject->getTestAttribute($this->subject->getAttributes()['test']);
    }

    public function benchMethodDirectMultipleReuse()
    {
        $attributes = $this->subject->getAttributes();
        $this->subject->getTestAttribute($attributes['test']);
        $this->subject->getTestAttribute($attributes['test']);
        $this->subject->getTestAttribute($attributes['test']);
        $this->subject->getTestAttribute($attributes['test']);
        $this->subject->getTestAttribute($attributes['test']);
    }

    public function benchGetterMethod()
    {
        $this->subject->getTest();
    }

    public function benchGetterMethodMultiple()
    {
        $this->subject->getTest();
        $this->subject->getTest();
        $this->subject->getTest();
        $this->subject->getTest();
        $this->subject->getTest();
    }
}
