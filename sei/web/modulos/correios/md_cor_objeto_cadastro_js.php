<script type="text/javascript">

function inicializar(){
  if ('<?=$_GET['acao']?>'=='md_cor_objeto_cadastrar'){
    document.getElementById('selMdCorTipoObjeto').focus();
  } else if ('<?=$_GET['acao']?>'=='md_cor_objeto_consultar'){
    infraDesabilitarCamposAreaDados();
  }else{
    document.getElementById('btnCancelar').focus();
  }
  infraEfeitoTabelas();
}

function validarCadastro() {
  if (!infraSelectSelecionado('selMdCorTipoObjeto')) {
    alert('Selecione um tipo de objeto.');
    document.getElementById('selMdCorTipoObjeto').focus();
    return false;
  }

  if(document.getElementById('optTipoRotuloImpressaoCompleto').checked == false && document.getElementById('optTipoRotuloImpressaoResumido').checked == false ){
    alert('Selecione o tipo de rótulo utilizado para impressão');
    document.getElementById('optTipoRotuloImpressaoResumido').focus();
    return false;
  }

  if(document.getElementById('optSinObjetoPadraoSim').checked == false && document.getElementById('optSinObjetoPadraoNao').checked == false ){
    alert('Selecione o objeto padrão para expedição');
    document.getElementById('optSinObjetoPadraoNao').focus();
    return false;
  }

  if(document.getElementById('txtMargemSuperiorImpressao').value == '' ){
    alert('Preencha o campo margem superior.');
    document.getElementById('txtMargemSuperiorImpressao').focus();
    return false;
  }

  if(document.getElementById('txtMargemEsquerdaImpressao').value == '' ){
    alert('Preencha o campo margem esquerda.');
    document.getElementById('txtMargemEsquerdaImpressao').focus();
    return false;
  }

  return true;
}

function OnSubmitForm() {
  return validarCadastro();
}

</script>