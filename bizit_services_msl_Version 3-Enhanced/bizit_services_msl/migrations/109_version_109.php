<?php defined('BASEPATH') or exit('No direct script access allowed');

class Migration_Version_109 extends App_module_migration
{
    public function up()
    {
        $CI = &get_instance();

        // 1. Add Review Tokens (For QR Codes)
        if (!$CI->db->field_exists('service_review_token', 'tblinvoices')) {
            $CI->db->query('ALTER TABLE `tblinvoices` ADD COLUMN `service_review_token` VARCHAR(100) NULL DEFAULT NULL');
        }
        if (!$CI->db->field_exists('service_review_token', 'tblservice_rental_agreement')) {
            $CI->db->query('ALTER TABLE `tblservice_rental_agreement` ADD COLUMN `service_review_token` VARCHAR(100) NULL DEFAULT NULL');
        }
        if (!$CI->db->field_exists('service_review_token', 'tblservice_request')) {
            $CI->db->query('ALTER TABLE `tblservice_request` ADD COLUMN `service_review_token` VARCHAR(100) NULL DEFAULT NULL');
        }

        // 2. Add Calibration Reminder Date
        if (!$CI->db->field_exists('next_calibration_date', 'tblservices_calibration')) {
            $CI->db->query('ALTER TABLE `tblservices_calibration` ADD COLUMN `next_calibration_date` DATE NULL DEFAULT NULL AFTER `calibration_remark`');
            $CI->db->query('ALTER TABLE `tblservices_calibration` ADD INDEX `idx_next_cal_date` (`next_calibration_date`)');
        }
    }
}