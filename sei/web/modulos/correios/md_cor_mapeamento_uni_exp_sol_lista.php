<?php
/**
 * ANATEL
 *
 * 26/12/2016 - criado por marcelo.emiliano@cast.com.br - CAST
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
    PaginaSEI::getInstance()->verificarSelecao('md_cor_mapeamento_uni_exp_sol_selecionar');
    SessaoSEI::getInstance()->validarPermissao($_GET['acao']);

    $strItensSelUnidade = MdCorUnidadeExpINT::montarSelectUnidade(null, null, $_POST['selUnidadeExpedidora'], false);
    $strItensSelUnidadeSolicitante = MdCorMapeamentoUniExpSolINT::montarSelectUnidadesMapeadas(null, $_POST['selUnidadeSolicitante'], false);

    switch ($_GET['acao']) {
        case 'md_cor_mapeamento_uni_exp_sol_listar':
            $strTitulo = 'Mapeamento de Unidades Expedidoras e Unidades Solicitantes';
            break;
        case 'md_cor_mapeamento_uni_exp_sol_desativar':
            /*
            try{
                $arrStrIds = PaginaSEI::getInstance()->getArrStrItensSelecionados();
                $arrObjMdCorMapeamentoUniExpSolDTO = array();

                $objMdCorMapeamentoUniExpSolDTO = new MdCorMapeamentoUniExpSolDTO();
                $objMdCorMapeamentoUniExpSolRN = new MdCorMapeamentoUniExpSolRN();
                $objMdCorMapeamentoUniExpSolDTO->setNumIdUnidadeExp($arrStrIds[0]);
                $objMdCorMapeamentoUniExpSolDTO->retNumIdUnidadeExp();
                $objMdCorMapeamentoUniExpSolDTO->retNumIdUnidadeSolicitante();

                $arrObjDto = $objMdCorMapeamentoUniExpSolRN->listar($objMdCorMapeamentoUniExpSolDTO);
                $objMdCorMapeamentoUniExpSolRN->desativar($arrObjDto);

            }catch(Exception $e){
                PaginaSEI::getInstance()->processarExcecao($e);
            }
            header('Location: '.SessaoSEI::getInstance()->assinarLink('controlador.php?acao='.$_GET['acao_origem'].'&acao_origem='.$_GET['acao']));
            */

            $objMdCorMapeamentoUniExpSolRN = new MdCorMapeamentoUniExpSolRN();
            $resultado = $objMdCorMapeamentoUniExpSolRN->excluirDesativarVerificarServicos('D');
            if ($resultado) {
                header('Location: ' . SessaoSEI::getInstance()->assinarLink('controlador.php?acao=' . $_GET['acao_origem'] . '&acao_origem=' . $_GET['acao']));
            }

            break;

        case 'md_cor_mapeamento_uni_exp_sol_reativar':
            try {
                $arrStrIds = PaginaSEI::getInstance()->getArrStrItensSelecionados();
                $arrObjMdCorMapeamentoUniExpSolDTO = array();

                $objMdCorMapeamentoUniExpSolDTO = new MdCorMapeamentoUniExpSolDTO();
                $objMdCorMapeamentoUniExpSolRN = new MdCorMapeamentoUniExpSolRN();
                $objMdCorMapeamentoUniExpSolDTO->setNumIdUnidadeExp($arrStrIds[0]);
                $objMdCorMapeamentoUniExpSolDTO->retTodos();

                $arrObjDto = $objMdCorMapeamentoUniExpSolRN->listar($objMdCorMapeamentoUniExpSolDTO);
                $objMdCorMapeamentoUniExpSolRN->reativar($arrObjDto);
            } catch (Exception $e) {
                PaginaSEI::getInstance()->processarExcecao($e);
            }
            header('Location: ' . SessaoSEI::getInstance()->assinarLink('controlador.php?acao=' . $_GET['acao_origem'] . '&acao_origem=' . $_GET['acao']));

            break;

        case 'md_cor_mapeamento_uni_exp_sol_excluir':
            /*
            try{
                $arrStrIds = PaginaSEI::getInstance()->getArrStrItensSelecionados();
                $arrObjMdCorMapeamentoUniExpSolDTO = array();

                $objMdCorMapeamentoUniExpSolDTO = new MdCorMapeamentoUniExpSolDTO();
                $objMdCorMapeamentoUniExpSolRN = new MdCorMapeamentoUniExpSolRN();
                $objMdCorMapeamentoUniExpSolDTO->setNumIdUnidadeExp($arrStrIds[0]);
                $objMdCorMapeamentoUniExpSolDTO->retNumIdUnidadeExp();
                $objMdCorMapeamentoUniExpSolDTO->retNumIdUnidadeSolicitante();

                $arrObjDto = $objMdCorMapeamentoUniExpSolRN->listar($objMdCorMapeamentoUniExpSolDTO);
                $objMdCorMapeamentoUniExpSolRN->excluir($arrObjDto);
            }catch(Exception $e){
                PaginaSEI::getInstance()->processarExcecao($e);
            }
            header('Location: '.SessaoSEI::getInstance()->assinarLink('controlador.php?acao='.$_GET['acao_origem'].'&acao_origem='.$_GET['acao']));
            */
            $objMdCorMapeamentoUniExpSolRN = new MdCorMapeamentoUniExpSolRN();
            $resultado = $objMdCorMapeamentoUniExpSolRN->excluirDesativarVerificarServicos('E');
            if ($resultado) {
                header('Location: ' . SessaoSEI::getInstance()->assinarLink('controlador.php?acao=' . $_GET['acao_origem'] . '&acao_origem=' . $_GET['acao']));
            }
            break;

        default:
            throw new InfraException("Ação '" . $_GET['acao'] . "' não reconhecida.");
    }
    $arrComandos = [];
    $arrComandos[] = '<button type="button" onclick="pesquisar()" accesskey="P" id="btnPesquisar" name="btnPesquisar" value="Pesquisar" class="infraButton"><span class="infraTeclaAtalho">P</span>esquisar</button>';
    $arrComandos[] = '<button type="button" accesskey="N" id="btnNovo" value="Novo" onclick="location.href=\'' . SessaoSEI::getInstance()->assinarLink('controlador.php?acao=md_cor_mapeamento_uni_exp_sol_cadastrar&acao_origem=' . $_GET['acao'] . '&acao_retorno=' . $_GET['acao']) . '\'" class="infraButton"><span class="infraTeclaAtalho">N</span>ovo</button>';
    $arrComandos[] = '<button type="button" accesskey="I" id="btnImprimir" value="Imprimir" onclick="infraImprimirTabela();" class="infraButton"><span class="infraTeclaAtalho">I</span>mprimir</button>';

    //$arrComandos[] = '<button type="button" accesskey="C" id="btnFechar" value="Fechar" onclick="location.href=\''.SessaoSEI::getInstance()->assinarLink('controlador.php?acao='.PaginaSEI::getInstance()->getAcaoRetorno().'&acao_origem='.$_GET['acao']).'\'" class="infraButton">Fe<span class="infraTeclaAtalho">c</span>har</button>';
    $arrComandos[] = '<button type="button" accesskey="C" id="btnFechar" value="Fechar" onclick="location.href=\'' . SessaoSEI::getInstance()->assinarLink('controlador.php?acao=procedimento_controlar&acao_origem=' . $_GET['acao']) . '\'" class="infraButton">Fe<span class="infraTeclaAtalho">c</span>har</button>';

} catch (Exception $e) {
    PaginaSEI::getInstance()->processarExcecao($e);
}

