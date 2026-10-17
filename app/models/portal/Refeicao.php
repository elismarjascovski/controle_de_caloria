<?php

namespace app\models\portal;

use app\models\Model;

class Refeicao extends Model {

    protected $table = 'refeicao';

    public function inserir($idPessoa, $dtRefeicao) {
        $sql = "INSERT INTO {$this->table} (id_pessoa, dt_refeicao) VALUES ( :id_pessoa, :dt_refeicao )";
        $dados = $this->connection->prepare($sql);
        $dados->bindParam(':id_pessoa', $idPessoa);
        $dados->bindParam(':dt_refeicao', $dtRefeicao);

        $dados->execute();

        return $this->connection->lastInsertId();
    }

    public function alterar($idRefeicao, $dtRefeicao) {
        $sql = "UPDATE {$this->table} set dt_refeicao = :dt_refeicao where id = :id_refeicao";
        $dados = $this->connection->prepare($sql);
        $dados->bindParam(':id_refeicao', $idRefeicao);
        $dados->bindParam(':dt_refeicao', $dtRefeicao);

        $dados->execute();

        return $idRefeicao;
    }

    public function excluir($idRefeicao) {
        $sql = "DELETE from {$this->table} where id = :id_refeicao";
        $dados = $this->connection->prepare($sql);
        $dados->bindParam(':id_refeicao', $idRefeicao);

        $dados->execute();

        return true;
    }

    public function getDadosRefeicaoDiaAtualByIdPessoa($idPessoa, $dataAtual = null) {
        if(!$dataAtual) {
            $dataAtual = date('Y-m-d');
        }
        $sql = "select r.id, 
                    r.dt_refeicao,
                    ra.nu_consumo, 
                    ra.id as id_refeicao_alimento,
                    a.ds_alimento,
                    round(a.nu_energia, 0) as nu_energia,
                    a.nu_proteina,
                    a.nu_carboidrato,
                    a.nu_fibra,
                    a.quantidade,
                    rt.tp_refeicao,
                    ra.id_refeicao_tipo
                from {$this->table} as r 
                join refeicao_alimento as ra on r.id = ra.id_refeicao 
                join alimento as a on ra.id_alimento = a.id
                join refeicao_tipo as rt on ra.id_refeicao_tipo = rt.id
                where r.id_pessoa = :id_pessoa 
                    and r.dt_refeicao = :dt_refeicao
                order by rt.tp_refeicao, ra.ts_cadastro desc";
        $dados = $this->connection->prepare($sql);
        $dados->bindParam(':id_pessoa', $idPessoa);
        $dados->bindParam(':dt_refeicao', $dataAtual);
        $dados->execute();

        return $dados->fetchAll(\PDO::FETCH_OBJ);
    }

    public function gereRefeicoesByDtRefeicao($dtRefeicao) {
        $sql = "select * from {$this->table} where dt_refeicao = :dt_refeicao";
        $dados = $this->connection->prepare($sql);
        $dados->bindParam(':dt_refeicao', $dtRefeicao);
        $dados->execute();

        return $dados->fetchAll(\PDO::FETCH_OBJ);
    }
    

    public function getHistoricoRefeicaoByIdPessoa($idPessoa) {
        $sql = "select DATE_FORMAT(r.dt_refeicao, '%d/%m/%Y') AS dt_refeicao, 
                    round(sum((a.nu_energia / a.quantidade) * ra.nu_consumo), 0) as calorias 
                from {$this->table} as r 
                join refeicao_alimento ra on ra.id_refeicao = r.id 
                join alimento as a on a.id = ra.id_alimento
                where r.id_pessoa = :id_pessoa 
                group by r.dt_refeicao
                order by r.dt_refeicao desc";
        $dados = $this->connection->prepare($sql);
        $dados->bindParam(':id_pessoa', $idPessoa);
        $dados->execute();

        return $dados->fetchAll(\PDO::FETCH_OBJ);
    }

    public function getDadosRefeicaoByIdRefeicao($idRefeicao) {
        $sql = "select r.id, r.dt_refeicao, ra.id_alimento, ra.nu_consumo, ra.id_refeicao_tipo from {$this->table} as r join refeicao_alimento ra on ra.id_refeicao = r.id where r.id = :id_refeicao";
        $dados = $this->connection->prepare($sql);
        $dados->bindParam(':id_refeicao', $idRefeicao);
        $dados->execute();

        return $dados->fetch(\PDO::FETCH_OBJ);
    }

}