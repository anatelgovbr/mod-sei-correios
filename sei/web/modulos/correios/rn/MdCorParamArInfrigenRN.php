<?
/**
* TRIBUNAL REGIONAL FEDERAL DA 4ª REGIÃO
*
* 03/07/2018 - criado por augusto.cast
*
* Versão do Gerador de Código: 1.41.0
*/

require_once dirname(__FILE__) . '/../../../SEI.php';

class MdCorParamArInfrigenRN extends InfraRN {

  public function __construct(){
    parent::__construct();
  }

  protected function inicializarObjInfraIBanco(){
    return BancoSEI::getInstance();
  }

  private function validarNumIdMdCorParamArInfrigencia(MdCorParamArInfrigenDTO $objMdCorParamArInfrigenDTO, InfraException $objInfraException){
    if (InfraString::isBolVazia($objMdCorParamArInfrigenDTO->getNumIdMdCorParamArInfrigencia())){
      $objInfraException->adicionarValidacao(' não informad.');
    }
  }

  private function validarNumIdMdCorParametroAr(MdCorParamArInfrigenDTO $objMdCorParamArInfrigenDTO, InfraException $objInfraException){
    if (InfraString::isBolVazia($objMdCorParamArInfrigenDTO->getNumIdMdCorParametroAr())){
      $objMdCorParamArInfrigenDTO->setNumIdMdCorParametroAr(null);
    }
  }

  private function validarStrMotivoInfrigencia(MdCorParamArInfrigenDTO $objMdCorParamArInfrigenDTO, InfraException $objInfraException){
    if (InfraString::isBolVazia($objMdCorParamArInfrigenDTO->getStrMotivoInfrigencia())){
      $objInfraException->adicionarValidacao(' não informad.');
    }else{
      $objMdCorParamArInfrigenDTO->setStrMotivoInfrigencia(trim($objMdCorParamArInfrigenDTO->getStrMotivoInfrigencia()));

      if (strlen($objMdCorParamArInfrigenDTO->getStrMotivoInfrigencia())>50){
        $objInfraException->adicionarValidacao(' possui tamanho superior a 50 caracteres.');
      }
    }
  }

  private function validarStrSinAtivo(MdCorParamArInfrigenDTO $objMdCorParamArInfrigenDTO, InfraException $objInfraException){
    if (InfraString::isBolVazia($objMdCorParamArInfrigenDTO->getStrSinAtivo())){
      $objInfraException->adicionarValidacao('Sinalizador de Exclusão Lógica não informado.');
    }else{
      if (!InfraUtil::isBolSinalizadorValido($objMdCorParamArInfrigenDTO->getStrSinAtivo())){
        $objInfraException->adicionarValidacao('Sinalizador de Exclusão Lógica inválido.');
      }
    }
  }

  protected function cadastrarControlado(MdCorParamArInfrigenDTO $objMdCorParamArInfrigenDTO) {
    try{

      //Valida Permissao
//      SessaoSEI::getInstance()->validarPermissao('md_cor_param_ar_infrigen_cadastrar');

      //Regras de Negocio
      $objInfraException = new InfraException();

//      $this->validarNumIdMdCorParamArInfrigencia($objMdCorParamArInfrigenDTO, $objInfraException);
      $this->validarNumIdMdCorParametroAr($objMdCorParamArInfrigenDTO, $objInfraException);
      $this->validarStrMotivoInfrigencia($objMdCorParamArInfrigenDTO, $objInfraException);
      $this->validarStrSinAtivo($objMdCorParamArInfrigenDTO, $objInfraException);

      $objInfraException->lancarValidacoes();

      $objMdCorParamArInfrigenBD = new MdCorParamArInfrigenBD($this->getObjInfraIBanco());
      $ret = $objMdCorParamArInfrigenBD->cadastrar($objMdCorParamArInfrigenDTO);

      //Auditoria

      return $ret;

    }catch(Exception $e){
      throw new InfraException('Erro cadastrando .',$e);
    }
  }

  protected function alterarControlado(MdCorParamArInfrigenDTO $objMdCorParamArInfrigenDTO){
    try {

      //Valida Permissao
//      SessaoSEI::getInstance()->validarPermissao('md_cor_param_ar_infrigen_alterar');

      //Regras de Negocio
      $objInfraException = new InfraException();

//      if ($objMdCorParamArInfrigenDTO->isSetNumIdMdCorParamArInfrigencia()){
//        $this->validarNumIdMdCorParamArInfrigencia($objMdCorParamArInfrigenDTO, $objInfraException);
//      }
      if ($objMdCorParamArInfrigenDTO->isSetNumIdMdCorParametroAr()){
        $this->validarNumIdMdCorParametroAr($objMdCorParamArInfrigenDTO, $objInfraException);
      }
      if ($objMdCorParamArInfrigenDTO->isSetStrMotivoInfrigencia()){
        $this->validarStrMotivoInfrigencia($objMdCorParamArInfrigenDTO, $objInfraException);
      }
      if ($objMdCorParamArInfrigenDTO->isSetStrSinAtivo()){
        $this->validarStrSinAtivo($objMdCorParamArInfrigenDTO, $objInfraException);
      }

      $objInfraException->lancarValidacoes();

      $objMdCorParamArInfrigenBD = new MdCorParamArInfrigenBD($this->getObjInfraIBanco());
      $objMdCorParamArInfrigenBD->alterar($objMdCorParamArInfrigenDTO);

      //Auditoria

    }catch(Exception $e){
      throw new InfraException('Erro alterando .',$e);
    }
  }