//Condição dos botões
$bolSelecionar = $_GET['acao'] == 'md_cor_mapeamento_uni_exp_sol_selecionar';
$bolAcaoReativar = SessaoSEI::getInstance()->verificarPermissao('md_cor_mapeamento_uni_exp_sol_reativar');
$bolAcaoConsultar = SessaoSEI::getInstance()->verificarPermissao('md_cor_mapeamento_uni_exp_sol_consultar');
$bolAcaoAlterar = SessaoSEI::getInstance()->verificarPermissao('md_cor_mapeamento_uni_exp_sol_alterar');;
$bolAcaoImprimir = true;
$bolAcaoExcluir = SessaoSEI::getInstance()->verificarPermissao('md_cor_mapeamento_uni_exp_sol_excluir');
$bolAcaoDesativar = SessaoSEI::getInstance()->verificarPermissao('md_cor_mapeamento_uni_exp_sol_desativar');

if ($bolAcaoDesativar) {
    $strLinkDesativar = SessaoSEI::getInstance()->assinarLink('controlador.php?acao=md_cor_mapeamento_uni_exp_sol_desativar&acao_origem=' . $_GET['acao']);
}

if ($bolAcaoReativar) {
    $strLinkReativar = SessaoSEI::getInstance()->assinarLink('controlador.php?acao=md_cor_mapeamento_uni_exp_sol_reativar&acao_origem=' . $_GET['acao']);
}

