<?php
try {
  require_once dirname(__FILE__) . '/../../SEI.php';

  session_start();
  SessaoSEI::getInstance()->validarLink();
  SessaoSEI::getInstance()->validarPermissao($_GET['acao']);
  PaginaSEI::getInstance()->setTipoPagina(PaginaSEI::$TIPO_PAGINA_SIMPLES);


  //URL's
  $idMdCorEexpedicaoSolicitada = $_GET['id_md_cor_expedicao_solicitada'];
  $strUrlAcaoForm = SessaoSEI::getInstance()->assinarLink('controlador.php?acao=' . $_GET['acao'] . '&acao_origem=' . $_GET['acao'] . '&id_md_cor_expedicao_solicitada=' . $idMdCorEexpedicaoSolicitada);

  PaginaSEI::getInstance()->salvarCamposPost(array('txtNumeroProcesso', 'txtDocumentoPrincipal', 'txtDataSolicitacao',
    'txtDataExpedicao', 'txtCodigoRastreamento', 'selServicoPostal'));

  switch ($_GET['acao']) {

    case 'md_cor_plp_selecionar_tipo_objeto':
      $mdCorExpedicaoSolicitadaRN = new MdCorExpedicaoSolicitadaRN();
      $mdCorExpedicaoSolicitadaRN->validarTipoEmbalagem($idMdCorEexpedicaoSolicitada);

      $strTitulo = 'Formato de Expedição do Objeto:';
      $arrComandos[] = '<button type="button" accesskey="S" id="BtnSalvar" class="infraButton" onclick="SalvarTipoObjeto();"><span class="infraTeclaAtalho">S</span>alvar</button>';
      $arrComandos[] = '<button type="button" accesskey="C" id="btnFechar" class="infraButton" onclick="infraFecharJanelaModal();">Fe<span class="infraTeclaAtalho">c</span>har</button>';

      /**
       * Consultar o contrato da solicitacao para buscar os tipos de objeto
       */
      $mdCorExpedicaoSolicitadaDTO = new MdCorExpedicaoSolicitadaDTO();
      $mdCorExpedicaoSolicitadaDTO->retNumIdMdCorObjeto();
      $mdCorExpedicaoSolicitadaDTO->retNumIdMdCorTipoObjeto();
      $mdCorExpedicaoSolicitadaDTO->retDblIdMdCorContrato();
      $mdCorExpedicaoSolicitadaDTO->setNumIdMdCorExpedicaoSolicitada($idMdCorEexpedicaoSolicitada);
      $objMdCorExpedicaoSolicitada = $mdCorExpedicaoSolicitadaRN->consultar($mdCorExpedicaoSolicitadaDTO);

      /**
       * Buscar os tipos de objeto do contrato
       */
      $mdCorObjetoRN = new MdCorObjetoRN();
      $mdCorObjetoDTO = new MdCorObjetoDTO();
      $mdCorObjetoDTO->retNumIdMdCorObjeto();
      $mdCorObjetoDTO->retStrNomeTipoObjeto();
      $mdCorObjetoDTO->retNumIdMdCorTipoObjeto();
      $mdCorObjetoDTO->retStrSinObjetoPadrao();
      $mdCorObjetoDTO->setStrSinAtivo('S');
      $mdCorObjetoDTO->setNumIdMdCorContrato($objMdCorExpedicaoSolicitada->getDblIdMdCorContrato());

      $objMdCorObjeto = $mdCorObjetoRN->listar($mdCorObjetoDTO);
      if (!empty($_POST)) {
        $rdFormatoObjeto = $_POST['rdFormatoObjeto'];
        $mdCorExpedicaoSolicitadaDTO->setNumIdMdCorObjeto($rdFormatoObjeto);
        $mdCorExpedicaoSolicitadaRN->alterar($mdCorExpedicaoSolicitadaDTO);
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
PaginaSEI::getInstance()->abrirStyle(); ?>
    #frmExpedicaoSolicitadaUnidade .bloco {float: left;  margin-top: 1%;  margin-right: 1%;}
    #frmExpedicaoSolicitadaUnidade .clear {clear: both;}
    #frmExpedicaoSolicitadaUnidade select:not([multiple]), input[type=text] {width: 250px;  border: .1em solid #666;}
    #frmExpedicaoSolicitadaUnidade label:not([for^=rdo]) {display: block;  white-space: nowrap;}
    #frmExpedicaoSolicitadaUnidade input[id^='txtData'] {width: 70px;}
    #frmExpedicaoSolicitadaUnidade img[id^='imgData'] {vertical-align: middle;}
<?php
PaginaSEI::getInstance()->fecharStyle();
PaginaSEI::getInstance()->montarJavaScript();
PaginaSEI::getInstance()->abrirJavaScript(); ?>
    function inicializar() {
    }

    function SalvarTipoObjeto(){
        document.getElementById('frmTipoObjeto').submit();
    }
<?php if (!empty($_POST)) { ?>
    window.close();
<?php } ?>
<?php
PaginaSEI::getInstance()->fecharJavaScript();
PaginaSEI::getInstance()->fecharHead();
PaginaSEI::getInstance()->abrirBody($strTitulo, 'onload="inicializar();"'); ?>
    <form id="frmTipoObjeto" method="post" action="<?= $strUrlAcaoForm ?>">
      <?php PaginaSEI::getInstance()->abrirAreaDados(); ?>
        <div class="bloco">
          <?php foreach ($objMdCorObjeto as $dadosMdCorObjeto) { ?>
            <?php
            $checked = '';
            if (is_null($objMdCorExpedicaoSolicitada->getNumIdMdCorObjeto())) {
              if ($dadosMdCorObjeto->getStrSinObjetoPadrao() == 'S') {
                $checked = 'checked="checked"';
              }
            } else {
              if ($objMdCorExpedicaoSolicitada->getNumIdMdCorTipoObjeto() == $dadosMdCorObjeto->getNumIdMdCorTipoObjeto()) {
                $checked = 'checked="checked"';
              } else {
                $checked = '';
              }
            }
            ?>
              <input type="radio" id="rdFormatoObjeto" name="rdFormatoObjeto" class="infraRadio"
                <?php echo $checked; ?>
                     value="<?php echo $dadosMdCorObjeto->getNumIdMdCorObjeto(); ?>"
                     tabindex="<?= PaginaSEI::getInstance()->getProxTabDados(); ?>"/> <?php echo $dadosMdCorObjeto->getStrNomeTipoObjeto(); ?>
              <br/>
          <?php } ?>
        </div>
      <?php PaginaSEI::getInstance()->montarBarraComandosInferior($arrComandos); ?>
      <?php PaginaSEI::getInstance()->fecharAreaDados(); ?>
    </form>

<?php
PaginaSEI::getInstance()->fecharBody();
PaginaSEI::getInstance()->fecharHtml();