<?php

namespace app\models\portal;

use app\models\Model;

class User extends Model {

    protected $table = 'user';

    public function inserirUsuarioGoogle($idPessoa, $credential) {
        $sql = "INSERT INTO {$this->table} (id_pessoa, credential) VALUES ( :id_pessoa, :credential )";
        $dados = $this->connection->prepare($sql);
        $dados->bindParam(':id_pessoa', $idPessoa);
        $dados->bindParam(':credential', $credential);

        $dados->execute();

        return $this->connection->lastInsertId();
    }

    public function inserirUsuario($idPessoa, $senha) {
        $sql = "INSERT INTO {$this->table} (id_pessoa, password) VALUES ( :id_pessoa, :senha )";
        $dados = $this->connection->prepare($sql);
        $dados->bindParam(':id_pessoa', $idPessoa);
        $hash = password_hash($senha, CRYPT_SHA256);
        $dados->bindParam(':senha', $hash);

        $dados->execute();
        return $this->connection->lastInsertId();
    }
    
    public function getDadosUsuariById($id) {
        $sql = "select * from {$this->table} as u join pessoa as p on u.id_pessoa = p.id where u.id = :id";
        $dados = $this->connection->prepare($sql);
        $dados->bindParam(':id', $id);
        $dados->execute();

        return $dados->fetch(\PDO::FETCH_OBJ);
    }

    public function getDadosUsuarioLogado() {
        $idUsuario = $_SESSION['id_usuario'];
        $sql = "select * from {$this->table} as u join pessoa as p on u.id_pessoa = p.id where p.id = :id";
        $dados = $this->connection->prepare($sql);
        $dados->bindParam(':id', $idUsuario);
        $dados->execute();

        return $dados->fetch(\PDO::FETCH_OBJ);
    }

    public function validarLogin($email, $senha)
    {
        $stmt = $this->connection->prepare('SELECT u.*, p.nome, p.email FROM user as u
                                JOIN pessoa as p on u.id_pessoa = p.id
                                WHERE p.email = :email and u.bo_ativo is true');
        
        $stmt->bindParam(':email', $email);
        $stmt->execute();
        $usuario = $stmt->fetch(\PDO::FETCH_OBJ);
        $boUsuarioLogado = false;
        if ($usuario && password_verify($senha, $usuario->password)) {
            $_SESSION['id_usuario'] = $usuario->id;
            $_SESSION['usuario_email'] = $usuario->email;
            $_SESSION['no_usuario'] = $usuario->nome;
            $boUsuarioLogado = true;
        }
        return $boUsuarioLogado;
    }

    public function checkEmailGoogle($email)
    {
        $stmt = $this->connection->prepare('SELECT u.*, p.nome, p.email FROM user as u
                                JOIN pessoa as p on u.id_pessoa = p.id
                                WHERE p.email = :email');
        
        $stmt->bindParam(':email', $email);
        $stmt->execute();
        $usuario = $stmt->fetch(\PDO::FETCH_OBJ);
        return $usuario ? true : false;
    }

    public function validarLoginGoogle($email, $credential)
    {
        $stmt = $this->connection->prepare('SELECT u.*, p.nome, p.email FROM user as u
                                JOIN pessoa as p on u.id_pessoa = p.id
                                WHERE p.email = :email and u.bo_ativo is true');
        
        $stmt->bindParam(':email', $email);
        $stmt->execute();
        $usuario = $stmt->fetch(\PDO::FETCH_OBJ);
        $boUsuarioLogado = false;
        if ($usuario && $credential == $usuario->credential) {
            $_SESSION['id_usuario'] = $usuario->id;
            $_SESSION['usuario_email'] = $usuario->email;
            $_SESSION['no_usuario'] = $usuario->nome;
            $boUsuarioLogado = true;
        }
        return $boUsuarioLogado;
    }

    public function alterarStatusValidacaoUsuario($boAtivo, $idPessoa)
    {
        $sql = "UPDATE {$this->table} set bo_ativo = :bo_ativo where id_pessoa = :id_pessoa";
        $dados = $this->connection->prepare($sql);
        $dados->bindParam(':bo_ativo', $boAtivo);
        $dados->bindParam(':id_pessoa', $idPessoa);

        $dados->execute();

        return $idPessoa;
    }

}