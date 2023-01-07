<?
/**
* TRIBUNAL REGIONAL FEDERAL DA 4ª REGIÃO
*
* 27/10/2017 - criado por augusto.cast
*
* Versão do Gerador de Código: 1.41.0
*/

require_once dirname(__FILE__) . '/../../../SEI.php';

class MdCorDiretoriaRN extends InfraRN {

  public function __construct(){
    parent::__construct();
  }

  protected function inicializarObjInfraIBanco(){
    return BancoSEI::getInstance();
  }

  private function validarNumIdMdCorDiretoria(MdCorDiretoriaDTO $objMdCorDiretoriaDTO, InfraException $objInfraException){
    if (InfraString::isBolVazia($objMdCorDiretoriaDTO->getNumIdMdCorDiretoria())){
      $objInfraException->adicionarValidacao(' não informad.');
    }
  }

  private function validarStrCodigoDiretoria(MdCorDiretoriaDTO $objMdCorDiretoriaDTO, InfraException $objInfraException){
    if (InfraString::isBolVazia($objMdCorDiretoriaDTO->getStrCodigoDiretoria())){
      $objInfraException->adicionarValidacao(' não informad.');
    }else{
      $objMdCorDiretoriaDTO->setStrCodigoDiretoria(trim($objMdCorDiretoriaDTO->getStrCodigoDiretoria()));

      if (strlen($objMdCorDiretoriaDTO->getStrCodigoDiretoria())>3){
        $objInfraException->adicionarValidacao(' possui tamanho superior a 3 caracteres.');
      }
    }
  }

  private function validarStrDescricaoDiretoria(MdCorDiretoriaDTO $objMdCorDiretoriaDTO, InfraException $objInfraException){
    if (InfraString::isBolVazia($objMdCorDiretoriaDTO->getStrDescricaoDiretoria())){
      $objInfraException->adicionarValidacao(' não informad.');
    }else{
      $objMdCorDiretoriaDTO->setStrDescricaoDiretoria(trim($objMdCorDiretoriaDTO->getStrDescricaoDiretoria()));

      if (strlen($objMdCorDiretoriaDTO->getStrDescricaoDiretoria())>150){
        $objInfraException->adicionarValidacao(' possui tamanho superior a 150 caracteres.');
      }
    }
  }

  private function validarStrSiglaDiretoria(MdCorDiretoriaDTO $objMdCorDiretoriaDTO, InfraException $objInfraException){
    if (InfraString::isBolVazia($objMdCorDiretoriaDTO->getStrSiglaDiretoria())){
      $objInfraException->adicionarValidacao(' não informad.');
    }else{
      $objMdCorDiretoriaDTO->setStrSiglaDiretoria(trim($objMdCorDiretoriaDTO->getStrSiglaDiretoria()));

      if (strlen($objMdCorDiretoriaDTO->getStrSiglaDiretoria())>5){
        $objInfraException->adicionarValidacao(' possui tamanho superior a 5 caracteres.');
      }
    }
  }

  protected function cadastrarControlado(MdCorDiretoriaDTO $objMdCorDiretoriaDTO) {
    try{

      //Valida Permissao
      SessaoSEI::getInstance()->validarPermissao('md_cor_diretoria_cadastrar');

      //Regras de Negocio
      $objInfraException = new InfraException();

      $this->validarNumIdMdCorDiretoria($objMdCorDiretoriaDTO, $objInfraException);
      $this->validarStrCodigoDiretoria($objMdCorDiretoriaDTO, $objInfraException);
      $this->validarStrDescricaoDiretoria($objMdCorDiretoriaDTO, $objInfraException);
      $this->validarStrSiglaDiretoria($objMdCorDiretoriaDTO, $objInfraException);

      $objInfraException->lancarValidacoes();

      $objMdCorDiretoriaBD = new MdCorDiretoriaBD($this->getObjInfraIBanco());
      $ret = $objMdCorDiretoriaBD->cadastrar($objMdCorDiretoriaDTO);

      //Auditoria

      return $ret;

    }catch(Exception $e){
      throw new InfraException('Erro cadastrando .',$e);
    }
  }

  protected function alterarControlado(MdCorDiretoriaDTO $objMdCorDiretoriaDTO){
    try {

      //Valida Permissao
      SessaoSEI::getInstance()->validarPermissao('md_cor_diretoria_alterar');

      //Regras de Negocio
      $objInfraException = new InfraException();

      if ($objMdCorDiretoriaDTO->isSetNumIdMdCorDiretoria()){
        $this->validarNumIdMdCorDiretoria($objMdCorDiretoriaDTO, $objInfraException);
      }
      if ($objMdCorDiretoriaDTO->isSetStrCodigoDiretoria()){
        $this->validarStrCodigoDiretoria($objMdCorDiretoriaDTO, $objInfraException);
      }
      if ($objMdCorDiretoriaDTO->isSetStrDescricaoDiretoria()){
        $this->validarStrDescricaoDiretoria($objMdCorDiretoriaDTO, $objInfraException);
      }
      if ($objMdCorDiretoriaDTO->isSetStrSiglaDiretoria()){
        $this->validarStrSiglaDiretoria($objMdCorDiretoriaDTO, $objInfraException);
      }

      $objInfraException->lancarValidacoes();

      $objMdCorDiretoriaBD = new MdCorDiretoriaBD($this->getObjInfraIBanco());
      $objMdCorDiretoriaBD->alterar($objMdCorDiretoriaDTO);

      //Auditoria

    }catch(Exception $e){
      throw new InfraException('Erro alterando .',$e);
    }
  }

