<?

require_once dirname(__FILE__) . '/../../../SEI.php';

class MdCorRelContatoJustificativaINT extends InfraINT
{

    public static function listarDestinatarioNaoHabilitado($arr)
    {
        $arrObjContatoDTO = '';

        if (count($arr)) {
            $objContatoDTO = new ContatoDTO();
            $objContatoDTO->setBolExclusaoLogica(false);
            $objContatoDTO->retNumIdContato();
            $objContatoDTO->retStrNome();
            $objContatoDTO->retStrStaNatureza();
            $objContatoDTO->retDblCpf();
            $objContatoDTO->retStrCnpj();
            $objContatoDTO->setOrdStrNome(InfraDTO::$TIPO_ORDENACAO_ASC);
            $objContatoDTO->setNumIdContato($arr, InfraDTO::$OPER_IN);

            $objContatoRN = new ContatoRN();
            $arrObjContatoDTO = $objContatoRN->listarRN0325($objContatoDTO);
        }
        return $arrObjContatoDTO;
    }

    public static function listarDestinatarioNaoHabilitadoDuplicidade($arr)
    {
        $arrObjRelContJustDTO = '';

        if (count($arr)) {
            $arrObjRelContJustDTO = new MdCorRelContatoJustificativaDTO();
            $arrObjRelContJustDTO->retNumIdContato();
            $arrObjRelContJustDTO->retStrNomeContato();
            $arrObjRelContJustDTO->setNumIdContato($arr, InfraDTO::$OPER_IN);

            $objRelContJustRN = new MdCorRelContatoJustificativaRN();
            $arrObjRelContJustDTO = $objRelContJustRN->listar($arrObjRelContJustDTO);
        }
        return $arrObjRelContJustDTO;
    }

}
