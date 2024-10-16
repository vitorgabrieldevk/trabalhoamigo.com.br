$(document).ready(function() {
    function validarFormulario() {
        var valor = $('#valor').val();
        var descricao = $('#descricao').val();
        var tempo = $('#tempo').val();
        var data_servico = $('#data_servico').val();
        var id = $('#idservico').val();

        if (descricao.length < 10) {
            swal("Atenção!", "A descrição deve conter no mínimo 10 caracteres.", "warning");
            return false;
        }
        if (tempo <= 0) {
            swal("Atenção!", "O tempo estimado deve ser maior que zero.", "warning");
            return false;
        }
        var dataRegex = /^\d{4}-\d{2}-\d{2}$/;
        if (!data_servico || !dataRegex.test(data_servico)) {
            swal("Atenção!", "Por favor, insira uma data válida no formato AAAA-MM-DD.", "warning");
            return false;
        }
        
        return true;
    }

    $('#FormProposta').on('submit', function(event) {
        event.preventDefault();

        $(".background-loading-50").removeClass('hidden');

        if (validarFormulario()) {
            $.ajax({
                url: '../../../controllers/contratante/EnvioProposta.php',
                type: 'POST',
                data: {
                    valor: $('#valor').val(),
                    descricao: $('#descricao').val(),
                    tempo: $('#tempo').val(),
                    data_servico: $('#data_servico').val(),
                    id_servico: $('#idservico').val() // Altere para id_servico para corresponder ao PHP
                },
                success: function(response) {
                    $(".background-loading-50").addClass('hidden');
                    if (response === 'true') {
                        swal("Sucesso!", "Proposta enviada com sucesso!", "success");
                        location.href = '../ListagemServico/'
                    };
                },
                error: function(xhr, status, error) {
                    $(".background-loading-50").addClass('hidden');
                    $('#mensagem').html('<p>Ocorreu um erro: ' + error + '</p>');
                    swal("Erro!", "Ocorreu um erro ao enviar a proposta.", "error");
                }
            });
        } else {
            $(".background-loading-50").addClass('hidden');  
        };
    });
});
