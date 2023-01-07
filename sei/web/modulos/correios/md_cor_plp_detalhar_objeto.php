<?php
/**
 * ANATEL
 *
 * 21/10/2017 - criado por Ellyson de Jesus Silva
 *
 */
try {
    require_once dirname(__FILE__) . '/../../SEI.php';

    session_start();

    SessaoSEI::getInstance()->validarLink();

    SessaoSEI::getInstance()->validarPermissao($_GET['acao']);

    $objMdCorExpedicaoSolicitadaRN = new MdCorExpedicaoSolicitadaRN();

    switch ($_GET['acao']) {
        case 'md_cor_plp_detalhar_objeto':
            $strTitulo = 'Detalhar objeto';
            break;

        case 'md_cor_plp_expedir_objeto':
            $strTitulo = 'Expedir Objeto';
            try {
                //Alterar o status do objeto para acessado
                $selIdMdCorExpedicaoSolicitada = $_GET['id_md_expedicao_solicitada'];

                $mdCorExpedicaoSolicitadaRN = new MdCorExpedicaoSolicitadaRN();
                $mdCorExpedicaoSolicitadaDTO = new MdCorExpedicaoSolicitadaDTO();
                $mdCorExpedicaoSolicitadaDTO->retNumIdMdCorExpedicaoSolicitada();
                $mdCorExpedicaoSolicitadaDTO->retDblIdProtocolo();
                $mdCorExpedicaoSolicitadaDTO->setNumIdMdCorExpedicaoSolicitada($selIdMdCorExpedicaoSolicitada);
                $mdCorExpedicaoSolicitadaDTO = $mdCorExpedicaoSolicitadaRN->consultar($mdCorExpedicaoSolicitadaDTO);

                $mdCorExpedicaoSolicitadaDTO->setStrSinObjetoAcessado('S');
                $mdCorExpedicaoSolicitadaRN->alterar($mdCorExpedicaoSolicitadaDTO);
            } catch (Exception $e) {
                PaginaSEI::getInstance()->processarExcecao($e);
            }

            break;

        case 'md_cor_plp_gerar_zip':
            try {

                $arrIdSel = array_unique(PaginaSEI::getInstance()->getArrStrItensSelecionados());
                $arrIdDocumento = empty($arrIdSel)? [$_GET['id_documento']] : $arrIdSel;

                $objMdCorExpedicaoFormatoDTO = new MdCorExpedicaoFormatoDTO();
                $objMdCorExpedicaoFormatoDTO->retNumIdMdCorExpedicaoFormato();
                $objMdCorExpedicaoFormatoDTO->retDblIdProtocolo();
                $objMdCorExpedicaoFormatoDTO->retStrImpressao();
                $objMdCorExpedicaoFormatoDTO->retStrFormaExpedicao();
                $objMdCorExpedicaoFormatoDTO->retNumIdMdCorExpedicaoSolicitada();

                $objMdCorExpedicaoFormatoDTO->setDblIdProtocolo($arrIdDocumento, InfraDTO::$OPER_IN);

                $objMdCorExpedicaoFormatoRN = new MdCorExpedicaoFormatoRN();
                $objAnexoDTO = $objMdCorExpedicaoFormatoRN->gerarZip($objMdCorExpedicaoFormatoDTO);
                $linkRedirecionar = SessaoSEI::getInstance()->assinarLink('controlador.php?acao=exibir_arquivo&nome_arquivo=' . $objAnexoDTO->getStrNome() . '&nome_download=SEI-ArquivosLote.zip&acao_origem=' . $_GET['acao'] . '&acao_retorno=' . $_GET['acao']);
                header('Location: ' . $linkRedirecionar);
            } catch (Exception $e) {
                PaginaSEI::getInstance()->processarExcecao($e);
            }
            break;
        case 'md_cor_plp_pdf_arquivo_lote_objeto' :

            $gIdDocumento = $_GET['id_documento'];

            if (empty($gIdDocumento)) {
                $selIdDocumento = PaginaSEI::getInstance()->getArrStrItensSelecionados();
            } else {
                $selIdDocumento = [$gIdDocumento];
            }
            $selIdDocumento = array_unique($selIdDocumento);

            $mdCorExpedicaoSolicitadaRN = new MdCorExpedicaoSolicitadaRN();
            $objMdCorExpedicaoFormatoRN = new MdCorExpedicaoFormatoRN();

            $mdCorExpedicaoSolicitadaDTO = new MdCorExpedicaoSolicitadaDTO();
            $idDocumento = array();

            foreach ($selIdDocumento as $idDocumentoSelecionado) {

                $objMdCorExpedicaoFormatoDTO = new MdCorExpedicaoFormatoDTO();
                $objMdCorExpedicaoFormatoDTO->retDblIdProtocolo();
                $objMdCorExpedicaoFormatoDTO->retStrImpressao();
                $objMdCorExpedicaoFormatoDTO->retDblIdDocumentoPrincipal();
                $objMdCorExpedicaoFormatoDTO->retNumIdMdCorExpedicaoSolicitada();

                $objMdCorExpedicaoFormatoDTO->setDblIdProtocolo($idDocumentoSelecionado);
                $objMdCorExpedicaoFormatoDTO->setStrFormaExpedicao('I');
                $objMdCorExpedicaoFormatoDTO->setOrd('IdMdCorExpedicaoFormato', InfraDTO::$TIPO_ORDENACAO_DESC);

                $arrObjMdCorExpedicaoFormatoDTO = $objMdCorExpedicaoFormatoRN->listar($objMdCorExpedicaoFormatoDTO);

                foreach ($arrObjMdCorExpedicaoFormatoDTO as $dadosFormato) {

                    $idDocumentoPrincipal = $dadosFormato->getDblIdDocumentoPrincipal();

                    $mdCorExpedicaoSolicitadaDTO->setNumIdMdCorExpedicaoSolicitada($dadosFormato->getNumIdMdCorExpedicaoSolicitada());
                    $mdCorExpedicaoSolicitadaDTO->setDblIdDocumentoPrincipal($idDocumentoPrincipal);

                    $objDocumentoDTO = new DocumentoDTO();
                    $objDocumentoDTO->setDblIdDocumento($dadosFormato->getDblIdProtocolo());

                    if ($dadosFormato->getStrImpressao() == 'P') {
                        $objDocumentoDTO->setStrSinPdfEscalaCinza('S');
                    } else {
                        $objDocumentoDTO->setStrSinPdfEscalaCinza('N');
                    }

                    if ($idDocumentoPrincipal == $dadosFormato->getDblIdProtocolo()) {
                        array_unshift($idDocumento, $objDocumentoDTO);
                    } else {
                        $idDocumento[] = $objDocumentoDTO;
                    }

                    $mdCorExpedicaoSolicitadaDTO->setStrSinObjetoAcessado('S');
                    $mdCorExpedicaoSolicitadaRN->alterar($mdCorExpedicaoSolicitadaDTO);
                }
            }

            $objAnexoDTO = new AnexoDTO();
            $objDocumentoRN = new DocumentoRN();

            $objAnexoDTO = $objDocumentoRN->gerarPdf($idDocumento);
            $linkRedirecionar = SessaoSEI::getInstance()->assinarLink('controlador.php?acao=md_cor_exibir_pdf&nome_arquivo=' . $objAnexoDTO->getStrNome() . '&nome_download=SEI-PLP-ArquivolLote.pdf');
            header('Location: ' . $linkRedirecionar);
            break;

        default:
            throw new InfraException("Ação '" . $_GET['acao'] . "' não reconhecida.");
    }

    $strUrlZerarZip = SessaoSEI::getInstance()->assinarLink('controlador.php?acao=md_cor_plp_gerar_zip&acao_origem=' . $_GET['acao'] . '&id_md_expedicao_solicitada=' . $_GET['id_md_expedicao_solicitada']);

    $strLinkAjaxValidarDocumentoAPI = SessaoSEI::getInstance()->assinarLink('controlador_ajax.php?acao_ajax=validar_documento_api');
    $strLinkAjaxValidarDocumentoIdProtocoloFormatadoAPI = SessaoSEI::getInstance()->assinarLink('controlador_ajax.php?acao_ajax=validar_documento_id_protocoloFormatado_api');

    $objMdCorExpedicaoSolicitadaDTO = new MdCorExpedicaoSolicitadaDTO();
    $objMdCorExpedicaoSolicitadaDTO->retNumIdMdCorExpedicaoSolicitada();
    $objMdCorExpedicaoSolicitadaDTO->retNumIdMdCorPlp();
    $objMdCorExpedicaoSolicitadaDTO->retDblIdProtocolo();
    $objMdCorExpedicaoSolicitadaDTO->retStrProtocoloFormatado();
    $objMdCorExpedicaoSolicitadaDTO->setNumIdMdCorExpedicaoSolicitada($_GET['id_md_expedicao_solicitada']);

    $objMdCorExpedicaoSolicitadaDTO = $objMdCorExpedicaoSolicitadaRN->consultar($objMdCorExpedicaoSolicitadaDTO);

    /**
     * Montar array de comandos superior
     */
    $arrComandos = array();
    $arrComandos[] = '<button type="button" id="btnGerarZip" class="infraButton" accesskey="g" onclick="gerarZip()">
                        <span class="infraTeclaAtalho">G</span>erar ZIP 
                      </button>';

    $arrComandos[] = '<button type="button" id="btnImprimirArquivo" class="infraButton" onclick="gerarArquivoLote()" accesskey="i">
                                   <span
                        class="infraTeclaAtalho">I</span>mprimir Documentos
                              </button>';
    $arrComandos[] = '<button type="button" accesskey="c" id="btnFechar" onclick="location.href=\'' . SessaoSEI::getInstance()->assinarLink('controlador.php?acao='.$_GET['acao_origem'].'&acao_origem=' . $_GET['acao'].'&id_md_cor_plp=' . $_GET['id_md_cor_plp'] . PaginaSEI::getInstance()->montarAncora($_GET['id_md_expedicao_solicitada'])) . '\';" class="infraButton">
                                    Fe<span class="infraTeclaAtalho">c</span>har
                              </button>';

    //recuperando a lista de expedição formato
    $objMdCorExpedicaoFormatoDTO = new MdCorExpedicaoFormatoDTO();
    $objMdCorExpedicaoFormatoDTO->retTodos(true);

    $objMdCorExpedicaoFormatoDTO->setNumIdMdCorExpedicaoSolicitada($_GET['id_md_expedicao_solicitada']);

    PaginaSEI::getInstance()->prepararOrdenacao($objMdCorExpedicaoFormatoDTO, 'IdMdCorExpedicaoFormato', InfraDTO::$TIPO_ORDENACAO_DESC);
    PaginaSEI::getInstance()->prepararPaginacao($objMdCorExpedicaoFormatoDTO);

    PaginaSEI::getInstance()->processarPaginacao($objMdCorExpedicaoFormatoDTO);

    $objMdCorExpedicaoFormatoRN = new MdCorExpedicaoFormatoRN();
    $arrObjMdCorExpedicaoFormatoDTO = $objMdCorExpedicaoFormatoRN->listar($objMdCorExpedicaoFormatoDTO);

    $numRegistros = count($arrObjMdCorExpedicaoFormatoDTO);

    if ($numRegistros > 0) {

        $strResultado = '';

        $strSumarioTabela = 'Tabela de objetos';
        $strCaptionTabela = 'objetos';

        $strResultado .= '<table width="99%" class="infraTable" summary="' . $strSumarioTabela . '">' . "\n";
        $strResultado .= '<caption class="infraCaption">' . PaginaSEI::getInstance()->gerarCaptionTabela($strCaptionTabela, $numRegistros) . '</caption>';
        $strResultado .= '<tr>';
        $strResultado .= '<th class="infraTh" width="1%">' . PaginaSEI::getInstance()->getThCheck() . '</th>' . "\n";
        $strResultado .= '<th class="infraTh" width="20%">Documento</th>' . "\n";
        $strResultado .= '<th class="infraTh" width="20%">Formato de Expedição</th>' . "\n";
        $strResultado .= '<th class="infraTh" width="5%">Impressão</th>' . "\n";
        $strResultado .= '<th class="infraTh" width="30%">Justificativa</th>' . "\n";
        $strResultado .= '<th class="infraTh" width="5%">Ações</th>' . "\n";

        $strResultado .= '</tr>' . "\n";
        $strCssTr = '';

        for ($i = 0; $i < $numRegistros; $i++) {

            $strCssTr = ($strCssTr == '<tr class="infraTrClara">') ? '<tr class="infraTrEscura">' : '<tr class="infraTrClara">';

            $strResultado .= $strCssTr;

            if ($arrObjMdCorExpedicaoFormatoDTO[$i]->getStrFormaExpedicao() == MdCorExpedicaoFormatoRN::$TP_FORMATO_IMPRESSO) {
                if ($arrObjMdCorExpedicaoFormatoDTO[$i]->getStrImpressao() == MdCorExpedicaoFormatoRN::$TP_IMPRESSAO_PRETO_BRANCO) {
                    $imgImpressao = 'impressao_preto_branco.svg';
                    $title = 'Preto e Branco';
                } else {
                    $imgImpressao = 'impressao_colorida.svg';
                    $title = 'Colorido';
                }
            } else {
                $imgImpressao = 'gravacao_media.svg';
                $title = 'Gravar Mídia';
            }

            $bolDocumentoRestrito = !$objMdCorExpedicaoSolicitadaRN->validarAcessoRestrito( $arrObjMdCorExpedicaoFormatoDTO[$i]->getDblIdDocumento() ) ? 'null' : $objMdCorExpedicaoSolicitadaRN->validarAcessoRestrito( $arrObjMdCorExpedicaoFormatoDTO[$i]->getDblIdDocumento() );

            $strResultado .= '<td valign="top">' . PaginaSEI::getInstance()->getTrCheck($i, $arrObjMdCorExpedicaoFormatoDTO[$i]->getDblIdProtocolo(), $arrObjMdCorExpedicaoFormatoDTO[$i]->getDblIdProtocolo(), 'N', 'Infra',  "idProtocoloFormatado='{$arrObjMdCorExpedicaoFormatoDTO[$i]->getStrProtocoloFormatado()}'") . '</td>';
            $strUrlDocumento = SessaoSEI::getInstance()->assinarLink('controlador.php?acao=procedimento_trabalhar&id_documento=' . $arrObjMdCorExpedicaoFormatoDTO[$i]->getDblIdDocumentoPrincipal());

            if($arrObjMdCorExpedicaoFormatoDTO[$i]->getStrNomeSerie()){
                $strResultado .= '<td>' . PaginaSEI::tratarHTML($arrObjMdCorExpedicaoFormatoDTO[$i]->getStrNomeSerie() . ' ' . $arrObjMdCorExpedicaoFormatoDTO[$i]->getStrNumeroDocumento() ). ' <a class="protocoloNormal" style="font-size: 1.0em !important; font-size:1em" href="'.$strUrlDocumento.'" target="_blank">(' . $arrObjMdCorExpedicaoFormatoDTO[$i]->getStrProtocoloFormatado() . ')</a> - ' . PaginaSEI::tratarHTML( $arrObjMdCorExpedicaoFormatoDTO[$i]->getStrTipoDocumento() ) . '</td>';
            }else{
                $strResultado .= '<td>' . $arrObjMdCorExpedicaoFormatoDTO[$i]->getStrProtocoloFormatado() . ' - ' . PaginaSEI::tratarHTML( $arrObjMdCorExpedicaoFormatoDTO[$i]->getStrTipoDocumento() ) . '</td>';
            }

            $strResultado .= '<td style="word-break:break-all">' . PaginaSEI::tratarHTML($arrObjMdCorExpedicaoFormatoDTO[$i]->getTextoFormato()) . '</td>';
            $strResultado .= '<td style="word-break:break-all;text-align:center;"><div style="float:left;"><img src="modulos/correios/imagens/svg/' . $imgImpressao . '" title="' . $title . '" alt="' . $title . '" class="infraImgAcoes" style="width: 24px; height: 24px"/></div></td>';
            $strResultado .= '<td style="word-break:break-all">' . PaginaSEI::tratarHTML($arrObjMdCorExpedicaoFormatoDTO[$i]->getStrJustificativa()) . '</td>';
            $strResultado .= '<td align="center">';

            if ($arrObjMdCorExpedicaoFormatoDTO[$i]->getStrFormaExpedicao() == MdCorExpedicaoFormatoRN::$TP_FORMATO_IMPRESSO) {
                $strUrl = SessaoSEI::getInstance()->assinarLink('controlador.php?acao=md_cor_plp_pdf_arquivo_lote_objeto&acao_origem=' . $_GET['acao'] . '&id_documento=' . $arrObjMdCorExpedicaoFormatoDTO[$i]->getDblIdDocumento());

                $strResultado .= '<a onclick="javascript:validarAcesso( '.$bolDocumentoRestrito.', '."'$strUrl'".' )" tabindex="' . PaginaSEI::getInstance()->getProxTabTabela() . '">
                                    <img   src="modulos/correios/imagens/svg/impressao.svg" title="Impressão de documento" alt="Impressão de documento" class="infraImgAcoes" />
                                  </a>&nbsp;';
            } else {
                $verificaProtocolo = strpos($arrObjMdCorExpedicaoFormatoDTO[$i]->getStrProtocoloFormatado(), '.');
                if($verificaProtocolo === false){
                    $strUrl = SessaoSEI::getInstance()->assinarLink('controlador.php?acao=md_cor_plp_gerar_zip&acao_origem=' . $_GET['acao'] . '&id_documento=' . $arrObjMdCorExpedicaoFormatoDTO[$i]->getDblIdDocumento());
                    $strResultado .= '<a  onclick="javascript:validarAcesso( '.$bolDocumentoRestrito.', '."'$strUrl'".' )">

                                      <img src="modulos/correios/imagens/svg/download_arquivo_media.svg" title="download do arquivo para gravação em mídia" alt="Detalhar Objeto" class="infraImgAcoes" style="width: 24px; height: 24px" />
                                  </a>';
                    $strResultado .= '&nbsp;';
                }else {
                    $strResultado .= '<a class="protocoloNormal" href="' . $strUrlDocumento . '" target="_blank">
                                      <img style="margin-right:8px" src="modulos/correios/imagens/svg/download_arquivo_media.svg" title="1 - download do arquivo para gravao em mdia" alt="Detalhar Objeto" class="infraImg"  style="width: 24px; height: 24px"/>
                                  </a>';
                }
            }
            $strResultado .= '</td></tr>' . "\n";
        }
        $strResultado .= '</table>';
    }

    $strComboStatus = MdCorPlpINT::montarSelectStaPlp('null', '&nbsp;', $_POST['txtStatus']);
    $strComboServicoPostal = MdCorPlpINT::montarSelectServicoPostal('null', '&nbsp;', $_POST['selServicoPostal']);
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

.infraImgAcoes{width:24px;}

#lblCodigoPlp {position:absolute;left:0px;top:0%;width:230px;}
#txtCodigoPlp {position:absolute;left:0px;top:40%;width:230px;}

#lblServicoPostal {position:absolute;left:250px;top:0%;width:230px;}
#selServicoPostal {position:absolute;left:250px;top:40%;width:230px;}

#lblCodRastreamento {position:absolute;left:500px;top:0%;width:230px;}
#txtCodRastreamento {position:absolute;left:500px;top:40%;width:230px;}

#lblStatus {position:absolute;left:750px;top:0%;width:230px;}
#txtStatus {position:absolute;left:750px;top:40%;width:230px;}

<?
PaginaSEI::getInstance()->fecharStyle();
PaginaSEI::getInstance()->montarJavaScript();
PaginaSEI::getInstance()->abrirJavaScript();
?>

function inicializar(){

infraEfeitoTabelas();

}

function validarAcesso( bolDocumentoRestrito, strUrl ){

    if( bolDocumentoRestrito != 1){
        alert('Esta Unidade Expedidora não possui acesso ao documento para poder imprimir.');
        return false;
    }

    window.open( strUrl, '_blank' );
}

function pesquisar(){
    document.getElementById('frmDetalharObjetoLista').submit();
}

function gerarZip(id){

    if(typeof id !== 'undefined'){
        document.getElementById('hdnInfraItensSelecionados').value = id;
    }

    if(document.getElementById('hdnInfraItensSelecionados').value == ''){
        alert('Selecione ao menos um objeto para gerar o ZIP');
        return false;
    }

    var isRestrito = getAjaxValidarDocumentoAPI();

    if(isRestrito === 'false'){
        alert('Esta Unidade Expedidora não possui acesso ao documento para poder imprimir.');
        return false;
    }

    document.getElementById('frmDetalharObjetoLista').target = '_blank';
    document.getElementById('frmDetalharObjetoLista').action = '<?= $strUrlZerarZip ?>';
    document.getElementById('frmDetalharObjetoLista').submit();
}

function getAjaxValidarDocumentoAPI() {

    var arrIdSocitacao = new Array();
    $('input:checked').each(function () {
        arrIdSocitacao.push($(this).val());
    });

    $.ajax({
        type: "POST",
        url: "<?= $strLinkAjaxValidarDocumentoAPI?>",
        async: false,
        cache: false,
        dataType: "xml",
        data: { arrIdSocitacao: arrIdSocitacao },

        success: function (r) {
            isRestrito = $(r).find('isRestrito').text();
        },
        error: function (msgError) {
            msgCommit = "Erro ao processar o XML do SEI: " + msgError.responseText;
            alert(msgCommit);
            isRestrito =  false;
        }
    });

    return isRestrito;
}


function getAjaxValidarDocumentoProtocoloFormatadoAPI() {

    var arrIdProtocoloFormatado = new Array();
    var isRestrito;

    $('input:checked').each(function () {
        arrIdProtocoloFormatado.push($(this).attr('idProtocoloFormatado'));
    });

    $.ajax({
    type: "POST",
    url: "<?= $strLinkAjaxValidarDocumentoIdProtocoloFormatadoAPI?>",
    async: false,
    cache: false,
    dataType: "xml",
    data: { arrIdProtocoloFormatado: arrIdProtocoloFormatado },

    success: function (r) {
        isRestrito = $(r).find('isRestrito').text();
    },
    error: function (msgError) {
            msgCommit = "Erro ao processar o XML do SEI: " + msgError.responseText;
            alert(msgCommit);
            isRestrito =  false;
        }
    });

    return isRestrito;
}


function gerarArquivoLote(){

    if(document.getElementById('hdnInfraItensSelecionados').value == ''){
        alert('Selecione ao menos um objeto para imprimir em lote');
        return false;
    }

    var isDocumentoRestrito = getAjaxValidarDocumentoProtocoloFormatadoAPI();

    if(isDocumentoRestrito === 'false'){
        alert('Esta Unidade Expedidora não possui acesso ao documento para poder imprimir.');
        return false;
    }

    document.getElementById('frmDetalharObjetoLista').target = '_blank';
    document.getElementById('frmDetalharObjetoLista').action = '<?php echo SessaoSEI::getInstance()->assinarLink('controlador.php?acao=md_cor_plp_pdf_arquivo_lote_objeto&acao_origem=' . $_GET['acao'] . '&id_md_expedicao_solicitada=' . $_GET['id_md_expedicao_solicitada']) ?>';
    document.getElementById('frmDetalharObjetoLista').submit();
}

<?
PaginaSEI::getInstance()->fecharJavaScript();
PaginaSEI::getInstance()->fecharHead();
PaginaSEI::getInstance()->abrirBody($strTitulo, 'onload="inicializar();"');
?>
<form id="frmDetalharObjetoLista" method="post"
      action="<?= PaginaSEI::getInstance()->formatarXHTML(SessaoSEI::getInstance()->assinarLink('controlador.php?acao=' . $_GET['acao'] . '&acao_origem=' . $_GET['acao'])) ?>">
    <?
    PaginaSEI::getInstance()->montarBarraComandosSuperior($arrComandos);
    ?>

    <input type="submit" style="visibility: hidden"/>

    <?
    PaginaSEI::getInstance()->montarAreaTabela($strResultado, $numRegistros);
    PaginaSEI::getInstance()->montarBarraComandosInferior($arrComandos);
    ?>
</form>
<?
PaginaSEI::getInstance()->fecharBody();
PaginaSEI::getInstance()->fecharHtml();
?>
