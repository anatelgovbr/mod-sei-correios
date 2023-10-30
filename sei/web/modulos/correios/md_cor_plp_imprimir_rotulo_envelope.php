<?php
/**
 * ANATEL
 *
 * 23/10/2017 - criado por Ellyson de Jesus Silva
 *
 */
try {
    require_once dirname(__FILE__) . '/../../SEI.php';

    session_start();

    SessaoSEI::getInstance()->validarLink();

    SessaoSEI::getInstance()->validarPermissao($_GET['acao']);

    switch ($_GET['acao']) {
        case 'md_cor_plp_imprimir_rotulo_envelope':
            $strTitulo = '';
            break;

        default:
            throw new InfraException("Ação '" . $_GET['acao'] . "' não reconhecida.");
    }

    //colocando a pagina sem menu e titulo inicial
    PaginaSEI::getInstance()->setTipoPagina(InfraPagina::$TIPO_PAGINA_SIMPLES);

    //recuperando a expedição solicitada
    $objExpSolicDTO = new MdCorExpedicaoSolicitadaDTO();
    $objExpSolicDTO->retNumIdUnidade();
    $objExpSolicDTO->retNumIdContatoDestinatario();
    $objExpSolicDTO->retStrNomeSerie();
    $objExpSolicDTO->retStrNumeroDocumento();
    $objExpSolicDTO->retStrProtocoloFormatadoDocumento();
    $objExpSolicDTO->retDtaGeracaoProtocolo();
    $objExpSolicDTO->retStrSiglaOrgao();
    $objExpSolicDTO->retStrTipoRotuloImpressaoObjeto();
    $objExpSolicDTO->retDblMargemSuperiorImpressaoObjeto();
    $objExpSolicDTO->retDblMargemEsquerdaImpressaoObjeto();
    $objExpSolicDTO->retStrNumeroContratoCorreio();
    $objExpSolicDTO->retStrDescricaoServicoPostal();
    $objExpSolicDTO->retStrNomeImagemChancela();
    $objExpSolicDTO->retStrCodigoRastreamento();
    $objExpSolicDTO->retStrProtocoloFormatado();
    $objExpSolicDTO->retStrNomeDestinatario();
    $objExpSolicDTO->retStrEnderecoDestinatario();
    $objExpSolicDTO->retStrBairroDestinatario();
    $objExpSolicDTO->retStrCepDestinatario();
    $objExpSolicDTO->retStrSiglaUfDestinatario();
    $objExpSolicDTO->retStrNomeContratoOrgao();
    $objExpSolicDTO->retStrEnderecoContratoOrgao();
    $objExpSolicDTO->retStrBairroContratoOrgao();
    $objExpSolicDTO->retStrCepContratoOrgao();
    $objExpSolicDTO->retStrSiglaUfContratoOrgao();
    $objExpSolicDTO->retStrTimbreOrgao();
    $objExpSolicDTO->retStrComplementoDestinatario();
    $objExpSolicDTO->retStrComplementoContratoOrgao();
    $objExpSolicDTO->retStrSinNecessitaAr();
    $objExpSolicDTO->retStrCartaoPostagem();
    $objExpSolicDTO->retStrCodigoWsCorreioServico();
    $objExpSolicDTO->retStrNomeCidadeContratoOrgao();
    $objExpSolicDTO->retStrNomeCidadeDestinatario();
    $objExpSolicDTO->retNumIdMdCorExpedicaoSolicitada();
    $objExpSolicDTO->setNumIdMdCorPlp($_GET['id_md_cor_plp']);
    $objExpSolicDTO->setDistinct(true);
    $objExpSolicDTO->retStrSinEnderecoAssociado();
    $objExpSolicDTO->setOrd('IdMdCorExpedicaoSolicitada', InfraDTO::$TIPO_ORDENACAO_ASC);

    if (isset($_POST['hdnInfraItensSelecionados']) && $_POST['hdnInfraItensSelecionados'] != '') {
        $objExpSolicDTO->setNumIdMdCorExpedicaoSolicitada(explode(',', $_POST['hdnInfraItensSelecionados']), InfraDTO::$OPER_IN);
    }

    $objMdCorExpedicaoSolicitadaRN = new MdCorExpedicaoSolicitadaRN();
    $arrObjMdCorExpedicaoSolicitadaDTO = $objMdCorExpedicaoSolicitadaRN->listar($objExpSolicDTO);

    if (count($arrObjMdCorExpedicaoSolicitadaDTO) == 0) {
        throw new InfraException('Não possui Expedição Solicitada!');
    }
    $objExpSolicDTO = current($arrObjMdCorExpedicaoSolicitadaDTO);

} catch (Exception $e) {
    PaginaSEI::getInstance()->processarExcecao($e);
}

