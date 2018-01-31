<?php

namespace ResultSystems\Validation\Traits\Documents;

trait CountryRegister
{
    /**
     * Valida Cnpj.
     *
     * @param string $attribute
     * @param string $value
     * @param string $parameters
     *
     * @return bool
     */
    public function validateCnpj($attribute, $value, $parameters)
    {
        // Code ported from Respect\Validation\Rules\Cnpj
        //        $value = preg_replace('/\D/', '', $value);
        $b = [6, 5, 4, 3, 2, 9, 8, 7, 6, 5, 4, 3, 2];

        if (14 !== strlen($value)) {
            return false;
        }

        for ($i = 0, $n = 0; $i < 12; $n += $value[$i] * $b[++$i]) {
        }

        if ((int) $value[12] !== (int) ((($n %= 11) < 2) ? 0 : 11 - $n)) {
            return false;
        }

        for ($i = 0, $n = 0; $i <= 12; $n += $value[$i] * $b[$i++]) {
        }

        if ((int) $value[13] !== (int) ((($n %= 11) < 2) ? 0 : 11 - $n)) {
            return false;
        }

        return true;
    }

    /**
     * Valida Cnpj com mascara.
     *
     * @param string $attribute
     * @param string $value
     * @param string $parameters
     *
     * @return bool
     */
    public function validateCnpjMascara($attribute, $value, $parameters)
    {
        return $this->validateCnpj($attribute, preg_replace('/\D/', '', $value), $parameters);
    }

    /**
     * Valida Cpf.
     *
     * @param string $attribute
     * @param string $value
     * @param string $parameters
     *
     * @return bool
     */
    public function validateCpf($attribute, $value, $parameters)
    {
        // Code ported from Respect\Validation\Rules\Cpf
        //        $value = preg_replace('/\D/', '', $value);
        if (11 !== strlen($value) || preg_match("/^{$value[0]}{11}$/", $value)) {
            return false;
        }

        for ($s = 10, $n = 0, $i = 0; $s >= 2; $n += $value[$i++] * $s--) {
        }

        if ((int) $value[9] !== (int) ((($n %= 11) < 2) ? 0 : 11 - $n)) {
            return false;
        }

        for ($s = 11, $n = 0, $i = 0; $s >= 2; $n += $value[$i++] * $s--) {
        }

        if ((int) $value[10] !== (int) ((($n %= 11) < 2) ? 0 : 11 - $n)) {
            return false;
        }

        return true;
    }

    /**
     * Valida Cpf com Mascara.
     *
     * @param string $attribute
     * @param string $value
     * @param string $parameters
     *
     * @return bool
     */
    public function validateCpfMascara($attribute, $value, $parameters)
    {
        return $this->validateCpf($attribute, preg_replace('/\D/', '', $value), $parameters);
    }

    /**
     * valida CPF/CNPJ.
     *
     * @param string $attribute
     * @param string $value
     * @param string $parameters
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
     *
     * @param string $attribute
     * @param string $value
     * @param string $parameters
     *
     * @return bool
     */
    public function validateCnpjCpfMascara($attribute, $value, $parameters)
    {
        return $this->validateCnpjCpf($attribute, preg_replace('/\D/', '', $value), $parameters);
    }

    /**
     * valida CPF/CNPJ possibilitando ter números zerados.
     *
     * @param string $attribute
     * @param string $value
     * @param string $parameters
     *
     * @return bool
     */
    public function validateCnpjCpfZero($attribute, $value, $parameters)
    {
        if ('00000000000' === (string) $value || '00000000000000' === (string) $value) {
            return true;
        }

        return $this->validateCnpjCpf($attribute, $value, $parameters);
    }

    /**
     * Valida Cnpj/Cpf com Mascara (tudo zero).
     *
     * @param string $attribute
     * @param string $value
     * @param string $parameters
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
     * @param string $value
     *
     * @return bool
     */
    public function isCnpj($value = '')
    {
        return strlen($value) > 11;
    }
}
