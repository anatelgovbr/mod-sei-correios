<?
/**
 * TRIBUNAL REGIONAL FEDERAL DA 4ª REGIÃO
 *
 * 19/06/2018 - criado por augusto.cast
 *
 * Versão do Gerador de Código: 1.41.0
 */

require_once dirname(__FILE__) . '/../../../SEI.php';
require_once dirname(__FILE__) . '/../lib/zbar/vendor/autoload.php';
//require_once dirname(__FILE__) . '/../lib/qrcode/vendor/autoload.php';

class MdCorRetornoArRN extends InfraRN
{

  public static $STA_RETORNO_AR_AUTOMATICO = 1;
  public static $STA_RETORNO_AR_MANUAL = 2;
  public static $STA_RETORNO_AR_JA_PROCESSADO = 3;
  public static $STA_RETORNO_AR_NAO_PROCESSADO = 4;

  public static $SUBSTA_RETORNO_AR_NAO_IDENTIFICADO = 1;
  public static $SUBSTA_RETORNO_AR_PROCESSADO_ANTERIORMENTE = 2;
  public static $SUBSTA_RETORNO_AR_PROC_SOBRESTADO = 3;
  public static $SUBSTA_RETORNO_AR_PROC_ANEXADO = 4;
  public static $SUBSTA_RETORNO_AR_PROC_BLOQUEADO = 5;
  public static $SUBSTA_RETORNO_AR_UNIDADE_DESATIVADA = 6;
  
  public static $STA_PENDENTE_RETORNO_COM_COBRANCA = 'C';
  public static $STA_PENDENTE_RETORNO_SEM_COBRANCA = 'S';
  public static $STA_FORA_PRAZO_COBRANCA = 'F';
  public static $STA_COBRADO_FORA_PRAZO = 'P';
  public static $STA_OBJETO_EXTRAVIADO_SEM_COBRANCA = 'E';
  public static $STA_OBJETO_EXTRAVIADO_COM_COBRANCA = 'O';

  public static $arrStatus = [
    1 => 'Identificado Automaticamente',
    2 => 'Identificado Manualmente',
    4 => 'Não Processado',
    3 => 'Retorno de AR já Processado Anteriormente',
  ];

  public static $arrStatusRetorno = [
    'C' => 'Pendente de retorno - Com Cobrança',
    'S' => 'Pendente de retorno - Sem Cobrança',
    'F' => 'Fora do Prazo do Cobrança',
    'P' => 'Cobrado Fora do Prazo ',
    'E' => 'Objeto Extraviado - Sem Cobrança',
    'O' => 'Objeto Extraviado - Com Cobrança',
  ];

  public function __construct()
  {
    parent::__construct();
  }


  protected function inicializarObjInfraIBanco()
  {
    return BancoSEI::getInstance();
  }

  private function validarNumIdMdCorParametroAr(MdCorParametroArDTO $objMdCorParametroArDTO, InfraException $objInfraException)
  {
    if (InfraString::isBolVazia($objMdCorParametroArDTO->getNumIdMdCorParametroAr())) {
      $objInfraException->adicionarValidacao(' não informado.');
    }
  }

  private function validarStrNuDiasRetornoAr(MdCorParametroArDTO $objMdCorParametroArDTO, InfraException $objInfraException)
  {

    if (InfraString::isBolVazia($objMdCorParametroArDTO->getStrNuDiasRetornoAr())) {
      $objInfraException->adicionarValidacao(' não informad.');
    } else {
      $objMdCorParametroArDTO->setStrNuDiasRetornoAr(trim($objMdCorParametroArDTO->getStrNuDiasRetornoAr()));

      if (strlen($objMdCorParametroArDTO->getStrNuDiasRetornoAr()) > 6) {
        $objInfraException->adicionarValidacao(' possui tamanho superior a 6 caracteres.');
      }
    }
  }

  private function validarNumIdSerie(MdCorParametroArDTO $objMdCorParametroArDTO, InfraException $objInfraException)
  {
    if (InfraString::isBolVazia($objMdCorParametroArDTO->getNumIdSerie())) {
      $objInfraException->adicionarValidacao(' não informad.');
    }
  }

