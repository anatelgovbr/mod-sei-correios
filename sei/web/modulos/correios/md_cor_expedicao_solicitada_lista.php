<?
/**
* TRIBUNAL REGIONAL FEDERAL DA 4ª REGIÃO
*
* 07/06/2017 - criado por marcelo.cast
*
* Versão do Gerador de Código: 1.40.1
*/

try {
  require_once dirname(__FILE__).'/Sei.php';

  session_start();

  //////////////////////////////////////////////////////////////////////////////
  //InfraDebug::getInstance()->setBolLigado(false);
  //InfraDebug::getInstance()->setBolDebugInfra(true);
  //InfraDebug::getInstance()->limpar();
  //////////////////////////////////////////////////////////////////////////////

  SessaoSEI::getInstance()->validarLink();

  PaginaSEI::getInstance()->prepararSelecao('md_cor_expedicao_solicitada_selecionar');

  SessaoSEI::getInstance()->validarPermissao($_GET['acao']);

  switch($_GET['acao']){
    case 'md_cor_expedicao_solicitada_excluir':
      try{
        $arrStrIds = PaginaSEI::getInstance()->getArrStrItensSelecionados();
        $arrObjMdCorExpedicaoSolicitadaDTO = array();
        for ($i=0;$i<count($arrStrIds);$i++){
          $objMdCorExpedicaoSolicitadaDTO = new MdCorExpedicaoSolicitadaDTO();
          $objMdCorExpedicaoSolicitadaDTO->setNumIdMdCorExpedicaoSolicitada($arrStrIds[$i]);
          $arrObjMdCorExpedicaoSolicitadaDTO[] = $objMdCorExpedicaoSolicitadaDTO;
        }
        $objMdCorExpedicaoSolicitadaRN = new MdCorExpedicaoSolicitadaRN();
        $objMdCorExpedicaoSolicitadaRN->excluir($arrObjMdCorExpedicaoSolicitadaDTO);
        PaginaSEI::getInstance()->adicionarMensagem('Operação realizada com sucesso.');
      }catch(Exception $e){
        PaginaSEI::getInstance()->processarExcecao($e);
      } 
      header('Location: '.SessaoSEI::getInstance()->assinarLink('controlador.php?acao='.$_GET['acao_origem'].'&acao_origem='.$_GET['acao']));
      die;

/* 
    case 'md_cor_expedicao_solicitada_desativar':
      try{
        $arrStrIds = PaginaSEI::getInstance()->getArrStrItensSelecionados();
        $arrObjMdCorExpedicaoSolicitadaDTO = array();
        for ($i=0;$i<count($arrStrIds);$i++){
          $objMdCorExpedicaoSolicitadaDTO = new MdCorExpedicaoSolicitadaDTO();
          $objMdCorExpedicaoSolicitadaDTO->setNumIdMdCorExpedicaoSolicitada($arrStrIds[$i]);
          $arrObjMdCorExpedicaoSolicitadaDTO[] = $objMdCorExpedicaoSolicitadaDTO;
        }
        $objMdCorExpedicaoSolicitadaRN = new MdCorExpedicaoSolicitadaRN();
        $objMdCorExpedicaoSolicitadaRN->desativar($arrObjMdCorExpedicaoSolicitadaDTO);
        PaginaSEI::getInstance()->adicionarMensagem('Operação realizada com sucesso.');
      }catch(Exception $e){
        PaginaSEI::getInstance()->processarExcecao($e);
      } 
      header('Location: '.SessaoSEI::getInstance()->assinarLink('controlador.php?acao='.$_GET['acao_origem'].'&acao_origem='.$_GET['acao']));
      die;

    case 'md_cor_expedicao_solicitada_reativar':
      $strTitulo = 'Reativar ';
      if ($_GET['acao_confirmada']=='sim'){
        try{
          $arrStrIds = PaginaSEI::getInstance()->getArrStrItensSelecionados();
          $arrObjMdCorExpedicaoSolicitadaDTO = array();
          for ($i=0;$i<count($arrStrIds);$i++){
            $objMdCorExpedicaoSolicitadaDTO = new MdCorExpedicaoSolicitadaDTO();
            $objMdCorExpedicaoSolicitadaDTO->setNumIdMdCorExpedicaoSolicitada($arrStrIds[$i]);
            $arrObjMdCorExpedicaoSolicitadaDTO[] = $objMdCorExpedicaoSolicitadaDTO;
          }
          $objMdCorExpedicaoSolicitadaRN = new MdCorExpedicaoSolicitadaRN();
          $objMdCorExpedicaoSolicitadaRN->reativar($arrObjMdCorExpedicaoSolicitadaDTO);
          PaginaSEI::getInstance()->adicionarMensagem('Operação realizada com sucesso.');
        }catch(Exception $e){
          PaginaSEI::getInstance()->processarExcecao($e);
        } 
        header('Location: '.SessaoSEI::getInstance()->assinarLink('controlador.php?acao='.$_GET['acao_origem'].'&acao_origem='.$_GET['acao']));
        die;
      } 
      break;

 */
    case 'md_cor_expedicao_solicitada_selecionar':
      $strTitulo = PaginaSEI::getInstance()->getTituloSelecao('Selecionar ','Selecionar ');

      //Se cadastrou alguem
      if ($_GET['acao_origem']=='md_cor_expedicao_solicitada_cadastrar'){
        if (isset($_GET['id_md_cor_expedicao_solicitada'])){
          PaginaSEI::getInstance()->adicionarSelecionado($_GET['id_md_cor_expedicao_solicitada']);
        }
      }
      break;

    case 'md_cor_expedicao_solicitada_listar':
      $strTitulo = '';
      break;

    default:
      throw new InfraException("Ação '".$_GET['acao']."' não reconhecida.");
  }

  $arrComandos = array();
  if ($_GET['acao'] == 'md_cor_expedicao_solicitada_selecionar'){
    $arrComandos[] = '<button type="button" accesskey="T" id="btnTransportarSelecao" value="Transportar" onclick="infraTransportarSelecao();" class="infraButton"><span class="infraTeclaAtalho">T</span>ransportar</button>';
  }

  /* if ($_GET['acao'] == 'md_cor_expedicao_solicitada_listar' || $_GET['acao'] == 'md_cor_expedicao_solicitada_selecionar'){ */
    $bolAcaoCadastrar = SessaoSEI::getInstance()->verificarPermissao('md_cor_expedicao_solicitada_cadastrar');
    if ($bolAcaoCadastrar){
      $arrComandos[] = '<button type="button" accesskey="N" id="btnNov" value="Nov" onclick="location.href=\''.SessaoSEI::getInstance()->assinarLink('controlador.php?acao=md_cor_expedicao_solicitada_cadastrar&acao_origem='.$_GET['acao'].'&acao_retorno='.$_GET['acao']).'\'" class="infraButton"><span class="infraTeclaAtalho">N</span>ov</button>';
    }
  /* } */

  $objMdCorExpedicaoSolicitadaDTO = new MdCorExpedicaoSolicitadaDTO();
  $objMdCorExpedicaoSolicitadaDTO->retNumIdMdCorExpedicaoSolicitada();
  //$objMdCorExpedicaoSolicitadaDTO->retStrSinNecessitaAr();
  //$objMdCorExpedicaoSolicitadaDTO->retDblIdDocumentoPrincipal();
  //$objMdCorExpedicaoSolicitadaDTO->retNumIdMdCorServicoPostal();
  //$objMdCorExpedicaoSolicitadaDTO->retNumIdUnidade();
  //$objMdCorExpedicaoSolicitadaDTO->retDthDataSolicitacao();
  //$objMdCorExpedicaoSolicitadaDTO->retDthDataExpedicao();
  //$objMdCorExpedicaoSolicitadaDTO->retNumIdUsuarioSolicitante();
  //$objMdCorExpedicaoSolicitadaDTO->retNumIdUsuarioExpAutorizador();
  //$objMdCorExpedicaoSolicitadaDTO->retStrCodigoRastreamento();
/* 
  if ($_GET['acao'] == 'md_cor_expedicao_solicitada_reativar'){
    //Lista somente inativos
    $objMdCorExpedicaoSolicitadaDTO->setBolExclusaoLogica(false);
    $objMdCorExpedicaoSolicitadaDTO->setStrSinAtivo('N');
  }
 */
  PaginaSEI::getInstance()->prepararOrdenacao($objMdCorExpedicaoSolicitadaDTO, 'IdMdCorExpedicaoSolicitada', InfraDTO::$TIPO_ORDENACAO_ASC);
  //PaginaSEI::getInstance()->prepararPaginacao($objMdCorExpedicaoSolicitadaDTO);

  $objMdCorExpedicaoSolicitadaRN = new MdCorExpedicaoSolicitadaRN();
  $arrObjMdCorExpedicaoSolicitadaDTO = $objMdCorExpedicaoSolicitadaRN->listar($objMdCorExpedicaoSolicitadaDTO);

  //PaginaSEI::getInstance()->processarPaginacao($objMdCorExpedicaoSolicitadaDTO);
  $numRegistros = count($arrObjMdCorExpedicaoSolicitadaDTO);

  if ($numRegistros > 0){

    $bolCheck = false;

    if ($_GET['acao']=='md_cor_expedicao_solicitada_selecionar'){
      $bolAcaoReativar = false;
      $bolAcaoConsultar = SessaoSEI::getInstance()->verificarPermissao('md_cor_expedicao_solicitada_consultar');
      $bolAcaoAlterar = SessaoSEI::getInstance()->verificarPermissao('md_cor_expedicao_solicitada_alterar');
      $bolAcaoImprimir = false;
      //$bolAcaoGerarPlanilha = false;
      $bolAcaoExcluir = false;
      $bolAcaoDesativar = false;
      $bolCheck = true;
/*     }else if ($_GET['acao']=='md_cor_expedicao_solicitada_reativar'){
      $bolAcaoReativar = SessaoSEI::getInstance()->verificarPermissao('md_cor_expedicao_solicitada_reativar');
      $bolAcaoConsultar = SessaoSEI::getInstance()->verificarPermissao('md_cor_expedicao_solicitada_consultar');
      $bolAcaoAlterar = false;
      $bolAcaoImprimir = true;
      //$bolAcaoGerarPlanilha = SessaoSEI::getInstance()->verificarPermissao('infra_gerar_planilha_tabela');
      $bolAcaoExcluir = SessaoSEI::getInstance()->verificarPermissao('md_cor_expedicao_solicitada_excluir');
      $bolAcaoDesativar = false;
 */    }else{
      $bolAcaoReativar = false;
      $bolAcaoConsultar = SessaoSEI::getInstance()->verificarPermissao('md_cor_expedicao_solicitada_consultar');
      $bolAcaoAlterar = SessaoSEI::getInstance()->verificarPermissao('md_cor_expedicao_solicitada_alterar');
      $bolAcaoImprimir = true;
      //$bolAcaoGerarPlanilha = SessaoSEI::getInstance()->verificarPermissao('infra_gerar_planilha_tabela');
      $bolAcaoExcluir = SessaoSEI::getInstance()->verificarPermissao('md_cor_expedicao_solicitada_excluir');
      $bolAcaoDesativar = SessaoSEI::getInstance()->verificarPermissao('md_cor_expedicao_solicitada_desativar');
    }

    /* 
    if ($bolAcaoDesativar){
      $bolCheck = true;
      $arrComandos[] = '<button type="button" accesskey="t" id="btnDesativar" value="Desativar" onclick="acaoDesativacaoMultipla();" class="infraButton">Desa<span class="infraTeclaAtalho">t</span>ivar</button>';
      $strLinkDesativar = SessaoSEI::getInstance()->assinarLink('controlador.php?acao=md_cor_expedicao_solicitada_desativar&acao_origem='.$_GET['acao']);
    }

    if ($bolAcaoReativar){
      $bolCheck = true;
      $arrComandos[] = '<button type="button" accesskey="R" id="btnReativar" value="Reativar" onclick="acaoReativacaoMultipla();" class="infraButton"><span class="infraTeclaAtalho">R</span>eativar</button>';
      $strLinkReativar = SessaoSEI::getInstance()->assinarLink('controlador.php?acao=md_cor_expedicao_solicitada_reativar&acao_origem='.$_GET['acao'].'&acao_confirmada=sim');
    }
     */

    if ($bolAcaoExcluir){
      $bolCheck = true;
      $arrComandos[] = '<button type="button" accesskey="E" id="btnExcluir" value="Excluir" onclick="acaoExclusaoMultipla();" class="infraButton"><span class="infraTeclaAtalho">E</span>xcluir</button>';
      $strLinkExcluir = SessaoSEI::getInstance()->assinarLink('controlador.php?acao=md_cor_expedicao_solicitada_excluir&acao_origem='.$_GET['acao']);
    }

    /*
    if ($bolAcaoGerarPlanilha){
      $bolCheck = true;
      $arrComandos[] = '<button type="button" accesskey="P" id="btnGerarPlanilha" value="Gerar Planilha" onclick="infraGerarPlanilhaTabela(\''.SessaoSEI::getInstance()->assinarLink('controlador.php?acao=infra_gerar_planilha_tabela').'\');" class="infraButton">Gerar <span class="infraTeclaAtalho">P</span>lanilha</button>';
    }
    */

    $strResultado = '';

    /* if ($_GET['acao']!='md_cor_expedicao_solicitada_reativar'){ */
      $strSumarioTabela = 'Tabela de .';
      $strCaptionTabela = '';
    /* }else{
      $strSumarioTabela = 'Tabela de  Inativs.';
      $strCaptionTabela = ' Inativs';
    } */

    $strResultado .= '<table width="99%" class="infraTable" summary="'.$strSumarioTabela.'">'."\n";
    $strResultado .= '<caption class="infraCaption">'.PaginaSEI::getInstance()->gerarCaptionTabela($strCaptionTabela,$numRegistros).'</caption>';
    $strResultado .= '<tr>';
    if ($bolCheck) {
      $strResultado .= '<th class="infraTh" width="1%">'.PaginaSEI::getInstance()->getThCheck().'</th>'."\n";
    }
    //$strResultado .= '<th class="infraTh">'.PaginaSEI::getInstance()->getThOrdenacao($objMdCorExpedicaoSolicitadaDTO,'','SinNecessitaAr',$arrObjMdCorExpedicaoSolicitadaDTO).'</th>'."\n";
    //$strResultado .= '<th class="infraTh">'.PaginaSEI::getInstance()->getThOrdenacao($objMdCorExpedicaoSolicitadaDTO,'','IdDocumentoPrincipal',$arrObjMdCorExpedicaoSolicitadaDTO).'</th>'."\n";
    //$strResultado .= '<th class="infraTh">'.PaginaSEI::getInstance()->getThOrdenacao($objMdCorExpedicaoSolicitadaDTO,'','IdMdCorServicoPostal',$arrObjMdCorExpedicaoSolicitadaDTO).'</th>'."\n";
    //$strResultado .= '<th class="infraTh">'.PaginaSEI::getInstance()->getThOrdenacao($objMdCorExpedicaoSolicitadaDTO,'','IdUnidade',$arrObjMdCorExpedicaoSolicitadaDTO).'</th>'."\n";
    //$strResultado .= '<th class="infraTh">'.PaginaSEI::getInstance()->getThOrdenacao($objMdCorExpedicaoSolicitadaDTO,'','DataSolicitacao',$arrObjMdCorExpedicaoSolicitadaDTO).'</th>'."\n";
    //$strResultado .= '<th class="infraTh">'.PaginaSEI::getInstance()->getThOrdenacao($objMdCorExpedicaoSolicitadaDTO,'','DataExpedicao',$arrObjMdCorExpedicaoSolicitadaDTO).'</th>'."\n";
    //$strResultado .= '<th class="infraTh">'.PaginaSEI::getInstance()->getThOrdenacao($objMdCorExpedicaoSolicitadaDTO,'','IdUsuarioSolicitante',$arrObjMdCorExpedicaoSolicitadaDTO).'</th>'."\n";
    //$strResultado .= '<th class="infraTh">'.PaginaSEI::getInstance()->getThOrdenacao($objMdCorExpedicaoSolicitadaDTO,'','IdUsuarioExpAutorizador',$arrObjMdCorExpedicaoSolicitadaDTO).'</th>'."\n";
    //$strResultado .= '<th class="infraTh">'.PaginaSEI::getInstance()->getThOrdenacao($objMdCorExpedicaoSolicitadaDTO,'','CodigoRastreamento',$arrObjMdCorExpedicaoSolicitadaDTO).'</th>'."\n";
    $strResultado .= '<th class="infraTh">Ações</th>'."\n";
    $strResultado .= '</tr>'."\n";
    $strCssTr='';
    for($i = 0;$i < $numRegistros; $i++){

      $strCssTr = ($strCssTr=='<tr class="infraTrClara">')?'<tr class="infraTrEscura">':'<tr class="infraTrClara">';
      $strResultado .= $strCssTr;

      if ($bolCheck){
        $strResultado .= '<td valign="top">'.PaginaSEI::getInstance()->getTrCheck($i,$arrObjMdCorExpedicaoSolicitadaDTO[$i]->getNumIdMdCorExpedicaoSolicitada(),$arrObjMdCorExpedicaoSolicitadaDTO[$i]->getNumIdMdCorExpedicaoSolicitada()).'</td>';
      }
      //$strResultado .= '<td>'.PaginaSEI::tratarHTML($arrObjMdCorExpedicaoSolicitadaDTO[$i]->getStrSinNecessitaAr()).'</td>';
      //$strResultado .= '<td>'.PaginaSEI::tratarHTML($arrObjMdCorExpedicaoSolicitadaDTO[$i]->getDblIdDocumentoPrincipal()).'</td>';
      //$strResultado .= '<td>'.PaginaSEI::tratarHTML($arrObjMdCorExpedicaoSolicitadaDTO[$i]->getNumIdMdCorServicoPostal()).'</td>';
      //$strResultado .= '<td>'.PaginaSEI::tratarHTML($arrObjMdCorExpedicaoSolicitadaDTO[$i]->getNumIdUnidade()).'</td>';
      //$strResultado .= '<td>'.PaginaSEI::tratarHTML($arrObjMdCorExpedicaoSolicitadaDTO[$i]->getDthDataSolicitacao()).'</td>';
      //$strResultado .= '<td>'.PaginaSEI::tratarHTML($arrObjMdCorExpedicaoSolicitadaDTO[$i]->getDthDataExpedicao()).'</td>';
      //$strResultado .= '<td>'.PaginaSEI::tratarHTML($arrObjMdCorExpedicaoSolicitadaDTO[$i]->getNumIdUsuarioSolicitante()).'</td>';
      //$strResultado .= '<td>'.PaginaSEI::tratarHTML($arrObjMdCorExpedicaoSolicitadaDTO[$i]->getNumIdUsuarioExpAutorizador()).'</td>';
      //$strResultado .= '<td>'.PaginaSEI::tratarHTML($arrObjMdCorExpedicaoSolicitadaDTO[$i]->getStrCodigoRastreamento()).'</td>';
      $strResultado .= '<td align="center">';

      $strResultado .= PaginaSEI::getInstance()->getAcaoTransportarItem($i,$arrObjMdCorExpedicaoSolicitadaDTO[$i]->getNumIdMdCorExpedicaoSolicitada());

      if ($bolAcaoConsultar){
        $strResultado .= '<a href="'.SessaoSEI::getInstance()->assinarLink('controlador.php?acao=md_cor_expedicao_solicitada_consultar&acao_origem='.$_GET['acao'].'&acao_retorno='.$_GET['acao'].'&id_md_cor_expedicao_solicitada='.$arrObjMdCorExpedicaoSolicitadaDTO[$i]->getNumIdMdCorExpedicaoSolicitada()).'" tabindex="'.PaginaSEI::getInstance()->getProxTabTabela().'"><img src="'.PaginaSEI::getInstance()->getDiretorioImagensGlobal().'/consultar.gif" title="Consultar " alt="Consultar " class="infraImg" /></a>&nbsp;';
      }

      if ($bolAcaoAlterar){
        $strResultado .= '<a href="'.SessaoSEI::getInstance()->assinarLink('controlador.php?acao=md_cor_expedicao_solicitada_alterar&acao_origem='.$_GET['acao'].'&acao_retorno='.$_GET['acao'].'&id_md_cor_expedicao_solicitada='.$arrObjMdCorExpedicaoSolicitadaDTO[$i]->getNumIdMdCorExpedicaoSolicitada()).'" tabindex="'.PaginaSEI::getInstance()->getProxTabTabela().'"><img src="'.PaginaSEI::getInstance()->getDiretorioImagensGlobal().'/alterar.gif" title="Alterar " alt="Alterar " class="infraImg" /></a>&nbsp;';
      }

      if ($bolAcaoDesativar || $bolAcaoReativar || $bolAcaoExcluir){
        $strId = $arrObjMdCorExpedicaoSolicitadaDTO[$i]->getNumIdMdCorExpedicaoSolicitada();
        $strDescricao = PaginaSEI::getInstance()->formatarParametrosJavaScript(PaginaSEI::tratarHTML($arrObjMdCorExpedicaoSolicitadaDTO[$i]->getNumIdMdCorExpedicaoSolicitada()));
      }
/* 
      if ($bolAcaoDesativar){
        $strResultado .= '<a href="'.PaginaSEI::getInstance()->montarAncora($strId).'" onclick="acaoDesativar(\''.$strId.'\',\''.$strDescricao.'\');" tabindex="'.PaginaSEI::getInstance()->getProxTabTabela().'"><img src="'.PaginaSEI::getInstance()->getDiretorioImagensGlobal().'/desativar.gif" title="Desativar " alt="Desativar " class="infraImg" /></a>&nbsp;';
      }

      if ($bolAcaoReativar){
        $strResultado .= '<a href="'.PaginaSEI::getInstance()->montarAncora($strId).'" onclick="acaoReativar(\''.$strId.'\',\''.$strDescricao.'\');" tabindex="'.PaginaSEI::getInstance()->getProxTabTabela().'"><img src="'.PaginaSEI::getInstance()->getDiretorioImagensGlobal().'/reativar.gif" title="Reativar " alt="Reativar " class="infraImg" /></a>&nbsp;';
      }
 */

      if ($bolAcaoExcluir){
        $strResultado .= '<a href="'.PaginaSEI::getInstance()->montarAncora($strId).'" onclick="acaoExcluir(\''.$strId.'\',\''.$strDescricao.'\');" tabindex="'.PaginaSEI::getInstance()->getProxTabTabela().'"><img src="'.PaginaSEI::getInstance()->getDiretorioImagensGlobal().'/excluir.gif" title="Excluir " alt="Excluir " class="infraImg" /></a>&nbsp;';
      }

      $strResultado .= '</td></tr>'."\n";
    }
    $strResultado .= '</table>';
  }
  if ($_GET['acao'] == 'md_cor_expedicao_solicitada_selecionar'){
    $arrComandos[] = '<button type="button" accesskey="F" id="btnFecharSelecao" value="Fechar" onclick="window.close();" class="infraButton">Fe<span class="infraTeclaAtalho">c</span>har</button>';
  }else{
    $arrComandos[] = '<button type="button" accesskey="F" id="btnFechar" value="Fechar" onclick="location.href=\''.SessaoSEI::getInstance()->assinarLink('controlador.php?acao='.PaginaSEI::getInstance()->getAcaoRetorno().'&acao_origem='.$_GET['acao']).'\'" class="infraButton">Fe<span class="infraTeclaAtalho">c</span>har</button>';
  }

}catch(Exception $e){
  PaginaSEI::getInstance()->processarExcecao($e);
} 

