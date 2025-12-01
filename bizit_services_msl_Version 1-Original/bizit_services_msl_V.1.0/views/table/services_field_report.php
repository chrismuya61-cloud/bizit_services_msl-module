<?php
defined('BASEPATH') OR exit('No direct script access allowed');

// Ensure $vars is an array before calling extract
if (isset($vars) && is_array($vars)) {
    extract($vars); // Now you have access to $service_rental_agreement_id and $service_rental_agreement_code
}

// If $service_rental_agreement_id is still not set, handle it gracefully
if (!isset($service_rental_agreement_id)) {
    $service_rental_agreement_id = 0; // Default or handle the error
}

$aColumns     = array(
    'report_code',
    'site_name',
    'status',
    'approved_by',
    'dateadded'
    );
$sIndexColumn = "field_report_id";
$sTable       = 'tblfield_report';

$join             = array();
$additionalSelect = array(
    'field_report_id',
    'added_by'
);
$result = data_tables_init($aColumns, $sIndexColumn, $sTable, $join, array(' AND service_rental_agreement_id =' . $service_rental_agreement_id), $additionalSelect);

// $result           = data_tables_init($aColumns, $sIndexColumn, $sTable, $join, array(' AND service_rental_agreement_id ='.$service_rental_agreement_id), $additionalSelect);
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
       if($aColumns[$i] == 'status'){
            $_data = $aRow['status'] == 0 ? '<span class="label label-warning">Incomplete Report</span>' : ($aRow['status'] == 1 ? '<span class="label label-danger">Report Cancelled</span>' : ($aRow['status'] == 2 ? '<span class="label label-primary">Awaiting Approval</span>' : ($aRow['status'] == 3 ? '<span class="label label-danger">Report Rejected</span>' : '<span class="label label-success">Report Completed & Approved</span>')));
        } 
        else if($aColumns[$i] == 'approved_by'){
            $_data = get_staff_full_name($aRow['approved_by']);
        }
        elseif ($aColumns[$i] == 'dateadded') {
            $_data = _d($aRow['dateadded']);
        }     

        $row[] = $_data;
    }
    $options = '';

    $options .= icon_btn(admin_url('services/field_report/view/' . $aRow['report_code']), 'fa fa-eye', 'btn-success', []);

    if ((is_admin() || get_staff_user_id() == $aRow['added_by'] || has_permission(BIZIT_SERVICES_MSL.'_rental_agreement_field_report', '', 'edit')) && $aRow['status'] <= 3 && $aRow['status'] != 1) {
        $options .= icon_btn(admin_url('services/field_report/edit/' . $aRow['report_code']), 'fa fa-pencil', 'btn-default', []);
    }
        if($aRow['status'] == 4 and has_permission(BIZIT_SERVICES_MSL.'_rental_agreement_field_report', '', 'view')){
        $options .= icon_btn(admin_url('services/field_report/pdf/' . $aRow['report_code']), 'fas fa-file-pdf', 'btn-primary', []);
    }
    if(is_admin() or has_permission(BIZIT_SERVICES_MSL.'_rental_agreement_field_report', '', 'delete')){
       $options .= icon_btn('services/delete_field_report/' . $aRow['field_report_id'].'/'. $service_rental_agreement_code, 'fa fa-trash', 'btn-danger _delete', []);
    }
   $row[] = $options;

   $output['aaData'][] = $row;
}
