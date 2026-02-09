<?

/**
 * TRIBUNAL REGIONAL FEDERAL DA 4ª REGIÃO
 *
 * 07/06/2017 - criado por marcelo.cast
 *
 * Versão do Gerador de Código: 1.40.1
 */
require_once dirname(__FILE__) . '/../../../SEI.php';

class MdCorExpedicaoSolicitadaRN extends InfraRN
{

    public $isConsultarExpedicaoAndamento = false;
    public static $MD_COR_EXPEDICAO_SOLICITADA_DEVOLVER = 'MD_COR_EXPEDICAO_SOLICITADA_DEVOLVER';

    public function __construct()
    {
        parent::__construct();
    }

    protected function inicializarObjInfraIBanco()
    {
        return BancoSEI::getInstance();
    }

    private function validarStrSinNecessitaAr(MdCorExpedicaoSolicitadaDTO $objMdCorExpedicaoSolicitadaDTO, InfraException $objInfraException)
    {
        if (InfraString::isBolVazia($objMdCorExpedicaoSolicitadaDTO->getStrSinNecessitaAr())) {
            $objInfraException->adicionarValidacao('Necessita AR não informado.');
        } else {
            if (!InfraUtil::isBolSinalizadorValido($objMdCorExpedicaoSolicitadaDTO->getStrSinNecessitaAr())) {
                $objInfraException->adicionarValidacao('Necessita AR inválido.');
            }
        }
    }

    private function validarDblIdDocumentoPrincipal(MdCorExpedicaoSolicitadaDTO $objMdCorExpedicaoSolicitadaDTO, InfraException $objInfraException)
    {
        if (InfraString::isBolVazia($objMdCorExpedicaoSolicitadaDTO->getDblIdDocumentoPrincipal())) {
            $objInfraException->adicionarValidacao('Documento Principal não informado.');
        }
    }

    private function validarNumIdMdCorServicoPostal(MdCorExpedicaoSolicitadaDTO $objMdCorExpedicaoSolicitadaDTO, InfraException $objInfraException)
    {
        if (InfraString::isBolVazia($objMdCorExpedicaoSolicitadaDTO->getNumIdMdCorServicoPostal())) {
            $objInfraException->adicionarValidacao('Serviço postal não informado.');
        }
    }

    private function validarNumIdUnidade(MdCorExpedicaoSolicitadaDTO $objMdCorExpedicaoSolicitadaDTO, InfraException $objInfraException)
    {
        if (InfraString::isBolVazia($objMdCorExpedicaoSolicitadaDTO->getNumIdUnidade())) {
            $objInfraException->adicionarValidacao('Unidade não informada.');
        }
    }

    private function validarDthDataSolicitacao(MdCorExpedicaoSolicitadaDTO $objMdCorExpedicaoSolicitadaDTO, InfraException $objInfraException)
    {
        if (InfraString::isBolVazia($objMdCorExpedicaoSolicitadaDTO->getDthDataSolicitacao())) {
            $objInfraException->adicionarValidacao('Data de Solicitação não informada.');
        }
    }

    private function validarDthDataExpedicao(MdCorExpedicaoSolicitadaDTO $objMdCorExpedicaoSolicitadaDTO, InfraException $objInfraException)
    {
        if (InfraString::isBolVazia($objMdCorExpedicaoSolicitadaDTO->getDthDataExpedicao())) {
            $objMdCorExpedicaoSolicitadaDTO->setDthDataExpedicao(null);
        }
    }

    private function validarNumIdUsuarioSolicitante(MdCorExpedicaoSolicitadaDTO $objMdCorExpedicaoSolicitadaDTO, InfraException $objInfraException)
    {
        if (InfraString::isBolVazia($objMdCorExpedicaoSolicitadaDTO->getNumIdUsuarioSolicitante())) {
            $objInfraException->adicionarValidacao('Usuário Solicitante não informado.');
        }
    }

    private function validarTamanhoEnderecoDest($arrObjContatoDTO , $objInfraException)
    {
        $msgValid = "O campo 'Endereço' do destinatário extrapolou os 50 caracteres aceitos pelos Correios, o que pode implicar em insucesso na entrega do objeto postal. Dessa forma, revise o campo 'Endereço' do destinatário para que tenha até 50 caracteres antes de fazer uma nova solicitação de expedição";
        if ( $arrObjContatoDTO->getStrSinEnderecoAssociado() == 'S' ) {
            if ( strlen( $arrObjContatoDTO->getStrEnderecoContatoAssociado() ) > 50 ) $objInfraException->adicionarValidacao($msgValid);
        } else {
            if ( strlen( $arrObjContatoDTO->getStrEndereco() ) > 50 )  $objInfraException->adicionarValidacao($msgValid);
        }
    }

    private function validarNumIdUsuarioExpAutorizador(MdCorExpedicaoSolicitadaDTO $objMdCorExpedicaoSolicitadaDTO, InfraException $objInfraException)
    {
        if (InfraString::isBolVazia($objMdCorExpedicaoSolicitadaDTO->getNumIdUsuarioExpAutorizador())) {
            $objMdCorExpedicaoSolicitadaDTO->setNumIdUsuarioExpAutorizador(null);
        }
    }

    private function validarStrCodigoRastreamento(MdCorExpedicaoSolicitadaDTO $objMdCorExpedicaoSolicitadaDTO, InfraException $objInfraException)
    {
        if (InfraString::isBolVazia($objMdCorExpedicaoSolicitadaDTO->getStrCodigoRastreamento())) {
            $objMdCorExpedicaoSolicitadaDTO->setStrCodigoRastreamento(null);
        } else {
            $objMdCorExpedicaoSolicitadaDTO->setStrCodigoRastreamento(trim($objMdCorExpedicaoSolicitadaDTO->getStrCodigoRastreamento()));

            if (strlen($objMdCorExpedicaoSolicitadaDTO->getStrCodigoRastreamento()) > 45) {
                $objInfraException->adicionarValidacao('Código de Rastreamento possui tamanho superior a 45 caracteres.');
            }
        }
    }

    protected function cadastrarControlado(MdCorExpedicaoSolicitadaDTO $objMdCorExpedicaoSolicitadaDTO)
    {

        try {

            //Valida Permissao - Log apenas na operaçao "pai de todas"
            SessaoSEI::getInstance()->validarAuditarPermissao('md_cor_expedicao_solicitada_cadastrar');

            //Regras de Negocio
            $objInfraException = new InfraException();

            //campos minimos obrigatorios para o cadastro de expedição
            $this->validarStrSinNecessitaAr($objMdCorExpedicaoSolicitadaDTO, $objInfraException);
            $this->validarDblIdDocumentoPrincipal($objMdCorExpedicaoSolicitadaDTO, $objInfraException);
            $this->validarNumIdMdCorServicoPostal($objMdCorExpedicaoSolicitadaDTO, $objInfraException);
            $this->validarNumIdUnidade($objMdCorExpedicaoSolicitadaDTO, $objInfraException);
            $this->validarDthDataSolicitacao($objMdCorExpedicaoSolicitadaDTO, $objInfraException);
            $this->validarNumIdUsuarioSolicitante($objMdCorExpedicaoSolicitadaDTO, $objInfraException);

            // Validação do endereço do destinatario
            // Dados do Contato
            $objContatoDTO = new ContatoDTO();
            $objContatoDTO->retTodos(true);
            $idDestinatario = $objMdCorExpedicaoSolicitadaDTO->getNumIdContatoDestinatario();
            $objContatoDTO->setNumIdContato($objMdCorExpedicaoSolicitadaDTO->getNumIdContatoDestinatario());
            $objContatoRN = new ContatoRN();
            $arrObjContatoDTO = $objContatoRN->consultarRN0324($objContatoDTO);

            if (!is_null($arrObjContatoDTO)) {
                $this->validarTamanhoEnderecoDest($arrObjContatoDTO,$objInfraException);
            }
            // fim Validação do endereço do destinatario

            //@todo validar conteudos dos arrays de protocolo anexo e formatos

            $objInfraException->lancarValidacoes();

            $objMdCorExpedicaoSolicitadaBD = new MdCorExpedicaoSolicitadaBD($this->getObjInfraIBanco());
            $ret = $objMdCorExpedicaoSolicitadaBD->cadastrar($objMdCorExpedicaoSolicitadaDTO);

            if (!is_null($arrObjContatoDTO)) {
                $itemDTO = new MdCorContatoDTO();

                //$itemDTO->setNumIdMdCorContato();
                $itemDTO->setNumIdMdCorExpedicaoSolicitada($ret->getNumIdMdCorExpedicaoSolicitada());
                $itemDTO->setNumIdContato($arrObjContatoDTO->getNumIdContato());
                $itemDTO->setStrNome($arrObjContatoDTO->getStrNome());
                $itemDTO->setStrSinAtivo('S');
                $itemDTO->setStrStaNatureza($arrObjContatoDTO->getStrStaNatureza());
                $itemDTO->setStrSinEnderecoAssociado($arrObjContatoDTO->getStrSinEnderecoAssociado());

                if ($arrObjContatoDTO->getStrSinEnderecoAssociado() == 'S') {

                    $itemDTO->setNumIdContatoAssociado($arrObjContatoDTO->getNumIdContatoAssociado());
                    $itemDTO->setStrNomeContatoAssociado($arrObjContatoDTO->getStrNomeContatoAssociado());
                    $itemDTO->setStrStaNaturezaContatoAssociado($arrObjContatoDTO->getStrStaNaturezaContatoAssociado());
                    $itemDTO->setNumIdTipoContatoAssociado($arrObjContatoDTO->getNumIdTipoContatoAssociado());
                    $itemDTO->setStrEndereco($arrObjContatoDTO->getStrEnderecoContatoAssociado());
                    $itemDTO->setStrComplemento($arrObjContatoDTO->getStrComplementoContatoAssociado());
                    $itemDTO->setStrBairro($arrObjContatoDTO->getStrBairroContatoAssociado());
                    $itemDTO->setStrCep($arrObjContatoDTO->getStrCepContatoAssociado());
                    $itemDTO->setStrNomeCidade($arrObjContatoDTO->getStrNomeCidadeContatoAssociado());
                    $itemDTO->setStrSiglaUf($arrObjContatoDTO->getStrSiglaUfContatoAssociado());
                } else {
                    $itemDTO->setNumIdContatoAssociado($arrObjContatoDTO->getNumIdContatoAssociado());
                    $itemDTO->setStrNomeContatoAssociado($arrObjContatoDTO->getStrNomeContatoAssociado());
	                $itemDTO->setStrStaNaturezaContatoAssociado($arrObjContatoDTO->getStrStaNaturezaContatoAssociado());
                    $itemDTO->setStrEndereco($arrObjContatoDTO->getStrEndereco());
                    $itemDTO->setStrComplemento($arrObjContatoDTO->getStrComplemento());
                    $itemDTO->setStrBairro($arrObjContatoDTO->getStrBairro());
                    $itemDTO->setStrCep($arrObjContatoDTO->getStrCep());
                    $itemDTO->setStrNomeCidade($arrObjContatoDTO->getStrNomeCidade());
                    $itemDTO->setStrSiglaUf($arrObjContatoDTO->getStrSiglaUf());
                }

                $itemDTO->setStrStaGenero($arrObjContatoDTO->getStrStaGenero());
                $itemDTO->setNumIdTipoContato($arrObjContatoDTO->getNumIdTipoContato());
                $itemDTO->setStrExpressaoCargo($arrObjContatoDTO->getStrExpressaoCargo());
                $itemDTO->setStrExpressaoTratamentoCargo($arrObjContatoDTO->getStrExpressaoTratamentoCargo());

                $itemRN = new MdCorContatoRN();
                $itemRN->cadastrar($itemDTO);
            }

            /*
              //processando array de protocolos
              $arrProtAnexos = $objMdCorExpedicaoSolicitadaDTO->getArrProtocolosAnexos();

              if( is_array( $arrProtAnexos ) && count( $arrProtAnexos ) > 0 ){

              $itemRN = new MdCorExpedicaoAnexoRN();
              foreach( $arrProtAnexos as $idProtocolo ){
              $itemDTO = new MdCorExpedicaoAnexoDTO();
              $itemDTO->setDblIdDocumento( $idProtocolo[0] );
              $itemDTO->setNumIdMdCorExpedicaoSolicitada( $ret->getNumIdMdCorExpedicaoSolicitada() );
              $itemRN->cadastrar( $itemDTO );
              }

              }
             */

            //processando array de formatos
            $arrFormatos = $objMdCorExpedicaoSolicitadaDTO->getArrMdCorExpedicaoFormatoDTO();

            if (is_array($arrFormatos) && count($arrFormatos) > 0) {

                $itemRN = new MdCorExpedicaoFormatoRN();
                $indexPosicaoElemento = 0;


                foreach ($arrFormatos as $idFormato) {
                    //salvar informaçoes da grid de formatos
                    $itemDTO = new MdCorExpedicaoFormatoDTO();

                    $id_protocolo = $idFormato[0];
                    $justificativa = $_POST['txtJustificativa'][$id_protocolo];

                    $nomeCampoFormato = 'rdoFormato_' . $id_protocolo;
                    $nomeCampoImpressao = 'rdoImpressao_' . $id_protocolo;

                    $txtFormatoExp = $_POST[$nomeCampoFormato];
                    $txtImpressaoExp = $_POST[$nomeCampoImpressao];

                    $itemDTO->setDblIdProtocolo($idFormato[0]);
                    $itemDTO->setNumIdMdCorExpedicaoSolicitada($ret->getNumIdMdCorExpedicaoSolicitada());
                    $itemDTO->setStrFormaExpedicao($txtFormatoExp);
                    $itemDTO->setStrImpressao($txtImpressaoExp);
                    $itemDTO->setStrJustificativa($justificativa);

                    $itemRN->cadastrar($itemDTO);
                    $indexPosicaoElemento = $indexPosicaoElemento + 1;
                }
            }

            return $ret;
        } catch (Exception $e) {
            throw new InfraException('Erro cadastrando .', $e);
        }
    }

