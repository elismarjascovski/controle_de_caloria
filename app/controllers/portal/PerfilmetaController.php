<?php

namespace app\controllers\portal;

use app\controllers\ContainerController;
use app\models\portal\PessoaMeta;
use app\models\portal\User;

class PerfilmetaController extends ContainerController {

    public function index() {
        $arDados = $this->carregarDados();
        $dadosUsuario = $arDados['user'];
        $calculoCaloriasDiarias = 0;
        $dbPessoaMeta = new PessoaMeta();
        $historicoDadosPessoaMeta = $dbPessoaMeta->getHistoricoDadosPessoaMetaByIdPessoa($arDados['user']->id_pessoa);
        $boDadosFaltantes = 'false';
        if(!empty($dadosUsuario->nascimento) && !empty($dadosUsuario->peso) && !empty($dadosUsuario->altura) && !empty($dadosUsuario->ds_genero)) {
            $dateNascimento = new \DateTime($dadosUsuario->nascimento);
            $dataAtual = new \DateTime();
            $idade = $dataAtual->diff($dateNascimento);
            if($dadosUsuario->ds_genero == 'MA') {
                $calculoCaloriasDiarias = (13.75 * floatval($dadosUsuario->peso)) + (5 * floatval($dadosUsuario->altura)) + (6.76 * floatval($idade->y)) + 66.5;
            } else if ($dadosUsuario->ds_genero == 'FE') {
                $calculoCaloriasDiarias = (9.56 * floatval($dadosUsuario->peso)) + (1.85 * floatval($dadosUsuario->altura)) + (4.68 * floatval($idade->y)) + 665;
            }
        } else {
            $boDadosFaltantes = 'true';
        }
        $this->view([
            'title' => 'Perfil - Meta',
            'dados' => $arDados,
            'dados_faltantes' => $boDadosFaltantes,
            'calorias_diarias_limpa' => $calculoCaloriasDiarias,
            'calorias_diarias' => intval($calculoCaloriasDiarias * 1.2),
            'historico' => $historicoDadosPessoaMeta
        ], 'portal.perfilmeta');
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
            $nuCaloriasDiarias = $_POST['nu_caloriasdiarias'] ?? null;
            
            if ($nuCaloriasDiarias) {
                $dbPessoaMeta = new PessoaMeta();
                $dbUser = new User();
                $usuarioLogado = $dbUser->getDadosUsuarioLogado();
                $dadosPessoaMeta = $dbPessoaMeta->getDadosPessoaMetaByIdPessoa($usuarioLogado->id_pessoa);
                if(isset($dadosPessoaMeta->id)) {
                    $dbPessoaMeta->alterar($nuCaloriasDiarias, $dadosPessoaMeta->id);
                } else {
                    $idPessoaMeta = $dbPessoaMeta->inserir($usuarioLogado->id_pessoa, $nuCaloriasDiarias);
                }
                
                header("Location: /perfilmeta");
                exit;
            } else {
                $this->view([
                    'title' => 'Perfil',
                    'error' => 'Todos os campos são obrigatórios'
                ], 'portal.perfilmeta');
            }
        }
    }

    public function destroy($id) {
        
    }

}