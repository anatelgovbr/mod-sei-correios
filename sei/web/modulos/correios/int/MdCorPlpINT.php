<?
/**
* TRIBUNAL REGIONAL FEDERAL DA 4ª REGIÃO
*
* 11/10/2017 - criado por José Vieira <jose.vieira@cast.com.br>
*
* Versão do Gerador de Código: 1.41.0
*/

require_once dirname(__FILE__) . '/../../../SEI.php';

class MdCorPlpINT extends InfraINT {

  public static $STR_IMPRIMIR_AR_OK = 'IMPRIMIR_AR_OK'; // Todos os itens selecionados possuem AR
  public static $STR_IMPRIMIR_AR_SEM_AR = 'IMPRIMIR_AR_SEM_AR'; // Tem apenas itens que não necessitam de AR
  public static $STR_IMPRIMIR_AR_MISTO = 'STR_IMPRIMIR_AR_MISTO'; // Tem item que nescessitam e não nescessitam de AR

  public static function montarSelectCodigoPlp($strPrimeiroItemValor, $strPrimeiroItemDescricao, $strValorItemSelecionado){
    $objMdCorPlpDTO = new MdCorPlpDTO();
    $objMdCorPlpDTO->retDblCodigoPlp();

    $objMdCorPlpDTO->setOrdDblCodigoPlp(InfraDTO::$TIPO_ORDENACAO_ASC);

    $objMdCorPlpRN = new MdCorPlpRN();
    $arrObjMdCorPlpDTO = $objMdCorPlpRN->listar($objMdCorPlpDTO);

    return parent::montarSelectArrInfraDTO($strPrimeiroItemValor, $strPrimeiroItemDescricao, $strValorItemSelecionado, $arrObjMdCorPlpDTO, '', 'CodigoPlp');
  }

  public static function montarSelectStaPlp($strPrimeiroItemValor, $strPrimeiroItemDescricao, $strValorItemSelecionado){
      $arr = array(MdCorPlpRN::$STA_PENDENTE => 'Possui Objeto Pendente de Entrega', MdCorPlpRN::$STA_ENTREGUES => 'Todos os Objetos Entregues', MdCorPlpRN::$STA_RETORNO_AR_PENDENTE => 'Objetos com Retorno de AR Pendente', MdCorPlpRN::$STA_FINALIZADA => 'Finalizada');
      return InfraINT::montarSelectArray($strPrimeiroItemValor, $strPrimeiroItemDescricao, $strValorItemSelecionado, $arr);
  }

    public static function montarSelectServicoPostal($strPrimeiroItemValor, $strPrimeiroItemDescricao, $strValorItemSelecionado){
        $objMdCorExpedicaoSolicitadaDTO = new MdCorExpedicaoSolicitadaDTO();
        $objMdCorExpedicaoSolicitadaDTO->retNumIdMdCorServicoPostal();
        $objMdCorExpedicaoSolicitadaDTO->retStrNomeServicoPostal();
        $objMdCorExpedicaoSolicitadaDTO->retStrDescricaoServicoPostal();
        $objMdCorExpedicaoSolicitadaDTO->setNumIdMdCorPlp(null, InfraDTO::$OPER_DIFERENTE);
        $objMdCorExpedicaoSolicitadaDTO->setDistinct(true);
        $objMdCorExpedicaoSolicitadaDTO->setOrdStrDescricaoServicoPostal(InfraDTO::$TIPO_ORDENACAO_ASC);


        $objMdCorExpedicaoSolicitadaRN = new MdCorExpedicaoSolicitadaRN();
        $arrObjMdCorExpedicaoSolicitadaDTO = $objMdCorExpedicaoSolicitadaRN->listar($objMdCorExpedicaoSolicitadaDTO);

        return parent::montarSelectArrInfraDTO($strPrimeiroItemValor, $strPrimeiroItemDescricao, $strValorItemSelecionado, $arrObjMdCorExpedicaoSolicitadaDTO, 'IdMdCorServicoPostal','DescricaoServicoPostal');
    }

