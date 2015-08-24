<?php

namespace ResultSystems\Validation;

use KennedyTedesco\Validation\Validator as TedescoValidator;

class Validator extends TedescoValidator
{
    /**
     * valida hora
     * @param  string $attribute
     * @param  string $value
     * @param  string $parameters
     *
     * @return bool
     */
    public function validateHora($attribute, $value, $parameters)
    {
        return preg_match('/^([0-9]|0[0-9]|1[0-9]|2[0-3]):[0-5][0-9]$/', $value, $matches);
    }

    /**
     * valida Telefone
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
     * valida Telefone com mascara
     * @param  string $attribute
     * @param  string $value
     * @param  string $parameters
     *
     * @return bool
     */
    public function validateTelefoneMascara($attribute, $value, $parameters)
    {
        return $this->validateTelefone($attribute, str_replace(array(' ', '/', '-'), '', $value), $parameters);
    }

    /**
     * valida Celular
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
     * valida Celular com mascara
     * @param  string $attribute
     * @param  string $value
     * @param  string $parameters
     *
     * @return bool
     */
    public function validateCelularMascara($attribute, $value, $parameters)
    {
        return $this->validateCelular($attribute, str_replace(array(' ', '/', '-'), '', $value), $parameters);
    }

    /**
     * Valida Cnpj
     * @param  string $attribute
     * @param  string $value
     * @param  string $parameters
     * @return bool
     */
    public function validateCnpj($attribute, $value, $parameters)
    {
        // Code ported from Respect\Validation\Rules\Cnpj
        //        $value = preg_replace('/\D/', '', $input);
        $b = array(6, 5, 4, 3, 2, 9, 8, 7, 6, 5, 4, 3, 2);

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
     * Valida Cnpj com mascara
     * @param  string $attribute
     * @param  string $value
     * @param  string $parameters
     * @return bool
     */
    public function validateCnpjMascara($attribute, $value, $parameters)
    {
        return $this->validateCnpj($attribute, preg_replace('/\D/', '', $input), $parameters);
    }

    /**
     * Valida Cpf
     * @param  string $attribute
     * @param  string $value
     * @param  string $parameters
     * @return bool
     */
    public function validateCpf($attribute, $value, $parameters)
    {
        // Code ported from Respect\Validation\Rules\Cpf
        //        $value = preg_replace('/\D/', '', $input);
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
     * Valida Cpf com Mascara
     * @param  string $attribute
     * @param  string $value
     * @param  string $parameters
     * @return bool
     */
    public function validateCpfMascara($attribute, $value, $parameters)
    {
        return $this->validateCpf($attribute, preg_replace('/\D/', '', $input), $parameters);
    }

    /**
     * valida CPF/CNPJ
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
     * Valida Cnpj/Cpf com Mascara
     * @param  string $attribute
     * @param  string $value
     * @param  string $parameters
     * @return bool
     */
    public function validateCnpjCpfMascara($attribute, $value, $parameters)
    {
        return $this->validateCnpjCpf($attribute, preg_replace('/\D/', '', $input), $parameters);
    }

    /**
     * valida CPF/CNPJ possibilitando ter números zerados
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
     * Valida Cnpj/Cpf com Mascara (tudo zero)
     * @param  string $attribute
     * @param  string $value
     * @param  string $parameters
     * @return bool
     */
    public function validateCnpjCpfZeroMascara($attribute, $value, $parameters)
    {
        return $this->validateCnpjCpfZero($attribute, preg_replace('/\D/', '', $input), $parameters);
    }

    /**
     * Verifica se o tamanho pode ser um CNPJ
     *
     * @param  string  $value
     *
     * @return boolean
     */
    public function isCnpj($value = '')
    {
        return (strlen($value) > 11);
    }
}
