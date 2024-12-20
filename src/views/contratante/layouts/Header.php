<?php
// Inicia a sessão
if (!isset($_SESSION)) {
    session_start();
}

require_once __DIR__ . '/../../../../config/config.php';

// Conexão com o banco de dados
$conn = new mysqli(DB_SERVER, DB_USERNAME, DB_PASSWORD, DB_NAME);

// Verifica se o formulário foi enviado via POST para atualizar o endereço
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['rua'], $_POST['numero'], $_POST['cidade'], $_POST['estado'], $_POST['cep'])) {
    header('Content-Type: application/json');
    // Verifica se houve erro na conexão
    if ($conn->connect_error) {
        echo json_encode(['success' => false, 'message' => 'Falha na conexão com o banco de dados.']);
        exit;
    }

    // Verifica se o usuário está logado
    if (!isset($_SESSION['id_usuario'])) {
        echo json_encode(['success' => false, 'message' => 'Usuário não autenticado.']);
        exit;
    }

    // Filtra e obtém os dados do formulário
    $id_usuario = $_SESSION['id_usuario'];
    $rua = $conn->real_escape_string($_POST['rua']);
    $numero = intval($_POST['numero']);
    $cidade = $conn->real_escape_string($_POST['cidade']);
    $estado = $conn->real_escape_string($_POST['estado']);
    $cep = $conn->real_escape_string($_POST['cep']);

    // Atualiza o endereço do usuário no banco de dados usando prepared statement
    $sql = "UPDATE enderecos SET rua = ?, numero = ?, cep = ?, bairro = ?, complemento = ? WHERE id_usuario = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("sisssi", $rua, $numero, $cep, $cidade, $estado, $id_usuario);

    // Executa a atualização e verifica o resultado
    if ($stmt->execute()) {
        echo json_encode(['success' => true, 'message' => 'Endereço atualizado com sucesso.']);
    } else {
        echo json_encode(['success' => false, 'message' => 'Erro ao atualizar o endereço: ' . $stmt->error]);
    }

    $stmt->close();
    $conn->close();
    exit;
}
?>

<script src="../../../../public/js/global/Loading.js"></script>

<!-- =================================      TOPO      =================================-->
<section id="popup-profile">
    <header class="topo-popup-profile">
        <div class="edit-user">
        <?php
            $userImage = (isset($_SESSION['img']) && strlen($_SESSION['img']) > 1) 
            ? '../../../../public/uploads/usuarios/'.$_SESSION['img'] 
            : '../../../../public/img/UserProfile-default.png';
        ?>
        <img class="img-editor" id="profile-image" src="<?php echo htmlspecialchars($userImage); ?>">    
            <span class="span-edit span-edit-fix-left">
                <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="#fff" class="bi bi-pencil-square" viewBox="0 0 16 16"><path d="M15.502 1.94a.5.5 0 0 1 0 .706L14.459 3.69l-2-2L13.502.646a.5.5 0 0 1 .707 0l1.293 1.293zm-1.75 2.456-2-2L4.939 9.21a.5.5 0 0 0-.121.196l-.805 2.414a.25.25 0 0 0 .316.316l2.414-.805a.5.5 0 0 0 .196-.12l6.813-6.814z"/><path fill-rule="evenodd" d="M1 13.5A1.5 1.5 0 0 0 2.5 15h11a1.5 1.5 0 0 0 1.5-1.5v-6a.5.5 0 0 0-1 0v6a.5.5 0 0 1-.5.5h-11a.5.5 0 0 1-.5-.5v-11a.5.5 0 0 1 .5-.5H9a.5.5 0 0 0 0-1H2.5A1.5 1.5 0 0 0 1 2.5z"/></svg>
            </span>
        </div>
        <input type="file" id="profile-image-input" accept="image/*" style="display: none;" onchange="updateProfileImage(event)">
        <h2 class="name-user"><?php echo isset($_SESSION['primeiro_nome']) ? $_SESSION['primeiro_nome'] : 'NotFound 404'; ?></h2>
    </header>
    <hr class="small-line">
    <div class="list-links">
        <a onclick="openModalPerfil()" class="link" href="#">
            <svg xmlns="http://www.w3.org/2000/svg" width="22" height="22" fill="currentColor" class="bi bi-person-fill" viewBox="0 0 16 16">
                <path d="M3 14s-1 0-1-1 1-4 6-4 6 3 6 4-1 1-1 1zm5-6a3 3 0 1 0 0-6 3 3 0 0 0 0 6"/>
            </svg>
            Alterar dados
        </a>
        <a class="link" onclick="openModalEndereco()" href="#">
            <svg xmlns="http://www.w3.org/2000/svg" width="22" height="22" fill="currentColor" class="bi bi-house-fill" viewBox="0 0 16 16">
                <path d="M8.707 1.5a1 1 0 0 0-1.414 0L.646 8.146a.5.5 0 0 0 .708.708L8 2.207l6.646 6.647a.5.5 0 0 0 .708-.708L13 5.793V2.5a.5.5 0 0 0-.5-.5h-1a.5.5 0 0 0-.5.5v1.293z"/>
                <path d="m8 3.293 6 6V13.5a1.5 1.5 0 0 1-1.5 1.5h-9A1.5 1.5 0 0 1 2 13.5V9.293z"/>
            </svg>
            Alterar endereço
        </a>
        <a class="link" onclick="openModalSecurity()" href="#">
            <svg xmlns="http://www.w3.org/2000/svg" width="22" height="22" fill="currentColor" class="bi bi-shield-lock-fill" viewBox="0 0 16 16">
                <path fill-rule="evenodd" d="M8 0c-.69 0-1.843.265-2.928.56-1.11.3-2.229.655-2.887.87a1.54 1.54 0 0 0-1.044 1.262c-.596 4.477.787 7.795 2.465 9.99a11.8 11.8 0 0 0 2.517 2.453c.386.273.744.482 1.048.625.28.132.581.24.829.24s.548-.108.829-.24a7 7 0 0 0 1.048-.625 11.8 11.8 0 0 0 2.517-2.453c1.678-2.195 3.061-5.513 2.465-9.99a1.54 1.54 0 0 0-1.044-1.263 63 63 0 0 0-2.887-.87C9.843.266 8.69 0 8 0m0 5a1.5 1.5 0 0 1 .5 2.915l.385 1.99a.5.5 0 0 1-.491.595h-.788a.5.5 0 0 1-.49-.595l.384-1.99A1.5 1.5 0 0 1 8 5"/>
            </svg>
            Segurança
        </a>
    </div>
    <hr class="small-line">
    <a onclick="Delete()" class="link link-child-logout" href="#">
        <svg xmlns="http://www.w3.org/2000/svg" width="22" height="22" fill="currentColor" class="bi bi-trash3-fill" viewBox="0 0 16 16">
          <path d="M11 1.5v1h3.5a.5.5 0 0 1 0 1h-.538l-.853 10.66A2 2 0 0 1 11.115 16h-6.23a2 2 0 0 1-1.994-1.84L2.038 3.5H1.5a.5.5 0 0 1 0-1H5v-1A1.5 1.5 0 0 1 6.5 0h3A1.5 1.5 0 0 1 11 1.5m-5 0v1h4v-1a.5.5 0 0 0-.5-.5h-3a.5.5 0 0 0-.5.5M4.5 5.029l.5 8.5a.5.5 0 1 0 .998-.06l-.5-8.5a.5.5 0 1 0-.998.06m6.53-.528a.5.5 0 0 0-.528.47l-.5 8.5a.5.5 0 0 0 .998.058l.5-8.5a.5.5 0 0 0-.47-.528M8 4.5a.5.5 0 0 0-.5.5v8.5a.5.5 0 0 0 1 0V5a.5.5 0 0 0-.5-.5"/>
        </svg>
        Excluir usuário
    </a>
    <hr class="small-line">
    <a onclick="Logout()" class="link link-child-logout" href="#">
        <svg xmlns="http://www.w3.org/2000/svg" width="22" height="22" fill="currentColor" class="bi bi-door-open-fill" viewBox="0 0 16 16">
            <path d="M1.5 15a.5.5 0 0 0 0 1h13a.5.5 0 0 0 0-1H13V2.5A1.5 1.5 0 0 0 11.5 1H11V.5a.5.5 0 0 0-.57-.495l-7 1A.5.5 0 0 0 3 1.5V15zM11 2h.5a.5.5 0 0 1 .5.5V15h-1zm-2.5 8c-.276 0-.5-.448-.5-1s.224-1 .5-1 .5.448.5 1-.224 1-.5 1"/>
        </svg>
        SAIR
    </a>
