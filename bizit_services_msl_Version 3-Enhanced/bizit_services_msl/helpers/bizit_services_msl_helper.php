<?php defined('BASEPATH') or exit('No direct script access allowed');

// ==========================================================
// 1. V3 ENHANCEMENTS (QR, Reminders)
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
// 2. V1 LEGACY LOGIC (Restored)
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

        if (isset($service_request->hours) && isset($service_request->licences)) {
            $time_sub = $service_request->hours;
            if ($service_code == "004-0002") $time_sub = ($service_request->hours / 24);
            else if ($service_code == "004-0003") $time_sub = ($service_request->hours / 24 / 30);
            else if ($service_code == "004-0004") $time_sub = ($service_request->hours / 24 / 365);
            $qty_amt = $time_sub * $service_request->licences;
        }
        return true; 
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

// ==========================================================
// 4. RESTORED NOTIFICATION FUNCTIONS (REQUIRED)
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