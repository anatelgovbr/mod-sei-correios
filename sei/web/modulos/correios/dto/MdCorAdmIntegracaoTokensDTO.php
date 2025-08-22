<?

require_once dirname(__FILE__).'/../../../SEI.php';

class MdCorAdmIntegracaoTokensDTO extends InfraDTO {

	public function getStrNomeTabela() {
		return 'md_cor_adm_integr_tokens';
	}

	public function montar() {

		$this->adicionarAtributoTabela(InfraDTO::$PREFIXO_NUM, 'IdMdCorAdmIntegracaoTokens', 'id_md_cor_adm_integr_tokens');

		$this->adicionarAtributoTabela(InfraDTO::$PREFIXO_NUM, 'IdMdCorAdmIntegracao', 'id_md_cor_adm_integracao');

		$this->adicionarAtributoTabela(InfraDTO::$PREFIXO_NUM, 'IdMdCorContrato', 'id_md_cor_contrato');

		$this->adicionarAtributoTabela(InfraDTO::$PREFIXO_STR, 'Usuario', 'usuario');

		$this->adicionarAtributoTabela(InfraDTO::$PREFIXO_STR, 'Senha', 'senha');

		$this->adicionarAtributoTabela(InfraDTO::$PREFIXO_STR, 'Token', 'token');

		$this->adicionarAtributoTabela(InfraDTO::$PREFIXO_DTH, 'DataExpiraToken', 'data_exp_token');

		$this->adicionarAtributoTabela(InfraDTO::$PREFIXO_STR, 'SinAtivo', 'sin_ativo');
		
		$this->configurarPK('IdMdCorAdmIntegracaoTokens',InfraDTO::$TIPO_PK_NATIVA);

		$this->adicionarAtributoTabelaRelacionada(InfraDTO::$PREFIXO_STR, 'NomeMdCorAdmIntegracao', 'nome', 'md_cor_adm_integracao');

		$this->adicionarAtributoTabelaRelacionada(InfraDTO::$PREFIXO_STR, 'UrlOperacao', 'url_operacao', 'md_cor_adm_integracao');

		$this->adicionarAtributoTabelaRelacionada(InfraDTO::$PREFIXO_NUM, 'Funcionalidade', 'funcionalidade', 'md_cor_adm_integracao');

		$this->adicionarAtributoTabelaRelacionada(InfraDTO::$PREFIXO_STR, 'SinAtivoMdCorAdmIntegracao', 'sin_ativo', 'md_cor_adm_integracao');

		$this->adicionarAtributoTabelaRelacionada(InfraDTO::$PREFIXO_STR, 'NumeroContrato', 'numero_contrato', 'md_cor_contrato');

		$this->configurarFK('IdMdCorAdmIntegracao', 'md_cor_adm_integracao', 'id_md_cor_adm_integracao');

		$this->configurarFK('IdMdCorContrato', 'md_cor_contrato', 'id_md_cor_contrato');
	}
}
