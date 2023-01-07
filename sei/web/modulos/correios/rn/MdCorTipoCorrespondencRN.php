<?
/**
* TRIBUNAL REGIONAL FEDERAL DA 4ª REGIÃO
*
* 04/12/2017 - criado por augusto.cast
*
* Versão do Gerador de Código: 1.41.0
*/

require_once dirname(__FILE__) . '/../../../SEI.php';

class MdCorTipoCorrespondencRN extends InfraRN {

  public function __construct(){
    parent::__construct();
  }

  protected function inicializarObjInfraIBanco(){
    return BancoSEI::getInstance();
  }

  private function validarNumIdMdCorTipoCorrespondenc(MdCorTipoCorrespondencDTO $objMdCorTipoCorrespondencDTO, InfraException $objInfraException){
    if (InfraString::isBolVazia($objMdCorTipoCorrespondencDTO->getNumIdMdCorTipoCorrespondenc())){
      $objInfraException->adicionarValidacao(' não informad.');
    }
  }

  private function validarStrNomeTipo(MdCorTipoCorrespondencDTO $objMdCorTipoCorrespondencDTO, InfraException $objInfraException){
    if (InfraString::isBolVazia($objMdCorTipoCorrespondencDTO->getStrNomeTipo())){
      $objInfraException->adicionarValidacao(' não informad.');
    }else{
      $objMdCorTipoCorrespondencDTO->setStrNomeTipo(trim($objMdCorTipoCorrespondencDTO->getStrNomeTipo()));

      if (strlen($objMdCorTipoCorrespondencDTO->getStrNomeTipo())>50){
        $objInfraException->adicionarValidacao(' possui tamanho superior a 50 caracteres.');
      }
    }
  }

  private function validarStrSinAr(MdCorTipoCorrespondencDTO $objMdCorTipoCorrespondencDTO, InfraException $objInfraException){
    if (InfraString::isBolVazia($objMdCorTipoCorrespondencDTO->getStrSinAr())){
      $objInfraException->adicionarValidacao('Sinalizador de  não informado.');
    }else{
      if (!InfraUtil::isBolSinalizadorValido($objMdCorTipoCorrespondencDTO->getStrSinAr())){
        $objInfraException->adicionarValidacao('Sinalizador de  inválid.');
      }
    }
  }

  protected function cadastrarControlado(MdCorTipoCorrespondencDTO $objMdCorTipoCorrespondencDTO) {
    try{

      //Valida Permissao
      SessaoSEI::getInstance()->validarPermissao('md_cor_tipo_correspondenc_cadastrar');

      //Regras de Negocio
      $objInfraException = new InfraException();

      $this->validarNumIdMdCorTipoCorrespondenc($objMdCorTipoCorrespondencDTO, $objInfraException);
      $this->validarStrNomeTipo($objMdCorTipoCorrespondencDTO, $objInfraException);
      $this->validarStrSinAr($objMdCorTipoCorrespondencDTO, $objInfraException);

      $objInfraException->lancarValidacoes();

      $objMdCorTipoCorrespondencBD = new MdCorTipoCorrespondencBD($this->getObjInfraIBanco());
      $ret = $objMdCorTipoCorrespondencBD->cadastrar($objMdCorTipoCorrespondencDTO);

      //Auditoria

      return $ret;

    }catch(Exception $e){
      throw new InfraException('Erro cadastrando .',$e);
    }
  }

  protected function alterarControlado(MdCorTipoCorrespondencDTO $objMdCorTipoCorrespondencDTO){
    try {

      //Valida Permissao
      SessaoSEI::getInstance()->validarPermissao('md_cor_tipo_correspondenc_alterar');

      //Regras de Negocio
      $objInfraException = new InfraException();

      if ($objMdCorTipoCorrespondencDTO->isSetNumIdMdCorTipoCorrespondenc()){
        $this->validarNumIdMdCorTipoCorrespondenc($objMdCorTipoCorrespondencDTO, $objInfraException);
      }
      if ($objMdCorTipoCorrespondencDTO->isSetStrNomeTipo()){
        $this->validarStrNomeTipo($objMdCorTipoCorrespondencDTO, $objInfraException);
      }
      if ($objMdCorTipoCorrespondencDTO->isSetStrSinAr()){
        $this->validarStrSinAr($objMdCorTipoCorrespondencDTO, $objInfraException);
      }

      $objInfraException->lancarValidacoes();

      $objMdCorTipoCorrespondencBD = new MdCorTipoCorrespondencBD($this->getObjInfraIBanco());
      $objMdCorTipoCorrespondencBD->alterar($objMdCorTipoCorrespondencDTO);

      //Auditoria

    }catch(Exception $e){
      throw new InfraException('Erro alterando .',$e);
    }
  }

