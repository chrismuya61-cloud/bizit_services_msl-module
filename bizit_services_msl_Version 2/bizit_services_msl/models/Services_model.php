<?php

if (!defined('BASEPATH')) {
    exit('No direct script access allowed');
}

/*
 * @author : Swivernet
 * Version: 1.0.9 (Repaired & Merged)
 */

class Services_Model extends App_Model
{
    // Constants
    public const CALIBRATION_ITEMS = ['Level', 'GNSS', 'Total Station', 'Theodolite', 'Lasers'];    

    // ==============================================================
    // CORE SERVICES CRUD
    // ==============================================================

    public function add($data)
    {
        unset($data['serviceid']);

        if ($data['service_type_code'] != '001') {
            unset($data['penalty_rental_price']);
            unset($data['rental_serial']);
            unset($data['rental_duration_check']);
            unset($data['rental_status']);
        } else {
            unset($data['quantity_unit']);
        }

        $this->db->insert('tblservices_module', $data);
        $insert_id = $this->db->insert_id();
        if ($insert_id) {
            log_activity('New Product Added [ID:' . $insert_id . ', ' . $data['name'] . ']');
            return $insert_id;
        }
        return false;
    }

    public function edit($data)
    {
        $service_code = $data['serviceid'];
        unset($data['serviceid']);
        $this->db->where('service_code', $service_code);
        $this->db->update('tblservices_module', $data);
        if ($this->db->affected_rows() > 0) {
            log_activity('Product Updated [ID: ' . $service_code . ', ' . $data['name'] . ']');
            return true;
        }
        return false;
    }

    public function delete($id)
    {
        $this->db->select('serviceid', false);
        $this->db->from('tblservices_module');
        $this->db->where('service_code', $id);
        $query = $this->db->get();
        if ($query->num_rows() > 0) {
            $this->db->where('service_code', $id);
            $this->db->delete('tblservices_module');
            log_activity('Service Deleted [ID: ' . $id . ']');
            return true;
        }
        return false;
    }

    // ==============================================================
    // CATEGORY MANAGEMENT
    // ==============================================================

    public function add_category($data)
    {
        unset($data['service_typeid']);
        $this->db->insert('tblservice_type', $data);
        $insert_id = $this->db->insert_id();
        if ($insert_id) {
            log_activity('New Service Category Added [ID:' . $insert_id . ', ' . $data['name'] . ']');
            return $insert_id;
        }
        return false;
    }

    public function edit_category($data)
    {
        $service_category_id = $data['service_typeid'];
        unset($data['service_typeid']);
        $this->db->where('service_typeid', $service_category_id);
        $this->db->update('tblservice_type', $data);
        if ($this->db->affected_rows() > 0) {
            log_activity('Service Category Updated [ID: ' . $service_category_id . ', ' . $data['name'] . ']');
            return true;
        }
        return false;
    }

    public function delete_category($id)
    {
        $this->db->select('service_code', false);
        $this->db->from('tblservices_module');
        $this->db->where('service_type_code', $id);
        $query = $this->db->get();
        if ($query->num_rows() > 0) {
            return false;
        } else {
            $this->db->where('type_code', $id);
            $this->db->delete('tblservice_type');
            log_activity('Service Category Deleted [ID: ' . $id . ']');
            return true;
        }
    }

    public function get_all_services_category_info($where = array(), $filtered = false)
    {
        $this->db->select('*', false);
        $this->db->order_by('tblservice_type.service_typeid', 'ASC');
        if ($filtered) {
            $this->db->where('type_code !=', '001');
            $this->db->where('type_code !=', '002');
        }
        $this->db->where($where);
        return $this->db->get('tblservice_type')->result_array();
    }

    // ==============================================================
    // STATUS & UTILITY METHODS (Restored)
    // ==============================================================

    public function change_service_status($id, $status)
    {
        $hook_data = hooks()->do_action('change_service_status', ['id' => $id, 'status' => $status]);
        $this->db->where('service_code', $hook_data['id']);
        $this->db->update('tblservices_module', ['status' => $hook_data['status']]);
        if ($this->db->affected_rows() > 0) {
            log_activity('Service Status Changed [ServiceID: ' . $id . ' Status: ' . $status . ']');
            return true;
        }
        return false;
    }

