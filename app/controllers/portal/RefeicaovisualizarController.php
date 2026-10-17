<?php

namespace app\controllers\portal;

use app\controllers\ContainerController;
use app\models\portal\Refeicao;
use app\models\portal\RefeicaoAlimento;
use app\models\portal\RefeicaoTipo;
use app\models\portal\User;
use app\models\portal\Alimento;


class RefeicaoVisualizarController extends ContainerController {

    public function index() {
        $arDados = $this->carregarDados();
        $dbRefeicao = new Refeicao();
        $dbRefeicaoTipo = new RefeicaoTipo();
        $dbUser = new User();
        $dbAlimento = new Alimento();
        $dtRefeicao =  $_GET['dt_refeicao'];
        $dataFormatada = \DateTime::createFromFormat('d/m/Y', $dtRefeicao)->format('Y-m-d');

        $arAlimentos = $dbAlimento->all();
        usort($arAlimentos, function($a, $b) {
            return strcmp($a->ds_alimento, $b->ds_alimento);
        });
    
        $dadosUsuario = $dbUser->getDadosUsuarioLogado();
        $refeicoesAtuais = $dbRefeicao->getDadosRefeicaoDiaAtualByIdPessoa($dadosUsuario->id_pessoa, $dataFormatada);
        $tipoRefeicao = $dbRefeicaoTipo->getTipoRefeicaoByUsuarioLogado();
        $arTpRefeicao = [];
        $arTotalCalorias = [];
        $arTotalProteinas = [];
        $arTotalFibras = [];
        $arTotalCarboidratos = [];
        $arCorTpRefeicao = [];
        $consumoCalorias = 0;
        $consumoProteinas = 0;
        $consumoFibras = 0;
        $consumoCarboidratos = 0;
        foreach ($refeicoesAtuais as $key => $refeicao) {
            if(!in_array($refeicao->tp_refeicao, $arTpRefeicao)) {
                $arTpRefeicao[] = $refeicao->tp_refeicao;
                $arCorTpRefeicao[] = $dbRefeicaoTipo->getCorByIdTipoRefeicao($refeicao->id_refeicao_tipo)->cor;
                $arTotalCalorias[$refeicao->tp_refeicao] = 0;
                $arTotalProteinas[$refeicao->tp_refeicao] = 0;
                $arTotalFibras[$refeicao->tp_refeicao] = 0;
                $arTotalCarboidratos[$refeicao->tp_refeicao] = 0;
            }
            $nu_energia = str_replace(',', '.', $refeicao->nu_energia);
            if (is_numeric($nu_energia) && $nu_energia > 0) {
                $consumoCalorias += round((str_replace(',', '.', $refeicao->nu_energia) / $refeicao->quantidade) * $refeicao->nu_consumo, 2);
                $refeicoesAtuais[$key]->nu_energia = round((str_replace(',', '.', $refeicao->nu_energia) / $refeicao->quantidade) * $refeicao->nu_consumo, 2);
                $arTotalCalorias[$refeicao->tp_refeicao] += $refeicoesAtuais[$key]->nu_energia;
            }
            $nu_proteina = str_replace(',', '.', $refeicao->nu_proteina);
            if (is_numeric($nu_proteina) && $nu_proteina > 0) {
                $consumoProteinas += round((str_replace(',', '.', $refeicao->nu_proteina) / $refeicao->quantidade) * $refeicao->nu_consumo, 2);
                $refeicoesAtuais[$key]->nu_proteina = round((str_replace(',', '.', $refeicao->nu_proteina) / $refeicao->quantidade) * $refeicao->nu_consumo, 2);
                $arTotalProteinas[$refeicao->tp_refeicao] += $refeicoesAtuais[$key]->nu_proteina;
            }
            $nu_carboidrato = str_replace(',', '.', $refeicao->nu_carboidrato);
            if (is_numeric($nu_carboidrato) && $nu_carboidrato > 0) {
                $consumoCarboidratos += round((str_replace(',', '.', $refeicao->nu_carboidrato) / $refeicao->quantidade) * $refeicao->nu_consumo, 2);
                $refeicoesAtuais[$key]->nu_carboidrato = round((str_replace(',', '.', $refeicao->nu_carboidrato) / $refeicao->quantidade) * $refeicao->nu_consumo, 2);
                $arTotalCarboidratos[$refeicao->tp_refeicao] += $refeicoesAtuais[$key]->nu_carboidrato;
            }
            $nu_fibra = str_replace(',', '.', $refeicao->nu_fibra);
            if (is_numeric($nu_fibra) && $nu_fibra > 0) {
                $consumoFibras += round((str_replace(',', '.', $refeicao->nu_fibra) / $refeicao->quantidade) * $refeicao->nu_consumo, 2);
                $refeicoesAtuais[$key]->nu_fibra = round((str_replace(',', '.', $refeicao->nu_fibra) / $refeicao->quantidade) * $refeicao->nu_consumo, 2);
                $arTotalFibras[$refeicao->tp_refeicao] += $refeicoesAtuais[$key]->nu_fibra;
            }
        }
        
        $tpRefeicao = implode('", "', $arTpRefeicao);
        $totalCalorias = implode('", "', $arTotalCalorias);
        $totalProteinas = implode('", "', $arTotalProteinas);
        $totalFibras = implode('", "', $arTotalFibras);
        $totalCarboidratos = implode('", "', $arTotalCarboidratos);

        $tpCorRefeicao = implode('", "', $arCorTpRefeicao);

        $tpRefeicao = '"' . $tpRefeicao . '"';
        $totalCalorias = '"' . $totalCalorias . '"';
        $totalProteinas = '"' . $totalProteinas . '"';
        $totalFibras = '"' . $totalFibras . '"';
        $totalCarboidratos = '"' . $totalCarboidratos . '"';

        $tpCorRefeicao = '"' . $tpCorRefeicao . '"';

        $this->view([
            'title' => 'Refeição - Visualizar',
            'grafico_legenda' => $tpRefeicao,
            'grafico_valores_calorias' => $totalCalorias,
            'grafico_valores_proteinas' => $totalProteinas,
            'grafico_valores_fibras' => $totalFibras,
            'grafico_valores_carboidratos' => $totalCarboidratos,
            'total_calorias' => round($consumoCalorias, 0),
            'total_proteinas' => round($consumoProteinas, 0),
            'total_fibras' => round($consumoFibras, 0),
            'total_carboidratos' => round($consumoCarboidratos, 0),
            'cor_refeicao' => $tpCorRefeicao,
            'dados' => $arDados,
            'refeicoesatuais' => $refeicoesAtuais,
            'alimentos' => $arAlimentos,
            'tipo_refeicao' => $tipoRefeicao,
            'data_atual' => date('Y-m-d'),
            'dt_refeicao' => $dtRefeicao
        ], 'portal.refeicaovisualizar');
    }
    

