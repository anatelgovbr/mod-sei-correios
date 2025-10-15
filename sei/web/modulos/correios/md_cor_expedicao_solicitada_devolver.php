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
    PaginaSEI::getInstance()->setTipoPagina(PaginaSEI::$TIPO_PAGINA_SIMPLES);
    $_SESSION['idDocumentoPrincipal'] = $_GET['id_doc'];
    //Variaveis
    $strAcaoForm = SessaoSEI::getInstance()->assinarLink('controlador.php?acao=md_cor_expedicao_solicitada_devolver_alterar&acao_origem=' . $_GET['acao']);

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
    PaginaSEI::getInstance()->abrirBody($strTitulo);

    switch ($_GET['acao']) {
        case 'md_cor_expedicao_solicitada_devolver_consultar':

            $strTitulo = 'Devolver Expedição pelos Correios';

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
            $dto->retDblIdDocumentoPrincipal();
            $dto->retStrDescricaoServicoPostal();
            $dto->retDthDataSolicitacao();
            $dto->retStrProtocoloFormatado();
            $dto->setNumIdMdCorExpedicaoSolicitada($idExpedicaoSolicitada);
            $dto->setDistinct(true);
            $dto = $rn->consultar($dto);

            if (is_null($dto)) {
                throw new InfraException("Registro não encontrado.");
            }

            $unidade_sol = $dto->getStrDescricaoUnidade() . " (" . $dto->getStrSiglaUnidade() . ")";
            $dataSolicitada = substr($dto->getDthDataSolicitacao(), 0, 10);
            $protocoloFormatado = $dto->getStrProtocoloFormatado();

            //informaçoes do doc principal
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
            $descricao_documento_principal = $nomeTipoDocumento . " ". $numeroDoc . " (" . $numeroProtocoloFormatado . ")";


            //obtendo informações do destinatario
            $objMdCorContatoDTO = new MdCorContatoDTO();
            $objMdCorContatoDTO->retTodos(true);

            $objMdCorContatoDTO->setNumIdContato($dto->getNumIdContatoDestinatario());
            $objMdCorContatoDTO->setNumIdMdCorExpedicaoSolicitada($dto->getNumIdMdCorExpedicaoSolicitada());

            $objMdCorContatoRN = new MdCorContatoRN();

            $arrObjMdCorContatoDTO = $objMdCorContatoRN->consultar($objMdCorContatoDTO);

            $strDestinatario = "";

            $strDestinatario .= mb_strtoupper($arrObjMdCorContatoDTO->getStrNome(), 'ISO-8859-1') . "\n";
            if ($arrObjMdCorContatoDTO->getStrSinEnderecoAssociado() == 'S') {
                $strDestinatario .= $arrObjMdCorContatoDTO->getStrNomeContatoAssociado() . "\n";
            }
            $strDestinatario .= $arrObjMdCorContatoDTO->getStrNomeTipoContato() . "\n";
            if ($arrObjMdCorContatoDTO->getStrSinEnderecoAssociado() == 'S') {
                $strDestinatario .= $arrObjMdCorContatoDTO->getStrNomeCidadeContatoAssociado() . "/";
                $strDestinatario .= $arrObjMdCorContatoDTO->getStrSiglaUfContatoAssociado() . "\n";
            } else {
                $strDestinatario .= $arrObjMdCorContatoDTO->getStrNomeCidade() . "/";
                $strDestinatario .= $arrObjMdCorContatoDTO->getStrSiglaUf() . "\n";
            }

            $arrComandos[] = '<button type="button" accesskey="D" id="btnAlterar" value="btnAlterar" onclick="validarFormulario()" class="infraButton">
                      <span class="infraTeclaAtalho">D</span>evolver Solicitação</button>';
            $arrComandos[] = '<button type="button" accesskey="C" id="btnFechar" value="btnFechar" onclick="$(window.top.document).find(\'div[id^=divInfraSparklingModalClose]\').click();" class="infraButton">
                      Fe<span class="infraTeclaAtalho">c</span>har</button>';

            break;
        case 'md_cor_expedicao_solicitada_devolver_alterar':

            $arrComandos[] = "";
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
                    $dto->retNumIdUnidadeGeradora();
                    $dto->retDblIdUnidadeExpedidora();
                    $dto->setNumIdMdCorExpedicaoSolicitada($idExpedicaoSolicitada);
                    $dto->setDistinct(true);
                    $dto = $rn->consultar($dto);

                    $dto->setStrSinDevolvido("S");
                    $dto->setStrJustificativaDevolucao($_POST['txaJustificativa']);
                    $rn->alterar($dto);

                    $documentoRN = new DocumentoRN();
                    $objDocumentoDTO = new DocumentoDTO();
                    $objDocumentoDTO->setDblIdDocumento($dto->getDblIdDocumentoPrincipal());
                    $objDocumentoDTO->retStrProtocoloDocumentoFormatado();
                    $objDocumentoDTO->retDblIdProcedimento();
                    $objDocumentoDTO->retDblIdDocumento();
                    $arrDocumentoDTO = $documentoRN->consultarRN0005($objDocumentoDTO);

                    //Cadastro de Andamento
                    $objEntradaLancarAndamentoAPI = new EntradaLancarAndamentoAPI();
                    $objEntradaLancarAndamentoAPI->setIdProcedimento($arrDocumentoDTO->getDblIdProcedimento());
                    $objEntradaLancarAndamentoAPI->setIdTarefaModulo('MD_COR_SOLICITACAO_DEVOLUCAO');

                    $arrObjAtributoAndamentoAPI = array();
                    $arrObjAtributoAndamentoAPI[] = $rn->_retornaObjAtributoAndamentoAPI('DOCUMENTO', $arrDocumentoDTO->getStrProtocoloDocumentoFormatado(), $arrDocumentoDTO->getDblIdDocumento());
                    $arrObjAtributoAndamentoAPI[] = $rn->_retornaObjAtributoAndamentoAPI('JUSTIFICATIVA_DEVOLUCAO_SOLICITACAO_EXPEDICAO', $_POST['txaJustificativa']);
                    $objEntradaLancarAndamentoAPI->setAtributos($arrObjAtributoAndamentoAPI);

                    $objSeiRN = new SeiRN();
                    $objSeiRN->lancarAndamento($objEntradaLancarAndamentoAPI);

                    $parametrosEmail = array();
                    $parametrosEmail["siglaUnidadeSolicitante"] = $_POST["selUnidadeSolicitante"];
                    $protocoloDocumento = explode("(", $_POST["lblDocumentoPrincipal"]);
                    $protocoloDocumento = explode(")", $protocoloDocumento[1]);
                    $parametrosEmail["documento"] = $protocoloDocumento[0];
                    $parametrosEmail["processo"] = $_POST["lblProcesso"];
                    $parametrosEmail["justificativaDevolucao"] = $_POST['txaJustificativa'];
                    $parametrosEmail["idUnidade"] = $dto->getNumIdUnidade();
                    $parametrosEmail["IdUnidadeGeradora"] = $dto->getNumIdUnidadeGeradora();
                    $parametrosEmail["IdUnidadeExpedidora"] = $dto->getDblIdUnidadeExpedidora();

                    $rn->enviarEmail($parametrosEmail);

                } catch (Exception $e) {
                    PaginaSEI::getInstance()->processarExcecao($e);
                }
            }
            $strLinkMontarArvore = SessaoSEI::getInstance()->assinarLink('controlador.php?acao=md_cor_geracao_plp_listar');
            echo "<script>";
            echo "window.parent.document.location.href = '" . $strLinkMontarArvore . "';";
            echo "$(window.top.document).find('div[id^=divInfraSparklingModalClose]').click();";
            echo "</script>";
            break;
    }
} catch (Exception $e) {
    PaginaSEI::getInstance()->processarExcecao($e);
}

