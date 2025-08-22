<?php

class CorreiosIntegracao extends SeiIntegracao
{

    public function __construct()
    {

    }

    public function getNome()
    {
        return 'SEI Correios';
    }

    public function getVersao()
    {
        return '2.5.0';
    }

    public function getInstituicao()
    {
        return 'Anatel - Agência Nacional de Telecomunicações';
    }

    public function inicializar($strVersaoSEI)
    {

    }

    public function obterDiretorioIconesMenu()
    {
        return 'modulos/correios/menu';
    }

    public function processarControlador($strAcao)
    {
        switch ($strAcao) {
            //unidades expedidoras
            case 'md_cor_unidade_exp_cadastrar' :
                require_once dirname(__FILE__) . '/md_cor_unidade_exp_cadastro.php';
                return true;

            //extensao de midia
            case 'md_cor_extensao_midia_listar' :
            case 'md_cor_extensao_midia_desativar' :
            case 'md_cor_extensao_midia_reativar' :
            case 'md_cor_extensao_midia_excluir' :
                require_once dirname(__FILE__) . '/md_cor_extensao_midia_lista.php';
                return true;

            //extensoes de midia
            case 'md_cor_extensao_midia_cadastrar' :
            case 'md_cor_extensao_midia_consultar' :
            case 'md_cor_extensao_midia_alterar' :
                require_once dirname(__FILE__) . '/md_cor_extensao_midia_cadastro.php';
                return true;
            case 'md_cor_extensao_arquivo_selecionar':
                require_once dirname(__FILE__) . '/md_cor_extensao_arquivo_lista.php';
                return true;

            //contrato
            case 'md_cor_contrato_cadastrar' :
            case 'md_cor_contrato_consultar' :
            case 'md_cor_contrato_alterar' :
                require_once dirname(__FILE__) . '/md_cor_contrato_cadastro.php';
                return true;

            //Serviços Postais
            case 'md_cor_servicos_postais_contrato_alterar' :
                require_once dirname(__FILE__) . '/md_cor_servicos_postais_contrato_cadastro.php';
                return true;

            case 'md_cor_contrato_desativar' :
            case 'md_cor_contrato_reativar' :
            case 'md_cor_contrato_excluir' :
            case 'md_cor_contrato_selecionar' :
            case 'md_cor_contrato_listar' :
                require_once dirname(__FILE__) . '/md_cor_contrato_lista.php';
                return true;

            //tipos de documentos
            case 'md_cor_serie_exp_cadastrar':
                require_once dirname(__FILE__) . '/md_cor_serie_exp_cadastro.php';
                return true;

            case 'md_cor_serie_exp_selecionar':
                require_once dirname(__FILE__) . '/md_cor_serie_exp_lista.php';
                return true;

            //mapeamento unidade exp X solicitantes
            case 'md_cor_mapeamento_uni_exp_sol_listar':
            case'md_cor_mapeamento_uni_exp_sol_desativar':
            case'md_cor_mapeamento_uni_exp_sol_reativar':
            case'md_cor_mapeamento_uni_exp_sol_excluir' :
                require_once dirname(__FILE__) . '/md_cor_mapeamento_uni_exp_sol_lista.php';
                return true;

            case 'md_cor_mapeamento_uni_exp_sol_cadastrar':
            case 'md_cor_mapeamento_uni_exp_sol_alterar':
            case 'md_cor_mapeamento_uni_exp_sol_consultar':
                require_once dirname(__FILE__) . '/md_cor_mapeamento_uni_exp_sol_cadastro.php';
                return true;

            //mapeamento de unid solicitando x servicos postais
            case 'md_cor_map_unid_servico_listar':
            case 'md_cor_map_unid_servico_desativar':
            case 'md_cor_map_unid_servico_reativar':
            case 'md_cor_map_unid_servico_excluir' :
                require_once dirname(__FILE__) . '/md_cor_map_unid_servico_lista.php';
                return true;

            case 'md_cor_map_unid_servico_cadastrar':
            case 'md_cor_map_unid_servico_alterar':
            case 'md_cor_map_unid_servico_consultar':
                require_once dirname(__FILE__) . '/md_cor_map_unid_servico_cadastro.php';
                return true;

            //outros
            case 'md_cor_unidade_selecionar_todas':
                require_once dirname(__FILE__) . '/md_cor_unidade_lista.php';
                return true;

            case 'md_cor_servico_postal_listar':
                return true;

            case 'md_cor_expedicao_solicitada_cadastrar':
            case 'md_cor_expedicao_solicitada_alterar':
            case 'md_cor_expedicao_solicitada_consultar':
            case 'md_cor_expedicao_solicitada_excluir':
                require_once dirname(__FILE__) . '/md_cor_expedicao_solicitada_cadastro.php';
                return true;

            case 'md_cor_expedicao_detalhar_rastreio':
                require_once dirname(__FILE__) . '/md_cor_expedicao_detalhar_rastreio.php';
                return true;

            case 'md_cor_expedicao_processo_listar':
                require_once dirname(__FILE__) . '/md_cor_expedicao_processo_lista.php';
                return true;

            case 'md_cor_expedicao_unidade_listar':
                require_once dirname(__FILE__) . '/md_cor_expedicao_unidade_lista.php';
                return true;

            case 'md_cor_expedicao_solicitada_devolver_consultar':
            case 'md_cor_expedicao_solicitada_devolver_alterar':
                require_once dirname(__FILE__) . '/md_cor_expedicao_solicitada_devolver.php';
                return true;

            case 'md_cor_expedicao_cadastro_protocolos_selecionar':
                require_once dirname(__FILE__) . '/md_cor_expedicao_cadastro_protocolos_selecionar.php';
                return true;

            case 'md_cor_expedicao_objeto_listar':
                require_once dirname(__FILE__) . '/md_cor_expedicao_objeto_lista.php';
                return true;

            case 'md_cor_expedicao_plp_consultar':
            case 'md_cor_plp_objeto_listar':
                require_once dirname(__FILE__) . '/md_cor_expedicao_plp_consulta.php';
                return true;

            case 'md_cor_geracao_plp_listar': //9364 - Gerar PLP - Consulta
            case 'md_cor_plp_correio_cadastro'://9364 - Gerar PLP - WebService
                require_once dirname(__FILE__) . '/md_cor_geracao_plp_lista.php';
                return true;

            case 'md_cor_geracao_plp_consultar':
                require_once dirname(__FILE__) . '/md_cor_geracao_plp_consulta.php';
                return true;

            case 'md_cor_plps_geradas_listar':
            case 'md_cor_expedicao_plp_listar': //9365 - PLPs geradas para expedição
                require_once dirname(__FILE__) . '/md_cor_plps_geradas_lista.php';
                return true;

            case 'md_cor_plp_detalhada':
            case 'md_cor_plp_expedir'://9365 - Expedir PLP
            case 'md_cor_plp_pdf_documento_principal'://9365 - Gerar documento Principal
            case 'md_cor_plp_pdf_arquivo_lote'://9365 - Gerar Arquivo em lote
            case 'md_cor_plp_concluir'://9365 - Concluir PLP
                require_once dirname(__FILE__) . '/md_cor_plp_detalhada.php';
                return true;

            case 'md_cor_plp_detalhar_objeto':
            case 'md_cor_plp_gerar_zip':
            case 'md_cor_plp_pdf_arquivo_lote_objeto': //9365 - Gerar Arquivo em lote por objeto
            case 'md_cor_plp_expedir_objeto'://9365 - Expedir Objeto
                require_once dirname(__FILE__) . '/md_cor_plp_detalhar_objeto.php';
                return true;
            //9368
            //impressões da plp
            case 'md_cor_plp_imprimir_voucher':
                require_once dirname(__FILE__) . '/md_cor_plp_imprimir_voucher.php';
                return true;
            case 'md_cor_plp_imprimir_ar':
                require_once dirname(__FILE__) . '/md_cor_plp_imprimir_ar.php';
                return true;
            case 'md_cor_plp_imprimir_rotulo_envelope':
                require_once dirname(__FILE__) . '/md_cor_plp_imprimir_rotulo_envelope.php';
                return true;
            case 'md_cor_exibir_pdf' :
                require_once dirname(__FILE__) . '/md_cor_exibe_pdf.php';
                return true;

            case 'md_cor_objeto_excluir' :
            case 'md_cor_objeto_selecionar' :
            case 'md_cor_objeto_listar' :
                require_once dirname(__FILE__) . '/md_cor_objeto_lista.php';
                return true;

            case 'md_cor_objeto_alterar' :
            case 'md_cor_objeto_cadastrar' :
            case 'md_cor_objeto_consultar':
                require_once dirname(__FILE__) . '/md_cor_objeto_cadastro.php';
                return true;

            case 'md_cor_plp_selecionar_tipo_objeto' :
                require_once dirname(__FILE__) . '/md_cor_plp_seleciona_tipo_objeto.php';
                return true;

            //18881
            case 'md_cor_parametro_ar_cadastrar' :
                require_once dirname(__FILE__) . '/md_cor_parametro_ar_cadastro.php';
                return true;

            //9373
            case 'md_cor_retorno_ar_listar' :
                require_once dirname(__FILE__) . '/md_cor_retorno_ar_lista.php';
                return true;

            case 'md_cor_retorno_ar_resumir' :
                require_once dirname(__FILE__) . '/md_cor_retorno_ar_resumo.php';
                return true;

            case 'md_cor_resumo_processamento' :
                require_once dirname(__FILE__) . '/md_cor_retorno_ar_resumo_processamento.php';
                return true;

            case 'md_cor_retorno_ar_autenticar' :
                require_once dirname(__FILE__) . '/md_cor_retorno_ar_autenticacao.php';
                return true;

            case 'md_cor_retorno_ar_consultar' :
            case 'md_cor_retorno_ar_alterar' :
                //9372
            case 'md_cor_retorno_ar_cadastrar' :
            case 'md_cor_retorno_ar_processar' :
            case 'md_cor_retorno_ar_salvar' :
                require_once dirname(__FILE__) . '/md_cor_retorno_ar_cadastro.php';
                return true;

            case 'md_cor_retorno_ar_arquivo_mostrar' :
                $noDiretorio = $_GET['diretorio'];
                $noArquivo = $_GET['documento'];
                $urlFile = DIR_SEI_TEMP . '/' . $noDiretorio . '/' . $noArquivo;

                header("Content-type: application/pdf");
                header("Content-Disposition: inline; filename=pdf.pdf");
                @readfile($urlFile);

                return true;
                break;
            case 'md_cor_upload_zip_ar' :
                if (isset($_FILES['fileArquivo'])) {
                    PaginaSEI::getInstance()->processarUpload('fileArquivo', DIR_SEI_TEMP, true);
                    die;
                }

            case 'md_cor_ar_pendente_listar' :
                require_once dirname(__FILE__) . '/md_cor_ar_pendente_lista.php';
                return true;

            //19205
            case 'md_cor_cobranca_gerar' :
                require_once dirname(__FILE__) . '/md_cor_retorno_ar_gera_cobranca.php';
                return true;

            case 'md_cor_cobranca_informar' :
                require_once dirname(__FILE__) . '/md_cor_retorno_ar_informa_cobranca.php';
                return true;

            case 'md_cor_historico_visualizar' :
                require_once dirname(__FILE__) . '/md_cor_historico_visualiza.php';
                return true;
            /*
            case 'md_cor_parametrizacao_rastreio_listar' :
                require_once dirname(__FILE__) . '/md_cor_parametrizacao_rastreio_lista.php';
                return true;
            */

            //Parametrização de Status
            case 'md_cor_parametrizacao_status_cadastrar' :
            case 'md_cor_parametrizacao_status_consultar' :
            case 'md_cor_parametrizacao_status_alterar' :
                require_once dirname(__FILE__) . '/md_cor_parametrizacao_status_cadastro.php';
                return true;

            case 'md_cor_parametrizacao_status_desativar' :
            case 'md_cor_parametrizacao_status_reativar' :
            case 'md_cor_parametrizacao_status_listar' :
                require_once dirname(__FILE__) . '/md_cor_parametrizacao_status_lista.php';
                return true;


            //Destinatários não habilitados parra expedição
            case 'md_cor_rel_contato_justificativa_cadastrar' :
            case 'md_cor_rel_contato_justificativa_alterar' :
                require_once dirname(__FILE__) . '/md_cor_rel_contato_justificativa_cadastro.php';
                return true;

            case 'md_cor_rel_contato_justificativa_listar' :
            case 'md_cor_rel_contato_justificativa_excluir' :
                require_once dirname(__FILE__) . '/md_cor_rel_contato_justificativa_lista.php';
                return true;

            //Justificativa - Destinatários não habilitados parra expedição
            case 'md_cor_justificativa_desativar' :
            case 'md_cor_justificativa_reativar' :
            case 'md_cor_justificativa_excluir' :
            case 'md_cor_justificativa_listar' :
                require_once dirname(__FILE__) . '/md_cor_justificativa_lista.php';
                return true;

            case 'md_cor_justificativa_cadastrar' :
            case 'md_cor_justificativa_alterar' :
                require_once dirname(__FILE__) . '/md_cor_justificativa_cadastro.php';
                return true;

	        case 'md_cor_adm_integracao_cadastrar':
	        case 'md_cor_adm_integracao_alterar':
	        case 'md_cor_adm_integracao_consultar':
		        require_once dirname(__FILE__) . '/md_cor_adm_integracao_cadastro.php';
		        return true;

	        case 'md_cor_adm_integracao_listar':
	        case 'md_cor_adm_integracao_excluir':
	        case 'md_cor_adm_integracao_desativar':
	        case 'md_cor_adm_integracao_reativar':
		        require_once dirname(__FILE__) . '/md_cor_adm_integracao_lista.php';
		        return true;

            case 'md_cor_selecionar_layout_envelope':
                require_once dirname(__FILE__) . '/md_cor_selecionar_layout_envelope.php';
                return true;
        }

        return false;
    }

