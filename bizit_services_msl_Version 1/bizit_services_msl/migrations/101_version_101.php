<?php

defined('BASEPATH') or exit('No direct script access allowed');

class Migration_Version_101 extends App_module_migration
{
    public function __construct()
    {
        parent::__construct();
    }

    public function up()
    {
        $CI = &get_instance();


        if (!$CI->db->table_exists(db_prefix() . 'services_calibration')) {
            $CI->db->query("CREATE TABLE " . db_prefix() . "services_calibration (
                `calibration_id` bigint(20) NOT NULL AUTO_INCREMENT PRIMARY KEY,
                `service_request_id` bigint(20) NOT NULL,
                `calibration_instrument` varchar(50) NOT NULL,
                `i_backsight_a` double DEFAULT NULL,
                `i_foresight_b` double DEFAULT NULL,
                `ii_backsight_a` double DEFAULT NULL,
                `ii_foresight_b` double DEFAULT NULL,
                `iii_backsight_a` double DEFAULT NULL,
                `iii_foresight_b` double DEFAULT NULL,
                `iv_backsight_a` double DEFAULT NULL,
                `iv_foresight_b` double DEFAULT NULL,
                `i_h_a` decimal(11,2) DEFAULT NULL,
                `i_h_b` decimal(11,2) DEFAULT NULL,
                `ii_h_a` decimal(11,2) DEFAULT NULL,
                `ii_h_b` decimal(11,2) DEFAULT NULL,
                `i_v_a` decimal(11,2) DEFAULT NULL,
                `i_v_b` decimal(11,2) DEFAULT NULL,
                `ii_v_a` decimal(11,2) DEFAULT NULL,
                `ii_v_b` decimal(11,2) DEFAULT NULL,
                `i_edm_a_1` double DEFAULT NULL,
                `i_edm_a_2` double DEFAULT NULL,
                `i_edm_a_3` double DEFAULT NULL,
                `i_edm_b_1` double DEFAULT NULL,
                `i_edm_b_2` double DEFAULT NULL,
                `i_edm_b_3` double DEFAULT NULL,
                `ii_edm_a_1` double DEFAULT NULL,
                `ii_edm_a_2` double DEFAULT NULL,
                `ii_edm_a_3` double DEFAULT NULL,
                `ii_edm_b_1` double DEFAULT NULL,
                `ii_edm_b_2` double DEFAULT NULL,
                `ii_edm_b_3` double DEFAULT NULL,
                `date_created` timestamp NOT NULL DEFAULT current_timestamp(),
                `calibration_remark` text DEFAULT NULL
                ) ENGINE=InnoDB DEFAULT CHARSET=utf8;");
        }

        if (!$CI->db->table_exists(db_prefix() . 'service_rental_agreement')) {
            $CI->db->query("CREATE TABLE " . db_prefix() . "service_rental_agreement (
                `service_rental_agreement_id` bigint(20) NOT NULL AUTO_INCREMENT PRIMARY KEY,
                `service_rental_agreement_code` varchar(100) NOT NULL,
                `clientid` bigint(20) NOT NULL,
                `start_date` date NOT NULL,
                `end_date` date DEFAULT NULL,
                `actual_date_returned` date DEFAULT NULL,
                `rental_agreement_note` text NOT NULL,
                `received_by` bigint(20) NOT NULL,
                `status` tinyint(1) DEFAULT 0,
                `invoice_rel_id` bigint(20) DEFAULT NULL,
                `extra_days` decimal(11,1) NOT NULL DEFAULT 0.0,
                `discounted_days` decimal(11,1) NOT NULL DEFAULT 0.0,
                `dateadded` timestamp NOT NULL DEFAULT current_timestamp(),
                `field_operator` int(11) DEFAULT NULL,
                `site_name` mediumtext DEFAULT NULL
                ) ENGINE=InnoDB DEFAULT CHARSET=utf8;");
        }

        if (!$CI->db->table_exists(db_prefix() . 'service_rental_agreement_details')) {
            $CI->db->query("CREATE TABLE " . db_prefix() . "service_rental_agreement_details (
                `service_rental_agreement_details_id` bigint(20) NOT NULL  AUTO_INCREMENT PRIMARY KEY,
                `service_rental_agreement_id` bigint(20) NOT NULL,
                `serviceid` bigint(20) NOT NULL,
                `price` decimal(11,2) NOT NULL DEFAULT 0.00,
                `date_created` timestamp NOT NULL DEFAULT current_timestamp()
                ) ENGINE=InnoDB DEFAULT CHARSET=utf8;");
        }

        if (!$CI->db->table_exists(db_prefix() . 'service_request')) {
            $CI->db->query("CREATE TABLE " . db_prefix() . "service_request (
                `service_request_id` bigint(20) NOT NULL  AUTO_INCREMENT PRIMARY KEY,
                `service_request_code` varchar(100) NOT NULL,
                `clientid` bigint(20) NOT NULL,
                `collection_date` date NOT NULL,
                `drop_off_date` date DEFAULT NULL,
                `condition` varchar(100) NOT NULL,
                `item_type` varchar(100) NOT NULL,
                `item_model` varchar(100) NOT NULL,
                `item_make` varchar(100) DEFAULT '1',
                `serial_no` varchar(100) NOT NULL,
                `service_note` text NOT NULL,
                `received_by` bigint(20) NOT NULL,
                `status` int(11) DEFAULT 0,
                `invoice_rel_id` bigint(20) DEFAULT NULL,
                `payment_method` text DEFAULT NULL,
                `payment_ref` text DEFAULT NULL,
                `calibration` int(11) DEFAULT NULL,
                `online_ref` text DEFAULT NULL
                ) ENGINE=InnoDB DEFAULT CHARSET=utf8;");
        }

        if (!$CI->db->table_exists(db_prefix() . 'service_request_details')) {
            $CI->db->query("CREATE TABLE " . db_prefix() . "service_request_details (
                `service_request_details_id` bigint(20) NOT NULL  AUTO_INCREMENT PRIMARY KEY,
                `service_request_id` bigint(20) NOT NULL,
                `serviceid` bigint(20) NOT NULL,
                `price` decimal(11,2) NOT NULL DEFAULT 0.00,
                `date_created` timestamp NOT NULL DEFAULT current_timestamp()
                ) ENGINE=InnoDB DEFAULT CHARSET=utf8;");
        }

        if (!$CI->db->table_exists(db_prefix() . 'field_report')) {
            $CI->db->query("CREATE TABLE " . db_prefix() . "field_report (
            `field_report_id` bigint(20) NOT NULL AUTO_INCREMENT PRIMARY KEY,
            `report_code` varchar(50) NOT NULL,
            `service_rental_agreement_id` bigint(20) NOT NULL,
            `status` int(11) NOT NULL DEFAULT 0,
            `clientid` bigint(20) NOT NULL,
            `added_by` bigint(20) NOT NULL,
            `approved_by` bigint(20) DEFAULT NULL,
            `approval_remarks` text DEFAULT NULL,
            `rejected_by` bigint(20) DEFAULT NULL,
            `rejection_remarks` text DEFAULT NULL,
            `site_name` mediumtext DEFAULT NULL,
            `control_points_used` text DEFAULT NULL,
            `survey_type` text DEFAULT NULL,
            `survey_report` text DEFAULT NULL,
            `equipment_report` text DEFAULT NULL,
            `comments` text DEFAULT NULL,
            `dateadded` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
            ) ENGINE=InnoDB DEFAULT CHARSET=utf8;");
        }
    }
}