if ($bolAcaoExcluir) {
    $strLinkExcluir = SessaoSEI::getInstance()->assinarLink('controlador.php?acao=md_cor_mapeamento_uni_exp_sol_excluir&acao_origem=' . $_GET['acao']);
}

$objMdCorMapeamentoUniExpSolDTO = new MdCorMapeamentoUniExpSolDTO();
$objMdCorMapeamentoUniExpSolDTO->retTodos(true);

$objMdCorMapeamentoRN = new MdCorMapeamentoUniExpSolRN();

$objMdCorMapeamentoUniExpSolDTO->setOrdStrSiglaUnidadeExpedidora(InfraDTO::$TIPO_ORDENACAO_ASC);

if ($_POST['selUnidadeExpedidora'] != '') {
    $objMdCorMapeamentoUniExpSolDTO->setNumIdUnidadeExp($_POST['selUnidadeExpedidora']);
}

$retorno = $objMdCorMapeamentoRN->listar($objMdCorMapeamentoUniExpSolDTO);

$filtroMultiplos = $_POST['selUnidadeSolicitante'] == 'M' ? true : false;
$idSolicitante = $_POST['selUnidadeSolicitante'] != '' && !$filtroMultiplos ? $_POST['selUnidadeSolicitante'] : false;

$arrObjMdCorSerieExpDTO = MdCorMapeamentoUniExpSolINT::formatarListaUnidadesMapeadas($retorno, $filtroMultiplos, $idSolicitante);

PaginaSEI::getInstance()->prepararOrdenacao($objMdCorMapeamentoUniExpSolDTO, 'IdUnidadeExp', InfraDTO::$TIPO_ORDENACAO_ASC);
PaginaSEI::getInstance()->prepararPaginacao($objMdCorMapeamentoUniExpSolDTO, 200);
PaginaSEI::getInstance()->processarPaginacao($objMdCorMapeamentoUniExpSolDTO);

$numRegistros = count($arrObjMdCorSerieExpDTO);

