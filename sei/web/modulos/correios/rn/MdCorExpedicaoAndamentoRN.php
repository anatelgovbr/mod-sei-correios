<?
/**
 * TRIBUNAL REGIONAL FEDERAL DA 4� REGI�O
 *
 * 07/06/2017 - criado por marcelo.cast
 *
 * Vers�o do Gerador de C�digo: 1.40.1
 */

require_once dirname(__FILE__) . '/../../../SEI.php';

class MdCorExpedicaoAndamentoRN extends InfraRN
{

    public function __construct()
    {
        parent::__construct();
    }

    protected function inicializarObjInfraIBanco()
    {
        return BancoSEI::getInstance();
    }

    private function validarDthDataHora(MdCorExpedicaoAndamentoDTO $objMdCorExpedicaoAndamentoDTO, InfraException $objInfraException)
    {
        if (InfraString::isBolVazia($objMdCorExpedicaoAndamentoDTO->getDthDataHora())) {
            $objInfraException->adicionarValidacao(' data n�o informada.');
        }
    }

    private function validarStrDescricao(MdCorExpedicaoAndamentoDTO $objMdCorExpedicaoAndamentoDTO, InfraException $objInfraException)
    {
        if (InfraString::isBolVazia($objMdCorExpedicaoAndamentoDTO->getStrDescricao())) {
            $objInfraException->adicionarValidacao(' descricao n�o informada.');
        } else {
            $objMdCorExpedicaoAndamentoDTO->setStrDescricao(trim($objMdCorExpedicaoAndamentoDTO->getStrDescricao()));

            if (strlen($objMdCorExpedicaoAndamentoDTO->getStrDescricao()) > 500) {
                $objInfraException->adicionarValidacao(' descricao possui tamanho superior a 500 caracteres.');
            }
        }
    }

    private function validarNumIdMdCorExpedicaoSolicitada(MdCorExpedicaoAndamentoDTO $objMdCorExpedicaoAndamentoDTO, InfraException $objInfraException)
    {
        if (InfraString::isBolVazia($objMdCorExpedicaoAndamentoDTO->getNumIdMdCorExpedicaoSolicitada())) {
            $objInfraException->adicionarValidacao(' idMdCor n�o informad.');
        }
    }

    private function validarStrDetalhe(MdCorExpedicaoAndamentoDTO $objMdCorExpedicaoAndamentoDTO, InfraException $objInfraException)
    {
        if (InfraString::isBolVazia($objMdCorExpedicaoAndamentoDTO->getStrDetalhe())) {
            $objInfraException->adicionarValidacao(' detalhe n�o informado.');
        } else {
            $objMdCorExpedicaoAndamentoDTO->setStrDetalhe(trim($objMdCorExpedicaoAndamentoDTO->getStrDetalhe()));

            if (strlen($objMdCorExpedicaoAndamentoDTO->getStrDetalhe()) > 500) {
                $objInfraException->adicionarValidacao('detalhe possui tamanho superior a 500 caracteres.');
            }
        }
    }

    private function validarStrStatus(MdCorExpedicaoAndamentoDTO $objMdCorExpedicaoAndamentoDTO, InfraException $objInfraException)
    {
        if (InfraString::isBolVazia($objMdCorExpedicaoAndamentoDTO->getNumStatus())) {
            $objInfraException->adicionarValidacao(' status n�o informado.');
        } else {
            $objMdCorExpedicaoAndamentoDTO->setNumStatus(trim($objMdCorExpedicaoAndamentoDTO->getNumStatus()));

            if (strlen($objMdCorExpedicaoAndamentoDTO->getNumStatus()) > 500) {
                $objInfraException->adicionarValidacao(' status possui tamanho superior a 500 caracteres.');
            }
        }
    }

    private function validarStrLocal(MdCorExpedicaoAndamentoDTO $objMdCorExpedicaoAndamentoDTO, InfraException $objInfraException)
    {
        if (InfraString::isBolVazia($objMdCorExpedicaoAndamentoDTO->getStrLocal())) {
            $objInfraException->adicionarValidacao(' local n�o informado.');
        } else {
            $objMdCorExpedicaoAndamentoDTO->setStrLocal(trim($objMdCorExpedicaoAndamentoDTO->getStrLocal()));

            if (strlen($objMdCorExpedicaoAndamentoDTO->getStrLocal()) > 250) {
                $objInfraException->adicionarValidacao(' local possui tamanho superior a 250 caracteres.');
            }
        }
    }

