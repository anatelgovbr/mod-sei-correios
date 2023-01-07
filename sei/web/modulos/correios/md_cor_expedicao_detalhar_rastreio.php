<?php
try {
    require_once dirname(__FILE__) . '/../../SEI.php';

    session_start();
    SessaoSEI::getInstance()->validarLink();
    SessaoSEI::getInstance()->validarPermissao($_GET['acao']);
    PaginaSEI::getInstance()->setTipoPagina(PaginaSEI::$TIPO_PAGINA_SIMPLES);

    //Start Vars
    $objExpedSolicitacaoDTO = null;
    $objExpedSolicitacaoRN = new MdCorExpedicaoSolicitadaRN();
    $objExpedAndamentoRN = new MdCorExpedicaoAndamentoRN();

    switch ($_GET['acao']) {
        case 'md_cor_expedicao_detalhar_rastreio':
            $strTitulo = '  Detalhar Rastreamento do Objeto';
            $arrComandos[] = '<button type="button" accesskey="I" id="btnImprimir" onclick="window.print();" class="infraButton"><span class="infraTeclaAtalho">I</span>mprimir</button>';
            $arvore = $_GET['arvore'];

            if (!is_null($arvore)) {
                $arrComandos[] = '<button type="button" accesskey="C" id="btnFechar" onclick="infraFecharJanelaModal();" class="infraButton">Fe<span class="infraTeclaAtalho">c</span>har</button>';
            }

            if (array_key_exists('co_rastreio', $_GET)) {
                $coRastreio = $_GET['co_rastreio'];
                if ($coRastreio != '') {
                    $objExpedSolicitacaoDTO = $objExpedSolicitacaoRN->listarDadosRastreioCodigo($coRastreio);
                    if (!is_null($objExpedSolicitacaoDTO)) {
                        $idSol = $objExpedSolicitacaoDTO->getNumIdMdCorExpedicaoSolicitada();
                    }
                }
            }
            if (array_key_exists('id_solicitacao_exp', $_GET)) {
                $idSol = $_GET['id_solicitacao_exp'];

                if ($idSol != '') {
                    $objExpedSolicitacaoDTO = $objExpedSolicitacaoRN->listarDadosRastreioSolicitacao($idSol);
                }
            }

            if ($objExpedSolicitacaoDTO) {
                $mdCorExpedicaoSolicitadaDTO = new MdCorExpedicaoSolicitadaDTO();
                $mdCorExpedicaoSolicitadaDTO->retNumIdContatoDestinatario();
                $mdCorExpedicaoSolicitadaDTO->retNumIdMdCorExpedicaoSolicitada();
                $mdCorExpedicaoSolicitadaDTO->retStrSinNecessitaAr();
                $mdCorExpedicaoSolicitadaDTO->setDblIdDocumentoPrincipal($objExpedSolicitacaoDTO->getDblIdDocumentoPrincipal());
                $arrObjMdCorExpedicaoSolicitadaDTO = $objExpedSolicitacaoRN->listar($mdCorExpedicaoSolicitadaDTO);
                $objMdCorExpedicaoSolicitadaDTO = $arrObjMdCorExpedicaoSolicitadaDTO[0];

                $mdCorContatoRN = new MdCorContatoRN();
                $mdCorContatoDTO = new MdCorContatoDTO();
                $mdCorContatoDTO->retTodos();

                $mdCorContatoDTO->setNumIdContato($objMdCorExpedicaoSolicitadaDTO->getNumIdContatoDestinatario());
                $mdCorContatoDTO->setNumIdMdCorExpedicaoSolicitada($objMdCorExpedicaoSolicitadaDTO->getNumIdMdCorExpedicaoSolicitada());

                $arrObjMdCorContatoDTO = $mdCorContatoRN->listar($mdCorContatoDTO);
                $objMdCorContatoDTO = $arrObjMdCorContatoDTO[0];

                $id_destinatario = $objMdCorContatoDTO->getNumIdContato();
                $nome_destinatario = $objMdCorContatoDTO->getStrNome();
                $cargo_destinatario = $objMdCorContatoDTO->getStrExpressaoCargo();
                $tratamento_destinatario = $objMdCorContatoDTO->getStrExpressaoTratamentoCargo();
                $endereco_destinatario = $objMdCorContatoDTO->getStrEndereco();
                $complemento_destinatario = $objMdCorContatoDTO->getStrComplemento();
                $bairro_destinatario = $objMdCorContatoDTO->getStrBairro();
                $cep_destinatario = $objMdCorContatoDTO->getStrCep();
                $cidade_destinatario = $objMdCorContatoDTO->getStrNomeCidade();
                $uf_destinatario = $objMdCorContatoDTO->getStrSiglaUf();
                $necessita_ar = $objMdCorExpedicaoSolicitadaDTO->getStrSinNecessitaAr();

                $idContatoAssociado = $objMdCorContatoDTO->getNumIdContatoAssociado();

                $nome_destinatario_associado = '';
                if ($objMdCorContatoDTO->getStrStaNaturezaContatoAssociado() == ContatoRN::$TN_PESSOA_JURIDICA) {
                    $nome_destinatario_associado = $objMdCorContatoDTO->getStrNomeContatoAssociado();
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
PaginaSEI::getInstance()->montarTitle(':: ' . PaginaSEI::getInstance()->getStrNomeSistema() . ' - ' . $strTitulo . ' ::');
PaginaSEI::getInstance()->montarStyle();
PaginaSEI::getInstance()->abrirStyle();
PaginaSEI::getInstance()->fecharStyle();
PaginaSEI::getInstance()->montarJavaScript();
PaginaSEI::getInstance()->abrirJavaScript(); ?>

function inicializar() {
infraEfeitoTabelas();
}

<?php
PaginaSEI::getInstance()->fecharJavaScript();
require_once("md_cor_expedicao_detalhar_rastreio_css.php");
PaginaSEI::getInstance()->fecharHead();
PaginaSEI::getInstance()->abrirBody($strTitulo, 'onload="inicializar();"'); ?>

<form id="frmModalRastreamentoObj">
    <?php PaginaSEI::getInstance()->montarBarraComandosSuperior($arrComandos);
    if (!is_null($objExpedSolicitacaoDTO)) {
        $strUrlDocumento = SessaoSEI::getInstance()->assinarLink('controlador.php?acao=procedimento_trabalhar&id_documento=' . $objExpedSolicitacaoDTO->getDblIdDocumentoPrincipal());
        $docFormt = $objExpedSolicitacaoDTO->getStrNomeSerie(). ' ' . $objExpedSolicitacaoDTO->getStrNumeroDocumento() . ' <a class="protocoloNormal" style="font-size: 1.0em !important; font-size:1em" href="'.$strUrlDocumento.'" target="_blank">(' . $objExpedSolicitacaoDTO->getStrProtocoloFormatadoDocumento() . ')</a>';
        ?>
        <div class="row">
            <div class="col-sm-12 col-md-12 col-lg-6 col-xl-6">
                <label class="infraLabelOpcional" id="lblDocPrincipal">Documento Principal:</label>
                <span class="spanTxt" id="txtDocPrincipal"> <?php echo $docFormt ?> </span>
            </div>
        </div>
        <div class="row">
            <div class="col-sm-12 col-md-12 col-lg-6 col-xl-6">
                <label class="infraLabelOpcional" id="lblCodRastreamento">Código de Rastreamento:</label>
                <span class="spanTxt"
                      id="txtCodRastreamento"> <?php echo $objExpedSolicitacaoDTO->getStrCodigoRastreamento() ?> </span>
            </div>
        </div>
        <div class="row">
            <div class="col-sm-12 col-md-12 col-lg-6 col-xl-6">
                <label class="infraLabelOpcional" id="lblServicoPostal">Serviço Postal:</label>
                <span class="spanTxt" id="txtServicoPostal">
                    <?php echo $objExpedSolicitacaoDTO->getStrDescricaoServicoPostal() ?>
                </span>
                <?php if ($necessita_ar === 'S'): ?>
                    <strong>(Com Aviso de Recebimento)</strong>
                <?php endif; ?>
            </div>
        </div>
        <div class="row">
            <div class="col-12">
                <br />
                <fieldset id="fieldDestinatario" class="infraFieldset sizeFieldset form-control">
                    <legend class="infraLegend">&nbspDestinatário&nbsp</legend>
                    <?php if ($tratamento_destinatario != '') : ?>
                        <div class="row">
                            <div class="col-sm-12 col-md-12 col-lg-6 col-xl-6">
                                <label class="infraLabelOpcional">
                                    <?= $tratamento_destinatario; ?>
                                </label>
                            </div>
                        </div>
                    <?php endif; ?>
                    <div class="row">
                        <div class="col-sm-12 col-md-12 col-lg-6 col-xl-6">
                            <label class="infraLabelOpcional" style="text-transform:uppercase;">
                                <?php echo InfraString::transformarCaixaAlta($nome_destinatario); ?>
                            </label>
                        </div>
                    </div>
                    <?php if ($cargo_destinatario != '') : ?>
                        <div class="row">
                            <div class="col-sm-12 col-md-12 col-lg-6 col-xl-6">
                                <label class="infraLabelOpcional">
                                    <?= $cargo_destinatario; ?>
                                </label>
                            </div>
                        </div>
                    <?php endif; ?>
                    <div class="bloco" style="width: 100%">
                        <?php if ($nome_destinatario_associado != '') : ?>
                            <label class="infraLabelOpcional">
                                <?= $nome_destinatario_associado; ?>
                            </label>
                        <?php endif; ?>
                    </div>

                    <div class="bloco" style="width: 100%">
                        <label class="infraLabelOpcional">
                            <?php
                            echo $endereco_destinatario;
                            echo $complemento_destinatario ? ', ' . $complemento_destinatario : '';
                            echo ', ' . $bairro_destinatario;
                            ?>
                        </label>
                    </div>

                    <div class="bloco" style="width: 100%">
                        <label name=lblCep id=lblCep class="infraLabelOpcional">
                            <?php
                            echo 'CEP: ' . $cep_destinatario . ' - ' . $cidade_destinatario . '/' . $uf_destinatario;
                            ?>
                        </label>
                    </div>
                </fieldset>
            </div>
        </div>
        <?php
        $arrObjMdCorExpAndamentoDTO = $objExpedAndamentoRN->getDadosAndamentosParaRastreio($idSol);

        /*
         * Nescessário para consultar os stataus da md_cor_lista_status. EU#27389
         */
        $objMdCorListaStatusDTO = new MdCorListaStatusDTO();
        $objMdCorListaStatusDTO->retTodos(true);
        $objMdCorListaStatusRN = new MdCorListaStatusRN();


        if (!is_null($arrObjMdCorExpAndamentoDTO)) {
            ?>

            <div class="clear" style="height: 20px;"></div>

            <div class="bloco" style="width: 100%; text-align: center;">
                <?php
                    switch ($arrObjMdCorExpAndamentoDTO[0]->getStrStaRastreioModulo()) {
                        case 'P':
                            $nomeImg = "rastreamento_postagem.svg";
                            break;
                        case 'T':
                            $nomeImg = "rastreamento_em_transito.svg";
                            break;
                        case 'S':
                            $nomeImg = "rastreamento_sucesso.svg";
                            break;
                        case 'I':
                            $nomeImg = "rastreamento_cancelado.svg";
                            break;
                    }
                $caminhoImg = "modulos/correios/imagens/svg/" . $nomeImg;
                ?>
                <img class="imgRastreio" src="<?php echo $caminhoImg; ?>"/>
            </div>

            <div class="clear ultima_atualizacao lblCinza">Data da Última
                Atualização: <?= current($arrObjMdCorExpAndamentoDTO)->getDthDataUltimaAtualizacao(); ?> </div>

            <div class="clear" style="height: 40px;width: 100%"></div>

            <div class="bloco" style="width: 100%;">
                <div style="width: 20%">
                </div>

                <div style="width: 90%">
                    <?php
                    $string = '';

                    foreach ($arrObjMdCorExpAndamentoDTO as $objDTO) {
                        $qtdCaract = strlen($objDTO->getStrDescricao()) + strlen($objDTO->getStrDetalhe());


                        /*
                         * Recupera status da tabela md_cor_lista_status. EU#27389
                         */
                        $objMdCorListaStatusDTO->setNumStatus($objDTO->getNumStatus(), InfraDTO::$OPER_IGUAL);
                        $objMdCorListaStatusDTO->setStrTipo('' . $objDTO->getStrTipo() . '', InfraDTO::$OPER_IGUAL);
                        $objMdCorListaStatusDTO->setStrStaRastreioModulo('', InfraDTO::$OPER_DIFERENTE);
                        $objMdCorListaStatusDTO->setStrSinAtivo('S');
                        $arrObjMdCorListaStatusDTO = $objMdCorListaStatusRN->listar($objMdCorListaStatusDTO);
                        $descricaoObjetoStatosSRO = false;
                        $sinObjetoStatosSRO = false;

                        if (count($arrObjMdCorListaStatusDTO) > 0) {

                            //$descricaoObjetoStatosSRO = $arrObjMdCorListaStatusDTO[0]->getStrDescricaoObjeto();
                            $sinObjetoStatosSRO = $arrObjMdCorListaStatusDTO[0]->getStrSinAtivo();
                        }


                        $cssDiv = $qtdCaract > 135 && !strpos($objDTO->getStrDetalhe(), '<a') ? 'min-height: 80px;' : '';
                        $dtHrFormt = !is_null($objDTO->getDthDataHora()) ? explode(' ', $objDTO->getDthDataHora()) : null;
                        $cidadeUf = !is_null($objDTO->getStrCidade()) ? $objDTO->getStrCidade() : '';
                        $cidadeUf .= !is_null($objDTO->getStrUf()) ? ' / ' . $objDTO->getStrUf() : '';

                        if ($sinObjetoStatosSRO == 'S') {
                            ?>
                            <div class="divPontilhada">
                                <div class="divLeftCinza" style="<?php echo $cssDiv ?>">
                                    <label class="lblCinza"> <?php echo !is_null($dtHrFormt) ? $dtHrFormt[0] : ''; ?></label>
                                    <div class="clear"></div>
                                    <label class="lblCinza"> <?php echo !is_null($dtHrFormt) ? substr($dtHrFormt[1], 0, 5) : ''; ?></label>
                                    <div class="clear"></div>
                                    <label class="lblCinza"> <?php echo $cidadeUf ?></label>
                                </div>
                                <div style="width: 80%; margin-top:2px">
                                    <label style="font-weight: bolder"><?php echo $objDTO->getStrDescricao() ?></label>
                                    <!--                              <label style="font-weight: bolder">-->
                                    <?php //echo !empty($arrObjMdCorListaStatusDTO[0]->getStrDescricaoObjeto()) ? $arrObjMdCorListaStatusDTO[0]->getStrDescricaoObjeto() : $objDTO->getStrDescricao()  ?><!--</label>-->
                                    <br/>
                                    <label class="lblCinza"><?php echo !is_null($objDTO->getStrDetalhe()) ? $objDTO->getStrDetalhe() : '' ?></label>
                                </div>
                                <div class="clear"></div>
                            </div>
                            <div class="clear"></div>
                        <?php }
                        ?>
                    <?php }
                    ?>
                    <div class="divPontilhada"></div>
                </div>
                <br/>
                <div style="width: 20%">
                </div>

            </div>

        <?php } else { ?>

            <?php if (!is_null($objExpedSolicitacaoDTO->getStrCodigoRastreamento())) { ?>

                <div class="row">
                    <div class="bloco" style="width: 100%; text-align: center;">
                        <img class="imgRastreio"
                             src="<?php echo "modulos/correios/imagens/svg/rastreamento_aguardando_postagem.svg?".Icone::VERSAO; ?>"/>
                    </div>
                </div>
                <div class="row">
                    <div class="bloco" style="width: 100%; text-align: center;">
                        <span style="color: red; font-size: 12px;">O Objeto ainda não possui Rastreamento nos Correios. <br> Acesse novamente amanhã para conferir.</span>
                    </div>
                </div>

            <?php } ?>

        <?php }
    } ?>
</form>
<?php

PaginaSEI::getInstance()->fecharAreaDados();
PaginaSEI::getInstance()->fecharBody();
PaginaSEI::getInstance()->fecharHtml();

?>
