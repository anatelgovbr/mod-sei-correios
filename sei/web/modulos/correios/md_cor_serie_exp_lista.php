<?
/**
 * TRIBUNAL REGIONAL FEDERAL DA 4ª REGIÃO
 *
 * 01/07/2008 - criado por fbv
 *
 * Versão do Gerador de Código: 1.19.0
 *
 * Versão no CVS: $Id$
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

    PaginaSEI::getInstance()->prepararSelecao('md_cor_serie_exp_selecionar');

    SessaoSEI::getInstance()->validarPermissao($_GET['acao']);

    if (isset($_GET['id_grupo_serie'])) {
        PaginaSEI::getInstance()->salvarCampo('selGrupoSerie', $_GET['id_grupo_serie']);
        //$_POST['hdnInfraTotalRegistros'] = 0;
    } else {
        PaginaSEI::getInstance()->salvarCamposPost(array('selGrupoSerie'));
    }

    PaginaSEI::getInstance()->salvarCamposPost(array('selModeloPesquisa', 'txtNomeSeriePesquisa'));

    switch ($_GET['acao']) {
        case 'md_cor_serie_exp_excluir':
            try {
                $arrStrIds = PaginaSEI::getInstance()->getArrStrItensSelecionados();
                $arrObjSerieDTO = array();
                for ($i = 0; $i < count($arrStrIds); $i++) {
                    $objSerieDTO = new SerieDTO();
                    $objSerieDTO->setNumIdSerie($arrStrIds[$i]);
                    $arrObjSerieDTO[] = $objSerieDTO;
                }
                $objSerieRN = new SerieRN();
                $objSerieRN->excluirRN0645($arrObjSerieDTO);
                PaginaSEI::getInstance()->setStrMensagem('Operação realizada com sucesso.');
            } catch (Exception $e) {
                PaginaSEI::getInstance()->processarExcecao($e);
            }
            header('Location: ' . SessaoSEI::getInstance()->assinarLink('controlador.php?acao=' . $_GET['acao_origem'] . '&acao_origem=' . $_GET['acao']));
            die;


        case 'md_cor_serie_exp_desativar':
            try {
                $arrStrIds = PaginaSEI::getInstance()->getArrStrItensSelecionados();
                $arrObjSerieDTO = array();
                for ($i = 0; $i < count($arrStrIds); $i++) {
                    $objSerieDTO = new SerieDTO();
                    $objSerieDTO->setNumIdSerie($arrStrIds[$i]);
                    $arrObjSerieDTO[] = $objSerieDTO;
                }
                $objSerieRN = new SerieRN();
                $objSerieRN->desativarRN0648($arrObjSerieDTO);
                PaginaSEI::getInstance()->setStrMensagem('Operação realizada com sucesso.');
            } catch (Exception $e) {
                PaginaSEI::getInstance()->processarExcecao($e);
            }
            header('Location: ' . SessaoSEI::getInstance()->assinarLink('controlador.php?acao=' . $_GET['acao_origem'] . '&acao_origem=' . $_GET['acao']));
            die;

        case 'md_cor_serie_exp_reativar':
            $strTitulo = 'Reativar Tipos de Documento';
            if ($_GET['acao_confirmada'] == 'sim') {
                try {
                    $arrStrIds = PaginaSEI::getInstance()->getArrStrItensSelecionados();
                    $arrObjSerieDTO = array();
                    for ($i = 0; $i < count($arrStrIds); $i++) {
                        $objSerieDTO = new SerieDTO();
                        $objSerieDTO->setNumIdSerie($arrStrIds[$i]);
                        $arrObjSerieDTO[] = $objSerieDTO;
                    }
                    $objSerieRN = new SerieRN();
                    $objSerieRN->reativarRN0649($arrObjSerieDTO);
                    PaginaSEI::getInstance()->setStrMensagem('Operação realizada com sucesso.');
                } catch (Exception $e) {
                    PaginaSEI::getInstance()->processarExcecao($e);
                }
                header('Location: ' . SessaoSEI::getInstance()->assinarLink('controlador.php?acao=' . $_GET['acao_origem'] . '&acao_origem=' . $_GET['acao']));
                die;
            }
            break;


        case 'md_cor_serie_exp_selecionar':
            $strTitulo = PaginaSEI::getInstance()->getTituloSelecao('Selecionar Tipo de Documento', 'Selecionar Tipos de Documento');

            //Se cadastrou alguem
            if ($_GET['acao_origem'] == 'md_cor_serie_exp_cadastrar') {
                if (isset($_GET['id_serie'])) {
                    PaginaSEI::getInstance()->adicionarSelecionado($_GET['id_serie']);
                }
            }
            break;

        case 'md_cor_serie_exp_listar':
            $strTitulo = 'Tipos de Documento';
            break;

        default:
            throw new InfraException("Ação '" . $_GET['acao'] . "' não reconhecida.");
    }

    $arrComandos = array();

    $arrComandos[] = '<button type="submit" accesskey="P" id="sbmPesquisar" value="Pesquisar" class="infraButton"><span class="infraTeclaAtalho">P</span>esquisar</button>';

    if ($_GET['acao'] == 'md_cor_serie_exp_selecionar') {
        $arrComandos[] = '<button type="button" accesskey="T" id="btnTransportarSelecao" value="Transportar" onclick="infraTransportarSelecao();" class="infraButton"><span class="infraTeclaAtalho">T</span>ransportar</button>';
    }

    $objSerieDTO = new SerieDTO(true);
    $objSerieDTO->retNumIdSerie();
    $objSerieDTO->retStrNome();
    //$objSerieDTO->retStrDescricao();
    $objSerieDTO->retStrNomeGrupoSerie();

    if (isset($_GET['cobranca'])) {
        $strAplicabilidadeTipoDocumento = SerieRN::$TA_EXTERNO;
    } else {
        $objSerieDTO->setStrSinDestinatario('S');
        $strAplicabilidadeTipoDocumento = SerieRN::$TA_INTERNO;
    }

    $objSerieDTO->setStrStaAplicabilidade(array($strAplicabilidadeTipoDocumento, SerieRN::$TA_INTERNO_EXTERNO), InfraDTO::$OPER_IN);

    $numIdGrupoSerie = PaginaSEI::getInstance()->recuperarCampo('selGrupoSerie');
    if ($numIdGrupoSerie !== '') {
        $objSerieDTO->setNumIdGrupoSerie($numIdGrupoSerie);
    }

    $strNomeSeriePesquisa = PaginaSEI::getInstance()->recuperarCampo('txtNomeSeriePesquisa');
    if (trim($strNomeSeriePesquisa) != '') {
        $objSerieDTO->setStrNome('%' . trim($strNomeSeriePesquisa . '%'), InfraDTO::$OPER_LIKE);
    }

    $numIdModelo = PaginaSEI::getInstance()->recuperarCampo('selModeloPesquisa', 'null');
    if ($numIdModelo !== 'null') {
        $objSerieDTO->setNumIdModelo($numIdModelo);
    }

    $objSerieDTO->setStrSinAtivo('S');
    if ($_GET['acao'] == 'md_cor_serie_exp_reativar') {
        //Lista somente inativos
        $objSerieDTO->setBolExclusaoLogica(false);
        $objSerieDTO->setStrSinAtivo('N');
    }

    PaginaSEI::getInstance()->prepararOrdenacao($objSerieDTO, 'Nome', InfraDTO::$TIPO_ORDENACAO_ASC);
    PaginaSEI::getInstance()->prepararPaginacao($objSerieDTO);

    $objSerieRN = new SerieRN();
    $arrObjSerieDTO = $objSerieRN->listarRN0646($objSerieDTO);

    PaginaSEI::getInstance()->processarPaginacao($objSerieDTO);
    $numRegistros = count($arrObjSerieDTO);

    if ($numRegistros > 0) {

        $bolCheck = false;

        if ($_GET['acao'] == 'md_cor_serie_exp_selecionar') {
            $bolAcaoReativar = false;
            $bolAcaoConsultar = SessaoSEI::getInstance()->verificarPermissao('md_cor_serie_exp_consultar');
            $bolAcaoAlterar = SessaoSEI::getInstance()->verificarPermissao('md_cor_serie_exp_alterar');
            $bolAcaoImprimir = false;
            $bolAcaoExcluir = false;
            $bolAcaoDesativar = false;
            $bolCheck = true;
        } else if ($_GET['acao'] == 'md_cor_serie_exp_reativar') {
            $bolAcaoReativar = SessaoSEI::getInstance()->verificarPermissao('md_cor_serie_exp_reativar');
            $bolAcaoConsultar = false;
            $bolAcaoAlterar = false;
            $bolAcaoImprimir = true;
            $bolAcaoExcluir = SessaoSEI::getInstance()->verificarPermissao('md_cor_serie_exp_excluir');
            $bolAcaoDesativar = false;
        } else {
            $bolAcaoReativar = false;
            $bolAcaoConsultar = SessaoSEI::getInstance()->verificarPermissao('md_cor_serie_exp_consultar');
            $bolAcaoAlterar = SessaoSEI::getInstance()->verificarPermissao('md_cor_serie_exp_alterar');
            $bolAcaoImprimir = true;
            $bolAcaoExcluir = SessaoSEI::getInstance()->verificarPermissao('md_cor_serie_exp_excluir');
            $bolAcaoDesativar = SessaoSEI::getInstance()->verificarPermissao('md_cor_serie_exp_desativar');
        }


        if ($bolAcaoDesativar) {
            $bolCheck = true;
            $arrComandos[] = '<button type="button" accesskey="t" id="btnDesativar" value="Desativar" onclick="acaoDesativacaoMultipla();" class="infraButton">Desa<span class="infraTeclaAtalho">t</span>ivar</button>';
            $strLinkDesativar = SessaoSEI::getInstance()->assinarLink('controlador.php?acao=md_cor_serie_exp_desativar&acao_origem=' . $_GET['acao']);
        }

        if ($bolAcaoReativar) {
            $bolCheck = true;
            $arrComandos[] = '<button type="button" accesskey="R" id="btnReativar" value="Reativar" onclick="acaoReativacaoMultipla();" class="infraButton"><span class="infraTeclaAtalho">R</span>eativar</button>';
            $strLinkReativar = SessaoSEI::getInstance()->assinarLink('controlador.php?acao=md_cor_serie_exp_reativar&acao_origem=' . $_GET['acao'] . '&acao_confirmada=sim');
        }


        if ($bolAcaoExcluir) {
            $bolCheck = true;
            $arrComandos[] = '<button type="button" accesskey="E" id="btnExcluir" value="Excluir" onclick="acaoExclusaoMultipla();" class="infraButton"><span class="infraTeclaAtalho">E</span>xcluir</button>';
            $strLinkExcluir = SessaoSEI::getInstance()->assinarLink('controlador.php?acao=md_cor_serie_exp_excluir&acao_origem=' . $_GET['acao']);
        }

        if ($bolAcaoImprimir) {
            $bolCheck = true;
            $arrComandos[] = '<button type="button" accesskey="I" id="btnImprimir" value="Imprimir" onclick="infraImprimirTabela();" class="infraButton"><span class="infraTeclaAtalho">I</span>mprimir</button>';
        }

        $strResultado = '';

        if ($_GET['acao'] != 'md_cor_serie_exp_reativar') {
            $strSumarioTabela = 'Tabela de Tipos de Documento.';
            $strCaptionTabela = 'Tipos de Documento';
        } else {
            $strSumarioTabela = 'Tabela de Tipos de Documento Inativos.';
            $strCaptionTabela = 'Tipos de Documento Inativos';
        }

        $strResultado .= '<table width="100%" class="infraTable" summary="' . $strSumarioTabela . '">' . "\n";
        $strResultado .= '<caption class="infraCaption">' . PaginaSEI::getInstance()->gerarCaptionTabela($strCaptionTabela, $numRegistros) . '</caption>';
        $strResultado .= '<tr>';
        if ($bolCheck) {
            $strResultado .= '<th class="infraTh" width="1%">' . PaginaSEI::getInstance()->getThCheck() . '</th>' . "\n";
        }
        $strResultado .= '<th class="infraTh" width="10%">ID</th>' . "\n";
        $strResultado .= '<th class="infraTh text-left">' . PaginaSEI::getInstance()->getThOrdenacao($objSerieDTO, 'Nome', 'Nome', $arrObjSerieDTO) . '</th>' . "\n";
        //$strResultado .= '<th class="infraTh">Modelo</th>'."\n";
        $strResultado .= '<th class="infraTh text-left">' . PaginaSEI::getInstance()->getThOrdenacao($objSerieDTO, 'Grupo', 'NomeGrupoSerie', $arrObjSerieDTO) . '</th>' . "\n";
        $strResultado .= '<th class="infraTh" width="15%">Ações</th>' . "\n";
        $strResultado .= '</tr>' . "\n";
        $strCssTr = '';
        for ($i = 0; $i < $numRegistros; $i++) {

            $strCssTr = ($strCssTr == '<tr class="infraTrClara">') ? '<tr class="infraTrEscura">' : '<tr class="infraTrClara">';
            $strResultado .= $strCssTr;

            if ($bolCheck) {
                $strResultado .= '<td valign="top">' . PaginaSEI::getInstance()->getTrCheck($i, $arrObjSerieDTO[$i]->getNumIdSerie(), $arrObjSerieDTO[$i]->getStrNome()) . '</td>';
            }
            $strResultado .= '<td align="center">' . $arrObjSerieDTO[$i]->getNumIdSerie() . '</td>';
            $strResultado .= '<td>' . PaginaSEI::tratarHTML($arrObjSerieDTO[$i]->getStrNome()) . '</td>';
            $strResultado .= '<td>' . PaginaSEI::tratarHTML($arrObjSerieDTO[$i]->getStrNomeGrupoSerie()) . '</td>';
            $strResultado .= '<td align="center">';

            $strResultado .= PaginaSEI::getInstance()->getAcaoTransportarItem($i, $arrObjSerieDTO[$i]->getNumIdSerie());

            if ($bolAcaoConsultar) {
                $strResultado .= '<a href="' . SessaoSEI::getInstance()->assinarLink('controlador.php?acao=md_cor_serie_exp_consultar&acao_origem=' . $_GET['acao'] . '&acao_retorno=' . $_GET['acao'] . '&id_serie=' . $arrObjSerieDTO[$i]->getNumIdSerie()) . '" tabindex="' . PaginaSEI::getInstance()->getProxTabTabela() . '"><img src="imagens/consultar.gif" title="Consultar Tipo de Documento" alt="Consultar Tipo de Documento" class="infraImg" /></a>&nbsp;';
            }

            if ($bolAcaoAlterar) {
                $strResultado .= '<a href="' . SessaoSEI::getInstance()->assinarLink('controlador.php?acao=md_cor_serie_exp_alterar&acao_origem=' . $_GET['acao'] . '&acao_retorno=' . $_GET['acao'] . '&id_serie=' . $arrObjSerieDTO[$i]->getNumIdSerie()) . '" tabindex="' . PaginaSEI::getInstance()->getProxTabTabela() . '"><img src="imagens/alterar.gif" title="Alterar Tipo de Documento" alt="Alterar Tipo de Documento" class="infraImg" /></a>&nbsp;';
            }

            if ($bolAcaoDesativar || $bolAcaoReativar || $bolAcaoExcluir) {
                $strId = $arrObjSerieDTO[$i]->getNumIdSerie();
                $strDescricao = PaginaSEI::getInstance()->formatarParametrosJavaScript(PaginaSEI::tratarHTML($arrObjSerieDTO[$i]->getStrNome()));
            }

            if ($bolAcaoDesativar) {
                $strResultado .= '<a href="#ID-' . $strId . '" onclick="acaoDesativar(\'' . $strId . '\',\'' . $strDescricao . '\');" tabindex="' . PaginaSEI::getInstance()->getProxTabTabela() . '"><img src="imagens/desativar.gif" title="Desativar Tipo de Documento" alt="Desativar Tipo de Documento" class="infraImg" /></a>&nbsp;';
            }

            if ($bolAcaoReativar) {
                $strResultado .= '<a href="#ID-' . $strId . '" onclick="acaoReativar(\'' . $strId . '\',\'' . $strDescricao . '\');" tabindex="' . PaginaSEI::getInstance()->getProxTabTabela() . '"><img src="imagens/reativar.gif" title="Reativar Tipo de Documento" alt="Reativar Tipo de Documento" class="infraImg" /></a>&nbsp;';
            }


            if ($bolAcaoExcluir) {
                $strResultado .= '<a href="#ID-' . $strId . '" onclick="acaoExcluir(\'' . $strId . '\',\'' . $strDescricao . '\');" tabindex="' . PaginaSEI::getInstance()->getProxTabTabela() . '"><img src="imagens/excluir.gif" title="Excluir Tipo de Documento" alt="Excluir Tipo de Documento" class="infraImg" /></a>&nbsp;';
            }

            $strResultado .= '</td></tr>' . "\n";
        }
        $strResultado .= '</table>';
    }
    if ($_GET['acao'] == 'md_cor_serie_exp_selecionar') {
        $arrComandos[] = '<button type="button" accesskey="F" id="btnFecharSelecao" value="Fechar" onclick="window.close();" class="infraButton">Fe<span class="infraTeclaAtalho">c</span>har</button>';
    } else {
        $arrComandos[] = '<button type="button" accesskey="F" id="btnFechar" value="Fechar" onclick="location.href=\'' . SessaoSEI::getInstance()->assinarLink('controlador.php?acao=' . PaginaSEI::getInstance()->getAcaoRetorno() . '&acao_origem=' . $_GET['acao'] . PaginaSEI::getInstance()->montarAncora($numIdGrupoSerie)) . '\'" class="infraButton">Fe<span class="infraTeclaAtalho">c</span>har</button>';
    }

    $strItensSelGrupoSerie = GrupoSerieINT::montarSelectNomeRI0801('', 'Todos', $numIdGrupoSerie);
    $strItensSelModelo = ModeloINT::montarSelectNome('null', 'Todos', $numIdModelo);
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
PaginaSEI::getInstance()->abrirJavaScript();
?>

function inicializar(){
if ('<?= $_GET['acao'] ?>'=='md_cor_serie_exp_selecionar'){
infraReceberSelecao();
document.getElementById('btnFecharSelecao').focus();
}else{
document.getElementById('btnFechar').focus();
}

infraEfeitoTabelas();
}

<? if ($bolAcaoDesativar) { ?>
    function acaoDesativar(id,desc){
    if (confirm("Confirma desativação do Tipo de Documento \""+desc+"\"?")){
    document.getElementById('hdnInfraItemId').value=id;
    document.getElementById('frmSerieLista').action='<?= $strLinkDesativar ?>';
    document.getElementById('frmSerieLista').submit();
    }
    }

    function acaoDesativacaoMultipla(){
    if (document.getElementById('hdnInfraItensSelecionados').value==''){
    alert('Nenhum Tipo de Documento selecionado.');
    return;
    }
    if (confirm("Confirma desativação dos Tipos de Documento selecionados?")){
    document.getElementById('hdnInfraItemId').value='';
    document.getElementById('frmSerieLista').action='<?= $strLinkDesativar ?>';
    document.getElementById('frmSerieLista').submit();
    }
    }
<? } ?>

<? if ($bolAcaoReativar) { ?>
    function acaoReativar(id,desc){
    if (confirm("Confirma reativação do Tipo de Documento \""+desc+"\"?")){
    document.getElementById('hdnInfraItemId').value=id;
    document.getElementById('frmSerieLista').action='<?= $strLinkReativar ?>';
    document.getElementById('frmSerieLista').submit();
    }
    }

    function acaoReativacaoMultipla(){
    if (document.getElementById('hdnInfraItensSelecionados').value==''){
    alert('Nenhum Tipo de Documento selecionado.');
    return;
    }
    if (confirm("Confirma reativação dos Tipos de Documento selecionados?")){
    document.getElementById('hdnInfraItemId').value='';
    document.getElementById('frmSerieLista').action='<?= $strLinkReativar ?>';
    document.getElementById('frmSerieLista').submit();
    }
    }
<? } ?>

<? if ($bolAcaoExcluir) { ?>
    function acaoExcluir(id,desc){
    if (confirm("Confirma exclusão do Tipo de Documento \""+desc+"\"?")){
    document.getElementById('hdnInfraItemId').value=id;
    document.getElementById('frmSerieLista').action='<?= $strLinkExcluir ?>';
    document.getElementById('frmSerieLista').submit();
    }
    }

    function acaoExclusaoMultipla(){
    if (document.getElementById('hdnInfraItensSelecionados').value==''){
    alert('Nenhum Tipo de Documento selecionado.');
    return;
    }
    if (confirm("Confirma exclusão dos Tipos de Documento selecionados?")){
    document.getElementById('hdnInfraItemId').value='';
    document.getElementById('frmSerieLista').action='<?= $strLinkExcluir ?>';
    document.getElementById('frmSerieLista').submit();
    }
    }
<? } ?>

<?
PaginaSEI::getInstance()->fecharJavaScript();
PaginaSEI::getInstance()->fecharHead();
PaginaSEI::getInstance()->abrirBody($strTitulo, 'onload="inicializar();"');
$cobranca = isset($_GET['cobranca']) ? '&cobranca='.$_GET['cobranca'] : '';
?>
<form id="frmSerieLista" method="post" action="<?= SessaoSEI::getInstance()->assinarLink('controlador.php?acao=' . $_GET['acao'] . '&acao_origem=' . $_GET['acao'] . '&tipo_selecao=' . $_GET['tipo_selecao'] . $cobranca) ?>">
    <?
    //PaginaSEI::getInstance()->montarBarraLocalizacao($strTitulo);
    PaginaSEI::getInstance()->montarBarraComandosSuperior($arrComandos);
    PaginaSEI::getInstance()->abrirAreaDados();
    ?>

    <div class="row">
        <div class="col-12 col-sm-6 col-md-6 col-lg-4 col-xl-4">
            <div class="form-group">
                <label id="lblNomeSeriePesquisa" for="txtNomeSeriePesquisa" accesskey="" class="infraLabelOpcional">Nome:</label>
                <input type="text" id="txtNomeSeriePesquisa" name="txtNomeSeriePesquisa" value="<?= PaginaSEI::tratarHTML($strNomeSeriePesquisa) ?>" class="infraText form-control" tabindex="<?= PaginaSEI::getInstance()->getProxTabDados() ?>" />
            </div>
        </div>
        <div class="col-12 col-sm-6 col-md-6 col-lg-4 col-xl-4">
            <div class="form-group">
                <label id="lblGrupoSerie" for="selGrupoSerie" accesskey="" class="infraLabelOpcional">Grupo:</label>
                <select id="selGrupoSerie" name="selGrupoSerie" onchange="this.form.submit();" class="infraSelect form-select" tabindex="<?= PaginaSEI::getInstance()->getProxTabDados() ?>" >
                    <?= $strItensSelGrupoSerie ?>
                </select>
            </div>
        </div>
        <div class="col-12 col-sm-12 col-md-10 col-lg-4 col-xl-4">
            <label id="lblModeloPesquisa" for="selModeloPesquisa" accesskey="" class="infraLabelOpcional">Modelo:</label>
            <select id="selModeloPesquisa" name="selModeloPesquisa" onchange="this.form.submit();" class="infraSelect form-select" tabindex="<?= PaginaSEI::getInstance()->getProxTabDados() ?>" >
                <?= $strItensSelModelo ?>
            </select>
        </div>
    </div>

    <? PaginaSEI::getInstance()->fecharAreaDados(); ?>
    <div class="row">
        <div class="col-12">
        <? PaginaSEI::getInstance()->montarAreaTabela($strResultado, $numRegistros); ?>
        </div>
    </div>
    <? 
    PaginaSEI::getInstance()->montarAreaDebug();
    PaginaSEI::getInstance()->montarBarraComandosInferior($arrComandos);
    ?>

</form>

<?
PaginaSEI::getInstance()->fecharBody();
PaginaSEI::getInstance()->fecharHtml();
?>