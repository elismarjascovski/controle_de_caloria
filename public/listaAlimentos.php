<?php

require_once '../vendor/autoload.php';
require_once '../app/models/portal/Alimento.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $data = json_decode(file_get_contents("php://input"), true);
    $nome = $data['alimento'] ?? '';
    $idPessoa = $data['id_pessoa'];

    $alimentoModel = new \app\models\portal\Alimento();
    $pessoa = new \app\models\portal\Pessoa();
    $dadosPessoa = $pessoa->getDadosPessoaByIdTelegram($idPessoa);

    $alimentos = $alimentoModel->getDadosAlimentoByNomeAndIdPessoa($nome, $dadosPessoa->id);

    header('Content-Type: application/json');
    echo json_encode($alimentos);
} else {
    http_response_code(405);
    echo json_encode(['error' => 'Método não permitido. Use POST.']);
}