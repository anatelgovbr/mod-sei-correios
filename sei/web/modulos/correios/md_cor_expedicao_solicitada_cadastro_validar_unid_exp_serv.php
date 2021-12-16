<?php

//Unidade Solicitante
$objMdCorMapeamentoUniExpSolDTO = new MdCorMapeamentoUniExpSolDTO();
$objMdCorMapeamentoUniExpSolDTO->retStrSiglaUnidadeExpedidora();
$objMdCorMapeamentoUniExpSolDTO->retStrDescricaoUnidadeExpedidora();
$objMdCorMapeamentoUniExpSolDTO->setDistinct(true);
$objMdCorMapeamentoUniExpSolDTO->setStrSinAtivo('S');
$objMdCorMapeamentoUniExpSolDTO->setNumIdUnidadeSolicitante(SessaoSei::getInstance()->getNumIdUnidadeAtual());

$objMdCorMapeamentoUniExpSolRN = new MdCorMapeamentoUniExpSolRN();
$arrObjMdCorMapeamentoUniExpSolDTO = $objMdCorMapeamentoUniExpSolRN->listar($objMdCorMapeamentoUniExpSolDTO);		        

if (count($arrObjMdCorMapeamentoUniExpSolDTO)>0){
	$unidade_exp = $arrObjMdCorMapeamentoUniExpSolDTO[0]->getStrDescricaoUnidadeExpedidora() . " (" . $arrObjMdCorMapeamentoUniExpSolDTO[0]->getStrSiglaUnidadeExpedidora() . ")";
}else{
	echo "<script>";
	echo "alert('Sua unidade \"" . SessaoSei::getInstance()->getStrSiglaUnidadeAtual().' - '.SessaoSei::getInstance()->getStrDescricaoUnidadeAtual() . "\" não é uma Unidade Solicitante Ativa, portanto não é possível solicitar expedições..');";
	echo "window.history.back();";
	echo "</script>";
	die;	        		
}


//Unidade Solicitante
$objMdCorMapeamentoUniExpSolDTO = new MdCorMapeamentoUniExpSolDTO();
$objMdCorMapeamentoUniExpSolDTO->retStrSiglaUnidadeExpedidora();
$objMdCorMapeamentoUniExpSolDTO->retStrDescricaoUnidadeExpedidora();
$objMdCorMapeamentoUniExpSolDTO->setDistinct(true);
$objMdCorMapeamentoUniExpSolDTO->setStrSinAtivo('S');
$objMdCorMapeamentoUniExpSolDTO->setNumIdUnidadeExp(SessaoSei::getInstance()->getNumIdUnidadeAtual());

$objMdCorMapeamentoUniExpSolRN = new MdCorMapeamentoUniExpSolRN();
$arrObjMdCorMapeamentoUniExpSolDTO = $objMdCorMapeamentoUniExpSolRN->listar($objMdCorMapeamentoUniExpSolDTO);		        

if (count($arrObjMdCorMapeamentoUniExpSolDTO)>0){
	echo "<script>";
	echo "alert('Sua unidade \"" . SessaoSei::getInstance()->getStrSiglaUnidadeAtual().' - '.SessaoSei::getInstance()->getStrDescricaoUnidadeAtual() . "\" é uma Unidade Expedidora Ativa, não é possível solicitar expedições.');";
	echo "window.history.back();";
	echo "</script>";
	die;
}

//Unidade Solicitante X Serviços
$objMdCorMapUnidServicoDTO = new MdCorMapUnidServicoDTO();
$objMdCorMapUnidServicoDTO->retNumIdMdCorServicoPostal();
$objMdCorMapUnidServicoDTO->setDistinct(true);
$objMdCorMapUnidServicoDTO->setStrSinAtivo('S');
$objMdCorMapUnidServicoDTO->setNumIdUnidadeSolicitante(SessaoSei::getInstance()->getNumIdUnidadeAtual());


$objMdCorMapUnidServicoRN = new MdCorMapUnidServicoRN();
$arrObjMdCorMapUnidServicoDTO = $objMdCorMapUnidServicoRN->listar($objMdCorMapUnidServicoDTO);

//$unidade_exp = SessaoSei::getInstance()->getStrSiglaUnidadeAtual();
if (count($arrObjMdCorMapUnidServicoDTO)>0){
	if (count($arrObjMdCorMapUnidServicoDTO)==1){
		$id_num_servico_postal = $arrObjMdCorMapUnidServicoDTO[0]->getNumIdMdCorServicoPostal();
	}
}else{
	
//	echo "<script>";
//	echo "alert('A Unidade Solicitante \"" . SessaoSei::getInstance()->getStrSiglaUnidadeAtual().' - '.SessaoSei::getInstance()->getStrDescricaoUnidadeAtual() . "\" deve possuir vinculos ativo com pelo menos 1(um) serviço.');";
//	echo "window.history.back();";
//	echo "</script>";
//	die;	        		
}
?>