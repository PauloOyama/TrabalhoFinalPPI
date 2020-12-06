# Public

A parte do website voltada para o público em geral deve conter:

1. Uma página principal para exibição das informações da clínica (Home);

2. Uma página para exibição de fotos da clínica (Galeria - utilize fotos fictícias);

3. Uma página para cadastro de endereços (Novo Endereço);

4. Uma página de login;

5. Uma página para que os clientes possam realizar o agendamento de consultas médicas (Agendamento).

---

## Pagina Home

Cada grupo deverá criar um nome fictício para a clínica. A página principal do website deverá exibir, de maneira elegante e bem organizada, os seguintes dados sobre a clínica (fique à vontade para elaborar um texto fictício para cada item):

• Nome da clínica;

• Breve descrição;

• Sua missão;

• Seus Valores;

• Uma foto principal ou logomarca.

## Página Galeria

Esta página deve apresentar fotos, logos, etc., relacionados à clínica médica. O layout é livre.

## Página Novo Endereço

Deve apresentar um formulário para cadastro de endereço contendo os seguintes campos: CEP, logradouro, bairro, cidade e estado. Os dados devem ser inseridos adequadamente na tabela “Base de Endereços AJAX” do banco de dados, conforme esquema apresentado no final deste documento.

## Página de Login

A página de login deverá exibir um pequeno formulário com os campos e-mail e senha para que os funcionários da clínica possam logar e ter acesso à parte restrita do website. O formulário de login deve ser apresentado de maneira bem estruturada e elegante, conforme exercício feito anteriormente no trabalho de CSS. A validação dos dados de login deve ser feita em segundo plano, com AJAX. Mensagens adequadas devem ser apresentadas caso os dados estejam incorretos. Para validar os dados de login utilize o campo E-mail da tabela Pessoa juntamente com o campo SenhaHash da tabela Funcionário (veja diagrama no final do documento).

## Agendamento de Consulta

O procedimento de agendamento de consulta médica deve ser realizado explorando a tecnologia AJAX. O formulário de agendamento deve ser responsivo e deve se apresentar de maneira amigável, contendo os seguintes campos e funcionalidades:

**Especialidade médica desejada**. Campo do tipo select listando as especialidades disponíveis dinamicamente, de acordo com os médicos correntemente cadastrados no banco de dados. Não deve aparecer neste select uma especialidade médica sem que tenha um respectivo médico cadastrado.

**Nome do médico especialista**. Campo do tipo select que deverá ser carregado dinamicamente com AJAX depois que a especialidade médica for selecionada (uma requisição assíncrona deve buscar no servidor os nomes dos médicos cadastrados naquela especialidade);

**Data da consulta**. Campo do tipo date para que o usuário escolha a data do agendamento;

**Horário disponível para consulta**. Campo do tipo select para que o usuário escolha uma opção de horário disponível na data previamente selecionada. Os horários de agendamento devem ser exatos, no período das 8h às 17h (exemplos: 8, 9, 10, 11, etc.). Quando a data da consulta for informada, uma requisição AJAX deverá buscar no banco de dados todos os horários já agendados para o médico em questão na data selecionada. Ao receber o retorno do servidor, um código JavaScript deverá filtrar as opções de horários eliminando os horários já agendados anteriormente e preenchendo o campo select apenas com os horários “disponíveis” (para simplificar, basta montar uma lista com todos os inteiros no intervalo de 8 a 17 e posteriormente remover os números “ocupados” de acordo com a lista de horários ocupados retornados pela requisição AJAX). O formulário de agendamento deve possuir também uma região para que o paciente possa informar os seus dados pessoais essenciais: nome, e-mail e telefone.

Os dados do agendamento devem ser armazenados de maneira adequada em uma tabela de nome Agenda do banco de dados, conforme apresentado no esquema de dados no final deste documento. Para fins de simplificação, os dados do usuário que está realizando o agendamento (nome, e-mail e telefone) devem ser armazenados na própria tabela Agenda. Suponha que uma equipe interna da clínica posteriormente fará o cadastro formal do paciente utilizando a respectiva funcionalidade da parte restrita do website
