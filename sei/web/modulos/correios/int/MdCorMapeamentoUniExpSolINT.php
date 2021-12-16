<?php
/**
 * ANATEL
 *
 * 22/12/2016 - criado por marcelo.emiliano@cast.com.br - CAST
 */

require_once dirname(__FILE__) . '/../../../SEI.php';

class MdCorMapeamentoUniExpSolINT extends InfraINT
{
    public static function autoCompletarUnidades($strPalavrasPesquisa, $bolTodas, $numIdOrgao = '', $intIdUnidadeExpExcecao=null){ 
        $objUnidadeDTO = new UnidadeDTO();
        $objUnidadeDTO->retNumIdUnidade();
        $objUnidadeDTO->retStrSigla();
        $objUnidadeDTO->retStrDescricao();
        $objUnidadeDTO->setNumMaxRegistrosRetorno(50);
        $objUnidadeDTO->setOrdStrSigla(InfraDTO::$TIPO_ORDENACAO_ASC);

        $arrUnidadeExpedidora = array_keys(InfraArray::indexarArrInfraDTO(MdCorUnidadeExpINT::buscarUnidadesExpedidoras(), 'IdUnidade'));
        $arrUnidadeMapeadas = array_keys(InfraArray::indexarArrInfraDTO(MdCorMapeamentoUniExpSolINT::buscarUnidadesMapeadas($intIdUnidadeExpExcecao), 'IdUnidadeSolicitante'));

        $arrUnidadeNotIn = array_merge($arrUnidadeExpedidora, $arrUnidadeMapeadas);
        if (count($arrUnidadeNotIn)>0){
        	$objUnidadeDTO->setNumIdUnidade($arrUnidadeNotIn, InfraDTO::$OPER_NOT_IN);
        }

        if ($strPalavrasPesquisa!=''){
            $objUnidadeDTO->setStrPalavrasPesquisa($strPalavrasPesquisa);
        }

        if ($numIdOrgao!= ""){
            $objUnidadeDTO->setNumIdOrgao(explode(',',$numIdOrgao),InfraDTO::$OPER_IN);
        }

        $objUnidadeRN = new UnidadeRN();
        if ($bolTodas){
            $arrObjUnidadeDTO = $objUnidadeRN->listarTodasComFiltro($objUnidadeDTO);
        }else{
            $arrObjUnidadeDTO = $objUnidadeRN->listarOutrasComFiltro($objUnidadeDTO);
        }

        foreach($arrObjUnidadeDTO as $objUnidadeDTO){
            $objUnidadeDTO->setStrSigla(UnidadeINT::formatarSiglaDescricao($objUnidadeDTO->getStrSigla(),$objUnidadeDTO->getStrDescricao()));
        }

        return $arrObjUnidadeDTO;
    }

    public static function buscarUnidadesMapeadas($intIdUnidadeExpExcecao=null)
    {
        $objMdCorMapeamentoUniExpSolRN = new MdCorMapeamentoUniExpSolRN();
        $objMdCorMapeamentoUniExpSolDTO = new MdCorMapeamentoUniExpSolDTO();
        $objMdCorMapeamentoUniExpSolDTO->retNumIdUnidadeSolicitante();
        $objMdCorMapeamentoUniExpSolDTO->setStrSinAtivo('S');

        if (!is_null($intIdUnidadeExpExcecao)){
            $objMdCorMapeamentoUniExpSolDTO->setNumIdUnidadeExp(is_array($intIdUnidadeExpExcecao)?$intIdUnidadeExpExcecao:array($intIdUnidadeExpExcecao), InfraDTO::$OPER_NOT_IN);
        }

        $objRetorno = $objMdCorMapeamentoUniExpSolRN->listar($objMdCorMapeamentoUniExpSolDTO);

        return $objRetorno;
    }

