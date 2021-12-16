<script type="text/javascript">
    function inicializar() {
        infraEfeitoTabelas();
    }

    function pesquisar() {
        $("#frmMdCorUnidadeExpCadastro").submit();
    }

    function acaoDesativar(idCampo) {
        if (confirm("Confirma a desativação do Mapeamento de Unidades Solicitantes?")) {
            document.getElementById('hdnInfraItemId').value = idCampo;
            document.getElementById('frmMdCorUnidadeExpCadastro').action = '<?=$strLinkDesativar?>';
            document.getElementById('frmMdCorUnidadeExpCadastro').submit();
        }
    }

    function acaoReativar(idCampo) {
        if (confirm("Confirma a reativação do Mapeamento de Unidades Solicitantes?")) {
            document.getElementById('hdnInfraItemId').value = idCampo;
            document.getElementById('frmMdCorUnidadeExpCadastro').action = '<?=$strLinkReativar?>';
            document.getElementById('frmMdCorUnidadeExpCadastro').submit();
        }
    }

    function acaoExcluir(idCampo) {
        if (confirm("Confirma a exclusão do Mapeamento de Unidades Solicitantes?")) {
            document.getElementById('hdnInfraItemId').value = idCampo;
            document.getElementById('frmMdCorUnidadeExpCadastro').action = '<?=$strLinkExcluir?>';
            document.getElementById('frmMdCorUnidadeExpCadastro').submit();
        }
    }
</script>
