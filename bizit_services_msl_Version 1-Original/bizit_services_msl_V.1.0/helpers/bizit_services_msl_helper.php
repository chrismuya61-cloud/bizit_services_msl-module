<?php
defined('BASEPATH') or exit('No direct script access allowed');


if (!function_exists('_raise_service_invoices')) {
    /**
     * Raise Sevice Invoice
     * @param  [type] $service_id    [description]
     * @param  string $request_table [description]
     * @param  string $description   [description]
     * @return [invoice_id]                [description]
     */
    function _raise_service_invoices($code, $service_code, $request_table = '', $description = "", $draft = false)
    {
        $CI = &get_instance();
        $CI->load->model('invoices_model');
        $CI->load->model('clients_model');

        if (!isset($service_code)) {
            return false;
        }
        //Get service
        $service_request = $CI->db->where('request_code', $code)->get($request_table)->row();
        $qty_amt = 1;
        if (isset($service_request->hours) && isset($service_request->licences)) {
            $time_sub = $service_request->hours;

            if ($service_code == "004-0002") {
                $time_sub = ($service_request->hours / 24);
            } else if ($service_code == "004-0003") {
                $time_sub = ($service_request->hours / 24) / 30;
            }

            $qty_amt = $time_sub * $service_request->licences;
        }

        //request details
        $service_details = $CI->db->select(db_prefix() . 'services_module.name, ' . db_prefix() . 'services_module.price, ' . db_prefix() . 'services_module.quantity_unit, ' . db_prefix() . 'services_module.description, ' . db_prefix() . 'services_module.service_code, ' . db_prefix() . 'service_type.name as category_name, ' . db_prefix() . 'service_type.service_typeid')->where(db_prefix() . 'services_module.service_code', $service_code)->join(db_prefix() . 'service_type', db_prefix() . 'services_module.service_type_code = tblservice_type.type_code')->get(db_prefix() . 'services_module')->result();

        $i        = 1;
        $newitems = array();
        $subtotal = 0;
        $total = 0;
        foreach ($service_details as $key => $value) {
            $newitems[$i] = array(
                "order" => $i,
                "description" => $value->name,
                "long_description" => $value->category_name . ', Service Code => ' . $value->service_code . '<br/>' . !empty($description) ? $description : $value->description,
                "serial" => "",
                "product_id" => "",
                "qty" => $qty_amt,
                "unit" => empty($value->quantity_unit) ? "Service" : $value->quantity_unit,
                "rate" => $value->price,
                "taxname" => [get_option('general_services_tax')],
                "taxable" => "1",
                "item_for" => "3"
            );
            $subtotal += $value->price * $qty_amt;
            $tax_arr = explode('|', get_option('general_services_tax'));
            $total += $subtotal + ($subtotal * (float)$tax_arr[1] / 100);
            $i++;
        }

        //Client Details
        $service_request_client = $CI->clients_model->get($service_request->clientid);

        //Invoice Number
        $next_invoice_number = get_option('next_invoice_number');
        $format              = get_option('invoice_number_format');
        $prefix              = get_option('invoice_prefix');

        $next_invoice_number = invoice_number_update($next_invoice_number);

        if ($format == 1) {
            // Number based
            $__number = $next_invoice_number;
        } else {
            $__number = $next_invoice_number;
            $prefix   = $prefix . '<span id="prefix_year">' . date('Y') . '</span>/';
        }

        $_invoice_number = str_pad($__number, get_option('number_padding_prefixes'), '0', STR_PAD_LEFT);


        //Payment Modes
        $CI->load->model('payment_modes_model');
        $payment_modes         = $CI->payment_modes_model->get('', array(
            'expenses_only !=' => 1
        ));
        $allowed_payment_modes = array();
        if (count($payment_modes) > 0) {
            foreach ($payment_modes as $mode) {
                if ($mode['selected_by_default'] == 1) {
                    $allowed_payment_modes[] = $mode['id'];
                }
            }
        }

        $adjustment = 0;
        if ($total > 0) {
            if (is_float($total)) {
                $decimal = fmod($total, 1);
                $adjustment = 1 - round($decimal, 2);
            }
        }

        //Generate Invoice Data
        $invoice_data = array(
            "clientid" => $service_request_client->userid,
            "invoice_for" => "3",
            "project_id" => "",
            "billing_street" => $service_request_client->billing_street,
            "billing_city" => $service_request_client->billing_city,
            "billing_state" => $service_request_client->billing_state,
            "billing_zip" => $service_request_client->billing_zip,
            "billing_country" => $service_request_client->billing_country,
            //"include_shipping" => "on",
            "show_shipping_on_invoice" => "on",
            "shipping_street" => $service_request_client->shipping_street,
            "shipping_city" => $service_request_client->shipping_city,
            "shipping_state" => $service_request_client->shipping_state,
            "shipping_zip" => $service_request_client->shipping_zip,
            "shipping_country" => $service_request_client->shipping_country,
            "number" => $_invoice_number,
            "date" => _d(date('Y-m-d')),
            "duedate" => get_option('invoice_due_after') != 0 ? _d(date('Y-m-d', strtotime('+' . get_option('invoice_due_after') . ' DAY', strtotime(date('Y-m-d'))))) : "",
            "allowed_payment_modes" => $allowed_payment_modes,
            "currency" => get_default_currency('id'),
            "sale_agent" => "",
            "recurring" => "0",
            "discount_type" => "",
            "repeat_every_custom" => "1",
            "repeat_type_custom" => "day",
            "adminnote" => "",
            "item_select" => "",
            "task_select" => "",
            "show_quantity_as" => "1",
            "description" => "",
            "long_description" => "",
            "quantity" => "1",
            "unit" => "",
            "rate" => "",
            "taxname" => "VAT|16.00",
            "newitems" => $newitems,
            "subtotal" => $subtotal,
            "discount_percent" => "0",
            "discount_total" => "0",
            "adjustment" => $adjustment,
            "total" => $total + $adjustment,
            "task_id" => "",
            "expense_id" => "",
            "clientnote" => get_option('predefined_clientnote_invoice'),
            "terms" => get_option('predefined_terms_invoice')
        );

        if ($draft) {
            $invoice_data["save_as_draft"] = "true";
        }

        //echo '<pre>'.json_encode($invoice_data); exit;

        $id = $CI->invoices_model->add($invoice_data);
        if ($id) {
            $CI->db->where('id', $service_request->id)->update($request_table, array(
                'invoice_rel_id' => $id
            ));
            set_alert('success', _l('added_successfully', _l('invoice')));
            return $id;
        }

        return false;
    }
}