    public function alterarContato(ContatoAPI $objContatoAPI)
    {
        /**
         * Criação da variável de Sessão para transportar o Id do Documento Principal da Solicitação de Expedição
         * Por que a Função Alterar Contato do Core do SEI não permite que essa variável seja passada.
         * Caso seja feita a alteração pela Administração do SEI, não será feito nada nesse método.
         */
        $idDocumentoPrincipal = isset($_SESSION['idDocumentoPrincipal']) ? $_SESSION['idDocumentoPrincipal'] : null;

        if(!is_null($idDocumentoPrincipal)) {
            $mdCorExpedicaoSolicitadaRN = new MdCorExpedicaoSolicitadaRN();
            $mdCorExpedicaoSolicitadaRN->alterarEnderecoExpedSemPLP($objContatoAPI, $idDocumentoPrincipal, false);
        }
    }

    public function excluirContato($objContatoAPI)
    {
        $objInfraException = new InfraException();

        $objContatoAPI = current($objContatoAPI);
        $idContato = $objContatoAPI->getIdContato();
        $mdCorExpedicaoSolicitadaRN = new MdCorExpedicaoSolicitadaRN();
        $mdCorExpedicaoSolicitadaDTO = new MdCorExpedicaoSolicitadaDTO();
        $mdCorExpedicaoSolicitadaDTO->setNumIdContatoDestinatario($idContato);
        $mdCorExpedicaoSolicitadaDTO->retNumIdMdCorExpedicaoSolicitada();
        $mdCorExpedicaoSolicitadaDTO->setStrStaPlp(MdCorPlpRN::$STA_FINALIZADA, INFRADTO::$OPER_DIFERENTE);
        $mdCorExpedicaoSolicitadaDTO->setDistinct(true);

        $qtdSolicitacaoExp = $mdCorExpedicaoSolicitadaRN->contar($mdCorExpedicaoSolicitadaDTO);

        if ($qtdSolicitacaoExp > 0) {
            $objInfraException->adicionarValidacao('Não é permitido Excluir este Contato, pois ele está relacionado a uma expedição pelos Correios.');
            $objInfraException->lancarValidacoes();
        }

        $mdCorExpedicaoSolicitadaRN = new MdCorExpedicaoSolicitadaRN();
        $validacao = $mdCorExpedicaoSolicitadaRN->validarContatoComExpedicaoAndamento($objContatoAPI);

        if($validacao){
            $objInfraException->adicionarValidacao('Não é permitido Desativar este Contato, pois ele está relacionado a uma expedição pelos Correios em andamento.');
            $objInfraException->lancarValidacoes();
        }
    }

    public function excluirDocumento(DocumentoAPI $objDocumentoAPI)
    {
        $idDocumento = $objDocumentoAPI->getIdDocumento();
        $mdCorExpedicaoSolicitadaRN = new MdCorExpedicaoSolicitadaRN();
        $mdCorExpedicaoSolicitadaDTO = new MdCorExpedicaoSolicitadaDTO();

        $mdCorExpedicaoSolicitadaDTO->adicionarCriterio(array('IdDocumentoPrincipal', 'IdDocumentoFormato')
            , array(InfraDTO::$OPER_IGUAL, InfraDTO::$OPER_IGUAL)
            , array($idDocumento, $idDocumento), InfraDTO::$OPER_LOGICO_OR);

        $mdCorExpedicaoSolicitadaDTO->retNumIdMdCorExpedicaoSolicitada();
        $mdCorExpedicaoSolicitadaDTO->setDistinct(true);
        $qtdSolicitacaoExp = $mdCorExpedicaoSolicitadaRN->contar($mdCorExpedicaoSolicitadaDTO);

        if ($qtdSolicitacaoExp > 0) {
            $objInfraException = new InfraException();
            $objInfraException->adicionarValidacao('Não é permitido Excluir este Documento, pois ele está relacionado a uma expedição pelos Correios.');
            $objInfraException->lancarValidacoes();
        }
    }

