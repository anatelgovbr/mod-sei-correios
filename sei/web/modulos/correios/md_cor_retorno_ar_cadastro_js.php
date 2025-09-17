<?php $strUrlUploadArquivo = SessaoSEI::getInstance()->assinarLink('controlador.php?acao=md_cor_upload_zip_ar'); ?>
<?php $strLinkAjaxDadosDocumento = SessaoSEI::getInstance()->assinarLink('controlador_ajax.php?acao_ajax=md_cor_dados_documento_consultar'); ?>
<?php $strContarArquivos = SessaoSEI::getInstance()->assinarLink('controlador_ajax.php?acao_ajax=md_cor_contar_arquivos'); ?>
<?php $tamanhoZip = MdCorParametroArINT::tamanhoMaximoZipPermitido(); ?>
<script type="text/javascript">
    "use strict";
    var objUploadArquivo = null;

    function inicializar() {
        iniciarObjUploadArquivo();
        infraEfeitoTabelas();
    } //fim funcao inicializar

    function iniciarObjUploadArquivo() {
        disabledObjDevolvido();
        var TAMANHO_MAXIMO = "<?= $tamanhoZip ?>";
        objUploadArquivo = new infraUpload('frmMdCorRetornoUpload', '<?= $strUrlUploadArquivo ?>');
        objUploadArquivo.finalizou = function (arr) {
            var retorno = contarArquivos(arr['nome_upload']);
            if (retorno == false) {
                //Tamanho do Arquivo
                var fileArquivo = document.getElementById('fileArquivo');
                alert('O arquivo Zip cont�m mais do que 50 PDF\'s');
                fileArquivo.value = '';
                fileArquivo.focus();
                return false;
            } else {
                document.getElementById('hdnNomeArquivo').value = arr['nome_upload'];
                document.getElementById('frmMdCorRetornoArCadastro').submit();

                //Tamanho do Arquivo
                var fileArquivo = document.getElementById('fileArquivo');
                var tamanhoArquivo = (arr['tamanho'] / 1024 / 1024).toFixed(2);

                if (tamanhoArquivo > parseInt(TAMANHO_MAXIMO)) {
                    alert('Tamanho m�ximo para o arquivo � de ' + TAMANHO_MAXIMO + 'Mb');
                    fileArquivo.value = '';
                    fileArquivo.focus();
                    return false;
                }
                processando();
            }
        }

//Arquivo com o mesmo nome j� adicionado

        objUploadArquivo.validar = function () {
            var fileArquivo = document.getElementById('fileArquivo');
            var ext = fileArquivo.value.split('.').pop().toLowerCase();


            if (ext != 'zip') {
                alert('Arquivo com Extens�o Incorreta.');
                fileArquivo.value = '';
                fileArquivo.focus();
                return false;
            }

            return true;
        };

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

    function adicionarDocumento() {
        objUploadArquivo.executar();
    }

    function buscarDadosDocumento(campo) {
        var nuSeiDocumento = $.trim(campo.value);
        var linha = $(campo).parents('tr').find('td:eq(0)').html();
        var campoNuSei = $('.nu-sei');

        $("#dt_ar" + linha).attr('disabled', 'disabled');
        $("#dt_retorno" + linha).attr('disabled', 'disabled');
        $("#st_devolvido" + linha).attr('disabled', 'disabled');
        $("#nu_documento" + linha + "").html('')

        if (nuSeiDocumento.length > 0) {
            var valida = 0;
            $(".nu_sei").each(function () {
                if (nuSeiDocumento == $(this).val()) {
                    valida++;
                }
            });

            if (valida > 1) {
                alert('N�mero do Sei j� preenchido.');
                $(campo).val('');
                $("#nu_documento" + linha + "").html('')
                $("#nu-processo" + linha + "").html('')
                $(campo).focus();
                return false;
            } else {
                $.ajax({
                    url: '<?= $strLinkAjaxDadosDocumento ?>',
                    type: 'POST',
                    dataType: 'XML',
                    data: {
                        nuSeiDocumento: nuSeiDocumento
                    },
                    beforeSend: function () {
                        processando();
                    },
                    success: function (r) {
                        var error = $.trim($(r).find('error').text());

                        if (error.length > 0) {
                            alert(error);
                            $(campo).val('');
                            $("#nu_documento" + linha + "").html('');
                            $("#nu-processo" + linha + "").html('');
                            $(campo).focus();
                            fecharProcessando();
                            return false;
                        } else {
                            var nuDocumento = $(r).find('nu-documento').text();
                            var nuProcesso = $(r).find('nu-processo').text();
                            var codigoRastreio = $(r).find('codigo-rastreamento').text();
                            var linkRastreio = $(r).find('link-rastreamento').text();
                            var idDocumentoPrincipal = $(r).find('id-documento-principal').text();
                            if (codigoRastreio != null) {
                                var txtRastreio = '<input name="hdnRastreamento[' + linha + ']" type="hidden" value="' + codigoRastreio + '"><a class="protocoloNormal" style="font-size: 1.0em !important; width:80%" title="Rastreio do AR" href="' + linkRastreio + '" target="_blank">' + codigoRastreio + '</a>'
                                var sinRecebido = $(r).find('sin-recebido').text();
                                var numObjetoDevolvido = $(r).find('num-objeto-devolvido').text();
                            }

                            if (codigoRastreio != '') {
                                $("#nu_documento" + linha + "").html('<span title="N�mero do Processo: ' + nuProcesso + '">' + nuDocumento + '</span>' + '<input type="hidden" name="idDocumentoPrincipal[' + linha + ']" value="' + idDocumentoPrincipal + '">');
                                $("#nu-processo" + linha + "").html(nuProcesso);
                                $("#codigo_rastreio" + linha + "").html(txtRastreio);
                                $("#spanStProcessamento" + linha).html('Identificado Manualmente');
                                $("#hdnStProcessamento" + linha).val(<?php echo MdCorRetornoArRN::$STA_RETORNO_AR_MANUAL; ?>);
                                $("#dt_ar" + linha).removeAttr('disabled');
                                $("#dt_retorno" + linha).removeAttr('disabled');
                                $("#st_devolvido" + linha).removeAttr('disabled');

                                if (sinRecebido == 'S' && numObjetoDevolvido != null) {

                                    alert('J� existe AR Retornado como Objeto Devolvido em Processamento anterior para o n�mero SEI informado.');
                                    limparCampos(linha)
                                }

                            } else {

                                alert('O documento indicado n�o foi expedido pelo m�dulo de Correios');
                                limparCampos(linha)
                            }
                        }
                        fecharProcessando();
                    }
                });
            }
        }

    }

    function limparCampos(linha) {
        $("#nu_documento" + linha + "").html('');
        $("#nu-processo" + linha + "").html('');
        $("#codigo_rastreio" + linha).html('');
        $("#nu_sei" + linha + "").val('');
        $("#spanStProcessamento" + linha).html('<?php echo MdCorRetornoArRN::$arrStatus[MdCorRetornoArRN::$STA_RETORNO_AR_NAO_PROCESSADO]; ?>');
        $("#hdnStProcessamento" + linha).val(<?php echo MdCorRetornoArRN::$STA_RETORNO_AR_NAO_PROCESSADO; ?>)
    }


    function enviarFormulario() {
        var valida = [];
        $(".nu_sei").each(function () {
            var nuSei = $.trim($(this).val());

            var linha = $(this).parents('tr').find('td:eq(0)').html();
            var dtAr = $('#dt_ar' + linha).val();
            var dtRetorno = $('#dt_retorno' + linha).val();
            var stDevolvido = $('#st_devolvido' + linha + ':checked').length;
            var coMotivo = $('#co_motivo' + linha + ' option:selected').val();

            if (infraCompararDatas(dtAr, infraDataAtual()) < 0) {
                valida.push('Data do AR n�o pode ser Maior que a Data Atual. Linha: ' + linha);
            }

            if (infraCompararDatas(dtRetorno, infraDataAtual()) < 0) {
                valida.push(' Data de Retorno n�o pode ser Maior que a Data Atual. Linha: ' + linha);
            }

            if (nuSei.length == 0) {
                valida.push('N�mero SEI documento principal Obrigat�rio. Linha: ' + linha);
            }

            if ($.trim(dtAr).length == 0) {
                valida.push('Data do AR Obrigat�rio. Linha: ' + linha);
            }
            if ($.trim(dtRetorno).length == 0) {
                valida.push('Data do Retorno do AR Obrigat�rio. Linha: ' + linha);
            }
            if (stDevolvido > 0 && coMotivo == 'null') {
                valida.push('Motivo do Objeto Devolvido Obrig�torio. Linha: ' + linha);
            }

            if (infraCompararDatas(dtAr, dtRetorno) < 0) {
                valida.push('Data do Retorno do AR Tem que ser Maior que a Data do AR. Linha: ' + linha);
            }

        });

        if (valida.length > 0) {
            alert(valida.join('\n'));
            return false;
        } else {

            $(".ckeckbox").removeAttr('disabled');
            $(".hidden_idDocumentoPrincipal").removeAttr('disabled');

            var qtdNaoIdentificado = 0;
            $(".hdnStProcessamento").each(function () {
                if ($(this).val() == 'N') {
                    qtdNaoIdentificado++;
                }
            });

            if (qtdNaoIdentificado > 0) {
                if (confirm("Na Tabela Existem " + qtdNaoIdentificado + " Arquivo(s) de ARs que N�o Foram Identificados. Deseja Continuar?")) {
                    infraExibirAviso(false);
                    document.getElementById('frmSalvarRetorno').submit();
                }
            } else {
                infraExibirAviso(false);
                document.getElementById('frmSalvarRetorno').submit();
            }
        }
    }

    function exibirBotaoCancelarAviso() {

        var div = document.getElementById('divInfraAvisoFundo');

        if (div != null && div.style.visibility == 'visible') {

            var botaoCancelar = document.getElementById('btnInfraAvisoCancelar');

            if (botaoCancelar != null) {
                botaoCancelar.style.display = 'block';
            }
        }
    }

    function validarFormatoData(obj) {

        var validar = infraValidarData(obj, false);
        if (!validar) {
            alert('Data Inv�lida!');
            obj.value = '';
        }

    }

    function fecharProcessando() {
        var divFundo = document.getElementById('divInfraAvisoFundo');
        divFundo.style.visibility = 'hidden';
    }

    function processando() {

        exibirAvisoEditor();
        self.setTimeout('exibirBotaoCancelarAviso()', 30000);

        if (INFRA_IE > 0) {
            window.tempoInicio = (new Date()).getTime();
        } else {
            console.time('s');
        }

    }

    function selecionaDevolvido(campo) {
        $(campo).parents('tr').find('.co_motivo').attr("disabled", 'disabled').val('null');
        if (campo.checked == true) {
            $(campo).parents('tr').find('.co_motivo').removeAttr("disabled");
        }
    }

    function disabledObjDevolvido() {
        $('.co_motivo').attr("disabled", 'disabled');
    }

    function contarArquivos(strNomeArquivo) {
        var retorno = false;
        $.ajax({
            url: '<?= $strContarArquivos ?>',
            type: 'POST',
            dataType: 'XML',
            data: {
                strNomeArquivo: strNomeArquivo
            },
            async: false,
            success: function (r) {
                var ret = $(r).find('Retorno').text();
                if (ret == 'true') {
                    retorno = true;
                } else {
                    retorno = false;
                }
            },
            error: function (e) {
                retorno = false;
            }
        });
        return retorno;
    }
</script>
