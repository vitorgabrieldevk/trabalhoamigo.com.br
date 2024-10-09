<!DOCTYPE html>
<html lang="pt-br">
<head>

    <!-- Tituloda Página - SEO -->
    <title>Listagem Serviços | Trabalho Amigo</title>
    <!-- Descrição da Página - SEO -->
    <meta name="description" content="Crie sua Conta">

    <!-- Metas tags de configurações das páginas -->
    <meta charset='utf-8'>
    <meta http-equiv='X-UA-Compatible' content='IE=edge'>
    <meta name='viewport' content='width=device-width, initial-scale=1'>
    
    <!-- Importação do Icone do Projeto -->
    <link rel="icon" href="../../favicon.ico" type="image/x-icon">

    <!-- Tags Globais do projeto -->
    <script src="../../../../app.js" defer></script>
    <link rel="stylesheet" href="../../../../app.css">

    <!-- Tags Especificas de cada página-->
    <link rel="stylesheet" href="../../../../public/css/anunciante/ListagemPropostas.css">

    <script src="../../../../public/js/contratante/Listagem.js" defer></script>

    <!-- Importação da bibliotecas -->
    <script src="https://code.jquery.com/jquery-3.7.1.min.js" integrity="sha256-/JqT3SQfawRcv/BIHPThkBvs0OEvtFFmqPF/lYI/Cxo=" crossorigin="anonymous"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>


    <body>

        <script>
            $.ajax({
                url: `../../../controllers/contratante/Security.php`,
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

        <section id="popup-profile">
            <header class="topo-popup-profile">
                <img src="../../../../public/img/user_profile.png" alt="USER PROFILE">
                <h2 class="name-user">Vitor Gabriel</h2>
            </header>
            <hr class="small-line">
            <div class="list-links">
                <a class="link" href="#">
                    <svg xmlns="http://www.w3.org/2000/svg" width="22" height="22" fill="currentColor" class="bi bi-person-fill" viewBox="0 0 16 16">
                        <path d="M3 14s-1 0-1-1 1-4 6-4 6 3 6 4-1 1-1 1zm5-6a3 3 0 1 0 0-6 3 3 0 0 0 0 6"/>
                    </svg>
                    Meu Perfil
                </a>
                <a class="link" href="#">
                    <svg xmlns="http://www.w3.org/2000/svg" width="22" height="22" fill="currentColor" class="bi bi-gear-fill" viewBox="0 0 16 16">
                        <path d="M9.405 1.05c-.413-1.4-2.397-1.4-2.81 0l-.1.34a1.464 1.464 0 0 1-2.105.872l-.31-.17c-1.283-.698-2.686.705-1.987 1.987l.169.311c.446.82.023 1.841-.872 2.105l-.34.1c-1.4.413-1.4 2.397 0 2.81l.34.1a1.464 1.464 0 0 1 .872 2.105l-.17.31c-.698 1.283.705 2.686 1.987 1.987l.311-.169a1.464 1.464 0 0 1 2.105.872l.1.34c.413 1.4 2.397 1.4 2.81 0l.1-.34a1.464 1.464 0 0 1 2.105-.872l.31.17c1.283.698 2.686-.705 1.987-1.987l-.169-.311a1.464 1.464 0 0 1 .872-2.105l.34-.1c1.4-.413 1.4-2.397 0-2.81l-.34-.1a1.464 1.464 0 0 1-.872-2.105l.17-.31c.698-1.283-.705-2.686-1.987-1.987l-.311.169a1.464 1.464 0 0 1-2.105-.872zM8 10.93a2.929 2.929 0 1 1 0-5.86 2.929 2.929 0 0 1 0 5.858z"/>
                    </svg>
                    Configurações
                </a>
                <a class="link" href="#">
                    <svg xmlns="http://www.w3.org/2000/svg" width="22" height="22" fill="currentColor" class="bi bi-question-circle-fill" viewBox="0 0 16 16">
                        <path d="M16 8A8 8 0 1 1 0 8a8 8 0 0 1 16 0M5.496 6.033h.825c.138 0 .248-.113.266-.25.09-.656.54-1.134 1.342-1.134.686 0 1.314.343 1.314 1.168 0 .635-.374.927-.965 1.371-.673.489-1.206 1.06-1.168 1.987l.003.217a.25.25 0 0 0 .25.246h.811a.25.25 0 0 0 .25-.25v-.105c0-.718.273-.927 1.01-1.486.609-.463 1.244-.977 1.244-2.056 0-1.511-1.276-2.241-2.673-2.241-1.267 0-2.655.59-2.75 2.286a.237.237 0 0 0 .241.247m2.325 6.443c.61 0 1.029-.394 1.029-.927 0-.552-.42-.94-1.029-.94-.584 0-1.009.388-1.009.94 0 .533.425.927 1.01.927z"/>
                    </svg>
                    Ajuda
                </a>
            </div>
            <hr class="small-line">
            <a class="link link-child-logout" href="#">
                <svg xmlns="http://www.w3.org/2000/svg" width="22" height="22" fill="currentColor" class="bi bi-door-open-fill" viewBox="0 0 16 16">
                    <path d="M1.5 15a.5.5 0 0 0 0 1h13a.5.5 0 0 0 0-1H13V2.5A1.5 1.5 0 0 0 11.5 1H11V.5a.5.5 0 0 0-.57-.495l-7 1A.5.5 0 0 0 3 1.5V15zM11 2h.5a.5.5 0 0 1 .5.5V15h-1zm-2.5 8c-.276 0-.5-.448-.5-1s.224-1 .5-1 .5.448.5 1-.224 1-.5 1"/>
                </svg>
                SAIR
            </a>
        </section>
        <section id="offCanva-mobile">
            <header class="headerOffCanva">
                <img src="../../../../public/img/Topo-logo.png" alt="Logo Offcanva">
                <div class="openMenuTopo" class="close-btn">
                    <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-x-lg" viewBox="0 0 16 16">
                        <path d="M2.146 2.854a.5.5 0 1 1 .708-.708L8 7.293l5.146-5.147a.5.5 0 0 1 .708.708L8.707 8l5.147 5.146a.5.5 0 0 1-.708.708L8 8.707l-5.146 5.147a.5.5 0 0 1-.708-.708L7.293 8z"/>
                    </svg>
                </div>
            </header>
            <div class="content-offcanva">
                <a href="#">
                    PROCURAR
                </a>
                <a href="#">
                    PROPOSTAS
                </a>
                <a href="#">
                    NOTIFICAÇÕES
                </a>
            </div>
        </section>
        <header id="site-topo">
            <div onclick="window.location.href = '../HomeUsuario/'" class="logo-box">
                <img class="logo" src="../../../../public/img/Topo-logo.png" alt="Logo Trabalho Amigo">
            </div>
            <nav class="navigation-box">
                <div class="links-box">
                    <a class="link-element" href="#">
                        <img src="../../../../public/img/Icon-document.png" alt="Icon Propostas">
                        Propostas
                    </a>
                    <a class="link-element" href="#">
                        <img src="../../../../public/img/Icon-search.png" alt="Icon Procurar">
                        Procurar
                    </a>
                    <a class="link-element" href="#">
                        <img src="../../../../public/img/Icon-notification.png" alt="Icon Notificações">
                        Notificações
                    </a>
                </div>
                <div class="userProfile-circle">
                    <img src="../../../../public/img/UserProfile-default.png" alt="Imagem de Usuário Padrão">
                    <img src="../../../../public/img/Topo-User-More.png" alt="Btn Mais informações">
                </div>
                <div class="openMenuTopo menu-mobile">
                    <svg xmlns="http://www.w3.org/2000/svg" width="32" height="32" fill="currentColor" class="bi bi-list" viewBox="0 0 16 16">
                        <path fill-rule="evenodd" d="M2.5 12a.5.5 0 0 1 .5-.5h10a.5.5 0 0 1 0 1H3a.5.5 0 0 1-.5-.5m0-4a.5.5 0 0 1 .5-.5h10a.5.5 0 0 1 0 1H3a.5.5 0 0 1-.5-.5m0-4a.5.5 0 0 1 .5-.5h10a.5.5 0 0 1 0 1H3a.5.5 0 0 1-.5-.5"/>
                    </svg>
                </div>
            </nav>
        </header>
        <!-- ================================================================================== -->
        <main id="content">
            
            <section id="bloco-busca">
                <div class="row">
                    <form id="formBusca" method="post" class="input-box">
                        <input type="text" name="busca" id="busca" placeholder="Pesquisa por serviços" class="input_element">
                        <button type="button" class="button_busca">Buscar</button>
                    </form>
                </div>
            </section>
    
            <section id="bloco-servico">
                <div id="filterContainer" class="filtros-box hidden">
                    <h3 class="titulo-box">FILTROS:</h3>
                    <div class="filtro-item">
                        <h1 class="titulo">Saúde</h1>
                    </div>
                </div>
                <div id="listServicos" class="servicos">
                    <?php
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

                        // Função para buscar os serviços e categorias
                        function getServicos($conexao) {
                            // Consulta SQL para buscar serviços e suas respectivas categorias
                            $sql = "
                                SELECT 
                                    s.id_servico, 
                                    s.titulo, 
                                    s.descricao, 
                                    s.preco, 
                                    s.aceita_oferta, 
                                    s.comunitario, 
                                    c.nome AS categoria_nome,
                                    s.data_Criacao
                                FROM 
                                    servicos s
                                INNER JOIN 
                                    categorias c ON s.id_categoria_fk = c.id_categoria
                                WHERE 
                                    s.ativo = 1
                                ORDER BY 
                                    s.data_Criacao DESC
                            ";
                            
                            $result = $conexao->query($sql);
                            
                            if ($result->num_rows > 0) {
                                return $result->fetch_all(MYSQLI_ASSOC);  // Retorna os resultados como um array associativo
                            } else {
                                return [];
                            }
                        }

                        // Renderizando a lista de serviços com um foreach
                        function renderServicos($servicos) {
                            if (empty($servicos)) {
                                echo "<p>Nenhum serviço encontrado.</p>";
                                return;
                            }
                            
                            echo '<ul class="listagem-servico">';
                            foreach ($servicos as $servico) {
                                echo '<li class="listagem-item">';
                                echo '<h2 class="listagem-titulo">' . htmlspecialchars($servico['titulo']) . '</h2>';
                                echo '<p class="listagem-descricao">Descrição: ' . htmlspecialchars($servico['descricao']) . '</p>';
                                echo '<p class="listagem-preco">Preço: R$ ' . number_format($servico['preco'], 2, ',', '.') . '</p>';
                                echo '<p class="listagem-categoria">Categoria: ' . htmlspecialchars($servico['categoria_nome']) . '</p>';
                                echo '<p class="listagem-data">Data de Criação: ' . date('d/m/Y H:i:s', strtotime($servico['data_Criacao'])) . '</p>';
                                echo '<p class="listagem-oferta">Aceita Oferta: ' . ($servico['aceita_oferta'] ? 'Sim' : 'Não') . '</p>';
                                echo '<p class="listagem-comunitario">Comunitário: ' . ($servico['comunitario'] ? 'Sim' : 'Não') . '</p>';
                                echo '<button class="btn-modal" onclick="openModal(' . $servico['id_servico'] . ', \'' . addslashes(htmlspecialchars($servico['titulo'])) . '\', \'' . addslashes(htmlspecialchars($servico['descricao'])) . '\', ' . $servico['preco'] . ')">Ver Mais</button>';
                                echo '</li>';
                            }
                            echo '</ul>';

                            echo '
                            <div id="modal" class="modal" style="display:none;">
                                <div class="modal-content">
                                    <span class="close" onclick="closeModal()">&times;</span>
                                    <div class="content-container">
                                        <h2 id="modal-titulo"></h2>
                                        <p id="modal-descricao"></p>
                                        <p id="modal-preco"></p>
                                    </div>
                                    <a id="contratar-btn" class="btn-contratar" href="#" onclick="contratarServico()">Contratar Serviço</a>
                                </div>
                            </div>';
                        };

                        // Processa a listagem dos serviços
                        function processListServicos() {
                            try {
                                $conexao = getDatabaseConnection();
                                $servicos = getServicos($conexao);
                                renderServicos($servicos);
                                $conexao->close();
                            } catch (Exception $e) {
                                echo "<p>Erro: " . $e->getMessage() . "</p>";
                            }
                        }

                        // Chamando a função para processar e renderizar a listagem
                        processListServicos();
                        ?>


                </div>
                <section class="button-loadingMore">
                    <button id="LoadingMore" class="LoadingMore" type="button">
                        <div class="isLoading hidden"></div>
                        <h2 class="titulo">Carregar mais</h2>
                    </button>
                </section>
            </section>
    
            <section id="bloco-chamada">
                <div class="item">
                    <a href="#">
                        <img src="../../../../public/img/Bloco-chamada-listagem-1.png" alt="Imagem de Chamada">
                    </a>
                </div>
                <div class="item">
                    <a href="#">
                        <img src="../../../../public/img/Bloco-chamada-listagem-2.png" alt="Imagem de Chamada">
                    </a>
                </div>
            </section>
    
        </main>
        <!-- =================================      RODAPÉ      =================================-->
        <footer id="site-rodape">
            <section class="topo-rodape">
                <img class="logo" src="../../../../public/img/Topo-logo.png" alt="Logo Rodapé">
                <nav class="navigation-social">
                    <img src="../../../../public/img/instagram.png" alt="Icone Instagram">
                    <img src="../../../../public/img/whatsapp.png" alt="Icone Whatsapp">
                    <img src="../../../../public/img/github.png" alt="Icone Github">
                </nav>
            </section>
            <hr class="bar-long">
            <div class="d-flex">
                <div class="item">
                    <h1 class="titulo">LINKS RÁPIDOS</h1>
                    <div class="list-itens">
                        <a class="itens-links" href="#">Como funciona</a>
                        <a class="itens-links" href="#">Termos de serviço</a>
                        <a class="itens-links" href="#">Contato</a>
                        <a class="itens-links" href="#">Política do trabalho amigo</a>
                        <a class="itens-links" href="#">Cookies</a>
                        <a class="itens-links" href="#">Configuração de cookies</a>
                    </div>
                </div>
                <div class="item">
                    <h1 class="titulo">AJUDA</h1>
                    <div class="list-itens">
                        <a class="itens-links" href="#">Centro de ajuda</a>
                        <a class="itens-links" href="#">Novidades</a>
                        <a class="itens-links" href="#">Documentação</a>
                        <a class="itens-links" href="#">Bugs</a>
                        <a class="itens-links" href="#">Faça um pergunta</a>
                    </div>
                </div>
                <div class="item"></div>
                <div class="item">
                    <h1 class="titulo">FALE CONOSCO</h1>
                    <div class="list-itens">
                        <a class="itens-links" href="#">+55 (43) 98487-3806</a>
                        <a class="itens-links" href="#">suport@trabalhoamigo.com.br</a>
                        <a class="itens-links" href="#">admin@trabalhoamigo.com.br</a>
                    </div>
                </div>
            </div>
            <hr class="bar-long">
            <div class="d-flex-footer">
                <h3 class="copy">Copyright 2023-2024 - Trabalhoamigo.com.br</h3>
            </div>
        </footer>
        </section>
    </body>
</html>