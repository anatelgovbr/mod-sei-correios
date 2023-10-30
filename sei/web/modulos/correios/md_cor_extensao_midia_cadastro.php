<?
/**
 * TRIBUNAL REGIONAL FEDERAL DA 4ª REGIÃO
 *
 * 20/12/2016 - criado por Wilton Júnior
 *
 * Versão do Gerador de Código: 1.39.0
 *
 * Versão no SVN: $Id$
 */

try {
//var_dump(dirname(__FILE__) . '/../../SEI.php');exit;
    require_once dirname(__FILE__) . '/../../SEI.php';
    session_start();

    //////////////////////////////////////////////////////////////////////////////
    //InfraDebug::getInstance()->setBolLigado(false);
    //InfraDebug::getInstance()->setBolDebugInfra(true);
    //InfraDebug::getInstance()->limpar();
    //////////////////////////////////////////////////////////////////////////////

    SessaoSEI::getInstance()->validarLink();
    PaginaSEI::getInstance()->verificarSelecao('md_cor_extensao_midia_selecionar');
    SessaoSEI::getInstance()->validarPermissao($_GET['acao']);

    $objMdCorExtensaoMidiaDTO = new MdCorExtensaoMidiaDTO();
    $strDesabilitar = '';
    $arrComandos = array();

    switch ($_GET['acao']) {
        case 'md_cor_extensao_midia_cadastrar':
            $strTitulo = 'Extensões para Gravação em Mídia';
            $arrComandos[] = '<button type="submit" accesskey="S" name="sbmCadastrarMdCorExtensaoMidia" value="Salvar" class="infraButton"><span class="infraTeclaAtalho">S</span>alvar</button>';

            //$arrComandos[] = '<button type="button" accesskey="C" name="btnCancelar" id="btnCancelar" value="Cancelar" onclick="location.href=\'' . SessaoSEI::getInstance()->assinarLink('controlador.php?acao=' . PaginaSEI::getInstance()->getAcaoRetorno() . '&acao_origem=' . $_GET['acao']) . '\';" class="infraButton"><span class="infraTeclaAtalho">C</span>ancelar</button>';
            $arrComandos[] = '<button type="button" accesskey="C" name="btnCancelar" id="btnCancelar" value="Cancelar" onclick="location.href=\'' . SessaoSEI::getInstance()->assinarLink('controlador.php?acao=procedimento_controlar&acao_origem=' . $_GET['acao']) . '\';" class="infraButton"><span class="infraTeclaAtalho">C</span>ancelar</button>';

            if (isset($_POST['sbmCadastrarMdCorExtensaoMidia'])) {
                try {
                    $objInfraException = new InfraException();

                    $arrValuesExtensoes = PaginaSEI::getInstance()->getArrValuesSelect($_POST['hdnPrincipal']);
                    $objMdCorExtensaoMidiaDTO->retTodos();
                    $objMdCorExtensaoMidiaRN = new MdCorExtensaoMidiaRN();

                    $arrObjMdCorExtensaoMidiaDTO = $objMdCorExtensaoMidiaRN->listar($objMdCorExtensaoMidiaDTO);

                    foreach ($arrObjMdCorExtensaoMidiaDTO as $chave => $objMdCorExtensaoMidiaDTO) {
                        if (in_array($objMdCorExtensaoMidiaDTO->getNumIdArquivoExtensao(), $arrValuesExtensoes)) {
                            $chaveArray = array_search($objMdCorExtensaoMidiaDTO->getNumIdArquivoExtensao(), $arrValuesExtensoes);
                            unset($arrObjMdCorExtensaoMidiaDTO[$chave]);
                            unset($arrValuesExtensoes[$chaveArray]);
                        }
                    }
                    $objMdCorExtensaoMidiaRN->excluir($arrObjMdCorExtensaoMidiaDTO);

                    //$objMdCorExtensaoMidiaRN->excluir($objMdCorExtensaoMidiaRN->listar($objMdCorExtensaoMidiaDTO));
                    if (!$arrValuesExtensoes) {
                        $objInfraException->adicionarValidacao('Informe pelo menos uma extensão para documento principal.');
                    }
                    $objInfraException->lancarValidacoes();
                    $objMdCorExtensaoMidiaRN = new MdCorExtensaoMidiaRN();
                    foreach ($arrValuesExtensoes as $valueExtensao) {
                        $objMdCorExtensaoMidiaDTO->setNumIdArquivoExtensao($valueExtensao);
                        //$objMdCorExtensaoMidiaDTO->setStrNomeExtensao($valueExtensao);
                        $objMdCorExtensaoMidiaDTO->setStrSinAtivo('S');
                        $objMdCorExtensaoMidiaRN->cadastrar($objMdCorExtensaoMidiaDTO);
                    }
                    //$objMdCorExtensaoMidiaDTO = $objMdCorExtensaoMidiaRN->cadastrar($objMdCorExtensaoMidiaDTO);
                    PaginaSEI::getInstance()->adicionarMensagem('Extensões cadastradas com sucesso.');
                    header('Location: ' . SessaoSEI::getInstance()->assinarLink('controlador.php?acao=' . $_GET['acao'] . '&acao_origem=' . $_GET['acao']));
                    die;
                } catch (Exception $e) {
                    PaginaSEI::getInstance()->processarExcecao($e);
                }
            }
            break;

        case 'md_cor_extensao_midia_alterar':
            $strTitulo = 'Alterar a';
            $arrComandos[] = '<button type="submit" accesskey="S" name="sbmAlterarMdCorExtensaoMidia" value="Salvar" class="infraButton"><span class="infraTeclaAtalho">S</span>alvar</button>';
            $strDesabilitar = 'disabled="disabled"';

            if (isset($_GET['id_md_cor_extensao_midia'])) {
                $objMdCorExtensaoMidiaDTO->setNumIdMdCorExtensaoMidia($_GET['id_md_cor_extensao_midia']);
                $objMdCorExtensaoMidiaDTO->retTodos();
                $objMdCorExtensaoMidiaRN = new MdCorExtensaoMidiaRN();
                $objMdCorExtensaoMidiaDTO = $objMdCorExtensaoMidiaRN->consultar($objMdCorExtensaoMidiaDTO);
                if ($objMdCorExtensaoMidiaDTO == null) {
                    throw new InfraException("Registro não encontrado.");
                }
            } else {
                $objMdCorExtensaoMidiaDTO->setNumIdMdCorExtensaoMidia($_POST['hdnIdMdCorExtensaoMidia']);
                $objMdCorExtensaoMidiaDTO->setStrNomeExtensao($_POST['txtNomeExtensao']);
                $objMdCorExtensaoMidiaDTO->setStrSinAtivo('S');
            }

            $arrComandos[] = '<button type="button" accesskey="C" name="btnCancelar" id="btnCancelar" value="Cancelar" onclick="location.href=\'' . SessaoSEI::getInstance()->assinarLink('controlador.php?acao=' . PaginaSEI::getInstance()->getAcaoRetorno() . '&acao_origem=' . $_GET['acao'] . PaginaSEI::getInstance()->montarAncora($objMdCorExtensaoMidiaDTO->getNumIdMdCorExtensaoMidia())) . '\';" class="infraButton"><span class="infraTeclaAtalho">C</span>ancelar</button>';

            if (isset($_POST['sbmAlterarMdCorExtensaoMidia'])) {
                try {
                    $objMdCorExtensaoMidiaRN = new MdCorExtensaoMidiaRN();
                    $objMdCorExtensaoMidiaRN->alterar($objMdCorExtensaoMidiaDTO);
                    PaginaSEI::getInstance()->adicionarMensagem('a "' . $objMdCorExtensaoMidiaDTO->getStrNomeExtensao() . '" alterada com sucesso.');
                    header('Location: ' . SessaoSEI::getInstance()->assinarLink('controlador.php?acao=' . PaginaSEI::getInstance()->getAcaoRetorno() . '&acao_origem=' . $_GET['acao'] . PaginaSEI::getInstance()->montarAncora($objMdCorExtensaoMidiaDTO->getNumIdMdCorExtensaoMidia())));
                    die;
                } catch (Exception $e) {
                    PaginaSEI::getInstance()->processarExcecao($e);
                }
            }
            break;

        case 'md_cor_extensao_midia_consultar':
            $strTitulo = 'Consultar a';
            //$arrComandos[] = '<button type="button" accesskey="C" name="btnFechar" value="Fechar" onclick="location.href=\'' . SessaoSEI::getInstance()->assinarLink('controlador.php?acao=' . PaginaSEI::getInstance()->getAcaoRetorno() . '&acao_origem=' . $_GET['acao'] . PaginaSEI::getInstance()->montarAncora($_GET['id_md_cor_extensao_midia'])) . '\';" class="infraButton">Fe<span class="infraTeclaAtalho">c</span>har</button>';
            $arrComandos[] = '<button type="button" accesskey="C" name="btnFechar" value="Fechar" onclick="location.href=\'' . SessaoSEI::getInstance()->assinarLink('controlador.php?acao=procedimento_controlar&acao_origem=' . $_GET['acao']) . '\';" class="infraButton">Fe<span class="infraTeclaAtalho">c</span>har</button>';

            $objMdCorExtensaoMidiaDTO->setNumIdMdCorExtensaoMidia($_GET['id_md_cor_extensao_midia']);
            $objMdCorExtensaoMidiaDTO->setBolExclusaoLogica(false);
            $objMdCorExtensaoMidiaDTO->retTodos();
            $objMdCorExtensaoMidiaRN = new MdCorExtensaoMidiaRN();
            $objMdCorExtensaoMidiaDTO = $objMdCorExtensaoMidiaRN->consultar($objMdCorExtensaoMidiaDTO);
            if ($objMdCorExtensaoMidiaDTO === null) {
                throw new InfraException("Registro não encontrado.");
            }
            break;

        default:
            throw new InfraException("Ação '" . $_GET['acao'] . "' não reconhecida.");
    }


} catch (Exception $e) {
    PaginaSEI::getInstance()->processarExcecao($e);
}

