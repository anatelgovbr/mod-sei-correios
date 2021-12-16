<?
/**
* TRIBUNAL REGIONAL FEDERAL DA 4ª REGIÃO
*
* 29/06/2018 - criado por augusto.cast
*
* Versão do Gerador de Código: 1.41.0
*/

require_once dirname(__FILE__) . '/../../../SEI.php';

class MdCorRetornoArDocDTO extends InfraDTO
{

    public function getStrNomeTabela()
    {
        return 'md_cor_retorno_ar_doc';
    }

    public function montar()
    {

        $this->adicionarAtributoTabela(InfraDTO::$PREFIXO_NUM, 'IdMdCorRetornoArDoc', 'id_md_cor_retorno_ar_doc');
        $this->adicionarAtributoTabela(InfraDTO::$PREFIXO_NUM, 'IdMdCorRetornoAr', 'id_md_cor_retorno_ar');
        $this->adicionarAtributoTabela(InfraDTO::$PREFIXO_NUM, 'IdDocumentoPrincipal', 'id_documento_principal');
        $this->adicionarAtributoTabela(InfraDTO::$PREFIXO_NUM, 'IdProtocolo', 'id_documento_principal');
        $this->adicionarAtributoTabela(InfraDTO::$PREFIXO_DTA, 'DataAr', 'data_ar');
        $this->adicionarAtributoTabela(InfraDTO::$PREFIXO_DTA, 'DataRetorno', 'data_retorno');
        $this->adicionarAtributoTabela(InfraDTO::$PREFIXO_STR, 'SinStatus', 'sin_status');
        $this->adicionarAtributoTabela(InfraDTO::$PREFIXO_STR, 'NomeArquivoPdf', 'nome_arquivo_pdf');
        $this->adicionarAtributoTabela(InfraDTO::$PREFIXO_NUM, 'IdDocumentoAr', 'id_documento_ar');
        $this->adicionarAtributoTabela(InfraDTO::$PREFIXO_NUM, 'IdMdCorParamArInfrigencia', 'id_md_cor_param_ar_infrigencia');
        $this->adicionarAtributoTabela(InfraDTO::$PREFIXO_STR, 'SinRetorno', 'sin_retorno');
        $this->adicionarAtributoTabela(InfraDTO::$PREFIXO_NUM, 'IdStatusProcess', 'id_status_process');
        $this->adicionarAtributoTabela(InfraDTO::$PREFIXO_NUM, 'IdSubStatusProcess', 'id_substatus_process');
        $this->adicionarAtributoTabela(InfraDTO::$PREFIXO_STR, 'CodigoRastreamento', 'codigo_rastreamento');

        // Retorno
        $this->adicionarAtributoTabelaRelacionada(InfraDTO::$PREFIXO_STR, 'SinAutenticado', 'mcra.sin_autenticado', 'md_cor_retorno_ar mcra');

        //Documento Principal
        $this->adicionarAtributoTabelaRelacionada(InfraDTO::$PREFIXO_NUM, 'IdProcedimento', 'd.id_procedimento', 'documento d');
        $this->adicionarAtributoTabelaRelacionada(InfraDTO::$PREFIXO_NUM, 'IdSerie', 'd.id_serie', 'documento d');
        $this->adicionarAtributoTabelaRelacionada(InfraDTO::$PREFIXO_STR, 'ProtocoloFormatadoDocumento', 'prod.protocolo_formatado', 'protocolo prod');
        $this->adicionarAtributoTabelaRelacionada(InfraDTO::$PREFIXO_STR, 'NumeroDocumento', 'd.numero', 'documento d');
        $this->adicionarAtributoTabelaRelacionada(InfraDTO::$PREFIXO_NUM, 'IdUnidadeResponsavel', 'd.id_unidade_responsavel', 'documento d');
        $this->adicionarAtributoTabelaRelacionada(InfraDTO::$PREFIXO_STR, 'NomeSerie', 's.nome', 'serie s');

        //Unidade Documento Principal
        $this->adicionarAtributoTabelaRelacionada(InfraDTO::$PREFIXO_STR, 'UnidadePrincipal', 'up.sigla', 'unidade up');
        $this->adicionarAtributoTabelaRelacionada(InfraDTO::$PREFIXO_NUM, 'IdOrgaoPrincipal', 'up.id_orgao', 'unidade up');

        //Orgao Documento Principal
        $this->adicionarAtributoTabelaRelacionada(InfraDTO::$PREFIXO_STR, 'OrgaoPrincipal', 'op.sigla', 'orgao op');

        //Documento AR
        $this->adicionarAtributoTabelaRelacionada(InfraDTO::$PREFIXO_STR, 'NumeroDocumentoAR', 'da.numero', 'documento da');
        $this->adicionarAtributoTabelaRelacionada(InfraDTO::$PREFIXO_NUM, 'IdDocumentoAr', 'da.id_documento', 'documento da');

        //Documento AR
        $this->adicionarAtributoTabelaRelacionada(InfraDTO::$PREFIXO_NUM, 'IdSubStatusProcess', 'ss.id_md_cor_substatus_process', 'md_cor_substatus_process ss');
        $this->adicionarAtributoTabelaRelacionada(InfraDTO::$PREFIXO_STR, 'DescricaoSubStatusProcess', 'ss.descricao', 'md_cor_substatus_process ss');

        // Get Dados Protocolo - Processo
        $this->adicionarAtributoTabelaRelacionada(InfraDTO::$PREFIXO_STR, 'ProtocoloFormatado', 'prodp.protocolo_formatado', 'protocolo prodp');

        //Documento Principal
        $this->configurarFK('IdDocumentoPrincipal', 'md_cor_expedicao_solicitad es', 'es.id_documento_principal', InfraDTO::$TIPO_FK_OPCIONAL);
        $this->adicionarAtributoTabelaRelacionada(InfraDTO::$PREFIXO_NUM, 'IdDocumentoPrincipal', 'es.id_documento_principal', 'md_cor_expedicao_solicitad es');
        $this->adicionarAtributoTabelaRelacionada(InfraDTO::$PREFIXO_NUM, 'IdMdCorPlp', 'es.id_md_cor_plp', 'md_cor_expedicao_solicitad es');

        //Código PLP
        $this->configurarFK('IdMdCorPlp', 'md_cor_plp plp', 'plp.id_md_cor_plp', InfraDTO::$TIPO_FK_OPCIONAL);
        $this->adicionarAtributoTabelaRelacionada(InfraDTO::$PREFIXO_STR, 'CodigoPlp', 'plp.codigo_plp', 'md_cor_plp plp');

        $this->configurarPK('IdMdCorRetornoArDoc', InfraDTO::$TIPO_PK_NATIVA);
        $this->configurarFK('IdDocumentoPrincipal', 'documento d', 'd.id_documento', InfraDTO::$TIPO_FK_OPCIONAL);
        $this->configurarFK('IdDocumentoAr', 'documento da', 'da.id_documento', InfraDTO::$TIPO_FK_OPCIONAL);
        $this->configurarFK('IdProtocolo', 'protocolo prod', 'prod.id_protocolo', InfraDTO::$TIPO_FK_OPCIONAL);
        $this->configurarFK('IdProcedimento', 'protocolo prodp', 'prodp.id_protocolo', InfraDTO::$TIPO_FK_OPCIONAL);
        $this->configurarFK('IdDocumentoPrincipal', 'md_cor_expedicao_solicitad mces', 'mces.id_documento_principal', InfraDTO::$TIPO_FK_OPCIONAL);
        $this->configurarFK('IdSerie', 'serie s', 's.id_serie', InfraDTO::$TIPO_FK_OPCIONAL);
        $this->configurarFK('IdUnidadeResponsavel', 'unidade up', 'up.id_unidade', InfraDTO::$TIPO_FK_OPCIONAL);
        $this->configurarFK('IdOrgaoPrincipal', 'orgao op', 'op.id_orgao', InfraDTO::$TIPO_FK_OPCIONAL);
        $this->configurarFK('IdSubStatusProcess', 'md_cor_substatus_process ss', 'ss.id_md_cor_substatus_process', InfraDTO::$TIPO_FK_OPCIONAL);
        $this->configurarFK('IdMdCorRetornoAr', 'md_cor_retorno_ar mcra', 'mcra.id_md_cor_retorno_ar');

    }

    public function getStrTipoDocumento()
    {
        if (!is_null($this->getStrNumeroDocumento())) {
            return 'Principal';
        }

        return 'Anexo';
    }
}
