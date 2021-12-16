<?
/**
* TRIBUNAL REGIONAL FEDERAL DA 4ª REGIÃO
*
* 07/06/2017 - criado por marcelo.cast
*
* Versão do Gerador de Código: 1.40.1
*/

try {
  require_once dirname(__FILE__).'/SEI.php';

  session_start();

  //////////////////////////////////////////////////////////////////////////////
  //InfraDebug::getInstance()->setBolLigado(false);
  //InfraDebug::getInstance()->setBolDebugInfra(true);
  //InfraDebug::getInstance()->limpar();
  //////////////////////////////////////////////////////////////////////////////

  SessaoSEI::getInstance()->validarLink();

  PaginaSEI::getInstance()->prepararSelecao('md_cor_expedicao_andamento_selecionar');

  SessaoSEI::getInstance()->validarPermissao($_GET['acao']);

  switch($_GET['acao']){
    case 'md_cor_expedicao_andamento_excluir':
      try{
        $arrStrIds = PaginaSEI::getInstance()->getArrStrItensSelecionados();
        $arrObjMdCorExpedicaoAndamentoDTO = array();
        for ($i=0;$i<count($arrStrIds);$i++){
          $objMdCorExpedicaoAndamentoDTO = new MdCorExpedicaoAndamentoDTO();
          $objMdCorExpedicaoAndamentoDTO->setNumIdMdCorExpedicaoAndamento($arrStrIds[$i]);
          $arrObjMdCorExpedicaoAndamentoDTO[] = $objMdCorExpedicaoAndamentoDTO;
        }
        $objMdCorExpedicaoAndamentoRN = new MdCorExpedicaoAndamentoRN();
        $objMdCorExpedicaoAndamentoRN->excluir($arrObjMdCorExpedicaoAndamentoDTO);
        PaginaSEI::getInstance()->adicionarMensagem('Operação realizada com sucesso.');
      }catch(Exception $e){
        PaginaSEI::getInstance()->processarExcecao($e);
      } 
      header('Location: '.SessaoSEI::getInstance()->assinarLink('controlador.php?acao='.$_GET['acao_origem'].'&acao_origem='.$_GET['acao']));
      die;

/* 
    case 'md_cor_expedicao_andamento_desativar':
      try{
        $arrStrIds = PaginaSEI::getInstance()->getArrStrItensSelecionados();
        $arrObjMdCorExpedicaoAndamentoDTO = array();
        for ($i=0;$i<count($arrStrIds);$i++){
          $objMdCorExpedicaoAndamentoDTO = new MdCorExpedicaoAndamentoDTO();
          $objMdCorExpedicaoAndamentoDTO->setNumIdMdCorExpedicaoAndamento($arrStrIds[$i]);
          $arrObjMdCorExpedicaoAndamentoDTO[] = $objMdCorExpedicaoAndamentoDTO;
        }
        $objMdCorExpedicaoAndamentoRN = new MdCorExpedicaoAndamentoRN();
        $objMdCorExpedicaoAndamentoRN->desativar($arrObjMdCorExpedicaoAndamentoDTO);
        PaginaSEI::getInstance()->adicionarMensagem('Operação realizada com sucesso.');
      }catch(Exception $e){
        PaginaSEI::getInstance()->processarExcecao($e);
      } 
      header('Location: '.SessaoSEI::getInstance()->assinarLink('controlador.php?acao='.$_GET['acao_origem'].'&acao_origem='.$_GET['acao']));
      die;

    case 'md_cor_expedicao_andamento_reativar':
      $strTitulo = 'Reativar ';
      if ($_GET['acao_confirmada']=='sim'){
        try{
          $arrStrIds = PaginaSEI::getInstance()->getArrStrItensSelecionados();
          $arrObjMdCorExpedicaoAndamentoDTO = array();
          for ($i=0;$i<count($arrStrIds);$i++){
            $objMdCorExpedicaoAndamentoDTO = new MdCorExpedicaoAndamentoDTO();
            $objMdCorExpedicaoAndamentoDTO->setNumIdMdCorExpedicaoAndamento($arrStrIds[$i]);
            $arrObjMdCorExpedicaoAndamentoDTO[] = $objMdCorExpedicaoAndamentoDTO;
          }
          $objMdCorExpedicaoAndamentoRN = new MdCorExpedicaoAndamentoRN();
          $objMdCorExpedicaoAndamentoRN->reativar($arrObjMdCorExpedicaoAndamentoDTO);
          PaginaSEI::getInstance()->adicionarMensagem('Operação realizada com sucesso.');
        }catch(Exception $e){
          PaginaSEI::getInstance()->processarExcecao($e);
        } 
        header('Location: '.SessaoSEI::getInstance()->assinarLink('controlador.php?acao='.$_GET['acao_origem'].'&acao_origem='.$_GET['acao']));
        die;
      } 
      break;

 */
    case 'md_cor_expedicao_andamento_selecionar':
      $strTitulo = PaginaSEI::getInstance()->getTituloSelecao('Selecionar ','Selecionar ');

      //Se cadastrou alguem
      if ($_GET['acao_origem']=='md_cor_expedicao_andamento_cadastrar'){
        if (isset($_GET['id_md_cor_expedicao_andamento'])){
          PaginaSEI::getInstance()->adicionarSelecionado($_GET['id_md_cor_expedicao_andamento']);
        }
      }
      break;

    case 'md_cor_expedicao_andamento_listar':
      $strTitulo = '';
      break;

    default:
      throw new InfraException("Ação '".$_GET['acao']."' não reconhecida.");
  }

  $arrComandos = array();
  if ($_GET['acao'] == 'md_cor_expedicao_andamento_selecionar'){
    $arrComandos[] = '<button type="button" accesskey="T" id="btnTransportarSelecao" value="Transportar" onclick="infraTransportarSelecao();" class="infraButton"><span class="infraTeclaAtalho">T</span>ransportar</button>';
  }

  /* if ($_GET['acao'] == 'md_cor_expedicao_andamento_listar' || $_GET['acao'] == 'md_cor_expedicao_andamento_selecionar'){ */
    $bolAcaoCadastrar = SessaoSEI::getInstance()->verificarPermissao('md_cor_expedicao_andamento_cadastrar');
    if ($bolAcaoCadastrar){
      $arrComandos[] = '<button type="button" accesskey="N" id="btnNov" value="Nov" onclick="location.href=\''.SessaoSEI::getInstance()->assinarLink('controlador.php?acao=md_cor_expedicao_andamento_cadastrar&acao_origem='.$_GET['acao'].'&acao_retorno='.$_GET['acao']).'\'" class="infraButton"><span class="infraTeclaAtalho">N</span>ov</button>';
    }
  /* } */

  $objMdCorExpedicaoAndamentoDTO = new MdCorExpedicaoAndamentoDTO();
  $objMdCorExpedicaoAndamentoDTO->retNumIdMdCorExpedicaoAndamento();
  //$objMdCorExpedicaoAndamentoDTO->retDthDataHora();
  //$objMdCorExpedicaoAndamentoDTO->retStrDescricao();
  //$objMdCorExpedicaoAndamentoDTO->retNumIdMdCorExpedicaoSolicitada();
  //$objMdCorExpedicaoAndamentoDTO->retStrDetalhe();
  //$objMdCorExpedicaoAndamentoDTO->retStrStatus();
  //$objMdCorExpedicaoAndamentoDTO->retStrLocal();
  //$objMdCorExpedicaoAndamentoDTO->retStrCodigoCep();
  //$objMdCorExpedicaoAndamentoDTO->retStrCidade();
  //$objMdCorExpedicaoAndamentoDTO->retStrUf();
  //$objMdCorExpedicaoAndamentoDTO->retStrVersaoSroXml();
  //$objMdCorExpedicaoAndamentoDTO->retStrSiglaObjeto();
  //$objMdCorExpedicaoAndamentoDTO->retStrNomeObjeto();
  //$objMdCorExpedicaoAndamentoDTO->retStrCategoriaObjeto();
/* 
  if ($_GET['acao'] == 'md_cor_expedicao_andamento_reativar'){
    //Lista somente inativos
    $objMdCorExpedicaoAndamentoDTO->setBolExclusaoLogica(false);
    $objMdCorExpedicaoAndamentoDTO->setStrSinAtivo('N');
  }
 */
  PaginaSEI::getInstance()->prepararOrdenacao($objMdCorExpedicaoAndamentoDTO, 'IdMdCorExpedicaoAndamento', InfraDTO::$TIPO_ORDENACAO_ASC);
  //PaginaSEI::getInstance()->prepararPaginacao($objMdCorExpedicaoAndamentoDTO);

  $objMdCorExpedicaoAndamentoRN = new MdCorExpedicaoAndamentoRN();
  $arrObjMdCorExpedicaoAndamentoDTO = $objMdCorExpedicaoAndamentoRN->listar($objMdCorExpedicaoAndamentoDTO);

  //PaginaSEI::getInstance()->processarPaginacao($objMdCorExpedicaoAndamentoDTO);
  $numRegistros = count($arrObjMdCorExpedicaoAndamentoDTO);

  if ($numRegistros > 0){

    $bolCheck = false;

    if ($_GET['acao']=='md_cor_expedicao_andamento_selecionar'){
      $bolAcaoReativar = false;
      $bolAcaoConsultar = SessaoSEI::getInstance()->verificarPermissao('md_cor_expedicao_andamento_consultar');
      $bolAcaoAlterar = SessaoSEI::getInstance()->verificarPermissao('md_cor_expedicao_andamento_alterar');
      $bolAcaoImprimir = false;
      //$bolAcaoGerarPlanilha = false;
      $bolAcaoExcluir = false;
      $bolAcaoDesativar = false;
      $bolCheck = true;
/*     }else if ($_GET['acao']=='md_cor_expedicao_andamento_reativar'){
      $bolAcaoReativar = SessaoSEI::getInstance()->verificarPermissao('md_cor_expedicao_andamento_reativar');
      $bolAcaoConsultar = SessaoSEI::getInstance()->verificarPermissao('md_cor_expedicao_andamento_consultar');
      $bolAcaoAlterar = false;
      $bolAcaoImprimir = true;
      //$bolAcaoGerarPlanilha = SessaoSEI::getInstance()->verificarPermissao('infra_gerar_planilha_tabela');
      $bolAcaoExcluir = SessaoSEI::getInstance()->verificarPermissao('md_cor_expedicao_andamento_excluir');
      $bolAcaoDesativar = false;
 */    }else{
      $bolAcaoReativar = false;
      $bolAcaoConsultar = SessaoSEI::getInstance()->verificarPermissao('md_cor_expedicao_andamento_consultar');
      $bolAcaoAlterar = SessaoSEI::getInstance()->verificarPermissao('md_cor_expedicao_andamento_alterar');
      $bolAcaoImprimir = true;
      //$bolAcaoGerarPlanilha = SessaoSEI::getInstance()->verificarPermissao('infra_gerar_planilha_tabela');
      $bolAcaoExcluir = SessaoSEI::getInstance()->verificarPermissao('md_cor_expedicao_andamento_excluir');
      $bolAcaoDesativar = SessaoSEI::getInstance()->verificarPermissao('md_cor_expedicao_andamento_desativar');
    }

    /* 
    if ($bolAcaoDesativar){
      $bolCheck = true;
      $arrComandos[] = '<button type="button" accesskey="t" id="btnDesativar" value="Desativar" onclick="acaoDesativacaoMultipla();" class="infraButton">Desa<span class="infraTeclaAtalho">t</span>ivar</button>';
      $strLinkDesativar = SessaoSEI::getInstance()->assinarLink('controlador.php?acao=md_cor_expedicao_andamento_desativar&acao_origem='.$_GET['acao']);
    }

    if ($bolAcaoReativar){
      $bolCheck = true;
      $arrComandos[] = '<button type="button" accesskey="R" id="btnReativar" value="Reativar" onclick="acaoReativacaoMultipla();" class="infraButton"><span class="infraTeclaAtalho">R</span>eativar</button>';
      $strLinkReativar = SessaoSEI::getInstance()->assinarLink('controlador.php?acao=md_cor_expedicao_andamento_reativar&acao_origem='.$_GET['acao'].'&acao_confirmada=sim');
    }
     */

    if ($bolAcaoExcluir){
      $bolCheck = true;
      $arrComandos[] = '<button type="button" accesskey="E" id="btnExcluir" value="Excluir" onclick="acaoExclusaoMultipla();" class="infraButton"><span class="infraTeclaAtalho">E</span>xcluir</button>';
      $strLinkExcluir = SessaoSEI::getInstance()->assinarLink('controlador.php?acao=md_cor_expedicao_andamento_excluir&acao_origem='.$_GET['acao']);
    }

    /*
    if ($bolAcaoGerarPlanilha){
      $bolCheck = true;
      $arrComandos[] = '<button type="button" accesskey="P" id="btnGerarPlanilha" value="Gerar Planilha" onclick="infraGerarPlanilhaTabela(\''.SessaoSEI::getInstance()->assinarLink('controlador.php?acao=infra_gerar_planilha_tabela').'\');" class="infraButton">Gerar <span class="infraTeclaAtalho">P</span>lanilha</button>';
    }
    */

    $strResultado = '';

    /* if ($_GET['acao']!='md_cor_expedicao_andamento_reativar'){ */
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
    //$strResultado .= '<th class="infraTh">'.PaginaSEI::getInstance()->getThOrdenacao($objMdCorExpedicaoAndamentoDTO,'','DataHora',$arrObjMdCorExpedicaoAndamentoDTO).'</th>'."\n";
    //$strResultado .= '<th class="infraTh">'.PaginaSEI::getInstance()->getThOrdenacao($objMdCorExpedicaoAndamentoDTO,'','Descricao',$arrObjMdCorExpedicaoAndamentoDTO).'</th>'."\n";
    //$strResultado .= '<th class="infraTh">'.PaginaSEI::getInstance()->getThOrdenacao($objMdCorExpedicaoAndamentoDTO,'','IdMdCorExpedicaoSolicitada',$arrObjMdCorExpedicaoAndamentoDTO).'</th>'."\n";
    //$strResultado .= '<th class="infraTh">'.PaginaSEI::getInstance()->getThOrdenacao($objMdCorExpedicaoAndamentoDTO,'','Detalhe',$arrObjMdCorExpedicaoAndamentoDTO).'</th>'."\n";
    //$strResultado .= '<th class="infraTh">'.PaginaSEI::getInstance()->getThOrdenacao($objMdCorExpedicaoAndamentoDTO,'','Status',$arrObjMdCorExpedicaoAndamentoDTO).'</th>'."\n";
    //$strResultado .= '<th class="infraTh">'.PaginaSEI::getInstance()->getThOrdenacao($objMdCorExpedicaoAndamentoDTO,'','Local',$arrObjMdCorExpedicaoAndamentoDTO).'</th>'."\n";
    //$strResultado .= '<th class="infraTh">'.PaginaSEI::getInstance()->getThOrdenacao($objMdCorExpedicaoAndamentoDTO,'','CodigoCep',$arrObjMdCorExpedicaoAndamentoDTO).'</th>'."\n";
    //$strResultado .= '<th class="infraTh">'.PaginaSEI::getInstance()->getThOrdenacao($objMdCorExpedicaoAndamentoDTO,'','Cidade',$arrObjMdCorExpedicaoAndamentoDTO).'</th>'."\n";
    //$strResultado .= '<th class="infraTh">'.PaginaSEI::getInstance()->getThOrdenacao($objMdCorExpedicaoAndamentoDTO,'','Uf',$arrObjMdCorExpedicaoAndamentoDTO).'</th>'."\n";
    //$strResultado .= '<th class="infraTh">'.PaginaSEI::getInstance()->getThOrdenacao($objMdCorExpedicaoAndamentoDTO,'','VersaoSroXml',$arrObjMdCorExpedicaoAndamentoDTO).'</th>'."\n";
    //$strResultado .= '<th class="infraTh">'.PaginaSEI::getInstance()->getThOrdenacao($objMdCorExpedicaoAndamentoDTO,'','SiglaObjeto',$arrObjMdCorExpedicaoAndamentoDTO).'</th>'."\n";
    //$strResultado .= '<th class="infraTh">'.PaginaSEI::getInstance()->getThOrdenacao($objMdCorExpedicaoAndamentoDTO,'','NomeObjeto',$arrObjMdCorExpedicaoAndamentoDTO).'</th>'."\n";
    //$strResultado .= '<th class="infraTh">'.PaginaSEI::getInstance()->getThOrdenacao($objMdCorExpedicaoAndamentoDTO,'','CategoriaObjeto',$arrObjMdCorExpedicaoAndamentoDTO).'</th>'."\n";
    $strResultado .= '<th class="infraTh">Ações</th>'."\n";
    $strResultado .= '</tr>'."\n";
    $strCssTr='';
    for($i = 0;$i < $numRegistros; $i++){

      $strCssTr = ($strCssTr=='<tr class="infraTrClara">')?'<tr class="infraTrEscura">':'<tr class="infraTrClara">';
      $strResultado .= $strCssTr;

      if ($bolCheck){
        $strResultado .= '<td valign="top">'.PaginaSEI::getInstance()->getTrCheck($i,$arrObjMdCorExpedicaoAndamentoDTO[$i]->getNumIdMdCorExpedicaoAndamento(),$arrObjMdCorExpedicaoAndamentoDTO[$i]->getNumIdMdCorExpedicaoAndamento()).'</td>';
      }
      //$strResultado .= '<td>'.PaginaSEI::tratarHTML($arrObjMdCorExpedicaoAndamentoDTO[$i]->getDthDataHora()).'</td>';
      //$strResultado .= '<td>'.PaginaSEI::tratarHTML($arrObjMdCorExpedicaoAndamentoDTO[$i]->getStrDescricao()).'</td>';
      //$strResultado .= '<td>'.PaginaSEI::tratarHTML($arrObjMdCorExpedicaoAndamentoDTO[$i]->getNumIdMdCorExpedicaoSolicitada()).'</td>';
      //$strResultado .= '<td>'.PaginaSEI::tratarHTML($arrObjMdCorExpedicaoAndamentoDTO[$i]->getStrDetalhe()).'</td>';
      //$strResultado .= '<td>'.PaginaSEI::tratarHTML($arrObjMdCorExpedicaoAndamentoDTO[$i]->getStrStatus()).'</td>';
      //$strResultado .= '<td>'.PaginaSEI::tratarHTML($arrObjMdCorExpedicaoAndamentoDTO[$i]->getStrLocal()).'</td>';
      //$strResultado .= '<td>'.PaginaSEI::tratarHTML($arrObjMdCorExpedicaoAndamentoDTO[$i]->getStrCodigoCep()).'</td>';
      //$strResultado .= '<td>'.PaginaSEI::tratarHTML($arrObjMdCorExpedicaoAndamentoDTO[$i]->getStrCidade()).'</td>';
      //$strResultado .= '<td>'.PaginaSEI::tratarHTML($arrObjMdCorExpedicaoAndamentoDTO[$i]->getStrUf()).'</td>';
      //$strResultado .= '<td>'.PaginaSEI::tratarHTML($arrObjMdCorExpedicaoAndamentoDTO[$i]->getStrVersaoSroXml()).'</td>';
      //$strResultado .= '<td>'.PaginaSEI::tratarHTML($arrObjMdCorExpedicaoAndamentoDTO[$i]->getStrSiglaObjeto()).'</td>';
      //$strResultado .= '<td>'.PaginaSEI::tratarHTML($arrObjMdCorExpedicaoAndamentoDTO[$i]->getStrNomeObjeto()).'</td>';
      //$strResultado .= '<td>'.PaginaSEI::tratarHTML($arrObjMdCorExpedicaoAndamentoDTO[$i]->getStrCategoriaObjeto()).'</td>';
      $strResultado .= '<td align="center">';

      $strResultado .= PaginaSEI::getInstance()->getAcaoTransportarItem($i,$arrObjMdCorExpedicaoAndamentoDTO[$i]->getNumIdMdCorExpedicaoAndamento());

      if ($bolAcaoConsultar){
        $strResultado .= '<a href="'.SessaoSEI::getInstance()->assinarLink('controlador.php?acao=md_cor_expedicao_andamento_consultar&acao_origem='.$_GET['acao'].'&acao_retorno='.$_GET['acao'].'&id_md_cor_expedicao_andamento='.$arrObjMdCorExpedicaoAndamentoDTO[$i]->getNumIdMdCorExpedicaoAndamento()).'" tabindex="'.PaginaSEI::getInstance()->getProxTabTabela().'"><img src="'.PaginaSEI::getInstance()->getDiretorioImagensGlobal().'/consultar.gif" title="Consultar " alt="Consultar " class="infraImg" /></a>&nbsp;';
      }

      if ($bolAcaoAlterar){
        $strResultado .= '<a href="'.SessaoSEI::getInstance()->assinarLink('controlador.php?acao=md_cor_expedicao_andamento_alterar&acao_origem='.$_GET['acao'].'&acao_retorno='.$_GET['acao'].'&id_md_cor_expedicao_andamento='.$arrObjMdCorExpedicaoAndamentoDTO[$i]->getNumIdMdCorExpedicaoAndamento()).'" tabindex="'.PaginaSEI::getInstance()->getProxTabTabela().'"><img src="'.PaginaSEI::getInstance()->getDiretorioImagensGlobal().'/alterar.gif" title="Alterar " alt="Alterar " class="infraImg" /></a>&nbsp;';
      }

      if ($bolAcaoDesativar || $bolAcaoReativar || $bolAcaoExcluir){
        $strId = $arrObjMdCorExpedicaoAndamentoDTO[$i]->getNumIdMdCorExpedicaoAndamento();
        $strDescricao = PaginaSEI::getInstance()->formatarParametrosJavaScript(PaginaSEI::tratarHTML($arrObjMdCorExpedicaoAndamentoDTO[$i]->getNumIdMdCorExpedicaoAndamento()));
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
  if ($_GET['acao'] == 'md_cor_expedicao_andamento_selecionar'){
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
  if ('<?=$_GET['acao']?>'=='md_cor_expedicao_andamento_selecionar'){
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
    document.getElementById('frmMdCorExpedicaoAndamentoLista').action='<?=$strLinkDesativar?>';
    document.getElementById('frmMdCorExpedicaoAndamentoLista').submit();
  }
}

function acaoDesativacaoMultipla(){
  if (document.getElementById('hdnInfraItensSelecionados').value==''){
    alert('Nenhuma  selecionada.');
    return;
  }
  if (confirm("Confirma desativação ds  selecionads?")){
    document.getElementById('hdnInfraItemId').value='';
    document.getElementById('frmMdCorExpedicaoAndamentoLista').action='<?=$strLinkDesativar?>';
    document.getElementById('frmMdCorExpedicaoAndamentoLista').submit();
  }
}
<? } ?>

<? if ($bolAcaoReativar){ ?>
function acaoReativar(id,desc){
  if (confirm("Confirma reativação d  \""+desc+"\"?")){
    document.getElementById('hdnInfraItemId').value=id;
    document.getElementById('frmMdCorExpedicaoAndamentoLista').action='<?=$strLinkReativar?>';
    document.getElementById('frmMdCorExpedicaoAndamentoLista').submit();
  }
}

function acaoReativacaoMultipla(){
  if (document.getElementById('hdnInfraItensSelecionados').value==''){
    alert('Nenhuma  selecionada.');
    return;
  }
  if (confirm("Confirma reativação ds  selecionads?")){
    document.getElementById('hdnInfraItemId').value='';
    document.getElementById('frmMdCorExpedicaoAndamentoLista').action='<?=$strLinkReativar?>';
    document.getElementById('frmMdCorExpedicaoAndamentoLista').submit();
  }
}
<? } ?>

<? if ($bolAcaoExcluir){ ?>
function acaoExcluir(id,desc){
  if (confirm("Confirma exclusão d  \""+desc+"\"?")){
    document.getElementById('hdnInfraItemId').value=id;
    document.getElementById('frmMdCorExpedicaoAndamentoLista').action='<?=$strLinkExcluir?>';
    document.getElementById('frmMdCorExpedicaoAndamentoLista').submit();
  }
}

function acaoExclusaoMultipla(){
  if (document.getElementById('hdnInfraItensSelecionados').value==''){
    alert('Nenhuma  selecionada.');
    return;
  }
  if (confirm("Confirma exclusão ds  selecionads?")){
    document.getElementById('hdnInfraItemId').value='';
    document.getElementById('frmMdCorExpedicaoAndamentoLista').action='<?=$strLinkExcluir?>';
    document.getElementById('frmMdCorExpedicaoAndamentoLista').submit();
  }
}
<? } ?>

<?if(0){?></script><?}?>
<?
PaginaSEI::getInstance()->fecharJavaScript();
PaginaSEI::getInstance()->fecharHead();
PaginaSEI::getInstance()->abrirBody($strTitulo,'onload="inicializar();"');
?>
<form id="frmMdCorExpedicaoAndamentoLista" method="post" action="<?=SessaoSEI::getInstance()->assinarLink('controlador.php?acao='.$_GET['acao'].'&acao_origem='.$_GET['acao'])?>">
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