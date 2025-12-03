<?php defined('BASEPATH') or exit('No direct script access allowed');

class Services extends AdminController
{
    public function __construct() {
        parent::__construct();
        $this->load->model([
            'bizit_services_msl/services_core_model', 'bizit_services_msl/requests_model',
            'bizit_services_msl/rentals_model', 'bizit_services_msl/reports_model',
            'bizit_services_msl/compensation_model', 'clients_model', 'invoices_model', 'staff_model'
        ]);
        $this->load->helper(['bizit_services_msl', 'invoices', 'number']);
        $this->load->library(['ciqrcode', 'bizit_services_msl/Dpdf']);
    }

    public function getNextServiceCode($type_code) { if (!$this->input->is_ajax_request()) show_404(); $count = $this->db->where('service_type_code', $type_code)->count_all_results('tblservices_module'); echo sprintf("%s-%04d", $type_code, $count + 1); }
    public function get_services($type_code) { if (!$this->input->is_ajax_request()) show_404(); echo json_encode($this->services_core_model->get_all_services($type_code)); }
    public function get_service_by_code($code) { if (!$this->input->is_ajax_request()) show_404(); $service = $this->services_core_model->get_service_by_code($code); if($service) { $cat = $this->db->where('type_code', $service->service_type_code)->get('tblservice_type')->row(); $service->category_name = $cat ? $cat->name : ''; } echo json_encode($service); }

    public function index() {
        if (!has_permission(BIZIT_SERVICES_MSL, '', 'view') && !has_permission(BIZIT_SERVICES_MSL, '', 'view_own')) access_denied('Services');
        if ($this->input->is_ajax_request()) $this->app->get_table_data(module_views_path(BIZIT_SERVICES_MSL, 'table/services'));
        $data['currency_symbol'] = get_default_currency('symbol');
        $data['service_categories'] = $this->services_core_model->get_all_services_category_info();
        $data['title'] = _l('services');
        $this->load->view('admin/services/manage', $data);
    }
    
    public function manage() {
        if (has_permission(BIZIT_SERVICES_MSL, '', 'view') || has_permission(BIZIT_SERVICES_MSL, '', 'view_own')) {
            if ($this->input->post()) {
                $data = $this->input->post();
                if (empty($data['serviceid'])) {
                    if (!has_permission(BIZIT_SERVICES_MSL, '', 'create')) access_denied('Create Service');
                    $id = $this->services_core_model->add($data);
                    echo json_encode(['success' => (bool)$id, 'message' => $id ? _l('added_successfully', _l('service')) : 'Error']);
                } else {
                    if (!has_permission(BIZIT_SERVICES_MSL, '', 'edit')) access_denied('Edit Service');
                    $success = $this->services_core_model->edit($data);
                    echo json_encode(['success' => $success, 'message' => $success ? _l('updated_successfully', _l('service')) : 'No changes']);
                }
            }
        }
    }

    public function delete($id) {
        if (!has_permission(BIZIT_SERVICES_MSL, '', 'delete')) access_denied('Delete Service');
        $response = $this->services_core_model->delete($id);
        set_alert($response ? 'success' : 'warning', $response ? _l('deleted', _l('service')) : _l('problem_deleting', _l('service')));
        redirect(admin_url('services'));
    }

    public function delete_category($id) {
        if (!has_permission(BIZIT_SERVICES_MSL, '', 'delete')) access_denied('Delete Category');
        if (!$id) redirect(admin_url('services'));
        $response = $this->services_core_model->delete_category($id);
        if (is_array($response) && isset($response['referenced'])) { set_alert('warning', _l('is_referenced', _l('product_lowercase') . ' category')); } 
        elseif ($response == true) { set_alert('success', _l('deleted', _l('product') . ' category')); } 
        else { set_alert('warning', _l('problem_deleting', _l('product_lowercase') . ' category')); }
        redirect(admin_url('services'));
    }

    public function category_manage() {
        if (has_permission(BIZIT_SERVICES_MSL, '', 'view') || has_permission(BIZIT_SERVICES_MSL, '', 'view_own')) {
            if ($this->input->post()) {
                $data = $this->input->post();
                if (empty($data['type_code'])) { $count = $this->db->count_all('tblservice_type'); $data['type_code'] = sprintf("%03d", $count + 1); }
                if (empty($data['service_typeid'])) {
                    if (!has_permission(BIZIT_SERVICES_MSL, '', 'create')) access_denied('Create Category');
                    $id = $this->services_core_model->add_category($data);
                    echo json_encode(['success' => (bool)$id, 'message' => _l('added_successfully', _l('service_type'))]);
                } else {
                    if (!has_permission(BIZIT_SERVICES_MSL, '', 'edit')) access_denied('Edit Category');
                    $success = $this->services_core_model->edit_category($data);
                    echo json_encode(['success' => $success, 'message' => _l('updated_successfully', _l('service_type'))]);
                }
            } else { $this->load->view('admin/services/modal_category'); }
        }
    }

