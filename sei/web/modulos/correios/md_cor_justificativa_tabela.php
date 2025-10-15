<?php

$objMdCorJustDTO = new MdCorJustificativaDTO();
$objMdCorJustDTO->retStrNome();
$objMdCorJustDTO->retNumIdMdCorJustificativa();
$objMdCorJustDTO->retStrSinAtivo();

$txtJustificativa = PaginaSEI::getInstance()->recuperarCampo('txtJustificativa');
if ($txtJustificativa != '') {
    $objMdCorJustDTO->setStrNome('%' . $txtJustificativa . '%', InfraDTO::$OPER_LIKE);
}

if (isset($_POST['txtJustificativa']) && !empty($_POST['txtJustificativa'])) {
    $objMdCorJustDTO->setStrNome('%' . $_POST['txtJustificativa'] . '%', InfraDTO::$OPER_LIKE);
}

PaginaSEI::getInstance()->prepararOrdenacao($objMdCorJustDTO, 'IdMdCorJustificativa', InfraDTO::$TIPO_ORDENACAO_ASC);
PaginaSEI::getInstance()->prepararPaginacao($objMdCorJustDTO, 200);

$objMdCorJustRN = new MdCorJustificativaRN();
$arrObjMdCorJustDTO = $objMdCorJustRN->listar($objMdCorJustDTO);

PaginaSEI::getInstance()->processarPaginacao($objMdCorJustDTO);
$numRegistros = count($arrObjMdCorJustDTO);

