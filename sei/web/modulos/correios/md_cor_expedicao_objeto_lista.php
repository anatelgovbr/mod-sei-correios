<?php
/**
 * Created by PhpStorm.
 * User: jhon.carvalho
 * Date: 05/10/2017
 * Time: 15:04
 */
require_once dirname(__FILE__) . '/../../SEI.php';
session_start();
if(isset($_GET['idMdCorExpedicaoSolicitada'])){
    $idMdCorExpedicaoSolicitada = $_GET['idMdCorExpedicaoSolicitada'];
    $objMdCorExpedicaoFomatoRN = new MdCorExpedicaoFormatoRN();
    $objMdCorExpedicaoFomatoDTO = new MdCorExpedicaoSolicitadaDTO();
    $objMdCorExpedicaoFomatoDTO->retTodos();
}
switch ($_GET['acao']){
    case 'md_cor_expedicao_objeto_listar':
        $strTitulo = "Expedir Objeto";
        $arrComandos[]= '<button type="button" accesskey="g" class="infraButton" name="btnGerarZip" id="btnGerarZip" value="btnGerarZip" onclick="testeBtn(this.value)"><span class="infraTeclaAtalho">G</span>erar ZIP</button>';
        $arrComandos[]= '<button type="button" accesskey="i" class="infraButton" name="btnImpArqLote" id="btnImpArqLote" value="btnImpArqLote" onclick="infraImprimirTabela()"><span class="infraTeclaAtalho">I</span>mprimir Arquivos Lote</button>';
        $arrComandos[]= '<button type="button" accesskey="m" class="infraButton" name="btnImpArLote" id="btnImpArLote" value="btnImpArLote" onclick="testeBtn(this.value)">I<span class="infraTeclaAtalho">m</span>primir AR Lote</button>';
        $arrComandos[]= '<button type="button" accesskey="p" class="infraButton" name="btnImpRotuloLote" id="btnImpRotuloLote" value="btnImpRotuloLote" onclick="testeBtn(this.value)">Im<span class="infraTeclaAtalho">p</span>rimir Rótulo do Envelope Lote</button>';
        $arrComandos[]= '<button type="button" accesskey="c" class="infraButton" name="btnFechar" id="btnFechar" value="Fechar" onclick="javascript:history.back()" >Fe<span class="infraTeclaAtalho">c</span>har</button>';
        break;
    default:
        throw new InfraException("Ação '" . $_GET['acao'] . "' não reconhecida.");
}

#Configuração da Paginação
PaginaSEI::getInstance()->prepararOrdenacao($objMdCorExpedicaoFomatoDTO, 'ProtocoloFormatado', InfraDTO::$TIPO_ORDENACAO_ASC);
PaginaSEI::getInstance()->prepararPaginacao($objMdCorExpedicaoFomatoDTO, 200);

$objMdCorExpedicaoFomato = $objMdCorExpedicaoFomatoRN->retornarFormatoExpedicao($idMdCorExpedicaoSolicitada);
PaginaSEI::getInstance()->processarPaginacao($objMdCorExpedicaoFomatoDTO);
$numRegistro = count($objMdCorExpedicaoFomato); 

