<script type="text/javascript">
    function inicializar() {
    };

    function abrirModalSelecionarTipoObjeto(url) {
        infraAbrirJanelaModal(url,
            550,
            150);
    }

    function expandirTodos(idDiv, img) {
        var divFilha = document.getElementById(idDiv);

        if (divFilha.style.display == "none") {
            document.getElementById(idDiv).removeAttribute('style');
            img.setAttribute('src', '/infra_css/svg/ocultar.svg');//menos
        } else {
            document.getElementById(idDiv).setAttribute("style", "display: none");
            img.setAttribute('src', '/infra_css/svg/exibir.svg');//mais
        }
    }

    function selecionaTodos(classChk, chk) {
        selecionado = chk.getAttribute('selecionado')
        if (selecionado === 'false') {
            $("." + classChk).prop("checked", true);
        } else {
            $("." + classChk).prop("checked", false);
        }
        if (selecionado === 'false') {
            chk.setAttribute('selecionado', true);
            chk.childNodes[0].setAttribute('title', 'Remover Seleção');
            chk.childNodes[0].setAttribute('alt', 'Remover Seleção');
        } else {
            chk.setAttribute('selecionado', false);
            chk.childNodes[0].setAttribute('title', 'Selecionar Tudo');
            chk.childNodes[0].setAttribute('alt', 'Selecionar Tudo');
        }
    }

    function pesquisar() {
        document.getElementById('frmGerarPlp').submit();
    }

    function fechar() {
        document.location = '<?= $strUrlFechar ?>';
    }

    function imprimir() {
        infraImprimirTabela();
    }

    function gerarPLP() {
        var contador = 0;
        $(".infraCheckboxInput:checked").each(
            function () {
                contador++;
            }
        );
        console.log(contador);
        if (contador == 0) {
            alert('Deverá ser selecionado pelo menos 1(uma) solicitação de expedição para geração da PLP')
            return false;
        } else {
            infraExibirAviso(false);
            document.getElementById('frmGerarPlp').setAttribute('action', '<?= SessaoSEI::getInstance()->assinarLink('controlador.php?acao=md_cor_plp_correio_cadastro&acao_origem=' . $_GET['acao']) ?>')
            document.getElementById('frmGerarPlp').submit();
        }
    }

    function abrirModalDevolverSolicitacao(url) {
        infraAbrirJanelaModal(url,
            800,
            550);
    }
    function atualizaPagina() {
        location.href =  '<?= SessaoSEI::getInstance()->assinarLink("controlador.php?acao=md_cor_geracao_plp_listar") ?> ';
    }
</script>
