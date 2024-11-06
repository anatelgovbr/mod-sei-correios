<?
require_once dirname(__FILE__) . '/../web/SEI.php';

class MdCorAtualizadorSeiRN extends InfraRN
{

    private $numSeg = 0;
    private $versaoAtualDesteModulo = '2.3.0';
    private $nomeDesteModulo = 'MÓDULO CORREIOS';
    private $nomeParametroModulo = 'VERSAO_MODULO_CORREIOS';
    private $historicoVersoes = array('1.0.0', '2.0.0', '2.1.0','2.2.0','2.3.0');

    public function __construct()
    {
        parent::__construct();
    }

    protected function inicializarObjInfraIBanco()
    {
        return BancoSEI::getInstance();
    }

    private function inicializar($strTitulo)
    {
        session_start();
        SessaoSEI::getInstance(false);

        ini_set('max_execution_time', '0');
        ini_set('memory_limit', '-1');
        @ini_set('implicit_flush', '1');
        ob_implicit_flush();

        InfraDebug::getInstance()->setBolLigado(true);
        InfraDebug::getInstance()->setBolDebugInfra(true);
        InfraDebug::getInstance()->setBolEcho(true);
        InfraDebug::getInstance()->limpar();

        $this->numSeg = InfraUtil::verificarTempoProcessamento();

        $this->logar($strTitulo);
    }

    private function logar($strMsg)
    {
        InfraDebug::getInstance()->gravar($strMsg);
        flush();
    }

    private function finalizar($strMsg = null, $bolErro = false)
    {
        if (!$bolErro) {
            $this->numSeg = InfraUtil::verificarTempoProcessamento($this->numSeg);
            $this->logar('TEMPO TOTAL DE EXECUÇÃO: ' . $this->numSeg . ' s');
        } else {
            $strMsg = 'ERRO: ' . $strMsg;
        }

        if ($strMsg != null) {
            $this->logar($strMsg);
        }

        InfraDebug::getInstance()->setBolLigado(false);
        InfraDebug::getInstance()->setBolDebugInfra(false);
        InfraDebug::getInstance()->setBolEcho(false);
        $this->numSeg = 0;
        die;
    }

    protected function atualizarVersaoConectado()
    {

        try {
            $this->inicializar('INICIANDO A INSTALAÇÃO/ATUALIZAÇÃO DO ' . $this->nomeDesteModulo . ' NO SEI VERSÃO ' . SEI_VERSAO);

            //checando BDs suportados
            if (!(BancoSEI::getInstance() instanceof InfraMySql) &&
                !(BancoSEI::getInstance() instanceof InfraSqlServer) &&
                !(BancoSEI::getInstance() instanceof InfraOracle)) {
                $this->finalizar('BANCO DE DADOS NÃO SUPORTADO: ' . get_parent_class(BancoSEI::getInstance()), true);
            }

            //testando versao do framework
            $numVersaoInfraRequerida = '2.0.18';
            if (version_compare(VERSAO_INFRA, $numVersaoInfraRequerida) < 0) {
                $this->finalizar('VERSÃO DO FRAMEWORK PHP INCOMPATÍVEL (VERSÃO ATUAL ' . VERSAO_INFRA . ', SENDO REQUERIDA VERSÃO IGUAL OU SUPERIOR A ' . $numVersaoInfraRequerida . ')', true);
            }

            //checando permissoes na base de dados
            $objInfraMetaBD = new InfraMetaBD(BancoSEI::getInstance());

            if (count($objInfraMetaBD->obterTabelas('sei_teste')) == 0) {
                BancoSEI::getInstance()->executarSql('CREATE TABLE sei_teste (id ' . $objInfraMetaBD->tipoNumero() . ' null)');
            }

            BancoSEI::getInstance()->executarSql('DROP TABLE sei_teste');

            $objInfraParametro = new InfraParametro(BancoSEI::getInstance());

            $strVersaoModuloCorreio = $objInfraParametro->getValor($this->nomeParametroModulo, false);

            switch ($strVersaoModuloCorreio) {
                case '':
                    $this->instalarv100();
                case '1.0.0':
                    $this->instalarv200();
                case '2.0.0':
                    $this->instalarv210();
                case '2.1.0':
	                $this->instalarv220();
	            case '2.2.0':
	                $this->instalarv230();
                    break;

                default:
                    $this->finalizar('A VERSÃO MAIS ATUAL DO ' . $this->nomeDesteModulo . ' (v' . $this->versaoAtualDesteModulo . ') JÁ ESTÁ INSTALADA.');
                    break;

            }

            $this->finalizar('FIM');
            InfraDebug::getInstance()->setBolDebugInfra(true);
        } catch (Exception $e) {
            InfraDebug::getInstance()->setBolLigado(true);
            InfraDebug::getInstance()->setBolDebugInfra(true);
            InfraDebug::getInstance()->setBolEcho(true);
            throw new InfraException('Erro instalando/atualizando versão.', $e);
        }
    }

