<?php

/**
 * Created by PhpStorm.
 * User: wilton.cast
 * Date: 26/12/2016
 * Time: 16:17
 */
class MdCorClientWsRN
{
    const WSDL_BUSCA_CLIENTE = 'AtendeCliente';
    const AMBIENTE_DSV = 'dsv';
    const AMBIENTE_PRD = 'prd';
    const URL_DSV = 'https://apphom.correios.com.br/SigepMasterJPA/AtendeClienteService/AtendeCliente?wsdl';
    const URL_PRD = 'https://apps.correios.com.br/SigepMasterJPA/AtendeClienteService/AtendeCliente?wsdl';

    private static $usuario = 'sigep';
    private static $senha = 'n5f9t8';

    private static $arrWsdl = array(
        self::WSDL_BUSCA_CLIENTE => array(
            self::AMBIENTE_DSV => self::URL_DSV,
            self::AMBIENTE_PRD => self::URL_PRD,
        )
    );

    private function __construct()
    {}

    /**
     * @param string $wsdl
     * @return null|SoapClient
     */
    public static function gerarCliente($wsdl)
    {
        try {

            //$wsdl = self::retornaWsdlPorTipo(self::WSDL_BUSCA_CLIENTE);

            return new SoapClient ($wsdl, array(
                'stream_context' => stream_context_create(
                    array(
                            'ssl' => array(
                                'verify_peer' => false,
                                'verify_peer_name' => false,
                            ),
                            'http' => array(
                                'protocol_version' => '1.1',
                                'header' => 'Connection: Close'
                            )
                        )
                    )
                )
            );

        } catch (SoapFault $soapFault) {
          throw new InfraException('Problema ao acessar o Web Service dos Correios. Por Favor, tentar mais tarde.');
        }
    }

    /**
     * @param string $tipo
     * @return string gmixed
     */
    public static function retornaWsdlPorTipo($tipo)
    {
        $objConfiguracaoSEI = ConfiguracaoSEI::getInstance();
        $arrConfiguracoes = $objConfiguracaoSEI->getArrConfiguracoes();

        $ambiente = self::AMBIENTE_DSV;
        if ($arrConfiguracoes['SEI']['Producao'] == true) {
            $ambiente = self::AMBIENTE_PRD;
        }

        return self::$arrWsdl[$tipo][$ambiente];
    }

    /**
     * @param integer $idContrato
     * @param integer $idCartaoPostagem
     * @param string $wsdl
     * @return string
     */
    public static function buscarServicosPostais($idContrato, $idCartaoPostagem, $wsdl, $strUsuario, $strSenha)
    {
      try{

        //return self::retornarXmlServicosPostaisMock();
        $cliente = self::gerarCliente($wsdl);
        
        $response = $cliente->buscaCliente(array(
            'idContrato'       => $idContrato,
            'idCartaoPostagem' => $idCartaoPostagem,
            'usuario'          => $strUsuario,
            'senha'            => $strSenha
        ));
        
        return self::retornarXmlServicosPostais($response);
      }catch (SoapFault $e){
        throw new InfraException($e->getMessage(). 'Por Favor, verifique suas Credencias.');
      }
    }

    /**
     * @param $obj
     * @return array
     */
    private static function retornarArrayServicosPostais($obj)
    {
        $ret = array();
        $servicos = $obj->return->contratos->cartoesPostagem->servicos;

        foreach($servicos as $servico)
        {
            $item = array(
                'descricao' => $servico->descricao,
                'id' => $servico->id,
                'codigo' => $servico->codigo,
            );
            $ret[] = $item;
        }
        return $ret;
    }

    private static function retornarXmlServicosPostais($obj)
    {
        $xml = "<ServicoPostalLista>";
        $servicos = $obj->return->contratos->cartoesPostagem->servicos;
        foreach($servicos as $servico)
        {
            $xml .= "<ServicoPostal><Descricao><![CDATA[{$servico->descricao}]]></Descricao><Id><![CDATA[{$servico->id}]]></Id><Codigo><![CDATA[{$servico->codigo}]]></Codigo></ServicoPostal>";
        }
        $xml .= "</ServicoPostalLista>";
        return $xml;
    }

    private static function retornarXmlServicosPostaisMock()
    {
        $servico1 = new stdClass();
        $servico1->descricao = 'sedex 10';
        $servico1->id = '1';
        $servico1->codigo = '10';

        $servico2 = new stdClass();
        $servico2->descricao = 'sedex pac';
        $servico2->id = '2';
        $servico2->codigo = '20';

        $servico3 = new stdClass();
        $servico3->descricao = 'carta';
        $servico3->id = '3';
        $servico3->codigo = '30';

        $servicos = array(
            $servico1, $servico2, $servico3
        );

        $xml = "<ServicoPostalLista>";
        foreach($servicos as $servico)
        {
            $xml .= "<ServicoPostal><Descricao><![CDATA[{$servico->descricao}]]></Descricao><Id><![CDATA[{$servico->id}]]></Id><Codigo><![CDATA[{$servico->codigo}]]></Codigo></ServicoPostal>";
        }
        $xml .= "</ServicoPostalLista>";
        return $xml;
    }

}