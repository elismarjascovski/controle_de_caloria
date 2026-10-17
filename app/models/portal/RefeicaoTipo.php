<?php

namespace app\models\portal;

use app\models\Model;

class RefeicaoTipo extends Model {

    protected $table = 'refeicao_tipo';

    public function inserir($tpRefeicao, $cor, $idPessoa) {
        $sql = "INSERT INTO {$this->table} (tp_refeicao, cor, id_pessoa) VALUES ( :tp_refeicao, :cor, :id_pessoa )";
        $dados = $this->connection->prepare($sql);
        $dados->bindParam(':tp_refeicao', $tpRefeicao);
        $dados->bindParam(':cor', $cor);
        $dados->bindParam(':id_pessoa', $idPessoa);

        $dados->execute();

        return $this->connection->lastInsertId();
    }

    public function alterar($idRefeicaoTipo, $tpRefeicao, $cor) {
        $sql = "UPDATE {$this->table} set tp_refeicao = :tp_refeicao, cor = :cor where id = :id_refeicaotipo";
        $dados = $this->connection->prepare($sql);
        $dados->bindParam(':tp_refeicao', $tpRefeicao);
        $dados->bindParam(':cor', $cor);
        $dados->bindParam(':id_refeicaotipo', $idRefeicaoTipo);

        $dados->execute();

        return true;
    }

    public function excluir($idTipoRefeicao) {
        $sql = "DELETE from {$this->table} where id = :id_refeicaotipo";
        $dados = $this->connection->prepare($sql);
        $dados->bindParam(':id_refeicaotipo', $idTipoRefeicao);

        $dados->execute();

        return true;
    }

    public function getTipoRefeicaoById($id) {
        $sql = "select * from {$this->table} where id = :id";
        $dados = $this->connection->prepare($sql);
        $dados->bindParam(':id', $id);
        $dados->execute();

        return $dados->fetch(\PDO::FETCH_OBJ);
    }

    public function getTipoRefeicao() {
        $sql = "select * from {$this->table} order by tp_refeicao asc";
        $dados = $this->connection->prepare($sql);
        $dados->execute();

        return $dados->fetchAll(\PDO::FETCH_OBJ);
    }

    public function getTipoRefeicaoByUsuarioLogado() {
        $dbUser = new User();
        $usuarioLogado = $dbUser->getDadosUsuarioLogado();

        $sql = "select * from {$this->table} where id_pessoa = :id_pessoa order by tp_refeicao asc";
        $dados = $this->connection->prepare($sql);
        $dados->bindParam(':id_pessoa', $usuarioLogado->id_pessoa);
        $dados->execute();

        return $dados->fetchAll(\PDO::FETCH_OBJ);
    }

    public function getTipoRefeicaoByIdPessoa($idPessoa) {
        $sql = "select * from {$this->table} where id_pessoa = :id_pessoa order by tp_refeicao asc";
        $dados = $this->connection->prepare($sql);
        $dados->bindParam(':id_pessoa', $idPessoa);
        $dados->execute();

        return $dados->fetchAll(\PDO::FETCH_OBJ);
    }

    public function getCorByIdTipoRefeicao($idTipoRefeicao) {
        $sql = "select cor from {$this->table} where id = :id";
        $dados = $this->connection->prepare($sql);
        $dados->bindParam(':id', $idTipoRefeicao);

        $dados->execute();

        return $dados->fetch(\PDO::FETCH_OBJ);
    }

    public function checkRefeicaoLancadaByIdRefeicaoTipo($id) {
        $sql = "select rt.* from {$this->table} as rt 
                join refeicao_alimento as ra on rt.id = ra.id_refeicao_tipo 
                where rt.id = :id";
        $dados = $this->connection->prepare($sql);
        $dados->bindParam(':id', $id);

        $dados->execute();

        return $dados->fetch(\PDO::FETCH_OBJ);
    }

}