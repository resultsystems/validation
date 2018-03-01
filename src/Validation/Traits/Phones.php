<?php

namespace ResultSystems\Validation\Traits;

trait Phones
{
    /**
     * valida Telefone.
     *
     * @param string $attribute
     * @param string $value
     * @param string $parameters
     *
     * @return bool
     */
    public function validateOnlyPhoneBr($attribute, $value, $parameters)
    {
        $result = false;

        // Verifica telefones
        // 0XX XXXX-XXXX
        // XX XXXX-XXXX
        // XXXX-XXXX
        $result = preg_match('/^(0?[1-9]{2})?([2-5][0-9]{7})$/', $value, $matches);
        if ($result) {
            return true;
        }

        //Verifica 0800
        return preg_match('/^0800([0-9]{7,8})$/', $value, $matches);
    }

    /**
     * valida Telefone.
     *
     * @param string $attribute
     * @param string $value
     * @param string $parameters
     *
     * @return bool
     */
    public function validatePhoneBr($attribute, $value, $parameters)
    {
        $result = false;

        // Verifica telefones
        // 0XX [9]XXXX-XXXX
        // XX [9]XXXX-XXXX
        // [9]XXXX - XXXX
        $result = preg_match('/^(0?[1-9]{2})?(([2-9][0-9]{7})|(9[0-9]{8}))$/', $value, $matches);
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
     *
     * @param string $attribute
     * @param string $value
     * @param string $parameters
     *
     * @return bool
     */
    public function validateTelefoneMascara($attribute, $value, $parameters)
    {
        return $this->validateTelefone($attribute, str_replace([' ', '/', '-'], '', $value), $parameters);
    }

    /**
     * valida Celular.
     *
     * @param string $attribute
     * @param string $value
     * @param string $parameters
     *
     * @return bool
     */
    public function validateCellphoneBr($attribute, $value, $parameters)
    {
        $result = false;

        // Verifica celulares
        // 0XX 9XXXX-XXXX
        // XX 9XXXX-XXXX
        // 9XXXX-XXXX
        return preg_match('/^(0?[1-9]{2})?(9[0-9]{8})$/', $value, $matches);
    }

    public function validateCelular($attribute, $value, $parameters)
    {
        return $this->validateCellphoneBr($attribute, $value, $parameters);
    }

    /**
     * valida Celular com mascara.
     *
     * @param string $attribute
     * @param string $value
     * @param string $parameters
     *
     * @return bool
     */
    public function validateCelularMascara($attribute, $value, $parameters)
    {
        return $this->validateCelular($attribute, str_replace([' ', '/', '-'], '', $value), $parameters);
    }
}
