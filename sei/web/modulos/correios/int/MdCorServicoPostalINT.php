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

	public static function retornarServicosPostais($strNumeroContratoCorreio, $strCnpj) {
		$objMdCorAdmIntegracaoRN = new MdCorAdmIntegracaoRN();

		$objMdCorIntegServPostal = $objMdCorAdmIntegracaoRN->buscaIntegracaoPorFuncionalidade(MdCorAdmIntegracaoRN::$SERV_POSTAL);
		if ( empty( $objMdCorIntegServPostal ) || is_array($objMdCorIntegServPostal) && isset($objMdCorIntegServPostal['suc']) && $objMdCorIntegServPostal['suc'] === false )
		    return self::retornarXmlServicosPostais(['suc' => false , 'msg' => 'Mapeamento de Integração '. MdCorAdmIntegracaoRN::$STR_SERV_POSTAL .' não existe ou está inativo.']);

		$arrParametro = [
			'endpoint' => $objMdCorIntegServPostal->getStrUrlOperacao(),
			'token'    => $objMdCorIntegServPostal->getStrToken(),
			'expiraEm' => $objMdCorIntegServPostal->getDthDataExpiraToken(),
		];

		$ret = $objMdCorAdmIntegracaoRN->verificaTokenExpirado($arrParametro, $objMdCorIntegServPostal);
		if ( is_array( $ret ) && array_key_exists('suc',$ret) && $ret['suc'] === false )  return self::retornarXmlServicosPostais($ret);

		$objMdCorApiServPostal = new MdCorApiRestRN($arrParametro);
		$xml = self::retornarXmlServicosPostais( $objMdCorApiServPostal->buscarServicosPostais($strNumeroContratoCorreio, $strCnpj) );
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


  public static function getInfoServicoPostalPorId( $id ){
  	$objMdCorServicoPostalDTO = new MdCorServicoPostalDTO();
	  $objMdCorServicoPostalRN  = new MdCorServicoPostalRN();

	  $objMdCorServicoPostalDTO->setNumIdMdCorServicoPostal( $id );
	  $objMdCorServicoPostalDTO->retStrSinAnexarMidia();
	  $objMdCorServicoPostalDTO->retStrSinServicoCobrar();
	  $objMdCorServicoPostalDTO->retStrNome();
	  $objMdCorServicoPostalDTO->retStrDescricao();

	  $objMdCorServicoPostalDTO = $objMdCorServicoPostalRN->consultar( $objMdCorServicoPostalDTO );

	  return $objMdCorServicoPostalDTO;
  }

  public static function validaArqExt($arrParams){
	  $arrIdsProt            = ( array_key_exists('arrIdsProt',$arrParams) && !empty( $arrParams['arrIdsProt'] ) )  ? $arrParams['arrIdsProt'] : null;
	  $arrDescDoc            = ( array_key_exists('arrDescDoc',$arrParams ) && !empty( $arrParams['arrDescDoc'] ) ) ? $arrParams['arrDescDoc'] : null;
	  $strPermiteGravarMidia = ( array_key_exists('strPermiteGravarMidia',$arrParams ) && !empty( $arrParams['strPermiteGravarMidia'] ) ) ? $arrParams['strPermiteGravarMidia'] : null;
	  $arrExibir             = [];
	  $arrNaoExibir          = [];

	  if ( $arrIdsProt ) {
          foreach ($arrIdsProt as $k => $prot) {
              $somenteMidia = filter_var(MdCorExpedicaoSolicitadaProtocoloAnexoINT::verificarAnexoSomenteMidia($prot, false), FILTER_VALIDATE_BOOLEAN);

              if ($strPermiteGravarMidia == 'N') {
                  if ($somenteMidia) {
                      array_push($arrNaoExibir, $prot . '#' . $arrDescDoc[$k]);
                  } else {
                      array_push($arrExibir, $prot . '#' . $arrDescDoc[$k]);
                  }
              } else {
                  array_push($arrExibir, $prot . '#' . $arrDescDoc[$k]);
              }
          }
      }
	  $strListaExibir    = !empty($arrExibir)    ? '<Exibir>'.implode(';' ,$arrExibir ).'</Exibir>' : '';
	  $strListaNaoExibir = !empty($arrNaoExibir) ? '<NaoExibir>'.implode(';' ,$arrNaoExibir ).'</NaoExibir>' : '';

	  return $strListaExibir . $strListaNaoExibir;
  }

  public static function retornarXmlServicosPostais($dados){
      // se ocorreu algum erro, gera uma exception
	  $msg = 'Erro na busca dos Serviços Postais:';

	  if ( array_key_exists('suc',$dados) && $dados['suc'] === false ) {
          $msg .= "<br/>" . $dados['msg'];
          return "<Erros><Msg><![CDATA[$msg]]></Msg></Erros>";
	  }

	  // continua fluxo
	  $xml = "<ServicoPostalLista>";
	  foreach($dados['itens'] as $servico)
	  {
		  $xml .= "<ServicoPostal><Descricao>{$servico['descricao']}</Descricao><Id>{$servico['codigo']}</Id><Codigo>{$servico['codigo']}</Codigo></ServicoPostal>";
	  }
	  $xml .= "</ServicoPostalLista>";
	  return $xml;
  }

}
?>