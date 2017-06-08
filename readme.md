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

Métodos disponíveis:

* cpf;
* cnpj;
* cnh;
* celular;
* tituloEleitoral;
* uf;
* cc (Cartão de Crédito);

```php
$validator = new \BrunoCouty\ValidatorDataBR\Services\ValidatorDataBR();

// Para todas as validações abaixo, o parâmetro é uma *string*.
// As máscaras são tratadas, logo, você pode enviar como parâmetro "111.222.333-44"
$validCPF = $validator->cpf('your-cpf');
$validCNPJ = $validator->cnpf('your-cnpj');
$validCNH = $validator->cnh('your-cnh');
$validCelular = $validator->celular('your-celular');
$validTituloEleitoral = $validator->tituloEleitoral('titulo-eleitor');
$validUF = $validator->uf('unidade-federal');
```

Para validar cartões de crédito, utiliza-se dois parâmetros: *número do cartão* e bandeira. A bandeira pode ser passada da seguinte forma:

* *ValidatorDataBR::CARD_AMEX*;
* *ValidatorDataBR::CARD_DINERS*;
* *ValidatorDataBR::CARD_DISCOVER*;
* *ValidatorDataBR::CARD_MASTERCARD*;
* *ValidatorDataBR::CARD_VISA*;
* *ValidatorDataBR::CARD_ALL*;

```php
// $response = $validatorBR->cc('credit-card-number', 'static-card-flag');
$response = $validatorBR->cc('5111111111111118', $validator::CARD_MASTERCARD);
```

A resposta sempre será um ***boolean*** (*true* ou *false*).

## Agradecimento e Créditos

Os métodos de cartão de crédito, título de eleitor e NIS foram retirados do repositório do [@paulofreitas](https://gist.github.com/paulofreitas/4704673). Dê uma passadinha lá, vale a pena!

## Gostou deste conteúdo? Me pague um café!

Yeah! Você gostou deste pacote? Me pague um café e me ajude a manter este pacote atualizado!

Quando você me ajuda, você tem acesso a **posts exclusivos** com muitas coisas úteis sobre PHP, Laravel, AngularJS, VueJS, Ionic, e muito mais! Você aprenderá a criar seus próprios pacotes PHP (independentes de framework), resolver problemas em seu código fonte... Um excelente conteúdo, sempre atualizado!

Você pode me ajudar com R$ 1 / mês e já terá acesso a meu conteúdo privado! 
E mais, precisa de ajuda com seu projeto? Eu posso ajudar você! Acesse [https://apoia.se/brunocouty](https://www.apoia.se/brunocouty), posso te ajudar via e-mail ou skype!

[https://apoia.se/brunocouty](https://www.apoia.se/brunocouty)
