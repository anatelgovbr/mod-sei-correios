<?
/**
* TRIBUNAL REGIONAL FEDERAL DA 4ª REGIÃO
*
* 29/06/2018 - criado por augusto.cast
*
* Versão do Gerador de Código: 1.41.0
*/

require_once dirname(__FILE__) . '/../../../SEI.php';

class MdCorRetornoArINT extends InfraINT {

  public static function montarSelectIdCorRetornoAr($strPrimeiroItemValor, $strPrimeiroItemDescricao, $strValorItemSelecionado){
    $objMdCorRetornoArDTO = new MdCorRetornoArDTO();
    $objMdCorRetornoArDTO->retNumIdCorRetornoAr();

    $objMdCorRetornoArDTO->setOrdNumIdCorRetornoAr(InfraDTO::$TIPO_ORDENACAO_ASC);

    $objMdCorRetornoArRN = new MdCorRetornoArRN();
    $arrObjMdCorRetornoArDTO = $objMdCorRetornoArRN->listar($objMdCorRetornoArDTO);

    return parent::montarSelectArrInfraDTO($strPrimeiroItemValor, $strPrimeiroItemDescricao, $strValorItemSelecionado, $arrObjMdCorRetornoArDTO, '', 'IdCorRetornoAr');
  }

  public static function montarSelectIdStatusArPendente($strPrimeiroItemValor, $strPrimeiroItemDescricao, $strValorItemSelecionado){

    $arrObjMdCorRetornoArDTO = MdCorRetornoArRN::$arrStatusRetorno;
    return parent::montarSelectArray($strPrimeiroItemValor, $strPrimeiroItemDescricao, $strValorItemSelecionado, $arrObjMdCorRetornoArDTO);
  }
  
  public static function contarAr($strNomeArquivo) {
        $nome = basename((string)$strNomeArquivo);

        if ($nome !== $strNomeArquivo || !preg_match('/^\[[A-Za-z0-9_.]+\]\[\d{8}-\d{6}\]-[A-Za-z0-9_.]+\.zip$/D', $nome)) {
            return '<Retorno>false</Retorno>';
        }

        $strDirTempCanonico = realpath(DIR_SEI_TEMP);
        if ($strDirTempCanonico === false || !is_dir($strDirTempCanonico)) {
            return '<Retorno>false</Retorno>';
        }

        $url = $strDirTempCanonico . DIRECTORY_SEPARATOR . $nome;
        $strArquivoCanonico = realpath($url);

        if ($strArquivoCanonico === false || strpos($strArquivoCanonico, $strDirTempCanonico . DIRECTORY_SEPARATOR) !== 0) {
            return '<Retorno>false</Retorno>';
        }
        
        $zip = new ZipArchive();
        if ($zip->open($strArquivoCanonico) !== true) {
            return '<Retorno>false</Retorno>';
        }

        if ($zip->numFiles <= 250){

            if (!isset($_SESSION['MD_COR_UPLOAD_AR_VALIDOS']) || !is_array($_SESSION['MD_COR_UPLOAD_AR_VALIDOS'])) {
                $_SESSION['MD_COR_UPLOAD_AR_VALIDOS'] = array();
            }
            $_SESSION['MD_COR_UPLOAD_AR_VALIDOS'][$nome] = time();
            $zip->close();
            return '<Retorno>true</Retorno>';
        }
        $zip->close();
        return '<Retorno>false</Retorno>';
  }

  public static function extrairArquivosPdfPlanos(ZipArchive $zip, $destino)
  {
    if (is_dir($destino) === false && mkdir($destino, 0777, true) === false) {
      throw new Exception('Nao foi possivel preparar o diretorio de destino para extracao do ZIP.');
    }

    $destinoReal = realpath($destino);

    if ($destinoReal === false) {
      throw new Exception('Nao foi possivel resolver o caminho do diretorio de destino para extracao do ZIP.');
    }

    $arrExtraidos = [];

    for ($i = 0; $i < $zip->numFiles; $i++) {
      $stat = $zip->statIndex($i);

      if ($stat === false || isset($stat['name']) === false) {
        throw new Exception('Nao foi possivel validar uma entrada do arquivo ZIP.');
      }

      $nomeEntry = $stat['name'];
      self::validarNomeEntry($nomeEntry);

      $nomeArquivo = basename($nomeEntry);
      $caminhoFinal = $destinoReal . DIRECTORY_SEPARATOR . $nomeArquivo;

      if (strpos($caminhoFinal, $destinoReal . DIRECTORY_SEPARATOR) !== 0) {
        throw new Exception('Entrada de ZIP invalida: caminho final fora do diretorio de destino.');
      }

      $stream = $zip->getStream($nomeEntry);

      if ($stream === false) {
        throw new Exception('Nao foi possivel ler a entrada do ZIP: ' . $nomeEntry);
      }

      $fpDestino = fopen($caminhoFinal, 'wb');

      if ($fpDestino === false) {
        fclose($stream);
        throw new Exception('Nao foi possivel criar o arquivo de destino: ' . $nomeArquivo);
      }

      while (!feof($stream)) {
        $chunk = fread($stream, 8192);
        if ($chunk === false) {
          fclose($fpDestino);
          fclose($stream);
          throw new Exception('Falha durante leitura da entrada do ZIP: ' . $nomeEntry);
        }

        if ($chunk !== '' && fwrite($fpDestino, $chunk) === false) {
          fclose($fpDestino);
          fclose($stream);
          throw new Exception('Falha durante gravacao do arquivo extraido: ' . $nomeArquivo);
        }
      }

      fclose($fpDestino);
      fclose($stream);
      $arrExtraidos[] = $nomeArquivo;
    }

    return $arrExtraidos;
  }

  private static function validarNomeEntry($nomeEntry)
  {
    if (strpos($nomeEntry, '..') !== false) {
      throw new Exception('Entrada de ZIP invalida: caminho com sequencia ".." nao permitida.');
    }

    if (preg_match('/^(\/|\\\\|[A-Za-z]:[\\\\\/])/', $nomeEntry)) {
      throw new Exception('Entrada de ZIP invalida: caminho absoluto nao permitido.');
    }

    if (strpos($nomeEntry, '\\') !== false) {
      throw new Exception('Entrada de ZIP invalida: separador de diretorio inesperado.');
    }

    if (substr($nomeEntry, -1) === '/' || strpos($nomeEntry, '/') !== false) {
      throw new Exception('Entrada de ZIP invalida: apenas arquivos PDF planos sao permitidos.');
    }

    $extensao = strtolower(pathinfo($nomeEntry, PATHINFO_EXTENSION));
    if ($extensao !== 'pdf') {
      throw new Exception('Entrada de ZIP invalida: apenas arquivos com extensao .pdf sao permitidos.');
    }
  }
  
}
