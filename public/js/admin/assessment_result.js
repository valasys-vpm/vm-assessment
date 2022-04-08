
let ASSESSMENT_RESULT_TABLE;

$(function (){

    ASSESSMENT_RESULT_TABLE = $('#table-assessment-result').DataTable({
        "lengthMenu": [ [50,10,20,30,-1], [50,10,20,30,'All'] ],
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

            {
                orderable: false,
                render: function (data, type, row) {
                    let html = '';
                    html += '<button class="btn btn-outline-primary btn-sm" title="View Result" onclick="window.location.href=\''+$('meta[name="base-path"]').attr('content') + '/admin/assessment/'+btoa(row.id)+'/view-user-assessment\'"><i class="feather icon-eye mr-0"></i></button>';
                    html += '<button class="btn btn-outline-danger btn-sm" title="Delete Record" onclick="deleteAssessment(\''+ btoa(row.id) +'\');"><i class="feather icon-trash mr-0"></i></button>';
                    return html;
                }
            },
        ]
    });

});

function refreshTableAssessmentResult()
{
    ASSESSMENT_RESULT_TABLE.ajax.reload();
}

function deleteAssessment(_id) {
    if (confirm('Are you sure to delete this user assessment?')) {
        $.ajax({
            type: 'post',
            url: $('meta[name="base-path"]').attr('content') + '/admin/assessment/'+ _id +'/delete-user-assessment',
            dataType: 'json',
            success: function(response) {
                if (response.status === true) {
                    trigger_pnofify('success', 'Successful', response.message);
                } else {
                    trigger_pnofify('error', 'Something went wrong', response.message);
                }
                ASSESSMENT_RESULT_TABLE.ajax.reload();
            }
        });
    } else {
        return true;
    }

}

