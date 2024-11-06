<script type="text/javascript">
    function inicializar() {
        switch ( document.querySelector('#hdnTipoAcao').value ){
            case 'cadastrar':
                break;
            case 'alterar':
                break;
            case 'consultar':
                infraDesabilitarCamposAreaDados();
                break;
        }
        showHideDivAutenticao( $("#selFuncionalidade option:selected").val() );
    }

    function OnSubmitForm() {
        return validarCadastro();
    }

    function validarCadastro() {
        return true;
    }

    // Alterou a funcionalidade
    $('#selFuncionalidade').change(function (){
        showHideDivAutenticao( $( this ).val() );
        exibeNomeDaFuncionalidade();
    });

    function showHideDivAutenticao(vlr){
        if ( vlr == <?= MdCorAdmIntegracaoRN::$GERAR_TOKEN ?> ) {
            $('#divAutenticacao').show();
        } else {
            $('#divAutenticacao').hide();
        }
    }

    function exibeNomeDaFuncionalidade(){
        let strFunc    = $("#selFuncionalidade option:selected").text();
        let arrStrFunc = strFunc.split('::');
        $("input[name*='txtNome']").val( arrStrFunc[1] );
    }
</script>
