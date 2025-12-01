<?php defined('BASEPATH') or exit('No direct script access allowed');

$aColumns = [
    'type_code', 
    'name', 
    'status'
];
$sIndexColumn = "service_typeid";
$sTable = 'tblservice_type';

$additionalSelect = ['service_typeid', 'description'];
$result = data_tables_init($aColumns, $sIndexColumn, $sTable, [], [], $additionalSelect);
$output = $result['output'];
$rResult = $result['rResult'];

// Protected Codes from Original
$protected = ['001', '002', '003', '004', '005', '006'];

foreach ($rResult as $aRow) {
    $row = [];
    
    $row[] = $aRow['type_code'];
    $row[] = $aRow['name'];
    
    // Status
    $toggle = '<div class="onoffswitch">
        <input type="checkbox" data-switch-url="' . admin_url() . 'services/change_service_category_status" name="onoffswitch" class="onoffswitch-checkbox" id="cat_' . $aRow['type_code'] . '" data-id="' . $aRow['type_code'] . '" ' . ($aRow['status'] == 1 ? 'checked' : '') . '>
        <label class="onoffswitch-label" for="cat_' . $aRow['type_code'] . '"></label>
    </div>';
    $row[] = $toggle;

    // Options
    $options = '';
    if(has_permission(BIZIT_SERVICES_MSL,'','edit')){
        // Don't allow editing codes of protected types, but allow name edit
        $options .= '<a class="btn btn-default btn-icon" data-toggle="modal" data-target="#service_category_modal" 
            data-id="' . $aRow['service_typeid'] . '" 
            data-name="' . $aRow['name'] . '" 
            data-code="' . $aRow['type_code'] . '" 
            data-description="' . $aRow['description'] . '">
            <i class="fa fa-pencil-square-o"></i></a>';
    }

    if(has_permission(BIZIT_SERVICES_MSL,'','delete') && !in_array($aRow['type_code'], $protected)){
        $options .= icon_btn('services/delete_category/' . $aRow['type_code'], 'remove', 'btn-danger _delete');
    }

    $row[] = $options;
    $output['aaData'][] = $row;
}