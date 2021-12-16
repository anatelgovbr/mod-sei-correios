<?php

/**
 * ANATEL
 *
 * 18/10/2017 - criado por Ellyson de Jesus Silva
 *
 */

try {
    require_once dirname(__FILE__) . '/../../SEI.php';

    session_start();

    SessaoSEI::getInstance()->validarLink();

    SessaoSEI::getInstance()->validarPermissao($_GET['acao']);

    PaginaSEI::getInstance()->setTipoPagina(InfraPagina::$TIPO_PAGINA_SIMPLES);

    /**
     * Montar array de comandos superior
     */
    $arrComandos = array();
    switch ($_GET['acao']) {
        case 'md_cor_cobranca_gerar':
            $strTitulo = 'Gerar Documento de Cobrança';
            $arrComandos[] = '<button type="button" accesskey="o" id="btnFechar" onclick="confirmarGeraCobranca()" class="infraButton">
                                    C<span class="infraTeclaAtalho">o</span>nfirmar
                              </button>';
            $arrComandos[] = '<button type="button" accesskey="c" id="btnFechar" onclick="infraFecharJanelaModal();" class="infraButton">
                                    <span class="infraTeclaAtalho">C</span>ancelar
                              </button>';

            $mdCorParametroArRN = new MdCorParametroArRN();
            $mdCorParametroArDTO = new MdCorParametroArDTO();
            $mdCorParametroArDTO->retStrProtocoloFormatadoCobranca();
            $mdCorParametroArDTO = $mdCorParametroArRN->consultar($mdCorParametroArDTO);

            if (!empty($_POST['hdnCodIdSolicitacao'])) {
                $arrCodigoIdSolicitacao = $_POST['hdnCodIdSolicitacao'];

                $mdCorArCobrancaRN = new MdCorArCobrancaRN();
                $mdCorArCobrancaRN->gerarDocumentoCobranca($arrCodigoIdSolicitacao);

                echo '<script> window.parent.location.reload();infraFecharJanelaModal();</script>';
            }
            break;
        default:
            throw new InfraException("Ação '" . $_GET['acao'] . "' não reconhecida.");
    }

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
?>
#slTipoRetorno {position:absolute; left:0; top:50%;}
<?
PaginaSEI::getInstance()->fecharStyle();
PaginaSEI::getInstance()->montarJavaScript();
require_once('md_cor_retorno_ar_gera_cobranca_js.php');
PaginaSEI::getInstance()->fecharHead();
PaginaSEI::getInstance()->abrirBody($strTitulo, 'onload="inicializar();"');
PaginaSEI::getInstance()->abrirAreaDados();
PaginaSEI::getInstance()->abrirAreaDados('4em');
?>
<form id="frmConsulta" method="post" action="">
    <label id="lblslTipoRetorno" for="slTipoRetorno" accesskey="o" class="infraLabelOpcional">Será gerado um
        documento de cobrança no processo:
        <b><?php echo $mdCorParametroArDTO->getStrProtocoloFormatadoCobranca(); ?></b>
    </label>
    <input type="hidden" name="hdnCodIdSolicitacao" id="hdnCodIdSolicitacao"/>
</form>
<?php
PaginaSEI::getInstance()->fecharAreaDados();
PaginaSEI::getInstance()->montarBarraComandosInferior($arrComandos);
PaginaSEI::getInstance()->abrirAreaDados();
PaginaSEI::getInstance()->montarAreaTabela($strResultado, $numRegistros);
PaginaSEI::getInstance()->fecharAreaDados();
PaginaSEI::getInstance()->fecharBody();
PaginaSEI::getInstance()->fecharHtml();
?>
