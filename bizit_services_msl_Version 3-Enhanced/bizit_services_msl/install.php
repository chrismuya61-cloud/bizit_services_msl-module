<?php

defined('BASEPATH') or exit('No direct script access allowed');

$CI = &get_instance();

// ==================================================
// 1. REGISTER MODULE
// ==================================================
if (!$CI->db->table_exists(db_prefix() . 'modules')) {
    $CI->db->query('CREATE TABLE `' . db_prefix() . 'modules` (
      `id` int(11) NOT NULL AUTO_INCREMENT,
      `module_name` varchar(55) NOT NULL,
      `installed_version` varchar(11) NOT NULL,
      `active` int(11) NOT NULL DEFAULT 0,
      PRIMARY KEY (`id`)
    ) ENGINE=InnoDB DEFAULT CHARSET=utf8;');
}

$CI->db->where('module_name', BIZIT_SERVICES_MSL);
if (!$CI->db->get(db_prefix() . 'modules')->row()) {
    $CI->db->insert(db_prefix() . 'modules', ['module_name' => BIZIT_SERVICES_MSL, 'installed_version' => '1.1.3', 'active' => 1]);
} else {
    $CI->db->where('module_name', BIZIT_SERVICES_MSL)->update(db_prefix() . 'modules', ['installed_version' => '1.1.3']);
}

// ==================================================
// 2. CREATE TABLES (V3 SCHEMA)
// ==================================================

// Core Services
if (!$CI->db->table_exists('tblservices_module')) {
    $CI->db->query('CREATE TABLE `tblservices_module` (
      `serviceid` int(11) NOT NULL AUTO_INCREMENT,
      `service_code` varchar(30), `name` varchar(200), `service_type_code` varchar(30), `price` decimal(15,2),
      `description` text, `quantity_unit` varchar(100), `penalty_rental_price` decimal(15,2),
      `rental_serial` varchar(100), `rental_duration_check` varchar(50), `rental_status` varchar(50) DEFAULT "Not-Hired",
      `status` int(11) DEFAULT 1, `datecreated` datetime, PRIMARY KEY (`serviceid`)
    ) ENGINE=InnoDB DEFAULT CHARSET=utf8;');
}

// Categories
if (!$CI->db->table_exists('tblservice_type')) {
    $CI->db->query('CREATE TABLE `tblservice_type` (
      `service_typeid` int(11) NOT NULL AUTO_INCREMENT, `type_code` varchar(30), `name` varchar(100),
      `description` text, `status` int(11) DEFAULT 1, `datecreated` datetime, PRIMARY KEY (`service_typeid`)
    ) ENGINE=InnoDB DEFAULT CHARSET=utf8;');
    // Defaults
    $CI->db->insert('tblservice_type', ['type_code'=>'001','name'=>'Rental Equipment']);
    $CI->db->insert('tblservice_type', ['type_code'=>'002','name'=>'Service & Calibration']);
}

// Requests
if (!$CI->db->table_exists('tblservice_request')) {
    $CI->db->query('CREATE TABLE `tblservice_request` (
      `service_request_id` int(11) NOT NULL AUTO_INCREMENT, `service_request_code` varchar(50), `clientid` int(11),
      `item_type` varchar(100), `item_make` varchar(100), `item_model` varchar(100), `serial_no` varchar(100),
      `service_note` text, `condition` text, `received_by` int(11), `drop_off_date` date, `collection_date` date,
      `status` int(11) DEFAULT 0, `invoice_rel_id` int(11), `service_review_token` varchar(100), `report_files` text,
      `dropped_off_by` varchar(100), `dropped_off_date` date, `dropped_off_signature` text, `dropped_off_id_number` varchar(50),
      `req_received_by` varchar(100), `received_date` date, `received_signature` text, `received_id_number` varchar(50),
      `dateadded` datetime DEFAULT CURRENT_TIMESTAMP, PRIMARY KEY (`service_request_id`)
    ) ENGINE=InnoDB DEFAULT CHARSET=utf8;');
}
if (!$CI->db->table_exists('tblservice_request_details')) {
    $CI->db->query('CREATE TABLE `tblservice_request_details` (`service_request_details_id` int(11) NOT NULL AUTO_INCREMENT, `service_request_id` int(11), `serviceid` int(11), `price` decimal(15,2), PRIMARY KEY (`service_request_details_id`)) ENGINE=InnoDB DEFAULT CHARSET=utf8;');
}
if (!$CI->db->table_exists('tblservice_request_accessories')) {
    $CI->db->query('CREATE TABLE `tblservice_request_accessories` (`id` int(11) NOT NULL AUTO_INCREMENT, `service_request_id` int(11), `accessory_id` int(11), `price` decimal(15,2), PRIMARY KEY (`id`)) ENGINE=InnoDB DEFAULT CHARSET=utf8;');
}

