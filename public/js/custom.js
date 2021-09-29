
function trigger_pnofify(type = 'default', title = '', message = '')
{
    new PNotify({
        title: (title === '') ? false : title,
        text: (message === '') ? false : message,
        type: type,
        icon: (title === '') ? 'none' : true,
        buttons: {
            sticker: false
        },
        delay: 5000
    });

}

function changePassword()
{
    $('#password').val('');
    $('#confirmPassword').val('');
    $('#modalChangePassword').modal('show');
}
$('#modal-chagepassword-button-submit').on('click', function (e) {
    e.preventDefault();
    if($("#modal-passwordChange-form").valid()) {
    $.ajax({
        type: 'post',
        url: $('meta[name="base-path"]').attr('content') + '/update-password',
        data: $('#modal-passwordChange-form').serialize(),
        success: function (response) {
            if(response.status === true) {
                $('#modalChangePassword').modal('hide');
                trigger_pnofify('success', 'Successful', response.message);
            } else {
                trigger_pnofify('error', 'Something went wrong', response.message);
            }
            
        }
    });

    } else {
    }
    

});

$("#modal-passwordChange-form").validate({
    focusInvalid: false,
    rules: {
        'password' : {
            required : true,
            minlength : 8
        },
        'confirmPassword' : {
            required : true,
            minlength : 8,
            equalTo : "#password"
        }
    },
    messages: {
        'password' : { required: "Please enter password" },
        'confirmPassword' : { 
            required: "Please confirm password",
            equalTo: "Password dosen't match" },
    },
    errorPlacement: function errorPlacement(error, element) {
        var $parent = $(element).parents('.form-group');

        // Do not duplicate errors
        if ($parent.find('.jquery-validation-error').length) {
            return;
        }

        $parent.append(
            error.addClass('jquery-validation-error small form-text invalid-feedback')
        );
    },
    highlight: function(element) {
        var $el = $(element);
        var $parent = $el.parents('.form-group');

        $el.addClass('is-invalid');

        // Select2 and Tagsinput
        if ($el.hasClass('select2-hidden-accessible') || $el.attr('data-role') === 'tagsinput') {
            $el.parent().addClass('is-invalid');
        }
    },
    unhighlight: function(element) {
        if($(element).attr('aria-invalid') === 'false') {
            $(element).parents('.form-group').find('.is-invalid').removeClass('is-invalid');
        }
    }
});