  private function validarStrNomeArvore(MdCorParametroArDTO $objMdCorParametroArDTO, InfraException $objInfraException)
  {
    if (InfraString::isBolVazia($objMdCorParametroArDTO->getStrNomeArvore())) {
      $objInfraException->adicionarValidacao(' não informad.');
    } else {
      $objMdCorParametroArDTO->setStrNomeArvore(trim($objMdCorParametroArDTO->getStrNomeArvore()));

      if (strlen($objMdCorParametroArDTO->getStrNomeArvore()) > 60) {
        $objInfraException->adicionarValidacao(' possui tamanho superior a 60 caracteres.');
      }
    }
  }

  private function validarNumIdTipoConferencia(MdCorParametroArDTO $objMdCorParametroArDTO, InfraException $objInfraException)
  {
    if (InfraString::isBolVazia($objMdCorParametroArDTO->getNumIdTipoConferencia())) {
      $objInfraException->adicionarValidacao(' não informad.');
    }
  }

  protected function cadastrarControlado(MdCorRetornoArDTO $objMdCorRetornoArDTO)
  {
    try {

      //Valida Permissao
      SessaoSEI::getInstance()->validarPermissao('md_cor_parametro_ar_cadastrar');

      //Regras de Negocio
      $objInfraException = new InfraException();

//      $this->validarNumIdMdCorParametroAr($objMdCorParametroArDTO, $objInfraException);
//      $this->validarStrNuDiasRetornoAr($objMdCorParametroArDTO, $objInfraException);
//      $this->validarNumIdSerie($objMdCorParametroArDTO, $objInfraException);
//      $this->validarStrNomeArvore($objMdCorRetornoArDTO, $objInfraException);
//      $this->validarNumIdTipoConferencia($objMdCorParametroArDTO, $objInfraException);


      $objInfraException->lancarValidacoes();

      $objMdCorParametroArBD = new MdCorParametroArBD($this->getObjInfraIBanco());
      $ret = $objMdCorParametroArBD->cadastrar($objMdCorRetornoArDTO);

      //Auditoria

      return $ret;

    } catch (Exception $e) {
      throw new InfraException('Erro cadastrando .', $e);
    }
  }

  protected function alterarControlado(MdCorRetornoArDTO $objMdCorRetornoArDTO)
  {
    try {

      //Valida Permissao
//      SessaoSEI::getInstance()->validarPermissao('md_cor_parametro_ar_cadastrar');

      //Regras de Negocio
      $objInfraException = new InfraException();



      $objInfraException->lancarValidacoes();

      $objMdCorRetornoArBD = new MdCorRetornoArBD($this->getObjInfraIBanco());
      $objMdCorRetornoArBD->alterar($objMdCorRetornoArDTO);

      //Auditoria

    } catch (Exception $e) {
      throw new InfraException('Erro alterando .', $e);
    }
  }

  protected function excluirControlado($arrObjMdCorParametroArDTO)
  {
    try {

      //Valida Permissao
      SessaoSEI::getInstance()->validarPermissao('md_cor_parametro_ar_excluir');

      //Regras de Negocio
      //$objInfraException = new InfraException();

      //$objInfraException->lancarValidacoes();

      $objMdCorParametroArBD = new MdCorParametroArBD($this->getObjInfraIBanco());
      for ($i = 0; $i < count($arrObjMdCorParametroArDTO); $i++) {
        $objMdCorParametroArBD->excluir($arrObjMdCorParametroArDTO[$i]);
      }

      //Auditoria

    } catch (Exception $e) {
      throw new InfraException('Erro excluindo .', $e);
    }
  }

  protected function consultarConectado(MdCorRetornoArDTO $objMdCorRetornoArDTO)
  {
    try {

      //Valida Permissao
      SessaoSEI::getInstance()->validarPermissao('md_cor_retorno_ar_salvar');

      //Regras de Negocio
      //$objInfraException = new InfraException();

      //$objInfraException->lancarValidacoes();

      $objMdCorRetornoArBD = new MdCorRetornoArBD($this->getObjInfraIBanco());
      $ret = $objMdCorRetornoArBD->consultar($objMdCorRetornoArDTO);

      //Auditoria

      return $ret;
    } catch (Exception $e) {
      throw new InfraException('Erro consultando .', $e);
    }
  }

