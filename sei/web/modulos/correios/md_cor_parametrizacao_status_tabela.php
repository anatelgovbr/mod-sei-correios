<?php

$objMdCorListaStatusDTO = new MdCorListaStatusDTO();
$objMdCorListaStatusDTO->retTodos(true);

if (isset($_POST['txtStatus']) && !empty($_POST['txtStatus'])) {
    $objMdCorListaStatusDTO->setNumStatus($_POST['txtStatus'], InfraDTO::$OPER_IGUAL);
}
if (isset($_POST['txtTipo']) && !empty($_POST['txtTipo'])) {
    $objMdCorListaStatusDTO->setStrTipo('%' . $_POST['txtTipo'] . '%', InfraDTO::$OPER_LIKE);
}
if (isset($_POST['txtDescricao']) && !empty($_POST['txtDescricao'])) {
    $objMdCorListaStatusDTO->setStrDescricao('%' . $_POST['txtDescricao'] . '%', InfraDTO::$OPER_LIKE);
}
if (isset($_POST['txtSituacaoRastreio']) && !empty($_POST['txtSituacaoRastreio'])) {
    $objMdCorListaStatusDTO->setStrStaRastreioModulo('%' . $_POST['txtSituacaoRastreio'] . '%', InfraDTO::$OPER_LIKE);
}

if ($_GET['acao'] == 'md_cor_parametrizacao_status_reativar') {
    $objMdCorListaStatusDTO->setStrSinAtivo('N');
}

PaginaSEI::getInstance()->prepararOrdenacao($objMdCorListaStatusDTO, 'IdMdCorListaStatus', InfraDTO::$TIPO_ORDENACAO_ASC);
PaginaSEI::getInstance()->prepararPaginacao($objMdCorListaStatusDTO, 200);

$objMdCorListaStatusRN = new MdCorListaStatusRN();
$arrObjMdCorListaStatusDTO = $objMdCorListaStatusRN->listar($objMdCorListaStatusDTO);

PaginaSEI::getInstance()->processarPaginacao($objMdCorListaStatusDTO);
$numRegistros = count($arrObjMdCorListaStatusDTO);

