<?php
/**
 * Created by PhpStorm.
 * User: jhon.carvalho
 * Date: 06/10/2017
 * Time: 11:42
 */
require_once dirname(__FILE__) . '/../../SEI.php';
$idUnidadeAtual = SessaoSEI::getInstance()->getNumIdUnidadeAtual();
SessaoSEI::getInstance()->validarLink();
SessaoSEI::getInstance()->validarPermissao($_GET['acao']);
switch ($_GET['acao']) {
    case 'md_cor_expedicao_plp_listar' :

        $strTitulo = "PLPs Geradas para Expedição";
        $arrComandos[] = '<button type="button" accesskey="p" name="btnPesquisar" value="Pesquisar" onclick="pesquisar()" class="infraButton"><span class="infraTeclaAtalho">P</span>esquisar</button>';
        $arrComandos[] = '<button type="button" accesskey="i" name="btnImprimir" value="Imprimir" onclick="infraImprimirTabela()" class="infraButton"><span class="infraTeclaAtalho">I</span>mprimir</button>';
        $arrComandos[] = '<button type="button" accesskey="c" name="btnCancelar" id="btnCancelar" value="Cancelar" onclick="location.href=\'' . SessaoSEI::getInstance()->assinarLink('controlador.php?acao=procedimento_controlar&acao_origem=' . $_GET['acao']) . '\';" class="infraButton">Fe<span class="infraTeclaAtalho">c</span>har</button>';
        break;

    default:
        throw new InfraException("Ação '" . $_GET['acao'] . "' não reconhecida.");

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

function pesquisar(){
document.getElementById('frmPlpGerada').submit();
}


<?php
PaginaSEI::getInstance()->fecharJavaScript();
PaginaSEI::getInstance()->fecharHead();
PaginaSEI::getInstance()->abrirBody($strTitulo, 'onload="inicializar();"');
PaginaSEI::getInstance()->montarBarraComandosSuperior($arrComandos);
$objMdCorExpedicaoSolicitadaRN = new MdCorExpedicaoSolicitadaRN();
$arrObjMdCorExpedicaoSolicitadaDTO = new MdCorExpedicaoSolicitadaDTO();
$arrObjMdCorExpedicaoSolicitadaDTO->setNumIdUnidade($idUnidadeAtual);

$selServicoPostal= $selSituacaoPlp= 'null';
$inputCodigoRastreamento=$inputIdentificadorPLP="";
if(isset($_POST)){

    if(($_POST['identificadorPlp']!='null')&&($_POST['identificadorPlp']!= '')){
        $arrObjMdCorExpedicaoSolicitadaDTO->setNumCodigoPlp($_POST['identificadorPlp']);
        $inputIdentificadorPLP = $_POST['identificadorPlp'];
    }
    if(($_POST['selServicoPostal']!= 'null')&&($_POST['selServicoPostal']!= '')){
        $arrObjMdCorExpedicaoSolicitadaDTO->setNumIdMdCorServicoPostal($_POST['selServicoPostal']);
        $selServicoPostal = $_POST['selServicoPostal'];
    }
    if(($_POST['codigoRastreamento']!= 'null')&&($_POST['codigoRastreamento']!= '')){
        $arrObjMdCorExpedicaoSolicitadaDTO->setStrCodigoRastreamento($_POST['codigoRastreamento']);
        $inputCodigoRastreamento = $_POST['codigoRastreamento'];
    }
    if(($_POST['selSituacaoPlp']!= 'null')&&($_POST['selSituacaoPlp']!= '')){
        $selSituacaoPlp = $_POST['selSituacaoPlp'];
       $arrObjMdCorExpedicaoSolicitadaDTO->setStrStaPlp($_POST['selSituacaoPlp']);

    }

}
$strServicoPostal = MdCorPlpINT::montarSelectServicoPostal('null','&nbsp;',$selServicoPostal);
$strSituacaoPlp = MdCorPlpINT::montarSelectStaPlp('null','&nbsp;',$selSituacaoPlp);

$arrObjMdCorExpedicaoSolicitadaDTO->retTodos();

?>
<form id="frmPlpGerada" method="post" action="<?=PaginaSEI::getInstance()->formatarXHTML(SessaoSEI::getInstance()->assinarLink('controlador.php?acao='. $_GET['acao'] . '&acao_origem=' . $_GET['acao']))?>">
<div style="float: left">
    <div style="float: left">
        <div style="float: left">
            <label>Identificador da PLP:</label></br>
            <input type="text" name="identificadorPlp" value="<?= PaginaSEI::tratarHTML($inputIdentificadorPLP) ?>">
        </div>
        <div style="float: right; padding-left: 15px">
            <label>Serviço Postal:</label>
            </br><select tabindex="504" class="infraSelect"  name="selServicoPostal" id="selServicoPostal" onchange="pesquisar()">
                <?= $strServicoPostal?>
            </select>
        </div>
    </div>
    <div style="float: right">
        <div style="float: left; padding-left: 15px">
            <label>Código de Rastreamento:</label></br>
            <input type="text" name="codigoRastreamento" value="<?= PaginaSEI::tratarHTML($inputCodigoRastreamento) ?>">
        </div>
        <div style="float: right; padding-left: 15px">
            <label>Situação da PLP:</label>
            </br><select tabindex="504" class="infraSelect" name="selSituacaoPlp" id="selSituacaoPlp" onchange="pesquisar()">
                <?=$strSituacaoPlp?>
            </select>
        </div>
    </div>
</div>
</form>
<?php

#Configuração da Paginação
PaginaSEI::getInstance()->prepararOrdenacao($arrObjMdCorExpedicaoSolicitadaDTO,'CodigoPlp',InfraDTO::$TIPO_ORDENACAO_ASC);
PaginaSEI::getInstance()->prepararPaginacao($arrObjMdCorExpedicaoSolicitadaDTO,200);

$arrObjMdCorExpedicaoSolicitada = $objMdCorExpedicaoSolicitadaRN->retornarPlpGeradas($arrObjMdCorExpedicaoSolicitadaDTO);

PaginaSEI::getInstance()->processarPaginacao($arrObjMdCorExpedicaoSolicitadaDTO);

$numRegistro = count($arrObjMdCorExpedicaoSolicitada);

if($numRegistro >0) {
    $strTable = '';
    $strTable .= '</br><table width="99%" class="infraTable" summary="PlpGerada">';
    $strTable .= '<caption class="infraCaption">';
    $strTable .= PaginaSEI::getInstance()->gerarCaptionTabela('PLPs Geradas', $numRegistro);
    $strTable .= '</caption>';

#Cabeçalho da Tabela
    $strTable .= '<tr>';
    $strTable .= '<th class="infraTh" width="1%">' . PaginaSEI::getInstance()->getThCheck() . '</th>';
    $strTable .= '<th class="infraTh" width="20%">' . PaginaSEI::getInstance()->getThOrdenacao($arrObjMdCorExpedicaoSolicitadaDTO, 'Indentificador da Plp', 'CodigoPlp', $arrObjMdCorExpedicaoSolicitada) . '</th>';

    $strTable .= '<th class="infraTh" width="30%">Serviços Postais</th>';
    $strTable .= '<th class="infraTh" width="20%">Quantidade de Objetos</th>';
    $strTable .= '<th class="infraTh" width="30%">Situação</th>';
    $strTable .= '<th class="infraTh" width="5%">Ações</th>';
    $strTable .= '</tr>';

    $strObjPlp = '';
#linhas

    for($i = 0; $i < $numRegistro; $i++) {

        $strId = $arrObjMdCorExpedicaoSolicitada[$i]->getNumIdMdCorPlp();
        $strCodigoPlp = $arrObjMdCorExpedicaoSolicitada[$i]->getNumCodigoPlp();

        $strObjPlp .= '<tr class="infraTrClara">';
        $strObjPlp .= '<td>' . PaginaSEI::getInstance()->getTrCheck($i, $strId, $strCodigoPlp) . '</td>';
        $strObjPlp .= '<td>' . $arrObjMdCorExpedicaoSolicitada[$i]->getNumCodigoPlp() . '</td>';
        $strObjPlp .= '<td>' . $arrObjMdCorExpedicaoSolicitada[$i]->getStrNomeServicoPostal() . '</td>';
        $strObjPlp .= '<td class="d-flex align-items-center justify-content-center">'. $arrObjMdCorExpedicaoSolicitada[$i]->getNumQuantidadeObjeto(). '</td>';
        $strObjPlp .= '<td>'.$arrObjMdCorExpedicaoSolicitada[$i]->getStrNomeStaPlp().'</td>';

        $strObjPlp .= '<td style="text-align: center"><a onclick="location.href=\'' . SessaoSEI::getInstance()->assinarLink('controlador.php?acao=md_cor_expedicao_plp_consultar&idCorMdPlp=' . $strId . '&codigoPlp=' . $strCodigoPlp) . '\'"><img src="modulos/correios/imagens/svg/solicitacao_correios.svg?'.Icone::VERSAO.'" style="width: 24px; height: 24px" title="Expedir Objetos"/></a></td>';

        $strObjPlp .= '</tr>';
    }

    $strTable .= $strObjPlp;
    $strTable .= '</table>';
}
PaginaSEI::getInstance()->montarAreaTabela($strTable, $numRegistro);
//PaginaSEI::getInstance()->montarBarraComandosInferior($arrComandos);
PaginaSEI::getInstance()->fecharBody();
PaginaSEI::getInstance()->fecharHtml();

?>


