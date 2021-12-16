<script type="text/javascript" charset="iso-8859-1" src="/../../../infra_js/InfraUtil.js"></script>

<script type="text/javascript">


    var objAutoCompletarDestinatario = null;
    var objLupaDestinatarios = null;

    function inicializar() {

        objAutoCompletarDestinatario = new infraAjaxAutoCompletar('hdnIdDestinatarioNaoHabilitado', 'txtDestinatarioNaoHabilitado', '<?=$strLinkAjaxContatos?>');
        objAutoCompletarDestinatario.limparCampo = false;
        objAutoCompletarDestinatario.tamanhoMinimo = 3;

        objAutoCompletarDestinatario.prepararExecucao = function () {
            return 'palavras_pesquisa=' + encodeURIComponent(document.getElementById('txtDestinatarioNaoHabilitado').value);
        };

        objAutoCompletarDestinatario.processarResultado = function (id, descricao, complemento) {
            if (id != '') {
                objLupaDestinatarios.adicionar(id, descricao, document.getElementById('txtDestinatarioNaoHabilitado'));
            }
        };

        objLupaDestinatarios = new infraLupaSelect('selDestinatarioNaoHabilitado', 'hdnDestinatarios', '<?=$strLinkDestinatarios?>');
        infraAdicionarEvento(document.getElementById('txtDestinatarioNaoHabilitado'), 'keyup', infraGetCodigoTecla(tratarEnterDestinatarioNaoHabilitado));


        //////////TABELA DINAMICA
        objTabelaDestinatarioNaoHabilitado = new infraTabelaDinamica('tblDestinatarioNaoHabilitado', 'hdnTblDestinatarios', false, true);

        objTabelaDestinatarioNaoHabilitado.remover = function (arr) {
            return true;
        }

        objTabelaDestinatarioNaoHabilitado.gerarEfeitoTabela = true;

        <? foreach(array_keys($arrAcoes) as $id) { ?>
            objTabelaDestinatarioNaoHabilitado.adicionarAcoes('<?=$id?>', '<?=$arrAcoes[$id]?>');
        <? } ?>

        infraEfeitoTabelas();
    }

    function tratarEnterDestinatarioNaoHabilitado(ev) {
        infraGetCodigoTecla(ev);
    }

    function validarCadastro() {
        if (infraTrim(document.getElementById('txtNome').value) == '') {
            alert('Informe o Nome.');
            document.getElementById('txtNome').focus();
            return false;
        }
        return true;
    }

    function transportarDestinatario() {

        var arrIdDestinatario = new Array();

        if (validarCampoObrigatorio(arrIdDestinatario)) {
            if (getAjaxDestinatarioDuplicado(arrIdDestinatario)) {
                getAjaxDestinatario(arrIdDestinatario);
            }
            return false;
        }
    }

    function validarCampoObrigatorio(arrIdDestinatario) {

        $('#selDestinatarioNaoHabilitado option').each(function () {
            arrIdDestinatario.push($(this).val());
        });

        if (arrIdDestinatario.length == 0) {
            alert('Destinatário não informado.');
            document.getElementById('txtDestinatarioNaoHabilitado').focus();
            return false;
        }

        if (infraTrim($('#selContatoJustificativa').val()) == 'null') {
            alert('Justificativa não informada.');
            document.getElementById('selContatoJustificativa').focus();
            return false;
        }

        return true;
    }

    function getAjaxDestinatario(arrIdDestinatario) {
        $.ajax({
            type: "POST",
            url: "<?= $strLinkAjaxDestinatarioNaoHabilitado?>",
            dataType: "xml",
            data: {
                arrIdDestinatario: arrIdDestinatario
            },
            success: function (r) {

                var idJustificativa = infraGetElementById('selContatoJustificativa').value;
                var nomJustificativa = $('#selContatoJustificativa option:selected').text();

                $.each($(r).find('ContatoDTO'), function (key, value) {

                    var natureza = $(this).find('StaNatureza').text();
                    var idContato = $(this).find('IdContato').text();
                    var destinatario = $(this).find('Nome').text();

                    var cpf_cnpj = '';
                    var cpf = $(this).find('Cpf').text();
                    var cnpj = $(this).find('Cnpj').text();

                    if (infraTrim(cpf) != '') {
                        cpf_cnpj = formataCPF(zeroEsquerda(cpf, 11));
                    } else {
                        if (infraTrim(cnpj) != '') {
                            cpf_cnpj = formataCNPJ(zeroEsquerda(cnpj, 14));
                        }
                    }

                    natureza = (natureza == 'F') ? 'Pessoa Física' : 'Pessoa Jurídica'

                    objTabelaDestinatarioNaoHabilitado.adicionar([idContato, idJustificativa, destinatario, natureza, cpf_cnpj, nomJustificativa]);

                });

                limparCampos();
            },
            error: function (msgError) {
                msgCommit = "Erro ao processar o XML do SEI: " + msgError.responseText;
            }
        });
    }

    function getAjaxDestinatarioDuplicado(arrIdDestinatario) {

        var bool = true;
        $.ajax({
            type: "POST",
            url: "<?= $strLinkAjaxDestinatarioDuplicitadade?>",
            async: false,
            dataType: "xml",
            data: {
                arrIdDestinatario: arrIdDestinatario
            },
            success: function (r) {

                var msg = '';
                $.each($(r).find('MdCorRelContatoJustificativaDTO'), function (key, value) {
                    var destinatario = $(this).find('NomeContato').text();
                    msg += 'O Destinatário (a) ' + destinatario + ' já está cadastrado como não Habilitado para Expedção.\n';
                });

                if (infraTrim(msg) != '') {
                    bool = false;
                    alert(msg);
                }
            },
            error: function (msgError) {
                msgCommit = "Erro ao processar o XML do SEI: " + msgError.responseText;
                bool = false;
            }
        });

        return bool;
    }

    function limparCampos() {
        $('#selDestinatarioNaoHabilitado option[value]').remove();
        $('#selContatoJustificativa').val('');
    }

    function zeroEsquerda(numero, comprimento) {
        numero = numero.toString();
        while (numero.length < comprimento)
            numero = "0" + numero;
        return numero;
    }

    function formataCPF(cpf) {
        cpf = cpf.replace(/[^\d]/g, "");
        return cpf.replace(/(\d{3})(\d{3})(\d{3})(\d{2})/, "$1.$2.$3-$4");
    }

    function formataCNPJ(cnpj) {
        cnpj = cnpj.replace(/[^\d]/g, "");
        return cnpj.replace(/(\d{2})(\d{3})(\d{3})(\d{4})(\d{2})/, "$1.$2.$3/$4-$5");
    }

</script>