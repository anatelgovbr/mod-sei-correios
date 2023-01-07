<?
/**
 * TRIBUNAL REGIONAL FEDERAL DA 4� REGI�O
 *
 * 14/06/2017 - criado por jaqueline.cast
 *
 * Vers�o do Gerador de C�digo: 1.40.1
 */

require_once dirname(__FILE__) . '/../../../SEI.php';

class MdCorListaStatusRN extends InfraRN
{

    public function __construct()
    {
        parent::__construct();
    }

    protected function inicializarObjInfraIBanco()
    {
        return BancoSEI::getInstance();
    }

    private function validarNumStatus(MdCorListaStatusDTO $objMdCorListaStatusDTO, InfraException $objInfraException)
    {
        if (InfraString::isBolVazia($objMdCorListaStatusDTO->getNumStatus())) {
            $objInfraException->adicionarValidacao('Status n�o informado.');
        }
    }

    /*
     * Valida se j� existe alguma linha com Tipo/Status no banco. EU#27389

    private function validarDuplicidadeTipoStatus(MdCorListaStatusDTO $objMdCorListaStatusDTO, InfraException $objInfraException)
    {
        $objMdCorListaStatusDTOAux = new MdCorListaStatusDTO();
        $objMdCorListaStatusRNAux = new MdCorListaStatusRN();
        $objMdCorListaStatusDTOAux->retTodos(true);
        $objMdCorListaStatusDTOAux->setNumStatus($objMdCorListaStatusDTO->getNumStatus(), InfraDTO::$OPER_IGUAL);
        $objMdCorListaStatusDTOAux->setStrTipo('' . $objMdCorListaStatusDTO->getStrTipo() . '', InfraDTO::$OPER_IGUAL);
        $arrObjMdCorListaStatusDTO = $objMdCorListaStatusRNAux->listar($objMdCorListaStatusDTOAux);

        if (count($arrObjMdCorListaStatusDTO) > 0) {
             $objInfraException->adicionarValidacao('J� existe um tipo de status cadastrado com este C�digo/Tipo.');
        }
    }

    private function validarStrDescricaoObjeto(MdCorListaStatusDTO $objMdCorListaStatusDTO, InfraException $objInfraException)
    {
        if (InfraString::isBolVazia($objMdCorListaStatusDTO->getStrDescricaoObjeto())) {
            $objInfraException->adicionarValidacao('Descri��o no Rastreio do Objeto n�o informado.');
        } else {
            $objMdCorListaStatusDTO->setStrDescricaoObjeto(trim($objMdCorListaStatusDTO->getStrDescricaoObjeto()));
        }
    }

    private function validarStrDescricao(MdCorListaStatusDTO $objMdCorListaStatusDTO, InfraException $objInfraException)
    {
        if (InfraString::isBolVazia($objMdCorListaStatusDTO->getStrDescricao())) {
            $objInfraException->adicionarValidacao('Descri��o SRO n�o informado.');
        } else {
            $objMdCorListaStatusDTO->setStrDescricao(trim($objMdCorListaStatusDTO->getStrDescricao()));
        }
    }*/

    private function validarStrTipo(MdCorListaStatusDTO $objMdCorListaStatusDTO, InfraException $objInfraException)
    {
        if (InfraString::isBolVazia($objMdCorListaStatusDTO->getStrTipo())) {
            $objInfraException->adicionarValidacao('Tipo n�o informado.');
        } else {
            $objMdCorListaStatusDTO->setStrTipo(strtoupper(trim($objMdCorListaStatusDTO->getStrTipo())));

            if (strlen($objMdCorListaStatusDTO->getStrTipo()) > 4) {
                $objInfraException->adicionarValidacao('Tipo possui tamanho superior a 4 caracteres.');
            }
        }
    }

    private function validarStrStaRastreioModulo(MdCorListaStatusDTO $objMdCorListaStatusDTO, InfraException $objInfraException)
    {
        if (InfraString::isBolVazia($objMdCorListaStatusDTO->getStrStaRastreioModulo())) {
            $objInfraException->adicionarValidacao('Situa��o no Rastreio do M�dulo n�o informada.');
        } else {
            $objMdCorListaStatusDTO->setStrStaRastreioModulo(trim($objMdCorListaStatusDTO->getStrStaRastreioModulo()));

            if (strlen($objMdCorListaStatusDTO->getStrStaRastreioModulo()) > 2100) {
                $objInfraException->adicionarValidacao('Situa��o no Rastreio do M�dulo possui tamanho superior a 2100 caracteres.');
            }
        }
    }

    protected function cadastrarControlado(MdCorListaStatusDTO $objMdCorListaStatusDTO)
    {
        try {

            //Valida Permissao
            SessaoSEI::getInstance()->validarPermissao('md_cor_lista_status_cadastrar');

            //Regras de Negocio
            $objInfraException = new InfraException();

            $this->validarNumStatus($objMdCorListaStatusDTO, $objInfraException);
            $this->validarStrTipo($objMdCorListaStatusDTO, $objInfraException);

            $objInfraException->lancarValidacoes();


            $objMdCorListaStatusBD = new MdCorListaStatusBD($this->getObjInfraIBanco());
            $ret = $objMdCorListaStatusBD->cadastrar($objMdCorListaStatusDTO);

            //Auditoria

            return $ret;

        } catch (Exception $e) {
            throw new InfraException('Erro cadastrando projeto.', $e);
        }
    }

    protected function alterarControlado(MdCorListaStatusDTO $objMdCorListaStatusDTO)
    {
        try {

            //Valida Permissao
            SessaoSEI::getInstance()->validarPermissao('md_cor_lista_status_alterar');

            //Regras de Negocio
            $objInfraException = new InfraException();

            if ($objMdCorListaStatusDTO->isSetNumStatus()) {
                $this->validarNumStatus($objMdCorListaStatusDTO, $objInfraException);
            }
            if ($objMdCorListaStatusDTO->isSetStrTipo()) {
                $this->validarStrTipo($objMdCorListaStatusDTO, $objInfraException);
            }
//            if ($objMdCorListaStatusDTO->isSetStrStaRastreioModulo()) {
//                $this->validarStrDescricao($objMdCorListaStatusDTO, $objInfraException);
//            }

//            if ($objMdCorListaStatusDTO->isSetStrDescricaoObjeto()) {
//                $this->validarStrDescricaoObjeto($objMdCorListaStatusDTO, $objInfraException);
//            }

            if ($objMdCorListaStatusDTO->isSetStrTipo()) {
                $this->validarNumStatus($objMdCorListaStatusDTO, $objInfraException);
            }

            $objInfraException->lancarValidacoes();

            $objMdCorListaStatusBD = new MdCorListaStatusBD($this->getObjInfraIBanco());
            $objMdCorListaStatusBD->alterar($objMdCorListaStatusDTO);

            //Auditoria

        } catch (Exception $e) {
            throw new InfraException('Erro alterando projeto.', $e);
        }
    }

    protected function excluirControlado($arrObjMdCorListaStatusDTO)
    {
        try {

            //Valida Permissao
            SessaoSEI::getInstance()->validarPermissao('md_cor_lista_status_excluir');

            //Regras de Negocio
            //$objInfraException = new InfraException();

            //$objInfraException->lancarValidacoes();

            $objMdCorListaStatusBD = new MdCorListaStatusBD($this->getObjInfraIBanco());
            for ($i = 0; $i < count($arrObjMdCorListaStatusDTO); $i++) {
                $objMdCorListaStatusBD->excluir($arrObjMdCorListaStatusDTO[$i]);
            }

            //Auditoria

        } catch (Exception $e) {
            throw new InfraException('Erro excluindo projeto.', $e);
        }
    }

    protected function consultarConectado(MdCorListaStatusDTO $objMdCorListaStatusDTO)
    {
        try {


            //Valida Permissao
            SessaoSEI::getInstance()->validarPermissao('md_cor_lista_status_consultar');

            //Regras de Negocio
            //$objInfraException = new InfraException();

            //$objInfraException->lancarValidacoes();

            $objMdCorListaStatusBD = new MdCorListaStatusBD($this->getObjInfraIBanco());
            $ret = $objMdCorListaStatusBD->consultar($objMdCorListaStatusDTO);

            //Auditoria

            return $ret;
        } catch (Exception $e) {
            throw new InfraException('Erro consultando projeto.', $e);
        }
    }

    protected function listarConectado(MdCorListaStatusDTO $objMdCorListaStatusDTO)
    {

        try {

            $objMdCorListaStatusBD = new MdCorListaStatusBD($this->getObjInfraIBanco());
            $ret = $objMdCorListaStatusBD->listar($objMdCorListaStatusDTO);

            return $ret;

        } catch (Exception $e) {
            throw new InfraException('Erro listando projetos.', $e);
        }
    }

    protected function contarConectado(MdCorListaStatusDTO $objMdCorListaStatusDTO)
    {
        try {


            //Valida Permissao
            SessaoSEI::getInstance()->validarPermissao('md_cor_lista_status_listar');

            //Regras de Negocio
            //$objInfraException = new InfraException();

            //$objInfraException->lancarValidacoes();

            $objMdCorListaStatusBD = new MdCorListaStatusBD($this->getObjInfraIBanco());
            $ret = $objMdCorListaStatusBD->contar($objMdCorListaStatusDTO);

            //Auditoria

            return $ret;
        } catch (Exception $e) {
            throw new InfraException('Erro contando projetos.', $e);
        }
    }

    protected function getArrRelStatusImagemConectado()
    {
        $arr = array();
        $this->_cargaInicialListaStatus($arr);

        return $arr;
    }

