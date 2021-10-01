
let ASSESSMENT_RESULT_TABLE;

$(function (){

    ASSESSMENT_RESULT_TABLE = $('#table-assessment-result').DataTable({
        "lengthMenu": [ [5,10,20,30,'all'], [5,10,20,30,'All'] ],
        "processing": true,
        "serverSide": true,
        "ajax": {
            "url": $('meta[name="base-path"]').attr('content') + '/admin/assessment/get-assessment-result/'+btoa($('#assessment_id').val()),
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
                render: function (data, type, row) {
                    return '<span title="'+row.user.employee_code+' - '+row.user.email+'" style="cursor: pointer;">'+row.user.first_name+' '+row.user.last_name+'</span>';
                }
            },
            {
                data: 'attempted',
            },
            {
                data: 'marks_obtained',
            },
            {
                data: 'submit_count',
            },

            /*{
                orderable: false,
                render: function (data, type, row) {
                    let html = '';
                    html += '<button class="btn btn-outline-primary btn-sm" title="View Assessment" onclick="window.location.href=\''+$('meta[name="base-path"]').attr('content') + '/admin/assessment/'+btoa(row.id)+'/view-assessment\'"><i class="feather icon-eye mr-0"></i></button>';
                    html += '<button class="btn btn-outline-secondary btn-sm" title="Send Result" onclick="sendAssessmentResult('+row.id+')"><i class="feather icon-mail mr-0"></i></button>';
                    html += '<button class="btn btn-outline-dark btn-sm" title="Edit Assessment" onclick="editAssessment('+row.id+')"><i class="feather icon-edit mr-0"></i></button>';
                    html += '<button class="btn btn-outline-danger btn-sm" title="Delete Assessment" onclick="deleteAssessment('+row.id+')"><i class="feather icon-trash-2 mr-0"></i></button>';
                    return html;
                }
            },*/
        ]
    });

});

function refreshTableAssessmentResult()
{
    ASSESSMENT_RESULT_TABLE.ajax.reload();
}

