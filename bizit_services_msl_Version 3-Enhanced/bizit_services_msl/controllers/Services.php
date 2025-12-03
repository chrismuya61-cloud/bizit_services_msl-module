<?php defined('BASEPATH') or exit('No direct script access allowed');

class Services extends ClientsController
{
    public function __construct()
    {
        parent::__construct();
        // Load V3 Models
        $this->load->model('bizit_services_msl/requests_model');
        $this->load->model('bizit_services_msl/reports_model');
        $this->load->model('clients_model');
        
        // Load Helpers & Libraries
        $this->load->helper(['bizit_services_msl', 'invoices']);
        $this->load->library('ciqrcode');
        $this->load->library('bizit_services_msl/Dpdf'); // Required for Certificate PDF
    }

    public function index()
    {
        show_404();
    }

    /**
     * Verify Calibration Certificate
     * URL: services/certificate/validate/[CODE]
     */
    public function certificate($flag_or_code = null, $legacy_code = null)
    {
        // Handle V1 URL pattern (certificate/validate/REQ-001) vs V3 pattern (certificate/REQ-001)
        $code = ($legacy_code) ? $legacy_code : $flag_or_code;
        
        if (empty($code)) show_404();

        // Fetch Data
        $data['service_request'] = $this->requests_model->get_request($code);
        if (!$data['service_request']) show_404();

        $data['calibration_info'] = $this->requests_model->get_report_check($data['service_request']->service_request_id);
        $data['service_request_client'] = $this->clients_model->get($data['service_request']->clientid);

        // Load View
        $this->app_scripts->theme('sticky-js', 'assets/plugins/sticky/sticky.js');
        $data['title'] = 'Certificate Verification';
        $this->data($data);
        $this->view('client/certificate_verification');
        $this->layout();
    }
    
    /**
     * View/Download Calibration Report
     * URL: services/report/view/[CODE] or services/report/pdf/[CODE]
     */
    public function report($flag = null, $code = null)
    {
        // Fix for argument shifting if only code is passed (defaults to view)
        if(empty($code) && !empty($flag)) {
            $code = $flag;
            $flag = 'view';
        }

        if(empty($code)) show_404();
        
        $req = $this->requests_model->get_request($code);
        if(!$req) show_404();
        
        // Prepare Data
        $data['service_request'] = $req;
        $data['service_info'] = $req; 
        $data['calibration_info'] = $this->requests_model->get_report_check($req->service_request_id);
        $data['service_request_client'] = $this->clients_model->get($req->clientid);
        $data['service_request_code'] = $code;
        
        // PDF Generation (Restored from V1)
        if ($flag == 'pdf') {
            $request_number = get_option('service_request_prefix') . $code;
            
            // Generate Validation QR Code
            $qr_data = "This Service Report Registration No. REQ-{$code} is a valid report from Measurement Systems Ltd.";
            $params['data'] = $qr_data;
            $params['level'] = 'L';
            $params['size'] = 2;
            $qr_image_path = FCPATH . 'uploads/temp/' . $code . '_qrcode.png';
            
            // Ensure temp directory exists
            if(!is_dir(FCPATH.'uploads/temp/')) mkdir(FCPATH.'uploads/temp/', 0755, true);
            
            $params['savename'] = $qr_image_path;
            $this->ciqrcode->generate($params);
            
            // Encode QR for PDF
            $data['qr_code_base64'] = base64_encode(file_get_contents($qr_image_path));
            @unlink($qr_image_path); // Cleanup

            try {
                // Use Helper Function to generate PDF
                $pdf = service_request_report_pdf($data);
                $pdf->Output('SERVICE REPORT ' . mb_strtoupper(slug_it($request_number)) . '.pdf', 'I');
            } catch (Exception $e) {
                log_message('error', 'PDF Generation Error: ' . $e->getMessage());
                show_error('Error generating PDF report.');
            }
            die;
        }

        // Standard HTML View
        $data['title'] = 'Calibration Report';
        $this->data($data);
        $this->view('client/report_calibration_view');
        $this->layout();
    }

    /**
     * View/Download Field Report
     * URL: services/field_report/view/[CODE] or services/field_report/pdf/[CODE]
     */
    public function field_report($flag = null, $code = null)
    {
        // Fix for argument shifting
        if(empty($code) && !empty($flag) && $flag != 'view' && $flag != 'pdf') {
            $code = $flag;
            $flag = 'view';
        }

        if (empty($code)) show_404();

        // Retrieve Report
        $report = $this->reports_model->get_field_report($code);
        if (!$report) show_404();

        $data['field_report_info'] = $report;
        
        // Fetch Related Rental Data
        $agreement = $this->db->where('service_rental_agreement_id', $report->service_rental_agreement_id)->get('tblservice_rental_agreement')->row();
        $data['service_rental_agreement'] = $agreement;
        $data['service_info'] = $agreement; // For legacy helper compatibility
        $data['service_rental_agreement_client'] = $this->clients_model->get($agreement->clientid);

        $data['service_details'] = $this->db->select('d.*, m.name, m.rental_serial, d.price')
            ->from('tblservice_rental_agreement_details d')
            ->join('tblservices_module m', 'm.serviceid=d.serviceid')
            ->where('service_rental_agreement_id', $report->service_rental_agreement_id)
            ->get()->result();

        // PDF Generation (Restored from V1)
        if ($flag == 'pdf') {
            $request_number = get_option('service_rental_agreement_prefix') . $code;
            
            // Generate Validation QR Code
            $qr_data = "This Rental Agreement Report Registration No. ARG-{$code} is a valid report from Measurement Systems Ltd.";
            $params['data'] = $qr_data;
            $params['level'] = 'L';
            $params['size'] = 2;
            $qr_image_path = FCPATH . 'uploads/temp/' . $code . '_field_qr.png';
            
            if(!is_dir(FCPATH.'uploads/temp/')) mkdir(FCPATH.'uploads/temp/', 0755, true);
            
            $params['savename'] = $qr_image_path;
            $this->ciqrcode->generate($params);
            
            $data['qr_code_base64'] = base64_encode(file_get_contents($qr_image_path));
            @unlink($qr_image_path);

            try {
                // Use Helper Function
                $pdf = service_rental_agreement_pdf($data);
                $pdf->Output('RENTAL FIELD REPORT ' . mb_strtoupper(slug_it($request_number)) . '.pdf', 'I');
            } catch (Exception $e) {
                log_message('error', 'PDF Generation Error: ' . $e->getMessage());
                show_error('Error generating PDF report.');
            }
            die;
        }

        // Standard HTML View
        $data['title'] = 'Field Report View';
        $this->app_scripts->theme('sticky-js', 'assets/plugins/sticky/sticky.js');
        $this->data($data);
        $this->view('client/field_report_view');
        $this->layout();
    }
}