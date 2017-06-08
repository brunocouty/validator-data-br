# Sobre o ValidatorDataBR PHP

Este pacote o ajudará a validar dados Brasileiros, como CPF, CNPJ, número de celular... 

**Nota 1:** Este pacote pode ser usado com **qualquer framework ou aplicação PHP**. Neste documento, ensino a instalação em ***Laravel***.

## Requisitos:

- PHP >=7.1;
- Laravel <= 5.1;

## Utilização

Primeiro, faça o download da biblioteca através do composer:

```php
composer require brunocouty/validator-data-br
```

Agora, adicione o *Service Provider* no *array* de *providers*, no arquivo "*config/app.php*":

```php
BrunoCouty\ValidatorDataBR\ValidatorDataBRServiceProvider::class,
```

Para utilizar:

```php
$validator = new \BrunoCouty\ValidatorDataBR\Services\ValidatorDataBR();
$response = $validator->cpf('your-cpf');
$response = $validator->cnpf('your-cnpj');
$response = $validator->celular('your-celular');
```

A resposta será um ***boolean*** (*true* ou *false*).