<?
  /**
   * TRIBUNAL REGIONAL FEDERAL DA 4ª REGIÃO
   *
   * 21/08/2018 - criado por augusto.cast
   *
   * Versão do Gerador de Código: 1.41.0
   */

  require_once dirname(__FILE__) . '/../../../SEI.php';

  class MdCorArCobrancaRN extends InfraRN {

    public function __construct() {
      parent::__construct();
    }

    protected function inicializarObjInfraIBanco() {
      return BancoSEI::getInstance();
    }

    private function validarNumIdMdCorArCobranca(MdCorArCobrancaDTO $objMdCorArCobrancaDTO, InfraException $objInfraException) {
      if (InfraString::isBolVazia($objMdCorArCobrancaDTO->getNumIdMdCorArCobranca())) {
        $objInfraException->adicionarValidacao(' não informad.');
      }
    }

    private function validarDthDtMdCorArCobranca(MdCorArCobrancaDTO $objMdCorArCobrancaDTO, InfraException $objInfraException) {
      if (InfraString::isBolVazia($objMdCorArCobrancaDTO->getDthDtMdCorArCobranca())) {
        $objInfraException->adicionarValidacao(' não informad.');
      }
    }

    private function validarDblIdDocumentoCobranca(MdCorArCobrancaDTO $objMdCorArCobrancaDTO, InfraException $objInfraException) {
      if (InfraString::isBolVazia($objMdCorArCobrancaDTO->getDblIdDocumentoCobranca())) {
        $objInfraException->adicionarValidacao(' não informad.');
      }
    }

    private function validarNumIdMdCorExpedicaoSolicitada(MdCorArCobrancaDTO $objMdCorArCobrancaDTO, InfraException $objInfraException) {
      if (InfraString::isBolVazia($objMdCorArCobrancaDTO->getNumIdMdCorExpedicaoSolicitada())) {
        $objInfraException->adicionarValidacao(' não informad.');
      }
    }

    protected function cadastrarControlado(MdCorArCobrancaDTO $objMdCorArCobrancaDTO) {
      try {

        //Valida Permissao
        SessaoSEI::getInstance()->validarPermissao('md_cor_cobranca_gerar');

        //Regras de Negocio
        $objInfraException = new InfraException();

        $this->validarDthDtMdCorArCobranca($objMdCorArCobrancaDTO, $objInfraException);
        $this->validarDblIdDocumentoCobranca($objMdCorArCobrancaDTO, $objInfraException);
        $this->validarNumIdMdCorExpedicaoSolicitada($objMdCorArCobrancaDTO, $objInfraException);

        $objInfraException->lancarValidacoes();

        $objMdCorArCobrancaBD = new MdCorArCobrancaBD($this->getObjInfraIBanco());
        $ret = $objMdCorArCobrancaBD->cadastrar($objMdCorArCobrancaDTO);

        //Auditoria

        return $ret;

      } catch (Exception $e) {
        throw new InfraException('Erro cadastrando .', $e);
      }
    }

    protected function alterarControlado(MdCorArCobrancaDTO $objMdCorArCobrancaDTO) {
      try {

        //Valida Permissao
        SessaoSEI::getInstance()->validarPermissao('md_cor_ar_cobranca_alterar');

        //Regras de Negocio
        $objInfraException = new InfraException();

        if ($objMdCorArCobrancaDTO->isSetNumIdMdCorArCobranca()) {
          $this->validarNumIdMdCorArCobranca($objMdCorArCobrancaDTO, $objInfraException);
        }
        if ($objMdCorArCobrancaDTO->isSetDthDtMdCorArCobranca()) {
          $this->validarDthDtMdCorArCobranca($objMdCorArCobrancaDTO, $objInfraException);
        }
        if ($objMdCorArCobrancaDTO->isSetDblIdDocumentoCobranca()) {
          $this->validarDblIdDocumentoCobranca($objMdCorArCobrancaDTO, $objInfraException);
        }
        if ($objMdCorArCobrancaDTO->isSetNumIdMdCorExpedicaoSolicitada()) {
          $this->validarNumIdMdCorExpedicaoSolicitada($objMdCorArCobrancaDTO, $objInfraException);
        }

        $objInfraException->lancarValidacoes();

        $objMdCorArCobrancaBD = new MdCorArCobrancaBD($this->getObjInfraIBanco());
        $objMdCorArCobrancaBD->alterar($objMdCorArCobrancaDTO);

        //Auditoria

      } catch (Exception $e) {
        throw new InfraException('Erro alterando .', $e);
      }
    }

    protected function excluirControlado($arrObjMdCorArCobrancaDTO) {
      try {

        //Valida Permissao
        SessaoSEI::getInstance()->validarPermissao('md_cor_ar_cobranca_excluir');

        //Regras de Negocio
        //$objInfraException = new InfraException();

        //$objInfraException->lancarValidacoes();

        $objMdCorArCobrancaBD = new MdCorArCobrancaBD($this->getObjInfraIBanco());
        for ($i = 0; $i < count($arrObjMdCorArCobrancaDTO); $i++) {
          $objMdCorArCobrancaBD->excluir($arrObjMdCorArCobrancaDTO[$i]);
        }

        //Auditoria

      } catch (Exception $e) {
        throw new InfraException('Erro excluindo .', $e);
      }
    }

    protected function consultarConectado(MdCorArCobrancaDTO $objMdCorArCobrancaDTO) {
      try {

        //Valida Permissao
        SessaoSEI::getInstance()->validarPermissao('md_cor_ar_cobranca_consultar');

        //Regras de Negocio
        //$objInfraException = new InfraException();

        //$objInfraException->lancarValidacoes();

        $objMdCorArCobrancaBD = new MdCorArCobrancaBD($this->getObjInfraIBanco());
        $ret = $objMdCorArCobrancaBD->consultar($objMdCorArCobrancaDTO);

        //Auditoria

        return $ret;
      } catch (Exception $e) {
        throw new InfraException('Erro consultando .', $e);
      }
    }

    protected function listarConectado(MdCorArCobrancaDTO $objMdCorArCobrancaDTO) {
      try {

        //Valida Permissao
//        SessaoSEI::getInstance()->validarPermissao('md_cor_ar_cobranca_listar');

        //Regras de Negocio
        //$objInfraException = new InfraException();

        //$objInfraException->lancarValidacoes();

        $objMdCorArCobrancaBD = new MdCorArCobrancaBD($this->getObjInfraIBanco());
        $ret = $objMdCorArCobrancaBD->listar($objMdCorArCobrancaDTO);

        //Auditoria

        return $ret;

      } catch (Exception $e) {
        throw new InfraException('Erro listando .', $e);
      }
    }

    protected function contarConectado(MdCorArCobrancaDTO $objMdCorArCobrancaDTO) {
      try {

        //Valida Permissao
//        SessaoSEI::getInstance()->validarPermissao('md_cor_ar_cobranca_listar');

        //Regras de Negocio
        //$objInfraException = new InfraException();

        //$objInfraException->lancarValidacoes();

        $objMdCorArCobrancaBD = new MdCorArCobrancaBD($this->getObjInfraIBanco());
        $ret = $objMdCorArCobrancaBD->contar($objMdCorArCobrancaDTO);

        //Auditoria

        return $ret;
      } catch (Exception $e) {
        throw new InfraException('Erro contando .', $e);
      }
    }

    protected function gerarDocumentoCobrancaInformarControlado($arrInforma) {
      $idDocumento = $arrInforma['hdnIdDocumento'];

      $arrIdSolicitacaoExpedicao = explode(',', $arrInforma['hdnCodIdSolicitacao']);

      $mdCorParametroRN = new MdCorParametroArRN();
      $mdCorParametroDTO = new MdCorParametroArDTO();
      $mdCorParametroDTO->retNumIdSerieCobranca();
      $mdCorParametroDTO->retStrNuDiasCobrancaAr();
      $mdCorParametroDTO->retNumIdProcedimentoCobranca();
      $mdCorParametroDTO->retNumIdUnidadeCobranca();
      $mdCorParametroDTO->retStrModeloCobranca();

      $mdCorParametroDTO = $mdCorParametroRN->consultar($mdCorParametroDTO);

      $nuDiasAtraso = $mdCorParametroDTO->getStrNuDiasCobrancaAr();
      $dtPermitida = InfraData::calcularData($nuDiasAtraso, InfraData::$UNIDADE_DIAS, InfraData::$SENTIDO_ATRAS);

      if (is_null($mdCorParametroDTO->getNumIdUnidadeCobranca())) {
        $objInfraException = new InfraException();
        $objInfraException->adicionarValidacao(' Parâmetros de cobrança não cadastrados.');
        $objInfraException->lancarValidacoes();
      }

      $mdCorExpedicaoSolicitadaRN = new MdCorExpedicaoSolicitadaRN();
      $mdCorArCobrancaRN = new MdCorArCobrancaRN();
      foreach ($arrIdSolicitacaoExpedicao as $idSolicitacaoExpedicao) {
        $mdCorExpedicaoSolicitadaDTO = new MdCorExpedicaoSolicitadaDTO();
        $mdCorExpedicaoSolicitadaDTO->setNumIdMdCorExpedicaoSolicitada($idSolicitacaoExpedicao);
        $mdCorExpedicaoSolicitadaDTO->retStrCodigoRastreamento();
        $mdCorExpedicaoSolicitadaDTO->retNumIdMdCorExpedicaoSolicitada();
        $mdCorExpedicaoSolicitadaDTO->retDthDataExpedicao();
        $mdCorExpedicaoSolicitadaDTO = $mdCorExpedicaoSolicitadaRN->consultar($mdCorExpedicaoSolicitadaDTO);


        $dtExpedicao = substr($mdCorExpedicaoSolicitadaDTO->getDthDataExpedicao(), 0, 10);
        $stStatusCobranca = MdCorRetornoArRN::$STA_PENDENTE_RETORNO_COM_COBRANCA;
        if (InfraData::compararDatas($dtPermitida, $dtExpedicao) <= 0) {
          $stStatusCobranca = MdCorRetornoArRN::$STA_COBRADO_FORA_PRAZO;
        }


        $codigoDocumento = $this->_adicionarDocumentoCobrancaInformar($mdCorExpedicaoSolicitadaDTO, $mdCorParametroDTO, $idDocumento);

        $mdCorArCobrancaDTO = new MdCorArCobrancaDTO();
        $mdCorArCobrancaDTO->setDthDtMdCorArCobranca(date('d/m/Y H:i:s'));
        $mdCorArCobrancaDTO->setDblIdDocumentoCobranca($codigoDocumento);
        $mdCorArCobrancaDTO->setNumIdMdCorExpedicaoSolicitada($idSolicitacaoExpedicao);
        $mdCorArCobrancaRN->cadastrar($mdCorArCobrancaDTO);

        $mdCorExpedicaoSolicitadaDTO->setStrStatusCobranca($stStatusCobranca);
        $mdCorExpedicaoSolicitadaRN->alterar($mdCorExpedicaoSolicitadaDTO);
      }
    }

    protected function gerarDocumentoCobrancaControlado($arrIdSolicitacaoExpedicao) {
      $arrIdSolicitacaoExpedicao = explode(',', $arrIdSolicitacaoExpedicao);
      $mdCorParametroRN = new MdCorParametroArRN();
      $mdCorParametroDTO = new MdCorParametroArDTO();
      $mdCorParametroDTO->retNumIdSerieCobranca();
      $mdCorParametroDTO->retStrNuDiasCobrancaAr();
      $mdCorParametroDTO->retNumIdProcedimentoCobranca();
      $mdCorParametroDTO->retNumIdUnidadeCobranca();
      $mdCorParametroDTO->retStrModeloCobranca();
      $mdCorParametroDTO->retNumIdContato();

      $mdCorParametroDTO = $mdCorParametroRN->consultar($mdCorParametroDTO);

      $nuDiasAtraso = $mdCorParametroDTO->getStrNuDiasCobrancaAr();
      $dtPermitida = InfraData::calcularData($nuDiasAtraso, InfraData::$UNIDADE_DIAS, InfraData::$SENTIDO_ATRAS);

      if (is_null($mdCorParametroDTO->getNumIdUnidadeCobranca())) {
        $objInfraException = new InfraException();
        $objInfraException->adicionarValidacao(' Parâmetros de cobrança não cadastrados.');
        $objInfraException->lancarValidacoes();
      }


      $mdCorExpedicaoSolicitadaRN = new MdCorExpedicaoSolicitadaRN();
      $mdCorArCobrancaRN = new MdCorArCobrancaRN();


      $mdCorExpedicaoSolicitadaDTO = new MdCorExpedicaoSolicitadaDTO();
      $mdCorExpedicaoSolicitadaDTO->setNumIdMdCorExpedicaoSolicitada($arrIdSolicitacaoExpedicao, InfraDTO::$OPER_IN);
      $mdCorExpedicaoSolicitadaDTO->retStrCodigoRastreamento();
      $mdCorExpedicaoSolicitadaDTO->retNumIdMdCorExpedicaoSolicitada();
      $mdCorExpedicaoSolicitadaDTO->retStrNomeDestinatario();
      $mdCorExpedicaoSolicitadaDTO->retStrEnderecoDestinatario();
      $mdCorExpedicaoSolicitadaDTO->retStrBairroDestinatario();
      $mdCorExpedicaoSolicitadaDTO->retStrCepDestinatario();
      $mdCorExpedicaoSolicitadaDTO->retStrComplementoDestinatario();
      $mdCorExpedicaoSolicitadaDTO->retStrNomeCidadeDestinatario();
      $mdCorExpedicaoSolicitadaDTO->retStrSiglaUfDestinatario();
      $mdCorExpedicaoSolicitadaDTO->retStrProtocoloFormatadoDocumento();
      $mdCorExpedicaoSolicitadaDTO->retStrNomeSerie();
      $mdCorExpedicaoSolicitadaDTO->retDthDataExpedicao();
      $mdCorExpedicaoSolicitadaDTO->retStrNumeroDocumento();
      $mdCorExpedicaoSolicitadaDTO->retStrStatusCobranca();
      $mdCorExpedicaoSolicitadaDTO->retDblIdDocumentoPrincipal();
      $arrMdCorExpedicaoSolicitadaDTO = $mdCorExpedicaoSolicitadaRN->listar($mdCorExpedicaoSolicitadaDTO);
      $codigoDocumento = $this->_adicionarDocumentoCobranca($arrMdCorExpedicaoSolicitadaDTO, $mdCorParametroDTO);

      foreach ($arrMdCorExpedicaoSolicitadaDTO as $mdCorExpedicaoSolicitadaDTO) {
        $dtExpedicao = substr($mdCorExpedicaoSolicitadaDTO->getDthDataExpedicao(), 0, 10);
        $stStatusCobranca = MdCorRetornoArRN::$STA_PENDENTE_RETORNO_COM_COBRANCA;
        if (InfraData::compararDatas($dtPermitida, $dtExpedicao) <= 0) {
          $stStatusCobranca = MdCorRetornoArRN::$STA_COBRADO_FORA_PRAZO;
        }


        $mdCorArCobrancaDTO = new MdCorArCobrancaDTO();
        $mdCorArCobrancaDTO->setDthDtMdCorArCobranca(date('d/m/Y H:i:s'));
        $mdCorArCobrancaDTO->setDblIdDocumentoCobranca($codigoDocumento);
        $mdCorArCobrancaDTO->setNumIdMdCorExpedicaoSolicitada($mdCorExpedicaoSolicitadaDTO->getNumIdMdCorExpedicaoSolicitada());
        $mdCorArCobrancaRN->cadastrar($mdCorArCobrancaDTO);

        $mdCorExpedicaoSolicitadaDTO->setStrStatusCobranca($stStatusCobranca);
        $mdCorExpedicaoSolicitadaRN->alterar($mdCorExpedicaoSolicitadaDTO);
      }
    }

    private function _adicionarDocumentoCobranca($arrMdCorExpedicaoSolicitadaDTO, $mdCorParametroDTO) {

      $tabela = '<table border="1" cellpadding="1" cellspacing="0" style="margin-left:auto;margin-right:auto;width:98%;" width="99%">';
      $tabela .= '<caption class="infraCaption">&nbsp;</caption>';
      $tabela .= '<thead>';
      $tabela .= '<tr>';
      $tabela .= '<th align="center" style="text-align: center; font-weight: bold; background-color: rgb(221, 221, 221);"><p style="font-size: 14px; font-family: Calibri; text-align: center; word-wrap: normal; margin: 0 3pt 0;">Código de Rastreio</p></th>';
      $tabela .= '<th align="center" style="text-align: center; font-weight: bold; background-color: rgb(221, 221, 221);"><p style="font-size: 14px; font-family: Calibri; text-align: center; word-wrap: normal; margin: 0 3pt 0;">Destinatário</p></th>';
      $tabela .= '<th align="center" style="text-align: center; font-weight: bold; background-color: rgb(221, 221, 221);"><p style="font-size: 14px; font-family: Calibri; text-align: center; word-wrap: normal; margin: 0 3pt 0;">Endereço do destinatário</p></th>';
      $tabela .= '<th align="center" style="text-align: center; font-weight: bold; background-color: rgb(221, 221, 221);"><p style="font-size: 14px; font-family: Calibri; text-align: center; word-wrap: normal; margin: 0 3pt 0;">Documento Principal</p></th>';
      $tabela .= '<th align="center" style="text-align: center; font-weight: bold; background-color: rgb(221, 221, 221);"><p style="font-size: 14px; font-family: Calibri; text-align: center; word-wrap: normal; margin: 0 3pt 0;">Último Documento de Cobrança</p></th>';
      $tabela .= '<th align="center" style="text-align: center; font-weight: bold; background-color: rgb(221, 221, 221);"><p style="font-size: 14px; font-family: Calibri; text-align: center; word-wrap: normal; margin: 0 3pt 0;">Dias em Atraso</p></th>';
      $tabela .= '</tr>';
      $tabela .= '</thead>';
      $tabela .= '<tbody>';

      foreach ($arrMdCorExpedicaoSolicitadaDTO as $mdCorExpedicaoSolicitadaDTO) {
        $mdCorArCobrancaDTO = new MdCorArCobrancaDTO();
        $mdCorArCobrancaDTO->retDblIdDocumentoCobranca();
        $mdCorArCobrancaDTO->retStrProtocoloFormatadoCobranca();
        $mdCorArCobrancaDTO->retStrNumeroDocumentoCobranca();
        $mdCorArCobrancaDTO->retStrNomeSerieCobranca();
        $mdCorArCobrancaDTO->setNumIdMdCorExpedicaoSolicitada($mdCorExpedicaoSolicitadaDTO->getNumIdMdCorExpedicaoSolicitada());
        $mdCorArCobrancaDTO->setOrdDthDtMdCorArCobranca(InfraDTO::$TIPO_ORDENACAO_DESC);
        $mdCorArCobrancaRN = new MdCorArCobrancaRN();
        $arrMdCorArCobrancaDTO = $mdCorArCobrancaRN->listar($mdCorArCobrancaDTO);

        $tabela .= '<tr class="infraTrClara" style="border:1px solid black">';
        $tabela .= '<td style="border:1px solid black"><p style="font-size: 14px; font-family: Calibri; text-align: center; word-wrap: normal; margin: 0 3pt 0;">' . $mdCorExpedicaoSolicitadaDTO->getStrCodigoRastreamento() . '</p></td>';
        $tabela .= '<td style="border:1px solid black"><p style="font-size: 14px; font-family: Calibri; text-align: center; word-wrap: normal; margin: 0 3pt 0;">' . $mdCorExpedicaoSolicitadaDTO->getStrNomeDestinatario() . '</p></td>';
        $tabela .= '<td style="border:1px solid black"><p style="font-size: 14px; font-family: Calibri; text-align: center; word-wrap: normal; margin: 0 3pt 0;">' . $mdCorExpedicaoSolicitadaDTO->getStrEnderecoDestinatario() . ', ' . $mdCorExpedicaoSolicitadaDTO->getStrBairroDestinatario() . ' - ' . $mdCorExpedicaoSolicitadaDTO->getStrNomeCidadeDestinatario() . ' ' . $mdCorExpedicaoSolicitadaDTO->getStrSiglaUfDestinatario() . ' - ' . $mdCorExpedicaoSolicitadaDTO->getStrCepDestinatario() . '</p></td>';
        $tabela .= '<td style="border:1px solid black"><p style="font-size: 14px; font-family: Calibri; text-align: center; word-wrap: normal; margin: 0 3pt 0;">' . $mdCorExpedicaoSolicitadaDTO->getStrNomeSerie() . ' ' . $mdCorExpedicaoSolicitadaDTO->getStrNumeroDocumento() . '  (SEI nº ' . $mdCorExpedicaoSolicitadaDTO->getStrProtocoloFormatadoDocumento() . ')' . '</p></td>';
        $tabela .= '<td style="border:1px solid black"><p style="font-size: 14px; font-family: Calibri; text-align: center; word-wrap: normal; margin: 0 3pt 0;">';
        $arrUltimaCobranca = current($arrMdCorArCobrancaDTO);
        if ($arrUltimaCobranca) {
          $tabela .= $arrUltimaCobranca->getStrNomeSerieCobranca() . ' ' . $arrUltimaCobranca->getStrNumeroDocumentoCobranca() . '  (SEI nº ' . $arrUltimaCobranca->getStrProtocoloFormatadoCobranca() . ')';
        }

        $dtAtual = InfraData::getStrDataAtual();
        $dtExpedicao = substr($mdCorExpedicaoSolicitadaDTO->getDthDataExpedicao(), 0, 10);
        $resultadoDiasCobranca = InfraData::compararDatas($dtExpedicao, $dtAtual);
        $tabela .= '</td>';
        $tabela .= '<td style="border:1px solid black"><p style="font-size: 14px; font-family: Calibri; text-align: center; word-wrap: normal; margin: 0 3pt 0;">' . $resultadoDiasCobranca . '</p></td>';
        $tabela .= '</tr>';
      }
      $tabela .= '</tbody>';
      $tabela .= '</table>';

      $contatoRN = new ContatoRN();
      $objContatoDTO = new ContatoDTO();
      $objContatoDTO->setNumIdContato($mdCorParametroDTO->getNumIdContato());
      $objContatoDTO->retStrNome();
      $objContatoDTO->retStrSigla();
      $arrObjContatoDTO = $contatoRN->consultarRN0324($objContatoDTO);

      $htmlModelo = $mdCorParametroDTO->getStrModeloCobranca();
      $htmlModelo = str_replace('@tabela_cobranca@', $tabela, $htmlModelo);

      //==========================================================================
      //incluindo doc recibo no processo via SEIRN
      //==========================================================================

      $idSerie = $mdCorParametroDTO->getNumIdSerieCobranca();
      $idProcedimento = $mdCorParametroDTO->getNumIdProcedimentoCobranca();

      $idUsuarioLogado = SessaoSEI::getInstance()->getNumIdUsuario();
      $idUnidadeLogado = SessaoSEI::getInstance()->getNumIdUnidadeAtual();
      $idUnidadeParametro = $mdCorParametroDTO->getNumIdUnidadeCobranca();

      SessaoSEI::getInstance()->setBolHabilitada(false);
      SessaoSEI::getInstance()->simularLogin(null, null, $idUsuarioLogado, $idUnidadeParametro);

      $objDocumentoAPI = new DocumentoAPI();
      $objDocumentoAPI->setIdProcedimento($idProcedimento);
      $objDocumentoAPI->setSubTipo(DocumentoRN::$TD_EDITOR_INTERNO);
      $objDocumentoAPI->setTipo(ProtocoloRN::$TP_DOCUMENTO_GERADO);
      $objDocumentoAPI->setIdSerie($idSerie);
      $objDocumentoAPI->setSinAssinado('N');
      $objDocumentoAPI->setSinBloqueado('N');
      $objDocumentoAPI->setIdHipoteseLegal(null);
      $objDocumentoAPI->setNivelAcesso(ProtocoloRN::$NA_PUBLICO);
      $objDocumentoAPI->setIdTipoConferencia(null);
      $objDocumentoAPI->setConteudo(base64_encode(utf8_encode($htmlModelo)));

      $DestinatarioAPI = new DestinatarioAPI();
      $DestinatarioAPI->setNome($arrObjContatoDTO->getStrNome());
      $DestinatarioAPI->setSigla($arrObjContatoDTO->getStrSigla());

      $objDocumentoAPI->setDestinatarios(array($DestinatarioAPI));

      $objSeiRN = new SeiRN();
      $objSaidaDocumentoAPI = $objSeiRN->incluirDocumento($objDocumentoAPI);

      SessaoSEI::getInstance()->setBolHabilitada(true);
      SessaoSEI::getInstance()->simularLogin(null, null, $idUsuarioLogado, $idUnidadeLogado);

      //necessario forçar update da coluna sta_documento da tabela documento
      //inclusao via SeiRN nao permitiu definir como documento de formulario automatico
      $parObjDocumentoDTO = new DocumentoDTO();
      $parObjDocumentoDTO->retTodos();
      $parObjDocumentoDTO->setDblIdDocumento($objSaidaDocumentoAPI->getIdDocumento());

      $docRN = new DocumentoRN();
      $parObjDocumentoDTO = $docRN->consultarRN0005($parObjDocumentoDTO);
      $parObjDocumentoDTO->setStrStaDocumento(DocumentoRN::$TD_EDITOR_INTERNO);
      $objDocumentoBD = new DocumentoBD($this->getObjInfraIBanco());
      $objDocumentoBD->alterar($parObjDocumentoDTO);

      return $objSaidaDocumentoAPI->getIdDocumento();

    }

    private function _adicionarDocumentoCobrancaInformar($mdCorExpedicaoSolicitadaDTO, $mdCorParametroDTO, $idDocumento) {
      $conteudo = $this->getDocumentoConteudo($idDocumento);
      //======================================================================
      //incluindo doc recibo no processo via SEIRN
      //==========================================================================

      $idSerie = $mdCorParametroDTO->getNumIdSerieCobranca();
      $idProcedimento = $mdCorParametroDTO->getNumIdProcedimentoCobranca();

      $idUsuarioLogado = SessaoSEI::getInstance()->getNumIdUsuario();
      $idUnidadeLogado = SessaoSEI::getInstance()->getNumIdUnidadeAtual();
      $idUnidadeParametro = $mdCorParametroDTO->getNumIdUnidadeCobranca();

      SessaoSEI::getInstance()->setBolHabilitada(false);
      SessaoSEI::getInstance()->simularLogin(null, null, $idUsuarioLogado, $idUnidadeParametro);


      $objDocumentoAPI = new DocumentoAPI();
      $objDocumentoAPI->setIdProcedimento($idProcedimento);
      $objDocumentoAPI->setSubTipo(DocumentoRN::$TD_FORMULARIO_AUTOMATICO);
      $objDocumentoAPI->setTipo(ProtocoloRN::$TP_DOCUMENTO_GERADO);
      $objDocumentoAPI->setIdSerie($idSerie);
      $objDocumentoAPI->setSinAssinado('N');
      $objDocumentoAPI->setSinBloqueado('S');
      $objDocumentoAPI->setIdHipoteseLegal(null);
      $objDocumentoAPI->setNivelAcesso(ProtocoloRN::$NA_PUBLICO);
      $objDocumentoAPI->setIdTipoConferencia(null);
      $objDocumentoAPI->setConteudo(base64_encode(utf8_encode($conteudo)));

      $objSeiRN = new SeiRN();
      $objSaidaDocumentoAPI = $objSeiRN->incluirDocumento($objDocumentoAPI);

      SessaoSEI::getInstance()->setBolHabilitada(true);
      SessaoSEI::getInstance()->simularLogin(null, null, $idUsuarioLogado, $idUnidadeLogado);

      //necessario forçar update da coluna sta_documento da tabela documento
      //inclusao via SeiRN nao permitiu definir como documento de formulario automatico
      $parObjDocumentoDTO = new DocumentoDTO();
      $parObjDocumentoDTO->retTodos();
      $parObjDocumentoDTO->setDblIdDocumento($objSaidaDocumentoAPI->getIdDocumento());

      $docRN = new DocumentoRN();
      $parObjDocumentoDTO = $docRN->consultarRN0005($parObjDocumentoDTO);
      $parObjDocumentoDTO->setStrStaDocumento(DocumentoRN::$TD_EDITOR_INTERNO);
      $objDocumentoBD = new DocumentoBD($this->getObjInfraIBanco());
      $objDocumentoBD->alterar($parObjDocumentoDTO);

      return $objSaidaDocumentoAPI->getIdDocumento();

    }

    private function getDocumentoConteudo($idDocumento) {
      $parObjDocumentoConteudoDTO = new DocumentoConteudoDTO();
      $parObjDocumentoConteudoDTO->setDblIdDocumento($idDocumento);
      $parObjDocumentoConteudoDTO->retStrConteudo();

      $objDocumentoConteudoBD = new DocumentoConteudoBD($this->getObjInfraIBanco());
      return $objDocumentoConteudoBD->consultar($parObjDocumentoConteudoDTO);
    }
    /*
      protected function desativarControlado($arrObjMdCorArCobrancaDTO){
        try {

          //Valida Permissao
          ::getInstance()->validarPermissao('md_cor_ar_cobranca_desativar');

          //Regras de Negocio
          //$objInfraException = new InfraException();

          //$objInfraException->lancarValidacoes();

          $objMdCorArCobrancaBD = new MdCorArCobrancaBD($this->getObjInfraIBanco());
          for($i=0;$i<count($arrObjMdCorArCobrancaDTO);$i++){
            $objMdCorArCobrancaBD->desativar($arrObjMdCorArCobrancaDTO[$i]);
          }

          //Auditoria

        }catch(Exception $e){
          throw new InfraException('Erro desativando .',$e);
        }
      }

      protected function reativarControlado($arrObjMdCorArCobrancaDTO){
        try {

          //Valida Permissao
          ::getInstance()->validarPermissao('md_cor_ar_cobranca_reativar');

          //Regras de Negocio
          //$objInfraException = new InfraException();

          //$objInfraException->lancarValidacoes();

          $objMdCorArCobrancaBD = new MdCorArCobrancaBD($this->getObjInfraIBanco());
          for($i=0;$i<count($arrObjMdCorArCobrancaDTO);$i++){
            $objMdCorArCobrancaBD->reativar($arrObjMdCorArCobrancaDTO[$i]);
          }

          //Auditoria

        }catch(Exception $e){
          throw new InfraException('Erro reativando .',$e);
        }
      }

      protected function bloquearControlado(MdCorArCobrancaDTO $objMdCorArCobrancaDTO){
        try {

          //Valida Permissao
          ::getInstance()->validarPermissao('md_cor_ar_cobranca_consultar');

          //Regras de Negocio
          //$objInfraException = new InfraException();

          //$objInfraException->lancarValidacoes();

          $objMdCorArCobrancaBD = new MdCorArCobrancaBD($this->getObjInfraIBanco());
          $ret = $objMdCorArCobrancaBD->bloquear($objMdCorArCobrancaDTO);

          //Auditoria

          return $ret;
        }catch(Exception $e){
          throw new InfraException('Erro bloqueando .',$e);
        }
      }

     */
  }
