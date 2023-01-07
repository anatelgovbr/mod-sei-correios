<script type="text/javascript">
//======================= INICIANDO BLOCO JAVASCRIPT DA PAGINA =======================================
var objLupaTipoDocumento = null;
var objAutoCompletarTipoDocumento = null;

function inicializar(){

	objAutoCompletarTipoDocumento = new infraAjaxAutoCompletar('hdnIdSerie','txtSerie','<?=$strLinkAjaxTipoDocumento?>');
	objAutoCompletarTipoDocumento.limparCampo = true;
    objAutoCompletarTipoDocumento.tamanhoMinimo = 3;
	
	objAutoCompletarTipoDocumento.prepararExecucao = function(){
	    return 'palavras_pesquisa='+document.getElementById('txtSerie').value;
	};
	  
	objAutoCompletarTipoDocumento.processarResultado = function(id,descricao,complemento){
		if (id!=''){
			var options = document.getElementById('selDescricao').options;
			for(var i=0;i < options.length;i++){
				if (options[i].value == id){
					alert('Tipo de Documento já consta na lista.');
					break;
				}
			}
      
			if (i==options.length){
				for(i=0;i < options.length;i++){
					options[i].selected = false; 
				}
	      
				opt = infraSelectAdicionarOption(document.getElementById('selDescricao'),descricao,id);
				objLupaTipoDocumento.atualizar();
	        	opt.selected = true;
			}
	                  
			document.getElementById('txtSerie').value = '';
			document.getElementById('txtSerie').focus();
	    }
	};
    
	objLupaTipoDocumento = new infraLupaSelect('selDescricao','hdnTipoDocumento','<?=$strLinkTipoDocumentoSelecao?>'); 

	infraEfeitoTabelas();

} //fim funcao inicializar

function validarCadastro() {
	//tipo de documento associado
	var optionsTipoDocs = document.getElementById('selDescricao').options;

	if( optionsTipoDocs.length == 0 ){
	    alert('Informe ao menos um Tipo de Documento de Expedição.');
	    document.getElementById('selDescricao').focus();
	    return false;
	  }
	
	return true;
}

function OnSubmitForm() {
  return validarCadastro();
}
</script>