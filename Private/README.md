# Private

A parte pública do website deve exibir um botão/link Login na barra de navegação (à direita). Quando o usuário clicar nesse botão e efetuar login com sucesso, o website deverá abrir, em uma nova aba, a página principal da parte restrita do sistema. A parte restrita do website deverá ter uma barra de navegação diferente daquela feita para a parte pública, pois deverá exibir as opções: **Novo Funcionário**, **Novo Paciente**, **Listar Funcionários**, **Listar Pacientes**, **Listar Endereços**, **Listar todos Agendamentos** e **Listar meus Agendamentos** (esta última opção deverá aparecer apenas caso o funcionário logado seja um médico).

---

## Cadastro de Funcionário

Um formulário adequado deve ser exibido para cadastrar os seguintes dados básicos de todos os funcionários: nome, email, telefone, CEP, logradouro, bairro, cidade, estado, data de início do contrato de trabalho, salário e senha. Caso o funcionário seja um médico, também devem ser cadastrados os dados: Especialidade e CRM. A página de cadastro deve exibir os campos Especialidade e CRM apenas quando o usuário escolher uma opção do tipo “funcionário médico” (esses campos devem ficar inicialmente ocultos e devem ser apresentados dinamicamente utilizando JavaScript quando essa opção for selecionada). Os dados devem ser inseridos adequadamente utilizando o conceito de generalização/especialização nas tabelas Pessoa, Funcionário e Médico do banco de dados com as devidas chaves primárias e estrangeiras por um script PHP. A operação deve ser feita como uma transação no banco de dados utilizando os conceitos de commit/roolback. O preenchimento dos campos de endereço do funcionário deve ser facilitado utilizando AJAX. Assim que o usuário preencher o CEP, uma requisição AJAX deve buscar no próprio servidor, na tabela “Base de Endereços AJAX”, os demais dados do endereço relativo ao CEP indicado (logradouro, bairro, cidade e estado). Um código JavaScript deverá completar os campos do formulário automaticamente conforme dados retornados pela requisição. Se o CEP não estiver cadastrado no servidor, os campos não precisam ser preenchidos automaticamente.

**OBS 1**: esta funcionalidade deve ser implementada. Serviços/APIs de busca de endereço de terceiros não devem ser utilizadas. Caso seja, o recurso será desconsiderado na avaliação do trabalho.

**OBS 2**: os dados do endereço do funcionário precisam ser inseridos normalmente na tabela Pessoa. Não faça qualquer ligação com os dados de endereço cadastrados na tabela “Base de Endereços AJAX”. Esta tabela é apenas auxiliar e tem o único objetivo de prover o serviço de busca de endereço pelo CEP para facilitar o preenchimento do formulário.

## Cadastro de Paciente

A página de cadastro de paciente deve oferecer um formulário para cadastro das seguintes informações do paciente: nome, email, telefone, CEP, logradouro, bairro, cidade, estado, peso, altura e tipo sanguíneo. Os dados devem ser inseridos adequadamente no banco de dados nas tabelas Pessoa e Paciente por um script PHP. A operação deve ser feita como uma transação.

## Listagens dos Dados

A parte restrita deverá apresentar opções para que o funcionário possa visualizar de maneira estrutura os dados cadastrados. A listagem pode ser feita utilizando tabelas em HTML ou a estrutura de grade do Bootstrap.

• Listagem dos funcionários cadastrados;

• Listagem dos pacientes cadastrados;

• Listagem dos endereços auxiliares AJAX cadastrados;

• Listagem de todos os agendamentos de consultas realizados pelos clientes;

• Listagem dos agendamentos de consultas apenas do funcionário logado caso ele seja um médico.
