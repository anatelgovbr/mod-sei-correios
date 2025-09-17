<?
/**
 * TRIBUNAL REGIONAL FEDERAL DA 4ª REGIÃO
 *
 * 23/11/2022 - criado por gustavos.colab
 *
 * Versão do Gerador de Código: 1.43.1
 */

require_once dirname(__FILE__).'/../../../SEI.php';

class MdCorAdmIntegracaoRN extends InfraRN {

	public static $GERAR_TOKEN     = '1';
	public static $STR_GERAR_TOKEN = 'Correios::Gerar Token';

	public static $RASTREAR        = '2';
	public static $STR_RASTREAR    = 'Correios::Rastrear Objeto';

	public static $CEP             = '3';
	public static $STR_CEP         = 'Correios::Consultar CEP';

	public static $SERV_POSTAL     = '4';
	public static $STR_SERV_POSTAL = 'Correios::Serviços Postais';

	public static $GERAR_ETIQUETAS     = '5';
	public static $STR_GERAR_ETIQUETAS = 'Correios::Solicitar Etiquetas';

	public static $GERAR_PRE_POSTAGEM  = '6';
	public static $STR_PRE_POSTAGEM    = 'Correios::Pré Postagem Nacional';

	public static $EMITIR_ROTULO     = '7';
	public static $STR_EMITIR_ROTULO = 'Correios::Emitir Rótulo';

	public static $DOWN_ROTULO     = '8';
	public static $STR_DOWN_ROTULO = 'Correios::Download Rótulo';

    public static $AVISO_RECEB     = '9';
    public static $STR_AVISO_RECEB = 'Correios::Aviso Recebimento';

    public static $CANCELAR_PRE_POSTAGEM = '10';
    public static $STR_CANCELAR_PRE_POSTAGEM = 'Correios::Cancelar Pré Postagem';

	public static $STR_POST        = 'POST';
	public static $STR_GET         = 'GET';
    public static $STR_DEL         = 'DELETE';

	public static $INFO_RESTRITO = '*****';

	public function __construct(){
		parent::__construct();
	}

	protected function inicializarObjInfraIBanco(){
		return BancoSEI::getInstance();
	}

	private function validarStrNome(MdCorAdmIntegracaoDTO $objMdCorAdmIntegracaoDTO, InfraException $objInfraException){
		if (InfraString::isBolVazia($objMdCorAdmIntegracaoDTO->getStrNome())){
			$objInfraException->adicionarValidacao('Nome não informado.');
		}else{
			$objMdCorAdmIntegracaoDTO->setStrNome(trim($objMdCorAdmIntegracaoDTO->getStrNome()));

			if (strlen($objMdCorAdmIntegracaoDTO->getStrNome()) > 200){
				$objInfraException->adicionarValidacao('Nome possui tamanho superior a 200 caracteres.');
			}
		}
	}

	private function validarNumFuncionalidade(MdCorAdmIntegracaoDTO $objMdCorAdmIntegracaoDTO, InfraException $objInfraException){
		if (InfraString::isBolVazia($objMdCorAdmIntegracaoDTO->getNumFuncionalidade())){
			$objInfraException->adicionarValidacao('Funcionalidade não informada.');
		}
	}

	private function validarStrSinAtivo(MdCorAdmIntegracaoDTO $objMdCorAdmIntegracaoDTO, InfraException $objInfraException){
		if (InfraString::isBolVazia($objMdCorAdmIntegracaoDTO->getStrSinAtivo())){
			$objInfraException->adicionarValidacao('Sinalizador de Exclusão Lógica não informado.');
		}else{
			if (!InfraUtil::isBolSinalizadorValido($objMdCorAdmIntegracaoDTO->getStrSinAtivo())){
				$objInfraException->adicionarValidacao('Sinalizador de Exclusão Lógica inválido.');
			}
		}
	}

	protected function cadastrarControlado(MdCorAdmIntegracaoDTO $objMdCorAdmIntegracaoDTO) {
		try{
			SessaoSEI::getInstance()->validarAuditarPermissao('md_cor_adm_integracao_cadastrar', __METHOD__, $objMdCorAdmIntegracaoDTO);

			$objInfraException = new InfraException();

			$this->validarStrNome($objMdCorAdmIntegracaoDTO, $objInfraException);
			$this->validarNumFuncionalidade($objMdCorAdmIntegracaoDTO, $objInfraException);
			$objInfraException->lancarValidacoes();

			$objMdCorAdmIntegracaoBD = new MdCorAdmIntegracaoBD( $this->getObjInfraIBanco() );
			$ret = $objMdCorAdmIntegracaoBD->cadastrar( $objMdCorAdmIntegracaoDTO );

			return $ret;

		}catch(Exception $e){
			throw new InfraException('Erro cadastrando Integração.',$e);
		}
	}

