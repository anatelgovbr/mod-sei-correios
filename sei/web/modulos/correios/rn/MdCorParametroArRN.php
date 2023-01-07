<?
  /**
   * TRIBUNAL REGIONAL FEDERAL DA 4ª REGIÃO
   *
   * 19/06/2018 - criado por augusto.cast
   *
   * Versão do Gerador de Código: 1.41.0
   */

  require_once dirname(__FILE__) . '/../../../SEI.php';

  class MdCorParametroArRN extends InfraRN
  {

    public function __construct()
    {
      parent::__construct();
    }

    protected function inicializarObjInfraIBanco()
    {
      return BancoSEI::getInstance();
    }

    private function validarNumIdMdCorParametroAr(MdCorParametroArDTO $objMdCorParametroArDTO, InfraException $objInfraException)
    {
      if (InfraString::isBolVazia($objMdCorParametroArDTO->getNumIdMdCorParametroAr())) {
        $objInfraException->adicionarValidacao(' não informad.');
      }
    }

    private function validarStrNuDiasRetornoAr(MdCorParametroArDTO $objMdCorParametroArDTO, InfraException $objInfraException)
    {

      if (InfraString::isBolVazia($objMdCorParametroArDTO->getStrNuDiasRetornoAr())) {
        $objInfraException->adicionarValidacao(' não informad.');
      } else {
        $objMdCorParametroArDTO->setStrNuDiasRetornoAr(trim($objMdCorParametroArDTO->getStrNuDiasRetornoAr()));

        if (strlen($objMdCorParametroArDTO->getStrNuDiasRetornoAr()) > 6) {
          $objInfraException->adicionarValidacao(' possui tamanho superior a 6 caracteres.');
        }
      }
    }

    private function validarNumIdSerie(MdCorParametroArDTO $objMdCorParametroArDTO, InfraException $objInfraException)
    {
      if (InfraString::isBolVazia($objMdCorParametroArDTO->getNumIdSerie())) {
        $objInfraException->adicionarValidacao(' não informad.');
      }
    }

    private function validarStrNomeArvore(MdCorParametroArDTO $objMdCorParametroArDTO, InfraException $objInfraException)
    {
      if (InfraString::isBolVazia($objMdCorParametroArDTO->getStrNomeArvore())) {
        $objInfraException->adicionarValidacao(' não informad.');
      } else {
        $objMdCorParametroArDTO->setStrNomeArvore(trim($objMdCorParametroArDTO->getStrNomeArvore()));

        if (strlen($objMdCorParametroArDTO->getStrNomeArvore()) > 60) {
          $objInfraException->adicionarValidacao(' possui tamanho superior a 60 caracteres.');
        }
      }
    }

    private function validarNumIdTipoConferencia(MdCorParametroArDTO $objMdCorParametroArDTO, InfraException $objInfraException)
    {
      if (InfraString::isBolVazia($objMdCorParametroArDTO->getNumIdTipoConferencia())) {
        $objInfraException->adicionarValidacao(' não informad.');
      }
    }

    protected function cadastrarControlado(MdCorParametroArDTO $objMdCorParametroArDTO)
    {
      try {

        //Valida Permissao
        SessaoSEI::getInstance()->validarPermissao('md_cor_parametro_ar_cadastrar');

        //Regras de Negocio
        $objInfraException = new InfraException();

//      $this->validarNumIdMdCorParametroAr($objMdCorParametroArDTO, $objInfraException);
//      $this->validarStrNuDiasRetornoAr($objMdCorParametroArDTO, $objInfraException);
//      $this->validarNumIdSerie($objMdCorParametroArDTO, $objInfraException);
        $this->validarStrNomeArvore($objMdCorParametroArDTO, $objInfraException);
//      $this->validarNumIdTipoConferencia($objMdCorParametroArDTO, $objInfraException);

        $objInfraException->lancarValidacoes();

        $objMdCorParametroArBD = new MdCorParametroArBD($this->getObjInfraIBanco());
        $ret = $objMdCorParametroArBD->cadastrar($objMdCorParametroArDTO);

        //Auditoria

        return $ret;

      } catch (Exception $e) {
        throw new InfraException('Erro cadastrando .', $e);
      }
    }

    protected function alterarControlado(MdCorParametroArDTO $objMdCorParametroArDTO)
    {
      try {

        //Valida Permissao
        SessaoSEI::getInstance()->validarPermissao('md_cor_parametro_ar_cadastrar');

        //Regras de Negocio
        $objInfraException = new InfraException();


        if ($objMdCorParametroArDTO->isSetNumIdMdCorParametroAr()) {
          $this->validarNumIdMdCorParametroAr($objMdCorParametroArDTO, $objInfraException);
        }
        if ($objMdCorParametroArDTO->isSetStrNuDiasRetornoAr()) {
          $this->validarStrNuDiasRetornoAr($objMdCorParametroArDTO, $objInfraException);
        }
        if ($objMdCorParametroArDTO->isSetNumIdSerie()) {
          $this->validarNumIdSerie($objMdCorParametroArDTO, $objInfraException);
        }
        if ($objMdCorParametroArDTO->isSetStrNomeArvore()) {
          $this->validarStrNomeArvore($objMdCorParametroArDTO, $objInfraException);
        }
        if ($objMdCorParametroArDTO->isSetNumIdTipoConferencia()) {
          $this->validarNumIdTipoConferencia($objMdCorParametroArDTO, $objInfraException);
        }

        $objInfraException->lancarValidacoes();

        $objMdCorParametroArBD = new MdCorParametroArBD($this->getObjInfraIBanco());
        $objMdCorParametroArBD->alterar($objMdCorParametroArDTO);

        //Auditoria

      } catch (Exception $e) {
        throw new InfraException('Erro alterando .', $e);
      }
    }

    protected function excluirControlado($arrObjMdCorParametroArDTO)
    {
      try {

        //Valida Permissao
        SessaoSEI::getInstance()->validarPermissao('md_cor_parametro_ar_excluir');

        //Regras de Negocio
        //$objInfraException = new InfraException();

        //$objInfraException->lancarValidacoes();

        $objMdCorParametroArBD = new MdCorParametroArBD($this->getObjInfraIBanco());
        for ($i = 0; $i < count($arrObjMdCorParametroArDTO); $i++) {
          $objMdCorParametroArBD->excluir($arrObjMdCorParametroArDTO[$i]);
        }

        //Auditoria

      } catch (Exception $e) {
        throw new InfraException('Erro excluindo .', $e);
      }
    }

    protected function consultarConectado(MdCorParametroArDTO $objMdCorParametroArDTO)
    {
      try {

        //Valida Permissao
        SessaoSEI::getInstance()->validarPermissao('md_cor_parametro_ar_cadastrar');

        //Regras de Negocio
        //$objInfraException = new InfraException();

        //$objInfraException->lancarValidacoes();

        $objMdCorParametroArBD = new MdCorParametroArBD($this->getObjInfraIBanco());
        $ret = $objMdCorParametroArBD->consultar($objMdCorParametroArDTO);

        //Auditoria

        return $ret;
      } catch (Exception $e) {
        throw new InfraException('Erro consultando .', $e);
      }
    }

    protected function listarConectado(MdCorParametroArDTO $objMdCorParametroArDTO)
    {
      try {

        //Valida Permissao
        SessaoSEI::getInstance()->validarPermissao('md_cor_parametro_ar_listar');

        //Regras de Negocio
        //$objInfraException = new InfraException();

        //$objInfraException->lancarValidacoes();

        $objMdCorParametroArBD = new MdCorParametroArBD($this->getObjInfraIBanco());
        $ret = $objMdCorParametroArBD->listar($objMdCorParametroArDTO);

        //Auditoria

        return $ret;

      } catch (Exception $e) {
        throw new InfraException('Erro listando .', $e);
      }
    }

    protected function contarConectado(MdCorParametroArDTO $objMdCorParametroArDTO)
    {
      try {

        //Valida Permissao
        SessaoSEI::getInstance()->validarPermissao('md_cor_parametro_ar_cadastrar');

        //Regras de Negocio
        //$objInfraException = new InfraException();

        //$objInfraException->lancarValidacoes();

        $objMdCorParametroArBD = new MdCorParametroArBD($this->getObjInfraIBanco());
        $ret = $objMdCorParametroArBD->contar($objMdCorParametroArDTO);

        //Auditoria

        return $ret;
      } catch (Exception $e) {
        throw new InfraException('Erro contando .', $e);
      }
    }


    /*
      protected function desativarControlado($arrObjMdCorParametroArDTO){
        try {

          //Valida Permissao
          SessaoSEI::getInstance()->validarPermissao('md_cor_parametro_ar_desativar');

          //Regras de Negocio
          //$objInfraException = new InfraException();

          //$objInfraException->lancarValidacoes();

          $objMdCorParametroArBD = new MdCorParametroArBD($this->getObjInfraIBanco());
          for($i=0;$i<count($arrObjMdCorParametroArDTO);$i++){
            $objMdCorParametroArBD->desativar($arrObjMdCorParametroArDTO[$i]);
          }

          //Auditoria

        }catch(Exception $e){
          throw new InfraException('Erro desativando .',$e);
        }
      }

      protected function reativarControlado($arrObjMdCorParametroArDTO){
        try {

          //Valida Permissao
          SessaoSEI::getInstance()->validarPermissao('md_cor_parametro_ar_reativar');

          //Regras de Negocio
          //$objInfraException = new InfraException();

          //$objInfraException->lancarValidacoes();

          $objMdCorParametroArBD = new MdCorParametroArBD($this->getObjInfraIBanco());
          for($i=0;$i<count($arrObjMdCorParametroArDTO);$i++){
            $objMdCorParametroArBD->reativar($arrObjMdCorParametroArDTO[$i]);
          }

          //Auditoria

        }catch(Exception $e){
          throw new InfraException('Erro reativando .',$e);
        }
      }

      protected function bloquearControlado(MdCorParametroArDTO $objMdCorParametroArDTO){
        try {

          //Valida Permissao
          SessaoSEI::getInstance()->validarPermissao('md_cor_parametro_ar_consultar');

          //Regras de Negocio
          //$objInfraException = new InfraException();

          //$objInfraException->lancarValidacoes();

          $objMdCorParametroArBD = new MdCorParametroArBD($this->getObjInfraIBanco());
          $ret = $objMdCorParametroArBD->bloquear($objMdCorParametroArDTO);

          //Auditoria

          return $ret;
        }catch(Exception $e){
          throw new InfraException('Erro bloqueando .',$e);
        }
      }

     */
  }
