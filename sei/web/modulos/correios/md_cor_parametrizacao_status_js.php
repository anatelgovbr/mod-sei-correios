<script type="text/javascript">
    <!--  LISTAR  -->

    function inicializar() {

        if ('<?= $_GET['acao'] ?>' == 'md_cor_parametrizacao_status_cadastrar') {
            document.getElementById('txtCodigoStatusSro').focus();
        } else if ('<?= $_GET['acao'] ?>' == 'md_cor_parametrizacao_status_consultar') {
            infraDesabilitarCamposAreaDados();
        } else if ('<?= $_GET['acao'] ?>' == 'md_cor_parametrizacao_status_alterar') {
            desabilitarCampos();
        } else {
            if(document.getElementById('btnCancelar')) {
                document.getElementById('btnCancelar').focus();
            }
        }

        infraEfeitoTabelas();
        //tabelaDinamicaEventos();
    }

    <? if ($bolAcaoDesativar) { ?>

    function acaoDesativar(id, desc) {
        if (confirm("Confirma desativação do Status \"" + desc + "\"?")) {
            document.getElementById('hdnInfraItemId').value = id;
            document.getElementById('frmMdCorParametrizacaoLista').action = '<?= $strLinkDesativar ?>';
            document.getElementById('frmMdCorParametrizacaoLista').submit();
        }
    }

    <? } ?>

    <? if ($bolAcaoReativar) { ?>

    function acaoReativar(id, desc, situacaoRastreioModulo) {

        if (situacaoRastreioModulo == "") {
            alert('Favor acessar a funcionalidade alterar e preencher o campo Situação no Rastreio do Módulo');
        } else {

            if (confirm("Confirma reativação do Status \"" + desc + "\"?")) {
                document.getElementById('hdnInfraItemId').value = id;
                document.getElementById('frmMdCorParametrizacaoLista').action = '<?= $strLinkReativar ?>';
                document.getElementById('frmMdCorParametrizacaoLista').submit();
            }
        }

    }

    <? } ?>

    function pesquisar() {
        document.getElementById('frmMdCorParametrizacaoLista').action = '<?= $strLinkPesquisar ?>';
        document.getElementById('frmMdCorParametrizacaoLista').submit();
    }


    <!-- CADASTRAR, ALTERAR, CONSULTAR  -->

    function validarCadastro() {

        if (infraTrim(document.getElementById('txtCodigoStatusSro').value) == '') {
            alert('Informe o Código do Status no SRO.');
            document.getElementById('txtCodigoStatusSro').focus();
            return false;
        }

        if (infraTrim(document.getElementById('txtNovoTipo').value) == '') {
            alert('Informe o Tipo.');
            document.getElementById('txtNovoTipo').focus();
            return false;
        }

        if (!infraSelectSelecionado('selSituacaoRastreio')) {
            alert('Selecione a Situação no Rastreio do Módulo.');
            document.getElementById('selSituacaoRastreio').focus();
            return false;
        }

        <!--if (infraTrim(document.getElementById('txtDescricaoSRO').value) == '') {-->
        <!--alert('Informe o Descrição no SRO.');-->
        <!--document.getElementById('txtDescricaoSRO').focus();-->
        <!--return false;-->
        <!--}-->

        if (infraTrim(document.getElementById('txtDescricaoRastreioObjeto').value) == '') {
            alert('Informe o Descrição no Rastreio do Objeto.');
            document.getElementById('txtDescricaoRastreioObjeto').focus();
            return false;
        }

        return true;
    }

    function OnSubmitForm() {
        return validarCadastro();
    }

    function desabilitarCampos() {
        document.getElementById('txtCodigoStatusSro').readOnly = true;
        document.getElementById('txtNovoTipo').readOnly = true;
        document.getElementById('txtDescricaoSRO').readOnly = true;
    }

</script>