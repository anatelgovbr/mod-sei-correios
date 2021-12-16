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
        case 'md_cor_plp_imprimir_ar':
            $strTitulo = '';
            break;
        default:
            throw new InfraException("Ação '" . $_GET['acao'] . "' não reconhecida.");
    }

    //colocando a pagina sem menu e titulo inicial
    PaginaSEI::getInstance()->setTipoPagina(InfraPagina::$TIPO_PAGINA_SIMPLES);

    //recuperando a expedição solicitada
    $objExpSolicDTO = new MdCorExpedicaoSolicitadaDTO();
    $objExpSolicDTO->retNumIdContatoDestinatario();
    $objExpSolicDTO->retStrNumeroContratoCorreio();
    $objExpSolicDTO->retStrProtocoloFormatadoDocumento();
    $objExpSolicDTO->retStrCodigoRastreamento();
    $objExpSolicDTO->retStrNomeDestinatario();
    $objExpSolicDTO->retStrEnderecoDestinatario();
    $objExpSolicDTO->retStrComplementoDestinatario();
    $objExpSolicDTO->retStrBairroDestinatario();
    $objExpSolicDTO->retStrCepDestinatario();
    $objExpSolicDTO->retStrNomeCidadeDestinatario();
    $objExpSolicDTO->retStrSiglaUfDestinatario();
    $objExpSolicDTO->retStrCodigoRastreamento();
    $objExpSolicDTO->retStrNomeContratoOrgao();
    $objExpSolicDTO->retStrEnderecoContratoOrgao();
    $objExpSolicDTO->retStrComplementoContratoOrgao();
    $objExpSolicDTO->retStrCepContratoOrgao();
    $objExpSolicDTO->retStrNomeCidadeContratoOrgao();
    $objExpSolicDTO->retStrSiglaUfContratoOrgao();
    $objExpSolicDTO->retStrBairroContratoOrgao();
    $objExpSolicDTO->retStrNumeroDocumento();
    $objExpSolicDTO->retStrProtocoloFormatado();
    $objExpSolicDTO->retNumIdUnidade();
    $objExpSolicDTO->retNumIdMdCorExpedicaoSolicitada();
    $objExpSolicDTO->retStrNomeSerie();
    $objExpSolicDTO->retDtaGeracaoProtocolo();
    $objExpSolicDTO->retStrSiglaOrgao();
    $objExpSolicDTO->retStrSinEnderecoAssociado();

    $objExpSolicDTO->setNumIdMdCorPlp($_GET['id_md_cor_plp']);
    $objExpSolicDTO->setStrSinNecessitaAr('S');
    $objExpSolicDTO->setDistinct(true);
    $objExpSolicDTO->setOrd('IdMdCorExpedicaoSolicitada', InfraDTO::$TIPO_ORDENACAO_ASC);

    if (isset($_POST['hdnInfraItensSelecionados']) && $_POST['hdnInfraItensSelecionados'] != '') {
        $objExpSolicDTO->setNumIdMdCorExpedicaoSolicitada(explode(',', $_POST['hdnInfraItensSelecionados']), InfraDTO::$OPER_IN);
    }

    $objMdCorExpedicaoSolicitadaRN = new MdCorExpedicaoSolicitadaRN();
    $arrObjMdCorExpedicaoSolicitadaDTO = $objMdCorExpedicaoSolicitadaRN->listar($objExpSolicDTO);

    if (count($arrObjMdCorExpedicaoSolicitadaDTO) == 0) {
        throw new InfraException('Nenhuma solicitação de expedição possui AR!');
    }


} catch (Exception $e) {
    PaginaSEI::getInstance()->processarExcecao($e);
}

