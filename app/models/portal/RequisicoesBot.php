<?php

namespace app\models\portal;

use app\models\Model;

class RequisicoesBot extends Model {

    protected $table = 'requisicoes_bot';

    public function inserir($idPessoa, $nuLinhaChat, $dsLinha) {
        $sql = "INSERT INTO {$this->table} (id_pessoa, nu_linha_chat, ds_requisicao) VALUES ( :id_pessoa, :nu_linha_chat, :ds_requisicao )";
        $dados = $this->connection->prepare($sql);
        $dados->bindParam(':id_pessoa', $idPessoa);
        $dados->bindParam(':nu_linha_chat', $nuLinhaChat);
        $dados->bindParam(':ds_requisicao', $dsLinha);

        $dados->execute();

        return $this->connection->lastInsertId();
    }

    public function atualizarRequerimentoAlimento($idPessoa, $dsRequisicao, $idAlimento) {
        $sql = "UPDATE {$this->table} set id_alimento = :id_alimento, ds_requisicao = :ds_requisicao where id_pessoa = :id_pessoa";
        $dados = $this->connection->prepare($sql);
        $dados->bindParam(':id_pessoa', $idPessoa);
        $dados->bindParam(':id_alimento', $idAlimento);
        $dados->bindParam(':ds_requisicao', $dsRequisicao);

        $dados->execute();

        return true;
    }

    public function atualizarRequerimentoRefeicaoTipo($idPessoa, $dsRequisicao, $idRefeicaoTipo) {
        $sql = "UPDATE {$this->table} set id_refeicao_tipo = :id_refeicao_tipo, ds_requisicao = :ds_requisicao where id_pessoa = :id_pessoa";
        $dados = $this->connection->prepare($sql);
        $dados->bindParam(':id_pessoa', $idPessoa);
        $dados->bindParam(':id_refeicao_tipo', $idRefeicaoTipo);
        $dados->bindParam(':ds_requisicao', $dsRequisicao);

        $dados->execute();

        return true;
    }

    public function excluir($idRequisicao) {
        $sql = "DELETE from {$this->table} where id = :id_requisicao ";
        $dados = $this->connection->prepare($sql);
        $dados->bindParam(':id_requisicao', $idRequisicao);

        $dados->execute();

        return true;
    }

    public function excluirRequisicaoByIdPessoa($idPessoa) {
        $sql = "DELETE from {$this->table} where id_pessoa = :id_pessoa ";
        $dados = $this->connection->prepare($sql);
        $dados->bindParam(':id_pessoa', $idPessoa);

        $dados->execute();

        return true;
    }

    public function getDadosUltimaRequisicaoByIdPessoa($idPessoa) {
        $sql = "SELECT * FROM {$this->table} where id_pessoa = :id_pessoa order by ts_cadastro desc limit 1";
        $dados = $this->connection->prepare($sql);
        $dados->bindParam(':id_pessoa', $idPessoa);
        $dados->execute();

        return $dados->fetch(\PDO::FETCH_OBJ);
    }

}