if (!function_exists('get_default_currency')) {
    function get_default_currency($check)
    {
        $CI = &get_instance();
        $CI->db->where('isdefault', 1);

        return $CI->db->get(db_prefix() . 'currencies')->row()->$check;
    }
}

if (!function_exists('copyfolder')) {
    /**
     * Copy Entire folder and its files
     *
     * @param  mixed $from
     * @param  mixed $to
     * @return void
     */
    function copyfolder($from, $to)
    {
        // (A1) SOURCE FOLDER CHECK
        if (!is_dir($from)) {
            exit("$from does not exist");
        }

        // (A2) CREATE DESTINATION FOLDER
        if (!is_dir($to)) {
            if (!mkdir($to)) {
                exit("Failed to create $to");
            };
            //echo "$to created\r\n";
        }

        // (A3) COPY FILES + RECURSIVE INTERNAL FOLDERS
        $dir = opendir($from);
        while (($ff = readdir($dir)) !== false) {
            if ($ff != "." && $ff != "..") {
                if (is_dir("$from$ff")) {
                    copyfolder("$from$ff/", "$to$ff/");
                } else {
                    if (!copy("$from$ff", "$to$ff")) {
                        exit("Error copying $from$ff to $to$ff");
                    }
                    //echo "$from$ff copied to $to$ff\r\n";
                }
            }
        }
        closedir($dir);
    }
}


if (!function_exists('get_staff_with_permission')) {
    /**
     * Get staff member/s with permissions
     * @param  [type] $permission [description]
     * @param  string $can        [description]
     * @param  array  $where      [description]
     * @return [type]             [description]
     */
    function get_staff_with_permission($permission, $can = '', $where = array())
    {
        $CI = &get_instance();
        $CI->load->model('staff_model');
        $staff_arr = $CI->staff_model->get('', $where);
        $filterd_staff_arr = array();
        foreach ($staff_arr as $key => $staff) {
            //if (staff_can($can, $permission, $staff['staffid'])){
            $filterd_staff_arr[] = $staff;
            //}
        }
        return $filterd_staff_arr;
    }
}


//Confirm Report
function check_report($id, $type = 'service_report')
{

    // Get a reference to the controller object
    $CI = get_instance();
   
    // Call a function of the model
    $check = null;
    if( $type == 'service_report') {
       $check = $CI->services_model->get_report_check($id);
    } else if( $type == 'field_report') {
       $check = $CI->services_model->get_field_report_check($id);
    }
    if (!empty($check)) {
        return $check;
    }
}

//Coordinate CONVERTER

function dms2dec($deg, $min, $sec)
{

    // Converts DMS ( Degrees / minutes / seconds )
    // to decimal format longitude / latitude

    return (double)$deg + ((((double)$min * 60) + ((double)$sec)) / 3600);
}

function dec2dms($dec)
{

    // Converts decimal longitude / latitude to DMS
    // ( Degrees / minutes / seconds )

    // This is the piece of code which may appear to
    // be inefficient, but to avoid issues with floating
    // point math we extract the integer part and the float
    // part by using a string function.

    $vars = explode(".", $dec);
    $deg = $vars[0];
    $tempma = "0." . $vars[1];

    $tempma = $tempma * 3600;
    $min = floor($tempma / 60);
    $sec = $tempma - ($min * 60);

    return array("deg" => $deg, "min" => $min, "sec" => $sec);
}

