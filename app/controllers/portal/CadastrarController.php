<?php

namespace app\controllers\portal;

require_once '../vendor/autoload.php';

use app\controllers\ContainerController;
use Google\Client as GoogleClient;
use app\models\portal\Pessoa;
use app\models\portal\User;
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

class CadastrarController extends ContainerController {

    public function index() {
        $nome = isset($_GET['nome']) ? $_GET['nome'] : '';
        $email = isset($_GET['email']) ? $_GET['email'] : '';
        $credential = isset($_GET['credential']) ? $_GET['credential'] : '';

        $arDados = $this->carregarDados();
        $arDados['menuFile'] = 'menu_cadastrar.html';

        $this->view([
            'title' => 'Cadastrar',
            'dados' => $arDados,
            'nome' => $nome,
            'email' => $email,
            'credential' => $credential, // Passando o nome do arquivo de menu
        ], 'portal.cadastrar');
    }

    public function enviarEmailValidacao($email, $nome, $idPessoa) {
        $hash = password_hash($nome . $idPessoa, CRYPT_SHA256);

        $linkValidacao = \SITE_URL . "/validaremail?id={$idPessoa}&validar={$hash}";
        $mail = new PHPMailer(true);

        try {
            $mail->isSMTP();
            $mail->Host       = 'smtp.gmail.com';
            $mail->SMTPAuth   = true;
            $mail->Username   = 'elismarjascovski@gmail.com';
            $mail->Password   = PASSOWORD_EMAIL_GOOGLE;
            $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS; 
            $mail->Port       = 587;

            $mail->SMTPOptions = [
                'ssl' => [
                    'verify_peer'       => false,
                    'verify_peer_name'  => false,
                    'allow_self_signed' => true
                ]
            ];

            $mail->CharSet = 'UTF-8';
            $mail->setFrom('elismarjascovski@gmail.com', 'Controle de Calorias');
            $mail->addAddress($email, $nome);
            $mail->Subject = 'Confirme seu e-mail!';
            $mail->isHTML(true);
            $mail->Body    = "<html><head><meta charset='UTF-8'></head><body><p>Parabéns por se cadastrar no Controle de Calorias.</p><p>Para validar o seu e-mail <a href='{$linkValidacao}'>Clique Aqui</a>.</p></body></html>";
            $mail->AltBody = "Caso o link não esteja abrindo, acesse {$linkValidacao}";

            return $mail->send();
        } catch (Exception $e) {
            echo "Erro ao enviar e-mail: {$mail->ErrorInfo}";
            return false;
        }
    }

    public function show($request) {
        
    }

    public function create() {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $nome = $_POST['nome'];
            $email = $_POST['email'];
            $nascimento = $_POST['nascimento'];
            $senha = $_POST['senha'];
            if ($nome && $email && $senha && $nascimento) {
                $dbUser = new User();
                $dbPessoa = new Pessoa();
                if(!$dbUser->checkEmailGoogle($email)) {
                    $idPessoa = $dbPessoa->inserirCadastro($nome, $email, $nascimento);
                    $dbUser->inserirUsuario($idPessoa, $senha);
                    $this->enviarEmailValidacao($email, $nome, $idPessoa);
                } else {
                    header("Location: /cadastrar/cadastroexiste");
                    exit;
                }
            } else {
                // Redireciona de volta com erro (implemente a lógica de tratamento de erro conforme necessário)
                header("Location: /cadastrar/errocadastro");
                exit;
            }               
            $this->confirmacaocadastro();
            exit;
        }
    }

    public function creategoogle() {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $client = new GoogleClient(['client_id' => CLIENT_ID_GOOGLE]); 
            $payload = $client->verifyIdToken($_POST['credential']);
            if ($payload) {
                $userid = $payload['sub'];
                $email = $payload['email'];
                $nome = $payload['name'];
                $dbUsuario = new User;
                if(!$dbUsuario->checkEmailGoogle($email)) {
                    $dbPessoa = new Pessoa();
                    $idPessoa = $dbPessoa->inserirCadastroGoogle($nome, $email, true);
                    $dbUsuario->inserirUsuarioGoogle($idPessoa, $userid);
                    $this->enviarEmailValidacao($email, $nome, $idPessoa);
                }
            } else {
                header("Location: /cadastrar/errocadastrogoogle");
                exit;
            }
            header("Location: /");
            exit;
        }
    }

    public function errocadastro() {
        $arDados = $this->carregarDados();
        $arDados['menuFile'] = 'menu_cadastrar.html';
        $arDados['errologin'] = 'Erro ao cadastrar dados informados';
        $this->view([
            'title' => 'Perfil',
            'dados' => $arDados // Passando o nome do arquivo de menu
        ], 'portal.cadastrar');
    }

    public function errocadastrogoogle() {
        $arDados = $this->carregarDados();
        $arDados['menuFile'] = 'menu_cadastrar.html';
        $arDados['errologin'] = 'Erro para validar dados como Google';
        $this->view([
            'title' => 'Perfil',
            'dados' => $arDados
        ], 'portal.cadastrar');
    }

    public function cadastroexiste() {
        $arDados = $this->carregarDados();
        $arDados['menuFile'] = 'menu_cadastrar.html';
        $arDados['errologin'] = 'E-mail do usuário já está cadastrado, volte para a home e faça o login.';
        $this->view([
            'title' => 'Perfil',
            'dados' => $arDados
        ], 'portal.cadastrar');
    }

    public function confirmacaocadastro() {
        $arDados = $this->carregarDados();
        $arDados['menuFile'] = 'menu_cadastrar.html';
        $mensagem = "Cadastro realizado, em breve você recebera um e-mail para validação, para poder ter acesso ao site.";
        $this->view([
            'title' => 'Confirmar E-mail',
            'dados' => $arDados,
            'mensagem' => $mensagem,
        ], 'portal.validaremail');
    }

    public function store() {
        
    }

    public function edit($id) {
        
    }

    public function update($id) {
        
    }

    public function destroy($id) {
        
    }

}