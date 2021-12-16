<?
/**
 * TRIBUNAL REGIONAL FEDERAL DA 4ª REGIÃO
 *
 * 14/06/2017 - criado por jaqueline.cast
 *
 * Versão do Gerador de Código: 1.40.1
 */

require_once dirname(__FILE__) . '/../../../SEI.php';

class MdCorListaStatusDTO extends InfraDTO
{

    public function getStrNomeTabela()
    {
        return 'md_cor_lista_status';
    }

    public function montar()
    {

        $this->adicionarAtributoTabela(InfraDTO::$PREFIXO_NUM,'IdMdCorListaStatus','id_md_cor_lista_status');
        $this->adicionarAtributoTabela(InfraDTO::$PREFIXO_NUM, 'Status', 'status');
        $this->adicionarAtributoTabela(InfraDTO::$PREFIXO_STR, 'Tipo', 'tipo');
        $this->adicionarAtributoTabela(InfraDTO::$PREFIXO_STR, 'NomeImagem', 'nome_imagem');
        $this->adicionarAtributoTabela(InfraDTO::$PREFIXO_STR, 'Descricao', 'descricao');
        $this->adicionarAtributoTabela(InfraDTO::$PREFIXO_STR, 'SinAtivo', 'sin_ativo');

        $this->configurarPK('IdMdCorListaStatus', InfraDTO::$TIPO_PK_NATIVA);

    }
}

?>