    private function _cargaInicialListaStatus(&$arr)
    {
        $arr[0]['BDE']['ATEN��O: N�o consta mais no Manual do Web Service de Rastreamento dos Correios'] = 'rastreamento_sucesso.svg';
        $arr[0]['BDI']['ATEN��O: N�o consta mais no Manual do Web Service de Rastreamento dos Correios'] = 'rastreamento_sucesso.svg';
        $arr[0]['BDR']['ATEN��O: N�o consta mais no Manual do Web Service de Rastreamento dos Correios'] = 'rastreamento_sucesso.svg';
        $arr[0]['CD']['ATEN��O: N�o consta mais no Manual do Web Service de Rastreamento dos Correios'] = 'rastreamento_sucesso.svg';
        $arr[0]['CMT']['ATEN��O: N�o consta mais no Manual do Web Service de Rastreamento dos Correios'] = 'rastreamento_sucesso.svg';
        $arr[0]['CUN']['ATEN��O: N�o consta mais no Manual do Web Service de Rastreamento dos Correios'] = 'rastreamento_sucesso.svg';
        $arr[0]['DO']['ATEN��O: N�o consta mais no Manual do Web Service de Rastreamento dos Correios'] = 'rastreamento_em_transito.svg';
        $arr[0]['LDI']['ATEN��O: N�o consta mais no Manual do Web Service de Rastreamento dos Correios'] = 'rastreamento_em_transito.svg';
        $arr[0]['OEC']['ATEN��O: N�o consta mais no Manual do Web Service de Rastreamento dos Correios'] = 'rastreamento_em_transito.svg';
        $arr[0]['PO']['ATEN��O: N�o consta mais no Manual do Web Service de Rastreamento dos Correios'] = 'rastreamento_postagem.svg';
        $arr[0]['RO']['ATEN��O: N�o consta mais no Manual do Web Service de Rastreamento dos Correios'] = 'rastreamento_em_transito.svg';
        $arr[0]['TRI']['ATEN��O: N�o consta mais no Manual do Web Service de Rastreamento dos Correios'] = 'rastreamento_em_transito.svg';
        $arr[1]['BDE']['Objeto entregue ao destinat�rio'] = 'rastreamento_sucesso.svg';
        $arr[1]['BDI']['Objeto entregue ao destinat�rio'] = 'rastreamento_sucesso.svg';
        $arr[1]['BDR']['Objeto entregue ao destinat�rio'] = 'rastreamento_sucesso.svg';
        $arr[1]['BLQ']['Solicita��o de suspens�o de entrega recebida - Solicita��o realizada pelo contratante/remetente'] = 'rastreamento_em_transito.svg';
        $arr[1]['CD']['ATEN��O: N�o consta mais no Manual do Web Service de Rastreamento dos Correios'] = 'rastreamento_sucesso.svg';
        $arr[1]['CO']['Objeto coletado'] = 'rastreamento_em_transito.svg';
        $arr[1]['CUN']['ATEN��O: N�o consta mais no Manual do Web Service de Rastreamento dos Correios'] = 'rastreamento_sucesso.svg';
        $arr[1]['DO']['Objeto encaminhado '] = 'rastreamento_em_transito.svg';
        $arr[1]['EST']['Favor desconsiderar a informa��o anterior'] = 'rastreamento_em_transito.svg';
        $arr[1]['FC']['Objeto ser� devolvido por solicita��o do contratante/remetente - Em tratamento, aguarde.'] = 'rastreamento_em_transito.svg';
        $arr[1]['IDC']['ATEN��O: N�o consta mais no Manual do Web Service de Rastreamento dos Correios'] = 'rastreamento_em_transito.svg';
        $arr[1]['LDI']['Objeto aguardando retirada no endere�o indicado - Para retir�-lo, � preciso informar o c�digo do objeto e apresentar documenta��o que comprove ser o destinat�rio ou pessoa por ele oficialmente autorizada.'] = 'rastreamento_em_transito.svg';
        $arr[1]['PMT']['ATEN��O: N�o consta mais no Manual do Web Service de Rastreamento dos Correios'] = 'rastreamento_em_transito.svg';
        $arr[1]['PO']['Objeto postado'] = 'rastreamento_postagem.svg';
        $arr[1]['RO']['Objeto encaminhado '] = 'rastreamento_em_transito.svg';
        $arr[2]['BDE']['Carteiro n�o atendido - Entrega n�o realizada - Aguarde: objeto estar� dispon�vel para retirada na unidade a ser informada'] = 'rastreamento_em_transito.svg';
        $arr[2]['BDI']['Carteiro n�o atendido - Entrega n�o realizada - Aguarde: objeto estar� dispon�vel para retirada na unidade a ser informada'] = 'rastreamento_em_transito.svg';
        $arr[2]['BDR']['Carteiro n�o atendido - Entrega n�o realizada - Aguarde: objeto estar� dispon�vel para retirada na unidade a ser informada'] = 'rastreamento_em_transito.svg';
        $arr[2]['CD']['ATEN��O: N�o consta mais no Manual do Web Service de Rastreamento dos Correios'] = 'rastreamento_sucesso.svg';
        $arr[2]['DO']['Objeto encaminhado '] = 'rastreamento_em_transito.svg';
        $arr[2]['EST']['Favor desconsiderar a informa��o anterior'] = 'rastreamento_em_transito.svg';
        $arr[2]['FC']['Objeto com data de entrega agendada - Em tratamento, aguarde.'] = 'rastreamento_em_transito.svg';
        $arr[2]['IDC']['ATEN��O: N�o consta mais no Manual do Web Service de Rastreamento dos Correios'] = 'rastreamento_em_transito.svg';
        $arr[2]['LDI']['Objeto dispon�vel para retirada em Caixa Postal'] = 'rastreamento_em_transito.svg';
        $arr[3]['BDE']['Remetente n�o retirou objeto na Unidade dos Correios - Objeto em an�lise de destina��o'] = 'rastreamento_em_transito.svg';
        $arr[3]['BDI']['Remetente n�o retirou objeto na Unidade dos Correios - Objeto em an�lise de destina��o'] = 'rastreamento_em_transito.svg';
        $arr[3]['BDR']['Remetente n�o retirou objeto na Unidade dos Correios - Objeto em an�lise de destina��o'] = 'rastreamento_em_transito.svg';
        $arr[3]['CD']['ATEN��O: N�o consta mais no Manual do Web Service de Rastreamento dos Correios'] = 'rastreamento_sucesso.svg';
        $arr[3]['EST']['Favor desconsiderar a informa��o anterior'] = 'rastreamento_em_transito.svg';
        $arr[3]['FC']['Objeto mal encaminhado - Encaminhamento a ser corrigido'] = 'rastreamento_em_transito.svg';
        $arr[3]['IDC']['ATEN��O: N�o consta mais no Manual do Web Service de Rastreamento dos Correios'] = 'rastreamento_em_transito.svg';
        $arr[3]['LDI']['Objeto aguardando retirada no endere�o indicado - Para retir�-lo, � preciso informar o c�digo do objeto e apresentar documenta��o que comprove ser o destinat�rio ou pessoa por ele oficialmente autorizada.'] = 'rastreamento_em_transito.svg';
        $arr[4]['BDE']['Cliente recusou-se a receber o objeto - Entrega n�o realizada - Objeto ser� devolvido ao remetente'] = 'rastreamento_em_transito.svg';
        $arr[4]['BDI']['Cliente recusou-se a receber o objeto - Entrega n�o realizada - Objeto ser� devolvido ao remetente'] = 'rastreamento_em_transito.svg';
        $arr[4]['BDR']['Cliente recusou-se a receber o objeto - Entrega n�o realizada - Objeto ser� devolvido ao remetente'] = 'rastreamento_em_transito.svg';
        $arr[4]['EST']['Favor desconsiderar a informa��o anterior'] = 'rastreamento_em_transito.svg';
        $arr[4]['FC']['Endere�o incorreto - Entrega n�o realizada - Objeto sujeito a atraso na entrega ou a devolu��o ao remetente'] = 'rastreamento_em_transito.svg';
        $arr[4]['IDC']['ATEN��O: N�o consta mais no Manual do Web Service de Rastreamento dos Correios'] = 'rastreamento_em_transito.svg';
        $arr[5]['BDE']['A entrega n�o pode ser efetuada - Objeto ser� devolvido ao remetente'] = 'rastreamento_em_transito.svg';
        $arr[5]['BDI']['A entrega n�o pode ser efetuada - Objeto ser� devolvido ao remetente'] = 'rastreamento_em_transito.svg';
        $arr[5]['BDR']['A entrega n�o pode ser efetuada - Objeto ser� devolvido ao remetente'] = 'rastreamento_em_transito.svg';
        $arr[5]['EST']['Favor desconsiderar a informa��o anterior'] = 'rastreamento_em_transito.svg';
        $arr[5]['FC']['Objeto devolvido aos Correios'] = 'rastreamento_em_transito.svg';
        $arr[5]['IDC']['ATEN��O: N�o consta mais no Manual do Web Service de Rastreamento dos Correios'] = 'rastreamento_em_transito.svg';
        $arr[6]['BDE']['Cliente desconhecido no local - Entrega n�o realizada - Objeto ser� devolvido ao remetente'] = 'rastreamento_em_transito.svg';
        $arr[6]['BDI']['Cliente desconhecido no local - Entrega n�o realizada - Objeto ser� devolvido ao remetente'] = 'rastreamento_em_transito.svg';
        $arr[6]['BDR']['Cliente desconhecido no local - Entrega n�o realizada - Objeto ser� devolvido ao remetente'] = 'rastreamento_em_transito.svg';
        $arr[6]['EST']['Favor desconsiderar a informa��o anterior'] = 'rastreamento_em_transito.svg';
        $arr[6]['IDC']['ATEN��O: N�o consta mais no Manual do Web Service de Rastreamento dos Correios'] = 'rastreamento_em_transito.svg';
        $arr[7]['BDE']['Endere�o incorreto - Entrega n�o realizada - Objeto ser� devolvido ao remetente'] = 'rastreamento_em_transito.svg';
        $arr[7]['BDI']['Endere�o incorreto - Entrega n�o realizada - Objeto ser� devolvido ao remetente'] = 'rastreamento_em_transito.svg';
        $arr[7]['BDR']['Endere�o incorreto - Entrega n�o realizada - Objeto ser� devolvido ao remetente'] = 'rastreamento_em_transito.svg';
        $arr[7]['FC']['Empresa sem expediente - Entrega n�o realizada - Entrega dever� ocorrer no pr�ximo dia �til'] = 'rastreamento_em_transito.svg';
        $arr[7]['IDC']['ATEN��O: N�o consta mais no Manual do Web Service de Rastreamento dos Correios'] = 'rastreamento_em_transito.svg';
        $arr[8]['BDE']['Endere�o incorreto - Entrega n�o realizada - Objeto ser� devolvido ao remetente'] = 'rastreamento_em_transito.svg';
        $arr[8]['BDI']['Endere�o incorreto - Entrega n�o realizada - Objeto ser� devolvido ao remetente'] = 'rastreamento_em_transito.svg';
        $arr[8]['BDR']['Endere�o incorreto - Entrega n�o realizada - Objeto ser� devolvido ao remetente'] = 'rastreamento_em_transito.svg';
        $arr[9]['BDE']['Objeto ainda n�o chegou � unidade - Por favor, aguarde'] = 'rastreamento_em_transito.svg';
        $arr[9]['BDI']['Objeto ainda n�o chegou � unidade - Por favor, aguarde'] = 'rastreamento_em_transito.svg';
        $arr[9]['BDR']['Objeto ainda n�o chegou � unidade - Por favor, aguarde'] = 'rastreamento_em_transito.svg';
        $arr[9]['EST']['Favor desconsiderar a informa��o anterior'] = 'rastreamento_em_transito.svg';
        $arr[9]['LDE']['ATEN��O: N�o consta mais no Manual do Web Service de Rastreamento dos Correios'] = 'rastreamento_em_transito.svg';
        $arr[9]['PO']['Objeto postado ap�s o hor�rio limite da unidade - Sujeito a encaminhamento no pr�ximo dia �til'] = 'rastreamento_postagem.svg';
        $arr[10]['BDE']['Cliente mudou-se - Entrega n�o realizada - Objeto ser� devolvido ao remetente'] = 'rastreamento_em_transito.svg';
        $arr[10]['BDI']['Cliente mudou-se - Entrega n�o realizada - Objeto ser� devolvido ao remetente'] = 'rastreamento_em_transito.svg';
        $arr[10]['BDR']['Cliente mudou-se - Entrega n�o realizada - Objeto ser� devolvido ao remetente'] = 'rastreamento_em_transito.svg';
        $arr[12]['BDE']['Remetente n�o retirou objeto na Unidade dos Correios - Objeto em an�lise de destina��o'] = 'rastreamento_cancelado.svg';
        $arr[12]['BDI']['Remetente n�o retirou objeto na Unidade dos Correios - Objeto em an�lise de destina��o'] = 'rastreamento_cancelado.svg';
        $arr[12]['BDR']['Remetente n�o retirou objeto na Unidade dos Correios - Objeto em an�lise de destina��o'] = 'rastreamento_cancelado.svg';
        $arr[14]['LDI']['Objeto encaminhado para retirada no endere�o indicado - Para retir�-lo, � preciso informar o c�digo do objeto.'] = 'rastreamento_em_transito.svg';
        $arr[15]['PAR']['Objeto recebido em'] = 'rastreamento_em_transito.svg';
        $arr[16]['PAR']['Objeto recebido pelos Correios do Brasil'] = 'rastreamento_em_transito.svg';
        $arr[17]['PAR']['Aguardando pagamento'] = 'rastreamento_em_transito.svg';
        $arr[18]['PAR']['Objeto recebido na unidade de exporta��o no pa�s de origem'] = 'rastreamento_em_transito.svg';
        $arr[19]['BDE']['Endere�o incorreto - Entrega n�o realizada - Objeto sujeito a atraso na entrega ou a devolu��o ao remetente'] = 'rastreamento_em_transito.svg';
        $arr[19]['BDI']['Endere�o incorreto - Entrega n�o realizada - Objeto sujeito a atraso na entrega ou a devolu��o ao remetente'] = 'rastreamento_em_transito.svg';
        $arr[19]['BDR']['Endere�o incorreto - Entrega n�o realizada - Objeto sujeito a atraso na entrega ou a devolu��o ao remetente'] = 'rastreamento_em_transito.svg';
        $arr[20]['BDE']['Carteiro n�o atendido - Entrega n�o realizada - Ser� realizada nova tentativa de entrega'] = 'rastreamento_em_transito.svg';
        $arr[20]['BDI']['Carteiro n�o atendido - Entrega n�o realizada - Ser� realizada nova tentativa de entrega'] = 'rastreamento_em_transito.svg';
        $arr[20]['BDR']['Carteiro n�o atendido - Entrega n�o realizada - Ser� realizada nova tentativa de entrega'] = 'rastreamento_em_transito.svg';
        $arr[21]['BDE']['Carteiro n�o atendido - Entrega n�o realizada - Objeto ser� devolvido ao remetente'] = 'rastreamento_em_transito.svg';
        $arr[21]['BDI']['Carteiro n�o atendido - Entrega n�o realizada - Objeto ser� devolvido ao remetente'] = 'rastreamento_em_transito.svg';
        $arr[21]['BDR']['Carteiro n�o atendido - Entrega n�o realizada - Objeto ser� devolvido ao remetente'] = 'rastreamento_em_transito.svg';
        $arr[22]['BDE']['Objeto devolvido aos Correios'] = 'rastreamento_em_transito.svg';
        $arr[22]['BDI']['Objeto devolvido aos Correios'] = 'rastreamento_em_transito.svg';
        $arr[22]['BDR']['Objeto devolvido aos Correios'] = 'rastreamento_em_transito.svg';
        $arr[23]['BDE']['Objeto entregue ao remetente'] = 'rastreamento_cancelado.svg';
        $arr[23]['BDI']['Objeto entregue ao remetente'] = 'rastreamento_cancelado.svg';
        $arr[23]['BDR']['Objeto entregue ao remetente'] = 'rastreamento_cancelado.svg';
        $arr[24]['BDE']['Objeto dispon�vel para retirada em Caixa Postal'] = 'rastreamento_em_transito.svg';
        $arr[24]['BDI']['Objeto dispon�vel para retirada em Caixa Postal'] = 'rastreamento_em_transito.svg';
        $arr[24]['BDR']['Objeto dispon�vel para retirada em Caixa Postal'] = 'rastreamento_em_transito.svg';
        $arr[25]['BDE']['Empresa sem expediente - Entrega n�o realizada - Entrega dever� ocorrer no pr�ximo dia �til'] = 'rastreamento_em_transito.svg';
        $arr[25]['BDI']['Empresa sem expediente - Entrega n�o realizada - Entrega dever� ocorrer no pr�ximo dia �til'] = 'rastreamento_em_transito.svg';
        $arr[25]['BDR']['Empresa sem expediente - Entrega n�o realizada - Entrega dever� ocorrer no pr�ximo dia �til'] = 'rastreamento_em_transito.svg';
        $arr[26]['BDE']['Destinat�rio n�o retirou objeto no prazo - Objeto ser� devolvido ao remetente'] = 'rastreamento_em_transito.svg';
        $arr[26]['BDI']['Destinat�rio n�o retirou objeto no prazo - Objeto ser� devolvido ao remetente'] = 'rastreamento_em_transito.svg';
        $arr[26]['BDR']['Destinat�rio n�o retirou objeto no prazo - Objeto ser� devolvido ao remetente'] = 'rastreamento_em_transito.svg';
        $arr[28]['BDE']['Objeto e/ou conte�do avariado'] = 'rastreamento_em_transito.svg';
        $arr[28]['BDI']['Objeto e/ou conte�do avariado'] = 'rastreamento_em_transito.svg';
        $arr[28]['BDR']['Objeto e/ou conte�do avariado'] = 'rastreamento_em_transito.svg';
        $arr[32]['BDE']['Objeto com data de entrega agendada'] = 'rastreamento_em_transito.svg';
        $arr[32]['BDI']['Objeto com data de entrega agendada'] = 'rastreamento_em_transito.svg';
        $arr[32]['BDR']['Objeto com data de entrega agendada'] = 'rastreamento_em_transito.svg';
        $arr[33]['BDE']['A entrega n�o pode ser efetuada - Destinat�rio n�o apresentou documento exigido'] = 'rastreamento_em_transito.svg';
        $arr[33]['BDI']['A entrega n�o pode ser efetuada - Destinat�rio n�o apresentou documento exigido'] = 'rastreamento_em_transito.svg';
        $arr[33]['BDR']['A entrega n�o pode ser efetuada - Destinat�rio n�o apresentou documento exigido'] = 'rastreamento_em_transito.svg';
        $arr[34]['BDE']['A entrega n�o pode ser efetuada - Logradouro com numera��o irregular - Objeto sujeito a atraso na entrega ou a devolu��o ao remetente'] = 'rastreamento_em_transito.svg';
        $arr[34]['BDI']['A entrega n�o pode ser efetuada - Logradouro com numera��o irregular - Objeto sujeito a atraso na entrega ou a devolu��o ao remetente'] = 'rastreamento_em_transito.svg';
        $arr[34]['BDR']['A entrega n�o pode ser efetuada - Logradouro com numera��o irregular - Objeto sujeito a atraso na entrega ou a devolu��o ao remetente'] = 'rastreamento_em_transito.svg';
        $arr[35]['BDE']['Coleta ou entrega de objeto n�o efetuada - Ser� realizada nova tentativa de coleta ou entrega'] = 'rastreamento_em_transito.svg';
        $arr[35]['BDI']['Coleta ou entrega de objeto n�o efetuada - Ser� realizada nova tentativa de coleta ou entrega'] = 'rastreamento_em_transito.svg';
        $arr[35]['BDR']['Coleta ou entrega de objeto n�o efetuada - Ser� realizada nova tentativa de coleta ou entrega'] = 'rastreamento_em_transito.svg';
        $arr[36]['BDE']['Coleta ou entrega de objeto n�o efetuada - Objeto ser� devolvido ao remetente'] = 'rastreamento_em_transito.svg';
        $arr[36]['BDI']['Coleta ou entrega de objeto n�o efetuada - Objeto ser� devolvido ao remetente'] = 'rastreamento_em_transito.svg';
        $arr[36]['BDR']['Coleta ou entrega de objeto n�o efetuada - Objeto ser� devolvido ao remetente'] = 'rastreamento_em_transito.svg';
        $arr[37]['BDE']['Objeto e/ou conte�do avariado por acidente com ve�culo'] = 'rastreamento_em_transito.svg';
        $arr[37]['BDI']['Objeto e/ou conte�do avariado por acidente com ve�culo'] = 'rastreamento_em_transito.svg';
        $arr[37]['BDR']['Objeto e/ou conte�do avariado por acidente com ve�culo'] = 'rastreamento_em_transito.svg';
        $arr[38]['BDE']['Objeto endere�ado � empresa falida - Objeto ser� encaminhado para entrega ao administrador judicial'] = 'rastreamento_em_transito.svg';
        $arr[38]['BDI']['Objeto endere�ado � empresa falida - Objeto ser� encaminhado para entrega ao administrador judicial'] = 'rastreamento_em_transito.svg';
        $arr[38]['BDR']['Objeto endere�ado � empresa falida - Objeto ser� encaminhado para entrega ao administrador judicial'] = 'rastreamento_em_transito.svg';
        $arr[40]['BDE']['N�o foi autorizada a entrada do objeto no pa�s pelos �rg�os fiscalizadores - Objeto em an�lise de destina��o - poder� ser devolvido ao remetente, encaminhado para refugo ou apreendido'] = 'rastreamento_em_transito.svg';
        $arr[40]['BDI']['N�o foi autorizada a entrada do objeto no pa�s pelos �rg�os fiscalizadores - Objeto em an�lise de destina��o - poder� ser devolvido ao remetente, encaminhado para refugo ou apreendido'] = 'rastreamento_em_transito.svg';
        $arr[40]['BDR']['N�o foi autorizada a entrada do objeto no pa�s pelos �rg�os fiscalizadores - Objeto em an�lise de destina��o - poder� ser devolvido ao remetente, encaminhado para refugo ou apreendido'] = 'rastreamento_em_transito.svg';
        $arr[41]['BDE']['A entrega do objeto est� condicionada � composi��o do lote'] = 'rastreamento_em_transito.svg';
        $arr[41]['BDI']['A entrega do objeto est� condicionada � composi��o do lote'] = 'rastreamento_em_transito.svg';
        $arr[41]['BDR']['A entrega do objeto est� condicionada � composi��o do lote'] = 'rastreamento_em_transito.svg';
        $arr[42]['BDE']['Lote de objetos incompleto - Em devolu��o ao remetente'] = 'rastreamento_em_transito.svg';
        $arr[42]['BDI']['Lote de objetos incompleto - Em devolu��o ao remetente'] = 'rastreamento_em_transito.svg';
        $arr[42]['BDR']['Lote de objetos incompleto - Em devolu��o ao remetente'] = 'rastreamento_em_transito.svg';
        $arr[43]['BDE']['Objeto apreendido por �rg�o de fiscaliza��o - Objeto est� em poder da autoridade competente'] = 'rastreamento_cancelado.svg';
        $arr[43]['BDI']['Objeto apreendido por �rg�o de fiscaliza��o - Objeto est� em poder da autoridade competente'] = 'rastreamento_cancelado.svg';
        $arr[43]['BDR']['Objeto apreendido por �rg�o de fiscaliza��o - Objeto est� em poder da autoridade competente'] = 'rastreamento_cancelado.svg';
        $arr[45]['BDE']['Objeto recebido na unidade de distribui��o - Entrega prevista para o pr�ximo dia �til'] = 'rastreamento_em_transito.svg';
        $arr[45]['BDI']['Objeto recebido na unidade de distribui��o - Entrega prevista para o pr�ximo dia �til'] = 'rastreamento_em_transito.svg';
        $arr[45]['BDR']['Objeto recebido na unidade de distribui��o - Entrega prevista para o pr�ximo dia �til'] = 'rastreamento_em_transito.svg';
        $arr[46]['BDE']['Tentativa de entrega n�o efetuada - Entrega prevista para o pr�ximo dia �til'] = 'rastreamento_em_transito.svg';
        $arr[46]['BDI']['Tentativa de entrega n�o efetuada - Entrega prevista para o pr�ximo dia �til'] = 'rastreamento_em_transito.svg';
        $arr[46]['BDR']['Tentativa de entrega n�o efetuada - Entrega prevista para o pr�ximo dia �til'] = 'rastreamento_em_transito.svg';
        $arr[47]['BDE']['Sa�da para entrega cancelada - Ser� efetuada nova sa�da para entrega'] = 'rastreamento_em_transito.svg';
        $arr[47]['BDI']['Sa�da para entrega cancelada - Ser� efetuada nova sa�da para entrega'] = 'rastreamento_em_transito.svg';
        $arr[47]['BDR']['Sa�da para entrega cancelada - Ser� efetuada nova sa�da para entrega'] = 'rastreamento_em_transito.svg';
        $arr[48]['BDE']['Retirada em Unidade dos Correios n�o autorizada pelo remetente - Objeto ser� devolvido ao remetente'] = 'rastreamento_em_transito.svg';
        $arr[48]['BDI']['Retirada em Unidade dos Correios n�o autorizada pelo remetente - Objeto ser� devolvido ao remetente'] = 'rastreamento_em_transito.svg';
        $arr[48]['BDR']['Retirada em Unidade dos Correios n�o autorizada pelo remetente - Objeto ser� devolvido ao remetente'] = 'rastreamento_em_transito.svg';
        $arr[49]['BDE']['As dimens�es do objeto impossibilitam o tratamento e a entrega - O objeto ser� devolvido ao remetente'] = 'rastreamento_em_transito.svg';
        $arr[49]['BDI']['As dimens�es do objeto impossibilitam o tratamento e a entrega - O objeto ser� devolvido ao remetente'] = 'rastreamento_em_transito.svg';
        $arr[49]['BDR']['As dimens�es do objeto impossibilitam o tratamento e a entrega - O objeto ser� devolvido ao remetente'] = 'rastreamento_em_transito.svg';
        $arr[50]['BDE']['Objeto roubado'] = 'rastreamento_cancelado.svg';
        $arr[50]['BDI']['Objeto roubado'] = 'rastreamento_cancelado.svg';
        $arr[50]['BDR']['Objeto roubado'] = 'rastreamento_cancelado.svg';
        $arr[51]['BDE']['Objeto roubado'] = 'rastreamento_cancelado.svg';
        $arr[51]['BDI']['Objeto roubado'] = 'rastreamento_cancelado.svg';
        $arr[51]['BDR']['Objeto roubado'] = 'rastreamento_cancelado.svg';
        $arr[52]['BDE']['Objeto roubado'] = 'rastreamento_cancelado.svg';
        $arr[52]['BDI']['Objeto roubado'] = 'rastreamento_cancelado.svg';
        $arr[52]['BDR']['Objeto roubado'] = 'rastreamento_cancelado.svg';
        $arr[53]['BDE']['Objeto reimpresso e reenviado'] = 'rastreamento_em_transito.svg';
        $arr[53]['BDI']['Objeto reimpresso e reenviado'] = 'rastreamento_em_transito.svg';
        $arr[53]['BDR']['Objeto reimpresso e reenviado'] = 'rastreamento_em_transito.svg';
        $arr[54]['BDE']['Aguardando a guia de recolhimento para pagamento do ICMS Importa��o - Por favor, aguarde.'] = 'rastreamento_em_transito.svg';
        $arr[54]['BDI']['Aguardando a guia de recolhimento para pagamento do ICMS Importa��o - Por favor, aguarde.'] = 'rastreamento_em_transito.svg';
        $arr[54]['BDR']['Aguardando a guia de recolhimento para pagamento do ICMS Importa��o - Por favor, aguarde.'] = 'rastreamento_em_transito.svg';
        $arr[55]['BDE']['Solicita��o de revis�o do tributo - Acesse Minhas Importa��es para acompanhamento do pedido de revis�o'] = 'rastreamento_em_transito.svg';
        $arr[55]['BDI']['Solicita��o de revis�o do tributo - Acesse Minhas Importa��es para acompanhamento do pedido de revis�o'] = 'rastreamento_em_transito.svg';
        $arr[55]['BDR']['Solicita��o de revis�o do tributo - Acesse Minhas Importa��es para acompanhamento do pedido de revis�o'] = 'rastreamento_em_transito.svg';
        $arr[56]['BDE']['Objeto com declara��o aduaneira ausente ou incorreta - Aguarde: objeto em tratamento e sujeito a devolu��o'] = 'rastreamento_em_transito.svg';
        $arr[56]['BDI']['Objeto com declara��o aduaneira ausente ou incorreta - Aguarde: objeto em tratamento e sujeito a devolu��o'] = 'rastreamento_em_transito.svg';
        $arr[56]['BDR']['Objeto com declara��o aduaneira ausente ou incorreta - Aguarde: objeto em tratamento e sujeito a devolu��o'] = 'rastreamento_em_transito.svg';
        $arr[57]['BDE']['Revis�o de tributo conclu�da - Objeto liberado'] = 'rastreamento_em_transito.svg';
        $arr[57]['BDI']['Revis�o de tributo conclu�da - Objeto liberado'] = 'rastreamento_em_transito.svg';
        $arr[57]['BDR']['Revis�o de tributo conclu�da - Objeto liberado'] = 'rastreamento_em_transito.svg';
        $arr[58]['BDE']['Revis�o de tributo conclu�da - Tributo alterado - O valor do tributo pode ter aumentado ou diminu�do'] = 'rastreamento_em_transito.svg';
        $arr[58]['BDI']['Revis�o de tributo conclu�da - Tributo alterado - O valor do tributo pode ter aumentado ou diminu�do'] = 'rastreamento_em_transito.svg';
        $arr[58]['BDR']['Revis�o de tributo conclu�da - Tributo alterado - O valor do tributo pode ter aumentado ou diminu�do'] = 'rastreamento_em_transito.svg';
        $arr[59]['BDE']['Revis�o de tributo conclu�da - Tributo mantido - Poder� haver incid�ncia de juros e multa'] = 'rastreamento_em_transito.svg';
        $arr[59]['BDI']['Revis�o de tributo conclu�da - Tributo mantido - Poder� haver incid�ncia de juros e multa'] = 'rastreamento_em_transito.svg';
        $arr[59]['BDR']['Revis�o de tributo conclu�da - Tributo mantido - Poder� haver incid�ncia de juros e multa'] = 'rastreamento_em_transito.svg';
        $arr[66]['BDE']['�rea com distribui��o sujeita a prazo diferenciado - Restri��o de entrega domiciliar tempor�ria'] = 'rastreamento_em_transito.svg';
        $arr[66]['BDI']['�rea com distribui��o sujeita a prazo diferenciado - Restri��o de entrega domiciliar tempor�ria'] = 'rastreamento_em_transito.svg';
        $arr[66]['BDR']['�rea com distribui��o sujeita a prazo diferenciado - Restri��o de entrega domiciliar tempor�ria'] = 'rastreamento_em_transito.svg';
        $arr[69]['BDE']['Objeto ainda n�o chegou � unidade - Por favor, aguarde'] = 'rastreamento_em_transito.svg';
        $arr[69]['BDI']['Objeto ainda n�o chegou � unidade - Por favor, aguarde'] = 'rastreamento_em_transito.svg';
        $arr[69]['BDR']['Objeto ainda n�o chegou � unidade - Por favor, aguarde'] = 'rastreamento_em_transito.svg';
        $arr[80]['BDE']['Objeto n�o localizado no fluxo postal'] = 'rastreamento_cancelado.svg';
        $arr[80]['BDI']['Objeto n�o localizado no fluxo postal'] = 'rastreamento_cancelado.svg';
        $arr[80]['BDR']['Objeto n�o localizado no fluxo postal'] = 'rastreamento_cancelado.svg';
        $arr[1]['OEC']['Objeto saiu para entrega ao destinat�rio'] = 'rastreamento_em_transito.svg';
        $arr[9]['OEC']['Objeto saiu para entrega ao remetente'] = 'rastreamento_em_transito.svg';
        $arr[1]['CMR']['ATEN��O: N�o consta mais no Manual do Web Service de Rastreamento dos Correios'] = 'rastreamento_em_transito.svg';
        $arr[18]['BDE']['Carteiro n�o atendido - Entrega n�o realizada - Ser� realizada nova tentativa de entrega no s�bado'] = 'rastreamento_em_transito.svg';
        $arr[8]['FC']['�rea com distribui��o sujeita a prazo diferenciado - Restri��o de entrega domiciliar tempor�ria'] = 'rastreamento_em_transito.svg';
        $arr[39]['BDI']['A entrega n�o pode ser efetuada - Objeto em an�lise de destina��o'] = 'rastreamento_em_transito.svg';
        $arr[10]['FC']['Objeto recebido na unidade de distribui��o - Entrega dever� ocorrer no pr�ximo dia �til'] = 'rastreamento_em_transito.svg';
        $arr[39]['BDR']['A entrega n�o pode ser efetuada - Objeto em an�lise de destina��o'] = 'rastreamento_em_transito.svg';
        $arr[11]['LDI']['Objeto encaminhado para retirada no endere�o indicado - Para retir�-lo, � preciso informar o c�digo do objeto.'] = 'rastreamento_em_transito.svg';
        $arr[4]['LDI']['Objeto aguardando retirada no endere�o indicado - Para retir�-lo, � preciso informar o c�digo do objeto e apresentar documenta��o que comprove ser o destinat�rio ou pessoa por ele oficialmente autorizada.'] = 'rastreamento_em_transito.svg';
        $arr[21]['BLQ']['Solicita��o de suspens�o de entrega recebida'] = 'rastreamento_em_transito.svg';
        $arr[68]['BDE']['Objeto entregue na Caixa de Correios Inteligente'] = 'rastreamento_sucesso.svg';
        $arr[13]['BDE']['Objeto atingido por inc�ndio em unidade operacional'] = 'rastreamento_em_transito.svg';
        $arr[14]['BDE']['Desist�ncia de postagem pelo remetente'] = 'rastreamento_em_transito.svg';
        $arr[15]['BDE']['Recebido na unidade de distribui��o - Por determina��o judicial o objeto ser� entregue em at� 7 dias'] = 'rastreamento_em_transito.svg';
        $arr[29]['BDE']['Objeto n�o localizado'] = 'rastreamento_em_transito.svg';
        $arr[30]['BDE']['Sa�da n�o efetuada - Em tratamento, aguarde.'] = 'rastreamento_em_transito.svg';
        $arr[39]['BDE']['A entrega n�o pode ser efetuada - Objeto em an�lise de destina��o'] = 'rastreamento_em_transito.svg';
        $arr[44]['BDE']['Aguardando �rg�o anuente apresentar documenta��o fiscal'] = 'rastreamento_em_transito.svg';
        $arr[60]['BDE']['Objeto encontra-se aguardando prazo para refugo'] = 'rastreamento_em_transito.svg';
        $arr[61]['BDE']['Objeto refugado - Devolu��o proibida por �rg�o anuente ou n�o contratada pelo remetente'] = 'rastreamento_em_transito.svg';
        $arr[62]['BDE']['Em devolu��o � origem por determina��o da Receita Federal - Objeto poder� ser devolvido � origem ou encaminhado para refugo de acordo com orienta��o do remetente'] = 'rastreamento_em_transito.svg';
        $arr[67]['BDE']['Objeto entregue ao destinat�rio - Entrega realizada em endere�o vizinho, conforme autorizado pelo remetente'] = 'rastreamento_sucesso.svg';
        $arr[70]['BDE']['Objeto entregue ao destinat�rio'] = 'rastreamento_sucesso.svg';
        $arr[71]['BDE']['Objeto apreendido por: ANVISA/VIGILANCIA SANITARIA - O objeto est� em poder da autoridade competente'] = 'rastreamento_cancelado.svg';
        $arr[72]['BDE']['Objeto apreendido por: BOMBEIROS - O objeto est� em poder da autoridade competente'] = 'rastreamento_cancelado.svg';
        $arr[73]['BDE']['Objeto apreendido por: EXERCITO - O objeto est� em poder da autoridade competente'] = 'rastreamento_cancelado.svg';
        $arr[74]['BDE']['Objeto apreendido por: IBAMA - O objeto est� em poder da autoridade competente'] = 'rastreamento_cancelado.svg';
        $arr[75]['BDE']['Objeto apreendido por: POLICIA FEDERAL - O objeto est� em poder da autoridade competente'] = 'rastreamento_cancelado.svg';
        $arr[76]['BDE']['Objeto apreendido por: POLICIA CIVIL/MILITAR - O objeto est� em poder da autoridade competente'] = 'rastreamento_cancelado.svg';
        $arr[77]['BDE']['Evento RFB - Aguardando DTRAT'] = 'rastreamento_em_transito.svg';
        $arr[79]['BDE']['POSTAGEM INEXISTENTE (RESERVADO DERAT)'] = 'rastreamento_em_transito.svg';
        $arr[81]['BDE']['N�o foi autorizada a sa�da do objeto do pa�s pelos �rg�os fiscalizadores - Objeto em an�lise de destina��o - poder� ser devolvido ao remetente, encaminhado para refugo ou apreendido'] = 'rastreamento_em_transito.svg';
        $arr[82]['BDE']['Necess�rio complementar endere�o de entrega - Acesse o Fale Conosco > Reclama��o > Objeto em processo de desembara�o'] = 'rastreamento_em_transito.svg';
        $arr[83]['BDE']['A��o necess�ria � libera��o do objeto n�o realizada pelo cliente - Objeto em an�lise de destina��o - poder� ser devolvido ao remetente, encaminhado para refugo ou apreendido'] = 'rastreamento_em_transito.svg';
        $arr[84]['BDE']['Verificando situa��o do objeto - Por favor, aguarde'] = 'rastreamento_em_transito.svg';
        $arr[85]['BDE']['C�digo do objeto fora do padr�o - Objeto ser� entregue sem rastreamento'] = 'rastreamento_em_transito.svg';
        $arr[86]['BDE']['Objeto com conte�do avariado - Sem condi��es de entrega'] = 'rastreamento_em_transito.svg';
        $arr[87]['BDE']['Objeto destinado a outro pa�s'] = 'rastreamento_em_transito.svg';
        $arr[89]['BDE']['Localidade desconhecida no Brasil - Objeto ser� devolvido ao remetente'] = 'rastreamento_em_transito.svg';
        $arr[98]['BDE']['Objeto n�o localizado no fluxo postal'] = 'rastreamento_em_transito.svg';
        $arr[13]['BDI']['Objeto atingido por inc�ndio em unidade operacional'] = 'rastreamento_em_transito.svg';
        $arr[14]['BDI']['Desist�ncia de postagem pelo remetente'] = 'rastreamento_em_transito.svg';
        $arr[15]['BDI']['Recebido na unidade de distribui��o - Por determina��o judicial o objeto ser� entregue em at� 7 dias'] = 'rastreamento_em_transito.svg';
        $arr[18]['BDI']['Carteiro n�o atendido - Entrega n�o realizada - Ser� realizada nova tentativa de entrega no s�bado'] = 'rastreamento_em_transito.svg';
        $arr[29]['BDI']['Objeto n�o localizado'] = 'rastreamento_em_transito.svg';
        $arr[30]['BDI']['Sa�da n�o efetuada - Em tratamento, aguarde.'] = 'rastreamento_em_transito.svg';
        $arr[44]['BDI']['Aguardando �rg�o anuente apresentar documenta��o fiscal'] = 'rastreamento_em_transito.svg';
        $arr[60]['BDI']['Objeto encontra-se aguardando prazo para refugo'] = 'rastreamento_em_transito.svg';
        $arr[61]['BDI']['Objeto refugado - Devolu��o proibida por �rg�o anuente ou n�o contratada pelo remetente'] = 'rastreamento_em_transito.svg';
        $arr[62]['BDI']['Em devolu��o � origem por determina��o da Receita Federal - Objeto poder� ser devolvido � origem ou encaminhado para refugo de acordo com orienta��o do remetente'] = 'rastreamento_em_transito.svg';
        $arr[67]['BDI']['Objeto entregue ao destinat�rio - Entrega realizada em endere�o vizinho, conforme autorizado pelo remetente'] = 'rastreamento_sucesso.svg';
        $arr[68]['BDI']['Objeto entregue na Caixa de Correios Inteligente'] = 'rastreamento_sucesso.svg';
        $arr[70]['BDI']['Objeto entregue ao destinat�rio'] = 'rastreamento_sucesso.svg';
        $arr[71]['BDI']['Objeto apreendido por: ANVISA/VIGILANCIA SANITARIA - O objeto est� em poder da autoridade competente'] = 'rastreamento_cancelado.svg';
        $arr[72]['BDI']['Objeto apreendido por: BOMBEIROS - O objeto est� em poder da autoridade competente'] = 'rastreamento_cancelado.svg';
        $arr[73]['BDI']['Objeto apreendido por: EXERCITO - O objeto est� em poder da autoridade competente'] = 'rastreamento_cancelado.svg';
        $arr[74]['BDI']['Objeto apreendido por: IBAMA - O objeto est� em poder da autoridade competente'] = 'rastreamento_cancelado.svg';
        $arr[75]['BDI']['Objeto apreendido por: POLICIA FEDERAL - O objeto est� em poder da autoridade competente'] = 'rastreamento_cancelado.svg';
        $arr[76]['BDI']['Objeto apreendido por: POLICIA CIVIL/MILITAR - O objeto est� em poder da autoridade competente'] = 'rastreamento_cancelado.svg';
        $arr[77]['BDI']['Evento RFB - Aguardando DTRAT'] = 'rastreamento_em_transito.svg';
        $arr[81]['BDI']['N�o foi autorizada a sa�da do objeto do pa�s pelos �rg�os fiscalizadores - Objeto em an�lise de destina��o - poder� ser devolvido ao remetente, encaminhado para refugo ou apreendido'] = 'rastreamento_em_transito.svg';
        $arr[82]['BDI']['Necess�rio complementar endere�o de entrega - Acesse o Fale Conosco > Reclama��o > Objeto em processo de desembara�o'] = 'rastreamento_em_transito.svg';
        $arr[83]['BDI']['A��o necess�ria � libera��o do objeto n�o realizada pelo cliente - Objeto em an�lise de destina��o - poder� ser devolvido ao remetente, encaminhado para refugo ou apreendido'] = 'rastreamento_em_transito.svg';
        $arr[84]['BDI']['Verificando situa��o do objeto - Por favor, aguarde'] = 'rastreamento_em_transito.svg';
        $arr[85]['BDI']['C�digo do objeto fora do padr�o - Objeto ser� entregue sem rastreamento'] = 'rastreamento_em_transito.svg';
        $arr[86]['BDI']['Objeto com conte�do avariado - Sem condi��es de entrega'] = 'rastreamento_em_transito.svg';
        $arr[87]['BDI']['Objeto destinado a outro pa�s'] = 'rastreamento_em_transito.svg';
        $arr[89]['BDI']['Localidade desconhecida no Brasil - O objeto ser� devolvido ao remetente'] = 'rastreamento_em_transito.svg';
        $arr[98]['BDI']['Objeto n�o localizado no fluxo postal.'] = 'rastreamento_em_transito.svg';
        $arr[13]['BDR']['Objeto atingido por inc�ndio em unidade operacional'] = 'rastreamento_em_transito.svg';
        $arr[14]['BDR']['Desist�ncia de postagem pelo remetente'] = 'rastreamento_em_transito.svg';
        $arr[15]['BDR']['Recebido na unidade de distribui��o - Por determina��o judicial o objeto ser� entregue em at� 7 dias'] = 'rastreamento_em_transito.svg';
        $arr[18]['BDR']['Carteiro n�o atendido - Entrega n�o realizada - Ser� realizada nova tentativa de entrega no s�bado'] = 'rastreamento_em_transito.svg';
        $arr[29]['BDR']['Objeto n�o localizado'] = 'rastreamento_em_transito.svg';
        $arr[30]['BDR']['Sa�da n�o efetuada - Em tratamento, aguarde.'] = 'rastreamento_em_transito.svg';
        $arr[31]['BDR']['Pagamento confirmado - Entrega em at� 40 dias �teis'] = 'rastreamento_em_transito.svg';
        $arr[44]['BDR']['Aguardando �rg�o anuente apresentar documenta��o fiscal'] = 'rastreamento_em_transito.svg';
        $arr[60]['BDR']['Objeto encontra-se aguardando prazo para refugo'] = 'rastreamento_em_transito.svg';
        $arr[61]['BDR']['Objeto refugado - Devolu��o proibida por �rg�o anuente ou n�o contratada pelo remetente'] = 'rastreamento_em_transito.svg';
        $arr[62]['BDR']['Em devolu��o � origem por determina��o da Receita Federal - Objeto poder� ser devolvido � origem ou encaminhado para refugo de acordo com orienta��o do remetente'] = 'rastreamento_em_transito.svg';
        $arr[63]['BDR']['Pagamento n�o efetuado no prazo - Objeto em an�lise de destina��o'] = 'rastreamento_em_transito.svg';
        $arr[64]['BDR']['Solicita��o de dados n�o atendida - O objeto ser� devolvido ao remetente'] = 'rastreamento_em_transito.svg';
        $arr[67]['BDR']['Objeto entregue ao destinat�rio - Entrega realizada em endere�o vizinho, conforme autorizado pelo remetente'] = 'rastreamento_sucesso.svg';
        $arr[68]['BDR']['Objeto entregue na Caixa de Correios Inteligente'] = 'rastreamento_sucesso.svg';
        $arr[70]['BDR']['Objeto entregue ao destinat�rio'] = 'rastreamento_sucesso.svg';
        $arr[71]['BDR']['Objeto apreendido por: ANVISA/VIGILANCIA SANITARIA - O objeto est� em poder da autoridade competente'] = 'rastreamento_cancelado.svg';
        $arr[72]['BDR']['Objeto apreendido por: BOMBEIROS - O objeto est� em poder da autoridade competente'] = 'rastreamento_cancelado.svg';
        $arr[73]['BDR']['Objeto apreendido por: EXERCITO - O objeto est� em poder da autoridade competente'] = 'rastreamento_cancelado.svg';
        $arr[74]['BDR']['Objeto apreendido por: IBAMA - O objeto est� em poder da autoridade competente'] = 'rastreamento_cancelado.svg';
        $arr[75]['BDR']['Objeto apreendido por: POLICIA FEDERAL - O objeto est� em poder da autoridade competente'] = 'rastreamento_cancelado.svg';
        $arr[76]['BDR']['Objeto apreendido por: POLICIA CIVIL/MILITAR - O objeto est� em poder da autoridade competente'] = 'rastreamento_cancelado.svg';
        $arr[77]['BDR']['Evento RFB - Aguardando DTRAT'] = 'rastreamento_em_transito.svg';
        $arr[79]['BDR']['POSTAGEM INEXISTENTE'] = 'rastreamento_em_transito.svg';
        $arr[81]['BDR']['N�o foi autorizada a sa�da do objeto do pa�s pelos �rg�os fiscalizadores - Objeto em an�lise de destina��o - poder� ser devolvido ao remetente, encaminhado para refugo ou apreendido'] = 'rastreamento_em_transito.svg';
        $arr[82]['BDR']['Necess�rio complementar endere�o de entrega - Acesse o Fale Conosco > Reclama��o > Objeto em processo de desembara�o'] = 'rastreamento_em_transito.svg';
        $arr[83]['BDR']['A��o necess�ria � libera��o do objeto n�o realizada pelo cliente - Objeto em an�lise de destina��o - poder� ser devolvido ao remetente, encaminhado para refugo ou apreendido'] = 'rastreamento_em_transito.svg';
        $arr[84]['BDR']['Verificando situa��o do objeto - Por favor, aguarde'] = 'rastreamento_em_transito.svg';
        $arr[85]['BDR']['C�digo do objeto fora do padr�o - Objeto ser� entregue sem rastreamento'] = 'rastreamento_em_transito.svg';
        $arr[86]['BDR']['Objeto com conte�do avariado - Sem condi��es de entrega'] = 'rastreamento_em_transito.svg';
        $arr[87]['BDR']['Objeto destinado a outro pa�s'] = 'rastreamento_em_transito.svg';
        $arr[89]['BDR']['Localidade desconhecida no Brasil - Objeto ser� devolvido ao remetente'] = 'rastreamento_em_transito.svg';
        $arr[4]['BLQ']['Bloqueio'] = 'rastreamento_em_transito.svg';
        $arr[5]['BLQ']['Solicita��o de suspens�o de entrega recebida - Objeto deve retornar ao Centro Internacional'] = 'rastreamento_em_transito.svg';
        $arr[11]['BLQ']['Solicita��o de suspens�o de entrega recebida - Solicita��o realizada pelo contratante/remetente'] = 'rastreamento_em_transito.svg';
        $arr[22]['BLQ']['Solicita��o de suspens�o de entrega recebida'] = 'rastreamento_em_transito.svg';
        $arr[24]['BLQ']['Desbloqueio de objeto indenizado'] = 'rastreamento_em_transito.svg';
        $arr[30]['BLQ']['Bloqueio teste'] = 'rastreamento_em_transito.svg';
        $arr[31]['BLQ']['Extravio varejo p�s-indenizado'] = 'rastreamento_em_transito.svg';
        $arr[41]['BLQ']['Bloqueio SEGOPE 4/1'] = 'rastreamento_em_transito.svg';
        $arr[42]['BLQ']['Bloqueio SEGOPE 4/2'] = 'rastreamento_em_transito.svg';
        $arr[44]['BLQ']['Desbloqueio de objeto com conte�do proibido/perigoso'] = 'rastreamento_em_transito.svg';
        $arr[51]['BLQ']['Objeto bloqueado por necessidade de revis�o de impostos/tributos'] = 'rastreamento_em_transito.svg';
        $arr[54]['BLQ']['Desbloqueio de objeto com revis�o de impostos/tributos'] = 'rastreamento_em_transito.svg';
        $arr[61]['BLQ']['Desbloqueado'] = 'rastreamento_em_transito.svg';
        $arr[2]['CO']['A coletar'] = 'rastreamento_em_transito.svg';
        $arr[3]['CO']['Coletando'] = 'rastreamento_em_transito.svg';
        $arr[4]['CO']['Pedido Transferido'] = 'rastreamento_em_transito.svg';
        $arr[5]['CO']['1� Tentativa de Coleta'] = 'rastreamento_em_transito.svg';
        $arr[6]['CO']['2� Tentativa / Coleta Cancelada'] = 'rastreamento_em_transito.svg';
        $arr[7]['CO']['Aguardando Objeto na Ag�ncia'] = 'rastreamento_em_transito.svg';
        $arr[8]['CO']['Entregue'] = 'rastreamento_em_transito.svg';
        $arr[9]['CO']['Coleta Cancelada'] = 'rastreamento_em_transito.svg';
        $arr[10]['CO']['Desist�ncia do Cliente ECT'] = 'rastreamento_em_transito.svg';
        $arr[11]['CO']['Objeto Sinistrado'] = 'rastreamento_em_transito.svg';
        $arr[12]['CO']['Aguardando Objeto de Entrega'] = 'rastreamento_em_transito.svg';
        $arr[13]['CO']['Objeto n�o coletado'] = 'rastreamento_em_transito.svg';
        $arr[14]['CO']['Transformado em e-ticket'] = 'rastreamento_em_transito.svg';
        $arr[9]['FC']['Remetente n�o retirou objeto na Unidade dos Correios - Objeto em an�lise de destina��o'] = 'rastreamento_em_transito.svg';
        $arr[11]['FC']['Objeto destru�do com autoriza��o do remetente'] = 'rastreamento_cancelado.svg';
        $arr[12]['FC']['Objeto encaminhado para fiscaliza��o - ANVISA'] = 'rastreamento_em_transito.svg';
        $arr[13]['FC']['Fiscalizado ANATEL - Seguir orienta��es do site da ANATEL. Prazo: 10 dias corridos'] = 'rastreamento_em_transito.svg';
        $arr[19]['FC']['Objeto encaminhado para fiscaliza��o - ANATEL'] = 'rastreamento_em_transito.svg';
        $arr[24]['FC']['Em fiscaliza��o pela Receita Federal'] = 'rastreamento_em_transito.svg';
        $arr[26]['FC']['RFB - Objeto Tributado - Emiss�o da nota de tributa��o'] = 'rastreamento_em_transito.svg';
        $arr[27]['FC']['Objeto encaminhado para emiss�o de DSI'] = 'rastreamento_em_transito.svg';
        $arr[29]['FC']['Objeto encaminhado para apreens�o'] = 'rastreamento_em_transito.svg';
        $arr[31]['FC']['Aguardando autoriza��o para exporta��o'] = 'rastreamento_em_transito.svg';
        $arr[32]['FC']['Objeto encaminhado para fiscaliza��o - INMETRO'] = 'rastreamento_em_transito.svg';
        $arr[33]['FC']['Objeto encaminhado para fiscaliza��o - Pol�cia Federal'] = 'rastreamento_em_transito.svg';
        $arr[34]['FC']['Objeto encaminhado para fiscaliza��o - VIGIAGRO'] = 'rastreamento_em_transito.svg';
        $arr[35]['FC']['Objeto encaminhado para fiscaliza��o - Ex�rcito'] = 'rastreamento_em_transito.svg';
        $arr[37]['FC']['Objeto encaminhado para fiscaliza��o - IBAMA'] = 'rastreamento_em_transito.svg';
        $arr[41]['FC']['Problemas na documenta��o do objeto - Procure a ag�ncia de postagem em at� 2 dias �teis'] = 'rastreamento_em_transito.svg';
        $arr[42]['FC']['Objeto cont�m l�quido, impossibilitando seu transporte - Em tratamento, aguarde'] = 'rastreamento_em_transito.svg';
        $arr[47]['FC']['Objeto ser� devolvido por solicita��o do contratante/remetente - Em tratamento, aguarde.'] = 'rastreamento_em_transito.svg';
        $arr[51]['FC']['Objeto roubado'] = 'rastreamento_em_transito.svg';
        $arr[55]['FC']['Encomenda unitizada finalizada'] = 'rastreamento_em_transito.svg';
        $arr[13]['LDI']['Objeto encaminhado para retirada no endere�o indicado - Para retir�-lo, � preciso informar o c�digo do objeto.'] = 'rastreamento_em_transito.svg';
        $arr[10]['PAR']['Fiscaliza��o aduaneira finalizada'] = 'rastreamento_em_transito.svg';
        $arr[11]['PAR']['Aguardando pagamento'] = 'rastreamento_em_transito.svg';
        $arr[12]['PAR']['Informa��es prestadas pelo cliente em an�lise'] = 'rastreamento_em_transito.svg';
        $arr[13]['PAR']['Objeto devolvido ao pa�s de origem'] = 'rastreamento_em_transito.svg';
        $arr[14]['PAR']['Revis�o de tributos solicitada pelo cliente'] = 'rastreamento_em_transito.svg';
        $arr[19]['PAR']['Fiscaliza��o aduaneira finalizada no pa�s de destino'] = 'rastreamento_em_transito.svg';
        $arr[20]['PAR']['Objeto recebido pelos Correios do Brasil com embalagem danificada - Por favor, aguarde'] = 'rastreamento_em_transito.svg';
        $arr[21]['PAR']['Encaminhado para fiscaliza��o aduaneira'] = 'rastreamento_em_transito.svg';
        $arr[22]['PAR']['Encaminhado para fiscaliza��o no pa�s de destino'] = 'rastreamento_em_transito.svg';
        $arr[23]['PAR']['Aguardando autoriza��o da Receita Federal para devolu��o do objeto ao remetente'] = 'rastreamento_em_transito.svg';
        $arr[24]['PAR']['Devolu��o autorizada pela Receita Federal'] = 'rastreamento_em_transito.svg';
        $arr[25]['PAR']['Faltam informa��es. Sua a��o � necess�ria'] = 'rastreamento_em_transito.svg';
        $arr[26]['PAR']['Destinat�rio recusou o objeto - Objeto em an�lise de destina��o'] = 'rastreamento_em_transito.svg';
        $arr[27]['PAR']['Despacho Postal pago na origem'] = 'rastreamento_em_transito.svg';
        $arr[28]['PAR']['Encaminhado para a unidade de tratamento'] = 'rastreamento_em_transito.svg';
        $arr[29]['PAR']['PAR 29'] = 'rastreamento_em_transito.svg';
        $arr[30]['PAR']['Aguardando pagamento do despacho postal'] = 'rastreamento_em_transito.svg';
        $arr[31]['PAR']['Pagamento confirmado'] = 'rastreamento_em_transito.svg';
        $arr[32]['PAR']['Aguardando confirma��o de pagamento pela operadora'] = 'rastreamento_em_transito.svg';
        $arr[33]['PAR']['Pagamento n�o confirmado pela operadora - Entre em contato com sua operadora'] = 'rastreamento_em_transito.svg';
        $arr[34]['PAR']['Liberado sem tributa��o'] = 'rastreamento_em_transito.svg';
        $arr[40]['PAR']['Devolu��o a pedido - Cliente solicitou a devolu��o da encomenda'] = 'rastreamento_em_transito.svg';
        $arr[41]['PAR']['Prazo de pagamento encerrado - Objeto em an�lise de destina��o'] = 'rastreamento_em_transito.svg';
        $arr[42]['PAR']['Objeto liberado'] = 'rastreamento_em_transito.svg';

    }

