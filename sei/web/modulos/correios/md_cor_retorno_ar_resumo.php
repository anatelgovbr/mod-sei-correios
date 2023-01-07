<?php
/**
 * ANATEL
 *
 * 18/10/2017 - criado por Ellyson de Jesus Silva
 *
 */
try {
    require_once dirname(__FILE__) . '/../../SEI.php';

    session_start();

    SessaoSEI::getInstance()->validarLink();

    SessaoSEI::getInstance()->validarPermissao($_GET['acao']);

    /**
     * Montar array de comandos superior
     */
    $arrComandos = array();
    $arrComandos[] = '<button type="button" accesskey="c" id="btnFechar" onclick="location.href=\'' . SessaoSEI::getInstance()->assinarLink('controlador.php?acao=' . PaginaSEI::getInstance()->getAcaoRetorno() . '&acao_origem=' . $_GET['acao'] . PaginaSEI::getInstance()->montarAncora($_GET['id_md_cor_retorno_ar'])) . '\';" class="infraButton">
                                    Fechar
                              </button>';
    switch ($_GET['acao']) {
        case 'md_cor_retorno_ar_resumir':
            $idRetorno = $_GET['id_md_cor_retorno_ar'];

            $mdCorRetornoArRN = new MdCorRetornoArRN();
            $mdCorRetornoArDTO = new MdCorRetornoArDTO();
            $mdCorRetornoArDTO->retDthDataCadastro();
            $mdCorRetornoArDTO->setNumIdMdCorRetornoAr($idRetorno);

            $arrLabelStatus = $mdCorRetornoArRN->verificarQuantidade($idRetorno);
            $numRegistros = count($arrLabelStatus);


            $arrObjMdCorRetornoArDTO = $mdCorRetornoArRN->consultar($mdCorRetornoArDTO);
            $strTitulo = 'Resumo do Processamento - ' . $arrObjMdCorRetornoArDTO->getDthDataCadastro();
            $strSumarioTabela = 'Status de Processamento';
            $strCaptionTabela = 'Status de Processamento';

            $strResultado .= '<table width="100%" class="infraTable" summary="' . $strSumarioTabela . '">' . "\n";
            $strResultado .= '<caption class="infraCaption">' . PaginaSEI::getInstance()->gerarCaptionTabela($strCaptionTabela, $numRegistros) . '</caption>';
            $strResultado .= '<tr>';
            $strResultado .= '<th class="infraTh" style="width: 90%">Status</th>' . "\n";
            $strResultado .= '<th class="infraTh">Quantidade</th>' . "\n";
            $strResultado .= '<th class="infraTh" style="width: 110px !important;">Ações</th>';
            $strResultado .= '</tr>' . "\n";

            $strCssTr = '';
            foreach ($arrLabelStatus as $status => $quantidade) {
                $urlResumo = SessaoSEI::getInstance()->assinarLink('controlador.php?acao=md_cor_resumo_processamento&id_md_cor_retorno_ar=' . $_GET['id_md_cor_retorno_ar'] . '&status=' . $status);
                $strCssTr = $strCssTr == '<tr class="infraTrClara">' ? '<tr class="infraTrEscura">' : '<tr class="infraTrClara">';
                $strResultado .= $strCssTr;
                $strResultado .= '<td>';
                $strResultado .= MdCorRetornoArRN::$arrStatus[$status];
                $strResultado .= '</td>';
                $strResultado .= '<td>';
                $strResultado .= '<a style="cursor: pointer" onclick="abrirResumoProcessamento(\'' . $urlResumo . '\')">' . $quantidade . '</a>';
                $strResultado .= '</td>';
                $strResultado .= '<td>';
                // $strResultado .= '<a rel="button" style="cursor: pointer" onclick="abrirResumoProcessamento(\'' . $urlResumo . '\')"> <img src="modulos/correios/imagens/svg/associar.svg" title="Listar Dados do Processamento" alt="Listar Dados do Processamento" class="infraImg" /> </a>';
                $strResultado .= '<a rel="button" style="cursor: pointer" onclick="infraAbrirJanelaModal(\''.$urlResumo.'\', 1200, 600);"><img src="modulos/correios/imagens/svg/associar.svg?'.Icone::VERSAO.'" title="Listar Dados do Processamento" alt="Listar Dados do Processamento" class="infraImg" /> </a>';



                $strResultado .= '</td>';
                $strResultado .= '</tr>' . "\n";
            }

            $strResultado .= '</table>' . "\n";


            break;
        default:
            throw new InfraException("Ação '" . $_GET['acao'] . "' não reconhecida.");
    }
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
PaginaSEI::getInstance()->fecharStyle();
PaginaSEI::getInstance()->montarJavaScript();
require_once('md_cor_retorno_ar_resumo_js.php');

PaginaSEI::getInstance()->fecharHead();
PaginaSEI::getInstance()->abrirBody($strTitulo, 'onload="inicializar();"');

PaginaSEI::getInstance()->montarBarraComandosSuperior($arrComandos);
PaginaSEI::getInstance()->abrirAreaDados();
?>
<div class="row linha">
    <div class="col-sm-12 col-md-12 col-lg-12 col-xl-12 ">
        <?php
        PaginaSEI::getInstance()->montarAreaTabela($strResultado, $numRegistros);
        ?>
    </div>
</div>
<?php
PaginaSEI::getInstance()->fecharAreaDados();
PaginaSEI::getInstance()->fecharBody();
PaginaSEI::getInstance()->fecharHtml();
?>
