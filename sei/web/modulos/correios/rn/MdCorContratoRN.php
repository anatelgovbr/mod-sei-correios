<?

/**
 * TRIBUNAL REGIONAL FEDERAL DA 4ª REGIÃO
 *
 * 22/12/2016 - criado por Wilton Júnior
 *
 * Versão do Gerador de Código: 1.39.0
 */
require_once dirname(__FILE__) . '/../../../SEI.php';

class MdCorContratoRN extends InfraRN
{

    public function __construct()
    {
        parent::__construct();
    }

    protected function inicializarObjInfraIBanco()
    {
        return BancoSEI::getInstance();
    }

    private function validarStrNumeroContrato(MdCorContratoDTO $objMdCorContratoDTO, InfraException $objInfraException)
    {
        if (InfraString::isBolVazia($objMdCorContratoDTO->getStrNumeroContrato())) {
            $objInfraException->adicionarValidacao('Número do Contrato no Órgão não informado.');
        } else {
            $objMdCorContratoDTO->setStrNumeroContrato(trim($objMdCorContratoDTO->getStrNumeroContrato()));

            if (strlen($objMdCorContratoDTO->getStrNumeroContrato()) > 50) {
                $objInfraException->adicionarValidacao('Número do Contrato no Órgão possui tamanho superior a 50 caracteres.');
            }
        }
    }

    private function validarStrNumeroContratoCorreio(MdCorContratoDTO $objMdCorContratoDTO, InfraException $objInfraException)
    {
        if (InfraString::isBolVazia($objMdCorContratoDTO->getStrNumeroContratoCorreio())) {
            $objInfraException->adicionarValidacao('Número do Contrato nos Correios não informado.');
        } else {
            $objMdCorContratoDTO->setStrNumeroContratoCorreio(trim($objMdCorContratoDTO->getStrNumeroContratoCorreio()));

            if (strlen($objMdCorContratoDTO->getStrNumeroContratoCorreio()) > 50) {
                $objInfraException->adicionarValidacao('Número do Contrato nos Correios possui tamanho superior a 50 caracteres.');
            }
        }
    }

    private function validarStrNumeroCartaoPostagem(MdCorContratoDTO $objMdCorContratoDTO, InfraException $objInfraException)
    {
        if (InfraString::isBolVazia($objMdCorContratoDTO->getStrNumeroCartaoPostagem())) {
            $objInfraException->adicionarValidacao('Cartão de Postagem não informado.');
        } else {
            $objMdCorContratoDTO->setStrNumeroCartaoPostagem(trim($objMdCorContratoDTO->getStrNumeroCartaoPostagem()));

            if (strlen($objMdCorContratoDTO->getStrNumeroCartaoPostagem()) > 50) {
                $objInfraException->adicionarValidacao('Cartão de Postagem possui tamanho superior a 50 caracteres.');
            }
        }
    }

    private function validarStrUrlWebservice(MdCorContratoDTO $objMdCorContratoDTO, InfraException $objInfraException)
    {
        if (InfraString::isBolVazia($objMdCorContratoDTO->getStrUrlWebservice())) {
            $objInfraException->adicionarValidacao('Endereço WSDL do Webservice do SIGEP WEB não informada.');
        } else {
            $objMdCorContratoDTO->setStrUrlWebservice(trim($objMdCorContratoDTO->getStrUrlWebservice()));

            if (strlen($objMdCorContratoDTO->getStrUrlWebservice()) > 2081) {
                $objInfraException->adicionarValidacao('Endereço WSDL do Webservice do SIGEP WEB possui tamanho superior a 2081 caracteres.');
            }
        }
    }

    private function validarDblIdProcedimento(MdCorContratoDTO $objMdCorContratoDTO, InfraException $objInfraException)
    {
        if (InfraString::isBolVazia($objMdCorContratoDTO->getDblIdProcedimento())) {
            $objMdCorContratoDTO->setDblIdProcedimento(null);
        } else {
            $objMdCorContratoDTO->setDblIdProcedimento(trim($objMdCorContratoDTO->getDblIdProcedimento()));

            if (strlen($objMdCorContratoDTO->getDblIdProcedimento()) > 50) {
                $objInfraException->adicionarValidacao('Número do Processo de Contratação possui tamanho superior a 50 caracteres.');
            }
        }
    }

