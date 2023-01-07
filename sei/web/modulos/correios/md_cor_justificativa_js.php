<script type="text/javascript" charset="iso-8859-1" src="/../../../infra_js/InfraUtil.js"></script>

<script type="text/javascript">

    function inicializar() {
        infraEfeitoTabelas();
    }

    <? if ($bolAcaoExcluir){ ?>
    function acaoExcluir(id, desc, link) {
        if (confirm("Confirma exclus�o Justificativas de Destinat�rios n�o Habilitados para Expedi��o \"" + desc + "\"?")) {
            document.getElementById('hdnInfraItemId').value = id;
            document.getElementById('frmMdCorJustificativa').action = link;
            document.getElementById('frmMdCorJustificativa').submit();
        }
    }
    <? } ?>

    function OnSubmitForm() {
        return validarCadastro();
    }

    function validarCadastro() {

        if (infraTrim(document.getElementById('txtJustificativa').value) == '') {
            alert('Informe o Nome.');
            document.getElementById('txtJustificativa').focus();
            return false;
        }

        return true;
    }

    <? if ($bolAcaoDesativar) { ?>

    function acaoDesativar(id, desc) {
        if (confirm("Confirma desativa��o de Justificativa \"" + desc + "\"?")) {
            document.getElementById('hdnInfraItemId').value = id;
            document.getElementById('frmMdCorJustificativa').action = '<?= $strLinkDesativar ?>';
            document.getElementById('frmMdCorJustificativa').submit();
        }
    }

    <? } ?>

    <? if ($bolAcaoReativar) { ?>

    function acaoReativar(id, desc) {

        if (confirm("Confirma reativa��o de Justificativa \"" + desc + "\"?")) {
            document.getElementById('hdnInfraItemId').value = id;
            document.getElementById('frmMdCorJustificativa').action = '<?= $strLinkReativar ?>';
            document.getElementById('frmMdCorJustificativa').submit();

        }
    }

    <? } ?>

</script>