    private function validarStrCodigoCep(MdCorExpedicaoAndamentoDTO $objMdCorExpedicaoAndamentoDTO, InfraException $objInfraException)
    {
        if (InfraString::isBolVazia($objMdCorExpedicaoAndamentoDTO->getStrCodigoCep())) {
            $objInfraException->adicionarValidacao(' cep n�o informado.');
        } else {
            $objMdCorExpedicaoAndamentoDTO->setStrCodigoCep(trim($objMdCorExpedicaoAndamentoDTO->getStrCodigoCep()));

            if (strlen($objMdCorExpedicaoAndamentoDTO->getStrCodigoCep()) > 45) {
                $objInfraException->adicionarValidacao(' cep possui tamanho superior a 45 caracteres.');
            }
        }
    }

    private function validarStrCidade(MdCorExpedicaoAndamentoDTO $objMdCorExpedicaoAndamentoDTO, InfraException $objInfraException)
    {
        if (InfraString::isBolVazia($objMdCorExpedicaoAndamentoDTO->getStrCidade())) {
//            $objInfraException->adicionarValidacao($objMdCorExpedicaoAndamentoDTO->getNumIdMdCorExpedicaoSolicitada() . ' - cidade n�o informada.');
            $objMdCorExpedicaoAndamentoDTO->setStrCidade('Cidade n�o informada no Rastreamento do Objeto');
        } else {
            $objMdCorExpedicaoAndamentoDTO->setStrCidade(trim($objMdCorExpedicaoAndamentoDTO->getStrCidade()));

            if (strlen($objMdCorExpedicaoAndamentoDTO->getStrCidade()) > 250) {
                $objInfraException->adicionarValidacao(' cidade possui tamanho superior a 250 caracteres.');
            }
        }
    }

    private function validarStrUf(MdCorExpedicaoAndamentoDTO $objMdCorExpedicaoAndamentoDTO, InfraException $objInfraException)
    {
        if (InfraString::isBolVazia($objMdCorExpedicaoAndamentoDTO->getStrUf())) {
//            $objInfraException->adicionarValidacao(' uf n�o informada.');
            $objMdCorExpedicaoAndamentoDTO->setStrUf('UF n�o informada no Rastreamento do Objeto');
        } else {
            $objMdCorExpedicaoAndamentoDTO->setStrUf(trim($objMdCorExpedicaoAndamentoDTO->getStrUf()));

            if (strlen($objMdCorExpedicaoAndamentoDTO->getStrUf()) > 250) {
                $objInfraException->adicionarValidacao(' uf possui tamanho superior a 250 caracteres.');
            }
        }
    }

    private function validarStrVersaoSroXml(MdCorExpedicaoAndamentoDTO $objMdCorExpedicaoAndamentoDTO, InfraException $objInfraException)
    {
        if (InfraString::isBolVazia($objMdCorExpedicaoAndamentoDTO->getStrVersaoSroXml())) {
            $objInfraException->adicionarValidacao('versao n�o informada.');
        } else {
            $objMdCorExpedicaoAndamentoDTO->setStrVersaoSroXml(trim($objMdCorExpedicaoAndamentoDTO->getStrVersaoSroXml()));

            if (strlen($objMdCorExpedicaoAndamentoDTO->getStrVersaoSroXml()) > 150) {
                $objInfraException->adicionarValidacao('versao possui tamanho superior a 150 caracteres.');
            }
        }
    }

    private function validarStrSiglaObjeto(MdCorExpedicaoAndamentoDTO $objMdCorExpedicaoAndamentoDTO, InfraException $objInfraException)
    {
        if (InfraString::isBolVazia($objMdCorExpedicaoAndamentoDTO->getStrSiglaObjeto())) {
            $objInfraException->adicionarValidacao(' sigla n�o informada.');
        } else {
            $objMdCorExpedicaoAndamentoDTO->setStrSiglaObjeto(trim($objMdCorExpedicaoAndamentoDTO->getStrSiglaObjeto()));

            if (strlen($objMdCorExpedicaoAndamentoDTO->getStrSiglaObjeto()) > 250) {
                $objInfraException->adicionarValidacao(' sigla possui tamanho superior a 250 caracteres.');
            }
        }
    }

