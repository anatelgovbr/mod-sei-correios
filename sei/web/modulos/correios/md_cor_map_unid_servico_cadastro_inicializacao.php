<?
  $strLinkUnidadesSelecao = SessaoSEI::getInstance()->assinarLink('controlador.php?acao=md_cor_unidade_selecionar_todas&tipo_selecao=2&id_object=objLupaUnidades&UnidadeSolicitanteSomente=*');

  $strLinkAjaxUnidade = SessaoSEI::getInstance()->assinarLink('controlador_ajax.php?acao_ajax=md_cor_unidades_solicitantes_auto_completar');

  $strLinkAjaxDependConduta = SessaoSEI::getInstance()->assinarLink('controlador_ajax.php?acao_ajax=md_cor_map_unid_servico_montar_select');
  $strLinkDispNormatDNSelecao     = SessaoSEI::getInstance()->assinarLink('controlador.php?acao=dispositivo_normativo_litigioso_selecionar&tipo_selecao=1&filtro='.$idTpControle.'&id_object=objLupaIDNDispositivoNormativo');
  $strLinkAjaxDispNormatDNAjax    =  SessaoSEI::getInstance()->assinarLink('controlador_ajax.php?acao_ajax=dispositivo_auto_completar&filtro='.$idTpControle);

  //Valores para Combo Contrato
  $strItensSelContrato  = MdCorContratoINT::montarSelectId_NumeroContrato_MdCorContrato(null, null, null, null, $idTpControle);
?>