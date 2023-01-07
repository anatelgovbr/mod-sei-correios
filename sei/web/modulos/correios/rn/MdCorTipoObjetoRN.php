<?
/**
* TRIBUNAL REGIONAL FEDERAL DA 4ª REGIÃO
*
* 14/11/2017 - criado por ellyson.silva
*
* Versão do Gerador de Código: 1.41.0
*/

require_once dirname(__FILE__).'/../../../SEI.php';

class MdCorTipoObjetoRN extends InfraRN {

  public function __construct(){
    parent::__construct();
  }

  protected function inicializarObjInfraIBanco(){
    return BancoSEI::getInstance();
  }

  private function validarStrNome(MdCorTipoObjetoDTO $objMdCorTipoObjetoDTO, InfraException $objInfraException){
    if (InfraString::isBolVazia($objMdCorTipoObjetoDTO->getStrNome())){
      $objInfraException->adicionarValidacao('nome não informado.');
    }else{
      $objMdCorTipoObjetoDTO->setStrNome(trim($objMdCorTipoObjetoDTO->getStrNome()));

      if (strlen($objMdCorTipoObjetoDTO->getStrNome())>100){
        $objInfraException->adicionarValidacao('nome possui tamanho superior a 100 caracteres.');
      }
    }
  }

  protected function cadastrarControlado(MdCorTipoObjetoDTO $objMdCorTipoObjetoDTO) {
    try{

      //Valida Permissao
      SessaoSEI::getInstance()->validarPermissao('md_cor_tipo_objeto_cadastrar');

      //Regras de Negocio
      $objInfraException = new InfraException();

      $this->validarStrNome($objMdCorTipoObjetoDTO, $objInfraException);

      $objInfraException->lancarValidacoes();

      $objMdCorTipoObjetoBD = new MdCorTipoObjetoBD($this->getObjInfraIBanco());
      $ret = $objMdCorTipoObjetoBD->cadastrar($objMdCorTipoObjetoDTO);

      //Auditoria

      return $ret;

    }catch(Exception $e){
      throw new InfraException('Erro cadastrando tipo de objeto.',$e);
    }
  }

  protected function alterarControlado(MdCorTipoObjetoDTO $objMdCorTipoObjetoDTO){
    try {

      //Valida Permissao
  	   SessaoSEI::getInstance()->validarPermissao('md_cor_tipo_objeto_alterar');

      //Regras de Negocio
      $objInfraException = new InfraException();

      if ($objMdCorTipoObjetoDTO->isSetStrNome()){
        $this->validarStrNome($objMdCorTipoObjetoDTO, $objInfraException);
      }

      $objInfraException->lancarValidacoes();

      $objMdCorTipoObjetoBD = new MdCorTipoObjetoBD($this->getObjInfraIBanco());
      $objMdCorTipoObjetoBD->alterar($objMdCorTipoObjetoDTO);

      //Auditoria

    }catch(Exception $e){
      throw new InfraException('Erro alterando tipo de objeto.',$e);
    }
  }

  protected function excluirControlado($arrObjMdCorTipoObjetoDTO){
    try {

      //Valida Permissao
      SessaoSEI::getInstance()->validarPermissao('md_cor_tipo_objeto_excluir');

      //Regras de Negocio
      //$objInfraException = new InfraException();

      //$objInfraException->lancarValidacoes();

      $objMdCorTipoObjetoBD = new MdCorTipoObjetoBD($this->getObjInfraIBanco());
      for($i=0;$i<count($arrObjMdCorTipoObjetoDTO);$i++){
        $objMdCorTipoObjetoBD->excluir($arrObjMdCorTipoObjetoDTO[$i]);
      }

      //Auditoria

    }catch(Exception $e){
      throw new InfraException('Erro excluindo tipo de objeto.',$e);
    }
  }

  protected function consultarConectado(MdCorTipoObjetoDTO $objMdCorTipoObjetoDTO){
    try {

      //Valida Permissao
      SessaoSEI::getInstance()->validarPermissao('md_cor_tipo_objeto_consultar');

      //Regras de Negocio
      //$objInfraException = new InfraException();

      //$objInfraException->lancarValidacoes();

      $objMdCorTipoObjetoBD = new MdCorTipoObjetoBD($this->getObjInfraIBanco());
      $ret = $objMdCorTipoObjetoBD->consultar($objMdCorTipoObjetoDTO);

      //Auditoria

      return $ret;
    }catch(Exception $e){
      throw new InfraException('Erro consultando tipo de objeto.',$e);
    }
  }

