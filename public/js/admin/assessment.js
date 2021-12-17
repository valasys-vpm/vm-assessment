let URL = $('meta[name="base-path"]').attr('content');
let ASSESSMENT_TABLE;

$(function() {

    ASSESSMENT_TABLE = $('#table-assessments').DataTable({
        "lengthMenu": [
            [5, 10, 20, 30, 'all'],
            [5, 10, 20, 30, 'All']
        ],
        "processing": true,
        "serverSide": true,
        "ajax": {
            "url": $('meta[name="base-path"]').attr('content') + '/admin/assessment/get-assessments',
            data: {
                filters: function() {
                    let obj = {};
                    localStorage.setItem("filters", JSON.stringify(obj));
                    return JSON.stringify(obj);
                }
            },
            error: function(jqXHR, textStatus, errorThrown) { checkSession(jqXHR); }
        },
        "columns": [{
                data: 'name',
            },
            {
                data: 'date',
            },
            {
                data: 'number_of_questions',
            },
            {
                render: function(data, type, row) {
                    switch (parseInt(row.status)) {
                        case 1:
                            return '<span class="badge badge-pill badge-info" style="padding: 5px;min-width:50px;">Active</span>';
                        case 0:
                            return '<span class="badge badge-pill badge-danger" style="padding: 5px;min-width:50px;">Inactive</span>';
                        case 2:
                            return '<span class="badge badge-pill badge-success" style="padding: 5px;min-width:50px;">Completed</span>';
                    }
                }
            },
            {
                render: function(data, type, row) {
                    return moment(row.created_at).format('YYYY-MM-DD HH:mm:ss');
                }
            },
            {
                render: function(data, type, row) {
                    return moment(row.updated_at).format('YYYY-MM-DD HH:mm:ss');
                }
            },
            {
                orderable: false,
                render: function(data, type, row) {
                    let html = '';
                    if (parseInt($('#auth_user_id').val()) === 59) {
                        html += '<button class="btn btn-outline-primary btn-sm" title="View Assessment" onclick="window.location.href=\'' + $('meta[name="base-path"]').attr('content') + '/admin/assessment/' + btoa(row.id) + '/view-assessment\'"><i class="feather icon-eye mr-0"></i></button>';
                    } else {
                        html += '<button class="btn btn-outline-primary btn-sm" title="View Assessment" onclick="window.location.href=\'' + $('meta[name="base-path"]').attr('content') + '/admin/assessment/' + btoa(row.id) + '/view-assessment\'"><i class="feather icon-eye mr-0"></i></button>';
                        html += '<button class="btn btn-outline-secondary btn-sm" title="Send Result" onclick="sendAssessmentResult(' + row.id + ')"><i class="feather icon-mail mr-0"></i></button>';
                        html += '<button class="btn btn-outline-dark btn-sm" title="Edit Assessment" onclick="editAssessment(' + row.id + ')"><i class="feather icon-edit mr-0"></i></button>';
                        html += '<button class="btn btn-outline-danger btn-sm" title="Delete Assessment" onclick="deleteAssessment(' + row.id + ')"><i class="feather icon-trash-2 mr-0"></i></button>';
                    }
                    return html;
                }
            },
        ]
    });

    $('#modal-form-button-submit').on('click', function(e) {
        e.preventDefault();
        let url = '';
        if ($(this).text() === 'Save') {
            url = $('meta[name="base-path"]').attr('content') + '/admin/assessment/store';
        } else if ($(this).text() === 'Update') {
            url = $('meta[name="base-path"]').attr('content') + '/admin/assessment/update/' + $('#assessment_id').val();
        } else {
            resetModalForm();
            trigger_pnofify('error', 'Something went wrong', 'Please try again');
        }

        $.ajax({
            type: 'post',
            url: url,
            data: $('#modal-assessment-form').serialize(),
            success: function(response) {
                if (response.status === true) {
                    resetModalForm();
                    $('#modalAssessment').modal('hide');
                    trigger_pnofify('success', 'Successful', response.message);
                } else {
                    trigger_pnofify('error', 'Something went wrong', response.message);
                }
                ASSESSMENT_TABLE.ajax.reload();
            }
        });

    });
});

