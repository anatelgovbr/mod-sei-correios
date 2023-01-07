<?

try {
    require_once dirname(__FILE__) . '/../../SEI.php';

    session_start();
    SessaoSEI::getInstance()->validarLink();
    SessaoSEI::getInstance()->validarPermissao($_GET['acao']);

    $objRelContJustDTO = new MdCorRelContatoJustificativaDTO();
    $arrComandos = array();

    switch ($_GET['acao']) {
        case 'md_cor_rel_contato_justificativa_cadastrar':
            $strTitulo = 'Inclusão de Destinatários não Habilitados para Expedição';
            $arrComandos[] = '<button type="submit" accesskey="S" name="sbmCadastrarDestinatario" value="Salvar" class="infraButton"><span class="infraTeclaAtalho">S</span>alvar</button>';
            $arrComandos[] = '<button type="button" accesskey="C" name="btnCancelar" id="btnCancelar" value="Cancelar" onclick="location.href=\'' . SessaoSEI::getInstance()->assinarLink('controlador.php?acao=' . PaginaSEI::getInstance()->getAcaoRetorno() . '&acao_origem=' . $_GET['acao']) . '\';" class="infraButton"><span class="infraTeclaAtalho">C</span>ancelar</button>';
            $arrAcoes = [];
            $arr = PaginaSEI::getInstance()->getArrItensTabelaDinamica($_POST['hdnTblDestinatarios']);

            if (!empty($arr)) {

                $strDestinatarios = $_POST['hdnTblDestinatarios'];

                if (isset($_POST['sbmCadastrarDestinatario'])) {
                    try {

                        $objRelContJustRN = new MdCorRelContatoJustificativaRN();

                        foreach ($arr as $linha) {
                            $objNovoRelContJustDTO = new MdCorRelContatoJustificativaDTO();
                            $objNovoRelContJustDTO->setNumIdRelContatoJustificativa(null);
                            $objNovoRelContJustDTO->setNumIdContato($linha[0]);
                            $objNovoRelContJustDTO->setNumIdMdCorJustificativa($linha[1]);

                            $objRelContJustDTO = $objRelContJustRN->cadastrar($objNovoRelContJustDTO);
                        }

                        PaginaSEI::getInstance()->adicionarMensagem('Destinatários não Habilitados para Expedição cadastrado com sucesso.');
                        header('Location: ' . SessaoSEI::getInstance()->assinarLink('controlador.php?acao=' . PaginaSEI::getInstance()->getAcaoRetorno() . '&acao_origem=' . $_GET['acao'] . '&id_md_cor_rel_contato_justificativa=' . $objRelContJustDTO->getNumIdRelContatoJustificativa() . PaginaSEI::getInstance()->montarAncora($objRelContJustDTO->getNumIdRelContatoJustificativa())));
                        die;
                    } catch (Exception $e) {
                        PaginaSEI::getInstance()->processarExcecao($e);
                    }
                }
            }

            $objRelContJustDTO->setNumIdMdCorJustificativa($_POST['selIdJustificativa']);
            break;

        case 'md_cor_rel_contato_justificativa_alterar':

            $formAlterar = true;
            $strTitulo = 'Alterar Justificativa de Destinatário não Habilitado para Expedição';
            $arrComandos[] = '<button type="submit" accesskey="S" name="sbmAlterarDestinatario" value="Salvar" class="infraButton"><span class="infraTeclaAtalho">S</span>alvar</button>';

            $strIdMdCorRelContJust = $_GET['idRelContJustificativa'];

            if (isset($strIdMdCorRelContJust)) {

                #CONSULTAR
                $objRelContJustDTO->setNumIdRelContatoJustificativa($strIdMdCorRelContJust);
                $objRelContJustDTO->retTodos(true);

                $objRelContJustRN = new MdCorRelContatoJustificativaRN();
                $objRelContJustDTO = $objRelContJustRN->consultar($objRelContJustDTO);

                if ($objRelContJustDTO == null) {
                    throw new InfraException("Registro não encontrado.");
                }

                $objContato = new ContatoDTO();
                $objContato->setNumIdContato($objRelContJustDTO->getNumIdContato());
                $objContato->retStrNome();

                $objContatoRN = new ContatoRN();
                $objContato = $objContatoRN->consultarRN0324($objContato);

            } else {

                if (isset($_POST['sbmAlterarDestinatario'])) {
                    try {

                        $objRelContJustDTO->setNumIdRelContatoJustificativa($_POST['hdnIdMdCorRelContJust']);
                        $objRelContJustDTO->setNumIdMdCorJustificativa($_POST['selContatoJustificativa']);

                        $objRelContJustRN = new MdCorRelContatoJustificativaRN();
                        $objRelContJustRN->alterar($objRelContJustDTO);

                        $strIdMdCorRelContJust = $_POST['hdnIdMdCorRelContJust'];

                        PaginaSEI::getInstance()->adicionarMensagem('Destinatário não Habilitado para Expedição alterado com sucesso.');
                        header('Location: ' . SessaoSEI::getInstance()->assinarLink('controlador.php?acao=' . PaginaSEI::getInstance()->getAcaoRetorno() . '&acao_origem=' . $_GET['acao'] . '&id_rel_contato_justificativa=' . $objRelContJustDTO->getNumIdRelContatoJustificativa() . PaginaSEI::getInstance()->montarAncora($objRelContJustDTO->getNumIdRelContatoJustificativa())));
                        die;
                    } catch (Exception $e) {
                        PaginaSEI::getInstance()->processarExcecao($e);
                    }
                }
            }

            $arrComandos[] = '<button type="button" accesskey="C" name="btnCancelar" id="btnCancelar" value="Cancelar" onclick="location.href=\'' . PaginaSEI::getInstance()->formatarXHTML(SessaoSEI::getInstance()->assinarLink('controlador.php?acao=' . PaginaSEI::getInstance()->getAcaoRetorno() . '&acao_origem=' . $_GET['acao'] . PaginaSEI::getInstance()->montarAncora($objRelContJustDTO->getNumIdRelContatoJustificativa()))) . '\';" class="infraButton"><span class="infraTeclaAtalho">C</span>ancelar</button>';
            break;

        default:
            throw new InfraException("Ação '" . $_GET['acao'] . "' não reconhecida.");
    }

    #Justificativa
    $strItensSelJustificativa = MdCorJustificativaINT::montarSelectJustificativa('null', '&nbsp;', $objRelContJustDTO->getNumIdMdCorJustificativa());

    $strLinkAjaxContatos = SessaoSEI::getInstance()->assinarLink('controlador_ajax.php?acao_ajax=contato_auto_completar_contexto_RI1225');
    $strLinkDestinatarios = SessaoSEI::getInstance()->assinarLink('controlador.php?acao=contato_selecionar&tipo_selecao=2&id_object=objLupaDestinatarios');
    $strItensSelDestinatario = PaginaSEI::getInstance()->recuperarCampo('txtDestinatarioNaoHabilitado');

    $strLinkAjaxDestinatarioNaoHabilitado = SessaoSEI::getInstance()->assinarLink('controlador_ajax.php?acao_ajax=contato_destinatario_nao_habilitado');
    $strLinkAjaxDestinatarioDuplicitadade = SessaoSEI::getInstance()->assinarLink('controlador_ajax.php?acao_ajax=contato_destinatario_nao_habilitado_duplicidade');

} catch (Exception $e) {
    PaginaSEI::getInstance()->processarExcecao($e);
}

