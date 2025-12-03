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

$join = [
    'LEFT JOIN ' . db_prefix() . 'clients ON ' . db_prefix() . 'clients.userid = ' . db_prefix() . 'service_request.clientid',
    'LEFT JOIN ' . db_prefix() . 'invoices ON ' . db_prefix() . 'invoices.id = ' . db_prefix() . 'service_request.invoice_rel_id',
    'LEFT JOIN ' . db_prefix() . 'staff ON ' . db_prefix() . 'staff.staffid = ' . db_prefix() . 'service_request.received_by',
];

$where = [];

// --- PERMISSION FILTER (VIEW OWN) ---
$hasPermissionView   = has_permission(BIZIT_SERVICES_MSL, '', 'view');
$hasPermissionViewOwn = has_permission(BIZIT_SERVICES_MSL, '', 'view_own');

if (!$hasPermissionView && $hasPermissionViewOwn) {
    // Only show requests received/created by the current staff member
    array_push($where, 'AND ' . db_prefix() . 'service_request.received_by = ' . get_staff_user_id());
}
// -------------------------------------

// Standard Filters
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

    // 1. Code (Modernized Link Style)
    $link = admin_url('services/view_request/' . $aRow['service_request_code']);
    $row[] = '<a href="' . $link . '" class="text-dark font-medium">' . get_option('service_request_prefix') . $aRow['service_request_code'] . '</a>';

    // 2. Client
    $row[] = '<a href="' . admin_url('clients/client/' . $aRow['clientid']) . '">' . $aRow['company_name'] . '</a>';

    // 3. Item
    $row[] = '<span class="text-muted">' . $aRow['item_type'] . '</span>';

    // 4. Dates
    $row[] = _d($aRow['drop_off_date']);
    $row[] = _d($aRow['collection_date']);

    // 5. Received By (Avatar + Name for Modern Look)
    $staffFullName = $aRow['firstname'] . ' ' . $aRow['lastname'];
    $staffProfile = staff_profile_image($aRow['received_by'], ['staff-profile-image-small', 'mright5']);
    $row[] = $staffProfile . '<a href="' . admin_url('staff/member/' . $aRow['received_by']) . '">' . $staffFullName . '</a>';

    // 6. Status (Modern Badges)
    $statusClass = ($aRow['status'] == 1) ? 'label-success' : 'label-info';
    $statusText = ($aRow['status'] == 1) ? 'Closed' : 'Open';
    $invoiceBadge = $aRow['invoice_rel_id'] ? '<span class="label label-default mleft5">Invoiced</span>' : '';
    
    $row[] = '<span class="label ' . $statusClass . '">' . $statusText . '</span>' . $invoiceBadge;

    // 7. Options (Permission Check applied to buttons)
    $options = '';
    
    // View Button (Always allowed if rows are visible)
    $options .= icon_btn('services/view_request/' . $aRow['service_request_code'], 'eye', 'btn-default', ['title' => 'View Details']);

    // Edit Button
    $inv_status = $aRow['invoice_status'];
    $canEdit = has_permission(BIZIT_SERVICES_MSL, '', 'edit');
    $isEditableStatus = (empty($aRow['invoice_rel_id']) || ($inv_status != 2 && $inv_status != 5));
    
    if ($canEdit && $isEditableStatus) {
         $options .= icon_btn('services/new_request/1/' . $aRow['service_request_code'], 'pencil-square-o', 'btn-default', ['title' => 'Edit Request']);
    }

    // PDF Button
    $options .= icon_btn('services/request_pdf/' . $aRow['service_request_code'], 'file-pdf-o', 'btn-success', ['title' => 'Download PDF']);

    // Report Buttons
    if (function_exists('check_report')) {
        $has_report = check_report($aRow['service_request_id']);
        if ($has_report) {
            $options .= icon_btn('services/report/view/' . $aRow['service_request_code'], 'file-text', 'btn-info', ['title' => 'View Calibration Report']);
        } else {
             if ($canEdit) { // Only allow adding report if user has edit rights
                $options .= icon_btn('services/report/1/' . $aRow['service_request_code'], 'plus', 'btn-warning', ['title' => 'Create Calibration Report']);
             }
        }
    }

    $row[] = $options;
    $output['aaData'][] = $row;
}
