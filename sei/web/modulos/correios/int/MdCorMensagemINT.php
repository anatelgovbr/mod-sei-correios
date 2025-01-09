<?

require_once dirname(__FILE__).'/../../../SEI.php';

class MdCorMensagemINT extends InfraINT {

    public static $MSG_COR_01 = '@VALOR1@ existe mapeamento dos Serviços Postais para esta Unidade. Por gentileza, solicitar a equipe responsável, pela administração dos Correios, o cadastro deste mapeamento.';
    public static $MSG_COR_02 = "Foi identificado que o Endereçamento do Destinatário sofreu alterações entre o cadastro dessa Solicitação e o presente momento.\nAvalie se pretende acionar o botão \"Excluir Solicitação de Expedição\" ou atualizar os dados de Endereçamento clicando no botão \"Alterar e Reenviar Solicitação de Expedição\" e retorná-la ao fluxo de expedição com novo Endereçamento.";
    #public static $MSG_COR_02 = "Foi identificado que os dados do contato indicado abaixo como destinatário foram alterados entre a solicitação e o retorno nessa tela de alteração. \nPara replicar as alterações nesta solicitação de expedição acione o botão \"@VALOR1@\". @VALOR2@";

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