  protected function excluirControlado($arrObjMdCorParamArInfrigenDTO){
    try {

      //Valida Permissao
      SessaoSEI::getInstance()->validarPermissao('md_cor_param_ar_infrigen_excluir');

      //Regras de Negocio
      //$objInfraException = new InfraException();

      //$objInfraException->lancarValidacoes();

      $objMdCorParamArInfrigenBD = new MdCorParamArInfrigenBD($this->getObjInfraIBanco());
      for($i=0;$i<count($arrObjMdCorParamArInfrigenDTO);$i++){
        $objMdCorParamArInfrigenBD->excluir($arrObjMdCorParamArInfrigenDTO[$i]);
      }

      //Auditoria

    }catch(Exception $e){
      throw new InfraException('Erro excluindo .',$e);
    }
  }

  protected function consultarConectado(MdCorParamArInfrigenDTO $objMdCorParamArInfrigenDTO){
    try {

      //Valida Permissao
//      SessaoSEI::getInstance()->validarPermissao('md_cor_param_ar_infrigen_consultar');

      //Regras de Negocio
      //$objInfraException = new InfraException();

      //$objInfraException->lancarValidacoes();

      $objMdCorParamArInfrigenBD = new MdCorParamArInfrigenBD($this->getObjInfraIBanco());
      $ret = $objMdCorParamArInfrigenBD->consultar($objMdCorParamArInfrigenDTO);

      //Auditoria

      return $ret;
    }catch(Exception $e){
      throw new InfraException('Erro consultando .',$e);
    }
  }

  protected function listarConectado(MdCorParamArInfrigenDTO $objMdCorParamArInfrigenDTO) {
    try {

      //Valida Permissao
      SessaoSEI::getInstance()->validarPermissao('md_cor_parametro_ar_cadastrar');

      //Regras de Negocio
      //$objInfraException = new InfraException();

      //$objInfraException->lancarValidacoes();

      $objMdCorParamArInfrigenBD = new MdCorParamArInfrigenBD($this->getObjInfraIBanco());
      $ret = $objMdCorParamArInfrigenBD->listar($objMdCorParamArInfrigenDTO);

      //Auditoria

      return $ret;

    }catch(Exception $e){
      throw new InfraException('Erro listando .',$e);
    }
  }

  protected function contarConectado(MdCorParamArInfrigenDTO $objMdCorParamArInfrigenDTO){
    try {

      //Valida Permissao
      SessaoSEI::getInstance()->validarPermissao('md_cor_param_ar_infrigen_listar');

      //Regras de Negocio
      //$objInfraException = new InfraException();

      //$objInfraException->lancarValidacoes();

      $objMdCorParamArInfrigenBD = new MdCorParamArInfrigenBD($this->getObjInfraIBanco());
      $ret = $objMdCorParamArInfrigenBD->contar($objMdCorParamArInfrigenDTO);

      //Auditoria

      return $ret;
    }catch(Exception $e){
      throw new InfraException('Erro contando .',$e);
    }
  }

  protected function desativarControlado($arrObjMdCorParamArInfrigenDTO){
    try {

      //Valida Permissao
      SessaoSEI::getInstance()->validarPermissao('md_cor_param_ar_infrigen_desativar');

      //Regras de Negocio
      //$objInfraException = new InfraException();

      //$objInfraException->lancarValidacoes();

      $objMdCorParamArInfrigenBD = new MdCorParamArInfrigenBD($this->getObjInfraIBanco());
      for($i=0;$i<count($arrObjMdCorParamArInfrigenDTO);$i++){
        $objMdCorParamArInfrigenBD->desativar($arrObjMdCorParamArInfrigenDTO[$i]);
      }

      //Auditoria

    }catch(Exception $e){
      throw new InfraException('Erro desativando .',$e);
    }
  }

  protected function reativarControlado($arrObjMdCorParamArInfrigenDTO){
    try {

      //Valida Permissao
      SessaoSEI::getInstance()->validarPermissao('md_cor_param_ar_infrigen_reativar');

      //Regras de Negocio
      //$objInfraException = new InfraException();

      //$objInfraException->lancarValidacoes();

      $objMdCorParamArInfrigenBD = new MdCorParamArInfrigenBD($this->getObjInfraIBanco());
      for($i=0;$i<count($arrObjMdCorParamArInfrigenDTO);$i++){
        $objMdCorParamArInfrigenBD->reativar($arrObjMdCorParamArInfrigenDTO[$i]);
      }

      //Auditoria

    }catch(Exception $e){
      throw new InfraException('Erro reativando .',$e);
    }
  }

  protected function bloquearControlado(MdCorParamArInfrigenDTO $objMdCorParamArInfrigenDTO){
    try {

      //Valida Permissao
      SessaoSEI::getInstance()->validarPermissao('md_cor_param_ar_infrigen_consultar');

      //Regras de Negocio
      //$objInfraException = new InfraException();

      //$objInfraException->lancarValidacoes();

      $objMdCorParamArInfrigenBD = new MdCorParamArInfrigenBD($this->getObjInfraIBanco());
      $ret = $objMdCorParamArInfrigenBD->bloquear($objMdCorParamArInfrigenDTO);

      //Auditoria

      return $ret;
    }catch(Exception $e){
      throw new InfraException('Erro bloqueando .',$e);
    }
  }


}
