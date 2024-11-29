<?
require_once dirname(__FILE__) . '/../web/Sip.php';

class MdCorAtualizadorSipRN extends InfraRN
{

    private $numSeg = 0;
    private $versaoAtualDesteModulo = '2.4.0';
    private $nomeDesteModulo = 'MÓDULO DOS CORREIOS';
    private $nomeParametroModulo = 'VERSAO_MODULO_CORREIOS';
    private $historicoVersoes = array('1.0.0', '2.0.0', '2.1.0','2.2.0','2.3.0','2.4.0');

    public function __construct()
    {
        parent::__construct();
    }

    protected function inicializarObjInfraIBanco()
    {
        return BancoSip::getInstance();
    }

    protected function inicializar($strTitulo)
    {
        session_start();
        SessaoSip::getInstance(false);

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

    protected function logar($strMsg)
    {
        InfraDebug::getInstance()->gravar($strMsg);
        flush();
    }

    protected function finalizar($strMsg = null, $bolErro = false)
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
            $this->inicializar('INICIANDO A INSTALAÇÃO/ATUALIZAÇÃO DO ' . $this->nomeDesteModulo . ' NO SIP VERSÃO ' . SIP_VERSAO);

            //checando BDs suportados
            if (!(BancoSip::getInstance() instanceof InfraMySql) &&
                !(BancoSip::getInstance() instanceof InfraSqlServer) &&
                !(BancoSip::getInstance() instanceof InfraOracle) &&
                !(BancoSip::getInstance() instanceof InfraPostgreSql)) {
                $this->finalizar('BANCO DE DADOS NÃO SUPORTADO: ' . get_parent_class(BancoSip::getInstance()), true);
            }

            //testando versao do framework
            $numVersaoInfraRequerida = '2.23.8';
            if (version_compare(VERSAO_INFRA, $numVersaoInfraRequerida) < 0) {
                $this->finalizar('VERSÃO DO FRAMEWORK PHP INCOMPATÍVEL (VERSÃO ATUAL ' . VERSAO_INFRA . ', SENDO REQUERIDA VERSÃO IGUAL OU SUPERIOR A ' . $numVersaoInfraRequerida . ')', true);
            }

            //checando permissoes na base de dados
            $objInfraMetaBD = new InfraMetaBD(BancoSip::getInstance());

            if (count($objInfraMetaBD->obterTabelas('sip_teste')) == 0) {
                BancoSip::getInstance()->executarSql('CREATE TABLE sip_teste (id ' . $objInfraMetaBD->tipoNumero() . ' null)');
            }
            
            BancoSip::getInstance()->executarSql('DROP TABLE sip_teste');

            $objInfraParametro = new InfraParametro(BancoSip::getInstance());

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
                case '2.3.0':
                    $this->instalarv240();
                    break;

                default:
                    $this->finalizar('A VERSÃO MAIS ATUAL DO ' . $this->nomeDesteModulo . ' (v' . $this->versaoAtualDesteModulo . ') JÁ ESTÁ INSTALADA.');
                    break;

            }