function dec2dms_full($dec)
{

    // Converts decimal longitude / latitude to DMS
    // ( Degrees / minutes / seconds )

    // This is the piece of code which may appear to
    // be inefficient, but to avoid issues with floating
    // point math we extract the integer part and the float
    // part by using a string function.

    $vars = explode(".", $dec);
    $deg = $vars[0];
    if (isset($vars[1])) {
        $tempma = "0." . $vars[1];
    } else {
        $tempma = 0;
    }

    $tempma = $tempma * 3600;
    $min = floor($tempma / 60);
    $sec = $tempma - ($min * 60);

    return $deg . "&deg; " . $min . "' " . round($sec) . '"';
}


function pdf_signatures($id = null)
{
    if (empty($id)) {
        $id = get_option('default_e_sign');
    }

    $CI = &get_instance();
    $CI->db->select('e_sign,firstname,lastname');
    $CI->db->where('staffid', $id);
    $result = $CI->db->get('tblstaff')->row();

    $section = '
  <div>
  <div>Signature:</div>
  <div>' . staff_e_sign($id, array('img', 'img-responsive', 'staff-profile-image-thumb'), '', ["width" => "150px"]) . '</div>
  <div>..................................................................</div>
  <div><h3>' . $result->firstname . ' ' . $result->lastname . '</h3></div>
  </div>';

    return $section;
}

/**
 * Rental Agreement Notifications
 * @param  [type]  $to_user                       [description]
 * @param  [type]  $from_user                     [description]
 * @param  [type]  $service_rental_agreement_code [description]
 * @param  [type]  $description                   [description]
 * @param  [type]  $site_name                     [description]
 * @param  boolean $from_company                  [description]
 * @return [type]                                 [description]
 */
function rental_agreement_notifications($to_user, $from_user, $service_rental_agreement_code, $description, $site_name = null, $from_company = false)
{
    $at_site_name = $site_name != null ? ' at ' . $site_name : '';
    $notifiedUsers = array();
    $notice_content = array(
        'touserid' => $to_user, //staff id
        'fromuserid' => $from_user,
        'from_fullname' => get_staff_full_name($from_user),
        'link' => 'services/view_rental_agreement/' . $service_rental_agreement_code,
        'description' => $description,
        'additional_data' => serialize(array(' Service Rental Agreement: #' . get_option('service_rental_agreement_prefix') . $service_rental_agreement_code . $at_site_name)),
    );

    if ($from_company) {
        $notice_content['fromcompany'] = $from_company;
    }

    $notified = add_notification($notice_content);

    if ($notified) {
        array_push($notifiedUsers, $to_user);
    }

    pusher_trigger_notification($notifiedUsers);
}

/**
 * Field Report Notifications
 * @param  [type]  $to_user      [description]
 * @param  [type]  $from_user    [description]
 * @param  [type]  $report_code  [description]
 * @param  [type]  $description  [description]
 * @param  [type]  $site_name    [description]
 * @param  boolean $from_company [description]
 * @return [type]                [description]
 */
function rental_agreement_report_notifications($to_user, $from_user, $report_code, $description, $site_name = null, $from_company = false, $approval = false)
{
    $at_site_name = $site_name != null ? ' at ' . $site_name : '';
    $approval_link = $approval ? '/' . $approval : '';
    $notifiedUsers = array();
    $notice_content = array(
        'touserid' => $to_user, //staff id
        'fromuserid' => $from_user,
        'from_fullname' => get_staff_full_name($from_user),
        'link' => 'services/field_report/view/' . $report_code . $approval_link,
        'description' => $description,
        'additional_data' => serialize(array(' Service Field Report: #' . get_option('service_rental_agreement_prefix') . $report_code . $at_site_name)),
    );

    if ($from_company) {
        $notice_content['fromcompany'] = $from_company;
    }

    $notified = add_notification($notice_content);

    if ($notified) {
        array_push($notifiedUsers, $to_user);
    }

    pusher_trigger_notification($notifiedUsers);
}

/**
 * [handle_service_report_attachments description]
 * @param  [type] $id [description]
 * @return [type]     [description]
 */
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
        // Get the temp file path
        $tmpFilePath = $_FILES['file']['tmp_name'];
        // Make sure we have a filepath
        if (!empty($tmpFilePath) && $tmpFilePath != '') {
            // Setup our new file path
            if (!file_exists($path)) {
                mkdir($path);
                fopen($path . 'index.html', 'w');
            }
            $filename = $_FILES["file"]["name"];
            $newFilePath = $path . $filename;
            // Upload the file into the temp dir
            if (move_uploaded_file($tmpFilePath, $newFilePath)) {
                $attachment = array();
                $attachment[] = array(
                    'file_name' => $filename,
                    'filetype' => $_FILES["file"]["type"],
                );

                $CI->misc_model->add_attachment_to_database($id, $type, $attachment);
            }
        }
    }
}


/**
 * Prepare service request pdf
 * @param  object $invoice Invoice as object with all necessary fields
 * @return mixed object
 */
