<?
/**
* TRIBUNAL REGIONAL FEDERAL DA 4º REGIAO
*
* 07/06/2017 - criado por marcelo.cast
*
* Versao do Gerador de Codigo: 1.40.1
*/

require_once dirname(__FILE__) . '/../../../SEI.php';

class MdCorExpedicaoSolicitadaINT extends InfraINT {

  public static function montarSelectIdMdCorExpedicaoSolicitada($strPrimeiroItemValor, $strPrimeiroItemDescricao, $strValorItemSelecionado){
    $objMdCorExpedicaoSolicitadaDTO = new MdCorExpedicaoSolicitadaDTO();
    $objMdCorExpedicaoSolicitadaDTO->retNumIdMdCorExpedicaoSolicitada();
    $objMdCorExpedicaoSolicitadaDTO->retNumIdMdCorExpedicaoSolicitada();

    $objMdCorExpedicaoSolicitadaDTO->setOrdNumIdMdCorExpedicaoSolicitada(InfraDTO::$TIPO_ORDENACAO_ASC);

    $objMdCorExpedicaoSolicitadaRN = new MdCorExpedicaoSolicitadaRN();
    $arrObjMdCorExpedicaoSolicitadaDTO = $objMdCorExpedicaoSolicitadaRN->listar($objMdCorExpedicaoSolicitadaDTO);

    return parent::montarSelectArrInfraDTO($strPrimeiroItemValor, $strPrimeiroItemDescricao, $strValorItemSelecionado, $arrObjMdCorExpedicaoSolicitadaDTO, 'IdMdCorExpedicaoSolicitada', 'IdMdCorExpedicaoSolicitada');
  }


  public static function montarSelectDocumentoPrincipalSol($strPrimeiroItemValor, $strPrimeiroItemDescricao, $strValorItemSelecionado){
    $objMdCorExpedicaoSolicitadaRN = new MdCorExpedicaoSolicitadaRN();

    $objMdCorExpedicaoSolicitadaDTO = new MdCorExpedicaoSolicitadaDTO();
    $objMdCorExpedicaoSolicitadaDTO->setOrdStrNomeSerie(InfraDTO::$TIPO_ORDENACAO_ASC);
    $objMdCorExpedicaoSolicitadaDTO->retNumIdMdCorExpedicaoSolicitada();
    $objMdCorExpedicaoSolicitadaDTO->retDblIdDocumentoPrincipal();
    $objMdCorExpedicaoSolicitadaDTO->retNumIdSerie();
    $objMdCorExpedicaoSolicitadaDTO->retStrNomeSerie();
    $objMdCorExpedicaoSolicitadaDTO->retStrNumeroDocumento();
    $objMdCorExpedicaoSolicitadaDTO->retDblIdProtocoloDocumento();
    $objMdCorExpedicaoSolicitadaDTO->retStrProtocoloFormatadoDocumento();
    $objMdCorExpedicaoSolicitadaDTO->setDblIdProtocolo($_GET['id_procedimento']);

    $arrObjDTO = $objMdCorExpedicaoSolicitadaRN->listar($objMdCorExpedicaoSolicitadaDTO);
    
    foreach($arrObjDTO as $key => $objDTO){
        $docFormatado = $objDTO->getStrNomeSerie().' '.$objDTO->getStrNumeroDocumento() .' ('.$objDTO->getStrProtocoloFormatadoDocumento().')';
        $arrObjDTO[$key]->setStrDocSerieFormatados($docFormatado);
    }

    return parent::montarSelectArrInfraDTO($strPrimeiroItemValor, $strPrimeiroItemDescricao, $strValorItemSelecionado, $arrObjDTO, 'IdMdCorExpedicaoSolicitada', 'DocSerieFormatados');
  }

  public static function montarSelectServicoPostal($strPrimeiroItemValor, $strPrimeiroItemDescricao, $strValorItemSelecionado){
    $objMdCorExpedicaoSolicitadaRN = new MdCorExpedicaoSolicitadaRN();

    $objMdCorExpedicaoSolicitadaDTO = new MdCorExpedicaoSolicitadaDTO();
    $objMdCorExpedicaoSolicitadaDTO->retNumIdMdCorServicoPostal();
    $objMdCorExpedicaoSolicitadaDTO->retStrNomeServicoPostal();
    $objMdCorExpedicaoSolicitadaDTO->setDistinct(true);

    $arrObjDTO = $objMdCorExpedicaoSolicitadaRN->listar($objMdCorExpedicaoSolicitadaDTO);

    return parent::montarSelectArrInfraDTO($strPrimeiroItemValor, $strPrimeiroItemDescricao, $strValorItemSelecionado, $arrObjDTO, 'IdMdCorServicoPostal', 'NomeServicoPostal');
  }

