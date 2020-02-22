$(document).ready(() => {

    // Altera o conteúdo da página de forma assíncrona utilizando AJAX
    // Invocado pelo click do botão no menu lateral
    $('#documentacao').on('click', () => {
        $('#pagina').load('../documentacao.html');
    });

    $('#suporte').on('click', () => {
        $('#pagina').load('../suporte.html');
    });

    // Realiza a atualização e/ou alteração dos dados apresentados no Dashboard
    // com base nas Queries implementadas em app.php que retorna um objeto JSON
    // invocado com base na troca de valor do Select que representa a competência
    $('#competencia').on('change', e => {
        let competencia = $(e.target).val();

        // Chamada AJAX do jQuery
        // Substitui o HTML dos cards pelo retorno do objeto JSON de app.php
        $.ajax({
            type: 'GET',
            url: '../app/app.php',
            data: `competencia=${competencia}`,
            dataType: 'json',
            success: dados => {
                $('#numeroVendas').html(dados.numeroVendas);
                $('#totalVendas').html(dados.totalVendas);
                $('#clientesAtivos').html(dados.usuariosAtivos);
                $('#clientesInativos').html(dados.usuariosInativos);
                $('#totalReclamacoes').html(dados.totalReclamacoes);
                $('#totalElogios').html(dados.totalElogios);
                $('#totalSugestoes').html(dados.totalSugestoes);
                $('#totalDespesas').html(dados.totalDespesas);
            } ,
            error: erro => {console.log(erro)}
        });
    })

});