    public function change_service_category_status($id, $status)
    {
        $hook_data = hooks()->do_action('change_service_category_status', ['id' => $id, 'status' => $status]);
        $this->db->where('type_code', $hook_data['id']);
        $this->db->update('tblservice_type', ['status' => $hook_data['status']]);
        if ($this->db->affected_rows() > 0) {
            log_activity('Service Category Status Changed [ID: ' . $id . ' Status: ' . $status . ']');
            return true;
        }
        return false;
    }

    public function get_services_information_by_id($id)
    {
        $this->db->select('tblservices_module.*, tblsubcategory.subcategory_name, tblcategory.category_name', false);
        $this->db->from('tblservices_module');
        $this->db->where('tblservices_module.services_id', $id);
        $this->db->join('tblsubcategory', 'tblsubcategory.subcategory_id  =  tblservices_module.subcategory_id ', 'left');
        $this->db->join('tblcategory', 'tblcategory.category_id  =  tblsubcategory.category_id ', 'left');
        $this->db->order_by('tblservices_module.services_id', ' DESC');
        return $this->db->get()->row();
    }

    public function get_services_stock_information_by_id($id)
    {
        $this->db->select('tblservices_module.services_name, tblservices_moduleservices.*', false);
        $this->db->from('tblservices_moduleservices');
        $this->db->where('tblservices_moduleservices.id', $id);
        $this->db->join('tblservices_module', 'tblservices_module.services_id  =  tblservices_moduleservices.services_id ', 'inner');
        $this->db->order_by('tblservices_moduleservices.id', ' DESC');
        return $this->db->get()->row();
    }

    public function check_if_exist($select, $table, $value)
    {
        $this->db->select($select, false);
        $this->db->from($table);
        $this->db->where($select, $value);
        return $this->db->get()->num_rows() === 0;
    }

    public function get_all_services_info()
    {
        $this->db->select('tblservices_module.*, tblsubcategory.subcategory_name, tblcategory.category_name', false);
        $this->db->from('tblservices_module');
        $this->db->join('tblsubcategory', 'tblsubcategory.subcategory_id  =  tblservices_module.subcategory_id ', 'left');
        $this->db->join('tblcategory', 'tblcategory.category_id  =  tblsubcategory.category_id ', 'left');
        $this->db->order_by('tblservices_module.services_id', ' DESC');
        return $this->db->get()->result();
    }

    public function get_all_services($code, $filtered = false, $exclude = array())
    {
        $data = array();
        $this->db->select('tblservices_module.*, tblservice_type.name as category_name');
        if (isset($code) && $code != null) {
            $this->db->where('service_type_code', $code);
        }
        if ($code == '001' and $filtered) {
            $this->db->where('rental_status', 'Not-Hired');
        }
        if (count($exclude) > 0) {
            foreach ($exclude as $key => $value) {
                $this->db->where('service_type_code !=', $value);
            }
        }
        $this->db->join('tblservice_type', 'tblservices_module.service_type_code = tblservice_type.type_code AND tblservice_type.status = 1');
        return $this->db->get('tblservices_module')->result();
    }

    // ==============================================================
    // INSPECTIONS & ACCESSORIES (Restored)
    // ==============================================================

    public function get_inspection_data($service_request_id)
    {
        $this->db->where('service_request_id', $service_request_id);
        return $this->db->get('tblinspection_requests')->result_array();
    }

    public function get_checklist_data($service_request_id)
    {
        $this->db->select('*')->from('tblchecklist1')->where('service_request_id', $service_request_id);
        return $this->db->get()->result_array();
    }

    public function get_collection_data($service_request_id)
    {
        $this->db->select('*')->from('tblcollection1')->where('service_request_id', $service_request_id);
        return $this->db->get()->row(); 
    }

    public function delete_inspection_item($service_request_id, $inspection_item, $inspection_type)
    {
        $this->db->where('service_request_id', $service_request_id);
        $this->db->where('inspection_item', $inspection_item);
        $this->db->where('inspection_type', $inspection_type);
        return $this->db->delete('tblinspection_requests'); 
    }

    public function get_all_services_accessories()
    {
        $this->db->select('id, description, rate')->from('tblitems')->where('active', 1); 
        return $this->db->get()->result(); 
    }

