<script type="text/javascript" charset="iso-8859-1" src="/../../../infra_js/InfraUtil.js"></script>

<script type="text/javascript">
var objLupaUnidades = null;
var objAutoCompletarUnidade = null;

function inicializar(){
	objAutoCompletarUnidade = new infraAjaxAutoCompletar('hdnIdUnidade','txtUnidade','<?=$strLinkAjaxUnidade?>');
	objAutoCompletarUnidade.limparCampo = true;
	objAutoCompletarUnidade.tamanhoMinimo = 3;

	objAutoCompletarUnidade.prepararExecucao = function(){
		return 'palavras_pesquisa='+document.getElementById('txtUnidade').value;
	};

	objAutoCompletarUnidade.processarResultado = function(id,descricao,complemento){
		if (id!=''){
			var options = document.getElementById('selUnidades').options;
			for(var i=0;i < options.length;i++){
				if (options[i].value == id){
					alert('Unidade já consta na lista.');
					break;
				}
			}
      
			if (i==options.length){
				for(i=0;i < options.length;i++){
					options[i].selected = false; 
				}

				opt = infraSelectAdicionarOption(document.getElementById('selUnidades'),descricao,id);
				objLupaUnidades.atualizar();
				opt.selected = true;
			}

			document.getElementById('txtUnidade').value = '';
			document.getElementById('txtUnidade').focus();
		}
	};
	objLupaUnidades = new infraLupaSelect('selUnidades','hdnUnidades','<?=$strLinkUnidadesSelecao?>'); 
	
	function carregarDependenciaConduta(){
		  objAjaxIdConduta = new infraAjaxMontarSelectDependente('selContrato','selServicos','<?=$strLinkAjaxDependConduta?>');
		  objAjaxIdConduta.prepararExecucao = function(){
			document.getElementById('selServicos').innerHTML  = '';
			var arrIdMdCorContrato = document.getElementById('selContrato').value.split("#");
		    return infraAjaxMontarPostPadraoSelect('null','','null') + '&IdMdCorContrato='+arrIdMdCorContrato[0];
		  }
		  objAjaxIdConduta.processarResultado = function(){}
	}
	
	//carregarComponenteDispositivoNormativoDN();
	
	carregarDependenciaConduta();
	if (document.getElementById('selContrato').value!=''){
		objAjaxIdConduta.executar();
	}
	
	////////////

	
	infraEfeitoTabelas();

} //fim funcao inicializar

function validarCadastro() {
    if (document.getElementById('selUnidades').length<1) {
        alert('Informe o Unidade Solicitante');
        document.getElementById('txtUnidade').focus();
        return false;
    }	
    if (infraTrim(document.getElementById('hdnListaContratoServicosIndicados').value) == '') {
        alert('Informe Serviços por Contrato.');
        document.getElementById('selContrato').focus();
        return false;
    }
    return true;
}

function OnSubmitForm() {
    return validarCadastro();
}

</script>

<script type="text/javascript">

var objTabelaContratoServicos = null;
var hdnListaContratoServicosIndicados = null;
var arrhdnListaContratoServicosIndicados = null;

// ContratoServicos - funcionalidades
objTabelaContratoServicos = new infraTabelaDinamica('tbContratoServicos','hdnListaContratoServicosIndicados',false,false);

objTabelaContratoServicos.gerarEfeitoTabela = true;
objTabelaContratoServicos.inserirNoInicio = false;
objTabelaContratoServicos.exibirMensagens=true;

objTabelaContratoServicos.procuraLinha = function(id) {
	var qtd;
	var linha;
	qtd = document.getElementById('tbContratoServicos').rows.length;
	for (i = 1; i < qtd; i++) {
		linha = document.getElementById('tbContratoServicos').rows[i];
		var valorLinha = $.trim(linha.cells[0].innerText);
		if (valorLinha == id) {
			return i;
		}
	}
	return null;
};

function adicionarContratoServicos(){
	$('#selServicos option:selected').each(function(){
		var arrDadosContratoServicosValido = [];	

		//ID
		arrDadosContratoServicosValido[0] = $(this).val();
		arrDadosContratoServicosValido[1] = $("#selContrato option:selected").text();;

		var arrIdMdCorContrato = $('#selContrato').val().split("#");
		arrDadosContratoServicosValido[2] = arrIdMdCorContrato[1];

		arrDadosContratoServicosValido[3] = $(this).text();
		
		arrDadosContratoServicosValido[4] = '0_S'; //"Ações";          
		
		//var bolContratoServicosCustomizado = hdnCustomizado;
		var bolContratoServicosCustomizado = 'hdnCustomizado';

		receberContratoServicos( arrDadosContratoServicosValido , bolContratoServicosCustomizado );
	});	
	
}

function receberContratoServicos( arrDadosContratoServicos, ContratoServicosCustomizado ){
	var qtdContratoServicosIndicados = objTabelaContratoServicos.tbl.rows.length;
	objTabelaContratoServicos.adicionar([ 
	                    	arrDadosContratoServicos[0],
	                    	arrDadosContratoServicos[1],
	                    	arrDadosContratoServicos[2],
	                    	arrDadosContratoServicos[3],
	                    	arrDadosContratoServicos[4],
	                    	'' ]);
  	//Linha adicionada, adiciona as ações
  	if (qtdContratoServicosIndicados < objTabelaContratoServicos.tbl.rows.length){
	    if( ContratoServicosCustomizado != "") {
	    	objTabelaContratoServicos.adicionarAcoes(arrDadosContratoServicos[0], '', false, true);
	    }
  	}
  	
	infraEfeitoTabelas();
}

