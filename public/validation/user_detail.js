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
        filter.user_id   = $('#user_id_search').val();
        if(filter.user_id == ''){
            $("#user_id_search_error").html('Please enter user name').fadeIn().delay(3000).fadeOut();
            $('#user_id_search').focus();
            return false;
        }
        filter.start     = start;
        filter.limit     = limit;
        filtering(filter);
    });

     $("#cancel").on("click",function(){ 
        $("#search_limits").val('10').trigger('change');
        $('#user_id_search').val('');
        filter.user_id   = '';
        filter.start     = start;
        filter.limit     = limit;
        filtering(filter);
    });


    $("#user_details_form").validate({
        onkeydown: false,
        onkeyup: false,
        onfocusin: false,
        onfocusout: false,
        errorElement: "div",
        rules: {
            user_id: {
                required: true,
            },
            aadhar_no: {
                required: true,
            },
            pan_no: {
                required: true,
            },
            phone2: {
                required: true,
            },
            address: {
                required: true,
            },
            pincode: {
                required: true,
            },
            city_id: {
                required: true,
            },
            lat: {
                required: true,
            },
            long: {
                required: true,
            },
            landmark: {
                required: true,
            },
        },
        messages: {
            user_id: {
                required: "Please Enter user Name.",
            },
            aadhar_no: {
                required: "Please Enter Aadhar No.",
            },
            pan_no: {
                required: "Please Enter pan No.",
            },
            phone2: {
                required: "Please Enter phone No.",
            },
            address: {
                required: "Please Enter Address.",
            },
            pincode: {
                required: "Please Enter pincode.",
            },
            city_id: {
                required: "Please Enter city Name.",
            },
            lat: {
                required: "Please Enter lat.",
            },
            long: {
                required: "Please Enter long.",
            },
            landmark: {
                required: "Please Enter landmark.",
            },
        },
        submitHandler: function (form) {
            queryString = $('#user_details_form').serialize();
            $("#submit_button").hide();
            $("#display_processing").css('display','inline-block');
            $.post(SITE_URL+'user-detail/save', queryString, function (data) {
               commonStatusMessage(data, SITE_URL+'user-detail');
            }, "json");
            return false;
        },
        errorPlacement: function (error, element) {
            showError(error, element);
        }
    });
    
    $("#submit_button").click(function () {
        $("#user_details_form").submit();
        return false;
    });
});

function statusUpdate(status,id,checkbox) {
    var url = SITE_URL + 'user-detail/status/' + status+'/'+id;
    commonConfirmation(url, SITE_URL + 'user-detail');
}

// Filtering Function
function filtering(filter) {
    $("#search").hide();
    $("#search_display_processing").css('display', 'inline-block');
    let start = filter.start 
    $.ajax({
        headers: { 'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content') },
        type: 'POST',
        url: SITE_URL + 'user-detail/filter',
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
                                    <td>${list[i]['user_id']}</td>
                                    <td>${list[i]['aadhar_no']}</td>
                                    <td>${list[i]['pan_no']}</td>
                                    <td>${list[i]['phone2']}</td>
                                    <td>${list[i]['address']}</td>
                                    <td>${list[i]['pincode']}</td>
                                    <td>${list[i]['city_id']}</td>
                                    <td>${list[i]['lat']}</td>
                                    <td>${list[i]['long']}</td>
                                    <td>${list[i]['landmark']}</td>
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
