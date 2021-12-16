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

function adicionarContratoServicos(){
	$('#selServicos option:selected').each(function(){
		var arrDadosContratoServicosValido = [];	

		//ID
		arrDadosContratoServicosValido[0] = $(this).val();
		arrDadosContratoServicosValido[1] = $("#selContrato option:selected").text();;

		var arrIdMdCorContrato = $('#selContrato').val().split("#");
		arrDadosContratoServicosValido[2] = arrIdMdCorContrato[1];

		arrDadosContratoServicosValido[3] = $(this).text();
		
		arrDadosContratoServicosValido[4] = "Ações";          
		
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

// ContratoServicos - funcionalidades - FIM

<? if ($_REQUEST['acao']== "md_cor_map_unid_servico_alterar" /*|| $_REQUEST['acao']== "md_cor_map_unid_servico_consultar"*/) { ?>
if (objTabelaContratoServicos.tbl.rows.length>0){

    //pular a linha 0, que é do header da tabela
    for(var i=1;i<objTabelaContratoServicos.tbl.rows.length;i++){
		var tbl = document.getElementById('tbContratoServicos');
		var colAcoes = tbl.rows[i].cells[4];
		//objTabelaContratoServicos.adicionarAcoes(i, '', false, true);
		objTabelaContratoServicos.adicionarAcaoRemover( colAcoes );
	}
	
}
<? } ?>
</script>
