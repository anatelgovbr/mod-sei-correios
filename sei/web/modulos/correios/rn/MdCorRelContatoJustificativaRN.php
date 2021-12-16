<?

require_once dirname(__FILE__) . '/../../../SEI.php';

class MdCorRelContatoJustificativaRN extends InfraRN
{

    public function __construct()
    {
        parent::__construct();
    }

    protected function inicializarObjInfraIBanco()
    {
        return BancoSEI::getInstance();
    }

    protected function cadastrarControlado(MdCorRelContatoJustificativaDTO $objDTO)
    {
        try {

            SessaoSEI::getInstance()->validarPermissao('md_cor_rel_contato_justificativa_cadastrar');

            $objMdCorContatoJustificativaBD = new MdCorRelContatoJustificativaBD($this->getObjInfraIBanco());
            $ret = $objMdCorContatoJustificativaBD->cadastrar($objDTO);

            return $ret;

        } catch (Exception $e) {
            throw new InfraException('Erro cadastrando projeto.', $e);
        }
    }

    protected function alterarControlado(MdCorRelContatoJustificativaDTO $objDTO)
    {
        try {

            SessaoSEI::getInstance()->validarPermissao('md_cor_rel_contato_justificativa_alterar');

            $objMdCorContatoJustificativaBD = new MdCorRelContatoJustificativaBD($this->getObjInfraIBanco());
            $objMdCorContatoJustificativaBD->alterar($objDTO);

        } catch (Exception $e) {
            throw new InfraException('Erro alterando projeto.', $e);
        }
    }

    protected function excluirControlado($objMdCorContJustDTO)
    {
        try {

            SessaoSEI::getInstance()->validarPermissao('md_cor_rel_contato_justificativa_excluir');

            $objMdCorContatoJustificativaBD = new MdCorRelContatoJustificativaBD($this->getObjInfraIBanco());
            $objMdCorContatoJustificativaBD->excluir($objMdCorContJustDTO);

        } catch (Exception $e) {
            throw new InfraException('Erro excluindo projeto.', $e);
        }
    }

    protected function listarConectado(MdCorRelContatoJustificativaDTO $objDTO)
    {
        try {

            $objMdCorContatoJustificativaBD = new MdCorRelContatoJustificativaBD($this->getObjInfraIBanco());
            $ret = $objMdCorContatoJustificativaBD->listar($objDTO);

            return $ret;

        } catch (Exception $e) {
            throw new InfraException('Erro listando projetos.', $e);
        }
    }

    protected function consultarConectado(MdCorRelContatoJustificativaDTO $objDTO)
    {
        try {

            $objMdCorContatoJustificativaBD = new MdCorRelContatoJustificativaBD($this->getObjInfraIBanco());
            $ret = $objMdCorContatoJustificativaBD->consultar($objDTO);

            return $ret;

        } catch (Exception $e) {
            throw new InfraException('Erro consultando projeto.', $e);
        }
    }

}

?>
