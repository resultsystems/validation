<?php

namespace ResultSystems\Validation;

use Illuminate\Validation\Validator as BaseValidator;
use Symfony\Component\Translation\TranslatorInterface;

class Validator extends BaseValidator
{
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
     * Create a new Validator instance.
     *
     * @param  \Symfony\Component\Translation\TranslatorInterface  $translator
     * @param  array  $data
     * @param  array  $rules
     * @param  array  $messages
     * @param  array  $customAttributes
     */
    public function __construct(TranslatorInterface $translator, array $data, array $rules, array $messages = [], array $customAttributes = [])
    {
        parent::__construct($translator, $data, $rules, $messages, $customAttributes);
        $this->_validRules = $this->getValidRules();
    }

    /**
     * Verify is Implicit rule and add implicit extensions.
     *
     * @param string
     *
     * @return array
     */
    protected function isImplicit($rule)
    {
        return in_array($rule, array_merge($this->addImplicitExtension, $this->implicitRules));
    }

    /**
     * Validate that an attribute exists when another attribute has a given value.
     *
     * @param  string  $attribute
     * @param  mixed   $value
     * @param  mixed   $parameters
     *
     * @return bool
     */
    protected function validateRequiredIfNot($attribute, $value, $parameters)
    {
        $this->requireParameterCount(2, $parameters, 'required_if_not');

        $data = Arr::get($this->data, $parameters[0]);

        $values = array_slice($parameters, 1);

        if (!in_array($data, $values)) {
            return $this->validateRequired($attribute, $value);
        }

        return true;
    }

    /**
     * Replace all place-holders for the required_if rule.
     *
     * @param  string  $message
     * @param  string  $attribute
     * @param  string  $rule
     * @param  array   $parameters
     *
     * @return string
     */
    protected function replaceRequiredIfNot($message, $attribute, $rule, $parameters)
    {
        $values = implode(',', array_slice($parameters, 1));
        $other  = $parameters[0];

        $message = str_replace(':other', $other, $message);
        $message = str_replace(':value', $values, $message);

        return $message;
    }

    /**
     * valida hora.
     * @param  string $attribute
     * @param  string $value
     * @param  string $parameters
     *
     * @return bool
     */
    public function validateHora($attribute, $value, $parameters)
    {
        return preg_match('/^([0-9]|0[0-9]|1[0-9]|2[0-3]):[0-5][0-9](:[0-5][0-9])?$/', $value, $matches);
    }

    /**
     * valida Telefone.
     * @param  string $attribute
     * @param  string $value
     * @param  string $parameters
     *
     * @return bool
     */
    public function validateTelefone($attribute, $value, $parameters)
    {
        $result = false;

        //Verifica telefones
        // 0XX XXXX-XXXX
        // XX XXXX-XXXX
        // XXXX - XXXX
        $result = preg_match('/^(0?[1-9]{2})?(([2-9][0-9]{7})|(9[0-9][0-9]{7}))$/', $value, $matches);
        if ($result) {
            return true;
        }

        //Verifica 0800
        return preg_match('/^0800([0-9]{7,8})$/', $value, $matches);
    }

    /**
     * valida Telefone com mascara.
     * @param  string $attribute
     * @param  string $value
     * @param  string $parameters
     *
     * @return bool
     */
    public function validateTelefoneMascara($attribute, $value, $parameters)
    {
        return $this->validateTelefone($attribute, str_replace([' ', '/', '-'], '', $value), $parameters);
    }

    /**
     * valida Celular.
     * @param  string $attribute
     * @param  string $value
     * @param  string $parameters
     *
     * @return bool
     */
    public function validateCelular($attribute, $value, $parameters)
    {
        $result = false;

        //Verifica telefones
        // 0XX XXXX-XXXX
        // XX XXXX-XXXX
        // XXXX - XXXX
        return preg_match('/^(0?[1-9]{2})?(([6-9][0-9]{7})|(9[0-9][0-9]{7}))$/', $value, $matches);
    }

    /**
     * valida Celular com mascara.
     * @param  string $attribute
     * @param  string $value
     * @param  string $parameters
     *
     * @return bool
     */
    public function validateCelularMascara($attribute, $value, $parameters)
    {
        return $this->validateCelular($attribute, str_replace([' ', '/', '-'], '', $value), $parameters);
    }

    /**
     * Valida Cnpj.
     * @param  string $attribute
     * @param  string $value
     * @param  string $parameters
     *
     * @return bool
     */
    public function validateCnpj($attribute, $value, $parameters)
    {
        // Code ported from Respect\Validation\Rules\Cnpj
        //        $value = preg_replace('/\D/', '', $value);
        $b = [6, 5, 4, 3, 2, 9, 8, 7, 6, 5, 4, 3, 2];

        if (strlen($value) != 14) {
            return false;
        }

        for ($i = 0, $n = 0; $i < 12; $n += $value[$i] * $b[++$i]);

        if ($value[12] != ((($n %= 11) < 2) ? 0 : 11 - $n)) {
            return false;
        }

        for ($i = 0, $n = 0; $i <= 12; $n += $value[$i] * $b[$i++]);

        if ($value[13] != ((($n %= 11) < 2) ? 0 : 11 - $n)) {
            return false;
        }

        return true;
    }

    /**
     * Valida Cnpj com mascara.
     * @param  string $attribute
     * @param  string $value
     * @param  string $parameters
     *
     * @return bool
     */
    public function validateCnpjMascara($attribute, $value, $parameters)
    {
        return $this->validateCnpj($attribute, preg_replace('/\D/', '', $value), $parameters);
    }

