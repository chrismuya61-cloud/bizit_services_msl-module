/** Bizit Services MSL - Master JS File (V3.2) */
$(function() {
    if ($('.table-services').length > 0) initDataTable('.table-services', admin_url + 'services/manage', [4], [4], 'undefined', [0, 'ASC']);
    if ($('.table-services_requests').length > 0) initDataTable('.table-services_requests', admin_url + 'services/requests', [6], [6], {}, [0, 'DESC']);
    if ($('.table-services_rental_agreements').length > 0) initDataTable('.table-services_rental_agreements', admin_url + 'services/rental_agreements', [6], [6], {}, [0, 'DESC']);
    if ($('.table-services_field_report').length > 0) initDataTable('.table-services_field_report', admin_url + 'services/field_reports', [5], [5], 'undefined', [0, 'DESC']);
    
    $('body').on('show.bs.modal', '#services_modal', function(event) {
        var button = $(event.relatedTarget); var id = button.data('id'); var $itemModal = $('#services_modal');
        $("form")[0].reset();
        if (typeof id !== 'undefined') {
            $('input[name="serviceid"]').val(id);
            $itemModal.find('.add-title').addClass('hide'); $itemModal.find('.edit-title').removeClass('hide');
            $itemModal.find('input[name="name"]').val(button.data('name'));
            $itemModal.find('input[name="price"]').val(button.data('price'));
        } else {
            $itemModal.find('.add-title').removeClass('hide'); $itemModal.find('.edit-title').addClass('hide');
        }
    });

    $('body').on('change', '.gen_add_service select[name="invoice_services"]', function () {
        var code = $(this).val(); if(!code) return;
        $.get(admin_url + 'services/get_service_by_code/' + code, function (response) {
            if(!response) return;
            $('.main textarea[name="description"]').val(response.name);
            $('.main input[name="rate"]').val(response.price);
        }, 'json');
    });
});

// --- RESTORED HEADER FEATURE ---
function add_section_header() {
    var unique_index = (new Date).getTime();
    var table_body = $('.invoice-items-table tbody, .estimate-items-table tbody');
    var total_columns = table_body.closest('table').find('thead th').length;
    var colspan = total_columns - 2;
    var row = '<tr class="sortable item section-header-row" style="background-color: #f8f9fa;">';
    row += '<td class="dragger"><input type="hidden" class="order" name="newitems[' + unique_index + '][order]"><i class="fa fa-bars"></i></td>';
    row += '<td colspan="' + colspan + '" class="bold"><input type="text" name="newitems[' + unique_index + '][description]" class="form-control" style="font-weight:bold; background:transparent; border:none;" placeholder="SECTION HEADER">';
    row += '<input type="hidden" name="newitems[' + unique_index + '][unit]" value="SECTION"><input type="hidden" name="newitems[' + unique_index + '][qty]" value="0"><input type="hidden" name="newitems[' + unique_index + '][rate]" value="0"></td>';
    row += '<td><a href="#" class="btn btn-danger pull-left" onclick="delete_item(this); return false;"><i class="fa fa-times"></i></a></td></tr>';
    table_body.append(row);
    if(typeof init_items_sortable === 'function') init_items_sortable(true);
}
