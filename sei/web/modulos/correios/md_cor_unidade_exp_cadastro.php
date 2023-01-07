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

    $strUrlUnidade = SessaoSEI::getInstance()->assinarLink('controlador.php?acao=md_cor_unidade_selecionar_todas&tipo_selecao=2&id_object=objLupaUnidade&intIdUnidadeExpExcecao=*');
    $strLinkAjaxAutocompletarUnidade = SessaoSEI::getInstance()->assinarLink('controlador_ajax.php?acao_ajax=md_cor_unidade_mapeadas_auto_completar&intIdUnidadeExpExcecao=*');

    $strItensSelUnidade = MdCorUnidadeExpINT::montarSelectUnidade();

    switch ($_GET['acao']) {
        case 'md_cor_unidade_exp_cadastrar':
            $strTitulo = 'Unidades Expedidoras ';
            $arrComandos[] = '<button type="submit" accesskey="S" name="sbmCadastrarMdCorUnidadeExp" value="Salvar" class="infraButton"><span class="infraTeclaAtalho">S</span>alvar</button>';

            //$arrComandos[] = '<button type="button" accesskey="C" name="btnCancelar" id="btnCancelar" value="Cancelar" onclick="location.href=\'' . SessaoSEI::getInstance()->assinarLink('controlador.php?acao=' . PaginaSEI::getInstance()->getAcaoRetorno() . '&acao_origem=' . $_GET['acao']) . '\';" class="infraButton"><span class="infraTeclaAtalho">C</span>ancelar</button>';
            $arrComandos[] = '<button type="button" accesskey="C" name="btnCancelar" id="btnCancelar" value="Cancelar" onclick="location.href=\'' . SessaoSEI::getInstance()->assinarLink('controlador.php?acao=procedimento_controlar&acao_origem=' . $_GET['acao']) . '\';" class="infraButton"><span class="infraTeclaAtalho">C</span>ancelar</button>';

            if (isset($_POST['sbmCadastrarMdCorUnidadeExp'])) {
                $objMDCorUnidadeExpRN = new MdCorUnidadeExpRN();
                $arrUnidades = PaginaSEI::getInstance()->getArrValuesSelect($_POST['hdnIdUnidades']);

                try {
                    $exibirMensagem = false;
                    $objMDCorUnidadeExpRN->validaCamposEnderecoUnidade($arrUnidades);
                    $objMdCorMapeamentoUniExpSolDTO = new MdCorMapeamentoUniExpSolDTO();
                    $objMdCorMapeamentoUniExpSolRN = new MdCorMapeamentoUniExpSolRN();
                    $objMdCorMapeamentoUniExpSolDTO->retNumIdUnidadeExp();
                    $objMdCorMapeamentoUniExpSolDTO->retNumIdUnidadeSolicitante();

                    $objMdCorMapeamentoUniExpSolDTO->setNumIdUnidadeExp($arrUnidades, InfraDTO::$OPER_NOT_IN);
                    $objMdCorMapeamentoUniExpSolDTO->setNumIdUnidadeSolicitante($arrUnidades, InfraDTO::$OPER_NOT_IN);

                    $arrMdCorMapeamentoUniExpSolListar = $objMdCorMapeamentoUniExpSolRN->listar($objMdCorMapeamentoUniExpSolDTO);

                    $objInfraException = new InfraException();
                    if (!empty($arrMdCorMapeamentoUniExpSolListar)) {
                        $objInfraException->lancarValidacao('A Unidade Expedidora não pode ser excluída, pois está mapeada com alguma Unidade Solicitante.');
                    }

                    $objMdCorUnidadeExpDTO = new MdCorUnidadeExpDTO();
                    $objMdCorUnidadeExpDTO->retTodos();
                    $arrObjMdCorUnidadeExpDTO = $objMDCorUnidadeExpRN->listar($objMdCorUnidadeExpDTO);

                    $countArrObjMdCorUnidadeExpDTO = is_array($arrObjMdCorUnidadeExpDTO) ? count($arrObjMdCorUnidadeExpDTO) : 0;
                    if ($countArrObjMdCorUnidadeExpDTO > 0) {
                        foreach ($arrObjMdCorUnidadeExpDTO as $objMdCorUnidadeExpDTO) {
                            $arrObjMdCorUnidadeExpBD[] = $objMdCorUnidadeExpDTO->getNumIdUnidade();
                        }
                    }

                    // Excluir registros
                    $objMdCorUnidadeExpDTO = new MdCorUnidadeExpDTO();
                    $objMdCorUnidadeExpDTO->setNumIdUnidade($arrUnidades, infraDTO::$OPER_NOT_IN);
                    $objMdCorUnidadeExpDTO->retTodos();
                    $listObjMdCorUnidadeExpDTO = $objMDCorUnidadeExpRN->listar($objMdCorUnidadeExpDTO);
                    $countListObjMdCorUnidadeExpDTO = is_array($listObjMdCorUnidadeExpDTO) ? count($listObjMdCorUnidadeExpDTO) : 0;
                    if ($countListObjMdCorUnidadeExpDTO > 0) {
                        $objMDCorUnidadeExpRN->excluir($listObjMdCorUnidadeExpDTO);
                        $exibirMensagem = true;
                    }

                    $counArrtUnidades = is_array($arrUnidades) ? count($arrUnidades) : 0;
                    if ($counArrtUnidades > 0) {
                        foreach ($arrUnidades as $unidade) {
                            $arrObjMdCorUnidadeExpBD[] = $objMdCorUnidadeExpDTO->getNumIdUnidade();
                            if (!in_array($unidade, $arrObjMdCorUnidadeExpBD)) {
                                $objMdCorUnidadeExpDTO->setNumIdUnidade($unidade);
                                $objMdCorUnidadeExpDTO->setStrSinAtivo('S');
                                $objMdCorUnidadeExp = $objMDCorUnidadeExpRN->cadastrar($objMdCorUnidadeExpDTO);
                                $exibirMensagem = true;
                            }
                        }
                    }

                    PaginaSEI::getInstance()->adicionarMensagem('Dados salvos com sucesso.', InfraPagina::$TIPO_MSG_AVISO);
                    if ($exibirMensagem) {
                        $strItensSelUnidade = MdCorUnidadeExpINT::montarSelectUnidade();
                    }

//                    header('Location: ' . SessaoSEI::getInstance()->assinarLink('controlador.php?acao=' . $_GET['acao']));

                } catch (Exception $e) {
                    PaginaSEI::getInstance()->processarExcecao($e);
                }
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
PaginaSEI::getInstance()->montarTitle(PaginaSEI::getInstance()->getStrNomeSistema() . ' - ' . $strTitulo);
PaginaSEI::getInstance()->montarStyle();
PaginaSEI::getInstance()->abrirStyle();
include_once('md_cor_unidade_exp_cadastro_css.php');
PaginaSEI::getInstance()->fecharStyle();
PaginaSEI::getInstance()->montarJavaScript();
PaginaSEI::getInstance()->fecharHead();
PaginaSEI::getInstance()->abrirBody($strTitulo, 'onload="inicializar();"');
?>
<form id="frmMdCorUnidadeExpCadastro" method="post" onsubmit="return OnSubmitForm();"
      action="<?= SessaoSEI::getInstance()->assinarLink('controlador.php?acao=' . $_GET['acao'] . '&acao_origem=' . $_GET['acao']) ?>">
    <?php PaginaSEI::getInstance()->montarBarraComandosSuperior($arrComandos); ?>
    <?php PaginaSEI::getInstance()->abrirAreaDados('auto'); ?>
        <div class="row linha">
            <div class="col-sm-6 col-md-6 col-lg-5">
                <label id="lblUnidade" for="txtUnidade" class="infraLabelObrigatorio">Unidades Expedidoras:</label>
                <img align="top" id="imgAjuda"
                        src="<?= PaginaSEI::getInstance()->getDiretorioSvgGlobal() ?>/ajuda.svg"
                        name="ajuda"
                        onmouseover="return infraTooltipMostrar('Para que o menu de \'Expedição pelos Correios\' seja exibido na Unidade Expedidora é necessário que o usuario possua o perfil \'Expedição Correios\' no SIP', 'Ajuda');"
                        onmouseout="return infraTooltipOcultar();"
                        alt="Ajuda" class="infraImgModulo"/>

                <input type="text" id="txtUnidade" name="txtUnidade" class="infraText form-control"
                        tabindex="<?= PaginaSEI::getInstance()->getProxTabDados() ?>"/>
                <input type="hidden" id="hdnIdUnidade" name="hdnIdUnidade" class="infraText" value=""/>
            </div>
        </div>
        <div class="row" id="divSelDescricao">
            <div class="col-sm-8 col-md-8 col-lg-7">
                <div class="input-group mb-3">
                    <select id="selUnidade" name="selUnidade" size="8" multiple="multiple" class="infraSelect form-control"
                            tabindex="<?= PaginaSEI::getInstance()->getProxTabDados() ?>">
                        <?= $strItensSelUnidade ?>
                    </select>
                    <div class="divIcones">
                        <img id="imgLupaUnidade" onclick="objLupaUnidade.selecionar(700,500);"
                                src="<?= PaginaSEI::getInstance()->getDiretorioSvgGlobal() ?>/pesquisar.svg"
                                alt="Selecionar Unidade" title="Selecionar Unidade" class="infraImg"
                                tabindex="<?= PaginaSEI::getInstance()->getProxTabDados() ?>"/>
                        <br>
                        <img id="imgExcluirUnidade" onclick="objLupaUnidade.remover();"
                                src="<?= PaginaSEI::getInstance()->getDiretorioSvgGlobal() ?>/remover.svg"
                                alt="Remover Unidade" title="Remover Unidade" class="infraImg"
                                tabindex="<?= PaginaSEI::getInstance()->getProxTabDados() ?>"/>
                        <input type="hidden" id="hdnIdUnidades" name="hdnIdUnidades" class="infraText" value=""/>
                    </div>
                </div>
            </div>
        </div>
        <?php PaginaSEI::getInstance()->fecharAreaDados(); ?>
</form>
<?php
PaginaSEI::getInstance()->fecharBody();
PaginaSEI::getInstance()->fecharHtml();
include_once('md_cor_unidade_exp_cadastro_js.php');
?>