    public function get_request_accessories($service_request_id)
    {
        $this->db->select('sra.id, sra.accessory_id, sra.price, ti.description, ti.rate');
        $this->db->from('tblservice_request_accessories sra');
        $this->db->join('tblitems ti', 'sra.accessory_id = ti.id', 'left'); 
        $this->db->where('sra.service_request_id', $service_request_id);
        $this->db->where('ti.active', 1); 
        return $this->db->get()->result();
    }

    public function get_service_accessory_by_id($id)
    {
        $this->db->select('rate')->from('tblitems')->where('id', $id);
        return $this->db->get()->row(); 
    }

    public function get_table_products_bulk($id)
    {
        $CI = &get_instance();
        $CI->db->select('service_request_id');
        $CI->db->where('invoice_rel_id', $id);
        $service_request_query = $CI->db->get('tblservice_request');

        $items_data = [];

        foreach ($service_request_query->result() as $service_request_row) {
            $service_request_id = $service_request_row->service_request_id;
            $CI->db->select('serviceid, price');
            $CI->db->where('service_request_id', $service_request_id);
            $details_query = $CI->db->get('tblservice_request_details');

            foreach ($details_query->result() as $details_row) {
                $serviceid = $details_row->serviceid;
                $price = $details_row->price;
                $CI->db->select('name');
                $CI->db->where('serviceid', $serviceid);
                $service_query = $CI->db->get('tblservices_module');
                $service_name = $service_query->row() ? $service_query->row()->name : 'Unknown Service';

                $items_data[] = '<tr><td>' . $service_name . '</td><td>' . $service_request_id . '</td><td>' . $price . '</td></tr>';
            }
        }
        return ['html' => implode('', $items_data)];
    }

    // ==============================================================
    // SERVICE REQUESTS & FILES
    // ==============================================================

    public function get_request($code = null)
    {
        $this->db->select('tblservice_request.*');
        $this->db->from('tblservice_request');
        $this->db->where('tblservice_request.service_request_code', $code);
        return $this->db->get()->row();
    }

    public function get_request_details($service_request_id = null)
    {
        $this->db->select('tblservice_request_details.*');
        $this->db->from('tblservice_request_details');
        $this->db->where('tblservice_request_details.service_request_id', $service_request_id);
        return $this->db->get()->result();
    }

    public function add_request($data)
    {
        $this->db->insert('tblservice_request', $data);
        $insert_id = $this->db->insert_id();
        if ($insert_id) {
            log_activity('New Service Request Added [ID:' . $insert_id . ', ' . $data['service_request_code'] . ']');
            return $insert_id;
        }
        return false;
    }

    public function edit_request($data)
    {
        $service_request_id = $data['service_request_id'];
        unset($data['service_request_id']);
        $this->db->where('service_request_id', $service_request_id);
        $this->db->update('tblservice_request', $data);
        if ($this->db->trans_status() === false) {
            return false;
        }
        log_activity('Service Request Updated [ID: ' . $service_request_id . ']');
        return true;
    }

    public function get_existing_files($service_request_id)
    {
        $this->db->select('report_files');
        $this->db->from('tblservice_request');
        $this->db->where('service_request_code', $service_request_id);
        $query = $this->db->get();
        if ($query->num_rows() > 0) {
            $result = $query->row();
            return json_decode($result->report_files, true) ?: [];
        }
        return [];
    }

    public function get_rental_agreement_files($agreement_id)
    {
        $this->db->select('report_files');
        $this->db->where('service_rental_agreement_id', $agreement_id);
        $result = $this->db->get('tblservice_rental_agreement')->row();
        if (!empty($result->report_files)) {
            return json_decode($result->report_files);
        }
        return []; 
    }

    public function get_service_files($service_request_id)
    {
        $this->db->select('report_files');
        $this->db->where('service_request_id', $service_request_id);
        $result = $this->db->get('tblservice_request')->row();
        if (!empty($result->report_files)) {
            return json_decode($result->report_files, true); 
        }
        return []; 
    }

    public function add_request_details($data)
    {
        $this->db->insert('tblservice_request_details', $data);
        $insert_id = $this->db->insert_id();
        if ($insert_id) {
            log_activity('New Service Request Detail Added [ID:' . $insert_id . ']');
            return $insert_id;
        }
        return false;
    }

