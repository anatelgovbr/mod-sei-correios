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

    PaginaSEI::getInstance()->setTipoPagina(InfraPagina::$TIPO_PAGINA_SIMPLES);

    /**
     * Montar array de comandos superior
     */
    $arrComandos = array();
    switch ($_GET['acao']) {
        case 'md_cor_resumo_processamento':
            $idRetorno = $_GET['id_md_cor_retorno_ar'];
            $status = $_GET['status'];

            if ($status != MdCorRetornoArRN::$STA_RETORNO_AR_NAO_PROCESSADO) {
                $arrComandos[] = '<button type="button" accesskey="p" id="btnFechar" onclick="pesquisar()" class="infraButton">
                                    Pesquisar
                              </button>';
            }

            $arrComandos[] = '<button type="button" accesskey="i" id="btnFechar" onclick="infraImprimirTabela();" class="infraButton">
                                    Imprimir
                              </button>';

            $arrComandos[] = '<button type="button" accesskey="c" id="btnFechar" onclick="window.close()" class="infraButton">
                                    Fechar
                              </button>';

            $mdCorRetornoArDocRN = new MdCorRetornoArDocRN();
            $mdCorRetornoArDocDTO = new MdCorRetornoArDocDTO();
            $mdCorRetornoArDocDTO->retNumIdMdCorRetornoArDoc();
            $mdCorRetornoArDocDTO->retStrNomeSerie();
            $mdCorRetornoArDocDTO->retStrCodigoRastreamento();
            $mdCorRetornoArDocDTO->retStrNumeroDocumento();
            $mdCorRetornoArDocDTO->retStrNumeroDocumentoAR();
            $mdCorRetornoArDocDTO->retNumIdDocumentoPrincipal();
            $mdCorRetornoArDocDTO->retStrNomeArquivoPdf();
            $mdCorRetornoArDocDTO->retStrUnidadePrincipal();
            $mdCorRetornoArDocDTO->retStrProtocoloFormatadoDocumento();
            $mdCorRetornoArDocDTO->retStrOrgaoPrincipal();
            $mdCorRetornoArDocDTO->retStrCodigoPlp();
            $mdCorRetornoArDocDTO->retStrProtocoloFormatado();
            $mdCorRetornoArDocDTO->retStrProtocoloFormatadoDocumento();
            $mdCorRetornoArDocDTO->retStrDescricaoSubStatusProcess();
            $mdCorRetornoArDocDTO->retNumIdDocumentoAr();
            $mdCorRetornoArDocDTO->setNumIdMdCorRetornoAr($idRetorno);
            $mdCorRetornoArDocDTO->setNumIdStatusProcess($status);

            $slRetorno = 'T';
            if (isset($_POST['slTipoRetorno'])) {
                $slRetorno = $_POST['slTipoRetorno'];

                if ($slRetorno == 'A') {
                    $mdCorRetornoArDocDTO->adicionarCriterio(array('IdMdCorParamArInfrigencia'), array(InfraDTO::$OPER_IGUAL), array(NULL));
                }

                if ($slRetorno == 'O') {
                    $mdCorRetornoArDocDTO->adicionarCriterio(array('IdMdCorParamArInfrigencia'), array(InfraDTO::$OPER_DIFERENTE), array(NULL));
                }
            }


            $arrMdCorRetornoArDocDTO = $mdCorRetornoArDocRN->listar($mdCorRetornoArDocDTO);
            $numRegistros = count($arrMdCorRetornoArDocDTO);

            $strTitulo = 'ARs Retornados - ' . MdCorRetornoArRN::$arrStatus[$status];
            $strSumarioTabela = 'Dados do Processamento';
            $strCaptionTabela = 'Dados do Processamento';

            $strResultado .= '<table width="100%" class="infraTable" summary="' . $strSumarioTabela . '">' . "\n";
            $strResultado .= '<caption class="infraCaption">' . PaginaSEI::getInstance()->gerarCaptionTabela($strCaptionTabela, $numRegistros) . '</caption>';
            $strResultado .= '<tr>';
            $strResultado .= '<th class="infraTh" width="1%">' . PaginaSEI::getInstance()->getThCheck() . '</th>' . "\n";
            $strResultado .= '<th class="infraTh">Arquivo</th>' . "\n";

            $strResultado .= '<th class="infraTh" >PLP</th>' . "\n";

            if ($status != MdCorRetornoArRN::$STA_RETORNO_AR_NAO_PROCESSADO) {
                $strResultado .= '<th class="infraTh">Ar Retornado</th>' . "\n";
            } else {
                $strResultado .= '<th class="infraTh">Motivo</th>' . "\n";
            }

            $strResultado .= '<th class="infraTh" >Orgão</th>' . "\n";
            $strResultado .= '<th class="infraTh" >Unidade</th>' . "\n";
            $strResultado .= '<th class="infraTh">Processo</th>' . "\n";
            $strResultado .= '<th class="infraTh" >Documento Principal</th>' . "\n";
            $strResultado .= '<th class="infraTh" >Rastreamento</th>' . "\n";
//      }
            $strResultado .= '</tr>' . "\n";

            $strCssTr = '';
            foreach ($arrMdCorRetornoArDocDTO as $chave => $mdCorRetornoArDocDTO) {
                $strCssTr = $strCssTr == '<tr class="infraTrClara">' ? '<tr class="infraTrEscura">' : '<tr class="infraTrClara">';
                $strResultado .= $strCssTr;
                $strResultado .= '<td valign="top">' . PaginaSEI::getInstance()->getTrCheck($chave, $mdCorRetornoArDocDTO->getNumIdMdCorRetornoArDoc(), $mdCorRetornoArDocDTO->getNumIdMdCorRetornoArDoc()) . '</td>';
                $strResultado .= '<td>';
                $strResultado .= $mdCorRetornoArDocDTO->getStrNomeArquivoPdf();
                $strResultado .= '</td>';

                $strResultado .= '<td>';
                $strResultado .= $mdCorRetornoArDocDTO->getStrCodigoPlp();

                if ($status != MdCorRetornoArRN::$STA_RETORNO_AR_NAO_PROCESSADO) {
                    $strResultado .= '<td>';
                    $strResultado .= 'AR ' . $mdCorRetornoArDocDTO->getStrNumeroDocumentoAR() . ' (SEI nº ' . str_pad($mdCorRetornoArDocDTO->getNumIdDocumentoAr(), 7, 0, STR_PAD_LEFT) . ')';
                    $strResultado .= '</td>';
                } else {
                    $strResultado .= '<td>';
                    $strResultado .= $mdCorRetornoArDocDTO->getStrDescricaoSubStatusProcess();
                    $strResultado .= '</td>';
                }

                $strResultado .= '<td>';
                $strResultado .= $mdCorRetornoArDocDTO->getStrOrgaoPrincipal();
                $strResultado .= '</td>';
                $strResultado .= '<td>';
                $strResultado .= $mdCorRetornoArDocDTO->getStrUnidadePrincipal();
                $strResultado .= '</td>';
                $strResultado .= '<td>';
                $strResultado .= $mdCorRetornoArDocDTO->getStrProtocoloFormatado();
                $strResultado .= '</td>';
                $strResultado .= '<td>';
                $strUrlDocumento = SessaoSEI::getInstance()->assinarLink('controlador.php?acao=procedimento_trabalhar&id_documento=' . $mdCorRetornoArDocDTO->getNumIdDocumentoPrincipal());
                $strResultado .= $mdCorRetornoArDocDTO->getStrNomeSerie() . ' ' . $mdCorRetornoArDocDTO->getStrNumeroDocumento() . ' <a class="protocoloNormal" style="font-size: 1.0em !important; font-size:1em" href="'.$strUrlDocumento.'" target="_blank">( ' . $mdCorRetornoArDocDTO->getStrProtocoloFormatadoDocumento() . ')</a>';


                $strResultado .= '<td>';
                $strResultado .= $mdCorRetornoArDocDTO->getStrCodigoRastreamento();

                $strResultado .= '</td>';
//        }
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
PaginaSEI::getInstance()->abrirAreaDados('4em');
if ($status != MdCorRetornoArRN::$STA_RETORNO_AR_NAO_PROCESSADO) {
    ?>
    <form id="frmConsulta" method="post" action="">
        <div class="row">
            <div class="col-4">
                <div class="form-group">
                    <label id="lblslTipoRetorno" for="slTipoRetorno" accesskey="o" class="infraLabelOpcional">
                        Tipo de Retorno:
                    </label>
                    <select id="slTipoRetorno" name="slTipoRetorno" class="infraSelect form-control">
                        <option value="T" <?php echo $slRetorno == 'T' ? 'selected="selected"' : null ?>>TODOS</option>
                        <option value="A" <?php echo $slRetorno == 'A' ? 'selected="selected"' : null ?>>AR RETORNADO</option>
                        <option value="O" <?php echo $slRetorno == 'O' ? 'selected="selected"' : null ?>>OBJETOS RETORNADOS</option>
                    </select>
                </div>
            </div>
        </div>
    </form>
    <?php
}
PaginaSEI::getInstance()->fecharAreaDados();
PaginaSEI::getInstance()->abrirAreaDados();
?>
<div class="row">
    <div class="col-12">
        <div class="table-responsive">
            <? PaginaSEI::getInstance()->montarAreaTabela($strResultado, $numRegistros) ?>
        </div>
    </div>
</div>
<?
PaginaSEI::getInstance()->fecharAreaDados();
PaginaSEI::getInstance()->fecharBody();
PaginaSEI::getInstance()->fecharHtml();
?>