  protected function listarConectado(MdCorRetornoArDTO $objMdCorRetornoArDTO)
  {
    try {

      //Valida Permissao
      SessaoSEI::getInstance()->validarPermissao('md_cor_retorno_ar_listar');

      //Regras de Negocio
      //$objInfraException = new InfraException();

      //$objInfraException->lancarValidacoes();

      $objMdCorRetornoArBD = new MdCorRetornoArBD($this->getObjInfraIBanco());
      $ret = $objMdCorRetornoArBD->listar($objMdCorRetornoArDTO);

      //Auditoria

      return $ret;

    } catch (Exception $e) {
      throw new InfraException('Erro listando .', $e);
    }
  }

  protected function contarConectado(MdCorRetornoArDTO $objMdCorRetornoArDTO)
  {
    try {

      //Valida Permissao
      SessaoSEI::getInstance()->validarPermissao('md_cor_parametro_ar_cadastrar');

      //Regras de Negocio
      //$objInfraException = new InfraException();

      //$objInfraException->lancarValidacoes();

      $objMdCorRetornoArBD = new MdCorParametroArBD($this->getObjInfraIBanco());
      $ret = $objMdCorRetornoArBD->contar($objMdCorRetornoArDTO);

      //Auditoria

      return $ret;
    } catch (Exception $e) {
      throw new InfraException('Erro contando .', $e);
    }
  }

  protected function processarArquivoConectado($dados)
  {
    ini_set('memory_limit','-1');
    ini_set('max_execution_time', 0);
    $nome = $dados['hdnNomeArquivo'];
    $url = DIR_SEI_TEMP . '/' . $nome;
    $arrNome = substr($nome, 0, -4);
    $_SESSION['ARQUIVO_ZIP'] = DIR_SEI_TEMP . '/' . $nome;
    $_SESSION['ARQUIVO_PASTA'] = DIR_SEI_TEMP . '/' . $arrNome;
    $destino = DIR_SEI_TEMP . '/' . $arrNome;

    $objInfraException = new InfraException();

    if ( file_exists($url) === false ) {
        $objInfraException->adicionarValidacao('Arquivo Zip não encontrado no caminho: ' . $url);
        $objInfraException->lancarValidacoes();
    }

    $zip = new ZipArchive();
    $bolOpenZip = $zip->open($url);

    if ( $bolOpenZip !== true ) {
        $objInfraException->adicionarValidacao('Não foi possível abrir o arquivo Zip no caminho: ' . $url);
        $objInfraException->lancarValidacoes();
    }

    $arrArs = [];
    $arrArs['numFiles'] = $zip->numFiles;
    if ($zip->numFiles <= 50) {
        if ($zip->extractTo($destino) == TRUE) {
          $arrArs = $this->converterPdfImage($destino);
        }
        $zip->close();
        return $this->verificarArs($arrArs);
    } else {
        $zip->close();
        $objInfraException->adicionarValidacao('Dentro do ZIP somente deve constar 50 arquivos PDFs.');
        $objInfraException->lancarValidacoes();
    }
  }

