<?php
// Iniciar a sessão
session_start();

// Definir as constantes de conexão ao banco de dados
define('DB_SERVER', '185.173.111.184');
define('DB_USERNAME', 'u858577505_trabalhoamigo');
define('DB_PASSWORD', '@#Trabalhoamigo023@_');
define('DB_NAME', 'u858577505_trabalhoamigo');

// Função para criar a conexão com o banco de dados
function getDatabaseConnection() {
    $conexao = new mysqli(DB_SERVER, DB_USERNAME, DB_PASSWORD, DB_NAME);
    
    if ($conexao->connect_error) {
        throw new Exception('Falha na conexão com o banco de dados: ' . $conexao->connect_error);
    }
    
    return $conexao;
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    if (!isset($_SESSION['id_usuario'])) {
        echo "Usuário não está autenticado.";
        exit;
    }

    $valor = (float) $_POST['valor'];
    $descricao = $_POST['descricao'];
    $tempo = (int) $_POST['tempo'];
    $id_servico_fk = (int) $_POST['id_servico'];
    $id_usuario_contrante_fk = (int) $_SESSION['id_usuario'];
    $id_usuario_prestador_fk = 1;
    $data_contrato = date('Y-m-d H:i:s');

    $prazo_estimado = date('Y-m-d H:i:s', strtotime("+$tempo days"));

    try {
        $conexao = getDatabaseConnection();

        // Preparar a instrução SQL
        $stmt = $conexao->prepare("INSERT INTO proposta (id_servico_fk, id_usuario_contrante_fk, id_usuario_prestador_fk, data_contrato, prazo_estimado, valor_total) VALUES (?, ?, ?, ?, ?, ?)");

        // Verifica se a preparação da declaração falhou
        if (!$stmt) {
            throw new Exception('Erro ao preparar a declaração: ' . $conexao->error);
        }

        // Vincula os parâmetros
        $stmt->bind_param("iiissd", $id_servico_fk, $id_usuario_contrante_fk, $id_usuario_prestador_fk, $data_contrato, $prazo_estimado, $valor);

        // Executa a declaração
        if ($stmt->execute()) {
            echo "Proposta enviada com sucesso!";
        } else {
            echo "Erro ao enviar proposta: " . $stmt->error;
        }

        // Fecha a declaração e a conexão
        $stmt->close();
        $conexao->close();
    } catch (Exception $e) {
        echo "Erro: " . $e->getMessage();
    }
} else {
    echo "Método de requisição não suportado.";
}
