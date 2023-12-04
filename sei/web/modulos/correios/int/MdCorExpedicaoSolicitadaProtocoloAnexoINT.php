<?
/**
 *
 * 20/06/2017 - criado por marcelo.cast
 *
 */

require_once dirname(__FILE__) . '/../../../SEI.php';

class MdCorExpedicaoSolicitadaProtocoloAnexoINT extends InfraINT{

    public static function autoCompletarProtocoloAnexo($strPalavrasPesquisa, $id_doc){

        $id_procedimento = $_GET['id_procedimento'];
        $protocoloDTO = new RelProtocoloProtocoloDTO();
        $protocoloDTO->retDblIdProtocolo1();
        $protocoloDTO->retDblIdProtocolo2();
        $protocoloDTO->retStrProtocoloFormatadoProtocolo1();
        $protocoloDTO->retStrProtocoloFormatadoProtocolo2();
        $protocoloDTO->setDblIdProtocolo1($id_procedimento);
        $protocoloDTO->setDblIdProtocolo2($id_doc, InfraDTO::$OPER_DIFERENTE);
        $protocoloDTO->setStrProtocoloFormatadoProtocolo2('%' . $strPalavrasPesquisa . '%', InfraDTO::$OPER_LIKE);
        //$protocoloDTO->setStrStaProtocolo( ProtocoloRN::$TPP_DOCUMENTOS );
        $protocoloDTO->setOrdStrProtocoloFormatadoProtocolo2(InfraDTO::$TIPO_ORDENACAO_ASC);

        $objProtocoloRN = new RelProtocoloProtocoloRN();
        $arrObjProtocoloDTO = $objProtocoloRN->listarRN0187($protocoloDTO);
        $arrRetorno = array();

        if (is_array($arrObjProtocoloDTO)) {

            foreach ($arrObjProtocoloDTO as $relProtProtDTO) {

                //necessario obter tipo de documento e numero
                //como é consulta ajax retornando poucos registros, pesquisando apenas em docs do proprio processo, optei por deixar uma consulta para cada registro mesmo, o impacto em performance deverá ser quase nenhum neste caso
                $docDTO = new DocumentoDTO();
                $docRN = new DocumentoRN();
                $docDTO->retDblIdDocumento();
                $docDTO->retNumIdSerie();
                $docDTO->retStrNomeSerie();
                $docDTO->retStrNumero();
                $docDTO->setDblIdDocumento($relProtProtDTO->getDblIdProtocolo2());
                $docDTO = $docRN->consultarRN0005($docDTO);

                $newDTO = new RelProtocoloProtocoloDTO();
                $newDTO->setDblIdProtocolo1($relProtProtDTO->getDblIdProtocolo1());
                $newDTO->setDblIdProtocolo2($relProtProtDTO->getDblIdProtocolo2());
                $newDTO->setStrProtocoloFormatadoProtocolo1($relProtProtDTO->getStrProtocoloFormatadoProtocolo1());
                $newDTO->setStrProtocoloFormatadoProtocolo2($relProtProtDTO->getStrProtocoloFormatadoProtocolo2() . ' ( ' . $docDTO->getStrNomeSerie() . ' ' . $docDTO->getStrNumero() . ' )');

                $arrRetorno[] = $newDTO;
            }

        }

        return $arrRetorno;
    }

    public static function retornaTipoMidiaExiste($idProtocolo, $bolRetornarXML = true){

        $mdCorExtensaoMidiaRN = new MdCorExtensaoMidiaRN();
        $objMdCorExtensaoMidiaDTO = new MdCorExtensaoMidiaDTO();
        $objMdCorExtensaoMidiaDTO->retStrNomeExtensao();
        $objMdCorExtensaoMidiaDTO->retNumIdArquivoExtensao();
        $arrObjMdCorExtensaoMidiaDTO = $mdCorExtensaoMidiaRN->listar($objMdCorExtensaoMidiaDTO);

        $anexoRN = new AnexoRN();
        $listaAnexoDTO = new AnexoDTO();
        $listaAnexoDTO->setDblIdProtocolo($idProtocolo);
        $listaAnexoDTO->retNumIdAnexo();
        $listaAnexoDTO = $anexoRN->listarRN0218($listaAnexoDTO);
        $existeExtensao = 'false';

        foreach($listaAnexoDTO as $anexoDTO) {
            $anexoRN = new AnexoRN();
            $objAnexoDTO = new AnexoDTO();
            $objAnexoDTO->setNumIdAnexo($anexoDTO->getNumIdAnexo());
            $objAnexoDTO->retStrNome();
            $objAnexoDTO = $anexoRN->consultarRN0736($objAnexoDTO);

            if($objAnexoDTO) {
                $arrDocumento = explode('.', $objAnexoDTO->getStrNome());
                foreach ($arrObjMdCorExtensaoMidiaDTO as $objMdCorExtensaoMidiaDTO) {
                    if ( strcasecmp( $objMdCorExtensaoMidiaDTO->getStrNomeExtensao() , end($arrDocumento) ) === 0 )
                        $existeExtensao = 'true';

                }

            }
        }

        $strRetorno = $bolRetornarXML ? '<retorno>' . $existeExtensao . '</retorno>' : $existeExtensao;
        return $strRetorno;
    }

    public static function verificarSeProcedimentoAnexado($idProtocolo, $bolRetornarXML = true){
        $strIsProcedimentoAnexado = 'false';
        $protocoloDTO = new ProtocoloDTO();
        $protocoloBD = new ProtocoloBD(SessaoSEI::getInstance()->getObjInfraIBanco());
        $protocoloDTO->retStrStaProtocolo();
        $protocoloDTO->setDblIdProtocolo($idProtocolo);
        $objProtocolo = $protocoloBD->consultar($protocoloDTO);
        if($objProtocolo->getStrStaProtocolo() == ProtocoloRN::$TP_PROCEDIMENTO) {
            $strIsProcedimentoAnexado = 'true';
        }

        $strRetorno = $bolRetornarXML ? '<retorno>' . $strIsProcedimentoAnexado . '</retorno>' : $strIsProcedimentoAnexado;
        return $strRetorno;
    }

    public static function verificarAnexoSomenteMidia($idProtocolo, $bolRetornarXML = true) {
        $strRetorno = self::retornaTipoMidiaExiste($idProtocolo, false);
        if($strRetorno!='true'){
            $strRetorno = self::verificarSeProcedimentoAnexado($idProtocolo, false);
        }
        $strRetorno = $bolRetornarXML ? '<retorno>' . $strRetorno . '</retorno>' : $strRetorno;
        return $strRetorno;
    }
}

?>