</section>

<script>
    $("#popup-profile").toggle();
    const spanEdit = document.querySelector('.span-edit');
    const profileImageInput = document.getElementById('profile-image-input');
    const imgEditor = document.querySelector('.img-editor');

    spanEdit.addEventListener('click', () => {
        profileImageInput.click();
    });

    profileImageInput.addEventListener('change', uploadImage);

    function uploadImage(event) {
        const input = event.target;

        if (input.files && input.files[0]) {
            const formData = new FormData();
            formData.append('arquivo', input.files[0]);

            const reader = new FileReader();
            reader.onload = function (e) {
                imgEditor.src = e.target.result;
            };
            reader.readAsDataURL(input.files[0]);

            $(".background-loading-50").removeClass('hidden');

            $.ajax({
                url: '../layouts/controller/upload.php',
                type: 'POST',
                data: formData,
                contentType: false,
                processData: false,
                success: function(data) {
                    $(".background-loading-50").addClass('hidden');
                    location.reload();
                },
                error: function(jqXHR, textStatus, errorThrown) {
                    $(".background-loading-50").addClass('hidden');
                    location.reload();
                }
            });
        }
    }

</script>

<!-- jQuery -->
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>

<!-- Notify.js -->
<script src="https://cdnjs.cloudflare.com/ajax/libs/notify/0.4.2/notify.min.js"></script>

<!-- Bootstrap Icons -->
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.7.2/font/bootstrap-icons.css">

<?php

$id_usuario = $_SESSION['id_usuario'];

$sql = "SELECT id, mensagem, tipo, status_lido, redirecionar, data_criacao
        FROM notificacoes
        WHERE usuario_id = ? AND status_lido = 0
        ORDER BY data_criacao DESC";
$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $id_usuario);

if ($stmt->execute()) {
    $result = $stmt->get_result();
    $notificacoesAgrupadas = [];

    while ($row = $result->fetch_assoc()) {
        $chave = $row['mensagem'] . '-' . $row['tipo'];

        if (isset($notificacoesAgrupadas[$chave])) {
            $notificacoesAgrupadas[$chave]['count']++;
        } else {
            $notificacoesAgrupadas[$chave] = [
                'mensagem' => $row['mensagem'],
                'tipo' => $row['tipo'],
                'status_lido' => $row['status_lido'],
                'redirecionar' => $row['redirecionar'],
                'data_criacao' => $row['data_criacao'],
                'count' => 1
            ];
        }
    }

    $notificacoes = array_values($notificacoesAgrupadas);
    $notificacaoCount = count($notificacoes);
}

$stmt->close();

?>

