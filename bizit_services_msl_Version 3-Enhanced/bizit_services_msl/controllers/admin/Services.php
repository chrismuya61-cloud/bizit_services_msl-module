<?php defined('BASEPATH') or exit('No direct script access allowed');

class Services extends AdminController
{
    public function __construct()
    {
        parent::__construct();
        // LOAD ALL 5 SPLIT MODELS
        $this->load->model([
            'bizit_services_msl/services_core_model',
            'bizit_services_msl/requests_model',
            'bizit_services_msl/rentals_model',
            'bizit_services_msl/reports_model',
            'bizit_services_msl/compensation_model'
        ]);
        $this->load->model(['clients_model', 'invoices_model', 'staff_model']);
        $this->load->helper(['bizit_services_msl', 'invoices', 'number']);
        $this->load->library('ciqrcode');
    }

    // ==========================================================
    //  CORE SERVICES & SETTINGS
    // ==========================================================

    public function index()
    {
        if (!has_permission(BIZIT_SERVICES_MSL, '', 'view') && !is_admin()) access_denied('Services');
        if ($this->input->is_ajax_request()) {
            $this->app->get_table_data(module_views_path(BIZIT_SERVICES_MSL, 'table/services'));
        }
        $data['currency_symbol'] = get_default_currency('symbol');
        $data['service_categories'] = $this->services_core_model->get_all_services_category_info();
        $data['title'] = _l('services');
        $this->load->view('admin/services/manage', $data);
    }

    public function manage()
    {
        if ($this->input->post()) {
            $data = $this->input->post();
            if (empty($data['serviceid'])) {
                if (!has_permission(BIZIT_SERVICES_MSL, '', 'create')) access_denied('Services');
                $id = $this->services_core_model->add($data);
                echo json_encode(['success' => (bool)$id, 'message' => $id ? _l('added_successfully', _l('service')) : 'Error']);
            } else {
                if (!has_permission(BIZIT_SERVICES_MSL, '', 'edit')) access_denied('Services');
                $success = $this->services_core_model->edit($data);
                echo json_encode(['success' => $success, 'message' => $success ? _l('updated_successfully', _l('service')) : 'No changes']);
            }
        }
    }

    public function delete($id)
    {
        if (!has_permission(BIZIT_SERVICES_MSL, '', 'delete')) access_denied('Services');
        $response = $this->services_core_model->delete($id);
        set_alert($response ? 'success' : 'warning', $response ? _l('deleted', _l('service')) : _l('problem_deleting', _l('service')));
        redirect(admin_url('services'));
    }

    public function category_manage()
    {
        if ($this->input->post()) {
            $data = $this->input->post();
            if (empty($data['type_code'])) $data['type_code'] = get_next_service_category_code_internal();
            
            if (empty($data['service_typeid'])) {
                $id = $this->services_core_model->add_category($data);
                echo json_encode(['success' => (bool)$id, 'message' => _l('added_successfully', _l('service_type'))]);
            } else {
                $success = $this->services_core_model->edit_category($data);
                echo json_encode(['success' => $success, 'message' => _l('updated_successfully', _l('service_type'))]);
            }
        } else {
            $this->load->view('admin/services/modal_category');
        }
    }

    // --- RESTORED: SALES LIST ---
    public function sales_list()
    {
        if ($this->input->is_ajax_request()) {
            $this->app->get_table_data(module_views_path(BIZIT_SERVICES_MSL, 'table/services_sales_list'));
        }
        $this->load->view('admin/services/manage_sales_list', ['title' => _l('als_services_sales_list')]);
    }

    // ==========================================================
    //  SERVICE REQUESTS & CALIBRATION
    // ==========================================================

    public function requests()
    {
        if ($this->input->is_ajax_request()) {
            $this->app->get_table_data(module_views_path(BIZIT_SERVICES_MSL, 'table/services_requests'));
        }
        $this->load->view('admin/services/manage_requests', ['title' => _l('als_services_requests')]);
    }