    private function instalarv100()
    {

        $objInfraParametro = new InfraParametro(BancoSEI::getInstance());
        $objInfraMetaBD = new InfraMetaBD(BancoSEI::getInstance());
        $this->logar('EXECUTANDO A INSTALAÇÃO/ATUALIZAÇÃO DA VERSAO 1.0.0 DO ' . $this->nomeDesteModulo . ' NA BASE DO SEI');

        $this->logar('CRIANDO A TABELA md_cor_serie_exp');
        BancoSEI::getInstance()->executarSql('CREATE TABLE md_cor_serie_exp (
			  id_serie ' . $objInfraMetaBD->tipoNumero() . ' NOT NULL,
			  sin_ativo ' . $objInfraMetaBD->tipoTextoFixo(1) . ' NOT NULL
			)');

        $objInfraMetaBD->adicionarChaveEstrangeira('fk1_md_cor_serie_exp', 'md_cor_serie_exp', array('id_serie'), 'serie', array('id_serie'));
        $objInfraMetaBD->adicionarChavePrimaria('md_cor_serie_exp', 'pk_md_cor_serie_exp', array('id_serie'));


        $this->logar('CRIANDO A TABELA md_cor_diretoria');
        BancoSEI::getInstance()->executarSql('CREATE TABLE md_cor_diretoria (
			  id_md_cor_diretoria ' . $objInfraMetaBD->tipoNumero() . ' NOT NULL,
			  codigo_diretoria ' . $objInfraMetaBD->tipoTextoFixo(3) . ' NOT NULL,
			  descricao_diretoria ' . $objInfraMetaBD->tipoTextoFixo(150) . ' NOT NULL,
			  sigla_diretoria ' . $objInfraMetaBD->tipoTextoFixo(5) . ' NOT NULL
			)');
        $objInfraMetaBD->adicionarChavePrimaria('md_cor_diretoria', 'pk_md_cor_diretoria', array('id_md_cor_diretoria'));

        $this->logar('CRIANDO A SEQUENCE seq_md_cor_diretoria');
        BancoSEI::getInstance()->criarSequencialNativa('seq_md_cor_diretoria', 1);

        $this->logar('POPULANDO TABELA md_cor_diretoria PARA DADOS DE DOMÍNIO DA DIRETORIA');
        $mdCorDiretoriaRN = new MdCorDiretoriaRN();
        $arrDiretoria = [
            ['codigo_diretoria' => '75', 'descricao_diretoria' => 'DR-TOCANTINS', 'sigla_diretoria' => 'TO'],
            ['codigo_diretoria' => '74', 'descricao_diretoria' => 'DR-SÃO PAULO INTERIOR', 'sigla_diretoria' => 'SPI'],
            ['codigo_diretoria' => '72', 'descricao_diretoria' => 'DR-SÃO PAULO ', 'sigla_diretoria' => 'SPM'],
            ['codigo_diretoria' => '70', 'descricao_diretoria' => 'DR-SERGIPE ', 'sigla_diretoria' => 'SE'],
            ['codigo_diretoria' => '68', 'descricao_diretoria' => 'DR-SANTA CATARINA', 'sigla_diretoria' => 'SC'],
            ['codigo_diretoria' => '65', 'descricao_diretoria' => 'DR-RORAIMA ', 'sigla_diretoria' => 'RR'],
            ['codigo_diretoria' => '64', 'descricao_diretoria' => 'DR-RIO GRANDE DO SUL ', 'sigla_diretoria' => 'RS'],
            ['codigo_diretoria' => '60', 'descricao_diretoria' => 'DR-RIO GRANDE DO NORTE', 'sigla_diretoria' => 'RN'],
            ['codigo_diretoria' => '50', 'descricao_diretoria' => 'DR-RIO DE JANEIRO', 'sigla_diretoria' => 'RJ'],
            ['codigo_diretoria' => '36', 'descricao_diretoria' => 'DR-PARANÁ', 'sigla_diretoria' => 'PR'],
            ['codigo_diretoria' => '34', 'descricao_diretoria' => 'DR-PIAUÍ ', 'sigla_diretoria' => 'PI'],
            ['codigo_diretoria' => '32', 'descricao_diretoria' => 'DR-PERNAMBUCO', 'sigla_diretoria' => 'PE'],
            ['codigo_diretoria' => '30', 'descricao_diretoria' => 'DR-PARAÍBA ', 'sigla_diretoria' => 'PB'],
            ['codigo_diretoria' => '28', 'descricao_diretoria' => 'DR-PARÁ', 'sigla_diretoria' => 'PA'],
            ['codigo_diretoria' => '26', 'descricao_diretoria' => 'DR-RONDONIA', 'sigla_diretoria' => 'RO'],
            ['codigo_diretoria' => '24', 'descricao_diretoria' => 'DR-MATO GROSSO ', 'sigla_diretoria' => 'MT'],
            ['codigo_diretoria' => '22', 'descricao_diretoria' => 'DR-MATO GROSSO DO SUL', 'sigla_diretoria' => 'MS'],
            ['codigo_diretoria' => '20', 'descricao_diretoria' => 'DR-MINAS GERAIS', 'sigla_diretoria' => 'MG'],
            ['codigo_diretoria' => '18', 'descricao_diretoria' => 'DR-MARANHÃO', 'sigla_diretoria' => 'MA'],
            ['codigo_diretoria' => '16', 'descricao_diretoria' => 'DR-GOIÁS ', 'sigla_diretoria' => 'GO'],
            ['codigo_diretoria' => '14', 'descricao_diretoria' => 'DR-ESPIRITO SANTO', 'sigla_diretoria' => 'ES'],
            ['codigo_diretoria' => '12', 'descricao_diretoria' => 'DR-CEARÁ ', 'sigla_diretoria' => 'CE'],
            ['codigo_diretoria' => '10', 'descricao_diretoria' => 'DR-BRASÍLIA', 'sigla_diretoria' => 'BSB'],
            ['codigo_diretoria' => '08', 'descricao_diretoria' => 'DR-BAHIA ', 'sigla_diretoria' => 'BA'],
            ['codigo_diretoria' => '06', 'descricao_diretoria' => 'DR-AMAZONAS', 'sigla_diretoria' => 'AM'],
            ['codigo_diretoria' => '05', 'descricao_diretoria' => 'DR-AMAPÁ ', 'sigla_diretoria' => 'AP'],
            ['codigo_diretoria' => '04', 'descricao_diretoria' => 'DR-ALAGOAS ', 'sigla_diretoria' => 'AL'],
            ['codigo_diretoria' => '03', 'descricao_diretoria' => 'DR-ACRE', 'sigla_diretoria' => 'ACR'],
            ['codigo_diretoria' => '01', 'descricao_diretoria' => 'AC ADMINISTRAÇAO CENTRAL', 'sigla_diretoria' => 'AC']
        ];

        foreach ($arrDiretoria as $chave => $diretoria) {
            $mdCorDiretoriaDTO = new MdCorDiretoriaDTO();
            $mdCorDiretoriaDTO->setNumIdMdCorDiretoria($chave + 1);
            $mdCorDiretoriaDTO->setStrCodigoDiretoria($diretoria['codigo_diretoria']);
            $mdCorDiretoriaDTO->setStrDescricaoDiretoria($diretoria['descricao_diretoria']);
            $mdCorDiretoriaDTO->setStrSiglaDiretoria($diretoria['sigla_diretoria']);
            $mdCorDiretoriaRN->cadastrar($mdCorDiretoriaDTO);
        }

        $this->logar('CRIANDO A TABELA md_cor_contrato');
        BancoSEI::getInstance()->executarSql('CREATE TABLE md_cor_contrato (
			  id_md_cor_contrato ' . $objInfraMetaBD->tipoNumero() . ' NOT NULL,
			  numero_contrato ' . $objInfraMetaBD->tipoTextoFixo(50) . ' NOT NULL,
			  numero_contrato_correio ' . $objInfraMetaBD->tipoTextoFixo(50) . ' NOT NULL,
			  numero_cartao_postagem ' . $objInfraMetaBD->tipoTextoFixo(50) . ' NOT NULL,
			  url_webservice ' . $objInfraMetaBD->tipoTextoVariavel(2081) . ' NOT NULL,
			  numero_cnpj ' . $objInfraMetaBD->tipoNumeroGrande(20) . ' NOT NULL,
			  codigo_administrativo ' . $objInfraMetaBD->tipoTextoFixo(8) . ' NOT NULL,
			  usuario ' . $objInfraMetaBD->tipoTextoVariavel(100) . ' NOT NULL,
			  senha ' . $objInfraMetaBD->tipoTextoVariavel(100) . ' NOT NULL,
			  id_procedimento ' . $objInfraMetaBD->tipoNumeroGrande() . ' NULL    ,
			  sin_ativo ' . $objInfraMetaBD->tipoTextoFixo(1) . ' NOT NULL,			  
			  id_md_cor_diretoria ' . $objInfraMetaBD->tipoNumero() . ' NOT NULL,
			  numero_ano_contrato ' . $objInfraMetaBD->tipoNumero(4) . ' NOT NULL			  
			)');

        $objInfraMetaBD->adicionarChavePrimaria('md_cor_contrato', 'pk_md_cor_contrato', array('id_md_cor_contrato'));
        $objInfraMetaBD->adicionarChaveEstrangeira('fk1_md_cor_contrato', 'md_cor_contrato', array('id_procedimento'), 'procedimento', array('id_procedimento'));
        $objInfraMetaBD->adicionarChaveEstrangeira('fk2_md_cor_contrato', 'md_cor_contrato', array('id_md_cor_diretoria'), 'md_cor_diretoria', array('id_md_cor_diretoria'));

        $this->logar('CRIANDO A SEQUENCE seq_md_cor_contrato');
        BancoSEI::getInstance()->criarSequencialNativa('seq_md_cor_contrato', 1);

        $this->logar('CRIANDO A TABELA md_cor_tipo_correspondenc');
        BancoSEI::getInstance()->executarSql('CREATE TABLE md_cor_tipo_correspondenc (
			  id_md_cor_tipo_correspondenc ' . $objInfraMetaBD->tipoNumero() . ' NOT NULL,
			  nome_tipo ' . $objInfraMetaBD->tipoTextoFixo(50) . ' NOT NULL,
			  sin_ar ' . $objInfraMetaBD->tipoTextoFixo(1) . ' NOT NULL,			  
			  nome_imagem_chancela ' . $objInfraMetaBD->tipoTextoVariavel(200) . ' NOT NULL			  
		  )');

        $objInfraMetaBD->adicionarChavePrimaria('md_cor_tipo_correspondenc', 'pk_md_cor_tipo_correspondenc', array('id_md_cor_tipo_correspondenc'));

        $this->logar('CRIANDO A SEQUENCE seq_md_cor_tipo_correspondenc');
        BancoSEI::getInstance()->criarSequencialNativa('seq_md_cor_tipo_correspondenc', 1);

        $this->logar('POPULANDO TABELA DE DOMINIO DE TIPO DE CORRESPONDENCIA');
        $mdCorTipoCorrespondeciaRN = new MdCorTipoCorrespondencRN();
        $arrTipoCorrespondencia = [
            ['id_md_cor_tipo_correspondenc' => '1', 'nome_tipo' => 'Carta Simples', 'sin_ar' => 'N', 'nome_imagem_chancela' => 'chancela_carta.png'],
            ['id_md_cor_tipo_correspondenc' => '2', 'nome_tipo' => 'Carta Registrada', 'sin_ar' => 'S', 'nome_imagem_chancela' => 'chancela_carta.png'],
            ['id_md_cor_tipo_correspondenc' => '3', 'nome_tipo' => 'SEDEX Hoje', 'sin_ar' => 'S', 'nome_imagem_chancela' => 'chancela_sedex.png'],
            ['id_md_cor_tipo_correspondenc' => '4', 'nome_tipo' => 'SEDEX 10', 'sin_ar' => 'S', 'nome_imagem_chancela' => 'chancela_sedex.png'],
            ['id_md_cor_tipo_correspondenc' => '5', 'nome_tipo' => 'SEDEX 12', 'sin_ar' => 'S', 'nome_imagem_chancela' => 'chancela_sedex.png'],
            ['id_md_cor_tipo_correspondenc' => '6', 'nome_tipo' => 'SEDEX', 'sin_ar' => 'S', 'nome_imagem_chancela' => 'chancela_sedex_outro.png'],
            ['id_md_cor_tipo_correspondenc' => '7', 'nome_tipo' => 'PAC', 'sin_ar' => 'S', 'nome_imagem_chancela' => 'chancela_pac.png'],
        ];

        foreach ($arrTipoCorrespondencia as $chave => $tipo) {
            $mdCorTipoCorrespondeciaDTO = new MdCorTipoCorrespondencDTO();
            $mdCorTipoCorrespondeciaDTO->setNumIdMdCorTipoCorrespondenc($chave + 1);
            $mdCorTipoCorrespondeciaDTO->setStrNomeTipo($tipo['nome_tipo']);
            $mdCorTipoCorrespondeciaDTO->setStrSinAr($tipo['sin_ar']);
            $mdCorTipoCorrespondeciaDTO->setStrNomeImagemChancela($tipo['nome_imagem_chancela']);
            $mdCorTipoCorrespondeciaRN->cadastrar($mdCorTipoCorrespondeciaDTO);
        }


        $this->logar('CRIANDO A TABELA md_cor_tipo_objeto');
        BancoSEI::getInstance()->executarSql('CREATE TABLE md_cor_tipo_objeto (
			  id_md_cor_tipo_objeto ' . $objInfraMetaBD->tipoNumero() . ' NOT NULL,
			  nome ' . $objInfraMetaBD->tipoTextoFixo(100) . ' NOT NULL,		
			  codigo_correio ' . $objInfraMetaBD->tipoTextoFixo(4) . ' NOT NULL		  
			)');

        $objInfraMetaBD->adicionarChavePrimaria('md_cor_tipo_objeto', 'pk_md_cor_tipo_objeto', array('id_md_cor_tipo_objeto'));

        $this->logar('CRIANDO A SEQUENCE seq_md_cor_tipo_objeto');
        BancoSEI::getInstance()->criarSequencialNativa('seq_md_cor_tipo_objeto', 1);

        $this->logar('POPULANDO VALORES PADRÕES DO TIPO DE OBJETO NA TABELA md_cor_tipo_objeto');
        $objMdCorTipoObjetoDTO = new MdCorTipoObjetoDTO();
        $objMdCorTipoObjetoDTO->setNumIdMdCorTipoObjeto(1);
        $objMdCorTipoObjetoDTO->setStrNome('Envelope');
        $objMdCorTipoObjetoDTO->setStrCodigoCorreio('001');

        $objMdCorTipoObjetoRN = new MdCorTipoObjetoRN();
        $objMdCorTipoObjetoRN->cadastrar($objMdCorTipoObjetoDTO);

        $objMdCorTipoObjetoDTO = new MdCorTipoObjetoDTO();
        $objMdCorTipoObjetoDTO->setNumIdMdCorTipoObjeto(2);
        $objMdCorTipoObjetoDTO->setStrNome('Caixa');
        $objMdCorTipoObjetoDTO->setStrCodigoCorreio('002');

        $objMdCorTipoObjetoRN->cadastrar($objMdCorTipoObjetoDTO);

        $objMdCorTipoObjetoDTO = new MdCorTipoObjetoDTO();
        $objMdCorTipoObjetoDTO->setNumIdMdCorTipoObjeto(3);
        $objMdCorTipoObjetoDTO->setStrNome('Cilindro');
        $objMdCorTipoObjetoDTO->setStrCodigoCorreio('003');

        $objMdCorTipoObjetoRN->cadastrar($objMdCorTipoObjetoDTO);


        $this->logar('CRIANDO A TABELA md_cor_objeto');
        BancoSEI::getInstance()->executarSql('CREATE TABLE md_cor_objeto (
              id_md_cor_objeto ' . $objInfraMetaBD->tipoNumero() . ' NOT NULL,
              id_md_cor_tipo_objeto ' . $objInfraMetaBD->tipoNumero() . ' NOT NULL,		
              id_md_cor_contrato ' . $objInfraMetaBD->tipoNumero() . ' NOT NULL,		
              tipo_rotulo_impressao ' . $objInfraMetaBD->tipoTextoFixo(1) . ' NOT NULL,		
              sin_objeto_padrao ' . $objInfraMetaBD->tipoTextoFixo(1) . ' NOT NULL,		
              margem_superior_impressao ' . $objInfraMetaBD->tipoNumeroDecimal(19, 2) . ' NOT NULL,		
              margem_esquerda_impressao ' . $objInfraMetaBD->tipoNumeroDecimal(19, 2) . ' NOT NULL,							  
              sin_ativo ' . $objInfraMetaBD->tipoTextoFixo(1) . ' NOT NULL		
            )');

        $objInfraMetaBD->adicionarChavePrimaria('md_cor_objeto', 'pk_md_cor_objeto', array('id_md_cor_objeto'));
        $objInfraMetaBD->adicionarChaveEstrangeira('fk1_md_cor_objeto', 'md_cor_objeto', array('id_md_cor_tipo_objeto'), 'md_cor_tipo_objeto', array('id_md_cor_tipo_objeto'));
        $objInfraMetaBD->adicionarChaveEstrangeira('fk2_md_cor_objeto', 'md_cor_objeto', array('id_md_cor_contrato'), 'md_cor_contrato', array('id_md_cor_contrato'));

        $this->logar('CRIANDO A SEQUENCE seq_md_cor_objeto');
        BancoSEI::getInstance()->criarSequencialNativa('seq_md_cor_objeto', 1);

        $this->logar('CRIANDO A TABELA md_cor_plp');
        BancoSEI::getInstance()->executarSql('CREATE TABLE md_cor_plp (
			  id_md_cor_plp ' . $objInfraMetaBD->tipoNumero() . ' NOT NULL,
			  codigo_plp ' . $objInfraMetaBD->tipoNumeroGrande() . ' NOT NULL,
			  id_unidade_geradora ' . $objInfraMetaBD->tipoNumero() . ' NULL,
			  data_cadastro ' . $objInfraMetaBD->tipoDataHora() . ' NULL,
			  sta_plp ' . $objInfraMetaBD->tipoTextoFixo(1) . ' NOT NULL
			)');

        $objInfraMetaBD->adicionarChavePrimaria('md_cor_plp', 'pk_md_cor_plp', array('id_md_cor_plp'));

        $this->logar('CRIANDO A SEQUENCE seq_md_cor_plp');
        BancoSEI::getInstance()->criarSequencialNativa('seq_md_cor_plp', 1);

        $this->logar('CRIANDO A TABELA md_cor_servico_postal');
        BancoSEI::getInstance()->executarSql('CREATE TABLE md_cor_servico_postal (
			  id_md_cor_servico_postal ' . $objInfraMetaBD->tipoNumero() . ' NOT NULL,
			  id_md_cor_contrato ' . $objInfraMetaBD->tipoNumero() . ' NOT NULL,
			  nome ' . $objInfraMetaBD->tipoTextoVariavel(500) . ' NOT NULL,
			  expedicao_aviso_recebimento ' . $objInfraMetaBD->tipoTextoFixo(1) . '         ,
			  descricao ' . $objInfraMetaBD->tipoTextoVariavel(500) . ' NOT NULL,
			  id_ws_correios ' . $objInfraMetaBD->tipoTextoVariavel(50) . ' NULL,
			  codigo_ws_correios ' . $objInfraMetaBD->tipoTextoVariavel(50) . ' NULL,
			  sin_ativo ' . $objInfraMetaBD->tipoTextoFixo(1) . ' NOT NULL,			  
			  sin_servico_cobrar  ' . $objInfraMetaBD->tipoTextoFixo(1) . ' NOT NULL,			  
			  id_md_cor_tipo_correspondenc  ' . $objInfraMetaBD->tipoNumero() . ' NOT NULL			  
			)');

        $objInfraMetaBD->adicionarChavePrimaria('md_cor_servico_postal', 'pk_md_cor_servico_postal', array('id_md_cor_servico_postal'));
        $objInfraMetaBD->adicionarChaveEstrangeira('fk1_md_cor_servico_postal', 'md_cor_servico_postal', array('id_md_cor_contrato'), 'md_cor_contrato', array('id_md_cor_contrato'));
        $objInfraMetaBD->adicionarChaveEstrangeira('fk2_md_cor_servico_postal', 'md_cor_servico_postal', array('id_md_cor_tipo_correspondenc'), 'md_cor_tipo_correspondenc', array('id_md_cor_tipo_correspondenc'));

        $this->logar('CRIANDO A SEQUENCE seq_md_cor_servico_postal');
        BancoSEI::getInstance()->criarSequencialNativa('seq_md_cor_servico_postal', 1);

        $this->logar('CRIANDO A TABELA md_cor_unidade_exp');
        BancoSEI::getInstance()->executarSql('CREATE TABLE md_cor_unidade_exp (
			  id_unidade ' . $objInfraMetaBD->tipoNumero() . ' NOT NULL,
			  sin_ativo ' . $objInfraMetaBD->tipoTextoFixo(1) . ' NOT NULL
			)');

        $objInfraMetaBD->adicionarChaveEstrangeira('fk1_md_cor_unidade_exp', 'md_cor_unidade_exp', array('id_unidade'), 'unidade', array('id_unidade'));
        $objInfraMetaBD->adicionarChavePrimaria('md_cor_unidade_exp', 'pk_md_cor_unidade_exp', array('id_unidade'));

        $this->logar('CRIANDO A TABELA md_cor_map_unidade_exp');
        BancoSEI::getInstance()->executarSql('CREATE TABLE md_cor_map_unidade_exp (
			  id_unidade_exp ' . $objInfraMetaBD->tipoNumero() . ' NOT NULL,
			  id_unidade_solicitante ' . $objInfraMetaBD->tipoNumero() . ' NOT NULL,
			  sin_ativo ' . $objInfraMetaBD->tipoTextoFixo(1) . ' NOT NULL
			)');
        $objInfraMetaBD->adicionarChavePrimaria('md_cor_map_unidade_exp', 'pk_md_cor_map_unidade_exp', array('id_unidade_exp', 'id_unidade_solicitante'));
        $objInfraMetaBD->adicionarChaveEstrangeira('fk1_md_cor_map_unidade_exp', 'md_cor_map_unidade_exp', array('id_unidade_exp'), 'unidade', array('id_unidade'));
        $objInfraMetaBD->adicionarChaveEstrangeira('fk2_md_cor_map_unidade_exp', 'md_cor_map_unidade_exp', array('id_unidade_solicitante'), 'unidade', array('id_unidade'));

        $this->logar('CRIANDO A TABELA md_cor_map_unid_servico');
        BancoSEI::getInstance()->executarSql('CREATE TABLE md_cor_map_unid_servico (
			  id_md_cor_map_unid_servico ' . $objInfraMetaBD->tipoNumero() . ' NOT NULL,
			  id_unidade_solicitante ' . $objInfraMetaBD->tipoNumero() . ' NOT NULL,
			  id_md_cor_servico_postal ' . $objInfraMetaBD->tipoNumero() . ' NOT NULL,
			  sin_ativo ' . $objInfraMetaBD->tipoTextoFixo(1) . ' NOT NULL
			)');
        $objInfraMetaBD->adicionarChavePrimaria('md_cor_map_unid_servico', 'pk_md_cor_map_unid_servico', array('id_md_cor_map_unid_servico'));
        $objInfraMetaBD->adicionarChaveEstrangeira('fk1_md_cor_map_unid_servico', 'md_cor_map_unid_servico', array('id_unidade_solicitante'), 'unidade', array('id_unidade'));
        $objInfraMetaBD->adicionarChaveEstrangeira('fk2_md_cor_map_unid_servico', 'md_cor_map_unid_servico', array('id_md_cor_servico_postal'), 'md_cor_servico_postal', array('id_md_cor_servico_postal'));

        $this->logar('CRIANDO A SEQUENCE seq_md_cor_map_unid_servico');
        BancoSEI::getInstance()->criarSequencialNativa('seq_md_cor_map_unid_servico', 1);

        $this->logar('CRIANDO A TABELA md_cor_extensao_midia');
        BancoSEI::getInstance()->executarSql('CREATE TABLE md_cor_extensao_midia (
			  id_md_cor_extensao_midia ' . $objInfraMetaBD->tipoNumero() . ' NOT NULL,
			  id_arquivo_extensao ' . $objInfraMetaBD->tipoNumero() . ' NOT NULL,
			  sin_ativo ' . $objInfraMetaBD->tipoTextoFixo(1) . ' NOT NULL
			)');
        $objInfraMetaBD->adicionarChavePrimaria('md_cor_extensao_midia', 'pk_md_cor_extensao_midia', array('id_md_cor_extensao_midia'));
        $objInfraMetaBD->adicionarChaveEstrangeira('fk1_md_cor_extensao_midia', 'md_cor_extensao_midia', array('id_arquivo_extensao'), 'arquivo_extensao', array('id_arquivo_extensao'));

        BancoSEI::getInstance()->criarSequencialNativa('seq_md_cor_extensao_midia', 1);

        $this->logar('CRIANDO A TABELA md_cor_expedicao_solicitad');
        BancoSEI::getInstance()->executarSql('CREATE TABLE md_cor_expedicao_solicitad ( 
					id_md_cor_expedicao_solicitada ' . $objInfraMetaBD->tipoNumero() . ' NOT NULL ,
					sin_necessita_ar ' . $objInfraMetaBD->tipoTextoFixo(1) . ' NOT NULL ,
					id_documento_principal ' . $objInfraMetaBD->tipoNumeroGrande() . ' NOT NULL ,
					id_contato_destinatario ' . $objInfraMetaBD->tipoNumero() . ' NOT NULL ,
					observacao ' . $objInfraMetaBD->tipoTextoVariavel(250) . ' NULL ,
					id_md_cor_servico_postal ' . $objInfraMetaBD->tipoNumero() . ' NOT NULL ,
					id_unidade ' . $objInfraMetaBD->tipoNumero() . ' NOT NULL ,
					data_solicitacao ' . $objInfraMetaBD->tipoDataHora() . ' NOT NULL ,
					data_expedicao ' . $objInfraMetaBD->tipoDataHora() . ' NULL ,
					id_usuario_solicitante ' . $objInfraMetaBD->tipoNumero() . ' NOT NULL ,
					id_usuario_exp_autorizador ' . $objInfraMetaBD->tipoNumero() . ' NULL ,
					codigo_rastreamento ' . $objInfraMetaBD->tipoTextoVariavel(45) . ' NULL,
					sin_objeto_acessado ' . $objInfraMetaBD->tipoTextoVariavel(1) . ' NULL,
					id_md_cor_plp ' . $objInfraMetaBD->tipoNumero() . ' NULL,
					id_md_cor_objeto ' . $objInfraMetaBD->tipoNumero() . ' NULL,
					sin_recebido ' . $objInfraMetaBD->tipoTextoVariavel(1) . ' NOT NULL
			)');

        $objInfraMetaBD->adicionarChavePrimaria('md_cor_expedicao_solicitad', 'pk_md_cor_expedicao_solicitad', array('id_md_cor_expedicao_solicitada'));

        $objInfraMetaBD->adicionarChaveEstrangeira('fk1_md_cor_expedicao_solicitad', 'md_cor_expedicao_solicitad', array('id_documento_principal'), 'documento', array('id_documento'));
        $objInfraMetaBD->adicionarChaveEstrangeira('fk2_md_cor_expedicao_solicitad', 'md_cor_expedicao_solicitad', array('id_contato_destinatario'), 'contato', array('id_contato'));
        $objInfraMetaBD->adicionarChaveEstrangeira('fk3_md_cor_expedicao_solicitad', 'md_cor_expedicao_solicitad', array('id_md_cor_servico_postal'), 'md_cor_servico_postal', array('id_md_cor_servico_postal'));
        $objInfraMetaBD->adicionarChaveEstrangeira('fk4_md_cor_expedicao_solicitad', 'md_cor_expedicao_solicitad', array('id_unidade'), 'unidade', array('id_unidade'));
        $objInfraMetaBD->adicionarChaveEstrangeira('fk5_md_cor_expedicao_solicitad', 'md_cor_expedicao_solicitad', array('id_usuario_solicitante'), 'usuario', array('id_usuario'));
        $objInfraMetaBD->adicionarChaveEstrangeira('fk6_md_cor_expedicao_solicitad', 'md_cor_expedicao_solicitad', array('id_usuario_exp_autorizador'), 'usuario', array('id_usuario'));
        $objInfraMetaBD->adicionarChaveEstrangeira('fk7_md_cor_expedicao_solicitad', 'md_cor_expedicao_solicitad', array('id_md_cor_plp'), 'md_cor_plp', array('id_md_cor_plp'));
        $objInfraMetaBD->adicionarChaveEstrangeira('fk8_md_cor_expedicao_solicitad', 'md_cor_expedicao_solicitad', array('id_md_cor_objeto'), 'md_cor_objeto', array('id_md_cor_objeto'));

        $this->logar('CRIANDO A SEQUENCE seq_md_cor_expedicao_solicitad');
        BancoSEI::getInstance()->criarSequencialNativa('seq_md_cor_expedicao_solicitad', 1);

        $this->logar('CRIANDO A TABELA md_cor_expedicao_formato');

        BancoSEI::getInstance()->executarSql('CREATE TABLE md_cor_expedicao_formato ( 
						id_md_cor_expedicao_formato ' . $objInfraMetaBD->tipoNumero() . ' NOT NULL ,
						id_md_cor_expedicao_solicitada ' . $objInfraMetaBD->tipoNumero() . ' NOT NULL ,  						
						id_protocolo ' . $objInfraMetaBD->tipoNumeroGrande() . ' NOT NULL ,
						sin_forma_expedicao ' . $objInfraMetaBD->tipoTextoFixo(1) . ' NOT NULL ,
						sin_impressao ' . $objInfraMetaBD->tipoTextoFixo(1) . ' NOT NULL ,
						justificativa ' . $objInfraMetaBD->tipoTextoVariavel(500) . ' NULL
			)');

        $objInfraMetaBD->adicionarChavePrimaria('md_cor_expedicao_formato', 'pk_md_cor_expedicao_formato', array('id_md_cor_expedicao_formato'));
        $objInfraMetaBD->adicionarChaveEstrangeira('fk1_md_cor_expedicao_formato', 'md_cor_expedicao_formato', array('id_protocolo'), 'protocolo', array('id_protocolo'));
        $objInfraMetaBD->adicionarChaveEstrangeira('fk2_md_cor_expedicao_formato', 'md_cor_expedicao_formato', array('id_md_cor_expedicao_solicitada'), 'md_cor_expedicao_solicitad', array('id_md_cor_expedicao_solicitada'));

        $this->logar('CRIANDO A SEQUENCE seq_md_cor_expedicao_formato');
        BancoSEI::getInstance()->criarSequencialNativa('seq_md_cor_expedicao_formato', 1);


        $this->logar('CRIANDO A TABELA md_cor_expedicao_andamento');

        BancoSEI::getInstance()->executarSql('CREATE TABLE md_cor_expedicao_andamento (
					id_md_cor_expedicao_andamento ' . $objInfraMetaBD->tipoNumero() . ' NOT NULL ,
					data_hora ' . $objInfraMetaBD->tipoDataHora() . ' NOT NULL ,
					data_ultima_atualizacao ' . $objInfraMetaBD->tipoDataHora() . ' NOT NULL ,
					descricao ' . $objInfraMetaBD->tipoTextoVariavel(500) . ' NOT NULL ,
					id_md_cor_expedicao_solicitada ' . $objInfraMetaBD->tipoNumero() . ' NOT NULL ,
					detalhe ' . $objInfraMetaBD->tipoTextoVariavel(500) . ' NULL ,
					status ' . $objInfraMetaBD->tipoNumero(3) . ' NOT NULL ,
					tipo   ' . $objInfraMetaBD->tipoTextoVariavel(4) . 'NOT NULL,
					local ' . $objInfraMetaBD->tipoTextoVariavel(250) . ' NOT NULL ,
					codigo_cep ' . $objInfraMetaBD->tipoTextoVariavel(45) . ' NOT NULL ,
					cidade ' . $objInfraMetaBD->tipoTextoVariavel(250) . ' NOT NULL ,
					uf ' . $objInfraMetaBD->tipoTextoVariavel(250) . ' NOT NULL ,
					versao_sro_xml ' . $objInfraMetaBD->tipoTextoVariavel(150) . ' NOT NULL ,
					sigla_objeto ' . $objInfraMetaBD->tipoTextoVariavel(250) . ' NOT NULL ,
					nome_objeto ' . $objInfraMetaBD->tipoTextoVariavel(250) . ' NOT NULL ,
					categoria_objeto ' . $objInfraMetaBD->tipoTextoVariavel(250) . ' NOT NULL
			)');

        $objInfraMetaBD->adicionarChavePrimaria('md_cor_expedicao_andamento', 'pk_md_cor_expedicao_andamento', array('id_md_cor_expedicao_andamento'));
        $objInfraMetaBD->adicionarChaveEstrangeira('fk1_md_cor_expedicao_andamento', 'md_cor_expedicao_andamento', array('id_md_cor_expedicao_solicitada'), 'md_cor_expedicao_solicitad', array('id_md_cor_expedicao_solicitada'));

        $this->logar('CRIANDO A SEQUENCE seq_md_cor_expedicao_andamento');

        BancoSEI::getInstance()->criarSequencialNativa('seq_md_cor_expedicao_andamento', 1);


        $this->logar('CRIANDO A TABELA md_cor_lista_status');
        BancoSEI::getInstance()->executarSql('CREATE TABLE md_cor_lista_status (
                    id_md_cor_lista_status ' . $objInfraMetaBD->tipoNumero() . ' NOT NULL ,
					status ' . $objInfraMetaBD->tipoNumero(3) . ' NOT NULL ,
					tipo   ' . $objInfraMetaBD->tipoTextoVariavel(4) . 'NOT NULL,
					nome_imagem ' . $objInfraMetaBD->tipoTextoVariavel(100) . 'NULL,
					descricao  ' . $objInfraMetaBD->tipoTextoVariavel(250) . ' NULL,
					sin_ativo  ' . $objInfraMetaBD->tipoTextoFixo(1) . ' NULL
			)');

        $objInfraMetaBD->adicionarChavePrimaria('md_cor_lista_status', 'pk_md_cor_lista_status', array('id_md_cor_lista_status'));

        $this->logar('CRIANDO A SEQUENCE seq_md_cor_lista_status');

        BancoSEI::getInstance()->criarSequencialNativa('seq_md_cor_lista_status', 1);

        $this->logar('POPULAR A TABELA DE LISTA DE STATUS');
        $objMdCorListaStatusRN = new MdCorListaStatusRN();
        $arrDados = $objMdCorListaStatusRN->getArrRelStatusImagem();

        $numMdCorListaStatus = 1;
        foreach ($arrDados as $status => $dados) {
            foreach ($dados as $tipo => $dado) {
                foreach ($dado as $desc => $nomeImagem) {
                    BancoSEI::getInstance()->executarSql("INSERT INTO md_cor_lista_status (id_md_cor_lista_status, status, tipo, nome_imagem, descricao, sin_ativo) VALUES('".$numMdCorListaStatus."', '".$status."', '".$tipo."', '".$nomeImagem."', '".$desc."', 'S')");
                    $numMdCorListaStatus++;
                }
            }
        }

        $this->logar('CRIANDO A TABELA md_cor_contato');
        BancoSEI::getInstance()->executarSql('CREATE TABLE md_cor_contato ( 
						id_md_cor_contato ' . $objInfraMetaBD->tipoNumero() . ' NOT NULL , 
						id_md_cor_expedicao_solicitada ' . $objInfraMetaBD->tipoNumero() . ' NOT NULL , 
						id_contato ' . $objInfraMetaBD->tipoNumero() . ' NOT NULL , 
						nome ' . $objInfraMetaBD->tipoTextoVariavel(250) . ' NOT NULL , 
						endereco ' . $objInfraMetaBD->tipoTextoVariavel(130) . '  , 
						bairro ' . $objInfraMetaBD->tipoTextoVariavel(70) . '  ,
						cep ' . $objInfraMetaBD->tipoTextoVariavel(15) . '  ,
						sin_ativo ' . $objInfraMetaBD->tipoTextoFixo(1) . ' NOT NULL , 
						complemento ' . $objInfraMetaBD->tipoTextoVariavel(130) . '  ,
						sta_natureza ' . $objInfraMetaBD->tipoTextoFixo(1) . ' NOT NULL , 
						sin_endereco_associado ' . $objInfraMetaBD->tipoTextoFixo(1) . ' NOT NULL ,
						nome_cidade ' . $objInfraMetaBD->tipoTextoVariavel(50) . ' NOT NULL ,
						sigla_uf ' . $objInfraMetaBD->tipoTextoVariavel(2) . ' NOT NULL ,  
						id_contato_associado ' . $objInfraMetaBD->tipoNumero() . ' NOT NULL ,
						sta_genero ' . $objInfraMetaBD->tipoTextoFixo(1) . '  ,
						id_tipo_contato ' . $objInfraMetaBD->tipoNumero() . ' NOT NULL ,
						nome_contato_associado ' . $objInfraMetaBD->tipoTextoVariavel(250) . '  ,
						sta_natureza_contato_associado ' . $objInfraMetaBD->tipoTextoFixo(1) . '  , 
						id_tipo_contato_associado ' . $objInfraMetaBD->tipoNumero() . '  ,
						tratamento_expressao ' . $objInfraMetaBD->tipoTextoVariavel(100) . '  ,
						cargo_expressao ' . $objInfraMetaBD->tipoTextoVariavel(100) . '  						
			)');

        $objInfraMetaBD->adicionarChavePrimaria('md_cor_contato', 'pk_md_cor_contato', array('id_md_cor_contato'));
        $objInfraMetaBD->adicionarChaveEstrangeira('fk1_md_cor_contato', 'md_cor_contato', array('id_md_cor_expedicao_solicitada'), 'md_cor_expedicao_solicitad', array('id_md_cor_expedicao_solicitada'));

        $this->logar('CRIANDO A SEQUENCE seq_md_cor_contato');

        BancoSEI::getInstance()->criarSequencialNativa('seq_md_cor_contato', 1);

        $this->logar('GERANDO ANDAMENTO PRÓPRIO DO MÓDULO DOS CORREIOS NO PROCESSO PARA REGISTRAR A SOLICITAÇÃO DE EXPEDIÇÃO');

        $texto1 = "Solicitação de Expedição pelos Correios gerada em @DATA_SOLICITACAO_EXPEDICAO@, do Documento @DOCUMENTO@, por meio de @SERVICO_POSTAGEM_CORREIOS@ @OPCAO_AVISO_RECEBIMENTO@";
        $texto2 = "O Documento @DOCUMENTO@ foi expedido pelos Correios em @DATA_EXPEDICAO_CORREIOS@, sob o Código de Rastreamento @CODIGO_RASTREAMENTO_OBJETO_CORREIOS@";

        $arrMaxIdTarefa = BancoSEI::getInstance()->consultarSql('SELECT MAX(id_tarefa) as max FROM tarefa');
        $numIdTarefaMax = $arrMaxIdTarefa[0]['max'];

        if ($numIdTarefaMax < 1000) {
            $numIdTarefaMax = 1000;
        } else {
            $numIdTarefaMax++;
        }

        $tarefaDTO1 = new TarefaDTO();
        $tarefaDTO1->setNumIdTarefa($numIdTarefaMax);
        $tarefaDTO1->setStrIdTarefaModulo('MD_COR_SOLICITACAO_EXPEDICAO');
        $tarefaDTO1->setStrNome($texto1);
        $tarefaDTO1->setStrSinHistoricoResumido('N');
        $tarefaDTO1->setStrSinHistoricoCompleto('S');
        $tarefaDTO1->setStrSinFecharAndamentosAbertos('S');
        $tarefaDTO1->setStrSinLancarAndamentoFechado('N');
        $tarefaDTO1->setStrSinPermiteProcessoFechado('N');

        if (BancoSEI::getInstance() instanceof InfraMySql) {
            BancoSEI::getInstance()->executarSql(" DELETE FROM seq_tarefa");
            BancoSEI::getInstance()->executarSql(" INSERT INTO seq_tarefa (id) VALUES (" . $numIdTarefaMax . ") ");
        } elseif (BancoSEI::getInstance() instanceof InfraOracle) {
            BancoSEI::getInstance()->executarSql("drop sequence seq_tarefa");
            BancoSEI::getInstance()->executarSql("CREATE SEQUENCE seq_tarefa START WITH " . $numIdTarefaMax . " INCREMENT BY 1 NOCACHE NOCYCLE");
        } else if (BancoSEI::getInstance() instanceof InfraSqlServer) {
            BancoSEI::getInstance()->executarSql("TRUNCATE TABLE seq_tarefa; SET IDENTITY_INSERT seq_tarefa ON; INSERT INTO seq_tarefa (id) VALUES (" . $numIdTarefaMax . "); SET IDENTITY_INSERT seq_tarefa OFF;");
        }

        $tarefaRN = new TarefaRN();
        $tarefaRN->cadastrar($tarefaDTO1);

        $numIdTarefaMax++;
        $tarefaDTO2 = new TarefaDTO();
        $tarefaDTO2->setNumIdTarefa($numIdTarefaMax);
        $tarefaDTO2->setStrIdTarefaModulo('MD_COR_EXPEDIR_PLP');
        $tarefaDTO2->setStrNome($texto2);
        $tarefaDTO2->setStrSinHistoricoResumido('N');
        $tarefaDTO2->setStrSinHistoricoCompleto('S');
        $tarefaDTO2->setStrSinFecharAndamentosAbertos('S');
        $tarefaDTO2->setStrSinLancarAndamentoFechado('N');
        $tarefaDTO2->setStrSinPermiteProcessoFechado('N');

        $tarefaRN = new TarefaRN();
        $tarefaRN->cadastrar($tarefaDTO2);

        $arrMaxIdTarefaSemModulo = BancoSEI::getInstance()->consultarSql('SELECT MAX(id_tarefa) as max FROM tarefa WHERE id_tarefa < 1000');
        $idMaxIdTarefa = $arrMaxIdTarefaSemModulo[0]['max'];
        if (BancoSEI::getInstance() instanceof InfraMySql) {
            BancoSEI::getInstance()->executarSql(" DELETE FROM seq_tarefa;");
            BancoSEI::getInstance()->executarSql(" INSERT INTO seq_tarefa (id) VALUES (" . ++$idMaxIdTarefa . "); ");
        } else if (BancoSEI::getInstance() instanceof InfraOracle) {
            BancoSEI::getInstance()->executarSql("drop sequence seq_tarefa");
            BancoSEI::getInstance()->executarSql("CREATE SEQUENCE seq_tarefa START WITH " . ++$idMaxIdTarefa . " INCREMENT BY 1 NOCACHE NOCYCLE");
        } else if (BancoSEI::getInstance() instanceof InfraSqlServer) {
            BancoSEI::getInstance()->executarSql("TRUNCATE TABLE seq_tarefa; SET IDENTITY_INSERT seq_tarefa ON; INSERT INTO seq_tarefa (id) VALUES (" . ++$idMaxIdTarefa . "); SET IDENTITY_INSERT seq_tarefa OFF;");
        }

        $this->logar('CRIANDO A TABELA md_cor_adm_parametro_ar');
        BancoSEI::getInstance()->executarSql('CREATE TABLE md_cor_adm_parametro_ar ( 
					id_md_cor_parametro_ar ' . $objInfraMetaBD->tipoNumero() . ' NOT NULL , 
					nu_dias_retorno_ar ' . $objInfraMetaBD->tipoTextoVariavel(6) . ' NULL, 
					id_serie ' . $objInfraMetaBD->tipoNumero() . '  NULL,
					nome_arvore ' . $objInfraMetaBD->tipoTextoVariavel(60) . ' NOT NULL ,
					id_tipo_conferencia ' . $objInfraMetaBD->tipoNumero() . ' NULL  
					)'
        );

