<?
/**
* TRIBUNAL REGIONAL FEDERAL DA 4ª REGIÃO
*
* 07/06/2017 - criado por marcelo.cast
*
* Versão do Gerador de Código: 1.40.1
*
* Versão no SVN: $Id$
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

  PaginaSEI::getInstance()->verificarSelecao('md_cor_expedicao_solicitada_selecionar');

  SessaoSEI::getInstance()->validarPermissao($_GET['acao']);

  $objMdCorExpedicaoSolicitadaDTO = new MdCorExpedicaoSolicitadaDTO();

  $strDesabilitar = '';

  $arrComandos = array();

  switch($_GET['acao']){
    case 'md_cor_expedicao_solicitada_cadastrar':
      $strTitulo = 'Nov ';
      $arrComandos[] = '<button type="submit" accesskey="S" name="sbmCadastrarMdCorExpedicaoSolicitada" value="Salvar" class="infraButton"><span class="infraTeclaAtalho">S</span>alvar</button>';
      $arrComandos[] = '<button type="button" accesskey="C" name="btnCancelar" id="btnCancelar" value="Cancelar" onclick="location.href=\''.SessaoSEI::getInstance()->assinarLink('controlador.php?acao='.PaginaSEI::getInstance()->getAcaoRetorno().'&acao_origem='.$_GET['acao']).'\';" class="infraButton"><span class="infraTeclaAtalho">C</span>ancelar</button>';

      $objMdCorExpedicaoSolicitadaDTO->setNumIdMdCorExpedicaoSolicitada(null);
      $objMdCorExpedicaoSolicitadaDTO->setStrSinNecessitaAr(PaginaSEI::getInstance()->getCheckbox($_POST['chkSinNecessitaAr']));
      $objMdCorExpedicaoSolicitadaDTO->setDblIdDocumentoPrincipal($_POST['txtIdDocumentoPrincipal']);
      $objMdCorExpedicaoSolicitadaDTO->setNumIdMdCorServicoPostal($_POST['txtIdMdCorServicoPostal']);
      $objMdCorExpedicaoSolicitadaDTO->setNumIdUnidade($_POST['txtIdUnidade']);
      $objMdCorExpedicaoSolicitadaDTO->setDthDataSolicitacao($_POST['txtDataSolicitacao']);
      $objMdCorExpedicaoSolicitadaDTO->setDthDataExpedicao($_POST['txtDataExpedicao']);
      $objMdCorExpedicaoSolicitadaDTO->setNumIdUsuarioSolicitante($_POST['txtIdUsuarioSolicitante']);
      $objMdCorExpedicaoSolicitadaDTO->setNumIdUsuarioExpAutorizador($_POST['txtIdUsuarioExpAutorizador']);
      $objMdCorExpedicaoSolicitadaDTO->setStrCodigoRastreamento($_POST['txtCodigoRastreamento']);

      if (isset($_POST['sbmCadastrarMdCorExpedicaoSolicitada'])) {
        try{
          $objMdCorExpedicaoSolicitadaRN = new MdCorExpedicaoSolicitadaRN();
          $objMdCorExpedicaoSolicitadaDTO = $objMdCorExpedicaoSolicitadaRN->cadastrar($objMdCorExpedicaoSolicitadaDTO);
          PaginaSEI::getInstance()->adicionarMensagem(' "'.$objMdCorExpedicaoSolicitadaDTO->getNumIdMdCorExpedicaoSolicitada().'" cadastrad com sucesso.');
          header('Location: '.SessaoSEI::getInstance()->assinarLink('controlador.php?acao='.PaginaSEI::getInstance()->getAcaoRetorno().'&acao_origem='.$_GET['acao'].'&id_md_cor_expedicao_solicitada='.$objMdCorExpedicaoSolicitadaDTO->getNumIdMdCorExpedicaoSolicitada().PaginaSEI::getInstance()->montarAncora($objMdCorExpedicaoSolicitadaDTO->getNumIdMdCorExpedicaoSolicitada())));
          die;
        }catch(Exception $e){
          PaginaSEI::getInstance()->processarExcecao($e);
        }
      }
      break;

    case 'md_cor_expedicao_solicitada_alterar':
      $strTitulo = 'Alterar ';
      $arrComandos[] = '<button type="submit" accesskey="S" name="sbmAlterarMdCorExpedicaoSolicitada" value="Salvar" class="infraButton"><span class="infraTeclaAtalho">S</span>alvar</button>';
      $strDesabilitar = 'disabled="disabled"';

      if (isset($_GET['id_md_cor_expedicao_solicitada'])){
        $objMdCorExpedicaoSolicitadaDTO->setNumIdMdCorExpedicaoSolicitada($_GET['id_md_cor_expedicao_solicitada']);
        $objMdCorExpedicaoSolicitadaDTO->retTodos();
        $objMdCorExpedicaoSolicitadaRN = new MdCorExpedicaoSolicitadaRN();
        $objMdCorExpedicaoSolicitadaDTO = $objMdCorExpedicaoSolicitadaRN->consultar($objMdCorExpedicaoSolicitadaDTO);
        if ($objMdCorExpedicaoSolicitadaDTO==null){
          throw new InfraException("Registro não encontrado.");
        }
      } else {
        $objMdCorExpedicaoSolicitadaDTO->setNumIdMdCorExpedicaoSolicitada($_POST['hdnIdMdCorExpedicaoSolicitada']);
        $objMdCorExpedicaoSolicitadaDTO->setStrSinNecessitaAr(PaginaSEI::getInstance()->getCheckbox($_POST['chkSinNecessitaAr']));
        $objMdCorExpedicaoSolicitadaDTO->setDblIdDocumentoPrincipal($_POST['txtIdDocumentoPrincipal']);
        $objMdCorExpedicaoSolicitadaDTO->setNumIdMdCorServicoPostal($_POST['txtIdMdCorServicoPostal']);
        $objMdCorExpedicaoSolicitadaDTO->setNumIdUnidade($_POST['txtIdUnidade']);
        $objMdCorExpedicaoSolicitadaDTO->setDthDataSolicitacao($_POST['txtDataSolicitacao']);
        $objMdCorExpedicaoSolicitadaDTO->setDthDataExpedicao($_POST['txtDataExpedicao']);
        $objMdCorExpedicaoSolicitadaDTO->setNumIdUsuarioSolicitante($_POST['txtIdUsuarioSolicitante']);
        $objMdCorExpedicaoSolicitadaDTO->setNumIdUsuarioExpAutorizador($_POST['txtIdUsuarioExpAutorizador']);
        $objMdCorExpedicaoSolicitadaDTO->setStrCodigoRastreamento($_POST['txtCodigoRastreamento']);
      }

      $arrComandos[] = '<button type="button" accesskey="C" name="btnCancelar" id="btnCancelar" value="Cancelar" onclick="location.href=\''.SessaoSEI::getInstance()->assinarLink('controlador.php?acao='.PaginaSEI::getInstance()->getAcaoRetorno().'&acao_origem='.$_GET['acao'].PaginaSEI::getInstance()->montarAncora($objMdCorExpedicaoSolicitadaDTO->getNumIdMdCorExpedicaoSolicitada())).'\';" class="infraButton"><span class="infraTeclaAtalho">C</span>ancelar</button>';

      if (isset($_POST['sbmAlterarMdCorExpedicaoSolicitada'])) {
        try{
          $objMdCorExpedicaoSolicitadaRN = new MdCorExpedicaoSolicitadaRN();
          $objMdCorExpedicaoSolicitadaRN->alterar($objMdCorExpedicaoSolicitadaDTO);
          PaginaSEI::getInstance()->adicionarMensagem(' "'.$objMdCorExpedicaoSolicitadaDTO->getNumIdMdCorExpedicaoSolicitada().'" alterad com sucesso.');
          header('Location: '.SessaoSEI::getInstance()->assinarLink('controlador.php?acao='.PaginaSEI::getInstance()->getAcaoRetorno().'&acao_origem='.$_GET['acao'].PaginaSEI::getInstance()->montarAncora($objMdCorExpedicaoSolicitadaDTO->getNumIdMdCorExpedicaoSolicitada())));
          die;
        }catch(Exception $e){
          PaginaSEI::getInstance()->processarExcecao($e);
        }
      }
      break;

    case 'md_cor_expedicao_solicitada_consultar':
      $strTitulo = 'Consultar ';
      $arrComandos[] = '<button type="button" accesskey="F" name="btnFechar" value="Fechar" onclick="location.href=\''.SessaoSEI::getInstance()->assinarLink('controlador.php?acao='.PaginaSEI::getInstance()->getAcaoRetorno().'&acao_origem='.$_GET['acao'].PaginaSEI::getInstance()->montarAncora($_GET['id_md_cor_expedicao_solicitada'])).'\';" class="infraButton">Fe<span class="infraTeclaAtalho">c</span>har</button>';
      $objMdCorExpedicaoSolicitadaDTO->setNumIdMdCorExpedicaoSolicitada($_GET['id_md_cor_expedicao_solicitada']);
      $objMdCorExpedicaoSolicitadaDTO->setBolExclusaoLogica(false);
      $objMdCorExpedicaoSolicitadaDTO->retTodos();
      $objMdCorExpedicaoSolicitadaRN = new MdCorExpedicaoSolicitadaRN();
      $objMdCorExpedicaoSolicitadaDTO = $objMdCorExpedicaoSolicitadaRN->consultar($objMdCorExpedicaoSolicitadaDTO);
      if ($objMdCorExpedicaoSolicitadaDTO===null){
        throw new InfraException("Registro não encontrado.");
      }
      break;

    default:
      throw new InfraException("Ação '".$_GET['acao']."' não reconhecida.");
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
#divSinNecessitaAr {position:absolute;left:0%;top:20%;}

#lblIdDocumentoPrincipal {position:absolute;left:0%;top:0%;width:20%;}
#txtIdDocumentoPrincipal {position:absolute;left:0%;top:40%;width:20%;}

#lblIdMdCorServicoPostal {position:absolute;left:0%;top:0%;width:11%;}
#txtIdMdCorServicoPostal {position:absolute;left:0%;top:40%;width:11%;}

#lblIdUnidade {position:absolute;left:0%;top:0%;width:11%;}
#txtIdUnidade {position:absolute;left:0%;top:40%;width:11%;}

#lblDataSolicitacao {position:absolute;left:0%;top:0%;width:25%;}
#txtDataSolicitacao {position:absolute;left:0%;top:40%;width:25%;}
#imgCalDataSolicitacao {position:absolute;left:26%;top:40%;}

#lblDataExpedicao {position:absolute;left:0%;top:0%;width:25%;}
#txtDataExpedicao {position:absolute;left:0%;top:40%;width:25%;}
#imgCalDataExpedicao {position:absolute;left:26%;top:40%;}

#lblIdUsuarioSolicitante {position:absolute;left:0%;top:0%;width:11%;}
#txtIdUsuarioSolicitante {position:absolute;left:0%;top:40%;width:11%;}

#lblIdUsuarioExpAutorizador {position:absolute;left:0%;top:0%;width:11%;}
#txtIdUsuarioExpAutorizador {position:absolute;left:0%;top:40%;width:11%;}

#lblCodigoRastreamento {position:absolute;left:0%;top:0%;width:45%;}
#txtCodigoRastreamento {position:absolute;left:0%;top:40%;width:45%;}

<?if(0){?></style><?}?>
<?
PaginaSEI::getInstance()->fecharStyle();
PaginaSEI::getInstance()->montarJavaScript();
PaginaSEI::getInstance()->abrirJavaScript();
?>
<?if(0){?><script type="text/javascript"><?}?>

function inicializar(){
  if ('<?=$_GET['acao']?>'=='md_cor_expedicao_solicitada_cadastrar'){
    document.getElementById('txtSinNecessitaAr').focus();
  } else if ('<?=$_GET['acao']?>'=='md_cor_expedicao_solicitada_consultar'){
    infraDesabilitarCamposAreaDados();
  }else{
    document.getElementById('btnCancelar').focus();
  }
  infraEfeitoTabelas();
}

function validarCadastro() {
  if (infraTrim(document.getElementById('txtIdDocumentoPrincipal').value)=='') {
    alert('Informe o .');
    document.getElementById('txtIdDocumentoPrincipal').focus();
    return false;
  }

  if (infraTrim(document.getElementById('txtIdMdCorServicoPostal').value)=='') {
    alert('Informe o .');
    document.getElementById('txtIdMdCorServicoPostal').focus();
    return false;
  }

  if (infraTrim(document.getElementById('txtIdUnidade').value)=='') {
    alert('Informe a .');
    document.getElementById('txtIdUnidade').focus();
    return false;
  }

  if (infraTrim(document.getElementById('txtDataSolicitacao').value)=='') {
    alert('Informe a .');
    document.getElementById('txtDataSolicitacao').focus();
    return false;
  }

  if (infraTrim(document.getElementById('txtIdUsuarioSolicitante').value)=='') {
    alert('Informe o .');
    document.getElementById('txtIdUsuarioSolicitante').focus();
    return false;
  }

  return true;
}

function OnSubmitForm() {
  return validarCadastro();
}

<?if(0){?></script><?}?>
<?
PaginaSEI::getInstance()->fecharJavaScript();
PaginaSEI::getInstance()->fecharHead();
PaginaSEI::getInstance()->abrirBody($strTitulo,'onload="inicializar();"');
?>
<form id="frmMdCorExpedicaoSolicitadaCadastro" method="post" onsubmit="return OnSubmitForm();" action="<?=SessaoSEI::getInstance()->assinarLink('controlador.php?acao='.$_GET['acao'].'&acao_origem='.$_GET['acao'])?>">
<?
PaginaSEI::getInstance()->montarBarraComandosSuperior($arrComandos);
//PaginaSEI::getInstance()->montarAreaValidacao();
PaginaSEI::getInstance()->abrirAreaDados('4.5em');
?>
  <div id="divSinNecessitaAr" class="infraDivCheckbox">
    <input type="checkbox" id="chkSinNecessitaAr" name="chkSinNecessitaAr" class="infraCheckbox form-check-input" <?=PaginaSEI::getInstance()->setCheckbox($objMdCorExpedicaoSolicitadaDTO->getStrSinNecessitaAr())?>  tabindex="<?=PaginaSEI::getInstance()->getProxTabDados()?>" />
    <label id="lblSinNecessitaAr" for="chkSinNecessitaAr" accesskey="" class="infraLabelCheckbox">sin_necessita_ar</label>
  </div>
<?
PaginaSEI::getInstance()->fecharAreaDados();
PaginaSEI::getInstance()->abrirAreaDados('4.5em');
?>
  <label id="lblIdDocumentoPrincipal" for="txtIdDocumentoPrincipal" accesskey="" class="infraLabelObrigatorio">id_documento_principal:</label>
  <input type="text" id="txtIdDocumentoPrincipal" name="txtIdDocumentoPrincipal" onkeypress="return infraMascaraNumero(this, event)" class="infraText" value="<?=PaginaSEI::tratarHTML($objMdCorExpedicaoSolicitadaDTO->getDblIdDocumentoPrincipal());?>" maxlength="20" tabindex="<?=PaginaSEI::getInstance()->getProxTabDados()?>" />
<?
PaginaSEI::getInstance()->fecharAreaDados();
PaginaSEI::getInstance()->abrirAreaDados('4.5em');
?>
  <label id="lblIdMdCorServicoPostal" for="txtIdMdCorServicoPostal" accesskey="" class="infraLabelObrigatorio">id_md_cor_servico_postal:</label>
  <input type="text" id="txtIdMdCorServicoPostal" name="txtIdMdCorServicoPostal" onkeypress="return infraMascaraNumero(this, event)" class="infraText" value="<?=PaginaSEI::tratarHTML($objMdCorExpedicaoSolicitadaDTO->getNumIdMdCorServicoPostal());?>" maxlength="11" tabindex="<?=PaginaSEI::getInstance()->getProxTabDados()?>" />
<?
PaginaSEI::getInstance()->fecharAreaDados();
PaginaSEI::getInstance()->abrirAreaDados('4.5em');
?>
  <label id="lblIdUnidade" for="txtIdUnidade" accesskey="" class="infraLabelObrigatorio">id_unidade:</label>
  <input type="text" id="txtIdUnidade" name="txtIdUnidade" onkeypress="return infraMascaraNumero(this, event)" class="infraText" value="<?=PaginaSEI::tratarHTML($objMdCorExpedicaoSolicitadaDTO->getNumIdUnidade());?>" maxlength="11" tabindex="<?=PaginaSEI::getInstance()->getProxTabDados()?>" />
<?
PaginaSEI::getInstance()->fecharAreaDados();
PaginaSEI::getInstance()->abrirAreaDados('4.5em');
?>
  <label id="lblDataSolicitacao" for="txtDataSolicitacao" accesskey="" class="infraLabelObrigatorio">data_solicitacao:</label>
  <input type="text" id="txtDataSolicitacao" name="txtDataSolicitacao" class="infraText" value="<?=PaginaSEI::tratarHTML($objMdCorExpedicaoSolicitadaDTO->getDthDataSolicitacao());?>" onkeypress="return infraMascaraTexto(this,event);" tabindex="<?=PaginaSEI::getInstance()->getProxTabDados()?>" />
  <img id="imgCalDataSolicitacao" title="Selecionar " alt="Selecionar " src="<?=PaginaSEI::getInstance()->getDiretorioImagensGlobal()?>/calendario.gif" class="infraImg" onclick="infraCalendario('txtDataSolicitacao',this);" />
<?
PaginaSEI::getInstance()->fecharAreaDados();
PaginaSEI::getInstance()->abrirAreaDados('4.5em');
?>
  <label id="lblDataExpedicao" for="txtDataExpedicao" accesskey="" class="infraLabelOpcional">data_expedicao:</label>
  <input type="text" id="txtDataExpedicao" name="txtDataExpedicao" class="infraText" value="<?=PaginaSEI::tratarHTML($objMdCorExpedicaoSolicitadaDTO->getDthDataExpedicao());?>" onkeypress="return infraMascaraTexto(this,event);" tabindex="<?=PaginaSEI::getInstance()->getProxTabDados()?>" />
  <img id="imgCalDataExpedicao" title="Selecionar " alt="Selecionar " src="<?=PaginaSEI::getInstance()->getDiretorioImagensGlobal()?>/calendario.gif" class="infraImg" onclick="infraCalendario('txtDataExpedicao',this);" />
<?
PaginaSEI::getInstance()->fecharAreaDados();
PaginaSEI::getInstance()->abrirAreaDados('4.5em');
?>
  <label id="lblIdUsuarioSolicitante" for="txtIdUsuarioSolicitante" accesskey="" class="infraLabelObrigatorio">id_usuario_solicitante:</label>
  <input type="text" id="txtIdUsuarioSolicitante" name="txtIdUsuarioSolicitante" onkeypress="return infraMascaraNumero(this, event)" class="infraText" value="<?=PaginaSEI::tratarHTML($objMdCorExpedicaoSolicitadaDTO->getNumIdUsuarioSolicitante());?>" maxlength="11" tabindex="<?=PaginaSEI::getInstance()->getProxTabDados()?>" />
<?
PaginaSEI::getInstance()->fecharAreaDados();
PaginaSEI::getInstance()->abrirAreaDados('4.5em');
?>
  <label id="lblIdUsuarioExpAutorizador" for="txtIdUsuarioExpAutorizador" accesskey="" class="infraLabelOpcional">id_usuario_exp_autorizador:</label>
  <input type="text" id="txtIdUsuarioExpAutorizador" name="txtIdUsuarioExpAutorizador" onkeypress="return infraMascaraNumero(this, event)" class="infraText" value="<?=PaginaSEI::tratarHTML($objMdCorExpedicaoSolicitadaDTO->getNumIdUsuarioExpAutorizador());?>" maxlength="11" tabindex="<?=PaginaSEI::getInstance()->getProxTabDados()?>" />
<?
PaginaSEI::getInstance()->fecharAreaDados();
PaginaSEI::getInstance()->abrirAreaDados('4.5em');
?>
  <label id="lblCodigoRastreamento" for="txtCodigoRastreamento" accesskey="" class="infraLabelOpcional">codigo_rastreamento:</label>
  <input type="text" id="txtCodigoRastreamento" name="txtCodigoRastreamento" class="infraText" value="<?=PaginaSEI::tratarHTML($objMdCorExpedicaoSolicitadaDTO->getStrCodigoRastreamento());?>" onkeypress="return infraMascaraTexto(this,event,45);" maxlength="45" tabindex="<?=PaginaSEI::getInstance()->getProxTabDados()?>" />
<?
PaginaSEI::getInstance()->fecharAreaDados();
?>
  <input type="hidden" id="hdnIdMdCorExpedicaoSolicitada" name="hdnIdMdCorExpedicaoSolicitada" value="<?=$objMdCorExpedicaoSolicitadaDTO->getNumIdMdCorExpedicaoSolicitada();?>" />
  <?
  //PaginaSEI::getInstance()->montarAreaDebug();
  PaginaSEI::getInstance()->montarBarraComandosInferior($arrComandos);
  ?>
</form>
<?
PaginaSEI::getInstance()->fecharBody();
PaginaSEI::getInstance()->fecharHtml();
?>