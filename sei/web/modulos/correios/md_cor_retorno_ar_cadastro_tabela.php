<?php
$strResultado .= '<table class="infraTable table" summary="Serviços">';
$strResultado .= '<thead>';
$strResultado .= '<tr style="height: 25px;">';
$strResultado .= '<th class="infraTh"></th>';
$strResultado .= '<th class="infraTh" style="min-width:130px;">Nome do Arquivo</th>';
$strResultado .= '<th class="infraTh" style="min-width:110px;">Número SEI';
$strResultado .= '<img id="ancAjuda" src="' . PaginaSEI::getInstance()->getDiretorioImagensGlobal() . '/ajuda.gif" class="infraImg"' . PaginaSEI::montarTitleTooltip('Número SEI do Documento Principal referente a expedição atrelada ao AR identificado. Para ARs identificados no processamento, este campo é recuperado a partir do Código de rastreamento e não pode ser alterado. Para ARs onde o documento não foi identificado, este deverá ser informado.', 'Ajuda') . '/>';
$strResultado .= '</th>';
$strResultado .= '<th class="infraTh" style="min-width:100px;">Documento';
$strResultado .= '<img id="ancAjuda" src="' . PaginaSEI::getInstance()->getDiretorioImagensGlobal() . '/ajuda.gif" class="infraImg"' . PaginaSEI::montarTitleTooltip('Documento referente ao Número SEI recuperado/ informado.', 'Ajuda') . '/>';
$strResultado .= '</th>';
$strResultado .= '<th class="infraTh" style="min-width:118px;">Rastreamento';
$strResultado .= '<img id="ancAjuda" src="' . PaginaSEI::getInstance()->getDiretorioImagensGlobal() . '/ajuda.gif" class="infraImg"' . PaginaSEI::montarTitleTooltip('Código do Rastreio do Documento', 'Ajuda') . '/>';
$strResultado .= '</th>';
$strResultado .= '<th class="infraTh">Data do AR';
$strResultado .= '<img id="ancAjuda" src="' . PaginaSEI::getInstance()->getDiretorioImagensGlobal() . '/ajuda.gif" class="infraImg"' . PaginaSEI::montarTitleTooltip('A data de Recebimento Efetivo pelo Destinatário ou da Última Tentativa de Entrega pelos Correios.', 'Ajuda') . '/>';
$strResultado .= '</th>';
$strResultado .= '<th class="infraTh">Data de Retorno';
$strResultado .= '<img id="ancAjuda" src="' . PaginaSEI::getInstance()->getDiretorioImagensGlobal() . '/ajuda.gif" class="infraImg"' . PaginaSEI::montarTitleTooltip('Data da Entrega Efetiva do AR pelos Correios ao Órgão.', 'Ajuda') . '/>';
$strResultado .= '</th>';
$strResultado .= '<th class="infraTh">Objeto Devolvido</th>';
$strResultado .= '<th class="infraTh" style="min-width:100px;">Status</th>';
$strResultado .= '</tr>';
$strResultado .= '</thead>';
$strResultado .= '<tbody>';

//Linhas
$strCssTr = '<tr class="infraTrEscura">';
if ($_GET['acao'] == 'md_cor_retorno_ar_alterar') {
    $checkbox = 'disabled="disabled"';
} else {
    $checkbox = '';
}


