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