PaginaSEI::getInstance()->montarDocType();
PaginaSEI::getInstance()->abrirHtml();
PaginaSEI::getInstance()->abrirHead();
PaginaSEI::getInstance()->montarMeta();
PaginaSEI::getInstance()->montarTitle(PaginaSEI::getInstance()->getStrNomeSistema() . ' - ' . $strTitulo);

PaginaSEI::getInstance()->montarStyle();
require_once 'md_cor_rel_contato_justificativa_css.php';

PaginaSEI::getInstance()->montarJavaScript();
require_once 'md_cor_rel_contato_justificativa_js.php';

PaginaSEI::getInstance()->fecharHead();
PaginaSEI::getInstance()->abrirBody($strTitulo, 'onload="inicializar();"');
?>

<form id="frmDestinatario" method="post" onsubmit="return OnSubmitForm();" action="<?= SessaoSEI::getInstance()->assinarLink('controlador.php?acao=' . $_GET['acao'] . '&acao_origem=' . $_GET['acao']) ?>">

    <?
    PaginaSEI::getInstance()->montarBarraComandosSuperior($arrComandos);
    PaginaSEI::getInstance()->abrirAreaDados('45em');
    ?>
    <? if ($formAlterar) { ?>

        <div class="row">
            <div class="col-sm-12 col-md-12 col-lg-12 col-xl-12">
                <table class="infraTable" style="border: solid 1px; color: #DCDCDC;" cellspacing="0" cellpadding="5" width="100%">
                    <tbody>
                        <tr class="infraTrEscura" style="height: 30px;">
                            <th class="infraTh" align="center"><b>Destinatário</b></th>
                            <th class="infraTh" align="center"><b>Justificativa</b></th>
                        </tr>
                        <tr class="infraTrClara" style="width: 30px;">
                            <td align="center" style="width: 50%; color: #212529"><?= $objContato->getStrNome(); ?></td>
                            <td align="center">
                                <select id="selContatoJustificativa" name="selContatoJustificativa" class="infraSelect" style="width: 100%">
                                    <?= $strItensSelJustificativa ?>
                                </select>
                            </td>
                        </tr>
                    </tbody>
                </table>
                <input type="hidden" id="hdnIdMdCorRelContJust" name="hdnIdMdCorRelContJust" value="<?= $strIdMdCorRelContJust; ?>"/>
            </div>
        </div>

    <? } else { ?>

        <? require_once 'md_cor_destinatario_tabela_js.php'; ?>
        <div class="row">
            <div class="col-sm-12 col-md-12 col-lg-12 col-xl-12">
                <fieldset class="infraFieldset form-control" style="height: auto">
                    <legend class="infraLegend">Administrar Destinatários não Habilitados para Expedição</legend>
                    <div class="row">
                        <div class="col-10 col-sm-10 col-md-8 col-lg-6 col-xl-6">
                            <label id="lblDestinatarios" for="txtDestinatarioNaoHabilitado" accesskey="e" class="infraLabelObrigatorio">
                                D<span class="infraTeclaAtalho">e</span>stinatários:
                            </label>
                            <input type="text" id="txtDestinatarioNaoHabilitado" name="txtDestinatarioNaoHabilitado" class="infraText form-control" tabindex="<?= PaginaSEI::getInstance()->getProxTabDados() ?>" autofocus/>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-11 col-sm-11 col-md-9 col-lg-7 col-xl-7">
                            <div class="form-group">
                                <div class="input-group mb-3">
                                    <select id="selDestinatarioNaoHabilitado" name="selDestinatarioNaoHabilitado" class="infraSelect form-control" multiple="multiple" tabindex="<?= PaginaSEI::getInstance()->getProxTabDados() ?>">
                                        <?= $strItensSelDestinatario ?>
                                    </select>
                                    <div id="divOpcoesDestinatarios">
                                        <img id="imgSelecionarGrupo" class="infraImg" src="/infra_css/svg/pesquisar.svg" onclick="objLupaDestinatarios.selecionar(700,500);" title="Selecionar Contatos para Destinatários" alt="Selecionar Contatos para Destinatários" tabindex="<?= PaginaSEI::getInstance()->getProxTabDados() ?>"/><br/>
                                        <img id="imgRemoverDestinatarios" class="infraImg" src="/infra_css/svg/remover.svg" onclick="objLupaDestinatarios.remover();" alt="Remover Destinatários Selecionados" title="Remover Destinatários Selecionados" tabindex="<?= PaginaSEI::getInstance()->getProxTabDados() ?>"/>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-10 col-sm-10 col-md-8 col-lg-6 col-xl-6">
                            <div class="form-group">
                                <label id="lblDestinatarioJustificativa" for="selDestinatarioJustificativa" accesskey="" class="infraLabelObrigatorio">Justifivativa:</label>
                                <div class="input-group mb-3">
                                    <select id="selContatoJustificativa" name="selContatoJustificativa" class="infraSelect form-control">
                                        <?= $strItensSelJustificativa ?>
                                    </select>
                                    <input type="hidden" id="hdnDestinatarios" name="hdnDestinatarios" value="<?= PaginaSEI::tratarHTML($_POST['hdnDestinatarios']) ?>"/>
                                    <input type="hidden" id="hdnIdDestinatarioNaoHabilitado" name="hdnIdDestinatarioNaoHabilitado" class="infraText" value=""/>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-sm-12 col-md-12 col-lg-12 col-xl-12 text-left">
                            <input type="button" name="sbmGravarEmail" value="Adicionar" class="infraButton" id="sbmGravarEmail" onclick="transportarDestinatario();" tabindex="<?= PaginaSEI::getInstance()->getProxTabDados() ?>"/>
                        </div>
                    </div>
                    <div id="divFieldset">
                        <div class="clear"></div>
                        <div id="tabelaDestinatario">
                            <div id="btnAdicionar"></div>
                            <div id="divTabelaDestinatario" class="infraAreaTabela">
                                <table id="tblDestinatarioNaoHabilitado" width="100%" class="infraTable mt-4">
                                    <tr>
                                        <th style="display:none;">IdContato</th>
                                        <th style="display:none;">IdJustificativa</th>
                                        <th class="infraTh text-left" width="25%">Destinatário</th>
                                        <th class="infraTh text-left" width="15%" align="left">Natureza</th>
                                        <th class="infraTh text-center" width="15%" align="left">CPF/CNPJ</th>
                                        <th class="infraTh text-left" width="45%" align="left">Justificativa</th>
                                        <th class="infraTh text-center" style="width: 90px">Ações</th>
                                    </tr>
                                </table>
                                <input type="hidden" id="hdnTblDestinatarios" name="hdnTblDestinatarios" value="<?= $strDestinatarios; ?>"/>
                            </div>
                        </div>
                    </div>
                </fieldset>
            </div>
        </div>
    <? } ?>

    <? PaginaSEI::getInstance()->fecharAreaDados(); ?>
</form>

<?
PaginaSEI::getInstance()->fecharBody();
PaginaSEI::getInstance()->fecharHtml();
?>