  /**
   * Metodo no qual busca os serviços postais que não tem nenhuma plp vinculado
   * @return montarSelectArrInfraDTO
   * @param $strPrimeiroItemValor
   * @param $strPrimeiroItemDescricao
   * @param $strValorItemSelecionado
  */
  public static function montarSelectServicoPostalPLPNaoVinculada($strPrimeiroItemValor, $strPrimeiroItemDescricao, $strValorItemSelecionado = null){
    $objMdCorExpedicaoSolicitadaRN = new MdCorExpedicaoSolicitadaRN();

    $objMdCorExpedicaoSolicitadaDTO = new MdCorExpedicaoSolicitadaDTO();
    $objMdCorExpedicaoSolicitadaDTO->retNumIdMdCorServicoPostal();
    $objMdCorExpedicaoSolicitadaDTO->retStrNomeServicoPostal();
    $objMdCorExpedicaoSolicitadaDTO->retStrDescricaoServicoPostal();
    $objMdCorExpedicaoSolicitadaDTO->setDistinct(true);
    $objMdCorExpedicaoSolicitadaDTO->setNumIdMdCorPlp(NULL);

    $arrObjDTO = $objMdCorExpedicaoSolicitadaRN->listar($objMdCorExpedicaoSolicitadaDTO);

    return parent::montarSelectArrInfraDTO($strPrimeiroItemValor, $strPrimeiroItemDescricao, $strValorItemSelecionado, $arrObjDTO, 'IdMdCorServicoPostal', 'DescricaoServicoPostal');
  }

  /**
   * Metodo no qual busca as unidades solicitantes que não tem nenhuma plp vinculado
   * @return montarSelectArrInfraDTO
   * @param $strPrimeiroItemValor
   * @param $strPrimeiroItemDescricao
   * @param $strValorItemSelecionado
  */
  public static function montarSelectUnidadeSolicitantePLPNaoVinculada($strPrimeiroItemValor, $strPrimeiroItemDescricao, $strValorItemSelecionado = null){
    $objMdCorExpedicaoSolicitadaRN = new MdCorExpedicaoSolicitadaRN();

    $objMdCorExpedicaoSolicitadaDTO = new MdCorExpedicaoSolicitadaDTO();
    $objMdCorExpedicaoSolicitadaDTO->retNumIdUnidade();
    $objMdCorExpedicaoSolicitadaDTO->retStrSiglaUnidade();
    $objMdCorExpedicaoSolicitadaDTO->setDistinct(true);
    $objMdCorExpedicaoSolicitadaDTO->setNumIdMdCorPlp(NULL);

    $arrObjDTO = $objMdCorExpedicaoSolicitadaRN->listar($objMdCorExpedicaoSolicitadaDTO);

    return parent::montarSelectArrInfraDTO($strPrimeiroItemValor, $strPrimeiroItemDescricao, $strValorItemSelecionado, $arrObjDTO, 'IdUnidade', 'SiglaUnidade');
  }

  /**
   * Metodo que recupera os dados das solicitacoes de expedicacao pendentes que não tem nenhuma plp vinculado
   * @return montarSelectArrInfraDTO
   */
  public static function montarTableSolicExpedicaoPendente($post = null, $idUnidadeAtual = null){

      $objMdCorExpedicaoSolicitadaDTO = new MdCorExpedicaoSolicitadaDTO();

      $objMdCorExpedicaoSolicitadaDTO->setDblIdUnidadeExpedidora($idUnidadeAtual);

      if(!is_null($post) && !empty($post)){
        if ($post['slServicoPostal'] != 'null') {
            $objMdCorExpedicaoSolicitadaDTO->setNumIdMdCorServicoPostal($post['slServicoPostal'], InfraDTO::$OPER_IGUAL);
        }
        if ($post['slUnidadeSolicitante'] != 'null') {
            $objMdCorExpedicaoSolicitadaDTO->setNumIdUnidade($post['slUnidadeSolicitante'], InfraDTO::$OPER_IGUAL);
        }
        if (!empty($post['txtDocumentoPrincipal']) && strlen($post['txtDocumentoPrincipal'] > 0)) {
            $objMdCorExpedicaoSolicitadaDTO->setStrProtocoloFormatadoDocumento('%'.$post['txtDocumentoPrincipal'].'%', InfraDTO::$OPER_LIKE);
        }

        if (!empty($post['txtProcesso']) && strlen($post['txtProcesso'] > 0)) {
            $objMdCorExpedicaoSolicitadaDTO->setStrProtocoloFormatado('%'.$post['txtProcesso'].'%', InfraDTO::$OPER_LIKE);
        }
      }
      $objMdCorExpedicaoSolicitadaRN = new MdCorExpedicaoSolicitadaRN();
      $objMdCorExpedicaoSolicitadaDTO->retNumIdMdCorExpedicaoSolicitada();
      $objMdCorExpedicaoSolicitadaDTO->retDblIdUnidadeExpedidora();
      $objMdCorExpedicaoSolicitadaDTO->retNumIdUnidade();
      $objMdCorExpedicaoSolicitadaDTO->retStrNomeSerie();
      $objMdCorExpedicaoSolicitadaDTO->retStrSiglaUnidade();
      $objMdCorExpedicaoSolicitadaDTO->retStrNomeServicoPostal();
      $objMdCorExpedicaoSolicitadaDTO->retStrDescricaoServicoPostal();
      $objMdCorExpedicaoSolicitadaDTO->retDblIdDocumentoPrincipal();
      $objMdCorExpedicaoSolicitadaDTO->retDthDataSolicitacao();
      $objMdCorExpedicaoSolicitadaDTO->retStrProtocoloFormatadoDocumento();
      $objMdCorExpedicaoSolicitadaDTO->retStrProtocoloFormatado();
      $objMdCorExpedicaoSolicitadaDTO->retStrNumeroDocumento();
      $objMdCorExpedicaoSolicitadaDTO->retNumIdContatoDestinatario();
      $objMdCorExpedicaoSolicitadaDTO->retStrNomeDestinatario();
      $objMdCorExpedicaoSolicitadaDTO->setDistinct(true);
      $objMdCorExpedicaoSolicitadaDTO->setNumIdMdCorPlp(NULL);
      $objMdCorExpedicaoSolicitadaDTO->setStrSinDevolvido("N");
      $objMdCorExpedicaoSolicitadaDTO->setOrdStrNomeServicoPostal(InfraDTO::$TIPO_ORDENACAO_ASC);
      $objMdCorExpedicaoSolicitadaDTO->setOrdDthDataSolicitacao(InfraDTO::$TIPO_ORDENACAO_DESC);

      $arrObjDTO = $objMdCorExpedicaoSolicitadaRN->listarSolicitacaoExpedicaoPendente($objMdCorExpedicaoSolicitadaDTO);

      return $arrObjDTO;
  }

