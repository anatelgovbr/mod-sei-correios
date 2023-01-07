<?
/**
* TRIBUNAL REGIONAL FEDERAL DA 4ª REGIÃO
*
* 22/12/2016 - criado por marcelo.cast
*
* Versão do Gerador de Código: 1.39.0
*/

require_once dirname(__FILE__) . '/../../../SEI.php';


class MdCorMapUnidServicoINT extends InfraINT {

  public static function montarSelectIdMdCorMapUnidServico($strPrimeiroItemValor, $strPrimeiroItemDescricao, $strValorItemSelecionado, $idUnidadeSolicitante = null, $consultar = false ){
    
  	$objMdCorMapUnidServicoDTO = new MdCorMapUnidServicoDTO();
    $objMdCorMapUnidServicoDTO->retNumIdMdCorMapUnidServico();
    $objMdCorMapUnidServicoDTO->retNumIdMdCorServicoPostal();
    $objMdCorMapUnidServicoDTO->retStrNomeServico();
    $objMdCorMapUnidServicoDTO->retStrDescricaoServico();
    if(!$consultar){
      $objMdCorMapUnidServicoDTO->setStrSinAtivoContrato('S');
    }

    if( $idUnidadeSolicitante != null ){
    	$objMdCorMapUnidServicoDTO->setNumIdUnidadeSolicitante( $idUnidadeSolicitante );
    }

    if ($strValorItemSelecionado!=null){
      $objMdCorMapUnidServicoDTO->setBolExclusaoLogica(false);
      $objMdCorMapUnidServicoDTO->adicionarCriterio(array('SinAtivo','IdMdCorServicoPostal'),array(InfraDTO::$OPER_IGUAL,InfraDTO::$OPER_IGUAL),array('S',$strValorItemSelecionado),InfraDTO::$OPER_LOGICO_OR);
    }else{
      $objMdCorMapUnidServicoDTO->setStrSinAtivo('S');
    }

    $objMdCorMapUnidServicoDTO->setStrSinAtivoServicoPostal('S');

    $objMdCorMapUnidServicoDTO->setOrdNumIdMdCorMapUnidServico(InfraDTO::$TIPO_ORDENACAO_ASC);
    $objMdCorMapUnidServicoRN = new MdCorMapUnidServicoRN();
    $arrObjMdCorMapUnidServicoDTO = $objMdCorMapUnidServicoRN->listar($objMdCorMapUnidServicoDTO);


    return parent::montarSelectArrInfraDTO($strPrimeiroItemValor, $strPrimeiroItemDescricao, $strValorItemSelecionado, $arrObjMdCorMapUnidServicoDTO, 'IdMdCorServicoPostal', 'DescricaoServico');
  }

}
?>