    private function _addTiposRestantes(&$arr, $imgPostado, $imgEmTransito, $imgSucesso, $imgCancelada)
    {
        //Blq
        $arr[1]['BLQ'] = $imgCancelada;

        //Cd
        $arr[0]['CD'] = $imgSucesso;
        $arr[1]['CD'] = $imgSucesso;
        $arr[2]['CD'] = $imgSucesso;
        $arr[3]['CD'] = $imgSucesso;

        //CMT
        $arr[0]['CMT'] = $imgSucesso;

        //CO
        $arr[1]['CO'] = $imgSucesso;

        //CUN
        $arr[0]['CUN'] = $imgSucesso;
        $arr[1]['CUN'] = $imgSucesso;

        //DO
        $arr[0]['DO'] = $imgEmTransito;
        $arr[1]['DO'] = $imgEmTransito;
        $arr[2]['DO'] = $imgEmTransito;

        //EST
        $arr[1]['EST'] = $imgEmTransito;
        $arr[2]['EST'] = $imgEmTransito;
        $arr[3]['EST'] = $imgEmTransito;
        $arr[4]['EST'] = $imgEmTransito;
        $arr[5]['EST'] = $imgEmTransito;
        $arr[6]['EST'] = $imgEmTransito;
        $arr[9]['EST'] = $imgEmTransito;

        //FC
        $arr[1]['FC'] = $imgEmTransito;
        $arr[2]['FC'] = $imgEmTransito;
        $arr[3]['FC'] = $imgEmTransito;
        $arr[4]['FC'] = $imgCancelada;
        $arr[5]['FC'] = $imgCancelada;
        $arr[7]['FC'] = $imgCancelada;

        //IDC
        $arr[1]['IDC'] = $imgEmTransito;
        $arr[2]['IDC'] = $imgEmTransito;
        $arr[3]['IDC'] = $imgEmTransito;
        $arr[4]['IDC'] = $imgEmTransito;
        $arr[5]['IDC'] = $imgEmTransito;
        $arr[6]['IDC'] = $imgEmTransito;
        $arr[7]['IDC'] = $imgEmTransito;

        //LDE
        $arr[9]['LDE'] = $imgEmTransito;

        //LDI
        $arr[0]['LDI'] = $imgEmTransito;
        $arr[1]['LDI'] = $imgEmTransito;
        $arr[2]['LDI'] = $imgCancelada;
        $arr[3]['LDI'] = $imgEmTransito;
        $arr[14]['LDI'] = $imgEmTransito;

        //OEC
        $arr[0]['OEC'] = $imgEmTransito;

        //PAR
        $arr[15]['PAR'] = $imgEmTransito;
        $arr[16]['PAR'] = $imgEmTransito;
        $arr[17]['PAR'] = $imgEmTransito;
        $arr[18]['PAR'] = $imgEmTransito;

        //PMT
        $arr[1]['PMT'] = $imgEmTransito;

        //PO
        $arr[0]['PO'] = $imgPostado;
        $arr[1]['PO'] = $imgPostado;
        $arr[9]['PO'] = $imgPostado;

        //RO
        $arr[0]['RO'] = $imgEmTransito;
        $arr[1]['RO'] = $imgEmTransito;

        //TRI
        $arr[0]['TRI'] = $imgEmTransito;
    }

