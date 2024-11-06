<script type="text/javascript">

    function inicializar() {
        if ('<?= $_GET['acao'] ?>' == 'md_cor_contrato_selecionar') {
            infraReceberSelecao();
            document.getElementById('btnFecharSelecao').focus();
        } else {
            document.getElementById('btnFechar').focus();
        }

        infraEfeitoTabelas();

        if( $('#divInfraAreaTabela').find('table').length == 0 ){
            $('#divInfraAreaPaginacaoSuperior').hide();
            $('#divInfraAreaTabela').addClass('mt-2');
            $('#divInfraAreaTabela > label').addClass('infraLabelOpcional'); 
        }else{
            if( $('#divInfraAreaPaginacaoSuperior').find('select').length == 0 ){
                $('#divInfraAreaPaginacaoSuperior').hide();
            }
        }
    }

    function acaoDesativar(id, desc) {
        if (confirm("Confirma desativação do contrato \"" + desc + "\"?")) {
            document.getElementById('hdnInfraItemId').value = id;
            document.getElementById('frmMdCorContratoLista').action = '<?= $strLinkDesativar ?>';
            document.getElementById('frmMdCorContratoLista').submit();
        }
    }

    function acaoDesativacaoMultipla() {
        if (document.getElementById('hdnInfraItensSelecionados').value == '') {
            alert('Nenhum contrato selecionado.');
            return;
        }
        if (confirm("Confirma desativação dos contratos selecionados?")) {
            document.getElementById('hdnInfraItemId').value = '';
            document.getElementById('frmMdCorContratoLista').action = '<?= $strLinkDesativar ?>';
            document.getElementById('frmMdCorContratoLista').submit();
        }
    }

    function acaoReativar(id, desc) {
        if (confirm("Confirma reativação do contrato \"" + desc + "\"?")) {
            document.getElementById('hdnInfraItemId').value = id;
            document.getElementById('frmMdCorContratoLista').action = '<?= $strLinkReativar ?>';
            document.getElementById('frmMdCorContratoLista').submit();
        }
    }

    function acaoReativacaoMultipla() {
        if (document.getElementById('hdnInfraItensSelecionados').value == '') {
            alert('Nenhum contrato selecionado.');
            return;
        }
        if (confirm("Confirma reativação dos contratos selecionados?")) {
            document.getElementById('hdnInfraItemId').value = '';
            document.getElementById('frmMdCorContratoLista').action = '<?= $strLinkReativar ?>';
            document.getElementById('frmMdCorContratoLista').submit();
        }
    }

    function acaoExcluir(id, desc) {
        if (confirm("Confirma exclusão do contrato \"" + desc + "\"?")) {
            document.getElementById('hdnInfraItemId').value = id;
            document.getElementById('frmMdCorContratoLista').action = '<?= $strLinkExcluir ?>';
            document.getElementById('frmMdCorContratoLista').submit();
        }
    }

    function acaoExclusaoMultipla() {
        if (document.getElementById('hdnInfraItensSelecionados').value == '') {
            alert('Nenhum contrato selecionado.');
            return;
        }
        if (confirm("Confirma exclusão dos contratos selecionados?")) {
            document.getElementById('hdnInfraItemId').value = '';
            document.getElementById('frmMdCorContratoLista').action = '<?= $strLinkExcluir ?>';
            document.getElementById('frmMdCorContratoLista').submit();
        }
    }

    function pesquisar() {
        document.getElementById('frmMdCorContratoLista').action = '<?= $strLinkPesquisar ?>';
        document.getElementById('frmMdCorContratoLista').submit();
    }
</script>