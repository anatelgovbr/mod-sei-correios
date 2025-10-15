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
    $strIdMdCorContrato = '';

    $arrComandos = array();

    $mdCorTipoCorrespondencia = MdCorTipoCorrespondencINT::montarSelectIdMdCorTipoCorrespondenc('null', '', 'null');

    switch ($_GET['acao']) {
        case 'md_cor_contrato_cadastrar':
            $strTitulo = 'Contrato';
            $arrComandos[] = '<button type="submit" accesskey="S" name="sbmCadastrarMdCorContrato" value="Salvar" class="infraButton"><span class="infraTeclaAtalho">S</span>alvar</button>';
            $arrComandos[] = '<button type="button" accesskey="C" name="btnCancelar" id="btnCancelar" value="Cancelar" onclick="location.href=\'' . SessaoSEI::getInstance()->assinarLink('controlador.php?acao=' . PaginaSEI::getInstance()->getAcaoRetorno() . '&acao_origem=' . $_GET['acao']) . '\';" class="infraButton"><span class="infraTeclaAtalho">C</span>ancelar</button>';

            $objMdCorContratoDTO->setStrNumeroContrato($_POST['txtNumeroContrato']);
            $objMdCorContratoDTO->setStrNumeroContratoCorreio($_POST['txtNumeroContratoCorreio']);
            $objMdCorContratoDTO->setStrNumeroCartaoPostagem($_POST['txtNumeroCartaoPostagem']);
            $objMdCorContratoDTO->setDblIdProcedimento($_POST['hdnIdProcedimento']);
            $objMdCorContratoDTO->setStrSinAtivo('S');
            $objMdCorContratoDTO->setNumNumeroCnpj($_POST['txtCNPJ']);
            $objMdCorContratoDTO->setNumIdMdCorDiretoria($_POST['slCodigoDiretoria']);

            if (isset($_POST['sbmCadastrarMdCorContrato'])) {
                try {
                    $objMdCorContratoRN = new MdCorContratoRN();
                    $objMdCorContratoDTO = $objMdCorContratoRN->cadastrar($_POST);
                    PaginaSEI::getInstance()->adicionarMensagem('contrato cadastrado com sucesso.');
                    header('Location: ' . SessaoSEI::getInstance()->assinarLink('controlador.php?acao=' . PaginaSEI::getInstance()->getAcaoRetorno() . '&acao_origem=' . $_GET['acao'] . '&id_md_cor_contrato=' . $objMdCorContratoDTO->getNumIdMdCorContrato() . PaginaSEI::getInstance()->montarAncora($objMdCorContratoDTO->getNumIdMdCorContrato())));
                    die;
                } catch (Exception $e) {
                    PaginaSEI::getInstance()->processarExcecao($e);
                }
            }
            break;

        case 'md_cor_contrato_alterar':
            $strTitulo = 'Alterar Contrato';
            $arrComandos[] = '<button type="submit" accesskey="S" name="sbmAlterarMdCorContrato" value="Salvar" class="infraButton"><span class="infraTeclaAtalho">S</span>alvar</button>';
            $strDesabilitar = 'disabled="disabled"';

            $idMdCorContrato = $_GET['id_md_cor_contrato'] ? $_GET['id_md_cor_contrato'] : $_POST['hdnIdMdCorContrato'];
            $objMdCorContratoDTO->setNumIdMdCorContrato($idMdCorContrato);

            $arrComandos[] = '<button type="button" accesskey="C" name="btnCancelar" id="btnCancelar" value="Cancelar" onclick="location.href=\'' . SessaoSEI::getInstance()->assinarLink('controlador.php?acao=' . PaginaSEI::getInstance()->getAcaoRetorno() . '&acao_origem=' . $_GET['acao'] . PaginaSEI::getInstance()->montarAncora($objMdCorContratoDTO->getNumIdMdCorContrato())) . '\';" class="infraButton"><span class="infraTeclaAtalho">C</span>ancelar</button>';

            if (isset($_POST['sbmAlterarMdCorContrato'])) {
                try {
                    $objMdCorContratoRN = new MdCorContratoRN();
                    $objMdCorContratoRN->alterar($_POST);
                    PaginaSEI::getInstance()->adicionarMensagem('contrato "' . $objMdCorContratoDTO->getNumIdMdCorContrato() . '" alterado com sucesso.');
                    header('Location: ' . SessaoSEI::getInstance()->assinarLink('controlador.php?acao=' . PaginaSEI::getInstance()->getAcaoRetorno() . '&acao_origem=' . $_GET['acao'] . '&id_me_cor_contrato=' . $_GET['id_md_cor_contrato'] . PaginaSEI::getInstance()->montarAncora($objMdCorContratoDTO->getNumIdMdCorContrato())));
                    die;
                } catch (Exception $e) {
                    PaginaSEI::getInstance()->processarExcecao($e);
                }
            }

            break;

        case 'md_cor_contrato_consultar':
            $strTitulo = 'Consultar Contrato';
            $arrComandos[] = '<button type="button" accesskey="C" name="btnFechar" value="Fechar" onclick="location.href=\'' . SessaoSEI::getInstance()->assinarLink('controlador.php?acao=' . PaginaSEI::getInstance()->getAcaoRetorno() . '&acao_origem=' . $_GET['acao'] . PaginaSEI::getInstance()->montarAncora($_GET['id_md_cor_contrato'])) . '\';" class="infraButton">Fe<span class="infraTeclaAtalho">c</span>har</button>';
            $objMdCorContratoDTO->setNumIdMdCorContrato($_GET['id_md_cor_contrato']);
            $objMdCorContratoDTO->setBolExclusaoLogica(false);

            $objMdCorContratoDTO->retStrNumeroContrato();
            $objMdCorContratoDTO->retStrNumeroContratoCorreio();
            $objMdCorContratoDTO->retStrNumeroCartaoPostagem();
            $objMdCorContratoDTO->retDblIdProcedimento();
            $objMdCorContratoDTO->retStrSinAtivo();
            $objMdCorContratoDTO->retNumNumeroCnpj();
            $objMdCorContratoDTO->retNumIdMdCorDiretoria();

            $objMdCorContratoRN = new MdCorContratoRN();
            $objMdCorContratoDTO = $objMdCorContratoRN->consultar($objMdCorContratoDTO);
            if ($objMdCorContratoDTO === null) {
                throw new InfraException("Registro não encontrado.");
            }

            break;

        default:
            throw new InfraException("Ação '" . $_GET['acao'] . "' não reconhecida.");
    }

    if ($_GET['acao'] == 'md_cor_contrato_alterar' || $_GET['acao'] == 'md_cor_contrato_consultar') {

        $idMdCorContrato = $_GET['id_md_cor_contrato'] ? $_GET['id_md_cor_contrato'] : $_POST['hdnIdMdCorContrato'];

        if ($idMdCorContrato) {
            $objMdCorContratoDTO->retNumIdMdCorContrato();
            $objMdCorContratoDTO->retStrNumeroContrato();
            $objMdCorContratoDTO->retStrNumeroContratoCorreio();
            $objMdCorContratoDTO->retStrNumeroCartaoPostagem();
            $objMdCorContratoDTO->retNumNumeroCnpj();
            $objMdCorContratoDTO->retNumIdMdCorDiretoria();
            $objMdCorContratoDTO->retDblIdProcedimento();
            $objMdCorContratoDTO->retStrSinAtivo();
            $objMdCorContratoDTO->setBolExclusaoLogica(false);
            $objMdCorContratoRN = new MdCorContratoRN();
            $objMdCorContratoDTO = $objMdCorContratoRN->consultar($objMdCorContratoDTO);
            $strIdMdCorContrato = $objMdCorContratoDTO->getNumIdMdCorContrato();

            $slCodigoDiretoria = $objMdCorContratoDTO->getNumIdMdCorDiretoria();
            if ($objMdCorContratoDTO == null) {
                throw new InfraException("Registro não encontrado.");
            }


            if ($objMdCorContratoDTO->getDblIdProcedimento() != '') {
                $objProtocoloRN = new ProtocoloRN();
                $objProtocoloDTO = new ProtocoloDTO();
                $objProtocoloDTO->setDblIdProtocolo($objMdCorContratoDTO->getDblIdProcedimento());
                $objProtocoloDTO->retTodos();
                $objProtocoloDTO = $objProtocoloRN->consultarRN0186($objProtocoloDTO);
                $strProtocoloFormatado = $objProtocoloDTO->getStrProtocoloFormatado();

                $objMdCorContratoRN = new MdCorContratoRN();
                $objProtocoloDTO = new ProtocoloDTO();
                $objProtocoloDTO->setStrProtocoloFormatadoPesquisa($strProtocoloFormatado);
                $objProtocoloDTO = $objMdCorContratoRN->pesquisarProtocoloFormatado($objProtocoloDTO);
                $strTipoProtocolo = $objProtocoloDTO->getStrNomeTipoProcedimentoProcedimento();
            }
        }
    }


} catch (Exception $e) {
    PaginaSEI::getInstance()->processarExcecao($e);
}
$mdCorDiretorioInt = MdCorDiretoriaINT::montarSelectIdMdCorDiretoria('null', '', $slCodigoDiretoria);
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

        <div class="row mb-3 linha">
            <input type="hidden" id="id_contrato" name="id_contrato" value="<?= $strIdMdCorContrato; ?>"/>
            <input type="hidden" id="hdnIdMdCorContrato" name="hdnIdMdCorContrato" value="<?= $strIdMdCorContrato; ?>"/>
            
            <div class="col-sm-12 col-md-12 col-lg-12 col-xl-12">
                <fieldset id="fieldsetContratoOrgao" class="infraFieldset form-control" style="height: 100%">
                    <legend class="infraLegend">&nbsp;Dados do Contrato no Órgão&nbsp;</legend>
                    <div class="row">
                        <div class="col-sm-7 col-md-7 col-lg-6">
                            <label id="lblNumeroContrato" for="txtNumeroContrato" accesskey="o"
                                   class="infraLabelObrigatorio">Númer<span class="infraTeclaAtalho">o</span> do
                                Contrato:
                                <img align="top" id="imgAjuda"
                                     src="<?= PaginaSEI::getInstance()->getDiretorioSvgGlobal() ?>/ajuda.svg"
                                     onmouseover="return infraTooltipMostrar('Informar o Número de identificação do Contrato no Órgão.', 'Ajuda');"
                                     onmouseout="return infraTooltipOcultar();"
                                     alt="Ajuda" class="infraImgModulo"/>
                            </label>
                            <input type="text" id="txtNumeroContrato" name="txtNumeroContrato"
                                   class="infraText form-control"
                                   value="<?= PaginaSEI::tratarHTML($objMdCorContratoDTO->getStrNumeroContrato()); ?>"
                                   onkeypress="return infraMascaraTexto(this,event,20);" maxlength="20"
                                   tabindex="<?= PaginaSEI::getInstance()->getProxTabDados() ?>"/>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-sm-7 col-md-7 col-lg-6">
                            <label id="lblNumeroProcessoContratacao" for="txtNumeroProcessoContratacao" accesskey="t"
                                   class="infraLabelOpcional">Número do Processo de Con<span class="infraTeclaAtalho">t</span>ratação:
                                <img align="top" id="imgAjuda"
                                     src="<?= PaginaSEI::getInstance()->getDiretorioSvgGlobal() ?>/ajuda.svg"
                                     name="ajuda"
                                     onmouseover="return infraTooltipMostrar('Informar o Número do Processo no SEI por meio do qual os Correios foi Contratado.', 'Ajuda');"
                                     onmouseout="return infraTooltipOcultar();"
                                     alt="Ajuda" class="infraImgModulo"/>
                            </label>
                            <div class="input-group mb-3">
                                <input type="text" id="txtNumeroProcessoContratacao" name="txtNumeroProcessoContratacao"
                                       onfocus="limparNumeroProcedimento()" class="infraText form-control" value="<?= $strProtocoloFormatado; ?>"
                                       onkeypress="return infraMascaraTexto(this,event,50);" maxlength="50"
                                       tabindex="<?= PaginaSEI::getInstance()->getProxTabDados() ?>"/>
                                <input type="hidden" id="hdnNumeroProcessoContratacao" name="hdnNumeroProcessoContratacao"
                                       value="<?= $strProtocoloFormatado; ?>"/>
                                <input type="hidden" id="hdnIdProcedimento" name="hdnIdProcedimento"
                                       value="<?= PaginaSEI::tratarHTML($objMdCorContratoDTO->getDblIdProcedimento()); ?>"
                                       onkeypress="return infraMascaraTexto(this,event,50);" maxlength="50"
                                       tabindex="<?= PaginaSEI::getInstance()->getProxTabDados() ?>"/>
                                <? if ($_GET['acao'] == 'md_cor_contrato_cadastrar' || $_GET['acao'] == 'md_cor_contrato_alterar') { ?>
                                    <button id="validar-processo-contratacao" class="infraButton" onclick="validarNumeroProcesso()"
                                            type="button">Validar
                                    </button>
                                <? } ?>
                            </div>
                        </div>

                        <? if ($_GET['acao'] == 'md_cor_contrato_cadastrar' || $_GET['acao'] == 'md_cor_contrato_alterar') { ?>
                            <div class="col-sm-5 col-md-5 col-lg-6" id="divLblTipoProcessoContratacao" style="padding-top:30px;">
                                <input type="text" id="txtTipoProcessoContratacao" disabled class="infraText form-control">
                            </div>
                        <? } ?>
                    </div>
                </fieldset>
            </div>
        </div>

        <!-- Padrão de largura para as div abaixo -->
        <?php $cls_def = "col-sm-7 col-md-7 col-lg-6" ?>

        <div class="row linha">
            <div class="col-12">
                <fieldset id="fieldsetContratoCorreios" class="infraFieldset form-control" style="height: 100%">
                    <legend class="infraLegend">&nbsp;Dados do Contrato nos Correios&nbsp;</legend>

                    <div class="row">
                        <div class="<?= $cls_def ?> mb-1">
                            <label id="lblNumeroContratoCorreio" for="txtNumeroContratoCorreio" accesskey="n"
                                   class="infraLabelObrigatorio"><span class="infraTeclaAtalho">N</span>úmero do
                                Contrato:
                                <img
                                        align="top" id="imgAjuda"
                                        src="<?= PaginaSEI::getInstance()->getDiretorioSvgGlobal() ?>/ajuda.svg"
                                        onmouseover="return infraTooltipMostrar('Código interno dos Correios de identificação do Contrato, utilizado na integração com o Web Service do SIGEP WEB. \n \n Se for informado número incorreto não vai validar o Endereço WSDL do Web Service do SIGEP WEB.', 'Ajuda');"
                                        onmouseout="return infraTooltipOcultar();"
                                        alt="Ajuda" class="infraImgModulo"/>
                            </label>
                            <input type="text" id="txtNumeroContratoCorreio" name="txtNumeroContratoCorreio"
                                   class="infraText form-control"
                                   value="<?= PaginaSEI::tratarHTML($objMdCorContratoDTO->getStrNumeroContratoCorreio()); ?>"
                                   onkeypress="return infraMascaraTexto(this,event,10);" maxlength="10"
                                   tabindex="<?= PaginaSEI::getInstance()->getProxTabDados() ?>"/>
                        </div>
                    </div>
                    <div class="row">
                        <div class="<?= $cls_def ?>">
                            <label id="lblNumeroCartaoPostagem" for="txtNumeroCartaoPostagem" accesskey="p"
                                   class="infraLabelObrigatorio">Cartão
                                de <span class="infraTeclaAtalho">P</span>ostagem:
                                <img align="top"
                                     id="imgAjuda"
                                     src="<?= PaginaSEI::getInstance()->getDiretorioSvgGlobal() ?>/ajuda.svg"
                                     onmouseover="return infraTooltipMostrar('Informar o Cartão de Postagem correspondente ao Contrato do Órgão.', 'Ajuda');"
                                     onmouseout="return infraTooltipOcultar();"
                                     alt="Ajuda" class="infraImgModulo"/>
                            </label>
                            <input type="text" id="txtNumeroCartaoPostagem" name="txtNumeroCartaoPostagem"
                                   class="infraText form-control"
                                   value="<?= PaginaSEI::tratarHTML($objMdCorContratoDTO->getStrNumeroCartaoPostagem()); ?>"
                                   onkeypress="return infraMascaraTexto(this,event,10);" maxlength="10"
                                   tabindex="<?= PaginaSEI::getInstance()->getProxTabDados() ?>"/>
                        </div>
                    </div>
                    <div class="row">
                        <div class="<?= $cls_def ?>">
                            <label id="lblCNPJ" for="txtCNPJ" class="infraLabelObrigatorio">CNPJ do Órgão: <img
                                        align="top"
                                        id="imgAjuda"
                                        src="<?= PaginaSEI::getInstance()->getDiretorioSvgGlobal() ?>/ajuda.svg"
                                        onmouseover="return infraTooltipMostrar('Informar o CNPJ do Órgão correspondente ao Contrato.', 'Ajuda');"
                                        onmouseout="return infraTooltipOcultar();"
                                        alt="Ajuda" class="infraImgModulo"/>
                            </label>
                            <input type="text" id="txtCNPJ" name="txtCNPJ" class="infraText form-control"
                                   value="<?= PaginaSEI::tratarHTML(InfraUtil::formatarCnpj($objMdCorContratoDTO->getNumNumeroCnpj())); ?>"
                                   onkeypress="return infraMascaraCnpj(this, event);" maxlength="18"
                                   tabindex="<?= PaginaSEI::getInstance()->getProxTabDados() ?>"/>
                        </div>
                    </div>
                    <div class="row">
                        <div class="<?= $cls_def ?>">
                            <label id="lblCodigoDiretoria" for="slCodigoDiretoria" class="infraLabelOpcional">Código da Diretoria:
                                <img align="top" id="imgAjuda"
                                     src="<?= PaginaSEI::getInstance()->getDiretorioSvgGlobal() ?>/ajuda.svg"
                                     onmouseover="return infraTooltipMostrar('Diretoria Regional dos Correios correspondente ao Contrato do Órgão.', 'Ajuda');"
                                     onmouseout="return infraTooltipOcultar();"
                                     alt="Ajuda" class="infraImgModulo"/>
                            </label>
                            <select class="infraSelect form-control" name="slCodigoDiretoria" id="slCodigoDiretoria"
                                    tabindex="<?= PaginaSEI::getInstance()->getProxTabDados(); ?>">
                                <?php echo $mdCorDiretorioInt; ?>
                            </select>
                        </div>
                    </div>
                    <?php 
                    $numServicosPostais = 0;
                    $numTokens = 0;
                    $qtdMapeamentos = 0;

                    if ($_GET['acao'] == 'md_cor_contrato_alterar' || $_GET['acao'] == 'md_cor_contrato_consultar') {
                        $objMdCorServicoPostalDTO = new MdCorServicoPostalDTO();
                        $objMdCorServicoPostalRN = new MdCorServicoPostalRN();
                        $objMdCorServicoPostalDTO->setNumIdMdCorContrato($idMdCorContrato);
                        $objMdCorServicoPostalDTO->retTodos(false);
                        $numServicosPostais = $objMdCorServicoPostalRN->contar($objMdCorServicoPostalDTO);

                        $objMdCorAdmIntegracaoTokensDTO = new MdCorAdmIntegracaoTokensDTO();
                        $objMdCorAdmIntegracaoTokensRN = new MdCorAdmIntegracaoTokensRN();
                        $objMdCorAdmIntegracaoTokensDTO->setNumIdMdCorContrato($idMdCorContrato);
                        $objMdCorAdmIntegracaoTokensDTO->retNumIdMdCorContrato();
                        $numTokens = $objMdCorAdmIntegracaoTokensRN->contar($objMdCorAdmIntegracaoTokensDTO);

                        $objMdCorMapUnidServicoDTO = new MdCorMapUnidServicoDTO();
                        $objMdCorMapUnidServicoRN = new MdCorMapUnidServicoRN();
                        $objMdCorMapUnidServicoDTO->setStrIdMdCorContrato($idMdCorContrato);
                        $objMdCorMapUnidServicoDTO->retStrIdMdCorContrato();
                        $qtdMapeamentos = $objMdCorMapUnidServicoRN->contar($objMdCorMapUnidServicoDTO);
                    }
                    
                    if ($numServicosPostais == 0 || $numTokens == 0 || $qtdMapeamentos == 0) {
                    ?>
                        <br>
                        <div class="row">
                            <div class="col-12">
                                <div class="alert alert-warning" role="alert">
                                    <strong>Atenção!</strong> Após a inclusão deste Contrato, é necessário seguir os seguintes passos:
                                    <br>
                                    <?php 
                                    if($numTokens == 0) {
                                    ?>
                                        <br>
                                        - Inclusão do Token no Mapeamento da Integração em Administração > Correios > Mapeamento das Integrações > Correios::Gerar Token
                                    <?php 
                                    }
                                    ?>
                                    <?php 
                                    if($numServicosPostais == 0) {
                                    ?>
                                        <br>
                                        - Configurar Serviços Postais em Administração > Correios > Botão de Serviços Postais no Contrato inserido
                                    <?php 
                                    }
                                    ?>
                                    <?php 
                                    if($qtdMapeamentos == 0) {
                                    ?>
                                        <br>
                                        - Configurar Mapeamento das Unidades Solicitantes com os Serviços Postais em Administração > Correios > Mapeamento Unidades e Serviços Postais
                                    <?php 
                                    }
                                    ?>
                                </div>
                            </div>
                        </div>
                    <?php 
                    }
                    ?>
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
include_once('md_cor_contrato_cadastro_js.php');
?>