<?

require_once dirname(__FILE__).'/../../../SEI.php';

class MdCorAdmIntegracaoDTO extends InfraDTO {

	public function getStrNomeTabela() {
		return 'md_cor_adm_integracao';
	}

	public function montar() {

		$this->adicionarAtributoTabela(InfraDTO::$PREFIXO_NUM, 'IdMdCorAdmIntegracao', 'id_md_cor_adm_integracao');

		$this->adicionarAtributoTabela(InfraDTO::$PREFIXO_NUM, 'Funcionalidade', 'funcionalidade');

		$this->adicionarAtributoTabela(InfraDTO::$PREFIXO_STR, 'Nome', 'nome');

		$this->adicionarAtributoTabela(InfraDTO::$PREFIXO_STR, 'UrlOperacao', 'url_operacao');

		$this->adicionarAtributoTabela(InfraDTO::$PREFIXO_STR, 'Usuario', 'usuario');

		$this->adicionarAtributoTabela(InfraDTO::$PREFIXO_STR, 'Senha', 'senha');

		$this->adicionarAtributoTabela(InfraDTO::$PREFIXO_STR, 'Token', 'token');

		$this->adicionarAtributoTabela(InfraDTO::$PREFIXO_DTH, 'DataExpiraToken', 'data_exp_token');

		$this->adicionarAtributoTabela(InfraDTO::$PREFIXO_STR, 'SinAtivo', 'sin_ativo');

		$this->configurarPK('IdMdCorAdmIntegracao',InfraDTO::$TIPO_PK_NATIVA);
	}
}
