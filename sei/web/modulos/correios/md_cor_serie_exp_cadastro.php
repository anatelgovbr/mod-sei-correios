<?
/**
 * TRIBUNAL REGIONAL FEDERAL DA 4ª REGIÃO
 *
 * 21/12/2016 - criado por CAST
 *
 * Versão do Gerador de Código: 1.39.0
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
    PaginaSEI::getInstance()->verificarSelecao('md_cor_serie_exp_selecionar');
    SessaoSEI::getInstance()->validarPermissao($_GET['acao']);

    //$objMdCorSerieExpDTO = new MdCorSerieExpDTO();

    $strDesabilitar = '';

    $arrComandos = array();
    $strLinkTipoDocumentoSelecao = SessaoSEI::getInstance()->assinarLink('controlador.php?acao=md_cor_serie_exp_selecionar&tipo_selecao=2&id_object=objLupaTipoDocumento');
    $strLinkAjaxTipoDocumento = SessaoSEI::getInstance()->assinarLink('controlador_ajax.php?acao_ajax=md_cor_serie_exp_auto_completar');

    switch ($_GET['acao']) {
        case 'md_cor_serie_exp_cadastrar':
            $strTitulo = 'Tipos de Documentos de Expedição';
            $arrComandos[] = '<button type="submit" accesskey="S" name="sbmCadastrarMdCorSerieExp" value="Salvar" class="infraButton"><span class="infraTeclaAtalho">S</span>alvar</button>';
            //$arrComandos[] = '<button type="button" accesskey="C" name="btnCancelar" id="btnCancelar" value="Cancelar" onclick="location.href=\''.SessaoSEI::getInstance()->assinarLink('controlador.php?acao='.PaginaSEI::getInstance()->getAcaoRetorno().'&acao_origem='.$_GET['acao']).'\';" class="infraButton"><span class="infraTeclaAtalho">C</span>ancelar</button>';

            //$arrComandos[] = '<button type="button" accesskey="C" name="btnFechar" value="Fechar" onclick="location.href=\''.SessaoSEI::getInstance()->assinarLink('controlador.php?acao='.PaginaSEI::getInstance()->getAcaoRetorno().'&acao_origem='.$_GET['acao'].PaginaSEI::getInstance()->montarAncora($_GET['id_unidade_solicitante'])).'\';" class="infraButton">Fe<span class="infraTeclaAtalho">c</span>har</button>';
            $arrComandos[] = '<button type="button" accesskey="C" name="btnCancelar" id="btnCancelar" value="Cancelar" onclick="location.href=\'' . SessaoSEI::getInstance()->assinarLink('controlador.php?acao=procedimento_controlar&acao_origem=' . $_GET['acao']) . '\';" class="infraButton"><span class="infraTeclaAtalho">C</span>ancelar</button>';

            //$objMdCorSerieExpDTO->setNumIdSerie(null);
            //$objMdCorSerieExpDTO->setStrSinAtivo('S');

            if (isset($_POST['sbmCadastrarMdCorSerieExp'])) {
                try {
                    // Excluindo
                    $objMdCorSerieExpDTO = new MdCorSerieExpDTO();
                    $objMdCorSerieExpRN = new MdCorSerieExpRN();
                    $objMdCorSerieExpDTO->retTodos();
                    $objMdCorSerieExpDTO->setStrSinAtivo('S');
                    $arrObjMdCorSerieExpDTO = $objMdCorSerieExpRN->listar($objMdCorSerieExpDTO);


                    $countArrObjMdCorSerieExpDTO = is_array($arrObjMdCorSerieExpDTO) ? count($arrObjMdCorSerieExpDTO) : 0;
                    if ($countArrObjMdCorSerieExpDTO > 0) {
                        foreach ($arrObjMdCorSerieExpDTO as $objMdCorSerieExpDTO) {
                            $arrIdObjMdCorSerieExpBD[] = $objMdCorSerieExpDTO->getNumIdSerie();
                        }
                    }

                    if ($_POST['hdnTipoDocumento'] != '') {
                        $arrTipoDocumentos = PaginaSEI::getInstance()->getArrValuesSelect($_POST['hdnTipoDocumento']);
                        $countArrTipoDocumentos = is_array($arrTipoDocumentos) ? count($arrTipoDocumentos) : 0;

                        // Excluir registros
                        $objMdCorSerieExpDTO = new MdCorSerieExpDTO();
                        $objMdCorSerieExpRN = new MdCorSerieExpRN();
                        $objMdCorSerieExpDTO->setNumIdSerie($arrTipoDocumentos, infraDTO::$OPER_NOT_IN);
                        $objMdCorSerieExpDTO->retTodos();
                        $listObjMdCorSerieExpDTO = $objMdCorSerieExpRN->listar($objMdCorSerieExpDTO);
                        $countListObjMdCorSerieExpDTO = is_array($listObjMdCorSerieExpDTO) ? count($listObjMdCorSerieExpDTO) : 0;
                        if ($countListObjMdCorSerieExpDTO > 0) {
                            $objMdCorSerieExpRN->excluir($listObjMdCorSerieExpDTO);
                        }

                        //Adicionar os registros
                        if ($countArrTipoDocumentos > 0) {
                            foreach ($arrTipoDocumentos as $idTipoDococumento) {
                                $arrIdObjMdCorSerieExpBD[] = $objMdCorSerieExpDTO->getNumIdSerie();
                                if (!in_array($idTipoDococumento, $arrIdObjMdCorSerieExpBD)) {
                                    $objMdCorSerieExpDTO = new MdCorSerieExpDTO();
                                    $objMdCorSerieExpDTO->setNumIdSerie($idTipoDococumento);
                                    $objMdCorSerieExpDTO->setStrSinAtivo('S');
                                    $objMdCorSerieExpDTO = $objMdCorSerieExpRN->cadastrar($objMdCorSerieExpDTO);
                                }
                            }
                        }
                    }

                    PaginaSEI::getInstance()->adicionarMensagem('Operação realizada com sucesso.', InfraPagina::$TIPO_MSG_AVISO);
                } catch (Exception $e) {
                    PaginaSEI::getInstance()->processarExcecao($e);
                }
            }
            break;

        default:
            throw new InfraException("Ação '" . $_GET['acao'] . "' não reconhecida.");
    }

    // Recuperando do BD
    $objMdCorSerieExpDTO = new MdCorSerieExpDTO();
    $objMdCorSerieExpRN = new MdCorSerieExpRN();
    $objMdCorSerieExpDTO->retTodos();
    $objMdCorSerieExpDTO->retStrNomeSerie();
    $objMdCorSerieExpDTO->setStrSinAtivo('S');
    $objMdCorSerieExpDTO->setOrdStrNomeSerie(InfraDTO::$TIPO_ORDENACAO_ASC);
    $arrObjMdCorSerieExpDTO = $objMdCorSerieExpRN->listar($objMdCorSerieExpDTO);

    $strItensSel = InfraINT::montarSelectArrInfraDTO(null, null, null, $arrObjMdCorSerieExpDTO, 'IdSerie', 'NomeSerie');

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
include_once('md_cor_serie_exp_cadastro_css.php');
PaginaSEI::getInstance()->fecharStyle();
PaginaSEI::getInstance()->montarJavaScript();
PaginaSEI::getInstance()->fecharHead();
PaginaSEI::getInstance()->abrirBody($strTitulo, 'onload="inicializar();"');
?>
    <form id="frmMdCorSerieExpCadastro" method="post" onsubmit="return OnSubmitForm();" action="<?= SessaoSEI::getInstance()->assinarLink('controlador.php?acao=' . $_GET['acao'] . '&acao_origem=' . $_GET['acao']) ?>">
        <?
        PaginaSEI::getInstance()->montarBarraComandosSuperior($arrComandos);
        PaginaSEI::getInstance()->abrirAreaDados('30em');
        ?>
        <div class="row">
            <div class="col-sm-6 col-md-6 col-lg-5 col-xl-5">
                <div class="form-group">
                    <label id="lblDescricao" for="txtSerie" accesskey="q" class="infraLabelObrigatorio">
                        Tipos de Documentos de Expedição:
                    </label>
                    <input type="text" id="txtSerie" name="txtSerie" class="infraText form-control" tabindex="<?= PaginaSEI::getInstance()->getProxTabDados() ?>"/>
                    <input type="hidden" id="hdnIdSerie" name="hdnIdSerie" value="<?= $_POST['hdnIdSerie'] ?>"/>
                </div>
            </div>
        </div>

        <div class="row mt-n3" id="divSelDescricao">
            <div class="col-sm-8 col-md-8 col-lg-7 col-xl-7">
                <div class="form-group">
                    <div class="input-group mb-3">
                        <select id="selDescricao" name="selDescricao" size="8" multiple="multiple" class="infraSelect form-control" tabindex="<?= PaginaSEI::getInstance()->getProxTabDados() ?>">
                            <?= $strItensSel ?>
                        </select>
                        <div class="divIcones">
                            <img id="imgLupaTipoDocumento" onclick="objLupaTipoDocumento.selecionar(700,500);" src="<?= PaginaSEI::getInstance()->getDiretorioSvgGlobal() ?>/pesquisar.svg"  alt="Selecionar Tipo de Documento" title="Selecionar Tipo de Documento" class="infraImg"/></br>
                            <img id="imgExcluirTipoDocumento" onclick="objLupaTipoDocumento.remover();" src="<?= PaginaSEI::getInstance()->getDiretorioSvgGlobal() ?>/remover.svg" alt="Remover Tipo de Documento Selecionado" title="Remover Tipo de Documento Selecionado" class="infraImg"/>
                        </div>
                        <input type="hidden" id="hdnIdTipoDocumento" name="hdnIdTipoDocumento" value=""/>
                        <input type="hidden" id="hdnTipoDocumento" name="hdnTipoDocumento" value="<?= $_POST['hdnTipoDocumento'] ?>"/>
                    </div>
                </div>
            </div>
        </div>
        <? PaginaSEI::getInstance()->fecharAreaDados(); ?>
    </form>
<?
PaginaSEI::getInstance()->fecharBody();
PaginaSEI::getInstance()->fecharHtml();
include_once('md_cor_serie_exp_cadastro_js.php');
?>