    public function cancelarDocumento(DocumentoAPI $objDocumentoAPI)
    {

        $idDocumento = $objDocumentoAPI->getIdDocumento();
        $mdCorExpedicaoSolicitadaRN = new MdCorExpedicaoSolicitadaRN();
        $mdCorExpedicaoSolicitadaDTO = new MdCorExpedicaoSolicitadaDTO();

        $mdCorExpedicaoSolicitadaDTO->adicionarCriterio(array('IdDocumentoPrincipal', 'IdDocumentoFormato')
            , array(InfraDTO::$OPER_IGUAL, InfraDTO::$OPER_IGUAL)
            , array($idDocumento, $idDocumento), InfraDTO::$OPER_LOGICO_OR);

        $mdCorExpedicaoSolicitadaDTO->retNumIdMdCorExpedicaoSolicitada();
        $mdCorExpedicaoSolicitadaDTO->setDistinct(true);
        $qtdSolicitacaoExp = $mdCorExpedicaoSolicitadaRN->contar($mdCorExpedicaoSolicitadaDTO);

        $MdCorRetornoArDocRN = new MdCorRetornoArDocRN();
        $MdCorRetornoArDocDTO = new MdCorRetornoArDocDTO();
        $MdCorRetornoArDocDTO->retNumIdMdCorRetornoAr();
        $MdCorRetornoArDocDTO->setNumIdDocumentoPrincipal($idDocumento);
        $MdCorRetornoArDocDTO = $MdCorRetornoArDocRN->consultar($MdCorRetornoArDocDTO);

        if (!is_null($MdCorRetornoArDocDTO)) {
            $objInfraException = new InfraException();
            $objInfraException->adicionarValidacao('Não é permitido Cancelar este Documento, pois ele está relacionado ao Retorno de um Aviso de Recebimento (AR) dos Correios.');
            $objInfraException->lancarValidacoes();
        }

        if ($qtdSolicitacaoExp > 0) {
            $objInfraException = new InfraException();
            $objInfraException->adicionarValidacao('Não é permitido Cancelar este Documento, pois ele está relacionado a uma expedição pelos Correios.');
            $objInfraException->lancarValidacoes();
        }
    }

    public function moverDocumento(DocumentoAPI $objDocumentoAPI, ProcedimentoAPI $objProcedimentoAPIOrigem, ProcedimentoAPI $objProcedimentoAPIDestino)
    {
        $idDocumento = $objDocumentoAPI->getIdDocumento();
        $mdCorExpedicaoSolicitadaRN = new MdCorExpedicaoSolicitadaRN();
        $mdCorExpedicaoSolicitadaDTO = new MdCorExpedicaoSolicitadaDTO();

        $mdCorExpedicaoSolicitadaDTO->adicionarCriterio(array('IdDocumentoPrincipal', 'IdDocumentoFormato')
            , array(InfraDTO::$OPER_IGUAL, InfraDTO::$OPER_IGUAL)
            , array($idDocumento, $idDocumento), InfraDTO::$OPER_LOGICO_OR);

        $mdCorExpedicaoSolicitadaDTO->retNumIdMdCorExpedicaoSolicitada();
        $mdCorExpedicaoSolicitadaDTO->setDistinct(true);
        $qtdSolicitacaoExp = $mdCorExpedicaoSolicitadaRN->contar($mdCorExpedicaoSolicitadaDTO);

        if ($qtdSolicitacaoExp > 0) {
            $objInfraException = new InfraException();
            $objInfraException->adicionarValidacao('Não é permitido Mover este Documento, pois ele está relacionado a uma expedição pelos Correios.');
            $objInfraException->lancarValidacoes();
        }

        return parent::moverDocumento($objDocumentoAPI, $objProcedimentoAPIOrigem, $objProcedimentoAPIDestino);
    }

    public function atualizarConteudoDocumento(DocumentoAPI $objDocumentoAPI)
    {
        $idDocumento = $objDocumentoAPI->getIdDocumento();
        $mdCorExpedicaoSolicitadaRN = new MdCorExpedicaoSolicitadaRN();
        $mdCorExpedicaoSolicitadaDTO = new MdCorExpedicaoSolicitadaDTO();

        $mdCorExpedicaoSolicitadaDTO->adicionarCriterio(array('IdDocumentoPrincipal', 'IdDocumentoFormato')
            , array(InfraDTO::$OPER_IGUAL, InfraDTO::$OPER_IGUAL)
            , array($idDocumento, $idDocumento), InfraDTO::$OPER_LOGICO_OR);

        $mdCorExpedicaoSolicitadaDTO->retNumIdMdCorExpedicaoSolicitada();
        $mdCorExpedicaoSolicitadaDTO->setDistinct(true);
        $qtdSolicitacaoExp = $mdCorExpedicaoSolicitadaRN->contar($mdCorExpedicaoSolicitadaDTO);

        if ($qtdSolicitacaoExp > 0) {
            $objInfraException = new InfraException();
            $objInfraException->adicionarValidacao('Não é permitido Alterar este Documento, pois ele está relacionado a uma expedição pelos Correios.');
            $objInfraException->lancarValidacoes();
        }
        return parent::atualizarConteudoDocumento($objDocumentoAPI);
    }

    public function anexarProcesso(ProcedimentoAPI $objProcedimentoAPIPrincipal, ProcedimentoAPI $objProcedimentoAPIAnexado)
    {

        $retorno = $this->verificaExisteSolicitacaoSemAR($objProcedimentoAPIAnexado);

        if ($retorno) {
            $objInfraException = new InfraException();
            $objInfraException->adicionarValidacao('Não é permitido Anexar este Processo, pois ele está relacionado a uma expedição pelos Correios ainda em andamento.');
            $objInfraException->lancarValidacoes();
        }
    }

    public function sobrestarProcesso(ProcedimentoAPI $objProcedimentoAPI, $objProcedimentoAPIVinculado)
    {

        $retorno = $this->verificaExisteSolicitacaoSemAR($objProcedimentoAPI);

        if ($retorno) {
            $objInfraException = new InfraException();
            $objInfraException->adicionarValidacao('Não é permitido Sobrestar este Processo, pois ele está relacionado a uma expedição pelos Correios ainda em andamento.');
            $objInfraException->lancarValidacoes();
        }
    }

