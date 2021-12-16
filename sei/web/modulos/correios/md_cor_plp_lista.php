<?
/**
 * TRIBUNAL REGIONAL FEDERAL DA 4 REGIO
 *
 * 11/10/2017 - criado por Jos Vieira <jose.vieira@cast.com.br>
 *
 * Verso do Gerador de Cdigo: 1.41.0
 */

try {
  require_once dirname(__FILE__).'/SEI.php';

  session_start();

  //////////////////////////////////////////////////////////////////////////////
  //InfraDebug::getInstance()->setBolLigado(false);
  //InfraDebug::getInstance()->setBolDebugInfra(true);
  //InfraDebug::getInstance()->limpar();
  //////////////////////////////////////////////////////////////////////////////

 PaginaSEI::getInstance()->validarLink();

 PaginaSEI::getInstance()->prepararSelecao('md_cor_plp_selecionar');

 PaginaSEI::getInstance()->validarPermissao($_GET['acao']);

 PaginaSEI::getInstance()->salvarCamposPost(array('selStaPlp'));

  switch($_GET['acao']){
    case 'md_cor_plp_excluir':
      try{
        $arrStrIds =PaginaSEI::getInstance()->getArrStrItensSelecionados();
        $arrObjMdCorPlpDTO = array();
        for ($i=0;$i<count($arrStrIds);$i++){
          $objMdCorPlpDTO = new MdCorPlpDTO();
          $arrObjMdCorPlpDTO[] = $objMdCorPlpDTO;
        }
        $objMdCorPlpRN = new MdCorPlpRN();
        $objMdCorPlpRN->excluir($arrObjMdCorPlpDTO);
       PaginaSEI::getInstance()->adicionarMensagem('Operao realizada com sucesso.');
      }catch(Exception $e){
       PaginaSEI::getInstance()->processarExcecao($e);
      }
      header('Location: '. PaginaSEI::getInstance()->assinarLink('controlador.php?acao='.$_GET['acao_origem'].'&acao_origem='.$_GET['acao']));
      break;

    /*
        case 'md_cor_plp_desativar':
          try{
            $arrStrIds =PaginaSEI::getInstance()->getArrStrItensSelecionados();
            $arrObjMdCorPlpDTO = array();
            for ($i=0;$i<count($arrStrIds);$i++){
              $objMdCorPlpDTO = new MdCorPlpDTO();
              $arrObjMdCorPlpDTO[] = $objMdCorPlpDTO;
            }
            $objMdCorPlpRN = new MdCorPlpRN();
            $objMdCorPlpRN->desativar($arrObjMdCorPlpDTO);
           PaginaSEI::getInstance()->adicionarMensagem('Operao realizada com sucesso.');
          }catch(Exception $e){
           PaginaSEI::getInstance()->processarExcecao($e);
          }
          header('Location: '.::getInstance()->assinarLink('controlador.php?acao='.$_GET['acao_origem'].'&acao_origem='.$_GET['acao']));
          die;

        case 'md_cor_plp_reativar':
          $strTitulo = 'Reativar ';
          if ($_GET['acao_confirmada']=='sim'){
            try{
              $arrStrIds =PaginaSEI::getInstance()->getArrStrItensSelecionados();
              $arrObjMdCorPlpDTO = array();
              for ($i=0;$i<count($arrStrIds);$i++){
                $objMdCorPlpDTO = new MdCorPlpDTO();
                $arrObjMdCorPlpDTO[] = $objMdCorPlpDTO;
              }
              $objMdCorPlpRN = new MdCorPlpRN();
              $objMdCorPlpRN->reativar($arrObjMdCorPlpDTO);
             PaginaSEI::getInstance()->adicionarMensagem('Operao realizada com sucesso.');
            }catch(Exception $e){
             PaginaSEI::getInstance()->processarExcecao($e);
            }
            header('Location: '.::getInstance()->assinarLink('controlador.php?acao='.$_GET['acao_origem'].'&acao_origem='.$_GET['acao']));
            die;
          }
          break;

     */
    case 'md_cor_plp_selecionar':
      $strTitulo =PaginaSEI::getInstance()->getTituloSelecao('Selecionar ','Selecionar ');

      //Se cadastrou alguem
      if ($_GET['acao_origem']=='md_cor_plp_cadastrar'){
        if (0){
         PaginaSEI::getInstance()->adicionarSelecionado();
        }
      }
      break;

    case 'md_cor_plp_listar':
      $strTitulo = '';
      break;

    default:
      throw new InfraException("Ao '".$_GET['acao']."' no reconhecida.");
  }

  $arrComandos = array();
  if ($_GET['acao'] == 'md_cor_plp_selecionar'){
    $arrComandos[] = '<button type="button" accesskey="T" id="btnTransportarSelecao" value="Transportar" onclick="infraTransportarSelecao();" class="infraButton"><span class="infraTeclaAtalho">T</span>ransportar</button>';
  }

  /* if ($_GET['acao'] == 'md_cor_plp_listar' || $_GET['acao'] == 'md_cor_plp_selecionar'){ */
  $bolAcaoCadastrar =PaginaSEI::getInstance()->verificarPermissao('md_cor_plp_cadastrar');
    if ($bolAcaoCadastrar){
      $arrComandos[] = '<button type="button" accesskey="N" id="btnNov" value="Nov" onclick="location.href=\''.PaginaSEI::getInstance()->assinarLink('controlador.php?acao=md_cor_plp_cadastrar&acao_origem='.$_GET['acao'].'&acao_retorno='.$_GET['acao']).'\'" class="infraButton"><span class="infraTeclaAtalho">N</span>ov</button>';
    }
  /* } */

  $objMdCorPlpDTO = new MdCorPlpDTO();
  //$objMdCorPlpDTO->retNumIdMdPlp();
  $objMdCorPlpDTO->retDblCodigoPlp();
  //$objMdCorPlpDTO->retStrStaPlp();
  $strStaPlp =PaginaSEI::getInstance()->recuperarCampo('selStaPlp');
  if ($strStaPlp!==''){
    $objMdCorPlpDTO->setStrStaPlp($strStaPlp);
  }

/*
  if ($_GET['acao'] == 'md_cor_plp_reativar'){
    //Lista somente inativos
    $objMdCorPlpDTO->setBolExclusaoLogica(false);
    $objMdCorPlpDTO->setStrSinAtivo('N');
  }
 */
 PaginaSEI::getInstance()->prepararOrdenacao($objMdCorPlpDTO, 'CodigoPlp', InfraDTO::$TIPO_ORDENACAO_ASC);
  //::getInstance()->prepararPaginacao($objMdCorPlpDTO);

  $objMdCorPlpRN = new MdCorPlpRN();
  $arrObjMdCorPlpDTO = $objMdCorPlpRN->listar($objMdCorPlpDTO);

  //::getInstance()->processarPaginacao($objMdCorPlpDTO);
  $numRegistros = count($arrObjMdCorPlpDTO);

  if ($numRegistros > 0){

    $bolCheck = false;

    if ($_GET['acao']=='md_cor_plp_selecionar'){
      $bolAcaoReativar = false;
      $bolAcaoConsultar =PaginaSEI::getInstance()->verificarPermissao('md_cor_plp_consultar');
      $bolAcaoAlterar =PaginaSEI::getInstance()->verificarPermissao('md_cor_plp_alterar');
      $bolAcaoImprimir = false;
      //$bolAcaoGerarPlanilha = false;
      $bolAcaoExcluir = false;
      $bolAcaoDesativar = false;
      $bolCheck = true;
/*     }else if ($_GET['acao']=='md_cor_plp_reativar'){
      $bolAcaoReativar =PaginaSEI::getInstance()->verificarPermissao('md_cor_plp_reativar');
      $bolAcaoConsultar =PaginaSEI::getInstance()->verificarPermissao('md_cor_plp_consultar');
      $bolAcaoAlterar = false;
      $bolAcaoImprimir = true;
      //$bolAcaoGerarPlanilha =PaginaSEI::getInstance()->verificarPermissao('infra_gerar_planilha_tabela');
      $bolAcaoExcluir =PaginaSEI::getInstance()->verificarPermissao('md_cor_plp_excluir');
      $bolAcaoDesativar = false;
 */    }else{
      $bolAcaoReativar = false;
      $bolAcaoConsultar =PaginaSEI::getInstance()->verificarPermissao('md_cor_plp_consultar');
      $bolAcaoAlterar =PaginaSEI::getInstance()->verificarPermissao('md_cor_plp_alterar');
      $bolAcaoImprimir = true;
      //$bolAcaoGerarPlanilha =PaginaSEI::getInstance()->verificarPermissao('infra_gerar_planilha_tabela');
      $bolAcaoExcluir =PaginaSEI::getInstance()->verificarPermissao('md_cor_plp_excluir');
      $bolAcaoDesativar =PaginaSEI::getInstance()->verificarPermissao('md_cor_plp_desativar');
    }

    /*
    if ($bolAcaoDesativar){
      $bolCheck = true;
      $arrComandos[] = '<button type="button" accesskey="t" id="btnDesativar" value="Desativar" onclick="acaoDesativacaoMultipla();" class="infraButton">Desa<span class="infraTeclaAtalho">t</span>ivar</button>';
      $strLinkDesativar =PaginaSEI::getInstance()->assinarLink('controlador.php?acao=md_cor_plp_desativar&acao_origem='.$_GET['acao']);
    }

    if ($bolAcaoReativar){
      $bolCheck = true;
      $arrComandos[] = '<button type="button" accesskey="R" id="btnReativar" value="Reativar" onclick="acaoReativacaoMultipla();" class="infraButton"><span class="infraTeclaAtalho">R</span>eativar</button>';
      $strLinkReativar =PaginaSEI::getInstance()->assinarLink('controlador.php?acao=md_cor_plp_reativar&acao_origem='.$_GET['acao'].'&acao_confirmada=sim');
    }
     */

    if ($bolAcaoExcluir){
      $bolCheck = true;
      $arrComandos[] = '<button type="button" accesskey="E" id="btnExcluir" value="Excluir" onclick="acaoExclusaoMultipla();" class="infraButton"><span class="infraTeclaAtalho">E</span>xcluir</button>';
      $strLinkExcluir =PaginaSEI::getInstance()->assinarLink('controlador.php?acao=md_cor_plp_excluir&acao_origem='.$_GET['acao']);
    }

    /*
    if ($bolAcaoGerarPlanilha){
      $bolCheck = true;
      $arrComandos[] = '<button type="button" accesskey="P" id="btnGerarPlanilha" value="Gerar Planilha" onclick="infraGerarPlanilhaTabela(\''.::getInstance()->assinarLink('controlador.php?acao=infra_gerar_planilha_tabela').'\');" class="infraButton">Gerar <span class="infraTeclaAtalho">P</span>lanilha</button>';
    }
    */

    $strResultado = '';

    /* if ($_GET['acao']!='md_cor_plp_reativar'){ */
    $strSumarioTabela = 'Tabela de .';
    $strCaptionTabela = '';
    /* }else{
      $strSumarioTabela = 'Tabela de  Inativs.';
      $strCaptionTabela = ' Inativs';
    } */

    $strResultado .= '<table width="99%" class="infraTable" summary="'.$strSumarioTabela.'">'."\n";
    $strResultado .= '<caption class="infraCaption">'. PaginaSEI::getInstance()->gerarCaptionTabela($strCaptionTabela,$numRegistros).'</caption>';
    $strResultado .= '<tr>';
    if ($bolCheck) {
      $strResultado .= '<th class="infraTh" width="1%">'. PaginaSEI::getInstance()->getThCheck().'</th>'."\n";
    }
    //$strResultado .= '<th class="infraTh">'.::getInstance()->getThOrdenacao($objMdCorPlpDTO,'','IdMdPlp',$arrObjMdCorPlpDTO).'</th>'."\n";
    $strResultado .= '<th class="infraTh">'. PaginaSEI::getInstance()->getThOrdenacao($objMdCorPlpDTO,'','CodigoPlp',$arrObjMdCorPlpDTO).'</th>'."\n";
    //$strResultado .= '<th class="infraTh">'.::getInstance()->getThOrdenacao($objMdCorPlpDTO,'','StaPlp',$arrObjMdCorPlpDTO).'</th>'."\n";
    $strResultado .= '<th class="infraTh">Aes</th>'."\n";
    $strResultado .= '</tr>'."\n";
    $strCssTr='';
    for($i = 0;$i < $numRegistros; $i++){

      $strCssTr = ($strCssTr=='<tr class="infraTrClara">')?'<tr class="infraTrEscura">':'<tr class="infraTrClara">';
      $strResultado .= $strCssTr;

      if ($bolCheck){
        $strResultado .= '<td valign="top">'.PaginaSEI::getInstance()->getTrCheck($i,$arrObjMdCorPlpDTO[$i]->getDblCodigoPlp()).'</td>';
      }
      //$strResultado .= '<td>'.::tratarHTML($arrObjMdCorPlpDTO[$i]->getNumIdMdPlp()).'</td>';
      $strResultado .= '<td>'.PaginaSEI::tratarHTML($arrObjMdCorPlpDTO[$i]->getDblCodigoPlp()).'</td>';
      //$strResultado .= '<td>'.::tratarHTML($arrObjMdCorPlpDTO[$i]->getStrStaPlp()).'</td>';
      $strResultado .= '<td align="center">';

      //$strResultado .=PaginaSEI::getInstance()->getAcaoTransportarItem($i,);

      if ($bolAcaoConsultar){
        $strResultado .= '<a href="'.PaginaSEI::getInstance()->assinarLink('controlador.php?acao=md_cor_plp_consultar&acao_origem='.$_GET['acao'].'&acao_retorno='.$_GET['acao']).'" tabindex="'.PaginaSEI::getInstance()->getProxTabTabela().'"><img src="'.PaginaSEI::getInstance()->getDiretorioImagensGlobal().'/consultar.gif" title="Consultar " alt="Consultar " class="infraImg" /></a>&nbsp;';
      }

      if ($bolAcaoAlterar){
        $strResultado .= '<a href="'.PaginaSEI::getInstance()->assinarLink('controlador.php?acao=md_cor_plp_alterar&acao_origem='.$_GET['acao'].'&acao_retorno='.$_GET['acao']).'" tabindex="'.PaginaSEI::getInstance()->getProxTabTabela().'"><img src="'.PaginaSEI::getInstance()->getDiretorioImagensGlobal().'/alterar.gif" title="Alterar " alt="Alterar " class="infraImg" /></a>&nbsp;';
      }

      if ($bolAcaoDesativar || $bolAcaoReativar || $bolAcaoExcluir){
        $strId = $arrObjMdCorPlpDTO[$i]->getDblCodigoPlp();
        $strDescricao =PaginaSEI::getInstance()->formatarParametrosJavaScript($arrObjMdCorPlpDTO[$i]->getDblCodigoPlp());
      }
/*
      if ($bolAcaoDesativar){
        $strResultado .= '<a href="'.::getInstance()->montarAncora($strId).'" onclick="acaoDesativar(\''.$strId.'\',\''.$strDescricao.'\');" tabindex="'.::getInstance()->getProxTabTabela().'"><img src="'.::getInstance()->getDiretorioImagensGlobal().'/desativar.gif" title="Desativar " alt="Desativar " class="infraImg" /></a>&nbsp;';
      }

      if ($bolAcaoReativar){
        $strResultado .= '<a href="'.::getInstance()->montarAncora($strId).'" onclick="acaoReativar(\''.$strId.'\',\''.$strDescricao.'\');" tabindex="'.::getInstance()->getProxTabTabela().'"><img src="'.::getInstance()->getDiretorioImagensGlobal().'/reativar.gif" title="Reativar " alt="Reativar " class="infraImg" /></a>&nbsp;';
      }
 */

      if ($bolAcaoExcluir){
        $strResultado .= '<a href="'.PaginaSEI::getInstance()->montarAncora($strId).'" onclick="acaoExcluir(\''.$strId.'\',\''.$strDescricao.'\');" tabindex="'.PaginaSEI::getInstance()->getProxTabTabela().'"><img src="'.PaginaSEI::getInstance()->getDiretorioImagensGlobal().'/excluir.gif" title="Excluir " alt="Excluir " class="infraImg" /></a>&nbsp;';
      }

      $strResultado .= '</td></tr>'."\n";
    }
    $strResultado .= '</table>';
  }
  if ($_GET['acao'] == 'md_cor_plp_selecionar'){
    $arrComandos[] = '<button type="button" accesskey="F" id="btnFecharSelecao" value="Fechar" onclick="window.close();" class="infraButton">Fe<span class="infraTeclaAtalho">c</span>har</button>';
  }else{
    $arrComandos[] = '<button type="button" accesskey="F" id="btnFechar" value="Fechar" onclick="location.href=\''.PaginaSEI::getInstance()->assinarLink('controlador.php?acao='.PaginaSEI::getInstance()->getAcaoRetorno().'&acao_origem='.$_GET['acao']).'\'" class="infraButton">Fe<span class="infraTeclaAtalho">c</span>har</button>';
  }

  $strItensSelStaPlp = MdCorPlpINT::montarSelectStaPlp('','Todos',$strStaPlp);
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
  #lblStaPlp {position:absolute;left:0%;top:0%;width:25%;}
  #selStaPlp {position:absolute;left:0%;top:40%;width:25%;}

  <?if(0){?></style><?}?>
<?
PaginaSEI::getInstance()->fecharStyle();
PaginaSEI::getInstance()->montarJavaScript();
PaginaSEI::getInstance()->abrirJavaScript();
?>
<?if(0){?><script type="text/javascript"><?}?>

  function inicializar(){
    if ('<?=$_GET['acao']?>'=='md_cor_plp_selecionar'){
      infraReceberSelecao();
      document.getElementById('btnFecharSelecao').focus();
    }else{
      document.getElementById('btnFechar').focus();
    }
    infraEfeitoTabelas();
  }

  <? if ($bolAcaoDesativar){ ?>
  function acaoDesativar(id,desc){
    if (confirm("Confirma desativao d  \""+desc+"\"?")){
      document.getElementById('hdnInfraItemId').value=id;
      document.getElementById('frmMdCorPlpLista').action='<?=$strLinkDesativar?>';
      document.getElementById('frmMdCorPlpLista').submit();
    }
  }

  function acaoDesativacaoMultipla(){
    if (document.getElementById('hdnInfraItensSelecionados').value==''){
      alert('Nenhuma  selecionada.');
      return;
    }
    if (confirm("Confirma desativao ds  selecionads?")){
      document.getElementById('hdnInfraItemId').value='';
      document.getElementById('frmMdCorPlpLista').action='<?=$strLinkDesativar?>';
      document.getElementById('frmMdCorPlpLista').submit();
    }
  }
  <? } ?>

  <? if ($bolAcaoReativar){ ?>
  function acaoReativar(id,desc){
    if (confirm("Confirma reativao d  \""+desc+"\"?")){
      document.getElementById('hdnInfraItemId').value=id;
      document.getElementById('frmMdCorPlpLista').action='<?=$strLinkReativar?>';
      document.getElementById('frmMdCorPlpLista').submit();
    }
  }

  function acaoReativacaoMultipla(){
    if (document.getElementById('hdnInfraItensSelecionados').value==''){
      alert('Nenhuma  selecionada.');
      return;
    }
    if (confirm("Confirma reativao ds  selecionads?")){
      document.getElementById('hdnInfraItemId').value='';
      document.getElementById('frmMdCorPlpLista').action='<?=$strLinkReativar?>';
      document.getElementById('frmMdCorPlpLista').submit();
    }
  }
  <? } ?>

  <? if ($bolAcaoExcluir){ ?>
  function acaoExcluir(id,desc){
    if (confirm("Confirma excluso d  \""+desc+"\"?")){
      document.getElementById('hdnInfraItemId').value=id;
      document.getElementById('frmMdCorPlpLista').action='<?=$strLinkExcluir?>';
      document.getElementById('frmMdCorPlpLista').submit();
    }
  }

  function acaoExclusaoMultipla(){
    if (document.getElementById('hdnInfraItensSelecionados').value==''){
      alert('Nenhuma  selecionada.');
      return;
    }
    if (confirm("Confirma excluso ds  selecionads?")){
      document.getElementById('hdnInfraItemId').value='';
      document.getElementById('frmMdCorPlpLista').action='<?=$strLinkExcluir?>';
      document.getElementById('frmMdCorPlpLista').submit();
    }
  }
  <? } ?>

  <?if(0){?></script><?}?>
<?
PaginaSEI::getInstance()->fecharJavaScript();
PaginaSEI::getInstance()->fecharHead();
PaginaSEI::getInstance()->abrirBody($strTitulo,'onload="inicializar();"');
?>
  <form id="frmMdCorPlpLista" method="post" action="<?=PaginaSEI::getInstance()->assinarLink('controlador.php?acao='.$_GET['acao'].'&acao_origem='.$_GET['acao'])?>">
    <?
   PaginaSEI::getInstance()->montarBarraComandosSuperior($arrComandos);
   PaginaSEI::getInstance()->abrirAreaDados('4.5em');
    ?>
    <label id="lblStaPlp" for="selStaPlp" accesskey="" class="infraLabelOpcional">sta_plp:</label>
    <select id="selStaPlp" name="selStaPlp" onchange="this.form.submit();" class="infraSelect" tabindex="<?=PaginaSEI::getInstance()->getProxTabDados()?>" >
      <?=$strItensSelStaPlp?>
    </select>

    <?
   PaginaSEI::getInstance()->fecharAreaDados();
   PaginaSEI::getInstance()->montarAreaTabela($strResultado,$numRegistros);
    //::getInstance()->montarAreaDebug();
   PaginaSEI::getInstance()->montarBarraComandosInferior($arrComandos);
    ?>
  </form>
<?
PaginaSEI::getInstance()->fecharBody();
PaginaSEI::getInstance()->fecharHtml();

