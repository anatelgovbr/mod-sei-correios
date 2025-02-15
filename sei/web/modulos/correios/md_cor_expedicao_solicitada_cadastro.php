<?php
try {
    require_once dirname(__FILE__) . '/../../SEI.php';

    session_start();

    //////////////////////////////////////////////////////////////////////////////
    InfraDebug::getInstance()->setBolLigado(false);
    InfraDebug::getInstance()->setBolDebugInfra(false);
    InfraDebug::getInstance()->limpar();
    //////////////////////////////////////////////////////////////////////////////

    SessaoSEI::getInstance()->validarLink();
    SessaoSEI::getInstance()->validarPermissao($_GET['acao']);
    if ($_GET['acao_origem'] != 'md_cor_geracao_plp_listar') {
        PaginaSEI::getInstance()->setTipoPagina(PaginaSEI::$TIPO_PAGINA_SIMPLES);
    }
    $_SESSION['idDocumentoPrincipal'] = $_GET['id_doc'];
    //Variaveis
    $strAcaoForm = SessaoSEI::getInstance()->assinarLink('controlador.php?acao=' . $_GET['acao'] . '&acao_origem=' . $_GET['acao']);
    $strLinkAlterarContato = SessaoSEI::getInstance()->assinarLink('controlador.php?acao=contato_alterar&acao_origem=' . $_GET['acao'].'&arvore=1');
    $strLinkAjaxContatoListar = SessaoSEI::getInstance()->assinarLink('controlador_ajax.php?acao_ajax=md_cor_contato_listar&acao_origem=' . $_GET['acao']);
    $strLinkAjaxTiposMidia = SessaoSEI::getInstance()->assinarLink('controlador_ajax.php?acao_ajax=md_cor_tipo_midia_listar&acao_origem=' . $_GET['acao']);
    $strLinkAjaxFormatoExpedicaoApenasMidia = SessaoSEI::getInstance()->assinarLink('controlador_ajax.php?acao_ajax=md_cor_formato_expedicao_apenas_midia&acao_origem=' . $_GET['acao']);
    $strLinkValidaoDestinatarioExpedicaoSolicitada = SessaoSEI::getInstance()->assinarLink('controlador_ajax.php?acao_ajax=md_validar_destinatario_solicitacao_expedicao');
    $strLinkAjaxChangeServicoPostal = SessaoSEI::getInstance()->assinarLink('controlador_ajax.php?acao_ajax=md_cor_change_serv_postal');
    $strLinkAjaxValidarExtArqExt = SessaoSEI::getInstance()->assinarLink('controlador_ajax.php?acao_ajax=md_cor_valida_arq_ext');

    $chkNecessitaRecebimentoAR = "checked";
    $chkPossuiAnexo = "";
    $chkPossuiAnexoDisable = null;
    $id_doc = $_GET['id_doc'];
    $id_destinatario_aux = null;
    $isPermiteGravarMidia = 'N';
    $arrMsgValidacao = [];
    $strIsConsultar  = false;
    $strNmBtnExibirMsg = null;
    switch ($_GET['acao']) {

        case 'md_cor_expedicao_solicitada_excluir':

            $strTitulo = 'Excluir Solicita��o de Expedi��o pelos Correios';

            if (isset($_POST['txaJustificativa'])) {

                $idExpedicaoSolicitada = $_POST['hdnIdExpedicaoSolicitada'];
                $rn = new MdCorExpedicaoSolicitadaRN();
                $dto = new MdCorExpedicaoSolicitadaDTO();
                $dto->retTodos();
                $dto->retNumIdUnidade();
                $dto->retStrSiglaUnidade();
                $dto->retStrDescricaoUnidade();
                $dto->retNumIdContatoDestinatario();
                $dto->retStrNomeDestinatario();
                $dto->retNumIdMdCorServicoPostal();
                $dto->retDblIdDocumentoPrincipal();
                $dto->retStrSinDevolvido();
                $dto->retStrJustificativaDevolucao();
                $dto->setNumIdMdCorExpedicaoSolicitada($idExpedicaoSolicitada);
                $dto->setDistinct(true);
                $dto = $rn->consultar($dto);

                if ($dto->getNumIdUnidade() == SessaoSEI::getInstance()->getNumIdUnidadeAtual()) {

                    if (is_null($dto)) {
                        throw new InfraException("Registro n�o encontrado.");
                    }


                    if($dto->getStrSinDevolvido() == "S") {
                        $txtJustificativaDevolucao = $dto->getStrJustificativaDevolucao();
                    }
                    if ($dto->getStrSinNecessitaAr() == 'S') {
                        $chkNecessitaRecebimentoAR = "checked";
                    }else{
                        $chkNecessitaRecebimentoAR = "";
                    }

                    //unidade expedidora
//                    $unidade_exp = $dto->getStrDescricaoUnidade() . "(" . $dto->getStrSiglaUnidade() . ")";

                    $strSelectServicoPostal = MdCorMapUnidServicoINT::montarSelectIdMdCorMapUnidServico('null', '&nbsp;', $dto->getNumIdMdCorServicoPostal(), $dto->getNumIdUnidade(), true);

                    //listando os protocolos anexos
                    $arrProtocoloAnexo = array();
                    $arrFormatos = array();

                    // principal
                    $dtoFormato = new MdCorExpedicaoFormatoDTO();
                    $dtoFormato->retTodos(true);
                    $dtoFormato->setDistinct(true);
                    $dtoFormato->setOrdDblIdProtocolo(InfraDTO::$TIPO_ORDENACAO_ASC);
                    $dtoFormato->setNumIdMdCorExpedicaoSolicitada($idExpedicaoSolicitada);
                    $dtoFormato->setDblIdProtocolo($dto->getDblIdDocumentoPrincipal());
                    $rnFormato = new MdCorExpedicaoFormatoRN();
                    $arrFormatos = $rnFormato->listar($dtoFormato);

                    //$dtoProtAnexo = new MdCorExpedicaoAnexoDTO();
                    $dtoProtAnexo = new MdCorExpedicaoFormatoDTO();
                    $dtoProtAnexo->setOrdDblIdProtocolo(InfraDTO::$TIPO_ORDENACAO_ASC);
                    $dtoProtAnexo->setNumIdMdCorExpedicaoSolicitada($idExpedicaoSolicitada);
                    $dtoProtAnexo->setDblIdProtocolo($dtoProtAnexo->getObjInfraAtributoDTO('IdDocumentoPrincipal'), InfraDTO::$OPER_DIFERENTE);

                    $dtoProtAnexo->retTodos(true);

                    //$rnProtAnexo = new MdCorExpedicaoAnexoRN();
                    $rnProtAnexo = new MdCorExpedicaoFormatoRN();
                    $arrProtocoloAnexo = $rnProtAnexo->listar($dtoProtAnexo);

                    $mdCorExpedicaoSolicitadaRN = new MdCorExpedicaoSolicitadaRN();

                    $mdCorExpedicaoSolicitadaDTO = new MdCorExpedicaoSolicitadaDTO();
                    $mdCorExpedicaoSolicitadaDTO->setNumIdMdCorExpedicaoSolicitada($dto->getNumIdMdCorExpedicaoSolicitada());
                    $mdCorExpedicaoSolicitadaRN->excluir(array($mdCorExpedicaoSolicitadaDTO));

                    $objMdCorExpedicaoFormatoDTO = new MdCorExpedicaoFormatoDTO();
                    $objMdCorExpedicaoFormatoDTO->retTodos();
                    $objMdCorExpedicaoFormatoDTO->setDblIdProtocolo($dto->getDblIdDocumentoPrincipal());
                    $arrObjMdCorExpedicaoFormatoDTO = $rnProtAnexo->listar($objMdCorExpedicaoFormatoDTO);

                    if (count($arrObjMdCorExpedicaoFormatoDTO) == 0) {
                        $documentoBD = new DocumentoBD(SessaoSEI::getInstance()->getObjInfraIBanco());
                        $documentoDTO = new DocumentoDTO();
                        $documentoDTO->setDblIdDocumento($dto->getDblIdDocumentoPrincipal());
                        $documentoDTO->setStrSinBloqueado('S');
                        $documentoBD->alterar($documentoDTO);
                    }

                    if (is_array($arrProtocoloAnexo) && count($arrProtocoloAnexo) > 0) {
                        foreach ($arrProtocoloAnexo as $objProtocoloAnexo) {
                            $objMdCorExpedicaoFormatoDTO = new MdCorExpedicaoFormatoDTO();
                            $objMdCorExpedicaoFormatoDTO->retTodos();
                            $objMdCorExpedicaoFormatoDTO->setDblIdProtocolo($objProtocoloAnexo->getDblIdProtocolo());
                            $arrObjMdCorExpedicaoFormatoDTO = $rnProtAnexo->listar($objMdCorExpedicaoFormatoDTO);
                            if (count($arrObjMdCorExpedicaoFormatoDTO) == 0) {
                                $protocoloBD = new ProtocoloBD(SessaoSEI::getInstance()->getObjInfraIBanco());
                                $protocoloDTO = new ProtocoloDTO();
                                $protocoloDTO->retStrStaProtocolo();
                                $protocoloDTO->setDblIdProtocolo($objProtocoloAnexo->getDblIdProtocolo());
                                $objProtocolo = $protocoloBD->consultar($protocoloDTO);
                                if ($objProtocolo->getStrStaProtocolo() <> ProtocoloRN::$TP_PROCEDIMENTO) {
                                    $documentoBD = new DocumentoBD(SessaoSEI::getInstance()->getObjInfraIBanco());
                                    $documentoDTO = new DocumentoDTO();
                                    $documentoDTO->setDblIdDocumento($objProtocoloAnexo->getDblIdProtocolo());
                                    $documentoDTO->setStrSinBloqueado('S');
                                    $documentoBD->alterar($documentoDTO);
                                }
                            }
                        }
                    }

                    $documentoRN = new DocumentoRN();
                    $objDocumentoDTO = new DocumentoDTO();
                    $objDocumentoDTO->setDblIdDocumento($_POST['hdnIdProcedimento']);
                    $objDocumentoDTO->retStrProtocoloDocumentoFormatado();
                    $objDocumentoDTO->retDblIdProcedimento();
                    $objDocumentoDTO->retDblIdDocumento();
                    $arrDocumentoDTO = $documentoRN->consultarRN0005($objDocumentoDTO);

                    $mdCorExpedicaoSolicitadaRN = new MdCorExpedicaoSolicitadaRN();

                    //Cadastro de Andamento
                    $objEntradaLancarAndamentoAPI = new EntradaLancarAndamentoAPI();
                    $objEntradaLancarAndamentoAPI->setIdProcedimento($arrDocumentoDTO->getDblIdProcedimento());
                    $objEntradaLancarAndamentoAPI->setIdTarefaModulo('MD_COR_EXCLUIR_EXPEDICAO');

                    $dataHoraGeracao = InfraData::getStrDataHoraAtual();

                    $arrObjAtributoAndamentoAPI = array();
                    $arrObjAtributoAndamentoAPI[] = $mdCorExpedicaoSolicitadaRN->_retornaObjAtributoAndamentoAPI('DOCUMENTO', $arrDocumentoDTO->getStrProtocoloDocumentoFormatado(), $arrDocumentoDTO->getDblIdDocumento());
                    $arrObjAtributoAndamentoAPI[] = $mdCorExpedicaoSolicitadaRN->_retornaObjAtributoAndamentoAPI('JUSTIFICATIVA_EXCLUSAO_EXPEDICAO', $_POST['txaJustificativa']);

                    $objEntradaLancarAndamentoAPI->setAtributos($arrObjAtributoAndamentoAPI);

                    $objSeiRN = new SeiRN();
                    $objSeiRN->lancarAndamento($objEntradaLancarAndamentoAPI);

                } else {
                    PaginaSEI::getInstance()->setStrMensagem('Essa Solicita��o de Expedi��o n�o pertence a essa unidade!', InfraPagina::$TIPO_MSG_AVISO);
                    header('Location: ' . SessaoSEI::getInstance()->assinarLink('controlador.php?acao=procedimento_controlar'));
                    die;
                }

                $objPesquisaProtocoloDTO = new PesquisaProtocoloDTO();
                $objPesquisaProtocoloDTO->setStrStaTipo(ProtocoloRN::$TPP_DOCUMENTOS);
                $objPesquisaProtocoloDTO->setStrStaAcesso(ProtocoloRN::$TAP_AUTORIZADO);
                $objPesquisaProtocoloDTO->setDblIdProtocolo($_POST['id_doc']);

                $objProtocoloRN = new ProtocoloRN();
                $arrObjProtocoloDTO = $objProtocoloRN->pesquisarRN0967($objPesquisaProtocoloDTO);

                $strLinkMontarArvore = SessaoSEI::getInstance()->assinarLink('controlador.php?acao=procedimento_visualizar&acao_origem='.$_GET["acao"].'&montar_visualizacao=1&arvore=1&id_procedimento='.$arrObjProtocoloDTO[0]->getDblIdProcedimentoDocumento() .'&id_documento='.$_POST['id_doc']);
                echo "<script>";
                echo "window.top.document.getElementById('ifrArvore').src = '".$strLinkMontarArvore."';";
                echo "</script>";
            }

            $arrComandos[] = '<button type="button" accesskey="S" id="btnSalvar" value="btnSalvar" onclick="validarFormulario();" class="infraButton">
                      <span class="infraTeclaAtalho">S</span>alvar</button>';
            $arrComandos[] = '<button type="button" accesskey="C" id="btnCancelar" value="btnCancelar" onclick="location.href = \'' . SessaoSEI::getInstance()->assinarLink('controlador.php?acao=md_cor_expedicao_solicitada_consultar&arvore=1&id_doc=' . $_GET['id_doc'] . '&id_md_cor_expedicao_solicitada=' . $_GET['id_md_cor_expedicao_solicitada']) . '\';" class="infraButton">
                              <span class="infraTeclaAtalho">C</span>ancelar</button>';


            break;

        case 'md_cor_expedicao_solicitada_alterar':


            if (isset($_POST['txaJustificativa'])) {
                try {

                    $idExpedicaoSolicitada = $_POST['hdnIdMdCorExpedicaoSolicitada'];
                    $rn = new MdCorExpedicaoSolicitadaRN();
                    $dto = new MdCorExpedicaoSolicitadaDTO();
                    $dto->retTodos();
                    $dto->retNumIdUnidade();
                    $dto->retStrSiglaUnidade();
                    $dto->retStrDescricaoUnidade();
                    $dto->retNumIdContatoDestinatario();
                    $dto->retStrNomeDestinatario();
                    $dto->retNumIdMdCorServicoPostal();
                    $dto->retDblIdDocumentoPrincipal();
                    $dto->retStrSinDevolvido();
                    $dto->retStrJustificativaDevolucao();
                    $dto->setNumIdMdCorExpedicaoSolicitada($idExpedicaoSolicitada);
                    $dto->setDistinct(true);
                    $dto = $rn->consultar($dto);

                    $idMdCorExpedSolic  = $dto->getNumIdMdCorExpedicaoSolicitada();
                    $idMdCorContatoDest = $dto->getNumIdContatoDestinatario();

                    if ($dto->getNumIdUnidade() == SessaoSEI::getInstance()->getNumIdUnidadeAtual()) {

                        $chkAviso = $_POST['chkAvisoRecebimento'];
                        $dto->setNumIdMdCorServicoPostal($_POST['selServicoPostal']);
                        if ($chkAviso != "" && ($chkAviso == "on" || $chkAviso == "true" || $chkAviso == "S")) {
                            $dto->setStrSinNecessitaAr("S");
                            $strAvisoRecebimento = ", com Aviso de Recebimento";
                        } else {
                            $dto->setStrSinNecessitaAr('N');
                            $strAvisoRecebimento = ", sem Aviso de Recebimento";
                        }

                        $dto->setStrSinDevolvido("N");
                        $dto->setStrJustificativaDevolucao("");
                        $rn->alterar($dto);

                        if (is_null($dto)) {
                            throw new InfraException("Registro n�o encontrado.");
                        }


                        if($dto->getStrSinDevolvido() == "S") {
                            $txtJustificativaDevolucao = $dto->getStrJustificativaDevolucao();
                        }

                        if ($dto->getStrSinNecessitaAr() == 'S') {
                            $chkNecessitaRecebimentoAR = "checked";
                        }else{
                            $chkNecessitaRecebimentoAR = "";
                        }

                        $strSelectServicoPostal = MdCorMapUnidServicoINT::montarSelectIdMdCorMapUnidServico('null', '&nbsp;', $dto->getNumIdMdCorServicoPostal(), $dto->getNumIdUnidade(), true);

                        //listando os protocolos anexos
                        $arrProtocoloAnexo = array();
                        $arrFormatos = array();

                        // principal
                        $dtoFormato = new MdCorExpedicaoFormatoDTO();
                        $dtoFormato->retTodos(true);
                        $dtoFormato->setDistinct(true);
                        $dtoFormato->setOrdDblIdProtocolo(InfraDTO::$TIPO_ORDENACAO_ASC);
                        $dtoFormato->setNumIdMdCorExpedicaoSolicitada($idExpedicaoSolicitada);
                        $dtoFormato->setDblIdProtocolo($dto->getDblIdDocumentoPrincipal());
                        $rnFormato = new MdCorExpedicaoFormatoRN();
                        $arrFormatos = $rnFormato->listar($dtoFormato);

                        $dtoProtAnexo = new MdCorExpedicaoFormatoDTO();
                        $dtoProtAnexo->setOrdDblIdProtocolo(InfraDTO::$TIPO_ORDENACAO_ASC);
                        $dtoProtAnexo->setNumIdMdCorExpedicaoSolicitada($idExpedicaoSolicitada);
                        $dtoProtAnexo->setDblIdProtocolo($dtoProtAnexo->getObjInfraAtributoDTO('IdDocumentoPrincipal'), InfraDTO::$OPER_DIFERENTE);

                        $dtoProtAnexo->retTodos(true);

                        $rnProtAnexo = new MdCorExpedicaoFormatoRN();
                        $arrProtocoloAnexo = $rnProtAnexo->listar($dtoProtAnexo);

                        $mdCorExpedicaoSolicitadaRN = new MdCorExpedicaoSolicitadaRN();

                        $mdCorExpedicaoSolicitadaDTO = new MdCorExpedicaoSolicitadaDTO();
                        $mdCorExpedicaoSolicitadaDTO->setNumIdMdCorExpedicaoSolicitada($dto->getNumIdMdCorExpedicaoSolicitada());
                        $mdCorExpedicaoSolicitadaRN->excluirDocumentos(array($mdCorExpedicaoSolicitadaDTO));

                        $objMdCorExpedicaoFormatoDTO = new MdCorExpedicaoFormatoDTO();
                        $objMdCorExpedicaoFormatoDTO->retTodos();
                        $objMdCorExpedicaoFormatoDTO->setDblIdProtocolo($dto->getDblIdDocumentoPrincipal());
                        $arrObjMdCorExpedicaoFormatoDTO = $rnProtAnexo->listar($objMdCorExpedicaoFormatoDTO);
                        if (count($arrObjMdCorExpedicaoFormatoDTO) == 0) {
                            $documentoBD = new DocumentoBD(SessaoSEI::getInstance()->getObjInfraIBanco());
                            $documentoDTO = new DocumentoDTO();
                            $documentoDTO->setDblIdDocumento($dto->getDblIdDocumentoPrincipal());
                            $documentoDTO->setStrSinBloqueado('N');
                            $documentoBD->alterar($documentoDTO);
                        }

                        if (is_array($arrProtocoloAnexo) && count($arrProtocoloAnexo) > 0) {
                            foreach ($arrProtocoloAnexo as $objProtocoloAnexo) {
                                $objMdCorExpedicaoFormatoDTO = new MdCorExpedicaoFormatoDTO();
                                $objMdCorExpedicaoFormatoDTO->retTodos();
                                $objMdCorExpedicaoFormatoDTO->setDblIdProtocolo($objProtocoloAnexo->getDblIdProtocolo());
                                $arrObjMdCorExpedicaoFormatoDTO = $rnProtAnexo->listar($objMdCorExpedicaoFormatoDTO);
                                if (count($arrObjMdCorExpedicaoFormatoDTO) == 0) {
                                    $documentoBD = new DocumentoBD(SessaoSEI::getInstance()->getObjInfraIBanco());
                                    $documentoDTO = new DocumentoDTO();
                                    $documentoDTO->setDblIdDocumento($objProtocoloAnexo->getDblIdProtocolo());
                                    $documentoDTO->setStrSinBloqueado('N');
                                    $documentoBD->alterar($documentoDTO);
                                }
                            }
                        }

                        $arrProtocolos = PaginaSEI::getInstance()->getArrItensTabelaDinamica($_POST['hdnProtocoloAnexo']);

                        for ($j = 0; $j < count($arrProtocolos); $j++) {
                            $id = explode('#', $arrProtocolos[$j][0]);
                            $arrProtocolos[$j][0] = $id[0];
                            $documentoBD = new DocumentoBD(SessaoSEI::getInstance()->getObjInfraIBanco());
                            $documentoDTO = new DocumentoDTO();
                            $documentoDTO->setDblIdDocumento($id[0]);
                            $documentoDTO->setStrSinBloqueado('S');
                            $documentoBD->alterar($documentoDTO);
                        }

                        $arrFormatos = PaginaSEI::getInstance()->getArrItensTabelaDinamica($_POST['hdnTbFormatos']);
                        for ($k = 0; $k < count($arrFormatos); $k++) {
                            $id = explode('#', $arrFormatos[$k][0]);
                            $arrFormatos[$k][0] = $id[0];
                        }

                        $dto->setArrProtocolosAnexos($arrProtocolos);
                        $dto->setArrMdCorExpedicaoFormatoDTO($arrFormatos);


                        $idDocumento = $_POST['id_doc'];
                        $documentoBD = new DocumentoBD(SessaoSEI::getInstance()->getObjInfraIBanco());
                        $documentoDTO = new DocumentoDTO();
                        $documentoDTO->setDblIdDocumento($idDocumento);
                        $documentoDTO->setStrSinBloqueado('S');
                        $documentoBD->alterar($documentoDTO);

                        $dto = $rn->cadastrarDocumentos($dto);

                        $documentoRN = new DocumentoRN();
                        $objDocumentoDTO = new DocumentoDTO();
                        $objDocumentoDTO->setDblIdDocumento($_POST['hdnIdProcedimento']);
                        $objDocumentoDTO->retStrProtocoloDocumentoFormatado();
                        $objDocumentoDTO->retDblIdProcedimento();
                        $objDocumentoDTO->retDblIdDocumento();
                        $arrDocumentoDTO = $documentoRN->consultarRN0005($objDocumentoDTO);

                        $objMdCorServicoPostalRN = new MdCorServicoPostalRN();
                        $objMdCorServicoPostalDTO = new MdCorServicoPostalDTO();
                        $objMdCorServicoPostalDTO->setNumIdMdCorServicoPostal($_POST['selServicoPostal']);
                        $objMdCorServicoPostalDTO->retStrDescricao();
                        $objMdCorServicoPostalDTO->retStrNome();
                        $arrServicoPostal = $objMdCorServicoPostalRN->consultar($objMdCorServicoPostalDTO);

                        //Cadastro de Andamento
                        $objEntradaLancarAndamentoAPI = new EntradaLancarAndamentoAPI();
                        $objEntradaLancarAndamentoAPI->setIdProcedimento($arrDocumentoDTO->getDblIdProcedimento());
                        $objEntradaLancarAndamentoAPI->setIdTarefaModulo('MD_COR_ALTERAR_EXPEDICAO');

                        $dataHoraGeracao = InfraData::getStrDataHoraAtual();

                        $arrObjAtributoAndamentoAPI = array();
                        $arrObjAtributoAndamentoAPI[] = $mdCorExpedicaoSolicitadaRN->_retornaObjAtributoAndamentoAPI('DOCUMENTO', $arrDocumentoDTO->getStrProtocoloDocumentoFormatado(), $arrDocumentoDTO->getDblIdDocumento());
                        $arrObjAtributoAndamentoAPI[] = $mdCorExpedicaoSolicitadaRN->_retornaObjAtributoAndamentoAPI('SERVICO_POSTAGEM_CORREIOS', $arrServicoPostal->getStrDescricao());
                        $arrObjAtributoAndamentoAPI[] = $mdCorExpedicaoSolicitadaRN->_retornaObjAtributoAndamentoAPI('OPCAO_AVISO_RECEBIMENTO', $strAvisoRecebimento);
                        $arrObjAtributoAndamentoAPI[] = $mdCorExpedicaoSolicitadaRN->_retornaObjAtributoAndamentoAPI('JUSTIFICATIVA_ALTERACAO_EXPEDICAO', $_POST['txaJustificativa']);

                        $objEntradaLancarAndamentoAPI->setAtributos($arrObjAtributoAndamentoAPI);

                        $objSeiRN = new SeiRN();
                        $objSeiRN->lancarAndamento($objEntradaLancarAndamentoAPI);

                        $arrObjMdCorContato = MdCorContatoINT::_isDadoAlterado( $idMdCorContatoDest , $idMdCorExpedSolic );

                        if ( $_POST['hdnContatoIdentificador'] == "" && $arrObjMdCorContato['isRegAlterado'] ) $_POST['hdnContatoIdentificador'] = $idMdCorContatoDest;

                        if ( $_POST['hdnContatoIdentificador'] != "" ) {
                            $contatoRN = new ContatoRN();
                            $objContatoDTO = new ContatoDTO();
                            $objContatoDTO->retTodos(true);
                            $objContatoDTO->setNumIdContato($_POST['hdnContatoIdentificador']);
                            $objContatoDTO = $contatoRN->consultarRN0324($objContatoDTO);
                            $mdCorExpedicaoSolicitadaRN = new MdCorExpedicaoSolicitadaRN();
                            $mdCorExpedicaoSolicitadaRN->alterarEnderecoExpedSemPLP($objContatoDTO, $_POST['id_doc'], true);
                        }

                    } else {
                        PaginaSEI::getInstance()->setStrMensagem('Essa Solicita��o de Expedi��o n�o pertence a essa unidade!', InfraPagina::$TIPO_MSG_AVISO);
                        header('Location: ' . SessaoSEI::getInstance()->assinarLink('controlador.php?acao=procedimento_controlar'));
                        die;
                    }
                } catch (Exception $e) {
                    PaginaSEI::getInstance()->processarExcecao($e);
                }
            }

            $objPesquisaProtocoloDTO = new PesquisaProtocoloDTO();
            $objPesquisaProtocoloDTO->setStrStaTipo(ProtocoloRN::$TPP_DOCUMENTOS);
            $objPesquisaProtocoloDTO->setStrStaAcesso(ProtocoloRN::$TAP_AUTORIZADO);
            $objPesquisaProtocoloDTO->setDblIdProtocolo($_POST['id_doc']);

            $objProtocoloRN = new ProtocoloRN();
            $arrObjProtocoloDTO = $objProtocoloRN->pesquisarRN0967($objPesquisaProtocoloDTO);
            $strLinkMontarArvore = SessaoSEI::getInstance()->assinarLink('controlador.php?acao=procedimento_visualizar&acao_origem='.$_GET["acao"].'&montar_visualizacao=1&arvore=1&id_procedimento='.$arrObjProtocoloDTO[0]->getDblIdProcedimentoDocumento() .'&id_documento='.$_POST['id_doc']);

            echo "<script>";
            echo "window.top.document.getElementById('ifrArvore').src = '".$strLinkMontarArvore."';";
            echo "</script>";
            break;

        case 'md_cor_expedicao_solicitada_cadastrar':
            $chkPossuiAnexoDisable = 'disabled';

            if (isset($_POST['id_doc'])) {
                try {
                    $rn = new MdCorExpedicaoSolicitadaRN();
                    $dto = new MdCorExpedicaoSolicitadaDTO();
                    $idDocumento = $_POST['id_doc'];
                    $dto->setDblIdDocumentoPrincipal($idDocumento);
                    $dto->retNumIdMdCorExpedicaoSolicitada();
                    $dto->retNumIdProcedimento();
                    $arrExpedicao = $rn->listar($dto);

                    $documentoBD = new DocumentoBD(SessaoSEI::getInstance()->getObjInfraIBanco());
                    $documentoDTO = new DocumentoDTO();
                    $documentoDTO->setDblIdDocumento($idDocumento);
                    $documentoDTO->setStrSinBloqueado('S');
                    $documentoBD->alterar($documentoDTO);

                    if (empty($arrExpedicao)) {

                        $chkAviso = $_POST['chkAvisoRecebimento'];
                        $dto->setDblIdDocumentoPrincipal($_POST['id_doc']);
                        $strAvisoRecebimento = "";
                        if ($chkAviso != "" && ($chkAviso == "on" || $chkAviso == "true" || $chkAviso == "S")) {
                            $dto->setStrSinNecessitaAr("S");
                            $strAvisoRecebimento = ", com Aviso de Recebimento";
                        } else {
                            $dto->setStrSinNecessitaAr('N');
                            $strAvisoRecebimento = ", sem Aviso de Recebimento";
                        }


                        $objProtocoloDocPrincipalRN = new ProtocoloRN();
                        $objProtocoloDocPrincipalDTO = new ProtocoloDTO();
                        $objProtocoloDocPrincipalDTO->retTodos();
                        $objProtocoloDocPrincipalDTO->retNumIdSerieDocumento();
                        $objProtocoloDocPrincipalDTO->retStrNomeSerieDocumento();
                        $objProtocoloDocPrincipalDTO->retStrNumeroDocumento();
                        $objProtocoloDocPrincipalDTO->retStrStaDocumentoDocumento();

                        $objProtocoloDocPrincipalDTO->setDblIdProtocolo($idDocumento);

                        $objProtocoloDocPrincipalDTO = $objProtocoloDocPrincipalRN->consultarRN0186($objProtocoloDocPrincipalDTO);


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
                        if (in_array($objProtocoloDocPrincipalDTO->getNumIdSerieDocumento(), $arrIdSerieDocumento) && $objProtocoloDocPrincipalDTO->getStrStaDocumentoDocumento() == 'X') {
                            $bolExpedicaoExterno = true;
                        }

                        $dto->setNumIdMdCorServicoPostal($_POST['selServicoPostal']);
                        $dto->setDthDataSolicitacao(InfraData::getStrDataHoraAtual());
                        $dto->setNumIdUnidade(SessaoSEI::getInstance()->getNumIdUnidadeAtual());
                        $dto->setNumIdUsuarioSolicitante(SessaoSEI::getInstance()->getNumIdUsuario());
                        $dto->setStrSinRecebido('N');
                        $dto->setStrSinDevolvido('N');
                        $dto->setStrStatusCobranca('N');

                        //id do contato destinatario
                        $objParticipanteRN = new ParticipanteRN();
                        $objParticipanteDTO = new ParticipanteDTO();
                        $objParticipanteDTO->setDblIdProtocolo($_POST['id_doc']);
                        $objParticipanteDTO->setNumMaxRegistrosRetorno(1);
                        if ($bolExpedicaoExterno) {
                            $objParticipanteDTO->setStrStaParticipacao(ParticipanteRN::$TP_INTERESSADO);
                        } else {
                            $objParticipanteDTO->setStrStaParticipacao(ParticipanteRN::$TP_DESTINATARIO);
                        }
                        $objParticipanteDTO->retTodos();
                        $objParticipanteDTO->retNumIdContato();
                        $objParticipanteDTO->retStrNomeContato();
                        $objParticipanteDTO = $objParticipanteRN->consultarRN1008($objParticipanteDTO);

                        $dto->setNumIdContatoDestinatario($objParticipanteDTO->getNumIdContato());

                        $arrValuesExtensoes = PaginaSEI::getInstance()->getArrValuesSelect($_POST['selProtocoloAnexo']);

                        $arrProtocolos = PaginaSEI::getInstance()->getArrItensTabelaDinamica($_POST['hdnProtocoloAnexo']);
                        for ($j = 0; $j < count($arrProtocolos); $j++) {
                            $id = explode('#', $arrProtocolos[$j][0]);
                            $arrProtocolos[$j][0] = $id[0];

                            $protocoloBD = new ProtocoloBD(SessaoSEI::getInstance()->getObjInfraIBanco());
                            $protocoloDTO = new ProtocoloDTO();
                            $protocoloDTO->retStrStaProtocolo();
                            $protocoloDTO->setDblIdProtocolo($id[0]);
                            $objProtocolo = $protocoloBD->consultar($protocoloDTO);
                            if ($objProtocolo->getStrStaProtocolo() <> ProtocoloRN::$TP_PROCEDIMENTO) {
                                $documentoBD = new DocumentoBD(SessaoSEI::getInstance()->getObjInfraIBanco());
                                $documentoDTO = new DocumentoDTO();
                                $documentoDTO->setDblIdDocumento($id[0]);
                                $documentoDTO->setStrSinBloqueado('S');
                                $documentoBD->alterar($documentoDTO);
                            }
                        }
                        $arrFormatos = PaginaSEI::getInstance()->getArrItensTabelaDinamica($_POST['hdnTbFormatos']);
                        for ($k = 0; $k < count($arrFormatos); $k++) {
                            $id = explode('#', $arrFormatos[$k][0]);
                            $arrFormatos[$k][0] = $id[0];
                        }

                        $dto->setArrProtocolosAnexos($arrProtocolos);
                        $dto->setArrMdCorExpedicaoFormatoDTO($arrFormatos);


                        $documentoRN = new DocumentoRN();
                        $objDocumentoDTO = new DocumentoDTO();
                        $objDocumentoDTO->setDblIdDocumento($_POST['id_doc']);
                        $objDocumentoDTO->retStrProtocoloDocumentoFormatado();
                        $objDocumentoDTO->retDblIdProcedimento();
                        $objDocumentoDTO->retDblIdDocumento();
                        $arrDocumentoDTO = $documentoRN->consultarRN0005($objDocumentoDTO);

                        $mdCorExpedicaoSolicitadaRN = new MdCorExpedicaoSolicitadaRN();

                        $objMdCorServicoPostalRN = new MdCorServicoPostalRN();
                        $objMdCorServicoPostalDTO = new MdCorServicoPostalDTO();
                        $objMdCorServicoPostalDTO->setNumIdMdCorServicoPostal($_POST['selServicoPostal']);
                        $objMdCorServicoPostalDTO->retStrDescricao();
                        $objMdCorServicoPostalDTO->retStrNome();
                        $arrServicoPostal = $objMdCorServicoPostalRN->consultar($objMdCorServicoPostalDTO);

                        //Cadastro de Andamento
                        $objEntradaLancarAndamentoAPI = new EntradaLancarAndamentoAPI();
                        $objEntradaLancarAndamentoAPI->setIdProcedimento($arrDocumentoDTO->getDblIdProcedimento());
                        $objEntradaLancarAndamentoAPI->setIdTarefaModulo('MD_COR_SOLICITACAO_EXPEDICAO');

                        $dataHoraGeracao = InfraData::getStrDataHoraAtual();

                        $arrObjAtributoAndamentoAPI = array();
                        $arrObjAtributoAndamentoAPI[] = $mdCorExpedicaoSolicitadaRN->_retornaObjAtributoAndamentoAPI('DATA_SOLICITACAO_EXPEDICAO', $dataHoraGeracao);
                        $arrObjAtributoAndamentoAPI[] = $mdCorExpedicaoSolicitadaRN->_retornaObjAtributoAndamentoAPI('DOCUMENTO', $arrDocumentoDTO->getStrProtocoloDocumentoFormatado(), $arrDocumentoDTO->getDblIdDocumento());
                        $arrObjAtributoAndamentoAPI[] = $mdCorExpedicaoSolicitadaRN->_retornaObjAtributoAndamentoAPI('SERVICO_POSTAGEM_CORREIOS', $arrServicoPostal->getStrDescricao());
                        $arrObjAtributoAndamentoAPI[] = $mdCorExpedicaoSolicitadaRN->_retornaObjAtributoAndamentoAPI('OPCAO_AVISO_RECEBIMENTO', $strAvisoRecebimento);

                        $objEntradaLancarAndamentoAPI->setAtributos($arrObjAtributoAndamentoAPI);

                        $dto = $rn->cadastrar($dto);

                        $objSeiRN = new SeiRN();
                        $objSeiRN->lancarAndamento($objEntradaLancarAndamentoAPI);
                    }
                } catch (Exception $e) {
                    //print_r( $e );
                    PaginaSEI::getInstance()->processarExcecao($e);
                }

                //redirecionar para a tela do consultar ap�s finalizar o cadastro
                //$urlConsulta = SessaoSEI::getInstance()->assinarLink('controlador.php?acao=md_cor_expedicao_solicitada_consultar&acao_origem=md_cor_expedicao_processo_listar&id_md_cor_expedicao_solicitada=' . $dto->getNumIdMdCorExpedicaoSolicitada());
                //header('Location:' . $urlConsulta ); die;


                $objPesquisaProtocoloDTO = new PesquisaProtocoloDTO();
                $objPesquisaProtocoloDTO->setStrStaTipo(ProtocoloRN::$TPP_DOCUMENTOS);
                $objPesquisaProtocoloDTO->setStrStaAcesso(ProtocoloRN::$TAP_AUTORIZADO);
                $objPesquisaProtocoloDTO->setDblIdProtocolo($_POST['id_doc']);

                $objProtocoloRN = new ProtocoloRN();
                $arrObjProtocoloDTO = $objProtocoloRN->pesquisarRN0967($objPesquisaProtocoloDTO);
                $strLinkMontarArvore = SessaoSEI::getInstance()->assinarLink('controlador.php?acao=procedimento_visualizar&acao_origem='.$_GET["acao"].'&montar_visualizacao=1&arvore=1&id_procedimento='.$arrObjProtocoloDTO[0]->getDblIdProcedimentoDocumento() .'&id_documento='.$_POST['id_doc']);
                echo "<script>";
                echo "window.top.document.getElementById('ifrArvore').src = '".$strLinkMontarArvore."';";
                echo "</script>";

                break;
            } else {
                try {
                    $docDTO = new DocumentoDTO();
                    $docRN = new DocumentoRN();
                    $docDTO->retDblIdDocumento();
                    $docDTO->retDblIdProcedimento();
                    $docDTO->setDblIdDocumento($id_doc);
                    $docDTO = $docRN->consultarRN0005($docDTO);
                    $id_procedimento = $docDTO->getDblIdProcedimento();

                    //montando links para pesquisa de protocolos anexos
                    $strLinkPopUpSelecaoProtocoloAnexo = SessaoSEI::getInstance()->assinarLink('controlador.php?acao=md_cor_expedicao_cadastro_protocolos_selecionar&tipo_selecao=2&id_procedimento=' . $id_procedimento . '&id_doc=' . $id_doc . '&id_object=objLupaProtocoloAnexo');

                    $strLinkAjaxProtocoloAnexo = SessaoSEI::getInstance()->assinarLink('controlador_ajax.php?acao_ajax=md_cor_expedicao_cadastro_protocolos_autocompletar&id_procedimento=' . $id_procedimento);


                    //aplicar valida�ao de informa�oes do destinatario do doc principal
                    require_once 'md_cor_expedicao_solicitada_cadastro_validar_destinatario.php';

                    //aplicar valida�ao de informa�oes da unidade solicitante x servi�os
                    $unidade_sol = '';
                    $id_num_unidade_solic = SessaoSei::getInstance()->getNumIdUnidadeAtual();
                    $id_num_servico_postal = 'null';

                    require_once 'md_cor_expedicao_solicitada_cadastro_validar_unid_exp_serv.php';

                    $isPermiteGravarMidia = $id_num_servico_postal != 'null'
                        ? (MdCorServicoPostalINT::getInfoServicoPostalPorId($id_num_servico_postal))->getStrSinAnexarMidia() ?? 'N'
                        : 'N';

                    //obtendo informa��es do contato
                    $objContatoDTO = new ContatoDTO();
                    $objContatoRN = new ContatoRN();
                    //$objContatoDTO->retTodos(true);
                    $objContatoDTO->retNumIdContato();
                    //$objContatoDTO->getNumIdCargo();
                    $objContatoDTO->retStrExpressaoCargo();
                    $objContatoDTO->retStrExpressaoTratamentoCargo();
                    $objContatoDTO->retStrStaGenero();
                    $objContatoDTO->retStrNome();
                    $objContatoDTO->retStrEndereco();
                    $objContatoDTO->retStrComplemento();
                    $objContatoDTO->retStrBairro();
                    $objContatoDTO->retStrCep();
                    $objContatoDTO->retStrNomeCidade();
                    $objContatoDTO->retStrSiglaUf();
                    $objContatoDTO->retNumIdContatoAssociado();
                    $objContatoDTO->retStrNomeContatoAssociado();
                    $objContatoDTO->retStrSinEnderecoAssociado();
                    $objContatoDTO->setNumIdContato($objParticipanteDTO->getNumIdContato());
                    $objContatoDTO = $objContatoRN->consultarRN0324($objContatoDTO);

                    $id_destinatario = $objContatoDTO->getNumIdContato();
                    $nome_destinatario = $objContatoDTO->getStrNome();

                    $cargo_destinatario = $objContatoDTO->getStrExpressaoCargo();

                    //$genero_destinatario = $objContatoDTO->getStrStaGenero();
                    $tratamento_destinatario = $objContatoDTO->getStrExpressaoTratamentoCargo();

                    $endereco_destinatario = $objContatoDTO->getStrEndereco();
                    $complemento_destinatario = $objContatoDTO->getStrComplemento();
                    $bairro_destinatario = $objContatoDTO->getStrBairro();
                    $cep_destinatario = $objContatoDTO->getStrCep();
                    $cidade_destinatario = $objContatoDTO->getStrNomeCidade();
                    $uf_destinatario = $objContatoDTO->getStrSiglaUf();

                    $idContatoAssociado = $objContatoDTO->getNumIdContatoAssociado();
                    //$nomeContatoAssociado = $objContatoDTO->getStrNomeContatoAssociado();

                    $nome_destinatario_associado = '';
                    //verifica se tem contato associado e se o contato associado � PJ
                    if (!is_null($idContatoAssociado)) {
                        $objContatoAssociadoDTO = new ContatoDTO();
                        $objContatoAssociadoDTO->retTodos(true);
                        $objContatoAssociadoDTO->setNumIdContato($idContatoAssociado);
                        $objContatoAssociadoDTO = $objContatoRN->consultarRN0324($objContatoAssociadoDTO);

                        //captura o nome do associado caso a Natureza seja Pessoa Juridica
                        if ($objContatoAssociadoDTO->getStrStaNatureza() == ContatoRN::$TN_PESSOA_JURIDICA) $nome_destinatario_associado = $objContatoAssociadoDTO->getStrNomeContatoAssociado();

                        //captura o endere�o do associado caso esteja sinalizado o uso do seu endere�o
                        if ($objContatoDTO->getStrSinEnderecoAssociado() == 'S') {
                            $endereco_destinatario = $objContatoAssociadoDTO->getStrEndereco();
                            $complemento_destinatario = $objContatoAssociadoDTO->getStrComplemento();
                            $bairro_destinatario = $objContatoAssociadoDTO->getStrBairro();
                            $cep_destinatario = $objContatoAssociadoDTO->getStrCep();
                            $cidade_destinatario = $objContatoAssociadoDTO->getStrNomeCidade();
                            $uf_destinatario = $objContatoAssociadoDTO->getStrSiglaUf();
                        }
                    }

                    //consulta de servi�o postal
                    $strSelectServicoPostal = MdCorMapUnidServicoINT::montarSelectIdMdCorMapUnidServico('null', '&nbsp;', $id_num_servico_postal, $id_num_unidade_solic);

                    if ( empty($strSelectServicoPostal) ) {
                        $msg = MdCorMensagemINT::setMensagemPadraoPersonalizada(MdCorMensagemINT::$MSG_COR_01,['N�o']);
                        PaginaSEI::getInstance()->adicionarMensagem( $msg , InfraPagina::$TIPO_MSG_AVISO );
                    }

                    $strTitulo = 'Cadastrar Solicit��o de Expedi��o pelos Correios';

                    $arrComandos[] = '<button type="button" onclick="validarFormulario()" accesskey="S" id="btnSolicitarExpedicao" value="SolicitarExpedicao" class="infraButton">
	                              <span class="infraTeclaAtalho">S</span>olicitar Expedi��o</button>';

                    $arrComandos[] = '<button type="button" accesskey="C" name="btnCancelar" id="btnCancelar" value="Cancelar" onclick="location.href = \'' . SessaoSEI::getInstance()->assinarLink('controlador.php?acao=arvore_visualizar&acao_origem=protocolo_modelo_cadastrar&arvore=1&id_protocolo=' . $_GET['id_doc']) . '\';" class="infraButton"><span class="infraTeclaAtalho">C</span>ancelar</button>';

                } catch ( InfraException $e ) {
                    echo '<div class="alert alert-danger alert-dismissible fade show" style="font-size:.875rem; top:0.25rem; margin-bottom: 14px !important; width:98%; margin:0 auto;" role="alert">
                            '.$e->getMessage().'
                            <button type="button" class="close media h-100" data-dismiss="alert" aria-label="Fechar Mensagem" aria-labelledby="divInfraMsg0" onclick="atualizarTela()">
                            <span aria-hidden="true" class="align-self-center"><b>X</b></span>
                            </button>
                        </div>';

                    $link = SessaoSEI::getInstance()->assinarLink('controlador.php?acao=procedimento_visualizar&acao_origem='.$_GET["acao"].'&montar_visualizacao=1&arvore=1&id_procedimento='.$id_procedimento.'&id_documento='.$id_doc);

                    echo "<script>";
                    echo 	"function atualizarTela() {";
                    echo 		"window.top.document.getElementById('ifrArvore').src = '".$link."';";
                    echo 	"}";
                    echo "</script>";
                }
                break;
            }

        case 'md_cor_expedicao_solicitada_consultar':

            $strTitulo = 'Consultar Solicita��o de Expedi��o pelos Correios';
            $strIsConsultar = true;

            $idExpedicaoSolicitada = $_GET['id_md_cor_expedicao_solicitada'];
            $rn = new MdCorExpedicaoSolicitadaRN();
            $dto = new MdCorExpedicaoSolicitadaDTO();
            $dto->retTodos();
            $dto->retNumIdUnidade();
            $dto->retNumIdUnidadeGeradora();
            $dto->retDblIdUnidadeExpedidora();
            $dto->retStrSiglaUnidade();
            $dto->retStrDescricaoUnidade();
            $dto->retNumIdContatoDestinatario();
            $dto->retStrNomeDestinatario();
            $dto->retDblIdDocumentoPrincipal();
            $dto->retDblIdProtocolo();
            $dto->retNumIdMdCorServicoPostal();
            $dto->retStrDescricaoServicoPostal();
            $dto->retStrSinDevolvido();
            $dto->retStrJustificativaDevolucao();
            $dto->setNumIdMdCorExpedicaoSolicitada($idExpedicaoSolicitada);
            $dto->setDistinct(true);
            $dto = $rn->consultar($dto);

            if (is_null($dto)) {
                throw new InfraException("Registro n�o encontrado.");
            }

            if($dto->getStrSinDevolvido() == "S") {
                $txtJustificativaDevolucao = $dto->getStrJustificativaDevolucao();
            }

            if ($dto->getStrSinNecessitaAr() == 'S') {
                $chkNecessitaRecebimentoAR = "checked";
            }else{
                $chkNecessitaRecebimentoAR = "";
            }

            $unidade_sol = $dto->getStrDescricaoUnidade() . " (" . $dto->getStrSiglaUnidade() . ")";

            // Unidade Expedidora: Antes de Gerar PLP, utiliza ID da Unidade Parametrizada, senao, ID
            // da Unidade salva na PLP
            $unidadeDTO = new UnidadeDTO();
            $unidadeDTO->retStrDescricao();
            $unidadeDTO->retStrSigla();
            $unidadeDTO->setNumIdUnidade($dto->getNumIdUnidadeGeradora()?: $dto->getDblIdUnidadeExpedidora());

            $unidadeRN = new UnidadeRN();
            $unidadeDTO = $unidadeRN->consultarRN0125($unidadeDTO);

            $unidade_exp = $unidadeDTO->getStrDescricao() . " (" . $unidadeDTO->getStrSigla() . ")";

            //require_once 'md_cor_expedicao_solicitada_cadastro_validar_unid_exp_serv.php';
            #$strSelectServicoPostal = MdCorMapUnidServicoINT::montarSelectIdMdCorMapUnidServico('null', '&nbsp;', $dto->getNumIdMdCorServicoPostal(), $dto->getNumIdUnidade(), true);

            //listando os protocolos anexos
            $arrProtocoloAnexo = array();
            $arrFormatos = array();

            // principal
            $dtoFormato = new MdCorExpedicaoFormatoDTO();
            $dtoFormato->retTodos(true);
            $dtoFormato->setDistinct(true);
            $dtoFormato->setOrdDblIdProtocolo(InfraDTO::$TIPO_ORDENACAO_ASC);
            $dtoFormato->setNumIdMdCorExpedicaoSolicitada($idExpedicaoSolicitada);
            $dtoFormato->setDblIdProtocolo($dto->getDblIdDocumentoPrincipal());
            $rnFormato = new MdCorExpedicaoFormatoRN();
            $arrFormatos = $rnFormato->listar($dtoFormato);

            //$dtoProtAnexo = new MdCorExpedicaoAnexoDTO();
            $dtoProtAnexo = new MdCorExpedicaoFormatoDTO();
            $dtoProtAnexo->setOrdDblIdProtocolo(InfraDTO::$TIPO_ORDENACAO_ASC);
            $dtoProtAnexo->setNumIdMdCorExpedicaoSolicitada($idExpedicaoSolicitada);
            $dtoProtAnexo->setDblIdProtocolo($dtoProtAnexo->getObjInfraAtributoDTO('IdDocumentoPrincipal'), InfraDTO::$OPER_DIFERENTE);


            $dtoProtAnexo->retTodos(true);

            //$rnProtAnexo = new MdCorExpedicaoAnexoRN();
            $rnProtAnexo = new MdCorExpedicaoFormatoRN();
            $arrProtocoloAnexo = $rnProtAnexo->listar($dtoProtAnexo);
            $arrDocs = array();

            if (is_array($arrProtocoloAnexo) && count($arrProtocoloAnexo) > 0) {

                foreach ($arrProtocoloAnexo as $docAnexo) {
                    $arrDocs[] = $docAnexo->getDblIdProtocolo();
                }
                $chkPossuiAnexo = "checked";
            }


            //listando informa��es de formatos
            if (is_array($arrDocs) && count($arrDocs)) {
                // anexos
                $rnFormato = new MdCorExpedicaoFormatoRN();
                $dtoFormato = new MdCorExpedicaoFormatoDTO();
                $dtoFormato->retTodos(true);
                $dtoFormato->setDistinct(true);
                $dtoFormato->setOrdDblIdProtocolo(InfraDTO::$TIPO_ORDENACAO_ASC);
                $dtoFormato->setNumIdMdCorExpedicaoSolicitada($idExpedicaoSolicitada);
                $dtoFormato->setDblIdProtocolo($arrDocs, InfraDTO::$OPER_IN);
                $arrFormatosOutros = $rnFormato->listar($dtoFormato);

                $arrFormatos = array_merge($arrFormatos, $arrFormatosOutros);
            }

            $s = $dto->getDblIdDocumentoPrincipal();

            //informa�oes do doc principal
            $objProtocoloDocPrincipalRN = new ProtocoloRN();
            $objProtocoloDocPrincipalDTO = new ProtocoloDTO();
            $objProtocoloDocPrincipalDTO->retTodos();
            $objProtocoloDocPrincipalDTO->retStrNomeSerieDocumento();
            $objProtocoloDocPrincipalDTO->retStrNumeroDocumento();
            $objProtocoloDocPrincipalDTO->setDblIdProtocolo($dto->getDblIdDocumentoPrincipal());

            $objProtocoloDocPrincipalDTO = $objProtocoloDocPrincipalRN->consultarRN0186($objProtocoloDocPrincipalDTO);

            $nomeTipoDocumento = $objProtocoloDocPrincipalDTO->getStrNomeSerieDocumento();
            $numeroProtocoloFormatado = $objProtocoloDocPrincipalDTO->getStrProtocoloFormatado();
            $numeroDoc = $objProtocoloDocPrincipalDTO->getStrNumeroDocumento();
            $strUrlDocumento = SessaoSEI::getInstance()->assinarLink('controlador.php?acao=procedimento_trabalhar&id_documento=' . $dto->getDblIdDocumentoPrincipal());
            $descricao_documento_principal = $nomeTipoDocumento . ' ' . $numeroDoc . ' <a class="protocoloNormal" style="font-size: 1.0em !important; font-size:1em" href="' . $strUrlDocumento . '" target="_blank">(' . $numeroProtocoloFormatado . ')</a>';

            //obtendo informa��es do destinatario
            $isPodeComparar = isset($_GET['isConsultar']) ? false : true;

            $arrObjMdCorContato = MdCorContatoINT::_isDadoAlterado( $dto->getNumIdContatoDestinatario() , $dto->getNumIdMdCorExpedicaoSolicitada(), $isPodeComparar );

            $arrObjMdCorContatoDTO = $arrObjMdCorContato['objMdCorContato'];

            $id_destinatario = $arrObjMdCorContatoDTO->getNumIdContato();

            $nome_destinatario = $arrObjMdCorContatoDTO->getStrNome();

            $cargo_destinatario = $arrObjMdCorContatoDTO->getStrExpressaoCargo();

            $tratamento_destinatario = $arrObjMdCorContatoDTO->getStrExpressaoTratamentoCargo();

            $endereco_destinatario = $arrObjMdCorContatoDTO->getStrEndereco();
            $complemento_destinatario = $arrObjMdCorContatoDTO->getStrComplemento();
            $bairro_destinatario = $arrObjMdCorContatoDTO->getStrBairro();
            $cep_destinatario = $arrObjMdCorContatoDTO->getStrCep();
            $cidade_destinatario = $arrObjMdCorContatoDTO->getStrNomeCidade();
            $uf_destinatario = $arrObjMdCorContatoDTO->getStrSiglaUf();

            $idContatoAssociado = $arrObjMdCorContatoDTO->getNumIdContatoAssociado();

            $nome_destinatario_associado = '';
            if ($arrObjMdCorContatoDTO->getStrStaNaturezaContatoAssociado() == ContatoRN::$TN_PESSOA_JURIDICA) {
                $nome_destinatario_associado = $arrObjMdCorContatoDTO->getStrNomeContatoAssociado();
            }

            $staAberto = true;
            if (isset($_GET['staAberto'])) {
                $staAberto = ($_GET['staAberto'] == 'N') ? false : true;
            }

            $strSelectServicoPostal = InfraINT::montarSelectArrInfraDTO(null,null,'',[$dto],'IdMdCorServicoPostal','DescricaoServicoPostal');

            $acoesModal = array('md_cor_expedicao_processo_listar', 'md_cor_expedicao_unidade_listar');
            if (array_key_exists('acao_origem', $_GET) && (in_array($_GET['acao_origem'], $acoesModal))) {
                $arrComandos[] = '<button onclick="infraFecharJanelaModal();" type="button" accesskey="C" id="btnFechar" value="btnFechar" class="infraButton">
                              Fe<span class="infraTeclaAtalho">c</span>har</button>';
            } else {
                if ( isset( $_GET['acao_origem'] ) && $_GET['acao_origem'] != 'md_cor_geracao_plp_listar' || $dto->getStrSinDevolvido() == 'S') {
                    //montando links para pesquisa de protocolos anexos
                    $strLinkPopUpSelecaoProtocoloAnexo = SessaoSEI::getInstance()->assinarLink('controlador.php?acao=md_cor_expedicao_cadastro_protocolos_selecionar&tipo_selecao=2&id_procedimento=' . $dto->getDblIdProtocolo() . '&id_doc=' . $_GET['id_doc'] . '&id_object=objLupaProtocoloAnexo');
                    if (SessaoSei::getInstance()->getNumIdUnidadeAtual() == $dto->getNumIdUnidade() && $dto->getNumIdMdCorPlp() == null && $staAberto) {

                        $validaContatoEdicao = MdCorExpedicaoSolicitadaINT::validaContatoPreeenchido($id_destinatario,true);

                        if ( !empty($validaContatoEdicao)) array_push($arrMsgValidacao,$validaContatoEdicao);

                        $strTitulo = 'Alterar Solicita��o de Expedi��o pelos Correios';
                        $strIsConsultar = false;

                        //Caso seja ALTERACAO da solicitacao de expedicao
                        $strSelectServicoPostal = MdCorMapUnidServicoINT::montarSelectIdMdCorMapUnidServico('null', '&nbsp;', $dto->getNumIdMdCorServicoPostal(), $dto->getNumIdUnidade(), true);

                        if ( empty($strSelectServicoPostal) ) {
                            array_push( $arrMsgValidacao , MdCorMensagemINT::setMensagemPadraoPersonalizada( MdCorMensagemINT::$MSG_COR_01 , ['N�o'] ) );
                        }

                        $isPermiteGravarMidia = ( MdCorServicoPostalINT::getInfoServicoPostalPorId( $dto->getNumIdMdCorServicoPostal() ) )->getStrSinAnexarMidia() ?? 'N';

                        // Configura botao de Alterar Solicita��o quando esta foi devolvida ou n�o
                        if ($dto) {
                            if ($dto->getStrSinDevolvido() == "S") {
                                $arrComandos[] = '<button type="button" accesskey="A" id="btnAlterar" value="btnAlterar" onclick="validarFormulario(\'alterar\')" class="infraButton">
                                                  <span class="infraTeclaAtalho">A</span>lterar e Reenviar Solicita��o de Expedi��o </button>';
                            } else {
                                $arrComandos[] = '<button type="button" accesskey="A" id="btnAlterar" value="btnAlterar" onclick="validarFormulario(\'alterar\')" class="infraButton">
                                                  <span class="infraTeclaAtalho">A</span>lterar Solicita��o</button>';
                            }
                        }

                        // Se teve mudan�a no registro do contato, exibe aviso para o usuario
                        if ( $arrObjMdCorContato['isRegAlterado'] ) {
                            $id_destinatario_aux = $id_destinatario;
                            array_push($arrMsgValidacao, MdCorMensagemINT::$MSG_COR_02 );
                        }

                        // caso tenha alguma msg de alerta para o usu�ro, trata no if abaixo
                        $msg = null;
                        if ( $arrMsgValidacao ) {
                            if ( count($arrMsgValidacao) == 1 ) {
                                $msg = str_replace( "<br>" , "\n" , $arrMsgValidacao[0] );
                            } else {
                                foreach ( $arrMsgValidacao as $strMsg ) {
                                    $msg .= "- " . str_replace( "<br>" , "\n" , $strMsg ) . "\n\n";
                                }
                            }
                        }

                        if ( $msg ) {
                            PaginaSEI::getInstance()->adicionarMensagem( $msg , InfraPagina::$TIPO_MSG_AVISO );
                        }

                        $arrComandos[] = '<button type="button" accesskey="E" id="btnExcluir" value="btnExcluir" onclick="validarFormulario(\'excluir\')" class="infraButton">
                              <span class="infraTeclaAtalho">E</span>xcluir Solicita��o de Expedi��o</button>';
                    }
                    #$strUrlFecharConsulta = SessaoSEI::getInstance()->assinarLink('controlador.php?acao=arvore_visualizar&acao_origem=protocolo_modelo_cadastrar&arvore=1&id_protocolo=' . $_GET['id_doc']);

                    $arrComandos[] = '<button type="button" accesskey="C" id="btnFechar" value="btnFechar" onclick="fecharConsulta()" class="infraButton">
                              Fe<span class="infraTeclaAtalho">c</span>har</button>';

                } else {
                    $strUrlFecharConsulta = isset( $_GET['acao_retorno'] )
                        ? SessaoSEI::getInstance()->assinarLink('controlador.php?acao='.$_GET['acao_retorno'])
                        : SessaoSEI::getInstance()->assinarLink('controlador.php?acao=arvore_visualizar&acao_origem=procedimento_visualizar&id_procedimento='.$dto->getDblIdProtocolo().'&id_documento='.$_GET['id_doc']);

                    $_GET['visualizar'] = 'S';

                    $arrComandos[] = '<button type="button" accesskey="C" id="btnFechar" value="btnFechar" onclick="location.href = \'' . $strUrlFecharConsulta . '\';" class="infraButton">
                              Fe<span class="infraTeclaAtalho">c</span>har</button>';
                }
            }
            break;

        default:
            throw new InfraException("A��o '" . $_GET['acao'] . "' n�o reconhecida.");
    }
} catch (Exception $e) {
    PaginaSEI::getInstance()->processarExcecao($e);
}

