<?php defined('BASEPATH') or exit('No direct script access allowed');

$aColumns = [
    'service_rental_agreement_code',
    'clientid',
    'start_date',
    'end_date',
    'received_by', // Original column requested
    'status'
];

$sIndexColumn = 'service_rental_agreement_id';
$sTable = 'tblservice_rental_agreement';
$join = [
    'LEFT JOIN ' . db_prefix() . 'clients ON ' . db_prefix() . 'clients.userid = ' . db_prefix() . 'service_rental_agreement.clientid',
    'LEFT JOIN ' . db_prefix() . 'staff ON ' . db_prefix() . 'staff.staffid = ' . db_prefix() . 'service_rental_agreement.received_by'
];

// V3 FILTERS
$where = [];
if ($this->ci->input->post('from_date')) {
    array_push($where, 'AND start_date >= "' . to_sql_date($this->ci->input->post('from_date')) . '"');
}
if ($this->ci->input->post('to_date')) {
    array_push($where, 'AND start_date <= "' . to_sql_date($this->ci->input->post('to_date')) . '"');
}

$additionalSelect = ['service_rental_agreement_id', 'company', 'invoice_rel_id', 'firstname', 'lastname'];

$result = data_tables_init($aColumns, $sIndexColumn, $sTable, $join, $where, $additionalSelect);
$output  = $result['output'];
$rResult = $result['rResult'];

foreach ($rResult as $aRow) {
    $row = [];
    
    // Code
    $row[] = '<a href="' . admin_url('services/view_rental_agreement/' . $aRow['service_rental_agreement_code']) . '">' . get_option('service_rental_agreement_prefix') . $aRow['service_rental_agreement_code'] . '</a>';
    
    // Client
    $row[] = '<a href="' . admin_url('clients/client/' . $aRow['clientid']) . '">' . $aRow['company'] . '</a>';
    
    // Dates
    $row[] = _d($aRow['start_date']);
    $row[] = _d($aRow['end_date']);

    // Received By
    $staffName = $aRow['firstname'] . ' ' . $aRow['lastname'];
    $row[] = '<a href="' . admin_url('staff/member/' . $aRow['received_by']) . '">' . $staffName . '</a>';

    // Status (Original Logic Restored)
    $status = '';
    if ($aRow['status'] == 0) {
        $status = '<span class="label label-warning">Pending Rental</span>';
    } elseif ($aRow['status'] == 1) {
        $status = '<span class="label label-danger">Canceled Rental</span>';
    } elseif ($aRow['status'] == 3) {
        $status = '<span class="label label-primary">Pending-partially-paid</span>';
    } else {
        $status = '<span class="label label-success">Paid Rental</span>';
    }
    $row[] = $status;

    // Options
    $options = icon_btn('services/view_rental_agreement/' . $aRow['service_rental_agreement_code'], 'eye', 'btn-success');
    
    // Only allow edit if not invoiced (Original Rule)
    if (empty($aRow['invoice_rel_id']) && has_permission(BIZIT_SERVICES_MSL.'_rental_agreement', '', 'edit')) {
        $options .= icon_btn('services/new_rental_agreement/1/' . $aRow['service_rental_agreement_code'], 'pencil-square-o', 'btn-default');
    }
    
    $options .= icon_btn('services/rental_agreement_pdf/' . $aRow['service_rental_agreement_code'], 'file-pdf-o', 'btn-info');

    $row[] = $options;
    $output['aaData'][] = $row;
}