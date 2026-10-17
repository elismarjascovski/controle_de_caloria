<?php

namespace app\controllers\portal;

use app\controllers\ContainerController;

class ErroController extends ContainerController {

    public function index() {
        $arDados = $this->carregarDados();
        $arDados['erro'] = 'Erro ao carregar a página.';

        $this->view([
            'title' => 'Erro',
            'dados' => $arDados,
            'location' => '/'
        ], 'portal.erro');
    }

    public function google() {
        $arDados = $this->carregarDados();
        $arDados['erro'] = 'Login com o google inválido ou inativo.';

        $this->view([
            'title' => 'Erro',
            'dados' => $arDados,
            'location' => '/'
        ], 'portal.erro');
    }

    public function telegram() {
        $arDados = $this->carregarDados();
        $arDados['erro'] = 'Autenticação com o telegram falhou.';

        $this->view([
            'title' => 'Erro',
            'dados' => $arDados,
            'location' => '/perfil'
        ], 'portal.erro');
    }

}