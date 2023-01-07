<?
/**
* TRIBUNAL REGIONAL FEDERAL DA 4ª REGIÃO
*
* 26/11/2018 - Criado por felipelino.cast <felipe.lino@castgroup.com.br>
*
* Versão do Gerador de Código: 1.40.1
*/

require_once dirname(__FILE__) . '/../../../SEI.php';

class MdCorSubStatusProcessDTO extends InfraDTO {

  public function getStrNomeTabela() {
  	 return 'md_cor_substatus_process';
  }

  public function montar() {

    $this->adicionarAtributoTabela(InfraDTO::$PREFIXO_NUM, 'IdMdCorSubStatusProcess', 'id_md_cor_substatus_process');
    
    $this->adicionarAtributoTabela(InfraDTO::$PREFIXO_NUM, 'IdMdCorStatusProcess', 'id_md_cor_status_process');

    $this->adicionarAtributoTabela(InfraDTO::$PREFIXO_STR, 'Descricao', 'descricao');

    $this->configurarPK('IdMdCorSubStatusProcess', InfraDTO::$TIPO_PK_INFORMADO);
    $this->configurarFK('IdMdCorStatusProcess', 'md_cor_status_process staproc', 'staproc.id_md_cor_status_process');
  }
}
?>