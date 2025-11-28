<?php

defined('BASEPATH') or exit('No direct script access allowed');

class Migration_Version_104 extends App_module_migration
{
    public function __construct()
    {
        parent::__construct();
    }

    public function up()
    {
        $CI = &get_instance();
        if ($CI->db->table_exists('tblcollection1')) {

            //alter to make nullable
            $CI->db->query("ALTER TABLE tblcollection1 
                            MODIFY COLUMN `released_by` varchar(255) NULL DEFAULT NULL,
                            MODIFY COLUMN `released_date` date NULL DEFAULT NULL,
                            MODIFY COLUMN `released_id_number` varchar(50) NULL DEFAULT NULL,
                            MODIFY COLUMN `collected_by` varchar(255) NULL DEFAULT NULL,
                            MODIFY COLUMN `collected_date` date NULL DEFAULT NULL,
                            MODIFY COLUMN `collected_id_number` varchar(50) NULL DEFAULT NULL;");
        }            
    }

    public function down()
    {
        $CI =& get_instance();

    }
}