<?php defined('BASEPATH') or exit('No direct script access allowed');

class Migration_Version_110 extends App_module_migration
{
    public function up()
    {
        $CI = &get_instance();

        // 1. Main Sales Order Table
        if (!$CI->db->table_exists('tblsales_orders')) {
            $CI->db->query('CREATE TABLE `tblsales_orders` (
                `id` INT(11) NOT NULL AUTO_INCREMENT,
                `order_number` INT(11) NOT NULL,
                `prefix` VARCHAR(50) DEFAULT NULL,
                `clientid` INT(11) NOT NULL,
                `estimate_id` INT(11) DEFAULT NULL,
                `date` DATE NOT NULL,
                `expiry_date` DATE DEFAULT NULL,
                `currency` INT(11) NOT NULL,
                `subtotal` DECIMAL(15,2) NOT NULL,
                `total_tax` DECIMAL(15,2) DEFAULT "0.00",
                `total` DECIMAL(15,2) NOT NULL,
                `adjustment` DECIMAL(15,2) DEFAULT "0.00",
                `discount_percent` DECIMAL(15,2) DEFAULT "0.00",
                `discount_total` DECIMAL(15,2) DEFAULT "0.00",
                `status` INT(11) DEFAULT 1 COMMENT "1=Draft, 2=Confirmed, 3=Invoiced, 4=Cancelled",
                `client_note` TEXT,
                `admin_note` TEXT,
                `terms` TEXT,
                `payment_details` TEXT,
                `signed_by` VARCHAR(100) DEFAULT NULL,
                `signature_image` VARCHAR(255) DEFAULT NULL,
                `datecreated` DATETIME NOT NULL,
                PRIMARY KEY (`id`)
            ) ENGINE=InnoDB DEFAULT CHARSET=' . $CI->db->char_set . ';');
        }

        // 2. Sales Order Items Table
        if (!$CI->db->table_exists('tblsales_order_items')) {
            $CI->db->query('CREATE TABLE `tblsales_order_items` (
                `id` INT(11) NOT NULL AUTO_INCREMENT,
                `rel_id` INT(11) NOT NULL COMMENT "Sales Order ID",
                `description` TEXT NOT NULL,
                `long_description` TEXT,
                `qty` DECIMAL(15,2) NOT NULL,
                `rate` DECIMAL(15,2) NOT NULL,
                `unit` VARCHAR(40) DEFAULT NULL,
                `item_order` INT(11) DEFAULT NULL,
                PRIMARY KEY (`id`)
            ) ENGINE=InnoDB DEFAULT CHARSET=' . $CI->db->char_set . ';');
        }
    }
}
