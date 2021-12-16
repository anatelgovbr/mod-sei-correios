<?
/**
* TRIBUNAL REGIONAL FEDERAL DA 4ª REGIÃO
*
* 07/06/2017 - criado por marcelo.cast
*
* Versão do Gerador de Código: 1.40.1
*/

require_once dirname(__FILE__) . '/../../../SEI.php';

class MdCorExpedicaoFormatoRN extends InfraRN {

 //formatos impresso ou gravacao em midia	
 public static $TP_FORMATO_IMPRESSO = 'I';
 public static $TP_FORMATO_MIDIA = 'M';
 
 //impressao em preto e branco ou colorido ou nenhum (caso o formato seja gravacao em midia)
 public static $TP_IMPRESSAO_PRETO_BRANCO = 'P';
 public static $TP_IMPRESSAO_COLORIDO = 'C';
 public static $TP_IMPRESSAO_NENHUMA = 'N';
 
  public function __construct(){
    parent::__construct();
  }

  protected function inicializarObjInfraIBanco(){
    return BancoSEI::getInstance();
  }

  private function validarDblIdProtocolo(MdCorExpedicaoFormatoDTO $objMdCorExpedicaoFormatoDTO, InfraException $objInfraException){
    if (InfraString::isBolVazia($objMdCorExpedicaoFormatoDTO->getDblIdProtocolo())){
      $objInfraException->adicionarValidacao(' Documento não informado.');
    }
  }

  private function validarStrFormaExpedicao(MdCorExpedicaoFormatoDTO $objMdCorExpedicaoFormatoDTO, InfraException $objInfraException){
    
  	if (InfraString::isBolVazia($objMdCorExpedicaoFormatoDTO->getStrFormaExpedicao())){
    	$objInfraException->adicionarValidacao(' Formato não informado.');
    }
  }

  private function validarStrImpressao(MdCorExpedicaoFormatoDTO $objMdCorExpedicaoFormatoDTO, InfraException $objInfraException){
    
  	if ( $objMdCorExpedicaoFormatoDTO->getStrFormaExpedicao() == MdCorExpedicaoFormatoRN::$TP_FORMATO_IMPRESSO  && InfraString::isBolVazia($objMdCorExpedicaoFormatoDTO->getStrImpressao())){
    	$objInfraException->adicionarValidacao(' Impressão não informado.');
    } elseif ( $objMdCorExpedicaoFormatoDTO->getStrFormaExpedicao() == MdCorExpedicaoFormatoRN::$TP_FORMATO_MIDIA){
    	//se o formato é gravaçao de midia, se o tipo de impressao para NENHUMA
    	$objMdCorExpedicaoFormatoDTO->setStrImpressao( MdCorExpedicaoFormatoRN::$TP_IMPRESSAO_NENHUMA );
    }
  }

  private function validarStrJustificativa(MdCorExpedicaoFormatoDTO $objMdCorExpedicaoFormatoDTO, InfraException $objInfraException){
  	
  	//é obrigatorio informar justificativa se a impressao for colorida
  	if ( $objMdCorExpedicaoFormatoDTO->getStrImpressao() == MdCorExpedicaoFormatoRN::$TP_IMPRESSAO_COLORIDO && InfraString::isBolVazia($objMdCorExpedicaoFormatoDTO->getStrJustificativa())){
    	$objInfraException->adicionarValidacao(' Justificativa não informada.');
    }else{
      $objMdCorExpedicaoFormatoDTO->setStrJustificativa(trim($objMdCorExpedicaoFormatoDTO->getStrJustificativa()));

      if (strlen($objMdCorExpedicaoFormatoDTO->getStrJustificativa())>500){
        $objInfraException->adicionarValidacao(' possui tamanho superior a 500 caracteres.');
      }
    }
  }

