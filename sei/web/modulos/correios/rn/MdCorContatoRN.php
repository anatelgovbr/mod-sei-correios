<?
/**
* TRIBUNAL REGIONAL FEDERAL DA 4ª REGIÃO
*
* 07/06/2017 - criado por marcelo.cast
*
* Versão do Gerador de Código: 1.40.1
*/

require_once dirname(__FILE__) . '/../../../SEI.php';

class MdCorContatoRN extends InfraRN {

  public function __construct(){
    parent::__construct();
  }

  protected function inicializarObjInfraIBanco(){
    return BancoSEI::getInstance();
  }

  protected function cadastrarControlado(MdCorContatoDTO $objMdCorContatoDTO) {
    
  	try{

      //Valida Permissao - chamada sempre a partir da RN de expedicao solicitada
  	  SessaoSEI::getInstance()->validarPermissao('md_cor_expedicao_solicitada_cadastrar');

      //Regras de Negocio
      $objInfraException = new InfraException();


      $objInfraException->lancarValidacoes();

      $objMdCorContatoBD = new MdCorContatoBD($this->getObjInfraIBanco());
      $ret = $objMdCorContatoBD->cadastrar($objMdCorContatoDTO);

      //Auditoria

      return $ret;

    }catch(Exception $e){
      throw new InfraException('Erro cadastrando .',$e);
    }
  }

  protected function alterarControlado(MdCorContatoDTO $objMdCorContatoDTO){
    
  	try {

    	//Valida Permissao - chamada sempre a partir da RN de expedicao solicitada
    	SessaoSEI::getInstance()->validarPermissao('md_cor_expedicao_solicitada_cadastrar');

      //Regras de Negocio
      $objInfraException = new InfraException();


      $objInfraException->lancarValidacoes();

      $objMdCorContatoBD = new MdCorContatoBD($this->getObjInfraIBanco());
      $objMdCorContatoBD->alterar($objMdCorContatoDTO);

      //Auditoria

    }catch(Exception $e){
      throw new InfraException('Erro alterando .',$e);
    }
  }

  protected function excluirControlado($arrObjMdCorContatoDTO){
    try {

      //Valida Permissao - chamada sempre a partir da RN de expedicao solicitada
      SessaoSEI::getInstance()->validarPermissao('md_cor_expedicao_solicitada_cadastrar');

      //Regras de Negocio
      //$objInfraException = new InfraException();

      //$objInfraException->lancarValidacoes();

      $objMdCorContatoBD = new MdCorContatoBD($this->getObjInfraIBanco());
      for($i=0;$i<count($arrObjMdCorContatoDTO);$i++){
        $objMdCorContatoBD->excluir($arrObjMdCorContatoDTO[$i]);
      }

      //Auditoria

    }catch(Exception $e){
      throw new InfraException('Erro excluindo .',$e);
    }
  }

  protected function consultarConectado(MdCorContatoDTO $objMdCorContatoDTO){
    
  	try {

      //Valida Permissao
//      SessaoSEI::getInstance()->validarPermissao('md_cor_expedicao_anexo_consultar');

      //Regras de Negocio
      //$objInfraException = new InfraException();

      //$objInfraException->lancarValidacoes();

      $objMdCorContatoBD = new MdCorContatoBD($this->getObjInfraIBanco());
      $ret = $objMdCorContatoBD->consultar($objMdCorContatoDTO);

      //Auditoria

      return $ret;
    }catch(Exception $e){
      throw new InfraException('Erro consultando .',$e);
    }
  }

  protected function listarConectado(MdCorContatoDTO $objMdCorContatoDTO) {
    
  	try {

      //Valida Permissao
      SessaoSEI::getInstance()->validarPermissao('md_cor_contato_listar');

      //Regras de Negocio
      //$objInfraException = new InfraException();

      //$objInfraException->lancarValidacoes();

      $objMdCorContatoBD = new MdCorContatoBD($this->getObjInfraIBanco());
      $ret = $objMdCorContatoBD->listar($objMdCorContatoDTO);

      //Auditoria

      return $ret;

    }catch(Exception $e){
      throw new InfraException('Erro listando .',$e);
    }
  }

  protected function contarConectado(MdCorContatoDTO $objMdCorContatoDTO){
    
  	try {

      //Valida Permissao
      SessaoSEI::getInstance()->validarPermissao('md_cor_contato_listar');

      //Regras de Negocio
      //$objInfraException = new InfraException();

      //$objInfraException->lancarValidacoes();

      $objMdCorContatoBD = new MdCorContatoBD($this->getObjInfraIBanco());
      $ret = $objMdCorContatoBD->contar($objMdCorContatoDTO);

      //Auditoria

      return $ret;
    }catch(Exception $e){
      throw new InfraException('Erro contando .',$e);
    }
  }

}
?>