    public static function montarSelectUnidadesMapeadas($intIdUnidadeExp = null, $valorSelecionado = null, $componente = true)
    {
        $objMdCorMapeamentoUniExpSolRN = new MdCorMapeamentoUniExpSolRN();
        $objMdCorMapeamentoUniExpSolDTO = new MdCorMapeamentoUniExpSolDTO();

        $objMdCorMapeamentoUniExpSolDTO->retNumIdUnidadeExp();
        $objMdCorMapeamentoUniExpSolDTO->retNumIdUnidadeSolicitante();
        $objMdCorMapeamentoUniExpSolDTO->retStrSiglaUnidadeExpedidora();
        $objMdCorMapeamentoUniExpSolDTO->retStrDescricaoUnidadeExpedidora();
        $objMdCorMapeamentoUniExpSolDTO->retStrSiglaUnidadeSolicitante();
        $objMdCorMapeamentoUniExpSolDTO->retStrDescricaoUnidadeSolicitante();
        if (!is_null($intIdUnidadeExp)) {
            $objMdCorMapeamentoUniExpSolDTO->setNumIdUnidadeExp($intIdUnidadeExp);
        }

        $objRetorno = $objMdCorMapeamentoUniExpSolRN->listar($objMdCorMapeamentoUniExpSolDTO);

        foreach ($objRetorno as $item) {
            $strTextoUnidadeSolicitante = $item->getStrSiglaUnidadeSolicitante() . ' - ' . $item->getStrDescricaoUnidadeSolicitante();
            $item->setStrSiglaUnidadeSolicitante($strTextoUnidadeSolicitante);
        }

        if(!$componente){
            $objDTO = new MdCorMapeamentoUniExpSolDTO();
            $objDTO->setStrSiglaUnidadeSolicitante('Múltiplos');
            $objDTO->setNumIdUnidadeSolicitante('M');

            array_unshift($objRetorno, $objDTO);

            $objDTO = new MdCorMapeamentoUniExpSolDTO();
            $objDTO->setStrSiglaUnidadeSolicitante('');
            $objDTO->setNumIdUnidadeSolicitante('');

            array_unshift($objRetorno, $objDTO);
        }

        return parent::montarSelectArrInfraDTO(null, null, $valorSelecionado, $objRetorno, 'IdUnidadeSolicitante', 'SiglaUnidadeSolicitante');
    }

    public static function formatarListaUnidadesMapeadas($arrObjsDto, $filtroMultiplos = false, $idSolicitante = false){

        $arrMain        = array();
        $arrQtdUnd      = array();
        $arrControle = array();

        foreach($arrObjsDto as $key => $objDto){
            $existePos = isset($arrQtdUnd[$objDto->getNumIdUnidadeExp()]) ? true : false;

            if($existePos){
                $arrQtdUnd[$objDto->getNumIdUnidadeExp()] = $arrQtdUnd[$objDto->getNumIdUnidadeExp()] + 1;
            }else{
                $arrQtdUnd[$objDto->getNumIdUnidadeExp()] = 1;
            }

        }


        foreach($arrObjsDto as $key => $objDto){
            $qtdUnidades   = 0;
            $qtdUnidades = $arrQtdUnd[$objDto->getNumIdUnidadeExp()];

            if($qtdUnidades > 1){
                $objDto->setStrSiglaUnidadeSolicitante('Múltiplos');
                $objDto->setBolMultiplos(true);
            }else{
                $nomeUnidade = $objDto->getStrSiglaUnidadeSolicitante(). ' - '.$objDto->getStrDescricaoUnidadeSolicitante();
                $objDto->setStrSiglaUnidadeSolicitante($nomeUnidade);
                $objDto->setBolMultiplos(false);
            }

            $existePosCtrl = isset($arrControle[$objDto->getNumIdUnidadeExp()]) ? true : false;


            if(!$existePosCtrl){

                if($filtroMultiplos && $objDto->getBolMultiplos() || (!$filtroMultiplos && !$idSolicitante) || (!$filtroMultiplos && $objDto->getNumIdUnidadeSolicitante() == $idSolicitante)){
                    array_push($arrMain, $objDto);
                }
            }

            $arrControle[$objDto->getNumIdUnidadeExp()] = true;
        }



            return $arrMain;
        }

