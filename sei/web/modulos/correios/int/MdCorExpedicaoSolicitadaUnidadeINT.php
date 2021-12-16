<?php

require_once dirname(__FILE__) . '/../../../SEI.php';

class MdCorExpedicaoSolicitadaUnidadeINT extends InfraINT
{

    public static function montarSelectServicoPostal($strPrimeiroItemValor = '', $strPrimeiroItemDescricao = '', $strValorItemSelecionado = '')
    {
        $objMdCorExpedicaoSolicitadaUnidadeRN = new MdCorExpedicaoSolicitadaUnidadeRN();
        $arrObjMdCorServicoPostalDTO          = $objMdCorExpedicaoSolicitadaUnidadeRN->retornaServicoPostalExpedicaoSolicitada();

        return parent::montarSelectArrInfraDTO($strPrimeiroItemValor, $strPrimeiroItemDescricao, $strValorItemSelecionado, $arrObjMdCorServicoPostalDTO, 'IdMdCorServicoPostal', 'Descricao');
    }
}