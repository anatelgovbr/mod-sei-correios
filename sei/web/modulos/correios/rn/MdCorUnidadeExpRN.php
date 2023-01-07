<?php

/**
 * ANATEL
 *
 * 09/12/2016 - criado por marcelo.emiliano@cast.com.br - CAST
 */
require_once dirname(__FILE__) . '/../../../SEI.php';

class MdCorUnidadeExpRN extends InfraRN {

    public function __construct() {
        parent::__construct();
    }

    protected function inicializarObjInfraIBanco() {
        return BancoSEI::getInstance();
    }

    private function validarNumIdUnidade(MdCorUnidadeExpDTO $objMdCorUnidadeExpDTO, InfraException $objInfraException) {
        if (InfraString::isBolVazia($objMdCorUnidadeExpDTO->getNumIdUnidade())) {
            $objInfraException->adicionarValidacao('Unidade não informada.');
        }
    }

    private function validarStrSinAtivo(MdCorUnidadeExpDTO $objMdCorUnidadeExpDTO, InfraException $objInfraException) {
        if (InfraString::isBolVazia($objMdCorUnidadeExpDTO->getStrSinAtivo())) {
            $objInfraException->adicionarValidacao('Sinalizador de Exclusão Lógica não informado.');
        } else {
            if (!InfraUtil::isBolSinalizadorValido($objMdCorUnidadeExpDTO->getStrSinAtivo())) {
                $objInfraException->adicionarValidacao('Sinalizador de Exclusão Lógica inválido.');
            }
        }
    }

    protected function cadastrarControlado(MdCorUnidadeExpDTO $objMdCorUnidadeExpDTO) {
        try {
            //Valida Permissao
            SessaoSEI::getInstance()->validarPermissao('md_cor_unidade_exp_cadastrar');

            //Regras de Negocio
            $objInfraException = new InfraException();

            $this->validarNumIdUnidade($objMdCorUnidadeExpDTO, $objInfraException);
            $this->validarStrSinAtivo($objMdCorUnidadeExpDTO, $objInfraException);
            $objInfraException->lancarValidacoes();

            $objMdCorUnidadeExpBD = new MdCorUnidadeExpBD($this->getObjInfraIBanco());
            $ret = $objMdCorUnidadeExpBD->cadastrar($objMdCorUnidadeExpDTO);

            //Auditoria

            return $ret;
        } catch (Exception $e) {
            throw new InfraException('Erro cadastrando .', $e);
        }
    }

    protected function alterarControlado(MdCorUnidadeExpDTO $objMdCorUnidadeExpDTO) {
        try {

            //Valida Permissao
            SessaoSEI::getInstance()->validarPermissao('md_cor_unidade_exp_alterar');

            //Regras de Negocio
            $objInfraException = new InfraException();

            if ($objMdCorUnidadeExpDTO->isSetNumIdUnidade()) {
                $this->validarNumIdUnidade($objMdCorUnidadeExpDTO, $objInfraException);
            }
            if ($objMdCorUnidadeExpDTO->isSetStrSinAtivo()) {
                $this->validarStrSinAtivo($objMdCorUnidadeExpDTO, $objInfraException);
            }

            $objInfraException->lancarValidacoes();

            $objMdCorUnidadeExpBD = new MdCorUnidadeExpBD($this->getObjInfraIBanco());
            $objMdCorUnidadeExpBD->alterar($objMdCorUnidadeExpDTO);

            //Auditoria
        } catch (Exception $e) {
            throw new InfraException('Erro alterando .', $e);
        }
    }

    protected function excluirControlado($arrObjMdCorUnidadeExpDTO) {
        try {

            //Valida Permissao
            SessaoSEI::getInstance()->validarPermissao('md_cor_unidade_exp_cadastrar');

            $objMdCorUnidadeExpBD = new MdCorUnidadeExpBD($this->getObjInfraIBanco());
            for ($i = 0; $i < count($arrObjMdCorUnidadeExpDTO); $i++) {
                $objMdCorUnidadeExpBD->excluir($arrObjMdCorUnidadeExpDTO[$i]);
            }

            //Auditoria
        } catch (Exception $e) {
            throw new InfraException('Erro excluindo .', $e);
        }
    }

    protected function consultarConectado(MdCorUnidadeExpDTO $objMdCorUnidadeExpDTO) {
        try {

            //Valida Permissao
            SessaoSEI::getInstance()->validarPermissao('md_cor_unidade_exp_consultar');

            //Regras de Negocio
            //$objInfraException = new InfraException();
            //$objInfraException->lancarValidacoes();

            $objMdCorUnidadeExpBD = new MdCorUnidadeExpBD($this->getObjInfraIBanco());
            $ret = $objMdCorUnidadeExpBD->consultar($objMdCorUnidadeExpDTO);

            //Auditoria

            return $ret;
        } catch (Exception $e) {
            throw new InfraException('Erro consultando .', $e);
        }
    }

    protected function listarConectado(MdCorUnidadeExpDTO $objMdCorUnidadeExpDTO) {
        try {

            //Valida Permissao
            SessaoSEI::getInstance()->validarPermissao('md_cor_unidade_exp_cadastrar');

            //Regras de Negocio
            //$objInfraException = new InfraException();
            //$objInfraException->lancarValidacoes();

            $objMdCorUnidadeExpBD = new MdCorUnidadeExpBD($this->getObjInfraIBanco());
            $ret = $objMdCorUnidadeExpBD->listar($objMdCorUnidadeExpDTO);

            //Auditoria

            return $ret;
        } catch (Exception $e) {
            throw new InfraException('Erro listando .', $e);
        }
    }

