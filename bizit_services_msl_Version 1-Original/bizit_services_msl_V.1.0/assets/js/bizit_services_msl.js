
//Dropzone.options.serviceReportsFilesUpload = false;

//==========================================================
//  Modals
//==========================================================

$('body').on('show.bs.modal', '#services_modal', function(event) {
    var button = $(event.relatedTarget);
    var id = button.data('id');
    var $itemModal = $('#services_modal');

    //clicking select
    /*  $('button[data-id="service_type_code"]').on('click', function(e){
        alert_float('warning', 'The service category field cannot be changed ones saved.')
      });*/
    $("form")[0].reset();

    // If id found get the text from the datatable
    if (typeof id !== 'undefined') {

        $('input[name="serviceid"]').val(id);
        $itemModal.find('.add-title').addClass('hide');
        $itemModal.find('.edit-title').removeClass('hide');
        $('button[data-id="service_type_code"]').addClass('disabled');
        $itemModal.find('select[name="service_type_code"]').selectpicker('val', button.data('category')).attr('disabled', true);
        $itemModal.find('input[name="service_code"]').val(id);
        $itemModal.find('input[name="name"]').val(button.data('name'));
        $itemModal.find('input[name="price"]').val(button.data('price'));
        $itemModal.find('textarea[name="description"]').val(button.data('description'));
        $itemModal.find('input[name="rental_serial"]').val(button.data('serial'));
        $itemModal.find('select[name="rental_duration_check"]').selectpicker('val', button.data('rental_duration_check'));
        $itemModal.find('select[name="rental_status"]').selectpicker('val', button.data('rental_status'));
        $itemModal.find('input[name="penalty_rental_price"]').val(button.data('penalty_rental_price'));
        $itemModal.find('input[name="quantity_unit"]').val(button.data('quantity_unit'));
        $itemModal.find('input[name="csrf_token_name"]').val(csrfData.formatted.csrf_token_name);

        var service_type_codes = $itemModal.find('select[name="service_type_code"]').selectpicker('val');
        if (service_type_codes == '001') {
            $('.rental_row').removeClass('hide');
            $('.quantity_unit_area').addClass('hide');
        } else {
            $('.rental_row').addClass('hide');

            if (service_type_code == '002'){
                $('.quantity_unit_area').removeClass('hide');
            }else{
                $('.quantity_unit_area').removeClass('hide');
            }
        }



    } else {
        $itemModal.find('.add-title').removeClass('hide');
        $itemModal.find('.edit-title').addClass('hide');
        $('button[data-id="service_type_code"]').removeClass('disabled');
        $itemModal.find('select[name="service_type_code"]').attr('disabled', false);
        $itemModal.find('input').val('');
        $itemModal.find('select[name="service_type_code"]').selectpicker('val', '').change();
        $itemModal.find('input[name="csrf_token_name"]').val(csrfData.formatted.csrf_token_name);

        $('select[name="service_type_code"]').change(function() {
            var service_type_code = $(this).selectpicker('val');
            if (typeof service_type_code !== 'undefined') {
                $.get(site_url + "bizit_services_msl/admin/services/getNextServiceCode/" + service_type_code, function(response) {
                    $itemModal.find('input[name="service_code"]').val(response);
                });
                if (service_type_code == '001') {
                    $('.rental_row').removeClass('hide');
                    $('.quantity_unit_area').addClass('hide');
                } else {
                    $('.rental_row').addClass('hide');

                    if (service_type_code == '002'){
                        $('.quantity_unit_area').removeClass('hide');
                    }else{
                        $('.quantity_unit_area').removeClass('hide');
                    }
                }
            }
            //$("form")[0].reset();
        });

    }
});

