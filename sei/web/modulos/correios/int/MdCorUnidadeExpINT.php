<?php
/**
 * ANATEL
 *
 * 09/12/2016 - criado por marcelo.emiliano@cast.com.br - CAST
 */

require_once dirname(__FILE__) . '/../../../SEI.php';

class MdCorUnidadeExpINT extends InfraINT
{

    public static function montarSelectUnidade($valuePrimeiroItem = null, $descricaoPrimeiroItem = null, $valorItemSelecionado = null, $componente = true, $retornaExistentes=true)
    {
        $objMdCorUnidadeExpRN = new MdCorUnidadeExpRN();
        $objMdCorUnidadeExpDTO = new MdCorUnidadeExpDTO();
        $objMdCorUnidadeExpDTO->retNumIdUnidade();
        $objMdCorUnidadeExpDTO->retStrSiglaUnidade();
        $objMdCorUnidadeExpDTO->retStrDescricaoUnidade();

        //existentes
        if (!$retornaExistentes) {
            $objMdCorMapeamentoUniExpSolDTO = new MdCorMapeamentoUniExpSolDTO();
            $objMdCorMapeamentoUniExpSolDTO->retNumIdUnidadeExp();

            $objMdCorMapeamentoRN = new MdCorMapeamentoUniExpSolRN();
            $arrObjMdCorMapeamentoUniExpSolDTO = $objMdCorMapeamentoRN->listar($objMdCorMapeamentoUniExpSolDTO);
            $arrUnidadeNotIn = InfraArray::converterArrInfraDTO($arrObjMdCorMapeamentoUniExpSolDTO,'IdUnidadeExp');
            if (count($arrUnidadeNotIn)>0){
                $objMdCorUnidadeExpDTO->setNumIdUnidade($arrUnidadeNotIn, InfraDTO::$OPER_NOT_IN);
            }
        }
        //existentes - fim

        $objRetorno = $objMdCorUnidadeExpRN->listar($objMdCorUnidadeExpDTO);

        foreach($objRetorno as $objValor){
            $novoValor = $objValor->getStrSiglaUnidade() .' - '. $objValor->getStrDescricaoUnidade();
            $objValor->setStrSiglaUnidade($novoValor);
        }

        if(!$componente){
            $objDTO = new MdCorUnidadeExpDTO();
            $objDTO->setStrSiglaUnidade('');
            $objDTO->setNumIdUnidade('');
            array_unshift($objRetorno, $objDTO);
        }

        return parent::montarSelectArrInfraDTO($valuePrimeiroItem, $descricaoPrimeiroItem, $valorItemSelecionado, $objRetorno, 'IdUnidade', 'SiglaUnidade');
    }

    public static function buscarUnidadesExpedidoras()
    {
        $objMdCorUnidadeExpRN = new MdCorUnidadeExpRN();
        $objMdCorUnidadeExpDTO = new MdCorUnidadeExpDTO();
        $objMdCorUnidadeExpDTO->retNumIdUnidade();
        $objMdCorUnidadeExpDTO->setStrSinAtivo('S');

        $objRetorno = $objMdCorUnidadeExpRN->listar($objMdCorUnidadeExpDTO);

        return $objRetorno;
    }
}