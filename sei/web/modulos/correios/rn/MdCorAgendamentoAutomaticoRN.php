<?
/**
 * TRIBUNAL REGIONAL FEDERAL DA 4� REGI�O
 *
 * 09/06/2017 - criado por jaqueline.mendes
 *
 * Vers�o do Gerador de C�digo: 1.40.0
 */

require_once dirname(__FILE__) . '/../../../SEI.php';

class MdCorAgendamentoAutomaticoRN extends InfraRN
{

    public function __construct()
    {
        parent::__construct();
    }

    protected function inicializarObjInfraIBanco()
    {
        return BancoSEI::getInstance();
    }

    /* M�todo respons�vel por atualizar o andamento dos objs no correios */
    protected function atualizarAndamentoObjetosConectado()
    {

        try {
            ini_set('max_execution_time', '0');
            ini_set('memory_limit', '-1');

            InfraDebug::getInstance()->setBolLigado(true);
            InfraDebug::getInstance()->setBolDebugInfra(false);
            InfraDebug::getInstance()->setBolEcho(false);
            InfraDebug::getInstance()->limpar();

            $numSeg = InfraUtil::verificarTempoProcessamento();
            InfraDebug::getInstance()->gravar('ATUALIZANDO ANDAMENTO DO RASTREAMENTO DOS OBJETOS NOS CORREIOS');

            $objMdCorExpedicaoAndamentoRN = new MdCorExpedicaoAndamentoRN();
            $retornoArrFalhas = $objMdCorExpedicaoAndamentoRN->salvarAndamento();

            $numSeg = InfraUtil::verificarTempoProcessamento($numSeg);

            InfraDebug::getInstance()->gravar('TEMPO TOTAL DE EXECUCAO: ' . $numSeg . ' s');

            if ($retornoArrFalhas) {
                InfraDebug::getInstance()->gravar('OBJETOS COM FALHA');
                foreach ($retornoArrFalhas as $falha) {
                    InfraDebug::getInstance()->gravar($falha['numero'] . ' - ' . $falha['erro']);
                }
            }

            InfraDebug::getInstance()->gravar('FIM');

            LogSEI::getInstance()->gravar(InfraDebug::getInstance()->getStrDebug(), InfraLog::$INFORMACAO);

            InfraDebug::getInstance()->setBolLigado(false);
            InfraDebug::getInstance()->setBolDebugInfra(false);
            InfraDebug::getInstance()->setBolEcho(false);

        } catch (Exception $e) {

            InfraDebug::getInstance()->setBolLigado(false);
            InfraDebug::getInstance()->setBolDebugInfra(false);
            InfraDebug::getInstance()->setBolEcho(false);
            throw new InfraException('Erro atualizando o andamento do rastreamento dos objetos nos Correios.', $e);
        }

    }