$('body').on('show.bs.modal', '#warranty_modal', function(event) {
    var button = $(event.relatedTarget);
    var id = button.data('id');
    var $itemModal = $('#services_modal');

    //clicking select
    /*  $('button[data-id="service_type_code"]').on('click', function(e){
        alert_float('warning', 'The service category field cannot be changed ones saved.')
      });*/
    $("form")[0].reset();

    // If id found get the text from the datatable
    if (typeof id !== 'undefined') {

        $('input[name="serviceid"]').val(id);
        $itemModal.find('.add-title').addClass('hide');
        $itemModal.find('.edit-title').removeClass('hide');
        $('button[data-id="service_type_code"]').addClass('disabled');
        $itemModal.find('select[name="service_type_code"]').selectpicker('val', button.data('category')).attr('disabled', true);
        $itemModal.find('input[name="service_code"]').val(id);
        $itemModal.find('input[name="name"]').val(button.data('name'));
        $itemModal.find('input[name="price"]').val(button.data('price'));
        $itemModal.find('textarea[name="description"]').val(button.data('description'));
        $itemModal.find('input[name="rental_serial"]').val(button.data('serial'));
        $itemModal.find('select[name="rental_duration_check"]').selectpicker('val', button.data('rental_duration_check'));
        $itemModal.find('select[name="rental_status"]').selectpicker('val', button.data('rental_status'));
        $itemModal.find('input[name="penalty_rental_price"]').val(button.data('penalty_rental_price'));
        $itemModal.find('input[name="quantity_unit"]').val(button.data('quantity_unit'));
        $itemModal.find('input[name="csrf_token_name"]').val(csrfData.formatted.csrf_token_name);

        var service_type_codes = $itemModal.find('select[name="service_type_code"]').selectpicker('val');
        if (service_type_codes == '001') {
            $('.rental_row').removeClass('hide');
            $('.quantity_unit_area').addClass('hide');
        } else {
            $('.rental_row').addClass('hide');

            if (service_type_code == '002'){
                $('.quantity_unit_area').removeClass('hide');
            }else{
                $('.quantity_unit_area').removeClass('hide');
            }
        }



    } else {
        $itemModal.find('.add-title').removeClass('hide');
        $itemModal.find('.edit-title').addClass('hide');
        $('button[data-id="service_type_code"]').removeClass('disabled');
        $itemModal.find('select[name="service_type_code"]').attr('disabled', false);
        $itemModal.find('input').val('');
        $itemModal.find('select[name="service_type_code"]').selectpicker('val', '').change();
        $itemModal.find('input[name="csrf_token_name"]').val(csrfData.formatted.csrf_token_name);

        $('select[name="service_type_code"]').change(function() {
            var service_type_code = $(this).selectpicker('val');
            if (typeof service_type_code !== 'undefined') {
                $.get(site_url + "bizit_services_msl/admin/services/getNextServiceCode/" + service_type_code, function(response) {
                    $itemModal.find('input[name="service_code"]').val(response);
                });
                if (service_type_code == '001') {
                    $('.rental_row').removeClass('hide');
                    $('.quantity_unit_area').addClass('hide');
                } else {
                    $('.rental_row').addClass('hide');

                    if (service_type_code == '002'){
                        $('.quantity_unit_area').removeClass('hide');
                    }else{
                        $('.quantity_unit_area').removeClass('hide');
                    }
                }
            }
            //$("form")[0].reset();
        });

    }
});

$('body').on('show.bs.modal', '#service_category_modal', function(event) {
    var button = $(event.relatedTarget);
    var $itemModal = $('#service_category_modal');

    $itemModal.find('input').val('');
    $itemModal.find('.add-title').removeClass('hide');
    $itemModal.find('.edit-title').addClass('hide');
    $itemModal.find('input[name="type_code"]').attr('readonly', 'readonly');
    $itemModal.find('input[name="csrf_token_name"]').val(csrfData.formatted.csrf_token_name);

    if (typeof(id) == 'undefined') {
        $.get(site_url + "bizit_services_msl/admin/services/getNextServiceCategoryCode", function(response) {
            $itemModal.find('input[name="type_code"]').val(response);
        });
    }

    $("[data-id]").click(function(ev) {
        //console.log($(this));
        var id = $(this).attr('data-id');
        var name = $(this).attr('data-name');
        var code = $(this).attr('data-code');


        // If id found get the text from the datatable
        if (typeof(id) !== 'undefined') {
            $('input[name="service_typeid"]').val(id);
            $itemModal.find('.add-title').addClass('hide');
            $itemModal.find('.edit-title').removeClass('hide');
            $itemModal.find('input[name="type_code"]').val(code);
            $itemModal.find('input[name="name"]').val(name);
            $itemModal.find('input[name="csrf_token_name"]').val(csrfData.formatted.csrf_token_name);
        }
    });
});

