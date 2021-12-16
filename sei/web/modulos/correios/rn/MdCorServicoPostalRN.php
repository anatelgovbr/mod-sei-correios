<?
/**
* TRIBUNAL REGIONAL FEDERAL DA 4ª REGIÃO
*
* 23/12/2016 - criado por Wilton Júnior
*
* Versão do Gerador de Código: 1.39.0
*/

require_once dirname(__FILE__).'/../../../SEI.php';

class MdCorServicoPostalRN extends InfraRN {

  public function __construct(){
    parent::__construct();
  }

  protected function inicializarObjInfraIBanco(){
    return BancoSEI::getInstance();
  }

  private function validarNumIdMdCorContrato(MdCorServicoPostalDTO $objMdCorServicoPostalDTO, InfraException $objInfraException){
    if (InfraString::isBolVazia($objMdCorServicoPostalDTO->getNumIdMdCorContrato())){
      $objInfraException->adicionarValidacao(' não informado.');
    }
  }

  private function validarStrNome(MdCorServicoPostalDTO $objMdCorServicoPostalDTO, InfraException $objInfraException){
    if (InfraString::isBolVazia($objMdCorServicoPostalDTO->getStrNome())){
      $objInfraException->adicionarValidacao('Nome não informado.');
    }else{
      $objMdCorServicoPostalDTO->setStrNome(trim($objMdCorServicoPostalDTO->getStrNome()));

      if (strlen($objMdCorServicoPostalDTO->getStrNome())>500){
        $objInfraException->adicionarValidacao('Nome possui tamanho superior a 500 caracteres.');
      }
    }
  }

  private function validarStrExpedicaoAvisoRecebimento(MdCorServicoPostalDTO $objMdCorServicoPostalDTO, InfraException $objInfraException){
    if (InfraString::isBolVazia($objMdCorServicoPostalDTO->getStrExpedicaoAvisoRecebimento())){
      $objInfraException->adicionarValidacao('Aviso Recebimento não informado.');
    }else{
      $objMdCorServicoPostalDTO->setStrExpedicaoAvisoRecebimento(trim($objMdCorServicoPostalDTO->getStrExpedicaoAvisoRecebimento()));

      if (strlen($objMdCorServicoPostalDTO->getStrExpedicaoAvisoRecebimento())>1){
        $objInfraException->adicionarValidacao('Aviso Recebimento possui tamanho superior a 1 caracteres.');
      }
    }
  }

  private function validarStrDescricao(MdCorServicoPostalDTO $objMdCorServicoPostalDTO, InfraException $objInfraException){
    if (InfraString::isBolVazia($objMdCorServicoPostalDTO->getStrDescricao())){
      $objInfraException->adicionarValidacao('Descrição não informada.');
    }else{
      $objMdCorServicoPostalDTO->setStrDescricao(trim($objMdCorServicoPostalDTO->getStrDescricao()));

      if (strlen($objMdCorServicoPostalDTO->getStrDescricao())>500){
        $objInfraException->adicionarValidacao('Descrição possui tamanho superior a 500 caracteres.');
      }
    }
  }

  private function validarStrSinAtivo(MdCorServicoPostalDTO $objMdCorServicoPostalDTO, InfraException $objInfraException){
    if (InfraString::isBolVazia($objMdCorServicoPostalDTO->getStrSinAtivo())){
      $objInfraException->adicionarValidacao('Sinalizador de Exclusão Lógica não informado.');
    }else{
      if (!InfraUtil::isBolSinalizadorValido($objMdCorServicoPostalDTO->getStrSinAtivo())){
        $objInfraException->adicionarValidacao('Sinalizador de Exclusão Lógica inválido.');
      }
    }
  }