    private function validarStrSinAtivo(MdCorContratoDTO $objMdCorContratoDTO, InfraException $objInfraException)
    {
        if (InfraString::isBolVazia($objMdCorContratoDTO->getStrSinAtivo())) {
            $objInfraException->adicionarValidacao('Sinalizador de Exclusão Lógica não informado.');
        } else {
            if (!InfraUtil::isBolSinalizadorValido($objMdCorContratoDTO->getStrSinAtivo())) {
                $objInfraException->adicionarValidacao('Sinalizador de Exclusão Lógica inválido.');
            }
        }
    }

    protected function cadastrarControlado($arr)
    {
        try {
            //Valida Permissao
            SessaoSEI::getInstance()->validarPermissao('md_cor_contrato_cadastrar');

            $objMdCorContratoDTO = new MdCorContratoDTO();
            $objMdCorContratoDTO->setNumIdMdCorContrato(null);
            $objMdCorContratoDTO->setStrNumeroContrato($arr['txtNumeroContrato']);
            $objMdCorContratoDTO->setStrNumeroContratoCorreio($arr['txtNumeroContratoCorreio']);
            $objMdCorContratoDTO->setStrNumeroCartaoPostagem($arr['txtNumeroCartaoPostagem']);
            $objMdCorContratoDTO->setStrUrlWebservice($arr['txtUrlWebservice']);
            $objMdCorContratoDTO->setDblIdProcedimento($arr['hdnIdProcedimento']);
            $objMdCorContratoDTO->setStrSinAtivo('S');

            $objMdCorContratoDTO->setNumNumeroCnpj(InfraUtil::retirarFormatacao($_POST['txtCNPJ']));
            $objMdCorContratoDTO->setStrUsuario($_POST['txtUsuario']);
            $objMdCorContratoDTO->setStrSenha($_POST['txtSenha']);
            $objMdCorContratoDTO->setNumIdMdCorDiretoria($_POST['slCodigoDiretoria']);
            $objMdCorContratoDTO->setStrCodigoAdministrativo($_POST['txtCodigoAdministrativo']);
            $objMdCorContratoDTO->setNumAnoContratoCorreio($arr['txtNumeroAnoContratoCorreio']);

            //Regras de Negocio
            $objInfraException = new InfraException();

            $this->validarStrNumeroContrato($objMdCorContratoDTO, $objInfraException);
            $this->validarStrNumeroContratoCorreio($objMdCorContratoDTO, $objInfraException);
            $this->validarStrNumeroCartaoPostagem($objMdCorContratoDTO, $objInfraException);
            $this->validarStrUrlWebservice($objMdCorContratoDTO, $objInfraException);
            $this->validarDblIdProcedimento($objMdCorContratoDTO, $objInfraException);
            $this->validarStrSinAtivo($objMdCorContratoDTO, $objInfraException);

            $objInfraException->lancarValidacoes();

            $objMdCorContratoBD = new MdCorContratoBD($this->getObjInfraIBanco());
            $ret = $objMdCorContratoBD->cadastrar($objMdCorContratoDTO);

            $objMdCorServicoPostalRN = new MdCorServicoPostalRN();
            foreach ($arr['ar'] as $i => $ar) {
                $cobrar = isset($arr['cobrar'][$i]) ? $arr['cobrar'][$i] : 'N';
                $objMdCorServicoPostalDTO = new MdCorServicoPostalDTO();
                $objMdCorServicoPostalDTO->setNumIdMdCorContrato($objMdCorContratoDTO->getNumIdMdCorContrato());
                $objMdCorServicoPostalDTO->setStrIdWsCorreios($arr['id'][$i]);
                $objMdCorServicoPostalDTO->setStrCodigoWsCorreios($arr['codigo'][$i]);
                $objMdCorServicoPostalDTO->setStrNome($arr['nome'][$i]);
                $objMdCorServicoPostalDTO->setStrExpedicaoAvisoRecebimento($arr['ar'][$i]);
                $objMdCorServicoPostalDTO->setStrDescricao($arr['descricao'][$i]);
                $objMdCorServicoPostalDTO->setStrSinAtivo('S');
                $objMdCorServicoPostalDTO->setStrSinServicoCobrar($cobrar);
                $objMdCorContratoDTO->setNumNumeroCnpj(InfraUtil::retirarFormatacao($arr['txtCNPJ']));
                $objMdCorContratoDTO->setStrUsuario($arr['txtUsuario']);
                $objMdCorContratoDTO->setStrSenha($arr['txtSenha']);

                $arrTipoCorrespondencia = explode('|', $arr['sl_tipo'][$i]);
                $tipoCorrespondencia = current($arrTipoCorrespondencia);
                $objMdCorServicoPostalDTO->setNumIdMdCorTipoCorrespondencia($tipoCorrespondencia);
                $sinAr = end($arrTipoCorrespondencia);

                $objMdCorServicoPostalDTO->setStrExpedicaoAvisoRecebimento($arr['ar'][$i]);
                if ($sinAr == 'N') {
                    $objMdCorServicoPostalDTO->setStrExpedicaoAvisoRecebimento('N');
                }

                $objMdCorServicoPostalRN->cadastrar($objMdCorServicoPostalDTO);
            }

            return $ret;
        } catch (Exception $e) {
            throw new InfraException('Erro cadastrando contrato.', $e);
        }
    }