PaginaSEI::getInstance()->montarDocType();
PaginaSEI::getInstance()->abrirHtml();
PaginaSEI::getInstance()->abrirHead();
PaginaSEI::getInstance()->montarMeta();
PaginaSEI::getInstance()->montarTitle(PaginaSEI::getInstance()->getStrNomeSistema().' - '.$strTitulo);
PaginaSEI::getInstance()->montarStyle();
PaginaSEI::getInstance()->abrirStyle();
?>
<?if(0){?><style><?}?>
<?if(0){?></style><?}?>
<?
PaginaSEI::getInstance()->fecharStyle();
PaginaSEI::getInstance()->montarJavaScript();
PaginaSEI::getInstance()->abrirJavaScript();
?>
<?if(0){?><script type="text/javascript"><?}?>

function inicializar(){
  if ('<?=$_GET['acao']?>'=='md_cor_expedicao_solicitada_selecionar'){
    infraReceberSelecao();
    document.getElementById('btnFecharSelecao').focus();
  }else{
    document.getElementById('btnFechar').focus();
  }
  infraEfeitoTabelas();
}

<? if ($bolAcaoDesativar){ ?>
function acaoDesativar(id,desc){
  if (confirm("Confirma desativação d  \""+desc+"\"?")){
    document.getElementById('hdnInfraItemId').value=id;
    document.getElementById('frmMdCorExpedicaoSolicitadaLista').action='<?=$strLinkDesativar?>';
    document.getElementById('frmMdCorExpedicaoSolicitadaLista').submit();
  }
}

function acaoDesativacaoMultipla(){
  if (document.getElementById('hdnInfraItensSelecionados').value==''){
    alert('Nenhuma  selecionada.');
    return;
  }
  if (confirm("Confirma desativação ds  selecionads?")){
    document.getElementById('hdnInfraItemId').value='';
    document.getElementById('frmMdCorExpedicaoSolicitadaLista').action='<?=$strLinkDesativar?>';
    document.getElementById('frmMdCorExpedicaoSolicitadaLista').submit();
  }
}
<? } ?>

