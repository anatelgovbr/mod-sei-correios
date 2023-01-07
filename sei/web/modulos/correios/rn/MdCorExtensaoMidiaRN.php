<?
/**
 * TRIBUNAL REGIONAL FEDERAL DA 4� REGI�O
 *
 * 20/12/2016 - criado por Wilton J�nior
 *
 * Vers�o do Gerador de C�digo: 1.39.0
 */

require_once dirname(__FILE__) . '/../../../SEI.php';

class MdCorExtensaoMidiaRN extends InfraRN
{

    public function __construct()
    {
        parent::__construct();
    }

    protected function inicializarObjInfraIBanco()
    {
        return BancoSEI::getInstance();
    }

    private function validarStrNomeExtensao(MdCorExtensaoMidiaDTO $objMdCorExtensaoMidiaDTO, InfraException $objInfraException)
    {
        if (InfraString::isBolVazia($objMdCorExtensaoMidiaDTO->getStrNomeExtensao())) {
            $objInfraException->adicionarValidacao('Extens�o n�o informada.');
        } else {
            $objMdCorExtensaoMidiaDTO->setStrNomeExtensao(trim($objMdCorExtensaoMidiaDTO->getStrNomeExtensao()));

            if (strlen($objMdCorExtensaoMidiaDTO->getStrNomeExtensao()) > 10) {
                $objInfraException->adicionarValidacao('Extens�o possui tamanho superior a 10 caracteres.');
            }
        }
    }

    private function validarStrSinAtivo(MdCorExtensaoMidiaDTO $objMdCorExtensaoMidiaDTO, InfraException $objInfraException)
    {
        if (InfraString::isBolVazia($objMdCorExtensaoMidiaDTO->getStrSinAtivo())) {
            $objInfraException->adicionarValidacao('Sinalizador de Exclus�o L�gica n�o informado.');
        } else {
            if (!InfraUtil::isBolSinalizadorValido($objMdCorExtensaoMidiaDTO->getStrSinAtivo())) {
                $objInfraException->adicionarValidacao('Sinalizador de Exclus�o L�gica inv�lido.');
            }
        }
    }

    protected function cadastrarControlado(MdCorExtensaoMidiaDTO $objMdCorExtensaoMidiaDTO)
    {
        try {

            //Valida Permissao
            SessaoSEI::getInstance()->validarPermissao('md_cor_extensao_midia_cadastrar');

            //Regras de Negocio
            $objInfraException = new InfraException();

            //$this->validarStrNomeExtensao($objMdCorExtensaoMidiaDTO, $objInfraException);
            $this->validarStrSinAtivo($objMdCorExtensaoMidiaDTO, $objInfraException);

            $objInfraException->lancarValidacoes();

            $objMdCorExtensaoMidiaBD = new MdCorExtensaoMidiaBD($this->getObjInfraIBanco());
            $ret = $objMdCorExtensaoMidiaBD->cadastrar($objMdCorExtensaoMidiaDTO);

            //Auditoria

            return $ret;

        } catch (Exception $e) {
            throw new InfraException('Erro cadastrando a.', $e);
        }
    }

    protected function alterarControlado(MdCorExtensaoMidiaDTO $objMdCorExtensaoMidiaDTO)
    {
        try {

            //Valida Permissao
            SessaoSEI::getInstance()->validarPermissao('md_cor_extensao_midia_alterar');

            //Regras de Negocio
            $objInfraException = new InfraException();

            if ($objMdCorExtensaoMidiaDTO->isSetStrNomeExtensao()) {
                $this->validarStrNomeExtensao($objMdCorExtensaoMidiaDTO, $objInfraException);
            }
            if ($objMdCorExtensaoMidiaDTO->isSetStrSinAtivo()) {
                $this->validarStrSinAtivo($objMdCorExtensaoMidiaDTO, $objInfraException);
            }

            $objInfraException->lancarValidacoes();

            $objMdCorExtensaoMidiaBD = new MdCorExtensaoMidiaBD($this->getObjInfraIBanco());
            $objMdCorExtensaoMidiaBD->alterar($objMdCorExtensaoMidiaDTO);

            //Auditoria

        } catch (Exception $e) {
            throw new InfraException('Erro alterando a.', $e);
        }
    }

    protected function excluirControlado($arrObjMdCorExtensaoMidiaDTO)
    {
        try {

            //Valida Permissao
            SessaoSEI::getInstance()->validarPermissao('md_cor_extensao_midia_excluir');

            //Regras de Negocio
            //$objInfraException = new InfraException();

            //$objInfraException->lancarValidacoes();

            $objMdCorExtensaoMidiaBD = new MdCorExtensaoMidiaBD($this->getObjInfraIBanco());
            foreach($arrObjMdCorExtensaoMidiaDTO as $objMdCorExtensaoMidiaDTO){
                $objMdCorExtensaoMidiaBD->excluir($objMdCorExtensaoMidiaDTO);
            }

            //Auditoria

        } catch (Exception $e) {
            throw new InfraException('Erro excluindo a.', $e);
        }
    }