    protected function alterarControlado($arr)
    {
        try {
            SessaoSEI::getInstance()->validarPermissao('md_cor_contrato_alterar');

            $objMdCorContratoDTO = new MdCorContratoDTO();
            $objMdCorContratoDTO->setNumIdMdCorContrato($arr['hdnIdMdCorContrato']);
            $objMdCorContratoDTO->setStrNumeroContrato($arr['txtNumeroContrato']);
            $objMdCorContratoDTO->setStrNumeroContratoCorreio($arr['txtNumeroContratoCorreio']);
            $objMdCorContratoDTO->setStrNumeroCartaoPostagem($arr['txtNumeroCartaoPostagem']);
            $objMdCorContratoDTO->setStrUrlWebservice($arr['txtUrlWebservice']);
            $objMdCorContratoDTO->setDblIdProcedimento($arr['hdnIdProcedimento']);
            $objMdCorContratoDTO->setNumNumeroCnpj(InfraUtil::retirarFormatacao($arr['txtCNPJ']));
            $objMdCorContratoDTO->setStrUsuario($arr['txtUsuario']);
            $objMdCorContratoDTO->setStrSenha($arr['txtSenha']);
            $objMdCorContratoDTO->setNumIdMdCorDiretoria($arr['slCodigoDiretoria']);
            $objMdCorContratoDTO->setStrCodigoAdministrativo($arr['txtCodigoAdministrativo']);
            $objMdCorContratoDTO->setStrSinAtivo('S');
            $objMdCorContratoDTO->setNumAnoContratoCorreio($arr['txtNumeroAnoContratoCorreio']);


            /*
             * Trata os id's a serem desativados/reativados vindo do post.
             */
            $arr['hdnListaContratoServicosDesativados'] = explode(",", $arr['hdnListaContratoServicosDesativados'][0]);
            $arr['hdnListaContratoServicosReativadas'] = explode(",", $arr['hdnListaContratoServicosReativadas'][0]);

            //Regras de Negocio
            $objInfraException = new InfraException();

            if ($objMdCorContratoDTO->isSetStrNumeroContrato()) {
                $this->validarStrNumeroContrato($objMdCorContratoDTO, $objInfraException);
            }
            if ($objMdCorContratoDTO->isSetStrNumeroContratoCorreio()) {
                $this->validarStrNumeroContratoCorreio($objMdCorContratoDTO, $objInfraException);
            }
            if ($objMdCorContratoDTO->isSetStrNumeroCartaoPostagem()) {
                $this->validarStrNumeroCartaoPostagem($objMdCorContratoDTO, $objInfraException);
            }
            if ($objMdCorContratoDTO->isSetStrUrlWebservice()) {
                $this->validarStrUrlWebservice($objMdCorContratoDTO, $objInfraException);
            }
            if ($objMdCorContratoDTO->isSetDblIdProcedimento()) {
                $this->validarDblIdProcedimento($objMdCorContratoDTO, $objInfraException);
            }
            if ($objMdCorContratoDTO->isSetStrSinAtivo()) {
                $this->validarStrSinAtivo($objMdCorContratoDTO, $objInfraException);
            }

            $objInfraException->lancarValidacoes();

            $objMdCorContratoBD = new MdCorContratoBD($this->getObjInfraIBanco());
            $ret = $objMdCorContratoBD->alterar($objMdCorContratoDTO);

            $objMdCorServicoPostalRN = new MdCorServicoPostalRN();
            $objMdCorMapUnidServicoRN = new MdCorMapUnidServicoRN();

            $objMdCorServicoPostalDTO = new MdCorServicoPostalDTO();
//            $objMdCorServicoPostalDTO->retNumIdMdCorServicoPostal();
            $objMdCorServicoPostalDTO->retTodos();
            $objMdCorServicoPostalDTO->setNumIdMdCorContrato($arr['hdnIdMdCorContrato']);
            $objMdCorServicoPostalDTO->setBolExclusaoLogica(array());
            $arrObjMdCorServicoPostalDTO = $objMdCorServicoPostalRN->listar($objMdCorServicoPostalDTO);

            // normalizando os dados para facilitar as operações
            $arrIdServicoPostalBanco = array();
            $arrIdServicoPostalPost = array();

            for ($j = 0; $j < count($arrObjMdCorServicoPostalDTO); $j++) {
                $key = $arrObjMdCorServicoPostalDTO[$j]->getStrIdWsCorreios();
                $arrIdServicoPostalBanco[$key] = $arrObjMdCorServicoPostalDTO[$j];
            }
            foreach ($arr['id'] as $i => $ar) {
                $cobrar = isset($arr['cobrar'][$i]) ? $arr['cobrar'][$i] : 'N';
                $objMdCorServicoPostalDTO = new MdCorServicoPostalDTO();
                $objMdCorServicoPostalDTO->setNumIdMdCorContrato($arr['hdnIdMdCorContrato']);
                $objMdCorServicoPostalDTO->setStrIdWsCorreios($arr['id'][$i]);
                $objMdCorServicoPostalDTO->setStrCodigoWsCorreios($arr['codigo'][$i]);
                $objMdCorServicoPostalDTO->setStrNome($arr['nome'][$i]);
                $objMdCorServicoPostalDTO->setStrDescricao($arr['descricao'][$i]);
                $objMdCorServicoPostalDTO->setStrSinServicoCobrar($cobrar);

                $arrTipoCorrespondencia = explode('|', $arr['sl_tipo'][$i]);
                $tipoCorrespondencia = current($arrTipoCorrespondencia);
                $sinAr = end($arrTipoCorrespondencia);


                $objMdCorServicoPostalDTO->setStrSinAtivo('S');

                $objMdCorServicoPostalDTO->setStrExpedicaoAvisoRecebimento($arr['ar'][$i]);
                if ($sinAr == 'N') {
                    $objMdCorServicoPostalDTO->setStrExpedicaoAvisoRecebimento('N');
                }

                $objMdCorServicoPostalDTO->setNumIdMdCorTipoCorrespondencia($tipoCorrespondencia);

                $key = $objMdCorServicoPostalDTO->getStrIdWsCorreios();
                $arrIdServicoPostalPost[$key] = $objMdCorServicoPostalDTO;
            }

            // calculando as operações
            $arrIdParaAtualizar = array_intersect_key($arrIdServicoPostalBanco, $arrIdServicoPostalPost);
            $arrIdParacadastrar = array_diff_key($arrIdServicoPostalPost, $arrIdServicoPostalBanco);
            $arrIdParaExcluir = array_diff_key($arrIdServicoPostalBanco, $arrIdServicoPostalPost);

            // atualizando
            foreach ($arrIdParaAtualizar as $j => $objMdCorServicoPostalDTO) {

                /*
                 * If para verificar se o IdWsCorreios está contido no array de serviçoes desativados vindo do post, se sim seta N para sin
                 * sin_ativo.
                 */
               // in_array($arrIdServicoPostalPost[$j]->getStrIdWsCorreios(), $arr['hdnListaContratoServicosDesativados']) $arrIdParaAtualizar[$j]->setStrSinAtivo('N');
                if(in_array($arrIdServicoPostalPost[$j]->getStrIdWsCorreios(), $arr['hdnListaContratoServicosDesativados'])){
                    $arrIdParaAtualizar[$j]->setStrSinAtivo('N');
                }else if (in_array($arrIdServicoPostalPost[$j]->getStrIdWsCorreios(), $arr['hdnListaContratoServicosReativadas'])){
                    $arrIdParaAtualizar[$j]->setStrSinAtivo('S');
                }

                $arrIdParaAtualizar[$j]->setStrExpedicaoAvisoRecebimento($arrIdServicoPostalPost[$j]->getStrExpedicaoAvisoRecebimento());
                $arrIdParaAtualizar[$j]->setStrDescricao($arrIdServicoPostalPost[$j]->getStrDescricao());
                $arrIdParaAtualizar[$j]->setStrSinServicoCobrar($arrIdServicoPostalPost[$j]->getStrSinServicoCobrar());
                $arrIdParaAtualizar[$j]->setNumIdMdCorTipoCorrespondencia($arrIdServicoPostalPost[$j]->getNumIdMdCorTipoCorrespondencia());
                $objMdCorServicoPostalRN->alterar($objMdCorServicoPostalDTO);
            }

            // cadastrando
            foreach ($arrIdParacadastrar as $j => $objMdCorServicoPostalDTO) {
                $objMdCorServicoPostalRN->cadastrar($objMdCorServicoPostalDTO);
            }


            // excluindo se nao tiver mapeado para alguma unidade
            foreach ($arrIdParaExcluir as $j => $objMdCorServicoPostalDTO) {

                $objMdCorExpedicaoSolicitadaDTO = new MdCorExpedicaoSolicitadaDTO();
                $objMdCorExpedicaoSolicitadaRN = new MdCorExpedicaoSolicitadaRN();
                $objMdCorMapUnidServicoDTO = new MdCorMapUnidServicoDTO();

                $objMdCorExpedicaoSolicitadaDTO->retNumIdMdCorServicoPostal();
                $objMdCorExpedicaoSolicitadaDTO->setNumIdUnidade(SessaoSEI::getInstance()->getNumIdUnidadeAtual());
                $objMdCorMapUnidServicoDTO->retNumIdMdCorMapUnidServico();
                $objMdCorMapUnidServicoDTO->setNumIdMdCorServicoPostal($objMdCorServicoPostalDTO->getNumIdMdCorServicoPostal());
                $arrObjMdCorMapUnidServicoDTO = $objMdCorMapUnidServicoRN->listar($objMdCorMapUnidServicoDTO);
                $arrMdCorExpedicaoSolicitadaDTO = $objMdCorExpedicaoSolicitadaRN->listar($objMdCorExpedicaoSolicitadaDTO);
                $arrIdMdCorServicoPostal = InfraArray::converterArrInfraDTO($arrMdCorExpedicaoSolicitadaDTO, 'IdMdCorServicoPostal');

                $objMdCorMapUnidServicoDTO->retNumIdMdCorMapUnidServico();

                /* Verifica se o serviço está mapeado em alguma unidade */
                if (count($arrObjMdCorMapUnidServicoDTO) <= 0) {

                    /* Verifica se o serviço está vinculado a alguma solicitação de expedição */
                    if (!(in_array($arrIdParaExcluir[$j]->getNumIdMdCorServicoPostal(),$arrIdMdCorServicoPostal))) {

                        $objMdCorServicoPostalRN->excluir(array($objMdCorServicoPostalDTO));

                    } else {

                        PaginaSEI::getInstance()->setStrMensagem('Não é permitido excluir o Serviço Postal ' . $arrIdParaExcluir[$j]->getStrNome() . ' pois ele está vinculado a alguma Solicitação de Expedição.', InfraPagina::$TIPO_MSG_AVISO);

                    }


                } else {

                    PaginaSEI::getInstance()->setStrMensagem('Não é permitido excluir o Serviço Postal ' . $arrIdParaExcluir[$j]->getStrNome() . ' pois ele está parametrizado no Mapeamento de Unidades Solicitantes e Serviços Postais.', InfraPagina::$TIPO_MSG_AVISO);

                    if ($arrIdParaExcluir[$j]->getStrSinAtivo() == 'N') {
                        $arrIdParaExcluir[$j]->setStrSinAtivo('S');
                        $objMdCorServicoPostalRN->alterar($objMdCorServicoPostalDTO);
                    }

                }
            }

            return $ret;
            //Auditoria
        } catch (Exception $e) {
            throw new InfraException('Erro alterando contrato.', $e);
        }
    }