    public function edit_request_details($data, $service_request_details_id)
    {
        $this->db->where('service_request_details_id', $service_request_details_id);
        $this->db->update('tblservice_request_details', $data);
        if ($this->db->affected_rows() > 0) {
            log_activity('Service Request Detail Updated [ID: ' . $service_request_details_id . ']');
            return true;
        }
        return false;
    }

    public function delete_request_details($id = null, $code = null)
    {
        $this->db->where('service_request_details_id', $id);
        $this->db->delete('tblservice_request_details');
        if ($this->db->affected_rows() > 0) {
            log_activity('Service Request Detail Deleted [ID: ' . $id . ']');
            return true;
        }
        return false;
    }

    public function add_request_calibration($data)
    {
        if(isset($data['service_info'])){
            unset($data['service_info']);
        }
        $this->db->insert('tblservices_calibration', $data);
        $insert_id = $this->db->insert_id();
        if ($insert_id) {
            log_activity('New Service Request Calibration Report Added [ID:' . $insert_id . ']');
            return $insert_id;
        }
        return false;
    }

    public function edit_request_calibration($data, $calibration_id)
    {
        $this->db->where('calibration_id', $calibration_id);
        $this->db->update('tblservices_calibration', $data);
        if ($this->db->affected_rows() > 0) {
            log_activity('Service Request Calibration Report Updated [ID: ' . $calibration_id . ']');
            return true;
        }
        return false;
    }

    public function get_report_check($id = null)
    {
        $this->db->select('tblservices_calibration.*', false);
        $this->db->from('tblservices_calibration');
        $this->db->where('tblservices_calibration.service_request_id', $id);
        return $this->db->get()->row();
    }

    // ==============================================================
    // RENTAL AGREEMENTS & FIELD REPORTS
    // ==============================================================

    public function get_rental_agreement($code = null)
    {
        $this->db->select('tblservice_rental_agreement.*');
        $this->db->from('tblservice_rental_agreement');
        $this->db->where('tblservice_rental_agreement.service_rental_agreement_code', $code);
        return $this->db->get()->row();
    }

    public function get_rental_agreement_details($service_rental_agreement_id = null)
    {
        $this->db->select('tblservice_rental_agreement_details.*');
        $this->db->from('tblservice_rental_agreement_details');
        $this->db->where('tblservice_rental_agreement_details.service_rental_agreement_id', $service_rental_agreement_id);
        return $this->db->get()->result();
    }

    public function add_rental_agreement($data)
    {
        $this->db->insert('tblservice_rental_agreement', $data);
        $insert_id = $this->db->insert_id();
        if ($insert_id) {
            log_activity('New Service Rental Agreement Added [ID:' . $insert_id . ', ' . $data['service_rental_agreement_code'] . ']');
            return $insert_id;
        }
        return false;
    }

    public function edit_rental_agreement($data)
    {
        $service_rental_agreement_id = $data['service_rental_agreement_id'];
        unset($data['service_rental_agreement_id']);
        $this->db->where('service_rental_agreement_id', $service_rental_agreement_id);
        $this->db->update('tblservice_rental_agreement', $data);
        if ($this->db->affected_rows() > 0) {
            log_activity('Service Rental Agreement Updated [ID: ' . $service_rental_agreement_id . ', ' . $data['service_rental_agreement_code'] . ']');
            return true;
        }
        return false;
    }

    public function add_rental_agreement_details($data)
    {
        $this->db->where('serviceid', $data['serviceid']);
        $this->db->update('tblservices_module', array('rental_status' => 'Hired'));
        $this->db->insert('tblservice_rental_agreement_details', $data);
        $insert_id = $this->db->insert_id();
        if ($insert_id) {
            log_activity('New Service rental_agreement Detail Added [ID:' . $insert_id . ']');
            return $insert_id;
        }
        return false;
    }

    public function edit_rental_agreement_details($data, $service_rental_agreement_details_id)
    {
        $serviceID = $this->db->where('service_rental_agreement_details_id', $service_rental_agreement_details_id)->get('tblservice_rental_agreement_details')->row()->serviceid;
        $this->db->where('serviceid', $serviceID);
        $this->db->update('tblservices_module', array('rental_status' => 'Hired'));

        $this->db->where('service_rental_agreement_details_id', $service_rental_agreement_details_id);
        $this->db->update('tblservice_rental_agreement_details', $data);
        if ($this->db->affected_rows() > 0) {
            log_activity('Service rental_agreement Detail Updated [ID: ' . $service_rental_agreement_details_id . ']');
            return true;
        }
        return false;
    }