<header id="site-topo">
    <div onclick="window.location.href = '../PaginaInicial/'" class="logo-box">
        <img width="40px" height="40px" class="logo" src="../../../../public/img/logo/favicon.ico" alt="Logo Rodapé">
    </div>
    <nav class="navigation-box">
        <div class="links-box">
            <a class="link-element" href="../HistoricoProposta/">
                <img src="../../../../public/img/Icon-document.png" alt="Icon Propostas">
                Propostas
            </a>
            <a class="link-element" href="../ListagemServico/">
                <img src="../../../../public/img/Icon-search.png" alt="Icon Procurar">
                Procurar
            </a>
            <a class="link-element" href="#" onclick="toggleNotifications()">
                <img src="../../../../public/img/Icon-notification.png" alt="Icon Notificações">
                Notificações
                <?php if ($notificacaoCount > 0): ?>
                    <div class="burble-alert" id="notification-bubble" style="display: flex;"></div>
                <?php endif; ?>
            </a>
        </div>
        <div class="userProfile-circle">
            <?php
                $userImage = (isset($_SESSION['img']) && strlen($_SESSION['img']) > 1) 
                ? '../../../../public/uploads/usuarios/'.$_SESSION['img'] 
                : '../../../../public/img/UserProfile-default.png';
            ?>
            <img class="imag-topo" width="40px" heigth="40px" src="<?php echo htmlspecialchars($userImage); ?>">
            <img src="../../../../public/img/Topo-User-More.png" alt="Btn Mais informações">
        </div>
        <div class="openMenuTopo menu-mobile" data-bs-toggle="offcanvas" data-bs-target="#offcanvasExample" aria-controls="offcanvasExample">
            <svg xmlns="http://www.w3.org/2000/svg" width="32" height="32" fill="currentColor" class="bi bi-list" viewBox="0 0 16 16">
                <path fill-rule="evenodd" d="M2.5 12a.5.5 0 0 1 .5-.5h10a.5.5 0 0 1 0 1H3a.5.5 0 0 1-.5-.5m0-4a.5.5 0 0 1 .5-.5h10a.5.5 0 0 1 0 1H3a.5.5 0 0 1-.5-.5m0-4a.5.5 0 0 1 .5-.5h10a.5.5 0 0 1 0 1H3a.5.5 0 0 1-.5-.5"/>
            </svg>
        </div>
    </nav>
</header>
<!-- ================================================================================== -->

<div id="notification-dropdown" class="notification-dropdown" style="display:none;">
    <div id="notification-container" style="max-height: 300px; overflow-y: auto;">
        <ul id="notification-list">
            <?php 
            $limite = 4; 
            $totalNotificacoes = count($notificacoes);
            $notificacoesExibidas = array_slice($notificacoes, 0, $limite);
            ?>
            
            <?php if ($totalNotificacoes === 0): ?>
                <li>
                    <p>Não há notificações</p>
                </li>
            <?php else: ?>
                <?php foreach ($notificacoesExibidas as $notificacao): ?>
                    <li>
                        <a target="_BLANK" href="<?= htmlspecialchars($notificacao['redirecionar']) ?>" class="notification-link">
                            <span class="notification-icon">
                                <?php if ($notificacao['tipo'] === 'informacao'): ?>
                                    <i class="bi bi-info-circle"></i>
                                <?php elseif ($notificacao['tipo'] === 'servico'): ?>
                                    <i class="bi bi-briefcase"></i>
                                <?php elseif ($notificacao['tipo'] === 'segurança'): ?>
                                    <i class="bi bi-shield-lock"></i>
                                <?php else: ?>
                                    <i class="bi bi-bell"></i>
                                <?php endif; ?>
                            </span>
                            <p>
                                <?= htmlspecialchars($notificacao['mensagem']) ?>
                                <?= $notificacao['count'] > 1 ? " ({$notificacao['count']}x)" : '' ?>
                            </p>
                            <span class="notification-time">
                                <?= date('H:i', strtotime($notificacao['data_criacao'])) ?>
                            </span>
                        </a>
                    </li>
                <?php endforeach; ?>

                <?php if ($totalNotificacoes > $limite): ?>
                    <li class="moreNotify">
                        <a href="javascript:void(0)" id="show-more-notifications" class="notification-link">
                            <p>
                                +<?= ($totalNotificacoes - $limite) ?> notificações
                            </p>
                        </a>
                    </li>
                <?php endif; ?>
            <?php endif; ?>
        </ul>

        <ul id="remaining-notification-list" style="display:none;">
            <?php 
            $notificacoesRestantes = array_slice($notificacoes, $limite);
            foreach ($notificacoesRestantes as $notificacao): ?>
                <li>
                    <a target="_BLANK" href="<?= htmlspecialchars($notificacao['redirecionar']) ?>" class="notification-link">
                        <span class="notification-icon">
                            <?php if ($notificacao['tipo'] === 'informacao'): ?>
                                <i class="bi bi-info-circle"></i>
                            <?php elseif ($notificacao['tipo'] === 'servico'): ?>
                                <i class="bi bi-briefcase"></i>
                            <?php elseif ($notificacao['tipo'] === 'segurança'): ?>
                                <i class="bi bi-shield-lock"></i>
                            <?php else: ?>
                                <i class="bi bi-bell"></i>
                            <?php endif; ?>
                        </span>
                        <p>
                            <?= htmlspecialchars($notificacao['mensagem']) ?>
                            <?= $notificacao['count'] > 1 ? " ({$notificacao['count']}x)" : '' ?>
                        </p>
                        <span class="notification-time">
                            <?= date('H:i', strtotime($notificacao['data_criacao'])) ?>
                        </span>
                    </a>
                </li>
            <?php endforeach; ?>
        </ul>
    </div>
</div>

<script>
    var showMoreButton = document.getElementById('show-more-notifications');
    if (showMoreButton) {
        showMoreButton.addEventListener('click', function() {
            var remainingNotifications = document.getElementById('remaining-notification-list');
            
            if (remainingNotifications.style.display === "none") {
                remainingNotifications.style.display = "block";
                $(".moreNotify").hide();
            } else {
                remainingNotifications.style.display = "none";
            }
        });
    }

    <?php if ($totalNotificacoes != 0): ?>
        $.notify("Você possui novas notificações", {
            className: "info",
            position: "bottom right",
            autoHide: true,       
            autoHideDelay: 3000,  
            showAnimation: "fadeIn", 
            hideAnimation: "fadeOut",
            style: 'bootstrap',   
        });
    <?php endif; ?>
</script>


<script>
function toggleNotifications() {
    const dropdown = document.getElementById('notification-dropdown');
    const popupProfile = document.getElementById('popup-profile');

    // Alterna a visibilidade do notification-dropdown
    if (dropdown.style.display === 'none' || dropdown.style.display === '') {
        dropdown.style.display = 'block';
        popupProfile.style.display = 'none'; // Oculta o popup-profile
    } else {
        dropdown.style.display = 'none';
    }
}
</script>

