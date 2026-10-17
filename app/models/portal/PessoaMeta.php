<?php

namespace app\models\portal;

use app\models\Model;

class PessoaMeta extends Model {

    protected $table = 'pessoa_meta';

    public function inserir($idPessoa, $nuCaloriasDiarias)
    {
        $sql = "INSERT INTO {$this->table} (id_pessoa, nu_caloriasdiarias) VALUES ( :id_pessoa, :nu_caloriasdiarias )";
        $dados = $this->connection->prepare($sql);
        $dados->bindParam(':id_pessoa', $idPessoa);
        $dados->bindParam(':nu_caloriasdiarias', $nuCaloriasDiarias);

        $dados->execute();

        return $this->connection->lastInsertId();
    }

    public function alterar($nuCaloriasDiarias, $id)
    {
        $sql = "UPDATE {$this->table} set nu_caloriasdiarias = :nu_caloriasdiarias where id = :id";
        $dados = $this->connection->prepare($sql);
        $dados->bindParam(':nu_caloriasdiarias', $nuCaloriasDiarias);
        $dados->bindParam(':id', $id);

        $dados->execute();

        return $id;
    }

    public function getDadosPessoaMetaByIdPessoa($idPessoa) {
        $sql = "SELECT * FROM {$this->table} where id_pessoa = :id_pessoa order by ts_criacao desc";
        $dados = $this->connection->prepare($sql);
        $dados->bindParam(':id_pessoa', $idPessoa);
        $dados->execute();

        return $dados->fetch(\PDO::FETCH_OBJ);
    }

    public function getHistoricoDadosPessoaMetaByIdPessoa($idPessoa) {
        $sql = "SELECT nu_caloriasdiarias, DATE_FORMAT(ts_criacao, '%d/%m/%Y') AS dt_criacao FROM {$this->table} where id_pessoa = :id_pessoa order by ts_criacao desc";
        $dados = $this->connection->prepare($sql);
        $dados->bindParam(':id_pessoa', $idPessoa);
        $dados->execute();

        return $dados->fetch(\PDO::FETCH_OBJ);
    }
}