    protected function excluirControlado($arrObjMdCorContratoDTO)
    {
        try {

            //Valida Permissao
            SessaoSEI::getInstance()->validarPermissao('md_cor_contrato_excluir');

            //Regras de Negocio
            $objMdCorServicoPostalDTO = new MdCorServicoPostalDTO();
            $objMdCorServicoPostalRN = new MdCorServicoPostalRN();
            $objMdCorMapUnidServicoRN = new MdCorMapUnidServicoRN();
            $alterarContrato = false;
            $objMdCorContratoBD = new MdCorContratoBD($this->getObjInfraIBanco());

            foreach ($arrObjMdCorContratoDTO as $i => $objMdCorContratoDTO) {

                $objMdCorServicoPostalDTO->setNumIdMdCorContrato($objMdCorContratoDTO->getNumIdMdCorContrato());
                $objMdCorServicoPostalDTO->retNumIdMdCorServicoPostal();
                $objMdCorServicoPostalDTO->setBolExclusaoLogica(false);
                $arrObjMdCorServicoPostalDTO = $objMdCorServicoPostalRN->listar($objMdCorServicoPostalDTO);

                foreach ($arrObjMdCorServicoPostalDTO as $j => $objItemMdCorServicoPostalDTO) {

                    $MdCorExpedicaoSolicitadaRN = new MdCorExpedicaoSolicitadaRN();
                    $objMdCorExpedicaoSolicitadaDTO = new MdCorExpedicaoSolicitadaDTO();
                    $objMdCorExpedicaoSolicitadaDTO->retTodos();
                    $objMdCorExpedicaoSolicitadaDTO->setNumIdMdCorServicoPostal($objItemMdCorServicoPostalDTO->getNumIdMdCorServicoPostal());

                    $arrObjMdCorExpedicaoSolicitadaDTO = $MdCorExpedicaoSolicitadaRN->listar($objMdCorExpedicaoSolicitadaDTO);
                    if (count($arrObjMdCorExpedicaoSolicitadaDTO) > 0) {
                        $alterarContrato = true;
                        PaginaSEI::getInstance()->setStrMensagem('Não foi possível excluir este Contrato, pois existem outras parametrizações ou expedições que já o ultilizaram.', InfraPagina::$TIPO_MSG_AVISO);
                    } else {
                        $objMdCorMapUnidServicoDTO = new MdCorMapUnidServicoDTO();
                        $objMdCorMapUnidServicoDTO->retNumIdMdCorMapUnidServico();
                        $objMdCorMapUnidServicoDTO->setNumIdMdCorServicoPostal($objItemMdCorServicoPostalDTO->getNumIdMdCorServicoPostal());
                        $arrObjMdCorMapUnidServicoDTO = $objMdCorMapUnidServicoRN->listar($objMdCorMapUnidServicoDTO);

                        /* Verifica se o serviço está mapeado em alguma unidade */
                        if (count($arrObjMdCorMapUnidServicoDTO) > 0) {
                            $alterarContrato = true;
//                        $objMdCorServicoPostalRN->desativar(array($objItemMdCorServicoPostalDTO));
                            PaginaSEI::getInstance()->setStrMensagem('Não foi possível excluir este Contrato, pois existem outras parametrizações ou expedições que já o ultilizaram.', InfraPagina::$TIPO_MSG_AVISO);
                        } else {
                            $objMdCorServicoPostalRN->excluir(array($objItemMdCorServicoPostalDTO));
                        }
                        $objMdCorServicoPostalExcluirDTO = new MdCorServicoPostalDTO();
                        $objMdCorServicoPostalExcluirDTO->retTodos();
                        $objMdCorServicoPostalExcluirDTO->setNumIdMdCorServicoPostal($objItemMdCorServicoPostalDTO->getNumIdMdCorServicoPostal());
                        $arrObjMdCorServicoPostalExcluirDTO = $objMdCorServicoPostalRN->consultar($objMdCorServicoPostalExcluirDTO);

                        $objMdCorServicoPostalRN->excluir($arrObjMdCorServicoPostalExcluirDTO);
                    }
                }

                if ($alterarContrato) {
                    PaginaSEI::getInstance()->setStrMensagem('Não foi possível excluir este Contrato, pois existem outras parametrizações ou expedições que já o ultilizaram.', InfraPagina::$TIPO_MSG_AVISO);
//                    $objMdCorContratoBD->desativar($objMdCorContratoDTO);
                } else {
                    $mdCorObjetoRN = new MdCorObjetoRN();
                    $objMdCorObjetoDTO = new MdCorObjetoDTO();
                    $objMdCorObjetoDTO->retTodos();
                    $objMdCorObjetoDTO->setNumIdMdCorContrato($objMdCorContratoDTO->getNumIdMdCorContrato());
                    $arrObjMdCorObjetoDTO = $mdCorObjetoRN->listar($objMdCorObjetoDTO);
                    foreach ($arrObjMdCorObjetoDTO as $itemObjMdCorObjetoDTO) {
                        $mdCorObjetoRN->excluir(array($itemObjMdCorObjetoDTO));
                    }
                    $objMdCorContratoBD->excluir($objMdCorContratoDTO);
                }
            }
            //Auditoria
        } catch (Exception $e) {
            throw new InfraException('Erro excluindo contrato.', $e);
        }
    }

