<script>

    function validarFormulario() {

        var txaJustificativa = document.getElementById('txaJustificativa').value;

        if (txaJustificativa == '') {

            alert('Informe a justificativa.');
            document.getElementById('txaJustificativa').focus();
            return;

        } 
        //chegando aqui é porque passou em todas as validações , esta tudo OK e pode submeter o cadastro
        document.getElementById('frmSolicitarExpedicao').submit();

    }

</script>