    private function validarStrNomeObjeto(MdCorExpedicaoAndamentoDTO $objMdCorExpedicaoAndamentoDTO, InfraException $objInfraException)
    {
        if (InfraString::isBolVazia($objMdCorExpedicaoAndamentoDTO->getStrNomeObjeto())) {
            $objInfraException->adicionarValidacao(' nome n�o informado.');
        } else {
            $objMdCorExpedicaoAndamentoDTO->setStrNomeObjeto(trim($objMdCorExpedicaoAndamentoDTO->getStrNomeObjeto()));

            if (strlen($objMdCorExpedicaoAndamentoDTO->getStrNomeObjeto()) > 250) {
                $objInfraException->adicionarValidacao(' nome possui tamanho superior a 250 caracteres.');
            }
        }
    }

    private function validarStrCategoriaObjeto(MdCorExpedicaoAndamentoDTO $objMdCorExpedicaoAndamentoDTO, InfraException $objInfraException)
    {
        if (InfraString::isBolVazia($objMdCorExpedicaoAndamentoDTO->getStrCategoriaObjeto())) {
            $objInfraException->adicionarValidacao(' categoria n�o informada.');
        } else {
            $objMdCorExpedicaoAndamentoDTO->setStrCategoriaObjeto(trim($objMdCorExpedicaoAndamentoDTO->getStrCategoriaObjeto()));

            if (strlen($objMdCorExpedicaoAndamentoDTO->getStrCategoriaObjeto()) > 250) {
                $objInfraException->adicionarValidacao(' categoria possui tamanho superior a 250 caracteres.');
            }
        }
    }

    protected function cadastrarConectado(MdCorExpedicaoAndamentoDTO $objMdCorExpedicaoAndamentoDTO)
    {
        try {
            //Valida Permissao
            SessaoSEI::getInstance()->validarPermissao('md_cor_expedicao_andamento_cadastrar');

            //Regras de Negocio
            $objInfraException = new InfraException();

            $this->validarDthDataHora($objMdCorExpedicaoAndamentoDTO, $objInfraException);
            $this->validarStrDescricao($objMdCorExpedicaoAndamentoDTO, $objInfraException);
            $this->validarNumIdMdCorExpedicaoSolicitada($objMdCorExpedicaoAndamentoDTO, $objInfraException);
            $this->validarStrStatus($objMdCorExpedicaoAndamentoDTO, $objInfraException);
            $this->validarStrLocal($objMdCorExpedicaoAndamentoDTO, $objInfraException);
            $this->validarStrCidade($objMdCorExpedicaoAndamentoDTO, $objInfraException);
            $this->validarStrUf($objMdCorExpedicaoAndamentoDTO, $objInfraException);
            $this->validarStrCodigoCep($objMdCorExpedicaoAndamentoDTO, $objInfraException);
            $this->validarStrVersaoSroXml($objMdCorExpedicaoAndamentoDTO, $objInfraException);
            $this->validarStrSiglaObjeto($objMdCorExpedicaoAndamentoDTO, $objInfraException);
            $this->validarStrNomeObjeto($objMdCorExpedicaoAndamentoDTO, $objInfraException);
            $this->validarStrCategoriaObjeto($objMdCorExpedicaoAndamentoDTO, $objInfraException);

            $objInfraException->lancarValidacoes();

            $objMdCorExpedicaoAndamentoBD = new MdCorExpedicaoAndamentoBD($this->getObjInfraIBanco());
            $ret = $objMdCorExpedicaoAndamentoBD->cadastrar($objMdCorExpedicaoAndamentoDTO);

            //Auditoria

            return $ret;

        } catch (Exception $e) {
            throw new InfraException('Erro cadastrando .', $e);
        }
    }

