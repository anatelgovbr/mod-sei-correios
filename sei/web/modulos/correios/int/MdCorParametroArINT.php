<?

/**
 * TRIBUNAL REGIONAL FEDERAL DA 4ª REGIÃO
 *
 * 19/06/2018 - criado por augusto.cast
 *
 * Versão do Gerador de Código: 1.41.0
 */
class MdCorParametroArINT extends InfraINT {

    public static function montarSelectIdMdCorParametroAr($strPrimeiroItemValor, $strPrimeiroItemDescricao, $strValorItemSelecionado) {
        $objMdCorParametroArDTO = new MdCorParametroArDTO();
        $objMdCorParametroArDTO->retNumIdMdCorParametroAr();

        $objMdCorParametroArDTO->setOrdNumIdMdCorParametroAr(InfraDTO::$TIPO_ORDENACAO_ASC);

        $objMdCorParametroArRN = new MdCorParametroArRN();
        $arrObjMdCorParametroArDTO = $objMdCorParametroArRN->listar($objMdCorParametroArDTO);

        return parent::montarSelectArrInfraDTO($strPrimeiroItemValor, $strPrimeiroItemDescricao, $strValorItemSelecionado, $arrObjMdCorParametroArDTO, '', 'IdMdCorParametroAr');
    }

    public static function tamanhoMaximoZipPermitido() {
        $objInfraParametro = new InfraParametro(BancoSEI::getInstance());
        $numTamMbDocExterno = $objInfraParametro->getValor('SEI_TAM_MB_DOC_EXTERNO');

        $objArquivoExtensaoRN = new ArquivoExtensaoRN();
        $objArquivoExtensaoDTO = new ArquivoExtensaoDTO();
        $objArquivoExtensaoDTO->setStrExtensao('zip');
        $objArquivoExtensaoDTO->setStrSinAtivo('S');
        $objArquivoExtensaoDTO->retNumTamanhoMaximo();

        $arrObjArquivoExtensao = $objArquivoExtensaoRN->consultar($objArquivoExtensaoDTO);

        $tamanhoMaximo = $arrObjArquivoExtensao->getNumTamanhoMaximo();

        if (is_null($tamanhoMaximo)) {
            $tamanhoMaximo = $numTamMbDocExterno;
        }

        return $tamanhoMaximo;
    }

    public static function tamanhoMaximoPdfPermitido() {
        $objInfraParametro = new InfraParametro(BancoSEI::getInstance());
        $numTamMbDocExterno = $objInfraParametro->getValor('SEI_TAM_MB_DOC_EXTERNO');

        $objArquivoExtensaoRN = new ArquivoExtensaoRN();
        $objArquivoExtensaoDTO = new ArquivoExtensaoDTO();
        $objArquivoExtensaoDTO->setStrExtensao('pdf');
        $objArquivoExtensaoDTO->setStrSinAtivo('S');
        $objArquivoExtensaoDTO->retNumTamanhoMaximo();

        $arrObjArquivoExtensao = $objArquivoExtensaoRN->consultar($objArquivoExtensaoDTO);

        $tamanhoMaximo = $arrObjArquivoExtensao->getNumTamanhoMaximo();

        if (is_null($tamanhoMaximo)) {
            $tamanhoMaximo = $numTamMbDocExterno;
        }

        return $tamanhoMaximo;
    }

