<?php
try {
    require_once dirname(__FILE__) . '/../../SEI.php';

    session_start();

    //////////////////////////////////////////////////////////////////////////////
    InfraDebug::getInstance()->setBolLigado(false);
    InfraDebug::getInstance()->setBolDebugInfra(true);
    InfraDebug::getInstance()->limpar();
    //////////////////////////////////////////////////////////////////////////////

    SessaoSEI::getInstance()->validarLink();
    SessaoSEI::getInstance()->validarPermissao($_GET['acao']);
    if ($_GET['acao_origem'] != 'md_cor_geracao_plp_listar') {
        PaginaSEI::getInstance()->setTipoPagina(PaginaSEI::$TIPO_PAGINA_SIMPLES);
    }

    //Variaveis
    $strAcaoForm = SessaoSEI::getInstance()->assinarLink('controlador.php?acao=' . $_GET['acao'] . '&acao_origem=' . $_GET['acao']);
    $strLinkAlterarContato = SessaoSEI::getInstance()->assinarLink('controlador.php?acao=contato_alterar&acao_origem=' . $_GET['acao']);
    $strLinkAjaxContatoListar = SessaoSEI::getInstance()->assinarLink('controlador_ajax.php?acao_ajax=md_cor_contato_listar&acao_origem=' . $_GET['acao']);

    $chkNecessitaRecebimentoAR = "checked";
    $chkPossuiAnexo = "";
    $id_doc = $_GET['id_doc'];


    switch ($_GET['acao']) {

        case 'md_cor_expedicao_solicitada_excluir':

            $strTitulo = 'Excluir Expedição pelos Correios';

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
                $dto->setNumIdMdCorExpedicaoSolicitada($idExpedicaoSolicitada);
                $dto->setDistinct(true);
                $dto = $rn->consultar($dto);
                
                if (!(count($dto) == 1)) {
                    throw new InfraException("Registro não encontrado.");
                }

                if ($dto->getStrSinNecessitaAr() == 'S') {
                    $chkNecessitaRecebimentoAR = "checked";
                }

                //unidade expedidora
                $unidade_exp = $dto->getStrSiglaUnidade() . ' - ' . $dto->getStrDescricaoUnidade();

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
                    $documentoBD = new DocumentoBD(SessaoSEI::getObjInfraIBanco());
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
                            $documentoBD = new DocumentoBD(SessaoSEI::getObjInfraIBanco());
                            $documentoDTO = new DocumentoDTO();
                            $documentoDTO->setDblIdDocumento($objProtocoloAnexo->getDblIdProtocolo());
                            $documentoDTO->setStrSinBloqueado('N');
                            $documentoBD->alterar($documentoDTO);
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


                $objEntradaConsultarDocumentoAPI = new EntradaConsultarDocumentoAPI();
                $objEntradaConsultarDocumentoAPI->setIdDocumento($_POST['hdnIdProcedimento']);

                $objSeiRN = new SeiRN();
                $objSaidaConsultarDocumentoAPI = new SaidaConsultarDocumentoAPI();
                $objSaidaConsultarDocumentoAPI = $objSeiRN->consultarDocumento($objEntradaConsultarDocumentoAPI);

                $strLinkMontarArvore = SessaoSEI::getInstance()->assinarLink('controlador.php?acao=procedimento_trabalhar&acao_origem=' . $_GET['acao'] . '&acao_retorno=' . PaginaSEI::getInstance()->getAcaoRetorno() . '&id_procedimento=' . $objSaidaConsultarDocumentoAPI->getIdProcedimento() . '&id_documento=' . $_POST['hdnIdProcedimento']/* .'&id_procedimento_anexado='.$dblIdProcedimentoAnexado */);
                echo "<script>";
                echo "window.parent.document.location.href = '" . $strLinkMontarArvore . "';";
                echo "</script>";
            }

            $arrComandos[] = '<button type="button" accesskey="S" id="btnSalvar" value="btnSalvar" onclick="validarFormulario();" class="infraButton">
                      <span class="infraTeclaAtalho">S</span>alvar</button>';
            $arrComandos[] = '<button type="button" accesskey="C" id="btnCancelar" value="btnCancelar" onclick="location.href = \'' . SessaoSEI::getInstance()->assinarLink('controlador.php?acao=md_cor_expedicao_solicitada_consultar&arvore=1&id_doc=' . $_GET['id_doc'] . '&id_md_cor_expedicao_solicitada=' . $_GET['id_md_cor_expedicao_solicitada']) . '\';" class="infraButton">
                              <span class="infraTeclaAtalho">C</span>ancelar</button>';



            break;

        default:
            throw new InfraException("Ação '" . $_GET['acao'] . "' não reconhecida.");
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
?>
#frmSolicitarExpedicao .bloco {float: left;  margin-top: 1%;  margin-right: 1%;}
#frmSolicitarExpedicao .clear {clear: both;}
#frmSolicitarExpedicao select:not([multiple]), input[type=text] {width: 330px;  border: .1em solid #666;}
#frmSolicitarExpedicao input[type="text"]:disabled {background: #dddddd;}
#frmSolicitarExpedicao select[multiple], textarea {width: 700px;  border: .1em solid #666;}
#frmSolicitarExpedicao label:not([for^=rdo]) {display: block;  white-space: nowrap;}
#frmSolicitarExpedicao label[for^=rdo] {font-size: 1.0em;}
#frmSolicitarExpedicao input[type=checkbox], input[type=radio] {position: relative;  top: 2px;}
#imgLupaProtocoloAnexo {position: absolute;  left: 740px;  top: -40px;}
#imgExcluirProtocoloAnexo {position: absolute;  left: 740px;  top: -20px;}
#divProtocoloAnexo{ display:none; }
#divProtocoloAnexo2{ display:none; }
<?php
PaginaSEI::getInstance()->fecharStyle();
PaginaSEI::getInstance()->montarJavaScript();
#PaginaSEI::getInstance()->abrirJavaScript(); 
require_once 'md_cor_expedicao_solicitada_excluir_js.php';
#PaginaSEI::getInstance()->fecharJavaScript();
PaginaSEI::getInstance()->fecharHead();
PaginaSEI::getInstance()->abrirBody($strTitulo, 'onload="inicializar();"');
?>

<form id="frmSolicitarExpedicao" method="post" action="">
    <?php PaginaSEI::getInstance()->montarBarraComandosSuperior($arrComandos); ?>
    <?php PaginaSEI::getInstance()->abrirAreaDados(); ?>

    <div class="bloco">
        <label for="txaJustificativa" class="infraLabelOpcional">
            Justificativa:
        </label>
        <input type="hidden" id="hdnIdProcedimento" name="hdnIdProcedimento" value="<?php echo $_GET['id_protocolo']; ?>"/>
        <input type="hidden" id="hdnIdExpedicaoSolicitada" name="hdnIdExpedicaoSolicitada" value="<?php echo $_GET['id_md_cor_expedicao_solicitada']; ?>"/>
        <textarea class="infraTextArea" name="txaJustificativa" id="txaJustificativa" rows="5"
                  cols="150"
                  onkeypress="return infraMascaraTexto(this,event,500);"
                  maxlength="500"
                  style="width: 100% !important;"
                  tabindex="<?= PaginaSEI::getInstance()->getProxTabDados() ?>"></textarea>
    </div>

    <div class="clear">&nbsp;</div>
    <?php PaginaSEI::getInstance()->montarBarraComandosInferior($arrComandos); ?>
    <?php PaginaSEI::getInstance()->fecharAreaDados(); ?>
    <input type="hidden" name="id_doc" value="<?php echo $id_doc; ?>"/>
    <input type="hidden" name="hdnIdProtocoloAnexo" id="hdnIdProtocoloAnexo" value=""/>
    <input type="hidden" name="hdnTbFormatos" id="hdnTbFormatos" value=""/>
</form>
<?php
PaginaSEI::getInstance()->montarAreaDebug();
PaginaSEI::getInstance()->fecharBody();
PaginaSEI::getInstance()->fecharHtml();
