<?
/**
* TRIBUNAL REGIONAL FEDERAL DA 4ª REGIÃO
*
* 27/10/2017 - criado por augusto.cast
*
* Versão do Gerador de Código: 1.41.0
*/

require_once dirname(__FILE__) . '/../../../SEI.php';

class MdCorDiretoriaDTO extends InfraDTO {

  public function getStrNomeTabela() {
  	 return 'md_cor_diretoria';
  }

  public function montar() {
    $this->adicionarAtributoTabela(InfraDTO::$PREFIXO_NUM, 'IdMdCorDiretoria', 'id_md_cor_diretoria');
    $this->adicionarAtributoTabela(InfraDTO::$PREFIXO_STR, 'CodigoDiretoria', 'codigo_diretoria');
    $this->adicionarAtributoTabela(InfraDTO::$PREFIXO_STR, 'DescricaoDiretoria', 'descricao_diretoria');
    $this->adicionarAtributoTabela(InfraDTO::$PREFIXO_STR, 'SiglaDiretoria', 'sigla_diretoria');

    $this->configurarPK('IdMdCorDiretoria', InfraDTO::$TIPO_PK_NATIVA);

  }
}
