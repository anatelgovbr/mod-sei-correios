<?
/**
 * TRIBUNAL REGIONAL FEDERAL DA 4ª REGIÃO
 *
 * 22/12/2016 - criado por Wilton Júnior
 *
 * Versão do Gerador de Código: 1.39.0
 *
 * Versão no SVN: $Id$
 */

try {
    require_once dirname(__FILE__) . '/../../SEI.php';

    session_start();

    //////////////////////////////////////////////////////////////////////////////
//    InfraDebug::getInstance()->setBolLigado(false);
//    InfraDebug::getInstance()->setBolDebugInfra(true);
//    InfraDebug::getInstance()->limpar();
    //////////////////////////////////////////////////////////////////////////////

    SessaoSEI::getInstance()->validarLink();

    PaginaSEI::getInstance()->verificarSelecao('md_cor_contrato_selecionar');

    SessaoSEI::getInstance()->validarPermissao($_GET['acao']);

    $arrComandos = array();
    $mdCorTipoCorrespondencia = MdCorTipoCorrespondencINT::montarSelectIdMdCorTipoCorrespondenc('null', '', 'null');

    switch ($_GET['acao']) {
        case 'md_cor_parametro_ar_cadastrar':
            $arrComboUnidade = null;
            $objEditorRN = new EditorRN();
            $objEditorDTO = new EditorDTO();
            $objEditorDTO->setNumTamanhoEditor(220);
            $objEditorDTO->setStrNomeCampo('txaConteudo');
            $objEditorDTO->setStrSinSomenteLeitura('N');
            $retEditor = $objEditorRN->montarSimples($objEditorDTO);

            $strLinkTipoDocumentoSelecao = SessaoSEI::getInstance()->assinarLink('controlador.php?acao=md_cor_serie_exp_selecionar&tipo_selecao=1&id_object=objLupaTipoDocumento&cobranca=true');
            $strLinkTipoDocumentoObjetoDevolvidoSelecao = SessaoSEI::getInstance()->assinarLink('controlador.php?acao=md_cor_serie_exp_selecionar&tipo_selecao=1&id_object=objLupaTipoDocumentoObjetoDevolvido&cobranca=true');
            $strLinkTipoDocumentoCobrancaSelecao = SessaoSEI::getInstance()->assinarLink('controlador.php?acao=md_cor_serie_exp_selecionar&tipo_selecao=1&id_object=objLupaTipoDocumentoCobranca');
            $strTitulo = 'Parâmetros para Retorno da AR';
            $arrComandos[] = '<button type="button" accesskey="S" name="sbmParametro" value="Salvar" onclick="enviarFormulario();" class="infraButton"><span class="infraTeclaAtalho">S</span>alvar</button>';
            $arrComandos[] = '<button type="button" accesskey="C" name="btnCancelar" id="btnCancelar" value="Cancelar" onclick="location.href=\'' . SessaoSEI::getInstance()->assinarLink('controlador.php?acao=' . PaginaSEI::getInstance()->getAcaoRetorno() . '&acao_origem=' . $_GET['acao']) . '\';" class="infraButton"><span class="infraTeclaAtalho">C</span>ancelar</button>';

            $objMdCorParametroArDTO = new MdCorParametroArDTO();
            $objMdCorParametroArDTO->retNumIdMdCorParametroAr();
            $objMdCorParametroArDTO->retStrNuDiasRetornoAr();
            $objMdCorParametroArDTO->retStrNuDiasCobrancaAr();
            $objMdCorParametroArDTO->retNumIdSerie();
            $objMdCorParametroArDTO->retStrNomeArvore();
            $objMdCorParametroArDTO->retStrNomeSerie();
            $objMdCorParametroArDTO->retNumIdSerieDevolvido();
            $objMdCorParametroArDTO->retStrNomeSerieObjetoDevolvido();
            $objMdCorParametroArDTO->retNumIdTipoConferencia();
            $objMdCorParametroArDTO->retStrNomeArvoreDevolvido();
            $objMdCorParametroArDTO->retNumIdTipoConferenciaDevolvido();
            $objMdCorParametroArDTO->retNumIdSerieCobranca();
            $objMdCorParametroArDTO->retStrNomeSerieCobranca();
            $objMdCorParametroArDTO->retNumIdProcedimentoCobranca();
            $objMdCorParametroArDTO->retStrProtocoloFormatadoCobranca();
            $objMdCorParametroArDTO->retNumIdUnidadeCobranca();
            $objMdCorParametroArDTO->retStrModeloCobranca();
            $objMdCorParametroArDTO->retNumIdContato();
            $objMdCorParametroArDTO->retStrNomeContato();

            $objMdCorParametroArRN = new MdCorParametroArRN();
            $arrObjMdCorParametroArDTO = $objMdCorParametroArRN->consultar($objMdCorParametroArDTO);

            if (!is_null($arrObjMdCorParametroArDTO->getNumIdProcedimentoCobranca())) {
                $arrComboUnidade = MdCorParametroArINT::montarSelectUnidadeGeradora('NULL', ' ', $arrObjMdCorParametroArDTO->getNumIdUnidadeCobranca(), $arrObjMdCorParametroArDTO->getNumIdProcedimentoCobranca());
            }


            $idParametro = $arrObjMdCorParametroArDTO->getNumIdMdCorParametroAr();

            $objMdCorParamArInfrigenRN = new MdCorParamArInfrigenRN();
            $objMdCorParamArInfrigenDTO = new MdCorParamArInfrigenDTO();
            $objMdCorParamArInfrigenDTO->retNumIdMdCorParamArInfrigencia();
            $objMdCorParamArInfrigenDTO->retStrSinStatusRetornoDocumento();
            $objMdCorParamArInfrigenDTO->retStrMotivoInfrigencia();
            $objMdCorParamArInfrigenDTO->retStrSinInfrigencia();
            $objMdCorParamArInfrigenDTO->setStrSinAtivo('S');
            $objMdCorParamArInfrigenDTO->setNumIdMdCorParametroAr($idParametro);
            $arrObjMdCorParamArInfrigenRN = $objMdCorParamArInfrigenRN->listar($objMdCorParamArInfrigenDTO);


            if (isset($_POST['txtNuDiaRetorno'])) {
                try {
                    if (!is_null($arrObjMdCorParametroArDTO)) {
                        $objMdCorParametroArDTO = $arrObjMdCorParametroArDTO;
                    }
                    $objMdCorParametroArDTO->setStrNuDiasRetornoAr($_POST['txtNuDiaRetorno']);
                    $objMdCorParametroArDTO->setStrNuDiasCobrancaAr($_POST['txtNuDiCobranca']);
                    $objMdCorParametroArDTO->setNumIdSerie($_POST['slTipoDocumento']);
                    $objMdCorParametroArDTO->setStrNomeArvore($_POST['txtNoArvore']);
                    $objMdCorParametroArDTO->setNumIdTipoConferencia($_POST['slTipoConferencia']);
                    $objMdCorParametroArDTO->setNumIdSerieDevolvido($_POST['slTipoDocumentoObjetoDevolvido']);
                    $objMdCorParametroArDTO->setStrNomeArvoreDevolvido($_POST['txtNoArvoreObjetoDevolvido']);
                    $objMdCorParametroArDTO->setNumIdTipoConferenciaDevolvido($_POST['slTipoConferenciaObjetoDevolvido']);
                    $objMdCorParametroArDTO->setNumIdSerieCobranca($_POST['slTipoDocumentoCobranca']);
                    $objMdCorParametroArDTO->setNumIdProcedimentoCobranca($_POST['hdnIdProcedimento']);
                    $objMdCorParametroArDTO->setNumIdUnidadeCobranca($_POST['txtUnidadeGeradora']);
                    $objMdCorParametroArDTO->setStrModeloCobranca($_POST['txaConteudo']);
                    $objMdCorParametroArDTO->setNumIdContato($_POST['hdnIdDestinatario']);

                    if (is_null($arrObjMdCorParametroArDTO)) {
                        $objMdCorParametroArRN = $objMdCorParametroArRN->cadastrar($objMdCorParametroArDTO);
                    } else {
                        $objMdCorParametroArRN = $objMdCorParametroArRN->alterar($objMdCorParametroArDTO);
                    }

                    $arrMotivo = PaginaSEIExterna::getInstance()->getArrItensTabelaDinamica($_POST['hdnTbMotivo']);


                    $objMdCorParamArInfrigenDTO = new MdCorParamArInfrigenDTO();
                    $objMdCorParamArInfrigenDTO->retNumIdMdCorParamArInfrigencia();
                    $objMdCorParamArInfrigenDTO->retStrSinInfrigencia();
                    $objMdCorParamArInfrigenDTO->retStrMotivoInfrigencia();
                    $objMdCorParamArInfrigenDTO->retStrSinStatusRetornoDocumento();
                    $objMdCorParamArInfrigenDTO->setStrSinAtivo('S');
                    $objMdCorParamArInfrigenDTO->retNumIdMdCorParamArInfrigencia();
                    $arrObjMdCorParamArInfrigenDTO = $objMdCorParamArInfrigenRN->listar($objMdCorParamArInfrigenDTO);

                    $arrCadastrado = [];

                    $objInfraException = new InfraException();
                    foreach ($arrObjMdCorParamArInfrigenDTO as $resultado) {
                        $permissaoExclusao = empty($resultado->getStrSinStatusRetornoDocumento()) ? true : false;
                        $excluido = array_search($resultado->getNumIdMdCorParamArInfrigencia(), array_column($arrMotivo, 4));

                        if ($excluido === false) {
                            if (!$permissaoExclusao) {
                                $objInfraException->adicionarValidacao('Motivo de Objeto Devolvido "' . $resultado->getStrMotivoInfrigencia() . '" não pode ser excluído, por já está vinculado ao processamento de retorno de AR.');
                                $objInfraException->lancarValidacoes();
                            } else {
                                $objMdCorParamArInfrigenDTO = new MdCorParamArInfrigenDTO();
                                $objMdCorParamArInfrigenDTO->setStrSinAtivo('N');
                                $objMdCorParamArInfrigenDTO->setNumIdMdCorParamArInfrigencia($resultado->getNumIdMdCorParamArInfrigencia());
                                $objMdCorParamArInfrigenRN->alterar($objMdCorParamArInfrigenDTO);
                            }
                        }
                    }

                    foreach ($arrMotivo as $motivo) {
                        $statusInfrigencia = $motivo[2] == 'true' ? 'S' : 'N';
                        $stUtilizado = !empty($motivo[3]) ? true : false;
                        $idMotivo = $motivo[4] == 'null' ? true : false;

                        if ($idMotivo) {
                            $objMdCorParamArInfrigenDTO = new MdCorParamArInfrigenDTO();
                            $objMdCorParamArInfrigenDTO->setNumIdMdCorParametroAr($idParametro);
                            $objMdCorParamArInfrigenDTO->setStrSinAtivo('S');
                            $objMdCorParamArInfrigenDTO->setStrSinInfrigencia($statusInfrigencia);
                            $objMdCorParamArInfrigenDTO->setStrMotivoInfrigencia($motivo[0]);
                            $objMdCorParamArInfrigenRN->cadastrar($objMdCorParamArInfrigenDTO);
                        }

                    }


                    PaginaSEI::getInstance()->adicionarMensagem('Parâmetros salvos com sucesso.', InfraPagina::$TIPO_MSG_AVISO);
                    header('Location: ' . SessaoSEI::getInstance()->assinarLink('controlador.php?acao=' . $_GET['acao'] . '&acao_origem=' . $_GET['acao']));
                } catch (Exception $e) {
                    PaginaSEI::getInstance()->processarExcecao($e);
                }
            }

            $valorSelect = null;
            $valorSelectObjetoDevolvido = null;
            $arrTipoDocumento = [];
            $arrTipoDocumentoObjetoDevolvido = [];
            $arrTipoDocumentoCobranca = [];
            if (!is_null($arrObjMdCorParametroArDTO)) {
                $valorSelect = $arrObjMdCorParametroArDTO->getNumIdTipoConferencia();
                $arrTipoDocumento[$arrObjMdCorParametroArDTO->getNumIdSerie()] = $arrObjMdCorParametroArDTO->getStrNomeSerie();
                $valorSelectObjetoDevolvido = $arrObjMdCorParametroArDTO->getNumIdTipoConferenciaDevolvido();
                $arrTipoDocumentoObjetoDevolvido[$arrObjMdCorParametroArDTO->getNumIdSerieDevolvido()] = $arrObjMdCorParametroArDTO->getStrNomeSerieObjetoDevolvido();
                $arrTipoDocumentoCobranca[$arrObjMdCorParametroArDTO->getNumIdSerieCobranca()] = $arrObjMdCorParametroArDTO->getStrNomeSerieCobranca();
            }


            $tipoConferenciaInt = TipoConferenciaINT::montarSelectDescricao('NULL', '', $valorSelect);
            $tipoConferenciaObjetoDevolvidoInt = TipoConferenciaINT::montarSelectDescricao('NULL', '', $valorSelectObjetoDevolvido);

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
PaginaSEI::getInstance()->montarTitle(PaginaSEI::getInstance()->getStrNomeSistema() . ' - ' . $strTitulo);
PaginaSEI::getInstance()->montarStyle();
PaginaSEI::getInstance()->abrirStyle();
include_once('md_cor_parametro_ar_cadastro_css.php');
PaginaSEI::getInstance()->fecharStyle();
PaginaSEI::getInstance()->montarJavaScript();
echo $retEditor->getStrInicializacao();
PaginaSEI::getInstance()->fecharHead();
PaginaSEI::getInstance()->abrirBody($strTitulo, 'onload="inicializar();"');
?>
    <form id="frmMdCorParametroCadastro" method="post"
          action="<?= SessaoSEI::getInstance()->assinarLink('controlador.php?acao=' . $_GET['acao'] . '&acao_origem=' . $_GET['acao']) ?>">
        <?
        PaginaSEI::getInstance()->montarBarraComandosSuperior($arrComandos);
        ?>
        <div class="row linha">
            <div class="col-sm-12 col-md-12 col-lg-12 col-xl-12">
                <div class="row">
                    <div class="col-sm-6 col-md-6 col-lg-6 col-xl-6">
                        <label id="lblNuDiasRetorno" for="txtNuDiaRetorno" class="infraLabelObrigatorio lblCampo">Prazo
                            para
                            Retorno de
                            AR: <img align="top" id="imgAjuda"
                                     src="<?= PaginaSEI::getInstance()->getDiretorioSvgGlobal(); ?>/ajuda.svg"
                                     name="ajuda"
                                     onmouseover="return infraTooltipMostrar('Prazo Contratual para retorno do AR, em Dias.', 'Ajuda');"
                                     onmouseout="return infraTooltipOcultar();" alt="Ajuda"
                                     class="infraImgModulo"></label>
                        <input type="text" id="txtNuDiaRetorno" name="txtNuDiaRetorno" class="infraText form-control"
                               value="<?= !is_null($arrObjMdCorParametroArDTO) ? PaginaSEI::tratarHTML($arrObjMdCorParametroArDTO->getStrNuDiasRetornoAr()) : null; ?>"
                               onkeypress="return infraMascaraNumero(this,event,4);" maxlength="4"
                               tabindex="<?= PaginaSEI::getInstance()->getProxTabDados() ?>"/>
                    </div>
                </div>
                <div class="row">
                    <div class="col-sm-6 col-md-6 col-lg-6 col-xl-6">
                        <label id="lblNuDiasRetorno" for="txtNuDiaRetorno" class="infraLabelObrigatorio lblCampo">Prazo
                            para
                            Cobrança:
                            <img align="top" id="imgAjuda"
                                 src="<?= PaginaSEI::getInstance()->getDiretorioSvgGlobal(); ?>/ajuda.svg"
                                 name="ajuda"
                                 onmouseover="return infraTooltipMostrar('Prazo Contratual para cobrança de AR não retornado, em Dias.', 'Ajuda');"
                                 onmouseout="return infraTooltipOcultar();" alt="Ajuda" class="infraImgModulo"></label>
                        <input type="text" id="txtNuDiaRetorno" name="txtNuDiCobranca" class="infraText form-control"
                               value="<?= !is_null($arrObjMdCorParametroArDTO) ? PaginaSEI::tratarHTML($arrObjMdCorParametroArDTO->getStrNuDiasCobrancaAr()) : null; ?>"
                               onkeypress="return infraMascaraNumero(this,event,4);" maxlength="4"
                               tabindex="<?= PaginaSEI::getInstance()->getProxTabDados() ?>"/>
                    </div>
                </div>
                <div style="clear:both;">&nbsp;</div>
                <div class="row">
                    <div class="col-sm-12 col-md-12 col-lg-12 col-xl-12">
                        <fieldset class="infraFieldset form-control">
                            <legend class="infraLegend">&nbsp;Padrão para Documento Externo de Retorno de AR&nbsp;
                            </legend>
                            <div class="row">
                                <div class="col-sm-6 col-md-6 col-lg-6 col-xl-6">
                                    <label id="lblTipoDocumento" for="slTipoDocumento"
                                           class="infraLabelObrigatorio lblCampo">Tipo
                                        de Documento:
                                        <img align="top" id="imgAjuda"
                                             src="<?= PaginaSEI::getInstance()->getDiretorioSvgGlobal(); ?>/ajuda.svg"
                                             name="ajuda"
                                             onmouseover="return infraTooltipMostrar('Tipo de Documento Padrão que será utilizado quando o AR for retornado.', 'Ajuda');"
                                             onmouseout="return infraTooltipOcultar();" alt="Ajuda"
                                             class="infraImgModulo">
                                    </label>
                                    <div class="input-group mb-3">
                                        <select class="infraSelect form-control" name="slTipoDocumento"
                                                id="slTipoDocumento"
                                                tabindex="<?= PaginaSEI::getInstance()->getProxTabDados(); ?>">
                                            <?php foreach ($arrTipoDocumento as $chave => $tipoDocumento) { ?>
                                                <?php echo '<option selected="selected" value="' . $chave . '">' . $tipoDocumento . '</option>' ?>
                                            <?php } ?>
                                        </select>
                                        <img id="imgLupaTipoDocumento"
                                             onclick="objLupaTipoDocumento.selecionar(700,500);"
                                             src="<?= PaginaSEI::getInstance()->getDiretorioSvgGlobal(); ?>/pesquisar.svg"
                                             alt="Selecionar Tipo de Documento" title="Selecionar Tipo de Documento"
                                             class="infraImg"/>
                                    </div>
                                    <input name="hdnTipoDocumento" id="hdnTipoDocumento" type="hidden" value=""/>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-sm-6 col-md-6 col-lg-6 col-xl-6">
                                    <label id="lblNoArvore" for="txtNoArvore" class="infraLabelObrigatorio lblCampo">Número/Nome
                                        na
                                        Árvore: <img
                                                align="top" id="imgAjuda"
                                                src="<?= PaginaSEI::getInstance()->getDiretorioSvgGlobal(); ?>/ajuda.svg"
                                                name="ajuda"
                                                onmouseover="return infraTooltipMostrar('Complemento do Documento Padrão que será utilizado quando o AR for retornado. \n \n Utilize a variável @tipo_doc_principal_expedido@ para apresentar o Tipo do Documento Principal e @numero@ para apresentar o Número SEI desse Documento Principal.', 'Ajuda');"
                                                onmouseout="return infraTooltipOcultar();" alt="Ajuda"
                                                class="infraImgModulo"></label>
                                    <input type="text" id="txtNoArvore" name="txtNoArvore"
                                           class="infraText form-control"
                                           value="<?= !is_null($arrObjMdCorParametroArDTO) ? PaginaSEI::tratarHTML($arrObjMdCorParametroArDTO->getStrNomeArvore()) : null; ?>"
                                           onkeypress="return infraMascaraTexto(this,event,60);" maxlength="60"
                                           tabindex="<?= PaginaSEI::getInstance()->getProxTabDados() ?>"/>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-sm-6 col-md-6 col-lg-6 col-xl-6">
                                    <label id="lblTipoDocumento" for="slTipoConferencia" class="infraLabelObrigatorio">Tipo
                                        de
                                        Conferência: <img
                                                align="top" id="imgAjuda"
                                                src="<?= PaginaSEI::getInstance()->getDiretorioSvgGlobal(); ?>/ajuda.svg"
                                                name="ajuda"
                                                onmouseover="return infraTooltipMostrar('Tipo de Conferência Padrão que será utilizado quando o AR for retornado.', 'Ajuda');"
                                                onmouseout="return infraTooltipOcultar();" alt="Ajuda"
                                                class="infraImgModulo"></label>
                                    <select class="infraSelec form-control" name="slTipoConferencia"
                                            id="slTipoConferencia"
                                            tabindex="<?= PaginaSEI::getInstance()->getProxTabDados(); ?>">
                                        <?php echo $tipoConferenciaInt; ?>
                                    </select>
                                </div>
                            </div>
                        </fieldset>
                    </div>
                </div>
                <div style="clear:both;">&nbsp;</div>
                <div class="row">
                    <div class="col-sm-12 col-md-12 col-lg-12 col-xl-12">
                        <fieldset class="infraFieldset form-control">
                            <legend class="infraLegend">&nbsp;Motivo de Objeto Devolvido&nbsp;</legend>
                            <div class="row">
                                <div class="col-sm-6 col-md-6 col-lg-6 col-xl-6">
                                    <label id="lblNoArvore" for="txtMotivo" class="infraLabelObrigatorio lblCampo">Motivo
                                        de
                                        Devolução:
                                        <img align="top" id="imgAjuda"
                                             src="<?= PaginaSEI::getInstance()->getDiretorioSvgGlobal(); ?>/ajuda.svg"
                                             name="ajuda"
                                             onmouseover="return infraTooltipMostrar('Inserir texto do Motivo de Devolução de Objeto que deseja Adicionar.', 'Ajuda');"
                                             onmouseout="return infraTooltipOcultar();" alt="Ajuda"
                                             class="infraImgModulo"></label>
                                    <input type="text" id="txtMotivo" name="txtMotivo" class="infraText form-control"
                                           value=""
                                           onkeypress="return infraMascaraTexto(this,event,50);" maxlength="50"
                                           tabindex="<?= PaginaSEI::getInstance()->getProxTabDados() ?>"/>
                                </div>
                            </div>
                            <div class="row linha">
                                <div class="col-sm-6 col-md-6 col-lg-6 col-xl-6">
                                    <input type="checkbox" id="checkInfrigencia" name="checkInfrigencia"
                                           class="infraCheckbox"
                                           tabindex="<?= PaginaSEI::getInstance()->getProxTabDados() ?>"/>
                                    <label id="lblCheckInfrigencia" for="checkInfrigencia"
                                           class="infraLabelObrigatorio lblCampo">Infrigência
                                        Contratual</label>
                                </div>
                            </div>
                            <div class="row linha">
                                <div class="col-sm-6 col-md-6 col-lg-6 col-xl-6 text-left">
                                    <button tabindex="<?= PaginaSEI::getInstance()->getProxTabDados() ?>"
                                            id="btnAdicionar"
                                            type="button"
                                            accesskey="A" name="btnAdicionar" value="Adicionar"
                                            onclick="adicionarMotivo();"
                                            class="infraButton"><span class="infraTeclaAtalho">A</span>dicionar
                                    </button>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-sm-12 col-md-12 col-lg-12 col-xl-12">
                                    <table class="infraTable" summary="Motivo" id="tbMotivo">
                                        <tr>
                                            <th class="infraTh" align="center">Motivo de Devolução</th> <!--9-->
                                            <th class="infraTh" align="center">Infrigência Contratual</th> <!--10-->
                                            <th class="infraTh" align="center" style="display: none">Status
                                                Infrigencia
                                            </th>
                                            <!--10-->
                                            <th class="infraTh" align="center" style="display: none">
                                                PermissaoAlteracao
                                            </th>
                                            <!--10-->
                                            <th class="infraTh" align="center" style="display: none">ID</th> <!--10-->
                                            <th class="infraTh" align="center" width="60px">Ações</th>
                                        </tr>
                                        <?php $hdnMotivo = ''; ?>
                                        <?php foreach ($arrObjMdCorParamArInfrigenRN as $mdCorParArInfrigen) { ?>
                                            <?php $stInfrigencia = $mdCorParArInfrigen->getStrSinInfrigencia() == 'S' ? 'Sim' : 'Não'; ?>
                                            <?php $hdnMotivoRetorno = $mdCorParArInfrigen->getStrSinInfrigencia() == 'S' ? 'true' : 'false'; ?>
                                            <?php $hdnMotivo .= $mdCorParArInfrigen->getStrMotivoInfrigencia() . '±' ?>
                                            <?php $hdnMotivo .= $stInfrigencia . '±'; ?>
                                            <?php $hdnMotivo .= $hdnMotivoRetorno . '±'; ?>
                                            <?php $hdnMotivo .= $mdCorParArInfrigen->getStrSinStatusRetornoDocumento() . '±'; ?>
                                            <?php $hdnMotivo .= $mdCorParArInfrigen->getNumIdMdCorParamArInfrigencia(); ?>
                                            <?php $hdnMotivo .= ($mdCorParArInfrigen !== end($arrObjMdCorParamArInfrigenRN)) ? '¥' : ''; ?>
                                        <?php } ?>
                                    </table>
                                </div>
                            </div>
                            <input name="hdnTbMotivo" id="hdnTbMotivo" type="hidden" value="<?php echo $hdnMotivo ?>"/>
                        </fieldset>
                    </div>
                </div>
                <div style="clear:both;">&nbsp;</div>
                <div class="row">
                    <div class="col-sm-12 col-md-12 col-lg-12 col-xl-12">
                        <fieldset class="infraFieldset form-control">
                            <legend class="infraLegend">&nbsp;Padrão para Documento Externo de Objeto Devolvido&nbsp;
                            </legend>
                            <div class="row">
                                <div class="col-sm-6 col-md-6 col-lg-6 col-xl-6">
                                    <label id="lblTipoDocumento" for="slTipoDocumento"
                                           class="infraLabelObrigatorio lblCampo">Tipo
                                        de
                                        Documento:
                                        <img align="top" id="imgAjuda"
                                             src="<?= PaginaSEI::getInstance()->getDiretorioSvgGlobal(); ?>/ajuda.svg"
                                             name="ajuda"
                                             onmouseover="return infraTooltipMostrar('Tipo de Documento Padrão que será utilizado quando o Objeto for devolvido.', 'Ajuda');"
                                             onmouseout="return infraTooltipOcultar();" alt="Ajuda"
                                             class="infraImgModulo"></label>
                                    <div class="input-group mb-3">
                                        <select class="infraSelect form-control" name="slTipoDocumentoObjetoDevolvido"
                                                id="slTipoDocumentoObjetoDevolvido"
                                                tabindex="<?= PaginaSEI::getInstance()->getProxTabDados(); ?>">
                                            <?php foreach ($arrTipoDocumentoObjetoDevolvido as $chave => $tipoDocumento) { ?>
                                                <?php echo '<option selected="selected" value="' . $chave . '">' . $tipoDocumento . '</option>' ?>
                                            <?php } ?>
                                        </select>
                                        <img id="imgLupaTipoDocumentoObjetoDevolucao"
                                             onclick="objLupaTipoDocumentoObjetoDevolvido.selecionar(700,500);"
                                             src="<?= PaginaSEI::getInstance()->getDiretorioSvgGlobal(); ?>/pesquisar.svg"
                                             alt=" Selecionar Tipo de Documento" title="Selecionar Tipo de Documento"
                                             class="infraImg"/>
                                    </div>
                                    <input name="hdnTipoDocumentoObjetoDevolvido" id="hdnTipoDocumentoObjetoDevolvido"
                                           type="hidden"
                                           value=""/>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-sm-6 col-md-6 col-lg-6 col-xl-6">
                                    <label id="lblNoArvore" for="txtNoArvoreDevolvido"
                                           class="infraLabelObrigatorio lblCampo">Número/Nome
                                        na
                                        Árvore: <img align="top" id="imgAjuda"
                                                     src="<?= PaginaSEI::getInstance()->getDiretorioSvgGlobal(); ?>/ajuda.svg"
                                                     name="ajuda"
                                                     onmouseover="return infraTooltipMostrar('Complemento do Documento Padrão que será utilizado quando o Objeto for devolvido. \n \n Utilize a variável @tipo_doc_principal_expedido@ para apresentar o Tipo do Documento Principal e @numero@ para apresentar o Número SEI desse Documento Principal.', 'Ajuda');"
                                                     onmouseout="return infraTooltipOcultar();" alt="Ajuda"
                                                     class="infraImgModulo"></label>
                                    <input type="text" id="txtNoArvoreDevolvido" name="txtNoArvoreObjetoDevolvido"
                                           class="infraText form-control"
                                           value="<?= !is_null($arrObjMdCorParametroArDTO) ? PaginaSEI::tratarHTML($arrObjMdCorParametroArDTO->getStrNomeArvoreDevolvido()) : null; ?>"
                                           onkeypress="return infraMascaraTexto(this,event,60);" maxlength="60"
                                           tabindex="<?= PaginaSEI::getInstance()->getProxTabDados() ?>"/>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-sm-6 col-md-6 col-lg-6 col-xl-6">
                                    <label id="lblTipoDocumento" for="slTipoConferencia" class="infraLabelObrigatorio">Tipo
                                        de
                                        Conferência: <img
                                                align="top" id="imgAjuda"
                                                src="<?= PaginaSEI::getInstance()->getDiretorioSvgGlobal(); ?>/ajuda.svg"
                                                name="ajuda"
                                                onmouseover="return infraTooltipMostrar('Tipo de Conferência Padrão que será utilizado quando o Objeto for devolvido.', 'Ajuda');"
                                                onmouseout="return infraTooltipOcultar();" alt="Ajuda"
                                                class="infraImgModulo"></label>
                                    <select class="infraSelect form-control" name="slTipoConferenciaObjetoDevolvido"
                                            id="slTipoConferenciaObjetoDevolvido"
                                            tabindex="<?= PaginaSEI::getInstance()->getProxTabDados(); ?>">
                                        <?php echo $tipoConferenciaObjetoDevolvidoInt; ?>
                                    </select>
                                </div>
                            </div>
                        </fieldset>
                    </div>
                </div>
                <div style="clear:both;">&nbsp;</div>
                <div class="row">
                    <div class="col-sm-12 col-md-12 col-lg-12 col-xl-12">
                        <fieldset class="infraFieldset form-control">
                            <legend class="infraLegend">&nbsp;Padrão de Documento de Cobrança&nbsp;</legend>
                            <div class="row linha">
                                <div class="col-sm-6 col-md-6 col-lg-6 col-xl-6">
                                    <label id="lblTipoDocumento" for="slTipoDocumento"
                                           class="infraLabelObrigatorio lblCampo">Tipo
                                        de
                                        Documento:
                                        <img align="top" id="imgAjuda"
                                             src="<?= PaginaSEI::getInstance()->getDiretorioSvgGlobal(); ?>/ajuda.svg"
                                             name="ajuda"
                                             onmouseover="return infraTooltipMostrar('Tipo de Documento Padrão que será utilizado quando for gerado Documento de Cobrança. Este Tipo de Documento geralmente é um Ofício, com o fim de cobrar dos Correios as pendências de retorno de ARs, conforme estipulado em Contrato.', 'Ajuda');"
                                             onmouseout="return infraTooltipOcultar();" alt="Ajuda"
                                             class="infraImgModulo"></label>
                                    <div class="input-group mb-3">
                                        <select class="infraSelect form-control" name="slTipoDocumentoCobranca"
                                                id="slTipoDocumentoCobranca"
                                                tabindex="<?= PaginaSEI::getInstance()->getProxTabDados(); ?>">
                                            <?php foreach ($arrTipoDocumentoCobranca as $chave => $tipoDocumento) { ?>
                                                <?php echo '<option selected="selected" value="' . $chave . '">' . $tipoDocumento . '</option>' ?>
                                            <?php } ?>
                                        </select>
                                        <img id="imgLupaTipoDocumentoCobranca"
                                             onclick="objLupaTipoDocumentoCobranca.selecionar(700,500);"
                                             src="<?= PaginaSEI::getInstance()->getDiretorioSvgGlobal(); ?>/pesquisar.svg"
                                             alt="Selecionar Tipo de Documento" title="Selecionar Tipo de Documento"
                                             class="infraImg"/>
                                    </div>
                                    <input name="hdnTipoDocumentoCobranca" id="hdnTipoDocumentoCobranca" type="hidden"
                                           value=""/>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-sm-6 col-md-6 col-lg-6 col-xl-6">
                                    <label id="lblProcessoCobranca" for="txtProcessoCobranca"
                                           class="infraLabelObrigatorio lblCampo">Processo
                                        de
                                        Cobrança: <img align="top" id="imgAjuda"
                                                       src="<?= PaginaSEI::getInstance()->getDiretorioSvgGlobal(); ?>/ajuda.svg"
                                                       name="ajuda"
                                                       onmouseover="return infraTooltipMostrar('Indique o Processo no qual cada Documento de Cobrança será gerado. Este Processo geralmente é o de acompanhamento da execução do Contrato correspondente com os Correios.', 'Ajuda');"
                                                       onmouseout="return infraTooltipOcultar();" alt="Ajuda"
                                                       class="infraImgModulo"></label>
                                    <input onblur="buscarProcessoUnidadeGeradora(this)" type="text"
                                           id="txtProcessoCobranca"
                                           name="txtProcessoCobranca" class="infraText form-control"
                                           value="<?= !is_null($arrObjMdCorParametroArDTO) ? PaginaSEI::tratarHTML($arrObjMdCorParametroArDTO->getStrProtocoloFormatadoCobranca()) : null; ?>"
                                           onkeypress="return infraMascaraTexto(this,event,60);" maxlength="60"
                                           tabindex="<?= PaginaSEI::getInstance()->getProxTabDados() ?>"/>
                                    <input type="hidden" name="hdnIdProcedimento" id="hdnIdProcedimento"
                                           value="<?= !is_null($arrObjMdCorParametroArDTO) ? PaginaSEI::tratarHTML($arrObjMdCorParametroArDTO->getNumIdProcedimentoCobranca()) : null; ?>"/>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-sm-6 col-md-6 col-lg-6 col-xl-6">
                                    <label id="lblUnidadeGeradora" for="txtUnidadeGeradora"
                                           class="infraLabelObrigatorio lblCampo">Unidade
                                        Geradora do Documento: <img align="top" id="imgAjuda"
                                                                    src="<?= PaginaSEI::getInstance()->getDiretorioSvgGlobal(); ?>/ajuda.svg"
                                                                    name="ajuda"
                                                                    onmouseover="return infraTooltipMostrar('Dentre as Unidades pelas quais o Processo de Cobrança tenha tramitado, indique a Unidade na qual cada Documento de Cobrança será gerado.', 'Ajuda');"
                                                                    onmouseout="return infraTooltipOcultar();"
                                                                    alt="Ajuda"
                                                                    class="infraImgModulo"></label>
                                    <select class="infraSelect form-control" name="txtUnidadeGeradora"
                                            id="txtUnidadeGeradora"
                                            tabindex="<?= PaginaSEI::getInstance()->getProxTabDados() ?>">
                                        <?php echo $arrComboUnidade; ?>
                                    </select>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-sm-6 col-md-6 col-lg-6 col-xl-6">
                                    <label id="lblDestinatarios" for="txtDestinatario"
                                           class="infraLabelObrigatorio infraLabelOpcional">Destinatário:
                                        <img align="top" id="imgAjuda"
                                             src="<?= PaginaSEI::getInstance()->getDiretorioSvgGlobal(); ?>/ajuda.svg"
                                             name="ajuda"
                                             onmouseover="return infraTooltipMostrar('Indique o Destinatário Padrão do Documento de Cobrança.', 'Ajuda');"
                                             onmouseout="return infraTooltipOcultar();" alt="Ajuda"
                                             class="infraImgModulo"></label><br/>
                                    <input type="text" id="txtDestinatario" name="txtDestinatario"
                                           class="infraText form-control infraAutoCompletar"
                                           tabindex="528"
                                           value="<?php echo $arrObjMdCorParametroArDTO->getStrNomeContato(); ?>"
                                           autocomplete="off">
                                    <input type="hidden" id="hdnIdDestinatario" name="hdnIdDestinatario"
                                           class="infraText"
                                           value="<?php echo $arrObjMdCorParametroArDTO->getNumIdContato(); ?>">
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-sm-12 col-md-12 col-lg-12 col-xl-12">
                                    <label id="lblModelo" for="txaConteudo" class="infraLabelObrigatorio lblCampo">Modelo:
                                        <img align="top" id="imgAjuda"
                                             src="<?= PaginaSEI::getInstance()->getDiretorioSvgGlobal(); ?>/ajuda.svg"
                                             name="ajuda" onmouseout="return infraTooltipOcultar();" alt="Ajuda"
                                             class="infraImgModulo"
                                             onmouseover="return infraTooltipMostrar('Lembrando que o Tipo de Documento geralmente é um Ofício, formate o modelo do Documento por meio do qual ocorrerão cada cobrança das pendências de retorno de ARs. \n \n Utilize a variável @tabela_cobranca@ no modelo para que o Módulo gera a tabela com a lista completa de Códigos de Rastreio de Objetos que estão com pendência de retorno de AR.', 'Ajuda');"
                                        >
                                    </label>
                                    <textarea id="txaConteudo" name="txaConteudo" rows="1"
                                              class="infraTextarea form-control"
                                              tabindex="<?= PaginaSEI::getInstance()->getProxTabDados() ?>">
                                <?= !is_null($arrObjMdCorParametroArDTO) ? PaginaSEI::tratarHTML($arrObjMdCorParametroArDTO->getStrModeloCobranca()) : null; ?>
                            </textarea>
                                </div>
                            </div>
                        </fieldset>
                    </div>
                </div>
            </div>
        </div>

        <? PaginaSEI::getInstance()->montarBarraComandosInferior($arrComandos); ?>
    </form>
<?
require_once('md_cor_parametro_ar_cadastro_js.php');
PaginaSEI::getInstance()->fecharBody();
PaginaSEI::getInstance()->fecharHtml();
?>