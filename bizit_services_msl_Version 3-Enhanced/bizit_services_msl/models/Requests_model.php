<?php defined('BASEPATH') or exit('No direct script access allowed');

class Requests_model extends App_Model {
    
    public const CALIBRATION_ITEMS = ['Level', 'GNSS', 'Total Station', 'Theodolite', 'Lasers'];

    public function get_request($code) { return $this->db->where('service_request_code', $code)->get('tblservice_request')->row(); }
    public function get_request_details($id) { return $this->db->where('service_request_id', $id)->get('tblservice_request_details')->result(); }
    
    public function add_request($data) { $this->db->insert('tblservice_request', $data); return $this->db->insert_id(); }
    public function edit_request($data) { $id=$data['service_request_id']; unset($data['service_request_id']); $this->db->where('service_request_id', $id)->update('tblservice_request', $data); }

    public function add_request_details($data) { $this->db->insert('tblservice_request_details', $data); }
    public function edit_request_details($data, $id) { $this->db->where('service_request_details_id', $id)->update('tblservice_request_details', $data); }
    public function delete_request_details($id) { $this->db->where('service_request_details_id', $id)->delete('tblservice_request_details'); return $this->db->affected_rows() > 0; }

    public function get_request_accessories($id) { return $this->db->select('sra.*, i.description, i.rate')->from('tblservice_request_accessories sra')->join('tblitems i', 'sra.accessory_id = i.id')->where('sra.service_request_id', $id)->get()->result(); }
    
    public function get_inspection_data($id) { return $this->db->where('service_request_id', $id)->get('tblinspection_requests')->result_array(); }
    public function get_checklist_data($id) { return $this->db->where('service_request_id', $id)->get('tblchecklist1')->result_array(); }
    public function get_collection_data($id) { return $this->db->where('service_request_id', $id)->get('tblcollection1')->row(); }
    
    public function delete_inspection_item($service_request_id, $inspection_item, $inspection_type) {
        $this->db->where('service_request_id', $service_request_id);
        $this->db->where('inspection_item', $inspection_item);
        $this->db->where('inspection_type', $inspection_type);
        return $this->db->delete('tblinspection_requests');
    }

    public function add_request_calibration($data) { unset($data['service_info']); $this->db->insert('tblservices_calibration', $data); return $this->db->insert_id(); }
    public function edit_request_calibration($data, $id) { $this->db->where('calibration_id', $id)->update('tblservices_calibration', $data); }
    public function get_report_check($id) { return $this->db->where('service_request_id', $id)->get('tblservices_calibration')->row(); }
    
    public function get_service_files($id) { $r=$this->db->where('service_request_id', $id)->get('tblservice_request')->row(); return $r ? json_decode($r->report_files, true) : []; }
    public function get_gps_details() { return $this->db->get('tblgps_data')->result_array(); }
    
    public function add_gps_data($data) {
        $data['staffid'] = get_staff_user_id();
        $data['date_recorded'] = date('Y-m-d H:i:s');
        $this->db->insert('tblgps_data', $data);
        return $this->db->insert_id();
    }

    // --- RESTORED CALENDAR DATA ---
    public function get_calendar_service_details() {
        return $this->db->select('r.service_request_id, r.drop_off_date as start_date, r.collection_date as end_date, r.service_request_code, r.item_type as name, r.serial_no, r.status, c.company as client_name')
            ->from('tblservice_request r')
            ->join('tblclients c', 'c.userid = r.clientid', 'left')
            ->where('r.status !=', 3)
            ->get()->result();
    }
}