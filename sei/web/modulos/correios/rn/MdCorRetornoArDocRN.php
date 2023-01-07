<?

/**
 * TRIBUNAL REGIONAL FEDERAL DA 4ª REGIÃO
 *
 * 29/06/2018 - criado por augusto.cast
 *
 * Versão do Gerador de Código: 1.41.0
 */
require_once dirname(__FILE__) . '/../../../SEI.php';

class MdCorRetornoArDocRN extends InfraRN {

    public function __construct() {
        parent::__construct();
    }

    protected function inicializarObjInfraIBanco() {
        return BancoSEI::getInstance();
    }

    private function validarNumIdCorRetornoArDoc(MdCorRetornoArDocDTO $objMdCorRetornoArDocDTO, InfraException $objInfraException) {
        if (InfraString::isBolVazia($objMdCorRetornoArDocDTO->getNumIdMdCorRetornoArDoc())) {
            $objInfraException->adicionarValidacao(' não informad.');
        }
    }

    private function validarNumIdCorRetornoAr(MdCorRetornoArDocDTO $objMdCorRetornoArDocDTO, InfraException $objInfraException) {
        if (InfraString::isBolVazia($objMdCorRetornoArDocDTO->getNumIdMdCorRetornoAr())) {
            $objInfraException->adicionarValidacao(' não informad.');
        }
    }

    private function validarNumIdDocumentoPrincipal(MdCorRetornoArDocDTO $objMdCorRetornoArDocDTO, InfraException $objInfraException) {
        if (InfraString::isBolVazia($objMdCorRetornoArDocDTO->getNumIdDocumentoPrincipal())) {
            $objInfraException->adicionarValidacao(' não informad2.');
        }
    }

    private function validarDthDataAr(MdCorRetornoArDocDTO $objMdCorRetornoArDocDTO, InfraException $objInfraException) {
        if (InfraString::isBolVazia($objMdCorRetornoArDocDTO->getDtaDataAr())) {
            $objInfraException->adicionarValidacao(' não informad3.');
        }
    }

    private function validarDthDataRetorno(MdCorRetornoArDocDTO $objMdCorRetornoArDocDTO, InfraException $objInfraException) {
        if (InfraString::isBolVazia($objMdCorRetornoArDocDTO->getDtaDataRetorno())) {
            $objInfraException->adicionarValidacao(' não informad4.');
        }
    }

    private function validarStrSinStatus(MdCorRetornoArDocDTO $objMdCorRetornoArDocDTO, InfraException $objInfraException) {
        if (InfraString::isBolVazia($objMdCorRetornoArDocDTO->getStrSinStatus())) {
            $objInfraException->adicionarValidacao(' não informad5.');
        } else {
            $objMdCorRetornoArDocDTO->setStrSinStatus(trim($objMdCorRetornoArDocDTO->getStrSinStatus()));

            if (strlen($objMdCorRetornoArDocDTO->getStrSinStatus()) > 1) {
                $objInfraException->adicionarValidacao(' possui tamanho superior a 1 caracteres.');
            }
        }
    }

    protected function cadastrarControlado(MdCorRetornoArDocDTO $objMdCorRetornoArDocDTO) {
        try {

            //Valida Permissao
            SessaoSEI::getInstance()->validarPermissao('md_cor_retorno_ar_salvar');

            //Regras de Negocio
            $objInfraException = new InfraException();

            $this->validarNumIdCorRetornoAr($objMdCorRetornoArDocDTO, $objInfraException);
//      $this->validarNumIdDocumentoPrincipal($objMdCorRetornoArDocDTO, $objInfraException);
//      $this->validarDthDataAr($objMdCorRetornoArDocDTO, $objInfraException);
//      $this->validarDthDataRetorno($objMdCorRetornoArDocDTO, $objInfraException);
            $this->validarStrSinStatus($objMdCorRetornoArDocDTO, $objInfraException);

            $objInfraException->lancarValidacoes();

            $objMdCorRetornoArDocBD = new MdCorRetornoArDocBD($this->getObjInfraIBanco());
            $ret = $objMdCorRetornoArDocBD->cadastrar($objMdCorRetornoArDocDTO);

            //Auditoria

            return $ret;
        } catch (Exception $e) {
            throw new InfraException('Erro cadastrando .', $e);
        }
    }

    protected function alterarControlado(MdCorRetornoArDocDTO $objMdCorRetornoArDocDTO) {
        try {

            //Valida Permissao
//      SessaoSEI::getInstance()->validarPermissao('md_cor_retorno_ar_doc_alterar');
            //Regras de Negocio
            $objInfraException = new InfraException();

            if ($objMdCorRetornoArDocDTO->isSetNumIdMdCorRetornoAr()) {
                $this->validarNumIdCorRetornoArDoc($objMdCorRetornoArDocDTO, $objInfraException);
            }
//      if ($objMdCorRetornoArDocDTO->isSetNumIdDocumentoPrincipal()) {
//        $this->validarNumIdDocumentoPrincipal($objMdCorRetornoArDocDTO, $objInfraException);
//      }
//      if ($objMdCorRetornoArDocDTO->isSetDtaDataAr()) {
//        $this->validarDthDataAr($objMdCorRetornoArDocDTO, $objInfraException);
//      }
//      if ($objMdCorRetornoArDocDTO->isSetDtaDataRetorno()) {
//        $this->validarDthDataRetorno($objMdCorRetornoArDocDTO, $objInfraException);
//      }
            if ($objMdCorRetornoArDocDTO->isSetStrSinStatus()) {
                $this->validarStrSinStatus($objMdCorRetornoArDocDTO, $objInfraException);
            }

            $objInfraException->lancarValidacoes();

            $objMdCorRetornoArDocBD = new MdCorRetornoArDocBD($this->getObjInfraIBanco());
            $objMdCorRetornoArDocBD->alterar($objMdCorRetornoArDocDTO);

            //Auditoria
        } catch (Exception $e) {
            throw new InfraException('Erro alterando .', $e);
        }
    }

