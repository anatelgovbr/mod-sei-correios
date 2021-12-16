<?
/**
 * TRIBUNAL REGIONAL FEDERAL DA 4ª REGIÃO
 *
 * 09/06/2017 - criado por jaqueline.mendes
 *
 * Versão do Gerador de Código: 1.40.0
 */

require_once dirname(__FILE__) . '/../../../SEI.php';

class MdCorAgendamentoAutomaticoRN extends InfraRN
{

    public function __construct()
    {
        parent::__construct();
    }

    protected function inicializarObjInfraIBanco()
    {
        return BancoSEI::getInstance();
    }

    /* Método responsável por atualizar o andamento dos objs no correios */
    protected function atualizarAndamentoObjetosConectado()
    {

        try {
            ini_set('max_execution_time', '0');
            ini_set('memory_limit', '-1');

            InfraDebug::getInstance()->setBolLigado(true);
            InfraDebug::getInstance()->setBolDebugInfra(false);
            InfraDebug::getInstance()->setBolEcho(false);
            InfraDebug::getInstance()->limpar();

            $numSeg = InfraUtil::verificarTempoProcessamento();
            InfraDebug::getInstance()->gravar('ATUALIZANDO ANDAMENTO DO RASTREAMENTO DOS OBJETOS NOS CORREIOS');

            $objMdCorExpedicaoAndamentoRN = new MdCorExpedicaoAndamentoRN();
            $retornoArrFalhas = $objMdCorExpedicaoAndamentoRN->salvarAndamento();

            $numSeg = InfraUtil::verificarTempoProcessamento($numSeg);

            InfraDebug::getInstance()->gravar('TEMPO TOTAL DE EXECUCAO: ' . $numSeg . ' s');

            if ($retornoArrFalhas) {
                InfraDebug::getInstance()->gravar('OBJETOS COM FALHA');
                foreach ($retornoArrFalhas as $falha) {
                    InfraDebug::getInstance()->gravar($falha['numero'] . ' - ' . $falha['erro']);
                }
            }

            InfraDebug::getInstance()->gravar('FIM');

            LogSEI::getInstance()->gravar(InfraDebug::getInstance()->getStrDebug(), InfraLog::$INFORMACAO);

            InfraDebug::getInstance()->setBolLigado(false);
            InfraDebug::getInstance()->setBolDebugInfra(false);
            InfraDebug::getInstance()->setBolEcho(false);

        } catch (Exception $e) {

            InfraDebug::getInstance()->setBolLigado(false);
            InfraDebug::getInstance()->setBolDebugInfra(false);
            InfraDebug::getInstance()->setBolEcho(false);
            throw new InfraException('Erro atualizando o andamento do rastreamento dos objetos nos Correios.', $e);
        }

    }


}
