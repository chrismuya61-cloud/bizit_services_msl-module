<?php defined('BASEPATH') or exit('No direct script access allowed');

class Rentals_model extends App_Model {

    // Get Single Agreement by Code
    public function get_rental_agreement($code) {
        return $this->db->where('service_rental_agreement_code', $code)->get('tblservice_rental_agreement')->row();
    }

    // Get Details (Line Items) for an Agreement
    // Added JOINs to ensure View gets Service Name and Category Name
    public function get_rental_agreement_details($id) {
        $this->db->select('tblservice_rental_agreement_details.*, tblservices_module.name, tblservices_module.service_code, tblservices_module.rental_serial, tblservice_type.name as category_name');
        $this->db->from('tblservice_rental_agreement_details');
        $this->db->join('tblservices_module', 'tblservices_module.serviceid = tblservice_rental_agreement_details.serviceid', 'left');
        $this->db->join('tblservice_type', 'tblservices_module.service_type_code = tblservice_type.type_code', 'left');
        $this->db->where('service_rental_agreement_id', $id);
        return $this->db->get()->result();
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
        return true;
    }

    // Add Line Item (Updates Inventory Status to 'Hired')
    public function add_rental_agreement_details($data) {
        // V3 RESTORED LOGIC: Mark inventory item as Hired
        $this->db->where('serviceid', $data['serviceid'])->update('tblservices_module', ['rental_status' => 'Hired']);
        
        $this->db->insert('tblservice_rental_agreement_details', $data);
        return $this->db->insert_id();
    }

    // Edit Line Item
    public function edit_rental_agreement_details($data, $id) {
        // Check if the item ID has changed (Swap Logic)
        $old_row = $this->db->where('service_rental_agreement_details_id', $id)->get('tblservice_rental_agreement_details')->row();

        if ($old_row && $old_row->serviceid != $data['serviceid']) {
            // Free the old item
            $this->db->where('serviceid', $old_row->serviceid)->update('tblservices_module', ['rental_status' => 'Not-Hired']);
            // Hire the new item
            $this->db->where('serviceid', $data['serviceid'])->update('tblservices_module', ['rental_status' => 'Hired']);
        }

        $this->db->where('service_rental_agreement_details_id', $id)->update('tblservice_rental_agreement_details', $data);
        return true;
    }

    // Delete Line Item (Restores Inventory Status to 'Not-Hired')
    public function delete_rental_agreement_details($id) {
        $row = $this->db->where('service_rental_agreement_details_id', $id)->get('tblservice_rental_agreement_details')->row();
        
        if ($row) {
            // Free the item
            $this->db->where('serviceid', $row->serviceid)->update('tblservices_module', ['rental_status' => 'Not-Hired']);
        }
        
        $this->db->where('service_rental_agreement_details_id', $id)->delete('tblservice_rental_agreement_details');
        return true;
    }

    // Get Attached Files
    public function get_rental_agreement_files($id) {
        $r = $this->db->where('service_rental_agreement_id', $id)->get('tblservice_rental_agreement')->row();
        return ($r && !empty($r->report_files)) ? json_decode($r->report_files, true) : [];
    }

    // --- CALENDAR DATA SOURCE ---
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