    protected function excluirControlado($arrObjMdCorRetornoArDocDTO) {
        try {

            //Valida Permissao
            SessaoSEI::getInstance()->validarPermissao('md_cor_retorno_ar_doc_excluir');

            //Regras de Negocio
            //$objInfraException = new InfraException();
            //$objInfraException->lancarValidacoes();

            $objMdCorRetornoArDocBD = new MdCorRetornoArDocBD($this->getObjInfraIBanco());
            for ($i = 0; $i < count($arrObjMdCorRetornoArDocDTO); $i++) {
                $objMdCorRetornoArDocBD->excluir($arrObjMdCorRetornoArDocDTO[$i]);
            }

            //Auditoria
        } catch (Exception $e) {
            throw new InfraException('Erro excluindo .', $e);
        }
    }

    protected function consultarConectado(MdCorRetornoArDocDTO $objMdCorRetornoArDocDTO) {
        try {

            //Regras de Negocio
            //$objInfraException = new InfraException();
            //$objInfraException->lancarValidacoes();

            $objMdCorRetornoArDocBD = new MdCorRetornoArDocBD($this->getObjInfraIBanco());
            $ret = $objMdCorRetornoArDocBD->consultar($objMdCorRetornoArDocDTO);

            //Auditoria

            return $ret;
        } catch (Exception $e) {
            throw new InfraException('Erro consultando .', $e);
        }
    }

    protected function listarConectado(MdCorRetornoArDocDTO $objMdCorRetornoArDocDTO) {
        try {

            //Valida Permissao
//      SessaoSEI::getInstance()->validarPermissao('md_cor_retorno_ar_doc_listar');
            //Regras de Negocio
            //$objInfraException = new InfraException();
            //$objInfraException->lancarValidacoes();

            $objMdCorRetornoArDocBD = new MdCorRetornoArDocBD($this->getObjInfraIBanco());
            $ret = $objMdCorRetornoArDocBD->listar($objMdCorRetornoArDocDTO);

            //Auditoria

            return $ret;
        } catch (Exception $e) {
            throw new InfraException('Erro listando .', $e);
        }
    }

    protected function contarConectado(MdCorRetornoArDocDTO $objMdCorRetornoArDocDTO) {
        try {

            //Valida Permissao
//      SessaoSEI::getInstance()->validarPermissao('md_cor_retorno_ar_doc_listar');
            //Regras de Negocio
            //$objInfraException = new InfraException();
            //$objInfraException->lancarValidacoes();

            $objMdCorRetornoArDocBD = new MdCorRetornoArDocBD($this->getObjInfraIBanco());
            $ret = $objMdCorRetornoArDocBD->contar($objMdCorRetornoArDocDTO);

            //Auditoria

            return $ret;
        } catch (Exception $e) {
            throw new InfraException('Erro contando .', $e);
        }
    }