    protected function cadastrarDocumentosControlado(MdCorExpedicaoSolicitadaDTO $objMdCorExpedicaoSolicitadaDTO)
    {

        try {

            //Valida Permissao - Log apenas na operaçao "pai de todas"
            SessaoSEI::getInstance()->validarAuditarPermissao('md_cor_expedicao_solicitada_cadastrar');

            //Regras de Negocio
            $objInfraException = new InfraException();

            //campos minimos obrigatorios para o cadastro de expedição
            $this->validarStrSinNecessitaAr($objMdCorExpedicaoSolicitadaDTO, $objInfraException);
            $this->validarDblIdDocumentoPrincipal($objMdCorExpedicaoSolicitadaDTO, $objInfraException);
            $this->validarNumIdMdCorServicoPostal($objMdCorExpedicaoSolicitadaDTO, $objInfraException);
            $this->validarNumIdUnidade($objMdCorExpedicaoSolicitadaDTO, $objInfraException);
            $this->validarDthDataSolicitacao($objMdCorExpedicaoSolicitadaDTO, $objInfraException);
            $this->validarNumIdUsuarioSolicitante($objMdCorExpedicaoSolicitadaDTO, $objInfraException);

            //@todo validar conteudos dos arrays de protocolo anexo e formatos

            $objInfraException->lancarValidacoes();

//            $objMdCorExpedicaoSolicitadaBD = new MdCorExpedicaoSolicitadaBD($this->getObjInfraIBanco());
//            $ret = $objMdCorExpedicaoSolicitadaBD->cadastrar($objMdCorExpedicaoSolicitadaDTO);

            //snap contato
//            $objContatoDTO = new ContatoDTO();
//            $objContatoDTO->retTodos(true);
//            $objContatoDTO->setNumIdContato($objMdCorExpedicaoSolicitadaDTO->getNumIdContatoDestinatario());
//            $objContatoRN = new ContatoRN();
//            $arrObjContatoDTO = $objContatoRN->consultarRN0324($objContatoDTO);
//
//            if (count($arrObjContatoDTO) == 1) {
//                $itemDTO = new MdCorContatoDTO();
//
//                //$itemDTO->setNumIdMdCorContato();
//                $itemDTO->setNumIdMdCorExpedicaoSolicitada($objMdCorExpedicaoSolicitadaDTO->getNumIdMdCorExpedicaoSolicitada());
//                $itemDTO->setNumIdContato($arrObjContatoDTO->getNumIdContato());
//                $itemDTO->setStrNome($arrObjContatoDTO->getStrNome());
//                $itemDTO->setStrSinAtivo('S');
//                $itemDTO->setStrStaNatureza($arrObjContatoDTO->getStrStaNatureza());
//                $itemDTO->setStrSinEnderecoAssociado($arrObjContatoDTO->getStrSinEnderecoAssociado());
//
//                if ($arrObjContatoDTO->getStrSinEnderecoAssociado() == 'S') {
//                    $itemDTO->setNumIdContatoAssociado($arrObjContatoDTO->getNumIdContatoAssociado());
//                    $itemDTO->setStrNomeContatoAssociado($arrObjContatoDTO->getStrNomeContatoAssociado());
//                    $itemDTO->setStrStaNaturezaContatoAssociado($arrObjContatoDTO->getStrStaNaturezaContatoAssociado());
//                    $itemDTO->setNumIdTipoContatoAssociado($arrObjContatoDTO->getNumIdTipoContatoAssociado());
//                    $itemDTO->setStrEndereco($arrObjContatoDTO->getStrEnderecoContatoAssociado());
//                    $itemDTO->setStrComplemento($arrObjContatoDTO->getStrComplementoContatoAssociado());
//                    $itemDTO->setStrBairro($arrObjContatoDTO->getStrBairroContatoAssociado());
//                    $itemDTO->setStrCep($arrObjContatoDTO->getStrCepContatoAssociado());
//                    $itemDTO->setStrNomeCidade($arrObjContatoDTO->getStrNomeCidadeContatoAssociado());
//                    $itemDTO->setStrSiglaUf($arrObjContatoDTO->getStrSiglaUfContatoAssociado());
//                } else {
//                    $itemDTO->setNumIdContatoAssociado($arrObjContatoDTO->getNumIdContato());
//                    $itemDTO->setStrEndereco($arrObjContatoDTO->getStrEndereco());
//                    $itemDTO->setStrComplemento($arrObjContatoDTO->getStrComplemento());
//                    $itemDTO->setStrBairro($arrObjContatoDTO->getStrBairro());
//                    $itemDTO->setStrCep($arrObjContatoDTO->getStrCep());
//                    $itemDTO->setStrNomeCidade($arrObjContatoDTO->getStrNomeCidade());
//                    $itemDTO->setStrSiglaUf($arrObjContatoDTO->getStrSiglaUf());
//                }
//
//                $itemDTO->setStrStaGenero($arrObjContatoDTO->getStrStaGenero());
//                $itemDTO->setNumIdTipoContato($arrObjContatoDTO->getNumIdTipoContato());
//                $itemDTO->setStrExpressaoCargo($arrObjContatoDTO->getStrExpressaoCargo());
//                $itemDTO->setStrExpressaoTratamentoCargo($arrObjContatoDTO->getStrExpressaoTratamentoCargo());
//
//                $itemRN = new MdCorContatoRN();
//                $itemRN->cadastrar($itemDTO);
//            }

            /*
              //processando array de protocolos
              $arrProtAnexos = $objMdCorExpedicaoSolicitadaDTO->getArrProtocolosAnexos();

              if( is_array( $arrProtAnexos ) && count( $arrProtAnexos ) > 0 ){

              $itemRN = new MdCorExpedicaoAnexoRN();
              foreach( $arrProtAnexos as $idProtocolo ){
              $itemDTO = new MdCorExpedicaoAnexoDTO();
              $itemDTO->setDblIdDocumento( $idProtocolo[0] );
              $itemDTO->setNumIdMdCorExpedicaoSolicitada( $ret->getNumIdMdCorExpedicaoSolicitada() );
              $itemRN->cadastrar( $itemDTO );
              }

              }
             */

            //processando array de formatos
            $arrFormatos = $objMdCorExpedicaoSolicitadaDTO->getArrMdCorExpedicaoFormatoDTO();

            if (is_array($arrFormatos) && count($arrFormatos) > 0) {

                $itemRN = new MdCorExpedicaoFormatoRN();
                $indexPosicaoElemento = 0;


                foreach ($arrFormatos as $idFormato) {

                    //salvar informaçoes da grid de formatos
                    $itemDTO = new MdCorExpedicaoFormatoDTO();

                    $id_protocolo = $idFormato[0];
                    $justificativa = $_POST['txtJustificativa'][$id_protocolo];

                    $nomeCampoFormato = 'rdoFormato_' . $id_protocolo;
                    $nomeCampoImpressao = 'rdoImpressao_' . $id_protocolo;

                    $txtFormatoExp = $_POST[$nomeCampoFormato];
                    $txtImpressaoExp = $_POST[$nomeCampoImpressao];

                    $itemDTO->setDblIdProtocolo($idFormato[0]);
                    $itemDTO->setNumIdMdCorExpedicaoSolicitada($objMdCorExpedicaoSolicitadaDTO->getNumIdMdCorExpedicaoSolicitada());
                    $itemDTO->setStrFormaExpedicao($txtFormatoExp);
                    $itemDTO->setStrImpressao($txtImpressaoExp);
                    $itemDTO->setStrJustificativa($justificativa);

                    $itemRN->cadastrar($itemDTO);
                    $indexPosicaoElemento = $indexPosicaoElemento + 1;
                }
            }

            return $ret;
        } catch (Exception $e) {
            throw new InfraException('Erro cadastrando .', $e);
        }
    }

