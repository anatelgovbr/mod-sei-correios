<script type="text/javascript">
    function inicializar() {
        infraEfeitoTabelas();
    }

    function pesquisar() {
        $("#frmMdCorUnidadeExpCadastro").submit();
    }

    function acaoDesativar(idCampo) {
        if (confirm("Confirma a desativa��o do Mapeamento de Unidades Solicitantes?")) {
            document.getElementById('hdnInfraItemId').value = idCampo;
            document.getElementById('frmMdCorUnidadeExpCadastro').action = '<?=$strLinkDesativar?>';
            document.getElementById('frmMdCorUnidadeExpCadastro').submit();
        }
    }

    function acaoReativar(idCampo) {
        if (confirm("Confirma a reativa��o do Mapeamento de Unidades Solicitantes?")) {
            document.getElementById('hdnInfraItemId').value = idCampo;
            document.getElementById('frmMdCorUnidadeExpCadastro').action = '<?=$strLinkReativar?>';
            document.getElementById('frmMdCorUnidadeExpCadastro').submit();
        }
    }

    function acaoExcluir(idCampo) {
        if (confirm("Confirma a exclus�o do Mapeamento de Unidades Solicitantes?")) {
            document.getElementById('hdnInfraItemId').value = idCampo;
            document.getElementById('frmMdCorUnidadeExpCadastro').action = '<?=$strLinkExcluir?>';
            document.getElementById('frmMdCorUnidadeExpCadastro').submit();
        }
    }
</script>