    public static function montarCodigoBarra($texto, $tipoCode, $largura = 1, $larguraImagem = null,  $alturaImagem = 50){

        if($larguraImagem === null){
            $larguraImagem = (13*strlen($texto)+30)*$largura;
        }
        //preparando a imagem do codigo de barra
        $nomeArquivo = InfraCodigoBarras::gerar($texto, DIR_SEI_TEMP, $tipoCode, InfraCodigoBarras::$COR_PRETO, $largura, $alturaImagem, 0, $larguraImagem, $alturaImagem, InfraCodigoBarras::$FORMATO_PNG);
        $strArquivoCodigoBarras = DIR_SEI_TEMP.'/'.$nomeArquivo;
        $fp = fopen($strArquivoCodigoBarras, "r");
        $imgCodigoBarras = fread($fp, filesize($strArquivoCodigoBarras));
        fclose($fp);
        unlink($strArquivoCodigoBarras);

        return base64_encode($imgCodigoBarras);
    }

  public function montarQrCode($texto){
      $objAnexoRN = new AnexoRN();
      $strArquivoQRCaminhoCompleto = DIR_SEI_TEMP.'/'.$objAnexoRN->gerarNomeArquivoTemporario();
      //preparando a imagem do codigo de barra
      InfraQRCode::gerar($texto,$strArquivoQRCaminhoCompleto,'L',5,1);
      $fp = fopen($strArquivoQRCaminhoCompleto, "r");
      $imgQRCode = fread($fp, filesize($strArquivoQRCaminhoCompleto));
      fclose($fp);
      unlink($strArquivoQRCaminhoCompleto);

      return base64_encode($imgQRCode);
  }

    public function montarQrCodeCorreio(MdCorExpedicaoSolicitadaDTO $objMdCorExpedicaoSolicitadaDTO){
        $texto = '';
        $complementoCEPDestino = 0;
        $complementoCEPOrigem = 0;
        $servicoAdicionais = '';

        $texto .= str_replace('-','',$objMdCorExpedicaoSolicitadaDTO->getStrCepDestinatario());

        //Complemento do CEP: Numero do logradouro.
        //Ex1: 00100
        //Ex2: 01200
        //Ex3: 00000 (Quando a informacao for ?S/N?, ?BR 101?, ?KM 5?)
        if(is_int($objMdCorExpedicaoSolicitadaDTO->getStrComplementoDestinatario()))
            $complementoCEPDestino = $objMdCorExpedicaoSolicitadaDTO->getStrComplementoDestinatario();

        $texto .= str_pad($complementoCEPDestino, 5, "0", STR_PAD_LEFT);
        $texto .= str_replace('-','',$objMdCorExpedicaoSolicitadaDTO->getStrCepContratoOrgao());

        //Complemento do CEP: Numero do logradouro.
        if(is_int($objMdCorExpedicaoSolicitadaDTO->getStrComplementoContratoOrgao()))
            $complementoCEPOrigem = $objMdCorExpedicaoSolicitadaDTO->getStrComplementoContratoOrgao();

        $texto .= str_pad($complementoCEPOrigem, 5, "0", STR_PAD_LEFT);

        //Validador do CEP Destino
        $texto .= self::validadorCEP(str_replace('-','',$objMdCorExpedicaoSolicitadaDTO->getStrCepDestinatario()));

        //IDV eh Identificador de Dados Variaveis 51 - encomenda e 81 - Malotes
        $texto .= 51;

        //etiquetas
        $texto .= $objMdCorExpedicaoSolicitadaDTO->getStrCodigoRastreamento();

        //Servicos Adicionais - eh obrigatorio informar o codigo 25 de servico adicional.
        $servicoAdicionais .= '25';

        //01 Aviso de Recebimento
        if($objMdCorExpedicaoSolicitadaDTO->getStrSinNecessitaAr() == 'S' )
            $servicoAdicionais .= '01';

        $texto .= str_pad($servicoAdicionais , 12, "0");

        //cartao de postagem
        $texto .= $objMdCorExpedicaoSolicitadaDTO->getStrCartaoPostagem();

        //codigo do servico
        $texto .= $objMdCorExpedicaoSolicitadaDTO->getStrCodigoWsCorreioServico();

        //Informacao de Agrupamento - nao possui agrupamento
        $texto .= '00';

        //Numero do Logradouro: Numero do endereco(no SEI nao possui)
        $texto .= '00000';

        //Complemento do Endereco: Informacao adicional do endereco
        $texto .= $objMdCorExpedicaoSolicitadaDTO->getStrComplementoDestinatario();

        //Valor Declarado: Numero inteiro - o produto nao possui valor
        $texto .= '00000';

        //DDD + Telefone Destinatario: Numero do telefone do destinatario - nao possui na md_cor_contato
        $texto .= '000000000000';

        //Latitude: Resevado para futura implementacao
        $texto .= '-00.000000';
        //Longitude: Resevado para futura implementacao.
        $texto .= '-00.000000';

        //$PIPE - a partir daqui Reservado para cliente
        $texto .= '|';

        return self::montarQrCode($texto);
    }

