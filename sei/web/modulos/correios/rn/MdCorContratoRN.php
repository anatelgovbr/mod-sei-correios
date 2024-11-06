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
            $objMdCorContratoDTO->setDblIdProcedimento($arr['hdnIdProcedimento']);
            $objMdCorContratoDTO->setStrSinAtivo('S');
            $objMdCorContratoDTO->setNumNumeroCnpj(InfraUtil::retirarFormatacao($_POST['txtCNPJ']));
            $objMdCorContratoDTO->setNumIdMdCorDiretoria($arr['slCodigoDiretoria']);

            //Regras de Negocio
            $objInfraException = new InfraException();

            $this->validarStrNumeroContrato($objMdCorContratoDTO, $objInfraException);
            $this->validarStrNumeroContratoCorreio($objMdCorContratoDTO, $objInfraException);
            $this->validarStrNumeroCartaoPostagem($objMdCorContratoDTO, $objInfraException);
            $this->validarDblIdProcedimento($objMdCorContratoDTO, $objInfraException);
            $this->validarStrSinAtivo($objMdCorContratoDTO, $objInfraException);

            $objInfraException->lancarValidacoes();

            $objMdCorContratoBD = new MdCorContratoBD($this->getObjInfraIBanco());
            $ret = $objMdCorContratoBD->cadastrar($objMdCorContratoDTO);

            $objMdCorServicoPostalRN = new MdCorServicoPostalRN();
            foreach ($arr['codigo'] as $i => $ar) {
                $cobrar = isset($arr['cobrar'][$i]) ? $arr['cobrar'][$i] : 'N';
                $anexarMidia = isset($arr['anexarMidia'][$i]) ? $arr['anexarMidia'][$i] : 'N';

                $objMdCorServicoPostalDTO = new MdCorServicoPostalDTO();
                $objMdCorServicoPostalDTO->setNumIdMdCorContrato($objMdCorContratoDTO->getNumIdMdCorContrato());
                $objMdCorServicoPostalDTO->setStrCodigoWsCorreios($arr['codigo'][$i]);
                $objMdCorServicoPostalDTO->setStrNome($arr['nome'][$i]);
                $objMdCorServicoPostalDTO->setStrExpedicaoAvisoRecebimento($arr['ar'][$i]);
                $objMdCorServicoPostalDTO->setStrDescricao($arr['descricao'][$i]);
                $objMdCorServicoPostalDTO->setStrSinAtivo('S');
                $objMdCorServicoPostalDTO->setStrSinServicoCobrar($cobrar);
                $objMdCorServicoPostalDTO->setStrSinAnexarMidia($anexarMidia);

                //trata dados sobre tipoo de correspondencia
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
            $objMdCorContratoDTO->setDblIdProcedimento($arr['hdnIdProcedimento']);
            $objMdCorContratoDTO->setNumNumeroCnpj(InfraUtil::retirarFormatacao($arr['txtCNPJ']));
            $objMdCorContratoDTO->setNumIdMdCorDiretoria($arr['slCodigoDiretoria']);
            $objMdCorContratoDTO->setStrSinAtivo('S');

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

            $objMdCorServicoPostalDTO->retTodos();
            $objMdCorServicoPostalDTO->setNumIdMdCorContrato($arr['hdnIdMdCorContrato']);
            $objMdCorServicoPostalDTO->setBolExclusaoLogica(array());
            $arrObjMdCorServicoPostalDTO = $objMdCorServicoPostalRN->listar($objMdCorServicoPostalDTO);

            // normalizando os dados para facilitar as operações
            $arrIdServicoPostalBanco = array();
            $arrIdServicoPostalPost = array();

            for ($j = 0; $j < count($arrObjMdCorServicoPostalDTO); $j++) {
	            $arrObjMdCorServicoPostalDTO[$j]->setStrCodigoWsCorreios( trim( $arrObjMdCorServicoPostalDTO[$j]->getStrCodigoWsCorreios() ) );
	            $key = $arrObjMdCorServicoPostalDTO[$j]->getStrCodigoWsCorreios();
                $arrIdServicoPostalBanco[$key] = $arrObjMdCorServicoPostalDTO[$j];
            }

	        foreach ($arr['codigo'] as $i => $ar) {
                $cobrar = isset($arr['cobrar'][$i]) ? $arr['cobrar'][$i] : 'N';
                $anexarMidia = isset($arr['anexarMidia'][$i]) ? $arr['anexarMidia'][$i] : 'N';

                $objMdCorServicoPostalDTO = new MdCorServicoPostalDTO();
                $objMdCorServicoPostalDTO->setNumIdMdCorContrato($arr['hdnIdMdCorContrato']);
                $objMdCorServicoPostalDTO->setStrCodigoWsCorreios($arr['codigo'][$i]);
                $objMdCorServicoPostalDTO->setStrNome($arr['nome'][$i]);
                $objMdCorServicoPostalDTO->setStrDescricao($arr['descricao'][$i]);
                $objMdCorServicoPostalDTO->setStrSinServicoCobrar($cobrar);
                $objMdCorServicoPostalDTO->setStrSinAnexarMidia($anexarMidia);

                $arrTipoCorrespondencia = explode('|', $arr['sl_tipo'][$i]);
                $tipoCorrespondencia = current($arrTipoCorrespondencia);
                $sinAr = end($arrTipoCorrespondencia);

                $objMdCorServicoPostalDTO->setStrSinAtivo('S');

                $objMdCorServicoPostalDTO->setStrExpedicaoAvisoRecebimento($arr['ar'][$i]);
                if ($sinAr == 'N') {
                    $objMdCorServicoPostalDTO->setStrExpedicaoAvisoRecebimento('N');
                }

                $objMdCorServicoPostalDTO->setNumIdMdCorTipoCorrespondencia($tipoCorrespondencia);

		        $key = $objMdCorServicoPostalDTO->getStrCodigoWsCorreios();
                $arrIdServicoPostalPost[$key] = $objMdCorServicoPostalDTO;
            }

            // calculando as operações
            $arrIdParaAtualizar = array_intersect_key($arrIdServicoPostalBanco, $arrIdServicoPostalPost);
            $arrIdParacadastrar = array_diff_key($arrIdServicoPostalPost, $arrIdServicoPostalBanco);
            $arrIdParaExcluir = array_diff_key($arrIdServicoPostalBanco, $arrIdServicoPostalPost);

            // atualizando
            foreach ($arrIdParaAtualizar as $j => $objMdCorServicoPostalDTO) {

                /*
                 * If para verificar se o CodigoWsCorreios está contido no array de serviçoes desativados vindo do post, se sim seta N para sin
                 * sin_ativo.
                 */
	            if(in_array($arrIdServicoPostalPost[$j]->getStrCodigoWsCorreios(), $arr['hdnListaContratoServicosDesativados'])){
                    $arrIdParaAtualizar[$j]->setStrSinAtivo('N');
	            }else if (in_array($arrIdServicoPostalPost[$j]->getStrCodigoWsCorreios(), $arr['hdnListaContratoServicosReativadas'])){
                    $arrIdParaAtualizar[$j]->setStrSinAtivo('S');
                }

                $arrIdParaAtualizar[$j]->setStrExpedicaoAvisoRecebimento($arrIdServicoPostalPost[$j]->getStrExpedicaoAvisoRecebimento());
                $arrIdParaAtualizar[$j]->setStrDescricao($arrIdServicoPostalPost[$j]->getStrDescricao());
                $arrIdParaAtualizar[$j]->setStrSinServicoCobrar($arrIdServicoPostalPost[$j]->getStrSinServicoCobrar());
                $arrIdParaAtualizar[$j]->setStrSinAnexarMidia($arrIdServicoPostalPost[$j]->getStrSinAnexarMidia());
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
                            PaginaSEI::getInstance()->setStrMensagem('Não foi possível excluir este Contrato, pois existem outras parametrizações ou expedições que já o ultilizaram.', InfraPagina::$TIPO_MSG_AVISO);
                        } else {
                            $objMdCorServicoPostalRN->excluir(array($objItemMdCorServicoPostalDTO));
                        }
                    }
                }

                if ($alterarContrato) {
                    PaginaSEI::getInstance()->setStrMensagem('Não foi possível excluir este Contrato, pois existem outras parametrizações ou expedições que já o ultilizaram.', InfraPagina::$TIPO_MSG_AVISO);
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

    /**
     * @param $idMdCorServicoPostal
     * @return string
     * @throws InfraException
     */
    public function verificarServicoMapeado($idMdCorServicoPostal)
    {

        $servicoMapeado = 'true';
        $msg = '';

        $objMdCorExpedicaoSolicitadaDTO = new MdCorExpedicaoSolicitadaDTO();
        $objMdCorExpedicaoSolicitadaRN = new MdCorExpedicaoSolicitadaRN();
        $objMdCorMapUnidServicoDTO = new MdCorMapUnidServicoDTO();
        $objMdCorMapUnidServicoRN = new MdCorMapUnidServicoRN();
        $objMdCorServicoPostalRN = new MdCorServicoPostalRN();

        $objMdCorServicoPostalDTO = new MdCorServicoPostalDTO();
        $objMdCorServicoPostalDTO->retTodos();
        $objMdCorServicoPostalDTO->setNumIdMdCorServicoPostal($idMdCorServicoPostal);
        $objMdCorServicoPostal = $objMdCorServicoPostalRN->consultar($objMdCorServicoPostalDTO);

        $objMdCorExpedicaoSolicitadaDTO->retNumIdMdCorServicoPostal();
        $objMdCorMapUnidServicoDTO->retNumIdMdCorMapUnidServico();
        $objMdCorMapUnidServicoDTO->setNumIdMdCorServicoPostal($objMdCorServicoPostal->getNumIdMdCorServicoPostal());
        $arrObjMdCorMapUnidServicoDTO = $objMdCorMapUnidServicoRN->listar($objMdCorMapUnidServicoDTO);
        $arrMdCorExpedicaoSolicitadaDTO = $objMdCorExpedicaoSolicitadaRN->listar($objMdCorExpedicaoSolicitadaDTO);
        $arrIdMdCorServicoPostal = InfraArray::converterArrInfraDTO($arrMdCorExpedicaoSolicitadaDTO, 'IdMdCorServicoPostal');

        /* Verifica se o serviço está mapeado em alguma unidade */
        if (count($arrObjMdCorMapUnidServicoDTO) <= 0) {

            /* Verifica se o serviço está vinculado a alguma solicitação de expedição */
            if (!(in_array($objMdCorServicoPostal->getNumIdMdCorServicoPostal(),$arrIdMdCorServicoPostal))) {
                $servicoMapeado = 'false';
            } else {
                $msg = 'Não é permitido excluir o Serviço Postal ' . $objMdCorServicoPostal->getStrNome() . ' pois ele está vinculado a alguma Solicitação de Expedição.';
            }
        } else {
            $msg = 'Não é permitido excluir o Serviço Postal ' . $objMdCorServicoPostal->getStrNome() . ' pois ele está parametrizado no Mapeamento de Unidades Solicitantes e Serviços Postais.';
        }

        $xml = '<Documento>';
        $xml.= '<ServicoMapeado>'.$servicoMapeado.'</ServicoMapeado>';
        $xml.= '<Msg>'.$msg.'</Msg>';
        $xml .= '</Documento>';
        return $xml;
    }

    public static function verificarUnidServicoMapeado( $idUnidServico , $idServicoPostal )
    {
        $msg = '';

        $objMdCorMapUnidServicoDTO = new MdCorMapUnidServicoDTO();
        $objMdCorMapUnidServicoRN = new MdCorMapUnidServicoRN();

        $objMdCorExpedicaoSolicitadaDTO = new MdCorExpedicaoSolicitadaDTO();
        $objMdCorExpedicaoSolicitadaRN = new MdCorExpedicaoSolicitadaRN();

        $objMdCorMapUnidServicoDTO->setNumIdMdCorMapUnidServico( $idUnidServico );
        $objMdCorMapUnidServicoDTO->retNumIdUnidadeSolicitante();
        $objMapUnidServ = $objMdCorMapUnidServicoRN->consultar( $objMdCorMapUnidServicoDTO );
        
        $objMdCorExpedicaoSolicitadaDTO->setNumIdUnidade( $objMapUnidServ->getNumIdUnidadeSolicitante() );
        $objMdCorExpedicaoSolicitadaDTO->setNumIdMdCorServicoPostal( $idServicoPostal );        
        $objMdCorExpedicaoSolicitadaDTO->retNumIdMdCorExpedicaoSolicitada();
        $qtd = $objMdCorExpedicaoSolicitadaRN->contar( $objMdCorExpedicaoSolicitadaDTO );

        if( $qtd > 0 ) $msg = 'Não é permitido excluir o Serviço Postal pois ele está vinculado a alguma Solicitação de Expedição.';        

        $xml = '<Documento>';
        $xml.= '<Msg>'.$msg.'</Msg>';
        $xml .= '</Documento>';
        return $xml;
    }

    public function getNumeroPostagemContratoAtivo(){
		$objMdCorContratoDTO = new MdCorContratoDTO();
	    $objMdCorContratoDTO->setStrSinAtivo('S');
	    $objMdCorContratoDTO->retStrNumeroCartaoPostagem();

	    $objMdCorContratoDTO = $this->consultar($objMdCorContratoDTO);

	    if ( $objMdCorContratoDTO )	return $objMdCorContratoDTO->getStrNumeroCartaoPostagem();

	    return false;
    }
}

?>