    /**
     * Valida Cpf.
     * @param  string $attribute
     * @param  string $value
     * @param  string $parameters
     *
     * @return bool
     */
    public function validateCpf($attribute, $value, $parameters)
    {
        // Code ported from Respect\Validation\Rules\Cpf
        //        $value = preg_replace('/\D/', '', $value);
        if (strlen($value) != 11 || preg_match("/^{$value[0]}{11}$/", $value)) {
            return false;
        }

        for ($s = 10, $n = 0, $i = 0; $s >= 2; $n += $value[$i++] * $s--);

        if ($value[9] != ((($n %= 11) < 2) ? 0 : 11 - $n)) {
            return false;
        }

        for ($s = 11, $n = 0, $i = 0; $s >= 2; $n += $value[$i++] * $s--);

        if ($value[10] != ((($n %= 11) < 2) ? 0 : 11 - $n)) {
            return false;
        }

        return true;
    }

    /**
     * Valida Cpf com Mascara.
     * @param  string $attribute
     * @param  string $value
     * @param  string $parameters
     *
     * @return bool
     */
    public function validateCpfMascara($attribute, $value, $parameters)
    {
        return $this->validateCpf($attribute, preg_replace('/\D/', '', $value), $parameters);
    }

    /**
     * valida CPF/CNPJ.
     * @param  string $attribute
     * @param  string $value
     * @param  string $parameters
     *
     * @return bool
     */
    public function validateCnpjCpf($attribute, $value, $parameters)
    {
        if ($this->isCnpj($value)) {
            return $this->validateCnpj($attribute, $value, $parameters);
        }

        return $this->validateCpf($attribute, $value, $parameters);
    }

    /**
     * Valida Cnpj/Cpf com Mascara.
     * @param  string $attribute
     * @param  string $value
     * @param  string $parameters
     *
     * @return bool
     */
    public function validateCnpjCpfMascara($attribute, $value, $parameters)
    {
        return $this->validateCnpjCpf($attribute, preg_replace('/\D/', '', $value), $parameters);
    }

    /**
     * valida CPF/CNPJ possibilitando ter números zerados.
     * @param  string $attribute
     * @param  string $value
     * @param  string $parameters
     *
     * @return bool
     */
    public function validateCnpjCpfZero($attribute, $value, $parameters)
    {
        if ($value == '00000000000' || $value == '00000000000000') {
            return true;
        }

        return $this->validateCnpjCpf($attribute, $value, $parameters);
    }

    /**
     * Valida Cnpj/Cpf com Mascara (tudo zero).
     * @param  string $attribute
     * @param  string $value
     * @param  string $parameters
     *
     * @return bool
     */
    public function validateCnpjCpfZeroMascara($attribute, $value, $parameters)
    {
        return $this->validateCnpjCpfZero($attribute, preg_replace('/\D/', '', $value), $parameters);
    }

    /**
     * Verifica se o tamanho pode ser um CNPJ.
     *
     * @param  string  $value
     *
     * @return bool
     */
    public function isCnpj($value = '')
    {
        return (strlen($value) > 11);
    }

    /**
     * Copied code from  KennedyTedesco/Validation.
     *
     * Handle dynamic calls to class methods.
     *
     * @param  string  $method
     * @param  array   $parameters
     *
     * @return mixed
     */
    public function __call($method, $parameters)
    {
        $rule = lcfirst(substr($method, 8));
        if (in_array($rule, $this->_validRules)) {
            $args       = $parameters[2];
            $value      = $parameters[1];
            $ruleObject = RuleFactory::make($rule, $args);

            return $ruleObject->validate($value);
        }

        return parent::__call($method, $parameters);
    }

    /**
     * Get all supported rules from Respect.
     *
     * @return bool
     */
    protected function getValidRules()
    {
        $path = __DIR__ . '/Respect/Rules.php';

        return array_unique(require $path, SORT_REGULAR);
    }

    /**
     * Copied code from  KennedyTedesco/Validation.
     *
     * Validate a minimum age.
     *
     * @param  string  $attribute
     * @param  mixed   $value
     * @param  array   $parameters
     *
     * @return bool
     */
    public function validateMinimumAge($attribute, $value, $parameters)
    {
        $parameter = (int) $parameters[0];

        return RuleFactory::make('MinimumAge', [$parameter])->validate($value);
    }

    /**
     * Copied code from  KennedyTedesco/Validation.
     *
     * Validate if file exists.
     *
     * @param  string  $attribute
     * @param  mixed   $value
     * @param  array   $parameters
     *
     * @return bool
     */
    public function validateFileExists($attribute, $value, $parameters)
    {
        return RuleFactory::make('exists', [])->validate($value);
    }

    /**
     * Copied code from  KennedyTedesco/Validation.
     *
     * Replace all place-holders for the MinimumAge rule.
     *
     * @param  string  $message
     * @param  string  $attribute
     * @param  string  $rule
     * @param  array   $parameters
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
     * @param  string  $message
     * @param  string  $attribute
     * @param  string  $rule
     * @param  array   $parameters
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
     * @param  string  $message
     * @param  string  $attribute
     * @param  string  $rule
     * @param  array   $parameters
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
     * @param  string  $message
     * @param  string  $attribute
     * @param  string  $rule
     * @param  array   $parameters
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
     * @param  string  $message
     * @param  string  $attribute
     * @param  string  $rule
     * @param  array   $parameters
     * @return string
     */
    protected function replaceMultiple($message, $attribute, $rule, $parameters)
    {
        return str_replace(':value', $parameters[0], $message);
    }
}
