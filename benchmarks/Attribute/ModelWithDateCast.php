<?php

namespace Benchmarks\Attribute;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model as BaseModel;

/**
 * This model simulates getting attributes that have to date cast a value.
 */
class ModelWithDateCast extends BaseModel
{
    protected $guarded = [];

    public function getTestAttribute($value)
    {
        return Carbon::createFromTimestamp($value);
    }

    public function getTest()
    {
        return Carbon::createFromTimestamp($this->attributes['test']);
    }
}
