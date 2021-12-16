<?
/**
* TRIBUNAL REGIONAL FEDERAL DA 4ª REGIÃO
*
* 29/06/2018 - criado por augusto.cast
*
* Versão do Gerador de Código: 1.41.0
*/

require_once dirname(__FILE__) . '/../../../SEI.php';

class MdCorRetornoArDocINT extends InfraINT {

  public static function montarSelectIdCorRetornoArDoc($strPrimeiroItemValor, $strPrimeiroItemDescricao, $strValorItemSelecionado){
    $objMdCorRetornoArDocDTO = new MdCorRetornoArDocDTO();
    $objMdCorRetornoArDocDTO->retNumIdCorRetornoArDoc();

    $objMdCorRetornoArDocDTO->setOrdNumIdCorRetornoArDoc(InfraDTO::$TIPO_ORDENACAO_ASC);

    $objMdCorRetornoArDocRN = new MdCorRetornoArDocRN();
    $arrObjMdCorRetornoArDocDTO = $objMdCorRetornoArDocRN->listar($objMdCorRetornoArDocDTO);

    return parent::montarSelectArrInfraDTO($strPrimeiroItemValor, $strPrimeiroItemDescricao, $strValorItemSelecionado, $arrObjMdCorRetornoArDocDTO, '', 'IdCorRetornoArDoc');
  }
}
