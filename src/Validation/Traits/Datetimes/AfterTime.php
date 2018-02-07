<?php

namespace ResultSystems\Validation\Traits\Datetimes;

use Illuminate\Support\Arr;

trait AfterTime
{
    /**
     * Valid after time.
     *
     * @param string $attribute
     * @param string $value
     * @param string $parameters
     *
     * @return bool
     */
    public function validateAfterTime($attribute, $value, $parameters)
    {
        $this->requireParameterCount(1, $parameters, 'min_time');

        $valid = $this->validateTime($attribute, $value, $parameters);

        if (!$valid) {
            return false;
        }

        $other = Arr::get($this->data, $parameters[0]);

        $time = $this->validateTime($parameters[0], $other, []);

        if (!$time) {
            return false;
        }

        return $value > $other;
    }

    /**
     * Replace all params the AfterTime rule.
     *
     * @param string $message
     * @param string $attribute
     * @param string $rule
     * @param array  $parameters
     *
     * @return string
     */
    protected function replaceAfterTime($message, $attribute, $rule, $parameters)
    {
        $other = $this->getDisplayableAttribute($parameters[0]);

        return str_replace(':other', $other, $message);
    }
}
