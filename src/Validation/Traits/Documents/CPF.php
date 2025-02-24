<?php

namespace ResultSystems\Validation\Traits\Documents;

trait CPF
{
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
        if (11 !== strlen($value)) {
            return false;
        }

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
