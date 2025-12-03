<?php defined('BASEPATH') or exit('No direct script access allowed');

class Reports_model extends App_Model {

    // Fetch by Code (Used by Controllers for Views/PDFs)
    public function get_field_report($code) {
        return $this->db->where('report_code', $code)->get('tblfield_report')->row();
    }

    // Fetch by ID (Used by Edit screens)
    public function get_field_report_by_id($id) {
        return $this->db->where('field_report_id', $id)->get('tblfield_report')->row();
    }

    // --- CRITICAL RESTORATION: Required by check_report() helper ---
    public function get_field_report_check($rental_agreement_id) {
        return $this->db->where('service_rental_agreement_id', $rental_agreement_id)->get('tblfield_report')->row();
    }

    public function add_field_report($data) {
        // Extract rental code to lookup ID
        $rental_code = $data['rental_agreement_code'];
        unset($data['rental_agreement_code']);
        
        $rental = $this->db->where('service_rental_agreement_code', $rental_code)->get('tblservice_rental_agreement')->row();
        
        if($rental) {
            $data['service_rental_agreement_id'] = $rental->service_rental_agreement_id;
            // Default report code format: RENTALCODE-1
            $data['report_code'] = $rental_code . '-1'; 
            $data['clientid'] = $rental->clientid;
            $data['added_by'] = get_staff_user_id();
            
            $this->db->insert('tblfield_report', $data);
            $insert_id = $this->db->insert_id();
            
            if ($insert_id) {
                log_activity('New Field Report Added [ID:' . $insert_id . ']');
                return $insert_id;
            }
        }
        return false;
    }

    public function edit_field_report($data) {
        $id = $data['field_report_id'];
        unset($data['field_report_id']);
        
        $this->db->where('field_report_id', $id)->update('tblfield_report', $data);
        if ($this->db->affected_rows() > 0) {
            log_activity('Field Report Updated [ID: ' . $id . ']');
            return true;
        }
        return false;
    }

    public function delete_field_report($id) {
        $this->db->where('field_report_id', $id)->delete('tblfield_report');
        if ($this->db->affected_rows() > 0) {
            log_activity('Field Report Deleted [ID: ' . $id . ']');
            return true;
        }
        return false;
    }
}
