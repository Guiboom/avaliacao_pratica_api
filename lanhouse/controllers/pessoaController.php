<?php
header("Content-Type: application/json; charset=UTF-8");
header("Access-Control-Allow-Origin: *");

require_once __DIR__ . "/../Models/Pessoa.php";

$request = $_SERVER['REQUEST_METHOD'];
$pessoa = new Pessoa();

try {

    if ($request === 'GET') {

        if (isset($_GET['idPessoa'])) {

            $id = $_GET['idPessoa'];

            if (!is_numeric($id)) {
                http_response_code(400);
                echo json_encode([
                    "mensagem" => "ID inválido"
                ]);
                exit;
            }

            $buscaPessoa = $pessoa->listarPessoa($id);

            if ($buscaPessoa) {

                http_response_code(200);
                echo json_encode($buscaPessoa);
            } else {

                http_response_code(404);
                echo json_encode([
                    "mensagem" => "Pessoa não encontrada"
                ]);
            }
            exit;
        }

        $lista = $pessoa->listarPessoas();

        http_response_code(200);
        echo json_encode($lista);

        exit;
    } else if ($request === 'POST') {

        $dadosRecebidos = json_decode(
            file_get_contents("php://input"),
            true
        );

        if ($dadosRecebidos === null) {

            http_response_code(400);
            echo json_encode([
                "mensagem" => "JSON inválido"
            ]);
            exit;
        }

        if (
            !isset($dadosRecebidos['nome']) ||
            !isset($dadosRecebidos['telefone']) ||
            !isset($dadosRecebidos['email'])
        ) {

            http_response_code(400);
            echo json_encode([
                "mensagem" => "Dados inválidos"
            ]);
            exit;
        }

        $nome = $dadosRecebidos['nome'];
        $telefone = $dadosRecebidos['telefone'];
        $email = $dadosRecebidos['email'];

        if ($nome === '') {

            http_response_code(400);
            echo json_encode([
                "mensagem" => "Nome inválido"
            ]);
            exit;
        }

        $inserir = $pessoa->cadastrarPessoa(
            $nome,
            $telefone,
            $email
        );

        if ($inserir) {

            http_response_code(201);
            echo json_encode([
                "mensagem" => "Pessoa cadastrada"
            ]);
        } else {

            http_response_code(400);
            echo json_encode([
                "mensagem" => "Não foi possível cadastrar a pessoa"
            ]);
        }
        exit;
    } else if ($request === "DELETE") {

        if (!isset($_GET['idPessoa'])) {

            http_response_code(404);
            echo json_encode([
                "mensagem" => "ID não informado"
            ]);
            exit;
        }

        $id = $_GET['idPessoa'];

        if (!is_numeric($id)) {

            http_response_code(400);
            echo json_encode([
                "mensagem" => "ID inválido"
            ]);
            exit;
        }

        $pessoaExiste = $pessoa->listarPessoa($id);

        if (!$pessoaExiste) {

            http_response_code(404);
            echo json_encode([
                "mensagem" => "Pessoa não encontrada"
            ]);
            exit;
        }

        $deletar = $pessoa->deletarPessoa($id);

        if ($deletar) {

            http_response_code(200);
            echo json_encode([
                "mensagem" => "Pessoa Deletada"
            ]);
        }

        exit;
    } else if ($request === "PUT") {

        if (!isset($_GET['idPessoa'])) {

            http_response_code(404);
            echo json_encode([
                "mensagem" => "Pessoa não encontrada"
            ]);
            exit;
        }

        $id = $_GET['idPessoa'];

        if (!is_numeric($id)) {

            http_response_code(400);
            echo json_encode([
                "mensagem" => "ID inválido"
            ]);
            exit;
        }

        $dadosRecebidos = json_decode(
            file_get_contents("php://input"),
            true
        );

        if ($dadosRecebidos === null) {

            http_response_code(400);
            echo json_encode([
                "mensagem" => "JSON inválido"
            ]);
            exit;
        }

        if (
            !isset($dadosRecebidos['nome']) ||
            !isset($dadosRecebidos['telefone']) ||
            !isset($dadosRecebidos['email'])
        ) {

            http_response_code(400);
            echo json_encode([
                "mensagem" => "Dados inválidos"
            ]);
            exit;
        }

        $pessoaExiste = $pessoa->listarPessoa($id);

        if (!$pessoaExiste) {

            http_response_code(404);
            echo json_encode([
                "mensagem" => "Pessoa não encontrada"
            ]);
            exit;
        }

        $dadosRecebidos['id'] = $id;

        $alterar = $pessoa->alterar($dadosRecebidos);

        if ($alterar) {

            http_response_code(200);
            echo json_encode([
                "mensagem" => "Pessoa Alterada"
            ]);
        } else {

            http_response_code(400);
            echo json_encode([
                "mensagem" => "Dados inválidos"
            ]);
        }

        exit;
    } else {

        http_response_code(400);
        echo json_encode([
            "mensagem" => "Método HTTP inválido"
        ]);
    }
} catch (PDOException $e) {

    if ($e->errorInfo[1] == 1451) {

        http_response_code(409);

        echo json_encode([
            "mensagem" => "Não é possível deletar a pessoa pois existem computadores associados"
        ]);
    } else {

        http_response_code(500);

        echo json_encode([
            "mensagem" => "Erro no banco de dados"
        ]);
    }
}
