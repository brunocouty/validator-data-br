<?php

namespace BrunoCouty\ValidatorDataBR\Services;

class ValidatorDataBR
{
    /**
     * Verifica se o CPF é válido
     * @param string $cpf
     * @return bool
     */
    public function cpf(string $cpf)
    {
        // Verifica se um número foi informado
        if (empty($cpf)) {
            return false;
        }
        // remove a máscara, caso exista
        $cpf = preg_replace("/[^0-9]/", "", $cpf);
        if (!is_numeric($cpf)) {
            return false;
        }
        // Elimina possivel mascara
        $cpf = str_pad($cpf, 11, '0', STR_PAD_LEFT);
        // Verifica se o numero de digitos informados é igual a 11
        if (strlen($cpf) != 11) {
            return false;
        }
        // Verifica se nenhuma das sequências invalidas abaixo
        // foi digitada. Caso afirmativo, retorna falso
        else if ($cpf == '00000000000' ||
            $cpf == '11111111111' ||
            $cpf == '22222222222' ||
            $cpf == '33333333333' ||
            $cpf == '44444444444' ||
            $cpf == '55555555555' ||
            $cpf == '66666666666' ||
            $cpf == '77777777777' ||
            $cpf == '88888888888' ||
            $cpf == '99999999999'
        ) {
            return false;
            // Calcula os digitos verificadores para verificar se o
            // CPF é válido
        } else {
            for ($t = 9; $t < 11; $t++) {
                for ($d = 0, $c = 0; $c < $t; $c++) {
                    $d += $cpf{$c} * (($t + 1) - $c);
                }
                $d = ((10 * $d) % 11) % 10;
                if ($cpf{$c} != $d) {
                    return false;
                }
            }
            return true;
        }
    }

    /**
     * Verifica se o CNPJ é válido
     * @param string $cnpj
     * @return bool
     */
    public function cnpj(string $cnpj)
    {
        $cnpj = preg_replace('/[^0-9]/', '', (string)$cnpj);
        // Valida tamanho
        if (strlen($cnpj) != 14)
            return false;
        // Valida primeiro dígito verificador
        for ($i = 0, $j = 5, $soma = 0; $i < 12; $i++) {
            $soma += $cnpj{$i} * $j;
            $j = ($j == 2) ? 9 : $j - 1;
        }
        $resto = $soma % 11;
        if ($cnpj{12} != ($resto < 2 ? 0 : 11 - $resto))
            return false;
        // Valida segundo dígito verificador
        for ($i = 0, $j = 6, $soma = 0; $i < 13; $i++) {
            $soma += $cnpj{$i} * $j;
            $j = ($j == 2) ? 9 : $j - 1;
        }
        $resto = $soma % 11;
        return $cnpj{13} == ($resto < 2 ? 0 : 11 - $resto);
    }

    /**
     * Validar celular
     * @param string $phone
     * @return bool
     */
    public function celular(string $phone)
    {
        if (preg_match('#^\(\d{2}\) (9|)[6789]\d{3}-\d{4}$#', $phone) > 0) {
            return true;
        }
        $phone = trim(
            str_replace(
                '/',
                '',
                str_replace(
                    ' ',
                    '',
                    str_replace(
                        '-',
                        '',
                        str_replace(
                            ')',
                            '',
                            str_replace(
                                '(',
                                '',
                                $phone)
                        )
                    )
                )
            )
        );
        $regexCel = '/[0-9]{2}[6789][0-9]{3,4}[0-9]{4}/';
        if (preg_match($regexCel, $phone)) {
            return true;
        }
        return false;
    }


}