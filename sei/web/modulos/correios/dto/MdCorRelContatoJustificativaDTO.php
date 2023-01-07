<?

require_once dirname(__FILE__) . '/../../../SEI.php';

class MdCorRelContatoJustificativaDTO extends InfraDTO
{

    public function getStrNomeTabela()
    {
        return 'rel_contato_justificativa';
    }

    public function montar()
    {
        $this->adicionarAtributoTabela(InfraDTO::$PREFIXO_NUM, 'IdRelContatoJustificativa', 'id_rel_contato_justificativa');
        $this->adicionarAtributoTabela(InfraDTO::$PREFIXO_NUM, 'IdContato', 'id_contato');
        $this->adicionarAtributoTabela(InfraDTO::$PREFIXO_NUM, 'IdMdCorJustificativa', 'id_md_cor_justificativa');

        #TABELA RELACIONAMENTO - CONTATO
        $this->configurarFK('IdContato', 'contato c', 'c.id_contato');
        $this->adicionarAtributoTabelaRelacionada(InfraDTO::$PREFIXO_STR, 'NomeContato', 'c.nome', 'contato c');
        $this->adicionarAtributoTabelaRelacionada(InfraDTO::$PREFIXO_STR, 'StaNatureza', 'c.sta_natureza', 'contato c');
        $this->adicionarAtributoTabelaRelacionada(InfraDTO::$PREFIXO_NUM, 'Cpf', 'c.cpf', 'contato c');
        $this->adicionarAtributoTabelaRelacionada(InfraDTO::$PREFIXO_NUM, 'Cnpj', 'c.cnpj', 'contato c');

        #TABELA RELACIONAMENTO - MD_COR_JUSTIFICATIVA
        $this->configurarFK('IdMdCorJustificativa', 'md_cor_justificativa j', 'j.id_md_cor_justificativa');
        $this->adicionarAtributoTabelaRelacionada(InfraDTO::$PREFIXO_STR, 'NomeJustificativa', 'j.nome', 'md_cor_justificativa j');
        $this->adicionarAtributoTabelaRelacionada(InfraDTO::$PREFIXO_STR, 'SinAtivo', 'j.sin_ativo', 'md_cor_justificativa j');

        $this->configurarPK('IdRelContatoJustificativa', InfraDTO::$TIPO_PK_NATIVA);
    }
}

?>
