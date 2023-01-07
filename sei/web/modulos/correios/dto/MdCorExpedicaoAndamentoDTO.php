<?
/**
* TRIBUNAL REGIONAL FEDERAL DA 4ª REGIÃO
*
* 07/06/2017 - criado por marcelo.cast
*
* Versão do Gerador de Código: 1.40.1
*/

require_once dirname(__FILE__) . '/../../../SEI.php';

class MdCorExpedicaoAndamentoDTO extends InfraDTO {

  public function getStrNomeTabela() {
  	 return 'md_cor_expedicao_andamento';
  }

  public function montar() {

    $this->adicionarAtributoTabela(InfraDTO::$PREFIXO_NUM, 'IdMdCorExpedicaoAndamento', 'id_md_cor_expedicao_andamento');

    $this->adicionarAtributoTabela(InfraDTO::$PREFIXO_DTH, 'DataHora', 'data_hora');

      $this->adicionarAtributoTabela(InfraDTO::$PREFIXO_DTH, 'DataUltimaAtualizacao', 'data_ultima_atualizacao');

    $this->adicionarAtributoTabela(InfraDTO::$PREFIXO_STR, 'Descricao', 'descricao');

    $this->adicionarAtributoTabela(InfraDTO::$PREFIXO_NUM, 'IdMdCorExpedicaoSolicitada', 'id_md_cor_expedicao_solicitada');

    $this->adicionarAtributoTabela(InfraDTO::$PREFIXO_STR, 'Detalhe', 'detalhe');

    $this->adicionarAtributoTabela(InfraDTO::$PREFIXO_NUM, 'Status', 'status');

    $this->adicionarAtributoTabela(InfraDTO::$PREFIXO_STR, 'Local', 'local');

    $this->adicionarAtributoTabela(InfraDTO::$PREFIXO_STR, 'CodigoCep', 'codigo_cep');

    $this->adicionarAtributoTabela(InfraDTO::$PREFIXO_STR, 'Cidade', 'cidade');

    $this->adicionarAtributoTabela(InfraDTO::$PREFIXO_STR, 'Uf', 'uf');

    $this->adicionarAtributoTabela(InfraDTO::$PREFIXO_STR, 'VersaoSroXml', 'versao_sro_xml');

    $this->adicionarAtributoTabela(InfraDTO::$PREFIXO_STR, 'SiglaObjeto', 'sigla_objeto');

    $this->adicionarAtributoTabela(InfraDTO::$PREFIXO_STR, 'NomeObjeto', 'nome_objeto');

    $this->adicionarAtributoTabela(InfraDTO::$PREFIXO_STR, 'CategoriaObjeto', 'categoria_objeto');

    $this->adicionarAtributoTabela(InfraDTO::$PREFIXO_STR, 'Tipo', 'tipo');

    $this->adicionarAtributo(InfraDTO::$PREFIXO_STR, 'StaRastreioModulo', 'sta_rastreio_modulo');

    $this->configurarPK('IdMdCorExpedicaoAndamento',InfraDTO::$TIPO_PK_NATIVA);
    
    $this->configurarFK('IdMdCorExpedicaoSolicitada', 'md_cor_expedicao_solicitada mdExpSolic', 'mdExpSolic.id_md_cor_expedicao_solicitada');

  }
}
?>