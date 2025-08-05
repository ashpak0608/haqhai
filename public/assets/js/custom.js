$(".toggle-password").click(function() {
    $(this).toggleClass("ki-eye");
    var input = $($(this).attr("toggle"));
    
    if (input.attr("type")=="password") {
        input.attr("type","text");
    }
    else {
        input.attr("type","password");
    }
});

$('.hitenter').keypress(function(e) {
    if (e.which==13) {
        jQuery(this).blur();jQuery('#submit').focus().click();
    }
});

$(function() {
    if (localStorage.chkbx && localStorage.chkbx != '') {
        $('#remember_me').attr('checked', 'checked');
        $('#Email_ID').val(localStorage.email);
        $('#Password').val(localStorage.password);
    }
    else {
        $('#remember_me').removeAttr('checked');
        $('#Email_ID').val('');
        $('#Password').val('');
    }
    
    $('#remember_me').click(function() {
        if ($('#remember_me').is(':checked')) {
            localStorage.email = $('#Email_ID').val();
            localStorage.password = $('#Password').val();
            localStorage.chkbx = $('#remember_me').val();
        }
        else {
            localStorage.email = '';
            localStorage.password = '';
            localStorage.chkbx = '';
        }
    });
});

$(".email-validation").on('change', function() {
    var email_id    = $(".email-validation").val();
    var patt        = /^([\w-]+(?:\.[\w-]+)*)@((?:[\w-]+\.)*\w[\w-]{0,66})\.([a-z]{2,6}(?:\.[a-z]{2})?)$/i;
    var rsemail     = patt.test(email_id);
    
    if (rsemail == false) {
        $(".email-validation").focus();
        return false;
    }
});