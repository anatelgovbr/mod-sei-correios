<script>

    function validarFormulario() {

        var txaJustificativa = document.getElementById('txaJustificativa').value;

        if (txaJustificativa == '') {

            alert('Informe a justificativa.');
            document.getElementById('txaJustificativa').focus();
            return;

        } 
        //chegando aqui � porque passou em todas as valida��es , esta tudo OK e pode submeter o cadastro
        document.getElementById('frmSolicitarExpedicao').submit();

    }

</script>