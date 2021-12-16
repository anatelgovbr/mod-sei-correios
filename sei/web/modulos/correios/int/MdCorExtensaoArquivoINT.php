<?
/**
* TRIBUNAL REGIONAL FEDERAL DA 4ª REGIÃO
*
* 08/02/2012 - criado por bcu
*
* Versão do Gerador de Código: 1.32.1
*
* Versão no CVS: $Id$
*/

require_once dirname(__FILE__).'/../../../SEI.php';

class MdCorExtensaoArquivoINT extends InfraINT {

  /*
   * @author Alan Campos <alan.campos@castgroup.com.br>
   * 
   */
  
  public static function autoCompletarExtensao($strExtensao){

	$objMdCorExtensaoArquivoDTO = new MdCorExtensaoArquivoDTO();
	$objMdCorExtensaoArquivoDTO->retNumIdArquivoExtensao();
	$objMdCorExtensaoArquivoDTO->retStrExtensao();
	$objMdCorExtensaoArquivoDTO->retStrDescricao();

	$objMdCorExtensaoArquivoDTO->setOrdStrExtensao(InfraDTO::$TIPO_ORDENACAO_ASC);
  
	if ($strExtensao!=''){
		$objMdCorExtensaoArquivoDTO->setStrPalavrasPesquisa($strExtensao);
	}

	$objMdCorExtensaoArquivoDTO->setNumMaxRegistrosRetorno(50);
	$objArquivoExtensaoPeticionamentoRN = new MdCorExtensaoArquivoRN();
	$arrObjArquivoPeticionamentoDTO = $objArquivoExtensaoPeticionamentoRN->listarAutoComplete($objMdCorExtensaoArquivoDTO);

  	return $arrObjArquivoPeticionamentoDTO;
  }
}
?>