  protected function cadastrarControlado(MdCorExpedicaoFormatoDTO $objMdCorExpedicaoFormatoDTO) {
    
  	try{

      //Valida Permissao - chamada sempre a partir da RN de expedicao solicitada
      SessaoSEI::getInstance()->validarPermissao('md_cor_expedicao_solicitada_cadastrar');

      //Regras de Negocio
      $objInfraException = new InfraException();
      $this->validarDblIdProtocolo($objMdCorExpedicaoFormatoDTO, $objInfraException);
      $this->validarStrFormaExpedicao($objMdCorExpedicaoFormatoDTO, $objInfraException);
      $this->validarStrImpressao($objMdCorExpedicaoFormatoDTO, $objInfraException);
      $this->validarStrJustificativa($objMdCorExpedicaoFormatoDTO, $objInfraException);

      $objInfraException->lancarValidacoes();

      $objMdCorExpedicaoFormatoBD = new MdCorExpedicaoFormatoBD($this->getObjInfraIBanco());
      $ret = $objMdCorExpedicaoFormatoBD->cadastrar($objMdCorExpedicaoFormatoDTO);

      //Auditoria

      return $ret;

    }catch(Exception $e){
      throw new InfraException('Erro cadastrando .',$e);
    }
  }

  protected function alterarControlado(MdCorExpedicaoFormatoDTO $objMdCorExpedicaoFormatoDTO){
    
  	try {

      //Valida Permissao - chamada sempre a partir da RN de expedicao solicitada
      SessaoSEI::getInstance()->validarPermissao('md_cor_expedicao_solicitada_cadastrar');

      //Regras de Negocio
      $objInfraException = new InfraException();

      if ($objMdCorExpedicaoFormatoDTO->isSetDblIdDocumento()){
        $this->validarDblIdDocumento($objMdCorExpedicaoFormatoDTO, $objInfraException);
      }
      if ($objMdCorExpedicaoFormatoDTO->isSetStrFormaExpedicao()){
        $this->validarStrFormaExpedicao($objMdCorExpedicaoFormatoDTO, $objInfraException);
      }
      if ($objMdCorExpedicaoFormatoDTO->isSetStrImpressao()){
        $this->validarStrImpressao($objMdCorExpedicaoFormatoDTO, $objInfraException);
      }
      if ($objMdCorExpedicaoFormatoDTO->isSetStrJustificativa()){
        $this->validarStrJustificativa($objMdCorExpedicaoFormatoDTO, $objInfraException);
      }

      $objInfraException->lancarValidacoes();

      $objMdCorExpedicaoFormatoBD = new MdCorExpedicaoFormatoBD($this->getObjInfraIBanco());
      $objMdCorExpedicaoFormatoBD->alterar($objMdCorExpedicaoFormatoDTO);

      //Auditoria

    }catch(Exception $e){
      throw new InfraException('Erro alterando .',$e);
    }
  }

  protected function consultarConectado(MdCorExpedicaoFormatoDTO $objMdCorExpedicaoFormatoDTO){

  	try {
     
      //Regras de Negocio
      //$objInfraException = new InfraException();

      //$objInfraException->lancarValidacoes();

      $objMdCorExpedicaoFormatoBD = new MdCorExpedicaoFormatoBD($this->getObjInfraIBanco());
      $ret = $objMdCorExpedicaoFormatoBD->consultar($objMdCorExpedicaoFormatoDTO);

      //Auditoria

      return $ret;
    }catch(Exception $e){
      throw new InfraException('Erro consultando .',$e);
    }
  }

  protected function excluirControlado($arrObjMdCorExpedicaoFormatoDTO){
    try {

      //Valida Permissao - chamada sempre a partir da RN de expedicao solicitada
//      SessaoSEI::getInstance()->validarPermissao('md_cor_expedicao_solicitada_cadastrar');

      //Regras de Negocio
      //$objInfraException = new InfraException();

      //$objInfraException->lancarValidacoes();

      $objMdCorExpedicaoFormatoBD = new MdCorExpedicaoFormatoBD($this->getObjInfraIBanco());
      for($i=0;$i<count($arrObjMdCorExpedicaoFormatoDTO);$i++){
        $objMdCorExpedicaoFormatoBD->excluir($arrObjMdCorExpedicaoFormatoDTO[$i]);
      }

      //Auditoria

    }catch(Exception $e){
      throw new InfraException('Erro excluindo .',$e);
    }
  }
  
