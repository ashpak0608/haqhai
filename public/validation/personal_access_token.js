var filter = new Object();
var start = 0;
var limit = 10;
var element = $('.card-body');

$(document).ready(function () {

    filter.start = start;
    let limit = $('#search_limits').val(); 

    $('#search_limits').on('change', function () {
        start = 0;
        limit = $(this).val();
        filter.start = start;
        filter.limit = limit;
        filtering(filter);
    });

     // Pagination click event (Next, Previous, etc.)
     element.on('click', '#pagination ul li.filter', function(){
        var newStart = parseInt($(this).attr('data-start'));
        if (newStart >= 0) {
            filter.start = newStart;
            filter.limit = limit;
            filtering(filter);
        }
    });
    
    $("#search").on("click",function(){ 
        filter.tokenable_type   = $('#tokenable_type_search').val();
        if(filter.tokenable_type == ''){
            $("#tokenable_type_search_error").html('Please enter tokenable type').fadeIn().delay(3000).fadeOut();
            $('#tokenable_type_search').focus();
            return false;
        }
        filter.start     = start;
        filter.limit     = limit;
        filtering(filter);
    });

     $("#cancel").on("click",function(){ 
        $("#search_limits").val('10').trigger('change');
        $('#tokenable_type_search').val('');
        filter.tokenable_type   = '';
        filter.start     = start;
        filter.limit     = limit;
        filtering(filter);
    });


    $("#personal_access_token_form").validate({
        onkeydown: false,
        onkeyup: false,
        onfocusin: false,
        onfocusout: false,
        errorElement: "div",
        rules: {
            tokenable_type: {
                required: true,
            },
            tokenable_id: {
                required: true,
            },
            name: {
                required: true,
            },
            token: {
                required: true,
            },
            abilities: {
                required: true,
            },
            last_used_at: {
                required: true,
            },
            expires_at: {
                required: true,
            },
        },
        messages: {
            tokenable_type: {
                required: "Please Enter page Name.",
            },
            tokenable_id: {
                required: "Please Enter tokenable ID.",
            },
            name: {
                required: "Please Enter Name.",
            },
            token: {
                required: "Please Enter token.",
            },
            abilities: {
                required: "Please Enter abilities.",
            },
            last_used_at: {
                required: "Please Enter last Used At.",
            },
            expires_at: {
                required: "Please Enter expires at.",
            },
        },
        submitHandler: function (form) {
            queryString = $('#personal_access_token_form').serialize();
            $("#submit_button").hide();
            $("#display_processing").css('display','inline-block');
            $.post(SITE_URL+'personal-access-token/save', queryString, function (data) {
               commonStatusMessage(data, SITE_URL+'personal-access-token');
            }, "json");
            return false;
        },
        errorPlacement: function (error, element) {
            showError(error, element);
        }
    });
    
    $("#submit_button").click(function () {
        $("#personal_access_token_form").submit();
        return false;
    });

});

function statusUpdate(status,id,checkbox) {
    var url = SITE_URL + 'personal-access-token/status/' + status+'/'+id;
    commonConfirmation(url, SITE_URL + 'personal-access-token');
}

// Filtering Function
function filtering(filter) {
    $("#search").hide();
    $("#search_display_processing").css('display', 'inline-block');
    let start = filter.start 
    $.ajax({
        headers: { 'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content') },
        type: 'POST',
        url: SITE_URL + 'personal-access-token/filter',
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
                        let status = list[i]['status'];
                        let sr_no = i + 1 + start;
                        let add = SITE_URL + 'country/add/' + list[i]['id'];
                        let view = SITE_URL + 'country/view/' + list[i]['id'];
                        html += `<tr id="follow_up_row${i}">
                                    <td>${list[i]['tokenable_type']}</td>
                                    <td>${list[i]['tokenable_id']}</td>
                                    <td>${list[i]['name']}</td>
                                    <td>${list[i]['token']}</td>
                                    <td>${list[i]['abilities']}</td>
                                    <td>${list[i]['last_used_at']}</td>
                                    <td>${list[i]['expires_at']}</td>
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
