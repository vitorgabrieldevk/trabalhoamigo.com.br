<?php
    /*----------------------------------------
    | Inicia o buffer de saída para controlar quando os dados serão enviados ao navegador
    |-----------------------------------------*/
    ob_start();

    /*----------------------------------------
    | Verifica se a sessão ainda não foi iniciada, e caso não tenha sido, inicia uma nova sessão
    |-----------------------------------------*/
    if (session_status() === PHP_SESSION_NONE) { 
        session_start();
    }

    /*----------------------------------------
    | Define as URLs de redirecionamento para diferentes tipos de usuários
    |-----------------------------------------*/
    $REDIRECT_LOGIN               = 'src/views/contratante/EntrarConta/'; 
    $REDIRECT_CONTRATANTE         = 'src/views/contratante/PaginaInicial';
    $REDIRECT_ANUNCIANTE          = 'src/views/anunciante/PaginaInicial'; 

    /*----------------------------------------
    | Verifica se o usuário está logado
    |-----------------------------------------*/
    if (isset($_SESSION['logado'])) {
        /*----------------------------------------
        | Verifica o tipo de usuário e redireciona conforme apropriado
        |-----------------------------------------*/
        if ($_SESSION['tipo_usuario'] == 'contratante') {
            // Redireciona para a página inicial do contratante
            header('location: ' . $REDIRECT_CONTRATANTE);
            exit();
        } else {
            // Redireciona para a página inicial do anunciante
            header('location: ' . $REDIRECT_ANUNCIANTE);
            exit();
        }
    } else {
        /*----------------------------------------
        | Se o usuário não estiver logado, redireciona para a página de login
        |-----------------------------------------*/
        header('location: ' . $REDIRECT_LOGIN);
        exit(); 
    }

    /*----------------------------------------
    | Envia todo o conteúdo armazenado no buffer de saída e finaliza o buffering
    |-----------------------------------------*/
    ob_end_flush(); 

?>

<!-- ------------------------------------------------------------------------- 
| Representação visual do carregamento do servidor
| ------------------------------------------------------------------------ -->
<!DOCTYPE html><html lang="en"><head><title>Redirecionando...</title><meta charset="UTF-8"><meta http-equiv="X-UA-Compatible" content="IE=edge"><meta name="viewport" content="width=device-width, initial-scale=1.0"><link rel="stylesheet" href="./app.css"><script src="./app.js"></script><style>body,html{margin:0;padding:0;box-sizing:border-box;display:flex;justify-content:center;align-items:center;height:100vh;background:#f0f0f0}.loader{width:108px;height:60px;color:#269af2;--style-current:radial-gradient(farthest-side,currentColor 96%,#0000);background:var(--style-current) 100% 100% /30% 60%,var(--style-current) 70% 0 /50% 100%,var(--style-current) 0 100% /36% 68%,var(--style-current) 27% 18% /26% 40%,linear-gradient(currentColor 0 0) bottom/67% 58%;background-repeat:no-repeat;position:relative}.loader:after{content:"";position:absolute;inset:0;background:inherit;opacity:.4;animation:AFTER_ANIMATION 1s infinite}@keyframes AFTER_ANIMATION{to{transform:scale(1.8);opacity:0}}section{display:flex;flex-direction:column;align-items:center}.descricao{font-weight:500;color:#3b3939;margin:40px 0 0 0}.subDescricao{font-weight:600;font-size:16px;color:#686868}</style></head><body><section><div class="loader"></div><h3 class="descricao">ACESSANDO O SERVIDOR</h3><p class="subDescricao">AGUARDE...</p></section></body></html>