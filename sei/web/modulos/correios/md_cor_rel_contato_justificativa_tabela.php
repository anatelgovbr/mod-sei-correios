<?php

$objMdCorRelContJustDTO = new MdCorRelContatoJustificativaDTO();
$objMdCorRelContJustDTO->retTodos(true);

$txtDestinatario = PaginaSEI::getInstance()->recuperarCampo('txtDestinatario');
if ($txtDestinatario != '') {
    $objMdCorRelContJustDTO->setStrNomeContato('%' . $txtDestinatario . '%', InfraDTO::$OPER_LIKE);
}

$txtCpfCnpj = PaginaSEI::getInstance()->recuperarCampo('txtCpfCnpj');
if ($txtCpfCnpj != '') {
    $strNumero = InfraUtil::retirarFormatacao($txtCpfCnpj, false);
    if (strlen($strNumero) > 11) {
        $objMdCorRelContJustDTO->setNumCnpj(trim(str_pad($strNumero, 14, '0', STR_PAD_LEFT)), InfraDTO::$OPER_IGUAL);
    } else {
        $objMdCorRelContJustDTO->setNumCpf(trim(str_pad($strNumero, 11, '0', STR_PAD_LEFT)), InfraDTO::$OPER_IGUAL);
    }
}

$selNatureza = PaginaSEI::getInstance()->recuperarCampo('selNatureza');
if ($selNatureza != '') {
    $objMdCorRelContJustDTO->setStrStaNatureza($selNatureza, InfraDTO::$OPER_IGUAL);
}

$selJustificativa = PaginaSEI::getInstance()->recuperarCampo('selJustificativa');
if ($selJustificativa != '' && $selJustificativa != 'null') {
    $objMdCorRelContJustDTO->setNumIdMdCorJustificativa($selJustificativa, InfraDTO::$OPER_IGUAL);
}

PaginaSEI::getInstance()->prepararOrdenacao($objMdCorRelContJustDTO, 'IdRelContatoJustificativa', InfraDTO::$TIPO_ORDENACAO_ASC);
PaginaSEI::getInstance()->prepararPaginacao($objMdCorRelContJustDTO, 200);

$objMdCorRelContatoJustificativaRN = new MdCorRelContatoJustificativaRN();
$arrObjMdCorContJustDTO = $objMdCorRelContatoJustificativaRN->listar($objMdCorRelContJustDTO);

PaginaSEI::getInstance()->processarPaginacao($objMdCorRelContJustDTO);
$numRegistros = count($arrObjMdCorContJustDTO);