	protected function alterarControlado(MdCorAdmIntegracaoDTO $objMdCorAdmIntegracaoDTO){
		try {

			SessaoSEI::getInstance()->validarAuditarPermissao('md_cor_adm_integracao_alterar', __METHOD__, $objMdCorAdmIntegracaoDTO);
			
			$objInfraException = new InfraException();

			// Processa os tokens
			if ($objMdCorAdmIntegracaoDTO->getNumFuncionalidade() == self::$GERAR_TOKEN && isset($_POST['hdnTbTokens'])) {
				$this->processaCadastroAlteracaoTokens($objMdCorAdmIntegracaoDTO->getNumIdMdCorAdmIntegracao());
			}
			
			$this->validarStrNome($objMdCorAdmIntegracaoDTO, $objInfraException);
			$this->validarNumFuncionalidade($objMdCorAdmIntegracaoDTO, $objInfraException);
			$objInfraException->lancarValidacoes();

			$objMdCorAdmIntegracaoBD = new MdCorAdmIntegracaoBD( $this->getObjInfraIBanco() );
			$objMdCorAdmIntegracaoBD->alterar( $objMdCorAdmIntegracaoDTO );

		}catch(Exception $e){
			throw new InfraException('Erro alterando Integração.',$e);
		}
	}

	protected function processaCadastroAlteracaoTokens($idMdCorAdmIntegracao) {
		$arrTbTokens = PaginaSEI::getInstance()->getArrItensTabelaDinamica( $_POST['hdnTbTokens'] );
		if ( !empty( $arrTbTokens ) ) {
			$objMdCorAdmIntegracaoTokensRN  = new MdCorAdmIntegracaoTokensRN();

			if ( !empty($_POST['hdnIdsItensHeader']) ) {
				$arrIdsOrigemIntegTokens = explode( ',' , $_POST['hdnIdsItensHeader'] );
			}

			foreach ( $arrTbTokens as $k => $v ) {
				$objMdCorAdmIntegTokensDTO = new MdCorAdmIntegracaoTokensDTO();
				$objMdCorAdmIntegTokensDTO->setNumIdMdCorAdmIntegracao($idMdCorAdmIntegracao);
				$objMdCorAdmIntegTokensDTO->setNumIdMdCorContrato($v[1]);
				$objMdCorAdmIntegTokensDTO->setStrUsuario($v[3]);
				$objMdCorAdmIntegTokensDTO->setStrToken($v[5]);
				$objMdCorAdmIntegTokensDTO->setStrSinAtivo('S');

				$arrId = explode( '_' , $v[0] );
				if ( $arrId[0] == 'novo' ) {
					$objMdCorAdmIntegTokensDTO->setStrSenha($v[4] ? MdCorAdmIntegracaoINT::gerenciaDadosRestritos($v[4],'C') : null);
					$objMdCorAdmIntegracaoTokensRN->cadastrar( $objMdCorAdmIntegTokensDTO );
				} else {
					$objMdCorAdmIntegTokensDTO->setNumIdMdCorAdmIntegracaoTokens($arrId[0]);
					$objMdCorAdmIntegracaoTokensRN->alterar( $objMdCorAdmIntegTokensDTO );

					// remove do array o Id Integracao Header atualizado
					$chave = array_search( $v[0] , $arrIdsOrigemIntegTokens );
					if ( $chave !== false ) unset( $arrIdsOrigemIntegTokens[$chave] );
				}
			}

			// se conter ids no array, eh porque foi removido em memoria da tela, com isso, deve ser removido da tabela
			if ( !empty( $arrIdsOrigemIntegTokens ) ){
				foreach ( $arrIdsOrigemIntegTokens as $idIntegToken ) {
					$objMdCorAdmIntegracaoTokensDTO = new MdCorAdmIntegracaoTokensDTO();
					$objMdCorAdmIntegracaoTokensDTO->setNumIdMdCorAdmIntegracaoTokens( $idIntegToken );
					$objMdCorAdmIntegracaoTokensDTO->retTodos();

					$arrObjMdUtlAdmIntegHeader[] = $objMdCorAdmIntegracaoTokensRN->consultar( $objMdCorAdmIntegracaoTokensDTO );
				}

				$objMdCorAdmIntegracaoTokensRN->excluir( $arrObjMdUtlAdmIntegHeader );
			}
		}
	}

