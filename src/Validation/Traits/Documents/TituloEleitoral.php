<?php

namespace ResultSystems\Validation\Traits\Documents;

trait TituloEleitoral
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
    public function validateTituloEleitoral($attribute, $value, $parameters)
    {
        // Canonicalize input and parse UF
        $te = sprintf('%012s', $value);
        $uf = (int) (substr($value, 8, 2));
        // Validate length and invalid UFs
        if ((12 !== strlen($te))
         || ($uf < 1)
         || ($uf > 28)) {
            return false;
        }
        // Validate check digits using a slightly modified modulus 11 algorithm
        foreach ([7, 8 => 10] as $s => $t) {
            for ($d = 0, $p = 2, $c = $t; $c >= $s; $c--, $p++) {
                $d += $te[$c] * $p;
            }
            if ((int) $te[($s) ? 11 : 10] !== (int) ((($d %= 11) < 2) ? (($uf < 3) ? 1 - $d
              : 0)
            : 11 - $d)) {
                return false;
            }
        }

        return true;
    }
}