if ($numRegistros > 0) {

    $bolAcaoAlterar = SessaoSEI::getInstance()->verificarPermissao('md_cor_rel_contato_justificativa_alterar');
    $bolAcaoExcluir = SessaoSEI::getInstance()->verificarPermissao('md_cor_rel_contato_justificativa_excluir');

    $strResultado = '';

    $strSumarioTabela = 'Tabela de Destinatários não Habilitados para Expedição.';
    $strCaptionTabela = 'Destinatários não Habilitados para Expedição ';

    $strResultado .= '<table width="100%" class="infraTable" summary="' . $strSumarioTabela . '">' . "\n";
    $strResultado .= '<caption class="infraCaption">' . PaginaSEI::getInstance()->gerarCaptionTabela($strCaptionTabela, $numRegistros) . '</caption>';

    $strResultado .= '<tr>';

    $strResultado .= '<th class="infraTh" width="1%" style="display: ;">'.PaginaSEI::getInstance()->getThCheck().'</th>';

    $strResultado .= '<th class="infraTh">' . PaginaSEI::getInstance()->getThOrdenacao($objMdCorRelContJustDTO, 'Destinatário', 'NomeContato', $arrObjMdCorContJustDTO) . '</th>' . "\n";
    $strResultado .= '<th class="infraTh">' . PaginaSEI::getInstance()->getThOrdenacao($objMdCorRelContJustDTO, 'Natureza', 'StaNatureza', $arrObjMdCorContJustDTO) . '</th>' . "\n";
    $strResultado .= '<th class="infraTh">' . PaginaSEI::getInstance()->getThOrdenacao($objMdCorRelContJustDTO, 'CPF/CNPJ', is_null($cpf) ? 'Cnpj' : 'Cpf', $arrObjMdCorContJustDTO) . '</th>' . "\n";
    $strResultado .= '<th class="infraTh">' . PaginaSEI::getInstance()->getThOrdenacao($objMdCorRelContJustDTO, 'Justificativa', 'NomeJustificativa', $arrObjMdCorContJustDTO) . '</th>' . "\n";
    $strResultado .= '<th class="infraTh">Ações</th>' . "\n";
    $strResultado .= '</tr>' . "\n";


    for ($i = 0; $i < $numRegistros; $i++) {

        $strCssTr = ($strCssTr == '<tr class="infraTrClara">') ? '<tr class="infraTrEscura">' : '<tr class="infraTrClara">';

        $strNomeContato = $arrObjMdCorContJustDTO[$i]->getStrNomeContato();
        $strId = $arrObjMdCorContJustDTO[$i]->getNumIdRelContatoJustificativa();

        $strResultado .= $strCssTr;
        $strResultado .= '<td style="display: ;">'.PaginaSEI::getInstance()->getTrCheck($i,$strId,$strNomeContato).'</td>';

        $strResultado .= '<td>' . PaginaSEI::tratarHTML($strNomeContato) . '</td>';
        $strResultado .= ($arrObjMdCorContJustDTO[$i]->getStrStaNatureza() == 'F') ? '<td>' . PaginaSEI::tratarHTML('Pesssoa Física') . '</td>' : '<td>' . PaginaSEI::tratarHTML('Pessoa Jurídica') . '</td>';
        $strResultado .= ((strlen($arrObjMdCorContJustDTO[$i]->getNumCnpj()) > 11)) ? '<td>' . PaginaSEI::tratarHTML(InfraUtil::formatarCnpj($arrObjMdCorContJustDTO[$i]->getNumCnpj())) . '</td>' : '<td>' . PaginaSEI::tratarHTML(InfraUtil::formatarCpf($arrObjMdCorContJustDTO[$i]->getNumCpf())) . '</td>';

        $strResultado .= '<td>' . PaginaSEI::tratarHTML($arrObjMdCorContJustDTO[$i]->getStrNomeJustificativa()) . '</td>';
        $strResultado .= '<td align="center">';
        $strResultado .= PaginaSEI::getInstance()->getAcaoTransportarItem($i, $strId);

        if ($bolAcaoAlterar) {
            $strResultado .= '<a href="' . SessaoSEI::getInstance()->assinarLink('controlador.php?acao=md_cor_rel_contato_justificativa_alterar&acao_origem=' . $_GET['acao'] . '&acao_retorno=' . $_GET['acao'] . '&idRelContJustificativa=' . $strId) . '" tabindex="' . PaginaSEI::getInstance()->getProxTabTabela() . '"><img src="' . PaginaSEI::getInstance()->getDiretorioSvgGlobal() . '/alterar.svg" title="Alterar Destinatários não Habilitados para Expedição" alt="Alterar Destinatários não Habilitados para Expedição" class="infraImg"/></a>&nbsp;';
        }

        if ($bolAcaoExcluir) {
            $strLinkExcluir = SessaoSEI::getInstance()->assinarLink('controlador.php?acao=md_cor_rel_contato_justificativa_excluir&acao_origem=' . $_GET['acao'] . '&idRelContJustificativa=' . $strId);
            $strDescricao = PaginaSEI::getInstance()->formatarParametrosJavaScript(PaginaSEI::tratarHTML($arrObjMdCorContJustDTO[$i]->getStrNomeContato()));
            $strResultado .= '<a href="#ID-' . $strId . '"  onclick="acaoExcluir(\'' . $strId . '\',\'' . $strDescricao . '\',\'' . $strLinkExcluir . '\');" tabindex="' . PaginaSEI::getInstance()->getProxTabTabela() . '"><img src="' . PaginaSEI::getInstance()->getDiretorioSvgGlobal() . '/excluir.svg" title="Excluir Destinatário não Habilitado para Expedição" alt="Excluir " class="infraImg" /></a>&nbsp;';
        }

        $strResultado .= '</td></tr>' . "\n";
    }

    $strResultado .= '
</table>';
}
