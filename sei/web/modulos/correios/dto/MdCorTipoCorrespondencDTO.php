<?
/**
* TRIBUNAL REGIONAL FEDERAL DA 4ª REGIÃO
*
* 04/12/2017 - criado por augusto.cast
*
* Versão do Gerador de Código: 1.41.0
*/

require_once dirname(__FILE__) . '/../../../SEI.php';


class MdCorTipoCorrespondencDTO extends InfraDTO {

  public function getStrNomeTabela() {
  	 return 'md_cor_tipo_correspondenc';
  }

  public function montar() {

    $this->adicionarAtributoTabela(InfraDTO::$PREFIXO_NUM, 'IdMdCorTipoCorrespondenc', 'id_md_cor_tipo_correspondenc');

    $this->adicionarAtributoTabela(InfraDTO::$PREFIXO_STR, 'NomeTipo', 'nome_tipo');

    $this->adicionarAtributoTabela(InfraDTO::$PREFIXO_STR, 'SinAr', 'sin_ar');

    $this->adicionarAtributoTabela(InfraDTO::$PREFIXO_STR, 'NomeImagemChancela', 'nome_imagem_chancela');

    $this->configurarPK('IdMdCorTipoCorrespondenc', InfraDTO::$TIPO_PK_NATIVA);

  }
}
