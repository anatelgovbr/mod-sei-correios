<?
/**
 * TRIBUNAL REGIONAL FEDERAL DA 4ª REGIÃO
 *
 * 22/12/2016 - criado por Wilton Júnior
 *
 * Versão do Gerador de Código: 1.39.0
 *
 * Versão no SVN: $Id$
 */
try {
    require_once dirname(__FILE__) . '/../../SEI.php';

    session_start();

    //////////////////////////////////////////////////////////////////////////////
//    InfraDebug::getInstance()->setBolLigado(false);
//    InfraDebug::getInstance()->setBolDebugInfra(true);
//    InfraDebug::getInstance()->limpar();
    //////////////////////////////////////////////////////////////////////////////

    SessaoSEI::getInstance()->validarLink();

    PaginaSEI::getInstance()->verificarSelecao('md_cor_contrato_selecionar');

    SessaoSEI::getInstance()->validarPermissao($_GET['acao']);

    $arrComandos = array();
    $strTitulo = 'Novo Processamento';

    $arrLabelStatus = [
        MdCorRetornoArRN::$STA_RETORNO_AR_NAO_PROCESSADO => 'Não Processado',
        MdCorRetornoArRN::$STA_RETORNO_AR_AUTOMATICO => 'Identificado Automaticamente',
        MdCorRetornoArRN::$STA_RETORNO_AR_MANUAL => 'Indicado Manualmente',
        MdCorRetornoArRN::$STA_RETORNO_AR_JA_PROCESSADO => 'Retorno de AR já Processado Anteriormente',
    ];

    switch ($_GET['acao']) {
        case 'md_cor_retorno_ar_cadastrar':
            $arrComandos[] = '<button type="button" accesskey="C" name="btnCancelar" id="btnCancelar" value="Cancelar" onclick="location.href=\'' . SessaoSEI::getInstance()->assinarLink('controlador.php?acao=' . $_GET['acao_origem'] . '&acao_origem=' . $_GET['acao']) . '\';" class="infraButton"><span class="infraTeclaAtalho">C</span>ancelar</button>';
            break;
        case 'md_cor_retorno_ar_salvar':
            $mdCorRetornoArDocRN = new MdCorRetornoArDocRN();
            $mdCorRetornoArDocRN->cadastrarArs($_POST);
            $url = SessaoSEI::getInstance()->assinarLink('controlador.php?acao=md_cor_retorno_ar_listar');
            PaginaSEI::getInstance()->adicionarMensagem('Ars Processados com sucesso');
            header('Location: ' . $url);
            break;

        case 'md_cor_retorno_ar_consultar':
            $strTitulo = 'Consultar Processamento';
            $disabled = "disabled='disabled'";

            $arrComandos[] = '<button type="button" accesskey="F" name="btnCancelar" id="btnCancelar" value="Fechar" onclick="location.href=\'' . SessaoSEI::getInstance()->assinarLink('controlador.php?acao=' . $_GET['acao_origem'] . '&acao_origem=' . $_GET['acao'] . PaginaSEI::getInstance()->montarAncora($_GET['id_md_cor_retorno_ar'])) . '\';" class="infraButton">Fe<span class="infraTeclaAtalho">c</span>har</button>';
            $idMdCorRetornoAr = $_GET['id_md_cor_retorno_ar'];
            $mdCorRetornoArRN = new MdCorRetornoArRN();
            $mdCorRetornoArDTO = new MdCorRetornoArDTO();
            $mdCorRetornoArDTO->setNumIdMdCorRetornoAr($idMdCorRetornoAr);
            $mdCorRetornoArDTO->retStrNomeArquivoZip();

            $arrMdCorRetornoArRN = $mdCorRetornoArRN->consultar($mdCorRetornoArDTO);

            $arquivoLido = $arrMdCorRetornoArRN->getStrNomeArquivoZip();
            $posCochete = strrpos($arquivoLido, ']') + 2;
            $arquivoTratado = substr($arquivoLido, $posCochete);

            $MdCorRetornoArDocRN = new MdCorRetornoArDocRN();
            $MdCorRetornoArDocDTO = new MdCorRetornoArDocDTO();
            $MdCorRetornoArDocDTO->setNumIdMdCorRetornoAr($idMdCorRetornoAr);
            $MdCorRetornoArDocDTO->retNumIdDocumentoPrincipal();
            $MdCorRetornoArDocDTO->retStrNomeArquivoPdf();
            $MdCorRetornoArDocDTO->retStrProtocoloFormatadoDocumento();
            $MdCorRetornoArDocDTO->retStrCodigoRastreamento();
            $MdCorRetornoArDocDTO->retStrNumeroDocumento();
            $MdCorRetornoArDocDTO->retStrProtocoloFormatado();
            $MdCorRetornoArDocDTO->retNumIdProtocolo();
            $MdCorRetornoArDocDTO->retDtaDataAr();
            $MdCorRetornoArDocDTO->retDtaDataRetorno();
            $MdCorRetornoArDocDTO->retStrNomeSerie();
            $MdCorRetornoArDocDTO->retNumIdMdCorParamArInfrigencia();
            $MdCorRetornoArDocDTO->retNumIdStatusProcess();
            $MdCorRetornoArDocDTO->retNumIdDocumentoAr();

            $arrObjMdCorRetornoArDocRN = $MdCorRetornoArDocRN->listar($MdCorRetornoArDocDTO);

            $numRegistros = $MdCorRetornoArDocRN->contar($MdCorRetornoArDocDTO);
            $stAltera = true;

            $arrArquivoProcessado = [];
            foreach ($arrObjMdCorRetornoArDocRN as $chave => $dados) {
                $arrArquivoProcessado[$chave]['noArquivoPdf'] = $dados->getStrNomeArquivoPdf();
                $arrArquivoProcessado[$chave]['noArquivoPdf'] = $dados->getStrNomeArquivoPdf();
                $arrArquivoProcessado[$chave]['nuSei'] = $dados->getStrProtocoloFormatadoDocumento();
                $arrArquivoProcessado[$chave]['idDocumentoPrincipal'] = $dados->getNumIdDocumentoPrincipal();
                $arrArquivoProcessado[$chave]['idDocumentoAr'] = $dados->getNumIdDocumentoAr();
                $arrArquivoProcessado[$chave]['nuDocumento'] = $dados->getStrNumeroDocumento();
                $arrArquivoProcessado[$chave]['tipoDocumento'] = $dados->getStrTipoDocumento();
                if (is_null($dados->getStrNumeroDocumento())) {
                    $arrArquivoProcessado[$chave]['nuDocumento'] = verificarDocumentoExpedicao($dados->getNumIdDocumentoPrincipal());
                }

                $arrArquivoProcessado[$chave]['nuSerie'] = $dados->getStrNomeSerie();
                $arrArquivoProcessado[$chave]['nuProcesso'] = $dados->getStrProtocoloFormatado();
                $arrArquivoProcessado[$chave]['coRastreamanento'] = $dados->getStrCodigoRastreamento();
                $arrArquivoProcessado[$chave]['dtAr'] = $dados->getDtaDataAr();
                $arrArquivoProcessado[$chave]['dtRetorno'] = $dados->getDtaDataRetorno();
                $arrArquivoProcessado[$chave]['stAltera'] = true;
                $arrArquivoProcessado[$chave]['idMotivo'] = $dados->getNumIdMdCorParamArInfrigencia();
                $arrArquivoProcessado[$chave]['status'] = $dados->getNumIdStatusProcess();
            }

            require_once('md_cor_retorno_ar_cadastro_tabela.php');
            break;
        case 'md_cor_retorno_ar_alterar':
            $arrComandos = [];
            $arrComandos[] = '<button type="button" accesskey="S" name="sbmParametro" value="Salvar" onclick="enviarFormulario();" class="infraButton"><span class="infraTeclaAtalho">S</span>alvar</button>';
            $arrComandos[] = '<button type="button" accesskey="C" name="btnCancelar" id="btnCancelar" value="Cancelar" onclick="location.href=\'' . SessaoSEI::getInstance()->assinarLink('controlador.php?acao=' . $_GET['acao_origem'] . '&acao_origem=' . $_GET['acao'] . PaginaSEI::getInstance()->montarAncora($_GET['id_md_cor_retorno_ar'])) . '\';" class="infraButton"><span class="infraTeclaAtalho">C</span>ancelar</button>';
            $strTitulo = 'Alterar Processamento';
            $idMdCorRetornoAr = $_GET['id_md_cor_retorno_ar'];
            $mdCorRetornoArRN = new MdCorRetornoArRN();
            $mdCorRetornoArDTO = new MdCorRetornoArDTO();
            $mdCorRetornoArDTO->setNumIdMdCorRetornoAr($idMdCorRetornoAr);
            $mdCorRetornoArDTO->retStrNomeArquivoZip();

            $arrMdCorRetornoArRN = $mdCorRetornoArRN->consultar($mdCorRetornoArDTO);

            $arquivoLido = $arrMdCorRetornoArRN->getStrNomeArquivoZip();
            $posCochete = strrpos($arquivoLido, ']') + 2;
            $arquivoTratado = substr($arquivoLido, $posCochete);

            $MdCorRetornoArDocRN = new MdCorRetornoArDocRN();
            $MdCorRetornoArDocDTO = new MdCorRetornoArDocDTO();
            $MdCorRetornoArDocDTO->setNumIdMdCorRetornoAr($idMdCorRetornoAr);
            $MdCorRetornoArDocDTO->retNumIdDocumentoPrincipal();
            $MdCorRetornoArDocDTO->retStrNomeArquivoPdf();
            $MdCorRetornoArDocDTO->retStrProtocoloFormatadoDocumento();
            $MdCorRetornoArDocDTO->retStrCodigoRastreamento();
            $MdCorRetornoArDocDTO->retStrNumeroDocumento();
            $MdCorRetornoArDocDTO->retStrProtocoloFormatado();
            $MdCorRetornoArDocDTO->retDtaDataAr();
            $MdCorRetornoArDocDTO->retDtaDataRetorno();
            $MdCorRetornoArDocDTO->retStrNomeSerie();
            $MdCorRetornoArDocDTO->retNumIdMdCorParamArInfrigencia();
            $MdCorRetornoArDocDTO->retNumIdStatusProcess();

            $arrObjMdCorRetornoArDocRN = $MdCorRetornoArDocRN->listar($MdCorRetornoArDocDTO);

            $numRegistros = $MdCorRetornoArDocRN->contar($MdCorRetornoArDocDTO);
            $stAltera = true;

            $arrArquivoProcessado = [];
            foreach ($arrObjMdCorRetornoArDocRN as $chave => $dados) {
                $arrArquivoProcessado[$chave]['noArquivoPdf'] = $dados->getStrNomeArquivoPdf();
                $arrArquivoProcessado[$chave]['nuSei'] = $dados->getStrProtocoloFormatadoDocumento();
                $arrArquivoProcessado[$chave]['idDocumentoPrincipal'] = $dados->getNumIdDocumentoPrincipal();
                $arrArquivoProcessado[$chave]['nuDocumento'] = $dados->getStrNumeroDocumento();
                $arrArquivoProcessado[$chave]['tipoDocumento'] = $dados->getStrTipoDocumento();

                if (is_null($dados->getStrNumeroDocumento())) {
                    $arrArquivoProcessado[$chave]['nuDocumento'] = verificarDocumentoExpedicao($dados->getNumIdDocumentoPrincipal());
                }

                $arrArquivoProcessado[$chave]['nuSerie'] = $dados->getStrNomeSerie();
                $arrArquivoProcessado[$chave]['nuProcesso'] = $dados->getStrProtocoloFormatado();
                $arrArquivoProcessado[$chave]['coRastreamanento'] = $dados->getStrCodigoRastreamento();
                $arrArquivoProcessado[$chave]['dtAr'] = $dados->getDtaDataAr();
                $arrArquivoProcessado[$chave]['dtRetorno'] = $dados->getDtaDataRetorno();
                $arrArquivoProcessado[$chave]['stAltera'] = true;
                $arrArquivoProcessado[$chave]['idMotivo'] = $dados->getNumIdMdCorParamArInfrigencia();
                $arrArquivoProcessado[$chave]['status'] = $dados->getNumIdStatusProcess();
            }

            require_once('md_cor_retorno_ar_cadastro_tabela.php');

            break;


        case 'md_cor_retorno_ar_processar':
            $arrComandos[] = '<button type="button" accesskey="P" name="sbmParametro" value="Salvar" onclick="enviarFormulario();" class="infraButton"><span class="infraTeclaAtalho">P</span>rocessar</button>';
            $arrComandos[] = '<button type="button" accesskey="C" name="btnCancelar" id="btnCancelar" value="Cancelar" onclick="location.href=\'' . SessaoSEI::getInstance()->assinarLink('controlador.php?acao=md_cor_retorno_ar_listar&acao_origem=' . $_GET['acao']) . '\';" class="infraButton"><span class="infraTeclaAtalho">C</span>ancelar</button>';
            $MdCorRetornoArRN = new MdCorRetornoArRN();
            $arrArquivoProcessado = $MdCorRetornoArRN->processarArquivo($_POST);

            foreach ($arrArquivoProcessado as $k => $v) {
                if (is_null($v['nuDocumento'])) {
                    $v['nuDocumento'] = verificarDocumentoExpedicao($v['idDocumentoPrincipal']);
                }
                $arrArquivoProcessado[$k] = $v;
            }

            $arquivoLido = $_POST['hdnNomeArquivo'];
            $posCochete = strrpos($arquivoLido, ']') + 2;
            $arquivoTratado = substr($arquivoLido, $posCochete);

            $numRegistros = count($arrArquivoProcessado);

            require_once('md_cor_retorno_ar_cadastro_tabela.php');

            break;
        default:
            throw new InfraException("Ação '" . $_GET['acao'] . "' não reconhecida.");
    }


} catch (Exception $e) {
    PaginaSEI::getInstance()->processarExcecao($e);
}

