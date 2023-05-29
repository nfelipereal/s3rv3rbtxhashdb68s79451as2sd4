<?php
if (isset($_GET['chave'])) {
    $chaveLicenca = $_GET['chave'];

    // Conexão com o banco de dados
    $host = 'db4free.net';
    $user = 'ulqmaster';
    $password = '12541258';
    $database = 'ulqbotmaster23';

    $conexao = mysqli_connect($host, $user, $password, $database);
    if (!$conexao) {
        die("Falha na conexão com o banco de dados: " . mysqli_connect_error());
    }

    // Consulta para verificar a licença no banco de dados
    $consulta = "SELECT ativa, data_expiracao FROM licencas WHERE chave = '$chaveLicenca'";
    $resultado = mysqli_query($conexao, $consulta);
    if ($resultado) {
        if (mysqli_num_rows($resultado) > 0) {
            $row = mysqli_fetch_assoc($resultado);
            $licencaAtiva = $row['ativa'];
            $dataExpiracao = $row['data_expiracao'];

            // Verificar se a licença está ativa e não expirada
            if ($licencaAtiva && strtotime($dataExpiracao) > time()) {
                // Licença válida
                http_response_code(200);
                echo json_encode(['valida' => true, 'data_expiracao' => $dataExpiracao]);
            } else {
                // Licença inválida ou expirada
                http_response_code(401);
                echo json_encode(['valida' => false, 'erro' => 'Licença expirada entre em contato com o seu provedor e adquira uma nova licença']);
            }
        } else {
            // Licença não encontrada
            http_response_code(404);
            echo json_encode(['valida' => false, 'erro' => 'Licença não encontrada']);
        }
    } else {
        // Erro na consulta ao banco de dados
        http_response_code(500);
        echo json_encode(['valida' => false, 'erro' => 'Erro na consulta ao banco de dados']);
    }

    mysqli_close($conexao);
} else {
    // Retorna uma resposta de erro caso a chave não seja fornecida
    http_response_code(400);
    echo json_encode(['valida' => false, 'erro' => 'Chave de licença não fornecida']);
}
?>
