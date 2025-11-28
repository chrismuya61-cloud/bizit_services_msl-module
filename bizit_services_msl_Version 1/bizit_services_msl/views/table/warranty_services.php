<?php
defined('BASEPATH') OR exit('No direct script access allowed');

$_not_to_delete = SERVICES_NOT_TO_DELETE;

$aColumns = array(
    'tblservices_module.name',            // Product Name (Title) as the first column
    'tblservices_module.rental_serial',    // Serial as the second column
    'tblservice_type.name',                // Category as the third column
    'tblservices_module.date_sold',        // Date Sold as the fourth column
    'tblservices_module.warranty_days_remaining', // Warranty Days Remaining as the fifth column
    'tblservices_module.warranty_end_date',       // Warranty End Date as the sixth column
    'tblservices_module.status'            // Status as the seventh column
);

$sIndexColumn = "serviceid";
$sTable = "tblservices_module";

$join = array(
    'LEFT JOIN tblservice_type ON (tblservice_type.type_code = tblservices_module.service_type_code)'
);

$additionalSelect = array(
    'tblservices_module.service_code',
    'tblservices_module.service_type_code as type_code',
    'tblservices_module.description as description',
    'tblservices_module.price as price',
    'tblservices_module.rental_status as rental_status',
    'tblservices_module.date_sold as date_sold',           // Date Sold
    'tblservices_module.warranty_days_remaining as warranty_days_remaining', // Warranty Days Remaining
    'tblservices_module.warranty_end_date as warranty_end_date', // Warranty End Date
    'penalty_rental_price',
    'rental_duration_check',
    'quantity_unit'
);

$result = data_tables_init($aColumns, $sIndexColumn, $sTable, $join, array(' AND tblservice_type.status = 1'), $additionalSelect);
$output = $result['output'];
$rResult = $result['rResult'];

foreach ($rResult as $aRow) {
    $row = array();
    foreach ($aColumns as $column) {
        if (strpos($column, 'as') !== false && !isset($aRow[$column])) {
            $_data = $aRow[strafter($column, 'as ')];
        } else {
            $_data = $aRow[$column];
        }

        // Custom display for the Product Name
        if ($column == 'tblservices_module.name' && $aRow['type_code'] == '001') {
            $_data = $aRow['tblservices_module.name'] . 
                     ' <span class="badge hidden" style="font-weight:normal; font-size:9px;">' . 
                     $aRow['rental_status'] . 
                     '</span>';
        }

        // Custom display for Serial
        if ($column == 'tblservices_module.rental_serial') {
            $_data = !empty($aRow['serial']) ? $aRow['serial'] : '<i class="fa fa-ellipsis-h"></i>';
        }

        // Format Date Sold
        if ($column == 'tblservices_module.date_sold') {
            $_data = !empty($aRow['date_sold']) ? date('Y-m-d', strtotime($aRow['date_sold'])) : '-';
        }

        // Calculate Warranty Days Remaining
        if ($column == 'tblservices_module.warranty_days_remaining') {
            $warrantyEndDate = $aRow['warranty_end_date'];
            $_data = !empty($warrantyEndDate) ? max(0, (strtotime($warrantyEndDate) - time()) / 86400) : 'N/A';
        }

        // Format Warranty End Date
        if ($column == 'tblservices_module.warranty_end_date') {
            $_data = !empty($aRow['warranty_end_date']) ? date('Y-m-d', strtotime($aRow['warranty_end_date'])) : '-';
        }

        // Toggle active/inactive Service
        if ($column == 'tblservices_module.status' && !in_array($aRow['service_code'], $_not_to_delete)) {
            $_data = '<div class="onoffswitch" data-toggle="tooltip" data-title="' . _l('customer_active_inactive_help') . '">
            <input type="checkbox" data-switch-url="' . admin_url().'services/change_service_status" name="onoffswitch" class="onoffswitch-checkbox" id="' . $aRow['service_code'] . '" data-id="' . $aRow['service_code'] . '" ' . ($aRow['status'] == 1 ? 'checked' : '') . '>
            <label class="onoffswitch-label" for="' . $aRow['service_code'] . '"></label>
            </div>';
        } elseif ($column == 'tblservices_module.status' && in_array($aRow['service_code'], $_not_to_delete)) {
            $_data = '';
        }

        $row[] = $_data;
    }

    $options = '';
    if (has_permission(BIZIT_SERVICES_MSL, '', 'edit')) {
        $options .= '<a class="btn btn-default btn-icon edit-service-btn" data-toggle="modal" data-target="#services_modal" data-id="' . $aRow['service_code'] . '" data-category="' . $aRow['type_code'] . '" data-description="' . $aRow['description'] . '" data-price="' . $aRow['price'] . '" data-name="' . $aRow['name'] . '" data-serial="' . $aRow['serial'] . '" data-penalty_rental_price="'. $aRow['penalty_rental_price'] .'" data-rental_duration_check="'. $aRow['rental_duration_check'] .'" data-rental_status="'. $aRow['rental_status'] .'" data-quantity_unit="'.$aRow['quantity_unit'].'" ><i class="fa fa-edit"></i></a>';
    }
    if (has_permission(BIZIT_SERVICES_MSL, '', 'delete') && !in_array($aRow['service_code'], $_not_to_delete)) {
        $options .= icon_btn('services/delete/' . $aRow['service_code'], 'fa fa-remove', 'btn-danger _delete');
    }
    $row[] = $options;

    $output['aaData'][] = $row;
}
