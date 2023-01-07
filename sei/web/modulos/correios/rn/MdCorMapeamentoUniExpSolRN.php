<?php
/**
 * ANATEL
 *
 * 22/12/2016 - criado por marcelo.emiliano@cast.com.br - CAST
 */

require_once dirname(__FILE__).'/../../../SEI.php';

class MdCorMapeamentoUniExpSolRN extends InfraRN {

    public function __construct(){
        parent::__construct();
    }

    protected function inicializarObjInfraIBanco(){
        return BancoSEI::getInstance();
    }

    private function validarNumIdUnidade(MdCorMapeamentoUniExpSolDTO $objMdCorMapeamentoUniExpSolDTO, InfraException $objInfraException){
        if (InfraString::isBolVazia($objMdCorMapeamentoUniExpSolDTO->getNumIdUnidadeExp())){
            $objInfraException->adicionarValidacao('Unidade não informada.');
        }
        if (InfraString::isBolVazia($objMdCorMapeamentoUniExpSolDTO->getNumIdUnidadeSolicitante())){
            $objInfraException->adicionarValidacao('Unidade não informada.');
        }
    }

    private function validarStrSinAtivo(MdCorMapeamentoUniExpSolDTO $objMdCorMapeamentoUniExpSolDTO, InfraException $objInfraException){
        if (InfraString::isBolVazia($objMdCorMapeamentoUniExpSolDTO->getStrSinAtivo())){
            $objInfraException->adicionarValidacao('Sinalizador de Exclusão Lógica não informado.');
        }else{
            if (!InfraUtil::isBolSinalizadorValido($objMdCorMapeamentoUniExpSolDTO->getStrSinAtivo())){
                $objInfraException->adicionarValidacao('Sinalizador de Exclusão Lógica inválido.');
            }
        }
    }

    protected function cadastrarControlado(MdCorMapeamentoUniExpSolDTO $objMdCorMapeamentoUniExpSolDTO) {
        try{
            //Valida Permissao
            SessaoSEI::getInstance()->validarPermissao('md_cor_mapeamento_uni_exp_sol_cadastrar');

            //Regras de Negocio
            $objInfraException = new InfraException();

            $this->validarStrSinAtivo($objMdCorMapeamentoUniExpSolDTO, $objInfraException);
            $objInfraException->lancarValidacoes();

            $objMdCorMapeamentoUniExpSolBD = new MdCorMapeamentoUniExpSolBD($this->getObjInfraIBanco());
            $ret = $objMdCorMapeamentoUniExpSolBD->cadastrar($objMdCorMapeamentoUniExpSolDTO);

            //Auditoria

            return $ret;

        }catch(Exception $e){
            throw new InfraException('Erro cadastrando .',$e);
        }
    }

    protected function alterarControlado(MdCorMapeamentoUniExpSolDTO $objMdCorMapeamentoUniExpSolDTO){
        try {

            //Valida Permissao
            SessaoSEI::getInstance()->validarPermissao('md_cor_mapeamento_uni_exp_sol_alterar');

            //Regras de Negocio
            $objInfraException = new InfraException();

            if ($objMdCorMapeamentoUniExpSolDTO->isSetStrSinAtivo()){
                $this->validarStrSinAtivo($objMdCorMapeamentoUniExpSolDTO, $objInfraException);
            }

            $objInfraException->lancarValidacoes();

            $objMdCorMapeamentoUniExpSolBD = new MdCorMapeamentoUniExpSolBD($this->getObjInfraIBanco());
            $objMdCorMapeamentoUniExpSolBD->alterar($objMdCorMapeamentoUniExpSolDTO);

            //Auditoria

        }catch(Exception $e){
            throw new InfraException('Erro alterando .',$e);
        }
    }

