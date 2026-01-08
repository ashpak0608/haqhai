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
        filter.sub_module_short_name   = $('#sub_module_short_name').val();
        filter.module_id   = $('#module_id').val();
        if(filter.sub_module_short_name == '' && filter.module_id == ''){
            $("#sub_module_short_name_error").html('Please enter sub menu name').fadeIn().delay(3000).fadeOut();
            $("#module_id_error").html('Please select main menu').fadeIn().delay(3000).fadeOut();
            $('#sub_module_short_name').focus();
            return false;
        }
        filter.start     = start;
        filter.limit     = limit;
        filtering(filter);
    });

     $("#cancel").on("click",function(){ 
        $("#search_limits").val('10').trigger('change');
        $('#sub_module_short_name').val('');
        $('#module_id').val('').trigger('change');
        filter.sub_module_short_name   = '';
        filter.module_id   = '';
        filter.start     = start;
        filter.limit     = limit;
        filtering(filter);
    });


    $("#sub_menu_form").validate({
        onkeydown: false,
        onkeyup: false,
        onfocusin: false,
        onfocusout: false,
        errorElement: "div",
        rules: {
            sub_module_short_name: {
                required: true,
            },
            module_id: {
                required: true,
            },
        },
        messages: {
            sub_module_short_name: {
                required: "Please Enter sub menu Name.",
            },
            module_id: {
                required: "Please select main menu Name.",
            },
        },
        submitHandler: function (form) {
            queryString = $('#sub_menu_form').serialize();
            $("#submit_button").hide();
            $("#display_processing").css('display','inline-block');
            $.post(SITE_URL+'sub-menu/save', queryString, function (data) {
               commonStatusMessage(data, SITE_URL+'sub-menu');
            }, "json");
            return false;
        },
        errorPlacement: function (error, element) {
            showError(error, element);
        }
    });
    
    $("#submit_button").click(function () {
        $("#sub_menu_form").submit();
        return false;
    });

});

function statusUpdate(status,id,checkbox) {
    var url = SITE_URL + 'sub-menu/status/' + status+'/'+id;
    let indexUrl = SITE_URL+'sub-menu';
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
        url: SITE_URL + 'sub-menu/filter',
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
                        let add = SITE_URL + 'sub-menu/add/' + list[i]['id'];
                        let view = SITE_URL + 'sub-menu/view/' + list[i]['id'];
                        html += `<tr id="follow_up_row${i}">
                                    <td>${sr_no}</td>
                                    <td>${list[i]['module_name']}</td>
                                    <td>${list[i]['sub_module_short_name']}</td>
                                    <td>${list[i]['sequence']}</td>
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