	protected function excluirControlado($arrObjMdCorAdmIntegracaoDTO){
		try {

			SessaoSEI::getInstance()->validarAuditarPermissao('md_cor_adm_integracao_excluir', __METHOD__, $arrObjMdCorAdmIntegracaoDTO);

			//Regras de Negocio
			//$objInfraException = new InfraException();

			//$objInfraException->lancarValidacoes();

			$objMdCorAdmIntegracaoBD = new MdCorAdmIntegracaoBD($this->getObjInfraIBanco());
			for($i=0;$i<count($arrObjMdCorAdmIntegracaoDTO);$i++){
				$objMdCorAdmIntegracaoBD->excluir($arrObjMdCorAdmIntegracaoDTO[$i]);
			}

		}catch(Exception $e){
			throw new InfraException('Erro excluindo Integração.',$e);
		}
	}

	protected function consultarConectado(MdCorAdmIntegracaoDTO $objMdCorAdmIntegracaoDTO){
		try {

			SessaoSEI::getInstance()->validarAuditarPermissao('md_cor_adm_integracao_consultar', __METHOD__, $objMdCorAdmIntegracaoDTO);

			//Regras de Negocio
			//$objInfraException = new InfraException();

			//$objInfraException->lancarValidacoes();
			$objMdCorAdmIntegracaoBD = new MdCorAdmIntegracaoBD($this->getObjInfraIBanco());
			$ret = $objMdCorAdmIntegracaoBD->consultar($objMdCorAdmIntegracaoDTO);

			return $ret;
		}catch(Exception $e){
			throw new InfraException('Erro consultando Integração.',$e);
		}
	}

	protected function listarConectado(MdCorAdmIntegracaoDTO $objMdCorAdmIntegracaoDTO) {
		try {

			SessaoSEI::getInstance()->validarAuditarPermissao('md_cor_adm_integracao_listar', __METHOD__, $objMdCorAdmIntegracaoDTO);

			//Regras de Negocio
			//$objInfraException = new InfraException();

			//$objInfraException->lancarValidacoes();

			$objMdCorAdmIntegracaoBD = new MdCorAdmIntegracaoBD($this->getObjInfraIBanco());
			$ret = $objMdCorAdmIntegracaoBD->listar($objMdCorAdmIntegracaoDTO);

			return $ret;

		}catch(Exception $e){
			throw new InfraException('Erro listando Integrações.',$e);
		}
	}

	protected function contarConectado(MdCorAdmIntegracaoDTO $objMdCorAdmIntegracaoDTO){
		try {

			SessaoSEI::getInstance()->validarAuditarPermissao('md_cor_adm_integracao_listar', __METHOD__, $objMdCorAdmIntegracaoDTO);

			//Regras de Negocio
			//$objInfraException = new InfraException();

			//$objInfraException->lancarValidacoes();

			$objMdCorAdmIntegracaoBD = new MdCorAdmIntegracaoBD($this->getObjInfraIBanco());
			$ret = $objMdCorAdmIntegracaoBD->contar($objMdCorAdmIntegracaoDTO);

			return $ret;
		}catch(Exception $e){
			throw new InfraException('Erro contando Integrações.',$e);
		}
	}

	protected function desativarControlado($arrObjMdCorAdmIntegracaoDTO){
		try {

			SessaoSEI::getInstance()->validarAuditarPermissao('md_cor_adm_integracao_desativar', __METHOD__, $arrObjMdCorAdmIntegracaoDTO);

			//Regras de Negocio
			//$objInfraException = new InfraException();

			//$objInfraException->lancarValidacoes();

			$objMdCorAdmIntegracaoBD = new MdCorAdmIntegracaoBD($this->getObjInfraIBanco());
			for($i=0;$i<count($arrObjMdCorAdmIntegracaoDTO);$i++){
				$objMdCorAdmIntegracaoBD->desativar($arrObjMdCorAdmIntegracaoDTO[$i]);
			}

		}catch(Exception $e){
			throw new InfraException('Erro desativando Integração.',$e);
		}
	}

	protected function reativarControlado($arrObjMdCorAdmIntegracaoDTO){
		try {

			SessaoSEI::getInstance()->validarAuditarPermissao('md_cor_adm_integracao_reativar', __METHOD__, $arrObjMdCorAdmIntegracaoDTO);

			//Regras de Negocio
			//$objInfraException = new InfraException();

			//$objInfraException->lancarValidacoes();

			$objMdCorAdmIntegracaoBD = new MdCorAdmIntegracaoBD($this->getObjInfraIBanco());
			for($i=0;$i<count($arrObjMdCorAdmIntegracaoDTO);$i++){
				$objMdCorAdmIntegracaoBD->reativar($arrObjMdCorAdmIntegracaoDTO[$i]);
			}

		}catch(Exception $e){
			throw new InfraException('Erro reativando Integração.',$e);
		}
	}

