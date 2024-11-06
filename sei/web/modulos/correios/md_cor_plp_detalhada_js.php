<script type="text/javascript">

    function inicializar() {
        document.getElementById('btnFechar').focus();
        infraEfeitoTabelas();
    }

    function pesquisar() {
        document.getElementById('frmDetalharPlp').target = '';
        document.getElementById('frmTipoControleLitigiosoLista').submit();
    }

    function imprimirPrincipalLote() {
        if (document.getElementById('hdnInfraItensSelecionados').value == '') {
            alert('Selecione ao menos uma PLP para o impressão do documento principal');
            return false;
        }

        document.getElementById('frmDetalharPlp').target = '_blank';
        document.getElementById('frmDetalharPlp').action = '<?php echo SessaoSEI::getInstance()->assinarLink('controlador.php?acao=md_cor_plp_pdf_documento_principal&acao_origem=' . $_GET['acao']); ?>'
        document.getElementById('frmDetalharPlp').submit();
    }

    function imprimirArquivolLote() {
        if (document.getElementById('hdnInfraItensSelecionados').value == '') {
            alert('Nenhum registro selecionado.');
            return false;
        }

        document.getElementById('frmDetalharPlp').target = '_blank';
        document.getElementById('frmDetalharPlp').action = '<?php echo SessaoSEI::getInstance()->assinarLink('controlador.php?acao=md_cor_plp_pdf_arquivo_lote&acao_origem=' . $_GET['acao'] . '&id_md_cor_plp=' . $_GET['id_md_cor_plp']); ?>'
        document.getElementById('frmDetalharPlp').submit();
        var arrIdSocitacao = new Array();
        $('input:checked').each(function () {
            substituiIconeExpedirPlp($(this).val());
        });

    }

    function substituiIconeExpedirPlp(idPlp) {
        $('.'+idPlp+' a.botaoExpedirObjeto').html('<img src="modulos/correios/imagens/svg/expedir_objeto.svg?<?= Icone::VERSAO ?>" title="Expedir Objeto" alt="Expedir Objeto" class="infraImgAcoes acessado" />');
    }

    function getAjaxValidarDocumentoAPI() {

        var arrIdSocitacao = new Array();
        $('input:checked').each(function () {
            arrIdSocitacao.push($(this).val());
        });

        $.ajax({
            type: "POST",
            url: "<?= $strLinkAjaxValidarDocumentoAPI?>",
            async: false,
            cache: false,
            dataType: "xml",
            data: {arrIdSocitacao: arrIdSocitacao},

            success: function (r) {
                isRestrito = $(r).find('isRestrito').text();
            },
            error: function (msgError) {
                msgCommit = "Erro ao processar o XML do SEI: " + msgError.responseText;
                alert(msgCommit);
                isRestrito = false;
            }
        });

        return isRestrito;
    }

    function imprimirVoucher() {

        infraSelecaoMultipla('Infra');

        var frm = document.getElementById('frmDetalharPlp');

        var targetAnterior = frm.target;
        var actionAnterior = frm.action;

        frm.target = '_blank';
        frm.action = '<?= $strLinkImprimirVoucher ?>';

        frm.submit();

        frm.target = targetAnterior;
        frm.action = actionAnterior;

    }

    function solicitarImprimirAR() {
        var frm = document.getElementById('frmDetalharPlp');

        var targetAnterior = frm.target;
        var actionAnterior = frm.action;

        frm.target = '_blank';
        frm.action = '<?= $strLinkImprimirAR ?>';

        frm.submit();

        frm.target = targetAnterior;
        frm.action = actionAnterior;
    }

    function imprimirAR(id) {

        if (typeof id !== 'undefined') {
            document.getElementById('hdnInfraItensSelecionados').value = id;
        }

        if (document.getElementById('hdnInfraItensSelecionados').value == '') {
            alert('Selecione ao menos uma PLP para as ARs');
            return false;
        }

        $.ajax({
            url: '<?= $strLinkVerificarImprimirAR ?>',
            type: 'POST',
            data: {'hdnInfraItensSelecionados': document.getElementById('hdnInfraItensSelecionados').value},
            async: false,
            success: function (response) {
                const resultado = $(response).find('Resultado').text();
                const mensagem = $(response).find('Mensagem').text();
                switch (resultado) {
                    case '<?=MdCorPlpINT::$STR_IMPRIMIR_AR_OK?>':
                        solicitarImprimirAR();
                        break;
                    case '<?=MdCorPlpINT::$STR_IMPRIMIR_AR_SEM_AR?>':
                        alert(mensagem);
                        break;
                    case '<?=MdCorPlpINT::$STR_IMPRIMIR_AR_MISTO?>':
                        if (confirm(mensagem)) {
                            solicitarImprimirAR();
                        }
                        break;
                }
            },
            error: function (e) {
                if ($(e.responseText).find('MensagemValidacao').text()) {
                    alert($(e.responseText).find('Mensagem').text());
                }
                console.error('Erro ao processar o XML do SEI: ' + e.responseText);
            }
        });

    }

    function imprimirRotuloEnvelopeModal(id){
        if (typeof id !== 'undefined') {
            document.getElementById('hdnInfraItensSelecionados').value = id;
        }

        if (document.getElementById('hdnInfraItensSelecionados').value == '') {
            alert('Selecione ao menos uma PLP para o rotulo do envelope');
            return false;
        }

        let link = "<?= SessaoSEI::getInstance()->assinarLink('controlador.php?acao=md_cor_selecionar_layout_envelope') ?>";
        infraAbrirJanelaModal(link,600,300,false);
    }

    function imprimirRotuloEnvelope() {
        var frm = document.getElementById('frmDetalharPlp');

        var targetAnterior = frm.target;
        var actionAnterior = frm.action;

        frm.target = '_blank';
        frm.action = '<?= $strLinkImprimirRotuloEnvelope ?>';

        frm.submit();

        frm.target = targetAnterior;
        frm.action = actionAnterior;
    }


    function ConcluirPrePostagem() {
        var numRegistro = document.getElementById('hdnInfraNroItens').value;
        var acessado = document.getElementsByClassName('acessado');
        var qtdAcessado = 0;
        for (i in acessado) {
            if (!acessado.hasOwnProperty(i)) continue;
            qtdAcessado++
        }

        if (qtdAcessado < numRegistro) {
            alert('Somente é possível concluir a expedição da Pré-Postagem depois que realizar a expedição de cada Objeto abaixo');
            return false;
        }
        infraExibirAviso(false);

        document.getElementById('frmDetalharPlp').action = '<?php echo SessaoSEI::getInstance()->assinarLink('controlador.php?acao=md_cor_plp_concluir&acao_origem=md_cor_plp_expedir&id_plp=' . $_GET['id_md_cor_plp']); ?>'
        setTimeout(function () {
            window.location = '<?php echo SessaoSEI::getInstance()->assinarLink('controlador.php?acao=md_cor_expedicao_plp_listar'); ?>'
        }, 500);
        document.getElementById('frmDetalharPlp').submit();
    }

</script>