    protected function excluirControlado($arrObjMdCorMapeamentoUniExpSolDTO){
        try {

            //Valida Permissao
            SessaoSEI::getInstance()->validarPermissao('md_cor_mapeamento_uni_exp_sol_excluir');

            $objMdCorMapeamentoUniExpSolBD = new MdCorMapeamentoUniExpSolBD($this->getObjInfraIBanco());
            for($i=0;$i<count($arrObjMdCorMapeamentoUniExpSolDTO);$i++){
                $objMdCorMapeamentoUniExpSolBD->excluir($arrObjMdCorMapeamentoUniExpSolDTO[$i]);
            }

            //Auditoria

        }catch(Exception $e){
            throw new InfraException('Erro excluindo .',$e);
        }
    }

    protected function consultarConectado(MdCorMapeamentoUniExpSolDTO $objMdCorMapeamentoUniExpSolDTO){
        try {

            //Valida Permissao
            SessaoSEI::getInstance()->validarPermissao('md_cor_mapeamento_uni_exp_sol_consultar');

            //Regras de Negocio
            //$objInfraException = new InfraException();

            //$objInfraException->lancarValidacoes();

            $objMdCorMapeamentoUniExpSolBD = new MdCorMapeamentoUniExpSolBD($this->getObjInfraIBanco());
            $ret = $objMdCorMapeamentoUniExpSolBD->consultar($objMdCorMapeamentoUniExpSolDTO);

            //Auditoria

            return $ret;
        }catch(Exception $e){
            throw new InfraException('Erro consultando .',$e);
        }
    }

    protected function listarConectado(MdCorMapeamentoUniExpSolDTO $objMdCorMapeamentoUniExpSolDTO) {
        try {

            //Valida Permissao
           // SessaoSEI::getInstance()->validarPermissao('md_cor_mapeamento_uni_exp_sol_listar');

            //Regras de Negocio
            //$objInfraException = new InfraException();
            //$objInfraException->lancarValidacoes();

            $objMdCorMapeamentoUniExpSolBD = new MdCorMapeamentoUniExpSolBD($this->getObjInfraIBanco());
            $ret = $objMdCorMapeamentoUniExpSolBD->listar($objMdCorMapeamentoUniExpSolDTO);
            //Auditoria
            return $ret;

        }catch(Exception $e){
            throw new InfraException('Erro listando .',$e);
        }
    }

    protected function contarConectado(MdCorUnidadeExpDTO $objMdCorUnidadeExpDTO){
        try {

            //Valida Permissao
            SessaoSEI::getInstance()->validarPermissao('md_cor_mapeamento_uni_exp_sol_listar');

            //Regras de Negocio
            //$objInfraException = new InfraException();

            //$objInfraException->lancarValidacoes();

            $objMdCorUnidadeExpBD = new MdCorUnidadeExpBD($this->getObjInfraIBanco());
            $ret = $objMdCorUnidadeExpBD->contar($objMdCorUnidadeExpDTO);

            //Auditoria

            return $ret;
        }catch(Exception $e){
            throw new InfraException('Erro contando .',$e);
        }
    }

    protected function desativarControlado($arrObjMdCorUnidadeExpDTO){
        try {

            //Valida Permissao
            SessaoSEI::getInstance()->validarPermissao('md_cor_mapeamento_uni_exp_sol_desativar');

            //Regras de Negocio
            //$objInfraException = new InfraException();

            //$objInfraException->lancarValidacoes();

            $objMdCorUnidadeExpBD = new MdCorUnidadeExpBD($this->getObjInfraIBanco());
            for($i=0;$i<count($arrObjMdCorUnidadeExpDTO);$i++){
                $objMdCorUnidadeExpBD->desativar($arrObjMdCorUnidadeExpDTO[$i]);
            }

            //Auditoria

        }catch(Exception $e){
            throw new InfraException('Erro desativando .',$e);
        }
    }

