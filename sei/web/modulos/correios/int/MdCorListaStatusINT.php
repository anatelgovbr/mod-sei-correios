<?
/**
 * TRIBUNAL REGIONAL FEDERAL DA 4ª REGIÃO
 *
 * 14/06/2017 - criado por jaqueline.cast
 *
 * Versão do Gerador de Código: 1.40.1
 */

require_once dirname(__FILE__) . '/../../../SEI.php';

class MdCorListaStatusINT extends InfraINT
{

    public static function montarSelectStatus($strPrimeiroItemValor, $strPrimeiroItemDescricao, $strValorItemSelecionado)
    {
        $objMdCorListaStatusDTO = new MdCorListaStatusDTO();
        $objMdCorListaStatusDTO->retNumStatus();

        $objMdCorListaStatusDTO->setOrdNumStatus(InfraDTO::$TIPO_ORDENACAO_ASC);

        $objMdCorListaStatusRN = new MdCorListaStatusRN();
        $arrObjMdCorListaStatusDTO = $objMdCorListaStatusRN->listar($objMdCorListaStatusDTO);

        return parent::montarSelectArrInfraDTO($strPrimeiroItemValor, $strPrimeiroItemDescricao, $strValorItemSelecionado, $arrObjMdCorListaStatusDTO, '', 'Status');
    }

    public static function montarSelectSituacaoRastreio($strPrimeiroItemValor, $strPrimeiroItemDescricao, $varValorItemSelecionado)
    {
        $arr = array(
                        'P' => 'Postado',
                        'T' => 'Em Trânsito',
                        'S' => 'Sucesso na Entrega',
                        'I' => 'Insucesso na Entrega');

        return self::montarSelectArray($strPrimeiroItemValor, $strPrimeiroItemDescricao, $varValorItemSelecionado, $arr);
    }

}

?>
