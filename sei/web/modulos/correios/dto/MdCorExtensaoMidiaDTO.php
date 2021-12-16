<?
/**
 * TRIBUNAL REGIONAL FEDERAL DA 4ª REGIÃO
 *
 * 20/12/2016 - criado por Wilton Júnior
 *
 * Versão do Gerador de Código: 1.39.0
 */

require_once dirname(__FILE__) . '/../../../SEI.php';

class MdCorExtensaoMidiaDTO extends InfraDTO
{

    public function getStrNomeTabela()
    {
        return 'md_cor_extensao_midia';
    }

    public function montar()
    {

        $this->adicionarAtributoTabela(InfraDTO::$PREFIXO_NUM,
            'IdMdCorExtensaoMidia',
            'id_md_cor_extensao_midia');

        $this->adicionarAtributoTabela(InfraDTO::$PREFIXO_NUM,
            'IdArquivoExtensao',
            'id_arquivo_extensao');

        $this->adicionarAtributoTabela(InfraDTO::$PREFIXO_STR,
            'SinAtivo',
            'sin_ativo');

        $this->adicionarAtributoTabelaRelacionada(InfraDTO::$PREFIXO_STR,
                                                  'NomeExtensao',
                                                  'extensao',
                                                  'arquivo_extensao');


        $this->configurarPK('IdMdCorExtensaoMidia', InfraDTO::$TIPO_PK_NATIVA);
        $this->configurarFK('IdArquivoExtensao', 'arquivo_extensao', 'id_arquivo_extensao');

    }
}

?>