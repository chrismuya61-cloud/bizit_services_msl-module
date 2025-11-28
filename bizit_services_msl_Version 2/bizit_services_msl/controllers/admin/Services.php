<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Services extends AdminController
{
    public function __construct()
    {
        parent::__construct();
        $this->load->model('services_model');
        $this->load->model('clients_model');
        $this->load->model('invoices_model');
        // FIX: Globally load necessary helpers to prevent "undefined function" errors
        $this->load->helper(['bizit_services_msl', 'invoices', 'number']);
        $this->load->library('ciqrcode');
    }

    /* List all available items */
    public function index()
    {
        if (!has_permission(BIZIT_SERVICES_MSL, '', 'view') and !is_admin()) {
            access_denied('Services');
        }
        if ($this->input->is_ajax_request()) {
            $this->app->get_table_data(module_views_path(BIZIT_SERVICES_MSL, 'table/services'));;
        }
        $data['currency_symbol'] = get_default_currency('symbol');
        $data['service_categories'] = $this->services_model->get_all_services_category_info();
        $data['title'] = _l('services');
        $this->load->view('admin/services/manage', $data);
    }

    // ==================================================
    // NEW FEATURES: STAFF COMPENSATION & DASHBOARD
    // ==================================================

    public function staff_compensation_rates()
    {
        if (!staff_can('view', BIZIT_SERVICES_MSL . '_compensation_rates')) {
            access_denied('Staff Compensation Rates');
        }
        $this->load->helper(['number', 'invoices']); 

        if ($this->input->post()) {
            if (!staff_can('edit', BIZIT_SERVICES_MSL . '_compensation_rates')) {
                access_denied('Staff Compensation Rates');
            }
            $data = $this->input->post();
            $success = $this->services_model->update_compensation_rate($data);
            if ($success) {
                set_alert('success', 'Compensation rates updated successfully.');
            } else {
                set_alert('danger', 'Error or no changes made to compensation rates.');
            }
            redirect(admin_url('services/staff_compensation_rates'));
        }

        $this->load->model('staff_model');
        $data['staff_members'] = $this->staff_model->get();
        $data['all_services'] = $this->services_model->get_all_services(null);
        $data['rates'] = $this->services_model->get_all_compensation_rates();
        $data['title'] = 'Staff Compensation Rates';
        $this->load->view('admin/services/staff_compensation_rates', $data);
    }

    public function reports_dashboard()
    {
        if (!staff_can('view', BIZIT_SERVICES_MSL . '_reports_dashboard')) {
            access_denied('Reports Dashboard');
        }

        $data['start_date'] = $this->input->post('start_date') ? to_sql_date($this->input->post('start_date'), true) : date('Y-01-01');
        $data['end_date'] = $this->input->post('end_date') ? to_sql_date($this->input->post('end_date'), true) : date('Y-12-31');
        $data['performance_data'] = $this->services_model->get_staff_compensation_data($data['start_date'], $data['end_date']);
        $data['title'] = 'Staff Performance and Compensation Dashboard';
        $this->load->view('admin/services/reports_dashboard', $data);
    }

    // ==================================================
    // CORE MODULE FUNCTIONS (Restored & Fixed)
    // ==================================================

    public function get_serial_numbers_by_commodity_code()
    {
        $commodity_code = $this->input->post('commodity_code');
        if ($commodity_code) {
            $this->db->select('tblwh_inventory_serial_numbers.serial_number');
            $this->db->from('tblwh_inventory_serial_numbers');
            $this->db->join('tblitems', 'tblitems.id = tblwh_inventory_serial_numbers.commodity_id');
            $this->db->where('tblitems.commodity_code', $commodity_code);
            $query = $this->db->get();
            echo json_encode($query->num_rows() > 0 ? $query->result_array() : []);
        } else {
            echo json_encode([]);
        }
    }

    public function get_service_code()
    {
        $serviceId = $this->input->post('serviceid');
        $this->db->select('service_code');
        $this->db->from('tblservices_module');
        $this->db->where('serviceid', $serviceId);
        $query = $this->db->get();
        $service = $query->row_array();
        echo json_encode($service);
    }

    public function sales_list()
    {
        if ($this->input->is_ajax_request()) {
            $this->app->get_table_data(module_views_path(BIZIT_SERVICES_MSL, 'table/services_sales_list'));;
        }
        $data['title'] = _l('als_services_sales_list');
        $this->load->view('admin/services/manage_sales_list', $data);
    }

    public function manage()
    {
        if (has_permission(BIZIT_SERVICES_MSL, '', 'view')) {
            if ($this->input->post()) {
                $data = $this->input->post();
                if ($data['serviceid'] == '') {
                    if (!has_permission(BIZIT_SERVICES_MSL, '', 'create')) {
                        header('HTTP/1.0 400 Bad error'); echo _l('access_denied'); die;
                    }
                    $id = $this->services_model->add($data);
                    $success = $id ? true : false;
                    $message = $id ? _l('added_successfully', _l('service')) : '';
                    echo json_encode(['success' => $success, 'message' => $message]);
                } else {
                    if (!has_permission(BIZIT_SERVICES_MSL, '', 'edit')) {
                        header('HTTP/1.0 400 Bad error'); echo _l('access_denied'); die;
                    }
                    $success = $this->services_model->edit($data);
                    $message = $success ? _l('updated_successfully', _l('service')) : 'Nothing updated!';
                    echo json_encode(['success' => $success, 'message' => $message]);
                }
            }
        }
    }

    public function delete($id)
    {
        if (!has_permission(BIZIT_SERVICES_MSL, '', 'delete')) { access_denied('Services'); }
        if (!$id) { redirect(admin_url('services')); }

        $response = $this->services_model->delete($id);
        if (is_array($response) && isset($response['referenced'])) {
            set_alert('warning', _l('is_referenced', _l('service_lowercase')));
        } elseif ($response == true) {
            set_alert('success', _l('deleted', _l('service')));
        } else {
            set_alert('warning', _l('problem_deleting', _l('service_lowercase')));
        }
        redirect(admin_url('services'));
    }

    /* FIXED: Category Manage with Auto Code Generation & Modal View */
    public function category_manage()
    {
        if (has_permission(BIZIT_SERVICES_MSL, '', 'view')) {
            if ($this->input->post()) {
                $data = $this->input->post();
                if ($data['service_typeid'] == '') {
                    if (!has_permission(BIZIT_SERVICES_MSL, '', 'create')) { echo _l('access_denied'); die; }
                    
                    // Generate code if missing
                    if (empty($data['type_code'])) {
                        $data['type_code'] = get_next_service_category_code_internal();
                    }

                    $id = $this->services_model->add_category($data);
                    $success = $id ? true : false;
                    $message = $id ? _l('added_successfully', _l('service_type')) : '';
                    echo json_encode(['success' => $success, 'message' => $message]);
                } else {
                    if (!has_permission(BIZIT_SERVICES_MSL, '', 'edit')) { echo _l('access_denied'); die; }
                    $success = $this->services_model->edit_category($data);
                    $message = $success ? _l('updated_successfully', _l('service_type')) : _l('no_changes', _l('service_type'));
                    echo json_encode(['success' => $success, 'message' => $message]);
                }
            } else {
                // LOAD MODAL VIEW FOR GET REQUESTS
                $this->load->view('admin/services/modal_category');
            }
        }
    }

    public function delete_category($id)
    {
        if (!has_permission(BIZIT_SERVICES_MSL, '', 'delete')) { access_denied('Services'); }
        if (!$id) { redirect(admin_url('services')); }
        $response = $this->services_model->delete_category($id);
        if (is_array($response) && isset($response['referenced'])) {
            set_alert('warning', _l('is_referenced', _l('product_lowercase') . ' category'));
        } elseif ($response == true) {
            set_alert('success', _l('deleted', _l('product') . ' category'));
        } else {
            set_alert('warning', _l('problem_deleting', _l('product_lowercase') . ' category'));
        }
        redirect(admin_url('services'));
    }

    public function service_category()
    {
        if (!has_permission(BIZIT_SERVICES_MSL, '', 'view')) { access_denied('Services'); }
        if ($this->input->is_ajax_request()) {
            $this->app->get_table_data(module_views_path(BIZIT_SERVICES_MSL, 'table/service_category'));;
        }
    }

    public function change_service_status($id, $status)
    {
        if ($this->input->is_ajax_request()) { $this->services_model->change_service_status($id, $status); }
    }

    public function change_service_category_status($id, $status)
    {
        if ($this->input->is_ajax_request()) { $this->services_model->change_service_category_status($id, $status); }
    }

    public function get_services($id_code)
    {
        if (!has_permission(BIZIT_SERVICES_MSL, '', 'view')) { access_denied('Services'); }
        $data = array();
        if ($id_code != null):
            $this->db->where('service_type_code', $id_code);
            $data = $this->db->get('tblservices_module')->result();
        endif;
        header('content-type: application/json; charset=utf-8');
        echo json_encode($data);
    }

    public function get_service_by_code($service_code)
    {
        if (!has_permission(BIZIT_SERVICES_MSL, '', 'view')) { access_denied('Services'); }
        $data = array();
        if ($service_code != null):
            $this->db->select('tblservices_module.*, tblservice_type.name as category_name');
            $this->db->where('service_code', $service_code);
            $this->db->join('tblservice_type', 'tblservices_module.service_type_code = tblservice_type.type_code');
            $data = $this->db->get('tblservices_module')->row();
        endif;
        header('content-type: application/json; charset=utf-8');
        echo json_encode($data);
    }

    public function get_service_by_id($serviceid)
    {
        if (!has_permission(BIZIT_SERVICES_MSL, '', 'view')) { access_denied('Services'); }
        $data = array();
        if ($serviceid != null):
            $this->db->select('tblservices_module.*, tblservice_type.name as category_name');
            $this->db->where('serviceid', $serviceid);
            $this->db->join('tblservice_type', 'tblservices_module.service_type_code = tblservice_type.type_code');
            $data = $this->db->get('tblservices_module')->row();
        endif;
        header('content-type: application/json; charset=utf-8');
        echo json_encode($data);
    }

    public function getNextServiceCode($id_code = null)
    {
        if (!has_permission(BIZIT_SERVICES_MSL, '', 'view')) { access_denied('Services'); }
        if ($id_code != null):
            $this->db->where('service_type_code', $id_code);
            $q = $this->db->get('tblservices_module')->result();
            $p_service_code = $id_code;
            if ($q) {
                $last = end($q);
                $l_array = explode('-', $last->service_code);
                $new_index = end($l_array);
                $new_index += 1;
                echo $p_service_code . "-" . sprintf("%04d", $new_index);
            } else {
                echo $p_service_code . "-" . sprintf("%04d", 1);
            }
        endif;
    }

    public function getNextServiceCategoryCode()
    {
        if (!has_permission(BIZIT_SERVICES_MSL, '', 'view')) { access_denied('Services'); }
        echo get_next_service_category_code_internal();
    }

    //==========================================================
    //  SERVICE REQUESTS
    //==========================================================

    public function requests()
    {
        if (!has_permission(BIZIT_SERVICES_MSL, '', 'view')) { access_denied('Services'); }
        if ($this->input->is_ajax_request()) {
            $this->app->get_table_data(module_views_path(BIZIT_SERVICES_MSL, 'table/services_requests'));;
        }
        $data['title'] = _l('als_services_requests');
        $this->load->view('admin/services/manage_requests', $data);
    }

    public function new_request($flag = null, $code = null)
    {
        if (!has_permission(BIZIT_SERVICES_MSL, '', 'create')) { access_denied('Services'); }
        if (!is_null($flag) && !is_numeric($flag)) { redirect(admin_url('services/new_request')); }

        if (empty($flag)) {
            $this->session->unset_userdata(['service_request_code' => '']);
            $random_number = rand(10000000, 99999);
            $q = $this->db->get('tblservice_request')->result();
            if (!empty($q)) { $last = end($q); $random_number .= $last->service_request_id; }
            $this->session->set_userdata(['service_request_code' => $random_number]);
        }

        $data['all_services'] = $this->services_model->get_all_services('002');
        $data['currency_symbol'] = get_default_currency('symbol');
        $data['all_services_accessories'] = $this->services_model->get_all_services_accessories();

        if ($code != null) {
            $data['request'] = $this->services_model->get_request($code);
            if (empty($data['request'])) { show_error('Invalid service request code provided.'); }
            
            $data['request_details'] = $this->services_model->get_request_details($data['request']->service_request_id);
            $data['service_file_info'] = $data['request'];
            $data['uploaded_files'] = $this->services_model->get_service_files($data['request']->service_request_id) ?? [];
            $data['inspection_data'] = $this->services_model->get_inspection_data($data['request']->service_request_id);
            $data['checklist_items'] = $this->services_model->get_checklist_data($data['request']->service_request_id);
            $data['collection_data'] = $this->services_model->get_collection_data($data['request']->service_request_id);

            $data['dropped_off_by'] = $data['request']->dropped_off_by;
            $data['dropped_off_date'] = $data['request']->dropped_off_date;
            $data['dropped_off_signature'] = $data['request']->dropped_off_signature;
            $data['dropped_off_id_number'] = $data['request']->dropped_off_id_number;
            $data['req_received_by'] = $data['request']->req_received_by;
            $data['received_date'] = $data['request']->received_date;
            $data['received_signature'] = $data['request']->received_signature;
            $data['received_id_number'] = $data['request']->received_id_number;

            $data['pre_inspection_items'] = []; $data['post_inspection_items'] = [];
            if (!empty($data['inspection_data'])) {
                foreach ($data['inspection_data'] as $inspection) {
                    $type = is_array($inspection) ? $inspection['inspection_type'] : $inspection->inspection_type;
                    if ($type == 'pre_inspection') { $data['pre_inspection_items'][] = $inspection; }
                    elseif ($type == 'post_inspection') { $data['post_inspection_items'][] = $inspection; }
                }
            }
            $this->session->set_userdata(['service_request_code' => $data['request']->service_request_code]);
            $data['existing_accessories'] = $this->services_model->get_request_accessories($data['request']->service_request_id);
        }
        $data['title'] = _l('add_service_request');
        $this->load->view('admin/services/service_requests_form', $data);
    }

    public function save_request()
    {
        if (!has_permission(BIZIT_SERVICES_MSL, '', 'create')) { access_denied('Services'); }
        $data_service = [
            'service_request_code'  => $this->input->post('service_request_code', true),
            'clientid'              => $this->input->post('clientid', true),
            'drop_off_date'         => to_sql_date($this->input->post('drop_off_date', true)),
            'collection_date'       => to_sql_date($this->input->post('collection_date', true)),
            'condition'             => $this->input->post('condition', true),
            'item_type'             => $this->input->post('type', true),
            'item_model'            => $this->input->post('model', true),
            'item_make'             => $this->input->post('make', true),
            'serial_no'             => $this->input->post('serial_no', true),
            'service_note'          => strip_tags($this->input->post('service_note', true)),
            'received_by'           => get_staff_user_id(),
            'dropped_off_by'        => $this->input->post('dropped_off_by', true),
            'dropped_off_date'      => to_sql_date($this->input->post('dropped_off_date', true)),
            'dropped_off_signature' => $this->input->post('dropped_off_signature', true),
            'dropped_off_id_number' => $this->input->post('dropped_off_id_number', true),
            'req_received_by'       => $this->input->post('req_received_by', true),
            'received_date'         => to_sql_date($this->input->post('received_date', true)),
            'received_signature'    => $this->input->post('received_signature', true),
            'received_id_number'    => $this->input->post('received_id_number', true)
        ];

        $submitted_accessory_ids = $this->input->post('accessoryserviceid', true);
        $submitted_accessory_prices = $this->input->post('accessoryservice_price', true);
        $serviceid = $this->input->post('serviceid', true);
        $service_price = $this->input->post('service_price', true);
        $edit_id = $this->input->post('edit_id', true);
        $service_request_details_id_edit = $this->input->post('service_request_details_id', true);

        $uploaded_files = $this->handle_file_uploads();
        $data_service['report_files'] = json_encode($uploaded_files, JSON_PRETTY_PRINT);

        $service_request_id = $this->save_or_update_service_request($data_service, $edit_id);
        $this->handle_inspection_data($service_request_id);
        $this->handle_checklist_and_collection_data($service_request_id);

        if ($serviceid && $service_price) {
            $this->process_service_request_details($serviceid, $service_price, $service_request_details_id_edit, $edit_id ?? $service_request_id);
        }

        $submitted_accessory_ids = !empty($submitted_accessory_ids) ? $submitted_accessory_ids : [];
        $this->db->select('accessory_id');
        $this->db->from('tblservice_request_accessories');
        $this->db->where('service_request_id', $service_request_id);
        $existing_accessories = $this->db->get()->result_array();
        $existing_accessory_ids = !empty($existing_accessories) ? array_column($existing_accessories, 'accessory_id') : [];

        $accessories_to_delete = array_diff($existing_accessory_ids, $submitted_accessory_ids);
        if (!empty($accessories_to_delete)) {
            $this->db->where('service_request_id', $service_request_id);
            $this->db->where_in('accessory_id', $accessories_to_delete);
            $this->db->delete('tblservice_request_accessories');
        }
        if (!empty($submitted_accessory_ids)) {
            foreach ($submitted_accessory_ids as $index => $accessory_id) {
                if (!in_array($accessory_id, $existing_accessory_ids)) {
                    $accessory_data = [
                        'service_request_id' => $service_request_id,
                        'accessory_id'       => $accessory_id,
                        'price'              => $submitted_accessory_prices[$index]
                    ];
                    $this->db->insert('tblservice_request_accessories', $accessory_data);
                }
            }
        }
        set_alert('success', $edit_id ? 'Service request updated successfully' : 'Service request added successfully');
        redirect(admin_url('services/view_request/' . $data_service['service_request_code']), 'refresh');
    }

    // Helper methods for save_request
    private function handle_file_uploads()
    {
        $uploaded_files = [];
        if (!empty($this->input->post('report_files'))) {
            $existing_files = array_filter($this->input->post('report_files'), fn($file) => !empty($file));
            $uploaded_files = array_merge($existing_files, $uploaded_files);
        }
        $config = ['upload_path' => './modules/bizit_services_msl/uploads/reports/', 'allowed_types' => 'jpg|jpeg|png|pdf|doc|docx|xls|xlsx', 'max_size' => 4096];
        $this->load->library('upload', $config);
        if (!empty($_FILES['service_files'])) {
            $this->load->library('upload');
            $files = $_FILES['service_files'];
            for ($i = 0; $i < count($files['name']); $i++) {
                if ($files['name'][$i] != '') {
                    $_FILES['file'] = ['name' => $files['name'][$i], 'type' => $files['type'][$i], 'tmp_name' => $files['tmp_name'][$i], 'error' => $files['error'][$i], 'size' => $files['size'][$i]];
                    if ($this->upload->do_upload('file')) {
                        $file_data = $this->upload->data(); $uploaded_files[] = $file_data['file_name'];
                    }
                }
            }
        }
        return $uploaded_files;
    }

    private function save_or_update_service_request($data_service, $edit_id)
    {
        if ($edit_id) {
            $data_service['service_request_id'] = $edit_id;
            $this->services_model->edit_request($data_service);
            return $edit_id;
        } else {
            $this->db->insert('tblservice_request', $data_service);
            return $this->db->insert_id();
        }
    }

    private function handle_inspection_data($service_request_id)
    {
        $pre_inspection_items = $this->input->post('inspection_items', true);
        $pre_remarks = $this->input->post('remarks', true);
        $post_inspection_items = $this->input->post('postinspection_items', true);
        $post_remarks = $this->input->post('postremarks', true);
        $removed_pre_items = $this->input->post('removed_pre_items', true);
        $removed_post_items = $this->input->post('removed_post_items', true);

        if ($service_request_id) {
            if ($removed_pre_items) { $this->remove_inspection_items($service_request_id, explode(',', $removed_pre_items), 'pre_inspection'); }
            if ($removed_post_items) { $this->remove_inspection_items($service_request_id, explode(',', $removed_post_items), 'post_inspection'); }
            if (!empty($pre_inspection_items)) { $this->handle_inspection_items($service_request_id, $pre_inspection_items, $pre_remarks, 'pre_inspection'); }
            if (!empty($post_inspection_items)) { $this->handle_inspection_items($service_request_id, $post_inspection_items, $post_remarks, 'post_inspection'); }
        }
    }

    private function remove_inspection_items($service_request_id, $items, $type)
    {
        if (!empty($items)) {
            $this->db->where_in('inspection_item', $items);
            $this->db->where('service_request_id', $service_request_id);
            $this->db->where('inspection_type', $type);
            $this->db->delete('tblinspection_requests');
        }
    }

    private function handle_inspection_items($service_request_id, $inspection_items, $remarks, $type)
    {
        foreach ($inspection_items as $item) {
            $data = ['service_request_id' => $service_request_id, 'inspection_item' => $item, 'remarks_condition' => isset($remarks[$item]) ? $remarks[$item] : null, 'inspection_type' => $type];
            $existing = $this->db->get_where('tblinspection_requests', ['service_request_id' => $service_request_id, 'inspection_item' => $item, 'inspection_type' => $type])->row();
            if ($existing) { $this->db->update('tblinspection_requests', $data, ['id' => $existing->id]); } 
            else { $this->db->insert('tblinspection_requests', $data); }
        }
    }

    private function process_service_request_details($serviceid, $service_price, $service_request_details_id_edit, $service_request_id)
    {
        for ($i = 0; $i < count($serviceid); $i++) {
            if (!empty($serviceid[$i]) && !empty($service_price[$i])) {
                $data_service_details = ['service_request_id' => $service_request_id, 'serviceid' => $serviceid[$i], 'price' => $service_price[$i]];
                if (!empty($service_request_details_id_edit[$i])) { $this->services_model->edit_request_details($data_service_details, $service_request_details_id_edit[$i]); } 
                else { $this->services_model->add_request_details($data_service_details); }
            }
        }
    }

    public function delete_accessory()
    {
        $id = $this->input->post('id', true);
        if ($id) {
            $this->db->where('id', $id); $deleted = $this->db->delete('tblservice_request_accessories');
            echo json_encode(['success' => $deleted, 'message' => $deleted ? 'Accessory deleted successfully' : 'Failed to delete']);
        } else { echo json_encode(['success' => false, 'message' => 'Invalid accessory ID']); }
    }

    public function handle_checklist_and_collection_data($service_request_id)
    {
        $item_status = $this->input->post('item_status', true);
        $checklist_items = ['Calibration Certificate Issued', 'Calibration Sticker Issued', 'Date of Next Calibration Advised', 'Equipment in Good Condition'];
        
        if ($item_status && $service_request_id) {
            foreach ($checklist_items as $index => $item) {
                $data = ['service_request_id' => $service_request_id, 'item' => $item, 'status' => $item_status[$index]];
                $existing = $this->db->get_where('tblchecklist1', ['service_request_id' => $service_request_id, 'item' => $item])->row();
                if ($existing) { $this->db->update('tblchecklist1', $data, ['id' => $existing->id]); } 
                else { $this->db->insert('tblchecklist1', $data); }
            }
        }
        $collection_data = [
            'service_request_id'   => $service_request_id,
            'released_by'          => $this->input->post('released_by', true),
            'released_date'        => to_sql_date($this->input->post('released_date', true)),
            'released_id_number'   => $this->input->post('released_id_number', true),
            'collected_by'         => $this->input->post('collected_by', true),
            'collected_date'       => to_sql_date($this->input->post('collected_date', true)),
            'collected_id_number'  => $this->input->post('collected_id_number', true),
        ];
        $existing_col = $this->db->get_where('tblcollection1', ['service_request_id' => $service_request_id])->row();
        if ($existing_col) { $this->db->update('tblcollection1', $collection_data, ['service_request_id' => $service_request_id]); } 
        else { $this->db->insert('tblcollection1', $collection_data); }
    }

    public function get_service_accessory_by_id($id)
    {
        $result = $this->services_model->get_service_accessory_by_id($id);
        echo json_encode($result ? ['success' => true, 'rate' => $result->rate] : ['success' => false, 'message' => 'Item not found']);
        exit;
    }

    public function delete_service_price($id, $code)
    {
        if (!has_permission(BIZIT_SERVICES_MSL, '', 'edit')) { access_denied('Services'); }
        $deleted = $this->services_model->delete_request_details($id, $code);
        set_alert($deleted ? 'success' : 'warning', $deleted ? 'Successfully deleted service' : 'Failed to delete service');
        redirect(admin_url('services/new_request/1/' . $code));
    }

    public function view_request($code = null)
    {
        if (!has_permission(BIZIT_SERVICES_MSL, '', 'view')) { access_denied('Services'); }
        $data['service_info'] = $this->db->where('service_request_code', $code)->get('tblservice_request')->row();
        if (empty($data['service_info'])) { redirect(admin_url('services/manage_requests')); }
        
        $data['dropped_off_by'] = $data['service_info']->dropped_off_by ?? 'N/A';
        $data['dropped_off_date'] = $data['service_info']->dropped_off_date ?? 'N/A';
        $data['dropped_off_signature'] = $data['service_info']->dropped_off_signature ?? 'N/A';
        $data['dropped_off_id_number'] = $data['service_info']->dropped_off_id_number ?? 'N/A';
        $data['received_by'] = $data['service_info']->req_received_by ?? 'N/A';
        $data['received_date'] = $data['service_info']->received_date ?? 'N/A';
        $data['received_signature'] = $data['service_info']->received_signature ?? 'N/A';
        $data['received_id_number'] = $data['service_info']->received_id_number ?? 'N/A';

        $data['service_details'] = $this->db->select('tblservice_request_details.*, tblservices_module.name, tblservice_type.name as category_name, tblservice_type.service_typeid')->where('service_request_id', $data['service_info']->service_request_id)->join('tblservices_module', 'tblservices_module.serviceid = tblservice_request_details.serviceid')->join('tblservice_type', 'tblservices_module.service_type_code = tblservice_type.type_code')->get('tblservice_request_details')->result();
        
        $inspection_items = $this->db->where('service_request_id', $data['service_info']->service_request_id)->get('tblinspection_requests')->result();
        $data['pre_inspection_items'] = []; $data['post_inspection_items'] = [];
        foreach ($inspection_items as $item) {
            if ($item->inspection_type == 'pre_inspection') $data['pre_inspection_items'][] = $item;
            elseif ($item->inspection_type == 'post_inspection') $data['post_inspection_items'][] = $item;
        }
        $data['checklist_items'] = $this->db->where('service_request_id', $data['service_info']->service_request_id)->get('tblchecklist1')->result();
        $data['released_info'] = $this->db->where('service_request_id', $data['service_info']->service_request_id)->get('tblcollection1')->row();
        $data['currency_symbol'] = get_default_currency('symbol');
        $data['service_request_client'] = $this->clients_model->get($data['service_info']->clientid);
        $data['existing_accessories'] = $this->db->select('tblservice_request_accessories.id, tblitems.description, tblservice_request_accessories.price')->from('tblservice_request_accessories')->join('tblitems', 'tblitems.id = tblservice_request_accessories.accessory_id', 'left')->where('tblservice_request_accessories.service_request_id', $data['service_info']->service_request_id)->get()->result();
        $data['title'] = 'Service Request View';
        $this->load->view('admin/services/view_request', $data);
    }

    public function service_re_confirmation()
    {
        if (!has_permission(BIZIT_SERVICES_MSL, '', 'edit')) { access_denied('Services'); }
        $data['status'] = $this->input->post('status', true);
        $service_request_id = $this->input->post('service_request_id', true);
        $service_request_code = $this->input->post('service_request_code', true);
        if (in_array($data['status'], [0,1,2,3])) { $this->db->where('service_request_id', $service_request_id)->update('tblservice_request', $data); }
        set_alert('success', 'Status change successful');
        redirect(admin_url('services/view_request/' . $service_request_code));
    }

    public function report($flag = null, $code = null)
    {
        if (!has_permission(BIZIT_SERVICES_MSL, '', 'view')) { access_denied('Services'); }
        if (empty($flag)) { redirect(admin_url('services/requests')); }

        if ($flag == '1') {
            $service_info = $this->db->where(array('service_request_code' => $code))->get('tblservice_request')->row();
            $data['service_request_id'] = $service_info->service_request_id;
            $data['calibration_instrument'] = $service_info->item_type;
            $data['service_info'] = $service_info;
            if ($this->services_model->add_request_calibration($data)) {
                set_alert('success', 'New Service Calibration Report Added successfully');
                redirect(admin_url('services/report/edit/' . $code));
            }
        }

        if ($flag == 'edit' or $flag == 'view' or $flag == 'pdf') {
            if (empty($code)) { redirect(admin_url('services/requests')); }
            $service_info = $this->db->where(array('service_request_code' => $code))->get('tblservice_request')->row();
            $data['service_request_id'] = $service_info->service_request_id;
            $data['calibration_info'] = $this->db->where(array('service_request_id' => $data['service_request_id']))->get('tblservices_calibration')->row();
            $data['service_info'] = $service_info;
            $data['service_request_client'] = $this->clients_model->get($data['service_info']->clientid);
            $data['service_request_code'] = $code;
        } else {
            if (!is_numeric($flag)) { redirect(admin_url('services/requests')); }
            $data['service_info'] = $this->db->where(array('service_request_code' => $code))->get('tblservice_request')->row();
            $data['service_request_code'] = $code;
            $data['service_details'] = $this->db->select('tblservice_request_details.*, tblservices_module.name, tblservice_type.name as category_name, tblservice_type.service_typeid')->where('service_request_id', $data['service_info']->service_request_id)->join('tblservices_module', 'tblservices_module.serviceid = tblservice_request_details.serviceid')->join('tblservice_type', 'tblservices_module.service_type_code = tblservice_type.type_code')->get('tblservice_request_details')->result();
        }

        if ($flag == 'view') {
            $data['title'] = 'View Service Calibration Report';
            $this->load->view('admin/services/report_calibration_view', $data);
        } else if ($flag == 'pdf') {
            $data['title'] = 'Service Calibration Report PDF';
            $request_number = get_option('service_request_prefix') . $code;
            $qr_data = "This Service Report Registration No. REQ-{$code} is a valid report from Measurement Systems Ltd.";
            $this->load->library('ciqrcode');
            $params['data'] = $qr_data; $params['level'] = 'L'; $params['size'] = 2;
            $qr_image_path = FCPATH . 'modules/wonder_pdf_template/assets/images/' . $code . '_qrcode.png';
            $params['savename'] = $qr_image_path;
            $this->ciqrcode->generate($params);
            ob_start();
            try { $pdf = service_request_report_pdf($data); } catch (Exception $e) { log_message('error', $e->getMessage()); }
            $type = $this->input->get('print') ? 'I' : 'I';
            $pdf->Output('SERVICE REPORT ' . mb_strtoupper(slug_it($request_number)) . '.pdf', $type);
        } else if ($flag == 'edit') {
            $data['title'] = 'Edit Service Calibration Report';
            $this->load->view('admin/services/new_report', $data);
        } else {
            if (!in_array($data['service_info']->item_type, Services_Model::CALIBRATION_ITEMS)) {
                redirect(admin_url('services/requests'));
            }
            $data['title'] = 'Add New Service Calibration Report';
            $this->load->view('admin/services/new_report', $data);
        }
    }

    public function rental_agreements()
    {
        if (!has_permission(BIZIT_SERVICES_MSL . '_rental_agreement', '', 'view')) {
            access_denied(BIZIT_SERVICES_MSL . '_rental_agreement');
        }
        if ($this->input->is_ajax_request()) {
            $this->app->get_table_data(module_views_path(BIZIT_SERVICES_MSL, 'table/services_rental_agreements'));;
        }
        $data['title'] = _l('als_services_for_hire');
        $this->load->view('admin/services/manage_rental_agreements', $data);
    }

    public function new_rental_agreement($flag = null, $code = null)
    {
        if (!has_permission(BIZIT_SERVICES_MSL . '_rental_agreement', '', 'create')) {
            access_denied(BIZIT_SERVICES_MSL . '_rental_agreement');
        }
        if (!is_null($flag) and !is_numeric($flag)) {
            redirect(admin_url('services/rental_agreement'));
        }

        if (empty($flag)) {
            $random_number = rand(10000000, 99999);
            $q = $this->db->get('tblservice_rental_agreement')->result();
            if (!empty($q)) { $last = end($q); $random_number .= $last->service_rental_agreement_id; }
            $this->session->set_userdata(['service_rental_agreement_code' => $random_number]);
        }

        $data['all_services'] = $this->services_model->get_all_services('001');
        $data['all_services_filtered'] = $this->services_model->get_all_services('001', true);
        $data['currency_symbol'] = get_default_currency('symbol');
        
        if ($code != null) {
            $data['rental_agreement'] = $this->services_model->get_rental_agreement($code);
            $data['rental_agreement_details'] = $this->services_model->get_rental_agreement_details($data['rental_agreement']->service_rental_agreement_id);
            $data['field_report_info'] = $data['rental_agreement'];
            $data['uploaded_files'] = $this->services_model->get_rental_agreement_files($data['rental_agreement']->service_rental_agreement_id);
            $this->session->set_userdata(['service_rental_agreement_code' => $data['rental_agreement']->service_rental_agreement_code]);
            if (($data['rental_agreement']->status == 1 or $data['rental_agreement']->status == 2) or !empty($data['rental_agreement']->invoice_rel_id) and $data['rental_agreement']->invoice_rel_id > 0) {
                redirect(admin_url('services/new_rental_agreement'));
            }
        }

        $data['staff'] = get_staff_with_permission(BIZIT_SERVICES_MSL . '_rental_agreement_field_report', 'create');
        $data['title'] = _l('add_service_rental_agreement');
        $this->load->view('admin/services/service_rental_agreements_form', $data);
    }

    public function save_rental_agreement()
    {
        if (!has_permission(BIZIT_SERVICES_MSL . '_rental_agreement', '', 'create')) {
            access_denied(BIZIT_SERVICES_MSL . '_rental_agreement');
        }

        $data_service = [
            'service_rental_agreement_code' => $this->input->post('service_rental_agreement_code', true),
            'clientid' => $this->input->post('clientid', true),
            'start_date' => to_sql_date($this->input->post('start_date', true)),
            'end_date' => to_sql_date($this->input->post('end_date', true)),
            'rental_agreement_note' => strip_tags($this->input->post('rental_agreement_note', true)),
            'received_by' => get_staff_user_id(),
            'status' => 0,
            'field_operator' => $this->input->post('field_operator', true),
            'site_name' => $this->input->post('site_name', true),
        ];

        $uploaded_files = $this->handle_file_uploads();
        $data_service['report_files'] = json_encode($uploaded_files);

        $serviceid = $this->input->post('serviceid', true);
        $service_price = $this->input->post('service_price', true);
        $edit_id = $this->input->post('edit_id', true);
        $service_rental_agreement_details_id_edit = $this->input->post('service_rental_agreement_details_id', true);

        if (!empty($data_service['clientid'])) {
            if (empty($edit_id)) {
                $id = $this->services_model->add_rental_agreement($data_service);
                $data_service['service_rental_agreement_id'] = $id;
                set_alert('success', 'Rental agreement added');
            } else {
                $data_service['service_rental_agreement_id'] = $edit_id;
                unset($data_service['received_by']);
                $this->services_model->edit_rental_agreement($data_service);
                set_alert('success', 'Rental agreement updated');
            }

            for ($i = 0; $i < sizeof($serviceid); $i++) {
                if ($serviceid[$i] && $service_price[$i]) {
                    $detail = ['service_rental_agreement_id' => $data_service['service_rental_agreement_id'], 'serviceid' => $serviceid[$i], 'price' => $service_price[$i]];
                    if (empty($edit_id)) { $this->services_model->add_rental_agreement_details($detail); }
                    else {
                        if (!empty($service_rental_agreement_details_id_edit[$i])) { $this->services_model->edit_rental_agreement_details($detail, $service_rental_agreement_details_id_edit[$i]); }
                        else { $this->services_model->add_rental_agreement_details($detail); }
                    }
                }
            }
            redirect(admin_url('services/new_rental_agreement/1/' . $data_service['service_rental_agreement_code']));
        }
        redirect(admin_url('services/new_rental_agreement'));
    }

    public function view_rental_agreement($code = null, $view_reports = false)
    {
        if (!has_permission(BIZIT_SERVICES_MSL . '_rental_agreement', '', 'view')) {
            access_denied(BIZIT_SERVICES_MSL . '_rental_agreement');
        }

        $data['service_info'] = $this->db->where('service_rental_agreement_code', $code)->get('tblservice_rental_agreement')->row();
        if (empty($data['service_info'])) { show_404(); }

        if ($view_reports && $this->input->is_ajax_request()) {
            $this->app->get_table_data(module_views_path(BIZIT_SERVICES_MSL, 'table/services_field_report'), array('service_rental_agreement_id' => $data['service_info']->service_rental_agreement_id, 'service_rental_agreement_code' => $code)); exit;
        }

        $data['invoice_status'] = $this->db->select('status')->where('id', $data['service_info']->invoice_rel_id)->get('tblinvoices')->row();
        $data['reports_count'] = total_rows('tblfield_report', array('service_rental_agreement_id' => $data['service_info']->service_rental_agreement_id));
        
        $start = new DateTime($data['service_info']->start_date);
        $end = new DateTime($data['service_info']->end_date);
        $interval = date_diff($start, $end);
        $data['rental_days'] = $interval->format('%a') - $data['service_info']->discounted_days;
        $data['actual_rental_days'] = $interval->format('%a');

        $data['service_details'] = $this->db->select('tblservice_rental_agreement_details.*, tblservices_module.name, tblservices_module.rental_duration_check, tblservices_module.penalty_rental_price, tblservice_type.name as category_name, tblservice_type.service_typeid')->where('service_rental_agreement_id', $data['service_info']->service_rental_agreement_id)->join('tblservices_module', 'tblservices_module.serviceid = tblservice_rental_agreement_details.serviceid')->join('tblservice_type', 'tblservices_module.service_type_code = tblservice_type.type_code')->get('tblservice_rental_agreement_details')->result();
        
        $data['currency_symbol'] = get_default_currency('symbol');
        $data['service_rental_agreement_client'] = $this->clients_model->get($data['service_info']->clientid);
        $data['staff'] = get_staff_with_permission(BIZIT_SERVICES_MSL . '_rental_agreement_field_report', 'create');
        $data['title'] = 'Service Rental Agreement View';
        $this->load->view('admin/services/view_rental_agreement', $data);
    }

    public function field_report($flag = null, $code = null, $approval = false)
    {
        if (!has_permission(BIZIT_SERVICES_MSL . '_rental_agreement_field_report', '', 'view')) { access_denied(BIZIT_SERVICES_MSL . '_rental_agreement_field_report'); }

        if ($this->input->is_ajax_request() && isset($code)) {
            for ($index = 1; $index < 20; $index++) {
                if (total_rows('tblfield_report', ['report_code' => $code . '-' . $index]) == 0) { echo $code . '-' . $index; exit; }
            }
        } else {
            if (empty($flag) || empty($code)) { redirect(admin_url('services/rental_agreements')); }
            if ($flag == '1') {
                if ($this->services_model->add_field_report(['rental_agreement_code' => $code])) {
                    set_alert('success', 'Field report created successfully');
                    redirect(admin_url('services/field_report/edit/' . $code . '-1'));
                }
            }

            $data['field_report_info'] = $this->services_model->get_field_report($code);
            if (empty($data['field_report_info'])) { log_message('error', 'Field report not found'); redirect(admin_url('services/rental_agreements')); }

            $code_arr = explode('-', $code);
            $data['service_request_client'] = $this->clients_model->get($data['field_report_info']->clientid);
            $data['service_rental_agreement'] = $this->db->where('service_rental_agreement_code', $code_arr[0])->get('tblservice_rental_agreement')->row();
            $data['service_details'] = $this->db->select('tblservice_rental_agreement_details.*, tblservices_module.name, tblservices_module.rental_serial, tblservice_type.name as category_name, tblservice_type.service_typeid')->where('service_rental_agreement_id', $data['service_rental_agreement']->service_rental_agreement_id)->join('tblservices_module', 'tblservices_module.serviceid = tblservice_rental_agreement_details.serviceid')->join('tblservice_type', 'tblservices_module.service_type_code = tblservice_type.type_code')->get('tblservice_rental_agreement_details')->result();
            $data['service_info'] = $this->services_model->get_rental_agreement($code_arr[0]);

            if ($flag == 'view') {
                $data['title'] = 'View Service Field Report';
                $data['report_approval'] = ($approval && in_array(get_staff_user_id(), unserialize(get_option('field_report_approvers'))));
                $this->load->view('admin/services/field_report_view', $data);
            } else if ($flag == 'pdf') {
                $data['title'] = 'Service Calibration Report PDF';
                $request_number = get_option('service_rental_agreement_prefix') . $code;
                $data['service_rental_agreement'] = $this->db->where('service_rental_agreement_code', $code)->get('tblservice_rental_agreement')->row();
                $data['service_rental_agreement'] = $data['service_info']; 
                $data['service_rental_agreement_client'] = $data['service_request_client'];
                $data['service_rental_request_code'] = $code;

                $qr_data = "This Rental Agreement Report Registration No. ARG-{$code} is a valid report from Measurement Systems Ltd.";
                $this->load->library('ciqrcode');
                $params['data'] = $qr_data; $params['level'] = 'L'; $params['size'] = 2;
                $qr_image_path = FCPATH . 'modules/wonder_pdf_template/assets/images/' . $code . '_qrcode.png';
                $params['savename'] = $qr_image_path;
                $this->ciqrcode->generate($params);
                if (file_exists($qr_image_path)) {
                    $qr_code_base64 = base64_encode(file_get_contents($qr_image_path)); $data['qr_code_base64'] = $qr_code_base64;
                } else { $data['qr_code_base64'] = ''; }

                ob_start();
                try { $pdf = service_rental_agreement_pdf($data); } catch (Exception $e) { log_message('error', $e->getMessage()); }
                $type = $this->input->get('print') ? 'I' : 'I';
                $pdf->Output('RENTAL FIELD REPORT ' . mb_strtoupper(slug_it($request_number)) . '.pdf', $type);
            } else {
                $data['title'] = 'Edit Service Field Report ' . get_option('service_rental_agreement_prefix') . $code;
                $this->load->view('admin/services/new_field_report', $data);
            }
        }
    }

    public function manage_field_report()
    {
        if ($this->input->post()) {
            $data = $this->input->post();
            $uploaded_files = $this->handle_file_uploads(); // Reuse existing file upload logic
            $data['report_files'] = json_encode($uploaded_files);

            // ENHANCEMENT: Handle Submission Date/Time for Allowance Constraint
            $edit_id = $this->input->post('field_report_id');
            if ($edit_id) {
                $old_report = $this->services_model->get_field_report_by_id($edit_id);
                $new_status = isset($data['status']) ? (int)$data['status'] : (int)$old_report->status;

                // If status is moved to Submitted (Status 2) or Approved (Status 4), log it.
                if ($new_status >= 2 && (int)$old_report->status < 2) {
                    $data['submitted_by'] = get_staff_user_id();
                    $data['date_submitted'] = date('Y-m-d H:i:s');
                }
            }

            if (!isset($data['field_report_id'])) {
                $id = $this->services_model->add_field_report($data);
                if ($id) {
                    set_alert('success', _l('added_successfully', _l('field_report')));
                    redirect(admin_url('services/field_report/edit/' . $data['report_code']), 'refresh');
                }
            } else {
                $success = $this->services_model->edit_field_report($data);
                if ($success) {
                    set_alert('success', _l('updated_successfully', _l('field_report')));
                    redirect(admin_url('services/field_report/edit/' . $data['report_code']), 'refresh');
                }
            }
        }
    }

    public function save_calibration() {
        // This function handles the massive form for calibration instruments.
        // To reduce redundancy (originally 500+ lines), we accept the raw post array for the dynamic fields.
        
        if (!has_permission(BIZIT_SERVICES_MSL, '', 'create') && !has_permission(BIZIT_SERVICES_MSL, '', 'edit')) {
			access_denied('Services');
		}

        $calibration_instrument = $this->input->post('calibration_instrument', true);
        $data = $this->input->post(); // Capture all inputs
        
        // Ensure DMS conversion happens if the fields exist (Total Station/Theodolite)
        if ($calibration_instrument == 'Total Station' || $calibration_instrument == 'Theodolite') {
             $dms_fields = ['i_h_a', 'i_h_b', 'ii_h_a', 'ii_h_b', 'i_v_a', 'i_v_b', 'ii_v_a', 'ii_v_b', 'th_h_a', 'th_h_b', 'thh_h_a', 'thh_h_b', 'th_v_a', 'th_v_b', 'thh_v_a', 'thh_v_b', 'thh_v_c'];
             foreach($dms_fields as $f) {
                 if(isset($data[$f]) && is_array($data[$f])) {
                     // Using helper function dms2dec
                     $data[$f] = dms2dec($data[$f][0], $data[$f][1], $data[$f][2]);
                 }
             }
        }
        
        // GNSS Date Parsing (Handling start/stop times)
        if ($calibration_instrument == 'GNSS') {
             $current_date = date('Y-m-d');
             for($i=1; $i<=6; $i++){
                 if(isset($data["start_time_$i"])) $data["start_time_$i"] = $current_date . ' ' . date('H:i:s', strtotime($data["start_time_$i"]));
                 if(isset($data["stop_time_$i"])) $data["stop_time_$i"] = $current_date . ' ' . date('H:i:s', strtotime($data["stop_time_$i"]));
             }
        }

        $service_code = $this->input->post('service_code', true);
		$edit_id = $this->input->post('edit_id', true);

        if (!empty($service_code)) {
			$service_info = $this->db->where('service_request_code', $service_code)->get('tblservice_request')->row();
			$data['service_request_id'] = $service_info->service_request_id;
		}

        $service_calibration = false;
		if (empty($edit_id)) {
			$service_calibration = $this->services_model->add_request_calibration($data);
		} else {
			$service_calibration = $this->services_model->edit_request_calibration($data, $edit_id);
		}

		if (!$this->input->post('autosave')) {
			set_alert('success', 'Calibration report saved.');
			redirect(admin_url('services/report/edit/' . $service_code));
		} else {
			echo json_encode(['success' => true]);
		}
    }
    
    // ==================================================
    // MISC UTILITY & LEGACY FUNCTIONS (Restored)
    // ==================================================

    public function gpsDetails() { 
        $data['gps_details'] = $this->services_model->get_gps_details(); // Note: Ensure model has this if used
        $this->load->view('admin/services/gps_details', $data); 
    }
    
    public function form() { $this->load->view('admin/services/gps_data_form'); }
    
    public function insert_data() { 
        // Placeholder for legacy logic if needed, originally validated form and inserted data
        $this->load->view('admin/services/form_success');
    }
    
    public function request_pdf($code = null) {
        if (empty($code)) redirect(admin_url('services/requests'));
        $data['service_request'] = $this->services_model->get_request($code);
        $data['service_details'] = $this->services_model->get_request_details($data['service_request']->service_request_id);
        $data['service_request_client'] = $this->clients_model->get($data['service_request']->clientid);
        $data['existing_accessories'] = $this->services_model->get_request_accessories($data['service_request']->service_request_id);
        
        // Populate inspection/checklist data for PDF
        $data['checklist_items'] = $this->services_model->get_checklist_data($data['service_request']->service_request_id);
        $data['released_info'] = $this->services_model->get_collection_data($data['service_request']->service_request_id);
        $inspection_items = $this->services_model->get_inspection_data($data['service_request']->service_request_id);
        $data['pre_inspection_items'] = []; $data['post_inspection_items'] = [];
        foreach ($inspection_items as $item) {
            if ($item['inspection_type'] == 'pre_inspection') $data['pre_inspection_items'][] = (object)$item;
            elseif ($item['inspection_type'] == 'post_inspection') $data['post_inspection_items'][] = (object)$item;
        }
        // Dropped off/Received info
        $info = $data['service_request'];
        $data['dropped_off_by'] = $info->dropped_off_by; $data['dropped_off_date'] = $info->dropped_off_date;
        $data['dropped_off_signature'] = $info->dropped_off_signature; $data['dropped_off_id_number'] = $info->dropped_off_id_number;
        $data['received_by'] = $info->req_received_by; $data['received_date'] = $info->received_date;
        $data['received_signature'] = $info->received_signature; $data['received_id_number'] = $info->received_id_number;

        try { $pdf = service_request_pdf($data); } catch(Exception $e) { echo $e->getMessage(); die; }
        $pdf->Output('REQUEST.pdf', 'I');
    }

    public function certificate_pdf($code = null) {
         $data['service_request'] = $this->services_model->get_request($code);
         // Generate QR code for certificate
         $qr_data = site_url('service/certificate/validate/' . $data['service_request']->service_request_code);
         $data['qr_code_base64'] = $this->generate($qr_data);
         
         $html = $this->load->view('admin/services/html_certificate', $data, true);
         $this->dpdf->pdf_create($html, 'CERTIFICATE', 'view');
    }
    
    public function generate($data) {
		$this->load->library('ciqrcode');
		$params['data'] = $data; $params['level'] = 'L'; $params['size'] = 2;
		$qr_image_path = FCPATH . 'modules/bizit_services_msl/assets/images/' . uniqid() . '.png';
		$params['savename'] = $qr_image_path;
		$this->ciqrcode->generate($params);
		$qr_image_base64 = base64_encode(file_get_contents($qr_image_path));
		unlink($qr_image_path);
		return $qr_image_base64;
	}

    public function return_rental($code, $invoiceid) {
         if (!has_permission(BIZIT_SERVICES_MSL . '_rental_agreement', '', 'edit')) access_denied('Services');
         $data_service['extra_days'] = $this->input->post('extra_days', true);
         $data_service['discounted_days'] = $this->input->post('discounted_days', true);
         $data_service['actual_date_returned'] = to_sql_date($this->input->post('actual_date_returned', true));
         $this->db->where('service_rental_agreement_code', $code)->update('tblservice_rental_agreement', $data_service);
         
         // Update Status to Not-Hired for items
         $s_ids = $this->db->select('tblservice_rental_agreement_details.serviceid')->join('tblservice_rental_agreement', 'tblservice_rental_agreement.service_rental_agreement_id = tblservice_rental_agreement_details.service_rental_agreement_id')->where('tblservice_rental_agreement.invoice_rel_id', $invoiceid)->get('tblservice_rental_agreement_details')->result();
         foreach($s_ids as $sid) { $this->db->where('serviceid', $sid->serviceid)->update('tblservices_module', ['rental_status' => 'Not-Hired']); }

         set_alert('success', 'Rental returned.');
         redirect(admin_url('services/view_rental_agreement/' . $code));
    }
    
    public function rental_agreement_invoice_generation($code = null, $invoiceid = null) {
        $this->load->model('invoices_model');
        if (!has_permission(BIZIT_SERVICES_MSL.'_rental_agreement', '', 'edit')) access_denied('Services');
        $service_rental_agreement = $this->db->where('service_rental_agreement_code', $code)->get('tblservice_rental_agreement')->row();
        $service_details = $this->services_model->get_rental_agreement_details($service_rental_agreement->service_rental_agreement_id);
        
        // Calculation Logic
        $start = new DateTime($service_rental_agreement->start_date);
        $end = new DateTime($service_rental_agreement->end_date);
        $rental_days = $end->diff($start)->format('%a') - $service_rental_agreement->discounted_days;
        
        $newitems = []; $subtotal = 0; $i = 1;
        foreach ($service_details as $val) {
            $newitems[$i++] = [ "order" => $i, "description" => "Rental Item", "long_description" => "Rental Code: ".$code, "qty" => $rental_days, "unit" => "Days", "rate" => $val->price, "taxable" => 1 ];
            $subtotal += ($val->price * $rental_days);
        }
        
        // Create Invoice
        $client = $this->clients_model->get($service_rental_agreement->clientid);
        $invoice_data = [
            "clientid" => $client->userid, 
            "date" => _d(date('Y-m-d')), 
            "currency" => get_default_currency('id'), 
            "subtotal" => $subtotal, 
            "total" => $subtotal, 
            "newitems" => $newitems,
            "billing_street" => $client->billing_street, 
            "billing_city" => $client->billing_city,
            "save_as_draft" => "true",
            "allowed_payment_modes" => $this->get_default_payment_modes()
        ];
        
        if(empty($invoiceid)){
            $id = $this->invoices_model->add($invoice_data);
            if ($id) {
                $this->db->where('service_rental_agreement_id', $service_rental_agreement->service_rental_agreement_id)->update('tblservice_rental_agreement', ['invoice_rel_id' => $id]);
                set_alert('success', 'Invoice generated');
                redirect(admin_url('services/view_rental_agreement/' . $code));
            }
        }
    }

    private function get_default_payment_modes() {
		$this->load->model('payment_modes_model');
		$payment_modes = $this->payment_modes_model->get('', ['expenses_only !=' => 1]);
		$allowed = [];
		foreach ($payment_modes as $mode) {
			if ($mode['selected_by_default'] == 1) $allowed[] = $mode['id'];
		}
		return $allowed;
	}
    
    public function delete_service_rental_agreement_price($id, $code) {
        if (!has_permission(BIZIT_SERVICES_MSL . '_rental_agreement', '', 'edit')) access_denied('Services');
        $this->services_model->delete_rental_agreement_details($id);
        set_alert('success', 'Service deleted');
        redirect(admin_url('services/new_rental_agreement/1/' . $code));
    }
    
    public function update_status() {
        $id = $this->input->post('service_rental_agreement_id'); $status = $this->input->post('status');
        if($id && $status) {
            $this->db->where('service_rental_agreement_id', $id)->update('tblservice_rental_agreement', ['status' => $status]);
            echo json_encode(['status' => 'success']);
        }
    }

    public function manage_field_report_appr_rej() {
         if ($this->input->post()) {
            $data = $this->input->post();
            if (isset($data['aprv_rej']) && $data['aprv_rej'] == "1") {
                $data['approved_by'] = get_staff_user_id(); $data['status'] = 4;
            } else {
                $data['rejected_by'] = get_staff_user_id(); $data['status'] = 3;
            }
            unset($data['aprv_rej'], $data['aprv_rej_remark']);
            $this->services_model->edit_field_report($data);
            set_alert('success', 'Report status updated');
            redirect(admin_url('services/field_report/view/' . $data['report_code']));
         }
    }
    
    public function rental_calendar() {
        $data['rental_details'] = $this->services_model->get_calendar_rental_details();
        $this->load->view('admin/services/rental_agreements_calendar', $data);
    }
    
    public function rental_agreement_pdf($code = null) {
         $data['service_rental_agreement'] = $this->db->where('service_rental_agreement_code', $code)->get('tblservice_rental_agreement')->row();
         $data['service_details'] = $this->services_model->get_rental_agreement_details($data['service_rental_agreement']->service_rental_agreement_id);
         $data['service_rental_agreement_client'] = $this->clients_model->get($data['service_rental_agreement']->clientid);
         
         try { $pdf = service_rental_agreement_pdf($data); } catch(Exception $e) { echo $e->getMessage(); die; }
         $pdf->Output('AGREEMENT.pdf', 'I');
    }
    
    public function upload_file($type, $type_id) {
        $data['report_code'] = get_field_value('tblfield_report', array('field_report_id' => $type_id), 'report_code');
		$data['field_report_info'] = $this->services_model->get_field_report($data['report_code']);
        $this->load->view('admin/services/report_files', $data);
    }
    
    public function manage_files($type, $type_id) {
        $this->app->get_table_data(module_views_path(BIZIT_SERVICES_MSL, 'table/services_report_files'), array('type' => $type, 'type_id' => $type_id));
    }
    
    // Helper method for field operator assignment
    public function service_rental_agreement_reasign_field_operator() {
		if (!has_permission(BIZIT_SERVICES_MSL . '_rental_agreement', '', 'edit')) access_denied(BIZIT_SERVICES_MSL . '_rental_agreement');
		$service_rental_agreement_id = $this->input->post('service_rental_agreement_id', true);
		$field_operator_id = $this->input->post('field_operator', true);
		$data = ['service_rental_agreement_id' => $service_rental_agreement_id, 'field_operator' => $field_operator_id];
		$this->services_model->edit_rental_agreement($data);
		redirect(admin_url('services/rental_agreements'));
    }
    
    public function service_rental_agreement_re_confirmation() {
		if (!has_permission(BIZIT_SERVICES_MSL . '_rental_agreement', '', 'edit')) access_denied(BIZIT_SERVICES_MSL . '_rental_agreement');
		$data['status'] = $this->input->post('status', true);
		$id = $this->input->post('service_rental_agreement_id', true);
		$this->db->where('service_rental_agreement_id', $id)->update('tblservice_rental_agreement', $data);
		redirect(admin_url('services/rental_agreements'));
    }
    
    public function delivery_note($id) {
        if (!has_permission('invoices', '', 'view') && !has_permission('invoices', '', 'view_own')) access_denied('invoices');
        $invoice = $this->invoices_model->get($id);
        $invoice_number = format_invoice_number($invoice->id);
        $client_details = $this->clients_model->get($invoice->clientid);
        $items_data = $this->services_model->get_table_products_bulk($id);
        $custom_fields = get_custom_fields('customers', ['show_on_pdf' => 1]);
        $client_custom_fields = [];
        foreach ($custom_fields as $field) {
            $value = get_custom_field_value($invoice->clientid, $field['id'], 'customers');
            if ($value) $client_custom_fields[$field['name']] = $value;
        }
        try {
            $pdf = delivery_note_pdf(['invoice' => $invoice, 'invoice_number' => $invoice_number, 'status' => $invoice->status, 'client' => $client_details, 'client_custom_fields' => $client_custom_fields, 'items_data' => $items_data]);
        } catch (Exception $e) { echo $e->getMessage(); die; }
        $pdf->Output(_l('delivery_note') . mb_strtoupper(slug_it($invoice_number)) . '.pdf', 'I');
    }

    public function inventory_checklist($id) {
        if (!has_permission('invoices', '', 'view') && !has_permission('invoices', '', 'view_own')) access_denied('invoices');
        $invoice = $this->invoices_model->get($id);
        $invoice_number = format_invoice_number($invoice->id);
        $client_details = $this->clients_model->get($invoice->clientid);
        $items_data = $this->services_model->get_table_products_bulk($id);
        $custom_fields = get_custom_fields('customers', ['show_on_pdf' => 1]);
        $client_custom_fields = [];
        foreach ($custom_fields as $field) {
            $value = get_custom_field_value($invoice->clientid, $field['id'], 'customers');
            if ($value) $client_custom_fields[$field['name']] = $value;
        }
        try {
            $pdf = inventory_checklist_pdf(['invoice' => $invoice, 'invoice_number' => $invoice_number, 'status' => $invoice->status, 'client' => $client_details, 'client_custom_fields' => $client_custom_fields, 'items_data' => $items_data]);
        } catch (Exception $e) { echo $e->getMessage(); die; }
        $pdf->Output(_l('inventory_checklist') . mb_strtoupper(slug_it($invoice_number)) . '.pdf', 'I');
    }
    
    public function change_assignee($invoiceid) {
		$data = array();
		if (is_admin()) {
			$data['addedfrom'] = $this->input->post('addedfrom');
			$this->db->where('id', $invoiceid)->update('tblinvoices', $data);
			redirect(admin_url('invoices/list_invoices#' . $invoiceid));
		}
		return true;
	}

	public function inventory_qty_check($product_id = 0, $qty = 0) {
		if ($this->input->is_ajax_request()) {
			echo $this->invoices_model->inventory_qty_check($product_id, $qty);
		}
	}
    
    public function test2($value = '') {
		$bulk_serial = 'bs-2323122';
		echo substr($bulk_serial, 3);
	}
    
    public function test($id) {
		$data = array();
		$this->load->view('admin/services/html_certificate', $data);
	}

    // Fallback for removed warranty features
    public function view_warranty(){ show_404(); }
    public function warranty(){ show_404(); }
    public function delete_warranty(){ show_404(); }
    public function collect_produts(){ show_404(); }
    public function warranty_pdf(){ show_404(); }
}