    protected function cadastrarArsControlado($dados) {
        try {
            $dtCadastro = InfraData::getStrDataHoraAtual();

            //Valida Permissao
            SessaoSEI::getInstance()->validarPermissao('md_cor_retorno_ar_salvar');
            $objMdCorRetornoArRN = new MdCorRetornoArRN();
            $objMdCorRetornoArDTO = new MdCorRetornoArDTO();

            if (empty($dados['hdnArquivoAlteracao'])) {
                $objMdCorRetornoArDTO->setDthDataCadastro($dtCadastro);
                $objMdCorRetornoArDTO->setNumIdUsuario(SessaoSEI::getInstance()->getNumIdUsuario());
                $objMdCorRetornoArDTO->setStrSinAutenticado('N');
                $objMdCorRetornoArDTO->setDthDataCadastro($dtCadastro);
                $objMdCorRetornoArDTO->setStrNomeArquivoZip($dados['hdnArquivoZip']);
                $objMdCorRetornoArDTO->setNumIdUnidade(SessaoSEI::getInstance()->getNumIdUnidadeAtual());
                $arrObjMdCorRetornoArDTO = $objMdCorRetornoArRN->cadastrar($objMdCorRetornoArDTO);
            } else {
                $objMdCorRetornoArDTO->retNumIdMdCorRetornoAr();
                $objMdCorRetornoArDTO->setNumIdMdCorRetornoAr($dados['hdnArquivoAlteracao']);
                $arrObjMdCorRetornoArDTO = $objMdCorRetornoArRN->consultar($objMdCorRetornoArDTO);
            }

            $idRetorno = $arrObjMdCorRetornoArDTO->getNumIdMdCorRetornoAr();

            $mdCorParametroArRN = new MdCorParametroArRN();
            $mdCorParametroArDTO = new MdCorParametroArDTO();
            $mdCorParametroArDTO->retNumIdSerie();
            $mdCorParametroArDTO->retStrNomeArvore();
            $mdCorParametroArDTO->retNumIdTipoConferencia();

            $arrMdCorParametroArDTO = $mdCorParametroArRN->consultar($mdCorParametroArDTO);

            $arrStatusEstado = array(
                ProtocoloRN::$TE_PROCEDIMENTO_SOBRESTADO,
                ProtocoloRN::$TE_PROCEDIMENTO_ANEXADO,
                ProtocoloRN::$TE_PROCEDIMENTO_BLOQUEADO,
            );

            foreach ($dados['hdnStProcessamento'] as $chave => $dadosSei) {

                $idDocumentoPrincipal = $dados['idDocumentoPrincipal'][$chave];
                $codigoRastreamento = $dados['hdnRastreamento'][$chave];
                $objMdCorRetornoArDocDTO = new MdCorRetornoArDocDTO();
                $noArquivo = $dados['no_arquivo'][$chave];
                if ($idDocumentoPrincipal) {
                    $mdCorExpedicaoSolicitadaRN = new MdCorExpedicaoSolicitadaRN();
                    $objMdCorExpedicaoSolicitadaDTO = new MdCorExpedicaoSolicitadaDTO();

                    $objMdCorExpedicaoSolicitadaDTO->setDblIdDocumentoPrincipal($idDocumentoPrincipal);
                    $objMdCorExpedicaoSolicitadaDTO->setStrCodigoRastreamento($codigoRastreamento);
                    $objMdCorExpedicaoSolicitadaDTO->retNumIdUnidade();
                    $objMdCorExpedicaoSolicidata = $mdCorExpedicaoSolicitadaRN->consultar($objMdCorExpedicaoSolicitadaDTO);

                    $idUsuarioLogado = SessaoSEI::getInstance()->getNumIdUsuario();
                    $idUnidadeLogado = SessaoSEI::getInstance()->getNumIdUnidadeAtual();
                    $idUnidadeParametro = $objMdCorExpedicaoSolicidata->getNumIdUnidade();

                    SessaoSEI::getInstance()->setBolHabilitada(false);
                    SessaoSEI::getInstance()->simularLogin(null, null, $idUsuarioLogado, $idUnidadeParametro);


                    $dtAr = $dados['dt_ar'][$chave];
                    $nuSei = $dados['nu_sei'][$chave];
                    $dtRetorno = $dados['dt_retorno'][$chave];
                    $stProcessamento = !empty(trim($nuSei)) ? $dados['hdnStProcessamento'][$chave] : MdCorRetornoArRN::$STA_RETORNO_AR_NAO_PROCESSADO;
                    $coRastreamento = $dados['hdnRastreamento'][$chave];
                    $objMdCorRetornoArDocDTO = new MdCorRetornoArDocDTO();

                    $documentoRN = new DocumentoRN();
                    $documentoDTO = new DocumentoDTO();
                    $documentoDTO->retStrStaEstadoProcedimento();
                    $documentoDTO->setStrProtocoloDocumentoFormatado($nuSei);
                    $objDocumentoDTO = $documentoRN->consultarRN0005($documentoDTO);

                    if (!in_array($objDocumentoDTO->getStrStaEstadoProcedimento(), $arrStatusEstado)) {

                        $idDocumentoAr = null;
                        if (empty($dados['hdnArquivoAlteracao']) && !is_null($idDocumentoPrincipal)) {
                            $idDocumentoAr = $this->_adicionarPdfProcedimento($dados, $chave, $arrMdCorParametroArDTO);
                        }
                    }

                    $objMdCorRetornoArDocDTO->setNumIdDocumentoPrincipal($idDocumentoPrincipal);
                    $objMdCorRetornoArDocDTO->retNumIdMdCorRetornoArDoc();
                    $objMdCorRetornoArDocDTO->retStrProtocoloFormatadoDocumento();
                    $objMdCorRetornoArDocDTO->retNumIdProcedimento();
                    $objMdCorRetornoArDocDTO->retDtaDataAr();
                    $arrObjMdCorRetornoArDTO = null;

                    if (!is_null($idDocumentoPrincipal) && !empty($dados['hdnArquivoAlteracao'])) {
                        $objMdCorRetornoArDocDTO->setNumIdMdCorRetornoAr($dados['hdnArquivoAlteracao']);
                        $arrObjMdCorRetornoArDTO = $this->consultar($objMdCorRetornoArDocDTO);
                    }
                } else {
                    $dtAr = null;
                    $dtRetorno = null;
                }

                $objMdCorRetornoArDocDTO->setDtaDataAr($dtAr);
                $objMdCorRetornoArDocDTO->setDtaDataRetorno($dtRetorno);
                if (!is_null($arrObjMdCorRetornoArDTO) && !is_null($idDocumentoPrincipal)) {
                    $objMdCorRetornoArDocDTO = $arrObjMdCorRetornoArDTO;
                    $numIdProcedimento = $objMdCorRetornoArDocDTO->getNumIdProcedimento();
                    if (!empty($dtRetorno)) {
                        $objMdCorRetornoArDocDTO->setDtaDataRetorno($dtRetorno);
                        $objMdCorRetornoArDocDTO->setDtaDataAr($dtAr);
                    }
                } else {
//          $dtAr = strpos($dtAr, '/') ?   InfraData::formatarDataBanco($dtAr) : $dtAr;
//          $dtRetorno = strpos($dtRetorno, '/') ?   InfraData::formatarDataBanco($dtRetorno) : $dtAr;
                    $objMdCorRetornoArDocDTO->setDtaDataAr($dtAr);
                    $objMdCorRetornoArDocDTO->setDtaDataRetorno($dtRetorno);
                    $objMdCorRetornoArDocDTO->setNumIdDocumentoAr($idDocumentoAr);
                }
                $coInfrigencia = null;
                $strSituacaoRetorno = ' entregue com sucesso';
                $strMotivoDevolucao = "";

                if ($dados['hdnArquivoAlteracao'] != '' || (isset($dados['st_devolvido'][$chave]) && !is_null($dados['st_devolvido'][$chave]))) {

                    $coInfrigencia = $dados['co_motivo'][$chave];
                    $objMdCorRetornoArDocDTO->setNumIdMdCorParamArInfrigencia($coInfrigencia);

                    $strSituacaoRetorno = ' objeto devolvido,';
                    if ($dados['co_motivo'][$chave] != 'null') {
                        $mdCorParamArInfrigenRN = new MdCorParamArInfrigenRN();
                        $objMdCorParamArInfrigenDTO = new MdCorParamArInfrigenDTO();
                        $objMdCorParamArInfrigenDTO->setNumIdMdCorParamArInfrigencia($coInfrigencia);
                        $objMdCorParamArInfrigenDTO->retStrMotivoInfrigencia();
                        $arrObjMdCorParamArInfrigenDTO = $mdCorParamArInfrigenRN->consultar($objMdCorParamArInfrigenDTO);
                        $strMotivoDevolucao = $arrObjMdCorParamArInfrigenDTO->getStrMotivoInfrigencia();

                        if($dados['hdnArquivoAlteracao'] != '') {
                            $objAtributoAndamentoDTO = new AtributoAndamentoDTO();
                            $atributoAndamentoRN = new AtributoAndamentoRN();
                            $objAtributoAndamentoDTO->retNumIdAtividade();
                            $objAtributoAndamentoDTO->setStrNome('DOCUMENTO');
                            $objAtributoAndamentoDTO->setStrIdTarefaModuloTarefa('MD_COR_RETORNO_AR');
                            $objAtributoAndamentoDTO->setStrValor($objMdCorRetornoArDocDTO->getStrProtocoloFormatadoDocumento());
                            $arrObjAtributoAndamento = $atributoAndamentoRN->listarRN1367($objAtributoAndamentoDTO);
                            //Aviso de Recebimento dos Correios referente ao Documento @DOCUMENTO@, Código de Rastreamento @CODIGO_RASTREAMENTO_OBJETO_CORREIOS@, foi retornado em @DATA_RETORNO_AR_CORREIOS@, com a situação @SITUACAO_RETORNO_AR@ @MOTIVO_OBJETO_DEVOLVIDO@
                            $objAtributoAndamentoDTONovo = new AtributoAndamentoDTO();
                            $objAtributoAndamentoDTONovo->setNumIdAtividade($arrObjAtributoAndamento[0]->getNumIdAtividade());
                            $objAtributoAndamentoDTONovo->setStrNome('MOTIVO_OBJETO_DEVOLVIDO');
                            $objAtributoAndamentoDTONovo->setStrIdTarefaModuloTarefa('MD_COR_RETORNO_AR');
                            $objAtributoAndamentoDTONovo->retTodos();
                            $arrObjAtributoAndamentoNovo = $atributoAndamentoRN->listarRN1367($objAtributoAndamentoDTONovo);
                            $objAtributoAndamentoDTONovo = $arrObjAtributoAndamentoNovo[0];
                            $objAtributoAndamentoDTONovo->setStrValor($strMotivoDevolucao);
                            $atributoAndamentoRN->alterarRN1364($objAtributoAndamentoDTONovo);

                            $objAtributoAndamentoDTONovo = new AtributoAndamentoDTO();
                            $objAtributoAndamentoDTONovo->setNumIdAtividade($arrObjAtributoAndamento[0]->getNumIdAtividade());
                            $objAtributoAndamentoDTONovo->setStrNome('SITUACAO_RETORNO_AR');
                            $objAtributoAndamentoDTONovo->setStrIdTarefaModuloTarefa('MD_COR_RETORNO_AR');
                            $objAtributoAndamentoDTONovo->retTodos();
                            $arrObjAtributoAndamentoNovo = $atributoAndamentoRN->listarRN1367($objAtributoAndamentoDTONovo);
                            $objAtributoAndamentoDTONovo = $arrObjAtributoAndamentoNovo[0];
                            $objAtributoAndamentoDTONovo->setStrValor($strSituacaoRetorno);
                            $atributoAndamentoRN->alterarRN1364($objAtributoAndamentoDTONovo);
                        }
                    }
                }
                $objMdCorRetornoArDocDTO->setNumIdMdCorRetornoAr($idRetorno);
                $objMdCorRetornoArDocDTO->setStrSinStatus('S');
                $objMdCorRetornoArDocDTO->setStrNomeArquivoPdf($noArquivo);
                $objMdCorRetornoArDocDTO->setNumIdStatusProcess($stProcessamento);
                $objMdCorRetornoArDocDTO->setNumIdDocumentoPrincipal($idDocumentoPrincipal);
                if ($idDocumentoPrincipal) {
                    if ($objDocumentoDTO->getStrStaEstadoProcedimento() == ProtocoloRN::$TE_PROCEDIMENTO_ANEXADO) {
                        $objMdCorRetornoArDocDTO->setNumIdStatusProcess(MdCorRetornoArRN::$STA_RETORNO_AR_NAO_PROCESSADO);
                        $objMdCorRetornoArDocDTO->setNumIdSubStatusProcess(MdCorRetornoArRN::$SUBSTA_RETORNO_AR_PROC_ANEXADO);
                    } elseif ($objDocumentoDTO->getStrStaEstadoProcedimento() == ProtocoloRN::$TE_PROCEDIMENTO_SOBRESTADO) {
                        $objMdCorRetornoArDocDTO->setNumIdStatusProcess(MdCorRetornoArRN::$STA_RETORNO_AR_NAO_PROCESSADO);
                        $objMdCorRetornoArDocDTO->setNumIdSubStatusProcess(MdCorRetornoArRN::$SUBSTA_RETORNO_AR_PROC_SOBRESTADO);
                    } elseif ($objDocumentoDTO->getStrStaEstadoProcedimento() == ProtocoloRN::$TE_PROCEDIMENTO_BLOQUEADO) {
                        $objMdCorRetornoArDocDTO->setNumIdStatusProcess(MdCorRetornoArRN::$STA_RETORNO_AR_NAO_PROCESSADO);
                        $objMdCorRetornoArDocDTO->setNumIdSubStatusProcess(MdCorRetornoArRN::$SUBSTA_RETORNO_AR_PROC_BLOQUEADO);
                    }
                    $objMdCorRetornoArDocDTO->setStrCodigoRastreamento($coRastreamento);

                    if (!is_null($arrObjMdCorRetornoArDTO) && !is_null($idDocumentoPrincipal)) {
                        $this->alterar($objMdCorRetornoArDocDTO);
                    } else {
                        $this->cadastrar($objMdCorRetornoArDocDTO);
                    }
                } else {
                    $objMdCorRetornoArDocDTO->setNumIdStatusProcess(MdCorRetornoArRN::$STA_RETORNO_AR_NAO_PROCESSADO);
                    $objMdCorRetornoArDocDTO->setNumIdSubStatusProcess(MdCorRetornoArRN::$SUBSTA_RETORNO_AR_NAO_IDENTIFICADO);
                    if ($dados['hdnArquivoAlteracao'] == '') {
                        $this->cadastrar($objMdCorRetornoArDocDTO);
                    }
                }


                if ($idDocumentoPrincipal) {
                    if (!in_array($objDocumentoDTO->getStrStaEstadoProcedimento(), $arrStatusEstado)) {
                        $andamentoDocumentoRN = new DocumentoRN();
                        $andamentoDocumentoDTO = new DocumentoDTO();
                        $andamentoDocumentoDTO->setDblIdDocumento($idDocumentoPrincipal);
                        $andamentoDocumentoDTO->retDblIdProcedimento();
                        $andamentoDocumentoDTO->retStrProtocoloDocumentoFormatado();
                        $objAndamentoDocumentoDTO = $andamentoDocumentoRN->consultarRN0005($andamentoDocumentoDTO);
                        $objEntradaLancarAndamentoAPI = new EntradaLancarAndamentoAPI();
                        $objEntradaLancarAndamentoAPI->setIdProcedimento($objAndamentoDocumentoDTO->getDblIdProcedimento());
                        $objEntradaLancarAndamentoAPI->setIdTarefaModulo('MD_COR_RETORNO_AR');


                        $strRastreio = " não identificado";
                        if (!is_null($coRastreamento)) {
                            $strRastreio = $coRastreamento;
                        }
                        $arrObjAtributoAndamentoAPI = array();
                        $arrObjAtributoAndamentoAPI[] = $mdCorExpedicaoSolicitadaRN->_retornaObjAtributoAndamentoAPI('DOCUMENTO', $objAndamentoDocumentoDTO->getStrProtocoloDocumentoFormatado(), $idDocumentoPrincipal);
                        $arrObjAtributoAndamentoAPI[] = $mdCorExpedicaoSolicitadaRN->_retornaObjAtributoAndamentoAPI('CODIGO_RASTREAMENTO_OBJETO_CORREIOS', $strRastreio);
                        $arrObjAtributoAndamentoAPI[] = $mdCorExpedicaoSolicitadaRN->_retornaObjAtributoAndamentoAPI('DATA_RETORNO_AR_CORREIOS', $dtRetorno);
                        $arrObjAtributoAndamentoAPI[] = $mdCorExpedicaoSolicitadaRN->_retornaObjAtributoAndamentoAPI('SITUACAO_RETORNO_AR', $strSituacaoRetorno);
                        $arrObjAtributoAndamentoAPI[] = $mdCorExpedicaoSolicitadaRN->_retornaObjAtributoAndamentoAPI('MOTIVO_OBJETO_DEVOLVIDO', $strMotivoDevolucao);

                        $objEntradaLancarAndamentoAPI->setAtributos($arrObjAtributoAndamentoAPI);

                        $objSeiRN = new SeiRN();
                        if ($dados['hdnArquivoAlteracao'] == '') {
                            $objSeiRN->lancarAndamento($objEntradaLancarAndamentoAPI);                            
                            
                            $objDocumentoDTO = new MdCorRetornoArDocDTO();
                            $objDocumentoDTO->setNumIdDocumentoPrincipal($idDocumentoPrincipal);
                            $objDocumentoDTO->retNumIdMdCorRetornoArDoc();
                            $objDocumentoDTO->retStrProtocoloFormatadoDocumento();
                            $objDocumentoDTO->retNumIdProcedimento();
                            $objDocumentoDTO->retDtaDataAr();
                            $arrObjDocumentoDTO = $this->consultar($objMdCorRetornoArDocDTO);
                            
                            $objAtividadeRN = new AtividadeRN();
                            $objAtividadeDTO = new AtividadeDTO();
                            $objAtividadeDTO->setDistinct(true);
                            $objAtividadeDTO->retNumIdAtividade();
                            $objAtividadeDTO->retStrSiglaUnidade();
                            $objAtividadeDTO->retStrDescricaoUnidade();
                            $objAtividadeDTO->retNumIdUsuarioAtribuicao();
                            $objAtividadeDTO->setDblIdProtocolo($arrObjDocumentoDTO->getNumIdProcedimento());
                            $objAtividadeDTO->setOrdNumIdAtividade(InfraDTO::$TIPO_ORDENACAO_DESC);
                            $objAtividadeDTO->setNumMaxRegistrosRetorno(1);
                            if ($idUnidadeParametro){
                                $objAtividadeDTO->setNumIdUnidade($idUnidadeParametro);
                            }
                            $arrObjAtividadeDTO = $objAtividadeRN->listarRN0036($objAtividadeDTO);

                            $arrParams = array(
                                $idUnidadeParametro,
                                $arrObjDocumentoDTO->getNumIdProcedimento(),
                                $arrObjAtividadeDTO[0]->getNumIdUsuarioAtribuicao()
                            );

                            $this->reenviarReatribuirUnidade($arrParams);
                            
                        }
                    }
                }

                if ($idDocumentoPrincipal) {
                    $parametros = array();
                    $parametros[] = $idDocumentoPrincipal;
                    $parametros[] = $codigoRastreamento;
                    $this->salvarRecebidoExpedicao($parametros);
                    SessaoSEI::getInstance()->setBolHabilitada(true);
                    SessaoSEI::getInstance()->simularLogin(null, null, $idUsuarioLogado, $idUnidadeLogado);
                }
            }
            if(isset($_SESSION['ARQUIVO_ZIP'])){
                unlink($_SESSION['ARQUIVO_ZIP']);
                unset($_SESSION['ARQUIVO_ZIP']);
                $pasta = $_SESSION['ARQUIVO_PASTA'];
                $arquivosPasta = scandir($pasta);
                if (is_dir($pasta)) {
                    foreach ($arquivosPasta as $arquivo) {
                        if ($arquivo != '.' && $arquivo != '..') {
                            unlink($pasta . '/' . $arquivo);
                        }
                    }
                    rmdir($pasta);
                }
                unset($_SESSION['ARQUIVO_PASTA']);
            }
            //Auditoria
            return $dados;
        } catch (Exception $e) {
            throw new InfraException('Erro cadastrando .', $e);
        }
    }

