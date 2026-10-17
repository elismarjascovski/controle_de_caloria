<?php

namespace app\controllers\portal;

use app\controllers\ContainerController;
use app\models\portal\Refeicao;
use app\models\portal\User;
use app\models\portal\RefeicaoAlimento;
use app\models\portal\PessoaMeta;

class RefeicaohistoricoController extends ContainerController {

    public function index() {
        $arDados = $this->carregarDados();
        $dbRefeicao = new Refeicao();
        $dbUser = new User();
        $dbPessoaMeta = new PessoaMeta();

        $hoje = new \DateTime();
        $segunda = clone $hoje->modify(('Monday' == $hoje->format('l')) ? 'this Monday' : 'last Monday');
        $datasDaSemana = [];
        for ($i = 0; $i < 7; $i++) {
            $datasDaSemana[] = $segunda->format('d/m/Y');
            $segunda->modify('+1 day');
        }

        $usuarioLogado = $dbUser->getDadosUsuarioLogado();
        $dadosHistorico = $dbRefeicao->getHistoricoRefeicaoByIdPessoa($usuarioLogado->id_pessoa);
        $dadosMeta = $dbPessoaMeta->getHistoricoDadosPessoaMetaByIdPessoa($usuarioLogado->id_pessoa);
        $arCaloriasIngeridas = [];
        $maiorCaloria = 0;
        foreach($datasDaSemana as $diaSemana) {
            $boExisteData = false;
            foreach($dadosHistorico as $value) {
                if($diaSemana == $value->dt_refeicao) {
                    $boExisteData = true;
                    $arCaloriasIngeridas[] = $value->calorias;
                    if($maiorCaloria < $value->calorias) {
                        $maiorCaloria = $value->calorias;
                    }
                }                
            }
            if(!$boExisteData) {
                $arCaloriasIngeridas[] = 0;
            }
        }
        $caloriasIngeridas = implode(', ', $arCaloriasIngeridas);

        $pessoaMeta = isset($dadosMeta->nu_caloriasdiarias) ? $dadosMeta->nu_caloriasdiarias : 0;
        $this->view([
            'title' => 'Refeição - Histórico',
            'dados' => $arDados,
            'historico' => $dadosHistorico,
            'calorias_ingeridas' => $caloriasIngeridas,
            'caloria_maxima' => $maiorCaloria,
            'pessoa_meta' => $pessoaMeta
        ], 'portal.refeicaohistorico');
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

    public function excluirRefeicaoCompletaAction() {
        $response = [];
        $dtRefeicao = $_POST['dt_refeicao'];
        $dataFormatada = \DateTime::createFromFormat('d/m/Y', $dtRefeicao)->format('Y-m-d');

        $dbRefeicao = new Refeicao();
        $dbRefeicaoAlimento = new RefeicaoAlimento();
        $arRefeicao = $dbRefeicao->gereRefeicoesByDtRefeicao($dataFormatada);
        foreach($arRefeicao as $refeicao) {
            $dbRefeicaoAlimento->excluir($refeicao->id);
            $dbRefeicao->excluir($refeicao->id);
        }
        if ($arRefeicao) {
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

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['action']) && $_POST['action'] === 'excluirRefeicaoCompleta') {
    $controller = new RefeicaoHistoricoController();
    $controller->excluirRefeicaoCompletaAction();
}
?>