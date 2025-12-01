<?php defined('BASEPATH') or exit('No direct script access allowed');

class Rentals_model extends App_Model {

    public function get_rental_agreement($code) { return $this->db->where('service_rental_agreement_code', $code)->get('tblservice_rental_agreement')->row(); }
    public function get_rental_agreement_details($id) { return $this->db->where('service_rental_agreement_id', $id)->get('tblservice_rental_agreement_details')->result(); }

    public function add_rental_agreement($data) { $this->db->insert('tblservice_rental_agreement', $data); return $this->db->insert_id(); }
    public function edit_rental_agreement($data) { $id=$data['service_rental_agreement_id']; unset($data['service_rental_agreement_id']); $this->db->where('service_rental_agreement_id', $id)->update('tblservice_rental_agreement', $data); }

    public function add_rental_agreement_details($data) { 
        $this->db->where('serviceid', $data['serviceid'])->update('tblservices_module', ['rental_status'=>'Hired']); 
        $this->db->insert('tblservice_rental_agreement_details', $data); 
    }
    public function edit_rental_agreement_details($data, $id) { $this->db->where('service_rental_agreement_details_id', $id)->update('tblservice_rental_agreement_details', $data); }
    public function delete_rental_agreement_details($id) {
        $row = $this->db->where('service_rental_agreement_details_id', $id)->get('tblservice_rental_agreement_details')->row();
        if($row) $this->db->where('serviceid', $row->serviceid)->update('tblservices_module', ['rental_status'=>'Not-Hired']);
        $this->db->where('service_rental_agreement_details_id', $id)->delete('tblservice_rental_agreement_details');
    }

    public function get_rental_agreement_files($id) { $r=$this->db->where('service_rental_agreement_id', $id)->get('tblservice_rental_agreement')->row(); return $r ? json_decode($r->report_files, true) : []; }
    
    public function get_calendar_rental_details() {
        return $this->db->select('d.*, a.start_date, a.end_date, m.name')
            ->from('tblservice_rental_agreement_details d')
            ->join('tblservice_rental_agreement a', 'a.service_rental_agreement_id = d.service_rental_agreement_id')
            ->join('tblservices_module m', 'm.serviceid = d.serviceid')
            ->where('a.status !=', 1)->get()->result();
    }
}