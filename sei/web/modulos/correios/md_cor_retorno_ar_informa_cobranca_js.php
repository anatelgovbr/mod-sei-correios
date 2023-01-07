<?php $strLinkAjaxDadosDocumento = SessaoSEI::getInstance()->assinarLink('controlador_ajax.php?acao_ajax=md_cor_dados_documento_consultar'); ?>
<script type="text/javascript">
    "use strict";

    function inicializar() {
        var arrSelecionado = window.opener.document.getElementById('hdnInfraItensSelecionados').value;
        document.getElementById('hdnCodIdSolicitacao').value = arrSelecionado;
    }


    function confirmarGeraCobranca() {
        var idDocumento = document.getElementById('hdnIdDocumento').value.trim();

        if(idDocumento.length == 0){
            alert('Número do Documento não informado.');
            return false;
        }

        document.getElementById('frmConsulta').submit();
    }

    function buscaDocumento(campo) {
        var nuSeiDocumento = campo.value.trim();

        if (nuSeiDocumento.length > 0) {
            $.ajax({
                url: '<?=$strLinkAjaxDadosDocumento?>',
                type: 'POST',
                dataType: 'XML',
                data: {
                    nuSeiDocumento: nuSeiDocumento
                },
                beforeSend: function () {
                },
                success: function (r) {
                    var error = $.trim($(r).find('error').text());
                    if (error.length > 0) {
                        alert(error);
                        $(campo).val('');
                        $("#hdnNumeroDocumento").val('');
                        $("#lblDocumentoRetorno").html('');
                        $(campo).focus();
                        return false;
                    } else {
                        var nuDocumento = nuSeiDocumento;
                        var noDocumento = $(r).find('nu-documento').text();
                        var idDocumento = $(r).find('id-documento-principal').text();
                        $("#hdnIdDocumento").val(idDocumento);
                        $("#lblDocumentoRetorno").html(noDocumento+' - '+nuDocumento);
                    }
                }
            });
        }
    }
</script>