    /**
     * retirado do http://www.corporativo.correios.com.br/encomendas/sigepweb/doc/Manual_de_Implementacao_do_Web_Service_SIGEP_WEB.pdf pagina 31
     * Soma dos 8 dígitos do CEP de destino:
     * Subtrai-se o resultado da soma do multiplo de 10, imediatamente superior ao resultado.
     * Ex: CEP: 71010050 eh 7+1+0+1+0+0+5+0 = 14
     * Subtrai-se 14 e 20.
     * O validador do CEP do exemplo eh 6.
     */
    public function validadorCEP($cep){

        $validador = 0;
        for ($i = 0; $i < strlen($cep); $i++){
            $validador += $cep[$i];
        }

        return (ceil($validador/10))*10;
    }

    /**
     * @description Funcao que ira validar se todos os itens do Destinatario estao preenchidos
     * @dataProvider 05-02-2021
     * @author Lino - Felipe Lino <felipe.silva@gt1tecnologia.com.br>
     * @param $idContato - Id do contato que estara querendo validar
     * @param false $bolEntrada - Caso nao tenha sido chamada pelo botao "Solicitar Expedicao" no Documento, esse parametro sempre sera falso
     * @return string Sera retornado uma string ou uma mensagem de alerta
     * @throws InfraException Caso tenha um erro na chamada Soap ele ira dar a excecao.
     */
    public static function validaContatoPreeenchido($idContato, $bolEntrada = false){

        try {
            if($bolEntrada) {
                $str_msg_validacao = 'O Destinatário deste documento está com dados cadastrais incompletos. <br>Acesse o botão de ação sobre o documento "Consultar/Alterar Documento" para editar o Contato indicado como Destinatário e preencha os campos abaixo:<br>';
            } else {
                $str_msg_validacao = "O Destinatário deste documento está com dados cadastrais incompletos. \nAcesse o botão de ação sobre o documento \"Consultar/Alterar Documento\" para editar o Contato indicado como Destinatário e preencha os campos abaixo:\n";
            }
            $erros = array();
            $id_contato = $idContato;
            $contatoRN = new ContatoRN();
            $contatoDTO = new ContatoDTO();
            $contatoDTO->retTodos();
            $contatoDTO->retNumIdTipoContato();
            $contatoDTO->setNumIdContato($id_contato);
            $contatoDTO = $contatoRN->consultarRN0324($contatoDTO);

            if ( is_null($contatoDTO) ) {
                if($bolEntrada) {
                    $str_msg_validacao = 'O Destinatário deste documento está com cadastro de Contato desativado. <br><br>Acesse o botão de ação "Consultar/Alterar Documento" sobre o documento para trocar o Contato indicado como Destinatário por um contato ativo.';
                } else {
                    $str_msg_validacao = "O Destinatário deste documento está com cadastro de Contato desativado. \n\nAcesse o botão de ação \"Consultar/Alterar Documento\" sobre o documento para trocar o Contato indicado como Destinatário por um contato ativo.";
                }
            }

            self::validarDestinatarioIntimacaoEletronica($contatoDTO, $bolEntrada);

            if ($contatoDTO->getStrSinEnderecoAssociado() == 'S') {
                $contatoAssociadoDTO = new ContatoDTO();
                $contatoAssociadoDTO->retTodos();
                $contatoAssociadoDTO->setNumIdContato($contatoDTO->getNumIdContatoAssociado());
                $contatoDTO = $contatoRN->consultarRN0324($contatoAssociadoDTO);
            }

            // checar Natureza, Endereco, Bairro, Estado, Cidade, CEP
            $nome = $contatoDTO->getStrNome();
            $idCargo = $contatoDTO->getNumIdCargo();
            $natureza = $contatoDTO->getStrStaNatureza();
            $genero = $contatoDTO->getStrStaGenero();
            $endereco = $contatoDTO->getStrEndereco();
            $bairro = $contatoDTO->getStrBairro();

            // critica qtd de caracteres do endereço, pois na API dos Correios, o logradouro aceita somente ate 50 caracteres
            if (strlen($endereco) > 50) {
                $msgValidacaoQtdLogra = "O campo 'Endereço' do destinatário extrapolou os 50 caracteres aceitos pelos Correios, o que pode implicar em insucesso na entrega do objeto postal. Dessa forma, revise o campo 'Endereço' do destinatário para que tenha até 50 caracteres antes de fazer uma nova solicitação de expedição";
                if($bolEntrada){
                    return $msgValidacaoQtdLogra;
                } else {
                    return "<item><flag>false</flag><mensagem>" . $msgValidacaoQtdLogra . "</mensagem></item>";
                }
            }

            // retorna o nome da UF
            $uf = $contatoDTO->getNumIdUf();
            $objUFDTO = new UfDTO();
            $objUFDTO->setNumIdUf($uf);
            $objUFDTO->retStrNome();
            $objUFDTO->retStrSigla();
            $objUFDTO = ( new UfRN() )->consultarRN0400($objUFDTO);

            // retorna nome da Cidade
            $idCidade = $contatoDTO->getNumIdCidade();
            $objCidadeDTO = new CidadeDTO();
            $objCidadeDTO->setNumIdCidade($idCidade);
            $objCidadeDTO->retStrNome();
            $objCidadeDTO = ( new CidadeRN() )->consultarRN0409($objCidadeDTO);

            $pais = $contatoDTO->getNumIdPais();
            $cep = preg_replace("/[^0-9]+/", '', $contatoDTO->getStrCep());

            $orgaoRN = new OrgaoRN();
            $orgaoDTO = new OrgaoDTO();
            $orgaoDTO->setNumIdOrgao(SessaoSEI::getInstance()->getNumIdOrgaoUnidadeAtual());
            $orgaoDTO->setStrSinAtivo('S');
            $orgaoDTO->retNumIdContato();
            $orgaoDTO = $orgaoRN->consultarRN1352($orgaoDTO);

            $contatoOrgaoDTO = new ContatoDTO();
            $contatoOrgaoDTO->retStrEndereco();
            $contatoOrgaoDTO->retStrBairro();
            $contatoOrgaoDTO->retStrCep();
            $contatoOrgaoDTO->retNumIdCidade();
            $contatoOrgaoDTO->retNumIdUf();
            $contatoOrgaoDTO->setNumIdContato($orgaoDTO->getNumIdContato());
            $contatoOrgaoDTO = $contatoRN->consultarRN0324($contatoOrgaoDTO);

            $enderecoOrgao = $contatoOrgaoDTO->getStrEndereco();
            $bairroOrgao = $contatoOrgaoDTO->getStrBairro();
            $idCidadeOrgao = $contatoOrgaoDTO->getNumIdCidade();
            $ufOrgao = $contatoOrgaoDTO->getNumIdUf();
            $cepOrgao = preg_replace("/[^0-9]+/", '', $contatoOrgaoDTO->getStrCep());
            $enderecoOrgaoIncompleto = false;

            $objRelContJustificativa = new MdCorRelContatoJustificativaDTO();
            $objRelContJustificativa->retStrNomeJustificativa();
            $objRelContJustificativa->setNumIdContato($idContato);

            $objRelContJustRN = new MdCorRelContatoJustificativaRN();
            $objRelContJust = $objRelContJustRN->consultar($objRelContJustificativa);

            if (is_null($objRelContJust)) {
                $objRelContJustificativa->setNumIdContato($contatoDTO->getNumIdContatoAssociado());
                $objRelContJust = $objRelContJustRN->consultar($objRelContJustificativa);
            }
            $qtdObjRelContJust = (is_array($objRelContJust) ? count($objRelContJust) : 0);
            if ($qtdObjRelContJust > 0) {

                if($bolEntrada){
                    $srt_msg_validacao_justificativa = 'O Destinatário do Documento não pode receber Expedições pelos Correios pelo seguinte motivo: <br><br>- ' . $objRelContJust->getStrNomeJustificativa();
                    return $srt_msg_validacao_justificativa;
                } else {
                    $srt_msg_validacao_justificativa = "O Destinatário do Documento não pode receber Expedições pelos Correios pelo seguinte motivo: \n\n- " . $objRelContJust->getStrNomeJustificativa();
                    return "<item><flag>false</flag><mensagem>" . $srt_msg_validacao_justificativa . "</mensagem></item>";
                }
            }

            $objInfraParametroDTO = new InfraParametroDTO();
            $objInfraParametroDTO->retStrValor();
            $objInfraParametroDTO->setStrNome('%ID_TIPO_CONTATO%', InfraDTO::$OPER_LIKE);
            $objInfraParametroDTO->adicionarCriterio(array('Nome'), array(InfraDTO::$OPER_NOT_LIKE), array('%ID_TIPO_CONTATO_USUARIOS_EXTERNOS'));

            $objInfraParametroRN = new InfraParametroRN();
            $arrObjInfraParametroDTO = $objInfraParametroRN->listar($objInfraParametroDTO);
            $arrIdTipoContato = InfraArray::converterArrInfraDTO($arrObjInfraParametroDTO, 'Valor');

            if (in_array($contatoDTO->getNumIdTipoContato(), $arrIdTipoContato)) {

                if($bolEntrada){
                    $str_msg_validacao_Tipo_contato = 'O Tipo de Contato do Destinatário ou da Pessoa Jurídica Associada não permite Expedição pelos Correios. Por exemplo, está utilizando o Tipo de Contato Temporário ou Usuário Externo. <br><br>Revise o Contato para classificá-lo em Tipo de Contato adequado ou realize a expedição por meio de Intimação Eletrônica.';
                    return $str_msg_validacao_Tipo_contato;
                } else {
                    $str_msg_validacao_Tipo_contato = "O Tipo de Contato do Destinatário ou da Pessoa Jurídica Associada não permite Expedição pelos Correios. Por exemplo, está utilizando o Tipo de Contato Temporário ou Usuário Externo. \n\nRevise o Contato para classificá-lo em Tipo de Contato adequado ou realize a expedição por meio de Intimação Eletrônica.";
                    return "<item></item><flag>false</flag><mensagem>" . $str_msg_validacao_Tipo_contato . "</mensagem></item>";
                }
            }

            if (InfraString::isBolVazia($enderecoOrgao) ||
                InfraString::isBolVazia($bairroOrgao) ||
                InfraString::isBolVazia($idCidadeOrgao) ||
                InfraString::isBolVazia($ufOrgao) ||
                InfraString::isBolVazia($cepOrgao)) {
                $enderecoOrgaoIncompleto = true;
            } else {

                if($bolEntrada){
                    $str_msg_validacaoCorreios = self::validarCepBaseCorreios($cepOrgao, 'O CEP do cadastro do órgão desta Unidade é inválido.<br>Faça contato com a Gestão do SEI do seu órgão para corrigir o CEP do órgão.');
                    if ($str_msg_validacaoCorreios != '') {
                        return $str_msg_validacaoCorreios;
                    }
                } else {
                    $str_msg_validacaoCorreios = self::validarCepBaseCorreios($cepOrgao, "O CEP do cadastro do órgão desta Unidade é inválido.\nFaça contato com a Gestão do SEI do seu órgão para corrigir o CEP do órgão.");
                    if ($str_msg_validacaoCorreios != '') {
                        return "<itens><flag>false</flag><mensagem>" . $str_msg_validacaoCorreios . "</mensagem></itens>";
                    }
                }
            }

            if ($natureza == '') {
                $erros[] = 'Natureza';
            }

            if ($endereco == '') {
                $erros[] = 'Endereço';
            }

            if ($bairro == '') {
                $erros[] = 'Bairro';
            }

            if ($uf == '') {
                $erros[] = 'Estado';
            }

            if ($idCidade == '') {
                $erros[] = 'Cidade';
            }

            if ($cep == '') {
                $erros[] = 'CEP';
            }

            if (is_array($erros) && count($erros) > 0) {
                $intContador = 0;
                $str_msg_validacao .= "   - ";
                foreach ($erros as $erro) {
                    if ($intContador > 0) {
                        $str_msg_validacao .= ", ";
                    }
                    $str_msg_validacao .= $erro;
                    $intContador++;
                }
                if($bolEntrada){
                    return $str_msg_validacao;
                } else {
                    return "<itens><flag>false</flag><mensagem>" . $str_msg_validacao . "</mensagem></itens>";
                }
            } else {
                if($bolEntrada){
                    $arrOpt = ['ufDest' => $objUFDTO->getStrSigla(),'cidadeDest' => $objCidadeDTO->getStrNome()];
                    $str_msg_validacaoCorreios = self::validarCepBaseCorreios($cep, "O CEP do Destinatário é inválido (não encontrado na base dos Correios).<br>Altere o Contato do Destinatário para indicar um CEP válido.",$arrOpt);
                    if ($str_msg_validacaoCorreios != '') {
                        return $str_msg_validacaoCorreios;
                    }
                } else {
                    if (!preg_match('/^[0-9]{5,5}([-]?[0-9]{3,3})$/', $contatoDTO->getStrCep()) && $contatoDTO->getNumIdPais() == 76) {
                        $str_msg_validacaoCep = "O CEP do Destinatário está com formato formato inválido.\nAltere o Contato do Destinatário para indicar o CEP no formato válido: XXXXX-YYY.";
                        return "<item><flag>false</flag><mensagem>" . $str_msg_validacaoCep . "</mensagem></item>";
                    }
                    $str_msg_validacaoCorreios = self::validarCepBaseCorreios($cep, "O CEP do Destinatário é inválido, pois não existe na base de dados de CEPs dos Correios.\nAltere o Contato do Destinatário para indicar um CEP válido.");
                    if ($str_msg_validacaoCorreios != '') {
                        return "<itens><flag>false</flag><mensagem>" . $str_msg_validacaoCorreios . "</mensagem></itens>";
                    }
                }
                if ($enderecoOrgaoIncompleto) {

                    if($bolEntrada){
                        $str_msg_validacao_orgao = 'Os dados cadastrais do órgão desta Unidade estão incompletos.<br>Faça contato com a Gestão do SEI do seu órgão para que preencham os dados cadastrais do órgão.';
                        return $str_msg_validacao_orgao;
                    } else {
                        $str_msg_validacao_orgao = "Os dados cadastrais do órgão desta Unidade estão incompletos.\nFaça contato com a Gestão do SEI do seu órgão para que preencham os dados cadastrais do órgão.";
                        return "<item><flag>false</flag><mensagem>" . $str_msg_validacao_orgao . "</mensagem></item>";
                    }
                }
            }

            //se for pessoa fisica checar ainda: Genero e Cargo
            if ($natureza == ContatoRN::$TN_PESSOA_FISICA) {

                if ($idCargo == '') {
                    $erros[] = '\n - Cargo';
                }

                if ($genero == '') {
                    $erros[] = '\n - Gênero';
                }

                if (is_array($erros) && count($erros) > 0) {

                    foreach ($erros as $erro) {
                        $str_msg_validacao .= $erro;
                    }
                    if($bolEntrada){
                        return $str_msg_validacao;
                    } else {
                        return "<item><flag>false</flag><mensagem>" . $str_msg_validacao . "</mensagem></item>";
                    }
                }
            }
            if(!$bolEntrada) {
                return "<item><flag>true</flag><mensagem></mensagem></item>";
            }
        } catch( Exception $e ){
        	if ( $bolEntrada ) {
        		return $e->getMessage();
	        }
        	return "<item><flag>false</flag><mensagem>{$e->getMessage()}</mensagem></item>";

        }
    }