function service_request_pdf($data, $tag = '')
{
    $CI = &get_instance();

    $service_request = $data['service_request'];
    $service_request_details = $data['service_details'];
    $service_request_client = $data['service_request_client'];
    $pre_inspection_items = $data['pre_inspection_items'];
    $post_inspection_items = $data['post_inspection_items'];
    $checklist_items = $data['checklist_items'];
    $released_info = $data['released_info'];
    $accessories = $data['existing_accessories']; // Accessory information



    load_pdf_language($service_request->clientid);

    $request_number = get_option('service_request_prefix') . $service_request->service_request_code;

    $font_name      = get_option('pdf_font');
    $font_size      = get_option('pdf_font_size');

    if ($font_size == '') {
        $font_size = 10;
    }

    $formatArray = get_pdf_format('pdf_format_invoice');
    if (!file_exists(APPPATH . 'libraries/service_request_pdf.php')) {
        $pdf         = new Pdf($formatArray['orientation'], 'mm', $formatArray['format'], true, 'UTF-8', false, false, 'invoice');
    } else {
        include_once(APPPATH . 'libraries/service_request_pdf.php');
        $pdf         = new Invoice_pdf($formatArray['orientation'], 'mm', $formatArray['format'], true, 'UTF-8', false, false);
    }

    if (defined('APP_PDF_MARGIN_LEFT') && defined('APP_PDF_MARGIN_TOP') && defined('APP_PDF_MARGIN_RIGHT')) {
        $pdf->SetMargins(APP_PDF_MARGIN_LEFT, APP_PDF_MARGIN_TOP, APP_PDF_MARGIN_RIGHT);
    }

    $pdf->SetTitle($request_number);

    $pdf->SetAutoPageBreak(true, (defined('APP_PDF_MARGIN_BOTTOM') ? APP_PDF_MARGIN_BOTTOM : PDF_MARGIN_BOTTOM));

    $pdf->SetAuthor(get_option('company'));
    $pdf->SetFont($font_name, '', $font_size);
    $pdf->setImageScale(1.53);
    $pdf->setJPEGQuality(100);
    $pdf->AddPage($formatArray['orientation'], $formatArray['format']);

    if ($CI->input->get('print') == 'true') {
        // force print dialog
        $js = 'print(true);';
        $pdf->IncludeJS($js);
    }

    $status = $service_request->status;
    $swap   = get_option('swap_pdf_info');
    $CI->load->library('numberword', array(
        'clientid' => $service_request->clientid
    ));
    $service_request = hooks()->apply_filters('service_request_html_pdf_data', $service_request);

    if (file_exists(module_dir_path(WPDF_TEMPLATE, 'views/pdf/' . TEMPLATE . '/my_service_requestpdf.php'))) {
        include(module_dir_path(WPDF_TEMPLATE, 'views/pdf/' . TEMPLATE . '/my_service_requestpdf.php'));
    }

    if (ob_get_length() > 0 && ENVIRONMENT == 'production') {
        ob_end_clean();
    }

    return $pdf;
}

/**
 * Prepare warranty request pdf
 * @param  object $invoice Invoice as object with all necessary fields
 * @return mixed object
 */
function warranty_request_pdf($data, $tag = '')
{
    $CI = &get_instance();

    $warranty = $data['warranty'];


    $request_number = get_option('Warranty') . $warranty->serial_number;

    $font_name      = get_option('pdf_font');
    $font_size      = get_option('pdf_font_size');

    if ($font_size == '') {
        $font_size = 12;
    }

    $formatArray = get_pdf_format('pdf_format_invoice');
    if (!file_exists(APPPATH . 'libraries/service_request_pdf.php')) {
        $pdf         = new Pdf($formatArray['orientation'], 'mm', $formatArray['format'], true, 'UTF-8', false, false, 'invoice');
    } else {
        include_once(APPPATH . 'libraries/service_request_pdf.php');
        $pdf         = new Invoice_pdf($formatArray['orientation'], 'mm', $formatArray['format'], true, 'UTF-8', false, false);
    }

    if (defined('APP_PDF_MARGIN_LEFT') && defined('APP_PDF_MARGIN_TOP') && defined('APP_PDF_MARGIN_RIGHT')) {
        $pdf->SetMargins(APP_PDF_MARGIN_LEFT, APP_PDF_MARGIN_TOP, APP_PDF_MARGIN_RIGHT);
    }

    $pdf->SetTitle($request_number);

    $pdf->SetAutoPageBreak(true, (defined('APP_PDF_MARGIN_BOTTOM') ? APP_PDF_MARGIN_BOTTOM : PDF_MARGIN_BOTTOM));

    $pdf->SetAuthor(get_option('company'));
    $pdf->SetFont($font_name, '', $font_size);
    $pdf->setImageScale(1.53);
    $pdf->setJPEGQuality(100);
    $pdf->AddPage($formatArray['orientation'], $formatArray['format']);

    if ($CI->input->get('print') == 'true') {
        // force print dialog
        $js = 'print(true);';
        $pdf->IncludeJS($js);
    }

    

    if (file_exists(module_dir_path(WPDF_TEMPLATE, 'views/pdf/' . TEMPLATE . '/my_warranty_certificate_pdf.php'))) {
        include(module_dir_path(WPDF_TEMPLATE, 'views/pdf/' . TEMPLATE . '/my_warranty_certificate_pdf.php'));
    }

    if (ob_get_length() > 0 && ENVIRONMENT == 'production') {
        ob_end_clean();
    }

    return $pdf;
}