    public function sales_list() {
        if ($this->input->is_ajax_request()) $this->app->get_table_data(module_views_path(BIZIT_SERVICES_MSL, 'table/services_sales_list'));
        $this->load->view('admin/services/manage_sales_list', ['title' => _l('als_services_sales_list')]);
    }

    public function requests() {
        if (!has_permission(BIZIT_SERVICES_MSL, '', 'view') && !has_permission(BIZIT_SERVICES_MSL, '', 'view_own')) access_denied('Services Requests');
        if ($this->input->is_ajax_request()) $this->app->get_table_data(module_views_path(BIZIT_SERVICES_MSL, 'table/services_requests'));
        $this->load->view('admin/services/manage_requests', ['title' => _l('als_services_requests')]);
    }

    public function new_request($flag = null, $code = null) {
        if (!$code && !has_permission(BIZIT_SERVICES_MSL, '', 'create')) access_denied('Create Service Request');
        if ($code && !has_permission(BIZIT_SERVICES_MSL, '', 'edit')) {
             if(has_permission(BIZIT_SERVICES_MSL, '', 'view') || has_permission(BIZIT_SERVICES_MSL, '', 'view_own')){ redirect(admin_url('services/view_request/'.$code)); }
             access_denied('Edit Service Request');
        }
        if (empty($flag)) $this->session->set_userdata(['service_request_code' => rand(10000, 99999)]);
        $data['all_services'] = $this->services_core_model->get_all_services('002');
        $data['all_services_accessories'] = $this->services_core_model->get_all_services_accessories();
        $data['currency_symbol'] = get_default_currency('symbol');

        if ($code) {
            $data['request'] = $this->requests_model->get_request($code);
            if(!$data['request']) show_404();
            if (!has_permission(BIZIT_SERVICES_MSL, '', 'view') && has_permission(BIZIT_SERVICES_MSL, '', 'view_own')) {
                if ($data['request']->received_by != get_staff_user_id()) access_denied('View Global Service Request');
            }
            $data['request_details'] = $this->requests_model->get_request_details($data['request']->service_request_id);
            $data['service_request_client'] = $this->clients_model->get($data['request']->clientid);
            $data['existing_accessories'] = $this->requests_model->get_request_accessories($data['request']->service_request_id);
            $data['checklist_items'] = $this->requests_model->get_checklist_data($data['request']->service_request_id);
            $data['uploaded_files'] = $this->requests_model->get_service_files($data['request']->service_request_id);
            $data['service_file_info'] = $data['request'];
            foreach(['dropped_off_by','dropped_off_date','dropped_off_signature','dropped_off_id_number','req_received_by','received_date','received_signature','received_id_number'] as $f) { $data[$f] = $data['request']->$f; }
            $inspections = $this->requests_model->get_inspection_data($data['request']->service_request_id);
            $data['pre_inspection_items'] = []; $data['post_inspection_items'] = [];
            foreach ($inspections as $i) {
                if ($i['inspection_type'] == 'pre_inspection') $data['pre_inspection_items'][] = (object)$i;
                else $data['post_inspection_items'][] = (object)$i;
            }
        }
        $this->load->view('admin/services/service_requests_form', $data);
    }

    public function save_request() {
        if (!has_permission(BIZIT_SERVICES_MSL, '', 'create') && !has_permission(BIZIT_SERVICES_MSL, '', 'edit')) access_denied('Save Service Request');
        $data = $this->input->post();
        $data['report_files'] = json_encode($this->handle_file_uploads());
        if (empty($data['service_review_token'])) $data['service_review_token'] = md5(uniqid(rand(), true));
        $id = isset($data['edit_id']) && $data['edit_id'] ? $this->requests_model->edit_request($data) : $this->requests_model->add_request($data);
        $request_id = isset($data['edit_id']) ? $data['edit_id'] : $id;
        if (isset($data['serviceid'])) {
            for ($i = 0; $i < count($data['serviceid']); $i++) {
                if ($data['serviceid'][$i]) {
                    $det = ['service_request_id' => $request_id, 'serviceid' => $data['serviceid'][$i], 'price' => $data['service_price'][$i]];
                    $this->requests_model->add_request_details($det);
                }
            }
        }
        $submitted_accessories = isset($data['accessoryserviceid']) ? $data['accessoryserviceid'] : [];
        $submitted_prices = isset($data['accessoryservice_price']) ? $data['accessoryservice_price'] : [];
        $current_accessories = $this->requests_model->get_request_accessories($request_id);
        $current_ids = array_column($current_accessories, 'accessory_id');
        if(!empty($submitted_accessories)){
            foreach($submitted_accessories as $k => $acc_id){
                if(!in_array($acc_id, $current_ids)){
                    $this->db->insert('tblservice_request_accessories', ['service_request_id' => $request_id, 'accessory_id' => $acc_id, 'price' => $submitted_prices[$k]]);
                }
            }
        }
        set_alert('success', 'Request Saved');
        redirect(admin_url('services/view_request/' . $data['service_request_code']));
    }