    public function _retornaObjAtributoAndamentoAPI($nome, $valor, $idOrigem = null)
    {
        $objAtributoAndamentoAPI = new AtributoAndamentoAPI();
        $objAtributoAndamentoAPI->setNome($nome);
        $objAtributoAndamentoAPI->setValor($valor);
        $objAtributoAndamentoAPI->setIdOrigem($idOrigem); //ID do prédio, pode ser null

        return $objAtributoAndamentoAPI;
    }

    protected function alterarControlado(MdCorExpedicaoSolicitadaDTO $objMdCorExpedicaoSolicitadaDTO)
    {
        try {

            //Valida Permissao
            SessaoSEI::getInstance()->validarPermissao('md_cor_expedicao_solicitada_alterar');

            //Regras de Negocio
            $objInfraException = new InfraException();

            if ($objMdCorExpedicaoSolicitadaDTO->isSetStrSinNecessitaAr()) {
                $this->validarStrSinNecessitaAr($objMdCorExpedicaoSolicitadaDTO, $objInfraException);
            }
            if ($objMdCorExpedicaoSolicitadaDTO->isSetDblIdDocumentoPrincipal()) {
                $this->validarDblIdDocumentoPrincipal($objMdCorExpedicaoSolicitadaDTO, $objInfraException);
            }
            if ($objMdCorExpedicaoSolicitadaDTO->isSetNumIdMdCorServicoPostal()) {
                $this->validarNumIdMdCorServicoPostal($objMdCorExpedicaoSolicitadaDTO, $objInfraException);
            }
            if ($objMdCorExpedicaoSolicitadaDTO->isSetNumIdUnidade()) {
                $this->validarNumIdUnidade($objMdCorExpedicaoSolicitadaDTO, $objInfraException);
            }
            if ($objMdCorExpedicaoSolicitadaDTO->isSetDthDataSolicitacao()) {
                $this->validarDthDataSolicitacao($objMdCorExpedicaoSolicitadaDTO, $objInfraException);
            }
            if ($objMdCorExpedicaoSolicitadaDTO->isSetDthDataExpedicao()) {
                $this->validarDthDataExpedicao($objMdCorExpedicaoSolicitadaDTO, $objInfraException);
            }
            if ($objMdCorExpedicaoSolicitadaDTO->isSetNumIdUsuarioSolicitante()) {
                $this->validarNumIdUsuarioSolicitante($objMdCorExpedicaoSolicitadaDTO, $objInfraException);
            }
            if ($objMdCorExpedicaoSolicitadaDTO->isSetNumIdUsuarioExpAutorizador()) {
                $this->validarNumIdUsuarioExpAutorizador($objMdCorExpedicaoSolicitadaDTO, $objInfraException);
            }
            if ($objMdCorExpedicaoSolicitadaDTO->isSetStrCodigoRastreamento()) {
                $this->validarStrCodigoRastreamento($objMdCorExpedicaoSolicitadaDTO, $objInfraException);
            }

            $objInfraException->lancarValidacoes();

            $objMdCorExpedicaoSolicitadaBD = new MdCorExpedicaoSolicitadaBD($this->getObjInfraIBanco());
            $objMdCorExpedicaoSolicitadaBD->alterar($objMdCorExpedicaoSolicitadaDTO);

            //Auditoria
        } catch (Exception $e) {
            throw new InfraException('Erro alterando .', $e);
        }
    }

    protected function excluirControlado($arrObjMdCorExpedicaoSolicitadaDTO)
    {
        try {

            //Valida Permissao
            SessaoSEI::getInstance()->validarPermissao('md_cor_expedicao_solicitada_excluir');

            //Regras de Negocio
            //$objInfraException = new InfraException();
            //$objInfraException->lancarValidacoes();
            $mdCorContatoRN = new MdCorContatoRN();
            $mdCorExpedicaoFormatoRN = new MdCorExpedicaoFormatoRN();

            $objMdCorExpedicaoSolicitadaBD = new MdCorExpedicaoSolicitadaBD($this->getObjInfraIBanco());
            for ($i = 0; $i < count($arrObjMdCorExpedicaoSolicitadaDTO); $i++) {

                $mdCorContatoDTO = new MdCorContatoDTO();
                $mdCorContatoDTO->retTodos();
                $mdCorContatoDTO->setNumIdMdCorExpedicaoSolicitada($arrObjMdCorExpedicaoSolicitadaDTO[$i]->getNumIdMdCorExpedicaoSolicitada());
                $arrObjMdCorContatoDTO = $mdCorContatoRN->listar($mdCorContatoDTO);
                $mdCorContatoRN->excluir($arrObjMdCorContatoDTO);

                $mdCorExpedicaoFormatoDTO = new MdCorExpedicaoFormatoDTO();
                $mdCorExpedicaoFormatoDTO->retTodos();
                $mdCorExpedicaoFormatoDTO->setNumIdMdCorExpedicaoSolicitada($arrObjMdCorExpedicaoSolicitadaDTO[$i]->getNumIdMdCorExpedicaoSolicitada());
                $arrObjMdCorExpedicaoFormatoDTO = $mdCorExpedicaoFormatoRN->listar($mdCorExpedicaoFormatoDTO);
                $mdCorExpedicaoFormatoRN->excluir($arrObjMdCorExpedicaoFormatoDTO);

                $objMdCorExpedicaoSolicitadaBD->excluir($arrObjMdCorExpedicaoSolicitadaDTO[$i]);
            }

            //Auditoria
        } catch (Exception $e) {
            throw new InfraException('Erro excluindo .', $e);
        }
    }


    protected function excluirDocumentosControlado($arrObjMdCorExpedicaoSolicitadaDTO)
    {
        try {

            //Valida Permissao
            SessaoSEI::getInstance()->validarPermissao('md_cor_expedicao_solicitada_excluir');

            //Regras de Negocio
            //$objInfraException = new InfraException();
            //$objInfraException->lancarValidacoes();
            $mdCorContatoRN = new MdCorContatoRN();
            $mdCorExpedicaoFormatoRN = new MdCorExpedicaoFormatoRN();

            $objMdCorExpedicaoSolicitadaBD = new MdCorExpedicaoSolicitadaBD($this->getObjInfraIBanco());
            for ($i = 0; $i < count($arrObjMdCorExpedicaoSolicitadaDTO); $i++) {

//                $mdCorContatoDTO = new MdCorContatoDTO();
//                $mdCorContatoDTO->retTodos();
//                $mdCorContatoDTO->setNumIdMdCorExpedicaoSolicitada($arrObjMdCorExpedicaoSolicitadaDTO[$i]->getNumIdMdCorExpedicaoSolicitada());
//                $arrObjMdCorContatoDTO = $mdCorContatoRN->listar($mdCorContatoDTO);
//                $mdCorContatoRN->excluir($arrObjMdCorContatoDTO);

                $mdCorExpedicaoFormatoDTO = new MdCorExpedicaoFormatoDTO();
                $mdCorExpedicaoFormatoDTO->retTodos();
                $mdCorExpedicaoFormatoDTO->setNumIdMdCorExpedicaoSolicitada($arrObjMdCorExpedicaoSolicitadaDTO[$i]->getNumIdMdCorExpedicaoSolicitada());
                $arrObjMdCorExpedicaoFormatoDTO = $mdCorExpedicaoFormatoRN->listar($mdCorExpedicaoFormatoDTO);
                $mdCorExpedicaoFormatoRN->excluir($arrObjMdCorExpedicaoFormatoDTO);
            }

            //Auditoria
        } catch (Exception $e) {
            throw new InfraException('Erro excluindo .', $e);
        }
    }


    protected function consultarConectado(MdCorExpedicaoSolicitadaDTO $objMdCorExpedicaoSolicitadaDTO)
    {
        try {

            //Valida Permissao
            SessaoSEI::getInstance()->validarPermissao('md_cor_expedicao_solicitada_consultar');

            //Regras de Negocio
            $objInfraException = new InfraException();
            $objInfraException->lancarValidacoes();

            $objMdCorExpedicaoSolicitadaBD = new MdCorExpedicaoSolicitadaBD($this->getObjInfraIBanco());
            $ret = $objMdCorExpedicaoSolicitadaBD->consultar($objMdCorExpedicaoSolicitadaDTO);

            //Auditoria

            return $ret;
        } catch (Exception $e) {
            throw new InfraException('Erro consultando .', $e);
        }
    }

    protected function listarConectado(MdCorExpedicaoSolicitadaDTO $objMdCorExpedicaoSolicitadaDTO)
    {
        try {

            //Valida Permissao
            SessaoSEI::getInstance()->validarPermissao('md_cor_expedicao_solicitada_listar');

            //Regras de Negocio
            //$objInfraException = new InfraException();
            //$objInfraException->lancarValidacoes();

            $objMdCorExpedicaoSolicitadaBD = new MdCorExpedicaoSolicitadaBD($this->getObjInfraIBanco());
            $ret = $objMdCorExpedicaoSolicitadaBD->listar($objMdCorExpedicaoSolicitadaDTO);

            //Auditoria

            return $ret;
        } catch (Exception $e) {
            throw new InfraException('Erro listando .', $e);
        }
    }

    protected function contarConectado(MdCorExpedicaoSolicitadaDTO $objMdCorExpedicaoSolicitadaDTO)
    {
        try {

            //Valida Permissao
//            SessaoSEI::getInstance()->validarPermissao('md_cor_expedicao_solicitada_listar');
            //Regras de Negocio
            //$objInfraException = new InfraException();
            //$objInfraException->lancarValidacoes();

            $objMdCorExpedicaoSolicitadaBD = new MdCorExpedicaoSolicitadaBD($this->getObjInfraIBanco());
            $ret = $objMdCorExpedicaoSolicitadaBD->contar($objMdCorExpedicaoSolicitadaDTO);

            //Auditoria

            return $ret;
        } catch (Exception $e) {
            throw new InfraException('Erro contando .', $e);
        }
    }

