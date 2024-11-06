<?php

require_once dirname(__FILE__) . '/../lib/nusoap/nusoap.php';


/**
 * RN responsálvel por consultar o andamento dos objetos nos correios
 *
 * @author André Luiz <andre.luiz@castgroup.com.br>
 * @since  12/06/2017
 */
class MdCorWsRastreioRN extends nusoap_client
{

    private $_endpoint;
    private $_usuario;
    private $_senha;
    private $_tipo;
    private $_resultado;
    private $_lingua;
    private $_wsdl;
    private $_token;
    private $_expiraEm;

    public function __construct($params = array())
    {
        $this->_endpoint  = isset($params['endpoint']) ? $params['endpoint'] : '';
        $this->_usuario   = isset($params['usuario']) ? $params['usuario'] : '';
        $this->_senha     = isset($params['senha']) ? $params['senha'] : '';
        $this->_tipo      = isset($params['tipo']) ? $params['tipo'] : 'L';
        $this->_resultado = isset($params['resultado']) ? $params['resultado'] : 'T'; //U - Ultimo evento, T - Todos os eventos
        $this->_lingua    = isset($params['lingua']) ? $params['lingua'] : '101';
        $this->_wsdl      = isset($params['wsdl']) ? $params['wsdl'] : true;
	    $this->_token     = isset($params['token']) ? $params['token'] : '';
	    $this->_expiraEm  = isset($params['expiraEm']) ? $params['expiraEm'] : '';

        //parent::nusoap_client($this->_endpoint, $this->_wsdl);

    }

    /*
    public function rastrearObjeto($codigoRastreio)
    {
        try {
            $evento = array(
                'usuario'   => $this->_usuario,
                'senha'     => $this->_senha,
                'tipo'      => $this->_tipo,
                'resultado' => $this->_resultado,
                'lingua'    => $this->_lingua,
                'objetos'   => $codigoRastreio
            );

            $client  = $this->getProxy();
            $exists =  method_exists($client, 'buscaEventos') ? true : false;

            if($exists){
              $eventos = $client->buscaEventos($evento);
              return $eventos['return'];
            }else{
              return false;
            }
        } catch (Exception $e) {
            throw new Exception('Erro ao rastrear o objeto' . $e->getMessage());
        }
    }
    */

    public function rastrearObjeto($codigoRastreio){
	    //$objInfraException = new InfraException();

	    $urlServico = $this->_endpoint .'/'. $codigoRastreio;

	    if ( !filter_var( $urlServico , FILTER_VALIDATE_URL ) )
		    throw new InfraException("Endereço do WebService inválido!");

	    $curl = curl_init( $urlServico );

	    curl_setopt_array( $curl, [
		    CURLOPT_RETURNTRANSFER => true,
		    CURLOPT_SSL_VERIFYPEER => false,
		    CURLOPT_CONNECTTIMEOUT => 5,
		    CURLOPT_TIMEOUT        => 20,
		    CURLOPT_CUSTOMREQUEST  => 'GET'
	    ]);

	    // monta dados de parametros necessarios
	    if ( $this->_resultado ) {
		    $payload = json_encode( ['resultado' => $this->_resultado] );
		    curl_setopt( $curl, CURLOPT_POSTFIELDS, $payload );
	    }

	    // monta dados de cabecalho
	    $headers = [
		    'Content-Type: application/json',
		    'Accept: application/json',
		    'Authorization: Bearer ' . $this->_token
	    ];

	    curl_setopt($curl, CURLOPT_HTTPHEADER, $headers);

	    // executa a consulta no webservice
	    $ret  = curl_exec( $curl );
	    $info = curl_getinfo( $curl );
	    $ret  = self::trataRetornoCurl( $info , $ret );

	    if ( $info['http_code'] == 0 ) $ret['msg'] = curl_error($curl);

	    if ( $ret['suc'] === false ) {
		    $strError = "STATUS CODE: " . $ret['code'] . " - ". $ret['msg'];
		    return ['objeto' => ['erro' => $strError , 'numero' => $codigoRastreio] ];
	    } else {
		    curl_close( $curl );
		    return $ret['dados'];
	    }
    }

	public function gerarToken($arrParametro){
		//$objInfraException = new InfraException();

		$urlServico = 'https://api.correios.com.br/token/v1/autentica/cartaopostagem';

		if ( !filter_var( $urlServico , FILTER_VALIDATE_URL ) )
			throw new InfraException("Endereço do WebService inválido!");

		$curl = curl_init( $urlServico );

		curl_setopt_array( $curl, [
			CURLOPT_RETURNTRANSFER => true,
			CURLOPT_SSL_VERIFYPEER => false,
			CURLOPT_CONNECTTIMEOUT => 5,
			CURLOPT_TIMEOUT        => 20,
			CURLOPT_CUSTOMREQUEST  => 'POST'
		]);

		// monta dados de parametros necessarios, neste caso, somente o numero do cartao de postagem
		//if ( $this->_resultado ) {
			$payload = json_encode( ['numero' => '0073770523'] );
			curl_setopt( $curl, CURLOPT_POSTFIELDS, $payload );
		//}

		// monta dados de cabecalho
		$headers = [
			'Content-Type: application/json',
			'Accept: application/json',
			'Authorization: Basic YW5hdGVsMDE6bVAzZldSeGRTZUdyTFl4a2FzeldJTVBQR0FKQkdkZFczRDlkdzZwZA==',
		];
		curl_setopt($curl, CURLOPT_HTTPHEADER, $headers);

		// monta credenciais usuario e senha
		curl_setopt($curl,CURLOPT_USERPWD,"{$arrParametro['usuario']}:{$arrParametro['senha']}");

		// executa a consulta no webservice
		$ret  = curl_exec( $curl );
		$info = curl_getinfo( $curl );
		$ret  = self::trataRetornoCurl( $info , $ret );

		if ( $info['http_code'] == 0 ) $ret['msg'] = curl_error($curl);

		if ( $ret['suc'] === false ) {
			$strError = "STATUS CODE: " . $ret['code'] . " - ". $ret['msg'];
			return ['objeto' => ['erro' => $strError , 'numero' => '0000000000'] ];
		} else {
			curl_close( $curl );
			return $ret['dados'];
		}
	}

	private static function trataRetornoCurl( $info , $ret ){
		$arrRet = ['suc' => false , 'msg' => null , 'dados' => null , 'code' => $info['http_code']];
		#$type   = gettype( $ret );
		$rs     = json_decode( $ret , true );

		switch ( $info['http_code'] ) {
			case 200:
			case 201:
				$arrRet['suc']   = true;
				$arrRet['dados'] = $rs;
				break;

			case 400:
			case 403:
			case 404:
			case 500:
				$arrRet['msg'] = utf8_decode( $rs['msgs'][0] );
				break;

			default:
				$arrRet['msg'] = 'Falha não Identificada';
				break;
		}

		return $arrRet;
	}
}