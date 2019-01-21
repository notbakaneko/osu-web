<?php

namespace Benchmarks;

use App\Models\User;

class UserNoCast extends User
{
    // public function getAttribute($key)
    // {
    //     if (! $key) {
    //         return;
    //     }

    //     // If the attribute exists in the attribute array or has a "get" mutator we will
    //     // get the attribute's value. Otherwise, we will proceed as if the developers
    //     // are asking for a relationship's value. This covers both types of values.
    //     if (array_key_exists($key, $this->attributes) ||
    //         $this->hasGetMutator($key)) {
    //         return $this->getAttributeValue($key);
    //     }

    //     // Here we will determine if the model base class itself contains this given key
    //     // since we don't want to treat any of those methods as relationships because
    //     // they are all intended as helper methods and none of these are relations.
    //     if (method_exists(self::class, $key)) {
    //         return;
    //     }

    //     return $this->getRelationValue($key);
    // }

    public function getAttributeValue($key)
    {
        $value = $this->getAttributeFromArray($key);

        if ($this->hasGetMutator($key)) {
            return $this->mutateAttribute($key, $value);
        }

        if (in_array($key, $this->getDates()) &&
            ! is_null($value)) {
            return $this->asDateTime($value);
        }

        return $value;
    }
}
