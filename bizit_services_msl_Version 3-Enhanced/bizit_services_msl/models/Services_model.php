<?php defined('BASEPATH') or exit('No direct script access allowed');

class Services_core_model extends App_Model {
    
    public function add($data) {
        unset($data['serviceid']);
        if ($data['service_type_code'] != '001') {
            unset($data['penalty_rental_price'], $data['rental_serial'], $data['rental_duration_check'], $data['rental_status']);
        } else { unset($data['quantity_unit']); }
        $this->db->insert('tblservices_module', $data);
        $insert_id = $this->db->insert_id();
        if ($insert_id) { log_activity('New Product Added [ID:' . $insert_id . ']'); return $insert_id; }
        return false;
    }

    public function edit($data) {
        $id = $data['serviceid']; unset($data['serviceid']);
        $this->db->where('service_code', $id)->update('tblservices_module', $data);
        return $this->db->affected_rows() > 0;
    }

    public function delete($id) {
        $this->db->where('service_code', $id)->delete('tblservices_module');
        return $this->db->affected_rows() > 0;
    }

    public function add_category($data) { unset($data['service_typeid']); $this->db->insert('tblservice_type', $data); return $this->db->insert_id(); }
    public function edit_category($data) { $id=$data['service_typeid']; unset($data['service_typeid']); $this->db->where('service_typeid',$id)->update('tblservice_type',$data); return true; }
    
    public function delete_category($id) {
        $this->db->where('service_type_code', $id);
        if($this->db->get('tblservices_module')->num_rows() > 0) return false;
        $this->db->where('type_code', $id)->delete('tblservice_type'); return true;
    }

    public function get_all_services_category_info() { return $this->db->order_by('service_typeid', 'ASC')->get('tblservice_type')->result_array(); }
    
    public function get_all_services($code) {
        $this->db->select('tblservices_module.*, tblservice_type.name as category_name');
        if($code) $this->db->where('service_type_code', $code);
        return $this->db->join('tblservice_type', 'tblservices_module.service_type_code = tblservice_type.type_code')->get('tblservices_module')->result();
    }
    
    public function get_service_by_code($code) { return $this->db->where('service_code', $code)->get('tblservices_module')->row(); }
    public function get_service_accessory_by_id($id) { return $this->db->select('rate')->where('id', $id)->get('tblitems')->row(); }
    public function get_all_services_accessories() { return $this->db->where('active', 1)->get('tblitems')->result(); }
    
    public function change_service_status($id, $status) { $this->db->where('service_code', $id)->update('tblservices_module', ['status' => $status]); }
    public function change_service_category_status($id, $status) { $this->db->where('type_code', $id)->update('tblservice_type', ['status' => $status]); }

    // --- RESTORED LEGACY FUNCTIONS ---
    public function get_services_information_by_id($id) {
        $this->db->select('tblservices_module.*, tblservice_type.name as category_name');
        $this->db->from('tblservices_module');
        $this->db->where('tblservices_module.serviceid', $id);
        $this->db->join('tblservice_type', 'tblservices_module.service_type_code = tblservice_type.type_code', 'left');
        return $this->db->get()->row();
    }

    public function get_all_services_info() {
        $this->db->select('tblservices_module.*, tblservice_type.name as category_name');
        $this->db->from('tblservices_module');
        $this->db->join('tblservice_type', 'tblservices_module.service_type_code = tblservice_type.type_code', 'left');
        $this->db->order_by('tblservices_module.serviceid', 'DESC');
        return $this->db->get()->result();
    }

    public function check_if_exist($select, $table, $value) {
        $this->db->select($select)->from($table)->where($select, $value);
        return $this->db->get()->num_rows() === 0;
    }

    // Helper for PDF Table Generation
    public function get_table_products_bulk($id) {
        $CI = &get_instance();
        $reqs = $CI->db->select('service_request_id')->where('invoice_rel_id', $id)->get('tblservice_request')->result();
        $html = '';
        foreach ($reqs as $r) {
            $details = $CI->db->where('service_request_id', $r->service_request_id)->get('tblservice_request_details')->result();
            foreach ($details as $d) {
                $name = $CI->db->select('name')->where('serviceid', $d->serviceid)->get('tblservices_module')->row()->name ?? 'Unknown';
                $html .= '<tr><td>'.$name.'</td><td>'.$r->service_request_id.'</td><td>'.$d->price.'</td></tr>';
            }
        }
        return ['html' => $html];
    }

    // --- GPS DATA SUPPORT (RESTORED) ---
    public function get_gps_details() {
        if ($this->db->table_exists('tblgps_data')) {
            return $this->db->get('tblgps_data')->result();
        }
        return [];
    }

    public function insert_gps_data($data) {
        if ($this->db->table_exists('tblgps_data')) {
            $this->db->insert('tblgps_data', $data);
            return $this->db->insert_id();
        }
        return false;
    }
}