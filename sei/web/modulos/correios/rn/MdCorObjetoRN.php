<?
/**
* TRIBUNAL REGIONAL FEDERAL DA 4ª REGIÃO
*
* 14/11/2017 - criado por ellyson.silva
*
* Versão do Gerador de Código: 1.41.0
*/

require_once dirname(__FILE__).'/../../../SEI.php';

class MdCorObjetoRN extends InfraRN {

    public static $ROTULO_COMPLETO = 'C';
    public static $ROTULO_RESUMIDO = 'R';

  public function __construct(){
    parent::__construct();
  }

  protected function inicializarObjInfraIBanco(){
    return BancoSEI::getInstance();
  }

  private function validarNumIdMdCorTipoObjeto(MdCorObjetoDTO $objMdCorObjetoDTO, InfraException $objInfraException){
    if (InfraString::isBolVazia($objMdCorObjetoDTO->getNumIdMdCorTipoObjeto())){
      $objInfraException->adicionarValidacao('tipo de objeto não informado.');
    }
  }

  private function validarNumIdMdCorContrato(MdCorObjetoDTO $objMdCorObjetoDTO, InfraException $objInfraException){
    if (InfraString::isBolVazia($objMdCorObjetoDTO->getNumIdMdCorContrato())){
      $objInfraException->adicionarValidacao('contrato não informado.');
    }
  }

  private function validarStrTipoRotuloImpressao(MdCorObjetoDTO $objMdCorObjetoDTO, InfraException $objInfraException){
    if (InfraString::isBolVazia($objMdCorObjetoDTO->getStrTipoRotuloImpressao())){
        $objMdCorObjetoDTO->setStrTipoRotuloImpressao(null);
    }else{
        $objMdCorObjetoDTO->setStrTipoRotuloImpressao(trim($objMdCorObjetoDTO->getStrTipoRotuloImpressao()));

      if (strlen($objMdCorObjetoDTO->getStrTipoRotuloImpressao())>1){
        $objInfraException->adicionarValidacao('tipo de utilizado para impressão possui tamanho superior a 1 caracteres.');
      }
    }
  }

  private function validarStrSinObjetoPadrao(MdCorObjetoDTO $objMdCorObjetoDTO, InfraException $objInfraException){
    if (InfraString::isBolVazia($objMdCorObjetoDTO->getStrSinObjetoPadrao())) {
        $objInfraException->adicionarValidacao('Sinalizador de objeto padrão para expedição não informado.');
    }elseif($objMdCorObjetoDTO->getStrSinObjetoPadrao() == 'S'){
        $objMdCorObjetoExiste = new MdCorObjetoDTO();
        $objMdCorObjetoExiste->retStrSinObjetoPadrao();
        $objMdCorObjetoExiste->retStrNomeTipoObjeto();
        $objMdCorObjetoExiste->setStrSinObjetoPadrao('S');
        $objMdCorObjetoExiste->setStrSinAtivo('S');
        $objMdCorObjetoExiste->setNumIdMdCorContrato($objMdCorObjetoDTO->getNumIdMdCorContrato());
        $objMdCorObjetoExiste->setNumIdMdCorObjeto($objMdCorObjetoDTO->getNumIdMdCorObjeto(), InfraDTO::$OPER_DIFERENTE);

        $objMdCorObjetoExiste = $this->consultar($objMdCorObjetoExiste);
        if($objMdCorObjetoExiste > 0){
            $objInfraException->adicionarValidacao('Sinalizador de objeto padrão já selecionado para o tipo de objeto '.$objMdCorObjetoExiste->getStrNomeTipoObjeto().'.');
        }
    }else{
      if (!InfraUtil::isBolSinalizadorValido($objMdCorObjetoDTO->getStrSinObjetoPadrao())){
        $objInfraException->adicionarValidacao('Sinalizador de objeto padrão para expedição inválido.');
      }
    }
  }

  private function validarDblMargemSuperiorImpressao(MdCorObjetoDTO $objMdCorObjetoDTO, InfraException $objInfraException){
    if (InfraString::isBolVazia($objMdCorObjetoDTO->getDblMargemSuperiorImpressao())){
      $objMdCorObjetoDTO->setDblMargemSuperiorImpressao(null);
    }
  }

  private function validarDblMargemEsquerdaImpressao(MdCorObjetoDTO $objMdCorObjetoDTO, InfraException $objInfraException){
    if (InfraString::isBolVazia($objMdCorObjetoDTO->getDblMargemEsquerdaImpressao())){
      $objMdCorObjetoDTO->setDblMargemEsquerdaImpressao(null);
    }
  }