    protected function listarAndamentoExpedicoesProcessoConectado($objDTO)
    {
        $this->_addSelectsListarAndamento($objDTO);
        $objDTO->setDblIdProtocolo($_GET['id_procedimento']);

        $count = $this->contar($objDTO);
        $arrObjDTO = array();

        if ($count > 0) {
            $arrObjDTO = $this->listar($objDTO);
            //$objMdCorExpedicaoSolicitadaBD = new MdCorExpedicaoSolicitadaBD($this->getObjInfraIBanco());
            //  $ret = $objMdCorExpedicaoSolicitadaBD->listar($objDTO, true);


            $idsSolicitacao = InfraArray::converterArrInfraDTO($arrObjDTO, 'IdMdCorExpedicaoSolicitada');

            $arrStatus = $this->_getUltimoAndamento($idsSolicitacao);
            $arrAnexo = $this->_getArrAnexo($idsSolicitacao);

            $this->_formatarArrListaAtributos($arrObjDTO, $arrStatus, $arrAnexo);
        }

        return array($arrObjDTO, $objDTO);
    }

    protected function listarSolicitacaoExpedicaoPendenteConectado($objDto)
    {
        $arrObjDto = $this->listar($objDto);

        $arrIdMdCorExpedicaoSolicitada = InfraArray::converterArrInfraDTO($arrObjDto, 'IdMdCorExpedicaoSolicitada');

        $arrAnexo = $this->_getArrAnexo($arrIdMdCorExpedicaoSolicitada);

        $arrSolExpedicao = [];
        $contadorFor = 0;
        foreach ($arrObjDto as $chave => $objItemDto) {

            $mdCorContatoRN = new MdCorContatoRN();
            $objMdCorContatoDTO = new MdCorContatoDTO();
            $objMdCorContatoDTO->setNumIdMdCorExpedicaoSolicitada($objItemDto->getNumIdMdCorExpedicaoSolicitada());
            $objMdCorContatoDTO->retTodos(true);
            $objMdCorContatoDTO = $mdCorContatoRN->consultar($objMdCorContatoDTO);

//            $contatoRN = new ContatoRN();
//            $objContatoDTO = new ContatoDTO();
//            $objContatoDTO->retTodos(true);
//            $objContatoDTO->setBolExclusaoLogica(false);
//            $objContatoDTO->setNumIdContato($objDto->getNumIdContatoDestinatario());
//            $objContatoDTO = $contatoRN->consultarRN0324($objContatoDTO);

            $strDestinatario = "";

            $strDestinatario .= mb_strtoupper($objMdCorContatoDTO->getStrNome(), 'ISO-8859-1') . "<br />";
            $strDestinatario .= !empty($objMdCorContatoDTO->getStrExpressaoCargo()) ? $objMdCorContatoDTO->getStrExpressaoCargo() . "<br />" : '';
            if ( !empty($objMdCorContatoDTO->getStrNomeContatoAssociado()) && $objMdCorContatoDTO->getNumIdContato() != $objMdCorContatoDTO->getNumIdContatoAssociado() ) {
                $strDestinatario .= $objMdCorContatoDTO->getStrNomeContatoAssociado() . "<br />";
            }
            //$strDestinatario .= $objMdCorContatoDTO->getStrNomeTipoContato() . "<br />"; #removido para padronizar os dados do destinatario
	          // monta a parte do endereco
	          $strEndereco = $objMdCorContatoDTO->getStrEndereco();
            $strCompl    = $objMdCorContatoDTO->getStrComplemento() ? ', ' . $objMdCorContatoDTO->getStrComplemento() : '';
            $strBairro   = $objMdCorContatoDTO->getStrBairro();

	          $strDestinatario .= "$strEndereco $strCompl , $strBairro <br />";
	          $strDestinatario .= "{$objMdCorContatoDTO->getStrCep()} - {$objMdCorContatoDTO->getStrNomeCidade()}/{$objMdCorContatoDTO->getStrSiglaUf()} <br />";

            $id = $objItemDto->getNumIdMdCorExpedicaoSolicitada();
            $arrSolExpedicao[$objItemDto->getStrDescricaoServicoPostal()][$contadorFor]['IdMdCorExpedicaoSolicitada'] = $id;
            $arrSolExpedicao[$objItemDto->getStrDescricaoServicoPostal()][$contadorFor]['unidade'] = $objItemDto->getStrSiglaUnidade();
            $arrSolExpedicao[$objItemDto->getStrDescricaoServicoPostal()][$contadorFor]['dtSolicitacao'] = $objItemDto->getDthDataSolicitacao();

            $numDoc = $objItemDto->isSetStrNumeroDocumento()?" ".$objItemDto->getStrNumeroDocumento():"";
            $tipo = !empty($objItemDto->getStrNomeSerie())?$objItemDto->getStrNomeSerie().$numDoc:$objItemDto->getStrNomeProcedimento();
            $idDocPrincipal = $objItemDto->isSetDblIdDocumentoPrincipal() ? $objItemDto->getDblIdDocumentoPrincipal() :null;
            $strUrlDocumento = SessaoSEI::getInstance()->assinarLink("controlador.php?acao=procedimento_trabalhar&id_documento=" . $idDocPrincipal);
            $arrSolExpedicao[$objItemDto->getStrDescricaoServicoPostal()][$contadorFor]['docPrincipal'] =  $tipo.'<a class="protocoloNormal" style="font-size: 1.0em !important; font-size:1em" href="'.$strUrlDocumento.'" target="_blank">('.$objItemDto->getStrProtocoloFormatadoDocumento().')</a>';
            $arrSolExpedicao[$objItemDto->getStrDescricaoServicoPostal()][$contadorFor]['idDocPrincipal'] = $objItemDto->isSetDblIdDocumentoPrincipal() ? $objItemDto->getDblIdDocumentoPrincipal() :null;
            $arrSolExpedicao[$objItemDto->getStrDescricaoServicoPostal()][$contadorFor]['processo']=$objItemDto->getStrProtocoloFormatado();
            $arrSolExpedicao[$objItemDto->getStrDescricaoServicoPostal()][$contadorFor]['destinatario'] = $strDestinatario;
            //Retirando o documento principal, para que não seja identificado como anexo
            $arrSolExpedicao[$objItemDto->getStrDescricaoServicoPostal()][$contadorFor]['qtdAnexo'] = $arrAnexo[$id] - 1;

            /* Verifica se existe alguma solicitacao em Midia para cada expedicao */
	          $objMdCorExpedicaoFormatoDTO = new MdCorExpedicaoFormatoDTO();
	          $objMdCorExpedicaoFormatoDTO->setNumIdMdCorExpedicaoSolicitada($id);
	          $objMdCorExpedicaoFormatoDTO->setStrFormaExpedicao('M');

	          $qtd = ( new MdCorExpedicaoFormatoRN() )->contar( $objMdCorExpedicaoFormatoDTO );

	          if ( $qtd > 0 ) $arrSolExpedicao[$objItemDto->getStrDescricaoServicoPostal()][$contadorFor]['formatoMidia'] = 'Sim';
	          else $arrSolExpedicao[$objItemDto->getStrDescricaoServicoPostal()][$contadorFor]['formatoMidia'] = 'Não';

            $contadorFor++;

        }

        return $arrSolExpedicao;
    }

    private function _addSelectsListarAndamento(&$objDTO)
    {

        $objDTO->retNumIdMdCorExpedicaoSolicitada();

        //Documento Principal
        $objDTO->retDblIdDocumentoPrincipal();
        $objDTO->retNumIdSerie();
        $objDTO->retStrNomeSerie();
        $objDTO->retDblIdProtocoloDocumento();
        $objDTO->retStrProtocoloFormatadoDocumento();
        $objDTO->retStrNumeroDocumento();

        //Outras Colunas
        $objDTO->retStrNomeServicoPostal();
        $objDTO->retDthDataSolicitacao();
        $objDTO->retDthDataExpedicao();
        $objDTO->retStrCodigoRastreamento();
        $objDTO->retStrDescricaoServicoPostal();

        $objDTO->setOrd('IdMdCorExpedicaoSolicitada',InfraDTO::$TIPO_ORDENACAO_DESC);
    }

    private function _formatarArrListaAtributos(&$arrObjDTO, $arrStatus, $arrAnexo)
    {

        foreach ($arrObjDTO as $key => $objDTO) {
            $id = $objDTO->getNumIdMdCorExpedicaoSolicitada();
            $strUrlDocumento = SessaoSEI::getInstance()->assinarLink('controlador.php?acao=procedimento_trabalhar&id_documento=' . $objDTO->getDblIdDocumentoPrincipal());
            $docFormatado = $objDTO->getStrNomeSerie() . ' ' . $objDTO->getStrNumeroDocumento() . ' <a class="protocoloNormal" style="font-size: 1.0em !important; font-size:1em" href="'.$strUrlDocumento.'" target="_blank">(' . $objDTO->getStrProtocoloFormatadoDocumento() . ')</a>';
            $anexos = isset($arrAnexo[$id]) && ($arrAnexo[$id] > 0) ? 'Sim' : 'Não';
            $ultimAnd = isset($arrStatus[$id]) ? $arrStatus[$id] : '';
            $dtSol = !is_null($objDTO->getDthDataSolicitacao()) && $objDTO->getDthDataSolicitacao() != '' ? explode(' ', $objDTO->getDthDataSolicitacao()) : '';
            $dtExp = !is_null($objDTO->getDthDataExpedicao()) && $objDTO->getDthDataExpedicao() != '' ? explode(' ', $objDTO->getDthDataExpedicao()) : '';
            $arrObjDTO[$key]->setStrAnexos($anexos);
            $arrObjDTO[$key]->setStrUltimoAndamento($ultimAnd);
            $arrObjDTO[$key]->setStrDocSerieFormatados($docFormatado);
            $arrObjDTO[$key]->setDthDataSolicitacao(is_array($dtSol) > 0 ? $dtSol[0] : $dtSol);
            $arrObjDTO[$key]->setDthDataExpedicao(is_array($dtExp) > 0 ? $dtExp[0] : $dtExp);
        }
    }

