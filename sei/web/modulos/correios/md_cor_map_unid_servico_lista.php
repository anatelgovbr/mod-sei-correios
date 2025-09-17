<?
/**
 * TRIBUNAL REGIONAL FEDERAL DA 4ª REGIÃO
 *
 * 22/12/2016 - criado por CAST
 *
 * Versão do Gerador de Código: 1.39.0
 */

try {

    session_start();

    //////////////////////////////////////////////////////////////////////////////
    //InfraDebug::getInstance()->setBolLigado(false);
    //InfraDebug::getInstance()->setBolDebugInfra(true);
    //InfraDebug::getInstance()->limpar();
    //////////////////////////////////////////////////////////////////////////////

    SessaoSEI::getInstance()->validarLink();

    PaginaSEI::getInstance()->prepararSelecao('md_cor_map_unid_servico_selecionar');

    SessaoSEI::getInstance()->validarPermissao($_GET['acao']);

    switch ($_GET['acao']) {
        case 'md_cor_map_unid_servico_excluir':
            $strTitulo = 'Excluir ';
            try {
                $arrStrIds = PaginaSEI::getInstance()->getArrStrItensSelecionados();
                for ($i = 0; $i < count($arrStrIds); $i++) {
                    $objMdCorMapUnidServicoDTO = new MdCorMapUnidServicoDTO();
                    $objMdCorMapUnidServicoDTO->setNumIdUnidadeSolicitante($arrStrIds[$i]);
                    $objMdCorMapUnidServicoDTO->retTodos();

                    $objMdCorMapUnidServicoRN = new MdCorMapUnidServicoRN();
                    $arrObjMdCorMapUnidServico = $objMdCorMapUnidServicoRN->listar($objMdCorMapUnidServicoDTO);

                    $objMdCorMapUnidServicoRN = new MdCorMapUnidServicoRN();
                    $objMdCorMapUnidServicoRN->excluir($arrObjMdCorMapUnidServico);
                }
                PaginaSEI::getInstance()->adicionarMensagem('Operação realizada com sucesso.');
            } catch (Exception $e) {
                PaginaSEI::getInstance()->processarExcecao($e);
            }
            header('Location: ' . SessaoSEI::getInstance()->assinarLink('controlador.php?acao=' . $_GET['acao_origem'] . '&acao_origem=' . $_GET['acao']));
            die;


        case 'md_cor_map_unid_servico_desativar':
            $strTitulo = 'Desativar ';
            try {
                $arrStrIds = PaginaSEI::getInstance()->getArrStrItensSelecionados();
                for ($i = 0; $i < count($arrStrIds); $i++) {
                    $objMdCorMapUnidServicoDTO = new MdCorMapUnidServicoDTO();
                    $objMdCorMapUnidServicoDTO->setNumIdUnidadeSolicitante($arrStrIds[$i]);
                    $objMdCorMapUnidServicoDTO->retTodos();

                    $objMdCorMapUnidServicoRN = new MdCorMapUnidServicoRN();
                    $arrObjMdCorMapUnidServico = $objMdCorMapUnidServicoRN->listar($objMdCorMapUnidServicoDTO);
                    $objMdCorMapUnidServicoRN = new MdCorMapUnidServicoRN();
                    $objMdCorMapUnidServicoRN->desativar($arrObjMdCorMapUnidServico);
                }
                PaginaSEI::getInstance()->adicionarMensagem('Operação realizada com sucesso.');
            } catch (Exception $e) {
                PaginaSEI::getInstance()->processarExcecao($e);
            }
            header('Location: ' . SessaoSEI::getInstance()->assinarLink('controlador.php?acao=' . $_GET['acao_origem'] . '&acao_origem=' . $_GET['acao']));
            die;

        case 'md_cor_map_unid_servico_reativar':
            $strTitulo = 'Reativar ';
            if ($_GET['acao_confirmada'] == 'sim') {
                try {
                    $arrStrIds = PaginaSEI::getInstance()->getArrStrItensSelecionados();
                    for ($i = 0; $i < count($arrStrIds); $i++) {
                        $objMdCorMapUnidServicoDTO = new MdCorMapUnidServicoDTO();
                        $objMdCorMapUnidServicoDTO->setNumIdUnidadeSolicitante($arrStrIds[$i]);
                        $objMdCorMapUnidServicoDTO->retTodos();

                        $objMdCorMapUnidServicoRN = new MdCorMapUnidServicoRN();
                        $arrObjMdCorMapUnidServico = $objMdCorMapUnidServicoRN->listar($objMdCorMapUnidServicoDTO);

                        $objMdCorMapUnidServicoRN = new MdCorMapUnidServicoRN();
                        $objMdCorMapUnidServicoRN->reativar($arrObjMdCorMapUnidServico);
                    }
                    PaginaSEI::getInstance()->adicionarMensagem('Operação realizada com sucesso.');
                } catch (Exception $e) {
                    PaginaSEI::getInstance()->processarExcecao($e);
                }
                header('Location: ' . SessaoSEI::getInstance()->assinarLink('controlador.php?acao=' . $_GET['acao_origem'] . '&acao_origem=' . $_GET['acao']));
                die;
            }
            break;


        case 'md_cor_map_unid_servico_selecionar':
            $strTitulo = PaginaSEI::getInstance()->getTituloSelecao('Selecionar ', 'Selecionar ');

            //Se cadastrou alguem
            if ($_GET['acao_origem'] == 'md_cor_map_unid_servico_cadastrar') {
                if (isset($_GET['id_md_cor_map_unid_servico']) && isset($_GET['id_unidade_solicitante'])) {
                    PaginaSEI::getInstance()->adicionarSelecionado($_GET['id_md_cor_map_unid_servico'] . '-' . $_GET['id_unidade_solicitante']);
                }
            }
            break;

        case 'md_cor_map_unid_servico_listar':
            $strTitulo = 'Mapeamento de Unidades Solicitantes e Serviços Postais';
            break;

        default:
            throw new InfraException("Ação '" . $_GET['acao'] . "' não reconhecida.");
    }

    $arrComandos = array();

///////////////////  
    $arrComandos[] = '<button type="submit" accesskey="P" id="btnPesquisar" name="btnPesquisar" value="Pesquisar" class="infraButton"><span class="infraTeclaAtalho">P</span>esquisar</button>';

    if ($_GET['acao'] == 'md_cor_map_unid_servico_selecionar') {
        $arrComandos[] = '<button type="button" accesskey="T" id="btnTransportarSelecao" value="Transportar" onclick="infraTransportarSelecao();" class="infraButton"><span class="infraTeclaAtalho">T</span>ransportar</button>';
    }

    if ($_GET['acao'] == 'md_cor_map_unid_servico_listar' || $_GET['acao'] == 'md_cor_map_unid_servico_selecionar') {
        $bolAcaoCadastrar = SessaoSEI::getInstance()->verificarPermissao('md_cor_map_unid_servico_cadastrar');
        if ($bolAcaoCadastrar) {
            $arrComandos[] = '<button type="button" accesskey="N" id="btnNovo" value="Novo" onclick="location.href=\'' . SessaoSEI::getInstance()->assinarLink('controlador.php?acao=md_cor_map_unid_servico_cadastrar&acao_origem=' . $_GET['acao'] . '&acao_retorno=' . $_GET['acao']) . '\'" class="infraButton"><span class="infraTeclaAtalho">N</span>ovo</button>';
        }
    }

    $objMdCorMapUnidServicoDTO = new MdCorMapUnidServicoDTO();
    $objMdCorMapUnidServicoDTO->setDistinct(true);

    $numIdUnidade = trim($_REQUEST['selUnidade']);
    if ($numIdUnidade != '') {
        $objMdCorMapUnidServicoDTO->setNumIdUnidadeSolicitante($numIdUnidade);
    }

    $numIdServico = trim($_REQUEST['selServico']);
    if ($numIdServico != '') {
        $objMdCorMapUnidServicoDTO->setNumIdMdCorServicoPostal($numIdServico);
    }

    //  $objMdCorMapUnidServicoDTO->retNumIdMdCorMapUnidServico();
    $objMdCorMapUnidServicoDTO->retNumIdUnidadeSolicitante();
    $objMdCorMapUnidServicoDTO->retStrSiglaUnidade();
    $objMdCorMapUnidServicoDTO->retStrDescricaoUnidade();
    $objMdCorMapUnidServicoDTO->retStrNomeServicos();
    #$objMdCorMapUnidServicoDTO->retStrSinAtivo();

    // Combos
    $strItensSelUnidade = MdCorMapeamentoUniExpSolINT::montarSelectUnidadesSolicitantes(' ', ' ', $numIdUnidade);


//  $objMdCorMapeamentoUniExpSolDTO = new MdCorMapeamentoUniExpSolDTO();
//  $objMdCorMapeamentoUniExpSolDTO->retTodos(true);

//  $objMdCorMapeamentoRN = new MdCorMapeamentoUniExpSolRN();

//  $objMdCorMapeamentoUniExpSolDTO->setOrdStrSiglaUnidadeExpedidora(InfraDTO::$TIPO_ORDENACAO_ASC);

//  $arrObjMdCorMapeamentoUniExpSolDTO = $objMdCorMapeamentoRN->listar($objMdCorMapeamentoUniExpSolDTO);
//  foreach($arrObjMdCorMapeamentoUniExpSolDTO as $objMdCorMapeamentoUniExpSolDTO){
//     $objMdCorMapeamentoUniExpSolDTO->setStrSiglaUnidadeSolicitante(UnidadeINT::formatarSiglaDescricao($objMdCorMapeamentoUniExpSolDTO->getStrSiglaUnidadeSolicitante(),$objMdCorMapeamentoUniExpSolDTO->getStrDescricaoUnidadeSolicitante()));
//  }

//  $strItensSelUnidade =  InfraINT::montarSelectArrInfraDTO($strPrimeiroItemValor, $strPrimeiroItemDescricao, $strValorItemSelecionado, $arrObjMdCorMapeamentoUniExpSolDTO, 'IdUnidadeExp', 'SiglaUnidadeSolicitante');


    $strItensSelServico = MdCorServicoPostalINT::montarSelectId_Descricao_MdCorServicoPostal(' ', '', $numIdServico, '', 'Nome');


    if ($_GET['acao'] == 'md_cor_map_unid_servico_reativar') {
        //Lista somente inativos
        $objMdCorMapUnidServicoDTO->setBolExclusaoLogica(false);
        $objMdCorMapUnidServicoDTO->setStrSinAtivo('N');
    }

    PaginaSEI::getInstance()->prepararOrdenacao($objMdCorMapUnidServicoDTO, /*'IdMdCorMapUnidServico'*/ 'SiglaUnidade', InfraDTO::$TIPO_ORDENACAO_ASC);
    PaginaSEI::getInstance()->prepararPaginacao($objMdCorMapUnidServicoDTO, 200);

    $objMdCorMapUnidServicoRN = new MdCorMapUnidServicoRN();
    $arrObjMdCorMapUnidServicoDTO = $objMdCorMapUnidServicoRN->listar($objMdCorMapUnidServicoDTO);

    PaginaSEI::getInstance()->processarPaginacao($objMdCorMapUnidServicoDTO);
    $numRegistros = count($arrObjMdCorMapUnidServicoDTO);

    if ($numRegistros > 0) {

        $bolCheck = true; //false;

        if ($_GET['acao'] == 'md_cor_map_unid_servico_selecionar') {
            $bolAcaoAlterar = SessaoSEI::getInstance()->verificarPermissao('md_cor_map_unid_servico_alterar');
            $bolAcaoConsultar = SessaoSEI::getInstance()->verificarPermissao('md_cor_map_unid_servico_consultar');
            $bolAcaoDesativar = false;
            $bolAcaoExcluir = false;
            $bolAcaoImprimir = false;
            $bolAcaoReativar = false;
            //$bolAcaoSelecionar
            //$bolAcaoGerarPlanilha = false;
            $bolCheck = true;
        } else if ($_GET['acao'] == 'md_cor_map_unid_servico_reativar') {
            $bolAcaoAlterar = false;
            $bolAcaoConsultar = SessaoSEI::getInstance()->verificarPermissao('md_cor_map_unid_servico_consultar');
            $bolAcaoDesativar = false;
            $bolAcaoExcluir = SessaoSEI::getInstance()->verificarPermissao('md_cor_map_unid_servico_excluir');
            $bolAcaoImprimir = true;
            $bolAcaoReativar = SessaoSEI::getInstance()->verificarPermissao('md_cor_map_unid_servico_reativar');
            //$bolAcaoSelecionar
            //$bolAcaoGerarPlanilha = SessaoSEI::getInstance()->verificarPermissao('infra_gerar_planilha_tabela');
        } else {
            $bolAcaoAlterar = SessaoSEI::getInstance()->verificarPermissao('md_cor_map_unid_servico_alterar');
            $bolAcaoConsultar = SessaoSEI::getInstance()->verificarPermissao('md_cor_map_unid_servico_consultar');
            $bolAcaoDesativar = SessaoSEI::getInstance()->verificarPermissao('md_cor_map_unid_servico_desativar');
            $bolAcaoExcluir = SessaoSEI::getInstance()->verificarPermissao('md_cor_map_unid_servico_excluir');
            $bolAcaoImprimir = true;
            $bolAcaoReativar = SessaoSEI::getInstance()->verificarPermissao('md_cor_map_unid_servico_reativar');
            //$bolAcaoSelecionar
            //$bolAcaoGerarPlanilha = SessaoSEI::getInstance()->verificarPermissao('infra_gerar_planilha_tabela');
        }

        if ($bolAcaoImprimir) {
            $bolCheck = true;
            $arrComandos[] = '<button type="button" accesskey="I" id="btnImprimir" value="Imprimir" onclick="infraImprimirTabela();" class="infraButton"><span class="infraTeclaAtalho">I</span>mprimir</button>';
        }

        if ($bolAcaoDesativar) {
            $bolCheck = true;
            //$arrComandos[] = '<button type="button" accesskey="t" id="btnDesativar" value="Desativar" onclick="acaoDesativacaoMultipla();" class="infraButton">Desa<span class="infraTeclaAtalho">t</span>ivar</button>';
            $strLinkDesativar = SessaoSEI::getInstance()->assinarLink('controlador.php?acao=md_cor_map_unid_servico_desativar&acao_origem=' . $_GET['acao']);
        }
        if ($bolAcaoReativar) {
            $bolCheck = true;
            //$arrComandos[] = '<button type="button" accesskey="R" id="btnReativar" value="Reativar" onclick="acaoReativacaoMultipla();" class="infraButton"><span class="infraTeclaAtalho">R</span>eativar</button>';
            $strLinkReativar = SessaoSEI::getInstance()->assinarLink('controlador.php?acao=md_cor_map_unid_servico_reativar&acao_origem=' . $_GET['acao'] . '&acao_confirmada=sim');
        }
        if ($bolAcaoExcluir) {
            $bolCheck = true;
            //$arrComandos[] = '<button type="button" accesskey="E" id="btnExcluir" value="Excluir" onclick="acaoExclusaoMultipla();" class="infraButton"><span class="infraTeclaAtalho">E</span>xcluir</button>';
            $strLinkExcluir = SessaoSEI::getInstance()->assinarLink('controlador.php?acao=md_cor_map_unid_servico_excluir&acao_origem=' . $_GET['acao']);
        }
        if ($bolAcaoGerarPlanilha) {
            $bolCheck = true;
            //$arrComandos[] = '<button type="button" accesskey="P" id="btnGerarPlanilha" value="Gerar Planilha" onclick="infraGerarPlanilhaTabela(\''.SessaoSEI::getInstance()->assinarLink('controlador.php?acao=infra_gerar_planilha_tabela').'\');" class="infraButton">Gerar <span class="infraTeclaAtalho">P</span>lanilha</button>';
        }

        $strResultado = '';

        if ($_GET['acao'] != 'md_cor_map_unid_servico_reativar') {
            $strSumarioTabela = 'Tabela de Unidades Mapeadas';
            $strCaptionTabela = 'Unidades Mapeadas:';
        } else {
            $strSumarioTabela = 'Tabela de Unidades Mapeadas Inativas';
            $strCaptionTabela = 'Unidades Mapeadas Inativas:';
        }

        $strResultado .= '<table width="100%" class="infraTable" summary="' . $strSumarioTabela . '">' . "\n";
        $strResultado .= '<caption class="infraCaption">' . PaginaSEI::getInstance()->gerarCaptionTabela($strCaptionTabela, $numRegistros) . '</caption>';
        $strResultado .= '<tr>';
        if ($bolCheck) {
            $strResultado .= '<th class="infraTh" width="10px">' . PaginaSEI::getInstance()->getThCheck() . '</th>' . "\n";
        }
        $strResultado .= '<th class="infraTh text-left" width="44%">Unidade Solicitante</th>' . "\n";
        $strResultado .= '<th class="infraTh text-left" width="40%">Serviços Postais</th>' . "\n";
        $strResultado .= '<th class="infraTh" width="140px">Ações</th>' . "\n";
        $strResultado .= '</tr>' . "\n";
        $strCssTr = '';

        for ($i = 0; $i < $numRegistros; $i++) {
            $todosRegUnidInativo = false;

            // Múltiplos Serviços
            $objMdCorMapUnidServicosDTO = new MdCorMapUnidServicoDTO();
            //$objMdCorMapUnidServicosDTO->setNumIdMdCorMapUnidServico(null);
            $objMdCorMapUnidServicosDTO->setNumIdUnidadeSolicitante($arrObjMdCorMapUnidServicoDTO[$i]->getNumIdUnidadeSolicitante());
            //$objMdCorMapUnidServicosDTO->setStrSinAtivo('S');
            $objMdCorMapUnidServicosDTO->retStrNomeServico();
            $objMdCorMapUnidServicosDTO->retStrDescricaoServico();
            $objMdCorMapUnidServicosDTO->retStrNumeroContrato();
            $objMdCorMapUnidServicosDTO->retStrSinAtivo();

            $objMdCorMapUnidServicosRN = new MdCorMapUnidServicoRN();
            $arrObjMdCorMapUnidServicosDTO = $objMdCorMapUnidServicosRN->listar($objMdCorMapUnidServicosDTO);

            $numRegistrosServico = count($arrObjMdCorMapUnidServicosDTO);
            $qtdRegInativos = 0;
            $servicos = '';
            if ($numRegistrosServico > 0) {                
                for ($j = 0; $j < $numRegistrosServico; $j++) {

                    if ($servicos != '') {
                        $servicos .= ', ';
                    }

                    if ( $arrObjMdCorMapUnidServicosDTO[$j]->getStrSinAtivo() == 'N' ) $qtdRegInativos++;

                    $servicos .= $arrObjMdCorMapUnidServicosDTO[$j]->getStrDescricaoServico() . ' - ' . $arrObjMdCorMapUnidServicosDTO[$j]->getStrNumeroContrato();
                }
            }
            $arrObjMdCorMapUnidServicoDTO[$i]->setStrNomeServicos($servicos);

            if ( $qtdRegInativos == $numRegistrosServico ) {
                $todosRegUnidInativo = true;
                $strCssTr = '<tr class="infraTrVermelha">';
            } else {
                $strCssTr = ($strCssTr == '<tr class="infraTrClara">') ? '<tr class="infraTrEscura">' : '<tr class="infraTrClara">';
            }

            $strResultado .= $strCssTr;

            if ($bolCheck) {
                $strResultado .= '<td valign="middle">' . PaginaSEI::getInstance()->getTrCheck($i, $arrObjMdCorMapUnidServicoDTO[$i]->getNumIdUnidadeSolicitante(), $arrObjMdCorMapUnidServicoDTO[$i]->getNumIdUnidadeSolicitante()) . '</td>';
            }
            $strResultado .= '<td>';
            $strResultado .= PaginaSEI::tratarHTML($arrObjMdCorMapUnidServicoDTO[$i]->getStrSiglaUnidade());
            $strResultado .= ' - ' . PaginaSEI::tratarHTML($arrObjMdCorMapUnidServicoDTO[$i]->getStrDescricaoUnidade());
            $strResultado .= '</td>';
            $strResultado .= '<td>';
            $strResultado .= PaginaSEI::tratarHTML($arrObjMdCorMapUnidServicoDTO[$i]->getStrNomeServicos());
            $strResultado .= '</td>';

            //Ações
            $strResultado .= '<td align="center">';

            $strResultado .= PaginaSEI::getInstance()->getAcaoTransportarItem($i, $arrObjMdCorMapUnidServicoDTO[$i]->getNumIdUnidadeSolicitante());


            if ($bolAcaoConsultar) {
                $strResultado .= '<a href="' . SessaoSEI::getInstance()->assinarLink('controlador.php?acao=md_cor_map_unid_servico_consultar&acao_origem=' . $_GET['acao'] . '&acao_retorno=' . $_GET['acao'] . '&id_unidade_solicitante=' . $arrObjMdCorMapUnidServicoDTO[$i]->getNumIdUnidadeSolicitante()) . '" tabindex="' . PaginaSEI::getInstance()->getProxTabTabela() . '"><img src="' . PaginaSEI::getInstance()->getDiretorioSvgGlobal() . '/consultar.svg" title="Consultar ' . $strTitulo . '" alt="Consultar ' . $strTitulo . '" class="infraImg" /></a>&nbsp;';
            }

            if ($bolAcaoAlterar) {
                $strResultado .= '<a href="' . SessaoSEI::getInstance()->assinarLink('controlador.php?acao=md_cor_map_unid_servico_alterar&acao_origem=' . $_GET['acao'] . '&acao_retorno=' . $_GET['acao'] . '&id_unidade_solicitante=' . $arrObjMdCorMapUnidServicoDTO[$i]->getNumIdUnidadeSolicitante()) . '" tabindex="' . PaginaSEI::getInstance()->getProxTabTabela() . '"><img src="' . PaginaSEI::getInstance()->getDiretorioSvgGlobal() . '/alterar.svg" title="Alterar ' . $strTitulo . '" alt="Alterar ' . $strTitulo . '" class="infraImg" /></a>&nbsp;';
            }

            if ($bolAcaoDesativar || $bolAcaoReativar || $bolAcaoExcluir) {
                $strId = $arrObjMdCorMapUnidServicoDTO[$i]->getNumIdUnidadeSolicitante();
                $strDescricao = PaginaSEI::getInstance()->formatarParametrosJavaScript(PaginaSEI::tratarHTML($arrObjMdCorMapUnidServicoDTO[$i]->getStrSiglaUnidade()) . ' - ' . PaginaSEI::tratarHTML($arrObjMdCorMapUnidServicoDTO[$i]->getStrDescricaoUnidade()));
            }

            if ($bolAcaoDesativar && !$todosRegUnidInativo) {
                $strResultado .= '<a href="' . PaginaSEI::getInstance()->montarAncora($strId) . '" onclick="acaoDesativar(\'' . $strId . '\',\'' . $strDescricao . '\');" tabindex="' . PaginaSEI::getInstance()->getProxTabTabela() . '"><img src="' . PaginaSEI::getInstance()->getDiretorioSvgGlobal() . '/desativar.svg" title="Desativar ' . $strTitulo . '" alt="Desativar ' . $strTitulo . '" class="infraImg" /></a>&nbsp;';
            }

            if ($bolAcaoReativar && $todosRegUnidInativo) {
                $strResultado .= '<a href="' . PaginaSEI::getInstance()->montarAncora($strId) . '" onclick="acaoReativar(\'' . $strId . '\',\'' . $strDescricao . '\');" tabindex="' . PaginaSEI::getInstance()->getProxTabTabela() . '"><img src="' . PaginaSEI::getInstance()->getDiretorioSvgGlobal() . '/reativar.svg" title="Reativar ' . $strTitulo . '" alt="Reativar ' . $strTitulo . '" class="infraImg" /></a>&nbsp;';
            }

            if ($bolAcaoExcluir) {
                $strResultado .= '<a href="' . PaginaSEI::getInstance()->montarAncora($strId) . '" onclick="acaoExcluir(\'' . $strId . '\',\'' . $strDescricao . '\');" tabindex="' . PaginaSEI::getInstance()->getProxTabTabela() . '"><img src="' . PaginaSEI::getInstance()->getDiretorioSvgGlobal() . '/excluir.svg" title="Excluir ' . $strTitulo . '" alt="Excluir ' . $strTitulo . '" class="infraImg" /></a>&nbsp;';
            }

            $strResultado .= '</td></tr>' . "\n";
        }
        $strResultado .= '</table>';
    }
    if ($_GET['acao'] == 'md_cor_map_unid_servico_selecionar') {
        $arrComandos[] = '<button type="button" accesskey="C" id="btnFecharSelecao" value="Fechar" onclick="window.close();" class="infraButton">Fe<span class="infraTeclaAtalho">c</span>har</button>';
    } else {
        //$arrComandos[] = '<button type="button" accesskey="C" id="btnFechar" value="Fechar" onclick="location.href=\''.SessaoSEI::getInstance()->assinarLink('controlador.php?acao='.PaginaSEI::getInstance()->getAcaoRetorno().'&acao_origem='.$_GET['acao']).'\'" class="infraButton">Fe<span class="infraTeclaAtalho">c</span>har</button>';
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
PaginaSEI::getInstance()->fecharStyle();
PaginaSEI::getInstance()->montarJavaScript();
PaginaSEI::getInstance()->abrirJavaScript();
PaginaSEI::getInstance()->fecharJavaScript();
require_once("md_cor_map_unid_servico_lista_css.php");
PaginaSEI::getInstance()->fecharHead();
PaginaSEI::getInstance()->abrirBody($strTitulo, 'onload="inicializar();"');
?>
    <form id="frmMdCorMapUnidServicoLista" method="post" action="<?= SessaoSEI::getInstance()->assinarLink('controlador.php?acao=' . $_GET['acao'] . '&acao_origem=' . $_GET['acao']) ?>">
        <? PaginaSEI::getInstance()->montarBarraComandosSuperior($arrComandos); ?>
        <div class="row ">
            <div class="col-12 col-sm-12 col-md-6 col-lg-6 col-xl-6 mb-2">                
                <label class="infraLabelOpcional">Unidade Solicitante:</label>
                <select class="infraSelect form-select" tabindex="504" name="selUnidade" onchange="enviarFormPesquisa()" id="selUnidade"<?= $strDesabilitarConsultar ?>>
                    <?= $strItensSelUnidade ?>
                </select>
            </div>
            <div class="col-12 col-sm-12 col-md-6 col-lg-6 col-xl-6">
                <label class="infraLabelOpcional">Serviço Postal:</label>
                <select class="infraSelect form-select" tabindex="504" name="selServico" onchange="enviarFormPesquisa()" id="selServico"<?= $strDesabilitarConsultar ?>>
                    <?= $strItensSelServico ?>
                </select>
            </div>
        </div>
        <?
        PaginaSEI::getInstance()->montarAreaTabela($strResultado, $numRegistros);
        PaginaSEI::getInstance()->montarBarraComandosInferior($arrComandos);
        ?>
    </form>
<?
require_once("md_cor_map_unid_servico_lista_js.php");
PaginaSEI::getInstance()->fecharBody();
PaginaSEI::getInstance()->fecharHtml();
?>