function verificarDocumentoExpedicao($numIdDocumentoPrincipal)
{

    $objProtocoloDocPrincipalRN = new ProtocoloRN();
    $objProtocoloDocPrincipalDTO = new ProtocoloDTO();
    $objProtocoloDocPrincipalDTO->retTodos();
    $objProtocoloDocPrincipalDTO->retNumIdSerieDocumento();
    $objProtocoloDocPrincipalDTO->retStrStaDocumentoDocumento();

    $objProtocoloDocPrincipalDTO->setDblIdProtocolo($numIdDocumentoPrincipal);
    $objProtocoloDocPrincipalDTO = $objProtocoloDocPrincipalRN->consultarRN0186($objProtocoloDocPrincipalDTO);

    $objInfraParametro = new InfraParametro(BancoSEI::getInstance());
    $strValor = $objInfraParametro->getValor('MODULO_CORREIOS_ID_DOCUMENTO_EXPEDICAO',false);

    if (!empty($strValor) && !is_null($objProtocoloDocPrincipalDTO)) {
        $arrIdSerieDocumento = explode(',', $strValor);
        if (in_array($objProtocoloDocPrincipalDTO->getNumIdSerieDocumento(), $arrIdSerieDocumento) && $objProtocoloDocPrincipalDTO->getStrStaDocumentoDocumento() == 'X') {
            return ' ';
        }
    }
}