  protected function listarConectado(MdCorTipoObjetoDTO $objMdCorTipoObjetoDTO) {
    try {

      //Valida Permissao
      SessaoSEI::getInstance()->validarPermissao('md_cor_tipo_objeto_listar');

      //Regras de Negocio
      //$objInfraException = new InfraException();

      //$objInfraException->lancarValidacoes();

      $objMdCorTipoObjetoBD = new MdCorTipoObjetoBD($this->getObjInfraIBanco());
      $ret = $objMdCorTipoObjetoBD->listar($objMdCorTipoObjetoDTO);

      //Auditoria

      return $ret;

    }catch(Exception $e){
      throw new InfraException('Erro listando tipos de embalagens.',$e);
    }
  }

  protected function contarConectado(MdCorTipoObjetoDTO $objMdCorTipoObjetoDTO){
    try {

      //Valida Permissao
      SessaoSEI::getInstance()->validarPermissao('md_cor_tipo_objeto_listar');

      //Regras de Negocio
      //$objInfraException = new InfraException();

      //$objInfraException->lancarValidacoes();

      $objMdCorTipoObjetoBD = new MdCorTipoObjetoBD($this->getObjInfraIBanco());
      $ret = $objMdCorTipoObjetoBD->contar($objMdCorTipoObjetoDTO);

      //Auditoria

      return $ret;
    }catch(Exception $e){
      throw new InfraException('Erro contando tipos de embalagens.',$e);
    }
  }
/* 
  protected function desativarControlado($arrObjMdCorTipoObjetoDTO){
    try {

      //Valida Permissao
      SessaoSEI::getInstance()->validarPermissao('md_cor_tipo_objeto_desativar');

      //Regras de Negocio
      //$objInfraException = new InfraException();

      //$objInfraException->lancarValidacoes();

      $objMdCorTipoObjetoBD = new MdCorTipoObjetoBD($this->getObjInfraIBanco());
      for($i=0;$i<count($arrObjMdCorTipoObjetoDTO);$i++){
        $objMdCorTipoObjetoBD->desativar($arrObjMdCorTipoObjetoDTO[$i]);
      }

      //Auditoria

    }catch(Exception $e){
      throw new InfraException('Erro desativando tipo de objeto.',$e);
    }
  }

  protected function reativarControlado($arrObjMdCorTipoObjetoDTO){
    try {

      //Valida Permissao
      SessaoSEI::getInstance()->validarPermissao('md_cor_tipo_objeto_reativar');

      //Regras de Negocio
      //$objInfraException = new InfraException();

      //$objInfraException->lancarValidacoes();

      $objMdCorTipoObjetoBD = new MdCorTipoObjetoBD($this->getObjInfraIBanco());
      for($i=0;$i<count($arrObjMdCorTipoObjetoDTO);$i++){
        $objMdCorTipoObjetoBD->reativar($arrObjMdCorTipoObjetoDTO[$i]);
      }

      //Auditoria

    }catch(Exception $e){
      throw new InfraException('Erro reativando tipo de objeto.',$e);
    }
  }

  protected function bloquearControlado(MdCorTipoObjetoDTO $objMdCorTipoObjetoDTO){
    try {

      //Valida Permissao
      SessaoSEI::getInstance()->validarPermissao('md_cor_tipo_objeto_consultar');

      //Regras de Negocio
      //$objInfraException = new InfraException();

      //$objInfraException->lancarValidacoes();

      $objMdCorTipoObjetoBD = new MdCorTipoObjetoBD($this->getObjInfraIBanco());
      $ret = $objMdCorTipoObjetoBD->bloquear($objMdCorTipoObjetoDTO);

      //Auditoria

      return $ret;
    }catch(Exception $e){
      throw new InfraException('Erro bloqueando tipo de objeto.',$e);
    }
  }

 */
}
