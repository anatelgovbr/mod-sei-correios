<?
/**
* TRIBUNAL REGIONAL FEDERAL DA 4ª REGIÃO
*
* 21/12/2016 - criado por CAST
*
* Versão do Gerador de Código: 1.39.0
*/

//require_once dirname(__FILE__).'/../SEI.php';
require_once dirname(__FILE__).'/../../../SEI.php';

class MdCorSerieExpINT extends InfraINT {

  public static function montarSelectIdSerie($strPrimeiroItemValor, $strPrimeiroItemDescricao, $strValorItemSelecionado){
    $objMdCorSerieExpDTO = new MdCorSerieExpDTO();
    $objMdCorSerieExpDTO->retNumIdSerie();
    $objMdCorSerieExpDTO->retNumIdSerie();

    if ($strValorItemSelecionado!=null){
      $objMdCorSerieExpDTO->setBolExclusaoLogica(false);
      $objMdCorSerieExpDTO->adicionarCriterio(array('SinAtivo','IdSerie'),array(InfraDTO::$OPER_IGUAL,InfraDTO::$OPER_IGUAL),array('S',$strValorItemSelecionado),InfraDTO::$OPER_LOGICO_OR);
    }

    $objMdCorSerieExpDTO->setOrdNumIdSerie(InfraDTO::$TIPO_ORDENACAO_ASC);

    $objMdCorSerieExpRN = new MdCorSerieExpRN();
    $arrObjMdCorSerieExpDTO = $objMdCorSerieExpRN->listar($objMdCorSerieExpDTO);

    return parent::montarSelectArrInfraDTO($strPrimeiroItemValor, $strPrimeiroItemDescricao, $strValorItemSelecionado, $arrObjMdCorSerieExpDTO, 'IdSerie', 'IdSerie');
  }
  
  //espelhada de SerieINT::autoCompletarSerie
  public static function autoCompletarSerieExpedicao($strPalavrasPesquisa,$strStaAplicabilidade=null){
    $objSerieDTO = new SerieDTO();
    $objSerieDTO->retNumIdSerie();
    $objSerieDTO->retStrNome();

    //Permiti destinatário
    $objSerieDTO->setStrSinDestinatario('S');
        
    if ($strStaAplicabilidade!=null){
      if (strpos($strStaAplicabilidade,',')!==false){
        $objSerieDTO->setStrStaAplicabilidade(explode(',',$strStaAplicabilidade),InfraDTO::$OPER_IN);
      } else {
        $objSerieDTO->setStrStaAplicabilidade($strStaAplicabilidade);
      }
    }
    $objSerieDTO->setNumMaxRegistrosRetorno(50);
    $objSerieDTO->setOrdStrNome(InfraDTO::$TIPO_ORDENACAO_ASC);

    $objSerieRN = new SerieRN();

    $arrObjSerieDTO = $objSerieRN->listarRN0646($objSerieDTO);

    $strPalavrasPesquisa = trim($strPalavrasPesquisa);
    if ($strPalavrasPesquisa != ''){
      $ret = array();
      $strPalavrasPesquisa = strtolower($strPalavrasPesquisa);
      foreach($arrObjSerieDTO as $objSerieDTO){
        if (strpos(strtolower($objSerieDTO->getStrNome()),$strPalavrasPesquisa)!==false){
          $ret[] = $objSerieDTO;
        }
      }
    }else{
      $ret = $arrObjSerieDTO;
    }
    return $ret;
  }
  
}
?>