PaginaSEI::getInstance()->montarDocType();
PaginaSEI::getInstance()->abrirHtml();
PaginaSEI::getInstance()->abrirHead();
PaginaSEI::getInstance()->montarMeta();
PaginaSEI::getInstance()->montarTitle(PaginaSEI::getInstance()->getStrNomeSistema() . ' - ' . $strTitulo);
PaginaSEI::getInstance()->abrirStyle();
include_once("md_cor_plp_imprimir_rotulo_envelope_css.php");
PaginaSEI::getInstance()->fecharStyle();
PaginaSEI::getInstance()->montarJavaScript();
PaginaSEI::getInstance()->abrirJavaScript();
?>
$("#btnInfraTopo").remove();
var objTabelaContatos;
function inicializar(){
if($('#imagemOrgao')[0].offsetWidth > 70){
$('#imagemOrgao')[0].style.width = '70px';
var altura = $('#imagemOrgao')[0].offsetHeight;
$('#imagemOrgao')[0].style.marginTop = (70) / 2 + 'px';
}
}
<?
PaginaSEI::getInstance()->fecharJavaScript();
PaginaSEI::getInstance()->fecharHead();
PaginaSEI::getInstance()->abrirBody($strTitulo, 'onload="inicializar();"');
PaginaSEI::getInstance()->abrirAreaDados();
$count = 0;
?>

<? foreach ($arrObjMdCorExpedicaoSolicitadaDTO as $objExpSolicDTO):

$mdCorContatoDTO = new MdCorContatoDTO();
$mdCorContatoDTO->retNumIdContatoAssociado();
$mdCorContatoDTO->retStrNomeContatoAssociado();
$mdCorContatoDTO->retStrEndereco();
$mdCorContatoDTO->retStrComplemento();
$mdCorContatoDTO->retStrBairro();
$mdCorContatoDTO->retStrCep();
$mdCorContatoDTO->retStrNomeCidade();
$mdCorContatoDTO->retStrSiglaUf();
$mdCorContatoDTO->retStrExpressaoTratamentoCargo();
$mdCorContatoDTO->retStrExpressaoCargo();
$mdCorContatoDTO->setNumIdContato($objExpSolicDTO->getNumIdContatoDestinatario());
$mdCorContatoDTO->setNumIdMdCorExpedicaoSolicitada($objExpSolicDTO->getNumIdMdCorExpedicaoSolicitada());

$mdCorContatoRN = new MdCorContatoRN();
$arrMdCorContatoDTO = $mdCorContatoRN->consultar($mdCorContatoDTO);

$nome = $objExpSolicDTO->getStrNomeDestinatario();

if (!empty($arrMdCorContatoDTO->getStrNomeContatoAssociado()) && ($objExpSolicDTO->getNumIdContatoDestinatario() != $arrMdCorContatoDTO->getNumIdContatoAssociado())) {
    $nome .= '<br>' . $arrMdCorContatoDTO->getStrNomeContatoAssociado();
}

