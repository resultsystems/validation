<?php

namespace ResultSystems\Validation;

use Exception;
use Illuminate\Validation\Validator as BaseValidator;

class Validator extends BaseValidator
{
    use Traits\Datetimes\AfterTime;
    use Traits\Datetimes\MinTime;
    use Traits\Datetimes\Time;
    use Traits\Documents\CountryRegister;
    use Traits\Documents\Nis;
    use Traits\Documents\TituloEleitoral;
    use Traits\Phones;
    use Traits\RequiredIfNot;
    use Traits\Uuid;

    /**
     * Add Implicit Extension.
     *
     * @var array
     */
    protected $addImplicitExtension = ['RequiredIfNot'];

    /**
     * All supported rules.
     *
     * @var array
     */
    private $_validRules = [];

    /**
     * Copied code from  KennedyTedesco/Validation.
     *
     * Handle dynamic calls to class methods.
     *
     * @param string $method
     * @param array  $parameters
     *
     * @return mixed
     */
    public function __call($method, $parameters)
    {
        try {
            $rule = substr($method, 8);
            $args = $parameters[2];
            $value = $parameters[1];
            $validation = RuleFactory::make($rule, $args);

            return $validation->validate($value);
        } catch (Exception $e) {
            return parent::__call($method, $parameters);
        }
    }

    /**
     * Copied code from  KennedyTedesco/Validation.
     *
     * Replace all place-holders for the MinimumAge rule.
     *
     * @param string $message
     * @param string $attribute
     * @param string $rule
     * @param array  $parameters
     *
     * @return string
     */
    protected function replaceMinimumAge($message, $attribute, $rule, $parameters)
    {
        return str_replace(':age', $parameters[0], $message);
    }

    /**
     * Copied code from  KennedyTedesco/Validation.
     *
     * Replace all place-holders for the Contains rule.
     *
     * @param string $message
     * @param string $attribute
     * @param string $rule
     * @param array  $parameters
     *
     * @return string
     */
    protected function replaceContains($message, $attribute, $rule, $parameters)
    {
        return str_replace(':value', $parameters[0], $message);
    }

    /**
     * Copied code from  KennedyTedesco/Validation.
     *
     * Replace all place-holders for the Charset rule.
     *
     * @param string $message
     * @param string $attribute
     * @param string $rule
     * @param array  $parameters
     *
     * @return string
     */
    protected function replaceCharset($message, $attribute, $rule, $parameters)
    {
        return str_replace(':charset', $parameters[0], $message);
    }

    /**
     * Copied code from  KennedyTedesco/Validation.
     *
     * Replace all place-holders for the EndsWith rule.
     *
     * @param string $message
     * @param string $attribute
     * @param string $rule
     * @param array  $parameters
     *
     * @return string
     */
    protected function replaceEndsWith($message, $attribute, $rule, $parameters)
    {
        return str_replace(':value', $parameters[0], $message);
    }

    /**
     * Copied code from  KennedyTedesco/Validation.
     *
     * Replace all place-holders for the Multiple rule.
     *
     * @param string $message
     * @param string $attribute
     * @param string $rule
     * @param array  $parameters
     *
     * @return string
     */
    protected function replaceMultiple($message, $attribute, $rule, $parameters)
    {
        return str_replace(':value', $parameters[0], $message);
    }
}