<?php

// Verifica se o usuário está logado
if (!isset($_SESSION['id_usuario'])) {
    die("Usuário não autenticado.");
}

// Verifica se houve erro na conexão
if ($conn->connect_error) {
    die("Conexão falhou: " . $conn->connect_error);
}

// Recupera o endereço atual do usuário
$id_usuario = $_SESSION['id_usuario'];
$sql = "SELECT rua, numero, cep, bairro, complemento FROM enderecos WHERE id_usuario = ?";
$stmt = $conn->prepare($sql);

// Verifique se a preparação da consulta foi bem-sucedida
if (!$stmt) {
    die("Erro na preparação da consulta: " . $conn->error);
}

$stmt->bind_param("i", $id_usuario);
$stmt->execute();
$result = $stmt->get_result();
$endereco = $result->fetch_assoc();

?>

<!-- Modal de Alterar Endereço -->
<div id="modal-alterar-endereco" class="modal-alterar-endereco" style="display: none">
    <div class="modal-content-alterar-endereco">
        <span class="close-alterar-endereco" onclick="closeModalEndereco()">&times;</span>
        <form id="form-alterar-endereco" action="../layouts/controller/UpdateAdress.php" method="POST">
            <div class="form-group-alterar-endereco">
                <label for="cep">CEP:</label>
                <input type="text" id="cep" name="cep" class="input cep mascara-cep" value="<?php echo isset($endereco['cep']) ? $endereco['cep'] : ''; ?>" required>
            </div>
            <hr>
            <div class="form-group-alterar-endereco">
                <label for="numero">Número:</label>
                <input type="text" id="numero" name="numero" value="<?php echo isset($endereco['numero']) ? $endereco['numero'] : ''; ?>" required>
            </div>
            <div class="form-group-alterar-endereco">
                <label for="rua">Rua:</label>
                <input type="text" id="rua" name="rua" class="input rua" value="<?php echo isset($endereco['rua']) ? $endereco['rua'] : ''; ?>" required disabled>
            </div>
            <div class="form-group-alterar-endereco">
                <label for="bairro">Bairro:</label>
                <input type="text" id="bairro" name="bairro" class="input bairro" value="<?php echo isset($endereco['bairro']) ? $endereco['bairro'] : ''; ?>" required disabled>
            </div>
            <div class="form-group-alterar-endereco">
                <label for="complemento">Complemento:</label>
                <input type="text" id="complemento" name="complemento" class="input complemento" value="<?php echo isset($endereco['complemento']) ? $endereco['complemento'] : ''; ?>">
            </div>
            <button type="submit" class="btn-alterar-endereco">Salvar</button>
        </form>
    </div>
</div>

<div class="background-loading-50 hidden">
    <div class="loading-icon"></div>
</div>

<script>
function openModalEndereco() {
    document.getElementById('modal-alterar-endereco').style.display = 'flex';
    $("#popup-profile").toggle();
}

function openModalEnderecoOffcanvas() {
    document.getElementById('modal-alterar-endereco').style.display = 'flex';
    const offcanvasElement = document.getElementById('offcanvasExample');
    const offcanvasInstance = bootstrap.Offcanvas.getOrCreateInstance(offcanvasElement);
    offcanvasInstance.hide();
}

function closeModalEndereco() {
    document.getElementById('modal-alterar-endereco').style.display = 'none';
}

$(document).ready(function() {
    // Evento para consulta do CEP
    $('.mascara-cep').on('input', function () {
        const cep = $(this).val().trim().replace(/\D/g, ''); // Remove caracteres não numéricos
        if (cep.length === 8) {
            $(".background-loading-50").removeClass('hidden');

            $.ajax({
                url: `https://viacep.com.br/ws/${cep}/json/`,
                method: 'GET',
                success: function (data) {
                    $(".background-loading-50").addClass('hidden');
                    if (data.erro) {
                        Swal.fire({
                            icon: 'error',
                            title: 'CEP Inválido',
                            text: 'Não foi possível encontrar o CEP informado.'
                        });
                    } else {
                        // Preenche os campos com os dados do CEP
                        $('.input.rua').val(data.logradouro).prop('disabled', false);
                        $('.input.bairro').val(data.bairro).prop('disabled', false);
                        $('.input.cidade').val(data.localidade).prop('disabled', false);
                        $('.input.uf').val(data.uf).prop('disabled', false);

                        // Remove as labels dos campos preenchidos
                        $('.input.rua').siblings('label').hide();
                        $('.input.bairro').siblings('label').hide();
                        $('.input.cidade').siblings('label').hide();
                        $('.input.uf').siblings('label').hide();
                    }
                },
                error: function () {
                    $(".background-loading-50").addClass('hidden');
                    Swal.fire({
                        icon: 'error',
                        title: 'Erro',
                        text: 'Erro ao consultar o CEP. Tente novamente mais tarde.'
                    });
                }
            });
        } else if (cep.length > 8) {
            Swal.fire({
                icon: 'warning',
                title: 'CEP Inválido',
                text: 'O CEP deve ter exatamente 8 dígitos.'
            });
        }
    });

    // Interceptar a submissão do formulário
    $("#form-alterar-endereco").on("submit", function(event) {
        event.preventDefault();

        $(".background-loading-50").removeClass('hidden');

        $.ajax({
            url: $(this).attr('action'),
            type: 'POST',
            data: $(this).serialize(),
            success: function(data) {
                $(".background-loading-50").addClass('hidden');
                if (data.success) {
                    Swal.fire({
                        icon: 'success',
                        title: 'Sucesso!',
                        text: data.message 
                    });
                    closeModalEndereco();
                } else {
                    Swal.fire({
                        icon: 'error',
                        title: 'Erro',
                        text: data.message
                    });
                }
            },
            error: function(xhr, status, error) {
                $(".background-loading-50").addClass('hidden');
                console.error('Erro na requisição:', xhr);
                Swal.fire({
                    icon: 'error',
                    title: 'Erro',
                    text: 'Ocorreu um erro, tente novamente mais tarde.'
                });
            }
        });
    });
});
</script>


</script>

