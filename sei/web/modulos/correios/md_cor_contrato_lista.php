<?
/**
 * TRIBUNAL REGIONAL FEDERAL DA 4ª REGIÃO
 *
 * 22/12/2016 - criado por Wilton Júnior
 *
 * Versão do Gerador de Código: 1.39.0
 */
try {
    require_once dirname(__FILE__) . '/../../SEI.php';

    session_start();

    //////////////////////////////////////////////////////////////////////////////
    //InfraDebug::getInstance()->setBolLigado(false);
    //InfraDebug::getInstance()->setBolDebugInfra(true);
    //InfraDebug::getInstance()->limpar();
    //////////////////////////////////////////////////////////////////////////////

    SessaoSEI::getInstance()->validarLink();

    PaginaSEI::getInstance()->prepararSelecao('md_cor_contrato_selecionar');

    SessaoSEI::getInstance()->validarPermissao($_GET['acao']);

    switch ($_GET['acao']) {
        case 'md_cor_contrato_excluir':
            try {
                $arrStrIds = PaginaSEI::getInstance()->getArrStrItensSelecionados();
                $arrObjMdCorContratoDTO = array();
                for ($i = 0; $i < count($arrStrIds); $i++) {
                    $objMdCorContratoDTO = new MdCorContratoDTO();
                    $objMdCorContratoDTO->setNumIdMdCorContrato($arrStrIds[$i]);
                    $arrObjMdCorContratoDTO[] = $objMdCorContratoDTO;
                }
                $objMdCorContratoRN = new MdCorContratoRN();
                $objMdCorContratoRN->excluir($arrObjMdCorContratoDTO);
                PaginaSEI::getInstance()->adicionarMensagem('Operação realizada com sucesso.', InfraPagina::$TIPO_MSG_AVISO);
            } catch (Exception $e) {
                PaginaSEI::getInstance()->processarExcecao($e);
            }
            header('Location: ' . SessaoSEI::getInstance()->assinarLink('controlador.php?acao=' . $_GET['acao_origem'] . '&acao_origem=' . $_GET['acao']));
            die;


        case 'md_cor_contrato_desativar':
            try {
                $arrStrIds = PaginaSEI::getInstance()->getArrStrItensSelecionados();
                $arrObjMdCorContratoDTO = array();
                for ($i = 0; $i < count($arrStrIds); $i++) {
                    $objMdCorContratoDTO = new MdCorContratoDTO();
                    $objMdCorContratoDTO->setNumIdMdCorContrato($arrStrIds[$i]);
                    $objMdCorContratoDTO->retTodos();
                    $arrObjMdCorContratoDTO[] = $objMdCorContratoDTO;
                }
                $objMdCorContratoRN = new MdCorContratoRN();
                $objMdCorContratoRN->desativar($arrObjMdCorContratoDTO);
                PaginaSEI::getInstance()->adicionarMensagem('Operação realizada com sucesso.', InfraPagina::$TIPO_MSG_AVISO);
            } catch (Exception $e) {
                PaginaSEI::getInstance()->processarExcecao($e);
            }
            header('Location: ' . SessaoSEI::getInstance()->assinarLink('controlador.php?acao=' . $_GET['acao_origem'] . '&acao_origem=' . $_GET['acao']));
            die;

        case 'md_cor_contrato_reativar':
            $strTitulo = 'Reativar contratos';
            if ($_GET['acao_confirmada'] == 'sim') {
                try {
                    $arrStrIds = PaginaSEI::getInstance()->getArrStrItensSelecionados();
                    $arrObjMdCorContratoDTO = array();
                    for ($i = 0; $i < count($arrStrIds); $i++) {
                        $objMdCorContratoDTO = new MdCorContratoDTO();
                        $objMdCorContratoDTO->setNumIdMdCorContrato($arrStrIds[$i]);
                        $objMdCorContratoDTO->retTodos();
                        $arrObjMdCorContratoDTO[] = $objMdCorContratoDTO;
                    }
                    $objMdCorContratoRN = new MdCorContratoRN();
                    $objMdCorContratoRN->reativar($arrObjMdCorContratoDTO);
                    PaginaSEI::getInstance()->adicionarMensagem('Operação realizada com sucesso.', InfraPagina::$TIPO_MSG_AVISO);
                } catch (Exception $e) {
                    PaginaSEI::getInstance()->processarExcecao($e);
                }
                header('Location: ' . SessaoSEI::getInstance()->assinarLink('controlador.php?acao=' . $_GET['acao_origem'] . '&acao_origem=' . $_GET['acao']));
                die;
            }
            break;


        case 'md_cor_contrato_selecionar':
            $strTitulo = PaginaSEI::getInstance()->getTituloSelecao('Selecionar contrato', 'Selecionar contratos');

            if ($_GET['acao_origem'] == 'md_cor_contrato_cadastrar') {
                if (isset($_GET['id_md_cor_contrato'])) {
                    PaginaSEI::getInstance()->adicionarSelecionado($_GET['id_md_cor_contrato']);
                }
            }
            break;

        case 'md_cor_contrato_listar':
            $strTitulo = 'Contratos e Serviços Postais';
            break;

        default:
            throw new InfraException("Ação '" . $_GET['acao'] . "' não reconhecida.");
    }

    $arrComandos = array();
    if ($_GET['acao'] == 'md_cor_contrato_selecionar') {
        $arrComandos[] = '<button type="button" accesskey="T" id="btnTransportarSelecao" value="Transportar" onclick="infraTransportarSelecao();" class="infraButton"><span class="infraTeclaAtalho">T</span>ransportar</button>';
    }

    if ($_GET['acao'] == 'md_cor_contrato_listar' || $_GET['acao'] == 'md_cor_contrato_selecionar') {
        $strLinkPesquisar = PaginaSEI::getInstance()->formatarXHTML(SessaoSEI::getInstance()->assinarLink('controlador.php?acao=' . $_GET['acao'] . '&acao_origem=' . $_GET['acao'] . '&acao_retorno=md_cor_contrato_listar'));
        $arrComandos[] = '<button type="button" accesskey="p" id="btnPesquisar" value="Pesquisar" onclick="pesquisar();" class="infraButton"><span class="infraTeclaAtalho">P</span>esquisar</button>';
        $bolAcaoCadastrar = SessaoSEI::getInstance()->verificarPermissao('md_cor_contrato_cadastrar');
        if ($bolAcaoCadastrar) {
            $arrComandos[] = '<button type="button" accesskey="N" id="btnNovo" value="Novo" onclick="location.href=\'' . SessaoSEI::getInstance()->assinarLink('controlador.php?acao=md_cor_contrato_cadastrar&acao_origem=' . $_GET['acao'] . '&acao_retorno=' . $_GET['acao']) . '\'" class="infraButton"><span class="infraTeclaAtalho">N</span>ovo</button>';
        }
    }

    $objMdCorContratoDTO = new MdCorContratoDTO();
    $objMdCorContratoDTO->retNumIdMdCorContrato();
    $objMdCorContratoDTO->retStrNumeroContrato();
    $objMdCorContratoDTO->retStrNumeroContratoCorreio();
    $objMdCorContratoDTO->retStrNumeroCartaoPostagem();
    $objMdCorContratoDTO->retNumNumeroCnpj();
    $objMdCorContratoDTO->retDblIdProcedimento();
    $objMdCorContratoDTO->retStrSinAtivo();

    if (isset($_POST['txtNumeroContrato']) && !empty($_POST['txtNumeroContrato'])) {
        $objMdCorContratoDTO->setStrNumeroContrato('%' . $_POST['txtNumeroContrato'] . '%', InfraDTO::$OPER_LIKE);
    }

    if (isset($_POST['txtNumeroContratoCorreio']) && !empty($_POST['txtNumeroContratoCorreio'])) {
        $objMdCorContratoDTO->setStrNumeroContratoCorreio('%' . $_POST['txtNumeroContratoCorreio'] . '%', InfraDTO::$OPER_LIKE);
    }


    if ($_GET['acao'] == 'md_cor_contrato_reativar') {
        //Lista somente inativos
        $objMdCorContratoDTO->setBolExclusaoLogica(false);
        $objMdCorContratoDTO->setStrSinAtivo('N');
    }

    $objMdCorContratoDTO->setBolExclusaoLogica(false);
    PaginaSEI::getInstance()->prepararOrdenacao($objMdCorContratoDTO, 'IdMdCorContrato', InfraDTO::$TIPO_ORDENACAO_ASC);
    PaginaSEI::getInstance()->prepararPaginacao($objMdCorContratoDTO, 200);

    $objMdCorContratoRN = new MdCorContratoRN();
    $arrObjMdCorContratoDTO = $objMdCorContratoRN->listar($objMdCorContratoDTO);

    PaginaSEI::getInstance()->processarPaginacao($objMdCorContratoDTO);
    $numRegistros = count($arrObjMdCorContratoDTO);

    if ($numRegistros > 0) {

        $bolCheck = false;

        if ($_GET['acao'] == 'md_cor_contrato_selecionar') {
            $bolAcaoReativar = false;
            $bolAcaoConsultar = SessaoSEI::getInstance()->verificarPermissao('md_cor_contrato_consultar');
            $bolAcaoAlterar = SessaoSEI::getInstance()->verificarPermissao('md_cor_contrato_alterar');
            $bolAcaoImprimir = false;
            $bolAcaoExcluir = false;
            $bolAcaoDesativar = false;
            $bolCheck = true;
        } else if ($_GET['acao'] == 'md_cor_contrato_reativar') {
            $bolAcaoReativar = SessaoSEI::getInstance()->verificarPermissao('md_cor_contrato_reativar');
            $bolAcaoConsultar = SessaoSEI::getInstance()->verificarPermissao('md_cor_contrato_consultar');
            $bolAcaoAlterar = false;
            $bolAcaoImprimir = true;
            $bolAcaoExcluir = SessaoSEI::getInstance()->verificarPermissao('md_cor_contrato_excluir');
            $bolAcaoDesativar = false;
        } else {
            $bolAcaoReativar = true;
            $bolAcaoConsultar = SessaoSEI::getInstance()->verificarPermissao('md_cor_contrato_consultar');
            $bolAcaoAlterar = SessaoSEI::getInstance()->verificarPermissao('md_cor_contrato_alterar');
            $bolAcaoImprimir = true;
            $bolAcaoExcluir = SessaoSEI::getInstance()->verificarPermissao('md_cor_contrato_excluir');
            $bolAcaoDesativar = SessaoSEI::getInstance()->verificarPermissao('md_cor_contrato_desativar');
        }


        if ($bolAcaoDesativar) {
            $bolCheck = true;
            $strLinkDesativar = SessaoSEI::getInstance()->assinarLink('controlador.php?acao=md_cor_contrato_desativar&acao_origem=' . $_GET['acao']);
        }

        if ($bolAcaoReativar) {
            $bolCheck = true;
            $strLinkReativar = SessaoSEI::getInstance()->assinarLink('controlador.php?acao=md_cor_contrato_reativar&acao_origem=' . $_GET['acao'] . '&acao_confirmada=sim');
        }

        if ($bolAcaoImprimir) {
            $bolCheck = true;
            $arrComandos[] = '<button type="button" accesskey="I" id="btnImprimir" value="Imprimir" onclick="infraImprimirTabela();" class="infraButton"><span class="infraTeclaAtalho">I</span>mprimir</button>';
        }

        if ($bolAcaoExcluir) {
            $bolCheck = true;
            $strLinkExcluir = SessaoSEI::getInstance()->assinarLink('controlador.php?acao=md_cor_contrato_excluir&acao_origem=' . $_GET['acao']);
        }

        $strResultado = '';

        if ($_GET['acao'] != 'md_cor_contrato_reativar') {
            $strSumarioTabela = 'Tabela de Contratos e Serviços Postais';
            $strCaptionTabela = 'Contratos e Serviços Postais';
        } else {
            $strSumarioTabela = 'Tabela de Contratos e Serviços Postais Inativos';
            $strCaptionTabela = 'Contratos e Serviços Postais';
        }

        $strResultado .= '<table class="infraTable" summary="' . $strSumarioTabela . '">' . "\n";
        $strResultado .= '<caption class="infraCaption">' . PaginaSEI::getInstance()->gerarCaptionTabela($strCaptionTabela, $numRegistros) . '</caption>';
        $strResultado .= '<tr>';
        if ($bolCheck) {
            $strResultado .= '<th class="infraTh" width="1%">' . PaginaSEI::getInstance()->getThCheck() . '</th>' . "\n";
        }
        $strResultado .= '<th class="infraTh text-left" width="250px">' . PaginaSEI::getInstance()->getThOrdenacao($objMdCorContratoDTO, 'Número do Contrato no Órgão', 'NumeroContrato', $arrObjMdCorContratoDTO) . '</th>' . "\n";
        $strResultado .= '<th class="infraTh text-center">' . PaginaSEI::getInstance()->getThOrdenacao($objMdCorContratoDTO, 'Número do Contrato nos Correios', 'NumeroContratoCorreio', $arrObjMdCorContratoDTO) . '</th>' . "\n";
        $strResultado .= '<th class="infraTh" width="200px">Ações</th>' . "\n";
        $strResultado .= '</tr>' . "\n";
        $strCssTr = '';
        for ($i = 0; $i < $numRegistros; $i++) {

            if ($arrObjMdCorContratoDTO[$i]->getStrSinAtivo() == 'S') {
                $strCssTr = ($strCssTr == '<tr class="infraTrClara">') ? '<tr class="infraTrEscura">' : '<tr class="infraTrClara">';
            } else {
                $strCssTr = '<tr class="infraTrVermelha">';
            }
            $strResultado .= $strCssTr;

            if ($bolCheck) {
                $strResultado .= '<td valign="top">' . PaginaSEI::getInstance()->getTrCheck($i, $arrObjMdCorContratoDTO[$i]->getNumIdMdCorContrato(), $arrObjMdCorContratoDTO[$i]->getNumIdMdCorContrato()) . '</td>';
            }
            $strResultado .= '<td>' . PaginaSEI::tratarHTML($arrObjMdCorContratoDTO[$i]->getStrNumeroContrato()) . '</td>';
            $strResultado .= '<td class="text-center">' . PaginaSEI::tratarHTML($arrObjMdCorContratoDTO[$i]->getStrNumeroContratoCorreio()) . '</td>';
            $strResultado .= '<td align="center">';

            $strResultado .= PaginaSEI::getInstance()->getAcaoTransportarItem($i, $arrObjMdCorContratoDTO[$i]->getNumIdMdCorContrato());

            $objMdCorObjetoDTO = new MdCorObjetoDTO();
            $objMdCorObjetoDTO->retTodos(false);
            $objMdCorObjetoDTO->setNumIdMdCorContrato($arrObjMdCorContratoDTO[$i]->getNumIdMdCorContrato());

            $objMdCorObjetoRN = new MdCorObjetoRN();
            $objMdCorObjetoDTO = $objMdCorObjetoRN->contar($objMdCorObjetoDTO);

            $objMdCorServicoPostalDTO = new MdCorServicoPostalDTO();
            $objMdCorServicoPostalRN = new MdCorServicoPostalRN();

            $objMdCorServicoPostalDTO->setNumIdMdCorContrato($arrObjMdCorContratoDTO[$i]->getNumIdMdCorContrato());
            $objMdCorServicoPostalDTO->retTodos(false);
            $numServicosPostais = $objMdCorServicoPostalRN->contar($objMdCorServicoPostalDTO);

            if ($numServicosPostais > 0) {
                $strResultado .= '<a href="' . SessaoSEI::getInstance()->assinarLink('controlador.php?acao=md_cor_servicos_postais_contrato_alterar&acao_origem=' . $_GET['acao'] . '&acao_retorno=' . $_GET['acao'] . '&id_md_cor_contrato=' . $arrObjMdCorContratoDTO[$i]->getNumIdMdCorContrato()) . '" tabindex="' . PaginaSEI::getInstance()->getProxTabTabela() . '"><img src="modulos/correios/imagens/svg/com_servicos_postais.svg?'.Icone::VERSAO.'" style="width: 24px; height: 24px" title="Serviços Postais" alt="Serviços Postais" class="infraImgModulo" /></a>&nbsp;';
            } else {
                $strResultado .= '<a href="' . SessaoSEI::getInstance()->assinarLink('controlador.php?acao=md_cor_servicos_postais_contrato_alterar&acao_origem=' . $_GET['acao'] . '&acao_retorno=' . $_GET['acao'] . '&id_md_cor_contrato=' . $arrObjMdCorContratoDTO[$i]->getNumIdMdCorContrato()) . '" tabindex="' . PaginaSEI::getInstance()->getProxTabTabela() . '"><img src="modulos/correios/imagens/svg/sem_servicos_postais.svg?'.Icone::VERSAO.'" style="width: 24px; height: 24px" title="Serviços Postais (Não configurado)" alt="Serviços Postais (Não configurado)" class="infraImgModulo" /></a>&nbsp;';
            }

            //if ($objMdCorObjetoDTO > 0) {
                $strResultado .= '<a href="' . SessaoSEI::getInstance()->assinarLink('controlador.php?acao=md_cor_objeto_listar&acao_origem=' . $_GET['acao'] . '&acao_retorno=' . $_GET['acao'] . '&id_md_cor_contrato=' . $arrObjMdCorContratoDTO[$i]->getNumIdMdCorContrato()) . '" tabindex="' . PaginaSEI::getInstance()->getProxTabTabela() . '"><img src="modulos/correios/imagens/svg/embalagem.svg?'.Icone::VERSAO.'" style="width: 24px; height: 24px" title="Tipos de Embalagem" alt="Tipos de Embalagem" class="infraImgModulo" /></a>&nbsp;';
            //}

            if ($bolAcaoConsultar) {
                $strResultado .= '<a href="' . SessaoSEI::getInstance()->assinarLink('controlador.php?acao=md_cor_contrato_consultar&acao_origem=' . $_GET['acao'] . '&acao_retorno=' . $_GET['acao'] . '&id_md_cor_contrato=' . $arrObjMdCorContratoDTO[$i]->getNumIdMdCorContrato()) . '" tabindex="' . PaginaSEI::getInstance()->getProxTabTabela() . '"><img src="' . PaginaSEI::getInstance()->getDiretorioSvgGlobal() . '/consultar.svg" title="Consultar Contrato e Serviços Postais" alt="Consultar Contrato e Serviços Postais" class="infraImg" /></a>&nbsp;';
            }

            if ($bolAcaoAlterar) {
                $strResultado .= '<a href="' . SessaoSEI::getInstance()->assinarLink('controlador.php?acao=md_cor_contrato_alterar&acao_origem=' . $_GET['acao'] . '&acao_retorno=' . $_GET['acao'] . '&id_md_cor_contrato=' . $arrObjMdCorContratoDTO[$i]->getNumIdMdCorContrato()) . '" tabindex="' . PaginaSEI::getInstance()->getProxTabTabela() . '"><img src="' . PaginaSEI::getInstance()->getDiretorioSvgGlobal() . '/alterar.svg" title="Alterar Contrato e Serviços Postais" alt="Alterar Contrato e Serviços Postais" class="infraImg" /></a>&nbsp;';
            }

            if ($bolAcaoDesativar || $bolAcaoReativar || $bolAcaoExcluir) {
                $strId = $arrObjMdCorContratoDTO[$i]->getNumIdMdCorContrato();
                $strDescricao = PaginaSEI::getInstance()->formatarParametrosJavaScript(PaginaSEI::tratarHTML($arrObjMdCorContratoDTO[$i]->getStrNumeroContrato()));
            }

            if ($bolAcaoDesativar || $bolAcaoReativar) {
                $strDescricao = PaginaSEI::getInstance()->formatarParametrosJavaScript(PaginaSEI::tratarHTML($arrObjMdCorContratoDTO[$i]->getStrNumeroContrato()));
                if ($bolAcaoDesativar && $arrObjMdCorContratoDTO[$i]->getStrSinAtivo() == 'S') {
                    $strResultado .= '<a href="' . PaginaSEI::getInstance()->montarAncora($strId) . '" onclick="acaoDesativar(\'' . $strId . '\',\'' . $strDescricao . '\');" tabindex="' . PaginaSEI::getInstance()->getProxTabTabela() . '"><img src="' . PaginaSEI::getInstance()->getDiretorioSvgGlobal() . '/desativar.svg" title="Desativar Contrato e Serviços Postais" alt="Desativar Contrato e Serviços Postais" class="infraImg" /></a>&nbsp;';
                } else {
                    $strResultado .= '<a href="' . PaginaSEI::getInstance()->montarAncora($strId) . '" onclick="acaoReativar(\'' . $strId . '\',\'' . $strDescricao . '\');" tabindex="' . PaginaSEI::getInstance()->getProxTabTabela() . '"><img src="' . PaginaSEI::getInstance()->getDiretorioSvgGlobal() . '/reativar.svg" title="Reativar Contrato e Serviços Postais" alt="Reativar Contrato e Serviços Postais" class="infraImg" /></a>&nbsp;';
                }
            }

            if ($bolAcaoExcluir) {
                $strResultado .= '<a href="' . PaginaSEI::getInstance()->montarAncora($strId) . '" onclick="acaoExcluir(\'' . $strId . '\',\'' . $strDescricao . '\');" tabindex="' . PaginaSEI::getInstance()->getProxTabTabela() . '"><img src="' . PaginaSEI::getInstance()->getDiretorioSvgGlobal() . '/excluir.svg" title="Excluir Contrato e Serviços Postais" alt="Excluir Contrato e Serviços Postais" class="infraImg" /></a>&nbsp;';
            }

            $strResultado .= '</td></tr>';
        }
        $strResultado .= '</table>';
    }
    if ($_GET['acao'] == 'md_cor_contrato_selecionar') {
        $arrComandos[] = '<button type="button" accesskey="C" id="btnFecharSelecao" value="Fechar" onclick="window.close();" class="infraButton">Fe<span class="infraTeclaAtalho">c</span>har</button>';
    } else {
        $arrComandos[] = '<button type="button" accesskey="C" id="btnFechar" value="Fechar" onclick="location.href=\'' . SessaoSEI::getInstance()->assinarLink('controlador.php?acao=procedimento_controlar&acao_origem=' . $_GET['acao']) . '\'" class="infraButton">Fe<span class="infraTeclaAtalho">c</span>har</button>';
    }
} catch (Exception $e) {
    PaginaSEI::getInstance()->processarExcecao($e);
}