  protected function cadastrarControlado(MdCorServicoPostalDTO $objMdCorServicoPostalDTO) {
    try{

      //Valida Permissao
      SessaoSEI::getInstance()->validarPermissao('md_cor_servico_postal_cadastrar');

      //Regras de Negocio
      $objInfraException = new InfraException();

      $this->validarNumIdMdCorContrato($objMdCorServicoPostalDTO, $objInfraException);
      $this->validarStrNome($objMdCorServicoPostalDTO, $objInfraException);
      $this->validarStrExpedicaoAvisoRecebimento($objMdCorServicoPostalDTO, $objInfraException);
      $this->validarStrDescricao($objMdCorServicoPostalDTO, $objInfraException);
      $this->validarStrSinAtivo($objMdCorServicoPostalDTO, $objInfraException);

      $objInfraException->lancarValidacoes();

      $objMdCorServicoPostalBD = new MdCorServicoPostalBD($this->getObjInfraIBanco());
      $ret = $objMdCorServicoPostalBD->cadastrar($objMdCorServicoPostalDTO);

      //Auditoria

      return $ret;

    }catch(Exception $e){
      throw new InfraException('Erro cadastrando o.',$e);
    }
  }

  protected function alterarControlado(MdCorServicoPostalDTO $objMdCorServicoPostalDTO){
    try {

      //Valida Permissao
  	   SessaoSEI::getInstance()->validarPermissao('md_cor_servico_postal_alterar');

      //Regras de Negocio
      $objInfraException = new InfraException();

      if ($objMdCorServicoPostalDTO->isSetNumIdMdCorContrato()){
        $this->validarNumIdMdCorContrato($objMdCorServicoPostalDTO, $objInfraException);
      }
      if ($objMdCorServicoPostalDTO->isSetStrNome()){
        $this->validarStrNome($objMdCorServicoPostalDTO, $objInfraException);
      }
      if ($objMdCorServicoPostalDTO->isSetStrExpedicaoAvisoRecebimento()){
        $this->validarStrExpedicaoAvisoRecebimento($objMdCorServicoPostalDTO, $objInfraException);
      }
      if ($objMdCorServicoPostalDTO->isSetStrDescricao()){
        $this->validarStrDescricao($objMdCorServicoPostalDTO, $objInfraException);
      }
      if ($objMdCorServicoPostalDTO->isSetStrSinAtivo()){
        $this->validarStrSinAtivo($objMdCorServicoPostalDTO, $objInfraException);
      }

      $objInfraException->lancarValidacoes();

      $objMdCorServicoPostalBD = new MdCorServicoPostalBD($this->getObjInfraIBanco());
      $objMdCorServicoPostalBD->alterar($objMdCorServicoPostalDTO);

      //Auditoria

    }catch(Exception $e){
      throw new InfraException('Erro alterando o.',$e);
    }
  }

  protected function excluirControlado($arrObjMdCorServicoPostalDTO){
    try {

      //Valida Permissao
      SessaoSEI::getInstance()->validarPermissao('md_cor_servico_postal_excluir');

      //Regras de Negocio
      //$objInfraException = new InfraException();

      //$objInfraException->lancarValidacoes();
      
      $objMdCorServicoPostalBD = new MdCorServicoPostalBD($this->getObjInfraIBanco());
      for($i=0;$i<count($arrObjMdCorServicoPostalDTO);$i++){
        $objMdCorServicoPostalBD->excluir($arrObjMdCorServicoPostalDTO[$i]);
      }

      //Auditoria

    }catch(Exception $e){
      throw new InfraException('Erro excluindo o.',$e);
    }
  }

  protected function consultarConectado(MdCorServicoPostalDTO $objMdCorServicoPostalDTO){
    try {

      //Valida Permissao
      SessaoSEI::getInstance()->validarPermissao('md_cor_servico_postal_consultar');

      //Regras de Negocio
      //$objInfraException = new InfraException();

      //$objInfraException->lancarValidacoes();

      $objMdCorServicoPostalBD = new MdCorServicoPostalBD($this->getObjInfraIBanco());
      $ret = $objMdCorServicoPostalBD->consultar($objMdCorServicoPostalDTO);

      //Auditoria

      return $ret;
    }catch(Exception $e){
      throw new InfraException('Erro consultando o.',$e);
    }
  }