    public static function validarCepBaseCorreios($cep, $msgErro=null, $opt=null)
    {
        try {
	        $objMdCorAdmIntegracaoRN = new MdCorAdmIntegracaoRN();

	        $objMdCorIntegCEP = $objMdCorAdmIntegracaoRN->buscaIntegracaoPorFuncionalidade(MdCorAdmIntegracaoRN::$CEP);
	        if ( is_array( $objMdCorIntegCEP ) && isset( $objMdCorIntegCEP['suc'] ) && $objMdCorIntegCEP['suc'] === false )
	            return 'Mapeamento de Integração '. MdCorAdmIntegracaoRN::$STR_CEP .' não existe ou está inativa.';

	        $arrParametro = [
		        'endpoint'  => $objMdCorIntegCEP->getStrUrlOperacao(),
		        'token'     => $objMdCorIntegCEP->getStrToken(),
		        'expiraEm'  => $objMdCorIntegCEP->getDthDataExpiraToken(),
	        ];

            $ret = $objMdCorAdmIntegracaoRN->verificaTokenExpirado($arrParametro, $objMdCorIntegCEP);

            // recupera algum erro sobre a validacao de token expirado
            if ( is_array( $ret ) && isset( $ret['suc'] ) && $ret['suc'] === false )
                return "Falha na Integração: ". MdCorAdmIntegracaoRN::$STR_GERAR_TOKEN . ".\n". $ret['msg'];

	        $objMdCorWsCEP = new MdCorApiRestRN($arrParametro);

	        $ret = $objMdCorWsCEP->consultarCEP($cep);

            // recupera algum erro sobre o retorno do consultar CEP
            if ( is_array( $ret ) && isset( $ret['suc'] ) && $ret['suc'] === false ) {
                return "Falha na Integração: ". MdCorAdmIntegracaoRN::$STR_CEP . ".\n". $ret['msg'];
            }

            //validacoes de dados do Endereço
	        if ( !empty( $opt ) ) {
	            if ( isset($opt['ufDest'] ) ) {
	                if ( $opt['ufDest'] != $ret['uf'] )
                        return "Não foi possível iniciar ou alterar a Solicitação de Expedição, antes é necessário revisar o cadastro do Contato definido como Destinatário, pois a UF dele, \"{$opt['ufDest']}\", não está relacionada ao CEP " . self::criarMascara($cep,'#####-###');
                }

	            if ( isset($opt['cidadeDest'] ) ) {
	                $cidadeREST = isset($ret['localidadeSuperior']) ? utf8_decode($ret['localidadeSuperior']) : utf8_decode($ret['localidade']);
	                if ( strcasecmp( InfraString::excluirAcentos($opt['cidadeDest']) , InfraString::excluirAcentos( $cidadeREST ) ) != 0 )
                        return "Não foi possível iniciar ou alterar a Solicitação de Expedição, antes é necessário revisar o cadastro do Contato definido como Destinatário, pois a Cidade dele, \"{$opt['cidadeDest']}\", não está relacionada ao CEP " . self::criarMascara($cep,'#####-###');
                }
            }

	        return '';

        } catch (Exception $e) {
            return $e->getMessage();
        }
    }

