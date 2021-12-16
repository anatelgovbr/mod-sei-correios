<?

/**
 * TRIBUNAL REGIONAL FEDERAL DA 4ª REGIÃO
 *
 * 12/09/2018 - criado por augusto.cast
 *
 * Versão do Gerador de Código: 1.41.0
 */
require_once dirname(__FILE__) . '/../../../SEI.php';

class MdCorParametroRastreioINT extends InfraINT {

    public static function montarSelectIdMdCorParametroRastreio($strPrimeiroItemValor, $strPrimeiroItemDescricao, $strValorItemSelecionado) {
        $objMdCorParametroRastreioDTO = new MdCorParametroRastreioDTO();
        $objMdCorParametroRastreioDTO->retNumIdMdCorParametroRastreio();

        $objMdCorParametroRastreioDTO->setOrdNumIdMdCorParametroRastreio(InfraDTO::$TIPO_ORDENACAO_ASC);

        $objMdCorParametroRastreioRN = new MdCorParametroRastreioRN();
        $arrObjMdCorParametroRastreioDTO = $objMdCorParametroRastreioRN->listar($objMdCorParametroRastreioDTO);

        return parent::montarSelectArrInfraDTO($strPrimeiroItemValor, $strPrimeiroItemDescricao, $strValorItemSelecionado, $arrObjMdCorParametroRastreioDTO, '', 'IdMdCorParametroRastreio');
    }

    public static function gerarXMLvalidacaoRastreio($dadosPost) {

        $existe = strrpos($dadosPost['endereco'], 'http://');
        $endereco = "";
        
        if(is_int($existe)){
            $endereco = $dadosPost['endereco'];
        } else {
            $endereco = 'http://' . $dadosPost['endereco'];
        }
        
        $arrDados = [
            'endpoint' => $endereco,
            'usuario' => $dadosPost['usuario'],
            'senha' => $dadosPost['senha']
        ];
        $objMdCorWsRastreio = new MdCorWsRastreioRN($arrDados);
        $verifica = $objMdCorWsRastreio->rastrearObjeto('0');
        if (is_array($verifica)) {
            if ($verifica['objeto']['numero'] == 'Erro') {
                $xml = '<Validacao>';
                $xml .= '<error>true</error>';
                $xml .= '<MensagemValidacao>' . $verifica['objeto']['erro'] . '</MensagemValidacao>';
                $xml .= '</Validacao>';
            }
        } elseif ($verifica == false) {
            $xml = '<Validacao>';
            $xml .= '<error>true</error>';
            $xml .= '<MensagemValidacao>Endereço WSDL preenchido incorretamente.</MensagemValidacao>';
            $xml .= '</Validacao>';
        }
        
        return $xml;
    }

}