$strTable = '';
if($numRegistro > 0) {
    $strTable .= '</br></br><table width="99%" class="infraTable" summary="ExpedirObejto">';
    $strTable .= '<caption class="infraCaption">';
    $strTable .= PaginaSEI::getInstance()->gerarCaptionTabela('Expedição de Objetos', $numRegistro);
    $strTable .= '</caption>';
    $strTable .= '<tr>';
    $strTable .= '<th class="infraTh" width="1%">' . PaginaSEI::getInstance()->getThCheck() . '</th>';
    $strTable .= '<th class="infraTh" width="25%">' . PaginaSEI::getInstance()->getThOrdenacao($objMdCorExpedicaoFomatoDTO, 'Documento', 'ProtocoloFormatado', $objMdCorExpedicaoFomato) . '</th>';

    //$strTable.= '<th class="infraTh" width="15%">Documento</th>';
    $strTable .= '<th class="infraTh" width="30%">Formato de Expedição</th>';
    $strTable .= '<th class="infraTh" width="3%" >Impressão</th>';
    $strTable .= '<th class="infraTh" width="30%">Justificativa</th>';
    $strTable .= '<th class="infraTh" width="8%">Ações</th>';
    $strTable .= '</tr>';

    $strObjTr = '';

    for ($i = 0; $i < $numRegistro; $i++) {
        $strId = $objMdCorExpedicaoFomato[$i]->getNumIdMdCorExpedicaoFormato();
        $strDocumento = $objMdCorExpedicaoFomato[$i]->getStrProtocoloFormatado();
        $strObjTr .= '<tr class="infraTrClara">';
        $strObjTr .= '<td>'.PaginaSEI::getInstance()->getTrCheck($i, $strId, $strDocumento).'</td>';

        $strObjTr .= '<td>' .$strDocumento. '</td>';
        if ($objMdCorExpedicaoFomato[$i]->getStrFormaExpedicao() === 'I')
            $strObjTr .= '<td>Impressão</td>';
        else
            $strObjTr .= '<td>Gravação de midia</td>';
        switch ($objMdCorExpedicaoFomato[$i]->getStrImpressao()) {
            case 'P':
                $strObjTr .= '<td style="text-align: center"><img src="modulos/correios/imagens/svg/impressao.svg?'.Icone::VERSAO.'" class="infraImgAcoes"></td>';
                break;
            case 'C':
                $strObjTr .= '<td style="text-align: center"><img src="modulos/correios/imagens/svg/impressao_colororida.svg'.Icone::VERSAO.'" class="infraImgAcoes"></td>';
                break;
            case 'G':
                $strObjTr .= '<td style="text-align: center"><img src="modulos/correios/imagens/svg/media.svg'.Icone::VERSAO.'" class="infraImgAcoes mr-1" ></td>';
                break;
        }
        $strObjTr .= '<td>' . $objMdCorExpedicaoFomato[$i]->getStrJustificativa() . '</td>';

        if ($objMdCorExpedicaoFomato[$i]->getStrFormaExpedicao() === 'I')
            $strObjTr .= '<td style="text-align: center"><a><img src="modulos/correios/imagens/svg/impressao.svg?'.Icone::VERSAO.'"></a> <a><img src="modulos/correios/imagens/svg/impressao_ar.svg'.Icone::VERSAO.'"></a> <a><img src="modulos/correios/imagens/svg/resumo_processamento.svg'.Icone::VERSAO.'"></a></td>';
        else
            $strObjTr .= '<td style="text-align: center"><a><img src="modulos/correios/imagens/svg/download.svg?'.Icone::VERSAO.'" ></a> <a><img src="modulos/correios/imagens/svg/impressao_ar.svg'.Icone::VERSAO.'"></a> <a><img src="modulos/correios/imagens/svg/resumo_processamento.svg'.Icone::VERSAO.'"></a></td>';

        $strObjTr .= '</tr>';
    }
    $strTable .= $strObjTr;
    $strTable .= '</table>';
}

PaginaSEI::getInstance()->montarDocType();
PaginaSEI::getInstance()->abrirHtml();
PaginaSEI::getInstance()->abrirHead();
PaginaSEI::getInstance()->montarMeta();
PaginaSEI::getInstance()->montarTitle(':: ' . PaginaSEI::getInstance()->getStrNomeSistema() . ' - ' . $strTitulo . ' ::');
PaginaSEI::getInstance()->montarStyle();
PaginaSEI::getInstance()->abrirStyle();
?>
 .infraImgAcoes{
     width: 20px;
}
<?php
PaginaSEI::getInstance()->fecharStyle();
PaginaSEI::getInstance()->montarJavaScript();
PaginaSEI::getInstance()->abrirJavaScript();
?>
function testeBtn(params){
alert(params)
    }

<?php
PaginaSEI::getInstance()->fecharJavaScript();
PaginaSEI::getInstance()->fecharHead();
PaginaSEI::getInstance()->abrirBody($strTitulo, 'onload="inicializar();"');
PaginaSEI::getInstance()->montarBarraComandosSuperior($arrComandos);
PaginaSEI::getInstance()->montarAreaTabela($strTable, 3);
PaginaSEI::getInstance()->montarAreaDebug();
//PaginaSEI::getInstance()->montarBarraComandosInferior($arrComandos);
PaginaSEI::getInstance()->fecharBody();
PaginaSEI::getInstance()->fecharHtml();


