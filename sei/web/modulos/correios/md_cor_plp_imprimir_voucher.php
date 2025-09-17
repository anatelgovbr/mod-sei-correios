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
        case 'md_cor_plp_imprimir_voucher':
            $strTitulo = '';
            break;

        default:
            throw new InfraException("Ação '" . $_GET['acao'] . "' não reconhecida.");
    }

    //colocando a pagina sem menu e titulo inicial
    PaginaSEI::getInstance()->setTipoPagina(InfraPagina::$TIPO_PAGINA_SIMPLES);

    //recuperando a PLP
    $objMdCorPlpDTO = new MdCorPlpDTO();
    $objMdCorPlpDTO->retNumIdMdPlp();
    $objMdCorPlpDTO->retDblCodigoPlp();
    $objMdCorPlpDTO->retStrStaPlp();
    $objMdCorPlpDTO->retNumContagem();

    $objMdCorPlpDTO->setNumIdMdPlp($_GET['id_md_cor_plp']);

    $objMdCorPlpRN = new MdCorPlpRN();
    $objMdCorPlpDTO = $objMdCorPlpRN->consultar($objMdCorPlpDTO);

    //preparando a imagem do código de barra
    $nomeArquivo = InfraCodigoBarras::gerar($objMdCorPlpDTO->getDblCodigoPlp(), DIR_SEI_TEMP, InfraCodigoBarras::$TIPO_CODE128, InfraCodigoBarras::$COR_PRETO, 2, 50, 0, (13 * strlen($objMdCorPlpDTO->getDblCodigoPlp()) + 30) * 2, 50, InfraCodigoBarras::$FORMATO_PNG);
    $strArquivoCodigoBarras = DIR_SEI_TEMP . '/' . $nomeArquivo;
    $fp = fopen($strArquivoCodigoBarras, "r");
    $imgCodigoBarras = fread($fp, filesize($strArquivoCodigoBarras));
    fclose($fp);
    unlink($strArquivoCodigoBarras);

    //recuperando a expedição solicitada
    $objMdCorExpedicaoSolicitadaDTO = new MdCorExpedicaoSolicitadaDTO();
    $objMdCorExpedicaoSolicitadaDTO->retTodos(true);
    $objMdCorExpedicaoSolicitadaDTO->setNumIdMdCorPlp($_GET['id_md_cor_plp']);

    if (isset($_POST['hdnInfraItensSelecionados']) && $_POST['hdnInfraItensSelecionados'] != '') {
        $objMdCorExpedicaoSolicitadaDTO->setNumIdMdCorExpedicaoSolicitada(explode(',', $_POST['hdnInfraItensSelecionados']), InfraDTO::$OPER_IN);
    }

    $objMdCorExpedicaoSolicitadaRN = new MdCorExpedicaoSolicitadaRN();
    $arrObjMdCorExpedicaoSolicitadaDTO = $objMdCorExpedicaoSolicitadaRN->listar($objMdCorExpedicaoSolicitadaDTO);

    if (count($arrObjMdCorExpedicaoSolicitadaDTO) == 0) {
        throw new InfraException('Não possui Expedição Solicitada!');
    }

    $objMdCorExpedicaoSolicitadaDTO = current($arrObjMdCorExpedicaoSolicitadaDTO);
    $strContrato = $objMdCorExpedicaoSolicitadaDTO->getStrNumeroContratoCorreio();

    $strTelefoneContato = '';

    //retornando o orgão do contrato
    if ($objMdCorExpedicaoSolicitadaDTO->getNumNumeroCnpj() != '') {
        $objOrgaoDTO = new OrgaoDTO();
        $objOrgaoDTO->setNumIdOrgao(SessaoSEI::getInstance()->getNumIdOrgaoUnidadeAtual());
        $objOrgaoDTO->retNumIdContato();
        $objOrgaoRN = new OrgaoRN();
        $objOrgaoRN = $objOrgaoRN->consultarRN1352($objOrgaoDTO);

        $unidadeDTO = new UnidadeDTO();
        $unidadeDTO->retNumIdContato();
        $unidadeDTO->setNumIdUnidade($objMdCorExpedicaoSolicitadaDTO->getDblIdUnidadeExpedidora());

        $unidadeRN = new UnidadeRN();
        $unidadeDTO = $unidadeRN->consultarRN0125($unidadeDTO);

        $contatoUnidadeExpedidora = new ContatoDTO();
        $contatoUnidadeExpedidora->retStrTelefoneComercial();
        $contatoUnidadeExpedidora->setNumIdContato($unidadeDTO->getNumIdContato());
        $objContatoRN = new ContatoRN();
        $contatoUnidadeExpedidora = $objContatoRN->consultarRN0324($contatoUnidadeExpedidora);


        $objContatoDTO = new ContatoDTO();
        $objContatoDTO->retNumIdContato();
        $objContatoDTO->retStrNome();
        $objContatoDTO->retStrCnpj();
        $objContatoDTO->retStrSigla();
        $objContatoDTO->retStrEmail();
        $objContatoDTO->retStrTelefoneCelular();
        $objContatoDTO->setNumIdContato($objOrgaoRN->getNumIdContato());

        $objContatoRN = new ContatoRN();
        $objContatoDTO = $objContatoRN->consultarRN0324($objContatoDTO);

        if (!is_null($objContatoDTO)) {
            $strCliente = $objContatoDTO ? $objContatoDTO->getStrNome().' ('.$objContatoDTO->getStrSigla().') - CNPJ: '. InfraUtil::formatarCnpj($objContatoDTO->getStrCnpj()): null;

            $strTelefoneContato = $contatoUnidadeExpedidora->getStrTelefoneComercial();

        } else {
            $strCliente = '';
            $strTelefoneContato = '';
        }
    }

    //montando a lista de serviço
    $arrServico = array();
    $arrDocumentoPrincipais = array();
    foreach ($arrObjMdCorExpedicaoSolicitadaDTO as $objMdCorExpedicaoSolicitadaDTO) {
        if(!in_array($objMdCorExpedicaoSolicitadaDTO->getDblIdDocumentoPrincipal(), $arrDocumentoPrincipais)){
            array_push($arrDocumentoPrincipais, $objMdCorExpedicaoSolicitadaDTO->getDblIdDocumentoPrincipal());        
            $idServicoPostal = $objMdCorExpedicaoSolicitadaDTO->getNumIdMdCorServicoPostal();
            if (isset($arrServico[$idServicoPostal])) {
                $arrServico[$idServicoPostal]['quantidade'] += 1;
            } else {
                $arrServico[$idServicoPostal]['quantidade'] = 1;
                $arrServico[$idServicoPostal]['codigo'] = $objMdCorExpedicaoSolicitadaDTO->getStrCodigoWsCorreioServico();
                $arrServico[$idServicoPostal]['nome'] = $objMdCorExpedicaoSolicitadaDTO->getStrNomeServicoPostal();
            }
        }
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
?>
td p{margin: 5px;}
td{padding: 5px;}
td b{font-weight: bold;}
hr {display: block;margin-top: 20px;margin-bottom: 20px;margin-left: auto;margin-right: auto;border-style: dashed;border-width: 1px;height: 0;border-bottom: dashed #0a0a0a .1em;}

@media print {
* {background:transparent !important;color:#000 !important;text-shadow:none !important;filter:none !important;-ms-filter:none !important;}

body {margin:0;padding:0;overflow-y:hidden;font: 9pt Georgia, "Times New Roman", serif;color: #000;}
td {padding: 0.5cm;}
#tableServico tr td,#tableServico tr th{padding: 0;}
@page {margin: 0cm;}
}
img.logo-correio{position: relative;left: 10px;top: -10px; float:left}
h2.titulo-correio{position: relative;right: 10px;top: 0;font-weight: bold;display: inline;float: right;}
#tableServico tr td,#tableServico tr th{padding: 0;}
<?
PaginaSEI::getInstance()->fecharStyle();
PaginaSEI::getInstance()->montarJavaScript();
PaginaSEI::getInstance()->abrirJavaScript();
PaginaSEI::getInstance()->fecharJavaScript();
PaginaSEI::getInstance()->fecharHead();
PaginaSEI::getInstance()->abrirBody($strTitulo, 'onload="inicializar();"');
?>
<?
PaginaSEI::getInstance()->abrirAreaDados('100%', 'style="overflow-y: hidden;"');
?>
<div>
    <img class="logo-correio" src="modulos/correios/imagens/svg/logo_correios.svg?<?= Icone::VERSAO ?>"  />
    <h2 class="titulo-correio">EMPRESA BRASILEIRA DE CORREIOS E TELÉGRAFOS</h2>
</div>
<table width="100%" style="border: 1px solid black; padding: 5px;border-collapse: collapse;">
    <tr >
        <td style="text-align: center;font-weight: bold;border: 1px solid black;padding: 5px;" colspan="2">PRÉ - LISTA DE POSTAGEM - PLP - SIGEP WEB</td>
    </tr>
    <tr>
        <td style="border-bottom: 1px solid black;padding: 5px;width: 80%;">
            <p><b>SIGEP WEB - Gerenciador de Postagens dos Correios</b></p>
            <p><b>Contrato:</b> <?= $strContrato ?></p>
            <p><b>Cliente:</b> <?= $strCliente ?></p>
            <p><b>Telefone de Contato:</b> <?= $strTelefoneContato ?></p>
        </td>
        <td style="text-align:center;border-bottom: 1px solid black;padding: 5px;width: 20%;">
            <p><b>Nº PLP: <?= $objMdCorPlpDTO->getDblCodigoPlp() ?></b></p>
            <img src="data:image/png;base64,<?= base64_encode($imgCodigoBarras) ?>" />
        </td>
    </tr>
    <tr>
        <td style="width: 80%;">
            <table width="100%" id="tableServico">
                <tr>
                    <th style="padding: 0;margin: 0">Cód. Serviço</th>
                    <th style="padding: 0;margin: 0">Quantidade</th>
                    <th style="padding: 0;margin: 0">Serviço</th>
                </tr>
                <? foreach ($arrServico as $servico): ?>
                    <tr>
                        <td style="padding: 0;margin: 0"><?= $servico['codigo'] ?></td>
                        <td style="padding: 0;margin: 0"><?= $servico['quantidade'] ?></td>
                        <td style="padding: 0;margin: 0"><?= $servico['nome'] ?></td>
                    </tr>
                <? endforeach; ?>
            </table>
            <p><b>Total:</b> <?= count($arrDocumentoPrincipais) ?></p>
        </td>
        <td style="text-align: center;width: 20%;">
            <p>Data de entrega: ___/___/_____</p><br>
            <p>____________________________</p>
            <p>Assinatura/Matricula dos Correios</p>
            <p>1ª via - Correios</p>
        </td>
    </tr>
</table>
<hr />
<div>
    <img src="modulos/correios/imagens/svg/logo_correios.svg?<?= Icone::VERSAO ?>" class="logo-correio" />
    <h2 class="titulo-correio">EMPRESA BRASILEIRA DE CORREIOS E TELÉGRAFOS</h2>
</div>
<table width="100%" style="border: 1px solid black; padding: 5px;border-collapse: collapse;">
    <tr >
        <td style="text-align: center;font-weight: bold;border: 1px solid black;padding: 5px;" colspan="2">PRÉ - LISTA DE POSTAGEM - PLP - SIGEP WEB</td>
    </tr>
    <tr>
        <td style="border-bottom: 1px solid black;padding: 5px;width: 80%;">
            <p><b>SIGEP WEB - Gerenciador de Postagens dos Correios</b></p>
            <p><b>Contrato:</b> <?= $strContrato ?></p>
            <p><b>Cliente:</b> <?= $strCliente ?></p>
            <p><b>Telefone de Contato:</b> <?= $strTelefoneContato ?></p>
        </td>
        <td style="text-align:center;border-bottom: 1px solid black;padding: 5px;width: 20%;">
            <p><b>Nº PLP:  <?= $objMdCorPlpDTO->getDblCodigoPlp() ?></b></p>
            <img src="data:image/png;base64,<?= base64_encode($imgCodigoBarras) ?>" />
        </td>
    </tr>
    <tr>
        <td>
            <table width="100%" id="tableServico">
                <tr>
                    <th>Cód. Serviço</th>
                    <th>Quantidade</th>
                    <th>Serviço</th>
                </tr>
<? foreach ($arrServico as $servico): ?>
                    <tr>
                        <td><?= $servico['codigo'] ?></td>
                        <td><?= $servico['quantidade'] ?></td>
                        <td><?= $servico['nome'] ?></td>
                    </tr>
<? endforeach; ?>
            </table>
            <p><b>Total:</b> <?= count($arrDocumentoPrincipais) ?></p>
        </td>
        <td style="text-align: center">
            <p>Data de entrega: ___/___/_____</p><br>
            <p>____________________________</p>
            <p>Assinatura/Matricula dos Correios</p>
            <p>2ª via - Cliente</p>
        </td>
    </tr>
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