<script type="text/javascript">
    var objAutoCompletarUnidade = null;
    var objLupaUnidade = null;

    function inicializar() {
        iniciarAutoCompletarUnidade();
    }

    function iniciarAutoCompletarUnidade() {
        objLupaUnidade = new infraLupaSelect('selUnidade', 'hdnIdUnidades', '<?= $strUrlUnidade ?>');

        objAutoCompletarUnidade = new infraAjaxAutoCompletar('hdnIdUnidade', 'txtUnidade', '<?= $strLinkAjaxAutocompletarUnidade ?>');
        objAutoCompletarUnidade.limparCampo = true;
        objAutoCompletarUnidade.tamanhoMinimo = 3;

        objAutoCompletarUnidade.prepararExecucao = function () {
            return 'palavras_pesquisa=' + document.getElementById('txtUnidade').value;
        };

        objAutoCompletarUnidade.processarResultado = function (id, descricao) {
            if (id != '') {
                objLupaUnidade.adicionar(id, descricao, document.getElementById('txtUnidade'));
            }
        };
    }

    function validarCadastro() {
        if (document.getElementById('selUnidade').length == 0) {
            alert('Informe a Unidade Expedidora.');
            document.getElementById('txtUnidade').focus();
            return false;
        }
        return true;
    }

    function OnSubmitForm() {
        return validarCadastro();
    }
</script>