    private function _adicionarPdfProcedimento($dados, $chave, $arrMdCorParametroArDTO) {

        
        $idDocumentoPrincipal = $dados['idDocumentoPrincipal'][$chave];
        $nuSei = $dados['nu_sei'][$chave];
        $noArquivo = $dados['no_arquivo'][$chave];
        $noDiretorio = substr($dados['hdnArquivoZip'], 0, -4);


        $documentoRN = new DocumentoRN();
        $documentoDTO = new DocumentoDTO();
        $documentoDTO->setDblIdDocumento($idDocumentoPrincipal);
        $documentoDTO->retDblIdProcedimento();
        $documentoDTO->retStrStaNivelAcessoLocalProtocolo();
        $documentoDTO->retNumIdHipoteseLegalProtocolo();
        $documentoDTO->retStrNomeSerie();
        $objRetDocumentoDTO = $documentoRN->consultarRN0005($documentoDTO);

        $nomeArvore = $arrMdCorParametroArDTO->getStrNomeArvore();
        $nomeArvore = str_replace('@tipo_doc_principal_expedido@', $objRetDocumentoDTO->getStrNomeSerie(), $nomeArvore);
        $nomeArvore = substr(str_replace('@numero@', $nuSei, $nomeArvore), 0, 50);

        $idProcedimento = $objRetDocumentoDTO->getDblIdProcedimento();
        $idSerie = $arrMdCorParametroArDTO->getNumIdSerie();
        $idTipoConferencia = $arrMdCorParametroArDTO->getNumIdTipoConferencia();

        $objEntradaConsultaProcApi = new EntradaConsultarProcedimentoAPI();
        $objEntradaConsultaProcApi->setIdProcedimento($idProcedimento);
        $objEntradaConsultaProcApi->setSinRetornarUnidadesProcedimentoAberto('S');
        $objEntradaConsultaProcApi->setSinRetornarUltimoAndamento('S');
        $objEntradaConsultaProcApi->setSinRetornarAndamentoConclusao('N');

        $objSeiRN = new SeiRN();
        $saidaConsultarProcedimentoAPI = $objSeiRN->consultarProcedimento($objEntradaConsultaProcApi);

//        $arrUnidadesAbertas = current($saidaConsultarProcedimentoAPI->getUnidadesProcedimentoAberto());
//        $idUnidade = $arrUnidadesAbertas->getUnidade()->getIdUnidade();
        //criando registro em protocolo
        $objDocumentoDTO = new DocumentoDTO();
        $objDocumentoDTO->setStrNumero($nomeArvore);
        $objDocumentoDTO->setDblIdDocumento(null);
        $objDocumentoDTO->setDblIdProcedimento($idProcedimento);

        $objDocumentoDTO->setNumIdSerie($idSerie);
        $objDocumentoDTO->setNumIdHipoteseLegalProtocolo($objRetDocumentoDTO->getNumIdHipoteseLegalProtocolo());
        $objDocumentoDTO->setStrStaNivelAcessoLocalProtocolo($objRetDocumentoDTO->getStrStaNivelAcessoLocalProtocolo());

        $objDocumentoDTO->setDblIdDocumentoEdoc(null);
        $objDocumentoDTO->setDblIdDocumentoEdocBase(null);
        $objDocumentoDTO->setNumIdUnidadeResponsavel($idUnidade);
        $objDocumentoDTO->setNumIdTipoConferencia($idTipoConferencia);
        $objDocumentoDTO->setStrSinBloqueado('S');
        $objDocumentoDTO->setStrStaDocumento(DocumentoRN::$TD_EXTERNO);

        $objDocumentoDTO->setNumIdTextoPadraoInterno('');
        $objDocumentoDTO->setStrProtocoloDocumentoTextoBase('');

        $objDocumentoAPI = new DocumentoAPI();
        $objDocumentoAPI->setIdProcedimento($idProcedimento);
        $objDocumentoAPI->setTipo(ProtocoloRN::$TP_DOCUMENTO_RECEBIDO);
        $objDocumentoAPI->setIdSerie($objDocumentoDTO->getNumIdSerie());
        $objDocumentoAPI->setData(InfraData::getStrDataAtual());
        $objDocumentoAPI->setSinAssinado('S');
        $objDocumentoAPI->setNumero($nomeArvore);
        $objDocumentoAPI->setSinBloqueado('S');
        $objDocumentoAPI->setIdHipoteseLegal($objDocumentoDTO->getNumIdHipoteseLegalProtocolo());
        $objDocumentoAPI->setNivelAcesso($objDocumentoDTO->getStrStaNivelAcessoLocalProtocolo());
        $objDocumentoAPI->setIdTipoConferencia($objDocumentoDTO->getNumIdTipoConferencia());


        $objDocumentoAPI->setNomeArquivo($noArquivo);
        $objDocumentoAPI->setConteudo(base64_encode(file_get_contents(DIR_SEI_TEMP . '/' . $noDiretorio . '/' . $noArquivo)));

        $objSaidaDocumentoAPI = $objSeiRN->incluirDocumento($objDocumentoAPI);


        return $objSaidaDocumentoAPI->getIdDocumento();
    }

