<?
/**
 * TRIBUNAL REGIONAL FEDERAL DA 4ª REGIÃO
 *
 * 12/09/2018 - criado por augusto.cast
 *
 * Versão do Gerador de Código: 1.41.0
 */

try {
    require_once dirname(__FILE__) . '/../../SEI.php';

    session_start();

    //////////////////////////////////////////////////////////////////////////////
    //InfraDebug::getInstance()->setBolLigado(false);
    //InfraDebug::getInstance()->setBolDebugInfra(true);
    //InfraDebug::getInstance()->limpar();
    //////////////////////////////////////////////////////////////////////////////

    SessaoSEI::getInstance()->validarLink();

    PaginaSEI::getInstance()->verificarSelecao('md_cor_parametro_rastreio_selecionar');

    SessaoSEI::getInstance()->validarPermissao($_GET['acao']);

    $objMdCorParametroRastreioDTO = new MdCorParametroRastreioDTO();

    $strDesabilitar = '';
    $strLinkAjaxValidacaoEndereco = SessaoSEI::getInstance()->assinarLink('controlador_ajax.php?acao_ajax=md_cor_valida_endereco_rastreio');
    $arrComandos = array();

    switch ($_GET['acao']) {
        case 'md_cor_parametrizacao_rastreio_listar':
            $strTitulo = 'Integração SRO';
            $arrComandos[] = '<button type="submit" accesskey="S" name="sbmCadastrarMdCorParametroRastreio" value="Salvar" class="infraButton"><span class="infraTeclaAtalho">S</span>alvar</button>';
            $arrComandos[] = '<button type="button" accesskey="C" name="btnCancelarPagina" id="btnCancelarPagina" value="Cancelar" onclick="location.href=\'' . SessaoSEI::getInstance()->assinarLink('controlador.php?acao=' . PaginaSEI::getInstance()->getAcaoRetorno() . '&acao_origem=' . $_GET['acao']) . '\';" class="infraButton"><span class="infraTeclaAtalho">C</span>ancelar</button>';

            $objMdCorParametroRastreioDTO->retTodos();
            $objMdCorParametroRastreioRN = new MdCorParametroRastreioRN();
            $qtdObjMdCorParametroRastreioDTO = $objMdCorParametroRastreioRN->contar($objMdCorParametroRastreioDTO);
            $objMdCorParametroRastreioDTO = $objMdCorParametroRastreioRN->consultar($objMdCorParametroRastreioDTO);

            if (isset($_POST['sbmCadastrarMdCorParametroRastreio'])) {
                try {
                    if ($qtdObjMdCorParametroRastreioDTO == 0) {
                        $objMdCorParametroRastreioDTO = new MdCorParametroRastreioDTO();
                    }
                    $objMdCorParametroRastreioDTO->setStrUsuario($_POST['txtUsuario']);
                    $objMdCorParametroRastreioDTO->setStrSenha($_POST['txtSenha']);
                    $objMdCorParametroRastreioDTO->setStrEnderecoWsdl($_POST['txtEnderecoWsdl']);
                    $objMdCorParametroRastreioRN = new MdCorParametroRastreioRN();
                    if ($qtdObjMdCorParametroRastreioDTO > 0) {
                        $objMdCorParametroRastreioRN->alterar($objMdCorParametroRastreioDTO);
                    } else {
                        $objMdCorParametroRastreioDTO = $objMdCorParametroRastreioRN->cadastrar($objMdCorParametroRastreioDTO);
                    }
                    PaginaSEI::getInstance()->adicionarMensagem('Dados salvos com sucesso.', InfraPagina::$TIPO_MSG_AVISO);
                    header('Location: ' . SessaoSEI::getInstance()->assinarLink('controlador.php?acao=md_cor_parametrizacao_rastreio_listar'));
                    die;
                } catch (Exception $e) {
                    PaginaSEI::getInstance()->processarExcecao($e);
                }
            }
            break;


        case 'md_cor_parametro_rastreio_consultar':
            $strTitulo = 'Consultar ';
            $arrComandos[] = '<button type="button" accesskey="C" name="btnFechar" value="Fechar" onclick="location.href=\'' . SessaoSEI::getInstance()->assinarLink('controlador.php?acao=' . PaginaSEI::getInstance()->getAcaoRetorno() . '&acao_origem=' . $_GET['acao'] . PaginaSEI::getInstance()->montarAncora()) . '\';" class="infraButton">Fe<span class="infraTeclaAtalho">c</span>har</button>';
            $objMdCorParametroRastreioDTO->setBolExclusaoLogica(false);
            $objMdCorParametroRastreioDTO->retTodos();
            $objMdCorParametroRastreioRN = new MdCorParametroRastreioRN();
            $objMdCorParametroRastreioDTO = $objMdCorParametroRastreioRN->consultar($objMdCorParametroRastreioDTO);
            if ($objMdCorParametroRastreioDTO === null) {
                throw new InfraException("Registro não encontrado.");
            }
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
PaginaSEI::getInstance()->fecharStyle();
PaginaSEI::getInstance()->montarJavaScript();
PaginaSEI::getInstance()->abrirJavaScript();
PaginaSEI::getInstance()->fecharJavaScript();
PaginaSEI::getInstance()->fecharHead();
PaginaSEI::getInstance()->abrirBody($strTitulo, 'onload="inicializar();"');
?>
    <form id="frmMdCorParametroRastreioCadastro" method="post" onsubmit="return OnSubmitForm();" action="<?= SessaoSEI::getInstance()->assinarLink('controlador.php?acao=' . $_GET['acao'] . '&acao_origem=' . $_GET['acao']) ?>">
        <? PaginaSEI::getInstance()->montarBarraComandosSuperior($arrComandos); ?>
        <div class="row">
            <div class="col-12 col-sm-12 col-md-6 col-lg-4 col-xl-3">
                <div class="form-group">
                    <label id="lblUsuario" for="txtUsuario" class="infraLabelObrigatorio">Usuário:
                        <img align="top" style="height:20px; width:20px;" id="imgAjuda" name="ajuda" src="<?= PaginaSEI::getInstance()->getDiretorioSvgGlobal(); ?>/ajuda.svg"  onmouseover="return infraTooltipMostrar('Obtenha um Nome de Usuário válido para autenticação no SRO com o consultor comercial dos Correios que atende ao Contrato do Órgão.', 'Ajuda');" onmouseout="return infraTooltipOcultar();" alt="Ajuda" class="infraImg">
                    </label>
                    <input type="text" id="txtUsuario" name="txtUsuario" class="infraText form-control"
                            onblur="validainput()"
                            value="<?= !is_null($objMdCorParametroRastreioDTO) ? PaginaSEI::tratarHTML($objMdCorParametroRastreioDTO->getStrUsuario()) : null; ?>"
                            onkeypress="return infraMascaraTexto(this,event,100);" maxlength="100"
                            tabindex="<?= PaginaSEI::getInstance()->getProxTabDados() ?>"/>
                </div>
            </div>
            <div class="col-12 col-sm-12 col-md-6 col-lg-4 col-xl-3">
                <div class="form-group">
                    <label id="lblSenha" for="txtSenha" class="infraLabelObrigatorio">
                        Senha:
                        <img align="top" style="height:20px; width:20px;" id="imgAjuda" src="<?= PaginaSEI::getInstance()->getDiretorioSvgGlobal(); ?>/ajuda.svg" name="ajuda" onmouseover="return infraTooltipMostrar('A Senha de acesso ao SRO é fornecida junto com o Nome de Usuário para autenticação informado pelo consultor comercial dos Correios que atende ao Contrato do Órgão.', 'Ajuda');" onmouseout="return infraTooltipOcultar();" alt="Ajuda" class="infraImg">
                    </label>
                    <input type="password" id="txtSenha" name="txtSenha" class="infraText form-control"
                        onblur="validainput()"
                        value="<?= !is_null($objMdCorParametroRastreioDTO) ? $objMdCorParametroRastreioDTO->getStrSenha() : null; ?>"
                        onkeypress="return infraMascaraTexto(this,event,100);" maxlength="100"
                        tabindex="<?= PaginaSEI::getInstance()->getProxTabDados() ?>"/>
                </div>
            </div>
        </div>
        <div class="row">
            <div class="col-12 col-sm-12 col-md-12 col-lg-8 col-xl-6">
                <div class="form-group">
                    <label id="lblEnderecoWsdl" for="txtEnderecoWsdl" class="infraLabelObrigatorio">
                        Endereço WSDL do Web Service do SRO: 
                        <img align="top" style="height:20px; width:20px;" src="<?= PaginaSEI::getInstance()->getDiretorioSvgGlobal(); ?>/ajuda.svg" name="ajuda" onmouseover="return infraTooltipMostrar('Obtenha o Endereço WSDL correto acessando o Manual do Web Service de Rastreamento de Objetos dos Correios.', 'Ajuda');" onmouseout="return infraTooltipOcultar();" alt="Ajuda" class="infraImg">
                    </label>
                    <div class="input-group">
                        <input type="text" id="txtEnderecoWsdl" name="txtEnderecoWsdl"
                                class="infraText form-control"
                                onblur="validainput()"
                                value="<?= !is_null($objMdCorParametroRastreioDTO) ? PaginaSEI::tratarHTML($objMdCorParametroRastreioDTO->getStrEnderecoWsdl()) : null; ?>"
                                onkeypress="return infraMascaraTexto(this,event,500);" maxlength="500"
                                tabindex="<?= PaginaSEI::getInstance()->getProxTabDados() ?>"/>
                        <button id="btnValidacao" tabindex="<?= PaginaSEI::getInstance()->getProxTabDados() ?>"
                                type="button"
                                accesskey="v" name="btnValidacao" value="Validar" class="infraButton"
                                onclick="validarEnderecoWs()">
                            <span class="infraTeclaAtalho">V</span>alidar
                            <input name="hdnValidacao" value="" type="hidden" id="hdnValidacao"/>
                        </button>
                    </div>
                </div>
            </div>
        </div>
        <? PaginaSEI::getInstance()->montarBarraComandosInferior($arrComandos); ?>
    </form>
<?
require_once("md_cor_parametrizacao_rastreio_lista_js.php");
PaginaSEI::getInstance()->fecharBody();
PaginaSEI::getInstance()->fecharHtml();