<?php
$mdCorRetornoArRN = new MdCorRetornoArRN();
$mdCorRetornoArDTO = new MdCorRetornoArDTO();

if (isset($_POST['txtPeriodoProcessamentoInicio']) && $_POST['txtPeriodoProcessamentoFim']) {
    $txtPeriodoProcessamentoInicio = $_POST['txtPeriodoProcessamentoInicio'] . ' 00:00:00';
    $txtPeriodoProcessamentoFim = $_POST['txtPeriodoProcessamentoFim'] . ' 23:59:59';


    $mdCorRetornoArDTO->adicionarCriterio(array('DataCadastro', 'DataCadastro'),
        array(InfraDTO::$OPER_MAIOR_IGUAL, InfraDTO::$OPER_MENOR_IGUAL),
        array($txtPeriodoProcessamentoInicio, $txtPeriodoProcessamentoFim),
        InfraDTO::$OPER_LOGICO_AND);
}

if (isset($_POST['txtCodRastreamento']) && $_POST['txtCodRastreamento'] != '') {
    $mdCorRetornoArDTO->setStrCodigoRastreamento($_POST['txtCodRastreamento']);
}
if (isset($_POST['numDocumentoPrincipal']) && $_POST['numDocumentoPrincipal'] != '') {
    $mdCorRetornoArDTO->setStrProtocoloDocumentoFormatado($_POST['numDocumentoPrincipal']);
}
if (isset($_POST['txtCodigoPlp']) && $_POST['txtCodigoPlp'] != '') {
    $mdCorRetornoArDTO->setStrCodigoPlp($_POST['txtCodigoPlp']);
}
if (isset($_POST['numProcesso']) && $_POST['numProcesso'] != '') {
    $mdCorRetornoArDTO->setStrProtocoloProcedimentoFormatado($_POST['numProcesso']);
}

PaginaSEI::getInstance()->prepararOrdenacao($mdCorRetornoArDTO, 'DataCadastro', InfraDTO::$TIPO_ORDENACAO_DESC);
PaginaSEI::getInstance()->prepararPaginacao($mdCorRetornoArDTO, 200);
$mdCorRetornoArDTO->retNumIdMdCorRetornoAr();
$mdCorRetornoArDTO->setDistinct(true);
$mdCorRetornoArDTO->setNumIdUnidade(SessaoSEI::getInstance()->getNumIdUnidadeAtual());
$mdCorRetornoArDTO->retDthDataCadastro();
$mdCorRetornoArDTO->retStrNomeUsuario();
$mdCorRetornoArDTO->retStrSinAutenticado();
$arrMdCorRetornoArDTO = $mdCorRetornoArRN->listar($mdCorRetornoArDTO);


PaginaSEI::getInstance()->processarPaginacao($mdCorRetornoArDTO);
$numRegistros = $mdCorRetornoArRN->contar($mdCorRetornoArDTO);
$strCaptionTabela = 'Processamento de ARs';


$strResultado .= '<table width="100%" class="infraTable" summary="Serviços">';
$strResultado .= '<caption class="infraCaption">' . PaginaSEI::getInstance()->gerarCaptionTabela($strCaptionTabela, $numRegistros) . '</caption>';
$strResultado .= '<tr style="height: 25px;">';
$strResultado .= '<th class="infraTh text-left" style="width: 15px !important;">' . PaginaSEI::getInstance()->getThCheck() . '</th>' . "\n";
$strResultado .= '<th class="infraTh text-left" style="width: 230px  !important;">' . PaginaSEI::getInstance()->getThOrdenacao($mdCorRetornoArDTO, 'Data e Hora do Processamento', 'DataCadastro', $arrMdCorRetornoArDTO) . '</th>';
$strResultado .= '<th class="infraTh text-left" style="width: 200px  !important;">' . PaginaSEI::getInstance()->getThOrdenacao($mdCorRetornoArDTO, 'Usuário', 'NomeUsuario', $arrMdCorRetornoArDTO) . '</th>';
$strResultado .= '<th class="infraTh text-center" style="width: 80px !important;">Ações</th>';
$strResultado .= '</tr>';

