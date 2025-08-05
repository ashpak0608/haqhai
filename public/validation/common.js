$(document).ready(function () {
    $('.capital-input').on('keyup', function() {
        var upperCaseValue = $(this).val().toUpperCase();
        $(this).val(upperCaseValue);
    });

    $(".uppercase-input").on('input', function() {
        $(this).val($(this).val().toUpperCase());
    });
});
function exportModule(controller){
    window.location.href = controller;
}

function showError(error, element)
{
    if (element.is(":radio")) {
        error.insertAfter(element.parent().parent());
        error.removeClass('valid-feedback').addClass('invalid-feedback');
        element.removeClass('is-valid').addClass('is-invalid');
    } else { // This is the default behavior of the script
        if (element.attr('name') == "mobile" || element.attr('name') == "phone" || element.attr('type') == 'date' || element.attr('type') == 'email') {
            error.insertAfter(element);
            error.removeClass('valid-feedback').addClass('invalid-feedback');
            element.removeClass('is-valid').addClass('is-invalid');
        } else {
            error.insertAfter(element);
            error.removeClass('valid-feedback').addClass('invalid-feedback');
            element.removeClass('is-valid').addClass('is-invalid');
        }
    }
}

function commonStatusMessage(data, indexUrl) {
    if (data.status == 'success') { //0
        toastr.success(data.message);
        if (indexUrl) {
            window.location.href = indexUrl;
        }
        return true;
    } else if (data.status == 'error') { //1
        $('.invalid-feedback').text('');
        $('.is-invalid').removeClass('is-invalid');
        
        $.each(data.message, function (field, errors) {
            var fieldElement = $('#' + field);
            var errorElement = $('#' + field + '_error');
            fieldElement.addClass('is-invalid');
            $.each(errors, function (key, errorMessage) {
                errorElement.append('<div>' + errorMessage + '</div>');
            });
        });
        toastr.error('Please correct the highlighted errors.');
        $("#submit_button").show();
        $("#display_processing").css('display','none');
    } else if (data.status == 'exist') { //2
        toastr.warning(data.message);
        $("#submit_button").show();
        $("#display_processing").css('display','none');
    } else if (data.status == 'warning') { //2
        $("#submit_button").show();
        $("#display_processing").css('display','none');
        toastr.warning(data.message);
    }
}


function commonConfirmDelete(deleteUrl, indexUrl) {
    swal.fire({
        title: 'Are you sure?',
        text: "You won't be able to revert this!",
        type: 'warning',
        showCancelButton: true,
        confirmButtonText: 'Yes, delete it!',
        cancelButtonText: 'No, cancel!',
        reverseButtons: true
    }).then(function (result) {
        if (result.value) {
            $.ajax({
                 headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                },
                url: deleteUrl,
                type: "POST",
                data: {'_method': 'POST', "_token": "{{ csrf_token() }}"},
                dataType: 'json',
                processing: true,
                serverSide: true,
                success: function (data) {
                    swal.fire(
                            'Deleted!',
                            data.message,
                            'success'
                            );
                    window.location.href = indexUrl;
                },
                error: function (data) {
                    console.log(data.statusText);
                    swal.fire({
                        title: 'Opps...',
                        text: data.statusText,
                        type: 'error',
                        timer: '1500'
                    });
                }
            })
        }
    });
}

function commonConfirmation(deleteUrl, indexUrl) {
    swal.fire({
        title: 'Are you sure?',
        text: "You won't be able to revert this!",
        type: 'warning',
        showCancelButton: true,
        confirmButtonText: 'Yes, allow it!',
        cancelButtonText: 'No, cancel!',
        reverseButtons: true
    }).then(function (result) {
        if (result.value) {
            $.ajax({
                url: deleteUrl,
                type: "GET",
                data: {'_method': 'GET'},
                dataType: 'json',
                processing: true,
                serverSide: true,
                success: function (data) {
                    swal.fire(
                            'Allow!',
                            data.message,
                            'success'
                            );
                    window.location.href = indexUrl;
                },
                error: function (data) {
                    console.log(data.statusText);
                    swal.fire({
                        title: 'Opps...',
                        text: data.statusText,
                        type: 'error',
                        timer: '1500'
                    });
                }
            })
        }
    });
}


function commonConfirmationToggle(deleteUrl, indexUrl, checkboxElement) {
    swal.fire({
        title: 'Are you sure?',
        text: "Please confirm if you want to make this change",
        icon: 'warning',
        showCancelButton: true,
        confirmButtonText: 'Yes, allow it!',
        cancelButtonText: 'No, cancel!',
        reverseButtons: true
    }).then(function (result) {
        if (result.value) {
            $.ajax({
                url: deleteUrl,
                type: "GET",
                data: { '_method': 'GET' },
                dataType: 'json',
                success: function (data) {
                    swal.fire('Success!', data.message, 'success');
                    window.location.href = indexUrl;
                },
                error: function (data) {
                    swal.fire({
                        title: 'Oops...',
                        text: data.statusText,
                        icon: 'error',
                        timer: 1500
                    });
                    if (checkboxElement) {
                        checkboxElement.checked = !checkboxElement.checked;
                    }
                }
            });
        } else if (result.dismiss === swal.DismissReason.cancel) {
            // Revert checkbox
            if (checkboxElement) {
                checkboxElement.checked = !checkboxElement.checked;
            }
        }
    });
}