  protected function converterPdfImageControlado($url)
  {
    ini_set('memory_limit','-1');
    ini_set('max_execution_time', 0);
    //Tamanho permitido em MB do arquivo PDF dentro do ZIP após a descompactação
    $tamanhoPdf = 3;
    $objInfraException = new InfraException();
    $files = array_diff(scandir($url), array('.', '..'));

    $objDecoder = new \RobbieP\ZbarQrdecoder\ZbarDecoder();

    $arrQrCode = [];

    foreach ($files as $chave => $file) {
      $localArquivo = $url . '/' . $file;
      $noArquivoJpg = $url . '/' . str_replace('pdf', 'jpg', $file);
      $ext = pathinfo($file, PATHINFO_EXTENSION);

      if ($ext != 'pdf') {
        $objInfraException->adicionarValidacao('Arquivo Dentro do Zip com Formato não Permitido');
        $objInfraException->lancarValidacoes();
      }

      $tamanho = (filesize($localArquivo) / 1024 / 1024);
      if ($tamanho > $tamanhoPdf) {
        $objInfraException->adicionarValidacao('Tamanho do Arquivo PDF maior que o Permitido.');
        $objInfraException->lancarValidacoes();
      }

      $im = new Imagick();
      $im->setResolution(380, 380);
      $im->readimage($url . '/' . $file . '[0]');
      $im->setImageFormat("jpg");
      $im->setImageCompressionQuality(100);
      $im->writeImage($noArquivoJpg);
      $im->destroy();

      // realiza leitura de qrcode ou barcode
      $result = $objDecoder->make($noArquivoJpg);
      $codigo = $result->code == 200 ? $result->text : '0';

      $arrQrCode[$file] = $codigo;

      if (($key = array_search($file, $files)) !== false) {
        unset($files[$key]);
      }
      unlink($noArquivoJpg);
    }

    return $arrQrCode;

  }

