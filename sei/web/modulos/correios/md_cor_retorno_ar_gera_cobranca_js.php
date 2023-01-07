<script type="text/javascript">
    "use strict";

    function inicializar() {
        var arrSelecionado = window.parent.document.getElementById('hdnInfraItensSelecionados').value;
        console.log(arrSelecionado);
        document.getElementById('hdnCodIdSolicitacao').value = arrSelecionado;
    }

    function confirmarGeraCobranca() {
        document.getElementById('frmConsulta').submit();
    }
</script>