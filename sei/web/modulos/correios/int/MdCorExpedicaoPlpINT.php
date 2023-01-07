<?php
/**
 * Created by PhpStorm.
 * User: jhon.carvalho
 * Date: 06/10/2017
 * Time: 13:56
 */
require_once dirname(__FILE__) . '/../../../SEI.php';

class MdCorExpedicaoPlpINT extends InfraINT
{
    public static function montarSelectServicoPostal(){
        $retorno ='';
        $retorno .= '<option value=0></option>';
        $retorno .= '<option value=1>Serviço 1</option>';
        $retorno .= '<option value=2>Serviço 2</option>';
        $retorno .= '<option value=3>Serviço 3</option>';
        
        return $retorno;
    }

    public static function montarSelectSituacaoPlp(){
        $retorno ='';
        $retorno .= '<option value=0></option>';
        $retorno .= '<option value=1>Situacao Plp 1</option>';
        $retorno .= '<option value=2>Situacao Plp 2</option>';
        $retorno .= '<option value=3>Situacao Plp 3</option>';

        return $retorno;
    }
}