<script type="text/javascript">
    function inicializar() {
        if ('<?=$_GET['acao']?>' == 'md_cor_map_unid_servico_selecionar') {
            infraReceberSelecao();
            document.getElementById('btnFecharSelecao').focus();
        } else {
            document.getElementById('btnFechar').focus();
        }
        infraEfeitoTabelas();
        buscaLinhas();
    }

    function buscaLinhas(){
        let linhas = document.querySelectorAll('.infraTrVermelha');
        [linhas].map( row => $( row ).removeClass('infraTrAcessada') );
    }

    function enviarFormPesquisa() {
        //document.getElementById('frmMdCorMapUnidServicoLista').action='<?=$strLinkDesativar?>';
        document.getElementById('frmMdCorMapUnidServicoLista').submit();
    }

    <? if ($bolAcaoDesativar){ ?>
    function acaoDesativar(id, desc) {
        if (confirm("Confirma desativação do Mapeamentos de Unidades Solicitantes e Serviços Postais \"" + desc + "\"?")) {
            document.getElementById('hdnInfraItemId').value = id;
            document.getElementById('frmMdCorMapUnidServicoLista').action = '<?=$strLinkDesativar?>';
            document.getElementById('frmMdCorMapUnidServicoLista').submit();
        }
    }

    function acaoDesativacaoMultipla() {
        if (document.getElementById('hdnInfraItensSelecionados').value == '') {
            alert('Nenhuma  selecionada.');
            return;
        }
        if (confirm("Confirma desativação dos Mapeamentos de Unidades Solicitantes e Serviços Postais selecionados?")) {
            document.getElementById('hdnInfraItemId').value = '';
            document.getElementById('frmMdCorMapUnidServicoLista').action = '<?=$strLinkDesativar?>';
            document.getElementById('frmMdCorMapUnidServicoLista').submit();
        }
    }
    <? } ?>

    <? if ($bolAcaoReativar){ ?>
    function acaoReativar(id, desc) {
        if (confirm("Confirma reativação do Mapeamento de Unidades Solicitantes e Serviços Postais \"" + desc + "\"?")) {
            document.getElementById('hdnInfraItemId').value = id;
            document.getElementById('frmMdCorMapUnidServicoLista').action = '<?=$strLinkReativar?>';
            document.getElementById('frmMdCorMapUnidServicoLista').submit();
        }
    }

    function acaoReativacaoMultipla() {
        if (document.getElementById('hdnInfraItensSelecionados').value == '') {
            alert('Nenhum Mapeamento de Unidades Solicitantes e Serviços Postais selecionado.');
            return;
        }
        if (confirm("Confirma reativação dos Mapeamentos de Unidades Solicitantes e Serviços Postais selecionados?")) {
            document.getElementById('hdnInfraItemId').value = '';
            document.getElementById('frmMdCorMapUnidServicoLista').action = '<?=$strLinkReativar?>';
            document.getElementById('frmMdCorMapUnidServicoLista').submit();
        }
    }
    <? } ?>

    <? if ($bolAcaoExcluir){ ?>
    function acaoExcluir(id, desc) {
        if (confirm("Confirma exclusão de  \"" + desc + "\"?")) {
            document.getElementById('hdnInfraItemId').value = id;
            document.getElementById('frmMdCorMapUnidServicoLista').action = '<?=$strLinkExcluir?>';
            document.getElementById('frmMdCorMapUnidServicoLista').submit();
        }
    }

    function acaoExclusaoMultipla() {
        if (document.getElementById('hdnInfraItensSelecionados').value == '') {
            alert('Nenhuma  selecionada.');
            return;
        }
        if (confirm("Confirma exclusão das selecionadas?")) {
            document.getElementById('hdnInfraItemId').value = '';
            document.getElementById('frmMdCorMapUnidServicoLista').action = '<?=$strLinkExcluir?>';
            document.getElementById('frmMdCorMapUnidServicoLista').submit();
        }
    }
    <? } ?>
</script>