<?php
defined('BASEPATH') or exit('No direct script access allowed');
class Services extends ClientsController
{
    /**
     * Status Paid
     */
    public function __construct()
    {
        parent::__construct();
        $this->load->model('services_model');
        $this->load->model('clients_model');
        $this->load->model('invoices_model');
    }

    // PDF Report with qr code generation logic implemented

    public function report($flag = null, $code = null)
    {

        if (empty($flag)) {
            access_denied('Services');
        }

        if ($flag == 'view' or $flag == 'pdf') {
            if (empty($code)) {
                access_denied('Services');
            }
            $service_info = $this->db->where(array('service_request_code' => $code))->get('tblservice_request')->row();
            $data['service_request_id'] = $service_info->service_request_id;
            $data['calibration_info'] = $this->db->where(array('service_request_id' => $data['service_request_id']))->get('tblservices_calibration')->row();
            $data['service_info'] = $service_info;
            $data['service_request_client'] = $this->clients_model->get($data['service_info']->clientid);
            $data['service_request_code'] = $code;
        } else {
            if (!is_numeric($flag)) {
                access_denied('Services');
            }
            $data['service_info'] = $this->db->where(array('service_request_code' => $code))->get('tblservice_request')->row();
            $data['service_request_code'] = $code;
            // Request details
            $data['service_details'] = $this->db->select('tblservice_request_details.*, tblservices_module.name, tblservice_type.name as category_name, tblservice_type.service_typeid')
                ->where('service_request_id', $data['service_request']->service_request_id)
                ->join('tblservices_module', 'tblservices_module.serviceid = tblservice_request_details.serviceid')
                ->join('tblservice_type', 'tblservices_module.service_type_code = tblservice_type.type_code')
                ->get('tblservice_request_details')->result();
        }

        // View logic
        if ($flag == 'view') {
            $this->app_scripts->theme('sticky-js', 'assets/plugins/sticky/sticky.js');
            $this->disableNavigation();
            $this->disableSubMenu();
            $data['title'] = 'View Service Calibration Report';
            $data['bodyclass'] = 'viewinvoice';
            $this->data($data);
            $this->view('client/report_calibration_view');
            no_index_customers_area();
            $this->layout();

            // PDF logic
        } else if ($flag == 'pdf') {
            $data['title'] = 'Service Calibration Report PDF';
            $request_number = get_option('service_request_prefix') . $code;


            // Generate QR code logic (common for both view and pdf)
            $qr_data = "This Service Report Registration No. REQ-{$code} is a valid report from Measurement Systems Ltd.";
            $this->load->library('ciqrcode');
            $params['data'] = $qr_data;
            $params['level'] = 'L';
            $params['size'] = 2;
            $qr_image_path = FCPATH . 'modules/wonder_pdf_template/assets/images/' . $code . '_qrcode.png';
            $params['savename'] = $qr_image_path;
            $this->ciqrcode->generate($params);


            // Start output buffering before PDF generation
            ob_start();

            try {
                $pdf = service_request_report_pdf($data); // Include $data with QR code
            } catch (Exception $e) {
                log_message('error', 'Error generating PDF with service_request_report_pdf: ' . $e->getMessage());
            }

            $type = 'I';
            if ($this->input->get('print')) {
                $type = 'I';
            }

            $pdf->Output('SERVICE REPORT ' . mb_strtoupper(slug_it($request_number)) . '.pdf', $type);

            // End and clean the output buffer after PDF generation
            // ob_end_clean();
        }
    }

