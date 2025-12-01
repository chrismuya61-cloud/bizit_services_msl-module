<?php

defined('BASEPATH') or exit('No direct script access allowed');

class Reviews extends ClientsController
{
    public function __construct()
    {
        parent::__construct();
        // Load models to access rental/request data if needed
        $this->load->model('bizit_services_msl/rentals_model');
        $this->load->model('bizit_services_msl/requests_model');
    }

    public function rate($token)
    {
        if (empty($token)) {
            show_404();
        }

        $rel_type = '';
        $rel_id = 0;
        $client_id = 0;
        $engineer_id = 0;
        $title_text = '';

        // 1. Check Invoices (Technical Support)
        $inv = $this->db->where('service_review_token', $token)->get('tblinvoices')->row();
        if ($inv) {
            $rel_type = 'invoice';
            $rel_id = $inv->id;
            $client_id = $inv->clientid;
            $engineer_id = $inv->technical_engineer_id; // Capture for Commission
            $title_text = 'Technical Support (Invoice #' . format_invoice_number($inv->id) . ')';
        } 
        // 2. Check Rental Agreements
        else {
            $rent = $this->db->where('service_review_token', $token)->get('tblservice_rental_agreement')->row();
            if ($rent) {
                $rel_type = 'rental';
                $rel_id = $rent->service_rental_agreement_id;
                $client_id = $rent->clientid;
                $engineer_id = $rent->field_operator; // Capture for Commission
                $title_text = 'Rental Agreement #' . $rent->service_rental_agreement_code;
            } 
            // 3. Check Service Requests
            else {
                $req = $this->db->where('service_review_token', $token)->get('tblservice_request')->row();
                if ($req) {
                    $rel_type = 'request';
                    $rel_id = $req->service_request_id;
                    $client_id = $req->clientid;
                    $engineer_id = $req->received_by; // Capture for Commission
                    $title_text = 'Service Request #' . $req->service_request_code;
                }
            }
        }

        if (empty($rel_type)) {
            show_404();
        }

        // Handle Review Submission
        if ($this->input->post()) {
            $data = [
                'rel_type'     => $rel_type,
                'rel_id'       => $rel_id,
                'client_id'    => $client_id,
                'engineer_id'  => $engineer_id, // Vital for Dashboard
                'rating'       => $this->input->post('rating'),
                'comment'      => $this->input->post('comment'),
                'date_created' => date('Y-m-d H:i:s'),
                'ip_address'   => $this->input->ip_address()
            ];
            
            $this->db->insert('tblservice_client_reviews', $data);
            
            // Optional: Log Activity
            log_activity("New Service Review Submitted [Type: $rel_type, ID: $rel_id, Rating: " . $data['rating'] . "]");

            redirect(site_url('bizit_services_msl/reviews/thank_you'));
        }

        // Pass data to View
        $data['title_text'] = $title_text;
        $data['title'] = 'Service Review';
        
        $this->data($data);
        $this->view('client/review_form');
        $this->layout();
    }

    public function thank_you()
    {
        $data['title'] = 'Thank You';
        $this->view('client/review_thank_you'); 
        $this->layout();
    }
}