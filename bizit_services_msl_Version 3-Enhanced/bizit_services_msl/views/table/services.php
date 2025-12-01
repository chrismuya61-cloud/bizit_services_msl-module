<?php defined('BASEPATH') or exit('No direct script access allowed');

// Original Constants
$not_to_delete = defined('SERVICES_NOT_TO_DELETE') ? SERVICES_NOT_TO_DELETE : ['003-0001', '004-0001', '004-0002', '004-0003', '004-0004', '005-0001', '006-0001', '006-0002'];

$aColumns = [
    'tblservices_module.service_code',
    'tblservices_module.name', 
    'tblservice_type.name',   
    'tblservices_module.status'
];
$sIndexColumn = "serviceid";
$sTable = "tblservices_module";

$join = ['LEFT JOIN tblservice_type ON (tblservice_type.type_code = tblservices_module.service_type_code)'];
$additionalSelect = [
    'tblservices_module.service_type_code as type_code',
    'tblservices_module.rental_serial as serial',
    'tblservices_module.description as description',
    'tblservices_module.price as price',
    'tblservices_module.rental_status as rental_status',
    'penalty_rental_price',
    'rental_duration_check',
    'quantity_unit',
    'serviceid'
];

$result = data_tables_init($aColumns, $sIndexColumn, $sTable, $join, [], $additionalSelect);
$output = $result['output'];
$rResult = $result['rResult'];

foreach ($rResult as $aRow) {
    $row = [];

    // 1. Code
    $row[] = $aRow['tblservices_module.service_code'];
    
    // 2. Name
    $row[] = $aRow['tblservices_module.name'];
    
    // 3. Category
    $row[] = $aRow['tblservice_type.name'];

    // 4. Status Toggle
    $toggle = '';
    // Only show toggle if not in protected list
    if (!in_array($aRow['tblservices_module.service_code'], $not_to_delete)) {
        $toggle = '<div class="onoffswitch">
            <input type="checkbox" data-switch-url="' . admin_url() . 'services/change_service_status" name="onoffswitch" class="onoffswitch-checkbox" id="c_' . $aRow['tblservices_module.service_code'] . '" data-id="' . $aRow['tblservices_module.service_code'] . '" ' . ($aRow['tblservices_module.status'] == 1 ? 'checked' : '') . '>
            <label class="onoffswitch-label" for="c_' . $aRow['tblservices_module.service_code'] . '"></label>
        </div>';
    }
    $row[] = $toggle;

    // 5. Options
    $options = '';
    if(has_permission(BIZIT_SERVICES_MSL,'','edit')){
        // Restored ALL data attributes needed for the modal
        $options .= '<a class="btn btn-default btn-icon" data-toggle="modal" data-target="#services_modal" 
            data-id="' . $aRow['tblservices_module.service_code'] . '" 
            data-category="' . $aRow['type_code'] . '" 
            data-description="' . $aRow['description'] . '" 
            data-price="' . $aRow['price'] . '" 
            data-name="' . $aRow['tblservices_module.name'] . '" 
            data-serial="' . $aRow['serial'] . '" 
            data-penalty_rental_price="'. $aRow['penalty_rental_price'] .'" 
            data-rental_duration_check="'. $aRow['rental_duration_check'] .'" 
            data-rental_status="'. $aRow['rental_status'] .'" 
            data-quantity_unit="'.$aRow['quantity_unit'].'" >
            <i class="fa fa-pencil-square-o"></i></a>';
    }
    
    if(has_permission(BIZIT_SERVICES_MSL,'','delete') && !in_array($aRow['tblservices_module.service_code'], $not_to_delete)){
        $options .= icon_btn('services/delete/' . $aRow['tblservices_module.service_code'], 'remove', 'btn-danger _delete');
    }

    $row[] = $options;
    $output['aaData'][] = $row;
}