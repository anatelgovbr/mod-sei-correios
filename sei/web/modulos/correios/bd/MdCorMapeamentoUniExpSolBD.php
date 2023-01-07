<?php
/**
 * ANATEL
 *
 * 22/12/2016 - criado por marcelo.emiliano@cast.com.br - CAST
 */

require_once dirname(__FILE__).'/../../../SEI.php';

class MdCorMapeamentoUniExpSolBD extends InfraBD {

    public function __construct(InfraIBanco $objInfraIBanco){
        parent::__construct($objInfraIBanco);
    }

}