    protected function alterarControlado(MdCorExpedicaoAndamentoDTO $objMdCorExpedicaoAndamentoDTO)
    {
        try {

            //Valida Permissao
            SessaoSEI::getInstance()->validarPermissao('md_cor_expedicao_andamento_alterar');

            //Regras de Negocio
            $objInfraException = new InfraException();

            if ($objMdCorExpedicaoAndamentoDTO->isSetDthDataHora()) {
                $this->validarDthDataHora($objMdCorExpedicaoAndamentoDTO, $objInfraException);
            }
            if ($objMdCorExpedicaoAndamentoDTO->isSetStrDescricao()) {
                $this->validarStrDescricao($objMdCorExpedicaoAndamentoDTO, $objInfraException);
            }
            if ($objMdCorExpedicaoAndamentoDTO->isSetNumIdMdCorExpedicaoSolicitada()) {
                $this->validarNumIdMdCorExpedicaoSolicitada($objMdCorExpedicaoAndamentoDTO, $objInfraException);
            }
            if ($objMdCorExpedicaoAndamentoDTO->isSetStrDetalhe()) {
                $this->validarStrDetalhe($objMdCorExpedicaoAndamentoDTO, $objInfraException);
            }
            if ($objMdCorExpedicaoAndamentoDTO->isSetNumStatus()) {
                $this->validarStrStatus($objMdCorExpedicaoAndamentoDTO, $objInfraException);
            }
            if ($objMdCorExpedicaoAndamentoDTO->isSetStrLocal()) {
                $this->validarStrLocal($objMdCorExpedicaoAndamentoDTO, $objInfraException);
            }
            if ($objMdCorExpedicaoAndamentoDTO->isSetStrCodigoCep()) {
                $this->validarStrCodigoCep($objMdCorExpedicaoAndamentoDTO, $objInfraException);
            }
            if ($objMdCorExpedicaoAndamentoDTO->isSetStrCidade()) {
                $this->validarStrCidade($objMdCorExpedicaoAndamentoDTO, $objInfraException);
            }
            if ($objMdCorExpedicaoAndamentoDTO->isSetStrUf()) {
                $this->validarStrUf($objMdCorExpedicaoAndamentoDTO, $objInfraException);
            }
            if ($objMdCorExpedicaoAndamentoDTO->isSetStrVersaoSroXml()) {
                $this->validarStrVersaoSroXml($objMdCorExpedicaoAndamentoDTO, $objInfraException);
            }
            if ($objMdCorExpedicaoAndamentoDTO->isSetStrSiglaObjeto()) {
                $this->validarStrSiglaObjeto($objMdCorExpedicaoAndamentoDTO, $objInfraException);
            }
            if ($objMdCorExpedicaoAndamentoDTO->isSetStrNomeObjeto()) {
                $this->validarStrNomeObjeto($objMdCorExpedicaoAndamentoDTO, $objInfraException);
            }
            if ($objMdCorExpedicaoAndamentoDTO->isSetStrCategoriaObjeto()) {
                $this->validarStrCategoriaObjeto($objMdCorExpedicaoAndamentoDTO, $objInfraException);
            }

            $objInfraException->lancarValidacoes();

            $objMdCorExpedicaoAndamentoBD = new MdCorExpedicaoAndamentoBD($this->getObjInfraIBanco());
            $objMdCorExpedicaoAndamentoBD->alterar($objMdCorExpedicaoAndamentoDTO);

            //Auditoria

        } catch (Exception $e) {
            throw new InfraException('Erro alterando .', $e);
        }
    }

    protected function excluirControlado($arrObjMdCorExpedicaoAndamentoDTO)
    {
        try {

            //Valida Permissao
            SessaoSEI::getInstance()->validarPermissao('md_cor_expedicao_andamento_excluir');

            //Regras de Negocio
            //$objInfraException = new InfraException();

            //$objInfraException->lancarValidacoes();

            $objMdCorExpedicaoAndamentoBD = new MdCorExpedicaoAndamentoBD($this->getObjInfraIBanco());
            for ($i = 0; $i < count($arrObjMdCorExpedicaoAndamentoDTO); $i++) {
                $objMdCorExpedicaoAndamentoBD->excluir($arrObjMdCorExpedicaoAndamentoDTO[$i]);
            }

            //Auditoria

        } catch (Exception $e) {
            throw new InfraException('Erro excluindo .', $e);
        }
    }