  protected function verificarArsControlado($arrArs)
  {

    $mdCorExpedicaoSolicitadaRN = new MdCorExpedicaoSolicitadaRN();
    $mdCorExpedicaoSolicitadaDTO = new MdCorExpedicaoSolicitadaDTO();
    $mdCorExpedicaoSolicitadaDTO->setStrCodigoRastreamento($arrArs, InfraDTO::$OPER_IN);
    $mdCorExpedicaoSolicitadaDTO->retNumIdMdCorExpedicaoSolicitada();
    $mdCorExpedicaoSolicitadaDTO->retStrProtocoloFormatadoDocumento();
    $mdCorExpedicaoSolicitadaDTO->retStrProtocoloFormatado();
    $mdCorExpedicaoSolicitadaDTO->retStrCodigoRastreamento();
    $mdCorExpedicaoSolicitadaDTO->retStrNumeroDocumento();
    $mdCorExpedicaoSolicitadaDTO->retStrNomeSerie();
    $mdCorExpedicaoSolicitadaDTO->retDtaDataAr();
    $mdCorExpedicaoSolicitadaDTO->retDtaDataRetorno();
    $mdCorExpedicaoSolicitadaDTO->retNumIdMdCorRetornoArDoc();
    $mdCorExpedicaoSolicitadaDTO->retDblIdDocumentoPrincipal();
    $mdCorExpedicaoSolicitadaDTO->retNumIdMdCorParamArInfrigencia();
    $mdCorExpedicaoSolicitadaDTO->setDistinct(true);


    $arrMdCorExpedicaoSolicitadaDTO = $mdCorExpedicaoSolicitadaRN->listar($mdCorExpedicaoSolicitadaDTO);

    
    
    $mdCorRetornoArDocRN = new MdCorRetornoArDocRN();
    $mdCorRetornoArDocDTO = new MdCorRetornoArDocDTO();
    $mdCorRetornoArDocDTO->retNumIdMdCorRetornoArDoc();
    $mdCorRetornoArDocDTO->retStrCodigoRastreamento();
    $mdCorRetornoArDocDTO->retStrProtocoloFormatadoDocumento();
    $mdCorRetornoArDocDTO->retStrProtocoloFormatado();
    $mdCorRetornoArDocDTO->retStrNomeSerie();
    $mdCorRetornoArDocDTO->retStrNumeroDocumento();
    $mdCorRetornoArDocDTO->retDtaDataAr();
    $mdCorRetornoArDocDTO->retDtaDataRetorno();
    $mdCorRetornoArDocDTO->retNumIdDocumentoPrincipal();
    $mdCorRetornoArDocDTO->retNumIdMdCorParamArInfrigencia();

    $mdCorRetornoArDocDTO->setStrCodigoRastreamento($arrArs, InfraDTO::$OPER_IN);
    $mdCorRetornoArDocDTO->setDistinct(true);

    $arrMdCorRetornoArDocDTO = $mdCorRetornoArDocRN->listar($mdCorRetornoArDocDTO);
    
    $arrExpedicao = InfraArray::converterArrInfraDTO($arrMdCorExpedicaoSolicitadaDTO, 'CodigoRastreamento');
    $arrRetorno = InfraArray::converterArrInfraDTO($arrMdCorRetornoArDocDTO, 'CodigoRastreamento');

    $arrExpedicao = array_unique($arrExpedicao);
    $arrRetorno = array_unique($arrRetorno);

    $arrArrRetorno = [];
    $contador = 0;
    foreach ($arrArs as $noArquivoPdf => $ar) {
      $arrArrRetorno[$contador]['coRastreamanento'] = $ar;
      $arrArrRetorno[$contador]['noArquivoPdf'] = $noArquivoPdf;
      $arrArrRetorno[$contador]['status'] = $ar ? self::$STA_RETORNO_AR_MANUAL : self::$STA_RETORNO_AR_NAO_PROCESSADO;
      $keyContem = array_search($ar, $arrExpedicao);
      $retornoContem = array_search($ar, $arrRetorno);

      if ($keyContem !== false) {
        $arrArrRetorno[$contador]['nuSei'] = $arrMdCorExpedicaoSolicitadaDTO[$keyContem]->getStrProtocoloFormatadoDocumento();
        $arrArrRetorno[$contador]['nuProcesso'] = $arrMdCorExpedicaoSolicitadaDTO[$keyContem]->getStrProtocoloFormatado();
        $arrArrRetorno[$contador]['nuSerie'] = $arrMdCorExpedicaoSolicitadaDTO[$keyContem]->getStrNomeSerie();
        $arrArrRetorno[$contador]['nuDocumento'] = $arrMdCorExpedicaoSolicitadaDTO[$keyContem]->getStrNumeroDocumento();
        $arrArrRetorno[$contador]['idDocumentoPrincipal'] = $arrMdCorExpedicaoSolicitadaDTO[$keyContem]->getDblIdDocumentoPrincipal();
        $arrArrRetorno[$contador]['status'] = self::$STA_RETORNO_AR_AUTOMATICO;

        if (!is_null($arrMdCorExpedicaoSolicitadaDTO[$keyContem]->getNumIdMdCorRetornoArDoc())) {
          $arrArrRetorno[$contador]['dtAr'] = $arrMdCorExpedicaoSolicitadaDTO[$keyContem]->getDtaDataAr();
          $arrArrRetorno[$contador]['dtRetorno'] = $arrMdCorExpedicaoSolicitadaDTO[$keyContem]->getDtaDataRetorno();
          $arrArrRetorno[$contador]['idMotivo'] = $arrMdCorExpedicaoSolicitadaDTO[$keyContem]->getNumIdMdCorParamArInfrigencia();
          if(is_null($arrMdCorExpedicaoSolicitadaDTO[$keyContem]->getNumIdMdCorParamArInfrigencia())){
                $arrArrRetorno[$contador]['status'] = self::$STA_RETORNO_AR_JA_PROCESSADO;
          } else {
                $arrArrRetorno[$contador]['status'] = self::$STA_RETORNO_AR_NAO_PROCESSADO;
                $arrArrRetorno[$contador]['substatus'] = self::$SUBSTA_RETORNO_AR_PROCESSADO_ANTERIORMENTE;
          }
        }
      } else if ($retornoContem !== false) {
        $arrArrRetorno[$contador]['nuSei'] = $arrMdCorRetornoArDocDTO[$keyContem]->getStrProtocoloFormatadoDocumento();
        $arrArrRetorno[$contador]['nuProcesso'] = $arrMdCorRetornoArDocDTO[$keyContem]->getStrProtocoloFormatado();
        $arrArrRetorno[$contador]['nuSerie'] = $arrMdCorRetornoArDocDTO[$keyContem]->getStrNomeSerie();
        $arrArrRetorno[$contador]['nuDocumento'] = $arrMdCorRetornoArDocDTO[$keyContem]->getStrNumeroDocumento();
        $arrArrRetorno[$contador]['idDocumentoPrincipal'] = $arrMdCorRetornoArDocDTO[$keyContem]->getNumIdDocumentoPrincipal();
        $arrArrRetorno[$contador]['status'] = self::$STA_RETORNO_AR_AUTOMATICO;

        if (!is_null($arrMdCorRetornoArDocDTO[$keyContem]->getNumIdMdCorRetornoArDoc())) {
          $arrArrRetorno[$contador]['dtAr'] = $arrMdCorRetornoArDocDTO[$keyContem]->getDtaDataAr();
          $arrArrRetorno[$contador]['dtRetorno'] = $arrMdCorRetornoArDocDTO[$keyContem]->getDtaDataRetorno();
          $arrArrRetorno[$contador]['idMotivo'] = $arrMdCorRetornoArDocDTO[$keyContem]->getNumIdMdCorParamArInfrigencia();
          $arrArrRetorno[$contador]['status'] = self::$STA_RETORNO_AR_JA_PROCESSADO;
        }
      }
      $contador++;
    }

    return $arrArrRetorno;
  }


