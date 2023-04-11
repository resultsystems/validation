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

        for ($i = 0, $n = 0; $i < 12; $n += $value[$i] * $b[++$i]) ;

        if ((int) $value[12] !== (int) ((($n %= 11) < 2) ? 0 : 11 - $n)) {
            return false;
        }

        for ($i = 0, $n = 0; $i <= 12; $n += $value[$i] * $b[$i++]) ;

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
    public function validateCnpjMask($attribute, $value, $parameters)
    {
        return $this->validateCnpj($attribute, $this->clearCountryRegistry($value), $parameters);
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
        $cpf = $value;
        if (preg_match("/^{$cpf[0]}{11}$/", $cpf)) {
            return false;
        }
        $generate_cpf = $this->cpfAddDigits(substr($cpf, 0, 9));

        return $generate_cpf === $cpf;
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
    public function validateCpfMask($attribute, $value, $parameters)
    {
        return $this->validateCpf($attribute, $this->clearCountryRegistry($value), $parameters);
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
     * Verifica se o tamanho pode ser um CNPJ.
     *
     * @param string $value
     *
     * @return bool
     */
    public function isCnpj($value = '')
    {
        $value = $this->clearCountryRegistry($value);

        return strlen($value) > 11;
    }

    private function clearCountryRegistry(string $document)
    {
        return preg_replace('/[^0-9]/', '', $document);
    }

    private function cpfAddDigits(string $digits)
    {
        $digitVerify1 = $this->calculateCpfDigit($digits);
        $digitVerify2 = $this->calculateCpfDigit("{$digits}{$digitVerify1}");

        return $digits . $digitVerify1 . $digitVerify2;
    }

    private function calculateCpfDigit(string $digits)
    {
        $array_digits  = str_split($digits);
        $reverse_index = count($array_digits) + 1;
        array_walk($array_digits, function (&$value, $key) use ($reverse_index) {
            return $value = (int) $value * ($reverse_index - $key);
        });
        $sum_digits = array_sum($array_digits);
        $rest       = $sum_digits % 11;

        return $rest < 2 ? 0 : 11 - $rest;
    }
}
