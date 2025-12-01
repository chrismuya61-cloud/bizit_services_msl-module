<?php defined('BASEPATH') or exit('No direct script access allowed');

class Migration_Version_106 extends App_module_migration
{
    public function up()
    {
        $CI = &get_instance();

        // 1. Remove Obsolete Warranty Feature
        if ($CI->db->table_exists('tblwarranty')) {
            $CI->db->query('DROP TABLE ' . db_prefix() . 'warranty');
        }

        // 2. Ensure V3 Services Table Structure
        if (!$CI->db->field_exists('rental_status', 'tblservices_module')) {
            $CI->db->query('ALTER TABLE `tblservices_module` ADD COLUMN `rental_status` VARCHAR(50) DEFAULT "Not-Hired"');
        }
        
        // 3. Ensure Field Reports Table Exists (Foundation for V3)
        if (!$CI->db->table_exists('tblfield_report')) {
            $CI->db->query('CREATE TABLE `tblfield_report` (
                `field_report_id` int(11) NOT NULL AUTO_INCREMENT,
                `service_rental_agreement_id` int(11) NOT NULL,
                `report_code` varchar(50) NOT NULL,
                `clientid` int(11) NOT NULL,
                `site_name` varchar(255) DEFAULT NULL,
                `report_files` text DEFAULT NULL,
                `status` int(11) DEFAULT 1,
                `added_by` int(11) NOT NULL,
                `submitted_by` int(11) DEFAULT NULL,
                `approved_by` int(11) DEFAULT NULL,
                `approval_remarks` text DEFAULT NULL,
                `date_submitted` datetime DEFAULT NULL,
                `dateadded` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP,
                PRIMARY KEY (`field_report_id`)
            ) ENGINE=InnoDB DEFAULT CHARSET=utf8;');
        }
    }
}