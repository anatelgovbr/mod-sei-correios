<script type="text/javascript" charset="iso-8859-1" src="/../../../infra_js/InfraUtil.js"></script>

<script type="text/javascript">

    function inicializar() {
        infraEfeitoTabelas();

        if( $('#divInfraAreaTabela').find('table').length == 0 ){
            $('#divInfraAreaPaginacaoSuperior').hide();
            $('#divInfraAreaTabela').parent().parent().addClass('mt-2');
            $('#divInfraAreaTabela > label').addClass('infraLabelOpcional'); 
        }else{
            if( $('#divInfraAreaPaginacaoSuperior').find('select').length == 0 ){
                $('#divInfraAreaPaginacaoSuperior').hide();
            }
        }
    }

    <? if ($bolAcaoExcluir){ ?>
    function acaoExcluir(id, desc, link) {
        if (confirm("Confirma exclus�o de Destinat�rios n�o Habilitados para Expedi��o \"" + desc + "\"?")) {
            document.getElementById('hdnInfraItemId').value = id;
            document.getElementById('frmMdCorContatoJustificativa').action = link;
            document.getElementById('frmMdCorContatoJustificativa').submit();
        }
    }
    <? } ?>

    function OnSubmitForm() {

        if (infraTrim($('#hdnTblDestinatarios').val()) == '') {
            alert('Informe ao menos um Destinat�rio.');
            return false;
        }

        <? if ($formAlterar){ ?>
        if (infraTrim($('#selContatoJustificativa').val()) == 'null') {
            alert('Justificativa n�o informada.');
            document.getElementById('selContatoJustificativa').focus();
            return false;
        }
        <? } ?>

        return true;
    }

</script>