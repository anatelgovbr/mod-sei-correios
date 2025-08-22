<script type="text/javascript">
    
    var acao = '<?= $_GET['acao'] ?>';

    <? require_once('md_cor_contrato_cadastro_inicializacao.php');?>

    function limparNumeroProcedimento() {
        document.getElementById('hdnIdProcedimento').value = '';
    }

    function desativar() {
        document.getElementById('hdnIdProcedimento').value = '';
    }

    function inicializar() {
        if ('<?=$_GET['acao']?>' == 'md_cor_contrato_cadastrar') {
            document.getElementById('txtNumeroContrato').focus();
        } else if ('<?=$_GET['acao']?>' == 'md_cor_contrato_consultar') {
            infraDesabilitarCamposAreaDados();
        } else {
            document.getElementById('btnCancelar').focus();
        }
    }

    /**
     * Funções responsáveis pela validação do processo
     * @author: Wilton Júnior <wilton.junior@castgroup.com.br>
     * @since: 29/12/2016
     */
    function validarNumeroProcesso() {

        document.getElementById('hdnIdProcedimento').value = '';
        document.getElementById('hdnNumeroProcessoContratacao').value = '';
        document.getElementById('txtTipoProcessoContratacao').value = '';

        var numeroProcessoPreenchido = document.getElementById('txtNumeroProcessoContratacao').value != '';
        if (!numeroProcessoPreenchido) {
            exibirAlert('Informe o Número.','txtNumeroProcessoContratacao');
            return false;
        }

        var paramsAjax = {
            txtNumeroProcessoContratacao: document.getElementById('txtNumeroProcessoContratacao').value
        };

        $.ajax({
            url: '<?=$strUrlAjaxNumeroProcesso?>',
            type: 'POST',
            dataType: 'XML',
            data: paramsAjax,
            success: function (r) {
                if ($(r).find('MensagemValidacao').text()) {
                    exibirAlert( $(r).find('MensagemValidacao').text() , 'txtNumeroProcessoContratacao');
                } else {
                    document.getElementById('hdnIdProcedimento').value = $(r).find('IdProcedimento').text();
                    document.getElementById('txtTipoProcessoContratacao').value = $(r).find('TipoProcedimento').text();
                    document.getElementById('txtNumeroProcessoContratacao').value = $(r).find('numeroProcesso').text();
                    document.getElementById('hdnNumeroProcessoContratacao').value = document.getElementById('txtNumeroProcessoContratacao').value;
                }
            },
            error: function (e) {

                if ($(e.responseText).find('MensagemValidacao').text()) {
                    exibirAlert( $(e.responseText).find('MensagemValidacao').text() , 'txtNumeroProcessoContratacao' );
                }
                console.error('Erro ao processar o XML do SEI: ' + e.responseText);
            }
        });
    }

    function existeContrato() {
        var flag = true;

        return flag;
    }

    function validarCadastro() {
        if (infraTrim(document.getElementById('txtNumeroContrato').value) == '') {
            exibirAlert('Informe o Número do Contrato no Órgão.','txtNumeroContrato');
            return false;
        }

        if (
            infraTrim(document.getElementById('hdnNumeroProcessoContratacao').value) != infraTrim(document.getElementById('txtNumeroProcessoContratacao').value)
            ||
            document.getElementById('txtNumeroProcessoContratacao').value == ''
        ){
            exibirAlert('Número do Processo de Contratação não foi validado.','txtNumeroProcessoContratacao');
            return false;
        }

        if (infraTrim(document.getElementById('txtNumeroContratoCorreio').value) == '') {
            exibirAlert('Informe o Número do Contrato nos Correios.','txtNumeroContratoCorreio');
            return false;
        }

        if (infraTrim(document.getElementById('txtNumeroCartaoPostagem').value) == '') {
            exibirAlert('Informe o Cartão de Postagem dos Correios correspondente ao Contrato.','txtNumeroCartaoPostagem');
            return false;
        }

        if (infraTrim(document.getElementById('txtCNPJ').value) == '') {
            exibirAlert('Informe o CNPJ do Orgão do Contrato.','txtCNPJ');
            return false;
        }

        var flag = false;
        $.ajax({
            url: '<?=$strUrlAjaxVerificaContrato?>',
            type: 'POST',
            dataType: 'XML',
            async: false,
            data: {
                txtNumeroContratoCorreio: $('#txtNumeroContratoCorreio').val(),
                hdnIdMdCorContrato: $('#hdnIdMdCorContrato').val(),
            },
            success: function (r) {
                if ($(r).find('MensagemValidacao').text() != 'false') {
                    exibirAlert( $(r).find('MensagemValidacao').text() , 'txtNumeroContratoCorreio' );
                    flag = true;
                }
            },
            error: function (e) {
                if ($(e.responseText).find('MensagemValidacao').text()) {
                    exibirAlert( $(e.responseText).find('MensagemValidacao').text() );
                }
            }
        });

        if (flag) {
            return false;
        }

        return true;
    }

    function OnSubmitForm() {
        return validarCadastro();
    }

    function exibirBotao() {
        var div = document.getElementById('divInfraAvisoFundo');

        if (div != null && div.style.visibility == 'visible') {

            var botaoCancelar = document.getElementById('btnInfraAvisoCancelar');

            if (botaoCancelar != null) {
                botaoCancelar.style.display = 'block';
            }
        }
    }

    function exibirAvisoEditor() {

        var divFundo = document.getElementById('divInfraAvisoFundo');

        if (divFundo == null) {
            divFundo = infraAviso(false, 'Processando...');
        } else {
            document.getElementById('btnInfraAvisoCancelar').style.display = 'none';
            document.getElementById('imgInfraAviso').src = '/infra_css/imagens/aguarde.gif';
        }

        if (INFRA_IE == 0 || INFRA_IE >= 7) {
            divFundo.style.position = 'fixed';
        }

        var divAviso = document.getElementById('divInfraAviso');

        divAviso.style.top = Math.floor(infraClientHeight() / 3) + 'px';
        divAviso.style.left = Math.floor((infraClientWidth() - 200) / 2) + 'px';
        divAviso.style.width = '200px';
        divAviso.style.border = '1px solid black';

        divFundo.style.width = screen.width * 2 + 'px';
        divFundo.style.height = screen.height * 2 + 'px';
        divFundo.style.visibility = 'visible';

    }

    function processando() {

        exibirAvisoEditor();

        if (INFRA_IE > 0) {
            window.tempoInicio = (new Date()).getTime();
        } else {
            console.time('s');
        }
    }

    function verificaAr(campo) {
        var str = campo.options[campo.selectedIndex].value;
        var name = campo.getAttribute("name");
        var indice = name.substr(7);
        var arrValue = str.split('|');
        var sinAr = arrValue[1];

        if (sinAr == 'N') {
            var arrCkAr = document.getElementsByName('ar' + indice);
            for (i in arrCkAr) {
                if (!arrCkAr.hasOwnProperty(i)) continue;
                arrCkAr[i].removeAttribute('checked');
                arrCkAr[i].setAttribute('disabled', true)
            }
        } else {
            var arrCkAr = document.getElementsByName('ar' + indice);
            var qtdElemento = arrCkAr.length;
            for (i in arrCkAr) {
                if (!arrCkAr.hasOwnProperty(i)) continue;
                arrCkAr[i].removeAttribute('disabled')
            }

            arrCkAr[qtdElemento - 1].setAttribute('checked', true);
        }

    }

    function lerCelula(celula) {
        var ret = null;
        var div = celula.getElementsByTagName('div');
        if (div.length == 0) {
            ret = celula.innerHTML;
        } else {
            ret = div[0].innerHTML;
        }
        return ret.infraReplaceAll('<br>', '<br />');
    }
</script>