<?php

require_once '../vendor/autoload.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $data = json_decode(file_get_contents("php://input"), true);
    $idPessoa = $data['id_pessoa'] ?? null;

    $dadosRefeicaoTipo = [];

    if ($idPessoa) {
        $refeicaoTipo = new \app\models\portal\RefeicaoTipo();
        $pessoa = new \app\models\portal\Pessoa();
        $dadosPessoa = $pessoa->getDadosPessoaByIdTelegram($idPessoa);
        
        if ($dadosPessoa && isset($dadosPessoa->id)) {
            $resultado = $refeicaoTipo->getTipoRefeicaoByIdPessoa($dadosPessoa->id);
            // Garante que se vier false/null do banco, vira um array vazio
            $dadosRefeicaoTipo = is_array($resultado) ? $resultado : [];
        }
    }

    header('Content-Type: application/json');
    echo json_encode($dadosRefeicaoTipo);
} else {
    http_response_code(405);
    echo json_encode(['error' => 'Método não permitido. Use POST.']);
}