<?php

/**
 * Created by PhpStorm.
 * User: jose.vieira <jose.vieira@cast.com.br>
 * Date: 04/10/2017
 * Time: 13:53
 */

try {
    require_once dirname(__FILE__) . '/../../SEI.php';

    SessaoSEI::getInstance()->validarLink();
    SessaoSEI::getInstance()->validarPermissao($_GET['acao']);

    $idUnidadeAtual = SessaoSEI::getInstance()->getNumIdUnidadeAtual();
    // Buscar itens do serviço postal
    $strItensSelServicoPostal = MdCorExpedicaoSolicitadaINT::montarSelectServicoPostalPLPNaoVinculada('null', '', $_POST['slServicoPostal']);
    // Buscar itens da unidade solicitante
    $strItensUnidadeSolicitante = MdCorExpedicaoSolicitadaINT::montarSelectUnidadeSolicitantePLPNaoVinculada("null", '', $_POST['slUnidadeSolicitante']);
    $post = $_POST;
    $strTabela = MdCorExpedicaoSolicitadaINT::montarTableSolicExpedicaoPendente($post, $idUnidadeAtual);

    $strTitulo = "Caixa de Entrada - Solicitações de Expedição Pendentes";
    $strUrlFechar = SessaoSEI::getInstance()->assinarLink('controlador.php?acao=procedimento_controlar&acao_origem=' . $_GET['acao']);
//
    /**
     * Montar array de comandos superior
     */
    $arrComandos[] = '<button type="button" accesskey="P" id="btnPesquisar" onclick="pesquisar()" class="infraButton">
                                    <span class="infraTeclaAtalho">P</span>esquisar
                              </button>';
    $arrComandos[] = '<button type="button" accesskey="N" id="btnGerarPlp" onclick="gerarPLP()" class="infraButton">
                                    <span class="infraTeclaAtalho">G</span>erar PLP
                              </button>';
    $arrComandos[] = '<button type="button" accesskey="I" id="btnImprimir" onclick="imprimir()" class="infraButton">
                                    <span class="infraTeclaAtalho">I</span>mprimir
                              </button>';
    $arrComandos[] = '<button type="button" accesskey="c" id="btnFechar" onclick="fechar()" class="infraButton">
                                    Fe<span class="infraTeclaAtalho">c</span>har
                              </button>';

    switch ($_GET['acao']) {

        //region Listar
        case 'md_cor_geracao_plp_listar':
            break;

        case 'md_cor_plp_correio_cadastro':
            try {
                $arrIdMdCorExpedicaoSolicitada = $_POST['chkSelecionado'];

                $objMdCorExpedicaoSolicitadaRN = new MdCorExpedicaoSolicitadaRN();
                $objMdCorExpedicaoSolicitadaRN->validarObjeto($arrIdMdCorExpedicaoSolicitada);

                $objMdCorPlpRN = new MdCorPlpRN();
                $arrRetorno = $objMdCorPlpRN->gerarPlpWebService($arrIdMdCorExpedicaoSolicitada);

                $idPlpGerada = $objMdCorPlpRN->alterarDadosPlpSolicitacao($arrRetorno);
                header('Location: ' . SessaoSEI::getInstance()->assinarLink('controlador.php?acao=md_cor_expedicao_plp_listar') . "#ID-" . $idPlpGerada);
                die;
            } catch (Exception $e) {
                PaginaSEI::getInstance()->processarExcecao($e);
            }
            break;
        //endregion

        #region Erro
        default:
            throw new InfraException("Ação '" . $_GET['acao'] . "' não reconhecida.");
        #endregion
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
include_once("md_cor_geracao_plp_lista_css.php");
PaginaSEI::getInstance()->fecharStyle();
PaginaSEI::getInstance()->montarJavaScript();
PaginaSEI::getInstance()->abrirJavaScript();
PaginaSEI::getInstance()->fecharJavaScript();
PaginaSEI::getInstance()->fecharHead();
PaginaSEI::getInstance()->abrirBody($strTitulo, 'onload="inicializar();"');
PaginaSEI::getInstance()->montarBarraComandosSuperior($arrComandos);
?>
    <form id="frmGerarPlp" method="post"
          action="<?= SessaoSEI::getInstance()->assinarLink('controlador.php?acao=' . $_GET['acao'] . '&acao_origem=' . $_GET['acao']) ?>">
        <title>Caixa de Entrada - Solicitações de Expedições Pendentes</title>
        <div class="row linha">
            <div class="col-sm-12 col-md-12 col-lg-12 col-xl-12">
                <div class="row">
                    <div class="col-sm-3 col-md-3 col-lg-3 col-xl-3">
                        <label id="lblServicoPostal" for="slServicoPostal" accesskey="o" class="infraLabelOpcional">Serviço
                            Postal:</label>
                        <select class="infraSelect form-control" name="slServicoPostal" id="slServicoPostal"
                                tabindex="<?= PaginaSEI::getInstance()->getProxTabDados(); ?>">
                            <?php echo $strItensSelServicoPostal; ?>
                        </select>
                    </div>
                    <div class="col-sm-3 col-md-3 col-lg-3 col-xl-3">
                        <label id="lblUnidadeSolicitante" for="slUnidadeSolicitante" accesskey="o"
                               class="infraLabelOpcional">Unidade
                            Solicitante:</label>
                        <select class="infraSelect form-control" name="slUnidadeSolicitante" id="slUnidadeSolicitante"
                                tabindex="<?= PaginaSEI::getInstance()->getProxTabDados(); ?>">
                            <?php echo $strItensUnidadeSolicitante; ?>
                        </select>
                    </div>
                    <div class="col-sm3 col-md-3 col-lg-3 col-xl-3">
                        <label id="lblDocumentoPrincipal" for="txtDocumentoPrincipal" accesskey="o"
                               class="infraLabelOpcional">Documento
                            Principal:</label>
                        <input type="text" id="txtDocumentoPrincipal" name="txtDocumentoPrincipal"
                               class="infraText form-control"
                               tabindex="<?= PaginaSEI::getInstance()->getProxTabDados() ?>"
                               value="<?php echo PaginaSEI::tratarHTML($_POST['txtDocumentoPrincipal']) ?>"
                        />
                    </div>
                    <div class="col-sm-3 col-md-3 col-lg-3 col-xl-3">
                        <label id="lblProcesso" for="txtProcesso" accesskey="o"
                               class="infraLabelOpcional">Processo:</label>
                        <input type="text" id="txtProcesso" name="txtProcesso" class="infraText form-control"
                               onkeypress="return infraMascara(this, event, '#####.######/####-##');"
                               tabindex="<?= PaginaSEI::getInstance()->getProxTabDados() ?>"
                               value="<?php echo PaginaSEI::tratarHTML($_POST['txtProcesso']) ?>"
                        />
                    </div>
                </div>

                <div class="row">
                    <div class="col-sm-12 col-md-12 col-lg-12 col-xl-12" style="padding-top: 40px;">
                        <?php $cServico = 0; ?>
                        <?php foreach ($strTabela as $nomeServico => $tabela) { ?>
                            <?php $qtdSolicitacaoPorServico = count($tabela); ?>

                            <div style="background-color: #999999; font-weight: 500; border-bottom: 1px #bfb7b7 solid; padding-top: 5px; padding-bottom:5px;">
                                <span class="spnExpandirTodos">
                                    <img id="imgExpandir" style="margin-bottom: -7px;"
                                         onclick="expandirTodos('div<?php echo $cServico; ?>', this)"
                                         src=" <?php echo PaginaSEI::getInstance()->getDiretorioSvgGlobal() . '/exibir.svg' ?>"/>
                                    <label class="infraLabelObrigatorio"><?php echo $nomeServico ?></label> - Pendentes de Expedição: <?php echo $qtdSolicitacaoPorServico ?> registro(s)
                                </span>
                                <div id="div<?php echo $cServico; ?>" style="display: none;">
                                    <?php
                                    $strResultado = '<table class="infraTable">';
                                    $strResultado .= '<tr>';
                                    $strResultado .= '<th style="text-align: center; width: 20px; background-color: #155f9b; color: white"><a selecionado="false" href="javascript:void(0);" id="lnkInfraCheck" onclick="selecionaTodos(\'servico' . $cServico . '\', this)" tabindex="1008">';
                                    $strResultado .= '<img src="/infra_css/svg/check.svg" id="imginfra1Check" title="Selecionar Tudo" alt="Selecionar Tudo" class="infraImg"></a>';
                                    $strResultado .= '</th>';
                                    $strResultado .= '<th class="infraTh" width="8%">Unidade Solicitante</th>';
                                    $strResultado .= '<th class="infraTh" width="10%">Data da Solicitação</th>';
                                    $strResultado .= '<th class="infraTh" width="13%">Documento Principal</th>';
                                    $strResultado .= '<th class="infraTh" width="20%">Processo</th>';
                                    $strResultado .= '<th class="infraTh">Destinatário</th>';
                                    $strResultado .= '<th class="infraTh" width="5%">Anexo</th>';
                                    $strResultado .= '<th class="infraTh" width="5%">Ação</th>';
                                    $strResultado .= '</tr>';

                                    $strPlpTr = '';
                                    $cLinha = 0;
                                    foreach ($tabela as $dadosTabela) {
                                        $strPlpTr .= '<tr class="infraTrClara">';
                                        $strPlpTr .= '<td><input type="checkbox" class="servico' . $cServico . ' infraCheckbox" name="chkSelecionado[]" id="solicitacao' . $dadosTabela['IdMdCorExpedicaoSolicitada'] . '" value="' . $dadosTabela['IdMdCorExpedicaoSolicitada'] . '" title=""><label for="solicitacao' . $dadosTabela['IdMdCorExpedicaoSolicitada'] . '"></label> </td>';
                                        $strPlpTr .= '<td>' . $dadosTabela['unidade'] . '</td>';
                                        $strPlpTr .= '<td>' . $dadosTabela['dtSolicitacao'] . '</td>';
                                        $strPlpTr .= '<td style="text-align: center;">';
                                        $strPlpTr .= $dadosTabela['docPrincipal'];
                                        $strPlpTr .= '</td>';
                                        $strPlpTr .= '<td style="text-align: center;">' . $dadosTabela['processo'] . '</td>';
                                        $strPlpTr .= '<td>' . $dadosTabela['destinatario'] . '</td>';
                                        $strPlpTr .= '<td style="text-align: center;">' . $dadosTabela['qtdAnexo'] . '</td>';
                                        $url = SessaoSEI::getInstance()->assinarLink('controlador.php?acao=md_cor_plp_selecionar_tipo_objeto&acao_origem=' . $_GET['acao'] . '&acao_retorno=' . $_GET['acao'] . '&id_md_cor_expedicao_solicitada=' . $dadosTabela['IdMdCorExpedicaoSolicitada']);
                                        $strPlpTr .= '<td style="text-align: center;"><a onclick="abrirModalSelecionarTipoObjeto(\'' . $url . '\')"><img src="modulos/correios/imagens/png/enviar_envelope.png" title="Selecionar Formato de Expedição do Objeto" alt="Selecionar Formato de Expedição do Objeto" class="infraImgAcoes"/></a>';
                                        $strPlpTr .= '<a href="' . PaginaSEI::getInstance()->formatarXHTML(SessaoSEI::getInstance()->assinarLink('controlador.php?acao=md_cor_expedicao_solicitada_consultar&acao_origem=' . $_GET['acao'] . '&acao_retorno=' . $_GET['acao'] . '&id_md_cor_expedicao_solicitada=' . $dadosTabela['IdMdCorExpedicaoSolicitada'])) . '" tabindex="' . PaginaSEI::getInstance()->getProxTabTabela() . '"><img src="' . PaginaSEI::getInstance()->getDiretorioSvgGlobal() . '/consultar.svg" title="Consultar Solicitação de Expedição" alt="Consultar Solicitação de Expedição" class="infraImg" /></a></td>';
                                        $strPlpTr .= '</tr>';
                                        $cLinha++;
                                    }
                                    $strResultado .= $strPlpTr;
                                    $strResultado .= '</table>';
                                    ?>
                                    <?php PaginaSEI::getInstance()->montarAreaTabela(trim($strResultado), 5); ?>
                                </div>
                                <?php $cServico++; ?>
                            </div>
                        <?php } ?>
                    </div>
                </div>
            </div>
        </div>
        <input type="hidden" name="hdnUrlImg" id="hdnUrlImg"
               value="<?php echo PaginaSEI::getInstance()->getDiretorioImagensGlobal() ?>"/></form>
<?php
require_once("md_cor_geracao_plp_lista_js.php");
PaginaSEI::getInstance()->fecharBody();
PaginaSEI::getInstance()->fecharHtml();