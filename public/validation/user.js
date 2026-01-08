var filter  = new Object();
var start = 0;
var limit = 10;
var element = $('.card-body');

$(document).ready(function () {

    jQuery.validator.addMethod("pattern", function(value, element, param) {
        if (this.optional(element)) {
            return true;
        }
        if (typeof param === "string") {
            param = new RegExp(param);
        }
        return param.test(value);
    }, "Invalid format.");


    $("#user_form").validate({
        onkeydown: false,
        onkeyup: false,
        onfocusin: false,
        onfocusout: false,
        errorElement: "div",
        rules: {
            full_name: {
                required: true,
            },
            email_id: {
                required: true,
            },
            phone_1: {
                required: true,
            },
            role_id: {
                required: true,
            },
            aadhar_card_no: {
                minlength: 12,
                maxlength: 12,
                digits: true,
                required: function(element) {
                    return $('#aadhar_card_no').val().trim() != "";
                }
            },
            pan_card_no: {
                minlength: 10,
                maxlength: 10,
                pattern: /^[A-Z]{5}[0-9]{4}[A-Z]{1}$/,
                required: function(element) {
                    return $('#pan_card_no').val().trim() != "";
                }
            }
        },
        messages: {
            full_name: "Please enter user name",
            email_id: "Please enter email name",
            phone_1: "Please enter phone",
            //password: "Please enter password",
            role_id: "Please select role",
            aadhar_card_no: {
                minlength: "Aadhar must be 12 digits",
                maxlength: "Aadhar must be 12 digits",
                digits: "Aadhar must be numbers only",
            },
            pan_card_no: {
                minlength: "PAN must be 10 characters",
                maxlength: "PAN must be 10 characters",
                pattern: "Invalid PAN format (e.g., ABCDE1234F)"
            }
        },
        submitHandler: function (form) {
            queryString = $('#user_form').serialize();
            $("#submit_button").hide();
            $("#display_processing").css('display','inline-block');
            $.post(SITE_URL+'user/save', queryString, function (data) {
               commonStatusMessage(data, SITE_URL+'user');
            }, "json");
            return false;
        },
        errorPlacement: function (error, element) {
            showError(error, element);
        }
    });

    $("#submit_button").click(function () {
        $("#user_form").submit();
        return false;
    });

    filter.start       = start;
    filter.limit       = limit;

    element.on('change','#search_limits',function(){
        start = 0;
        limit = $(this).val();
        filter.start = start; 
        filter.limit = limit; 
        filter.full_name   = $('#full_name').val();
        filter.email_id   = $('#email_id').val();
        filter.phone_1   = $('#phone_1').val();
        filtering(filter);                 
    });

    element.on('click','#pagination ul li.filter',function(){
        filter.start = $(this).attr('data-start');
        filter.limit = $(this).attr('data-limit');
        filter.full_name   = $('#full_name').val();
        filter.email_id   = $('#email_id').val();
        filter.phone_1   = $('#phone_1').val();
        filtering(filter);      
    });
});


$("#search").on("click",function(){ 
    filter.full_name   = $('#full_name').val();
    filter.email_id   = $('#email_id').val();
    filter.phone_1   = $('#phone_1').val();
    if(filter.full_name == '' && filter.email_id == '' && filter.level_id == ''){
        $("#full_name_error").html('Please enter user name').fadeIn().delay(3000).fadeOut();
        $("#email_id_error").html('Please enter email Address').fadeIn().delay(3000).fadeOut();
        $("#phone_1_error").html('Please enter mobile Name').fadeIn().delay(3000).fadeOut();
        $('#full_name_search').focus();
        return false;
    }
    filter.start     = start;
    filter.limit     = limit;
    filtering(filter);
});

 $("#cancel").on("click",function(){ 
        $("#search_limits").val('10').trigger('change');
    $('#full_name_search').val('');
    $('#email_id_search').val('');
    $('#level_id_search').val('');
    filter.full_name   = '';
    filter.email_id   = '';
    filter.level_id   = '';
    filter.start     = start;
    filter.limit     = limit;
    filtering(filter);
});


