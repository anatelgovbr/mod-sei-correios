<?php
/**
 * ANATEL
 *
 * 23/10/2017 - criado por Ellyson de Jesus Silva
 * 18/04/2024 - alterado por Gustavo Camelo
 *
 */
try {
    require_once dirname(__FILE__) . '/../../SEI.php';

    session_start();

    SessaoSEI::getInstance()->validarLink();

    SessaoSEI::getInstance()->validarPermissao($_GET['acao']);

    switch ($_GET['acao']) {
        case 'md_cor_plp_imprimir_rotulo_envelope':
            $strTitulo = 'Impress�o de Envelope';
            break;

        default:
            throw new InfraException("A��o '" . $_GET['acao'] . "' n�o reconhecida.");
    }

    $msgErroCompl = '';

    //recuperando a expedi��o solicitada
    $objExpSolicDTO = new MdCorExpedicaoSolicitadaDTO();

    $objExpSolicDTO->retNumIdMdCorExpedicaoSolicitada();
    $objExpSolicDTO->retStrTipoRotuloImpressaoObjeto();
    $objExpSolicDTO->retStrIdPrePostagem();
    $objExpSolicDTO->retStrCodigoRastreamento();
    $objExpSolicDTO->retNumIdMdCorServicoPostal();
    $objExpSolicDTO->setNumIdMdCorPlp($_GET['id_md_cor_plp']);
    //$objExpSolicDTO->setDistinct(true);
    $objExpSolicDTO->setOrd('IdMdCorExpedicaoSolicitada', InfraDTO::$TIPO_ORDENACAO_ASC);

    if (isset($_POST['hdnInfraItensSelecionados']) && $_POST['hdnInfraItensSelecionados'] != '') {
        $objExpSolicDTO->setNumIdMdCorExpedicaoSolicitada(explode(',', $_POST['hdnInfraItensSelecionados']), InfraDTO::$OPER_IN);
    }

    $objMdCorExpedicaoSolicitadaRN = new MdCorExpedicaoSolicitadaRN();
    $arrObjMdCorExpedicaoSolicitadaDTO = $objMdCorExpedicaoSolicitadaRN->listar($objExpSolicDTO);

    if ( count($arrObjMdCorExpedicaoSolicitadaDTO) == 0 )
        throw new InfraException('N�o possui Expedi��o Solicitada.');

    // retorna dados da integra��o Emitir Rotulo
    $objMdCorAdmIntegracaoRN = new MdCorAdmIntegracaoRN();

    $objMdCorIntegEmitirRotulo = $objMdCorAdmIntegracaoRN->buscaIntegracaoPorFuncionalidade(MdCorAdmIntegracaoRN::$EMITIR_ROTULO);
    if ( is_null( $objMdCorIntegEmitirRotulo ) )
        throw new InfraException('Mapeamento de Integra��o '. MdCorAdmIntegracaoRN::$STR_EMITIR_ROTULO .' n�o existe ou est� inativo.');

    $arrParametroRest = [
        'endpoint' => $objMdCorIntegEmitirRotulo->getStrUrlOperacao(),
        'token'    => $objMdCorIntegEmitirRotulo->getStrToken(),
        'expiraEm' => $objMdCorIntegEmitirRotulo->getDthDataExpiraToken(),
    ];

    $ret = $objMdCorAdmIntegracaoRN->verificaTokenExpirado($arrParametroRest, $objMdCorIntegEmitirRotulo);

    if ( is_array( $ret ) )
        throw new InfraException('Falha na Integra��o: '. MdCorAdmIntegracaoRN::$STR_GERAR_TOKEN .'.');

    // instancia class ApiRest com os parametros necessarios para uso da API que emiti rotulo
    $objMdCorApiEmitirRotulo = new MdCorApiRestRN($arrParametroRest);

    // retorna info da integracao Download Rotulo
    $objMdCorIntegDownRotulo = $objMdCorAdmIntegracaoRN->buscaIntegracaoPorFuncionalidade(MdCorAdmIntegracaoRN::$DOWN_ROTULO);

    $arrParametroRest['endpoint'] = $objMdCorIntegDownRotulo->getStrUrlOperacao();

    // instancia class ApiRest com os parametros necessarios para uso da API que faz o download do rotulo
    $objMdCorApiDownRotulo = new MdCorApiRestRN($arrParametroRest);

    // gera um array com os ids das PPN
    $arrIdsPPN = InfraArray::converterArrInfraDTO($arrObjMdCorExpedicaoSolicitadaDTO,'IdPrePostagem');

    //busca o tipo de servico postal cadastrado
    $idServicoPostal = $arrObjMdCorExpedicaoSolicitadaDTO[0]->getNumIdMdCorServicoPostal();
    $objMdCorServicoPostalDTO = new MdCorServicoPostalDTO();
    $objMdCorServicoPostalDTO->retStrCodigoWsCorreios();
    $objMdCorServicoPostalDTO->retStrNome();
    $objMdCorServicoPostalDTO->setNumIdMdCorServicoPostal($idServicoPostal);

    $objMdCorServicoPostalDTO = ( new MdCorServicoPostalRN() )->consultar($objMdCorServicoPostalDTO);

    $strLayoutImpressao = $_POST['hdnSelTipoLayout'];

    /*
     * Formulario onde salva o Tipo de Embalagem, os dados est�o assim: "C = completo e R = resumido"
     * Tipos de Rotuto: [P - Padrao , R - Reduzido]
     * Formato Rotulo:  [ET - Etiqueta , EV - Envelope]
     */
    $tpRotulo = $arrObjMdCorExpedicaoSolicitadaDTO[0]->getStrTipoRotuloImpressaoObjeto() == MdCorObjetoRN::$ROTULO_COMPLETO ? 'P' : 'R';

    $arrParams = [
        'idsPrePostagem' => $arrIdsPPN,
        'tipoRotulo'     => $tpRotulo,
        'formatoRotulo'  => 'ET', // No ambiente de HM dos Correios n�o aceita o valor EV
        'layoutImpressao' => $strLayoutImpressao
    ];

    // Gera Id Recibo necess�rio para imprimir a Etiqueta
    $arrEmissaoRot = $objMdCorApiEmitirRotulo->emissaoRotulo( $arrParams );

    if ( is_array($arrEmissaoRot) && key_exists('suc',$arrEmissaoRot) && $arrEmissaoRot['suc'] === false ) {
        $msgErroCompl = "Opera��o: {$objMdCorApiEmitirRotulo->getEndPoint()} <br><br>";
        throw new InfraException( $arrEmissaoRot['msg'] );
    }

    // Executa download da Etiqueta passando como parametro o Id Recibo
    $rotuloBase64 = $objMdCorApiDownRotulo->downloadRotulo( $arrEmissaoRot['idRecibo'] );

    if ( is_array($rotuloBase64) && key_exists('suc',$rotuloBase64) && $rotuloBase64['suc'] === false ) {
        $msgErroCompl = "Opera��o: {$objMdCorApiDownRotulo->getEndPoint()} <br><br>";
        throw new InfraException( $rotuloBase64['msg'] );
    }

    header("Content-Type: application/pdf");
    header("Cache-Control: no-store");
    //header("Content-Disposition: attachment;filename=ImpressaoEnvelope.pdf");
    header("Content-Disposition: inline;");

    echo file_get_contents('data://application/pdf;base64,'. $rotuloBase64);

} catch (InfraException $e) {

    $msgFinalErro = "N�o foi poss�vel integrar com os Correios para imprimir a(s) etiqueta(s) de envelope: <br><br>
                    $msgErroCompl" .
        "Retorno da API: " . $e->getMessage();

    PaginaSEI::getInstance()->montarDocType();
    PaginaSEI::getInstance()->abrirHtml();
    PaginaSEI::getInstance()->abrirHead();
    PaginaSEI::getInstance()->montarMeta();
    PaginaSEI::getInstance()->montarTitle(PaginaSEI::getInstance()->getStrNomeSistema() . ' - ' . $strTitulo );
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
    die;
}

?>