    private function _addTipoBdeBdiBdr(&$arr, $imgPostado, $imgEmTransito, $imgSucesso, $imgCancelada)
    {
        $tipos = array(0 => 'BDE', 1 => 'BDI', 2 => 'BDR');

        foreach ($tipos as $tipo) {
            $arr[0][$tipo] = $imgSucesso;
            $arr[1][$tipo] = $imgSucesso;
            $arr[2][$tipo] = $imgCancelada;
            $arr[3][$tipo] = $imgCancelada;
            $arr[4][$tipo] = $imgCancelada;
            $arr[5][$tipo] = $imgCancelada;
            $arr[6][$tipo] = $imgCancelada;
            $arr[7][$tipo] = $imgCancelada;
            $arr[8][$tipo] = $imgCancelada;
            $arr[9][$tipo] = $imgEmTransito;
            $arr[10][$tipo] = $imgCancelada;
            $arr[12][$tipo] = $imgCancelada;
            $arr[19][$tipo] = $imgCancelada;
            $arr[20][$tipo] = $imgCancelada;
            $arr[21][$tipo] = $imgCancelada;
            $arr[22][$tipo] = $imgCancelada;
            $arr[23][$tipo] = $imgCancelada;
            $arr[24][$tipo] = $imgCancelada;
            $arr[25][$tipo] = $imgCancelada;
            $arr[26][$tipo] = $imgCancelada;
            $arr[28][$tipo] = $imgEmTransito;
            $arr[32][$tipo] = $imgEmTransito;
            $arr[33][$tipo] = $imgCancelada;
            $arr[34][$tipo] = $imgCancelada;
            $arr[35][$tipo] = $imgCancelada;
            $arr[36][$tipo] = $imgCancelada;
            $arr[37][$tipo] = $imgEmTransito;
            $arr[38][$tipo] = $imgCancelada;
            $arr[40][$tipo] = $imgEmTransito;
            $arr[41][$tipo] = $imgEmTransito;
            $arr[42][$tipo] = $imgEmTransito;
            $arr[43][$tipo] = $imgEmTransito;
            $arr[45][$tipo] = $imgEmTransito;
            $arr[46][$tipo] = $imgCancelada;
            $arr[47][$tipo] = $imgEmTransito;
            $arr[48][$tipo] = $imgEmTransito;
            $arr[49][$tipo] = $imgEmTransito;
            $arr[50][$tipo] = $imgEmTransito;
            $arr[51][$tipo] = $imgEmTransito;
            $arr[52][$tipo] = $imgEmTransito;
            $arr[53][$tipo] = $imgEmTransito;
            $arr[54][$tipo] = $imgEmTransito;
            $arr[55][$tipo] = $imgEmTransito;
            $arr[56][$tipo] = $imgEmTransito;
            $arr[57][$tipo] = $imgEmTransito;
            $arr[58][$tipo] = $imgEmTransito;
            $arr[59][$tipo] = $imgEmTransito;
            $arr[66][$tipo] = $imgEmTransito;
            $arr[69][$tipo] = $imgEmTransito;
            $arr[80][$tipo] = $imgEmTransito;
        }
    }

