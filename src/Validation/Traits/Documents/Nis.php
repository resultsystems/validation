<?php

namespace ResultSystems\Validation\Traits\Documents;

trait Nis
{
    /**
     * Based by code from  https://gist.github.com/paulofreitas/4704673.
     *
     * Validate if file exists.
     *
     * @param string $attribute
     * @param mixed  $value
     * @param array  $parameters
     *
     * @return bool
     */
    public function validateNis($attribute, $value, $parameters)
    {
        // Canonicalize input
        $nis = sprintf('%011s', $value);
        // Validate length and invalid numbers
        if ((11 !== strlen($nis))
         || (0 === (int) $nis)) {
            return false;
        }
        // Validate check digit using a modulus 11 algorithm
        for ($d = 0, $p = 2, $c = 9; $c >= 0; $c--, ($p < 9) ? $p++ : $p = 2) {
            $d += $nis[$c] * $p;
        }

        return (int) $nis[10] === (int) (((10 * $d) % 11) % 10);
    }
}