    protected function finalizarFluxoArNaoRetornadoConectado()
    {
        ini_set('max_execution_time', '0');
        ini_set('memory_limit', '-1');

        InfraDebug::getInstance()->setBolLigado(true);
        InfraDebug::getInstance()->setBolDebugInfra(false);
        InfraDebug::getInstance()->setBolEcho(false);
        InfraDebug::getInstance()->limpar();

        $numSeg = InfraUtil::verificarTempoProcessamento();
        InfraDebug::getInstance()->gravar('FINALIZANDO FLUXO DE ARS N�O RETORNADOS');


        $objMdCorParametroArRN = new MdCorParametroArRN();
        $objMdCorParametroArDTO = new MdCorParametroArDTO();
        $objMdCorParametroArDTO->setNumMaxRegistrosRetorno(1);
        $objMdCorParametroArDTO->retStrNuDiasPrazoExpRetAr();
        $objMdCorParametroArDTO->retNumIdMdCorParametroAr();

        $objMdCorParametroArDTO = $objMdCorParametroArRN->consultar($objMdCorParametroArDTO);
        if($objMdCorParametroArDTO->getStrNuDiasPrazoExpRetAr() > 0) {
            $dtPermitida = InfraData::calcularData($objMdCorParametroArDTO->getStrNuDiasPrazoExpRetAr(), InfraData::$UNIDADE_DIAS, InfraData::$SENTIDO_ATRAS);

            $mdCorExpedicaoSolicitadaRN = new MdCorExpedicaoSolicitadaRN();
            $mdCorExpedicaoSolicitadaDTO = new MdCorExpedicaoSolicitadaDTO();

            $mdCorExpedicaoSolicitadaDTO->setStrSinRecebido('N');
            $mdCorExpedicaoSolicitadaDTO->setStrSinNecessitaAr('S');
            $mdCorExpedicaoSolicitadaDTO->setDblIdUnidadeExpedidora(SessaoSEI::getInstance()->getNumIdUnidadeAtual());

            $mdCorExpedicaoSolicitadaDTO->retNumIdMdCorExpedicaoSolicitada();
            $mdCorExpedicaoSolicitadaDTO->retDthDataExpedicao();
            $mdCorExpedicaoSolicitadaDTO->retDblIdProtocolo();
            $mdCorExpedicaoSolicitadaDTO->retStrProtocoloFormatado();
            $mdCorExpedicaoSolicitadaDTO->retNumIdUnidade();
            $mdCorExpedicaoSolicitadaDTO->retStrProtocoloFormatadoDocumento();
            $mdCorExpedicaoSolicitadaDTO->retDblIdDocumentoPrincipal();
            $mdCorExpedicaoSolicitadaDTO->retDblIdUnidadeExpedidora();
            $mdCorExpedicaoSolicitadaDTO->retStrCodigoRastreamento();
            $mdCorExpedicaoSolicitadaDTO->retStrDescricaoServicoPostal();
            $mdCorExpedicaoSolicitadaDTO->retStrSinNecessitaAr();
            $mdCorExpedicaoSolicitadaDTO->retStrSinDevolvido();
            $mdCorExpedicaoSolicitadaDTO->retNumIdContatoDestinatario();
            $mdCorExpedicaoSolicitadaDTO->setDthDataExpedicao($dtPermitida, InfraDTO::$OPER_MENOR);

            $arrMdCorExpedicaoSolicitadaDTO = $mdCorExpedicaoSolicitadaRN->listar($mdCorExpedicaoSolicitadaDTO);
            $retornoArrFalhas = [];
            foreach ($arrMdCorExpedicaoSolicitadaDTO as $mdCorExpedicaoSolicitadaDTO){
                $retornoAtualizacao = $this->_adicionarAvisoArNaoRetornado($mdCorExpedicaoSolicitadaDTO, $objMdCorParametroArDTO);
                if($retornoAtualizacao != true) {
                    $retornoArrFalhas[] = $mdCorExpedicaoSolicitadaDTO->getStrProtocoloFormatadoDocumento();
                }
            }
        }

        $numSeg = InfraUtil::verificarTempoProcessamento($numSeg);
        InfraDebug::getInstance()->gravar('TEMPO TOTAL DE EXECUCAO: ' . $numSeg . ' s');
        if ($retornoArrFalhas) {
            InfraDebug::getInstance()->gravar('ALGUNS DOCUMENTOS N�O TIVERAM SEU FLUXO DE AR FINALIZADO DEVIDO A UM ERRO NO PAR�METRO DE INFRIG�NCIA CONTRATUAL. DOCUMENTOS COM FALHA:');
            foreach ($retornoArrFalhas as $falha) {
                InfraDebug::getInstance()->gravar($falha);
            }
        }
        InfraDebug::getInstance()->gravar('FIM');

        LogSEI::getInstance()->gravar(InfraDebug::getInstance()->getStrDebug(), InfraLog::$INFORMACAO);

        InfraDebug::getInstance()->setBolLigado(false);
        InfraDebug::getInstance()->setBolDebugInfra(false);
        InfraDebug::getInstance()->setBolEcho(false);
    }