    protected function consultarConectado(MdCorExpedicaoAndamentoDTO $objMdCorExpedicaoAndamentoDTO)
    {
        try {

            //Valida Permissao
            SessaoSEI::getInstance()->validarPermissao('md_cor_expedicao_andamento_consultar');

            //Regras de Negocio
            //$objInfraException = new InfraException();

            //$objInfraException->lancarValidacoes();

            $objMdCorExpedicaoAndamentoBD = new MdCorExpedicaoAndamentoBD($this->getObjInfraIBanco());
            $ret = $objMdCorExpedicaoAndamentoBD->consultar($objMdCorExpedicaoAndamentoDTO);

            //Auditoria

            return $ret;
        } catch (Exception $e) {
            throw new InfraException('Erro consultando .', $e);
        }
    }

    protected function listarConectado(MdCorExpedicaoAndamentoDTO $objMdCorExpedicaoAndamentoDTO)
    {
        try {

            //Valida Permissao
            SessaoSEI::getInstance()->validarPermissao('md_cor_expedicao_andamento_listar');

            //Regras de Negocio
            //$objInfraException = new InfraException();

            //$objInfraException->lancarValidacoes();

            $objMdCorExpedicaoAndamentoBD = new MdCorExpedicaoAndamentoBD($this->getObjInfraIBanco());
            $ret = $objMdCorExpedicaoAndamentoBD->listar($objMdCorExpedicaoAndamentoDTO);

            //Auditoria

            return $ret;

        } catch (Exception $e) {
            throw new InfraException('Erro listando .', $e);
        }
    }

    protected function contarConectado(MdCorExpedicaoAndamentoDTO $objMdCorExpedicaoAndamentoDTO)
    {
        try {

            //Valida Permissao
            SessaoSEI::getInstance()->validarPermissao('md_cor_expedicao_andamento_listar');

            //Regras de Negocio
            //$objInfraException = new InfraException();

            //$objInfraException->lancarValidacoes();

            $objMdCorExpedicaoAndamentoBD = new MdCorExpedicaoAndamentoBD($this->getObjInfraIBanco());
            $ret = $objMdCorExpedicaoAndamentoBD->contar($objMdCorExpedicaoAndamentoDTO);

            //Auditoria

            return $ret;
        } catch (Exception $e) {
            throw new InfraException('Erro contando .', $e);
        }
    }

    protected function buscarUltimoAndamentoConectado($idMdCorExpedicaoSolicitada)
    {
        $objMdCorExpedicaoAndamentoDTO = new MdCorExpedicaoAndamentoDTO();
        $objMdCorExpedicaoAndamentoDTO->retTodos();
        $objMdCorExpedicaoAndamentoDTO->setNumIdMdCorExpedicaoSolicitada($idMdCorExpedicaoSolicitada);
        $objMdCorExpedicaoAndamentoDTO->setOrdDthDataHora(InfraDTO::$TIPO_ORDENACAO_DESC);
        $objMdCorExpedicaoAndamentoDTO->setNumMaxRegistrosRetorno(1);

        $objMdCorExpedicaoAndamentoRN = new MdCorExpedicaoAndamentoRN();
        return $objMdCorExpedicaoAndamentoRN->consultar($objMdCorExpedicaoAndamentoDTO);
    }


