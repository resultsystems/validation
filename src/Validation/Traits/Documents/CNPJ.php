<?php

namespace ResultSystems\Validation\Traits\Documents;

trait CNPJ
{
    protected function cnpjCharValue(string $c): int
    {
        if (ctype_digit($c)) {
            return (int) $c;
        }
        $ascii = ord($c);
        return $ascii - 48; // A(65) -> 17, B(66)->18, etc
    }


    /**
     * Valida Cnpj.
     *
     * @param string $attribute
     * @param string $value
     * @param string $parameters
     *
     * @return bool
     */
    public function validateCnpj(string $attribute, string $value, string $parameters): bool
    {
        $value = strtoupper(preg_replace('/[^A-Z0-9]/', '', $value));

        if (!preg_match('/^[A-Z0-9]{12}[0-9]{2}$/', $value)) {
            return false;
        }

        $weights1 = [5,4,3,2,9,8,7,6,5,4,3,2];
        $weights2 = [6,5,4,3,2,9,8,7,6,5,4,3,2];

        // Converte os 12 caracteres iniciais
        $nums = array_map([$this, 'cnpjCharValue'], str_split(substr($value, 0, 12)));

        // 1º DV
        $sum = array_sum(array_map(fn ($n, $w) => $n * $w, $nums, $weights1));
        $r = $sum % 11;
        $d1 = ($r < 2) ? 0 : 11 - $r;
        if ((int)$value[12] !== $d1) {
            return false;
        }

        // Inclui o primeiro DV e calcula o segundo
        $nums[] = $d1;
        $sum = array_sum(array_map(fn ($n, $w) => $n * $w, $nums, $weights2));
        $r = $sum % 11;
        $d2 = ($r < 2) ? 0 : 11 - $r;
        if ((int)$value[13] !== $d2) {
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
}