  protected function excluirControlado($arrObjMdCorTipoCorrespondencDTO){
    try {

      //Valida Permissao
      SessaoSEI::getInstance()->validarPermissao('md_cor_tipo_correspondenc_excluir');

      //Regras de Negocio
      //$objInfraException = new InfraException();

      //$objInfraException->lancarValidacoes();

      $objMdCorTipoCorrespondencBD = new MdCorTipoCorrespondencBD($this->getObjInfraIBanco());
      for($i=0;$i<count($arrObjMdCorTipoCorrespondencDTO);$i++){
        $objMdCorTipoCorrespondencBD->excluir($arrObjMdCorTipoCorrespondencDTO[$i]);
      }

      //Auditoria

    }catch(Exception $e){
      throw new InfraException('Erro excluindo .',$e);
    }
  }

  protected function consultarConectado(MdCorTipoCorrespondencDTO $objMdCorTipoCorrespondencDTO){
    try {

      //Valida Permissao
      SessaoSEI::getInstance()->validarPermissao('md_cor_tipo_correspondenc_consultar');

      //Regras de Negocio
      //$objInfraException = new InfraException();

      //$objInfraException->lancarValidacoes();

      $objMdCorTipoCorrespondencBD = new MdCorTipoCorrespondencBD($this->getObjInfraIBanco());
      $ret = $objMdCorTipoCorrespondencBD->consultar($objMdCorTipoCorrespondencDTO);

      //Auditoria

      return $ret;
    }catch(Exception $e){
      throw new InfraException('Erro consultando .',$e);
    }
  }

  protected function listarConectado(MdCorTipoCorrespondencDTO $objMdCorTipoCorrespondencDTO) {
    try {

      //Valida Permissao
//      SessaoSEI::getInstance()->validarPermissao('md_cor_tipo_correspondenc_listar');

      //Regras de Negocio
      //$objInfraException = new InfraException();

      //$objInfraException->lancarValidacoes();

      $objMdCorTipoCorrespondencBD = new MdCorTipoCorrespondencBD($this->getObjInfraIBanco());
      $ret = $objMdCorTipoCorrespondencBD->listar($objMdCorTipoCorrespondencDTO);

      //Auditoria

      return $ret;

    }catch(Exception $e){
      throw new InfraException('Erro listando .',$e);
    }
  }

  protected function contarConectado(MdCorTipoCorrespondencDTO $objMdCorTipoCorrespondencDTO){
    try {

      //Valida Permissao
      SessaoSEI::getInstance()->validarPermissao('md_cor_tipo_correspondenc_listar');

      //Regras de Negocio
      //$objInfraException = new InfraException();

      //$objInfraException->lancarValidacoes();

      $objMdCorTipoCorrespondencBD = new MdCorTipoCorrespondencBD($this->getObjInfraIBanco());
      $ret = $objMdCorTipoCorrespondencBD->contar($objMdCorTipoCorrespondencDTO);

      //Auditoria

      return $ret;
    }catch(Exception $e){
      throw new InfraException('Erro contando .',$e);
    }
  }
/* 
  protected function desativarControlado($arrObjMdCorTipoCorrespondencDTO){
    try {

      //Valida Permissao
      ::getInstance()->validarPermissao('md_cor_tipo_correspondenc_desativar');

      //Regras de Negocio
      //$objInfraException = new InfraException();

      //$objInfraException->lancarValidacoes();

      $objMdCorTipoCorrespondencBD = new MdCorTipoCorrespondencBD($this->getObjInfraIBanco());
      for($i=0;$i<count($arrObjMdCorTipoCorrespondencDTO);$i++){
        $objMdCorTipoCorrespondencBD->desativar($arrObjMdCorTipoCorrespondencDTO[$i]);
      }

      //Auditoria

    }catch(Exception $e){
      throw new InfraException('Erro desativando .',$e);
    }
  }

  protected function reativarControlado($arrObjMdCorTipoCorrespondencDTO){
    try {

      //Valida Permissao
      ::getInstance()->validarPermissao('md_cor_tipo_correspondenc_reativar');

      //Regras de Negocio
      //$objInfraException = new InfraException();

      //$objInfraException->lancarValidacoes();

      $objMdCorTipoCorrespondencBD = new MdCorTipoCorrespondencBD($this->getObjInfraIBanco());
      for($i=0;$i<count($arrObjMdCorTipoCorrespondencDTO);$i++){
        $objMdCorTipoCorrespondencBD->reativar($arrObjMdCorTipoCorrespondencDTO[$i]);
      }

      //Auditoria

    }catch(Exception $e){
      throw new InfraException('Erro reativando .',$e);
    }
  }

  protected function bloquearControlado(MdCorTipoCorrespondencDTO $objMdCorTipoCorrespondencDTO){
    try {

      //Valida Permissao
      ::getInstance()->validarPermissao('md_cor_tipo_correspondenc_consultar');

      //Regras de Negocio
      //$objInfraException = new InfraException();

      //$objInfraException->lancarValidacoes();

      $objMdCorTipoCorrespondencBD = new MdCorTipoCorrespondencBD($this->getObjInfraIBanco());
      $ret = $objMdCorTipoCorrespondencBD->bloquear($objMdCorTipoCorrespondencDTO);

      //Auditoria

      return $ret;
    }catch(Exception $e){
      throw new InfraException('Erro bloqueando .',$e);
    }
  }

 */
}
