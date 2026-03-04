# Módulo SEI Correios

## Requisitos
- Requisito Mínimo é o SEI 5.0.3 instalado/atualizado - Não é compatível com versões anteriores e em versões mais recentes é necessário conferir antes se possui compatibilidade.
   - Verificar valor da constante de versão no arquivo /sei/web/SEI.php ou, após logado no sistema, parando o mouse sobre a logo do SEI no canto superior esquerdo.
- Antes de executar os scripts de instalação/atualização, o usuário de acesso aos bancos de dados do SEI e do SIP, constante nos arquivos ConfiguracaoSEI.php e ConfiguracaoSip.php, deverá ter permissão de acesso total ao banco de dados, permitindo, por exemplo, criação e exclusão de tabelas.
- Os códigos-fonte do Módulo podem ser baixados a partir do link a seguir, devendo sempre utilizar a versão mais recente: [https://github.com/anatelgovbr/mod-sei-correios/releases](https://github.com/anatelgovbr/mod-sei-correios/releases "Clique e acesse")
- Se já tiver instalado versão principal com a execução dos scripts de banco do módulo no SEI e no SIP, **em versões intermediárias basta sobrescrever os códigos** e não precisa executar os scripts de banco novamente.
   - Atualizações apenas de código são identificadas com o incremento apenas do terceiro dígito da versão (p. ex. v4.1.1, v4.1.2) e não envolve execução de scripts de banco.

## Procedimentos para Instalação
1. Fazer backup dos bancos de dados do SEI e do SIP.
2. Carregar no servidor os arquivos do módulo nas pastas correspondentes nos servidores do SEI e do SIP.
   - **Caso se trate de atualização de versão anterior do Módulo**, antes de copiar os códigos-fontes para a pasta "/sei/web/modulos/correios", é necessário excluir os arquivos anteriores pré existentes na mencionada pasta, para não manter arquivos de códigos que foram renomeados ou descontinuados.
3. Editar o arquivo "/sei/config/ConfiguracaoSEI.php", tomando o cuidado de usar editor que não altere o charset do arquivo, para adicionar a referência à classe de integração do módulo e seu caminho relativo dentro da pasta "/sei/web/modulos" na array 'Modulos' da chave 'SEI':

		'SEI' => array(
			...
			'Modulos'=>array(
				'CorreiosIntegracao' => 'correios',
				),
			),

4. Antes de seguir para os próximos passos, é importante conferir se o Módulo foi corretamente declarado no arquivo "/sei/config/ConfiguracaoSEI.php". Acesse o menu **Infra > Módulos** e confira se consta a linha correspondente ao Módulo, pois, realizando os passos anteriores da forma correta, independente da execução do script de banco, o Módulo já deve ser reconhecido na tela aberta pelo menu indicado.
5. Rodar o script de banco "/sip/scripts/sip_atualizar_versao_modulo_correios.php" em linha de comando no servidor do SIP, verificando se não houve erro em sua execução, em que ao final do log deverá ser informado "FIM". Exemplo de comando de execução:

		/usr/bin/php -c /etc/php.ini /opt/sip/scripts/sip_atualizar_versao_modulo_correios.php > atualizacao_correio_sip.log

6. Rodar o script de banco "/sei/scripts/sei_atualizar_versao_modulo_correios.php" em linha de comando no servidor do SEI, verificando se não houve erro em sua execução, em que ao final do log deverá ser informado "FIM". Exemplo de comando de execução:

		/usr/bin/php -c /etc/php.ini /opt/sei/scripts/sei_atualizar_versao_modulo_correios.php > atualizacao_modulo_correios_sei.log

7. **IMPORTANTE**: Na execução dos dois scripts de banco acima, ao final deve constar o termo "FIM", o "TEMPO TOTAL DE EXECUÇÃO" e a informação de que a instalação/atualização foi realizada com sucesso na base de dados correspondente (SEM ERROS). Do contrário, o script não foi executado até o final e algum dado não foi inserido/atualizado no respectivo banco de dados, devendo recuperar o backup do banco e repetir o procedimento.
   - Constando ao final da execução do script as informações indicadas, pode logar no SEI e SIP e verificar no menu **Infra > Parâmetros** dos dois sistemas se consta o parâmetro "VERSAO_MODULO_CORREIOS" com o valor da última versão do módulo.
8. Em caso de erro durante a execução do script, verificar (lendo as mensagens de erro e no menu Infra > Log do SEI e do SIP) se a causa é algum problema na infraestrutura local ou ajustes indevidos na estrutura de banco do core do sistema. Neste caso, após a correção, deve recuperar o backup do banco pertinente e repetir o procedimento, especialmente a execução dos scripts de banco indicados acima.
9. Após a execução com sucesso, com um usuário com permissão de Administrador no SEI, seguir os passos dispostos no tópico "Orientações Negociais" mais abaixo.
10. Para o funcionamento correto do Módulo SEI Correios é necessário a instalação da biblioteca PHP "ImageMagick" e "ImageMagick-devel" em cada nó de aplicação do SEI, conforme comandos abaixo:

		Execute a linha de comando "yum install -y ImageMagick ImageMagick-devel"
		Execute a linha de comando "pecl install imagick"
		Modifique o arquvivo "/etc/php.ini", incluindo a linha "extension=imagick.so" no final da seção "Dynamic Extensions"
11. Para o funcionamento do Processamento de Retorno do AR é necessário a instalação do kit de ferramenta Zbar. Exemplo de instalação para uso no Sistema Operacional Centos:
    
        yum install zbar
## Orientações Negociais
1. Imediatamente após a instalação com sucesso, com usuário com permissão de "Administrador" do SEI, acessar os menus de administração do Módulo pelo seguinte caminho: Administração > Correios. Somente com tudo parametrizado adequadamente será possível o uso do módulo.
2. O script de banco do SIP já cria todos os Recursos e Menus e os associam automaticamente aos Perfis "Básico", "Administrador" e "Expedição Correios".
	- Independente da criação de outros Perfis, os recursos indicados para o Perfil "Básico", "Administrador" e "Expedição Correios" devem manter correspondência com os Perfis dos Usuários internos que utilizarão o Módulo e dos Usuários Administradores do Módulo.
	- Tão quanto ocorre com as atualizações do SEI, versões futuras deste Módulo continuarão a atualizar e criar Recursos e associá-los apenas aos Perfis "Básico", "Administrador" e "Expedição Correios".
	- Todos os recursos do Módulo iniciam pelo prefixo **"md_cor_"**.
	- Não foi possível ainda elaborar Manuais do módulo. Contudo, é importante ler o resumo sobre cada funcionalidade abaixo para poder entender o funcionamento do módulo e poder parametrizá-lo da forma correta.
3. Funcionalidades do Módulo SEI Correios:
	- 3.1. Administração:
		- Correios > Contratos e Serviços Postais:
			- Cadastra o Contrato que o órgão possui com os Correios e pelo menos o Tipo de Embalagem "Envelope".
			- Na tela de Cadastro do Contrato deve informar todos os campos sobre o Contrato junto aos Correios, especialmente os Números de Contrato e Postagem informado pelos Correios ao Órgão para que as integrações funcionem.
				- [Acesse o Link dos Correios](https://www.correios.com.br/atendimento/developers "Acesso à documentação das API's") para acesso à documentação sobre o uso das API's dos Correios.
				- Caso não tenha usuário no ambiente de Homologação, acesse [https://cwshom.correios.com.br](https://cwshom.correios.com.br), clicar na opção "Cadastrar" e informar os dados solicitados.
				- Caso não tenha usuário no ambiente de Produção, acesse [https://cws.correios.com.br](https://cws.correios.com.br) e realizar o mesmo procedimento feito no ambiente de Homologação.
				- Caso tenha dúvidas, entrar em contato com o agente comercial dos Correios que atende o Órgão. 
			- Deve deixar na lista de Serviços Postais somente os serviços que quer que fiquem disponíveis para uso, sendo o mais tradicional o serviço "CARTA COM A FATURAR SELO E SE", tipo "Carta Registrada", Expedido com AR "Sim", Descrição Amigável "Correspondência Registrada".
				- Remover os serviços que não for utilizar.
				- Os serviços mantidos na lista deverão depois serem mapeados com as unidades no menu Administração > Correios > Mapeamento Unidades e Serviços Postais.
		- Tipos de Documentos de Expedição:
			- Tela onde indica os Tipos de Documentos gerados no SEI que tem indicação de Destinatário e que, depois de assinado, aparecerá o botão para "Solicitar Expedição pelos Correios". Tradicionalmente é usado o tipo de documento "Ofício" nos órgãos.
			- Serão listados os Tipos de Documentos que na Administração do SEI possuem indicação de preenchimento de Destinatário.
		- Unidades Expedidoras:
			- Tela onde cadastra as Unidades Expedidoras que vão realizar a validação da Expedição do Correios.
		- Mapeamento de Unidades Expedidoras e Unidades Solicitantes:
			- Tela onde vincula a Unidade Expedidora com as Unidades Solicitantes que vão pode solicitar a Expedição do Correios.
		- Mapeamento de Unidades Solicitantes e Serviços Postais:
			- Tela onde vincula as Unidades Solicitantes com os Serviços Postais já cadastrados.
		- Extensões para Gravação em Mídia:
			- Tela onde cadastra as Extensões Permitidas de Arquivos para Gravação em Mídia.
		- Parâmetros para Retorno da AR:
			- Tela onde indica os Parâmetros para Retorno da AR.
		- Mapeamento das Integrações:
			- A Tela inicial é a listagem das integrações cadastradas após a execução do Script de instalação do módulo. 
			Para uso inicial das integrações é necessário cadastrar usuário, senha e token correspondente ao ambiente utilizado.
			O Authorization a ser usado na API **Token** pode ser recuperado no ambiente de [Homologação](https://cwshom.correios.com.br) ou [Produção](https://cws.correios.com.br) após seguir as instruções da documentação do uso das API's mencionada no item
			3 > 3.1 Administração.
			- Atenção: conforme disposto nos Manuais dos Correios, os códigos de Rastreio de Objeto ficam disponíveis para consulta apenas por 180 dias, inclusive na página na Internet de Rastreio de Objetos dos Correios.
			- Inclusive, somente com códigos de rastreio já existentes/reais é que o rastreio de objetos funciona, inclusive em outros ambientes internos do Órgão. Assim, para testes, tem que pegar códigos de rastreios reais recentes (menos de 180 dias) e incluir manualmente pelo banco do módulo para que possa testar.
		- Tipos de Situações SRO:
			- Tela onde lista os Tipos de Situações SRO vinculados ao Serviço.
		- Destinatários não Habilitados para Expedição:
			- Tela onde cadastrado os Contatos que não são Destinatários Habilitados para Expedição.
	- 3.2. Unidade de Expedição:
		- Expedição pelos Correios:
		    - Nomenclatura PLP = Identificador para **Pré lista de Pré-Postagens**
			- Gerar Pré-Postagem: 
				- Tela onde lista as solicitações de expedições realizadas pelos Usuários e gera a PLP(pré-lista de postagem), sendo possível selecionar o "Formato de Expedição do Objeto" e visualizar a "Solicitação de Expedição" cadastrada.
			- Expedir Pré-Postagem:
				- Lista as PLPs(pré-lista de postagem) geradas para expedição e realiza o "Expedir PLP".
					- Antes de "Concluir a Expedição da PLP" e possível Imprimir os Documentos, Envelopes, ARs e Voucher da PLP.
			- Consultar PLPs Geradas:
				- Tela onde lista as PLPs Geradas e visualiza o detalhamento é sendo possível Imprimir os Documentos, Envelopes, ARs e Voucher da PLP.
			- Processamento de Retorno de AR:
				- Tela onde Lista o Processamento de Retorno de AR e realiza o processamento em lote.
			- ARs Pendentes de Retorno:
				- Tela onde lista os ARs Pendentes de Retorno e "Gerar Documento de Cobrança" vinculado aos dias em atraso do processo.
	- 3.3 Relatórios:
		- Correios:
			- Expedições Solicitadas pela Unidade:
				- Tela onde lista as Expedições Solicitadas pela Unidade.
	- 3.4. Usuários:
		- Iniciar Processo > Ofício > Solicitar Expedição pelos Correios:
			- Solicitar Expedição pelos Correios:
				- Após iniciar um Processo e vincular um documento do tipo "Ofício" é realizar a assinatura do documento será exibido o icone "Solicitar Expedição pelos Correios".
				- Na tela de "Solicitar Expedição pelos Correios" é possível alterar os dados dos "Documentos Expedidos" e preencher o "Formato de Expedição dos Documentos" é incluir uma "Observação".

## Erros ou Sugestões
1. [Abrir Issue](https://github.com/anatelgovbr/mod-sei-correios/issues) no repositório do GitHub do módulo se ocorrer erro na execução dos scripts de banco do módulo no SEI ou no SIP acima.
2. [Abrir Issue](https://github.com/anatelgovbr/mod-sei-correios/issues) no repositório do GitHub do módulo se ocorrer erro na operação do módulo.
3. Na abertura da Issue utilizar o modelo **"1 - Reportar Erro"**.