    protected function contarConectado(MdCorUnidadeExpDTO $objMdCorUnidadeExpDTO) {
        try {

            //Valida Permissao
            SessaoSEI::getInstance()->validarPermissao('md_cor_unidade_exp_listar');

            //Regras de Negocio
            //$objInfraException = new InfraException();
            //$objInfraException->lancarValidacoes();

            $objMdCorUnidadeExpBD = new MdCorUnidadeExpBD($this->getObjInfraIBanco());
            $ret = $objMdCorUnidadeExpBD->contar($objMdCorUnidadeExpDTO);

            //Auditoria

            return $ret;
        } catch (Exception $e) {
            throw new InfraException('Erro contando .', $e);
        }
    }

    protected function desativarControlado($arrObjMdCorUnidadeExpDTO) {
        try {

            //Valida Permissao
            SessaoSEI::getInstance()->validarPermissao('md_cor_unidade_exp_desativar');

            //Regras de Negocio
            //$objInfraException = new InfraException();
            //$objInfraException->lancarValidacoes();

            $objMdCorUnidadeExpBD = new MdCorUnidadeExpBD($this->getObjInfraIBanco());
            for ($i = 0; $i < count($arrObjMdCorUnidadeExpDTO); $i++) {
                $objMdCorUnidadeExpBD->desativar($arrObjMdCorUnidadeExpDTO[$i]);
            }

            //Auditoria
        } catch (Exception $e) {
            throw new InfraException('Erro desativando .', $e);
        }
    }

    protected function reativarControlado($arrObjMdCorUnidadeExpDTO) {
        try {

            //Valida Permissao
            SessaoSEI::getInstance()->validarPermissao('md_cor_unidade_exp_reativar');

            //Regras de Negocio
            //$objInfraException = new InfraException();
            //$objInfraException->lancarValidacoes();

            $objMdCorUnidadeExpBD = new MdCorUnidadeExpBD($this->getObjInfraIBanco());
            for ($i = 0; $i < count($arrObjMdCorUnidadeExpDTO); $i++) {
                $objMdCorUnidadeExpBD->reativar($arrObjMdCorUnidadeExpDTO[$i]);
            }

            //Auditoria
        } catch (Exception $e) {
            throw new InfraException('Erro reativando .', $e);
        }
    }

    protected function bloquearControlado(MdCorUnidadeExpDTO $objMdCorUnidadeExpDTO) {
        try {

            //Valida Permissao
            SessaoSEI::getInstance()->validarPermissao('md_cor_unidade_exp_consultar');

            //Regras de Negocio
            //$objInfraException = new InfraException();
            //$objInfraException->lancarValidacoes();

            $objMdCorUnidadeExpBD = new MdCorUnidadeExpBD($this->getObjInfraIBanco());
            $ret = $objMdCorUnidadeExpBD->bloquear($objMdCorUnidadeExpDTO);

            //Auditoria

            return $ret;
        } catch (Exception $e) {
            throw new InfraException('Erro bloqueando .', $e);
        }
    }

    protected function validaCamposEnderecoUnidadeControlado($arrUnidades) {

        $unidadeRN = new UnidadeRN();

        $unidadeDTO = new UnidadeDTO();
        $unidadeDTO->retNumIdOrgao();
        $unidadeDTO->retNumIdContato();
        $unidadeDTO->retStrDescricao();
        $unidadeDTO->setNumIdUnidade($arrUnidades, InfraDTO::$OPER_IN);
        $arrObjUnidadeDTO = $unidadeRN->listarRN0127($unidadeDTO);
        
        foreach ($arrObjUnidadeDTO as $objOrgaoDTO) {
            
            $contatoRN = new ContatoRN();

            $objContatoDTO = new ContatoDTO();
            $objContatoDTO->setNumIdContato($objOrgaoDTO->getNumIdContato());        
            $objContatoDTO->retStrBairro();        
            $objContatoDTO->retStrEndereco();
            $objContatoDTO->retNumIdCidade();
            $objContatoDTO->retNumIdUf();
            $objContatoDTO->retStrBairro();
            $objContatoDTO->retStrCep();
            $arrContatoDTO = $contatoRN->consultarRN0324($objContatoDTO);

            $noOrgao = $objOrgaoDTO->getStrDescricao();
            $endereco = $arrContatoDTO->getStrEndereco();
            $noCidade = $arrContatoDTO->getNumIdCidade();
            $uf = $arrContatoDTO->getNumIdUf();
            $bairro = $arrContatoDTO->getStrBairro();
            $cep = $arrContatoDTO->getStrCep();

            if (empty($endereco) or is_null($endereco)) {
                $erros[$noOrgao][] = 'Endereço';
            }

            if (empty($bairro)or is_null($endereco)) {
                $erros[$noOrgao][] = 'Bairro';
            }

            if (empty($uf) or is_null($endereco)) {
                $erros[$noOrgao][] = 'Estado';
            }

            if (empty($noCidade) or is_null($noCidade)) {
                $erros[$noOrgao][] = 'Cidade';
            }

            if (empty($cep) or is_null($cep)) {
                $erros[$noOrgao][] = 'CEP';
            }
        }

        if (is_array($erros) && count($erros) > 0) {

            $str_msg_validacao = "Existe(m) Unidade(s) Expedidora(s) com dados cadastrais incompletos. Antes é necessário preencher os dados abaixo:" . '\n';
            foreach ($erros as $unidade => $erro) {
                foreach ($erro as $dErro) {
                    $str_msg_validacao .= '(' . $unidade . ') - ' . $dErro . '\n';
                }
            }

            echo "<script>";
            echo "alert('" . $str_msg_validacao . "');";
            echo "window.history.back();";
            echo "</script>";
            die;
        }
    }

}
