<?php defined('BASEPATH') or exit('No direct script access allowed');

// ==========================================================
// 1. V3 ENHANCEMENTS
// ==========================================================

if (!function_exists('get_review_qr_code')) {
    function get_review_qr_code($rel_type, $rel_id) {
        $CI = &get_instance(); $token = '';
        if ($rel_type == 'invoice') $token = $CI->db->select('service_review_token')->where('id', $rel_id)->get('tblinvoices')->row('service_review_token');
        elseif ($rel_type == 'rental') $token = $CI->db->select('service_review_token')->where('service_rental_agreement_id', $rel_id)->get('tblservice_rental_agreement')->row('service_review_token');
        elseif ($rel_type == 'request') $token = $CI->db->select('service_review_token')->where('service_request_id', $rel_id)->get('tblservice_request')->row('service_review_token');

        if (!$token) return '';
        $url = site_url('bizit_services_msl/reviews/rate/' . $token);
        $CI->load->library('ciqrcode'); 
        $path = FCPATH.'uploads/temp/qr_'.$rel_type.'_'.$rel_id.'.png';
        if(!is_dir(FCPATH.'uploads/temp/')) mkdir(FCPATH.'uploads/temp/', 0755);
        $CI->ciqrcode->generate(['data'=>$url, 'savename'=>$path, 'size'=>2]);
        return file_exists($path) ? '<img src="data:image/png;base64,'.base64_encode(file_get_contents($path)).'" />' : '';
    }
}

if (!function_exists('bizit_check_field_report_reminders')) {
    function bizit_check_field_report_reminders() {
        $CI = &get_instance();
        $rentals = $CI->db->query("SELECT t1.field_operator, t1.service_rental_agreement_code FROM tblservice_rental_agreement t1 LEFT JOIN tblfield_report t2 ON t1.service_rental_agreement_id = t2.service_rental_agreement_id WHERE t1.start_date <= DATE_SUB(NOW(), INTERVAL 2 DAY) AND (t1.status = 0 OR t1.status = 3) AND (t2.field_report_id IS NULL OR t2.status = 1) AND t1.field_operator IS NOT NULL")->result();
        foreach ($rentals as $r) add_notification(['fromuserid'=>0, 'touserid'=>$r->field_operator, 'description'=>'Reminder: Pending Field Report', 'link'=>'services/view_rental_agreement/'.$r->service_rental_agreement_code]);
    }
}

if (!function_exists('bizit_check_calibration_reminders')) {
    function bizit_check_calibration_reminders() {
        $CI = &get_instance();
        $due = $CI->db->where('next_calibration_date', date('Y-m-d', strtotime('+7 days')))->get('tblservices_calibration')->result();
        foreach($due as $cal) log_activity('Calibration Reminder Due: Request #'.$cal->service_request_id);
    }
}

// ==========================================================
// 2. CORE BUSINESS LOGIC
// ==========================================================

if (!function_exists('_raise_service_invoices')) {
    function _raise_service_invoices($code, $service_code, $request_table = '', $description = "", $draft = false)
    {
        $CI = &get_instance();
        $CI->load->model('invoices_model');
        $CI->load->model('clients_model');

        if (!isset($service_code)) return false;
        
        $service_request = $CI->db->where('request_code', $code)->get($request_table)->row();
        $qty_amt = 1;
        
        // Calculate Quantity Logic
        if (isset($service_request->hours) && isset($service_request->licences)) {
            $time_sub = $service_request->hours;
            if ($service_code == "004-0002") $time_sub = ($service_request->hours / 24);
            else if ($service_code == "004-0003") $time_sub = ($service_request->hours / 24 / 30);
            else if ($service_code == "004-0004") $time_sub = ($service_request->hours / 24 / 365);
            $qty_amt = $time_sub * $service_request->licences;
        }

        // Get Service Details
        $service_details = $CI->db->select('m.name, m.price, m.quantity_unit, m.description, m.service_code, t.name as category_name')
            ->from('tblservices_module m')
            ->join('tblservice_type t', 'm.service_type_code = t.type_code')
            ->where('m.service_code', $service_code)
            ->get()->result();

        $newitems = [];
        $subtotal = 0;
        $i = 1;

        foreach ($service_details as $value) {
            $newitems[$i] = [
                "order" => $i,
                "description" => $value->name,
                "long_description" => $value->category_name . ', Service Code: ' . $value->service_code . '<br/>' . (!empty($description) ? $description : $value->description),
                "qty" => $qty_amt,
                "unit" => empty($value->quantity_unit) ? "Service" : $value->quantity_unit,
                "rate" => $value->price,
                "taxname" => [get_option('general_services_tax')], 
                "item_for" => "3"
            ];
            $subtotal += $value->price * $qty_amt;
            $i++;
        }

        $service_request_client = $CI->clients_model->get($service_request->clientid);
        $next_invoice_number = get_option('next_invoice_number');
        $number = str_pad($next_invoice_number, get_option('number_padding_prefixes'), '0', STR_PAD_LEFT);

        $invoice_data = [
            "clientid" => $service_request_client->userid,
            "number" => $number,
            "date" => _d(date('Y-m-d')),
            "currency" => get_default_currency('id'),
            "subtotal" => $subtotal,
            "total" => $subtotal,
            "newitems" => $newitems,
            "save_as_draft" => $draft ? "true" : "false",
            "clientnote" => get_option('predefined_clientnote_invoice'),
            "terms" => get_option('predefined_terms_invoice')
        ];

        // --- RESTORED: INSERT TO DB ---
        $id = $CI->invoices_model->add($invoice_data);
        if ($id) {
            $CI->db->where('id', $service_request->id)->update($request_table, ['invoice_rel_id' => $id]);
            return $id;
        }
        return false;
    }
}