$tratamento = $arrMdCorContatoDTO->getStrExpressaoTratamentoCargo();
$cargo      = $arrMdCorContatoDTO->getStrExpressaoCargo();
$endereco = ($objExpSolicDTO->getStrSinEnderecoAssociado() != 'S') ? $objExpSolicDTO->getStrEnderecoDestinatario() : $arrMdCorContatoDTO->getStrEndereco();
$complemento = ($objExpSolicDTO->getStrSinEnderecoAssociado() != 'S') ? $objExpSolicDTO->getStrComplementoDestinatario() : $arrMdCorContatoDTO->getStrComplemento();
$bairro = ($objExpSolicDTO->getStrSinEnderecoAssociado() != 'S') ? $objExpSolicDTO->getStrBairroDestinatario() : $arrMdCorContatoDTO->getStrBairro();
$cep = ($objExpSolicDTO->getStrSinEnderecoAssociado() != 'S') ? $objExpSolicDTO->getStrCepDestinatario() : $arrMdCorContatoDTO->getStrCep();
$cidade = ($objExpSolicDTO->getStrSinEnderecoAssociado() != 'S') ? $objExpSolicDTO->getStrNomeCidadeDestinatario() : $arrMdCorContatoDTO->getStrNomeCidade();
$uf = ($objExpSolicDTO->getStrSinEnderecoAssociado() != 'S') ? $objExpSolicDTO->getStrSiglaUfDestinatario() : $arrMdCorContatoDTO->getStrSiglaUf();

$unidadeRN = new UnidadeRN();
$unidadeDTO = new UnidadeDTO();
$unidadeDTO->setNumIdUnidade($objExpSolicDTO->getNumIdUnidade());

$arrDadosHierarquia = $unidadeRN->obterDadosHierarquia($unidadeDTO);

$numSei = $objExpSolicDTO->getStrProtocoloFormatadoDocumento();

$hierarquia = $arrDadosHierarquia['RAMIFICACAO'];
$arrHierarquiaUnidade = explode('/', $arrDadosHierarquia['RAMIFICACAO']);
$strHierarquiaUnidade = '';
for ($i = count($arrHierarquiaUnidade) - 1; $i >= 0; $i--) {
    if ($strHierarquiaUnidade != '') {
        $strHierarquiaUnidade .= '/';
    }
    $strHierarquiaUnidade .= $arrHierarquiaUnidade[$i];
}
$strHierarquia = $strHierarquiaUnidade;
$arrConteudoTags = [];
$arrConteudoTags[] = array('@serie@', $objExpSolicDTO->getStrNomeSerie());
$arrConteudoTags[] = array('@numeracao_serie@', $objExpSolicDTO->getStrNumeroDocumento());
$arrConteudoTags[] = array('@ano@', substr($objExpSolicDTO->getDtaGeracaoProtocolo(), 6, 4));
$arrConteudoTags[] = array('@hierarquia_unidade_invertida@', $strHierarquia);
$arrConteudoTags[] = array('@sigla_orgao_origem@', $objExpSolicDTO->getStrSiglaOrgao());


