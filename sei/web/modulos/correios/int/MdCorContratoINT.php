<?

/**
 * TRIBUNAL REGIONAL FEDERAL DA 4ª REGIÃO
 *
 * 22/12/2016 - criado por Wilton Júnior
 *
 * Versão do Gerador de Código: 1.39.0
 */
require_once dirname(__FILE__) . '/../../../SEI.php';

class MdCorContratoINT extends InfraINT {

    public static function montarSelectIdMdCorContrato($strPrimeiroItemValor, $strPrimeiroItemDescricao, $strValorItemSelecionado) {
        $objMdCorContratoDTO = new MdCorContratoDTO();
        $objMdCorContratoDTO->retNumIdMdCorContrato();
        $objMdCorContratoDTO->retNumIdMdCorContrato();

        if ($strValorItemSelecionado != null) {
            $objMdCorContratoDTO->setBolExclusaoLogica(false);
            $objMdCorContratoDTO->adicionarCriterio(array('SinAtivo', 'IdMdCorContrato'), array(InfraDTO::$OPER_IGUAL, InfraDTO::$OPER_IGUAL), array('S', $strValorItemSelecionado), InfraDTO::$OPER_LOGICO_OR);
        }

        $objMdCorContratoDTO->setOrdNumIdMdCorContrato(InfraDTO::$TIPO_ORDENACAO_ASC);

        $objMdCorContratoRN = new MdCorContratoRN();
        $arrObjMdCorContratoDTO = $objMdCorContratoRN->listar($objMdCorContratoDTO);

        return parent::montarSelectArrInfraDTO($strPrimeiroItemValor, $strPrimeiroItemDescricao, $strValorItemSelecionado, $arrObjMdCorContratoDTO, 'IdMdCorContrato', 'IdMdCorContrato');
    }

    public static function montarSelectId_NumeroContrato_MdCorContrato($strPrimeiroItemValor, $strPrimeiroItemDescricao, $strValorItemSelecionado) {
        $objMdCorContratoDTO = new MdCorContratoDTO();
        $objMdCorContratoDTO->retNumIdMdCorContrato();
        $objMdCorContratoDTO->retStrNumeroContrato();
        $objMdCorContratoDTO->retStrNumeroContratoCorreio();
        $objMdCorContratoDTO->adicionarCriterio(array('SinAtivo'), array(InfraDTO::$OPER_IGUAL), array('S'));
        if ($strValorItemSelecionado != null) {
            $objMdCorContratoDTO->setBolExclusaoLogica(false);
            $objMdCorContratoDTO->adicionarCriterio(array('SinAtivo', 'IdMdCorContrato'), array(InfraDTO::$OPER_IGUAL, InfraDTO::$OPER_IGUAL), array('S', $strValorItemSelecionado), InfraDTO::$OPER_LOGICO_OR);
        } else {
            
        }

        $objMdCorContratoDTO->setOrdNumIdMdCorContrato(InfraDTO::$TIPO_ORDENACAO_ASC);

        $objMdCorContratoRN = new MdCorContratoRN();
        $arrObjMdCorContratoDTO = $objMdCorContratoRN->listar($objMdCorContratoDTO);

        return parent::montarSelectArrInfraDTO($strPrimeiroItemValor, $strPrimeiroItemDescricao, $strValorItemSelecionado, $arrObjMdCorContratoDTO, array('IdMdCorContrato', 'NumeroContratoCorreio'), 'NumeroContrato');
    }

    /**
     * Função responsável por gerar o XML para validação do número Processo
     * @param int $numeroProcesso
     * @return string
     * @since  29/12/2016
     * @author Wilton Júnior wilton.junior@castgroup.com.br
     */
    public static function gerarXMLvalidacaoNumeroProcesso($numeroProcesso) {
        $objMdCorContratoRN = new MdCorContratoRN();
        $objProtocoloDTO = new ProtocoloDTO();
        $objProtocoloDTO->setStrProtocoloFormatadoPesquisa($numeroProcesso);
        $objProtocoloDTO = $objMdCorContratoRN->pesquisarProtocoloFormatado($objProtocoloDTO);

        $xml = '<Validacao>';
        if (!is_null($objProtocoloDTO)) {
            $xml .= '<IdProcedimento>' . $objProtocoloDTO->getDblIdProtocolo() . '</IdProcedimento>';
            $xml .= '<TipoProcedimento>' . $objProtocoloDTO->getStrNomeTipoProcedimentoProcedimento() . '</TipoProcedimento>';
            $xml .= '<numeroProcesso>' . $objProtocoloDTO->getStrProtocoloFormatado() . '</numeroProcesso>';
        } else {
            $msg = 'O número de processo indicado não existe no sistema. Verifique se o número está correto e completo, inclusive com o Dígito Verificador.';
        }

        if ($msg != '') {
            $xml .= '<MensagemValidacao>' . $msg . '</MensagemValidacao>';
        }
        $xml .= '</Validacao>';

        return $xml;
    }
    
