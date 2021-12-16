<?
/**
* TRIBUNAL REGIONAL FEDERAL DA 4ª REGIÃO
*
* 12/09/2018 - criado por augusto.cast
*
* Versão do Gerador de Código: 1.41.0
*/

  require_once dirname(__FILE__) . '/../../../SEI.php';

class MdCorParametroRastreioRN extends InfraRN {

  public function __construct(){
    parent::__construct();
  }

  protected function inicializarObjInfraIBanco(){
    return BancoSEI::getInstance();
  }

  private function validarNumIdMdCorParametroRastreio(MdCorParametroRastreioDTO $objMdCorParametroRastreioDTO, InfraException $objInfraException){
    if (InfraString::isBolVazia($objMdCorParametroRastreioDTO->getNumIdMdCorParametroRastreio())){
      $objMdCorParametroRastreioDTO->setNumIdMdCorParametroRastreio(null);
    }
  }

  private function validarStrUsuario(MdCorParametroRastreioDTO $objMdCorParametroRastreioDTO, InfraException $objInfraException){
    if (InfraString::isBolVazia($objMdCorParametroRastreioDTO->getStrUsuario())){
      $objMdCorParametroRastreioDTO->setStrUsuario(null);
    }else{
      $objMdCorParametroRastreioDTO->setStrUsuario(trim($objMdCorParametroRastreioDTO->getStrUsuario()));

      if (strlen($objMdCorParametroRastreioDTO->getStrUsuario())>100){
        $objInfraException->adicionarValidacao(' possui tamanho superior a 100 caracteres.');
      }
    }
  }

  private function validarStrSenha(MdCorParametroRastreioDTO $objMdCorParametroRastreioDTO, InfraException $objInfraException){
    if (InfraString::isBolVazia($objMdCorParametroRastreioDTO->getStrSenha())){
      $objMdCorParametroRastreioDTO->setStrSenha(null);
    }else{
      $objMdCorParametroRastreioDTO->setStrSenha(trim($objMdCorParametroRastreioDTO->getStrSenha()));

      if (strlen($objMdCorParametroRastreioDTO->getStrSenha())>100){
        $objInfraException->adicionarValidacao(' possui tamanho superior a 100 caracteres.');
      }
    }
  }

  private function validarStrEnderecoWsdl(MdCorParametroRastreioDTO $objMdCorParametroRastreioDTO, InfraException $objInfraException){
    if (InfraString::isBolVazia($objMdCorParametroRastreioDTO->getStrEnderecoWsdl())){
      $objMdCorParametroRastreioDTO->setStrEnderecoWsdl(null);
    }else{
      $objMdCorParametroRastreioDTO->setStrEnderecoWsdl(trim($objMdCorParametroRastreioDTO->getStrEnderecoWsdl()));

      if (strlen($objMdCorParametroRastreioDTO->getStrEnderecoWsdl())>500){
        $objInfraException->adicionarValidacao(' possui tamanho superior a 500 caracteres.');
      }
    }
  }

  protected function cadastrarControlado(MdCorParametroRastreioDTO $objMdCorParametroRastreioDTO) {
    try{

      //Valida Permissao
      SessaoSEI::getInstance()->validarPermissao('md_cor_parametro_rastreio_cadastrar');

      //Regras de Negocio
      $objInfraException = new InfraException();

      $this->validarStrUsuario($objMdCorParametroRastreioDTO, $objInfraException);
      $this->validarStrSenha($objMdCorParametroRastreioDTO, $objInfraException);
      $this->validarStrEnderecoWsdl($objMdCorParametroRastreioDTO, $objInfraException);

      $objInfraException->lancarValidacoes();

      $objMdCorParametroRastreioBD = new MdCorParametroRastreioBD($this->getObjInfraIBanco());
      $ret = $objMdCorParametroRastreioBD->cadastrar($objMdCorParametroRastreioDTO);

      //Auditoria

      return $ret;

    }catch(Exception $e){
      throw new InfraException('Erro cadastrando .',$e);
    }
  }

  protected function alterarControlado(MdCorParametroRastreioDTO $objMdCorParametroRastreioDTO){
    try {

      //Valida Permissao
  	   SessaoSEI::getInstance()->validarPermissao('md_cor_parametro_rastreio_alterar');

      //Regras de Negocio
      $objInfraException = new InfraException();

      if ($objMdCorParametroRastreioDTO->isSetNumIdMdCorParametroRastreio()){
        $this->validarNumIdMdCorParametroRastreio($objMdCorParametroRastreioDTO, $objInfraException);
      }
      if ($objMdCorParametroRastreioDTO->isSetStrUsuario()){
        $this->validarStrUsuario($objMdCorParametroRastreioDTO, $objInfraException);
      }
      if ($objMdCorParametroRastreioDTO->isSetStrSenha()){
        $this->validarStrSenha($objMdCorParametroRastreioDTO, $objInfraException);
      }
      if ($objMdCorParametroRastreioDTO->isSetStrEnderecoWsdl()){
        $this->validarStrEnderecoWsdl($objMdCorParametroRastreioDTO, $objInfraException);
      }

      $objInfraException->lancarValidacoes();

      $objMdCorParametroRastreioBD = new MdCorParametroRastreioBD($this->getObjInfraIBanco());
      $objMdCorParametroRastreioBD->alterar($objMdCorParametroRastreioDTO);

      //Auditoria

    }catch(Exception $e){
      throw new InfraException('Erro alterando .',$e);
    }
  }