if (!function_exists('set_verification_qrcode')) {
    function set_verification_qrcode($data)
    {
        $CI = &get_instance();
        $CI->load->library('ciqrcode');
        $filePath = FCPATH . 'uploads/temp/temp_qr_'.uniqid().'.png';
        if(!is_dir(FCPATH.'uploads/temp/')) mkdir(FCPATH.'uploads/temp/', 0755);

        $codeContents = isset($data->verificationUrl) ? $data->verificationUrl : '';
        $CI->ciqrcode->generate(['data'=>$codeContents, 'savename'=>$filePath, 'size'=>2]);

        $qrImage = file_get_contents($filePath);
        unlink($filePath);
        return base64_encode($qrImage);
    }
}

if (!function_exists('show_verification_qrcode')) {
    function show_verification_qrcode($data)
    {
        if (!empty($data)) {
            $data_obj = json_decode(json_encode($data));
            $img_base64_encoded = 'data:image/png;base64,' . set_verification_qrcode($data_obj);
            return '<img src="@' . preg_replace('#^data:image/[^;]+;base64,#', '', $img_base64_encoded) . '" width="80px" style="margin:0 auto;">';
        }
        return '';
    }
}

// ==========================================================
// 3. UTILITIES & PDF LOADERS
// ==========================================================

if (!function_exists('dms2dec')) {
    function dms2dec($deg, $min, $sec) { return (double)$deg + ((((double)$min * 60) + ((double)$sec)) / 3600); }
}

if (!function_exists('get_currency_symbol')) {
    function get_currency_symbol($id = false) {
        $CI = &get_instance();
        if(!function_exists('get_currency_symbol')) $CI->load->helper('invoices');
        if(!function_exists('app_format_money')) $CI->load->helper('number');
        return function_exists('get_currency_symbol') ? get_currency_symbol($id) : '$';
    }
}

if (!function_exists('get_next_service_category_code_internal')) {
    function get_next_service_category_code_internal() {
        $CI = &get_instance();
        $q = $CI->db->get('tblservice_type')->result();
        $last = empty($q) ? 0 : (int)end($q)->type_code;
        return sprintf("%03d", $last + 1);
    }
}