foreach ($arrArquivoProcessado as $chave => $arquivoProcessado) {
    $numChave = (int)$chave;
    $numLinha = $numChave + 1;

    $strNomeArquivoPdf = isset($arquivoProcessado['noArquivoPdf']) ? (string)$arquivoProcessado['noArquivoPdf'] : '';
    $strNomeArquivoPdfExibicao = PaginaSEI::tratarHTML(utf8_decode($strNomeArquivoPdf));
    $strNomeArquivoPdfAttr = htmlspecialchars($strNomeArquivoPdf, ENT_QUOTES, 'ISO-8859-1');

    $strNuSei                   = isset($arquivoProcessado['nuSei']) ? (string)$arquivoProcessado['nuSei'] : '';
    $strIdDocumentoPrincipal    = isset($arquivoProcessado['idDocumentoPrincipal']) ? (string)$arquivoProcessado['idDocumentoPrincipal'] : '';
    $strNuProcesso              = isset($arquivoProcessado['nuProcesso']) ? (string)$arquivoProcessado['nuProcesso'] : '';
    $strNuSerieHtml             = isset($arquivoProcessado['nuSerie']) ? (string)$arquivoProcessado['nuSerie'] : '';
    $strNuDocumento             = isset($arquivoProcessado['nuDocumento']) ? (string)$arquivoProcessado['nuDocumento'] : '';
    $strTipoDocumento             = isset($arquivoProcessado['tipoDocumento']) ? (string)$arquivoProcessado['tipoDocumento'] : '';
        
    $strNuSeiHtml = PaginaSEI::tratarHTML($strNuSei);
    $strNuSeiAttr = htmlspecialchars($strNuSei, ENT_QUOTES, 'ISO-8859-1');
    $strNuSerieHtml = PaginaSEI::tratarHTML($strNuSerie);
    $strNuDocumentoHtml = PaginaSEI::tratarHTML($strNuDocumento);
    $strTipoDocumentoHtml = PaginaSEI::tratarHTML($strTipoDocumento);
    $strNuProcessoAttr = htmlspecialchars($strNuProcesso, ENT_QUOTES, 'ISO-8859-1');
    $strIdDocumentoPrincipalAttr = htmlspecialchars($strIdDocumentoPrincipal, ENT_QUOTES, 'ISO-8859-1');

    $strCodigoRastreamento = isset($arquivoProcessado['coRastreamanento']) ? (string)$arquivoProcessado['coRastreamanento'] : '';
    $strCodigoRastreamentoHtml = PaginaSEI::tratarHTML($strCodigoRastreamento);
    $strCodigoRastreamentoAttr = htmlspecialchars($strCodigoRastreamento, ENT_QUOTES, 'ISO-8859-1');

    $strStatusProcessamento = isset($arquivoProcessado['status']) ? (string)$arquivoProcessado['status'] : '';
    $strStatusProcessamentoAttr = htmlspecialchars($strStatusProcessamento, ENT_QUOTES, 'ISO-8859-1');

    $strCssTr = $strCssTr == '<tr class="infraTrClara"' ? '<tr class="infraTrEscura"' : '<tr class="infraTrClara"';
    $strResultado .= $strCssTr . ' id="linha_' . $numChave . '">';

    $strResultado .= '<td>';
    $strResultado .= $numLinha;
    $strResultado .= '</td>';

    $strResultado .= '<td align="center">';
    $strNaoProcessado = '';
    if ($arquivoProcessado['status'] == MdCorRetornoArRN::$STA_RETORNO_AR_NAO_PROCESSADO && $_GET['acao'] == 'md_cor_retorno_ar_alterar') {
        $strNaoProcessado = 'disabled="disabled"';
    }

    $strResultado .= '<input type="hidden" name="no_arquivo[' . $numChave . ']" value="' . $strNomeArquivoPdfAttr . '"/>';
    $strUrlDocumentoAr = '';
    if ($_GET['acao'] == 'md_cor_retorno_ar_processar') {
        $diretorio = substr($arquivoLido, 0, -4);
        $strUrlDocumentoAr = SessaoSEI::getInstance()->assinarLink(
            'controlador.php?acao=md_cor_retorno_ar_arquivo_mostrar&diretorio=' . rawurlencode($diretorio) . '&documento=' . rawurlencode($strNomeArquivoPdf)
        );
    }

    $url = !empty($strUrlDocumentoAr) ? 'href="' . htmlspecialchars($strUrlDocumentoAr, ENT_QUOTES, 'ISO-8859-1') . '"' : null;

    $documentoAr = '<a ' . $url . ' class="protocoloNormal" style="font-size: 1.0em !important; font-size:1em" target="_blank">' . $strNomeArquivoPdfExibicao . '</a>';
    $strResultado .= $documentoAr;
    $strResultado .= '</td>';

    $strResultado .= '<td align="center">';
    $documento = '<input ' . $disabled . ' onkeypress="return infraMascara(this, event,\'##########\')" style="width:84px; font-size:1em;" name="nu_sei[' . $numChave . ']" onblur="buscarDadosDocumento(this)" id="nu_sei' . $numChave . '" ' . $strNaoProcessado . ' class="nu_sei infraText"/>';
    if (isset($arquivoProcessado['nuSei'])) {
        $strUrlDocumento = SessaoSEI::getInstance()->assinarLink('controlador.php?acao=procedimento_trabalhar&id_documento=' . rawurlencode($strIdDocumentoPrincipal));
        $documento = '<a class="protocoloNormal" style="font-size: 1.0em !important; font-size:1em" href="' . htmlspecialchars($strUrlDocumento, ENT_QUOTES, 'ISO-8859-1') . '" target="_blank">' . $strNuSeiHtml . '</a><input id="nu_sei' . $numChave . '" name="nu_sei[' . $numChave . ']" type="hidden" value="' . $strNuSeiAttr . '" class="nu_sei infraText"/>';
    }
    $strResultado .= $documento;
    $strResultado .= '</td>';

    $strResultado .= '<td>';
    if ($arquivoProcessado['nuDocumento']) {
        $strUrlDocumento = SessaoSEI::getInstance()->assinarLink('controlador.php?acao=procedimento_trabalhar&id_documento=' . rawurlencode($strIdDocumentoPrincipal));
    }

    $tipoDocumento = ($arquivoProcessado['IdProtocolo'] == $arquivoProcessado['idDocumentoPrincipal']) ? 'Principal' : 'Anexo';
    $strResultado .= isset($arquivoProcessado['nuDocumento'])
        ? '<span title="Nï¿½mero do Processo: ' . $strNuProcessoAttr . '">' . $strNuSerieHtml . ' ' . $strNuDocumentoHtml . ' <a class="protocoloNormal" style="font-size: 1.0em !important; font-size:1em" href="' . htmlspecialchars($strUrlDocumento, ENT_QUOTES, 'ISO-8859-1') . '" target="_blank">(' . $strNuSeiHtml . ')</a> - ' . $strTipoDocumentoHtml . '</span></a><input type="hidden" class="hidden_idDocumentoPrincipal" name="idDocumentoPrincipal[' . $numChave . ']" value="' . $strIdDocumentoPrincipalAttr . '" ' . $strNaoProcessado . ' />'
        : '<span id="nu_documento' . $numChave . '"></span>';

    $strResultado .= '</td>';

    $strResultado .= '<td align="center">';


    $rastreio = '<span id="codigo_rastreio' . $numChave . '"></span>';
    if ($arquivoProcessado['coRastreamanento']) {
        $strUrl = SessaoSEI::getInstance()->assinarLink('controlador.php?acao=md_cor_expedicao_detalhar_rastreio&acao_origem=' . rawurlencode((string)$_GET['acao']) . '&co_rastreio=' . rawurlencode($strCodigoRastreamento));
        $rastreio = '<span id="codigo_rastreio' . $numChave . '"><input name="hdnRastreamento[' . $numChave . ']" type="hidden" value="' . $strCodigoRastreamentoAttr . '" /><a class="protocoloNormal" style="font-size: 1.0em !important; width:80%" title="Rastreio do AR" href="' . htmlspecialchars($strUrl, ENT_QUOTES, 'ISO-8859-1') . '" target="_blank">' . $strCodigoRastreamentoHtml . '</a></span>';

    }

    $strResultado .= $rastreio;
    $strResultado .= '</td>';

    $habilitado = !isset($arquivoProcessado['nuDocumento']) ? ' disabled="disabled" ' : null;

    $strDataRetorno = null;
    $strDataAR = null;
    $coMotivo = isset($arquivoProcessado['idMotivo']) ? $arquivoProcessado['idMotivo'] : null;

    $strDataRetorno = isset($arquivoProcessado['dtRetorno']) ? (string)$arquivoProcessado['dtRetorno'] : '';
    $strDataAR = isset($arquivoProcessado['dtAr']) ? (string)$arquivoProcessado['dtAr'] : '';
    $strDataRetornoAttr = htmlspecialchars($strDataRetorno, ENT_QUOTES, 'ISO-8859-1');
    $strDataARAttr = htmlspecialchars($strDataAR, ENT_QUOTES, 'ISO-8859-1');

    $strResultado .= '<td id="dtAR">';
    $strResultado .= '<div class="input-group" style="width:120px;"><input class="infraText form-control" onchange="return validarFormatoData(this);" style="font-size:1em; width:70%" value="' . $strDataARAttr . '" ' . $disabled . ' id="dt_ar' . $numChave . '" onkeypress="return infraMascara(this, event,\'##/##/####\')" style="width:80px" name="dt_ar[' . $numChave . ']"  ' . $strNaoProcessado . '/><img src="' . PaginaSEI::getInstance()->getDiretorioSvgGlobal() . '/calendario.svg" id="imgCalDthAr" title="Selecionar Data do AR" alt="Selecionar Data do AR" class="infraImg" onclick="infraCalendario(\'dt_ar' . $numChave . '\',this,false,\'\');" ' . $strNaoProcessado . ' ></div>';
    $strResultado .= '</td>';

    $stStatus = !isset($arquivoProcessado['stAltera']) && $habilitaDtAr;

    $strResultado .= '<td id="dtRetorno">';
    $strResultado .= '<div class="input-group" style="width:120px;"><input class="infraText form-control" onchange="return validarFormatoData(this);" style="font-size:1em; width:70%" value="' . $strDataRetornoAttr . '" ' . $disabled . ' id="dt_retorno' . $numChave . '" onkeypress="return infraMascara(this, event,\'##/##/####\')" style="width:80px" name="dt_retorno[' . $numChave . ']"  ' . $strNaoProcessado . '/><img src="' . PaginaSEI::getInstance()->getDiretorioSvgGlobal() . '/calendario.svg" id="imgCalDthRetorno" title="Selecionar Data do Retorno" alt="Selecionar Data do Retorno" class="infraImg" onclick="infraCalendario(\'dt_retorno' . $numChave . '\',this,false,\'\');" ' . $strNaoProcessado . ' ></div>';

    if ($stStatus && !empty($strDataRetorno)) {
        $strResultado .= '<input type="hidden" name="dt_retorno[' . $numChave . ']" value="' . $strDataRetornoAttr . '"/>';
    }

    $strResultado .= '</td>';
    $disabledSelect = '';
    if (is_null($coMotivo)) {
        $disabledSelect = 'disabled="disabled"';
    }
    $disabledInput = '';
    if (!is_null($coMotivo)) {
        $disabledInput = 'disabled="disabled"';
    }

    $motivoSelecionado = !is_null($coMotivo) ? 'checked="checked"' : null;

    $habilitaDtAr = !is_null($strDataAR) ? 'disabled="disabled"' : $habilitado;
    $slInfrigencia = MdCorParamArInfrigenINT::montarSelectIdMdCorParamArInfrigencia('null', '', $coMotivo);
    $strResultado .= '<td id="objDevolvido"><div class="input-group" style="width:160px;">';
    $strResultado .= '<span style="margin-top:5px;"><input class="infraCheckbox form-check-input" ' . $motivoSelecionado . ' ' . $disabledInput . 'id="st_devolvido' . $numChave . '" ' . $checkbox . ' onclick="selecionaDevolvido(this)" type="checkbox" name="st_devolvido[' . $numChave . ']"/></span>';
    $disabledSelect = '';


    $strResultado .= '<select ' . $disabledSelect . ' id="co_motivo' . $numChave . '" class="co_motivo infraSelect form-select" name="co_motivo[' . $numChave . ']" style="width : 80%; font-size:1em; right: -4px;">';
    $strResultado .= $slInfrigencia;
    $strResultado .= '</select>';

    if ($_GET['acao'] == 'md_cor_retorno_ar_processar' && is_null($habilitaDtAr)) {
        $strResultado .= '<input name="co_motivo_input[' . $numChave . ']" value="' . htmlspecialchars((string)$coMotivo, ENT_QUOTES, 'ISO-8859-1') . '" type="hidden"/>';
    }

    $strResultado .= '</div></td>';


    $strResultado .= '<td align="center">';
    $strResultado .= '<span id="spanStProcessamento' . $numChave . '">' . PaginaSEI::tratarHTML($arrLabelStatus[$arquivoProcessado['status']]) . '</span>';
    $strResultado .= '<input class="hdnStProcessamento" id="hdnStProcessamento' . $numChave . '" type="hidden" name="hdnStProcessamento[' . $numChave . ']" value="' . $strStatusProcessamentoAttr . '"/>';
    $strResultado .= '</td>';

    $strResultado .= '</tr>';

}
$strResultado .= '</tbody></table>';
?>