<?
/**
 * ANATEL
 *
 * 27/12/2016 - criado por marcelo.emiliano@cast.com.br - CAST
 */
try {

    require_once dirname(__FILE__) . '/../../SEI.php';

    session_start();

    //////////////////////////////////////////////////////////////////////////////
    InfraDebug::getInstance()->setBolLigado(false);
    InfraDebug::getInstance()->setBolDebugInfra(true);
    InfraDebug::getInstance()->limpar();
    //////////////////////////////////////////////////////////////////////////////

    SessaoSEI::getInstance()->validarLink();

    PaginaSEI::getInstance()->prepararSelecao('md_cor_unidade_selecionar_todas');

    SessaoSEI::getInstance()->validarPermissao($_GET['acao']);

    PaginaSEI::getInstance()->salvarCamposPost(array('selOrgao', 'txtSiglaUnidade', 'txtDescricaoUnidade'));

    switch ($_GET['acao']) {
        case 'md_cor_unidade_selecionar_todas':
            $strTitulo = PaginaSEI::getInstance()->getTituloSelecao('Selecionar Unidade', 'Selecionar Unidades');
            break;

        default:
            throw new InfraException("A��o '" . $_GET['acao'] . "' n�o reconhecida.");
    }

    $arrComandos = array();

    $arrComandos[] = '<button accesskey="P" type="submit" id="btnPesquisar" value="Pesquisar" class="infraButton"><span class="infraTeclaAtalho">P</span>esquisar</button>';

    if ($_GET['acao'] == 'md_cor_unidade_selecionar_todas') {
        $arrComandos[] = '<button type="button" accesskey="T" id="btnTransportarSelecao" value="Transportar" onclick="infraTransportarSelecao();" class="infraButton"><span class="infraTeclaAtalho">T</span>ransportar</button>';
    }

    $objUnidadeRN = new UnidadeRN();
    $objUnidadeDTO = new UnidadeDTO();
    $objUnidadeDTO->retNumIdUnidade();
    $objUnidadeDTO->retStrSigla();
    $objUnidadeDTO->retStrDescricao();
    $objUnidadeDTO->retStrSiglaOrgao();
    $objUnidadeDTO->retStrDescricaoOrgao();

    if (!is_null($_GET['intIdUnidadeExpExcecao'])) {
        $intIdUnidadeExpExcecao = $_GET['intIdUnidadeExpExcecao'];
    }

    if (!is_null($_GET['UnidadeSolicitanteSomente'])) {
        $UnidadeSolicitanteSomente = $_GET['UnidadeSolicitanteSomente'];
    }



    if ($intIdUnidadeExpExcecao != '*') {
        $arrUnidadeExpedidora = array_keys(InfraArray::indexarArrInfraDTO(MdCorUnidadeExpINT::buscarUnidadesExpedidoras(), 'IdUnidade'));
//        $arrUnidadeMapeadas = array_keys(InfraArray::indexarArrInfraDTO(MdCorMapeamentoUniExpSolINT::buscarUnidadesMapeadas($intIdUnidadeExpExcecao), 'IdUnidadeSolicitante'));
        $arrUnidadeNotIn = array_merge($arrUnidadeExpedidora);

        if (count($arrUnidadeNotIn) > 0) {
            $objUnidadeDTO->setNumIdUnidade($arrUnidadeNotIn, InfraDTO::$OPER_NOT_IN);
        }
    } else if (!is_null($UnidadeSolicitanteSomente)) {
        $arrUnidadeMapeadas = array_keys(InfraArray::indexarArrInfraDTO(MdCorMapeamentoUniExpSolINT::buscarUnidadesMapeadas(), 'IdUnidadeSolicitante'));
        $objUnidadeDTO->setNumIdUnidade($arrUnidadeMapeadas, InfraDTO::$OPER_IN);
    }


    $numNumAno = PaginaSEI::getInstance()->recuperarCampo('selAno');

    $numIdOrgao = PaginaSEI::getInstance()->recuperarCampo('selOrgao');
    if ($numIdOrgao !== '') {
        $objUnidadeDTO->setNumIdOrgao($numIdOrgao);
    }

    $strSiglaPesquisa = trim(PaginaSEI::getInstance()->recuperarCampo('txtSiglaUnidade'));
    if ($strSiglaPesquisa !== '') {
        $objUnidadeDTO->setStrSigla($strSiglaPesquisa);
    }

    $strDescricaoPesquisa = PaginaSEI::getInstance()->recuperarCampo('txtDescricaoUnidade');
    if ($strDescricaoPesquisa !== '') {
        $objUnidadeDTO->setStrDescricao($strDescricaoPesquisa);
    }

    // N�O ENCONTRADO USO
    //if ($_GET['acao'] == 'unidade_reativar') {
    //    //Lista somente inativos
    //    $objUnidadeDTO->setBolExclusaoLogica(false);
    //    $objUnidadeDTO->setStrSinAtivo('N');
    //}


    $objUnidadeDTO->setOrdStrSigla(InfraDTO::$TIPO_ORDENACAO_ASC);

    $strTermoPesquisa = '&idUnidadeExpedidora=';

    $objUnidadeRN = new UnidadeRN();
    $arrObjUnidadeDTO = $objUnidadeRN->pesquisar($objUnidadeDTO);

    if (isset($_GET['idUnidadeExpedidora'])) {
        $MdCorMapeamentoUniExpSolRN = new MdCorMapeamentoUniExpSolRN();
        $objMdCorMapeamentoUniExpSolDTO = new MdCorMapeamentoUniExpSolDTO();
        $objMdCorMapeamentoUniExpSolDTO->retNumIdUnidadeSolicitante();
        $objMdCorMapeamentoUniExpSolDTO->adicionarCriterio(array('IdUnidadeExp'), array(InfraDTO::$OPER_DIFERENTE), array($_GET['idUnidadeExpedidora']));
        $arrObjMdCorMapeamentoUniExpSolDTO = $MdCorMapeamentoUniExpSolRN->listar($objMdCorMapeamentoUniExpSolDTO);

        foreach ($arrObjUnidadeDTO as $chave => $item) {
            foreach ($arrObjMdCorMapeamentoUniExpSolDTO as $unidade) {
                if ($unidade->getNumIdUnidadeSolicitante() == $item->getNumIdUnidade()) {
                    unset($arrObjUnidadeDTO[$chave]);
                }
            }
        }
        $strTermoPesquisa = '&idUnidadeExpedidora='.$_GET['idUnidadeExpedidora'];
    }

    PaginaSEI::getInstance()->prepararPaginacao($objUnidadeDTO);
    PaginaSEI::getInstance()->processarPaginacao($objUnidadeDTO);

    $numRegistros = count($arrObjUnidadeDTO);


    if ($numRegistros > 0) {

        $bolCheck = false;

        if ($_GET['acao'] == 'md_cor_unidade_selecionar_todas') {
            // N�O ENCONTRADO USO
            //    $bolAcaoReativar = false;
            //    $bolAcaoConsultar = SessaoSEI::getInstance()->verificarPermissao('unidade_consultar');
            //    $bolAcaoAlterar = SessaoSEI::getInstance()->verificarPermissao('unidade_alterar');
            //    $bolAcaoImprimir = false;
            //    $bolAcaoExcluir = false;
            //    $bolAcaoDesativar = false;
            $bolCheck = true;
            //} else if ($_GET['acao'] == 'unidade_reativar') {
            //    $bolAcaoReativar = SessaoSEI::getInstance()->verificarPermissao('unidade_reativar');
            //    $bolAcaoConsultar = false;
            //    $bolAcaoAlterar = false;
            //    $bolAcaoImprimir = true;
            //    $bolAcaoExcluir = SessaoSEI::getInstance()->verificarPermissao('unidade_excluir');
            //    $bolAcaoDesativar = false;
            //} else if ($_GET['acao'] == 'gerar_estatisticas_unidade') {
            //    $bolAcaoReativar = false;
            //    $bolAcaoConsultar = false;
            //    $bolAcaoAlterar = false;
            //    $bolAcaoImprimir = true;
            //    $bolAcaoExcluir = false;
            //    $bolAcaoDesativar = false;
            //} else {
            //    $bolAcaoReativar = false;
            //    $bolAcaoConsultar = SessaoSEI::getInstance()->verificarPermissao('unidade_consultar');
            //    $bolAcaoAlterar = SessaoSEI::getInstance()->verificarPermissao('unidade_alterar');
            //    $bolAcaoImprimir = true;
            //    $bolAcaoExcluir = SessaoSEI::getInstance()->verificarPermissao('unidade_excluir');
            //    $bolAcaoDesativar = SessaoSEI::getInstance()->verificarPermissao('unidade_desativar');
        }

        // N�O ENCONTRADO USO
        //if ($bolAcaoDesativar) {
        //    $bolCheck = true;
        //    $strLinkDesativar = SessaoSEI::getInstance()->assinarLink('controlador.php?acao=unidade_desativar&acao_origem=' . $_GET['acao']);
        //}
        //if ($bolAcaoReativar) {
        //    $bolCheck = true;
        //    $arrComandos[] = '<button type="button" accesskey="R" id="btnReativar" value="Reativar" onclick="acaoReativacaoMultipla();" class="infraButton"><span class="infraTeclaAtalho">R</span>eativar</button>';
        //    $strLinkReativar = SessaoSEI::getInstance()->assinarLink('controlador.php?acao=unidade_reativar&acao_origem=' . $_GET['acao'] . '&acao_confirmada=sim');
        //}
        //if ($bolAcaoExcluir) {
        //    $bolCheck = true;
        //    $strLinkExcluir = SessaoSEI::getInstance()->assinarLink('controlador.php?acao=unidade_excluir&acao_origem=' . $_GET['acao']);
        //}
        //if ($bolAcaoImprimir) {
        //    $bolCheck = true;
        //    $arrComandos[] = '<button type="button" accesskey="I" id="btnImprimir" value="Imprimir" onclick="infraImprimirTabela();" class="infraButton"><span class="infraTeclaAtalho">I</span>mprimir</button>';
        //}

        $strResultado = '';

        //if ($_GET['acao'] == 'unidade_reativar') {
        //    $strSumarioTabela = 'Tabela de Unidades Inativas.';
        //    $strCaptionTabela = 'Unidades Inativas';
        //} else {
        $strSumarioTabela = 'Tabela de Unidades.';
        $strCaptionTabela = 'Unidades';
        //}

        $strResultado .= '<table width="100%" class="infraTable" summary="' . $strSumarioTabela . '">' . "\n";
        $strResultado .= '<caption class="infraCaption">' . PaginaSEI::getInstance()->gerarCaptionTabela($strCaptionTabela, $numRegistros) . '</caption>';
        $strResultado .= '<tr>';
        if ($bolCheck) {
            $strResultado .= '<th class="infraTh" width="1%">' . PaginaSEI::getInstance()->getThCheck() . '</th>' . "\n";
        }
        $strResultado .= '<th class="infraTh" width="10%">ID</th>' . "\n";
        $strResultado .= '<th class="infraTh text-center" width="10%">Sigla</th>' . "\n";
        $strResultado .= '<th class="infraTh text-left">Descri��o</th>' . "\n";
        $strResultado .= '<th class="infraTh text-center" width="5%">�rg�o</th>' . "\n";
        //$strResultado .= '<th class="infraTh">'.PaginaSEI::getInstance()->getThOrdenacao($objUnidadeDTO,'Endere�o','Endereco',$arrObjUnidadeDTO).'</th>'."\n";
        //$strResultado .= '<th class="infraTh">'.PaginaSEI::getInstance()->getThOrdenacao($objUnidadeDTO,'Data inicial padr�o para consultas','FixaInicioConsulta',$arrObjUnidadeDTO).'</th>'."\n";
        $strResultado .= '<th class="infraTh" width="15%">A��es</th>' . "\n";
        $strResultado .= '</tr>' . "\n";
        $strCssTr = '';
        foreach ($arrObjUnidadeDTO as $i => $item) {
//            if (isset($arrObjUnidadeDTO[$i])) {
            //echo $arrObjUnidadeDTO[$i]->__toString();die;

            $strCssTr = ($strCssTr == '<tr class="infraTrClara">') ? '<tr class="infraTrEscura">' : '<tr class="infraTrClara">';
            $strResultado .= $strCssTr;

            if ($bolCheck) {
                $strResultado .= '<td>' . PaginaSEI::getInstance()->getTrCheck($i, $arrObjUnidadeDTO[$i]->getNumIdUnidade(), UnidadeINT::formatarSiglaDescricao($arrObjUnidadeDTO[$i]->getStrSigla(), $arrObjUnidadeDTO[$i]->getStrDescricao())) . '</td>';
            }

            $strResultado .= '<td align="center">' . $arrObjUnidadeDTO[$i]->getNumIdUnidade() . '</td>';
            $strResultado .= '<td class="text-center">' . PaginaSEI::tratarHTML($arrObjUnidadeDTO[$i]->getStrSigla()) . '</td>';
            $strResultado .= '<td>' . PaginaSEI::tratarHTML($arrObjUnidadeDTO[$i]->getStrDescricao()) . '</td>';
            $strResultado .= '<td align="center"><a alt="' . PaginaSEI::tratarHTML($arrObjUnidadeDTO[$i]->getStrDescricaoOrgao()) . '" title="' . PaginaSEI::tratarHTML($arrObjUnidadeDTO[$i]->getStrDescricaoOrgao()) . '" class="ancoraSigla">' . PaginaSEI::tratarHTML($arrObjUnidadeDTO[$i]->getStrSiglaOrgao()) . '</a></td>';
            $strResultado .= '<td align="center">';

            $strResultado .= PaginaSEI::getInstance()->getAcaoTransportarItem($i, $arrObjUnidadeDTO[$i]->getNumIdUnidade());

            // N�O ENCONTRADO USO
            //if ($bolAcaoConsultar) {
            //    $strResultado .= '<a href="' . SessaoSEI::getInstance()->assinarLink('controlador.php?acao=unidade_consultar&acao_origem=' . $_GET['acao'] . '&acao_retorno=' . $_GET['acao'] . '&id_unidade=' . $arrObjUnidadeDTO[$i]->getNumIdUnidade() . '&sigla=' . $arrObjUnidadeDTO[$i]->getStrSigla()) . '" tabindex="' . PaginaSEI::getInstance()->getProxTabTabela() . '"><img src="imagens/consultar.gif" title="Consultar Unidade" alt="Consultar Unidade" class="infraImg" /></a>&nbsp;';
            //}
            //if ($bolAcaoAlterar) {
            //    $strResultado .= '<a href="' . SessaoSEI::getInstance()->assinarLink('controlador.php?acao=unidade_alterar&acao_origem=' . $_GET['acao'] . '&acao_retorno=' . $_GET['acao'] . '&id_unidade=' . $arrObjUnidadeDTO[$i]->getNumIdUnidade() . '&sigla=' . $arrObjUnidadeDTO[$i]->getStrSigla()) . '" tabindex="' . PaginaSEI::getInstance()->getProxTabTabela() . '"><img src="imagens/alterar.gif" title="Alterar Unidade" alt="Alterar Unidade" class="infraImg" /></a>&nbsp;';
            //}
            //if ($bolAcaoDesativar) {
            //    $strResultado .= '<a href="#ID-' . $arrObjUnidadeDTO[$i]->getNumIdUnidade() . '"  onclick="acaoDesativar(\'' . $arrObjUnidadeDTO[$i]->getNumIdUnidade() . '\',\'' . $arrObjUnidadeDTO[$i]->getStrSigla() . '\');" tabindex="' . PaginaSEI::getInstance()->getProxTabTabela() . '"><img src="imagens/desativar.gif" title="Desativar Unidade" alt="Desativar Unidade" class="infraImg" /></a>&nbsp;';
            //}
            //if ($bolAcaoReativar) {
            //    $strResultado .= '<a href="#ID-' . $arrObjUnidadeDTO[$i]->getNumIdUnidade() . '"  onclick="acaoReativar(\'' . $arrObjUnidadeDTO[$i]->getNumIdUnidade() . '\',\'' . $arrObjUnidadeDTO[$i]->getStrSigla() . '\');" tabindex="' . PaginaSEI::getInstance()->getProxTabTabela() . '"><img src="imagens/reativar.gif" title="Reativar Unidade" alt="Reativar Unidade" class="infraImg" /></a>&nbsp;';
            //}
            //if ($bolAcaoExcluir) {
            //    $strResultado .= '<a href="#ID-' . $arrObjUnidadeDTO[$i]->getNumIdUnidade() . '"  onclick="acaoExcluir(\'' . $arrObjUnidadeDTO[$i]->getNumIdUnidade() . '\',\'' . $arrObjUnidadeDTO[$i]->getStrSigla() . '\');" tabindex="' . PaginaSEI::getInstance()->getProxTabTabela() . '"><img src="imagens/excluir.gif" title="Excluir Unidade" alt="Excluir Unidade" class="infraImg" /></a>&nbsp;';
            //}

            $strResultado .= '</td></tr>' . "\n";
//            }
        }
        $strResultado .= '</table>';
    }
    if ($_GET['acao'] == 'md_cor_unidade_selecionar_todas') {
        $arrComandos[] = '<button type="button" accesskey="C" id="btnFecharSelecao" value="Fechar" onclick="window.close();" class="infraButton">Fe<span class="infraTeclaAtalho">c</span>har</button>';
    } else {
        $arrComandos[] = '<button type="button" accesskey="F" id="btnFechar" value="Fechar" onclick="location.href=\'' . SessaoSEI::getInstance()->assinarLink('controlador.php?acao=' . PaginaSEI::getInstance()->getAcaoRetorno() . '&acao_origem=' . $_GET['acao']) . '\'" class="infraButton">Fe<span class="infraTeclaAtalho">c</span>har</button>';
    }

    $strItensSelOrgao = OrgaoINT::montarSelectSiglaRI1358('', 'Todos', $numIdOrgao);
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

function inicializar(){
if ('<?= $_GET['acao'] ?>'=='md_cor_unidade_selecionar_todas'){
infraReceberSelecao();
document.getElementById('btnFecharSelecao').focus();
}else{
document.getElementById('btnFechar').focus();
}

infraEfeitoTabelas();
}

<?
/*
  if ($bolAcaoDesativar) {
  function acaoDesativar(id,desc){
  if (confirm("Confirma desativa��o da Unidade \""+desc+"\"?")){
  document.getElementById('hdnInfraItemId').value=id;
  document.getElementById('frmUnidadeLista').action='<?= $strLinkDesativar ?>';
  document.getElementById('frmUnidadeLista').submit();
  }
  }

  function acaoDesativacaoMultipla(){
  if (document.getElementById('hdnInfraItensSelecionados').value==''){
  alert('Nenhuma Unidade selecionada.');
  return;
  }
  if (confirm("Confirma desativa��o das Unidades selecionadas?")){
  document.getElementById('hdnInfraItemId').value='';
  document.getElementById('frmUnidadeLista').action='<?= $strLinkDesativar ?>';
  document.getElementById('frmUnidadeLista').submit();
  }
  }
  }
  if ($bolAcaoReativar) { ?>
  function acaoReativar(id,desc){
  if (confirm("Confirma reativa��o da Unidade \""+desc+"\"?")){
  document.getElementById('hdnInfraItemId').value=id;
  document.getElementById('frmUnidadeLista').action='<?= $strLinkReativar ?>';
  document.getElementById('frmUnidadeLista').submit();
  }
  }

  function acaoReativacaoMultipla(){
  if (document.getElementById('hdnInfraItensSelecionados').value==''){
  alert('Nenhuma Unidade selecionada.');
  return;
  }
  if (confirm("Confirma reativa��o das Unidades selecionadas?")){
  document.getElementById('hdnInfraItemId').value='';
  document.getElementById('frmUnidadeLista').action='<?= $strLinkReativar ?>';
  document.getElementById('frmUnidadeLista').submit();
  }
  }
  }
  if ($bolAcaoExcluir) { ?>
  function acaoExcluir(id,desc){
  if (confirm("Confirma exclus�o da Unidade \""+desc+"\"?")){
  document.getElementById('hdnInfraItemId').value=id;
  document.getElementById('frmUnidadeLista').action='<?= $strLinkExcluir ?>';
  document.getElementById('frmUnidadeLista').submit();
  }
  }

  function acaoExclusaoMultipla(){
  if (document.getElementById('hdnInfraItensSelecionados').value==''){
  alert('Nenhuma Unidade selecionada.');
  return;
  }
  if (confirm("Confirma exclus�o das Unidades selecionadas?")){
  document.getElementById('hdnInfraItemId').value='';
  document.getElementById('frmUnidadeLista').action='<?= $strLinkExcluir ?>';
  document.getElementById('frmUnidadeLista').submit();
  }
  }
  }
 */
?>

<?
PaginaSEI::getInstance()->fecharJavaScript();
PaginaSEI::getInstance()->fecharHead();
PaginaSEI::getInstance()->abrirBody($strTitulo, 'onload="inicializar();"');
?>
<form id="frmUnidadeLista" method="post" action="<?= SessaoSEI::getInstance()->assinarLink('controlador.php?acao=' . $_GET['acao'] . '&acao_origem=' . $_GET['acao'].$strTermoPesquisa) ?>">
    <?
    PaginaSEI::getInstance()->montarBarraComandosSuperior($arrComandos);
    PaginaSEI::getInstance()->abrirAreaDados('5em');
    ?>

    <div class="row">
        <div class="col-3">
            <div class="form-group">
                <label id="lblOrgao" for="selOrgao" accesskey="o" class="infraLabelOpcional">�rg�<span class="infraTeclaAtalho">o</span>:</label>
                <select id="selOrgao" name="selOrgao" onchange="this.form.submit();" class="infraSelect form-select" tabindex="<?= PaginaSEI::getInstance()->getProxTabDados() ?>">
                    <?= $strItensSelOrgao ?>
                </select>
            </div>
        </div>
        <div class="col-3">
            <div class="form-group">
                <label id="lblSiglaUnidade" for="txtSiglaUnidade" class="infraLabelOpcional">Sigla:</label>
                <input type="text" id="txtSiglaUnidade" name="txtSiglaUnidade" class="infraText form-control"
                    value="<?= PaginaSEI::tratarHTML(PaginaSEI::tratarHTML($strSiglaPesquisa)) ?>" maxlength="15"
                    tabindex="<?= PaginaSEI::getInstance()->getProxTabDados() ?>"/>
            </div>
        </div>
        <div class="col-6">
            <div class="form-group">
                <label id="lblDescricaoUnidade" for="txtDescricaoUnidade" class="infraLabelOpcional">Descri��o:</label>
                <input type="text" id="txtDescricaoUnidade" name="txtDescricaoUnidade" class="infraText form-control"
                    tabindex="<?= PaginaSEI::getInstance()->getProxTabDados() ?>"
                    value="<?= PaginaSEI::tratarHTML($strDescricaoPesquisa) ?>"/>
            </div>
        </div>
    </div>

    <? PaginaSEI::getInstance()->fecharAreaDados(); ?>
    
    <div class="row">
        <div class="col-12">
            <div class="table-responsive">
                <? PaginaSEI::getInstance()->montarAreaTabela($strResultado, $numRegistros); ?>
            </div>
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