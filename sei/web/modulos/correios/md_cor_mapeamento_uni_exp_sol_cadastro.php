<?php
/**
 * ANATEL
 *
 * 09/12/2016 - criado por marcelo.emiliano@cast.com.br - CAST
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
    PaginaSEI::getInstance()->verificarSelecao('md_cor_unidade_exp_selecionar');
    SessaoSEI::getInstance()->validarPermissao($_GET['acao']);

    $strLinkAjaxAtualizaUnidade = SessaoSEI::getInstance()->assinarLink('controlador_ajax.php?acao_ajax=md_cor_atualiza_link_unidade');

    if ($_GET['id_unidades']) {
        $strUrlUnidade = SessaoSEI::getInstance()->assinarLink('controlador.php?acao=md_cor_unidade_selecionar_todas&tipo_selecao=2&id_object=objLupaUnidade&intIdUnidadeExpExcecao=' . $_GET['id_unidades'] . '&idUnidadeExpedidora=' . $_GET['id_unidades']);
        $strLinkAjaxAutocompletarUnidade = SessaoSEI::getInstance()->assinarLink('controlador_ajax.php?acao_ajax=md_cor_unidade_mapeadas_auto_completar&intIdUnidadeExpExcecao=' . $_GET['id_unidades'] . '&idUnidadeExpedidora=' . $_GET['id_unidades']);
    } else {
        $strUrlUnidade = SessaoSEI::getInstance()->assinarLink('controlador.php?acao=md_cor_unidade_selecionar_todas&tipo_selecao=2&id_object=objLupaUnidade');
        $strLinkAjaxAutocompletarUnidade = SessaoSEI::getInstance()->assinarLink('controlador_ajax.php?acao_ajax=md_cor_unidade_mapeadas_auto_completar');
    }

    $strItensSelUnidadeExpedidora = '';
    $strItensUnidadesMapeadas = '';
    $strTitulo = '';
    $booDesabilitar = false;
    $booVisualizar = false;

    if ($_GET['acao'] == 'md_cor_mapeamento_uni_exp_sol_consultar') {
        $arrComandos[] = '<button type="button" accesskey="C" name="btnFechar" value="Fechar" onclick="location.href=\'' . SessaoSEI::getInstance()->assinarLink('controlador.php?acao=' . PaginaSEI::getInstance()->getAcaoRetorno() . '&acao_origem=' . $_GET['acao'] . PaginaSEI::getInstance()->montarAncora($_GET['id_unidades'])) . '\';" class="infraButton">Fe<span class="infraTeclaAtalho">c</span>har</button>';
    } else {
        $arrComandos[] = '<button type="submit" accesskey="S" name="sbmCadastrarMdCorMapUnidadeExpSol" value="Salvar" class="infraButton"><span class="infraTeclaAtalho">S</span>alvar</button>';
        $arrComandos[] = '<button type="button" accesskey="C" name="btnCancelar" id="btnCancelar" value="Cancelar" onclick="location.href=\'' . SessaoSEI::getInstance()->assinarLink('controlador.php?acao=' . PaginaSEI::getInstance()->getAcaoRetorno() . '&acao_origem=' . $_GET['acao'] . PaginaSEI::getInstance()->montarAncora($_GET['id_unidades'])) . '\';" class="infraButton"><span class="infraTeclaAtalho">C</span>ancelar</button>';
    }

    switch ($_GET['acao']) {
        case 'md_cor_mapeamento_uni_exp_sol_cadastrar':
            $strTitulo .= 'Cadastrar ';

            $strItensSelUnidadeExpedidora = MdCorUnidadeExpINT::montarSelectUnidade(null, null, null, false, false);

            if (isset($_POST['sbmCadastrarMdCorMapUnidadeExpSol'])) {

                //TIRANDO DA VIEW
                //$objMdPetIntercorrenteProcessoRN = new MdPetIntercorrenteProcessoRN();
                //$resultado = $objMdPetIntercorrenteProcessoRN->cadastrar($_POST);

                $arrUnidadesSolicitantes = PaginaSEI::getInstance()->getArrValuesSelect($_POST['hdnIdUnidades']);

                try {
                    $objMdCorMapeamentoUniExpSolDTO = new MdCorMapeamentoUniExpSolDTO();
                    $objMdCorMapeamentoUniExpSolRN = new MdCorMapeamentoUniExpSolRN();

                    $objMdCorMapeamentoUniExpSolDTO->setNumIdUnidadeExp($_POST['selUnidadeExpedidora']);
                    foreach ($arrUnidadesSolicitantes as $unidade) {
                        $objMdCorMapeamentoUniExpSolDTO->setNumIdUnidadeSolicitante($unidade);
                        $objMdCorMapeamentoUniExpSolDTO->setStrSinAtivo('S');
                        $objMdCorMapeamentoUniExpSol = $objMdCorMapeamentoUniExpSolRN->cadastrar($objMdCorMapeamentoUniExpSolDTO);
                    }
                    header('Location: ' . SessaoSEI::getInstance()->assinarLink('controlador.php?acao=' . PaginaSEI::getInstance()->getAcaoRetorno() . '&acao_origem=' . $_GET['acao'] . PaginaSEI::getInstance()->montarAncora($_POST['selUnidadeExpedidora'])));
                } catch (Exception $e) {
                    PaginaSEI::getInstance()->processarExcecao($e);
                }
            }
            break;
        case 'md_cor_mapeamento_uni_exp_sol_alterar':
        case 'md_cor_mapeamento_uni_exp_sol_consultar':
            $strTitulo .= $_GET['acao'] == 'md_cor_mapeamento_uni_exp_sol_consultar' ? 'Consultar ' : 'Alterar ';
            $intIdUnidadeExp = $_GET['id_unidades'];

            $booDesabilitar = true;

            $strItensSelUnidadeExpedidora = MdCorUnidadeExpINT::montarSelectUnidade(null, null, $intIdUnidadeExp, false);
            $strItensUnidadesMapeadas = MdCorMapeamentoUniExpSolINT::montarSelectUnidadesMapeadas($intIdUnidadeExp);

            if (isset($_POST['sbmCadastrarMdCorMapUnidadeExpSol'])) {
                $objMdCorMapeamentoUniExpSolRN = new MdCorMapeamentoUniExpSolRN();
                $resultado = $objMdCorMapeamentoUniExpSolRN->alterarVerificarServicos($_POST);
                if ($resultado) {
                    header('Location: ' . SessaoSEI::getInstance()->assinarLink('controlador.php?acao=' . PaginaSEI::getInstance()->getAcaoRetorno() . '&acao_origem=' . $_GET['acao'] . PaginaSEI::getInstance()->montarAncora($_POST['selUnidadeExpedidora'])));
                }
            }
            break;
        default:
            throw new InfraException("Ação '" . $_GET['acao'] . "' não reconhecida.");
    }
} catch (Exception $e) {
    PaginaSEI::getInstance()->processarExcecao($e);
}

$strTitulo .= 'Mapeamento de Unidades Expedidoras e Unidades Solicitantes';

PaginaSEI::getInstance()->montarDocType();
PaginaSEI::getInstance()->abrirHtml();
PaginaSEI::getInstance()->abrirHead();
PaginaSEI::getInstance()->montarMeta();
PaginaSEI::getInstance()->montarTitle(PaginaSEI::getInstance()->getStrNomeSistema() . ' - ' . $strTitulo);
PaginaSEI::getInstance()->montarStyle();
PaginaSEI::getInstance()->abrirStyle();
//include_once('md_cor_mapeamento_uni_exp_sol_cadastro_css.php');
PaginaSEI::getInstance()->fecharStyle();
PaginaSEI::getInstance()->montarJavaScript();
PaginaSEI::getInstance()->abrirJavaScript();
PaginaSEI::getInstance()->fecharJavaScript();
PaginaSEI::getInstance()->fecharHead();
PaginaSEI::getInstance()->abrirBody($strTitulo, 'onload="inicializar();"');
PaginaSEI::getInstance()->abrirAreaDados();
?>
<form id="frmMdCorUnidadeExpCadastro" method="post" onsubmit="return OnSubmitForm();" action="<? //= SessaoSEI::getInstance()->assinarLink('controlador.php?acao=' . $_GET['acao'] . '&acao_origem=' . $_GET['acao']) ?>">
    <?php PaginaSEI::getInstance()->montarBarraComandosSuperior($arrComandos); ?>
    <div class="row">
        <div class="col-sm-8 col-md-8 col-lg-6">
            <div class="form-group">
                <label id="lblUnidadeExpedidora" for="selUnidadeExpedidora" class="infraLabelObrigatorio">
                    Unidades Expedidoras:
                </label>
                <select id="selUnidadeExpedidora" name="selUnidadeExpedidora" class="infraSelect form-control"
                        tabindex="<?= PaginaSEI::getInstance()->getProxTabDados() ?>" <?= $booDesabilitar ? 'disabled="disabled"' : ''; ?>>
                    <?= $strItensSelUnidadeExpedidora ?>
                </select>
            </div>
        </div>
    </div>

    <div class="row">
        <div class="col-sm-8 col-md-8 col-lg-6">
            <label id="lblUnidadeSolicitante" for="txtUnidadeSolicitante" class="infraLabelObrigatorio">Unidades Solicitantes:</label>
            <input type="text" id="txtUnidadeSolicitante" name="txtUnidadeSolicitante" class="infraText form-control" tabindex="<?= PaginaSEI::getInstance()->getProxTabDados() ?>"/>
            <input type="hidden" id="hdnIdUnidade" name="hdnIdUnidade" class="infraText" value=""/>
        </div>
    </div>

    <div class="row">
        <div class="col-sm-10 col-md-10 col-lg-9">            
            <div class="input-group">
                <select id="selUnidadeSolicitante" name="selUnidadeSolicitante" size="10" multiple="multiple" class="infraSelect form-control" tabindex="<?= PaginaSEI::getInstance()->getProxTabDados ?>">
                    <?= $strItensUnidadesMapeadas ?>
                </select>
                <div class="botoes ml-1">
                    <img id="imgLupaUnidade" onclick="objLupaUnidade.selecionar(700,500);"
                            src="<?= PaginaSEI::getInstance()->getDiretorioSvgGlobal() ?>/pesquisar.svg"
                            alt="Selecionar Unidade Solicitante" title="Selecionar Unidade Solicitante"
                            class="infraImg"
                            tabindex="<?= PaginaSEI::getInstance()->getProxTabDados() ?>"/> <br/>
                    <img id="imgExcluirUnidade" onclick="objLupaUnidade.remover();"
                            src="<?= PaginaSEI::getInstance()->getDiretorioSvgGlobal() ?>/remover.svg"
                            alt="Remover Unidade" title="Remover Unidade" class="infraImg"
                            tabindex="<?= PaginaSEI::getInstance()->getProxTabDados() ?>"/>
                </div>
                <input type="hidden" id="hdnIdUnidades" name="hdnIdUnidades" class="infraText" value=""/>
            </div>
        </div>
    </div>
</form>
<?php
require_once("md_cor_mapeamento_uni_exp_sol_cadastro_js.php");
PaginaSEI::getInstance()->fecharAreaDados();
PaginaSEI::getInstance()->fecharBody();
PaginaSEI::getInstance()->fecharHtml();
?>

