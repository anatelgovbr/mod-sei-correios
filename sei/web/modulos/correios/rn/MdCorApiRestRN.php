<?php

// require_once dirname(__FILE__) . '/../lib/nusoap/nusoap.php';


/**
 * RN responsálvel por consultar o andamento dos objetos nos correios
 *
 * @author André Luiz <andre.luiz@castgroup.com.br>
 * @since  12/06/2017
 *
 * Atualizado por: Gustavo Camelo - 14/12/2023
 */
class MdCorApiRestRN
{
    private $_endpoint;
    private $_resultado;
    private $_token;
    private $_expiraEm;

    public static $STR_HEADER_ACCEPT = 'application/json';

    public function __construct($params = array())
    {
        $this->_endpoint  = isset($params['endpoint']) ? $params['endpoint'] : '';
        $this->_resultado = isset($params['resultado']) ? $params['resultado'] : null; //U - Ultimo evento, T - Todos os eventos
        $this->_token     = isset($params['token']) ? $params['token'] : '';
        $this->_expiraEm  = isset($params['expiraEm']) ? $params['expiraEm'] : '';
    }

    public function gerarToken($objMdCorIntegToken){

        if ( empty( $objMdCorIntegToken ) ) return ['erro' => 'Objeto de Integração Token inexistente ou inválido.'];

        $nr_postagem = isset( $_POST['txtNumeroCartaoPostagem'] )
                        ? $_POST['txtNumeroCartaoPostagem']
                        : ( new MdCorContratoRN() )->getNumeroPostagemContratoAtivo($objMdCorIntegToken->getNumIdMdCorContrato()); //COM O ID DO CONTRATO PEGAR O NUMERO CORRETO (ARRUMAR AQUI)

        if ( $nr_postagem === false ) return ['suc' => false ,'msg' => 'Número de postagem inexistente ou inválido.'];

        $arrParams = [
            'urlOperacao'    => $objMdCorIntegToken->getStrUrlOperacao(),
            'token'          => $objMdCorIntegToken->getStrToken(),
            'tipoReq'        => MdCorAdmIntegracaoRN::$STR_POST,
            'tipoAuth'       => 'Basic',
            'paramsCorpo'    => ['numero'  => $nr_postagem],
            'paramsUserPass' => ['usuario' => $objMdCorIntegToken->getStrUsuario() , 'senha' => $objMdCorIntegToken->getStrSenha()]
        ];
        
        $rs = $this->executaRequisicaoAPI($arrParams);
        return $rs['suc'] === false ? $rs : $rs['dados'];
    }

    public function rastrearObjeto($codigoRastreio){
        $arrParams = [
            'codigoRef' => ['objetos' => $codigoRastreio],
            'tipoReq'   => MdCorAdmIntegracaoRN::$STR_GET,
        ];
        $rs = $this->executaRequisicaoAPI($arrParams);
        return $rs['suc'] === false ? ['erro' => $rs] : $rs['dados'];
    }

    public function consultarCEP($cep){
        $arrParams = [
            'codigoRef' => ['enderecos' => $cep],
            'tipoReq'   => MdCorAdmIntegracaoRN::$STR_GET,
        ];
        $rs = $this->executaRequisicaoAPI($arrParams);
        return $rs['suc'] === false ? $rs : $rs['dados'];
    }

    public function buscarServicosPostais($strNumeroContratoCorreio, $strCnpj){
        $arrParams = [
            'codigoRef'   => ['empresas' => $strCnpj , 'contratos' => $strNumeroContratoCorreio , 'servicos' => ''],
            'tipoReq'     => MdCorAdmIntegracaoRN::$STR_GET,
            'queryParams' => ['size' => 250]
        ];
        $rs = $this->executaRequisicaoAPI($arrParams);
        return $rs['suc'] === false ? $rs : $rs['dados'];
    }

    public function solicitarEtiquetas($codServPostal,$qtdEtiquetas){
        $arrParams = [
            'tipoReq'     => MdCorAdmIntegracaoRN::$STR_POST,
            'paramsCorpo' => ['codigoServico' => $codServPostal, 'quantidade' => $qtdEtiquetas]
        ];
        $rs = $this->executaRequisicaoAPI($arrParams);
        return $rs['suc'] === false ? $rs : (object) $rs['dados'][0];
    }

    public function gerarPPN($arrObjJson){
        $arrParams = ['tipoReq' => MdCorAdmIntegracaoRN::$STR_POST , 'paramsCorpo' => $arrObjJson , 'operacao' => 'ppn'];
        $rs        = $this->executaRequisicaoAPI($arrParams);
        return $rs['suc'] === false ? $rs : $rs['dados'];
    }