    protected function getStaRastreioModuloAndamentoConectado($objUltimoAndamento)
    {
        $imagem = '';
        if (!is_null($objUltimoAndamento)) {
            $tipo = $objUltimoAndamento->getStrTipo();
            $status = $objUltimoAndamento->getNumStatus();

            $objMdCorListaStatusDTO = new MdCorListaStatusDTO();
            $objMdCorListaStatusDTO->retStrStaRastreioModulo();
            $objMdCorListaStatusDTO->setNumStatus($status);
            $objMdCorListaStatusDTO->setStrTipo($tipo);
            $objMdCorListaStatusDTO->setNumMaxRegistrosRetorno(1);

            $objMdCorListaStatusDTO = $this->consultar($objMdCorListaStatusDTO);

            if (!is_null($objMdCorListaStatusDTO)) {
                $imagem = $objMdCorListaStatusDTO->getStrStaRastreioModulo();
            }
        }

        return $imagem;
    }

    protected function desativarControlado($arrObjMdCorListaStatusDTO)
    {
        try {

            //Valida Permissao
            SessaoSEI::getInstance()->validarPermissao('md_cor_lista_status_desativar');

            //Regras de Negocio
            //$objInfraException = new InfraException();

            //$objInfraException->lancarValidacoes();

            $objMdCorListaStatusBD = new MdCorListaStatusBD($this->getObjInfraIBanco());
            for ($i = 0; $i < count($arrObjMdCorListaStatusDTO); $i++) {
                $objMdCorListaStatusBD->desativar($arrObjMdCorListaStatusDTO[$i]);
            }

            //Auditoria

        } catch (Exception $e) {
            throw new InfraException('Erro desativando projeto.', $e);
        }
    }