  protected function cadastrarControlado(MdCorObjetoDTO $objMdCorObjetoDTO) {
    try{

      //Valida Permissao
      SessaoSEI::getInstance()->validarPermissao('md_cor_objeto_cadastrar');

      //Regras de Negocio
      $objInfraException = new InfraException();

      $this->validarNumIdMdCorTipoObjeto($objMdCorObjetoDTO, $objInfraException);
      $this->validarNumIdMdCorContrato($objMdCorObjetoDTO, $objInfraException);
      $this->validarStrTipoRotuloImpressao($objMdCorObjetoDTO, $objInfraException);
      $this->validarStrSinObjetoPadrao($objMdCorObjetoDTO, $objInfraException);
      $this->validarDblMargemSuperiorImpressao($objMdCorObjetoDTO, $objInfraException);
      $this->validarDblMargemEsquerdaImpressao($objMdCorObjetoDTO, $objInfraException);

      $objInfraException->lancarValidacoes();

      $objMdCorObjetoBD = new MdCorObjetoBD($this->getObjInfraIBanco());
      $ret = $objMdCorObjetoBD->cadastrar($objMdCorObjetoDTO);


      //Auditoria
      return $ret;

    }catch(Exception $e){
      throw new InfraException('Erro cadastrando Objeto.',$e);
    }
  }

  protected function alterarControlado(MdCorObjetoDTO $objMdCorObjetoDTO){
    try {

      //Valida Permissao
  	   SessaoSEI::getInstance()->validarPermissao('md_cor_objeto_alterar');

      //Regras de Negocio
      $objInfraException = new InfraException();

      if ($objMdCorObjetoDTO->isSetNumIdMdCorTipoObjeto()){
        $this->validarNumIdMdCorTipoObjeto($objMdCorObjetoDTO, $objInfraException);
      }
      if ($objMdCorObjetoDTO->isSetNumIdMdCorContrato()){
        $this->validarNumIdMdCorContrato($objMdCorObjetoDTO, $objInfraException);
      }
      if ($objMdCorObjetoDTO->isSetStrTipoRotuloImpressao()){
        $this->validarStrTipoRotuloImpressao($objMdCorObjetoDTO, $objInfraException);
      }
      if ($objMdCorObjetoDTO->isSetStrSinObjetoPadrao()){
        $this->validarStrSinObjetoPadrao($objMdCorObjetoDTO, $objInfraException);
      }
      if ($objMdCorObjetoDTO->isSetDblMargemSuperiorImpressao()){
        $this->validarDblMargemSuperiorImpressao($objMdCorObjetoDTO, $objInfraException);
      }
      if ($objMdCorObjetoDTO->isSetDblMargemEsquerdaImpressao()){
        $this->validarDblMargemEsquerdaImpressao($objMdCorObjetoDTO, $objInfraException);
      }

      $objInfraException->lancarValidacoes();
      $objMdCorObjetoBD = new MdCorObjetoBD($this->getObjInfraIBanco());
      $ret =  $objMdCorObjetoBD->alterar($objMdCorObjetoDTO);

      //Auditoria
      return $ret;

    }catch(Exception $e){
      throw new InfraException('Erro alterando Objeto.',$e);
    }
  }

  protected function excluirControlado($arrObjMdCorObjetoDTO){
    try {

      //Valida Permissao
      SessaoSEI::getInstance()->validarPermissao('md_cor_objeto_excluir');

      //Regras de Negocio
      //$objInfraException = new InfraException();

      //$objInfraException->lancarValidacoes();

      $objMdCorObjetoBD = new MdCorObjetoBD($this->getObjInfraIBanco());

      $mdCorExpedicaoSolicitadaRN = new MdCorExpedicaoSolicitadaRN();
      $objMdCorExpedicaoSolicitadaDTO = new MdCorExpedicaoSolicitadaDTO();
      foreach ($arrObjMdCorObjetoDTO as $dObjMdCorObjDTO) {
        $objMdCorExpedicaoSolicitadaDTO->setNumIdMdCorObjeto($dObjMdCorObjDTO->getNumIdMdCorObjeto());
        $qtdSolObjeto = $mdCorExpedicaoSolicitadaRN->contar($objMdCorExpedicaoSolicitadaDTO);

        if($qtdSolObjeto > 0){
          $objMdCorObjetoBD->desativar($dObjMdCorObjDTO);
        }else{
          $objMdCorObjetoBD->excluir($dObjMdCorObjDTO);
        }
      }
      //Auditoria

    }catch(Exception $e){
      throw new InfraException('Erro excluindo Objeto.',$e);
    }
  }