$conteudo = "@serie@ nº @numeracao_serie@/@ano@/SEI/@hierarquia_unidade_invertida@-@sigla_orgao_origem@";
foreach ($arrConteudoTags as $item) {
    $conteudo = str_replace($item[0], $item[1], $conteudo);
}
if ($count == 2) {
    $count = 1;
} else {
    $style = '';
    $count++;
}
?>
<table style="width: 500px;">
    <?php if ($objExpSolicDTO->getStrTipoRotuloImpressaoObjeto() == MdCorObjetoRN::$ROTULO_RESUMIDO): ?>
    <tr>
        <td>
            <div style="margin-top:<?= str_replace(',', '.', $objExpSolicDTO->getDblMargemSuperiorImpressaoObjeto()) ?>cm;display: block;width: 450px;margin-left: <?= str_replace(',', '.', $objExpSolicDTO->getDblMargemEsquerdaImpressaoObjeto()) ?>cm;margin-bottom: 25px;height: 8.4cm;<?= $style ?> ">
                <div style="max-width: 10.1cm; width: 10.1cm; padding: 0.1cm 0.1cm;border: 1px solid #000;">
                    <div style="text-align: center;width: 100%;">
                        <div id="img-qrcode" style="text-align: center">
                            <img src="data:image/png;base64,<?= MdCorExpedicaoSolicitadaINT::montarQrCodeCorreio($objExpSolicDTO) ?>"
                                 id="imgQRCode" style="width: 2.5cm;"/>
                        </div>
                        <div style="margin-right: 5px">
                            <img src="modulos/correios/imagens/svg/logo_correios.svg?<?= Icone::VERSAO ?>" style="height: 30px;">
                            <p class="font-9" style="text-align: left">Contrato:
                                <b><?= $objExpSolicDTO->getStrNumeroContratoCorreio() ?></b></p>
                            <p class="font-9" style="text-align: left">
                                <b><?= $objExpSolicDTO->getStrDescricaoServicoPostal() ?></b></p>
                        </div>
                        <div>
                            <p class="font-9" style="text-align: left">Volume: 1/1</p>
                            <p class="font-9" style="text-align: left">Peso(g): <b>1000</b></p>
                        </div>
                        <div id="img-encaminhamento" style=" float:right;">
                            <img src="modulos/correios/imagens/svg/<?= str_replace('.png', '.svg', $objExpSolicDTO->getStrNomeImagemChancela()) ?>?<?= Icone::VERSAO ?>"
                                 style="width: 1.9cm;"/>
                        </div>
                        <div style="margin-top: 17px;max-width: 131px;">
                            <p style="text-align: Center" class="font-11">
                                <b><?= $objExpSolicDTO->getStrCodigoRastreamento() ?></b></p></div>
                        <div>
                        </div>
                        <div style="text-align: center;width: 100%;">
                            <div style="margin:0 0.5cm">
                                <img src="data:image/png;base64,<?= MdCorExpedicaoSolicitadaINT::montarCodigoBarra($objExpSolicDTO->getStrCodigoRastreamento(), InfraCodigoBarras::$TIPO_CODE128, 2, 320, 68) ?>"
                                     style="width: 8cm;height: 1.8cm"/>
                            </div>
                            <div style="float: right;width: 38px;text-align: right;line-height: 1.2;display: none;">
                                <p style="float: right;" class="font-9"><b>AR XX</b></p>
                                <p style="float: right;" class="font-9"><b>MP XX</b></p>
                                <p style="float: right;display: inline;" class="font-9"><b>DD XX</b></p>
                                <p style="float: right;display: inline;" class="font-9"><b>VD XX</b></p>
                            </div>
                        </div>
                        <div style="width: 100%;line-height: 1.5;">
                            <p style="text-align: left" class="font-9"><?php echo $conteudo; ?> (SEI
                                nº <?php echo $objExpSolicDTO->getStrProtocoloFormatadoDocumento(); ?>)</p>
                            <p style="text-align: left" class="font-9">Processo:
                                nº <?php echo $objExpSolicDTO->getStrProtocoloFormatado(); ?></p>
                        </div>
                    </div>
                </div>
                <div style="max-width:10.1cm; width: 10.1cm; padding: 0.1cm 0.1cm;border: 1px solid #000;border-top-width: 0;height: 2.5cm;max-height: 2.5cm">
                    <div style="text-align: center;">
                        <div style="float:left;position: relative;top: -6px;left: -5px;padding: 0;">
                            <img src="modulos/correios/imagens/svg/destinatario_correio.svg?<?= Icone::VERSAO ?>" style="width: 105px">
                        </div>
                    </div>
                    <div style="float: right">
                        <img src="data:image/png;base64,<?= MdCorExpedicaoSolicitadaINT::montarCodigoBarra(str_replace('-', '', $objExpSolicDTO->getStrCepDestinatario()), InfraCodigoBarras::$TIPO_CODE128, 2, 180) ?>"
                             style="height: 1.8cm;width: 4cm;"/>
                    </div>
                    <div style="width: 229px;position: relative;top: -7px;">
                        <?php if (!empty($tratamento)):?>
                            <p style="text-align: left" class="font-8"><?= trim($tratamento) ?></p>
                        <?php endif; ?>

                        <p style="text-align: left" class="font-8"><?= trim($nome) ?></p>

                        <?php if( !empty($cargo)): ?>
                            <p style="text-align: left" class="font-8"><?= trim($cargo) ?></p>
                        <?php endif; ?>

                        <p style="text-align: left" class="font-8">
                          <?php
                              $strEndCompleto = trim($endereco);
                              $strEndCompleto .= !empty($complemento) ? ', ' . trim($complemento) : '';
                              $strEndCompleto .= ', ' . trim($bairro);
                              echo $strEndCompleto;
                          ?>
                        </p>
                        <p style="text-align: left" class="font-8"><b><?= $cep ?></b> <?= $cidade . '-' . $uf ?></p>
                    </div>
                </div>
                <div style="max-width: 10.1cm; width: 10.1cm; padding: 0.1cm 0.1cm;border: 1px solid #000;border-top-width: 0;">
                    <div style="width: 100%;line-height: 1.2;">
                        <p style="text-align: left" class="font-8">
                            <b>Remetente:</b> <?= trim($objExpSolicDTO->getStrNomeContratoOrgao()) ?></p>
                        <p style="text-align: left"
                           class="font-8"><?= $objExpSolicDTO->getStrEnderecoContratoOrgao() . ', ' . $objExpSolicDTO->getStrComplementoContratoOrgao() ?>
                            - <?= $objExpSolicDTO->getStrBairroContratoOrgao() ?></p>
                        <p style="text-align: left" class="font-8">
                            <b><?= $objExpSolicDTO->getStrCepContratoOrgao() ?></b> <?= $objExpSolicDTO->getStrNomeCidadeContratoOrgao() . '-' . $objExpSolicDTO->getStrSiglaUfContratoOrgao() ?>
                        </p>
                    </div>
                </div>
            </div>

    <? else: ?>

                <div style="display: block;width: 400px;margin-left: <?= str_replace(',', '.', $objExpSolicDTO->getDblMargemEsquerdaImpressaoObjeto()) ?>cm;margin-top:0.5cm;">
                    <div style="max-width: 10.5cm; width: 10.5cm; padding: 0.2cm 0.5cm;border: 1px solid #000;">
                        <div style="text-align: center;width: 100%;">
                            <div id="logo-empresa"
                                 style="width: 2.5cm; height: 2.5cm; float:left; vertical-align: middle; text-align: center;">
                                <img id="imagemOrgao"
                                     src="data:image/png;base64,<?= $objExpSolicDTO->getStrTimbreOrgao() ?>" style="">
                            </div>
                            <div id="img-qrcode" style="margin: 0 30px;text-align: center">
                                <img src="data:image/png;base64,<?= MdCorExpedicaoSolicitadaINT::montarQrCodeCorreio($objExpSolicDTO) ?>"
                                     id="imgQRCode" style="width: 2.5cm;"/>
                                <p class="font-9">Contrato: <b><?= $objExpSolicDTO->getStrNumeroContratoCorreio() ?></b>
                                </p>
                                <p class="font-9"><b><?= $objExpSolicDTO->getStrDescricaoServicoPostal() ?></b></p>
                            </div>
                            <div id="img-encaminhamento" style=" float:right;">
                                <img src="modulos/correios/imagens/svg/<?= str_replace('.png', '.svg', $objExpSolicDTO->getStrNomeImagemChancela()) ?>?<?= Icone::VERSAO ?>"
                                     style="width: 2cm;"/>
                                <p class="font-9" style="margin-top: 23px; text-align: left;">Volume: 1/1</p>
                                <p class="font-9">Peso (g): <b>10</b></p>
                            </div>
                        </div>
                        <div style="text-align: center;width: 100%;margin-top: 5px">
                            <div>
                                <p style="text-align: Center" class="font-11">
                                    <b><?= $objExpSolicDTO->getStrCodigoRastreamento() ?></b></p>
                                <img src="data:image/png;base64,<?= MdCorExpedicaoSolicitadaINT::montarCodigoBarra($objExpSolicDTO->getStrCodigoRastreamento(), InfraCodigoBarras::$TIPO_CODE128, 2, 320, 68) ?>"
                                     style="width: 8cm;height: 1.8cm"/>
                            </div>
                            <div style="float: right;display: inline;width: 70px;    text-align: right;    margin-top: 14px;line-height: 1.2;display: none;">
                                <p style="float: right;" class="font-11"><b>AR XX</b></p>
                                <p style="float: right" class="font-11"><b>MP XX</b></p>
                                <p style="float: right;display: inline" class="font-11"><b>DD XX</b></p>
                                <p style="float: right;display: inline" class="font-11"><b>VD XX</b></p>
                            </div>
                        </div>
                        <div style="width: 100%;line-height: 1.5;">
                            <p style="text-align: left" class="font-9"><?php echo $conteudo; ?> (SEI
                                nº <?php echo $objExpSolicDTO->getStrProtocoloFormatadoDocumento(); ?>)</p>
                            <p style="text-align: left" class="font-9">Processo:
                                nº <?php echo $objExpSolicDTO->getStrProtocoloFormatado(); ?></p>
                        </div>
                    </div>
                    <div style="max-width: 10.5cm; width: 10.5cm; padding: 0.2cm 0.5cm;border: 1px solid #000;border-top-width: 0;">
                        <div style="text-align: center;width: 100%;">
                            <div style="float:left;position: relative;top: -10px;left: -21px;padding: 0;">
                                <img src="modulos/correios/imagens/svg/destinatario_correio.svg?<?= Icone::VERSAO ?>">
                            </div>
                            <div style="float:right; position: relative;top: -9px; right: 0;float: right;z-index: -1;">
                                <img src="modulos/correios/imagens/svg/logo_correios.svg?<?= Icone::VERSAO ?>" style="height: 26px;">
                            </div>
                        </div>
                        <div style="width: 100%;line-height: 1.2;">
	                        <?php if (!empty($tratamento)): ?>
                              <p style="text-align: left" class="font-11"><?= trim($tratamento) ?></p>
	                        <?php endif; ?>

                            <p style="text-align: left" class="font-11"><?= trim($nome) ?></p>

	                        <?php if( !empty($cargo)): ?>
                              <p style="text-align: left" class="font-11"><?= trim($cargo) ?></p>
	                        <?php endif; ?>

                            <p style="text-align: left" class="font-11">
                                <?php
                                    $strEndCompleto = trim($endereco);
                                    $strEndCompleto .= !empty($objExpSolicDTO->getStrComplementoDestinatario()) ? ', ' . trim($objExpSolicDTO->getStrComplementoDestinatario()) : '';
                                    $strEndCompleto .= ', ' . trim($bairro);
                                    echo $strEndCompleto;
                                ?>
                            </p>
                            <p style="text-align: left" class="font-11">
                                <b><?= $cep ?></b> <?= $cidade . '-' . $uf ?>
                            </p>
                        </div>
                        <div>
                            <img src="data:image/png;base64,<?= MdCorExpedicaoSolicitadaINT::montarCodigoBarra(str_replace('-', '', $objExpSolicDTO->getStrCepDestinatario()), InfraCodigoBarras::$TIPO_CODE128, 2, 180) ?>"
                                 style="height: 1.8cm;width: 4cm;"/>
                        </div>
                    </div>
                    <div style="max-width: 10.5cm; width: 10.5cm; padding: 0.2cm 0.5cm;border: 1px solid #000;border-top-width: 0;">
                        <div style="width: 100%;line-height: 1.2;">
                            <p style="text-align: left" class="font-10">
                                <b>Remetente:</b> <?= trim($objExpSolicDTO->getStrNomeContratoOrgao()) ?></p>
                            <p style="text-align: left"
                               class="font-10"><?= $objExpSolicDTO->getStrEnderecoContratoOrgao() . ', ' . $objExpSolicDTO->getStrComplementoContratoOrgao() ?></p>
                            <p style="text-align: left"
                               class="font-10"><?= $objExpSolicDTO->getStrBairroContratoOrgao() ?></p>
                            <p style="text-align: left" class="font-10">
                                <b><?= $objExpSolicDTO->getStrCepContratoOrgao() ?></b> <?= $objExpSolicDTO->getStrNomeCidadeContratoOrgao() . '-' . $objExpSolicDTO->getStrSiglaUfContratoOrgao() ?>
                            </p>
                        </div>
                    </div>
                </div>
            <? endif; ?>
        </td>
    </tr>
    <? endforeach; ?>
</table>
<?
PaginaSEI::getInstance()->fecharAreaDados();
PaginaSEI::getInstance()->fecharAreaTabela();
PaginaSEI::getInstance()->fecharBody();
PaginaSEI::getInstance()->fecharHtml();
?>
<script>
    $("#btnInfraTopo").remove();
    window.print();
</script>