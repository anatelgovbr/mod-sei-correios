<?php

$strResultado .= '<table width="100%" class="infraTable" summary="Serviços">';
$strResultado .= '<tr style="height: 25px;">';
$strResultado .= '<th class="infraTh" width="2%"></th>';
$strResultado .= '<th class="infraTh" width="10%">Nome do Arquivo</th>';
$strResultado .= '<th class="infraTh" width="14%">Número SEI';
$strResultado .= '<img id="ancAjuda" src="' . PaginaSEI::getInstance()->getDiretorioImagensGlobal() . '/ajuda.gif" class="infraImg"' . PaginaSEI::montarTitleTooltip('Número SEI do Documento Principal referente a expedição atrelada ao AR identificado. Para ARs identificados no processamento, este campo é recuperado a partir do Código de rastreamento e não pode ser alterado. Para ARs onde o documento não foi identificado, este deverá ser informado.', 'Ajuda') . '/>';
$strResultado .= '</th>';
$strResultado .= '<th class="infraTh" width="12%">Documento';
$strResultado .= '<img id="ancAjuda" src="' . PaginaSEI::getInstance()->getDiretorioImagensGlobal() . '/ajuda.gif" class="infraImg"' . PaginaSEI::montarTitleTooltip('Documento referente ao Número SEI recuperado/ informado.', 'Ajuda') . '/>';
$strResultado .= '</th>';
$strResultado .= '<th class="infraTh" width="14%">Rastreamento';
$strResultado .= '<img id="ancAjuda" src="' . PaginaSEI::getInstance()->getDiretorioImagensGlobal() . '/ajuda.gif" class="infraImg"' . PaginaSEI::montarTitleTooltip('Código do Rastreio do Documento', 'Ajuda') . '/>';
$strResultado .= '</th>';
$strResultado .= '<th class="infraTh" width="15%">Data do AR';
$strResultado .= '<img id="ancAjuda" src="' . PaginaSEI::getInstance()->getDiretorioImagensGlobal() . '/ajuda.gif" class="infraImg"' . PaginaSEI::montarTitleTooltip('A data de Recebimento Efetivo pelo Destinatário ou da Última Tentativa de Entrega pelos Correios.', 'Ajuda') . '/>';
$strResultado .= '</th>';
$strResultado .= '<th class="infraTh" width="15%">Data de Retorno';
$strResultado .= '<img id="ancAjuda" src="' . PaginaSEI::getInstance()->getDiretorioImagensGlobal() . '/ajuda.gif" class="infraImg"' . PaginaSEI::montarTitleTooltip('Data da Entrega Efetiva do AR pelos Correios ao Órgão.', 'Ajuda') . '/>';
$strResultado .= '</th>';
$strResultado .= '<th class="infraTh" width="17%">Objeto Devolvido</th>';
$strResultado .= '<th class="infraTh" width="15%">Status</th>';
$strResultado .= '</tr>';

//Linhas
$strCssTr = '<tr class="infraTrEscura">';
if ($_GET['acao'] == 'md_cor_retorno_ar_alterar') {
    $checkbox = 'disabled="disabled"';
} else {
    $checkbox = '';
}


