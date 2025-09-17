<?
/**
 * TRIBUNAL REGIONAL FEDERAL DA 4ª REGIÃO
 *
 * 22/12/2016 - criado por CAST
 *
 * Versão do Gerador de Código: 1.39.0
 *
 * Versão no SVN: $Id$
 */
try {
    require_once dirname(__FILE__) . '/../../SEI.php';

    session_start();

    //////////////////////////////////////////////////////////////////////////////
    InfraDebug::getInstance()->setBolLigado(false);
    InfraDebug::getInstance()->setBolDebugInfra(false);
    InfraDebug::getInstance()->limpar();
    //////////////////////////////////////////////////////////////////////////////

    SessaoSEI::getInstance()->validarLink();

    PaginaSEI::getInstance()->verificarSelecao('md_cor_map_unid_servico_selecionar');

    SessaoSEI::getInstance()->validarPermissao($_GET['acao']);

    $objMdCorMapUnidServicoDTO = new MdCorMapUnidServicoDTO();

    $strDesabilitar = '';

    $arrComandos = array();

    // INICIALIZAÇÃO
    require_once('md_cor_map_unid_servico_cadastro_inicializacao.php');

    $bolAcaoAlterar = false;

    $strTitulo = 'Mapeamento de Unidades Solicitantes e Serviços Postais';

    switch ($_GET['acao']) {
        case 'md_cor_map_unid_servico_cadastrar':
            $arrComandos[] = '<button type="submit" accesskey="S" name="sbmCadastrarMdCorMapUnidServico" value="Salvar" class="infraButton"><span class="infraTeclaAtalho">S</span>alvar</button>';
            $arrComandos[] = '<button type="button" accesskey="C" name="btnCancelar" id="btnCancelar" value="Cancelar" onclick="location.href=\'' . SessaoSEI::getInstance()->assinarLink('controlador.php?acao=' . PaginaSEI::getInstance()->getAcaoRetorno() . '&acao_origem=' . $_GET['acao']) . '\';" class="infraButton"><span class="infraTeclaAtalho">C</span>ancelar</button>';

            $objMdCorMapUnidServicoDTO->setNumIdMdCorMapUnidServico(null);
            $objMdCorMapUnidServicoDTO->setNumIdUnidadeSolicitante(null);
            $objMdCorMapUnidServicoDTO->setStrSinAtivo('S');
            if (isset($_POST['sbmCadastrarMdCorMapUnidServico'])) {
                try {
                    // MULTIPLOs 
                    //$arrObjMdCorMapUnidServicoDTO = array();

                    $unidades = PaginaSEI::getInstance()->getArrItensTabelaDinamica($_POST['hdnUnidades']);
                    $servicos = PaginaSEI::getInstance()->getArrItensTabelaDinamica($_POST['hdnListaContratoServicosIndicados']);

                    // MÚLTIPLAS UNIDADES
                    foreach ($unidades as $unidade) {
//                        echo "<pre>";
//                        var_dump($unidade);
//                        die;
                        $objMdCorMapUnidServicoDTO2 = new MdCorMapUnidServicoDTO();
                        $objMdCorMapUnidServicoDTO2->retTodos();
                        $dtoUnidade = clone($objMdCorMapUnidServicoDTO);
                        $dtoUnidade->setNumIdUnidadeSolicitante($unidade[0]);
                        $objMdCorMapUnidServicoDTO2->setNumIdUnidadeSolicitante($unidade[0]);
                        // MÚLTIPLAS SERVIÇOS
                        foreach ($servicos as $servico) {
                            $dtoServico = clone($dtoUnidade);
                            $dtoServico->setNumIdMdCorServicoPostal($servico[0]);
                            $objMdCorMapUnidServicoDTO2->setNumIdMdCorServicoPostal($servico[0]);
                            //$arrObjMdCorMapUnidServicoDTO[] = $dtoServico;
                            $objMdCorMapUnidServicoRN = new MdCorMapUnidServicoRN();
                            $objMdCorMapUnidServicoDTO3 = $objMdCorMapUnidServicoRN->listar($objMdCorMapUnidServicoDTO2);
                            if ($objMdCorMapUnidServicoDTO3 == null) {
                                $ret = $objMdCorMapUnidServicoRN->cadastrar($dtoServico);
                            }
                        }
                    }
                    //$objMdCorMapUnidServicoRN = new MdCorMapUnidServicoRN();
                    //$ret = $objMdCorMapUnidServicoRN->cadastrar($arrObjMdCorMapUnidServicoDTO);          
                    // MULTIPLOs - FIM 
                    //ÚNICOS
                    //$objMdCorMapUnidServicoDTO->setNumIdUnidadeSolicitante($_POST['selUnidades']);
                    //$objMdCorMapUnidServicoRN = new MdCorMapUnidServicoRN();
                    //$objMdCorMapUnidServicoDTO = $objMdCorMapUnidServicoRN->cadastrar($objMdCorMapUnidServicoDTO);

                    PaginaSEI::getInstance()->adicionarMensagem('Cadastro efetuado com sucesso.');
                    header('Location: ' . SessaoSEI::getInstance()->assinarLink('controlador.php?acao=' . PaginaSEI::getInstance()->getAcaoRetorno() . '&acao_origem=' . $_GET['acao'] . '&id_unidade_solicitante=' . $objMdCorMapUnidServicoDTO->getNumIdUnidadeSolicitante() . PaginaSEI::getInstance()->montarAncora($dtoServico->getNumIdUnidadeSolicitante())));
                    die;
                } catch (Exception $e) {
                    PaginaSEI::getInstance()->processarExcecao($e);
                }
            }
            break;

        case 'md_cor_map_unid_servico_consultar':
        case 'md_cor_map_unid_servico_alterar':
            // Origem: TELA PESQUISA
            if (/* isset($_GET['id_md_cor_map_unid_servico']) && */
            isset($_GET['id_unidade_solicitante'])) {
                //$objMdCorMapUnidServicoDTO->setNumIdMdCorMapUnidServico($_GET['id_md_cor_map_unid_servico']);
                $objMdCorMapUnidServicoDTO->setNumIdUnidadeSolicitante($_GET['id_unidade_solicitante']);
                //$objMdCorMapUnidServicoDTO->setStrSinAtivo('S');
                $objMdCorMapUnidServicoDTO->setDistinct(true);

                //$objMdCorMapUnidServicoDTO->retTodos();
                $objMdCorMapUnidServicoDTO->retNumIdUnidadeSolicitante();
                $objMdCorMapUnidServicoDTO->retStrSiglaUnidade();
                $objMdCorMapUnidServicoDTO->retStrDescricaoUnidade();
                $objMdCorMapUnidServicoRN = new MdCorMapUnidServicoRN();
                $objMdCorMapUnidServicoDTO = $objMdCorMapUnidServicoRN->consultar($objMdCorMapUnidServicoDTO);

                if ($objMdCorMapUnidServicoDTO == null) {
                    throw new InfraException("Registro não encontrado.");
                }


                $objMdCorMapUnidServicoDTO->setStrSiglaDescricaoUnidade($objMdCorMapUnidServicoDTO->getStrSiglaUnidade() . ' - ' . $objMdCorMapUnidServicoDTO->getStrDescricaoUnidade());

                // Consulta valores guardados
                $strItensSelUnidades = InfraINT::montarSelectArrInfraDTO($strPrimeiroItemValor, $strPrimeiroItemDescricao, $strValorItemSelecionado, array($objMdCorMapUnidServicoDTO), 'IdUnidadeSolicitante', 'SiglaDescricaoUnidade');

                $objMdCorMapUnidServicosDTO = new MdCorMapUnidServicoDTO();
                $objMdCorMapUnidServicoDTO->setNumIdMdCorMapUnidServico(null);
                $objMdCorMapUnidServicosDTO->setNumIdUnidadeSolicitante($objMdCorMapUnidServicoDTO->getNumIdUnidadeSolicitante());
                //$objMdCorMapUnidServicosDTO->setStrSinAtivo('S');
                $objMdCorMapUnidServicosDTO->retTodos();
                $objMdCorMapUnidServicosDTO->retStrNomeServico();
                $objMdCorMapUnidServicosDTO->retStrDescricaoServico();
                $objMdCorMapUnidServicosDTO->retStrNumeroContrato();
                $objMdCorMapUnidServicosDTO->retStrNumeroContratoCorreio();

                $objMdCorMapUnidServicosRN = new MdCorMapUnidServicoRN();
                $arrObjMdCorMapUnidServicosDTO = $objMdCorMapUnidServicosRN->listar($objMdCorMapUnidServicosDTO);

                $numRegistros = count($arrObjMdCorMapUnidServicosDTO);
                if ($numRegistros > 0) {
                    for ($i = 0; $i < $numRegistros; $i++) {
                        $arrItensTabelaContratoServicos[] = array($arrObjMdCorMapUnidServicosDTO[$i]->getNumIdMdCorServicoPostal(),
                            $arrObjMdCorMapUnidServicosDTO[$i]->getStrNumeroContrato(),
                            $arrObjMdCorMapUnidServicosDTO[$i]->getStrNumeroContratoCorreio(),
                            $arrObjMdCorMapUnidServicosDTO[$i]->getStrDescricaoServico(),
                            $arrObjMdCorMapUnidServicosDTO[$i]->getNumIdMdCorMapUnidServico().'_'.$arrObjMdCorMapUnidServicosDTO[$i]->getStrSinAtivo(),
                        );
                    }
                }

                $hdnListaContratoServicosIndicados = PaginaSEI::getInstance()->gerarItensTabelaDinamica($arrItensTabelaContratoServicos);
            } else {
                //$objMdCorMapUnidServicoDTO->setNumIdMdCorMapUnidServico($_POST['hdnIdMdCorMapUnidServico']);
                $objMdCorMapUnidServicoDTO->setNumIdUnidadeSolicitante($_POST['hdnIdUnidadeSolicitante']);
                //$objMdCorMapUnidServicoDTO->setStrSinAtivo('S');
            }

            if ($_GET['acao'] == 'md_cor_map_unid_servico_consultar') {
                $arrComandos[] = '<button type="button" accesskey="C" name="btnFechar" value="Fechar" onclick="location.href=\'' . SessaoSEI::getInstance()->assinarLink('controlador.php?acao=' . PaginaSEI::getInstance()->getAcaoRetorno() . '&acao_origem=' . $_GET['acao'] . PaginaSEI::getInstance()->montarAncora($_GET['id_unidade_solicitante'])) . '\';" class="infraButton">Fe<span class="infraTeclaAtalho">c</span>har</button>';
                $strDesabilitarConsultar = 'disabled="disabled"';
            } else {
                $arrComandos[] = '<button type="submit" accesskey="S" name="sbmAlterarMdCorMapUnidServico" value="Salvar" class="infraButton"><span class="infraTeclaAtalho">S</span>alvar</button>';
                $arrComandos[] = '<button type="button" accesskey="C" name="btnCancelar" id="btnCancelar" value="Cancelar" onclick="location.href=\'' . SessaoSEI::getInstance()->assinarLink('controlador.php?acao=' . PaginaSEI::getInstance()->getAcaoRetorno() . '&acao_origem=' . $_GET['acao'] . PaginaSEI::getInstance()->montarAncora($objMdCorMapUnidServicoDTO->getNumIdUnidadeSolicitante())) . '\';" class="infraButton"><span class="infraTeclaAtalho">C</span>ancelar</button>';
                $strDesabilitar = 'disabled="disabled"';
                $bolAcaoAlterar = true;
                $linkAjaxValidaExclContratoServ = SessaoSEI::getInstance()->assinarLink('controlador_ajax.php?acao_ajax=md_cor_valid_exc_servico_mapeado');
                $linkAjaxDesReaContratoServico = SessaoSEI::getInstance()->assinarLink('controlador_ajax.php?acao_ajax=md_cor_reativa_desativa_contrato_servico');
            }


            if (isset($_POST['sbmAlterarMdCorMapUnidServico'])) {
                try {

                    $unidades = PaginaSEI::getInstance()->getArrItensTabelaDinamica($_POST['hdnUnidades']);
                    $servicos = PaginaSEI::getInstance()->getArrItensTabelaDinamica($_POST['hdnListaContratoServicosIndicados']);

                    // MÚLTIPLAS UNIDADES
                    foreach ($unidades as $unidade) {
                        // EXCLUINDO
                        $dtoUnidade = clone($objMdCorMapUnidServicoDTO);
                        $dtoUnidade->setNumIdUnidadeSolicitante($unidade[0]);
                        $dtoUnidade->retTodos();

                        $objMdCorMapUnidServicosRN = new MdCorMapUnidServicoRN();
                        $arrdtoUnidade = $objMdCorMapUnidServicosRN->listar($dtoUnidade);

                        $objMdCorMapUnidServicosRN = new MdCorMapUnidServicoRN();
                        $objMdCorMapUnidServicosRN->excluir($arrdtoUnidade);

                        // INCLUINDO
                        foreach ($servicos as $servico) {
                            $dtoServico = clone($dtoUnidade);
                            $dtoServico->setNumIdMdCorServicoPostal($servico[0]);
                            $arrDados = explode( '_' , $servico[4] );
                            $dtoServico->setStrSinAtivo( $arrDados[1] );

                            $objMdCorMapUnidServicoRN = new MdCorMapUnidServicoRN();
                            $ret = $objMdCorMapUnidServicoRN->cadastrar($dtoServico);

                            //$arrObjMdCorMapUnidServicoDTO[] = $dtoServico;
                        }
                    }

                    //$objMdCorMapUnidServicoRN = new MdCorMapUnidServicoRN();
                    //$objMdCorMapUnidServicoRN->alterar($objMdCorMapUnidServicoDTO);
                    PaginaSEI::getInstance()->adicionarMensagem('Cadastro alterado com sucesso.');
                    header('Location: ' . SessaoSEI::getInstance()->assinarLink('controlador.php?acao=' . PaginaSEI::getInstance()->getAcaoRetorno() . '&acao_origem=' . $_GET['acao'] . PaginaSEI::getInstance()->montarAncora(/* $objMdCorMapUnidServicoDTO->getNumIdMdCorMapUnidServico().'-'. */ $objMdCorMapUnidServicoDTO->getNumIdUnidadeSolicitante())));
                    die;
                } catch (Exception $e) {
                    PaginaSEI::getInstance()->processarExcecao($e);
                }
            }
            break;

        case 'md_cor_map_unid_servico_desativar':
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
include_once("md_cor_map_unid_servico_cadastro_css.php");
PaginaSEI::getInstance()->fecharStyle();
PaginaSEI::getInstance()->montarJavaScript();
PaginaSEI::getInstance()->abrirJavaScript();
PaginaSEI::getInstance()->fecharJavaScript();
PaginaSEI::getInstance()->fecharHead();
PaginaSEI::getInstance()->abrirBody($strTitulo, 'onload="inicializar();"');
?>
    <form id="frmMdCorMapUnidServicoCadastro" method="post" onsubmit="return OnSubmitForm();"
          action="<?= SessaoSEI::getInstance()->assinarLink('controlador.php?acao=' . $_GET['acao'] . '&acao_origem=' . $_GET['acao']) ?>">
        <? PaginaSEI::getInstance()->montarBarraComandosSuperior($arrComandos); ?>
        <div class="row">
            <div class="col-sm-8 col-md-8 col-lg-6">
                <div class="form-group mb-0">
                    <label id="lblDescricao" for="txtDescricao" accesskey="q" class="infraLabelObrigatorio">
                        Unidade Solicitante:
                    </label>                    
                    <input type="text" id="txtUnidade" name="txtUnidade" class="infraText form-control" tabindex="<?= PaginaSEI::getInstance()->getProxTabDados() ?>" <?= $strDesabilitar . $strDesabilitarConsultar ?>/>                    
                </div>
            </div>
        </div>
        <div class="row">
            <div class="col-sm-10 col-md-10 col-lg-8">
                <div class="form-group">
                    <div class="input-group mb-3">
                        <select id="selUnidades" name="selUnidades" size="4" multiple="multiple"
                                class="infraSelect form-select"<?= $strDesabilitar . $strDesabilitarConsultar ?>>
                            <?= $strItensSelUnidades ?>
                        </select>
                        <? if ($strDesabilitar == '' && $strDesabilitarConsultar == '') { ?>
                            <div class="botoes">
                                <img id="imgLupaUnidades" onclick="objLupaUnidades.selecionar(700, 500);"
                                        src="<?= PaginaSEI::getInstance()->getDiretorioSvgGlobal(); ?>/pesquisar.svg"
                                        alt="Selecionar <?= $strTitulo ?>"
                                        title="Selecionar <?= $strTitulo ?>" class="infraImg"/>
                                <br/><img id="imgExcluirUnidades" onclick="objLupaUnidades.remover();"
                                            src="<?= PaginaSEI::getInstance()->getDiretorioSvgGlobal(); ?>/remover.svg"
                                            alt="Remover <?= $strTitulo ?>"
                                            title="Remover <?= $strTitulo ?>" class="infraImg"/>
                            </div>
                        <? } ?>
                    </div>
                    <input type="hidden" id="hdnUnidades" name="hdnUnidades" value="<?= $_POST['hdnUnidades'] ?>"/>
                    <input type="hidden" id="hdnIdUnidade" name="hdnIdUnidade" value="<?= $_POST['hdnIdUnidade'] ?>"/>
                    <input type="hidden" id="hdnIdUnidadeSolicitante" name="hdnIdUnidadeSolicitante" value="<?= $objMdCorMapUnidServicoDTO->getNumIdUnidadeSolicitante(); ?>"/>
                </div>
            </div>
        </div>

        <div class="row">
            <div class="col-sm-12 col-md-12 col-lg-12 col-xl-12">
                <fieldset id="fieldsetDocInstaurador" class="infraFieldset form-control mb-3 py-3">
                    <legend class="infraLegend">&nbsp;Serviços por Contrato&nbsp;</legend>

                    <div class="row">
                        <div class="col-sm-8 col-md-8 col-lg-6">
                            <div class="form-group">
                                <label class="infraLabelObrigatorio">&nbsp;Número do Contrato no Órgão:</label>
                                <select name="selContrato" class="infraSelect form-select" id="selContrato" <?= $strDesabilitarConsultar ?> tabindex="<?= PaginaSEI::getInstance()->getProxTabDados() ?>">
                                    <?= $strItensSelContrato ?>
                                </select>
                            </div>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-sm-10 col-md-10 col-lg-8">
                            <div class="form-group">
                                <label id="lblIDNCondutas" class="infraLabelObrigatorio classDispositivoNormativo" for="selServicos">
                                    Serviços Postais:
                                </label>
                                <div class="input-group mb-3">
                                    <select id="selServicos" name="selServicos" multiple size="9" class="infraSelect" tabindex="<?= PaginaSEI::getInstance()->getProxTabDados() ?>"<?= $strDesabilitarConsultar ?>>
                                        <? //=$strItensSelServicos ?>
                                    </select>
                                </div>
                            </div>
                        </div>
                    </div>
                    <? if ($strDesabilitarConsultar == '') { ?>
                        <div class="row">
                            <div class="col-sm-12 col-md-12 col-lg-12 col-xl-12" id="btnAdicionarNumeroSei">
                                <button type="button" name="sbmAdicionarNumeroSeiContratoServicos"
                                        onclick="adicionarContratoServicos();" id="sbmAdicionarNumeroSei"
                                        value="Adicionar"
                                        class="infraButton"
                                        tabindex="<?= PaginaSEI::getInstance()->getProxTabDados() ?>">
                                    Adicionar
                                </button>
                            </div>
                        </div>
                    <? } ?>
                    <div class="row">
                        <div class="col-sm-12 col-md-12 col-lg-12 col-xl-12">
                            <input type="hidden" name="hdnIdContratoServicosCadastrado"
                                    id="hdnIdContratoServicosCadastrado"
                                    value=""/>
                            <input type="hidden" name="hdnListaContratoServicosIndicados"
                                    id="hdnListaContratoServicosIndicados"
                                    value="<?= $hdnListaContratoServicosIndicados ?>"/>
                            <table id="tbContratoServicos" class="infraTable mt-5 table" align="left"
                                    summary="Lista de Processos a serem Sobrestados">
                                <tr>
                                    <th class="infraTh" style="display: none;">ID Servico</th>
                                    <th class="infraTh" id="tdNumeroProcessoContratoServicos" style="min-width:25%"
                                        align="center">
                                        Número do Contrato no Órgão
                                    </th>
                                    <th class="infraTh" id="tdNumeroProcessoTipoContratoServicos" style="min-width:25%"
                                        align="center">
                                        Número do Contrato nos Correios
                                    </th>
                                    <th class="infraTh" id="tdDtSobrestamentoContratoServicos" style="min-width:21%"
                                        align="center">
                                        Serviços Postais
                                    </th>
                                    <th class="infraTh" style="display: none;">IdUnidMapServ x Status</th>

                                    <?php if ( $_REQUEST['acao'] != "md_cor_map_unid_servico_consultar" ): ?>
                                        <th class="infraTh colAcoes" style="min-width:10%" align="center"> Ações</th>
                                    <?php endif; ?>
                                </tr>
                            </table>
                        </div>
                    </div>
                </fieldset>
            </div>
        </div>
        <?php PaginaSEI::getInstance()->montarBarraComandosInferior($arrComandos); ?>
    </form>
<?
PaginaSEI::getInstance()->fecharBody();
PaginaSEI::getInstance()->fecharHtml();
require_once('md_cor_map_unid_servico_cadastro_js.php');
?>