  protected function excluirControlado($arrObjMdCorDiretoriaDTO){
    try {

      //Valida Permissao
      SessaoSEI::getInstance()->validarPermissao('md_cor_diretoria_excluir');

      //Regras de Negocio
      //$objInfraException = new InfraException();

      //$objInfraException->lancarValidacoes();

      $objMdCorDiretoriaBD = new MdCorDiretoriaBD($this->getObjInfraIBanco());
      for($i=0;$i<count($arrObjMdCorDiretoriaDTO);$i++){
        $objMdCorDiretoriaBD->excluir($arrObjMdCorDiretoriaDTO[$i]);
      }

      //Auditoria

    }catch(Exception $e){
      throw new InfraException('Erro excluindo .',$e);
    }
  }

  protected function consultarConectado(MdCorDiretoriaDTO $objMdCorDiretoriaDTO){
    try {

      //Valida Permissao
      SessaoSEI::getInstance()->validarPermissao('md_cor_diretoria_consultar');

      //Regras de Negocio
      //$objInfraException = new InfraException();

      //$objInfraException->lancarValidacoes();

      $objMdCorDiretoriaBD = new MdCorDiretoriaBD($this->getObjInfraIBanco());
      $ret = $objMdCorDiretoriaBD->consultar($objMdCorDiretoriaDTO);

      //Auditoria

      return $ret;
    }catch(Exception $e){
      throw new InfraException('Erro consultando .',$e);
    }
  }

  protected function listarConectado(MdCorDiretoriaDTO $objMdCorDiretoriaDTO) {
    try {

      //Valida Permissao
      SessaoSEI::getInstance()->validarPermissao('md_cor_diretoria_listar');

      //Regras de Negocio
      //$objInfraException = new InfraException();

      //$objInfraException->lancarValidacoes();

      $objMdCorDiretoriaBD = new MdCorDiretoriaBD($this->getObjInfraIBanco());
      $ret = $objMdCorDiretoriaBD->listar($objMdCorDiretoriaDTO);

      //Auditoria

      return $ret;

    }catch(Exception $e){
      throw new InfraException('Erro listando .',$e);
    }
  }

  protected function contarConectado(MdCorDiretoriaDTO $objMdCorDiretoriaDTO){
    try {

      //Valida Permissao
      SessaoSEI::getInstance()->validarPermissao('md_cor_diretoria_listar');

      //Regras de Negocio
      //$objInfraException = new InfraException();

      //$objInfraException->lancarValidacoes();

      $objMdCorDiretoriaBD = new MdCorDiretoriaBD($this->getObjInfraIBanco());
      $ret = $objMdCorDiretoriaBD->contar($objMdCorDiretoriaDTO);

      //Auditoria

      return $ret;
    }catch(Exception $e){
      throw new InfraException('Erro contando .',$e);
    }
  }
/* 
  protected function desativarControlado($arrObjMdCorDiretoriaDTO){
    try {

      //Valida Permissao
      ::getInstance()->validarPermissao('md_cor_diretoria_desativar');

      //Regras de Negocio
      //$objInfraException = new InfraException();

      //$objInfraException->lancarValidacoes();

      $objMdCorDiretoriaBD = new MdCorDiretoriaBD($this->getObjInfraIBanco());
      for($i=0;$i<count($arrObjMdCorDiretoriaDTO);$i++){
        $objMdCorDiretoriaBD->desativar($arrObjMdCorDiretoriaDTO[$i]);
      }

      //Auditoria

    }catch(Exception $e){
      throw new InfraException('Erro desativando .',$e);
    }
  }

  protected function reativarControlado($arrObjMdCorDiretoriaDTO){
    try {

      //Valida Permissao
      ::getInstance()->validarPermissao('md_cor_diretoria_reativar');

      //Regras de Negocio
      //$objInfraException = new InfraException();

      //$objInfraException->lancarValidacoes();

      $objMdCorDiretoriaBD = new MdCorDiretoriaBD($this->getObjInfraIBanco());
      for($i=0;$i<count($arrObjMdCorDiretoriaDTO);$i++){
        $objMdCorDiretoriaBD->reativar($arrObjMdCorDiretoriaDTO[$i]);
      }

      //Auditoria

    }catch(Exception $e){
      throw new InfraException('Erro reativando .',$e);
    }
  }

  protected function bloquearControlado(MdCorDiretoriaDTO $objMdCorDiretoriaDTO){
    try {

      //Valida Permissao
      ::getInstance()->validarPermissao('md_cor_diretoria_consultar');

      //Regras de Negocio
      //$objInfraException = new InfraException();

      //$objInfraException->lancarValidacoes();

      $objMdCorDiretoriaBD = new MdCorDiretoriaBD($this->getObjInfraIBanco());
      $ret = $objMdCorDiretoriaBD->bloquear($objMdCorDiretoriaDTO);

      //Auditoria

      return $ret;
    }catch(Exception $e){
      throw new InfraException('Erro bloqueando .',$e);
    }
  }

 */
}
