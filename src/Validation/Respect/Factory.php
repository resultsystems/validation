<?php

/**
 * Copied code from  KennedyTedesco/Validation.
 */
namespace ResultSystems\Validation\Respect;

use ReflectionClass;

class Factory
{
    const RULE_PATH = 'Respect\\Validation\\Rules\\';

    public static function make($rule, array $parameters = [])
    {
        $class = self::RULE_PATH.ucfirst($rule);

        $validatorClass = new ReflectionClass($class);

        return $validatorClass->newInstanceArgs($parameters);
    }
}