$strSelectNomeExtensao = MdCorExtensaoMidiaINT::montarSelectNomeExtensao(null, null, null, 'S');

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

$strLinkAjaxPrincipal = SessaoSEI::getInstance()->assinarLink('controlador_ajax.php?acao_ajax=md_cor_extensao_arquivo_listar_todos');
$strLinkPrincipalSelecao = SessaoSEI::getInstance()->assinarLink('controlador.php?acao=md_cor_extensao_arquivo_selecionar&tipo_selecao=2&id_object=objLupaPrincipal');

PaginaSEI::getInstance()->fecharJavaScript();
require_once("md_cor_extensao_midia_cadastro_css.php");
PaginaSEI::getInstance()->fecharHead();
PaginaSEI::getInstance()->abrirBody($strTitulo, 'onload="inicializar();"');
?>
    <form id="frmMdCorExtensaoMidiaCadastro" method="post" onsubmit="return OnSubmitForm();"
          action="<?= SessaoSEI::getInstance()->assinarLink('controlador.php?acao=' . $_GET['acao'] . '&acao_origem=' . $_GET['acao']) ?>">
        <?
        PaginaSEI::getInstance()->montarBarraComandosSuperior($arrComandos);
        PaginaSEI::getInstance()->abrirAreaDados('50em');
        ?>
        <div class="row linha">
            <div class="col-sm-10 col-md-9 col-lg-7">
                <label id="lblPrincipal" for="txtPrincipal" accesskey="P" class="infraLabelObrigatorio">
                    Extensões de Arquivos que somente aceitam Anexar Mídia:
                </label>
                <input type="text" id="txtPrincipal" name="txtPrincipal" class="infraText form-control"
                        onkeypress="return infraMascaraTexto(this,event,50);" maxlength="50"
                        tabindex="<?= PaginaSEI::getInstance()->getProxTabDados() ?>"/>
            </div>
        </div>
            
        <div class="row">
            <div class="col-sm-12 col-md-11 col-lg-9 col-xl-8">
                <div class="input-group mb-3">
                    <select id="selPrincipal" name="selPrincipal" size="8" multiple="multiple"
                            class="infraSelect form-control"
                            tabindex="<?= PaginaSEI::getInstance()->getProxTabDados() ?>">
                        <?= $strSelectNomeExtensao; ?>
                    </select>
                    <div class="botoes">
                        <img id="imgLupaPrincipal" onclick="objLupaPrincipal.selecionar(700,500);"
                                src="<?= PaginaSEI::getInstance()->getDiretorioSvgGlobal(); ?>/pesquisar.svg"
                                alt="Selecionar Extensões" title="Selecionar Extensões" class="infraImg"
                                tabindex="<?= PaginaSEI::getInstance()->getProxTabDados() ?>"/>
                        <br/>
                        <img id="imgExcluirPrincipal" onclick="objLupaPrincipal.remover();"
                                src="<?= PaginaSEI::getInstance()->getDiretorioSvgGlobal(); ?>/remover.svg"
                                alt="Remover Extensões Selecionadas" title="Remover Extensões Selecionadas"
                                class="infraImg" tabindex="<?= PaginaSEI::getInstance()->getProxTabDados() ?>"/>
                    </div>
                </div>
            </div>
        </div>

        <input type="hidden" id="hdnIdPrincipal" name="hdnIdPrincipal" class="infraText" value=""/>
        <input type="hidden" id="hdnPrincipal" name="hdnPrincipal" value=""/>
        <input type="hidden" id="hdnIdMdCorExtensaoMidia" name="hdnIdMdCorExtensaoMidia" value=""/>

        <? PaginaSEI::getInstance()->fecharAreaDados(); ?>
        
    </form>
<?
require_once("md_cor_extensao_midia_cadastro_js.php");
PaginaSEI::getInstance()->fecharBody();
PaginaSEI::getInstance()->fecharHtml();
?>