foreach ($arrArquivoProcessado as $chave => $arquivoProcessado) {
    $strCssTr = $strCssTr == '<tr class="infraTrClara"' ? '<tr class="infraTrEscura"' : '<tr class="infraTrClara"';
    $strResultado .= $strCssTr . ' id="linha_' . $chave . '">';

    $strResultado .= '<td>';
    $strResultado .= ++$chave;
    $strResultado .= '</td>';

    $strResultado .= '<td align="center">';
    $strNaoProcessado = '';
    if ($arquivoProcessado['status'] == MdCorRetornoArRN::$STA_RETORNO_AR_NAO_PROCESSADO && $_GET['acao'] == 'md_cor_retorno_ar_alterar') {
        $strNaoProcessado = 'disabled="disabled"';
    }

    $strResultado .= '<input type="hidden" name="no_arquivo[' . $chave . ']" value="' . $arquivoProcessado['noArquivoPdf'] . '"/>';
    $strUrlDocumentoAr = '';
    if ($_GET['acao'] == 'md_cor_retorno_ar_processar') {
        $diretorio = substr($arquivoLido, 0, -4);
        $strUrlDocumentoAr = SessaoSEI::getInstance()->assinarLink('controlador.php?acao=md_cor_retorno_ar_arquivo_mostrar&diretorio=' . $diretorio . '&documento=' . $arquivoProcessado['noArquivoPdf']);
    }

    $url = !empty($strUrlDocumentoAr) ? 'href="' . $strUrlDocumentoAr . '"' : null;

    $documentoAr = '<a ' . $url . ' class="protocoloNormal" style="font-size: 1.0em !important; font-size:1em" ' . 'target="_blank">' . utf8_decode($arquivoProcessado['noArquivoPdf']) . '</a>';
    $strResultado .= $documentoAr;
    $strResultado .= '</td>';

    $strResultado .= '<td align="center">';
    $documento = '<input ' . $disabled . ' onkeypress="return infraMascara(this, event,\'#######\')" style="width:84px; font-size:1em;" name="nu_sei[' . $chave . ']" onblur="buscarDadosDocumento(this)" id="nu_sei' . $chave . '" ' . $strNaoProcessado . ' class="nu_sei infraText"/>';
    if (isset($arquivoProcessado['nuSei'])) {
        $strUrlDocumento = SessaoSEI::getInstance()->assinarLink('controlador.php?acao=procedimento_trabalhar&id_documento=' . $arquivoProcessado['idDocumentoPrincipal']);
        $documento = '<a class="protocoloNormal" style="font-size: 1.0em !important; font-size:1em" href="' . $strUrlDocumento . '" target="_blank">' . $arquivoProcessado['nuSei'] . '</a><input id="nu_sei' . $chave . '" name="nu_sei[' . $chave . ']" type="hidden" value="' . $arquivoProcessado['nuSei'] . '" class="nu_sei infraText"/>';
    }
    $strResultado .= $documento;
    $strResultado .= '</td>';

    $strResultado .= '<td>';
    if ($arquivoProcessado['nuDocumento'])
        $strUrlDocumento = SessaoSEI::getInstance()->assinarLink('controlador.php?acao=procedimento_trabalhar&id_documento=' . $arquivoProcessado['idDocumentoPrincipal']);
    $tipoDocumento = ($arquivoProcessado['IdProtocolo'] == $arquivoProcessado['idDocumentoPrincipal']) ? 'Principal' : 'Anexo';
    $strResultado .= isset($arquivoProcessado['nuDocumento']) ? '<span title="Número do Processo: ' . $arquivoProcessado['nuProcesso'] . '">' . $arquivoProcessado['nuSerie'] . ' ' . $arquivoProcessado['nuDocumento'] . ' <a class="protocoloNormal" style="font-size: 1.0em !important; font-size:1em" href="' . $strUrlDocumento . '" target="_blank">(' . $arquivoProcessado['nuSei'] . ')</a>' . ' - ' . $arquivoProcessado['tipoDocumento'] . '</span></a>' . '<input type="hidden" class="hidden_idDocumentoPrincipal" name="idDocumentoPrincipal[' . $chave . ']" value="' . $arquivoProcessado['idDocumentoPrincipal'] . '" ' . $strNaoProcessado . ' />' : '<span id="nu_documento' . $chave . '"></span>';

    $strResultado .= '</td>';

    $strResultado .= '<td align="center">';


    $rastreio = '<span id="codigo_rastreio' . $chave . '"></span>';
    if ($arquivoProcessado['coRastreamanento']) {
        $strUrl = SessaoSEI::getInstance()->assinarLink('controlador.php?acao=md_cor_expedicao_detalhar_rastreio&acao_origem=' . $_GET['acao'] . '&co_rastreio=' . $arquivoProcessado['coRastreamanento']);
        $rastreio = '<span id="codigo_rastreio' . $chave . '"><input name="hdnRastreamento[' . $chave . ']" type="hidden" value="' . $arquivoProcessado['coRastreamanento'] . '" /><a class="protocoloNormal" style="font-size: 1.0em !important; width:80%" title="Rastreio do AR" href="' . $strUrl . '" target="_blank">' . $arquivoProcessado['coRastreamanento'] . '</a></span>';
    } else {

    }

    $strResultado .= $rastreio;
    $strResultado .= '</td>';

    $habilitado = !isset($arquivoProcessado['nuDocumento']) ? ' disabled="disabled" ' : null;


    $strDataRetorno = null;
    $strDataAR = null;
    $coMotivo = isset($arquivoProcessado['idMotivo']) ? $arquivoProcessado['idMotivo'] : null;
    if (!is_null($coMotivo)) {
    }
    $strDataRetorno = $arquivoProcessado['dtRetorno'];
    $strDataAR = $arquivoProcessado['dtAr'];


    $strResultado .= '<td id="dtAR">';
    $strResultado .= '<div class="input-group mb-3"><input class="infraText form-control" onchange="return validarFormatoData(this);" style="font-size:1em; width:70%" value="' . $strDataAR . '" ' . $disabled . ' id="dt_ar' . $chave . '" onkeypress="return infraMascara(this, event,\'##/##/####\')" style="width:80px" name="dt_ar[' . $chave . ']"  ' . $strNaoProcessado . '/><img src="' . PaginaSEI::getInstance()->getDiretorioSvgGlobal() . '/calendario.svg" id="imgCalDthAr" title="Selecionar Data do AR" alt="Selecionar Data do AR" class="infraImg" onclick="infraCalendario(\'dt_ar' . $chave . '\',this,false,\'\');" ' . $strNaoProcessado . ' ></div>';
    $strResultado .= '</td>';

    $stStatus = !isset($arquivoProcessado['stAltera']) && $habilitaDtAr;

    $strResultado .= '<td id="dtRetorno">';
    $strResultado .= '<div class="input-group mb-3"><input class="infraText form-control"  onchange="return validarFormatoData(this);" style="font-size:1em; width:70%" value="' . $strDataRetorno . '" ' . $disabled . ' id="dt_retorno' . $chave . '" onkeypress="return infraMascara(this, event,\'##/##/####\')" style="width:80px" name="dt_retorno[' . $chave . ']"  ' . $strNaoProcessado . '/><img src="' . PaginaSEI::getInstance()->getDiretorioSvgGlobal() . '/calendario.svg" id="imgCalDthRetorno" title="Selecionar Data do Retorno" alt="Selecionar Data do Retorno" class="infraImg" onclick="infraCalendario(\'dt_retorno' . $chave . '\',this,false,\'\');" ' . $strNaoProcessado . ' ></div>';

    if ($stStatus && !empty($strDataRetorno)) {
        $strResultado .= '<input type="hidden" name="dt_retorno[' . $chave . ']" value="' . $strDataRetorno . '"/>';
    }

    $strResultado .= '</td>';
    $disabledSelect = "";
    if (is_null($coMotivo)) {
        $disabledSelect = 'disabled="disabled"';
    }
    $disabledInput = "";
    if (!is_null($coMotivo)) {
        $disabledInput = 'disabled="disabled"';
    }

    $motivoSelecionado = !is_null($coMotivo) ? 'checked="checked"' : null;

    $habilitaDtAr = !is_null($strDataAR) ? 'disabled="disabled"' : $habilitado;
    $slInfrigencia = MdCorParamArInfrigenINT::montarSelectIdMdCorParamArInfrigencia('null', '', $coMotivo);
    $strResultado .= '<td id="objDevolvido"><div class="input-group mb-3">';
    $strResultado .= '<input class="infraCheckbox" ' . $motivoSelecionado . ' ' . $disabledInput . 'id="st_devolvido' . $chave . '" ' . $checkbox . '  onclick="selecionaDevolvido(this)" type="checkbox" name="st_devolvido[' . $chave . ']"/>';
    $disabledSelect = '';


    $strResultado .= '<select ' . $disabledSelect . ' id="co_motivo' . $chave . '" class="co_motivo infraSelect form-control" name="co_motivo[' . $chave . ']" style="width : 80%; font-size:1em; right: -4px;top: 5px;">';
    $strResultado .= $slInfrigencia;
    $strResultado .= '</select>';

    if ($_GET['acao'] == 'md_cor_retorno_ar_processar' && is_null($habilitaDtAr)) {
        $strResultado .= '<input name="co_motivo_input[' . $chave . ']" value="' . $coMotivo . '" type="hidden"/>';
    }

    $strResultado .= '</div></td>';


    $strResultado .= '<td align="center">';
    $strResultado .= '<span id="spanStProcessamento' . $chave . '">' . PaginaSEI::tratarHTML($arrLabelStatus[$arquivoProcessado['status']]) . '</span>';
    $strResultado .= '<input class="hdnStProcessamento" id="hdnStProcessamento' . $chave . '" type="hidden" name="hdnStProcessamento[' . $chave . ']" value="' . $arquivoProcessado['status'] . '"/>';
    $strResultado .= '</td>';

    $strResultado .= '</tr>';

}
$strResultado .= '</table>';
?>