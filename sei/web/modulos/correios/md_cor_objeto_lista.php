<?
/**
* TRIBUNAL REGIONAL FEDERAL DA 4ª REGIÃO
*
* 14/11/2017 - criado por ellyson.silva
*
* Versão do Gerador de Código: 1.41.0
*/

try {
  require_once dirname(__FILE__).'/../../SEI.php';

  session_start();

  //////////////////////////////////////////////////////////////////////////////
  //InfraDebug::getInstance()->setBolLigado(false);
  //InfraDebug::getInstance()->setBolDebugInfra(true);
  //InfraDebug::getInstance()->limpar();
  //////////////////////////////////////////////////////////////////////////////

  SessaoSEI::getInstance()->validarLink();

  PaginaSEI::getInstance()->prepararSelecao('md_cor_objeto_selecionar');

  SessaoSEI::getInstance()->validarPermissao($_GET['acao']);

  PaginaSEI::getInstance()->salvarCamposPost(array('selMdCorTipoObjeto','selMdCorSituação no Rastreio do MóduloContrato'));

  switch($_GET['acao']){
    case 'md_cor_objeto_excluir':
      try{
        $arrStrIds = PaginaSEI::getInstance()->getArrStrItensSelecionados();
        $arrObjMdCorObjetoDTO = array();
        for ($i=0;$i<count($arrStrIds);$i++){
          $objMdCorObjetoDTO = new MdCorObjetoDTO();
          $objMdCorObjetoDTO->setNumIdMdCorObjeto($arrStrIds[$i]);
          $arrObjMdCorObjetoDTO[] = $objMdCorObjetoDTO;
        }
        $objMdCorObjetoRN = new MdCorObjetoRN();
        $objMdCorObjetoRN->excluir($arrObjMdCorObjetoDTO);
        PaginaSEI::getInstance()->adicionarMensagem('Operação realizada com sucesso.');
      }catch(Exception $e){
        PaginaSEI::getInstance()->processarExcecao($e);
      } 
      header('Location: '.SessaoSEI::getInstance()->assinarLink('controlador.php?acao='.$_GET['acao_origem'].'&acao_origem='.$_GET['acao'].'&id_md_cor_contrato='.$_GET['id_md_cor_contrato']));
      die;

    case 'md_cor_objeto_selecionar':
      $strTitulo = PaginaSEI::getInstance()->getTituloSelecao('Selecionar objeto','Selecionar embalagens');

      //Se cadastrou alguem
      if ($_GET['acao_origem']=='md_cor_objeto_cadastrar'){
        if (isset($_GET['id_md_cor_objeto'])){
          PaginaSEI::getInstance()->adicionarSelecionado($_GET['id_md_cor_objeto']);
        }
      }
      break;

    case 'md_cor_objeto_listar':
      $strTitulo = 'Rótulo de Envelope por Tipo de Embalagem';
      break;

    default:
      throw new InfraException("Ação '".$_GET['acao']."' não reconhecida.");
  }

  $arrComandos = array();
  if ($_GET['acao'] == 'md_cor_objeto_selecionar'){
    $arrComandos[] = '<button type="button" accesskey="T" id="btnTransportarSelecao" value="Transportar" onclick="infraTransportarSelecao();" class="infraButton"><span class="infraTeclaAtalho">T</span>ransportar</button>';
  }

    $bolAcaoCadastrar = SessaoSEI::getInstance()->verificarPermissao('md_cor_objeto_cadastrar');
    if ($bolAcaoCadastrar){
      $arrComandos[] = '<button type="button" accesskey="N" id="btnNova" value="Nova" onclick="location.href=\''.SessaoSEI::getInstance()->assinarLink('controlador.php?acao=md_cor_objeto_cadastrar&acao_origem='.$_GET['acao'].'&acao_retorno='.$_GET['acao'].'&id_md_cor_contrato='.$_GET['id_md_cor_contrato']).'\'" class="infraButton"><span class="infraTeclaAtalho">N</span>ova</button>';
    }

  $objMdCorObjetoDTO = new MdCorObjetoDTO();
  $objMdCorObjetoDTO->retNumIdMdCorObjeto();
  $objMdCorObjetoDTO->retStrTipoRotuloImpressao();
  $objMdCorObjetoDTO->retStrSinObjetoPadrao();
  $objMdCorObjetoDTO->retDblMargemSuperiorImpressao();
  $objMdCorObjetoDTO->retDblMargemEsquerdaImpressao();
  $objMdCorObjetoDTO->retNumIdMdCorTipoObjeto();
  $objMdCorObjetoDTO->retStrNomeTipoObjeto();

  $objMdCorObjetoDTO->setStrSinAtivo('S');

  $numIdMdCorTipoObjeto = PaginaSEI::getInstance()->recuperarCampo('selMdCorTipoObjeto');
  if ($numIdMdCorTipoObjeto!==''){
    $objMdCorObjetoDTO->setNumIdMdCorTipoObjeto($numIdMdCorTipoObjeto);
  }

  $numIdMdCorContrato = PaginaSEI::getInstance()->recuperarCampo('selMdCorContrato');
  if ($numIdMdCorContrato!==''){
    $objMdCorObjetoDTO->setNumIdMdCorContrato($numIdMdCorContrato);
  }

    $objMdCorObjetoDTO->setNumIdMdCorContrato($_GET['id_md_cor_contrato']);

  PaginaSEI::getInstance()->prepararOrdenacao($objMdCorObjetoDTO, 'IdMdCorTipoObjeto', InfraDTO::$TIPO_ORDENACAO_ASC);
  PaginaSEI::getInstance()->prepararPaginacao($objMdCorObjetoDTO);

  $objMdCorObjetoRN = new MdCorObjetoRN();
  $arrObjMdCorObjetoDTO = $objMdCorObjetoRN->listar($objMdCorObjetoDTO);

  PaginaSEI::getInstance()->processarPaginacao($objMdCorObjetoDTO);
  $numRegistros = count($arrObjMdCorObjetoDTO);

  if ($numRegistros > 0){

    $bolCheck = false;

    if ($_GET['acao']=='md_cor_objeto_selecionar'){
      $bolAcaoReativar = false;
      $bolAcaoConsultar = SessaoSEI::getInstance()->verificarPermissao('md_cor_objeto_consultar');
      $bolAcaoAlterar = SessaoSEI::getInstance()->verificarPermissao('md_cor_objeto_alterar');
      $bolAcaoImprimir = false;
      $bolAcaoExcluir = false;
      $bolAcaoDesativar = false;
      $bolCheck = true;
    }else{
      $bolAcaoReativar = false;
      $bolAcaoConsultar = SessaoSEI::getInstance()->verificarPermissao('md_cor_objeto_consultar');
      $bolAcaoAlterar = SessaoSEI::getInstance()->verificarPermissao('md_cor_objeto_alterar');
      $bolAcaoImprimir = true;
      $bolAcaoExcluir = SessaoSEI::getInstance()->verificarPermissao('md_cor_objeto_excluir');
      $bolAcaoDesativar = SessaoSEI::getInstance()->verificarPermissao('md_cor_objeto_desativar');
    }

    if ($bolAcaoExcluir){
      $bolCheck = true;
      $arrComandos[] = '<button type="button" accesskey="X" id="btnExcluir" value="Excluir" onclick="acaoExclusaoMultipla();" class="infraButton">E<span class="infraTeclaAtalho">x</span>cluir</button>';
      $strLinkExcluir = SessaoSEI::getInstance()->assinarLink('controlador.php?acao=md_cor_objeto_excluir&acao_origem='.$_GET['acao'].'&id_md_cor_contrato='.$_GET['id_md_cor_contrato']);
    }

    $strResultado = '';

      $strSumarioTabela = 'Tabela de embalagens.';
      $strCaptionTabela = 'embalagens';

    $strResultado .= '<table width="99%" class="infraTable" summary="'.$strSumarioTabela.'">'."\n";
    $strResultado .= '<caption class="infraCaption">'.PaginaSEI::getInstance()->gerarCaptionTabela($strCaptionTabela,$numRegistros).'</caption>';
    $strResultado .= '<tr>';
    if ($bolCheck) {
      $strResultado .= '<th class="infraTh" width="1%">'.PaginaSEI::getInstance()->getThCheck().'</th>'."\n";
    }
    $strResultado .= '<th class="infraTh">'.PaginaSEI::getInstance()->getThOrdenacao($objMdCorObjetoDTO,'Tipo de Embalagem','NomeTipoObjeto',$arrObjMdCorObjetoDTO).'</th>'."\n";
    $strResultado .= '<th class="infraTh">'.PaginaSEI::getInstance()->getThOrdenacao($objMdCorObjetoDTO,'Tipo de Rótulo para Impressão','TipoRotuloImpressao',$arrObjMdCorObjetoDTO).'</th>'."\n";
    $strResultado .= '<th class="infraTh">'.PaginaSEI::getInstance()->getThOrdenacao($objMdCorObjetoDTO,'Tipo de Objeto Padrão','SinObjetoPadrao',$arrObjMdCorObjetoDTO).'</th>'."\n";
    $strResultado .= '<th class="infraTh" width="120px">Ações</th>'."\n";
    $strResultado .= '</tr>'."\n";
    $strCssTr='';
    for($i = 0;$i < $numRegistros; $i++){

      $strCssTr = ($strCssTr=='<tr class="infraTrClara">')?'<tr class="infraTrEscura">':'<tr class="infraTrClara">';
      $strResultado .= $strCssTr;

      if ($bolCheck){
        $strResultado .= '<td valign="top">'.PaginaSEI::getInstance()->getTrCheck($i,$arrObjMdCorObjetoDTO[$i]->getNumIdMdCorObjeto(),$arrObjMdCorObjetoDTO[$i]->getNumIdMdCorTipoObjeto()).'</td>';
      }

      $strResultado .= '<td align="center">'.PaginaSEI::tratarHTML($arrObjMdCorObjetoDTO[$i]->getStrNomeTipoObjeto()).'</td>';
      $strResultado .= '<td align="center">'.PaginaSEI::tratarHTML($arrObjMdCorObjetoDTO[$i]->getStrRotuloImpressao()).'</td>';
      $strResultado .= '<td align="center">'.PaginaSEI::tratarHTML($arrObjMdCorObjetoDTO[$i]->getStrSinObjetoPadrao() =='S' ? 'Sim': 'Não').'</td>';
      $strResultado .= '<td align="center">';

      $strResultado .= PaginaSEI::getInstance()->getAcaoTransportarItem($i,$arrObjMdCorObjetoDTO[$i]->getNumIdMdCorObjeto());

      if ($bolAcaoConsultar){
        $strResultado .= '<a href="'.SessaoSEI::getInstance()->assinarLink('controlador.php?acao=md_cor_objeto_consultar&acao_origem='.$_GET['acao'].'&acao_retorno='.$_GET['acao'].'&id_md_cor_contrato='.$_GET['id_md_cor_contrato'].'&id_md_cor_objeto='.$arrObjMdCorObjetoDTO[$i]->getNumIdMdCorObjeto()).'" tabindex="'.PaginaSEI::getInstance()->getProxTabTabela().'"><img src="'.PaginaSEI::getInstance()->getDiretorioSvgGlobal().'/consultar.svg" title="Consultar Embalagem" alt="Consultar Embalagem" class="infraImg" /></a>&nbsp;';
      }

      if ($bolAcaoAlterar){
        $strResultado .= '<a href="'.SessaoSEI::getInstance()->assinarLink('controlador.php?acao=md_cor_objeto_alterar&acao_origem='.$_GET['acao'].'&acao_retorno='.$_GET['acao'].'&id_md_cor_contrato='.$_GET['id_md_cor_contrato'].'&id_md_cor_objeto='.$arrObjMdCorObjetoDTO[$i]->getNumIdMdCorObjeto()).'" tabindex="'.PaginaSEI::getInstance()->getProxTabTabela().'"><img src="'.PaginaSEI::getInstance()->getDiretorioSvgGlobal().'/alterar.svg" title="Alterar Embalagem" alt="Alterar Embalagem" class="infraImg" /></a>&nbsp;';
      }

      if ($bolAcaoDesativar || $bolAcaoReativar || $bolAcaoExcluir){
        $strId = $arrObjMdCorObjetoDTO[$i]->getNumIdMdCorObjeto();
        $strDescricao = PaginaSEI::getInstance()->formatarParametrosJavaScript(PaginaSEI::tratarHTML($arrObjMdCorObjetoDTO[$i]->getNumIdMdCorTipoObjeto()));
      }

      if ($bolAcaoExcluir){
        $strResultado .= '<a href="'.PaginaSEI::getInstance()->montarAncora($strId).'" onclick="acaoExcluir(\''.$strId.'\',\''.$strDescricao.'\');" tabindex="'.PaginaSEI::getInstance()->getProxTabTabela().'"><img src="'.PaginaSEI::getInstance()->getDiretorioSvgGlobal().'/excluir.svg" title="Excluir Embalagem" alt="Excluir Embalagem" class="infraImg" /></a>&nbsp;';
      }

      $strResultado .= '</td></tr>'."\n";
    }
    $strResultado .= '</table>';
  }
  if ($_GET['acao'] == 'md_cor_objeto_selecionar'){
    $arrComandos[] = '<button type="button" accesskey="C" id="btnFecharSelecao" value="Fechar" onclick="window.close();" class="infraButton">Fe<span class="infraTeclaAtalho">c</span>har</button>';
  }else{
    $arrComandos[] = '<button type="button" accesskey="C" id="btnFechar" value="Fechar" onclick="location.href=\''.SessaoSEI::getInstance()->assinarLink('controlador.php?acao='.PaginaSEI::getInstance()->getAcaoRetorno().'&acao_origem='.$_GET['acao'] . PaginaSEI::getInstance()->montarAncora($_GET['id_md_cor_contrato'])).'\'" class="infraButton">Fe<span class="infraTeclaAtalho">c</span>har</button>';
  }

  $strItensSelMdCorTipoObjeto = MdCorTipoObjetoINT::montarSelectNome('','Todos',$numIdMdCorTipoObjeto);
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

PaginaSEI::getInstance()->fecharStyle();
PaginaSEI::getInstance()->montarJavaScript();
PaginaSEI::getInstance()->fecharHead();
PaginaSEI::getInstance()->abrirBody($strTitulo,'onload="inicializar();"');
?>
<form id="frmMdCorObjetoLista" method="post" action="<?=SessaoSEI::getInstance()->assinarLink('controlador.php?acao='.$_GET['acao'].'&acao_origem='.$_GET['acao'].'&id_md_cor_contrato='.$_GET['id_md_cor_contrato'])?>">
  <?
  PaginaSEI::getInstance()->montarBarraComandosSuperior($arrComandos);
  PaginaSEI::getInstance()->abrirAreaDados();
  ?>

  <?
  PaginaSEI::getInstance()->fecharAreaDados();
  PaginaSEI::getInstance()->montarAreaTabela($strResultado,$numRegistros);
  //PaginaSEI::getInstance()->montarAreaDebug();
  PaginaSEI::getInstance()->montarBarraComandosInferior($arrComandos);
  ?>
</form>
<?
PaginaSEI::getInstance()->fecharBody();
PaginaSEI::getInstance()->fecharHtml();
include_once('md_cor_objeto_lista_js.php');
?>