<?php

defined('BASEPATH') or exit('No direct script access allowed');

$CI = &get_instance();

// 1. DROP ALL MODULE TABLES
$tables = [
    'tblservices_module',
    'tblservice_type',
    'tblservice_request',
    'tblservice_request_details',
    'tblservice_request_accessories',
    'tblservice_rental_agreement',
    'tblservice_rental_agreement_details',
    'tblfield_report',
    'tblinspection_requests',
    'tblchecklist1',
    'tblcollection1',
    'tblservices_calibration',
    'tblstaff_service_rates',
    'tblservice_client_reviews',
    'tblgps_data',
    'tblwarranty' // Legacy
];

foreach ($tables as $t) {
    $CI->db->query("DROP TABLE IF EXISTS `$t`");
}

// 2. CLEANUP CORE COLUMNS
if ($CI->db->field_exists('technical_engineer_id', 'tblinvoices')) $CI->db->query('ALTER TABLE `tblinvoices` DROP COLUMN `technical_engineer_id`');
if ($CI->db->field_exists('support_ticket_id', 'tblinvoices')) $CI->db->query('ALTER TABLE `tblinvoices` DROP COLUMN `support_ticket_id`');
if ($CI->db->field_exists('service_review_token', 'tblinvoices')) $CI->db->query('ALTER TABLE `tblinvoices` DROP COLUMN `service_review_token`');

// 3. REMOVE OPTIONS
delete_option('service_request_prefix');
delete_option('service_rental_agreement_prefix');
delete_option('field_report_approvers');

// 4. CLEANUP CORE FILES (Safety Net)
// Just in case deactivate wasn't run, we attempt to revert changes again.
$invoice_view = APPPATH . "views/admin/invoices/invoice.php";
$v1_code = "<?php \$this->load->view('admin/invoices/bizit_invoices/invoice_template'); ?>";
$core_code = "<?php \$this->load->view('admin/invoices/invoice_template'); ?>";
if (file_exists($invoice_view)) {
    $c = file_get_contents($invoice_view);
    if (strpos($c, $v1_code) !== false) {
        file_put_contents($invoice_view, str_replace($v1_code, $core_code, $c));
    }
}