    protected function reativarControlado($arrObjMdCorListaStatusDTO)
    {
        try {

            //Valida Permissao
            SessaoSEI::getInstance()->validarPermissao('md_cor_lista_status_reativar');

            //Regras de Negocio
            //$objInfraException = new InfraException();

            //$objInfraException->lancarValidacoes();

            $objMdCorListaStatusBD = new MdCorListaStatusBD($this->getObjInfraIBanco());
            for ($i = 0; $i < count($arrObjMdCorListaStatusDTO); $i++) {
                $objMdCorListaStatusBD->reativar($arrObjMdCorListaStatusDTO[$i]);
            }

            //Auditoria

        } catch (Exception $e) {
            throw new InfraException('Erro reativando projeto.', $e);
        }
    }

    /*
      protected function bloquearControlado(MdCorListaStatusDTO $objMdCorListaStatusDTO){
        try {

          //Valida Permissao
          SessaoSEI::getInstance()->validarPermissao('md_cor_lista_status_consultar');

          //Regras de Negocio
          //$objInfraException = new InfraException();

          //$objInfraException->lancarValidacoes();

          $objMdCorListaStatusBD = new MdCorListaStatusBD($this->getObjInfraIBanco());
          $ret = $objMdCorListaStatusBD->bloquear($objMdCorListaStatusDTO);

          //Auditoria

          return $ret;
        }catch(Exception $e){
          throw new InfraException('Erro bloqueando projeto.',$e);
        }
      }
    */

