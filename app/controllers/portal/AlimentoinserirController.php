<?php

namespace app\controllers\portal;

use app\controllers\ContainerController;
use app\models\portal\Alimento;
use app\models\portal\User;

class AlimentoinserirController extends ContainerController {

    public function index() {
        $arDados = $this->carregarDados();

        $dbAlimento = new Alimento();
        $arTpAlimento = $dbAlimento->getListaTipoAlimento();
        $listaTipoAlimentos = [];
        foreach($arTpAlimento as $tpAlimento) {
            $listaTipoAlimentos[] =  ['id' => $tpAlimento->tp_alimento, 'tp_alimento' => $tpAlimento->tp_alimento];
        }

        $this->view([
            'title' => 'Alimento - Inserir',
            'dados' => $arDados,
            'listaTipoAlimento' => $listaTipoAlimentos
        ], 'portal.alimentoinserir');
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

    public function inserir() {
        $tpAlimento = $_POST['tp_alimento'];
        $dsAlimento = $_POST['ds_alimento'];
        $quantidade = $_POST['quantidade'];
        $nuEnergia = $_POST['nu_energia'];
        $nuProteina = $_POST['nu_proteina'];
        $nuCarboidrato = $_POST['nu_carboidrato'];
        $nuFibra = $_POST['nu_fibra'];

        $dbAlimento = new Alimento();
        $dbUser = new User();
        $usuarioLogado = $dbUser->getDadosUsuarioLogado();
        $idAlimento = $dbAlimento->inserir($dsAlimento, $tpAlimento, $nuEnergia, $nuProteina, $nuCarboidrato, $nuFibra, $quantidade, $usuarioLogado->id_pessoa);

        header("Location: /alimento");
        exit;
    }

}