  protected function excluirControlado($arrObjMdCorParametroRastreioDTO){
    try {

      //Valida Permissao
      SessaoSEI::getInstance()->validarPermissao('md_cor_parametro_rastreio_excluir');

      //Regras de Negocio
      //$objInfraException = new InfraException();

      //$objInfraException->lancarValidacoes();

      $objMdCorParametroRastreioBD = new MdCorParametroRastreioBD($this->getObjInfraIBanco());
      for($i=0;$i<count($arrObjMdCorParametroRastreioDTO);$i++){
        $objMdCorParametroRastreioBD->excluir($arrObjMdCorParametroRastreioDTO[$i]);
      }

      //Auditoria

    }catch(Exception $e){
      throw new InfraException('Erro excluindo .',$e);
    }
  }

  protected function consultarConectado(MdCorParametroRastreioDTO $objMdCorParametroRastreioDTO){
    try {

      //Valida Permissao
      SessaoSEI::getInstance()->validarPermissao('md_cor_parametro_rastreio_consultar');

      //Regras de Negocio
      //$objInfraException = new InfraException();

      //$objInfraException->lancarValidacoes();

      $objMdCorParametroRastreioBD = new MdCorParametroRastreioBD($this->getObjInfraIBanco());
      $ret = $objMdCorParametroRastreioBD->consultar($objMdCorParametroRastreioDTO);

      //Auditoria

      return $ret;
    }catch(Exception $e){
      throw new InfraException('Erro consultando .',$e);
    }
  }

  protected function listarConectado(MdCorParametroRastreioDTO $objMdCorParametroRastreioDTO) {
    try {

      //Valida Permissao
      SessaoSEI::getInstance()->validarPermissao('md_cor_parametro_rastreio_listar');

      //Regras de Negocio
      //$objInfraException = new InfraException();

      //$objInfraException->lancarValidacoes();

      $objMdCorParametroRastreioBD = new MdCorParametroRastreioBD($this->getObjInfraIBanco());
      $ret = $objMdCorParametroRastreioBD->listar($objMdCorParametroRastreioDTO);

      //Auditoria

      return $ret;

    }catch(Exception $e){
      throw new InfraException('Erro listando .',$e);
    }
  }

  protected function contarConectado(MdCorParametroRastreioDTO $objMdCorParametroRastreioDTO){
    try {

      //Valida Permissao
      SessaoSEI::getInstance()->validarPermissao('md_cor_parametro_rastreio_listar');

      //Regras de Negocio
      //$objInfraException = new InfraException();

      //$objInfraException->lancarValidacoes();

      $objMdCorParametroRastreioBD = new MdCorParametroRastreioBD($this->getObjInfraIBanco());
      $ret = $objMdCorParametroRastreioBD->contar($objMdCorParametroRastreioDTO);

      //Auditoria

      return $ret;
    }catch(Exception $e){
      throw new InfraException('Erro contando .',$e);
    }
  }
/* 
  protected function desativarControlado($arrObjMdCorParametroRastreioDTO){
    try {

      //Valida Permissao
      SessaoSEI::getInstance()->validarPermissao('md_cor_parametro_rastreio_desativar');

      //Regras de Negocio
      //$objInfraException = new InfraException();

      //$objInfraException->lancarValidacoes();

      $objMdCorParametroRastreioBD = new MdCorParametroRastreioBD($this->getObjInfraIBanco());
      for($i=0;$i<count($arrObjMdCorParametroRastreioDTO);$i++){
        $objMdCorParametroRastreioBD->desativar($arrObjMdCorParametroRastreioDTO[$i]);
      }

      //Auditoria

    }catch(Exception $e){
      throw new InfraException('Erro desativando .',$e);
    }
  }

  protected function reativarControlado($arrObjMdCorParametroRastreioDTO){
    try {

      //Valida Permissao
      SessaoSEI::getInstance()->validarPermissao('md_cor_parametro_rastreio_reativar');

      //Regras de Negocio
      //$objInfraException = new InfraException();

      //$objInfraException->lancarValidacoes();

      $objMdCorParametroRastreioBD = new MdCorParametroRastreioBD($this->getObjInfraIBanco());
      for($i=0;$i<count($arrObjMdCorParametroRastreioDTO);$i++){
        $objMdCorParametroRastreioBD->reativar($arrObjMdCorParametroRastreioDTO[$i]);
      }

      //Auditoria

    }catch(Exception $e){
      throw new InfraException('Erro reativando .',$e);
    }
  }

  protected function bloquearControlado(MdCorParametroRastreioDTO $objMdCorParametroRastreioDTO){
    try {

      //Valida Permissao
      SessaoSEI::getInstance()->validarPermissao('md_cor_parametro_rastreio_consultar');

      //Regras de Negocio
      //$objInfraException = new InfraException();

      //$objInfraException->lancarValidacoes();

      $objMdCorParametroRastreioBD = new MdCorParametroRastreioBD($this->getObjInfraIBanco());
      $ret = $objMdCorParametroRastreioBD->bloquear($objMdCorParametroRastreioDTO);

      //Auditoria

      return $ret;
    }catch(Exception $e){
      throw new InfraException('Erro bloqueando .',$e);
    }
  }

 */
}
