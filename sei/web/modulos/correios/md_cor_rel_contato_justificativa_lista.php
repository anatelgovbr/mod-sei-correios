<?

try {
    require_once dirname(__FILE__) . '/../../SEI.php';

    session_start();
    SessaoSEI::getInstance()->validarLink();
    SessaoSEI::getInstance()->validarPermissao($_GET['acao']);
    InfraDebug::getInstance()->limpar();

    PaginaSEI::getInstance()->salvarCamposPost(array('txtDestinatario', 'selNatureza', 'txtCpfCnpj', 'selJustificativa'));

    switch ($_GET['acao']) {

        case 'md_cor_rel_contato_justificativa_listar':
            $strTitulo = 'Destinatários não Habilitados para Expedição';

            $arrComandos = array();
            $arrComandos[] = '<button type="submit" accesskey="p" id="btnPesquisar" value="Pesquisar" class="infraButton"><span class="infraTeclaAtalho">P</span>esquisar</button>';
            $arrComandos[] = '<button type="button" accesskey="J" id="btnNovo" value="Justificativas" onclick="location.href=\'' . SessaoSEI::getInstance()->assinarLink('controlador.php?acao=md_cor_justificativa_listar&acao_origem=' . $_GET['acao'] . '&acao_retorno=' . $_GET['acao']) . '\'" class="infraButton"><span class="infraTeclaAtalho">J</span>ustificativas</button>';
            $arrComandos[] = '<button type="button" accesskey="N" id="btnNovo" value="Novo" onclick="location.href=\'' . SessaoSEI::getInstance()->assinarLink('controlador.php?acao=md_cor_rel_contato_justificativa_cadastrar&acao_origem=' . $_GET['acao'] . '&acao_retorno=' . $_GET['acao']) . '\'" class="infraButton"><span class="infraTeclaAtalho">N</span>ovo</button>';
            $arrComandos[] = '<button type="button" accesskey="C" id="btnFechar" value="Fechar" onclick="location.href=\'' . SessaoSEI::getInstance()->assinarLink('controlador.php?acao=procedimento_controlar&acao_origem=' . $_GET['acao']) . '\'" class="infraButton">Fe<span class="infraTeclaAtalho">c</span>har</button>';

            break;

        case 'md_cor_rel_contato_justificativa_excluir':
            try {
                $id = $_GET['idRelContJustificativa'];
                $objMdCorContJustDTO = new MdCorRelContatoJustificativaDTO();
                $objMdCorContJustDTO->setNumIdRelContatoJustificativa($id);

                $objMdCorContJustRN = new MdCorRelContatoJustificativaRN();
                $objMdCorContJustRN->excluir($objMdCorContJustDTO);
                PaginaSEI::getInstance()->adicionarMensagem('Operação realizada com sucesso.');

            } catch (Exception $e) {
                PaginaSEI::getInstance()->processarExcecao($e);
            }
            header('Location: ' . SessaoSEI::getInstance()->assinarLink('controlador.php?acao=' . $_GET['acao_origem'] . '&acao_origem=' . $_GET['acao']));
            die;

        default:
            throw new InfraException("Ação '" . $_GET['acao'] . "' não reconhecida.");
    }

    require_once('md_cor_rel_contato_justificativa_tabela.php');

    $strItensSelJustificativa = MdCorJustificativaINT::montarSelectJustificativa('null', '&nbsp;', $selJustificativa, false);

} catch (Exception $e) {
    PaginaSEI::getInstance()->processarExcecao($e);
}

PaginaSEI::getInstance()->prepararPaginacao($objMdCorRelContJustDTO, 200);

PaginaSEI::getInstance()->montarDocType();
PaginaSEI::getInstance()->abrirHtml();
PaginaSEI::getInstance()->abrirHead();
PaginaSEI::getInstance()->montarMeta();
PaginaSEI::getInstance()->montarTitle(PaginaSEI::getInstance()->getStrNomeSistema() . ' - ' . $strTitulo);
PaginaSEI::getInstance()->montarStyle();
PaginaSEI::getInstance()->abrirStyle();
PaginaSEI::getInstance()->fecharStyle();
require_once 'md_cor_rel_contato_justificativa_css.php';

PaginaSEI::getInstance()->montarJavaScript();
PaginaSEI::getInstance()->abrirJavaScript();
PaginaSEI::getInstance()->fecharJavaScript();

PaginaSEI::getInstance()->fecharHead();
PaginaSEI::getInstance()->abrirBody($strTitulo, 'onload="inicializar();"');

?>

    <form id="frmMdCorContatoJustificativa" method="post" action="<?= SessaoSEI::getInstance()->assinarLink('controlador.php?acao=' . $_GET['acao'] . '&acao_origem=' . $_GET['acao']) ?>">

        <? PaginaSEI::getInstance()->montarBarraComandosSuperior($arrComandos); ?>
        <div class="row">
            <div class="col-12 col-sm-8 col-md-8 col-lg-6 col-xl-6">
                <div class="form-group">
                    <label id="lblDestinatario" for="txtDestinatario" accesskey="o" class="infraLabelOpcional">Destinatário:</label>
                    <input type="text" id="txtDestinatario" name="txtDestinatario" class="infraText form-control" value="<?= PaginaSEI::tratarHTML($txtDestinatario) ?>"/>
                </div>
            </div>
            <div class="col-12 col-sm-4 col-md-4 col-lg-3 col-xl-3">
                <div class="form-group">
                    <label id="lblNatureza" for="selNatureza" accesskey="" class="infraLabelOpicional">Natureza:</label>
                    <select onchange="this.form.submit()" id="selNatureza" name="selNatureza" class="infraSelect form-control" tabindex="<?= PaginaSEI::getInstance()->getProxTabDados() ?>">
                        <option value=""></option>
                        <option value="J" <?= $selNatureza == 'J' ? 'selected' : '' ?> >Pessoa Jurídica</option>
                        <option value="F" <?= $selNatureza == 'F' ? 'selected' : '' ?> > Pessoa Física</option>
                    </select>
                </div>
            </div>
            <div class="col-12 col-sm-4 col-md-4 col-lg-3 col-xl-3">
                <div class="form-group">
                    <label id="lblCpfCnpj" for="txtCpfCnpj" accesskey="o" class="infraLabelOpcional">CPF/CNPJ:</label>
                    <input type="text" id="txtCpfCnpj" name="txtCpfCnpj" class="infraText form-control" value="<?= PaginaSEI::tratarHTML($txtCpfCnpj) ?>"/>
                </div>
            </div>
            <div class="col-12 col-sm-8 col-md-8 col-lg-6 col-xl-6">
                <div class="form-group">
                    <label id="lblJustificativa" for="selJustificativa" accesskey="" class="infraLabelOpicional">Justifivativa:</label>
                    <select onchange="this.form.submit()" id="selJustificativa" name="selJustificativa" class="infraSelect form-control" tabindex="<?= PaginaSEI::getInstance()->getProxTabDados() ?>">
                        <?= $strItensSelJustificativa ?>
                    </select>
                </div>
            </div>
        </div>
        <?
        require_once 'md_cor_rel_contato_justificativa_js.php';

        PaginaSEI::getInstance()->montarAreaTabela($strResultado, $numRegistros);
        PaginaSEI::getInstance()->montarBarraComandosInferior($arrComandos);
        ?>

    </form>
<?
PaginaSEI::getInstance()->fecharBody();
PaginaSEI::getInstance()->fecharHtml();
?>