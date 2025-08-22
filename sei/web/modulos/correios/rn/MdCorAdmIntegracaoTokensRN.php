<?
/**
 * TRIBUNAL REGIONAL FEDERAL DA 4ª REGIÃO
 *
 * 23/11/2022 - criado por gustavos.colab
 *
 * Versão do Gerador de Código: 1.43.1
 */

require_once dirname(__FILE__).'/../../../SEI.php';

class MdCorAdmIntegracaoTokensRN extends InfraRN {

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

	protected function contarConectado(MdCorAdmIntegracaoTokensDTO $objMdCorAdmIntegracaoTokensDTO){
		try {
			//Valida Permissao
			//SessaoSEI::getInstance()->validarPermissao('md_cor_servico_postal_listar');

			//Regras de Negocio
			//$objInfraException = new InfraException();

			//$objInfraException->lancarValidacoes();

			$objMdCorServicoPostalBD = new MdCorAdmIntegracaoTokensBD($this->getObjInfraIBanco());
			$ret = $objMdCorServicoPostalBD->contar($objMdCorAdmIntegracaoTokensDTO);

			return $ret;
		} catch(Exception $e) {
			throw new InfraException('Erro contando os.',$e);
		}
	}

	protected function cadastrarControlado(MdCorAdmIntegracaoTokensDTO $objMdCorAdmIntegracaoTokensDTO) {
		try{
			//SessaoSEI::getInstance()->validarAuditarPermissao('md_cor_adm_integracao_tokens_cadastrar', __METHOD__, $objMdCorAdmIntegracaoTokensDTO);

			$objInfraException = new InfraException();
			$objInfraException->lancarValidacoes();

			$objMdCorAdmIntegracaoTokensBD = new MdCorAdmIntegracaoTokensBD( $this->getObjInfraIBanco() );
			$ret = $objMdCorAdmIntegracaoTokensBD->cadastrar( $objMdCorAdmIntegracaoTokensDTO );
			
			//$this->getObjInfraIBanco()->confirmarTransacao();

			return $ret;

		}catch(Exception $e){
			throw new InfraException('Erro cadastrando token de integração.',$e);
		}
	}

	protected function alterarControlado(MdCorAdmIntegracaoTokensDTO $objMdCorAdmIntegracaoTokensDTO) {
		try{
			//SessaoSEI::getInstance()->validarAuditarPermissao('md_cor_adm_integracao_tokens_cadastrar', __METHOD__, $objMdCorAdmIntegracaoTokensDTO);

			$objInfraException = new InfraException();
			$objInfraException->lancarValidacoes();

			$objMdCorAdmIntegracaoTokensBD = new MdCorAdmIntegracaoTokensBD( $this->getObjInfraIBanco() );
			$ret = $objMdCorAdmIntegracaoTokensBD->alterar( $objMdCorAdmIntegracaoTokensDTO );
			
			//$this->getObjInfraIBanco()->confirmarTransacao();

			return $ret;

		}catch(Exception $e){
			throw new InfraException('Erro cadastrando token de integração.',$e);
		}
	}

	protected function listarConectado(MdCorAdmIntegracaoTokensDTO $objMdCorAdmIntegracaoTokensDTO) {
		try {

			//SessaoSEI::getInstance()->validarAuditarPermissao('md_cor_adm_integracao_listar', __METHOD__, $objMdCorAdmIntegracaoTokensDTO);

			//Regras de Negocio
			//$objInfraException = new InfraException();

			//$objInfraException->lancarValidacoes();

			$objMdCorAdmIntegracaoTokensBD = new MdCorAdmIntegracaoTokensBD($this->getObjInfraIBanco());
			$ret = $objMdCorAdmIntegracaoTokensBD->listar($objMdCorAdmIntegracaoTokensDTO);

			return $ret;

		}catch(Exception $e){
			throw new InfraException('Erro listando Integrações.',$e);
		}
	}

	protected function consultarControlado(MdCorAdmIntegracaoTokensDTO $objMdCorAdmIntegracaoTokensDTO) {
		try {

			//SessaoSEI::getInstance()->validarAuditarPermissao('md_cor_adm_integracao_consultar', __METHOD__, $objMdCorAdmIntegracaoTokensDTO);

			//Regras de Negocio
			//$objInfraException = new InfraException();

			//$objInfraException->lancarValidacoes();

			$objMdCorAdmIntegracaoTokensBD = new MdCorAdmIntegracaoTokensBD($this->getObjInfraIBanco());

			$ret = $objMdCorAdmIntegracaoTokensBD->consultar($objMdCorAdmIntegracaoTokensDTO);

			return $ret;

		}catch(Exception $e){
			throw new InfraException('Erro consultando Integração.',$e);
		}
	}

