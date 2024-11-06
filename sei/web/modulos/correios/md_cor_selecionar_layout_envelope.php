<?php

try {
    require_once dirname(__FILE__) . '/../../SEI.php';

    session_start();

    //////////////////////////////////////////////////////////////////////////////
    InfraDebug::getInstance()->setBolLigado(false);
    InfraDebug::getInstance()->setBolDebugInfra(true);
    InfraDebug::getInstance()->limpar();
    //////////////////////////////////////////////////////////////////////////////

    SessaoSEI::getInstance()->validarLink();

    PaginaSEI::getInstance()->setTipoPagina(InfraPagina::$TIPO_PAGINA_SIMPLES);

    $strTitulo = 'Selecionar Layout para Impressão';

}catch(Exception $e){
    PaginaSEI::getInstance()->processarExcecao($e);
}

PaginaSEI::getInstance()->montarDocType();
PaginaSEI::getInstance()->abrirHtml();
PaginaSEI::getInstance()->abrirHead();
PaginaSEI::getInstance()->montarMeta();
PaginaSEI::getInstance()->montarTitle(PaginaSEI::getInstance()->getStrNomeSistema().' - '.$strTitulo);
PaginaSEI::getInstance()->montarStyle();
PaginaSEI::getInstance()->abrirStyle();
PaginaSEI::getInstance()->fecharStyle();
PaginaSEI::getInstance()->montarJavaScript();
PaginaSEI::getInstance()->abrirJavaScript();
PaginaSEI::getInstance()->fecharJavaScript();
PaginaSEI::getInstance()->fecharHead();
PaginaSEI::getInstance()->abrirBody($strTitulo,'');
?>

<div class="row">
    <div class="col-sm-6 col-md-4">
        <label class="infraLabelOpcional">Tipo de Layout</label>
        <select class="infraSelect form-control" id="selTpLayout" name="selTpLayout" tabindex="<?= PaginaSEI::getInstance()->getProxTabTabela() ?>" onchange="fecharModal(this)">
            <option value="PADRAO" selected> PADRAO </option>
            <option value="LINEAR_100_150"> LINEAR_100_150 </option>
            <option value="LINEAR_100_80"> LINEAR_100_80 </option>
            <option value="LINEAR_A4"> LINEAR_A4 </option>
            <option value="LINEAR_A"> LINEAR_A </option>
        </select>
        <br>
        <button class="infraButton" onclick="fecharModal()">Imprimir</button>
    </div>
</div>

<script type="text/javascript">
    function fecharModal(elem){
        let ret = null;
        if ( typeof elem !== 'undefined' ) {
            ret = elem.value;
        } else {
            ret = document.querySelector('#selTpLayout').value;
        }
        window.top.document.querySelector('#hdnSelTipoLayout').value = ret;
        window.top.window.imprimirRotuloEnvelope();
        $(window.top.document).find('div[id^=divInfraSparklingModalClose]').click();
    }
</script>

<?php
PaginaSEI::getInstance()->fecharBody();
PaginaSEI::getInstance()->fecharHtml();
?>
