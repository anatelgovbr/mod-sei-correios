<?
/**
* TRIBUNAL REGIONAL FEDERAL DA 4� REGI�O
*
* 08/02/2012 - criado por bcu
*
* Vers�o do Gerador de C�digo: 1.32.1
*
* Vers�o no CVS: $Id$
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