	protected function excluirControlado($arrObjMdCorAdmIntegracaoTokensDTO){
		try {

			//SessaoSEI::getInstance()->validarAuditarPermissao('md_cor_adm_integracao_excluir', __METHOD__, $arrObjMdCorAdmIntegracaoTokensDTO);

			//Regras de Negocio
			//$objInfraException = new InfraException();

			//$objInfraException->lancarValidacoes();

			$objMdCorAdmIntegracaoTokensBD = new MdCorAdmIntegracaoTokensBD($this->getObjInfraIBanco());
			for($i=0;$i<count($arrObjMdCorAdmIntegracaoTokensDTO);$i++){
				$objMdCorAdmIntegracaoTokensBD->excluir($arrObjMdCorAdmIntegracaoTokensDTO[$i]);
			}

		}catch(Exception $e){
			throw new InfraException('Erro excluindo Integração.',$e);
		}
	}

	protected function desativarControlado($arrObjMdCorAdmIntegracaoTokensDTO){
		try {

			//SessaoSEI::getInstance()->validarAuditarPermissao('md_cor_adm_integracao_desativar', __METHOD__, $arrObjMdCorAdmIntegracaoTokensDTO);

			//Regras de Negocio
			//$objInfraException = new InfraException();

			//$objInfraException->lancarValidacoes();

			$objMdCorAdmIntegracaoTokensBD = new MdCorAdmIntegracaoTokensBD($this->getObjInfraIBanco());
			for($i=0;$i<count($arrObjMdCorAdmIntegracaoTokensDTO);$i++){
				$objMdCorAdmIntegracaoTokensBD->desativar($arrObjMdCorAdmIntegracaoTokensDTO[$i]);
			}

		}catch(Exception $e){
			throw new InfraException('Erro desativando Integração.',$e);
		}
	}

	protected function reativarControlado($arrObjMdCorAdmIntegracaoTokensDTO){
		try {

			//SessaoSEI::getInstance()->validarAuditarPermissao('md_cor_adm_integracao_reativar', __METHOD__, $arrObjMdCorAdmIntegracaoTokensDTO);

			//Regras de Negocio
			//$objInfraException = new InfraException();

			//$objInfraException->lancarValidacoes();

			$objMdCorAdmIntegracaoTokensBD = new MdCorAdmIntegracaoTokensBD($this->getObjInfraIBanco());
			for($i=0;$i<count($arrObjMdCorAdmIntegracaoTokensDTO);$i++){
				$objMdCorAdmIntegracaoTokensBD->reativar($arrObjMdCorAdmIntegracaoTokensDTO[$i]);
			}

		}catch(Exception $e){
			throw new InfraException('Erro reativando Integração.',$e);
		}
	}

	protected function montarArrTokensConectado(){

    $objMdCorAdmIntegracaoTokensDTO = new MdCorAdmIntegracaoTokensDTO();
	$objMdCorAdmIntegracaoTokensDTO->setNumFuncionalidade( MdCorAdmIntegracaoRN::$GERAR_TOKEN );
    $objMdCorAdmIntegracaoTokensDTO->retTodos();
    $objMdCorAdmIntegracaoTokensDTO->retStrNumeroContrato();
    $arrListaHeader    = $this->listar( $objMdCorAdmIntegracaoTokensDTO );
    $arrItensHeader    = [];
    $arrIdsItensHeader = [];

    if( !empty( $arrListaHeader ) ){
      foreach ( $arrListaHeader as $k => $v ) {
        $arrIdsItensHeader[] = $v->getNumIdMdCorAdmIntegracaoTokens();

        $itemHeader = [
          $v->getNumIdMdCorAdmIntegracaoTokens(),
          $v->getNumIdMdCorContrato(),
          $v->getStrNumeroContrato(),
		  $v->getStrUsuario(),
		  $v->getStrSenha(),
		  $v->getStrToken(),
		  '*****',
		  '*****'
        ];

        $arrItensHeader[] = $itemHeader;
      }

    }

    return [
      'itensTabela'       => $arrItensHeader,
      'qtdTokens'         => $arrItensHeader ? count( $arrItensHeader ) : 0,
      'strIdsItensTokens' => implode( ',' , $arrIdsItensHeader )
    ];
  }
}
