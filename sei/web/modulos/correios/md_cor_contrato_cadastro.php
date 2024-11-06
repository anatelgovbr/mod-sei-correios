<?
/**
 * TRIBUNAL REGIONAL FEDERAL DA 4ª REGIÃO
 *
 * 22/12/2016 - criado por Wilton Júnior
 *
 * Versão do Gerador de Código: 1.39.0
 *
 * Versão no SVN: $Id$
 */

try {
    require_once dirname(__FILE__) . '/../../SEI.php';

    session_start();

    SessaoSEI::getInstance()->validarLink();

    PaginaSEI::getInstance()->verificarSelecao('md_cor_contrato_selecionar');

    SessaoSEI::getInstance()->validarPermissao($_GET['acao']);

    $objMdCorContratoDTO = new MdCorContratoDTO();

    $strDesabilitar = '';
    $strProtocoloFormatado = '';
    $hdnListaContratoServicosIndicados = '';
    $strIdMdCorContrato = '';

    $arrComandos = array();

    $mdCorTipoCorrespondencia = MdCorTipoCorrespondencINT::montarSelectIdMdCorTipoCorrespondenc('null', '', 'null');

    switch ($_GET['acao']) {
        case 'md_cor_contrato_cadastrar':
            $strTitulo = 'Contrato e Serviços Postais';
            $arrComandos[] = '<button type="submit" accesskey="S" name="sbmCadastrarMdCorContrato" value="Salvar" class="infraButton"><span class="infraTeclaAtalho">S</span>alvar</button>';
            $arrComandos[] = '<button type="button" accesskey="C" name="btnCancelar" id="btnCancelar" value="Cancelar" onclick="location.href=\'' . SessaoSEI::getInstance()->assinarLink('controlador.php?acao=' . PaginaSEI::getInstance()->getAcaoRetorno() . '&acao_origem=' . $_GET['acao']) . '\';" class="infraButton"><span class="infraTeclaAtalho">C</span>ancelar</button>';

            $objMdCorContratoDTO->setStrNumeroContrato($_POST['txtNumeroContrato']);
            $objMdCorContratoDTO->setStrNumeroContratoCorreio($_POST['txtNumeroContratoCorreio']);
            $objMdCorContratoDTO->setStrNumeroCartaoPostagem($_POST['txtNumeroCartaoPostagem']);
            $objMdCorContratoDTO->setDblIdProcedimento($_POST['hdnIdProcedimento']);
            $objMdCorContratoDTO->setStrSinAtivo('S');
            $objMdCorContratoDTO->setNumNumeroCnpj($_POST['txtCNPJ']);
            $objMdCorContratoDTO->setNumIdMdCorDiretoria($_POST['slCodigoDiretoria']);

            if (isset($_POST['sbmCadastrarMdCorContrato'])) {
                try {
                    $objMdCorContratoRN = new MdCorContratoRN();
                    $objMdCorContratoDTO = $objMdCorContratoRN->cadastrar($_POST);
                    PaginaSEI::getInstance()->adicionarMensagem('contrato cadastrado com sucesso.');
                    header('Location: ' . SessaoSEI::getInstance()->assinarLink('controlador.php?acao=' . PaginaSEI::getInstance()->getAcaoRetorno() . '&acao_origem=' . $_GET['acao'] . '&id_md_cor_contrato=' . $objMdCorContratoDTO->getNumIdMdCorContrato() . PaginaSEI::getInstance()->montarAncora($objMdCorContratoDTO->getNumIdMdCorContrato())));
                    die;
                } catch (Exception $e) {
                    PaginaSEI::getInstance()->processarExcecao($e);
                }
            }
            break;

        case 'md_cor_contrato_alterar':
            $strTitulo = 'Alterar Contrato e Serviço Postal';
            $arrComandos[] = '<button type="submit" accesskey="S" name="sbmAlterarMdCorContrato" value="Salvar" class="infraButton"><span class="infraTeclaAtalho">S</span>alvar</button>';
            $strDesabilitar = 'disabled="disabled"';

            $idMdCorContrato = $_GET['id_md_cor_contrato'] ? $_GET['id_md_cor_contrato'] : $_POST['hdnIdMdCorContrato'];
            $objMdCorContratoDTO->setNumIdMdCorContrato($idMdCorContrato);

            $arrComandos[] = '<button type="button" accesskey="C" name="btnCancelar" id="btnCancelar" value="Cancelar" onclick="location.href=\'' . SessaoSEI::getInstance()->assinarLink('controlador.php?acao=' . PaginaSEI::getInstance()->getAcaoRetorno() . '&acao_origem=' . $_GET['acao'] . PaginaSEI::getInstance()->montarAncora($objMdCorContratoDTO->getNumIdMdCorContrato())) . '\';" class="infraButton"><span class="infraTeclaAtalho">C</span>ancelar</button>';

            if (isset($_POST['sbmAlterarMdCorContrato'])) {
                try {
                    $objMdCorContratoRN = new MdCorContratoRN();
                    $objMdCorContratoRN->alterar($_POST);
                    PaginaSEI::getInstance()->adicionarMensagem('contrato "' . $objMdCorContratoDTO->getNumIdMdCorContrato() . '" alterado com sucesso.');
                    header('Location: ' . SessaoSEI::getInstance()->assinarLink('controlador.php?acao=' . PaginaSEI::getInstance()->getAcaoRetorno() . '&acao_origem=' . $_GET['acao'] . '&id_me_cor_contrato=' . $_GET['id_md_cor_contrato'] . PaginaSEI::getInstance()->montarAncora($objMdCorContratoDTO->getNumIdMdCorContrato())));
                    die;
                } catch (Exception $e) {
                    PaginaSEI::getInstance()->processarExcecao($e);
                }
            }

            break;

        case 'md_cor_contrato_consultar':
            $strTitulo = 'Consultar Contrato e Serviço Postal';
            $arrComandos[] = '<button type="button" accesskey="C" name="btnFechar" value="Fechar" onclick="location.href=\'' . SessaoSEI::getInstance()->assinarLink('controlador.php?acao=' . PaginaSEI::getInstance()->getAcaoRetorno() . '&acao_origem=' . $_GET['acao'] . PaginaSEI::getInstance()->montarAncora($_GET['id_md_cor_contrato'])) . '\';" class="infraButton">Fe<span class="infraTeclaAtalho">c</span>har</button>';
            $objMdCorContratoDTO->setNumIdMdCorContrato($_GET['id_md_cor_contrato']);
            $objMdCorContratoDTO->setBolExclusaoLogica(false);

            $objMdCorContratoDTO->retStrNumeroContrato();
            $objMdCorContratoDTO->retStrNumeroContratoCorreio();
            $objMdCorContratoDTO->retStrNumeroCartaoPostagem();
            $objMdCorContratoDTO->retDblIdProcedimento();
            $objMdCorContratoDTO->retStrSinAtivo();
            $objMdCorContratoDTO->retNumNumeroCnpj();
            $objMdCorContratoDTO->retNumIdMdCorDiretoria();

            $objMdCorContratoRN = new MdCorContratoRN();
            $objMdCorContratoDTO = $objMdCorContratoRN->consultar($objMdCorContratoDTO);
            if ($objMdCorContratoDTO === null) {
                throw new InfraException("Registro não encontrado.");
            }

            break;

        default:
            throw new InfraException("Ação '" . $_GET['acao'] . "' não reconhecida.");
    }

    if ($_GET['acao'] == 'md_cor_contrato_alterar' || $_GET['acao'] == 'md_cor_contrato_consultar') {

        $idMdCorContrato = $_GET['id_md_cor_contrato'] ? $_GET['id_md_cor_contrato'] : $_POST['hdnIdMdCorContrato'];

        if ($idMdCorContrato) {
            $objMdCorContratoDTO->retNumIdMdCorContrato();
            $objMdCorContratoDTO->retStrNumeroContrato();
            $objMdCorContratoDTO->retStrNumeroContratoCorreio();
            $objMdCorContratoDTO->retStrNumeroCartaoPostagem();
            $objMdCorContratoDTO->retNumNumeroCnpj();
            $objMdCorContratoDTO->retNumIdMdCorDiretoria();
            $objMdCorContratoDTO->retDblIdProcedimento();
            $objMdCorContratoDTO->retStrSinAtivo();
            $objMdCorContratoDTO->setBolExclusaoLogica(false);
            $objMdCorContratoRN = new MdCorContratoRN();
            $objMdCorContratoDTO = $objMdCorContratoRN->consultar($objMdCorContratoDTO);
            $strIdMdCorContrato = $objMdCorContratoDTO->getNumIdMdCorContrato();

            $slCodigoDiretoria = $objMdCorContratoDTO->getNumIdMdCorDiretoria();
            if ($objMdCorContratoDTO == null) {
                throw new InfraException("Registro não encontrado.");
            }


            if ($objMdCorContratoDTO->getDblIdProcedimento() != '') {
                $objProtocoloRN = new ProtocoloRN();
                $objProtocoloDTO = new ProtocoloDTO();
                $objProtocoloDTO->setDblIdProtocolo($objMdCorContratoDTO->getDblIdProcedimento());
                $objProtocoloDTO->retTodos();
                $objProtocoloDTO = $objProtocoloRN->consultarRN0186($objProtocoloDTO);
                $strProtocoloFormatado = $objProtocoloDTO->getStrProtocoloFormatado();

                $objMdCorContratoRN = new MdCorContratoRN();
                $objProtocoloDTO = new ProtocoloDTO();
                $objProtocoloDTO->setStrProtocoloFormatadoPesquisa($strProtocoloFormatado);
                $objProtocoloDTO = $objMdCorContratoRN->pesquisarProtocoloFormatado($objProtocoloDTO);
                $strTipoProtocolo = $objProtocoloDTO->getStrNomeTipoProcedimentoProcedimento();
            }

            $objMdCorServicoPostalRN = new MdCorServicoPostalRN();
            $objMdCorServicoPostalDTO = new MdCorServicoPostalDTO();
            $objMdCorServicoPostalDTO->retTodos(true);
            $objMdCorServicoPostalDTO->setNumIdMdCorContrato($_GET['id_md_cor_contrato']);
            $objMdCorServicoPostalDTO->setBolExclusaoLogica(array());
            $arrObjMdCorServicoPostalDTO = $objMdCorServicoPostalRN->listar($objMdCorServicoPostalDTO);

            $arrItensTabelaContratoServicos = array();

            $sinAtivo = array();
            foreach ($arrObjMdCorServicoPostalDTO as $i => $objMdCorServicoPostalDTO) {
                $mdCorTipoCorrespondencia = MdCorTipoCorrespondencINT::montarSelectIdMdCorTipoCorrespondenc('null', '', $objMdCorServicoPostalDTO->getNumIdMdCorTipoCorrespondencia() . '|' . $objMdCorServicoPostalDTO->getStrSinAr());

                $ar = $objMdCorServicoPostalDTO->getStrExpedicaoAvisoRecebimento();
                $sinAtivo[$i] = $objMdCorServicoPostalDTO->getStrSinAtivo();

                $cobrar = $objMdCorServicoPostalDTO->getStrSinServicoCobrar();
                $anexarMidia = $objMdCorServicoPostalDTO->getStrSinAnexarMidia();
                $checked = ' checked="checked"';
                $readonly = '';
                if (($_GET['acao'] == 'md_cor_contrato_consultar')) {
                    $readonly = ' disabled="disabled" readonly onClick="return false;"';
                }
                $checkedSim = ($ar == 'S') ? $checked : '';
                $checkedNao = ($ar == 'N' && $objMdCorServicoPostalDTO->getStrSinAr() != 'N') ? $checked : '';
                $checkedCobrar = ($cobrar == 'S') ? $checked : '';
                $checkedAnexarMidia = ($anexarMidia == 'S') ? $checked : '';
                $disabledSinAr = $objMdCorServicoPostalDTO->getStrSinAr() == 'N' ? 'disabled="disabled"' : null;

                $strRd = '<div id="divRdoAr" class="infraDivRadio" align="center" style="width: 100%">';
                $strRd .= '    <div class="infraRadioDiv ">';
                $strRd .= '        <input type="radio" name="ar[' . $i . ']" id="arS[' . $i . ']"';
                $strRd .= '               value="S"';
                $strRd .= '               class="infraRadioInput" ' . $checkedSim . $readonly . $disabledSinAr . '>';
                $strRd .= '            <label class="infraRadioLabel" for="arS[' . $i . ']"></label>';
                $strRd .= '    </div>';
                $strRd .= '    <label id="lblArS[' . $i . ']" for="arS[' . $i . ']" class="infraLabelRadio" tabindex="507">Sim</label>';
                $strRd .= '    <div class="infraRadioDiv ">';
                $strRd .= '        <input type="radio" name="ar[' . $i . ']" id="arN[' . $i . ']"';
                $strRd .= '               value="N"';
                $strRd .= '               class="infraRadioInput" ' . $checkedNao . $readonly . $disabledSinAr . '>';
                $strRd .= '            <label class="infraRadioLabel" for="arN[' . $i . ']"></label>';
                $strRd .= '    </div>';
                $strRd .= '    <label id="lblArN[' . $i . ']" for="arN[' . $i . ']" class="infraLabelRadio" tabindex="507">Não</label>';
                $strRd .= '</div>';

                $strChk = '<div id="divRdoAr" class="infraDivCheckbox">';
                $strChk .= '    <div class="infraCheckboxDiv">';
                $strChk .= '        <input type="checkbox" name="cobrar[' . $i . ']" id="cobrar[' . $i . ']"';
                $strChk .= '               value="S"';
                $strChk .= '               class="infraCheckboxInput" ' . $checkedCobrar . $readonly . '>';
                $strChk .= '            <label class="infraCheckboxLabel " for="cobrar[' . $i . ']"></label>';
                $strChk .= '    </div>';
                $strChk .= '</div>';

                $strAnexarMidia = '<div class="infraDivCheckbox" style="text-align: center"> <div class="infraCheckboxDiv"> <input type="checkbox" class="infraCheckboxInput" value="S" name="anexarMidia['.$i.']" id="anexarMidia['.$i.']" '. $checkedAnexarMidia . $readonly .'> <label class="infraCheckboxLabel" for="anexarMidia['.$i.']"></label> </div> </div>';

                $itensTabelaContratoServicos = array(
                    trim($objMdCorServicoPostalDTO->getStrCodigoWsCorreios()),
                    $objMdCorServicoPostalDTO->getStrSinAtivo(),
                    '',
                    $objMdCorServicoPostalDTO->getStrNome(),
                    /*5*/'<div style="width:100%;"><select id="slTipo_'. $i .'" class="infraSelect sl_tipo form-control" name="sl_tipo[' . $i . ']" onchange="verificaAr(this)">' . json_encode($mdCorTipoCorrespondencia) . '</select></div>',
	                /*6*/$strRd,
	                /*7*/$strChk,
	                $strAnexarMidia,
                    '<div><input type="text" id="idDesc_'. $i .'" class="input-desc form-control" style="width: 100%" name="descricao[' . $i . ']" value="' . PaginaSEI::tratarHTML($objMdCorServicoPostalDTO->getStrDescricao()) . '" ' . $readonly . '/> <input type="hidden" name="codigo[' . $i . ']" value="' . trim($objMdCorServicoPostalDTO->getStrCodigoWsCorreios()) . '"> <input type="hidden" name="nome[' . $i . ']" value="' . $objMdCorServicoPostalDTO->getStrNome() . '"></div>',
                    ''
                );

                if ($_GET['acao'] == 'md_cor_contrato_consultar') {
                    $itensTabelaContratoServicos[5] = ($objMdCorServicoPostalDTO->getStrExpedicaoAvisoRecebimento() == 'S') ? 'Sim' : 'Não';
                    $itensTabelaContratoServicos[6] = ($cobrar == 'S') ? 'Sim' : 'Não';
                    $itensTabelaContratoServicos[7] = ($anexarMidia == 'S') ? 'Sim' : 'Não';
                    $itensTabelaContratoServicos[8] = $objMdCorServicoPostalDTO->getStrDescricao();
                }

                $arrItensTabelaContratoServicos[] = $itensTabelaContratoServicos;
            }

            $hdnListaContratoServicosIndicados = PaginaSEI::getInstance()->gerarItensTabelaDinamica($arrItensTabelaContratoServicos, false);

        }
    }


} catch (Exception $e) {
    PaginaSEI::getInstance()->processarExcecao($e);
}
$mdCorDiretorioInt = MdCorDiretoriaINT::montarSelectIdMdCorDiretoria('null', '', $slCodigoDiretoria);
PaginaSEI::getInstance()->montarDocType();
PaginaSEI::getInstance()->abrirHtml();
PaginaSEI::getInstance()->abrirHead();
PaginaSEI::getInstance()->montarMeta();
PaginaSEI::getInstance()->montarTitle(PaginaSEI::getInstance()->getStrNomeSistema() . ' - ' . $strTitulo);
PaginaSEI::getInstance()->montarStyle();
PaginaSEI::getInstance()->abrirStyle();
include_once('md_cor_contrato_cadastro_css.php');
PaginaSEI::getInstance()->fecharStyle();
PaginaSEI::getInstance()->montarJavaScript();
PaginaSEI::getInstance()->fecharHead();
PaginaSEI::getInstance()->abrirBody($strTitulo, 'onload="inicializar();"');
PaginaSEI::getInstance()->abrirAreaDados();
?>
    <form id="frmMdCorContratoCadastro" method="post" onsubmit="return OnSubmitForm();"
          action="<?= SessaoSEI::getInstance()->assinarLink('controlador.php?acao=' . $_GET['acao'] . '&acao_origem=' . $_GET['acao']) ?>">
        <?
        PaginaSEI::getInstance()->montarBarraComandosSuperior($arrComandos);
        ?>

        <div class="row mb-3 linha">
            <div class="col-sm-12 col-md-12 col-lg-12 col-xl-12">
                <fieldset id="fieldsetContratoOrgao" class="infraFieldset form-control" style="height: 100%">
                    <legend class="infraLegend">&nbsp;Dados do Contrato no Órgão&nbsp;</legend>
                    <div class="row">
                        <div class="col-sm-7 col-md-7 col-lg-6">
                            <label id="lblNumeroContrato" for="txtNumeroContrato" accesskey="o"
                                   class="infraLabelObrigatorio">Númer<span class="infraTeclaAtalho">o</span> do
                                Contrato:
                                <img align="top" id="imgAjuda"
                                     src="<?= PaginaSEI::getInstance()->getDiretorioSvgGlobal() ?>/ajuda.svg"
                                     onmouseover="return infraTooltipMostrar('Informar o Número de identificação do Contrato no Órgão.', 'Ajuda');"
                                     onmouseout="return infraTooltipOcultar();"
                                     alt="Ajuda" class="infraImgModulo"/>
                            </label>
                            <input type="text" id="txtNumeroContrato" name="txtNumeroContrato"
                                   class="infraText form-control"
                                   value="<?= PaginaSEI::tratarHTML($objMdCorContratoDTO->getStrNumeroContrato()); ?>"
                                   onkeypress="return infraMascaraTexto(this,event,20);" maxlength="20"
                                   tabindex="<?= PaginaSEI::getInstance()->getProxTabDados() ?>"/>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-sm-7 col-md-7 col-lg-6">
                            <label id="lblNumeroProcessoContratacao" for="txtNumeroProcessoContratacao" accesskey="t"
                                   class="infraLabelOpcional">Número do Processo de Con<span class="infraTeclaAtalho">t</span>ratação:
                                <img align="top" id="imgAjuda"
                                     src="<?= PaginaSEI::getInstance()->getDiretorioSvgGlobal() ?>/ajuda.svg"
                                     name="ajuda"
                                     onmouseover="return infraTooltipMostrar('Informar o Número do Processo no SEI por meio do qual os Correios foi Contratado.', 'Ajuda');"
                                     onmouseout="return infraTooltipOcultar();"
                                     alt="Ajuda" class="infraImgModulo"/>
                            </label>
                            <div class="input-group mb-3">
                                <input type="text" id="txtNumeroProcessoContratacao" name="txtNumeroProcessoContratacao"
                                       onfocus="limparNumeroProcedimento()" class="infraText form-control" value="<?= $strProtocoloFormatado; ?>"
                                       onkeypress="return infraMascaraTexto(this,event,50);" maxlength="50"
                                       tabindex="<?= PaginaSEI::getInstance()->getProxTabDados() ?>"/>
                                <input type="hidden" id="hdnNumeroProcessoContratacao" name="hdnNumeroProcessoContratacao"
                                       value="<?= $strProtocoloFormatado; ?>"/>
                                <input type="hidden" id="hdnIdProcedimento" name="hdnIdProcedimento"
                                       value="<?= PaginaSEI::tratarHTML($objMdCorContratoDTO->getDblIdProcedimento()); ?>"
                                       onkeypress="return infraMascaraTexto(this,event,50);" maxlength="50"
                                       tabindex="<?= PaginaSEI::getInstance()->getProxTabDados() ?>"/>
                                <? if ($_GET['acao'] == 'md_cor_contrato_cadastrar' || $_GET['acao'] == 'md_cor_contrato_alterar') { ?>
                                    <button id="validar-processo-contratacao" class="infraButton" onclick="validarNumeroProcesso()"
                                            type="button">Validar
                                    </button>
                                <? } ?>
                            </div>
                        </div>

                        <? if ($_GET['acao'] == 'md_cor_contrato_cadastrar' || $_GET['acao'] == 'md_cor_contrato_alterar') { ?>
                            <div class="col-sm-5 col-md-5 col-lg-6" id="divLblTipoProcessoContratacao" style="padding-top:30px;">
                                <input type="text" id="txtTipoProcessoContratacao" disabled class="infraText form-control">
                            </div>
                        <? } ?>
                    </div>
                </fieldset>
            </div>
        </div>

        <!-- Padrão de largura para as div abaixo -->
        <?php $cls_def = "col-sm-7 col-md-7 col-lg-6" ?>

        <div class="row linha">
            <div class="col-12">
                <fieldset id="fieldsetContratoCorreios" class="infraFieldset form-control" style="height: 100%">
                    <legend class="infraLegend">&nbsp;Dados do Contrato nos Correios&nbsp;</legend>

                    <div class="row">
                        <div class="<?= $cls_def ?> mb-1">
                            <label id="lblNumeroContratoCorreio" for="txtNumeroContratoCorreio" accesskey="n"
                                   class="infraLabelObrigatorio"><span class="infraTeclaAtalho">N</span>úmero do
                                Contrato:
                                <img
                                        align="top" id="imgAjuda"
                                        src="<?= PaginaSEI::getInstance()->getDiretorioSvgGlobal() ?>/ajuda.svg"
                                        onmouseover="return infraTooltipMostrar('Código interno dos Correios de identificação do Contrato, utilizado na integração com o Web Service do SIGEP WEB. \n \n Se for informado número incorreto não vai validar o Endereço WSDL do Web Service do SIGEP WEB.', 'Ajuda');"
                                        onmouseout="return infraTooltipOcultar();"
                                        alt="Ajuda" class="infraImgModulo"/>
                            </label>
                            <input type="text" id="txtNumeroContratoCorreio" name="txtNumeroContratoCorreio"
                                   class="infraText form-control"
                                   value="<?= PaginaSEI::tratarHTML($objMdCorContratoDTO->getStrNumeroContratoCorreio()); ?>"
                                   onkeypress="return infraMascaraTexto(this,event,10);" maxlength="10"
                                   tabindex="<?= PaginaSEI::getInstance()->getProxTabDados() ?>"/>
                        </div>
                    </div>
                    <div class="row">
                        <div class="<?= $cls_def ?>">
                            <label id="lblNumeroCartaoPostagem" for="txtNumeroCartaoPostagem" accesskey="p"
                                   class="infraLabelObrigatorio">Cartão
                                de <span class="infraTeclaAtalho">P</span>ostagem:
                                <img align="top"
                                     id="imgAjuda"
                                     src="<?= PaginaSEI::getInstance()->getDiretorioSvgGlobal() ?>/ajuda.svg"
                                     onmouseover="return infraTooltipMostrar('Informar o Cartão de Postagem correspondente ao Contrato do Órgão.', 'Ajuda');"
                                     onmouseout="return infraTooltipOcultar();"
                                     alt="Ajuda" class="infraImgModulo"/>
                            </label>
                            <input type="text" id="txtNumeroCartaoPostagem" name="txtNumeroCartaoPostagem"
                                   class="infraText form-control"
                                   value="<?= PaginaSEI::tratarHTML($objMdCorContratoDTO->getStrNumeroCartaoPostagem()); ?>"
                                   onkeypress="return infraMascaraTexto(this,event,10);" maxlength="10"
                                   tabindex="<?= PaginaSEI::getInstance()->getProxTabDados() ?>"/>
                        </div>
                    </div>
                    <div class="row">
                        <div class="<?= $cls_def ?>">
                            <label id="lblCNPJ" for="txtCNPJ" class="infraLabelObrigatorio">CNPJ do Órgão: <img
                                        align="top"
                                        id="imgAjuda"
                                        src="<?= PaginaSEI::getInstance()->getDiretorioSvgGlobal() ?>/ajuda.svg"
                                        onmouseover="return infraTooltipMostrar('Informar o CNPJ do Órgão correspondente ao Contrato.', 'Ajuda');"
                                        onmouseout="return infraTooltipOcultar();"
                                        alt="Ajuda" class="infraImgModulo"/>
                            </label>
                            <input type="text" id="txtCNPJ" name="txtCNPJ" class="infraText form-control"
                                   value="<?= PaginaSEI::tratarHTML(InfraUtil::formatarCnpj($objMdCorContratoDTO->getNumNumeroCnpj())); ?>"
                                   onkeypress="return infraMascaraCnpj(this, event);" maxlength="18"
                                   tabindex="<?= PaginaSEI::getInstance()->getProxTabDados() ?>"/>
                        </div>
                    </div>
                    <div class="row">
                        <div class="<?= $cls_def ?>">
                            <label id="lblCodigoDiretoria" for="slCodigoDiretoria" class="infraLabelOpcional">Código da Diretoria:
                                <img align="top" id="imgAjuda"
                                     src="<?= PaginaSEI::getInstance()->getDiretorioSvgGlobal() ?>/ajuda.svg"
                                     onmouseover="return infraTooltipMostrar('Diretoria Regional dos Correios correspondente ao Contrato do Órgão.', 'Ajuda');"
                                     onmouseout="return infraTooltipOcultar();"
                                     alt="Ajuda" class="infraImgModulo"/>
                            </label>
                            <select class="infraSelect form-control" name="slCodigoDiretoria" id="slCodigoDiretoria"
                                    tabindex="<?= PaginaSEI::getInstance()->getProxTabDados(); ?>">
                                <?php echo $mdCorDiretorioInt; ?>
                            </select>
                        </div>
                    </div>
                    <div class="row">
                        <?php if ($_GET['acao'] == 'md_cor_contrato_cadastrar' || $_GET['acao'] == 'md_cor_contrato_alterar') { ?>
                            <div class="col-sm-11 col-md-11 col-lg-10 col-xl-8 mt-2 mb-2">
                                <button id="validar-url" onclick="buscarServicosPostais()" class="infraButton btn-outline-info"
                                        type="button">Buscar Serviços Postais
                                </button>
                            </div>
                        <?php } ?>

                    </div>
                    <div class="row">
                        <div class="col-12">
                            <input type="hidden" id="hdnIdMdCorContrato" name="hdnIdMdCorContrato" value="<?= $strIdMdCorContrato; ?>"/>
                            
                            <input type="hidden" name="hdnIdContratoServicosCadastrado" id="hdnIdContratoServicosCadastrado"
                                    value=""/>
                            <input type="hidden" name="hdnListaContratoServicosIndicados" id="hdnListaContratoServicosIndicados"
                                    value=""/>
                            <input type="hidden" name="hdnListaContratoServicosDesativados[]" id="hdnListaContratoServicosDesativados"
                                   value=""/>
                            <input type="hidden" name="hdnListaContratoServicosReativadas[]" id="hdnListaContratoServicosReativadas"
                                    value=""/>
                            <table id="tbContratoServicos" class="infraTable table w-100" align="left" summary="Lista de Serviços Postais">
                                <thead>
                                <tr>
                                    <th class="infraTh" style="display: none;">Codigo Servico</th>
                                    <th class="infraTh" style="display: none;">AR Hidden</th>
                                    <th class="infraTh" style="display: none;">Descricao Hidden</th>
                                    <th class="infraTh" width="18%" id="tdDescricaoServicoPostal">Serviço Postal</th>
                                    <th class="infraTh" width="12%" align="center" id="tdCheckExpedidoAR">Tipo</th>
                                    <th class="infraTh" width="16%" align="center" id="tdCheckExpedidoAR">Expedido com AR</th>
                                    <th class="infraTh" width="10%" align="center" id="tdCheckCobrar">Serviço à Cobrar</th>
                                    <th class="infraTh" width="10%" align="center" id="tdCheckAnexarMidia">Permite Anexar Mídia</th>
                                    <th class="infraTh" width="22%" id="tdTxtDescricao">Descrição Amigável</th>                                    
                                    <th class="infraTh" width="7%" align="center">Ações</th>                                    
                                </tr>
                                </thead>
                                <tbody>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </fieldset>
            </div>
        </div>

        <? PaginaSEI::getInstance()->montarBarraComandosInferior($arrComandos); ?>
    </form>
<?
PaginaSEI::getInstance()->fecharAreaDados();
PaginaSEI::getInstance()->fecharBody();
PaginaSEI::getInstance()->fecharHtml();
include_once('md_cor_funcoes_js.php');
include_once('md_cor_contrato_cadastro_js.php');
?>