    public function delete_rental_agreement_details($id = null, $code = null)
    {
        $service_details_arr = $this->db->where('service_rental_agreement_details_id', $id)->get('tblservice_rental_agreement_details')->row();
        if (!empty($service_details_arr)) {
            $this->db->where('serviceid', $service_details_arr->serviceid);
            $this->db->update('tblservices_module', array('rental_status' => 'Not-Hired'));
        }
        $this->db->where('service_rental_agreement_details_id', $id);
        $this->db->delete('tblservice_rental_agreement_details');
        if ($this->db->affected_rows() > 0) {
            log_activity('Service rental_agreement Detail Deleted [ID: ' . $id . ']');
            return true;
        }
        return false;
    }

    public function get_calendar_rental_details()
    {
        $this->db->select('tblservice_rental_agreement_details.*, tblservice_rental_agreement.*, tblservices_module.*', false);
        $this->db->from('tblservice_rental_agreement_details');
        $this->db->join('tblservice_rental_agreement', 'tblservice_rental_agreement.service_rental_agreement_id  =  tblservice_rental_agreement_details.service_rental_agreement_id ', 'left');
        $this->db->join('tblservices_module', 'tblservices_module.serviceid  =  tblservice_rental_agreement_details.serviceid ', 'left');
        $this->db->where('tblservice_rental_agreement.status !=', 1);
        $this->db->group_by('tblservices_module.serviceid');
        return $this->db->get()->result();
    }

    public function get_field_report($code = null)
    {
        $this->db->select('tblfield_report.*');
        $this->db->from('tblfield_report');
        $this->db->where('tblfield_report.report_code', $code);
        return $this->db->get()->row();
    }
    
    public function get_field_report_by_id($id)
    {
        return $this->db->where('field_report_id', $id)->get('tblfield_report')->row();
    }
    
    public function get_field_report_check($id = null)
    {
        $this->db->select('tblfield_report.*', false);
        $this->db->from('tblfield_report');
        $this->db->where('tblfield_report.service_rental_agreement_id', $id);
        return $this->db->get()->row();
    }

    public function add_field_report($data)
    {
        $rental_agreement_code = $data['rental_agreement_code'];
        unset($data['rental_agreement_code']);
        $rental_info = $this->get_rental_agreement($rental_agreement_code);
        $data['service_rental_agreement_id'] = $rental_info->service_rental_agreement_id;
        $data['report_code'] = $rental_agreement_code . '-1';
        $data['clientid'] = $rental_info->clientid;
        $data['added_by'] = get_staff_user_id();
        $this->db->insert('tblfield_report', $data);
        $insert_id = $this->db->insert_id();
        if ($insert_id) {
            log_activity('New ' . _l('field_report') . ' Added [Field Report ID:' . $insert_id . ' ' . _l('rental_agreement_code') . ' ' .  $data['site_name'] . ']');
            return $insert_id;
        }
        return false;
    }

    public function edit_field_report($data)
    {
        $field_report_id = $data['field_report_id'];
        $added_by = isset($data['added_by']) ? $data['added_by'] : "";
        unset($data['field_report_id'], $data['added_by'], $data['DataTables_Table_0_length']);

        if (isset($data['status']) && $data['status'] <= 2) {
            $data['rejected_by'] = null;
            $data['rejection_remarks'] = null;
        }

        $this->db->where('field_report_id', $field_report_id);
        $this->db->update('tblfield_report', $data);
        if ($this->db->affected_rows() > 0) {
            log_activity(_l('field_report') . ' Updated [ID: ' . $field_report_id . ', ' . $data['site_name'] . ']');
            
            if (isset($data['status'])) {
                // RESTORED NOTIFICATION LOGIC
                if ($data['status'] == 2) {
                    $approvers = unserialize(get_option('field_report_approvers'));
                    if (is_array($approvers)) {
                        foreach ($approvers as $key => $to_user) {
                             rental_agreement_report_notifications($to_user, '', $data['report_code'], 'approval_notice', $data['site_name'], true, true);
                        }
                    }
                } else if ($data['status'] == 3) {
                    rental_agreement_report_notifications($added_by, $data['rejected_by'], $data['report_code'], 'field_report_rejected', $data['site_name']);
                    foreach (unserialize(get_option('field_report_approvers')) as $key => $to_user) {
                        rental_agreement_report_notifications($to_user, '', $data['report_code'], 'field_report_rejected', $data['site_name'], true);
                    }
                } else if ($data['status'] == 4) {
                    rental_agreement_report_notifications($added_by, $data['approved_by'], $data['report_code'], 'field_report_approved', $data['site_name']);
                    foreach (unserialize(get_option('field_report_approvers')) as $key => $to_user) {
                        rental_agreement_report_notifications($to_user, '', $data['report_code'], 'field_report_approved', $data['site_name'], true);
                    }
                }
            }
            return true;
        }
        return false;
    }

