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

    SessaoSEI::getInstance()->validarLink();

    PaginaSEI::getInstance()->verificarSelecao('md_cor_contrato_selecionar');

    SessaoSEI::getInstance()->validarPermissao($_GET['acao']);

    $objMdCorContratoDTO = new MdCorContratoDTO();

    $strDesabilitar = '';
    $strProtocoloFormatado = '';
    $hdnListaContratoServicosIndicados = '';
    $strIdMdCorContrato = '';
    $strNumeroCNPJ = '';
    $strNumeroContratoCorreio = '';

    $arrComandos = array();

    $mdCorTipoCorrespondencia = MdCorTipoCorrespondencINT::montarSelectIdMdCorTipoCorrespondenc('null', '', 'null');

    switch ($_GET['acao']) {
        case 'md_cor_servicos_postais_contrato_alterar':
            $strTitulo = 'Alterar Serviços Postais do Contrato';
            $arrComandos[] = '<button type="submit" accesskey="S" name="sbmAlterarMdCorServicosPostaisContrato" value="Salvar" class="infraButton"><span class="infraTeclaAtalho">S</span>alvar</button>';
            $strDesabilitar = 'disabled="disabled"';

            $idMdCorContrato = $_GET['id_md_cor_contrato'] ? $_GET['id_md_cor_contrato'] : $_POST['hdnIdMdCorContrato'];

            $objMdCorAdmIntegracaoTokensDTO = new MdCorAdmIntegracaoTokensDTO();
            $objMdCorAdmIntegracaoTokensRN = new MdCorAdmIntegracaoTokensRN();

            $objMdCorAdmIntegracaoTokensDTO->setNumIdMdCorContrato($idMdCorContrato);
            $objMdCorAdmIntegracaoTokensDTO->retNumIdMdCorContrato();
            $numTokens = $objMdCorAdmIntegracaoTokensRN->contar($objMdCorAdmIntegracaoTokensDTO);
            $objMdCorContratoDTO->setNumIdMdCorContrato($idMdCorContrato);

            if ($numTokens == 0) {
                header('Location: ' . SessaoSEI::getInstance()->assinarLink('controlador.php?msg=Antes de adicionar Serviços Postais neste Contrato, é necessário incluir os dados de Autenticação para Obter Token Diário no Mapeamento da Integração em Administração > Correios > Mapeamento das Integrações > Correios::Gerar Token&acao=' . PaginaSEI::getInstance()->getAcaoRetorno() . '&acao_origem=' . $_GET['acao'] . '&id_me_cor_contrato=' . $_GET['id_md_cor_contrato'] . PaginaSEI::getInstance()->montarAncora($objMdCorContratoDTO->getNumIdMdCorContrato())));
                die;
            }


            $arrComandos[] = '<button type="button" accesskey="C" name="btnCancelar" id="btnCancelar" value="Cancelar" onclick="location.href=\'' . SessaoSEI::getInstance()->assinarLink('controlador.php?acao=' . PaginaSEI::getInstance()->getAcaoRetorno() . '&acao_origem=' . $_GET['acao'] . PaginaSEI::getInstance()->montarAncora($objMdCorContratoDTO->getNumIdMdCorContrato())) . '\';" class="infraButton"><span class="infraTeclaAtalho">C</span>ancelar</button>';

            if (isset($_POST['sbmAlterarMdCorServicosPostaisContrato'])) {
                try {
                    $objMdCorContratoRN = new MdCorContratoRN();
                    $objMdCorContratoRN->alterarServicosPostais($_POST);
                    PaginaSEI::getInstance()->adicionarMensagem('Serviços postais do contrato "' . $objMdCorContratoDTO->getNumIdMdCorContrato() . '" alterados com sucesso.');
                    header('Location: ' . SessaoSEI::getInstance()->assinarLink('controlador.php?acao=' . PaginaSEI::getInstance()->getAcaoRetorno() . '&acao_origem=' . $_GET['acao'] . '&id_me_cor_contrato=' . $_GET['id_md_cor_contrato'] . PaginaSEI::getInstance()->montarAncora($objMdCorContratoDTO->getNumIdMdCorContrato())));
                    die;
                } catch (Exception $e) {
                    PaginaSEI::getInstance()->processarExcecao($e);
                }
            }

            break;

        default:
            throw new InfraException("Ação '" . $_GET['acao'] . "' não reconhecida.");
    }

    $idMdCorContrato = $_GET['id_md_cor_contrato'] ? $_GET['id_md_cor_contrato'] : $_POST['hdnIdMdCorContrato'];

    if ($idMdCorContrato) {
        $objMdCorContratoRN = new MdCorContratoRN();

        $objMdCorContratoDTO->retNumIdMdCorContrato();
        $objMdCorContratoDTO->retNumNumeroCnpj();
        $objMdCorContratoDTO->retStrNumeroContrato();
        $objMdCorContratoDTO->retStrNumeroContratoCorreio();
        $objMdCorContratoDTO = $objMdCorContratoRN->consultar($objMdCorContratoDTO);
        $strIdMdCorContrato = $objMdCorContratoDTO->getNumIdMdCorContrato();
        $strNumeroCNPJ = InfraUtil::retirarFormatacao(InfraUtil::formatarCnpj($objMdCorContratoDTO->getNumNumeroCnpj()));
        $strNumeroContratoCorreio = $objMdCorContratoDTO->getStrNumeroContratoCorreio();

        $strTitulo .= ' ' . $objMdCorContratoDTO->getStrNumeroContrato();

        if ($objMdCorContratoDTO == null) {
            throw new InfraException("Registro não encontrado.");
        }

        $objMdCorServicoPostalRN = new MdCorServicoPostalRN();
        $objMdCorServicoPostalDTO = new MdCorServicoPostalDTO();
        $objMdCorServicoPostalDTO->retTodos(true);
        $objMdCorServicoPostalDTO->setNumIdMdCorContrato($_GET['id_md_cor_contrato']);
        $objMdCorServicoPostalDTO->setBolExclusaoLogica(array());
        $arrObjMdCorServicoPostalDTO = $objMdCorServicoPostalRN->listar($objMdCorServicoPostalDTO);

        $arrItensTabelaContratoServicos = array();

        $sinAtivo = array();
        foreach ($arrObjMdCorServicoPostalDTO as $i => $objMdCorServicoPostalDTO) {
            $mdCorTipoCorrespondencia = MdCorTipoCorrespondencINT::montarSelectIdMdCorTipoCorrespondenc('null', '', $objMdCorServicoPostalDTO->getNumIdMdCorTipoCorrespondencia() . '|' . $objMdCorServicoPostalDTO->getStrSinAr());

            $ar = $objMdCorServicoPostalDTO->getStrExpedicaoAvisoRecebimento();
            $sinAtivo[$i] = $objMdCorServicoPostalDTO->getStrSinAtivo();

            $cobrar = $objMdCorServicoPostalDTO->getStrSinServicoCobrar();
            $anexarMidia = $objMdCorServicoPostalDTO->getStrSinAnexarMidia();
            $checked = ' checked="checked"';
            $readonly = '';
            if (($_GET['acao'] == 'md_cor_contrato_consultar')) {
                $readonly = ' disabled="disabled" readonly onClick="return false;"';
            }
            $checkedSim = ($ar == 'S') ? $checked : '';
            $checkedNao = ($ar == 'N' && $objMdCorServicoPostalDTO->getStrSinAr() != 'N') ? $checked : '';
            $checkedCobrar = ($cobrar == 'S') ? $checked : '';
            $checkedAnexarMidia = ($anexarMidia == 'S') ? $checked : '';
            $disabledSinAr = $objMdCorServicoPostalDTO->getStrSinAr() == 'N' ? 'disabled="disabled"' : null;

            $strRd = '<div id="divRdoAr" class="infraDivRadio" align="center" style="width: 100%">';
            $strRd .= '    <div class="infraRadioDiv ">';
            $strRd .= '        <input type="radio" name="ar[' . $i . ']" id="arS[' . $i . ']"';
            $strRd .= '               value="S"';
            $strRd .= '               class="infraRadioInput" ' . $checkedSim . $readonly . $disabledSinAr . '>';
            $strRd .= '            <label class="infraRadioLabel" for="arS[' . $i . ']"></label>';
            $strRd .= '    </div>';
            $strRd .= '    <label id="lblArS[' . $i . ']" for="arS[' . $i . ']" class="infraLabelRadio" tabindex="507">Sim</label>';
            $strRd .= '    <div class="infraRadioDiv ">';
            $strRd .= '        <input type="radio" name="ar[' . $i . ']" id="arN[' . $i . ']"';
            $strRd .= '               value="N"';
            $strRd .= '               class="infraRadioInput" ' . $checkedNao . $readonly . $disabledSinAr . '>';
            $strRd .= '            <label class="infraRadioLabel" for="arN[' . $i . ']"></label>';
            $strRd .= '    </div>';
            $strRd .= '    <label id="lblArN[' . $i . ']" for="arN[' . $i . ']" class="infraLabelRadio" tabindex="507">Não</label>';
            $strRd .= '</div>';

            $strChk = '<div id="divRdoAr" class="infraDivCheckbox">';
            $strChk .= '    <div class="infraCheckboxDiv">';
            $strChk .= '        <input type="checkbox" name="cobrar[' . $i . ']" id="cobrar[' . $i . ']"';
            $strChk .= '               value="S"';
            $strChk .= '               class="infraCheckboxInput" ' . $checkedCobrar . $readonly . '>';
            $strChk .= '            <label class="infraCheckboxLabel " for="cobrar[' . $i . ']"></label>';
            $strChk .= '    </div>';
            $strChk .= '</div>';

            $strAnexarMidia = '<div class="infraDivCheckbox" style="text-align: center"> <div class="infraCheckboxDiv"> <input type="checkbox" class="infraCheckboxInput" value="S" name="anexarMidia['.$i.']" id="anexarMidia['.$i.']" '. $checkedAnexarMidia . $readonly .'> <label class="infraCheckboxLabel" for="anexarMidia['.$i.']"></label> </div> </div>';

            $itensTabelaContratoServicos = array(
                trim($objMdCorServicoPostalDTO->getStrCodigoWsCorreios()),
                $objMdCorServicoPostalDTO->getStrSinAtivo(),
                '',
                $objMdCorServicoPostalDTO->getStrNome(),
                /*5*/'<div style="width:100%;"><select id="slTipo_'. $i .'" class="infraSelect sl_tipo form-control" name="sl_tipo[' . $i . ']" onchange="verificaAr(this)">' . json_encode($mdCorTipoCorrespondencia) . '</select></div>',
                /*6*/$strRd,
                /*7*/$strChk,
                $strAnexarMidia,
                '<div><input type="text" id="idDesc_'. $i .'" class="input-desc form-control" style="width: 100%" name="descricao[' . $i . ']" value="' . PaginaSEI::tratarHTML($objMdCorServicoPostalDTO->getStrDescricao()) . '" ' . $readonly . '/> <input type="hidden" name="codigo[' . $i . ']" value="' . trim($objMdCorServicoPostalDTO->getStrCodigoWsCorreios()) . '"> <input type="hidden" name="nome[' . $i . ']" value="' . $objMdCorServicoPostalDTO->getStrNome() . '"></div>',
                ''
            );

            if ($_GET['acao'] == 'md_cor_contrato_consultar') {
                $itensTabelaContratoServicos[5] = ($objMdCorServicoPostalDTO->getStrExpedicaoAvisoRecebimento() == 'S') ? 'Sim' : 'Não';
                $itensTabelaContratoServicos[6] = ($cobrar == 'S') ? 'Sim' : 'Não';
                $itensTabelaContratoServicos[7] = ($anexarMidia == 'S') ? 'Sim' : 'Não';
                $itensTabelaContratoServicos[8] = $objMdCorServicoPostalDTO->getStrDescricao();
            }

            $arrItensTabelaContratoServicos[] = $itensTabelaContratoServicos;
        }

        $hdnListaContratoServicosIndicados = PaginaSEI::getInstance()->gerarItensTabelaDinamica($arrItensTabelaContratoServicos, false);

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
include_once('md_cor_contrato_cadastro_css.php');
PaginaSEI::getInstance()->fecharStyle();
PaginaSEI::getInstance()->montarJavaScript();
PaginaSEI::getInstance()->fecharHead();
PaginaSEI::getInstance()->abrirBody($strTitulo, 'onload="inicializar();"');
PaginaSEI::getInstance()->abrirAreaDados();
?>
    <form id="frmMdCorContratoCadastro" method="post" onsubmit="return OnSubmitForm();"
          action="<?= SessaoSEI::getInstance()->assinarLink('controlador.php?acao=' . $_GET['acao'] . '&acao_origem=' . $_GET['acao']) ?>">
        <?
        PaginaSEI::getInstance()->montarBarraComandosSuperior($arrComandos);
        ?>

        <input type="hidden" id="id_contrato" name="id_contrato" value="<?= $strIdMdCorContrato; ?>"/>
        <input type="hidden" id="txtCNPJ" name="txtCNPJ" value="<?= $strNumeroCNPJ; ?>"/>
        <input type="hidden" id="txtNumeroContratoCorreio" name="txtNumeroContratoCorreio" value="<?= $strNumeroContratoCorreio; ?>"/>

        <!-- Padrão de largura para as div abaixo -->
        <?php $cls_def = "col-sm-7 col-md-7 col-lg-6" ?>

        <div class="row linha">
            <div class="col-12">
                <fieldset id="fieldsetContratoCorreios" class="infraFieldset form-control" style="height: 100%">
                    <legend class="infraLegend">&nbsp;Serviços Postais&nbsp;</legend>

                        <div class="row">
                            <div class="col-sm-11 col-md-11 col-lg-10 col-xl-8 mt-2 mb-2">
                                <button id="validar-url" onclick="buscarServicosPostais()" class="infraButton btn-outline-info"
                                        type="button">Buscar Serviços Postais
                                </button>
                            </div>

                        </div>
                        <div class="row">
                            <div class="col-12">
                                <input type="hidden" id="hdnIdMdCorContrato" name="hdnIdMdCorContrato" value="<?= $strIdMdCorContrato; ?>"/>
                                
                                <input type="hidden" name="hdnIdContratoServicosCadastrado" id="hdnIdContratoServicosCadastrado"
                                        value=""/>
                                <input type="hidden" name="hdnListaContratoServicosIndicados" id="hdnListaContratoServicosIndicados"
                                        value=""/>
                                <input type="hidden" name="hdnListaContratoServicosDesativados[]" id="hdnListaContratoServicosDesativados"
                                    value=""/>
                                <input type="hidden" name="hdnListaContratoServicosReativadas[]" id="hdnListaContratoServicosReativadas"
                                        value=""/>
                                <table id="tbContratoServicos" class="infraTable table w-100" align="left" summary="Lista de Serviços Postais">
                                    <thead>
                                        <tr>
                                            <th class="infraTh" style="display: none;">Codigo Servico</th>
                                            <th class="infraTh" style="display: none;">AR Hidden</th>
                                            <th class="infraTh" style="display: none;">Descricao Hidden</th>
                                            <th class="infraTh" width="18%" id="tdDescricaoServicoPostal">Serviço Postal</th>
                                            <th class="infraTh" width="12%" align="center" id="tdCheckExpedidoAR">Tipo</th>
                                            <th class="infraTh" width="16%" align="center" id="tdCheckExpedidoAR">Expedido com AR</th>
                                            <th class="infraTh" width="10%" align="center" id="tdCheckCobrar">Serviço à Cobrar</th>
                                            <th class="infraTh" width="10%" align="center" id="tdCheckAnexarMidia">Permite Anexar Mídia</th>
                                            <th class="infraTh" width="22%" id="tdTxtDescricao">Descrição Amigável</th>                                    
                                            <th class="infraTh" width="7%" align="center">Ações</th>                                    
                                        </tr>
                                    </thead>
                                    <tbody>
                                    </tbody>
                                </table>
                            </div>
                        </div>
                </fieldset>
            </div>
        </div>

        <? PaginaSEI::getInstance()->montarBarraComandosInferior($arrComandos); ?>
    </form>
<?
PaginaSEI::getInstance()->fecharAreaDados();
PaginaSEI::getInstance()->fecharBody();
PaginaSEI::getInstance()->fecharHtml();
include_once('md_cor_funcoes_js.php');
include_once('md_cor_servicos_postais_contrato_cadastro_js.php');
?>