if ($numRegistros > 0) {

    $strResultado = '';

    $strSumarioTabela = 'Tabela de Justificativas de Destinatários não Habilitados para Expedição.';
    $strCaptionTabela = 'Justificativas de Destinatários não Habilitados para Expedição ';

    $strResultado .= '<table width="99%" class="infraTable" summary="' . $strSumarioTabela . '">' . "\n";
    $strResultado .= '<caption class="infraCaption">' . PaginaSEI::getInstance()->gerarCaptionTabela($strCaptionTabela, $numRegistros) . '</caption>';

    $strResultado .= '<tr>';
    $strResultado .= '<th class="infraTh text-left">' . PaginaSEI::getInstance()->getThOrdenacao($objMdCorJustDTO, 'Justificativa', 'Nome', $arrObjMdCorJustDTO) . '</th>' . "\n";
    $strResultado .= '<th class="infraTh" width="100px">Ações</th>' . "\n";
    $strResultado .= '</tr>' . "\n";

    $strCssTr = '';

    for ($i = 0; $i < $numRegistros; $i++) {

        if ($_GET['acao_origem'] == 'md_cor_justificativa_alterar' && $arrObjMdCorJustDTO[$i]->getNumIdMdCorJustificativa() == $_GET['id_md_cor_justificativa']) {
            $strCssTr = '<tr class="infraTrAcessada">';
        } else {
            if ($arrObjMdCorJustDTO[$i]->getStrSinAtivo() == 'S') {
                $strCssTr = ($strCssTr == '<tr class="infraTrClara">') ? '<tr class="infraTrEscura">' : '<tr class="infraTrClara">';
            } else {
                $strCssTr = '<tr class="trVermelha">';
            }
        }

        $strResultado .= $strCssTr;
        $strResultado .= '<td>' . PaginaSEI::tratarHTML($arrObjMdCorJustDTO[$i]->getStrNome()) . '</td>';
        $strResultado .= '<td align="center">';
        $strResultado .= PaginaSEI::getInstance()->getAcaoTransportarItem($i, $arrObjMdCorJustDTO[$i]->getNumIdMdCorJustificativa());

        $strId = $arrObjMdCorJustDTO[$i]->getNumIdMdCorJustificativa();

        $bolAcaoExcluir = SessaoSEI::getInstance()->verificarPermissao('md_cor_justificativa_excluir');

        if ($_GET['acao'] == 'md_cor_justificativa_reativar') {
            $bolAcaoReativar = SessaoSEI::getInstance()->verificarPermissao('md_cor_justificativa_reativar');
            $bolAcaoAlterar = false;
            $bolAcaoDesativar = false;
        } else {
            $bolAcaoAlterar = SessaoSEI::getInstance()->verificarPermissao('md_cor_justificativa_alterar');
            $bolAcaoDesativar = SessaoSEI::getInstance()->verificarPermissao('md_cor_justificativa_desativar');
            $bolAcaoReativar = true;
        }

        if($bolAcaoDesativar)
            $strLinkDesativar = SessaoSEI::getInstance()->assinarLink('controlador.php?acao=md_cor_justificativa_desativar&acao_origem=' . $_GET['acao']);

        if($bolAcaoReativar)
            $strLinkReativar = SessaoSEI::getInstance()->assinarLink('controlador.php?acao=md_cor_justificativa_reativar&acao_origem=' . $_GET['acao'] . '&acao_confirmada=sim');

        if ($bolAcaoAlterar) {
            $strResultado .= '<a href="' . SessaoSEI::getInstance()->assinarLink('controlador.php?acao=md_cor_justificativa_alterar&acao_origem=' . $_GET['acao'] . '&acao_retorno=' . $_GET['acao'] . '&idJustificativa=' . $strId) . '" tabindex="' . PaginaSEI::getInstance()->getProxTabTabela() . '"><img src="' . PaginaSEI::getInstance()->getDiretorioSvgGlobal() . '/alterar.svg" title="Alterar Justificativas de Destinatários não Habilitados para Expedição" alt="Alterar Justificativas de Destinatários não Habilitados para Expedição" class="infraImg"/></a>&nbsp;';
        }

        if ($bolAcaoDesativar || $bolAcaoReativar) {

            $strId = $arrObjMdCorJustDTO[$i]->getNumIdMdCorJustificativa();
            $strJustificativa = PaginaSEI::getInstance()->formatarParametrosJavaScript(PaginaSEI::tratarHTML($arrObjMdCorJustDTO[$i]->getStrNome()));

            if ($bolAcaoDesativar && $arrObjMdCorJustDTO[$i]->getStrSinAtivo() == 'S') {
                $strResultado .= '<a href="' . PaginaSEI::getInstance()->montarAncora($strId) . '"onclick="acaoDesativar(\'' . $strId . '\',\'' . $strJustificativa . '\');" tabindex="' . PaginaSEI::getInstance()->getProxTabTabela() . '"><img src="' . PaginaSEI::getInstance()->getDiretorioSvgGlobal() . '/desativar.svg" title="Desativar Visualização de Justificativas de Destinatários não Habilitados para Expedição" alt="Desativar Visualização de Justificativas de Destinatários não Habilitados para Expedição" class="infraImg"/></a>&nbsp;';
            } else {
                $strResultado .= '<a href="' . PaginaSEI::getInstance()->montarAncora($strId) . '"onclick="acaoReativar(\'' . $strId . '\',\'' . $strJustificativa . '\');" tabindex="' . PaginaSEI::getInstance()->getProxTabTabela() . '"><img src="' . PaginaSEI::getInstance()->getDiretorioSvgGlobal() . '/reativar.svg" title="Reativar Visualização de Justificativas de Destinatários não Habilitados para Expedição" alt="Reativar Visualização de Justificativas de Destinatários não Habilitados para Expedição" class="infraImg"/></a>&nbsp;';
            }
        }

        if ($bolAcaoExcluir) {
            $strLinkExcluir = SessaoSEI::getInstance()->assinarLink('controlador.php?acao=md_cor_justificativa_excluir&acao_origem=' . $_GET['acao'] . '&idJustificativa=' . $strId);
            $strJustificativa = PaginaSEI::getInstance()->formatarParametrosJavaScript(PaginaSEI::tratarHTML($arrObjMdCorJustDTO[$i]->getStrNome()));
            $strResultado .= '<a href="#ID-' . $strId . '"  onclick="acaoExcluir(\'' . $strId . '\',\'' . $strJustificativa . '\',\'' . $strLinkExcluir . '\');" tabindex="' . PaginaSEI::getInstance()->getProxTabTabela() . '"><img src="' . PaginaSEI::getInstance()->getDiretorioSvgGlobal() . '/excluir.svg" title="Excluir Justificativas de Destinatários não Habilitados para Expedição" alt="Excluir " class="infraImg" /></a>&nbsp;';
        }

        $strResultado .= '</td></tr>' . "\n";
    }

    $strResultado .= '
</table>';
}