<style>
.modal-alterar-endereco {
    position: fixed; 
    z-index: 999; 
    left: 0;
    top: 0;
    width: 100%;
    height: 100%;
    background-color: rgba(0,0,0,0.4); 
    overflow: hidden;
    display: flex;
    align-items: center;
    justify-content: center;
}

.modal-alterar-endereco * {
    font-family: sans-serif;
}

.modal-content-alterar-endereco {
    background-color: #fff; 
    padding: 20px;
    border: 1px solid #888;
    width: 80%; 
    max-width: 500px;
    border-radius: 10px;
}

@media screen and (max-width: 500px) {
    .modal-content-alterar-endereco {
        width: 100%;
        max-width: auto;
        height: 100%;
        max-height: auto;
        border-radius: 0;
    }
}

.modal-content-alterar-endereco h2 {
    margin-bottom: 15px
}

.close-alterar-endereco {
    color: #aaa;
    float: right;
    font-size: 28px;
    font-weight: bold;
}

.close-alterar-endereco:hover,
.close-alterar-endereco:focus {
    color: black;
    cursor: pointer;
}

.form-group-alterar-endereco {
    margin-bottom: 15px;
}

.form-group-alterar-endereco label {
    display: block;
    margin-bottom: 5px;
}

.form-group-alterar-endereco input {
    width: 100%;
    padding: 10px;
    box-sizing: border-box;
}

.btn-alterar-endereco {
    background-color: #FA511D;
    color: white;
    padding: 10px 20px;
    border: none;
    cursor: pointer;
    font-size: 16px;
    width: 100%;
}

.btn-alterar-endereco:hover {
    background-color: #FA511D;
}
</style>


<?php

// Verifica se o usuário está logado
if (!isset($_SESSION['id_usuario'])) {
    die("Usuário não autenticado.");
}

// Verifica se houve erro na conexão
if ($conn->connect_error) {
    die("Conexão falhou: " . $conn->connect_error);
}

$id_usuario = $_SESSION['id_usuario'];
$query_usuario = "SELECT * FROM usuarios WHERE id_usuario = ?";
$stmt_usuario = $conn->prepare($query_usuario);

// Verifique se a preparação da consulta foi bem-sucedida
if (!$stmt_usuario) {
    die("Erro na preparação da consulta: " . $conn->error);
}

$stmt_usuario->bind_param("i", $id_usuario);
$stmt_usuario->execute();
$result = $stmt_usuario->get_result();
$usuario = $result->fetch_assoc();

$stmt_usuario->close();
$conn->close();
?>


<!-- Modal de Editar Perfil -->
<div id="modal-editar-perfil" class="modal-editar-perfil" style="display: none">
    <div class="modal-content-editar-perfil">
        <span class="close-editar-perfil" onclick="closeModalPerfil()">&times;</span>
        <form id="form-editar-perfil" action="../layouts/controller/UpdateProfile.php" method="POST">
            <div class="form-group-editar-perfil">
                <label for="primeiro_nome">Primeiro nome:</label>
                <input type="text" id="primeiro_nome" name="primeiro_nome" class="input primeiro_nome" value="<?php echo isset($usuario['primeiro_nome']) ? $usuario['primeiro_nome'] : ''; ?>" required>
            </div>
            <div class="form-group-editar-perfil">
                <label for="ultimo_nome">Último nome:</label>
                <input type="text" id="ultimo_nome" name="ultimo_nome" class="input ultimo_nome" value="<?php echo isset($usuario['ultimo_nome']) ? $usuario['ultimo_nome'] : ''; ?>" required>
            </div>
            <hr>
            <div class="form-group-editar-perfil">
                <label for="email">E-mail:</label>
                <input type="email" id="email" name="email" class="input email" value="<?php echo isset($usuario['email']) ? $usuario['email'] : ''; ?>" required>
            </div>
            <div class="form-group-editar-perfil">
                <label for="telefone">Telefone:</label>
                <input type="text" id="telefone" name="telefone" class="input telefone mascara-telefone" value="<?php echo isset($usuario['telefone']) ? $usuario['telefone'] : ''; ?>" required>
            </div>
            <div class="form-group-editar-perfil">
                <label for="celular">Celular:</label>
                <input type="text" id="celular" name="celular" class="input celular mascara-telefone" value="<?php echo isset($usuario['celular']) ? $usuario['celular'] : ''; ?>" >
            </div>
            <div class="form-group-editar-perfil">
                <label for="whatsapp">WhatsApp:</label>
                <input type="text" id="whatsapp" name="whatsapp" class="input whatsapp mascara-telefone" value="<?php echo isset($usuario['whatsapp']) ? $usuario['whatsapp'] : ''; ?>" >
            </div>
            <hr>
            <div class="form-group-editar-perfil">
                <label for="senha">Nova Senha:</label>
                <input type="password" id="senha" name="senha" class="input senha">
            </div>
            <div class="form-group-editar-perfil">
                <label for="confirmar-senha">Confirmar Senha:</label>
                <input type="password" id="confirmar-senha" name="confirmar_senha" class="input confirmar-senha">
            </div>
            <button type="submit" class="btn-editar-perfil">Salvar</button>
        </form>
    </div>
</div>

<div class="background-loading-50 hidden">
    <div class="loading-icon"></div>
</div>

<script>
function openModalPerfil() {
    document.getElementById('modal-editar-perfil').style.display = 'flex';
    $("#popup-profile").toggle();
    const offcanvasElement = document.getElementById('offcanvasExample');
    const offcanvasInstance = bootstrap.Offcanvas.getOrCreateInstance(offcanvasElement);
    offcanvasInstance.hide();
}

function openModalPerfilOffcanvas() {
    document.getElementById('modal-editar-perfil').style.display = 'flex';
    const offcanvasElement = document.getElementById('offcanvasExample');
    const offcanvasInstance = bootstrap.Offcanvas.getOrCreateInstance(offcanvasElement);
    offcanvasInstance.hide();
}

function closeModalPerfil() {
    document.getElementById('modal-editar-perfil').style.display = 'none';
}
</script>

