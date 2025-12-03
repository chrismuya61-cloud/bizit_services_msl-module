/**
 * Bizit Services MSL - Master JS File (V3.2)
 */

$(function() {

    // ==========================================================
    //  1. DATATABLE INITIALIZATION
    // ==========================================================
    
    // Core Services Table
    if ($('.table-services').length > 0) {
        initDataTable('.table-services', admin_url + 'services/manage', [4], [4], 'undefined', [0, 'ASC']);
    }

    // Service Requests (With Filters)
    if ($('.table-services_requests').length > 0) {
        var requestParams = {};
        if($('#from_date').length > 0) {
            requestParams['from_date'] = '[name="from_date"]';
            requestParams['to_date'] = '[name="to_date"]';
            requestParams['status_filter'] = '[name="status_filter"]';
        }
        initDataTable('.table-services_requests', admin_url + 'services/requests', [6], [6], requestParams, [0, 'DESC']);
    }

    // Rental Agreements (With Filters)
    if ($('.table-services_rental_agreements').length > 0) {
        var rentalParams = {};
        if($('#from_date').length > 0) {
            rentalParams['from_date'] = '[name="from_date"]';
            rentalParams['to_date'] = '[name="to_date"]';
        }
        initDataTable('.table-services_rental_agreements', admin_url + 'services/rental_agreements', [6], [6], rentalParams, [0, 'DESC']);
    }

    // Field Reports
    if ($('.table-services_field_report').length > 0) {
        initDataTable('.table-services_field_report', admin_url + 'services/field_reports', [5], [5], 'undefined', [0, 'DESC']);
    }

    // Sales List
    if ($('.table-services_sales_list').length > 0) {
        initDataTable('.table-services_sales_list', admin_url + 'services/sales_list', [], [], 'undefined', [3, 'DESC']);
    }
    
    // Service Categories
    if ($('.table-service_category').length > 0) {
        initDataTable('.table-service_category', admin_url + 'services/category_manage', [3], [3], 'undefined', [0, 'ASC']);
    }

    // Reload Tables on Filter Change
    $('#from_date, #to_date, select[name="status_filter"]').on('change', function() {
        if ($('.table-services_requests').length > 0) { $('.table-services_requests').DataTable().ajax.reload(); }
        if ($('.table-services_rental_agreements').length > 0) { $('.table-services_rental_agreements').DataTable().ajax.reload(); }
    });


    // ==========================================================
    //  2. MODAL LOGIC
    // ==========================================================

    // Services Modal (Add/Edit)
    $('body').on('show.bs.modal', '#services_modal', function(event) {
        var button = $(event.relatedTarget);
        var id = button.data('id');
        var $itemModal = $('#services_modal');

        $("form")[0].reset();

        // EDIT MODE
        if (typeof id !== 'undefined') {
            $('input[name="serviceid"]').val(id);
            $itemModal.find('.add-title').addClass('hide');
            $itemModal.find('.edit-title').removeClass('hide');
            
            // Lock Category in Edit Mode
            $('button[data-id="service_type_code"]').addClass('disabled');
            $itemModal.find('select[name="service_type_code"]').selectpicker('val', button.data('category')).attr('disabled', true);
            
            // Populate Fields
            $itemModal.find('input[name="service_code"]').val(id); // This maps to code
            $itemModal.find('input[name="name"]').val(button.data('name'));
            $itemModal.find('input[name="price"]').val(button.data('price'));
            $itemModal.find('textarea[name="description"]').val(button.data('description'));
            
            // Rental Specifics
            $itemModal.find('input[name="rental_serial"]').val(button.data('serial'));
            $itemModal.find('select[name="rental_duration_check"]').selectpicker('val', button.data('rental_duration_check'));
            $itemModal.find('select[name="rental_status"]').selectpicker('val', button.data('rental_status'));
            $itemModal.find('input[name="penalty_rental_price"]').val(button.data('penalty_rental_price'));
            $itemModal.find('input[name="quantity_unit"]').val(button.data('quantity_unit'));

            // Toggle Fields based on Type
            var type = $itemModal.find('select[name="service_type_code"]').selectpicker('val');
            toggleServiceFields(type);

        } else {
            // ADD MODE
            $itemModal.find('.add-title').removeClass('hide');
            $itemModal.find('.edit-title').addClass('hide');
            $('button[data-id="service_type_code"]').removeClass('disabled');
            $itemModal.find('select[name="service_type_code"]').attr('disabled', false).selectpicker('val', '').change();
            $itemModal.find('input').val('');
            
            // Auto-Generate Code on Category Change
            $('select[name="service_type_code"]').change(function() {
                var code = $(this).selectpicker('val');
                if (typeof code !== 'undefined' && code !== '') {
                    $.get(admin_url + "services/getNextServiceCode/" + code, function(response) {
                        $itemModal.find('input[name="service_code"]').val(response);
                    });
                    toggleServiceFields(code);
                }
            });
        }
    });

    // Helper to toggle Rental vs Service fields
    function toggleServiceFields(type) {
        if (type == '001') { // Rental
            $('#rental_fields').removeClass('hide');
            $('.quantity_unit_area').addClass('hide');
        } else { // Service
            $('#rental_fields').addClass('hide');
            $('.quantity_unit_area').removeClass('hide');
        }
    }

    // Category Modal
    $('body').on('show.bs.modal', '#service_category_modal', function(event) {
        var $itemModal = $('#service_category_modal');
        var button = $(event.relatedTarget);
        if (typeof(button.data('id')) == 'undefined') {
             $itemModal.find('input').val('');
        }
    });


    // ==========================================================
    //  3. INVOICE INTEGRATION
    // ==========================================================

    // Listen for "Service Category" selection in Invoice
    $('body').on('change', '.gen_add_service select[name="service_select"]', function () {
        var invoice_services = '.gen_add_service select[name="invoice_services"]';
        var _id = $(this).selectpicker('val');
        $(invoice_services).html('').append('<option value=""></option>');
        
        if (_id != '') {
            $.get(admin_url + 'services/get_services/' + _id, function (response) {
                $.each(response, function (i, obj) {
                    var serial = (obj.rental_serial != '' && obj.rental_serial != null ? "SN: " + obj.rental_serial : "Code: " + obj.service_code);
                    $(invoice_services).append('<option value="' + obj.service_code + '" data-subtext="' + serial + '">' + obj.name + '</option>');
                });
            }, 'json')
            .done(function () {
                $(invoice_services).removeClass('hide').addClass("selectpicker").selectpicker('refresh');
            });
        } else {
            $(invoice_services).selectpicker('destroy').addClass('hide');
        }
    });

    // Listen for specific Service selection -> Populate Invoice Rows
    $('body').on('change', '.gen_add_service select[name="invoice_services"]', function () {
        add_service_to_preview($(this).val());
    });

    function add_service_to_preview(code) {
        if(!code) return;
        $.get(admin_url + 'services/get_service_by_code/' + code, function (response) {
            if(!response) return;

            $('.main textarea[name="description"]').val(response.name);
            
            var description = response.category_name + ', Service Code: ' + response.service_code;
            if (response.service_type_code == '001' && response.rental_serial) {
                description += ', Serial No.: ' + response.rental_serial;
            }
            
            $('.main textarea[name="long_description"]').val(description);
            $('.main input[name="quantity"]').val(1);

            var qty_unit = response.quantity_unit;
            if (!qty_unit && response.rental_duration_check) {
                qty_unit = response.rental_duration_check; 
            }

            $('.main input[name="unit"]').val(qty_unit);
            $('.main input[name="rate"]').val(response.price);
            $('.main input[name="item_for"]').val($('input[name="invoice_for"]:checked, input[name="estimate_for"]:checked').val());

        }, 'json').fail(function () {
            alert_float('danger', "Service not found");
        });
    }


    // ==========================================================
    //  4. VALIDATION
    // ==========================================================

    if ($("#service_form").length > 0) {
        $("#service_form").validate({
            rules: {
                service_type_code: { required: true },
                name: { required: true },
                service_code: { required: true },
                price: { required: true }
            },
            submitHandler: function(form) {
                return manage_service(form);
            }
        });
    }

    if ($("#service_category_form").length > 0) {
        $("#service_category_form").validate({
            rules: {
                name: { required: true }
            },
            submitHandler: function(form) {
                return manage_service_category(form);
            }
        });
    }

});


