<?php defined('BASEPATH') or exit('No direct script access allowed');

class Migration_Version_108 extends App_module_migration
{
    public function up()
    {
        $CI = &get_instance();

        // 1. Add Technical Engineer to Invoices
        if (!$CI->db->field_exists('technical_engineer_id', 'tblinvoices')) {
            $CI->db->query('ALTER TABLE `tblinvoices` ADD COLUMN `technical_engineer_id` INT(11) NULL DEFAULT NULL AFTER `sale_agent`');
        }
        
        // 2. Link Support Ticket
        if (!$CI->db->field_exists('support_ticket_id', 'tblinvoices')) {
            $CI->db->query('ALTER TABLE `tblinvoices` ADD COLUMN `support_ticket_id` INT(11) NULL DEFAULT NULL AFTER `technical_engineer_id`');
        }

        // 3. Create Reviews Table
        if (!$CI->db->table_exists('tblservice_client_reviews')) {
            $CI->db->query('CREATE TABLE `tblservice_client_reviews` (
                `id` INT(11) NOT NULL AUTO_INCREMENT,
                `rel_type` VARCHAR(20) NOT NULL DEFAULT "invoice",
                `rel_id` INT(11) NOT NULL,
                `client_id` INT(11) NOT NULL,
                `engineer_id` INT(11) NULL,
                `rating` INT(1) NOT NULL,
                `comment` TEXT,
                `date_created` DATETIME NOT NULL,
                `ip_address` VARCHAR(40),
                PRIMARY KEY (`id`)
            ) ENGINE=InnoDB DEFAULT CHARSET=utf8;');
        }
    }
}