    public function processarControladorAjax($strAcao)
    {
        $xml = null;

        switch ($_GET['acao_ajax']) {

            case 'md_cor_unidade_auto_completar':
                $arrObjUnidadeDTO = UnidadeINT::autoCompletarUnidades($_POST['palavras_pesquisa'], true, '');
                $xml = InfraAjax::gerarXMLItensArrInfraDTO($arrObjUnidadeDTO, 'IdUnidade', 'Sigla');
                break;

            case 'md_cor_extensao_arquivo_listar_todos':
                $arrObjArquivoExtensaoPeticionamentoDTO = MdCorExtensaoArquivoINT::autoCompletarExtensao($_POST['extensao']);
                $xml = InfraAjax::gerarXMLItensArrInfraDTO($arrObjArquivoExtensaoPeticionamentoDTO, 'IdArquivoExtensao', 'Extensao');
                break;

            case 'md_cor_unidade_mapeadas_auto_completar':
                if ($_GET['intIdUnidadeExpExcecao'] == '*') {
                    $arrObjUnidadeDTO = MdCorMapeamentoUniExpSolINT::autoCompletarUnidades($_POST['palavras_pesquisa'], false, '');
                } else {
                    $arrObjUnidadeDTO = MdCorMapeamentoUniExpSolINT::autoCompletarUnidades($_POST['palavras_pesquisa'], true, '', $_GET['intIdUnidadeExpExcecao']);
                }
                $xml = InfraAjax::gerarXMLItensArrInfraDTO($arrObjUnidadeDTO, 'IdUnidade', 'Sigla');
                break;

            case 'md_cor_unidades_solicitantes_auto_completar':
                $arrObjUnidadeDTO = MdCorMapeamentoUniExpSolINT::autoCompletarUnidadesMapeadas($_POST['palavras_pesquisa']);
                $xml = InfraAjax::gerarXMLItensArrInfraDTO($arrObjUnidadeDTO, 'IdUnidadeSolicitante', 'DescricaoUnidadeSolicitante');
                break;

            case 'contato_destinatario_nao_habilitado':
                $arrObjRelContJustDTO = MdCorRelContatoJustificativaINT::listarDestinatarioNaoHabilitado($_POST['arrIdDestinatario']);
                $arrRelContJustDTO = InfraArray::converterArrInfraDTO($arrObjRelContJustDTO);
                $xml = '<result>' . InfraArray::converterArrayXml($arrRelContJustDTO) . '</result>';
                break;

            case 'validar_documento_api':
                $xml = MdCorExpedicaoFormatoINT::ValidarDocumentoAPI($_POST['arrIdSocitacao']);
                return $xml;
                break;

            case 'validar_documento_id_protocoloFormatado_api':
                $xml = MdCorExpedicaoFormatoINT::validarDocumentoIdProtocoloFormatadoAPI($_POST['arrIdProtocoloFormatado']);
                return $xml;
                break;

            case 'contato_destinatario_nao_habilitado_duplicidade':
                $arrObjRelContJustDTO = MdCorRelContatoJustificativaINT::listarDestinatarioNaoHabilitadoDuplicidade($_POST['arrIdDestinatario']);
                $arrRelContJustDTO = InfraArray::converterArrInfraDTO($arrObjRelContJustDTO);
                $xml = '<result>' . InfraArray::converterArrayXml($arrRelContJustDTO) . '</result>';
                break;

            case 'md_cor_servicos_postais_buscar':
	            $_POST['txtCNPJ'] = InfraUtil::retirarFormatacao($_POST['txtCNPJ']);
	            $xml = MdCorServicoPostalINT::retornarServicosPostais($_POST['txtNumeroContratoCorreio'], $_POST['txtCNPJ'], $_POST['id_contrato']);
                break;

            case 'md_cor_numero_processo_validar':
                $xml = MdCorContratoINT::gerarXMLvalidacaoNumeroProcesso($_POST['txtNumeroProcessoContratacao']);
                break;

            case 'md_cor_verifica_contrato':
                $xml = MdCorContratoINT::gerarXMLvalidacaoNumeroContrato($_POST['txtNumeroContratoCorreio'], $_POST['hdnIdMdCorContrato']);
                break;

            case 'md_cor_serie_exp_auto_completar':
                $arrObjSerieDTO = MdCorSerieExpINT::autoCompletarSerieExpedicao($_POST['palavras_pesquisa']);
                $xml = InfraAjax::gerarXMLItensArrInfraDTO($arrObjSerieDTO, 'IdSerie', 'Nome');
                break;

            case 'md_cor_map_unid_servico_montar_select':
                $arrObjCondutaDTO = MdCorServicoPostalINT::montarSelectId_Descricao_MdCorServicoPostal(' ', '', null, $_POST['IdMdCorContrato']);
                $xml = InfraAjax::gerarXMLSelect($arrObjCondutaDTO);
                break;

            case 'md_cor_expedicao_cadastro_protocolos_autocompletar':
                $arrObjDTO = MdCorExpedicaoSolicitadaProtocoloAnexoINT::autoCompletarProtocoloAnexo($_POST['palavras_pesquisa'], $_POST['id_doc']);
                $xml = InfraAjax::gerarXMLItensArrInfraDTO($arrObjDTO, 'IdProtocolo2', 'ProtocoloFormatadoProtocolo2');
                break;

            case 'md_cor_contato_listar':
                $xml = MdCorContatoINT::listarContato(array("id_contato" => $_POST['idContato']));
                break;
            case 'md_cor_contar_arquivos':
                $xml = MdCorRetornoArINT::contarAr($_POST['strNomeArquivo']);
                break;

//            case 'md_cor_expedicao_plp_listar':
//                require_once dirname(__FILE__) . '/md_cor_expedicao_plp_lista.php';
//                return true;

            case 'md_cor_expedicao_objeto_listar':
                require_once dirname(__FILE__) . '/md_cor_expedicao_objeto_lista.php';
                return true;

            case 'md_cor_expedicao_plp_consultar':
                require_once dirname(__FILE__) . '/md_cor_expedicao_plp_consulta.php';
                return true;

            //9372
            case 'md_cor_dados_documento_consultar':
                $xml = MdCorParametroArINT::consultarDadosDocumento($_POST);
                break;
            case 'md_cor_atualiza_link_unidade':
                $xml = MdCorMapeamentoUniExpSolINT::retornaLinkAtualizado($_POST);
                break;
            //9373
            case 'md_cor_retorno_ar_autenticar_lote':
                $xml = MdCorParametroArINT::gerarUrl($_POST);
                break;

            case 'md_cor_contrato_desativar' :
            case 'md_cor_contrato_reativar' :
            case 'md_cor_contrato_excluir' :
            case 'md_cor_contrato_selecionar' :
            case 'md_cor_contrato_listar' :
                require_once dirname(__FILE__) . '/md_cor_contrato_lista.php';
                return true;
            case 'md_cor_numero_processo_cobranca_validar':
                $xml = MdCorContratoINT::gerarXMLvalidacaoNumeroProcessoCobranca($_POST['txtNumeroProcessoContratacao']);
                break;
            case 'md_cor_parametro_ar_cadastro_destinatario':
                $strOptions = MdCorArCobrancaINT::selecionarDestinatario($_POST);
                $xml = InfraAjax::gerarXMLItensArrInfraDTO($strOptions, 'IdContato', 'Nome');
                break;
            case 'md_cor_valida_endereco_rastreio':
                $xml = MdCorParametroRastreioINT::gerarXMLvalidacaoRastreio($_POST);
                break;
            case 'md_cor_tipo_midia_listar':
                $xml = MdCorExpedicaoSolicitadaProtocoloAnexoINT::retornaTipoMidiaExiste($_POST['idProtocolo']);
                break;
                case 'md_cor_formato_expedicao_apenas_midia':
                    $xml = MdCorExpedicaoSolicitadaProtocoloAnexoINT::verificarAnexoSomenteMidia($_POST['idProtocolo']);
                break;
            case 'md_validar_destinatario_solicitacao_expedicao':
                $xml = MdCorExpedicaoSolicitadaINT::validaContatoPreeenchido($_POST['id_contato'], false, $_POST['id_contrato_servico_postal']);
                break;
            case 'md_cor_plp_imprimir_ar_verificar':
                $xml = MdCorPlpINT::verificarImpressaoAR($_GET['id_md_cor_plp'], explode(',', $_POST['hdnInfraItensSelecionados']));
                break;
            case 'md_cor_verificar_servico_mapeado':
                $xml = ( new MdCorContratoRN )->verificarServicoMapeado($_POST['idMdCorServicoPostal']);
                break;  
            case 'md_cor_valid_exc_servico_mapeado':
                $xml = MdCorContratoRN::verificarUnidServicoMapeado( $_POST['idUnidMapServicoPostal'] , $_POST['idServicoPostal'] );
                break;  
            case 'md_cor_reativa_desativa_contrato_servico':
                try {
                    $objDTO = new MdCorMapUnidServicoDTO();
                    $objRN  = new MdCorMapUnidServicoRN();
                    $objDTO->setNumIdMdCorMapUnidServico( $_POST['id_contrato_servico'] );
                    $objDTO->retTodos();
                    $arrDTO = $objRN->listar( $objDTO );
                    if ( $_POST['acao'] == 'D' )
                        $objRN->desativar( $arrDTO );
                    else
                        $objRN->reativar( $arrDTO );

                    $xml = "<Resultado><Sucesso>S</Sucesso></Resultado>";
                } catch ( Exception $e ) {
                    $xml = "<Resultado><Sucesso>N</Sucesso></Resultado>";
                }
                break;

	        case 'md_cor_change_serv_postal':
		        $xml = "<Documento>";
	            if ( !empty( $_POST['idServPostal'] ) ) {
			        $objDTO = MdCorServicoPostalINT::getInfoServicoPostalPorId( $_POST['idServPostal'] );
			        if ( $objDTO ) {
				        if ( $objDTO->getStrSinAnexarMidia() == 'S' ) {
				         $xml .= "<Retorno>S</Retorno>";
			          } else {
				         $xml .= "<Retorno>N</Retorno>";
				        }
			        }
		        }
		        $xml .= "</Documento>";
	            break;

	        case 'md_cor_valida_arq_ext':
	            $xml = "<Documento>";
	            $xml .= MdCorServicoPostalINT::validaArqExt( $_POST );
		        $xml .= "</Documento>";

	            break;

            case 'md_cor_plp_cancelar_plp':
                $xml = '<Documento>';
                $arr = ['idPlp' => $_POST['idPlp'], 'idContrato' => $_POST['idContrato']];
                $rs  = ( new MdCorPlpRN() )->cancelarPlp( $arr );
                if (is_array($rs)) {
                    $xml .= '<Sucesso>N</Sucesso>';
                    $xml .= '<Msg>'.$rs['msg'].'</Msg>';
                } else {
                    $xml .= '<Sucesso>S</Sucesso>';
                }

                $xml .= '</Documento>';
                break;
        }

        return $xml;
    }

    public function processarControladorExterno($strAcao)
    {
        switch ($strAcao) {

        }
        return false;
    }