    public static function consultarDadosDocumento($arrPost) {
        $nuDocumento = $arrPost['nuSeiDocumento'];

        $documentoRN = new DocumentoRN();
        $documentoDTO = new DocumentoDTO();
        $documentoDTO->setStrProtocoloDocumentoFormatado($nuDocumento);
        $documentoDTO->retStrProtocoloDocumentoFormatado();
        $documentoDTO->retStrNomeSerie();
        $documentoDTO->retStrNumero();
        $documentoDTO->retDblIdDocumento();
        $documentoDTO->retStrProtocoloProcedimentoFormatado();

        $arrDocumentoDTO = $documentoRN->consultarRN0005($documentoDTO);
        if (is_null($arrDocumentoDTO)) {
            return '<error>Documento não Encontrado</error>';
        }

        $mdCorExpedicaoSolicitadaRN = new MdCorExpedicaoSolicitadaRN();
        $mdCorExpedicaoSolicitadaDTO = new MdCorExpedicaoSolicitadaDTO();
        $mdCorExpedicaoSolicitadaDTO->retStrCodigoRastreamento();
        $mdCorExpedicaoSolicitadaDTO->retStrSinRecebido();
        $mdCorExpedicaoSolicitadaDTO->retNumIdMdCorParamArInfrigencia();
        $mdCorExpedicaoSolicitadaDTO->setDblIdDocumentoPrincipal($arrDocumentoDTO->getDblIdDocumento());
        $mdCorExpedicaoSolicitadaDTO->setStrSinRecebido("N");
        $mdCorExpedicaoSolicitadaDTO->setNumMaxRegistrosRetorno(1);
        $mdCorExpedicaoSolicitadaDTO->setOrdDthDataSolicitacao(InfraDTO::$TIPO_ORDENACAO_DESC);
        $objExpedicaoSolicitadaDTO = $mdCorExpedicaoSolicitadaRN->consultar($mdCorExpedicaoSolicitadaDTO);

        if (is_null($objExpedicaoSolicitadaDTO)) {
            $mdCorExpedicaoSolicitadaDTO->setStrSinRecebido("S");
            $objExpedicaoSolicitadaDTO = $mdCorExpedicaoSolicitadaRN->consultar($mdCorExpedicaoSolicitadaDTO);

            if (is_null($objExpedicaoSolicitadaDTO)) {
                return '<error>Documento não Encontrado</error>';
            }
        }
        
        $xml = '<documento>';
        $xml .= '<nu-documento>' . $arrDocumentoDTO->getStrNomeSerie() . ' ' . $arrDocumentoDTO->getStrNumero() . '</nu-documento>';
        $xml .= '<id-documento-principal>' . $arrDocumentoDTO->getDblIdDocumento() . '</id-documento-principal>';
        $xml .= '<nu-processo>' . $arrDocumentoDTO->getStrProtocoloProcedimentoFormatado() . '</nu-processo>';
        if (!is_null($objExpedicaoSolicitadaDTO->getStrCodigoRastreamento())) {
            $xml .= '<codigo-rastreamento>' . $objExpedicaoSolicitadaDTO->getStrCodigoRastreamento() . '</codigo-rastreamento>';
            $xml .= '<sin-recebido>' . $objExpedicaoSolicitadaDTO->getStrSinRecebido() . '</sin-recebido>';
            $xml .= '<num-objeto-devolvido>' . $objExpedicaoSolicitadaDTO->getNumIdMdCorParamArInfrigencia() . '</num-objeto-devolvido>';
            $strLinkCodigoRastreio = SessaoSEI::getInstance()->assinarLink('controlador.php?acao=md_cor_expedicao_detalhar_rastreio&acao_origem=md_cor_retorno_ar_processar&co_rastreio=' . $objExpedicaoSolicitadaDTO->getStrCodigoRastreamento());
            $xml .= '<link-rastreamento>' . htmlspecialchars($strLinkCodigoRastreio) . '</link-rastreamento>';
        }
        $xml .= '</documento>';
        return $xml;
    }

    public static function gerarUrl($arrPost) {
        $idRetorno = $arrPost['arr'];

        $strAutenticar = SessaoSEI::getInstance()->assinarLink('controlador.php?acao=md_cor_retorno_ar_autenticar&id_retorno=' . $idRetorno);
        $xml = '<url><![CDATA[' . $strAutenticar . ']]></url>';

        return $xml;
    }

    public static function montarSelectUnidadeGeradora($strPrimeiroItemValor, $strPrimeiroItemDescricao, $strValorItemSelecionado, $idProtocolo) {
        $idUnidadeReabrirProcesso = null;
        $objAtividadeDTO = new AtividadeDTO();
        $objAtividadeDTO->setDblIdProcedimentoProtocolo($idProtocolo);
        $objAtividadeDTO->setDistinct(true);
        $objAtividadeRN = new AtividadeRN();
        $objAtividadeDTO->retNumIdUnidade();
        $objAtividadeDTO->retStrSiglaUnidade();
        $objAtividadeDTO->setOrdStrSiglaUnidade(InfraDTO::$TIPO_ORDENACAO_ASC);
        $arrObjAtividadeDTO = $objAtividadeRN->listarRN0036($objAtividadeDTO);


        return parent::montarSelectArrInfraDTO($strPrimeiroItemValor, $strPrimeiroItemDescricao, $strValorItemSelecionado, $arrObjAtividadeDTO, 'IdUnidade', 'SiglaUnidade');
    }

}
