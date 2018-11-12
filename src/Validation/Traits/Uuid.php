<?php

namespace ResultSystems\Validation\Traits;

trait Uuid
{
    /**
     * Validate that an attribute is a valid UUID.
     *
     * @param  string  $attribute
     * @param  mixed  $value
     * @return bool
     */
    public function validateUuid($attribute, $value)
    {
        if (!is_string($value)) {
            return false;
        }

        return preg_match('/^[\da-f]{8}-[\da-f]{4}-[\da-f]{4}-[\da-f]{4}-[\da-f]{12}$/iD', $value) > 0;
    }
}