function addAssessment() {
    resetModalForm();
    $('#modalAssessment').modal('show');
}

function editAssessment(id) {
    $.ajax({
        type: 'post',
        url: $('meta[name="base-path"]').attr('content') + '/admin/assessment/edit/' + btoa(id),
        dataType: 'json',
        success: function(response) {
            if (response.status === true) {
                $('#modal-heading').text('Edit assessment');
                $('#modal-form-button-submit').text('Update');
                $('#assessment_id').val(btoa(response.data.id));
                $('#group_id').val(response.data.group_id);
                $('#name').val(response.data.name);
                $('#date').val(response.data.date);
                $('#number_of_questions').val(response.data.number_of_questions);
                $('#status').val(response.data.status);
                $('#modalAssessment').modal('show');
            } else {
                resetModalForm();
                trigger_pnofify('error', 'Something went wrong', response.message);
            }

        }
    });
}

function deleteAssessment(id) {
    if (confirm('Are you sure to delete this Assessment?')) {
        $.ajax({
            type: 'post',
            url: $('meta[name="base-path"]').attr('content') + '/admin/assessment/destroy/' + btoa(id),
            dataType: 'json',
            success: function(response) {
                if (response.status === true) {
                    trigger_pnofify('success', 'Successful', response.message);
                } else {
                    trigger_pnofify('error', 'Something went wrong', response.message);
                }
                ASSESSMENT_TABLE.ajax.reload();
            }
        });
    } else {
        return true;
    }

}

function resetModalForm() {
    $('#modal-heading').text('Add new assessment');
    $('#modal-form-button-submit').text('Save');
    $('#assessment_id').val('');
    $('#name').val('');
    $('#date').val('');
    $('#number_of_questions').val('');
    $('#status').val('0');
}

function sendAssessmentResult(id) {
    if (confirm('Are you sure to send assessment result?')) {
        $.ajax({
            type: 'post',
            url: $('meta[name="base-path"]').attr('content') + '/admin/assessment/' + btoa(id) + '/send-assessment-result',
            dataType: 'json',
            success: function(response) {
                if (response.status === true) {
                    trigger_pnofify('success', 'Successful', response.message);
                    console.log(response.html);
                } else {
                    trigger_pnofify('error', 'Something went wrong', response.message);
                }
                //ASSESSMENT_TABLE.ajax.reload();
            }
        });
    } else {
        return true;
    }

}

function sendAssessmentResultBulk() {
    if (confirm('Are you sure to send assessment result in bulk?')) {
        $.ajax({
            type: 'post',
            url: $('meta[name="base-path"]').attr('content') + '/admin/assessment/send-assessment-result-bulk',
            dataType: 'json',
            success: function(response) {
                if (response.status === true) {
                    trigger_pnofify('success', 'Successful', response.message);
                    console.log(response.html);
                } else {
                    trigger_pnofify('error', 'Something went wrong', response.message);
                }
                //ASSESSMENT_TABLE.ajax.reload();
            }
        });
    } else {
        return true;
    }

}

function sendResult() {
    resetSendResultModalForm();
    $('#modalSendResult').modal('show');
}

function resetSendResultModalForm() {
    $('#department').val('0');
    $('#month').val('0');
    $('#year').val('0');

}
$('#modal-send-result-form-button-submit').on('click', function(e) {
    if ($('#department').val() == 0) {
        trigger_pnofify('error', 'Please select department');
        return false;
    }
    if (confirm('Are you sure to send assessment result in bulk?')) {
        $.ajax({
            type: 'post',
            url: URL + '/admin/assessment/send-assessment-result-bulk',
            data: $('#modal-send-result-form').serialize(),
            dataType: 'json',
            success: function(response) {
                if (response.status === true) {
                    $('#btn-modal-download-result').attr('href', URL + '/public/storage/result/' + response.message);
                    $('#modalSendResult').modal('hide');
                    $('#modalDownloadResult').modal('show');
                    //trigger_pnofify('success', 'Successful', response.message);
                    //console.log(response.html);
                } else {
                    trigger_pnofify('error', 'Something went wrong', response.message);
                }
                //ASSESSMENT_TABLE.ajax.reload();
            }
        });
    } else {
        return true;
    }


});