  protected function listarConectado(MdCorExpedicaoFormatoDTO $objMdCorExpedicaoFormatoDTO) {

  	try {
     
      //Regras de Negocio
      //$objInfraException = new InfraException();

      //$objInfraException->lancarValidacoes();

      $objMdCorExpedicaoFormatoBD = new MdCorExpedicaoFormatoBD($this->getObjInfraIBanco());
      $ret = $objMdCorExpedicaoFormatoBD->listar($objMdCorExpedicaoFormatoDTO);

      //Auditoria

      return $ret;

    }catch(Exception $e){
      throw new InfraException('Erro listando .',$e);
    }
  }

  protected function contarConectado(MdCorExpedicaoFormatoDTO $objMdCorExpedicaoFormatoDTO){
    
  	try {
    
      //Regras de Negocio
      //$objInfraException = new InfraException();

      //$objInfraException->lancarValidacoes();

      $objMdCorExpedicaoFormatoBD = new MdCorExpedicaoFormatoBD($this->getObjInfraIBanco());
      $ret = $objMdCorExpedicaoFormatoBD->contar($objMdCorExpedicaoFormatoDTO);

      //Auditoria

      return $ret;
    }catch(Exception $e){
      throw new InfraException('Erro contando .',$e);
    }
  }

    protected function retornarFormatoExpedicaoConectado($idMdCorExpedicaoSolicitada){

        $objMdCorExpedicaoFormatoRN = new MdCorExpedicaoFormatoRN();
        $objMdCorExpedicaoFormatoDTO = new MdCorExpedicaoFormatoDTO();
        $objMdCorExpedicaoFormatoDTO->retStrFormaExpedicao();
        $objMdCorExpedicaoFormatoDTO->retStrImpressao();
        $objMdCorExpedicaoFormatoDTO->retStrJustificativa();
        $objMdCorExpedicaoFormatoDTO->retNumIdMdCorExpedicaoFormato();
        $objMdCorExpedicaoFormatoDTO->retStrProtocoloFormatado();
        $objMdCorExpedicaoFormatoDTO->setNumIdMdCorExpedicaoSolicitada($idMdCorExpedicaoSolicitada);
        $arrObjMdCorExpedicaoFormatoDTO = $this->listar($objMdCorExpedicaoFormatoDTO);

        $objMdCorExpedicaoSolicitadaRN = new MdCorExpedicaoSolicitadaRN();
        $objMdCorExpedicaoSolicitadaDTO = new MdCorExpedicaoSolicitadaDTO();
        $objMdCorExpedicaoSolicitadaDTO->setStrSinObjetoAcessado('S');
        $objMdCorExpedicaoSolicitadaDTO->setNumIdMdCorExpedicaoSolicitada($idMdCorExpedicaoSolicitada);
        $objMdCorExpedicaoSolicitadaRN->alterar($objMdCorExpedicaoSolicitadaDTO);

        return $arrObjMdCorExpedicaoFormatoDTO;

    }