// PDF Wrappers
if(!function_exists('service_request_pdf')){ 
    function service_request_pdf($data){ 
        $CI=&get_instance(); 
        $CI->load->library(BIZIT_SERVICES_MSL.'/pdf');
        $pdf = new Pdf('P', 'mm', 'A4', true, 'UTF-8', false);
        $pdf->SetTitle('Service Request');
        $pdf->load_view('wonder_pdf_template/pdf/gamma/my_service_requestpdf', $data); 
        return $pdf;
    } 
}
if(!function_exists('service_rental_agreement_pdf')){ 
    function service_rental_agreement_pdf($data){ 
        $CI=&get_instance(); 
        $CI->load->library(BIZIT_SERVICES_MSL.'/pdf'); 
        $pdf = new Pdf('P', 'mm', 'A4', true, 'UTF-8', false);
        $pdf->SetTitle('Rental Agreement');
        $pdf->load_view('wonder_pdf_template/pdf/gamma/my_service_rental_agreementpdf', $data); 
        return $pdf;
    } 
}
if(!function_exists('delivery_note_pdf')){ 
    function delivery_note_pdf($data){ 
        $CI=&get_instance(); 
        $CI->load->library(BIZIT_SERVICES_MSL.'/pdf'); 
        $pdf = new Pdf('P', 'mm', 'A4', true, 'UTF-8', false);
        $pdf->SetTitle('Delivery Note');
        $pdf->load_view('wonder_pdf_template/pdf/gamma/my_delivery_notepdf', $data); 
        return $pdf;
    } 
}
if(!function_exists('inventory_checklist_pdf')){ 
    function inventory_checklist_pdf($data){ 
        $CI=&get_instance(); 
        $CI->load->library(BIZIT_SERVICES_MSL.'/pdf'); 
        $pdf = new Pdf('P', 'mm', 'A4', true, 'UTF-8', false);
        $pdf->SetTitle('Inventory Checklist');
        $pdf->load_view('wonder_pdf_template/pdf/gamma/my_inventory_checklistpdf', $data); 
        return $pdf;
    } 
}
if(!function_exists('service_request_report_pdf')){ 
    function service_request_report_pdf($data){ 
        $CI=&get_instance(); 
        $CI->load->library(BIZIT_SERVICES_MSL.'/pdf'); 
        $pdf = new Pdf('P', 'mm', 'A4', true, 'UTF-8', false);
        $pdf->SetTitle('Calibration Report');
        $pdf->load_view('wonder_pdf_template/pdf/gamma/my_service_request_reportpdf', $data); 
        return $pdf;
    } 
}

if (!function_exists('handle_service_report_attachments')) {
    function handle_service_report_attachments($type, $id)
    {
        if (isset($_FILES['file']) && _perfex_upload_error($_FILES['file']['error'])) {
            header('HTTP/1.0 400 Bad error');
            echo _perfex_upload_error($_FILES['file']['error']);
            die;
        }
        $path = get_upload_path_by_type($type) . $id . '/';
        $CI = &get_instance();
        if (isset($_FILES['file']['name'])) {
            hooks()->do_action('before_upload_service_report_attachment', $id);
            $tmpFilePath = $_FILES['file']['tmp_name'];
            if (!empty($tmpFilePath) && $tmpFilePath != '') {
                if (!file_exists($path)) { mkdir($path, 0755, true); fopen($path . 'index.html', 'w'); }
                $filename = unique_filename($path, $_FILES["file"]["name"]);
                if (move_uploaded_file($tmpFilePath, $path . $filename)) {
                    $attachment = [['file_name' => $filename, 'filetype' => $_FILES["file"]["type"]]];
                    $CI->misc_model->add_attachment_to_database($id, $type, $attachment);
                }
            }
        }
    }
}

// ==========================================================
// 4. RESTORED NOTIFICATION FUNCTIONS
// ==========================================================

if (!function_exists('rental_agreement_notifications')) {
    function rental_agreement_notifications($to_staff_id, $from_user_id, $code, $type, $site_name)
    {
        $CI = &get_instance();
        $description = '';
        $link = 'services/view_rental_agreement/' . $code;

        if ($type == 'field_operator_notice') {
            $description = _l('not_field_operator_assigned', [$site_name]);
        } elseif ($type == 'field_operator_removal_notice') {
            $description = _l('not_field_operator_removed', [$site_name]);
        }

        if ($description != '') {
            add_notification([
                'description' => $description,
                'touserid' => $to_staff_id,
                'fromuserid' => $from_user_id,
                'link' => $link,
            ]);
        }
    }
}

if (!function_exists('rental_agreement_report_notifications')) {
    function rental_agreement_report_notifications($to_staff_id, $from_user_id, $code, $type, $site_name, $is_staff = true)
    {
        $CI = &get_instance();
        $description = '';
        $link = 'services/field_report/view/' . $code;

        if ($type == 'approval_notice') {
            $description = _l('not_report_approval_request', [$site_name]);
        } elseif ($type == 'field_report_approved') {
            $description = _l('not_report_approved', [$site_name]);
        } elseif ($type == 'field_report_rejected') {
            $description = _l('not_report_rejected', [$site_name]);
        }

        if ($description != '') {
            add_notification([
                'description' => $description,
                'touserid' => $to_staff_id,
                'fromuserid' => $from_user_id,
                'link' => $link,
            ]);
        }
    }
}
