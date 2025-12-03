<?php defined('BASEPATH') or exit('No direct script access allowed');

class Services extends ClientsController
{
    public function __construct()
    {
        parent::__construct();
        $this->load->model('bizit_services_msl/requests_model');
        $this->load->model('bizit_services_msl/reports_model');
        $this->load->model('clients_model');
        $this->load->helper(['bizit_services_msl', 'invoices']);
        $this->load->library('ciqrcode');
        $this->load->library('bizit_services_msl/Dpdf');
    }

    public function index() { show_404(); }

    public function certificate($flag_or_code = null, $legacy_code = null)
    {
        $code = ($legacy_code) ? $legacy_code : $flag_or_code;
        if (empty($code)) show_404();
        $data['service_request'] = $this->requests_model->get_request($code);
        if (!$data['service_request']) show_404();
        $data['calibration_info'] = $this->requests_model->get_report_check($data['service_request']->service_request_id);
        $data['service_request_client'] = $this->clients_model->get($data['service_request']->clientid);
        $this->app_scripts->theme('sticky-js', 'assets/plugins/sticky/sticky.js');
        $data['title'] = 'Certificate Verification';
        $this->data($data);
        $this->view('client/certificate_verification');
        $this->layout();
    }
    
    public function report($flag = null, $code = null)
    {
        if(empty($code) && !empty($flag)) { $code = $flag; $flag = 'view'; }
        if(empty($code)) show_404();
        $req = $this->requests_model->get_request($code);
        if(!$req) show_404();
        $data['service_request'] = $req;
        $data['service_info'] = $req; 
        $data['calibration_info'] = $this->requests_model->get_report_check($req->service_request_id);
        $data['service_request_client'] = $this->clients_model->get($req->clientid);
        $data['service_request_code'] = $code;

        if ($flag == 'pdf') {
            $this->generate_pdf($code, get_option('service_request_prefix'), $data, 'service_request_report_pdf');
        }
        $data['title'] = 'Calibration Report';
        $this->data($data);
        $this->view('client/report_calibration_view');
        $this->layout();
    }

    public function field_report($flag = null, $code = null)
    {
        if(empty($code) && !empty($flag) && $flag != 'view' && $flag != 'pdf') { $code = $flag; $flag = 'view'; }
        if (empty($code)) show_404();
        $report = $this->reports_model->get_field_report($code);
        if (!$report) show_404();
        $data['field_report_info'] = $report;
        $agreement = $this->db->where('service_rental_agreement_id', $report->service_rental_agreement_id)->get('tblservice_rental_agreement')->row();
        $data['service_rental_agreement'] = $agreement;
        $data['service_info'] = $agreement; 
        $data['service_rental_agreement_client'] = $this->clients_model->get($agreement->clientid);
        $data['service_details'] = $this->db->select('d.*, m.name, m.rental_serial, d.price')
            ->from('tblservice_rental_agreement_details d')
            ->join('tblservices_module m', 'm.serviceid=d.serviceid')
            ->where('service_rental_agreement_id', $report->service_rental_agreement_id)
            ->get()->result();

        if ($flag == 'pdf') {
            $this->generate_pdf($code, get_option('service_rental_agreement_prefix'), $data, 'service_rental_agreement_pdf');
        }
        $this->app_scripts->theme('sticky-js', 'assets/plugins/sticky/sticky.js');
        $data['title'] = 'Field Report View';
        $this->data($data);
        $this->view('client/field_report_view');
        $this->layout();
    }

    private function generate_pdf($code, $prefix, $data, $pdf_func) {
        $qr_data = "Report No. {$prefix}{$code} - Valid MSL Document.";
        $params['data'] = $qr_data; $params['level'] = 'L'; $params['size'] = 2;
        $qr_image_path = FCPATH . 'uploads/temp/' . $code . '_qr.png';
        if(!is_dir(FCPATH.'uploads/temp/')) mkdir(FCPATH.'uploads/temp/', 0755);
        $params['savename'] = $qr_image_path;
        $this->ciqrcode->generate($params);
        $data['qr_code_base64'] = base64_encode(file_get_contents($qr_image_path));
        @unlink($qr_image_path);
        $pdf = $pdf_func($data);
        $pdf->Output('REPORT_' . $code . '.pdf', 'I');
        die;
    }
}