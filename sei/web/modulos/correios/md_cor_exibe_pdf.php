<?php
/**
 * Created by PhpStorm.
 * User: augusto.cast
 * Date: 26/10/2017
 * Time: 16:38
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

  SessaoSEI::getInstance()->validarAuditarPermissao($_GET['acao']);

  switch($_GET['acao']){
    case 'md_cor_exibir_pdf':
      $strNomeDownload = $_GET['nome_download'];
      if (InfraString::isBolVazia($strNomeDownload)){
        $strNomeDownload = $_GET['nome_arquivo'];
      }

      $arquivo = DIR_SEI_TEMP.'/'.$_GET['nome_arquivo'];
      header("Content-type: application/pdf");
      header("Content-Disposition", "inline; filename=\"$strNomeDownload\"");
      @readfile($arquivo);
      break;

    default:
      throw new InfraException("Aчуo '".$_GET['acao']."' nуo reconhecida.");
  }

}catch(Exception $e){
  PaginaSEI::getInstance()->setTipoPagina(InfraPagina::$TIPO_PAGINA_SIMPLES);
  PaginaSEI::getInstance()->processarExcecao($e);
}
?>