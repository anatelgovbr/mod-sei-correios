<?
/**
* TRIBUNAL REGIONAL FEDERAL DA 4ª REGIÃO
*
* 11/10/2017 - criado por José Vieira <jose.vieira@cast.com.br>
*
* Versão do Gerador de Código: 1.41.0
*/

require_once dirname(__FILE__).'/../../../SEI.php';

class MdCorPlpDTO extends InfraDTO {

  public function getStrNomeTabela() {
  	 return 'md_cor_plp';
  }

  public function montar() {

    $this->adicionarAtributoTabela(InfraDTO::$PREFIXO_NUM, 'IdMdPlp', 'id_md_cor_plp');

    $this->adicionarAtributoTabela(InfraDTO::$PREFIXO_DBL, 'CodigoPlp', 'codigo_plp');

    $this->adicionarAtributoTabela(InfraDTO::$PREFIXO_STR, 'StaPlp', 'sta_plp');

    $this->adicionarAtributoTabela(InfraDTO::$PREFIXO_NUM, 'IdUnidadeGeradora', 'id_unidade_geradora');
    $this->adicionarAtributoTabela(InfraDTO::$PREFIXO_DTH, 'DataCadastro', 'data_cadastro');

    $this->adicionarAtributo(InfraDTO::$PREFIXO_NUM, 'Contagem');
    
    $this->adicionarAtributo(InfraDTO::$PREFIXO_STR, 'Midia');
      
    $this->adicionarAtributo(InfraDTO::$PREFIXO_NUM, 'QtdObjeto');

    $this->adicionarAtributo(InfraDTO::$PREFIXO_ARR, 'MdCorExpedicaoSolicitadaDTO');

    $this->configurarPK('IdMdPlp', InfraDTO::$TIPO_PK_NATIVA);

  }

  public function getStrNomeStaPlp()
  {
    switch ($this->getStrStaPlp()) {
      case MdCorPlpRN::$STA_GERADA:
        return 'PLP Gerada';
      case MdCorPlpRN::$STA_PENDENTE:
        return 'Possui Objeto Pendente de Entrega';
      case MdCorPlpRN::$STA_ENTREGUES:
        return 'Todos os Objetos Entregues';
      case MdCorPlpRN::$STA_RETORNO_AR_PENDENTE:
        return 'Objetos com Retorno de AR Pendente';
      case MdCorPlpRN::$STA_FINALIZADA:
        return 'Finalizada';
    }
  }
}