$(document).on("keyup", ".allow_numeric", function(evt) {
    var self = $(this);
     self.val(self.val().replace(/[^0-9\.]/g, ''));
      if ((evt.which != 46 || self.val().indexOf('.') != -1) && (evt.which < 48 || evt.which > 57)) 
      {
        evt.preventDefault();
      }
  });

  $(document).on("keyup", ".allow_alpha", function(evt) {
    var self = $(this);
    self.val(self.val().replace(/[^a-zA-Z. ]/g, '')); 
    if ((evt.which < 65 || evt.which > 90) && 
        (evt.which < 97 || evt.which > 122) && 
        evt.which !== 32 && 
        evt.which !== 46) { 
        evt.preventDefault();
    }
});

      
$(document).on("keyup", ".allow_decimal", function(evt) {
    var self = $(this);
    self.val(self.val().replace(/[^0-9\.]/g, ''));
    if ((evt.which != 46 || self.val().indexOf('.') != -1) && (evt.which < 48 || evt.which > 57)) 
    {
      evt.preventDefault();
    }
  });


function convertDateFormat(date){
    var dateComponents = date.split("-");
    var day = parseInt(dateComponents[0], 10);
    var month = parseInt(dateComponents[1], 10) - 1; // Months are 0-based in JavaScript
    var year = parseInt(dateComponents[2], 10);
    // Create a Date object
    return new Date(year, month, day);
  }
 
  function checkFromDateIsGreaterThanToDate(from_date,to_date){
    if (from_date && to_date) {
        // Parse the date strings into Date objects with the correct format
        var fromDateParts = from_date.split('-');
        var toDateParts = to_date.split('-');

        var fromDate = new Date(fromDateParts[2], fromDateParts[1] - 1, fromDateParts[0]);
        var toDate = new Date(toDateParts[2], toDateParts[1] - 1, toDateParts[0]);

        // Check if toDate is less than fromDate
        if (toDate < fromDate) {
            return false;
        }
    }
  }

$(document).on("click", ".discard-modal", function () {
    let modal = $(this).closest(".modal"); // Get the closest modal
    let form = modal.find("form"); // Find the form inside the modal

    if (form.length) {
        form[0].reset(); // Reset form fields
    }

    modal.modal("hide"); // Close the modal
});

function resetForm(formId) {
    let form = $('#' + formId);

    if (form.length) {
        form[0].reset(); // Reset all input values

        // Reset validation errors (if using jQuery Validation)
        let validator = form.validate();
        if (validator) {
            validator.resetForm(); // Clears error messages
        }

        // Remove error classes and validation styles
        form.find(".is-invalid, .error").removeClass("is-invalid error");
        form.find(".is-invalid, .error").removeClass("is-invalid error");
        // Remove required attributes if needed
        form.find("[required]").removeAttr("required");

        // Clear hidden input values
        form.find('input[type=hidden]').val('');
    } else {
        console.warn("Form with ID '" + formId + "' not found.");
    }
}

function getCity(branch_id,selectedCityId){
    if(branch_id){
        $.ajax({
           type: 'POST',
           url: SITE_URL+'get-cities',
           data: {
               branch_id:branch_id
           },success: function(response){
              var obj = $.parseJSON(response);
              if(obj){
                   $("#city_id").empty();
                   $("#city_id").append('<option value="">Select</option>');
                   for(var i=0;i<obj.length;i++){
                       $("#city_id").append('<option value="'+obj[i]['id']+'">'+obj[i]['city_name']+'</option>');
                   }
                   if(selectedCityId!=''){
                      $('#city_id').val(selectedCityId).trigger('change');
                   }
              }else{
                   $("#city_id").append('<option value="">Data not found</option>');
              }
           }
        })
    }
}

function getLocation(district_id){
    if(district_id){
        $.ajax({
            headers: { 'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content') },
           type: 'POST',
           url: SITE_URL+'get-locations',
           data: {
               district_id:district_id
           },success: function(response){
              var obj = $.parseJSON(response);
              if(obj){
                   $("#location_id").empty();
                   $("#location_id").append('<option value="">Select</option>');
                   for(var i=0;i<obj.length;i++){
                       $("#location_id").append('<option value="'+obj[i]['id']+'">'+obj[i]['location_name']+'</option>');
                   }
                   if(selectedCityId!=''){
                      $('#location_id').val(selectedCityId).trigger('change');
                   }
              }else{
                   $("#location_id").append('<option value="">Data not found</option>');
              }
           }
        })
    }
}