    public function delete_service_price($id, $code) {
        if (!has_permission(BIZIT_SERVICES_MSL, '', 'edit')) access_denied('Edit Service Request');
        $deleted = $this->requests_model->delete_request_details($id);
        if ($deleted) set_alert('success', 'Successfully deleted service item');
        else set_alert('warning', 'Failed to delete service item');
        redirect(admin_url('services/new_request/1/' . $code));
    }

    public function view_request($code) {
        if (!has_permission(BIZIT_SERVICES_MSL, '', 'view') && !has_permission(BIZIT_SERVICES_MSL, '', 'view_own')) access_denied('View Service Request');
        $data['service_info'] = $this->requests_model->get_request($code);
        if(!$data['service_info']) show_404();
        if (!has_permission(BIZIT_SERVICES_MSL, '', 'view') && has_permission(BIZIT_SERVICES_MSL, '', 'view_own')) {
            if ($data['service_info']->received_by != get_staff_user_id()) access_denied('Access Restricted to Own Requests');
        }
        $data['service_details'] = $this->db->select('d.*, m.name, t.name as category_name')->from('tblservice_request_details d')->join('tblservices_module m', 'm.serviceid=d.serviceid')->join('tblservice_type t', 'm.service_type_code = t.type_code', 'left')->where('d.service_request_id', $data['service_info']->service_request_id)->get()->result();
        $data['service_request_client'] = $this->clients_model->get($data['service_info']->clientid);
        $data['existing_accessories'] = $this->requests_model->get_request_accessories($data['service_info']->service_request_id);
        $data['checklist_items'] = $this->requests_model->get_checklist_data($data['service_info']->service_request_id);
        $data['released_info'] = $this->db->where('service_request_id', $data['service_info']->service_request_id)->get('tblcollection1')->row();
        foreach(['dropped_off_by','dropped_off_date','req_received_by','received_date','received_signature'] as $f) { $data[$f] = $data['service_info']->$f ?? 'N/A'; }
        $this->load->view('admin/services/view_request', $data);
    }

    public function service_re_confirmation() {
        if (!has_permission(BIZIT_SERVICES_MSL, '', 'edit')) access_denied('Edit Service Status');
        $data['status'] = $this->input->post('status', true);
        $id = $this->input->post('service_request_id', true);
        $code = $this->input->post('service_request_code', true);
        $this->db->where('service_request_id', $id)->update('tblservice_request', ['status' => $data['status']]);
        set_alert('success', 'Status updated successfully');
        redirect(admin_url('services/view_request/' . $code));
    }

    public function delete_accessory() {
        if (!has_permission(BIZIT_SERVICES_MSL, '', 'edit')) { echo json_encode(['success' => false]); exit; }
        $id = $this->input->post('id', true);
        if ($id) {
            $this->db->where('id', $id);
            $deleted = $this->db->delete('tblservice_request_accessories');
            echo json_encode(['success' => $deleted, 'message' => $deleted ? 'Accessory deleted' : 'Failed']);
        } else echo json_encode(['success' => false, 'message' => 'Invalid ID']);
    }

    public function report($flag = null, $code = null) {
        if (empty($flag)) redirect(admin_url('services/requests'));
        $service_info = $this->requests_model->get_request($code);
        $data['service_request_id'] = $service_info->service_request_id;
        $data['service_info'] = $service_info;
        if ($flag == '1') {
            $this->requests_model->add_request_calibration(['service_request_id' => $service_info->service_request_id]);
            redirect(admin_url('services/report/edit/' . $code));
        }
        $data['calibration_info'] = $this->requests_model->get_report_check($service_info->service_request_id);
        $data['service_request_client'] = $this->clients_model->get($service_info->clientid);
        $data['service_request_code'] = $code;
        $data['service_details'] = $this->requests_model->get_request_details($service_info->service_request_id);
        if ($flag == 'pdf') {
            $this->load->library('ciqrcode');
            $params['data'] = site_url('service/certificate/validate/' . $code);
            $params['savename'] = FCPATH . 'uploads/temp/' . $code . '_qr.png';
            if(!is_dir(FCPATH.'uploads/temp/')) mkdir(FCPATH.'uploads/temp/', 0755);
            $this->ciqrcode->generate($params);
            $data['qr_code_base64'] = base64_encode(file_get_contents($params['savename']));
            unlink($params['savename']);
            $pdf = service_request_report_pdf($data);
            $pdf->Output('REPORT_' . $code . '.pdf', 'I');
        } elseif ($flag == 'edit') {
            $data['title'] = 'Edit Report';
            $this->load->view('admin/services/new_report', $data);
        } else {
            $this->load->view('admin/services/report_calibration_view', $data);
        }
    }

