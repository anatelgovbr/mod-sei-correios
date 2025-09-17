<?php

/**
 * ANATEL
 *
 * 18/10/2017 - criado por Ellyson de Jesus Silva
 *
 */

try {
    require_once dirname(__FILE__) . '/../../SEI.php';


    SessaoSEI::getInstance()->validarLink();
    SessaoSEI::getInstance()->validarPermissao($_GET['acao']);
    switch ($_GET['acao']) {
        case 'md_cor_plps_geradas_listar':
            $strTitulo = 'Consultar '. MdCorPlpRN::$STR_PLURAL_PRE_POSTAGEM .' Geradas';
            $strAcao = 'md_cor_plp_detalhada';
            break;
        case  'md_cor_expedicao_plp_listar':
            $strTitulo = MdCorPlpRN::$STR_PLURAL_PRE_POSTAGEM .' Geradas para Expedição';
            $strAcao = 'md_cor_plp_expedir';
            break;
        default:
            throw new InfraException("Ação '" . $_GET['acao'] . "' não reconhecida.");
    }

    /**
     * Montar array de comandos superior
     */
    $arrComandos = array();
    $arrComandos[] = '<button type="button" accesskey="P" id="btnPesquisar" onclick="pesquisar()" class="infraButton">
                                    <span class="infraTeclaAtalho">P</span>esquisar
                              </button>';
    $arrComandos[] = '<button type="button" accesskey="I" id="btnImprimir" onclick="infraImprimirTabela();" class="infraButton">
                                    <span class="infraTeclaAtalho">I</span>mprimir
                              </button>';
    $arrComandos[] = '<button type="button" accesskey="c" id="btnFechar" onclick="location.href=\'' . SessaoSEI::getInstance()->assinarLink('controlador.php?acao=procedimento_controlar&acao_origem=' . $_GET['acao']) . '\'" class="infraButton">
                                    Fe<span class="infraTeclaAtalho">c</span>har
                              </button>';


    $objMdCorPlpRN = new MdCorPlpRN();
    $objMdCorPlpDTO = new MdCorPlpDTO();
    $objMdCorPlpDTO->retNumIdMdPlp();
    $objMdCorPlpDTO->retDblCodigoPlp();
    $objMdCorPlpDTO->retStrStaPlp();
    $objMdCorPlpDTO->retDthDataCadastro();
    $objMdCorPlpDTO->retNumContagem();

    if ($_GET['acao'] == 'md_cor_expedicao_plp_listar') {
        $objMdCorPlpDTO->setStrStaPlp($objMdCorPlpRN::$STA_GERADA);
    }

    PaginaSEI::getInstance()->prepararOrdenacao($objMdCorPlpDTO, 'IdMdPlp', InfraDTO::$TIPO_ORDENACAO_DESC);
    PaginaSEI::getInstance()->prepararPaginacao($objMdCorPlpDTO, 200);

    if (isset($_POST['txtStatus']) && $_POST['txtStatus'] != "null") {
        $objMdCorPlpDTO->setStrStaPlp($_POST['txtStatus']);
    }

    if (isset($_POST['txtCodigoPlp']) && !empty($_POST['txtCodigoPlp'])) {
        $objMdCorPlpDTO->setDblCodigoPlp($_POST['txtCodigoPlp']);
    }

    $arrObjMdCorPlpDTO = $objMdCorPlpRN->listarComExpedicaoSolicitada($objMdCorPlpDTO);

    PaginaSEI::getInstance()->processarPaginacao($objMdCorPlpDTO);

    $numRegistros = count($arrObjMdCorPlpDTO);

    if ($numRegistros > 0) {

        $strResultado = '';

        $strSumarioTabela = 'Tabela de '.MdCorPlpRN::$STR_PLURAL_PRE_POSTAGEM.' geradas';
        $strCaptionTabela = MdCorPlpRN::$STR_PLURAL_PRE_POSTAGEM . ' geradas';

        $strResultado .= '<table width="100%" class="infraTable" summary="' . $strSumarioTabela . '">' . "\n";
        $strResultado .= '<caption class="infraCaption">' . PaginaSEI::getInstance()->gerarCaptionTabela($strCaptionTabela, $numRegistros) . '</caption>';
        $strResultado .= '<tr>';
        $strResultado .= '<th class="infraTh" width="1%">' . PaginaSEI::getInstance()->getThCheck() . '</th>' . "\n";
        $strResultado .= '<th class="infraTh" width="15%">' . PaginaSEI::getInstance()->getThOrdenacao($objMdCorPlpDTO, 'PLP', 'CodigoPlp', $arrObjMdCorPlpDTO) . '</th>' . "\n";

        $strResultado .= '<th class="infraTh" width="17%">' . PaginaSEI::getInstance()->getThOrdenacao($objMdCorPlpDTO, 'Data de Geração', 'DataCadastro', $arrObjMdCorPlpDTO) . '</th>' . "\n";

        $strResultado .= '<th class="infraTh" width="35%">' . 'Serviço Postal' . '</th>' . "\n";
        $strResultado .= '<th class="infraTh text-center">' . PaginaSEI::getInstance()->getThOrdenacao($objMdCorPlpDTO, 'Objetos', 'Contagem', $arrObjMdCorPlpDTO) . '</th>' . "\n";
        $strResultado .= '<th class="infraTh">' . PaginaSEI::getInstance()->getThOrdenacao($objMdCorPlpDTO, 'Situação', 'StaPlp', $arrObjMdCorPlpDTO) . '</th>' . "\n";
        $strResultado .= '<th class="infraTh" width="8%">Ações</th>' . "\n";

        $strResultado .= '</tr>' . "\n";
        $strCssTr = '';
        $cContador = 0;
        foreach ($arrObjMdCorPlpDTO as $objMdCorPlpDTO) {
            $midia = '';
            if ($objMdCorPlpDTO->getStrMidia() == 'S') {
                $midia = '<img src="modulos/correios/imagens/svg/media.svg?'.Icone::VERSAO.'" title="'. MdCorPlpRN::$STR_SING_PRE_POSTAGEM .' possui midia para gravação." style="width: 24px; height: 24px" alt="'. MdCorPlpRN::$STR_SING_PRE_POSTAGEM .' possui midia para gravação." class="infraImg inline-block mr-1" />';
            }


            $strCssTr = ($strCssTr == '<tr class="infraTrClara">') ? '<tr class="infraTrEscura">' : '<tr class="infraTrClara">'; 
            $strResultado .= $strCssTr;
            $strResultado .= '<td valign="middle">' . PaginaSEI::getInstance()->getTrCheck($cContador, $objMdCorPlpDTO->getNumIdMdPlp(), $objMdCorPlpDTO->getNumIdMdPlp()) . '</td>';
            
            $arrServico = InfraArray::implodeArrInfraDTO(
                InfraArray::distinctArrInfraDTO($objMdCorPlpDTO->getArrMdCorExpedicaoSolicitadaDTO(), 'DescricaoServicoPostal'), 
                'DescricaoServicoPostal', 
                ', '
            ) . ' (Contrato nº ' .
            InfraArray::implodeArrInfraDTO(
                InfraArray::distinctArrInfraDTO($objMdCorPlpDTO->getArrMdCorExpedicaoSolicitadaDTO(), 'NumeroContrato'), 
                'NumeroContrato', 
                ', '
            ) . ' - ' .
            InfraArray::implodeArrInfraDTO(
                InfraArray::distinctArrInfraDTO($objMdCorPlpDTO->getArrMdCorExpedicaoSolicitadaDTO(), 'NumeroContratoCorreio'), 
                'NumeroContratoCorreio', 
                ', '
            ) . ')';
            $strResultado .= '<td style="word-break:break-all">' . PaginaSEI::tratarHTML($objMdCorPlpDTO->getDblCodigoPlp()) . '</td>';

            $strResultado .= '<td style="word-break:break-all">' . PaginaSEI::tratarHTML($objMdCorPlpDTO->getDthDataCadastro()) . '</td>';

            $strResultado .= '<td style="word-break:break-all" align="center">' . PaginaSEI::tratarHTML($arrServico) . '</td>';
            $strResultado .= '<td class="d-flex align-items-center justify-content-center">' . $midia . PaginaSEI::tratarHTML($objMdCorPlpDTO->getNumContagem()) . '</td>';
            $strResultado .= '<td style="word-break:break-all">' . PaginaSEI::tratarHTML($objMdCorPlpDTO->getStrNomeStaPlp()) . '</td>';
            $strResultado .= '<td align="center">';
            if ($_GET['acao'] == 'md_cor_expedicao_plp_listar') {
                $strResultado .= '<a href="'. PaginaSEI::getInstance()->formatarXHTML(SessaoSEI::getInstance()->assinarLink('controlador.php?acao=' . $strAcao . '&acao_origem=' . $_GET['acao'] . '&acao_retorno=' . $_GET['acao'] . '&id_md_cor_plp=' . $objMdCorPlpDTO->getNumIdMdPlp())) . '" tabindex="' . PaginaSEI::getInstance()->getProxTabTabela() . '"><img src="modulos/correios/imagens/svg/solicitacao_correios.svg?'.Icone::VERSAO.'" style="width: 24px; height: 24px" title="Expedir '. MdCorPlpRN::$STR_SING_PRE_POSTAGEM .'" alt="Expedir '. MdCorPlpRN::$STR_SING_PRE_POSTAGEM .'" class="infraImgAcoes" /></a>&nbsp;';

                $linkCancelarPlp = PaginaSEI::getInstance()->formatarXHTML(SessaoSEI::getInstance()->assinarLink('controlador_ajax.php?acao_ajax=md_cor_plp_cancelar_plp'));
                $codRastreamento = $objMdCorPlpDTO->getArrMdCorExpedicaoSolicitadaDTO()[0]->getStrCodigoRastreamento();
                $strResultado .= '<a href="#" onclick="cancelarPLP('. InfraArray::implodeArrInfraDTO(InfraArray::distinctArrInfraDTO($objMdCorPlpDTO->getArrMdCorExpedicaoSolicitadaDTO(), 'IdMdCorContrato'), 'IdMdCorContrato', ', ') .','. $objMdCorPlpDTO->getDblCodigoPlp() .','. $objMdCorPlpDTO->getNumIdMdPlp() .',\''. $codRastreamento .'\',\''. $linkCancelarPlp .'\')" tabindex="'. PaginaSEI::getInstance()->getProxTabTabela() .'">
                                    <img src="'.Icone::DOCUMENTO_CANCELAR.'" style="width: 24px; height: 24px" title="Cancelar '. MdCorPlpRN::$STR_SING_PRE_POSTAGEM .'" alt="Cancelar '. MdCorPlpRN::$STR_SING_PRE_POSTAGEM .'" class="infraImgAcoes" />
                                  </a>&nbsp;';
            } else {
                $strResultado .= '<a href="' . PaginaSEI::getInstance()->formatarXHTML(SessaoSEI::getInstance()->assinarLink('controlador.php?acao=' . $strAcao . '&acao_origem=' . $_GET['acao'] . '&acao_retorno=' . $_GET['acao'] . '&id_md_cor_plp=' . $objMdCorPlpDTO->getNumIdMdPlp())) . '" tabindex="' . PaginaSEI::getInstance()->getProxTabTabela() . '"><img src="' . PaginaSEI::getInstance()->getDiretorioSvgGlobal() . '/consultar.svg" title="Detalhar '. MdCorPlpRN::$STR_SING_PRE_POSTAGEM .'" alt="Detalhar '. MdCorPlpRN::$STR_SING_PRE_POSTAGEM .'" class="infraImg" /></a>&nbsp;';
            }
            $strResultado .= '</td></tr>' . "\n";
            $cContador++;
        }
        $strResultado .= '</table>';
    }

    $strComboStatus = MdCorPlpINT::montarSelectStaPlp('null', '&nbsp;', $_POST['txtStatus']);
    $strComboServicoPostal = MdCorPlpINT::montarSelectServicoPostal('null', '&nbsp;', $_POST['selServicoPostal']);
} catch (Exception $e) {
    PaginaSEI::getInstance()->processarExcecao($e);
}


