<script type="text/javascript">

function inicializar(){
  if ('<?=$_GET['acao']?>'=='md_cor_objeto_selecionar'){
    infraReceberSelecao();
    document.getElementById('btnFecharSelecao').focus();
  }else{
    document.getElementById('btnFechar').focus();
  }
  infraEfeitoTabelas();
}

function acaoDesativar(id,desc){
  if (confirm("Confirma Desativa��o da Embalagem \""+desc+"\"?")){
    document.getElementById('hdnInfraItemId').value=id;
    document.getElementById('frmMdCorObjetoLista').action='<?=$strLinkDesativar?>';
    document.getElementById('frmMdCorObjetoLista').submit();
  }
}

function acaoDesativacaoMultipla(){
  if (document.getElementById('hdnInfraItensSelecionados').value==''){
    alert('Nenhuma Embalagem Selecionada.');
    return;
  }
  if (confirm("Confirma Desativa��o das Embalagens Selecionadas?")){
    document.getElementById('hdnInfraItemId').value='';
    document.getElementById('frmMdCorObjetoLista').action='<?=$strLinkDesativar?>';
    document.getElementById('frmMdCorObjetoLista').submit();
  }
}

function acaoReativar(id,desc){
  if (confirm("Confirma reativa��o da Embalagem \""+desc+"\"?")){
    document.getElementById('hdnInfraItemId').value=id;
    document.getElementById('frmMdCorObjetoLista').action='<?=$strLinkReativar?>';
    document.getElementById('frmMdCorObjetoLista').submit();
  }
}

function acaoReativacaoMultipla(){
  if (document.getElementById('hdnInfraItensSelecionados').value==''){
    alert('Nenhuma Embalagem Selecionada.');
    return;
  }
  if (confirm("Confirma Reativa��o das Embalagens Selecionadas?")){
    document.getElementById('hdnInfraItemId').value='';
    document.getElementById('frmMdCorObjetoLista').action='<?=$strLinkReativar?>';
    document.getElementById('frmMdCorObjetoLista').submit();
  }
}

function acaoExcluir(id,desc){
  if (confirm("Confirma Exclus�o da Embalagem \""+desc+"\"?")){
    document.getElementById('hdnInfraItemId').value=id;
    document.getElementById('frmMdCorObjetoLista').action='<?=$strLinkExcluir?>';
    document.getElementById('frmMdCorObjetoLista').submit();
  }
}

function acaoExclusaoMultipla(){
  if (document.getElementById('hdnInfraItensSelecionados').value==''){
    alert('Nenhuma Embalagem Selecionada.');
    return;
  }
  if (confirm("Confirma Exclus�o das Embalagens Selecionadas?")){
    document.getElementById('hdnInfraItemId').value='';
    document.getElementById('frmMdCorObjetoLista').action='<?=$strLinkExcluir?>';
    document.getElementById('frmMdCorObjetoLista').submit();
  }
}
</script>