<?

require_once dirname(__FILE__) . '/../../../SEI.php';

class MdCorJustificativaRN extends InfraRN
{

    public function __construct()
    {
        parent::__construct();
    }

    protected function inicializarObjInfraIBanco()
    {
        return BancoSEI::getInstance();
    }

    protected function cadastrarControlado(MdCorJustificativaDTO $objDTO)
    {
        try {

            SessaoSEI::getInstance()->validarPermissao('md_cor_justificativa_cadastrar', __METHOD__, $objDTO);

            #Regras de Negocio
            $objInfraException = new InfraException();

            $this->validarStrNome($objDTO, $objInfraException);

            if ($objDTO->isSetStrNome()) {
                $this->_validarDuplicidadeNome($objDTO, $objInfraException);
            }

            $objInfraException->lancarValidacoes();

            $objMdCorJustificativaBD = new MdCorJustificativaBD($this->getObjInfraIBanco());
            $ret = $objMdCorJustificativaBD->cadastrar($objDTO);

            return $ret;

        } catch (Exception $e) {
            throw new InfraException('Erro cadastrando justificativa.', $e);
        }
    }

    protected function alterarControlado(MdCorJustificativaDTO $objDTO)
    {
        try {

            SessaoSEI::getInstance()->validarPermissao('md_cor_justificativa_alterar', __METHOD__, $objDTO);

            $objInfraException = new InfraException();

            $this->validarStrNome($objDTO, $objInfraException);
            $this->_validarDuplicidadeNome($objDTO, $objInfraException, false);

            $objInfraException->lancarValidacoes();

            $objMdCorJustificativaBD = new MdCorJustificativaBD($this->getObjInfraIBanco());
            $objMdCorJustificativaBD->alterar($objDTO);

        } catch (Exception $e) {
            throw new InfraException('Erro alterando justificativa.', $e);
        }
    }

    protected function excluirControlado( MdCorJustificativaDTO $objMdCorJustDTO)
    {
        try {

            SessaoSEI::getInstance()->validarPermissao('md_cor_justificativa_excluir');

            //Regras de Negocio
            $objInfraException = new InfraException();
            $this->validarVinculoComDestinatarioNaoHabilitado($objMdCorJustDTO, $objInfraException);
            $objInfraException->lancarValidacoes();

            $objMdCorJustificativaBD = new MdCorJustificativaBD($this->getObjInfraIBanco());
            $objMdCorJustificativaBD->excluir($objMdCorJustDTO);

        } catch (Exception $e) {
            throw new InfraException('Erro excluindo justificativa.', $e);
        }
    }

    private function validarVinculoComDestinatarioNaoHabilitado( MdCorJustificativaDTO $objMdCorJustDTO, InfraException $objInfraException)
    {
        $objMdCorRelContJustBD = new MdCorRelContatoJustificativaBD($this->getObjInfraIBanco());

        $objMdCorRelContJustDTO = new MdCorRelContatoJustificativaDTO();
        $objMdCorRelContJustDTO->retNumIdContato();
        $objMdCorRelContJustDTO->setNumIdMdCorJustificativa($objMdCorJustDTO->getNumIdMdCorJustificativa());
        $objMdCorRelContJustDTO = $objMdCorRelContJustBD->listar($objMdCorRelContJustDTO);

        if (count($objMdCorRelContJustDTO) > 0) {
            $objInfraException->adicionarValidacao('A justificativa selecionada não pode ser excluída, porque tem vínculo com destinatário não habilitado para expedição.');
        }
    }

    protected function listarConectado(MdCorJustificativaDTO $objDTO)
    {
        try {

            $objMdCorJustificativaBD = new MdCorJustificativaBD($this->getObjInfraIBanco());
            $ret = $objMdCorJustificativaBD->listar($objDTO);

            return $ret;

        } catch (Exception $e) {
            throw new InfraException('Erro listando justificativas.', $e);
        }
    }

    protected function consultarConectado(MdCorJustificativaDTO $objDTO)
    {
        try {

            $objMdCorJustificativaBD = new MdCorJustificativaBD($this->getObjInfraIBanco());
            $ret = $objMdCorJustificativaBD->consultar($objDTO);

            return $ret;

        } catch (Exception $e) {
            throw new InfraException('Erro consultando justificativa.', $e);
        }
    }

    protected function desativarControlado($objDTO)
    {
        try {

            SessaoSEI::getInstance()->validarPermissao('md_cor_justificativa_desativar');

            $objMdCorListaStatusBD = new MdCorListaStatusBD($this->getObjInfraIBanco());
            $objMdCorListaStatusBD->desativar($objDTO);

        } catch (Exception $e) {
            throw new InfraException('Erro desativando justificativa.', $e);
        }
    }

    protected function reativarControlado($objDTO)
    {
        try {

            SessaoSEI::getInstance()->validarPermissao('md_cor_justificativa_reativar');

            $objMdCorListaStatusBD = new MdCorListaStatusBD($this->getObjInfraIBanco());
            $objMdCorListaStatusBD->reativar($objDTO);

        } catch (Exception $e) {
            throw new InfraException('Erro reativando justificativa.', $e);
        }
    }

    private function validarStrNome(MdCorJustificativaDTO $objObrigacaoJustificativaDTO, InfraException $objInfraException)
    {
        if (InfraString::isBolVazia($objObrigacaoJustificativaDTO->getStrNome())) {
            $objInfraException->adicionarValidacao('Nome não informado.');
        } else {
            $objObrigacaoJustificativaDTO->setStrNome(trim($objObrigacaoJustificativaDTO->getStrNome()));
        }
    }

    private function _validarDuplicidadeNome(MdCorJustificativaDTO $objMdCorJustificativaDTO, InfraException $objInfraException, $cadastrar = true)
    {
        try {

            $msg = 'A Justificativa informada já existe.';

            $objMdCorJustificativaDTO2 = new MdCorJustificativaDTO();
            $objMdCorJustificativaDTO2->setNumIdMdCorJustificativa($objMdCorJustificativaDTO->getNumIdMdCorJustificativa());

            $objMdCorJustificativaBD = new MdCorJustificativaBD($this->getObjInfraIBanco());


            if ($cadastrar) {
                $ret = $objMdCorJustificativaBD->contar($objMdCorJustificativaDTO2);

                if ($ret > 0) {
                    $objInfraException->adicionarValidacao($msg);
                }

            } else {

                $dtoValidacao = new MdCorJustificativaDTO();
                $dtoValidacao->setNumIdMdCorJustificativa($objMdCorJustificativaDTO->getNumIdMdCorJustificativa(), InfraDTO::$OPER_DIFERENTE);
                $dtoValidacao->setStrNome($objMdCorJustificativaDTO->getStrNome(), InfraDTO::$OPER_IGUAL);

                $retDuplicidade = $objMdCorJustificativaBD->contar($dtoValidacao);

                if ($retDuplicidade > 0) {
                    $objInfraException->adicionarValidacao($msg);
                }
            }

        } catch (Exception $e) {
            throw new InfraException('Erro realizando verificação duplicidade.', $e);
        }
    }
}

?>