PaginaSEI::getInstance()->montarDocType();
PaginaSEI::getInstance()->abrirHtml();
PaginaSEI::getInstance()->abrirHead();
PaginaSEI::getInstance()->montarMeta();
PaginaSEI::getInstance()->montarTitle(':: ' . PaginaSEI::getInstance()->getStrNomeSistema() . ' - ' . $strTitulo . ' ::');
PaginaSEI::getInstance()->montarStyle();
PaginaSEI::getInstance()->abrirStyle();
require_once('md_cor_expedicao_solicitada_cadastro_css.php');
PaginaSEI::getInstance()->fecharStyle();
PaginaSEI::getInstance()->montarJavaScript();
PaginaSEI::getInstance()->fecharHead();
PaginaSEI::getInstance()->abrirBody($strTitulo, 'onload="inicializar();"');

if ( $_GET['acao_origem'] != 'md_cor_expedicao_solicitada_cadastrar' && !isset($_POST['txaJustificativa']) && $arrComandos ) {
    ?>

    <form id="frmSolicitarExpedicao" method="post" action="<?= $strAcaoForm ?>">
        <?php
        PaginaSEI::getInstance()->montarBarraComandosSuperior($arrComandos);
        PaginaSEI::getInstance()->abrirAreaDados();
        ?>
        <?php $idMdCorEexpedicaoSolicitada = isset($_GET['id_md_cor_expedicao_solicitada']) ? $_GET['id_md_cor_expedicao_solicitada'] : ''; ?>
        <input type="hidden" id="hdnIdMdCorExpedicaoSolicitada" name="hdnIdMdCorExpedicaoSolicitada" value="<?= $idMdCorEexpedicaoSolicitada ?>"/>

        <div class="row">
            <div class="col-12 col-sm-12 col-md-6 col-lg-5 col-xl-5">
                <div class="form-group">
                    <?php if ($unidade_sol != '') { ?>
                        <label>Unidade Solicitante:</label>
                        <select name="" id="" class="infraSelect form-control" disabled>
                            <option value=""><?= $unidade_sol ?></option>
                        </select>
                    <?php } ?>
                </div>
            </div>
            <div class="col-12 col-sm-12 col-md-6 col-lg-5 col-xl-5">
                <div class="form-group">
                    <?php if ($_GET['acao'] == 'md_cor_expedicao_solicitada_consultar') { ?>
                        <label>Unidade Expedidora:</label>
                        <select name="" id="" class="infraSelect form-control" disabled>
                            <option value=""><?= $unidade_exp ?></option>
                        </select>
                    <?php } ?>
                </div>
            </div>
        </div>

        <div class="row">
            <div class="col-12 col-sm-12 col-md-6 col-lg-10 col-xl-10">
                <div class="form-group">
                    <label class="infraLabelObrigatorio">Servi�o Postal:</label>
                    <select class="infraSelect form-control" name="selServicoPostal" id="selServicoPostal" tabindex="<?= PaginaSEI::getInstance()->getProxTabDados(); ?>" onchange="gerenciarDadosServPostal(this)">
                        <?= $strSelectServicoPostal ?>
                    </select>
                </div>
            </div>
        </div>

        <div class="row">
            <div class="col-sm-12 col-md-12 col-lg-12 col-xl-12">

                <div class="row">
                    <div class="col-sm-12 col-md-12 col-lg-8 col-xl-6">
                        <label class="infraLabelCheck" for="chkAvisoRecebimento" style="font-size: .9rem">
                            <input type="checkbox" class="infraCheckbox form-control" id="chkAvisoRecebimento" name="chkAvisoRecebimento" <?= $chkNecessitaRecebimentoAR ?> tabindex="<?= PaginaSEI::getInstance()->getProxTabDados(); ?>">
                            Necessita de Aviso de Recebimento (AR)
                        </label>
                    </div>
                </div>

                <div class="clear">&nbsp;</div>

                <div class="row rowFieldSet1 rowFieldSet">
                    <div class="col-sm-12 col-md-12 col-lg-12 col-xl-12">
                        <fieldset id="fieldDocumentosExpedidos" class="infraFieldset sizeFieldset form-control mb-3 py-3">
                            <legend class="infraLegend">&nbspDocumentos Expedidos&nbsp</legend>

                            <div class="row">
                                <div class="col-sm-12 col-md-12 col-lg-8 col-xl-6">
                                    <label class="infraLabelOpcional">
                                        Documento Principal da Expedi��o: <?php echo $descricao_documento_principal; ?>
                                    </label>
                                </div>
                            </div>

                            <div class="row">
                                <div class="col" style="border-left: 6px solid red; margin: 15px;">
                                    <label class="infraLabelOpcional">
                                        <span style="color: red; font-weight: bold;"> ATEN��O: </span>
                                        A expedi��o � realizada de forma integrada com APIs dos Correios. <br>
                                        Assim, todo o fluxo de expedi��o � automatizado utilizando exclusivamente os dados de Endere�amento do Destinat�rio abaixo exibidos neste momento, sem qualquer rela��o com o teor do documento a ser expedido. <br>
                                        Altere antes se necess�rio.
                                    </label>
                                </div>
                            </div>

                            <div class="row">
                                <div class="col-sm-12 col-md-12 col-lg-8 col-xl-6">
                                    <label class="infraLabelOpcional">Destinat�rio:</label>
                                    <img align="top" id="imgAjuda"
                                         src="<?= PaginaSEI::getInstance()->getDiretorioSvgGlobal() ?>/ajuda.svg"
                                         name="ajuda"
                                         onmouseover="return infraTooltipMostrar('A Expedi��o pelos Correios utilizar� os dados do Contato do Destinat�rio indicado neste documento, conforme abaixo. \n \n Na Solicita��o original estes dados do Contato ser�o armazenados em separado para fins da realiza��o da Expedi��o solicitada para este documento e registros de controles. \n \n A Solicita��o somente poder� ter os dados do Contato do Destinat�rio alterados por meio desta tela somente at� a realiza��o da Expedi��o, no �mbito do \'\'Alterar Solicita��o de Expedi��o pelos Correios\'\' acionando o bot�o de a��o sobre o Destinat�rio \'\'Consultar/Alterar Dados do Destinat�rio\'\' constante na tela.', 'Ajuda');"
                                         onmouseout="return infraTooltipOcultar();"
                                         alt="Ajuda" class="infraImgModulo"/>
                                    <img id="imgAlterarDestinatario"
                                         onclick="alterarContato();"
                                         src="<?php echo PaginaSEI::getInstance()->getDiretorioSvgGlobal() ?>/alterar.svg"
                                         alt="Consultar/Alterar Dados do Destinat�rio Selecionado"
                                         title="Consultar/Alterar Dados do Destinat�rio Selecionado"
                                         class="infraImgModulo"
                                         tabindex="<?= PaginaSEI::getInstance()->getProxTabDados() ?>"/>
                                    <div style="margin-top: 7px">
                                        <?php
                                        if ($tratamento_destinatario != '') {
                                            echo '<label name=lblGenero id=lblGenero class="infraLabelOpcional" >&nbsp;&nbsp;&nbsp;&nbsp;' . $tratamento_destinatario . '</label> </br>';
                                        }
                                        ?>
                                        <label name=lblNome id=lblNome
                                               class=""
                                               style="text-transform:uppercase;">&nbsp;&nbsp;&nbsp;&nbsp;<?php echo InfraString::transformarCaixaAlta($nome_destinatario); ?></label>
                                        </br>
                                        <?php
                                        if ($cargo_destinatario != '') {
                                            echo '<label name=lblCargo id=lblCargo class="infraLabelOpcional">&nbsp;&nbsp;&nbsp;&nbsp;' . $cargo_destinatario . '</label></br>';
                                        } ?>
                                        <label name=lblNomeAssociado id=lblNomeAssociado class="infraLabelOpcional" <?php if ($nome_destinatario_associado == '') { echo 'style="display: none; margin-bottom: 0;"'; } ?>>&nbsp;&nbsp;&nbsp;&nbsp;<?=$nome_destinatario_associado?></label>
                                    </div>
                                </div>
                            </div>

                            <div class="row"></div>

                            <div class="row">
                                <div class="col-sm-12 col-md-12 col-lg-8 col-xl-6">
                                    <label name=lblEndereco id=lblEndereco
                                           class="infraLabelOpcional">&nbsp;&nbsp;&nbsp;&nbsp;<?php
                                        echo $endereco_destinatario;
                                        echo $complemento_destinatario ? ', ' . $complemento_destinatario : '';
                                        echo ', ' . $bairro_destinatario;
                                        ?></label>
                                </div>
                            </div>

                            <div class="row">
                                <div class="col-sm-12 col-md-12 col-lg-8 col-xl-6">
                                    <label name=lblCep id=lblCep
                                           class="infraLabelOpcional">&nbsp;&nbsp;&nbsp;&nbsp;<?php
                                        echo 'CEP: ' . $cep_destinatario . ' - ' . $cidade_destinatario . '/' . $uf_destinatario;
                                        ?></label>
                                </div>
                            </div>

                            <div class="row">
                                <div class="col-sm-12 col-md-12 col-lg-8 col-xl-6">
                                    <input type="hidden" id="hdnContatoObject" onclick="alert('');"
                                           onchange="alert(this.value);"
                                           onbeforeupdate="alert(this.value);" onchange="alert('a')"
                                           name="hdnContatoObject"
                                           value=""/>
                                    <input type="hidden" id="hdnContatoIdentificador" onchange="alert(this.value);"
                                           name="hdnContatoIdentificador" value="<?= $id_destinatario_aux ?>"/>
                                    <select id="selContato" name="selContato" style="display: none !important"
                                            class="form-control"
                                            tabindex="<?= PaginaSEI::getInstance()->getProxTabDados() ?>">
                                        <option onclick="alert('');" onchange="alert(this.value);"
                                                onbeforeupdate="alert(this.value);"
                                                value="<?= $id_destinatario ?>"><?= $nome_destinatario ?></option>
                                    </select>
                                </div>
                            </div>

                            <div class="clear">&nbsp;</div>

                            <div class="row" id="divSelContato">
                                <div class="col-sm-12 col-md-12 col-lg-8 col-xl-6">
                                    <label class="infraLabelCheck" for="chkDocumentoPossuiAnexo">
                                        <input type="checkbox" class="infraCheckbox form-control"
                                               id="chkDocumentoPossuiAnexo" onchange="marcarChkDocumentoPossuiAnexo()"
                                            <?php echo $chkPossuiAnexo; ?>
                                               name="chkDocumentoPossuiAnexo"
                                               tabindex="<?= PaginaSEI::getInstance()->getProxTabDados(); ?>" <?= $chkPossuiAnexoDisable ?> >
                                        O Documento a ser expedido possui Anexos
                                    </label>
                                </div>
                            </div>
                            <br>
                            <div class="row" id="divProtocoloAnexo">
                                <div class="col-sm-12 col-md-12 col-lg-8 col-xl-6">
                                    <label for="txtProtocoloAnexo" class="infraLabelObrigatorio">
                                        Protocolos Anexo ao Documento Principal da Expedi��o:
                                    </label>
                                    <input type="hidden" id="txtProtocoloAnexo" name="txtProtocoloAnexo"
                                           class="infraText form-control"
                                           tabindex="<?= PaginaSEI::getInstance()->getProxTabDados() ?>"/>
                                    <input type="hidden" id="hdnProtocoloAnexo" name="hdnProtocoloAnexo" value=""/>
                                    <div class="input-group mb-3" id="divIcones">

                                        <select id="selProtocoloAnexo" name="selProtocoloAnexo" size="6"
                                                multiple="multiple"
                                                class="infraSelect"
                                                tabindex="<?= PaginaSEI::getInstance()->getProxTabDados() ?>">
                                            <?php if (isset($arrProtocoloAnexo) && is_array($arrProtocoloAnexo) && count($arrProtocoloAnexo) > 0) { ?>
                                                <?php foreach ($arrProtocoloAnexo as $protAnexo) { ?>
                                                    <option selected="selected"
                                                            value='<?php echo $protAnexo->getDblIdProtocolo(); ?>'>
                                                        <?php $tipo = !empty($protAnexo->getStrNomeSerie()) ? $protAnexo->getStrNomeSerie() : $protAnexo->getStrNomeProcedimento(); ?>
                                                        <?php echo $tipo . " " . $protAnexo->getStrNumeroDocumento() . " (" . $protAnexo->getStrProtocoloFormatado() . ")"; ?>
                                                    </option>
                                                    <?php
                                                }
                                            }
                                            ?>
                                        </select>

                                        <div class="divIcones">
                                            <img id="imgLupaProtocoloAnexo"
                                                 onclick="objLupaProtocoloAnexo.selecionar(700, 500);"
                                                 src="<?php echo PaginaSEI::getInstance()->getDiretorioSvgGlobal() ?>/pesquisar.svg"
                                                 alt="Selecionar Protocolos Anexos ao Documento Principal da Expedi��o"
                                                 title="Selecionar Protocolos Anexos ao Documento Principal da Expedi��o"
                                                 tabindex="<?= PaginaSEI::getInstance()->getProxTabDados() ?>"/>
                                            <br>

                                            <img id="imgExcluirProtocoloAnexo"
                                                 onclick="objLupaProtocoloAnexo.remover()"
                                                 src="<?php echo PaginaSEI::getInstance()->getDiretorioSvgGlobal() ?>/remover.svg"
                                                 alt="Remover Protocolos Anexos ao Documento Principal da Expedi��o Selecionadas"
                                                 title="Remover Protocolos Anexos ao Documento Principal da Expedi��o Selecionadas"
                                                 tabindex="<?= PaginaSEI::getInstance()->getProxTabDados() ?>"/>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <div class="row" id="divProtocoloAnexo2">
                                <div class="col-sm-12 col-md-12 col-lg-8 col-xl-6">
                                    <div class="clear">&nbsp;</div>
                                </div>
                            </div>
                        </fieldset>
                    </div>
                </div>
            </div>
        </div>

        <div class="clear">&nbsp;</div>

        <div class="row rowFieldSet1 rowFieldSet">
            <div class="col-sm-12 col-md-12 col-lg-12 col-xl-12">
                <fieldset id="fieldFormatoDocumentos" class="infraFieldset sizeFieldset form-control mb-3 py-3">
                    <legend class="infraLegend">&nbspFormato de Expedi��o dos Documentos&nbsp</legend>

                    <table class="infraTable table table-responsive-lg" summary="Formato Expedi��o dos Documentos" id="tblFormatoExpedicao">
                        <thead>
                        <tr>
                            <th class="infraTh text-center" style="display: none;" width="0">ID Documento</th>
                            <th class="infraTh text-center">Documento</th>
                            <th class="infraTh text-center elementoRadio" width="300px">Formato da Expedi��o</th>
                            <th class="infraTh text-center elementoRadio" width="300px">Impress�o</th>
                            <th class="infraTh text-center">Justificativa</th>
                        </tr>
                        </thead>
                        <tbody>

                        <?php
                        //entra aqui quando a janela � aberta em modo de consulta
                        if ($_GET['acao'] != 'md_cor_expedicao_solicitada_cadastrar') {

                            $bolExistePLP = false;
                            if ($dto) {
                                $bolExistePLP = $dto->getNumIdMdCorPlp() != null ? true : false;
                            }
                            if (isset($_GET['visualizar']) || $bolExistePLP ) {
                                if (isset($arrFormatos) && is_array($arrFormatos) && count($arrFormatos) > 0) {
                                    ?>
                                    <?php foreach ($arrFormatos as $formatoDTO) { ?>
                                        <tr class="infraTrClara">
                                            <?php $tipo = !empty($formatoDTO->getStrNomeSerie()) ? $formatoDTO->getStrNomeSerie() : $formatoDTO->getStrNomeProcedimento(); ?>
                                            <td>
                                                <?php
                                                $numeroSerie = $formatoDTO->getStrNumeroDocumento();
                                                $strUrlDocumento = SessaoSEI::getInstance()->assinarLink('controlador.php?acao=procedimento_trabalhar&id_documento=' . $formatoDTO->getDblIdDocumentoPrincipal());
                                                echo $tipo . ' ' . $numeroSerie . ' <a class="protocoloNormal" style="font-size: 1.0em !important; font-size:1em" href="' . $strUrlDocumento . '" target="_blank">(' . $formatoDTO->getStrProtocoloFormatado() . ')</a>';
                                                echo ($numeroProtocoloFormatado == $formatoDTO->getStrProtocoloFormatado()) ? " - Principal" : " - Anexo";
                                                ?>
                                            </td>

                                            <td>
                                                <div class="row">
                                                    <div class="col-12">
                                                        <div id="divRdoImpressao" class="infraDivRadio d-inline-block">
                                                            <div class="infraRadioDiv ">
                                                                <input id="rdoImpresso_<?php echo $formatoDTO->getDblIdProtocolo() ?>" class="infraRadioInput" <?php if ($formatoDTO->getStrFormaExpedicao() == MdCorExpedicaoFormatoRN::$TP_FORMATO_IMPRESSO) echo 'checked'; ?>
                                                                       value="<?php echo MdCorExpedicaoFormatoRN::$TP_FORMATO_IMPRESSO; ?>" type="radio" name="rdoFormato_<?php echo $formatoDTO->getDblIdProtocolo() ?>">
                                                                <label class="infraRadioLabel" for="rdoImpresso_"></label>
                                                            </div>
                                                            <label id="lblImpresso_" for="rdoImpresso_" class="infraLabelRadio" tabindex="507">Impresso</label>
                                                        </div>
                                                        <div id="divRdoGravacao" class="infraDivRadio d-inline-block">
                                                            <div class="infraRadioDiv ">
                                                                <input id="rdoMidia_<?php echo $formatoDTO->getDblIdProtocolo() ?>" class="infraRadioInput" <?php if ($formatoDTO->getStrFormaExpedicao() == MdCorExpedicaoFormatoRN::$TP_FORMATO_MIDIA) echo 'checked'; ?>
                                                                       value="<?php echo MdCorExpedicaoFormatoRN::$TP_FORMATO_MIDIA; ?>" type="radio" name="rdoFormato_<?php echo $formatoDTO->getDblIdProtocolo() ?>">
                                                                <label class="infraRadioLabel" for="rdoFormato_<?php echo $formatoDTO->getDblIdProtocolo() ?>"></label>
                                                            </div>
                                                            <label id="lblImpresso_" for="rdoFormato_<?php echo $formatoDTO->getDblIdProtocolo() ?>" class="infraLabelRadio" tabindex="507">Grava��o em M�dia</label>
                                                        </div>
                                                    </div>
                                                </div>

                                            </td>
                                            <td>
                                                <div class="row">
                                                    <div class="col-12">
                                                        <div id="divRdoImpressao" class="infraDivRadio d-inline-block" style="<?if ($formatoDTO->getStrFormaExpedicao() == MdCorExpedicaoFormatoRN::$TP_FORMATO_MIDIA) echo 'display: none !important'; ?>">
                                                            <div class="infraRadioDiv">
                                                                <input type="radio" name="rdoImpressao_<?php echo $formatoDTO->getDblIdProtocolo() ?>"
                                                                       id="rdoImpressao1_<?php echo $formatoDTO->getDblIdProtocolo() ?>"
                                                                       value="<?php echo MdCorExpedicaoFormatoRN::$TP_IMPRESSAO_PRETO_BRANCO; ?>"
                                                                    <?php if ($formatoDTO->getStrImpressao() == MdCorExpedicaoFormatoRN::$TP_IMPRESSAO_PRETO_BRANCO) echo 'checked'; ?>
                                                                       class="infraRadioInput"
                                                                       onclick="justificativaImprimir(this)">
                                                                <label class="infraRadioLabel"
                                                                       for="rdoImpressao1_<?php echo $formatoDTO->getDblIdProtocolo() ?>'"></label>
                                                            </div>
                                                            <label id="lblImpressao_<?php echo $formatoDTO->getDblIdProtocolo() ?>"
                                                                   for="rdoImpressao1_<?php echo $formatoDTO->getDblIdProtocolo() ?>"
                                                                   class="infraLabelRadio lblImpressao_<?php echo $formatoDTO->getDblIdProtocolo() ?>" tabindex="507">Preto e
                                                                Branco</label>
                                                        </div>
                                                        <div id="divRdoGravacao" class="infraDivRadio d-inline-block" style="<?if ($formatoDTO->getStrFormaExpedicao() == MdCorExpedicaoFormatoRN::$TP_FORMATO_MIDIA) echo 'display: none !important'; ?>">
                                                            <div class="infraRadioDiv ">
                                                                <input type="radio" name="rdoImpressao_<?php echo $formatoDTO->getDblIdProtocolo() ?>"
                                                                       id="rdoImpressao2_<?php echo $formatoDTO->getDblIdProtocolo() ?>"
                                                                    <?php if ($formatoDTO->getStrImpressao() == MdCorExpedicaoFormatoRN::$TP_IMPRESSAO_COLORIDO) echo 'checked'; ?>
                                                                       value="<?php echo MdCorExpedicaoFormatoRN::$TP_IMPRESSAO_COLORIDO; ?>"
                                                                       class="infraRadioInput"
                                                                       onclick="justificativaImprimir(this)">
                                                                <label class="infraRadioLabel"
                                                                       for="rdoImpressao2_<?php echo $formatoDTO->getDblIdProtocolo() ?>"></label>
                                                            </div>
                                                            <label id="lblImpressao_<?php echo $formatoDTO->getDblIdProtocolo() ?>"
                                                                   for="rdoImpressao2_<?php echo $formatoDTO->getDblIdProtocolo() ?>" class="infraLabelRadio lblImpressao_<?php echo $formatoDTO->getDblIdProtocolo() ?>"
                                                                   tabindex="507">Colorido</label>
                                                        </div>
                                                    </div>
                                                </div>
                                            </td>
                                            <td><?php echo $formatoDTO->getStrJustificativa(); ?></td>
                                        </tr>

                                        <?php
                                    }
                                }
                            }
                        }
                        ?>
                        </tbody>
                    </table>
                </fieldset>
            </div>
        </div>
        <?php
        if ($dto) {
            if ($dto->getStrSinDevolvido() == "S") { ?>
                <div class="clear">&nbsp;</div>
                <div class="row">
                    <div class="col-sm-12 col-md-12 col-lg-12 col-xl-12">
                        <label for="txaJustificativaDevolucao" class="infraLabelOpcional">
                            Justificativa da Devolu��o pela Unidade Expedidora
                        </label>
                        <label for="txaJustificativaDevolucao" class="infraLabelOpcional">
                        </label>
                        <textarea class="infraTextArea form-control" name="txaJustificativaDevolucao" id="txaJustificativaDevolucao" rows="3"
                                  cols="150" readonly><?php echo PaginaSEI::tratarHTML($txtJustificativaDevolucao); ?></textarea>
                    </div>
                </div>
                <?php
            }
        }
        ?>
        <? if ($_GET['acao'] != 'md_cor_expedicao_solicitada_cadastrar' && !$strIsConsultar) : ?>
            <div class="clear">&nbsp;</div>
            <div class="row">
                <div class="col-sm-12 col-md-12 col-lg-12 col-xl-12">
                    <label for="txaJustificativa" class="infraLabelOpcional">
                        Justificativa para Altera��o / Exclus�o da Solicita��o:
                    </label>
                    <input type="hidden" id="hdnIdProcedimento" name="hdnIdProcedimento"
                           value="<?php echo $_GET['id_doc']; ?>"/>
                    <input type="hidden" id="hdnIdExpedicaoSolicitada" name="hdnIdExpedicaoSolicitada"
                           value="<?php echo $_GET['id_md_cor_expedicao_solicitada']; ?>"/>
                    <label for="txaJustificativa" class="infraLabelOpcional">
                    </label>
                    <textarea class="infraTextArea form-control" name="txaJustificativa" id="txaJustificativa" rows="3"
                              cols="150"
                              onkeypress="return infraMascaraTexto(this, event, 500);"
                              maxlength="500"
                              tabindex="<?= PaginaSEI::getInstance()->getProxTabDados() ?>"></textarea>
                </div>
            </div>
        <? endif; ?>

        <div class="clear">&nbsp;</div>
        <?php
        PaginaSEI::getInstance()->montarBarraComandosInferior($arrComandos);
        PaginaSEI::getInstance()->fecharAreaDados();
        ?>
        <input type="hidden" name="id_doc" value="<?php echo $id_doc; ?>"/>
        <input type="hidden" name="hdnIdProtocoloAnexo" id="hdnIdProtocoloAnexo" value=""/>
        <input type="hidden" name="hdnTbFormatos" id="hdnTbFormatos" value=""/>
    </form>
<?php }
PaginaSEI::getInstance()->montarAreaDebug();
PaginaSEI::getInstance()->fecharBody();
PaginaSEI::getInstance()->fecharHtml();
require_once 'md_cor_funcoes_js.php';
if (!isset($_POST['txaJustificativa']) && $arrComandos ) {
    require_once 'md_cor_expedicao_solicitada_cadastro_js.php';
}
