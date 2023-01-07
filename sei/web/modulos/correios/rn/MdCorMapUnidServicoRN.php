<?
/**
* TRIBUNAL REGIONAL FEDERAL DA 4ª REGIÃO
*
* 22/12/2016 - criado por CAST
*
* Versão do Gerador de Código: 1.39.0
*/

require_once dirname(__FILE__).'/../../../SEI.php';

class MdCorMapUnidServicoRN extends InfraRN {

  public function __construct(){
    parent::__construct();
  }

  protected function inicializarObjInfraIBanco(){
    return BancoSEI::getInstance();
  }

  private function validarStrSinAtivo(MdCorMapUnidServicoDTO $objMdCorMapUnidServicoDTO, InfraException $objInfraException){
    if (InfraString::isBolVazia($objMdCorMapUnidServicoDTO->getStrSinAtivo())){
      $objInfraException->adicionarValidacao('Sinalizador de Exclusão Lógica não informado.');
    }else{
      if (!InfraUtil::isBolSinalizadorValido($objMdCorMapUnidServicoDTO->getStrSinAtivo())){
        $objInfraException->adicionarValidacao('Sinalizador de Exclusão Lógica inválido.');
      }
    }
  }

  protected function cadastrarControlado(MdCorMapUnidServicoDTO $objMdCorMapUnidServicoDTO) {
    try{

      //Valida Permissao
      SessaoSEI::getInstance()->validarPermissao('md_cor_map_unid_servico_cadastrar');

      //Regras de Negocio
      $objInfraException = new InfraException();

      $this->validarStrSinAtivo($objMdCorMapUnidServicoDTO, $objInfraException);

      $objInfraException->lancarValidacoes();

      /*
      // MÚLTIPLO
        $arrObjAndamentoMarcadorDTO = array();
        foreach ($arrIdProcedimento as $dblIdProcedimento) {

          if (!isset($arrIdProcedimentoNaoModificado[$dblIdProcedimento])) {
            $dto = clone($objAndamentoMarcadorDTO);
            $dto->setDblIdProcedimento($dblIdProcedimento);
            $arrObjAndamentoMarcadorDTO[] = $dto;
          }
        }
        $ret = $objAndamentoMarcadorBD->cadastrar($arrObjAndamentoMarcadorDTO);
      */
        
      $objMdCorMapUnidServicoBD = new MdCorMapUnidServicoBD($this->getObjInfraIBanco());
      $ret = $objMdCorMapUnidServicoBD->cadastrar($objMdCorMapUnidServicoDTO);


      //Auditoria

      return $ret;

    }catch(Exception $e){
      throw new InfraException('Erro cadastrando .',$e);
    }
  }

  protected function alterarControlado(MdCorMapUnidServicoDTO $objMdCorMapUnidServicoDTO){
    try {

      //Valida Permissao
  	   SessaoSEI::getInstance()->validarPermissao('md_cor_map_unid_servico_alterar');

      //Regras de Negocio
      $objInfraException = new InfraException();

      if ($objMdCorMapUnidServicoDTO->isSetStrSinAtivo()){
        $this->validarStrSinAtivo($objMdCorMapUnidServicoDTO, $objInfraException);
      }

      $objInfraException->lancarValidacoes();

      $objMdCorMapUnidServicoBD = new MdCorMapUnidServicoBD($this->getObjInfraIBanco());
      $objMdCorMapUnidServicoBD->alterar($objMdCorMapUnidServicoDTO);

      //Auditoria

    }catch(Exception $e){
      throw new InfraException('Erro alterando .',$e);
    }
  }

  protected function excluirControlado($arrObjMdCorMapUnidServicoDTO){
    try {

      //Valida Permissao
      SessaoSEI::getInstance()->validarPermissao('md_cor_map_unid_servico_excluir');

      //Regras de Negocio
      //$objInfraException = new InfraException();

      //$objInfraException->lancarValidacoes();

      $objMdCorMapUnidServicoBD = new MdCorMapUnidServicoBD($this->getObjInfraIBanco());
      for($i=0;$i<count($arrObjMdCorMapUnidServicoDTO);$i++){
        $objMdCorMapUnidServicoBD->excluir($arrObjMdCorMapUnidServicoDTO[$i]);
      }

      //Auditoria

    }catch(Exception $e){
      throw new InfraException('Erro excluindo .',$e);
    }
  }

