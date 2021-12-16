<?php $strUrlRetorno = SessaoSEI::getInstance()->assinarLink('controlador.php?acao=procedimento_controlar&acao_origem=principal&acao_retorno=principal'); ?>
<script type="text/javascript">
    "use strict";

    function inicializar() {
        var checkboxs = $('.infraCheckboxInput');
        $.each(checkboxs, function(chave, item){
            $(item).prop('checked', false);
        });
    } //fim


    function pesquisar() {
        document.getElementById('frmConsulta').submit();
    }
    
    function fechar() {
        window.location = '<?php echo $strUrlRetorno;?>'
    }

    function abrirGerarCobranca(url){
        var infraNroItens = document.getElementById('hdnInfraNroItens').value;
        var i, box;
        var arrSelected = [];
        for (i = 0; i < infraNroItens; i++) {
            box = document.getElementById('chkInfraItem' + i);
            if (box != null && box.checked) {
                arrSelected.push(box.value);
            }
        }


        if (arrSelected.length == 0) {
            alert('Nenhum registro selecionado.');
            return false;
        } else {
            parent.infraAbrirJanelaModal(url,500,150, true)
        }
    }

    function abrirInformarCobranca(url){
        var infraNroItens = document.getElementById('hdnInfraNroItens').value;
        var i, box;
        var arrSelected = [];
        for (i = 0; i < infraNroItens; i++) {
            box = document.getElementById('chkInfraItem' + i);
            if (box != null && box.checked) {
                arrSelected.push(box.value);
            }
        }


        if (arrSelected.length == 0) {
            alert('Nenhum registro selecionado.');
            return false;
        } else {
            infraAbrirJanela(url,"janelaResumo",500,230, 'location=0,status=1,resizable=1,scrollbars=1', true)
        }
    }

    function historicoCobranca(url){
        infraAbrirJanela(url,"janelaResumo",500,150, 'location=0,status=1,resizable=1,scrollbars=1', true)
    }

    function enterValidarDocumento(e) {
        if(e && e.keyCode == 13) {
            pesquisar();
            return false;
        }
    }

</script>