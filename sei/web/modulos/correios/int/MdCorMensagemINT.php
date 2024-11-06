<?

require_once dirname(__FILE__).'/../../../SEI.php';

class MdCorMensagemINT extends InfraINT {

    //public static $MSG_UTL_01 = 'Não é possível @VALOR1@ este Tipo de Controle pois as Unidades listadas abaixo estão associadas a outro Tipo de Controle @VALOR2@:';
    public static $MSG_COR_01 = '@VALOR1@ existe mapeamento dos Serviços Postais para esta Unidade. Por gentileza, solicitar a equipe responsável, pela administração dos Correios, o cadastro deste mapeamento.';
    public static $MSG_COR_02 = "Foi identificado que os dados do contato indicado abaixo como destinatário foram alterados entre a solicitação e o retorno nessa tela de alteração. Para replicar as alterações nesta solicitação de expedição acione o botão \"Alterar Solicitação\". Caso não queira replicar as alterações no contato acione o botão \"Fechar\"";

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
