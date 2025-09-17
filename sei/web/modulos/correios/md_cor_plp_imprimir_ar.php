<?php
/**
 * ANATEL
 *
 * 23/10/2017 - criado por Ellyson de Jesus Silva
 *
 * 07/05/2024 - alterdo por Gustavo Camelo
 *
 */
require_once dirname(__FILE__) . '/../../SEI.php';

session_start();

SessaoSEI::getInstance()->validarLink();

SessaoSEI::getInstance()->validarPermissao($_GET['acao']);

switch ($_GET['acao']) {
    case 'md_cor_plp_imprimir_ar':
        $strTitulo    = 'Impressão de AR';
        $validado     = true;
        $msgErro      = '';
        $msgFinalErro = 'Erro na impressão do Aviso de Recebimento: <br>';
        break;
    default:
        throw new InfraException("Ação '" . $_GET['acao'] . "' não reconhecida.");
}

//recuperando a expedição solicitada
$objExpSolicDTO = new MdCorExpedicaoSolicitadaDTO();


$objExpSolicDTO->retNumIdMdCorExpedicaoSolicitada();
$objExpSolicDTO->retStrCodigoRastreamento();
$objExpSolicDTO->retDthDataExpedicao();
$objExpSolicDTO->retStrSinObjetoAcessado();
$objExpSolicDTO->retNumIdMdCorPlp();
$objExpSolicDTO->retNumIdMdCorObjeto();
$objExpSolicDTO->retStrIdPrePostagem();

$objExpSolicDTO->retDblIdMdCorContrato();
$objExpSolicDTO->setNumIdMdCorPlp($_GET['id_md_cor_plp']);
$objExpSolicDTO->setStrSinNecessitaAr('S');
//$objExpSolicDTO->setDistinct(true);
$objExpSolicDTO->setOrd('IdMdCorExpedicaoSolicitada', InfraDTO::$TIPO_ORDENACAO_ASC);

if (isset($_POST['hdnInfraItensSelecionados']) && $_POST['hdnInfraItensSelecionados'] != '') {
    $objExpSolicDTO->setNumIdMdCorExpedicaoSolicitada(explode(',', $_POST['hdnInfraItensSelecionados']), InfraDTO::$OPER_IN);
}

$objMdCorExpedicaoSolicitadaRN = new MdCorExpedicaoSolicitadaRN();
$arrObjMdCorExpedicaoSolicitadaDTO = $objMdCorExpedicaoSolicitadaRN->listar($objExpSolicDTO);

if ( count($arrObjMdCorExpedicaoSolicitadaDTO) == 0 ) {
    //throw new InfraException('Nenhuma solicitação de expedição possui AR!');
    $msgErro .= "Nenhuma solicitação de expedição possui AR.<br>";
}

