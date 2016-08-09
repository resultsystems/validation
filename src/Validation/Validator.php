<?php

namespace ResultSystems\Validation;

use Exception;
use Illuminate\Validation\Validator as BaseValidator;

class Validator extends BaseValidator
{
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
     * Replace all error message place-holders with actual values.
     *
     * @param  string  $message
     * @param  string  $attribute
     * @param  string  $rule
     * @param  array   $parameters
     * @return string
     */
    protected function doReplacements($message, $attribute, $rule, $parameters)
    {
        $message = parent::doReplacements($message, $attribute, $rule, $parameters);
        $search = [];
        foreach ($parameters as $key => $parameter) {
            array_push($search, ':parameter'.$key);
        }

        return str_replace($search, $parameters, $message);
    }

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
        $other = $parameters[0];

        $message = str_replace(':other', $other, $message);
        $message = str_replace(':value', $values, $message);

        return $message;
    }

    /**
     * Valid time.
     * @param  string $attribute
     * @param  string $value
     * @param  string $parameters
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

    /**
     * valida Telefone.
     * @param  string $attribute
     * @param  string $value
     * @param  string $parameters
     *
     * @return bool
     */
    public function validatePhoneBr($attribute, $value, $parameters)
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

    public function validateTelefone($attribute, $value, $parameters)
    {
        return $this->validatePhoneBr($attribute, $value, $parameters);
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
    public function validateCellphoneBr($attribute, $value, $parameters)
    {
        $result = false;

        //Verifica telefones
        // 0XX XXXX-XXXX
        // XX XXXX-XXXX
        // XXXX - XXXX
        return preg_match('/^(0?[1-9]{2})?(([6-9][0-9]{7})|(9[0-9][0-9]{7}))$/', $value, $matches);
    }

    public function validateCelular($attribute, $value, $parameters)
    {
        return $this->validateCellphoneBr($attribute, $value, $parameters);
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
     * Get all supported rules from Respect.
     *
     * @return bool
     */
    protected function getValidRules()
    {
        $path = __DIR__.'/Respect/Rules.php';

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
     * Based by code from  https://gist.github.com/paulofreitas/4704673.
     *
     * Validate if file exists.
     *
     * @param  string  $attribute
     * @param  mixed   $value
     * @param  array   $parameters
     *
     * @return bool
     */
    public function validateTituloEleitoral($attribute, $value, $parameters)
    {
        // Canonicalize input and parse UF
        $te = sprintf('%012s', $value);
        $uf = intval(substr($value, 8, 2));
        // Validate length and invalid UFs
        if ((strlen($te) != 12)
            || ($uf < 1)
            || ($uf > 28)) {
            return false;
        }
        // Validate check digits using a slightly modified modulus 11 algorithm
        foreach ([7, 8 => 10] as $s => $t) {
            for ($d = 0, $p = 2, $c = $t; $c >= $s; $c--, $p++) {
                $d += $te[$c] * $p;
            }
            if ($te[($s) ? 11 : 10] != ((($d %= 11) < 2) ? (($uf < 3) ? 1 - $d
                : 0)
                : 11 - $d)) {
                return false;
            }
        }

        return true;
    }

    /**
     * Based by code from  https://gist.github.com/paulofreitas/4704673.
     *
     * Validate if file exists.
     *
     * @param  string  $attribute
     * @param  mixed   $value
     * @param  array   $parameters
     *
     * @return bool
     */
    public function validateNis($attribute, $value, $parameters)
    {
        // Canonicalize input
        $nis = sprintf('%011s', $value);
        // Validate length and invalid numbers
        if ((strlen($nis) != 11)
            || (intval($nis) == 0)) {
            return false;
        }
        // Validate check digit using a modulus 11 algorithm
        for ($d = 0, $p = 2, $c = 9; $c >= 0; $c--, ($p < 9) ? $p++ : $p = 2) {
            $d += $nis[$c] * $p;
        }

        return ($nis[10] == (((10 * $d) % 11) % 10));
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
