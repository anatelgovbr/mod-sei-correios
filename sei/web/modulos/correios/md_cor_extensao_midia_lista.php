<?
/**
* TRIBUNAL REGIONAL FEDERAL DA 4ª REGIÃO
*
* 20/12/2016 - criado por Wilton Júnior
*
* Versão do Gerador de Código: 1.39.0
*/

try {
  require_once dirname(__FILE__).'/Correios.php';

  session_start();

  //////////////////////////////////////////////////////////////////////////////
  //InfraDebug::getInstance()->setBolLigado(false);
  //InfraDebug::getInstance()->setBolDebugInfra(true);
  //InfraDebug::getInstance()->limpar();
  //////////////////////////////////////////////////////////////////////////////

  SessaoSEI::getInstance()->validarLink();

  PaginaSEI::getInstance()->prepararSelecao('md_cor_extensao_midia_selecionar');

  SessaoSEI::getInstance()->validarPermissao($_GET['acao']);

  switch($_GET['acao']){
    case 'md_cor_extensao_midia_excluir':
      try{
        $arrStrIds = PaginaSEI::getInstance()->getArrStrItensSelecionados();
        $arrObjMdCorExtensaoMidiaDTO = array();
        for ($i=0;$i<count($arrStrIds);$i++){
          $objMdCorExtensaoMidiaDTO = new MdCorExtensaoMidiaDTO();
          $objMdCorExtensaoMidiaDTO->setNumIdMdCorExtensaoMidia($arrStrIds[$i]);
          $arrObjMdCorExtensaoMidiaDTO[] = $objMdCorExtensaoMidiaDTO;
        }
        $objMdCorExtensaoMidiaRN = new MdCorExtensaoMidiaRN();
        $objMdCorExtensaoMidiaRN->excluir($arrObjMdCorExtensaoMidiaDTO);
        PaginaSEI::getInstance()->adicionarMensagem('Operação realizada com sucesso.');
      }catch(Exception $e){
        PaginaSEI::getInstance()->processarExcecao($e);
      } 
      header('Location: '.SessaoSEI::getInstance()->assinarLink('controlador.php?acao='.$_GET['acao_origem'].'&acao_origem='.$_GET['acao']));
      die;


    case 'md_cor_extensao_midia_desativar':
      try{
        $arrStrIds = PaginaSEI::getInstance()->getArrStrItensSelecionados();
        $arrObjMdCorExtensaoMidiaDTO = array();
        for ($i=0;$i<count($arrStrIds);$i++){
          $objMdCorExtensaoMidiaDTO = new MdCorExtensaoMidiaDTO();
          $objMdCorExtensaoMidiaDTO->setNumIdMdCorExtensaoMidia($arrStrIds[$i]);
          $arrObjMdCorExtensaoMidiaDTO[] = $objMdCorExtensaoMidiaDTO;
        }
        $objMdCorExtensaoMidiaRN = new MdCorExtensaoMidiaRN();
        $objMdCorExtensaoMidiaRN->desativar($arrObjMdCorExtensaoMidiaDTO);
        PaginaSEI::getInstance()->adicionarMensagem('Operação realizada com sucesso.');
      }catch(Exception $e){
        PaginaSEI::getInstance()->processarExcecao($e);
      } 
      header('Location: '.SessaoSEI::getInstance()->assinarLink('controlador.php?acao='.$_GET['acao_origem'].'&acao_origem='.$_GET['acao']));
      die;

    case 'md_cor_extensao_midia_reativar':
      $strTitulo = 'Reativar as';
      if ($_GET['acao_confirmada']=='sim'){
        try{
          $arrStrIds = PaginaSEI::getInstance()->getArrStrItensSelecionados();
          $arrObjMdCorExtensaoMidiaDTO = array();
          for ($i=0;$i<count($arrStrIds);$i++){
            $objMdCorExtensaoMidiaDTO = new MdCorExtensaoMidiaDTO();
            $objMdCorExtensaoMidiaDTO->setNumIdMdCorExtensaoMidia($arrStrIds[$i]);
            $arrObjMdCorExtensaoMidiaDTO[] = $objMdCorExtensaoMidiaDTO;
          }
          $objMdCorExtensaoMidiaRN = new MdCorExtensaoMidiaRN();
          $objMdCorExtensaoMidiaRN->reativar($arrObjMdCorExtensaoMidiaDTO);
          PaginaSEI::getInstance()->adicionarMensagem('Operação realizada com sucesso.');
        }catch(Exception $e){
          PaginaSEI::getInstance()->processarExcecao($e);
        } 
        header('Location: '.SessaoSEI::getInstance()->assinarLink('controlador.php?acao='.$_GET['acao_origem'].'&acao_origem='.$_GET['acao']));
        die;
      } 
      break;


    case 'md_cor_extensao_midia_selecionar':
      $strTitulo = PaginaSEI::getInstance()->getTituloSelecao('Selecionar a','Selecionar as');

      //Se cadastrou alguem
      if ($_GET['acao_origem']=='md_cor_extensao_midia_cadastrar'){
        if (isset($_GET['id_md_cor_extensao_midia'])){
          PaginaSEI::getInstance()->adicionarSelecionado($_GET['id_md_cor_extensao_midia']);
        }
      }
      break;

    case 'md_cor_extensao_midia_listar':
      $strTitulo = 'as';
      break;

    default:
      throw new InfraException("Ação '".$_GET['acao']."' não reconhecida.");
  }

  $arrComandos = array();
  if ($_GET['acao'] == 'md_cor_extensao_midia_selecionar'){
    $arrComandos[] = '<button type="button" accesskey="T" id="btnTransportarSelecao" value="Transportar" onclick="infraTransportarSelecao();" class="infraButton"><span class="infraTeclaAtalho">T</span>ransportar</button>';
  }

  if ($_GET['acao'] == 'md_cor_extensao_midia_listar' || $_GET['acao'] == 'md_cor_extensao_midia_selecionar'){
    $bolAcaoCadastrar = SessaoSEI::getInstance()->verificarPermissao('md_cor_extensao_midia_cadastrar');
    if ($bolAcaoCadastrar){
      $arrComandos[] = '<button type="button" accesskey="N" id="btnNova" value="Nova" onclick="location.href=\''.SessaoSEI::getInstance()->assinarLink('controlador.php?acao=md_cor_extensao_midia_cadastrar&acao_origem='.$_GET['acao'].'&acao_retorno='.$_GET['acao']).'\'" class="infraButton"><span class="infraTeclaAtalho">N</span>ova</button>';
    }
  }

  $objMdCorExtensaoMidiaDTO = new MdCorExtensaoMidiaDTO();
  $objMdCorExtensaoMidiaDTO->retNumIdMdCorExtensaoMidia();
  $objMdCorExtensaoMidiaDTO->retStrNomeExtensao();

  if ($_GET['acao'] == 'md_cor_extensao_midia_reativar'){
    //Lista somente inativos
    $objMdCorExtensaoMidiaDTO->setBolExclusaoLogica(false);
    $objMdCorExtensaoMidiaDTO->setStrSinAtivo('N');
  }

  PaginaSEI::getInstance()->prepararOrdenacao($objMdCorExtensaoMidiaDTO, 'NomeExtensao', InfraDTO::$TIPO_ORDENACAO_ASC);
  //PaginaSEI::getInstance()->prepararPaginacao($objMdCorExtensaoMidiaDTO);

  $objMdCorExtensaoMidiaRN = new MdCorExtensaoMidiaRN();
  $arrObjMdCorExtensaoMidiaDTO = $objMdCorExtensaoMidiaRN->listar($objMdCorExtensaoMidiaDTO);

  //PaginaSEI::getInstance()->processarPaginacao($objMdCorExtensaoMidiaDTO);
  $numRegistros = count($arrObjMdCorExtensaoMidiaDTO);

  if ($numRegistros > 0){

    $bolCheck = false;

    if ($_GET['acao']=='md_cor_extensao_midia_selecionar'){
      $bolAcaoReativar = false;
      $bolAcaoConsultar = SessaoSEI::getInstance()->verificarPermissao('md_cor_extensao_midia_consultar');
      $bolAcaoAlterar = SessaoSEI::getInstance()->verificarPermissao('md_cor_extensao_midia_alterar');
      $bolAcaoImprimir = false;
      //$bolAcaoGerarPlanilha = false;
      $bolAcaoExcluir = false;
      $bolAcaoDesativar = false;
      $bolCheck = true;
    }else if ($_GET['acao']=='md_cor_extensao_midia_reativar'){
      $bolAcaoReativar = SessaoSEI::getInstance()->verificarPermissao('md_cor_extensao_midia_reativar');
      $bolAcaoConsultar = SessaoSEI::getInstance()->verificarPermissao('md_cor_extensao_midia_consultar');
      $bolAcaoAlterar = false;
      $bolAcaoImprimir = true;
      //$bolAcaoGerarPlanilha = SessaoSEI::getInstance()->verificarPermissao('infra_gerar_planilha_tabela');
      $bolAcaoExcluir = SessaoSEI::getInstance()->verificarPermissao('md_cor_extensao_midia_excluir');
      $bolAcaoDesativar = false;
    }else{
      $bolAcaoReativar = false;
      $bolAcaoConsultar = SessaoSEI::getInstance()->verificarPermissao('md_cor_extensao_midia_consultar');
      $bolAcaoAlterar = SessaoSEI::getInstance()->verificarPermissao('md_cor_extensao_midia_alterar');
      $bolAcaoImprimir = true;
      //$bolAcaoGerarPlanilha = SessaoSEI::getInstance()->verificarPermissao('infra_gerar_planilha_tabela');
      $bolAcaoExcluir = SessaoSEI::getInstance()->verificarPermissao('md_cor_extensao_midia_excluir');
      $bolAcaoDesativar = SessaoSEI::getInstance()->verificarPermissao('md_cor_extensao_midia_desativar');
    }

    
    if ($bolAcaoDesativar){
      $bolCheck = true;
      $arrComandos[] = '<button type="button" accesskey="t" id="btnDesativar" value="Desativar" onclick="acaoDesativacaoMultipla();" class="infraButton">Desa<span class="infraTeclaAtalho">t</span>ivar</button>';
      $strLinkDesativar = SessaoSEI::getInstance()->assinarLink('controlador.php?acao=md_cor_extensao_midia_desativar&acao_origem='.$_GET['acao']);
    }

    if ($bolAcaoReativar){
      $bolCheck = true;
      $arrComandos[] = '<button type="button" accesskey="R" id="btnReativar" value="Reativar" onclick="acaoReativacaoMultipla();" class="infraButton"><span class="infraTeclaAtalho">R</span>eativar</button>';
      $strLinkReativar = SessaoSEI::getInstance()->assinarLink('controlador.php?acao=md_cor_extensao_midia_reativar&acao_origem='.$_GET['acao'].'&acao_confirmada=sim');
    }
    

    if ($bolAcaoExcluir){
      $bolCheck = true;
      $arrComandos[] = '<button type="button" accesskey="E" id="btnExcluir" value="Excluir" onclick="acaoExclusaoMultipla();" class="infraButton"><span class="infraTeclaAtalho">E</span>xcluir</button>';
      $strLinkExcluir = SessaoSEI::getInstance()->assinarLink('controlador.php?acao=md_cor_extensao_midia_excluir&acao_origem='.$_GET['acao']);
    }

    /*
    if ($bolAcaoGerarPlanilha){
      $bolCheck = true;
      $arrComandos[] = '<button type="button" accesskey="P" id="btnGerarPlanilha" value="Gerar Planilha" onclick="infraGerarPlanilhaTabela(\''.SessaoSEI::getInstance()->assinarLink('controlador.php?acao=infra_gerar_planilha_tabela').'\');" class="infraButton">Gerar <span class="infraTeclaAtalho">P</span>lanilha</button>';
    }
    */

    $strResultado = '';

    if ($_GET['acao']!='md_cor_extensao_midia_reativar'){
      $strSumarioTabela = 'Tabela de as.';
      $strCaptionTabela = 'as';
    }else{
      $strSumarioTabela = 'Tabela de as Inativas.';
      $strCaptionTabela = 'as Inativas';
    }

    $strResultado .= '<table width="99%" class="infraTable" summary="'.$strSumarioTabela.'">'."\n";
    $strResultado .= '<caption class="infraCaption">'.PaginaSEI::getInstance()->gerarCaptionTabela($strCaptionTabela,$numRegistros).'</caption>';
    $strResultado .= '<tr>';
    if ($bolCheck) {
      $strResultado .= '<th class="infraTh" width="1%">'.PaginaSEI::getInstance()->getThCheck().'</th>'."\n";
    }
    $strResultado .= '<th class="infraTh">'.PaginaSEI::getInstance()->getThOrdenacao($objMdCorExtensaoMidiaDTO,'Extensão','NomeExtensao',$arrObjMdCorExtensaoMidiaDTO).'</th>'."\n";
    $strResultado .= '<th class="infraTh">Ações</th>'."\n";
    $strResultado .= '</tr>'."\n";
    $strCssTr='';
    for($i = 0;$i < $numRegistros; $i++){

      $strCssTr = ($strCssTr=='<tr class="infraTrClara">')?'<tr class="infraTrEscura">':'<tr class="infraTrClara">';
      $strResultado .= $strCssTr;

      if ($bolCheck){
        $strResultado .= '<td valign="top">'.PaginaSEI::getInstance()->getTrCheck($i,$arrObjMdCorExtensaoMidiaDTO[$i]->getNumIdMdCorExtensaoMidia(),$arrObjMdCorExtensaoMidiaDTO[$i]->getStrNomeExtensao()).'</td>';
      }
      $strResultado .= '<td>'.PaginaSEI::tratarHTML($arrObjMdCorExtensaoMidiaDTO[$i]->getStrNomeExtensao()).'</td>';
      $strResultado .= '<td align="center">';

      $strResultado .= PaginaSEI::getInstance()->getAcaoTransportarItem($i,$arrObjMdCorExtensaoMidiaDTO[$i]->getNumIdMdCorExtensaoMidia());

      if ($bolAcaoConsultar){
        $strResultado .= '<a href="'.SessaoSEI::getInstance()->assinarLink('controlador.php?acao=md_cor_extensao_midia_consultar&acao_origem='.$_GET['acao'].'&acao_retorno='.$_GET['acao'].'&id_md_cor_extensao_midia='.$arrObjMdCorExtensaoMidiaDTO[$i]->getNumIdMdCorExtensaoMidia()).'" tabindex="'.PaginaSEI::getInstance()->getProxTabTabela().'"><img src="'.PaginaSEI::getInstance()->getDiretorioImagensGlobal().'/consultar.gif" title="Consultar a" alt="Consultar a" class="infraImg" /></a>&nbsp;';
      }

      if ($bolAcaoAlterar){
        $strResultado .= '<a href="'.SessaoSEI::getInstance()->assinarLink('controlador.php?acao=md_cor_extensao_midia_alterar&acao_origem='.$_GET['acao'].'&acao_retorno='.$_GET['acao'].'&id_md_cor_extensao_midia='.$arrObjMdCorExtensaoMidiaDTO[$i]->getNumIdMdCorExtensaoMidia()).'" tabindex="'.PaginaSEI::getInstance()->getProxTabTabela().'"><img src="'.PaginaSEI::getInstance()->getDiretorioImagensGlobal().'/alterar.gif" title="Alterar a" alt="Alterar a" class="infraImg" /></a>&nbsp;';
      }

      if ($bolAcaoDesativar || $bolAcaoReativar || $bolAcaoExcluir){
        $strId = $arrObjMdCorExtensaoMidiaDTO[$i]->getNumIdMdCorExtensaoMidia();
        $strDescricao = PaginaSEI::getInstance()->formatarParametrosJavaScript(PaginaSEI::tratarHTML($arrObjMdCorExtensaoMidiaDTO[$i]->getStrNomeExtensao()));
      }

      if ($bolAcaoDesativar){
        $strResultado .= '<a href="'.PaginaSEI::getInstance()->montarAncora($strId).'" onclick="acaoDesativar(\''.$strId.'\',\''.$strDescricao.'\');" tabindex="'.PaginaSEI::getInstance()->getProxTabTabela().'"><img src="'.PaginaSEI::getInstance()->getDiretorioImagensGlobal().'/desativar.gif" title="Desativar a" alt="Desativar a" class="infraImg" /></a>&nbsp;';
      }

      if ($bolAcaoReativar){
        $strResultado .= '<a href="'.PaginaSEI::getInstance()->montarAncora($strId).'" onclick="acaoReativar(\''.$strId.'\',\''.$strDescricao.'\');" tabindex="'.PaginaSEI::getInstance()->getProxTabTabela().'"><img src="'.PaginaSEI::getInstance()->getDiretorioImagensGlobal().'/reativar.gif" title="Reativar a" alt="Reativar a" class="infraImg" /></a>&nbsp;';
      }


      if ($bolAcaoExcluir){
        $strResultado .= '<a href="'.PaginaSEI::getInstance()->montarAncora($strId).'" onclick="acaoExcluir(\''.$strId.'\',\''.$strDescricao.'\');" tabindex="'.PaginaSEI::getInstance()->getProxTabTabela().'"><img src="'.PaginaSEI::getInstance()->getDiretorioImagensGlobal().'/excluir.gif" title="Excluir a" alt="Excluir a" class="infraImg" /></a>&nbsp;';
      }

      $strResultado .= '</td></tr>'."\n";
    }
    $strResultado .= '</table>';
  }
  if ($_GET['acao'] == 'md_cor_extensao_midia_selecionar'){
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
  if ('<?=$_GET['acao']?>'=='md_cor_extensao_midia_selecionar'){
    infraReceberSelecao();
    document.getElementById('btnFecharSelecao').focus();
  }else{
    document.getElementById('btnFechar').focus();
  }
  infraEfeitoTabelas();
}

<? if ($bolAcaoDesativar){ ?>
function acaoDesativar(id,desc){
  if (confirm("Confirma desativação da a \""+desc+"\"?")){
    document.getElementById('hdnInfraItemId').value=id;
    document.getElementById('frmMdCorExtensaoMidiaLista').action='<?=$strLinkDesativar?>';
    document.getElementById('frmMdCorExtensaoMidiaLista').submit();
  }
}

function acaoDesativacaoMultipla(){
  if (document.getElementById('hdnInfraItensSelecionados').value==''){
    alert('Nenhuma a selecionada.');
    return;
  }
  if (confirm("Confirma desativação das as selecionadas?")){
    document.getElementById('hdnInfraItemId').value='';
    document.getElementById('frmMdCorExtensaoMidiaLista').action='<?=$strLinkDesativar?>';
    document.getElementById('frmMdCorExtensaoMidiaLista').submit();
  }
}
<? } ?>

<? if ($bolAcaoReativar){ ?>
function acaoReativar(id,desc){
  if (confirm("Confirma reativação da Extensão para Gravação em Mídia \""+desc+"\"?")){
    document.getElementById('hdnInfraItemId').value=id;
    document.getElementById('frmMdCorExtensaoMidiaLista').action='<?=$strLinkReativar?>';
    document.getElementById('frmMdCorExtensaoMidiaLista').submit();
  }
}

function acaoReativacaoMultipla(){
  if (document.getElementById('hdnInfraItensSelecionados').value==''){
    alert('Nenhuma a selecionada.');
    return;
  }
  if (confirm("Confirma reativação das Extensões para Gravação em Mídia selecionadas?")){
    document.getElementById('hdnInfraItemId').value='';
    document.getElementById('frmMdCorExtensaoMidiaLista').action='<?=$strLinkReativar?>';
    document.getElementById('frmMdCorExtensaoMidiaLista').submit();
  }
}
<? } ?>

<? if ($bolAcaoExcluir){ ?>
function acaoExcluir(id,desc){
  if (confirm("Confirma exclusão da a \""+desc+"\"?")){
    document.getElementById('hdnInfraItemId').value=id;
    document.getElementById('frmMdCorExtensaoMidiaLista').action='<?=$strLinkExcluir?>';
    document.getElementById('frmMdCorExtensaoMidiaLista').submit();
  }
}

function acaoExclusaoMultipla(){
  if (document.getElementById('hdnInfraItensSelecionados').value==''){
    alert('Nenhuma a selecionada.');
    return;
  }
  if (confirm("Confirma exclusão das as selecionadas?")){
    document.getElementById('hdnInfraItemId').value='';
    document.getElementById('frmMdCorExtensaoMidiaLista').action='<?=$strLinkExcluir?>';
    document.getElementById('frmMdCorExtensaoMidiaLista').submit();
  }
}
<? } ?>

<?if(0){?></script><?}?>
<?
PaginaSEI::getInstance()->fecharJavaScript();
PaginaSEI::getInstance()->fecharHead();
PaginaSEI::getInstance()->abrirBody($strTitulo,'onload="inicializar();"');
?>
<form id="frmMdCorExtensaoMidiaLista" method="post" action="<?=SessaoSEI::getInstance()->assinarLink('controlador.php?acao='.$_GET['acao'].'&acao_origem='.$_GET['acao'])?>">
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