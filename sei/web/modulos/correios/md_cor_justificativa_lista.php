<?

try {
    require_once dirname(__FILE__) . '/../../SEI.php';

    session_start();
    SessaoSEI::getInstance()->validarLink();
    SessaoSEI::getInstance()->validarPermissao($_GET['acao']);

    PaginaSEI::getInstance()->salvarCamposPost(array('txtJustificativa'));

    switch ($_GET['acao']) {

        case 'md_cor_justificativa_listar':
            $strTitulo = 'Justificativas de Destinatários não Habilitados para Expedição';

            $arrComandos = array();
            $arrComandos[] = '<button type="submit" accesskey="p" id="btnPesquisar" value="Pesquisar" class="infraButton"><span class="infraTeclaAtalho">P</span>esquisar</button>';
            $arrComandos[] = '<button type="button" accesskey="N" id="btnNovo" value="Novo" onclick="location.href=\'' . SessaoSEI::getInstance()->assinarLink('controlador.php?acao=md_cor_justificativa_cadastrar&acao_origem=' . $_GET['acao'] . '&acao_retorno=' . $_GET['acao']) . '\'" class="infraButton"><span class="infraTeclaAtalho">N</span>ovo</button>';
            $arrComandos[] = '<button type="button" accesskey="C" id="btnFechar" value="Fechar" onclick="location.href=\'' . SessaoSEI::getInstance()->assinarLink('controlador.php?acao=' . PaginaSEI::getInstance()->getAcaoRetorno() . '&acao_origem=' . $_GET['acao']) . '\';" class="infraButton">Fe<span class="infraTeclaAtalho">c</span>har</button>';

            break;

        case 'md_cor_justificativa_desativar':
            try {

                $objMdCorJustDTO = new MdCorJustificativaDTO();
                $objMdCorJustDTO->setNumIdMdCorJustificativa($_POST['hdnInfraItemId']);

                $MdCorJustificativaRN = new MdCorJustificativaRN();
                $MdCorJustificativaRN->desativar($objMdCorJustDTO);
                PaginaSEI::getInstance()->adicionarMensagem('Operação realizada com sucesso.');
            } catch (Exception $e) {
                PaginaSEI::getInstance()->processarExcecao($e);
            }
            header('Location: ' . SessaoSEI::getInstance()->assinarLink('controlador.php?acao=' . $_GET['acao_origem'] . '&acao_origem=' . $_GET['acao']));
            die;

        case 'md_cor_justificativa_reativar':
            $strTitulo = 'Reativar Visualização de Justificativas de Destinatários não Habilitados para Expedição';
            if ($_GET['acao_confirmada'] == 'sim') {
                try {

                    $objMdCorJustDTO = new MdCorJustificativaDTO();
                    $objMdCorJustDTO->setNumIdMdCorJustificativa($_POST['hdnInfraItemId']);

                    $MdCorJustificativaRN = new MdCorJustificativaRN();
                    $MdCorJustificativaRN->reativar($objMdCorJustDTO);
                    PaginaSEI::getInstance()->adicionarMensagem('Operação realizada com sucesso.');
                } catch (Exception $e) {
                    PaginaSEI::getInstance()->processarExcecao($e);
                }
                header('Location: ' . SessaoSEI::getInstance()->assinarLink('controlador.php?acao=' . $_GET['acao_origem'] . '&acao_origem=' . $_GET['acao']));
                die;
            }
            break;

        case 'md_cor_justificativa_excluir':
            try {

                $id = $_GET['idJustificativa'];
                $objMdCorJustDTO = new MdCorJustificativaDTO();
                $objMdCorJustDTO->setNumIdMdCorJustificativa($id);

                $objMdCorJustRN = new MdCorJustificativaRN();
                $objMdCorJustRN->excluir($objMdCorJustDTO);
                PaginaSEI::getInstance()->adicionarMensagem('Operação realizada com sucesso.');

            } catch (Exception $e) {
                PaginaSEI::getInstance()->processarExcecao($e);
            }
            header('Location: ' . SessaoSEI::getInstance()->assinarLink('controlador.php?acao=' . $_GET['acao_origem'] . '&acao_origem=' . $_GET['acao']));
            die;

        default:
            throw new InfraException("Ação '" . $_GET['acao'] . "' não reconhecida.");
    }

    require_once('md_cor_justificativa_tabela.php');

} catch (Exception $e) {
    PaginaSEI::getInstance()->processarExcecao($e);
}

PaginaSEI::getInstance()->prepararPaginacao($objMdCorJustDTO, 200);

PaginaSEI::getInstance()->montarDocType();
PaginaSEI::getInstance()->abrirHtml();
PaginaSEI::getInstance()->abrirHead();
PaginaSEI::getInstance()->montarMeta();
PaginaSEI::getInstance()->montarTitle(PaginaSEI::getInstance()->getStrNomeSistema() . ' - ' . $strTitulo);

PaginaSEI::getInstance()->montarStyle();
require_once 'md_cor_justificativa_css.php';

PaginaSEI::getInstance()->montarJavaScript();
require_once 'md_cor_justificativa_js.php';

PaginaSEI::getInstance()->fecharHead();
PaginaSEI::getInstance()->abrirBody($strTitulo, 'onload="inicializar();"');

?>

<form id="frmMdCorJustificativa" method="post" action="<?= SessaoSEI::getInstance()->assinarLink('controlador.php?acao=' . $_GET['acao'] . '&acao_origem=' . $_GET['acao']) ?>">

    <? PaginaSEI::getInstance()->montarBarraComandosSuperior($arrComandos); ?>
    <div class="row">
        <div class="col-12 col-sm-12 col-md-10 col-lg-8 col-xl-6">
            <div class="form-group">
                <label id="lblJustificativa" for="txtJustificativa" accesskey="o" class="infraLabelOpcional">Justificativa:</label>
                <input type="text" id="txtJustificativa" name="txtJustificativa" class="infraText form-control"
                           value="<?= PaginaSEI::tratarHTML($txtJustificativa) ?>"/>
            </div>
        </div>
    </div>
    <?
    PaginaSEI::getInstance()->montarAreaTabela($strResultado, $numRegistros);
    PaginaSEI::getInstance()->montarBarraComandosInferior($arrComandos);
    ?>

</form>
<?
PaginaSEI::getInstance()->fecharBody();
PaginaSEI::getInstance()->fecharHtml();
?>
