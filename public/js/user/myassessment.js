let MYASSESSMENT_TABLE;

$(function() {

    MYASSESSMENT_TABLE = $('#table-myassessments').DataTable({
        "lengthMenu": [
            [10, 25, 50, 100, 'all'],
            [10, 25, 50, 100, 'All']
        ],
        "processing": true,
        "serverSide": true,
        "ajax": {
            "url": $('meta[name="base-path"]').attr('content') + '/assessment/get-my-assessment',
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
                data: 'assessment.name',
            },
            {
                data: 'assessment.date',
            },
            {
                data: 'assessment.number_of_questions',
            },
            {
                data: 'attempted',
            },
            {
                data: 'marks_obtained',
            },
        ]
    });
});