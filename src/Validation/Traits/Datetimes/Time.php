<?php

namespace ResultSystems\Validation\Traits\Datetimes;

trait Time
{
    /**
     * Valid time.
     *
     * @param string $attribute
     * @param string $value
     * @param string $parameters
     *
     * @return bool
     */
    public function validateTime($attribute, $value, $parameters)
    {
        return preg_match('/^([0-9]|0[0-9]|1[0-9]|2[0-3]):[0-5][0-9](:[0-5][0-9])?$/', $value, $matches);
    }

    public function validateHora($attribute, $value, $parameters)
    {
        return $this->validateTime($attribute, $value, $parameters);
    }
}
