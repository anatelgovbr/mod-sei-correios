<?php

try {
	require_once dirname(__FILE__) . '/../../SEI.php';

	session_start();

	//////////////////////////////////////////////////////////////////////////////
	//InfraDebug::getInstance()->setBolLigado(false);
	//InfraDebug::getInstance()->setBolDebugInfra(true);
	//InfraDebug::getInstance()->limpar();
	//////////////////////////////////////////////////////////////////////////////

	SessaoSEI::getInstance()->validarLink();

	SessaoSEI::getInstance()->validarPermissao($_GET['acao']);

	// Links para consulta Ajax
	#$strLinkValidarWsdl = SessaoSEI::getInstance()->assinarLink('controlador_ajax.php?acao_ajax=md_utl_integracao_busca_operacao');

	// Instancia classes RN e DTO
	$objMdCorAdmIntegracaoDTO = new MdCorAdmIntegracaoDTO();
	$objMdCorAdmIntegracaoRN  = new MdCorAdmIntegracaoRN();


	// Variaveis globais
	$strTipoAcao      = '';
	$strDesabilitar   = '';
	$arrDados         = [];
	$tpFuncionalidade = null;
	$arrFuncionalidadesCadastradas = null;

	$arrComandos = array();

	switch($_GET['acao']){
		case 'md_cor_adm_integracao_cadastrar':
			$strTipoAcao = 'cadastrar';
			$strTitulo = 'Novo Mapeamento de Integração';
			$arrComandos[] = '<button type="submit" accesskey="S" name="sbmCadastrarMdCorAdmIntegracao" value="Salvar" class="infraButton"><span class="infraTeclaAtalho">S</span>alvar</button>';
			$arrComandos[] = '<button type="button" accesskey="C" name="btnCancelar" id="btnCancelar" value="Cancelar" onclick="location.href=\''.SessaoSEI::getInstance()->assinarLink('controlador.php?acao='.PaginaSEI::getInstance()->getAcaoRetorno().'&acao_origem='.$_GET['acao']).'\';" class="infraButton"><span class="infraTeclaAtalho">C</span>ancelar</button>';

			$objMdCorAdmIntegracaoDTO->setNumIdMdCorAdmIntegracao(null);
			$objMdCorAdmIntegracaoDTO->setStrNome($_POST['txtNome']);
			$objMdCorAdmIntegracaoDTO->setNumFuncionalidade($_POST['selFuncionalidade']);
			$objMdCorAdmIntegracaoDTO->setStrUrlOperacao($_POST['txtUrlServico']);

			$objMdCorAdmIntegracaoDTO->setStrUsuario($_POST['txtUsuario'] ?? null);
            $objMdCorAdmIntegracaoDTO->setStrSenha($_POST['txtSenha'] ? MdCorAdmIntegracaoINT::gerenciaDadosRestritos($_POST['txtSenha'],'C') : null);
            $objMdCorAdmIntegracaoDTO->setStrToken($_POST['txtToken'] ?? null);

			$objMdCorAdmIntegracaoDTO->setStrSinAtivo('S');

			$arrFuncionalidadesCadastradas = $objMdCorAdmIntegracaoRN->buscaFuncionalidadesCadastradas();

			if (isset($_POST['sbmCadastrarMdCorAdmIntegracao'])) {
				try{
					$objMdCorAdmIntegracaoDTO = $objMdCorAdmIntegracaoRN->cadastrar($objMdCorAdmIntegracaoDTO);
					PaginaSEI::getInstance()->adicionarMensagem('Integração "'.$objMdCorAdmIntegracaoDTO->getStrNome().'" cadastrada com sucesso.');
					header('Location: '.SessaoSEI::getInstance()->assinarLink('controlador.php?acao='.PaginaSEI::getInstance()->getAcaoRetorno().'&acao_origem='.$_GET['acao'].'&id_md_cor_adm_integracao='.$objMdCorAdmIntegracaoDTO->getNumIdMdCorAdmIntegracao().PaginaSEI::getInstance()->montarAncora($objMdCorAdmIntegracaoDTO->getNumIdMdCorAdmIntegracao())));
					die;
				}catch(Exception $e){
					PaginaSEI::getInstance()->processarExcecao($e);
				}
			}
			break;

		case 'md_cor_adm_integracao_alterar':
			$strTipoAcao = 'alterar';
			$strTitulo   = 'Alterar Mapeamento de Integração';
			$arrComandos[] = '<button type="submit" accesskey="S" name="sbmAlterarMdCorAdmIntegracao" value="Salvar" class="infraButton"><span class="infraTeclaAtalho">S</span>alvar</button>';
			$strDesabilitar = 'disabled="disabled"';

			if (isset($_GET['id_md_cor_adm_integracao'])){
				$objMdCorAdmIntegracaoDTO->setNumIdMdCorAdmIntegracao( $_GET['id_md_cor_adm_integracao'] );
				$objMdCorAdmIntegracaoDTO->retTodos();
				$objMdCorAdmIntegracaoRN = new MdCorAdmIntegracaoRN();
				$objMdCorAdmIntegracaoDTO = $objMdCorAdmIntegracaoRN->consultar($objMdCorAdmIntegracaoDTO);

				if ($objMdCorAdmIntegracaoDTO==null){
					throw new InfraException("Registro não encontrado.");
				}
			} else {
				$objMdCorAdmIntegracaoDTO->setNumIdMdCorAdmIntegracao($_POST['hdnIdMdCorAdmInteg']);
				$objMdCorAdmIntegracaoDTO->setStrNome($_POST['txtNome']);
				$objMdCorAdmIntegracaoDTO->setNumFuncionalidade($_POST['selFuncionalidade']);
				$objMdCorAdmIntegracaoDTO->setStrUrlOperacao($_POST['txtUrlServico']);

				$objMdCorAdmIntegracaoDTO->setStrUsuario($_POST['txtUsuario'] ?? null);
				$objMdCorAdmIntegracaoDTO->setStrSenha($_POST['txtSenha'] ? MdCorAdmIntegracaoINT::gerenciaDadosRestritos($_POST['txtSenha'],'C') : null);
				$objMdCorAdmIntegracaoDTO->setStrToken($_POST['txtToken'] ?? null);
			}

			$arrComandos[] = '<button type="button" accesskey="C" name="btnCancelar" id="btnCancelar" value="Cancelar" onclick="location.href=\''.SessaoSEI::getInstance()->assinarLink('controlador.php?acao='.PaginaSEI::getInstance()->getAcaoRetorno().'&acao_origem='.$_GET['acao'].PaginaSEI::getInstance()->montarAncora($objMdCorAdmIntegracaoDTO->getNumIdMdCorAdmIntegracao())).'\';" class="infraButton"><span class="infraTeclaAtalho">C</span>ancelar</button>';

			if (isset($_POST['sbmAlterarMdCorAdmIntegracao'])) {
				try{
					$objMdCorAdmIntegracaoRN = new MdCorAdmIntegracaoRN();
					$objMdCorAdmIntegracaoRN->alterar( $objMdCorAdmIntegracaoDTO );
					PaginaSEI::getInstance()->adicionarMensagem('Integração "'.$objMdCorAdmIntegracaoDTO->getStrNome().'" alterada com sucesso.');
					header('Location: '.SessaoSEI::getInstance()->assinarLink('controlador.php?acao='.PaginaSEI::getInstance()->getAcaoRetorno().'&acao_origem='.$_GET['acao'].PaginaSEI::getInstance()->montarAncora($objMdCorAdmIntegracaoDTO->getNumIdMdCorAdmIntegracao())));
					die;
				}catch(Exception $e){
					PaginaSEI::getInstance()->processarExcecao($e);
				}
			}
			break;

		case 'md_cor_adm_integracao_consultar':
			$strTipoAcao = 'consultar';
			$strTitulo = 'Consultar Mapeamento de Integração';
			$arrComandos[] = '<button type="button" accesskey="F" name="btnFechar" value="Fechar" onclick="location.href=\''.SessaoSEI::getInstance()->assinarLink('controlador.php?acao='.PaginaSEI::getInstance()->getAcaoRetorno().'&acao_origem='.$_GET['acao'].PaginaSEI::getInstance()->montarAncora($_GET['id_md_cor_adm_integracao'])).'\';" class="infraButton"><span class="infraTeclaAtalho">F</span>echar</button>';
			$objMdCorAdmIntegracaoDTO->setNumIdMdCorAdmIntegracao($_GET['id_md_cor_adm_integracao']);
			$objMdCorAdmIntegracaoDTO->setBolExclusaoLogica(false);
			$objMdCorAdmIntegracaoDTO->retTodos();
			$objMdCorAdmIntegracaoRN = new MdCorAdmIntegracaoRN();
			$objMdCorAdmIntegracaoDTO = $objMdCorAdmIntegracaoRN->consultar($objMdCorAdmIntegracaoDTO);

			if ($objMdCorAdmIntegracaoDTO===null){
				throw new InfraException("Registro não encontrado.");
			}
			break;

		default:
			throw new InfraException("Ação '".$_GET['acao']."' não reconhecida.");
	}


}catch(Exception $e){
	PaginaSEI::getInstance()->processarExcecao($e);
}

