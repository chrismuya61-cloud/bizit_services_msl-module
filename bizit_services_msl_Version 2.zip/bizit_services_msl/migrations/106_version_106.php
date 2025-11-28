<?php

defined('BASEPATH') or exit('No direct script access allowed');

class Migration_Version_106 extends App_module_migration

{

    public function up()

    {

        $CI = &get_instance();

        // 1. Drop old tblwarranty table (Removes Warranty Feature)

        if ($CI->db->table_exists('tblwarranty')) {

            $CI->db->query('DROP TABLE ' . db_prefix() . 'warranty');

        }

        // 2. Create the new Staff Service Rates table

        if (!$CI->db->table_exists('tblstaff_service_rates')) {

            $CI->db->query('CREATE TABLE `' . db_prefix() . 'staff_service_rates` (

                `id` INT(11) NOT NULL AUTO_INCREMENT,

                `staffid` INT(11) NOT NULL,

                `serviceid` INT(11) NOT NULL,

                `rate` DECIMAL(15,2) NOT NULL DEFAULT 0.00,

                `allowance_type` VARCHAR(50) NOT NULL DEFAULT "unit",

                `datecreated` DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,

                PRIMARY KEY (`id`),

                UNIQUE KEY `staffid_serviceid` (`staffid`, `serviceid`)

            ) ENGINE=InnoDB DEFAULT CHARSET=utf8;');

        }

    }

}