    protected function reativarControlado($arrObjMdCorUnidadeExpDTO){
        try {

            //Valida Permissao
            SessaoSEI::getInstance()->validarPermissao('md_cor_mapeamento_uni_exp_sol_reativar');

            //Regras de Negocio
            //$objInfraException = new InfraException();

            //$objInfraException->lancarValidacoes();

            $objMdCorUnidadeExpBD = new MdCorUnidadeExpBD($this->getObjInfraIBanco());
            for($i=0;$i<count($arrObjMdCorUnidadeExpDTO);$i++){
                $objMdCorUnidadeExpBD->reativar($arrObjMdCorUnidadeExpDTO[$i]);
            }

            //Auditoria

        }catch(Exception $e){
            throw new InfraException('Erro reativando .',$e);
        }
    }

    protected function bloquearControlado(MdCorUnidadeExpDTO $objMdCorUnidadeExpDTO){
        try {

            //Valida Permissao
            SessaoSEI::getInstance()->validarPermissao('md_cor_mapeamento_uni_exp_sol_consultar');

            //Regras de Negocio
            //$objInfraException = new InfraException();

            //$objInfraException->lancarValidacoes();

            $objMdCorUnidadeExpBD = new MdCorUnidadeExpBD($this->getObjInfraIBanco());
            $ret = $objMdCorUnidadeExpBD->bloquear($objMdCorUnidadeExpDTO);

            //Auditoria

            return $ret;
        }catch(Exception $e){
            throw new InfraException('Erro bloqueando .',$e);
        }
    }

    protected function listarComFiltroConectado(MdCorMapeamentoUniExpSolDTO $objDTO) {
       try {

           //Valida Permissao
           //'md_cor_map_unid_servico_cadastrar', 'md_cor_map_unid_servico_consultar', 'md_cor_map_unid_servico_alterar', 'md_cor_map_unid_servico_desativar'
           //SessaoSEI::getInstance()->validarAuditarPermissao('md_cor_map_unid_servico_cadastrar',__METHOD__,$objDTO);

           //Regras de Negocio
           //$objInfraException = new InfraException();

           //$objInfraException->lancarValidacoes();

           if ($objDTO->isSetStrPalavrasPesquisa() && trim($objDTO->getStrPalavrasPesquisa())!=''){

               $strPalavrasPesquisa = InfraString::transformarCaixaAlta(trim($objDTO->getStrPalavrasPesquisa()));
               $arrPalavrasPesquisa = explode(' ',$strPalavrasPesquisa);

               for($i=0;$i<count($arrPalavrasPesquisa);$i++){
                   $arrPalavrasPesquisa[$i] = '%'.$arrPalavrasPesquisa[$i].'%';
               }

               if (count($arrPalavrasPesquisa)==1){
                   $objDTO->adicionarCriterio(array('SiglaUnidadeSolicitante'),array(InfraDTO::$OPER_LIKE),$arrPalavrasPesquisa[0],null,'filtroSigla');
                   $objDTO->adicionarCriterio(array('DescricaoUnidadeSolicitante'),array(InfraDTO::$OPER_LIKE),$arrPalavrasPesquisa[0],null,'filtroDescricao');
               }else{
                   $objDTO->adicionarCriterio(array_fill(0,count($arrPalavrasPesquisa),'SiglaUnidadeSolicitante'),
                                              array_fill(0,count($arrPalavrasPesquisa),InfraDTO::$OPER_LIKE),
                                              $arrPalavrasPesquisa,
                                              array_fill(0,count($arrPalavrasPesquisa)-1,InfraDTO::$OPER_LOGICO_OR),
                                              'filtroSigla');

                   $objDTO->adicionarCriterio(array_fill(0,count($arrPalavrasPesquisa),'DescricaoUnidadeSolicitante'),
                                              array_fill(0,count($arrPalavrasPesquisa),InfraDTO::$OPER_LIKE),
                                              $arrPalavrasPesquisa,
                                              array_fill(0,count($arrPalavrasPesquisa)-1,InfraDTO::$OPER_LOGICO_AND),
                                              'filtroDescricao');
               }

               $objDTO->agruparCriterios(array('filtroSigla','filtroDescricao'),InfraDTO::$OPER_LOGICO_OR);

           }

           $objBD = new MdCorMapeamentoUniExpSolBD($this->getObjInfraIBanco());
           $ret = $objBD->listar($objDTO);

           //Auditoria

           return $ret;

        }catch(Exception $e){
            throw new InfraException('Erro listando outras Unidades com filtro.',$e);
        }
    }

