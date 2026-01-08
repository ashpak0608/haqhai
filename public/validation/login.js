$(document).ready(function(){
    $("#login_form").validate({
        rules: {
            email_id: {
                required: true,
                email: true
            },
            password: {
                required: true,
                minlength: 6
            }
        },
        messages: {
            email_id: {
                required: "Please enter your email",
                email: "Enter a valid email address"
            },
            password: {
                required: "Please enter your password",
                minlength: "Password must be at least 6 characters long"
            }
        },
        submitHandler: function(form) {
            var formData = $(form).serialize();
            $.ajax({
                url: SITE_URL+"login-check",
                type: "POST",
                data: formData,
                success: function(response){
                    console.log(response);
                    var data = JSON.parse(response);
                    if(data.status == 'success'){
                        window.location.href = SITE_URL+"dashboard";
                    }
                    commonStatusMessage(data,'')
                },
                error: function(xhr) {
                    toastr.warning(xhr.responseText);
                }
            });
        }
    });

    // $("#login").click(function(){
    //     $("#login_form").submit(); // Trigger form validation and submission
    // });
});