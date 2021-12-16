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

    public function __construct($params = array())
    {
        $this->_endpoint  = isset($params['endpoint']) ? $params['endpoint'] : '';
        $this->_usuario   = isset($params['usuario']) ? $params['usuario'] : '';
        $this->_senha     = isset($params['senha']) ? $params['senha'] : '';
        $this->_tipo      = isset($params['tipo']) ? $params['tipo'] : 'L';
        $this->_resultado = isset($params['resultado']) ? $params['resultado'] : 'T'; //U - Ultimo evento, T - Todos os eventos
        $this->_lingua    = isset($params['lingua']) ? $params['lingua'] : '101';
        $this->_wsdl      = isset($params['wsdl']) ? $params['wsdl'] : true;

        parent::nusoap_client($this->_endpoint, $this->_wsdl);

    }

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

}