// Rentals
if (!$CI->db->table_exists('tblservice_rental_agreement')) {
    $CI->db->query('CREATE TABLE `tblservice_rental_agreement` (
      `service_rental_agreement_id` int(11) NOT NULL AUTO_INCREMENT, `service_rental_agreement_code` varchar(50), `clientid` int(11),
      `start_date` date, `end_date` date, `field_operator` int(11), `site_name` varchar(255), `status` int(11) DEFAULT 0,
      `rental_agreement_note` text, `extra_days` int(11) DEFAULT 0, `discounted_days` int(11) DEFAULT 0, `actual_date_returned` date,
      `invoice_rel_id` int(11), `service_review_token` varchar(100), `report_files` text, `dateadded` datetime DEFAULT CURRENT_TIMESTAMP,
      PRIMARY KEY (`service_rental_agreement_id`)
    ) ENGINE=InnoDB DEFAULT CHARSET=utf8;');
}
if (!$CI->db->table_exists('tblservice_rental_agreement_details')) {
    $CI->db->query('CREATE TABLE `tblservice_rental_agreement_details` (`service_rental_agreement_details_id` int(11) NOT NULL AUTO_INCREMENT, `service_rental_agreement_id` int(11), `serviceid` int(11), `price` decimal(15,2), PRIMARY KEY (`service_rental_agreement_details_id`)) ENGINE=InnoDB DEFAULT CHARSET=utf8;');
}

// Sub-tables (Calibration, Inspection, Checklists, GPS)
if (!$CI->db->table_exists('tblservices_calibration')) {
     $CI->db->query('CREATE TABLE `tblservices_calibration` (`calibration_id` int(11) NOT NULL AUTO_INCREMENT, `service_request_id` int(11), `calibration_instrument` varchar(100), `calibration_remark` text, `next_calibration_date` date, `datecreated` datetime, PRIMARY KEY (`calibration_id`)) ENGINE=InnoDB DEFAULT CHARSET=utf8;');
}
if (!$CI->db->table_exists('tblinspection_requests')) {
    $CI->db->query('CREATE TABLE `tblinspection_requests` (`id` int(11) NOT NULL AUTO_INCREMENT, `service_request_id` int(11), `inspection_item` varchar(255), `remarks_condition` text, `inspection_type` varchar(50), PRIMARY KEY (`id`)) ENGINE=InnoDB DEFAULT CHARSET=utf8;');
}
if (!$CI->db->table_exists('tblchecklist1')) {
    $CI->db->query('CREATE TABLE `tblchecklist1` (`id` int(11) NOT NULL AUTO_INCREMENT, `service_request_id` int(11), `item` varchar(255), `status` int(11), PRIMARY KEY (`id`)) ENGINE=InnoDB DEFAULT CHARSET=utf8;');
}
if (!$CI->db->table_exists('tblcollection1')) {
    $CI->db->query('CREATE TABLE `tblcollection1` (`id` int(11) NOT NULL AUTO_INCREMENT, `service_request_id` int(11), `released_by` varchar(100), `released_date` date, `released_id_number` varchar(50), `collected_by` varchar(100), `collected_date` date, `collected_id_number` varchar(50), PRIMARY KEY (`id`)) ENGINE=InnoDB DEFAULT CHARSET=utf8;');
}
if (!$CI->db->table_exists('tblgps_data')) {
    $CI->db->query('CREATE TABLE `tblgps_data` (`id` int(11) NOT NULL AUTO_INCREMENT, `latitude` varchar(50), `longitude` varchar(50), `description` text, `staffid` int(11), `date_recorded` datetime DEFAULT CURRENT_TIMESTAMP, PRIMARY KEY (`id`)) ENGINE=InnoDB DEFAULT CHARSET=utf8;');
}

