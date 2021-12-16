<?php

  /**
   * ANATEL
   *
   * 18/10/2017 - criado por Ellyson de Jesus Silva
   *
   */

  try {
    require_once dirname(__FILE__) . '/../../SEI.php';

    session_start();

    SessaoSEI::getInstance()->validarLink();

    SessaoSEI::getInstance()->validarPermissao($_GET['acao']);

    PaginaSEI::getInstance()->setTipoPagina(InfraPagina::$TIPO_PAGINA_SIMPLES);

    /**
     * Montar array de comandos superior
     */
    $arrComandos = array();
    switch ($_GET['acao']) {
      case 'md_cor_cobranca_informar':
        $strTitulo = 'Informar Documento de Cobrança';
        $arrComandos[] = '<button type="button" accesskey="o" id="btnFechar" onclick="confirmarGeraCobranca()" class="infraButton">
                                    C<span class="infraTeclaAtalho">o</span>nfirma
                              </button>';
        $arrComandos[] = '<button type="button" accesskey="c" id="btnFechar" onclick="window.close();" class="infraButton">
                                    <span class="infraTeclaAtalho">C</span>ancelar
                              </button>';

        if (!empty($_POST['hdnCodIdSolicitacao'])) {
          $arrInforma['hdnCodIdSolicitacao'] = $_POST['hdnCodIdSolicitacao'];
          $arrInforma['hdnIdDocumento'] = $_POST['hdnIdDocumento'];

          $mdCorArCobrancaRN = new MdCorArCobrancaRN();
          $mdCorArCobrancaRN->gerarDocumentoCobrancaInformar($arrInforma);

          echo '<script> window.opener.location.reload();window.close();</script>';
        }
        break;
      default:
        throw new InfraException("Ação '" . $_GET['acao'] . "' não reconhecida.");
    }

  } catch (Exception $e) {
    PaginaSEI::getInstance()->processarExcecao($e);
  }

  PaginaSEI::getInstance()->montarDocType();
  PaginaSEI::getInstance()->abrirHtml();
  PaginaSEI::getInstance()->abrirHead();
  PaginaSEI::getInstance()->montarMeta();
  PaginaSEI::getInstance()->montarTitle(':: ' . PaginaSEI::getInstance()->getStrNomeSistema() . ' - ' . $strTitulo . ' ::');
  PaginaSEI::getInstance()->montarStyle();
  PaginaSEI::getInstance()->abrirStyle();
?>
#txtNumeroDocumento {position:absolute; left:0; top:50%;}
<?
  PaginaSEI::getInstance()->fecharStyle();
  PaginaSEI::getInstance()->montarJavaScript();
  require_once('md_cor_retorno_ar_informa_cobranca_js.php');
  PaginaSEI::getInstance()->fecharHead();
  PaginaSEI::getInstance()->abrirBody($strTitulo, 'onload="inicializar();"');
  PaginaSEI::getInstance()->abrirAreaDados();
?>
<form id="frmConsulta" method="post" action="">
  <?
    PaginaSEI::getInstance()->abrirAreaDados('4em');
  ?>
    <label class="infraLabelOpcional">Informar documento de cobrança</label>
  <?php
    PaginaSEI::getInstance()->fecharAreaDados();
    PaginaSEI::getInstance()->abrirAreaDados('4em');
  ?>
    <label id="lblNumeroDocumento" for="txtNumeroDocumento" class="infraLabelOpcional">Número do documento:</label>
    <input type="text"
           id="txtNumeroDocumento"
           name="txtNumeroDocumento"
           class="infraText"
           onblur="buscaDocumento(this)"
           tabindex="<?= PaginaSEI::getInstance()->getProxTabDados() ?>"/>
    <input type="hidden" name="hdnCodIdSolicitacao" id="hdnCodIdSolicitacao"/>
    <input type="hidden" name="hdnIdDocumento" id="hdnIdDocumento"/>
  <?php
    PaginaSEI::getInstance()->fecharAreaDados();
    PaginaSEI::getInstance()->abrirAreaDados('3em');
  ?>
    <br/>
    <label id="lblDocumentoRetorno"  class="infraLabelOpcional"></label>
  <?
    PaginaSEI::getInstance()->fecharAreaDados();
  ?>
</form>
<?php
  PaginaSEI::getInstance()->fecharAreaDados();
  PaginaSEI::getInstance()->montarBarraComandosInferior($arrComandos);
  PaginaSEI::getInstance()->fecharBody();
  PaginaSEI::getInstance()->fecharHtml();
?>
