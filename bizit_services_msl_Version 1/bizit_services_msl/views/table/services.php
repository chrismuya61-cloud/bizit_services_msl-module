<?php
/*`serviceid`, `service_code`, `service_type_code`, `name`, `price`, `penalty_rental_price`, `rental_status`, `rental_duration_check`, `description`, `status`*/
defined('BASEPATH') OR exit('No direct script access allowed');

$_not_to_delete = SERVICES_NOT_TO_DELETE;

$aColumns     = array(
              'tblservices_module.service_code',
              'tblservices_module.name', 
              'tblservice_type.name',   
              'tblservices_module.status'
    );
$sIndexColumn = "serviceid";
$sTable       = "tblservices_module";

$join             = array('LEFT JOIN tblservice_type ON (tblservice_type.type_code = tblservices_module.service_type_code)');
$additionalSelect = array(
    'tblservices_module.service_type_code as type_code',
    'tblservices_module.rental_serial as serial',
    'tblservices_module.description as description',
    'tblservices_module.price as price',
    'tblservices_module.rental_status as rental_status',
    'penalty_rental_price',
    'rental_duration_check',
    'rental_status',
    'quantity_unit'
    );

$result           = data_tables_init($aColumns, $sIndexColumn, $sTable, $join, array(' AND tblservice_type.status = 1'), $additionalSelect);
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

        if($aColumns[$i] == 'tblservices_module.name' AND $aRow['type_code'] == '001'){
            $_data = $aRow['tblservices_module.name'].' <span class="badge hidden" style="font-weight:normal; font-size:9px;">'.$aRow['rental_status'].'</span><br><span class="text-default small">SN: '.(!empty($aRow['serial']) ? $aRow['serial'] : '<i class="fa fa-ellipsis-h"></i>').'</span>';
        }   

        // Toggle active/inactive Service
        if($aColumns[$i] == 'tblservices_module.status' && !in_array($aRow['tblservices_module.service_code'], $_not_to_delete)){ 
        $toggleActive = '<div class="onoffswitch" data-toggle="tooltip" data-title="' . _l('customer_active_inactive_help') . '">
        <input type="checkbox" data-switch-url="' . admin_url().'services/change_service_status" name="onoffswitch" class="onoffswitch-checkbox" id="' . $aRow['tblservices_module.service_code'] . '" data-id="' . $aRow['tblservices_module.service_code'] . '" ' . ($aRow['tblservices_module.status'] == 1 ? 'checked' : '') . '>
        <label class="onoffswitch-label" for="' . $aRow['tblservices_module.service_code'] . '"></label>
        </div>';

        $_data = $toggleActive;
        
        }  
        else if($aColumns[$i] == 'tblservices_module.status' && in_array($aRow['tblservices_module.service_code'], $_not_to_delete)){
            $_data = '';
        }

        $row[] = $_data;
    }
    
    

    $options = '';
    if(has_permission(BIZIT_SERVICES_MSL,'','edit')){
        $options .= '<a class="btn btn-default btn-icon edit-service-btn" data-toggle="modal" data-target="#services_modal" data-id="' . $aRow['tblservices_module.service_code'] . '" data-category="' . $aRow['type_code'] . '" data-description="' . $aRow['description'] . '" data-price="' . $aRow['price'] . '" data-name="' . $aRow['tblservices_module.name'] . '" data-serial="' . $aRow['serial'] . '" data-penalty_rental_price="'. $aRow['penalty_rental_price'] .'" data-rental_duration_check="'. $aRow['rental_duration_check'] .'" data-rental_status="'. $aRow['rental_status'] .'" data-quantity_unit="'.$aRow['quantity_unit'].'" ><i class="fa fa-edit"></i></a>';
    }
    if(has_permission(BIZIT_SERVICES_MSL,'','delete') && !in_array($aRow['tblservices_module.service_code'], $_not_to_delete)){
       $options .= icon_btn('services/delete/' . $aRow['tblservices_module.service_code'], 'fa fa-remove', 'btn-danger _delete');
   }
   $row[] = $options;

   $output['aaData'][] = $row;
}
