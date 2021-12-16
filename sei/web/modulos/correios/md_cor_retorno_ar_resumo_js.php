<script type="text/javascript">
    "use strict";

    function inicializar() {
        infraEfeitoTabelas();
    }

    function abrirResumoProcessamento(url) {
        infraAbrirJanela(url, "janelaResumo", 1200, 500)
    }

    function pesquisar() {
        document.getElementById('frmConsulta').submit();
    }

</script>