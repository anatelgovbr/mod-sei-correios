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
    if ($count == 2) {
        $style = 'page-break-after: always;';
        $count = 0;
    } else {
        $style = '';
        $count++;
    }
    ?>
    <table class="table-ar" style="width: 715px; <?= $style ?>">
        <tr class="td-ar">
            <td class="verticalTableHeader" rowspan="6"><p>Cole Aqui</p></td>
            <td class="vazioTableHeader" rowspan="6"></td>
            <td class="pd-0" colspan="3">
                <table style="width: 600px">
                    <tr align="center">
                        <td class="pd-0">
                        <img src="modulos/correios/imagens/svg/logo_correios.svg?<?= Icone::VERSAO ?>" class="logo-correio"/>
                        </td>
                        <td class="pd-0">
                            <h5 class="titulo-correio">AVISO DE RECEBIMENTO</h5>
                        </td>
                        <td class="pd-0">
                            <h5 class="titulo-correio">CONTRATO <?php echo $objExpSolicDTO->getStrNumeroContratoCorreio();?></h5>
                        </td>
                    </tr>
                </table>
            </td>
        </tr>
        <tr>
            <!--DESTINATARIO-->
            <td rowspan="2" class="td-ar" style="width: 350px">
                <table>
                    <tr>
                        <td class="pd-0">
                            <p><b>DESTINATÁRIO:</b></p>
                            <p><?= trim($nome) ?></p>
                            <p><?= $endereco ?>, <?= $complemento ?></p>
                            <p><?= $bairro ?></p>
                            <p><?= $cep ?> - <?= $cidade . '/' . $uf ?></p>
                            <p style='text-indent: 67px; padding-top: 10px'><b><?= $objExpSolicDTO->getStrCodigoRastreamento() ?></b></p>
                            <p><img src="data:image/png;base64,<?= MdCorExpedicaoSolicitadaINT::montarCodigoBarra($objExpSolicDTO->getStrCodigoRastreamento(), InfraCodigoBarras::$TIPO_CODE128) ?>"/></p>
                            <p style="padding-top: 10px">
                                <label><b>REMETENTE:</b></label>
                                <span><?= $objExpSolicDTO->getStrNomeContratoOrgao() ?></span>
                            </p>
                            <p><b>ENDEREÇO PARA DEVOLUÇÃO DO OBJETO:</b></p>
                            <p><?= $objExpSolicDTO->getStrEnderecoContratoOrgao() . ', ' . $objExpSolicDTO->getStrComplementoContratoOrgao() ?></p>
                            <p><?= $objExpSolicDTO->getStrBairroContratoOrgao() ?></p>
                            <p><?= $objExpSolicDTO->getStrCepContratoOrgao() . ' - ' . $objExpSolicDTO->getStrNomeCidadeContratoOrgao() . '/' . $objExpSolicDTO->getStrSiglaUfContratoOrgao() ?></p>
                        </td>
                        <td style="vertical-align: baseline;">
                            <p style="margin-right: 7px" align="right"><b><? echo $objExpSolicDTO->getStrCodigoRastreamento() ?></b></p>
                            <img align="right" class="qr-code" src="data:image/png;base64,<?= MdCorExpedicaoSolicitadaINT::montarQrCode($objExpSolicDTO->getStrCodigoRastreamento()) ?>"id="imgQRCode"/>
                        </td>
                    </tr>
                </table>
            </td>

            <!--TENTATIVAS DE ENTREGA-->
            <td class="pd-0" rowspan="3" style="vertical-align: baseline; width: 220px">
                <p style="padding-bottom: 15px"><b>TENTATIVAS DE ENTREGA:</b></p>
                <p class="mg-tp-7">1º ____/____/____&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; ____:____h</p>
                <p class="mg-tp-7">2º ____/____/____&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; ____:____h</p>
                <p class="mg-tp-7">3º ____/____/____&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; ____:____h</p>
                <p style="padding-top: 25px">MOTIVO DE DEVOLUÇÃO:</p>
                <table>
                    <tr>
                        <td>
                            <p><span class="box-numero">1</span> Mudou-se</p>
                        </td>
                        <td>
                            <p><span class="box-numero">5</span> Recusado</p>
                        </td>
                    </tr>
                    <tr>
                        <td>
                            <p><span class="box-numero">2</span> Endereço Insuficiente</p>
                        </td>
                        <td>
                            <p><span class="box-numero">6</span> Não Procurado</p>
                        </td>
                    </tr>
                    <tr>
                        <td>
                            <p><span class="box-numero">3</span> Não Existe o Número</p>
                        </td>
                        <td>
                            <p><span class="box-numero">7</span> Ausente</p>
                        </td>
                    </tr>
                    <tr>
                        <td>
                            <p><span class="box-numero">4</span> Desconhecido</p>
                        </td>
                        <td>
                            <p><span class="box-numero">8</span> Falecido</p>
                        </td>
                    </tr>
                    <tr>
                        <td colspan="2">
                            <p><span class="box-numero">9</span> Outros___________________________</p>
                        </td>
                    </tr>
                </table>
            </td>
            <td class="td-ar texto-alinhado-acima" style="height: 150px; text-align: center">
                <p style="font-size: 4pt;">CARIMBO<br> UNIDADE DE ENTREGA</p>
            </td>
        </tr>
        <tr>
            <td class="td-ar texto-alinhado-acima" rowspan="4" style="text-align: center">
                <p style="font-size: 4pt;">RUBRICA E MATRÍCULA DO CARTEIRO</p>
            </td>
        </tr>
        <tr>
            <td class="td-ar"  style="text-align: left;border: 1px solid black;padding: 0px;height: 20px;vertical-align: top;">
                <p class="assinatura">DECLARAÇÃO DE CONTEÚDO</p>
                <p>
                    <span style="font-weight: bold;">Documento Principal:</span>
                    <label>
                        <?php
                        echo "{$objExpSolicDTO->getStrNomeSerie()} {$objExpSolicDTO->getStrNumeroDocumento()} ({$objExpSolicDTO->getStrProtocoloFormatadoDocumento()})";
                        ?>
                    </label>
                </p>
                <p>
                    <span style="font-weight: bold;">Anexos:</span>
                    <label>
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
                </p>
                <p>
                    <span style="font-weight: bold;">Processo nº</span>
                    <label>
                        <?php echo $objExpSolicDTO->getStrProtocoloFormatado(); ?>
                    </label>
                </p>
            </td>
        </tr>
        <tr>
            <td class="td-ar label-campo">
                <p class="assinatura">Assinatura do Recebedor</p>
            </td>
            <td class="td-ar label-campo">
                <p class="assinatura">Data de entrega</p>
            </td>
        </tr>
        <tr>
            <td class="td-ar label-campo">
                <p class="assinatura">Nome Legível do Recebedor</p>
            </td>
            <td class="td-ar label-campo">
                <p class="assinatura">N° Doc de identidade</p>
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