<?
/**
* TRIBUNAL REGIONAL FEDERAL DA 4ª REGIÃO
*
* 14/11/2017 - criado por ellyson.silva
*
* Versão do Gerador de Código: 1.41.0
*/

require_once dirname(__FILE__).'/../../../SEI.php';

class MdCorTipoObjetoINT extends InfraINT {

  public static function montarSelectNome($strPrimeiroItemValor, $strPrimeiroItemDescricao, $strValorItemSelecionado, $idContrato = null){
    $objMdCorTipoObjetoDTO = new MdCorTipoObjetoDTO();
    $objMdCorTipoObjetoDTO->retNumIdMdCorTipoObjeto();
    $objMdCorTipoObjetoDTO->retStrNome();

      $objMdCorTipoObjetoDTO->setOrdStrNome(InfraDTO::$TIPO_ORDENACAO_ASC);

      $objMdCorObjetoDTO = new MdCorObjetoDTO();
      $objMdCorObjetoDTO->retNumIdMdCorTipoObjeto();
      $objMdCorObjetoDTO->setNumIdMdCorTipoObjeto($strValorItemSelecionado, InfraDTO::$OPER_DIFERENTE);
      $objMdCorObjetoDTO->setStrSinAtivo('S');
      if(!is_null($idContrato)){
        $objMdCorObjetoDTO->setNumIdMdCorContrato($idContrato);
      }
      $objMdCorObjetoDTO->setDistinct(true);

      $objMdCorObjetoRN = new MdCorObjetoRN();
      $arrObjMdCorObjetoDTO = $objMdCorObjetoRN->listar($objMdCorObjetoDTO);

      if(count($arrObjMdCorObjetoDTO)){
          $objMdCorTipoObjetoDTO->setNumIdMdCorTipoObjeto(InfraArray::converterArrInfraDTO($arrObjMdCorObjetoDTO,'IdMdCorTipoObjeto' ), InfraDTO::$OPER_NOT_IN);
      }

    $objMdCorTipoObjetoRN = new MdCorTipoObjetoRN();
    $arrObjMdCorTipoObjetoDTO = $objMdCorTipoObjetoRN->listar($objMdCorTipoObjetoDTO);

    return parent::montarSelectArrInfraDTO($strPrimeiroItemValor, $strPrimeiroItemDescricao, $strValorItemSelecionado, $arrObjMdCorTipoObjetoDTO, 'IdMdCorTipoObjeto', 'Nome');
  }
}
