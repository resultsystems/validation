<?php

namespace ResultSystems\Validation\Traits;

trait Uuid
{
    /**
     * Validate uuid
     * by @ericson.
     *
     * @param string $attribute
     * @param mixed  $value
     * @param mixed  $parameters
     *
     * @return bool
     */
    protected function validateUuid($attribute, $value, $parameters)
    {
        return 1 === preg_match('/^[[:xdigit:]]{8}\-[[:xdigit:]]{4}\-[[:xdigit:]]{4}\-[[:xdigit:]]{4}\-[[:xdigit:]]{12}$/', $value);
    }
}
