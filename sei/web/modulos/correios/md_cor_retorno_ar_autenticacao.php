<?
/*
* TRIBUNAL REGIONAL FEDERAL DA 4ª REGIÃO
*
* 15/09/2008 - criado por marcio_db
*
*
*/

try {
  require_once dirname(__FILE__) . '/../../SEI.php';

  session_start();

  //////////////////////////////////////////////////////////////////////////////
  InfraDebug::getInstance()->setBolLigado(false);
  InfraDebug::getInstance()->setBolDebugInfra(true);
  InfraDebug::getInstance()->limpar();
  //////////////////////////////////////////////////////////////////////////////

  SessaoSEI::getInstance()->validarLink();

  SessaoSEI::getInstance()->validarPermissao($_GET['acao']);

  //PaginaSEI::getInstance()->salvarCamposPost(array('selCargoFuncao'));

  PaginaSEI::getInstance()->setTipoPagina(InfraPagina::$TIPO_PAGINA_SIMPLES);

  $bolAssinaturaOK = false;
  $bolPermiteAssinaturaLogin=false;
  $bolPermiteAssinaturaCertificado=false;
  $bolAutenticacao = false;

  $strLinkRetorno = SessaoSEI::getInstance()->assinarLink('controlador.php?acao=md_cor_retorno_ar_listar');

  switch($_GET['acao']){
    case 'md_cor_retorno_ar_autenticar':
      $idRetornoAr = $_GET['id_retorno'];
      $arrRetorno = explode(',', $idRetornoAr);

      if(count($arrRetorno) > 1){
          $idRetornoAr = $arrRetorno;
      }else{
          $idRetornoAr = [$idRetornoAr];
      }

      $mdCorRetornoArDocRN = new MdCorRetornoArDocRN();
      $mdCorRetornoArDocDTO = new MdCorRetornoArDocDTO();
      $mdCorRetornoArDocDTO->setNumIdMdCorRetornoAr($idRetornoAr, InfraDTO::$OPER_IN);
      $mdCorRetornoArDocDTO->setStrSinAutenticado('N');
      $mdCorRetornoArDocDTO->retNumIdDocumentoAr();
      $mdCorRetornoArDocDTO->retNumIdUnidadeResponsavel();

      $mdCorRetornoArDocDTO->adicionarCriterio(array('IdDocumentoAr'),
        array(InfraDTO::$OPER_DIFERENTE),
        array(NULL));

      $arrMdCorRetornoArDocDTO = $mdCorRetornoArDocRN->listar($mdCorRetornoArDocDTO);

      $arrIdDocumentos = InfraArray::converterArrInfraDTO($arrMdCorRetornoArDocDTO, 'IdDocumentoAr');

      $objInfraParametro=new InfraParametro(BancoSEI::getInstance());
      $tipoAssinatura=$objInfraParametro->getValor('SEI_TIPO_AUTENTICACAO_INTERNA');

      $strTitulo = 'Assinatura de Documento';

      $numRegistros = count($arrIdDocumentos);


      if ($numRegistros == 1){
        $objDocumentoDTO = new DocumentoDTO();
        $objDocumentoDTO->retStrStaDocumento();
        $objDocumentoDTO->retNumIdTipoConferencia();
        $objDocumentoDTO->setDblIdDocumento($arrIdDocumentos, InfraDTO::$OPER_IN);

        $objDocumentoRN = new DocumentoRN();
        $objDocumentoDTO = $objDocumentoRN->consultarRN0005($objDocumentoDTO);

        if ($objDocumentoDTO!=null && $objDocumentoDTO->getStrStaDocumento()==DocumentoRN::$TD_EXTERNO){
          $strTitulo = 'Autenticação de Documento';
          $tipoAssinatura=$objInfraParametro->getValor('SEI_TIPO_AUTENTICACAO_INTERNA');
          $bolAutenticacao = true;
        }
      }

      switch ($tipoAssinatura){
        case 1:
          $bolPermiteAssinaturaCertificado=true;
          $bolPermiteAssinaturaLogin=true;
          break;
        case 2:
          $bolPermiteAssinaturaLogin=true;
          break;
        case 3:
          $bolPermiteAssinaturaCertificado=true;
      }

      $objAssinaturaDTO = new AssinaturaDTO();
      $objAssinaturaDTO->setStrStaFormaAutenticacao($_POST['hdnFormaAutenticacao']);

      if (!isset($_POST['hdnFlagAssinatura'])){
        $objAssinaturaDTO->setNumIdOrgaoUsuario(SessaoSEI::getInstance()->getNumIdOrgaoUsuario());
      }else{
        $objAssinaturaDTO->setNumIdOrgaoUsuario($_POST['selOrgao']);
      }

      if (!isset($_POST['hdnFlagAssinatura'])){
      }else{
        $objAssinaturaDTO->setNumIdContextoUsuario($_POST['selContexto']);
      }

      $objAssinaturaDTO->setNumIdUsuario($_POST['hdnIdUsuario']);
      $objAssinaturaDTO->setStrSenhaUsuario($_POST['pwdSenha']);

      //$objAssinaturaDTO->setStrCargoFuncao(PaginaSEI::getInstance()->recuperarCampo('selCargoFuncao'));

      $objInfraDadoUsuario = new InfraDadoUsuario(SessaoSEI::getInstance());

      $strChaveDadoUsuarioAssinatura = 'ASSINATURA_CARGO_FUNCAO_'.SessaoSEI::getInstance()->getNumIdUnidadeAtual();

      if (!isset($_POST['selCargoFuncao'])){
        $objAssinaturaDTO->setStrCargoFuncao($objInfraDadoUsuario->getValor($strChaveDadoUsuarioAssinatura));
      }else{
        $objAssinaturaDTO->setStrCargoFuncao($_POST['selCargoFuncao']);

        if ($objAssinaturaDTO->getNumIdUsuario()==SessaoSEI::getInstance()->getNumIdUsuario()) {
          $objInfraDadoUsuario->setValor($strChaveDadoUsuarioAssinatura, $_POST['selCargoFuncao']);
        }
      }

      if ($_POST['hdnFormaAutenticacao'] != null){

        if($_POST['hdnFormaAutenticacao']==AssinaturaRN::$TA_CERTIFICADO_DIGITAL && !$bolPermiteAssinaturaCertificado){
          throw new InfraException('Assinatura por Certificado Digital não permitida.');
        } else if($_POST['hdnFormaAutenticacao']==AssinaturaRN::$TA_SENHA && !$bolPermiteAssinaturaLogin){
          throw new InfraException('Assinatura por login não permitida.');
        }

        $idUsuarioLogado = SessaoSEI::getInstance()->getNumIdUsuario();
        $idUnidadeAtual = SessaoSEI::getInstance()->getNumIdUnidadeAtual();
        try {

          foreach ($arrMdCorRetornoArDocDTO as $resultado) {
            $idUnidadeResponsavel = $resultado->getNumIdUnidadeResponsavel();
            SessaoSEI::getInstance()->setBolHabilitada(false);
            SessaoSEI::getInstance()->simularLogin(null, null, $idUsuarioLogado, $idUnidadeResponsavel);

            $idDocumentoRetorno = $resultado->getNumIdDocumentoAr();

            $objAssinaturaDTO->setArrObjDocumentoDTO(InfraArray::gerarArrInfraDTO('DocumentoDTO', 'IdDocumento', [$idDocumentoRetorno]));
            $objDocumentoRN = new DocumentoRN();

            $arrObjAssinaturaDTO = $objDocumentoRN->assinar($objAssinaturaDTO);

            $bolAssinaturaOK = true;

            if ($objAssinaturaDTO->getStrStaFormaAutenticacao() == AssinaturaRN::$TA_CERTIFICADO_DIGITAL) {

              $strJSDocumentosAssinar = '';
              $strJSAssinaturas = '  arrIdsAssinaturas = [];' . "\n";

              foreach ($arrObjAssinaturaDTO as $dto) {

                if ($strJSDocumentosAssinar == '') {
                  $strJSDocumentosAssinar .= '  strIdsDocumentosAssinar = "' . $dto->getDblIdDocumento() . '=' . $dto->getStrProtocoloDocumentoFormatado() . '";' . "\n";
                } else {
                  $strJSDocumentosAssinar .= '  strIdsDocumentosAssinar += ";' . $dto->getDblIdDocumento() . '=' . $dto->getStrProtocoloDocumentoFormatado() . '";' . "\n";
                }

                $strJSAssinaturas .= '  arrIdsAssinaturas.push("' . $dto->getDblIdDocumento() . ':' . $dto->getNumIdAssinatura() . '");' . "\n";
              }

              $oCodeDownload = 'http://java.com/download/';
              $oJavaVersion = '1.7+';

              $strDivCertificacao = '<br/><br/><br/>';

              $strDivCertificacao .= '<div id="divExibeApplet" style="width:100%; top:0%;">';

              $arrArquivosCache = array('assinador/assinador-3.6.jar',
                'assinador/sei-assinador-3.6.jar',
                'assinador/bcprov-jdk15-1.45.jar',
                'assinador/bcmail-jdk15-1.45.jar',
                'assinador/demoiselle-certificate-ca-icpbrasil-1.0.12.jar',
                'assinador/demoiselle-certificate-core-1.0.12.jar',
                'assinador/demoiselle-certificate-criptography-1.0.12.jar',
                'assinador/demoiselle-certificate-signer-1.0.12.jar',
                'assinador/log4j-1.2.16.jar',
                'assinador/slf4j-api-1.6.1.jar',
                'assinador/slf4j-log4j12-1.6.1.jar');

              $strArquivosCache = implode(',', $arrArquivosCache);
              $strVersoesCache = implode(',', array_fill(0, count($arrArquivosCache), 'SEI-' . SEI_VERSAO));

              if (PaginaSEI::getInstance()->isBolNavegadorIE()) {
                $strDivCertificacao .= '<object id="assinador" classid = "clsid:8AD9C840-044E-11D1-B3E9-00805F499D93" border="0"
              codebase = "' . $oCodeDownload . '" width = "550" height = "45" align = "center">
              <param name = "archive"    value="' . $strArquivosCache . '"/>
              <param name = "java_version"    value="' . $oJavaVersion . '"/>
              <param name = "code" 			value = "br.jus.trf4.assinador.applet.AssinadorApplet" />
              <param name = "type" 			value = "application/x-java-applet"/>
              <param name = "browser" 		value="MSIE"/>
              <param name = "submit.form" 	value="true"/>
              <param name = "nome.form" 		value=""/>
              <param name = "cache_option"  value="browser" />
              <param name = "cache_archive" value="' . $strArquivosCache . '" />
              <param name = "cache_version" 	value="' . $strVersoesCache . '" />
              <param name = "url.upload" 		value=""/> <!-- vazio para não fazer upload -->
              <param name = "url.download"	value=""/>
              <param name = "url.service"	    value="' . ConfiguracaoSEI::getInstance()->getValor('SEI', 'URL') . '/controlador_ws.php?servico=assinador"/>
              <param name = "url.service.up"  value="' . ConfiguracaoSEI::getInstance()->getValor('SEI', 'URL') . '/controlador_ws.php?servico=assinador"/>
              <param name = "service.provider" value="br.jus.trf4.assinador.sei.SEIClient"/>
              <param name = "standalone" 		value="false"/>
              </object>';
              } else if (PaginaSEI::getInstance()->isBolNavegadorFirefox()) {
                $strDivCertificacao .= '<embed id="assinador" CODE = "br.jus.trf4.assinador.applet.AssinadorApplet"
              type="application/x-java-applet"
              java_version="' . $oJavaVersion . '"
              pluginspage="' . $oCodeDownload . '"
              width = "550" height = "45" align = "center"
              mayscript = "mayscript"
              browser 		="MOZZ"
              submit.form 	="true"
              nome.form 		=""
              cache_option  ="browser"
              cache_archive 	="' . $strArquivosCache . '"
              cache_version 	="' . $strVersoesCache . '"
              url.upload		=""
              url.download	=""
              url.service		="' . ConfiguracaoSEI::getInstance()->getValor('SEI', 'URL') . '/controlador_ws.php?servico=assinador"
              url.service.up	="' . ConfiguracaoSEI::getInstance()->getValor('SEI', 'URL') . '/controlador_ws.php?servico=assinador"
              service.provider="br.jus.trf4.assinador.sei.SEIClient">
              </embed>';
              } else {
                throw new InfraException('Este navegador não suporta o plugin de assinatura digital do SEI.');
              }
              $strDivCertificacao .= '</div>' . "\n";

            }

          SessaoSEI::getInstance()->setBolHabilitada(true);
          SessaoSEI::getInstance()->simularLogin(null, null, $idUsuarioLogado, $idUnidadeAtual);

          foreach ($idRetornoAr as $id){
              $mdCorRetornoArRN = new MdCorRetornoArRN();
              $mdCorRetornoArDTO = new MdCorRetornoArDTO();
              $mdCorRetornoArDTO->setNumIdMdCorRetornoAr($id);
              $mdCorRetornoArDTO->setStrSinAutenticado('S');
              $mdCorRetornoArRN->alterar($mdCorRetornoArDTO);
          }

          }
        } catch (Exception $e) {
          PaginaSEI::getInstance()->processarExcecao($e, true);
        }
      }

      break;

    default:
      throw new InfraException("Ação '".$_GET['acao']."' não reconhecida.");
  }

  $arrComandos = array();


  if ($numRegistros) {
    if ($bolPermiteAssinaturaCertificado && $objAssinaturaDTO->getStrStaFormaAutenticacao() == AssinaturaRN::$TA_CERTIFICADO_DIGITAL){
      $arrComandos[] = '<button type="button" accesskey="A" onclick="assinarCertificadoDigital();" id="btnAssinar" name="btnAssinar" value="Assinar" class="infraButton" style="visibility:hidden">&nbsp;<span class="infraTeclaAtalho">A</span>ssinar&nbsp;</button>';
    }else if ($bolPermiteAssinaturaLogin ) {
      $arrComandos[] = '<button type="button" accesskey="A" onclick="assinarSenha();" id="btnAssinar" name="btnAssinar" value="Assinar" class="infraButton">&nbsp;<span class="infraTeclaAtalho">A</span>ssinar&nbsp;</button>';
    }
  }

  if (!isset($_POST['hdnIdUsuario'])){
    $strIdUsuario = SessaoSEI::getInstance()->getNumIdUsuario();
    $strNomeUsuario = SessaoSEI::getInstance()->getStrNomeUsuario();
  }else{
    $strIdUsuario = $_POST['hdnIdUsuario'];
    $strNomeUsuario = $_POST['txtUsuario'];
  }

  if ($bolAssinaturaOK){
    $strDisplayAutenticacao = 'display:none;';
  }else{
    $strDisplayAutenticacao = '';
  }


  $strLinkAjaxUsuarios = SessaoSEI::getInstance()->assinarLink('controlador_ajax.php?acao_ajax=usuario_assinatura_auto_completar');
  $strItensSelOrgaos = OrgaoINT::montarSelectSiglaRI1358('null','&nbsp;',$objAssinaturaDTO->getNumIdOrgaoUsuario());
  $strItensSelCargoFuncao = AssinanteINT::montarSelectCargoFuncaoUnidadeUsuarioRI1344('null','&nbsp;', $objAssinaturaDTO->getStrCargoFuncao(), $strIdUsuario);
  $strLinkAjaxCargoFuncao = SessaoSEI::getInstance()->assinarLink('controlador_ajax.php?acao_ajax=assinante_carregar_cargo_funcao');

  $strIdDocumentos = implode(',',$arrIdDocumentos);
  $strHashDocumentos = md5($strIdDocumentos);


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
?>

    #lblOrgao {position:absolute;left:0%;top:0%;}
    #selOrgao {position:absolute;left:0%;top:40%;width:40%;}

    #divContexto {<?=$strDisplayContexto?>}
    #lblContexto {position:absolute;left:0%;top:0%;}
    #selContexto {position:absolute;left:0%;top:40%;width:40%;}

    #lblUsuario {position:absolute;left:0%;top:0%;}
    #txtUsuario {position:absolute;left:0%;top:40%;width:60%;}

    #divAutenticacao {<?=$strDisplayAutenticacao?>}
    #pwdSenha {width:15%;}

    #lblCargoFuncao {position:absolute;left:0%;top:0%;}
    #selCargoFuncao {position:absolute;left:0%;top:40%;width:99%;}

    #lblOu {<?=((PaginaSEI::getInstance()->isBolIpad() || PaginaSEI::getInstance()->isBolAndroid())?'visibility:hidden;':'')?>}
    #lblCertificadoDigital {<?=((PaginaSEI::getInstance()->isBolIpad() || PaginaSEI::getInstance()->isBolAndroid())?'visibility:hidden;':'')?>}
    #divAjudaAssinaturaDigital {display:inline;<?=((PaginaSEI::getInstance()->isBolIpad() || PaginaSEI::getInstance()->isBolAndroid())?'visibility:hidden;':'')?>}
    #ancAjudaAssinaturaDigital {position:absolute;}

<?
PaginaSEI::getInstance()->fecharStyle();
PaginaSEI::getInstance()->montarJavaScript();
PaginaSEI::getInstance()->abrirJavaScript();
?>

    //<script>

    var objAutoCompletarUsuario = null;
    var objAjaxCargoFuncao = null;
    var bolAssinandoSenha = false;

    <?if ($objAssinaturaDTO->getStrStaFormaAutenticacao() == AssinaturaRN::$TA_CERTIFICADO_DIGITAL) {?>

    function verificarQuantidadeCertificados(){
        if (document.getElementById("assinador").getNumCertificados() == 1){
            assinarCertificadoDigital();
        }else if (document.getElementById("assinador").getNumCertificados() > 1){
            document.getElementById('btnAssinar').style.visibility = 'visible';
        }
    }

    function assinarCertificadoDigital(){
        applet = document.getElementById("assinador");
        var sucesso = false;
        document.getElementById('btnAssinar').style.visibility = 'hidden';

      <?=$strJSDocumentosAssinar?>

      <?=$strJSAssinaturas?>

        //setar propriedades para o assinador. isso permite que outros sistemas
        //possam usar o assinador passando os seus parametros
        properties = "strIdsAssinaturas=";
        for(var i=0;i < arrIdsAssinaturas.length;i++){
            if (i>0){
                properties += "|";
            }
            properties += arrIdsAssinaturas[i];
        }

        applet.jsSetPropertiesStr(properties);
        sucesso = applet.jsSignDocsListStr(strIdsDocumentosAssinar);

    }
    // funcao chamada pelo applet após este ser carregado
    function onFinishApplet(){
        infraOcultarAviso();
        verificarQuantidadeCertificados();
    }

    // funcao chamada pelo applet se retornar um erro tipo Exception
    function onErrorApplet(erro){
        //alert(erro);
    }

    //funcao chamada pelo applet depois de assinar e antes de fazer o submit
    function afterSign() {
        finalizar();
    }

    <?}?>

    function inicializar(){
      <?if ($numRegistros == 0){?>
        alert('Nenhum documento informado.');
        return;
      <?}?>

      <?if ($bolDocumentoNaoEncontrado){?>
        alert('Documento não encontrado.');
        return;
      <?}?>

        //se realizou assinatura
      <?if ($bolAssinaturaOK){ ?>

      <?if ($objAssinaturaDTO->getStrStaFormaAutenticacao() == AssinaturaRN::$TA_CERTIFICADO_DIGITAL) {?>
        infraExibirAviso(false);
      <?} else {?>
        finalizar();
      <?}?>

        return;

      <?}else{?>

        if (document.getElementById('selCargoFuncao').options.length==2){
            document.getElementById('selCargoFuncao').options[1].selected = true;
        }


        objAjaxCargoFuncao = new infraAjaxMontarSelect('selCargoFuncao','<?=$strLinkAjaxCargoFuncao?>');
        //objAjaxCargoFuncao.mostrarAviso = true;
        //objAjaxCargoFuncao.tempoAviso = 2000;
        objAjaxCargoFuncao.prepararExecucao = function(){

            if (document.getElementById('hdnIdUsuario').value==''){
                return false;
            }

            return 'id_usuario=' + document.getElementById('hdnIdUsuario').value;
        }

        objAutoCompletarUsuario = new infraAjaxAutoCompletar('hdnIdUsuario','txtUsuario','<?=$strLinkAjaxUsuarios?>');
        //objAutoCompletarUsuario.maiusculas = true;
        //objAutoCompletarUsuario.mostrarAviso = true;
        //objAutoCompletarUsuario.tempoAviso = 1000;
        //objAutoCompletarUsuario.tamanhoMinimo = 3;
        objAutoCompletarUsuario.limparCampo = true;
        //objAutoCompletarUsuario.bolExecucaoAutomatica = false;

        objAutoCompletarUsuario.prepararExecucao = function(){

            if (!infraSelectSelecionado(document.getElementById('selOrgao'))){
                alert('Selecione um Órgão.');
                document.getElementById('selOrgao').focus();
                return false;
            }

            return 'id_orgao=' + document.getElementById('selOrgao').value + '&palavras_pesquisa='+document.getElementById('txtUsuario').value + '&inativos=0';
        };

        objAutoCompletarUsuario.processarResultado = function(id,descricao,complemento){
            if (id!=''){
                document.getElementById('hdnIdUsuario').value = id;
                document.getElementById('txtUsuario').value = descricao;
                objAjaxCargoFuncao.executar();
                window.status='Finalizado.';
            }
        }

        //infraSelecionarCampo(document.getElementById('txtUsuario'));

      <? if($bolPermiteAssinaturaLogin) { ?>
        document.getElementById('pwdSenha').focus();
      <?}?>

      <?}?>
    }

    function OnSubmitForm() {

        if (!infraSelectSelecionado(document.getElementById('selOrgao'))){
            alert('Selecione um Órgão.');
            document.getElementById('selOrgao').focus();
            return false;
        }

        if (document.getElementById('selContexto').options.length > 0 &&  !infraSelectSelecionado(document.getElementById('selContexto'))){
            alert('Selecione um Contexto.');
            document.getElementById('selContexto').focus();
            return false;
        }

        if (infraTrim(document.getElementById('hdnIdUsuario').value)==''){
            alert('Informe um Assinante.');
            document.getElementById('txtUsuario').focus();
            return false;
        }

        if (!infraSelectSelecionado(document.getElementById('selCargoFuncao'))){
            alert('Selecione um Cargo/Função.');
            document.getElementById('selCargoFuncao').focus();
            return false;
        }

        if ('<?=$numRegistros?>'=='0'){
            alert('Nenhum documento informado para assinatura.');
            return false;
        }

        return true;
    }

    function trocarOrgaoUsuario(){
        objAutoCompletarUsuario.limpar();
        objAjaxCargoFuncao.executar();
    }
    <? if($bolPermiteAssinaturaLogin) { ?>
    function assinarSenha(){
        if (infraTrim(document.getElementById('pwdSenha').value)==''){
            alert('Senha não informada.');
            document.getElementById('pwdSenha').focus();
        }else{
            document.getElementById('hdnFormaAutenticacao').value = '<?=AssinaturaRN::$TA_SENHA?>';
            if (OnSubmitForm()){
                infraExibirAviso(false);
                document.getElementById('frmAssinaturas').submit();
                return true;
            }
        }
        return false;
    }

    function tratarSenha(ev){
        if (!bolAssinandoSenha && infraGetCodigoTecla(ev)==13){
            bolAssinandoSenha = true;
            if (!assinarSenha()){
                bolAssinandoSenha = false;
            }
        }
    }
    <? } ?>
    <? if($bolPermiteAssinaturaCertificado) { ?>
    function tratarCertificadoDigital(){
        document.getElementById('hdnFormaAutenticacao').value = '<?=AssinaturaRN::$TA_CERTIFICADO_DIGITAL?>';
        if (OnSubmitForm()){
            infraExibirAviso(false);
            document.getElementById('frmAssinaturas').submit();
        }
    }
    <? } ?>

    function finalizar(){

        //se realizou assinatura
      <?if ($bolAssinaturaOK){ ?>
        window.opener.location = '<?=$strLinkRetorno?>';
        self.setTimeout('window.close()',500);

      <?}?>
    }

    //</script>
<?
PaginaSEI::getInstance()->fecharJavaScript();
PaginaSEI::getInstance()->fecharHead();
PaginaSEI::getInstance()->abrirBody($strTitulo,'onload="inicializar();"');
?>

    <form id="frmAssinaturas" method="post" onsubmit="return OnSubmitForm();" action="<?=SessaoSEI::getInstance()->assinarLink('controlador.php?acao='.$_GET['acao'].'&acao_origem='.$_GET['acao'].'&id_retorno='.$_GET['id_retorno'].'&acao_retorno='.PaginaSEI::getInstance()->getAcaoRetorno().'&hash_documentos='.$strHashDocumentos.$strParametros)?>">

      <?
      //PaginaSEI::getInstance()->montarBarraLocalizacao($strTitulo);
      PaginaSEI::getInstance()->montarBarraComandosSuperior($arrComandos);
      //PaginaSEI::getInstance()->montarAreaValidacao();
      if ($numRegistros > 0){
        ?>


          <div id="divOrgao" class="infraAreaDados" style="height:4.5em;">
              <label id="lblOrgao" for="selOrgao" accesskey="r" class="infraLabelObrigatorio">Ó<span class="infraTeclaAtalho">r</span>gão do Assinante:</label>
              <select id="selOrgao" name="selOrgao" onchange="trocarOrgaoUsuario();" class="infraSelect" tabindex="<?=PaginaSEI::getInstance()->getProxTabDados()?>">
                <?=$strItensSelOrgaos?>
              </select>
          </div>
          <div id="divUsuario" class="infraAreaDados" style="height:4.5em;">
              <label id="lblUsuario" for="txtUsuario" accesskey="e" class="infraLabelObrigatorio">Assinant<span class="infraTeclaAtalho">e</span>:</label>
              <input type="text" id="txtUsuario" name="txtUsuario" class="infraText" value="<?= PaginaSEI::tratarHTML($strNomeUsuario) ?>" tabindex="<?=PaginaSEI::getInstance()->getProxTabDados()?>" />
              <input type="hidden" id="hdnIdUsuario" name="hdnIdUsuario" value="<?=$strIdUsuario?>" />
          </div>

          <div id="divCargoFuncao" class="infraAreaDados" style="height:4.5em;">
              <label id="lblCargoFuncao" for="selCargoFuncao" accesskey="F" class="infraLabelObrigatorio">Cargo / <span class="infraTeclaAtalho">F</span>unção:</label>
              <select id="selCargoFuncao" name="selCargoFuncao" class="infraSelect" tabindex="<?=PaginaSEI::getInstance()->getProxTabDados()?>">
                <?=$strItensSelCargoFuncao?>
              </select>
          </div>
          <br />
          <div id="divAutenticacao" class="infraAreaDados" style="height:2.5em;">
            <? if($bolPermiteAssinaturaLogin) { ?>
                <label id="lblSenha" for="pwdSenha" accesskey="S" class="infraLabelRadio infraLabelObrigatorio" tabindex="<?=PaginaSEI::getInstance()->getProxTabDados()?>"><span class="infraTeclaAtalho">S</span>enha</label>&nbsp;&nbsp;
                <input type="password" id="pwdSenha" name="pwdSenha" autocomplete="off" class="infraText" onkeypress="return tratarSenha(event);" value="" tabindex="<?=PaginaSEI::getInstance()->getProxTabDados()?>" />&nbsp;&nbsp;&nbsp;&nbsp;
            <? }
            if($bolPermiteAssinaturaLogin && $bolPermiteAssinaturaCertificado) { ?>
                <label id="lblOu" class="infraLabelOpcional" tabindex="<?=PaginaSEI::getInstance()->getProxTabDados()?>">ou</label>&nbsp;&nbsp;&nbsp;
            <? }
            if($bolPermiteAssinaturaCertificado) { ?>
                <label id="lblCertificadoDigital" onclick="tratarCertificadoDigital();" accesskey="" for="optCertificadoDigital" class="infraLabelRadio infraLabelObrigatorio" tabindex="<?=PaginaSEI::getInstance()->getProxTabDados()?>"><?=((!$bolPermiteAssinaturaLogin)?(!$bolAutenticacao?'Assinar com ':'Autenticar com '):'')?>Certificado Digital</label>&nbsp;
                <div id="divAjudaAssinaturaDigital"><a id="ancAjudaAssinaturaDigital" href="<?=SessaoSEI::getInstance()->assinarLink('controlador.php?acao=assinatura_digital_ajuda&acao_origem='.$_GET['acao'])?>" target="janAjudaAssinaturaDigital" title="Instruções para Configuração da Assinatura Digital" tabindex="<?=PaginaSEI::getInstance()->getProxTabDados()?>"><img src="<?=PaginaSEI::getInstance()->getDiretorioImagensLocal()?>/sei_informacao.svg" class="infraImg" /></a></div>
            <? } ?>
          </div>

        <?=$strDivCertificacao?>
        <?
      }
      //PaginaSEI::getInstance()->fecharAreaDados();
      PaginaSEI::getInstance()->montarAreaDebug();
      //PaginaSEI::getInstance()->montarBarraComandosInferior($arrComandos);
      ?>
        <input type="hidden" id="hdnFormaAutenticacao" name="hdnFormaAutenticacao" value="" />
        <input type="hidden" id="hdnLinkRetorno" name="hdnLinkRetorno" value="<?=$strLinkRetorno?>" />
        <input type="hidden" id="hdnFlagAssinatura" name="hdnFlagAssinatura" value="1" />
        <input type="hidden" id="hdnIdDocumentos" name="hdnIdDocumentos" value="<?=$strIdDocumentos?>" />
    </form>
<?
PaginaSEI::getInstance()->fecharBody();
PaginaSEI::getInstance()->fecharHtml();
?>