<style>
.modal-editar-perfil {
    position: fixed; 
    z-index: 1; 
    left: 0;
    top: 0;
    width: 100%;
    height: 100%;
    background-color: rgba(0, 0, 0, 0.4); 
    overflow: hidden;
    display: flex;
    align-items: center;
    justify-content: center;
}

.modal-editar-perfil * {
    font-family: sans-serif;
}

.modal-content-editar-perfil {
    background-color: #fff; 
    padding: 20px;
    border: 1px solid #888;
    width: 80%; 
    max-width: 500px;
    border-radius: 10px;
    max-height: 80%; /* Define a altura máxima do conteúdo */
    overflow-y: auto; /* Permite rolagem vertical se necessário */
}

@media screen and (max-width: 500px) {
    .modal-content-editar-perfil {
        width: 100%;
        max-width: auto;
        max-height: 100%;
        border-radius: 0;
    }
}

.modal-content-editar-perfil h2 {
    margin-bottom: 15px;
}

.close-editar-perfil {
    color: #aaa;
    float: right;
    font-size: 28px;
    font-weight: bold;
}

.close-editar-perfil:hover,
.close-editar-perfil:focus {
    color: black;
    cursor: pointer;
}

.form-group-editar-perfil {
    margin-bottom: 15px;
}

.form-group-editar-perfil label {
    display: block;
    margin-bottom: 5px;
}

.form-group-editar-perfil input {
    width: 100%;
    padding: 10px;
    box-sizing: border-box;
}

.btn-editar-perfil {
    background-color: #FA511D;
    color: white;
    padding: 10px 20px;
    border: none;
    cursor: pointer;
    font-size: 16px;
    width: 100%;
}

.btn-editar-perfil:hover {
    background-color: #FA511D;
}
</style>


<script>
    $('#form-editar-perfil').on('submit', function(event) {
        event.preventDefault();

        $(".background-loading-50").removeClass('hidden');

        var formData = $(this).serialize();

        // Envia a requisição AJAX
        $.ajax({
            url: '../layouts/controller/UpdateProfile.php',
            type: 'POST',
            data: formData,
            dataType: 'json',
            success: function(response) {

                $(".background-loading-50").addClass('hidden');
                document.getElementById('modal-editar-perfil').style.display = 'none';

                if (response.success) {
                    Swal.fire({
                        icon: 'success',
                        title: 'Sucesso!',
                        text: response.message,
                        confirmButtonText: 'OK'
                    }).then(() => {
                    });
                } else {
                    Swal.fire({
                        icon: 'error',
                        title: 'Erro!',
                        text: response.message,
                        confirmButtonText: 'OK'
                    });
                }
            },
            error: function(xhr, status, error) {
                $(".background-loading-50").addClass('hidden');
                
                console.error('Erro AJAX:', error);
                alert('Ocorreu um erro ao atualizar o perfil. Tente novamente.');
            }
        });
    });
</script>

<?php

function gerarSegredoBase32($length = 16) {
    $bytes = random_bytes($length);
    
    $base32 = '';

    $alphabet = 'ABCDEFGHIJKLMNOPQRSTUVWXYZ234567';
    for ($i = 0; $i < strlen($bytes); $i++) {
        $base32 .= $alphabet[ord($bytes[$i]) >> 3];
        $base32 .= $alphabet[(ord($bytes[$i]) & 0x07) << 2];
    }

    return substr($base32, 0, $length);
}

if (!isset($_SESSION)) {
    session_start();
}

require '../../../../vendor/autoload.php';

use OTPHP\TOTP;

$username = $usuario['primeiro_nome'];
$hostname = 'TrabalhoAmigo';

$secret = gerarSegredoBase32(16);
$totp = TOTP::create($secret);

$totp->setLabel($username . '@' . $hostname);
$_SESSION['TOTP_secret'] = $totp->getSecret();
$provisioningUri = $totp->getProvisioningUri();
$urimage = 'https://api.qrserver.com/v1/create-qr-code/?data=' . urlencode($provisioningUri); 

?>

<!-- Modal de segurança -->
<div id="modal-alterar-seguranca" class="modal-alterar-endereco" style="display: none">
    <div class="modal-content-alterar-endereco">
        <span class="close-alterar-endereco" onclick="closeModalSeguranca()">&times;</span>

        <?php if (!isset($usuario['totp_secret']) || empty($usuario['totp_secret'])): ?>
            <!-- Se o usuário não tiver o `totp_secret`, exibe o formulário para configurar o TOTP -->
            <form id="form-alterar-securanca" action="../layouts/controller/AuthTOTP.php" method="POST">
                <div class="qrcode-container">
                    <img class="arcode-imagem" src="<?= $urimage ?>" alt="Imagem qrcode">
                </div>
                <div class="qrcode-description">
                    <p class="text-qrcode">
                        Escaneie pelo seu celular
                    </p>
                </div>
                <hr class="margin-bottom-15-px">
                <div class="form-group-alterar-endereco">
                    <label for="numero">Código:</label>
                    <input maxlength="6" type="text" id="code" name="code" placeholder="Digite o código que aparece em seu aplicativo vinculado" required>
                </div>
                <button type="submit" class="btn-alterar-endereco">Verificar código</button>
            </form>
        <?php else: ?>
            <!-- Se o usuário já tiver o `totp_secret`, exibe uma mensagem de que a autenticação TOTP está configurada -->
            <div class="totp-configured">
                <p>Ativar/Desativar Autenticação de Dois Fatores.</p>
                <section class="container-fatores">
                <label class="switch">
                <input type="checkbox" id="fatores" name="fatores" <?php if (isset($usuario['totp_enabled']) && $usuario['totp_enabled'] == 1) echo 'checked'; ?> />
                <span class="slider"></span>
                </label>
                </section>
                <hr>
                <a href="#" class="reset-fator">Resetar autenticação de dois fatores</a>
            </div>
        <?php endif; ?>
    </div>
</div>

<script>
function openModalSecurity() {
    document.getElementById('modal-alterar-seguranca').style.display = 'flex';
    $("#popup-profile").toggle();
}

