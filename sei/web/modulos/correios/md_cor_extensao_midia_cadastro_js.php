<script type="text/javascript">
    var objLupaPrincipal = null;
    var objAutoCompletarPrincipal = null;
    var objLupaComplementar = null;
    var objAutoCompletarComplementar = null;

    function inicializar() {
        objLupaPrincipal = new infraLupaSelect('selPrincipal', 'hdnPrincipal', '<?=$strLinkPrincipalSelecao?>');
        objAutoCompletarPrincipal = new infraAjaxAutoCompletar('hdnIdPrincipal', 'txtPrincipal', '<?=$strLinkAjaxPrincipal?>');
        objAutoCompletarPrincipal.limparCampo = true;
        objAutoCompletarPrincipal.tamanhoMinimo = 3;

        objAutoCompletarPrincipal.prepararExecucao = function () {
            return 'extensao=' + document.getElementById('txtPrincipal').value;
        };

        objAutoCompletarPrincipal.processarResultado = function (id, descricao, complemento) {
            if (id != '') {
                var options = document.getElementById('selPrincipal').options;

                for (var i = 0; i < options.length; i++) {
                    if (options[i].value == id) {
                        self.setTimeout('alert(\'Extensão já consta na lista.\')', 100);
                        break;
                    }
                }

                if (i == options.length) {
                    for (i = 0; i < options.length; i++) {
                        options[i].selected = false;
                    }

                    opt = infraSelectAdicionarOption(document.getElementById('selPrincipal'), descricao, id);
                    objLupaPrincipal.atualizar();
                    opt.selected = true;
                }

                document.getElementById('txtPrincipal').value = '';
                //document.getElementById('txtPrincipal').focus();
            }
        };

        //objAutoCompletarPrincipal.selecionar('id', '<?//=PaginaSEI::getInstance()->formatarParametrosJavascript('descricao')?>');
        objAutoCompletarPrincipal.selecionar('<?=$strIdUnidade?>', '<?=PaginaSEI::getInstance()->formatarParametrosJavaScript($strDescricaoUnidade)?>');

        if ('<?=$_GET['acao']?>' == 'md_cor_extensao_midia_cadastrar') {
            //document.getElementById('txtNomeExtensao').focus();
        } else if ('<?=$_GET['acao']?>' == 'md_cor_extensao_midia_consultar') {
            infraDesabilitarCamposAreaDados();
        } else {
            document.getElementById('btnCancelar').focus();
        }
        infraEfeitoTabelas();
    }

    function validarCadastro() {
        if (infraTrim(document.getElementById('hdnPrincipal').value) == '') {
            alert('Informe a Extensão.');
            //document.getElementById('txtNomeExtensao').focus();
            return false;
        }

        return true;
    }

    function OnSubmitForm() {
        return validarCadastro();
    }
</script>