if ($numRegistros > 0) {
    $strResultado .= '<table width="100%" class="infraTable" summary="Mapeamento de Unidades Solicitantes">';
    $strResultado .= '<caption class="infraCaption">';
    $strResultado .= PaginaSEI::getInstance()->gerarCaptionTabela('Unidades Mapeadas', $numRegistros);
    $strResultado .= '</caption>';
    #Cabealho da Tabela
    $strResultado .= '<tr>';
    $strResultado .= '<th class="infraTh" align="center" width="10px">' . PaginaSEI::getInstance()->getThCheck() . '</th>';
    $strResultado .= '<th class="infraTh text-left" width="auto">' . PaginaSEI::getInstance()->getThOrdenacao($objMdCorMapeamentoUniExpSolDTO, 'Unidade Expedidora', 'SiglaUnidadeExpedidora', $arrObjMdCorSerieExpDTO) . '</th>';
    $strResultado .= '<th class="infraTh text-left" width="auto">' . PaginaSEI::getInstance()->getThOrdenacao($objMdCorMapeamentoUniExpSolDTO, 'Unidade Solicitante', 'SiglaUnidadeSolicitante', $arrObjMdCorSerieExpDTO) . '</th>';
    $strResultado .= '<th class="infraTh" width="140px">Ações</th>';
    $strResultado .= '</tr>';
    #Linhas
    $strCssTr = '<tr class="infraTrEscura">';
    for ($i = 0; $i < $numRegistros; $i++) {
        #vars
        $strIdUnidades = $arrObjMdCorSerieExpDTO[$i]->getNumIdUnidadeExp();
        $strNomeUnidadeExpedidora = $arrObjMdCorSerieExpDTO[$i]->getStrSiglaUnidadeExpedidora() . ' - ' . $arrObjMdCorSerieExpDTO[$i]->getStrDescricaoUnidadeExpedidora();
        $strNomeUnidadeSolicitante = $arrObjMdCorSerieExpDTO[$i]->getStrSiglaUnidadeSolicitante();
        $bolRegistroAtivo = $arrObjMdCorSerieExpDTO[$i]->getStrSinAtivo() == 'S';
        $strCssTr = !$bolRegistroAtivo ? '<tr class="infraTrVermelha">' : ($strCssTr == '<tr class="infraTrClara">' ? '<tr class="infraTrEscura">' : '<tr class="infraTrClara">');
        $strResultado .= $strCssTr;
        #Linha Checkbox
        $strResultado .= '<td align="center" valign="top">';
        $strResultado .= PaginaSEI::getInstance()->getTrCheck($i, $strIdUnidades, $strNomeServico);
        $strResultado .= '</td>';
        #Linha Unidade Expedidora
        $strResultado .= '<td>';
        $strResultado .= PaginaSEI::tratarHTML($strNomeUnidadeExpedidora);
        $strResultado .= '</td>';
        $strResultado .= '<td>';
        $strResultado .= PaginaSEI::tratarHTML($strNomeUnidadeSolicitante);
        $strResultado .= '</td>';
        $strResultado .= '<td align="center">';
        #Ao Consulta
        if (!$bolSelecionar) {
            $strResultado .= '<a href="' . PaginaSEI::getInstance()->formatarXHTML(SessaoSEI::getInstance()->assinarLink('controlador.php?acao=md_cor_mapeamento_uni_exp_sol_consultar&acao_origem=' . $_GET['acao'] . '&acao_retorno=' . $_GET['acao'] . '&id_unidades=' . $strIdUnidades)) . '" tabindex="' . PaginaSEI::getInstance()->getProxTabTabela() . '"><img src="' . PaginaSEI::getInstance()->getDiretorioSvgGlobal() . '/consultar.svg" title="Consultar Mapeamento Unidades Solicitantes" alt="Consultar Mapeamento Unidades Solicitantes" class="infraImg" /></a>&nbsp;';
            $strResultado .= '<a href="' . PaginaSEI::getInstance()->formatarXHTML(SessaoSEI::getInstance()->assinarLink('controlador.php?acao=md_cor_mapeamento_uni_exp_sol_alterar&acao_origem=' . $_GET['acao'] . '&acao_retorno=' . $_GET['acao'] . '&id_unidades=' . $strIdUnidades)) . '" tabindex="' . PaginaSEI::getInstance()->getProxTabTabela() . '"><img src="' . PaginaSEI::getInstance()->getDiretorioSvgGlobal() . '/alterar.svg" title="Alterar Mapeamento Unidades Solicitantes" alt="Alterar Mapeamento Unidades Solicitantes" class="infraImg" /></a>&nbsp;';

            #Ao Desativar
            if ($bolRegistroAtivo) {
                $strResultado .= '<a href="' . PaginaSEI::getInstance()->montarAncora($strIdUnidades) . '" onclick="acaoDesativar(\'' . $strIdUnidades . '\',\')' . PaginaSEI::getInstance()->formatarParametrosJavaScript(PaginaSEI::tratarHTML($strNomeServico)) . '\');" tabindex="' . PaginaSEI::getInstance()->getProxTabTabela() . '"><img src="' . PaginaSEI::getInstance()->getDiretorioSvgGlobal() . '/desativar.svg" title="Desativar Mapeamento Unidades Solicitantes" alt="Desativar Mapeamento Unidades Solicitantes" class="infraImg" /></a>&nbsp;';
            }
            #Ao Reativar
            if (!$bolRegistroAtivo) {
                $strResultado .= '<a href="' . PaginaSEI::getInstance()->montarAncora($strIdUnidades) . '" onclick="acaoReativar(\'' . $strIdUnidades . '\',\')' . PaginaSEI::getInstance()->formatarParametrosJavaScript(PaginaSEI::tratarHTML($strNomeServico)) . '\');" tabindex="' . PaginaSEI::getInstance()->getProxTabTabela() . '"><img src="' . PaginaSEI::getInstance()->getDiretorioSvgGlobal() . '/reativar.svg" title="Reativar Mapeamento Unidades Solicitantes" alt="Reativar Mapeamento Unidades Solicitantes" class="infraImg" /></a>&nbsp;';
            }
            #Ao Excluir
            $strResultado .= '<a href="' . PaginaSEI::getInstance()->montarAncora($strIdUnidades) . '" onclick="acaoExcluir(\'' . $strIdUnidades . '\',\')' . PaginaSEI::getInstance()->formatarParametrosJavaScript(PaginaSEI::tratarHTML($strNomeServico)) . '\');" tabindex="' . PaginaSEI::getInstance()->getProxTabTabela() . '"><img src="' . PaginaSEI::getInstance()->getDiretorioSvgGlobal() . '/excluir.svg" title="Excluir Mapeamento Unidades Solicitantes" alt="Excluir Mapeamento Unidades Solicitantes" class="infraImg" /></a>&nbsp;';
        } else {
            $strResultado .= PaginaSEI::getInstance()->getAcaoTransportarItem($i, $strIdUnidades);
        }
        $strResultado .= '</td>';
        $strResultado .= '</tr>';
    }
    $strResultado .= '</table>';
}

