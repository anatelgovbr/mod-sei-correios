<script type="text/javascript">
    var objAutoCompletarUnidade = null;
    var objLupaUnidade = null;

    function inicializar() {
        iniciarAutoCompletarUnidadeSolicitante();
        if ('<?= $_GET['acao'] ?>' == 'md_cor_mapeamento_uni_exp_sol_consultar') {
            infraDesabilitarCamposAreaDados();
        }

        $('#selUnidadeExpedidora').on('change', function () {
            $.ajax({
                url: '<?= $strLinkAjaxAtualizaUnidade ?>',
                type: 'POST',
                dataType: 'XML',
                data: {
                    <?= isset($_GET['id_unidade']) ? 'idUnidade:' . $_GET['id_unidade'] . ',' : ''; ?>
                    idUnidadeExpedidora: $(this).val()
                },
                beforeSend: function () {
                },
                success: function (r) {
                    var urlUnidade = $.trim($(r).find('url-unidade').text());
                    iniciarAutoCompletarUnidadeSolicitante(urlUnidade);

                }
            });
        });

    }

    function iniciarAutoCompletarUnidadeSolicitante(strUrlUnidade = null) {
        var urlUnidade = '<?= $strUrlUnidade ?>';
        if (strUrlUnidade != null) {
            urlUnidade = strUrlUnidade;
        }
        console.log(urlUnidade);
        objLupaUnidade = new infraLupaSelect('selUnidadeSolicitante', 'hdnIdUnidades', urlUnidade);

        objAutoCompletarUnidade = new infraAjaxAutoCompletar('hdnIdUnidade', 'txtUnidadeSolicitante', '<?= $strLinkAjaxAutocompletarUnidade ?>');
        objAutoCompletarUnidade.limparCampo = true;
        objAutoCompletarUnidade.tamanhoMinimo = 3;

        objAutoCompletarUnidade.prepararExecucao = function () {
            return 'palavras_pesquisa=' + document.getElementById('txtUnidadeSolicitante').value;
        };

        objAutoCompletarUnidade.processarResultado = function (id, descricao) {
            if (id != '') {
                objLupaUnidade.adicionar(id, descricao, document.getElementById('txtUnidadeSolicitante'));
            }
        };
    }

    function validarCadastro() {
        document.getElementById('selUnidadeExpedidora').disabled = false;
        if (infraTrim(document.getElementById('selUnidadeExpedidora').value) == '') {
            alert('Informe a Unidade Expedidora.');
            document.getElementById('selUnidadeExpedidora').focus();
            return false;
        }
        if (document.getElementById('selUnidadeSolicitante').length == 0) {
            alert('Informe a Unidade Solicitante.');
            document.getElementById('txtUnidadeSolicitante').focus();
            document.getElementById('selUnidadeExpedidora').disabled = true;
            return false;
        }

        var encontrou = false;
        for (i = 0; i
        < document.getElementById('selUnidadeSolicitante').options.length; i++) {
            if (infraTrim(document.getElementById('selUnidadeExpedidora').value) == document.getElementById('selUnidadeSolicitante').options[i].value) {
                encontrou = true;
            }
        }
        if (encontrou) {
            alert('A Unidade Expedidora não pode ser Unidade Solicitante dela própria.');
            document.getElementById('txtUnidadeSolicitante').focus();
            document.getElementById('selUnidadeExpedidora').disabled = true;
            return false;
        }

        return true;
    }

    function OnSubmitForm() {
        return validarCadastro();
    }

</script>