    public function new_request($flag = null, $code = null)
    {
        if (empty($flag)) $this->session->set_userdata(['service_request_code' => rand(10000, 99999)]);
        
        $data['all_services'] = $this->services_core_model->get_all_services('002');
        $data['all_services_accessories'] = $this->services_core_model->get_all_services_accessories();
        $data['currency_symbol'] = get_default_currency('symbol');

        if ($code) {
            $data['request'] = $this->requests_model->get_request($code);
            $data['request_details'] = $this->requests_model->get_request_details($data['request']->service_request_id);
            $data['service_request_client'] = $this->clients_model->get($data['request']->clientid);
            $data['existing_accessories'] = $this->requests_model->get_request_accessories($data['request']->service_request_id);
            
            $data['checklist_items'] = $this->requests_model->get_checklist_data($data['request']->service_request_id);
            $data['uploaded_files'] = $this->requests_model->get_service_files($data['request']->service_request_id);
            
            foreach(['dropped_off_by','dropped_off_date','req_received_by','received_date'] as $f) $data[$f] = $data['request']->$f;

            $inspections = $this->requests_model->get_inspection_data($data['request']->service_request_id);
            $data['pre_inspection_items'] = []; $data['post_inspection_items'] = [];
            foreach ($inspections as $i) {
                if ($i['inspection_type'] == 'pre_inspection') $data['pre_inspection_items'][] = (object)$i;
                else $data['post_inspection_items'][] = (object)$i;
            }
        }
        $this->load->view('admin/services/service_requests_form', $data);
    }

    public function save_request()
    {
        $data = $this->input->post();
        $data['report_files'] = json_encode($this->handle_file_uploads());
        if (empty($data['service_review_token'])) $data['service_review_token'] = md5(uniqid(rand(), true));

        $id = $data['edit_id'] ? $this->requests_model->edit_request($data) : $this->requests_model->add_request($data);

        if (isset($data['serviceid'])) {
            for ($i = 0; $i < count($data['serviceid']); $i++) {
                if ($data['serviceid'][$i]) {
                    $det = ['service_request_id' => ($data['edit_id'] ?: $id), 'serviceid' => $data['serviceid'][$i], 'price' => $data['service_price'][$i]];
                    $this->requests_model->add_request_details($det);
                }
            }
        }
        set_alert('success', 'Request Saved');
        redirect(admin_url('services/view_request/' . $data['service_request_code']));
    }

    public function view_request($code)
    {
        $data['service_info'] = $this->requests_model->get_request($code);
        $data['service_details'] = $this->db->select('d.*, m.name')->from('tblservice_request_details d')->join('tblservices_module m', 'm.serviceid=d.serviceid')->where('d.service_request_id', $data['service_info']->service_request_id)->get()->result();
        $data['service_request_client'] = $this->clients_model->get($data['service_info']->clientid);
        $data['existing_accessories'] = $this->requests_model->get_request_accessories($data['service_info']->service_request_id);
        $this->load->view('admin/services/view_request', $data);
    }

