<?php

header("Content-Type: application/json; charset=UTF-8");
header("Access-Control-Allow-Origin: *");
require_once __DIR__ . "/../Models/Computador.php";
require_once __DIR__ . "/../Models/Pessoa.php";

$request = $_SERVER['REQUEST_METHOD'];

$computador = new Computador();
$Pessoa = new Pessoa();

try {
    if ($request === 'GET') {

        if (isset($_GET['idComputador'])) {
            $id = $_GET['idComputador'];

            if (!is_numeric($id)) {
                http_response_code(400);
                echo json_encode([
                    "mensagem" => "ID inválido"
                ]);
                exit;
            }

            $buscaComputador = $computador->listarComputador($id);

            if ($buscaComputador) {
                http_response_code(200);
                echo json_encode($buscaComputador);
            } else {
                http_response_code(404);
                echo json_encode([
                    "mensagem" => "Computador não encontrado"
                ]);
            }
        } else {
            $lista = $computador->listarComputadores();
            http_response_code(200);
            echo json_encode($lista);
        }
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
            !isset($dadosRecebidos['numero']) ||
            !isset($dadosRecebidos['status']) ||
            !array_key_exists('pessoa_id', $dadosRecebidos)
        ) {
            http_response_code(400);
            echo json_encode([
                "mensagem" => "Dados inválidos"
            ]);
            exit;
        }

        $nome = $dadosRecebidos['nome'];
        $numero = $dadosRecebidos['numero'];
        $status = $dadosRecebidos['status'];
        $pessoa_id = $dadosRecebidos['pessoa_id'];

        if ($nome === '' || !is_numeric($numero)) {

            http_response_code(400);
            echo json_encode([
                "mensagem" => "Dados inválidos"
            ]);

            exit;
        }

        if ($pessoa_id !== null) {

            if (!is_numeric($pessoa_id)) {

                http_response_code(400);
                echo json_encode([
                    "mensagem" => "Pessoa inválida"
                ]);
                exit;
            }

            $pessoa = $Pessoa->listarPessoa($pessoa_id);

            if (!$pessoa) {

                http_response_code(400);
                echo json_encode([
                    "mensagem" => "Pessoa não encontrada"
                ]);
                exit;
            }
        }

        $inserir = $computador->cadastrarComputador(
            $nome,
            $numero,
            $status,
            $pessoa_id
        );

        if ($inserir) {

            http_response_code(201);
            echo json_encode([
                "mensagem" => "Computador cadastrado"
            ]);
        } else {

            http_response_code(400);
            echo json_encode([
                "mensagem" => "Não foi possível cadastrar o computador"
            ]);
        }
        exit;
    } else if ($request === "DELETE") {
        if (!isset($_GET['idComputador'])) {

            http_response_code(404);
            echo json_encode([
                "mensagem" => "ID não informado"
            ]);
            exit;
        }

        $id = $_GET['idComputador'];

        if (!is_numeric($id)) {

            http_response_code(400);
            echo json_encode([
                "mensagem" => "ID inválido"
            ]);
            exit;
        }

        $computadorExiste = $computador->listarComputador($id);

        if (!$computadorExiste) {

            http_response_code(404);
            echo json_encode([
                "mensagem" => "Computador não encontrado"
            ]);
            exit;
        }

        $deletar = $computador->deletarComputador($id);

        if ($deletar) {

            http_response_code(200);
            echo json_encode([
                "mensagem" => "Computador Deletado"
            ]);
        }
        exit;
    } else if ($request === "PUT") {

        if (!isset($_GET['idComputador'])) {

            http_response_code(404);
            echo json_encode([
                "mensagem" => "Computador não encontrado"
            ]);
            exit;
        }

        $id = $_GET['idComputador'];

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
            !isset($dadosRecebidos['numero']) ||
            !isset($dadosRecebidos['status']) ||
            !array_key_exists('pessoa_id', $dadosRecebidos)
        ) {

            http_response_code(400);
            echo json_encode([
                "mensagem" => "Dados inválidos"
            ]);
            exit;
        }

        $computadorExiste = $computador->listarComputador($id);

        if (!$computadorExiste) {

            http_response_code(404);
            echo json_encode([
                "mensagem" => "Computador não encontrado"
            ]);
            exit;
        }

        $pessoa_id = $dadosRecebidos['pessoa_id'];

        if ($pessoa_id !== null) {

            if (!is_numeric($pessoa_id)) {

                http_response_code(400);
                echo json_encode([
                    "mensagem" => "Pessoa inválida"
                ]);
                exit;
            }

            $pessoaExiste = $Pessoa->listarPessoa($pessoa_id);

            if (!$pessoaExiste) {

                http_response_code(400);
                echo json_encode([
                    "mensagem" => "Pessoa não encontrada"
                ]);
                exit;
            }
        }

        $dadosRecebidos['id'] = $id;

        $alterar = $computador->alterar($dadosRecebidos);

        if ($alterar) {

            http_response_code(200);
            echo json_encode([
                "mensagem" => "Computador Alterado"
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

    http_response_code(500);

    echo json_encode([
        "mensagem" => "Erro no banco de dados"
    ]);
}