    public function save_calibration() {
        $data = $this->input->post();
        if (in_array($data['calibration_instrument'], ['Total Station', 'Theodolite'])) {
            foreach (['i_h_a', 'i_h_b', 'ii_h_a', 'ii_h_b', 'i_v_a', 'i_v_b'] as $f) {
                if (isset($data[$f]) && is_array($data[$f])) $data[$f] = dms2dec($data[$f][0], $data[$f][1], $data[$f][2]);
            }
        }
        if (!empty($data['edit_id'])) {
            $this->requests_model->edit_request_calibration($data, $data['edit_id']);
        } else {
            $this->requests_model->add_request_calibration($data);
        }
        set_alert('success', 'Calibration Saved');
        redirect(admin_url('services/report/edit/' . $data['service_code']));
    }

    public function request_invoice_generation($code = null) {
        if (!has_permission(BIZIT_SERVICES_MSL, '', 'create')) access_denied('invoices');
        $service_request = $this->db->where('service_request_code', $code)->get('tblservice_request')->row();
        $service_details = $this->db->select('d.*, m.name, m.service_code, t.name as category_name')->from('tblservice_request_details d')->join('tblservices_module m', 'm.serviceid = d.serviceid')->join('tblservice_type t', 'm.service_type_code = t.type_code', 'left')->where('d.service_request_id', $service_request->service_request_id)->get()->result();
        $accessories = $this->db->select('a.*, i.commodity_name, i.unit')->from('tblservice_request_accessories a')->join('tblitems i', 'i.id = a.accessory_id')->where('a.service_request_id', $service_request->service_request_id)->get()->result();
        $newitems = []; $i = 1; $subtotal = 0;
        foreach ($service_details as $val) {
            $newitems[$i] = ["order" => $i, "description" => $val->name, "long_description" => $val->category_name . ' (' . $val->service_code . ') - Make: ' . $service_request->item_make . ' Model: ' . $service_request->item_model, "qty" => 1, "unit" => "Unit", "rate" => $val->price, "taxable" => 1];
            $subtotal += $val->price; $i++;
        }
        foreach ($accessories as $acc) {
            $newitems[$i] = ["order" => $i, "description" => $acc->commodity_name, "long_description" => "Accessory", "qty" => 1, "unit" => $acc->unit, "rate" => $acc->price, "taxable" => 1];
            $subtotal += $acc->price; $i++;
        }
        $client = $this->clients_model->get($service_request->clientid);
        $inv_data = ["clientid" => $client->userid, "date" => _d(date('Y-m-d')), "currency" => get_default_currency('id'), "subtotal" => $subtotal, "total" => $subtotal, "newitems" => $newitems, "save_as_draft" => "true", "number" => str_pad(get_option('next_invoice_number'), get_option('number_padding_prefixes'), '0', STR_PAD_LEFT)];
        $id = $this->invoices_model->add($inv_data);
        if ($id) {
            $this->db->where('service_request_id', $service_request->service_request_id)->update('tblservice_request', ['invoice_rel_id' => $id]);
            set_alert('success', _l('added_successfully', _l('invoice')));
            redirect(admin_url('services/view_request/' . $code));
        }
    }

    public function certificate_pdf($code = null) {
        if (!has_permission(BIZIT_SERVICES_MSL, '', 'view')) access_denied('Services');
        if (empty($code)) redirect(admin_url('services/requests'));
        $service_request = $this->db->where('service_request_code', $code)->get('tblservice_request')->row();
        if (!$service_request) show_error('Service request not found.');
        $qr_data = site_url('service/certificate/validate/' . $service_request->service_request_code);
        $this->load->library('ciqrcode');
        $params['data'] = $qr_data; $params['level'] = 'L'; $params['size'] = 2;
        $qr_image_path = FCPATH . 'uploads/temp/' . uniqid() . '.png';
        $params['savename'] = $qr_image_path;
        if(!is_dir(FCPATH.'uploads/temp/')) mkdir(FCPATH.'uploads/temp/', 0755);
        $this->ciqrcode->generate($params);
        $data['qr_code_base64'] = base64_encode(file_get_contents($qr_image_path));
        unlink($qr_image_path);
        $data['service_request'] = $service_request;
        $html = $this->load->view('admin/services/html_certificate', $data, true);
        $this->dpdf->pdf_create($html, 'CERTIFICATE-' . $code, 'view');
    }

    public function rental_agreements() {
        if (!has_permission(BIZIT_SERVICES_MSL . '_rental_agreement', '', 'view') && !has_permission(BIZIT_SERVICES_MSL . '_rental_agreement', '', 'view_own')) access_denied('Rental Agreements');
        if ($this->input->is_ajax_request()) $this->app->get_table_data(module_views_path(BIZIT_SERVICES_MSL, 'table/services_rental_agreements'));
        $this->load->view('admin/services/manage_rental_agreements', ['title' => _l('als_services_for_hire')]);
    }

