<?
/**
* TRIBUNAL REGIONAL FEDERAL DA 4ª REGIÃO
*
* 26/11/2018 - Criado por felipelino.cast <felipe.lino@castgroup.com.br>
*
* Versão do Gerador de Código: 1.41.0
*/

require_once dirname(__FILE__) . '/../../../SEI.php';

class MdCorStatusProcessRN extends InfraRN {

  public function __construct(){
    parent::__construct();
  }

  protected function inicializarObjInfraIBanco(){
    return BancoSEI::getInstance();
  }

  private function validarNumIdMdCorStatusProcess(MdCorStatusProcessDTO $objMdCorStatusProcessDTO, InfraException $objInfraException){
    if (InfraString::isBolVazia($objMdCorStatusProcessDTO->getNumIdMdCorStatusProcess())){
      $objInfraException->adicionarValidacao(' não informad.');
    }
  }

  private function validarStrDescricao(MdCorStatusProcessDTO $objMdCorStatusProcessDTO, InfraException $objInfraException){
    if (InfraString::isBolVazia($objMdCorStatusProcessDTO->getStrDescricao())){
      $objInfraException->adicionarValidacao(' não informad.');
    }else{
      $objMdCorStatusProcessDTO->setStrDescricao(trim($objMdCorStatusProcessDTO->getStrDescricao()));

      if (strlen($objMdCorStatusProcessDTO->getStrDescricao())>100){
        $objInfraException->adicionarValidacao(' possui tamanho superior a 100 caracteres.');
      }
    }
  }

  protected function cadastrarControlado(MdCorStatusProcessDTO $objMdCorStatusProcessDTO) {
    try{

      //Valida Permissao
//      SessaoSEI::getInstance()->validarPermissao('md_cor_status_process_cadastrar');

      //Regras de Negocio
      $objInfraException = new InfraException();

      $this->validarNumIdMdCorStatusProcess($objMdCorStatusProcessDTO, $objInfraException);
      $this->validarStrDescricao($objMdCorStatusProcessDTO, $objInfraException);

      $objInfraException->lancarValidacoes();

      $objMdCorStatusProcessBD = new MdCorStatusProcessBD($this->getObjInfraIBanco());
      $ret = $objMdCorStatusProcessBD->cadastrar($objMdCorStatusProcessDTO);

      //Auditoria

      return $ret;

    }catch(Exception $e){
      throw new InfraException('Erro cadastrando .',$e);
    }
  }

  protected function alterarControlado(MdCorStatusProcessDTO $objMdCorStatusProcessDTO){
    try {

      //Valida Permissao
      SessaoSEI::getInstance()->validarPermissao('md_cor_status_process_alterar');

      //Regras de Negocio
      $objInfraException = new InfraException();

      if ($objMdCorStatusProcessDTO->isSetNumIdMdCorStatusProcess()){
        $this->validarNumIdMdCorStatusProcess($objMdCorStatusProcessDTO, $objInfraException);
      }
      if ($objMdCorStatusProcessDTO->isSetStrDescricao()){
        $this->validarStrDescricao($objMdCorStatusProcessDTO, $objInfraException);
      }

      $objInfraException->lancarValidacoes();

      $objMdCorStatusProcessBD = new MdCorStatusProcessBD($this->getObjInfraIBanco());
      $objMdCorStatusProcessBD->alterar($objMdCorStatusProcessDTO);

      //Auditoria

    }catch(Exception $e){
      throw new InfraException('Erro alterando .',$e);
    }
  }

  protected function excluirControlado($arrObjMdCorStatusProcessDTO){
    try {

      //Valida Permissao
      SessaoSEI::getInstance()->validarPermissao('md_cor_status_process_excluir');

      //Regras de Negocio
      //$objInfraException = new InfraException();

      //$objInfraException->lancarValidacoes();

      $objMdCorStatusProcessBD = new MdCorStatusProcessBD($this->getObjInfraIBanco());
      for($i=0;$i<count($arrObjMdCorStatusProcessDTO);$i++){
        $objMdCorStatusProcessBD->excluir($arrObjMdCorStatusProcessDTO[$i]);
      }

      //Auditoria

    }catch(Exception $e){
      throw new InfraException('Erro excluindo .',$e);
    }
  }

  protected function consultarConectado(MdCorStatusProcessDTO $objMdCorStatusProcessDTO){
    try {

      //Valida Permissao
      SessaoSEI::getInstance()->validarPermissao('md_cor_status_process_consultar');

      //Regras de Negocio
      //$objInfraException = new InfraException();

      //$objInfraException->lancarValidacoes();

      $objMdCorStatusProcessBD = new MdCorStatusProcessBD($this->getObjInfraIBanco());
      $ret = $objMdCorStatusProcessBD->consultar($objMdCorStatusProcessDTO);

      //Auditoria

      return $ret;
    }catch(Exception $e){
      throw new InfraException('Erro consultando .',$e);
    }
  }

  protected function listarConectado(MdCorStatusProcessDTO $objMdCorStatusProcessDTO) {
    try {

      //Valida Permissao
//      SessaoSEI::getInstance()->validarPermissao('md_cor_status_process_listar');

      //Regras de Negocio
      //$objInfraException = new InfraException();

      //$objInfraException->lancarValidacoes();

      $objMdCorStatusProcessBD = new MdCorStatusProcessBD($this->getObjInfraIBanco());
      $ret = $objMdCorStatusProcessBD->listar($objMdCorStatusProcessDTO);

      //Auditoria

      return $ret;

    }catch(Exception $e){
      throw new InfraException('Erro listando .',$e);
    }
  }

  protected function contarConectado(MdCorStatusProcessDTO $objMdCorStatusProcessDTO){
    try {

      //Valida Permissao
      SessaoSEI::getInstance()->validarPermissao('md_cor_status_process_listar');

      //Regras de Negocio
      //$objInfraException = new InfraException();

      //$objInfraException->lancarValidacoes();

      $objMdCorStatusProcessBD = new MdCorStatusProcessBD($this->getObjInfraIBanco());
      $ret = $objMdCorStatusProcessBD->contar($objMdCorStatusProcessDTO);

      //Auditoria

      return $ret;
    }catch(Exception $e){
      throw new InfraException('Erro contando .',$e);
    }
  }

}
