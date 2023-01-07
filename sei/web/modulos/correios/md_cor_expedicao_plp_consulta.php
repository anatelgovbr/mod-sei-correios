<?php

/**
 * Created by PhpStorm.
 * User: jhon.carvalho
 * Date: 04/10/2017
 * Time: 13:54
 */
require_once dirname(__FILE__) . '/../../SEI.php';

session_start();
$idCorMdPlp = 0;
$idUnidadeAtual = SessaoSEI::getInstance()->getNumIdUnidadeAtual();
SessaoSEI::getInstance()->validarLink();
SessaoSEI::getInstance()->validarPermissao($_GET['acao']);
switch ($_GET['acao']) {

    case 'md_cor_expedicao_plp_consultar':
        $identificadorPlp = $_GET['codigoPlp'];
        $idCorMdPlp = $_GET['idCorMdPlp'];
        $arrComandos[] = '<button type="submit" accesskey="i" name="btnImprimirDocumentoPricipalLote" value="ImprimirDocumentoPricipalLote" class="infraButton"><span class="infraTeclaAtalho">I</span>mprimir Documento Pricipal Lote</button>';
        $arrComandos[] = '<button type="submit" accesskey="o" name="sbmConcluirMdCorExpPlp" value="ConcluirExpediçãodaPLP" onclick="concluirExpedicao()" class="infraButton ">C<span class="infraTeclaAtalho">o</span>ncluir Expedição da PLP</button>';
        $arrComandos[] = '<button type="button" accesskey="c" name="btnCancelar" id="btnCancelar" value="Cancelar" onclick="location.href=\'' . SessaoSEI::getInstance()->assinarLink('controlador.php?acao=md_cor_expedicao_plp_listar&acao_origem=' . $_GET['acao']) . '\';" class="infraButton"><span class="infraTeclaAtalho">C</span>ancelar</button>';
        $strTitulo = "Expedir PLP - Identificador n° " . $identificadorPlp;
        break;
    default:
        throw new InfraException("Ação '" . $_GET['acao'] . "' não reconhecida.");
}

$objMdCorExpedicaoSolicitadaRN = new MdCorExpedicaoSolicitadaRN();
$arrObjMdCorExpedicaoSolicitadaDTO = new MdCorExpedicaoSolicitadaDTO();
$arrObjMdCorExpedicaoSolicitadaDTO->setNumIdUnidade($idUnidadeAtual);
$arrObjMdCorExpedicaoSolicitadaDTO->setNumIdMdCorPlp($idCorMdPlp);

#Configuração da Paginação
PaginaSEI::getInstance()->prepararOrdenacao($arrObjMdCorExpedicaoSolicitadaDTO, 'SiglaUnidade', InfraDTO::$TIPO_ORDENACAO_ASC);
PaginaSEI::getInstance()->prepararPaginacao($arrObjMdCorExpedicaoSolicitadaDTO, 200);