    public static function autoCompletarUnidadesMapeadas($strPalavrasPesquisa)
    {
        $objMdCorMapeamentoUniExpSolRN = new MdCorMapeamentoUniExpSolRN();
        $objMdCorMapeamentoUniExpSolDTO = new MdCorMapeamentoUniExpSolDTO();
        $objMdCorMapeamentoUniExpSolDTO->retNumIdUnidadeSolicitante();
        $objMdCorMapeamentoUniExpSolDTO->retStrDescricaoUnidadeSolicitante();

        $objMdCorMapeamentoUniExpSolDTO->setBolExclusaoLogica(false);
        $objMdCorMapeamentoUniExpSolDTO->adicionarCriterio(array('SinAtivo'), array(InfraDTO::$OPER_IGUAL), array('S'));

        if ($strPalavrasPesquisa!=''){
            $objMdCorMapeamentoUniExpSolDTO->setStrPalavrasPesquisa($strPalavrasPesquisa);
        }

        $objMdCorMapeamentoUniExpSolDTO->setNumMaxRegistrosRetorno(50);
        $objRetorno = $objMdCorMapeamentoUniExpSolRN->listarComFiltro($objMdCorMapeamentoUniExpSolDTO);

//        foreach($arrObjUnidadeDTO as $objUnidadeDTO){
//            $objMdCorMapeamentoUniExpSolDTO->setStrSigla(UnidadeINT::formatarSiglaDescricao($objMdCorMapeamentoUniExpSolDTO->getStrSigla(),$objMdCorMapeamentoUniExpSolDTO->getStrDescricao()));
//        }

        return $objRetorno;
    }

  
    public static function montarSelectUnidadesSolicitantes($strPrimeiroItemValor, $strPrimeiroItemDescricao, $strValorItemSelecionado) {
        $objMdCorMapeamentoUniExpSolRN = new MdCorMapeamentoUniExpSolRN();
        $objMdCorMapeamentoUniExpSolDTO = new MdCorMapeamentoUniExpSolDTO();
        $objMdCorMapeamentoUniExpSolDTO->retNumIdUnidadeSolicitante();
        $objMdCorMapeamentoUniExpSolDTO->retStrSiglaUnidadeSolicitante();
        $objMdCorMapeamentoUniExpSolDTO->retStrDescricaoUnidadeSolicitante();

        $objMdCorMapeamentoUniExpSolDTO->setBolExclusaoLogica(false);
        $objMdCorMapeamentoUniExpSolDTO->adicionarCriterio(array('SinAtivo'), array(InfraDTO::$OPER_IGUAL), array('S'));

        $objRetorno = $objMdCorMapeamentoUniExpSolRN->listar($objMdCorMapeamentoUniExpSolDTO);

        foreach ($objRetorno as $item) {
            $item->setStrSiglaUnidadeSolicitante($item->getStrSiglaUnidadeSolicitante() . ' - ' . $item->getStrDescricaoUnidadeSolicitante());
        }

        return parent::montarSelectArrInfraDTO($strPrimeiroItemValor, $strPrimeiroItemDescricao, $strValorItemSelecionado, $objRetorno, 'IdUnidadeSolicitante', 'SiglaUnidadeSolicitante');
    }
    
    public static function retornaLinkAtualizado() {
        if (isset($_POST['idUnidade'])){
            $strUrlUnidade = SessaoSEI::getInstance()->assinarLink('controlador.php?acao=md_cor_unidade_selecionar_todas&tipo_selecao=2&id_object=objLupaUnidade&intIdUnidadeExpExcecao='.$_POST['idUnidades'].'&idUnidadeExpedidora='.$_POST['idUnidadeExpedidora']);
            $strLinkAjaxAutocompletarUnidade = SessaoSEI::getInstance()->assinarLink('controlador_ajax.php?acao_ajax=md_cor_unidade_mapeadas_auto_completar&intIdUnidadeExpExcecao='.$_POST['idUnidades'].'&idUnidadeExpedidora='.$_POST['idUnidadeExpedidora']);
        }else{
            $strUrlUnidade = SessaoSEI::getInstance()->assinarLink('controlador.php?acao=md_cor_unidade_selecionar_todas&tipo_selecao=2&id_object=objLupaUnidade&idUnidadeExpedidora='.$_POST['idUnidadeExpedidora']);
            $strLinkAjaxAutocompletarUnidade = SessaoSEI::getInstance()->assinarLink('controlador_ajax.php?acao_ajax=md_cor_unidade_mapeadas_auto_completar&idUnidadeExpedidora='.$_POST['idUnidadeExpedidora']);
        }
        
        $xml = "<item><url-unidade>".htmlspecialchars($strUrlUnidade)."</url-unidade><link-ajax>".htmlspecialchars($strLinkAjaxAutocompletarUnidade)."</link-ajax></item>";
        
        return $xml;
    }
}