<?php

namespace ResultSystems\Validation\Traits;

trait RequiredIfNot
{
    /**
     * Validate that an attribute exists when another attribute has a given value.
     *
     * @param string $attribute
     * @param mixed  $value
     * @param mixed  $parameters
     *
     * @return bool
     */
    protected function validateRequiredIfNot($attribute, $value, $parameters)
    {
        $this->requireParameterCount(2, $parameters, 'required_if_not');

        $data = array_get($this->data, $parameters[0]);

        $values = array_slice($parameters, 1);

        if (!in_array($data, $values, true)) {
            return $this->validateRequired($attribute, $value);
        }

        return true;
    }

    /**
     * Replace all place-holders for the required_if rule.
     *
     * @param string $message
     * @param string $attribute
     * @param string $rule
     * @param array  $parameters
     *
     * @return string
     */
    protected function replaceRequiredIfNot($message, $attribute, $rule, $parameters)
    {
        $values = implode(',', array_slice($parameters, 1));
        $other = $parameters[0];

        $message = str_replace(':other', $other, $message);
        $message = str_replace(':value', $values, $message);

        return $message;
    }
}
