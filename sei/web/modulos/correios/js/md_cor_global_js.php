<script type="text/javascript">
    
function addEventoEnter() {
    document.addEventListener("keypress", function (evt) {
        var key_code = evt.keyCode ? evt.keyCode :
            evt.charCode ? evt.charCode :
                evt.which ? evt.which : void 0;

        if (key_code == 13) {
            pesquisar();
        }
    });
}

function fechar() {
    document.location = '<?= $strUrlFechar ?>';
}

function exibirBotao() {
    var div = document.getElementById('divInfraAvisoFundo');

    if (div != null && div.style.visibility == 'visible') {

        var botaoCancelar = document.getElementById('btnInfraAvisoCancelar');

        if (botaoCancelar != null) {
            botaoCancelar.style.display = 'block';
        }
    }
}

function exibirBotao() {
    var div = document.getElementById('divInfraAvisoFundo');

    if (div != null && div.style.visibility == 'visible') {

        var botaoCancelar = document.getElementById('btnInfraAvisoCancelar');

        if (botaoCancelar != null) {
            botaoCancelar.style.display = 'block';
        }
    }
}

function exibirAvisoEditor() {

    var divFundo = document.getElementById('divInfraAvisoFundo');

    if (divFundo == null) {
        divFundo = infraAviso(false, 'Processando...');
    } else {
        document.getElementById('btnInfraAvisoCancelar').style.display = 'none';
        document.getElementById('imgInfraAviso').src = '/infra_css/imagens/aguarde.gif';
    }

    if (INFRA_IE == 0 || INFRA_IE >= 7) {
        divFundo.style.position = 'fixed';
    }

    var divAviso = document.getElementById('divInfraAviso');

    divAviso.style.top = Math.floor(infraClientHeight() / 3) + 'px';
    divAviso.style.left = Math.floor((infraClientWidth() - 200) / 2) + 'px';
    divAviso.style.width = '200px';
    divAviso.style.border = '1px solid black';

    divFundo.style.width = screen.width * 2 + 'px';
    divFundo.style.height = screen.height * 2 + 'px';
    divFundo.style.visibility = 'visible';

}

function verificaAr(campo) {
    var str = campo.options[campo.selectedIndex].value;
    var name = campo.getAttribute("name");
    var indice = name.substr(7);
    var arrValue = str.split('|');
    var sinAr = arrValue[1];

    if (sinAr == 'N') {
        var arrCkAr = document.getElementsByName('ar' + indice);
        for (i in arrCkAr) {
            if (!arrCkAr.hasOwnProperty(i)) continue;
            arrCkAr[i].removeAttribute('checked');
            arrCkAr[i].setAttribute('disabled', true)
        }
    } else {
        var arrCkAr = document.getElementsByName('ar' + indice);
        var qtdElemento = arrCkAr.length;
        for (i in arrCkAr) {
            if (!arrCkAr.hasOwnProperty(i)) continue;
            arrCkAr[i].removeAttribute('disabled')
        }

        arrCkAr[qtdElemento - 1].setAttribute('checked', true);
    }

}

function lerCelula(celula) {
    var ret = null;
    var div = celula.getElementsByTagName('div');
    if (div.length == 0) {
        ret = celula.innerHTML;
    } else {
        ret = div[0].innerHTML;
    }
    return ret.infraReplaceAll('<br>', '<br />');
}

function acaoConsultarExpedicao(url) {
    parent.infraAbrirJanelaModal(url, 900, 600);
}

function acaoDetalharRastreamento(url) {
    parent.infraAbrirJanelaModal(url, 800, 500);
}

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