foreach ($arrMdCorRetornoArDTO as $chave => $resultado) {
    $strAutenticar = SessaoSEI::getInstance()->assinarLink('controlador.php?acao=md_cor_retorno_ar_autenticar&id_retorno=' . $resultado->getNumIdMdCorRetornoAr());
    $strCssTr = $strCssTr == '<tr class="infraTrClara"' ? '<tr class="infraTrEscura"' : '<tr class="infraTrClara"';
    $strResultado .= $strCssTr . ' id="linha_' . $chave . '">';
    $strResultado .= '<td valign="middle">' . PaginaSEI::getInstance()->getTrCheck($chave, $resultado->getNumIdMdCorRetornoAr(), $resultado->getNumIdMdCorRetornoAr()) . '</td>';
    $strResultado .= '<td>';
    $strResultado .= $resultado->getDthDataCadastro();
    $strResultado .= '</td>';
    $strResultado .= '<td>';
    $strResultado .= $resultado->getStrNomeUsuario();
    $strResultado .= '</td>';
    $strResultado .= '<td class="text-center">';
    $strResultado .= '<a href="' . SessaoSEI::getInstance()->assinarLink('controlador.php?acao=md_cor_retorno_ar_resumir&acao_origem=' . $_GET['acao'] . '&acao_retorno=' . $_GET['acao'] . '&id_md_cor_retorno_ar=' . $resultado->getNumIdMdCorRetornoAr()) . '" tabindex="' . PaginaSEI::getInstance()->getProxTabTabela() . '"><img src="modulos/correios/imagens/svg/associar.svg?'.Icone::VERSAO.'" title="Resumo do Processamento" alt="Resumo do Processamento" class="infraImgAcoes" /></a>&nbsp;';
//    if ($resultado->getStrSinAutenticado() == 'S') {
//         $strResultado .= '<a  tabindex="' . PaginaSEI::getInstance()->getProxTabTabela() . '"><img src="modulos/correios/imagens/icon_autenticado.svg" title="ARs Autenticados" alt="ARs Autenticados" class="infraImg" /></a>&nbsp;';
//    } else {
//        $strResultado .= '<a onclick="autenticarDocumento(\'' . $strAutenticar . '\')" style="cursor:pointer" tabindex="' . PaginaSEI::getInstance()->getProxTabTabela() . '"><img src="' . PaginaSEI::getInstance()->getDiretorioImagens() . '/sei_autenticar_pequeno.gif" title="ARs não Autenticados" alt="ARs não Autenticados" class="infraImg" /></a>&nbsp;';
//    }
    $strResultado .= '<a href="' . SessaoSEI::getInstance()->assinarLink('controlador.php?acao=md_cor_retorno_ar_consultar&acao_origem=' . $_GET['acao'] . '&acao_retorno=' . $_GET['acao'] . '&id_md_cor_retorno_ar=' . $resultado->getNumIdMdCorRetornoAr()) . '" tabindex="' . PaginaSEI::getInstance()->getProxTabTabela() . '"><img src="' . PaginaSEI::getInstance()->getDiretorioSvgGlobal() . '/consultar.svg" title="Consultar" alt="Consultar" class="infraImg" /></a>&nbsp;';
    $strResultado .= '<a href="' . SessaoSEI::getInstance()->assinarLink('controlador.php?acao=md_cor_retorno_ar_alterar&acao_origem=' . $_GET['acao'] . '&acao_retorno=' . $_GET['acao'] . '&id_md_cor_retorno_ar=' . $resultado->getNumIdMdCorRetornoAr()) . '" tabindex="' . PaginaSEI::getInstance()->getProxTabTabela() . '"><img src="' . PaginaSEI::getInstance()->getDiretorioSvgGlobal() . '/alterar.svg" title="Alterar" alt="Alterar" class="infraImg" /></a>';

    $strResultado .= '</td>';
    $strResultado .= '</tr>';
}
$strResultado .= '</table>';

?>