PaginaSEI::getInstance()->montarDocType();
PaginaSEI::getInstance()->abrirHtml();
PaginaSEI::getInstance()->abrirHead();
PaginaSEI::getInstance()->montarMeta();
PaginaSEI::getInstance()->montarTitle(PaginaSEI::getInstance()->getStrNomeSistema() . ' - ' . $strTitulo);
PaginaSEI::getInstance()->montarStyle();
PaginaSEI::getInstance()->abrirStyle();
?>
.infraDivOrdenacao { margin: 0px !important; padding: 0px !important }
<?
PaginaSEI::getInstance()->fecharStyle();
PaginaSEI::getInstance()->montarJavaScript();
PaginaSEI::getInstance()->abrirJavaScript();
PaginaSEI::getInstance()->fecharJavaScript();
PaginaSEI::getInstance()->fecharHead();
PaginaSEI::getInstance()->abrirBody($strTitulo, 'onload="inicializar();"');
?>
    <form id="frmMdCorUnidadeExpCadastro" method="post" action="<?= SessaoSEI::getInstance()->assinarLink('controlador.php?acao=' . $_GET['acao'] . '&acao_origem=' . $_GET['acao']) ?>">
        <?php PaginaSEI::getInstance()->montarBarraComandosSuperior($arrComandos); ?>
        <div class="row">
            <div class="col-12 col-sm-6 col-md-6 col-lg-4 col-xl-4">
                <div class="form-group">
                    <label id="lblUnidadeExpedidora" for="selUnidadeExpedidora" class="infraLabelOpcional">Unidades Expedidoras:</label>
                    <select onchange="pesquisar()" id="selUnidadeExpedidora" name="selUnidadeExpedidora" class="infraSelect form-select" tabindex="<?= PaginaSEI::getInstance()->getProxTabDados() ?>" <?= $strDesabilitar; ?>>
                        <?= $strItensSelUnidade ?>
                    </select>
                </div>
            </div>
            <div class="col-12 col-sm-6 col-md-6 col-lg-4 col-xl-4">
                <div class="form-group">
                    <label id="lblUnidadeSolicitante" for="selUnidadeSolicitante" class="infraLabelOpcional">Unidades Solicitantes:</label>
                    <select onchange="pesquisar()" id="selUnidadeSolicitante" name="selUnidadeSolicitante" class="infraSelect form-select" tabindex="<?= PaginaSEI::getInstance()->getProxTabDados() ?>" <?= $strDesabilitar; ?>>
                        <?= $strItensSelUnidadeSolicitante ?>
                    </select>
                </div>
            </div>
        </div>
        <?php PaginaSEI::getInstance()->montarAreaTabela($strResultado, $numRegistros); ?>
    </form>
    <div class="clear"></div>
    <div class="clear"></div>
    <div class="clear"></div>
<?php
require_once("md_cor_mapeamento_uni_exp_sol_lista_js.php");
PaginaSEI::getInstance()->fecharBody();
PaginaSEI::getInstance()->fecharHtml();
?>