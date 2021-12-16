<?
/**
* TRIBUNAL REGIONAL FEDERAL DA 4ª REGIÃO
*
* 26/11/2018 - Criado por felipelino.cast <felipe.lino@castgroup.com.br>
*
* Versão do Gerador de Código: 1.41.0
*/

require_once dirname(__FILE__) . '/../../../SEI.php';

class MdCorSubStatusProcessRN extends InfraRN {

  public function __construct(){
    parent::__construct();
  }

  protected function inicializarObjInfraIBanco(){
    return BancoSEI::getInstance();
  }

  private function validarNumIdMdCorSubStatusProcess(MdCorSubStatusProcessDTO $objMdCorSubStatusProcessDTO, InfraException $objInfraException){
    if (InfraString::isBolVazia($objMdCorSubStatusProcessDTO->getNumIdMdCorSubStatusProcess())){
      $objInfraException->adicionarValidacao(' não informad.');
    }
  }

  private function validarNumIdMdCorStatusProcess(MdCorSubStatusProcessDTO $objMdCorSubStatusProcessDTO, InfraException $objInfraException){
    if (InfraString::isBolVazia($objMdCorSubStatusProcessDTO->getNumIdMdCorStatusProcess())){
      $objInfraException->adicionarValidacao(' não informad.');
    }
  }

  private function validarStrDescricao(MdCorSubStatusProcessDTO $objMdCorSubStatusProcessDTO, InfraException $objInfraException){
    if (InfraString::isBolVazia($objMdCorSubStatusProcessDTO->getStrDescricao())){
      $objInfraException->adicionarValidacao(' não informad.');
    }else{
      $objMdCorSubStatusProcessDTO->setStrDescricao(trim($objMdCorSubStatusProcessDTO->getStrDescricao()));

      if (strlen($objMdCorSubStatusProcessDTO->getStrDescricao())>100){
        $objInfraException->adicionarValidacao(' possui tamanho superior a 100 caracteres.');
      }
    }
  }

  protected function cadastrarControlado(MdCorSubStatusProcessDTO $objMdCorSubStatusProcessDTO) {
    try{

      //Valida Permissao
//      SessaoSEI::getInstance()->validarPermissao('md_cor_status_process_cadastrar');

      //Regras de Negocio
      $objInfraException = new InfraException();

      $this->validarNumIdMdCorSubStatusProcess($objMdCorSubStatusProcessDTO, $objInfraException);
      $this->validarNumIdMdCorStatusProcess($objMdCorSubStatusProcessDTO, $objInfraException);
      $this->validarStrDescricao($objMdCorSubStatusProcessDTO, $objInfraException);

      $objInfraException->lancarValidacoes();

      $objMdCorSubStatusProcessBD = new MdCorSubStatusProcessBD($this->getObjInfraIBanco());
      $ret = $objMdCorSubStatusProcessBD->cadastrar($objMdCorSubStatusProcessDTO);

      //Auditoria

      return $ret;

    }catch(Exception $e){
      throw new InfraException('Erro cadastrando .',$e);
    }
  }

  protected function alterarControlado(MdCorSubStatusProcessDTO $objMdCorSubStatusProcessDTO){
    try {

      //Valida Permissao
      SessaoSEI::getInstance()->validarPermissao('md_cor_status_process_alterar');

      //Regras de Negocio
      $objInfraException = new InfraException();

      if ($objMdCorSubStatusProcessDTO->isSetNumIdMdCorSubStatusProcess()){
        $this->validarNumIdMdCorSubStatusProcess($objMdCorSubStatusProcessDTO, $objInfraException);
      }
      if ($objMdCorSubStatusProcessDTO->isSetNumIdMdCorStatusProcess()){
        $this->validarNumIdMdCorStatusProcess($objMdCorSubStatusProcessDTO, $objInfraException);
      }
      if ($objMdCorSubStatusProcessDTO->isSetStrDescricao()){
        $this->validarStrDescricao($objMdCorSubStatusProcessDTO, $objInfraException);
      }

      $objInfraException->lancarValidacoes();

      $objMdCorSubStatusProcessBD = new MdCorSubStatusProcessBD($this->getObjInfraIBanco());
      $objMdCorSubStatusProcessBD->alterar($objMdCorSubStatusProcessDTO);

      //Auditoria

    }catch(Exception $e){
      throw new InfraException('Erro alterando .',$e);
    }
  }

  protected function excluirControlado($arrObjMdCorSubStatusProcessDTO){
    try {

      //Valida Permissao
      SessaoSEI::getInstance()->validarPermissao('md_cor_status_process_excluir');

      //Regras de Negocio
      //$objInfraException = new InfraException();

      //$objInfraException->lancarValidacoes();

      $objMdCorSubStatusProcessBD = new MdCorSubStatusProcessBD($this->getObjInfraIBanco());
      for($i=0;$i<count($arrObjMdCorSubStatusProcessDTO);$i++){
        $objMdCorSubStatusProcessBD->excluir($arrObjMdCorSubStatusProcessDTO[$i]);
      }

      //Auditoria

    }catch(Exception $e){
      throw new InfraException('Erro excluindo .',$e);
    }
  }

  protected function consultarConectado(MdCorSubStatusProcessDTO $objMdCorSubStatusProcessDTO){
    try {

      //Valida Permissao
      SessaoSEI::getInstance()->validarPermissao('md_cor_status_process_consultar');

      //Regras de Negocio
      //$objInfraException = new InfraException();

      //$objInfraException->lancarValidacoes();

      $objMdCorSubStatusProcessBD = new MdCorSubStatusProcessBD($this->getObjInfraIBanco());
      $ret = $objMdCorSubStatusProcessBD->consultar($objMdCorSubStatusProcessDTO);

      //Auditoria

      return $ret;
    }catch(Exception $e){
      throw new InfraException('Erro consultando .',$e);
    }
  }

  protected function listarConectado(MdCorSubStatusProcessDTO $objMdCorSubStatusProcessDTO) {
    try {

      //Valida Permissao
//      SessaoSEI::getInstance()->validarPermissao('md_cor_status_process_listar');

      //Regras de Negocio
      //$objInfraException = new InfraException();

      //$objInfraException->lancarValidacoes();

      $objMdCorSubStatusProcessBD = new MdCorSubStatusProcessBD($this->getObjInfraIBanco());
      $ret = $objMdCorSubStatusProcessBD->listar($objMdCorSubStatusProcessDTO);

      //Auditoria

      return $ret;

    }catch(Exception $e){
      throw new InfraException('Erro listando .',$e);
    }
  }

  protected function contarConectado(MdCorSubStatusProcessDTO $objMdCorSubStatusProcessDTO){
    try {

      //Valida Permissao
      SessaoSEI::getInstance()->validarPermissao('md_cor_status_process_listar');

      //Regras de Negocio
      //$objInfraException = new InfraException();

      //$objInfraException->lancarValidacoes();

      $objMdCorSubStatusProcessBD = new MdCorSubStatusProcessBD($this->getObjInfraIBanco());
      $ret = $objMdCorSubStatusProcessBD->contar($objMdCorSubStatusProcessDTO);

      //Auditoria

      return $ret;
    }catch(Exception $e){
      throw new InfraException('Erro contando .',$e);
    }
  }

}