    protected function salvarRecebidoExpedicaoControlado($parametros) {
        $mdCorExpedicaoSolicitadaRN = new MdCorExpedicaoSolicitadaRN();
        $mdCorExpedicaoSolicitadaDTO = new MdCorExpedicaoSolicitadaDTO();
        $mdCorExpedicaoSolicitadaDTO->retNumIdMdCorExpedicaoSolicitada();
        $mdCorExpedicaoSolicitadaDTO->retNumIdMdCorPlp();
        $mdCorExpedicaoSolicitadaDTO->setDblIdDocumentoPrincipal($parametros[0]);
        $mdCorExpedicaoSolicitadaDTO->setStrCodigoRastreamento($parametros[1]);

        $arrDados = $mdCorExpedicaoSolicitadaDTO = $mdCorExpedicaoSolicitadaRN->consultar($mdCorExpedicaoSolicitadaDTO);

        $mdCorExpedicaoSolicitadaDTO->setStrSinRecebido('S');
        $mdCorExpedicaoSolicitadaRN->alterar($mdCorExpedicaoSolicitadaDTO);

        $mdCorExpedicaoSolicitadaDTO = new MdCorExpedicaoSolicitadaDTO();

        $mdCorExpedicaoSolicitadaDTO->setNumIdMdCorPlp($arrDados->getNumIdMdCorPlp());
        $mdCorExpedicaoSolicitadaDTO->setStrSinRecebido('N');
        $qtd = $mdCorExpedicaoSolicitadaRN->contar($mdCorExpedicaoSolicitadaDTO);

        if ($qtd == 0) {
            $mdCorPlpRN = new MdCorPlpRN();
            $mdCorPlpDTO = new MdCorPlpDTO();
            $mdCorPlpDTO->setNumIdMdPlp($arrDados->getNumIdMdCorPlp());
            $mdCorPlpDTO->setStrStaPlp($mdCorPlpRN::$STA_ENTREGUES);
            $mdCorPlpRN->alterar($mdCorPlpDTO);
        }
    }

