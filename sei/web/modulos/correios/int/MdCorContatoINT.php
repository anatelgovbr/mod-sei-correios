<?
/**
 * TRIBUNAL REGIONAL FEDERAL DA 4� REGI�O
 *
 * 07/06/2017 - criado por marcelo.cast
 *
 * Vers�o do Gerador de C�digo: 1.40.1
 */

require_once dirname(__FILE__) . '/../../../SEI.php';

class MdCorContatoINT extends InfraINT
{

    public static function listarContato($params)
    {

        $id_contato = $params['id_contato'];

        $objContatoDTO = new ContatoDTO();
        $objContatoDTO->retTodos(true);
        $objContatoDTO->setNumIdContato($id_contato);

        $objContatoRN = new ContatoRN();
        $arrObjContatoDTO = $objContatoRN->consultarRN0324($objContatoDTO);

        if (!is_null($arrObjContatoDTO) == 1) {
            $xml = '<Contato>';
            $xml .= '<IdContato>' . $arrObjContatoDTO->getNumIdContato() . '</IdContato>';
            $xml .= '<Nome>' . $arrObjContatoDTO->getStrNome() . '</Nome>';
            $xml .= '<ExpressaoTratamentoCargo>' . $arrObjContatoDTO->getStrExpressaoTratamentoCargo() . '</ExpressaoTratamentoCargo>';
            $xml .= '<ExpressaoCargo>' . $arrObjContatoDTO->getStrExpressaoCargo() . '</ExpressaoCargo>';
            $xml .= '<StaGenero>' . $arrObjContatoDTO->getStrStaGenero() . '</StaGenero>';
            $xml .= '<IdContatoAssociado>' . $arrObjContatoDTO->getNumIdContatoAssociado() . '</IdContatoAssociado>';
            $xml .= '<NomeContatoAssociado>' . $arrObjContatoDTO->getStrNomeContatoAssociado() . '</NomeContatoAssociado>';

            $idContatoAssociado = $arrObjContatoDTO->getNumIdContatoAssociado();

            if ($arrObjContatoDTO->getStrSinEnderecoAssociado() == 'S' && $arrObjContatoDTO->getNumIdContatoAssociado() != $arrObjContatoDTO->getNumIdContato()) {
                $objContatoAssociadoDTO = new ContatoDTO();
                $objContatoAssociadoDTO->retTodos(true);
                $objContatoAssociadoDTO->setNumIdContato($idContatoAssociado);
                $objContatoAssociadoDTO = $objContatoRN->consultarRN0324($objContatoAssociadoDTO);

                if ($objContatoAssociadoDTO->getStrStaNatureza() == ContatoRN::$TN_PESSOA_JURIDICA) {
                    $xml .= '<NomeDestinatarioAssociado>' . $arrObjContatoDTO->getStrNomeContatoAssociado() . '</NomeDestinatarioAssociado>';
                    $xml .= '<Endereco>' . $objContatoAssociadoDTO->getStrEndereco() . '</Endereco>';
                    $xml .= '<Complemento>' . $objContatoAssociadoDTO->getStrComplemento() . '</Complemento>';
                    $xml .= '<Bairro>' . $objContatoAssociadoDTO->getStrBairro() . '</Bairro>';
                    $xml .= '<Cep>' . $objContatoAssociadoDTO->getStrCep() . '</Cep>';
                    $xml .= '<NomeCidade>' . $objContatoAssociadoDTO->getStrNomeCidade() . '</NomeCidade>';
                    $xml .= '<SiglaUf>' . $objContatoAssociadoDTO->getStrSiglaUf() . '</SiglaUf>';
                }
            } else {
                $xml .= '<Endereco>' . $arrObjContatoDTO->getStrEndereco() . '</Endereco>';
                $xml .= '<Complemento>' . $arrObjContatoDTO->getStrComplemento() . '</Complemento>';
                $xml .= '<Bairro>' . $arrObjContatoDTO->getStrBairro() . '</Bairro>';
                $xml .= '<Cep>' . $arrObjContatoDTO->getStrCep() . '</Cep>';
                $xml .= '<NomeCidade>' . $arrObjContatoDTO->getStrNomeCidade() . '</NomeCidade>';
                $xml .= '<SiglaUf>' . $arrObjContatoDTO->getStrSiglaUf() . '</SiglaUf>';
            }
            $xml .= '</Contato>';
        }

        return $xml;
    }

	public static function _isDadoAlterado( $idContato , $idMdCorExpedSolic ){
		try {
			$arrAtributos       = ['Endereco','Cep','Complemento','Bairro','NomeCidade','SiglaUf'];
			$objContato         = self::getInfoContato($idContato);
			$nmContatoPrincipal = $objContato->getStrNome();
			$isContatoAssociado = false;

			if ( $objContato->getStrSinEnderecoAssociado() == 'S' ) {
				$idContato          = $objContato->getNumIdContatoAssociado();
				$isContatoAssociado = true;

				//busca novamente dados do contato, mas sera do associado que sera usado nas consultas abaixo
				$objContato         = self::getInfoContato($idContato);
			}
			$objMdCorContato = self::getinfoMdCorContato( $idContato, $idMdCorExpedSolic, $isContatoAssociado );

			$isTeveRegistroAlterado = false;
			foreach ( $arrAtributos as $atributo ) {
				if ( $objContato->get($atributo) != $objMdCorContato->get($atributo) ) {
					$isTeveRegistroAlterado = true;
					$objMdCorContato->set($atributo,$objContato->get($atributo));
				}
			}

			if ( $isContatoAssociado ) {
				if (
					$objContato->getStrNome() != $objMdCorContato->getStrNomeContatoAssociado()
					|| $nmContatoPrincipal != $objMdCorContato->getStrNome()
				) {
					$objMdCorContato->setStrNomeContatoAssociado($objContato->getStrNome());
					$objMdCorContato->setStrNome($nmContatoPrincipal);
					$isTeveRegistroAlterado = true;
				}
			} else {
				if ( $objContato->getStrNome() != $objMdCorContato->getStrNome() ) {
					$objMdCorContato->setStrNome($objContato->getStrNome());
					$isTeveRegistroAlterado = true;
				}
			}

			return ['objMdCorContato' => $objMdCorContato , 'isRegAlterado' => $isTeveRegistroAlterado];
		} catch (Exception $e) {
			throw new InfraException('N�o foi poss�vel comparar dados modificados do Contato no M�dulo dos Correios',$e);
		}
	}

	private static function getInfoContato( $idContato ){
		$objContatoDTO = new ContatoDTO();
		$objContatoRN = new ContatoRN();

		$objContatoDTO->setNumIdContato($idContato);
		$objContatoDTO->retTodos(true);

		return $objContatoRN->consultarRN0324($objContatoDTO);
	}

	private static function getinfoMdCorContato( $idContato , $idMdCorExpedSolic , $isContatoAssociado ){
		$objMdCorContatoDTO = new MdCorContatoDTO();
		$objMdCorContatoRN  = new MdCorContatoRN();

		if ( $isContatoAssociado )
			$objMdCorContatoDTO->setNumIdContatoAssociado($idContato);
		else
			$objMdCorContatoDTO->setNumIdContato($idContato);

		$objMdCorContatoDTO->setNumIdMdCorExpedicaoSolicitada($idMdCorExpedSolic);
		$objMdCorContatoDTO->retTodos(true);

		return $objMdCorContatoRN->consultar($objMdCorContatoDTO);
	}

}

?>