// Funções de validação modular

/**
 * Valida se o nome não está vazio.
 * @param {string} nome - O nome a ser validado.
 * @return {boolean} - Retorna true se o nome for válido.
 */
function validarNome(nome) {
    return nome.length > 0;
}

/**
 * Valida se o email está no formato correto.
 * @param {string} email - O email a ser validado.
 * @return {boolean} - Retorna true se o email for válido.
 */
function validarEmail(email) {
    const regexEmail = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
    return regexEmail.test(email);
}

/**
 * Valida se o telefone está no formato correto (10 ou 11 dígitos).
 * @param {string} telefone - O telefone a ser validado.
 * @return {boolean} - Retorna true se o telefone for válido.
 */
function validarTelefone(telefone) {
    const regexTelefone = /^\d{10,11}$/; // Exemplo para telefones brasileiros
    return regexTelefone.test(telefone);
}

/**
 * Valida se a senha tem pelo menos 6 caracteres.
 * @param {string} senha - A senha a ser validada.
 * @return {boolean} - Retorna true se a senha for válida.
 */
function validarSenha(senha) {
    return senha.length >= 6;
}

/**
 * Valida se a senha e a confirmação de senha são iguais.
 * @param {string} senha - A senha original.
 * @param {string} repetirSenha - A senha de confirmação.
 * @return {boolean} - Retorna true se as senhas coincidirem.
 */
function validarRepetirSenha(senha, repetirSenha) {
    return senha === repetirSenha;
}

/**
 * Valida se o CEP está no formato correto (XXXXX-XXX).
 * @param {string} cep - O CEP a ser validado.
 * @return {boolean} - Retorna true se o CEP for válido.
 */
function validarCep(cep) {
    const regexCep = /^\d{5}-?\d{3}$/;
    return regexCep.test(cep);
}

/**
 * Valida se o número da residência não está vazio.
 * @param {string} numero - O número da residência a ser validado.
 * @return {boolean} - Retorna true se o número for válido.
 */
function validarNumero(numero) {
    return numero.length > 0;
}

/**
 * Valida se a caixa de seleção foi marcada.
 * @param {boolean} checkbox - O estado da caixa de seleção.
 * @return {boolean} - Retorna true se a caixa de seleção estiver marcada.
 */
function validarCheckbox(checkbox) {
    return checkbox === true;
}

// Função principal de validação

/**
 * Função que valida todos os dados do formulário.
 * @param {object} formData - Um objeto contendo os dados do formulário.
 * @return {Array} - Retorna um array de mensagens de erro, se houver.
 */
function validarDados(formData) {
    let erros = [];

    if (!validarNome(formData.primeiroNome)) erros.push("Primeiro nome inválido");
    if (!validarNome(formData.sobrenome)) erros.push("Sobrenome inválido");
    if (!validarEmail(formData.email)) erros.push("Email inválido");
    if (!validarTelefone(formData.telefone)) erros.push("Telefone inválido");
    if (!validarSenha(formData.senha)) erros.push("Senha deve ter pelo menos 6 caracteres");
    if (!validarRepetirSenha(formData.senha, formData.repetirSenha)) erros.push("As senhas não coincidem");
    if (!validarCep(formData.cep)) erros.push("CEP inválido");
    if (!validarNome(formData.rua)) erros.push("Rua inválida");
    if (!validarNome(formData.bairro)) erros.push("Bairro inválido");
    if (!validarNumero(formData.numero)) erros.push("Número inválido");
    if (!validarCheckbox(formData.aceitouTermos)) erros.push("Você deve aceitar os termos e condições");

    return erros;
}

$('#FormCriarUsuario').on('submit', function (e) {
    e.preventDefault(); // Previne o envio padrão do formulário

    let formData = {
        primeiroNome: $('.input.nome').eq(0).val().trim(), // Obtém o valor do primeiro nome
        sobrenome: $('.input.nome').eq(1).val().trim(),    // Obtém o valor do sobrenome
        email: $('.input.email').val().trim(),             // Obtém o valor do email
        telefone: $('.input.telefone').val().trim().replace(/\D/g, ''), // Remove a pontuação do telefone
        celular: $('.input.celular').val().trim().replace(/\D/g, ''), // Remove a pontuação do celular
        whatsapp: $('.input.whatsapp').val().trim().replace(/\D/g, ''), // Remove a pontuação do WhatsApp
        cpf: $('.input.cpf').val().trim().replace(/\D/g, ''), // Remove a pontuação do CPF
        senha: $('.input.senha').val().trim(),             // Obtém o valor da senha
        repetirSenha: $('.input.againsenha').val().trim(), // Obtém o valor da confirmação de senha
        cep: $('.input.cep').val().trim(),                 // Obtém o valor do CEP
        rua: $('.input.rua').val().trim(),                 // Obtém o valor da rua
        bairro: $('.bairro').val().trim(),                 // Obtém o valor do bairro
        numero: $('.input.numero').val().trim(),           // Obtém o valor do número da residência
        complemento: $('.complemento').val().trim(),       // Obtém o valor do complemento (se houver)
        aceitouTermos: $('#checkTerms input[type="checkbox"]').is(':checked') // Verifica se os termos foram aceitos
    };

    let erros = validarDados(formData); // Valida os dados do formulário

    if (erros.length > 0) {
        Swal.fire({
            icon: 'error',
            title: 'Erro de Validação',
            text: 'Verifique os campos e tente novamente.',
            footer: erros.join('<br>') // Mostra todos os erros em uma linha
        });
    } else {
        // Ativa animação de loading
        $(".SendForm").html(`<section class="loading-container"><div class='loading-form-animation'></div></section>`);

        $(".background-loading-50").removeClass('hidden');

        $.ajax({
            url: '../../../controllers/anunciante/CreateAccount.php',
            type: 'POST',
            data: formData, 
            success: function (response) {
                $(".background-loading-50").addClass('hidden');
                $(".SendForm").html("Cadastrar");

                if (response.success) {
                    Swal.fire({
                        icon: 'success',
                        title: 'Sucesso!',
                        text: 'Login efetuado com sucesso!'
                    });
                    
                    window.location.href = "../PaginaInicial/";
                } else {
                    Swal.fire({
                        icon: 'error',
                        title: 'Ocorreu um erro',
                        text: response.message
                    });
                }
            },
            error: function (xhr, status, error) {
                $(".background-loading-50").addClass('hidden');
                $(".SendForm").html("Cadastrar");

                Swal.fire({
                    icon: 'error',
                    title: 'Erro',
                    text: 'Ocorreu um erro. Tente novamente.'
                });
            }
        });
    }
});