    public function new_rental_agreement($flag = null, $code = null) {
        if (!$code && !has_permission(BIZIT_SERVICES_MSL . '_rental_agreement', '', 'create')) access_denied('Create Rental');
        if ($code && !has_permission(BIZIT_SERVICES_MSL . '_rental_agreement', '', 'edit')) access_denied('Edit Rental');
        if (empty($flag)) $this->session->set_userdata(['service_rental_agreement_code' => rand(10000, 99999)]);
        $data['all_services'] = $this->services_core_model->get_all_services('001');
        $data['currency_symbol'] = get_default_currency('symbol');
        $data['staff'] = get_staff_with_permission(BIZIT_SERVICES_MSL . '_rental_agreement_field_report', 'create');
        if ($code) {
            $data['rental_agreement'] = $this->rentals_model->get_rental_agreement($code);
            if (!has_permission(BIZIT_SERVICES_MSL . '_rental_agreement', '', 'view') && has_permission(BIZIT_SERVICES_MSL . '_rental_agreement', '', 'view_own')) {
                if ($data['rental_agreement']->received_by != get_staff_user_id()) access_denied('View Global Rental Agreement');
            }
            $data['rental_agreement_details'] = $this->rentals_model->get_rental_agreement_details($data['rental_agreement']->service_rental_agreement_id);
            $data['uploaded_files'] = $this->rentals_model->get_rental_agreement_files($data['rental_agreement']->service_rental_agreement_id);
        }
        $this->load->view('admin/services/service_rental_agreements_form', $data);
    }

    public function save_rental_agreement() {
        $data = $this->input->post();
        $data['report_files'] = json_encode($this->handle_file_uploads());
        if (empty($data['service_review_token'])) $data['service_review_token'] = md5(uniqid(rand(), true));
        $field_operator_id = $data['field_operator'] ?? null;
        $site_name = $data['site_name'] ?? '';
        if (!empty($data['edit_id'])) {
            $old_info = $this->rentals_model->get_rental_agreement($data['service_rental_agreement_code']);
            $this->rentals_model->edit_rental_agreement($data);
            if ($field_operator_id && $old_info && $old_info->field_operator != $field_operator_id) {
                if(function_exists('rental_agreement_notifications')) {
                    rental_agreement_notifications($field_operator_id, get_staff_user_id(), $data['service_rental_agreement_code'], 'field_operator_notice', $site_name);
                    if ($old_info->field_operator) rental_agreement_notifications($old_info->field_operator, get_staff_user_id(), $data['service_rental_agreement_code'], 'field_operator_removal_notice', $old_info->site_name);
                }
            }
        } else {
            $this->rentals_model->add_rental_agreement($data);
            if ($field_operator_id && function_exists('rental_agreement_notifications')) rental_agreement_notifications($field_operator_id, get_staff_user_id(), $data['service_rental_agreement_code'], 'field_operator_notice', $site_name);
        }
        redirect(admin_url('services/new_rental_agreement/1/' . $data['service_rental_agreement_code']));
    }

    public function delete_service_rental_agreement_price($id, $code) {
        if (!has_permission(BIZIT_SERVICES_MSL . '_rental_agreement', '', 'edit')) access_denied(BIZIT_SERVICES_MSL . '_rental_agreement');
        $this->rentals_model->delete_rental_agreement_details($id);
        set_alert('success', 'Successfully deleted rental item');
        redirect(admin_url('services/new_rental_agreement/1/' . $code));
    }

    public function view_rental_agreement($code) {
        if (!has_permission(BIZIT_SERVICES_MSL . '_rental_agreement', '', 'view') && !has_permission(BIZIT_SERVICES_MSL . '_rental_agreement', '', 'view_own')) access_denied('View Rental Agreement');
        $data['service_info'] = $this->rentals_model->get_rental_agreement($code);
        if(!$data['service_info']) show_404();
        if (!has_permission(BIZIT_SERVICES_MSL . '_rental_agreement', '', 'view') && has_permission(BIZIT_SERVICES_MSL . '_rental_agreement', '', 'view_own')) {
            if ($data['service_info']->received_by != get_staff_user_id()) access_denied('Access Restricted');
        }
        if ($this->input->is_ajax_request()) {
            $this->app->get_table_data(module_views_path(BIZIT_SERVICES_MSL, 'table/services_field_report'), ['service_rental_agreement_id' => $data['service_info']->service_rental_agreement_id]); 
            exit;
        }
        $data['reports_count'] = total_rows('tblfield_report', ['service_rental_agreement_id' => $data['service_info']->service_rental_agreement_id]);
        $data['service_details'] = $this->rentals_model->get_rental_agreement_details($data['service_info']->service_rental_agreement_id);
        $data['service_rental_agreement_client'] = $this->clients_model->get($data['service_info']->clientid);
        $start = new DateTime($data['service_info']->start_date);
        $end = new DateTime($data['service_info']->end_date);
        $interval = $start->diff($end);
        $data['rental_days'] = $interval->format("%a") - $data['service_info']->discounted_days;
        $data['actual_rental_days'] = $interval->format("%a");
        $data['currency_symbol'] = get_default_currency('symbol');
        $this->load->view('admin/services/view_rental_agreement', $data);
    }