if ($numRegistros > 0) {


    if ($_GET['acao'] == 'md_cor_parametrizacao_status_reativar') {

        $bolAcaoReativar = SessaoSEI::getInstance()->verificarPermissao('md_cor_parametrizacao_status_reativar');
        $bolAcaoAlterar = false;
        $bolAcaoDesativar = false;

    } else {
        $bolAcaoAlterar = SessaoSEI::getInstance()->verificarPermissao('md_cor_parametrizacao_status_alterar');
        $bolAcaoDesativar = SessaoSEI::getInstance()->verificarPermissao('md_cor_parametrizacao_status_desativar');

        $bolAcaoReativar = true;
    }

    if ($bolAcaoDesativar) {
        $strLinkDesativar = SessaoSEI::getInstance()->assinarLink('controlador.php?acao=md_cor_parametrizacao_status_desativar&acao_origem=' . $_GET['acao']);
    }

    if ($bolAcaoReativar) {
        $strLinkReativar = SessaoSEI::getInstance()->assinarLink('controlador.php?acao=md_cor_parametrizacao_status_reativar&acao_origem=' . $_GET['acao'] . '&acao_confirmada=sim');
    }

    $strResultado = '';

    if ($_GET['acao'] != 'md_cor_parametrizacao_status_reativar') {
        $strSumarioTabela = 'Tabela de Tipos de Situações de Rastreamento de Objeto.';
        $strCaptionTabela = 'Tipos de Situações de Rastreamento de Objeto ';
    } else {
        $strSumarioTabela = 'Tabela de Tipos de Situações de Rastreamento de Objeto Inativos.';
        $strCaptionTabela = 'Tipos de Situações de Rastreamento de Objeto ';
    }

    $strResultado .= '<table width="100%" class="infraTable" summary="' . $strSumarioTabela . '">' . "\n";
    $strResultado .= '<caption class="infraCaption">' . PaginaSEI::getInstance()->gerarCaptionTabela($strCaptionTabela, $numRegistros) .'</caption>';

    $strResultado .= '<tr>';
    $strResultado .= '<th class="infraTh text-center" width="30px">' . PaginaSEI::getInstance()->getThOrdenacao($objMdCorListaStatusDTO, 'Código','Status', $arrObjMdCorListaStatusDTO) . '</th>' . "\n";
    $strResultado .= '<th class="infraTh text-center" width="50px">' . PaginaSEI::getInstance()->getThOrdenacao($objMdCorListaStatusDTO, 'Tipo', 'Tipo',$arrObjMdCorListaStatusDTO) . '</th>' . "\n";
    $strResultado .= '<th class="infraTh text-left">' . PaginaSEI::getInstance()->getThOrdenacao($objMdCorListaStatusDTO, 'Descrição','Descricao', $arrObjMdCorListaStatusDTO) . '</th>' . "\n";
    $strResultado .= '<th class="infraTh text-left" width="200px">' . PaginaSEI::getInstance()->getThOrdenacao($objMdCorListaStatusDTO, 'Situação no Módulo','SituacaoRastreio', $arrObjMdCorListaStatusDTO) . '</th>' . "\n";
    $strResultado .= '<th class="infraTh text-center" width="80px">Ações</th>' . "\n";
    $strResultado .= '</tr>' . "\n";

    $strCssTr = '';

    for ($i = 0; $i < $numRegistros; $i++) {


        if (($_GET['acao_origem'] == 'md_cor_parametrizacao_status_consultar' && $arrObjMdCorListaStatusDTO[$i]->getNumIdMdCorListaStatus() == $_GET['id_md_cor_lista_status'])
           ||($_GET['acao_origem'] == 'md_cor_parametrizacao_status_alterar' && $arrObjMdCorListaStatusDTO[$i]->getNumIdMdCorListaStatus() == $_GET['id_md_cor_lista_status'])
           ||($_GET['acao_origem'] == 'md_cor_parametrizacao_status_cadastrar' && $arrObjMdCorListaStatusDTO[$i]->getNumIdMdCorListaStatus() == $_GET['id_md_cor_lista_status'])){

            $strCssTr = '<tr class="infraTrAcessada">';
        } else {
            if ($arrObjMdCorListaStatusDTO[$i]->getStrSinAtivo() == 'S') {
                $strCssTr = ($strCssTr == '<tr class="infraTrClara">') ? '<tr class="infraTrEscura">' : '<tr class="infraTrClara">';
            } else {
                $strCssTr = '<tr class="infraTrVermelha">';
            }
        }

        $strResultado .= $strCssTr;
        $strResultado .= '<td class="text-center">' . PaginaSEI::tratarHTML($arrObjMdCorListaStatusDTO[$i]->getNumStatus()) . '</td>';
        $strResultado .= '<td class="text-center">' . PaginaSEI::tratarHTML($arrObjMdCorListaStatusDTO[$i]->getStrTipo()) . '</td>';
        $strResultado .= '<td>' . PaginaSEI::tratarHTML($arrObjMdCorListaStatusDTO[$i]->getStrDescricao()) . '</td>';
        if($arrObjMdCorListaStatusDTO[$i]->getStrStaRastreioModulo() == 'S'){
            $strResultado .= '<td>' . PaginaSEI::tratarHTML('Sucesso na Entrega') . '</td>';
        }elseif ($arrObjMdCorListaStatusDTO[$i]->getStrStaRastreioModulo() == 'T'){
            $strResultado .= '<td>' . PaginaSEI::tratarHTML('Em Trânsito') . '</td>';
        }elseif ($arrObjMdCorListaStatusDTO[$i]->getStrStaRastreioModulo() == 'I'){
            $strResultado .= '<td>' . PaginaSEI::tratarHTML('Insucesso na Entrega') . '</td>';
        }elseif ($arrObjMdCorListaStatusDTO[$i]->getStrStaRastreioModulo() == 'P'){
            $strResultado .= '<td>' . PaginaSEI::tratarHTML('Postado') . '</td>';
        }else{
            $strResultado .= '<td>' . PaginaSEI::tratarHTML('') . '</td>';
        }

        $strResultado .= '<td class="text-center">';
        $strResultado .= PaginaSEI::getInstance()->getAcaoTransportarItem($i, $arrObjMdCorListaStatusDTO[$i]->getNumIdMdCorListaStatus());

        if ($bolAcaoAlterar) {
            $strResultado .= '<a href="' . SessaoSEI::getInstance()->assinarLink('controlador.php?acao=md_cor_parametrizacao_status_alterar&acao_origem=' . $_GET['acao'] . '&acao_retorno=' . $_GET['acao'] . '&id_md_cor_lista_status=' . $arrObjMdCorListaStatusDTO[$i]->getNumIdMdCorListaStatus()) . '" tabindex="' . PaginaSEI::getInstance()->getProxTabTabela() . '"><img src="' . PaginaSEI::getInstance()->getDiretorioSvgGlobal() . '/alterar.svg" title="Alterar Tipos de Situações SRO" alt="Alterar Tipos de Situações SRO" class="infraImg"/></a>&nbsp;';
        }

        if ($bolAcaoDesativar || $bolAcaoReativar) {

            $strId = $arrObjMdCorListaStatusDTO[$i]->getNumIdMdCorListaStatus();
            $strDescricao = PaginaSEI::getInstance()->formatarParametrosJavaScript(PaginaSEI::tratarHTML($arrObjMdCorListaStatusDTO[$i]->getStrDescricao()));
            $strSituacaoRastreioModulo = PaginaSEI::getInstance()->formatarParametrosJavaScript(PaginaSEI::tratarHTML($arrObjMdCorListaStatusDTO[$i]->getStrStaRastreioModulo()));

            if ($bolAcaoDesativar && $arrObjMdCorListaStatusDTO[$i]->getStrSinAtivo() == 'S') {
                $strResultado .= '<a href="' . PaginaSEI::getInstance()->montarAncora($strId) . '"onclick="acaoDesativar(\'' . $strId . '\',\'' . $strDescricao . '\');" tabindex="' . PaginaSEI::getInstance()->getProxTabTabela() . '"><img src="' . PaginaSEI::getInstance()->getDiretorioSvgGlobal() . '/desativar.svg" title="Desativar Visualização do Status no Rastreamento do Objeto" alt="Desativar Visualização do Status no Rastreamento do Objeto" class="infraImg"/></a>&nbsp;';
            } else {
                $strResultado .= '<a href="' . PaginaSEI::getInstance()->montarAncora($strId) . '"onclick="acaoReativar(\'' . $strId . '\',\'' . $strDescricao . '\',\'' . $strSituacaoRastreioModulo . '\');"tabindex="' . PaginaSEI::getInstance()->getProxTabTabela() . '"><img src="' . PaginaSEI::getInstance()->getDiretorioSvgGlobal() . '/reativar.svg" title="Reativar Visualização do Status no Rastreamento do Objeto" alt="Reativar Visualização do Status no Rastreamento do Objeto" class="infraImg"/></a>&nbsp;';
            }
        }

        $strResultado .= '</td></tr>' . "\n";
    }

    $strResultado .= '
</table>';
}