PaginaSEI::getInstance()->montarDocType();
PaginaSEI::getInstance()->abrirHtml();
PaginaSEI::getInstance()->abrirHead();
PaginaSEI::getInstance()->montarMeta();
PaginaSEI::getInstance()->montarTitle(PaginaSEI::getInstance()->getStrNomeSistema() . ' - ' . $strTitulo);
PaginaSEI::getInstance()->montarStyle();
PaginaSEI::getInstance()->abrirStyle();
include_once('md_cor_estilos_css.php');
PaginaSEI::getInstance()->fecharStyle();
PaginaSEI::getInstance()->montarJavaScript();
PaginaSEI::getInstance()->fecharHead();
PaginaSEI::getInstance()->abrirBody($strTitulo, 'onload="inicializar();"');
PaginaSEI::getInstance()->montarBarraComandosSuperior($arrComandos);

?>
<? PaginaSEI::getInstance()->abrirAreaDados(); ?>
    <div class="row">
        <div class="col-sm-12 col-md-12 col-lg-12 col-xl-12 text-justify">
            <fieldset class="infraFieldset form-control mb-3 py-3" style="height: auto">
                <legend class="infraLegend ">Requistos para Carregamento de Arquivo</legend>
                <ol class="m-0 pl-4">
                    <li class="requisito">O arquivo a ser carregado tem que ser do tipo ZIP.</li>
                    <li class="requisito">Dentro do ZIP somente deve constar arquivos PDFs, sendo um PDF por AR (Aviso
                        de Recebimento) limitado a 50 PDFs.
                    </li>
                    <li class="requisito">Somente os ARs impressos a partir da expedição do próprio Módulo de Correios
                        do SEI terão o QR Code e processo
                    </li>
                    <li class="requisito">Identificados automaticamente para inclusão do PDF do AR correspondente no
                        processo.
                    </li>
                    <li class="requisito">Os Níveis de Acesso dos ARs identificados automaticamente serão os
                        correspondentes ao documento principal que originou a expedição no momento do presente
                        processamento
                    </li>
                    <li class="requisito">Para os ARs que não forem reconhecidos automaticamente a partir de QR Code de
                        AR impresso pelo Módulo, na tabela de processamento serão abertos campos para indicação do
                        Número SEI do documento principal que originou a expedição.
                    </li>
                    <li class="requisito">Para todos os ARs processados com identificação automatica ou com indicação
                        manual do documento principal que originou a expedição deverá ser informada a Data de
                        Recebimento.
                    </li>
                    <li class="requisito">O processamento do cada arquivo PDF será realizado com as informações da
                        primeira página desse arquivo.
                    </li>
                    <li class="requisito">O arquivo PDF dentro do ZIP deverá ter no máximo 3MB.</li>
                    <li class="requisito">O AR deverá ser escaneado em preto e branco e com 300 DPI de resolução.</li>
                </ol>
            </fieldset>
            <br/>
        </div>
    </div>