    private function _getArrAnexo($idsSolicitacao)
    {
        /*
          $arrAnexo = array();

          $objMdCorExpedicaoAnexoDTO = new MdCorExpedicaoAnexoDTO();
          $objMdCorExpedicaoAnexoDTO->setNumIdMdCorExpedicaoSolicitada($idsSolicitacao, InfraDTO::$OPER_IN);
          $objMdCorExpedicaoAnexoDTO->retNumIdMdCorExpedicaoSolicitada();

          $objMdCorExpedicaoAnexoRN  = new MdCorExpedicaoAnexoRN();
          $arrExpedicaoAnexoDTO = $objMdCorExpedicaoAnexoRN->listar($objMdCorExpedicaoAnexoDTO);

          foreach ($arrExpedicaoAnexoDTO as $key => $anexoDTO){
          $count = isset($arrAnexo[$anexoDTO->getNumIdMdCorExpedicaoSolicitada()]) ? $arrAnexo[$anexoDTO->getNumIdMdCorExpedicaoSolicitada()] + 1 : 1;
          $arrAnexo[$anexoDTO->getNumIdMdCorExpedicaoSolicitada()] = $count;
          }
          return $arrAnexo;

         */
//  	die('_getArrAnexo');
        $arrFormato = array();

        $objMdCorExpedicaoFormatoDTO = new MdCorExpedicaoFormatoDTO();
        if (count($idsSolicitacao) > 0) {
            $objMdCorExpedicaoFormatoDTO->setNumIdMdCorExpedicaoSolicitada($idsSolicitacao, InfraDTO::$OPER_IN);
            //$objMdCorExpedicaoFormatoDTO->setDblIdDocumento($dtoProtAnexo->getObjInfraAtributoDTO('IdDocumentoPrincipal'), InfraDTO::$OPER_DIFERENTE);
            $objMdCorExpedicaoFormatoDTO->retNumIdMdCorExpedicaoSolicitada();

            $objMdCorExpedicaoFormatoRN = new MdCorExpedicaoFormatoRN();
            $arrObjMdCorExpedicaoFormatoDTO = $objMdCorExpedicaoFormatoRN->listar($objMdCorExpedicaoFormatoDTO);

            foreach ($arrObjMdCorExpedicaoFormatoDTO as $key => $formatoDTO) {
                $count = isset($arrFormato[$formatoDTO->getNumIdMdCorExpedicaoSolicitada()]) ? $arrFormato[$formatoDTO->getNumIdMdCorExpedicaoSolicitada()] + 1 : 1;
                $arrFormato[$formatoDTO->getNumIdMdCorExpedicaoSolicitada()] = $count;
            }
        }

        return $arrFormato;
    }

    private function _getUltimoAndamento($idsSolicitacao)
    {
        $arrStatus = array();
        $objMdCorExpedicaoAndamentoRN = new MdCorExpedicaoAndamentoRN();
        $objMdCorExpedicaoAndamentoDTO = new MdCorExpedicaoAndamentoDTO();
        $objMdCorExpedicaoAndamentoDTO->retNumIdMdCorExpedicaoSolicitada();
        $objMdCorExpedicaoAndamentoDTO->setDistinct(true);
        $objMdCorExpedicaoAndamentoDTO->setOrdDthDataHora(InfraDTO::$TIPO_ORDENACAO_DESC);
        $objMdCorExpedicaoAndamentoDTO->setNumIdMdCorExpedicaoSolicitada($idsSolicitacao, InfraDTO::$OPER_IN);
        $objMdCorExpedicaoAndamentoDTO->retStrDescricao();
        $objMdCorExpedicaoAndamentoDTO->retDthDataHora();

        $count = $objMdCorExpedicaoAndamentoRN->contar($objMdCorExpedicaoAndamentoDTO);
        $arrListaDTO = $objMdCorExpedicaoAndamentoRN->listar($objMdCorExpedicaoAndamentoDTO);

        if ($count > 0) {
            foreach ($arrListaDTO as $objDTO) {
                if (!isset($arrStatus[$objDTO->getNumIdMdCorExpedicaoSolicitada()])) {
                    $arrStatus[$objDTO->getNumIdMdCorExpedicaoSolicitada()] = $objDTO->getStrDescricao();
                }
            }
        }

        return $arrStatus;
    }

    public function listarExpedicaoSolicitadaExpedidaConectado($idProcedimento = null)
    {
        //Busca todas as expedições solicitadas que ja foram expedidas
        $objMdCorExpedicaoSolicitadaDTO = new MdCorExpedicaoSolicitadaDTO();

        $objMdCorExpedicaoSolicitadaDTO->retNumIdMdCorExpedicaoSolicitada();
        $objMdCorExpedicaoSolicitadaDTO->retStrCodigoRastreamento();
        $objMdCorExpedicaoSolicitadaDTO->retDthDataExpedicao();
        $objMdCorExpedicaoSolicitadaDTO->retDblIdMdCorContrato();
        $objMdCorExpedicaoSolicitadaDTO->retStrSinRecebido();

        if ($this->getIsConsultarExpedicaoAndamento()) {
            $objMdCorExpedicaoSolicitadaDTO->setNumIdUnidade(SessaoSEI::getInstance()->getNumIdUnidadeAtual());
        }

        if (!is_null($idProcedimento)) {
            $objMdCorExpedicaoSolicitadaDTO->setNumIdProcedimento($idProcedimento);
        }
        //Condição para pegar apenas as expedidas
        $objMdCorExpedicaoSolicitadaDTO->adicionarCriterio(array('CodigoRastreamento', 'DataExpedicao'), array(InfraDTO::$OPER_DIFERENTE, InfraDTO::$OPER_DIFERENTE), array('null', 'null'), InfraDTO::$OPER_LOGICO_AND);

        $arrObjMdCorExpedicaoSolicitadaDTO = $this->listar($objMdCorExpedicaoSolicitadaDTO);

        foreach ($arrObjMdCorExpedicaoSolicitadaDTO as $objMdCorExpedicaoSolicitadaDTO) {

            $dataHoraExpedicao = $objMdCorExpedicaoSolicitadaDTO->getDthDataExpedicao();

            if (!is_null($dataHoraExpedicao) && $dataHoraExpedicao != "") {
                $diasCorridos = 181;

                if ($this->retornarQuantidadeDias($dataHoraExpedicao) < $diasCorridos) {
                    $arrMdCorExpedicaoSolicitadaDTO[] = $objMdCorExpedicaoSolicitadaDTO;
                }
            }
        }

        return $arrMdCorExpedicaoSolicitadaDTO;
    }

    private function retornarQuantidadeDias($dataHoraExpedicao)
    {

        $d = substr($dataHoraExpedicao, 0, 2);
        $m = substr($dataHoraExpedicao, 3, 2);
        $y = substr($dataHoraExpedicao, 6, 4);

        $dtExpedicao = $y . '-' . $m . '-' . $d;

        // Calcula a diferença em segundos entre as datas
        $diferenca = strtotime(date('Y-m-d')) - strtotime($dtExpedicao);

        //Calcula a diferença em dias
        $dias = floor($diferenca / (60 * 60 * 24));
        return $dias;
    }

    protected function listarExpedicaoSolicitadaUnidadeConectado($objMdCorExpedicaoSolitadaDTO)
    {
        $objMdCorExpedicaoAndamentoRN = new MdCorExpedicaoAndamentoRN();

        $arrObjMdCorExpedicaoSolitadaDTO = $this->listar($objMdCorExpedicaoSolitadaDTO);
        foreach ($arrObjMdCorExpedicaoSolitadaDTO as &$objMdCorExpedicaoSolictadaDTO) {
            $idMdCorExpedicaoSolicitada = $objMdCorExpedicaoSolictadaDTO->getNumIdMdCorExpedicaoSolicitada();
            $objMdCorExpedicaoAndamentoDTO = $objMdCorExpedicaoAndamentoRN->buscarUltimoAndamento($idMdCorExpedicaoSolicitada);
            $strUltimoAndamento = '';
            if ($objMdCorExpedicaoAndamentoDTO) {
                $strUltimoAndamento = $objMdCorExpedicaoAndamentoDTO->getStrDescricao();
            }

            $objMdCorExpedicaoSolictadaDTO->setStrUltimoAndamento($strUltimoAndamento);
        }
        return $arrObjMdCorExpedicaoSolitadaDTO;
    }

    protected function listarDadosRastreioSolicitacaoConectado($idSolicitacao)
    {
        $objDTO = new MdCorExpedicaoSolicitadaDTO();
        $objDTO->setNumIdMdCorExpedicaoSolicitada($idSolicitacao);
        $objDTO->retDblIdDocumentoPrincipal();
        $objDTO->retNumIdSerie();
        $objDTO->retStrNomeSerie();
        $objDTO->retDblIdProtocoloDocumento();
        $objDTO->retStrProtocoloFormatadoDocumento();
        $objDTO->retStrCodigoRastreamento();
        $objDTO->retStrNomeServicoPostal();
        $objDTO->retStrDescricaoServicoPostal();
        $objDTO->retStrNumeroDocumento();
        return $this->consultar($objDTO);
    }

    protected function listarDadosRastreioCodigoConectado($coRastreio)
    {
        $objDTO = new MdCorExpedicaoSolicitadaDTO();
        $objDTO->setStrCodigoRastreamento($coRastreio);
        $objDTO->retDblIdDocumentoPrincipal();
        $objDTO->retNumIdSerie();
        $objDTO->retStrNomeSerie();
        $objDTO->retDblIdProtocoloDocumento();
        $objDTO->retNumIdMdCorExpedicaoSolicitada();
        $objDTO->retStrProtocoloFormatadoDocumento();
        $objDTO->retStrCodigoRastreamento();
        $objDTO->retStrNomeServicoPostal();
        $objDTO->retStrDescricaoServicoPostal();
        $objDTO->retStrNumeroDocumento();

        return $this->consultar($objDTO);
    }

    protected function verificarProcessoPossuiExpedicaoConectado($idProcesso)
    {
        $arrIdsDoc = $this->_getDocumentosProcesso($idProcesso);
        $objRN = new MdCorExpedicaoSolicitadaRN();

        if (!is_null($arrIdsDoc)) {
            $objDTO = new MdCorExpedicaoSolicitadaDTO();
            $objDTO->setDblIdDocumentoPrincipal($arrIdsDoc, InfraDTO::$OPER_IN);
            return $objRN->contar($objDTO) > 0;
        }

        return false;
    }

    private function _getDocumentosProcesso($idProcedimento)
    {
        //Init Vars
        $arrIdsDoc = null;
        $objDocumentoRN = new DocumentoRN();
        $objDocumentoDTO = new DocumentoDTO();

        $objDocumentoDTO->setDblIdProcedimento($idProcedimento);
        $objDocumentoDTO->retDblIdDocumento();
        $count = $objDocumentoRN->contarRN0007($objDocumentoDTO);

        if ($count > 0) {
            $arrObjDTODoc = $objDocumentoRN->listarRN0008($objDocumentoDTO);
            $arrIdsDoc = InfraArray::converterArrInfraDTO($arrObjDTODoc, 'IdDocumento');
        }

        return $arrIdsDoc;
    }

