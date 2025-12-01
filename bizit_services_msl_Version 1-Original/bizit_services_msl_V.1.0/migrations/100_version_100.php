<?php

defined('BASEPATH') or exit('No direct script access allowed');

class Migration_Version_100 extends App_module_migration
{
	public function __construct()
	{
		parent::__construct();
	}

	public function up()
	{
		$CI = &get_instance();

		//tblservices
		if (!$CI->db->table_exists(db_prefix() . 'services')) {
			add_option('service_request_prefix', 'REQ-');
			add_option('service_rental_agreement_prefix', 'ARG-');

			$CI->db->query("CREATE TABLE `tblservices` (
                `serviceid` bigint(20) NOT NULL AUTO_INCREMENT PRIMARY KEY,
                `name` varchar(50) NOT NULL
              ) ENGINE=InnoDB DEFAULT CHARSET=utf8;");
		}

		//tblservices
		if (!$CI->db->table_exists(db_prefix() . 'services_module')) {
			$CI->db->query("CREATE TABLE `tblservices_module` (
                `serviceid` bigint(20) NOT NULL AUTO_INCREMENT PRIMARY KEY,
                `service_code` varchar(50) NOT NULL,
                `service_type_code` varchar(50) NOT NULL,
                `name` varchar(50) NOT NULL,
                `price` decimal(11,2) DEFAULT NULL,
                `penalty_rental_price` decimal(11,2) DEFAULT NULL,
                `rental_serial` varchar(100) DEFAULT NULL,
                `rental_status` varchar(10) DEFAULT NULL COMMENT '''Hired'',''Not-Hired''',
                `rental_duration_check` enum('hours','days','weeks','months','years') NOT NULL,
                `description` text,
                `rental_accessories` text,
                `status` tinyint(4) NOT NULL DEFAULT '1',
                `quantity_unit` varchar(100) DEFAULT NULL
              ) ENGINE=InnoDB DEFAULT CHARSET=utf8;");
		}

		if (!$CI->db->table_exists(db_prefix() . 'service_type')) {
			$CI->db->query("CREATE TABLE `tblservice_type` (
				`service_typeid` bigint(20) NOT NULL AUTO_INCREMENT,
				`type_code` varchar(50) NOT NULL,
				`name` varchar(50) NOT NULL,
				`status` tinyint(4) NOT NULL DEFAULT '1',
				PRIMARY KEY (`service_typeid`)
			  ) ENGINE=InnoDB DEFAULT CHARSET=utf8;");

			$CI->db->query("INSERT INTO `tblservice_type` VALUES (1,'001','For Hire',0),(2,'002','Calibration',0),(3,'003','Rinex Data Post Processing',1),(4,'004','Data Corrections',1),(5,'005','Rinex Data',1),(6,'006','Map Details',1)");
		}

		if (!$CI->db->field_exists("invoice_for", db_prefix() . "invoices")) {
			$sql3 = "ALTER TABLE " . db_prefix() . "invoices ADD invoice_for int(2) DEFAULT NULL";
			$CI->db->query($sql3);
		}
	}
}