    protected function salvarAndamentoConectado($idProcedimento = null)
    {
        $objMdCorParametroRastreioRN = new MdCorParametroRastreioRN();
        $objMdCorParametroRastreioDTO = new MdCorParametroRastreioDTO();
        $objMdCorParametroRastreioDTO->retStrUsuario();
        $objMdCorParametroRastreioDTO->retStrSenha();
        $objMdCorParametroRastreioDTO->retStrEnderecoWsdl();

        $objMdCorParametroRastreioDTO = $objMdCorParametroRastreioRN->consultar($objMdCorParametroRastreioDTO);

        if (is_null($objMdCorParametroRastreioDTO)) {
            $objInfraException = new InfraException();
            $objInfraException->adicionarValidacao(' Parametros de rastreio n�o cadastrados.');
            $objInfraException->lancarValidacoes();
        }

        $arrParametro = [
            'resultado' => 'T',
            'endpoint' => $objMdCorParametroRastreioDTO->getStrEnderecoWsdl(),
            'usuario' => $objMdCorParametroRastreioDTO->getStrUsuario(),
            'senha' => $objMdCorParametroRastreioDTO->getStrSenha(),
        ];

        $objMdCorExpedicaoSolicitadaRN = new MdCorExpedicaoSolicitadaRN();
        $objMdCorWsRastreio = new MdCorWsRastreioRN($arrParametro);

        if ($_GET['acao'] == 'md_cor_expedicao_unidade_listar') {
            $objMdCorExpedicaoSolicitadaRN->setIsConsultarExpedicaoAndamento(true);
        }

        $arrObjMdCorExpedicaoSolicitadaDTO = $objMdCorExpedicaoSolicitadaRN->listarExpedicaoSolicitadaExpedida($idProcedimento);
        $arrFalha = array();

        if (!is_null($arrObjMdCorExpedicaoSolicitadaDTO)) {
            $mdCorListaStatusRN = new MdCorListaStatusRN();

            foreach ($arrObjMdCorExpedicaoSolicitadaDTO as $objMdCorExpedicaoSolicitadaDTO) {
                $idMdCorExpedicaoSolicitada = $objMdCorExpedicaoSolicitadaDTO->getNumIdMdCorExpedicaoSolicitada();
                $codigoRastreamento = trim($objMdCorExpedicaoSolicitadaDTO->getStrCodigoRastreamento());

                $objMdCorExpedicaoAndamentoDTO = $this->buscarUltimoAndamento($idMdCorExpedicaoSolicitada);

                //Se � o primeiro andamento
                if (!$objMdCorExpedicaoAndamentoDTO) {
                    $dadosRastreio = $objMdCorWsRastreio->rastrearObjeto($codigoRastreamento);

                    //Se n�o deu erro no rastreio
                    if (!isset($dadosRastreio['objeto']['erro'])) {
                        $this->_criarObjetoAndamento($idMdCorExpedicaoSolicitada, $dadosRastreio);
                    } else {
                        $arrFalha[] = $dadosRastreio['objeto'];
                    }
                } else {

                    $staRastreioModulo = $mdCorListaStatusRN->getStaRastreioModuloAndamento($objMdCorExpedicaoAndamentoDTO);

                    if ($staRastreioModulo == 'P' || $staRastreioModulo == 'T') {
                        $dadosRastreio = $objMdCorWsRastreio->rastrearObjeto($codigoRastreamento);

                        //Se n�o deu erro no rastreio
                        if (!isset($dadosRastreio['objeto']['erro'])) {
                            $arrEvento = $dadosRastreio['objeto']['evento'];

                            $objMdCorListaStatusRN = new MdCorListaStatusRN();
                            $objMdCorListaStatusRN->_retornaTipoStatusNaoSalvos($arrEvento);

                            if (!is_null($arrEvento)) {
                                $dtUltimoAndamento = $objMdCorExpedicaoAndamentoDTO->getDthDataHora();
                                $arrEventoNaoSalvo = $this->_retornaEventoNaoSalvo($arrEvento, $dtUltimoAndamento);

                                //Salva todos os andamentos que ainda n�o est�o no banco
                                if (!empty($arrEventoNaoSalvo)) {
                                    $dadosRastreio['objeto']['evento'] = $arrEventoNaoSalvo;
                                    $this->_criarObjetoAndamento($idMdCorExpedicaoSolicitada, $dadosRastreio);
                                }
                            }

                        } else {
                            $arrFalha[] = $dadosRastreio['objeto'];
                        }
                    }
                }
            }
        }

        return $arrFalha;

    }

    private function _retornaEventoNaoSalvo($arrEvento, $dtUltimoAndamento)
    {

        $arrEventoNaoSalvo = array();
        $arrData = explode(' ', $dtUltimoAndamento);
        $data = implode('-', array_reverse(explode('/', $arrData[0])));;
        $hora = $arrData[1];
        $dataHoraAndamento = strtotime($data . ' ' . $hora);

        if (isset($arrEvento['data'])) {
            $dataHoraWs = $this->_converterDataHoraWs($arrEvento);
            if ($dataHoraAndamento < $dataHoraWs) {
                $arrEventoNaoSalvo[] = $arrEvento;
            }

        } else {
            foreach ($arrEvento as $evento) {
                $dataHoraWs = $this->_converterDataHoraWs($evento);
                if ($dataHoraAndamento < $dataHoraWs) {
                    $arrEventoNaoSalvo[] = $evento;
                }

            }
        }

        return $arrEventoNaoSalvo;

    }