// V3 Features (Compensation, Reports, Reviews)
if (!$CI->db->table_exists('tblstaff_service_rates')) {
    $CI->db->query('CREATE TABLE `tblstaff_service_rates` (`id` int(11) NOT NULL AUTO_INCREMENT, `staffid` int(11), `serviceid` int(11), `rate` decimal(15,2), `allowance_type` varchar(50) DEFAULT "unit", `datecreated` datetime, PRIMARY KEY (`id`)) ENGINE=InnoDB DEFAULT CHARSET=utf8;');
}
if (!$CI->db->table_exists('tblfield_report')) {
    $CI->db->query('CREATE TABLE `tblfield_report` (`field_report_id` int(11) NOT NULL AUTO_INCREMENT, `service_rental_agreement_id` int(11), `report_code` varchar(50), `clientid` int(11), `site_name` varchar(255), `report_files` text, `status` int(11) DEFAULT 1, `added_by` int(11), `submitted_by` int(11), `approved_by` int(11), `approval_remarks` text, `date_submitted` datetime, `dateadded` datetime, PRIMARY KEY (`field_report_id`)) ENGINE=InnoDB DEFAULT CHARSET=utf8;');
}
if (!$CI->db->table_exists('tblservice_client_reviews')) {
    $CI->db->query('CREATE TABLE `tblservice_client_reviews` (`id` int(11) NOT NULL AUTO_INCREMENT, `rel_type` varchar(20), `rel_id` int(11), `client_id` int(11), `engineer_id` int(11), `rating` int(1), `comment` text, `date_created` datetime, `ip_address` varchar(40), PRIMARY KEY (`id`)) ENGINE=InnoDB DEFAULT CHARSET=utf8;');
}

// ==================================================
// 3. MODIFY CORE TABLES (Add V3 Columns)
// ==================================================
if (!$CI->db->field_exists('technical_engineer_id', 'tblinvoices')) $CI->db->query('ALTER TABLE `tblinvoices` ADD COLUMN `technical_engineer_id` INT(11) NULL');
if (!$CI->db->field_exists('support_ticket_id', 'tblinvoices')) $CI->db->query('ALTER TABLE `tblinvoices` ADD COLUMN `support_ticket_id` INT(11) NULL');
if (!$CI->db->field_exists('service_review_token', 'tblinvoices')) $CI->db->query('ALTER TABLE `tblinvoices` ADD COLUMN `service_review_token` VARCHAR(100) NULL');

add_option('service_request_prefix', 'REQ-');
add_option('service_rental_agreement_prefix', 'ARG-');

// ==================================================
// 4. COPY VIEWS (Legacy Invoice Template Support)
// ==================================================
if (!function_exists('copyfolder')) {
    function copyfolder($source, $destination) {
        if (is_dir($source)) {
            @mkdir($destination, 0755, true);
            $dir = opendir($source);
            while (($file = readdir($dir)) !== false) {
                if ($file != '.' && $file != '..') {
                    if (is_dir($source . '/' . $file)) copyfolder($source . '/' . $file, $destination . '/' . $file);
                    else copy($source . '/' . $file, $destination . '/' . $file);
                }
            }
            closedir($dir);
        }
    }
}

// Copy PDF Views to Core (For Invoice/Estimate overrides if needed by user settings)
$dest = APPPATH . 'views/admin/invoices/bizit_invoices/';
$src = module_dir_path(BIZIT_SERVICES_MSL, 'views/admin/bizit_invoices/');
copyfolder($src, $dest);

$dest = APPPATH . 'views/admin/estimates/bizit_estimates/';
$src = module_dir_path(BIZIT_SERVICES_MSL, 'views/admin/bizit_estimates/');
copyfolder($src, $dest);