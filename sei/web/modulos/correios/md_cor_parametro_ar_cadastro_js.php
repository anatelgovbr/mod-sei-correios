<?php $strUrlAjaxNumeroProcesso = SessaoSEI::getInstance()->assinarLink('controlador_ajax.php?acao_ajax=md_cor_numero_processo_cobranca_validar'); ?>
<?php $strLinkAjaxAutocompletarDestinatario = SessaoSEI::getInstance()->assinarLink('controlador_ajax.php?acao_ajax=md_cor_parametro_ar_cadastro_destinatario'); ?>
<script type="text/javascript">

    "use strict";
    var objLupaTipoDocumento = null;
    var objLupaTipoDocumentoObjetoDevolvido = null;
    var objLupaTipoDocumentoCobranca = null;
    var objTabelaDinamicaMotivo = null;
    var objAutoCompletarDestinatario = null;
    function inicializar() {
        objLupaTipoDocumento = new infraLupaSelect('slTipoDocumento', 'hdnTipoDocumento', '<?= $strLinkTipoDocumentoSelecao ?>');
        objLupaTipoDocumentoObjetoDevolvido = new infraLupaSelect('slTipoDocumentoObjetoDevolvido', 'hdnTipoDocumentoObjetoDevolvido', '<?= $strLinkTipoDocumentoObjetoDevolvidoSelecao ?>');
        objLupaTipoDocumentoCobranca = new infraLupaSelect('slTipoDocumentoCobranca', 'hdnTipoDocumentoCobranca', '<?= $strLinkTipoDocumentoCobrancaSelecao ?>');
        iniciarTabelaDinamicaMotivo();
//        ativarEditor();s
        iniciarAutoCompletarDestinatario();
    } //fim funcao inicializar

    function iniciarTabelaDinamicaMotivo() {
        objTabelaDinamicaMotivo = new infraTabelaDinamica('tbMotivo', 'hdnTbMotivo', true, true);
        objTabelaDinamicaMotivo.gerarEfeitoTabela = true;

        objTabelaDinamicaMotivo.alterar = function (arr) {
            document.getElementById('txtMotivo').value = arr[0];
            if(arr[2] == "true") {
                document.getElementById("checkInfrigencia").checked = true;
            } else {
                document.getElementById("checkInfrigencia").checked = false;
            }
            document.getElementById("hdnIdMotivoDevolucao").value = arr[3];
            document.getElementById("btnAdicionar").innerHTML = "Alterar";
            document.getElementById("btnCancelarAlteracao").style.display = "block";
        };
    }

    function limparTipoDocumento() {
        document.getElementById('hdnTipoDocumento').value = '';
        var selectobject = document.getElementById("slTipoDocumento")
        for (var i = 0; i < selectobject.length; i++) {
            selectobject.remove(i);
        }
    }

    function enviarFormulario() {
        if (infraTrim(document.getElementById('txtNuDiaRetorno').value) == '') {
            alert('Informe o Prazo Padrão para Retorno de AR.');
            document.getElementById('txtNuDiaRetorno').focus();
            return false;
        }

        if (infraTrim(document.getElementById('txtNoArvore').value) == '') {
            alert('Informe o Número/Nome da Árvore para Padrão para Documento Externo de Retorno de AR.');
            document.getElementById('txtNoArvore').focus();
            return false;
        }
        if (infraTrim(document.getElementById('txtNoArvoreDevolvido').value) == '') {
            alert('Informe o Número/Nome da Árvore para Padrão para Documento Externo de Objeto Devolvido.');
            document.getElementById('txtNoArvoreDevolvido').focus();
            return false;
        }

        var qtdTipoDocumentoRetorno = 0
        $('#slTipoDocumento').each(function (i, val) {
            if ($(val).find(':selected').val() != 'NULL') {
                qtdTipoDocumentoRetorno++;
            }
        })

        if (qtdTipoDocumentoRetorno == 0) {
            alert('Informe o Tipo de Documento para Padrão para Documento Externo de Retorno de AR.');
            $(val).focus();
            return false;
        }

        var qtdTipoConferenciaRetorno = 0;
        $('#slTipoConferencia').each(function (i, val) {
            if ($(val).find(':selected').val() != 'NULL') {
                qtdTipoConferenciaRetorno++;
            }
        })

        if (qtdTipoConferenciaRetorno == 0) {
            alert('Informe o Tipo de Conferência para Padrão para Documento Externo de Retorno de AR.');
            $(val).focus();
            return false;
        }

        var qtdTipoDocumentoDevolvido = 0
        $('#slTipoDocumentoObjetoDevolvido').each(function (i, val) {
            if ($(val).find(':selected').val() != 'NULL') {
                qtdTipoDocumentoDevolvido++;
            }
        })

        if (qtdTipoDocumentoDevolvido == 0) {
            alert('Informe o Tipo de Documento para Padrão para Documento Externo de Objeto Devolvido.');
            $(val).focus();
            return false;
        }

        var qtdTipoConferenciaDevolvido = 0;
        $('#slTipoConferenciaObjetoDevolvido').each(function (i, val) {
            if ($(val).find(':selected').val() != 'NULL') {
                qtdTipoConferenciaDevolvido++;
            }
        })

        if (qtdTipoConferenciaDevolvido == 0) {
            alert('Informe o Tipo de Conferência para Padrão para Documento Externo de Objeto Devolvido.');
            $(val).focus();
            return false;
        }

        var qtdTipoDocumentoCobranca = 0
        $('#slTipoDocumentoCobranca').each(function (i, val) {
            if ($(val).find(':selected').val() != 'NULL') {
                qtdTipoDocumentoCobranca++;
            }
        })

        if (qtdTipoDocumentoCobranca == 0) {
            alert('Informe o Tipo de Documento Padrão de Documento de Cobrança.');
            $(val).focus();
            return false;
        }

        if (infraTrim(document.getElementById('txtProcessoCobranca').value) == '') {
            alert('Informe o Processo de Cobrança.');
            document.getElementById('txtProcessoCobranca').focus();
            return false;
        }

        var qtdUnidadeGeradora = 0
        $('#txtUnidadeGeradora').each(function (i, val) {
            if ($(val).find(':selected').val() != 'NULL') {
                qtdUnidadeGeradora++;
            }
        })

        if (qtdUnidadeGeradora == 0) {
            alert('Informe a Unidade Geradora do Documento.');
            $(val).focus();
            return false;
        }

        document.getElementById('frmMdCorParametroCadastro').submit()
    }

    function adicionarMotivo() {

        var dsMotivo = infraTrim(document.getElementById('txtMotivo').value);
        var ckInfrigencia = document.getElementById('checkInfrigencia').checked;

        var qtdMotivo = 0;
        var infrigenciaContratualCadastrada = false;
        var rowIndexInfrigencia = "";
        $('#tbMotivo tr').each(function () {
            var dsMotivoTabela = infraTrim($(this).find('td:eq(0)').find('div').html());
            if (dsMotivo == dsMotivoTabela) {
                qtdMotivo++;
            }
            var dsInfrigenciaContratual = infraTrim($(this).find('td:eq(1)').find('div').html());
            if(dsInfrigenciaContratual == "Sim") {
                infrigenciaContratualCadastrada = true;
                rowIndexInfrigencia = $(this).index();
            }
        })
        if(infrigenciaContratualCadastrada && ckInfrigencia && $("#hdnIdMotivoDevolucao").val() == "") {
            alert('Só pode existir um Motivo de Devolução de Objeto com Infrigência Contratual.');
            document.getElementById('checkInfrigencia').focus();
            return false;
        }
        if (qtdMotivo > 0 && $("#hdnIdMotivoDevolucao").val() == "") {
            alert('Motivo já cadastrado na tabela.');
            document.getElementById('txtMotivo').focus();
            return false;
        }


        if (infraTrim(dsMotivo) == '') {
            alert('Informe o Motivo de Retorno.');
            document.getElementById('txtMotivo').focus();
            return false;
        }

        var dsInfrigencia = ckInfrigencia ? 'Sim' : "Não";

        if($("#hdnIdMotivoDevolucao").val() != "") {
            var dados = [
                $("#hdnIdMotivoDevolucao").val(),
                dsMotivo,
                dsInfrigencia,
                ckInfrigencia,
                null,
            ];
            $('#tbMotivo tr').each(function() {
                if ($("#hdnIdMotivoDevolucao").val() == $(this).find('td:eq(3) div').html()) {
                    if((rowIndexInfrigencia != $(this).index()) && infrigenciaContratualCadastrada && ckInfrigencia) {
                        alert('Só pode existir um Motivo de Devolução de Objeto com Infrigência Contratual.');
                        document.getElementById('checkInfrigencia').focus();
                        return false;
                    }
                    $(this).find('td:eq(0) div').html(dsMotivo);
                    $(this).find('td:eq(1) div').html(dsInfrigencia);
                    $(this).find('td:eq(2) div').html(ckInfrigencia);
                    objTabelaDinamicaMotivo.alterarLinha($(this).index());
                    objTabelaDinamicaMotivo.atualizaHdn();
                    document.getElementById("btnAdicionar").innerHTML = "Adicionar";
                    document.getElementById("btnCancelarAlteracao").style.display = "none";
                    document.getElementById("hdnIdMotivoDevolucao").value = "";
                    document.getElementById('txtMotivo').value = ''
                    document.getElementById('checkInfrigencia').checked = false;
                }
            });
        } else {
            var dados = [
                dsMotivo,
                dsInfrigencia,
                ckInfrigencia,
                null,
            ];
            objTabelaDinamicaMotivo.adicionar(dados);
            document.getElementById('txtMotivo').value = ''
            document.getElementById('checkInfrigencia').checked = false;
        }
    }

    function buscarProcessoUnidadeGeradora(campo) {
        var valor = campo.value.trim();

        if (valor.length > 0) {
            var paramsAjax = {
                txtNumeroProcessoContratacao: valor
            };

            $.ajax({
                url: '<?= $strUrlAjaxNumeroProcesso ?>',
                type: 'POST',
                dataType: 'XML',
                data: paramsAjax,
                success: function (r) {
                    if ($(r).find('MensagemValidacao').text()) {
                        //inicializarCamposPadroesProcesso();
                        alert($(r).find('MensagemValidacao').text());
                    } else {
                        document.getElementById('hdnIdProcedimento').value = $(r).find('IdProcedimento').text();
                        $("#txtUnidadeGeradora").find('option').remove();
                        $("#txtUnidadeGeradora").append(
                                $('<option></option>')
                                .val('NULL')
                                .text(' ')
                                );
                        $("#txtUnidadeGeradora").append($(r).find('Unidades').html());

                    }
                },
                error: function (e) {

                    if ($(e.responseText).find('MensagemValidacao').text()) {
                        //inicializarCamposPadroesProcesso();
                        alert($(e.responseText).find('MensagemValidacao').text());
                    }
                    console.error('Erro ao processar o XML do SEI: ' + e.responseText);
                }
            });
        }

    }

    function iniciarAutoCompletarDestinatario() {


        objAutoCompletarDestinatario = new infraAjaxAutoCompletar('hdnIdDestinatario', 'txtDestinatario', '<?= $strLinkAjaxAutocompletarDestinatario ?>');
        objAutoCompletarDestinatario.limparCampo = true;
        objAutoCompletarDestinatario.tamanhoMinimo = 3;
        objAutoCompletarDestinatario.prepararExecucao = function () {
            return 'palavras_pesquisa=' + document.getElementById('txtDestinatario').value;
        };

        objAutoCompletarDestinatario.processarResultado = function (id, descricao) {
            if (id != '') {
//                objLupaUnidade.adicionar(id, descricao, document.getElementById('txtDestinatario'));
            }
        };
    }

</script>