    private function _converterDataHoraWs($evento)
    {
        $data = implode('-', array_reverse(explode('/', $evento['data'])));
        $hora = $evento['hora'] . ':00';
        return strtotime($data . ' ' . $hora);
    }

    private function _criarObjetoAndamento($idMdCorExpedicaoSolicitada, $dadosRastreio)
    {

        $siglaObjeto = trim($dadosRastreio['objeto']['sigla']);
        $nomeObjeto = trim($dadosRastreio['objeto']['nome']);
        $categoriaObjeto = trim($dadosRastreio['objeto']['categoria']);
        $versao = trim($dadosRastreio['versao']);


        //Ajuste para quando vir somente um evento
        if (isset($dadosRastreio['objeto']['evento']['tipo'])) {
            $arrEventos = $dadosRastreio['objeto']['evento'];
            unset($dadosRastreio['objeto']['evento']);
            $dadosRastreio['objeto']['evento'][] = $arrEventos;
        }

        $dtUltimaAtualizacao = date('d/m/Y H:i:s');
        if (!empty($dadosRastreio['objeto']['evento']) && is_array($dadosRastreio['objeto']['evento'])){
            foreach ($dadosRastreio['objeto']['evento'] as $evento) {

                $tipo = $evento['tipo'];
                $data = $evento['data'];
                $hora = $evento['hora'] . ':00';
                $dataHora = $data . ' ' . $hora;

                $descricao = trim(@$evento['descricao']);
                $detalhe = trim(@$evento['detalhe']);
                $status = trim(@$evento['status']);
                $local = trim(@$evento['local']);
                $codigoCep = trim(@$evento['codigo']);
                $cidade = trim(@$evento['cidade']);
                $uf = trim(@$evento['uf']);


                $objMdCorExpedicaoAndamentoDTO = new MdCorExpedicaoAndamentoDTO();
                $objMdCorExpedicaoAndamentoDTO->setNumIdMdCorExpedicaoSolicitada($idMdCorExpedicaoSolicitada);
                $objMdCorExpedicaoAndamentoDTO->setDthDataHora($dataHora);

                $objMdCorExpedicaoAndamentoDTO->setDthDataUltimaAtualizacao($dtUltimaAtualizacao);

                $objMdCorExpedicaoAndamentoDTO->setStrDescricao($descricao);
                $objMdCorExpedicaoAndamentoDTO->setStrDetalhe($detalhe);
                $objMdCorExpedicaoAndamentoDTO->setNumStatus($status);
                $objMdCorExpedicaoAndamentoDTO->setStrLocal($local);
                $objMdCorExpedicaoAndamentoDTO->setStrCodigoCep($codigoCep);
                $objMdCorExpedicaoAndamentoDTO->setStrCidade($cidade);
                $objMdCorExpedicaoAndamentoDTO->setStrUf($uf);
                $objMdCorExpedicaoAndamentoDTO->setStrVersaoSroXml($versao);
                $objMdCorExpedicaoAndamentoDTO->setStrSiglaObjeto($siglaObjeto);
                $objMdCorExpedicaoAndamentoDTO->setStrNomeObjeto($nomeObjeto);
                $objMdCorExpedicaoAndamentoDTO->setStrCategoriaObjeto($categoriaObjeto);
                $objMdCorExpedicaoAndamentoDTO->setStrTipo($tipo);
                $this->cadastrar($objMdCorExpedicaoAndamentoDTO);
            }
        }
    }

