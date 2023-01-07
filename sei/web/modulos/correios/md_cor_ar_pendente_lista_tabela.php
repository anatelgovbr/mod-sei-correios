<?php
  $mdCorParametroArRN = new MdCorParametroArRN();
  $mdCorParametroArDTO = new MdCorParametroArDTO();
  $mdCorParametroArDTO->retStrNuDiasRetornoAr();
  $mdCorParametroArDTO->retStrNuDiasCobrancaAr();

  $arrParametro = $mdCorParametroArRN->consultar($mdCorParametroArDTO);
  $nuDiasRetorno = $arrParametro->getStrNuDiasRetornoAr();
  $nuDiasCobranca = $arrParametro->getStrNuDiasCobrancaAr();

  $mdCorExpedicaoSolicitadaRN = new MdCorExpedicaoSolicitadaRN();
  $mdCorExpedicaoSolicitadaDTO = new MdCorExpedicaoSolicitadaDTO();

  $dtPermitida = InfraData::calcularData($nuDiasRetorno, InfraData::$UNIDADE_DIAS, InfraData::$SENTIDO_ATRAS);

  $txtCodigoRastreio = PaginaSEI::getInstance()->recuperarCampo('txtCodigoRastreio');
  if ($txtCodigoRastreio != '') {
    $mdCorExpedicaoSolicitadaDTO->setStrCodigoRastreamento('%' . $txtCodigoRastreio . '%', InfraDTO::$OPER_LIKE);
  }

  $selStatus = PaginaSEI::getInstance()->recuperarCampo('selStatus');
  if ($selStatus != '' && $selStatus != 'null') {
    $statusPesquisa = $selStatus;
    $mdCorExpedicaoSolicitadaDTO->setStrStatusCobranca($statusPesquisa);
    if($statusPesquisa  == MdCorRetornoArRN::$STA_FORA_PRAZO_COBRANCA){
      $dtPermitidaPesquisa = InfraData::calcularData($nuDiasCobranca, InfraData::$UNIDADE_DIAS, InfraData::$SENTIDO_ATRAS);

      $mdCorExpedicaoSolicitadaDTO->adicionarCriterio(array('StatusCobranca', 'DataExpedicao'),
        array(InfraDTO::$OPER_IGUAL, InfraDTO::$OPER_MENOR),
        array(MdCorRetornoArRN::$STA_PENDENTE_RETORNO_SEM_COBRANCA, $dtPermitidaPesquisa),
        array(InfraDTO::$OPER_LOGICO_AND)
      );

    }elseif ($statusPesquisa  == MdCorRetornoArRN::$STA_PENDENTE_RETORNO_SEM_COBRANCA){
      $dtPermitidaPesquisa = InfraData::calcularData($nuDiasCobranca, InfraData::$UNIDADE_DIAS, InfraData::$SENTIDO_ATRAS);

      $mdCorExpedicaoSolicitadaDTO->adicionarCriterio(array('StatusCobranca', 'DataExpedicao'),
        array(InfraDTO::$OPER_IGUAL, InfraDTO::$OPER_MAIOR_IGUAL),
        array(MdCorRetornoArRN::$STA_PENDENTE_RETORNO_SEM_COBRANCA, $dtPermitidaPesquisa),
        array(InfraDTO::$OPER_LOGICO_AND)
      );
    }
  }

  $txtDocumentoPrincipal = PaginaSEI::getInstance()->recuperarCampo('txtDocumentoPrincipal');
  if ( $txtDocumentoPrincipal != '' ) {
    $txtDocumento = '%' . $txtDocumentoPrincipal . '%';
    $mdCorExpedicaoSolicitadaDTO->adicionarCriterio(array('ProtocoloFormatadoDocumento', 'NomeSerie', 'NumeroDocumento'),
      array(InfraDTO::$OPER_LIKE, InfraDTO::$OPER_LIKE, InfraDTO::$OPER_LIKE),
      array($txtDocumento, $txtDocumento, $txtDocumento),
      array(InfraDTO::$OPER_LOGICO_OR, InfraDTO::$OPER_LOGICO_OR));
  }

  $txtEndereco = PaginaSEI::getInstance()->recuperarCampo('txtEndereco');
  if ( $txtEndereco != '' ) {
    $strEndereco = '%' . $txtEndereco . '%';

    $mdCorExpedicaoSolicitadaDTO->adicionarCriterio(array('EnderecoDestinatario', 'BairroDestinatario', 'NomeCidadeDestinatario', 'SiglaUfDestinatario'),
      array(InfraDTO::$OPER_LIKE, InfraDTO::$OPER_LIKE, InfraDTO::$OPER_LIKE, InfraDTO::$OPER_LIKE),
      array($strEndereco, $strEndereco, $strEndereco, $strEndereco),
      array(InfraDTO::$OPER_LOGICO_OR, InfraDTO::$OPER_LOGICO_OR, InfraDTO::$OPER_LOGICO_OR));
  }

  $txtDestinatario = PaginaSEI::getInstance()->recuperarCampo('txtDestinatario');
  if ( $txtDestinatario != '') {
    $mdCorExpedicaoSolicitadaDTO->setStrNomeDestinatario('%' . $txtDestinatario . '%', InfraDTO::$OPER_LIKE);
  }

  $txtDiasAtraso = PaginaSEI::getInstance()->recuperarCampo('txtDiasAtraso');
  if ( $txtDiasAtraso != '') {
    $nuDiasAtraso = $txtDiasAtraso;
    $dtPermitida = InfraData::calcularData($nuDiasAtraso, InfraData::$UNIDADE_DIAS, InfraData::$SENTIDO_ATRAS);
    $mdCorExpedicaoSolicitadaDTO->setDthDataExpedicao($dtPermitida, InfraDTO::$OPER_IGUAL);
  } else {
    $mdCorExpedicaoSolicitadaDTO->setDthDataExpedicao($dtPermitida, InfraDTO::$OPER_MENOR_IGUAL);
  }

  $mdCorExpedicaoSolicitadaDTO->retStrCodigoRastreamento();
  $mdCorExpedicaoSolicitadaDTO->retStrNomeDestinatario();
  $mdCorExpedicaoSolicitadaDTO->retStrEnderecoDestinatario();
  $mdCorExpedicaoSolicitadaDTO->retStrBairroDestinatario();
  $mdCorExpedicaoSolicitadaDTO->retStrCepDestinatario();
  $mdCorExpedicaoSolicitadaDTO->retStrComplementoDestinatario();
  $mdCorExpedicaoSolicitadaDTO->retStrNomeCidadeDestinatario();
  $mdCorExpedicaoSolicitadaDTO->retStrSiglaUfDestinatario();
  $mdCorExpedicaoSolicitadaDTO->retStrProtocoloFormatadoDocumento();
  $mdCorExpedicaoSolicitadaDTO->retStrNomeSerie();
  $mdCorExpedicaoSolicitadaDTO->retDthDataSolicitacao();
  $mdCorExpedicaoSolicitadaDTO->retDthDataExpedicao();
  $mdCorExpedicaoSolicitadaDTO->retStrNumeroDocumento();
  $mdCorExpedicaoSolicitadaDTO->retStrStatusCobranca();
  $mdCorExpedicaoSolicitadaDTO->retDblIdDocumentoPrincipal();
  $mdCorExpedicaoSolicitadaDTO->retNumIdMdCorExpedicaoSolicitada();
  $mdCorExpedicaoSolicitadaDTO->retStrSinEnderecoAssociado();
  $mdCorExpedicaoSolicitadaDTO->retNumIdContatoDestinatario();

  $mdCorExpedicaoSolicitadaDTO->setStrSinRecebido('N');
  $mdCorExpedicaoSolicitadaDTO->setStrSinNecessitaAr('S');
  $mdCorExpedicaoSolicitadaDTO->setDblIdUnidadeExpedidora(SessaoSEI::getInstance()->getNumIdUnidadeAtual());

  PaginaSEI::getInstance()->prepararOrdenacao($mdCorExpedicaoSolicitadaDTO, 'DataExpedicao', InfraDTO::$TIPO_ORDENACAO_DESC);
  PaginaSEI::getInstance()->prepararPaginacao($mdCorExpedicaoSolicitadaDTO, 200);

  $arrMdCorExpedicaoSolicitadaDTO = $mdCorExpedicaoSolicitadaRN->listar($mdCorExpedicaoSolicitadaDTO);

  $numRegistros = $mdCorExpedicaoSolicitadaRN->contar($mdCorExpedicaoSolicitadaDTO);

  PaginaSEI::getInstance()->processarPaginacao($mdCorExpedicaoSolicitadaDTO);
  $strCaptionTabela = 'ARs Pendentes de Retorno';

  $strResultado = '<table width="100%" class="infraTable" summary="Serviços">';
  $strResultado .= '<caption class="infraCaption">' . PaginaSEI::getInstance()->gerarCaptionTabela($strCaptionTabela, $numRegistros) . '</caption>';
  $strResultado .= '<tr style="height: 25px;">';
  $strResultado .= '<th class="infraTh"  style="width: 25px !important;">' . PaginaSEI::getInstance()->getThCheck() . '</th>' . "\n";
  $strResultado .= '<th class="infraTh" style="width: 140px  !important;">' . PaginaSEI::getInstance()->getThOrdenacao($mdCorExpedicaoSolicitadaDTO, 'Documento Principal', 'ProtocoloFormatadoDocumento', $arrMdCorExpedicaoSolicitadaDTO) . '</th>';
  $strResultado .= '<th class="infraTh" style="width: 90px  !important;">' . PaginaSEI::getInstance()->getThOrdenacao($mdCorExpedicaoSolicitadaDTO, 'Rastreamento', 'CodigoRastreamento', $arrMdCorExpedicaoSolicitadaDTO) . '</th>';
  $strResultado .= '<th class="infraTh">' . PaginaSEI::getInstance()->getThOrdenacao($mdCorExpedicaoSolicitadaDTO, 'Destinatário', 'EnderecoDestinatario', $arrMdCorExpedicaoSolicitadaDTO) . '</th>';

  $strResultado .= '<th class="infraTh" style="width: 70px  !important;">' . PaginaSEI::getInstance()->getThOrdenacao($mdCorExpedicaoSolicitadaDTO, 'Data Solicitação', 'DataSolicitacao', $arrMdCorExpedicaoSolicitadaDTO) . '</th>';
  $strResultado .= '<th class="infraTh" style="width: 70px  !important;">' . PaginaSEI::getInstance()->getThOrdenacao($mdCorExpedicaoSolicitadaDTO, 'Data Expedição', 'DataExpedicao', $arrMdCorExpedicaoSolicitadaDTO) . '</th>';

  $strResultado .= '<th class="infraTh" style="width: 70px  !important;">' . PaginaSEI::getInstance()->getThOrdenacao($mdCorExpedicaoSolicitadaDTO, 'Dias Atraso', 'DataExpedicao', $arrMdCorExpedicaoSolicitadaDTO) . '</th>';
  $strResultado .= '<th class="infraTh" style="width: 140px  !important;">' . PaginaSEI::getInstance()->getThOrdenacao($mdCorExpedicaoSolicitadaDTO, 'Tipo Atraso', 'NomeDestinatario', $arrMdCorExpedicaoSolicitadaDTO) . '</th>';
  $strResultado .= '<th class="infraTh" style="width: 140px  !important;">' . PaginaSEI::getInstance()->getThOrdenacao($mdCorExpedicaoSolicitadaDTO, 'Documento Cobrança', 'ProtocoloFormatadoDocumento', $arrMdCorExpedicaoSolicitadaDTO) . '</th>';
  $strResultado .= '</tr>';


  $mdCorArCobrancaRN = new MdCorArCobrancaRN();
  foreach ($arrMdCorExpedicaoSolicitadaDTO as $chave => $resultado) {
    $strUrlDocumento = SessaoSEI::getInstance()->assinarLink('controlador.php?acao=procedimento_trabalhar&id_documento=' .$resultado->getDblIdDocumentoPrincipal());
    $mdCorArCobrancaDTO = new MdCorArCobrancaDTO();
    $mdCorArCobrancaDTO->retDblIdDocumentoCobranca();
    $mdCorArCobrancaDTO->retStrProtocoloFormatadoCobranca();
    $mdCorArCobrancaDTO->retStrNumeroDocumentoCobranca();
    $mdCorArCobrancaDTO->retStrNomeSerieCobranca();
    $mdCorArCobrancaDTO->setNumIdMdCorExpedicaoSolicitada($resultado->getNumIdMdCorExpedicaoSolicitada());
    $mdCorArCobrancaDTO->setOrdDthDtMdCorArCobranca(InfraDTO::$TIPO_ORDENACAO_DESC);
    $arrMdCorArCobrancaDTO = $mdCorArCobrancaRN->listar($mdCorArCobrancaDTO);
    $arrUltimaCobranca = current($arrMdCorArCobrancaDTO);

    $dtAtual = InfraData::getStrDataAtual();
    $dtExpedicao = substr($resultado->getDthDataExpedicao(), 0, 10);
    $strUrlRastreio = SessaoSEI::getInstance()->assinarLink('controlador.php?acao=md_cor_expedicao_detalhar_rastreio&acao_origem=' . $_GET['acao'] . '&co_rastreio=' . $resultado->getStrCodigoRastreamento());
    $strCssTr = $strCssTr == '<tr class="infraTrClara"' ? '<tr class="infraTrEscura"' : '<tr class="infraTrClara"';
    $strResultado .= $strCssTr . ' id="linha_' . $chave . '">';
    $strResultado .= '<td valign="center">' . PaginaSEI::getInstance()->getTrCheck($chave, $resultado->getNumIdMdCorExpedicaoSolicitada(), $resultado->getNumIdMdCorExpedicaoSolicitada()) . '</td>';


    $strResultado .= '<td>';
    $strResultado .= $resultado->getStrNomeSerie() . ' ' . $resultado->getStrNumeroDocumento() . ' <a class="protocoloNormal" style="font-size: 1.0em !important; font-size:1em" href="' . $strUrlDocumento . '" target="_blank">(' . $resultado->getStrProtocoloFormatadoDocumento() .')</a>';
    $strResultado .= '</a>';
    $strResultado .= '</td>';

    $strResultado .= '<td>';
    $strResultado .= '<a class="protocoloNormal" style="font-size: 1.0em !important; font-size:1em" href="' . $strUrlRastreio . '" target="_blank">';
    $strResultado .= $resultado->getStrCodigoRastreamento();
    $strResultado .= '</a>';
    $strResultado .= '</td>';
    $strResultado .= '<td>';

    $strResultado .= mb_strtoupper($resultado->getStrNomeDestinatario(), 'ISO-8859-1') . '<br>';

    if($resultado->getStrSinEnderecoAssociado() == 'S') {

        $objContDTO = new ContatoDTO();
        $objContDTO->retStrNomeContatoAssociado();
        $objContDTO->retStrEnderecoContatoAssociado();
        $objContDTO->retStrBairroContatoAssociado();
        $objContDTO->retStrCepContatoAssociado();
        $objContDTO->retStrNomeCidadeContatoAssociado();
        $objContDTO->retStrSiglaUfContatoAssociado();
        $objContDTO->setNumIdContato($resultado->getNumIdContatoDestinatario());

        $objContatoRN = new ContatoRN();
        $objContDTO = $objContatoRN->consultarRN0324($objContDTO);

        if($objContDTO){
            $strResultado .= $objContDTO->getStrNomeContatoAssociado() . '<br>';
            $strResultado .= $objContDTO->getStrEnderecoContatoAssociado() . ', ' . $objContDTO->getStrBairroContatoAssociado() . ' - ' . $objContDTO->getStrNomeCidadeContatoAssociado() . ' ' . $objContDTO->getStrSiglaUfContatoAssociado() . ' - ' . $objContDTO->getStrCepContatoAssociado();
        }

    }else{
        $strResultado .= $resultado->getStrEnderecoDestinatario() . ', ' . $resultado->getStrBairroDestinatario() . ' - ' . $resultado->getStrNomeCidadeDestinatario() . ' ' . $resultado->getStrSiglaUfDestinatario() . ' - ' . $resultado->getStrCepDestinatario();
     }

    $strResultado .= '</td>';

    $strResultado .= '<td>';
    $strResultado .= $resultado->getDthDataSolicitacao();
    $strResultado .= '</td>';

    $strResultado .= '<td>';
    $strResultado .= substr( $resultado->getDthDataExpedicao(),0,10);
    $strResultado .= '</td>';

    $strResultado .= '<td>';
    $resultadoDiasCobranca = InfraData::compararDatas($dtExpedicao, $dtAtual);
    $strResultado .= $resultadoDiasCobranca;
    $strResultado .= '</td>';
    $strResultado .= '<td>';
    $statusCobranca = $resultado->getStrStatusCobranca();

    if ($resultado->getStrStatusCobranca() == MdCorRetornoArRN::$STA_PENDENTE_RETORNO_SEM_COBRANCA && $resultadoDiasCobranca > $nuDiasCobranca) {
      $statusCobranca = MdCorRetornoArRN::$STA_FORA_PRAZO_COBRANCA;
    }
    $strResultado .= MdCorRetornoArRN::$arrStatusRetorno[$statusCobranca];
    $strResultado .= '</td>';

    $strResultado .= '<td>';

    if ($arrUltimaCobranca) {

      $idSolicitacao = $resultado->getNumIdMdCorExpedicaoSolicitada();

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

      $strResultado .= '<div style="margin: 5px 0 5px 0;">';
      foreach ($arrMdCorArCobrancaDTO as $chave=>$result) {
          $strUrlDocumento = SessaoSEI::getInstance()->assinarLink('controlador.php?acao=procedimento_trabalhar&infra_sistema='.$result->getStrProtocoloFormatadoCobranca().'&id_documento=' .$result->getDblIdDocumentoCobranca());
          $strResultado .= '<a class="protocoloNormal" style="font-size: 1.0em !important; font-size:1em" href="' . $strUrlDocumento . '" target="_blank">';
          $strResultado .= 'Ofício '.$result->getStrNumeroDocumentoCobranca().' (' . $result->getStrProtocoloFormatadoCobranca().')';
          $strResultado .= '</a>'. "<br>";
      }

      $strResultado .= '</div>';
  }

    $strResultado .= '</td>';

    $strResultado .= '</tr>';
  }
  $strResultado .= '</table>';
?>