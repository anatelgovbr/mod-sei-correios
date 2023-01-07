<?
/**
* TRIBUNAL REGIONAL FEDERAL DA 4ª REGIÃO
*
* 12/09/2018 - criado por augusto.cast
*
* Versão do Gerador de Código: 1.41.0
*/

  require_once dirname(__FILE__) . '/../../../SEI.php';

class MdCorParametroRastreioDTO extends InfraDTO {

  public function getStrNomeTabela() {
  	 return 'md_cor_parametro_rastreio';
  }

  public function montar() {

    $this->adicionarAtributoTabela(InfraDTO::$PREFIXO_NUM, 'IdMdCorParametroRastreio', 'id_md_cor_parametro_rastreio');

    $this->adicionarAtributoTabela(InfraDTO::$PREFIXO_STR, 'Usuario', 'usuario');

    $this->adicionarAtributoTabela(InfraDTO::$PREFIXO_STR, 'Senha', 'senha');

    $this->adicionarAtributoTabela(InfraDTO::$PREFIXO_STR, 'EnderecoWsdl', 'endereco_wsdl');

    $this->configurarPK('IdMdCorParametroRastreio', InfraDTO::$TIPO_PK_NATIVA);


  }
}