    protected function consultarConectado(MdCorContratoDTO $objMdCorContratoDTO)
    {
        try {

            //Valida Permissao
            SessaoSEI::getInstance()->validarPermissao('md_cor_contrato_consultar');

            //Regras de Negocio
            //$objInfraException = new InfraException();
            //$objInfraException->lancarValidacoes();

            $objMdCorContratoBD = new MdCorContratoBD($this->getObjInfraIBanco());
            $ret = $objMdCorContratoBD->consultar($objMdCorContratoDTO);

            //Auditoria

            return $ret;
        } catch (Exception $e) {
            throw new InfraException('Erro consultando contrato.', $e);
        }
    }

    protected function listarConectado(MdCorContratoDTO $objMdCorContratoDTO)
    {
        try {

            //Valida Permissao
            SessaoSEI::getInstance()->validarPermissao('md_cor_contrato_listar');

            //Regras de Negocio
            //$objInfraException = new InfraException();
            //$objInfraException->lancarValidacoes();

            $objMdCorContratoBD = new MdCorContratoBD($this->getObjInfraIBanco());
            $ret = $objMdCorContratoBD->listar($objMdCorContratoDTO);

            //Auditoria

            return $ret;
        } catch (Exception $e) {
            throw new InfraException('Erro listando contratos.', $e);
        }
    }

