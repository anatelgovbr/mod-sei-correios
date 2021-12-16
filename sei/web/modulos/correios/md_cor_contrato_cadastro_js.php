<script type="text/javascript">
    var comboTipoCorrepondencia = '<?php echo json_encode($mdCorTipoCorrespondencia);?>'
    var tabelaDinamicaEditar = <? echo ($_GET['acao'] == 'md_cor_contrato_alterar' || $_GET['acao'] == 'md_cor_contrato_cadastrar') ? 'true' : 'false';  ?>;

    var tabelaDinamicaRemover = <? echo ($_GET['acao'] == 'md_cor_contrato_alterar') ? 'true' : 'false'; ?>;

    /*
    Variáveis usadas para montar o array de servições desativados
     */
    var arrLinhasDesativadas = Array();
    var arrLinhasReativadas = Array();
    var contadorDesativadas = 0;
    var contadorReativadas = 0;
    /*
     */

    <? require_once('md_cor_contrato_cadastro_inicializacao.php');?>

    var objTabelaContratoServicos = null;
    var row = {};

    function tabelaDinamicaEventos() {
        $('.input-ar').click(function () {
            var $this = $(this);
            var val = $this.val();
            //console.log(val);
            var $tr = $this.closest('tr');
            $tr.find('td:eq(2) div').html(val); //index
            //console.log($tr.index());
            objTabelaContratoServicos.alterarLinha($tr.index());
            objTabelaContratoServicos.atualizaHdn();
        });

        $('.input-desc').blur(function () {
            var $this = $(this);
            var val = $this.val();
            var $tr = $this.closest('tr');
            $tr.find('td:eq(3) div').html(val); //index
            //console.log($tr.index());
            objTabelaContratoServicos.alterarLinha($tr.index());
            objTabelaContratoServicos.atualizaHdn();
        });

    }

    function adicionarContratoServicos(xml) {
        var linhasTabela = objTabelaContratoServicos.tbl.rows.length;
        $(xml).find('ServicoPostal').each(function (index, val) {
            var indice = (linhasTabela - 1) + index;


            row = {};
            row.id = $(val).find('Id').text();
            row.codigo = $(val).find('Codigo').text();
            row.ar = '';
            row.desc = '';
            row.tipo = '<label><select class="infraSelect sl_tipo" id="sl_tipo" name="sl_tipo[' + indice + ']" onchange="verificaAr(this)">' + comboTipoCorrepondencia + '</select></label>';
            row.nome = $(val).find('Descricao').text();
            row.check = '<label><input class="input-ar" type="radio" value="S" name="ar[' + indice + ']"> Sim </label> <label> <input class="input-ar" type="radio" value="N" name="ar[' + indice + ']" checked="checked">Não</label>';
            row.cobrar = '<div class="checkCobrar"><label><input class="input-cobrar" type="checkbox" value="S" name="cobrar[' + indice + ']"></label></div>';
            row.txt = '<input class="input-desc" type="text" name="descricao[' + indice + ']" value=""><input type="hidden" name="id[' + indice + ']" value="' + $(val).find('Id').text() + '"><input class="input-desc" type="hidden" name="codigo[' + indice + ']" value="' + $(val).find('Codigo').text() + '"><input class="input-desc" type="hidden" name="nome[' + indice + ']" value="' + $(val).find('Descricao').text() + '">';
            row.acoes = 'Ações';
            var bolContratoServicosCustomizado = 'hdnCustomizado';

            receberContratoServicos(row, bolContratoServicosCustomizado);
        });
    }

    function receberContratoServicos(row, ContratoServicosCustomizado) {
        var qtdContratoServicosIndicados = objTabelaContratoServicos.tbl.rows.length;
        objTabelaContratoServicos.adicionar([row.id, row.codigo, row.ar, row.desc, row.nome, row.tipo, row.check, row.cobrar, row.txt, ''], false);
        infraEfeitoTabelas();
    }

    function inicializar() {

        $('#hdnListaContratoServicosIndicados').val('<?= $hdnListaContratoServicosIndicados;?>');
        //trVermelha

        objTabelaContratoServicos = new infraTabelaDinamica('tbContratoServicos', 'hdnListaContratoServicosIndicados', false, tabelaDinamicaRemover, false);


        objTabelaContratoServicos.gerarEfeitoTabela = true;
        objTabelaContratoServicos.inserirNoInicio = false;
        objTabelaContratoServicos.exibirMensagens = true;

        // Sobrescrevendo o método para remover corretamente a linha com os itens de formulario
        objTabelaContratoServicos.removerLinhaCustom = objTabelaContratoServicos.removerLinha;
        objTabelaContratoServicos.removerLinha = function (rowIndex) {
            objTabelaContratoServicos.removerLinhaCustom(rowIndex);
            objTabelaContratoServicos.atualizaHdn();
        };


        /*
         Sobrescrevendo o método  alterar para fazer o desativar
         */

        var tabela = document.getElementById('tbContratoServicos');
        var arrAux = Array();

        for (i = 1; i < tabela.rows.length; i++) {

            var linha = tabela.rows[i];
            var coluna = tabela.rows[i].cells[9];
            var dirImg = '<?= PaginaSEI::getInstance()->getDiretorioSvgGlobal() ?>';


            if (lerCelula(tabela.rows[i].cells[2]) == 'S') {

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
              Solução paliativa afim de agrupar todos os id's a serem desativados dentro de um array. Tratar o mesmo em MdCorContratoRn
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
    }

    function limparNumeroProcedimento() {
        document.getElementById('hdnIdProcedimento').value = '';
    }

    function desativar() {
        document.getElementById('hdnIdProcedimento').value = '';
    }

    /**
     * Funções responsáveis pela validação do processo
     * @author: Wilton Júnior <wilton.junior@castgroup.com.br>
     * @since: 29/12/2016
     */
    function validarNumeroProcesso() {

        document.getElementById('hdnIdProcedimento').value = '';
        document.getElementById('hdnNumeroProcessoContratacao').value = '';
        document.getElementById('lblTipoProcessoContratacao').innerHTML = '';

        var numeroProcessoPreenchido = document.getElementById('txtNumeroProcessoContratacao').value != '';
        if (!numeroProcessoPreenchido) {
            alert('Informe o Número.');
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
                    //inicializarCamposPadroesProcesso();
                    alert($(r).find('MensagemValidacao').text());
                } else {
                    document.getElementById('hdnIdProcedimento').value = $(r).find('IdProcedimento').text();
                    document.getElementById('lblTipoProcessoContratacao').innerHTML = $(r).find('TipoProcedimento').text();
                    document.getElementById('txtNumeroProcessoContratacao').value = $(r).find('numeroProcesso').text();
                    document.getElementById('hdnNumeroProcessoContratacao').value = document.getElementById('txtNumeroProcessoContratacao').value;
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

    function buscarServicosPostais() {

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
                if ($(xml).find("erros").length > 0) {
                    alert($(xml).find("erro").attr('descricao'));
                    return false;
                }
                else {
                    adicionarContratoServicos(xml);
                    objTabelaContratoServicos.atualizaHdn();
                    tabelaDinamicaEventos();
                }
            },
            error: function (e) {
                alert('3')
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
            alert('Informe o Número do Contrato no Órgão.');
            document.getElementById('txtNumeroContrato').focus();
            return false;
        }

        if (infraTrim(document.getElementById('hdnNumeroProcessoContratacao').value) != infraTrim(document.getElementById('txtNumeroProcessoContratacao').value)) {
            alert('Número do Processo de Contratação não foi validado.');
            document.getElementById('txtNumeroProcessoContratacao').focus();
            return false;
        }

        if (infraTrim(document.getElementById('txtNumeroContratoCorreio').value) == '') {
            alert('Informe o Número do Contrato nos Correios.');
            document.getElementById('txtNumeroContratoCorreio').focus();
            return false;
        }

        if (infraTrim(document.getElementById('txtNumeroAnoContratoCorreio').value) == '') {
            alert('Informe o Ano do Contrato nos Correios.');
            document.getElementById('txtNumeroAnoContratoCorreio').focus();
            return false;
        }

        if (infraTrim(document.getElementById('txtNumeroCartaoPostagem').value) == '') {
            alert('Informe o Cartão de Postagem dos Correios correspondente ao Contrato.');
            document.getElementById('txtNumeroCartaoPostagem').focus();
            return false;
        }

        if (infraTrim(document.getElementById('txtCNPJ').value) == '') {
            alert('Informe o CNPJ do Orgão do Contrato.');
            document.getElementById('txtCNPJ').focus();
            return false;
        }

        if (infraTrim(document.getElementById('txtCodigoAdministrativo').value) == '') {
            alert('Informe o Código Administrativo dos Correios correspondente ao Contrato.');
            document.getElementById('txtCodigoAdministrativo').focus();
            return false;
        }

        if (infraTrim(document.getElementById('txtUsuario').value) == '') {
            alert('Informe o Usuário do SIGEP WEB fornecido pelos Correios.');
            document.getElementById('txtUsuario').focus();
            return false;
        }

        if (infraTrim(document.getElementById('txtSenha').value) == '') {
            alert('Informe a Senha do SIGEP WEB fornecida pelos Correios.');
            document.getElementById('txtSenha').focus();
            return false;
        }

        if (infraTrim(document.getElementById('slCodigoDiretoria').value) == 'null') {
            alert('Informe o Código da Diretoria dos Correios correspondente ao Contrato.');
            document.getElementById('slCodigoDiretoria').focus();
            return false;
        }

        var inputDescList = document.getElementsByClassName('input-desc');
        if (inputDescList.length <= 0) {
            alert('Endereço WSDL do Web Service do SIGEP WEB não foi validado.');
			document.getElementById('txtUrlWebservice').focus();
            return false;
        }

        // validando a descricao do servico postal
        var erroDescricao = false;
        $('.input-desc').each(function (i, val) {
            if ($(val).val() == '') {
                erroDescricao = true;
                alert('Informe a Descrição.');
                $(val).focus();
                return false;
            }
        })

        if(erroDescricao)
            return false;

        var erroDescricao = false;
        $('.sl_tipo').each(function (i, val) {
            if ($(val).find(':selected').val() == 'null') {
                erroDescricao = true;
                alert('Informe um Tipo.');
                $(val).focus();
                return false;
            }
        });

        if(erroDescricao)
            return false;

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
                    //inicializarCamposPadroesProcesso();
                    alert($(r).find('MensagemValidacao').text());
                    flag = true;
                }
            },
            error: function (e) {
                if ($(e.responseText).find('MensagemValidacao').text()) {
                    //inicializarCamposPadroesProcesso();
                    alert($(e.responseText).find('MensagemValidacao').text());
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

        //timeoutExibirBotao = self.setTimeout('exibirBotao()',1000);

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