    public function emissaoRotulo($arrParamsApi){
        $arrParams = [
            'tipoReq'     => MdCorAdmIntegracaoRN::$STR_POST,
            'paramsCorpo' => $arrParamsApi
        ];
        $rs = $this->executaRequisicaoAPI($arrParams);
        return $rs['suc'] === false ? $rs : $rs['dados'];
    }

    public function downloadRotulo($idRecibo){
	    $arrParams = [
	        'tipoReq'        => MdCorAdmIntegracaoRN::$STR_GET,
            'codigoRef'      => ['assincrono' => $idRecibo],
            'naoContentType' => true
        ];
	    $rs = $this->executaRequisicaoAPI($arrParams);
	    return $rs['suc'] === false ? $rs : $rs['dados']['dados'];
    }

    public function avisoRecebimento($idPrePostagem){
        $arrParams = [
            'tipoReq'        => MdCorAdmIntegracaoRN::$STR_GET,
            'codigoRef'      => ['avisorecebimento' => $idPrePostagem],
            'naoContentType' => true,
            'accept'         => 'text/html;charset=utf-8',
            'retornaString'  => true
        ];
        $rs = $this->executaRequisicaoAPI($arrParams);
        return $rs['suc'] === false ? $rs : $rs['dados'];
    }

    public function cancelarPPN($arrCodObjeto){

        foreach ( $arrCodObjeto as $codObjeto ) {
            $arrParams = [
                'tipoReq'   => MdCorAdmIntegracaoRN::$STR_DEL,
                'codigoRef' => ['objeto' => $codObjeto]
            ];

            $rs = $this->executaRequisicaoAPI($arrParams);

            if ( $rs['suc'] === false ) return $rs;
        }
        return true;
    }

    /*
     * Método responsável pela execução da requisição API Correios
     *
     * */
    private function executaRequisicaoAPI( $arrParams = null ){
        try {
            if (is_null($arrParams))
                throw new InfraException('Não foi informado nenhum dado de parâmetro para execução da Requisição na API');

            $urlServico = isset($arrParams['urlOperacao']) ? $arrParams['urlOperacao'] : $this->_endpoint;

            if (empty($urlServico))
                throw new InfraException('URL da operação não informada.');

            // parametros montados na URL do servico
            if (key_exists('codigoRef', $arrParams)) {
                foreach ($arrParams['codigoRef'] as $k => $v) {
                    if (!empty($v))
                        $urlServico .= '/' . $k . '/' . $v;
                    else
                        $urlServico .= '/' . $k;
                }
            }

            // verifica se existe parametros na url a ser passado
            if ( isset($arrParams['queryParams']) ) {
                $urlServico .= '?' . http_build_query($arrParams['queryParams']);
            }

            $curl = curl_init($urlServico);

            // parametros montado no corpo da requisicao
            $corpoJson = null;
            if (key_exists('paramsCorpo', $arrParams)) {
                $corpoJson = InfraUtil::converterJSON($arrParams['paramsCorpo']);

                if ( empty($corpoJson) ) {
                    curl_close($curl);
                    throw new InfraException( self::trataErroJson() );
                }

                $arrParams['corpoJson'] = $corpoJson;
                curl_setopt($curl, CURLOPT_POSTFIELDS, $corpoJson);
            }

            // monta dados de parametros especificos
            if ($this->_resultado)
                curl_setopt($curl, CURLOPT_POSTFIELDS, InfraUtil::converterJSON(['resultado' => $this->_resultado]));

            curl_setopt_array($curl, [
                CURLOPT_RETURNTRANSFER => true,
                CURLOPT_SSL_VERIFYPEER => false,
                CURLOPT_CONNECTTIMEOUT => 10,
                CURLOPT_TIMEOUT => 20,
                CURLOPT_CUSTOMREQUEST => $arrParams['tipoReq'],
            ]);

            // monta dados de cabecalho
            $strTpAuth      = $arrParams['tipoAuth'] ?? 'Bearer';
            $strToken       = isset($arrParams['token']) ? $arrParams['token'] : $this->_token;
            $strAutorizacao = "$strTpAuth $strToken";
            $strAccept      = isset($arrParams['accept']) ? $arrParams['accept'] : self::$STR_HEADER_ACCEPT;

            $headers = [
                'Accept: ' . $strAccept,
                'Authorization: ' . $strAutorizacao
            ];

            if (!isset($arrParams['naoContentType'])) $headers[] = 'Content-Type: application/json';

            
            curl_setopt($curl, CURLOPT_HTTPHEADER, $headers);
            
            // monta credenciais usuario e senha
            if (key_exists('paramsUserPass', $arrParams)) {
                $senha = MdCorAdmIntegracaoINT::gerenciaDadosRestritos($arrParams['paramsUserPass']['senha'],'D');
                $strUserPass = "{$arrParams['paramsUserPass']['usuario']}:$senha";
                curl_setopt($curl, CURLOPT_USERPWD, $strUserPass);
            }
            
            // executa requisicao no webservice
            $rs      = curl_exec($curl);
            sleep(1);
            $info    = curl_getinfo($curl);
            $rs      = $this->trataRetornoCurl($info, $rs, $arrParams);
            $strErro = '';
            
            // se ocorreu alguma falha
            if ( $info['http_code'] == 0 ) {
                $strErro = curl_error($curl);
            } else if ( $rs['suc'] === false ) {
                $strErro = $rs['msg'];
            }
            
            curl_close($curl);

            if ( !empty( $strErro) ) throw new InfraException( $strErro );

            return $rs;

        } catch ( Exception $e ) {
            return ['suc' => false , 'msg' => $e->getMessage()];
        }
    }