        $objInfraMetaBD->adicionarChavePrimaria('md_cor_adm_parametro_ar', 'pk_md_cor_adm_parametro_ar', array('id_md_cor_parametro_ar'));
        $objInfraMetaBD->adicionarChaveEstrangeira('fk1_md_cor_adm_parametro_ar', 'md_cor_adm_parametro_ar', array('id_serie'), 'serie', array('id_serie'));
        $objInfraMetaBD->adicionarChaveEstrangeira('fk2_md_cor_adm_parametro_ar', 'md_cor_adm_parametro_ar', array('id_tipo_conferencia'), 'tipo_conferencia', array('id_tipo_conferencia'));
        $this->logar('CRIANDO A SEQUENCE seq_md_cor_adm_parametro_ar');
        BancoSEI::getInstance()->criarSequencialNativa('seq_md_cor_adm_parametro_ar', 1);

        $this->logar('POPULANDO TABELA md_cor_adm_parametro_ar COM O NOME DA ÁRVORE PADRÃO');
        $mdCorParametroArDTO = new MdCorParametroArDTO();
        $mdCorParametroArDTO->setStrNomeArvore('referente ao @tipo_doc_principal_expedido@ @numero@');

        $mdCorParametroArRN = new MdCorParametroArRN();
        $mdCorParametroArRN->cadastrar($mdCorParametroArDTO);