function removerContratoServico( idServicoPostal , idUnidMapServicoPostal ){
	$.ajax({
		url: "<?= $linkAjaxValidaExclContratoServ ?>",
		type: 'post',
		data: { idUnidMapServicoPostal: idUnidMapServicoPostal , idServicoPostal: idServicoPostal },
		dataType: 'xml'
	})
	.done( function( rs ){
		if( $( rs ).find('Msg').text() != '' ){
			alert( $( rs ).find('Msg').text() );
		}else{
			let row = objTabelaContratoServicos.procuraLinha( idServicoPostal );
			objTabelaContratoServicos.removerLinha( row );
		}
	})
	.fail( function( xhr ){
		alert('Erro na exclusão do registro.');
		console.error( xhr.responseText );
	});	
}

function AtivarDesativarContratoServico( id , acao ){
	let msg_acao = acao == 'D' ? 'Desativar' : 'Reativar';
	$.ajax({
		url: "<?= $linkAjaxDesReaContratoServico ?>",
		type: 'post',
		data: { id_contrato_servico: id , acao: acao },
		dataType: 'xml'
	})
	.done( function( rs ){		
		if( $( rs ).find('Sucesso').text() == 'S' )
			window.location.reload();
		else
			alert('Não foi possível '+ msg_acao +' este serviço postal por falha na requisição.');
	})
	.fail( function( xhr ){
		alert('Erro na requisição.');
		console.error( xhr.responseText );
	});
}

function gerenciaAcoesGrid(){
	hdnListaContrato = objTabelaContratoServicos.hdn.value;console.log(hdnListaContrato);
    arrListaContrato = hdnListaContrato.split('¥'); //equivalente as linhas da grid
	var icoExc = "<?= PaginaSEI::getInstance()->getDiretorioSvgGlobal() . '/remover.svg' ?>";
	var icoDes = "<?= PaginaSEI::getInstance()->getDiretorioSvgGlobal() . '/desativar.svg' ?>";
	var icoRea = "<?= PaginaSEI::getInstance()->getDiretorioSvgGlobal() . '/reativar.svg' ?>";
	var arrLinhasDesativadas = new Array();
	for ( i = 0 ; i < arrListaContrato.length ; i++ ) {
		hdnListaCont  = arrListaContrato[i].split('±'); //equivalente as colunas da linha
		var arrIdUnidStatus = hdnListaCont[4].split('_'); //recupera valor da coluna que contem: id_md_cor_map_unid_servico e sin_ativo
		var objBtn;
		var btnDesRea = '';
		
		if( arrIdUnidStatus[1] == 'S' ){
			objBtn = { id: arrIdUnidStatus[0] , acao: 'D' , title: 'Desativar' , btn: icoDes };
		}else{
			//adiciona no array o valor da coluna[0] para, posteriormente, adicionar a class trVermelha para indicacao de linha desativada
			arrLinhasDesativadas.push( hdnListaCont[0] );
			objBtn = { id: arrIdUnidStatus[0] , acao: 'R' , title: 'Reativar' , btn: icoRea };
		}
		btnDesRea ="<img onclick=\"AtivarDesativarContratoServico("+ objBtn.id +",'"+ objBtn.acao +"')\""+" title='"+ objBtn.title +" Serviço Postal' alt='"+ objBtn.title +" Serviço Postal' src='"+ objBtn.btn +"' class='infraImg'/> ";
		
		//parametros: idServicoPostal e idUnidMapeadaServicoPostal
		var btnRemover = "<img onclick=\"removerContratoServico("+ hdnListaCont[0] +","+ arrIdUnidStatus[0] +")\""+" title='Remover Item' alt='Remover Item' src='"+icoExc+"' class='infraImg'/> ";
		
		<?php if ( $_REQUEST['acao']== "md_cor_map_unid_servico_alterar" ): ?>
			objTabelaContratoServicos.adicionarAcoes(hdnListaCont[0], btnRemover + btnDesRea , false, false);
		<?php endif; ?>
	}
	
	// controle de quais linhas terao a marcação de desativada
	if( arrLinhasDesativadas.length > 0 ){
		arrLinhasDesativadas.forEach( function( el , idx ){
			let linha = objTabelaContratoServicos.procuraLinha( el );
			let objRow = document.getElementById('tbContratoServicos').rows[linha];
			$( objRow ).removeClass('infraTrClara infraTrEscura').addClass('trVermelha');
		});
	}
}

// ContratoServicos - funcionalidades - FIM

<? if ($_REQUEST['acao']== "md_cor_map_unid_servico_alterar" || $_REQUEST['acao']== "md_cor_map_unid_servico_consultar") { ?>
if ( objTabelaContratoServicos.tbl.rows.length > 2 ){
	gerenciaAcoesGrid();
}
<? } ?>
</script>
