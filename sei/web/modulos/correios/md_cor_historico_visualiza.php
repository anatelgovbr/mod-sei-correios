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
      case 'md_cor_historico_visualizar':
        $idSolicitacao = $_GET['id_solicitacao'];

        $mdCorArCobrancaRN = new MdCorArCobrancaRN();
        $mdCorArCobrancaDTO = new MdCorArCobrancaDTO();
        $mdCorArCobrancaDTO->retDblIdDocumentoCobranca();
        $mdCorArCobrancaDTO->retStrProtocoloFormatadoCobranca();
        $mdCorArCobrancaDTO->retStrNumeroDocumentoCobranca();
        $mdCorArCobrancaDTO->retStrNomeSerieCobranca();
        $mdCorArCobrancaDTO->setNumIdMdCorExpedicaoSolicitada($idSolicitacao);
        $mdCorArCobrancaDTO->setOrdDthDtMdCorArCobranca(InfraDTO::$TIPO_ORDENACAO_DESC);
        $arrMdCorArCobrancaDTO = $mdCorArCobrancaRN->listar($mdCorArCobrancaDTO);
        $numRegistros = $mdCorArCobrancaRN->contar($mdCorArCobrancaDTO);

        $strResultado .= '<table width="100%" class="infraTable" summary="' . $strSumarioTabela . '">' . "\n";
        $strResultado .= '<caption class="infraCaption">' . PaginaSEI::getInstance()->gerarCaptionTabela($strCaptionTabela, $numRegistros) . '</caption>';
        $strResultado .= '<tr>';
        $strResultado .= '<th class="infraTh" style="width: 10%"></th>' . "\n";
        $strResultado .= '<th class="infraTh" style="width: 90%">Número do Documento</th>' . "\n";
        $strResultado .= '</tr>' . "\n";

        $strCssTr = '';
        foreach ($arrMdCorArCobrancaDTO as $chave=>$resultado) {
          $strUrlDocumento = SessaoSEI::getInstance()->assinarLink('controlador.php?acao=documento_visualizar&acao_origem=procedimento_visualizar&id_documento=' . $resultado->getDblIdDocumentoCobranca());
          $strCssTr = $strCssTr == '<tr class="infraTrClara">' ? '<tr class="infraTrEscura">' : '<tr class="infraTrClara">';
          $strResultado .= $strCssTr;
          $strResultado .= '<td>';
          $strResultado .= ++$chave;
          $strResultado .= '</td>';
          $strResultado .= '<td>';
          $strResultado .= '<a class="protocoloNormal" style="font-size: 1.0em !important; font-size:1em" href="' . $strUrlDocumento . '" target="_blank">';
          $strResultado .= $resultado->getStrNomeSerieCobranca() . ' ' . $resultado->getStrNumeroDocumentoCobranca() . '  (SEI Nº ' . $resultado->getStrProtocoloFormatadoCobranca() . ')';
          $strResultado .= '</a>';
          $strResultado .= '</td>';
          $strResultado .= '</tr>' . "\n";
        }

        $strResultado .= '</table>' . "\n";
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
#slTipoRetorno {position:absolute; left:0; top:50%;}
<?
  PaginaSEI::getInstance()->fecharStyle();
  PaginaSEI::getInstance()->montarJavaScript();
  require_once('md_cor_historico_visualiza_js.php');
  PaginaSEI::getInstance()->fecharHead();
  PaginaSEI::getInstance()->abrirAreaDados();
  PaginaSEI::getInstance()->montarAreaTabela($strResultado, $numRegistros);
  PaginaSEI::getInstance()->fecharAreaDados();
  PaginaSEI::getInstance()->fecharBody();
  PaginaSEI::getInstance()->fecharHtml();
?>
