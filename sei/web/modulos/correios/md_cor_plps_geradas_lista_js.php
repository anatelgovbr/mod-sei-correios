<script type="text/javascript">
    function inicializar(){
        document.getElementById('btnFechar').focus();
        infraEfeitoTabelas();
    }

    function pesquisar(){
        document.getElementById('frmPlpsGeradas').submit();
    }

    function cancelarPLP(codPlp, idPlp , codRastreamento, linkCancelarPlp){
       if ( confirm('Deseja cancelar a PLP de número: '+ codPlp +'?') ) {
            $.ajax({
                url: linkCancelarPlp,
                type: 'post',
                dataType: 'xml',
                data: { idPlp: idPlp, codRastreamento: codRastreamento },
                beforeSend: function(){
                    infraExibirAviso(false);
                },
                success: function ( result ) {
                    infraAvisoCancelar();
                    let rs = $( result ).find('Sucesso').text();
                    if ( rs == 'N') {
                        exibirMsg(codPlp, $( result ).find('Msg').text() );
                        return false;
                    } else {
                        location.reload();
                    }
                },
                error: function (e) {
                    alert('Ocorreu alguma falha!!');
                    console.error('Erro ao processar o XML do SEI: ' + e.responseText);
                }
            });
       }
    }

    function exibirMsg(codPlp,msg){
        msg = msg.replace('#','<br><br>');
        let elem = '<div class="alert alert-danger alert-dismissible fade show" style="font-size:.875rem; top:0.25rem; margin-bottom: 14px !important; width:98%; margin:0 auto;" role="alert">'+
                        'Não foi possível cancelar a PLP: '+ codPlp +'<br><br>'
                        + msg +
                        '<button type="button" class="close media h-100" data-dismiss="alert" aria-label="Fechar Mensagem" aria-labelledby="divInfraMsg0">'+
                            '<span aria-hidden="true" class="align-self-center"><b>X</b></span>'+
                        '</button>'+
                    '</div>';

        $('#divInfraBarraComandosSuperior').prepend( elem );

        let nivel = document.getElementById( 'divInfraBarraComandosSuperior' ).offsetTop + top;
        let divInfraMoverTopo = document.getElementById("divInfraAreaTelaD");
        $( divInfraMoverTopo ).animate( { scrollTop: nivel } , 800 );
    }
</script>
