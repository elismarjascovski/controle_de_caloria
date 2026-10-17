<?php

namespace app\controllers\portal;

use app\controllers\ContainerController;
use app\models\portal\Pessoa;

class PerfilController extends ContainerController {

    public function index() {
        $arDados = $this->carregarDados();
        $this->view([
            'title' => 'Perfil',
            'dados' => $arDados
        ], 'portal.perfil');
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
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $nome = $_POST['nome'] ?? null;
            $email = $_POST['email'] ?? null;
            $nascimento = $_POST['nascimento'] ?? null;
            $peso = $_POST['peso'] ?? null;
            $altura = $_POST['altura'] ?? null;
            $idTelegram = $_POST['id_telegram'] ?? null;
            $dsGenero = $_POST['ds_genero'] ?? null;
    
            if ($nome && $email && $nascimento && $peso && $altura) {
                $id = is_object($id) ? $id->parameter : $id;
                $pessoa = new Pessoa();
                $idPessoa = $pessoa->alterar($nome, $email, $nascimento, $peso, $altura, $idTelegram, $dsGenero, $id);
                
                header("Location: /perfil?id={$id}");
                exit;
            } else {
                $this->view([
                    'title' => 'Perfil',
                    'error' => 'Todos os campos são obrigatórios'
                ], 'portal.perfil');
            }
        }
    }

    public function destroy($id) {
        
    }

}