	protected function bloquearControlado(MdCorAdmIntegracaoDTO $objMdCorAdmIntegracaoDTO){
		try {

			SessaoSEI::getInstance()->validarAuditarPermissao('md_utl_adm_integracao_consultar', __METHOD__, $objMdCorAdmIntegracaoDTO);

			//Regras de Negocio
			//$objInfraException = new InfraException();

			//$objInfraException->lancarValidacoes();

			$objMdCorAdmIntegracaoBD = new MdCorAdmIntegracaoBD($this->getObjInfraIBanco());
			$ret = $objMdCorAdmIntegracaoBD->bloquear($objMdCorAdmIntegracaoDTO);

			return $ret;
		}catch(Exception $e){
			throw new InfraException('Erro bloqueando Integração.',$e);
		}
	}

	/*
	 * Consulta somente na Integracao
	 * */
	public function buscaIntegracaoPorFuncionalidade( $tpFuncionalidade, $contratoId ){

	    if ( empty($tpFuncionalidade) || empty($contratoId) ) return ['suc' => false , 'msg' => 'O Tipo de Funcionalidade da Integração e Contrato estão vazios ou nulos.'];

		//$objMdCorAdmIntegracaoDTO = new MdCorAdmIntegracaoDTO();
		$objMdCorAdmIntegracaoTokensDTO = new MdCorAdmIntegracaoTokensDTO();
		$objMdCorAdmIntegracaoTokensRN = new MdCorAdmIntegracaoTokensRN();

		$objMdCorAdmIntegracaoTokensDTO->setStrSinAtivoMdCorAdmIntegracao( 'S' );
		$objMdCorAdmIntegracaoTokensDTO->setNumFuncionalidade( $tpFuncionalidade );
		$objMdCorAdmIntegracaoTokensDTO->setNumIdMdCorContrato($contratoId);
		$objMdCorAdmIntegracaoTokensDTO->retStrUrlOperacao();
		$objMdCorAdmIntegracaoTokensDTO->retNumIdMdCorContrato();
		$objMdCorAdmIntegracaoTokensDTO->retTodos();

		$ret = $objMdCorAdmIntegracaoTokensRN->consultar( $objMdCorAdmIntegracaoTokensDTO );
		//$ret = $ret[0] ?? null;

		// caso não exista integração cadastrada, realiza a inserção com os campos token, usuario e senha como null
		if ( $ret == null ) {
			$objMdCorAdmIntegRN = new MdCorAdmIntegracaoRN();
			$objMdCorAdmIntegDTO = new MdCorAdmIntegracaoDTO();
			$objMdCorAdmIntegDTO->setNumFuncionalidade($tpFuncionalidade);
			$objMdCorAdmIntegDTO->retNumFuncionalidade();
			$ObjMdCorAdmInteg = $objMdCorAdmIntegRN->consultar($objMdCorAdmIntegDTO);
	
			$objMdCorAdmIntegTokensDTOInsercao = new MdCorAdmIntegracaoTokensDTO();
			$objMdCorAdmIntegTokensDTOInsercao->setNumIdMdCorAdmIntegracaoTokens(null);
			$objMdCorAdmIntegTokensDTOInsercao->setNumIdMdCorAdmIntegracao($ObjMdCorAdmInteg->getNumFuncionalidade());
			$objMdCorAdmIntegTokensDTOInsercao->setNumIdMdCorContrato($contratoId);
			$objMdCorAdmIntegTokensDTOInsercao->setStrUsuario(null);
			$objMdCorAdmIntegTokensDTOInsercao->setStrSenha(null);
			$objMdCorAdmIntegTokensDTOInsercao->setStrToken(null);
			$objMdCorAdmIntegTokensDTOInsercao->setDthDataExpiraToken(null);
			$objMdCorAdmIntegTokensDTOInsercao->setStrSinAtivo('S');
	
			$objMdCorAdmIntegracaoTokensRN->cadastrar($objMdCorAdmIntegTokensDTOInsercao);
	
			return $this->buscaIntegracaoPorFuncionalidade( $tpFuncionalidade, $contratoId );
		}

		return $ret;

	}

