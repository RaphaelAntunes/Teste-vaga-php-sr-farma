document.addEventListener('DOMContentLoaded', function() {

    var calendarEl = document.getElementById('calendar');

    var calendar = new FullCalendar.Calendar(calendarEl, {
        initialView: 'dayGridMonth',
        height: 650,
        businessHours: {
            daysOfWeek: [1, 2, 3, 4, 5],
        },
        dateClick: function(info) {

            var selectedDayOfWeek = info.date.getDay();
            if (calendar.getOption('businessHours').daysOfWeek.includes(selectedDayOfWeek)) {
                Swal.fire({
                    title: 'Adicionar Evento',
                    html: '<div class="custom-swal-modal" style="display: flex; flex-direction: column; align-items: center; justify-content: center; padding: 20px">' +
                        '<input id="swalEvtTitle" class="swal2-input custom-swal-input" style="width: 100%;" placeholder="Digite o título">' +
                        '<input id="swalEvtDesc" class="swal2-input custom-swal-input" style="width: 100%; margin-top: 10px" placeholder="Digite a descrição"></input>' +
                        '<label for="swalEvtStartDate" class="custom-swal-title" style="width: 100%">Data Inicial:</label>' +
                        '<input type="datetime-local" id="swalEvtStartDate" class="swal2-input" style="width: 100%" value="' +
                        info.dateStr.replace(/T.*$/, '') + 'T00:00">' +
                        '<label for="swalEvtEndDate" class="custom-swal-title">Data Final:</label>' +
                        '<input type="datetime-local" id="swalEvtEndDate" class="swal2-input" style="width: 100%" value="' +
                        info.dateStr.replace(/T.*$/, '') + 'T23:59">' +
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
                        var eventTitle = document.getElementById('swalEvtTitle')
                            .value;
                        var eventDescription = document.getElementById(
                            'swalEvtDesc').value;
                        var startDate = new Date(document.getElementById(
                            'swalEvtStartDate').value);
                        var endDate = new Date(document.getElementById(
                            'swalEvtEndDate').value);

                        // Formatar as datas no formato 'Y-m-d H:i:s'
                        var formattedStartDate = startDate.toISOString().slice(0,
                            19).replace("T", " ");
                        var formattedEndDate = endDate.toISOString().slice(0, 19)
                            .replace("T", " ");

                        console.log(formattedStartDate);

                        if (!eventTitle) {
                            Swal.showValidationMessage(
                                'O título do evento é obrigatório.');
                            return false;
                        }

                        if (startDate > endDate) {
                            Swal.showValidationMessage(
                                'A data final não pode ser anterior à data inicial.'
                            );
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
                        calendar.render();
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
                                if (response.status = 201) {
                                    calendar.addEvent(eventData);
                                    calendar.renderEvents([eventData]);
                                    calendar.render();
                                    Swal.fire('Evento adicionado com sucesso!',
                                        '', 'success');
                                    location.reload();
                                } else {
                                    Swal.fire('Erro ao adicionar evento', data
                                        .message, 'error');
                                }

                            })

                        return false;
                    }
                }).then(function(result) {
                    if (result.isConfirmed) {
                        Swal.fire('Evento adicionado com sucesso!', '', 'success');
                    }
                });
            } else {
                alert('Esse dia não está disponível para agendamento.');
            }
        }
    });

    $.ajax({
        type: 'GET',
        url: '/eventos',
        success: function(data) {
            console.log(data);
            var eventos = data.eventos;
            var tableBody = $('#eventosTable');
            $.each(eventos, function(index, evento) {

                var eventDataGET = {
                    title: evento.title,
                    description: evento.description,
                    status: 'Aberto',
                    start: evento.start,
                    end: evento.end
                };
                calendar.addEvent(eventDataGET);

                console.log(eventDataGET);

            });

        },
        error: function(error) {
            console.error('Erro na solicitação AJAX:', error);
        }
    });

    calendar.render();
});



///