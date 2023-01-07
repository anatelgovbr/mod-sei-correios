<?php
/**
 * ANATEL
 *
 * 18/10/2017 - criado por Ellyson de Jesus Silva
 *
 */
try {
    require_once dirname(__FILE__) . '/../../SEI.php';

    session_start();

    SessaoSEI::getInstance()->validarLink();

    SessaoSEI::getInstance()->validarPermissao($_GET['acao']);

    $strLinkImprimirVoucher = SessaoSEI::getInstance()->assinarLink('controlador.php?acao=md_cor_plp_imprimir_voucher&id_md_cor_plp=' . $_GET['id_md_cor_plp']);
    $strLinkImprimirAR = SessaoSEI::getInstance()->assinarLink('controlador.php?acao=md_cor_plp_imprimir_ar&id_md_cor_plp=' . $_GET['id_md_cor_plp']);
    $strLinkVerificarImprimirAR = SessaoSEI::getInstance()->assinarLink('controlador_ajax.php?acao_ajax=md_cor_plp_imprimir_ar_verificar&id_md_cor_plp=' . $_GET['id_md_cor_plp']);
    $strLinkImprimirRotuloEnvelope = SessaoSEI::getInstance()->assinarLink('controlador.php?acao=md_cor_plp_imprimir_rotulo_envelope&id_md_cor_plp=' . $_GET['id_md_cor_plp']);

    /**
     * Montar array de comandos superior
     */
    $arrComandos = array();

    //recuperando a PLP
    $objMdCorPlpDTO = new MdCorPlpDTO();
    $objMdCorPlpDTO->retNumIdMdPlp();
    $objMdCorPlpDTO->retDblCodigoPlp();
    $objMdCorPlpDTO->retStrStaPlp();
    $objMdCorPlpDTO->retNumContagem();
    $objMdCorPlpDTO->setNumIdMdPlp($_GET['id_md_cor_plp']);

    $objMdCorPlpRN = new MdCorPlpRN();
    $objMdCorPlpDTO = $objMdCorPlpRN->consultar($objMdCorPlpDTO);

    switch ($_GET['acao']) {
        case 'md_cor_plp_detalhada':
            $strTitulo = 'Detalhar PLP - Identificador nº ';
            $strAcaoTabela = 'md_cor_plp_detalhar_objeto';
            $titleBotao = 'Detalhar Objeto';


            $arrComandos[] = '<button type="button" accesskey="t" id="btnArquivoLote" onclick="imprimirArquivolLote();" class="infraButton">
                                   Imprimir Documen<span class="infraTeclaAtalho">t</span>os
                              </button>';

            $arrComandos[] = '<button type="button" accesskey="v"  id="btnImprimirRotulo" onclick="imprimirRotuloEnvelope()" class="infraButton">
                                   Imprimir En<span class="infraTeclaAtalho">v</span>elopes
                              </button>';
            $arrComandos[] = '<button type="button" accesskey="m" id="btnImprimirAR" onclick="imprimirAR()"  class="infraButton">
                                   Impri<span class="infraTeclaAtalho">m</span>ir ARs
                              </button>';
            $arrComandos[] = '<button accesskey="p" type="button" id="btnVoucher" onclick="imprimirVoucher()" class="infraButton">
                                   Im<span class="infraTeclaAtalho">p</span>rimir Voucher
                              </button>';
            break;

        case 'md_cor_plp_expedir':
            $strAcaoTabela = 'md_cor_plp_expedir_objeto';
            $strTitulo = 'Expedir PLP - Identificador nº ';
            $titleBotao = 'Expedir Objeto';


            $arrComandos[] = '<button type="button" accesskey="t" id="btnArquivoLote" onclick="imprimirArquivolLote();" class="infraButton">
                                   Imprimir Documen<span class="infraTeclaAtalho">t</span>os
                              </button>';


            $arrComandos[] = '<button type="button" accesskey="v"  id="btnImprimirRotulo" onclick="imprimirRotuloEnvelope()" class="infraButton">
                                   Imprimir En<span class="infraTeclaAtalho">v</span>elopes
                              </button>';
            $arrComandos[] = '<button type="button" accesskey="m" id="btnImprimirAR" onclick="imprimirAR()"  class="infraButton">
                                   Impri<span class="infraTeclaAtalho">m</span>ir ARs
                              </button>';
            $arrComandos[] = '<button accesskey="p" type="button" id="btnVoucher" onclick="imprimirVoucher()" class="infraButton">
                                   Im<span class="infraTeclaAtalho">p</span>rimir Voucher
                              </button>';
            $arrComandos[] = '<button type="button" accesskey="o" id="btnImprimir" onclick="ConcluirPLP()" class="infraButton">
                                  C<span class="infraTeclaAtalho">o</span>ncluir Expedição da PLP
                              </button>';
            break;

        case 'md_cor_plp_pdf_documento_principal' :
            $objMdCorExpedicaoSolicitadaRN = new MdCorExpedicaoSolicitadaRN();
            $selIdMdCorExpedicaoSolicitada = PaginaSEI::getInstance()->getArrStrItensSelecionados();

            $objMdCorExpedicaoSolicitadaDTO = new MdCorExpedicaoSolicitadaDTO();
            $objMdCorExpedicaoSolicitadaDTO->retDblIdDocumentoPrincipal();
            $objMdCorExpedicaoSolicitadaDTO->setNumIdMdCorExpedicaoSolicitada($selIdMdCorExpedicaoSolicitada, InfraDTO::$OPER_IN);
            $ret = InfraArray::converterArrInfraDTO($objMdCorExpedicaoSolicitadaRN->listar($objMdCorExpedicaoSolicitadaDTO), 'IdDocumentoPrincipal');


            $objAnexoDTO = new AnexoDTO();
            $objDocumentoRN = new DocumentoRN();
            $objAnexoDTO = $objDocumentoRN->gerarPdf(InfraArray::gerarArrInfraDTO('DocumentoDTO', 'IdDocumento', $ret));
            $linkRedirecionar = SessaoSEI::getInstance()->assinarLink('controlador.php?acao=md_cor_exibir_pdf&nome_arquivo=' . $objAnexoDTO->getStrNome() . '&nome_download=SEI-PLP-DocumentoPrincipalLote.pdf');
            header('Location: ' . $linkRedirecionar);
            break;

        case 'md_cor_plp_pdf_arquivo_lote' :

            $selIdMdCorExpedicaoSolicitada = PaginaSEI::getInstance()->getArrStrItensSelecionados();

            $objMdCorExpedicaoSolicitadaRN = new MdCorExpedicaoSolicitadaRN();
            $objMdCorExpedicaoSolicitadaDTO = new MdCorExpedicaoSolicitadaDTO();
            $objMdCorExpedicaoSolicitadaDTO->setNumIdMdCorExpedicaoSolicitada($selIdMdCorExpedicaoSolicitada, InfraDTO::$OPER_IN);
            $objMdCorExpedicaoSolicitadaDTO->retDblIdDocumentoPrincipal();
            $objMdCorExpedicaoSolicitadaDTO->retNumIdMdCorExpedicaoSolicitada();
            $objMdCorExpedicaoSolicitadaDTO->retStrCodigoRastreamento();
            $objMdCorExpedicaoSolicitadaDTO->retStrProtocoloFormatadoDocumento();
            $arrObjMdCorExpedicaoSolicitadaDTO = $objMdCorExpedicaoSolicitadaRN->listar($objMdCorExpedicaoSolicitadaDTO);

            $objMdCorExpedicaoFormatoRN = new MdCorExpedicaoFormatoRN();
            $mdCorExpedicaoSolicitadaRN = new MdCorExpedicaoSolicitadaRN();
            $arrDocumento = array();

            $objAnexoRN = new AnexoRN();
            $strCaminhoCompletoArquivoZip = DIR_SEI_TEMP.'/'.$objAnexoRN->gerarNomeArquivoTemporario();

            $zipFile = new ZipArchive();
            $zipFile->open($strCaminhoCompletoArquivoZip, ZIPARCHIVE::CREATE);

            foreach ($arrObjMdCorExpedicaoSolicitadaDTO as $dados) {
                $sequencial++;
                $idDocumentoPrincipal = $dados->getDblIdDocumentoPrincipal();
                $objMdCorExpedicaoFormatoDTO = new MdCorExpedicaoFormatoDTO();
                $objMdCorExpedicaoFormatoDTO->retDblIdProtocolo();
                $objMdCorExpedicaoFormatoDTO->retStrImpressao();
                $objMdCorExpedicaoFormatoDTO->setNumIdMdCorExpedicaoSolicitada($dados->getNumIdMdCorExpedicaoSolicitada());
                $objMdCorExpedicaoFormatoDTO->setStrFormaExpedicao('I');
                $objMdCorExpedicaoFormatoDTO->setOrd('IdMdCorExpedicaoSolicitada', InfraDTO::$TIPO_ORDENACAO_ASC);
                $arrObjMdCorExpedicaoFormatoDTO = $objMdCorExpedicaoFormatoRN->listar($objMdCorExpedicaoFormatoDTO);

                $idDocumento = array();

                foreach ($arrObjMdCorExpedicaoFormatoDTO as $dadosFormato) {

                    $objDocumentoDTO = new DocumentoDTO();
                    $objDocumentoDTO->setDblIdDocumento($dadosFormato->getDblIdProtocolo());

                    if ($dadosFormato->getStrImpressao() == 'P') {
                        $objDocumentoDTO->setStrSinPdfEscalaCinza('S');
                    } else {
                        $objDocumentoDTO->setStrSinPdfEscalaCinza('N');
                    }
                    if ($idDocumentoPrincipal == $dadosFormato->getDblIdProtocolo()) {
                        array_unshift($idDocumento, $objDocumentoDTO);
                    } else {
                        $idDocumento[] = $objDocumentoDTO;
                    }
                    $dados->setStrSinObjetoAcessado('S');
                    $mdCorExpedicaoSolicitadaRN->alterar($dados);
                }

                $objAnexoDTO = new AnexoDTO();
                $arrNomeArquivo = explode('/',$strCaminhoCompletoArquivoZip);
                $objAnexoDTO->setStrNome($arrNomeArquivo[count($arrNomeArquivo)-1]);

                $objDocumentoRN = new DocumentoRN();
                $ObjetoAnexoDTO = $objDocumentoRN->gerarPdf($idDocumento);

                $strCaminhoCompletoArquivoPdf = DIR_SEI_TEMP.'/'.$ObjetoAnexoDTO->getStrNome();

                //montar nome do arquivo
                $ProtocoloFormatadoDocPrincipal = $dados->getStrProtocoloFormatadoDocumento();
                $CodigoRastreio = $dados->getStrCodigoRastreamento();
                $strNomeArquivo = "{$sequencial}_{$ProtocoloFormatadoDocPrincipal}_{$CodigoRastreio}.pdf";

                if ($zipFile->addFile( $strCaminhoCompletoArquivoPdf, $strNomeArquivo) === false) {
                    throw new InfraException('Erro adicionando arquivo externo ao zip.');
                }

            }

            if ($zipFile->close() === false) {
                throw new InfraException('Não foi possível fechar arquivo zip.');
            }

            if (file_exists($strCaminhoCompletoArquivoPdf)){
                unlink($strCaminhoCompletoArquivoPdf);
            }

            $strNomeArquivoPLP = 'PLP_'.$objMdCorPlpDTO->getDblCodigoPlp();

            $linkRedirecionar = SessaoSEI::getInstance()->assinarLink('controlador.php?acao=exibir_arquivo&nome_arquivo=' . $objAnexoDTO->getStrNome() . '&nome_download='.$strNomeArquivoPLP.'.zip&acao_origem=' . $_GET['acao'] . '&acao_retorno=' . $_GET['acao']);
            header('Location: ' . $linkRedirecionar);
            break;

        case 'md_cor_plp_concluir' :
            try {
                $idUnidadeAtual = SessaoSEI::getInstance()->getNumIdUnidadeAtual();
                $idUsuarioAtual = SessaoSEI::getInstance()->getNumIdUsuario();
                $idPlp = $_GET['id_plp'];
                $objMdCorPlpRN = new MdCorPlpRN();
                $objMdCorPlpDTO = new MdCorPlpDTO();
                $objMdCorPlpDTO->retNumIdMdPlp();
                $objMdCorPlpDTO->retStrStaPlp();
                $objMdCorPlpDTO->setNumIdMdPlp($idPlp);
                $objMdCorPlpDTO = $objMdCorPlpRN->consultar($objMdCorPlpDTO);

                $objMdCorPlpDTO->setStrStaPlp(MdCorPlpRN::$STA_PENDENTE);
                $objMdCorPlpRN->alterar($objMdCorPlpDTO);

                $arrParametro = [
                    'idUnidade' => $idUnidadeAtual,
                    'idUsuario' => $idUsuarioAtual,
                    'idPlp' => $idPlp,
                ];
                $objMdCorPlpRN->salvarAndamentoProcesso($arrParametro);
                PaginaSEI::getInstance()->setStrMensagem('Conclusão da expedição da PLP realizada com sucesso!', InfraPagina::$TIPO_MSG_AVISO);
                header('Location: ' . SessaoSEI::getInstance()->assinarLink('controlador.php?acao=md_cor_geracao_plp_listar'));
            } catch (Exception $e) {
                throw new InfraException('Erro ao imprimir', $e);
            }
            break;
        default:
            throw new InfraException("Ação '" . $_GET['acao'] . "' não reconhecida.");
    }

    $arrComandos[] = '<button type="button" accesskey="c" id="btnFechar" onclick="location.href=\'' . SessaoSEI::getInstance()->assinarLink('controlador.php?acao=' . PaginaSEI::getInstance()->getAcaoRetorno() . '&acao_origem=' . $_GET['acao'] . PaginaSEI::getInstance()->montarAncora($_GET['id_md_cor_plp'])) . '\';" class="infraButton">
                                    Fe<span class="infraTeclaAtalho">c</span>har
                              </button>';

    $processar = false;
    if($objMdCorPlpDTO){
        if(count($objMdCorPlpDTO->getArrAtributos()) != 2){
            $processar = true;
        }
    }

    if($processar) {
        $strTitulo .= $objMdCorPlpDTO->getDblCodigoPlp();

        //recuperando a lista de expedição solicitada
        $objMdCorExpedicaoSolicitadaDTO = new MdCorExpedicaoSolicitadaDTO();
        $objMdCorExpedicaoSolicitadaDTO->retStrSiglaUnidade();
        $objMdCorExpedicaoSolicitadaDTO->retNumIdMdCorPlp();
        $objMdCorExpedicaoSolicitadaDTO->retStrProtocoloFormatadoDocumento();
        $objMdCorExpedicaoSolicitadaDTO->retDblIdDocumentoPrincipal();
        $objMdCorExpedicaoSolicitadaDTO->retStrProtocoloFormatado();
        $objMdCorExpedicaoSolicitadaDTO->retStrSinObjetoAcessado();
        $objMdCorExpedicaoSolicitadaDTO->retStrCodigoRastreamento();
        $objMdCorExpedicaoSolicitadaDTO->retNumIdMdCorExpedicaoSolicitada();
        $objMdCorExpedicaoSolicitadaDTO->retNumIdMdCorExpedicaoSolicitada();
        $objMdCorExpedicaoSolicitadaDTO->retStrSiglaUnidade();
        $objMdCorExpedicaoSolicitadaDTO->retStrNumeroDocumento();
        $objMdCorExpedicaoSolicitadaDTO->retStrProtocoloFormatadoDocumento();
        $objMdCorExpedicaoSolicitadaDTO->retStrProtocoloFormatado();
        $objMdCorExpedicaoSolicitadaDTO->retStrCodigoRastreamento();
        $objMdCorExpedicaoSolicitadaDTO->retNumQuantidadeAnexo();
        $objMdCorExpedicaoSolicitadaDTO->retStrSinNecessitaAr();
        $objMdCorExpedicaoSolicitadaDTO->retDthDataSolicitacao();
        $objMdCorExpedicaoSolicitadaDTO->retDthDataExpedicao();
        $objMdCorExpedicaoSolicitadaDTO->setOrd('IdMdCorExpedicaoSolicitada', InfraDTO::$TIPO_ORDENACAO_ASC);

        $objMdCorExpedicaoSolicitadaDTO->setNumIdMdCorPlp($objMdCorPlpDTO->getNumIdMdPlp());

        PaginaSEI::getInstance()->prepararOrdenacao($objMdCorExpedicaoSolicitadaDTO, 'IdMdCorExpedicaoSolicitada', InfraDTO::$TIPO_ORDENACAO_ASC);
        PaginaSEI::getInstance()->prepararPaginacao($objMdCorExpedicaoSolicitadaDTO, 1000);

        $objMdCorExpedicaoSolicitadaRN = new MdCorExpedicaoSolicitadaRN();
        $arrObjMdCorExpedicaoSolicitadaDTO = $objMdCorExpedicaoSolicitadaRN->retornarExpedicaoPlp($objMdCorExpedicaoSolicitadaDTO);
        PaginaSEI::getInstance()->processarPaginacao($objMdCorExpedicaoSolicitadaDTO);

        $numRegistros = count($arrObjMdCorExpedicaoSolicitadaDTO);

        if ($numRegistros > 0) {
            $strResultado = '';
            $strSumarioTabela = 'Tabela de PLP detalhada';
            $strCaptionTabela = 'PLP detalhada';

            $strResultado .= '<table width="100%" class="infraTable" summary="' . $strSumarioTabela . '">' . "\n";
            $strResultado .= '<caption class="infraCaption">' . PaginaSEI::getInstance()->gerarCaptionTabela($strCaptionTabela, $numRegistros) . '</caption>';
            $strResultado .= '<tr>';
            $strResultado .= '<th class="infraTh" width="15px">' . PaginaSEI::getInstance()->getThCheck() . '</th>' . "\n";
            $strResultado .= '<th class="infraTh">' . PaginaSEI::getInstance()->getThOrdenacao($objMdCorPlpDTO, 'Unidade Solicitante', 'SiglaUnidade', $arrObjMdCorExpedicaoSolicitadaDTO) . '</th>' . "\n";

            $strResultado .= '<th class="infraTh"">' . PaginaSEI::getInstance()->getThOrdenacao($objMdCorPlpDTO, 'Data da Solicitação', 'DataSolicitacao', $arrObjMdCorExpedicaoSolicitadaDTO) . '</th>' . "\n";

            if ($_GET['acao'] == 'md_cor_plp_detalhada') {
                $strResultado .= '<th class="infraTh">' . PaginaSEI::getInstance()->getThOrdenacao($objMdCorPlpDTO, 'Data da Expedição', 'DataExpedicao', $arrObjMdCorExpedicaoSolicitadaDTO) . '</th>' . "\n";
            }

            $strResultado .= '<th class="infraTh">' . PaginaSEI::getInstance()->getThOrdenacao($objMdCorPlpDTO, 'Documento Principal', 'ProtocoloFormatadoDocumento', $arrObjMdCorExpedicaoSolicitadaDTO) . '</th>' . "\n";
            $strResultado .= '<th class="infraTh"><div style="width: 150px">' . PaginaSEI::getInstance()->getThOrdenacao($objMdCorPlpDTO, 'Processo', 'ProtocoloFormatado', $arrObjMdCorExpedicaoSolicitadaDTO) . '</div></th>' . "\n";
            $strResultado .= '<th class="infraTh" width="60px">' . PaginaSEI::getInstance()->getThOrdenacao($objMdCorPlpDTO, 'Rastreamento', 'CodigoRastreamento', $arrObjMdCorExpedicaoSolicitadaDTO) . '</th>' . "\n";
            $strResultado .= '<th class="infraTh" width="20px">' . PaginaSEI::getInstance()->getThOrdenacao($objMdCorPlpDTO, 'Anexos', 'CodigoRastreamento', $arrObjMdCorExpedicaoSolicitadaDTO) . '</th>' . "\n";
            $strResultado .= '<th class="infraTh text-center"><div style="width: 90px; text-align: center">Ações</div></th>' . "\n";

            $strResultado .= '</tr>' . "\n";
            $strCssTr = '';

            for ($i = 0; $i < $numRegistros; $i++) {

                $midia = '';
                if ($arrObjMdCorExpedicaoSolicitadaDTO[$i]->getStrMidia() == 'S') {
                    $midia = '<div style="float: left"><img src="modulos/correios/imagens/svg/media.svg?'.Icone::VERSAO.'" title="PLP possui midia para gravação." style="width: 24px; height: 24px" alt="PLP possui midia para gravação." class="infraImg mr-1" /></div>';
                }

                $strCssTr = ($strCssTr == '<tr class="infraTrClara">') ? '<tr class="infraTrEscura">' : '<tr class="infraTrClara">';

                $strResultado .= $strCssTr;

                $strResultado .= '<td valign="center">' . PaginaSEI::getInstance()->getTrCheck($i, $arrObjMdCorExpedicaoSolicitadaDTO[$i]->getNumIdMdCorExpedicaoSolicitada(), $arrObjMdCorExpedicaoSolicitadaDTO[$i]->getNumIdMdCorExpedicaoSolicitada()) . '</td>';
                $strResultado .= '<td align="center">' . PaginaSEI::tratarHTML($arrObjMdCorExpedicaoSolicitadaDTO[$i]->getStrSiglaUnidade()) . '</td>';

                $strResultado .= '<td align="center">' . PaginaSEI::tratarHTML($arrObjMdCorExpedicaoSolicitadaDTO[$i]->getDthDataSolicitacao()) . '</td>';

                if ($_GET['acao'] == 'md_cor_plp_detalhada') {
                    $dataCadastro = substr($arrObjMdCorExpedicaoSolicitadaDTO[$i]->getDthDataExpedicao(), 0, 10);
                    $strResultado .= '<td align="center">' . PaginaSEI::tratarHTML($dataCadastro) . '</td>';
                }

                $strUrlDocumento = SessaoSEI::getInstance()->assinarLink('controlador.php?acao=procedimento_trabalhar&id_documento=' . $arrObjMdCorExpedicaoSolicitadaDTO[$i]->getDblIdDocumentoPrincipal());

                $strResultado .= '<td align="center">' . PaginaSEI::tratarHTML($arrObjMdCorExpedicaoSolicitadaDTO[$i]->getStrNomeSerie() . ' ' .$arrObjMdCorExpedicaoSolicitadaDTO[$i]->getStrNumeroDocumento() ) . '<a class="protocoloNormal" style="font-size: 1.0em !important; font-size:1em" href="'.$strUrlDocumento.'" target="_blank">(' .PaginaSEI::tratarHTML($arrObjMdCorExpedicaoSolicitadaDTO[$i]->getStrProtocoloFormatadoDocumento() ) . ')</a>' . '</td>';
                $strResultado .= '<td align="center">' . PaginaSEI::tratarHTML($arrObjMdCorExpedicaoSolicitadaDTO[$i]->getStrProtocoloFormatado()) . '</td>';
                $strResultado .= '<td align="center">' . PaginaSEI::tratarHTML($arrObjMdCorExpedicaoSolicitadaDTO[$i]->getStrCodigoRastreamento()) . '</td>';
                $strResultado .= '<td align="center">' . PaginaSEI::tratarHTML($arrObjMdCorExpedicaoSolicitadaDTO[$i]->getNumQuantidadeAnexo() - 1) . $midia . '</td>';
                $strResultado .= '<td align="center" class="'.$arrObjMdCorExpedicaoSolicitadaDTO[$i]->getNumIdMdCorExpedicaoSolicitada().'">';

                if ($arrObjMdCorExpedicaoSolicitadaDTO[$i]->getStrSinObjetoAcessado() == 'S') {
                    $strResultado .= '<a href="' . SessaoSEI::getInstance()->assinarLink('controlador.php?acao=' . $strAcaoTabela . '&acao_origem=' . $_GET['acao'] . '&acao_retorno=' . $_GET['acao'] . '&id_md_expedicao_solicitada=' . $arrObjMdCorExpedicaoSolicitadaDTO[$i]->getNumIdMdCorExpedicaoSolicitada() . '&id_md_cor_plp=' . $_GET['id_md_cor_plp']) . '" tabindex="' . PaginaSEI::getInstance()->getProxTabTabela() . '" class="botaoExpedirObjeto"><img src="modulos/correios/imagens/svg/expedir_objeto.svg?'.Icone::VERSAO.'" title="' . $titleBotao . '" alt="' . $titleBotao . '" class="infraImgAcoes acessado" /></a>';
                } else {
                    $strResultado .= '<a onclick=" setTimeout(function(){substituiIconeExpedirPlp('.$arrObjMdCorExpedicaoSolicitadaDTO[$i]->getNumIdMdCorExpedicaoSolicitada().')}, 500);" href="' . SessaoSEI::getInstance()->assinarLink('controlador.php?acao=' . $strAcaoTabela . '&acao_origem=' . $_GET['acao'] . '&acao_retorno=' . $_GET['acao'] . '&id_md_expedicao_solicitada=' . $arrObjMdCorExpedicaoSolicitadaDTO[$i]->getNumIdMdCorExpedicaoSolicitada() . '&id_md_cor_plp=' . $_GET['id_md_cor_plp']) . '" tabindex="' . PaginaSEI::getInstance()->getProxTabTabela() . '" class="botaoExpedirObjeto"><img src="modulos/correios/imagens/svg/expedir_objeto_ok.svg?'.Icone::VERSAO.'" title="' . $titleBotao . '" alt="' . $titleBotao . '" class="infraImgAcoes" /></a>';
                }

                $strResultado .= '<a href="' . PaginaSEI::getInstance()->montarAncora($arrObjMdCorExpedicaoSolicitadaDTO[$i]->getNumIdMdCorExpedicaoSolicitada()) . '" tabindex="' . PaginaSEI::getInstance()->getProxTabTabela() . '"><img src="modulos/correios/imagens/svg/impressao_rotulo_envelope.svg?'.Icone::VERSAO.'" title="Impressão do Rótulo do Envelope" onclick="imprimirRotuloEnvelope(' . $arrObjMdCorExpedicaoSolicitadaDTO[$i]->getNumIdMdCorExpedicaoSolicitada() . ')"  alt="Impressão do Rótulo do Envelope" class="infraImgAcoes" /></a>';
                if ($arrObjMdCorExpedicaoSolicitadaDTO[$i]->getStrSinNecessitaAr() == 'S') {
                    $strResultado .= '<a href="' . PaginaSEI::getInstance()->montarAncora($arrObjMdCorExpedicaoSolicitadaDTO[$i]->getNumIdMdCorExpedicaoSolicitada()) . '" tabindex="' . PaginaSEI::getInstance()->getProxTabTabela() . '"><img src="modulos/correios/imagens/svg/impressao_ar.svg?'.Icone::VERSAO.'" title="Impressão do AR" onclick="imprimirAR(' . $arrObjMdCorExpedicaoSolicitadaDTO[$i]->getNumIdMdCorExpedicaoSolicitada() . ')" alt="Impressão do AR" class="infraImgAcoes" /></a>';
                }


                $strResultado .= '</td></tr>' . "\n";
            }

            $strLinkAjaxValidarDocumentoAPI = SessaoSEI::getInstance()->assinarLink('controlador_ajax.php?acao_ajax=validar_documento_api');

            $strResultado .= '</table>';
            $strResultado .= '</table>';
        }

        $strComboStatus = MdCorPlpINT::montarSelectStaPlp('null', '&nbsp;', $_POST['txtStatus']);
        $strComboServicoPostal = MdCorPlpINT::montarSelectServicoPostal('null', '&nbsp;', $_POST['selServicoPostal']);
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
require_once "md_cor_plp_detalhada_css.php";
PaginaSEI::getInstance()->fecharHead();
PaginaSEI::getInstance()->abrirBody($strTitulo, 'onload="inicializar();"');
?>
<form id="frmDetalharPlp" method="post"
      action="<?= PaginaSEI::getInstance()->formatarXHTML(SessaoSEI::getInstance()->assinarLink('controlador.php?acao=' . $_GET['acao'] . '&acao_origem=' . $_GET['acao'] . '&id_md_cor_plp=' . $_GET['id_md_cor_plp'])) ?>">
          <?
          PaginaSEI::getInstance()->montarBarraComandosSuperior($arrComandos);
          ?>

    <input type="submit" style="visibility: hidden"/>
    <? PaginaSEI::getInstance()->montarAreaTabela($strResultado, $numRegistros); ?>
    <? PaginaSEI::getInstance()->montarBarraComandosInferior($arrComandos); ?>
</form>
<?
require_once "md_cor_plp_detalhada_js.php";
PaginaSEI::getInstance()->fecharBody();
PaginaSEI::getInstance()->fecharHtml();
?>
