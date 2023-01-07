<?
/**
* TRIBUNAL REGIONAL FEDERAL DA 4ª REGIÃO
*
* 04/12/2017 - criado por augusto.cast
*
* Versão do Gerador de Código: 1.41.0
*/

require_once dirname(__FILE__).'/../../../SEI.php';

class MdCorTipoCorrespondencINT extends InfraINT {

  public static function montarSelectIdMdCorTipoCorrespondenc($strPrimeiroItemValor, $strPrimeiroItemDescricao, $strValorItemSelecionado){
    $objMdCorTipoCorrespondencDTO = new MdCorTipoCorrespondencDTO();
    $objMdCorTipoCorrespondencDTO->retNumIdMdCorTipoCorrespondenc();
    $objMdCorTipoCorrespondencDTO->retStrNomeTipo();
    $objMdCorTipoCorrespondencDTO->retStrSinAr();

    $objMdCorTipoCorrespondencDTO->setOrdNumIdMdCorTipoCorrespondenc(InfraDTO::$TIPO_ORDENACAO_ASC);

    $objMdCorTipoCorrespondencRN = new MdCorTipoCorrespondencRN();
    $arrObjMdCorTipoCorrespondencDTO = $objMdCorTipoCorrespondencRN->listar($objMdCorTipoCorrespondencDTO);

    foreach ($arrObjMdCorTipoCorrespondencDTO as $chave=>$objMdCorTipoCorrespondencDTO){
      $arrObjMdCorTipoCorrespondencDTO[$chave]->setNumIdMdCorTipoCorrespondenc($objMdCorTipoCorrespondencDTO->getNumIdMdCorTipoCorrespondenc().'|'.$objMdCorTipoCorrespondencDTO->getStrSinAr());
    }

    return parent::montarSelectArrInfraDTO($strPrimeiroItemValor, $strPrimeiroItemDescricao, $strValorItemSelecionado, $arrObjMdCorTipoCorrespondencDTO, 'IdMdCorTipoCorrespondenc', 'NomeTipo');
  }
}