/**
 * Prepare delivery request pdf
 * @param  object $invoice Invoice as object with all necessary fields
 * @return mixed object
 */
function delivery_note_pdf($data, $tag = '')
{
    $CI = &get_instance();

    $invoice = $data['invoice'];
    $invoice_number = $data['invoice_number'];
    $status = $data['status'];

    // Define status colors
    $status_colors = [
        1 => '252, 45, 66',
        2 => '0, 191, 54',
        3 => '255, 111, 0',
        4 => '255, 111, 0',
        5 => '114, 123, 144',
        6 => '114, 123, 144',
    ];
    $data['bg_status'] = $status_colors[$status] ?? '255, 255, 255'; // Default to white

    load_pdf_language($invoice->clientid);


    $font_name      = get_option('pdf_font');
    $font_size      = get_option('pdf_font_size');

    if ($font_size == '') {
        $font_size = 12;
    }

    $formatArray = get_pdf_format('pdf_format_invoice');
    if (!file_exists(APPPATH . 'libraries/service_request_pdf.php')) {
        $pdf         = new Pdf($formatArray['orientation'], 'mm', $formatArray['format'], true, 'UTF-8', false, false, 'invoice');
    } else {
        include_once(APPPATH . 'libraries/service_request_pdf.php');
        $pdf         = new Invoice_pdf($formatArray['orientation'], 'mm', $formatArray['format'], true, 'UTF-8', false, false);
    }

    if (defined('APP_PDF_MARGIN_LEFT') && defined('APP_PDF_MARGIN_TOP') && defined('APP_PDF_MARGIN_RIGHT')) {
        $pdf->SetMargins(APP_PDF_MARGIN_LEFT, APP_PDF_MARGIN_TOP, APP_PDF_MARGIN_RIGHT);
    }

    $pdf->SetTitle($invoice_number);

    $pdf->SetAutoPageBreak(true, (defined('APP_PDF_MARGIN_BOTTOM') ? APP_PDF_MARGIN_BOTTOM : PDF_MARGIN_BOTTOM));

    $pdf->SetAuthor(get_option('company'));
    $pdf->SetFont($font_name, '', $font_size);
    $pdf->setImageScale(1.53);
    $pdf->setJPEGQuality(100);
    $pdf->AddPage($formatArray['orientation'], $formatArray['format']);

    if ($CI->input->get('print') == 'true') {
        // force print dialog
        $js = 'print(true);';
        $pdf->IncludeJS($js);
    }


    if (file_exists(module_dir_path(WPDF_TEMPLATE, 'views/pdf/' . TEMPLATE . '/my_delivery_notepdf.php'))) {
        include(module_dir_path(WPDF_TEMPLATE, 'views/pdf/' . TEMPLATE . '/my_delivery_notepdf.php'));
    }

    if (ob_get_length() > 0 && ENVIRONMENT == 'production') {
        ob_end_clean();
    }

    return $pdf;
}


function inventory_checklist_pdf($data, $tag = '')
{
    $CI = &get_instance();
    $invoice = $data['invoice'];
    $invoice_number = $data['invoice_number'];
    $status = $data['status'];

    // Define status colors
    $status_colors = [
        1 => '252, 45, 66',
        2 => '0, 191, 54',
        3 => '255, 111, 0',
        4 => '255, 111, 0',
        5 => '114, 123, 144',
        6 => '114, 123, 144',
    ];
    $data['bg_status'] = $status_colors[$status] ?? '255, 255, 255'; // Default to white

    load_pdf_language($invoice->clientid);

    $font_name = get_option('pdf_font');
    $font_size = get_option('pdf_font_size') ?: 12;

    $formatArray = get_pdf_format('pdf_format_invoice');

    if (!file_exists(APPPATH . 'libraries/service_request_pdf.php')) {
        $pdf = new Pdf($formatArray['orientation'], 'mm', $formatArray['format'], true, 'UTF-8', false, false, 'invoice');
    } else {
        include_once(APPPATH . 'libraries/service_request_pdf.php');
        $pdf = new Invoice_pdf($formatArray['orientation'], 'mm', $formatArray['format'], true, 'UTF-8', false, false);
    }

    if (defined('APP_PDF_MARGIN_LEFT') && defined('APP_PDF_MARGIN_TOP') && defined('APP_PDF_MARGIN_RIGHT')) {
        $pdf->SetMargins(APP_PDF_MARGIN_LEFT, APP_PDF_MARGIN_TOP, APP_PDF_MARGIN_RIGHT);
    }

    $pdf->SetTitle($invoice_number);

    $pdf->SetAutoPageBreak(true, (defined('APP_PDF_MARGIN_BOTTOM') ? APP_PDF_MARGIN_BOTTOM : PDF_MARGIN_BOTTOM));

    $pdf->SetAuthor(get_option('company'));
    $pdf->SetFont($font_name, '', $font_size);
    $pdf->setImageScale(1.53);
    $pdf->setJPEGQuality(100);
    $pdf->AddPage($formatArray['orientation'], $formatArray['format']);

    if ($CI->input->get('print') == 'true') {
        // force print dialog
        $js = 'print(true);';
        $pdf->IncludeJS($js);
    }
    // Embed QR code image in the PDF
    if (!empty($data->qr_code_base64)) {
        $qr_code_path = FCPATH . 'modules/wonder_pdf_template/assets/images/' . $service_request->service_request_code . '_qrcode.png';
        if (file_exists($qr_code_path)) {
            $pdf->Image($qr_code_path, 10, 20, 20, 15, 'PNG'); // Adjust coordinates and size as needed
        }
    }

    $CI->load->library('numberword', ['clientid' => $invoice->clientid]);

    if (file_exists(module_dir_path(WPDF_TEMPLATE, 'views/pdf/' . TEMPLATE . '/my_inventory_checklistpdf.php'))) {
        include(module_dir_path(WPDF_TEMPLATE, 'views/pdf/' . TEMPLATE . '/my_inventory_checklistpdf.php'));
    }

    if (ob_get_length() > 0 && ENVIRONMENT == 'production') {
        ob_end_clean();
    }

    return $pdf;
}

