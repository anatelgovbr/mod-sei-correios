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

class MdCorSerieExpRN extends InfraRN {

  public function __construct(){
    parent::__construct();
  }

  protected function inicializarObjInfraIBanco(){
    return BancoSEI::getInstance();
  }

  private function validarStrSinAtivo(MdCorSerieExpDTO $objMdCorSerieExpDTO, InfraException $objInfraException){
    if (InfraString::isBolVazia($objMdCorSerieExpDTO->getStrSinAtivo())){
      $objInfraException->adicionarValidacao('Sinalizador de Exclusão Lógica não informado.');
    }else{
      if (!InfraUtil::isBolSinalizadorValido($objMdCorSerieExpDTO->getStrSinAtivo())){
        $objInfraException->adicionarValidacao('Sinalizador de Exclusão Lógica inválido.');
      }
    }
  }

  protected function cadastrarControlado(MdCorSerieExpDTO $objMdCorSerieExpDTO) {
    try{
      //Valida Permissao
      SessaoSEI::getInstance()->validarPermissao('md_cor_serie_exp_cadastrar');

      //Regras de Negocio
      $objInfraException = new InfraException();

      //$this->validarStrSinAtivo($objMdCorSerieExpDTO, $objInfraException);

      $objInfraException->lancarValidacoes();

      $objMdCorSerieExpBD = new MdCorSerieExpBD($this->getObjInfraIBanco());
      $ret = $objMdCorSerieExpBD->cadastrar($objMdCorSerieExpDTO);

      //Auditoria

      return $ret;
    }catch(Exception $e){
      throw new InfraException('Erro cadastrando .',$e);
    }
  }
  
 /*
  protected function alterarControlado(MdCorSerieExpDTO $objMdCorSerieExpDTO){
    try {

      //Valida Permissao
      SessaoSEI::getInstance()->validarPermissao('md_cor_serie_exp_alterar');

      //Regras de Negocio
      $objInfraException = new InfraException();

      if ($objMdCorSerieExpDTO->isSetStrSinAtivo()){
        $this->validarStrSinAtivo($objMdCorSerieExpDTO, $objInfraException);
      }

      $objInfraException->lancarValidacoes();

      $objMdCorSerieExpBD = new MdCorSerieExpBD($this->getObjInfraIBanco());
      $objMdCorSerieExpBD->alterar($objMdCorSerieExpDTO);

      //Auditoria

    }catch(Exception $e){
      throw new InfraException('Erro alterando .',$e);
    }
  }
 */
  
  protected function excluirControlado($arrObjMdCorSerieExpDTO){
    try {

      //Valida Permissao
      SessaoSEI::getInstance()->validarPermissao('md_cor_serie_exp_excluir');

      //Regras de Negocio
      //$objInfraException = new InfraException();

      //$objInfraException->lancarValidacoes();

      //if( is_array( $arrObjRelSerieExpedicaoCorreioDTO ) && count( $arrObjRelSerieExpedicaoCorreioDTO ) > 0  ) {
      $objMdCorSerieExpBD = new MdCorSerieExpBD($this->getObjInfraIBanco());
      for($i=0;$i<count($arrObjMdCorSerieExpDTO);$i++){
        $objMdCorSerieExpBD->excluir($arrObjMdCorSerieExpDTO[$i]);
      }
      //}

      //Auditoria

    }catch(Exception $e){
      throw new InfraException('Erro excluindo .',$e);
    }
  }

/*
  protected function consultarConectado(MdCorSerieExpDTO $objMdCorSerieExpDTO){
    try {

      //Valida Permissao
      SessaoSEI::getInstance()->validarPermissao('md_cor_serie_exp_consultar');

      //Regras de Negocio
      //$objInfraException = new InfraException();

      //$objInfraException->lancarValidacoes();

      $objMdCorSerieExpBD = new MdCorSerieExpBD($this->getObjInfraIBanco());
      $ret = $objMdCorSerieExpBD->consultar($objMdCorSerieExpDTO);

      //Auditoria

      return $ret;
    }catch(Exception $e){
      throw new InfraException('Erro consultando .',$e);
    }
  }
*/  

