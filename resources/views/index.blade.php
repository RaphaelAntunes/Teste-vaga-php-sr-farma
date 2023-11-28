<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Agenda de Eventos</title>
    <link href="{{ asset('css.css') }}" rel="stylesheet">
    <script src="{{ asset('calendario.js') }}"></script>

    <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Roboto|Varela+Round">
    <link rel="stylesheet" href="https://fonts.googleapis.com/icon?family=Material+Icons">
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/font-awesome/4.7.0/css/font-awesome.min.css">
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css">
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/1.12.4/jquery.min.js"></script>
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/js/bootstrap.min.js"></script>
    <script src='https://cdn.jsdelivr.net/npm/fullcalendar@6.1.8/index.global.min.js'></script>
    <script src='https://cdn.jsdelivr.net/npm/sweetalert2@11'></script>

    <script></script>

    <script src="js/sweetalert2.all.min.js"></script>
    <link href="js/fullcalendar/lib/main.css" rel="stylesheet" />
    <script src="js/fullcalendar/lib/main.js"></script>


<body>
    <div class="container">
        <div class="table-wrapper">
            <div class="table-title">
                <div class="row">
                    <div class="col-sm-6">
                        <h2>Agenda de <b>Eventos</b></h2>
                    </div>
                    <div class="col-sm-6">
                        @auth
                            <form method="POST" action="{{ route('logout') }}">
                                @csrf
                                <button type="submit" class="btn btn-primary">Sair</button>
                            </form>
                        @else
                            <a href="{{ route('login') }}" class="btn btn-info">Login</a>
                        @endauth
                        <a onclick="NewEvento()" class="btn btn-success" data-toggle="modal">
                            <i class="material-icons">&#xE147;</i> <span>Criar novo evento</span>
                        </a>
                        
                    </div>
                    <!-- <div class="col-sm-6">
                    @auth
                                <form method="POST" action="{{ route('logout') }}">
                                    @csrf
                                    <button type="submit" class="btn btn-primary">Logout</button>
                                </form>
@else
    <a href="{{ route('login') }}" class="btn btn-primary">Login</a>
                    @endauth
                        <a href="#addEmployeeModal" class="btn btn-success" data-toggle="modal">
                            <i class="material-icons">&#xE147;</i> <span>Criar novo evento</span>
                        </a>
                        <a href="#deleteEmployeeModal" class="btn btn-danger" data-toggle="modal">
                            <i class="material-icons">&#xE15C;</i> <span>Remover</span>
                        </a>
                    </div> -->
                </div>
            </div>
            <table class="table table-striped table-hover">
                <thead>
                    <tr>
                    
                        <th>Titulo</th>
                        <th>Sobre</th>
                        <th>Status</th>
                        <th>Data de Início</th>
                        <th>Data de Encerramento</th>
                        <th>Responsável</th>
                        <th>Ações</th>


                    </tr>
                </thead>


                <tbody id="eventosTable">


                </tbody>

            </table>

        </div>
    </div>
    <div class="container" id='calendar'></div>

</body>

</html>

