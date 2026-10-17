<?php

namespace app\controllers;

use app\traits\View;
use app\models\portal\User;
use app\classes\Uri;

abstract class ContainerController {
    use View;

    public function carregarDados() {
        $arDados = [];
        if (substr_count(Uri::uri(), '/') > 1) {
            $arDados['img_cabecalho'] = '../assets/images/cabecalho.png';
        } else {
            $arDados['img_cabecalho'] = 'assets/images/cabecalho.png';
        }
        if (isset($_SESSION['id_usuario'])) {
            $dbUser = new User();
            $arDados['user'] = $dbUser->getDadosUsuariById($_SESSION['id_usuario']);
            $arDados['menuFile'] = 'menu_login.html';
            $arDados['nomeUsuario'] =  $_SESSION['no_usuario'];
        } else {
            $arDados['menuFile'] = 'menu_sem_login.html';
        }
        return $arDados;
    }
}