<?
/**
* TRIBUNAL REGIONAL FEDERAL DA 4ª REGIÃO
*
* 07/06/2017 - criado por marcelo.cast
*
* Versão do Gerador de Código: 1.40.1
*/

require_once dirname(__FILE__) . '/../../../SEI.php';

class MdCorExpedicaoFormatoINT extends InfraINT {

  public static function montarSelectIdMdCorExpedicaoFormato($strPrimeiroItemValor, $strPrimeiroItemDescricao, $strValorItemSelecionado){
    $objMdCorExpedicaoFormatoDTO = new MdCorExpedicaoFormatoDTO();
    $objMdCorExpedicaoFormatoDTO->retNumIdMdCorExpedicaoFormato();
    $objMdCorExpedicaoFormatoDTO->retNumIdMdCorExpedicaoFormato();

    $objMdCorExpedicaoFormatoDTO->setOrdNumIdMdCorExpedicaoFormato(InfraDTO::$TIPO_ORDENACAO_ASC);

    $objMdCorExpedicaoFormatoRN = new MdCorExpedicaoFormatoRN();
    $arrObjMdCorExpedicaoFormatoDTO = $objMdCorExpedicaoFormatoRN->listar($objMdCorExpedicaoFormatoDTO);

    return parent::montarSelectArrInfraDTO($strPrimeiroItemValor, $strPrimeiroItemDescricao, $strValorItemSelecionado, $arrObjMdCorExpedicaoFormatoDTO, 'IdMdCorExpedicaoFormato', 'IdMdCorExpedicaoFormato');
  }

    public static function validarDocumentoAPI($arrIdSocitacao)
    {
        $objMdCorExpedicaoSolicitadaRN = new MdCorExpedicaoSolicitadaRN();

        $objMdCorExpedicaoFormatoDTO = new MdCorExpedicaoFormatoDTO();
        $objMdCorExpedicaoFormatoDTO->retDblIdDocumento();
        $objMdCorExpedicaoFormatoDTO->setNumIdMdCorExpedicaoSolicitada($arrIdSocitacao, InfraDTO::$OPER_IN);

        $objMdCorExpedicaoFormatoRN = new MdCorExpedicaoFormatoRN();
        $arrObjMdCorExpedicaoFormatoDTO = $objMdCorExpedicaoFormatoRN->listar($objMdCorExpedicaoFormatoDTO);

        foreach ( $arrObjMdCorExpedicaoFormatoDTO  as $objDTO ){

            $bolDocumentoRestrito = $objMdCorExpedicaoSolicitadaRN->validarAcessoRestrito($objDTO->getDblIdDocumento());
            if(!$bolDocumentoRestrito){
                return '<isRestrito>false</isRestrito>';
            }
        }
        return '<isRestrito>true</isRestrito>';
    }

    public static function validarDocumentoIdProtocoloFormatadoAPI($arrIdProtocoloFormatado)
    {
        $objMdCorExpedicaoSolicitadaRN = new MdCorExpedicaoSolicitadaRN();

        $objMdCorExpedicaoFormatoDTO = new MdCorExpedicaoFormatoDTO();
        $objMdCorExpedicaoFormatoDTO->retDblIdDocumento();
        $objMdCorExpedicaoFormatoDTO->setStrProtocoloFormatado($arrIdProtocoloFormatado, InfraDTO::$OPER_IN);

        $objMdCorExpedicaoFormatoRN = new MdCorExpedicaoFormatoRN();
        $arrObjMdCorExpedicaoFormatoDTO = $objMdCorExpedicaoFormatoRN->listar($objMdCorExpedicaoFormatoDTO);

        foreach ( $arrObjMdCorExpedicaoFormatoDTO  as $objDTO ){

            $bolDocumentoRestrito = $objMdCorExpedicaoSolicitadaRN->validarAcessoRestrito($objDTO->getDblIdDocumento());
            if(!$bolDocumentoRestrito){
                return '<isRestrito>false</isRestrito>';
            }
        }
        return '<isRestrito>true</isRestrito>';
    }
}
?>