<?php
function formatarCpfCnpj($numero) {
    $numero = preg_replace('/\D/', '', $numero);
    if (strlen($numero) === 11) {
        return preg_replace('/^(\d{3})(\d{3})(\d{3})(\d{2})$/', '$1.$2.$3-$4', $numero);
    } elseif (strlen($numero) === 14) {
        return preg_replace('/^(\d{2})(\d{3})(\d{3})(\d{4})(\d{2})$/', '$1.$2.$3/$4-$5', $numero);
    }
    return $numero;
}

function formatarTelefone($telefone) {
    $telefone = preg_replace('/\D/', '', $telefone);
    if (strlen($telefone) === 11) {
        return preg_replace('/^(\d{2})(\d{1})(\d{4})(\d{4})$/', '($1) $2 $3-$4', $telefone);
    } elseif (strlen($telefone) === 10) {
        return preg_replace('/^(\d{2})(\d{4})(\d{4})$/', '($1) $2-$3', $telefone);
    }
    return $telefone;
}

// ... (suas funções formatarCpfCnpj e formatarTelefone já estão aqui) ...

function validarCpf($cpf) {
    // Extrai somente os números
    $cpf = preg_replace('/[^0-9]/is', '', $cpf);
    
    // Verifica se foi informado todos os digitos corretamente
    if (strlen($cpf) != 11) {
        return false;
    }

    // Verifica se foi informada uma sequência de digitos repetidos. Ex: 111.111.111-11
    if (preg_match('/(\d)\1{10}/', $cpf)) {
        return false;
    }

    // Faz o calculo para validar o CPF
    for ($t = 9; $t < 11; $t++) {
        for ($d = 0, $c = 0; $c < $t; $c++) {
            $d += $cpf[$c] * (($t + 1) - $c);
        }
        $d = ((10 * $d) % 11) % 10;
        if ($cpf[$c] != $d) {
            return false;
        }
    }
    return true;
}

function validarCnpj($cnpj) {
	$cnpj = preg_replace('/[^0-9]/', '', (string) $cnpj);
	
	// Valida tamanho
	if (strlen($cnpj) != 14)
		return false;

	// Verifica se todos os digitos são iguais
	if (preg_match('/(\d)\1{13}/', $cnpj))
		return false;	

	// Valida primeiro dígito verificador
	for ($i = 0, $j = 5, $soma = 0; $i < 12; $i++)
	{
		$soma += $cnpj[$i] * $j;
		$j = ($j == 2) ? 9 : $j - 1;
	}

	$resto = $soma % 11;

	if ($cnpj[12] != ($resto < 2 ? 0 : 11 - $resto))
		return false;

	// Valida segundo dígito verificador
	for ($i = 0, $j = 6, $soma = 0; $i < 13; $i++)
	{
		$soma += $cnpj[$i] * $j;
		$j = ($j == 2) ? 9 : $j - 1;
	}

	$resto = $soma % 11;

	return $cnpj[13] == ($resto < 2 ? 0 : 11 - $resto);
}
?>