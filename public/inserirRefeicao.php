<?php
require '../vendor/autoload.php';
require_once '../app/models/portal/Refeicao.php';
require_once '../app/models/portal/RefeicaoAlimento.php';

header('Content-Type: application/json');

date_default_timezone_set('America/Sao_Paulo');

if ($_SERVER['REQUEST_METHOD'] === 'POST') {

    $jsonData = file_get_contents("php://input");
    $data = json_decode($jsonData, true);

    if (isset($data['id_pessoa'], $data['alimentos']) && is_array($data['alimentos'])) {
        $idPessoa = $data['id_pessoa'];
        $alimentos = $data['alimentos'];

        $pessoa = new \app\models\portal\Pessoa();
        $refeicao = new \app\models\portal\Refeicao();
        $refeicaoAlimento = new \app\models\portal\RefeicaoAlimento();

        try {
            $dadosPessoa = $pessoa->getDadosPessoaByIdTelegram($idPessoa);
            if($dadosPessoa) {
                $dtRefeicao = date('Y-m-d H:i:s');

                $idRefeicao = $refeicao->inserir($dadosPessoa->id, $dtRefeicao);
                
                foreach ($alimentos as $alimento) {
                    if (isset($alimento['id_alimento'], $alimento['nu_consumo'], $alimento['id_refeicao_tipo'])) {
                        $refeicaoAlimento->inserir(
                            $idRefeicao,
                            $alimento['id_alimento'],
                            $alimento['nu_consumo'],
                            $alimento['id_refeicao_tipo']
                        );
                    } else {
                        throw new Exception('Dados insuficientes em um dos alimentos.');
                    }
                }

                echo json_encode([
                    'success' => true,
                    'message' => 'Refeição e alimentos inseridos com sucesso.',
                    'id_refeicao' => $idRefeicao
                ]);
            } else {
                echo json_encode([
                    'success' => false,
                    'message' => 'Usuário do telegram ainda não vinculado.'
                ]);
            }
        } catch (Exception $e) {
            http_response_code(500);
            echo json_encode([
                'success' => false,
                'message' => 'Erro ao inserir refeição ou alimentos: ' . $e->getMessage()
            ]);
        }
    } else {
        http_response_code(400);
        echo json_encode([
            'success' => false,
            'message' => 'Dados insuficientes para inserção. Necessário id_pessoa e alimentos.'
        ]);
    }
} else {
    http_response_code(405);
    echo json_encode([
        'success' => false,
        'message' => 'Método não permitido. Use POST.'
    ]);
}
?>