<?

try {
    require_once dirname(__FILE__) . '/../../SEI.php';

    session_start();
    SessaoSEI::getInstance()->validarLink();
    SessaoSEI::getInstance()->validarPermissao($_GET['acao']);

    $objListaStatusDTO = new MdCorListaStatusDTO();
    $strDesabilitar = '';

    $arrComandos = array();

    switch ($_GET['acao']) {
        case 'md_cor_parametrizacao_status_cadastrar':
            $strTitulo = 'Novo Tipos de Situações SRO';
            $arrComandos[] = '<button type="submit" accesskey="S" name="sbmCadastrarStatusSro" value="Salvar" class="infraButton"><span class="infraTeclaAtalho">S</span>alvar</button>';
            $arrComandos[] = '<button type="button" accesskey="C" name="btnCancelar" id="btnCancelar" value="Cancelar" onclick="location.href=\'' . SessaoSEI::getInstance()->assinarLink('controlador.php?acao=' . PaginaSEI::getInstance()->getAcaoRetorno() . '&acao_origem=' . $_GET['acao']) . '\';" class="infraButton"><span class="infraTeclaAtalho">C</span>ancelar</button>';

            $objListaStatusDTO->setNumIdMdCorListaStatus(null);
            $objListaStatusDTO->setStrSinAtivo('S');
            popularListaStatusDTO($objListaStatusDTO);

            if (isset($_POST['sbmCadastrarStatusSro'])) {
                try {
                    $objListaStatusRN = new MdCorListaStatusRN();
                    $objListaStatusDTO = $objListaStatusRN->cadastrar($objListaStatusDTO);

                    PaginaSEI::getInstance()->adicionarMensagem('Tipos de Situações SRO cadastrado com sucesso.');
                    header('Location: ' . SessaoSEI::getInstance()->assinarLink('controlador.php?acao=' . PaginaSEI::getInstance()->getAcaoRetorno() . '&acao_origem=' . $_GET['acao'] . '&id_md_cor_lista_status=' . $objListaStatusDTO->getNumIdMdCorListaStatus() . '&id_md_cor_contrato=' . $objListaStatusDTO->getNumIdMdCorListaStatus() . PaginaSEI::getInstance()->montarAncora($objListaStatusDTO->getNumIdMdCorListaStatus())));
                    die;
                } catch (Exception $e) {
                    PaginaSEI::getInstance()->processarExcecao($e);
                }
            }
            break;

        case 'md_cor_parametrizacao_status_alterar':
            $strTitulo = 'Alterar Tipos de Situações SRO';
            $arrComandos[] = '<button type="submit" accesskey="S" name="sbmAlterarStatusSro" value="Salvar" class="infraButton"><span class="infraTeclaAtalho">S</span>alvar</button>';
            $strDesabilitar = 'disabled="disabled"';

            $strIdMdCorListaStatus = $_GET['id_md_cor_lista_status'];

            if (isset($strIdMdCorListaStatus)) {

                $objListaStatusDTO->setNumIdMdCorListaStatus($strIdMdCorListaStatus);
                $objListaStatusDTO->retTodos(true);
                $objListaStatusRN = new MdCorListaStatusRN();
                $objListaStatusDTO = $objListaStatusRN->consultar($objListaStatusDTO);

                if ($objListaStatusDTO == null) {
                    throw new InfraException("Registro não encontrado.");
                }

            } else {
                $objListaStatusDTO->setNumIdMdCorListaStatus($_POST['hdnIdMdCorListaStatus']);
                popularListaStatusDTO($objListaStatusDTO);
            }

            $arrComandos[] = '<button type="button" accesskey="C" name="btnCancelar" id="btnCancelar" value="Cancelar" onclick="location.href=\'' . SessaoSEI::getInstance()->assinarLink('controlador.php?acao=' . PaginaSEI::getInstance()->getAcaoRetorno() . '&acao_origem=' . $_GET['acao']) . '#ID-' . $objListaStatusDTO->getNumIdMdCorListaStatus() . '\';" class="infraButton"><span class="infraTeclaAtalho">C</span>ancelar</button>';

            if (isset($_POST['sbmAlterarStatusSro'])) {
                try {
                    $objListaStatusRN = new MdCorListaStatusRN();
                    $objListaStatusRN->alterar($objListaStatusDTO);

                    PaginaSEI::getInstance()->adicionarMensagem('Tipos de Situações SRO alterado com sucesso.');
                    header('Location: ' . SessaoSEI::getInstance()->assinarLink('controlador.php?acao=' . PaginaSEI::getInstance()->getAcaoRetorno() . '&acao_origem=' . $_GET['acao'] . '&id_md_cor_lista_status=' . $objListaStatusDTO->getNumIdMdCorListaStatus() . '&id_md_cor_contrato=' . $objListaStatusDTO->getNumIdMdCorListaStatus() . PaginaSEI::getInstance()->montarAncora($objListaStatusDTO->getNumIdMdCorListaStatus())));
                    die;
                } catch (Exception $e) {
                    PaginaSEI::getInstance()->processarExcecao($e);
                }
            }
            break;

        case 'md_cor_parametrizacao_status_consultar':
            $strTitulo = "Consultar Tipos de Situações SRO";
            $arrComandos[] = '<button type="button" accesskey="F" name="btnFechar" value="Fechar" onclick="location.href=\'' . SessaoSEI::getInstance()->assinarLink('controlador.php?acao=' . PaginaSEI::getInstance()->getAcaoRetorno() . '&id_md_cor_lista_status=' . $_GET['id_md_cor_lista_status'] . '&acao_origem=' . $_GET['acao']) . '#ID-' . $_GET['id_md_cor_lista_status'] . '\';" class="infraButton">Fe<span class="infraTeclaAtalho">c</span>har</button>';

            $objListaStatusDTO->setNumIdMdCorListaStatus($_GET['id_md_cor_lista_status']);
            $objListaStatusDTO->retTodos(true);
            $objListaStatusRN = new MdCorListaStatusRN();
            $objListaStatusDTO = $objListaStatusRN->consultar($objListaStatusDTO);
            if ($objListaStatusDTO === null) {
                throw new InfraException("Registro não encontrado.");
            }
            break;

        default:
            throw new InfraException("Ação '" . $_GET['acao'] . "' não reconhecida.");
    }

    $strItensSelRastreioModulo = MdCorListaStatusINT::montarSelectSituacaoRastreio('null', '&nbsp;', $objListaStatusDTO->getStrStaRastreioModulo());

} catch (Exception $e) {
    PaginaSEI::getInstance()->processarExcecao($e);
}

