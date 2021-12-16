<?
/**
 * TRIBUNAL REGIONAL FEDERAL DA 4ª REGIÃO
 *
 * 22/12/2016 - criado por Wilton Júnior
 *
 * Versão do Gerador de Código: 1.39.0
 */

require_once dirname(__FILE__) . '/../../../SEI.php';

class MdCorContratoDTO extends InfraDTO
{

    public function getStrNomeTabela()
    {
        return 'md_cor_contrato';
    }

    public function montar()
    {

        $this->adicionarAtributoTabela(InfraDTO::$PREFIXO_NUM,
            'IdMdCorContrato',
            'id_md_cor_contrato');

        $this->adicionarAtributoTabela(InfraDTO::$PREFIXO_STR,
            'NumeroContrato',
            'numero_contrato');

        $this->adicionarAtributoTabela(InfraDTO::$PREFIXO_STR,
            'NumeroContratoCorreio',
            'numero_contrato_correio');

        $this->adicionarAtributoTabela(InfraDTO::$PREFIXO_STR,
            'NumeroCartaoPostagem',
            'numero_cartao_postagem');

        $this->adicionarAtributoTabela(InfraDTO::$PREFIXO_NUM,
            'NumeroCnpj',
            'numero_cnpj');

        $this->adicionarAtributoTabela(InfraDTO::$PREFIXO_NUM,
            'AnoContratoCorreio',
            'numero_ano_contrato');

        $this->adicionarAtributoTabela(InfraDTO::$PREFIXO_STR,
            'UrlWebservice',
            'url_webservice');

        $this->adicionarAtributoTabela(InfraDTO::$PREFIXO_DBL,
            'IdProcedimento',
            'id_procedimento');

        $this->adicionarAtributoTabela(InfraDTO::$PREFIXO_STR,
            'Usuario',
            'usuario');

        $this->adicionarAtributoTabela(InfraDTO::$PREFIXO_STR,
            'Senha',
            'senha');

        $this->adicionarAtributoTabela(InfraDTO::$PREFIXO_STR,
            'CodigoAdministrativo',
            'codigo_administrativo');

        $this->adicionarAtributoTabela(InfraDTO::$PREFIXO_STR,
            'SinAtivo',
            'sin_ativo');

        $this->adicionarAtributoTabela(InfraDTO::$PREFIXO_NUM,
            'IdMdCorDiretoria',
            'id_md_cor_diretoria');

        $this->configurarPK('IdMdCorContrato', InfraDTO::$TIPO_PK_NATIVA);

    }
}

?>