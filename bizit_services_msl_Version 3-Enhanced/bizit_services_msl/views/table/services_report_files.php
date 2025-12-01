<?php

defined('BASEPATH') or exit('No direct script access allowed');

$aColumns = [
    'file_name',
    'filetype',
    'staffid',
    'dateadded'
];

$sIndexColumn = 'id';
$sTable       = 'tblfiles';

// --- FIX: RETRIEVE VARIABLES SAFELY ---
$type    = $this->ci->input->post('type');
$type_id = $this->ci->input->post('type_id');

if (!$type || !$type_id) {
    $type = 'dummy'; // Prevent SQL error if empty
    $type_id = 0;
}

$where = [
    'AND rel_type = "' . $this->ci->db->escape_str($type) . '"',
    'AND rel_id = ' . $this->ci->db->escape_str($type_id)
];

$join = [];
$additionalSelect = [
    'id', 'rel_id', 'rel_type', 'external', 'external_link', 'thumbnail_link'
];

$result = data_tables_init($aColumns, $sIndexColumn, $sTable, $join, $where, $additionalSelect);

$output  = $result['output'];
$rResult = $result['rResult'];

foreach ($rResult as $aRow) {
    $row = [];

    // 1. File Name / Link
    $file_path = 'modules/bizit_services_msl/uploads/reports/' . $aRow['file_name'];
    $full_url  = base_url($file_path);
    
    $link_html = '<a href="' . $full_url . '" target="_blank">';
    if (is_image(FCPATH . $file_path)) {
        $link_html .= '<img src="' . $full_url . '" class="img-thumbnail img-responsive" style="max-width: 50px; margin-right: 10px;">';
    }
    $link_html .= $aRow['file_name'];
    $link_html .= '</a>';
    
    $row[] = $link_html;

    // 2. Type
    $row[] = $aRow['filetype'];

    // 3. Staff
    $row[] = staff_profile_image($aRow['staffid'], ['staff-profile-image-small', 'mright5']) . ' ' . get_staff_full_name($aRow['staffid']);

    // 4. Date
    $row[] = _dt($aRow['dateadded']);

    // 5. Delete Option
    $options = '';
    if (is_admin() || $aRow['staffid'] == get_staff_user_id()) {
        // Calls the delete_file JS function defined in module JS
        $options .= '<a href="#" class="btn btn-danger btn-icon" onclick="delete_file(' . $aRow['id'] . ', \'' . $aRow['rel_type'] . '\'); return false;"><i class="fa fa-remove"></i></a>';
    }
    $row[] = $options;

    $output['aaData'][] = $row;
}