$objMdCorAdmIntegracaoRN = new MdCorAdmIntegracaoRN();
$pular_aviso_recebimento = false;
foreach ($arrObjMdCorExpedicaoSolicitadaDTO as $objMdCorExpedicaoSolicitadaDTO) {

    $objMdCorIntegAR = $objMdCorAdmIntegracaoRN->buscaIntegracaoPorFuncionalidade(MdCorAdmIntegracaoRN::$AVISO_RECEB, $objMdCorExpedicaoSolicitadaDTO->getDblIdMdCorContrato());

    if ( is_null( $objMdCorIntegAR ) )
        $msgErro .= "Mapeamento de Integração ". MdCorAdmIntegracaoRN::$STR_AVISO_RECEB ." não existe ou está inativo.<br>";

    $arrParametroRest = [
        'endpoint' => $objMdCorIntegAR->getStrUrlOperacao(),
        'token'    => $objMdCorIntegAR->getStrToken(),
        'expiraEm' => $objMdCorIntegAR->getDthDataExpiraToken(),
    ];

    $ret = $objMdCorAdmIntegracaoRN->verificaTokenExpirado($arrParametroRest, $objMdCorIntegAR, $objMdCorExpedicaoSolicitadaDTO->getDblIdMdCorContrato());

    if ( is_array( $ret ) )	$msgErro .= "Falha na Integração: ". MdCorAdmIntegracaoRN::$STR_AVISO_RECEB . ".<br>";

    $statusCanceladoPlp = (new MdCorAdmIntegracaoRN())->validaStatusCanceladoPlp($objMdCorExpedicaoSolicitadaDTO->getStrIdPrePostagem(), $arrParametroRest);
    // Valida se o status retornou cancelado
    if ($statusCanceladoPlp) {
        $objMdCorAdmIntegracaoRN = new MdCorAdmIntegracaoRN();

        $objMdCorIntegCancelarPPN = $objMdCorAdmIntegracaoRN->buscaIntegracaoPorFuncionalidade(MdCorAdmIntegracaoRN::$CANCELAR_PRE_POSTAGEM, $objMdCorExpedicaoSolicitadaDTO->getDblIdMdCorContrato());
        if ( empty($objMdCorIntegCancelarPPN) || ( is_array( $objMdCorIntegCancelarPPN ) && isset($objMdCorIntegCancelarPPN['suc'] ) && $objMdCorIntegCancelarPPN['suc'] === false ) )
            $msgErro .= 'Mapeamento de Integração '. MdCorAdmIntegracaoRN::$STR_CANCELAR_PRE_POSTAGEM .' não existe ou está inativo.';
            
        $arrParametroRest = [
            'endpoint' => $objMdCorIntegCancelarPPN->getStrUrlOperacao(),
            'token'    => $objMdCorIntegCancelarPPN->getStrToken(),
            'expiraEm' => $objMdCorIntegCancelarPPN->getDthDataExpiraToken(),
        ];

        $ret = $objMdCorAdmIntegracaoRN->verificaTokenExpirado($arrParametroRest, $objMdCorIntegCancelarPPN, $objMdCorExpedicaoSolicitadaDTO->getDblIdMdCorContrato());

        if ( is_array( $ret ) ) 
            $msgErro .= $ret['msg'] ;

        // instancia class ApiRest com os dados necessarios para uso da API que gera Pre Postagem
        $objMdCorApiCancelarPPN = new MdCorApiRestRN( $arrParametroRest );

        $rs = $objMdCorApiCancelarPPN->cancelarPPN([$objMdCorExpedicaoSolicitadaDTO->getStrCodigoRastreamento()]);

        //if ( is_array($rs) ){
        //    $msgErroCompl = "Operação: {$objMdCorApiCancelarPPN->getEndPoint()}.#Retorno: {$rs['msg']}";
        //    $msgErro .=  $msgErroCompl ;
        //}

        $objMdCorExpedicaoSolicitadaDTO->setStrCodigoRastreamento(null);
        $objMdCorExpedicaoSolicitadaDTO->setDthDataExpedicao(null);
        $objMdCorExpedicaoSolicitadaDTO->setStrSinObjetoAcessado(null);
        $objMdCorExpedicaoSolicitadaDTO->setNumIdMdCorPlp(null);
        $objMdCorExpedicaoSolicitadaDTO->setNumIdMdCorObjeto(null);
        $objMdCorExpedicaoSolicitadaDTO->setStrIdPrePostagem(null);
        ( new MdCorExpedicaoSolicitadaRN() )->alterar($objMdCorExpedicaoSolicitadaDTO);
        
        $msgErro .= "Os Correios impediu seguir com a Impressão dos Documentos pois identificou que a situação da Pré Postagem do objeto cujo o código de rastreio é " . $objMdCorExpedicaoSolicitadaDTO->getStrCodigoRastreamento() . " é Cancelado.<br>
        Com isso, a Pré Postagem do Objeto foi Cancelada no SEI Módulo Correios, sendo necessário iniciar novamente o fluxo de Expedição utilizando a funcionalidade Gerar Pré Postagem." ;
        $pular_aviso_recebimento = true;
    }
}

if (!$pular_aviso_recebimento) {
    // instancia class ApiRest com os dados necessarios para uso da API que gera Aviso Recebimento
    $objMdCorApiAR = new MdCorApiRestRN( $arrParametroRest );

    $strHtmlAR = '';

    //recupera os ids da Pre Postagem separa por virgula para buscar os AR via API REST dos Correios
    $strIdsPPN = implode( ',' , InfraArray::converterArrInfraDTO($arrObjMdCorExpedicaoSolicitadaDTO,'IdPrePostagem') );

    $ret = $objMdCorApiAR->avisoRecebimento( $strIdsPPN );

    if ( is_array( $ret ) && isset( $ret['suc'] ) && $ret['suc'] === false )
        $msgErro .= $ret['msg']. "<br>";
    else
        $strHtmlAR .= $ret;
}

if ( !empty($msgErro) ) {
    $validado = false;
    $msgFinalErro .= $msgErro;
}

if ( $validado ) {
    echo $strHtmlAR;
    echo "<script> window.print(); </script>";
} else {
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
    PaginaSEI::getInstance()->abrirBody($strTitulo, '');
    PaginaSEI::getInstance()->abrirAreaDados('100%', 'style="overflow-y: hidden;"');
    ?>

    <div class="alert alert-danger alert-dismissible fade show" style="font-size:.875rem; top:0.25rem; margin-bottom: 14px !important; width:98%; margin:0 auto;" role="alert">
        <?= $msgFinalErro ?>
        <button type="button" class="close media h-100" data-dismiss="alert" aria-label="Fechar Mensagem" aria-labelledby="divInfraMsg0" onclick="fecharAba()">
            <span aria-hidden="true" class="align-self-center"><b>X</b></span>
        </button>
    </div>

    <script>
        function fecharAba() {
            let tab = window.open("","_self");
            tab.close();
        }
    </script>

    <?php

}

PaginaSEI::getInstance()->fecharAreaDados();
PaginaSEI::getInstance()->fecharBody();
PaginaSEI::getInstance()->fecharHtml();
?>