    public function service_rental_agreement_re_confirmation() {
        if (!has_permission(BIZIT_SERVICES_MSL . '_rental_agreement', '', 'edit')) access_denied(BIZIT_SERVICES_MSL . '_rental_agreement');
        $data['status'] = $this->input->post('status', true);
        $id = $this->input->post('service_rental_agreement_id', true);
        $code = $this->input->post('service_rental_agreement_code', true);
        $this->db->where('service_rental_agreement_id', $id)->update('tblservice_rental_agreement', ['status' => $data['status']]);
        set_alert('success', 'Rental status updated successfully');
        redirect(admin_url('services/view_rental_agreement/' . $code));
    }

    public function return_rental($code, $invoiceid) {
        if (!has_permission(BIZIT_SERVICES_MSL . '_rental_agreement', '', 'edit')) access_denied(BIZIT_SERVICES_MSL . '_rental_agreement');
        $data_service['extra_days'] = $this->input->post('extra_days', true);
        $data_service['discounted_days'] = $this->input->post('discounted_days', true);
        $data_service['actual_date_returned'] = to_sql_date($this->input->post('actual_date_returned', true));
        $switch_to_inv = $this->input->post('switch_to_inv', true);
        $this->db->where('service_rental_agreement_code', $code)->update('tblservice_rental_agreement', $data_service);
        if ($this->db->affected_rows() > 0) {
            $service_ID = $this->db->select('d.serviceid')->from('tblservice_rental_agreement_details d')->join('tblservice_rental_agreement a', 'a.service_rental_agreement_id = d.service_rental_agreement_id')->where('a.invoice_rel_id', $invoiceid)->get()->result();
            if (!empty($service_ID)) {
                foreach ($service_ID as $val) { $this->db->where('serviceid', $val->serviceid)->update('tblservices_module', ['rental_status' => 'Not-Hired']); }
            }
            set_alert('success', _l('updated_successfully', _l('rental_agreement')));
            if (empty($switch_to_inv)) redirect(admin_url('services/view_rental_agreement/' . $code));
            else redirect(admin_url('invoices/list_invoices#' . $invoiceid));
        } else {
            if (empty($switch_to_inv)) redirect(admin_url('services/view_rental_agreement/' . $code));
            else redirect(admin_url('invoices/list_invoices#' . $invoiceid));
        }
    }

    public function service_rental_agreement_reasign_field_operator() {
        if (!has_permission(BIZIT_SERVICES_MSL . '_rental_agreement', '', 'edit')) access_denied(BIZIT_SERVICES_MSL . '_rental_agreement');
        $id = $this->input->post('service_rental_agreement_id', true);
        $operator_id = $this->input->post('field_operator', true);
        $info = $this->db->where('service_rental_agreement_id', $id)->get('tblservice_rental_agreement')->row();
        $update = ['field_operator' => $operator_id];
        $this->db->where('service_rental_agreement_id', $id)->update('tblservice_rental_agreement', $update);
        if ($this->db->affected_rows() > 0 && function_exists('rental_agreement_notifications')) {
            if ($info->field_operator != $operator_id) {
                rental_agreement_notifications($operator_id, get_staff_user_id(), $info->service_rental_agreement_code, 'field_operator_notice', $info->site_name);
                if ($info->field_operator) rental_agreement_notifications($info->field_operator, get_staff_user_id(), $info->service_rental_agreement_code, 'field_operator_removal_notice', $info->site_name);
            }
            set_alert('success', 'Field Operator Reassigned');
        }
        redirect(admin_url('services/view_rental_agreement/' . $info->service_rental_agreement_code));
    }

    public function field_reports() {
        if ($this->input->is_ajax_request()) $this->app->get_table_data(module_views_path(BIZIT_SERVICES_MSL, 'table/services_field_report'));
        $this->load->view('admin/services/manage_field_reports', ['title' => 'Field Reports']);
    }

    public function manage_field_report() {
        if ($this->input->post()) {
            $data = $this->input->post();
            $data['report_files'] = json_encode($this->handle_file_uploads());
            if (isset($data['field_report_id']) && $data['field_report_id']) {
                $old = $this->reports_model->get_field_report_by_id($data['field_report_id']);
                if ($data['status'] >= 2 && $old->status < 2) {
                    $data['submitted_by'] = get_staff_user_id();
                    $data['date_submitted'] = date('Y-m-d H:i:s');
                }
                $this->reports_model->edit_field_report($data);
            } else {
                $this->reports_model->add_field_report($data);
            }
            redirect(admin_url('services/field_report/edit/' . $data['report_code']));
        }
    }

