<?php
defined('BASEPATH') OR exit('No direct script access allowed');

$aColumns = array(
  'tblwarranty_services.sku_code',
  'tblwarranty_services.name',
  'tblwarranty_services.service_code',
  'tblwarranty_services.date_sold',
  'tblwarranty_services.warranty_days_remaining',
  'tblwarranty_services.warranty_end_date',
);
$sIndexColumn = "warranty_id";
$sTable = "tblwarranty_services";

$join = array();
$additionalSelect = array('warranty_id');
$where = array();

$result = data_tables_init($aColumns, $sIndexColumn, $sTable, $join, $where, $additionalSelect);
$output = $result['output'];
$rResult = $result['rResult'];

foreach ($rResult as $aRow) {

  $row = array();
  for ($i = 0; $i < count($aColumns); $i++) {
    $_data = $aRow[$aColumns[$i]];

    if ($aColumns[$i] == 'tblwarranty_services.date_sold') {
      $_data = _d($aRow['tblwarranty_services.date_sold']);
    }

    if ($aColumns[$i] == 'tblwarranty_services.warranty_end_date') {
      $_data = _d($aRow['tblwarranty_services.warranty_end_date']);
    }

    $row[] = $_data;
  }

  $options = '';
  $options .= icon_btn('services/warranty_pdf/' . $aRow['tblwarranty_services.service_code'], 'fa fa-download', 'btn-info', ['title' => 'View PDF']);
  $options .= icon_btn('services/delete_warranty/' . $aRow['tblwarranty_services.service_code'], 'fa fa-remove', 'btn-danger _delete', ['title' => 'Delete Warranty']);
  
  $row[] = $options;

  $output['aaData'][] = $row;
}

echo json_encode($output);
