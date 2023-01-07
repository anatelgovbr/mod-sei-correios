<?php

//logica para validar configuracoes do destinatario do doc principal da expedi�ao
//obtendo informa��es sobre o doc principal e seu destinatario
$objProtocoloDocPrincipalRN = new ProtocoloRN();
$objProtocoloDocPrincipalDTO = new ProtocoloDTO();
$objProtocoloDocPrincipalDTO->retTodos();
$objProtocoloDocPrincipalDTO->retNumIdSerieDocumento();
$objProtocoloDocPrincipalDTO->retStrNomeSerieDocumento();
$objProtocoloDocPrincipalDTO->retStrNumeroDocumento();
$objProtocoloDocPrincipalDTO->retStrStaDocumentoDocumento();

$objProtocoloDocPrincipalDTO->setDblIdProtocolo($id_doc);

$objProtocoloDocPrincipalDTO = $objProtocoloDocPrincipalRN->consultarRN0186($objProtocoloDocPrincipalDTO);

$infraParametrosRN = new InfraParametroRN();
$objInfraParametrosDTO = new InfraParametroDTO();
$objInfraParametrosDTO->retStrValor();
$objInfraParametrosDTO->setStrNome('MODULO_CORREIOS_ID_DOCUMENTO_EXPEDICAO');
$objInfraParametrosDTO = $infraParametrosRN->consultar($objInfraParametrosDTO);

$arrIdSerieDocumento = array();
if ($objInfraParametrosDTO) {
    $arrIdSerieDocumento = explode(',', $objInfraParametrosDTO->getStrValor());
}

$bolExpedicaoExterno = false;
if (in_array($objProtocoloDocPrincipalDTO->getNumIdSerieDocumento(), $arrIdSerieDocumento) && $objProtocoloDocPrincipalDTO->getStrStaDocumentoDocumento() == 'X') {
    $bolExpedicaoExterno = true;
}

$nomeTipoDocumento = $objProtocoloDocPrincipalDTO->getStrNomeSerieDocumento();
$numeroProtocoloFormatado = $objProtocoloDocPrincipalDTO->getStrProtocoloFormatado();
$numeroDoc = $objProtocoloDocPrincipalDTO->getStrNumeroDocumento();
$descricao_documento_principal = $nomeTipoDocumento . " " . $numeroDoc . " (" . $numeroProtocoloFormatado . ")";

//consultando o destinatario - s� pode ter um
$objParticipanteRN = new ParticipanteRN();
$objParticipanteDTOConsulta = new ParticipanteDTO();
$objParticipanteDTOConsulta->setNumMaxRegistrosRetorno(1);
$objParticipanteDTOConsulta->setDblIdProtocolo($id_doc);
if ($bolExpedicaoExterno) {
    $objParticipanteDTOConsulta->setStrStaParticipacao(ParticipanteRN::$TP_INTERESSADO);
} else {
    $objParticipanteDTOConsulta->setStrStaParticipacao(ParticipanteRN::$TP_DESTINATARIO);
}
$objParticipanteDTOConsulta->retTodos();
$objParticipanteDTOConsulta->retStrNomeContato();
$objParticipanteDTO = $objParticipanteRN->consultarRN1008($objParticipanteDTOConsulta);

if (($objParticipanteDTO == null || !is_object($objParticipanteDTO))) {
    $str_msg_validacao_destinatario_doc = 'N�o consta a indica��o de Destinat�rio no cadastro deste documento. Antes � necess�rio indicar o Destinat�rio';
    exibirAlerta($str_msg_validacao_destinatario_doc);
}

//checando se h� mais de um destinatario
$objParticipanteDTOConsulta = new ParticipanteDTO();
$objParticipanteDTOConsulta->setDblIdProtocolo($id_doc);
if ($bolExpedicaoExterno) {
    $objParticipanteDTOConsulta->setStrStaParticipacao(ParticipanteRN::$TP_INTERESSADO);
} else {
    $objParticipanteDTOConsulta->setStrStaParticipacao(ParticipanteRN::$TP_DESTINATARIO);
}
$objParticipanteDTOConsulta->retTodos();
$objParticipanteDTOConsulta->retStrNomeContato();
$objParticipanteDTOConsulta->retNumIdContato();
$arrObjParticipanteDTO = $objParticipanteRN->listarRN0189($objParticipanteDTOConsulta);

if ($arrObjParticipanteDTO != null && is_array($arrObjParticipanteDTO) && count($arrObjParticipanteDTO) > 1) {
    $str_msg_validacao_destinatario = 'N�o � permitida a indica��o de mais de um Destinat�rio para o Documento a ser Expedido pelos Correios. Antes � necess�rio revisar a indica��o do Destinat�rio';
    exibirAlerta($str_msg_validacao_destinatario);
}
$mensagemErro = MdCorExpedicaoSolicitadaINT::validaContatoPreeenchido($objParticipanteDTO->getNumIdContato(), true, "'");
if($mensagemErro != '') {
    exibirAlerta($mensagemErro);
}

function exibirAlerta($msgErro)
{
    echo "<script> alert('" . $msgErro . "'); window.history.back(); </script>";
    die;
}