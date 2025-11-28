<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Migration_Version_109 extends App_module_migration
{
    public function up()
    {
        $CI = &get_instance();
        if ($CI->db->table_exists('tblwarranty')) {
            $CI->db->query('DROP TABLE ' . db_prefix() . 'warranty');
        }
        if (!$CI->db->table_exists('tblstaff_service_rates')) {
            $CI->db->query('CREATE TABLE `' . db_prefix() . 'staff_service_rates` (
                `id` INT(11) NOT NULL AUTO_INCREMENT,
                `staffid` INT(11) NOT NULL,
                `serviceid` INT(11) NOT NULL,
                `rate` DECIMAL(15,2) NOT NULL DEFAULT 0.00,
                `allowance_type` VARCHAR(50) NOT NULL DEFAULT "unit",
                `datecreated` DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
                PRIMARY KEY (`id`)
            ) ENGINE=InnoDB DEFAULT CHARSET=utf8;');
        }
        if ($CI->db->table_exists('tblfield_report')) {
            if (!$CI->db->field_exists('submitted_by', 'tblfield_report')) {
                $CI->db->query('ALTER TABLE `' . db_prefix() . 'field_report` ADD COLUMN `submitted_by` INT(11) NULL DEFAULT NULL AFTER `added_by`');
            }
            if (!$CI->db->field_exists('date_submitted', 'tblfield_report')) {
                $CI->db->query('ALTER TABLE `' . db_prefix() . 'field_report` ADD COLUMN `date_submitted` DATETIME NULL DEFAULT NULL AFTER `dateadded`');
            }
        }
    }
}