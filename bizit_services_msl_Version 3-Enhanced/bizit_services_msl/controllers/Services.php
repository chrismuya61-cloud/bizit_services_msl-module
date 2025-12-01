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
        $this->load->helper(['bizit_services_msl', 'invoices']);
    }

    public function index()
    {
        show_404();
    }

    /**
     * View Calibration Certificate (Simple Verification)
     */
    public function certificate($code = null)
    {
        if (empty($code)) show_404();

        $data['service_request'] = $this->requests_model->get_request($code);
        if (!$data['service_request']) show_404();

        $data['title'] = 'Certificate Verification';
        $this->data($data);
        $this->view('client/certificate_verification');
        $this->layout();
    }
    
    /**
     * View Full Calibration Report (Detailed Readings)
     * Missing in previous batch
     */
    public function report($code = null)
    {
        if(empty($code)) show_404();
        
        $req = $this->requests_model->get_request($code);
        if(!$req) show_404();
        
        $data['service_request'] = $req;
        $data['service_info'] = $req; // Alias for view compatibility
        $data['calibration_info'] = $this->requests_model->get_report_check($req->service_request_id);
        $data['service_request_client'] = $this->clients_model->get($req->clientid);
        
        $data['title'] = 'Calibration Report';
        $this->data($data);
        $this->view('client/report_calibration_view');
        $this->layout();
    }

    /**
     * View Field Report (For Rental Clients)
     */
    public function field_report($code = null)
    {
        if (empty($code)) show_404();

        $report = $this->reports_model->get_field_report($code);
        if (!$report) show_404();

        $data['field_report_info'] = $report;
        
        // Get Equipment Details manually via Query to avoid loading Admin Rental Model
        $data['service_details'] = $this->db->select('d.*, m.name, m.rental_serial, d.price as description')
            ->from('tblservice_rental_agreement_details d')
            ->join('tblservices_module m', 'm.serviceid=d.serviceid')
            ->where('service_rental_agreement_id', $report->service_rental_agreement_id)
            ->get()->result();

        $data['title'] = 'Field Report View';
        $this->data($data);
        $this->view('client/field_report_view');
        $this->layout();
    }
}