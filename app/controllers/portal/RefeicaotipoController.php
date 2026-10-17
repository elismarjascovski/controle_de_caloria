<?php

namespace app\controllers\portal;

use app\controllers\ContainerController;
use app\models\portal\Refeicao;
use app\models\portal\RefeicaoTipo;
use app\models\portal\User;

class RefeicaotipoController extends ContainerController {

    public function index() {
        $arDados = $this->carregarDados();
        $dbRefeicaoTipo = new RefeicaoTipo();

        $arRefeicaoTipo = $dbRefeicaoTipo->getTipoRefeicaoByUsuarioLogado();
        usort($arRefeicaoTipo, function($a, $b) {
            return strcmp($a->tp_refeicao, $b->tp_refeicao);
        });

        $this->view([
            'title' => 'Tipo de Refeição',
            'dados' => $arDados, // Passando o nome do arquivo de menu
            'tipo_refeicao' => $arRefeicaoTipo,
        ], 'portal.refeicaotipo');
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

    public function inserirTipoRefeicaoAction() {
        $response = [];
        $tpRefeicao = $_POST['tp_refeicao'];
        $cor = $_POST['cor'];

        $dbRefeicaoTipo = new RefeicaoTipo();
        $dbUser = new User();

        $usuarioLogado = $dbUser->getDadosUsuarioLogado();
        $idRefeicaoTipo = $dbRefeicaoTipo->inserir($tpRefeicao, $cor, $usuarioLogado->id_pessoa);

        if ($idRefeicaoTipo) {
            $response = [
                'status' => 'success',
                'message' => 'Tipo de Refeição adicionada com sucesso.'
            ];
        } else {
            $response = [
                'status' => 'error',
                'message' => 'Não foi possível adicionar o tipo de refeição.'
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

    public function alterarTipoRefeicaoAction() {
        $response = [];
        $idRefeicaoTipo = $_POST['id_refeicaotipo'];
        $tpRefeicao = $_POST['tp_refeicao'];
        $cor = $_POST['cor'];

        $dbRefeicaoTipo = new RefeicaoTipo();
        $idRefeicaoTipo = $dbRefeicaoTipo->alterar($idRefeicaoTipo, $tpRefeicao, $cor);

        if ($idRefeicaoTipo) {
            $response = [
                'status' => 'success',
                'message' => 'Tipo de Refeição alterada com sucesso.'
            ];
        } else {
            $response = [
                'status' => 'error',
                'message' => 'Não foi possível alterar o tipo de refeição.'
            ];
        }

        if(empty($response)) {
            $response = [
                'status' => 'error',
                'message' => 'Erro ao alterar a refeição.'
            ];
        }
        header('Content-Type: application/json');
        echo json_encode($response);
        exit();
    }

    public function carregarDadosRefeicaoAction() {
        $response = [];
        $idRefeicao = $_POST['id_refeicao'];

        $dbRefeicao = new Refeicao();
        $dadosRefeicao = $dbRefeicao->getDadosRefeicaoByIdRefeicao($idRefeicao);
        if ($dadosRefeicao) {
            $response = [
                'status' => 'success',
                'dados' => $dadosRefeicao
            ];
        } else {
            $response = [
                'status' => 'error',
                'message' => 'Não foi possível adicionar o tipo de refeição.'
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

    public function carregarDadosTipoRefeicaoAction() {
        $response = [];
        $idRefeicaoTipo = $_POST['id_refeicaotipo'];

        $dbRefeicaoTipo = new RefeicaoTipo();
        $dadosRefeicaoTipo = $dbRefeicaoTipo->getTipoRefeicaoById($idRefeicaoTipo);
        if ($dadosRefeicaoTipo) {
            $response = [
                'status' => 'success',
                'dados' => $dadosRefeicaoTipo
            ];
        } else {
            $response = [
                'status' => 'error',
                'message' => 'Não foi possível adicionar o tipo de refeição.'
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

    public function excluirDadosRefeicaoAction() {
        $response = [];
        $idRefeicaoTipo = $_POST['id_refeicaotipo'];

        $dbRefeicaoTipo = new RefeicaoTipo();
        $dadosRefeicaoTipo = $dbRefeicaoTipo->checkRefeicaoLancadaByIdRefeicaoTipo($idRefeicaoTipo);
        if (!$dadosRefeicaoTipo) {
            $dbRefeicaoTipo->excluir($idRefeicaoTipo);
            $response = [
                'status' => 'success',
                'message' => 'Tipo de Refeição excluída com sucesso.'
            ];
        } else {
            $response = [
                'status' => 'error',
                'message' => 'Existem refeições adicionadas com esse Tipo de Refeição.'
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
    

}

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['action']) && $_POST['action'] === 'inserirTipoRefeicao') {
    $controller = new RefeicaotipoController();
    $controller->inserirTipoRefeicaoAction();
}
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['action']) && $_POST['action'] === 'carregarDadosRefeicao') {
    $controller = new RefeicaotipoController();
    $controller->carregarDadosRefeicaoAction();
}
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['action']) && $_POST['action'] === 'carregarDadosTipoRefeicao') {
    $controller = new RefeicaotipoController();
    $controller->carregarDadosTipoRefeicaoAction();
}
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['action']) && $_POST['action'] === 'excluirDadosRefeicao') {
    $controller = new RefeicaotipoController();
    $controller->excluirDadosRefeicaoAction();
}
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['action']) && $_POST['action'] === 'alterarTipoRefeicao') {
    $controller = new RefeicaotipoController();
    $controller->alterarTipoRefeicaoAction();
}

?>