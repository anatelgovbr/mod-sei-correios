<?

try {
    require_once dirname(__FILE__) . '/../../SEI.php';

    session_start();
    SessaoSEI::getInstance()->validarLink();
    SessaoSEI::getInstance()->validarPermissao($_GET['acao']);

    switch ($_GET['acao']) {

        case 'md_cor_parametrizacao_status_desativar':
            try {

                $objMdCorListaStatusDTO = new MdCorListaStatusDTO();
                $objMdCorListaStatusDTO->setNumIdMdCorListaStatus($_POST['hdnInfraItemId']);
                $objMdCorListaStatusDTO->retStrSinAtivo();
                $arrObjMdCorListaStatusDTO[] = $objMdCorListaStatusDTO;

                $objMdCorListaStatusRN = new MdCorListaStatusRN();
                $objMdCorListaStatusRN->desativar($arrObjMdCorListaStatusDTO);
                PaginaSEI::getInstance()->adicionarMensagem('Operação realizada com sucesso.');
            } catch (Exception $e) {
                PaginaSEI::getInstance()->processarExcecao($e);
            }
            header('Location: ' . SessaoSEI::getInstance()->assinarLink('controlador.php?acao=' . $_GET['acao_origem'] . '&acao_origem=' . $_GET['acao']));
            die;

        case 'md_cor_parametrizacao_status_reativar':
            $strTitulo = 'Reativar Visualização do Status no Rastreamento do Objeto';
            if ($_GET['acao_confirmada'] == 'sim') {
                try {

                    $objMdCorListaStatusDTO = new MdCorListaStatusDTO();
                    $objMdCorListaStatusDTO->setNumIdMdCorListaStatus($_POST['hdnInfraItemId']);
                    $objMdCorListaStatusDTO->retStrSinAtivo();
                    $arrObjMdCorListaStatusDTO[] = $objMdCorListaStatusDTO;

                    $objMdCorListaStatusRN = new MdCorListaStatusRN();
                    $objMdCorListaStatusRN->reativar($arrObjMdCorListaStatusDTO);
                    PaginaSEI::getInstance()->adicionarMensagem('Operação realizada com sucesso.');
                } catch (Exception $e) {
                    PaginaSEI::getInstance()->processarExcecao($e);
                }
                header('Location: ' . SessaoSEI::getInstance()->assinarLink('controlador.php?acao=' . $_GET['acao_origem'] . '&acao_origem=' . $_GET['acao']));
                die;
            }
            break;

        case 'md_cor_parametrizacao_status_listar':
            $strTitulo = 'Tipos de Situações de Rastreamento de Objeto';

            $arrComandos = array();
            $arrComandos[] = '<button type="submit" accesskey="p" id="btnPesquisar" value="Pesquisar" onclick="pesquisar();" class="infraButton"><span class="infraTeclaAtalho">P</span>esquisar</button>';
            $arrComandos[] = '<button type="button" accesskey="C" id="btnFechar" value="Fechar" onclick="location.href=\'' . SessaoSEI::getInstance()->assinarLink('controlador.php?acao=procedimento_controlar&acao_origem=' . $_GET['acao']) . '\'" class="infraButton">Fe<span class="infraTeclaAtalho">c</span>har</button>';

            break;

        default:
            throw new InfraException("Ação '" . $_GET['acao'] . "' não reconhecida.");
    }

    require_once('md_cor_parametrizacao_status_tabela.php');


} catch (Exception $e) {
    PaginaSEI::getInstance()->processarExcecao($e);
}

PaginaSEI::getInstance()->prepararPaginacao($objMdCorListaStatusDTO, 200);

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

<form id="frmMdCorParametrizacaoLista" method="post" action="<?= SessaoSEI::getInstance()->assinarLink('controlador.php?acao=' . $_GET['acao'] . '&acao_origem=' . $_GET['acao']) ?>">

    <? PaginaSEI::getInstance()->montarBarraComandosSuperior($arrComandos); ?>
    <div class="row">
        <div class="col-6 col-sm-6 col-md-3 col-lg-2 col-xl-2">
            <div class="form-group">
                <label id="lblStatus" for="txtStatus" accesskey="o" class="infraLabelOpcional">Código:</label>
                <input type="text" id="txtStatus" name="txtStatus" class="infraText form-control" value="<?= PaginaSEI::tratarHTML($_POST['txtStatus']) ?>"/>
            </div>
        </div>
        <div class="col-6 col-sm-6 col-md-3 col-lg-2 col-xl-2">
            <div class="form-group">
                <label id="lblTipo" for="txtTipo" accesskey="o" class="infraLabelOpcional">Tipo:</label>
                <input type="text" id="txtTipo" name="txtTipo" class="infraText form-control" value="<?= PaginaSEI::tratarHTML($_POST['txtTipo']) ?>"/>
            </div>
        </div>
        <div class="col-12 col-sm-12 col-md-6 col-lg-5 col-xl-5">
            <div class="form-group">
                <label id="lblDescricao" for="txtDescricao" accesskey="o" class="infraLabelOpcional">Descrição:</label>
                <input type="text" id="txtDescricao" name="txtDescricao" class="infraText form-control" value="<?= PaginaSEI::tratarHTML($_POST['txtDescricao']) ?>"/>
            </div>
        </div>
        <div class="col-12 col-sm-12 col-md-6 col-lg-3 col-xl-3">
            <div class="form-group">
                <label id="lblSituacaoRastreio" for="txtSituacaoRastreio" accesskey="o" class="infraLabelOpcional">Situação no Módulo:</label>
                <select class="infraSelect form-control" name="txtSituacaoRastreio" id="txtSituacaoRastreio" tabindex="<?= PaginaSEI::getInstance()->getProxTabDados(); ?>">
                    <option value=""></option>
                    <option value="P">Postado</option>
                    <option value="T">Em Trânsito</option>
                    <option value="S">Sucesso na Entrega</option>
                    <option value="I">Insucesso na Entrega</option>
                </select>
            </div>
        </div>
    </div>
    <?
    PaginaSEI::getInstance()->montarAreaTabela($strResultado, $numRegistros);
    PaginaSEI::getInstance()->montarBarraComandosInferior($arrComandos);
    ?>

</form>
<?
require_once 'md_cor_parametrizacao_status_js.php';
PaginaSEI::getInstance()->fecharBody();
PaginaSEI::getInstance()->fecharHtml();

?>