function popularListaStatusDTO(MdCorListaStatusDTO $objListaStatusDTO)
{
    $objListaStatusDTO->setNumStatus($_POST['txtCodigoStatusSro']);
    $objListaStatusDTO->setStrTipo($_POST['txtNovoTipo']);
    $objListaStatusDTO->setStrStaRastreioModulo($_POST['selSituacaoRastreio']);
    $objListaStatusDTO->setStrDescricao($_POST['txtDescricaoSRO']);
    //$objListaStatusDTO->setStrDescricaoObjeto($_POST['txtDescricaoRastreioObjeto']);
    //$objListaStatusDTO->setStrDetalhe(trim($_POST['txtDetalhe']));
}

PaginaSEI::getInstance()->montarDocType();
PaginaSEI::getInstance()->abrirHtml();
PaginaSEI::getInstance()->abrirHead();
PaginaSEI::getInstance()->montarMeta();
PaginaSEI::getInstance()->montarTitle(PaginaSEI::getInstance()->getStrNomeSistema() . ' - ' . $strTitulo);
PaginaSEI::getInstance()->montarStyle();
PaginaSEI::getInstance()->abrirStyle();
include_once 'md_cor_parametrizacao_status_css.php';
PaginaSEI::getInstance()->fecharStyle();
PaginaSEI::getInstance()->montarJavaScript();
PaginaSEI::getInstance()->abrirJavaScript();
PaginaSEI::getInstance()->fecharJavaScript();
PaginaSEI::getInstance()->fecharHead();
PaginaSEI::getInstance()->abrirBody($strTitulo, 'onload="inicializar();"');
?>

<form id="frmTipoStatusSro" method="post" onsubmit="return OnSubmitForm();"
      action="<?= SessaoSEI::getInstance()->assinarLink('controlador.php?acao=' . $_GET['acao'] . '&acao_origem=' . $_GET['acao']) ?>">

    <?
    PaginaSEI::getInstance()->montarBarraComandosSuperior($arrComandos);
    PaginaSEI::getInstance()->abrirAreaDados();
    $col_def = 'col-sm-10 col-md-9 col-lg-7';
    ?>
    
        <div class="row mb-2">
            <div class="<?= $col_def ?>">
                <label id="lblCodigoStatusSro" for="txtCodigoStatusSro" accesskey="" class="infraLabelObrigatorio">
                    Código Situação SRO:
                </label>
                <input type="text" id="txtCodigoStatusSro" name="txtCodigoStatusSro"
                        onkeypress="return infraMascaraNumero(this, event)" class="infraText form-control"
                        value="<?= PaginaSEI::tratarHTML($objListaStatusDTO->getNumStatus()); ?>"
                        tabindex="<?= PaginaSEI::getInstance()->getProxTabDados() ?>"/>
            </div>
        </div>

        <div class="row mb-2">
            <div class="<?= $col_def ?>">
                <label id="lblNovoTipo" for="txtNovoTipo" accesskey="" class="infraLabelObrigatorio">
                    Tipo SRO:
                </label>
                <input type="text" id="txtNovoTipo" name="txtNovoTipo" class="infraText form-control"
                        value="<?= PaginaSEI::tratarHTML($objListaStatusDTO->getStrTipo()); ?>"
                        tabindex="<?= PaginaSEI::getInstance()->getProxTabDados() ?>"/>
            </div>
        </div>

        <div class="row mb-2">
            <div class="<?= $col_def ?>">
                <label id="lblDescricaoSRO" for="txtDescricaoSRO" class="infraLabelObrigatorio">
                    Descrição SRO:
                </label>
                <input type="text" id="txtDescricaoSRO" name="txtDescricaoSRO" maxlength="250"
                        class="infraText form-control"
                        value="<?= PaginaSEI::tratarHTML($objListaStatusDTO->getStrDescricao()); ?>"
                        tabindex="<?= PaginaSEI::getInstance()->getProxTabDados() ?>"/>
            </div>
        </div>

        <div class="row">
            <div class="<?= $col_def ?>">
                <label id="lblSituacaoRastreio" for="selSituacaoRastreio" class="infraLabelObrigatorio">
                    Situação no Rastreio do Módulo:</label></br>
                <select id="selSituacaoRastreio" name="selSituacaoRastreio" class="infraSelect form-control"
                        tabindex="<?= PaginaSEI::getInstance()->getProxTabDados() ?>">
                    <?= $strItensSelRastreioModulo ?>
                </select>
            </div>
        </div>
        <input type="hidden" id="hdnIdMdCorListaStatus" name="hdnIdMdCorListaStatus"
                value="<?= $strIdMdCorListaStatus; ?>"/>
       
    <?
    PaginaSEI::getInstance()->fecharAreaDados();
    ?>
</form>

<?
require_once('md_cor_parametrizacao_status_js.php');
PaginaSEI::getInstance()->fecharBody();
PaginaSEI::getInstance()->fecharHtml();
?>
