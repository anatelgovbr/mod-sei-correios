<?
/**
* TRIBUNAL REGIONAL FEDERAL DA 4ª REGIÃO
*
* 22/12/2016 - criado por CAST
*
* Versão do Gerador de Código: 1.39.0
*/

require_once dirname(__FILE__).'/../../../SEI.php';

class MdCorMapUnidServicoDTO extends InfraDTO {

  public function getStrNomeTabela() {
  	 return 'md_cor_map_unid_servico';
  }

  public function montar() {

    $this->adicionarAtributoTabela(InfraDTO::$PREFIXO_NUM,
                                   'IdMdCorMapUnidServico',
                                   'id_md_cor_map_unid_servico');

    $this->adicionarAtributoTabela(InfraDTO::$PREFIXO_NUM,
                                   'IdUnidadeSolicitante',
                                   'id_unidade_solicitante');
    
    $this->adicionarAtributoTabela(InfraDTO::$PREFIXO_NUM,
                                   'IdMdCorServicoPostal',
                                   'id_md_cor_servico_postal');    
    
    $this->adicionarAtributoTabela(InfraDTO::$PREFIXO_STR,
                                   'SinAtivo',
                                   'sin_ativo');


    //Unidade
    $this->adicionarAtributoTabelaRelacionada(InfraDTO::$PREFIXO_STR, 'SiglaUnidade', 'sigla', 'unidade');
    $this->adicionarAtributoTabelaRelacionada(InfraDTO::$PREFIXO_STR, 'DescricaoUnidade', 'descricao', 'unidade');
    $this->adicionarAtributo(InfraDTO::$PREFIXO_STR, 'SiglaDescricaoUnidade');

    //Contrato
    $this->adicionarAtributoTabelaRelacionada(InfraDTO::$PREFIXO_STR, 'NumeroContrato', 'numero_contrato', 'md_cor_contrato');
    $this->adicionarAtributoTabelaRelacionada(InfraDTO::$PREFIXO_STR, 'NumeroContratoCorreio', 'numero_contrato_correio', 'md_cor_contrato');
    $this->adicionarAtributoTabelaRelacionada(InfraDTO::$PREFIXO_STR, 'SinAtivoContrato', 'sin_ativo', 'md_cor_contrato');

    //Serviço Postal
    $this->adicionarAtributoTabelaRelacionada(InfraDTO::$PREFIXO_STR, 'NomeServico', 'md.nome', 'md_cor_servico_postal md');
    $this->adicionarAtributoTabelaRelacionada(InfraDTO::$PREFIXO_STR, 'DescricaoServico', 'md.descricao', 'md_cor_servico_postal md');
    $this->adicionarAtributoTabelaRelacionada(InfraDTO::$PREFIXO_STR, 'IdMdCorContrato', 'md.id_md_cor_contrato', 'md_cor_servico_postal md');
    $this->adicionarAtributoTabelaRelacionada(InfraDTO::$PREFIXO_STR, 'SinAtivoServicoPostal', 'md.sin_ativo', 'md_cor_servico_postal md');
    $this->adicionarAtributo(InfraDTO::$PREFIXO_STR, 'NomeServicos');


    $this->configurarPK('IdMdCorMapUnidServico',InfraDTO::$TIPO_PK_NATIVA);

    //Unidade
    $this->configurarFK('IdUnidadeSolicitante', 'unidade', 'id_unidade');

    //Contrato
    $this->configurarFK('IdMdCorContrato', 'md_cor_contrato', 'id_md_cor_contrato');

    //Serviço Postal
    $this->configurarFK('IdMdCorServicoPostal', 'md_cor_servico_postal md', 'md.id_md_cor_servico_postal');


    //$this->configurarExclusaoLogica('SinAtivo', 'N');

  }
}
?>