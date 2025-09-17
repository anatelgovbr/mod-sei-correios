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

    //////////////////////////////////////////////////////////////////////////////
//    InfraDebug::getInstance()->setBolLigado(false);
//    InfraDebug::getInstance()->setBolDebugInfra(true);
//    InfraDebug::getInstance()->limpar();
    //////////////////////////////////////////////////////////////////////////////

    SessaoSEI::getInstance()->validarLink();

    PaginaSEI::getInstance()->verificarSelecao('md_cor_contrato_selecionar');

    SessaoSEI::getInstance()->validarPermissao($_GET['acao']);

    $arrComandos = array();
    $mdCorTipoCorrespondencia = MdCorTipoCorrespondencINT::montarSelectIdMdCorTipoCorrespondenc('null', '', 'null');

    switch ($_GET['acao']) {
        case 'md_cor_retorno_ar_listar':
            $strLinkTipoDocumentoSelecao = SessaoSEI::getInstance()->assinarLink('controlador.php?acao=md_cor_serie_exp_selecionar&tipo_selecao=1&id_object=objLupaTipoDocumento');
            $strTitulo = 'Lista de Processamento de Retorno de AR';
            $arrComandos[] = '<button type="button" accesskey="P" name="sbmParametro" value="Salvar" onclick="pesquisar();" class="infraButton"><span class="infraTeclaAtalho">P</span>esquisar</button>';
//            $arrComandos[] = '<button type="button" accesskey="A" name="autenticar" value="Autenticar em Lote" onclick="autenticarLote();" class="infraButton"><span class="infraTeclaAtalho">A</span>utenticar em Lote</button>';
            $arrComandos[] = '<button type="button" accesskey="N" name="btnCancelar" id="btnCancelar" value="Cancelar" onclick="location.href=\'' . SessaoSEI::getInstance()->assinarLink('controlador.php?acao=md_cor_retorno_ar_cadastrar&acao_origem=' . $_GET['acao']) . '\';" class="infraButton"><span class="infraTeclaAtalho">N</span>ovo Processamento</button>';
            $arrComandos[] = '<button type="button" accesskey="I" id="btnImprimir" onclick="infraImprimirTabela()" class="infraButton">
                                    <span class="infraTeclaAtalho">I</span>mprimir
                              </button>';
            $arrComandos[] = '<button type="button" accesskey="c" id="btnFechar" onclick="fechar()" class="infraButton">
                                    Fe<span class="infraTeclaAtalho">c</span>har
                              </button>';

            require_once('md_cor_retorno_ar_lista_tabela.php');
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
PaginaSEI::getInstance()->montarTitle(PaginaSEI::getInstance()->getStrNomeSistema() . ' - ' . $strTitulo);
PaginaSEI::getInstance()->montarStyle();
PaginaSEI::getInstance()->abrirStyle();
require_once('md_cor_estilos_css.php');
PaginaSEI::getInstance()->fecharStyle();
PaginaSEI::getInstance()->montarJavaScript();
PaginaSEI::getInstance()->abrirJavaScript();
PaginaSEI::getInstance()->fecharJavaScript();
PaginaSEI::getInstance()->fecharHead();
PaginaSEI::getInstance()->abrirBody($strTitulo, 'onload="inicializar();"');
?>
    <form id="frmConsulta" method="post" action="<?= PaginaSEI::getInstance()->formatarXHTML(SessaoSEI::getInstance()->assinarLink('controlador.php?acao=' . $_GET['acao'] . '&acao_origem=' . $_GET['acao'])) ?>">
        <? PaginaSEI::getInstance()->montarBarraComandosSuperior($arrComandos); ?>
        <div class="row">
            <div class="col-12 col-sm-4 col-md-4 col-lg-4 col-xl-3">
                <div class="form-group">
                    <label id="lblCodigoPlp" for="txtCodigoPlp" class="infraLabelOpcional">PLP:</label><br/>
                    <input type="text" id="txtCodigoPlp" name="txtCodigoPlp" class="infraText form-control" value="<?= PaginaSEI::tratarHTML($_POST['txtCodigoPlp']) ?>" maxlength="50" tabindex="502">
                </div>
            </div>
            <div class="col-12 col-sm-4 col-md-4 col-lg-4 col-xl-3">
                <div class="form-group">
                    <label id="lblCodRastreamento" for="txtCodRastreamento" class="infraLabelOpcional">Rastreamento:</label><br/>
                    <input type="text" id="txtCodRastreamento" name="txtCodRastreamento" class="infraText form-control" value="<?= PaginaSEI::tratarHTML($_POST['txtCodRastreamento']) ?>" maxlength="250" tabindex="503">
                </div>
            </div>
            <div class="col-12 col-sm-4 col-md-4 col-lg-4 col-xl-3">
                <div class="form-group">
                    <label id="lblNumDocumentoPrincipal" for="numDocumentoPrincipal" class="infraLabelOpcional">Documento Principal:</label><br/>
                    <input type="text" id="numDocumentoPrincipal" name="numDocumentoPrincipal" class="infraText form-control" value="<?= PaginaSEI::tratarHTML($_POST['numDocumentoPrincipal']) ?>" maxlength="250" tabindex="503">
                </div>
            </div>
            <div class="col-12 col-sm-4 col-md-4 col-lg-4 col-xl-3">
                <div class="form-group">
                    <label id="lblNumProcesso" for="numProcesso" class="infraLabelOpcional">Processo:</label><br/>
                    <input type="text" id="numProcesso" name="numProcesso" class="infraText form-control" value="<?= PaginaSEI::tratarHTML($_POST['numProcesso']) ?>" maxlength="250" tabindex="503">
                </div>
            </div>
            <div class="col-12 col-sm-8 col-md-8 col-lg-6 col-xl-5">
                <div class="form-group">
                    <label id="lblPeriodoProcessamento" for="txtPeriodoProcessamentoInicio" accesskey="o" class="infraLabelOpcional">Período do Processamento:</label><br/>
                    <div class="input-group input-group-sm mb-3 pt-0">

                        <span class="input-group-text group input-group-sm" id="basic-addon1">De</span>

                        <input type="text" name="txtPeriodoProcessamentoInicio" id="txtPeriodoProcessamentoInicio" class="infraText form-control" 
                               onkeypress="return infraMascara(this, event,'##/##/####')" tabindex="<?= PaginaSEI::getInstance()->getProxTabDados() ?>" />

                        <img src="<?php echo PaginaSEI::getInstance()->getDiretorioSvgGlobal(); ?>/calendario.svg"
                            id="imgCalDthPeriodoInicio" class="infraImg"
                            title="Selecionar Data de Início do Período" alt="Selecionar Data de Início do Período"
                            onclick="infraCalendario('txtPeriodoProcessamentoInicio',this,false,'');">

                        <span class="input-group-text" id="basic-addon1">Até</span>

                        <input type="text" onkeypress="return infraMascara(this, event,'##/##/####')" id="txtPeriodoProcessamentoFim"
                            name="txtPeriodoProcessamentoFim" class="infraText form-control" tabindex="<?= PaginaSEI::getInstance()->getProxTabDados() ?>" />

                        <img src="<?php echo PaginaSEI::getInstance()->getDiretorioSvgGlobal(); ?>/calendario.svg"
                            id="imgCalDthPeriodoFim" class="infraImg mt-1"
                            title="Selecionar Data de Fim do Período" alt="Selecionar Data de Fim do Período"
                            onclick="infraCalendario('txtPeriodoProcessamentoFim',this,false,'');">

                    </div>
                </div>
            </div>
        </div>
        <div class="row">
            <div class="col-sm-12 col-md-12 col-lg-12 col-xl-12">
                <?php PaginaSEI::getInstance()->montarAreaTabela($strResultado, $numRegistros); ?>
            </div>
        </div>
    </form>
<?php
require_once('md_cor_retorno_ar_lista_js.php');
PaginaSEI::getInstance()->fecharBody();
PaginaSEI::getInstance()->fecharHtml();
?>