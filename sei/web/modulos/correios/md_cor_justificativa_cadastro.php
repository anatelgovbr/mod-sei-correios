<?

try {
    require_once dirname(__FILE__) . '/../../SEI.php';

    session_start();
    SessaoSEI::getInstance()->validarLink();
    SessaoSEI::getInstance()->validarPermissao($_GET['acao']);

    $objJustDTO = new MdCorJustificativaDTO();
    $arrComandos = array();

    switch ($_GET['acao']) {
        case 'md_cor_justificativa_cadastrar':
            $strTitulo = 'Incluir Justificativa de Destinatários não Habilitados para Expedição';
            $arrComandos[] = '<button type="submit" accesskey="S" name="sbmCadastrarJustificativa" value="Salvar" class="infraButton"><span class="infraTeclaAtalho">S</span>alvar</button>';
            $arrComandos[] = '<button type="button" accesskey="C" name="btnCancelar" id="btnCancelar" value="Cancelar" onclick="location.href=\'' . SessaoSEI::getInstance()->assinarLink('controlador.php?acao=' . PaginaSEI::getInstance()->getAcaoRetorno() . '&acao_origem=' . $_GET['acao']) . '\';" class="infraButton"><span class="infraTeclaAtalho">C</span>ancelar</button>';

            $objJustDTO->setNumIdMdCorJustificativa(null);
            $objJustDTO->setStrNome($_POST['txtJustificativa']);
            $objJustDTO->setStrSinAtivo('S');

            if (isset($_POST['sbmCadastrarJustificativa'])) {
                try {
                    $objJustRN = new MdCorJustificativaRN();
                    $objJustDTO = $objJustRN->cadastrar($objJustDTO);

                    PaginaSEI::getInstance()->adicionarMensagem('Justificativa de Destinatários não Habilitados para Expedição cadastrado com sucesso.');
                    header('Location: ' . SessaoSEI::getInstance()->assinarLink('controlador.php?acao=' . PaginaSEI::getInstance()->getAcaoRetorno() . '&acao_origem=' . $_GET['acao'] . '&id_md_cor_justificativa=' . $objJustDTO->getNumIdMdCorJustificativa() . PaginaSEI::getInstance()->montarAncora($objJustDTO->getNumIdMdCorJustificativa())));
                    die;
                } catch (Exception $e) {
                    PaginaSEI::getInstance()->processarExcecao($e);
                }
            }
            break;

        case 'md_cor_justificativa_alterar':
            $strTitulo = 'Alterar Justificativa de Destinatário não Habilitado para Expedição';
            $arrComandos[] = '<button type="submit" accesskey="S" name="sbmAlterarJustificativa" value="Salvar" class="infraButton"><span class="infraTeclaAtalho">S</span>alvar</button>';

            $strIdMdCorJust = $_GET['idJustificativa'];

            if (isset($strIdMdCorJust)) {

                $objJustDTO->setNumIdMdCorJustificativa($strIdMdCorJust);
                $objJustDTO->retTodos(true);

                $objJustRN = new MdCorJustificativaRN();
                $objJustDTO = $objJustRN->consultar($objJustDTO);

                if ($objJustDTO == null) {
                    throw new InfraException("Registro não encontrado.");
                }

            } else {
                $objJustDTO->setNumIdMdCorJustificativa($_POST['hdnIdMdCorJust']);
                $objJustDTO->setStrNome($_POST['txtJustificativa']);
                $strIdMdCorJust = $_POST['hdnIdMdCorJust'];
            }

            $arrComandos[] = '<button type="button" accesskey="C" name="btnCancelar" id="btnCancelar" value="Cancelar" onclick="location.href=\'' . SessaoSEI::getInstance()->assinarLink('controlador.php?acao=' . PaginaSEI::getInstance()->getAcaoRetorno() . '&acao_origem=' . $_GET['acao']) . '#ID-' . $objJustDTO->getNumIdMdCorJustificativa() . '\';" class="infraButton"><span class="infraTeclaAtalho">C</span>ancelar</button>';

            if (isset($_POST['sbmAlterarJustificativa'])) {
                try {
                    $objJustRN = new MdCorJustificativaRN();
                    $objJustRN->alterar($objJustDTO);

                    PaginaSEI::getInstance()->adicionarMensagem('Justificativa de Destinatários não Habilitados para Expedição alterado com sucesso.');
                    header('Location: ' . SessaoSEI::getInstance()->assinarLink('controlador.php?acao=' . PaginaSEI::getInstance()->getAcaoRetorno() . '&acao_origem=' . $_GET['acao'] . '&id_justificativa=' . $objJustDTO->getNumIdMdCorJustificativa() . PaginaSEI::getInstance()->montarAncora($objJustDTO->getNumIdMdCorJustificativa())));
                    die;
                } catch (Exception $e) {
                    PaginaSEI::getInstance()->processarExcecao($e);
                }
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

require_once 'md_cor_justificativa_css.php';
PaginaSEI::getInstance()->fecharHead();
PaginaSEI::getInstance()->abrirBody($strTitulo, 'onload=""');
?>

<form id="frmJustificativa" method="post" onsubmit="return OnSubmitForm();"
      action="<?= SessaoSEI::getInstance()->assinarLink('controlador.php?acao=' . $_GET['acao'] . '&acao_origem=' . $_GET['acao']) ?>">

    <?
    PaginaSEI::getInstance()->montarBarraComandosSuperior($arrComandos);
    ?>
    <div class="row ">
        <div class="col-12 col-sm-12 col-md-10 col-lg-8 col-xl-6">
            <div class="form-group">
                <label id="lblJustificativa" for="txtJustificativa" accesskey="N" class="infraLabelObrigatorio"><span class="infraTeclaAtalho">N</span>ome:</label>
                <input type="text" id="txtJustificativa" name="txtJustificativa" class="infraText form-control" value="<?= PaginaSEI::tratarHTML($objJustDTO->getStrNome()); ?>"/>
                <input type="hidden" id="hdnIdMdCorJust" name="hdnIdMdCorJust" value="<?= $strIdMdCorJust; ?>"/>
            </div>
        </div>
    </div>
</form>

<?
PaginaSEI::getInstance()->fecharBody();
PaginaSEI::getInstance()->fecharHtml();
?>
