<?php
session_start();

if (!defined('DB_SERVER')) {
    define('DB_SERVER', '185.173.111.184');
}
if (!defined('DB_USERNAME')) {
    define('DB_USERNAME', 'u858577505_trabalhoamigo');
}
if (!defined('DB_PASSWORD')) {
    define('DB_PASSWORD', '@#Trabalhoamigo023@_');
}
if (!defined('DB_NAME')) {
    define('DB_NAME', 'u858577505_trabalhoamigo');
}

$conn = new mysqli(DB_SERVER, DB_USERNAME, DB_PASSWORD, DB_NAME);

// Verificação da conexão
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Lógica para aceitar ou recusar serviço
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $action = $_POST['action'];
    $idServico = $_POST['idServico'];

    if ($action === 'accept') {
        $idContrato = $_POST['idContrato']; // Novo parâmetro
        $qtdServico = $_POST['qtdServico']; // Novo parâmetro
        $valorFinal = $_POST['valorFinal']; // Novo parâmetro
        acceptService($idServico, $idContrato, $qtdServico, $valorFinal);
    } elseif ($action === 'reject') {
        rejectService($idServico);
    }
    exit; // Encerrar após processar a requisição AJAX
}

function acceptService($idServico, $idContrato, $qtdServico, $valorFinal) {
    global $conn;

    // Atualiza o status da proposta para '2' (aceito)
    $sql = "UPDATE proposta SET status = '2' WHERE id_contrato = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("i", $idContrato);  // Corrigido para usar $idContrato

    if ($stmt->execute()) {
        $stmt->close(); // Fecha o statement do UPDATE

        // Agora faz a inserção na tabela contratos
        $sqlInsert = "INSERT INTO contratos (id_servico_fk, id_contrato_fk, qtd_servico, valor_final) VALUES (?, ?, ?, ?)";
        $stmtInsert = $conn->prepare($sqlInsert);
        $stmtInsert->bind_param("iiid", $idServico, $idContrato, $qtdServico, $valorFinal); // Ajustado para "d" (double) em valor_final

        if ($stmtInsert->execute()) {
            echo json_encode(['message' => 'Serviço aceito e contrato cadastrado com sucesso.']);
        } else {
            echo json_encode(['error' => 'Erro ao cadastrar contrato: ' . $stmtInsert->error]);
        }

        $stmtInsert->close(); // Fecha o statement da inserção
    } else {
        echo json_encode(['error' => 'Erro ao aceitar serviço: ' . $stmt->error]);
    }
}

function rejectService($idServico) {
    global $conn;

    $sql = "UPDATE proposta SET status = '4' WHERE id_contrato = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("i", $idServico);

    if ($stmt->execute()) {
        echo json_encode(['message' => 'Serviço recusado com sucesso.']);
    } else {
        echo json_encode(['message' => 'Erro ao recusar serviço: ' . $stmt->error]);
    }

    $stmt->close();
}

// Buscar propostas
$id_usuario = $_SESSION['id_usuario'];
$sql = "SELECT p.id_contrato, DATE(p.data_contrato) AS data_envio, s.titulo AS titulo_servico, 
               p.valor_total, u.primeiro_nome, u.telefone, u.celular, u.whatsapp, u.email, u.unique_id,
               p.prazo_estimado, p.data_esperada, p.status
        FROM proposta p 
        JOIN servicos s ON p.id_servico_fk = s.id_servico 
        JOIN usuarios u ON p.id_usuario_contrante_fk = u.id_usuario 
        WHERE p.id_usuario_prestador_fk = ? AND p.status != 4 AND u.ativo = 1";

$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $id_usuario);
$stmt->execute();
$result = $stmt->get_result();
$propostas = $result->fetch_all(MYSQLI_ASSOC);
$stmt->close();
$conn->close();

?>