    // --- RESTORED: REPORT PDF/VIEW LOGIC ---
    public function report($flag = null, $code = null)
    {
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

        if ($flag == 'pdf') {
            $this->load->library('ciqrcode');
            $params['data'] = site_url('services/certificate/' . $code);
            $params['savename'] = FCPATH . 'uploads/temp/' . $code . '_qr.png';
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

    public function save_calibration()
    {
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

    // ==========================================================
    //  RENTAL AGREEMENTS
    // ==========================================================

    public function rental_agreements()
    {
        if ($this->input->is_ajax_request()) {
            $this->app->get_table_data(module_views_path(BIZIT_SERVICES_MSL, 'table/services_rental_agreements'));
        }
        $this->load->view('admin/services/manage_rental_agreements', ['title' => _l('als_services_for_hire')]);
    }

    public function new_rental_agreement($flag = null, $code = null)
    {
        if (empty($flag)) $this->session->set_userdata(['service_rental_agreement_code' => rand(10000, 99999)]);
        
        $data['all_services'] = $this->services_core_model->get_all_services('001');
        $data['currency_symbol'] = get_default_currency('symbol');
        $data['staff'] = get_staff_with_permission(BIZIT_SERVICES_MSL . '_rental_agreement_field_report', 'create');

        if ($code) {
            $data['rental_agreement'] = $this->rentals_model->get_rental_agreement($code);
            $data['rental_agreement_details'] = $this->rentals_model->get_rental_agreement_details($data['rental_agreement']->service_rental_agreement_id);
            $data['uploaded_files'] = $this->rentals_model->get_rental_agreement_files($data['rental_agreement']->service_rental_agreement_id);
        }
        $this->load->view('admin/services/service_rental_agreements_form', $data);
    }

    public function save_rental_agreement()
    {
        $data = $this->input->post();
        $data['report_files'] = json_encode($this->handle_file_uploads());
        if (empty($data['service_review_token'])) $data['service_review_token'] = md5(uniqid(rand(), true));

        if ($data['edit_id']) {
            $this->rentals_model->edit_rental_agreement($data);
        } else {
            $this->rentals_model->add_rental_agreement($data);
        }
        redirect(admin_url('services/new_rental_agreement/1/' . $data['service_rental_agreement_code']));
    }

    public function view_rental_agreement($code)
    {
        $data['service_info'] = $this->rentals_model->get_rental_agreement($code);
        if ($this->input->is_ajax_request()) {
            $this->app->get_table_data(module_views_path(BIZIT_SERVICES_MSL, 'table/services_field_report'), ['service_rental_agreement_id' => $data['service_info']->service_rental_agreement_id]); exit;
        }
        $data['reports_count'] = total_rows('tblfield_report', ['service_rental_agreement_id' => $data['service_info']->service_rental_agreement_id]);
        $data['service_details'] = $this->rentals_model->get_rental_agreement_details($data['service_info']->service_rental_agreement_id);
        $data['service_rental_agreement_client'] = $this->clients_model->get($data['service_info']->clientid);
        
        $start = new DateTime($data['service_info']->start_date);
        $end = new DateTime($data['service_info']->end_date);
        $data['rental_days'] = $end->diff($start)->format("%a");
        
        $this->load->view('admin/services/view_rental_agreement', $data);
    }

    // ==========================================================
    //  FIELD REPORTS & COMPENSATION
    // ==========================================================

    public function field_reports()
    {
        if ($this->input->is_ajax_request()) {
            $this->app->get_table_data(module_views_path(BIZIT_SERVICES_MSL, 'table/services_field_report'));
        }
        $this->load->view('admin/services/manage_field_reports', ['title' => 'Field Reports']);
    }

    public function manage_field_report()
    {
        if ($this->input->post()) {
            $data = $this->input->post();
            $data['report_files'] = json_encode($this->handle_file_uploads());
            
            if ($data['field_report_id']) {
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

    // --- RESTORED: APPROVE/REJECT LOGIC ---
    public function manage_field_report_appr_rej()
    {
        if ($this->input->post()) {
            $data = $this->input->post();
            $update = [
                'field_report_id' => $data['field_report_id'],
                'status' => ($data['aprv_rej'] == "1" ? 4 : 3),
                'approval_remarks' => $data['aprv_rej_remark']
            ];
            if ($update['status'] == 4) $update['approved_by'] = get_staff_user_id();
            else $update['rejected_by'] = get_staff_user_id();

            $this->reports_model->edit_field_report($update);
            set_alert('success', 'Report Status Updated');
            redirect(admin_url('services/field_report/view/' . $data['report_code']));
        }
    }

    public function staff_compensation_rates()
    {
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

    public function reports_dashboard()
    {
        $start = $this->input->post('start_date') ?: date('Y-01-01');
        $end = $this->input->post('end_date') ?: date('Y-12-31');
        
        $data['performance_data'] = $this->compensation_model->get_staff_compensation_data($start, $end);
        $data['widgets'] = $this->compensation_model->get_dashboard_widgets();
        $data['analytics'] = $this->compensation_model->get_analytics_data($start, $end);
        $data['start_date'] = $start; $data['end_date'] = $end;
        $data['title'] = 'Performance Dashboard';
        
        $this->load->view('admin/services/reports_dashboard', $data);
    }

    // ==========================================================
    //  UTILITIES & PDF
    // ==========================================================

    public function rental_agreement_invoice_generation($code)
    {
        $agr = $this->rentals_model->get_rental_agreement($code);
        $details = $this->rentals_model->get_rental_agreement_details($agr->service_rental_agreement_id);
        
        $start = new DateTime($agr->start_date);
        $end = new DateTime($agr->end_date);
        $days = $end->diff($start)->format("%a") - $agr->discounted_days;
        
        $newitems = []; $i = 1; $subtotal = 0;
        foreach ($details as $d) {
            $newitems[$i] = [
                "order" => $i, "description" => "Rental: " . $code, "qty" => $days, "unit" => "Days", "rate" => $d->price, "taxable" => 1
            ];
            $subtotal += ($d->price * $days);
            $i++;
        }

        if ($agr->extra_days > 0) {
            $newitems[$i] = ["order" => $i, "description" => "Extra Days Penalty", "qty" => $agr->extra_days, "unit" => "Days", "rate" => 0, "taxable" => 1]; 
        }

        $client = $this->clients_model->get($agr->clientid);
        $inv_data = [
            "clientid" => $client->userid, "date" => _d(date('Y-m-d')), "currency" => get_default_currency('id'),
            "subtotal" => $subtotal, "total" => $subtotal, "newitems" => $newitems,
            "save_as_draft" => "true"
        ];
        $this->invoices_model->add($inv_data);
        set_alert('success', 'Invoice Generated');
        redirect(admin_url('services/view_rental_agreement/' . $code));
    }

    private function handle_file_uploads()
    {
        $uploaded = [];
        if (!empty($_FILES['service_files']['name'][0])) {
            $config = ['upload_path' => './modules/bizit_services_msl/uploads/reports/', 'allowed_types' => 'jpg|png|pdf|docx'];
            $this->load->library('upload', $config);
            foreach ($_FILES['service_files']['name'] as $i => $name) {
                if ($name) {
                    $_FILES['f']['name'] = $name;
                    $_FILES['f']['type'] = $_FILES['service_files']['type'][$i];
                    $_FILES['f']['tmp_name'] = $_FILES['service_files']['tmp_name'][$i];
                    $_FILES['f']['error'] = $_FILES['service_files']['error'][$i];
                    $_FILES['f']['size'] = $_FILES['service_files']['size'][$i];
                    if ($this->upload->do_upload('f')) $uploaded[] = $this->upload->data('file_name');
                }
            }
        }
        return $uploaded;
    }
    
    // Legacy PDF Wrappers
    public function request_pdf($code) { 
        $data['service_request'] = $this->requests_model->get_request($code);
        $data['service_details'] = $this->requests_model->get_request_details($data['service_request']->service_request_id);
        $data['service_request_client'] = $this->clients_model->get($data['service_request']->clientid);
        $data['pre_inspection_items'] = []; 
        $pdf = service_request_pdf($data);
        $pdf->Output('REQUEST.pdf', 'I');
    }

    public function rental_agreement_pdf($code) {
        $data['service_rental_agreement'] = $this->rentals_model->get_rental_agreement($code);
        $data['service_details'] = $this->rentals_model->get_rental_agreement_details($data['service_rental_agreement']->service_rental_agreement_id);
        $data['service_rental_agreement_client'] = $this->clients_model->get($data['service_rental_agreement']->clientid);
        $pdf = service_rental_agreement_pdf($data);
        $pdf->Output('AGREEMENT.pdf', 'I');
    }
    
    // --- RESTORED UTILITIES ---
    public function change_assignee($id) { 
        if(is_admin()) $this->db->where('id',$id)->update('tblinvoices',['addedfrom'=>$this->input->post('addedfrom')]); 
        redirect(admin_url('invoices/list_invoices#'.$id)); 
    }
    
    public function inventory_qty_check($pid=0, $qty=0) { 
        if($this->input->is_ajax_request()) echo $this->invoices_model->inventory_qty_check($pid, $qty); 
    }

    // PDF Viewing Functions (Delivery Note, Checklist)
    public function delivery_note($invoice_id) {
        $invoice = $this->invoices_model->get($invoice_id);
        $data['invoice_number'] = format_invoice_number($invoice->id);
        $data['items_data'] = $this->services_core_model->get_table_products_bulk($invoice->id);
        $pdf = delivery_note_pdf($data);
        $pdf->Output('DELIVERY.pdf', 'I');
    }
    
    public function inventory_checklist($invoice_id) {
        $invoice = $this->invoices_model->get($invoice_id);
        $data['invoice_number'] = format_invoice_number($invoice->id);
        $data['items_data'] = $this->services_core_model->get_table_products_bulk($invoice->id);
        $pdf = inventory_checklist_pdf($data);
        $pdf->Output('CHECKLIST.pdf', 'I');
    }

    public function view_warranty(){ show_404(); }
}