    protected function retornarExpedicaoPlpConectado($objMdCorExpedicaoSolicitadaDTO)
    {

        $objMdCorExpedicaoSolicitadaDTO->retNumIdMdCorExpedicaoSolicitada();
        $objMdCorExpedicaoSolicitadaDTO->retStrSiglaUnidade();
        $objMdCorExpedicaoSolicitadaDTO->retStrDescricaoUnidade();
        $objMdCorExpedicaoSolicitadaDTO->retStrNomeSerie();
        $objMdCorExpedicaoSolicitadaDTO->retStrProtocoloFormatadoDocumento();
        $objMdCorExpedicaoSolicitadaDTO->retStrProtocoloFormatado();
        $objMdCorExpedicaoSolicitadaDTO->retStrCodigoRastreamento();
        $objMdCorExpedicaoSolicitadaDTO->retStrSinObjetoAcessado();
        $objMdCorExpedicaoSolicitadaDTO->retNumCodigoPlp();
        $objMdCorExpedicaoSolicitadaDTO->retNumIdMdCorPlp();

        $arrObjMdCorExpedicaoSolicitadaDTO = $this->listar($objMdCorExpedicaoSolicitadaDTO);

        $idsMdCorExpedicaoSolicitada = array();

        foreach ($arrObjMdCorExpedicaoSolicitadaDTO as $key => $objDTO) {

            $objMdCorExpedicaoSolicitadaDTOFormato = $objDTO;
            $objMdCorExpedicaoSolicitadaDTOFormato->retStrFormaExpedicao();
            $arrObjMdCorExpedicaoSolicitadaDTOFormato = $this->listar($objMdCorExpedicaoSolicitadaDTOFormato);
            $midia = 'N';
            foreach ($arrObjMdCorExpedicaoSolicitadaDTOFormato as $item) {
                if ($item->getStrFormaExpedicao() == MdCorExpedicaoFormatoRN::$TP_FORMATO_MIDIA) {
                    $midia = 'S';
                }
            }
            $objDTO->setStrMidia($midia);
            $idsMdCorExpedicaoSolicitada[] = $objDTO->getNumIdMdCorExpedicaoSolicitada();
            ///$objMdCorExpedicaoSolicitadaDTO->setNumQuantidadeAnexo(count($arrAnexo));
        }
        $arrAnexo = $this->_getArrAnexo($idsMdCorExpedicaoSolicitada);

        foreach ($arrObjMdCorExpedicaoSolicitadaDTO as $key => $objDTO) {

            $objDTO->setNumQuantidadeAnexo($arrAnexo[$objDTO->getNumIdMdCorExpedicaoSolicitada()]);
        }

        return $arrObjMdCorExpedicaoSolicitadaDTO;
    }

    protected function retornarPlpGeradasConectado($objMdCorExpedicaoSolicitadaDTO)
    {

//        $objMdCorExpedicaoSolicitadaDTO = new MdCorExpedicaoSolicitadaDTO();
        $objMdCorExpedicaoSolicitadaDTO->retNumIdMdCorPlp();
        $objMdCorExpedicaoSolicitadaDTO->retNumIdMdCorExpedicaoSolicitada();
        $objMdCorExpedicaoSolicitadaDTO->retStrNomeServicoPostal();
        $objMdCorExpedicaoSolicitadaDTO->retNumCodigoPlp();
        $objMdCorExpedicaoSolicitadaDTO->retStrStaPlp();
        $objMdCorExpedicaoSolicitadaDTO->retStrSinObjetoAcessado();
        $objMdCorExpedicaoSolicitadaDTO->retNumIdUnidade();
        $objMdCorExpedicaoSolicitadaDTO->retStrFormaExpedicao();
        //$objMdCorExpedicaoSolicitadaDTO->setNumIdMdCorServicoPostal(14);
        $arrObjMdCorExpedicaoSolicitadaDTO = $this->listar($objMdCorExpedicaoSolicitadaDTO);

        $numRegistro = count($arrObjMdCorExpedicaoSolicitadaDTO);

        $arrRetorno = $this->_retornaArrayPlpGeradasFormatado($arrObjMdCorExpedicaoSolicitadaDTO);

        /* foreach($arrObjMdCorExpedicaoSolicitadaDTO as $keys=> $objDTO ){
          array_search($objDTO->getNumIdMdCorPlp(),$arrObjMdCorExpedicaoSolicitadaDTO);
          $newArrObjMdCorExpedicaoSolicitadaDTO =new MdCorExpedicaoSolicitadaDTO();

          } */


        return $arrRetorno;
    }

    private function _retornaArrayPlpGeradasFormatado($arrObjMdCorExpedicaoSolicitadaDTO)
    {

        $arrServicoPorPlp = array();
        $arrClean = array();
        $arrFormatado = array();
        $arrQtdDeObjetosPorPlp = array();

        foreach ($arrObjMdCorExpedicaoSolicitadaDTO as $objDto) {
            if (array_key_exists($objDto->getNumIdMdCorPlp(), $arrServicoPorPlp)) {
                if (!strrpos($arrServicoPorPlp[$objDto->getNumIdMdCorPlp()], $objDto->getStrDescricaoServicoPostal())) {
                    $arrServicoPorPlp[$objDto->getNumIdMdCorPlp()] .= ' , ' . $objDto->getStrDescricaoServicoPostal();
                }
            } else {
                $arrServicoPorPlp[$objDto->getNumIdMdCorPlp()] = $objDto->getStrDescricaoServicoPostal();
            }
        }


        foreach ($arrObjMdCorExpedicaoSolicitadaDTO as $key => $objDto) {

            if (!array_key_exists($objDto->getNumIdMdCorPlp(), $arrClean)) {

                $objDto->setStrNomeServicoPostal($arrServicoPorPlp[$objDto->getNumIdMdCorPlp()]);
                $qtdObj = count($this->retornarExpedicaoPlpConectado($objDto));

                $objDto->setNumQuantidadeObjeto($qtdObj);
                $arrClean[$objDto->getNumIdMdCorPlp()] = $objDto;


                array_push($arrFormatado, $objDto);
            }
        }

        return $arrFormatado;
    }

    protected function validarObjetoConectado($arrIdMdCorExpedicaoSolicitada)
    {
        $objMdCorExpedicaoSolicitadaDTO = new MdCorExpedicaoSolicitadaDTO();
        $objMdCorExpedicaoSolicitadaDTO->retTodos(false);
        $objMdCorExpedicaoSolicitadaDTO->retDblIdMdCorContrato();
        $objMdCorExpedicaoSolicitadaDTO->setNumIdMdCorExpedicaoSolicitada($arrIdMdCorExpedicaoSolicitada, InfraDTO::$OPER_IN);
        $objMdCorExpedicaoSolicitadaDTO->setNumIdMdCorObjeto(null);

        $arrObjMdCorExpedicaoSolicitadaDTO = $this->listar($objMdCorExpedicaoSolicitadaDTO);

        foreach ($arrObjMdCorExpedicaoSolicitadaDTO as $dados) {
            $objMdCorObjetoDTO = new MdCorObjetoDTO();
            $objMdCorObjetoDTO->retNumIdMdCorObjeto();
            $objMdCorObjetoDTO->setStrSinObjetoPadrao('S');
            $objMdCorObjetoDTO->setNumIdMdCorContrato($dados->getDblIdMdCorContrato());

            $objMdCorObjetoRN = new MdCorObjetoRN();
            $objMdCorObjetoDTO = $objMdCorObjetoRN->consultar($objMdCorObjetoDTO);

            if (count($arrObjMdCorExpedicaoSolicitadaDTO) > 0 && empty($objMdCorObjetoDTO)) {
                $objInfraException = new InfraException();
                $objInfraException->adicionarValidacao('Selecione o formato de expedição dos objetos.');
                $objInfraException->lancarValidacoes();
            }
        }
    }

    protected function validarTipoEmbalagemConectado($idMdCorExpedicaoSolicitada)
    {
        $objMdCorExpedicaoSolicitadaDTO = new MdCorExpedicaoSolicitadaDTO();
        $objMdCorExpedicaoSolicitadaDTO->retDblIdMdCorContrato();
        $objMdCorExpedicaoSolicitadaDTO->setNumIdMdCorExpedicaoSolicitada($idMdCorExpedicaoSolicitada);
        $arrObjMdCorExpedicaoSolicitadaDTO = $this->consultar($objMdCorExpedicaoSolicitadaDTO);

        $objMdCorObjetoRN = new MdCorObjetoRN();
        $objMdCorObjetoDTO = new MdCorObjetoDTO();
        $objMdCorObjetoDTO->setStrSinAtivo('S');
        $objMdCorObjetoDTO->setNumIdMdCorContrato($arrObjMdCorExpedicaoSolicitadaDTO->getDblIdMdCorContrato());

        $qtdTipoEmbalagem = $objMdCorObjetoRN->contar($objMdCorObjetoDTO);


        if ($qtdTipoEmbalagem == 0) {
            echo "<script>";
            echo "alert('Os tipos de embalagens para o Contrato selecionado não foram cadastradas, antes é necessário que verifique com o Gestor do Módulo dos Correios para cadastrar os Tipos de Embalagens em Administração > Correios > Contratos e Serviços Postais > Tipos de Embalagem.');";
            echo "window.close();";
            echo "</script>";
        }
    }


    public function validarAcessoRestrito($idProtocolo)
    {

        $arrObjDocumentoAPI = array();
        $objProtocoloRN = new ProtocoloRN();
        $objCorreioIntegracao = new CorreiosIntegracao();

        $objProtocoloDTO = new ProtocoloDTO();
        $objProtocoloDTO->setDblIdProtocolo($idProtocolo);
        $objProtocoloDTO->setNumTipoFkProcedimento(InfraDTO::$TIPO_FK_OBRIGATORIA);
        $objProtocoloDTO->setNumTipoFkDocumento(InfraDTO::$TIPO_FK_OPCIONAL);

        $objProtocoloDTO->setDistinct(true);
        $objProtocoloDTO->retStrStaNivelAcessoGlobal();
        $objProtocoloDTO->retStrStaProtocolo();
        $objProtocoloDTO->retNumIdSerieDocumento();
        $objProtocoloDTO->retNumIdUnidadeGeradora();
        $objProtocoloDTO->retStrStaDocumentoDocumento();

        $arrObjProtocoloDTO = $objProtocoloRN->listarRN0668($objProtocoloDTO);
        $acesso = false;

        if (count($arrObjProtocoloDTO)) {

            foreach ($arrObjProtocoloDTO as $objProtocoloDTO) {

                $objDocumentoAPI = new DocumentoAPI();
                $objDocumentoAPI->setSubTipo($objProtocoloDTO->getStrStaDocumentoDocumento());
                $objDocumentoAPI->setNivelAcesso($objProtocoloDTO->getStrStaNivelAcessoGlobal());
                $objDocumentoAPI->setIdSerie($objProtocoloDTO->getNumIdSerieDocumento());
                $objDocumentoAPI->setIdUnidadeGeradora($objProtocoloDTO->getNumIdUnidadeGeradora());
                $arrObjDocumentoAPI[] = $objDocumentoAPI;
            }

            $arrIdUnidadeSolicitante = $objCorreioIntegracao->retornarArrIdUnidadeSolicitante();

            if (!empty($arrIdUnidadeSolicitante)) {

                foreach ($arrObjDocumentoAPI as $objDocumentoAPI) {

                    if (in_array($objDocumentoAPI->getIdUnidadeGeradora(), $arrIdUnidadeSolicitante)) {
                        if ($objDocumentoAPI->getNivelAcesso() != ProtocoloRN::$NA_SIGILOSO) {
                            $acesso = true;
                        }
                    }
                }
            }
        }

        return $acesso;

    }