    private function _adicionarAvisoArNaoRetornado($mdCorExpedicaoSolicitadaDTO, $objMdCorParametroArDTO) {

        $unidadeDTO = new UnidadeDTO();
        $unidadeDTO->retStrDescricao();
        $unidadeDTO->retStrSigla();
        $unidadeDTO->setNumIdUnidade($mdCorExpedicaoSolicitadaDTO->getDblIdUnidadeExpedidora());

        $unidadeRN = new UnidadeRN();
        $unidadeDTO = $unidadeRN->consultarRN0125($unidadeDTO);

        $objMdCorParamArInfrigenRN = new MdCorParamArInfrigenRN();
        $objMdCorParamArInfrigenDTO = new MdCorParamArInfrigenDTO();
        $objMdCorParamArInfrigenDTO->retNumIdMdCorParamArInfrigencia();
        $objMdCorParamArInfrigenDTO->setStrSinAtivo('S');
        $objMdCorParamArInfrigenDTO->setStrSinInfrigencia('S');
        $objMdCorParamArInfrigenDTO->setNumIdMdCorParametroAr($objMdCorParametroArDTO->getNumIdMdCorParametroAr());
        $arrObjMdCorParamArInfrigenRN = $objMdCorParamArInfrigenRN->listar($objMdCorParamArInfrigenDTO);
        if(count($arrObjMdCorParamArInfrigenRN) == 1) {
            $dados["nu_sei"] = $mdCorExpedicaoSolicitadaDTO->getStrProtocoloFormatadoDocumento();
            $dados["idDocumentoPrincipal"] = $mdCorExpedicaoSolicitadaDTO->getDblIdDocumentoPrincipal();
            $dados["hdnRastreamento"] = $mdCorExpedicaoSolicitadaDTO->getStrCodigoRastreamento();
            $dados["dt_ar"] = date("d/m/Y");
            $dados["dt_retorno"] = date("d/m/Y");
            $dados["st_devolvido"] = "on";
            $dados["co_motivo"] = $arrObjMdCorParamArInfrigenRN[0]->getNumIdMdCorParamArInfrigencia();

            $documentoRN = new DocumentoRN();
            $documentoDTO = new DocumentoDTO();
            $documentoDTO->setDblIdDocumento($dados["idDocumentoPrincipal"]);
            $documentoDTO->retDblIdProcedimento();
            $documentoDTO->retStrStaNivelAcessoLocalProtocolo();
            $documentoDTO->retNumIdHipoteseLegalProtocolo();
            $documentoDTO->retStrNomeSerie();
            $documentoDTO->retStrNumero();
            $objRetDocumentoDTO = $documentoRN->consultarRN0005($documentoDTO);

            $objExpedAndamentoRN = new MdCorExpedicaoAndamentoRN();

            $arrObjMdCorExpAndamentoDTO = $objExpedAndamentoRN->getDadosAndamentosParaRastreio($mdCorExpedicaoSolicitadaDTO->getNumIdMdCorExpedicaoSolicitada());
            if (isset($arrObjMdCorExpAndamentoDTO)) {
                $retornoStatusRastreamento = $this->retornaStatusRastreamento($arrObjMdCorExpAndamentoDTO);
            }

            $dadosDestinatario = $this->retornaDadosDestinatario($mdCorExpedicaoSolicitadaDTO);

            $modeloDocumento = $this->definirModeloPdf($retornoStatusRastreamento["statusRastreamento"], $mdCorExpedicaoSolicitadaDTO);

            $nomeArquivo = 'AR-' . $mdCorExpedicaoSolicitadaDTO->getDblIdDocumentoPrincipal();

            if (PaginaSEI::getInstance()->getObjInfraSessao() !== null) {
                $strUsuario = PaginaSEI::getInstance()->getObjInfraSessao()->getStrSiglaUsuario();
            } else {
                $strUsuario = 'anonimo';
            }

            $numTimestamp = time();

            $nomeArquivo = InfraUtil::montarNomeArquivoUpload($strUsuario, $numTimestamp, $nomeArquivo);

            mkdir(DIR_SEI_TEMP . '/' . $nomeArquivo, 0755, true);

            $this->construiPdf($modeloDocumento, $mdCorExpedicaoSolicitadaDTO, $unidadeDTO, $objRetDocumentoDTO, $retornoStatusRastreamento, $dadosDestinatario, $arrObjMdCorExpAndamentoDTO, $nomeArquivo);

            $zip = new ZipArchive();
            $filename = DIR_SEI_TEMP . '/' . $nomeArquivo . '.zip';

            if ($zip->open($filename, ZipArchive::CREATE) !== TRUE) {
                exit("cannot open <$filename>\n");
            }
            $zip->addFile(DIR_SEI_TEMP . '/' . $nomeArquivo . '/' . $nomeArquivo . '.pdf', $nomeArquivo . '.pdf');
            $zip->close();

            $dados["hdnArquivoZip"] = $nomeArquivo . ".zip";
            $dados['hdnStProcessamento'] = array(MdCorRetornoArRN::$STA_RETORNO_AR_NAO_PROCESSADO);
            $dados["no_arquivo"] = array($nomeArquivo . ".pdf");
            $dados["nu_sei"] = array($dados["nu_sei"]);
            $dados["idDocumentoPrincipal"] = array($dados["idDocumentoPrincipal"]);
            $dados["hdnRastreamento"] = array($dados["hdnRastreamento"]);
            $dados["dt_ar"] = array($dados["dt_ar"]);
            $dados["dt_retorno"] = array($dados["dt_retorno"]);
            $dados["st_devolvido"] = array($dados["st_devolvido"]);
            $dados["co_motivo"] = array($dados["co_motivo"]);
            $_SESSION['ARQUIVO_ZIP'] = DIR_SEI_TEMP . '/' . $dados["hdnArquivoZip"];
            $_SESSION['ARQUIVO_PASTA'] = DIR_SEI_TEMP . '/' . $nomeArquivo;
            $mdCorRetornoArDocRN = new MdCorRetornoArDocRN();
            $mdCorRetornoArDocRN->cadastrarArs($dados);
            return true;
        } else {
            return false;
        }
    }
    private function definirModeloPdf($statusRastreamento, $mdCorExpedicaoSolicitadaDTO) {
        if($statusRastreamento == "S") {
            return 1;
        } elseif($statusRastreamento != "S" && $mdCorExpedicaoSolicitadaDTO->getStrSinDevolvido() == "N") {
            return 2;
        }
    }
    private function inserirImagemTopoPdf($pdf, $modeloDocumento) {
        if($modeloDocumento == 1) {
            $pdf->Image('modulos/correios/imagens/png/cabecalhoAr.png',10,10, -200);
            $pdf->Ln(35);
        } else if($modeloDocumento == 2) {
            $pdf->Image('modulos/correios/imagens/png/cabecalhoAr2.png',10,10, -150);
            $pdf->Ln(40);
        }
    }
    private function retornaStatusRastreamento($arrObjMdCorExpAndamentoDTO) {
        if($arrObjMdCorExpAndamentoDTO[0]->getStrStaRastreioModulo()) {
            switch ($arrObjMdCorExpAndamentoDTO[0]->getStrStaRastreioModulo()) {
                case 'P':
                    $nomeImg = "rastreamento_postagem.png";
                    break;
                case 'T':
                    $nomeImg = "rastreamento_em_transito.png";
                    break;
                case 'S':
                    $nomeImg = "rastreamento_sucesso.png";
                    break;
                case 'I':
                    $nomeImg = "rastreamento_cancelado.png";
                    break;
            }
            $caminhoImagem = "modulos/correios/imagens/png/" . $nomeImg;
            $retornoStatusRastreamento = array();
            $retornoStatusRastreamento['caminhoImagem'] = $caminhoImagem;
            $retornoStatusRastreamento['statusRastreamento'] = $arrObjMdCorExpAndamentoDTO[0]->getStrStaRastreioModulo();
            return $retornoStatusRastreamento;
        }
    }