/**
 * Prepare service request report pdf
 * @param  object $invoice Invoice as object with all necessary fields
 * @return mixed object
 */
function service_request_report_pdf($data, $tag = '')
{
    $CI = &get_instance();

    $data = json_decode(json_encode($data));
    $service_request = $data->service_info;
    //$service_details = $data->service_details;
    $service_request_client = $data->service_request_client;
    $calibration_info = $data->calibration_info;
    load_pdf_language($service_request->clientid);

    $request_number = get_option('service_request_prefix') . $service_request->service_request_code;
    $font_name = get_option('pdf_font');
    $font_size = get_option('pdf_font_size');
    if ($font_size == '') {
        $font_size = 10;
    }

    $formatArray = get_pdf_format('pdf_format_invoice');
    if (!file_exists(APPPATH . 'libraries/service_request_report_pdf.php')) {
        $pdf = new Pdf($formatArray['orientation'], 'mm', $formatArray['format'], true, 'UTF-8', false, false, 'invoice');
    } else {
        include_once(APPPATH . 'libraries/service_request_report_pdf.php');
        $pdf = new Invoice_pdf($formatArray['orientation'], 'mm', $formatArray['format'], true, 'UTF-8', false, false);
    }

    if (defined('APP_PDF_MARGIN_LEFT') && defined('APP_PDF_MARGIN_TOP') && defined('APP_PDF_MARGIN_RIGHT')) {
        $pdf->SetMargins(APP_PDF_MARGIN_LEFT, APP_PDF_MARGIN_TOP, APP_PDF_MARGIN_RIGHT);
    }

    $pdf->SetTitle($request_number);
    $pdf->SetAutoPageBreak(true, (defined('APP_PDF_MARGIN_BOTTOM') ? APP_PDF_MARGIN_BOTTOM : PDF_MARGIN_BOTTOM));
    $pdf->SetAuthor(get_option('company'));
    $pdf->SetFont($font_name, '', $font_size);
    $pdf->setImageScale(1.53);
    $pdf->setJPEGQuality(100);
    $pdf->AddPage($formatArray['orientation'], $formatArray['format']);

    // Embed QR code image in the PDF
    if (!empty($data->qr_code_base64)) {
        $qr_code_path = FCPATH . 'modules/wonder_pdf_template/assets/images/' . $service_request->service_request_code . '_qrcode.png';
        if (file_exists($qr_code_path)) {
            $pdf->Image($qr_code_path, 10, 20, 25, 15, 'PNG'); // Adjust coordinates and size as needed
        }
    }

    $service_request = hooks()->apply_filters('service_request_html_pdf_data', $service_request);

    if (file_exists(module_dir_path(WPDF_TEMPLATE, 'views/pdf/' . TEMPLATE . '/my_service_request_reportpdf.php'))) {
        include(module_dir_path(WPDF_TEMPLATE, 'views/pdf/' . TEMPLATE . '/my_service_request_reportpdf.php'));
    }

    if (ob_get_length() > 0 && ENVIRONMENT == 'production') {
        ob_end_clean();
    }

    return $pdf;
}

/**
 * Service certificate PDF
 * @param  [type] $data [description]
 * @param  string $tag  [description]
 * @return [type]       [description]
 */
