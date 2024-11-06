<?
/**
* TRIBUNAL REGIONAL FEDERAL DA 4ª REGIÃO
*
* 14/11/2017 - criado por ellyson.silva
*
* Versão do Gerador de Código: 1.41.0
*/

require_once dirname(__FILE__).'/../../../SEI.php';

class MdCorObjetoINT extends InfraINT {

  public static function montarSelectIdMdCorTipoObjeto($strPrimeiroItemValor, $strPrimeiroItemDescricao, $strValorItemSelecionado, $numIdMdCorTipoObjeto='', $numIdMdCorContrato=''){
    $objMdCorObjetoDTO = new MdCorObjetoDTO();
    $objMdCorObjetoDTO->retNumIdMdCorEmbalagem();
    $objMdCorObjetoDTO->retNumIdMdCorTipoEmbalagem();

    if ($numIdMdCorTipoObjeto!==''){
        $objMdCorObjetoDTO->setNumIdMdCorTipoEmbalagem($numIdMdCorTipoObjeto);
    }

    if ($numIdMdCorContrato!==''){
        $objMdCorObjetoDTO->setNumIdMdCorContrato($numIdMdCorContrato);
    }

      $objMdCorObjetoDTO->setOrdNumIdMdCorTipoEmbalagem(InfraDTO::$TIPO_ORDENACAO_ASC);

    $objMdCorObjetomRN = new MdCorObjetoRN();
    $arrObjMdCorObjetoDTO = $objMdCorObjetomRN->listar($objMdCorObjetoDTO);

    return parent::montarSelectArrInfraDTO($strPrimeiroItemValor, $strPrimeiroItemDescricao, $strValorItemSelecionado, $arrObjMdCorObjetoDTO, 'IdMdCorObjeto', 'IdMdCorTipoObjeto');
  }

  public static function UTF8_Decode($str){
  	if ( !empty($str) ) {
		return utf8_decode($str);
    }
  }

  public static function trataHoraAndamento( $hr ){
	if ( !empty($hr) ) {
		$arrHr = explode(':' , $hr);
		$arrHr[2] = '00';
		return implode(':' , $arrHr);
	}
  }
}
