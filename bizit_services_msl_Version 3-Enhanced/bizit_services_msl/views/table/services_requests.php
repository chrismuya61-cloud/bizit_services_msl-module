<?php defined('BASEPATH') or exit('No direct script access allowed');

$aColumns = [
    'service_request_code',
    'clientid',
    'item_type', 
    'drop_off_date',   
    'collection_date',
    'received_by', 
    'status'
];

$sIndexColumn = 'service_request_id';
$sTable = 'tblservice_request';

// OPTIMIZATION: Join tables to fetch names/status in 1 query instead of loops
$join = [
    'LEFT JOIN ' . db_prefix() . 'clients ON ' . db_prefix() . 'clients.userid = ' . db_prefix() . 'service_request.clientid',
    'LEFT JOIN ' . db_prefix() . 'invoices ON ' . db_prefix() . 'invoices.id = ' . db_prefix() . 'service_request.invoice_rel_id',
    'LEFT JOIN ' . db_prefix() . 'staff ON ' . db_prefix() . 'staff.staffid = ' . db_prefix() . 'service_request.received_by',
];

// V3 FILTERS
$where = [];
if ($this->ci->input->post('from_date')) {
    array_push($where, 'AND dateadded >= "' . to_sql_date($this->ci->input->post('from_date')) . ' 00:00:00"');
}
if ($this->ci->input->post('to_date')) {
    array_push($where, 'AND dateadded <= "' . to_sql_date($this->ci->input->post('to_date')) . ' 23:59:59"');
}
if ($this->ci->input->post('status_filter') !== '') {
    array_push($where, 'AND ' . db_prefix() . 'service_request.status = ' . $this->ci->input->post('status_filter'));
}

$additionalSelect = [
    'service_request_id', 
    'invoice_rel_id', 
    db_prefix() . 'clients.company as company_name',
    db_prefix() . 'invoices.status as invoice_status',
    'firstname', 
    'lastname'
];

$result = data_tables_init($aColumns, $sIndexColumn, $sTable, $join, $where, $additionalSelect);
$output  = $result['output'];
$rResult = $result['rResult'];

foreach ($rResult as $aRow) {
    $row = [];

    // 1. Code
    $row[] = '<a href="' . admin_url('services/view_request/' . $aRow['service_request_code']) . '">' . get_option('service_request_prefix') . $aRow['service_request_code'] . '</a>';

    // 2. Client (Fetched via Join)
    $row[] = '<a href="' . admin_url('clients/client/' . $aRow['clientid']) . '">' . $aRow['company_name'] . '</a>';

    // 3. Item
    $row[] = $aRow['item_type'];

    // 4. Dates
    $row[] = _d($aRow['drop_off_date']);
    $row[] = _d($aRow['collection_date']);

    // 5. Received By (Fetched via Join)
    $staffFullName = $aRow['firstname'] . ' ' . $aRow['lastname'];
    $row[] = '<a href="' . admin_url('staff/member/' . $aRow['received_by']) . '">' . $staffFullName . '</a>';

    // 6. Status
    $status = 'Open';
    $label = 'info';
    if ($aRow['status'] == 1) { $status = 'Closed'; $label = 'success'; }
    // Original Logic: Add "Invoiced" text if linked
    if ($aRow['invoice_rel_id']) {
        $status .= '<br><span class="text-muted font-size-xs">(Invoiced)</span>';
    }
    $row[] = '<span class="label label-' . $label . '">' . $status . '</span>';

    // 7. Options
    $options = icon_btn('services/view_request/' . $aRow['service_request_code'], 'eye', 'btn-success');

    // Logic from Original: Edit allowed if No Invoice OR Invoice is NOT "Sent/Paid" (Status 2)
    // Using the joined 'invoice_status' avoids the N+1 query problem.
    $inv_status = $aRow['invoice_status'];
    if (empty($aRow['invoice_rel_id']) || ($inv_status != 2 && $inv_status != 5)) { // 2=Sent/Paid typically, adjusting for standard Perfex status IDs
         $options .= icon_btn('services/new_request/1/' . $aRow['service_request_code'], 'pencil-square-o', 'btn-default');
    }

    $options .= icon_btn('services/request_pdf/' . $aRow['service_request_code'], 'file-pdf-o', 'btn-info');

    // Check Report (Logic retained)
    if (function_exists('check_report')) {
        $has_report = check_report($aRow['service_request_id']);
        if ($has_report) {
            $options .= icon_btn('services/report/view/' . $aRow['service_request_code'], 'file-text', 'btn-success');
        } else {
            $options .= icon_btn('services/report/1/' . $aRow['service_request_code'], 'plus', 'btn-warning', ['title' => 'Add Report']);
        }
    }

    $row[] = $options;
    $output['aaData'][] = $row;
}