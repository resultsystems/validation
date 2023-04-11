<?php

namespace ResultSystems\Validation\Traits\Documents;

use ResultSystems\Validation\Traits\Documents\CNPJ;
use ResultSystems\Validation\Traits\Documents\CPF;

trait CountryRegister
{
    use CNPJ;
    use CPF;

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
    public function validateCnpjCpfMask($attribute, $value, $parameters)
    {
        return $this->validateCnpjCpf($attribute, $this->clearCountryRegistry($value), $parameters);
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

    public function clearCountryRegistry(string $document)
    {
        return preg_replace('/[^0-9]/', '', $document);
    }
}
