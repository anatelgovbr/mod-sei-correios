<?php
/**
 * EU9363 - Expedições Solicitadas pela Unidade pelos Correios
 *
 * @author André Luiz <andre.luiz@castgroup.com.br>
 * @since  02/06/2017
 */
try {
    require_once dirname(__FILE__) . '/../../SEI.php';

    session_start();
    SessaoSEI::getInstance()->validarLink();
    SessaoSEI::getInstance()->validarPermissao($_GET['acao']);
    PaginaSEI::getInstance()->setTipoPagina(PaginaSEI::$TIPO_PAGINA_COMPLETA);

    //URL's
    $strUrlAcaoForm = SessaoSEI::getInstance()->assinarLink('controlador.php?acao=' . $_GET['acao'] . '&acao_origem=' . $_GET['acao']);
    $strUrlFechar = SessaoSEI::getInstance()->assinarLink('controlador.php?acao=procedimento_controlar&acao_origem=' . $_GET['acao']);

    PaginaSEI::getInstance()->salvarCamposPost(array('txtNumeroProcesso', 'txtDocumentoPrincipal', 'txtDataSolicitacao',
        'txtDataExpedicao', 'txtCodigoRastreamento', 'selServicoPostal'));

    switch ($_GET['acao']) {

        case 'md_cor_expedicao_unidade_listar':
            $strTitulo = 'Expedições Solicitadas pela Unidade pelos Correios';
            $arrComandos[] = '<button type="button" accesskey="P" id="btnPesquisar" class="infraButton" onclick="pesquisar();"><span class="infraTeclaAtalho">P</span>esquisar</button>';
            $arrComandos[] = '<button type="button" accesskey="I" id="btnImprimir" class="infraButton" onclick="infraImprimirTabela();"><span class="infraTeclaAtalho">I</span>mprimir</button>';
            $arrComandos[] = '<button type="button" accesskey="C" id="btnFechar" class="infraButton" onclick="fechar();">Fe<span class="infraTeclaAtalho">c</span>har</button>';


            if (!empty($_POST)) {
                $txtNumeroProcesso = PaginaSEI::getInstance()->recuperarCampo('txtNumeroProcesso');
                $txtDocumentoPrincipal = PaginaSEI::getInstance()->recuperarCampo('txtDocumentoPrincipal');
                $txtDataSolicitacao = PaginaSEI::getInstance()->recuperarCampo('txtDataSolicitacao');
                $txtDataExpedicao = PaginaSEI::getInstance()->recuperarCampo('txtDataExpedicao');
                $txtCodigoRastreamento = PaginaSEI::getInstance()->recuperarCampo('txtCodigoRastreamento');
                $selServicoPostal = PaginaSEI::getInstance()->recuperarCampo('selServicoPostal');
            }

            $strItensSelServico = MdCorExpedicaoSolicitadaUnidadeINT::montarSelectServicoPostal(' ', '', $selServicoPostal);

            $objMdCorExpedicaoSolicitadaDTO = new  MdCorExpedicaoSolicitadaDTO();

            $objMdCorExpedicaoSolicitadaDTO->retNumIdMdCorExpedicaoSolicitada();
            $objMdCorExpedicaoSolicitadaDTO->retStrNomeSerie();
            $objMdCorExpedicaoSolicitadaDTO->retStrProtocoloFormatadoDocumento();
            $objMdCorExpedicaoSolicitadaDTO->retStrProtocoloFormatado();
            $objMdCorExpedicaoSolicitadaDTO->retStrNomeDestinatario();
            $objMdCorExpedicaoSolicitadaDTO->retDthDataSolicitacao();
            $objMdCorExpedicaoSolicitadaDTO->retDthDataExpedicao();
            $objMdCorExpedicaoSolicitadaDTO->retStrCodigoRastreamento();
            $objMdCorExpedicaoSolicitadaDTO->retStrUltimoAndamento();

            $objMdCorExpedicaoSolicitadaDTO->setDistinct(true);

            if (!InfraString::isBolVazia($txtNumeroProcesso)) {
                $objMdCorExpedicaoSolicitadaDTO->setStrProtocoloFormatado($txtNumeroProcesso);
            }

            if (!InfraString::isBolVazia($txtDocumentoPrincipal)) {
                $objMdCorExpedicaoSolicitadaDTO->setStrNomeSerie('%' . $txtDocumentoPrincipal . '%', InfraDTO::$OPER_LIKE);
            }

            if (!InfraString::isBolVazia($txtDataSolicitacao)) {
                $objMdCorExpedicaoSolicitadaDTO->adicionarCriterio(array('DataSolicitacao', 'DataSolicitacao'),
                    array(InfraDTO::$OPER_MAIOR_IGUAL, InfraDTO::$OPER_MENOR_IGUAL),
                    array($txtDataSolicitacao . ' 00:00:00', $txtDataSolicitacao . ' 23:59:59'),
                    InfraDTO::$OPER_LOGICO_AND);
            }

            if (!InfraString::isBolVazia($txtDataExpedicao)) {
                $objMdCorExpedicaoSolicitadaDTO->adicionarCriterio(array('DataExpedicao', 'DataExpedicao'),
                    array(InfraDTO::$OPER_MAIOR_IGUAL, InfraDTO::$OPER_MENOR_IGUAL),
                    array($txtDataExpedicao . ' 00:00:00', $txtDataExpedicao . ' 23:59:59'),
                    InfraDTO::$OPER_LOGICO_AND);
            }

            if (!InfraString::isBolVazia($txtCodigoRastreamento)) {
                $objMdCorExpedicaoSolicitadaDTO->setStrCodigoRastreamento($txtCodigoRastreamento);
            }

            if (!InfraString::isBolVazia($selServicoPostal)) {
                $objMdCorExpedicaoSolicitadaDTO->setNumIdMdCorServicoPostal($selServicoPostal);
            }

            $objMdCorExpedicaoSolicitadaDTO->setNumIdUnidade(SessaoSEI::getInstance()->getNumIdUnidadeAtual());

            PaginaSEI::getInstance()->prepararOrdenacao($objMdCorExpedicaoSolicitadaDTO, 'NomeSerie', InfraDTO::$TIPO_ORDENACAO_ASC);
            PaginaSEI::getInstance()->prepararPaginacao($objMdCorExpedicaoSolicitadaDTO, 200);

            $objMdCorExpedicaoSolicitadaRN = new MdCorExpedicaoSolicitadaRN();
            $arrObjMdCorExpedicaoSolicitadaDTO = $objMdCorExpedicaoSolicitadaRN->listarExpedicaoSolicitadaUnidade($objMdCorExpedicaoSolicitadaDTO);

            PaginaSEI::getInstance()->processarPaginacao($objMdCorExpedicaoSolicitadaDTO);
            $numRegistros = count($arrObjMdCorExpedicaoSolicitadaDTO);

            if ($numRegistros > 0) {

                //Table
                $strResultado = '<table width="99%" class="infraTable">';
                $strResultado .= '<caption class="infraCaption">';
                $strResultado .= PaginaSEI::getInstance()->gerarCaptionTabela("Expedições", $numRegistros);
                $strResultado .= '</caption>';

                //THead
                $strResultado .= '<tr>';
                $strResultado .= '<th class="infraTh" align="center" width="1%">' . PaginaSEI::getInstance()->getThCheck() . '</th>';
                $strResultado .= '<th class="infraTh" align="center">' . PaginaSEI::getInstance()->getThOrdenacao($objMdCorExpedicaoSolicitadaDTO, "Documento Principal", "NomeSerie", $arrObjMdCorExpedicaoSolicitadaDTO) . '</th>';
                $strResultado .= '<th class="infraTh" align="center">' . PaginaSEI::getInstance()->getThOrdenacao($objMdCorExpedicaoSolicitadaDTO, "Processo", "ProtocoloFormatado", $arrObjMdCorExpedicaoSolicitadaDTO) . '</th>';
                $strResultado .= '<th class="infraTh" align="center">' . PaginaSEI::getInstance()->getThOrdenacao($objMdCorExpedicaoSolicitadaDTO, "Destinatário", "NomeDestinatario", $arrObjMdCorExpedicaoSolicitadaDTO) . '</th>';
                $strResultado .= '<th class="infraTh" align="center">' . PaginaSEI::getInstance()->getThOrdenacao($objMdCorExpedicaoSolicitadaDTO, "Solicitação", "DataSolicitacao", $arrObjMdCorExpedicaoSolicitadaDTO) . '</th>';
                $strResultado .= '<th class="infraTh" align="center">' . PaginaSEI::getInstance()->getThOrdenacao($objMdCorExpedicaoSolicitadaDTO, "Expedição", "DataExpedicao", $arrObjMdCorExpedicaoSolicitadaDTO) . '</th>';
                $strResultado .= '<th class="infraTh" align="center">' . PaginaSEI::getInstance()->getThOrdenacao($objMdCorExpedicaoSolicitadaDTO, "Situação", "NomeDestinatario", $arrObjMdCorExpedicaoSolicitadaDTO) . '</th>';
                $strResultado .= '<th class="infraTh" align="center">' . PaginaSEI::getInstance()->getThOrdenacao($objMdCorExpedicaoSolicitadaDTO, "Código", "CodigoRastreamento", $arrObjMdCorExpedicaoSolicitadaDTO) . '</th>';
                $strResultado .= '<th class="infraTh" align="center" width="5%">' . PaginaSEI::getInstance()->getThOrdenacao($objMdCorExpedicaoSolicitadaDTO, "Andamento", "UltimoAndamento", $arrObjMdCorExpedicaoSolicitadaDTO) . '</th>';
                $strResultado .= '<th class="infraTh" align="center" width="5%">Ações</th>';
                $strResultado .= '</tr>';

                $strCssTr = '<tr class="infraTrClara" align="center">';
                for ($i = 0; $i < $numRegistros; $i++) {
                    $strCssTr == '<tr class="infraTrClara" align="center">' ? '<tr class="infraTrEscura" align="center">' : '<tr class="infraTrClara" align="center">';

                    //Dados
                    $strId = $arrObjMdCorExpedicaoSolicitadaDTO[$i]->getNumIdMdCorExpedicaoSolicitada();
                    $strDocumentoPrincipal = $arrObjMdCorExpedicaoSolicitadaDTO[$i]->getStrNomeSerie();
                    $strNumeroDocumento = $arrObjMdCorExpedicaoSolicitadaDTO[$i]->getStrProtocoloFormatadoDocumento();
                    $strNumeroProtocoloFormatado = $arrObjMdCorExpedicaoSolicitadaDTO[$i]->getStrProtocoloFormatado();
                    $strStrNoDestinatario = $arrObjMdCorExpedicaoSolicitadaDTO[$i]->getStrNomeDestinatario();

                    $dataExpedicao = substr($arrObjMdCorExpedicaoSolicitadaDTO[$i]->getDthDataExpedicao(), 0, 10);

                    $strStrSituacao = is_null($dataExpedicao) ? 'Solicitada' : 'Expedida';
                    $strStrCodigoRastreio = $arrObjMdCorExpedicaoSolicitadaDTO[$i]->getStrCodigoRastreamento();
                    $strDsUltimoAndamento = $arrObjMdCorExpedicaoSolicitadaDTO[$i]->getStrUltimoAndamento();

                    $dataSolicitada = substr($arrObjMdCorExpedicaoSolicitadaDTO[$i]->getDthDataSolicitacao(), 0, 10);

                    $strResultado .= $strCssTr;

                    //Checkbox
                    $strResultado .= '<td>' . PaginaSEI::getInstance()->getTrCheck($i, $strId, $strDocumentoPrincipal) . '</td>';

                    //Documento Principal
                    $strResultado .= '<td>' . PaginaSEI::tratarHTML($strDocumentoPrincipal) . ' (' . $strNumeroDocumento . ')' . '</td>';

                    //Processo
                    $strResultado .= '<td>' . $strNumeroProtocoloFormatado . '</td>';

                    //Destinatário
                    $strResultado .= '<td>' . PaginaSEI::tratarHTML($strStrNoDestinatario) . '</td>';

                    //Data Solicitação
                    $strResultado .= '<td>' . PaginaSEI::tratarHTML($dataSolicitada) . '</td>';

                    //Data Expedição
                    $strResultado .= '<td>' . PaginaSEI::tratarHTML($dataExpedicao) . '</td>';

                    //Situação
                    $strResultado .= '<td>' . PaginaSEI::tratarHTML($strStrSituacao) . '</td>';

                    //Código de Rastreamento
                    $strResultado .= '<td>' . PaginaSEI::tratarHTML($strStrCodigoRastreio) . '</td>';

                    //Ultimo Andamento
                    $strResultado .= '<td>' . PaginaSEI::tratarHTML($strDsUltimoAndamento) . '</td>';

                    //Ações
                    //Detalhar Rastreamento
                    $strUrlDetalharRastreamento = SessaoSEI::getInstance()->assinarLink('controlador.php?acao=md_cor_expedicao_detalhar_rastreio&acao_origem=' . $_GET['acao'] . '&id_solicitacao_exp=' . $strId);;

                    $strResultado .= '<td>';
                    $strResultado .= "<a onclick='acaoDetalharRastreamento(\"" . $strUrlDetalharRastreamento . "\")'>";
                    $strResultado .= '<img src="' . PaginaSEI::getInstance()->getDiretorioSvgGlobal() . '/menu.svg"';
                    $strResultado .= 'title="Detalhar Rastreamento do Objeto"';
                    $strResultado .= 'alt="Detalhar Rastreamento do Objeto"';
                    $strResultado .= 'class="infraImg"/></a>';

                    //Consultar Expedição
                    $strUrlConsultarExpedicao = SessaoSEI::getInstance()->assinarLink('controlador.php?acao=md_cor_expedicao_solicitada_consultar&acao_origem=' . $_GET['acao'] . '&id_md_cor_expedicao_solicitada=' . $strId);

                    $strResultado .= "<a onclick='acaoConsultarExpedicao(\"" . $strUrlConsultarExpedicao . "\")'>";
                    $strResultado .= '<img src="' . PaginaSEI::getInstance()->getDiretorioSvgGlobal() . '/consultar.svg"';
                    $strResultado .= 'title="Consultar Expedições pelos Correios"';
                    $strResultado .= 'alt="Consultar Expedições pelos Correios"';
                    $strResultado .= 'class="infraImg"/></a>';
                    $strResultado .= '</td>';

                    $strResultado .= '</tr>';
                }
                $strResultado .= '</table>';

                //============================== SALVA O ANDAMENTO DO OBJETO POSTADO NOS CORREIOS =======================================//
                //Toda a vez que entrar nessa tela, verifica se ouve atualização no objeto dos correios, se houver salva no banco do SEI //
                if (empty($_POST)) {

                    ini_set('max_execution_time', '0');
                    ini_set('memory_limit', '1024M');

                    $objMdCorExpedicaoAndamentoRN = new MdCorExpedicaoAndamentoRN();
                    $objMdCorExpedicaoAndamentoRN->salvarAndamento();
                }
                //=======================================================================================================================//
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
PaginaSEI::getInstance()->abrirJavaScript();
PaginaSEI::getInstance()->fecharJavaScript();
require_once('md_cor_expedicao_unidade_lista_css.php');
PaginaSEI::getInstance()->fecharHead();
PaginaSEI::getInstance()->abrirBody($strTitulo, 'onload="inicializar();"'); ?>
    <form id="frmExpedicaoSolicitadaUnidade" method="post" action="<?= $strUrlAcaoForm ?>">
        <?php PaginaSEI::getInstance()->montarBarraComandosSuperior($arrComandos); ?>

        <div class="row linha">
            <div class="col-sm-12 col-md-12 col-lg-12 col-xl-12">
                <div class="row">
                    <div class="col-sm-4 col-md-4 col-lg-4 col-xl-4">
                        <label class="infraLabelOpcional" for="txtNumeroProcesso">
                            Número do Processo:
                        </label>
                        <input type="text" id="txtNumeroProcesso" name="txtNumeroProcesso"
                               class="infraText form-control"
                               value="<?= PaginaSEI::tratarHTML($txtNumeroProcesso) ?>"
                               maxlength="20"
                               onkeypress="return infraMascaraTexto(this,event,20);"
                               tabindex="<?= PaginaSEI::getInstance()->getProxTabDados(); ?>"/>
                    </div>
                    <div class="col-sm-4 col-md-4 col-lg-4 col-xl-4">
                        <label class="infraLabelOpcional" for="txtDocumentoPrincipal">
                            Documento Principal:
                        </label>
                        <input type="text" id="txtDocumentoPrincipal" name="txtDocumentoPrincipal"
                               class="infraText form-control"
                               value="<?= PaginaSEI::tratarHTML($txtDocumentoPrincipal) ?>"
                               maxlength="150"
                               onkeypress="return infraMascaraTexto(this,event,150);"
                               tabindex="<?= PaginaSEI::getInstance()->getProxTabDados(); ?>"/>
                    </div>
                    <div class="col-sm-4 col-md-4 col-lg-4 col-xl-4">
                        <label class="infraLabelOpcional" for="txtDataSolicitacao">
                            Data da Solicitação:
                        </label>
                        <div class="input-group mb-3">
                            <input type="text" name="txtDataSolicitacao" id="txtDataSolicitacao"
                                   class="infraText form-control"
                                   onkeypress="return infraMascaraData(this, event);" maxlength="10"
                                   tabindex="<?= PaginaSEI::getInstance()->getProxTabDados(); ?>"
                                   value="<?= PaginaSEI::tratarHTML($txtDataSolicitacao) ?>"/>

                            <img src="<?= PaginaSEI::getInstance()->getDiretorioSvgGlobal() ?>/calendario.svg"
                                 id="imgDataSolicitacao"
                                 title="Selecionar  Data Solicitação"
                                 alt="Selecionar  Data Solicitação" class="infraImg"
                                 onclick="infraCalendario('txtDataSolicitacao',this,false,'<?= InfraData::getStrDataAtual() ?>');"/>
                        </div>
                    </div>
                    <div class="col-sm-4 col-md-4 col-lg-4 col-xl-4">
                        <label class="infraLabelOpcional" for="txtDataSolicitacao">
                            Data da Expedição:
                        </label>
                        <div class="input-group mb-3">
                            <input type="text" id="txtDataExpedicao" name="txtDataExpedicao"
                                   class="infraText form-control"
                                   value="<?= PaginaSEI::tratarHTML($txtDataExpedicao) ?>"
                                   onkeypress="return infraMascaraData(this, event);" maxlength="10"
                                   tabindex="<?= PaginaSEI::getInstance()->getProxTabDados(); ?>"/>

                            <img src="<?= PaginaSEI::getInstance()->getDiretorioSvgGlobal() ?>/calendario.svg"
                                 id="imgDataFim"
                                 title="Selecionar Data da Expedição"
                                 alt="Selecionar Data da Expedição" class="infraImg"
                                 onclick="infraCalendario('txtDataExpedicao',this,false,'<?= InfraData::getStrDataAtual() ?>');"/>
                        </div>
                    </div>
                    <div class="col-sm-4 col-md-4 col-lg-4 col-xl-4">
                        <label class="infraLabelOpcional" for="txtCodigoRastreamento">
                            Rastreamento:
                        </label>
                        <input type="text" name="txtCodigoRastreamento" id="txtCodigoRastreamento"
                               class="infraText form-control"
                               value="<?= PaginaSEI::tratarHTML($txtCodigoRastreamento) ?>"
                               maxlength="13"
                               onkeypress="return infraMascaraTexto(this,event,13);"
                               tabindex="<?= PaginaSEI::getInstance()->getProxTabDados(); ?>">
                    </div>
                    <div class="col-sm-4 col-md-4 col-lg-4 col-xl-4">
                        <label class="infraLabelOpcional" for="selServicoPostal">
                            Serviço Postal:
                        </label>

                        <select id="selServicoPostal" name="selServicoPostal" class="infraSelect form-select"
                                tabindex="<?= PaginaSEI::getInstance()->getProxTabDados(); ?>"
                                onchange="pesquisar();">
                            <?= $strItensSelServico ?>
                        </select>
                    </div>
                </div>
            </div>
        </div>
        <div class="row">
            <div class="col-sm-12 col-md-12 col-lg-12 col-xl-12">
                <?php PaginaSEI::getInstance()->montarAreaTabela($strResultado, $numRegistros); ?>
            </div>
        </div>
    </form>
<?php
require_once('md_cor_expedicao_unidade_lista_js.php');
PaginaSEI::getInstance()->fecharBody();
PaginaSEI::getInstance()->fecharHtml();