    public function reenviarReatribuirUnidadeConectado( $params ){
        try{
            $idUnidadeAberta     = $params[0];
            $idProcedimento      = $params[1];
            $idUsuarioAtribuicao = $params[2];

            // Andamento - Processo remetido pela unidade
            $unidadeDTO = new UnidadeDTO();
            $unidadeDTO->retTodos();
            $unidadeDTO->setBolExclusaoLogica(false);
            $unidadeDTO->setNumIdUnidade($idUnidadeAberta);
            $unidadeRN = new UnidadeRN();
            $unidadeDTO = $unidadeRN->consultarRN0125($unidadeDTO);

            $arrObjAtributoAndamentoDTO = array();
            $objAtributoAndamentoDTO = new AtributoAndamentoDTO();
            $objAtributoAndamentoDTO->setStrNome('UNIDADE');
            $objAtributoAndamentoDTO->setStrValor($unidadeDTO->getStrSigla().'¥'.$unidadeDTO->getStrDescricao());
            $objAtributoAndamentoDTO->setStrIdOrigem($unidadeDTO->getNumIdUnidade());
            $arrObjAtributoAndamentoDTO[] = $objAtributoAndamentoDTO;

            $objAtividadeDTO = new AtividadeDTO();
            $objAtividadeDTO->setDblIdProtocolo($idProcedimento);
            $objAtividadeDTO->setNumIdUnidade( $unidadeDTO->getNumIdUnidade() );
            $objAtividadeDTO->setNumIdUnidadeOrigem( $unidadeDTO->getNumIdUnidade() );

            if ( !is_null($idUsuarioAtribuicao) ){
                $objAtividadeDTO->setNumIdUsuarioAtribuicao( $idUsuarioAtribuicao );
            }else{
                $objAtividadeDTO->unSetNumIdUsuarioAtribuicao();
            }

            $objAtividadeDTO->setArrObjAtributoAndamentoDTO($arrObjAtributoAndamentoDTO);
            $objAtividadeDTO->setNumIdTarefa(TarefaRN::$TI_PROCESSO_REMETIDO_UNIDADE);

            $objAtividadeRN = new AtividadeRN();
            $objAtividadeRN->gerarInternaRN0727($objAtividadeDTO);
        }catch(Exception $e){
            throw new InfraException('Erro ao Reenviar e Reatribuir no Retorno de AR.',$e);
        }

    }
    
    
    /*
      protected function desativarControlado($arrObjMdCorRetornoArDocDTO){
      try {

      //Valida Permissao
      ::getInstance()->validarPermissao('md_cor_retorno_ar_doc_desativar');

      //Regras de Negocio
      //$objInfraException = new InfraException();        //$objInfraException = new InfraException();

      //$objInfraException->lancarValidacoes();

      $objMdCorRetornoArDocBD = new MdCorRetornoArDocBD($this->getObjInfraIBanco());
      for($i=0;$i<count($arrObjMdCorRetornoArDocDTO);$i++){
      $objMdCorRetornoArDocBD->desativar($arrObjMdCorRetornoArDocDTO[$i]);
      }

      //Auditoria

      }catch(Exception $e){
      throw new InfraException('Erro desativando .',$e);
      }
      }

      protected function reativarControlado($arrObjMdCorRetornoArDocDTO){
      try {

      //Valida Permissao
      ::getInstance()->validarPermissao('md_cor_retorno_ar_doc_reativar');

      //Regras de Negocio
      //$objInfraException = new InfraException();

      //$objInfraException->lancarValidacoes();

      $objMdCorRetornoArDocBD = new MdCorRetornoArDocBD($this->getObjInfraIBanco());
      for($i=0;$i<count($arrObjMdCorRetornoArDocDTO);$i++){
      $objMdCorRetornoArDocBD->reativar($arrObjMdCorRetornoArDocDTO[$i]);
      }

      //Auditoria

      }catch(Exception $e){
      throw new InfraException('Erro reativando .',$e);
      }
      }

      protected function bloquearControlado(MdCorRetornoArDocDTO $objMdCorRetornoArDocDTO){
      try {

      //Valida Permissao
      ::getInstance()->validarPermissao('md_cor_retorno_ar_doc_consultar');

      //Regras de Negocio
      //$objInfraException = new InfraException();

      //$objInfraException->lancarValidacoes();

      $objMdCorRetornoArDocBD = new MdCorRetornoArDocBD($this->getObjInfraIBanco());
      $ret = $objMdCorRetornoArDocBD->bloquear($objMdCorRetornoArDocDTO);

      //Auditoria

      return $ret;
      }catch(Exception $e){
      throw new InfraException('Erro bloqueando .',$e);
      }
      }

     */
}