function service_certificate_pdf($data, $tag = '')
{
    $CI = &get_instance();

    $service_request = $data['service_request'];
    $service_request_details = $data['service_details'];
    $service_request_client = $data['service_request_client'];

    load_pdf_language($service_request->clientid);

    $request_number = get_option('service_request_prefix') . $service_request->service_request_code;

    $font_name      = get_option('pdf_font');
    $font_size      = get_option('pdf_font_size');

    if ($font_size == '') {
        $font_size = 10;
    }

    $formatArray = get_pdf_format('pdf_format_invoice');
    if (!file_exists(APPPATH . 'libraries/service_request_pdf.php')) {
        $pdf         = new Pdf($formatArray['orientation'], 'mm', $formatArray['format'], true, 'UTF-8', false, false, 'invoice');
    } else {
        include_once(APPPATH . 'libraries/service_request_pdf.php');
        $pdf         = new Invoice_pdf($formatArray['orientation'], 'mm', $formatArray['format'], true, 'UTF-8', false, false);
    }

    if (defined('APP_PDF_MARGIN_LEFT') && defined('APP_PDF_MARGIN_TOP') && defined('APP_PDF_MARGIN_RIGHT')) {
        $pdf->SetMargins(APP_PDF_MARGIN_LEFT, APP_PDF_MARGIN_TOP, APP_PDF_MARGIN_RIGHT);
    }

    $pdf->SetTitle($request_number);

    $pdf->SetAutoPageBreak(true, (defined('APP_PDF_MARGIN_BOTTOM') ? APP_PDF_MARGIN_BOTTOM : PDF_MARGIN_BOTTOM));

    $pdf->SetAuthor(get_option('company'));
    $pdf->SetFont($font_name, '', $font_size);
    $pdf->setImageScale(1.53);
    $pdf->setJPEGQuality(100);
    $pdf->AddPage($formatArray['orientation'], $formatArray['format']);

    if ($CI->input->get('print') == 'true') {
        // force print dialog
        $js = 'print(true);';
        $pdf->IncludeJS($js);
    }

    $status = $service_request->status;
    $swap   = get_option('swap_pdf_info');
    $CI->load->library('numberword', array(
        'clientid' => $service_request->clientid
    ));
    $service_request = hooks()->apply_filters('service_request_html_pdf_data', $service_request);

    if (file_exists(module_dir_path(WPDF_TEMPLATE, 'views/pdf/' . TEMPLATE . '/my_service_requestpdf.php'))) {
        include(module_dir_path(WPDF_TEMPLATE, 'views/pdf/' . TEMPLATE . '/my_service_requestpdf.php'));
    }


    if (ob_get_length() > 0 && ENVIRONMENT == 'production') {
        ob_end_clean();
    }

    return $pdf;
}


function service_rental_agreement_pdf($data, $tag = '')
{
    $CI = &get_instance();

    // Decode and convert data to object
    $data = json_decode(json_encode($data));
    $service_rental_agreement = $data->service_rental_agreement;
    $service_rental_agreement_details = $data->service_details;  // Make sure this is included
    $service_rental_agreement_client = $data->service_rental_agreement_client;
    $field_report_info = $data->field_report_info;
    $service_request = $data->service_info;

    load_pdf_language($service_rental_agreement->clientid);

    $rental_agreement_number = get_option('service_rental_agreement_prefix') . $service_rental_agreement->service_rental_agreement_code;
    $font_name = get_option('pdf_font');
    $font_size = get_option('pdf_font_size');

    if ($font_size == '') {
        $font_size = 10;
    }

    $formatArray = get_pdf_format('pdf_format_invoice');
    if (!file_exists(APPPATH . 'libraries/service_rental_agreement_pdf.php')) {
        $pdf = new Pdf($formatArray['orientation'], 'mm', $formatArray['format'], true, 'UTF-8', false, false, 'invoice');
    } else {
        include_once(APPPATH . 'libraries/service_rental_agreement_pdf.php');
        $pdf = new Invoice_pdf($formatArray['orientation'], 'mm', $formatArray['format'], true, 'UTF-8', false, false);
    }

    if (defined('APP_PDF_MARGIN_LEFT') && defined('APP_PDF_MARGIN_TOP') && defined('APP_PDF_MARGIN_RIGHT')) {
        $pdf->SetMargins(APP_PDF_MARGIN_LEFT, APP_PDF_MARGIN_TOP, APP_PDF_MARGIN_RIGHT);
    }

    $pdf->SetTitle($rental_agreement_number);
    $pdf->SetAutoPageBreak(true, (defined('APP_PDF_MARGIN_BOTTOM') ? APP_PDF_MARGIN_BOTTOM : PDF_MARGIN_BOTTOM));
    $pdf->SetAuthor(get_option('company'));
    $pdf->SetFont($font_name, '', $font_size);
    $pdf->setImageScale(1.53);
    $pdf->setJPEGQuality(100);
    $pdf->AddPage($formatArray['orientation'], $formatArray['format']);


    if ($CI->input->get('print') == 'true') {
        // force print dialog
        $js = 'print(true);';
        $pdf->IncludeJS($js);
    }

    $status = $service_rental_agreement->status;
    $swap = get_option('swap_pdf_info');
    $CI->load->library('numberword', array(
        'clientid' => $service_rental_agreement->clientid
    ));


    $service_rental_agreement = hooks()->apply_filters('service_rental_agreement_html_pdf_data', $service_rental_agreement);

    if (file_exists(module_dir_path(WPDF_TEMPLATE, 'views/pdf/' . TEMPLATE . '/my_service_rental_agreementpdf.php'))) {
        include(module_dir_path(WPDF_TEMPLATE, 'views/pdf/' . TEMPLATE . '/my_service_rental_agreementpdf.php'));
    }

    if (ob_get_length() > 0 && ENVIRONMENT == 'production') {
        ob_end_clean();
    }

    return $pdf;
}