    protected function contarConectado(MdCorContratoDTO $objMdCorContratoDTO)
    {
        try {

            //Valida Permissao
            SessaoSEI::getInstance()->validarPermissao('md_cor_contrato_listar');

            //Regras de Negocio
            //$objInfraException = new InfraException();
            //$objInfraException->lancarValidacoes();

            $objMdCorContratoBD = new MdCorContratoBD($this->getObjInfraIBanco());
            $ret = $objMdCorContratoBD->contar($objMdCorContratoDTO);

            //Auditoria

            return $ret;
        } catch (Exception $e) {
            throw new InfraException('Erro contando contratos.', $e);
        }
    }

    protected function desativarControlado($arrObjMdCorContratoDTO)
    {
        try {

            //Valida Permissao
            SessaoSEI::getInstance()->validarPermissao('md_cor_contrato_desativar');

            //Regras de Negocio
            //$objInfraException = new InfraException();
            //$objInfraException->lancarValidacoes();

            $objMdCorContratoBD = new MdCorContratoBD($this->getObjInfraIBanco());
            for ($i = 0; $i < count($arrObjMdCorContratoDTO); $i++) {
                $objMdCorContratoBD->desativar($arrObjMdCorContratoDTO[$i]);
            }

            //Auditoria
        } catch (Exception $e) {
            throw new InfraException('Erro desativando contrato.', $e);
        }
    }