    // PDF rental angreement pdf Report with qr code generation logic implemented
    public function field_report($flag = null, $code = null, $approval = false)
    {
            if (empty($flag)) {
                access_denied('Services');
            }

            if (in_array($flag, ['view', 'pdf'])) {
                if (empty($code)) {
                    access_denied('Services');
                }

                $data['field_report_info'] = $this->services_model->get_field_report($code);

                // Debugging step - check if 'field_report_info' is retrieved correctly
                if (empty($data['field_report_info'])) {
                    log_message('error', 'Field report not found for code: ' . $code);
                    access_denied('Services');
                }

                $data['service_request_client'] = $this->clients_model->get($data['field_report_info']->clientid);
                $data['service_rental_agreement'] = $this->db->where('service_rental_agreement_code', $code)->get('tblservice_rental_agreement')->row();
                $data['service_details'] = $this->db->select('tblservice_rental_agreement_details.*, tblservices_module.name, tblservices_module.rental_serial, tblservice_type.name as category_name, tblservice_type.service_typeid')->where('service_rental_agreement_id', $data['service_rental_agreement']->service_rental_agreement_id)->join('tblservices_module', 'tblservices_module.serviceid = tblservice_rental_agreement_details.serviceid')->join('tblservice_type', 'tblservices_module.service_type_code = tblservice_type.type_code')->get('tblservice_rental_agreement_details')->result();

                $code_arr = explode('-', $code);
                $data['service_info'] = $this->services_model->get_rental_agreement($code_arr[0]);

                // Debugging step - check if service_rental_agreement_id is retrieved correctly
                log_message('debug', 'Field report info: ' . print_r($data['field_report_info'], true));

                // Fetching the service details with the correct ID
                $service_rental_agreement_id = $data['field_report_info']->service_rental_agreement_id;
                if (!empty($service_rental_agreement_id)) {
                    // Call the get_service_details method
                    $data['service_details'] = $this->services_model->get_service_details($service_rental_agreement_id);
                } else {
                    log_message('error', 'Service rental agreement ID not found in field report.');
                    $data['service_details'] = null;  // Handle the error appropriately
                }

                // Additional check - ensure 'service_details' is retrieved correctly
                log_message('debug', 'Service details: ' . print_r($data['service_details'], true));
            } else {
                access_denied('Services');
            }

            if ($flag == 'view') {
                $this->app_scripts->theme('sticky-js', 'assets/plugins/sticky/sticky.js');
                $this->disableNavigation();
                $this->disableSubMenu();
                $data['title'] = 'View Service Field Report';
                $data['bodyclass'] = 'viewinvoice';
                $this->data($data);
                $this->view('client/field_report_view');
                no_index_customers_area();
                $this->layout();
            } else if ($flag == 'pdf') {
                // Ensure service details are included in PDF generation
                $data['title'] = 'Service Calibration Report PDF';
                $request_number = get_option('service_rental_agreement_prefix') . $code;
                $data['service_rental_agreement'] = $this->db->where('service_rental_agreement_code', $code)->get('tblservice_rental_agreement')->row();


                // Prepare data for PDF
                $data['service_rental_agreement'] = $data['service_info']; // Rental agreement information
                $data['service_rental_agreement_client'] = $data['service_request_client']; // Client information
                $data['service_details'] = $this->db->select('tblservice_rental_agreement_details.*, tblservices_module.name, tblservices_module.rental_serial, tblservice_type.name as category_name, tblservice_type.service_typeid')->where('service_rental_agreement_id', $data['service_rental_agreement']->service_rental_agreement_id)->join('tblservices_module', 'tblservices_module.serviceid = tblservice_rental_agreement_details.serviceid')->join('tblservice_type', 'tblservices_module.service_type_code = tblservice_type.type_code')->get('tblservice_rental_agreement_details')->result();
                $data['field_report_info'] = $this->services_model->get_field_report($code);
                $data['service_rental_request_code'] = $code;

                // Generate QR code logic (common for both view and pdf)
                $qr_data = "This Rental Agreement Report Registration No. ARG-{$code} is a valid report from Measurement Systems Ltd.";
                $this->load->library('ciqrcode');
                $params['data'] = $qr_data;
                $params['level'] = 'L';
                $params['size'] = 2;
                $qr_image_path = FCPATH . 'modules/wonder_pdf_template/assets/images/' . $code . '_qrcode.png';
                $params['savename'] = $qr_image_path;
                $this->ciqrcode->generate($params);

                if (file_exists($qr_image_path)) {
                    $qr_code_base64 = base64_encode(file_get_contents($qr_image_path));
                    $data['qr_code_base64'] = $qr_code_base64;
                } else {
                    log_message('error', 'QR code image not found: ' . $qr_image_path);
                    $data['qr_code_base64'] = '';
                }

                // Start PDF generation
                ob_start();
                try {
                    $pdf = service_rental_agreement_pdf($data); // Pass all required data
                } catch (Exception $e) {
                    log_message('error', 'Error generating PDF with service_rental_agreement_pdf: ' . $e->getMessage());
                }

                $type = 'I';
                if ($this->input->get('print')) {
                    $type = 'I';
                }

                log_message('debug', print_r($data, true)); // Log data to debug

                $pdf->Output('RENTAL FIELD REPORT ' . mb_strtoupper(slug_it($request_number)) . '.pdf', $type);
            } 
    }

    public function certificate($flag = null, $code = null)
    {

        if (empty($flag)) {
            access_denied('Services');
        }

        if ($flag == 'validate') {
            if (empty($code)) {
                access_denied('Services');
            }
            $service_info = $this->db->where(array('service_request_code' => $code))->get('tblservice_request')->row();
            $data['service_request_id'] = $service_info->service_request_id;
            $data['calibration_info'] = $this->db->where(array('service_request_id' => $data['service_request_id']))->get('tblservices_calibration')->row();
            $data['service_info'] = $service_info;
            $data['service_request_client'] = $this->clients_model->get($data['service_info']->clientid);
            $data['service_request_code'] = $code;

            //view logic
            
            $this->app_scripts->theme('sticky-js', 'assets/plugins/sticky/sticky.js');
            $this->disableNavigation();
            $this->disableSubMenu();
            $data['title'] = 'Verify MSL Certificates';
            $data['bodyclass'] = 'viewinvoice';
            $this->data($data);
            $this->view('client/certificate_verification');
            no_index_customers_area();
            $this->layout();

        } else {
                access_denied('Services');
             }

    }
}
