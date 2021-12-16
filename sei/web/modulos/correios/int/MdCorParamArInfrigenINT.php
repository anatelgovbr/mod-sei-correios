<?
/**
* TRIBUNAL REGIONAL FEDERAL DA 4ª REGIÃO
*
* 03/07/2018 - criado por augusto.cast
*
* Versão do Gerador de Código: 1.41.0
*/

require_once dirname(__FILE__) . '/../../../SEI.php';

class MdCorParamArInfrigenINT extends InfraINT {

  public static function montarSelectIdMdCorParamArInfrigencia($strPrimeiroItemValor, $strPrimeiroItemDescricao, $strValorItemSelecionado){
    $objMdCorParamArInfrigenDTO = new MdCorParamArInfrigenDTO();
    $objMdCorParamArInfrigenDTO->retNumIdMdCorParamArInfrigencia();
    $objMdCorParamArInfrigenDTO->retStrMotivoInfrigencia();

    if ($strValorItemSelecionado!=null){
      $objMdCorParamArInfrigenDTO->setBolExclusaoLogica(false);
      $objMdCorParamArInfrigenDTO->adicionarCriterio(array('SinAtivo','SinInfrigencia'),array(InfraDTO::$OPER_IGUAL,InfraDTO::$OPER_IGUAL),array('S',$strValorItemSelecionado),InfraDTO::$OPER_LOGICO_OR);
    }

    $objMdCorParamArInfrigenDTO->setOrdNumIdMdCorParamArInfrigencia(InfraDTO::$TIPO_ORDENACAO_ASC);

    $objMdCorParamArInfrigenRN = new MdCorParamArInfrigenRN();
    $arrObjMdCorParamArInfrigenDTO = $objMdCorParamArInfrigenRN->listar($objMdCorParamArInfrigenDTO);

    return parent::montarSelectArrInfraDTO($strPrimeiroItemValor, $strPrimeiroItemDescricao, $strValorItemSelecionado, $arrObjMdCorParamArInfrigenDTO, 'IdMdCorParamArInfrigencia', 'MotivoInfrigencia');
  }
}