    protected function alterarVerificarServicosControlado($params){
        $post = $params;

        $arrUnidadesSolicitantes = PaginaSEI::getInstance()->getArrValuesSelect($post['hdnIdUnidades']);

        // @TODO Bloco de validações - Não precisa $this->validarCadastro($params);

        try {
            $objMdCorMapeamentoUniExpSolDTO = new MdCorMapeamentoUniExpSolDTO();

            //existentes
            $objMdCorMapeamentoUniExpSolDTO->retNumIdUnidadeSolicitante();
            $objMdCorMapeamentoUniExpSolDTO->setNumIdUnidadeExp($post['selUnidadeExpedidora']);
            $arrMdCorMapeamentoUniExpSolDTOExistente = $this->listar($objMdCorMapeamentoUniExpSolDTO);

            //enviadas
            $arrUnidadesSolicitantesExistentes = InfraArray::converterArrInfraDTO($arrMdCorMapeamentoUniExpSolDTOExistente,'IdUnidadeSolicitante');
            $arrUnidadesSolicitantesEnviadas = PaginaSEI::getInstance()->getArrValuesSelect($post['hdnIdUnidades']);
            $arrUnidadesSolicitantesExcluir = array_diff($arrUnidadesSolicitantesExistentes, $arrUnidadesSolicitantesEnviadas);
            $arrUnidadesSolicitantesIncluir = array_diff($arrUnidadesSolicitantesEnviadas, $arrUnidadesSolicitantesExistentes);

            $incluir = true;

            //checando se existe serviços
            if (count($arrUnidadesSolicitantesExcluir)){
                $incluir = false;
                $objMdCorMapUnidServicoDTO = new MdCorMapUnidServicoDTO();
                $objMdCorMapUnidServicoRN = new MdCorMapUnidServicoRN();
                $objMdCorMapUnidServicoDTO->retNumIdUnidadeSolicitante();
                $objMdCorMapUnidServicoDTO->retStrSiglaUnidade();                    
                $objMdCorMapUnidServicoDTO->setDistinct(true);
                $objMdCorMapUnidServicoDTO->setNumIdUnidadeSolicitante($arrUnidadesSolicitantesExcluir,InfraDTO::$OPER_IN);

                $arrObjMdCorMapUnidServicoDTO = $objMdCorMapUnidServicoRN->listar($objMdCorMapUnidServicoDTO);  
                if (count($arrObjMdCorMapUnidServicoDTO)>0){
                    $objInfraException = new InfraException();
                    $objInfraException->adicionarValidacao('Unidade(s) com serviço(s) vinculados:\n' . implode(',',InfraArray::converterArrInfraDTO($arrObjMdCorMapUnidServicoDTO,'SiglaUnidade')));
                    $objInfraException->lancarValidacoes();
                }else{
                    $objMdCorMapeamentoUniExpSolDTO = new MdCorMapeamentoUniExpSolDTO();
                    $objMdCorMapeamentoUniExpSolDTO->retNumIdUnidadeExp();
                    $objMdCorMapeamentoUniExpSolDTO->retNumIdUnidadeSolicitante();

                    $objMdCorMapeamentoUniExpSolDTO->setNumIdUnidadeSolicitante($arrUnidadesSolicitantesExcluir,InfraDTO::$OPER_IN);
                    $this->excluir($this->listar($objMdCorMapeamentoUniExpSolDTO));
                    $incluir = true;
                }
            }

            if ($incluir){
                $objMdCorMapeamentoUniExpSolDTO = new MdCorMapeamentoUniExpSolDTO();
                $objMdCorMapeamentoUniExpSolDTO->setNumIdUnidadeExp($post['selUnidadeExpedidora']);
                foreach ($arrUnidadesSolicitantesIncluir as $unidade) {
                    $objMdCorMapeamentoUniExpSolDTO->setNumIdUnidadeSolicitante($unidade);
                    $objMdCorMapeamentoUniExpSolDTO->setStrSinAtivo('S');
                    $objMdCorMapeamentoUniExpSol = $this->cadastrar($objMdCorMapeamentoUniExpSolDTO);
                }
            }

            return true;

        } catch (Exception $e) {
            PaginaSEI::getInstance()->processarExcecao($e);
        }

    }