$arrayObjDTO = $objMdCorExpedicaoSolicitadaRN->retornarExpedicaoPlp($arrObjMdCorExpedicaoSolicitadaDTO);
PaginaSEI::getInstance()->processarPaginacao($arrObjMdCorExpedicaoSolicitadaDTO);
$numRegistro = count($arrayObjDTO);
$qtdObjetoAcessado =0;
$strResultado = '';
if ($numRegistro > 0) {
    $strResultado .= '</br></br><table width="99%" class="infraTable" summary="ExpedirPlp">';
    $strResultado .= '<caption class="infraCaption">';
    $strResultado .= PaginaSEI::getInstance()->gerarCaptionTabela('Expedição PLPs', $numRegistro);
    $strResultado .= '</caption>';

    #cabeçalho
    $strResultado .= '<tr>';
    $strResultado .= '<th class="infraTh" width="1%">' . PaginaSEI::getInstance()->getThCheck() . '</th>';
    $strResultado .= '<th class="infraTh" width="25%">' . PaginaSEI::getInstance()->getThOrdenacao($arrObjMdCorExpedicaoSolicitadaDTO, 'Unidade Solicitante', 'SiglaUnidade', $arrayObjDTO) . '</th>';
    $strResultado .= '<th class="infraTh" width="25%">' . PaginaSEI::getInstance()->getThOrdenacao($arrObjMdCorExpedicaoSolicitadaDTO, 'Documento Principal','NomeSerie',$arrayObjDTO).'</th>';
    $strResultado .= '<th class="infraTh" width="20%">'. PaginaSEI::getInstance()->getThOrdenacao($arrObjMdCorExpedicaoSolicitadaDTO, 'Processo','ProtocoloFormatado',$arrayObjDTO).'</th>';
    $strResultado .= '<th class="infraTh" width="10%">'. PaginaSEI::getInstance()->getThOrdenacao($arrObjMdCorExpedicaoSolicitadaDTO, 'Código de Rastreamento', 'CodigoRastreamento', $arrayObjDTO).'</th>';
    $strResultado .= '<th class="infraTh" width="10%">'. PaginaSEI::getInstance()->getThOrdenacao($arrObjMdCorExpedicaoSolicitadaDTO, 'Quantidade de Anexo','QuantidadeAnexo',$arrayObjDTO).'</th>';
    $strResultado .= '<th class="infraTh" width="10%">Ações</th>';
    $strResultado .= '</tr>';

    $strPlpTr = '';

    #Linhas
    for ($i = 0; $i < $numRegistro; $i++) {

        $strId = $arrayObjDTO[$i]->getNumIdMdCorPlp();


        $unidadeSolicitada = $arrayObjDTO[$i]->getStrSiglaUnidade() . '-' . $arrayObjDTO[$i]->getStrDescricaoUnidade();
        $strPlpTr .= '<tr class="infraTrClara">';
        $strPlpTr .= '<td>' . PaginaSEI::getInstance()->getTrCheck($i, $strId, $unidadeSolicitada) . '</td>';
        $strPlpTr .= '<td>' . $arrayObjDTO[$i]->getStrSiglaUnidade() . '-' . $arrayObjDTO[$i]->getStrDescricaoUnidade() . '</td>';
        $strPlpTr .= '<td>' . $arrayObjDTO[$i]->getStrNomeSerie() . ' (' . $arrayObjDTO[$i]->getStrProtocoloFormatadoDocumento() . ')</td>';
        $strPlpTr .= '<td>' . $arrayObjDTO[$i]->getStrProtocoloFormatado() . '</td>';
        $strPlpTr .= '<td>' . $arrayObjDTO[$i]->getStrCodigoRastreamento() . '</td>';
        $qtdAnexo = $arrayObjDTO[$i]->getNumQuantidadeAnexo() > 0 ? $arrayObjDTO[$i]->getNumQuantidadeAnexo() : 0;
        $strPlpTr .= '<td>' . $qtdAnexo . '</td>';
        if ($qtdAnexo == 0) {
            $strPlpTr .= '<td style="text-align: center"></td>';

        } else if ($arrayObjDTO[$i]->getStrSinObjetoAcessado() == 'N')
            $strPlpTr .= '<td style="text-align: center"><a href="' . SessaoSEI::getInstance()->assinarLink('controlador.php?acao=md_cor_expedicao_objeto_listar&acao_origem=' . $_GET['acao'] . '&acao_retorno=' . $_GET['acao'] . '&idMdCorExpedicaoSolicitada=' . $arrayObjDTO[$i]->getNumIdMdCorExpedicaoSolicitada()) . '"><img src="modulos/correios/imagens/svg/icon_carta.svg?'.Icone::VERSAO.'" style="width: 24px; height: 24px" title="Expedir Objetos" class="infraImg" alt="Expedir Objetos"></a></td>';
         else {
             $strPlpTr .= '<td style="text-align: center"><a href="' . SessaoSEI::getInstance()->assinarLink('controlador.php?acao=md_cor_expedicao_objeto_listar&acao_origem=' . $_GET['acao'] . '&acao_retorno=' . $_GET['acao'] . '&idMdCorExpedicaoSolicitada=' . $arrayObjDTO[$i]->getNumIdMdCorExpedicaoSolicitada()) . '"><img src="modulos/correios/imagens/svg/icon_carta_sem_marcacao.svg?'.Icone::VERSAO.'" style="width: 24px; height: 24px" title="Expedir Objetos" class="infraImg" alt="Expedir Objetos"></a></td>';
             $qtdObjetoAcessado ++;
         }
        $strPlpTr .= '</tr>';
    }

    $strResultado .= $strPlpTr;
    $strResultado .= '</table>';
}
PaginaSEI::getInstance()->montarDocType();
PaginaSEI::getInstance()->abrirHtml();
PaginaSEI::getInstance()->abrirHead();
PaginaSEI::getInstance()->montarMeta();
PaginaSEI::getInstance()->montarTitle(':: ' . PaginaSEI::getInstance()->getStrNomeSistema() . ' - ' . $strTitulo . ' ::');
PaginaSEI::getInstance()->montarStyle();
PaginaSEI::getInstance()->montarJavaScript();
PaginaSEI::getInstance()->abrirJavaScript();
?>
function concluirExpedicao(){
    if(<?=$numRegistro == $qtdObjetoAcessado?>){
        alert('oi');
    }
}

<?php

PaginaSEI::getInstance()->fecharJavaScript();
PaginaSEI::getInstance()->fecharHead();
PaginaSEI::getInstance()->abrirBody($strTitulo, 'onload="inicializar();"');
PaginaSEI::getInstance()->montarBarraComandosSuperior($arrComandos);
PaginaSEI::getInstance()->montarAreaTabela($strResultado, $numRegistro);
PaginaSEI::getInstance()->montarAreaDebug();
//PaginaSEI::getInstance()->montarBarraComandosInferior($arrComandos);
PaginaSEI::getInstance()->fecharBody();
PaginaSEI::getInstance()->fecharHtml();