function openModalSecurityOffcanvas() {
    document.getElementById('modal-alterar-seguranca').style.display = 'flex';
    const offcanvasElement = document.getElementById('offcanvasExample');
    const offcanvasInstance = bootstrap.Offcanvas.getOrCreateInstance(offcanvasElement);
    offcanvasInstance.hide();
}

function closeModalSeguranca() {
    document.getElementById('modal-alterar-seguranca').style.display = 'none';
}

// Interceptar a submissão do formulário
$("#form-alterar-securanca").on("submit", function(event) {
    event.preventDefault();

    $(".background-loading-50").removeClass('hidden');

    $.ajax({
        url: $(this).attr('action'),
        type: 'POST',
        data: $(this).serialize(),
        success: function(data) {
            $(".background-loading-50").addClass('hidden');
            if (data.success) {
                Swal.fire({
                    icon: 'success',
                    title: 'Sucesso!',
                    text: data.message 
                });
                closeModalSeguranca();
                location.reload();
            } else {
                Swal.fire({
                    icon: 'error',
                    title: 'Erro',
                    text: data.message
                });
            }
        },
        error: function(xhr, status, error) {
            $(".background-loading-50").addClass('hidden');
            console.error('Erro na requisição:', xhr);
            Swal.fire({
                icon: 'error',
                title: 'Erro',
                text: 'Ocorreu um erro, tente novamente mais tarde.'
            });
        }
    });
});


$(document).ready(function(){
    $('#fatores').change(function(){
        var isChecked = $(this).is(':checked');
        
        $(".background-loading-50").removeClass('hidden');

        $.ajax({
            url: '../layouts/controller/AuthTOTP_button.php',
            method: 'POST',
            data: {
                ativar_totp: isChecked ? 1 : 0
            },
            success: function(response) {
                $(".background-loading-50").addClass('hidden');
                try {
                    var data = response;

                    if (data.success) {
                        Swal.fire({
                            icon: 'success',
                            title: 'Sucesso!',
                            text: data.message
                        });
                    } else {
                        Swal.fire({
                            icon: 'error',
                            title: 'Erro',
                            text: data.message
                        });
                    }
                } catch (e) {
                    console.error('Erro ao processar a resposta:', e);
                    Swal.fire({
                        icon: 'error',
                        title: 'Erro',
                        text: 'Ocorreu um erro inesperado ao processar a resposta.'
                    });
                }
            },
            error: function(xhr, status, error) {
                $(".background-loading-50").addClass('hidden');
                Swal.fire({
                    icon: 'error',
                    title: 'Erro',
                    text: 'Erro na requisição: ' + error
                });
            }
        });
    });
});


$(document).ready(function() {
    $('.reset-fator').on('click', function(e) {
        e.preventDefault(); // Impede o comportamento padrão do link

        Swal.fire({
            title: 'Você tem certeza?',
            text: 'Deseja realmente resetar a autenticação de dois fatores?',
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#3085d6',
            cancelButtonColor: '#d33',
            confirmButtonText: 'Sim, resetar',
            cancelButtonText: 'Cancelar'
        }).then((result) => {
            if (result.isConfirmed) {
                $(".background-loading-50").removeClass('hidden'); // Mostra o loader

                $.ajax({
                    url: '../layouts/controller/AuthTOTP_reset.php',
                    method: 'POST',
                    data: {
                        reset_totp: 1
                    },
                    success: function(response) {
                        $(".background-loading-50").addClass('hidden');
                        try {
                            var data = typeof response === 'string' ? JSON.parse(response) : response;

                            if (data.success) {
                                Swal.fire({
                                    icon: 'success',
                                    title: 'Sucesso!',
                                    text: data.message
                                });
                                location.reload();
                            } else {
                                Swal.fire({
                                    icon: 'error',
                                    title: 'Erro',
                                    text: data.message
                                });
                            }
                        } catch (e) {
                            console.error('Erro ao processar a resposta:', e);
                            Swal.fire({
                                icon: 'error',
                                title: 'Erro',
                                text: 'Ocorreu um erro inesperado ao processar a resposta.'
                            });
                        }
                    },
                    error: function(xhr, status, error) {
                        $(".background-loading-50").addClass('hidden'); // Esconde o loader
                        console.error('Erro na requisição:', xhr, status, error);
                        Swal.fire({
                            icon: 'error',
                            title: 'Erro',
                            text: 'Ocorreu um erro, tente novamente mais tarde.'
                        });
                    }
                });
            }
        });
    });
});


</script>