<? if ($bolAcaoReativar){ ?>
function acaoReativar(id,desc){
  if (confirm("Confirma reativação d  \""+desc+"\"?")){
    document.getElementById('hdnInfraItemId').value=id;
    document.getElementById('frmMdCorExpedicaoSolicitadaLista').action='<?=$strLinkReativar?>';
    document.getElementById('frmMdCorExpedicaoSolicitadaLista').submit();
  }
}

function acaoReativacaoMultipla(){
  if (document.getElementById('hdnInfraItensSelecionados').value==''){
    alert('Nenhuma  selecionada.');
    return;
  }
  if (confirm("Confirma reativação ds  selecionads?")){
    document.getElementById('hdnInfraItemId').value='';
    document.getElementById('frmMdCorExpedicaoSolicitadaLista').action='<?=$strLinkReativar?>';
    document.getElementById('frmMdCorExpedicaoSolicitadaLista').submit();
  }
}
<? } ?>

<? if ($bolAcaoExcluir){ ?>
function acaoExcluir(id,desc){
  if (confirm("Confirma exclusão d  \""+desc+"\"?")){
    document.getElementById('hdnInfraItemId').value=id;
    document.getElementById('frmMdCorExpedicaoSolicitadaLista').action='<?=$strLinkExcluir?>';
    document.getElementById('frmMdCorExpedicaoSolicitadaLista').submit();
  }
}

function acaoExclusaoMultipla(){
  if (document.getElementById('hdnInfraItensSelecionados').value==''){
    alert('Nenhuma  selecionada.');
    return;
  }
  if (confirm("Confirma exclusão ds  selecionads?")){
    document.getElementById('hdnInfraItemId').value='';
    document.getElementById('frmMdCorExpedicaoSolicitadaLista').action='<?=$strLinkExcluir?>';
    document.getElementById('frmMdCorExpedicaoSolicitadaLista').submit();
  }
}
<? } ?>

<?if(0){?></script><?}?>
<?
PaginaSEI::getInstance()->fecharJavaScript();
PaginaSEI::getInstance()->fecharHead();
PaginaSEI::getInstance()->abrirBody($strTitulo,'onload="inicializar();"');
?>
<form id="frmMdCorExpedicaoSolicitadaLista" method="post" action="<?=SessaoSEI::getInstance()->assinarLink('controlador.php?acao='.$_GET['acao'].'&acao_origem='.$_GET['acao'])?>">
  <?
  PaginaSEI::getInstance()->montarBarraComandosSuperior($arrComandos);
  //PaginaSEI::getInstance()->abrirAreaDados('5em');
  //PaginaSEI::getInstance()->fecharAreaDados();
  PaginaSEI::getInstance()->montarAreaTabela($strResultado,$numRegistros);
  //PaginaSEI::getInstance()->montarAreaDebug();
  PaginaSEI::getInstance()->montarBarraComandosInferior($arrComandos);
  ?>
</form>
<?
PaginaSEI::getInstance()->fecharBody();
PaginaSEI::getInstance()->fecharHtml();
?>