<!DOCTYPE html>
<html lang="pt-br">
<head>
    <title>Histórico de Propostas | Trabalho Amigo</title>
    <meta name="description" content="Confira o histórico de propostas enviadas e recebidas, e gerencie suas oportunidades de trabalho na plataforma Trabalho Amigo." />
    <meta charset='utf-8'>
    <meta http-equiv='X-UA-Compatible' content='IE=edge'>
    <meta name='viewport' content='width=device-width, initial-scale=1'>
    <link rel="icon" href="../../favicon.ico" type="image/x-icon">
    <script src="../../../../app.js" defer></script>
    <link rel="stylesheet" href="../../../../app.css">
    <link rel="stylesheet" href="../../../../public/css/contrante/HistoricoProposta.css">
    <script src="https://code.jquery.com/jquery-3.7.1.min.js" integrity="sha256-/JqT3SQfawRcv/BIHPThkBvs0OEvtFFmqPF/lYI/Cxo=" crossorigin="anonymous"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
</head>

<body>
    <script>
        $.ajax({
            url: `../../../controllers/anunciante/Security.php`,
            method: 'GET',
            success: function (data) {
                if (data == 'true') {
                } else if (data == 'false') {
                    window.location.href = "../CriarConta/";
                }
            },
            error: function () {
                Swal.fire({
                    icon: 'error',
                    title: 'Erro',
                    text: 'Erro na Autenticação.'
                });
            }
        });
    </script>

    <?php include '../layouts/Header.php'; ?>

    <section id="ListagemServico">
        <h3><a href="../PaginaInicial/">←</a> Histórico de propostas</h3>
        <div class="grid-container">
            <div class="grid-header">Data de envio</div>
            <div class="grid-header">Título do Serviço</div>
            <div class="grid-header">Valor</div>
            <div class="grid-header">Ações</div>

            <?php foreach ($propostas as $proposta): ?>
                <div class="grid-item"><?= $proposta['data_envio'] ?></div>
                <div class="grid-item"><?= $proposta['titulo_servico'] ?></div>
                <div class="grid-item">R$ <?= number_format($proposta['valor_total'], 2, ',', '.') ?></div>
                <div class="grid-item">
                <?php if ($proposta['status'] == 1): ?>
                    <button class="button button-vermais" onclick="showServiceDetails(
                        <?= $proposta['id_contrato'] ?>,
                        '<?= addslashes($proposta['titulo_servico']) ?>',
                        <?= $proposta['valor_total'] ?>,
                        '<?= addslashes($proposta['primeiro_nome']) ?>',
                        '<?= addslashes($proposta['telefone']) ?>',
                        '<?= addslashes($proposta['celular']) ?>',
                        '<?= addslashes($proposta['whatsapp']) ?>',
                        '<?= addslashes($proposta['email']) ?>',
                        '<?= addslashes($proposta['prazo_estimado']) ?>',
                        '<?= addslashes($proposta['data_esperada']) ?>'
                    )">Visualizar <i class="bi bi-gear-fill"></i></button>
                <?php elseif ($proposta['status'] == 2): ?>
                    <button class="button" onclick="showContractorInfo(
                        '<?= addslashes($proposta['primeiro_nome']) ?>',
                        '<?= addslashes($proposta['telefone']) ?>',
                        '<?= addslashes($proposta['celular']) ?>',
                        '<?= addslashes($proposta['whatsapp']) ?>',
                        '<?= addslashes($proposta['unique_id']) ?>',
                        '<?= addslashes($proposta['email']) ?>',
                        '<?= addslashes($proposta['id_contrato']) ?>'
                    )">Entrar em contato <i class="bi bi-arrow-right"></i></button>
                <?php elseif ($proposta['status'] == 3): ?>
                    <button class="button button-finalizado" disabled>Proposta finalizada</button>
                <?php endif; ?>
                </div>
            <?php endforeach; ?>
        </div>
    </section>

    <?php include '../layouts/Footer.php'; ?>

    <div id="toast" class="toast" style="display: none;">
        <div class="toast-body">
            Copiado para a área de transferência!
        </div>
    </div>

    <div class="background-loading hidden">
        <div class="loading-icon"></div>
    </div>

    <script>

    function formatDate(dataString) {
        const [year, month, day] = dataString.split('-');
        return `${day}/${month}/${year}`;
    }

    function showServiceDetails(idServico, tituloServico, valorTotal, primeiroNome, telefone, celular, whatsapp, email, prazo_estimado, data_esperada) {
        Swal.fire({
                title: 'Detalhes da proposta',
                html: `
                    <div style="text-align: left;">
                        <p><strong>Serviço:</strong> ${tituloServico}</p><br>
                        <p><strong>Valor proposto:</strong> R$ ${valorTotal.toFixed(2).replace('.', ',')}</p><br>
                        <p><strong>Nome do contratante:</strong> ${primeiroNome}</p><br>
                        <p><strong>Tempo estimado:</strong> ${prazo_estimado} Dias</p><br>
                        <p><strong>Data estimada:</strong> ${data_esperada}</p><br>
                    </div>
                `,
                showCloseButton: true,
                showCancelButton: true,
                confirmButtonText: `Aceitar`,
                cancelButtonText: `Recusar`,
                focusConfirm: false,
                width: '700px',
                padding: '1.5rem',
                customClass: {
                    popup: 'swal-custom-popup',
                    title: 'swal-custom-title',
                    htmlContainer: 'swal-custom-html'
                },
                allowOutsideClick: false
        }).then((result) => {
            if (result.isConfirmed) {
                acceptService(idServico, tituloServico, valorTotal);
            } else if (result.isDismissed) {
                rejectService(idServico);
            }
        });
        
    }

    function acceptService(idServico, tituloServico, valorTotal) {
        const idContrato = idServico; // Supondo que idServico é igual a idContrato
        const qtdServico = 1; // Ajuste conforme necessário
        const valorFinal = valorTotal.toFixed(2).replace('.', ','); // Ajuste conforme necessário

        $(".background-loading").removeClass("hidden");

        $.ajax({
            type: 'POST',
            url: '', 
            data: {
                action: 'accept',
                idServico: idServico,
                idContrato: idContrato,
                qtdServico: qtdServico,
                valorFinal: valorFinal
            },
            success: function(response) {
                $(".background-loading").addClass("hidden");
                const res = JSON.parse(response);
                Swal.fire('Serviço Aceito!', res.message, 'success');
                location.reload(); // Recarrega a página para atualizar a lista
            },
            error: function() {
                $(".background-loading").addClass("hidden");
                Swal.fire('Erro!', 'Não foi possível aceitar o serviço.', 'error');
            }
        });
    }

    function rejectService(idServico) {

        $(".background-loading").removeClass("hidden");

        $.ajax({
            type: 'POST',
            url: '',
            data: {
                action: 'reject',
                idServico: idServico
            },
            success: function(response) {
                $(".background-loading").addClass("hidden");
                const res = JSON.parse(response);
                Swal.fire('Serviço Recusado!', res.message, 'info');
                location.reload();
            },
            error: function() {
                $(".background-loading").addClass("hidden");
                Swal.fire('Erro!', 'Não foi possível recusar o serviço.', 'error');
            }
        });
    }

    function copyToClipboard(text) {
        navigator.clipboard.writeText(text).then(function() {
            showToast();
        }, function(err) {
            console.error('Erro ao copiar: ', err);
        });
    }

    function showToast() {
        const toast = document.getElementById('toast');
        toast.style.display = 'block';

        setTimeout(() => {
            toast.style.display = 'none';
        }, 3000);
    }

    function showContractorInfo(primeiroNome, telefone, celular, whatsapp, unique_id, email, id_contrato) {
        Swal.fire({
        title: 'Informações do Contratante',
        html: `
            <div style="text-align: left;">
                <p><strong>Nome:</strong> ${primeiroNome}</p><br>
                <p><strong>Telefone:</strong> ${telefone}</p><br>
                <p><strong>Celular:</strong> ${celular}</p><br>
                <p><strong>WhatsApp:</strong> ${whatsapp}</p><br>
                <p><strong>Email:</strong> ${email}</p><br>
            </div>
        `,
        showCancelButton: true,
        confirmButtonText: 'Fechar',
        cancelButtonText: 'Abrir Chat',
        width: '500px',
        padding: '1.5rem',
        customClass: {
            popup: 'swal-custom-popup',
            title: 'swal-custom-title',
            htmlContainer: 'swal-custom-html'
        },
    }).then((result) => {
        if (result.isConfirmed) {
            // O usuário clicou em "Fechar"
        } else if (result.dismiss === Swal.DismissReason.cancel) {
            window.open(`../../../../chat/chat.php?user_id=${unique_id}&proposta_id=${id_contrato}`, "_blank");
        }
    });
    }

    </script>
</body>
</html>
