<?php

defined('BASEPATH') or exit('No direct script access allowed');

$aColumns = [
    'report_code',
    'site_name',
    'status',
    'approved_by',
    'dateadded'
];

$sIndexColumn = 'field_report_id';
$sTable       = 'tblfield_report';

// --- FIX: CONDITIONAL FILTER ---
$where = [];
// Only filter by Rental ID if it was passed via POST (from the Rental Tab)
$rental_id = $this->ci->input->post('service_rental_agreement_id');
if (is_numeric($rental_id) && $rental_id > 0) {
    array_push($where, 'AND service_rental_agreement_id = ' . $rental_id);
}

$join = [];
$additionalSelect = [
    'field_report_id',
    'added_by',
    'service_rental_agreement_id'
];

$result = data_tables_init($aColumns, $sIndexColumn, $sTable, $join, $where, $additionalSelect);

$output  = $result['output'];
$rResult = $result['rResult'];

foreach ($rResult as $aRow) {
    $row = [];

    // 1. Code Link
    $row[] = '<a href="' . admin_url('services/field_report/view/' . $aRow['report_code']) . '">' . $aRow['report_code'] . '</a>';

    // 2. Site
    $row[] = $aRow['site_name'];

    // 3. Status Label
    $status = $aRow['status'];
    $label = 'default'; $text = 'Draft';
    if ($status == 2) { $label = 'info'; $text = 'Submitted'; }
    elseif ($status == 3) { $label = 'danger'; $text = 'Rejected'; }
    elseif ($status == 4) { $label = 'success'; $text = 'Approved'; }
    $row[] = '<span class="label label-' . $label . '">' . _l($text) . '</span>';

    // 4. Approver
    $row[] = $aRow['approved_by'] ? get_staff_full_name($aRow['approved_by']) : '-';

    // 5. Date
    $row[] = _d($aRow['dateadded']);

    // 6. Options
    $options = icon_btn('services/field_report/view/' . $aRow['report_code'], 'eye', 'btn-default');
    
    // Edit: Only if Draft/Rejected OR Admin
    if ((is_admin() || get_staff_user_id() == $aRow['added_by']) && $aRow['status'] != 4) {
        $options .= icon_btn('services/field_report/edit/' . $aRow['report_code'], 'pencil-square-o');
    }
    
    // PDF: Only if Approved
    if ($aRow['status'] == 4) {
        $options .= icon_btn('services/field_report/pdf/' . $aRow['report_code'], 'file-pdf-o', 'btn-success', ['target'=>'_blank']);
    }

    $row[] = $options;
    $output['aaData'][] = $row;
}