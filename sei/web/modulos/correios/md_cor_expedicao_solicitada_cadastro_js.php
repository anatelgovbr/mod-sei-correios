<script>
    // JavaScript da pagina de cadastro / solicitaao de expediao

    //objeto global da tabela dinamica da grid de formatos
    var objTabelaDinamicaFormatos = null;
    var stopTimer = 0;
    var timerId;

    function inicializar() {
        var formObject = $('#frmSolicitarExpedicao, #frmSolicitarExpedicao');
        formObject.data('original_serialized_form', formObject.serialize());
        var arrAcoes = new Array('md_cor_geracao_plp_listar', 'md_cor_expedicao_processo_listar');
        <?php
        $arrAcoes = [
            'md_cor_geracao_plp_listar',
            'md_cor_expedicao_processo_listar'
        ];
        if (!in_array($_GET['acao_origem'], $arrAcoes)) :
        ?>
        window.onbeforeunload = function() {
            if (formObject.data('original_serialized_form') !== formObject.serialize()) {
                return "As mudanças deste formulário não foram salvas. Saindo desta página, todas as mudanças serão perdidas.";
            }
        };
        <?php
        endif;
        $staAberto = true;
        if (isset($_GET['staAberto'])) {
            $staAberto = ($_GET['staAberto'] == 'S') ? false : true;
        }

        if($_GET['acao'] != 'md_cor_expedicao_solicitada_cadastrar'){
        $bolExistePLP = false;
        if ($dto) {
            $bolExistePLP = $dto->getNumIdMdCorPlp() != null ? true : false;
            if (SessaoSei::getInstance()->getNumIdUnidadeAtual() != $dto->getNumIdUnidade()) {
                $bolExistePLP = true;
            }
        }
        if (isset($_GET['visualizar']) || $bolExistePLP || $staAberto) { ?>
        infraDesabilitarCamposAreaDados();
        $("#imgAjuda").css("visibility", "visible");
        <?}
        }?>

        objTabelaDinamicaFormatos = new infraTabelaDinamica('tblFormatoExpedicao', 'hdnTbFormatos', false, true);
        objTabelaDinamicaFormatos.inserirNoInicio = false;

        //se for na acao de cadastrar, ja inserir na grid o documento principal
        //id, Documento, Formato da Expedição, Impressão, Justificativa
        //if ('<?= $_GET['acao'] ?>' == 'md_cor_expedicao_solicitada_cadastrar') {
        <?
        if (!isset($_POST['txaJustificativa'])) {
            //Anexo = extensao
            $bolExtensaoPermitida = 0;

            $objAnexoDTO = new AnexoDTO();
            $objAnexoDTO->retStrNome();
            $objAnexoDTO->setDblIdProtocolo($objProtocoloDocPrincipalDTO->getDblIdProtocolo());
            $objAnexoRN = new AnexoRN();

            //$arrObjAnexoDTO = $objAnexoRN->listarRN0218($objAnexoDTO);
            $arrObjAnexo = InfraArray::converterArrInfraDTO($objAnexoRN->listarRN0218($objAnexoDTO), 'Nome');

            if (count($arrObjAnexo) == 1) {
                $arrNome = explode('.', $arrObjAnexo[0]);
                $extensao = $arrNome[count($arrNome) - 1];

                //Extensões para Gravação em Mídia
                $objMdCorExtensaoMidiaDTO = new MdCorExtensaoMidiaDTO();
                $objMdCorExtensaoMidiaDTO->setStrNomeExtensao($extensao);
                $objMdCorExtensaoMidiaDTO->setBolExclusaoLogica(false);

                $objMdCorExtensaoMidiaDTO->retTodos(true);
                $objMdCorExtensaoMidiaRN = new MdCorExtensaoMidiaRN();
                $arrObjMdCorExtensaoMidiaDTO = $objMdCorExtensaoMidiaRN->listar($objMdCorExtensaoMidiaDTO);

                if (count($arrObjMdCorExtensaoMidiaDTO) > 0) {
                    $bolExtensaoPermitida = 1;
                }
            }
        }
        //Anexo = extensao - fim
        ?>

        //HTML para a coluna Formato
        var arrLinhaInicial = [];
        <?php if ($_GET['acao'] == 'md_cor_expedicao_solicitada_cadastrar') : ?>
        var id = '<?php echo $objProtocoloDocPrincipalDTO->getDblIdProtocolo() ?>';

        var formato = '';

        formato += '<div class="row">';
        formato += '    <div class="col-sm-12 col-md-12 col-lg-5 col-xl-5">';
        formato += '        <div id="divRdoImpressao" class="infraDivRadio">';
        formato += '            <div class="infraRadioDiv">';
        formato += '                <input type="radio" name="rdoFormato_' + id + '" id="rdoImpresso_' + id + '"';
        formato += '               value="<?php echo MdCorExpedicaoFormatoRN::$TP_FORMATO_IMPRESSO; ?>" checked="checked"';
        formato += '               class="infraRadioInput" onclick="impressaoMostrar()">';
        formato += '                <label class="infraRadioLabel" for="rdoImpresso_' + id + '"></label>';
        formato += '            </div>';
        formato += '            <label id="lblImpresso_' + id + '" for="rdoImpresso_' + id + '" class="infraLabelRadio lblImpresso_' + id + '" tabindex="507">Impresso</label>';
        formato += '        </div>';
        formato += '    </div>';
        formato += '    <div class="col-sm-12 col-md-12 col-lg-7 col-xl-7">';
        formato += '        <div id="divRdoGravacao" class="infraDivRadio">';
        formato += '            <div class="infraRadioDiv ">';
        formato += '                <input type="radio" name="rdoFormato_' + id + '" id="rdoFormato_' + id + '"';
        formato += '                value="<?php echo MdCorExpedicaoFormatoRN::$TP_FORMATO_MIDIA; ?>"';
        formato += '                disabled';
        formato += '                class="infraRadioInput" onclick="impressaoMostrar()">';
        formato += '                <label class="infraRadioLabel" for="rdoFormato_' + id + '"></label>';
        formato += '            </div>';
        formato += '        <label id="lblImpresso_' + id + '" for="rdoFormato_' + id + '" class="infraLabelRadio lblImpresso_' + id + '" tabindex="507">Gravação em Mídia</label>';
        formato += '    </div>';
        formato += '</div>';

        //HTML para a coluna impressao
        var impressao = '';

        impressao += '<div class="row">';
        impressao += '    <div class="col-sm-12 col-md-12 col-lg-5 col-xl-5">';
        impressao += '          <div id="divRdoImpressao" class="infraDivRadio">';
        impressao += '              <div class="infraRadioDiv ">';
        impressao += '                  <input type="radio" name="rdoImpressao_' + id + '" id="rdoImpressao1_' + id + '"';
        impressao += '                  value="<?php echo MdCorExpedicaoFormatoRN::$TP_IMPRESSAO_PRETO_BRANCO; ?>" checked="checked"';
        impressao += '                  class="infraRadioInput" onclick="justificativaImprimir(this)">';
        impressao += '              <label class="infraRadioLabel" for="rdoImpressao1_' + id + '"></label>';
        impressao += '              </div>';
        impressao += '              <label id="lblImpressao_' + id + '" for="rdoImpressao1_' + id + '" class="infraLabelRadio lblImpressao_' + id + '" tabindex="507">Preto e Branco</label>';
        impressao += '          </div>';
        impressao += '    </div>';
        impressao += '    <div class="col-sm-12 col-md-12 col-lg-7 col-xl-7">';
        impressao += '          <div id="divRdoGravacao" class="infraDivRadio ">';
        impressao += '              <div class="infraRadioDiv ">';
        impressao += '              <input type="radio" name="rdoImpressao_' + id + '" id="rdoImpressao2_' + id + '"';
        impressao += '               value="<?php echo MdCorExpedicaoFormatoRN::$TP_IMPRESSAO_COLORIDO; ?>"';
        impressao += '               class="infraRadioInput" onclick="justificativaImprimir(this)">';
        impressao += '              <label class="infraRadioLabel" for="rdoImpressao2_' + id + '"></label>';
        impressao += '          </div>';
        impressao += '          <label id="lblImpressao_' + id + '" for="rdoImpressao2_' + id + '" class="infraLabelRadio lblImpressao_' + id + '" tabindex="507">Colorido</label>';
        impressao += '    </div>';
        impressao += '</div>';

        //HTML para a coluna justificativa
        var justificativa = '<textarea name="txtJustificativa[' + id + ']" class="infraTextArea form-control" style="width: 100%;" disabled="disabled"></textarea>';


        arrLinhaInicial = [
            '<?php echo $objProtocoloDocPrincipalDTO->getDblIdProtocolo() . '#' . $bolExtensaoPermitida; ?>',
            '<?php echo $descricao_documento_principal; ?> - Principal',
            formato, //html aqui
            impressao, //html aqui
            justificativa //justificativa html aqui
        ];
        objTabelaDinamicaFormatos.adicionar(arrLinhaInicial);
        <?php else: ?>

        <?php
        $bolExistePLP = false;
        if ($dto) {
            $bolExistePLP = $dto->getNumIdMdCorPlp() == null ? true : false;
        }
        if (!isset($_GET['visualizar']) && $bolExistePLP) {
        foreach ($arrFormatos as $formatoDTO) {
        ?>
        var visualizarListagemPLP = <?php echo ($_GET['acao_origem'] == 'md_cor_geracao_plp_listar') ? "disabled='disabled'" : "''"; ?>;
        var existeTipoMidiaValido = verificarAnexoMidia(<?php echo $formatoDTO->getDblIdProtocolo(); ?>);
        var checkImpressao = existeTipoMidiaValido == 'false' ? "" : "disabled='disabled'";
        var id = '<?php echo $formatoDTO->getDblIdProtocolo() ?>';

        var formato = '';

        formato += '<div class="row">';
        formato += '    <div class="col-sm-12 col-md-12 col-lg-5 col-xl-5">';
        formato += '        <div id="divRdoImpressao" class="infraDivRadio">';
        formato += '            <div class="infraRadioDiv ">';
        formato += '                <input id="rdoImpresso_' + id + '" <?php if ($formatoDTO->getStrFormaExpedicao() == MdCorExpedicaoFormatoRN::$TP_FORMATO_IMPRESSO) echo 'checked'; ?> '+ checkImpressao +'  value="<?php echo MdCorExpedicaoFormatoRN::$TP_FORMATO_IMPRESSO; ?>" type="radio" name="rdoFormato_' + id + '" class="infraRadioInput" onclick="impressaoMostrar()" ' + visualizarListagemPLP +'>';
        formato += '                <label class="infraRadioLabel" for="rdoImpresso_' + id + '"></label>';
        formato += '            </div>';
        formato += '            <label id="lblImpresso_' + id + '" for="rdoImpresso_' + id + '" class="infraLabelRadio lblImpresso_' + id + '" tabindex="507">Impresso</label>';
        formato += '        </div>';
        formato += '    </div>';
        formato += '    <div class="col-sm-12 col-md-12 col-lg-7 col-xl-7">';
        formato += '        <div id="divRdoGravacao" class="infraDivRadio">';
        formato += '            <div class="infraRadioDiv ">';
        formato += '            <input id="rdoFormato_' + id + '" <?php if ($formatoDTO->getStrFormaExpedicao() == MdCorExpedicaoFormatoRN::$TP_FORMATO_MIDIA) echo 'checked'; ?>  value="<?php echo MdCorExpedicaoFormatoRN::$TP_FORMATO_MIDIA; ?>" type="radio" name="rdoFormato_' + id + '" class="infraRadioInput" onclick="impressaoMostrar()" ' + visualizarListagemPLP ;
        <?php if ($numeroProtocoloFormatado == $formatoDTO->getStrProtocoloFormatado()) { ?>
        formato += ' disabled=disabled';
        <?php } ?>
        formato += '>';
        formato += '            <label class="infraRadioLabel" for="rdoFormato_' + id + '"></label>';
        formato += '        </div>';
        formato += '        <label id="lblImpresso_' + id + '" for="rdoFormato_' + id + '" class="infraLabelRadio lblImpresso_' + id + '" tabindex="507">Gravação em Mídia</label>';
        formato += '    </div>';
        formato += '</div>';

        var impressaoMostrar = "display:none;";
        <?php if ($formatoDTO->getStrFormaExpedicao() != MdCorExpedicaoFormatoRN::$TP_FORMATO_MIDIA) { ?>
        var impressaoMostrar = "";
        <?php } ?>

        var impressao = '';

        impressao += '<div class="row">';
        impressao += '    <div class="col-sm-12 col-md-12 col-lg-5 col-xl-5">';
        impressao += '          <div id="divRdoImpressao" class="infraDivRadio" style="'+impressaoMostrar+'">';
        impressao += '              <div class="infraRadioDiv ">';
        impressao += '              <input id="rdoImpressao1_' + id + '" <?php if ($formatoDTO->getStrImpressao() == MdCorExpedicaoFormatoRN::$TP_IMPRESSAO_PRETO_BRANCO) echo 'checked'; ?> value="<?php echo MdCorExpedicaoFormatoRN::$TP_IMPRESSAO_PRETO_BRANCO; ?>" type="radio" name="rdoImpressao_' + id + '" onclick="justificativaImprimir(this)"' + visualizarListagemPLP + '>';
        impressao += '              <label class="infraRadioLabel" for="rdoImpressao1_' + id + '"></label>';
        impressao += '              </div>';
        impressao += '              <label id="lblImpressao_' + id + '" for="rdoImpressao1_' + id + '" class="infraLabelRadio lblImpressao_' + id + '" tabindex="507">Preto e Branco</label>';
        impressao += '          </div>';
        impressao += '    </div>';
        impressao += '    <div class="col-sm-12 col-md-12 col-lg-7 col-xl-7">';
        impressao += '          <div id="divRdoGravacao" class="infraDivRadio"  style="'+impressaoMostrar+'">';
        impressao += '              <div class="infraRadioDiv ">';
        impressao += '              <input id="rdoImpressao2_' + id + '" <?php if ($formatoDTO->getStrImpressao() == MdCorExpedicaoFormatoRN::$TP_IMPRESSAO_COLORIDO) echo 'checked'; ?> value="<?php echo MdCorExpedicaoFormatoRN::$TP_IMPRESSAO_COLORIDO; ?>" type="radio" name="rdoImpressao_' + id + '" onclick="justificativaImprimir(this)" ' + visualizarListagemPLP + '>';
        impressao += '              <label class="infraRadioLabel" for="rdoImpressao2_' + id + '"></label>';
        impressao += '          </div>';
        impressao += '          <label id="lblImpressao_' + id + '" for="rdoImpressao2_' + id + '" class="infraLabelRadio lblImpressao_' + id + '" tabindex="507">Colorido</label>';
        impressao += '    </div>';
        impressao += '</div>';

        <?php
        if ($formatoDTO->getStrImpressao() == MdCorExpedicaoFormatoRN::$TP_IMPRESSAO_COLORIDO) {
            $disabled = "";
        } elseif ($formatoDTO->getStrFormaExpedicao() == MdCorExpedicaoFormatoRN::$TP_FORMATO_MIDIA) {
            $disabled = "";
        } else {
            $disabled = 'disabled="disabled"';
        }
        ?>

        //HTML para a coluna justificativa
        var justificativa = '<textarea name="txtJustificativa[' + id + ']" ' + checkImpressao + ' class="infraTextArea form-control" style="width: 100%;" value="<?php echo $formatoDTO->getStrJustificativa(); ?>" <?php echo $disabled; ?> ' + visualizarListagemPLP + '><?php echo $formatoDTO->getStrJustificativa(); ?></textarea>';

        arrLinhaInicial = [
            <?php if ($numeroProtocoloFormatado == $formatoDTO->getStrProtocoloFormatado()) { ?>
            '<?php echo $formatoDTO->getDblIdProtocolo() . '#' . $bolExtensaoPermitida; ?>'
            <?php } else { ?>
            '<?php echo $formatoDTO->getDblIdProtocolo(); ?>'
            <?php } ?>,
            '<?php

                $strUrlDocumento = SessaoSEI::getInstance()->assinarLink('controlador.php?acao=procedimento_trabalhar&id_documento=' . $formatoDTO->getDblIdDocumentoPrincipal());
                $strNomeAnexo = $formatoDTO->getStrNomeSerie() ? $formatoDTO->getStrNomeSerie() . ' ' . $formatoDTO->getStrNumeroDocumento() : $formatoDTO->getStrNomeProcedimento();
                echo  $strNomeAnexo . '<a class="protocoloNormal" style="font-size: 1.0em !important; font-size:1em" href="'.$strUrlDocumento.'" target="_blank">(' . $formatoDTO->getStrProtocoloFormatado() . ')</a>';

                if ($numeroProtocoloFormatado == $formatoDTO->getStrProtocoloFormatado()) {
                    echo " - Principal";
                }else{
                    echo " - Anexo";
                }
                ?>',
            formato, //html aqui
            impressao, //html aqui
            justificativa //justificativa html aqui
        ];

        objTabelaDinamicaFormatos.adicionar(arrLinhaInicial);
        <?php
        }
        }
        ?>
        <?php endif; ?>

        //adiciona nova item na tabela dinamica de formatos
        objTabelaDinamicaFormatos.procuraLinha = function (id) {
            var qtd;
            var linha;
            //qtd = document.getElementById('tblFormatoExpedicao').rows.length;
            qtd = objTabelaDinamicaFormatos.tbl.rows.length;
            for (i = 1; i < qtd; i++) {
                linha = objTabelaDinamicaFormatos.tbl.rows[i];
                if (linha.cells[0].innerText == id) {
                    return i;
                }
            }
            return null;
        };

        objAutoCompletarProtocoloAnexo = new infraAjaxAutoCompletar('hdnIdProtocoloAnexo', 'txtProtocoloAnexo', '<?= $strLinkAjaxProtocoloAnexo ?>');

        objAutoCompletarProtocoloAnexo.limparCampo = true;

        objAutoCompletarProtocoloAnexo.prepararExecucao = function () {
            return 'id_doc=<?php echo $id_doc; ?>&palavras_pesquisa=' + document.getElementById('txtProtocoloAnexo').value;
        };

        objAutoCompletarProtocoloAnexo.processarResultado = function (id, descricao, complemento) {

            //REGRAS DESATUALIZADAS, POIS AUTOCOMPLETE FOI DESATIVADO
            if (id != '') {

                var options = document.getElementById('selProtocoloAnexo').options;

                for (var i = 0; i < options.length; i++) {
                    if (options[i].value == id) {
                        alert('Protocolo Anexo já consta na lista.');
                        break;
                    }
                }

                if (i == options.length) {
                    /*************/
                    for (i = 0; i < options.length; i++) {
                        options[i].selected = false;
                    }

                    opt = infraSelectAdicionarOption(document.getElementById('selProtocoloAnexo'), descricao, id);
                    objLupaProtocoloAnexo.atualizar();
                    opt.selected = true;

                    //HTML para a coluna Formato
                    var formato = '';

                    formato += '<label for="rdoImpresso_' + id + '" class="infraLabelRadio">';
                    formato += '<input id="rdoImpresso_' + id + '" value="<?php echo MdCorExpedicaoFormatoRN::$TP_FORMATO_IMPRESSO; ?>" type="radio" name="rdoFormato_' + id + '" class="infraRadioInput" onclick="impressaoMostrar()">Impresso';
                    formato += '</label>';

                    formato += '<label for="rdoFormato_' + id + '" class="infraLabelRadio">';
                    formato += '<input id="rdoFormato_' + id + '" value="<?php echo MdCorExpedicaoFormatoRN::$TP_FORMATO_MIDIA; ?>" type="radio" name="rdoFormato_' + id + '" class="infraRadioInput" onclick="impressaoMostrar()">Gravao em Mdia';
                    formato += '</label>';

                    //HTML para a coluna impressao
                    var impressao = '';

                    impressao += '<label for="rdoImpressao1_' + id + '" class="infraLabelRadio">';
                    impressao += '<input id="rdoImpressao1_' + id + '" value="<?php echo MdCorExpedicaoFormatoRN::$TP_IMPRESSAO_PRETO_BRANCO; ?>" type="radio"  class="infraRadioInput" name="rdoImpressao_' + id + '">Preto e Branco';
                    impressao += '</label>';

                    impressao += '<label for="rdoImpressao2_' + id + '" class="infraLabelRadio">';
                    impressao += '<input id="rdoImpressao2_' + id + '" value="<?php echo MdCorExpedicaoFormatoRN::$TP_IMPRESSAO_COLORIDO; ?>" type="radio" class="infraRadioInput" name="rdoImpressao_' + id + '">Colorido';
                    impressao += '</label>';

                    //HTML para a coluna justificativa
                    var justificativa = '<textarea name="txtJustificativa[' + id + ']" class="infraTextArea form-control" style="width: 100%;"></textarea>';

                    //id, Documento, Formato da Expedição, Impressão, Justificativa
                    var arrLinha = [
                        id,
                        descricao,
                        formato, //html aqui
                        impressao, //html aqui
                        justificativa //html aqui
                    ];

                    //adiciona nova item na tabela dinamica de formatos
                    objTabelaDinamicaFormatos.adicionar(arrLinha);
                    //objTabelaDinamicaFormatos.adicionarAcoes(id, "", false, false);

                    /*************/
                }

                document.getElementById('txtProtocoloAnexo').value = '';
                document.getElementById('txtProtocoloAnexo').focus();
            }
        };

        objLupaProtocoloAnexo = new infraLupaSelect('selProtocoloAnexo', 'hdnProtocoloAnexo', '<?= $strLinkPopUpSelecaoProtocoloAnexo ?>');

        // Sobrescrevendo o método para remover corretamente a linha com os itens de formulario
        objLupaProtocoloAnexo.processarRemocao = function (valor) {
            removerProtocoloAnexo(valor);
            objTabelaDinamicaFormatos.atualizaHdn();
            return true;
        };

        //evento para pegar insercao de options na combo
        AddEventToSelect(document.getElementById('selProtocoloAnexo'), 'I');
        AddEventToSelect(document.getElementById('selContato'), 'M');

        marcarChkDocumentoPossuiAnexo();

    }

    function justificativaImprimir(campo, isMidia) {
        var indice = campo.getAttribute('name').substr(13);
        document.getElementsByName('txtJustificativa[' + indice + ']')[0].setAttribute('disabled', 'disabled');
        document.getElementsByName('txtJustificativa[' + indice + ']')[0].value = '';

        if (campo.value == 'C') {
            document.getElementsByName('txtJustificativa[' + indice + ']')[0].removeAttribute('disabled');
        }
    }

    function AddEventToSelect(elem, option) {
        if (option == 'I') {
            if (elem.addEventListener) {
                elem.addEventListener('DOMNodeInserted', OnNodeInserted, false);
            }
        } else if (option == 'M') {
            if (elem.addEventListener) {
                elem.addEventListener('DOMCharacterDataModified', OnNodeModified, false);
            }
        }
    }

    function OnNodeModified(event) {


    }

    function OnNodeInserted(event) {
        var elemento = event.target;
        var idCompleto = elemento.value;
        var id = idCompleto.split("#")[0];
        var extensaopermitida = idCompleto.split("#")[1];
        var descricao = elemento.text;
        var options = document.getElementById('selProtocoloAnexo').options;
        var existeTipoMidiaValido = verificarAnexoMidia(idCompleto);


        for (i = 0; i < options.length; i++) {
            options[i].selected = false;
        }

        var formato = '';

        var checkImpressao = existeTipoMidiaValido == 'false' ? "checked='checked' " : "disabled='disabled'";
        var checkMidia = existeTipoMidiaValido == 'true' ? "checked='checked'" : '';

        formato += '<div class="row">';
        formato += '    <div class="col-sm-12 col-md-12 col-lg-5 col-xl-5">';
        formato += '        <div id="divRdoImpressao" class="infraDivRadio">';
        formato += '            <div class="infraRadioDiv ">';
        formato += '                <input type="radio" name="rdoFormato_' + id + '" id="rdoImpresso_' + id + '"';
        formato += '                value="<?php echo MdCorExpedicaoFormatoRN::$TP_FORMATO_IMPRESSO; ?>" '+checkImpressao;
        formato += '               class="infraRadioInput" onclick="impressaoMostrar(' + existeTipoMidiaValido + ')">';
        formato += '                <label class="infraRadioLabel" for="rdoImpresso_' + id + '"></label>';
        formato += '            </div>';
        formato += '        <label id="lblImpresso_' + id + '" for="rdoImpresso_' + id + '" class="infraLabelRadio" tabindex="507">Impresso</label>';
        formato += '        </div>';
        formato += '    </div>';
        formato += '    <div class="col-sm-12 col-md-12 col-lg-7 col-xl-7">';
        formato += '        <div id="divRdoGravacao" class="infraDivRadio">';
        formato += '        <div class="infraRadioDiv ">';
        formato += '            <input type="radio" name="rdoFormato_' + id + '" id="rdoFormato_' + id + '"';
        formato += '               value="<?php echo MdCorExpedicaoFormatoRN::$TP_FORMATO_MIDIA; ?>" '+checkMidia;
        formato += '               class="infraRadioInput" onclick="impressaoMostrar(' + existeTipoMidiaValido + ')">';
        formato += '            <label class="infraRadioLabel" for="rdoFormato_' + id + '"></label>';
        formato += '        </div>';
        formato += '            <label id="lblImpresso_' + id + '" for="rdoFormato_' + id + '" class="infraLabelRadio" tabindex="507">Gravação em Mídia</label>';
        formato += '     </div>';
        formato += '</div>';

        //HTML para a coluna impressao
        var impressao = '';

        impressao += '<div class="row">';
        impressao += '    <div class="col-sm-12 col-md-12 col-lg-5 col-xl-5">';
        impressao += '        <div id="divRdoImpressao" class="infraDivRadio">';
        impressao += '            <div class="infraRadioDiv ">';
        impressao += '                  <input type="radio" name="rdoImpressao_' + id + '" id="rdoImpressao1_' + id + '"';
        impressao += '                  value="<?php echo MdCorExpedicaoFormatoRN::$TP_IMPRESSAO_PRETO_BRANCO; ?>" checked="checked"';
        impressao += '                  onclick="justificativaImprimir(this)"';
        if (extensaopermitida == '1') {
            impressao += ' disabled';
        }
        impressao += '                  class="infraRadioInput">';
        impressao += '                  <label class="infraRadioLabel" for="rdoImpressao1_' + id + '"></label>';
        impressao += '            </div>';
        impressao += '            <label id="lblImpressao_' + id + '" for="rdoImpressao1_' + id + '" class="infraLabelRadio lblImpressao_' + id + '" tabindex="507">Preto e Branco</label>';
        impressao += '        </div>';
        impressao += '    </div>';
        impressao += '    <div class="col-sm-12 col-md-12 col-lg-7 col-xl-7">';
        impressao += '        <div id="divRdoGravacao" class="infraDivRadio">';
        impressao += '        <div class="infraRadioDiv ">';
        impressao += '            <input type="radio" name="rdoImpressao_' + id + '" id="rdoImpressao2_' + id + '"';
        impressao += '               value="<?php echo MdCorExpedicaoFormatoRN::$TP_IMPRESSAO_COLORIDO; ?>"';
        impressao += '               onclick="justificativaImprimir(this)"';
        if (extensaopermitida == '1' ) {
            impressao += ' disabled';
        }
        impressao += '               class="infraRadioInput">';
        impressao += '              <label class="infraRadioLabel" for="rdoImpressao2_' + id + '"></label>';
        impressao += '        </div>';
        impressao += '          <label id="lblImpressao_' + id + '" for="rdoImpressao2_' + id + '" class="infraLabelRadio lblImpressao_' + id + '" tabindex="507">Colorido</label>';
        impressao += '     </div>';
        impressao += '</div>';

        //HTML para a coluna justificativa
        var justificativa = '<textarea name="txtJustificativa[' + id + ']" class="infraTextArea form-control" style="width: 100%;" disabled="disabled"></textarea>';

        //id, Documento, Formato da Expedição, Impressão, Justificativa
        var arrLinha = [
            idCompleto,
            descricao,
            formato, //html aqui
            impressao, //html aqui
            justificativa //html aqui
        ];

        //adiciona nova item na tabela dinamica de formatos
        objTabelaDinamicaFormatos.adicionar(arrLinha);
        //objTabelaDinamicaFormatos.adicionarAcoes(id, "", false, false);

        impressaoMostrar(existeTipoMidiaValido);
    }

    function validarFormulario(acao) {

        if (acao == 'excluir') {

            var confirmar = confirm('Deseja realmente excluir esta Solicitação?');
            if (confirmar == false) {
                return;
            }
            var strAcaoForm = '<?php echo SessaoSEI::getInstance()->assinarLink('controlador.php?acao=md_cor_expedicao_solicitada_excluir&acao_origem=' . $_GET['acao']); ?>';
            document.getElementById('frmSolicitarExpedicao').action = strAcaoForm;
            var txaJustificativa = document.getElementById('txaJustificativa').value;

            if (txaJustificativa == '') {

                alert('Informe a justificativa.');
                document.getElementById('txaJustificativa').focus();
                return;

            }
        } else {
            if (acao == 'alterar') {

                var strAcaoForm = '<?php echo SessaoSEI::getInstance()->assinarLink('controlador.php?acao=md_cor_expedicao_solicitada_alterar&acao_origem=' . $_GET['acao']); ?>';
                document.getElementById('frmSolicitarExpedicao').action = strAcaoForm;
                var txaJustificativa = document.getElementById('txaJustificativa').value;

                if (txaJustificativa == '') {

                    alert('Informe a justificativa.');
                    document.getElementById('txaJustificativa').focus();
                    return;

                }
                var confirmar = confirm('Deseja realmente alterar esta Solicitação?');
                if (confirmar == false) {
                    return;
                }
                document.getElementsByClassName('documentoPrincipal').disabled = false;

            }
            var selServicoPostal = document.getElementById('selServicoPostal').value;
            var selProtocoloAnexo = document.getElementById('selProtocoloAnexo').options.length;
            var chkDocumentoPossuiAnexo = document.getElementById('chkDocumentoPossuiAnexo');
            var arrSelect = ['<?= $id_doc ?>#0'];

            if (selServicoPostal == '' || selServicoPostal == 'null') {

                alert('Informe o serviço postal.');
                document.getElementById('selServicoPostal').focus();
                return;

            } else if (chkDocumentoPossuiAnexo.checked && selProtocoloAnexo == 0) {

                alert('Informe ao menos um protocolo anexo.');
                document.getElementById('selProtocoloAnexo').focus();
                return;

            } else if (chkDocumentoPossuiAnexo.checked && selProtocoloAnexo > 0) {

                var arrSelect2 = getSelectValues(document.getElementById('selProtocoloAnexo'), true);
                arrSelect = arrSelect.concat(arrSelect2);

            }

            //campos justificativa
            var camposJustificativa = document.querySelectorAll('textarea[name^="txtJustificativa"]');

            for (var i = 0; i < arrSelect.length; i++) {

                var valorIdProtocoloAnexo = arrSelect[i].split("#")[0];

                //verificar se preencheu o formato da expediçao
                var camposFormato = document.getElementsByName('rdoFormato_' + valorIdProtocoloAnexo);
                if (!camposFormato[0].checked && !camposFormato[1].checked) {
                    alert('Seleciona o formato da expedição.');
                    camposFormato[0].focus();
                    return;
                }

                //verificar se preencheu a impressao
                var camposImpressao = document.getElementsByName('rdoImpressao_' + valorIdProtocoloAnexo);
                if (camposFormato[0].checked && (!camposImpressao[0].checked && !camposImpressao[1].checked)) {
                    alert('Seleciona o tipo de impressão.');
                    camposImpressao[0].focus();
                    return;
                }

                // Gravação em Mídia Opcional ou Impresso - Colorido
                if (((camposFormato[0].disabled == false && camposFormato[1].checked)
                    || camposImpressao[1].checked)
                    && camposJustificativa[i].value == '') {
                    alert('Informe a justificativa.');
                    camposJustificativa[i].focus();
                    return;
                }
            }

            infraSelecaoMultiplaMarcarTodos('selProtocoloAnexo', true);

            //chegando aqui é porque passou em todas as validações , esta tudo OK e pode submeter o cadastro
        }
        var retorno = validarDestinatario();
        console.log(retorno);
        if(retorno) {
            window.onbeforeunload = null;
            document.getElementById('frmSolicitarExpedicao').submit();
        }
    }

    function validarDestinatario(){
        var retorno = true;
        var id_contato = $('#selContato').val();
        $.ajax({
            url: '<?= $strLinkValidaoDestinatarioExpedicaoSolicitada ?>',
            type: 'POST',
            data: {'id_contato': id_contato},
            async: false,
            // contentType  : "application/json",
            success: function (r) {
                var flag = $(r).find('flag').text();
                if(flag == 'false'){
                    alert($(r).find('mensagem').text());
                    retorno = false;
                } else if($(r).find('erros').length > 0){
                    alert($(r).find('erro').attr('descricao'));
                    retorno = false;
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
        return retorno
    }


    //Retorna um array de valores dos options selecionados
    function getSelectValues(select, selected) {

        var result = [];
        var options = select && select.options;
        var opt;

        for (var i = 0, iLen = options.length; i < iLen; i++) {
            opt = options[i];

            if (selected == undefined && opt.selected) {
                result.push(opt.value || opt.text);
            } else {
                result.push(opt.value || opt.text);
            }
        }
        return result;

    }

    function removerProtocoloAnexo(valor) {
        for (var i = 0, iLen = valor.length; i < iLen; i++) {
            objTabelaDinamicaFormatos.removerLinha(objTabelaDinamicaFormatos.procuraLinha(valor[i].value));
        }
    }

    function marcarChkDocumentoPossuiAnexo() {
        var checkbox = document.getElementById('chkDocumentoPossuiAnexo');
        var div1 = document.getElementById('divProtocoloAnexo');
        var div2 = document.getElementById('divProtocoloAnexo2');

        if (checkbox.checked) {
            div1.style.display = 'block';
            div2.style.display = 'block';
        } else {
            div1.style.display = 'none';
            div2.style.display = 'none';
        }

    }

    function impressaoMostrar() {
        var arrSelect = getSelectValues(document.getElementById('selProtocoloAnexo'), false);

        for (var i = 0; i < arrSelect.length; i++) {
            var valorIdProtocoloAnexo = arrSelect[i].split("#")[0];
            var camposFormato = document.getElementsByName('rdoFormato_' + valorIdProtocoloAnexo);
            var camposImpressao = document.getElementsByName('rdoImpressao_' + valorIdProtocoloAnexo);
            var labelsImpressao = document.getElementsByClassName('lblImpressao_' + valorIdProtocoloAnexo);
            var desabilitado = camposFormato[0].disabled;

            if (camposFormato[0].checked || camposFormato[1].checked) {
                if (camposFormato[0].checked) {
                    impressaoHabilitar(camposFormato[0], false, desabilitado);
                }
                if (camposFormato[1].checked) {
                    impressaoHabilitar(camposFormato[1], true, desabilitado);
                }
                camposImpressao[0].style.display = '';
                labelsImpressao[0].style.display = '';
                camposImpressao[1].style.display = '';
                labelsImpressao[1].style.display = '';
            } else {
                camposImpressao[0].style.display = 'none';
                labelsImpressao[0].style.display = 'none';
                camposImpressao[1].style.display = 'none';
                labelsImpressao[1].style.display = 'none';
            }
        }
    }

    function impressaoHabilitar(objeto, opcao, isMidia) {
        var camposImpressao = document.getElementsByName(objeto.name.replace("rdoFormato_", "rdoImpressao_"));

        var desabilitado = camposImpressao[0].disabled
        var indice = camposImpressao[0].getAttribute('name').substr('13');
        camposImpressao[0].disabled = opcao;
        camposImpressao[1].disabled = opcao;
        camposImpressao[0].parentElement.parentElement.style.display = ''
        camposImpressao[1].parentElement.parentElement.style.display = ''

        document.getElementsByName('txtJustificativa[' + indice + ']')[0].setAttribute('disabled', true);
        document.getElementsByName('txtJustificativa[' + indice + ']')[0].value = '';
        if (opcao){
            document.getElementsByName('txtJustificativa[' + indice + ']')[0].removeAttribute('disabled');
            camposImpressao[0].checked = false;
            camposImpressao[1].checked = false;
            camposImpressao[0].parentElement.parentElement.style.display = 'none'
            camposImpressao[1].parentElement.parentElement.style.display = 'none'
        } else {
            camposImpressao[0].checked = true;
        }
        if(isMidia){
            document.getElementsByName('txtJustificativa[' + indice + ']')[0].setAttribute('disabled','disabled');
            document.getElementsByName('txtJustificativa[' + indice + ']')[0].value = '';
        }
    }

    function verificarAnexoMidia(idCompleto){
        var retorno = "";
        $.ajax({
            url: '<?= $strLinkAjaxFormatoExpedicaoApenasMidia ?>',
            type: 'POST',
            data: {'idProtocolo': idCompleto},
            async: false,
            success: function (r) {
                retorno = $(r).find('retorno').text();
            },
            error: function (e) {
                if ($(e.responseText).find('MensagemValidacao').text()) {
                    //inicializarCamposPadroesProcesso();
                    alert($(e.responseText).find('MensagemValidacao').text());
                }
                console.error('Erro ao processar o XML do SEI: ' + e.responseText);
            }
        });
        return retorno;
    }

    function recarregarContato(){
        var paramsAjax = {
            idContato: $('#selContato').val()
        };
        $.ajax({
            url: '<?= $strLinkAjaxContatoListar ?>',
            type: 'POST',
            dataType: 'XML',
            data: paramsAjax,
            success: function (r) {
                if (document.getElementById('lblGenero')) {
                    document.getElementById('lblGenero').innerHTML = '&nbsp;&nbsp;&nbsp;&nbsp;' + $(r).find('ExpressaoTratamentoCargo').text();
                }
                document.getElementById('lblNome').innerHTML = '&nbsp;&nbsp;&nbsp;&nbsp;' + $(r).find('Nome').text();
                if (document.getElementById('lblCargo')) {
                    document.getElementById('lblCargo').innerHTML = '&nbsp;&nbsp;&nbsp;&nbsp;' + $(r).find('ExpressaoCargo').text();
                }
                document.getElementById('lblEndereco').innerHTML = '&nbsp;&nbsp;&nbsp;&nbsp;' + $(r).find('Endereco').text() + ',&nbsp;';

                if ($(r).find('Complemento').text() != '') {
                    document.getElementById('lblEndereco').innerHTML += $(r).find('Complemento').text() + ',&nbsp;';
                }
                document.getElementById('lblEndereco').innerHTML += $(r).find('Bairro').text();
                document.getElementById('lblCep').innerHTML = '&nbsp;&nbsp;&nbsp;&nbsp;CEP: ' + $(r).find('Cep').text();
                document.getElementById('lblCep').innerHTML += ' -  ' + $(r).find('NomeCidade').text() + '/' + $(r).find('SiglaUf').text();
                if($(r).find('NomeContatoAssociado').text() != '' && ($(r).find('IdContatoAssociado').text() != $(r).find('IdContato').text())) {
                    $("#lblNomeAssociado").html("&nbsp;&nbsp;&nbsp;&nbsp;"+$(r).find('NomeContatoAssociado').text());
                    document.getElementById('lblNomeAssociado').style.display = 'block';
                    document.getElementById('lblNomeAssociado').style.marginBottom = '0';
                } else {
                    document.getElementById('lblNomeAssociado').style.display = 'none';
                }

                stopTimer++;
                if (stopTimer == 35) {
                    clearInterval(timerId);
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

    function alterarContato() {
        funcaoCallbackAlterarContato(identificaFechamentoModalAlterarContato);
    }
    function fecharConsulta() {
        window.onbeforeunload = null;
        document.location = '<?= $strUrlFecharConsulta ?>';
    }
    function identificaFechamentoModalAlterarContato() {
        $('#divInfraSparklingModalFrame', window.parent.document).on("click", function() {
            recarregarContato()
        });
    }
    function funcaoCallbackAlterarContato(callback) {
        seiCadastroContato(<?= $id_destinatario ?>, 'selContato', 'frmSolicitarExpedicao', '<?= $strLinkAlterarContato ?>');
        callback();
    }
    $(document).ready(function () {
        $('#ifrVisualizacao').focus(function () {});
    });
</script>
