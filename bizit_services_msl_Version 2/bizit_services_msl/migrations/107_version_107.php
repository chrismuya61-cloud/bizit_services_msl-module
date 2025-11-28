<?php

defined('BASEPATH') or exit('No direct script access allowed');

class Migration_Version_107 extends App_module_migration

{

    public function up()

    {

        $CI = &get_instance();

        // Add columns to tblfield_report for submission tracking

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

