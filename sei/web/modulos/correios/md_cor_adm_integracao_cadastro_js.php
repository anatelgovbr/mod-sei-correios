<script type="text/javascript">
    var contNewReg        = 0;
    var idHeaderAlt       = null;

    function inicializar() {
        iniciarTabelaDinamicaHeader();
        infraEfeitoTabelas( true );
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

    function iniciarTabelaDinamicaHeader() {

        objTabelaDinamicaHeaders = new infraTabelaDinamica('tblAutenticacao', 'hdnTbTokens', false, true);

        objTabelaDinamicaHeaders.gerarEfeitoTabela = true;

        if (objTabelaDinamicaHeaders.hdn.value != '') objTabelaDinamicaHeaders.recarregar();

        objTabelaDinamicaHeaders.procuraLinha = function( id ) {
            let qtd = document.querySelector('#tblAutenticacao').rows.length;;
            let linha;

            for ( let i = 1 ; i < qtd ; i++ ) {
                linha = document.querySelector('#tblAutenticacao').rows[i];
                let valorLinha = $.trim( linha.cells[0].innerText );
                if ( valorLinha == id ) return i;
            }
            return null;
        };

        objTabelaDinamicaHeaders.alterar = function( id ) {
            editarHeader( id[0] );
        };
    }

    function adicionarHeaderTable() {

        let idHeader     = idHeaderAlt !== null ? idHeaderAlt : 'novo_' + contNewReg;
        let selContrato   = document.querySelector('#selContrato option:checked').textContent;
        let selContratoId   = document.querySelector('#selContrato').value.split('#')[0];
        let txtUsuario     = document.querySelector('#txtUsuario').value;
        let txtSenha     = document.querySelector('#txtSenha').value;
        let txtToken     = document.querySelector('#txtToken').value;
        
        let objRetorno   = {
            selContratoId: selContratoId,
            selContrato: selContrato,
            txtUsuario: txtUsuario,
            txtSenhaReal: txtSenha,
            txtTokenReal: txtToken,
            txtSenha: '*****',
            txtToken: '*****'
        };

        if ( txtUsuario == '' || txtSenha == '' || txtToken == '' ) {
            alert('Faltou preencher os campos Usuário, Senha ou Token.');
            return false;
        }

        let hdnListaHeadersPart = objTabelaDinamicaHeaders.hdn.value;
        let arrListaHeadersPart = hdnListaHeadersPart.split('¥');

        for ( let i = 0 ; i < arrListaHeadersPart.length ; i++ ) {
            let hdnListaHeadPart = arrListaHeadersPart[i].split('±');
            if ( hdnListaHeadPart[1] == selContratoId ) {
                alert('Já existe um registro com o contrato selecionado.');
                return false;
            }
        }

        let arrLinha = [
            idHeader,
            objRetorno.selContratoId,
            objRetorno.selContrato,
            objRetorno.txtUsuario,
            objRetorno.txtSenhaReal,
            objRetorno.txtTokenReal,
            objRetorno.txtSenha,
            objRetorno.txtToken
        ];

        // caso seja null, é um novo registro
        if( idHeaderAlt === null ) contNewReg += 1;

        objTabelaDinamicaHeaders.adicionar( arrLinha );

        idHeaderAlt = null;

        limparCamposHeader();
    }

    function limparCamposHeader() {
        const list_inputs = document.querySelectorAll('.input_header');
        list_inputs.forEach( elem => {
            if ( elem.type == 'text' )
                elem.value = null;
            else if ( elem.type == 'checkbox' )
                elem.checked = false;
        });
    }
</script>
