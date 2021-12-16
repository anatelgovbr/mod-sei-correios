<?
/**
 * TRIBUNAL REGIONAL FEDERAL DA 4ª REGIÃO
 *
 * 07/06/2017 - criado por marcelo.cast
 *
 * Versão do Gerador de Código: 1.40.1
 */

require_once dirname(__FILE__) . '/../../../SEI.php';

class MdCorContatoDTO extends InfraDTO
{

    public function getStrNomeTabela()
    {
        return 'md_cor_contato';
    }

    public function montar()
    {

        $this->adicionarAtributoTabela(InfraDTO::$PREFIXO_NUM,
            'IdMdCorContato',
            'id_md_cor_contato');

        $this->adicionarAtributoTabela(InfraDTO::$PREFIXO_NUM,
            'IdMdCorExpedicaoSolicitada',
            'id_md_cor_expedicao_solicitada');

        $this->adicionarAtributoTabela(InfraDTO::$PREFIXO_NUM,
            'IdContato',
            'id_contato');

        $this->adicionarAtributoTabela(InfraDTO::$PREFIXO_STR,
            'Nome',
            'nome');

        $this->adicionarAtributoTabela(InfraDTO::$PREFIXO_STR,
            'Endereco',
            'endereco');

        $this->adicionarAtributoTabela(InfraDTO::$PREFIXO_STR,
            'Complemento',
            'complemento');

        $this->adicionarAtributoTabela(InfraDTO::$PREFIXO_STR,
            'Bairro',
            'bairro');

        $this->adicionarAtributoTabela(InfraDTO::$PREFIXO_STR,
            'Cep',
            'cep');

        $this->adicionarAtributoTabela(InfraDTO::$PREFIXO_STR,
            'SinAtivo',
            'sin_ativo');

        $this->adicionarAtributoTabela(InfraDTO::$PREFIXO_STR,
            'StaNatureza',
            'sta_natureza');

        $this->adicionarAtributoTabela(InfraDTO::$PREFIXO_STR,
            'SinEnderecoAssociado',
            'sin_endereco_associado');

        $this->adicionarAtributoTabela(InfraDTO::$PREFIXO_STR,
            'NomeCidade',
            'nome_cidade');

        $this->adicionarAtributoTabela(InfraDTO::$PREFIXO_STR,
            'SiglaUf',
            'sigla_uf');

        $this->adicionarAtributoTabela(InfraDTO::$PREFIXO_NUM,
            'IdContatoAssociado',
            'id_contato_associado');

        $this->adicionarAtributoTabela(InfraDTO::$PREFIXO_STR,
            'StaGenero',
            'sta_genero');

        $this->adicionarAtributoTabela(InfraDTO::$PREFIXO_NUM,
            'IdTipoContato',
            'id_tipo_contato');


        $this->adicionarAtributoTabela(InfraDTO::$PREFIXO_STR,
            'NomeContatoAssociado',
            'nome_contato_associado');

        $this->adicionarAtributoTabela(InfraDTO::$PREFIXO_STR,
            'StaNaturezaContatoAssociado',
            'sta_natureza_contato_associado');

        $this->adicionarAtributoTabela(InfraDTO::$PREFIXO_NUM,
            'IdTipoContatoAssociado',
            'id_tipo_contato_associado');

        $this->adicionarAtributoTabela(InfraDTO::$PREFIXO_STR,
            'ExpressaoTratamentoCargo',
            'tratamento_expressao');

        $this->adicionarAtributoTabela(InfraDTO::$PREFIXO_STR,
            'ExpressaoCargo',
            'cargo_expressao');
        $this->adicionarAtributoTabelaRelacionada(InfraDTO::$PREFIXO_STR,
            'NomeTipoContato',
            'tcca.nome',
            'tipo_contato tcca');

        $this->adicionarAtributoTabelaRelacionada(InfraDTO::$PREFIXO_NUM,
            'IdCidadeContatoAssociado',
            'a.id_cidade',
            'contato a');

        $this->adicionarAtributoTabelaRelacionada(InfraDTO::$PREFIXO_STR,
            'NomeCidadeContatoAssociado',
            'c2.nome',
            'cidade c2');

        $this->adicionarAtributoTabelaRelacionada(InfraDTO::$PREFIXO_NUM,
            'IdUfContatoAssociado',
            'a.id_uf',
            'contato a');

        $this->adicionarAtributoTabelaRelacionada(InfraDTO::$PREFIXO_STR,
            'SiglaUfContatoAssociado',
            'u2.sigla',
            'uf u2');

        $this->configurarPK('IdMdCorContato', InfraDTO::$TIPO_PK_NATIVA);
        $this->configurarFK('IdTipoContato', 'tipo_contato tcca', 'tcca.id_tipo_contato');
        $this->configurarFK('IdContatoAssociado', 'contato a', 'a.id_contato');
        $this->configurarFK('IdCidadeContatoAssociado', 'cidade c2', 'c2.id_cidade', InfraDTO::$TIPO_FK_OPCIONAL);
        $this->configurarFK('IdUfContatoAssociado', 'uf u2', 'u2.id_uf', InfraDTO::$TIPO_FK_OPCIONAL);

    }
}

?>