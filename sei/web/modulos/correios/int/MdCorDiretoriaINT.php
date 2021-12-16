<?
/**
* TRIBUNAL REGIONAL FEDERAL DA 4ª REGIÃO
*
* 27/10/2017 - criado por augusto.cast
*
* Versão do Gerador de Código: 1.41.0
*/

require_once dirname(__FILE__) . '/../../../SEI.php';

class MdCorDiretoriaINT extends InfraINT {

  public static function montarSelectIdMdCorDiretoria($strPrimeiroItemValor, $strPrimeiroItemDescricao, $strValorItemSelecionado){
    $objMdCorDiretoriaDTO = new MdCorDiretoriaDTO();
    $objMdCorDiretoriaDTO->retNumIdMdCorDiretoria();
    $objMdCorDiretoriaDTO->retStrCodigoDiretoria();
    $objMdCorDiretoriaDTO->retStrDescricaoDiretoria();
    $objMdCorDiretoriaDTO->retStrSiglaDiretoria();

    $objMdCorDiretoriaDTO->setOrdStrCodigoDiretoria(InfraDTO::$TIPO_ORDENACAO_ASC);

    $objMdCorDiretoriaRN = new MdCorDiretoriaRN();
    $arrObjMdCorDiretoriaDTO = $objMdCorDiretoriaRN->listar($objMdCorDiretoriaDTO);



    return parent::montarSelectArrInfraDTO($strPrimeiroItemValor, $strPrimeiroItemDescricao, $strValorItemSelecionado, $arrObjMdCorDiretoriaDTO, 'IdMdCorDiretoria', 'DescricaoDiretoria');
  }
}
