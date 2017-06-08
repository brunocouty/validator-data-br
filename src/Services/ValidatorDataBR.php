<?php

namespace BrunoCouty\ValidatorDataBR\Services;

class ValidatorDataBR
{
    /*
     * Constantes das bandeiras do cartão de crédito
     */
    const CARD_AMEX = 1;
    const CARD_DINERS = 2;
    const CARD_DISCOVER = 4;
    const CARD_MASTERCARD = 8;
    const CARD_VISA = 16;
    const CARD_ALL = 31;

    /**
     * Valida o número de um cartão de crédito de acordo com sua bandeira
     * @param $cc
     * @param $flag
     * @return bool
     */
    public static function cc(string $cc, $flag = ValidatorDataBR::CARD_ALL)
    {
        // Canonicalize input
        $cc = preg_replace('{\D}', '', $cc);
        // Validate choosed flags
        $er = array();
        if ($flag & self::CARD_AMEX) {
            $er[] = '^3[47].{13}$';
        }
        if ($flag & self::CARD_DINERS) {
            $er[] = '^3(0[0-5].{11}|6.{12})$';
        }
        if ($flag & self::CARD_DISCOVER) {
            $er[] = '^6(011.{12}|5.{14})$';
        }
        if ($flag & self::CARD_MASTERCARD) {
            $er[] = '^5[1-5].{14}$';
        }
        if ($flag & self::CARD_VISA) {
            $er[] = '^4.{15}$';
        }
        if (empty($er) || !preg_match('~' . implode('|', $er) . '~', $cc)) {
            return false;
        }
        // Validate digits using a modulus 10 algorithm (aka Luhn)
        for ($sum = 0, $idx = strlen($cc) - 1, $wt = 1; $idx >= 0;
             $wt = ($wt % 2) + 1, --$idx) {
            $sum += (($d = intval($cc[$idx]) * $wt) > 9) ? $d - 9 : $d;
        }
        return (($sum % 10) == 0);
    }

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
     * Verifica se a CNH é válida
     * @param string $cnh
     * @return bool
     */
    public function cnh(string $cnh)
    {
        // Canonicalize input
        $cnh = sprintf('%011s', preg_replace('{\D}', '', $cnh));
        // Validate length and invalid numbers
        if ((strlen($cnh) != 11) || (intval($cnh) == 0)) {
            return false;
        }
        // Validate check digits using a modulus 11 algorithm
        for ($c = $s1 = $s2 = 0, $p = 9; $c < 9; $c++, $p--) {
            $s1 += intval($cnh[$c]) * $p;
            $s2 += intval($cnh[$c]) * (10 - $p);
        }
        if ($cnh[9] != (($dv1 = $s1 % 11) > 9) ? 0 : $dv1) {
            return false;
        }
        if ($cnh[10] != (((($dv2 = ($s2 % 11) - (($dv1 > 9) ? 2 : 0)) < 0)
                ? $dv2 + 11 : $dv2) > 9) ? 0 : $dv2
        ) {
            return false;
        }
        return true;
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

    /**
     * Valida título eleitoral
     * @param $te
     * @return bool
     */
    public function tituloEleitoral($te)
    {
        $te = sprintf('%012s', preg_replace('{\D}', '', $te));
        $uf = intval(substr($te, 8, 2));
        if ((strlen($te) != 12)
            || ($uf < 1)
            || ($uf > 28)
        ) {
            return false;
        }
        foreach (array(7, 8 => 10) as $s => $t) {
            for ($d = 0, $p = 2, $c = $t; $c >= $s; $c--, $p++) {
                $d += $te[$c] * $p;
            }
            if ($te[($s) ? 11 : 10] != ((($d %= 11) < 2) ? (($uf < 3) ? 1 - $d
                    : 0)
                    : 11 - $d)
            ) {
                return false;
            }
        }
        return true;
    }

    /**
     * Valida número de inscrição social
     * @param $nis
     * @return bool
     */
    public function nis($nis)
    {
        $nis = sprintf('%011s', preg_replace('{\D}', '', $nis));
        if ((strlen($nis) != 11)
            || (intval($nis) == 0)
        ) {
            return false;
        }
        for ($d = 0, $p = 2, $c = 9; $c >= 0; $c--, ($p < 9) ? $p++ : $p = 2) {
            $d += $nis[$c] * $p;
        }
        return ($nis[10] == (((10 * $d) % 11) % 10));
    }

    /**
     * Valida o DDD de um telefone
     * @param $ddd
     * @return bool
     */
    public function ddd($ddd)
    {
        return preg_match('{^([14689][1-9]|2[14]|[23][278]|[357][13-5]|7[79])$}',
                $ddd) != false;
    }


    /**
     * Valida UF
     * @param $uf
     * @return bool
     */
    public static function uf($uf)
    {
        return preg_match('{^A[CLMP]|BA|CE|DF|ES|[GT]O|M[AGST]|P[ABEIR]|R[JNORS]'
                . '|S[CEP]$}', $uf) != false;
    }


}