    private function retornaDadosDestinatario($mdCorExpedicaoSolicitadaDTO) {

        $dadosDestinatario = array();

        $mdCorContatoRN = new MdCorContatoRN();
        $mdCorContatoDTO = new MdCorContatoDTO();
        $mdCorContatoDTO->retTodos();

        $mdCorContatoDTO->setNumIdContato($mdCorExpedicaoSolicitadaDTO->getNumIdContatoDestinatario());
        $mdCorContatoDTO->setNumIdMdCorExpedicaoSolicitada($mdCorExpedicaoSolicitadaDTO->getNumIdMdCorExpedicaoSolicitada());

        $arrObjMdCorContatoDTO = $mdCorContatoRN->listar($mdCorContatoDTO);
        $objMdCorContatoDTO = $arrObjMdCorContatoDTO[0];

        $dadosDestinatario["id_destinatario"] = $objMdCorContatoDTO->getNumIdContato();
        $dadosDestinatario["nome_destinatario"] = $objMdCorContatoDTO->getStrNome();
        $dadosDestinatario["cargo_destinatario"] = $objMdCorContatoDTO->getStrExpressaoCargo();
        $dadosDestinatario["tratamento_destinatario"] = $objMdCorContatoDTO->getStrExpressaoTratamentoCargo();
        $dadosDestinatario["endereco_destinatario"] = $objMdCorContatoDTO->getStrEndereco();
        $dadosDestinatario["complemento_destinatario"] = $objMdCorContatoDTO->getStrComplemento();
        $dadosDestinatario["bairro_destinatario"] = $objMdCorContatoDTO->getStrBairro();
        $dadosDestinatario["cep_destinatario"] = $objMdCorContatoDTO->getStrCep();
        $dadosDestinatario["cidade_destinatario"] = $objMdCorContatoDTO->getStrNomeCidade();
        $dadosDestinatario["uf_destinatario"] = $objMdCorContatoDTO->getStrSiglaUf();

        if ($objMdCorContatoDTO->getStrStaNaturezaContatoAssociado() == ContatoRN::$TN_PESSOA_JURIDICA) {
            $dadosDestinatario["nome_destinatario_associado"] = $objMdCorContatoDTO->getStrNomeContatoAssociado();
        }
        return $dadosDestinatario;
    }

