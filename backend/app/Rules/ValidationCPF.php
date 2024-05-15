<?php

namespace App\Rules;

use Illuminate\Contracts\Validation\Rule;
use Illuminate\Support\Facades\Log;

class ValidationCPF implements Rule
{
    /**
     * Determine se a regra de validação passa.
     *
     * @param  string  $attribute
     * @param  mixed  $value
     * @return bool
     */
    public function passes($attribute, $value)
    {
        // Remover caracteres especiais do CPF
        $cpf = preg_replace('/[^0-9]/', '', $value);
        Log::info('CPF after cleaning: ' . $cpf);

        // Verificar se o CPF tem 11 dígitos
        if (strlen($cpf) != 11) {
            Log::info('Invalid length: ' . strlen($cpf));
            return false;
        }

        // Verificar se todos os dígitos são iguais
        if (preg_match('/(\d)\1{10}/', $cpf)) {
            Log::info('All digits are the same');
            return false;
        }

        // Calcular o primeiro dígito verificador
        $soma = 0;
        for ($i = 0; $i < 9; $i++) {
            $soma += $cpf[$i] * (10 - $i);
        }
        Log::info('Sum for first digit: ' . $soma);

        $resto = $soma % 11;
        $digitoVerificador1 = ($resto < 2) ? 0 : 11 - $resto;
        Log::info('First verification digit: ' . $digitoVerificador1);

        // Verificar o primeiro dígito verificador
        if ($cpf[9] != $digitoVerificador1) {
            Log::info('First digit does not match');
            return false;
        }

        // Calcular o segundo dígito verificador
        $soma = 0;
        for ($i = 0; $i < 10; $i++) {
            $soma += $cpf[$i] * (11 - $i);
        }
        Log::info('Sum for second digit: ' . $soma);

        $resto = $soma % 11;
        $digitoVerificador2 = ($resto < 2) ? 0 : 11 - $resto;
        Log::info('Second verification digit: ' . $digitoVerificador2);

        // Verificar o segundo dígito verificador
        if ($cpf[10] != $digitoVerificador2) {
            Log::info('Second digit does not match');
            return false;
        }

        return true;
    }

    /**
     * Obtém a mensagem de erro de validação.
     *
     * @return string
     */
    public function message()
    {
        return 'O campo :attribute não é um CPF válido.';
    }
}
