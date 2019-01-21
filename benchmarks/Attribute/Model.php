<?php

namespace Benchmarks\Attribute;

use Illuminate\Database\Eloquent\Model as BaseModel;

class Model extends BaseModel
{
    protected $guarded = [];

    public function getTestAttribute($value)
    {
        return $value;
    }
}
