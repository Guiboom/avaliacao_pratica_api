<?php
require_once __DIR__ . "/../conexao.php";

class Computador
{

    public function listarComputadores()
    {

        try {
            $conexao = obterConexao();

            $sql = "SELECT * FROM computador";

            $stmt = $conexao->query($sql);

            $dados = [];

            while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {

                if ($row['pessoa_id'] !== null) {

                    $sqlPessoa = "SELECT * FROM pessoa WHERE id = :id";

                    $stmtPessoa = $conexao->prepare($sqlPessoa);

                    $stmtPessoa->execute([
                        "id" => $row['pessoa_id']
                    ]);

                    $row['pessoa'] = $stmtPessoa->fetch(PDO::FETCH_ASSOC);
                } else {

                    $row['pessoa'] = null;
                }

                $dados[] = $row;
            }
            return $dados;
        } catch (PDOException $e) {
            throw $e;
        }
    }


    public function listarComputador($id)
    {

        try {

            $conexao = obterConexao();

            $sql = "SELECT * FROM computador WHERE id = :id";

            $stmt = $conexao->prepare($sql);

            $stmt->execute([
                "id" => $id
            ]);

            $computador = $stmt->fetch(PDO::FETCH_ASSOC);

            if (!$computador) {
                return null;
            }

            if ($computador['pessoa_id'] !== null) {

                $sqlPessoa = "SELECT * FROM pessoa WHERE id = :id";
                $stmtPessoa = $conexao->prepare($sqlPessoa);
                $stmtPessoa->execute([
                    "id" => $computador['pessoa_id']
                ]);

                $computador['pessoa'] = $stmtPessoa->fetch(PDO::FETCH_ASSOC);
            } else {

                $computador['pessoa'] = null;
            }

            return $computador;
        } catch (PDOException $e) {

            throw $e;
        }
    }


    public function cadastrarComputador($nome, $numero, $status, $pessoa_id)
    {

        try {

            $conexao = obterConexao();

            $sql = "INSERT INTO computador
                    (nome, numero, status, pessoa_id)
                    VALUES
                    (:nome, :numero, :status, :pessoa_id)";

            $stmt = $conexao->prepare($sql);

            return $stmt->execute([
                "nome" => $nome,
                "numero" => $numero,
                "status" => $status,
                "pessoa_id" => $pessoa_id
            ]);
        } catch (PDOException $e) {
            throw $e;
        }
    }


    public function deletarComputador($id)
    {

        try {

            $conexao = obterConexao();

            $sql = "DELETE FROM computador WHERE id = :id";

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

            $sql = "UPDATE computador
                    SET nome = :nome,
                        numero = :numero,
                        status = :status,
                        pessoa_id = :pessoa_id
                    WHERE id = :id";

            $stmt = $conexao->prepare($sql);

            return $stmt->execute([
                "nome" => $dados['nome'],
                "numero" => $dados['numero'],
                "status" => $dados['status'],
                "pessoa_id" => $dados['pessoa_id'],
                "id" => $dados['id']
            ]);
        } catch (PDOException $e) {
            throw $e;
        }
    }
}
