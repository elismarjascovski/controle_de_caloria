<?php

namespace app\controllers\portal;

if (!isset($_SESSION)) {
    session_start();
}

use app\controllers\ContainerController;
use app\models\portal\User;
use Google\Client as GoogleClient;

class HomeController extends ContainerController {

    public function index() {
        $arDados = $this->carregarDados();
    
        $this->view([
            'title' => 'Controle de Calorias',
            'dados' => $arDados,
        ], 'portal.home');
    }

    public function logar() {
        $email = $_POST['email'];
        $senha = $_POST['senha'];
        $dbUsuario = new User;
        $usuarioLogado = $dbUsuario->validarLogin($email, $senha);

        if(!$usuarioLogado) {
            $arDados = $this->carregarDados();
            $arDados['errologin'] = 'E-mail ou senha inválido.';
            $this->view([
                'title' => 'Controle de Calorias',
                'dados' => $arDados,
            ], 'portal.home');
            exit;
        }

        header("Location: /alimento");
        exit;
    }

    public function logargoogle() {

        if(!isset($_POST['credential']) || !isset($_POST['g_csrf_token'])) {
            header("Location: /");
            exit;
        }
        
        $cookie = $_COOKIE['g_csrf_token'] ?? '';
        
        if($_POST['g_csrf_token'] != $cookie) {
            header("Location: /");
            exit;
        }
        
        $client = new GoogleClient(['client_id' => CLIENT_ID_GOOGLE]); 
        
        $payload = $client->verifyIdToken($_POST['credential']);
        
        if ($payload) {
            $userid = $payload['sub'];
            $email = $payload['email'];
            $nome = $payload['name'];
            $dbUsuario = new User;
            if($dbUsuario->checkEmailGoogle($email)) {
                $usuarioLogado = $dbUsuario->validarLoginGoogle($email, $userid);
                if(!$usuarioLogado) {
                    header("Location: /erro/google");
                    exit;
                }
            } else {
                header("Location: /cadastrar");
                exit;
            }
        }
    
        header("Location: /alimento");
        exit;
    }

    public function logout() {
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }
        
        session_unset();
        session_destroy();
        
        header("Location: /");
        exit;
    }
}