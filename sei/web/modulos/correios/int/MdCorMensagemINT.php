<?

require_once dirname(__FILE__).'/../../../SEI.php';

class MdCorMensagemINT extends InfraINT {

    public static $MSG_COR_01 = '@VALOR1@ existe mapeamento dos Servi�os Postais para esta Unidade. Por gentileza, solicitar a equipe respons�vel, pela administra��o dos Correios, o cadastro deste mapeamento.';
    public static $MSG_COR_02 = "Foi identificado que o Endere�amento do Destinat�rio sofreu altera��es entre o cadastro dessa Solicita��o e o presente momento.\nAvalie se pretende acionar o bot�o \"Excluir Solicita��o de Expedi��o\" ou atualizar os dados de Endere�amento clicando no bot�o \"Alterar e Reenviar Solicita��o de Expedi��o\" e retorn�-la ao fluxo de expedi��o com novo Endere�amento.";
    #public static $MSG_COR_02 = "Foi identificado que os dados do contato indicado abaixo como destinat�rio foram alterados entre a solicita��o e o retorno nessa tela de altera��o. \nPara replicar as altera��es nesta solicita��o de expedi��o acione o bot�o \"@VALOR1@\". @VALOR2@";

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
