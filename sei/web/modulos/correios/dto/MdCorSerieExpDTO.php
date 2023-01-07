<?
/**
* TRIBUNAL REGIONAL FEDERAL DA 4ª REGIÃO
*
* 21/12/2016 - criado por CAST
*
* Versão do Gerador de Código: 1.39.0
*/

//require_once dirname(__FILE__).'/../SEI.php';
require_once dirname(__FILE__).'/../../../SEI.php';

class MdCorSerieExpDTO extends InfraDTO {

  public function getStrNomeTabela() {
  	 return 'md_cor_serie_exp';
  }

  public function montar() {

    $this->adicionarAtributoTabela(InfraDTO::$PREFIXO_NUM,
                                   'IdSerie',
                                   'id_serie');

    $this->adicionarAtributoTabela(InfraDTO::$PREFIXO_STR,
                                   'SinAtivo',
                                   'sin_ativo');

    //Serie
    $this->adicionarAtributoTabelaRelacionada(InfraDTO::$PREFIXO_STR, 'NomeSerie', 'nome', 'serie');

    //Serie
    $this->configurarFK('IdSerie', 'serie', 'id_serie');
  	
    $this->configurarPK('IdSerie',InfraDTO::$TIPO_PK_INFORMADO);

    //$this->configurarExclusaoLogica('SinAtivo', 'N');

  }
}
?>