  protected function consultarConectado(MdCorObjetoDTO $objMdCorObjetoDTO){
    try {

      //Valida Permissao
      SessaoSEI::getInstance()->validarPermissao('md_cor_objeto_consultar');

      //Regras de Negocio
      //$objInfraException = new InfraException();

      //$objInfraException->lancarValidacoes();

      $objMdCorObjetoBD = new MdCorObjetoBD($this->getObjInfraIBanco());
      $ret = $objMdCorObjetoBD->consultar($objMdCorObjetoDTO);

      //Auditoria

      return $ret;
    }catch(Exception $e){
      throw new InfraException('Erro consultando Objeto.',$e);
    }
  }

  protected function listarConectado(MdCorObjetoDTO $objMdCorObjetoDTO) {
    try {

      //Valida Permissao
      SessaoSEI::getInstance()->validarPermissao('md_cor_objeto_listar');

      //Regras de Negocio
      //$objInfraException = new InfraException();

      //$objInfraException->lancarValidacoes();

      $objMdCorObjetoBD = new MdCorObjetoBD($this->getObjInfraIBanco());
      $ret = $objMdCorObjetoBD->listar($objMdCorObjetoDTO);

      //Auditoria

      return $ret;

    }catch(Exception $e){
      throw new InfraException('Erro listando embalagens.',$e);
    }
  }

  protected function contarConectado(MdCorObjetoDTO $objMdCorObjetoDTO){
    try {

      //Valida Permissao
      SessaoSEI::getInstance()->validarPermissao('md_cor_objeto_listar');

      //Regras de Negocio
      //$objInfraException = new InfraException();

      //$objInfraException->lancarValidacoes();

      $objMdCorObjetoBD = new MdCorObjetoBD($this->getObjInfraIBanco());
      $ret = $objMdCorObjetoBD->contar($objMdCorObjetoDTO);

      //Auditoria

      return $ret;
    }catch(Exception $e){
      throw new InfraException('Erro contando embalagens.',$e);
    }
  }
  /*
  protected function desativarControlado($arrObjMdCorObjetoDTO){
    try {

      //Valida Permissao
      SessaoSEI::getInstance()->validarPermissao('md_cor_objeto_desativar');

      //Regras de Negocio
      //$objInfraException = new InfraException();

      //$objInfraException->lancarValidacoes();

      $objMdCorObjetoBD = new MdCorObjetoBD($this->getObjInfraIBanco());
      for($i=0;$i<count($arrObjMdCorObjetoDTO);$i++){
        $objMdCorObjetoBD->desativar($arrObjMdCorObjetoDTO[$i]);
      }

      //Auditoria

    }catch(Exception $e){
      throw new InfraException('Erro desativando Objeto.',$e);
    }
  }


    protected function reativarControlado($arrObjMdCorObjetoDTO){
      try {

        //Valida Permissao
        SessaoSEI::getInstance()->validarPermissao('md_cor_objeto_reativar');

        //Regras de Negocio
        //$objInfraException = new InfraException();

        //$objInfraException->lancarValidacoes();

        $objMdCorObjetoBD = new MdCorObjetoBD($this->getObjInfraIBanco());
        for($i=0;$i<count($arrObjMdCorObjetoDTO);$i++){
          $objMdCorObjetoBD->reativar($arrObjMdCorObjetoDTO[$i]);
        }

        //Auditoria

      }catch(Exception $e){
        throw new InfraException('Erro reativando Objeto.',$e);
      }
    }

    protected function bloquearControlado(MdCorObjetoDTO $objMdCorObjetoDTO){
      try {

        //Valida Permissao
        SessaoSEI::getInstance()->validarPermissao('md_cor_objeto_consultar');

        //Regras de Negocio
        //$objInfraException = new InfraException();

        //$objInfraException->lancarValidacoes();

        $objMdCorObjetoBD = new MdCorObjetoBD($this->getObjInfraIBanco());
        $ret = $objMdCorObjetoBD->bloquear($objMdCorObjetoDTO);

        //Auditoria

        return $ret;
      }catch(Exception $e){
        throw new InfraException('Erro bloqueando Objeto.',$e);
      }
    }

   */
}
