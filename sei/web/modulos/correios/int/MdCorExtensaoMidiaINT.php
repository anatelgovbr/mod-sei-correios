<?
/**
 * TRIBUNAL REGIONAL FEDERAL DA 4ª REGIÃO
 *
 * 20/12/2016 - criado por Wilton Júnior
 *
 * Versão do Gerador de Código: 1.39.0
 */

require_once dirname(__FILE__) . '/../../../SEI.php';

class MdCorExtensaoMidiaINT extends InfraINT
{

    public static function montarSelectNomeExtensao($strPrimeiroItemValor, $strPrimeiroItemDescricao, $strValorItemSelecionado)
    {
        $objMdCorExtensaoMidiaDTO = new MdCorExtensaoMidiaDTO();
        $objMdCorExtensaoMidiaDTO->retTodos(true);

        if ($strValorItemSelecionado != null) {
            $objMdCorExtensaoMidiaDTO->setBolExclusaoLogica(false);
            $objMdCorExtensaoMidiaDTO->adicionarCriterio(array('SinAtivo', 'IdMdCorExtensaoMidia'), array(InfraDTO::$OPER_IGUAL, InfraDTO::$OPER_IGUAL), array('S', $strValorItemSelecionado), InfraDTO::$OPER_LOGICO_OR);
        }

        $objMdCorExtensaoMidiaDTO->setOrdStrNomeExtensao(InfraDTO::$TIPO_ORDENACAO_ASC);

        $objMdCorExtensaoMidiaRN = new MdCorExtensaoMidiaRN();
        $arrObjMdCorExtensaoMidiaDTO = $objMdCorExtensaoMidiaRN->listar($objMdCorExtensaoMidiaDTO);

        return parent::montarSelectArrInfraDTO($strPrimeiroItemValor, $strPrimeiroItemDescricao, $strValorItemSelecionado, $arrObjMdCorExtensaoMidiaDTO, 'IdArquivoExtensao', 'NomeExtensao');
    }
}

?>