<?
/**
 * TRIBUNAL REGIONAL FEDERAL DA 4ª REGIÃO
 *
 * 07/06/2017 - criado por marcelo.cast
 *
 * Versão do Gerador de Código: 1.40.1
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

    public static function _montarNewMdCorContatoDTO( $objMdCorContatoDTO , $idContato , $idMdCorExpedSolic, $isAtualizacao ){
        try {
            $objMdCorContatoDTO = $isAtualizacao ? $objMdCorContatoDTO : new MdCorContatoDTO();

            $arrObjContatoDTO = self::getInfoContato($idContato);
            
            //$itemDTO->setNumIdMdCorContato();
            $objMdCorContatoDTO->setNumIdMdCorExpedicaoSolicitada($idMdCorExpedSolic);
            $objMdCorContatoDTO->setNumIdContato($arrObjContatoDTO->getNumIdContato());
            $objMdCorContatoDTO->setStrNome($arrObjContatoDTO->getStrNome());
            $objMdCorContatoDTO->setStrSinAtivo('S');
            $objMdCorContatoDTO->setStrStaNatureza($arrObjContatoDTO->getStrStaNatureza());
            $objMdCorContatoDTO->setStrSinEnderecoAssociado($arrObjContatoDTO->getStrSinEnderecoAssociado());

            if ($arrObjContatoDTO->getStrSinEnderecoAssociado() == 'S') {

                $objMdCorContatoDTO->setNumIdContatoAssociado($arrObjContatoDTO->getNumIdContatoAssociado());
                $objMdCorContatoDTO->setStrNomeContatoAssociado($arrObjContatoDTO->getStrNomeContatoAssociado());
                $objMdCorContatoDTO->setStrStaNaturezaContatoAssociado($arrObjContatoDTO->getStrStaNaturezaContatoAssociado());
                $objMdCorContatoDTO->setNumIdTipoContatoAssociado($arrObjContatoDTO->getNumIdTipoContatoAssociado());
                $objMdCorContatoDTO->setStrEndereco($arrObjContatoDTO->getStrEnderecoContatoAssociado());
                $objMdCorContatoDTO->setStrComplemento($arrObjContatoDTO->getStrComplementoContatoAssociado());
                $objMdCorContatoDTO->setStrBairro($arrObjContatoDTO->getStrBairroContatoAssociado());
                $objMdCorContatoDTO->setStrCep($arrObjContatoDTO->getStrCepContatoAssociado());
                $objMdCorContatoDTO->setStrNomeCidade($arrObjContatoDTO->getStrNomeCidadeContatoAssociado());
                $objMdCorContatoDTO->setStrSiglaUf($arrObjContatoDTO->getStrSiglaUfContatoAssociado());
            } else {
                $objMdCorContatoDTO->setNumIdContatoAssociado($arrObjContatoDTO->getNumIdContatoAssociado());
                $objMdCorContatoDTO->setStrNomeContatoAssociado($arrObjContatoDTO->getStrNomeContatoAssociado());
                $objMdCorContatoDTO->setStrStaNaturezaContatoAssociado($arrObjContatoDTO->getStrStaNaturezaContatoAssociado());
                $objMdCorContatoDTO->setStrEndereco($arrObjContatoDTO->getStrEndereco());
                $objMdCorContatoDTO->setStrComplemento($arrObjContatoDTO->getStrComplemento());
                $objMdCorContatoDTO->setStrBairro($arrObjContatoDTO->getStrBairro());
                $objMdCorContatoDTO->setStrCep($arrObjContatoDTO->getStrCep());
                $objMdCorContatoDTO->setStrNomeCidade($arrObjContatoDTO->getStrNomeCidade());
                $objMdCorContatoDTO->setStrSiglaUf($arrObjContatoDTO->getStrSiglaUf());
            }

            $objMdCorContatoDTO->setStrStaGenero($arrObjContatoDTO->getStrStaGenero());
            $objMdCorContatoDTO->setNumIdTipoContato($arrObjContatoDTO->getNumIdTipoContato());
            $objMdCorContatoDTO->setStrExpressaoCargo($arrObjContatoDTO->getStrExpressaoCargo());
            $objMdCorContatoDTO->setStrExpressaoTratamentoCargo($arrObjContatoDTO->getStrExpressaoTratamentoCargo());

            return $objMdCorContatoDTO;
        } catch (Exception $e) {
            throw new InfraException('Não foi possível montar o DTO de Contato do Módulo dos Correios a partir do POST',$e);
        }
    }

    public static function _isDadoAlterado( $idContato , $idMdCorExpedSolic, $isPodeAtualizar, $idDocumentoPrincipal){
        try {
            $arrAtributos       = ['IdContato', 'Endereco','Cep','Complemento','Bairro','NomeCidade','SiglaUf'];
            // verifica se alterou o destinatario do documento

            $objProtocoloDocPrincipalRN = new ProtocoloRN();
            $objProtocoloDocPrincipalDTO = new ProtocoloDTO();
            $objProtocoloDocPrincipalDTO->retTodos();
            $objProtocoloDocPrincipalDTO->retNumIdSerieDocumento();
            $objProtocoloDocPrincipalDTO->retStrNomeSerieDocumento();
            $objProtocoloDocPrincipalDTO->retStrNumeroDocumento();
            $objProtocoloDocPrincipalDTO->retStrStaDocumentoDocumento();

            $objProtocoloDocPrincipalDTO->setDblIdProtocolo($idDocumentoPrincipal);

            $objProtocoloDocPrincipalDTO = $objProtocoloDocPrincipalRN->consultarRN0186($objProtocoloDocPrincipalDTO);

            $infraParametrosRN = new InfraParametroRN();
            $objInfraParametrosDTO = new InfraParametroDTO();
            $objInfraParametrosDTO->retStrValor();
            $objInfraParametrosDTO->setStrNome('MODULO_CORREIOS_ID_DOCUMENTO_EXPEDICAO');
            $objInfraParametrosDTO = $infraParametrosRN->consultar($objInfraParametrosDTO);

            $arrIdSerieDocumento = array();
            if ($objInfraParametrosDTO) {
                $arrIdSerieDocumento = explode(',', $objInfraParametrosDTO->getStrValor());
            }

            $bolExpedicaoExterno = false;
            if (in_array($objProtocoloDocPrincipalDTO->getNumIdSerieDocumento(), $arrIdSerieDocumento) && $objProtocoloDocPrincipalDTO->getStrStaDocumentoDocumento() == 'X') {
                $bolExpedicaoExterno = true;
            }
            
            $objParticipanteRN = new ParticipanteRN();
            $objParticipanteDTO = new ParticipanteDTO();
            $objParticipanteDTO->setDblIdProtocolo($idDocumentoPrincipal);
            $objParticipanteDTO->setNumMaxRegistrosRetorno(1);
            if ($bolExpedicaoExterno) {
                $objParticipanteDTO->setStrStaParticipacao(ParticipanteRN::$TP_INTERESSADO);
            } else {
                $objParticipanteDTO->setStrStaParticipacao(ParticipanteRN::$TP_DESTINATARIO);
            }
            $objParticipanteDTO->setBolExclusaoLogica(false);
            $objParticipanteDTO->retTodos();
            $objParticipanteDTO = $objParticipanteRN->consultarRN1008($objParticipanteDTO);
            if ( !empty($objParticipanteDTO) && $objParticipanteDTO->getNumIdContato() != $idContato ) {
                $idContato = $objParticipanteDTO->getNumIdContato();
            }            
            
            $objContato         = self::getInfoContato($idContato);
            $nmContatoPrincipal = $objContato->getStrNome();
            $isContatoAssociado = false;

            if ( $objContato->getStrSinEnderecoAssociado() == 'S' ) {
                $isContatoAssociado = true;

                //busca novamente dados do contato, mas sera do associado que sera usado nas consultas abaixo
                $objContato = self::getInfoContato( $objContato->getNumIdContatoAssociado() );
            }

            $objMdCorContato = self::getinfoMdCorContato( $idMdCorExpedSolic );

            $isTeveRegistroAlterado = false;

            if ( $isPodeAtualizar ) {
                // verifica se houve mudança nos dados do endereço, sendo do associado ou não
                foreach ($arrAtributos as $atributo) {
                    if ($objContato->get($atributo) != $objMdCorContato->get($atributo)) {
                        $isTeveRegistroAlterado = true;
                        $objMdCorContato->set($atributo, $objContato->get($atributo));
                    }
                }

                //verifica se usa endereço do associado ou nao e, em seguida, se houve mudança nos nomes e/ou
                //vinculo com associado, mesmo não usando o endereço do mesmo
                if ( $isContatoAssociado ) {
                    if ( $objContato->getStrNome() != $objMdCorContato->getStrNomeContatoAssociado()
                        ||
                        $nmContatoPrincipal != $objMdCorContato->getStrNome()
                    ) {
                        $objMdCorContato->setStrNomeContatoAssociado($objContato->getStrNome());
                        $objMdCorContato->setStrNome($nmContatoPrincipal);
                        $isTeveRegistroAlterado = true;
                    }

                    if ( $objContato->getNumIdContatoAssociado() != $objMdCorContato->getNumIdContatoAssociado() ) {
                        $objMdCorContato->setStrNomeContatoAssociado( $objContato->getStrNome() );
                        $objMdCorContato->setNumIdContatoAssociado( $objContato->getNumIdContato() );
                        $objMdCorContato->setStrStaNaturezaContatoAssociado( $objContato->getStrStaNatureza() );

                        $isTeveRegistroAlterado = true;
                    }
                } else {
                    if ( $objContato->getStrNome() != $objMdCorContato->getStrNome() ) {
                        $objMdCorContato->setStrNome($objContato->getStrNome());
                        $isTeveRegistroAlterado = true;
                    }

                    if ( $objContato->getNumIdContatoAssociado() != $objMdCorContato->getNumIdContatoAssociado() ) {
                        $objMdCorContato->setStrNomeContatoAssociado( $objContato->getStrNomeContatoAssociado() );
                        $objMdCorContato->setNumIdContatoAssociado( $objContato->getNumIdContatoAssociado() );
                        $objMdCorContato->setStrStaNaturezaContatoAssociado( $objContato->getStrStaNaturezaContatoAssociado() );

                        $isTeveRegistroAlterado = true;
                    }
                }
            }

            return ['objMdCorContato' => $objMdCorContato , 'isRegAlterado' => $isTeveRegistroAlterado];
        } catch (Exception $e) {
            throw new InfraException('Não foi possível comparar dados modificados do Contato no Módulo dos Correios',$e);
        }
    }

    public static function getInfoContato( $idContato ){
        $objContatoDTO = new ContatoDTO();
        $objContatoRN = new ContatoRN();

        $objContatoDTO->setNumIdContato($idContato);
        $objContatoDTO->setBolExclusaoLogica(false);
        $objContatoDTO->retTodos(true);

        return $objContatoRN->consultarRN0324($objContatoDTO);
    }

    private static function getinfoMdCorContato( $idMdCorExpedSolic ){
        $objMdCorContatoDTO = new MdCorContatoDTO();
        $objMdCorContatoRN  = new MdCorContatoRN();

        $objMdCorContatoDTO->setNumIdMdCorExpedicaoSolicitada($idMdCorExpedSolic);
        $objMdCorContatoDTO->retTodos(true);

        return $objMdCorContatoRN->consultar($objMdCorContatoDTO);
    }

}

?>