    private function construiPdf($modeloDocumento, $mdCorExpedicaoSolicitadaDTO, $unidadeDTO, $objRetDocumentoDTO, $retornoStatusRastreamento, $dadosDestinatario, $arrObjMdCorExpAndamentoDTO, $nomeArquivo) {

        $unidade_exp = $unidadeDTO->getStrDescricao() . " (" . $unidadeDTO->getStrSigla() . ")";

        $objMdCorListaStatusDTO = new MdCorListaStatusDTO();
        $objMdCorListaStatusDTO->retTodos(true);
        $objMdCorListaStatusRN = new MdCorListaStatusRN();
        $pdf = new InfraPDF();
        $pdf->AddPage();
        $pdf->SetTitle($nomeArquivo);
        $pdf->SetFont('Times','',12);
        $this->inserirImagemTopoPdf($pdf, $modeloDocumento);
        $pdf->write(5, 'A ' . $unidade_exp . ' informa que a correspond�ncia associada ao documento principal SEI ' . $mdCorExpedicaoSolicitadaDTO->getDblIdDocumentoPrincipal() . ', c�digo de rastreamento ' . $mdCorExpedicaoSolicitadaDTO->getStrCodigoRastreamento() . ' ');
        $pdf->SetFont('Times', 'U', 12);
        if ($modeloDocumento == 1) {
            $pdf->write(5, 'foi entregue ao destinat�rio,');
        } else {
            $pdf->write(5, 'n�o foi entregue ao destinat�rio,');
        }
        $pdf->SetFont('Times', '', 12);
        $pdf->write(5, ' conforme informa��es do rastreamento eletr�nico dos Correios apresentadas mais abaixo. ');
        $pdf->SetFont('Times', 'U', 12);
        $pdf->write(5, 'Ademais, at� o presente momento, a correspond�ncia tamb�m n�o foi devolvida pelos Correios � Anatel.');
        $pdf->Ln(10);
        $pdf->SetFont('Times', '', 12);
        $pdf->write(5, 'Em que pese v�rias tratativas e reclama��es junto aos Correios e considerando o tempo passado desde a expedi��o da correspond�ncia, ');
        $pdf->SetFont('Times', 'U', 12);
        if ($modeloDocumento == 1) {
            $pdf->write(5, 'n�o temos perspectiva de que o AR em particular seja retornado.');
        } else {
            $pdf->write(5, 'n�o temos perspectiva de que a correspond�ncia seja devolvida');
        }
        $pdf->Ln(10);
        $pdf->SetFont('Times', 'B', 11);
        $pdf->write(5, 'DESTA FORMA, A �REA DEVE AVALIAR SE AS INFORMA��ES DE RASTREAMENTO ELETR�NICO DO OBJETO S�O SUFICIENTE PARA O PROCEDIMENTO ESPEC�FICO NO PROCESSO OU SE � O CASO DE SOLICITAR A EMISS�O DE UMA NOVA CORRESPOND�NCIA, CASO O RETORNO DO AR SEJA IMPRESCIND�VEL PARA O PROCESSO.');
        $pdf->Ln(10);
        $this->incluiDetalhamentoRastreamento($pdf, $objRetDocumentoDTO, $mdCorExpedicaoSolicitadaDTO, $dadosDestinatario, $retornoStatusRastreamento, $arrObjMdCorExpAndamentoDTO, $objMdCorListaStatusDTO, $objMdCorListaStatusRN, $modeloDocumento);
        $pdf->SetFont('Times', 'B', 11);
        $pdf->write(5, 'Observa��o:');
        $pdf->SetFont('Times', '', 12);
        if ($modeloDocumento == 1) {
            $pdf->write(5, ' Na hip�tese de o Aviso de Recebimento em quest�o ser retornado pelos Correios ap�s a emiss�o deste alerta, ');
        } else {
            $pdf->write(5, ' Na hip�tese de o objeto em quest�o ser devolvido pelos Correios ap�s a emiss�o deste alerta, ');
        }
        $pdf->SetFont('Times', 'U', 12);
        $pdf->write(5, 'ser� ent�o inclu�do como documento externo no processo.');
        return $pdf->Output(DIR_SEI_TEMP . '/'.$nomeArquivo.'/'.$nomeArquivo.'.pdf', 'F');
    }
    private function incluiDetalhamentoRastreamento($pdf, $objRetDocumentoDTO, $mdCorExpedicaoSolicitadaDTO, $dadosDestinatario, $retornoStatusRastreamento, $arrObjMdCorExpAndamentoDTO, $objMdCorListaStatusDTO, $objMdCorListaStatusRN, $modeloDocumento) {
        $pdf->SetFont('Times','B',12);
        $pdf->write(5, 'Detalhar Rastreamento do Objeto');
        $pdf->Ln(10);
        $pdf->SetFont('Times','',10);
        $pdf->write(5, 'Documento Principal: '.$objRetDocumentoDTO->getStrNomeSerie().' '.$objRetDocumentoDTO->getStrNumero().' ('.$mdCorExpedicaoSolicitadaDTO->getStrProtocoloFormatadoDocumento().')');
        $pdf->Ln();
        $pdf->write(5, 'C�digo de Rastreamento: '.$mdCorExpedicaoSolicitadaDTO->getStrCodigoRastreamento());
        $pdf->Ln();
        $pdf->write(5, 'Servi�o Postal: '.$mdCorExpedicaoSolicitadaDTO->getStrDescricaoServicoPostal());
        $pdf->SetFont('Times','B',10);
        $pdf->write(5, ' (Com Aviso de Recebimento)');
        $pdf->SetFont('Times','',10);
        $pdf->Ln(5);
        if($modeloDocumento != 3) {
            $pdf->Rect(10, 135, 190, 25);
        } else {
            $pdf->Rect(10, 95, 190, 25);
        }
        $pdf->Cell(0,5,'Destinat�rio',0);
        $pdf->Ln();
        if($dadosDestinatario["tratamento_destinatario"] != "") {
            $pdf->Cell(0,5,$dadosDestinatario["tratamento_destinatario"],0);
            $pdf->Ln();
        }
        $pdf->Cell(0,5,InfraString::transformarCaixaAlta($dadosDestinatario["nome_destinatario"]),0);
        $pdf->Ln();
        if($dadosDestinatario["cargo_destinatario"] != "") {
            $pdf->Cell(0,5,$dadosDestinatario["cargo_destinatario"],0);
            $pdf->Ln();
        }
        if($dadosDestinatario["nome_destinatario_associado"] != "") {
            $pdf->Cell(0,5,$dadosDestinatario["nome_destinatario_associado"],0);
            $pdf->Ln(5);
        }
        $pdf->Write(5, $dadosDestinatario["endereco_destinatario"].', ');
        if($dadosDestinatario["complemento_destinatario"] != "") {
            $pdf->Write(5,  $dadosDestinatario["complemento_destinatario"] . ', ');
        }
        $pdf->Write(5, $dadosDestinatario["bairro_destinatario"]);
        $pdf->Ln();
        $pdf->Cell(0,5,'CEP: ' . $dadosDestinatario["cep_destinatario"] . ' - ' . $dadosDestinatario["cidade_destinatario"] . '/' . $dadosDestinatario["uf_destinatario"],0);
        $pdf->Ln();
        $pdf->Ln(10);
        if($retornoStatusRastreamento) {
            $pdf->Image($retornoStatusRastreamento["caminhoImagem"], 65, 160, -200);
            $pdf->Ln(25);
        }
        $pdf->SetFont('Times','',9);
        if($arrObjMdCorExpAndamentoDTO) {
            $pdf->Cell(0,5,'Data da �ltima Atualiza��o: '.$arrObjMdCorExpAndamentoDTO[0]->getDthDataUltimaAtualizacao(),0, 0, 'C');
            $pdf->Ln(5);
            $quantidadeEventos = 0;
            foreach ($arrObjMdCorExpAndamentoDTO as $objDTO) {
                $quantidadeEventos++;
                $objMdCorListaStatusDTO->setNumStatus($objDTO->getNumStatus(), InfraDTO::$OPER_IGUAL);
                $objMdCorListaStatusDTO->setStrTipo('' . $objDTO->getStrTipo() . '', InfraDTO::$OPER_IGUAL);
                $objMdCorListaStatusDTO->setStrStaRastreioModulo('', InfraDTO::$OPER_DIFERENTE);
                $objMdCorListaStatusDTO->setStrSinAtivo('S');
                $arrObjMdCorListaStatusDTO = $objMdCorListaStatusRN->listar($objMdCorListaStatusDTO);
                $sinObjetoStatosSRO = false;

                if (count($arrObjMdCorListaStatusDTO) > 0) {

                    $sinObjetoStatosSRO = $arrObjMdCorListaStatusDTO[0]->getStrSinAtivo();
                }

                $dtHrFormt = !is_null($objDTO->getDthDataHora()) ? explode(' ', $objDTO->getDthDataHora()) : null;
                $cidadeUf = !is_null($objDTO->getStrCidade()) ? $objDTO->getStrCidade() : '';
                $cidadeUf .= !is_null($objDTO->getStrUf()) ? ' / ' . $objDTO->getStrUf() : '';
                if ($sinObjetoStatosSRO == 'S') {
                    $pdf->Cell(0, 5, '------------------------------------------------------------------------------------------------------------------------------------------------------------------------------');
                    $pdf->Ln(5);
                    if ($dtHrFormt) {
                        $pdf->write(0, $dtHrFormt[0] . ' ');
                    }
                    $pdf->SetFont('Times', 'B', 9);
                    $pdf->write(0, $objDTO->getStrDescricao());
                    $pdf->SetFont('Times', '', 9);
                    $pdf->Ln();
                    if ($dtHrFormt) {
                        $pdf->Cell(0, 10, $dtHrFormt[1]);
                        $pdf->Ln(5);
                    }
                    $pdf->Cell(0, 10, $cidadeUf);
                    $pdf->Ln(5);
                }
            }
        }
        $pdf->Ln(5);
    }
}
