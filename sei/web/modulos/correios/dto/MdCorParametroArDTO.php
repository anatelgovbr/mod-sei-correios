<?
/**
* TRIBUNAL REGIONAL FEDERAL DA 4ª REGIÃO
*
* 19/06/2018 - criado por augusto.cast
*
* Versão do Gerador de Código: 1.41.0
*/

require_once dirname(__FILE__) . '/../../../SEI.php';

class MdCorParametroArDTO extends InfraDTO {

  public function getStrNomeTabela() {
  	 return 'md_cor_adm_parametro_ar';
  }

  public function montar() {

    $this->adicionarAtributoTabela(InfraDTO::$PREFIXO_NUM, 'IdMdCorParametroAr', 'id_md_cor_parametro_ar');

    $this->adicionarAtributoTabela(InfraDTO::$PREFIXO_STR, 'NuDiasRetornoAr', 'nu_dias_retorno_ar');

    $this->adicionarAtributoTabela(InfraDTO::$PREFIXO_STR, 'NuDiasCobrancaAr', 'nu_dias_cobranca_ar');

    $this->adicionarAtributoTabela(InfraDTO::$PREFIXO_NUM, 'IdSerie', 'id_serie');

    $this->adicionarAtributoTabela(InfraDTO::$PREFIXO_STR, 'NomeArvore', 'nome_arvore');

    $this->adicionarAtributoTabela(InfraDTO::$PREFIXO_NUM, 'IdTipoConferencia', 'id_tipo_conferencia');

    $this->adicionarAtributoTabela(InfraDTO::$PREFIXO_NUM, 'IdSerieDevolvido', 'id_serie_devolvido');

    $this->adicionarAtributoTabela(InfraDTO::$PREFIXO_STR, 'NomeArvoreDevolvido', 'nome_arvore_devolvido');

    $this->adicionarAtributoTabela(InfraDTO::$PREFIXO_NUM, 'IdTipoConferenciaDevolvido', 'id_tipo_conferencia_devolvido');

    $this->adicionarAtributoTabela(InfraDTO::$PREFIXO_NUM, 'IdSerieCobranca', 'id_serie_cobranca');

    $this->adicionarAtributoTabela(InfraDTO::$PREFIXO_NUM, 'IdProcedimentoCobranca', 'id_procedimento_cobranca');

    $this->adicionarAtributoTabela(InfraDTO::$PREFIXO_NUM, 'IdUnidadeCobranca', 'id_unidade_cobranca');

    $this->adicionarAtributoTabela(InfraDTO::$PREFIXO_STR, 'ModeloCobranca', 'modelo_cobranca');
    
    $this->adicionarAtributoTabela(InfraDTO::$PREFIXO_NUM, 'IdContato', 'id_contato');

    $this->adicionarAtributoTabela(InfraDTO::$PREFIXO_STR, 'NuDiasPrazoExpRetAr', 'dias_exp_ret_ar');

    $this->adicionarAtributoTabela(InfraDTO::$PREFIXO_STR, 'SinNivelAcessoDocPrincipalAr', 'sin_niv_ace_doc_princ_ar');

    $this->adicionarAtributoTabela(InfraDTO::$PREFIXO_STR, 'NivelAcessoAr', 'nivel_acesso_ar');

    $this->adicionarAtributoTabela(InfraDTO::$PREFIXO_NUM, 'IdHipoteseLegalAr', 'id_hipotese_legal_ar');

    $this->configurarPK('IdMdCorParametroAr', InfraDTO::$TIPO_PK_NATIVA);

    //Tipo de serie
    $this->configurarFK('IdSerie', 'serie sr', 'sr.id_serie', InfraDTO::$TIPO_FK_OPCIONAL);
    $this->adicionarAtributoTabelaRelacionada(InfraDTO::$PREFIXO_STR, 'NomeSerie', 'sr.nome', 'serie sr');
    
    //Contato
    $this->configurarFK('IdContato', 'contato cont', 'cont.id_contato', InfraDTO::$TIPO_FK_OPCIONAL);
    $this->adicionarAtributoTabelaRelacionada(InfraDTO::$PREFIXO_STR, 'NomeContato', 'cont.nome', 'contato cont');

    //Tipo de Serie Objeto Devolvido
    $this->configurarFK('IdSerieDevolvido', 'serie sro', 'sro.id_serie', InfraDTO::$TIPO_FK_OPCIONAL);
    $this->adicionarAtributoTabelaRelacionada(InfraDTO::$PREFIXO_STR, 'NomeSerieObjetoDevolvido', 'sro.nome', 'serie sro');

   //Tipo de Serie Objeto Devolvido
    $this->configurarFK('IdSerieCobranca', 'serie src', 'src.id_serie', InfraDTO::$TIPO_FK_OPCIONAL);
    $this->adicionarAtributoTabelaRelacionada(InfraDTO::$PREFIXO_STR, 'NomeSerieCobranca', 'src.nome', 'serie src');

    //Tipo de Conferencia
    $this->configurarFK('IdTipoConferencia', 'tipo_conferencia tc', 'tc.id_tipo_conferencia', InfraDTO::$TIPO_FK_OPCIONAL);
    $this->adicionarAtributoTabelaRelacionada(InfraDTO::$PREFIXO_STR, 'DescricaoConferencia', 'tc.descricao', 'tipo_conferencia tc');

    //Tipo de Conferencia Objeto Devolvido
    $this->configurarFK('IdTipoConferenciaDevolvido', 'tipo_conferencia tco', 'tco.id_tipo_conferencia', InfraDTO::$TIPO_FK_OPCIONAL);
    $this->adicionarAtributoTabelaRelacionada(InfraDTO::$PREFIXO_STR, 'DescricaoConferenciaObjetoDevolvido', 'tco.descricao', 'tipo_conferencia tco');

    //Procedimento Cobrança
    $this->configurarFK('IdProcedimentoCobranca', 'protocolo pc', 'pc.id_protocolo', InfraDTO::$TIPO_FK_OPCIONAL);
    $this->adicionarAtributoTabelaRelacionada(InfraDTO::$PREFIXO_STR, 'ProtocoloFormatadoCobranca', 'pc.protocolo_formatado', 'protocolo pc');



  }
}