    public function getIsConsultarExpedicaoAndamento()
    {
        return $this->isConsultarExpedicaoAndamento;
    }

    public function setIsConsultarExpedicaoAndamento($isConsultarExpedicaoAndamento)
    {
        $this->isConsultarExpedicaoAndamento = $isConsultarExpedicaoAndamento;
    }

    public function validarContatoComExpedicaoAndamento($objContato)
    {
        $idContato = $objContato->getIdContato();
        $mdCorExpedicaoSolicitadaRN = new MdCorExpedicaoSolicitadaRN();
        $mdCorExpedicaoSolicitadaDTO = new MdCorExpedicaoSolicitadaDTO();

        $mdCorExpedicaoSolicitadaDTO->setNumIdContatoDestinatario($idContato);

        if ($objContato->getStaNatureza() == 'J') {

            $dto = new ContatoDTO();
            $dto->retNumIdContato();
            $dto->setNumIdContatoAssociado($idContato);
            $dto->setDistinct(true);
            $dto->setStrStaNatureza('F');
            $dto->setStrSinEnderecoAssociado('S');

            $rn = new ContatoRN();
            $dto = $rn->listarRN0325($dto);

            if (count($dto) > 0) {
                $arrIdContato = InfraArray::converterArrInfraDTO($dto, 'IdContato');
                $mdCorExpedicaoSolicitadaDTO->setNumIdContatoDestinatario($arrIdContato, InfraDTO::$OPER_IN);
            }
        }

        $mdCorExpedicaoSolicitadaDTO->retNumIdMdCorExpedicaoSolicitada();
        $mdCorExpedicaoSolicitadaDTO->retStrStaPlp();
        $mdCorExpedicaoSolicitadaDTO->retNumCodigoPlp();
        $mdCorExpedicaoSolicitadaDTO->setDistinct(true);

        $arrMdCorExpedicaoSolicitadaDTO = $mdCorExpedicaoSolicitadaRN->listar($mdCorExpedicaoSolicitadaDTO);

        if (count($arrMdCorExpedicaoSolicitadaDTO) > 0) {
            foreach ($arrMdCorExpedicaoSolicitadaDTO as $mdCorExpedicaoSolicitadaDTO) {
                if ($mdCorExpedicaoSolicitadaDTO->getStrStaPlp() == MdCorPlpRN::$STA_GERADA) {
                    return true;
                }
            }
        }
    }

    public function alterarEnderecoExpedSemPLP($objContato, $idDocumentoPrincipal, $flagAlterarCopia)
    {
        if($flagAlterarCopia) {
            $idContato = $objContato->getNumIdContato();
            $mdCorExpedicaoSolicitadaRN = new MdCorExpedicaoSolicitadaRN();
            $mdCorExpedicaoSolicitadaDTO = new MdCorExpedicaoSolicitadaDTO();

            $mdCorExpedicaoSolicitadaDTO->setNumIdContatoDestinatario($idContato);
            $mdCorExpedicaoSolicitadaDTO->setDblIdDocumentoPrincipal($idDocumentoPrincipal);
            if ($objContato->getStrStaNatureza() == 'J') {

                $dto = new ContatoDTO();
                $dto->retNumIdContato();
                $dto->setNumIdContatoAssociado($idContato);
                $dto->setDistinct(true);
                $dto->setStrStaNatureza('F');
                $dto->setStrSinEnderecoAssociado('S');

                $rn = new ContatoRN();
                $dto = $rn->listarRN0325($dto);

                if (count($dto) > 0) {
                    $arrIdContato = InfraArray::converterArrInfraDTO($dto, 'IdContato');
                    $mdCorExpedicaoSolicitadaDTO->setNumIdContatoDestinatario($arrIdContato, InfraDTO::$OPER_IN);
                }
            }

            $mdCorExpedicaoSolicitadaDTO->retNumIdMdCorExpedicaoSolicitada();
            $mdCorExpedicaoSolicitadaDTO->retStrStaPlp();
            $mdCorExpedicaoSolicitadaDTO->retNumCodigoPlp();
            $mdCorExpedicaoSolicitadaDTO->setDistinct(true);

            $arrMdCorExpedicaoSolicitadaDTO = $mdCorExpedicaoSolicitadaRN->listar($mdCorExpedicaoSolicitadaDTO);

            if (count($arrMdCorExpedicaoSolicitadaDTO) > 0) {
                foreach ($arrMdCorExpedicaoSolicitadaDTO as $mdCorExpedicaoSolicitadaDTO) {
                    if (is_null($mdCorExpedicaoSolicitadaDTO->getStrStaPlp()) || $mdCorExpedicaoSolicitadaDTO->getStrStaPlp() == MdCorPlpRN::$STA_PENDENTE) {
                        $mdCorContatoRN = new MdCorContatoRN();
                        $mdCorContatoDTO = new MdCorContatoDTO();
                        $mdCorContatoDTO->retTodos();
                        $mdCorContatoDTO->setNumIdMdCorExpedicaoSolicitada($mdCorExpedicaoSolicitadaDTO->getNumIdMdCorExpedicaoSolicitada());

                        $newMdCorContatoDTO = $mdCorContatoRN->consultar($mdCorContatoDTO);

                        if ($newMdCorContatoDTO) {
                            $newMdCorContatoDTO->setStrNome($objContato->getStrNome());
                            $newMdCorContatoDTO->setStrStaNatureza($objContato->getStrStaNatureza());
                            $newMdCorContatoDTO->setStrSinEnderecoAssociado($objContato->getStrSinEnderecoAssociado());

                            if ($objContato->getStrSinEnderecoAssociado() == 'S') {
                                $contatoAssociadoDTO = new ContatoDTO();
                                $contatoAssociadoDTO->retTodos();
                                $contatoAssociadoDTO->setNumIdContato($objContato->getNumIdContatoAssociado());
                                $contatoAssociadoDTO->setDistinct(true);

                                $rn = new ContatoRN();
                                $arrContatoAssociadoDTO = $rn->listarRN0325($contatoAssociadoDTO);

                                if (count($arrContatoAssociadoDTO) > 0) {
                                    $contatoAssociadoDTO = current($arrContatoAssociadoDTO);

                                    $newMdCorContatoDTO->setNumIdContatoAssociado($contatoAssociadoDTO->getNumIdContato());
                                    $newMdCorContatoDTO->setNumIdTipoContatoAssociado($contatoAssociadoDTO->getNumIdTipoContato());
                                    $newMdCorContatoDTO->setStrNomeContatoAssociado($contatoAssociadoDTO->getStrNome());
                                    $newMdCorContatoDTO->setStrStaNaturezaContatoAssociado($contatoAssociadoDTO->getStrStaNatureza());
                                    $newMdCorContatoDTO->setStrEndereco($contatoAssociadoDTO->getStrEndereco());
                                    $newMdCorContatoDTO->setStrComplemento($contatoAssociadoDTO->getStrComplemento());
                                    $newMdCorContatoDTO->setStrBairro($contatoAssociadoDTO->getStrBairro());
                                    $newMdCorContatoDTO->setStrCep($contatoAssociadoDTO->getStrCep());

                                    $nomeCidade = $this->getNomeCidade($contatoAssociadoDTO->getNumIdCidade());
                                    $newMdCorContatoDTO->setStrNomeCidade($nomeCidade);

                                    $siglaUf = $this->getSiglaUf($contatoAssociadoDTO->getNumIdUf());
                                    $newMdCorContatoDTO->setStrSiglaUf($siglaUf);
                                }
                            } else {
                                if ( $objContato->getNumIdContato() != $objContato->getNumIdContatoAssociado() ) {
                                    $contatoAssociadoDTO = new ContatoDTO();
                                    $contatoAssociadoDTO->retTodos();
                                    $contatoAssociadoDTO->setNumIdContato($objContato->getNumIdContatoAssociado());
                                    $contatoAssociadoDTO->setDistinct(true);

                                    $rn = new ContatoRN();
                                    $arrContatoAssociadoDTO = $rn->listarRN0325($contatoAssociadoDTO);
                                    $contatoAssociadoDTO = current($arrContatoAssociadoDTO);

                                    $newMdCorContatoDTO->setStrStaNaturezaContatoAssociado($contatoAssociadoDTO->getStrStaNatureza());
                                    $newMdCorContatoDTO->setStrNomeContatoAssociado($contatoAssociadoDTO->getStrNome());
                                    $newMdCorContatoDTO->setNumIdTipoContatoAssociado($contatoAssociadoDTO->getNumIdTipoContato());
                                    $newMdCorContatoDTO->setNumIdContatoAssociado($contatoAssociadoDTO->getNumIdContato());
                                } else {
                                    $newMdCorContatoDTO->setStrStaNaturezaContatoAssociado($objContato->getStrStaNatureza());
                                    $newMdCorContatoDTO->setStrNomeContatoAssociado($objContato->getStrNome());
                                    $newMdCorContatoDTO->setNumIdTipoContatoAssociado($objContato->getNumIdTipoContato());
                                    $newMdCorContatoDTO->setNumIdContatoAssociado($objContato->getNumIdContato());
                                }

                                $newMdCorContatoDTO->setStrEndereco($objContato->getStrEndereco());
                                $newMdCorContatoDTO->setStrComplemento($objContato->getStrComplemento());
                                $newMdCorContatoDTO->setStrBairro($objContato->getStrBairro());
                                $newMdCorContatoDTO->setStrCep($objContato->getStrCep());

                                $nomeCidade = $this->getNomeCidade($objContato->getNumIdCidade());
                                $newMdCorContatoDTO->setStrNomeCidade($nomeCidade);

                                $siglaUf = $this->getSiglaUf($objContato->getNumIdUf());
                                $newMdCorContatoDTO->setStrSiglaUf($siglaUf);
                            }

                            $newMdCorContatoDTO->setStrStaGenero($objContato->getStrStaGenero());
                            $newMdCorContatoDTO->setNumIdTipoContato($objContato->getNumIdTipoContato());
                            $newMdCorContatoDTO->setStrExpressaoCargo($objContato->getStrExpressaoCargo());
                            $newMdCorContatoDTO->setStrExpressaoTratamentoCargo($objContato->getStrExpressaoTratamentoCargo());
                            $newMdCorContatoDTO->setNumIdContato($objContato->getNumIdContato());

                            $mdCorContatoRN->alterar($newMdCorContatoDTO);
                        }
                    }
                }
            }
            /**
             * Exclusão da variável de Sessão criada para transportar o Id do Documento Principal da Solicitação de Expedição
             * Por que a Função Alterar Contato do Core do SEI não permite que essa variável seja passada.
             */
            unset($_SESSION['idDocumentoPrincipal']);
        }
    }

