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

    PaginaSEI::getInstance()->salvarCamposPost(array('selStatus', 'txtDocumentoPrincipal', 'txtCodigoRastreio', 'txtDestinatario', 'txtEndereco', 'txtDiasAtraso'));

    switch ($_GET['acao']) {
        case 'md_cor_ar_pendente_listar':
            $strLinkGerarCobranca = SessaoSEI::getInstance()->assinarLink('controlador.php?acao=md_cor_cobranca_gerar');
            $strTitulo = 'ARs Pendentes de Retorno';
            $arrComandos[] = '<button type="button" accesskey="P" name="sbmParametro" value="Salvar" onclick="pesquisar();" class="infraButton"><span class="infraTeclaAtalho">P</span>esquisar</button>';
            $arrComandos[] = '<button type="button" accesskey="g" name="sbmParametro" value="Gerar" onclick="abrirGerarCobranca(\'' . $strLinkGerarCobranca . '\')" class="infraButton"><span class="infraTeclaAtalho">G</span>erar Documento de Cobrança</button>';
            $arrComandos[] = '<button type="button" accesskey="c" id="btnFechar" onclick="fechar()" class="infraButton">
                                    Fe<span class="infraTeclaAtalho">c</span>har</button>';

            require_once('md_cor_ar_pendente_lista_tabela.php');
            break;
        default:
            throw new InfraException("Ação '" . $_GET['acao'] . "' não reconhecida.");
    }

    $mdCorRetornoArINT = MdCorRetornoArINT::montarSelectIdStatusArPendente('null', '', $selStatus);

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
include_once('md_cor_estilos_css.php');
PaginaSEI::getInstance()->fecharStyle();
PaginaSEI::getInstance()->montarJavaScript();
PaginaSEI::getInstance()->fecharHead();
PaginaSEI::getInstance()->abrirBody($strTitulo, 'onload="inicializar();"');
?>

    <form id="frmConsulta" method="post" action="<?= PaginaSEI::getInstance()->formatarXHTML(SessaoSEI::getInstance()->assinarLink('controlador.php?acao=' . $_GET['acao'] . '&acao_origem=' . $_GET['acao'])) ?>">

        <?php PaginaSEI::getInstance()->montarBarraComandosSuperior($arrComandos); ?>

        <div class="row">
            <div class="col-12 col-sm-6 col-md-4 col-lg-4 col-xl-3">
                <div class="form-group">
                    <label id="lblDocumentoPrincipal" for="txtDocumentoPrincipal" accesskey="t" class="infraLabelOpcional">Documento Principal:</label>
                    <input type="text"
                            id="txtDocumentoPrincipal"
                            name="txtDocumentoPrincipal"
                            class="infraText form-control"
                            value="<?= PaginaSEI::tratarHTML($txtDocumentoPrincipal) ?>"
                            tabindex="<?= PaginaSEI::getInstance()->getProxTabDados() ?>"/>
                </div>
            </div>
            <div class="col-12 col-sm-6 col-md-4 col-lg-4 col-xl-3">
                <div class="form-group">
                    <label id="lblCodigoRastreio" for="txtCodigoRastreio" accesskey="r" class="infraLabelOpcional">Rastreamento:</label>
                    <input type="text"
                            id="txtCodigoRastreio"
                            name="txtCodigoRastreio"
                            class="infraText form-control"
                            value="<?= PaginaSEI::tratarHTML($txtCodigoRastreio) ?>"
                            tabindex="<?= PaginaSEI::getInstance()->getProxTabDados() ?>" />
                </div>
            </div>
            <div class="col-12 col-sm-8 col-md-4 col-lg-4 col-xl-4">
                <div class="form-group">
                    <label id="lblDestinatario" for="txtDestinatario" accesskey="d" class="infraLabelOpcional">Destinatário:</label>
                    <input type="text"
                            id="txtDestinatario"
                            name="txtDestinatario"
                            class="infraText form-control"
                            value="<?= PaginaSEI::tratarHTML($txtDestinatario) ?>"
                            tabindex="<?= PaginaSEI::getInstance()->getProxTabDados() ?>" />
                </div>
            </div>
            <div class="col-12 col-sm-4 col-md-4 col-lg-4 col-xl-2">
                <div class="form-group">
                    <label id="lblDiasAtraso" for="txtDiasAtraso" accesskey="a" class="infraLabelOpcional">
                        Dias em Atraso:
                        <img id="ancAjuda" src="<?= PaginaSEI::getInstance()->getDiretorioSvgGlobal() ?>/ajuda.svg" class="infraImg imgIconeMenor" style="vertical-align: top;" <?= PaginaSEI::montarTitleTooltip('Quantidade de dias que o objeto foi enviado e não retornado após o Prazo Padrão para Retorno de AR, informado na parametrização.', 'Ajuda') ?>/>
                    </label>
                    <input type="text"
                            id="txtDiasAtraso"
                            name="txtDiasAtraso"
                            class="infraText form-control"
                            value="<?= PaginaSEI::tratarHTML($txtDiasAtraso) ?>"
                            tabindex="<?= PaginaSEI::getInstance()->getProxTabDados() ?>" />
                </div>
            </div>
            <div class="col-12 col-sm-6 col-md-4 col-lg-4 col-xl-4">
                <div class="form-group">
                    <label id="lblEndereco" for="txtEndereco" accesskey="e" class="infraLabelOpcional">Endereço:</label>
                    <input type="text"
                            id="txtEndereco"
                            name="txtEndereco"
                            class="infraText form-control"
                            value="<?= PaginaSEI::tratarHTML($txtEndereco) ?>"
                            tabindex="<?= PaginaSEI::getInstance()->getProxTabDados() ?>" />
                </div>
            </div>
            
            <div class="col-12 col-sm-6 col-md-4 col-lg-4 col-xl-4">
                <div class="form-group">
                    <label id="lblStatus" for="selStatus" accesskey="o" class="infraLabelOpcional">Tipo de Atraso:</label>
                    <select class="infraSelect form-select" name="slServicoPostal" id="slServicoPostal" tabindex="<?= PaginaSEI::getInstance()->getProxTabDados(); ?>">
                        <?php echo $mdCorRetornoArINT; ?>
                    </select>
                </div>
            </div>
        </div>
        
        <div class="row linha">
            <div class="col-sm-12 col-md-12 col-lg-12 col-xl-12">
                <?php PaginaSEI::getInstance()->montarAreaTabela($strResultado, $numRegistros); ?>
            </div>
        </div>
        <?php PaginaSEI::getInstance()->montarBarraComandosInferior($arrComandos, true); ?>

    </form>
<?php
require_once('md_cor_ar_pendente_lista_js.php');
PaginaSEI::getInstance()->fecharBody();
PaginaSEI::getInstance()->fecharHtml();
?>