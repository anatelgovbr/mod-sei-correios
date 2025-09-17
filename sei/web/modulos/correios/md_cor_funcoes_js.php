<script type="text/javascript">
    function exibirAlert(msg,elem,tipoMsg='danger'){
        if ( elem == null || elem == undefined ) elem = 'sem-valor';
        let divMsg = '<div id="divInfraMsg0" class="alert alert-'+ tipoMsg +' alert-dismissible fade show" style="font-size:.875rem; top:0.25rem; margin-bottom: 14px !important; width:100%; margin:0 auto;" role="alert">'
                        + msg +
                        '<button type="button" class="close media h-100" data-bs-dismiss="alert" aria-label="Fechar Mensagem" aria-labelledby="divInfraMsg0" onclick="execFocus(\''+ elem +'\')">'+
                            '<span aria-hidden="true" class="align-self-center"><b>X</b></span>'+
                        '</button>'+
                    '</div>';

        $('#divInfraBarraComandosSuperior').after( divMsg );

        scrollTela('divInfraBarraLocalizacao');
    }

    function execFocus(elemento){
        if ( elemento.indexOf('grid') == 0 ){
            let arrTxt = elemento. split('-');
            arrTxt.shift();
            elemento = arrTxt.join('-');
            $('#' + elemento).click();
        } else if ( elemento != 'sem-valor' ){
            document.getElementById( elemento ).focus();
        }
    }

    function scrollTela(idEle , top = 80){
        // scroll barra de rolagem automatica
        var nivel = document.getElementById( idEle ).offsetTop + top;
        divInfraMoverTopo = document.getElementById("divInfraAreaTelaD");
        $( divInfraMoverTopo ).animate( { scrollTop: nivel } , 600 );
    }
</script>