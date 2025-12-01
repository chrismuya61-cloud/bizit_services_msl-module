<?php defined('BASEPATH') or exit('No direct script access allowed');

class Reports_model extends App_Model {

    public function get_field_report($code) { return $this->db->where('report_code', $code)->get('tblfield_report')->row(); }
    public function get_field_report_by_id($id) { return $this->db->where('field_report_id', $id)->get('tblfield_report')->row(); }
    public function get_field_report_check($rental_id) { return $this->db->where('service_rental_agreement_id', $rental_id)->get('tblfield_report')->row(); }

    public function add_field_report($data) { 
        if(isset($data['rental_agreement_code'])) {
             $CI=&get_instance(); $CI->load->model('bizit_services_msl/rentals_model');
             $info = $CI->rentals_model->get_rental_agreement($data['rental_agreement_code']);
             $data['service_rental_agreement_id'] = $info->service_rental_agreement_id;
             $data['report_code'] = $data['rental_agreement_code'].'-1';
             $data['clientid'] = $info->clientid;
             $data['added_by'] = get_staff_user_id();
             unset($data['rental_agreement_code']);
        }
        $this->db->insert('tblfield_report', $data); return $this->db->insert_id(); 
    }
    public function edit_field_report($data) { 
        $id=$data['field_report_id']; unset($data['field_report_id'], $data['added_by'], $data['DataTables_Table_0_length']); 
        $this->db->where('field_report_id', $id)->update('tblfield_report', $data); 
    }
    public function delete_field_report($id) { 
        $this->db->where('field_report_id', $id)->delete('tblfield_report'); return true; 
    }

    public function delete_file($id, $type, $type_id, $fname) { 
        $this->db->where('id', $id)->delete('tblfiles'); 
        $path = FCPATH . 'modules/bizit_services_msl/uploads/reports/' . $fname;
        if(file_exists($path)) unlink($path);
        return true;
    }
}