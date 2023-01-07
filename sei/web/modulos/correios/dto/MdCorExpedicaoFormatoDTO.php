<?
/**
* TRIBUNAL REGIONAL FEDERAL DA 4Є REGIГO
*
* 07/06/2017 - criado por marcelo.cast
*
* Versгo do Gerador de Cуdigo: 1.40.1
*/

require_once dirname(__FILE__) . '/../../../SEI.php';

class MdCorExpedicaoFormatoDTO extends InfraDTO {

  public function getStrNomeTabela() {
  	 return 'md_cor_expedicao_formato';
  }

  public function montar() {

    $this->adicionarAtributoTabela(InfraDTO::$PREFIXO_NUM, 'IdMdCorExpedicaoFormato', 'id_md_cor_expedicao_formato');
    $this->adicionarAtributoTabela(InfraDTO::$PREFIXO_DBL, 'IdProtocolo', 'id_protocolo');
    $this->adicionarAtributoTabela(InfraDTO::$PREFIXO_NUM, 'IdMdCorExpedicaoSolicitada', 'id_md_cor_expedicao_solicitada');
    $this->adicionarAtributoTabela(InfraDTO::$PREFIXO_STR, 'FormaExpedicao', 'sin_forma_expedicao');
    $this->adicionarAtributoTabela(InfraDTO::$PREFIXO_STR, 'Impressao', 'sin_impressao');
    $this->adicionarAtributoTabela(InfraDTO::$PREFIXO_STR, 'Justificativa', 'justificativa');

    $this->configurarPK('IdMdCorExpedicaoFormato',InfraDTO::$TIPO_PK_NATIVA );    

    //FKs e atributos relacionados - Protocolo
    $this->configurarFK('IdProtocolo', 'protocolo prot', 'prot.id_protocolo');
    $this->adicionarAtributoTabelaRelacionada(InfraDTO::$PREFIXO_STR, 'ProtocoloFormatado', 'prot.protocolo_formatado', 'protocolo prot');

    //FKs e atributos relacionado - Documento
    $this->adicionarAtributoTabelaRelacionada(InfraDTO::$PREFIXO_DBL, 'IdDocumento', 'prot.id_protocolo', 'protocolo prot');
    $this->configurarFK('IdDocumento', 'documento doc', 'doc.id_documento', InfraDTO::$TIPO_FK_OPCIONAL);
    $this->adicionarAtributoTabelaRelacionada(InfraDTO::$PREFIXO_STR, 'NumeroDocumento', 'doc.numero', 'documento doc');
    $this->adicionarAtributoTabelaRelacionada(InfraDTO::$PREFIXO_DBL, 'IdSerie', 'doc.id_serie', 'documento doc');
    $this->configurarFK('IdSerie', 'serie ser', 'ser.id_serie', InfraDTO::$TIPO_FK_OPCIONAL);
    $this->adicionarAtributoTabelaRelacionada(InfraDTO::$PREFIXO_STR, 'NomeSerie', 'ser.nome', 'serie ser');

      //FKs e atributos relacionado - Procedimento
      $this->adicionarAtributoTabelaRelacionada(InfraDTO::$PREFIXO_DBL, 'IdProcedimento', 'prot.id_protocolo', 'protocolo prot');
      $this->configurarFK('IdProcedimento', 'procedimento proc', 'proc.id_procedimento', InfraDTO::$TIPO_FK_OPCIONAL);
      $this->adicionarAtributoTabelaRelacionada(InfraDTO::$PREFIXO_DBL, 'IdTipoProcedimento', 'proc.id_tipo_procedimento', 'procedimento proc');
      $this->configurarFK('IdTipoProcedimento', 'tipo_procedimento tip', 'tip.id_tipo_procedimento', InfraDTO::$TIPO_FK_OPCIONAL);
      $this->adicionarAtributoTabelaRelacionada(InfraDTO::$PREFIXO_STR, 'NomeProcedimento', 'tip.nome', 'tipo_procedimento tip');


      //fks e atributos relacionados - Expedicao Solicitada
    $this->configurarFK('IdMdCorExpedicaoSolicitada', 'md_cor_expedicao_solicitad mdexpsolic', 'mdexpsolic.id_md_cor_expedicao_solicitada');
    $this->adicionarAtributoTabelaRelacionada(InfraDTO::$PREFIXO_DBL, 'IdDocumentoPrincipal', 'mdexpsolic.id_documento_principal', 'md_cor_expedicao_solicitad mdexpsolic');

  }

  public function getTextoImpressao(){
  	
  	if( $this->getStrImpressao() == MdCorExpedicaoFormatoRN::$TP_IMPRESSAO_COLORIDO ){
  	 	return "Colorido";
  	}

  	else if( $this->getStrImpressao() == MdCorExpedicaoFormatoRN::$TP_IMPRESSAO_PRETO_BRANCO ){
  		return "Preto e branco";
  	}

  	else if( $this->getStrImpressao() == MdCorExpedicaoFormatoRN::$TP_IMPRESSAO_NENHUMA ){
  		return "";
  	}

  }
  
  public function getTextoFormato(){
  	if( $this->getStrFormaExpedicao() == MdCorExpedicaoFormatoRN::$TP_FORMATO_IMPRESSO ){
  		return "Impresso";
  	}

  	else if( $this->getStrFormaExpedicao() == MdCorExpedicaoFormatoRN::$TP_FORMATO_MIDIA ){
  		return "Gravaзгo em Mнdia";
  	}
  	
  }

    public function getStrTipoDocumento(){
        if($this->getDblIdProtocolo() == $this->getDblIdDocumentoPrincipal()){
            return 'Principal';
        }

        return 'Anexo';
    }
}
?>