<?php

namespace app\models\portal;

use app\models\Model;

class RefeicaoAlimento extends Model {

    protected $table = 'refeicao_alimento';

    public function inserir($idRefeicao, $idAlimento, $nuConsumo, $idRefeicaoTipo) {
        $sql = "INSERT INTO {$this->table} (id_refeicao, id_alimento, nu_consumo, id_refeicao_tipo) VALUES ( :id_refeicao, :id_alimento, :nu_consumo, :id_refeicao_tipo )";
        $dados = $this->connection->prepare($sql);
        $dados->bindParam(':id_refeicao', $idRefeicao);
        $dados->bindParam(':id_alimento', $idAlimento);
        $dados->bindParam(':nu_consumo', $nuConsumo);
        $dados->bindParam(':id_refeicao_tipo', $idRefeicaoTipo);

        $dados->execute();

        return $this->connection->lastInsertId();
    }

    public function alterar($idRefeicao, $idAlimento, $nuConsumo, $idRefeicaoTipo) {
        $sql = "UPDATE {$this->table} set id_alimento = :id_alimento, nu_consumo = :nu_consumo, id_refeicao_tipo = :id_refeicao_tipo where id_refeicao = :id_refeicao";
        $dados = $this->connection->prepare($sql);
        $dados->bindParam(':id_refeicao', $idRefeicao);
        $dados->bindParam(':id_alimento', $idAlimento);
        $dados->bindParam(':nu_consumo', $nuConsumo);
        $dados->bindParam(':id_refeicao_tipo', $idRefeicaoTipo);

        $dados->execute();

        return $this->connection->lastInsertId();
    }

    public function excluir($idRefeicao) {
        $sql = "DELETE from {$this->table} where id_refeicao = :id_refeicao";
        $dados = $this->connection->prepare($sql);
        $dados->bindParam(':id_refeicao', $idRefeicao);

        $dados->execute();

        return true;
    }

}