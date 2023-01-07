<?php
/**
 * Created by PhpStorm.
 * User: matheus.pereira
 * Date: 09/10/2017
 * Time: 10:28
 */

try {

    require_once dirname(__FILE__) . '/../../SEI.php';

    switch ($_GET['acao']) {

        //region Listar
        case 'md_cor_geracao_plp_consultar':
            break;
        //endregion

        #region Erro
        default:

            throw new InfraException("Ação '" . $_GET['acao'] . "' não reconhecida.");
        #endregion
    }

    $arrComandos[] = '<button type="button" accesskey="c" id="btnFechar" onclick="fechar()" class="infraButton">
                                    Fe<span class="infraTeclaAtalho">c</span>har
                              </button>';

    $strResultado = '';
    $strResultado .= '</br></br><table width="99%" class="infraTable">';
    $strResultado .= '<tr>';
    $strResultado .= '<th class="infraTh" width="25%">Documento</th>';
    $strResultado .= '<th class="infraTh" width="25%">Formato de Expedição</th>';
    $strResultado .= '<th class="infraTh" width="25%">Impresso</th>';
    $strResultado .= '<th class="infraTh" width="25%">Justificativa</th>';
    $strResultado .= '</tr>';

    $strPlpTr = '';

    for ($i = 0; $i < 3; $i++) {

        $strPlpTr .= '<tr class="infraTrClara">';
        $strPlpTr .= '<td></td>';
        $strPlpTr .= '<td>
                            <input type="radio">Impresso</input>
                            <input type="radio">Gravao em Midia</input>
                       </td>';
        $strPlpTr .= '<td>
                            <input type="radio">Preto e Branco</input>
                            <input type="radio">Colorido</input>
                       </td>';
        $strPlpTr .= '<td><input type="text" style="width: 250px"></td>';
        $strPlpTr .= '</tr>';

    }
    $strResultado .= $strPlpTr;
    $strResultado .= '</table>';

//    $strItensSelExpedicao = MdCorGeracaoPlpINT::montarSelectUnidade();
    $strTitulo = "Vizualizar Solicitao de Expedição pelos Correios";

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
?>
    #selExpCorreios {left: 0%;top: 58%;width: 83%;margin-top: 4px;border: .1em solid #666;}
    #imgLupaUnidade {position: absolute;left: 83.5%;top: 28%;}
    #imgExcluirUnidade {position: absolute;left: 83.4%;top: 41%;}
    .clear {clear: both;}
<?php
PaginaSEI::getInstance()->fecharStyle();
PaginaSEI::getInstance()->montarJavaScript();
PaginaSEI::getInstance()->abrirJavaScript();?>

<?php PaginaSEI::getInstance()->fecharJavaScript(); ?>

<?php
PaginaSEI::getInstance()->fecharHead();
PaginaSEI::getInstance()->abrirBody($strTitulo, 'onload="inicializar();"');
PaginaSEI::getInstance()->montarBarraComandosSuperior($arrComandos);
?>
    <form id="frmConsultarPlp" method="post">

        <h2>Unidade Expedidora: Protocolo.Sede</h2>

        <div style="margin-top: 3%">
            <label for="SrvPostal" Display="inline">Servio Postal:</label></br>
            <input type="text" class="col1">
        </div>

        <div>
            <input type="checkbox" style="margin-top: 1%"><span>Necessita de Aviso de Recebimento (AR)</span>
        </div>

        <fieldset style="margin-top: 1%">
            <legend>Documentos Expedidos</legend>
            <p>Documento Principal de Expedição: Oficio 3 (0018403)</p>
            <p>Destinatrio: Nome Completo</p>

            <input type="checkbox"><span>O Documento a ser expedido possui Anexos</span></br>

            <div style="margin-top: 1%">
                <label for="Anexo" display="inline">Protocolo de Anexo ao Documento Principal Expedição:</label>
            </div>
            <form id="frmExpedicaoCorreio" method="post" onsubmit="return OnSubmitForm();" action="<?= SessaoSEI::getInstance()->assinarLink('controlador.php?acao=' . $_GET['acao'] . '&acao_origem=' . $_GET['acao']) ?>">
                <?php PaginaSEI::getInstance()->abrirAreaDados('auto'); ?>
                <input type="hidden" id="hdnIdExpCorreio" name="hdnIdExpCorreio" class="infraText" value=""/>

                <div class="clear" style="margin-top: 1%;"></div>
                <select id="selExpCorreios" name="selExpCorreios" size="8" multiple="multiple" class="infraSelect" tabindex="<?= PaginaSEI::getInstance()->getProxTabDados() ?>"></select>

                <img id="imgLupaUnidade" onclick="objLupaUnidade.selecionar(700,500);" src="<?= PaginaSEI::getInstance()->getDiretorioImagensGlobal() ?>/lupa.gif" alt="Selecionar ExpCorreio" title="Selecionar ExpCorreio" class="infraImg" tabindex="<?= PaginaSEI::getInstance()->getProxTabDados() ?>"/>
                <img id="imgExcluirUnidade" onclick="objLupaUnidade.remover();" src="<?= PaginaSEI::getInstance()->getDiretorioImagensGlobal() ?>/remover.gif" alt="Remover ExpCorreio" title="Remover ExpCorreio" class="infraImg" tabindex="<?= PaginaSEI::getInstance()->getProxTabDados() ?>"/>

                <input type="hidden" id="hdnIdExpCorreios" name="hdnIdExpCorreios" class="infraText" value=""/>
                <?php PaginaSEI::getInstance()->fecharAreaDados(); ?>
            </form>
        </fieldset>

        <fieldset style="margin-top: 1%">
            <legend>Formato de Expedio dos Documentos</legend>
            <div>
                <?php PaginaSEI::getInstance()->montarAreaTabela($strResultado,3);?>
            </div>
        </fieldset>

    </form>
<?php
PaginaSEI::getInstance()->fecharBody();
PaginaSEI::getInstance()->fecharHtml();
