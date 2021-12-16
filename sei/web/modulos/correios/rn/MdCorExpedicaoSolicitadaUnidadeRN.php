<?php

require_once dirname(__FILE__) . '/../../../SEI.php';

class MdCorExpedicaoSolicitadaUnidadeRN extends InfraRN
{
    public function __construct()
    {
        parent::__construct();
    }

    protected function inicializarObjInfraIBanco()
    {
        return BancoSEI::getInstance();
    }

    /**
     * Retorna os serviços postais vinculados a expedição solicitada da unidade corrente
     */
    protected function retornaServicoPostalExpedicaoSolicitadaConectado()
    {

        SessaoSEI::getInstance()->validarPermissao('md_cor_expedicao_unidade_listar');

        try {
            $arrMdCorServicoPostalDTO       = array();
            $objMdCorExpedicaoSolicitadaDTO = new MdCorExpedicaoSolicitadaDTO();
            $objMdCorExpedicaoSolicitadaDTO->retNumIdMdCorServicoPostal();
            $objMdCorExpedicaoSolicitadaDTO->setNumIdUnidade(SessaoSEI::getInstance()->getNumIdUnidadeAtual());
            $objMdCorExpedicaoSolicitadaRN  = new MdCorExpedicaoSolicitadaRN();
            $arrMdCorExpedicaoSolicitadaDTO = $objMdCorExpedicaoSolicitadaRN->listar($objMdCorExpedicaoSolicitadaDTO);
            if (count($arrMdCorExpedicaoSolicitadaDTO) > 0) {
                $arrIdMdCorServicoPostal  = InfraArray::converterArrInfraDTO($arrMdCorExpedicaoSolicitadaDTO, 'IdMdCorServicoPostal');
                $objMdCorServicoPostalDTO = new MdCorServicoPostalDTO();
                $objMdCorServicoPostalDTO->retTodos();
                $objMdCorServicoPostalDTO->setNumIdMdCorServicoPostal($arrIdMdCorServicoPostal, InfraDTO::$OPER_IN);
                $objMdCorServicoPostalDTO->setStrSinAtivo('S');
                $objMdCorServicoPostalDTO->setOrdStrDescricao(InfraDTO::$TIPO_ORDENACAO_ASC);
                $objMdCorServicoPostalRN  = new MdCorServicoPostalRN();
                $arrMdCorServicoPostalDTO = $objMdCorServicoPostalRN->listar($objMdCorServicoPostalDTO);
            }

            return $arrMdCorServicoPostalDTO;

        } catch (Exception $e) {
            throw new InfraException('Erro ao listar os serviços postais vinculados a expedição solicitada.', $e);
        }

    }

}