    /**
     * @param MdCorExpedicaoFormatoDTO $objMdCorExpedicaoFormatoDTO
     * @return AnexoDTO
     * @throws InfraException
     * @description adaptação do metodo da classe DocumentoRN com diferença no parametro de entrada e o nome do arquivo dentro do zip
     */
    protected function gerarZipConectado(MdCorExpedicaoFormatoDTO $objMdCorExpedicaoFormatoDTO) {
        try{

            ini_set('max_execution_time','300');

            $objInfraException = new InfraException();


            $objDocumentoRN = new DocumentoRN();
            $objMdCorExpedicaoFormatoRN = new MdCorExpedicaoFormatoRN();
            $arrObjMdCorExpedicaoFormatoDTO = $objMdCorExpedicaoFormatoRN->listar($objMdCorExpedicaoFormatoDTO);

            $arrIdDocumentos = InfraArray::converterArrInfraDTO($arrObjMdCorExpedicaoFormatoDTO,'IdProtocolo');

            $objDocumentoDTO = new DocumentoDTO();
            $objDocumentoDTO->retDblIdDocumento();
            $objDocumentoDTO->retDblIdProcedimento();
            $objDocumentoDTO->retStrStaProtocoloProtocolo();
            $objDocumentoDTO->retStrNumero();
            $objDocumentoDTO->retStrNomeSerie();
            $objDocumentoDTO->retStrProtocoloDocumentoFormatado();
            $objDocumentoDTO->retStrProtocoloProcedimentoFormatado();
            //$objDocumentoDTO->retStrSiglaUnidadeGeradoraProtocolo();
            $objDocumentoDTO->retStrStaDocumento();
            $objDocumentoDTO->retDblIdDocumentoEdoc();
            //$objDocumentoDTO->retStrConteudo();
            $objDocumentoDTO->setDblIdDocumento($arrIdDocumentos, InfraDTO::$OPER_IN);

            $arrObjDocumentoDTO = $objDocumentoRN->listarRN0008($objDocumentoDTO);

            if (count($arrObjDocumentoDTO)==0){
                throw new InfraException('Nenhum documento informado.');
            }

            $arrIdMdCorExpedicaoSolicitada = InfraArray::converterArrInfraDTO($arrObjMdCorExpedicaoFormatoDTO,'IdMdCorExpedicaoSolicitada');
            $objMdCorExpedicaoSolicitadaDTO = new MdCorExpedicaoSolicitadaDTO();
            $objMdCorExpedicaoSolicitadaDTO->retNumIdMdCorExpedicaoSolicitada();
            $objMdCorExpedicaoSolicitadaDTO->retNumCodigoPlp();
            $objMdCorExpedicaoSolicitadaDTO->setNumIdMdCorExpedicaoSolicitada($arrIdMdCorExpedicaoSolicitada, InfraDTO::$OPER_IN);
            $objMdCorExpedicaoSolicitadaDTO->setNumMaxRegistrosRetorno(1);

            $objMdCorExpedicaoSolicitadaRN = new MdCorExpedicaoSolicitadaRN();
            $objMdCorExpedicaoSolicitadaDTO = $objMdCorExpedicaoSolicitadaRN->consultar($objMdCorExpedicaoSolicitadaDTO);

            if(empty($objMdCorExpedicaoSolicitadaDTO)){
                throw new InfraException('Nenhuma expedição solicitada.');
            }

            $objAnexoRN = new AnexoRN();
            $strCaminhoCompletoArquivoZip = DIR_SEI_TEMP.'/'.$objAnexoRN->gerarNomeArquivoTemporario();

            $zipFile= new ZipArchive();
            $zipFile->open($strCaminhoCompletoArquivoZip, ZIPARCHIVE::CREATE);

            $arrObjDocumentoDTO = InfraArray::indexarArrInfraDTO($arrObjDocumentoDTO,'IdDocumento');
            $numCasas=floor(log10(count($arrObjDocumentoDTO)))+1;
            $numSequencial = 0;

            foreach($arrIdDocumentos as $dblIdDocumento){
                $numSequencial++;
                $numDocumento=str_pad($numSequencial, $numCasas, "0", STR_PAD_LEFT);
                $objDocumentoDTO = $arrObjDocumentoDTO[$dblIdDocumento];
                $strDocumento = '';

                $strNomeArquivo = $objMdCorExpedicaoSolicitadaDTO->getNumCodigoPlp().'_';

                $mdCorExpedicaoFormatoDTO = InfraArray::filtrarArrInfraDTO($arrObjMdCorExpedicaoFormatoDTO, 'IdProtocolo',$dblIdDocumento );

                if($mdCorExpedicaoFormatoDTO[0]->getStrFormaExpedicao() == MdCorExpedicaoFormatoRN::$TP_FORMATO_MIDIA ){
                    $strNomeArquivo .= 'MD_';
                }elseif ($mdCorExpedicaoFormatoDTO[0]->getStrImpressao() == MdCorExpedicaoFormatoRN::$TP_IMPRESSAO_COLORIDO){
                    $strNomeArquivo .= 'CO_';
                }elseif ($mdCorExpedicaoFormatoDTO[0]->getStrImpressao() == MdCorExpedicaoFormatoRN::$TP_IMPRESSAO_PRETO_BRANCO){
                    $strNomeArquivo .= 'PB_';
                }

                if ($objDocumentoDTO->getStrStaProtocoloProtocolo() == ProtocoloRN::$TP_DOCUMENTO_GERADO){
                    if ($objDocumentoDTO->getStrStaDocumento()==DocumentoRN::$TD_EDITOR_EDOC){
                        if ($objDocumentoDTO->getDblIdDocumentoEdoc()==null){
                            $strDocumento .= 'Documento e-Doc '.$objDocumentoDTO->getStrProtocoloDocumentoFormatado() .' não encontrado.';
                            $objInfraException->adicionarValidacao('Documento e-Doc '.$objDocumentoDTO->getStrProtocoloDocumentoFormatado() .' não encontrado.');
                        }else{
                            $strDocumento .= EDocINT::montarVisualizacaoDocumento($objDocumentoDTO->getDblIdDocumentoEdoc());
                        }
                    }else if ($objDocumentoDTO->getStrStaDocumento()==DocumentoRN::$TD_EDITOR_INTERNO){
                        $objEditorDTO = new EditorDTO();
                        $objEditorDTO->setDblIdDocumento($objDocumentoDTO->getDblIdDocumento());
                        $objEditorDTO->setNumIdBaseConhecimento(null);
                        $objEditorDTO->setStrSinCabecalho('S');
                        $objEditorDTO->setStrSinRodape('S');
                        $objEditorDTO->setStrSinCarimboPublicacao('S');
                        $objEditorDTO->setStrSinIdentificacaoVersao('N');

                        $objEditorRN = new EditorRN();
                        $strDocumento .= $objEditorRN->consultarHtmlVersao($objEditorDTO);
                    }else{
                        // email, por exemplo
                        $strDocumento .= $this->consultarHtmlFormulario($objDocumentoDTO);
                    }

                    $strNomeArquivo .= $objDocumentoDTO->getStrProtocoloDocumentoFormatado().'-'.$objDocumentoDTO->getStrNomeSerie();
                    if (!InfraString::isBolVazia($objDocumentoDTO->getStrNumero())){
                        $strNomeArquivo .= '-'.$objDocumentoDTO->getStrNumero();
                    }
                    $strNomeArquivo .='.html';

                    if ($zipFile->addFromString('['.$numDocumento.']-'.InfraUtil::formatarNomeArquivo($strNomeArquivo),$strDocumento) === false){
                        throw new InfraException('Erro adicionando conteúdo html ao zip.');
                    }
                }else if ($objDocumentoDTO->getStrStaProtocoloProtocolo()==ProtocoloRN::$TP_DOCUMENTO_RECEBIDO){
                    $objAnexoDTO = new AnexoDTO();
                    $objAnexoDTO->retNumIdAnexo();
                    $objAnexoDTO->retStrNome();
                    $objAnexoDTO->retDthInclusao();
                    $objAnexoDTO->setDblIdProtocolo($objDocumentoDTO->getDblIdDocumento());
                    $objAnexoRN = new AnexoRN();
                    $objAnexoDTO = $objAnexoRN->consultarRN0736($objAnexoDTO);
                    if ($objAnexoDTO==null){
                        $objInfraException->adicionarValidacao('Documento '.$objDocumentoDTO->getStrProtocoloDocumentoFormatado() .' não encontrado.');
                    }else{
                        if ($zipFile->addFile($objAnexoRN->obterLocalizacao($objAnexoDTO),'['.$numDocumento.']-'.InfraUtil::formatarNomeArquivo($objAnexoDTO->getStrNome())) === false){
                            throw new InfraException('Erro adicionando arquivo externo ao zip.');
                        }
                    }
                }else{
                    $objInfraException->adicionarValidacao('Não foi possível detectar o tipo do documento '.$objDocumentoDTO->getStrProtocoloDocumentoFormatado().'.');
                }
            }
            $objInfraException->lancarValidacoes();

            if ($zipFile->close() === false) {
                throw new InfraException('Não foi possível fechar arquivo zip.');
            }

            $objAnexoDTO = new AnexoDTO();
            $arrNomeArquivo = explode('/',$strCaminhoCompletoArquivoZip);
            $objAnexoDTO->setStrNome($arrNomeArquivo[count($arrNomeArquivo)-1]);

            return $objAnexoDTO;

        }catch(Exception $e){
            throw new InfraException('Erro gerando zip.',$e);
        }
    }
}
?>