    public function montarBotaoDocumento(ProcedimentoAPI $objProcedimentoAPI, $arrObjDocumentoAPI)
    {
        $arrBotoes = array();
        foreach ($arrObjDocumentoAPI as $documentoAPI) {

            $idDocumento = $documentoAPI->getIdDocumento();

            //Inicio EU9360 - Solicitar Expedição pelos correios.

            $bolAcaoSolicitarExpedicao = SessaoSEI::getInstance()->verificarPermissao('md_cor_expedicao_solicitada_cadastrar');
            $bolAcaoSolicitarExpedicaoMapUnidServicoListar = SessaoSEI::getInstance()->verificarPermissao('md_cor_map_unid_servico_listar');

            //==============================================================//
            //@todo só mostrar o botao depois que atender as regras necessarias
            // Tipo de Documento estiver habilitado como de Expedição;      //
            // for Documento Gerado; estiver Assinado;                      //
            // o Processo estiver aberto na Unidade geradora do Documento e //
            // não existir solicitação de expedição em aberto               //
            // usando esse doc como principal da expedição.                 //
            //==============================================================//

            $idSerie = $documentoAPI->getIdSerie();
            $isAssinado = $documentoAPI->getSinAssinado();
            $idUnidadeGeradora = $documentoAPI->getIdUnidadeGeradora();

            //checar se o tipo de documento esta habilitado como Tipo de Expedição

            $bolAcaoListarSerie = SessaoSEI::getInstance()->verificarPermissao('md_cor_serie_exp_listar');

            $isTipoDocHabilitado = false;
            if ($bolAcaoListarSerie === true) {
                $objMdCorSerieExpRN = new MdCorSerieExpRN();
                $objMdCorSerieExpDTO = new MdCorSerieExpDTO();
                $objMdCorSerieExpDTO->retTodos();
                $objMdCorSerieExpDTO->setNumIdSerie($idSerie);
                $arrMdCorSerieExpDTO = $objMdCorSerieExpRN->listar($objMdCorSerieExpDTO);

                if ($arrMdCorSerieExpDTO != null && is_array($arrMdCorSerieExpDTO) && count($arrMdCorSerieExpDTO) > 0) {
                    $isTipoDocHabilitado = true;
                }
            }

            //checar se o Processo está aberto na Unidade geradora do Documento (a forma de checar onde o processo está aberto muda caso o processo seja sigiloso)
            $isProcessoAbertoUnidadeGeradoraDoDocumento = false;

            if ($objProcedimentoAPI->getSinAberto() == 'S') {
                $isProcessoAbertoUnidadeGeradoraDoDocumento = true;
            }

            $bolAcaoListarExpedicao = SessaoSEI::getInstance()->verificarPermissao('md_cor_expedicao_solicitada_listar');

            $bolAcaoConsultarExpedicao = SessaoSEI::getInstance()->verificarPermissao('md_cor_expedicao_solicitada_consultar');

            $isSolicitacaoExpedicaoEmAberto = false;

            if ($bolAcaoListarExpedicao || $bolAcaoConsultarExpedicao) {

                //checar se não existe solicitação de expedição em aberto
                $rnExpSolicitada = new MdCorExpedicaoSolicitadaRN();
                $bdExpSolicitada = new MdCorExpedicaoSolicitadaBD(BancoSEI::getInstance());
                $dtoExpSolicitada = new MdCorExpedicaoSolicitadaDTO();
                $dtoExpSolicitada->retNumIdMdCorExpedicaoSolicitada();
                $dtoExpSolicitada->retDthDataExpedicao();
                $dtoExpSolicitada->retDblIdProtocolo();
                $dtoExpSolicitada->retStrSinDevolvido();
                $dtoExpSolicitada->setDblIdDocumentoPrincipal($idDocumento);
                $dtoExpSolicitada->setDblIdProtocolo($objProcedimentoAPI->getIdProcedimento());
//                $dtoExpSolicitada->adicionarCriterio(array('DataExpedicao', 'DataExpedicao'), array(InfraDTO::$OPER_IGUAL, InfraDTO::$OPER_IGUAL), array('', null), InfraDTO::$OPER_LOGICO_OR);

                $arrDtoExpSolicitada = $rnExpSolicitada->listar($dtoExpSolicitada);

                if ($arrDtoExpSolicitada != null && is_array($arrDtoExpSolicitada) && count($arrDtoExpSolicitada) > 0) {
                    $isSolicitacaoExpedicaoEmAberto = true;
                }
            }

            $params = array();
            $params['bolAcaoSolicitarExpedicao'] = $bolAcaoSolicitarExpedicao;
            $params['isAssinado'] = $isAssinado;
            $params['isTipoDocHabilitado'] = $isTipoDocHabilitado;
            $params['objProcedimentoAPI->getSinAberto()'] = $objProcedimentoAPI->getSinAberto();
            $params['isSolicitacaoExpedicaoEmAberto'] = $isSolicitacaoExpedicaoEmAberto;
            $params['isProcessoAbertoUnidadeGeradoraDoDocumento'] = $isProcessoAbertoUnidadeGeradoraDoDocumento;

            $infraParametrosRN = new InfraParametroRN();
            $objInfraParametrosDTO = new InfraParametroDTO();
            $objInfraParametrosDTO->retStrValor();
            $objInfraParametrosDTO->setStrNome('MODULO_CORREIOS_ID_DOCUMENTO_EXPEDICAO');
            $objInfraParametrosDTO = $infraParametrosRN->consultar($objInfraParametrosDTO);
            $arrIdSerieDocumento = array();
            if ($objInfraParametrosDTO) {
                $arrIdSerieDocumento = explode(',', $objInfraParametrosDTO->getStrValor());
            }

            $bolExpedicaoExterno = false;
            if (in_array($documentoAPI->getIdSerie(), $arrIdSerieDocumento) && $documentoAPI->getSubTipo() == 'X') {
                $bolExpedicaoExterno = true;
            }

            if (($bolAcaoSolicitarExpedicao && $isAssinado == 'S' && $isTipoDocHabilitado && $isProcessoAbertoUnidadeGeradoraDoDocumento) || $bolExpedicaoExterno ||
                (!$this->isUnidadeSolicitante() && $isSolicitacaoExpedicaoEmAberto)) {

                if (($bolAcaoConsultarExpedicao && $bolAcaoSolicitarExpedicaoMapUnidServicoListar)) {

                    if (!$isSolicitacaoExpedicaoEmAberto) {
                        $strLink = SessaoSEI::getInstance()->assinarLink('controlador.php?acao=md_cor_expedicao_solicitada_cadastrar&arvore=1&id_doc=' . $idDocumento . "&staAberto=" . $objProcedimentoAPI->getSinAberto());
                        $title = "Solicitar Expedição pelos Correios";
                    } else {

                        //já tem expedição solicitada usando esse doc como principal
                        $idExpedicao = $arrDtoExpSolicitada[0]->getNumIdMdCorExpedicaoSolicitada();

                        $isConsultar = $arrDtoExpSolicitada[0]->getStrSinDevolvido() == 'S' ? '' : '&isConsultar=S';

                        $strLink = SessaoSEI::getInstance()->assinarLink('controlador.php?acao=md_cor_expedicao_solicitada_consultar&arvore=1&id_doc=' . $idDocumento . '&id_md_cor_expedicao_solicitada=' . $idExpedicao . "&staAberto=" . $objProcedimentoAPI->getSinAberto() . $isConsultar);
                        $title = "Consultar Expedição pelos Correios";
                    }

                    $imgIcone = "modulos/correios/imagens/svg/solicitacao_correios.svg?".Icone::VERSAO;

                    $objMdCorMapUnidServicoDTO = new MdCorMapUnidServicoDTO();
                    $objMdCorMapUnidServicoDTO->retNumIdMdCorServicoPostal();
                    $objMdCorMapUnidServicoDTO->setDistinct(true);
                    $objMdCorMapUnidServicoDTO->setStrSinAtivo('S');
                    $objMdCorMapUnidServicoDTO->setNumIdUnidadeSolicitante(SessaoSei::getInstance()->getNumIdUnidadeAtual());

                    $strBotaoSolicitarExpedicao = '<a href="' . $strLink . '"class="botaoSEI">';
                    $strBotaoSolicitarExpedicao .= '<img class="infraCorBarraSistema" id="solicitarExpedicao" src="' . $imgIcone . '" alt="' . $title . '" title="' . $title . '">';
                    $strBotaoSolicitarExpedicao .= '</a>';

                    $arrBotoes[$idDocumento][] = $strBotaoSolicitarExpedicao;
                }
            }
        }

        return $arrBotoes;
    }

    public function isUnidadeSolicitante()
    {

        $objMdCorMapeamentoUniExpSolDTO = new MdCorMapeamentoUniExpSolDTO();
        $objMdCorMapeamentoUniExpSolDTO->retStrSiglaUnidadeExpedidora();
        $objMdCorMapeamentoUniExpSolDTO->retStrDescricaoUnidadeExpedidora();
        $objMdCorMapeamentoUniExpSolDTO->setDistinct(true);
        $objMdCorMapeamentoUniExpSolDTO->setStrSinAtivo('S');
        $objMdCorMapeamentoUniExpSolDTO->setNumIdUnidadeSolicitante(SessaoSei::getInstance()->getNumIdUnidadeAtual());
        $objMdCorMapeamentoUniExpSolRN = new MdCorMapeamentoUniExpSolRN();
        $arrObjMdCorMapeamentoUniExpSolDTO = $objMdCorMapeamentoUniExpSolRN->listar($objMdCorMapeamentoUniExpSolDTO);

        return (count($arrObjMdCorMapeamentoUniExpSolDTO) > 0);
    }