    private function _getAndamentosSolicitacao($idSol)
    {
        $objMdCorExpedicaoAndamentoDTO = new MdCorExpedicaoAndamentoDTO();
        $objMdCorExpedicaoAndamentoDTO->setNumIdMdCorExpedicaoSolicitada($idSol);
        $objMdCorExpedicaoAndamentoDTO->setOrdDthDataHora(InfraDTO::$TIPO_ORDENACAO_DESC);
        $objMdCorExpedicaoAndamentoDTO->retStrCidade();
        $objMdCorExpedicaoAndamentoDTO->retStrUf();
        $objMdCorExpedicaoAndamentoDTO->retDthDataHora();
        $objMdCorExpedicaoAndamentoDTO->retDthDataUltimaAtualizacao();
        $objMdCorExpedicaoAndamentoDTO->retStrDescricao();
        $objMdCorExpedicaoAndamentoDTO->retStrDetalhe();
        $objMdCorExpedicaoAndamentoDTO->retNumStatus();
        $objMdCorExpedicaoAndamentoDTO->retStrTipo();

        $count = $this->contar($objMdCorExpedicaoAndamentoDTO);

        if ($count > 0) {
            return $this->listar($objMdCorExpedicaoAndamentoDTO);
        }

        return null;
    }

    protected function getDadosAndamentosParaRastreioConectado($idSol)
    {
        $objMdCorListaStatusRN = new MdCorListaStatusRN();

        $status = '';
        $arrObjMdCorExpAndamentoDTO = $this->_getAndamentosSolicitacao($idSol);
        if (!is_null($arrObjMdCorExpAndamentoDTO)) {
            $objUltimoAndamentoDTO = current($arrObjMdCorExpAndamentoDTO);
            $status = $objMdCorListaStatusRN->getStaRastreioModuloAndamento($objUltimoAndamentoDTO);
            $arrObjMdCorExpAndamentoDTO[0]->setStrStaRastreioModulo($status);
            return $arrObjMdCorExpAndamentoDTO;
        }

        return null;
    }

    /*
      protected function desativarControlado($arrObjMdCorExpedicaoAndamentoDTO){
        try {

          //Valida Permissao
          SessaoSEI::getInstance()->validarPermissao('md_cor_expedicao_andamento_desativar');

          //Regras de Negocio
          //$objInfraException = new InfraException();

          //$objInfraException->lancarValidacoes();

          $objMdCorExpedicaoAndamentoBD = new MdCorExpedicaoAndamentoBD($this->getObjInfraIBanco());
          for($i=0;$i<count($arrObjMdCorExpedicaoAndamentoDTO);$i++){
            $objMdCorExpedicaoAndamentoBD->desativar($arrObjMdCorExpedicaoAndamentoDTO[$i]);
          }

          //Auditoria

        }catch(Exception $e){
          throw new InfraException('Erro desativando .',$e);
        }
      }

      protected function reativarControlado($arrObjMdCorExpedicaoAndamentoDTO){
        try {

          //Valida Permissao
          SessaoSEI::getInstance()->validarPermissao('md_cor_expedicao_andamento_reativar');

          //Regras de Negocio
          //$objInfraException = new InfraException();

          //$objInfraException->lancarValidacoes();

          $objMdCorExpedicaoAndamentoBD = new MdCorExpedicaoAndamentoBD($this->getObjInfraIBanco());
          for($i=0;$i<count($arrObjMdCorExpedicaoAndamentoDTO);$i++){
            $objMdCorExpedicaoAndamentoBD->reativar($arrObjMdCorExpedicaoAndamentoDTO[$i]);
          }

          //Auditoria

        }catch(Exception $e){
          throw new InfraException('Erro reativando .',$e);
        }
      }

      protected function bloquearControlado(MdCorExpedicaoAndamentoDTO $objMdCorExpedicaoAndamentoDTO){
        try {

          //Valida Permissao
          SessaoSEI::getInstance()->validarPermissao('md_cor_expedicao_andamento_consultar');

          //Regras de Negocio
          //$objInfraException = new InfraException();

          //$objInfraException->lancarValidacoes();

          $objMdCorExpedicaoAndamentoBD = new MdCorExpedicaoAndamentoBD($this->getObjInfraIBanco());
          $ret = $objMdCorExpedicaoAndamentoBD->bloquear($objMdCorExpedicaoAndamentoDTO);

          //Auditoria

          return $ret;
        }catch(Exception $e){
          throw new InfraException('Erro bloqueando .',$e);
        }
      }

     */
}

?>
