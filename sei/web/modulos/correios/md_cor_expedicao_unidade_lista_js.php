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


    function acaoDetalharRastreamento(url) {
        parent.infraAbrirJanelaModal(url, 800, 500);
    }

    function acaoConsultarExpedicao(url) {
        parent.infraAbrirJanelaModal(url, 1000, 700);
    }
</script>
