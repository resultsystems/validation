<?php

require_once 'CNPJ.php';

class CnpjValidator
{
    use \ResultSystems\Validation\Traits\Documents\CNPJ;

    public function charValue(string $c): int
    {
        return ctype_digit($c) ? (int) $c : ord($c) - 48;
    }

    public function validateCnpj(string $attribute, string $value, string $parameters = null): bool
    {
        $value = strtoupper(preg_replace('/[^A-Z0-9]/', '', $value));

        if (!preg_match('/^[A-Z0-9]{12}[0-9]{2}$/', $value)) {
            return false;
        }

        $weights1 = [5,4,3,2,9,8,7,6,5,4,3,2];
        $weights2 = [6,5,4,3,2,9,8,7,6,5,4,3,2];

        $nums = array_map([$this, 'charValue'], str_split(substr($value, 0, 12)));

        $sum = array_sum(array_map(fn ($n, $w) => $n * $w, $nums, $weights1));
        $r = $sum % 11;
        $d1 = ($r < 2) ? 0 : 11 - $r;

        if ((int)$value[12] !== $d1) {
            return false;
        }

        $nums[] = $d1;
        $sum = array_sum(array_map(fn ($n, $w) => $n * $w, $nums, $weights2));
        $r = $sum % 11;
        $d2 = ($r < 2) ? 0 : 11 - $r;

        return (int)$value[13] === $d2;
    }
}

// Instância de teste
$validator = new CnpjValidator();

// --- Testes automáticos simples ---
function assertTrue($condition, $message)
{
    if (!$condition) {
        echo "❌ FAIL: $message\n";
    } else {
        echo "✅ PASS: $message\n";
    }
}

function assertFalse($condition, $message)
{
    assertTrue(!$condition, $message);
}

// Exemplos reais
assertTrue($validator->validateCnpj('cnpj', '11444777000161'), 'CNPJ tradicional válido');
assertTrue($validator->validateCnpj('cnpj', '33300033000106'), 'CNPJ tradicional válido');
assertFalse($validator->validateCnpj('cnpj', '33300033000206'), 'CNPJ tradicional inválido');
assertFalse($validator->validateCnpj('cnpj', '11444777000162'), 'CNPJ tradicional inválido');

// Exemplo alfanumérico válido (calculado corretamente)
assertTrue($validator->validateCnpj('cnpj', 'A2YAJLFF6GQE88'), 'CNPJ alfanumérico válido');
assertTrue($validator->validateCnpj('cnpj', 'CIAREISLTDA110'), 'CNPJ alfanumérico válido');
assertTrue($validator->validateCnpj('cnpj', '11.111.CIA/REIS-77'), 'CNPJ alfanumérico válido');


// Exemplo alfanumérico inválido
assertFalse($validator->validateCnpj('cnpj', 'A2YAJLFF6GQ188'), 'CNPJ alfanumérico inválido');
assertFalse($validator->validateCnpj('cnpj', 'A2YAJLFF6GQA88'), 'CNPJ alfanumérico inválido');
assertFalse($validator->validateCnpj('cnpj', 'A2345678B00174'), 'CNPJ alfanumérico inválido');
assertFalse($validator->validateCnpj('cnpj', '11.111.CIA/REIS-00'), 'CNPJ alfanumérico inválido');

// Casos inválidos
assertFalse($validator->validateCnpj('cnpj', '123'), 'Tamanho inválido');
assertFalse($validator->validateCnpj('cnpj', '1234567890####'), 'Caracteres inválidos');
