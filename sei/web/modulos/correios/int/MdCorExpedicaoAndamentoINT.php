<?
/**
* TRIBUNAL REGIONAL FEDERAL DA 4ª REGIÃO
*
* 07/06/2017 - criado por marcelo.cast
*
* Versão do Gerador de Código: 1.40.1
*/

require_once dirname(__FILE__) . '/../../../SEI.php';

class MdCorExpedicaoAndamentoINT extends InfraINT {

  public static function montarSelectIdMdCorExpedicaoAndamento($strPrimeiroItemValor, $strPrimeiroItemDescricao, $strValorItemSelecionado){
    $objMdCorExpedicaoAndamentoDTO = new MdCorExpedicaoAndamentoDTO();
    $objMdCorExpedicaoAndamentoDTO->retNumIdMdCorExpedicaoAndamento();
    $objMdCorExpedicaoAndamentoDTO->retNumIdMdCorExpedicaoAndamento();

    $objMdCorExpedicaoAndamentoDTO->setOrdNumIdMdCorExpedicaoAndamento(InfraDTO::$TIPO_ORDENACAO_ASC);

    $objMdCorExpedicaoAndamentoRN = new MdCorExpedicaoAndamentoRN();
    $arrObjMdCorExpedicaoAndamentoDTO = $objMdCorExpedicaoAndamentoRN->listar($objMdCorExpedicaoAndamentoDTO);

    return parent::montarSelectArrInfraDTO($strPrimeiroItemValor, $strPrimeiroItemDescricao, $strValorItemSelecionado, $arrObjMdCorExpedicaoAndamentoDTO, 'IdMdCorExpedicaoAndamento', 'IdMdCorExpedicaoAndamento');
  }
}
?>