    public function getNomeCidade($idCidade)
    {
        $cidadeRN = new CidadeRN();
        $cidadeDTO = new CidadeDTO();
        $cidadeDTO->setNumIdCidade($idCidade);
        $cidadeDTO->retStrNome();

        $arrCidadeDTO = $cidadeRN->listarRN0410($cidadeDTO);
        if ($arrCidadeDTO) {
            return current($arrCidadeDTO)->getStrNome();
        }
    }

    public function getSiglaUf($idEstado)
    {
        $cidadeRN = new CidadeRN();
        $cidadeDTO = new CidadeDTO();
        $cidadeDTO->setNumIdUf($idEstado);
        $cidadeDTO->retStrSiglaUf();

        $arrCidadeDTO = $cidadeRN->listarRN0410($cidadeDTO);
        if ($arrCidadeDTO) {
            return current($arrCidadeDTO)->getStrSiglaUf();
        }
    }
    public function enviarEmail($parametrosEmail)
    {
        $enviaemail = true;
        $objInfraParametro = new InfraParametro( $this->getObjInfraIBanco());

        $objUnidadeDTO = new UnidadeDTO();
        $objUnidadeDTO->setBolExclusaoLogica(false);
        $objUnidadeDTO->retNumIdUnidade();
        $objUnidadeDTO->retNumIdOrgao();
        $objUnidadeDTO->setNumIdUnidade($parametrosEmail["idUnidade"]);

        $objUnidadeRN = new UnidadeRN();
        $objUnidadeDTO = $objUnidadeRN->consultarRN0125($objUnidadeDTO);

        $unidadeDTO = new UnidadeDTO();
        $unidadeDTO->retStrDescricao();
        $unidadeDTO->retStrSigla();
        $unidadeDTO->setNumIdUnidade($parametrosEmail["IdUnidadeGeradora"] ?: $parametrosEmail["IdUnidadeExpedidora"]);

        $unidadeRN = new UnidadeRN();
        $unidadeDTO = $unidadeRN->consultarRN0125($unidadeDTO);

        $unidade_exp = $unidadeDTO->getStrSigla();

        //consultar email da unidade (orgao)
        $orgaoRN = new OrgaoRN();
        $objOrgaoDTO = new OrgaoDTO();
        $objOrgaoDTO->retTodos();
        $objOrgaoDTO->retStrSitioInternetContato();
        $objOrgaoDTO->setNumIdOrgao($objUnidadeDTO->getNumIdOrgao());
        $objOrgaoDTO->setStrSinAtivo('S');
        $objOrgaoDTO = $orgaoRN->consultarRN1352( $objOrgaoDTO );

        $objEmailUnidadeDTO = new EmailUnidadeDTO();
        $emailUnidadeRN = new EmailUnidadeRN();
        $objEmailUnidadeDTO->setDistinct(true);
        $objEmailUnidadeDTO->retNumIdUnidade();
        $objEmailUnidadeDTO->retStrEmail();
        $objEmailUnidadeDTO->setNumIdUnidade($objUnidadeDTO->getNumIdUnidade());
        $arrEmailUnidade = $emailUnidadeRN->listar($objEmailUnidadeDTO);

        $parametrosEmail["siglaOrgao"] = $objOrgaoDTO->getStrSigla();
        $parametrosEmail["descricaoOrgao"] = $objOrgaoDTO->getStrDescricao();
        $parametrosEmail["siglaSistema"] = SessaoSEIExterna::getInstance()->getStrSiglaSistema();
        $parametrosEmail["emailSistema"] = $objInfraParametro->getValor('SEI_EMAIL_SISTEMA');
        $parametrosEmail["sitioInternetOrgao"] = $objOrgaoDTO->getStrSitioInternetContato();

        //================================================================================================
        //EMAIL PARA A UNIDADE DE ABERTURA DO PETICIONAMENTO
        //================================================================================================

        $objEmailSistemaDTO = new EmailSistemaDTO();
        $objEmailSistemaDTO->retStrDe();
        $objEmailSistemaDTO->retStrPara();
        $objEmailSistemaDTO->retStrAssunto();
        $objEmailSistemaDTO->retStrConteudo();
        $objEmailSistemaDTO->setStrIdEmailSistemaModulo(MdCorExpedicaoSolicitadaRN::$MD_COR_EXPEDICAO_SOLICITADA_DEVOLVER);

        $objEmailSistemaRN = new EmailSistemaRN();
        $objEmailSistemaDTO = $objEmailSistemaRN->consultar($objEmailSistemaDTO);

        if ($objEmailSistemaDTO!=null){
            foreach($arrEmailUnidade as $mail){
                $strDe = $objEmailSistemaDTO->getStrDe();
                $strDe = str_replace('@email_sistema@',$parametrosEmail["emailSistema"],$strDe);
                $strDe = str_replace('@sigla_sistema@',$parametrosEmail["siglaSistema"],$strDe);

                $strPara = $objEmailSistemaDTO->getStrPara();
                $strPara = str_replace('@emails_unidade@', $mail->getStrEmail() , $strPara);

                $strAssunto = $objEmailSistemaDTO->getStrAssunto();
                $strAssunto = str_replace('@processo@', $parametrosEmail["processo"], $strAssunto);

                $strConteudo = $objEmailSistemaDTO->getStrConteudo();
                $strConteudo = str_replace('@sigla_unidade_solicitante@', $parametrosEmail["siglaUnidadeSolicitante"], $strConteudo);
                $strConteudo = str_replace('@sigla_orgao@', $parametrosEmail["siglaOrgao"], $strConteudo);
                $strConteudo = str_replace('@documento@', $parametrosEmail["documento"], $strConteudo);
                $strConteudo = str_replace('@processo@', $parametrosEmail["processo"], $strConteudo);
                $strConteudo = str_replace('@justificativa_devolucao_solicitacao_expedicao@', $parametrosEmail["justificativaDevolucao"],$strConteudo);
                $strConteudo = str_replace('@descricao_orgao@', $parametrosEmail["descricaoOrgao"], $strConteudo);
                $strConteudo = str_replace('@sitio_internet_orgao@', $parametrosEmail["sitioInternetOrgao"], $strConteudo);
                $strConteudo = str_replace('@unidade_expedidora@', $unidade_exp, $strConteudo);

                if ($enviaemail){
                     InfraMail::enviarConfigurado(ConfiguracaoSEI::getInstance(), $strDe, $strPara, null, null, $strAssunto, $strConteudo);
                }
            }
        }

    }

    public function validarExistenciaObjetoAguardandoRetornoAR($objProcedimentoAPI) {
        $idProcedimento = $objProcedimentoAPI[0]->getIdProcedimento();

        $mdCorExpedicaoSolicitadaDTO = new MdCorExpedicaoSolicitadaDTO();
        $mdCorExpedicaoSolicitadaDTO->retNumIdMdCorExpedicaoSolicitada();
        $mdCorExpedicaoSolicitadaDTO->retStrCodigoRastreamento();
        $mdCorExpedicaoSolicitadaDTO->retDthDataExpedicao();
        $mdCorExpedicaoSolicitadaDTO->retNumIdDocumentoAr();
        $mdCorExpedicaoSolicitadaDTO->retDblIdDocumentoPrincipal();
        $mdCorExpedicaoSolicitadaDTO->retNumIdProcedimento();
        $mdCorExpedicaoSolicitadaDTO->retNumIdMdCorParamArInfrigencia();
        $mdCorExpedicaoSolicitadaDTO->retNumIdMdCorPlp();
        $mdCorExpedicaoSolicitadaDTO->retStrSinDevolvido();
        $mdCorExpedicaoSolicitadaDTO->retStrStaPlp();
        $mdCorExpedicaoSolicitadaDTO->setStrStaPlp(MdCorPlpRN::$STA_GERADA, INFRADTO::$OPER_DIFERENTE);
        $mdCorExpedicaoSolicitadaDTO->setStrSinNecessitaAr('S');
        $mdCorExpedicaoSolicitadaDTO->setStrSinRecebido('N');
        $mdCorExpedicaoSolicitadaDTO->setStrSinDevolvido('N');
        $mdCorExpedicaoSolicitadaDTO->setNumIdProcedimento($idProcedimento);
        $mdCorExpedicaoSolicitadaDTO->setOrdNumIdMdCorRetornoArDoc(InfraDTO::$TIPO_ORDENACAO_DESC);
        $arrMdCorExpedicaoSolicitadaDTO = $this->listar($mdCorExpedicaoSolicitadaDTO);

        if (!empty($arrMdCorExpedicaoSolicitadaDTO)) {
            foreach ($arrMdCorExpedicaoSolicitadaDTO as $mdCorExpedicaoSolicitadaDTO) {
                // --- CASO 1: OBJETO AINDA NÃO FOI PARA O CORREIO ---
        
                // Se não tem PLP, está "Aguardando Expedição" -> PODE BLOQUEAR
                if (is_null($mdCorExpedicaoSolicitadaDTO->getNumIdMdCorPlp())) {
                    continue;
                }

                // Se tem PLP mas NÃO TEM Data de Expedição, está "Em procedimento de postagem" -> PODE BLOQUEAR
                // Conforme seu var_dump: DataExpedicao é NULL
                if (is_null($mdCorExpedicaoSolicitadaDTO->getDthDataExpedicao())) {
                    continue;
                }

                // --- CASO 2: OBJETO JÁ ESTÁ NA RUA, MAS FOI DEVOLVIDO OU ENTREGUE ---

                // Se foi devolvido por infrigência -> PODE BLOQUEAR
                if (!is_null($mdCorExpedicaoSolicitadaDTO->getNumIdMdCorParamArInfrigencia())) {
                    continue;
                }

                // Verificar o Rastreamento para ver se já foi entregue ou extraviado
                $objExpedAndamentoRN = new MdCorExpedicaoAndamentoRN();
                $arrAndamentos = $objExpedAndamentoRN->getDadosAndamentosParaRastreio($mdCorExpedicaoSolicitadaDTO->getNumIdMdCorExpedicaoSolicitada());
                
                if (!empty($arrAndamentos)) {
                    $statusRastreio = $arrAndamentos[0]->getStrStaRastreioModulo();
                    // 'S' = Entregue, 'I' = Insucesso/Extraviado -> PODE BLOQUEAR
                    if ($statusRastreio == 'S' || $statusRastreio == 'I') {
                        continue;
                    }
                }

                // Se passar por todos os "continues" acima, significa que o ícone atual 
                // deste objeto é obrigatoriamente "AR enviado aguardando retorno".
                return true;
            }
        }
        return false;
    }

}