<? PaginaSEI::getInstance()->abrirAreaDados(); ?>
    <form id="frmMdCorRetornoUpload" method="post" enctype="multipart/form-data">
        <?php if (empty($arquivoLido)) { ?>
            <div class="row">
                <div class="col-10">
                    <label class="infraLabelObrigatorio">Selecionar Arquivo ZIP:</label>
                    <input type="file"
                        id="fileArquivo"
                        name="fileArquivo"
                        class="form-control-file"
                        onchange="adicionarDocumento()"
                        tabindex="<?= PaginaSEI::getInstance()->getProxTabDados() ?>"/>
                </div>
            </div>
        <?php } else { ?>
            <label class="infraLabelObrigatorio">Arquivo ZIP para Processamento:</label>
            <span style="font-size: 1.1em"><?php echo $arquivoTratado ?></span>
        <?php } ?>
    </form>
    <form id="frmMdCorRetornoArCadastro" method="post"
          action="<?php echo SessaoSEI::getInstance()->assinarLink('controlador.php?acao=md_cor_retorno_ar_processar'); ?>">
        <input type="hidden" name="hdnNomeArquivo" id="hdnNomeArquivo">
    </form>

<?php if (!is_null($arrArquivoProcessado)) { ?>
    <form id="frmSalvarRetorno" method="post"
          action="<?php echo SessaoSEI::getInstance()->assinarLink('controlador.php?acao=md_cor_retorno_ar_salvar'); ?>">
        <input type="hidden" name="hdnArquivoZip" value="<?php echo $arquivoLido ?>"/>
        <input type="hidden" name="hdnArquivoAlteracao" value="<?php echo $idMdCorRetornoAr ?>"/>
        <div class="row mt-3">
            <div class="col-12">
                <div class="table-responsive">
                    <?php PaginaSEI::getInstance()->montarAreaTabela($strResultado, $numRegistros) ?>
                </div>
            </div>
        </div>
    </form>
<?php } ?>

<?
// PaginaSEI::getInstance()->montarBarraComandosInferior($arrComandos, true);
PaginaSEI::getInstance()->fecharAreaDados();
require_once('md_cor_retorno_ar_cadastro_js.php');
PaginaSEI::getInstance()->fecharBody();
PaginaSEI::getInstance()->fecharHtml();
?>