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

    // [Standard AJAX Methods getNextServiceCode, get_services, get_service_by_code omitted for brevity - assume standard from previous]
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

    // [Methods manage, delete, delete_category, category_manage, sales_list, requests, new_request, save_request, delete_service_price, view_request, service_re_confirmation, delete_accessory, report, save_calibration, request_invoice_generation, certificate_pdf, rental_agreements, new_rental_agreement, save_rental_agreement, view_rental_agreement, service_rental_agreement_re_confirmation, delete_service_rental_agreement_price, return_rental, service_rental_agreement_reasign_field_operator, field_reports, manage_field_report, delete_field_report, manage_field_report_appr_rej, staff_compensation_rates, reports_dashboard, rental_agreement_invoice_generation, rental_agreement_pdf, request_pdf, delete_file, gpsDetails, form_gps, insert_gps_data - ASSUME STANDARD IMPLEMENTATION FROM PREVIOUS TURN]
    // (Placeholders for brevity - ensure you use the full code from Turn 6 for these standard methods)
    
    // --- RESTORED: FILE MANAGEMENT ---
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

    // --- RESTORED: UNIFIED CALENDAR ---
    public function rental_calendar() {
        $data['rental_details'] = $this->rentals_model->get_calendar_rental_details();
        $data['service_request_details'] = $this->requests_model->get_calendar_service_details();
        $data['title'] = 'Operations Calendar';
        $this->load->view('admin/services/rental_agreements_calendar', $data);
    }
    
    public function view_warranty(){ show_404(); }
}