    protected function excluirDesativarVerificarServicosControlado($params){

        $operacao = $params;

        $arrUnidadesSolicitantes = PaginaSEI::getInstance()->getArrValuesSelect($post['hdnIdUnidades']);

        // @TODO Bloco de validações - Não precisa $this->validarCadastro($params);

        try {
            $objMdCorMapeamentoUniExpSolDTO = new MdCorMapeamentoUniExpSolDTO();

            $arrStrIds = PaginaSEI::getInstance()->getArrStrItensSelecionados();

            $objMdCorMapeamentoUniExpSolDTO = new MdCorMapeamentoUniExpSolDTO();
            $objMdCorMapeamentoUniExpSolDTO->setNumIdUnidadeExp($arrStrIds[0]);
            $objMdCorMapeamentoUniExpSolDTO->retNumIdUnidadeExp();
            $objMdCorMapeamentoUniExpSolDTO->retNumIdUnidadeSolicitante();            
            $arrMdCorMapeamentoUniExpSolDTO = $this->listar($objMdCorMapeamentoUniExpSolDTO);

            $arrUnidadesSolicitantes = InfraArray::converterArrInfraDTO($arrMdCorMapeamentoUniExpSolDTO,'IdUnidadeSolicitante');

            //checando se existe serviços
            if (count($arrUnidadesSolicitantes)){
                $incluir = false;
                $objMdCorMapUnidServicoDTO = new MdCorMapUnidServicoDTO();
                $objMdCorMapUnidServicoRN = new MdCorMapUnidServicoRN();
                $objMdCorMapUnidServicoDTO->retNumIdUnidadeSolicitante();
                $objMdCorMapUnidServicoDTO->retStrSiglaUnidade();                    
                $objMdCorMapUnidServicoDTO->setDistinct(true);
                $objMdCorMapUnidServicoDTO->setNumIdUnidadeSolicitante($arrUnidadesSolicitantes,InfraDTO::$OPER_IN);

                $arrObjMdCorMapUnidServicoDTO = $objMdCorMapUnidServicoRN->listar($objMdCorMapUnidServicoDTO);  
                if (count($arrObjMdCorMapUnidServicoDTO)>0){
                    $objInfraException = new InfraException();
                    $objInfraException->adicionarValidacao('Unidade(s) com serviço(s) vinculados:\n' . implode(',',InfraArray::converterArrInfraDTO($arrObjMdCorMapUnidServicoDTO,'SiglaUnidade')));
                    $objInfraException->lancarValidacoes();
                }else{
                    if ($operacao=='E'){
                        // exclusão
                        $this->excluir($arrMdCorMapeamentoUniExpSolDTO);
                    }else if ($operacao=='D'){
                        // desativação
                        $this->desativar($arrMdCorMapeamentoUniExpSolDTO);
                    }
                }
            }

            return true;

        } catch (Exception $e) {
            PaginaSEI::getInstance()->processarExcecao($e);
        }

    }

}