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
    public static $STA_RETORNO_AR_PENDENTE = 'R';
    public static $STA_FINALIZADA = 'F';

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
          $objMdCorExpedicaoSolicitadaDTO->retStrNomeServicoPostal();
          $objMdCorExpedicaoSolicitadaDTO->retStrDescricaoServicoPostal();
          $objMdCorExpedicaoSolicitadaDTO->retStrFormaExpedicao();
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

        $objMdCorExpedicaoSolicitadaRN = new MdCorExpedicaoSolicitadaRN();

        $objMdCorExpedicaoSolicitadaDTO = new MdCorExpedicaoSolicitadaDTO();
        $objMdCorExpedicaoSolicitadaDTO->retNumIdMdCorServicoPostal();
        $objMdCorExpedicaoSolicitadaDTO->retDblIdMdCorContrato();
        $objMdCorExpedicaoSolicitadaDTO->retStrUrlWebService();
        $objMdCorExpedicaoSolicitadaDTO->retStrUsuario();
        $objMdCorExpedicaoSolicitadaDTO->retStrCartaoPostagem();
        $objMdCorExpedicaoSolicitadaDTO->retStrSenha();
        $objMdCorExpedicaoSolicitadaDTO->retNumNumeroCnpj();
        $objMdCorExpedicaoSolicitadaDTO->retStrNumeroContratoCorreio();
        $objMdCorExpedicaoSolicitadaDTO->retStrNumeroCodigoAdministrativo();
        $objMdCorExpedicaoSolicitadaDTO->retNumIdWsCorreios();
        $objMdCorExpedicaoSolicitadaDTO->retNumIdMdCorContato();
        $objMdCorExpedicaoSolicitadaDTO->retDblIdUnidadeExpedidora();
        $objMdCorExpedicaoSolicitadaDTO->retDblIdContatoOrgao();
        $objMdCorExpedicaoSolicitadaDTO->retStrCodigoWsCorreioServico();
        $objMdCorExpedicaoSolicitadaDTO->retStrExpedicaoAvisoRecebimentoServico();
        $objMdCorExpedicaoSolicitadaDTO->retNumIdMdCorExpedicaoSolicitada();
        $objMdCorExpedicaoSolicitadaDTO->retStrBairroContratoOrgao();
        $objMdCorExpedicaoSolicitadaDTO->retStrCodigoDiretoria();
        $objMdCorExpedicaoSolicitadaDTO->setDistinct(true);

        //Dados Destinatario
        $objMdCorExpedicaoSolicitadaDTO->retStrNomeDestinatario();
        $objMdCorExpedicaoSolicitadaDTO->retStrEnderecoDestinatario();
        $objMdCorExpedicaoSolicitadaDTO->retStrBairroDestinatario();
        $objMdCorExpedicaoSolicitadaDTO->retStrCepDestinatario();
        $objMdCorExpedicaoSolicitadaDTO->retStrComplementoDestinatario();
        $objMdCorExpedicaoSolicitadaDTO->retStrNomeCidadeDestinatario();
        $objMdCorExpedicaoSolicitadaDTO->retStrSiglaUfDestinatario();

        $objMdCorExpedicaoSolicitadaDTO->setNumIdMdCorExpedicaoSolicitada($arrIdMdCorExpedicaoSolicitada, InfraDTO::$OPER_IN);

        $arrObjDTO = $objMdCorExpedicaoSolicitadaRN->listar($objMdCorExpedicaoSolicitadaDTO);


        $arrQtdEtiquetas = [];
        $arrServico = [];
        $arrIdUnidade = [];
        foreach ($arrObjDTO as $objDto) {
          $arrQtdEtiquetas[$objDto->getNumIdWsCorreios()] += 1;
          $arrServico[$objDto->getNumIdWsCorreios()][] = $objDto->getNumIdMdCorExpedicaoSolicitada();
          $wsdl = $objDto->getStrUrlWebService();
          $cnpj = str_pad($objDto->getNumNumeroCnpj(),  14, '0',STR_PAD_LEFT);
          $usuario = $objDto->getStrUsuario();
          $senha = $objDto->getStrSenha();
          $cartaoPostagem = $objDto->getStrCartaoPostagem();
          $nuContratoCorreio = $objDto->getStrNumeroContratoCorreio();
          $nuCodigoAdministrativo = $objDto->getStrNumeroCodigoAdministrativo();
          $nuNumeroDiretoria = $objDto->getStrCodigoDiretoria();
          $idContatoOrgao = $objDto->getDblIdContatoOrgao();

          $arrDados[$objDto->getNumIdMdCorExpedicaoSolicitada()]['coServicoPostagem'] = $objDto->getStrCodigoWsCorreioServico();
          $arrDados[$objDto->getNumIdMdCorExpedicaoSolicitada()]['stExpedicaoAvisoRecebimentoServico'] = $objDto->getStrExpedicaoAvisoRecebimentoServico();
          $arrDados[$objDto->getNumIdMdCorExpedicaoSolicitada()]['noDestinatario'] = $objDto->getStrNomeDestinatario();
          $arrDados[$objDto->getNumIdMdCorExpedicaoSolicitada()]['dsEnderecoDestinatario'] = $objDto->getStrEnderecoDestinatario();
          $arrDados[$objDto->getNumIdMdCorExpedicaoSolicitada()]['dsBairroDestinatario'] = $objDto->getStrBairroDestinatario();
          $arrDados[$objDto->getNumIdMdCorExpedicaoSolicitada()]['nuCepDestinatario'] = $objDto->getStrCepDestinatario();
          $arrDados[$objDto->getNumIdMdCorExpedicaoSolicitada()]['dsComplementoDestinatario'] = $objDto->getStrComplementoDestinatario();
          $arrDados[$objDto->getNumIdMdCorExpedicaoSolicitada()]['noCidadeDestinatario'] = $objDto->getStrNomeCidadeDestinatario();
          $arrDados[$objDto->getNumIdMdCorExpedicaoSolicitada()]['sgUfDestinatario'] = $objDto->getStrSiglaUfDestinatario();
          $arrDados[$objDto->getNumIdMdCorExpedicaoSolicitada()]['idMdCorExpedicaoSolicitada'] = $objDto->getNumIdMdCorExpedicaoSolicitada();
          $arrDados[$objDto->getNumIdMdCorExpedicaoSolicitada()]['idMdCorContato'] = $objDto->getNumIdMdCorContato();
          $arrDados[$objDto->getNumIdMdCorExpedicaoSolicitada()]['idUnidadeExpedidora'] = $arrIdUnidade[] = $objDto->getDblIdUnidadeExpedidora();

        }

        $objMDCorUnidadeExpRN = new MdCorUnidadeExpRN();
        $objMDCorUnidadeExpRN->validaCamposEnderecoUnidade($arrIdUnidade);

        $cliente = MdCorClientWsRN::gerarCliente($wsdl);
        $arrCodigoRastreio = [];
        $arrCodigoRastreioTratado = [];
        

        foreach ($arrQtdEtiquetas as $idServico => $qtdEtiqueta) {
            
          $codigoRastreio = $cliente->solicitaEtiquetas(
            ['tipoDestinatario' => 'C'
              , 'identificador' => $cnpj
              , 'idServico' => $idServico
              , 'qtdEtiquetas' => $qtdEtiqueta
              , 'usuario' => $usuario
              , 'senha' => $senha
            ]
          )->return;
        
          $arrDadosRastreioWebService = explode(',', $codigoRastreio);

          $codRangeInicial = substr(current($arrDadosRastreioWebService), 2, 8);
          $codRangeFinal = substr(end($arrDadosRastreioWebService), 2, 8);
          $sgCodigoRastreio = substr(current($arrDadosRastreioWebService), 0, 2);
          $sgPais = substr(current($arrDadosRastreioWebService), 10);

          for ($inicio = $codRangeInicial; $inicio <= $codRangeFinal; $inicio++) {
            $arrCodigoRastreio[$idServico][] = $sgCodigoRastreio . str_pad($inicio, 8, '0', STR_PAD_LEFT) . $sgPais;
          }

          $arrDv[$idServico] = $cliente->geraDigitoVerificadorEtiquetas(
            ['etiquetas' => $arrCodigoRastreio[$idServico]
              , 'usuario' => $usuario
              , 'senha' => $senha
            ]
          )->return;


          $contador = 0;
          $cCodigoRastreiamento = 0;
          foreach ($arrDv as $idServico => $dadosDv) {
            $dadosDv = is_array($dadosDv) ? $dadosDv : [$dadosDv];
            foreach ($dadosDv as $chave => $dv) {
              $arrCodigoRastreioTratado[$cCodigoRastreiamento]['codigoRastreio'] = substr($arrCodigoRastreio[$idServico][$chave], 0, 10)
                . $dv
                . substr($arrCodigoRastreio[$idServico][$chave], 11);

              $arrCodigoRastreioFinal[$arrServico[$idServico][$chave]] = $arrCodigoRastreioTratado[$cCodigoRastreiamento]['codigoRastreio'];

              $arrEtiquetaSemDigito[] =  substr($arrCodigoRastreio[$idServico][$chave], 0, 10)
                . substr($arrCodigoRastreio[$idServico][$chave], 11);
              $IdMdCorExpedicaoSolicitada = $arrIdMdCorExpedicaoSolicitada[$contador];

                $objContDTO = new MdCorContatoDTO();
                $objContDTO->retStrCep();
                $objContDTO->setNumIdMdCorContato($arrDados[$IdMdCorExpedicaoSolicitada]['idMdCorContato']);

                $objContatoRN = new MdCorContatoRN();
                $objContDTO = $objContatoRN->consultar($objContDTO);

              $arrCodigoRastreioTratado[$cCodigoRastreiamento]['IdMdCorExpedicaoSolicitada'] = $IdMdCorExpedicaoSolicitada;
              $arrCodigoRastreioTratado[$cCodigoRastreiamento]['coServicoPostagem'] = trim($arrDados[$IdMdCorExpedicaoSolicitada]['coServicoPostagem']);
              $arrCodigoRastreioTratado[$cCodigoRastreiamento]['stExpedicaoAvisoRecebimentoServico'] = trim($arrDados[$IdMdCorExpedicaoSolicitada]['stExpedicaoAvisoRecebimentoServico']);
              $arrCodigoRastreioTratado[$cCodigoRastreiamento]['noDestinatario'] = $arrDados[$IdMdCorExpedicaoSolicitada]['noDestinatario'];
              $arrCodigoRastreioTratado[$cCodigoRastreiamento]['dsEnderecoDestinatario'] = $arrDados[$IdMdCorExpedicaoSolicitada]['dsEnderecoDestinatario'];
              $arrCodigoRastreioTratado[$cCodigoRastreiamento]['dsBairroDestinatario'] = $arrDados[$IdMdCorExpedicaoSolicitada]['dsBairroDestinatario'];
              $arrCodigoRastreioTratado[$cCodigoRastreiamento]['nuCepDestinatario'] = $arrDados[$IdMdCorExpedicaoSolicitada]['nuCepDestinatario'];
              $arrCodigoRastreioTratado[$cCodigoRastreiamento]['dsComplementoDestinatario'] = $arrDados[$IdMdCorExpedicaoSolicitada]['dsComplementoDestinatario'];
              $arrCodigoRastreioTratado[$cCodigoRastreiamento]['noCidadeDestinatario'] = $arrDados[$IdMdCorExpedicaoSolicitada]['noCidadeDestinatario'];
              $arrCodigoRastreioTratado[$cCodigoRastreiamento]['sgUfDestinatario'] = $arrDados[$IdMdCorExpedicaoSolicitada]['sgUfDestinatario'];
              $arrCodigoRastreioTratado[$cCodigoRastreiamento]['idUnidadeExpedidora'] = $arrDados[$IdMdCorExpedicaoSolicitada]['idUnidadeExpedidora'];
              if($objContDTO->getStrCep() != $arrCodigoRastreioTratado[$cCodigoRastreiamento]['nuCepDestinatario']) {
                  unset($arrCodigoRastreioFinal[$arrServico[$idServico][$chave]]);
                  LogSEI::getInstance()->gravar("A solicitação de expedição de id ".$IdMdCorExpedicaoSolicitada." não foi inserida na PLP. O CEP enviado para a PLP está diferente do CEP cadastrado na tabela md_cor_contato. O CEP inserido na PLP é ".$arrCodigoRastreioTratado[$cCodigoRastreiamento]['nuCepDestinatario']. " o CEP correto é o ".$objContDTO->getStrCep());

              }
              $cCodigoRastreiamento++;
              $contador++;
            }
          }
        }


        $arrParametros = [
          'nuCartaoPostagem' => $cartaoPostagem,
          'nuContrato' => $nuContratoCorreio,
          'nuDiretoria' => $nuNumeroDiretoria,
          'coAdministrativo' => $nuCodigoAdministrativo,
          'nuCnpjRemetente' => $cnpj,
          'idContatoOrgao' => $idContatoOrgao,
          'arrCodigoRastreioTratado' => $arrCodigoRastreioTratado,
        ];

        $xml = $this->_recuperarXmlPlp($arrParametros);

        $arrEtiquetaSemDigito = array_values(array_unique($arrEtiquetaSemDigito));

        $arrParametroPlp = ['xml' => $xml
          , 'idPlpCliente' => '1'
          , 'cartaoPostagem' => $cartaoPostagem
          , 'listaEtiquetas' => $arrEtiquetaSemDigito
          , 'usuario' => $usuario
          , 'senha' => $senha
        ];
        $idPlp = $cliente->fechaPlpVariosServicos($arrParametroPlp)->return;

        $arrRetorno = [
          'idPlp' => $idPlp,
          'arrRastreio' => $arrCodigoRastreioFinal
        ];

        return $arrRetorno;

      } catch (SoapFault $soapFault) {
        throw new InfraException('Erro ao gerar PLP - WebService.', $soapFault);
      }

    }

    protected function alterarDadosPlpSolicitacaoControlado($arrParametro) {
      try {
        $mdCorPlpDto = new MdCorPlpDTO();
        SessaoSEI::getInstance()->validarAuditarPermissao('md_cor_plp_cadastrar', __METHOD__, $mdCorPlpDto);
        $mdCorPlpDto->setDblCodigoPlp($arrParametro['idPlp']);
        $mdCorPlpDto->setStrStaPlp(self::$STA_GERADA);

        $mdCorPlpDto->setNumIdUnidadeGeradora(SessaoSEI::getInstance()->getNumIdUnidadeAtual());
        $mdCorPlpDto->setDthDataCadastro(InfraData::getStrDataHoraAtual());

        $ret = $this->cadastrar($mdCorPlpDto);

        foreach ($arrParametro['arrRastreio'] as $idMdCorExpedicaoSolicitada => $dadosRastreio) {
          $mdCorExpedicaoSolicitadaRN = new MdCorExpedicaoSolicitadaRN();
          $mdCorExpedicaoSolicitadaDTO = new MdCorExpedicaoSolicitadaDTO();
          $mdCorExpedicaoSolicitadaDTO->retTodos();
          $mdCorExpedicaoSolicitadaDTO->retDblIdMdCorContrato();
          $mdCorExpedicaoSolicitadaDTO->setNumIdMdCorExpedicaoSolicitada($idMdCorExpedicaoSolicitada);
          $mdCorExpedicaoSolicitadaDTO = $mdCorExpedicaoSolicitadaRN->consultar($mdCorExpedicaoSolicitadaDTO);

          $mdCorExpedicaoSolicitadaDTO->setStrCodigoRastreamento($dadosRastreio);
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

    /**
     * Metodo que gera o XML para geração do PLP seguindo o manual Correios - Revisão: 13/06/2017
     * @param $arrParametros
     * @return string
     */
    private function _recuperarXmlPlp($arrParametros) {
      $dom = new DOMDocument("1.0", "ISO-8859-1");
      $dom->preserveWhiteSpace = false;
      $dom->formatOutput = true;

      //Tag principal
      $tagInicial = $dom->createElement("correioslog");

      //Tipo de Arquivo e Versao
      $tipoArquivo = $dom->createElement("tipo_arquivo", 'Postagem');
      $versao = $dom->createElement("versao_arquivo", '2.3');
      $tagInicial->appendChild($tipoArquivo);
      $tagInicial->appendChild($versao);
//
//
//    //tag da PLP
      $plp = $dom->createElement("plp");
      $idPlp = $dom->createElement("id_plp");
      $vlGlobal = $dom->createElement("valor_global");
      $mcuUnidadePostagem = $dom->createElement("mcu_unidade_postagem");
      $nomeUnidadePostagem = $dom->createElement("nome_unidade_postagem");
      $nuCartaoPostagem = $dom->createElement("cartao_postagem", $arrParametros['nuCartaoPostagem']);

      $plp->appendChild($idPlp);
      $plp->appendChild($vlGlobal);
      $plp->appendChild($mcuUnidadePostagem);
      $plp->appendChild($nomeUnidadePostagem);
      $plp->appendChild($nuCartaoPostagem);
      $tagInicial->appendChild($plp);

      //Tag Remetente
      $remetente = $dom->createElement("remetente");
      $nuContrato = $dom->createElement("numero_contrato", $arrParametros['nuContrato']);
      $remetente->appendChild($nuContrato);
      $nuDiretoria = $dom->createElement("numero_diretoria", '10');
      $remetente->appendChild($nuDiretoria);
      $coAdministrativo = $dom->createElement("codigo_administrativo", $arrParametros['coAdministrativo']);
      $remetente->appendChild($coAdministrativo);

      $objContatoDTO = new ContatoDTO();
      $objContatoDTO->retNumIdContato();
      $objContatoDTO->retStrNome();
      $objContatoDTO->retStrEndereco();
      $objContatoDTO->retStrComplemento();
      $objContatoDTO->retStrBairro();
      $objContatoDTO->retStrCep();
      $objContatoDTO->retStrNomeCidade();
      $objContatoDTO->retStrSiglaUf();
      $objContatoDTO->retDblCnpj();
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
      $objContatoDTO = $objContatoRN->consultarRN0324($objContatoDTO);

      $noRemetente = $dom->createElement('nome_remetente') ;
      $noRemetente->appendChild($dom->createCDATASection(preg_replace('/[^A-Za-z0-9\-]/', ' ', InfraString::excluirAcentos($objContatoDTO->getStrNome()))));
      $remetente->appendChild($noRemetente);

      $arrContato = $this->retornarArrEnderecoComplento($objContatoDTO->getStrEndereco(), $objContatoDTO->getStrComplemento());



      $cpfCnpjRemetente = $dom->createElement("cpf_cnpj_remetente", InfraUtil::retirarFormatacao(InfraUtil::formatarCnpj($objContatoDTO->getDblCnpj())));
      $remetente->appendChild($cpfCnpjRemetente);

      $remetenteEndereco    = $arrContato['endereco'];
      $remetenteComplemento = $arrContato['complemento'];

      $logradouroRemetente = $dom->createElement('logradouro_remetente') ;
      $logradouroRemetente->appendChild($dom->createCDATASection(preg_replace('/[^A-Za-z0-9\-]/', ' ', InfraString::excluirAcentos($remetenteEndereco))));
      $remetente->appendChild($logradouroRemetente);

      $nuRemetente = $dom->createElement('numero_remetente') ;
      $nuRemetente->appendChild($dom->createCDATASection('N/A'));
      $remetente->appendChild($nuRemetente);

      $compRemetente = $dom->createElement('complemento_remetente') ;
      $compRemetente->appendChild($dom->createCDATASection(preg_replace('/[^A-Za-z0-9\-]/', ' ', InfraString::excluirAcentos($remetenteComplemento))));
      $remetente->appendChild($compRemetente);

      $bairroRemetente = $dom->createElement('bairro_remetente') ;
      $bairroRemetente->appendChild($dom->createCDATASection(preg_replace('/[^A-Za-z0-9\-]/', ' ', InfraString::excluirAcentos($objContatoDTO->getStrBairro()))));
      $remetente->appendChild($bairroRemetente);

      $cepRemetente = $dom->createElement("cep_remetente", InfraUtil::retirarFormatacao($objContatoDTO->getStrCep()));
      $remetente->appendChild($cepRemetente);

      $cidadeRemetente = $dom->createElement('cidade_remetente') ;

      $cidade_Remetente = substr(preg_replace('/[^A-Za-z0-9\-]/', ' ', InfraString::excluirAcentos($objContatoDTO->getStrNomeCidade())), 0, 30);
      $cidadeRemetente->appendChild($dom->createCDATASection($cidade_Remetente));
      $remetente->appendChild($cidadeRemetente);

      $ufRemetente = $dom->createElement("uf_remetente", $objContatoDTO->getStrSiglaUf());
      $remetente->appendChild($ufRemetente);

      $telefoneRemetente = $dom->createElement('telefone_remetente') ;
      $telefoneRemetente->appendChild($dom->createCDATASection(InfraUtil::retirarFormatacao($objContatoDTO->getStrTelefoneComercial())));
      $remetente->appendChild($telefoneRemetente);

      $faxRemetente = $dom->createElement("fax_remetente");
      $remetente->appendChild($faxRemetente);

      $emailRemetente = $dom->createElement('email_remetente') ;
      $emailRemetente->appendChild($dom->createCDATASection($objContatoDTO->getStrEmail()));
      $remetente->appendChild($emailRemetente);

      $tagInicial->appendChild($remetente);

      //forma pagamento
      $emailRemetente = $dom->createElement("forma_pagamento");
      $tagInicial->appendChild($emailRemetente);

      //Objeto Postal

      foreach ($arrParametros['arrCodigoRastreioTratado'] as $codigoRastreio) {
        $objetoPostal = $dom->createElement("objeto_postal");

        $nuEtiqueta = $dom->createElement("numero_etiqueta", $codigoRastreio['codigoRastreio']);
        $objetoPostal->appendChild($nuEtiqueta);
        $coObjCliente = $dom->createElement("codigo_objeto_cliente");
        $objetoPostal->appendChild($coObjCliente);
        $coServicoPostagem = $dom->createElement("codigo_servico_postagem", $codigoRastreio['coServicoPostagem']);
        $objetoPostal->appendChild($coServicoPostagem);
        $cubagem = $dom->createElement("cubagem", '0,00');
        $objetoPostal->appendChild($cubagem);
        $peso = $dom->createElement("peso", 10);
        $objetoPostal->appendChild($peso);
        $rt1 = $dom->createElement("rt1");
        $objetoPostal->appendChild($rt1);
        $rt2 = $dom->createElement("rt2");
        $objetoPostal->appendChild($rt2);
        $tagInicial->appendChild($objetoPostal);

        //Destinatario
        $destinatario = $dom->createElement("destinatario");

        $noDestinatario = $dom->createElement('nome_destinatario') ;
        $noDestinatario->appendChild($dom->createCDATASection(preg_replace('/[^A-Za-z0-9\-]/', ' ', InfraString::excluirAcentos($codigoRastreio['noDestinatario']))));
        $destinatario->appendChild($noDestinatario);

        $telDestinatario = $dom->createElement("telefone_destinatario");
        $destinatario->appendChild($telDestinatario);
        $celDestinatario = $dom->createElement("celular_destinatario");
        $destinatario->appendChild($celDestinatario);
        $emailDestinatario = $dom->createElement("email_destinatario");
        $destinatario->appendChild($emailDestinatario);

        $destinatarioEndereco    = ($objContatoDTO->getStrSinEnderecoAssociado() == 'S') ? $objContatoDTO->getStrEnderecoContatoAssociado(): $codigoRastreio['dsEnderecoDestinatario'];
        $destinatarioComplemento = ($objContatoDTO->getStrSinEnderecoAssociado() == 'S') ? $objContatoDTO->getStrComplementoContatoAssociado(): $codigoRastreio['dsComplementoDestinatario'];

        $arrContato = $this->retornarArrEnderecoComplento( $destinatarioEndereco , $destinatarioComplemento);

        $destinatarioEndereco    = $arrContato['endereco'];
        $destinatarioComplemento = $arrContato['complemento'];

        $logradouroDestinatario = $dom->createElement('logradouro_destinatario') ;
        $logradouroDestinatario->appendChild($dom->createCDATASection(preg_replace('/[^A-Za-z0-9\-]/', ' ', InfraString::excluirAcentos( $destinatarioEndereco ))));
        $destinatario->appendChild($logradouroDestinatario);

        $compDestinatario = $dom->createElement('complemento_destinatario') ;
        $compDestinatario->appendChild($dom->createCDATASection(preg_replace('/[^A-Za-z0-9\-]/', ' ', InfraString::excluirAcentos( $destinatarioComplemento ))));
        $destinatario->appendChild($compDestinatario);

        $nuEnderecoDestinatario = $dom->createElement("numero_end_destinatario", 'N/A');
        $destinatario->appendChild($nuEnderecoDestinatario);
        $objetoPostal->appendChild($destinatario);
        // Tag Nacional
        $nacional = $dom->createElement("nacional");

        $bairroDestinatario = $dom->createElement('bairro_destinatario') ;
        $bairroDestinatario->appendChild($dom->createCDATASection(preg_replace('/[^A-Za-z0-9\-]/', ' ', InfraString::excluirAcentos($objContatoDTO->getStrSinEnderecoAssociado() == 'S' ? $objContatoDTO->getStrBairroContatoAssociado() : $codigoRastreio['dsBairroDestinatario']))));
        $nacional->appendChild($bairroDestinatario);

        $cidadeDestinatario = $dom->createElement('cidade_destinatario') ;
        $cidadeDestinatario->appendChild($dom->createCDATASection(preg_replace('/[^A-Za-z0-9\-]/', ' ', InfraString::excluirAcentos($objContatoDTO->getStrSinEnderecoAssociado() == 'S' ? $objContatoDTO->getStrNomeCidadeContatoAssociado() : $codigoRastreio['noCidadeDestinatario']))));
        $nacional->appendChild($cidadeDestinatario);

        $ufDestinatario = $dom->createElement("uf_destinatario", $objContatoDTO->getStrSinEnderecoAssociado() == 'S' ? $objContatoDTO->getStrSiglaUf() : $codigoRastreio['sgUfDestinatario']);
        $nacional->appendChild($ufDestinatario);

        $cepDestinatario = $dom->createElement('cep_destinatario') ;
        $cepDestinatario->appendChild($dom->createCDATASection(InfraUtil::retirarFormatacao($objContatoDTO->getStrSinEnderecoAssociado() == 'S' ? $objContatoDTO->getStrCepContatoAssociado() : $codigoRastreio['nuCepDestinatario'])));
        $nacional->appendChild($cepDestinatario);

        $codigoUsuarioPostal = $dom->createElement("codigo_usuario_postal");
        $nacional->appendChild($codigoUsuarioPostal);
        $centroCustoCliente = $dom->createElement("centro_custo_cliente");
        $nacional->appendChild($centroCustoCliente);
        $numeroNotaFiscal = $dom->createElement("numero_nota_fiscal");
        $nacional->appendChild($numeroNotaFiscal);
        $serieNotaFiscal = $dom->createElement("serie_nota_fiscal");
        $nacional->appendChild($serieNotaFiscal);
        $valorNotaFiscal = $dom->createElement("valor_nota_fiscal");
        $nacional->appendChild($valorNotaFiscal);
        $naturezaNotaFiscal = $dom->createElement("natureza_nota_fiscal");
        $nacional->appendChild($naturezaNotaFiscal);
        $descricaoObjeto = $dom->createElement("descricao_objeto");
        $nacional->appendChild($descricaoObjeto);
        $valorACobrar = $dom->createElement("valor_a_cobrar");
        $nacional->appendChild($valorACobrar);
        $objetoPostal->appendChild($nacional);

        //tag servico adicional
        $servicoAdicional = $dom->createElement("servico_adicional");
        $nacional = $dom->createElement("codigo_servico_adicional", "025");
        $servicoAdicional->appendChild($nacional);

        if( $codigoRastreio['stExpedicaoAvisoRecebimentoServico'] == 'S' ){
            $nacional = $dom->createElement("codigo_servico_adicional", "001");
            $servicoAdicional->appendChild($nacional);
        }

        $valorDeclarado = $dom->createElement("valor_declarado");
        $servicoAdicional->appendChild($valorDeclarado);
        $objetoPostal->appendChild($servicoAdicional);


        //tag dimensao_objeto
        /**
         * @todo verificar com os correios pois o documento está desacordo com o XSD de validacao
         */
        $dimensaoObjeto = $dom->createElement("dimensao_objeto");
        $tipoObjeto = $dom->createElement("tipo_objeto", '001');
        $dimensaoObjeto->appendChild($tipoObjeto);
        $dimensaoAltura = $dom->createElement("dimensao_altura", '0');
        $dimensaoObjeto->appendChild($dimensaoAltura);
        $dimencaoLargura = $dom->createElement("dimensao_largura", '0');
        $dimensaoObjeto->appendChild($dimencaoLargura);
        $dimencaoComprimento = $dom->createElement("dimensao_comprimento", '0');
        $dimensaoObjeto->appendChild($dimencaoComprimento);
        $dimencaoDiametro = $dom->createElement("dimensao_diametro", '0');
        $dimensaoObjeto->appendChild($dimencaoDiametro);
        $objetoPostal->appendChild($dimensaoObjeto);


        //tags finais
        $dataPostagemSara = $dom->createElement("data_postagem_sara");
        $objetoPostal->appendChild($dataPostagemSara);
        $statusProcessamento = $dom->createElement("status_processamento", '0');
        $objetoPostal->appendChild($statusProcessamento);
        $numeroComprovantePostagem = $dom->createElement("numero_comprovante_postagem");
        $objetoPostal->appendChild($numeroComprovantePostagem);
        $vlCobrado = $dom->createElement("valor_cobrado");
        $objetoPostal->appendChild($vlCobrado);
      }


      $dom->appendChild($tagInicial);


#cabeçalho da página
//    header("Content-Type: text/xml");
# imprime o xml na tela
//    print $dom->saveXML();die;
      return $dom->saveXML();
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

  }

