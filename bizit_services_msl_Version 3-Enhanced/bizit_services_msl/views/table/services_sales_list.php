<?php defined('BASEPATH') or exit('No direct script access allowed');

$aColumns = [
    'tblservices_module.service_code',
    'tblservices_module.name', 
    'tblservice_type.name',
    'tblservices_module.price'
];
$sIndexColumn = "serviceid";
$sTable = "tblservices_module";

$join = ['LEFT JOIN tblservice_type ON (tblservice_type.type_code = tblservices_module.service_type_code)'];

$result = data_tables_init($aColumns, $sIndexColumn, $sTable, $join, ['AND tblservice_type.status = 1'], ['serviceid']);
$output = $result['output'];
$rResult = $result['rResult'];

foreach ($rResult as $aRow) {
    $row = [];
    $row[] = $aRow['tblservices_module.service_code'];
    $row[] = $aRow['tblservices_module.name'];
    $row[] = $aRow['tblservice_type.name'];
    $row[] = app_format_money($aRow['tblservices_module.price'], get_currency_symbol());
    $output['aaData'][] = $row;
}