    public function montarBotaoProcesso(ProcedimentoAPI $objProcedimentoAPI)
    {
        $arrBotoes = array();
        $objMdCorExpedSolictRN = new MdCorExpedicaoSolicitadaRN();
        $bolProcedimentoAberto = $objProcedimentoAPI->getSinAberto() == 'S';
        $bolPermissaoAcesso = $objProcedimentoAPI->getCodigoAcesso() > 0;
//        $bolProcedimentoAberto = true;
        //Inicio EU9362 - Lista de Expedições pelos Correios
        $bolAcaoListarExpedicao = SessaoSEI::getInstance()->verificarPermissao('md_cor_expedicao_processo_listar');
        $processoPossuiExpedicao = false;
        if ($bolAcaoListarExpedicao === true) {
            $bolAcaoprocessoPossuiExpedicao = SessaoSEI::getInstance()->verificarPermissao('md_cor_expedicao_solicitada_listar');
            if ($bolAcaoprocessoPossuiExpedicao === true) {
                $processoPossuiExpedicao = $objMdCorExpedSolictRN->verificarProcessoPossuiExpedicao($objProcedimentoAPI->getIdProcedimento());
            }
        }

        //=======================================================================//
        // Exibido quando o Processo estiver aberto em sua Unidade;              //
        // e existir pelo menos uma Expedição realizada no Processo em questão.  //
        //=======================================================================//
//        echo "<pre>";
//        var_dump(array($bolAcaoListarExpedicao, $bolProcedimentoAberto, $bolPermissaoAcesso, $processoPossuiExpedicao));
//        die;
//        if ($bolAcaoListarExpedicao && $bolProcedimentoAberto && $bolPermissaoAcesso && $processoPossuiExpedicao) {
        if ($bolAcaoListarExpedicao && $bolPermissaoAcesso && $processoPossuiExpedicao) {

            $strLink = SessaoSEI::getInstance()->assinarLink('controlador.php?acao=md_cor_expedicao_processo_listar&arvore=1&id_procedimento=' . $_GET['id_procedimento']);

            $imgIcone = "modulos/correios/imagens/svg/solicitacao_correios.svg?".Icone::VERSAO;
            $title = "Listar Expedições pelos Correios";

            $strBotaoListarExpedicao = '<a href="' . $strLink . '"class="botaoSEI">';
            $strBotaoListarExpedicao .= '<img class="infraCorBarraSistema" src="' . $imgIcone . '" alt="' . $title . '" title="' . $title . '">';
            $strBotaoListarExpedicao .= '</a>';

            $arrBotoes[] = $strBotaoListarExpedicao;
        }
        //Fim EU9362 - Lista de Expedições pelos Correios

        return $arrBotoes;
    }

    public function montarIconeDocumento(ProcedimentoAPI $objProcedimentoAPI, $arrObjDocumentoAPI)
    {
        $arrIcones = [];
        if ($objProcedimentoAPI->getCodigoAcesso() > 0) {
            $bolAcaoAbcDocumentoProcessar = SessaoSEI::getInstance()->verificarPermissao('md_cor_expedicao_processo_listar');

            foreach ($arrObjDocumentoAPI as $objDocumento) {
                if ($bolAcaoAbcDocumentoProcessar) {
                    $idDocumento = $objDocumento->getIdDocumento();
                    $mdCorExpedicaoSolicitadaRN = new MdCorExpedicaoSolicitadaRN();
                    $mdCorExpedicaoSolicitadaDTO = new MdCorExpedicaoSolicitadaDTO();
                    $mdCorExpedicaoSolicitadaDTO->retNumIdMdCorExpedicaoSolicitada();
                    $mdCorExpedicaoSolicitadaDTO->retStrSinRecebido();
                    $mdCorExpedicaoSolicitadaDTO->retStrSinNecessitaAr();
                    $mdCorExpedicaoSolicitadaDTO->retStrCodigoRastreamento();
                    $mdCorExpedicaoSolicitadaDTO->retDthDataExpedicao();
                    $mdCorExpedicaoSolicitadaDTO->retNumIdDocumentoAr();
                    $mdCorExpedicaoSolicitadaDTO->retNumIdProcedimento();
                    $mdCorExpedicaoSolicitadaDTO->retNumIdMdCorParamArInfrigencia();
                    $mdCorExpedicaoSolicitadaDTO->retStrStaPlp();
                    $mdCorExpedicaoSolicitadaDTO->retNumIdMdCorPlp();
                    $mdCorExpedicaoSolicitadaDTO->retStrSinDevolvido();
                    $mdCorExpedicaoSolicitadaDTO->setDblIdDocumentoPrincipal($idDocumento);
                    $mdCorExpedicaoSolicitadaDTO->setOrdNumIdMdCorRetornoArDoc(InfraDTO::$TIPO_ORDENACAO_DESC);

                    $qtd = $mdCorExpedicaoSolicitadaRN->contar($mdCorExpedicaoSolicitadaDTO);

                    $statusRecebidoRastreamento = "";

                    $idDocumentoAr = false;
                    if ($qtd > 0) {
                        $mdCorExpedicaoSolicitadaDTO = current($mdCorExpedicaoSolicitadaRN->listar($mdCorExpedicaoSolicitadaDTO));
                        if (!is_null($mdCorExpedicaoSolicitadaDTO)) {
                            if($mdCorExpedicaoSolicitadaDTO->getStrSinNecessitaAr() == 'N'){
                                $objExpedAndamentoRN = new MdCorExpedicaoAndamentoRN();
                                $arrObjMdCorExpAndamentoDTO = $objExpedAndamentoRN->getDadosAndamentosParaRastreio($mdCorExpedicaoSolicitadaDTO->getNumIdMdCorExpedicaoSolicitada());
                                if (isset($arrObjMdCorExpAndamentoDTO)) {
                                    if($arrObjMdCorExpAndamentoDTO[0]->getStrStaRastreioModulo() == 'S') {
                                        $statusRecebidoRastreamento = 'S';
                                    } elseif($arrObjMdCorExpAndamentoDTO[0]->getStrStaRastreioModulo() == 'I') {
                                        $statusRecebidoRastreamento = 'N';
                                    }
                                }
                            }
                            $target = 'ifrVisualizacao';
                            $statusRecebido = $mdCorExpedicaoSolicitadaDTO->getStrSinRecebido();

                            $icone = 'modulos/correios/imagens/svg/objeto_aguardando_retorno.svg?'.Icone::VERSAO;
                            $title = 'AR enviado aguardando retorno';
                            $link = SessaoSEI::getInstance()->assinarLink('controlador.php?acao=md_cor_expedicao_detalhar_rastreio&co_rastreio=' . $mdCorExpedicaoSolicitadaDTO->getStrCodigoRastreamento());

                            if ($statusRecebido == 'S') {
                                $idDocumentoAr = $mdCorExpedicaoSolicitadaDTO->getNumIdDocumentoAr();
                                $idProcedimento = $mdCorExpedicaoSolicitadaDTO->getNumIdProcedimento();
                                $icone = 'modulos/correios/imagens/svg/objeto_retornado_sucesso.svg?'.Icone::VERSAO;
                                $title = 'AR retornado com sucesso!';
                                $link = $link = SessaoSEI::getInstance()->assinarLink('controlador.php?acao=arvore_visualizar&acao_origem=' . $_GET['acao'] . '&id_procedimento=' . $idProcedimento . '&id_documento=' . $idDocumentoAr);
                            }
                            if($statusRecebidoRastreamento == "S") {
                                $icone = 'modulos/correios/imagens/svg/objeto_retornado_sucesso.svg?'.Icone::VERSAO;
                                $title = 'Objeto entregue com sucesso!';
                            }
                            if($statusRecebidoRastreamento == "N") {
                                $icone = 'modulos/correios/imagens/svg/objeto_extraviado.svg?'.Icone::VERSAO;
                                $title = 'Objeto não entregue!';
                            }

                            $stDevolvido = $mdCorExpedicaoSolicitadaDTO->getNumIdMdCorParamArInfrigencia();
                            if (!is_null($stDevolvido)) {
                                $icone = 'modulos/correios/imagens/svg/objeto_devolvido.svg?'.Icone::VERSAO;
                                $title = 'Objeto devolvido';
                            }

                            $stPlpNaoGerada = $mdCorExpedicaoSolicitadaDTO->getNumIdMdCorPlp();
                            if (is_null($stPlpNaoGerada)) {
                                $icone = 'modulos/correios/imagens/svg/plp_nao_gerada.svg?'.Icone::VERSAO;
                                $title = 'Aguardando Expedição';
                                $link = $link = SessaoSEI::getInstance()->assinarLink("controlador.php?acao=md_cor_expedicao_solicitada_consultar&arvore=1&id_doc={$idDocumento}&id_md_cor_expedicao_solicitada=" . $mdCorExpedicaoSolicitadaDTO->getNumIdMdCorExpedicaoSolicitada() . "&staAberto=" . $objProcedimentoAPI->getSinAberto().'&isConsultar=S');
                            }

                            $stPlpGerada = $mdCorExpedicaoSolicitadaDTO->getStrStaPlp();
                            if ($stPlpGerada == MdCorPlpRN::$STA_GERADA) {
                                $icone = 'modulos/correios/imagens/svg/plp_gerada.svg?'.Icone::VERSAO;
                                $title = 'Documento em procedimento de postagem';
                                $link = $link = SessaoSEI::getInstance()->assinarLink("controlador.php?acao=md_cor_expedicao_solicitada_consultar&arvore=1&id_doc={$idDocumento}&id_md_cor_expedicao_solicitada=" . $mdCorExpedicaoSolicitadaDTO->getNumIdMdCorExpedicaoSolicitada() .'&isConsultar=S');
                            }

                            $stSolicitacaoDevolvida = $mdCorExpedicaoSolicitadaDTO->getStrSinDevolvido();

                            if ($stSolicitacaoDevolvida == "S") {
                                $icone = 'modulos/correios/imagens/svg/devolucao_solicitacao.svg?'.Icone::VERSAO;
                                $title = 'Solicitação de expedição devolvida';
                                $link = $link = SessaoSEI::getInstance()->assinarLink("controlador.php?acao=md_cor_expedicao_solicitada_consultar&arvore=1&id_doc={$idDocumento}&id_md_cor_expedicao_solicitada=" . $mdCorExpedicaoSolicitadaDTO->getNumIdMdCorExpedicaoSolicitada() . "&staAberto=" . $objProcedimentoAPI->getSinAberto().'&isAlterar=S');
                            }
                            
                            $arrIcones[$idDocumento] = [];

                            if ($idDocumentoAr == false) {
                                $idDocumentoLink = $idDocumento;
                            } else {
                                $idDocumentoLink = $idDocumentoAr;
                            }

                            $objArvoreAcaoItemAPI = new ArvoreAcaoItemAPI();
                            $objArvoreAcaoItemAPI->setTipo('');
                            $objArvoreAcaoItemAPI->setId('' . $idDocumentoLink);
                            $objArvoreAcaoItemAPI->setIdPai($idDocumento);
                            $objArvoreAcaoItemAPI->setTitle($title);
                            $objArvoreAcaoItemAPI->setIcone($icone);

                            $objArvoreAcaoItemAPI->setTarget($target);
                            $objArvoreAcaoItemAPI->setHref($link);

                            $objArvoreAcaoItemAPI->setSinHabilitado('S');

                            $arrIcones[$idDocumento][] = $objArvoreAcaoItemAPI;
                        }
                    }
//                    }
                }
            }

            return $arrIcones;
        }
    }