            $this->finalizar('FIM');
            InfraDebug::getInstance()->setBolDebugInfra(true);
        } catch (Exception $e) {
            InfraDebug::getInstance()->setBolLigado(false);
            InfraDebug::getInstance()->setBolDebugInfra(false);
            InfraDebug::getInstance()->setBolEcho(false);
            throw new InfraException('Erro atualizando versão.', $e);
        }

    }

    protected function instalarv100()
    {

        $this->logar('EXECUTANDO A INSTALAÇÃO/ATUALIZAÇÃO DA VERSÃO 1.0.0 DO ' . $this->nomeDesteModulo . ' NA BASE DO SIP');

        $objSistemaRN = new SistemaRN();
        $objPerfilRN = new PerfilRN();
        $objMenuRN = new MenuRN();
        $objItemMenuRN = new ItemMenuRN();

        $objSistemaDTO = new SistemaDTO();
        $objSistemaDTO->retNumIdSistema();
        $objSistemaDTO->setStrSigla('SEI');

        $objSistemaDTO = $objSistemaRN->consultar($objSistemaDTO);

        if ($objSistemaDTO == null) {
            throw new InfraException('Sistema SEI não encontrado.');
        }

        $numIdSistemaSei = $objSistemaDTO->getNumIdSistema();

        $objPerfilDTO = new PerfilDTO();
        $objPerfilDTO->retNumIdPerfil();
        $objPerfilDTO->setNumIdSistema($numIdSistemaSei);
        $objPerfilDTO->setStrNome('Administrador');
        $objPerfilDTO = $objPerfilRN->consultar($objPerfilDTO);

        if ($objPerfilDTO == null) {
            throw new InfraException('Perfil Administrador do sistema SEI não encontrado.');
        }

        $numIdPerfilSeiAdministrador = $objPerfilDTO->getNumIdPerfil();

        $noPerfilExpedicao = 'Expedição Correios';
        $dsPerfilExpedicao = 'Acesso aos recursos específicos do Módulo de Correios no âmbito das Unidades de Expedição.';
        $objPerfilExpedicaoDTO = new PerfilDTO();
        $objPerfilExpedicaoDTO->retNumIdPerfil();
        $objPerfilExpedicaoDTO->setNumIdSistema($numIdSistemaSei);
        $objPerfilExpedicaoDTO->setStrNome($noPerfilExpedicao);
        $objPerfilExpedicaoDTO->setStrDescricao($dsPerfilExpedicao);
        $objPerfilExpedicaoDTO->setStrSinCoordenado('N');
        $objPerfilExpedicaoDTO->setStrSinAtivo('S');
        $objPerfilExpedicaoDTO->setStrSin2Fatores('N');
        $objPerfilRN->cadastrar($objPerfilExpedicaoDTO);

        $objPerfilDTO = new PerfilDTO();
        $objPerfilDTO->retNumIdPerfil();
        $objPerfilDTO->setNumIdSistema($numIdSistemaSei);
        $objPerfilDTO->setStrNome($noPerfilExpedicao);
        $objPerfilDTO = $objPerfilRN->consultar($objPerfilDTO);

        if ($objPerfilDTO == null) {
            throw new InfraException('Perfil Expedição Correios do sistema SEI não encontrado.');
        }

        $numIdPerfilSeiExpedicao = $objPerfilDTO->getNumIdPerfil();

        //Perfil Básico
        $objPerfilDTO = new PerfilDTO();
        $objPerfilDTO->retNumIdPerfil();
        $objPerfilDTO->setNumIdSistema($numIdSistemaSei);
        $objPerfilDTO->setStrNome('Básico');
        $objPerfilDTO = $objPerfilRN->consultar($objPerfilDTO);

        if ($objPerfilDTO == null) {
            throw new InfraException('Perfil Básico do sistema SEI não encontrado.');
        }

        $numIdPerfilSeiBasico = $objPerfilDTO->getNumIdPerfil();


        //Válidar menu - Principal
        $numIdMenuSei = $this->validarMenu('Principal', $numIdSistemaSei, $objMenuRN);

        //Válidar menu - Administração
        $numIdItemMenuSeiAdministracao = $this->validarMenu('Administração', $numIdSistemaSei, $objItemMenuRN);

        //Válidar menu - Relatórios
        $numIdItemMenuSeiRelatorios = $this->validarMenu('Relatórios', $numIdSistemaSei, $objItemMenuRN);


        $this->logar('ATUALIZANDO RECURSOS, MENUS E PERFIS DO ' . $this->nomeDesteModulo . ' NA BASE DO SIP...');

        //SEI ////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
        $this->logar('ATUALIZANDO RECURSOS, MENUS E PERFIS DO ' . $this->nomeDesteModulo . ' NA BASE DO SIP...');

        $this->logar('CRIANDO e VINCULANDO RECURSO DE MENU A PERFIL - ADMINISTRAÇÃO / CORREIOS');
        $objItemMenuDTOCorreio = $this->adicionarItemMenu($numIdSistemaSei, $numIdPerfilSeiAdministrador, $numIdMenuSei, $numIdItemMenuSeiAdministracao, null, 'Correios', 0);

        $this->logar('CRIANDO e VINCULANDO RECURSO DE MENU A PERFIL - Contratos e Serviços Postais EM Administrador');
        $this->adicionarRecursoPerfil($numIdSistemaSei, $numIdPerfilSeiAdministrador, 'md_cor_contrato_desativar');
        $this->adicionarRecursoPerfil($numIdSistemaSei, $numIdPerfilSeiAdministrador, 'md_cor_contrato_reativar');
        $this->adicionarRecursoPerfil($numIdSistemaSei, $numIdPerfilSeiAdministrador, 'md_cor_contrato_excluir');
        $this->adicionarRecursoPerfil($numIdSistemaSei, $numIdPerfilSeiAdministrador, 'md_cor_contrato_cadastrar');
        $this->adicionarRecursoPerfil($numIdSistemaSei, $numIdPerfilSeiAdministrador, 'md_cor_contrato_consultar');
        $this->adicionarRecursoPerfil($numIdSistemaSei, $numIdPerfilSeiAdministrador, 'md_cor_contrato_alterar');
        $this->adicionarRecursoPerfil($numIdSistemaSei, $numIdPerfilSeiAdministrador, 'md_cor_contrato_selecionar');
        $this->adicionarRecursoPerfil($numIdSistemaSei, $numIdPerfilSeiAdministrador, 'md_cor_objeto_alterar');
        $this->adicionarRecursoPerfil($numIdSistemaSei, $numIdPerfilSeiAdministrador, 'md_cor_objeto_cadastrar');
        $this->adicionarRecursoPerfil($numIdSistemaSei, $numIdPerfilSeiAdministrador, 'md_cor_objeto_excluir');
        $this->adicionarRecursoPerfil($numIdSistemaSei, $numIdPerfilSeiAdministrador, 'md_cor_objeto_selecionar');
        $this->adicionarRecursoPerfil($numIdSistemaSei, $numIdPerfilSeiAdministrador, 'md_cor_objeto_excluir');
        $this->adicionarRecursoPerfil($numIdSistemaSei, $numIdPerfilSeiBasico, 'md_cor_objeto_listar');
        $this->adicionarRecursoPerfil($numIdSistemaSei, $numIdPerfilSeiExpedicao, 'md_cor_objeto_listar');
        $this->adicionarRecursoPerfil($numIdSistemaSei, $numIdPerfilSeiBasico, 'md_cor_objeto_consultar');
        $this->adicionarRecursoPerfil($numIdSistemaSei, $numIdPerfilSeiExpedicao, 'md_cor_objeto_consultar');
        $this->adicionarRecursoPerfil($numIdSistemaSei, $numIdPerfilSeiAdministrador, 'md_cor_diretoria_listar');
        $this->adicionarRecursoPerfil($numIdSistemaSei, $numIdPerfilSeiAdministrador, 'md_cor_tipo_objeto_listar');
        $this->adicionarRecursoPerfil($numIdSistemaSei, $numIdPerfilSeiAdministrador, 'md_cor_tipo_objeto_consultar');
        $objRecursoComMenuDTO2 = $this->adicionarRecursoPerfil($numIdSistemaSei, $numIdPerfilSeiAdministrador, 'md_cor_contrato_listar');
        $this->adicionarItemMenu($numIdSistemaSei,
            $numIdPerfilSeiAdministrador,
            $numIdMenuSei,
            $objItemMenuDTOCorreio->getNumIdItemMenu(),
            $objRecursoComMenuDTO2->getNumIdRecurso(),
            'Contratos e Serviços Postais',
            10);

        $this->logar('CRIANDO e VINCULANDO RECURSO DE MENU A PERFIL - Tipos de Documentos de Expedição EM Administrador');
        $this->adicionarRecursoPerfil($numIdSistemaSei, $numIdPerfilSeiBasico, 'md_cor_serie_exp_listar');
        $this->adicionarRecursoPerfil($numIdSistemaSei, $numIdPerfilSeiAdministrador, 'md_cor_serie_exp_excluir');
        $this->adicionarRecursoPerfil($numIdSistemaSei, $numIdPerfilSeiAdministrador, 'md_cor_serie_exp_selecionar');
        $objRecursoComMenuDTO1 = $this->adicionarRecursoPerfil($numIdSistemaSei, $numIdPerfilSeiAdministrador, 'md_cor_serie_exp_cadastrar');
        $this->adicionarItemMenu($numIdSistemaSei,
            $numIdPerfilSeiAdministrador,
            $numIdMenuSei,
            $objItemMenuDTOCorreio->getNumIdItemMenu(),
            $objRecursoComMenuDTO1->getNumIdRecurso(),
            'Tipos de Documentos de Expedição',
            20);

        $this->logar('CRIANDO e VINCULANDO RECURSO DE MENU A PERFIL - Unidades Expedidoras EM Administrador');
        $this->adicionarRecursoPerfil($numIdSistemaSei, $numIdPerfilSeiAdministrador, 'md_cor_unidade_selecionar_todas');
        $this->adicionarRecursoPerfil($numIdSistemaSei, $numIdPerfilSeiAdministrador, 'md_cor_unidade_exp_consultar');
        $this->adicionarRecursoPerfil($numIdSistemaSei, $numIdPerfilSeiExpedicao, 'md_cor_unidade_exp_consultar');
        $objRecursoComMenuDTO3 = $this->adicionarRecursoPerfil($numIdSistemaSei, $numIdPerfilSeiAdministrador, 'md_cor_unidade_exp_cadastrar');
        $this->adicionarItemMenu($numIdSistemaSei,
            $numIdPerfilSeiAdministrador,
            $numIdMenuSei,
            $objItemMenuDTOCorreio->getNumIdItemMenu(),
            $objRecursoComMenuDTO3->getNumIdRecurso(),
            'Unidades Expedidoras',
            30);

        $this->logar('CRIANDO e VINCULANDO RECURSO DE MENU A PERFIL - Mapeamento Unidades Expedidoras e Solicitantes EM Administrador');
        $this->adicionarRecursoPerfil($numIdSistemaSei, $numIdPerfilSeiAdministrador, 'md_cor_mapeamento_uni_exp_sol_desativar');
        $this->adicionarRecursoPerfil($numIdSistemaSei, $numIdPerfilSeiAdministrador, 'md_cor_mapeamento_uni_exp_sol_reativar');
        $this->adicionarRecursoPerfil($numIdSistemaSei, $numIdPerfilSeiAdministrador, 'md_cor_mapeamento_uni_exp_sol_excluir');
        $this->adicionarRecursoPerfil($numIdSistemaSei, $numIdPerfilSeiAdministrador, 'md_cor_mapeamento_uni_exp_sol_cadastrar');
        $this->adicionarRecursoPerfil($numIdSistemaSei, $numIdPerfilSeiAdministrador, 'md_cor_mapeamento_uni_exp_sol_consultar');
        $this->adicionarRecursoPerfil($numIdSistemaSei, $numIdPerfilSeiAdministrador, 'md_cor_mapeamento_uni_exp_sol_alterar');
        $this->adicionarRecursoPerfil($numIdSistemaSei, $numIdPerfilSeiAdministrador, 'md_cor_mapeamento_uni_exp_sol_selecionar');
        $this->adicionarRecursoPerfil($numIdSistemaSei, $numIdPerfilSeiBasico, 'md_cor_mapeamento_uni_exp_sol_listar');
        $objRecursoComMenuDTO4 = $this->adicionarRecursoPerfil($numIdSistemaSei, $numIdPerfilSeiAdministrador, 'md_cor_mapeamento_uni_exp_sol_listar');
        $this->adicionarItemMenu($numIdSistemaSei,
            $numIdPerfilSeiAdministrador,
            $numIdMenuSei,
            $objItemMenuDTOCorreio->getNumIdItemMenu(),
            $objRecursoComMenuDTO4->getNumIdRecurso(),
            'Mapeamento Unidades Expedidoras e Solicitantes',
            40);

        $this->logar('CRIANDO e VINCULANDO RECURSO DE MENU A PERFIL - Mapeamento Unidades e Serviços Postais EM Administrador');
        $this->adicionarRecursoPerfil($numIdSistemaSei, $numIdPerfilSeiAdministrador, 'md_cor_servico_postal_desativar');
        $this->adicionarRecursoPerfil($numIdSistemaSei, $numIdPerfilSeiAdministrador, 'md_cor_servico_postal_reativar');
        $this->adicionarRecursoPerfil($numIdSistemaSei, $numIdPerfilSeiAdministrador, 'md_cor_servico_postal_excluir');
        $this->adicionarRecursoPerfil($numIdSistemaSei, $numIdPerfilSeiAdministrador, 'md_cor_servico_postal_cadastrar');
        $this->adicionarRecursoPerfil($numIdSistemaSei, $numIdPerfilSeiBasico, 'md_cor_servico_postal_consultar');
        $this->adicionarRecursoPerfil($numIdSistemaSei, $numIdPerfilSeiBasico, 'md_cor_servico_postal_listar');
        $this->adicionarRecursoPerfil($numIdSistemaSei, $numIdPerfilSeiAdministrador, 'md_cor_servico_postal_alterar');
        $this->adicionarRecursoPerfil($numIdSistemaSei, $numIdPerfilSeiAdministrador, 'md_cor_servico_postal_selecionar');
        $this->adicionarRecursoPerfil($numIdSistemaSei, $numIdPerfilSeiAdministrador, 'md_cor_map_unid_servico_desativar');
        $this->adicionarRecursoPerfil($numIdSistemaSei, $numIdPerfilSeiAdministrador, 'md_cor_map_unid_servico_reativar');
        $this->adicionarRecursoPerfil($numIdSistemaSei, $numIdPerfilSeiAdministrador, 'md_cor_map_unid_servico_excluir');
        $this->adicionarRecursoPerfil($numIdSistemaSei, $numIdPerfilSeiAdministrador, 'md_cor_map_unid_servico_cadastrar');
        $this->adicionarRecursoPerfil($numIdSistemaSei, $numIdPerfilSeiAdministrador, 'md_cor_map_unid_servico_consultar');
        $this->adicionarRecursoPerfil($numIdSistemaSei, $numIdPerfilSeiAdministrador, 'md_cor_map_unid_servico_alterar');
        $this->adicionarRecursoPerfil($numIdSistemaSei, $numIdPerfilSeiAdministrador, 'md_cor_map_unid_servico_selecionar');
        $this->adicionarRecursoPerfil($numIdSistemaSei, $numIdPerfilSeiBasico, 'md_cor_map_unid_servico_listar');
        $this->adicionarRecursoPerfil($numIdSistemaSei, $numIdPerfilSeiExpedicao, 'md_cor_map_unid_servico_listar');
        $objRecursoComMenuDTO6 = $this->adicionarRecursoPerfil($numIdSistemaSei, $numIdPerfilSeiAdministrador, 'md_cor_map_unid_servico_listar');
        $this->adicionarItemMenu($numIdSistemaSei,
            $numIdPerfilSeiAdministrador,
            $numIdMenuSei,
            $objItemMenuDTOCorreio->getNumIdItemMenu(),
            $objRecursoComMenuDTO6->getNumIdRecurso(),
            'Mapeamento Unidades e Serviços Postais',
            50);

        $this->logar('CRIANDO e VINCULANDO RECURSO DE MENU A PERFIL - Extensões para Gravação em Mídia EM Administrador');
        $this->adicionarRecursoPerfil($numIdSistemaSei, $numIdPerfilSeiAdministrador, 'md_cor_extensao_arquivo_selecionar');
        $this->adicionarRecursoPerfil($numIdSistemaSei, $numIdPerfilSeiBasico, 'md_cor_extensao_midia_listar');
        $this->adicionarRecursoPerfil($numIdSistemaSei, $numIdPerfilSeiExpedicao, 'md_cor_extensao_midia_listar');
        $this->adicionarRecursoPerfil($numIdSistemaSei, $numIdPerfilSeiAdministrador, 'md_cor_extensao_midia_alterar');
        $this->adicionarRecursoPerfil($numIdSistemaSei, $numIdPerfilSeiAdministrador, 'md_cor_extensao_midia_excluir');
        $this->adicionarRecursoPerfil($numIdSistemaSei, $numIdPerfilSeiAdministrador, 'md_cor_extensao_midia_consultar');
        $objRecursoComMenuDTO5 = $this->adicionarRecursoPerfil($numIdSistemaSei, $numIdPerfilSeiAdministrador, 'md_cor_extensao_midia_cadastrar');
        $this->adicionarItemMenu($numIdSistemaSei,
            $numIdPerfilSeiAdministrador,
            $numIdMenuSei,
            $objItemMenuDTOCorreio->getNumIdItemMenu(),
            $objRecursoComMenuDTO5->getNumIdRecurso(),
            'Extensões para Gravação em Mídia',
            60);

        $this->logar('CRIANDO e VINCULANDO RECURSO DE MENU A PERFIL - Parâmetros para Retorno de AR EM Administrador');
        $this->adicionarRecursoPerfil($numIdSistemaSei, $numIdPerfilSeiBasico, 'md_cor_parametro_ar_cadastrar');
        $this->adicionarRecursoPerfil($numIdSistemaSei, $numIdPerfilSeiExpedicao, 'md_cor_parametro_ar_cadastrar');
        $objRecursoDTO18881 = $this->adicionarRecursoPerfil($numIdSistemaSei, $numIdPerfilSeiAdministrador, 'md_cor_parametro_ar_cadastrar');
        $this->adicionarItemMenu($numIdSistemaSei,
            $numIdPerfilSeiAdministrador,
            $numIdMenuSei,
            $objItemMenuDTOCorreio->getNumIdItemMenu(),
            $objRecursoDTO18881->getNumIdRecurso(),
            'Parâmetros para Retorno de AR',
            70);


        $this->logar('CRIANDO e VINCULANDO RECURSO DE MENU A PERFIL - Integração SRO EM Admnistrador');
        $this->adicionarRecursoPerfil($numIdSistemaSei, $numIdPerfilSeiAdministrador, 'md_cor_parametro_rastreio_alterar');
        $this->adicionarRecursoPerfil($numIdSistemaSei, $numIdPerfilSeiBasico, 'md_cor_parametro_rastreio_consultar');
        $this->adicionarRecursoPerfil($numIdSistemaSei, $numIdPerfilSeiAdministrador, 'md_cor_parametro_rastreio_listar');
        $this->adicionarRecursoPerfil($numIdSistemaSei, $numIdPerfilSeiAdministrador, 'md_cor_parametro_rastreio_cadastrar');
        $objRecursoComMenuDTO7 = $this->adicionarRecursoPerfil($numIdSistemaSei, $numIdPerfilSeiAdministrador, 'md_cor_parametrizacao_rastreio_listar');
        $this->adicionarItemMenu($numIdSistemaSei,
            $numIdPerfilSeiAdministrador,
            $numIdMenuSei,
            $objItemMenuDTOCorreio->getNumIdItemMenu(),
            $objRecursoComMenuDTO7->getNumIdRecurso(),
            'Integração SRO',
            80);


        $this->logar('CRIANDO e VINCULANDO RECURSO DE MENU A PERFIL - Tipos de Situações SRO EM Administrador');
        $this->adicionarRecursoPerfil($numIdSistemaSei, $numIdPerfilSeiAdministrador, 'md_cor_parametrizacao_status_alterar');
        $this->adicionarRecursoPerfil($numIdSistemaSei, $numIdPerfilSeiAdministrador, 'md_cor_parametrizacao_status_desativar');
        $this->adicionarRecursoPerfil($numIdSistemaSei, $numIdPerfilSeiAdministrador, 'md_cor_parametrizacao_status_reativar');
        $this->adicionarRecursoPerfil($numIdSistemaSei, $numIdPerfilSeiAdministrador, 'md_cor_lista_status_alterar');
        $this->adicionarRecursoPerfil($numIdSistemaSei, $numIdPerfilSeiAdministrador, 'md_cor_lista_status_reativar');
        $this->adicionarRecursoPerfil($numIdSistemaSei, $numIdPerfilSeiAdministrador, 'md_cor_lista_status_listar');
        $this->adicionarRecursoPerfil($numIdSistemaSei, $numIdPerfilSeiAdministrador, 'md_cor_lista_status_desativar');
        $objRecursoComMenuDT11 = $this->adicionarRecursoPerfil($numIdSistemaSei, $numIdPerfilSeiAdministrador, 'md_cor_parametrizacao_status_listar');
        $this->adicionarItemMenu($numIdSistemaSei,
            $numIdPerfilSeiAdministrador,
            $numIdMenuSei,
            $objItemMenuDTOCorreio->getNumIdItemMenu(),
            $objRecursoComMenuDT11->getNumIdRecurso(),
            'Tipos de Situações SRO',
            90);


        $this->logar('CRIANDO e VINCULANDO RECURSO DE MENU A PERFIL - Expedição pelos Correios EM Expedição Correios');
        $objItemMenuDTOExpedicaoCorreio = $this->adicionarItemMenu($numIdSistemaSei
            , $numIdPerfilSeiExpedicao
            , $numIdMenuSei
            , null
            , null
            , 'Expedição pelos Correios'
            , 32);

        $this->logar('CRIANDO e VINCULANDO RECURSO DE MENU A PERFIL - Gerar PLP - EXPEDIÇÃO CORREIOS');
        $objRecursoComMenuDTO8 = $this->adicionarRecursoPerfil($numIdSistemaSei, $numIdPerfilSeiExpedicao, 'md_cor_geracao_plp_listar');
        $this->adicionarItemMenu($numIdSistemaSei,
            $numIdPerfilSeiExpedicao,
            $numIdMenuSei,
            $objItemMenuDTOExpedicaoCorreio->getNumIdItemMenu(),
            $objRecursoComMenuDTO8->getNumIdRecurso(),
            'Gerar PLP',
            10);

        $this->logar('CRIANDO e VINCULANDO RECURSO DE MENU A PERFIL - Expedir PLP - EXPEDIÇÃO CORREIOS');
        $this->adicionarRecursoPerfil($numIdSistemaSei, $numIdPerfilSeiExpedicao, 'md_cor_plp_correio_cadastro');
        $this->adicionarRecursoPerfil($numIdSistemaSei, $numIdPerfilSeiExpedicao, 'md_cor_plp_cadastrar');
        $this->adicionarRecursoPerfil($numIdSistemaSei, $numIdPerfilSeiBasico, 'md_cor_expedicao_solicitada_alterar');
        $this->adicionarRecursoPerfil($numIdSistemaSei, $numIdPerfilSeiExpedicao, 'md_cor_expedicao_solicitada_alterar');
        $this->adicionarRecursoPerfil($numIdSistemaSei, $numIdPerfilSeiExpedicao, 'md_cor_plp_listar');
        $this->adicionarRecursoPerfil($numIdSistemaSei, $numIdPerfilSeiExpedicao, 'md_cor_plp_expedir');
        $this->adicionarRecursoPerfil($numIdSistemaSei, $numIdPerfilSeiExpedicao, 'md_cor_plp_consultar');
        $this->adicionarRecursoPerfil($numIdSistemaSei, $numIdPerfilSeiExpedicao, 'md_cor_plp_expedir_objeto');
        $this->adicionarRecursoPerfil($numIdSistemaSei, $numIdPerfilSeiExpedicao, 'md_cor_plp_gerar_zip');
        $this->adicionarRecursoPerfil($numIdSistemaSei, $numIdPerfilSeiExpedicao, 'md_cor_plp_pdf_documento_principal');
        $this->adicionarRecursoPerfil($numIdSistemaSei, $numIdPerfilSeiExpedicao, 'md_cor_exibir_pdf');
        $this->adicionarRecursoPerfil($numIdSistemaSei, $numIdPerfilSeiExpedicao, 'md_cor_plp_pdf_arquivo_lote_objeto');
        $this->adicionarRecursoPerfil($numIdSistemaSei, $numIdPerfilSeiExpedicao, 'md_cor_plp_webservice_acessar');
        $this->adicionarRecursoPerfil($numIdSistemaSei, $numIdPerfilSeiExpedicao, 'md_cor_plp_concluir');
        $this->adicionarRecursoPerfil($numIdSistemaSei, $numIdPerfilSeiExpedicao, 'md_cor_plp_alterar');
        $this->adicionarRecursoPerfil($numIdSistemaSei, $numIdPerfilSeiExpedicao, 'md_cor_plp_imprimir_ar');
        $this->adicionarRecursoPerfil($numIdSistemaSei, $numIdPerfilSeiExpedicao, 'md_cor_plp_detalhada');
        $this->adicionarRecursoPerfil($numIdSistemaSei, $numIdPerfilSeiExpedicao, 'md_cor_plp_consultar');
        $this->adicionarRecursoPerfil($numIdSistemaSei, $numIdPerfilSeiExpedicao, 'md_cor_plp_detalhar_objeto');
        $this->adicionarRecursoPerfil($numIdSistemaSei, $numIdPerfilSeiExpedicao, 'md_cor_plp_imprimir_voucher');
        $this->adicionarRecursoPerfil($numIdSistemaSei, $numIdPerfilSeiExpedicao, 'md_cor_plp_imprimir_rotulo_envelope');
        $this->adicionarRecursoPerfil($numIdSistemaSei, $numIdPerfilSeiExpedicao, 'md_cor_expedicao_plp_listar');
        $this->adicionarRecursoPerfil($numIdSistemaSei, $numIdPerfilSeiBasico, 'md_cor_expedicao_anexo_listar');
        $this->adicionarRecursoPerfil($numIdSistemaSei, $numIdPerfilSeiBasico, 'md_cor_expedicao_solicitada_excluir');
        $this->adicionarRecursoPerfil($numIdSistemaSei, $numIdPerfilSeiExpedicao, 'md_cor_plp_pdf_arquivo_lote');
        $this->adicionarRecursoPerfil($numIdSistemaSei, $numIdPerfilSeiExpedicao, 'md_cor_plp_selecionar_tipo_objeto');
        $objRecursoComMenuDT10 = $this->adicionarRecursoPerfil($numIdSistemaSei, $numIdPerfilSeiExpedicao, 'md_cor_expedicao_plp_listar');
        $this->adicionarItemMenu($numIdSistemaSei,
            $numIdPerfilSeiExpedicao,
            $numIdMenuSei,
            $objItemMenuDTOExpedicaoCorreio->getNumIdItemMenu(),
            $objRecursoComMenuDT10->getNumIdRecurso(),
            'Expedir PLP',
            20);
			
		$this->logar('CRIANDO e VINCULANDO RECURSO DE MENU A PERFIL - Relátorios / Correios / Consultar PLPs Geradas EM Expedição Correios');
        $objRecursoComMenuDTO9 = $this->adicionarRecursoPerfil($numIdSistemaSei, $numIdPerfilSeiExpedicao, 'md_cor_plps_geradas_listar');
        $this->adicionarItemMenu($numIdSistemaSei,
            $numIdPerfilSeiExpedicao,
            $numIdMenuSei,
            $objItemMenuDTOExpedicaoCorreio->getNumIdItemMenu(),
            $objRecursoComMenuDTO9->getNumIdRecurso(),
            'Consultar PLPs Geradas',
            30);	

        $this->logar('CRIANDO e VINCULANDO RECURSO DE MENU A PERFIL - Processamento de Retorno de AR EM Expedição Correios');
        $this->adicionarRecursoPerfil($numIdSistemaSei, $numIdPerfilSeiExpedicao, 'md_cor_retorno_ar_resumir');
        $this->adicionarRecursoPerfil($numIdSistemaSei, $numIdPerfilSeiExpedicao, 'md_cor_resumo_processamento');
        $this->adicionarRecursoPerfil($numIdSistemaSei, $numIdPerfilSeiExpedicao, 'md_cor_retorno_ar_autenticar');
        $this->adicionarRecursoPerfil($numIdSistemaSei, $numIdPerfilSeiExpedicao, 'md_cor_retorno_ar_consultar');
        $this->adicionarRecursoPerfil($numIdSistemaSei, $numIdPerfilSeiExpedicao, 'md_cor_retorno_ar_alterar');
        $this->adicionarRecursoPerfil($numIdSistemaSei, $numIdPerfilSeiExpedicao, 'md_cor_retorno_ar_cadastrar');
        $this->adicionarRecursoPerfil($numIdSistemaSei, $numIdPerfilSeiExpedicao, 'md_cor_retorno_ar_processar');
        $this->adicionarRecursoPerfil($numIdSistemaSei, $numIdPerfilSeiExpedicao, 'md_cor_retorno_ar_salvar');
        $this->adicionarRecursoPerfil($numIdSistemaSei, $numIdPerfilSeiExpedicao, 'md_cor_retorno_ar_arquivo_mostrar');
        $this->adicionarRecursoPerfil($numIdSistemaSei, $numIdPerfilSeiExpedicao, 'md_cor_upload_zip_ar');
        $objRecursoDTO9373 = $this->adicionarRecursoPerfil($numIdSistemaSei, $numIdPerfilSeiExpedicao, 'md_cor_retorno_ar_listar');
        $this->adicionarItemMenu($numIdSistemaSei,
            $numIdPerfilSeiExpedicao,
            $numIdMenuSei,
            $objItemMenuDTOExpedicaoCorreio->getNumIdItemMenu(),
            $objRecursoDTO9373->getNumIdRecurso(),
            'Processamento de Retorno de AR',
            40);

        $this->logar('CRIANDO e VINCULANDO RECURSO DE MENU A PERFIL - ARs Pendentes de Retorno EM Expedição Correios');
        $objRecursoDTO18882 = $this->adicionarRecursoPerfil($numIdSistemaSei, $numIdPerfilSeiExpedicao, 'md_cor_ar_pendente_listar');
        $this->adicionarItemMenu($numIdSistemaSei,
            $numIdPerfilSeiExpedicao,
            $numIdMenuSei,
            $objItemMenuDTOExpedicaoCorreio->getNumIdItemMenu(),
            $objRecursoDTO18882->getNumIdRecurso(),
            'ARs Pendentes de Retorno',
            50);

        $this->logar('CRIANDO e VINCULANDO RECURSO DE MENU A PERFIL - Relátorios / Correios EM Básico');
        $objItemMenuDTORelatorioCorreio = $this->adicionarItemMenu($numIdSistemaSei, $numIdPerfilSeiBasico, $numIdMenuSei, $numIdItemMenuSeiRelatorios, null, 'Correios', 40);
        

        $this->logar('CRIANDO e VINCULANDO RECURSO DE MENU A PERFIL - Relátorios / Correios / Expedições Solicitadas pela Unidade EM Básico');
        $objRecursoComMenuDTO7 = $this->adicionarRecursoPerfil($numIdSistemaSei, $numIdPerfilSeiBasico, 'md_cor_expedicao_unidade_listar');
        $this->adicionarItemMenu($numIdSistemaSei,
            $numIdPerfilSeiBasico,
            $numIdMenuSei,
            $objItemMenuDTORelatorioCorreio->getNumIdItemMenu(),
            $objRecursoComMenuDTO7->getNumIdRecurso(),
            'Expedições Solicitadas pela Unidade',
            10);


        $this->logar('CRIANDO e VINCULANDO RECURSO AO PERFIL BASICO');
        $this->adicionarRecursoPerfil($numIdSistemaSei, $numIdPerfilSeiBasico, 'md_cor_expedicao_solicitada_cadastrar');
        $this->adicionarRecursoPerfil($numIdSistemaSei, $numIdPerfilSeiBasico, 'md_cor_expedicao_cadastro_protocolos_selecionar');
        $this->adicionarRecursoPerfil($numIdSistemaSei, $numIdPerfilSeiBasico, 'md_cor_expedicao_processo_listar');
        $this->adicionarRecursoPerfil($numIdSistemaSei, $numIdPerfilSeiAdministrador, 'md_cor_expedicao_andamento_listar');
        $this->adicionarRecursoPerfil($numIdSistemaSei, $numIdPerfilSeiBasico, 'md_cor_expedicao_solicitada_consultar');
        $this->adicionarRecursoPerfil($numIdSistemaSei, $numIdPerfilSeiExpedicao, 'md_cor_expedicao_solicitada_consultar');
        $this->adicionarRecursoPerfil($numIdSistemaSei, $numIdPerfilSeiAdministrador, 'md_cor_lista_status_cadastrar');
        $this->adicionarRecursoPerfil($numIdSistemaSei, $numIdPerfilSeiBasico, 'md_cor_lista_status_consultar');
        $this->adicionarRecursoPerfil($numIdSistemaSei, $numIdPerfilSeiBasico, 'md_cor_expedicao_detalhar_rastreio');
        $this->adicionarRecursoPerfil($numIdSistemaSei, $numIdPerfilSeiExpedicao, 'md_cor_expedicao_detalhar_rastreio');
        $this->adicionarRecursoPerfil($numIdSistemaSei, $numIdPerfilSeiBasico, 'md_cor_expedicao_solicitada_listar');
        $this->adicionarRecursoPerfil($numIdSistemaSei, $numIdPerfilSeiExpedicao, 'md_cor_expedicao_solicitada_listar');
        $this->adicionarRecursoPerfil($numIdSistemaSei, $numIdPerfilSeiExpedicao, 'md_cor_expedicao_unidade_listar');
        $this->adicionarRecursoPerfil($numIdSistemaSei, $numIdPerfilSeiBasico, 'md_cor_expedicao_andamento_consultar');
        $this->adicionarRecursoPerfil($numIdSistemaSei, $numIdPerfilSeiBasico, 'md_cor_expedicao_andamento_listar');
        $this->adicionarRecursoPerfil($numIdSistemaSei, $numIdPerfilSeiBasico, 'md_cor_expedicao_andamento_cadastrar');
        $this->adicionarRecursoPerfil($numIdSistemaSei, $numIdPerfilSeiBasico, 'md_cor_contato_listar');
        $this->adicionarRecursoPerfil($numIdSistemaSei, $numIdPerfilSeiExpedicao, 'md_cor_contato_listar');

        //novo grupo de regra de auditoria nova
        $objRegraAuditoriaDTO = new RegraAuditoriaDTO();
        $objRegraAuditoriaDTO->retNumIdRegraAuditoria();
        $objRegraAuditoriaDTO->setNumIdRegraAuditoria(null);
        $objRegraAuditoriaDTO->setStrSinAtivo('S');
        $objRegraAuditoriaDTO->setNumIdSistema($numIdSistemaSei);
        $objRegraAuditoriaDTO->setArrObjRelRegraAuditoriaRecursoDTO(array());
        $objRegraAuditoriaDTO->setStrDescricao('Modulo_Correio');

        $objRegraAuditoriaRN = new RegraAuditoriaRN();
        $objRegraAuditoriaDTO = $objRegraAuditoriaRN->cadastrar($objRegraAuditoriaDTO);

        $rs = BancoSip::getInstance()->consultarSql('select id_recurso from recurso where id_sistema=' . $numIdSistemaSei . ' and nome in (
        \'md_cor_serie_exp_cadastrar\',
         \'md_cor_unidade_exp_cadastrar\',
         \'md_cor_extensao_midia_cadastrar\',
         \'md_cor_mapeamento_uni_exp_sol_desativar\',
         \'md_cor_mapeamento_uni_exp_sol_reativar\',
         \'md_cor_mapeamento_uni_exp_sol_excluir\',
         \'md_cor_mapeamento_uni_exp_sol_cadastrar\',
         \'md_cor_mapeamento_uni_exp_sol_alterar\',
         \'md_cor_map_unid_servico_desativar\',
         \'md_cor_map_unid_servico_reativar\',
         \'md_cor_map_unid_servico_excluir\',
         \'md_cor_map_unid_servico_cadastrar\',
         \'md_cor_map_unid_servico_alterar\',
         \'md_cor_extensao_midia_alterar\',
         \'md_cor_contrato_desativar\',
         \'md_cor_contrato_reativar\',
         \'md_cor_contrato_excluir\',
         \'md_cor_contrato_cadastrar\',
         \'md_cor_contrato_alterar\')');

        //Válidar menu módulo - Correios
        $this->validarMenu('Correios', $numIdSistemaSei, $objItemMenuRN, $numIdItemMenuSeiAdministracao);


        //CRIANDO REGRA DE AUDITORIA PARA NOVOS RECURSOS RECEM ADICIONADOS

        foreach ($rs as $recurso) {
            BancoSip::getInstance()->executarSql('insert into rel_regra_auditoria_recurso (id_regra_auditoria, id_sistema, id_recurso) values (' . $objRegraAuditoriaDTO->getNumIdRegraAuditoria() . ', ' . $numIdSistemaSei . ', ' . $recurso['id_recurso'] . ')');
        }

        $objReplicacaoRegraAuditoriaDTO = new ReplicacaoRegraAuditoriaDTO();
        $objReplicacaoRegraAuditoriaDTO->setStrStaOperacao('A');
        $objReplicacaoRegraAuditoriaDTO->setNumIdRegraAuditoria($objRegraAuditoriaDTO->getNumIdRegraAuditoria());

        $objSistemaRN = new SistemaRN();
        $objSistemaRN->replicarRegraAuditoria($objReplicacaoRegraAuditoriaDTO);

        $objSistemaRN = new SistemaRN();

        $objSistemaDTO = new SistemaDTO();
        $objSistemaDTO->retNumIdSistema();
        $objSistemaDTO->setStrSigla('SEI');

        $objSistemaDTO = $objSistemaRN->consultar($objSistemaDTO);

        if ($objSistemaDTO == null) {
            throw new InfraException('Sistema SEI não encontrado.');
        }

        $this->logar('CRIANDO e VINCULANDO RECURSOS  - COBRANÇA - EXPEDICAO CORREIOS');
        $numIdPerfilSeiExpedicao = $objPerfilDTO->getNumIdPerfil();
        $this->adicionarRecursoPerfil($numIdSistemaSei, $numIdPerfilSeiExpedicao, 'md_cor_cobranca_gerar');
        $this->adicionarRecursoPerfil($numIdSistemaSei, $numIdPerfilSeiExpedicao, 'md_cor_cobranca_informar');
        $this->adicionarRecursoPerfil($numIdSistemaSei, $numIdPerfilSeiExpedicao, 'md_cor_historico_visualizar');

        $objSistemaRN = new SistemaRN();

        $objSistemaDTO = new SistemaDTO();
        $objSistemaDTO->retNumIdSistema();
        $objSistemaDTO->setStrSigla('SEI');

        $objSistemaDTO = $objSistemaRN->consultar($objSistemaDTO);

        if ($objSistemaDTO == null) {
            throw new InfraException('Sistema SEI não encontrado.');
        }

        $arr = $this->getArrNumIdSei();
        $numIdPerfilSeiAdmin = $arr['numIdPerfilSei'];
        $numIdSistemaSei = $arr['numIdSistemaSei'];

        //Válidar menu - Principal
        $objMenuRN = new MenuRN();
        $numIdMenuSei = $this->validarMenu('Principal', $numIdSistemaSei, $objMenuRN);

        //Válidar menu - Administração
        $objItemMenuRN = new ItemMenuRN();
        $numIdItemMenuSeiAdministracao = $this->validarMenu('Administração', $numIdSistemaSei, $objItemMenuRN);

        $this->logar('CRIANDO e VINCULANDO RECURSO DE MENU A PERFIL - ADMINISTRAÇÃO / CORREIOS');
        $objItemMenuDTOCorreio = $this->adicionarItemMenu($numIdSistemaSei, $numIdPerfilSeiAdmin, $numIdMenuSei, $numIdItemMenuSeiAdministracao, null, 'Correios', 0);

        $this->logar('CRIANDO e VINCULANDO RECURSO DE MENU A PERFIL - Destinatários não Habilitados para Expedição EM Administrador');
        $this->adicionarRecursoPerfil($numIdSistemaSei, $numIdPerfilSeiAdmin, 'md_cor_justificativa_listar');
        $this->adicionarRecursoPerfil($numIdSistemaSei, $numIdPerfilSeiAdmin, 'md_cor_justificativa_excluir');
        $this->adicionarRecursoPerfil($numIdSistemaSei, $numIdPerfilSeiAdmin, 'md_cor_justificativa_desativar');
        $this->adicionarRecursoPerfil($numIdSistemaSei, $numIdPerfilSeiAdmin, 'md_cor_justificativa_reativar');
        $this->adicionarRecursoPerfil($numIdSistemaSei, $numIdPerfilSeiAdmin, 'md_cor_justificativa_cadastrar');
        $this->adicionarRecursoPerfil($numIdSistemaSei, $numIdPerfilSeiAdmin, 'md_cor_justificativa_alterar');
        $this->adicionarRecursoPerfil($numIdSistemaSei, $numIdPerfilSeiAdmin, 'md_cor_rel_contato_justificativa_excluir');
        $this->adicionarRecursoPerfil($numIdSistemaSei, $numIdPerfilSeiAdmin, 'md_cor_rel_contato_justificativa_cadastrar');
        $this->adicionarRecursoPerfil($numIdSistemaSei, $numIdPerfilSeiAdmin, 'md_cor_rel_contato_justificativa_alterar');

        $objRecurso = $this->adicionarRecursoPerfil($numIdSistemaSei, $numIdPerfilSeiAdmin, 'md_cor_rel_contato_justificativa_listar');

        $this->adicionarItemMenu($numIdSistemaSei, $numIdPerfilSeiAdmin, $numIdMenuSei, $objItemMenuDTOCorreio->getNumIdItemMenu(), $objRecurso->getNumIdRecurso(),
            'Destinatários não Habilitados para Expedição', 100);

        $this->logar('ADICIONANDO PARÂMETRO ' . $this->nomeParametroModulo . ' NA TABELA infra_parametro PARA CONTROLAR A VERSÃO DO MÓDULO');
        BancoSip::getInstance()->executarSql('INSERT INTO infra_parametro (valor, nome) VALUES(\'1.0.0\',   \'' . $this->nomeParametroModulo . '\' )');

        $this->logar('INSTALAÇÃO/ATUALIZAÇÃO DA VERSÃO 1.0.0 DO ' . $this->nomeDesteModulo . ' REALIZADA COM SUCESSO NA BASE DO SIP');
    }

    protected function instalarv200()
    {
	    $nmVersao = '2.0.0';
        $this->logar('EXECUTANDO A INSTALAÇÃO/ATUALIZAÇÃO DA VERSÃO '. $nmVersao .' DO ' . $this->nomeDesteModulo . ' NA BASE DO SIP');

        $this->logar('INCLUINDO O ÍCONE DA EXPEDIÇÃO PELOS CORREIOS');
        $objItemMenuRN = new ItemMenuRN();
        $objItemMenuDTO = new ItemMenuDTO();
        $objItemMenuDTO->setStrRotulo('Expedição pelos Correios');
        $objItemMenuDTO->retTodos();
        $objItemMenuDTO = $objItemMenuRN->consultar($objItemMenuDTO);
        $objItemMenuDTO->setStrIcone('correios_logo.svg');
        $objItemMenuRN->alterar($objItemMenuDTO);

	    $this->atualizarNumeroVersao($nmVersao);
    }

    protected function instalarv210()
    {
	    $nmVersao = '2.1.0';
        $this->logar('EXECUTANDO A INSTALAÇÃO/ATUALIZAÇÃO DA VERSÃO '. $nmVersao .' DO ' . $this->nomeDesteModulo . ' NA BASE DO SIP');

        $arr = $this->getArrNumIdSei();
        $numIdPerfilSeiAdmin = $arr['numIdPerfilSei'];
        $numIdSistemaSei = $arr['numIdSistemaSei'];

        $noPerfilExpedicao = 'Expedição Correios';

        $objPerfilRN = new PerfilRN();

        $objPerfilDTO = new PerfilDTO();
        $objPerfilDTO->retNumIdPerfil();
        $objPerfilDTO->setNumIdSistema($numIdSistemaSei);
        $objPerfilDTO->setStrNome($noPerfilExpedicao);
        $objPerfilDTO = $objPerfilRN->consultar($objPerfilDTO);

        if ($objPerfilDTO == null) {
            throw new InfraException('Perfil Expedição Correios do sistema SEI não encontrado.');
        }

        $numIdPerfilSeiExpedicao = $objPerfilDTO->getNumIdPerfil();

        $this->logar('CRIANDO e VINCULANDO RECURSO DE MENU A PERFIL - Devolver Solicitação de Expedição');
        $this->adicionarRecursoPerfil($numIdSistemaSei, $numIdPerfilSeiExpedicao, 'md_cor_expedicao_solicitada_devolver_consultar');
        $this->adicionarRecursoPerfil($numIdSistemaSei, $numIdPerfilSeiExpedicao, 'md_cor_expedicao_solicitada_devolver_alterar');

	    $this->atualizarNumeroVersao($nmVersao);
    }

	protected function instalarv220()
	{
		$nmVersao = '2.2.0';
		$this->logar('EXECUTANDO A INSTALAÇÃO/ATUALIZAÇÃO DA VERSÃO '. $nmVersao .' DO ' . $this->nomeDesteModulo . ' NA BASE DO SIP');
		$this->atualizarNumeroVersao($nmVersao);
	}

	protected function instalarv230(){
		$nmVersao = '2.3.0';
		$this->logar('EXECUTANDO A INSTALAÇÃO/ATUALIZAÇÃO DA VERSÃO '. $nmVersao .' DO ' . $this->nomeDesteModulo . ' NA BASE DO SIP');

		$objSistemaRN  = new SistemaRN();
		$objPerfilRN   = new PerfilRN();
		$objMenuRN     = new MenuRN();
		$objItemMenuRN = new ItemMenuRN();

		// retorna ID do Sistema Principal
		$objSistemaDTO = new SistemaDTO();
		$objSistemaDTO->retNumIdSistema();
		$objSistemaDTO->setStrSigla('SEI');

		$objSistemaDTO = $objSistemaRN->consultar($objSistemaDTO);

		if ($objSistemaDTO == null) {
			throw new InfraException('Sistema SEI não encontrado.');
		}

		$numIdSistemaSei = $objSistemaDTO->getNumIdSistema();

		// Perfil Administrador e Basico
		$objPerfilDTO = new PerfilDTO();
		$objPerfilDTO->retNumIdPerfil();
		$objPerfilDTO->retStrNome();
		$objPerfilDTO->setNumIdSistema($numIdSistemaSei);
		// caso seja necessario adicionar ou remover o perfil, editar o array abaixo
		$objPerfilDTO->setStrNome(['Administrador','Básico','Expedição Correios'] , InfraDTO::$OPER_IN);
		$objPerfilDTO = $objPerfilRN->listar($objPerfilDTO);

		if ($objPerfilDTO == null) {
			throw new InfraException('Perfil buscado no sistema SEI não encontrado.');
		}

		$arrPerfis = InfraArray::converterArrInfraDTO($objPerfilDTO,'IdPerfil','Nome');

		/*
		 * Inicia o vinculo dos perfis com recursos relacionados a integração
		 * $k => Nome , $v => IdPerfil
		 */
		foreach($arrPerfis as $k => $v ) {
			$this->logar('CRIANDO e VINCULANDO RECURSO DO PERFIL '. $k .' - Mapeamento das Integrações');

			//Relacionamento dos recursos para todos os perfis do loop
			$objRecursoComMenuDTO = $this->adicionarRecursoPerfil($numIdSistemaSei, $v , 'md_cor_adm_integracao_listar');

			$this->adicionarRecursoPerfil($numIdSistemaSei, $v, 'md_cor_adm_integracao_consultar');
            $this->adicionarRecursoPerfil($numIdSistemaSei, $v, 'md_cor_adm_integracao_alterar');

            //Administrador já tem perfil relacionado a recurso md_cor_contrato_consultar, então cria somente
            //para os perfis do array abaixo
            if ( in_array( $k , ['Básico','Expedição Correios'] ) ) {
                $this->adicionarRecursoPerfil($numIdSistemaSei, $v, 'md_cor_contrato_consultar');
            }

			if ( $k == 'Administrador') {
                $this->adicionarRecursoPerfil($numIdSistemaSei, $v, 'md_cor_adm_integracao_cadastrar');
                $this->adicionarRecursoPerfil($numIdSistemaSei, $v, 'md_cor_adm_integracao_excluir');
                $this->adicionarRecursoPerfil($numIdSistemaSei, $v, 'md_cor_adm_integracao_desativar');
                $this->adicionarRecursoPerfil($numIdSistemaSei, $v, 'md_cor_adm_integracao_reativar');

				$idItemMenuPrincipal = $this->_getIdItemMenuCorreiosNaAdministracao($numIdSistemaSei);
				$numIdMenuSei = $this->validarMenu('Principal', $numIdSistemaSei, $objMenuRN);

				$this->adicionarItemMenu($numIdSistemaSei,
					$v,
					$numIdMenuSei,
					$idItemMenuPrincipal,
					$objRecursoComMenuDTO->getNumIdRecurso(),
					'Mapeamento das Integrações',
					110);
			}
		}

        // Alterar nome de Menu Gerar e Expedir PLP para Gerar e Expedir Pré-Postagem
        $numIdMenuSei = $this->validarMenu('Principal', $numIdSistemaSei, $objMenuRN);

		// [ 0 => nomes atuais , 1 => nome para qual sera alterado ]
		$arrNomesUrls = [
            ['Gerar PLP','Expedir PLP','Consultar PLPs Geradas','Tipos de Situações SRO'],
            ['Gerar Pré-Postagem','Expedir Pré-Postagem','Consultar Pré-Postagens Geradas','Tipos de Situações de Rastreamento']
        ];

        $objItemMenuDTO = new ItemMenuDTO();
        $objItemMenuDTO->retTodos();

        $objItemMenuDTO->setNumIdSistema($numIdSistemaSei);
        $objItemMenuDTO->setNumIdMenu($numIdMenuSei);
        $objItemMenuDTO->setStrRotulo($arrNomesUrls[0],InfraDTO::$OPER_IN);

        $arrObjItemMenuDTO = $objItemMenuRN->listar($objItemMenuDTO);

        if ( $arrObjItemMenuDTO ) {
            foreach ($arrObjItemMenuDTO as $itemMenuDTO) {
                foreach ( $arrNomesUrls[0] as $k => $nmASerAlterado ) {
                    if ( $itemMenuDTO->getStrRotulo() == $nmASerAlterado ) {
                        $this->logar('Alterar nome do Menu '. $nmASerAlterado .' para '. $arrNomesUrls[1][$k] .'');
                        $itemMenuDTO->setStrRotulo($arrNomesUrls[1][$k]);
                        break;
                    }
                }
                $objItemMenuRN->alterar($itemMenuDTO);
            }
        }

        // Remover recursos relacionados ao rastreio
        $numIdMenu     = $this->_getIdMenu($numIdSistemaSei);
        $numIdItemMenu = $this->_getIdItemMenu($numIdSistemaSei,'Integração SRO');

        $this->logar('Remover o Menu Integração SRO');
        $this->removerItemMenu($numIdSistemaSei, $numIdMenu, $numIdItemMenu);

        $this->logar('Remover os Recursos atrelados ao parametro de rastreio');
        $this->removerRecurso($numIdSistemaSei, 'md_cor_parametro_rastreio_alterar');
        $this->removerRecurso($numIdSistemaSei, 'md_cor_parametro_rastreio_consultar');
        $this->removerRecurso($numIdSistemaSei, 'md_cor_parametro_rastreio_listar');
        $this->removerRecurso($numIdSistemaSei, 'md_cor_parametro_rastreio_cadastrar');
        $this->removerRecurso($numIdSistemaSei, 'md_cor_parametrizacao_rastreio_listar');

		$this->atualizarNumeroVersao($nmVersao);
	}

	protected function instalarv240() {
        $nmVersao = '2.4.0';
        $this->logar('EXECUTANDO A INSTALAÇÃO/ATUALIZAÇÃO DA VERSÃO ' . $nmVersao . ' DO ' . $this->nomeDesteModulo . ' NA BASE DO SIP');
        $this->atualizarNumeroVersao($nmVersao);
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
		$objInfraParametroBD = new InfraParametroBD(BancoSIP::getInstance());
		$arrObjInfraParametroDTO = $objInfraParametroBD->listar($objInfraParametroDTO);

		foreach ($arrObjInfraParametroDTO as $objInfraParametroDTO) {
			$objInfraParametroDTO->setStrValor($parStrNumeroVersao);
			$objInfraParametroBD->alterar($objInfraParametroDTO);
		}

		$this->logar('INSTALAÇÃO/ATUALIZAÇÃO DA VERSÃO '. $parStrNumeroVersao .' DO '. $this->nomeDesteModulo .' REALIZADA COM SUCESSO NA BASE DO SIP');
	}

    private function adicionarRecursoPerfil($numIdSistema, $numIdPerfil, $strNome, $strCaminho = null)
    {

        $objRecursoDTO = new RecursoDTO();
        $objRecursoDTO->retNumIdRecurso();
        $objRecursoDTO->setNumIdSistema($numIdSistema);
        $objRecursoDTO->setStrNome($strNome);

        $objRecursoRN = new RecursoRN();
        $objRecursoDTO = $objRecursoRN->consultar($objRecursoDTO);

        if ($objRecursoDTO == null) {
            $objRecursoDTO = new RecursoDTO();
            $objRecursoDTO->setNumIdRecurso(null);
            $objRecursoDTO->setNumIdSistema($numIdSistema);
            $objRecursoDTO->setStrNome($strNome);
            $objRecursoDTO->setStrDescricao(null);

            if ($strCaminho == null) {
                $objRecursoDTO->setStrCaminho('controlador.php?acao=' . $strNome);
            } else {
                $objRecursoDTO->setStrCaminho($strCaminho);
            }
            $objRecursoDTO->setStrSinAtivo('S');
            $objRecursoDTO = $objRecursoRN->cadastrar($objRecursoDTO);
        }

        if ($numIdPerfil != null) {
            $objRelPerfilRecursoDTO = new RelPerfilRecursoDTO();
            $objRelPerfilRecursoDTO->setNumIdSistema($numIdSistema);
            $objRelPerfilRecursoDTO->setNumIdPerfil($numIdPerfil);
            $objRelPerfilRecursoDTO->setNumIdRecurso($objRecursoDTO->getNumIdRecurso());

            $objRelPerfilRecursoRN = new RelPerfilRecursoRN();

            if ($objRelPerfilRecursoRN->contar($objRelPerfilRecursoDTO) == 0) {
                $objRelPerfilRecursoRN->cadastrar($objRelPerfilRecursoDTO);
            }
        }

        return $objRecursoDTO;

    }

    private function removerRecursoPerfil($numIdSistema, $strNome, $numIdPerfil)
    {

        $objRecursoDTO = new RecursoDTO();
        $objRecursoDTO->setBolExclusaoLogica(false);
        $objRecursoDTO->retNumIdRecurso();
        $objRecursoDTO->setNumIdSistema($numIdSistema);
        $objRecursoDTO->setStrNome($strNome);

        $objRecursoRN = new RecursoRN();
        $objRecursoDTO = $objRecursoRN->consultar($objRecursoDTO);

        if ($objRecursoDTO != null) {

            $objRelPerfilRecursoDTO = new RelPerfilRecursoDTO();
            $objRelPerfilRecursoDTO->retTodos();
            $objRelPerfilRecursoDTO->setNumIdSistema($numIdSistema);
            $objRelPerfilRecursoDTO->setNumIdRecurso($objRecursoDTO->getNumIdRecurso());
            $objRelPerfilRecursoDTO->setNumIdPerfil($numIdPerfil);

            $objRelPerfilRecursoRN = new RelPerfilRecursoRN();
            $objRelPerfilRecursoRN->excluir($objRelPerfilRecursoRN->listar($objRelPerfilRecursoDTO));

            $objRelPerfilItemMenuDTO = new RelPerfilItemMenuDTO();
            $objRelPerfilItemMenuDTO->retTodos();
            $objRelPerfilItemMenuDTO->setNumIdSistema($numIdSistema);
            $objRelPerfilItemMenuDTO->setNumIdRecurso($objRecursoDTO->getNumIdRecurso());
            $objRelPerfilItemMenuDTO->setNumIdPerfil($numIdPerfil);

            $objRelPerfilItemMenuRN = new RelPerfilItemMenuRN();
            $objRelPerfilItemMenuRN->excluir($objRelPerfilItemMenuRN->listar($objRelPerfilItemMenuDTO));
        }
    }

    private function desativarRecurso($numIdSistema, $strNome)
    {

        $objRecursoDTO = new RecursoDTO();
        $objRecursoDTO->retNumIdRecurso();
        $objRecursoDTO->setNumIdSistema($numIdSistema);
        $objRecursoDTO->setStrNome($strNome);

        $objRecursoRN = new RecursoRN();
        $objRecursoDTO = $objRecursoRN->consultar($objRecursoDTO);

        if ($objRecursoDTO != null) {
            $objRecursoRN->desativar(array($objRecursoDTO));
        }
    }

    private function removerRecurso($numIdSistema, $strNome)
    {

        $objRecursoDTO = new RecursoDTO();
        $objRecursoDTO->setBolExclusaoLogica(false);
        $objRecursoDTO->retNumIdRecurso();
        $objRecursoDTO->setNumIdSistema($numIdSistema);
        $objRecursoDTO->setStrNome($strNome);

        $objRecursoRN = new RecursoRN();
        $objRecursoDTO = $objRecursoRN->consultar($objRecursoDTO);

        if ($objRecursoDTO != null) {
            $objRelPerfilRecursoDTO = new RelPerfilRecursoDTO();
            $objRelPerfilRecursoDTO->retTodos();
            $objRelPerfilRecursoDTO->setNumIdSistema($numIdSistema);
            $objRelPerfilRecursoDTO->setNumIdRecurso($objRecursoDTO->getNumIdRecurso());

            $objRelPerfilRecursoRN = new RelPerfilRecursoRN();
            $objRelPerfilRecursoRN->excluir($objRelPerfilRecursoRN->listar($objRelPerfilRecursoDTO));

            $objItemMenuDTO = new ItemMenuDTO();
            $objItemMenuDTO->retNumIdMenu();
            $objItemMenuDTO->retNumIdItemMenu();
            $objItemMenuDTO->setNumIdSistema($numIdSistema);
            $objItemMenuDTO->setNumIdRecurso($objRecursoDTO->getNumIdRecurso());

            $objItemMenuRN = new ItemMenuRN();
            $arrObjItemMenuDTO = $objItemMenuRN->listar($objItemMenuDTO);

            $objRelPerfilItemMenuRN = new RelPerfilItemMenuRN();

            foreach ($arrObjItemMenuDTO as $objItemMenuDTO) {
                $objRelPerfilItemMenuDTO = new RelPerfilItemMenuDTO();
                $objRelPerfilItemMenuDTO->retTodos();
                $objRelPerfilItemMenuDTO->setNumIdSistema($numIdSistema);
                $objRelPerfilItemMenuDTO->setNumIdItemMenu($objItemMenuDTO->getNumIdItemMenu());

                $objRelPerfilItemMenuRN->excluir($objRelPerfilItemMenuRN->listar($objRelPerfilItemMenuDTO));
            }

            $objItemMenuRN->excluir($arrObjItemMenuDTO);

            $objRecursoRN->excluir(array($objRecursoDTO));
        }
    }

    private function adicionarItemMenu($numIdSistema, $numIdPerfil, $numIdMenu, $numIdItemMenuPai, $numIdRecurso, $strRotulo, $numSequencia)
    {

        $objItemMenuDTO = new ItemMenuDTO();
        $objItemMenuDTO->retNumIdItemMenu();
        $objItemMenuDTO->setNumIdMenu($numIdMenu);

        if ($numIdItemMenuPai == null) {
            $objItemMenuDTO->setNumIdMenuPai(null);
            $objItemMenuDTO->setNumIdItemMenuPai(null);
        } else {
            $objItemMenuDTO->setNumIdMenuPai($numIdMenu);
            $objItemMenuDTO->setNumIdItemMenuPai($numIdItemMenuPai);
        }

        $objItemMenuDTO->setNumIdSistema($numIdSistema);
        $objItemMenuDTO->setNumIdRecurso($numIdRecurso);
        $objItemMenuDTO->setStrRotulo($strRotulo);

        $objItemMenuRN = new ItemMenuRN();
        $objItemMenuDTO = $objItemMenuRN->consultar($objItemMenuDTO);

        if ($objItemMenuDTO == null) {
            $objItemMenuDTO = new ItemMenuDTO();
            $objItemMenuDTO->setNumIdItemMenu(null);
            $objItemMenuDTO->setNumIdMenu($numIdMenu);

            if ($numIdItemMenuPai == null) {
                $objItemMenuDTO->setNumIdMenuPai(null);
                $objItemMenuDTO->setNumIdItemMenuPai(null);
            } else {
                $objItemMenuDTO->setNumIdMenuPai($numIdMenu);
                $objItemMenuDTO->setNumIdItemMenuPai($numIdItemMenuPai);
            }

            $objItemMenuDTO->setNumIdSistema($numIdSistema);
            $objItemMenuDTO->setNumIdRecurso($numIdRecurso);
            $objItemMenuDTO->setStrRotulo($strRotulo);
            $objItemMenuDTO->setStrDescricao(null);
            $objItemMenuDTO->setNumSequencia($numSequencia);
            $objItemMenuDTO->setStrSinNovaJanela('N');
            $objItemMenuDTO->setStrSinAtivo('S');
            $objItemMenuDTO->setStrIcone(null);

            $objItemMenuDTO = $objItemMenuRN->cadastrar($objItemMenuDTO);
        }

        if ($numIdPerfil != null && $numIdRecurso != null) {
            $objRelPerfilRecursoDTO = new RelPerfilRecursoDTO();
            $objRelPerfilRecursoDTO->setNumIdSistema($numIdSistema);
            $objRelPerfilRecursoDTO->setNumIdPerfil($numIdPerfil);
            $objRelPerfilRecursoDTO->setNumIdRecurso($numIdRecurso);

            $objRelPerfilRecursoRN = new RelPerfilRecursoRN();

            if ($objRelPerfilRecursoRN->contar($objRelPerfilRecursoDTO) == 0) {
                $objRelPerfilRecursoRN->cadastrar($objRelPerfilRecursoDTO);
            }

            $objRelPerfilItemMenuDTO = new RelPerfilItemMenuDTO();
            $objRelPerfilItemMenuDTO->setNumIdPerfil($numIdPerfil);
            $objRelPerfilItemMenuDTO->setNumIdSistema($numIdSistema);
            $objRelPerfilItemMenuDTO->setNumIdRecurso($numIdRecurso);
            $objRelPerfilItemMenuDTO->setNumIdMenu($numIdMenu);
            $objRelPerfilItemMenuDTO->setNumIdItemMenu($objItemMenuDTO->getNumIdItemMenu());

            $objRelPerfilItemMenuRN = new RelPerfilItemMenuRN();

            if ($objRelPerfilItemMenuRN->contar($objRelPerfilItemMenuDTO) == 0) {
                $objRelPerfilItemMenuRN->cadastrar($objRelPerfilItemMenuDTO);
            }
        }

        return $objItemMenuDTO;

    }

    private function removerItemMenu($numIdSistema, $numIdMenu, $numIdItemMenu)
    {

        $objItemMenuDTO = new ItemMenuDTO();
        $objItemMenuDTO->retNumIdMenu();
        $objItemMenuDTO->retNumIdItemMenu();
        $objItemMenuDTO->setNumIdSistema($numIdSistema);
        $objItemMenuDTO->setNumIdMenu($numIdMenu);
        $objItemMenuDTO->setNumIdItemMenu($numIdItemMenu);

        $objItemMenuRN = new ItemMenuRN();
        $objItemMenuDTO = $objItemMenuRN->consultar($objItemMenuDTO);

        if ($objItemMenuDTO != null) {
            $objRelPerfilItemMenuDTO = new RelPerfilItemMenuDTO();
            $objRelPerfilItemMenuDTO->retTodos();
            $objRelPerfilItemMenuDTO->setNumIdSistema($numIdSistema);
            $objRelPerfilItemMenuDTO->setNumIdMenu($objItemMenuDTO->getNumIdMenu());
            $objRelPerfilItemMenuDTO->setNumIdItemMenu($objItemMenuDTO->getNumIdItemMenu());

            $objRelPerfilItemMenuRN = new RelPerfilItemMenuRN();
            $objRelPerfilItemMenuRN->excluir($objRelPerfilItemMenuRN->listar($objRelPerfilItemMenuDTO));

            $objItemMenuRN->excluir(array($objItemMenuDTO));
        }
    }

    private function removerPerfil($numIdSistema, $strNome)
    {

        $objPerfilDTO = new PerfilDTO();
        $objPerfilDTO->retNumIdPerfil();
        $objPerfilDTO->setNumIdSistema($numIdSistema);
        $objPerfilDTO->setStrNome($strNome);

        $objPerfilRN = new PerfilRN();
        $objPerfilDTO = $objPerfilRN->consultar($objPerfilDTO);

        if ($objPerfilDTO != null) {
            $objPermissaoDTO = new PermissaoDTO();
            $objPermissaoDTO->retNumIdSistema();
            $objPermissaoDTO->retNumIdUsuario();
            $objPermissaoDTO->retNumIdPerfil();
            $objPermissaoDTO->retNumIdUnidade();
            $objPermissaoDTO->setNumIdSistema($numIdSistema);
            $objPermissaoDTO->setNumIdPerfil($objPerfilDTO->getNumIdPerfil());

            $objPermissaoRN = new PermissaoRN();
            $objPermissaoRN->excluir($objPermissaoRN->listar($objPermissaoDTO));

            $objRelPerfilItemMenuDTO = new RelPerfilItemMenuDTO();
            $objRelPerfilItemMenuDTO->retTodos();
            $objRelPerfilItemMenuDTO->setNumIdSistema($numIdSistema);
            $objRelPerfilItemMenuDTO->setNumIdPerfil($objPerfilDTO->getNumIdPerfil());

            $objRelPerfilItemMenuRN = new RelPerfilItemMenuRN();
            $objRelPerfilItemMenuRN->excluir($objRelPerfilItemMenuRN->listar($objRelPerfilItemMenuDTO));

            $objRelPerfilRecursoDTO = new RelPerfilRecursoDTO();
            $objRelPerfilRecursoDTO->retTodos();
            $objRelPerfilRecursoDTO->setNumIdSistema($numIdSistema);
            $objRelPerfilRecursoDTO->setNumIdPerfil($objPerfilDTO->getNumIdPerfil());

            $objRelPerfilRecursoRN = new RelPerfilRecursoRN();
            $objRelPerfilRecursoRN->excluir($objRelPerfilRecursoRN->listar($objRelPerfilRecursoDTO));

            $objCoordenadorPerfilDTO = new CoordenadorPerfilDTO();
            $objCoordenadorPerfilDTO->retTodos();
            $objCoordenadorPerfilDTO->setNumIdSistema($numIdSistema);
            $objCoordenadorPerfilDTO->setNumIdPerfil($objPerfilDTO->getNumIdPerfil());

            $objCoordenadorPerfilRN = new CoordenadorPerfilRN();
            $objCoordenadorPerfilRN->excluir($objCoordenadorPerfilRN->listar($objCoordenadorPerfilDTO));

            $objPerfilRN->excluir(array($objPerfilDTO));

        }
    }

    private function validarMenu($nome, $numIdSistemaSei, $objRN, $numIdItemMenu = null)
    {

        switch ($nome) {

            case 'Principal':
                $objMenuDTO = new MenuDTO();
                $objMenuDTO->retNumIdMenu();
                $objMenuDTO->setNumIdSistema($numIdSistemaSei);
                $objMenuDTO->setStrNome('Principal');
                $objMenuDTO = $objRN->consultar($objMenuDTO);

                if (is_null($objMenuDTO)) {
                    throw new InfraException('Menu do sistema SEI não encontrado.');
                }
                return $objMenuDTO->getNumIdMenu();

            case 'Administração':
                $objItemMenuDTO = new ItemMenuDTO();
                $objItemMenuDTO->retNumIdItemMenu();
                $objItemMenuDTO->setNumIdSistema($numIdSistemaSei);
                $objItemMenuDTO->setStrRotulo('Administração');
                $objItemMenuDTO = $objRN->consultar($objItemMenuDTO);

                if ($objItemMenuDTO == null) {
                    throw new InfraException('Item de menu Administração do sistema SEI não encontrado.');
                }
                return $objItemMenuDTO->getNumIdItemMenu();

            case 'Relatórios':
                $objItemMenuDTO = new ItemMenuDTO();
                $objItemMenuDTO->retNumIdItemMenu();
                $objItemMenuDTO->setNumIdSistema($numIdSistemaSei);
                $objItemMenuDTO->setStrRotulo('Relatórios');
                $objItemMenuDTO = $objRN->consultar($objItemMenuDTO);

                if ($objItemMenuDTO == null) {
                    throw new InfraException('Item de menu Principal/Relatórios do sistema SEI não encontrado.');
                }
                return $objItemMenuDTO->getNumIdItemMenu();

            case 'Correios':
                $objItemMenuDTO = new ItemMenuDTO();
                $objItemMenuDTO->retNumIdItemMenu();
                $objItemMenuDTO->setNumIdSistema($numIdSistemaSei);
                $objItemMenuDTO->setNumIdItemMenuPai($numIdItemMenu);
                $objItemMenuDTO->setStrRotulo('Correios');
                $objItemMenuDTO = $objRN->consultar($objItemMenuDTO);

                if ($objItemMenuDTO == null) {
                    throw new InfraException('Item de menu Administração/Correios do sistema SEI não encontrado.');
                }
                break;
        }
    }

    private function getArrNumIdSei($nomePerfil = 'Administrador')
    {

        $objSistemaRN = new SistemaRN();
        $objPerfilRN = new PerfilRN();

        $objSistemaDTO = new SistemaDTO();
        $objSistemaDTO->retNumIdSistema();
        $objSistemaDTO->setStrSigla('SEI');

        $objSistemaDTO = $objSistemaRN->consultar($objSistemaDTO);

        if ($objSistemaDTO == null) {
            throw new InfraException('Sistema SEI não encontrado.');
        }

        $numIdSistemaSei = $objSistemaDTO->getNumIdSistema();

        $objPerfilDTO = new PerfilDTO();
        $objPerfilDTO->retNumIdPerfil();
        $objPerfilDTO->setNumIdSistema($numIdSistemaSei);
        $objPerfilDTO->setStrNome($nomePerfil);
        $objPerfilDTO = $objPerfilRN->consultar($objPerfilDTO);

        if ($objPerfilDTO == null) {
            throw new InfraException("Perfil $nomePerfil do sistema SEI não encontrado.");
        }

        return array('numIdPerfilSei' => $objPerfilDTO->getNumIdPerfil(), 'numIdSistemaSei' => $numIdSistemaSei);
    }

	private function _getIdItemMenuCorreiosNaAdministracao($numIdSistema)
	{
		$rotuloItemMenu    = 'Correios';
		$rotuloItemMenuAdm = 'Administração';

		$objItemMenuRN = new ItemMenuRN();

		//captura informação do menu Administração
		$objItemMenuAdmDTO = new ItemMenuDTO();
		$objItemMenuAdmDTO->retNumIdItemMenu();

		$objItemMenuAdmDTO->setNumIdSistema($numIdSistema);
		$objItemMenuAdmDTO->setStrRotulo($rotuloItemMenuAdm);
		$objItemMenuAdmDTO = $objItemMenuRN->consultar($objItemMenuAdmDTO);

		if ($objItemMenuAdmDTO == null) {
			$msg = 'Item de menu Administração do sistema no encontrado.';
			throw new InfraException($msg);
		}

		$objItemMenuDTO = new ItemMenuDTO();
		$objItemMenuDTO->retNumIdItemMenu();

		$objItemMenuDTO->setNumIdSistema($numIdSistema);
		$objItemMenuDTO->setStrRotulo($rotuloItemMenu);
		$objItemMenuDTO->setNumIdItemMenuPai($objItemMenuAdmDTO->getNumIdItemMenu());

		$objItemMenuDTO = $objItemMenuRN->consultar($objItemMenuDTO);

		if ($objItemMenuDTO == null) {
			$msg = 'Item de menu ' . $rotuloItemMenu . ' do sistema no encontrado.';
			throw new InfraException($msg);
		}

		$numIdItemMenuSeiAdm = $objItemMenuDTO->getNumIdItemMenu();

		return $numIdItemMenuSeiAdm;
	}

    private function _getIdMenu($numIdSistema, $nomeMenu = 'Principal')
    {
        $objMenuRN = new MenuRN();
        $objMenuDTO = new MenuDTO();
        $objMenuDTO->retNumIdMenu();
        $objMenuDTO->setNumIdSistema($numIdSistema);
        $objMenuDTO->setStrNome($nomeMenu);
        $objMenuDTO = $objMenuRN->consultar($objMenuDTO);

        if ($objMenuDTO == null) {
            throw new InfraException('Menu do sistema não encontrado.');
        }

        $idMenu = $objMenuDTO->getNumIdMenu();

        return $idMenu;
    }

    private function _getIdItemMenu($numIdSistema, $rotuloItemMenu = 'Administração')
    {
        $objItemMenuRN = new ItemMenuRN();
        $objItemMenuDTO = new ItemMenuDTO();
        $objItemMenuDTO->retNumIdItemMenu();
        $objItemMenuDTO->setNumIdSistema($numIdSistema);
        $objItemMenuDTO->setStrRotulo($rotuloItemMenu);
        $objItemMenuDTO = $objItemMenuRN->consultar($objItemMenuDTO);

        if ($objItemMenuDTO == null) {
            $msg = 'Item de menu ' . $rotuloItemMenu . ' do sistema no encontrado.';
            throw new InfraException($msg);
        }

        $numIdItemMenuSeiAdm = $objItemMenuDTO->getNumIdItemMenu();

        return $numIdItemMenuSeiAdm;
    }

}

try {

    SessaoSip::getInstance(false);
    BancoSip::getInstance()->setBolScript(true);

    InfraScriptVersao::solicitarAutenticacao(BancoSip::getInstance());
    $objVersaoSipRN = new MdCorAtualizadorSipRN();
    $objVersaoSipRN->atualizarVersao();
	exit;

} catch (Exception $e) {
    echo(InfraException::inspecionar($e));
    try {
        LogSip::getInstance()->gravar(InfraException::inspecionar($e));
    } catch (Exception $e) {
    }
    exit(1);
}