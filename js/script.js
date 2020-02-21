$(document).ready(() => {

    $('#documentacao').on('click', () => {
        $('#pagina').load('../documentacao.html');
    })

    $('#suporte').on('click', () => {
        $('#pagina').load('../suporte.html');
    })

    $('#competencia').on('change', e => {
        let competencia = $(e.target).val();

        $.ajax({
            type: 'GET',
            url: '../app/app.php',
            data: `competencia=${competencia}`,
            dataType: 'json',
            success: dados => {
                $('#numeroVendas').html(dados.numeroVendas);
                $('#totalVendas').html(dados.totalVendas);
                console.log(dados.numeroVendas + ' ' + dados.totalVendas)
            } ,
            error: erro => {console.log(erro)}
        });
    })

})