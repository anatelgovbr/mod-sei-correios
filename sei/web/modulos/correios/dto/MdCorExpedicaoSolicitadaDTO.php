<?
  /**
   * TRIBUNAL REGIONAL FEDERAL DA 4ª REGIÃO
   *
   * 07/06/2017 - criado por marcelo.cast
   *
   * Versão do Gerador de Código: 1.40.1
   */

  require_once dirname(__FILE__) . '/../../../SEI.php';

  class MdCorExpedicaoSolicitadaDTO extends InfraDTO
  {

    public function getStrNomeTabela()
    {
      return 'md_cor_expedicao_solicitad';
    }

    public function montar()
    {

      $this->adicionarAtributoTabela(InfraDTO::$PREFIXO_NUM, 'IdMdCorExpedicaoSolicitada', 'id_md_cor_expedicao_solicitada');
      $this->adicionarAtributoTabela(InfraDTO::$PREFIXO_STR, 'SinNecessitaAr', 'sin_necessita_ar');
      $this->adicionarAtributoTabela(InfraDTO::$PREFIXO_DBL, 'IdDocumentoPrincipal', 'id_documento_principal');
      $this->adicionarAtributoTabela(InfraDTO::$PREFIXO_NUM, 'IdContatoDestinatario', 'id_contato_destinatario');
      $this->adicionarAtributoTabela(InfraDTO::$PREFIXO_NUM, 'IdMdCorServicoPostal', 'id_md_cor_servico_postal');
      $this->adicionarAtributoTabela(InfraDTO::$PREFIXO_NUM, 'IdMdCorObjeto', 'id_md_cor_objeto');
      $this->adicionarAtributoTabela(InfraDTO::$PREFIXO_NUM, 'IdUnidade', 'id_unidade');
      $this->adicionarAtributoTabela(InfraDTO::$PREFIXO_DTH, 'DataSolicitacao', 'data_solicitacao');
      $this->adicionarAtributoTabela(InfraDTO::$PREFIXO_DTH, 'DataExpedicao', 'data_expedicao');
      $this->adicionarAtributoTabela(InfraDTO::$PREFIXO_NUM, 'IdUsuarioSolicitante', 'id_usuario_solicitante');
      $this->adicionarAtributoTabela(InfraDTO::$PREFIXO_STR, 'SinRecebido', 'sin_recebido');
      $this->adicionarAtributoTabela(InfraDTO::$PREFIXO_STR, 'SinObjetoAcessado', 'sin_objeto_acessado');
      $this->adicionarAtributoTabela(InfraDTO::$PREFIXO_NUM, 'IdMdCorPlp', 'id_md_cor_plp');
      $this->adicionarAtributoTabela(InfraDTO::$PREFIXO_NUM, 'IdUsuarioExpAutorizador', 'id_usuario_exp_autorizador');
      $this->adicionarAtributoTabela(InfraDTO::$PREFIXO_STR, 'CodigoRastreamento', 'codigo_rastreamento');
      $this->adicionarAtributoTabela(InfraDTO::$PREFIXO_STR, 'StatusCobranca', 'status_cobranca');
      $this->adicionarAtributoTabela(InfraDTO::$PREFIXO_STR, 'SinDevolvido', 'sin_devolvido');
      $this->adicionarAtributoTabela(InfraDTO::$PREFIXO_STR, 'JustificativaDevolucao', 'justificativa_devolucao');
	  $this->adicionarAtributoTabela(InfraDTO::$PREFIXO_STR, 'IdPrePostagem', 'id_pre_postagem');

      //Fk's Atributo Tabela
      $this->configurarPK('IdMdCorExpedicaoSolicitada', InfraDTO::$TIPO_PK_NATIVA);
      $this->configurarFK('IdDocumentoPrincipal', 'documento doc', 'doc.id_documento');
      $this->configurarFK('IdContatoDestinatario', 'md_cor_contato cont', 'cont.id_contato and cont.id_md_cor_expedicao_solicitada = md_cor_expedicao_solicitad.id_md_cor_expedicao_solicitada');
      $this->configurarFK('IdUsuarioSolicitante', 'usuario usu1', 'usu1.id_usuario');
      $this->configurarFK('IdUsuarioExpAutorizador', 'usuario usu2', 'usu2.id_usuario');
      $this->configurarFK('IdUnidade', 'unidade und', 'und.id_unidade');
      $this->configurarFK('IdMdCorServicoPostal', 'md_cor_servico_postal mdsp', 'mdsp.id_md_cor_servico_postal');
      $this->configurarFK('IdMdCorContrato', 'md_cor_contrato contr', 'contr.id_md_cor_contrato');
      $this->configurarFK('IdMdCorExpedicaoSolicitada', 'md_cor_ar_cobranca mcacb', 'mcacb.id_md_cor_expedicao_solicitada');

      //FK Contato do contrato para contato Orgão
      $this->configurarFK('IdContatoOrgao', 'contato contorg', 'contorg.id_contato');
      $this->configurarFK('IdCidadeContratoOrgao', 'cidade cidorg', 'cidorg.id_cidade');
      $this->configurarFK('IdUfContratoOrgao', 'uf uforg', 'uforg.id_uf');

      //Fk's EXpedirPLP
      $this->configurarFK('IdMdCorPlp', 'md_cor_plp plp', 'plp.id_md_cor_plp', INFRADTO::$TIPO_FK_OPCIONAL);

      //Fk's Atributo Relacionado
      $this->configurarFK('IdSerie', 'serie s', 's.id_serie');
      $this->configurarFK('IdProtocolo', 'protocolo pro', 'pro.id_protocolo');
      $this->configurarFK('IdProtocoloDocumento', 'protocolo prod', 'prod.id_protocolo');

      //Fk's Usuario Solicitante
      $this->configurarFK('IdUsuarioSolicitante', 'usuario usu', 'usu.id_usuario');
      $this->configurarFK('IdContatoSolicitante', 'contato contsoli', 'contsoli.id_contato');

      // Fk Unidade Expedidora
      $this->configurarFK('IdUnidade', 'md_cor_map_unidade_exp uniexpedidora', 'uniexpedidora.id_unidade_solicitante');
      $this->adicionarAtributoTabelaRelacionada(InfraDTO::$PREFIXO_DBL, 'IdUnidadeExpedidora', 'uniexpedidora.id_unidade_exp', 'md_cor_map_unidade_exp uniexpedidora');
      $this->configurarFK('IdUnidadeExpedidora', 'unidade uexpedidora', 'uexpedidora.id_unidade');
      $this->adicionarAtributoTabelaRelacionada(InfraDTO::$PREFIXO_DBL, 'IdOrgaoExpedidora', 'uexpedidora.id_orgao', 'unidade uexpedidora');

      //FK Contato do contato para Orgão
      $this->configurarFK('IdOrgaoExpedidora', 'orgao oexpedidora', 'oexpedidora.id_orgao');
      $this->adicionarAtributoTabelaRelacionada(InfraDTO::$PREFIXO_DBL, 'IdContatoOrgao', 'oexpedidora.id_contato', 'orgao oexpedidora');
      $this->adicionarAtributoTabelaRelacionada(InfraDTO::$PREFIXO_STR, 'TimbreOrgao', 'oexpedidora.timbre', 'orgao oexpedidora');
      $this->adicionarAtributoTabelaRelacionada(InfraDTO::$PREFIXO_STR, 'SiglaOrgao',  'oexpedidora.sigla', 'orgao oexpedidora');

      //Atributos Relacionados
      $this->adicionarAtributoTabelaRelacionada(InfraDTO::$PREFIXO_DBL, 'IdMdCorContrato', 'mdsp.id_md_cor_contrato', 'md_cor_servico_postal mdsp');
      $this->adicionarAtributoTabelaRelacionada(InfraDTO::$PREFIXO_NUM, 'IdSerie', 'doc.id_serie', 'documento doc');
      $this->adicionarAtributoTabelaRelacionada(InfraDTO::$PREFIXO_STR, 'SiglaUnidade', 'und.sigla', 'unidade und');
      $this->adicionarAtributoTabelaRelacionada(InfraDTO::$PREFIXO_STR, 'DescricaoUnidade', 'und.descricao', 'unidade und');
      $this->adicionarAtributoTabelaRelacionada(InfraDTO::$PREFIXO_STR, 'NomeSerie', 's.nome', 'serie s');
      $this->adicionarAtributoTabelaRelacionada(InfraDTO::$PREFIXO_STR, 'NomeServicoPostal', 'mdsp.nome', 'md_cor_servico_postal mdsp');
      $this->adicionarAtributoTabelaRelacionada(InfraDTO::$PREFIXO_STR, 'DescricaoServicoPostal', 'mdsp.descricao', 'md_cor_servico_postal mdsp');
      $this->adicionarAtributoTabelaRelacionada(InfraDTO::$PREFIXO_STR, 'CodigoWsCorreioServico', 'mdsp.codigo_ws_correios', 'md_cor_servico_postal mdsp');
      $this->adicionarAtributoTabelaRelacionada(InfraDTO::$PREFIXO_STR, 'ExpedicaoAvisoRecebimentoServico', 'mdsp.expedicao_aviso_recebimento', 'md_cor_servico_postal mdsp');
      $this->adicionarAtributoTabelaRelacionada(InfraDTO::$PREFIXO_NUM, 'IdMdCorTipoCorrespondenc', 'mdsp.id_md_cor_tipo_correspondenc', 'md_cor_servico_postal mdsp');

      //Dados Tipo
      $this->configurarFK('IdMdCorTipoCorrespondenc', 'md_cor_tipo_correspondenc mctcd', 'mctcd.id_md_cor_tipo_correspondenc', INFRADTO::$TIPO_FK_OPCIONAL);
      $this->adicionarAtributoTabelaRelacionada(InfraDTO::$PREFIXO_STR, 'NomeImagemChancela', 'mctcd.nome_imagem_chancela', 'md_cor_tipo_correspondenc mctcd');

      //Get Dados Contato - Destinatario
      $this->adicionarAtributoTabelaRelacionada(InfraDTO::$PREFIXO_STR, 'NomeDestinatario', 'cont.nome', 'md_cor_contato cont');
      $this->adicionarAtributoTabelaRelacionada(InfraDTO::$PREFIXO_STR, 'EnderecoDestinatario', 'cont.endereco', 'md_cor_contato cont');
      $this->adicionarAtributoTabelaRelacionada(InfraDTO::$PREFIXO_STR, 'BairroDestinatario', 'cont.bairro', 'md_cor_contato cont');
      $this->adicionarAtributoTabelaRelacionada(InfraDTO::$PREFIXO_STR, 'CepDestinatario', 'cont.cep', 'md_cor_contato cont');
      $this->adicionarAtributoTabelaRelacionada(InfraDTO::$PREFIXO_STR, 'ComplementoDestinatario', 'cont.complemento', 'md_cor_contato cont');
      $this->adicionarAtributoTabelaRelacionada(InfraDTO::$PREFIXO_STR, 'NomeCidadeDestinatario', 'cont.nome_cidade', 'md_cor_contato cont');
      $this->adicionarAtributoTabelaRelacionada(InfraDTO::$PREFIXO_STR, 'SiglaUfDestinatario', 'cont.sigla_uf', 'md_cor_contato cont');
      $this->adicionarAtributoTabelaRelacionada(InfraDTO::$PREFIXO_STR, 'SinEnderecoAssociado', 'cont.sin_endereco_associado', 'md_cor_contato cont');
      $this->adicionarAtributoTabelaRelacionada(InfraDTO::$PREFIXO_NUM, 'IdMdCorContato', 'cont.id_md_cor_contato', 'md_cor_contato cont');

      //Get Dados - Contrato Correio
      $this->adicionarAtributoTabelaRelacionada(InfraDTO::$PREFIXO_DBL, 'IdMdCorContrato', 'mdsp.id_md_cor_contrato', 'md_cor_servico_postal mdsp');
      $this->adicionarAtributoTabelaRelacionada(InfraDTO::$PREFIXO_NUM, 'NumeroCnpj', 'contr.numero_cnpj', 'md_cor_contrato contr');
      $this->adicionarAtributoTabelaRelacionada(InfraDTO::$PREFIXO_STR, 'NumeroContratoCorreio', 'contr.numero_contrato_correio', 'md_cor_contrato contr');
      $this->adicionarAtributoTabelaRelacionada(InfraDTO::$PREFIXO_STR, 'CartaoPostagem', 'contr.numero_cartao_postagem', 'md_cor_contrato contr');

      //Get Dados - PLP
      $this->adicionarAtributoTabelaRelacionada(InfraDTO::$PREFIXO_NUM, 'CodigoPlp', 'plp.codigo_plp', 'md_cor_plp plp');
      $this->adicionarAtributoTabelaRelacionada(InfraDTO::$PREFIXO_STR, 'StaPlp', 'plp.sta_plp', 'md_cor_plp plp');
      $this->adicionarAtributoTabelaRelacionada(InfraDTO::$PREFIXO_NUM, 'IdUnidadeGeradora', 'plp.id_unidade_geradora', 'md_cor_plp plp');

      // Get Dados Protocolo - Processo
      $this->adicionarAtributoTabelaRelacionada(InfraDTO::$PREFIXO_DBL, 'IdProtocolo', 'doc.id_procedimento', 'documento doc');
      $this->adicionarAtributoTabelaRelacionada(InfraDTO::$PREFIXO_STR, 'ProtocoloFormatado', 'pro.protocolo_formatado', 'protocolo pro');
      $this->adicionarAtributoTabelaRelacionada(InfraDTO::$PREFIXO_STR, 'ProtocoloFormatadoPesquisa', 'pro.protocolo_formatado_pesquisa', 'protocolo pro');
      $this->adicionarAtributoTabelaRelacionada(InfraDTO::$PREFIXO_DTA,'GeracaoProtocolo','pro.dta_geracao','protocolo pro');
      //Get Dados Protocolo - Documento
      $this->adicionarAtributoTabelaRelacionada(InfraDTO::$PREFIXO_DBL, 'IdProtocoloDocumento', 'doc.id_documento', 'documento doc');
      $this->adicionarAtributoTabelaRelacionada(InfraDTO::$PREFIXO_STR, 'ProtocoloFormatadoDocumento', 'prod.protocolo_formatado', 'protocolo prod');
      $this->adicionarAtributoTabelaRelacionada(InfraDTO::$PREFIXO_STR, 'NumeroDocumento', 'doc.numero', 'documento doc');
      $this->adicionarAtributoTabelaRelacionada(InfraDTO::$PREFIXO_DTA, 'GeracaoProtocolo', 'doc.numero', 'documento doc');

      //Get usuario - contato solicitante
      $this->adicionarAtributoTabelaRelacionada(InfraDTO::$PREFIXO_NUM, 'IdContatoSolicitante', 'usu.id_contato', 'usuario usu');
      $this->adicionarAtributoTabelaRelacionada(InfraDTO::$PREFIXO_STR, 'EmailSolicitante', 'contsoli.email', 'contato contsoli');
      $this->adicionarAtributoTabelaRelacionada(InfraDTO::$PREFIXO_STR, 'TelefoneCelularSolicitante', 'contsoli.telefone_celular', 'contato contsoli');

      //Get usuario - contato contrato Orgão
      $this->adicionarAtributoTabelaRelacionada(InfraDTO::$PREFIXO_STR, 'NomeContratoOrgao', 'contorg.nome', 'contato contorg');
      $this->adicionarAtributoTabelaRelacionada(InfraDTO::$PREFIXO_STR, 'EnderecoContratoOrgao', 'contorg.endereco', 'contato contorg');
      $this->adicionarAtributoTabelaRelacionada(InfraDTO::$PREFIXO_STR, 'BairroContratoOrgao', 'contorg.bairro', 'contato contorg');
      $this->adicionarAtributoTabelaRelacionada(InfraDTO::$PREFIXO_STR, 'CepContratoOrgao', 'contorg.cep', 'contato contorg');
      $this->adicionarAtributoTabelaRelacionada(InfraDTO::$PREFIXO_STR, 'ComplementoContratoOrgao', 'contorg.complemento', 'contato contorg');
      $this->adicionarAtributoTabelaRelacionada(InfraDTO::$PREFIXO_NUM, 'IdCidadeContratoOrgao', 'contorg.id_cidade', 'contato contorg');
      $this->adicionarAtributoTabelaRelacionada(InfraDTO::$PREFIXO_NUM, 'IdUfContratoOrgao', 'contorg.id_uf', 'contato contorg');
      $this->adicionarAtributoTabelaRelacionada(InfraDTO::$PREFIXO_STR, 'NomeCidadeContratoOrgao', 'cidorg.nome', 'cidade cidorg');
      $this->adicionarAtributoTabelaRelacionada(InfraDTO::$PREFIXO_STR, 'SiglaUfContratoOrgao', 'uforg.sigla', 'uf uforg');
      $this->adicionarAtributoTabelaRelacionada(InfraDTO::$PREFIXO_NUM, 'IdContatoOrgao', 'contorg.id_contato', 'contato contorg');

      // Get Objeto envio
      $this->configurarFK('IdMdCorObjeto', 'md_cor_objeto obj', 'obj.id_md_cor_objeto', INFRADTO::$TIPO_FK_OPCIONAL);
      $this->adicionarAtributoTabelaRelacionada(InfraDTO::$PREFIXO_NUM, 'IdMdCorObjeto', 'obj.id_md_cor_objeto', 'md_cor_objeto obj');
      $this->adicionarAtributoTabelaRelacionada(InfraDTO::$PREFIXO_DBL, 'MargemSuperiorImpressaoObjeto', 'obj.margem_superior_impressao', 'md_cor_objeto obj');
      $this->adicionarAtributoTabelaRelacionada(InfraDTO::$PREFIXO_DBL, 'MargemEsquerdaImpressaoObjeto', 'obj.margem_esquerda_impressao', 'md_cor_objeto obj');
      $this->adicionarAtributoTabelaRelacionada(InfraDTO::$PREFIXO_STR, 'TipoRotuloImpressaoObjeto', 'obj.tipo_rotulo_impressao', 'md_cor_objeto obj');
      $this->adicionarAtributoTabelaRelacionada(InfraDTO::$PREFIXO_NUM, 'IdMdCorTipoObjeto', 'obj.id_md_cor_tipo_objeto', 'md_cor_objeto obj');

      // Get Objeto Formatado
      $this->configurarFK('IdMdCorExpedicaoSolicitada', 'md_cor_expedicao_formato mcef', 'mcef.id_md_cor_expedicao_solicitada');
      $this->adicionarAtributoTabelaRelacionada(InfraDTO::$PREFIXO_NUM, 'IdDocumentoFormato', 'mcef.id_protocolo', 'md_cor_expedicao_formato mcef');
      $this->adicionarAtributoTabelaRelacionada(InfraDTO::$PREFIXO_STR, 'FormaExpedicao', 'mcef.sin_forma_expedicao', 'md_cor_expedicao_formato mcef');

      //Atributos Simples
      $this->adicionarAtributo(InfraDTO::$PREFIXO_STR, 'Anexos');
      $this->adicionarAtributo(InfraDTO::$PREFIXO_STR, 'DocSerieFormatados');
      $this->adicionarAtributo(InfraDTO::$PREFIXO_STR, 'UltimoAndamento');
      $this->adicionarAtributo(InfraDTO::$PREFIXO_ARR, 'ProtocolosAnexos');
      $this->adicionarAtributo(InfraDTO::$PREFIXO_ARR, 'MdCorExpedicaoFormatoDTO');
      $this->adicionarAtributo(InfraDTO::$PREFIXO_NUM, 'QuantidadeAnexo');
      $this->adicionarAtributo(InfraDTO::$PREFIXO_STR, 'Midia');
      $this->adicionarAtributo(InfraDTO::$PREFIXO_NUM, 'QuantidadeObjeto');

      //Documento Principal
      $this->configurarFK('IdDocumentoPrincipal', 'md_cor_retorno_ar_doc retornodoc', 'retornodoc.id_documento_principal', INFRADTO::$TIPO_FK_OPCIONAL);
      $this->adicionarAtributoTabelaRelacionada(InfraDTO::$PREFIXO_NUM, 'IdMdCorRetornoArDoc', 'retornodoc.id_md_cor_retorno_ar_doc', 'md_cor_retorno_ar_doc retornodoc');
      $this->adicionarAtributoTabelaRelacionada(InfraDTO::$PREFIXO_DTA, 'DataAr', 'retornodoc.data_ar', 'md_cor_retorno_ar_doc retornodoc');
      $this->adicionarAtributoTabelaRelacionada(InfraDTO::$PREFIXO_DTA, 'DataRetorno', 'retornodoc.data_retorno', 'md_cor_retorno_ar_doc retornodoc');
      $this->adicionarAtributoTabelaRelacionada(InfraDTO::$PREFIXO_DTA, 'DataRetorno', 'retornodoc.data_retorno', 'md_cor_retorno_ar_doc retornodoc');
      $this->adicionarAtributoTabelaRelacionada(InfraDTO::$PREFIXO_NUM, 'IdMdCorParamArInfrigencia', 'retornodoc.id_md_cor_param_ar_infrigencia', 'md_cor_retorno_ar_doc retornodoc');
      $this->adicionarAtributoTabelaRelacionada(InfraDTO::$PREFIXO_NUM, 'IdDocumentoAr', 'retornodoc.id_documento_ar', 'md_cor_retorno_ar_doc retornodoc');

      //Procedimento
      $this->configurarFK('IdDocumentoPrincipal', 'rel_protocolo_protocolo rpp', 'rpp.id_protocolo_2');
      $this->adicionarAtributoTabelaRelacionada(InfraDTO::$PREFIXO_NUM, 'IdProcedimento', 'rpp.id_protocolo_1', 'rel_protocolo_protocolo rpp');

      //Documento de Cobrança
      $this->configurarFK('IdMdCorExpedicaoSolicitada', 'md_cor_ar_cobranca mcacob', 'mcacob.id_md_cor_expedicao_solicitada', INFRADTO::$TIPO_FK_OPCIONAL);
      $this->configurarFK('IdDocumentoCobranca', 'protocolo prodcob', 'prodcob.id_protocolo', INFRADTO::$TIPO_FK_OPCIONAL);
      $this->configurarFK('IdDocumentoCobranca', 'documento doccob', 'doccob.id_documento', INFRADTO::$TIPO_FK_OPCIONAL);
      $this->configurarFK('IdSerieCobranca', 'serie scob', 'scob.id_serie', INFRADTO::$TIPO_FK_OPCIONAL);
      $this->adicionarAtributoTabelaRelacionada(InfraDTO::$PREFIXO_NUM, 'IdDocumentoCobranca', 'mcacob.id_documento_cobranca', 'md_cor_ar_cobranca mcacob');
      $this->adicionarAtributoTabelaRelacionada(InfraDTO::$PREFIXO_STR, 'ProtocoloFormatadoCobranca', 'prodcob.protocolo_formatado', 'protocolo prodcob');
      $this->adicionarAtributoTabelaRelacionada(InfraDTO::$PREFIXO_STR, 'NumeroDocumentoCobranca', 'doccob.numero', 'documento doccob');
      $this->adicionarAtributoTabelaRelacionada(InfraDTO::$PREFIXO_NUM, 'IdSerieCobranca', 'doccob.id_serie', 'documento doccob');
      $this->adicionarAtributoTabelaRelacionada(InfraDTO::$PREFIXO_STR, 'NomeSerieCobranca', 'scob.nome', 'serie scob');


    }

    public function getStrNomeStaPlp()
    {
      switch ($this->getStrStaPlp()) {
        case MdCorPlpRN::$STA_PENDENTE:
          return 'Possui Objeto Pendente de Entrega';
        case MdCorPlpRN::$STA_ENTREGUES:
          return 'Todos os Objetos Entregues';
        case MdCorPlpRN::$STA_RETORNO_AR_PENDENTE:
          return 'Objetos com Retorno de AR Pendente';
        case MdCorPlpRN::$STA_FINALIZADA:
          return 'Finalizada';
      }
    }
  }