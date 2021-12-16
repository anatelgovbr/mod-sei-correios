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
  
  public function contarAr($strNomeArquivo) {
        $nome = $strNomeArquivo;
        $url = DIR_SEI_TEMP . '/' . $nome;
        $arrNome = substr($nome, 0, -4);
        
        $zip = new ZipArchive();
        $zip->open($url);
        if ($zip->numFiles <= 250){
            $xml = "<Retorno>";
            $xml .= "true";
            $xml .= "</Retorno>";
            return $xml;
        } else {
            $xml = "<Retorno>";
            $xml .= "false";
            $xml .= "</Retorno>";
            return $xml;
        }
        $zip->close();
  }
  
}
