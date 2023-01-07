<script type="text/javascript">
    function inicializar() {
        infraEfeitoTabelas();
        addEventoEnter();
    }

    function pesquisar() {
        var frmListarExpedicao = document.getElementById('frmListarExpedicao');
        frmListarExpedicao.submit();
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

    function acaoConsultarExpedicao(url) {
        parent.infraAbrirJanelaModal(url, 900, 600);
    }

    function acaoDetalharRastreamento(url) {
        parent.infraAbrirJanelaModal(url, 800, 500);
    }
</script>