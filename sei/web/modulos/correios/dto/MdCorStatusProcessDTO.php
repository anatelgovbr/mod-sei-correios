<?
/**
* TRIBUNAL REGIONAL FEDERAL DA 4� REGI�O
*
* 26/11/2018 - Criado por felipelino.cast <felipe.lino@castgroup.com.br>
*
* Vers�o do Gerador de C�digo: 1.40.1
*/

require_once dirname(__FILE__) . '/../../../SEI.php';

class MdCorStatusProcessDTO extends InfraDTO {

  public function getStrNomeTabela() {
  	 return 'md_cor_status_process';
  }

  public function montar() {

    $this->adicionarAtributoTabela(InfraDTO::$PREFIXO_NUM, 'IdMdCorStatusProcess', 'id_md_cor_status_process');

    $this->adicionarAtributoTabela(InfraDTO::$PREFIXO_STR, 'Descricao', 'descricao');

    $this->configurarPK('IdMdCorStatusProcess', InfraDTO::$TIPO_PK_INFORMADO);
  }
}
?>