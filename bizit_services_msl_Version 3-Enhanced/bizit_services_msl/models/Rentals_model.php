<?php defined('BASEPATH') or exit('No direct script access allowed');

class Rentals_model extends App_Model {

    // Get Single Agreement by Code
    public function get_rental_agreement($code) { 
        return $this->db->where('service_rental_agreement_code', $code)->get('tblservice_rental_agreement')->row(); 
    }
    
    // Get Details (Line Items) for an Agreement
    public function get_rental_agreement_details($id) { 
        return $this->db->where('service_rental_agreement_id', $id)->get('tblservice_rental_agreement_details')->result(); 
    }

    // Create New Agreement Header
    public function add_rental_agreement($data) { 
        $this->db->insert('tblservice_rental_agreement', $data); 
        return $this->db->insert_id(); 
    }
    
    // Update Agreement Header
    public function edit_rental_agreement($data) { 
        $id = $data['service_rental_agreement_id']; 
        unset($data['service_rental_agreement_id']); 
        $this->db->where('service_rental_agreement_id', $id)->update('tblservice_rental_agreement', $data); 
    }

    // Add Line Item (Updates Inventory Status to 'Hired')
    public function add_rental_agreement_details($data) { 
        $this->db->where('serviceid', $data['serviceid'])->update('tblservices_module', ['rental_status'=>'Hired']); 
        $this->db->insert('tblservice_rental_agreement_details', $data); 
    }
    
    // Edit Line Item
    public function edit_rental_agreement_details($data, $id) { 
        $this->db->where('service_rental_agreement_details_id', $id)->update('tblservice_rental_agreement_details', $data); 
    }
    
    // Delete Line Item (Restores Inventory Status to 'Not-Hired')
    public function delete_rental_agreement_details($id) {
        $row = $this->db->where('service_rental_agreement_details_id', $id)->get('tblservice_rental_agreement_details')->row();
        if($row) {
            $this->db->where('serviceid', $row->serviceid)->update('tblservices_module', ['rental_status'=>'Not-Hired']);
        }
        $this->db->where('service_rental_agreement_details_id', $id)->delete('tblservice_rental_agreement_details');
    }

    // Get Attached Files
    public function get_rental_agreement_files($id) { 
        $r = $this->db->where('service_rental_agreement_id', $id)->get('tblservice_rental_agreement')->row(); 
        return ($r && !empty($r->report_files)) ? json_decode($r->report_files, true) : []; 
    }
    
    // --- CALENDAR DATA SOURCE (Enhanced for V3) ---
    public function get_calendar_rental_details() {
        return $this->db->select('
                d.*, 
                a.start_date, 
                a.end_date, 
                a.actual_date_returned,
                a.service_rental_agreement_code,
                a.status,
                m.name, 
                m.rental_serial,
                c.company as client_name
            ')
            ->from('tblservice_rental_agreement_details d')
            ->join('tblservice_rental_agreement a', 'a.service_rental_agreement_id = d.service_rental_agreement_id')
            ->join('tblservices_module m', 'm.serviceid = d.serviceid')
            ->join('tblclients c', 'c.userid = a.clientid', 'left')
            ->where('a.status !=', 1) // Exclude Drafts
            ->get()->result();
    }
}