    public function _retornaTipoStatusNaoSalvos($arrTipoStatus)
    {

        $objMdCorListaStatusDTOAux = new MdCorListaStatusDTO();
        $objMdCorListaStatusDTOAuxll = new MdCorListaStatusDTO();
        $objMdCorListaStatusRNAux = new MdCorListaStatusRN();
        $objMdCorListaStatusDTOAux->retTodos(true);

        if ($arrTipoStatus) {
            foreach ($arrTipoStatus as $tipoStatus) {

                if (empty($arrTipoStatus[0])) {
                    $tipoStatus = $arrTipoStatus;
                }

                $status = (int)$tipoStatus['status'];
                $tipo = $tipoStatus['tipo'];

                $objMdCorListaStatusDTOAux->setNumStatus($status, InfraDTO::$OPER_IGUAL);
                $objMdCorListaStatusDTOAux->setStrTipo('' . $tipo . '', InfraDTO::$OPER_IGUAL);
                $arrObjMdCorListaStatusDTOll = $objMdCorListaStatusRNAux->listar($objMdCorListaStatusDTOAux);

                if (count($arrObjMdCorListaStatusDTOll) == 0) {
                    $objMdCorListaStatusDTOAuxll->setNumStatus($status);
                    $objMdCorListaStatusDTOAuxll->setStrTipo('' . $tipoStatus['tipo'] . '');
                    $objMdCorListaStatusDTOAuxll->setStrDescricao('' . $tipo . '');
                    $objMdCorListaStatusDTOAuxll->setStrStaRastreioModulo('');
                    $objMdCorListaStatusDTOAuxll->setStrSinAtivo('N');
                    $this->cadastrarControlado($objMdCorListaStatusDTOAuxll);
                }

            }
        }
    }

}

?>