function service_rental_agreement_pdf1($data, $tag = '')
{
    $CI = &get_instance();

    $service_rental_agreement = $data['service_rental_agreement'];
    $service_rental_agreement_details = $data['service_details'];
    $service_rental_agreement_client = $data['service_rental_agreement_client'];

    load_pdf_language($service_rental_agreement->clientid);

    $rental_agreement_number = get_option('service_rental_agreement_prefix') . $service_rental_agreement->service_rental_agreement_code;

    $font_name      = get_option('pdf_font');
    $font_size      = get_option('pdf_font_size');

    if ($font_size == '') {
        $font_size = 10;
    }

    $formatArray = get_pdf_format('pdf_format_invoice');
    if (!file_exists(APPPATH . 'libraries/service_rental_agreement_pdf.php')) {
        $pdf         = new Pdf($formatArray['orientation'], 'mm', $formatArray['format'], true, 'UTF-8', false, false, 'invoice');
    } else {
        include_once(APPPATH . 'libraries/service_rental_agreement_pdf.php');
        $pdf         = new Invoice_pdf($formatArray['orientation'], 'mm', $formatArray['format'], true, 'UTF-8', false, false);
    }

    if (defined('APP_PDF_MARGIN_LEFT') && defined('APP_PDF_MARGIN_TOP') && defined('APP_PDF_MARGIN_RIGHT')) {
        $pdf->SetMargins(APP_PDF_MARGIN_LEFT, APP_PDF_MARGIN_TOP, APP_PDF_MARGIN_RIGHT);
    }

    $pdf->SetTitle($rental_agreement_number);

    $pdf->SetAutoPageBreak(true, (defined('APP_PDF_MARGIN_BOTTOM') ? APP_PDF_MARGIN_BOTTOM : PDF_MARGIN_BOTTOM));

    $pdf->SetAuthor(get_option('company'));
    $pdf->SetFont($font_name, '', $font_size);
    $pdf->setImageScale(1.53);
    $pdf->setJPEGQuality(100);
    $pdf->AddPage($formatArray['orientation'], $formatArray['format']);

    if ($CI->input->get('print') == 'true') {
        // force print dialog
        $js = 'print(true);';
        $pdf->IncludeJS($js);
    }

    $status = $service_rental_agreement->status;
    $swap   = get_option('swap_pdf_info');
    $CI->load->library('numberword', array(
        'clientid' => $service_rental_agreement->clientid
    ));
    $service_rental_agreement = hooks()->apply_filters('service_rental_agreement_html_pdf_data', $service_rental_agreement);

    if (file_exists(module_dir_path(WPDF_TEMPLATE, 'views/pdf/' . TEMPLATE . '/my_service_rental_agreementpdf1.php'))) {
        include(module_dir_path(WPDF_TEMPLATE, 'views/pdf/' . TEMPLATE . '/my_service_rental_agreementpdf1.php'));
    }


    if (ob_get_length() > 0 && ENVIRONMENT == 'production') {
        ob_end_clean();
    }

    return $pdf;
}

if (!function_exists('set_verification_qrcode')) {
    /*** QRcode Generate ***/
    function set_verification_qrcode($data)
    {
        $CI = &get_instance();
        //load library
        //$CI->load->library(BIZIT_SERVICES_MSL . '/Tazamali_QRcode');
        $filePath = TEMP_FOLDER . 'temp_file.png';

        $codeContents = $data->verificationUrl;

        QRcode::png($codeContents, $filePath, QR_ECLEVEL_L, 3, 0);

        $qrImage = file_get_contents($filePath);

        unlink($filePath);

        return base64_encode($qrImage);
    }
}


if (!function_exists('show_verification_qrcode')) {
    /*** QRcode Generate ***/
    function show_verification_qrcode($data)
    {

        $output = '';

        if (!empty($data)) {
            $data_obj = json_decode(json_encode($data));

            $img_base64_encoded = 'data:image/png;base64,' . set_verification_qrcode($data_obj);
            $img = '<img src="@' . preg_replace('#^data:image/[^;]+;base64,#', '', $img_base64_encoded) . '" width="80px" style="margin:0 auto;">';
            //$img = '<img src="' .  $img_base64_encoded . '" width="80px" style="margin:0 auto;">';

            $info_qr = '<table><tr><td style="vertical-align: middle;
			text-align:center;">' . $img . '<br /><span style="font-size: 8pt; font-family:WonderUnitSans-Regular;">*Scan QR*</span></td></tr></table>';


            $output .= $info_qr;
        } else {
            $output .= '';
        }

        return $output;
    }
}
