<?

require_once dirname(__FILE__) . '/../../../SEI.php';

class MdCorJustificativaDTO extends InfraDTO
{

    public function getStrNomeTabela()
    {
        return 'md_cor_justificativa';
    }

    public function montar()
    {
        $this->adicionarAtributoTabela(InfraDTO::$PREFIXO_NUM, 'IdMdCorJustificativa', 'id_md_cor_justificativa');
        $this->adicionarAtributoTabela(InfraDTO::$PREFIXO_STR, 'Nome', 'nome');
        $this->adicionarAtributoTabela(InfraDTO::$PREFIXO_STR, 'SinAtivo', 'sin_ativo');

        $this->configurarPK('IdMdCorJustificativa', InfraDTO::$TIPO_PK_NATIVA);
    }
}

?>