  protected function listarConectado(MdCorSerieExpDTO $objMdCorSerieExpDTO) {
    try {

      //Valida Permissao
      SessaoSEI::getInstance()->validarPermissao('md_cor_serie_exp_listar');

      //Regras de Negocio
      $objInfraException = new InfraException();

      $objInfraException->lancarValidacoes();

      $objMdCorSerieExpBD = new MdCorSerieExpBD($this->getObjInfraIBanco());
      $ret = $objMdCorSerieExpBD->listar($objMdCorSerieExpDTO);
      
      //Auditoria

      return $ret;

    }catch(Exception $e){
      throw new InfraException('Erro listando .',$e);
    }

  }

/*
  protected function contarConectado(MdCorSerieExpDTO $objMdCorSerieExpDTO){
    try {

      //Valida Permissao
      SessaoSEI::getInstance()->validarPermissao('md_cor_serie_exp_listar');

      //Regras de Negocio
      //$objInfraException = new InfraException();

      //$objInfraException->lancarValidacoes();

      $objMdCorSerieExpBD = new MdCorSerieExpBD($this->getObjInfraIBanco());
      $ret = $objMdCorSerieExpBD->contar($objMdCorSerieExpDTO);

      //Auditoria

      return $ret;
    }catch(Exception $e){
      throw new InfraException('Erro contando .',$e);
    }
  }
*/

/*
  protected function desativarControlado($arrObjMdCorSerieExpDTO){
    try {

      //Valida Permissao
      SessaoSEI::getInstance()->validarPermissao('md_cor_serie_exp_desativar');

      //Regras de Negocio
      //$objInfraException = new InfraException();

      //$objInfraException->lancarValidacoes();

      $objMdCorSerieExpBD = new MdCorSerieExpBD($this->getObjInfraIBanco());
      for($i=0;$i<count($arrObjMdCorSerieExpDTO);$i++){
        $objMdCorSerieExpBD->desativar($arrObjMdCorSerieExpDTO[$i]);
      }

      //Auditoria

    }catch(Exception $e){
      throw new InfraException('Erro desativando .',$e);
    }
  }

  protected function reativarControlado($arrObjMdCorSerieExpDTO){
    try {

      //Valida Permissao
      SessaoSEI::getInstance()->validarPermissao('md_cor_serie_exp_reativar');

      //Regras de Negocio
      //$objInfraException = new InfraException();

      //$objInfraException->lancarValidacoes();

      $objMdCorSerieExpBD = new MdCorSerieExpBD($this->getObjInfraIBanco());
      for($i=0;$i<count($arrObjMdCorSerieExpDTO);$i++){
        $objMdCorSerieExpBD->reativar($arrObjMdCorSerieExpDTO[$i]);
      }

      //Auditoria

    }catch(Exception $e){
      throw new InfraException('Erro reativando .',$e);
    }
  }

/*
  protected function bloquearControlado(MdCorSerieExpDTO $objMdCorSerieExpDTO){
    try {

      //Valida Permissao
      SessaoSEI::getInstance()->validarPermissao('md_cor_serie_exp_consultar');

      //Regras de Negocio
      //$objInfraException = new InfraException();

      //$objInfraException->lancarValidacoes();

      $objMdCorSerieExpBD = new MdCorSerieExpBD($this->getObjInfraIBanco());
      $ret = $objMdCorSerieExpBD->bloquear($objMdCorSerieExpDTO);

      //Auditoria

      return $ret;
    }catch(Exception $e){
      throw new InfraException('Erro bloqueando .',$e);
    }
  }
*/

  protected function excluirRelacionamentosControlado( RelDispositivoNormativoTipoControleDTO $relDispositivoNormativoTipoControleDTO ) {
  }
}
?>