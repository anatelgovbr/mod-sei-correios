<?
/**
* TRIBUNAL REGIONAL FEDERAL DA 4ª REGIÃO
*
* 21/08/2018 - criado por augusto.cast
*
* Versão do Gerador de Código: 1.41.0
*/

require_once dirname(__FILE__) . '/../../../SEI.php';

class MdCorArCobrancaDTO extends InfraDTO {

  public function getStrNomeTabela() {
  	 return 'md_cor_ar_cobranca';
  }

  public function montar() {

    $this->adicionarAtributoTabela(InfraDTO::$PREFIXO_NUM, 'IdMdCorArCobranca', 'id_md_cor_ar_cobranca');

    $this->adicionarAtributoTabela(InfraDTO::$PREFIXO_DTH, 'DtMdCorArCobranca', 'dt_md_cor_ar_cobranca');

    $this->adicionarAtributoTabela(InfraDTO::$PREFIXO_DBL, 'IdDocumentoCobranca', 'id_documento_cobranca');

    $this->adicionarAtributoTabela(InfraDTO::$PREFIXO_NUM, 'IdMdCorExpedicaoSolicitada', 'id_md_cor_expedicao_solicitada');

    $this->configurarPK('IdMdCorArCobranca', InfraDTO::$TIPO_PK_NATIVA);

    //Documento de Cobrança
    $this->configurarFK('IdMdCorExpedicaoSolicitada', 'md_cor_ar_cobranca mcacob', 'mcacob.id_md_cor_expedicao_solicitada');
    $this->configurarFK('IdDocumentoCobranca', 'protocolo prodcob', 'prodcob.id_protocolo');
    $this->configurarFK('IdDocumentoCobranca', 'documento doccob', 'doccob.id_documento');
    $this->configurarFK('IdSerieCobranca', 'serie scob', 'scob.id_serie');
    $this->adicionarAtributoTabelaRelacionada(InfraDTO::$PREFIXO_NUM, 'IdDocumentoCobranca', 'mcacob.id_documento_cobranca', 'md_cor_ar_cobranca mcacob');
    $this->adicionarAtributoTabelaRelacionada(InfraDTO::$PREFIXO_STR, 'ProtocoloFormatadoCobranca', 'prodcob.protocolo_formatado', 'protocolo prodcob');
    $this->adicionarAtributoTabelaRelacionada(InfraDTO::$PREFIXO_STR, 'NumeroDocumentoCobranca', 'doccob.numero', 'documento doccob');
    $this->adicionarAtributoTabelaRelacionada(InfraDTO::$PREFIXO_NUM, 'IdSerieCobranca', 'doccob.id_serie', 'documento doccob');
    $this->adicionarAtributoTabelaRelacionada(InfraDTO::$PREFIXO_STR, 'NomeSerieCobranca', 'scob.nome', 'serie scob');
  }
}