PaginaSEI::getInstance()->montarDocType();
PaginaSEI::getInstance()->abrirHtml();
PaginaSEI::getInstance()->abrirHead();
PaginaSEI::getInstance()->montarMeta();
PaginaSEI::getInstance()->montarTitle(PaginaSEI::getInstance()->getStrNomeSistema() . ' - ' . $strTitulo);
//PaginaSEI::getInstance()->montarStyle();
PaginaSEI::getInstance()->abrirStyle();
include_once("md_cor_plp_imprimir_ar_css.php");
PaginaSEI::getInstance()->fecharStyle();
PaginaSEI::getInstance()->montarJavaScript();
PaginaSEI::getInstance()->abrirJavaScript();
PaginaSEI::getInstance()->fecharJavaScript();
PaginaSEI::getInstance()->fecharHead();
PaginaSEI::getInstance()->abrirBody($strTitulo, 'onload="inicializar();"');
?>
<?
PaginaSEI::getInstance()->abrirAreaDados('100%', 'style="overflow-y: hidden;"');
$count = 0;
?>
<? foreach ($arrObjMdCorExpedicaoSolicitadaDTO as $objExpSolicDTO): ?>
    <?

    $mdCorContatoDTO = new MdCorContatoDTO();
    $mdCorContatoDTO->retNumIdContatoAssociado();
    $mdCorContatoDTO->retStrNomeContatoAssociado();
    $mdCorContatoDTO->retStrEndereco();
    $mdCorContatoDTO->retStrComplemento();
    $mdCorContatoDTO->retStrBairro();
    $mdCorContatoDTO->retStrCep();
    $mdCorContatoDTO->retStrNomeCidade();
    $mdCorContatoDTO->retStrSiglaUf();
    $mdCorContatoDTO->setNumIdContato($objExpSolicDTO->getNumIdContatoDestinatario());
    $mdCorContatoDTO->setNumIdMdCorExpedicaoSolicitada($objExpSolicDTO->getNumIdMdCorExpedicaoSolicitada());

    $mdCorContatoRN = new MdCorContatoRN();
    $arrMdCorContatoDTO = $mdCorContatoRN->consultar($mdCorContatoDTO);

    $nome = $objExpSolicDTO->getStrNomeDestinatario();

    if (!empty($arrMdCorContatoDTO->getStrNomeContatoAssociado()) && ($objExpSolicDTO->getNumIdContatoDestinatario() != $arrMdCorContatoDTO->getNumIdContatoAssociado())) {
        $nome .= '<br>' . $arrMdCorContatoDTO->getStrNomeContatoAssociado();
    }

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


    $objMdCorExpedicaoFormatoDTO = new MdCorExpedicaoFormatoDTO();
    $objMdCorExpedicaoFormatoDTO->retStrProtocoloFormatado();
    $objMdCorExpedicaoFormatoDTO->retStrNomeSerie();
    $objMdCorExpedicaoFormatoDTO->retStrNumeroDocumento();
    $objMdCorExpedicaoFormatoDTO->setNumIdMdCorExpedicaoSolicitada($objExpSolicDTO->getNumIdMdCorExpedicaoSolicitada());
    $objMdCorExpedicaoFormatoDTO->setStrProtocoloFormatado($objExpSolicDTO->getStrProtocoloFormatadoDocumento(), INFRADTO::$OPER_DIFERENTE);

    $objMdCorExpedicaoFormatoRN = new MdCorExpedicaoFormatoRN();
    $arrObjMdCorExpedicaoFormatoDTO = $objMdCorExpedicaoFormatoRN->listar($objMdCorExpedicaoFormatoDTO);

    $conteudo = "@serie@ @numeracao_serie@";
    foreach ($arrConteudoTags as $item) {
        $conteudo = str_replace($item[0], $item[1], $conteudo);
    }
    if ($count == 1) {
        $style = 'page-break-after: always;';
        $count = 0;
    } else {
        $style = '';
        $count++;
    }
    ?>
    <table width="100%"
           style="height: 430px; border: 1px solid black; padding: 1px;border-collapse: collapse; max-width: 20cm;margin-top: 1px;<?= $style ?>">
        <tr>
            <td class="verticalTableHeader" rowspan="12"><p>Cole Aqui</p></td>
            <td class="vazioTableHeader" rowspan="12"></td>
            <td style="text-align: center;font-weight: bold;border: 1px solid black;padding: 5px; height: 40px"
                colspan="3">
                <img src="modulos/correios/imagens/logo-correio.png" class="logo-correio"/>
                <h3 class="titulo-correio"></h3>
                <h5 class="titulo-correio" style="text-align: left;margin-left: 80px;">AVISO DE RECEBIMENTO <span
                            style="margin-left: 100px; font-weight: bold;">CONTRATO <?php echo $objExpSolicDTO->getStrNumeroContratoCorreio(); ?></span
                           >
                </h5>
            </td>
        </tr>
        <tr>
            <td style="text-align: left;border: 0 solid black;padding: 0;border-right-width:1px;">
                <p><b>DESTINATÁRIO:</b></p>
            </td>
            <td style="border: 0 solid black;padding: 0;width: 6cm;border-right-width:1px;">
                <p><b>TENTATIVAS DE ENTREGA:</b></p>
            </td>
            <td style="text-align: center;border: 0 solid black;padding: 0;border-right-width:1px;width: 3cm;">
                <p style="font-size: 4pt;">CARIMBO<br> UNIDADE DE ENTREGA</p>
            </td>
        </tr>
        <tr>
            <td style="text-align: left;border: 0 solid black;padding: 0;border-right-width:1px;">
                <p style="font-size: 7pt;text-align: right; margin-top: -10px; margin-right: 6px">
                    <b><? echo $objExpSolicDTO->getStrCodigoRastreamento() ?></b>
                </p>
                <img src="data:image/png;base64,<?= MdCorExpedicaoSolicitadaINT::montarQrCode($objExpSolicDTO->getStrCodigoRastreamento()) ?>"
                     id="imgQRCode"/>
                <p><?= trim($nome) ?></p>
                <p><?= $endereco ?>, <?= $complemento ?>
                </p>
                <p><?= $bairro ?></p>
                <p><?= $cep ?> - <?= $cidade . '/' . $uf ?></p>
                <br/>
                <p style='text-indent: 55px;'><b><?= $objExpSolicDTO->getStrCodigoRastreamento() ?></b></p>
                <p>
                    <img src="data:image/png;base64,<?= MdCorExpedicaoSolicitadaINT::montarCodigoBarra($objExpSolicDTO->getStrCodigoRastreamento(), InfraCodigoBarras::$TIPO_CODE128) ?>"/>
                </p>
            </td>
            <td style="border:0 solid black;padding: 0;border-right-width:1px;">
                <p style="margin-top: 7px;">1º ____/____/____&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; ____:____h</p>
                <p style="margin-top: 7px;">2º ____/____/____&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; ____:____h</p>
                <p style="margin-top: 7px;">3º ____/____/____&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; ____:____h</p>
            </td>
            <td style="text-align: center;border: 0 solid black;padding: 0;border-right-width:1px;">
            </td>
        </tr>
        <tr>
            <td style="text-align: center;border: 0 solid black;padding:0;border-right-width:1px;">

            </td>
            <td style="border:0 solid black;padding: 0;border-right-width:1px;vertical-align: bottom;">
                <p>MOTIVO DE DEVOLUÇÃO:</p>
            </td>
            <td style="text-align: center;border: 0 solid black;padding: 0;border-right-width:1px;">
            </td>
        </tr>
        <tr>
            <td style="border:0 solid black;padding: 0;border-right-width:1px;">
                <div style="margin-left: 5px;">
                    <div style="font-size: 7px;margin-top: 2px;">
                        <label style="font-size: 1.5em"><b>REMETENTE:</b></label>
                        <span style="font-size: 1.4em"><?= $objExpSolicDTO->getStrNomeContratoOrgao() ?></span>
                    </div>
                </div>
            </td>
            <td style="border:0 solid black;padding: 0;border-right-width:1px;">
                <p style="display: inline"><span class="box-numero">1</span> Mudou-se</p>
                <p style="display: inline;margin-left: 77px;"><span class="box-numero">5</span> Recusado</p>
            </td>
            <td style="text-align: center;border: 0 solid black;padding: 0;border-right-width:1px;border-bottom-width:1px;">
            </td>
        </tr>
        <tr>
            <td style="text-align: left;border: 0 solid black;padding: 0;border-right-width:1px;">
                <p><b>ENDEREÇO PARA DEVOLUÇÃO DO OBJETO:</b></p>
            </td>
            <td style="border:0 solid black;padding: 0;border-right-width:1px;">
                <p style="display: inline"><span class="box-numero">2</span> Endereço Insuficiente</p>
                <p style="display: inline;margin-left: 29px;"><span class="box-numero">6</span> Não Procurado</p>
            </td>
            <td style="text-align: center;border: 0 solid black;padding: 0;border-right-width:1px;">
                <p style="font-size: 4pt;">RUBRICA E MATRÍCULA DO CARTEIRO</p>
            </td>
        </tr>
        <tr>
            <td style="text-align: left;border: 0 solid black;padding: 0;border-right-width:1px;">
                <p><?= $objExpSolicDTO->getStrEnderecoContratoOrgao() . ', ' . $objExpSolicDTO->getStrComplementoContratoOrgao() ?></p>
            </td>
            <td style="border:0 solid black;padding: 0;border-right-width:1px;">
                <p style="display: inline"><span class="box-numero">3</span> Não Existe o Número</p>
                <p style="display: inline;margin-left: 30px;"><span class="box-numero">7</span> Ausente</p>
            </td>
            <td style="text-align: center;border: 0 solid black;padding: 0;border-right-width:1px;">
            </td>
        </tr>
        <tr>
            <td style="text-align: left;border: 0 solid black;padding: 0;border-right-width:1px;">
                <p><?= $objExpSolicDTO->getStrBairroContratoOrgao() ?></p>
            </td>
            <td style="border:0 solid black;padding: 0;border-right-width:1px;">
                <p style="display: inline"><span class="box-numero">4</span> Desconhecido</p>
                <p style="display: inline;margin-left: 59px;"><span class="box-numero">8</span> Falecido</p>
            </td>
            <td style="text-align: center;border: 0 solid black;padding: 0;border-right-width:1px;">
            </td>
        </tr>
        <tr>
            <td style="text-align: left;border: 0 solid black;padding: 0;border-right-width:1px;">
                <p><?= $objExpSolicDTO->getStrCepContratoOrgao() . ' - ' . $objExpSolicDTO->getStrNomeCidadeContratoOrgao() . '/' . $objExpSolicDTO->getStrSiglaUfContratoOrgao() ?></p>
            </td>
            <td style="border:0 solid black;padding: 0;border-right-width:1px;">
                <p style="display: inline"><span class="box-numero">9</span> Outros______________________________</p>
            </td>
            <td style="text-align: center;border: 0 solid black;padding: 0;border-right-width:1px;">
            </td>
        </tr>
        <tr>
            <td style="text-align: left;border: 1px solid black;padding: 0;height: 20px;vertical-align: top;">
                <p class="assinatura">DECLARAÇÃO DE CONTEÚDO</p>
                <div style="margin-left: 5px;">
                    <div style="font-size: 7px;margin-top: 2px;">
                        <span style="font-weight: bold;">Documento Principal:</span>
                        <label style="font-size: 1.5em">
                            <?php
                            echo "{$objExpSolicDTO->getStrNomeSerie()} {$objExpSolicDTO->getStrNumeroDocumento()} ({$objExpSolicDTO->getStrProtocoloFormatadoDocumento()})";
                            ?>
                        </label>
                    </div>

                    <div style="font-size: 7px;margin-bottom: 2px;">
                    <span style="font-weight: bold;">
                    Anexos:
                    </span>
                        <label style="font-size: 1.5em">
                            <?php
                            $conteudoAnexo = '';
                            if (count($arrObjMdCorExpedicaoFormatoDTO) <= 4) {
                                foreach ($arrObjMdCorExpedicaoFormatoDTO as $item) {
                                    $conteudoAnexo .= "{$item->getStrNomeSerie()} {$item->getStrNumeroDocumento()} ({$item->getStrProtocoloFormatado()}); ";
                                }
                            } else {
                                foreach ($arrObjMdCorExpedicaoFormatoDTO as $item) {
                                    $conteudoAnexo .= "{$item->getStrProtocoloFormatado()}; ";
                                }
                            }
                            $conteudoAnexo = rtrim(rtrim($conteudoAnexo), '; ');
                            echo $conteudoAnexo;
                            ?>
                        </label>
                    </div>

                    <div style="font-size: 7px;margin-top: 2px;">
                        <span style="font-weight: bold;">Processo nº</span>
                        <label style="font-size: 1.5em">
                            <?php echo $objExpSolicDTO->getStrProtocoloFormatado(); ?>
                        </label>
                    </div>

                </div>
            </td>
            <td style="border:0 solid black;padding: 5px;border-right-width:1px;">
            </td>
            <td style="text-align: center;border: 0 solid black;padding: 0;border-right-width:1px;">
            </td>
        </tr>
        <tr>
            <td style="text-align: left;border: 0 solid black;padding: 0;border-right-width:1px;height: 20px;vertical-align: top;">
                <p class="assinatura">Assinatura do Recebedor</p>
            </td>
            <td style="border:1px solid black;padding: 0;height: 20px;vertical-align: top;">
                <p class="assinatura">Data de entrega</p>
            </td>
            <td style="text-align: center;border: 0 solid black;padding: 0;">
            </td>
        </tr>
        <tr>
            <td style="text-align: left;border: 1px solid black;padding: 0px;height: 20px;vertical-align: top;">
                <p class="assinatura">Nome Legível do Recebedor</p>
            </td>
            <td style=" border:1px solid black;padding: 0;height: 20px;vertical-align: top;">
                <p class="assinatura">N° Doc de identidade</p>
            </td>
            <td style="text-align: center;border: 0 solid black;padding: 5px;border-right-width:1px;border-bottom-width: 1px;">
            </td>
        </tr>
    </table>
<? endforeach; ?>
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