PaginaSEI::getInstance()->montarDocType();
PaginaSEI::getInstance()->abrirHtml();
PaginaSEI::getInstance()->abrirHead();
PaginaSEI::getInstance()->montarMeta();
PaginaSEI::getInstance()->montarTitle(':: ' . PaginaSEI::getInstance()->getStrNomeSistema() . ' - ' . $strTitulo . ' ::');
PaginaSEI::getInstance()->montarStyle();
PaginaSEI::getInstance()->abrirStyle();
include_once("md_cor_estilos_css.php");
PaginaSEI::getInstance()->fecharStyle();
PaginaSEI::getInstance()->montarJavaScript();
PaginaSEI::getInstance()->abrirJavaScript();
PaginaSEI::getInstance()->fecharJavaScript();
PaginaSEI::getInstance()->fecharHead();
PaginaSEI::getInstance()->abrirBody($strTitulo, 'onload="inicializar();"');
?>
<form id="frmPlpsGeradas" method="post" action="<?= PaginaSEI::getInstance()->formatarXHTML(SessaoSEI::getInstance()->assinarLink('controlador.php?acao=' . $_GET['acao'] . '&acao_origem=' . $_GET['acao'])) ?>">
    <? PaginaSEI::getInstance()->montarBarraComandosSuperior($arrComandos); ?>
    <div class="row">
        <div class="col-xl-3 col-lg-3 col-md-4 col-sm-4 col-12">
            <div class="form-group">
                <label id="lblCodigoPlp" for="txtCodigoPlp" class="infraLabelOpcional">PLP:</label>
                <input type="text" id="txtCodigoPlp" name="txtCodigoPlp" class="infraText form-control" value="<?= PaginaSEI::tratarHTML($_POST['txtCodigoPlp']) ?>" maxlength="50" tabindex="502" onkeypress="return infraMascaraNumero(this, event, '50')">
            </div>
        </div>
        <div class="col-xl-6 col-lg-9 col-md-8 col-sm-8 col-12">
            <div class="form-group">
                <label id="lblServicoPostal" for="selServicoPostal" class="infraLabelOpcional">Serviços Postais:</label>
                <select id="selServicoPostal" name="selServicoPostal" class="infraSelect form-select" maxlength="250"  tabindex="503">
                    <?= $strComboServicoPostal ?>
                </select>
            </div>
        </div>
        <div class="col-xl-3 col-lg-4 col-md-4 col-sm-4 col-12">
            <div class="form-group">
                <label id="lblCodRastreamento" for="txtCodRastreamento" class="infraLabelOpcional">Rastreamento:</label>
                <input type="text" id="txtCodRastreamento" name="txtCodRastreamento" class="infraText form-control" value="<?= PaginaSEI::tratarHTML($_POST['txtCodRastreamento']) ?>" maxlength="250" tabindex="503">
            </div>
        </div>
        <div class="col-xl-3 col-lg-4 col-md-4 col-sm-4 col-12">
            <div class="form-group">
                <label id="lblNumDocumentoPrincipal" for="numDocumentoPrincipal" class="infraLabelOpcional">Documento Principal:</label>
                <input type="text" id="numDocumentoPrincipal" name="numDocumentoPrincipal" class="infraText form-control" value="<?= PaginaSEI::tratarHTML($_POST['numDocumentoPrincipal']) ?>" maxlength="250" tabindex="503">
            </div>
        </div>
        <div class="col-xl-3 col-lg-4 col-md-4 col-sm-4 col-12">
            <div class="form-group">
                <label id="lblNumProcesso" for="numProcesso" class="infraLabelOpcional">Processo:</label>
                <input type="text" id="numProcesso" name="numProcesso" class="infraText form-control" value="<?= PaginaSEI::tratarHTML($_POST['numProcesso']) ?>" maxlength="250" tabindex="503">
            </div>
        </div>
        <?php if ($_GET['acao'] != 'md_cor_expedicao_plp_listar'): ?> 
        <div class="col-xl-6 col-lg-7 col-md-8 col-sm-8 col-12">
            <label id="lblStatus" for="lblStatus" class="infraLabelOpcional">Situação da PLP:</label>
            <select id="txtStatus" name="txtStatus" class="infraSelect form-select" maxlength="250" tabindex="503">
                <?= $strComboStatus ?>
            </select>
        </div>
        <?php endif ?>
    </div>
    <input type="submit" style="visibility: hidden"/>
    <?

    PaginaSEI::getInstance()->montarAreaTabela($strResultado, $numRegistros);
    PaginaSEI::getInstance()->montarBarraComandosInferior($arrComandos);
    ?>
</form>
<?
require_once("md_cor_plps_geradas_lista_js.php");
PaginaSEI::getInstance()->fecharBody();
PaginaSEI::getInstance()->fecharHtml();
?>
