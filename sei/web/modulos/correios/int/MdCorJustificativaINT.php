<?

require_once dirname(__FILE__) . '/../../../SEI.php';

class MdCorJustificativaINT extends InfraINT
{

    public static function montarSelectJustificativa($strItemValor, $strItemDescricao, $strValorItemSelecionado, $isStAtivo = true)
    {
        $objMdCorJustDTO = new MdCorJustificativaDTO();
        $objMdCorJustDTO->retNumIdMdCorJustificativa();
        $objMdCorJustDTO->retStrNome();
        $objMdCorJustDTO->setDistinct(true);
        if($isStAtivo) {
            $objMdCorJustDTO->setStrSinAtivo('S');
        }
        $objMdCorJustDTO->setOrdStrNome(InfraDTO::$TIPO_ORDENACAO_ASC);

        $objMdCorJustRN = new MdCorJustificativaRN();
        $arrobjMdCorJustDTO = $objMdCorJustRN->listar($objMdCorJustDTO);

        return parent::montarSelectArrInfraDTO($strItemValor, $strItemDescricao, $strValorItemSelecionado, $arrobjMdCorJustDTO, 'IdMdCorJustificativa', 'Nome');
    }

}
