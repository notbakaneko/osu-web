<?php

namespace Benchmarks\Attribute;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model as BaseModel;

/**
 * This model simulates getting attributes that do some kind of conversion on a value.
 */
class ModelWithStringModification extends BaseModel
{
    protected $guarded = [];

    public function getTestAttribute($value)
    {
        if (present($value)) {
            return "#{$value}";
        }
    }

    public function getTest()
    {
        $value = $this->attributes['test'] ?? null;
        if (present($value)) {
            return "#{$value}";
        }
    }
}
