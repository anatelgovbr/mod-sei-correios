<?
/**
 * TRIBUNAL REGIONAL FEDERAL DA 4ª REGIÃO
 *
 * 14/11/2017 - criado por ellyson.silva
 *
 * Versão do Gerador de Código: 1.41.0
 *
 * Versão no SVN: $Id$
 */

try {
    require_once dirname(__FILE__) . '/../../SEI.php';

    session_start();

    //////////////////////////////////////////////////////////////////////////////
    //InfraDebug::getInstance()->setBolLigado(false);
    //InfraDebug::getInstance()->setBolDebugInfra(true);
    //InfraDebug::getInstance()->limpar();
    //////////////////////////////////////////////////////////////////////////////

    SessaoSEI::getInstance()->validarLink();

    PaginaSEI::getInstance()->verificarSelecao('md_cor_objeto_selecionar');

    SessaoSEI::getInstance()->validarPermissao($_GET['acao']);

    $objMdCorObjetoDTO = new MdCorObjetoDTO();

    $strDesabilitar = '';

    $arrComandos = array();

    switch ($_GET['acao']) {
        case 'md_cor_objeto_cadastrar':
            $strTitulo = 'Tipo de Embalagem';
            $arrComandos[] = '<button type="submit" accesskey="S" name="sbmCadastrarMdCorObjeto" value="Salvar" class="infraButton"><span class="infraTeclaAtalho">S</span>alvar</button>';
            $arrComandos[] = '<button type="button" accesskey="C" name="btnCancelar" id="btnCancelar" value="Cancelar" onclick="location.href=\'' . SessaoSEI::getInstance()->assinarLink('controlador.php?acao=' . PaginaSEI::getInstance()->getAcaoRetorno() . '&acao_origem=' . $_GET['acao'] . '&id_md_cor_contrato=' . $_GET['id_md_cor_contrato']) . '\';" class="infraButton"><span class="infraTeclaAtalho">C</span>ancelar</button>';

            $objMdCorObjetoDTO->setNumIdMdCorObjeto(null);
            $numIdMdCorTipoObjeto = $_POST['selMdCorTipoObjeto'];
            if ($numIdMdCorTipoObjeto !== '') {
                $objMdCorObjetoDTO->setNumIdMdCorTipoObjeto($numIdMdCorTipoObjeto);
            } else {
                $objMdCorObjetoDTO->setNumIdMdCorTipoObjeto(null);
            }

            $objMdCorObjetoDTO->setNumIdMdCorContrato($_POST['hdnIdMdCorContrato']);
            $objMdCorObjetoDTO->setStrTipoRotuloImpressao($_POST['rdoTipoRotuloImpressao']);
            $objMdCorObjetoDTO->setStrSinObjetoPadrao($_POST['rdoSinObjetoPadrao']);
            $objMdCorObjetoDTO->setStrSinAtivo('S');
            $objMdCorObjetoDTO->setDblMargemSuperiorImpressao($_POST['txtMargemSuperiorImpressao']);
            $objMdCorObjetoDTO->setDblMargemEsquerdaImpressao($_POST['txtMargemEsquerdaImpressao']);

            if (isset($_POST['sbmCadastrarMdCorObjeto'])) {
                try {
                    $objMdCorObjetoRN = new MdCorObjetoRN();
                    $objMdCorObjetoDTO = $objMdCorObjetoRN->cadastrar($objMdCorObjetoDTO);

                    PaginaSEI::getInstance()->adicionarMensagem('objeto "' . $objMdCorObjetoDTO->getNumIdMdCorTipoObjeto() . '" cadastrada com sucesso.');
                    header('Location: ' . SessaoSEI::getInstance()->assinarLink('controlador.php?acao=' . PaginaSEI::getInstance()->getAcaoRetorno() . '&acao_origem=' . $_GET['acao'] . '&id_md_cor_objeto=' . $objMdCorObjetoDTO->getNumIdMdCorObjeto() . '&id_md_cor_contrato=' . $_GET['id_md_cor_contrato'] . PaginaSEI::getInstance()->montarAncora($objMdCorObjetoDTO->getNumIdMdCorObjeto())));
                    die;
                } catch (Exception $e) {
                    PaginaSEI::getInstance()->processarExcecao($e);
                }
            }
            break;

        case 'md_cor_objeto_alterar':
            $strTitulo = 'Alterar Embalagem';
            $arrComandos[] = '<button type="submit" accesskey="S" name="sbmAlterarMdCorObjeto" value="Salvar" class="infraButton"><span class="infraTeclaAtalho">S</span>alvar</button>';
            $strDesabilitar = 'disabled="disabled"';

            if (isset($_GET['id_md_cor_objeto'])) {
                $objMdCorObjetoDTO->setNumIdMdCorObjeto($_GET['id_md_cor_objeto']);
                $objMdCorObjetoDTO->retTodos();
                $objMdCorObjetoRN = new MdCorObjetoRN();
                $objMdCorObjetoDTO = $objMdCorObjetoRN->consultar($objMdCorObjetoDTO);
                if ($objMdCorObjetoDTO == null) {
                    throw new InfraException("Registro não encontrado.");
                }
            } else {
                $objMdCorObjetoDTO->setNumIdMdCorObjeto($_POST['hdnIdMdCorObjeto']);
                $objMdCorObjetoDTO->setNumIdMdCorTipoObjeto($_POST['selMdCorTipoObjeto']);
                $objMdCorObjetoDTO->setNumIdMdCorContrato($_POST['hdnIdMdCorContrato']);
                $objMdCorObjetoDTO->setStrTipoRotuloImpressao($_POST['rdoTipoRotuloImpressao']);
                $objMdCorObjetoDTO->setStrSinObjetoPadrao($_POST['rdoSinObjetoPadrao']);
                $objMdCorObjetoDTO->setStrSinAtivo('S');
                $objMdCorObjetoDTO->setDblMargemSuperiorImpressao($_POST['txtMargemSuperiorImpressao']);
                $objMdCorObjetoDTO->setDblMargemEsquerdaImpressao($_POST['txtMargemEsquerdaImpressao']);
            }

            $arrComandos[] = '<button type="button" accesskey="C" name="btnCancelar" id="btnCancelar" value="Cancelar" onclick="location.href=\'' . SessaoSEI::getInstance()->assinarLink('controlador.php?acao=' . PaginaSEI::getInstance()->getAcaoRetorno() . '&acao_origem=' . $_GET['acao'] . '&id_md_cor_contrato=' . $_GET['id_md_cor_contrato'] . PaginaSEI::getInstance()->montarAncora($objMdCorObjetoDTO->getNumIdMdCorObjeto())) . '\';" class="infraButton"><span class="infraTeclaAtalho">C</span>ancelar</button>';

            if (isset($_POST['sbmAlterarMdCorObjeto'])) {
                try {
                    $objMdCorObjetoRN = new MdCorObjetoRN();
                    $objMdCorObjetoRN->alterar($objMdCorObjetoDTO);
                    PaginaSEI::getInstance()->adicionarMensagem('objeto "' . $objMdCorObjetoDTO->getNumIdMdCorTipoObjeto() . '" alterada com sucesso.');
                    header('Location: ' . SessaoSEI::getInstance()->assinarLink('controlador.php?acao=' . PaginaSEI::getInstance()->getAcaoRetorno() . '&acao_origem=' . $_GET['acao'] . '&id_md_cor_contrato=' . $_GET['id_md_cor_contrato'] . PaginaSEI::getInstance()->montarAncora($objMdCorObjetoDTO->getNumIdMdCorObjeto())));
                    die;
                } catch (Exception $e) {
                    PaginaSEI::getInstance()->processarExcecao($e);
                }
            }
            break;

        case 'md_cor_objeto_consultar':
            $strTitulo = 'Consultar objeto';
            $arrComandos[] = '<button type="button" accesskey="F" name="btnFechar" value="Fechar" onclick="location.href=\'' . SessaoSEI::getInstance()->assinarLink('controlador.php?acao=' . PaginaSEI::getInstance()->getAcaoRetorno() . '&acao_origem=' . $_GET['acao'] . '&id_md_cor_contrato=' . $_GET['id_md_cor_contrato'] . PaginaSEI::getInstance()->montarAncora($_GET['id_md_cor_objeto'])) . '\';" class="infraButton">Fe<span class="infraTeclaAtalho">c</span>har</button>';
            $objMdCorObjetoDTO->setNumIdMdCorObjeto($_GET['id_md_cor_objeto']);
            $objMdCorObjetoDTO->setBolExclusaoLogica(false);
            $objMdCorObjetoDTO->retTodos();
            $objMdCorObjetoRN = new MdCorObjetoRN();
            $objMdCorObjetoDTO = $objMdCorObjetoRN->consultar($objMdCorObjetoDTO);
            if ($objMdCorObjetoDTO === null) {
                throw new InfraException("Registro não encontrado.");
            }
            break;

        default:
            throw new InfraException("Ação '" . $_GET['acao'] . "' não reconhecida.");
    }

    $idContrato = $_GET['id_md_cor_contrato'];
    $strItensSelMdCorTipoObjeto = MdCorTipoObjetoINT::montarSelectNome('null', '&nbsp;', $objMdCorObjetoDTO->getNumIdMdCorTipoObjeto(), $idContrato);

    if ($objMdCorObjetoDTO->getNumIdMdCorContrato() == '') {
        $objMdCorObjetoDTO->setNumIdMdCorContrato($idContrato);
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
PaginaSEI::getInstance()->fecharStyle();
PaginaSEI::getInstance()->montarJavaScript();
PaginaSEI::getInstance()->abrirJavaScript();
?>
<? if (0){ ?>
    <script type="text/javascript"><?}?>

        function inicializar() {
            if ('<?=$_GET['acao']?>' == 'md_cor_objeto_cadastrar') {
                document.getElementById('selMdCorTipoObjeto').focus();
            } else if ('<?=$_GET['acao']?>' == 'md_cor_objeto_consultar') {
                infraDesabilitarCamposAreaDados();
            } else {
                document.getElementById('btnCancelar').focus();
            }
            infraEfeitoTabelas();
        }

        function validarCadastro() {
            if (!infraSelectSelecionado('selMdCorTipoObjeto')) {
                alert('Selecione um tipo de objeto.');
                document.getElementById('selMdCorTipoObjeto').focus();
                return false;
            }

            if (document.getElementById('optTipoRotuloImpressaoCompleto').checked == false && document.getElementById('optTipoRotuloImpressaoResumido').checked == false) {
                alert('Selecione o tipo de rótulo utilizado para impressão');
                document.getElementById('optTipoRotuloImpressaoResumido').focus();
                return false;
            }

            if (document.getElementById('optSinObjetoPadraoSim').checked == false && document.getElementById('optSinObjetoPadraoNao').checked == false) {
                alert('Selecione o objeto padrão para expedição');
                document.getElementById('optSinObjetoPadraoNao').focus();
                return false;
            }

            if (document.getElementById('txtMargemSuperiorImpressao').value == '') {
                alert('Preencha o campo margem superior.');
                document.getElementById('txtMargemSuperiorImpressao').focus();
                return false;
            }

            if (document.getElementById('txtMargemEsquerdaImpressao').value == '') {
                alert('Preencha o campo margem esquerda.');
                document.getElementById('txtMargemEsquerdaImpressao').focus();
                return false;
            }

            return true;
        }

        function OnSubmitForm() {
            return validarCadastro();
        }

        <? if (0){ ?></script><? } ?>
<?
PaginaSEI::getInstance()->fecharJavaScript();
PaginaSEI::getInstance()->fecharHead();
PaginaSEI::getInstance()->abrirBody($strTitulo, 'onload="inicializar();"');
PaginaSEI::getInstance()->abrirAreaDados();
?>
    <form id="frmMdCorObjetoCadastro" method="post" onsubmit="return OnSubmitForm();"
          action="<?= SessaoSEI::getInstance()->assinarLink('controlador.php?acao=' . $_GET['acao'] . '&acao_origem=' . $_GET['acao'] . '&id_md_cor_contrato=' . $_GET['id_md_cor_contrato']) ?>">
        <?
        PaginaSEI::getInstance()->montarBarraComandosSuperior($arrComandos);
        //PaginaSEI::getInstance()->montarAreaValidacao();
        ?>
        <div class="row">
            <div class="col-sm-12 col-md-6 col-lg-5 col-xl-3">
                <div class="form-group">
                    <label id="lblMdCorTipoObjeto" for="selMdCorTipoObjeto" accesskey="" class="infraLabelObrigatorio">Tipo
                        de Embalagem:</label>
                    <select id="selMdCorTipoObjeto" name="selMdCorTipoObjeto" class="infraSelect form-control"
                            tabindex="<?= PaginaSEI::getInstance()->getProxTabDados() ?>">
                        <?= $strItensSelMdCorTipoObjeto ?>
                    </select>
                </div>
            </div>
        </div>
        <div class="row">
            <div class="col-sm-12 col-md-8 col-lg-5 col-xl-4">
                <div class="form-group">
                    <label id="lblTipoRotuloImpressao" for="lblSinParamModalComplInteres" accesskey=""
                        class="infraLabelObrigatorio">Tipo de Rótulo Utilizado para Impressão:</label>
                    <div id="divTipoRotuloCompleto" class="infraDivRadio">
                        <input type="radio" name="rdoTipoRotuloImpressao"
                            id="optTipoRotuloImpressaoCompleto" <?= PaginaSEI::getInstance()->setRadio($objMdCorObjetoDTO->getStrTipoRotuloImpressao(), 'C') ?>
                            class="infraRadio" tabindex="<?= PaginaSEI::getInstance()->getProxTabDados() ?>">
                        <span id="spnTipoRotuloImpressaoCompleto"><label id="lblTipoRotuloImpressaoCompleto"
                                                                        for="optTipoRotuloImpressaoCompleto"
                                                                        class="infraLabelRadio">Completo</label></span>

                        <input type="radio" name="rdoTipoRotuloImpressao"
                            id="optTipoRotuloImpressaoResumido" <?= PaginaSEI::getInstance()->setRadio($objMdCorObjetoDTO->getStrTipoRotuloImpressao(), 'R') ?>
                            class="infraRadio" tabindex="<?= PaginaSEI::getInstance()->getProxTabDados() ?>">
                        <span id="spnTipoRotuloImpressaoResumido"><label id="lblTipoRotuloImpressaoResumido"
                                                                        for="optTipoRotuloImpressaoResumido"
                                                                        class="infraLabelRadio">Resumido</label></span>
                    </div>
                </div>
            </div>
        </div>
        <div class="row">
            <div class="col-sm-12 col-md-8 col-lg-8 col-xl-8">
                <div class="form-group">
                    <label id="lblSinObjetoPadrao" accesskey="" class="infraLabelObrigatorio">Objeto Padrão para
                        Expedição?</label>
                    <div id="divSinObjetoPadraoSim" class="infraDivRadio">
                        <input type="radio" name="rdoSinObjetoPadrao"
                            id="optSinObjetoPadraoSim" <?= PaginaSEI::getInstance()->setRadio($objMdCorObjetoDTO->getStrSinObjetoPadrao(), 'S') ?>
                            class="infraRadio" tabindex="<?= PaginaSEI::getInstance()->getProxTabDados() ?>">
                        <span id="spnSinObjetoPadraoSim"><label id="lblSinObjetoPadraoSim" for="optSinObjetoPadraoSim"
                                                                class="infraLabelRadio">Sim</label></span>

                        <input type="radio" name="rdoSinObjetoPadrao"
                            id="optSinObjetoPadraoNao" <?= PaginaSEI::getInstance()->setRadio($objMdCorObjetoDTO->getStrSinObjetoPadrao(), 'N') ?>
                            class="infraRadio" tabindex="<?= PaginaSEI::getInstance()->getProxTabDados() ?>">
                        <span id="spnSinObjetoPadraoNao"><label id="lblSinObjetoPadraoNao" for="optSinObjetoPadraoNao"
                                                                class="infraLabelRadio">Não</label></span>
                    </div>
                </div>
            </div>
        </div>
        <div class="row">
            <div class="col-sm-12 col-md-12 col-lg-8 col-xl-6">
                <fieldset class="infraFieldset mb-3 p-3" style="height: 100%">
                    <legend class="infraLegend">&nbsp;Margens&nbsp;</legend>

                    <div class="row">
                        <div class="col-sm-12 col-md-8 col-lg-5 col-xl-6">
                            <div class="form-group">
                                <label id="lblMargemSuperiorImpressao" for="txtMargemSuperiorImpressao" accesskey=""
                                    class="infraLabelObrigatorio">Superior (em cm):</label>
                                <input type="text" id="txtMargemSuperiorImpressao" name="txtMargemSuperiorImpressao"
                                    onkeypress="return infraMascaraDecimais(this, '', ',', event, 2, 10)"
                                    class="infraText form-control"
                                    value="<?= PaginaSEI::tratarHTML($objMdCorObjetoDTO->getDblMargemSuperiorImpressao()); ?>"
                                    tabindex="<?= PaginaSEI::getInstance()->getProxTabDados() ?>"/>
                            </div>
                        </div>
                        <div class="col-sm-12 col-md-8 col-lg-5 col-xl-6">
                            <div class="form-group">
                                <label id="lblMargemEsquerdaImpressao" for="txtMargemEsquerdaImpressao" accesskey=""
                                    class="infraLabelObrigatorio">Esquerda (em cm):</label>
                                <input type="text" id="txtMargemEsquerdaImpressao" name="txtMargemEsquerdaImpressao"
                                    onkeypress="return infraMascaraDecimais(this, '', ',', event, 2, 10)"
                                    class="infraText form-control"
                                    value="<?= PaginaSEI::tratarHTML($objMdCorObjetoDTO->getDblMargemEsquerdaImpressao()); ?>"
                                    tabindex="<?= PaginaSEI::getInstance()->getProxTabDados() ?>"/>
                            </div>
                        </div>
                    </div>
                </fieldset>
            </div>
        </div>

        <input type="hidden" id="hdnIdMdCorObjeto" name="hdnIdMdCorObjeto"
               value="<?= $objMdCorObjetoDTO->getNumIdMdCorObjeto(); ?>"/>
        <input type="hidden" id="hdnIdMdCorContrato" name="hdnIdMdCorContrato"
               value="<?= $objMdCorObjetoDTO->getNumIdMdCorContrato(); ?>"/>
        <?
        //PaginaSEI::getInstance()->montarAreaDebug();
        ?>
    </form>
<?
PaginaSEI::getInstance()->fecharAreaDados();
PaginaSEI::getInstance()->fecharBody();
PaginaSEI::getInstance()->fecharHtml();
include_once('md_cor_objeto_cadastro_js.php');