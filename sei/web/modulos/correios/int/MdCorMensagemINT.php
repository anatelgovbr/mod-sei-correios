<?

require_once dirname(__FILE__).'/../../../SEI.php';

class MdCorMensagemINT extends InfraINT {

    //public static $MSG_UTL_01 = 'N�o � poss�vel @VALOR1@ este Tipo de Controle pois as Unidades listadas abaixo est�o associadas a outro Tipo de Controle @VALOR2@:';
    public static $MSG_COR_01 = '@VALOR1@ existe mapeamento dos Servi�os Postais para esta Unidade. Por gentileza, solicitar a equipe respons�vel, pela administra��o dos Correios, o cadastro deste mapeamento.';
    public static $MSG_COR_02 = "Foi identificado que os dados do contato indicado abaixo como destinat�rio foram alterados entre a solicita��o e o retorno nessa tela de altera��o. Para replicar as altera��es nesta solicita��o de expedi��o acione o bot�o \"Alterar Solicita��o\". Caso n�o queira replicar as altera��es no contato acione o bot�o \"Fechar\"";

    public static function getMensagem($msg, $arrParams = null){
        $isPersonalizada = count(explode('@VALOR', self::$MSG_UTL_10)) > 1;

        if($isPersonalizada && !is_null($arrParams)){
            $msgPersonalizada = self::setMensagemPadraoPersonalizada($msg, $arrParams);
            return $msgPersonalizada;
        }

        return $msg;
    }

    public static function setMensagemPadraoPersonalizada($msg, $arrParametros = null)
    {
        if(!is_array($arrParametros)){
            $arrParametros = array($arrParametros);
        }

        if ($msg != '') {
            $arrSubstituicao = array();

            foreach ($arrParametros as $key => $param) {
                $vl = $key + 1;
                $arrSubstituicao[] = '@VALOR' . $vl . '@';
            }
            $msgRetorno = str_replace($arrSubstituicao, $arrParametros, $msg);
            return $msgRetorno;
        }

        return '';
    }

}
