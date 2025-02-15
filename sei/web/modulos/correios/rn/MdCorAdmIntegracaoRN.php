<?
/**
 * TRIBUNAL REGIONAL FEDERAL DA 4� REGI�O
 *
 * 23/11/2022 - criado por gustavos.colab
 *
 * Vers�o do Gerador de C�digo: 1.43.1
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
	public static $STR_SERV_POSTAL = 'Correios::Servi�os Postais';

	public static $GERAR_ETIQUETAS     = '5';
	public static $STR_GERAR_ETIQUETAS = 'Correios::Solicitar Etiquetas';

	public static $GERAR_PRE_POSTAGEM  = '6';
	public static $STR_PRE_POSTAGEM    = 'Correios::Pr� Postagem Nacional';

	public static $EMITIR_ROTULO     = '7';
	public static $STR_EMITIR_ROTULO = 'Correios::Emitir R�tulo';

	public static $DOWN_ROTULO     = '8';
	public static $STR_DOWN_ROTULO = 'Correios::Download R�tulo';

    public static $AVISO_RECEB     = '9';
    public static $STR_AVISO_RECEB = 'Correios::Aviso Recebimento';

    public static $CANCELAR_PRE_POSTAGEM = '10';
    public static $STR_CANCELAR_PRE_POSTAGEM = 'Correios::Cancelar Pr� Postagem';

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
			$objInfraException->adicionarValidacao('Nome n�o informado.');
		}else{
			$objMdCorAdmIntegracaoDTO->setStrNome(trim($objMdCorAdmIntegracaoDTO->getStrNome()));

			if (strlen($objMdCorAdmIntegracaoDTO->getStrNome()) > 200){
				$objInfraException->adicionarValidacao('Nome possui tamanho superior a 200 caracteres.');
			}
		}
	}

	private function validarNumFuncionalidade(MdCorAdmIntegracaoDTO $objMdCorAdmIntegracaoDTO, InfraException $objInfraException){
		if (InfraString::isBolVazia($objMdCorAdmIntegracaoDTO->getNumFuncionalidade())){
			$objInfraException->adicionarValidacao('Funcionalidade n�o informada.');
		}
	}

	private function validarStrSinAtivo(MdCorAdmIntegracaoDTO $objMdCorAdmIntegracaoDTO, InfraException $objInfraException){
		if (InfraString::isBolVazia($objMdCorAdmIntegracaoDTO->getStrSinAtivo())){
			$objInfraException->adicionarValidacao('Sinalizador de Exclus�o L�gica n�o informado.');
		}else{
			if (!InfraUtil::isBolSinalizadorValido($objMdCorAdmIntegracaoDTO->getStrSinAtivo())){
				$objInfraException->adicionarValidacao('Sinalizador de Exclus�o L�gica inv�lido.');
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
			throw new InfraException('Erro cadastrando Integra��o.',$e);
		}
	}

	protected function alterarControlado(MdCorAdmIntegracaoDTO $objMdCorAdmIntegracaoDTO){
		try {
			SessaoSEI::getInstance()->validarAuditarPermissao('md_cor_adm_integracao_alterar', __METHOD__, $objMdCorAdmIntegracaoDTO);

			$objInfraException = new InfraException();

			$this->validarStrNome($objMdCorAdmIntegracaoDTO, $objInfraException);
			$this->validarNumFuncionalidade($objMdCorAdmIntegracaoDTO, $objInfraException);
			$objInfraException->lancarValidacoes();

			$objMdCorAdmIntegracaoBD = new MdCorAdmIntegracaoBD( $this->getObjInfraIBanco() );
			$objMdCorAdmIntegracaoBD->alterar( $objMdCorAdmIntegracaoDTO );

		}catch(Exception $e){
			throw new InfraException('Erro alterando Integra��o.',$e);
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
			throw new InfraException('Erro excluindo Integra��o.',$e);
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
			throw new InfraException('Erro consultando Integra��o.',$e);
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
			throw new InfraException('Erro listando Integra��es.',$e);
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
			throw new InfraException('Erro contando Integra��es.',$e);
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
			throw new InfraException('Erro desativando Integra��o.',$e);
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
			throw new InfraException('Erro reativando Integra��o.',$e);
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
			throw new InfraException('Erro bloqueando Integra��o.',$e);
		}
	}

	/*
	 * Consulta somente na Integracao
	 * */
	public function buscaIntegracaoPorFuncionalidade( $tpFuncionalidade ){

	    if ( empty($tpFuncionalidade) ) return ['suc' => false , 'msg' => 'O Tipo de Funcionalidade da Integra��o est� vazio ou nulo.'];

		$objMdCorAdmIntegracaoDTO = new MdCorAdmIntegracaoDTO();

		$objMdCorAdmIntegracaoDTO->setNumFuncionalidade( $tpFuncionalidade );
		$objMdCorAdmIntegracaoDTO->setStrSinAtivo( 'S' );
		$objMdCorAdmIntegracaoDTO->retTodos();

		return $this->consultar( $objMdCorAdmIntegracaoDTO );
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

	public function verificaTokenExpirado( &$arrParametro, $objMdCorIntegracao ){
		// data atual
		$dataNesteInstante = date('Y-m-d H:i:s');
		$dataNesteInstante = strtotime( $dataNesteInstante );

		// data de expiracao salva no banco
		$arrDataExp   = explode(' ' , $arrParametro['expiraEm']);
		$dataExpToken = implode('-',array_reverse(explode('/',$arrDataExp[0])));
		$dataExpToken .= ' ' . $arrDataExp[1]; // $arrDataExp[1] => horas
		$dataExpToken = strtotime($dataExpToken);

		// verifica se data atual eh maior que a data de expiracao da operacao da API
		if ( $dataExpToken < $dataNesteInstante ) {

			$objMdCorIntegToken = $this->buscaIntegracaoPorFuncionalidade( self::$GERAR_TOKEN );

			if ( is_array( $objMdCorIntegToken ) && isset( $objMdCorIntegToken['suc'] ) && $objMdCorIntegToken['suc'] === false )
			    return $objMdCorIntegToken;

			if ( empty($objMdCorIntegToken) )
			    return ['suc' => false , ',msg' => 'Mapeamento de Integra��o '. MdCorAdmIntegracaoRN::$STR_GERAR_TOKEN .' n�o existe ou est� inativo.'];

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

			$objMdCorIntegracao->setDthDataExpiraToken($strDataHr);
			$objMdCorIntegracao->setStrToken($dados['token']);

			$this->alterar($objMdCorIntegracao);

			//atualiza dados da variavel $arrParametro
			$arrParametro['token']    = $objMdCorIntegracao->getStrToken();
			$arrParametro['expiraEm'] = $objMdCorIntegracao->getDthDataExpiraToken();
		}
		return true;
	}
}