    protected function consultarConectado(MdCorExtensaoMidiaDTO $objMdCorExtensaoMidiaDTO)
    {
        try {

            //Valida Permissao
            SessaoSEI::getInstance()->validarPermissao('md_cor_extensao_midia_consultar');

            //Regras de Negocio
            //$objInfraException = new InfraException();

            //$objInfraException->lancarValidacoes();

            $objMdCorExtensaoMidiaBD = new MdCorExtensaoMidiaBD($this->getObjInfraIBanco());
            $ret = $objMdCorExtensaoMidiaBD->consultar($objMdCorExtensaoMidiaDTO);

            //Auditoria

            return $ret;
        } catch (Exception $e) {
            throw new InfraException('Erro consultando a.', $e);
        }
    }

    protected function listarConectado(MdCorExtensaoMidiaDTO $objMdCorExtensaoMidiaDTO)
    {
        try {

            //Valida Permissao
            SessaoSEI::getInstance()->validarPermissao('md_cor_extensao_midia_listar');

            //Regras de Negocio
            //$objInfraException = new InfraException();

            //$objInfraException->lancarValidacoes();

            $objMdCorExtensaoMidiaBD = new MdCorExtensaoMidiaBD($this->getObjInfraIBanco());
            $ret = $objMdCorExtensaoMidiaBD->listar($objMdCorExtensaoMidiaDTO);

            //Auditoria

            return $ret;

        } catch (Exception $e) {
            throw new InfraException('Erro listando as.', $e);
        }
    }

    protected function contarConectado(MdCorExtensaoMidiaDTO $objMdCorExtensaoMidiaDTO)
    {
        try {

            //Valida Permissao
            SessaoSEI::getInstance()->validarPermissao('md_cor_extensao_midia_listar');

            //Regras de Negocio
            //$objInfraException = new InfraException();

            //$objInfraException->lancarValidacoes();

            $objMdCorExtensaoMidiaBD = new MdCorExtensaoMidiaBD($this->getObjInfraIBanco());
            $ret = $objMdCorExtensaoMidiaBD->contar($objMdCorExtensaoMidiaDTO);

            //Auditoria

            return $ret;
        } catch (Exception $e) {
            throw new InfraException('Erro contando as.', $e);
        }
    }

    protected function desativarControlado($arrObjMdCorExtensaoMidiaDTO)
    {
        try {

            //Valida Permissao
            SessaoSEI::getInstance()->validarPermissao('md_cor_extensao_midia_desativar');

            //Regras de Negocio
            //$objInfraException = new InfraException();

            //$objInfraException->lancarValidacoes();

            $objMdCorExtensaoMidiaBD = new MdCorExtensaoMidiaBD($this->getObjInfraIBanco());
            for ($i = 0; $i < count($arrObjMdCorExtensaoMidiaDTO); $i++) {
                $objMdCorExtensaoMidiaBD->desativar($arrObjMdCorExtensaoMidiaDTO[$i]);
            }

            //Auditoria

        } catch (Exception $e) {
            throw new InfraException('Erro desativando a.', $e);
        }
    }

    protected function reativarControlado($arrObjMdCorExtensaoMidiaDTO)
    {
        try {

            //Valida Permissao
            SessaoSEI::getInstance()->validarPermissao('md_cor_extensao_midia_reativar');

            //Regras de Negocio
            //$objInfraException = new InfraException();

            //$objInfraException->lancarValidacoes();

            $objMdCorExtensaoMidiaBD = new MdCorExtensaoMidiaBD($this->getObjInfraIBanco());
            for ($i = 0; $i < count($arrObjMdCorExtensaoMidiaDTO); $i++) {
                $objMdCorExtensaoMidiaBD->reativar($arrObjMdCorExtensaoMidiaDTO[$i]);
            }

            //Auditoria

        } catch (Exception $e) {
            throw new InfraException('Erro reativando a.', $e);
        }
    }

    protected function bloquearControlado(MdCorExtensaoMidiaDTO $objMdCorExtensaoMidiaDTO)
    {
        try {

            //Valida Permissao
            SessaoSEI::getInstance()->validarPermissao('md_cor_extensao_midia_consultar');

            //Regras de Negocio
            //$objInfraException = new InfraException();

            //$objInfraException->lancarValidacoes();

            $objMdCorExtensaoMidiaBD = new MdCorExtensaoMidiaBD($this->getObjInfraIBanco());
            $ret = $objMdCorExtensaoMidiaBD->bloquear($objMdCorExtensaoMidiaDTO);

            //Auditoria

            return $ret;
        } catch (Exception $e) {
            throw new InfraException('Erro bloqueando a.', $e);
        }
    }


}

?>