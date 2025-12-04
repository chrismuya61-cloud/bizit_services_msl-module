<?php defined('BASEPATH') or exit('No direct script access allowed');

class Migration_Version_111 extends App_module_migration
{
    public function up()
    {
        $CI = &get_instance();

        // 1. Add Approval Columns to Rental Agreements
        if (!$CI->db->field_exists('approved_by', 'tblservice_rental_agreement')) {
            $CI->db->query("ALTER TABLE `tblservice_rental_agreement` 
                ADD COLUMN `approved_by` INT(11) DEFAULT NULL,
                ADD COLUMN `date_approved` DATETIME DEFAULT NULL,
                ADD COLUMN `approval_status` INT(11) DEFAULT 0 COMMENT '0=Draft, 1=Pending, 2=Approved, 3=Rejected';");
        }

        // 2. Add Approval Columns to Service Requests
        if (!$CI->db->field_exists('approved_by', 'tblservice_request')) {
            $CI->db->query("ALTER TABLE `tblservice_request` 
                ADD COLUMN `approved_by` INT(11) DEFAULT NULL,
                ADD COLUMN `date_approved` DATETIME DEFAULT NULL,
                ADD COLUMN `approval_status` INT(11) DEFAULT 0;");
        }

        // 3. Add Staff Signature to Sales Orders
        if (!$CI->db->field_exists('staff_signature', 'tblsales_orders')) {
            $CI->db->query("ALTER TABLE `tblsales_orders` 
                ADD COLUMN `staff_signature` TEXT DEFAULT NULL;");
        }
        
        // 4. Add Staff Signature to Core Tables (Estimates & Invoices)
        // Note: Modifying core tables allows native integration
        if (!$CI->db->field_exists('staff_signature', 'tblestimates')) {
            $CI->db->query("ALTER TABLE `tblestimates` ADD COLUMN `staff_signature` TEXT DEFAULT NULL;");
        }
        if (!$CI->db->field_exists('staff_signature', 'tblinvoices')) {
            $CI->db->query("ALTER TABLE `tblinvoices` ADD COLUMN `staff_signature` TEXT DEFAULT NULL;");
        }
    }
}
