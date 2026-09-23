<?php

require_once __DIR__ . "/../conexao.php";

class Pessoa
{

    public function listarPessoas()
    {

        try {

            $conexao = obterConexao();

            $sql = "SELECT * FROM pessoa";

            $stmt = $conexao->query($sql);

            return $stmt->fetchAll(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {

            throw $e;
        }
    }


    public function listarPessoa($id)
    {

        try {

            $conexao = obterConexao();

            if (!isset($id)) {
                return null;
            }

            $sql = "SELECT * FROM pessoa WHERE id = :id";

            $stmt = $conexao->prepare($sql);

            $stmt->execute([
                "id" => $id
            ]);

            return $stmt->fetch(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {

            throw $e;
        }
    }


    public function cadastrarPessoa($nome, $telefone, $email)
    {

        try {

            $conexao = obterConexao();

            $sql = "INSERT INTO pessoa
                    (nome, telefone, email)
                    VALUES
                    (:nome, :telefone, :email)";

            $stmt = $conexao->prepare($sql);

            return $stmt->execute([
                "nome" => $nome,
                "telefone" => $telefone,
                "email" => $email
            ]);
        } catch (PDOException $e) {

            throw $e;
        }
    }


    public function deletarPessoa($id)
    {

        try {

            $conexao = obterConexao();

            $sql = "DELETE FROM pessoa WHERE id = :id";

            $stmt = $conexao->prepare($sql);

            return $stmt->execute([
                "id" => $id
            ]);
        } catch (PDOException $e) {

            throw $e;
        }
    }


    public function alterar($dados)
    {

        try {

            $conexao = obterConexao();

            $sql = "UPDATE pessoa
                    SET nome = :nome,
                        telefone = :telefone,
                        email = :email
                    WHERE id = :id";

            $stmt = $conexao->prepare($sql);

            return $stmt->execute([
                "nome" => $dados['nome'],
                "telefone" => $dados['telefone'],
                "email" => $dados['email'],
                "id" => $dados['id']
            ]);
        } catch (PDOException $e) {

            throw $e;
        }
    }
}