        $this->logar('CRIANDO A TABELA md_cor_adm_par_ar_infrigen');
        BancoSEI::getInstance()->executarSql('CREATE TABLE md_cor_adm_par_ar_infrigen ( 
					id_md_cor_param_ar_infrigencia ' . $objInfraMetaBD->tipoNumero() . ' NOT NULL , 
					id_md_cor_parametro_ar ' . $objInfraMetaBD->tipoNumero() . ' NOT NULL , 
					motivo_infrigencia ' . $objInfraMetaBD->tipoTextoVariavel(60) . 'NOT NULL , 
					sin_ativo ' . $objInfraMetaBD->tipoTextoVariavel(1) . 'NOT NULL , 
					sin_infrigencia ' . $objInfraMetaBD->tipoTextoVariavel(1) . 'NOT NULL  
					)'
        );

        $objInfraMetaBD->adicionarChavePrimaria('md_cor_adm_par_ar_infrigen', 'pk_md_cor_adm_par_ar_infrigen', array('id_md_cor_param_ar_infrigencia'));
        $objInfraMetaBD->adicionarChaveEstrangeira('fk1_md_cor_adm_par_ar_infrigen', 'md_cor_adm_par_ar_infrigen', array('id_md_cor_parametro_ar'), 'md_cor_adm_parametro_ar', array('id_md_cor_parametro_ar'));

        $this->logar('CRIANDO A SEQUENCE seq_md_cor_adm_par_ar_infrigen');
        BancoSEI::getInstance()->criarSequencialNativa('seq_md_cor_adm_par_ar_infrigen', 1);


        $this->logar('POPULANDO TABELA md_cor_adm_par_ar_infrigen');

        $arrMotivoDevolucao = [
            ['id_md_cor_param_ar_infrigencia' => '1', 'motivo_infrigencia' => 'Não entregue e AR não retornado pelos Correios', 'sin_ativo' => 'S', 'sin_infrigencia' => 'S'],
            ['id_md_cor_param_ar_infrigencia' => '1', 'motivo_infrigencia' => 'Outros', 'sin_ativo' => 'S', 'sin_infrigencia' => 'N'],
            ['id_md_cor_param_ar_infrigencia' => '1', 'motivo_infrigencia' => 'Falecido', 'sin_ativo' => 'S', 'sin_infrigencia' => 'N'],
            ['id_md_cor_param_ar_infrigencia' => '1', 'motivo_infrigencia' => 'Não Procurado', 'sin_ativo' => 'S', 'sin_infrigencia' => 'N'],
            ['id_md_cor_param_ar_infrigencia' => '1', 'motivo_infrigencia' => 'Recusado', 'sin_ativo' => 'S', 'sin_infrigencia' => 'N'],
            ['id_md_cor_param_ar_infrigencia' => '1', 'motivo_infrigencia' => 'Desconhecido', 'sin_ativo' => 'S', 'sin_infrigencia' => 'N'],
            ['id_md_cor_param_ar_infrigencia' => '1', 'motivo_infrigencia' => 'Não existe o Número', 'sin_ativo' => 'S', 'sin_infrigencia' => 'N'],
            ['id_md_cor_param_ar_infrigencia' => '1', 'motivo_infrigencia' => 'Endereço Insuficiente', 'sin_ativo' => 'S', 'sin_infrigencia' => 'N'],
            ['id_md_cor_param_ar_infrigencia' => '1', 'motivo_infrigencia' => 'Mudou-se', 'sin_ativo' => 'S', 'sin_infrigencia' => 'N'],
            ['id_md_cor_param_ar_infrigencia' => '1', 'motivo_infrigencia' => 'Ausente', 'sin_ativo' => 'S', 'sin_infrigencia' => 'N'],
        ];

        $objMdCorParamArInfrigenRN = new MdCorParamArInfrigenRN();

        foreach ($arrMotivoDevolucao as $chave => $motivoDevolucao) {
            $objMdCorParamArInfrigenDTO = new MdCorParamArInfrigenDTO();
            $objMdCorParamArInfrigenDTO->setNumIdMdCorParametroAr($motivoDevolucao['id_md_cor_param_ar_infrigencia']);
            $objMdCorParamArInfrigenDTO->setStrSinAtivo($motivoDevolucao['sin_ativo']);
            $objMdCorParamArInfrigenDTO->setStrSinInfrigencia($motivoDevolucao['sin_infrigencia']);
            $objMdCorParamArInfrigenDTO->setStrMotivoInfrigencia($motivoDevolucao['motivo_infrigencia']);
            $objMdCorParamArInfrigenRN->cadastrar($objMdCorParamArInfrigenDTO);
        }

        $this->logar('CRIANDO A TABELA md_cor_retorno_ar');
        BancoSEI::getInstance()->executarSql('CREATE TABLE md_cor_retorno_ar ( 
					id_md_cor_retorno_ar ' . $objInfraMetaBD->tipoNumero() . ' NOT NULL , 
					data_cadastro ' . $objInfraMetaBD->tipoDataHora() . ' NOT NULL , 
					id_usuario ' . $objInfraMetaBD->tipoNumero() . ' NOT NULL , 
					sin_autenticado ' . $objInfraMetaBD->tipoTextoVariavel(1) . 'NOT NULL , 
					id_unidade ' . $objInfraMetaBD->tipoNumero() . ' NOT NULL , 
					nome_arquivo_zip ' . $objInfraMetaBD->tipoTextoVariavel(100) . 'NOT NULL  
					)'
        );

        $objInfraMetaBD->adicionarChavePrimaria('md_cor_retorno_ar', 'pk_md_cor_retorno_ar', array('id_md_cor_retorno_ar'));
        $objInfraMetaBD->adicionarChaveEstrangeira('fk1_md_cor_retorno_ar', 'md_cor_retorno_ar', array('id_usuario'), 'usuario', array('id_usuario'));
        $objInfraMetaBD->adicionarChaveEstrangeira('fk2_md_cor_retorno_ar', 'md_cor_retorno_ar', array('id_unidade'), 'unidade', array('id_unidade'));

        $this->logar('CRIANDO A SEQUENCE seq_md_cor_retorno_ar');
        BancoSEI::getInstance()->criarSequencialNativa('seq_md_cor_retorno_ar', 1);

        $this->logar('CRIANDO A TABELA md_cor_retorno_ar_doc');
        BancoSEI::getInstance()->executarSql('CREATE TABLE md_cor_retorno_ar_doc ( 
					id_md_cor_retorno_ar_doc ' . $objInfraMetaBD->tipoNumero() . ' NOT NULL , 
					id_md_cor_retorno_ar ' . $objInfraMetaBD->tipoNumero() . ' NOT NULL , 
					id_documento_principal ' . $objInfraMetaBD->tipoNumeroGrande() . '  , 
					data_ar ' . $objInfraMetaBD->tipoDataHora() . '  , 
					data_retorno ' . $objInfraMetaBD->tipoDataHora() . '  , 
					sin_status ' . $objInfraMetaBD->tipoTextoVariavel(1) . 'NOT NULL , 
					nome_arquivo_pdf ' . $objInfraMetaBD->tipoTextoVariavel(50) . 'NOT NULL , 
					id_documento_ar ' . $objInfraMetaBD->tipoNumeroGrande() . '  , 
					id_md_cor_param_ar_infrigencia ' . $objInfraMetaBD->tipoNumero() . '  , 
					sin_retorno ' . $objInfraMetaBD->tipoTextoVariavel(1) . ' NOT NULL,  
					codigo_rastreamento ' . $objInfraMetaBD->tipoTextoVariavel(45) . '  
					)'
        );

        $objInfraMetaBD->adicionarChavePrimaria('md_cor_retorno_ar_doc', 'pk_md_cor_retorno_ar_doc', array('id_md_cor_retorno_ar_doc'));
        $objInfraMetaBD->adicionarChaveEstrangeira('fk1_md_cor_retorno_ar_doc', 'md_cor_retorno_ar_doc', array('id_md_cor_retorno_ar'), 'md_cor_retorno_ar', array('id_md_cor_retorno_ar'));
        $objInfraMetaBD->adicionarChaveEstrangeira('fk2_md_cor_retorno_ar_doc', 'md_cor_retorno_ar_doc', array('id_documento_principal'), 'documento', array('id_documento'));
        $objInfraMetaBD->adicionarChaveEstrangeira('fk3_md_cor_retorno_ar_doc', 'md_cor_retorno_ar_doc', array('id_documento_ar'), 'documento', array('id_documento'));
        $objInfraMetaBD->adicionarChaveEstrangeira('fk4_md_cor_retorno_ar_doc', 'md_cor_retorno_ar_doc', array('id_md_cor_param_ar_infrigencia'), 'md_cor_adm_par_ar_infrigen', array('id_md_cor_param_ar_infrigencia'));

        $this->logar('CRIANDO A SEQUENCE seq_md_cor_retorno_ar_doc');
        BancoSEI::getInstance()->criarSequencialNativa('seq_md_cor_retorno_ar_doc', 1);


        $this->logar('POPULAR "cronjob" PARA SCRIPT AGENDADO RESPONSÁVEL POR ATUALIZAR O ANDAMENTO DOS OBJS NO CORREIOS');
        $infraAgendamentoDTO = new InfraAgendamentoTarefaDTO();
        $infraAgendamentoDTO->retTodos();
        $infraAgendamentoDTO->setStrDescricao('Script responsável por atualizar o andamento dos objetos no SEI, de acordo com os correios.');
        $infraAgendamentoDTO->setStrComando('MdCorAgendamentoAutomaticoRN::atualizarAndamentoObjetos');
        $infraAgendamentoDTO->setStrSinAtivo('S');
        $infraAgendamentoDTO->setStrStaPeriodicidadeExecucao(InfraAgendamentoTarefaRN::$PERIODICIDADE_EXECUCAO_HORA);
        $infraAgendamentoDTO->setStrPeriodicidadeComplemento(23);
        $infraAgendamentoDTO->setStrParametro(null);
        $infraAgendamentoDTO->setDthUltimaExecucao(null);
        $infraAgendamentoDTO->setDthUltimaConclusao(null);
        $infraAgendamentoDTO->setStrSinSucesso('N');
        $infraAgendamentoDTO->setStrEmailErro($objInfraParametro->getValor('SEI_EMAIL_ADMINISTRADOR'));

        $infraAgendamentoRN = new InfraAgendamentoTarefaRN();
        $infraAgendamentoRN->cadastrar($infraAgendamentoDTO);


        $this->logar('ALTERANDO A TABELA md_cor_adm_parametro_ar');

        $objInfraMetaBD->adicionarColuna('md_cor_adm_parametro_ar', 'nu_dias_cobranca_ar', $objInfraMetaBD->tipoTextoVariavel(6), '');
        $objInfraMetaBD->adicionarColuna('md_cor_adm_parametro_ar', 'id_serie_devolvido', $objInfraMetaBD->tipoNumero(), '');
        $objInfraMetaBD->adicionarColuna('md_cor_adm_parametro_ar', 'nome_arvore_devolvido', $objInfraMetaBD->tipoTextoVariavel(60), '');
        $objInfraMetaBD->adicionarColuna('md_cor_adm_parametro_ar', 'id_tipo_conferencia_devolvido', $objInfraMetaBD->tipoNumero(), '');
        $objInfraMetaBD->adicionarColuna('md_cor_adm_parametro_ar', 'id_serie_cobranca', $objInfraMetaBD->tipoNumero(), '');
        $objInfraMetaBD->adicionarColuna('md_cor_adm_parametro_ar', 'id_procedimento_cobranca', $objInfraMetaBD->tipoNumeroGrande(), '');
        $objInfraMetaBD->adicionarColuna('md_cor_adm_parametro_ar', 'id_unidade_cobranca', $objInfraMetaBD->tipoNumero(), '');
        $objInfraMetaBD->adicionarColuna('md_cor_adm_parametro_ar', 'modelo_cobranca', $objInfraMetaBD->tipoTextoGrande(), '');

        $objInfraMetaBD->adicionarChaveEstrangeira('fk3_md_cor_adm_parametro_ar', 'md_cor_adm_parametro_ar', array('id_serie_devolvido'), 'serie', array('id_serie'));
        $objInfraMetaBD->adicionarChaveEstrangeira('fk4_md_cor_adm_parametro_ar', 'md_cor_adm_parametro_ar', array('id_tipo_conferencia_devolvido'), 'tipo_conferencia', array('id_tipo_conferencia'));
        $objInfraMetaBD->adicionarChaveEstrangeira('fk5_md_cor_adm_parametro_ar', 'md_cor_adm_parametro_ar', array('id_serie_cobranca'), 'serie', array('id_serie'));
        $objInfraMetaBD->adicionarChaveEstrangeira('fk6_md_cor_adm_parametro_ar', 'md_cor_adm_parametro_ar', array('id_procedimento_cobranca'), 'procedimento', array('id_procedimento'));
        $objInfraMetaBD->adicionarChaveEstrangeira('fk7_md_cor_adm_parametro_ar', 'md_cor_adm_parametro_ar', array('id_unidade_cobranca'), 'unidade', array('id_unidade'));

        $this->logar('ALTERANDO A TABELA md_cor_expedicao_solicitad');
        $objInfraMetaBD->adicionarColuna('md_cor_expedicao_solicitad', 'status_cobranca', $objInfraMetaBD->tipoTextoVariavel(1), 'NOT NULL');

        $this->logar('CRIANDO A TABELA md_cor_ar_cobranca');
        BancoSEI::getInstance()->executarSql('CREATE TABLE md_cor_ar_cobranca ( 
					id_md_cor_ar_cobranca ' . $objInfraMetaBD->tipoNumero() . ' NOT NULL , 
					dt_md_cor_ar_cobranca ' . $objInfraMetaBD->tipoDataHora() . ' NOT NULL , 
					id_documento_cobranca ' . $objInfraMetaBD->tipoNumeroGrande() . ' NOT NULL , 
					id_md_cor_expedicao_solicitada ' . $objInfraMetaBD->tipoNumero() . ' NOT NULL 
					)'
        );

        $objInfraMetaBD->adicionarChavePrimaria('md_cor_ar_cobranca', 'pk_md_cor_ar_cobranca', array('id_md_cor_ar_cobranca'));
        $objInfraMetaBD->adicionarChaveEstrangeira('fk1_md_cor_ar_cobranca', 'md_cor_ar_cobranca', array('id_documento_cobranca'), 'documento', array('id_documento'));
        $objInfraMetaBD->adicionarChaveEstrangeira('fk2_md_cor_ar_cobranca', 'md_cor_ar_cobranca', array('id_md_cor_expedicao_solicitada'), 'md_cor_expedicao_solicitad', array('id_md_cor_expedicao_solicitada'));

        $this->logar('CRIANDO A SEQUENCE seq_md_cor_ar_cobranca');
        BancoSEI::getInstance()->criarSequencialNativa('seq_md_cor_ar_cobranca', 1);


        $this->logar('CRIANDO A TABELA md_cor_parametro_rastreio');
        BancoSEI::getInstance()->executarSql('CREATE TABLE md_cor_parametro_rastreio ( 
					id_md_cor_parametro_rastreio ' . $objInfraMetaBD->tipoNumero() . ' NOT NULL , 
					usuario ' . $objInfraMetaBD->tipoTextoVariavel(100) . ' NOT NULL ,
					senha ' . $objInfraMetaBD->tipoTextoVariavel(100) . ' NOT NULL ,
					endereco_wsdl ' . $objInfraMetaBD->tipoTextoVariavel(500) . ' NOT NULL 
					)'
        );

        $objInfraMetaBD->adicionarChavePrimaria('md_cor_parametro_rastreio', 'pk_md_cor_parametro_rastreio', array('id_md_cor_parametro_rastreio'));

        $this->logar('CRIANDO A SEQUENCE seq_md_cor_parametro_rastreio');
        BancoSEI::getInstance()->criarSequencialNativa('seq_md_cor_parametro_rastreio', 1);


        $this->logar('GERANDO ANDAMENTO PRÓPRIO DO MÓDULO DOS CORREIOS NO PROCESSO PARA REGISTRAR ANDAMENTO DO RETORNO DO AR');

        $texto1 = "Aviso de Recebimento dos Correios referente ao Documento @DOCUMENTO@, Código de Rastreamento @CODIGO_RASTREAMENTO_OBJETO_CORREIOS@, foi retornado em @DATA_RETORNO_AR_CORREIOS@, com a situação @SITUACAO_RETORNO_AR@ @MOTIVO_OBJETO_DEVOLVIDO@";

        $arrMaxIdTarefa = BancoSEI::getInstance()->consultarSql('SELECT MAX(id_tarefa) as max FROM tarefa');
        $numIdTarefaMax = $arrMaxIdTarefa[0]['max'];

        if ($numIdTarefaMax < 1000) {
            $numIdTarefaMax = 1000;
        } else {
            $numIdTarefaMax++;
        }

        $tarefaDTO1 = new TarefaDTO();
        $tarefaDTO1->setNumIdTarefa($numIdTarefaMax);
        $tarefaDTO1->setStrIdTarefaModulo('MD_COR_RETORNO_AR');
        $tarefaDTO1->setStrNome($texto1);
        $tarefaDTO1->setStrSinHistoricoResumido('N');
        $tarefaDTO1->setStrSinHistoricoCompleto('S');
        $tarefaDTO1->setStrSinFecharAndamentosAbertos('S');
        $tarefaDTO1->setStrSinLancarAndamentoFechado('N');
        $tarefaDTO1->setStrSinPermiteProcessoFechado('S');

        if (BancoSEI::getInstance() instanceof InfraMySql) {
            BancoSEI::getInstance()->executarSql(" DELETE FROM seq_tarefa");
            BancoSEI::getInstance()->executarSql(" INSERT INTO seq_tarefa (id) VALUES (" . $numIdTarefaMax . ") ");
        }
        if (BancoSEI::getInstance() instanceof InfraOracle) {
            BancoSEI::getInstance()->executarSql("drop sequence seq_tarefa");
            BancoSEI::getInstance()->executarSql("CREATE SEQUENCE seq_tarefa START WITH " . $numIdTarefaMax . " INCREMENT BY 1 NOCACHE NOCYCLE");
        } else if (BancoSEI::getInstance() instanceof InfraSqlServer) {
            BancoSEI::getInstance()->executarSql("TRUNCATE TABLE seq_tarefa; SET IDENTITY_INSERT seq_tarefa ON; INSERT INTO seq_tarefa (id) VALUES (" . $numIdTarefaMax . "); SET IDENTITY_INSERT seq_tarefa OFF;");
        }

        $tarefaRN = new TarefaRN();
        $tarefaRN->cadastrar($tarefaDTO1);

        $this->logar('CORRIGINDO O VALOR DA TABELA SEQ_TAREFA QUANDO TIVER VALORES MAIOR QUE 1000');
        $arrMaxIdTarefaSemModulo = BancoSEI::getInstance()->consultarSql('SELECT MAX(id_tarefa) as max FROM tarefa WHERE id_tarefa < 1000');
        $idMaxIdTarefa = $arrMaxIdTarefaSemModulo[0]['max'];
        if (BancoSEI::getInstance() instanceof InfraMySql) {
            BancoSEI::getInstance()->executarSql(" DELETE FROM seq_tarefa");
            BancoSEI::getInstance()->executarSql(" INSERT INTO seq_tarefa (id) VALUES (" . ++$idMaxIdTarefa . ") ");
        } else if (BancoSEI::getInstance() instanceof InfraOracle) {
            BancoSEI::getInstance()->executarSql("drop sequence seq_tarefa");
            BancoSEI::getInstance()->executarSql("CREATE SEQUENCE seq_tarefa START WITH " . ++$idMaxIdTarefa . " INCREMENT BY 1 NOCACHE NOCYCLE");
        } else if (BancoSEI::getInstance() instanceof InfraSqlServer) {
            BancoSEI::getInstance()->executarSql("TRUNCATE TABLE seq_tarefa; SET IDENTITY_INSERT seq_tarefa ON; INSERT INTO seq_tarefa (id) VALUES (" . ++$idMaxIdTarefa . "); SET IDENTITY_INSERT seq_tarefa OFF;");
        }


        $this->logar('CRIANDO A TABELA md_cor_status_process');
        BancoSEI::getInstance()->executarSql('CREATE TABLE md_cor_status_process ( 
					id_md_cor_status_process ' . $objInfraMetaBD->tipoNumero() . ' NOT NULL, 
					descricao ' . $objInfraMetaBD->tipoTextoVariavel(100) . ' NOT NULL
					)'
        );

        $objInfraMetaBD->adicionarChavePrimaria('md_cor_status_process', 'pk_md_cor_status_process', array('id_md_cor_status_process'));

        $this->logar('CRIANDO A SEQUENCE seq_md_cor_status_process');
        BancoSEI::getInstance()->criarSequencialNativa('seq_md_cor_status_process', 1);

        $this->logar('POPULANDO TABELA DE DOMINIO DE STATUS DE PROCESSAMENTO');
        $arrStatusProcessamento = [
            ['id_md_cor_status_process' => '1', 'descricao' => 'Identificado Automaticamente'],
            ['id_md_cor_status_process' => '2', 'descricao' => 'Identificado Manualmente'],
            ['id_md_cor_status_process' => '3', 'descricao' => 'Retorno já processado anteriormente'],
            ['id_md_cor_status_process' => '4', 'descricao' => 'Não Processado'],
        ];

        $mdCorStatusProcessRN = new MdCorStatusProcessRN();

        foreach ($arrStatusProcessamento as $chave => $tipo) {
            $mdCorStatusProcessDTO = new MdCorStatusProcessDTO();
            $mdCorStatusProcessDTO->setNumIdMdCorStatusProcess($chave + 1);
            $mdCorStatusProcessDTO->setStrDescricao($tipo['descricao']);
            $mdCorStatusProcessRN->cadastrar($mdCorStatusProcessDTO);
        }

        $this->logar('CRIANDO A TABELA md_cor_substatus_process');
        BancoSEI::getInstance()->executarSql('CREATE TABLE md_cor_substatus_process ( 
					id_md_cor_substatus_process ' . $objInfraMetaBD->tipoNumero() . ' NOT NULL, 
					id_md_cor_status_process ' . $objInfraMetaBD->tipoNumero() . ' NOT NULL, 
					descricao ' . $objInfraMetaBD->tipoTextoVariavel(100) . ' NOT NULL
					)'
        );

        $objInfraMetaBD->adicionarChavePrimaria('md_cor_substatus_process', 'pk_md_cor_substatus_process', array('id_md_cor_substatus_process'));

        $this->logar('CRIANDO A SEQUENCE seq_md_cor_substatus_process');
        BancoSEI::getInstance()->criarSequencialNativa('seq_md_cor_substatus_process', 1);

        $objInfraMetaBD->adicionarChaveEstrangeira('fk1_md_cor_substatus_process', 'md_cor_substatus_process', array('id_md_cor_status_process'), 'md_cor_status_process', array('id_md_cor_status_process'));

        $this->logar('POPULANDO TABELA DE DOMINIO DE SUBSTATUS DE PROCESSAMENTO');
        $arrSubStatusProcessamento = [
            ['id_md_cor_substatus_process' => '1', 'id_md_cor_status_process' => '4', 'descricao' => 'Não Identificado'],
            ['id_md_cor_substatus_process' => '2', 'id_md_cor_status_process' => '4', 'descricao' => 'Documento Processado Anteriomente'],
            ['id_md_cor_substatus_process' => '3', 'id_md_cor_status_process' => '4', 'descricao' => 'Processo está Sobrestado'],
            ['id_md_cor_substatus_process' => '4', 'id_md_cor_status_process' => '4', 'descricao' => 'Processo anexado a outro'],
            ['id_md_cor_substatus_process' => '5', 'id_md_cor_status_process' => '4', 'descricao' => 'Processo está bloqueado'],
            ['id_md_cor_substatus_process' => '6', 'id_md_cor_status_process' => '4', 'descricao' => 'Unidade de expedição foi desativada'],

        ];

        $mdCorSubStatusProcessRN = new MdCorSubStatusProcessRN();

        foreach ($arrSubStatusProcessamento as $chave => $tipo) {
            $mdCorSubStatusProcessDTO = new MdCorSubStatusProcessDTO();
            $mdCorSubStatusProcessDTO->setNumIdMdCorSubStatusProcess($chave + 1);
            $mdCorSubStatusProcessDTO->setNumIdMdCorStatusProcess($tipo['id_md_cor_status_process']);
            $mdCorSubStatusProcessDTO->setStrDescricao($tipo['descricao']);
            $mdCorSubStatusProcessRN->cadastrar($mdCorSubStatusProcessDTO);
        }

        $this->logar('ALTERANDO A TABELA md_cor_retorno_ar_doc');
        $objInfraMetaBD->adicionarColuna('md_cor_retorno_ar_doc', 'id_status_process', $objInfraMetaBD->tipoNumero(), 'NOT NULL');
        $objInfraMetaBD->adicionarColuna('md_cor_retorno_ar_doc', 'id_substatus_process', $objInfraMetaBD->tipoNumero(), 'NULL');

        $this->logar('CORRIGINDO REGISTROS DA TABELA md_cor_retorno_ar_doc');

        $arrSinRetorno = [
            'A' => 1,
            'M' => 2,
            'P' => 3,
            'N' => 4
        ];

        $mdCorRetornoArDocRN = new MdCorRetornoArDocRN();
        $mdCorRetornoArDocDTO = new MdCorRetornoArDocDTO();
        $mdCorRetornoArDocDTO->retTodos();
        $arrObjMdCorRetornoArDocDTO = $mdCorRetornoArDocRN->listar($mdCorRetornoArDocDTO);
        foreach ($arrObjMdCorRetornoArDocDTO as $chave => $item) {
            $item->setNumIdStatusProcess($arrSinRetorno[$item->getStrSinRetorno()]);
            if ($item->getStrSinRetorno() == 'N') {
                $item->setNumIdSubStatusProcess(1);
            }
            $mdCorRetornoArDocRN->alterar($item);
        }

        $this->logar('ALTERANDO A TABELA md_cor_retorno_ar_doc');
        $objInfraMetaBD->excluirColuna('md_cor_retorno_ar_doc', 'sin_retorno');

        $this->logar('GERANDO ANDAMENTO PRÓPRIO DO MÓDULO DOS CORREIOS NO PROCESSO PARA REGISTRAR ANDAMENTO DA ALTERAÇÃO DA SOLICITACAO DE EXPEDIÇÃO');

        $texto1 = "Solicitação de Expedição pelos Correios referente ao Documento @DOCUMENTO@, por meio de @SERVICO_POSTAGEM_CORREIOS@, @OPCAO_AVISO_RECEBIMENTO@, alterada pela Unidade Solicitante, em razão de: @JUSTIFICATIVA_ALTERACAO_EXPEDICAO@";

        $arrMaxIdTarefa = BancoSEI::getInstance()->consultarSql('SELECT MAX(id_tarefa) as max FROM tarefa');
        $numIdTarefaMax = $arrMaxIdTarefa[0]['max'];

        if ($numIdTarefaMax < 1000) {
            $numIdTarefaMax = 1000;
        } else {
            $numIdTarefaMax++;
        }

        $tarefaDTO1 = new TarefaDTO();
        $tarefaDTO1->setNumIdTarefa($numIdTarefaMax);
        $tarefaDTO1->setStrIdTarefaModulo('MD_COR_ALTERAR_EXPEDICAO');
        $tarefaDTO1->setStrNome($texto1);
        $tarefaDTO1->setStrSinHistoricoResumido('N');
        $tarefaDTO1->setStrSinHistoricoCompleto('S');
        $tarefaDTO1->setStrSinFecharAndamentosAbertos('S');
        $tarefaDTO1->setStrSinLancarAndamentoFechado('N');
        $tarefaDTO1->setStrSinPermiteProcessoFechado('N');

        if (BancoSEI::getInstance() instanceof InfraMySql) {
            BancoSEI::getInstance()->executarSql(" DELETE FROM seq_tarefa");
            BancoSEI::getInstance()->executarSql(" INSERT INTO seq_tarefa (id) VALUES (" . $numIdTarefaMax . ") ");
        }
        if (BancoSEI::getInstance() instanceof InfraOracle) {
            BancoSEI::getInstance()->executarSql("drop sequence seq_tarefa");
            BancoSEI::getInstance()->executarSql("CREATE SEQUENCE seq_tarefa START WITH " . $numIdTarefaMax . " INCREMENT BY 1 NOCACHE NOCYCLE");
        } else if (BancoSEI::getInstance() instanceof InfraSqlServer) {
            BancoSEI::getInstance()->executarSql("TRUNCATE TABLE seq_tarefa; SET IDENTITY_INSERT seq_tarefa ON; INSERT INTO seq_tarefa (id) VALUES (" . $numIdTarefaMax . "); SET IDENTITY_INSERT seq_tarefa OFF;");
        }

        $tarefaRN = new TarefaRN();
        $tarefaRN->cadastrar($tarefaDTO1);

        $this->logar('CORRIGINDO O VALOR DA TABELA SEQ_TAREFA QUANDO TIVER VALORES MAIOR QUE 1000');
        $arrMaxIdTarefaSemModulo = BancoSEI::getInstance()->consultarSql('SELECT MAX(id_tarefa) as max FROM tarefa WHERE id_tarefa < 1000');
        $idMaxIdTarefa = $arrMaxIdTarefaSemModulo[0]['max'];
        if (BancoSEI::getInstance() instanceof InfraMySql) {
            BancoSEI::getInstance()->executarSql(" DELETE FROM seq_tarefa");
            BancoSEI::getInstance()->executarSql(" INSERT INTO seq_tarefa (id) VALUES (" . ++$idMaxIdTarefa . ") ");
        } else if (BancoSEI::getInstance() instanceof InfraOracle) {
            BancoSEI::getInstance()->executarSql("drop sequence seq_tarefa");
            BancoSEI::getInstance()->executarSql("CREATE SEQUENCE seq_tarefa START WITH " . ++$idMaxIdTarefa . " INCREMENT BY 1 NOCACHE NOCYCLE");
        } else if (BancoSEI::getInstance() instanceof InfraSqlServer) {
            BancoSEI::getInstance()->executarSql("TRUNCATE TABLE seq_tarefa; SET IDENTITY_INSERT seq_tarefa ON; INSERT INTO seq_tarefa (id) VALUES (" . ++$idMaxIdTarefa . "); SET IDENTITY_INSERT seq_tarefa OFF;");
        }

        $this->logar('GERANDO ANDAMENTO PRÓPRIO DO MÓDULO DOS CORREIOS NO PROCESSO PARA REGISTRAR ANDAMENTO DA EXCLUSÃO DA SOLICITAÇÃO DE EXPEDIÇÃO');

        $texto2 = "Solicitação de Expedição pelos Correios referente ao Documento @DOCUMENTO@ excluída pela Unidade Solicitante, em razão de: @JUSTIFICATIVA_EXCLUSAO_EXPEDICAO@";

        $arrMaxIdTarefa = BancoSEI::getInstance()->consultarSql('SELECT MAX(id_tarefa) as max FROM tarefa');
        $numIdTarefaMax = $arrMaxIdTarefa[0]['max'];

        if ($numIdTarefaMax < 1000) {
            $numIdTarefaMax = 1000;
        } else {
            $numIdTarefaMax++;
        }

        $tarefaDTO2 = new TarefaDTO();
        $tarefaDTO2->setNumIdTarefa($numIdTarefaMax);
        $tarefaDTO2->setStrIdTarefaModulo('MD_COR_EXCLUIR_EXPEDICAO');
        $tarefaDTO2->setStrNome($texto2);
        $tarefaDTO2->setStrSinHistoricoResumido('N');
        $tarefaDTO2->setStrSinHistoricoCompleto('S');
        $tarefaDTO2->setStrSinFecharAndamentosAbertos('S');
        $tarefaDTO2->setStrSinLancarAndamentoFechado('N');
        $tarefaDTO2->setStrSinPermiteProcessoFechado('N');

        if (BancoSEI::getInstance() instanceof InfraMySql) {
            BancoSEI::getInstance()->executarSql(" DELETE FROM seq_tarefa");
            BancoSEI::getInstance()->executarSql(" INSERT INTO seq_tarefa (id) VALUES (" . $numIdTarefaMax . ") ");
        }
        if (BancoSEI::getInstance() instanceof InfraOracle) {
            BancoSEI::getInstance()->executarSql("drop sequence seq_tarefa");
            BancoSEI::getInstance()->executarSql("CREATE SEQUENCE seq_tarefa START WITH " . $numIdTarefaMax . " INCREMENT BY 1 NOCACHE NOCYCLE");
        } else if (BancoSEI::getInstance() instanceof InfraSqlServer) {
            BancoSEI::getInstance()->executarSql("TRUNCATE TABLE seq_tarefa; SET IDENTITY_INSERT seq_tarefa ON; INSERT INTO seq_tarefa (id) VALUES (" . $numIdTarefaMax . "); SET IDENTITY_INSERT seq_tarefa OFF;");
        }

        $tarefaRN = new TarefaRN();
        $tarefaRN->cadastrar($tarefaDTO2);

        $this->logar('CORRIGINDO O VALOR DA TABELA SEQ_TAREFA QUANDO TIVER VALORES MAIOR QUE 1000');
        $arrMaxIdTarefaSemModulo = BancoSEI::getInstance()->consultarSql('SELECT MAX(id_tarefa) as max FROM tarefa WHERE id_tarefa < 1000');
        $idMaxIdTarefa = $arrMaxIdTarefaSemModulo[0]['max'];
        if (BancoSEI::getInstance() instanceof InfraMySql) {
            BancoSEI::getInstance()->executarSql(" DELETE FROM seq_tarefa");
            BancoSEI::getInstance()->executarSql(" INSERT INTO seq_tarefa (id) VALUES (" . ++$idMaxIdTarefa . ") ");
        } else if (BancoSEI::getInstance() instanceof InfraOracle) {
            BancoSEI::getInstance()->executarSql("drop sequence seq_tarefa");
            BancoSEI::getInstance()->executarSql("CREATE SEQUENCE seq_tarefa START WITH " . ++$idMaxIdTarefa . " INCREMENT BY 1 NOCACHE NOCYCLE");
        } else if (BancoSEI::getInstance() instanceof InfraSqlServer) {
            BancoSEI::getInstance()->executarSql("TRUNCATE TABLE seq_tarefa; SET IDENTITY_INSERT seq_tarefa ON; INSERT INTO seq_tarefa (id) VALUES (" . ++$idMaxIdTarefa . "); SET IDENTITY_INSERT seq_tarefa OFF;");
        }

        $this->logar('ALTERANDO A TABELA md_cor_adm_parametro_ar');
        $objInfraMetaBD->adicionarColuna('md_cor_adm_parametro_ar', 'id_contato', $objInfraMetaBD->tipoNumero(), 'NULL');

        $this->logar('ADICIONANDO CHAVE ESTRANGEIRA PARA O CONTATO NA TABELA md_cor_adm_parametro_ar');
        $objInfraMetaBD->adicionarChaveEstrangeira('fk8_md_cor_adm_parametro_ar', 'md_cor_adm_parametro_ar', array('id_contato'), 'contato', array('id_contato'));

        $this->logar('CRIANDO A TABELA md_cor_justificativa');
        BancoSEI::getInstance()->executarSql('CREATE TABLE md_cor_justificativa ( 
					id_md_cor_justificativa ' . $objInfraMetaBD->tipoNumero() . ' NOT NULL , 
					nome ' . $objInfraMetaBD->tipoTextoFixo(150) . ' NOT NULL,
			        sin_ativo ' . $objInfraMetaBD->tipoTextoFixo(1) . ' NOT NULL
					)'
        );

        $objInfraMetaBD->adicionarChavePrimaria('md_cor_justificativa', 'pk_md_cor_justificativa', array('id_md_cor_justificativa'));

        $this->logar('CRIANDO A SEQUENCE seq_md_cor_justificativa');
        BancoSEI::getInstance()->criarSequencialNativa('seq_md_cor_justificativa', 1);


        $this->logar('CRIANDO A TABELA rel_contato_justificativa');
        BancoSEI::getInstance()->executarSql('CREATE TABLE rel_contato_justificativa ( 
					id_rel_contato_justificativa ' . $objInfraMetaBD->tipoNumero() . ' NOT NULL ,
					id_contato ' . $objInfraMetaBD->tipoNumero() . ' NOT NULL , 
					id_md_cor_justificativa ' . $objInfraMetaBD->tipoNumero() . ' NOT NULL 
					)'
        );

        $objInfraMetaBD->adicionarChavePrimaria('rel_contato_justificativa', 'pk_rel_contato_justificativa', array('id_rel_contato_justificativa'));
        $objInfraMetaBD->adicionarChaveEstrangeira('fk1_rel_contato_justificativa', 'rel_contato_justificativa', array('id_contato'), 'contato', array('id_contato'));
        $objInfraMetaBD->adicionarChaveEstrangeira('fk2_rel_contato_justificativa', 'rel_contato_justificativa', array('id_md_cor_justificativa'), 'md_cor_justificativa', array('id_md_cor_justificativa'));

        $this->logar('CRIANDO A SEQUENCE seq_rel_contato_justificativa');
        BancoSEI::getInstance()->criarSequencialNativa('seq_rel_contato_justificativa', 1);

        $objInfraMetaBD = new InfraMetaBD(BancoSEI::getInstance());
        $objInfraMetaBD->setBolValidarIdentificador(true);

        $arrTabelas = array('md_cor_adm_par_ar_infrigen', 'md_cor_adm_parametro_ar', 'md_cor_ar_cobranca', 'md_cor_contato', 'md_cor_contrato', 'md_cor_diretoria', 'md_cor_embalagem', 'md_cor_expedicao_andamento', 'md_cor_expedicao_anexo', 'md_cor_expedicao_formato', 'md_cor_expedicao_solicitad', 'md_cor_extensao_midia', 'md_cor_justificativa', 'md_cor_lista_status', 'md_cor_map_unid_servico', 'md_cor_map_unidade_exp', 'md_cor_objeto', 'md_cor_parametro_rastreio', 'md_cor_plp', 'md_cor_retorno_ar', 'md_cor_retorno_ar_doc', 'md_cor_serie_exp', 'md_cor_servico_postal', 'md_cor_status_process', 'md_cor_substatus_process', 'md_cor_tipo_correspondenc', 'md_cor_tipo_embalagem', 'md_cor_tipo_objeto', 'md_cor_unidade_exp');

        $this->fixIndices($objInfraMetaBD, $arrTabelas);

        $this->logar('ADICIONANDO PARÂMETRO ' . $this->nomeParametroModulo . ' NA TABELA infra_parametro PARA CONTROLAR A VERSÃO DO MÓDULO');
        BancoSEI::getInstance()->executarSql('INSERT INTO infra_parametro (valor, nome) VALUES (\'1.0.0\' , \'' . $this->nomeParametroModulo . '\' ) ');

        $this->logar('INSTALAÇÃO/ATUALIZAÇÃO DA VERSÃO 1.0.0 DO ' . $this->nomeDesteModulo . ' REALIZADA COM SUCESSO NA BASE DO SEI');
    }

    private function instalarv200()
    {
	    $nmVersao = '2.0.0';
	    $objInfraParametro = new InfraParametro(BancoSEI::getInstance());
        $this->logar('EXECUTANDO A INSTALAÇÃO/ATUALIZAÇÃO DA VERSAO '. $nmVersao .' DO ' . $this->nomeDesteModulo . ' NA BASE DO SEI');

        $objInfraMetaBD = new InfraMetaBD(BancoSEI::getInstance());
        $objInfraMetaBD->setBolValidarIdentificador(true);

        $arrTabelas = array('md_cor_adm_par_ar_infrigen', 'md_cor_adm_parametro_ar', 'md_cor_ar_cobranca', 'md_cor_contato', 'md_cor_contrato', 'md_cor_diretoria', 'md_cor_embalagem', 'md_cor_expedicao_andamento', 'md_cor_expedicao_anexo', 'md_cor_expedicao_formato', 'md_cor_expedicao_solicitad', 'md_cor_extensao_midia', 'md_cor_justificativa', 'md_cor_lista_status', 'md_cor_map_unid_servico', 'md_cor_map_unidade_exp', 'md_cor_objeto', 'md_cor_parametro_rastreio', 'md_cor_plp', 'md_cor_retorno_ar', 'md_cor_retorno_ar_doc', 'md_cor_serie_exp', 'md_cor_servico_postal', 'md_cor_status_process', 'md_cor_substatus_process', 'md_cor_tipo_correspondenc', 'md_cor_tipo_embalagem', 'md_cor_tipo_objeto', 'md_cor_unidade_exp');

        $this->fixIndices($objInfraMetaBD, $arrTabelas);

	    $this->atualizarNumeroVersao($nmVersao);
    }

    private function instalarv210()
    {
	    $nmVersao = '2.1.0';
        $objInfraParametro = new InfraParametro(BancoSEI::getInstance());
        $this->logar('EXECUTANDO A INSTALAÇÃO/ATUALIZAÇÃO DA VERSAO '. $nmVersao .' DO ' . $this->nomeDesteModulo . ' NA BASE DO SEI');

        $objInfraMetaBD = new InfraMetaBD(BancoSEI::getInstance());
        $objInfraMetaBD->setBolValidarIdentificador(true);

        $this->logar('CRIANDO AGENDAMENTO PARA FINALIZAR O FLUXO QUANDO O AR NÃO É RETORNADO APÓS 5 MESES.');

        $infraAgendamentoDTO = new InfraAgendamentoTarefaDTO();
        $infraAgendamentoRN = new InfraAgendamentoTarefaRN();

        $infraAgendamentoDTO->setStrComando('MdCorAgendamentoAutomaticoRN::finalizarFluxoArNaoRetornado');
        $infraAgendamentoDTO->setStrDescricao('Agendamento para Finalizar o Fluxo quando o AR não retornado após 5 meses');
        $infraAgendamentoDTO->setStrSinAtivo('S');
        $infraAgendamentoDTO->setStrStaPeriodicidadeExecucao(InfraAgendamentoTarefaRN::$PERIODICIDADE_EXECUCAO_HORA);
        $infraAgendamentoDTO->setStrPeriodicidadeComplemento(0);
        $infraAgendamentoDTO->setStrParametro(null);
        $infraAgendamentoDTO->setDthUltimaExecucao(null);
        $infraAgendamentoDTO->setDthUltimaConclusao(null);
        $infraAgendamentoDTO->setStrSinSucesso('S');
        $infraAgendamentoDTO->setStrEmailErro($objInfraParametro->getValor('SEI_EMAIL_ADMINISTRADOR'));

        $infraAgendamentoRN->cadastrar($infraAgendamentoDTO);
        $this->logar('FIM CRIAR AGENDAMENTO PARA FINALIZAR O FLUXO QUANDO O AR NÃO É RETORNADO APÓS 5 MESES.');

        $this->logar('EXCLUINDO A COLUNA observacao DA TABELA md_cor_expedicao_solicitad');
        $objInfraMetaBD->excluirColuna('md_cor_expedicao_solicitad', 'observacao');

        $this->logar('ADICIONANDO A COLUNA dias_exp_ret_ar NA TABELA md_cor_adm_parametro_ar');
        $objInfraMetaBD->adicionarColuna('md_cor_adm_parametro_ar', 'dias_exp_ret_ar', $objInfraMetaBD->tipoTextoVariavel(6), '');

        $objMdCorParametroArRN = new MdCorParametroArRN();
        $objMdCorParametroArDTO = new MdCorParametroArDTO();
        $objMdCorParametroArDTO->setNumMaxRegistrosRetorno(1);
        $objMdCorParametroArDTO->retNumIdMdCorParametroAr();

        $objMdCorParametroArDTO = $objMdCorParametroArRN->consultar($objMdCorParametroArDTO);
        $objMdCorParametroArDTO->setStrNuDiasPrazoExpRetAr('150');
        $objMdCorParametroArRN->alterar($objMdCorParametroArDTO);

        $this->inicializar('ADICIONANDO COLUNA "sin_devolvido" NA TABELA "md_cor_expedicao_solicitad"');
        $objInfraMetaBD = new InfraMetaBD(BancoSEI::getInstance());
        $objInfraMetaBD->adicionarColuna('md_cor_expedicao_solicitad','sin_devolvido',$objInfraMetaBD->tipoTextoFixo(1),'null');
        BancoSEI::getInstance()->executarSql("update md_cor_expedicao_solicitad set sin_devolvido = 'N'");

        $this->inicializar('ADICIONANDO COLUNA "justificativa_devolucao" NA TABELA "md_cor_expedicao_solicitad"');
        $objInfraMetaBD->adicionarColuna('md_cor_expedicao_solicitad', 'justificativa_devolucao', $objInfraMetaBD->tipoTextoVariavel(250), 'NULL');


        $texto1 = "Solicitação de Expedição pelos Correios referente ao Documento @DOCUMENTO@ devolvida pela Unidade Expedidora, em razão de: @JUSTIFICATIVA_DEVOLUCAO_SOLICITACAO_EXPEDICAO@";

        $arrMaxIdTarefa = BancoSEI::getInstance()->consultarSql('SELECT MAX(id_tarefa) as max FROM tarefa');
        $numIdTarefaMax = $arrMaxIdTarefa[0]['max'];

        if ($numIdTarefaMax < 1000) {
            $numIdTarefaMax = 1000;
        } else {
            $numIdTarefaMax++;
        }

        $tarefaDTO1 = new TarefaDTO();
        $tarefaDTO1->setNumIdTarefa($numIdTarefaMax);
        $tarefaDTO1->setStrIdTarefaModulo('MD_COR_SOLICITACAO_DEVOLUCAO');
        $tarefaDTO1->setStrNome($texto1);
        $tarefaDTO1->setStrSinHistoricoResumido('N');
        $tarefaDTO1->setStrSinHistoricoCompleto('S');
        $tarefaDTO1->setStrSinFecharAndamentosAbertos('S');
        $tarefaDTO1->setStrSinLancarAndamentoFechado('N');
        $tarefaDTO1->setStrSinPermiteProcessoFechado('N');

        if (BancoSEI::getInstance() instanceof InfraMySql) {
            BancoSEI::getInstance()->executarSql(" DELETE FROM seq_tarefa");
            BancoSEI::getInstance()->executarSql(" INSERT INTO seq_tarefa (id) VALUES (" . $numIdTarefaMax . ") ");
        } elseif (BancoSEI::getInstance() instanceof InfraOracle) {
            BancoSEI::getInstance()->executarSql("drop sequence seq_tarefa");
            BancoSEI::getInstance()->executarSql("CREATE SEQUENCE seq_tarefa START WITH " . $numIdTarefaMax . " INCREMENT BY 1 NOCACHE NOCYCLE");
        } else if (BancoSEI::getInstance() instanceof InfraSqlServer) {
            BancoSEI::getInstance()->executarSql("TRUNCATE TABLE seq_tarefa; SET IDENTITY_INSERT seq_tarefa ON; INSERT INTO seq_tarefa (id) VALUES (" . $numIdTarefaMax . "); SET IDENTITY_INSERT seq_tarefa OFF;");
        }

        $tarefaRN = new TarefaRN();
        $tarefaRN->cadastrar($tarefaDTO1);



        //INSERCAO DE NOVOS MODELOS DE EMAIL NO MENU E-MAILS DO SISTEMA
        $this->logar('INSERINDO EMAIL MD_COR_EXPEDICAO_SOLICITADA_DEVOLVER NA TABELA email_sistema');

        $conteudoVinculoRestabelecimento = "      :: Este é um e-mail automático ::

A Unidade Expedidora de correspondências pelos Correios @unidade_expedidora@ do SEI-@sigla_orgao@ devolveu a solicitação de expedição referente ao documento SEI nº @documento@ no processo nº @processo@ em razão de: @justificativa_devolucao_solicitacao_expedicao@.

Com isso, esta unidade solicitante deve realizar as alterações devidas ou a exclusão da solicitação de expedição.


@sigla_orgao@
@descricao_orgao@
@sitio_internet_orgao@

ATENÇÃO: As informações contidas neste e-mail, incluindo seus anexos, podem ser restritas apenas à pessoa ou entidade para a qual foi endereçada. Se você não é o destinatário ou a pessoa responsável por encaminhar esta mensagem ao destinatário, você está, por meio desta, notificado que não deverá rever, retransmitir, imprimir, copiar, usar ou distribuir esta mensagem ou quaisquer anexos. Caso você tenha recebido esta mensagem por engano, por favor, contate o remetente imediatamente e em seguida apague esta mensagem.";

        $maxIdEmailSistemaVinculoRestabelecimento = $this->retornarMaxIdEmailSistema();

        $insertVinculoRestabelecimento = "INSERT INTO email_sistema
            (id_email_sistema,
            descricao,
            de,
            para,
            assunto,
            conteudo,
            sin_ativo,
            id_email_sistema_modulo
             )
        VALUES
            (" . $maxIdEmailSistemaVinculoRestabelecimento . ",
            'Correios - Devolução da Solicitação da Expedição',
            '@sigla_sistema@ <@email_sistema@>',
            '@emails_unidade@',
            'SEI Correios - Devolução de Solicitação de Expedição no processo nº @processo@',
            '" . $conteudoVinculoRestabelecimento . "',
            'S',
            'MD_COR_EXPEDICAO_SOLICITADA_DEVOLVER'
            )";

        BancoSEI::getInstance()->executarSql($insertVinculoRestabelecimento);

        $this->inicializar('ADICIONANDO COLUNA "sta_rastreio_modulo" NA TABELA "md_cor_lista_status"');
        $objInfraMetaBD->adicionarColuna('md_cor_lista_status', 'sta_rastreio_modulo', $objInfraMetaBD->tipoTextoVariavel(1), 'NULL');

        $this->inicializar('ATUALIZADO OS DADOS DA COLUNA "sta_rastreio_modulo" DE ACORDO COM A COLUNA "nome_imagem"');
        BancoSEI::getInstance()->executarSql("UPDATE md_cor_lista_status SET sta_rastreio_modulo = 'P' WHERE nome_imagem LIKE '%rastreamento_postagem%'");
        BancoSEI::getInstance()->executarSql("UPDATE md_cor_lista_status SET sta_rastreio_modulo = 'T' WHERE nome_imagem LIKE '%rastreamento_em_transito%'");
        BancoSEI::getInstance()->executarSql("UPDATE md_cor_lista_status SET sta_rastreio_modulo = 'S' WHERE nome_imagem LIKE '%rastreamento_sucesso%'");
        BancoSEI::getInstance()->executarSql("UPDATE md_cor_lista_status SET sta_rastreio_modulo = 'I' WHERE nome_imagem LIKE '%rastreamento_cancelado%'");
        BancoSEI::getInstance()->executarSql("UPDATE md_cor_lista_status SET sta_rastreio_modulo = 'T' WHERE nome_imagem LIKE '%rastreamento_em_transito%'");


        $this->logar('EXCLUINDO A COLUNA nome_imagem DA TABELA md_cor_lista_status');
        $objInfraMetaBD->excluirColuna('md_cor_lista_status', 'nome_imagem');

        $arrTabelas = array('md_cor_adm_par_ar_infrigen', 'md_cor_adm_parametro_ar', 'md_cor_ar_cobranca', 'md_cor_contato', 'md_cor_contrato', 'md_cor_diretoria', 'md_cor_embalagem', 'md_cor_expedicao_andamento', 'md_cor_expedicao_anexo', 'md_cor_expedicao_formato', 'md_cor_expedicao_solicitad', 'md_cor_extensao_midia', 'md_cor_justificativa', 'md_cor_lista_status', 'md_cor_map_unid_servico', 'md_cor_map_unidade_exp', 'md_cor_objeto', 'md_cor_parametro_rastreio', 'md_cor_plp', 'md_cor_retorno_ar', 'md_cor_retorno_ar_doc', 'md_cor_serie_exp', 'md_cor_servico_postal', 'md_cor_status_process', 'md_cor_substatus_process', 'md_cor_tipo_correspondenc', 'md_cor_tipo_embalagem', 'md_cor_tipo_objeto', 'md_cor_unidade_exp');

        $this->fixIndices($objInfraMetaBD, $arrTabelas);

        $this->atualizarNumeroVersao($nmVersao);
    }

    protected function instalarv220(){
	    $nmVersao = '2.2.0';
	    $objInfraParametro = new InfraParametro(BancoSEI::getInstance());
	    $this->logar('EXECUTANDO A INSTALAÇÃO/ATUALIZAÇÃO DA VERSAO '. $nmVersao .' DO ' . $this->nomeDesteModulo . ' NA BASE DO SEI');

	    $objInfraMetaBD = new InfraMetaBD(BancoSEI::getInstance());
	    $objInfraMetaBD->setBolValidarIdentificador(true);

	    $this->logar('ADICIONANDO A COLUNA sin_anexar_midia NA TABELA md_cor_servico_postal');
	    $objInfraMetaBD->adicionarColuna('md_cor_servico_postal', 'sin_anexar_midia', $objInfraMetaBD->tipoTextoFixo(1), 'null');
	    BancoSEI::getInstance()->executarSql("update md_cor_servico_postal set sin_anexar_midia = 'N'");
	    $objInfraMetaBD->alterarColuna('md_cor_servico_postal', 'sin_anexar_midia', $objInfraMetaBD->tipoTextoFixo(1), 'not null');
	    $this->logar('COLUNA sin_anexar_midia criada, valor atualizado para \'N\' e, posteriormente, modificada para NOT NULL');

	    $this->atualizarNumeroVersao($nmVersao);
    }

	protected function instalarv230(){
		$nmVersao = '2.3.0';
		$objInfraParametro = new InfraParametro(BancoSEI::getInstance());
		$this->logar('EXECUTANDO A INSTALAÇÃO/ATUALIZAÇÃO DA VERSAO '. $nmVersao .' DO ' . $this->nomeDesteModulo . ' NA BASE DO SEI');

		$objInfraMetaBD     = new InfraMetaBD(BancoSEI::getInstance());
        $objMdCorAdmIntegRN = new MdCorAdmIntegracaoRN();

		$this->logar('CRIANDO A TABELA md_cor_adm_integracao');
		BancoSEI::getInstance()->executarSql('CREATE TABLE md_cor_adm_integracao ( 
					id_md_cor_adm_integracao ' . $objInfraMetaBD->tipoNumero() . ' NOT NULL ,
					funcionalidade ' . $objInfraMetaBD->tipoNumero() . ' NOT NULL , 
					nome ' . $objInfraMetaBD->tipoTextoVariavel(200) . ' NOT NULL,
					url_operacao '. $objInfraMetaBD->tipoTextoVariavel(500) .' NOT NULL,					
					usuario '. $objInfraMetaBD->tipoTextoVariavel(100) .' NULL,
					senha '. $objInfraMetaBD->tipoTextoVariavel(1000) .' NULL,
					token '. $objInfraMetaBD->tipoTextoVariavel(1500) .' NULL,					
					data_exp_token '. $objInfraMetaBD->tipoDataHora() .' NULL,
					sin_ativo '. $objInfraMetaBD->tipoTextoFixo(1) .' NOT NULL
				)'
		);

		$objInfraMetaBD->adicionarChavePrimaria('md_cor_adm_integracao', 'pk_md_cor_adm_integracao', array('id_md_cor_adm_integracao'));

		$this->logar('CRIANDO A SEQUENCE seq_md_cor_adm_integracao');
		BancoSEI::getInstance()->criarSequencialNativa('seq_md_cor_adm_integracao', 1);

		// GERAR TOKEN
		$this->logar('CADASTRAR REGISTRO DE INTEGRAÇÃO: GERAR TOKEN');
		$objMdCorAdmIntegDTO = new MdCorAdmIntegracaoDTO();
		$objMdCorAdmIntegDTO->retTodos();
		$objMdCorAdmIntegDTO->setNumFuncionalidade(1);
		$objMdCorAdmIntegDTO->setStrNome('Gerar Token');
		$objMdCorAdmIntegDTO->setStrUrlOperacao('https://apihom.correios.com.br/token/v1/autentica/cartaopostagem');
		$objMdCorAdmIntegDTO->setStrUsuario(null);
		$objMdCorAdmIntegDTO->setStrSenha(null);
		$objMdCorAdmIntegDTO->setStrToken(null);
		$objMdCorAdmIntegDTO->setDthDataExpiraToken(null);
		$objMdCorAdmIntegDTO->setStrSinAtivo('S');
		$objMdCorAdmIntegRN->cadastrar($objMdCorAdmIntegDTO);

		// RASTREAR OBJETO
		$this->logar('CADASTRAR REGISTRO DE INTEGRAÇÃO: RASTREAR OBJETO');
		$objMdCorAdmIntegDTO = new MdCorAdmIntegracaoDTO();
		$objMdCorAdmIntegDTO->retTodos();
		$objMdCorAdmIntegDTO->setNumFuncionalidade(2);
		$objMdCorAdmIntegDTO->setStrNome('Rastrear Objeto');
		$objMdCorAdmIntegDTO->setStrUrlOperacao('https://apihom.correios.com.br/srorastro/v1');
		$objMdCorAdmIntegDTO->setStrUsuario(null);
		$objMdCorAdmIntegDTO->setStrSenha(null);
		$objMdCorAdmIntegDTO->setStrToken(null);
		$objMdCorAdmIntegDTO->setDthDataExpiraToken(InfraData::getStrDataHoraAtual());
		$objMdCorAdmIntegDTO->setStrSinAtivo('S');
		$objMdCorAdmIntegRN->cadastrar($objMdCorAdmIntegDTO);

		// CONSULTAR CEP
		$this->logar('CADASTRAR REGISTRO DE INTEGRAÇÃO: CONSULTAR CEP');
		$objMdCorAdmIntegDTO = new MdCorAdmIntegracaoDTO();
		$objMdCorAdmIntegDTO->retTodos();
		$objMdCorAdmIntegDTO->setNumFuncionalidade(3);
		$objMdCorAdmIntegDTO->setStrNome('Consultar CEP');
		$objMdCorAdmIntegDTO->setStrUrlOperacao('https://apihom.correios.com.br/cep/v2');
		$objMdCorAdmIntegDTO->setStrUsuario(null);
		$objMdCorAdmIntegDTO->setStrSenha(null);
		$objMdCorAdmIntegDTO->setStrToken(null);
		$objMdCorAdmIntegDTO->setDthDataExpiraToken(InfraData::getStrDataHoraAtual());
		$objMdCorAdmIntegDTO->setStrSinAtivo('S');
		$objMdCorAdmIntegRN->cadastrar($objMdCorAdmIntegDTO);

		// CONTRATO - SERVICOS POSTAIS
		$this->logar('CADASTRAR REGISTRO DE INTEGRAÇÃO: SERVICOS POSTAIS');
		$objMdCorAdmIntegDTO = new MdCorAdmIntegracaoDTO();
		$objMdCorAdmIntegDTO->retTodos();
		$objMdCorAdmIntegDTO->setNumFuncionalidade(4);
		$objMdCorAdmIntegDTO->setStrNome('Serviços Postais');
		$objMdCorAdmIntegDTO->setStrUrlOperacao('https://apihom.correios.com.br/meucontrato/v1');
		$objMdCorAdmIntegDTO->setStrUsuario(null);
		$objMdCorAdmIntegDTO->setStrSenha(null);
		$objMdCorAdmIntegDTO->setStrToken(null);
		$objMdCorAdmIntegDTO->setDthDataExpiraToken(InfraData::getStrDataHoraAtual());
		$objMdCorAdmIntegDTO->setStrSinAtivo('S');
		$objMdCorAdmIntegRN->cadastrar($objMdCorAdmIntegDTO);

		// SOLICITA ETIQUETAS
		$this->logar('CADASTRAR REGISTRO DE INTEGRAÇÃO: PRE POSTAGEM - SOLICITA ETIQUETAS');
		$objMdCorAdmIntegDTO = new MdCorAdmIntegracaoDTO();
		$objMdCorAdmIntegDTO->retTodos();
		$objMdCorAdmIntegDTO->setNumFuncionalidade(5);
		$objMdCorAdmIntegDTO->setStrNome('Solicitar Etiquetas');
		$objMdCorAdmIntegDTO->setStrUrlOperacao('https://apihom.correios.com.br/prepostagem/v1/prepostagens/rotulo/range');
		$objMdCorAdmIntegDTO->setStrUsuario(null);
		$objMdCorAdmIntegDTO->setStrSenha(null);
		$objMdCorAdmIntegDTO->setStrToken(null);
		$objMdCorAdmIntegDTO->setDthDataExpiraToken(InfraData::getStrDataHoraAtual());
		$objMdCorAdmIntegDTO->setStrSinAtivo('S');
		$objMdCorAdmIntegRN->cadastrar($objMdCorAdmIntegDTO);

		// PRE POSTAGEM
		$this->logar('CADASTRAR REGISTRO DE INTEGRAÇÃO: PRE POSTAGEM - SOLICITA PRE-POSTAGEM');
		$objMdCorAdmIntegDTO = new MdCorAdmIntegracaoDTO();
		$objMdCorAdmIntegDTO->retTodos();
		$objMdCorAdmIntegDTO->setNumFuncionalidade(6);
		$objMdCorAdmIntegDTO->setStrNome('Pré Postagem Nacional');
		$objMdCorAdmIntegDTO->setStrUrlOperacao('https://apihom.correios.com.br/prepostagem/v1/prepostagens');
		$objMdCorAdmIntegDTO->setStrUsuario(null);
		$objMdCorAdmIntegDTO->setStrSenha(null);
		$objMdCorAdmIntegDTO->setStrToken(null);
		$objMdCorAdmIntegDTO->setDthDataExpiraToken(InfraData::getStrDataHoraAtual());
		$objMdCorAdmIntegDTO->setStrSinAtivo('S');
		$objMdCorAdmIntegRN->cadastrar($objMdCorAdmIntegDTO);

		// EMITE ROTULO
		$this->logar('CADASTRAR REGISTRO DE INTEGRAÇÃO: EMISSAO DE ROTULO');
		$objMdCorAdmIntegDTO = new MdCorAdmIntegracaoDTO();
		$objMdCorAdmIntegDTO->retTodos();
		$objMdCorAdmIntegDTO->setNumFuncionalidade(7);
		$objMdCorAdmIntegDTO->setStrNome('Emitir Rótulo');
		$objMdCorAdmIntegDTO->setStrUrlOperacao('https://apihom.correios.com.br/prepostagem/v1/prepostagens/rotulo/assincrono/pdf');
		$objMdCorAdmIntegDTO->setStrUsuario(null);
		$objMdCorAdmIntegDTO->setStrSenha(null);
		$objMdCorAdmIntegDTO->setStrToken(null);
		$objMdCorAdmIntegDTO->setDthDataExpiraToken(InfraData::getStrDataHoraAtual());
		$objMdCorAdmIntegDTO->setStrSinAtivo('S');
		$objMdCorAdmIntegRN->cadastrar($objMdCorAdmIntegDTO);

		// DOWNLOAD DO ROTULO
		$this->logar('CADASTRAR REGISTRO DE INTEGRAÇÃO: DOWNLOAD DO ROTULO');
		$objMdCorAdmIntegDTO = new MdCorAdmIntegracaoDTO();
		$objMdCorAdmIntegDTO->retTodos();
		$objMdCorAdmIntegDTO->setNumFuncionalidade(8);
		$objMdCorAdmIntegDTO->setStrNome('Download Rótulo');
		$objMdCorAdmIntegDTO->setStrUrlOperacao('https://apihom.correios.com.br/prepostagem/v1/prepostagens/rotulo/download');
		$objMdCorAdmIntegDTO->setStrUsuario(null);
		$objMdCorAdmIntegDTO->setStrSenha(null);
		$objMdCorAdmIntegDTO->setStrToken(null);
		$objMdCorAdmIntegDTO->setDthDataExpiraToken(InfraData::getStrDataHoraAtual());
		$objMdCorAdmIntegDTO->setStrSinAtivo('S');
		$objMdCorAdmIntegRN->cadastrar($objMdCorAdmIntegDTO);

        // AVISO DE RECEBIMENTO
        $this->logar('CADASTRAR REGISTRO DE INTEGRAÇÃO: AVISO DE RECEBIMENTO');
        $objMdCorAdmIntegDTO = new MdCorAdmIntegracaoDTO();
        $objMdCorAdmIntegDTO->retTodos();
        $objMdCorAdmIntegDTO->setNumFuncionalidade(9);
        $objMdCorAdmIntegDTO->setStrNome('Aviso de Recebimento');
        $objMdCorAdmIntegDTO->setStrUrlOperacao('https://apihom.correios.com.br/prepostagem/v1/prepostagens');
        $objMdCorAdmIntegDTO->setStrUsuario(null);
        $objMdCorAdmIntegDTO->setStrSenha(null);
        $objMdCorAdmIntegDTO->setStrToken(null);
        $objMdCorAdmIntegDTO->setDthDataExpiraToken(InfraData::getStrDataHoraAtual());
        $objMdCorAdmIntegDTO->setStrSinAtivo('S');
        $objMdCorAdmIntegRN->cadastrar($objMdCorAdmIntegDTO);

        // CANCELAR PRE POSTAGEM
        $this->logar('CADASTRAR REGISTRO DE INTEGRAÇÃO: CANCELAR PRE POSTAGEM');
        $objMdCorAdmIntegDTO = new MdCorAdmIntegracaoDTO();
        $objMdCorAdmIntegDTO->retTodos();
        $objMdCorAdmIntegDTO->setNumFuncionalidade(10);
        $objMdCorAdmIntegDTO->setStrNome('Cancelar Pré Postagem');
        $objMdCorAdmIntegDTO->setStrUrlOperacao('https://apihom.correios.com.br/prepostagem/v1/prepostagens');
        $objMdCorAdmIntegDTO->setStrUsuario(null);
        $objMdCorAdmIntegDTO->setStrSenha(null);
        $objMdCorAdmIntegDTO->setStrToken(null);
        $objMdCorAdmIntegDTO->setDthDataExpiraToken(InfraData::getStrDataHoraAtual());
        $objMdCorAdmIntegDTO->setStrSinAtivo('S');
        $objMdCorAdmIntegRN->cadastrar($objMdCorAdmIntegDTO);

		$this->logar('ADICIONANDO COLUNA "id_pre_postagem" NA TABELA "md_cor_expedicao_solicitad"');
		$objInfraMetaBD->adicionarColuna('md_cor_expedicao_solicitad', 'id_pre_postagem', $objInfraMetaBD->tipoTextoVariavel(100), 'NULL');

		// Remocao de colunas da tabela md_cor_contrato e alteração na coluna id_md_cor_diretoria para NULL
        $this->logar('Removendo a COLUNA "url_webservice" NA TABELA "md_cor_contrato"');
        $objInfraMetaBD->excluirColuna('md_cor_contrato','url_webservice');

        $this->logar('Removendo a COLUNA "codigo_administrativo" NA TABELA "md_cor_contrato"');
        $objInfraMetaBD->excluirColuna('md_cor_contrato','codigo_administrativo');

        $this->logar('Removendo a COLUNA "usuario" NA TABELA "md_cor_contrato"');
        $objInfraMetaBD->excluirColuna('md_cor_contrato','usuario');

        $this->logar('Removendo a COLUNA "senha" NA TABELA "md_cor_contrato"');
        $objInfraMetaBD->excluirColuna('md_cor_contrato','senha');

        $this->logar('Removendo a COLUNA "numero_ano_contrato" NA TABELA "md_cor_contrato"');
        $objInfraMetaBD->excluirColuna('md_cor_contrato','numero_ano_contrato');

        $objInfraMetaBD->alterarColuna('md_cor_contrato', 'id_md_cor_diretoria', $objInfraMetaBD->tipoNumero(), 'null');

        // Drop na tabela md_cor_parametro_rastreio
        $this->logar('Remover a sequence e tabela md_cor_parametro_rastreio');
        if (BancoSEI::getInstance() instanceof InfraOracle) {
            BancoSEI::getInstance()->executarSql('drop sequence seq_md_cor_parametro_rastreio');
        } else {
            BancoSEI::getInstance()->executarSql('DROP TABLE seq_md_cor_parametro_rastreio');
        }
        BancoSEI::getInstance()->executarSql('DROP TABLE md_cor_parametro_rastreio');

        $this->logar('Altera as colunas numero_contrato, numero_contrato_correio e numero_cartao_postagem de char() para varchar()');
        $objInfraMetaBD->alterarColuna('md_cor_contrato', 'numero_contrato', $objInfraMetaBD->tipoTextoVariavel(50), 'not null');
        $objInfraMetaBD->alterarColuna('md_cor_contrato', 'numero_contrato_correio', $objInfraMetaBD->tipoTextoVariavel(50), 'not null');
        $objInfraMetaBD->alterarColuna('md_cor_contrato', 'numero_cartao_postagem', $objInfraMetaBD->tipoTextoVariavel(50), 'not null');

		$this->atualizarNumeroVersao($nmVersao);
	}

    protected function fixIndices(InfraMetaBD $objInfraMetaBD, $arrTabelas)
    {
        InfraDebug::getInstance()->setBolDebugInfra(true);

        $this->logar('ATUALIZANDO INDICES...');

        $objInfraMetaBD->processarIndicesChavesEstrangeiras($arrTabelas);

        InfraDebug::getInstance()->setBolDebugInfra(false);
    }

    private function retornarMaxIdEmailSistema()
    {
        $this->logar('BUSCANDO O PROXIMO ID DISPONIVEL NA TABELA EMAIL_SISTEMA');
        $arrMaxIdEmailSistemaSelect = BancoSEI::getInstance()->consultarSql('SELECT MAX(id_email_sistema) as max FROM email_sistema');
        $numMaxIdEmailSistemaSelect = $arrMaxIdEmailSistemaSelect[0]['max'];

        if ($numMaxIdEmailSistemaSelect >= 1000) {
            $this->$numMaxIdEmailSistemaSelect = $numMaxIdEmailSistemaSelect + 1;
        } else {
            $this->$numMaxIdEmailSistemaSelect = 1000;
        }
        return $this->$numMaxIdEmailSistemaSelect;
    }

	/**
	 * Atualiza o número de versão do módulo na tabela de parâmetro do sistema
	 *
	 * @param string $parStrNumeroVersao
	 * @return void
	 */
	private function atualizarNumeroVersao($parStrNumeroVersao)	{
		$this->logar('ATUALIZANDO PARÂMETRO '. $this->nomeParametroModulo .' NA TABELA infra_parametro PARA CONTROLAR A VERSÃO DO MÓDULO');

		$objInfraParametroDTO = new InfraParametroDTO();
		$objInfraParametroDTO->setStrNome($this->nomeParametroModulo);
		$objInfraParametroDTO->retTodos();
		$objInfraParametroBD = new InfraParametroBD(BancoSEI::getInstance());
		$arrObjInfraParametroDTO = $objInfraParametroBD->listar($objInfraParametroDTO);

		foreach ($arrObjInfraParametroDTO as $objInfraParametroDTO) {
			$objInfraParametroDTO->setStrValor($parStrNumeroVersao);
			$objInfraParametroBD->alterar($objInfraParametroDTO);
		}

		$this->logar('INSTALAÇÃO/ATUALIZAÇÃO DA VERSÃO '. $parStrNumeroVersao .' DO '. $this->nomeDesteModulo .' REALIZADA COM SUCESSO NA BASE DO SEI');
	}

}

