<?php

namespace Benchmarks;

use App\Models\User;

class UserNoMutator extends User
{
    public function getAttributeValue($key)
    {
        $value = $this->getAttributeFromArray($key);

        if ($this->hasCast($key)) {
            return $this->castAttribute($key, $value);
        }

        if (in_array($key, $this->getDates()) &&
            ! is_null($value)) {
            return $this->asDateTime($value);
        }

        return $value;
    }
}