function statusUpdate(status,id,checkbox) {
    var url = SITE_URL + 'user/status/' + status+'/'+id;
    let indexUrl = SITE_URL+'user';
    commonConfirmationToggle(url, indexUrl, checkbox);
}


function filtering(filter) {
    const date = new Date();
    $("#search").hide();
    $("#search_display_processing").css('display', 'block');

    $.ajax({
        Headers:{ 'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')},
        type: 'POST',
        url: SITE_URL + 'user/filter',
        data: filter,
        success: function(response) {
            let obj = $.parseJSON(response);

            if (obj.status == 'success') {
                $("#search").show();
                $("#search_display_processing").css('display', 'none');

                if (obj.total_count > 0) {
                    let html = '';
                    let city = obj.lists;
                    for (let i = 0; i < city.length; i++) {
                        let statusChecked = city[i]['status'] == 1 ? 'checked' : '';
                        let id = city[i]['id'];
                        let status = city[i]['status'];
                        let add = SITE_URL + 'user/add/' + list[i]['id'];
                        let view = SITE_URL + 'user/view/' + list[i]['id'];
                        html += `<tr id="follow_up_row${i}">
                                    <td>${city[i]['full_name']}</td>
                                    <td>${city[i]['email_id']}</td>
                                    <td>${city[i]['phone_1']}</td>
                                    <td>${city[i]['level_name']}</td>
                                    <td>
                                        <label class="form-check form-switch form-switch-sm form-check-custom form-check-solid">
                                            <input class="form-check-input" name="status" type="checkbox" value="${status}" onchange="statusUpdate(${status}, ${id},this)" ${statusChecked} />
                                            <span class="form-check-label fw-semibold text-muted" for="status"></span>
                                        </label>
                                    </td>
                                    <td>
                                        <a href="${add}" class="btn btn-icon btn-active-light-info w-30px h-30px me-3"><i class="ki-outline ki-pencil text-info fs-3"></i></a>
                                        <a href="${view}" class="btn btn-icon btn-active-light-primary w-30px h-30px me-3"><i class="ki-outline ki-eye text-primary fs-3"></i></a>
                                    </td>
                                </tr>`;
                    }
                    $('#table-content').html(html);
                    $('.pagination').show();
                    $('#showing').show().html(obj.message);

                    const remaining = obj.total_count % filter.limit;
                    const totalPages = Math.ceil(obj.total_count / filter.limit);

                    $('#pagination').find('ul li.strt').attr('data-start', 0);
                    $('#pagination').find('ul li.prev').attr('data-start', Math.max(0, parseInt(filter.start) - filter.limit));
                    $('#pagination').find('ul li a.disp').text(Math.ceil(parseInt(filter.start) / filter.limit) + 1);
                    $('#pagination').find('ul li.filter').attr('data-limit', filter.limit);

                    if (totalPages != parseInt($('#pagination').find('ul li a.disp').text())) {
                        $('#pagination').find('ul li.next').attr('data-start', obj.limit === 10000000 ? 0 : parseInt(filter.start) + filter.limit);
                    } else {
                        $('#pagination').find('ul li.next').attr('data-start', (totalPages - 1) * filter.limit);
                    }

                    $('#pagination').find('ul li.last').attr('data-start', obj.limit === 10000000 ? 0 : (totalPages - 1) * filter.limit);
                } else {
                    $("#search").show();
                    $("#search_display_processing").css('display', 'none');
                    $('#table-content').html('<tr><td colspan="12" class="fieldEdit" style="text-align: center;">No record found.</td></tr>');
                    $('#showing').hide();
                    $('.pagination').hide();
                }
            } else {
                $("#search").show();
                $("#search_display_processing").css('display', 'none');
                $('#table-content').html('<tr><td colspan="12" class="fieldEdit" style="text-align: center;">No record found.</td></tr>');
                $('#showing').hide();
                $('.pagination').hide();
            }
        }
    });
}
