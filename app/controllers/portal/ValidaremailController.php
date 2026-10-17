<?php

namespace app\controllers\portal;

use app\controllers\ContainerController;
use app\models\portal\Pessoa;
use app\models\portal\User;

class ValidaremailController extends ContainerController {

    public function index() {
        $id = isset($_GET['id']) ? $_GET['id'] : '';
        $validar = isset($_GET['validar']) ? $_GET['validar'] : '';
        $arDados = $this->carregarDados();
        $mensagem = "Não foi possível validar o e-mail";
        if(!empty($id)) {
            $dbPessoa = new Pessoa();
            $dbUser = new User();
            $dadosPessoa = $dbPessoa->getDadosPessoaByIdPessoa($id);
            if(isset($dadosPessoa->id)) {
                $dataEspecifica = new \DateTime($dadosPessoa->ts_validaemail);
                $dataAtual = new \DateTime();
                $difereca = $dataAtual->diff($dataEspecifica);
                $horasDiferenca = ($difereca->days * 24) + $difereca->h;
                if ($horasDiferenca <= 24) {
                    $hash = $dadosPessoa->nome . $dadosPessoa->id;
                    if (password_verify($hash, $validar)) {
                        $dbPessoa->alterarStatusValidacaoEmail(true, $dadosPessoa->id);
                        $dbUser->alterarStatusValidacaoUsuario(true, $dadosPessoa->id);
                        $mensagem = "E-mail validado com sucesso.";
                    } else {
                        $mensagem = "Link de validação inválido. Caso o erro persista, solicite um novo link tentando logar no site.";
                    }
                } else {
                    $mensagem =  "O link de validação expirou, solicite um novo e-mail de validação tentando logar no site.";
                }
            }
        }
        $this->view([
            'title' => 'Validar E-mail',
            'dados' => $arDados,
            'mensagem' => $mensagem,
        ], 'portal.validaremail');
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
    
}

?>