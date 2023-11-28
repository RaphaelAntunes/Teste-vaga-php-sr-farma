<!DOCTYPE html>
<html>
<head>
    <!-- Inclua as bibliotecas e os estilos necessários aqui -->
</head>
<body>
    <div class="container">
        <!-- ... Seu HTML existente aqui ... -->

        <table class="table table-striped table-hover">
            <thead>
                <!-- ... Cabeçalho da tabela ... -->
            </thead>
            <tbody id="eventTableBody">
                <!-- Linhas da tabela serão adicionadas aqui -->
            </tbody>
        </table>
    </div>

    <script>
        // JavaScript para buscar e renderizar os dados
        fetch("/eventos.php", { // Supondo que o arquivo PHP para buscar os eventos seja "eventos.php"
            method: 'GET',
            headers: {
                'Content-Type': 'application/json'
            }
        })
        .then(response => {
            if (response.ok) {
                console.log(response);
                return response.json();
            } else {
                throw new Error('Erro ao obter a lista de eventos');
                console.log("erro");
            }
        })
        .then(data => {
            console.log("Lista de eventos:", data);

            const eventTableBody = document.getElementById("eventTableBody");

            eventTableBody.innerHTML = "";

            data.forEach(evento => {
                const newRow = document.createElement("tr");
                // Crie e preencha as células da tabela como mostrado no exemplo anterior
                // ...
                eventTableBody.appendChild(newRow);
            });
        })
        .catch(error => {
            console.error("Erro na solicitação GET:", error);
        });
    </script>
</body>
</html>

<!-- <div class="container">
    <div class="table-wrapper">
        <div class="table-title">
            <div class="row">
                <div class="col-sm-6">
                    <h2>Agenda de <b>Eventos</b></h2>
                </div>
                <div class="col-sm-6">
                    <a href="" class="btn btn-success" data-toggle="modal"><i class="material-icons">&#xE147;</i> <span>Criar novo evento</span></a>
                </div>
            </div>
        </div>
        <table class="table table-striped table-hover">
            <thead>
                <tr>
                    <th>
                        <span class="custom-checkbox">
                            <input type="checkbox" id="selectAll">
                            <label for="selectAll"></label>
                        </span>
                    </th>
                    <th>Titulo</th>
                    <th>Sobre</th>
                    <th>Status</th>
                    <th>Data de Início</th>
                    <th>Data de Encerramento</th>
                    <th>Responsável</th>
                    <th>Ação</th>
                </tr>
            </thead>
            <tbody>
                <tr>
                    <td>
                        <span class="custom-checkbox">
                            <input type="checkbox" id="checkbox" name="options[]" value="">
                            <label for="checkbox"></label>
                        </span>
                    </td>
                    <td></td>
                    <td></td>
                    <td></td>
                    <td></td>
                    <td></td>
                    <td></td>
                    <td>
                        <a href="" class="edit" data-toggle="modal"><i class="material-icons" data-toggle="tooltip" title="Edit">&#xE254;</i></a>
                        <a href="" class="delete" data-toggle="modal"><i class="material-icons" data-toggle="tooltip" title="Delete">&#xE872;</i></a>
                    </td>
                </tr>
            </tbody>
        </table>
        <div class="clearfix">
            <div class="hint-text">Showing <b>5</b> out of <b>25</b> entries</div>
            <ul class="pagination">
                <li class="page-item disabled"><a href="#">Previous</a></li>
                <li class="page-item"><a href="#" class="page-link">1</a></li>
                <li class="page-item"><a href="#" class="page-link">2</a></li>
                <li class="page-item active"><a href="#" class="page-link">3</a></li>
                <li class="page-item"><a href="#" class="page-link">4</a></li>
                <li class="page-item"><a href="#" class="page-link">5</a></li>
                <li class="page-item"><a href="#">Next</a></li>
            </ul>
        </div>
    </div>
</div> -->
