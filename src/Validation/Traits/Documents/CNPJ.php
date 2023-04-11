<?php

namespace ResultSystems\Validation\Traits\Documents;

trait CNPJ
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
}