    public function delete_file($id, $type, $type_id, $file_name)
    {
        if (is_dir(get_upload_path_by_type($type) . $type_id)) {
            if ($id != null) {
                $this->db->where('id', $id);
            } else if ($type_id != null) {
                $this->db->where('rel_id', $type_id);
            }
            $this->db->where('rel_type', $type);
            $this->db->delete('tblfiles');
            if ($id != null) {
                log_activity($type . ' File Deleted [' . $type . 'ID: ' . $type_id . ' - FileID: ' . $id . ']');
                unlink(get_upload_path_by_type($type) . $type_id . '/' . $file_name);
            } else {
                $dirname = get_upload_path_by_type($type) . $type_id;
                array_map('unlink', glob("$dirname/*.*"));
                rmdir($dirname);
            }
            return true;
        }
        return false;
    }

    public function delete_field_report($field_report_id)
    {
        $this->db->where('field_report_id', $field_report_id);
        $this->db->delete('tblfield_report');
        if ($this->db->affected_rows() > 0) {
            log_activity(_l('field_report') . ' Deleted [ID: ' . $field_report_id . ']');
            $this->delete_file('', 'field_report', $field_report_id, ''); 
            return true;
        }
        return false;
    }

    // ==============================================================
    // NEW STAFF COMPENSATION FEATURES
    // ==============================================================

    public function get_all_compensation_rates()
    {
        $this->db->select('t1.*, t2.name as service_name, CONCAT(t3.firstname, " ", t3.lastname) as staff_name');
        $this->db->from('tblstaff_service_rates t1');
        $this->db->join('tblservices_module t2', 't2.serviceid = t1.serviceid', 'left');
        $this->db->join('tblstaff t3', 't3.staffid = t1.staffid', 'left');
        return $this->db->get()->result_array();
    }

    public function update_compensation_rate($data)
    {
        $staff_ids = is_array($data['staffid']) ? $data['staffid'] : [$data['staffid']];
        $service_id = $data['serviceid'];
        $rate = $data['rate'];
        $allowance_type = $data['allowance_type'] ?? 'unit';
        $rate_id = $data['rate_id'] ?? null;
        $success = false;

        foreach ($staff_ids as $staffid) {
            if ($rate_id && count($staff_ids) === 1) {
                $this->db->where('id', $rate_id);
                $this->db->update('tblstaff_service_rates', ['rate' => $rate, 'allowance_type' => $allowance_type]);
                if ($this->db->affected_rows() > 0) $success = true;
                continue;
            }
            $this->db->where('staffid', $staffid)->where('serviceid', $service_id);
            $existing = $this->db->get('tblstaff_service_rates')->row();
            $insert_data = ['staffid' => $staffid, 'serviceid' => $service_id, 'rate' => $rate, 'allowance_type' => $allowance_type];
            
            if ($existing) {
                $this->db->where('id', $existing->id)->update('tblstaff_service_rates', $insert_data);
                if ($this->db->affected_rows() > 0) $success = true;
            } else {
                $this->db->insert('tblstaff_service_rates', $insert_data);
                if ($this->db->insert_id() > 0) $success = true;
            }
        }
        return $success;
    }
    