    public function verificaExisteSolicitacaoSemAR($objProcedimentoAPI)
    {
        $objRelProtocoloProtocoloDTO = new RelProtocoloProtocoloDTO();
        $objRelProtocoloProtocoloDTO->retTodos();
        $objRelProtocoloProtocoloDTO->setDblIdProtocolo1($objProcedimentoAPI->getIdProcedimento());
        $objRelProtocoloProtocoloDTO->setStrStaAssociacao(RelProtocoloProtocoloRN::$TA_DOCUMENTO_ASSOCIADO);
        $objRelProtocoloProtocoloRN = new RelProtocoloProtocoloRN();
        $objRelProtocoloProtocoloDTO = $objRelProtocoloProtocoloRN->listarRN0187($objRelProtocoloProtocoloDTO);

        $arrIdRelProtocoloProtocolo = array();

        foreach ($objRelProtocoloProtocoloDTO as $objRelProtocoloProtocolo) {
            $arrIdRelProtocoloProtocolo[] = $objRelProtocoloProtocolo->getDblIdRelProtocoloProtocolo();
            $MdCorExpedicaoSolicitadaRN = new MdCorExpedicaoSolicitadaRN();
            $objMdCorExpedicaoSolicitadaDTO = new MdCorExpedicaoSolicitadaDTO();
            $objMdCorExpedicaoSolicitadaDTO->retDthDataSolicitacao();
            $objMdCorExpedicaoSolicitadaDTO->retNumIdMdCorExpedicaoSolicitada();
            $objMdCorExpedicaoSolicitadaDTO->retDblIdDocumentoPrincipal();
            $objMdCorExpedicaoSolicitadaDTO->setDblIdDocumentoPrincipal($objRelProtocoloProtocolo->getDblIdProtocolo2());
//            $arrMdCorExpedicaoSolicitadaDTO = $MdCorExpedicaoSolicitadaRN->consultar($objMdCorExpedicaoSolicitadaDTO);
            $arrObjMdCorExpedicaoSolicitadaDTO = $MdCorExpedicaoSolicitadaRN->listar($objMdCorExpedicaoSolicitadaDTO);

            if ($arrObjMdCorExpedicaoSolicitadaDTO) {
                foreach ($arrObjMdCorExpedicaoSolicitadaDTO as $mdCorExpedicaoSolicitadaDTO) {
                    $mdCorExpedicaoSolicitadaRN = new MdCorExpedicaoSolicitadaRN();
                    $objMdCorExpedicaoSolicitadaDTO = new MdCorExpedicaoSolicitadaDTO();

                    $objMdCorExpedicaoSolicitadaDTO->setNumIdMdCorExpedicaoSolicitada($mdCorExpedicaoSolicitadaDTO->getNumIdMdCorExpedicaoSolicitada());
                    $objMdCorExpedicaoSolicitadaDTO->setDblIdDocumentoPrincipal($mdCorExpedicaoSolicitadaDTO->getDblIdDocumentoPrincipal());
                    $objMdCorExpedicaoSolicitadaDTO->retNumIdUnidade();
                    $objMdCorExpedicaoSolicidata = $mdCorExpedicaoSolicitadaRN->consultar($objMdCorExpedicaoSolicitadaDTO);

                    $idUsuarioLogado = SessaoSEI::getInstance()->getNumIdUsuario();
                    $idUnidadeLogado = SessaoSEI::getInstance()->getNumIdUnidadeAtual();
                    $idUnidadeParametro = $objMdCorExpedicaoSolicidata->getNumIdUnidade();

                    SessaoSEI::getInstance()->setBolHabilitada(false);
                    SessaoSEI::getInstance()->simularLogin(null, null, $idUsuarioLogado, $idUnidadeParametro);

                    $MdCorRetornoArDocRN = new MdCorRetornoArDocRN();
                    $objMdCorRetornoArDocDTO = new MdCorRetornoArDocDTO();
                    $objMdCorRetornoArDocDTO->setNumIdDocumentoPrincipal($mdCorExpedicaoSolicitadaDTO->getDblIdDocumentoPrincipal());
                    $objMdCorRetornoArDocDTO->setNumMaxRegistrosRetorno(1);
                    $objMdCorRetornoArDocDTO->retDtaDataRetorno();
                    $arrMdCorRetornoArDocDTO = $MdCorRetornoArDocRN->consultar($objMdCorRetornoArDocDTO);

                    SessaoSEI::getInstance()->setBolHabilitada(true);
                    SessaoSEI::getInstance()->simularLogin(null, null, $idUsuarioLogado, $idUnidadeLogado);

                    if (!$arrMdCorRetornoArDocDTO) {
                        return true;
                    }
                }
            }
        }
        return false;
    }

    public function desativarUnidade($arrObjUnidadeDTO)
    {

        $mdCorExpedicaoSolicitadaRN = new MdCorExpedicaoSolicitadaRN();
        $mdCorExpedicaoSolicitadaDTO = new MdCorExpedicaoSolicitadaDTO();
        $mdCorExpedicaoSolicitadaDTO->setNumIdUnidade($_POST['hdnInfraItemId']);
        $mdCorExpedicaoSolicitadaDTO->retDblIdDocumentoPrincipal();
        $mdCorExpedicaoSolicitadaDTO->retDblIdProtocolo();
        $arrObjExpedicaoSolicitadaDTO = $mdCorExpedicaoSolicitadaRN->listar($mdCorExpedicaoSolicitadaDTO);

        $existe = false;

        foreach ($arrObjExpedicaoSolicitadaDTO as $item) {
            $seiRN = new SeiRN();
            $procedimentoAPI = new EntradaConsultarProcedimentoAPI();
            $procedimentoAPI->setIdProcedimento($item->getDblIdProtocolo());
            $objProcedimentoAPI = $seiRN->consultarProcedimento($procedimentoAPI);
            $retorno = $this->verificaExisteSolicitacaoSemAR($objProcedimentoAPI);
            if ($retorno) {
                $existe = true;
            }
        }

        if ($existe) {
            $objInfraException = new InfraException();
            $objInfraException->adicionarValidacao('Unidade não pode ser desativada por que exitem solicitação de expedição sem AR retornado');
            $objInfraException->lancarValidacoes();
        }
    }

