<?php
/**
 * ANATEL
 *
 * 09/12/2016 - criado por marcelo.emiliano@cast.com.br - CAST
 */

require_once dirname(__FILE__) . '/../../../SEI.php';

class MdCorUnidadeExpDTO extends InfraDTO
{

    public function getStrNomeTabela()
    {
        return 'md_cor_unidade_exp';
    }

    public function montar()
    {

        $this->adicionarAtributoTabela(InfraDTO::$PREFIXO_NUM,
            'IdUnidade',
            'id_unidade');

        $this->adicionarAtributoTabela(InfraDTO::$PREFIXO_STR,
            'SinAtivo',
            'sin_ativo');

        $this->configurarFK('IdUnidade', 'unidade u', 'u.id_unidade');

        $this->adicionarAtributoTabelaRelacionada(InfraDTO::$PREFIXO_STR, 'SiglaUnidade', 'u.sigla', 'unidade u');
        $this->adicionarAtributoTabelaRelacionada(InfraDTO::$PREFIXO_STR, 'DescricaoUnidade', 'u.descricao', 'unidade u');

        $this->configurarPK('IdUnidade', InfraDTO::$TIPO_PK_INFORMADO);

        $this->configurarExclusaoLogica('SinAtivo', 'N');
    }
}