    public function get_staff_compensation_data($start_date, $end_date)
    {
        $rates = $this->get_all_compensation_rates();
        $staff_rates = [];
        foreach ($rates as $rate) {
            $staff_rates[$rate['staffid']][$rate['serviceid']] = ['rate' => $rate['rate'], 'type' => $rate['allowance_type']];
        }

        // Service Requests (SQL Fix Applied: drop_off_date)
        $service_sql = "SELECT t1.staffid, t4.total AS paid_revenue, t3.price AS service_value, t3.service_request_details_id, t3.serviceid, t5.name AS service_name
            FROM " . db_prefix() . "staff AS t1
            INNER JOIN tblservice_request AS t2 ON t2.received_by = t1.staffid
            INNER JOIN tblservice_request_details AS t3 ON t3.service_request_id = t2.service_request_id
            INNER JOIN " . db_prefix() . "invoices AS t4 ON t4.id = t2.invoice_rel_id AND t4.status = 2 
            INNER JOIN " . db_prefix() . "services_module AS t5 ON t5.serviceid = t3.serviceid
            WHERE t2.drop_off_date BETWEEN '$start_date' AND '$end_date'";
        $service_data = $this->db->query($service_sql)->result_array();
        
        // Rental Agreements
        $rental_sql = "SELECT t1.staffid, t4.total AS paid_revenue, (DATEDIFF(t2.end_date, t2.start_date) + 1 + t2.extra_days - t2.discounted_days) AS rental_days, t3.serviceid, t5.report_code, t6.name AS service_name
            FROM " . db_prefix() . "staff AS t1
            INNER JOIN tblservice_rental_agreement AS t2 ON t2.field_operator = t1.staffid
            INNER JOIN tblservice_rental_agreement_details AS t3 ON t3.service_rental_agreement_id = t2.service_rental_agreement_id
            INNER JOIN " . db_prefix() . "invoices AS t4 ON t4.id = t2.invoice_rel_id AND t4.status = 2 
            INNER JOIN " . db_prefix() . "field_report AS t5 ON t5.service_rental_agreement_id = t2.service_rental_agreement_id 
            INNER JOIN " . db_prefix() . "services_module AS t6 ON t6.serviceid = t3.serviceid
            WHERE t2.start_date BETWEEN '$start_date' AND '$end_date' AND t5.status >= 2";
        $rental_data = $this->db->query($rental_sql)->result_array();

        $merged = [];
        $all_staff = $this->staff_model->get();
        foreach ($all_staff as $staff) {
            $merged[$staff['staffid']] = [
                'staffid' => $staff['staffid'], 'full_name' => $staff['firstname'] . ' ' . $staff['lastname'],
                'service_revenue' => 0, 'rental_revenue' => 0, 'total_revenue' => 0,
                'service_units' => 0, 'rental_days' => 0,
                'service_allowance' => 0, 'rental_allowance' => 0, 'total_allowance' => 0,
                'service_details' => [], 'rental_details' => []
            ];
        }

        foreach ($service_data as $item) {
            $sid = $item['staffid'];
            $rate = $staff_rates[$sid][$item['serviceid']]['rate'] ?? 0;
            $allowance = $rate * 1;
            $merged[$sid]['service_revenue'] += $item['paid_revenue'];
            $merged[$sid]['total_revenue'] += $item['paid_revenue'];
            $merged[$sid]['service_units'] += 1;
            $merged[$sid]['service_allowance'] += $allowance;
            $merged[$sid]['total_allowance'] += $allowance;
            $merged[$sid]['service_details'][] = ['name' => $item['service_name'], 'revenue' => $item['service_value'], 'allowance' => $allowance];
        }

        foreach ($rental_data as $item) {
            $sid = $item['staffid'];
            $rate = $staff_rates[$sid][$item['serviceid']]['rate'] ?? 0;
            $allowance = $rate * $item['rental_days'];
            $merged[$sid]['rental_revenue'] += $item['paid_revenue'];
            $merged[$sid]['total_revenue'] += $item['paid_revenue'];
            $merged[$sid]['rental_days'] += $item['rental_days'];
            $merged[$sid]['rental_allowance'] += $allowance;
            $merged[$sid]['total_allowance'] += $allowance;
            $merged[$sid]['rental_details'][] = ['name' => $item['service_name'], 'revenue' => $item['paid_revenue'], 'allowance' => $allowance];
        }
        return array_values($merged);
    }
}