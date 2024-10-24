<!DOCTYPE html>
<html lang="pt-br">
<head>

    <!-- Título da Página - SEO -->
    <title>Listagem de Serviços | Trabalho Amigo</title>
    <!-- Descrição da Página - SEO -->
    <meta name="description" content="Explore nossa listagem de serviços disponíveis e encontre freelancers qualificados para atender suas necessidades. Comece a contratar hoje mesmo!" />

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
    <link rel="stylesheet" href="../../../../public/css/contrante/ListagemServico.css">

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

        <?php include '../layouts/Header.php'; ?>
        
        <!-- ================================================================================== -->
        <main id="content">
            <section id="bloco-servico">
                <div id="filterContainer" class="filtros-box hidden">
                    <h3 class="titulo-box">FILTROS:</h3>
                    <div class="filtro-item">
                        <h1 class="titulo">Saúde</h1>
                    </div>
                </div>
                <div id="listServicos" class="servicos">
                    <?php
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

                        // Função para criar a conexão com o banco de dados
                        function getDatabaseConnection() {
                            $conexao = new mysqli(DB_SERVER, DB_USERNAME, DB_PASSWORD, DB_NAME);
                            
                            if ($conexao->connect_error) {
                                throw new Exception('Falha na conexão com o banco de dados: ' . $conexao->connect_error);
                            }
                            
                            return $conexao;
                        }

                        // Função para buscar os serviços e categorias com paginação
                        function getServicos($conexao, $limit, $offset) {
                            // Consulta SQL para buscar serviços e suas respectivas categorias com paginação
                            $sql = "
                                SELECT 
                                    s.id_servico, 
                                    s.titulo, 
                                    s.descricao, 
                                    s.preco, 
                                    s.aceita_oferta, 
                                    s.comunitario, 
                                    c.nome AS categoria_nome,
                                    s.data_Criacao,
                                    s.imagem,
                                    u.id_usuario
                                FROM 
                                    servicos s
                                INNER JOIN 
                                    categorias c ON s.id_categoria_fk = c.id_categoria
                                INNER JOIN 
                                    usuarios u ON s.id_usuario_fk = u.id_usuario
                                WHERE 
                                    s.ativo = 1 
                                    AND u.ativo = 1
                                ORDER BY 
                                    s.data_Criacao DESC
                                LIMIT ? OFFSET ?
                            ";

                            // Preparar a consulta
                            $stmt = $conexao->prepare($sql);

                            // Verificar se a preparação falhou
                            if ($stmt === false) {
                                die('Erro na preparação da consulta: ' . $conexao->error);
                            }

                            // Associar os parâmetros
                            $stmt->bind_param('ii', $limit, $offset);

                            // Executar a consulta
                            if (!$stmt->execute()) {
                                die('Erro na execução da consulta: ' . $stmt->error);
                            }

                            // Obter os resultados
                            $result = $stmt->get_result();

                            // Retornar os dados ou um array vazio se não houver resultados
                            if ($result) {
                                return $result->fetch_all(MYSQLI_ASSOC);
                            } else {
                                return [];
                            }
                        }

                        // Função para contar o total de serviços com usuários ativados
                        function getTotalServicos($conexao) {
                            $sql = "
                                SELECT COUNT(*) AS total 
                                FROM servicos s
                                INNER JOIN usuarios u ON s.id_usuario_fk = u.id_usuario
                                WHERE s.ativo = 1
                                AND u.ativo = 1
                            ";
                            
                            // Executar a consulta
                            $result = $conexao->query($sql);

                            // Verificar se a consulta retornou resultado
                            if ($result) {
                                $row = $result->fetch_assoc();
                                return $row['total'];
                            } else {
                                return 0; // Retorna 0 se não houver resultados ou ocorrer erro
                            }
                        }

                        // Função para renderizar os serviços
                        function renderServicos($servicos) {
                            if (empty($servicos)) {
                                echo "<p>Nenhum serviço encontrado.</p>";
                                return;
                            }

                            echo '<ul class="listagem-servico">';
                            foreach ($servicos as $servico) {
                                echo '<li class="listagem-item">';
                                echo '<div class="item-conteudo">';
                                if (!empty($servico['imagem']) && $servico['imagem'] !== 'null') {
                                    echo '<img class="listagem-imagem" src="../../../../public/uploads/servicos/' . htmlspecialchars($servico['imagem']) . '" alt="' . htmlspecialchars($servico['titulo']) . '" />';
                                } else {
                                    echo '<img class="listagem-imagem" src="../../../../public/uploads/servicos/default-image.png" alt="Imagem não disponível" />'; // imagem padrão caso não haja uma imagem
                                }
                                echo '<h2 class="listagem-titulo">' . htmlspecialchars($servico['titulo']) . '</h2>';
                                echo '<p class="listagem-descricao">Descrição: ' . htmlspecialchars($servico['descricao']) . '</p>';
                                echo '<p class="listagem-preco">' . ($servico['preco'] == 0 ? 'Comunitário' : 'Preço: R$ ' . number_format($servico['preco'], 2, ',', '.')) . '</p>';
                                echo '<p class="listagem-categoria">Categoria: ' . htmlspecialchars($servico['categoria_nome']) . '</p>';
                                echo '<p class="listagem-data">Data de Criação: ' . date('d/m/Y H:i:s', strtotime($servico['data_Criacao'])) . '</p>';
                                echo '<p class="listagem-oferta">Aceita Oferta: ' . ($servico['aceita_oferta'] ? 'Sim' : 'Não') . '</p>';
                                echo '<p class="listagem-comunitario">Comunitário: ' . ($servico['comunitario'] ? 'Sim' : 'Não') . '</p>';
                                echo '<button class="btn-modal" onclick="openModal(' . $servico['id_servico'] . ', \'' . addslashes(htmlspecialchars($servico['titulo'])) . '\', \'' . addslashes(htmlspecialchars($servico['descricao'])) . '\', ' . $servico['preco'] . ')">Fazer proposta</button>';
                                echo '</div>';
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
                                        <a id="contratar-btn" class="btn-contratar" href="#" onclick="contratarServico()">Deseja contratar esse serviço?</a>
                                    </div>
                                </div>
                            ';
                        }

                        // Função para renderizar a paginação
                        function renderPagination($currentPage, $totalPages) {
                            echo '<div class="pagination">';
                            
                            // Botão "anterior"
                            if ($currentPage > 1) {
                                echo '<a href="?page=' . ($currentPage - 1) . '">&laquo; Anterior</a>';
                            }

                            // Números de página
                            for ($i = 1; $i <= $totalPages; $i++) {
                                if ($i == $currentPage) {
                                    echo '<a href="?page=' . $i . '" class="active">' . $i . '</a>';
                                } else {
                                    echo '<a href="?page=' . $i . '">' . $i . '</a>';
                                }
                            }

                            // Botão "próxima"
                            if ($currentPage < $totalPages) {
                                echo '<a href="?page=' . ($currentPage + 1) . '">Próxima &raquo;</a>';
                            }
                            
                            echo '</div>';
                        }

                        // Processa a listagem de serviços com paginação
                        function processListServicos() {
                            try {
                                $conexao = getDatabaseConnection();

                                // Parâmetros de paginação
                                $limit = 6; // Serviços por página
                                $page = isset($_GET['page']) ? (int)$_GET['page'] : 1;  // Página atual, padrão 1
                                $offset = ($page - 1) * $limit;  // Cálculo de offset

                                // Obter total de serviços e calcular o número total de páginas
                                $totalServicos = getTotalServicos($conexao);
                                $totalPages = ceil($totalServicos / $limit);

                                // Buscar os serviços da página atual
                                $servicos = getServicos($conexao, $limit, $offset);

                                // Renderizar os serviços e a paginação
                                renderServicos($servicos);
                                renderPagination($page, $totalPages);

                                $conexao->close();
                            } catch (Exception $e) {
                                echo "<p>Erro: " . $e->getMessage() . "</p>";
                            }
                        }

                        // Processar a listagem de serviços
                        processListServicos();
                    ?>
                </div>
            </section>
        </main>
        <!-- ================================================================================== -->
    </body>
</html>