    // --- RESTORED FILE MANAGEMENT ---
    public function upload_file($type, $type_id, $upload = false)
    {
        $data['report_code'] = '';
        if($type == 'field_report') {
             $data['report_code'] = $this->db->select('report_code')->where('field_report_id', $type_id)->get('tblfield_report')->row('report_code');
        }
        if (!$upload) {
            if ($this->input->is_ajax_request()) $this->load->view('admin/services/report_files', $data);
        } else {
            handle_service_report_attachments($type, $type_id);
        }
    }

    public function manage_files($type, $type_id)
    {
        if ($this->input->is_ajax_request()) {
            $this->app->get_table_data(module_views_path(BIZIT_SERVICES_MSL, 'table/services_report_files'), array('type' => $type, 'type_id' => $type_id));
        }
    }

    public function delete_file($id, $type) {
        $this->db->where('id', $id);
        $this->db->where('rel_type', $type);
        $file = $this->db->get('tblfiles')->row();
        $success = false;
        if ($file && ($file->staffid == get_staff_user_id() || is_admin())) {
            $this->db->where('id', $id)->delete('tblfiles');
            $path = FCPATH . 'modules/bizit_services_msl/uploads/reports/' . $file->file_name;
            if(file_exists($path)) unlink($path);
            $success = true;
        }
        echo json_encode(['success' => $success, 'message' => $success ? _l('deleted') : _l('problem_deleting')]);
    }

    public function delete_field_report($id, $code) {
        $success = $this->reports_model->delete_field_report($id);
        if ($success) set_alert('success', _l('deleted', _l('field_report')));
        else set_alert('warning', _l('problem_deleting', _l('field_report')));
        redirect(admin_url('services/view_rental_agreement/' . $code));
    }

    public function manage_field_report_appr_rej() {
        if ($this->input->post()) {
            $data = $this->input->post();
            $update = ['field_report_id' => $data['field_report_id'], 'status' => ($data['aprv_rej'] == "1" ? 4 : 3), 'approval_remarks' => $data['aprv_rej_remark']];
            if ($update['status'] == 4) $update['approved_by'] = get_staff_user_id();
            else $update['rejected_by'] = get_staff_user_id();
            $this->reports_model->edit_field_report($update);
            set_alert('success', 'Report Status Updated');
            redirect(admin_url('services/field_report/view/' . $data['report_code']));
        }
    }

    public function staff_compensation_rates() {
        if ($this->input->post()) {
            $this->compensation_model->update_compensation_rate($this->input->post());
            set_alert('success', 'Rates Saved');
            redirect(admin_url('services/staff_compensation_rates'));
        }
        $data['rates'] = $this->compensation_model->get_all_compensation_rates();
        $data['staff_members'] = $this->staff_model->get();
        $data['all_services'] = $this->services_core_model->get_all_services(null);
        $data['title'] = 'Staff Compensation Rates';
        $this->load->view('admin/services/staff_compensation_rates', $data);
    }

    public function reports_dashboard() {
        $start = $this->input->post('start_date') ?: date('Y-01-01');
        $end = $this->input->post('end_date') ?: date('Y-12-31');
        $data['performance_data'] = $this->compensation_model->get_staff_compensation_data($start, $end);
        $data['widgets'] = $this->compensation_model->get_dashboard_widgets();
        $data['analytics'] = $this->compensation_model->get_analytics_data($start, $end);
        $data['start_date'] = $start; $data['end_date'] = $end;
        $data['title'] = 'Performance Dashboard';
        $this->load->view('admin/services/reports_dashboard', $data);
    }

    public function rental_agreement_invoice_generation($code) {
        $agr = $this->rentals_model->get_rental_agreement($code);
        $details = $this->rentals_model->get_rental_agreement_details($agr->service_rental_agreement_id);
        $start = new DateTime($agr->start_date);
        $end = new DateTime($agr->end_date);
        $days = $end->diff($start)->format("%a") - $agr->discounted_days;
        $newitems = []; $i = 1; $subtotal = 0;
        foreach ($details as $d) {
            $newitems[$i] = ["order" => $i, "description" => "Rental: " . $code, "qty" => $days, "unit" => "Days", "rate" => $d->price, "taxable" => 1];
            $subtotal += ($d->price * $days); $i++;
        }
        if ($agr->extra_days > 0) {
            $newitems[$i] = ["order" => $i, "description" => "Extra Days Penalty", "qty" => $agr->extra_days, "unit" => "Days", "rate" => 0, "taxable" => 1]; 
        }
        $client = $this->clients_model->get($agr->clientid);
        $inv_data = ["clientid" => $client->userid, "date" => _d(date('Y-m-d')), "currency" => get_default_currency('id'), "subtotal" => $subtotal, "total" => $subtotal, "newitems" => $newitems, "save_as_draft" => "true"];
        $this->invoices_model->add($inv_data);
        set_alert('success', 'Invoice Generated');
        redirect(admin_url('services/view_rental_agreement/' . $code));
    }

