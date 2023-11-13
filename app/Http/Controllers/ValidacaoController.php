<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\{
    RecuperarSenha,
    Pessoas
};
use Illuminate\Http\Request;
use Illuminate\Support\Facades\{Hash, Validator};
use Illuminate\Support\Facades\Http;
use App\Mail\SendPasswordMail;
use App\Jobs\{
    RecuperSenhaQueue,
    SendEmailConfirmationEmailQueue
};
use Exception;
use Illuminate\Support\Str;


class ValidacaoController extends Controller
{
    
    //Validar CPF
    public function validaCPF(Request $request, $cpf)
    {
        $existe_cpf = Pessoas::where("cpfcnpj", $cpf)->first();

        // Extrai somente os números
        $cpf = preg_replace('/[^0-9]/is', '', $cpf);

        // Verifica se foi informado todos os dígitos corretamente
        if (strlen($cpf) != 11) {
            return response()->json([
                'status' => 'erro',
                'message' => 'CPF inválido',
            ], 400);
        }

        // Verifica se foi informada uma sequência de dígitos repetidos. Ex: 111.111.111-11
        if (preg_match('/(\d)\1{10}/', $cpf)) {
            return response()->json([
                'status' => 'erro',
                'message' => 'CPF inválido',
            ], 400);
        }

        // Faz o cálculo para validar o CPF
        for ($t = 9; $t < 11; $t++) {
            for ($d = 0, $c = 0; $c < $t; $c++) {
                $d += $cpf[$c] * (($t + 1) - $c);
            }
            $d = ((10 * $d) % 11) % 10;
            if ($cpf[$c] != $d) {
                return response()->json([
                    'status' => 'erro',
                    'message' => 'CPF inválido',
                ], 400);
            }
        }
        return response()->json([
            'status' => 'success',
            'message' => 'CPF válido',
        ], 200);
    }

    //validar CNPJ
    public function validaCNPJ($cnpj)
    {
        $cnpj = preg_replace('/[^0-9]/is', '', $cnpj);

        // Valida tamanho
        if (strlen($cnpj) != 14)
            return response()->json([
                'status' => 'erro',
                'message' => 'CNPJ inválido',
            ], 400);

        // Verifica se todos os digitos são iguais
        if (preg_match('/(\d)\1{13}/', $cnpj))
            return response()->json([
                'status' => 'erro',
                'message' => 'CNPJ inválido',
            ], 400);

        // Valida primeiro dígito verificador
        for ($i = 0, $j = 5, $soma = 0; $i < 12; $i++) {
            $soma += $cnpj[$i] * $j;
            $j = ($j == 2) ? 9 : $j - 1;
        }

        $resto = $soma % 11;

        if ($cnpj[12] != ($resto < 2 ? 0 : 11 - $resto))
            return response()->json([
                'status' => 'erro',
                'message' => 'CNPJ inválido',
            ], 400);

        // Valida segundo dígito verificador
        for ($i = 0, $j = 6, $soma = 0; $i < 13; $i++) {
            $soma += $cnpj[$i] * $j;
            $j = ($j == 2) ? 9 : $j - 1;
        }

        $resto = $soma % 11;

        return response()->json([
            'status' => 'success',
            'message' => 'CNPJ válido',
        ], 200);
    }

    //Consulta CEP
    public function buscaCEP(Request $request, $cep)
    {
        try {
            //code...
            $response = Http::withoutVerifying()->get("https://viacep.com.br/ws/{$cep}/json/");

            if ($response->successful()) {
                $data = $response->json();
                return response()->json(['data' => $data], 200);
            } else {
                return response()->json(['error' => 'CEP não encontrado'], 404);
            }
        } catch (Exception $e) {
            return response()->json(['error' => 'Falha na conexão'], 500);
        }
    }


    

}
