<?php

namespace app\models\portal;

use app\models\Model;

class Pessoa extends Model {

    protected $table = 'pessoa';

    public function inserirCadastroGoogle($nome, $email, $boEmail)
    {
        $sql = "INSERT INTO {$this->table} (nome, email, bo_email) VALUES ( :nome, :email , :bo_email )";
        $dados = $this->connection->prepare($sql);
        $dados->bindParam(':nome', $nome);
        $dados->bindParam(':email', $email);
        $dados->bindParam(':bo_email', $boEmail);

        $dados->execute();

        return $this->connection->lastInsertId();
    }

    public function inserirCadastro($nome, $email, $nascimento)
    {
        $sql = "INSERT INTO {$this->table} (nome, email, nascimento) VALUES ( :nome, :email, :nascimento )";
        $dados = $this->connection->prepare($sql);
        $dados->bindParam(':nome', $nome);
        $dados->bindParam(':email', $email);
        $dados->bindParam(':nascimento', $nascimento);

        $dados->execute();

        return $this->connection->lastInsertId();
    }
    
    public function alterar($nome, $email, $nascimento, $peso, $altura, $idTelegram, $dsGenero, $id)
    {
        $sql = "UPDATE {$this->table} set nome = :nome, email = :email, nascimento = :nascimento, peso = :peso, altura = :altura, id_telegram = :id_telegram, ds_genero = :ds_genero where id = :id";
        $dados = $this->connection->prepare($sql);
        $dados->bindParam(':nome', $nome);
        $dados->bindParam(':email', $email);
        $dados->bindParam(':nascimento', $nascimento);
        $dados->bindParam(':peso', $peso);
        $dados->bindParam(':altura', $altura);
        $dados->bindParam(':id_telegram', $idTelegram);
        $dados->bindParam(':ds_genero', $dsGenero);
        $dados->bindParam(':id', $id);

        $dados->execute();

        return $id;
    }

    public function alterarStatusValidacaoEmail($boEmail, $id)
    {
        $sql = "UPDATE {$this->table} set bo_email = :bo_email where id = :id";
        $dados = $this->connection->prepare($sql);
        $dados->bindParam(':bo_email', $boEmail);
        $dados->bindParam(':id', $id);

        $dados->execute();

        return $id;
    }

    public function vincularTelegram($idTelegram, $id)
    {
        $sql = "UPDATE {$this->table} set id_telegram = :id_telegram where id = :id";
        $dados = $this->connection->prepare($sql);
        $dados->bindParam(':id_telegram', $idTelegram);
        $dados->bindParam(':id', $id);

        $dados->execute();

        return $id;
    }

    public function getDadosPessoaByIdTelegram($idTelegram) {
        $sql = "SELECT p.* FROM {$this->table} as p join user as u on p.id = u.id_pessoa where id_telegram = :id_telegram and u.bo_ativo is true";
        $dados = $this->connection->prepare($sql);
        $dados->bindParam(':id_telegram', $idTelegram);
        $dados->execute();

        return $dados->fetch(\PDO::FETCH_OBJ);
    }

    public function getDadosPessoaByIdPessoa($idPessoa) {
        $sql = "SELECT * FROM {$this->table} where id = :id_pessoa";
        $dados = $this->connection->prepare($sql);
        $dados->bindParam(':id_pessoa', $idPessoa);
        $dados->execute();

        return $dados->fetch(\PDO::FETCH_OBJ);
    }

}