    public static function verificarImpressaoAR($numIdPlp, $arrNumItemSelecionado) {
      $strResultado = '';
      $strMensagem = '';
      //recuperando a expedição solicitada
      $objExpSolicDTO = new MdCorExpedicaoSolicitadaDTO();
      $objExpSolicDTO->retStrProtocoloFormatadoDocumento();
      $objExpSolicDTO->retStrCodigoRastreamento();
      $objExpSolicDTO->retStrNumeroDocumento();
      $objExpSolicDTO->retStrProtocoloFormatado();
      $objExpSolicDTO->retNumIdMdCorExpedicaoSolicitada();
      $objExpSolicDTO->retStrNomeSerie();

      $objExpSolicDTO->setNumIdMdCorPlp($numIdPlp);
      $objExpSolicDTO->setStrSinNecessitaAr('N');
      $objExpSolicDTO->setDistinct(true);
      $objExpSolicDTO->setOrd('IdMdCorExpedicaoSolicitada', InfraDTO::$TIPO_ORDENACAO_ASC);
      $objExpSolicDTO->setNumIdMdCorExpedicaoSolicitada($arrNumItemSelecionado, InfraDTO::$OPER_IN);

      $objMdCorExpedicaoSolicitadaRN = new MdCorExpedicaoSolicitadaRN();
      $arrObjMdCorExpedicaoSolicitadaDTO = $objMdCorExpedicaoSolicitadaRN->listar($objExpSolicDTO);      

      $numItemSemAr = count($arrObjMdCorExpedicaoSolicitadaDTO);
      if($numItemSemAr== 0){
        $strResultado = self::$STR_IMPRIMIR_AR_OK;
      }else{
        $strItensSemAr = '';
        foreach($arrObjMdCorExpedicaoSolicitadaDTO as $objExpSolicDTO) {
          //$objExpSolicDTO->getStrCodigoRastreamento()
          //$objExpSolicDTO->getStrProtocoloFormatadoDocumento()
          $strItensSemAr .= "{$objExpSolicDTO->getStrProtocoloFormatado()}: Documento Principal: {$objExpSolicDTO->getStrNomeSerie()} {$objExpSolicDTO->getStrNumeroDocumento()} ({$objExpSolicDTO->getStrCodigoRastreamento()})\n";
        }

        if($numItemSemAr == count($arrNumItemSelecionado)) {
          $strResultado = self::$STR_IMPRIMIR_AR_SEM_AR;
          $strMensagem = "As Solicitações de Expedição selecionadas não possuem a necessidade de Aviso de Recebimento. Para imprimir os ARs, selecione apenas Solicitações de Expedição com a necessidade de Aviso de Recebimento.";
        }else{
          $strResultado = self::$STR_IMPRIMIR_AR_MISTO;
          $strMensagem = "As Solicitações de Expedição listadas abaixo não possuem a necessidade de Aviso de Recebimento. Para imprimir os ARs, selecione apenas as Solicitações de Expedição com a necessidade de Aviso de Recebimento.\n\n";
          $strMensagem .= $strItensSemAr;
          $strMensagem .= "\n Deseja imprimir os ARs das solicitações selecionadas que possuem a necessidade de Aviso de Recebimento?";
        }
        
      }

      return "
        <Response>
          <Resultado>{$strResultado}</Resultado>
          <Mensagem>{$strMensagem }</Mensagem>
        </Response>
      ";
    }

    /*
     * Função criada a partir da documentação disponibilizada pelos Correios
     * https://www.correios.com.br/atendimento/developers/arquivos/layout-para-postagem-eletronica-para-ambito-nacional-sara
     * */
		public static function geraDigitoVerificador($num){
			$total    = 0;
			$arrPesos = [8,6,4,2,3,5,9,7];
			$digito   = null;
			$arrLista = str_split($num);

			foreach ( $arrLista as $k => $v ) {
				// num * peso
				$total += (int) $v * $arrPesos[$k];
			}

			$resto = $total % 11;

			if ( $resto > 1 ) {
				$digito = 11 - $resto;
			} else {
				$digito = $resto == 1 ? 0 : 5;
			}
			return $digito;
		}

}
