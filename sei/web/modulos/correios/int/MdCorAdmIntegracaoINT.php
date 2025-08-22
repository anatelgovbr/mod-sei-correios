<?php

require_once dirname(__FILE__).'/../../../SEI.php';

class MdCorAdmIntegracaoINT extends InfraINT {

	public static function getDadosFuncionalidade(){
		return [
			MdCorAdmIntegracaoRN::$GERAR_TOKEN     => MdCorAdmIntegracaoRN::$STR_GERAR_TOKEN,
			MdCorAdmIntegracaoRN::$RASTREAR        => MdCorAdmIntegracaoRN::$STR_RASTREAR,
			MdCorAdmIntegracaoRN::$CEP             => MdCorAdmIntegracaoRN::$STR_CEP,
			MdCorAdmIntegracaoRN::$SERV_POSTAL     => MdCorAdmIntegracaoRN::$STR_SERV_POSTAL,
			MdCorAdmIntegracaoRN::$GERAR_ETIQUETAS => MdCorAdmIntegracaoRN::$STR_GERAR_ETIQUETAS,
			MdCorAdmIntegracaoRN::$GERAR_PRE_POSTAGEM => MdCorAdmIntegracaoRN::$STR_PRE_POSTAGEM,
			MdCorAdmIntegracaoRN::$EMITIR_ROTULO      => MdCorAdmIntegracaoRN::$STR_EMITIR_ROTULO,
			MdCorAdmIntegracaoRN::$DOWN_ROTULO        => MdCorAdmIntegracaoRN::$STR_DOWN_ROTULO,
            MdCorAdmIntegracaoRN::$AVISO_RECEB        => MdCorAdmIntegracaoRN::$STR_AVISO_RECEB,
            MdCorAdmIntegracaoRN::$CANCELAR_PRE_POSTAGEM => MdCorAdmIntegracaoRN::$STR_CANCELAR_PRE_POSTAGEM
		];
	}

	/*
	 * Funcionalidades
	 * */
	public static function montarSelectFuncionalidade($itemSelecionado = null , $retornaItem = false, $arrItensCadastrados = null, $idSelecionado = null) {

		$arrFuncionalidades = self::getDadosFuncionalidade();

		if ( $retornaItem ) return $arrFuncionalidades[$retornaItem];

		$strOptions = '<option value="">Selecione</option>';

		foreach ( $arrFuncionalidades as $k => $v ) {
			$selected = '';
			// Filtro para retirar a Funcionalidade que já está cadastrada e ativa
			if ( !empty($arrItensCadastrados) ){
				if( !in_array($k,$arrItensCadastrados) ||  $k == $idSelecionado ) {
					if ($itemSelecionado && $itemSelecionado == $k) $selected = ' selected';
					$strOptions .= "<option value='$k'$selected>$v</option>";
				}
			} else {
				if ( $itemSelecionado && $itemSelecionado == $k ) $selected = ' selected';
				$strOptions .= "<option value='$k'$selected>$v</option>";
			}
		}
		return $strOptions;
	}

    public static function gerenciaDadosRestritos($valor, $acao = 'C'){
        switch ( $acao ) {
            case 'C':
                return base64_encode( strrev( base64_encode( strrev( $valor ) ) ) );
                break;

            case 'D':
                return strrev( base64_decode( strrev( base64_decode( $valor ) ) ) );
                break;

            default:
                throw new InfraException('Tipo de Ação não declarado na função.');
        }
    }
}
?>