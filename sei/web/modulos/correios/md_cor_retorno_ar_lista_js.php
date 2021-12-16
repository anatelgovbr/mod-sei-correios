
<?php $strAutenticarLote = SessaoSEI::getInstance()->assinarLink('controlador_ajax.php?acao_ajax=md_cor_retorno_ar_autenticar_lote'); ?>
<script type="text/javascript">
    "use strict";

    function inicializar() {        
        infraEfeitoTabelas();
    } //fim


    function pesquisar() {
//        var dtInicio = document.getElementById('txtCodigoRastreio').value;
//        var dtFim = document.getElementById('txtPeriodoProcessamentoFim').value;
//
//        if (infraTrim(dtInicio) == '') {
//            alert('Informe o Período Inicial.');
//            document.getElementById('txtCodigoRastreio').focus();
//            return false;
//        }
//
//        if (infraTrim(dtFim) == '') {
//            alert('Informe o Periodo Final.');
//            document.getElementById('txtPeriodoProcessamentoFim').focus();
//            return false;
//        }
//
//        if (infraCompararDatas(dtInicio, dtFim) < 0) {
//            alert('Período Final tem que ser Maior ou Igual ao Período Inicial.');
//            document.getElementById('txtPeriodoProcessamentoFim').focus();
//            return false;
//        }
        document.getElementById('frmConsulta').submit();
    }

    function autenticarDocumento(url) {
        infraAbrirJanela(url,
            'concluirRetornoAr',
            770,
            480,
            '', //options
            false); //modal
    }

    function autenticarLote() {
        var infraNroItens = document.getElementById('hdnInfraNroItens').value;
        var i, box;
        var arrSelected = [];
        for (i = 0; i < infraNroItens; i++) {
            box = document.getElementById('chkInfraItem' + i);
            if (box != null && box.checked) {
                arrSelected.push(box.value);
            }
        }


        if (arrSelected.length == 0) {
            alert('Nenhum registro selecionado.');
            return false;
        } else {
            $.ajax({
                url: '<?php echo $strAutenticarLote?>',
                type: 'POST',
                dataType: 'XML',
                data: {
                    'arr': $("#hdnInfraItensSelecionados").val()
                },
                success: function (data) {
                    var url = $(data).find('url').text();
                    infraAbrirJanela(url,
                        'concluirRetornoAr',
                        770,
                        480,
                        '', //options
                        false); //modal
                }

            })
        }

    }
</script>