  protected function listarConectado(MdCorServicoPostalDTO $objMdCorServicoPostalDTO) {
    try {

      //Valida Permissao
      SessaoSEI::getInstance()->validarPermissao('md_cor_servico_postal_listar');

      //Regras de Negocio
      //$objInfraException = new InfraException();

      //$objInfraException->lancarValidacoes();

      $objMdCorServicoPostalBD = new MdCorServicoPostalBD($this->getObjInfraIBanco());
      $ret = $objMdCorServicoPostalBD->listar($objMdCorServicoPostalDTO);

      //Auditoria

      return $ret;

    }catch(Exception $e){
      throw new InfraException('Erro listando os.',$e);
    }
  }

  protected function contarConectado(MdCorServicoPostalDTO $objMdCorServicoPostalDTO){
    try {

      //Valida Permissao
      SessaoSEI::getInstance()->validarPermissao('md_cor_servico_postal_listar');

      //Regras de Negocio
      //$objInfraException = new InfraException();

      //$objInfraException->lancarValidacoes();

      $objMdCorServicoPostalBD = new MdCorServicoPostalBD($this->getObjInfraIBanco());
      $ret = $objMdCorServicoPostalBD->contar($objMdCorServicoPostalDTO);

      //Auditoria

      return $ret;
    }catch(Exception $e){
      throw new InfraException('Erro contando os.',$e);
    }
  }

  protected function desativarControlado($arrObjMdCorServicoPostalDTO){
    try {

      //Valida Permissao
      SessaoSEI::getInstance()->validarPermissao('md_cor_servico_postal_desativar');

      //Regras de Negocio
      //$objInfraException = new InfraException();

      //$objInfraException->lancarValidacoes();

      $objMdCorServicoPostalBD = new MdCorServicoPostalBD($this->getObjInfraIBanco());
      for($i=0;$i<count($arrObjMdCorServicoPostalDTO);$i++){
        $objMdCorServicoPostalBD->desativar($arrObjMdCorServicoPostalDTO[$i]);
      }

      //Auditoria

    }catch(Exception $e){
      throw new InfraException('Erro desativando o.',$e);
    }
  }

  protected function reativarControlado($arrObjMdCorServicoPostalDTO){
    try {

      //Valida Permissao
      SessaoSEI::getInstance()->validarPermissao('md_cor_servico_postal_reativar');

      //Regras de Negocio
      //$objInfraException = new InfraException();

      //$objInfraException->lancarValidacoes();

      $objMdCorServicoPostalBD = new MdCorServicoPostalBD($this->getObjInfraIBanco());
      for($i=0;$i<count($arrObjMdCorServicoPostalDTO);$i++){
        $objMdCorServicoPostalBD->reativar($arrObjMdCorServicoPostalDTO[$i]);
      }

      //Auditoria

    }catch(Exception $e){
      throw new InfraException('Erro reativando o.',$e);
    }
  }

  protected function bloquearControlado(MdCorServicoPostalDTO $objMdCorServicoPostalDTO){
    try {

      //Valida Permissao
      SessaoSEI::getInstance()->validarPermissao('md_cor_servico_postal_consultar');

      //Regras de Negocio
      //$objInfraException = new InfraException();

      //$objInfraException->lancarValidacoes();

      $objMdCorServicoPostalBD = new MdCorServicoPostalBD($this->getObjInfraIBanco());
      $ret = $objMdCorServicoPostalBD->bloquear($objMdCorServicoPostalDTO);

      //Auditoria

      return $ret;
    }catch(Exception $e){
      throw new InfraException('Erro bloqueando o.',$e);
    }
  }


}
?>