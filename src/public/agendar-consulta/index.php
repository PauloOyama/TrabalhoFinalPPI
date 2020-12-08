<?php
include_once "../../db.php";
include_once "../../common.php";

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $expected_keys = ["data_agendamento", "horario", "nome", "email", "telefone", "id_medico"];
    validate_keys($expected_keys, $_POST);

    extract($_POST);

    $sql = <<<SQL
    INSERT INTO agenda (data_agendamento, horario, nome, email, telefone, codigo_medico)
    VALUES (?, ?, ?, ?, ?, ?)
    SQL;
    run_sql($pdo, $sql, [$data_agendamento, $horario, $nome, $email, $telefone, $id_medico]);
}
?>
<!DOCTYPE html>
<html lang="pt-BR">
   <head>
      <meta charset="UTF-8">
      <meta name="viewport" content="width=device-width, initial-scale=1.0">
      <link rel="stylesheet" href="/utils.css">
      <link rel="stylesheet" href="../css's/novo_endereco.css">
      <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.0-alpha3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-CuOF+2SnTUfTwSZjCXf01h7uYhfOBuxIhGKPbfEJ3+FqH/s6cIFN9bGr1HmAg4fQ" crossorigin="anonymous">
      <title>Agendar Consulta</title>
   </head>
   <body>
      <nav class="navbar navbar-expand-lg navbar-dark bg-dark fixed-top ">
         <div class="container">
            <img src="/public/svg/cardiograma.svg" alt="Logo" class="logo" />
            <h2 class="navbar-brand">Clínica São Miguel</h2>
            <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarResponsive" aria-controls="navbarResponsive" aria-expanded="false" aria-label="Toggle navigation">
            <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse" id="navbarResponsive">
               <ul class="navbar-nav ml-auto">
                  <li class="nav-item active">
                     <a class="nav-link" href="/public/#quemSomos">Conheça-nos
                     </a>
                  </li>
                  <li class="nav-item">
                     <a class="nav-link" href="/public/#visao">Visão</a>
                  </li>
                  <li class="nav-item">
                     <a class="nav-link" href="/public/#valores">Valores</a>
                  </li>
                  <li class="nav-item dropdown">
                     <a class="nav-link dropdown-toggle" id="navbarDropdown" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                     Menu
                     </a>
                     <div class="dropdown-menu dropdown-menu-right" aria-labelledby="navbarDropdown">
                        <a class="dropdown-item" href="/public/login/">Login</a>
                        <a class="dropdown-item " href="/public/novo-endereco/">Cadastro de Endereço</a>
                        <a class="dropdown-item " href="/public/galeria/">Galeria</a>
                        <a class="dropdown-item " href="/public/agendar-consulta/">Agendar Consulta</a>
                     </div>
                  </li>
               </ul>
            </div>
         </div>
      </nav>
      <div class="container topics">
         <main id="homeMain">
            <h2 class="centralizaX">Consulta</h2>
            <form class="row gx-2 gy-4" action="./" method="POST" name="Cadastro">
               <div class="col-md-4">
                  <label for="especialidade" class="form-label">Especialidade médica</label>
                  <select name="especialidade" id="especialidade" class="form-select" required>
                     <option disabled selected value="">Carregando Opções...</option>
                  </select>
               </div>
               <div class="col-md-8">
                  <label for="medico" class="form-label">Nome do médico especialista</label>
                  <select name="id_medico" id="medico" class="form-select" disabled required>
                     <option disabled selected value="">--</option>
                  </select>
                  <span>Selecione uma especialidade primeiro</span>
               </div>
               <div class="col-md-4">
                  <label for="data" class="form-label">Data</label>
                  <input type="date" class="form-control" id="data" name="data_agendamento" disabled required><span>Selecione o médico primeiro</span>
               </div>
               <div class="col-md-2">
                  <label for="horarioDisponivel" class="form-label">Horário para à consulta</label>
                  <select name="horario" id="horarioDisponivel" class="form-select" disabled required>
                     <option disabled selected value="">--</option>
                  </select>
                  <span>Selecione uma data primeiro</span>
               </div>
               <h2 class="centralizaX">Dados do Paciente</h2>
               <div class="col-md-6 ">
                  <label for="nome" class="form-label">Nome</label>
                  <input type="text" class="form-control" id="nome" name="nome" autocomplete="off" required><span></span>
               </div>
               <div class="col-md-6 ">
                  <label for="email" class="form-label">Email</label>
                  <input type="email" class="form-control" id="email" placeholder="Ex: arel@vidal.com" name="email" autocomplete="off" required><span></span>
               </div>
               <div class="col-md-4 ">
                  <label for="telefone" class="form-label">Telefone</label>
                  <input type="tel" class="form-control" id="telefone" name="telefone" placeholder="Ex: (xx) xxxxx-xxxx" pattern="\([0-9]{2}\) [0-9]{5}-[0-9]{4}" autocomplete="off" required><span></span>
               </div>
               <div class="col-md-12">
                  <button type="submit" class="btn" style="background-color: #f34213;color: #ffffff">
                  Agendar
                  </button>
               </div>
            </form>
            <div class="modal fade" id="staticBackdrop" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" aria-hidden="true">
               <div class="modal-dialog">
                  <div class="modal-content">
                     <div id="modal-text" class="modal-body">
                     </div>
                     <div class="modal-footer">
                        <button type="button" class="btn" data-bs-dismiss="modal" id="modal-close" style="background-color: #f34213;color: #ffffff">Close</button>
                     </div>
                  </div>
               </div>
            </div>
         </main>
      </div>
      <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.0-alpha3/dist/js/bootstrap.bundle.min.js" integrity="sha384-popRpmFF9JQgExhfw5tZT4I9/CI5e2QcuUZPOVXb1m7qUmeR2b50u+YFEYe1wgzy" crossorigin="anonymous"></script>
      <script>
         // Usa requisições dinâmicas para preencher a lista de especialidades
         function preencheEspecialidades() {
             let especialidades;
             let httpReq = new XMLHttpRequest();
             httpReq.responseType = "json";
         
             httpReq.onreadystatechange = function() {
                 if (httpReq.readyState === XMLHttpRequest.DONE) {  // Ações a serem feitas assim que a requição estiver completas
                     let especialidadesForm = document.getElementById("especialidade");
                     let opcaoNull = document.createElement("option");
         
                     especialidades = httpReq.response;
                     especialidadesForm.innerHTML = "";
                     opcaoNull.setAttribute("selected", ""); opcaoNull.setAttribute("disabled", "");
                     opcaoNull.innerText = "Selecione"
                     especialidadesForm.appendChild(opcaoNull)
         
                     especialidades.forEach(especialidade => {
                         let novaOpcao = document.createElement("option");
                         novaOpcao.setAttribute("value", especialidade);
                         novaOpcao.innerText = especialidade;
                         especialidadesForm.appendChild(novaOpcao);
                     })
                 }
             }
         
             httpReq.open("GET", "./lista-especialidades.php");
             httpReq.send();
         }
         
         
         // Preenche o nome dos médicos com base na especialidade listada
         function preencheMedicos(event) {
             let medicosForm = document.getElementById("medico");
             let httpReq = new XMLHttpRequest();
             let medicos;
         
             httpReq.responseType = "json";
             medicosForm.nextElementSibling.innerHTML = ""; // limpa o <span>
             medicosForm.removeAttribute("disabled");
         
             httpReq.onreadystatechange = function() {
                 if (httpReq.readyState === XMLHttpRequest.DONE) {
                     medicos = httpReq.response;
                     medicosForm.innerHTML = "";
         
                     medicos.forEach(medico => {
                         let novaOpcao = document.createElement("option");
                         novaOpcao.setAttribute("value", medico["codigo"]);
                         novaOpcao.innerText = medico["nome"];
                         medicosForm.appendChild(novaOpcao);
                     })
         
                     let dataForm = document.getElementById("data");
                     dataForm.removeAttribute("disabled");
                     dataForm.nextElementSibling.innerHTML = ""; // limpa o <span>
                 }
             }
         
             httpReq.open("POST", "./lista-especialistas.php");
             let data = new FormData();
             data.append("especialidade", event.target.value);
             httpReq.send(data);
         }
         
         
         function preencheHorario(event) {
             let horarios_invalidos;
             let httpReq = new XMLHttpRequest();
             httpReq.responseType = "json";
             httpReq.onreadystatechange = function() {
                 if (httpReq.readyState === XMLHttpRequest.DONE) {
                     let horarioForm = document.getElementById("horarioDisponivel");
                     horarioForm.nextElementSibling.innerHTML = ""; // limpa o <span>
                     horarioForm.innerHTML = "";
                     horarioForm.removeAttribute("disabled");
                     horarios_invalidos = httpReq.response;
                     let horarios_validos = ["08:00:00", "09:00:00", "10:00:00", "11:00:00", "12:00:00", "13:00:00", "14:00:00", "15:00:00", "16:00:00", "17:00:00"]
         
                     // Usa o filter para remover todos horarios invalidos do array de horarios válidos e associa à variável horários_filtrados
                     let horarios_filtrados = horarios_validos.filter(function (element) {
                         return this.indexOf(element) < 0;
                     }, horarios_invalidos)
         
                     horarios_filtrados.forEach(element => {
                         let novaOpcao = document.createElement("option");
                         novaOpcao.setAttribute("value", element);
                         novaOpcao.innerText = element;
                         horarioForm.appendChild(novaOpcao);
                     })
         
                 }
             }
         
             let dados = new FormData();
             dados.append("id_medico", document.getElementById("medico").value);
             dados.append("data", event.target.value);
             httpReq.open("POST", "./lista-horarios-ocupados.php");
             httpReq.send(dados);
         }
         
         function sendForm(event) {
             event.preventDefault();
             let formDados = new FormData(event.target);
             let modal = new bootstrap.Modal(document.getElementById("staticBackdrop"), {backdrop: 'static'});
         
         
             let httpReq = new XMLHttpRequest();
             httpReq.onreadystatechange = function() {
                 if (httpReq.readyState === XMLHttpRequest.DONE) {
                     if (httpReq.status === 200)
                         document.getElementById("modal-text").innerText = "Consulta agendada com sucesso";
                     else
                         document.getElementById("modal-text").innerText = "Ocorreu um erro inesperado";
                     document.getElementById("modal-close").addEventListener("click", event => {
                         window.location = window.location;
                     })
                     modal.show();
                 }
             }
         
             httpReq.open("POST", "./");
             httpReq.send(formDados);
         }
         
         window.onload = () => {
             document.getElementById("especialidade").addEventListener("change", preencheMedicos);
             document.getElementById("data").addEventListener("change", preencheHorario);
             preencheEspecialidades();
             document.querySelector("form").addEventListener("submit", sendForm);
         }
      </script>
   </body>
</html>
