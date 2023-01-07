<?
/**
* TRIBUNAL REGIONAL FEDERAL DA 4� REGI�O
*
* 14/11/2017 - criado por ellyson.silva
*
* Vers�o do Gerador de C�digo: 1.41.0
*/

require_once dirname(__FILE__).'/../../../SEI.php';

class MdCorTipoObjetoDTO extends InfraDTO {

  public function getStrNomeTabela() {
  	 return 'md_cor_tipo_objeto';
  }

  public function montar() {

    $this->adicionarAtributoTabela(InfraDTO::$PREFIXO_NUM, 'IdMdCorTipoObjeto', 'id_md_cor_tipo_objeto');

    $this->adicionarAtributoTabela(InfraDTO::$PREFIXO_STR, 'Nome', 'nome');

      $this->adicionarAtributoTabela(InfraDTO::$PREFIXO_STR, 'CodigoCorreio', 'codigo_correio');

    $this->configurarPK('IdMdCorTipoObjeto',InfraDTO::$TIPO_PK_NATIVA);

  }
}