    public function verificarAcessoProtocolo($arrObjProcedimentoAPI, $arrObjDocumentoAPI)
    {

        $arrBotoes = array();

        if (!(SessaoSEI::getInstance()->verificarPermissao('md_cor_unidade_exp_consultar'))) {
            return $arrBotoes;
        }

        $ret = null;
        $arrIdUnidadeSolicitante = $this->retornarArrIdUnidadeSolicitante();

        #Verifica se existe unidade solicitante vinculada a unidade expedidora.
        if (!empty($arrIdUnidadeSolicitante)) {
            foreach ($arrObjDocumentoAPI as $objDocumentoAPI) {
                $tipoDocVerificado = $this->verificarExpedicaoDocumento($objDocumentoAPI->getIdDocumento());
                if ($tipoDocVerificado['principal'] || $tipoDocVerificado['anexo']) {
                    if (in_array($objDocumentoAPI->getIdUnidadeGeradora(), $arrIdUnidadeSolicitante)) {
                        if ($objDocumentoAPI->getNivelAcesso() != ProtocoloRN::$NA_SIGILOSO) {
                            $ret[$objDocumentoAPI->getIdDocumento()] = SeiIntegracao::$TAM_PERMITIDO;
                        }
                    }
                }
                $arrObjDocRetornado = $this->verificarDocumentoRetornado($objDocumentoAPI->getIdDocumento());
                if (!is_null($arrObjDocRetornado)) {
                    $ret[$objDocumentoAPI->getIdDocumento()] = SeiIntegracao::$TAM_PERMITIDO;
                }
            }
        }

        return $ret;
    }

    public function retornarArrIdUnidadeSolicitante()
    {
        $objMdCorUnidadeExpRN = new MdCorUnidadeExpRN();
        $objMdCorUnidadeExpDTO = new MdCorUnidadeExpDTO();
        $objMdCorUnidadeExpDTO->setNumIdUnidade(SessaoSEI::getInstance()->getNumIdUnidadeAtual());
        $objMdCorUnidadeExpDTO->retNumIdUnidade();
        $objMdCorUnidadeExpDTO->setStrSinAtivo('S');
        $objMdCorUnidadeExpDTO = $objMdCorUnidadeExpRN->consultar($objMdCorUnidadeExpDTO);

        $arrIdUnidadeSolicitante = [];

        #Verifica se é uma unidade de expedição
        if (!empty($objMdCorUnidadeExpDTO)) {

            $objMdCorMapeamentoUniExpSolRN = new MdCorMapeamentoUniExpSolRN();
            $objMdCorMapeamentoUniExpSolDTO = new MdCorMapeamentoUniExpSolDTO();
            $objMdCorMapeamentoUniExpSolDTO->setNumIdUnidadeExp(SessaoSEI::getInstance()->getNumIdUnidadeAtual());
            $objMdCorMapeamentoUniExpSolDTO->retNumIdUnidadeSolicitante();

            $objRetorno = $objMdCorMapeamentoUniExpSolRN->listar($objMdCorMapeamentoUniExpSolDTO);

            $arrIdUnidadeSolicitante = InfraArray::converterArrInfraDTO($objRetorno, 'IdUnidadeSolicitante');
        }

        return $arrIdUnidadeSolicitante;
    }

    public function retornarArrDocLiberado()
    {

        $objInfraParametro = new InfraParametro(BancoSEI::getInstance());
        $strValor = $objInfraParametro->getValor('MODULO_CORREIOS_ID_DOCUMENTO_EXPEDICAO', false);
        $arrDocsLiberados = [];

        if (!empty($strValor)) {
            $arrDocsLiberados = array_merge($arrDocsLiberados, explode(',', $strValor));
        }

        $objMdCorSerieExpRN = new MdCorSerieExpRN();
        $objMdCorSerieExpDTO = new MdCorSerieExpDTO();
        $objMdCorSerieExpDTO->retNumIdSerie();
        $objMdCorSerieExpDTO->setStrSinAtivo('S');
        $objMdCorSerieExpDTO = $objMdCorSerieExpRN->listar($objMdCorSerieExpDTO);

        foreach ($objMdCorSerieExpDTO as $item) {
            array_push($arrDocsLiberados, $item->getNumIdSerie());
        }

        return $arrDocsLiberados;
    }

    public static function consultarVersaoInfraParametro($nomeInfraParametro, $moduloIntegracao = 'PeticionamentoIntegracao')
    {
        $arrModulos = ConfiguracaoSEI::getInstance()->getValor('SEI', 'Modulos');

        if (is_array($arrModulos) && array_key_exists($moduloIntegracao, $arrModulos)) {

            $objInfraParametro = new InfraParametro(BancoSEI::getInstance());

            $versao = intval(preg_replace('/[^0-9]/', '', $objInfraParametro->getValor($nomeInfraParametro, false)));

            return $versao;
        }

        return null;
    }

    public function documentoPrincipalExpSolicitada($idDocumento)
    {
        $MdCorExpedicaoSolicitadaRN = new MdCorExpedicaoSolicitadaRN();
        $MdCorExpedicaoSolicitadaDTO = new MdCorExpedicaoSolicitadaDTO();
        $MdCorExpedicaoSolicitadaDTO->retNumIdMdCorExpedicaoSolicitada();
        $MdCorExpedicaoSolicitadaDTO->setDblIdDocumentoPrincipal($idDocumento);
        $objMdCorExpedicaoSolicitadaDTO = $MdCorExpedicaoSolicitadaRN->contar($MdCorExpedicaoSolicitadaDTO);

        if ($objMdCorExpedicaoSolicitadaDTO > 0) {
            return true;
        }

        return false;
    }

    public function verificarExpedicaoDocumento($idDocumento)
    {
        $arrTipoDoc = array();
        $MdCorExpedicaoSolicitadaRN = new MdCorExpedicaoSolicitadaRN();
        $MdCorExpedicaoSolicitadaDTO = new MdCorExpedicaoSolicitadaDTO();
        $MdCorExpedicaoSolicitadaDTO->retNumIdMdCorExpedicaoSolicitada();
        $MdCorExpedicaoSolicitadaDTO->setDblIdDocumentoPrincipal($idDocumento);
        $objMdCorExpedicaoSolicitadaDTO = $MdCorExpedicaoSolicitadaRN->contar($MdCorExpedicaoSolicitadaDTO);

        if ($objMdCorExpedicaoSolicitadaDTO > 0) {
            $arrTipoDoc['principal'] = true;
        }

        $MdCorExpedicaoFormatoRN = new MdCorExpedicaoFormatoRN();
        $MdCorExpedicaoFormatoDTO = new MdCorExpedicaoFormatoDTO();

        $MdCorExpedicaoFormatoDTO->setOrdDblIdProtocolo(InfraDTO::$TIPO_ORDENACAO_ASC);
        $MdCorExpedicaoFormatoDTO->setDblIdProtocolo($idDocumento);
        $MdCorExpedicaoFormatoDTO->retNumIdMdCorExpedicaoFormato();

        $protocoloAnexo = $MdCorExpedicaoFormatoRN->contar($MdCorExpedicaoFormatoDTO);
        if ($protocoloAnexo > 0) {
            $arrTipoDoc['anexo'] = true;
        }

        return $arrTipoDoc;
    }

    public function desativarContato($objContatoAPI)
    {
        $objInfraException = new InfraException();
        if (count($objContatoAPI) > 0) {
            $mdCorExpedicaoSolicitadaRN = new MdCorExpedicaoSolicitadaRN();
            $validacao = $mdCorExpedicaoSolicitadaRN->validarContatoComExpedicaoAndamento($objContatoAPI[0]);

            if($validacao){
                $objInfraException->adicionarValidacao('Não é permitido Desativar este Contato, pois ele está relacionado a uma expedição pelos Correios em andamento.');
                $objInfraException->lancarValidacoes();
            }
        }
    }

    public function verificarDocumentoRetornado($idDocumento)
    {
        $MdCorRetornoArDocRN = new MdCorRetornoArDocRN();
        $MdCorRetornoArDocDTO = new MdCorRetornoArDocDTO();
        $MdCorRetornoArDocDTO->setNumIdDocumentoAr($idDocumento);
        $MdCorRetornoArDocDTO->retNumIdDocumentoPrincipal();
	      $MdCorRetornoArDocDTO = $MdCorRetornoArDocRN->contar($MdCorRetornoArDocDTO);
        #$MdCorRetornoArDocDTO = $MdCorRetornoArDocRN->consultar($MdCorRetornoArDocDTO);

        return $MdCorRetornoArDocDTO > 0 ? true : null;
    }
}
