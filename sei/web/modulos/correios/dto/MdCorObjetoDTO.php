<?
/**
* TRIBUNAL REGIONAL FEDERAL DA 4ª REGIÃO
*
* 14/11/2017 - criado por ellyson.silva
*
* Versão do Gerador de Código: 1.41.0
*/

require_once dirname(__FILE__).'/../../../SEI.php';

class MdCorObjetoDTO extends InfraDTO {

  public function getStrNomeTabela() {
  	 return 'md_cor_objeto';
  }

  public function montar() {

    $this->adicionarAtributoTabela(InfraDTO::$PREFIXO_NUM, 'IdMdCorObjeto', 'id_md_cor_objeto');

    $this->adicionarAtributoTabela(InfraDTO::$PREFIXO_NUM, 'IdMdCorTipoObjeto', 'id_md_cor_tipo_objeto');

    $this->adicionarAtributoTabela(InfraDTO::$PREFIXO_NUM, 'IdMdCorContrato', 'id_md_cor_contrato');

    $this->adicionarAtributoTabela(InfraDTO::$PREFIXO_STR, 'TipoRotuloImpressao', 'tipo_rotulo_impressao');

    $this->adicionarAtributoTabela(InfraDTO::$PREFIXO_STR, 'SinObjetoPadrao', 'sin_objeto_padrao');

    $this->adicionarAtributoTabela(InfraDTO::$PREFIXO_STR, 'SinAtivo', 'sin_ativo');

    $this->adicionarAtributoTabela(InfraDTO::$PREFIXO_DBL, 'MargemSuperiorImpressao', 'margem_superior_impressao');

    $this->adicionarAtributoTabela(InfraDTO::$PREFIXO_DBL, 'MargemEsquerdaImpressao', 'margem_esquerda_impressao');

    $this->configurarPK('IdMdCorObjeto',InfraDTO::$TIPO_PK_NATIVA);

    $this->configurarFK('IdMdCorTipoObjeto', 'md_cor_tipo_objeto', 'id_md_cor_tipo_objeto');
    $this->configurarFK('IdMdCorContrato', 'md_cor_contrato', 'id_md_cor_contrato');

      $this->adicionarAtributoTabelaRelacionada(InfraDTO::$PREFIXO_STR,'NomeTipoObjeto','nome', 'md_cor_tipo_objeto');

  }

  public function getStrRotuloImpressao(){
      switch ($this->getStrTipoRotuloImpressao()){
          case MdCorObjetoRN::$ROTULO_COMPLETO:
              return 'Completo';
              break;
          case MdCorObjetoRN::$ROTULO_RESUMIDO:
              return 'Resumido';
              break;
      }
  }
}
