[Vídeo tutorial PT-BR](https://www.youtube.com/watch?v=z451-TPk79w)

Validation (Laravel 5.X Package) 
==========

```php
composer require resultsystems/validation
```

In your **config/app.php** add *'ResultSystems\Validation\ValidationServiceProvider::class'* to the end of the **'providers'** array:

```php
'providers' => array(
    ...
    ...
    ResultSystems\Validation\ValidationServiceProvider::class,
),
```

## Supported rules (with examples)

```php
// Rules
$rules = array(
    'nis'                   => 'nis',
    'titulo_eleitoral'      => 'titulo_eleitoral',
    'cpf'                   => 'cpf',
    'cpf_mascara'           => 'cpf_mascara',
    'cnpj'                  => 'cnpj',
    'cnpj_mascara'          => 'cnpj_mascara',
    'cnpj_cpf'              => 'cnpj_cpf',
    'cnpj_cpf_mascara'      => 'cnpj_cpf_mascara',
    'cnpj_cpf_zero'         => 'cnpj_cpf_zero',
    'cnpj_cpf_zero_mascara' => 'cnpj_cpf_zero_mascara',
    'telefone'              => 'telefone',
    'telefone_mascara'      => 'telefone_mascara',
    'celular'               => 'celular',
    'celular_mascara'       => 'celular_mascara',
    'time'                  => 'time',
    'min_time'              => 'min_time',
    'after_time'            => 'after_time',
    'required_if_not'       => 'required_if_not',
    'cnh'                   => 'cnh', // Carteira Nacional de Habilitação
    'minimum_age'           => 'minimumAge:20',
    'callback'              => 'callback:is_int',
    'charset'               => 'charset:ASCII',
    'consonant'             => 'consonant',
    'vowel'                 => 'vowel',
    'alnum'                 => 'alnum:-',
    'digit'                 => 'digit',
    'alpha'                 => 'alpha',
    'containsArray'         => 'contains:banana',
    'contains'              => 'contains:banana',
    'countryCode'           => 'countryCode',
    'creditCard'            => 'digit|creditCard',
    'domain'                => 'domain',
    'directory'             => 'directory',
    'FileExists'            => 'FileExists',
    'isFile'                => 'file',
    'endsWith'              => 'endsWith:banana',
    'equals'                => 'equals:banana',
    'even'                  => 'even',
    'float'                 => 'float',
    'graph'                 => 'graph',
    'instance'              => 'instance:DateTime',
    'int'                   => 'int',
    'json'                  => 'json',
    'leapDate'              => 'leapDate:Y-m-d',
    'leapYear'              => 'leapYear',
    'arr'                   => 'arr',
    'lowercase'             => 'lowercase',
    'macAddress'            => 'macAddress',
    'multiple'              => 'multiple:3',
    'negative'              => 'negative',
    'noWhitespace'          => 'noWhitespace',
    'notArray'              => 'arr|not',
    'nullValue'             => 'nullValue',
    'numeric'               => 'numeric',
    'object'                => 'object',
    'odd'                   => 'odd',
    'perfectSquare'         => 'perfectSquare',
    'positive'              => 'positive',
    'primeNumber'           => 'primeNumber',
    'punct'                 => 'punct',
    'readable'              => 'readable',
    'regex'                 => 'regex:/5/',
    'roman'                 => 'roman',
    'slug'                  => 'slug',
    'space'                 => 'space:b',
    'tld'                   => 'tld', // Top Level Domain,
    //'uploaded'            => 'uploaded',
    'uppercase'             => 'uppercase',
    'version'               => 'version',
    'xdigit'                => 'xdigit', // Hexadecimal
    'writable'              => 'writable',
    'alwaysValid'           => 'alwaysValid',
    'bool'                  => 'bool'
);

// Data
$data = array(
    'nis'                   => '17033259504',
    'titulo_eleitoral'      => '000000000000',
    'cpf'                   => '22205417118',
    'cpf_mascara'           => '222.054.171-18',
    'cnpj'                  => '68518321000116',
    'cnpj_mascara'          => '68.518.321/0001-16',
    'cnpj_cpf'              => '22205417118', // ou 68518321000116
    'cnpj_cpf_zero'         => '22205417118', // ou 68518321000116 ou 00000000000 ou 00000000000000
    'cnpj_cpf_zero_mascara' => '222.054.171-18', // ou 68518321000116 ou 00000000000 ou 00000000000000
    'telefone'              => '3430353637',
    'telefone_mascara'      => '34 3035-3637',
    'celular'               => '4191234567',
    'celular_mascara'       => '41 9123-4567',
    'time'                  => '18:40',
    'min_time'              => 'min_time:time',
    'after_time'            => 'after_time:time',
    'condicional'           => 'required_if_not,Tipo:5',
    'cnh'                   => '02650306461',
    'minimum_age'           => '10/10/1990',
    'callback'              => 20,
    'charset'               => 'acucar',
    'consonant'             => 'dcfg',
    'vowel'                 => 'aeiou',
    'alnum'                 => 'banana-123 ',
    'digit'                 => '120129  21212',
    'alpha'                 => 'banana',
    'containsArray'         => array('www', 'banana', 'jfk', 'http'),
    'contains'              => 'www banana jfk http',
    'countryCode'           => 'BR',
    'creditCard'            => '5555666677778884',
    'domain'                => 'google.com.br',
    'directory'             => __DIR__,
    'FileExists'            => __FILE__,
    'file'                  => __FILE__,
    'endsWith'              => 'pera banana',
    'equals'                => 'banana',
    'even'                  => 8,
    'float'                 => 9.8,
    'graph'                 => 'LKM@#$%4;',
    'instance'              => new Datetime(),
    'int'                   => 9,
    'json'                  => '{"file":"laravel.php"}',
    'leapDate'              => '1988-02-29',
    'leapYear'              => '1988',
    'arr'                   => array('Brazil'),
    'lowercase'             => 'brazil',
    'macAddress'            => '00:11:22:33:44:55',
    'multiple'              => '9',
    'negative'              => '-10',
    'noWhitespace'          => 'laravelBrazil',
    'nullValue'             => null,
    'numeric'               => '179.9',
    'object'                => new stdClass(),
    'odd'                   => 3,
    'perfectSquare'         => 25,
    'positive'              => 1,
    'primeNumber'           => 7,
    'punct'                 => '&,.;[]',
    'readable'              => __FILE__,
    'regex'                 => '5',
    'roman'                 => 'VI',
    'slug'                  => 'laravel-brazil',
    'space'                 => '              b      ',
    'tld'                   => 'com',
    //'uploaded'            => 'path to file',
    'uppercase'             => 'BRAZIL',
    'version'               => '1.0.0',
    'xdigit'                => 'abc123',
    'writable'              => __FILE__,
    'alwaysValid'           => '@#$_',
    'bool'                  => is_int(2)
);

// Make the validation
$validator = \Validator::make($data, $rules);

// Result
if( $validator->fails() )
{
    // Print errors
    $messages = $validator->messages();
    foreach ($messages->all() as $message) {
        echo '<li>'.$message.'</li>';
    }
}
else
{
    // Success
    echo 'True.';
}
```

Code inspired from https://github.com/KennedyTedesco/Validation

# Respect Validation

For more details on the rules:

https://github.com/Respect/Validation

Tks for Respect: **The most awesome validation engine ever created for PHP!**
