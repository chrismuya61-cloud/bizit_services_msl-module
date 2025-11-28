<?php
/*`serviceid`, `service_code`, `service_type_code`, `name`, `price`, `penalty_rental_price`, `rental_status`, `rental_duration_check`, `description`, `status`*/
defined('BASEPATH') OR exit('No direct script access allowed');

$aColumns     = array(
              'tblservices_module.service_code',
              'tblservices_module.name', 
              'tblservice_type.name',
              'tblservices_module.price as price',
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
    'rental_duration_check'
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

        if($aColumns[$i] == 'tblservices_module.price as price'){
            $_data = app_format_money($aRow['price'], get_default_currency( 'symbol' ));
        }

        $row[] = $_data;
    }
    

$options = '';
   $row[] = $options;

   $output['aaData'][] = $row;
}