$('body').on('show.bs.modal', '#service_field_report_modal', function(event) {
    var button = $(event.relatedTarget);
    var id = button.data('id');
    var $itemModal = $('#service_field_report_modal');

    var _rental_agreement_code = $itemModal.find('input[name="rental_agreement_code"]').val();
    $itemModal.find('input[name="csrf_token_name"]').val(csrfData.formatted.csrf_token_name);

    $.get(site_url + "bizit_services_msl/admin/services/field_report/1/" + _rental_agreement_code, function(response) {
            $itemModal.find('input[name="report_code"]').val(response);
    });

});




//==========================================================
//  Managers
//==========================================================

function manage_service(form) {
    var data = $(form).serialize();
    var url = form.action;
    var optKey;
    if (form.id == 'service_form') {
        optKey = ['.table-services', '#services_modal'];
    }
    $.post(url, data).done(function(response) {
        //console.log(response);
        response = JSON.parse(response);
        if (response.success == true) {
            // Is general items view
            $(optKey[0]).DataTable().ajax.reload();

            alert_float('success', response.message);
            $(optKey[1]).modal('hide');
        } else {
            alert_float('warning', response.message);
        }
        //$(optKey[1]).modal('hide');
    }).fail(function(data) {
        alert_float('danger', data.responseText);
    });
    return false;
}

function manage_service_category(form) {
    var data = $(form).serialize();
    var url = form.action;
    var optKey;
    if (form.id == 'service_category_form') {
        optKey = ['.table-service_category', '#service_category_modal'];
    }
    $.post(url, data).done(function(response) {
        response = JSON.parse(response);
        if (response.success == true) {
            // Is general items view
            $(optKey[0]).DataTable().ajax.reload();

            alert_float('success', response.message);
            $(optKey[1]).modal('hide');
        } else {
            alert_float('warning', response.message);
        }
        //$(optKey[1]).modal('hide');
    }).fail(function(data) {
        alert_float('danger', data.responseText);
    });
    return false;
}


//==========================================================
//  Validations
//==========================================================

//service form
$().ready(function() {
    $("#service_form").validate({
        ignore: [],
        rules: {
            service_type_code: {
                required: true
            },
            name: {
                required: true
            },
            service_code: {
                required: true
            },
            price: {
                required: true
            }

        },
        submitHandler: function(element) {
            if ($(element).hasClass('disable-on-submit')) {
                $(element).find('[type="submit"]').attr('disabled', true);
            }
            if (typeof(manage_service) !== 'undefined') {
                manage_service(element);
            } else {
                return true;
            }
        },

        highlight: function(element) {
            $(element).closest('.form-group').addClass('has-error');
        },
        unhighlight: function(element) {
            $(element).closest('.form-group').removeClass('has-error');
        },
        errorElement: 'span',
        errorClass: 'help-block',
        errorPlacement: function(error, element) {
            if (element.parent('.input-group').length) {
                error.insertAfter(element.parent());
            } else {
                error.insertAfter(element);
            }
        }

    })
});

