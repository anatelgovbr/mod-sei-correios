<?

/**
 * TRIBUNAL REGIONAL FEDERAL DA 4ª REGIÃO
 *
 * 21/08/2018 - criado por augusto.cast
 *
 * Versão do Gerador de Código: 1.41.0
 */
require_once dirname(__FILE__) . '/../../../SEI.php';

class MdCorArCobrancaINT extends InfraINT {

    public static function montarSelectIdMdCorArCobranca($strPrimeiroItemValor, $strPrimeiroItemDescricao, $strValorItemSelecionado) {
        $objMdCorArCobrancaDTO = new MdCorArCobrancaDTO();
        $objMdCorArCobrancaDTO->retNumIdMdCorArCobranca();

        $objMdCorArCobrancaDTO->setOrdNumIdMdCorArCobranca(InfraDTO::$TIPO_ORDENACAO_ASC);

        $objMdCorArCobrancaRN = new MdCorArCobrancaRN();
        $arrObjMdCorArCobrancaDTO = $objMdCorArCobrancaRN->listar($objMdCorArCobrancaDTO);

        return parent::montarSelectArrInfraDTO($strPrimeiroItemValor, $strPrimeiroItemDescricao, $strValorItemSelecionado, $arrObjMdCorArCobrancaDTO, '', 'IdMdCorArCobranca');
    }

    public static function selecionarDestinatario($dados) {

        $objContextoContatoDTO = new ContatoDTO();
        $objContextoContatoDTO->retTodos();
        $objContextoContatoDTO->setStrIdxContato('%'.$_POST['palavras_pesquisa'].'%', InfraDTO::$OPER_LIKE);
        $objContatoRN = new ContatoRN();
        $arrContextoContatoDTO = $objContatoRN->listarComEndereco($objContextoContatoDTO);

        return $arrContextoContatoDTO;
    }

}