// ==========================================================
//  5. CUSTOM FEATURE: SECTION HEADERS
// ==========================================================

function add_section_header() {
    // Generate unique index based on timestamp
    var unique_index = (new Date).getTime();
    var table_body = $('.invoice-items-table tbody, .estimate-items-table tbody, .proposal-items-table tbody');
    
    // Calculate colspan based on visible columns (dynamic)
    var total_columns = table_body.closest('table').find('thead th').length;
    var colspan = total_columns - 2; // Subtract drag column and delete action column

    var row = '<tr class="sortable item section-header-row" style="background-color: #f8f9fa;">';
    
    // Drag Handle
    row += '<td class="dragger"><input type="hidden" class="order" name="newitems[' + unique_index + '][order]"><i class="fa fa-bars"></i></td>';
    
    // Header Input Area
    row += '<td colspan="' + colspan + '" class="bold">';
    row += '<input type="text" name="newitems[' + unique_index + '][description]" class="form-control" style="font-weight:bold; font-size:14px; background:transparent; border:none; border-bottom:1px solid #ddd;" placeholder="SECTION HEADER (e.g. Phase 1 Requirements)">';
    
    // Hidden Fields to ensure it saves as a non-billable item with specific SECTION flag
    row += '<input type="hidden" name="newitems[' + unique_index + '][long_description]" value="">';
    row += '<input type="hidden" name="newitems[' + unique_index + '][unit]" value="SECTION">';
    row += '<input type="hidden" name="newitems[' + unique_index + '][qty]" value="0">';
    row += '<input type="hidden" name="newitems[' + unique_index + '][rate]" value="0">';
    row += '</td>';
    
    // Delete Button
    row += '<td><a href="#" class="btn btn-danger pull-left" onclick="delete_item(this); return false;"><i class="fa fa-times"></i></a></td>';
    
    row += '</tr>';

    // Append to table
    table_body.append(row);
    
    // Re-init sortable from Perfex core
    if(typeof init_items_sortable === 'function') {
        init_items_sortable(true);
    }
}


// ==========================================================
//  6. AJAX HANDLERS (Global Scope)
// ==========================================================

function manage_service(form) {
    var data = $(form).serialize();
    var url = form.action;
    $.post(url, data).done(function(response) {
        response = JSON.parse(response);
        if (response.success == true) {
            $('.table-services').DataTable().ajax.reload();
            alert_float('success', response.message);
            $('#services_modal').modal('hide');
        } else {
            alert_float('warning', response.message);
        }
    });
    return false;
}

function manage_service_category(form) {
    var data = $(form).serialize();
    var url = form.action;
    $.post(url, data).done(function(response) {
        response = JSON.parse(response);
        if (response.success == true) {
            $('.table-service_category').DataTable().ajax.reload();
            alert_float('success', response.message);
            $('#service_category_modal').modal('hide');
        }
    });
    return false;
}
