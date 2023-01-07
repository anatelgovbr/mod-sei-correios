<?
/**
 * TRIBUNAL REGIONAL FEDERAL DA 4ª REGIÃO
 *
 * 29/06/2018 - criado por augusto.cast
 *
 * Versão do Gerador de Código: 1.41.0
 */

require_once dirname(__FILE__) . '/../../../SEI.php';

class MdCorRetornoArDTO extends InfraDTO
{

  public function getStrNomeTabela()
  {
    return 'md_cor_retorno_ar';
  }

  public function montar()
  {

    $this->adicionarAtributoTabela(InfraDTO::$PREFIXO_NUM, 'IdMdCorRetornoAr', 'id_md_cor_retorno_ar');

    $this->adicionarAtributoTabela(InfraDTO::$PREFIXO_DTH, 'DataCadastro', 'data_cadastro');

    $this->adicionarAtributoTabela(InfraDTO::$PREFIXO_NUM, 'IdUsuario', 'id_usuario');

    $this->adicionarAtributoTabela(InfraDTO::$PREFIXO_STR, 'SinAutenticado', 'sin_autenticado');

    $this->adicionarAtributoTabela(InfraDTO::$PREFIXO_NUM, 'IdUnidade', 'id_unidade');

    $this->adicionarAtributoTabela(InfraDTO::$PREFIXO_STR, 'NomeArquivoZip', 'nome_arquivo_zip');

    $this->configurarPK('IdMdCorRetornoAr', InfraDTO::$TIPO_PK_NATIVA);

    //Usuario
    $this->configurarFK('IdUsuario', 'usuario u', 'u.id_usuario');
    $this->adicionarAtributoTabelaRelacionada(InfraDTO::$PREFIXO_STR, 'NomeUsuario', 'u.nome', 'usuario u');
    
    //Retorno Ar Documento
    $this->configurarFK('IdMdCorRetornoAr', 'md_cor_retorno_ar_doc rardoc', 'rardoc.id_md_cor_retorno_ar');
    $this->adicionarAtributoTabelaRelacionada(InfraDTO::$PREFIXO_NUM, 'IdDocumentoPrincipal', 'rardoc.id_documento_principal', 'md_cor_retorno_ar_doc rardoc');
    
    //Documento
    $this->configurarFK('IdDocumentoPrincipal', 'protocolo d', 'd.id_protocolo');
    $this->adicionarAtributoTabelaRelacionada(InfraDTO::$PREFIXO_STR, 'ProtocoloDocumentoFormatado', 'd.protocolo_formatado', 'protocolo d');
    
    
    $this->configurarFK('IdDocumentoPrincipal', 'rel_protocolo_protocolo pp', 'pp.id_protocolo_2');
    $this->adicionarAtributoTabelaRelacionada(InfraDTO::$PREFIXO_STR, 'ProtocoloFormatadoProtocolo1', 'pp.id_protocolo_1', 'rel_protocolo_protocolo pp');
    
    //Documento
    $this->configurarFK('ProtocoloFormatadoProtocolo1', 'protocolo d2', 'd2.id_protocolo');
    $this->adicionarAtributoTabelaRelacionada(InfraDTO::$PREFIXO_STR, 'ProtocoloProcedimentoFormatado', 'd2.protocolo_formatado', 'protocolo d2');
    
    //Documento
    $this->configurarFK('IdMdCorPlp', 'md_cor_plp plp', 'plp.id_md_cor_plp');
    $this->adicionarAtributoTabelaRelacionada(InfraDTO::$PREFIXO_STR, 'CodigoPlp', 'plp.codigo_plp', 'md_cor_plp plp');
    
    
    //Expedicao Solicitaca
    $this->configurarFK('IdDocumentoPrincipal', 'md_cor_expedicao_solicitad expsol', 'expsol.id_documento_principal');
    $this->adicionarAtributoTabelaRelacionada(InfraDTO::$PREFIXO_NUM, 'IdMdCorPlp', 'expsol.id_md_cor_plp', 'md_cor_expedicao_solicitad expsol');
    $this->adicionarAtributoTabelaRelacionada(InfraDTO::$PREFIXO_STR, 'CodigoRastreamento', 'expsol.codigo_rastreamento', 'md_cor_expedicao_solicitad expsol');
  }
}
