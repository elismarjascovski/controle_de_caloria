<?php

require_once '../vendor/autoload.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $data = json_decode(file_get_contents("php://input"), true);
    $idPessoa = $data['id_pessoa'];

    $refeicaoTipo = new \app\models\portal\RefeicaoTipo();
    $pessoa = new \app\models\portal\Pessoa();
    $dadosPessoa = $pessoa->getDadosPessoaByIdTelegram($idPessoa);
    $dadosRefeicaoTipo = $refeicaoTipo->getTipoRefeicaoByIdPessoa($dadosPessoa->id);

    header('Content-Type: application/json');
    echo json_encode($dadosRefeicaoTipo);
} else {
    http_response_code(405);
    echo json_encode(['error' => 'Método não permitido. Use POST.']);
}