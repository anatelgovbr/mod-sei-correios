<?
/**
* TRIBUNAL REGIONAL FEDERAL DA 4ª REGIÃO
*
* 23/12/2016 - criado por Wilton Júnior
*
* Versão do Gerador de Código: 1.39.0
*/

require_once dirname(__FILE__).'/../../../SEI.php';

class MdCorServicoPostalDTO extends InfraDTO {

  public function getStrNomeTabela() {
  	 return 'md_cor_servico_postal';
  }

  public function montar() {

    $this->adicionarAtributoTabela(InfraDTO::$PREFIXO_NUM,
                                   'IdMdCorServicoPostal',
                                   'id_md_cor_servico_postal');

    $this->adicionarAtributoTabela(InfraDTO::$PREFIXO_NUM,
                                   'IdMdCorContrato',
                                   'id_md_cor_contrato');

    $this->adicionarAtributoTabela(InfraDTO::$PREFIXO_NUM,
                                   'IdMdCorTipoCorrespondencia',
                                   'id_md_cor_tipo_correspondenc');

    $this->adicionarAtributoTabela(InfraDTO::$PREFIXO_STR,
                                   'Nome',
                                   'nome');

    $this->adicionarAtributoTabela(InfraDTO::$PREFIXO_STR,
                                   'IdWsCorreios',
                                   'id_ws_correios');

    $this->adicionarAtributoTabela(InfraDTO::$PREFIXO_STR,
                                   'CodigoWsCorreios',
                                   'codigo_ws_correios');

    $this->adicionarAtributoTabela(InfraDTO::$PREFIXO_STR,
                                   'ExpedicaoAvisoRecebimento',
                                   'expedicao_aviso_recebimento');

    $this->adicionarAtributoTabela(InfraDTO::$PREFIXO_STR,
                                   'Descricao',
                                   'descricao');

    $this->adicionarAtributoTabela(InfraDTO::$PREFIXO_STR,
                                   'SinServicoCobrar',
                                   'sin_servico_cobrar ');

    $this->adicionarAtributoTabela(InfraDTO::$PREFIXO_STR,
      'SinAtivo',
      'sin_ativo');

    $this->configurarPK('IdMdCorServicoPostal',InfraDTO::$TIPO_PK_NATIVA);

    $this->configurarExclusaoLogica('SinAtivo', 'N');

    $this->configurarFK('IdMdCorTipoCorrespondencia', 'md_cor_tipo_correspondenc tpc', 'tpc.id_md_cor_tipo_correspondenc', INFRADTO::$TIPO_FK_OPCIONAL);
    $this->adicionarAtributoTabelaRelacionada(InfraDTO::$PREFIXO_NUM, 'IdMdCorTipoCorrespondencia', 'tpc.id_md_cor_tipo_correspondenc', 'md_cor_tipo_correspondenc tpc');
    $this->adicionarAtributoTabelaRelacionada(InfraDTO::$PREFIXO_STR, 'SinAr', 'tpc.sin_ar', 'md_cor_tipo_correspondenc tpc');

  }
}
?>
