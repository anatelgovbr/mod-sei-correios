<?
/**
* TRIBUNAL REGIONAL FEDERAL DA 4ª REGIÃO
*
* 03/07/2018 - criado por augusto.cast
*
* Versão do Gerador de Código: 1.41.0
*/

require_once dirname(__FILE__) . '/../../../SEI.php';

class MdCorParamArInfrigenDTO extends InfraDTO {

  public function getStrNomeTabela() {
  	 return 'md_cor_adm_par_ar_infrigen';
  }

  public function montar() {

    $this->adicionarAtributoTabela(InfraDTO::$PREFIXO_NUM, 'IdMdCorParamArInfrigencia', 'id_md_cor_param_ar_infrigencia');

    $this->adicionarAtributoTabela(InfraDTO::$PREFIXO_NUM, 'IdMdCorParametroAr', 'id_md_cor_parametro_ar');

    $this->adicionarAtributoTabela(InfraDTO::$PREFIXO_STR, 'MotivoInfrigencia', 'motivo_infrigencia');

    $this->adicionarAtributoTabela(InfraDTO::$PREFIXO_STR, 'SinAtivo', 'sin_ativo');

    $this->adicionarAtributoTabela(InfraDTO::$PREFIXO_STR, 'SinInfrigencia', 'sin_infrigencia');


    //
    $this->adicionarAtributoTabelaRelacionada(InfraDTO::$PREFIXO_STR, 'SinStatusRetornoDocumento','rad.sin_status','md_cor_retorno_ar_doc rad');

    $this->configurarExclusaoLogica('SinAtivo', 'N');

    $this->configurarPK('IdMdCorParamArInfrigencia',InfraDTO::$TIPO_PK_NATIVA);

    $this->configurarFK('IdMdCorParamArInfrigencia','md_cor_retorno_ar_doc rad','rad.id_md_cor_param_ar_infrigencia',  InfraDTO::$TIPO_FK_OPCIONAL);

  }
}