    public function show($request) {

    }

    public function create() {
        
    }

    public function store() {
        
    }

    public function edit($id) {
        
    }

    public function update($id) {
        
    }

    public function destroy($id) {
        
    }

    public function inserirRefeicaoAction() {
        $response = [];
        $idAlimento = $_POST['id_alimento'];
        $consumo = $_POST['consumo'];
        $dtRefeicao = $_POST['dt_refeicao'];
        $tpRefeicao = $_POST['tp_refeicao'];

        $dbUser = new User();
        $dbRefeicao = new Refeicao();
        $dbRefeicaoAlimento = new RefeicaoAlimento();

        $dadosUsuario = $dbUser->getDadosUsuarioLogado();
        $idRefeicao = $dbRefeicao->inserir($dadosUsuario->id_pessoa, $dtRefeicao);
        $dbRefeicaoAlimento->inserir($idRefeicao, $idAlimento, $consumo, $tpRefeicao);

        if ($idRefeicao) {
            $response = [
                'status' => 'success',
                'message' => 'Refeição adicionada com sucesso.'
            ];
        } else {
            $response = [
                'status' => 'error',
                'message' => 'Não foi possível adicionar a refeição.'
            ];
        }

        if(empty($response)) {
            $response = [
                'status' => 'error',
                'message' => 'Erro ao adicionar a refeição.'
            ];
        }
        header('Content-Type: application/json');
        echo json_encode($response);
        exit();
    }

    public function alterarRefeicaoAction() {
        $response = [];
        $idRefeicao = $_POST['id_refeicao'];
        $idAlimento = $_POST['id_alimento'];
        $consumo = $_POST['consumo'];
        $dtRefeicao = $_POST['dt_refeicao'];
        $tpRefeicao = $_POST['tp_refeicao'];

        $dbUser = new User();
        $dbRefeicao = new Refeicao();
        $dbRefeicaoAlimento = new RefeicaoAlimento();

        $dadosUsuario = $dbUser->getDadosUsuarioLogado();
        $idRefeicao = $dbRefeicao->alterar($idRefeicao, $dtRefeicao);
        $dbRefeicaoAlimento->alterar($idRefeicao, $idAlimento, $consumo, $tpRefeicao);

        if ($idRefeicao) {
            $response = [
                'status' => 'success',
                'message' => 'Refeição alterada com sucesso.'
            ];
        } else {
            $response = [
                'status' => 'error',
                'message' => 'Não foi possível alterar a refeição.'
            ];
        }

        if(empty($response)) {
            $response = [
                'status' => 'error',
                'message' => 'Erro ao adicionar a refeição.'
            ];
        }
        header('Content-Type: application/json');
        echo json_encode($response);
        exit();
    }

    public function excluirRefeicaoAction() {
        $response = [];
        $idRefeicao = $_POST['id_refeicao'];

        $dbRefeicao = new Refeicao();
        $dbRefeicaoAlimento = new RefeicaoAlimento();
        $dbRefeicaoAlimento->excluir($idRefeicao);
        $dbRefeicao->excluir($idRefeicao);


        if ($idRefeicao) {
            $response = [
                'status' => 'success',
                'message' => 'Refeição excluída com sucesso.'
            ];
        } else {
            $response = [
                'status' => 'error',
                'message' => 'Não foi possível excluir a refeição.'
            ];
        }

        if(empty($response)) {
            $response = [
                'status' => 'error',
                'message' => 'Erro ao excluir a refeição.'
            ];
        }
        header('Content-Type: application/json');
        echo json_encode($response);
        exit();
    }
    
}

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['action']) && $_POST['action'] === 'inserirRefeicao') {
    $controller = new RefeicaoController();
    $controller->inserirRefeicaoAction();
}
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['action']) && $_POST['action'] === 'alterarRefeicao') {
    $controller = new RefeicaoController();
    $controller->alterarRefeicaoAction();
}
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['action']) && $_POST['action'] === 'excluirRefeicao') {
    $controller = new RefeicaoController();
    $controller->excluirRefeicaoAction();
}


?>