    private function trataRetornoCurl( $info , $ret , $arrOpcoes = [] ){
        $strCodInfo = isset($info['http_code']) ? "Código: {$info['http_code']} - " : "";
        $arrRet  = ['suc' => false , 'msg' => $strCodInfo , 'dados' => null , 'code' => $info['http_code'] ?? ''];

        if ( ($arrRet['code'] == 200 || $arrRet['code'] == 201) && ( isset( $arrOpcoes['retornaString'] ) && $arrOpcoes['retornaString'] === true ) ) {
            $arrRet['suc']   = true;
            $arrRet['dados'] = $ret;

            return $arrRet;
        }

        $rs = json_decode( $ret , true );

        switch ( $info['http_code'] ) {
            case 200:
            case 201:
                if ( isset( $rs['mensagem'] ) ) {
                    $arrRet['msg'] = utf8_decode( $rs['mensagem'] );
                } else {
                    $arrRet['suc']   = true;
                    $arrRet['dados'] = $rs;
                    $arrRet['msg']   = null;
                }
            break;

            case 401:
                $arrRet['msg'] = 'Acesso não autorizado. Verifique as credenciais informadas.';
            break;

            case 400:
            case 403:
            case 404:
            case 405:
            case 500:
                if ( empty( $rs ) ) {
                    if ( empty($ret) ) $arrRet['msg'] .= 'Erro não identificado';
                    else $arrRet['msg'] .= utf8_decode( $ret );
                } else {
                    if ( isset($rs['msgs']) && !empty($rs['msgs']) ) {
                        $arrRet['msg'] .= utf8_decode( $rs['msgs'][0] );
                    } else if( isset( $rs['detail'] ) && !empty( $rs['detail'] ) ) {
                        $arrRet['msg'] .= utf8_decode( $rs['detail'] );
                    } else {
                        $arrRet['msg'] .= "Erro não Identificado";
                    }
                }
            break;

            default:
                if ( isset( $rs['mensagem'] ) ) : $arrRet['msg'] = utf8_decode( $rs['mensagem'] );
                elseif ( isset( $rs['msg'] ) )  : $arrRet['msg'] = utf8_decode( $rs['msg'] );
                else                            : $arrRet['msg'] = 'Sem Código de Retorno - Falha não Identificada';
                endif;
            break;
        }
        return $arrRet;
    }

    public static function trataErroJson(){
        $msg_erro = '';

        switch ( json_last_error() ) {
            case JSON_ERROR_DEPTH:
                $msg_erro = "Profundidade máxima da pilha foi excedida.";
                break;
            case JSON_ERROR_STATE_MISMATCH:
                $msg_erro = "Inválido ou mal formado JSON.";
                break;
            case JSON_ERROR_CTRL_CHAR:
                $msg_erro = "Caractere de controle inesperado encontrado.";
                break;
            case JSON_ERROR_SYNTAX:
                $msg_erro = "Erro de sintaxe, JSON malformado.";
                break;
            case JSON_ERROR_UTF8:
                $msg_erro = "Caracteres UTF-8 malformados, possivelmente codificados incorretamente.";
                break;
            default:
                $msg_erro = "";
                break;
        }

        return "$msg_erro \n\n Erro: " . json_last_error_msg();
    }

    public function getEndPoint(){
        return $this->_endpoint;
    }
}