    /**
     * Função responsável por gerar o XML para validação do Número do Contrato
     * @param str $numeroContrato
     * @return string
     * @since  29/12/2016
     * @author Wilton Júnior wilton.junior@castgroup.com.br
     */
    public static function gerarXMLvalidacaoNumeroContrato($numeroContrato, $idContrato) {
        $objMdCorContratoRN = new MdCorContratoRN();
        $objMdCorContatoDTO = new MdCorContratoDTO();
        $objMdCorContatoDTO->setStrNumeroContratoCorreio($numeroContrato);
        $objMdCorContatoDTO->retNumIdMdCorContrato();
        $objMdCorContatoDTO->retStrNumeroContrato();
        $arrObjMdCorContratoDTO = $objMdCorContratoRN->listar($objMdCorContatoDTO);

        $xml = '<Validacao>';
        if ($arrObjMdCorContratoDTO && $arrObjMdCorContratoDTO[0]->getNumIdMdCorContrato() != $idContrato) {
            $msg = 'O número do contrato com os Correios já está cadastrado no sistema. Por favor digite outro número de contrato.';
        }

        if ($msg != '') {
            $xml .= '<MensagemValidacao>' . $msg . '</MensagemValidacao>';
        }else {
            $xml .= '<MensagemValidacao>false</MensagemValidacao>';
        }
        $xml .= '</Validacao>';

        return $xml;
    }

    /**
     * Função responsável por gerar o XML para validação do número Processo
     * @param int $numeroProcesso
     * @return string
     */
    public static function gerarXMLvalidacaoNumeroProcessoCobranca($numeroProcesso) {
        $objMdCorContratoRN = new MdCorContratoRN();
        $objProtocoloDTO = new ProtocoloDTO();
        $objProtocoloDTO->setStrProtocoloFormatadoPesquisa($numeroProcesso);
        $objProtocoloDTO = $objMdCorContratoRN->pesquisarProtocoloFormatado($objProtocoloDTO);

        $xml = '<Validacao>';
        if (!is_null($objProtocoloDTO)) {
            $xml .= '<IdProcedimento>' . $objProtocoloDTO->getDblIdProtocolo() . '</IdProcedimento>';
            $xml .= '<TipoProcedimento>' . $objProtocoloDTO->getStrNomeTipoProcedimentoProcedimento() . '</TipoProcedimento>';
            $xml .= '<numeroProcesso>' . $objProtocoloDTO->getStrProtocoloFormatado() . '</numeroProcesso>';
        } else {
            $msg = 'O número de processo indicado não existe no sistema. Verifique se o número está correto e completo, inclusive com o Dígito Verificador.';
        }

        if ($msg != '') {
            $xml .= '<MensagemValidacao>' . $msg . '</MensagemValidacao>';
        }


        $idUnidadeReabrirProcesso = null;
        $objAtividadeDTO = new AtividadeDTO();
        $objAtividadeDTO->setDblIdProcedimentoProtocolo($objProtocoloDTO->getDblIdProtocolo());
        $objAtividadeRN = new AtividadeRN();
        $objAtividadeDTO->setDistinct(true);
        $objAtividadeDTO->retDthConclusao();
        $objAtividadeDTO->retNumIdUnidade();
        $objAtividadeDTO->retStrSiglaUnidade();
        $objAtividadeDTO->setOrdDthConclusao(InfraDTO::$TIPO_ORDENACAO_DESC);
        $arrObjAtividadeDTO = $objAtividadeRN->listarRN0036($objAtividadeDTO);

        $arrObjAtividadeExistente = array();

        $xml .= '<Unidades>';
        foreach ($arrObjAtividadeDTO as $unidade) {
            if (!in_array($unidade->getNumIdUnidade(), $arrObjAtividadeExistente)) {
                $xml .= '<option value="' . $unidade->getNumIdUnidade() . '">' . $unidade->getStrSiglaUnidade() . '</option>';
                array_push($arrObjAtividadeExistente, $unidade->getNumIdUnidade());
            }
        }
        $xml .= '</Unidades>';

        $xml .= '</Validacao>';

        return $xml;
    }

}

?>