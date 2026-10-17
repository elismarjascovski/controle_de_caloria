<?php

namespace app\controllers\portal;

use app\controllers\ContainerController;
use app\models\portal\Alimento;
use app\models\portal\User;

class AlimentoController extends ContainerController {

    public function index() {
        $arDados = $this->carregarDados();
        $dbAlimento = new Alimento();
        $dbUser = new User();

        $usuarioLogado = $dbUser->getDadosUsuarioLogado();
        $arAlimentos = $dbAlimento->getListaAlimentosByIdPessoa($usuarioLogado->id_pessoa);
        $arAlimentosCadastrados = $dbAlimento->getListaAlimentosCadastradosByIdPessoa($usuarioLogado->id_pessoa);
        usort($arAlimentos, function($a, $b) {
            return strcmp($a->ds_alimento, $b->ds_alimento);
        });

        $this->view([
            'title' => 'Alimento',
            'dados' => $arDados,
            'alimentos' => $arAlimentos,
            'alimentos_cadastrados' => $arAlimentosCadastrados
        ], 'portal.alimento');
    }

    public function show($request) {
        $this->view([
            'title' => 'Alimento',
            'alimento' => "It is a long established fact that a reader will be distracted by the readable content of a page when looking at its layout. The point of using Lorem Ipsum is that it has a more-or-less normal distribution of letters, as opposed to using 'Content here, content here', making it look like readable English. Many desktop publishing packages and web page editors now use Lorem Ipsum as their default model text, and a search for 'lorem ipsum' will uncover many web sites still in their infancy. Various versions have evolved over the years, sometimes by accident, sometimes on purpose (injected humour and the like).",
        ],'portal.alimento');
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

    public function pesquisarDadosAlimentoAction() {
        $response = [];
        $idAlimento = $_POST['id_alimento'];

        $dbAlimento = new Alimento();
        $dadosAlimento = $dbAlimento->getDadosAlimentoById($idAlimento);
                
        if ($dadosAlimento) {
            $response = [
                'status' => 'success',
                'dados_alimento' => $dadosAlimento
            ];
        } else {
            $response = [
                'status' => 'error',
                'message' => 'Dados do alimento selecionado não foram encontrados.'
            ];
        }

        if(empty($response)) {
            $response = [
                'status' => 'error',
                'message' => 'Erro ao cadastrar o usuário.'
            ];
        }

        header('Content-Type: application/json');
        echo json_encode($response);
        exit();
    }

    public function excluirAlimentoAction() {
        $response = [];
        $idAlimento = $_POST['id_alimento'];

        $dbAlimento = new Alimento();
        $boAlimentoUtiliazado = $dbAlimento->checkAlimentoUtilizadoById($idAlimento);

        if(!$boAlimentoUtiliazado) {
            $dbAlimento->excluir($idAlimento);
            $response = [
                'status' => 'success',
                'message' => 'Alimento excluído com sucesso.'
            ];
        } else {
            $response = [
                'status' => 'error',
                'message' => 'Não foi possível excluir o alimento, pois ele está sendo utilizado nas refeições.'
            ];
        }

        if(empty($response)) {
            $response = [
                'status' => 'error',
                'message' => 'Erro ao excluir a refeição.'
            ];
        }
        // Retorna a resposta como JSON
        header('Content-Type: application/json');
        echo json_encode($response);
        exit();
    }

}

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['action']) && $_POST['action'] === 'pesquisarDadosAlimento') {
    $controller = new AlimentoController();
    $controller->pesquisarDadosAlimentoAction();
}
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['action']) && $_POST['action'] === 'excluirAlimento') {
    $controller = new AlimentoController();
    $controller->excluirAlimentoAction();
}
?>