    private function handle_file_uploads() {
        $uploaded = [];
        if (!empty($_FILES['service_files']['name'][0])) {
            $config = ['upload_path' => './modules/bizit_services_msl/uploads/reports/', 'allowed_types' => 'jpg|png|pdf|docx'];
            $this->load->library('upload', $config);
            foreach ($_FILES['service_files']['name'] as $i => $name) {
                if ($name) {
                    $_FILES['f']['name'] = $name; $_FILES['f']['type'] = $_FILES['service_files']['type'][$i]; $_FILES['f']['tmp_name'] = $_FILES['service_files']['tmp_name'][$i]; $_FILES['f']['error'] = $_FILES['service_files']['error'][$i]; $_FILES['f']['size'] = $_FILES['service_files']['size'][$i];
                    if ($this->upload->do_upload('f')) $uploaded[] = $this->upload->data('file_name');
                }
            }
        }
        return $uploaded;
    }
    
    public function request_pdf($code) { 
        $data['service_request'] = $this->requests_model->get_request($code);
        $data['service_details'] = $this->requests_model->get_request_details($data['service_request']->service_request_id);
        $data['service_request_client'] = $this->clients_model->get($data['service_request']->clientid);
        $data['pre_inspection_items'] = [];
        $data['checklist_items'] = $this->requests_model->get_checklist_data($data['service_request']->service_request_id);
        $data['released_info'] = $this->db->where('service_request_id', $data['service_request']->service_request_id)->get('tblcollection1')->row();
        $pdf = service_request_pdf($data);
        $pdf->Output('REQUEST.pdf', 'I');
    }

    public function rental_agreement_pdf($code) {
        $data['service_rental_agreement'] = $this->rentals_model->get_rental_agreement($code);
        $data['service_details'] = $this->rentals_model->get_rental_agreement_details($data['service_rental_agreement']->service_rental_agreement_id);
        $data['service_rental_agreement_client'] = $this->clients_model->get($data['service_rental_agreement']->clientid);
        $data['service_info'] = $data['service_rental_agreement']; 
        $pdf = service_rental_agreement_pdf($data);
        $pdf->Output('AGREEMENT.pdf', 'I');
    }
    
    public function change_assignee($id) { 
        if(is_admin()) $this->db->where('id',$id)->update('tblinvoices',['addedfrom'=>$this->input->post('addedfrom')]); 
        redirect(admin_url('invoices/list_invoices#'.$id)); 
    }
    
    public function inventory_qty_check($pid=0, $qty=0) { 
        if($this->input->is_ajax_request()) echo $this->invoices_model->inventory_qty_check($pid, $qty); 
    }

    public function delivery_note($invoice_id) {
        $invoice = $this->invoices_model->get($invoice_id);
        $data['invoice_number'] = format_invoice_number($invoice->id);
        $data['items_data'] = $this->services_core_model->get_table_products_bulk($invoice->id);
        $data['invoice'] = $invoice;
        $data['client'] = $this->clients_model->get($invoice->clientid);
        $data['status'] = $invoice->status;
        $pdf = delivery_note_pdf($data);
        $pdf->Output('DELIVERY.pdf', 'I');
    }
    
    public function inventory_checklist($invoice_id) {
        $invoice = $this->invoices_model->get($invoice_id);
        $data['invoice_number'] = format_invoice_number($invoice->id);
        $data['items_data'] = $this->services_core_model->get_table_products_bulk($invoice->id);
        $data['invoice'] = $invoice;
        $data['client'] = $this->clients_model->get($invoice->clientid);
        $data['status'] = $invoice->status;
        $pdf = inventory_checklist_pdf($data);
        $pdf->Output('CHECKLIST.pdf', 'I');
    }

    public function gpsDetails() {
        if(method_exists($this->services_core_model, 'get_gps_details')) $data['gps_details'] = $this->services_core_model->get_gps_details();
        else $data['gps_details'] = $this->db->get('tblgps_data')->result();
        $this->load->view('admin/services/gps_details', $data);
    }

    public function form_gps() { $this->load->view('admin/services/gps_data_form'); }

    public function insert_gps_data() {
        $this->load->library('form_validation');
        $this->form_validation->set_rules('data', 'Data', 'required');
        if ($this->form_validation->run() == FALSE) $this->load->view('admin/services/gps_data_form');
        else {
            $data = $this->input->post('data');
            $this->db->insert('tblgps_data', $data);
            $this->load->view('admin/services/form_success');
        }
    }

    // --- RESTORED UNIFIED CALENDAR ---
    public function rental_calendar() {
        $data['rental_details'] = $this->rentals_model->get_calendar_rental_details();
        $data['service_request_details'] = $this->requests_model->get_calendar_service_details();
        $data['title'] = 'Operations Calendar';
        $this->load->view('admin/services/rental_agreements_calendar', $data);
    }
    
    public function view_warranty(){ show_404(); }
}
