<?php
/**
 * ANATEL
 *
 * 22/12/2016 - criado por marcelo.emiliano@cast.com.br - CAST
 */

require_once dirname(__FILE__) . '/../../../SEI.php';

class MdCorMapeamentoUniExpSolDTO extends InfraDTO
{

    public function getStrNomeTabela()
    {
        return 'md_cor_map_unidade_exp';
    }

    public function montar()
    {
        $this->adicionarAtributoTabela(InfraDTO::$PREFIXO_NUM,
            'IdUnidadeExp',
            'id_unidade_exp');

        $this->adicionarAtributoTabela(InfraDTO::$PREFIXO_NUM,
            'IdUnidadeSolicitante',
            'id_unidade_solicitante');

        $this->adicionarAtributoTabela(InfraDTO::$PREFIXO_STR,
            'SinAtivo',
            'sin_ativo');

        $this->configurarPK('IdUnidadeExp', InfraDTO::$TIPO_PK_INFORMADO);
        $this->configurarPK('IdUnidadeSolicitante', InfraDTO::$TIPO_PK_INFORMADO);

        $this->configurarFK('IdUnidadeExp', 'md_cor_unidade_exp ue', 'ue.id_unidade');
        $this->configurarFK('IdUnidadeExp', 'unidade uu', 'uu.id_unidade');

        //Sigla Unidade Expedidora
        $this->adicionarAtributoTabelaRelacionada(InfraDTO::$PREFIXO_STR, 'SiglaUnidadeExpedidora', 'uu.sigla', 'unidade uu');
        //Descricao Unidade Expedidora
        $this->adicionarAtributoTabelaRelacionada(InfraDTO::$PREFIXO_STR, 'DescricaoUnidadeExpedidora', 'uu.descricao', 'unidade uu');

        $this->configurarFK('IdUnidadeSolicitante', 'unidade u', 'u.id_unidade');
        //Sigla Unidade
        $this->adicionarAtributoTabelaRelacionada(InfraDTO::$PREFIXO_STR, 'SiglaUnidadeSolicitante', 'u.sigla', 'unidade u');
        //Descricao Unidade
        $this->adicionarAtributoTabelaRelacionada(InfraDTO::$PREFIXO_STR, 'DescricaoUnidadeSolicitante', 'u.descricao', 'unidade u');

        $this->adicionarAtributo(InfraDTO::$PREFIXO_BOL,'Multiplos');
        $this->adicionarAtributo(InfraDTO::$PREFIXO_STR, 'PalavrasPesquisa');

    }
}