    protected function reativarControlado($arrObjMdCorContratoDTO)
    {
        try {

            //Valida Permissao
            SessaoSEI::getInstance()->validarPermissao('md_cor_contrato_reativar');

            //Regras de Negocio
            //$objInfraException = new InfraException();
            //$objInfraException->lancarValidacoes();

            $objMdCorContratoBD = new MdCorContratoBD($this->getObjInfraIBanco());
            for ($i = 0; $i < count($arrObjMdCorContratoDTO); $i++) {
                $objMdCorContratoBD->reativar($arrObjMdCorContratoDTO[$i]);
            }

            //Auditoria
        } catch (Exception $e) {
            throw new InfraException('Erro reativando contrato.', $e);
        }
    }

    protected function bloquearControlado(MdCorContratoDTO $objMdCorContratoDTO)
    {
        try {

            //Valida Permissao
            SessaoSEI::getInstance()->validarPermissao('md_cor_contrato_consultar');

            //Regras de Negocio
            //$objInfraException = new InfraException();
            //$objInfraException->lancarValidacoes();

            $objMdCorContratoBD = new MdCorContratoBD($this->getObjInfraIBanco());
            $ret = $objMdCorContratoBD->bloquear($objMdCorContratoDTO);

            //Auditoria

            return $ret;
        } catch (Exception $e) {
            throw new InfraException('Erro bloqueando contrato.', $e);
        }
    }