// Consulta de CEP
$('.input.cep').on('blur', function () {
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
                    $('.input.rua').val(data.logradouro);
                    $('.input.bairro').val(data.bairro);
                    $('.input.cidade').val(data.localidade);
                    $('.input.uf').val(data.uf);
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
    } else {
        Swal.fire({
            icon: 'warning',
            title: 'CEP Inválido',
            text: 'O CEP deve ter 8 dígitos.'
        });
    }
});
``

/*------------------------------------------------------
*  Sistema de exibição de politicas de privacidade
* ----------------------------------------------------- */
document.getElementById('privacy-policy-btn').addEventListener('click', function() {
    Swal.fire({
        title: 'Política de Privacidade',
        html: `
            <div style="text-align: left;">
                <p><strong>1. Coleta de Informações:</strong> Coletamos informações pessoais fornecidas por você, como nome, e-mail, e dados de contato, bem como informações sobre o serviço que você compartilha na plataforma.</p><br>
                <p><strong>2. Uso das Informações:</strong> Utilizamos as informações para facilitar a comunicação entre usuários, melhorar a experiência na plataforma e garantir a segurança dos serviços compartilhados. Seus dados podem ser usados para envio de notificações relacionadas à plataforma.</p><br>
                <p><strong>3. Compartilhamento de Dados:</strong> Compartilhamos suas informações apenas com os usuários relevantes para a execução dos serviços oferecidos ou solicitados. Não vendemos suas informações a terceiros.</p><br>
                <p><strong>4. Segurança:</strong> Implementamos medidas de segurança para proteger suas informações, como criptografia e monitoramento de atividades suspeitas.</p><br>
                <p><strong>5. Seus Direitos:</strong> Você tem o direito de acessar, corrigir ou excluir suas informações pessoais a qualquer momento, além de poder solicitar a interrupção do uso dos seus dados.</p><br>
                <p><strong>6. Alterações na Política:</strong> Esta política de privacidade pode ser atualizada a qualquer momento. Recomendamos a leitura periódica desta página para manter-se informado.</p><br>
                <p><strong>Contato:</strong> Para dúvidas ou solicitações sobre a política de privacidade, entre em contato conosco pelo e-mail suporte@trabalhoamigo.com.</p>
            </div>
        `,
        icon: 'info',
        showCloseButton: true,
        showCancelButton: false,
        focusConfirm: true,
        confirmButtonText: 'Fechar!',
        width: '600px',
        padding: '3em',
    });
});

/*------------------------------------------------------
*  Sistema de exibição de Termos de uso
* ----------------------------------------------------- */
document.getElementById('termos-btn').addEventListener('click', function() {
    Swal.fire({
        title: 'Termos de Uso',
        html: `
            <div style="text-align: left;">
                <p><strong>1. Aceitação dos Termos:</strong> Ao utilizar nossa plataforma, você concorda em cumprir estes termos. Caso não concorde com qualquer parte dos termos, você não deve utilizar a plataforma.</p><br>
                <p><strong>2. Uso da Plataforma:</strong> Nossa plataforma é destinada ao compartilhamento de serviços entre comunidades. Você concorda em usar a plataforma de maneira responsável e respeitosa, sem violar leis ou direitos de terceiros.</p><br>
                <p><strong>3. Responsabilidade pelos Conteúdos:</strong> Você é responsável por todo conteúdo ou informação que compartilhar na plataforma. Isso inclui a veracidade e a legalidade das informações postadas.</p><br>
                <p><strong>4. Propriedade Intelectual:</strong> O conteúdo da plataforma, incluindo textos, gráficos e logos, é protegido por direitos autorais. Você não pode reproduzir ou utilizar qualquer parte do conteúdo sem nossa permissão expressa.</p><br>
                <p><strong>5. Modificações dos Termos:</strong> Podemos modificar estes termos a qualquer momento. Recomendamos que você revise os termos periodicamente para estar ciente de quaisquer alterações.</p><br>
                <p><strong>6. Suspensão de Conta:</strong> Reservamo-nos o direito de suspender ou encerrar a sua conta caso haja violação dos termos ou uso indevido da plataforma.</p><br>
                <p><strong>7. Contato:</strong> Para dúvidas ou esclarecimentos sobre os Termos de Uso, entre em contato conosco pelo e-mail suporte@trabalhoamigo.com.</p>
            </div>
        `,
        icon: 'info',
        showCloseButton: true,
        showCancelButton: false,
        focusConfirm: true,
        confirmButtonText: 'Fechar!',
        width: '600px',
        padding: '3em',
    });
});