<?
/**
* TRIBUNAL REGIONAL FEDERAL DA 4ª REGIÃO
*
* 23/12/2016 - criado por Wilton Júnior
*
* Versão do Gerador de Código: 1.39.0
*/

require_once dirname(__FILE__).'/../../../SEI.php';

class MdCorServicoPostalINT extends InfraINT {

  public static function montarSelectIdMdCorServicoPostal($strPrimeiroItemValor, $strPrimeiroItemDescricao, $strValorItemSelecionado, $numIdMdCorContrato=''){
    $objMdCorServicoPostalDTO = new MdCorServicoPostalDTO();
    $objMdCorServicoPostalDTO->retNumIdMdCorServicoPostal();
    $objMdCorServicoPostalDTO->retNumIdMdCorServicoPostal();

    if ($numIdMdCorContrato!==''){
      $objMdCorServicoPostalDTO->setNumIdMdCorContrato($numIdMdCorContrato);
    }

    if ($strValorItemSelecionado!=null){
      $objMdCorServicoPostalDTO->setBolExclusaoLogica(false);
      $objMdCorServicoPostalDTO->adicionarCriterio(array('SinAtivo','IdMdCorServicoPostal'),array(InfraDTO::$OPER_IGUAL,InfraDTO::$OPER_IGUAL),array('S',$strValorItemSelecionado),InfraDTO::$OPER_LOGICO_OR);
    }

    $objMdCorServicoPostalDTO->setOrdNumIdMdCorServicoPostal(InfraDTO::$TIPO_ORDENACAO_ASC);

    $objMdCorServicoPostalRN = new MdCorServicoPostalRN();
    $arrObjMdCorServicoPostalDTO = $objMdCorServicoPostalRN->listar($objMdCorServicoPostalDTO);

    return parent::montarSelectArrInfraDTO($strPrimeiroItemValor, $strPrimeiroItemDescricao, $strValorItemSelecionado, $arrObjMdCorServicoPostalDTO, 'IdMdCorServicoPostal', 'IdMdCorServicoPostal');
  }

  public static function montarSelectId_Descricao_MdCorServicoPostal($strPrimeiroItemValor, $strPrimeiroItemDescricao, $strValorItemSelecionado, $numIdMdCorContrato='', $campoordenacao='IdMdCorServicoPostal'){
    $objMdCorServicoPostalDTO = new MdCorServicoPostalDTO();
    $objMdCorServicoPostalDTO->retNumIdMdCorServicoPostal();
    $objMdCorServicoPostalDTO->retStrNome();
    $objMdCorServicoPostalDTO->retStrNome();
    $objMdCorServicoPostalDTO->retStrDescricao();
    $objMdCorServicoPostalDTO->setStrSinServicoCobrar('N');

    if ($numIdMdCorContrato!==''){
      $objMdCorServicoPostalDTO->setNumIdMdCorContrato($numIdMdCorContrato);
    }

    if ($strValorItemSelecionado!=null){
      $objMdCorServicoPostalDTO->setBolExclusaoLogica(false);
      $objMdCorServicoPostalDTO->adicionarCriterio(array('SinAtivo','IdMdCorServicoPostal'),array(InfraDTO::$OPER_IGUAL,InfraDTO::$OPER_IGUAL),array('S',$strValorItemSelecionado),InfraDTO::$OPER_LOGICO_OR);
    }

	$objMdCorServicoPostalDTO->setOrd($campoordenacao, InfraDTO::$TIPO_ORDENACAO_ASC);
	
    $objMdCorServicoPostalRN = new MdCorServicoPostalRN();
    $arrObjMdCorServicoPostalDTO = $objMdCorServicoPostalRN->listar($objMdCorServicoPostalDTO);

    return parent::montarSelectArrInfraDTO($strPrimeiroItemValor, $strPrimeiroItemDescricao, $strValorItemSelecionado, $arrObjMdCorServicoPostalDTO, 'IdMdCorServicoPostal', 'Descricao');
  }

  public static function retornarServicosPostais($strNumeroContratoCorreio, $strNumeroCartaoPostagem, $strUrlWebservice, $usuario, $senha) {
      $xml = MdCorClientWsRN::buscarServicosPostais($strNumeroContratoCorreio,$strNumeroCartaoPostagem, $strUrlWebservice, $usuario, $senha);
      return $xml;
  }

    public static function montarSelectIdDescricaoMdCorServicoPostalSolicitacaoExpedicao($strPrimeiroItemValor, $strPrimeiroItemDescricao, $strValorItemSelecionado, $numIdMdCorContrato='', $campoordenacao='IdMdCorServicoPostal'){
        $objMdCorServicoPostalDTO = new MdCorServicoPostalDTO();
        $objMdCorServicoPostalDTO->retNumIdMdCorServicoPostal();
        $objMdCorServicoPostalDTO->retStrNome();
        $objMdCorServicoPostalDTO->retStrDescricao();

        if ($numIdMdCorContrato!==''){
            $objMdCorServicoPostalDTO->setNumIdMdCorContrato($numIdMdCorContrato);
        }

        if ($strValorItemSelecionado!=null){
            $objMdCorServicoPostalDTO->setBolExclusaoLogica(false);
            $objMdCorServicoPostalDTO->adicionarCriterio(array('SinAtivo','IdMdCorServicoPostal'),array(InfraDTO::$OPER_IGUAL,InfraDTO::$OPER_IGUAL),array('S',$strValorItemSelecionado),InfraDTO::$OPER_LOGICO_OR);
        }

        $objMdCorServicoPostalDTO->setOrd($campoordenacao, InfraDTO::$TIPO_ORDENACAO_ASC);

        $objMdCorServicoPostalRN = new MdCorServicoPostalRN();
        $arrObjMdCorServicoPostalDTO = $objMdCorServicoPostalRN->listar($objMdCorServicoPostalDTO);

        return parent::montarSelectArrInfraDTO($strPrimeiroItemValor, $strPrimeiroItemDescricao, $strValorItemSelecionado, $arrObjMdCorServicoPostalDTO, 'IdMdCorServicoPostal', 'Nome');
    }

}
?>