    protected function pesquisarProtocoloFormatadoConectado(ProtocoloDTO $parObjProtocoloDTO)
    {
        try {
            $objProtocoloRN = new ProtocoloRN();
            //busca pelo numero do processo
            $objProtocoloDTOPesquisa = new ProtocoloDTO();
            $objProtocoloDTOPesquisa->retStrNomeTipoProcedimentoProcedimento();
            $objProtocoloDTOPesquisa->retDblIdProtocolo();
            $objProtocoloDTOPesquisa->retStrProtocoloFormatado();
            $objProtocoloDTOPesquisa->retStrStaProtocolo();
            $objProtocoloDTOPesquisa->retStrStaNivelAcessoGlobal();
            $objProtocoloDTOPesquisa->setNumMaxRegistrosRetorno(2);

            $strProtocoloPesquisa = InfraUtil::retirarFormatacao($parObjProtocoloDTO->getStrProtocoloFormatadoPesquisa(), false);

            $objProtocoloDTOPesquisa->setStrProtocoloFormatadoPesquisa($strProtocoloPesquisa);
            $arrObjProtocoloDTO = $objProtocoloRN->listarRN0668($objProtocoloDTOPesquisa);

            if (count($arrObjProtocoloDTO) > 1) {
                return null;
            }

            if (count($arrObjProtocoloDTO) == 1) {
                return $arrObjProtocoloDTO[0];
            }


            return null;
        } catch (Exception $e) {
            throw new InfraException('Erro pesquisando protocolo.', $e);
        }
    }

}

?>