	public function buscaFuncionalidadesCadastradas(){
		$objMdCorAdmIntegracaoDTO = new MdCorAdmIntegracaoDTO();

		$objMdCorAdmIntegracaoDTO->setStrSinAtivo('S');
		$objMdCorAdmIntegracaoDTO->retNumFuncionalidade();

		return InfraArray::converterArrInfraDTO(
			$this->listar( $objMdCorAdmIntegracaoDTO ),
			'Funcionalidade'
		);
	}

	public function verificaTokenExpirado( &$arrParametro, $objMdCorIntegracao, $idContrato ){
		// data atual
		$dataNesteInstante = date('Y-m-d H:i:s');
		$dataNesteInstante = strtotime( $dataNesteInstante );

		//
		// data de expiracao salva no banco
		$arrDataExp   = explode(' ' , $arrParametro['expiraEm']);
		$dataExpToken = implode('-',array_reverse(explode('/',$arrDataExp[0])));
		$dataExpToken .= ' ' . $arrDataExp[1]; // $arrDataExp[1] => horas
		$dataExpToken = is_null($arrParametro['expiraEm']) ? 0 : strtotime($dataExpToken);

		// verifica se data atual eh maior que a data de expiracao da operacao da API
		if ( $dataExpToken < $dataNesteInstante ) {
			
			$objMdCorIntegToken = $this->buscaIntegracaoPorFuncionalidade(MdCorAdmIntegracaoRN::$GERAR_TOKEN, $idContrato);
			
			if ( is_array( $objMdCorIntegToken ) && isset( $objMdCorIntegToken['suc'] ) && $objMdCorIntegToken['suc'] === false )
			    return $objMdCorIntegToken;

			if ( empty($objMdCorIntegToken) )
			    return ['suc' => false , ',msg' => 'Mapeamento de Integração '. MdCorAdmIntegracaoRN::$STR_GERAR_TOKEN .' não existe ou está inativo.'];
			
			// busca nova data de expiracao e token da operacao da API
			$dados = ( new MdCorApiRestRN() )->gerarToken($objMdCorIntegToken);
			
			if( empty($dados) ) return ['suc' => false , 'msg' => 'Erro no uso da API Gerar Token' , 'url' => $objMdCorIntegToken->getStrUrlOperacao()];
			
			if ( isset( $dados['suc'] ) && $dados['suc'] === false ) {
				$dados['url'] = $objMdCorIntegToken->getStrUrlOperacao();
			    return $dados;
            }

			//atualiza dados no banco
			$arrDataHr = explode('T',$dados['expiraEm']);
			$strData   = implode('/' , array_reverse(explode('-',$arrDataHr[0])));
			$strDataHr = $strData .' '. $arrDataHr[1];
			$IdMdCorAdmIntegracaoTokens = $objMdCorIntegracao->getNumIdMdCorAdmIntegracaoTokens();
			
			$objMdCorAdmIntegTokensRN = new MdCorAdmIntegracaoTokensRN();
			$objMdCorAdmIntegTokensDTO = new MdCorAdmIntegracaoTokensDTO();
			$objMdCorAdmIntegTokensDTO->setNumIdMdCorAdmIntegracaoTokens($IdMdCorAdmIntegracaoTokens);
			$objMdCorAdmIntegTokensDTO->retNumIdMdCorAdmIntegracaoTokens();
			$objMdCorAdmIntegTokensDTO = $objMdCorAdmIntegTokensRN->consultar($objMdCorAdmIntegTokensDTO);

			$objMdCorAdmIntegTokensDTO->setStrToken($dados['token']);
			$objMdCorAdmIntegTokensDTO->setDthDataExpiraToken($strDataHr);
			$objMdCorAdmIntegTokensRN->alterar($objMdCorAdmIntegTokensDTO);

			//atualiza dados da variavel $arrParametro
			$arrParametro['token']    = $objMdCorAdmIntegTokensDTO->getStrToken();
			$arrParametro['expiraEm'] = $objMdCorAdmIntegTokensDTO->getDthDataExpiraToken();
		}
		
		return true;
	}

	public function validaStatusCanceladoPlp($idPPN, $arrParametroRest) {
		$arrParametroRest['endpoint'] = 'https://api.correios.com.br/prepostagem/v2/prepostagens?id=' . $idPPN;
		$objMdCorApiValidaRotulo = new MdCorApiRestRN($arrParametroRest);
		$statusRotulo = $objMdCorApiValidaRotulo->validaStatusRotulo( $idPPN, $arrParametroRest['endpoint'] );

		if ( !isset( $arrStatusRotulo['suc'] ) && $statusRotulo == 'Cancelado' ) {
			return true;
		}

		return false;
	}
}
