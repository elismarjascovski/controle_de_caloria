<?php

require_once '../vendor/autoload.php';
require_once '../app/models/portal/RequisicoesBot.php';

date_default_timezone_set('America/Sao_Paulo');

if ($_SERVER['REQUEST_METHOD'] === 'POST') {

    $jsonData = file_get_contents("php://input");
    $data = json_decode($jsonData, true);

    if (isset($data['id_pessoa'], $data['function'])) {
        $idPessoa = $data['id_pessoa'];
        $function = $data['function'];

        $pessoa = new \app\models\portal\Pessoa();
        $requisicoesBot = new \app\models\portal\RequisicoesBot();
        
        try {
            $dadosPessoa = $pessoa->getDadosPessoaByIdTelegram($idPessoa);
            if($dadosPessoa) {
                switch ($function) {
                    case 'verificar_pessoa':
                        if(isset($dadosPessoa->id)) {
                            echo json_encode([
                                'success' => true,
                                'message' => 'Pessoa Cadastrada.'
                            ]);
                        } else {
                            echo json_encode([
                                'success' => false,
                                'message' => 'Pessoa não está cadastrada.'
                            ]);
                        }
                        break;
                    case 'limpar_requisicao':
                        $requisicoesBot->excluirRequisicaoByIdPessoa($dadosPessoa->id);
                        echo json_encode([
                            'success' => true,
                            'message' => 'Requisições resetadas com sucesso.',
                            'pessoa' => $dadosPessoa->id
                        ]);
                        break;
                    case 'inserir_refeicao_inicio':
                        $requisicoesBot->inserir($dadosPessoa->id, intval($data['linha_chat']), $data['ds_message']);
                        echo json_encode([
                            'success' => true,
                            'message' => 'Requisição inciada com sucesso.',
                        ]);
                        break;
                    case 'get_ultima_requisicao':
                        $resultado = $requisicoesBot->getDadosUltimaRequisicaoByIdPessoa($dadosPessoa->id);
                        echo json_encode([
                            'success' => true,
                            'message' => 'Requisição pesquisadas com sucesso.',
                            'requisicao' => $resultado->ds_requisicao
                        ]);
                        break;
                    case 'inserir_alimento_selecionado':
                        $requisicoesBot->atualizarRequerimentoAlimento($dadosPessoa->id, $data['ds_requisicao'], $data['id_alimento']);
                        echo json_encode([
                            'success' => true,
                            'message' => 'Requisição atualizada de alimento com sucesso.',
                            'requisicao' => $data['ds_requisicao']
                        ]);
                        break;
                    case 'inserir_refeicao':
                        $refeicao = new \app\models\portal\Refeicao();
                        $refeicaoAlimento = new \app\models\portal\RefeicaoAlimento();
                        $dtRefeicao = date('Y-m-d H:i:s');
                        $idRefeicao = $refeicao->inserir($dadosPessoa->id, $dtRefeicao);
                        $resultado = $requisicoesBot->getDadosUltimaRequisicaoByIdPessoa($dadosPessoa->id);
                        $refeicaoAlimento->inserir($idRefeicao, $resultado->id_alimento, $data['quantidade'], $resultado->id_refeicao_tipo);
                        echo json_encode([
                            'success' => true,
                            'message' => 'Refeição inserida com sucesso'
                        ]);
                        break;
                    case 'inserir_refeicao_tipo':
                        $requisicoesBot->atualizarRequerimentoRefeicaoTipo($dadosPessoa->id, $data['ds_requisicao'], $data['id_tipo_refeicao']);
                        echo json_encode([
                            'success' => true,
                            'message' => 'Requisição atualizada de refeição tipo com sucesso.',
                            'requisicao' => $data['ds_requisicao']
                        ]);
                        break;
                    case 'consumo_diario':
                        $refeicao = new \app\models\portal\Refeicao();
                        $refeicoesAtuais = $refeicao->getDadosRefeicaoDiaAtualByIdPessoa($dadosPessoa->id);
                        $consumoCalorias = 0;
                        $consumoProteinas = 0;
                        $consumoFibras = 0;
                        $consumoCarboidratos = 0;
                        foreach ($refeicoesAtuais as $key => $refeicao) {
                            $nu_energia = str_replace(',', '.', $refeicao->nu_energia);
                            if (is_numeric($nu_energia) && $nu_energia > 0) {
                                $consumoCalorias += round((str_replace(',', '.', $refeicao->nu_energia) / $refeicao->quantidade) * $refeicao->nu_consumo, 2);
                                $refeicoesAtuais[$key]->nu_energia = round((str_replace(',', '.', $refeicao->nu_energia) / $refeicao->quantidade) * $refeicao->nu_consumo, 2);
                            }
                            $nu_proteina = str_replace(',', '.', $refeicao->nu_proteina);
                            if (is_numeric($nu_proteina) && $nu_proteina > 0) {
                                $consumoProteinas += round((str_replace(',', '.', $refeicao->nu_proteina) / $refeicao->quantidade) * $refeicao->nu_consumo, 2);
                                $refeicoesAtuais[$key]->nu_proteina = round((str_replace(',', '.', $refeicao->nu_proteina) / $refeicao->quantidade) * $refeicao->nu_consumo, 2);
                            }
                            $nu_carboidrato = str_replace(',', '.', $refeicao->nu_carboidrato);
                            if (is_numeric($nu_carboidrato) && $nu_carboidrato > 0) {
                                $consumoCarboidratos += round((str_replace(',', '.', $refeicao->nu_carboidrato) / $refeicao->quantidade) * $refeicao->nu_consumo, 2);
                                $refeicoesAtuais[$key]->nu_carboidrato = round((str_replace(',', '.', $refeicao->nu_carboidrato) / $refeicao->quantidade) * $refeicao->nu_consumo, 2);
                            }
                            $nu_fibra = str_replace(',', '.', $refeicao->nu_fibra);
                            if (is_numeric($nu_fibra) && $nu_fibra > 0) {
                                $consumoFibras += round((str_replace(',', '.', $refeicao->nu_fibra) / $refeicao->quantidade) * $refeicao->nu_consumo, 2);
                                $refeicoesAtuais[$key]->nu_fibra = round((str_replace(',', '.', $refeicao->nu_fibra) / $refeicao->quantidade) * $refeicao->nu_consumo, 2);
                            }
                        }
                        $msg = "\nCalorias Consumidas: " . round($consumoCalorias, 0) . " (kcal)" .
                                "\nProteínas Consumidas: " . round($consumoProteinas, 0) . " (gramas)" .
                                "\nFibras Consumidas: " . round($consumoFibras, 0) . " (gramas)" .
                                "\nCarboidratos Consumidos: " . round($consumoCarboidratos, 0) . " (gramas)";

                        echo json_encode([
                            'success' => true,
                            'message' => $msg
                        ]);
                        break;
                    default:
                        echo json_encode([
                            'success' => false,
                            'message' => 'Função requisitada não existe: ' . $function,
                            'pessoa' => $function
                        ]);
                }
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
                'message' => 'Erro ao iniciar o bot: ' . $e->getMessage()
            ]);
        }
    } else {
        http_response_code(400);
        echo json_encode([
            'success' => false,
            'message' => 'Dados insuficientes para iniciar o bot.'
        ]);
    }
} else {
    http_response_code(405);
    echo json_encode([
        'success' => false,
        'message' => 'Método não permitido. Use POST.'
    ]);
}