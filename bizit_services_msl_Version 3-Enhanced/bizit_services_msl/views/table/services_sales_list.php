<?php defined('BASEPATH') or exit('No direct script access allowed');

$aColumns = [
    'tblservices_module.name', 
    'tblservices_module.service_code',
    'tblservice_type.name',
    'tblservices_module.price',
    'tblservices_module.datecreated'
];

$sIndexColumn = "serviceid";
$sTable = "tblservices_module";

$join = ['LEFT JOIN tblservice_type ON (tblservice_type.type_code = tblservices_module.service_type_code)'];

// Filter: Only show items where the category status is Active (1)
$where = ['AND tblservice_type.status = 1'];

$result = data_tables_init($aColumns, $sIndexColumn, $sTable, $join, $where, ['serviceid']);
$output = $result['output'];
$rResult = $result['rResult'];

foreach ($rResult as $aRow) {
    $row = [];
    
    // Name
    $row[] = $aRow['tblservices_module.name'];
    
    // Code
    $row[] = $aRow['tblservices_module.service_code'];
    
    // Category
    $row[] = $aRow['tblservice_type.name'];
    
    // Price
    $row[] = app_format_money($aRow['tblservices_module.price'], get_currency_symbol());
    
    // Date
    $row[] = _d($aRow['tblservices_module.datecreated']);
    
    $output['aaData'][] = $row;
}