<script>
    // Função que faz consumo e distribuição dos eventos
    function formatarDT(dataStr) {
    const data = new Date(dataStr);
    const dia = String(data.getDate()).padStart(2, '0');
    const mes = String(data.getMonth() + 1).padStart(2, '0'); // Lembre-se de que os meses são base 0 em JavaScript
    const ano = data.getFullYear();
    const horas = String(data.getHours()).padStart(2, '0');
    const minutos = String(data.getMinutes()).padStart(2, '0');
    
    return `${dia}/${mes}/${ano} ${horas}:${minutos}`;
}
    $.ajax({
        type: 'GET',
        url: '/eventos',
        success: function(data) {
            console.log(data);
            var eventos = data.eventos;
            var tableBody = $('#eventosTable');
            $.each(eventos, function(index, evento) {
                var row = '<tr>' +
                    '<td>' + evento.title + '</td>' +
                    '<td>' + evento.description + '</td>' +
                    '<td>' + evento.status + '</td>' +
                    '<td>' + formatarDT(evento.start) + '</td>' +
                    '<td>' + formatarDT(evento.end) + '</td>' +
                    '<td>' + evento.usr_responsavel + '</td>' +

                    '<td>' +
                    '<a href="#editEmployeeModal" class="edit" onclick="EditarEvento(this)" data-title="' +
                    evento.title +
                    '"  data-toggle="modal"><i class="material-icons" data-toggle="tooltip" title="Edit">&#xE254;</i></a>' +
                    '<a href="#deleteEmployeeModal" class="delete" onclick="RemoveEvento(this)" data-title="' +
                    evento.title +
                    '" ><i class="material-icons" data-toggle="tooltip" title="Delete">&#xE872;</i></a>'

                '</td>' +
                '</tr>';
                tableBody.append(row);
            });

        },
        error: function(error) {
            console.error('Erro na solicitação AJAX:', error);
        }
    });

    function RemoveEvento(x) {
        var title = $(x).data('title');
        var url = 'excluir-evento/' + encodeURIComponent(title);

        Swal.fire({
            title: 'Remover Evento',
            html: '<p>Tem certeza que deseja remover o evento? Essa ação não poderá ser desfeita.</p>',
            showCancelButton: true,
            confirmButtonText: 'Remover',
            cancelButtonText: 'Cancelar',
        }).then(function(result) {
            if (result.isConfirmed) {
                $.ajax({
                    type: 'DELETE',
                    url: url,
                    success: function(response) {
                        Swal.fire({
                            title: 'Evento removido com sucesso',
                            icon: 'success',
                        }).then(function() {
                            location.reload(); // Recarrega a página após clicar em "OK"
                        });
                    },
                    error: function(error) {
                        console.error('Erro na solicitação AJAX:', error);
                        Swal.fire({
                            title: 'Erro ao remover evento',
                            text: 'Houve um erro ao remover o evento.',
                            icon: 'error',
                        });
                    }
                });
            }
        });
    }

    function NewEvento() {
        Swal.fire({
            title: 'Adicionar Evento',
            html: '<div class="custom-swal-modal" style="display: flex; flex-direction: column; align-items: center; justify-content: center; padding: 20px">' +
                '<input id="swalEvtTitle" class="swal2-input custom-swal-input" style="width: 100%;" placeholder="Digite o título">' +
                '<input id="swalEvtDesc" class="swal2-input custom-swal-input" style="width: 100%; margin-top: 10px" placeholder="Digite a descrição"></input>' +
                '<label for="swalEvtStartDate" class="custom-swal-title" style="width: 100%">Data Inicial:</label>' +
                '<input type="datetime-local" id="swalEvtStartDate" class="swal2-input" style="width: 100%">' +
                '<label for="swalEvtEndDate" class="custom-swal-title">Data Final:</label>' +
                '<input type="datetime-local" id="swalEvtEndDate" class="swal2-input" style="width: 100%">' +
                '</div>',
            showCancelButton: true,
            confirmButtonText: 'Adicionar',
            cancelButtonText: 'Cancelar',
            customClass: {
                popup: 'custom-swal-modal',
                title: 'custom-swal-title',
                confirmButton: 'custom-swal-confirm-button',
                cancelButton: 'custom-swal-cancel-button',
            },
            preConfirm: function() {
                var eventTitle = document.getElementById('swalEvtTitle').value;
                var eventDescription = document.getElementById('swalEvtDesc').value;
                var startDate = new Date(document.getElementById('swalEvtStartDate').value);
                var endDate = new Date(document.getElementById('swalEvtEndDate').value);

                // Formatar as datas no formato 'Y-m-d H:i:s'
                var formattedStartDate = startDate.toISOString().slice(0, 19).replace("T", " ");
                var formattedEndDate = endDate.toISOString().slice(0, 19).replace("T", " ");

                console.log(formattedStartDate);

                if (!eventTitle) {
                    Swal.showValidationMessage('O título do evento é obrigatório.');
                    return false;
                }

                if (startDate > endDate) {
                    Swal.showValidationMessage('A data final não pode ser anterior à data inicial.');
                    return false;
                }

                var eventData = {
                    title: eventTitle,
                    description: eventDescription,
                    status: 'Aberto',
                    start: formattedStartDate,
                    end: formattedEndDate
                };

                // Sending AJAX request
                fetch("/criar-evento", {
                        method: 'POST',
                        headers: {
                            'Content-Type': 'application/json'
                        },
                        body: JSON.stringify(eventData)
                    })
                    .then(response => {
                        console.log("Resposta do servidor:", response);
                        console.log("Resposta do servidor:", eventData);
                        console.log("par:", response);
                        if (response.status == 201) {

                            Swal.fire('Evento adicionado com sucesso!', '', 'success');
                            location.reload();
                        }
                        if (response.status == 400) {

                            Swal.fire('Não é permitido registrar eventos em finais de semana', '',
                                'error');
                        }
                        if (response.status == 500) {

                            Swal.fire('Já existe um evento com a mesma data de início', '', 'error');
                        }
                    })

                return false;
            }
        }).then(function(result) {
            if (result.isConfirmed) {
                Swal.fire('Evento adicionado com sucesso!', '', 'success');
            }
        });


    }


    function EditarEvento(x) {
    var title = $(x).data('title'); // Obtém o título do atributo de dados
    var url = '/eventos/' + encodeURIComponent(title); // Correção na construção da URL
    var url2 = '/editar-evento/' + encodeURIComponent(title); // Correção na construção da URL

    $.ajax({
        type: 'GET',
        url: url,
        success: function(data) {
            console.log(data);
            var eventos = data.eventos;
            var dataInicioFormatada = formatarDT(eventos.start);
            var dataPrazoFormatada = formatarDT(eventos.end);
            console.log(dataPrazoFormatada);
            Swal.fire({
                title: 'Editar Evento',
                html: '<div class="custom-swal-modal" style="display: flex; flex-direction: column; align-items: center; justify-content: center; padding: 20px">' +
                    '<input id="swalEvtTitle" class="swal2-input custom-swal-input" style="width: 100%;" placeholder="Digite o título" value="' + eventos.title + '">' +
                    '<input id="swalEvtDesc" class="swal2-input custom-swal-input" style="width: 100%; margin-top: 10px" placeholder="Digite a descrição" value="' + eventos.description + '"></input>' +
                    '<div class="swal2-radio-group">' +
                    '<p class="mt-2">Status</p>' +
                    '<label class="swal2-radio">' +
                    '<input type="radio" id="swalEvtStatusAberto" class="swal2-radio-input" name="swalEvtStatus" value="Aberto" ' + (eventos.status === 'Aberto' ? 'checked' : '') + '>' +
                    '<span class="swal2-radio-label">Aberto</span>' +
                    '</label>' +
                    '<label class="swal2-radio">' +
                    '<input type="radio" id="swalEvtStatusConcluido" class="swal2-radio-input" name="swalEvtStatus" value="Concluído" ' + (eventos.status === 'Concluído' ? 'checked' : '') + '>' +
                    '<span class="swal2-radio-label">Concluído</span>' +
                    '</label>' +
                    '</div>' +
                    '<label for="swalEvtStartDate" class="custom-swal-title" style="width: 100%">Data Inicial:</label>' +
                    '<input type="datetime-local" id="swalEvtStartDate" class="swal2-input" style="width: 100%;" value="' + dataInicioFormatada + '">' +
                    '<label for="swalEvtEndDate" class="custom-swal-title">Data Final:</label>' +
                    '<input type="datetime-local" id="swalEvtEndDate" class="swal2-input" style="width: 100%;" value="' + dataPrazoFormatada + '">' +
                    '</div>',
                showCancelButton: true,
                confirmButtonText: 'Editar',
                cancelButtonText: 'Cancelar',
                customClass: {
                    popup: 'custom-swal-modal',
                    title: 'custom-swal-title',
                    confirmButton: 'custom-swal-confirm-button',
                    cancelButton: 'custom-swal-cancel-button',
                },
                preConfirm: function() {
                    var eventTitle = document.getElementById('swalEvtTitle').value;
                    var eventDescription = document.getElementById('swalEvtDesc').value;
                    var startDate = new Date(document.getElementById('swalEvtStartDate').value);
                    var endDate = new Date(document.getElementById('swalEvtEndDate').value);

                    // Formatar as datas no formato 'Y-m-d H:i:s'
                    var formattedStartDate = startDate.toISOString().slice(0, 19).replace("T", " ");
                    var formattedEndDate = endDate.toISOString().slice(0, 19).replace("T", " ");

                    console.log(formattedStartDate);

                    if (!eventTitle) {
                        Swal.showValidationMessage('O título do evento é obrigatório.');
                        return false;
                    }

                    if (startDate > endDate) {
                        Swal.showValidationMessage('A data final não pode ser anterior à data inicial.');
                        return false;
                    }

                    var eventData = {
                        title: eventTitle,
                        description: eventDescription,
                        status: $("input[name='swalEvtStatus']:checked").val(), // Obter o valor do input de rádio selecionado
                        start: formattedStartDate,
                        end: formattedEndDate
                    };

                    // Sending AJAX request
                    $.ajax({
                        type: 'put',
                        url: url2, // Substitua pelo URL correto da sua rota de edição de evento
                        data: eventData, // Envie os dados do evento editado
                        success: function(response) {
                            // Manipule a resposta da API, se necessário
                            console.log(response);
                            Swal.fire('Evento editado com sucesso!', '', 'success').then(function() {
                                location.reload(); // Recarrega a página após clicar em "OK"
                            });
                        },
                        error: function(error) {
                            console.error('Erro na solicitação AJAX:', error);
                        }
                    });

                    return false;
                }
            }).then(function(result) {
                if (result.isConfirmed) {
                    Swal.fire('Evento editado com sucesso!', '', 'success');
                }
            });
        },
        error: function(error) {
            console.error('Erro na solicitação AJAX:', error);
        }
    });
}






</script>
