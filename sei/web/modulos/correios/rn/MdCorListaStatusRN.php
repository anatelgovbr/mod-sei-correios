<?
/**
 * TRIBUNAL REGIONAL FEDERAL DA 4ª REGIÃO
 *
 * 14/06/2017 - criado por jaqueline.cast
 *
 * Versão do Gerador de Código: 1.40.1
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
            $objInfraException->adicionarValidacao('Status não informado.');
        }
    }

    /*
     * Valida se já existe alguma linha com Tipo/Status no banco. EU#27389

    private function validarDuplicidadeTipoStatus(MdCorListaStatusDTO $objMdCorListaStatusDTO, InfraException $objInfraException)
    {
        $objMdCorListaStatusDTOAux = new MdCorListaStatusDTO();
        $objMdCorListaStatusRNAux = new MdCorListaStatusRN();
        $objMdCorListaStatusDTOAux->retTodos(true);
        $objMdCorListaStatusDTOAux->setNumStatus($objMdCorListaStatusDTO->getNumStatus(), InfraDTO::$OPER_IGUAL);
        $objMdCorListaStatusDTOAux->setStrTipo('' . $objMdCorListaStatusDTO->getStrTipo() . '', InfraDTO::$OPER_IGUAL);
        $arrObjMdCorListaStatusDTO = $objMdCorListaStatusRNAux->listar($objMdCorListaStatusDTOAux);

        if (count($arrObjMdCorListaStatusDTO) > 0) {
             $objInfraException->adicionarValidacao('Já existe um tipo de status cadastrado com este Código/Tipo.');
        }
    }

    private function validarStrDescricaoObjeto(MdCorListaStatusDTO $objMdCorListaStatusDTO, InfraException $objInfraException)
    {
        if (InfraString::isBolVazia($objMdCorListaStatusDTO->getStrDescricaoObjeto())) {
            $objInfraException->adicionarValidacao('Descrição no Rastreio do Objeto não informado.');
        } else {
            $objMdCorListaStatusDTO->setStrDescricaoObjeto(trim($objMdCorListaStatusDTO->getStrDescricaoObjeto()));
        }
    }

    private function validarStrDescricao(MdCorListaStatusDTO $objMdCorListaStatusDTO, InfraException $objInfraException)
    {
        if (InfraString::isBolVazia($objMdCorListaStatusDTO->getStrDescricao())) {
            $objInfraException->adicionarValidacao('Descrição SRO não informado.');
        } else {
            $objMdCorListaStatusDTO->setStrDescricao(trim($objMdCorListaStatusDTO->getStrDescricao()));
        }
    }*/

    private function validarStrTipo(MdCorListaStatusDTO $objMdCorListaStatusDTO, InfraException $objInfraException)
    {
        if (InfraString::isBolVazia($objMdCorListaStatusDTO->getStrTipo())) {
            $objInfraException->adicionarValidacao('Tipo não informado.');
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
            $objInfraException->adicionarValidacao('Situação no Rastreio do Módulo não informada.');
        } else {
            $objMdCorListaStatusDTO->setStrStaRastreioModulo(trim($objMdCorListaStatusDTO->getStrStaRastreioModulo()));

            if (strlen($objMdCorListaStatusDTO->getStrStaRastreioModulo()) > 2100) {
                $objInfraException->adicionarValidacao('Situação no Rastreio do Módulo possui tamanho superior a 2100 caracteres.');
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
        $arr[0]['BDE']['ATENÇÃO: Não consta mais no Manual do Web Service de Rastreamento dos Correios'] = 'rastreamento_sucesso.svg';
        $arr[0]['BDI']['ATENÇÃO: Não consta mais no Manual do Web Service de Rastreamento dos Correios'] = 'rastreamento_sucesso.svg';
        $arr[0]['BDR']['ATENÇÃO: Não consta mais no Manual do Web Service de Rastreamento dos Correios'] = 'rastreamento_sucesso.svg';
        $arr[0]['CD']['ATENÇÃO: Não consta mais no Manual do Web Service de Rastreamento dos Correios'] = 'rastreamento_sucesso.svg';
        $arr[0]['CMT']['ATENÇÃO: Não consta mais no Manual do Web Service de Rastreamento dos Correios'] = 'rastreamento_sucesso.svg';
        $arr[0]['CUN']['ATENÇÃO: Não consta mais no Manual do Web Service de Rastreamento dos Correios'] = 'rastreamento_sucesso.svg';
        $arr[0]['DO']['ATENÇÃO: Não consta mais no Manual do Web Service de Rastreamento dos Correios'] = 'rastreamento_em_transito.svg';
        $arr[0]['LDI']['ATENÇÃO: Não consta mais no Manual do Web Service de Rastreamento dos Correios'] = 'rastreamento_em_transito.svg';
        $arr[0]['OEC']['ATENÇÃO: Não consta mais no Manual do Web Service de Rastreamento dos Correios'] = 'rastreamento_em_transito.svg';
        $arr[0]['PO']['ATENÇÃO: Não consta mais no Manual do Web Service de Rastreamento dos Correios'] = 'rastreamento_postagem.svg';
        $arr[0]['RO']['ATENÇÃO: Não consta mais no Manual do Web Service de Rastreamento dos Correios'] = 'rastreamento_em_transito.svg';
        $arr[0]['TRI']['ATENÇÃO: Não consta mais no Manual do Web Service de Rastreamento dos Correios'] = 'rastreamento_em_transito.svg';
        $arr[1]['BDE']['Objeto entregue ao destinatário'] = 'rastreamento_sucesso.svg';
        $arr[1]['BDI']['Objeto entregue ao destinatário'] = 'rastreamento_sucesso.svg';
        $arr[1]['BDR']['Objeto entregue ao destinatário'] = 'rastreamento_sucesso.svg';
        $arr[1]['BLQ']['Solicitação de suspensão de entrega recebida - Solicitação realizada pelo contratante/remetente'] = 'rastreamento_em_transito.svg';
        $arr[1]['CD']['ATENÇÃO: Não consta mais no Manual do Web Service de Rastreamento dos Correios'] = 'rastreamento_sucesso.svg';
        $arr[1]['CO']['Objeto coletado'] = 'rastreamento_em_transito.svg';
        $arr[1]['CUN']['ATENÇÃO: Não consta mais no Manual do Web Service de Rastreamento dos Correios'] = 'rastreamento_sucesso.svg';
        $arr[1]['DO']['Objeto encaminhado '] = 'rastreamento_em_transito.svg';
        $arr[1]['EST']['Favor desconsiderar a informação anterior'] = 'rastreamento_em_transito.svg';
        $arr[1]['FC']['Objeto será devolvido por solicitação do contratante/remetente - Em tratamento, aguarde.'] = 'rastreamento_em_transito.svg';
        $arr[1]['IDC']['ATENÇÃO: Não consta mais no Manual do Web Service de Rastreamento dos Correios'] = 'rastreamento_em_transito.svg';
        $arr[1]['LDI']['Objeto aguardando retirada no endereço indicado - Para retirá-lo, é preciso informar o código do objeto e apresentar documentação que comprove ser o destinatário ou pessoa por ele oficialmente autorizada.'] = 'rastreamento_em_transito.svg';
        $arr[1]['PMT']['ATENÇÃO: Não consta mais no Manual do Web Service de Rastreamento dos Correios'] = 'rastreamento_em_transito.svg';
        $arr[1]['PO']['Objeto postado'] = 'rastreamento_postagem.svg';
        $arr[1]['RO']['Objeto encaminhado '] = 'rastreamento_em_transito.svg';
        $arr[2]['BDE']['Carteiro não atendido - Entrega não realizada - Aguarde: objeto estará disponível para retirada na unidade a ser informada'] = 'rastreamento_em_transito.svg';
        $arr[2]['BDI']['Carteiro não atendido - Entrega não realizada - Aguarde: objeto estará disponível para retirada na unidade a ser informada'] = 'rastreamento_em_transito.svg';
        $arr[2]['BDR']['Carteiro não atendido - Entrega não realizada - Aguarde: objeto estará disponível para retirada na unidade a ser informada'] = 'rastreamento_em_transito.svg';
        $arr[2]['CD']['ATENÇÃO: Não consta mais no Manual do Web Service de Rastreamento dos Correios'] = 'rastreamento_sucesso.svg';
        $arr[2]['DO']['Objeto encaminhado '] = 'rastreamento_em_transito.svg';
        $arr[2]['EST']['Favor desconsiderar a informação anterior'] = 'rastreamento_em_transito.svg';
        $arr[2]['FC']['Objeto com data de entrega agendada - Em tratamento, aguarde.'] = 'rastreamento_em_transito.svg';
        $arr[2]['IDC']['ATENÇÃO: Não consta mais no Manual do Web Service de Rastreamento dos Correios'] = 'rastreamento_em_transito.svg';
        $arr[2]['LDI']['Objeto disponível para retirada em Caixa Postal'] = 'rastreamento_em_transito.svg';
        $arr[3]['BDE']['Remetente não retirou objeto na Unidade dos Correios - Objeto em análise de destinação'] = 'rastreamento_em_transito.svg';
        $arr[3]['BDI']['Remetente não retirou objeto na Unidade dos Correios - Objeto em análise de destinação'] = 'rastreamento_em_transito.svg';
        $arr[3]['BDR']['Remetente não retirou objeto na Unidade dos Correios - Objeto em análise de destinação'] = 'rastreamento_em_transito.svg';
        $arr[3]['CD']['ATENÇÃO: Não consta mais no Manual do Web Service de Rastreamento dos Correios'] = 'rastreamento_sucesso.svg';
        $arr[3]['EST']['Favor desconsiderar a informação anterior'] = 'rastreamento_em_transito.svg';
        $arr[3]['FC']['Objeto mal encaminhado - Encaminhamento a ser corrigido'] = 'rastreamento_em_transito.svg';
        $arr[3]['IDC']['ATENÇÃO: Não consta mais no Manual do Web Service de Rastreamento dos Correios'] = 'rastreamento_em_transito.svg';
        $arr[3]['LDI']['Objeto aguardando retirada no endereço indicado - Para retirá-lo, é preciso informar o código do objeto e apresentar documentação que comprove ser o destinatário ou pessoa por ele oficialmente autorizada.'] = 'rastreamento_em_transito.svg';
        $arr[4]['BDE']['Cliente recusou-se a receber o objeto - Entrega não realizada - Objeto será devolvido ao remetente'] = 'rastreamento_em_transito.svg';
        $arr[4]['BDI']['Cliente recusou-se a receber o objeto - Entrega não realizada - Objeto será devolvido ao remetente'] = 'rastreamento_em_transito.svg';
        $arr[4]['BDR']['Cliente recusou-se a receber o objeto - Entrega não realizada - Objeto será devolvido ao remetente'] = 'rastreamento_em_transito.svg';
        $arr[4]['EST']['Favor desconsiderar a informação anterior'] = 'rastreamento_em_transito.svg';
        $arr[4]['FC']['Endereço incorreto - Entrega não realizada - Objeto sujeito a atraso na entrega ou a devolução ao remetente'] = 'rastreamento_em_transito.svg';
        $arr[4]['IDC']['ATENÇÃO: Não consta mais no Manual do Web Service de Rastreamento dos Correios'] = 'rastreamento_em_transito.svg';
        $arr[5]['BDE']['A entrega não pode ser efetuada - Objeto será devolvido ao remetente'] = 'rastreamento_em_transito.svg';
        $arr[5]['BDI']['A entrega não pode ser efetuada - Objeto será devolvido ao remetente'] = 'rastreamento_em_transito.svg';
        $arr[5]['BDR']['A entrega não pode ser efetuada - Objeto será devolvido ao remetente'] = 'rastreamento_em_transito.svg';
        $arr[5]['EST']['Favor desconsiderar a informação anterior'] = 'rastreamento_em_transito.svg';
        $arr[5]['FC']['Objeto devolvido aos Correios'] = 'rastreamento_em_transito.svg';
        $arr[5]['IDC']['ATENÇÃO: Não consta mais no Manual do Web Service de Rastreamento dos Correios'] = 'rastreamento_em_transito.svg';
        $arr[6]['BDE']['Cliente desconhecido no local - Entrega não realizada - Objeto será devolvido ao remetente'] = 'rastreamento_em_transito.svg';
        $arr[6]['BDI']['Cliente desconhecido no local - Entrega não realizada - Objeto será devolvido ao remetente'] = 'rastreamento_em_transito.svg';
        $arr[6]['BDR']['Cliente desconhecido no local - Entrega não realizada - Objeto será devolvido ao remetente'] = 'rastreamento_em_transito.svg';
        $arr[6]['EST']['Favor desconsiderar a informação anterior'] = 'rastreamento_em_transito.svg';
        $arr[6]['IDC']['ATENÇÃO: Não consta mais no Manual do Web Service de Rastreamento dos Correios'] = 'rastreamento_em_transito.svg';
        $arr[7]['BDE']['Endereço incorreto - Entrega não realizada - Objeto será devolvido ao remetente'] = 'rastreamento_em_transito.svg';
        $arr[7]['BDI']['Endereço incorreto - Entrega não realizada - Objeto será devolvido ao remetente'] = 'rastreamento_em_transito.svg';
        $arr[7]['BDR']['Endereço incorreto - Entrega não realizada - Objeto será devolvido ao remetente'] = 'rastreamento_em_transito.svg';
        $arr[7]['FC']['Empresa sem expediente - Entrega não realizada - Entrega deverá ocorrer no próximo dia útil'] = 'rastreamento_em_transito.svg';
        $arr[7]['IDC']['ATENÇÃO: Não consta mais no Manual do Web Service de Rastreamento dos Correios'] = 'rastreamento_em_transito.svg';
        $arr[8]['BDE']['Endereço incorreto - Entrega não realizada - Objeto será devolvido ao remetente'] = 'rastreamento_em_transito.svg';
        $arr[8]['BDI']['Endereço incorreto - Entrega não realizada - Objeto será devolvido ao remetente'] = 'rastreamento_em_transito.svg';
        $arr[8]['BDR']['Endereço incorreto - Entrega não realizada - Objeto será devolvido ao remetente'] = 'rastreamento_em_transito.svg';
        $arr[9]['BDE']['Objeto ainda não chegou à unidade - Por favor, aguarde'] = 'rastreamento_em_transito.svg';
        $arr[9]['BDI']['Objeto ainda não chegou à unidade - Por favor, aguarde'] = 'rastreamento_em_transito.svg';
        $arr[9]['BDR']['Objeto ainda não chegou à unidade - Por favor, aguarde'] = 'rastreamento_em_transito.svg';
        $arr[9]['EST']['Favor desconsiderar a informação anterior'] = 'rastreamento_em_transito.svg';
        $arr[9]['LDE']['ATENÇÃO: Não consta mais no Manual do Web Service de Rastreamento dos Correios'] = 'rastreamento_em_transito.svg';
        $arr[9]['PO']['Objeto postado após o horário limite da unidade - Sujeito a encaminhamento no próximo dia útil'] = 'rastreamento_postagem.svg';
        $arr[10]['BDE']['Cliente mudou-se - Entrega não realizada - Objeto será devolvido ao remetente'] = 'rastreamento_em_transito.svg';
        $arr[10]['BDI']['Cliente mudou-se - Entrega não realizada - Objeto será devolvido ao remetente'] = 'rastreamento_em_transito.svg';
        $arr[10]['BDR']['Cliente mudou-se - Entrega não realizada - Objeto será devolvido ao remetente'] = 'rastreamento_em_transito.svg';
        $arr[12]['BDE']['Remetente não retirou objeto na Unidade dos Correios - Objeto em análise de destinação'] = 'rastreamento_cancelado.svg';
        $arr[12]['BDI']['Remetente não retirou objeto na Unidade dos Correios - Objeto em análise de destinação'] = 'rastreamento_cancelado.svg';
        $arr[12]['BDR']['Remetente não retirou objeto na Unidade dos Correios - Objeto em análise de destinação'] = 'rastreamento_cancelado.svg';
        $arr[14]['LDI']['Objeto encaminhado para retirada no endereço indicado - Para retirá-lo, é preciso informar o código do objeto.'] = 'rastreamento_em_transito.svg';
        $arr[15]['PAR']['Objeto recebido em'] = 'rastreamento_em_transito.svg';
        $arr[16]['PAR']['Objeto recebido pelos Correios do Brasil'] = 'rastreamento_em_transito.svg';
        $arr[17]['PAR']['Aguardando pagamento'] = 'rastreamento_em_transito.svg';
        $arr[18]['PAR']['Objeto recebido na unidade de exportação no país de origem'] = 'rastreamento_em_transito.svg';
        $arr[19]['BDE']['Endereço incorreto - Entrega não realizada - Objeto sujeito a atraso na entrega ou a devolução ao remetente'] = 'rastreamento_em_transito.svg';
        $arr[19]['BDI']['Endereço incorreto - Entrega não realizada - Objeto sujeito a atraso na entrega ou a devolução ao remetente'] = 'rastreamento_em_transito.svg';
        $arr[19]['BDR']['Endereço incorreto - Entrega não realizada - Objeto sujeito a atraso na entrega ou a devolução ao remetente'] = 'rastreamento_em_transito.svg';
        $arr[20]['BDE']['Carteiro não atendido - Entrega não realizada - Será realizada nova tentativa de entrega'] = 'rastreamento_em_transito.svg';
        $arr[20]['BDI']['Carteiro não atendido - Entrega não realizada - Será realizada nova tentativa de entrega'] = 'rastreamento_em_transito.svg';
        $arr[20]['BDR']['Carteiro não atendido - Entrega não realizada - Será realizada nova tentativa de entrega'] = 'rastreamento_em_transito.svg';
        $arr[21]['BDE']['Carteiro não atendido - Entrega não realizada - Objeto será devolvido ao remetente'] = 'rastreamento_em_transito.svg';
        $arr[21]['BDI']['Carteiro não atendido - Entrega não realizada - Objeto será devolvido ao remetente'] = 'rastreamento_em_transito.svg';
        $arr[21]['BDR']['Carteiro não atendido - Entrega não realizada - Objeto será devolvido ao remetente'] = 'rastreamento_em_transito.svg';
        $arr[22]['BDE']['Objeto devolvido aos Correios'] = 'rastreamento_em_transito.svg';
        $arr[22]['BDI']['Objeto devolvido aos Correios'] = 'rastreamento_em_transito.svg';
        $arr[22]['BDR']['Objeto devolvido aos Correios'] = 'rastreamento_em_transito.svg';
        $arr[23]['BDE']['Objeto entregue ao remetente'] = 'rastreamento_cancelado.svg';
        $arr[23]['BDI']['Objeto entregue ao remetente'] = 'rastreamento_cancelado.svg';
        $arr[23]['BDR']['Objeto entregue ao remetente'] = 'rastreamento_cancelado.svg';
        $arr[24]['BDE']['Objeto disponível para retirada em Caixa Postal'] = 'rastreamento_em_transito.svg';
        $arr[24]['BDI']['Objeto disponível para retirada em Caixa Postal'] = 'rastreamento_em_transito.svg';
        $arr[24]['BDR']['Objeto disponível para retirada em Caixa Postal'] = 'rastreamento_em_transito.svg';
        $arr[25]['BDE']['Empresa sem expediente - Entrega não realizada - Entrega deverá ocorrer no próximo dia útil'] = 'rastreamento_em_transito.svg';
        $arr[25]['BDI']['Empresa sem expediente - Entrega não realizada - Entrega deverá ocorrer no próximo dia útil'] = 'rastreamento_em_transito.svg';
        $arr[25]['BDR']['Empresa sem expediente - Entrega não realizada - Entrega deverá ocorrer no próximo dia útil'] = 'rastreamento_em_transito.svg';
        $arr[26]['BDE']['Destinatário não retirou objeto no prazo - Objeto será devolvido ao remetente'] = 'rastreamento_em_transito.svg';
        $arr[26]['BDI']['Destinatário não retirou objeto no prazo - Objeto será devolvido ao remetente'] = 'rastreamento_em_transito.svg';
        $arr[26]['BDR']['Destinatário não retirou objeto no prazo - Objeto será devolvido ao remetente'] = 'rastreamento_em_transito.svg';
        $arr[28]['BDE']['Objeto e/ou conteúdo avariado'] = 'rastreamento_em_transito.svg';
        $arr[28]['BDI']['Objeto e/ou conteúdo avariado'] = 'rastreamento_em_transito.svg';
        $arr[28]['BDR']['Objeto e/ou conteúdo avariado'] = 'rastreamento_em_transito.svg';
        $arr[32]['BDE']['Objeto com data de entrega agendada'] = 'rastreamento_em_transito.svg';
        $arr[32]['BDI']['Objeto com data de entrega agendada'] = 'rastreamento_em_transito.svg';
        $arr[32]['BDR']['Objeto com data de entrega agendada'] = 'rastreamento_em_transito.svg';
        $arr[33]['BDE']['A entrega não pode ser efetuada - Destinatário não apresentou documento exigido'] = 'rastreamento_em_transito.svg';
        $arr[33]['BDI']['A entrega não pode ser efetuada - Destinatário não apresentou documento exigido'] = 'rastreamento_em_transito.svg';
        $arr[33]['BDR']['A entrega não pode ser efetuada - Destinatário não apresentou documento exigido'] = 'rastreamento_em_transito.svg';
        $arr[34]['BDE']['A entrega não pode ser efetuada - Logradouro com numeração irregular - Objeto sujeito a atraso na entrega ou a devolução ao remetente'] = 'rastreamento_em_transito.svg';
        $arr[34]['BDI']['A entrega não pode ser efetuada - Logradouro com numeração irregular - Objeto sujeito a atraso na entrega ou a devolução ao remetente'] = 'rastreamento_em_transito.svg';
        $arr[34]['BDR']['A entrega não pode ser efetuada - Logradouro com numeração irregular - Objeto sujeito a atraso na entrega ou a devolução ao remetente'] = 'rastreamento_em_transito.svg';
        $arr[35]['BDE']['Coleta ou entrega de objeto não efetuada - Será realizada nova tentativa de coleta ou entrega'] = 'rastreamento_em_transito.svg';
        $arr[35]['BDI']['Coleta ou entrega de objeto não efetuada - Será realizada nova tentativa de coleta ou entrega'] = 'rastreamento_em_transito.svg';
        $arr[35]['BDR']['Coleta ou entrega de objeto não efetuada - Será realizada nova tentativa de coleta ou entrega'] = 'rastreamento_em_transito.svg';
        $arr[36]['BDE']['Coleta ou entrega de objeto não efetuada - Objeto será devolvido ao remetente'] = 'rastreamento_em_transito.svg';
        $arr[36]['BDI']['Coleta ou entrega de objeto não efetuada - Objeto será devolvido ao remetente'] = 'rastreamento_em_transito.svg';
        $arr[36]['BDR']['Coleta ou entrega de objeto não efetuada - Objeto será devolvido ao remetente'] = 'rastreamento_em_transito.svg';
        $arr[37]['BDE']['Objeto e/ou conteúdo avariado por acidente com veículo'] = 'rastreamento_em_transito.svg';
        $arr[37]['BDI']['Objeto e/ou conteúdo avariado por acidente com veículo'] = 'rastreamento_em_transito.svg';
        $arr[37]['BDR']['Objeto e/ou conteúdo avariado por acidente com veículo'] = 'rastreamento_em_transito.svg';
        $arr[38]['BDE']['Objeto endereçado à empresa falida - Objeto será encaminhado para entrega ao administrador judicial'] = 'rastreamento_em_transito.svg';
        $arr[38]['BDI']['Objeto endereçado à empresa falida - Objeto será encaminhado para entrega ao administrador judicial'] = 'rastreamento_em_transito.svg';
        $arr[38]['BDR']['Objeto endereçado à empresa falida - Objeto será encaminhado para entrega ao administrador judicial'] = 'rastreamento_em_transito.svg';
        $arr[40]['BDE']['Não foi autorizada a entrada do objeto no país pelos órgãos fiscalizadores - Objeto em análise de destinação - poderá ser devolvido ao remetente, encaminhado para refugo ou apreendido'] = 'rastreamento_em_transito.svg';
        $arr[40]['BDI']['Não foi autorizada a entrada do objeto no país pelos órgãos fiscalizadores - Objeto em análise de destinação - poderá ser devolvido ao remetente, encaminhado para refugo ou apreendido'] = 'rastreamento_em_transito.svg';
        $arr[40]['BDR']['Não foi autorizada a entrada do objeto no país pelos órgãos fiscalizadores - Objeto em análise de destinação - poderá ser devolvido ao remetente, encaminhado para refugo ou apreendido'] = 'rastreamento_em_transito.svg';
        $arr[41]['BDE']['A entrega do objeto está condicionada à composição do lote'] = 'rastreamento_em_transito.svg';
        $arr[41]['BDI']['A entrega do objeto está condicionada à composição do lote'] = 'rastreamento_em_transito.svg';
        $arr[41]['BDR']['A entrega do objeto está condicionada à composição do lote'] = 'rastreamento_em_transito.svg';
        $arr[42]['BDE']['Lote de objetos incompleto - Em devolução ao remetente'] = 'rastreamento_em_transito.svg';
        $arr[42]['BDI']['Lote de objetos incompleto - Em devolução ao remetente'] = 'rastreamento_em_transito.svg';
        $arr[42]['BDR']['Lote de objetos incompleto - Em devolução ao remetente'] = 'rastreamento_em_transito.svg';
        $arr[43]['BDE']['Objeto apreendido por órgão de fiscalização - Objeto está em poder da autoridade competente'] = 'rastreamento_cancelado.svg';
        $arr[43]['BDI']['Objeto apreendido por órgão de fiscalização - Objeto está em poder da autoridade competente'] = 'rastreamento_cancelado.svg';
        $arr[43]['BDR']['Objeto apreendido por órgão de fiscalização - Objeto está em poder da autoridade competente'] = 'rastreamento_cancelado.svg';
        $arr[45]['BDE']['Objeto recebido na unidade de distribuição - Entrega prevista para o próximo dia útil'] = 'rastreamento_em_transito.svg';
        $arr[45]['BDI']['Objeto recebido na unidade de distribuição - Entrega prevista para o próximo dia útil'] = 'rastreamento_em_transito.svg';
        $arr[45]['BDR']['Objeto recebido na unidade de distribuição - Entrega prevista para o próximo dia útil'] = 'rastreamento_em_transito.svg';
        $arr[46]['BDE']['Tentativa de entrega não efetuada - Entrega prevista para o próximo dia útil'] = 'rastreamento_em_transito.svg';
        $arr[46]['BDI']['Tentativa de entrega não efetuada - Entrega prevista para o próximo dia útil'] = 'rastreamento_em_transito.svg';
        $arr[46]['BDR']['Tentativa de entrega não efetuada - Entrega prevista para o próximo dia útil'] = 'rastreamento_em_transito.svg';
        $arr[47]['BDE']['Saída para entrega cancelada - Será efetuada nova saída para entrega'] = 'rastreamento_em_transito.svg';
        $arr[47]['BDI']['Saída para entrega cancelada - Será efetuada nova saída para entrega'] = 'rastreamento_em_transito.svg';
        $arr[47]['BDR']['Saída para entrega cancelada - Será efetuada nova saída para entrega'] = 'rastreamento_em_transito.svg';
        $arr[48]['BDE']['Retirada em Unidade dos Correios não autorizada pelo remetente - Objeto será devolvido ao remetente'] = 'rastreamento_em_transito.svg';
        $arr[48]['BDI']['Retirada em Unidade dos Correios não autorizada pelo remetente - Objeto será devolvido ao remetente'] = 'rastreamento_em_transito.svg';
        $arr[48]['BDR']['Retirada em Unidade dos Correios não autorizada pelo remetente - Objeto será devolvido ao remetente'] = 'rastreamento_em_transito.svg';
        $arr[49]['BDE']['As dimensões do objeto impossibilitam o tratamento e a entrega - O objeto será devolvido ao remetente'] = 'rastreamento_em_transito.svg';
        $arr[49]['BDI']['As dimensões do objeto impossibilitam o tratamento e a entrega - O objeto será devolvido ao remetente'] = 'rastreamento_em_transito.svg';
        $arr[49]['BDR']['As dimensões do objeto impossibilitam o tratamento e a entrega - O objeto será devolvido ao remetente'] = 'rastreamento_em_transito.svg';
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
        $arr[54]['BDE']['Aguardando a guia de recolhimento para pagamento do ICMS Importação - Por favor, aguarde.'] = 'rastreamento_em_transito.svg';
        $arr[54]['BDI']['Aguardando a guia de recolhimento para pagamento do ICMS Importação - Por favor, aguarde.'] = 'rastreamento_em_transito.svg';
        $arr[54]['BDR']['Aguardando a guia de recolhimento para pagamento do ICMS Importação - Por favor, aguarde.'] = 'rastreamento_em_transito.svg';
        $arr[55]['BDE']['Solicitação de revisão do tributo - Acesse Minhas Importações para acompanhamento do pedido de revisão'] = 'rastreamento_em_transito.svg';
        $arr[55]['BDI']['Solicitação de revisão do tributo - Acesse Minhas Importações para acompanhamento do pedido de revisão'] = 'rastreamento_em_transito.svg';
        $arr[55]['BDR']['Solicitação de revisão do tributo - Acesse Minhas Importações para acompanhamento do pedido de revisão'] = 'rastreamento_em_transito.svg';
        $arr[56]['BDE']['Objeto com declaração aduaneira ausente ou incorreta - Aguarde: objeto em tratamento e sujeito a devolução'] = 'rastreamento_em_transito.svg';
        $arr[56]['BDI']['Objeto com declaração aduaneira ausente ou incorreta - Aguarde: objeto em tratamento e sujeito a devolução'] = 'rastreamento_em_transito.svg';
        $arr[56]['BDR']['Objeto com declaração aduaneira ausente ou incorreta - Aguarde: objeto em tratamento e sujeito a devolução'] = 'rastreamento_em_transito.svg';
        $arr[57]['BDE']['Revisão de tributo concluída - Objeto liberado'] = 'rastreamento_em_transito.svg';
        $arr[57]['BDI']['Revisão de tributo concluída - Objeto liberado'] = 'rastreamento_em_transito.svg';
        $arr[57]['BDR']['Revisão de tributo concluída - Objeto liberado'] = 'rastreamento_em_transito.svg';
        $arr[58]['BDE']['Revisão de tributo concluída - Tributo alterado - O valor do tributo pode ter aumentado ou diminuído'] = 'rastreamento_em_transito.svg';
        $arr[58]['BDI']['Revisão de tributo concluída - Tributo alterado - O valor do tributo pode ter aumentado ou diminuído'] = 'rastreamento_em_transito.svg';
        $arr[58]['BDR']['Revisão de tributo concluída - Tributo alterado - O valor do tributo pode ter aumentado ou diminuído'] = 'rastreamento_em_transito.svg';
        $arr[59]['BDE']['Revisão de tributo concluída - Tributo mantido - Poderá haver incidência de juros e multa'] = 'rastreamento_em_transito.svg';
        $arr[59]['BDI']['Revisão de tributo concluída - Tributo mantido - Poderá haver incidência de juros e multa'] = 'rastreamento_em_transito.svg';
        $arr[59]['BDR']['Revisão de tributo concluída - Tributo mantido - Poderá haver incidência de juros e multa'] = 'rastreamento_em_transito.svg';
        $arr[66]['BDE']['Área com distribuição sujeita a prazo diferenciado - Restrição de entrega domiciliar temporária'] = 'rastreamento_em_transito.svg';
        $arr[66]['BDI']['Área com distribuição sujeita a prazo diferenciado - Restrição de entrega domiciliar temporária'] = 'rastreamento_em_transito.svg';
        $arr[66]['BDR']['Área com distribuição sujeita a prazo diferenciado - Restrição de entrega domiciliar temporária'] = 'rastreamento_em_transito.svg';
        $arr[69]['BDE']['Objeto ainda não chegou à unidade - Por favor, aguarde'] = 'rastreamento_em_transito.svg';
        $arr[69]['BDI']['Objeto ainda não chegou à unidade - Por favor, aguarde'] = 'rastreamento_em_transito.svg';
        $arr[69]['BDR']['Objeto ainda não chegou à unidade - Por favor, aguarde'] = 'rastreamento_em_transito.svg';
        $arr[80]['BDE']['Objeto não localizado no fluxo postal'] = 'rastreamento_cancelado.svg';
        $arr[80]['BDI']['Objeto não localizado no fluxo postal'] = 'rastreamento_cancelado.svg';
        $arr[80]['BDR']['Objeto não localizado no fluxo postal'] = 'rastreamento_cancelado.svg';
        $arr[1]['OEC']['Objeto saiu para entrega ao destinatário'] = 'rastreamento_em_transito.svg';
        $arr[9]['OEC']['Objeto saiu para entrega ao remetente'] = 'rastreamento_em_transito.svg';
        $arr[1]['CMR']['ATENÇÃO: Não consta mais no Manual do Web Service de Rastreamento dos Correios'] = 'rastreamento_em_transito.svg';
        $arr[18]['BDE']['Carteiro não atendido - Entrega não realizada - Será realizada nova tentativa de entrega no sábado'] = 'rastreamento_em_transito.svg';
        $arr[8]['FC']['Área com distribuição sujeita a prazo diferenciado - Restrição de entrega domiciliar temporária'] = 'rastreamento_em_transito.svg';
        $arr[39]['BDI']['A entrega não pode ser efetuada - Objeto em análise de destinação'] = 'rastreamento_em_transito.svg';
        $arr[10]['FC']['Objeto recebido na unidade de distribuição - Entrega deverá ocorrer no próximo dia útil'] = 'rastreamento_em_transito.svg';
        $arr[39]['BDR']['A entrega não pode ser efetuada - Objeto em análise de destinação'] = 'rastreamento_em_transito.svg';
        $arr[11]['LDI']['Objeto encaminhado para retirada no endereço indicado - Para retirá-lo, é preciso informar o código do objeto.'] = 'rastreamento_em_transito.svg';
        $arr[4]['LDI']['Objeto aguardando retirada no endereço indicado - Para retirá-lo, é preciso informar o código do objeto e apresentar documentação que comprove ser o destinatário ou pessoa por ele oficialmente autorizada.'] = 'rastreamento_em_transito.svg';
        $arr[21]['BLQ']['Solicitação de suspensão de entrega recebida'] = 'rastreamento_em_transito.svg';
        $arr[68]['BDE']['Objeto entregue na Caixa de Correios Inteligente'] = 'rastreamento_sucesso.svg';
        $arr[13]['BDE']['Objeto atingido por incêndio em unidade operacional'] = 'rastreamento_em_transito.svg';
        $arr[14]['BDE']['Desistência de postagem pelo remetente'] = 'rastreamento_em_transito.svg';
        $arr[15]['BDE']['Recebido na unidade de distribuição - Por determinação judicial o objeto será entregue em até 7 dias'] = 'rastreamento_em_transito.svg';
        $arr[29]['BDE']['Objeto não localizado'] = 'rastreamento_em_transito.svg';
        $arr[30]['BDE']['Saída não efetuada - Em tratamento, aguarde.'] = 'rastreamento_em_transito.svg';
        $arr[39]['BDE']['A entrega não pode ser efetuada - Objeto em análise de destinação'] = 'rastreamento_em_transito.svg';
        $arr[44]['BDE']['Aguardando órgão anuente apresentar documentação fiscal'] = 'rastreamento_em_transito.svg';
        $arr[60]['BDE']['Objeto encontra-se aguardando prazo para refugo'] = 'rastreamento_em_transito.svg';
        $arr[61]['BDE']['Objeto refugado - Devolução proibida por órgão anuente ou não contratada pelo remetente'] = 'rastreamento_em_transito.svg';
        $arr[62]['BDE']['Em devolução à origem por determinação da Receita Federal - Objeto poderá ser devolvido à origem ou encaminhado para refugo de acordo com orientação do remetente'] = 'rastreamento_em_transito.svg';
        $arr[67]['BDE']['Objeto entregue ao destinatário - Entrega realizada em endereço vizinho, conforme autorizado pelo remetente'] = 'rastreamento_sucesso.svg';
        $arr[70]['BDE']['Objeto entregue ao destinatário'] = 'rastreamento_sucesso.svg';
        $arr[71]['BDE']['Objeto apreendido por: ANVISA/VIGILANCIA SANITARIA - O objeto está em poder da autoridade competente'] = 'rastreamento_cancelado.svg';
        $arr[72]['BDE']['Objeto apreendido por: BOMBEIROS - O objeto está em poder da autoridade competente'] = 'rastreamento_cancelado.svg';
        $arr[73]['BDE']['Objeto apreendido por: EXERCITO - O objeto está em poder da autoridade competente'] = 'rastreamento_cancelado.svg';
        $arr[74]['BDE']['Objeto apreendido por: IBAMA - O objeto está em poder da autoridade competente'] = 'rastreamento_cancelado.svg';
        $arr[75]['BDE']['Objeto apreendido por: POLICIA FEDERAL - O objeto está em poder da autoridade competente'] = 'rastreamento_cancelado.svg';
        $arr[76]['BDE']['Objeto apreendido por: POLICIA CIVIL/MILITAR - O objeto está em poder da autoridade competente'] = 'rastreamento_cancelado.svg';
        $arr[77]['BDE']['Evento RFB - Aguardando DTRAT'] = 'rastreamento_em_transito.svg';
        $arr[79]['BDE']['POSTAGEM INEXISTENTE (RESERVADO DERAT)'] = 'rastreamento_em_transito.svg';
        $arr[81]['BDE']['Não foi autorizada a saída do objeto do país pelos órgãos fiscalizadores - Objeto em análise de destinação - poderá ser devolvido ao remetente, encaminhado para refugo ou apreendido'] = 'rastreamento_em_transito.svg';
        $arr[82]['BDE']['Necessário complementar endereço de entrega - Acesse o Fale Conosco > Reclamação > Objeto em processo de desembaraço'] = 'rastreamento_em_transito.svg';
        $arr[83]['BDE']['Ação necessária à liberação do objeto não realizada pelo cliente - Objeto em análise de destinação - poderá ser devolvido ao remetente, encaminhado para refugo ou apreendido'] = 'rastreamento_em_transito.svg';
        $arr[84]['BDE']['Verificando situação do objeto - Por favor, aguarde'] = 'rastreamento_em_transito.svg';
        $arr[85]['BDE']['Código do objeto fora do padrão - Objeto será entregue sem rastreamento'] = 'rastreamento_em_transito.svg';
        $arr[86]['BDE']['Objeto com conteúdo avariado - Sem condições de entrega'] = 'rastreamento_em_transito.svg';
        $arr[87]['BDE']['Objeto destinado a outro país'] = 'rastreamento_em_transito.svg';
        $arr[89]['BDE']['Localidade desconhecida no Brasil - Objeto será devolvido ao remetente'] = 'rastreamento_em_transito.svg';
        $arr[98]['BDE']['Objeto não localizado no fluxo postal'] = 'rastreamento_em_transito.svg';
        $arr[13]['BDI']['Objeto atingido por incêndio em unidade operacional'] = 'rastreamento_em_transito.svg';
        $arr[14]['BDI']['Desistência de postagem pelo remetente'] = 'rastreamento_em_transito.svg';
        $arr[15]['BDI']['Recebido na unidade de distribuição - Por determinação judicial o objeto será entregue em até 7 dias'] = 'rastreamento_em_transito.svg';
        $arr[18]['BDI']['Carteiro não atendido - Entrega não realizada - Será realizada nova tentativa de entrega no sábado'] = 'rastreamento_em_transito.svg';
        $arr[29]['BDI']['Objeto não localizado'] = 'rastreamento_em_transito.svg';
        $arr[30]['BDI']['Saída não efetuada - Em tratamento, aguarde.'] = 'rastreamento_em_transito.svg';
        $arr[44]['BDI']['Aguardando órgão anuente apresentar documentação fiscal'] = 'rastreamento_em_transito.svg';
        $arr[60]['BDI']['Objeto encontra-se aguardando prazo para refugo'] = 'rastreamento_em_transito.svg';
        $arr[61]['BDI']['Objeto refugado - Devolução proibida por órgão anuente ou não contratada pelo remetente'] = 'rastreamento_em_transito.svg';
        $arr[62]['BDI']['Em devolução à origem por determinação da Receita Federal - Objeto poderá ser devolvido à origem ou encaminhado para refugo de acordo com orientação do remetente'] = 'rastreamento_em_transito.svg';
        $arr[67]['BDI']['Objeto entregue ao destinatário - Entrega realizada em endereço vizinho, conforme autorizado pelo remetente'] = 'rastreamento_sucesso.svg';
        $arr[68]['BDI']['Objeto entregue na Caixa de Correios Inteligente'] = 'rastreamento_sucesso.svg';
        $arr[70]['BDI']['Objeto entregue ao destinatário'] = 'rastreamento_sucesso.svg';
        $arr[71]['BDI']['Objeto apreendido por: ANVISA/VIGILANCIA SANITARIA - O objeto está em poder da autoridade competente'] = 'rastreamento_cancelado.svg';
        $arr[72]['BDI']['Objeto apreendido por: BOMBEIROS - O objeto está em poder da autoridade competente'] = 'rastreamento_cancelado.svg';
        $arr[73]['BDI']['Objeto apreendido por: EXERCITO - O objeto está em poder da autoridade competente'] = 'rastreamento_cancelado.svg';
        $arr[74]['BDI']['Objeto apreendido por: IBAMA - O objeto está em poder da autoridade competente'] = 'rastreamento_cancelado.svg';
        $arr[75]['BDI']['Objeto apreendido por: POLICIA FEDERAL - O objeto está em poder da autoridade competente'] = 'rastreamento_cancelado.svg';
        $arr[76]['BDI']['Objeto apreendido por: POLICIA CIVIL/MILITAR - O objeto está em poder da autoridade competente'] = 'rastreamento_cancelado.svg';
        $arr[77]['BDI']['Evento RFB - Aguardando DTRAT'] = 'rastreamento_em_transito.svg';
        $arr[81]['BDI']['Não foi autorizada a saída do objeto do país pelos órgãos fiscalizadores - Objeto em análise de destinação - poderá ser devolvido ao remetente, encaminhado para refugo ou apreendido'] = 'rastreamento_em_transito.svg';
        $arr[82]['BDI']['Necessário complementar endereço de entrega - Acesse o Fale Conosco > Reclamação > Objeto em processo de desembaraço'] = 'rastreamento_em_transito.svg';
        $arr[83]['BDI']['Ação necessária à liberação do objeto não realizada pelo cliente - Objeto em análise de destinação - poderá ser devolvido ao remetente, encaminhado para refugo ou apreendido'] = 'rastreamento_em_transito.svg';
        $arr[84]['BDI']['Verificando situação do objeto - Por favor, aguarde'] = 'rastreamento_em_transito.svg';
        $arr[85]['BDI']['Código do objeto fora do padrão - Objeto será entregue sem rastreamento'] = 'rastreamento_em_transito.svg';
        $arr[86]['BDI']['Objeto com conteúdo avariado - Sem condições de entrega'] = 'rastreamento_em_transito.svg';
        $arr[87]['BDI']['Objeto destinado a outro país'] = 'rastreamento_em_transito.svg';
        $arr[89]['BDI']['Localidade desconhecida no Brasil - O objeto será devolvido ao remetente'] = 'rastreamento_em_transito.svg';
        $arr[98]['BDI']['Objeto não localizado no fluxo postal.'] = 'rastreamento_em_transito.svg';
        $arr[13]['BDR']['Objeto atingido por incêndio em unidade operacional'] = 'rastreamento_em_transito.svg';
        $arr[14]['BDR']['Desistência de postagem pelo remetente'] = 'rastreamento_em_transito.svg';
        $arr[15]['BDR']['Recebido na unidade de distribuição - Por determinação judicial o objeto será entregue em até 7 dias'] = 'rastreamento_em_transito.svg';
        $arr[18]['BDR']['Carteiro não atendido - Entrega não realizada - Será realizada nova tentativa de entrega no sábado'] = 'rastreamento_em_transito.svg';
        $arr[29]['BDR']['Objeto não localizado'] = 'rastreamento_em_transito.svg';
        $arr[30]['BDR']['Saída não efetuada - Em tratamento, aguarde.'] = 'rastreamento_em_transito.svg';
        $arr[31]['BDR']['Pagamento confirmado - Entrega em até 40 dias úteis'] = 'rastreamento_em_transito.svg';
        $arr[44]['BDR']['Aguardando órgão anuente apresentar documentação fiscal'] = 'rastreamento_em_transito.svg';
        $arr[60]['BDR']['Objeto encontra-se aguardando prazo para refugo'] = 'rastreamento_em_transito.svg';
        $arr[61]['BDR']['Objeto refugado - Devolução proibida por órgão anuente ou não contratada pelo remetente'] = 'rastreamento_em_transito.svg';
        $arr[62]['BDR']['Em devolução à origem por determinação da Receita Federal - Objeto poderá ser devolvido à origem ou encaminhado para refugo de acordo com orientação do remetente'] = 'rastreamento_em_transito.svg';
        $arr[63]['BDR']['Pagamento não efetuado no prazo - Objeto em análise de destinação'] = 'rastreamento_em_transito.svg';
        $arr[64]['BDR']['Solicitação de dados não atendida - O objeto será devolvido ao remetente'] = 'rastreamento_em_transito.svg';
        $arr[67]['BDR']['Objeto entregue ao destinatário - Entrega realizada em endereço vizinho, conforme autorizado pelo remetente'] = 'rastreamento_sucesso.svg';
        $arr[68]['BDR']['Objeto entregue na Caixa de Correios Inteligente'] = 'rastreamento_sucesso.svg';
        $arr[70]['BDR']['Objeto entregue ao destinatário'] = 'rastreamento_sucesso.svg';
        $arr[71]['BDR']['Objeto apreendido por: ANVISA/VIGILANCIA SANITARIA - O objeto está em poder da autoridade competente'] = 'rastreamento_cancelado.svg';
        $arr[72]['BDR']['Objeto apreendido por: BOMBEIROS - O objeto está em poder da autoridade competente'] = 'rastreamento_cancelado.svg';
        $arr[73]['BDR']['Objeto apreendido por: EXERCITO - O objeto está em poder da autoridade competente'] = 'rastreamento_cancelado.svg';
        $arr[74]['BDR']['Objeto apreendido por: IBAMA - O objeto está em poder da autoridade competente'] = 'rastreamento_cancelado.svg';
        $arr[75]['BDR']['Objeto apreendido por: POLICIA FEDERAL - O objeto está em poder da autoridade competente'] = 'rastreamento_cancelado.svg';
        $arr[76]['BDR']['Objeto apreendido por: POLICIA CIVIL/MILITAR - O objeto está em poder da autoridade competente'] = 'rastreamento_cancelado.svg';
        $arr[77]['BDR']['Evento RFB - Aguardando DTRAT'] = 'rastreamento_em_transito.svg';
        $arr[79]['BDR']['POSTAGEM INEXISTENTE'] = 'rastreamento_em_transito.svg';
        $arr[81]['BDR']['Não foi autorizada a saída do objeto do país pelos órgãos fiscalizadores - Objeto em análise de destinação - poderá ser devolvido ao remetente, encaminhado para refugo ou apreendido'] = 'rastreamento_em_transito.svg';
        $arr[82]['BDR']['Necessário complementar endereço de entrega - Acesse o Fale Conosco > Reclamação > Objeto em processo de desembaraço'] = 'rastreamento_em_transito.svg';
        $arr[83]['BDR']['Ação necessária à liberação do objeto não realizada pelo cliente - Objeto em análise de destinação - poderá ser devolvido ao remetente, encaminhado para refugo ou apreendido'] = 'rastreamento_em_transito.svg';
        $arr[84]['BDR']['Verificando situação do objeto - Por favor, aguarde'] = 'rastreamento_em_transito.svg';
        $arr[85]['BDR']['Código do objeto fora do padrão - Objeto será entregue sem rastreamento'] = 'rastreamento_em_transito.svg';
        $arr[86]['BDR']['Objeto com conteúdo avariado - Sem condições de entrega'] = 'rastreamento_em_transito.svg';
        $arr[87]['BDR']['Objeto destinado a outro país'] = 'rastreamento_em_transito.svg';
        $arr[89]['BDR']['Localidade desconhecida no Brasil - Objeto será devolvido ao remetente'] = 'rastreamento_em_transito.svg';
        $arr[4]['BLQ']['Bloqueio'] = 'rastreamento_em_transito.svg';
        $arr[5]['BLQ']['Solicitação de suspensão de entrega recebida - Objeto deve retornar ao Centro Internacional'] = 'rastreamento_em_transito.svg';
        $arr[11]['BLQ']['Solicitação de suspensão de entrega recebida - Solicitação realizada pelo contratante/remetente'] = 'rastreamento_em_transito.svg';
        $arr[22]['BLQ']['Solicitação de suspensão de entrega recebida'] = 'rastreamento_em_transito.svg';
        $arr[24]['BLQ']['Desbloqueio de objeto indenizado'] = 'rastreamento_em_transito.svg';
        $arr[30]['BLQ']['Bloqueio teste'] = 'rastreamento_em_transito.svg';
        $arr[31]['BLQ']['Extravio varejo pós-indenizado'] = 'rastreamento_em_transito.svg';
        $arr[41]['BLQ']['Bloqueio SEGOPE 4/1'] = 'rastreamento_em_transito.svg';
        $arr[42]['BLQ']['Bloqueio SEGOPE 4/2'] = 'rastreamento_em_transito.svg';
        $arr[44]['BLQ']['Desbloqueio de objeto com conteúdo proibido/perigoso'] = 'rastreamento_em_transito.svg';
        $arr[51]['BLQ']['Objeto bloqueado por necessidade de revisão de impostos/tributos'] = 'rastreamento_em_transito.svg';
        $arr[54]['BLQ']['Desbloqueio de objeto com revisão de impostos/tributos'] = 'rastreamento_em_transito.svg';
        $arr[61]['BLQ']['Desbloqueado'] = 'rastreamento_em_transito.svg';
        $arr[2]['CO']['A coletar'] = 'rastreamento_em_transito.svg';
        $arr[3]['CO']['Coletando'] = 'rastreamento_em_transito.svg';
        $arr[4]['CO']['Pedido Transferido'] = 'rastreamento_em_transito.svg';
        $arr[5]['CO']['1ª Tentativa de Coleta'] = 'rastreamento_em_transito.svg';
        $arr[6]['CO']['2ª Tentativa / Coleta Cancelada'] = 'rastreamento_em_transito.svg';
        $arr[7]['CO']['Aguardando Objeto na Agência'] = 'rastreamento_em_transito.svg';
        $arr[8]['CO']['Entregue'] = 'rastreamento_em_transito.svg';
        $arr[9]['CO']['Coleta Cancelada'] = 'rastreamento_em_transito.svg';
        $arr[10]['CO']['Desistência do Cliente ECT'] = 'rastreamento_em_transito.svg';
        $arr[11]['CO']['Objeto Sinistrado'] = 'rastreamento_em_transito.svg';
        $arr[12]['CO']['Aguardando Objeto de Entrega'] = 'rastreamento_em_transito.svg';
        $arr[13]['CO']['Objeto não coletado'] = 'rastreamento_em_transito.svg';
        $arr[14]['CO']['Transformado em e-ticket'] = 'rastreamento_em_transito.svg';
        $arr[9]['FC']['Remetente não retirou objeto na Unidade dos Correios - Objeto em análise de destinação'] = 'rastreamento_em_transito.svg';
        $arr[11]['FC']['Objeto destruído com autorização do remetente'] = 'rastreamento_cancelado.svg';
        $arr[12]['FC']['Objeto encaminhado para fiscalização - ANVISA'] = 'rastreamento_em_transito.svg';
        $arr[13]['FC']['Fiscalizado ANATEL - Seguir orientações do site da ANATEL. Prazo: 10 dias corridos'] = 'rastreamento_em_transito.svg';
        $arr[19]['FC']['Objeto encaminhado para fiscalização - ANATEL'] = 'rastreamento_em_transito.svg';
        $arr[24]['FC']['Em fiscalização pela Receita Federal'] = 'rastreamento_em_transito.svg';
        $arr[26]['FC']['RFB - Objeto Tributado - Emissão da nota de tributação'] = 'rastreamento_em_transito.svg';
        $arr[27]['FC']['Objeto encaminhado para emissão de DSI'] = 'rastreamento_em_transito.svg';
        $arr[29]['FC']['Objeto encaminhado para apreensão'] = 'rastreamento_em_transito.svg';
        $arr[31]['FC']['Aguardando autorização para exportação'] = 'rastreamento_em_transito.svg';
        $arr[32]['FC']['Objeto encaminhado para fiscalização - INMETRO'] = 'rastreamento_em_transito.svg';
        $arr[33]['FC']['Objeto encaminhado para fiscalização - Polícia Federal'] = 'rastreamento_em_transito.svg';
        $arr[34]['FC']['Objeto encaminhado para fiscalização - VIGIAGRO'] = 'rastreamento_em_transito.svg';
        $arr[35]['FC']['Objeto encaminhado para fiscalização - Exército'] = 'rastreamento_em_transito.svg';
        $arr[37]['FC']['Objeto encaminhado para fiscalização - IBAMA'] = 'rastreamento_em_transito.svg';
        $arr[41]['FC']['Problemas na documentação do objeto - Procure a agência de postagem em até 2 dias úteis'] = 'rastreamento_em_transito.svg';
        $arr[42]['FC']['Objeto contém líquido, impossibilitando seu transporte - Em tratamento, aguarde'] = 'rastreamento_em_transito.svg';
        $arr[47]['FC']['Objeto será devolvido por solicitação do contratante/remetente - Em tratamento, aguarde.'] = 'rastreamento_em_transito.svg';
        $arr[51]['FC']['Objeto roubado'] = 'rastreamento_em_transito.svg';
        $arr[55]['FC']['Encomenda unitizada finalizada'] = 'rastreamento_em_transito.svg';
        $arr[13]['LDI']['Objeto encaminhado para retirada no endereço indicado - Para retirá-lo, é preciso informar o código do objeto.'] = 'rastreamento_em_transito.svg';
        $arr[10]['PAR']['Fiscalização aduaneira finalizada'] = 'rastreamento_em_transito.svg';
        $arr[11]['PAR']['Aguardando pagamento'] = 'rastreamento_em_transito.svg';
        $arr[12]['PAR']['Informações prestadas pelo cliente em análise'] = 'rastreamento_em_transito.svg';
        $arr[13]['PAR']['Objeto devolvido ao país de origem'] = 'rastreamento_em_transito.svg';
        $arr[14]['PAR']['Revisão de tributos solicitada pelo cliente'] = 'rastreamento_em_transito.svg';
        $arr[19]['PAR']['Fiscalização aduaneira finalizada no país de destino'] = 'rastreamento_em_transito.svg';
        $arr[20]['PAR']['Objeto recebido pelos Correios do Brasil com embalagem danificada - Por favor, aguarde'] = 'rastreamento_em_transito.svg';
        $arr[21]['PAR']['Encaminhado para fiscalização aduaneira'] = 'rastreamento_em_transito.svg';
        $arr[22]['PAR']['Encaminhado para fiscalização no país de destino'] = 'rastreamento_em_transito.svg';
        $arr[23]['PAR']['Aguardando autorização da Receita Federal para devolução do objeto ao remetente'] = 'rastreamento_em_transito.svg';
        $arr[24]['PAR']['Devolução autorizada pela Receita Federal'] = 'rastreamento_em_transito.svg';
        $arr[25]['PAR']['Faltam informações. Sua ação é necessária'] = 'rastreamento_em_transito.svg';
        $arr[26]['PAR']['Destinatário recusou o objeto - Objeto em análise de destinação'] = 'rastreamento_em_transito.svg';
        $arr[27]['PAR']['Despacho Postal pago na origem'] = 'rastreamento_em_transito.svg';
        $arr[28]['PAR']['Encaminhado para a unidade de tratamento'] = 'rastreamento_em_transito.svg';
        $arr[29]['PAR']['PAR 29'] = 'rastreamento_em_transito.svg';
        $arr[30]['PAR']['Aguardando pagamento do despacho postal'] = 'rastreamento_em_transito.svg';
        $arr[31]['PAR']['Pagamento confirmado'] = 'rastreamento_em_transito.svg';
        $arr[32]['PAR']['Aguardando confirmação de pagamento pela operadora'] = 'rastreamento_em_transito.svg';
        $arr[33]['PAR']['Pagamento não confirmado pela operadora - Entre em contato com sua operadora'] = 'rastreamento_em_transito.svg';
        $arr[34]['PAR']['Liberado sem tributação'] = 'rastreamento_em_transito.svg';
        $arr[40]['PAR']['Devolução a pedido - Cliente solicitou a devolução da encomenda'] = 'rastreamento_em_transito.svg';
        $arr[41]['PAR']['Prazo de pagamento encerrado - Objeto em análise de destinação'] = 'rastreamento_em_transito.svg';
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
