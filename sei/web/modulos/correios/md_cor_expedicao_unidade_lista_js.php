<script type="text/javascript">
    function inicializar() {
        infraEfeitoTabelas();
        addEventoEnter();
    }

    function pesquisar() {
        var frmExpedicaoSolicitadaUnidade = document.getElementById('frmExpedicaoSolicitadaUnidade');
        var txtDataSolicitacao = document.getElementById('txtDataSolicitacao');
        var txtDataExpedicao = document.getElementById('txtDataExpedicao');

        if (!infraValidaData(txtDataSolicitacao)) {
            return false;
        }

        if (!infraValidaData(txtDataExpedicao)) {
            return false;
        }

        frmExpedicaoSolicitadaUnidade.submit();
    }
</script>