PaginaSEI::getInstance()->montarDocType();
PaginaSEI::getInstance()->abrirHtml();
PaginaSEI::getInstance()->abrirHead();
PaginaSEI::getInstance()->montarMeta();
PaginaSEI::getInstance()->montarTitle(PaginaSEI::getInstance()->getStrNomeSistema() . ' - ' . $strTitulo);
PaginaSEI::getInstance()->montarStyle();
PaginaSEI::getInstance()->abrirStyle();
include_once('md_cor_contrato_lista_css.php');
PaginaSEI::getInstance()->fecharStyle();
PaginaSEI::getInstance()->montarJavaScript();
PaginaSEI::getInstance()->fecharHead();
PaginaSEI::getInstance()->abrirBody($strTitulo, 'onload="inicializar();"');

PaginaSEI::getInstance()->montarMensagens();
?>
    <form id="frmMdCorContratoLista" method="post"
          action="<?= SessaoSEI::getInstance()->assinarLink('controlador.php?acao=' . $_GET['acao'] . '&acao_origem=' . $_GET['acao']) ?>">
        <?
        PaginaSEI::getInstance()->montarBarraComandosSuperior($arrComandos);
        ?>
        <div class="infraAreaDados" id="divInfraAreaDados">
            <div class="row">
                <div class="col-sm-6 col-md-6 col-lg-6 col-xl-4 mb-2">
                    <label id="lblNumeroContrato" for="txtNumeroContrato" accesskey="o" class="infraLabelOpcional">Númer<span
                                class="infraTeclaAtalho">o</span> do Contrato no Órgão:</label>
                    <input type="text" id="txtNumeroContrato" name="txtNumeroContrato" class="infraText form-control"
                        value="<?= PaginaSEI::tratarHTML($_POST['txtNumeroContrato']) ?>"/>
                </div>
                <div class="col-sm-6 col-md-6 col-lg-6 col-xl-4">
                    <label id="lblNumeroContratoCorreio" for="txtNumeroContratoCorreio" accesskey="m"
                           class="infraLabelOpcional">Nú<span class="infraTeclaAtalho">m</span>ero do Contrato nos
                        Correios:</label>
                    <input type="text" id="txtNumeroContratoCorreio" name="txtNumeroContratoCorreio"
                           class="infraText form-control"
                           value="<?= PaginaSEI::tratarHTML($_POST['txtNumeroContratoCorreio']) ?>"/>

                    <input type="submit" style="visibility: hidden;"/>
                </div>

            </div>
            <div class="row">
                <div class="col-sm-12 col-md-12 col-lg-12 col-xl-12">
                    <?
                    PaginaSEI::getInstance()->montarAreaTabela($strResultado, $numRegistros);
                    //PaginaSEI::getInstance()->montarAreaDebug();
                    PaginaSEI::getInstance()->montarBarraComandosInferior($arrComandos);
                    ?>
                </div>
            </div>
        </div>
    </form>
<?
PaginaSEI::getInstance()->fecharBody();
PaginaSEI::getInstance()->fecharHtml();
require_once('md_cor_contrato_lista_js.php');
?>