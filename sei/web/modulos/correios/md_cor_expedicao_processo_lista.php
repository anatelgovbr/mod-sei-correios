<?php
try {
    require_once dirname(__FILE__) . '/../../SEI.php';

    session_start();
    SessaoSEI::getInstance()->validarLink();
    SessaoSEI::getInstance()->validarPermissao($_GET['acao']);
    PaginaSEI::getInstance()->setTipoPagina(PaginaSEI::$TIPO_PAGINA_SIMPLES);

    //Variaveis
    $strUrlAcaoForm = SessaoSEI::getInstance()->assinarLink('controlador.php?acao=' . $_GET['acao'] . '&acao_origem=' . $_GET['acao'] . '&id_procedimento=' . $_GET['id_procedimento']);
    $strUrlFechar = SessaoSEI::getInstance()->assinarLink('controlador.php?acao=arvore_visualizar&acao_origem=' . $_GET['acao'] . '&id_procedimento=' . $_GET['id_procedimento']);
    $selItensDocPrincipal = MdCorExpedicaoSolicitadaINT::montarSelectDocumentoPrincipalSol(null, '', $_POST['selDocumentoPrincipal']);
    $selItensServicoPostal = MdCorExpedicaoSolicitadaINT::montarSelectServicoPostal(null, '', $_POST['selServicoPostal']);

    switch ($_GET['acao']) {

        case 'md_cor_expedicao_processo_listar':
            $strTitulo = 'Listar Expedições pelos Correios';
            $arrComandos[] = '<button type="button" accesskey="P" id="btnPesquisar"  onclick="pesquisar();" class="infraButton"><span class="infraTeclaAtalho">P</span>esquisar</button>';
            $arrComandos[] = '<button type="button" accesskey="I" id="btnImprimir" onclick="infraImprimirTabela();" class="infraButton"><span class="infraTeclaAtalho">I</span>mprimir</button>';
            $arrComandos[] = '<button type="button" accesskey="C" id="btnFechar" onclick="fechar();" class="infraButton">Fe<span class="infraTeclaAtalho">c</span>har</button>';

            //============================== SALVA O ANDAMENTO DO OBJETO POSTADO NOS CORREIOS =======================================//
            //Toda a vez que entrar nessa tela, verifica se ouve atualização no objeto dos correios, se houver salva no banco do SEI //
            if (empty($_POST)) {
                $idProcedimento = $_GET['id_procedimento'];

                $objMdCorExpedicaoAndamentoRN = new MdCorExpedicaoAndamentoRN();
                $objMdCorExpedicaoAndamentoRN->salvarAndamento($idProcedimento);
            }
            //=======================================================================================================================//


            //===================================//
            //==============CONSULTA=============//
            //===================================//
            $objMdCorExpSolicitadaRN = new MdCorExpedicaoSolicitadaRN();
            $objDTO = new MdCorExpedicaoSolicitadaDTO();

            $numRegistros = isset($arrDados) ? count($arrDados) : 0;

            if (array_key_exists('selDocumentoPrincipal', $_POST) && trim($_POST['selDocumentoPrincipal']) != '') {
                $objDTO->setNumIdMdCorExpedicaoSolicitada($_POST['selDocumentoPrincipal']);
            }

            if (isset($_POST['selServicoPostal']) && trim($_POST['selServicoPostal']) != '') {
                $objDTO->setNumIdMdCorServicoPostal($_POST['selServicoPostal']);
            }

            if (isset($_POST['txtCodigoRastreio']) && trim($_POST['txtCodigoRastreio']) != '') {
                $objDTO->setStrCodigoRastreamento('%' . $_POST['txtCodigoRastreio'] . '%', InfraDTO::$OPER_LIKE);
            }


            //===================================//
            //============FIM CONSULTA===========//
            //===================================//
            PaginaSEI::getInstance()->prepararOrdenacao($objDTO, 'DocSerieFormatados', InfraDTO::$TIPO_ORDENACAO_ASC);
            PaginaSEI::getInstance()->prepararPaginacao($objDTO, 200);

            $arr = $objMdCorExpSolicitadaRN->listarAndamentoExpedicoesProcesso($objDTO);
            $arrDados = $arr[0];
            $objDTO = $arr[1];
            $numRegistros = count($arrDados);

            PaginaSEI::getInstance()->processarPaginacao($objDTO);

            if ($numRegistros > 0) {

                //Table
                $strResultado = '<table width="99%" id="tblExpedicaoCorreios" name="tblExpedicaoCorreios" class="infraTable">';
                $strResultado .= '<caption class="infraCaption">';
                $strResultado .= PaginaSEI::getInstance()->gerarCaptionTabela("Expedições", $numRegistros);
                $strResultado .= '</caption>';

                //THead
                $strResultado .= '<thead>';
                $strResultado .= '<tr>';
                $strResultado .= '<th class="infraTh" align="center" width="1%">' . PaginaSEI::getInstance()->getThCheck() . '</th>';
                $strResultado .= '<th class="infraTh" align="center" width="10%">' . PaginaSEI::getInstance()->getThOrdenacao($objDTO, 'Documento Principal', 'DocSerieFormatados', $arrDados) . '</th>';
                $strResultado .= '<th class="infraTh" align="center" width="3%">' . PaginaSEI::getInstance()->getThOrdenacao($objDTO, 'Anexos', 'Anexos', $arrDados) . '</th>';
                $strResultado .= '<th class="infraTh" align="center" width="30%">' . PaginaSEI::getInstance()->getThOrdenacao($objDTO, 'Serviço Postal', 'NomeServicoPostal', $arrDados) . '</th>';
                $strResultado .= '<th class="infraTh" align="center" width="auto">' . PaginaSEI::getInstance()->getThOrdenacao($objDTO, 'Solicitação', 'DataSolicitacao', $arrDados) . '</th>';
                $strResultado .= '<th class="infraTh" align="center" width="auto">' . PaginaSEI::getInstance()->getThOrdenacao($objDTO, 'Expedição', 'DataExpedicao', $arrDados) . '</th>';
                $strResultado .= '<th class="infraTh" align="center" width="auto">' . PaginaSEI::getInstance()->getThOrdenacao($objDTO, 'Rastreamento', 'CodigoRastreamento', $arrDados) . '</th>';
                $strResultado .= '<th class="infraTh" align="center" width="auto">' . PaginaSEI::getInstance()->getThOrdenacao($objDTO, 'Último Andamento', 'UltimoAndamento', $arrDados) . '</th>';
                $strResultado .= '<th class="infraTh" align="center" width="8%">Ações</th>';
                $strResultado .= '</tr>';
                $strResultado .= '</thead>';


                //TBody
                $strResultado .= '<tbody>';
                $strCssTr = '<tr class="infraTrClara" align="center">';
                for ($i = 0; $i < $numRegistros; $i++) {
                    $strCssTr == '<tr class="infraTrClara" align="center">' ? '<tr class="infraTrEscura" align="center">' : '<tr class="infraTrClara" align="center">';
                    $strId = $arrDados[$i]->getNumIdMdCorExpedicaoSolicitada();

                    $strResultado .= $strCssTr;

                    //Checkbox
                    $strResultado .= '<td valign="center">';
                    $strResultado .= ' ';
                    $strResultado .= PaginaSEI::getInstance()->getTrCheck($i, $strId, 'teste');
                    $strResultado .= '</td>';

                    //Documento Principal
                    $strResultado .= '<td>';
                    $strResultado .= $arrDados[$i]->getStrDocSerieFormatados();
                    $strResultado .= '</td>';

                    //Anexos
                    $strResultado .= '<td>';
                    $strResultado .= $arrDados[$i]->getStrAnexos();
                    $strResultado .= '</td>';

                    //Serviço Postal
                    $strResultado .= '<td>';
                    $strResultado .= $arrDados[$i]->getStrDescricaoServicoPostal();
                    $strResultado .= '</td>';

                    //Solicitação
                    $strResultado .= '<td>';
                    $strResultado .= $arrDados[$i]->getDthDataSolicitacao();
                    $strResultado .= '</td>';

                    //Expedição
                    $strResultado .= '<td>';
                    $strResultado .= $arrDados[$i]->getDthDataExpedicao();
                    $strResultado .= '</td>';

                    //Código de Rastreamento
                    $strResultado .= '<td>';
                    $strResultado .= $arrDados[$i]->getStrCodigoRastreamento();
                    $strResultado .= '</td>';

                    //Último Andamento
                    $strResultado .= '<td>';
                    $strResultado .= $arrDados[$i]->getStrUltimoAndamento();
                    $strResultado .= '</td>';

                    //Ações
                    //Detalhar Rastreamento
                    $strUrlDetalharRastreamento = SessaoSEI::getInstance()->assinarLink('controlador.php?acao=md_cor_expedicao_detalhar_rastreio&acao_origem=' . $_GET['acao'] . '&id_solicitacao_exp=' . $strId);

                    $strResultado .= '<td>';
                    $strResultado .= "<a onclick='acaoDetalharRastreamento(\"" . $strUrlDetalharRastreamento . "\")'>";
                    $strResultado .= '<img src="' . PaginaSEI::getInstance()->getDiretorioSvgGlobal() . '/menu.svg"';
                    $strResultado .= 'title="Detalhar Rastreamento do Objeto"';
                    $strResultado .= 'alt="Detalhar Rastreamento do Objeto"';
                    $strResultado .= 'class="infraImg"/></a>';

                    //Consultar Expedição
                    $strUrlConsultarExpedicao = SessaoSEI::getInstance()->assinarLink('controlador.php?acao=md_cor_expedicao_solicitada_consultar&acao_origem=' . $_GET['acao'] . '&id_md_cor_expedicao_solicitada=' . $strId . '&visualizar=true');

                    $strResultado .= "<a onclick='acaoConsultarExpedicao(\"" . $strUrlConsultarExpedicao . "\")'>";
                    $strResultado .= '<img src="' . PaginaSEI::getInstance()->getDiretorioSvgGlobal() . '/consultar.svg"';
                    $strResultado .= 'title="Consultar Expedições pelos Correios"';
                    $strResultado .= 'alt="Consultar Expedições pelos Correios"';
                    $strResultado .= 'class="infraImg"/></a>';
                    $strResultado .= '</td>';

                    $strResultado .= '</tr>';

                }
                $strResultado .= '<tbody>';

                $strResultado .= '</table>';

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
require_once("md_cor_expedicao_processo_listar_css.php");
PaginaSEI::getInstance()->fecharHead();
PaginaSEI::getInstance()->abrirBody($strTitulo, 'onload="inicializar();"'); ?>
    <form id="frmListarExpedicao" method="post" action="<?= $strUrlAcaoForm ?>">
        <?php PaginaSEI::getInstance()->montarBarraComandosSuperior($arrComandos); ?>
        <div class="row linha">
            <div class="col-sm-12 col-md-12 col-lg-12 col-xl-12">
                <div class="row">
                    <div class="col-sm-4 col-md-4 col-lg-4 col-xl-4">
                        <label class="infraLabelOpcional" for="selDocumentoPrincipal">
                            Documento Principal:
                        </label>
                        <select id="selDocumentoPrincipal" name="selDocumentoPrincipal" class="infraSelect form-control"
                                onchange="pesquisar()" tabindex="<?= PaginaSEI::getInstance()->getProxTabDados(); ?>">
                            <option value=""></option>
                            <?php echo $selItensDocPrincipal; ?>
                        </select>
                    </div>
                    <div class="col-sm-4 col-md-4 col-lg-4 col-xl-4">
                        <label class="infraLabelOpcional" for="selServicoPostal">
                            Serviço Postal:
                        </label>

                        <select id="selServicoPostal" name="selServicoPostal" class="infraSelect form-control"
                                onchange="pesquisar()" tabindex="<?= PaginaSEI::getInstance()->getProxTabDados(); ?>">
                            <option value=""></option>
                            <?php echo $selItensServicoPostal; ?>
                        </select>
                    </div>
                    <div class="col-sm-4 col-md-4 col-lg-4 col-xl-4">
                        <label class="infraLabelOpcional" for="txtCodigoRastreio">
                            Rastreamento:
                        </label>
                        <input type="text" name="txtCodigoRastreio" id="txtCodigoRastreio"
                               class="infraText form-control"
                               value="<?php echo isset($_POST['txtCodigoRastreio']) ? PaginaSEI::tratarHTML($_POST['txtCodigoRastreio']) : '' ?>"
                               onchange="pesquisar()" tabindex="<?= PaginaSEI::getInstance()->getProxTabDados(); ?>">
                    </div>
                </div>
                <div class="row">
                    <div class="col-sm-12 col-md-12 col-lg-12 col-xl-12">
                        <?php PaginaSEI::getInstance()->montarAreaTabela($strResultado, $numRegistros); ?>
                    </div>
                </div>
            </div>
        </div>
    </form>
<?php
require_once("md_cor_expedicao_processo_listar_js.php");
PaginaSEI::getInstance()->fecharBody();
PaginaSEI::getInstance()->fecharHtml();
