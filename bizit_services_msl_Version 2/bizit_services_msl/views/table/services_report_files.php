<?php
defined('BASEPATH') OR exit('No direct script access allowed');

$aColumns     = array('file_name', 'filetype','staffid','dateadded');
$sIndexColumn = 'id';
$sTable       = 'tblfiles';

$join             = array();
$additionalSelect = array('id','rel_id','rel_type', 'external', 'external_link', 'thumbnail_link');
$where =  array(" AND rel_type = '".$type."' AND rel_id = ". $type_id );
$result           = data_tables_init($aColumns, $sIndexColumn, $sTable, $join, $where, $additionalSelect);
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

        if($aColumns[$i] == 'file_name'){
            $file_name = '<a href="#">';
            if(is_image(get_upload_path_by_type($type) .$aRow['rel_id'].'/'.$aRow['file_name']) || (!empty($aRow['external']) && !empty($aRow['thumbnail_link']))){
                        $url = base_url('uploads/services/rental_agreement/'.$type.'/'.$aRow['rel_id'].'/'.$aRow['file_name']);
                        if(!empty($aRow['external']) && !empty($aRow['thumbnail_link'])){
                        $url = $aRow['thumbnail_link'];
                        }
                        $file_name .= '<img class="project-file-image" src="'.$url.'" width="100">';
                        }
                    $file_name .= $aRow['file_name'];
                    $file_name .= '</a>';

                    $_data = $file_name;
        }    
        if($aColumns[$i] == 'staffid'){
            $user = '<a href="' . admin_url('staff/profile/' . $aRow['staffid']). '">' .staff_profile_image($aRow['staffid'], array(
                        'staff-profile-image-small'
                    )) . '</a>';
            $user .= ' <a href="' . admin_url('staff/member/' . $aRow['staffid'])  . '">' . get_staff_full_name($aRow['staffid']) . '</a>';
            $_data = $user;        
        } 
        if($aColumns[$i] == 'dateadded'){  
            $_data = _d($aRow['dateadded']);
        }

        $row[] = $_data;
    }
    $options = '';

    if(has_permission(BIZIT_SERVICES_MSL.'_rental_agreement_field_report','','edit')){
       $options .= '<button type="button" class="btn btn-danger _delete" onclick="delete_services_report_file('.$aRow['id'].')"; return false;"><i class="fa fa-remove"></i></button>';
   }
   $row[] = $options;

   $output['aaData'][] = $row;
}
