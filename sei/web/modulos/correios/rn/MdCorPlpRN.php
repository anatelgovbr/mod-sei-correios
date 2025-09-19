<?
  /**
   * TRIBUNAL REGIONAL FEDERAL DA 4ª REGIÃO
   *
   * 11/10/2017 - criado por José Vieira <jose.vieira@cast.com.br>
   *
   * Versão do Gerador de Código: 1.41.0
   */

  require_once dirname(__FILE__) . '/../../../SEI.php';

  class MdCorPlpRN extends InfraRN {

    public static $PLP_1 = 'A';
    public static $PLP_2 = 'B';
    public static $PLP_3 = 'C';

    public static $STA_GERADA = 'G';
    public static $STA_PENDENTE = 'P';
    public static $STA_ENTREGUES = 'E';
    public static $STA_CANCELADA = 'C';

    public static $STA_RETORNO_AR_PENDENTE = 'R';
    public static $STA_FINALIZADA = 'F';

    public static $STR_SING_PRE_POSTAGEM = 'Pré-Postagem';
    public static $STR_PLURAL_PRE_POSTAGEM = 'Pré-Postagens';

    public function __construct() {
      parent::__construct();
    }

    protected function inicializarObjInfraIBanco() {
      return BancoSEI::getInstance();
    }

    public function listarValoresPlp() {
      try {

        $arrObjPlpMdCorPlpDTO = array();

        $objPlpMdCorPlpDTO = new MdCorPlpDTO();
        $objPlpMdCorPlpDTO->setStrStaPlp(self::$PLP_1);
        $objPlpMdCorPlpDTO->setStrDescricao('Descrição Plp 1');
        $arrObjPlpMdCorPlpDTO[] = $objPlpMdCorPlpDTO;

        $objPlpMdCorPlpDTO = new PlpMdCorPlpDTO();
        $objPlpMdCorPlpDTO->setStrStaPlp(self::$PLP_2);
        $objPlpMdCorPlpDTO->setStrDescricao('Descrição Plp 2');
        $arrObjPlpMdCorPlpDTO[] = $objPlpMdCorPlpDTO;

        $objPlpMdCorPlpDTO = new PlpMdCorPlpDTO();
        $objPlpMdCorPlpDTO->setStrStaPlp(self::$PLP_3);
        $objPlpMdCorPlpDTO->setStrDescricao('Descrição Plp 3');
        $arrObjPlpMdCorPlpDTO[] = $objPlpMdCorPlpDTO;

        return $arrObjPlpMdCorPlpDTO;

      } catch (Exception $e) {
        throw new InfraException('Erro listando valores de Plp.', $e);
      }
    }

    private function validarNumIdMdPlp(MdCorPlpDTO $objMdCorPlpDTO, InfraException $objInfraException) {
      if (InfraString::isBolVazia($objMdCorPlpDTO->getNumIdMdPlp())) {
        $objInfraException->adicionarValidacao(' não informad.');
      }
    }

    private function validarDblCodigoPlp(MdCorPlpDTO $objMdCorPlpDTO, InfraException $objInfraException) {
      if (InfraString::isBolVazia($objMdCorPlpDTO->getDblCodigoPlp())) {
        $objInfraException->adicionarValidacao(' não informad.');
      }
    }

    private function validarStrStaPlp(MdCorPlpDTO $objMdCorPlpDTO, InfraException $objInfraException) {
      if (InfraString::isBolVazia($objMdCorPlpDTO->getStrStaPlp())) {
        $objInfraException->adicionarValidacao(' não informad.');
      } else {
        if (!in_array($objMdCorPlpDTO->getStrStaPlp(), InfraArray::converterArrInfraDTO($this->listarValoresPlp(), 'StaPlp'))) {
          $objInfraException->adicionarValidacao(' inválid.');
        }
      }
    }

    protected function cadastrarControlado(MdCorPlpDTO $objMdCorPlpDTO) {
      try {
        //Valida Permissao
        SessaoSEI::getInstance()->validarPermissao('md_cor_plp_cadastrar');

        //Regras de Negocio
        $objInfraException = new InfraException();

//      $this->validarNumIdMdPlp($objMdCorPlpDTO, $objInfraException);
//      $this->validarDblCodigoPlp($objMdCorPlpDTO, $objInfraException);
//      $this->validarStrStaPlp($objMdCorPlpDTO, $objInfraException);

        $objInfraException->lancarValidacoes();

        $objMdCorPlpBD = new MdCorPlpBD($this->getObjInfraIBanco());
        $ret = $objMdCorPlpBD->cadastrar($objMdCorPlpDTO);

        //Auditoria

        return $ret;

      } catch (Exception $e) {
        throw new InfraException('Erro cadastrando .', $e);
      }
    }

    protected function alterarControlado(MdCorPlpDTO $objMdCorPlpDTO) {
      try {

        //Valida Permissao
        SessaoSEI::getInstance()->validarPermissao('md_cor_plp_alterar');

        //Regras de Negocio
        $objInfraException = new InfraException();

//      if ($objMdCorPlpDTO->isSetNumIdMdPlp()){
//        $this->validarNumIdMdPlp($objMdCorPlpDTO, $objInfraException);
//      }
//      if ($objMdCorPlpDTO->isSetDblCodigoPlp()){
//        $this->validarDblCodigoPlp($objMdCorPlpDTO, $objInfraException);
//      }
//      if ($objMdCorPlpDTO->isSetStrStaPlp()){
//        $this->validarStrStaPlp($objMdCorPlpDTO, $objInfraException);
//      }

        $objInfraException->lancarValidacoes();

        $objMdCorPlpBD = new MdCorPlpBD($this->getObjInfraIBanco());
        $objMdCorPlpBD->alterar($objMdCorPlpDTO);

        //Auditoria

      } catch (Exception $e) {
        throw new InfraException('Erro alterando .', $e);
      }
    }

    protected function excluirControlado($arrObjMdCorPlpDTO) {
      try {

        //Valida Permissao
        SessaoSEI::getInstance()->validarPermissao('md_cor_plp_excluir');

        //Regras de Negocio
        //$objInfraException = new InfraException();

        //$objInfraException->lancarValidacoes();

        $objMdCorPlpBD = new MdCorPlpBD($this->getObjInfraIBanco());
        for ($i = 0; $i < count($arrObjMdCorPlpDTO); $i++) {
          $objMdCorPlpBD->excluir($arrObjMdCorPlpDTO[$i]);
        }

        //Auditoria

      } catch (Exception $e) {
        throw new InfraException('Erro excluindo .', $e);
      }
    }

    protected function consultarConectado(MdCorPlpDTO $objMdCorPlpDTO) {
      try {

        //Valida Permissao
        SessaoSEI::getInstance()->validarPermissao('md_cor_plp_consultar');

        //Regras de Negocio
        //$objInfraException = new InfraException();

        //$objInfraException->lancarValidacoes();

        $objMdCorPlpBD = new MdCorPlpBD($this->getObjInfraIBanco());
        $ret = $objMdCorPlpBD->consultar($objMdCorPlpDTO);

        //Auditoria

        return $ret;
      } catch (Exception $e) {
        throw new InfraException('Erro consultando .', $e);
      }
    }

    protected function listarConectado(MdCorPlpDTO $objMdCorPlpDTO) {
      try {

        //Valida Permissao
        SessaoSEI::getInstance()->validarPermissao('md_cor_plp_listar');

        //Regras de Negocio
        //$objInfraException = new InfraException();

        //$objInfraException->lancarValidacoes();

        $objMdCorPlpBD = new MdCorPlpBD($this->getObjInfraIBanco());
        $ret = $objMdCorPlpBD->listar($objMdCorPlpDTO);
        $count = $objMdCorPlpBD->contar($objMdCorPlpDTO);

        foreach ($ret as $item) {
          $item->setNumContagem($count);
        }

        //Auditoria

        return $ret;

      } catch (Exception $e) {
        throw new InfraException('Erro listando .', $e);
      }
    }

    protected function listarComExpedicaoSolicitadaConectado(MdCorPlpDTO $objMdCorPlpDTO) {
      try {

        //Valida Permissao
        SessaoSEI::getInstance()->validarPermissao('md_cor_plp_listar');
        $idUnidadeAtual = SessaoSEI::getInstance()->getNumIdUnidadeAtual();
        //Regras de Negocio
        //$objInfraException = new InfraException();

        //$objInfraException->lancarValidacoes();

        $objMdCorPlpBD = new MdCorPlpBD($this->getObjInfraIBanco());

        if (isset($_POST['numDocumentoPrincipal']) && $_POST['numDocumentoPrincipal'] != "") {
          $objMdCorPlpDTO->setDblCodigoPlp( $this->getCodigoPlP( ['tipo' => 2 , 'valor' => $_POST['numDocumentoPrincipal'] ] ) );
        }

        if (isset($_POST['txtCodRastreamento']) && !empty($_POST['txtCodRastreamento'])) {
          $objMdCorPlpDTO->setDblCodigoPlp( $this->getCodigoPlP( ['tipo' => 1 , 'valor' => $_POST['txtCodRastreamento'] ] ) );
        }

        $ret = $objMdCorPlpBD->listar($objMdCorPlpDTO);

        foreach ($ret as $key => $item) {
          $objMdCorExpedicaoSolicitadaDTO = new MdCorExpedicaoSolicitadaDTO();
          $objMdCorExpedicaoSolicitadaDTO->retNumIdMdCorExpedicaoSolicitada();
          $objMdCorExpedicaoSolicitadaDTO->retNumIdMdCorServicoPostal();
          $objMdCorExpedicaoSolicitadaDTO->retDblIdMdCorContrato();
          $objMdCorExpedicaoSolicitadaDTO->retStrNomeServicoPostal();
          $objMdCorExpedicaoSolicitadaDTO->retStrDescricaoServicoPostal();
          $objMdCorExpedicaoSolicitadaDTO->retStrNumeroContrato();
          $objMdCorExpedicaoSolicitadaDTO->retStrNumeroContratoCorreio();
          $objMdCorExpedicaoSolicitadaDTO->retStrFormaExpedicao();
          $objMdCorExpedicaoSolicitadaDTO->retStrCodigoRastreamento();
          $objMdCorExpedicaoSolicitadaDTO->setDblIdUnidadeExpedidora($idUnidadeAtual);

          $objMdCorExpedicaoSolicitadaDTO->setNumIdMdCorPlp($item->getNumIdMdPlp());

          if (isset($_POST['selServicoPostal']) && $_POST['selServicoPostal'] != "null") {
            $objMdCorExpedicaoSolicitadaDTO->setNumIdMdCorServicoPostal($_POST['selServicoPostal']);
          }
          if (isset($_POST['numProcesso']) && $_POST['numProcesso'] != "") {
            $objMdCorExpedicaoSolicitadaDTO->adicionarCriterio(
              ['ProtocoloFormatado' , 'ProtocoloFormatadoPesquisa'],
              [InfraDTO::$OPER_IGUAL , InfraDTO::$OPER_IGUAL],
              [$_POST['numProcesso'] , $_POST['numProcesso']],
              [InfraDTO::$OPER_LOGICO_OR]
            );
          }

          $objMdCorExpedicaoSolicitadaRN = new MdCorExpedicaoSolicitadaRN();
          $arrObjMdCorExpedicaoSolicitadaDTO = $objMdCorExpedicaoSolicitadaRN->listar($objMdCorExpedicaoSolicitadaDTO);
          $item->setStrMidia('N');
          foreach($arrObjMdCorExpedicaoSolicitadaDTO as $itemObjMdCorExpedicaoSolicitadaDTO) {
              if($itemObjMdCorExpedicaoSolicitadaDTO->getStrFormaExpedicao() == MdCorExpedicaoFormatoRN::$TP_FORMATO_MIDIA){
                  $item->setStrMidia('S');
              }
          }
          if (count($arrObjMdCorExpedicaoSolicitadaDTO) == 0) {
            unset($ret[$key]);
            continue;
          }

          $item->setNumContagem(count($arrObjMdCorExpedicaoSolicitadaDTO));
          $item->setArrMdCorExpedicaoSolicitadaDTO($arrObjMdCorExpedicaoSolicitadaDTO);
        }

        //Auditoria

        return $ret;

      } catch (Exception $e) {
        throw new InfraException('Erro listando .', $e);
      }
    }

    private function getCodigoPlP( $arrParams ){
      $objMdCorExpedicaoSolicitadaRN  = new MdCorExpedicaoSolicitadaRN();
      $objMdCorExpedicaoSolicitadaDTO = new MdCorExpedicaoSolicitadaDTO();

      switch ( $arrParams['tipo'] ) {
        case 1: // rastreamento
          $objMdCorExpedicaoSolicitadaDTO->setStrCodigoRastreamento( $arrParams['valor'] );
          break;

        case 2: // documento principal
          $objMdCorExpedicaoSolicitadaDTO->setStrProtocoloFormatadoDocumento( $arrParams['valor'] );
          break;

        default:
          break;
      }
      $objMdCorExpedicaoSolicitadaDTO->retNumCodigoPlp();
      $objCodPlP = $objMdCorExpedicaoSolicitadaRN->consultar( $objMdCorExpedicaoSolicitadaDTO );
      return !is_null( $objCodPlP ) ? $objCodPlP->getNumCodigoPlp() : null;
    }

    protected function contarConectado(MdCorPlpDTO $objMdCorPlpDTO) {
      try {

        //Valida Permissao
        SessaoSEI::getInstance()->validarPermissao('md_cor_plp_listar');

        //Regras de Negocio
        //$objInfraException = new InfraException();

        //$objInfraException->lancarValidacoes();

        $objMdCorPlpBD = new MdCorPlpBD($this->getObjInfraIBanco());
        $ret = $objMdCorPlpBD->contar($objMdCorPlpDTO);

        //Auditoria

        return $ret;
      } catch (Exception $e) {
        throw new InfraException('Erro contando .', $e);
      }
    }
    /*
      protected function desativarControlado($arrObjMdCorPlpDTO){
        try {

          //Valida Permissao
          ::getInstance()->validarPermissao('md_cor_plp_desativar');

          //Regras de Negocio
          //$objInfraException = new InfraException();

          //$objInfraException->lancarValidacoes();

          $objMdCorPlpBD = new MdCorPlpBD($this->getObjInfraIBanco());
          for($i=0;$i<count($arrObjMdCorPlpDTO);$i++){
            $objMdCorPlpBD->desativar($arrObjMdCorPlpDTO[$i]);
          }

          //Auditoria

        }catch(Exception $e){
          throw new InfraException('Erro desativando .',$e);
        }
      }

      protected function reativarControlado($arrObjMdCorPlpDTO){
        try {

          //Valida Permissao
          ::getInstance()->validarPermissao('md_cor_plp_reativar');

          //Regras de Negocio
          //$objInfraException = new InfraException();

          //$objInfraException->lancarValidacoes();

          $objMdCorPlpBD = new MdCorPlpBD($this->getObjInfraIBanco());
          for($i=0;$i<count($arrObjMdCorPlpDTO);$i++){
            $objMdCorPlpBD->reativar($arrObjMdCorPlpDTO[$i]);
          }

          //Auditoria

        }catch(Exception $e){
          throw new InfraException('Erro reativando .',$e);
        }
      }

      protected function bloquearControlado(MdCorPlpDTO $objMdCorPlpDTO){
        try {

          //Valida Permissao
          ::getInstance()->validarPermissao('md_cor_plp_consultar');

          //Regras de Negocio
          //$objInfraException = new InfraException();

          //$objInfraException->lancarValidacoes();

          $objMdCorPlpBD = new MdCorPlpBD($this->getObjInfraIBanco());
          $ret = $objMdCorPlpBD->bloquear($objMdCorPlpDTO);

          //Auditoria

          return $ret;
        }catch(Exception $e){
          throw new InfraException('Erro bloqueando .',$e);
        }
      }

     */

    /**
     * Método responsavel pela solicitacao da etiqueta, geração da plp e fechamento da PLP das solicitações
     * de expedição pelos correios
     * @param $arrIdMdCorExpedicaoSolicitada
     * @return mixed
     */
    protected function gerarPlpWebServiceControlado($arrIdMdCorExpedicaoSolicitada) {
      try {

        //Valida Permissao
        SessaoSEI::getInstance()->validarPermissao('md_cor_plp_webservice_acessar');
        $strUrlAPIUsada = '';

        $objMdCorExpedicaoSolicitadaRN = new MdCorExpedicaoSolicitadaRN();

        $objMdCorExpedicaoSolicitadaDTO = new MdCorExpedicaoSolicitadaDTO();
        $objMdCorExpedicaoSolicitadaDTO->retNumIdMdCorServicoPostal();
        $objMdCorExpedicaoSolicitadaDTO->retDblIdMdCorContrato();
        $objMdCorExpedicaoSolicitadaDTO->retStrCartaoPostagem();
        $objMdCorExpedicaoSolicitadaDTO->retNumNumeroCnpj();
        $objMdCorExpedicaoSolicitadaDTO->retStrNumeroContratoCorreio();
        $objMdCorExpedicaoSolicitadaDTO->retNumIdMdCorContato();
        $objMdCorExpedicaoSolicitadaDTO->retDblIdUnidadeExpedidora();
        $objMdCorExpedicaoSolicitadaDTO->retDblIdContatoOrgao();
        $objMdCorExpedicaoSolicitadaDTO->retStrCodigoWsCorreioServico();
        $objMdCorExpedicaoSolicitadaDTO->retStrExpedicaoAvisoRecebimentoServico();
        $objMdCorExpedicaoSolicitadaDTO->retNumIdMdCorExpedicaoSolicitada();
        $objMdCorExpedicaoSolicitadaDTO->retStrBairroContratoOrgao();

        $objMdCorExpedicaoSolicitadaDTO->retStrNomeSerie();
        $objMdCorExpedicaoSolicitadaDTO->retStrNumeroDocumento();
        $objMdCorExpedicaoSolicitadaDTO->retStrProtocoloFormatado();
        $objMdCorExpedicaoSolicitadaDTO->retStrProtocoloFormatadoDocumento();

        //Dados Destinatario
        $objMdCorExpedicaoSolicitadaDTO->retStrNomeDestinatario();
        $objMdCorExpedicaoSolicitadaDTO->retStrEnderecoDestinatario();
        $objMdCorExpedicaoSolicitadaDTO->retStrBairroDestinatario();
        $objMdCorExpedicaoSolicitadaDTO->retStrCepDestinatario();
        $objMdCorExpedicaoSolicitadaDTO->retStrComplementoDestinatario();
        $objMdCorExpedicaoSolicitadaDTO->retStrNomeCidadeDestinatario();
        $objMdCorExpedicaoSolicitadaDTO->retStrSiglaUfDestinatario();

        $objMdCorExpedicaoSolicitadaDTO->setNumIdMdCorExpedicaoSolicitada($arrIdMdCorExpedicaoSolicitada, InfraDTO::$OPER_IN);
        $objMdCorExpedicaoSolicitadaDTO->setDistinct(true);

        $arrObjDTO = $objMdCorExpedicaoSolicitadaRN->listar($objMdCorExpedicaoSolicitadaDTO);

        $arrIdUnidade = [];
        foreach ( $arrObjDTO as $objDto ) {

              $objContDTO = new MdCorContatoDTO();
              $objContDTO->retStrCep();
              $objContDTO->setNumIdMdCorContato( $objDto->getNumIdMdCorContato() );

              $objContatoRN = new MdCorContatoRN();
              $objContDTO = $objContatoRN->consultar( $objContDTO );

              if( $objContDTO->getStrCep() != $objDto->getStrCepDestinatario() ) {
                  LogSEI::getInstance()->gravar("A solicitação de expedição de id ".$objDto->getNumIdMdCorExpedicaoSolicitada()." não foi inserida na PLP. O CEP enviado para a PLP está diferente do CEP cadastrado na tabela md_cor_contato. O CEP inserido na PLP é ".$objDto->getStrCepDestinatario(). " o CEP correto é o ".$objContDTO->getStrCep());
                  continue;
              }

              $idContatoOrgao = $objDto->getDblIdContatoOrgao();

              $arrDados[$objDto->getNumIdMdCorExpedicaoSolicitada()]['IdMdCorContrato']                    = $objDto->getDblIdMdCorContrato();
              $arrDados[$objDto->getNumIdMdCorExpedicaoSolicitada()]['coServicoPostagem']                  = trim($objDto->getStrCodigoWsCorreioServico());
              $arrDados[$objDto->getNumIdMdCorExpedicaoSolicitada()]['stExpedicaoAvisoRecebimentoServico'] = $objDto->getStrExpedicaoAvisoRecebimentoServico();
              $arrDados[$objDto->getNumIdMdCorExpedicaoSolicitada()]['noDestinatario']                     = $objDto->getStrNomeDestinatario();
              $arrDados[$objDto->getNumIdMdCorExpedicaoSolicitada()]['dsEnderecoDestinatario']             = $objDto->getStrEnderecoDestinatario();
              $arrDados[$objDto->getNumIdMdCorExpedicaoSolicitada()]['dsBairroDestinatario']               = $objDto->getStrBairroDestinatario();
              $arrDados[$objDto->getNumIdMdCorExpedicaoSolicitada()]['nuCepDestinatario']                  = $objDto->getStrCepDestinatario();
              $arrDados[$objDto->getNumIdMdCorExpedicaoSolicitada()]['dsComplementoDestinatario']          = $objDto->getStrComplementoDestinatario();
              $arrDados[$objDto->getNumIdMdCorExpedicaoSolicitada()]['noCidadeDestinatario']               = $objDto->getStrNomeCidadeDestinatario();
              $arrDados[$objDto->getNumIdMdCorExpedicaoSolicitada()]['sgUfDestinatario']                   = $objDto->getStrSiglaUfDestinatario();
              $arrDados[$objDto->getNumIdMdCorExpedicaoSolicitada()]['idMdCorExpedicaoSolicitada']         = $objDto->getNumIdMdCorExpedicaoSolicitada();
              $arrDados[$objDto->getNumIdMdCorExpedicaoSolicitada()]['idMdCorContato']                     = $objDto->getNumIdMdCorContato();

              $arrDados[$objDto->getNumIdMdCorExpedicaoSolicitada()]['NomeSerie']                          = $objDto->getStrNomeSerie();
              $arrDados[$objDto->getNumIdMdCorExpedicaoSolicitada()]['NumeroDocumento']                    = $objDto->getStrNumeroDocumento();
              $arrDados[$objDto->getNumIdMdCorExpedicaoSolicitada()]['ProtocoloFormatadoDocumento']        = $objDto->getStrProtocoloFormatadoDocumento();
              $arrDados[$objDto->getNumIdMdCorExpedicaoSolicitada()]['ProtocoloFormatado']                 = $objDto->getStrProtocoloFormatado();

              $arrDados[$objDto->getNumIdMdCorExpedicaoSolicitada()]['codigoObjeto']                       = null;
              $arrDados[$objDto->getNumIdMdCorExpedicaoSolicitada()]['idPrePostagem']                      = null;
              $arrDados[$objDto->getNumIdMdCorExpedicaoSolicitada()]['idUnidadeExpedidora'] = $arrIdUnidade[] = $objDto->getDblIdUnidadeExpedidora();

              $numDoc = $objDto->isSetStrNumeroDocumento() ? " " . $objDto->getStrNumeroDocumento() : "";
              $tipo = explode(" ", $objDto->getStrNomeSerie())[0] . $numDoc;
              $arrDados[$objDto->getNumIdMdCorExpedicaoSolicitada()]['infoAnexos'] = $tipo . " (" . $objDto->getStrProtocoloFormatadoDocumento() . ")";
        }

        $objMDCorUnidadeExpRN = new MdCorUnidadeExpRN();
        $objMDCorUnidadeExpRN->validaCamposEnderecoUnidadePLP($arrIdUnidade);

        // Array parametro criado para montar a base de informacoes a ser passado para os Correios
        $arrParametros = [
          'idContatoOrgao'      => $idContatoOrgao,
          'arrDadosSolicitacao' => $arrDados
        ];

        // ['idSolicExp' => idMdCorSolicitacaoExpedicao , 'arrJson' => estrutura do Json para a API ]
        $arrParamJson = $this->montarDadosJsonPostagem($arrParametros);

        $objMdCorAdmIntegracaoRN = new MdCorAdmIntegracaoRN();
        $arrCodigoRastreio = [];

        foreach ( $arrParamJson as $arrItens ) {
          // retorna dados da integração GERAR PRE POSTAGEM
          $objMdCorIntegSolicPPN = $objMdCorAdmIntegracaoRN->buscaIntegracaoPorFuncionalidade(MdCorAdmIntegracaoRN::$GERAR_PRE_POSTAGEM, $arrItens['arrJson']['idMdCorContrato']);

          if ( is_null( $objMdCorIntegSolicPPN ) ) throw new InfraException('Mapeamento de Integração '. MdCorAdmIntegracaoRN::$STR_PRE_POSTAGEM .' não existe ou está inativo.');

          $arrParametroRest = [
              'endpoint' => $objMdCorIntegSolicPPN->getStrUrlOperacao(),
              'token'    => $objMdCorIntegSolicPPN->getStrToken(),
              'expiraEm' => $objMdCorIntegSolicPPN->getDthDataExpiraToken(),
          ];
          
          $ret = $objMdCorAdmIntegracaoRN->verificaTokenExpirado($arrParametroRest, $objMdCorIntegSolicPPN, $arrItens['arrJson']['idMdCorContrato']);
          
          if ( is_array( $ret ) && isset( $ret['suc'] ) && $ret['suc'] === false ) {
            $strUrlAPIUsada = $ret['url'] ?? $arrParametroRest['endpoint'];
            throw new InfraException("Falha na Integração: " . MdCorAdmIntegracaoRN::$STR_PRE_POSTAGEM . "." . "\n" . $ret['msg'] );
          }
          
          // instancia class ApiRest com os dados necessarios para uso da API que gera Pre Postagem
          $objMdCorApiPPN = new MdCorApiRestRN( $arrParametroRest );
          
          $rs = $objMdCorApiPPN->gerarPPN( $arrItens['arrJson'] );

            if ( is_array( $rs ) and $rs['suc'] === false ) {
                $strUrlAPIUsada = $objMdCorApiPPN->getEndPoint();
                throw new InfraException("A solicitação de expedição de id {$arrItens['idSolicExp']} não foi inserida na Pré-Postagem.\n\n {$rs['msg']}");
            }

            $arrCodigoRastreio[$arrItens['idSolicExp']] = [
              'codigoObjeto'  => $rs['codigoObjeto'],
              'idPrePostagem' => $rs['id']
            ];
        }

        $arrRetorno = [
          'arrRastreio' => $arrCodigoRastreio
        ];

        return $arrRetorno;

      } catch ( InfraException $e ) {
          $operacao = '';
          $msgDefault = "Não foi possível Gerar PLP devido à incompatibilidade das informações da Solicitação de Expedição com a nova Integração com os Correios. 
          É necessário Devolver a Solicitação de Expedição e requerer à Unidade Solicitante que revise o Serviço Postal e demais informações da Solicitação.";

          if ( ! empty( $strUrlAPIUsada) ) $operacao = "Operação: $strUrlAPIUsada \n\n";

          $msgFinal = "$msgDefault \n\n" . $operacao . "Retorno: " . $e->getMessage();

          $e->lancarValidacao( $msgFinal );

          LogSEI::getInstance()->gravar( $msgFinal );
      }
    }

    protected function alterarDadosPlpSolicitacaoControlado($arrParametro) {
      try {
        $idPlp = $this->geradorCodigoPLP();
        $idPlp += 1;

        $mdCorPlpDto = new MdCorPlpDTO();

        SessaoSEI::getInstance()->validarAuditarPermissao('md_cor_plp_cadastrar', __METHOD__, $mdCorPlpDto);

        $mdCorPlpDto->setDblCodigoPlp($idPlp);
        $mdCorPlpDto->setStrStaPlp(self::$STA_GERADA);

        $mdCorPlpDto->setNumIdUnidadeGeradora(SessaoSEI::getInstance()->getNumIdUnidadeAtual());
        $mdCorPlpDto->setDthDataCadastro(InfraData::getStrDataHoraAtual());

        $ret = $this->cadastrar($mdCorPlpDto);

        foreach ($arrParametro['arrRastreio'] as $k => $dadosRastreio) {
          $idMdCorExpedicaoSolicitada = $k;
          $mdCorExpedicaoSolicitadaRN = new MdCorExpedicaoSolicitadaRN();
          $mdCorExpedicaoSolicitadaDTO = new MdCorExpedicaoSolicitadaDTO();
          $mdCorExpedicaoSolicitadaDTO->retTodos();
          $mdCorExpedicaoSolicitadaDTO->retDblIdMdCorContrato();
          $mdCorExpedicaoSolicitadaDTO->setNumIdMdCorExpedicaoSolicitada($idMdCorExpedicaoSolicitada);
          $mdCorExpedicaoSolicitadaDTO = $mdCorExpedicaoSolicitadaRN->consultar($mdCorExpedicaoSolicitadaDTO);

          $mdCorExpedicaoSolicitadaDTO->setStrCodigoRastreamento($dadosRastreio['codigoObjeto']);
	      $mdCorExpedicaoSolicitadaDTO->setStrIdPrePostagem($dadosRastreio['idPrePostagem']);
          $mdCorExpedicaoSolicitadaDTO->setNumIdMdCorPlp($ret->getNumIdMdPlp());

          if (empty($mdCorExpedicaoSolicitadaDTO->getNumIdMdCorObjeto())) {
            $objMdCorObjetoDTO = new MdCorObjetoDTO();
            $objMdCorObjetoDTO->retNumIdMdCorObjeto();
            $objMdCorObjetoDTO->retStrSinObjetoPadrao();
            $objMdCorObjetoDTO->setStrSinObjetoPadrao('S');
            $objMdCorObjetoDTO->setNumIdMdCorContrato($mdCorExpedicaoSolicitadaDTO->getDblIdMdCorContrato());

            $objMdCorObjetoRN = new MdCorObjetoRN();
            $objMdCorObjetoDTO = $objMdCorObjetoRN->consultar($objMdCorObjetoDTO);
            $mdCorExpedicaoSolicitadaDTO->setNumIdMdCorObjeto($objMdCorObjetoDTO->getNumIdMdCorObjeto());
          }

          $mdCorExpedicaoSolicitadaRN->alterar($mdCorExpedicaoSolicitadaDTO);
        }
        return $ret->getNumIdMdPlp();

      } catch (Exception $e) {
        throw new InfraException('Gerar PLP.', $e);
      }
    }

    private function retornarArrEnderecoComplento( $endereco, $complemento ){

        $arr = [];
        $enderecoCompleto   = trim($endereco);
        $arr['endereco']    = $endereco = trim($endereco);
        $arr['complemento'] = $complemento = trim($complemento);

        $qtdEndereco = strlen($endereco);
        $qtdComplemento = strlen($complemento);

        if( $qtdEndereco > 50 ){

            $posicaoRemetenteEndereco = strrpos(substr($endereco, 0, 50 ), ' ');
            $arr['endereco'] = substr($endereco, 0, $posicaoRemetenteEndereco );

            $remetenteParteEndereco = substr(trim($enderecoCompleto), ($posicaoRemetenteEndereco-$qtdEndereco));

            $complemento = trim("$remetenteParteEndereco $complemento");
            $qtdComplemento = strlen($complemento);
        }

        if( $qtdComplemento > 30 ){
            $posicaoComplemento = strrpos(substr($complemento, 0, 30 ), ' ');
            $arr['complemento'] = substr($complemento, 0, $posicaoComplemento );
        }

        return $arr;
    }

    protected function salvarAndamentoProcessoConectado($arrParametro) {
      $mdCorExpedicaoSolicitadaRN = new MdCorExpedicaoSolicitadaRN();
      $mdCorExpedicaoSolicitadaDTO = new MdCorExpedicaoSolicitadaDTO();
      $mdCorExpedicaoSolicitadaDTO->retNumIdMdCorExpedicaoSolicitada();
      $mdCorExpedicaoSolicitadaDTO->retDblIdProtocolo();
      $mdCorExpedicaoSolicitadaDTO->retDblIdDocumentoPrincipal();
      $mdCorExpedicaoSolicitadaDTO->retStrNomeSerie();
      $mdCorExpedicaoSolicitadaDTO->retStrProtocoloFormatadoDocumento();
      $mdCorExpedicaoSolicitadaDTO->retStrNumeroDocumento();
      $mdCorExpedicaoSolicitadaDTO->retStrCodigoRastreamento();
      $mdCorExpedicaoSolicitadaDTO->retNumIdUnidade();
      $mdCorExpedicaoSolicitadaDTO->setNumIdMdCorPlp($arrParametro['idPlp']);

      $arrObjMdCorExpedicacaoSolicitada = $mdCorExpedicaoSolicitadaRN->listar($mdCorExpedicaoSolicitadaDTO);

      $tarefaRN = new TarefaRN();
      $tarefaDTO = new TarefaDTO();
      $tarefaDTO->retNumIdTarefa();
      $tarefaDTO->setStrIdTarefaModulo('MD_COR_EXPEDIR_PLP');

      $objTarefa = $tarefaRN->consultar($tarefaDTO);

      $dtExpedicao = InfraData::getStrDataAtual();

      foreach ($arrObjMdCorExpedicacaoSolicitada as $objMdCorExpedicacaoSolicitada) {
        $objAtividadeDTO = new AtividadeDTO();

        $objAtividadeDTO->setNumIdTarefa($objTarefa->getNumIdTarefa());
        $objAtividadeDTO->setDthAbertura($dtExpedicao);
        $objAtividadeDTO->setStrSinInicial('S');
        $objAtividadeDTO->setNumIdUsuarioOrigem($arrParametro['idUsuario']);
        $objAtividadeDTO->setDblIdProtocolo($objMdCorExpedicacaoSolicitada->getDblIdProtocolo());
        $objAtividadeDTO->setNumIdUnidade($objMdCorExpedicacaoSolicitada->getNumIdUnidade());
        $objAtividadeDTO->setNumTipoVisualizacao(AtividadeRN::$TV_VISUALIZADO);

        $nomeTipoDocumento = $objMdCorExpedicacaoSolicitada->getStrNomeSerie();
        $numeroProtocoloFormatado = $objMdCorExpedicacaoSolicitada->getStrProtocoloFormatadoDocumento();
        $numeroDoc = $objMdCorExpedicacaoSolicitada->getStrNumeroDocumento();
        $docPrincipalFormatado = $nomeTipoDocumento . " " . $numeroDoc . " (" . $numeroProtocoloFormatado . ")";

        $arrObjAtributoAndamentoAPI = [];
        $arrObjAtributoAndamentoAPI[] = $this->retornaObjAtributoAndamentoAPI('DOCUMENTO', $docPrincipalFormatado, $objMdCorExpedicacaoSolicitada->getDblIdDocumentoPrincipal());
        $arrObjAtributoAndamentoAPI[] = $this->retornaObjAtributoAndamentoAPI('DATA_EXPEDICAO_CORREIOS', $dtExpedicao);
        $arrObjAtributoAndamentoAPI[] = $this->retornaObjAtributoAndamentoAPI('CODIGO_RASTREAMENTO_OBJETO_CORREIOS', $objMdCorExpedicacaoSolicitada->getStrCodigoRastreamento());

        $objEntradaLancarAndamentoAPI = new EntradaLancarAndamentoAPI();
        $objEntradaLancarAndamentoAPI->setIdProcedimento($objMdCorExpedicacaoSolicitada->getDblIdProtocolo());
        $objEntradaLancarAndamentoAPI->setIdTarefa(TarefaRN::$TI_LIBERACAO_ACESSO_EXTERNO);
        $objEntradaLancarAndamentoAPI->setAtributos($arrObjAtributoAndamentoAPI);

        $arrObjAtributoAndamentoDTO = [];
        if ($objEntradaLancarAndamentoAPI->getAtributos() != null) {
          foreach ($objEntradaLancarAndamentoAPI->getAtributos() as $objAtributoAndamentoAPI) {
            $objAtributoAndamentoDTO = new AtributoAndamentoDTO();
            $objAtributoAndamentoDTO->setStrNome($objAtributoAndamentoAPI->getNome());
            $objAtributoAndamentoDTO->setStrValor($objAtributoAndamentoAPI->getValor());
            $objAtributoAndamentoDTO->setStrIdOrigem($objAtributoAndamentoAPI->getIdOrigem());
            $arrObjAtributoAndamentoDTO[] = $objAtributoAndamentoDTO;
          }
        }

        $objAtividadeDTO->setArrObjAtributoAndamentoDTO($arrObjAtributoAndamentoDTO);

        $objAtividadeRN = new AtividadeRN();
        $objAtividadeRN->gerarInternaRN0727($objAtividadeDTO);

        $objMdCorExpedicacaoSolicitada->setDthDataExpedicao($dtExpedicao);
        $mdCorExpedicaoSolicitadaRN->alterar($objMdCorExpedicacaoSolicitada);
      }


    }

    private function retornaObjAtributoAndamentoAPI($nome, $valor, $id = null) {

      $objAtributoAndamentoAPI = new AtributoAndamentoAPI();
      $objAtributoAndamentoAPI->setNome($nome);

      $objAtributoAndamentoAPI->setValor($valor);
      $objAtributoAndamentoAPI->setIdOrigem($id); //ID do prédio, pode ser null

      return $objAtributoAndamentoAPI;
    }

    private function montarDadosJsonPostagem( $arrParametros ){
      $objContatoDTO = new ContatoDTO();
      $objContatoDTO->retNumIdContato();
      $objContatoDTO->retStrNome();
      $objContatoDTO->retStrEndereco();
      $objContatoDTO->retStrComplemento();
      $objContatoDTO->retStrBairro();
      $objContatoDTO->retStrCep();
      $objContatoDTO->retStrNomeCidade();
      $objContatoDTO->retStrSiglaUf();
      $objContatoDTO->retStrCnpj();
      $objContatoDTO->retStrSigla();
      $objContatoDTO->retStrEmail();
      $objContatoDTO->retStrTelefoneComercial();
      $objContatoDTO->retStrSinEnderecoAssociado();
      $objContatoDTO->retStrEnderecoContatoAssociado();
      $objContatoDTO->retStrComplementoContatoAssociado();
      $objContatoDTO->retStrBairroContatoAssociado();
      $objContatoDTO->retStrCepContatoAssociado();
      $objContatoDTO->retStrNomeCidadeContatoAssociado();
      $objContatoDTO->retStrSiglaUfContatoAssociado();
      $objContatoDTO->setNumIdContato($arrParametros['idContatoOrgao']);

      $objContatoRN = new ContatoRN();
      $objContatoDTO = $objContatoRN->consultarRN0324( $objContatoDTO );
      return $this->montaEstruturaJsonPostagem( $objContatoDTO, $arrParametros );
    }

    private function montaEstruturaJsonPostagem( $objContatoDTO,$arrParametros ){

    	$arrRetorno = [];

	    $arrContato = $this->retornarArrEnderecoComplento($objContatoDTO->getStrEndereco(), $objContatoDTO->getStrComplemento());

	    $arrRemetente = [
		    "nome"        => substr(InfraString::excluirAcentos($objContatoDTO->getStrNome()),0,50),
		    "dddTelefone" => "",
		    "telefone"    => "",
		    "dddCelular"  => "",
		    "celular"     => "",
		    "email"       => "",
		    "cpfCnpj"     => InfraUtil::retirarFormatacao(InfraUtil::formatarCnpj($objContatoDTO->getStrCnpj())),
		    "obs"         => "",
		    "endereco"    => [
			    "cep"         => InfraUtil::retirarFormatacao($objContatoDTO->getStrCep()),
			    "logradouro"  => InfraString::excluirAcentos( str_replace( ['º','ª','&'] , ['','a','e'] , $arrContato['endereco'] ) ),
			    "numero"      => "N/A",
			    "complemento" => InfraString::excluirAcentos( str_replace( ['º','ª','&'] , ['','a','e'] , $arrContato['complemento'] ) ),
			    "bairro"      => substr(InfraString::excluirAcentos($objContatoDTO->getStrBairro()), 0, 30),
			    "cidade"      => substr(InfraString::excluirAcentos($objContatoDTO->getStrNomeCidade()), 0, 30),
			    "uf"          => $objContatoDTO->getStrSiglaUf()
		    ]
	    ];

	    foreach ( $arrParametros['arrDadosSolicitacao'] as $idSolic => $arrItens ) {
            // configura dados de destinatario
            if ( $objContatoDTO->getStrSinEnderecoAssociado() == 'S' ) {
                $destinatarioEndereco    = $objContatoDTO->getStrEnderecoContatoAssociado();
                $destinatarioComplemento = $objContatoDTO->getStrComplementoContatoAssociado() ?? "";
                $destinatarioCEP         = $objContatoDTO->getStrCepContatoAssociado();
                $destinatarioBairro      = $objContatoDTO->getStrBairroContatoAssociado();
                $destinatarioCidade      = $objContatoDTO->getStrNomeCidadeContatoAssociado();
                $destinatarioUF          = $objContatoDTO->getStrSiglaUfContatoAssociado();
            } else {
                $destinatarioEndereco    = $arrItens['dsEnderecoDestinatario'];
                $destinatarioComplemento = $arrItens['dsComplementoDestinatario'] ?? "";
                $destinatarioCEP         = $arrItens['nuCepDestinatario'];
                $destinatarioBairro      = $arrItens['dsBairroDestinatario'];
                $destinatarioCidade      = $arrItens['noCidadeDestinatario'];
                $destinatarioUF          = $arrItens['sgUfDestinatario'];
            }

            $arrEnderecoDest = $this->retornarArrEnderecoComplento( $destinatarioEndereco , $destinatarioComplemento );

	    	$arrDest = [
			    "nome"        => substr(InfraString::excluirAcentos($arrItens['noDestinatario']),0,50),
			    "dddTelefone" => "",
			    "telefone"    => "",
			    "dddCelular"  => "",
			    "celular"     => "",
			    "email"       => "",
			    "cpfCnpj"     => "",
			    "obs"         => "",
			    "endereco"    => [
				    "cep"         => InfraUtil::retirarFormatacao($destinatarioCEP),
				    "logradouro"  => InfraString::excluirAcentos( str_replace( ['º','ª','&'],['','a','e'],$arrEnderecoDest['endereco'] ) ),
				    "numero"      => "N/A",
				    "complemento" => InfraString::excluirAcentos( str_replace( ['º','ª','&'],['','a','e'],$arrEnderecoDest['complemento'] ) ),
				    "bairro"      => substr(InfraString::excluirAcentos($destinatarioBairro), 0, 30),
				    "cidade"      => substr(InfraString::excluirAcentos($destinatarioCidade), 0, 30),
				    "uf"          => $destinatarioUF,
			    ]
		    ];

            // configura servico adicional
            $arrServAdd = [
                ['codigoServicoAdicional' => '025', 'valorDeclarado' => '0'],
            ];

            if ( $arrItens['stExpedicaoAvisoRecebimentoServico'] == 'S' ) array_push( $arrServAdd , ['codigoServicoAdicional' => '001', 'valorDeclarado' => '0'] );

		    $arrJson = [
                "idCorreios"              => "",
                "idMdCorContrato"         => $arrItens['IdMdCorContrato'], 
                "remetente"               => $arrRemetente,
                "destinatario"            => $arrDest,
                "codigoServico"           => $arrItens['coServicoPostagem'],
                "listaServicoAdicional"   => $arrServAdd,
                "pesoInformado"                => "10",
                "codigoFormatoObjetoInformado" => "1",
                "alturaInformada"              => "0",
                "larguraInformada"             => "0",
                "comprimentoInformado"         => "0",
                "diametroInformado"            => "0",
                "cienteObjetoNaoProibido"      => "1",
                "solicitarColeta"              => "N",
                "observacao"                   => $arrItens['infoAnexos'],
                "modalidadePagamento"          => "2",
                "itensDeclaracaoConteudo"      => [[ 'conteudo'   => 'Documentos', 'quantidade' => 1, 'valor' => 0.1]]
	        ];

            //CARTA RG AR CONV: Correspondência Registrada com AR
		    if ( $arrItens['coServicoPostagem'] == 80810 ) $arrJson['dataPrevistaPostagem'] = InfraData::getStrDataAtual();

		    $arrRetorno[] = ['idSolicExp' => $idSolic , 'arrJson' => $arrJson];
	    }

	    return $arrRetorno;
    }

	protected function geradorCodigoPLPConectado(){
    	$objMdCorPlpDTO = new MdCorPlpDTO();
        $objMdCorPlpDTO->setOrd('CodigoPlp',InfraDTO::$TIPO_ORDENACAO_DESC);
        $objMdCorPlpDTO->setNumMaxRegistrosRetorno(1);
        $objMdCorPlpDTO->retDblCodigoPlp();
        $objCodigoPLP = ( new MdCorPlpRN() )->consultar( $objMdCorPlpDTO );
		return $objCodigoPLP ? $objCodigoPLP->getDblCodigoPlp() + 1 : 0;
	}

	protected function cancelarPlpConectado($arrDados){
    // retorna dados da integração GERAR PRE POSTAGEM
      $objMdCorAdmIntegracaoRN = new MdCorAdmIntegracaoRN();

      $objMdCorIntegCancelarPPN = $objMdCorAdmIntegracaoRN->buscaIntegracaoPorFuncionalidade(MdCorAdmIntegracaoRN::$CANCELAR_PRE_POSTAGEM, $arrDados['idContrato']);

      if ( empty($objMdCorIntegCancelarPPN) || ( is_array( $objMdCorIntegCancelarPPN ) && isset($objMdCorIntegCancelarPPN['suc'] ) && $objMdCorIntegCancelarPPN['suc'] === false ) )
        return ['suc' => false , 'msg' => 'Mapeamento de Integração '. MdCorAdmIntegracaoRN::$STR_CANCELAR_PRE_POSTAGEM .' não existe ou está inativo.'];
        
        $arrParametroRest = [
            'endpoint' => $objMdCorIntegCancelarPPN->getStrUrlOperacao(),
            'token'    => $objMdCorIntegCancelarPPN->getStrToken(),
            'expiraEm' => $objMdCorIntegCancelarPPN->getDthDataExpiraToken(),
        ];

        $ret = $objMdCorAdmIntegracaoRN->verificaTokenExpirado($arrParametroRest, $objMdCorIntegCancelarPPN, $arrDados['idContrato']);

        if ( is_array( $ret ) ) return ['suc' => false , 'msg' => $ret['msg']];

        // instancia class ApiRest com os dados necessarios para uso da API que gera Pre Postagem
        $objMdCorApiCancelarPPN = new MdCorApiRestRN( $arrParametroRest );

        $arrCodRastreamento = $this->getListaCodigoRastreamento($arrDados['idPlp']);

        $rs = $objMdCorApiCancelarPPN->cancelarPPN($arrCodRastreamento);

        if ( is_array($rs) ){
            $msgErroCompl = "Operação: {$objMdCorApiCancelarPPN->getEndPoint()}.#Retorno: {$rs['msg']}";
            return ['suc' => false , 'msg' => $msgErroCompl];
        }

        $rs = $this->atualizarSolicitacoesAposCancelamentoPlp(['idPlp' => $_POST['idPlp']]);

        if ( is_array($rs) ) {
            return ['suc' => false , 'msg' => $rs['msg']];
        }

        return true;
    }

    private function getListaCodigoRastreamento($idPlp){
        $objMdCorExpSolicitacaoDTO = new MdCorExpedicaoSolicitadaDTO();
        $objMdCorExpSolicitacaoDTO->setNumIdMdCorPlp($idPlp);
        $objMdCorExpSolicitacaoDTO->retStrCodigoRastreamento();

        $arrObjExpSolicDTO = ( new MdCorExpedicaoSolicitadaRN() )->listar($objMdCorExpSolicitacaoDTO);
        return InfraArray::converterArrInfraDTO($arrObjExpSolicDTO,'CodigoRastreamento');
    }

    protected function atualizarSolicitacoesAposCancelamentoPlpConectado($arrDados){
        try {
            // atualizar a PLP para Cancelada
            $objMdCorPlpDTO = new MdCorPlpDTO();
            $objMdCorPlpDTO->setNumIdMdPlp($arrDados['idPlp']);
            $objMdCorPlpDTO->setStrStaPlp(self::$STA_CANCELADA);
            $rs = ( new MdCorPlpRN() )->alterar( $objMdCorPlpDTO );

            // atualizar as solicitacoes de expedicao
            $objMdCorExpSolicitacaoDTO = new MdCorExpedicaoSolicitadaDTO();
            $objMdCorExpSolicitacaoDTO->setNumIdMdCorPlp($arrDados['idPlp']);
            $objMdCorExpSolicitacaoDTO->retNumIdMdCorExpedicaoSolicitada();
            $objMdCorExpSolicitacaoDTO->retNumIdMdCorPlp();
            $objMdCorExpSolicitacaoDTO->retStrCodigoRastreamento();
            $objMdCorExpSolicitacaoDTO->retStrIdPrePostagem();

            $objMdCorExpSolicitadaRN = new MdCorExpedicaoSolicitadaRN();

            $arrObjMdCorExpSolicitacaoDTO = $objMdCorExpSolicitadaRN->listar($objMdCorExpSolicitacaoDTO);

            foreach ( $arrObjMdCorExpSolicitacaoDTO as $objExpSolicDTO ) {
                $objExpSolicDTO->setNumIdMdCorPlp(null);
                $objExpSolicDTO->setStrCodigoRastreamento(null);
                $objExpSolicDTO->setStrIdPrePostagem(null);

                $objMdCorExpSolicitadaRN->alterar( $objExpSolicDTO );
            }

        } catch(InfraException $e ) {
            return ['suc' => false , 'msg' => $e->getMessage()];
        }
    }
  }

