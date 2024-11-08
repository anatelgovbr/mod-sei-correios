<script type="text/javascript">
    var comboTipoCorrepondencia = '<?php echo json_encode($mdCorTipoCorrespondencia);?>'
    var tabelaDinamicaEditar = <?= in_array( $_GET['acao'] , ['md_cor_contrato_alterar','md_cor_contrato_cadastrar'] ) ? 'true' : 'false'?>;

    var tabelaDinamicaRemover = <?= in_array( $_GET['acao'] , ['md_cor_contrato_alterar','md_cor_contrato_cadastrar'] ) ? 'true' : 'false' ?>;

    /* Vari�veis usadas para montar o array de servi��es desativados */
    var arrLinhasDesativadas = Array();
    var arrLinhasReativadas = Array();
    var contadorDesativadas = 0;
    var contadorReativadas = 0;

    <? require_once('md_cor_contrato_cadastro_inicializacao.php');?>

    var objTabelaContratoServicos = null;
    var row = {};

    function tabelaDinamicaEventos() {
        $('.input-ar').click(function () {
            var $this = $(this);
            var val = $this.val();
            var $tr = $this.closest('tr');
            $tr.find('td:eq(2) div').html(val); //index
            objTabelaContratoServicos.alterarLinha($tr.index());
            objTabelaContratoServicos.atualizaHdn();
        });

        $('.input-desc').blur(function () {
            var $this = $(this);
            var val = $this.val();
            var $tr = $this.closest('tr');
            $tr.find('td:eq(2) div').html(val); //index
            objTabelaContratoServicos.alterarLinha($tr.index());
            objTabelaContratoServicos.atualizaHdn();
        });

    }

    function adicionarContratoServicos(xml) {
        var linhasTabela = objTabelaContratoServicos.tbl.rows.length;
        var rowRdS = rowRdN = '';
        var divIniRadio = '<div id="divRdoAr" class="infraDivRadio" align="center" style="width: 100%">';
        var divIniCheck = '<div id="divRdoAr" class="infraDivCheckbox checkCobrar"><div class="infraCheckboxDiv">';

        $(xml).find('ServicoPostal').each(function (index, val) {
            var indice = (linhasTabela - 1) + index;

            row = {};
            row.codigo = $(val).find('Codigo').text().trim();
            row.ar = '';
            row.desc = '';
            row.tipo = '<div style="width:100%;"><select id="slTipo_'+ indice +'" class="infraSelect form-control sl_tipo" name="sl_tipo[' + indice + ']" onchange="verificaAr(this)">' + comboTipoCorrepondencia + '</select></div>';
            row.nome = $(val).find('Descricao').text();
            rowRdS = '<div class="infraRadioDiv"><input class="infraRadioInput input-ar" type="radio" value="S" name="ar[' + indice + ']" id="arS[' + indice + ']"><label class="infraRadioLabel" for="arS['+ indice +']"></label></div><label id="lblArS['+ indice +']" for="arS['+ indice +']" class="infraLabelRadio" tabindex="<?= PaginaSEI::getInstance()->getProxTabDados() ?>">Sim</label>';
            rowRdN = '<div class="infraRadioDiv"><input class="infraRadioInput input-ar" type="radio" value="N" name="ar[' + indice + ']" id="arN[' + indice + ']" checked="checked"><label class="infraRadioLabel" for="arN['+ indice +']"></label></div><label id="lblArN['+ indice +']" for="arN['+ indice +']" class="infraLabelRadio" tabindex="<?= PaginaSEI::getInstance()->getProxTabDados() ?>">N�o</label>';
            row.check = divIniRadio + rowRdS + rowRdN + '</div>';
            row.cobrar = divIniCheck + '<input class="input-cobrar infraCheckboxInput" type="checkbox" value="S" name="cobrar[' + indice + ']" id="cobrar[' + indice + ']"><label class="infraCheckboxLabel " for="cobrar['+ indice +']"></label></div></div>';
            row.anexarMidia = '<div class="infraDivCheckbox" style="text-align: center"> <div class="infraCheckboxDiv"> <input type="checkbox" class="infraCheckboxInput" value="S" name="anexarMidia['+indice+']" id="anexarMidia['+indice+']"> <label class="infraCheckboxLabel" for="anexarMidia['+indice+']"></label> </div> </div>';
            row.txt = '<div><input type="text" id="idDesc_'+ indice +'" class="input-desc form-control" style="width: 100%" name="descricao[' + indice + ']" value=""><input type="hidden" name="codigo[' + indice + ']" value="' + $(val).find('Codigo').text() + '"><input type="hidden" name="nome[' + indice + ']" value="' + $(val).find('Descricao').text() + '"></div>';
            row.acoes = 'A��es';
            var bolContratoServicosCustomizado = 'hdnCustomizado';

            receberContratoServicos(row, bolContratoServicosCustomizado);
        });
    }

    function receberContratoServicos(row, ContratoServicosCustomizado) {
        var qtdContratoServicosIndicados = objTabelaContratoServicos.tbl.rows.length;
        objTabelaContratoServicos.exibirMensagens = false;
        objTabelaContratoServicos.adicionar([row.codigo, row.ar, row.desc, row.nome, row.tipo, row.check, row.cobrar, row.anexarMidia, row.txt, ''], false);
        infraEfeitoTabelas();
    }

    function inicializar() {

        $('#hdnListaContratoServicosIndicados').val('<?= $hdnListaContratoServicosIndicados;?>');

        objTabelaContratoServicos = new infraTabelaDinamica('tbContratoServicos', 'hdnListaContratoServicosIndicados', false, tabelaDinamicaRemover, false);

        objTabelaContratoServicos.gerarEfeitoTabela = true;
        objTabelaContratoServicos.inserirNoInicio = false;
        objTabelaContratoServicos.exibirMensagens = true;

        // Sobrescrevendo o m�todo para remover corretamente a linha com os itens de formulario
        objTabelaContratoServicos.removerLinhaCustom = objTabelaContratoServicos.removerLinha;
        objTabelaContratoServicos.removerLinha = function (rowIndex) {
            var tabela = document.getElementById('tbContratoServicos');

            // caso n�o seja um elemento incluido na tela verifica se pode ser excluido
            if (tabela.rows[rowIndex].cells[7].querySelector('#idMdCorServicoPostal')) {
                var idMdCorServicoPostal = tabela.rows[rowIndex].cells[7].querySelector('#idMdCorServicoPostal').value;
                var paramsAjax = {
                    idMdCorServicoPostal,
                };

                $.ajax({
                    url: '<?=$strUrlAjaxVerificaServicoMapeado?>',
                    type: 'POST',
                    dataType: 'XML',
                    data: paramsAjax,
                    beforeSend: function () {
                        processando();
                    },
                    complete: function () {
                        infraAvisoCancelar();
                    },
                    success: function (r) {
                        var response = $(r).find('ServicoMapeado').text();
                        if(response == 'false'){
                            objTabelaContratoServicos.removerLinhaCustom(rowIndex);
                            objTabelaContratoServicos.atualizaHdn();
                        } else {
                            alert($(r).find('Msg').text());
                        }
                    },
                    error: function (e) {
                        console.log('N�o foi poss�vel validar');
                    }
                });
            } else {
                objTabelaContratoServicos.removerLinhaCustom(rowIndex);
                objTabelaContratoServicos.atualizaHdn();
            }
        };


        /*
         Sobrescrevendo o m�todo  alterar para fazer o desativar
         */

        var tabela = document.getElementById('tbContratoServicos');
        var arrAux = Array();

        for (let i = 1; i < tabela.rows.length; i++) {

            var linha = tabela.rows[i];

            var coluna = tabela.rows[i].cells[9];

            var dirImg = '<?= PaginaSEI::getInstance()->getDiretorioSvgGlobal() ?>';


            if (lerCelula(tabela.rows[i].cells[1]) == 'S') {

                var imgDesativar = document.createElement('img');
                imgDesativar.src = dirImg + '/desativar.svg';
                imgDesativar.title = 'Desativar Item';
                imgDesativar.caption = 'Desativar Item';
                imgDesativar.className = 'infraImg';

                imgDesativar.onclick = function () {
                    objTabelaContratoServicos.alterarLinha(this.parentNode.parentNode.rowIndex, 'desativar');

                };

                coluna.appendChild(imgDesativar);
            } else {
                linha.style.backgroundColor = "#F39D9D";
                var imgAtivar = document.createElement('img');
                imgAtivar.src = dirImg + '/reativar.svg';
                imgAtivar.title = 'Reativar Item';
                imgAtivar.caption = 'Reativar Item';
                imgAtivar.className = 'infraImg';

                imgAtivar.onclick = function () {
                    objTabelaContratoServicos.alterarLinha(this.parentNode.parentNode.rowIndex, 'reativar');
                };
                coluna.appendChild(imgAtivar);
            }
        }

        objTabelaContratoServicos.alterarLinha = function (rowIndex, flag) {

            var i;
            var arrLinha = Array();
            var numColunas = tabela.rows[rowIndex].cells.length - 1;
            for (i = 0; i < numColunas; i++) {
                arrLinha[i] = infraRemoverFormatacaoXML(this.lerCelula(tabela.rows[rowIndex].cells[i]));
            }

            /*
              Solu��o paliativa afim de agrupar todos os id's a serem desativados dentro de um array. Tratar o mesmo em MdCorContratoRn
            */

            if (flag == "desativar") {
                tabela.rows[rowIndex].style.backgroundColor = "#F39D9D";
                arrLinhasDesativadas[contadorDesativadas] = arrLinha[0];
                $('#hdnListaContratoServicosDesativados').val(arrLinhasDesativadas);
                contadorDesativadas = contadorDesativadas + 1;
            } else if (flag == "reativar") {
                tabela.rows[rowIndex].style.backgroundColor = "#E4E4E4";
                arrLinhasReativadas[contadorReativadas] = arrLinha[0];
                $('#hdnListaContratoServicosReativadas').val(arrLinhasReativadas);
                contadorReativadas = contadorReativadas + 1;
            }
        };

        objTabelaContratoServicos.atualizaHdn();

        if ('<?=$_GET['acao']?>' == 'md_cor_contrato_cadastrar') {
            document.getElementById('txtNumeroContrato').focus();
        } else if ('<?=$_GET['acao']?>' == 'md_cor_contrato_consultar') {
            infraDesabilitarCamposAreaDados();
        } else {
            document.getElementById('btnCancelar').focus();
        }
        infraEfeitoTabelas();
        tabelaDinamicaEventos();
        
        <?php if($_GET['acao'] == 'md_cor_contrato_consultar'): ?>
            let tb = document.querySelector('#tbContratoServicos');
            [tb.rows].map( row => $( row ).find('th:last , td:last').css('display','none') );
        <?php endif; ?>
    }

    function limparNumeroProcedimento() {
        document.getElementById('hdnIdProcedimento').value = '';
    }

    function desativar() {
        document.getElementById('hdnIdProcedimento').value = '';
    }

    /**
     * Fun��es respons�veis pela valida��o do processo
     * @author: Wilton J�nior <wilton.junior@castgroup.com.br>
     * @since: 29/12/2016
     */
    function validarNumeroProcesso() {

        document.getElementById('hdnIdProcedimento').value = '';
        document.getElementById('hdnNumeroProcessoContratacao').value = '';
        document.getElementById('txtTipoProcessoContratacao').value = '';

        var numeroProcessoPreenchido = document.getElementById('txtNumeroProcessoContratacao').value != '';
        if (!numeroProcessoPreenchido) {
            exibirAlert('Informe o N�mero.','txtNumeroProcessoContratacao');
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

    function buscarServicosPostais() {

        if ( infraTrim( $('#txtNumeroContratoCorreio').val() ) == '' ) {
            exibirAlert('Informe o N�mero do Contrato nos Correios.','txtNumeroContratoCorreio');
            return false;
        }

        if ( infraTrim( $('#txtCNPJ').val() ) == '' ) {
            exibirAlert('Informe o CNPJ do Org�o do Contrato.','txtCNPJ');
            return false;
        }

        $.ajax({
            url: '<?=$strLinkAjaxBuscarServicosPostais?>',
            type: 'POST',
            dataType: 'XML',
            cache: false,
            data: $('form#frmMdCorContratoCadastro').serialize(),
            beforeSend: function () {
                processando();
            },
            complete: function () {
                infraAvisoCancelar();
            },
            success: function (xml) {
                if ($(xml).find("Erros").length > 0) {
                    exibirAlert( $(xml).find("Msg").text() );
                    return false;
                }
                else {
                    adicionarContratoServicos(xml);
                    objTabelaContratoServicos.atualizaHdn();
                    tabelaDinamicaEventos();
                }
            },
            error: function (e) {
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
            exibirAlert('Informe o N�mero do Contrato no �rg�o.','txtNumeroContrato');
            return false;
        }

        if (
            infraTrim(document.getElementById('hdnNumeroProcessoContratacao').value) != infraTrim(document.getElementById('txtNumeroProcessoContratacao').value)
            ||
            document.getElementById('txtNumeroProcessoContratacao').value == ''
        ){
            exibirAlert('N�mero do Processo de Contrata��o n�o foi validado.','txtNumeroProcessoContratacao');
            return false;
        }

        if (infraTrim(document.getElementById('txtNumeroContratoCorreio').value) == '') {
            exibirAlert('Informe o N�mero do Contrato nos Correios.','txtNumeroContratoCorreio');
            return false;
        }

        if (infraTrim(document.getElementById('txtNumeroCartaoPostagem').value) == '') {
            exibirAlert('Informe o Cart�o de Postagem dos Correios correspondente ao Contrato.','txtNumeroCartaoPostagem');
            return false;
        }

        if (infraTrim(document.getElementById('txtCNPJ').value) == '') {
            exibirAlert('Informe o CNPJ do Org�o do Contrato.','txtCNPJ');
            return false;
        }

        if (document.getElementById('hdnListaContratoServicosIndicados').value == '' ) {
            exibirAlert('A lista de Servi�os Postais est� vazia. Os Servi�os Postais ser�o carregados abaixo ap�s o fechamento desta mensagem.<br>Por favor, informar os dados necess�rios.','grid-validar-url');
            return false;
        }

        // validando a descricao do servico postal
        var erroDescricao = false;
        $('.input-desc').each(function (i, val) {
            if ($(val).val() == '') {
                erroDescricao = true;
                exibirAlert('Informe a Descri��o.', $(val).attr('id'));
                return false;
            }
        })

        if (erroDescricao) return false;

        var erroDescricao = false;
        $('.sl_tipo').each(function (i, val) {
            if ($(val).find(':selected').val() == 'null') {
                erroDescricao = true;
                exibirAlert('Informe um Tipo.', $(val).attr('id'));
                return false;
            }
        });

        if (erroDescricao) return false;

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

        if (erroDescricao) {
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