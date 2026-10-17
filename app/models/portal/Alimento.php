<?php

namespace app\models\portal;

use app\models\Model;

class Alimento extends Model {

    protected $table = 'alimento';

    public function inserir($dsAlimento, $tpAlimento, $nuEnergia, $nuProteina, $nuCarboidrato, $nuFibra, $quantidade, $idPessoa) {
        $sql = "INSERT INTO {$this->table} (ds_alimento, tp_alimento, nu_energia, nu_proteina, nu_carboidrato, nu_fibra, quantidade, id_pessoa) VALUES ( :ds_alimento, :tp_alimento, :nu_energia, :nu_proteina, :nu_carboidrato, :nu_fibra, :quantidade, :id_pessoa )";
        $dados = $this->connection->prepare($sql);
        $dados->bindParam(':ds_alimento', $dsAlimento);
        $dados->bindParam(':tp_alimento', $tpAlimento);
        $dados->bindParam(':nu_energia', $nuEnergia);
        $dados->bindParam(':nu_proteina', $nuProteina);
        $dados->bindParam(':nu_carboidrato', $nuCarboidrato);
        $dados->bindParam(':nu_fibra', $nuFibra);
        $dados->bindParam(':quantidade', $quantidade);
        $dados->bindParam(':id_pessoa', $idPessoa);

        $dados->execute();

        return $this->connection->lastInsertId();
    }

    public function excluir($idAlimento) {
        $sql = "DELETE from {$this->table} where id = :id_alimento";
        $dados = $this->connection->prepare($sql);
        $dados->bindParam(':id_alimento', $idAlimento);

        $dados->execute();

        return true;
    }

    public function getListaAlimentosByIdPessoa($idPessoa) {
        $sql = "select * from {$this->table} where id_pessoa is null or id_pessoa = :id_pessoa";
        $dados = $this->connection->prepare($sql);
        $dados->bindParam(':id_pessoa', $idPessoa);

        $dados->execute();

        return $dados->fetchAll(\PDO::FETCH_OBJ);
    }

    public function getListaAlimentosCadastradosByIdPessoa($idPessoa) {
        $sql = "select * from {$this->table} where id_pessoa = :id_pessoa order by ds_alimento asc";
        $dados = $this->connection->prepare($sql);
        $dados->bindParam(':id_pessoa', $idPessoa);

        $dados->execute();

        return $dados->fetchAll(\PDO::FETCH_OBJ);
    }

    public function getDadosAlimentoById($id) {
        $sql = "select * from {$this->table} where id = :id_alimento";
        $dados = $this->connection->prepare($sql);
        $dados->bindParam(':id_alimento', $id);
        $dados->execute();

        return $dados->fetch(\PDO::FETCH_OBJ);
    }

    public function getListaTipoAlimento() {
        $sql = "select distinct tp_alimento from {$this->table} order by tp_alimento asc";
        $dados = $this->connection->prepare($sql);
        $dados->execute();

        return $dados->fetchAll(\PDO::FETCH_OBJ);
    }

    public function checkAlimentoUtilizadoById($idAlimento) {
        $sql = "select a.id from {$this->table} as a
            join refeicao_alimento as ra on a.id = ra.id_alimento
            where a.id = :id_alimento ";
        $dados = $this->connection->prepare($sql);
        $dados->bindParam(':id_alimento', $idAlimento);
        $dados->execute();
        $alimento = $dados->fetch(\PDO::FETCH_OBJ);
        return $alimento ? true : false;
    }

    public function getDadosAlimentoByNomeAndIdPessoa($nome, $idPessoa) {
        $sql = "SELECT id, ds_alimento FROM {$this->table} WHERE ds_alimento LIKE :nome and ( id_pessoa is null or id_pessoa = :id_pessoa )";
        $dados = $this->connection->prepare($sql);
                $nome = '%' . $nome . '%';
        $dados->bindParam(':nome', $nome, \PDO::PARAM_STR);
        $dados->bindParam(':id_pessoa', $idPessoa);
        $dados->execute();

        return $dados->fetchAll(\PDO::FETCH_OBJ); 
    }

}