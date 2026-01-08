var filter = new Object();
var start = 0;
var limit = 10;
var element = $('.card-body');

$.ajaxSetup({
    headers: {
        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
    }
});
$(document).ready(function () {
    $("#user_role_form").validate({
        onkeydown: false,
        onkeyup: false,
        onfocusin: false,
        onfocusout: false,
        errorElement: "div",
        rules: {
            role_id: {
                required: true,
            },
            permissions: {
                require_at_least_one: true
            }
        },
        messages: {
            role_id: "Please enter Role Name",
            permissions: 'Please select at least one permission'
        },
        submitHandler: function (form) {
            queryString = $('#user_role_form').serialize();
            $("#submit_button").hide();
            $("#display_processing").css('display','block');
            $.post(SITE_URL+'user-page-access/save', queryString, function (data) {
               commonStatusMessage(data, SITE_URL+'user-page-access');
            }, "json");
            return false;
        },
        errorPlacement: function (error, element) {
            showError(error, element);
        }
    });
    
    $("#submit_button").click(function () {
        $("#user_role_form").submit();
        return false;
    });

    $.validator.addMethod('require_at_least_one', function(value, element) {
        return $('input[name="read[]"]:checked, input[name="write[]"]:checked, input[name="create[]"]:checked').length > 0;
    }, 'Please select at least one permission');


    filter.start = start;
    let limit = $('#search_limits').val(); 

    $('#search_limits').on('change', function () {
        start = 0;
        limit = $(this).val();
        filter.start = start;
        filter.limit = limit;
        filter.role_id   = $('#role_id').val();
        filtering(filter);
    });

     // Pagination click event (Next, Previous, etc.)
     element.on('click', '#pagination ul li.filter', function(){
        var newStart = parseInt($(this).attr('data-start'));
        if (newStart >= 0) {
            filter.start = newStart;
            filter.limit = limit;
            filter.role_id   = $('#role_id').val();
            filtering(filter);
        }
    });
    
    $("#search").on("click",function(){ 
        filter.role_id   = $('#role_id').val();
        if(filter.country_name == ''){
            $("#role_id_error").html('Please select role').fadeIn().delay(3000).fadeOut();
            $('#role_id').focus();
            return false;
        }
        filter.start     = start;
        filter.limit     = limit;
        filtering(filter);
    });

     $("#cancel").on("click",function(){ 
        $("#search_limits").val('10').trigger('change');
        $('#role_id').val('').trigger('change');
        filter.role_id   = '';
        filter.start     = start;
        filter.limit     = limit;
        filtering(filter);
    });

});

$('#select_all').change(function() {
    var isChecked = $(this).is(':checked');
    $('input[type=checkbox]').prop('checked', isChecked);
});

function statusUpdate(status, id, checkbox) {
    var url = SITE_URL + 'user-page-access/status/' + status+'/'+id;
    let indexUrl = '';//SITE_URL+'user-page-access';
    commonConfirmationToggle(url, indexUrl, checkbox);
}

// Filtering Function
function filtering(filter) {
    $("#search").hide();
    $("#search_display_processing").css('display', 'inline-block');
    let start = filter.start 
    $.ajax({
        headers: { 'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content') },
        type: 'POST',
        url: SITE_URL + 'user-page-access/filter',
        data: filter,
        success: function(response) {
            let obj = $.parseJSON(response);
            if (obj.status == 'success') {
                $("#search").show();
                $("#search_display_processing").css('display', 'none');

                if (obj.total_count > 0) {
                    let html = '';
                    let list = obj.lists;
                    for (let i = 0; i < list.length; i++) {
                        let statusChecked = list[i]['status'] == 0 ? 'checked' : '';
                        let id = list[i]['id'];
                        let role_id = list[i]['role_id'];
                        let status = list[i]['status'];
                        let sr_no = i + 1 + start;
                        let add = SITE_URL + 'user-page-access/add/' + list[i]['role_id'];
                        let view = SITE_URL + 'user-page-access/view/' + list[i]['role_id'];
                        html += `<tr id="follow_up_row${i}">
                                    <td>${sr_no}</td>
                                    <td>${list[i]['role_name']}</td>
                                    <td>
                                        <label class="form-check form-switch form-switch-sm form-check-custom form-check-solid">
                                            <input class="form-check-input" name="status" type="checkbox" value="${status}" onchange="statusUpdate(${status}, ${role_id},this)" ${statusChecked} />
                                            <span class="form-check-label fw-semibold text-muted" for="status"></span>
                                        </label>
                                    </td>
                                    <td>
                                        <a href="${add}" class="btn btn-icon btn-active-light-info w-30px h-30px me-3"><i class="ki-outline ki-pencil text-info fs-3"></i></a>
                                    </td>
                                </tr>`;
                    }
                    $('#table-content').html(html);
                    $('.pagination').show();
                    $('#showing').show().html(obj.message);

                    // Pagination Handling
                    const totalRecords = obj.total_count;
                    const totalPages = Math.ceil(totalRecords / filter.limit);
                    const currentPage = Math.floor(filter.start / filter.limit) + 1;

                    // Update Pagination
                    $('#pagination ul li.strt').attr('data-start', 0);
                    $('#pagination ul li.prev').attr('data-start', Math.max(0, filter.start - filter.limit));
                    $('#pagination ul li.next').attr('data-start', Math.min(filter.start + filter.limit, (totalPages - 1) * filter.limit));
                    $('#pagination ul li.last').attr('data-start', (totalPages - 1) * filter.limit);
                    
                    // Update Page Number
                    $('#pagination ul li a.disp').text(currentPage);

                    // Disable Prev button on first page
                    if (filter.start == 0) {
                        $('#pagination ul li.prev').addClass('disabled');
                    } else {
                        $('#pagination ul li.prev').removeClass('disabled');
                    }

                    // Disable Next button on last page
                    if (currentPage >= totalPages) {
                        $('#pagination ul li.next').addClass('disabled');
                    } else {
                        $('#pagination ul li.next').removeClass('disabled');
                    }

                } else {
                    showNoRecords();
                }
            } else {
                showNoRecords();
            }
        }
    });
}

// Show No Records Function
function showNoRecords() {
    $("#search").show();
    $("#search_display_processing").css('display', 'none');
    $('#table-content').html('<tr><td colspan="12" class="fieldEdit" style="text-align: center;">No record found.</td></tr>');
    $('#showing').hide();
    $('.pagination').hide();
}