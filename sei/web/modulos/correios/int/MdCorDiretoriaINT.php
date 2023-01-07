<?
/**
* TRIBUNAL REGIONAL FEDERAL DA 4� REGI�O
*
* 27/10/2017 - criado por augusto.cast
*
* Vers�o do Gerador de C�digo: 1.41.0
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