try {
    SessaoSEI::getInstance(false);
    BancoSEI::getInstance()->setBolScript(true);

    $configuracaoSEI = new ConfiguracaoSEI();
    $arrConfig = $configuracaoSEI->getInstance()->getArrConfiguracoes();

    if (!isset($arrConfig['SEI']['Modulos'])) {
        throw new InfraException('PARÂMETRO DE MÓDULOS NO CONFIGURAÇÃO DO SEI NÃO DECLARADO');
    } else {
        $arrModulos = $arrConfig['SEI']['Modulos'];
        if (!key_exists('CorreiosIntegracao', $arrModulos)) {
            throw new InfraException('MÓDULO CORREIOS NÃO DECLARADO NO CONFIGURAÇÃO DO SEI');
        }
    }

    if (!class_exists('CorreiosIntegracao')) {
        throw new InfraException('A CLASSE PRINCIPAL "CorreiosIntegracao" DO MÓDULO NÃO FOI ENCONTRADA');
    }

    InfraScriptVersao::solicitarAutenticacao(BancoSei::getInstance());
    $objVersaoSeiRN = new MdCorAtualizadorSeiRN();
    $objVersaoSeiRN->atualizarVersao();
    exit;

} catch (Exception $e) {
    echo(InfraException::inspecionar($e));
    try {
        LogSEI::getInstance()->gravar(InfraException::inspecionar($e));
    } catch (Exception $e) {
    }
    exit(1);
}