PaginaSEI::getInstance()->montarDocType();
PaginaSEI::getInstance()->abrirHtml();
PaginaSEI::getInstance()->abrirHead();
PaginaSEI::getInstance()->montarMeta();
PaginaSEI::getInstance()->montarTitle(PaginaSEI::getInstance()->getStrNomeSistema().' - '.$strTitulo);
PaginaSEI::getInstance()->montarStyle();
PaginaSEI::getInstance()->abrirStyle();
PaginaSEI::getInstance()->fecharStyle();
#require 'md_utl_geral_css.php';

PaginaSEI::getInstance()->montarJavaScript();
PaginaSEI::getInstance()->abrirJavaScript();
PaginaSEI::getInstance()->fecharJavaScript();
PaginaSEI::getInstance()->fecharHead();
PaginaSEI::getInstance()->abrirBody($strTitulo,'onload="inicializar();"');
?>
	<form id="frmMdCorAdmIntegracaoCadastro" method="post" onsubmit="return OnSubmitForm();" action="<?= SessaoSEI::getInstance()->assinarLink('controlador.php?acao=' . $_GET['acao'] . '&acao_origem=' . $_GET['acao']) ?>">

		<?php
		PaginaSEI::getInstance()->montarBarraComandosSuperior($arrComandos);
		//PaginaSEI::getInstance()->montarAreaValidacao();
		PaginaSEI::getInstance()->abrirAreaDados();
		?>

		<div class="row mb-2">
			<div class="col-sm-12 col-md-9">
				<label id="lblFuncionalidade" for="Funcionalidade"  class="infraLabelObrigatorio">Funcionalidade:</label>
				<img id="imgDefServico" align="top" src="<?= PaginaSEI::getInstance()->getDiretorioSvgGlobal() ?>/ajuda.svg"
                     class="infraImg" name="ajuda"
                    <?= PaginaSEI::montarTitleTooltip('Texto a Definir!','Ajuda') ?> />

				<select id="selFuncionalidade" name="selFuncionalidade" class="infraSelect form-control"
				        tabindex="<?=PaginaSEI::getInstance()->getProxTabDados()?>">
					<?= MdCorAdmIntegracaoINT::montarSelectFuncionalidade(
						$objMdCorAdmIntegracaoDTO->getNumFuncionalidade(),
						false,
						$arrFuncionalidadesCadastradas
					)
					?>
				</select>
			</div>
		</div>

		<div class="row mb-2">
			<div class="col-sm-12 col-md-9">
				<label id="lblNome" for="txtNome" class="infraLabelObrigatorio">Nome:</label>
				<input type="text" id="_txtNome" name="_txtNome" class="infraText form-control"
				       value="<?= PaginaSEI::tratarHTML($objMdCorAdmIntegracaoDTO->getStrNome()) ?>" onkeypress="return infraMascaraTexto(this,event,100);" maxlength="100"
				       tabindex="<?= PaginaSEI::getInstance()->getProxTabDados() ?>" disabled/>

                <input type="hidden" id="txtNome" name="txtNome" value="<?= PaginaSEI::tratarHTML($objMdCorAdmIntegracaoDTO->getStrNome()) ?>" />
			</div>
		</div>

		<div class="row mb-2">
			<div class="col-sm-12 col-md-9">
				<label id="lblUrlServico" for="txtUrlServico" class="infraLabelObrigatorio">URL do Endpoint da Operação:</label>
				<img id="imgDefServico" align="top" src="<?= PaginaSEI::getInstance()->getDiretorioSvgGlobal() ?>/ajuda.svg" class="infraImg"
				     name="ajuda" <?= PaginaSEI::montarTitleTooltip('Informar URL que consta o ENDPOINT do serviço a ser solicitado.','Ajuda') ?> />
				<div class="input-group">
					<input type="text" id="txtUrlServico" name="txtUrlServico" class="infraText form-control"
					       value="<?= PaginaSEI::tratarHTML($objMdCorAdmIntegracaoDTO->getStrUrlOperacao()) ?>"
					       onkeypress="return infraMascaraTexto(this,event,100);"
					       maxlength="100"
					       tabindex="<?=PaginaSEI::getInstance()->getProxTabDados()?>" />
				</div>
			</div>
		</div>

		<div class="row mb-2" id="divAutenticacao">
			<div class="col-12">
				<fieldset class="infraFieldset p-3">
					<legend class="infraLegend">Autenticação para Obter Token Diário</legend>
					<div class="row">
						<div class="col-md-3">
							<label class="infraLabelOpcional">Usuário do Órgão no Fale Conosco Correios:</label>
							<img id="imgDefServico" align="top" src="<?= PaginaSEI::getInstance()->getDiretorioSvgGlobal() ?>/ajuda.svg" class="infraImg"
							     name="ajuda" <?= PaginaSEI::montarTitleTooltip('Texto a Definir!','Ajuda') ?> />
							<input type="text" class="infraText form-control" id="txtUsuario" name="txtUsuario" value="<?= PaginaSEI::tratarHTML($objMdCorAdmIntegracaoDTO->getStrUsuario()) ?>">
						</div>
						<div class="col-md-3">
							<label class="infraLabelOpcional">Senha:</label>
							<img id="imgDefServico" align="top" src="<?= PaginaSEI::getInstance()->getDiretorioSvgGlobal() ?>/ajuda.svg" class="infraImg"
							     name="ajuda" <?= PaginaSEI::montarTitleTooltip('Texto a Definir!','Ajuda') ?> />
							<input type="text" class="infraText form-control" id="txtSenha" name="txtSenha" value="<?= PaginaSEI::tratarHTML($objMdCorAdmIntegracaoDTO->getStrSenha()) ?>">
						</div>
						<div class="col-md-6">
							<label class="infraLabelOpcional">Token Inicial:</label>
							<img id="imgDefServico" align="top" src="<?= PaginaSEI::getInstance()->getDiretorioSvgGlobal() ?>/ajuda.svg" class="infraImg"
							     name="ajuda" <?= PaginaSEI::montarTitleTooltip('Texto a Definir!','Ajuda') ?> />
							<input type="text" class="infraText form-control" id="txtToken" name="txtToken" value="<?= PaginaSEI::tratarHTML($objMdCorAdmIntegracaoDTO->getStrToken()) ?>">
						</div>
					</div>
				</fieldset>
			</div>
		</div>

        <input type="hidden" id="hdnIdMdCorAdmInteg" name="hdnIdMdCorAdmInteg" value="<?= $objMdCorAdmIntegracaoDTO->getNumIdMdCorAdmIntegracao() ?>">
        <input type="hidden" id="hdnTipoAcao" name="hdnTipoAcao" value="<?= $strTipoAcao ?>">

		<?php
		PaginaSEI::getInstance()->fecharAreaDados();
		//PaginaSEI::getInstance()->montarAreaDebug();
		PaginaSEI::getInstance()->montarBarraComandosInferior($arrComandos);
		?>
	</form>

<?php
require 'md_cor_adm_integracao_cadastro_js.php';
PaginaSEI::getInstance()->fecharBody();
PaginaSEI::getInstance()->fecharHtml();