    public static function validarDestinatarioIntimacaoEletronica($contatoDTO, $bolEntrada = false){

        $versaoInfraParametro = CorreiosIntegracao::consultarVersaoInfraParametro('VERSAO_MODULO_PETICIONAMENTO');

        if (!is_null($versaoInfraParametro) && $versaoInfraParametro >= 300 ) {

            if($bolEntrada){
                $msgErro = 'O Destinatário indicado pode receber Intimação Eletrônica, sendo vedada a expedição pelos Correios. \nNa tela anterior, acesse o botão \"Gerar Intimação Eletrônica\" para expedir o documento por Intimação Eletrônica.';
            }else {
                $msgErro = "O Destinatário indicado pode receber Intimação Eletrônica, sendo vedada a expedição pelos Correios. \nNa tela anterior, acesse o botão \"Gerar Intimação Eletrônica\" para expedir o documento por Intimação Eletrônica.";
            }
            $usuarioDTO = new UsuarioDTO();
            $usuarioDTO->retStrStaTipo();
            $usuarioDTO->retStrSinAtivo();
            $usuarioDTO->setNumIdContato($contatoDTO->getNumIdContato());

            $UsuarioRN = new UsuarioRN();
            $usuarioDTO = $UsuarioRN->consultarRN0489($usuarioDTO);

            if (is_null($usuarioDTO)) {

                $objContatoRN = new ContatoRN();

                if (!is_null($contatoDTO->getDblCpf())) {

                    $objContatoDTO = new ContatoDTO();
                    $objContatoDTO->setDblCpf($contatoDTO->getDblCpf());
                    $objContatoDTO->retNumIdContato();
                    $objContatoDTO = $objContatoRN->listarRN0325($objContatoDTO);

                    $arrIdContato = InfraArray::converterArrInfraDTO($objContatoDTO, 'IdContato');

                    $usuarioDTO = new UsuarioDTO();
                    $usuarioDTO->retNumIdContato();
                    $usuarioDTO->setNumIdContato($arrIdContato, InfraDTO::$OPER_IN);
                    $usuarioDTO->setStrStaTipo( UsuarioRN::$TU_EXTERNO );
                    $usuarioDTO->setStrSinAtivo('S');

                    $objUsuarioRN = new UsuarioRN();
                    $listaContato = $objUsuarioRN->listarRN0490($usuarioDTO);

                    if (count($listaContato) > 0) {
                        if($bolEntrada){
                            throw new Exception( str_replace('\n','<br>', $msgErro) );
                        } else {
                            return "<item></item><flag>false</flag><mensagem>" . $msgErro . "</mensagem></item>";
                        }
                    } else {

                        $arrIdContato = [$contatoDTO->getNumIdContatoAssociado()];
                        $arrObjMdPetVinculoDTO = self::validarPetVinculoUsuarioExterno($arrIdContato);

                        if (count($arrObjMdPetVinculoDTO) > 0) {
                            if($bolEntrada){
                                throw new Exception( str_replace('\n','<br>', $msgErro) );
                            } else {
                                return "<item></item><flag>false</flag><mensagem>" . $msgErro . "</mensagem></item>";
                            }
                        }
                    }
                }else{
                    $arrIdContato = [$contatoDTO->getNumIdContatoAssociado()];
                    $arrObjMdPetVinculoDTO = self::validarPetVinculoUsuarioExterno($arrIdContato);

                    if (count($arrObjMdPetVinculoDTO) > 0) {
                        if($bolEntrada){
                            throw new Exception( str_replace('\n','<br>', $msgErro) );
                        } else {
                            return "<item></item><flag>false</flag><mensagem>" . $msgErro . "</mensagem></item>";
                        }
                    }
                }

                if ($contatoDTO->getStrStaNatureza() == 'J' && !is_null($contatoDTO->getDblCnpj())) {

                    $arrIdContato = [$contatoDTO->getNumIdContato()];
                    $arrObjMdPetVinculoDTO = self::validarPetVinculoUsuarioExterno($arrIdContato);

                    if (count($arrObjMdPetVinculoDTO) > 0) {
                        if($bolEntrada){
                            throw new Exception( str_replace('\n','<br>', $msgErro) );
                        } else {
                            return "<item></item><flag>false</flag><mensagem>" . $msgErro . "</mensagem></item>";
                        }
                    } else {
                        $objContatoDTO = new ContatoDTO();
                        $objContatoDTO->setDblCnpj($contatoDTO->getDblCnpj());
                        $objContatoDTO->retNumIdContato();
                        $objContatoDTO = $objContatoRN->listarRN0325($objContatoDTO);

                        $arrIdContato = InfraArray::converterArrInfraDTO($objContatoDTO, 'IdContato');

                        $arrObjMdPetVinculoDTO = self::validarPetVinculoUsuarioExterno($arrIdContato);

                        if (count($arrObjMdPetVinculoDTO) > 0) {
                            if($bolEntrada){
                                throw new Exception( str_replace('\n','<br>', $msgErro) );
                            } else {
                                return "<item></item><flag>false</flag><mensagem>" . $msgErro . "</mensagem></item>";
                            }
                        }
                    }
                }

            } else {
                if ($usuarioDTO->getStrStaTipo() == UsuarioRN::$TU_EXTERNO && $usuarioDTO->getStrSinAtivo() == 'S') {
                    if ( $bolEntrada ){
                        throw new Exception( str_replace('\n','<br>', $msgErro) );
                    } else {
                        return "<item></item><flag>false</flag><mensagem>" . $msgErro . "</mensagem></item>";
                    }
                }
            }
        }
    }

    public static function validarPetVinculoUsuarioExterno( $arrIdContato ){

        $objMdPetVinculoDTO = new MdPetVinculoDTO();
        $objMdPetVinculoDTO->retNumIdContato();
        $objMdPetVinculoDTO->setNumIdContato($arrIdContato, InfraDTO::$OPER_IN);
        $objMdPetVinculoDTO->setStrStaEstado(MdPetVincRepresentantRN::$RP_ATIVO);

        $objMdPetVinculoRN = new MdPetVinculoRN();
        return $objMdPetVinculoRN->listar($objMdPetVinculoDTO);
    }

    public static function criarMascara($val, $mask){
        $maskared = '';
        $k = 0;
        for ($i = 0; $i <= strlen($mask) - 1; ++$i) {
            if ($mask[$i] == '#') {
                if (isset($val[$k])) {
                    $maskared .= $val[$k++];
                }
            } else {
                if (isset($mask[$i])) {
                    $maskared .= $mask[$i];
                }
            }
        }

        return $maskared;
    }
}
?>