?>
    <div id="divTituloModal" class="infraBarraLocalizacao"><?= $strTitulo ?></div>
    <form id="frmDevolverExpedicao" method="post" action="<?= $strAcaoForm ?>">
        <?php PaginaSEI::getInstance()->abrirAreaDados(); ?>
        <?php $idMdCorEexpedicaoSolicitada = isset($_GET['id_md_cor_expedicao_solicitada']) ? $_GET['id_md_cor_expedicao_solicitada'] : ''; ?>
        <input type="hidden" id="hdnIdMdCorExpedicaoSolicitada" name="hdnIdMdCorExpedicaoSolicitada" value="<?= $idMdCorEexpedicaoSolicitada ?>"/>
        <br>
        <div class="row">
            <div class="col-12 col-sm-12 col-md-6 col-lg-6 col-xl-6">
                <div class="form-group">
                    <?php if ($unidade_sol != '') { ?>
                        <label>Unidade Solicitante:</label>
                        <select name="selUnidadeSolicitante" id="selUnidadeSolicitante" class="infraSelect form-control" readonly>
                            <option value="<?= $unidade_sol ?>" selected><?= $unidade_sol ?></option>
                        </select>
                    <?php } ?>
                </div>
            </div>
            <div class="col-12 col-sm-12 col-md-6 col-lg-6 col-xl-6">
                <div class="form-group">
                    <label>Data da Solicitação:</label>
                    <input type="text" class="form-control" readonly value="<?= $dataSolicitada; ?>">
                </div>
            </div>
        </div>
        <div class="row">
            <div class="col-12 col-sm-12 col-md-6 col-lg-4 col-xl-4">
                <div class="form-group">
                    <label for="lblDocumentoPrincipal">Documento Principal:</label>
                    <input type="text" class="form-control" name="lblDocumentoPrincipal" id="lblDocumentoPrincipal" readonly value="<?= $descricao_documento_principal; ?>">
                </div>
            </div>
            <div class="col-12 col-sm-12 col-md-6 col-lg-4 col-xl-4">
                <div class="form-group">
                    <label>Processo:</label>
                    <input type="text" name="lblProcesso" id="lblProcesso" class="form-control" readonly value="<?= $protocoloFormatado; ?>">
                </div>
            </div>
        </div>
        <div class="row">
            <div class="col-12 col-sm-12 col-md-12 col-lg-12 col-xl-12">
                <div class="form-group">
                    <label>Destinatário:</label>
                    <textarea class="form-control" readonly rows="4"><?= $strDestinatario; ?></textarea>
                </div>
            </div>
        </div>
        <div class="row">
            <div class="col-12 col-sm-12 col-md-12 col-lg-12 col-xl-12">
                <div class="form-group">
                    <label for="txaJustificativa">Justificativa para Devolução:</label>
                    <textarea class="form-control" name="txaJustificativa" id="txaJustificativa" rows="3"></textarea>
                </div>
            </div>
        </div>
        <?php PaginaSEI::getInstance()->montarBarraComandosInferior($arrComandos); ?>
        <?php PaginaSEI::getInstance()->fecharAreaDados(); ?>
    </form>
<?php
PaginaSEI::getInstance()->montarAreaDebug();
PaginaSEI::getInstance()->fecharBody();
PaginaSEI::getInstance()->fecharHtml();
require_once 'md_cor_expedicao_solicitada_devolver_js.php';