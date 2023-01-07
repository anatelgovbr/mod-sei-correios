<script type="text/javascript">

    function inicializar() {
        if ('<?=$_GET['acao']?>' == 'md_cor_parametro_rastreio_cadastrar') {
            document.getElementById('txtIdMdCorParametroRastreio').focus();
        } else if ('<?=$_GET['acao']?>' == 'md_cor_parametro_rastreio_consultar') {
            infraDesabilitarCamposAreaDados();
        } else {
            document.getElementById('btnCancelar').focus();
        }
        infraEfeitoTabelas(true);
    }

    function validarEnderecoWs() {
        if (validarCadastro()) {
            $.ajax({
                url: '<?php echo $strLinkAjaxValidacaoEndereco?>',
                type: 'post',
                data: {
                    'usuario': $("#txtUsuario").val(),
                    'senha': $("#txtSenha").val(),
                    'endereco': $("#txtEnderecoWsdl").val()
                },
                dataType: 'xml',
                success: function (data) {
                    var error = $(data).find('error').text();
                    if (error == 'true') {
                        var msg = $(data).find('MensagemValidacao').text()
                        alert('Erro ao validar: ' + msg);
                        document.getElementById('txtUsuario').focus();
                        return false;
                    } else {
                        alert('Web Service validado.');
                        $("#hdnValidacao").val('validado');
                    }
                }
            })
        }
    }

    function validarCadastro() {
        if (infraTrim(document.getElementById('txtUsuario').value) == '') {
            alert('Informe o Usuário Correios');
            document.getElementById('txtUsuario').focus();
            return false;
        }
        if (infraTrim(document.getElementById('txtSenha').value) == '') {
            alert('Informe a Senha Correios');
            document.getElementById('txtSenha').focus();
            return false;
        }

        if (infraTrim(document.getElementById('txtEnderecoWsdl').value) == '') {
            alert('Informe o Endereço WSDL do Web Service do SRO.');
            document.getElementById('txtEnderecoWsdl').focus();
            return false;
        }
        return true;
    }

    function validainput() {
        document.getElementById('hdnValidacao').value = ''
    }

    function OnSubmitForm() {
        var validado = $("#hdnValidacao").val();
        if (validado != 'validado') {
            alert('Antes de salvar é necessário validar o Endereço WSDL do Web Service do SRO.');
            document.getElementById('btnValidacao').focus();
            return false;
        } else {
            return validarCadastro();
        }

    }

</script>