  protected function verificarQuantidadeControlado($idRetorno)
  {

    $mdCorRetornoArDocRN = new MdCorRetornoArDocRN();
    $mdCorRetornoArDocDTO = new MdCorRetornoArDocDTO();
    $mdCorRetornoArDocDTO->setNumIdMdCorRetornoAr($idRetorno);
    $mdCorRetornoArDocDTO->retNumIdStatusProcess();

    $arrMdCorRetornoArDoc = $mdCorRetornoArDocRN->listar($mdCorRetornoArDocDTO);

    $arrStatus = [];

    foreach ($arrMdCorRetornoArDoc as $mdCorRetornoArDoc) {
      $arrStatus[$mdCorRetornoArDoc->getNumIdStatusProcess()]++;
    }

    ksort($arrStatus);

    return $arrStatus;

  }


  /*
    protected function desativarControlado($arrObjMdCorParametroArDTO){
      try {

        //Valida Permissao
        SessaoSEI::getInstance()->validarPermissao('md_cor_parametro_ar_desativar');

        //Regras de Negocio
        //$objInfraException = new InfraException();

        //$objInfraException->lancarValidacoes();

        $objMdCorParametroArBD = new MdCorParametroArBD($this->getObjInfraIBanco());
        for($i=0;$i<count($arrObjMdCorParametroArDTO);$i++){
          $objMdCorParametroArBD->desativar($arrObjMdCorParametroArDTO[$i]);
        }

        //Auditoria

      }catch(Exception $e){
        throw new InfraException('Erro desativando .',$e);
      }
    }

    protected function reativarControlado($arrObjMdCorParametroArDTO){
      try {

        //Valida Permissao
        SessaoSEI::getInstance()->validarPermissao('md_cor_parametro_ar_reativar');

        //Regras de Negocio
        //$objInfraException = new InfraException();

        //$objInfraException->lancarValidacoes();

        $objMdCorParametroArBD = new MdCorParametroArBD($this->getObjInfraIBanco());
        for($i=0;$i<count($arrObjMdCorParametroArDTO);$i++){
          $objMdCorParametroArBD->reativar($arrObjMdCorParametroArDTO[$i]);
        }

        //Auditoria

      }catch(Exception $e){
        throw new InfraException('Erro reativando .',$e);
      }
    }

    protected function bloquearControlado(MdCorParametroArDTO $objMdCorParametroArDTO){
      try {

        //Valida Permissao
        SessaoSEI::getInstance()->validarPermissao('md_cor_parametro_ar_consultar');

        //Regras de Negocio
        //$objInfraException = new InfraException();

        //$objInfraException->lancarValidacoes();

        $objMdCorParametroArBD = new MdCorParametroArBD($this->getObjInfraIBanco());
        $ret = $objMdCorParametroArBD->bloquear($objMdCorParametroArDTO);

        //Auditoria

        return $ret;
      }catch(Exception $e){
        throw new InfraException('Erro bloqueando .',$e);
      }
    }

   */
}