  protected function consultarConectado(MdCorMapUnidServicoDTO $objMdCorMapUnidServicoDTO){
    try {

      //Valida Permissao
      SessaoSEI::getInstance()->validarPermissao('md_cor_map_unid_servico_consultar');

      //Regras de Negocio
      //$objInfraException = new InfraException();

      //$objInfraException->lancarValidacoes();

      $objMdCorMapUnidServicoBD = new MdCorMapUnidServicoBD($this->getObjInfraIBanco());
      $ret = $objMdCorMapUnidServicoBD->consultar($objMdCorMapUnidServicoDTO);

      //Auditoria

      return $ret;
    }catch(Exception $e){
      throw new InfraException('Erro consultando .',$e);
    }
  }

  protected function listarConectado(MdCorMapUnidServicoDTO $objMdCorMapUnidServicoDTO) {
    try {

      //Valida Permissao
      SessaoSEI::getInstance()->validarPermissao('md_cor_map_unid_servico_listar');

      //Regras de Negocio
      //$objInfraException = new InfraException();

      //$objInfraException->lancarValidacoes();

      $objMdCorMapUnidServicoBD = new MdCorMapUnidServicoBD($this->getObjInfraIBanco());
      $ret = $objMdCorMapUnidServicoBD->listar($objMdCorMapUnidServicoDTO);
      //Auditoria
      return $ret;

    }catch(Exception $e){
      throw new InfraException('Erro listando .',$e);
    }
  }

  protected function contarConectado(MdCorMapUnidServicoDTO $objMdCorMapUnidServicoDTO){
    try {

      //Valida Permissao
      SessaoSEI::getInstance()->validarPermissao('md_cor_map_unid_servico_listar');

      //Regras de Negocio
      //$objInfraException = new InfraException();

      //$objInfraException->lancarValidacoes();

      $objMdCorMapUnidServicoBD = new MdCorMapUnidServicoBD($this->getObjInfraIBanco());
      $ret = $objMdCorMapUnidServicoBD->contar($objMdCorMapUnidServicoDTO);

      //Auditoria

      return $ret;
    }catch(Exception $e){
      throw new InfraException('Erro contando .',$e);
    }
  }

  protected function desativarControlado($arrObjMdCorMapUnidServicoDTO){
    try {

      //Valida Permissao
      SessaoSEI::getInstance()->validarPermissao('md_cor_map_unid_servico_desativar');

      //Regras de Negocio
      //$objInfraException = new InfraException();

      //$objInfraException->lancarValidacoes();

      $objMdCorMapUnidServicoBD = new MdCorMapUnidServicoBD($this->getObjInfraIBanco());
      for($i=0;$i<count($arrObjMdCorMapUnidServicoDTO);$i++){
        $objMdCorMapUnidServicoBD->desativar($arrObjMdCorMapUnidServicoDTO[$i]);
      }

      //Auditoria

    }catch(Exception $e){
      throw new InfraException('Erro desativando .',$e);
    }
  }

  protected function reativarControlado($arrObjMdCorMapUnidServicoDTO){
    try {

      //Valida Permissao
      SessaoSEI::getInstance()->validarPermissao('md_cor_map_unid_servico_reativar');

      //Regras de Negocio
      //$objInfraException = new InfraException();

      //$objInfraException->lancarValidacoes();

      $objMdCorMapUnidServicoBD = new MdCorMapUnidServicoBD($this->getObjInfraIBanco());
      for($i=0;$i<count($arrObjMdCorMapUnidServicoDTO);$i++){
        $objMdCorMapUnidServicoBD->reativar($arrObjMdCorMapUnidServicoDTO[$i]);
      }

      //Auditoria

    }catch(Exception $e){
      throw new InfraException('Erro reativando .',$e);
    }
  }

  protected function bloquearControlado(MdCorMapUnidServicoDTO $objMdCorMapUnidServicoDTO){
    try {

      //Valida Permissao
      SessaoSEI::getInstance()->validarPermissao('md_cor_map_unid_servico_consultar');

      //Regras de Negocio
      //$objInfraException = new InfraException();

      //$objInfraException->lancarValidacoes();

      $objMdCorMapUnidServicoBD = new MdCorMapUnidServicoBD($this->getObjInfraIBanco());
      $ret = $objMdCorMapUnidServicoBD->bloquear($objMdCorMapUnidServicoDTO);

      //Auditoria

      return $ret;
    }catch(Exception $e){
      throw new InfraException('Erro bloqueando .',$e);
    }
  }


}
?>