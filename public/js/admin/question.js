
let QUESTION_TABLE;

$(function (){

    QUESTION_TABLE = $('#table-questions').DataTable({
        "lengthMenu": [ [5,10,20,30,'all'], [5,10,20,30,'All'] ],
        "processing": true,
        "serverSide": true,
        "ajax": {
            "url": $('meta[name="base-path"]').attr('content') + '/admin/question/get-questions/'+$('#assessment_id').val(),
            data: {
                filters: function (){
                    let obj = {
                    };
                    localStorage.setItem("filters", JSON.stringify(obj));
                    return JSON.stringify(obj);
                }
            },
            error: function(jqXHR, textStatus, errorThrown) { checkSession(jqXHR); }
        },
        "columns": [
            {
                data: 'category.name',
            },
            {
                data: 'question',
            },
            {
                render: function (data, type, row) {
                    let html = '<ul>';
                    $.each(row.options, function (key, value) {
                        if(parseInt(value.is_answer)) {
                            html += '<li class="text-success">'+value.option+'</li>';
                        } else {
                            html += '<li>'+value.option+'</li>';
                        }
                    });

                    return html+'</ul>';
                }
            },
            {
                render: function (data, type, row) {
                    switch (parseInt(row.status)) {
                        case 1: return '<span class="badge badge-pill badge-success" style="padding: 5px;min-width:50px;">Active</span>';
                        case 0: return '<span class="badge badge-pill badge-danger" style="padding: 5px;min-width:50px;">Inactive</span>';
                    }
                }
            },
            {
                render: function (data, type, row) {
                    return moment(row.created_at).format('YYYY-MM-DD HH:mm:ss');
                }
            },
            {
                render: function (data, type, row) {
                    return moment(row.updated_at).format('YYYY-MM-DD HH:mm:ss');
                }
            },
            {
                orderable: false,
                render: function (data, type, row) {
                    let html = '';
                    //html += '<button class="btn btn-outline-dark btn-sm" title="Edit Question" onclick="editQuestion('+row.id+')"><i class="feather icon-edit mr-0"></i></button>';
                    html += '<button class="btn btn-outline-danger btn-sm" title="Delete Question" onclick="deleteQuestion('+row.id+')"><i class="feather icon-trash-2 mr-0"></i></button>';
                    return html;
                }
            },
        ],
        order:[]
    });

    $('#modal-form-button-submit').on('click', function (e) {
        e.preventDefault();
        let url = '';
        if($(this).text() === 'Save') {
            url = $('meta[name="base-path"]').attr('content') + '/admin/question/store';
        } else if ($(this).text() === 'Update') {
            url = $('meta[name="base-path"]').attr('content') + '/admin/question/update/'+$('#question_id').val();
        } else {
            resetModalForm();
            trigger_pnofify('error', 'Something went wrong', 'Please try again');
        }

        $.ajax({
            type: 'post',
            url: url,
            data: $('#modal-question-form').serialize(),
            success: function (response) {
                if(response.status === true) {
                    resetModalForm();
                    $('#modalQuestion').modal('hide');
                    trigger_pnofify('success', 'Successful', response.message);
                } else {
                    trigger_pnofify('error', 'Something went wrong', response.message);
                }
                QUESTION_TABLE.ajax.reload();
            }
        });

    });

    //Add Option
    $("body").on("click", ".btn_add_option", function(){
        addOption();
    });

    //Remove Option
    $("body").on("click", ".btn_remove_option", function(){
        removeOption(this);
    });
});

function addOption()
{
    var no_of_option = $("#options").children('div.option').length;

    var html_option = '<div id="div_option_'+ (no_of_option+1) +'" class="form-group col-md-6 option">' +
        '                                            <div class="input-group">' +
        '                                                <div class="input-group-prepend">' +
        '                                                    <div class="input-group-text">' +
        '                                                        <input type="radio" name="answer" value="'+ (no_of_option+1) +'" required>' +
        '                                                    </div>' +
        '                                                </div>' +
        '                                                <input type="text" class="form-control" name="options['+ (no_of_option+1) +']" placeholder="Options..." required>' +
        '                                                <div class="input-group-append">' +
        '                                                    <div class="input-group-text">' +
        '                                                        <button type="button" class="btn btn-icon btn-outline-danger btn-sm btn_remove_option" style="width: 30px;height: 30px;padding: 0px;">' +
        '                                                            <i class="fas fa-times"></i>' +
        '                                                        </button>' +
        '                                                    </div>' +
        '                                                </div>' +
        '                                            </div>' +
        '                                        </div>';
    $('body').find("#options").append(html_option);
}

function removeOption(_this)
{
    var no_of_option = $("#options").children('div.option').length;
    if(no_of_option > 2) {
        if(confirm('Are you sure to remove option ?') === true) {
            $(_this).parents('div.option').remove();
        }
    } else {
        alert('Warning: At least two option must be there.');
    }
    return true;
}

function addQuestion()
{
    resetModalForm();
    $('#modalQuestion').modal('show');
}

function editQuestion(id)
{
    $.ajax({
        type: 'post',
        url: $('meta[name="base-path"]').attr('content') + '/admin/question/edit/'+btoa(id),
        dataType: 'json',
        success: function (response) {
            if(response.status === true) {
                $('#modal-heading').text('Edit question');
                $('#modal-form-button-submit').text('Update');
                $('#question_id').val(btoa(response.data.id));
                $('#category_id').val(response.data.category.id);
                $('#date,#div-input-date').css('display', 'none');
                $('#date').attr('disabled', 'disabled');
                $('#question').val(response.data.question);
                $('#status').val(response.data.status);
                $('#modalQuestion').modal('show');
            } else {
                resetModalForm();
                trigger_pnofify('error', 'Something went wrong', response.message);
            }

        }
    });
}

function deleteQuestion(id)
{
    if(confirm('Are you sure to delete this question?')) {
        $.ajax({
            type: 'post',
            url: $('meta[name="base-path"]').attr('content') + '/admin/question/destroy/'+btoa(id),
            dataType: 'json',
            success: function (response) {
                if(response.status === true) {
                    trigger_pnofify('success', 'Successful', response.message);
                } else {
                    trigger_pnofify('error', 'Something went wrong', response.message);
                }
                QUESTION_TABLE.ajax.reload();
            }
        });
    } else {
        return true;
    }

}

function resetModalForm()
{
    $('#modal-heading').text('Add new question');
    $('#modal-form-button-submit').text('Save');
    $('#question_id').val('');
    $('#category_id').val('');
    $('#date,#div-input-date').css('display', 'block');
    $('#date').removeAttr('disabled', 'disabled');
    $('#question').val('');
    $('#number_of_question').val('');
    $('#status').val('1');
}