//service_category_form
$().ready(function() {
    $("#service_category_form").validate({
        ignore: [],
        rules: {
            type_code: {
                required: true
            },
            name: {
                required: true
            }

        },
        submitHandler: function(element) {
            if ($(element).hasClass('disable-on-submit')) {
                $(element).find('[type="submit"]').attr('disabled', true);
            }
            if (typeof(manage_service_category) !== 'undefined') {
                manage_service_category(element);
            } else {
                return true;
            }
        },

        highlight: function(element) {
            $(element).closest('.form-group').addClass('has-error');
        },
        unhighlight: function(element) {
            $(element).closest('.form-group').removeClass('has-error');
        },
        errorElement: 'span',
        errorClass: 'help-block',
        errorPlacement: function(error, element) {
            if (element.parent('.input-group').length) {
                error.insertAfter(element.parent());
            } else {
                error.insertAfter(element);
            }
        }

    })
});


    /*######### Specific to Invoice Services ########*/
    // Add Service using service categories
    $('body').on('change', '.gen_add_service select[name="service_select"]', function () {
        var invoice_services = '.gen_add_service select[name="invoice_services"]';
        var _id = $(this).selectpicker('val');
        $(invoice_services).html('').append('<option value=""></option>');
        if (_id != '') {
            $.get(site_url + 'bizit_services_msl/admin/services/get_services/' + _id, function (response) {
                $.each(response, function (i, obj) {
                    var serial = (obj.rental_serial != '' ? "SN: " + obj.rental_serial : "Service Code: " + obj.service_code);
                    $(invoice_services).append('<option value="' + obj.service_code + '" data-subtext="' + serial + '">' + obj.name + '</option>');
                    //console.log(obj.serial);
                });
            }, 'json')
                .done(function () {
                    $(invoice_services).removeClass('hide').addClass("selectpicker").selectpicker('refresh');
                })
                .fail(function () {
                    $(invoice_services).removeClass('hide').selectpicker('refresh');
                });
        } else {
            $(invoice_services).selectpicker('destroy').addClass('hide');
        }
    });

    //On Select Serial Number
    $('body').on('change', '.gen_add_service select[name="invoice_services"]', function () {
        add_service_to_preview($(this).val());
    });


    // Add Service to preview
    function add_service_to_preview(code) {
        $.get(site_url + 'bizit_services_msl/admin/services/get_service_by_code/' + code, function (response) {
            $('.main textarea[name="description"]').val(response.name);
            var description;
            if (response.service_type_code == '001') {
                description = response.category_name + ', Service Code: ' + response.service_code + ', Serial No.: ' + response.rental_serial;
            } else {
                description = response.category_name + ', Service Code: ' + response.service_code;
            }

            $('.main textarea[name="long_description"]').val(description);
            //$('.main input[name="serial"]').val(response.serial);
            $('.main input[name="quantity"]').val(1);

            var qty_unit;
            if (response.quantity_unit == '') {
                if (response.rental_duration_check != '') {
                    qty_unit = response.rental_duration_check;
                }
            } else {
                qty_unit = response.quantity_unit;
            }

            $('.main input[name="unit"]').attr({
                'readonly': 'readonly'
            }).val(qty_unit);

            $('.main input[name="rate"]').val(response.price);

            $('.main input[name="item_for"]').val($('input[name="invoice_for"]:checked, input[name="estimate_for"]:checked').val());
        }, 'json')
            .fail(function () {
                alert_float('danger', "That Service doesn't exist within this System");
            });

    }


//==========================================================
//  General Functions
//==========================================================

function change_operator(){
    $('.field_operator_area').addClass('hide');
    $('.field_operator_edit_area').removeClass('hide');

    
    var $itemModal = $('.field_operator_edit_area');
    $itemModal.find('input[name="csrf_token_name"]').val(csrfData.formatted.csrf_token_name);
}

/* $().ready(function(){
    $itemModal = $('#service_field_report_form');
    var id = $itemModal.find('input[name="field_report_id"]').val();
    $.get(site_url + "bizit_services_msl/admin/services/upload_file/field_report/" + id, function(data) {
               $itemModal.find('[id="report_files"]').html(data);
    //Upload files in drop zone
         if ($('#service-reports-files-upload').length > 0) {
                  purchaseFilesUpload = new Dropzone('#service-reports-files-upload', {
                  paramName: "file",
                  addRemoveLinks: true,
                  dictFileTooBig: appLang.file_exceeds_maxfile_size_in_form,
                  dictDefaultMessage: appLang.drop_files_here_to_upload,
                  dictFallbackMessage: appLang.browser_not_support_drag_and_drop,
                  dictRemoveFile: appLang.remove_file,
                  dictCancelUpload: appLang.cancel_upload,
                  acceptedFiles: app_allowed_files,
                  maxFilesize: (max_php_ini_upload_size_bytes / (1024*1024)).toFixed(0),
                  accept: function(file, done) {
                      done();
                  },
                  success: function(file, response) {
                      if (this.getUploadingFiles().length === 0 && this.getQueuedFiles().length === 0) {
                          $('.table-services_report_files').DataTable().ajax.reload();
                      }
                  },
                  error: function(file, response) {
                      alert_float('danger', response);
                  },
                  sending: function(file, xhr, formData) {
                      //formData.append("visible_to_customer", $('input[name="visible_to_customer"]').prop('checked'));
                  }
              });
          }
      });
}); */