<div class="offcanvas offcanvas-end" tabindex="-1" id="offcanvasExample" aria-labelledby="offcanvasExampleLabel">
  <div class="offcanvas-header">
    <h5 class="offcanvas-title" id="offcanvasExampleLabel">
        <img src="../../../../public/img/logo/favicon.ico" alt="">    
        Trabalho Amigo
    </h5>
    <button type="button" class="btn-close" data-bs-dismiss="offcanvas" aria-label="Close"></button>
  </div>
  <div class="offcanvas-body">
    <a href="../HistoricoProposta/">Histórico de propostas</a>
    <a href="../ListagemServico/">Listagem de serviços</a>
    <hr>
    <div class="info-profile">
        <div class="row-offcanvas">
            <?php
                $userImage = (isset($_SESSION['img']) && strlen($_SESSION['img']) > 1) 
                ? '../../../../public/uploads/usuarios/'.$_SESSION['img'] 
                : '../../../../public/img/UserProfile-default.png';
            ?>
            <img src="<?= $userImage; ?>" alt="Imagem usuário">
            <span class="username"><?= $_SESSION['primeiro_nome'] ?></span>    
        </div>
        <div class="row-offcanvas">
            <a onclick="openModalPerfilOffcanvas()" href="#">
                <svg xmlns="http://www.w3.org/2000/svg" width="22" height="22" fill="currentColor" class="bi bi-person-fill" viewBox="0 0 16 16">
                    <path d="M3 14s-1 0-1-1 1-4 6-4 6 3 6 4-1 1-1 1zm5-6a3 3 0 1 0 0-6 3 3 0 0 0 0 6"/>
                </svg>
                Alterar dados
            </a>    
        </div>
        <div class="row-offcanvas">
            <a onclick="openModalEnderecoOffcanvas()" href="#">
                <svg xmlns="http://www.w3.org/2000/svg" width="22" height="22" fill="currentColor" class="bi bi-house-fill" viewBox="0 0 16 16">
                    <path d="M8.707 1.5a1 1 0 0 0-1.414 0L.646 8.146a.5.5 0 0 0 .708.708L8 2.207l6.646 6.647a.5.5 0 0 0 .708-.708L13 5.793V2.5a.5.5 0 0 0-.5-.5h-1a.5.5 0 0 0-.5.5v1.293z"/>
                    <path d="m8 3.293 6 6V13.5a1.5 1.5 0 0 1-1.5 1.5h-9A1.5 1.5 0 0 1 2 13.5V9.293z"/>
                </svg>
                Alterar endereço
            </a>
        </div>
        <div class="row-offcanvas">
            <a onclick="openModalSecurityOffcanvas()" href="#">
                <svg xmlns="http://www.w3.org/2000/svg" width="22" height="22" fill="currentColor" class="bi bi-shield-lock-fill" viewBox="0 0 16 16">
                    <path fill-rule="evenodd" d="M8 0c-.69 0-1.843.265-2.928.56-1.11.3-2.229.655-2.887.87a1.54 1.54 0 0 0-1.044 1.262c-.596 4.477.787 7.795 2.465 9.99a11.8 11.8 0 0 0 2.517 2.453c.386.273.744.482 1.048.625.28.132.581.24.829.24s.548-.108.829-.24a7 7 0 0 0 1.048-.625 11.8 11.8 0 0 0 2.517-2.453c1.678-2.195 3.061-5.513 2.465-9.99a1.54 1.54 0 0 0-1.044-1.263 63 63 0 0 0-2.887-.87C9.843.266 8.69 0 8 0m0 5a1.5 1.5 0 0 1 .5 2.915l.385 1.99a.5.5 0 0 1-.491.595h-.788a.5.5 0 0 1-.49-.595l.384-1.99A1.5 1.5 0 0 1 8 5"/>
                </svg>
                Segurança
            </a>    
        </div>
        <hr>
        <div class="row-offcanvas">
            <a class="alert" onclick="Delete()" href="#">
                <svg xmlns="http://www.w3.org/2000/svg" width="22" height="22" fill="currentColor" class="bi bi-trash3-fill" viewBox="0 0 16 16">
                  <path d="M11 1.5v1h3.5a.5.5 0 0 1 0 1h-.538l-.853 10.66A2 2 0 0 1 11.115 16h-6.23a2 2 0 0 1-1.994-1.84L2.038 3.5H1.5a.5.5 0 0 1 0-1H5v-1A1.5 1.5 0 0 1 6.5 0h3A1.5 1.5 0 0 1 11 1.5m-5 0v1h4v-1a.5.5 0 0 0-.5-.5h-3a.5.5 0 0 0-.5.5M4.5 5.029l.5 8.5a.5.5 0 1 0 .998-.06l-.5-8.5a.5.5 0 1 0-.998.06m6.53-.528a.5.5 0 0 0-.528.47l-.5 8.5a.5.5 0 0 0 .998.058l.5-8.5a.5.5 0 0 0-.47-.528M8 4.5a.5.5 0 0 0-.5.5v8.5a.5.5 0 0 0 1 0V5a.5.5 0 0 0-.5-.5"/>
                </svg>
                Excluir usuário
            </a>
        </div>
        <div class="row-offcanvas">
            <a class="alert" onclick="Logout()" href="#">
                <svg xmlns="http://www.w3.org/2000/svg" width="22" height="22" fill="currentColor" class="bi bi-door-open-fill" viewBox="0 0 16 16">
                    <path d="M1.5 15a.5.5 0 0 0 0 1h13a.5.5 0 0 0 0-1H13V2.5A1.5 1.5 0 0 0 11.5 1H11V.5a.5.5 0 0 0-.57-.495l-7 1A.5.5 0 0 0 3 1.5V15zM11 2h.5a.5.5 0 0 1 .5.5V15h-1zm-2.5 8c-.276 0-.5-.448-.5-1s.224-1 .5-1 .5.448.5 1-.224 1-.5 1"/>
                </svg>
                SAIR
            </a>
        </div>
    </div>
  </div>
</div>

<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css" rel="stylesheet">
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/js/bootstrap.bundle.min.js"></script>

<style>

    .row-offcanvas img {
        width: 55px;
        height: 55px;
        border-radius: 50%;
        object-fit: cover;
        border: 1px solid #888;
    }
    
    .row-offcanvas a {
        gap: 10px;
    }

    .row-offcanvas .username {
        font-size: 18px;
        font-weight: 500;
        color: #2d2d2d;
    }

    .row-offcanvas {
        display: flex;
        justify-content: start;
        gap: 10px;
        align-items: center;
        background: rgb(244, 244, 244);
        padding: 10px;
        border-radius: 6px;
        border: 1px solid #888;
    }

    .info-profile {
        width: 100%;
        display: flex;
        flex-direction: column;
        row-gap: 10px;
    }

    .offcanvas {
        width: 100% !important;
    }

    .offcanvas-header {
        padding: 40px 20px 20px 20px;
    }

    .offcanvas-title {
        display: flex;
        align-items: center;
        gap: 20px;
    }

    .offcanvas-title img {
        width: 40px;
        height: 40px;
    }

    .list-group-item a {
        text-decoration: none;
    }  
    
    .offcanvas-body {
        padding: 20px 20px;
        display: flex;
        flex-direction: column;
        gap: 5px;
    }

    .offcanvas-body a {
        width: calc(100% - 40px);
        background: #2f82fa;
        display: flex;
        justify-content: start;
        padding: 10px 20px;
        color: #fff;
        text-decoration: none;
        font-size: 18px;
        font-weight: 500;
        border-radius: 6px;
    }

    .row-offcanvas .alert {
        background: #FA511D;
        margin-bottom: 0;
    }

</style>