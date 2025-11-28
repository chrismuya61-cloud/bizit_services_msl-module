<?php
defined('BASEPATH') OR exit('No direct script access allowed');

$aColumns     = array(
    'tblservice_type.type_code as code',
    'tblservice_type.name as name',
    'tblservice_type.status as status'
    );
$sIndexColumn = "service_typeid";
$sTable       = 'tblservice_type';

$join             = array();
$additionalSelect = array(
'tblservice_type.service_typeid'
    );
$result           = data_tables_init($aColumns, $sIndexColumn, $sTable, $join, array(), $additionalSelect);
$output           = $result['output'];
$rResult          = $result['rResult'];

foreach ($rResult as $aRow) {

    $row = array();
    for ($i = 0; $i < count($aColumns); $i++) {

        if (strpos($aColumns[$i], 'as') !== false && !isset($aRow[$aColumns[$i]])) {
            $_data = $aRow[strafter($aColumns[$i], 'as ')];
        } else {
            $_data = $aRow[$aColumns[$i]];
        } 
         // Toggle active/inactive Service
        if($aColumns[$i] == 'tblservice_type.status as status'){ 
        $toggleActive = '<div class="onoffswitch" data-toggle="tooltip" data-title="' . _l('customer_active_inactive_help') . '">
        <input type="checkbox" data-switch-url="' . admin_url().'services/change_service_category_status" name="onoffswitch" class="onoffswitch-checkbox" id="' . $aRow['code'] . '" data-id="' . $aRow['code'] . '" ' . ($aRow['status'] == 1 ? 'checked' : '') . '>
        <label class="onoffswitch-label" for="' . $aRow['code'] . '"></label>
        </div>';

        $_data = $toggleActive;
        
        }       

        $row[] = $_data;
    }
    $options = '';
    if(has_permission(BIZIT_SERVICES_MSL,'','edit') and $aRow['code'] != '001' and $aRow['code'] != '002' and $aRow['code'] != '003' and $aRow['code'] != '004' and $aRow['code'] != '005' and $aRow['code'] != '006'){
        $options .= '<a class="btn btn-default btn-icon" data-name="'.$aRow['name'].'" data-code="'.$aRow['code'].'" data-id="'.$aRow['service_typeid'].'"><i class="fa fa-edit"></i></a>';

    }
    if(has_permission(BIZIT_SERVICES_MSL,'','delete') and $aRow['code'] != '001' and $aRow['code'] != '002' and $aRow['code'] != '003' and $aRow['code'] != '004' and $aRow['code'] != '005' and $aRow['code'] != '006'){
       $options .= icon_btn('services/delete_category/' . $aRow['code'], 'fa fa-remove', 'btn-danger _delete');
   }
   $row[] = $options;

   $output['aaData'][] = $row;
}
