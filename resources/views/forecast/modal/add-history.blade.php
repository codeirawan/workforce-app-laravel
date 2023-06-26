<div class="modal fade" id="modal-add-history" tabindex="-1" role="dialog" aria-labelledby="resultsModalLabel"
    aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-xl" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="resultsModalLabel">Add History Data Volume Actual</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <table id="resultsTable" class="table text-center">
                    <thead>
                        <tr>
                            <th>Site</th>
                            <th>Project</th>
                            <th>Skill</th>
                            <th>Start Date</th>
                            <th>End Date</th>
                            <th>Mon</th>
                            <th>Tue</th>
                            <th>Wed</th>
                            <th>Thu</th>
                            <th>Fri</th>
                            <th>Sat</th>
                            <th>Sun</th>
                            <th>Total</th>
                            <th>Avg</th>
                            <th>Select</th>
                        </tr>
                    </thead>
                    <tbody>
                        <!-- Data rows will be added dynamically -->
                    </tbody>
                </table>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancel</button>
                <button type="button" class="btn btn-primary" id="addHistory">Add Selected</button>
            </div>
        </div>
    </div>
</div>
<script src="{{ asset(mix('js/form/validation.js')) }}"></script>
<script type="text/javascript">
    $('.kt_selectpicker').selectpicker({
        noneResultsText: "{{ __('No matching results for') }} {0}"
    });
</script>
<script>
    $(document).ready(function() {
        // Fetch the query results using AJAX
        $.ajax({
            method: 'POST',
            url: "{{ url('forecast/data') . '/' . $params->id }}",
            headers: {
                'X-CSRF-TOKEN': '{{ csrf_token() }}'
            },
            dataType: 'json',
            success: function(response) {
                var tableBody = $('#resultsTable tbody');

                // Check if the response is an array
                if (Array.isArray(response)) {
                    // Populate the modal table with the response data
                    response.forEach(function(result) {
                        var row = '<tr>' +
                            '<td>' + result.site + '</td>' +
                            '<td>' + result.project + '</td>' +
                            '<td>' + result.skill + '</td>' +
                            '<td>' + result.start_date + '</td>' +
                            '<td>' + result.end_date + '</td>' +
                            '<td>' + result.mon + '</td>' +
                            '<td>' + result.tue + '</td>' +
                            '<td>' + result.wed + '</td>' +
                            '<td>' + result.thu + '</td>' +
                            '<td>' + result.fri + '</td>' +
                            '<td>' + result.sat + '</td>' +
                            '<td>' + result.sun + '</td>' +
                            '<td>' + result.sum_per_week + '</td>' +
                            '<td>' + result.avg_per_week + '</td>' +
                            '<td><input type="checkbox" value="' + result.start_date + '|' +
                            result.end_date + '"></td>' +
                            '</tr>';

                        tableBody.append(row);
                    });
                } else {
                    console.error('Invalid response format. Expected an array.');
                }
            },
            error: function(xhr, status, error) {
                console.error(error);
            }
        });

        // Handle insertion of selected data
        $('#addHistory').on('click', function() {
            var selectedData = [];

            // Iterate over the selected checkboxes
            $('#resultsTable tbody input[type="checkbox"]:checked').each(function() {
                var row = $(this).closest('tr');
                var data = {
                    forecast_id: {{ $params->id }},
                    start_date: row.find('td:eq(3)').text(),
                    end_date: row.find('td:eq(4)').text(),
                    mon: parseInt(row.find('td:eq(5)').text()),
                    tue: parseInt(row.find('td:eq(6)').text()),
                    wed: parseInt(row.find('td:eq(7)').text()),
                    thu: parseInt(row.find('td:eq(8)').text()),
                    fri: parseInt(row.find('td:eq(9)').text()),
                    sat: parseInt(row.find('td:eq(10)').text()),
                    sun: parseInt(row.find('td:eq(11)').text()),
                    sum: parseInt(row.find('td:eq(12)').text()),
                    avg: parseFloat(row.find('td:eq(13)').text())
                };
                selectedData.push(data);
            });

            // Send the selected data for insertion using AJAX
            $.ajax({
                method: 'POST',
                url: '{{ route('forecast.calculation') }}',
                headers: {
                    'X-CSRF-TOKEN': '{{ csrf_token() }}'
                },
                data: {
                    selectedData: selectedData
                },

                success: function(response) {
                    $('#modal-add-history').modal('hide');
                    Swal.fire({
                        title: 'Success',
                        text: 'Data has been added successfully.',
                        icon: 'success',
                        showCancelButton: false,
                        confirmButtonText: 'OK',
                        buttonsStyling: false,
                        confirmButtonText: "OK",
                        customClass: {
                            confirmButton: "btn btn-success"
                        }
                    }).then(function(result) {
                        location.reload();
                    });
                },
                error: function(xhr, status, error) {
                    $('#modal-add-history').modal('hide');
                    Swal.fire({
                        title: 'Error',
                        text: 'Data already added or duplicated.',
                        icon: 'error',
                        showCancelButton: false,
                        confirmButtonText: 'OK',
                        buttonsStyling: false,
                        confirmButtonText: "Please check again!",
                        customClass: {
                            confirmButton: "btn btn-danger"
                        }
                    });
                }
            });
        });

    });
</script>
