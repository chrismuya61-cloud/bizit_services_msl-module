<?php

defined('BASEPATH') or exit('No direct script access allowed');

class Migration_Version_102 extends App_module_migration
{
    public function __construct()
    {
        parent::__construct();
    }

    public function up()
    {
        $CI = &get_instance();

        // Drop the table if it exists
        // $CI->db->query("DROP TABLE IF EXISTS " . db_prefix() . "services_calibration");
        if ($CI->db->table_exists(db_prefix() . 'services_calibration')) {
            $table = db_prefix() . 'services_calibration';

            // Add missing fields from schema 2
            $missingColumns = [
                
                "`i_h_a` decimal(11,2) DEFAULT NULL",
                "`i_h_b` decimal(11,2) DEFAULT NULL",
                "`ii_h_a` decimal(11,2) DEFAULT NULL",
                "`ii_h_b` decimal(11,2) DEFAULT NULL",
                "`i_v_a` decimal(11,2) DEFAULT NULL",
                "`i_v_b` decimal(11,2) DEFAULT NULL",
                "`ii_v_a` decimal(11,2) DEFAULT NULL",
                "`ii_v_b` decimal(11,2) DEFAULT NULL",
                "`i_v_a_1` decimal(11,3) DEFAULT NULL",
                "`i_v_a_2` decimal(11,3) DEFAULT NULL",
                "`i_v_a_3` decimal(11,3) DEFAULT NULL",
                "`ii_v_a_1` decimal(11,3) DEFAULT NULL",
                "`ii_v_a_2` decimal(11,3) DEFAULT NULL",
                "`ii_v_a_3` decimal(11,3) DEFAULT NULL",
                "`iii_v_a_1` decimal(11,3) DEFAULT NULL",
                "`iii_v_a_2` decimal(11,3) DEFAULT NULL",
                "`iii_v_a_3` decimal(11,3) DEFAULT NULL",
                "`iv_v_a_1` decimal(11,3) DEFAULT NULL",
                "`iv_v_a_2` decimal(11,3) DEFAULT NULL",
                "`iv_v_a_3` decimal(11,3) DEFAULT NULL",
                "`v_v_a_1` decimal(11,3) DEFAULT NULL",
                "`v_v_a_2` decimal(11,3) DEFAULT NULL",
                "`v_v_a_3` decimal(11,3) DEFAULT NULL",
                "`vi_v_a_1` decimal(11,3) DEFAULT NULL",
                "`vi_v_a_2` decimal(11,3) DEFAULT NULL",
                "`vi_v_a_3` decimal(11,3) DEFAULT NULL",
                "`vii_v_a_1` decimal(11,3) DEFAULT NULL",
                "`vii_v_a_2` decimal(11,3) DEFAULT NULL",
                "`vii_v_a_3` decimal(11,3) DEFAULT NULL",
                "`viii_v_a_1` decimal(11,3) DEFAULT NULL",
                "`viii_v_a_2` decimal(11,3) DEFAULT NULL",
                "`viii_v_a_3` decimal(11,3) DEFAULT NULL",
                "`xi_v_a_1` decimal(11,3) DEFAULT NULL",
                "`xi_v_a_2` decimal(11,3) DEFAULT NULL",
                "`xi_v_a_3` decimal(11,3) DEFAULT NULL",
                "`x_v_a_1` decimal(11,3) DEFAULT NULL",
                "`x_v_a_2` decimal(11,3) DEFAULT NULL",
                "`x_v_a_3` decimal(11,3) DEFAULT NULL",
                "`r_v_a_1` varchar(50) DEFAULT NULL",
                "`r_v_a_2` varchar(50) DEFAULT NULL",
                "`r_v_a_3` varchar(50) DEFAULT NULL",
                "`r_v_a_4` varchar(50) DEFAULT NULL",
                "`r_v_a_5` int(11) DEFAULT NULL",
                "`r_v_a_6` varchar(50) DEFAULT NULL",
                "`r_v_a_7` int(11) DEFAULT NULL",
                "`r_v_a_8` int(11) DEFAULT NULL",
                "`r_v_a_9` double DEFAULT NULL",
                "`r_v_a_10` double DEFAULT NULL",
                "`r_v_a_11` int(11) DEFAULT NULL",
                "`r_v_a_12` int(11) DEFAULT NULL",
                "`r_v_a_13` double DEFAULT NULL",
                "`i_v_b_1` decimal(11,3) DEFAULT NULL",
                "`i_v_b_2` decimal(11,3) DEFAULT NULL",
                "`i_v_b_3` decimal(11,3) DEFAULT NULL",
                "`ii_v_b_1` decimal(11,3) DEFAULT NULL",
                "`ii_v_b_2` decimal(11,3) DEFAULT NULL",
                "`ii_v_b_3` decimal(11,3) DEFAULT NULL",
                "`iii_v_b_1` decimal(11,3) DEFAULT NULL",
                "`iii_v_b_2` decimal(11,3) DEFAULT NULL",
                "`iii_v_b_3` decimal(11,3) DEFAULT NULL",
                "`iv_v_b_1` decimal(11,3) DEFAULT NULL",
                "`iv_v_b_2` decimal(11,3) DEFAULT NULL",
                "`iv_v_b_3` decimal(11,3) DEFAULT NULL",
                "`v_v_b_1` decimal(11,3) DEFAULT NULL",
                "`v_v_b_2` decimal(11,3) DEFAULT NULL",
                "`v_v_b_3` decimal(11,3) DEFAULT NULL",
                "`vi_v_b_1` decimal(11,3) DEFAULT NULL",
                "`vi_v_b_2` decimal(11,3) DEFAULT NULL",
                "`vi_v_b_3` decimal(11,3) DEFAULT NULL",
                "`vii_v_b_1` decimal(11,3) DEFAULT NULL",
                "`vii_v_b_2` decimal(11,3) DEFAULT NULL",
                "`vii_v_b_3` decimal(11,3) DEFAULT NULL",
                "`viii_v_b_1` decimal(11,3) DEFAULT NULL",
                "`viii_v_b_2` decimal(11,3) DEFAULT NULL",
                "`viii_v_b_3` decimal(11,3) DEFAULT NULL",
                "`xi_v_b_1` decimal(11,3) DEFAULT NULL",
                "`xi_v_b_2` decimal(11,3) DEFAULT NULL",
                "`xi_v_b_3` decimal(11,3) DEFAULT NULL",
                "`x_v_b_1` decimal(11,3) DEFAULT NULL",
                "`x_v_b_2` decimal(11,3) DEFAULT NULL",
                "`x_v_b_3` decimal(11,3) DEFAULT NULL",
                "`i_v_c_1` decimal(11,3) DEFAULT NULL",
                "`i_v_c_2` decimal(11,3) DEFAULT NULL",
                "`i_v_c_3` decimal(11,3) DEFAULT NULL",
                "`ii_v_c_1` decimal(11,3) DEFAULT NULL",
                "`ii_v_c_2` decimal(11,3) DEFAULT NULL",
                "`ii_v_c_3` decimal(11,3) DEFAULT NULL",
                "`iii_v_c_1` decimal(11,3) DEFAULT NULL",
                "`iii_v_c_2` decimal(11,3) DEFAULT NULL",
                "`iii_v_c_3` decimal(11,3) DEFAULT NULL",
                "`iv_v_c_1` decimal(11,3) DEFAULT NULL",
                "`iv_v_c_2` decimal(11,3) DEFAULT NULL",
                "`iv_v_c_3` decimal(11,3) DEFAULT NULL",
                "`v_v_c_1` decimal(11,3) NOT NULL",
                "`v_v_c_2` decimal(11,3) DEFAULT NULL",
                "`v_v_c_3` decimal(11,3) DEFAULT NULL",
                "`vi_v_c_1` decimal(11,3) DEFAULT NULL",
                "`vi_v_c_2` decimal(11,3) DEFAULT NULL",
                "`vi_v_c_3` decimal(11,3) DEFAULT NULL",
                "`vii_v_c_1` decimal(11,3) DEFAULT NULL",
                "`vii_v_c_2` decimal(11,3) DEFAULT NULL",
                "`vii_v_c_3` decimal(11,3) DEFAULT NULL",
                "`viii_v_c_1` decimal(11,3) DEFAULT NULL",
                "`viii_v_c_2` decimal(11,3) DEFAULT NULL",
                "`viii_v_c_3` decimal(11,3) DEFAULT NULL",
                "`xi_v_c_1` decimal(11,3) DEFAULT NULL",
                "`xi_v_c_2` decimal(11,3) DEFAULT NULL",
                "`xi_v_c_3` decimal(11,3) DEFAULT NULL",
                "`x_v_c_1` decimal(11,3) DEFAULT NULL",
                "`x_v_c_2` decimal(11,3) DEFAULT NULL",
                "`x_v_c_3` decimal(11,3) DEFAULT NULL",
                "`i_v_aa_1` decimal(11,3) DEFAULT NULL",
                "`i_v_aa_2` decimal(11,3) DEFAULT NULL",
                "`i_v_aa_3` decimal(11,3) DEFAULT NULL",
                "`ii_v_aa_1` decimal(11,3) DEFAULT NULL",
                "`ii_v_aa_2` decimal(11,3) DEFAULT NULL",
                "`ii_v_aa_3` decimal(11,3) DEFAULT NULL",
                "`iii_v_aa_1` decimal(11,3) DEFAULT NULL",
                "`iii_v_aa_2` decimal(11,3) DEFAULT NULL",
                "`iii_v_aa_3` decimal(11,3) DEFAULT NULL",
                "`iv_v_aa_1` decimal(11,3) DEFAULT NULL",
                "`iv_v_aa_2` decimal(11,3) DEFAULT NULL",
                "`iv_v_aa_3` decimal(11,3) DEFAULT NULL",
                "`v_v_aa_1` decimal(11,3) DEFAULT NULL",
                "`v_v_aa_2` decimal(11,3) DEFAULT NULL",
                "`v_v_aa_3` decimal(11,3) DEFAULT NULL",
                "`vi_v_aa_1` decimal(11,3) DEFAULT NULL",
                "`vi_v_aa_2` decimal(11,3) DEFAULT NULL",
                "`vi_v_aa_3` decimal(11,3) DEFAULT NULL",
                "`vii_v_aa_1` decimal(11,3) DEFAULT NULL",
                "`vii_v_aa_2` decimal(11,3) DEFAULT NULL",
                "`vii_v_aa_3` decimal(11,3) DEFAULT NULL",
                "`viii_v_aa_1` decimal(11,3) DEFAULT NULL",
                "`viii_v_aa_2` decimal(11,3) DEFAULT NULL",
                "`viii_v_aa_3` decimal(11,3) DEFAULT NULL",
                "`xi_v_aa_1` decimal(11,3) DEFAULT NULL",
                "`xi_v_aa_2` decimal(11,3) DEFAULT NULL",
                "`xi_v_aa_3` decimal(11,3) DEFAULT NULL",
                "`x_v_aa_1` decimal(11,3) DEFAULT NULL",
                "`x_v_aa_2` decimal(11,3) DEFAULT NULL",
                "`x_v_aa_3` decimal(11,3) DEFAULT NULL",
                "`i_v_bb_1` decimal(11,3) DEFAULT NULL",
                "`i_v_bb_2` decimal(11,3) DEFAULT NULL",
                "`i_v_bb_3` decimal(11,3) DEFAULT NULL",
                "`ii_v_bb_1` decimal(11,3) DEFAULT NULL",
                "`ii_v_bb_2` decimal(11,3) DEFAULT NULL",
                "`ii_v_bb_3` decimal(11,3) DEFAULT NULL",
                "`iii_v_bb_1` decimal(11,3) DEFAULT NULL",
                "`iii_v_bb_2` decimal(11,3) DEFAULT NULL",
                "`iii_v_bb_3` decimal(11,3) DEFAULT NULL",
                "`iv_v_bb_1` decimal(11,3) DEFAULT NULL",
                "`iv_v_bb_2` decimal(11,3) DEFAULT NULL",
                "`iv_v_bb_3` decimal(11,3) DEFAULT NULL",
                "`v_v_bb_1` decimal(11,3) DEFAULT NULL",
                "`v_v_bb_2` decimal(11,3) DEFAULT NULL",
                "`v_v_bb_3` decimal(11,3) DEFAULT NULL",
                "`vi_v_bb_1` decimal(11,3) DEFAULT NULL",
                "`vi_v_bb_2` decimal(11,3) DEFAULT NULL",
                "`vi_v_bb_3` decimal(11,3) DEFAULT NULL",
                "`vii_v_bb_1` decimal(11,3) DEFAULT NULL",
                "`vii_v_bb_2` decimal(11,3) DEFAULT NULL",
                "`vii_v_bb_3` decimal(11,3) DEFAULT NULL",
                "`viii_v_bb_1` decimal(11,3) DEFAULT NULL",
                "`viii_v_bb_2` decimal(11,3) DEFAULT NULL",
                "`viii_v_bb_3` decimal(11,3) DEFAULT NULL",
                "`xi_v_bb_1` decimal(11,3) DEFAULT NULL",
                "`xi_v_bb_2` decimal(11,3) DEFAULT NULL",
                "`xi_v_bb_3` decimal(11,3) DEFAULT NULL",
                "`x_v_bb_1` decimal(11,3) DEFAULT NULL",
                "`x_v_bb_2` decimal(11,3) DEFAULT NULL",
                "`x_v_bb_3` decimal(11,3) DEFAULT NULL",
                "`i_v_cc_1` decimal(11,3) DEFAULT NULL",
                "`i_v_cc_2` decimal(11,3) DEFAULT NULL",
                "`i_v_cc_3` decimal(11,3) DEFAULT NULL",
                "`ii_v_cc_1` decimal(11,3) DEFAULT NULL",
                "`ii_v_cc_2` decimal(11,3) DEFAULT NULL",
                "`ii_v_cc_3` decimal(11,3) DEFAULT NULL",
                "`iii_v_cc_1` decimal(11,3) DEFAULT NULL",
                "`iii_v_cc_2` decimal(11,3) DEFAULT NULL",
                "`iii_v_cc_3` decimal(11,3) DEFAULT NULL",
                "`iv_v_cc_1` decimal(11,3) DEFAULT NULL",
                "`iv_v_cc_2` decimal(11,3) DEFAULT NULL",
                "`iv_v_cc_3` decimal(11,3) DEFAULT NULL",
                "`v_v_cc_1` decimal(11,3) DEFAULT NULL",
                "`v_v_cc_2` decimal(11,3) DEFAULT NULL",
                "`v_v_cc_3` decimal(11,3) DEFAULT NULL",
                "`vi_v_cc_1` decimal(11,3) DEFAULT NULL",
                "`vi_v_cc_2` decimal(11,3) DEFAULT NULL",
                "`vi_v_cc_3` decimal(11,3) DEFAULT NULL",
                "`vii_v_cc_1` decimal(11,3) DEFAULT NULL",
                "`vii_v_cc_2` decimal(11,3) DEFAULT NULL",
                "`vii_v_cc_3` decimal(11,3) DEFAULT NULL",
                "`viii_v_cc_1` decimal(11,3) DEFAULT NULL",
                "`viii_v_cc_2` decimal(11,3) DEFAULT NULL",
                "`viii_v_cc_3` decimal(11,3) DEFAULT NULL",
                "`xi_v_cc_1` decimal(11,3) DEFAULT NULL",
                "`xi_v_cc_2` decimal(11,3) DEFAULT NULL",
                "`xi_v_cc_3` decimal(11,3) DEFAULT NULL",
                "`x_v_cc_1` decimal(11,3) DEFAULT NULL",
                "`x_v_cc_2` decimal(11,3) DEFAULT NULL",
                "`x_v_cc_3` decimal(11,3) DEFAULT NULL",
                "`start_time_1` datetime DEFAULT NULL",
                "`stop_time_1` datetime DEFAULT NULL",
                "`start_time_2` datetime DEFAULT NULL",
                "`stop_time_2` datetime DEFAULT NULL",
                "`start_time_3` datetime DEFAULT NULL",
                "`stop_time_3` datetime DEFAULT NULL",
                "`start_time_4` datetime DEFAULT NULL",
                "`stop_time_4` datetime DEFAULT NULL",
                "`start_time_5` datetime DEFAULT NULL",
                "`stop_time_5` datetime DEFAULT NULL",
                "`start_time_6` datetime DEFAULT NULL",
                "`stop_time_6` datetime DEFAULT NULL",
                "`i_edm_a_1` double DEFAULT NULL",
                "`i_edm_a_2` double DEFAULT NULL",
                "`i_edm_a_3` double DEFAULT NULL",
                "`i_edm_a_4` double DEFAULT NULL",
                "`i_edm_a_5` double DEFAULT NULL",
                "`i_edm_a_6` double DEFAULT NULL",
                "`i_edm_a_7` double DEFAULT NULL",
                "`i_edm_a_8` double DEFAULT NULL",
                "`i_edm_a_9` double DEFAULT NULL",
                "`i_edm_a_10` double DEFAULT NULL",
                "`i_edm_a_11` double DEFAULT NULL",
                "`i_edm_a_12` double DEFAULT NULL",
                "`i_edm_a_13` double DEFAULT NULL",
                "`i_edm_a_14` double DEFAULT NULL",
                "`i_edm_a_15` double DEFAULT NULL",
                "`i_edm_a_16` double DEFAULT NULL",
                "`i_edm_a_17` double DEFAULT NULL",
                "`i_edm_a_18` double DEFAULT NULL",
                "`i_edm_a_19` double DEFAULT NULL",
                "`i_edm_a_20` double DEFAULT NULL",
                "`i_edm_a_21` double DEFAULT NULL",
                "`i_edm_a_22` double DEFAULT NULL",
                "`i_edm_a_23` double DEFAULT NULL",
                "`i_edm_a_24` double DEFAULT NULL",
                "`i_edm_a_25` double DEFAULT NULL",
                "`i_edm_a_26` double DEFAULT NULL",
                "`i_edm_a_27` double DEFAULT NULL",
                "`i_edm_a_28` double DEFAULT NULL",
                "`i_edm_a_29` double DEFAULT NULL",
                "`i_edm_a_30` double DEFAULT NULL",
                "`i_edm_a_31` double DEFAULT NULL",
                "`i_edm_a_32` double DEFAULT NULL",
                "`i_edm_a_33` double DEFAULT NULL",
                "`i_edm_a_34` double DEFAULT NULL",
                "`i_edm_a_35` double DEFAULT NULL",
                "`i_edm_a_36` double DEFAULT NULL",
                "`i_edm_a_37` double DEFAULT NULL",
                "`i_edm_a_38` double DEFAULT NULL",
                "`i_edm_a_39` double DEFAULT NULL",
                "`i_edm_a_40` double DEFAULT NULL",
                "`i_edm_a_41` double DEFAULT NULL",
                "`i_edm_a_42` double DEFAULT NULL",
                "`i_edm_a_43` double DEFAULT NULL",
                "`i_edm_a_44` double DEFAULT NULL",
                "`i_edm_a_45` double DEFAULT NULL",
                "`i_edm_a_46` double DEFAULT NULL",
                "`i_edm_a_47` double DEFAULT NULL",
                "`i_edm_a_48` double DEFAULT NULL",
                "`t_v_a_1` varchar(50) DEFAULT NULL",
                "`t_v_a_2` varchar(50) DEFAULT NULL",
                "`t_v_a_3` varchar(50) DEFAULT NULL",
                "`t_v_a_4` varchar(50) DEFAULT NULL",
                "`t_v_a_5` int(11) DEFAULT NULL",
                "`t_v_a_6` varchar(50) DEFAULT NULL",
                "`t_v_a_7` int(11) DEFAULT NULL",
                "`t_v_a_8` int(11) DEFAULT NULL",
                "`t_v_a_9` int(11) DEFAULT NULL",
                "`t_v_a_10` int(11) DEFAULT NULL",
                "`t_v_a_11` int(11) DEFAULT NULL",
                "`t_v_a_12` double DEFAULT NULL",
                "`t_v_a_13` int(11) DEFAULT NULL",
                "`t_v_a_14` double DEFAULT NULL",
                "`t_v_a_15` varchar(50) DEFAULT NULL",
                "`t_v_a_16` varchar(50) DEFAULT NULL",
                "`i_edm_a_49` double DEFAULT NULL",
                "`i_edm_a_50` double DEFAULT NULL",
                "`i_edm_a_51` double DEFAULT NULL",
                "`i_edm_a_52` double DEFAULT NULL",
                "`i_edm_a_53` double DEFAULT NULL",
                "`i_edm_a_54` double DEFAULT NULL",
                "`i_edm_a_55` double DEFAULT NULL",
                "`i_edm_a_56` double DEFAULT NULL",
                "`i_edm_a_57` double DEFAULT NULL",
                "`i_edm_a_58` double DEFAULT NULL",
                "`i_edm_a_59` double DEFAULT NULL",
                "`i_edm_a_60` double DEFAULT NULL",
                "`i_edm_a_61` double DEFAULT NULL",
                "`i_edm_a_62` double DEFAULT NULL",
                "`i_edm_a_63` double DEFAULT NULL",
                "`i_edm_a_64` double DEFAULT NULL",
                "`i_edm_a_65` double DEFAULT NULL",
                "`i_edm_a_66` double DEFAULT NULL",
                "`i_edm_a_67` double DEFAULT NULL",
                "`i_edm_a_68` double DEFAULT NULL",
                "`i_edm_a_69` double DEFAULT NULL",
                "`i_edm_a_70` double DEFAULT NULL",
                "`i_edm_a_71` double DEFAULT NULL",
                "`i_edm_a_72` double DEFAULT NULL",
                "`i_edm_a_73` double DEFAULT NULL",
                "`i_edm_a_74` double DEFAULT NULL",
                "`i_edm_a_75` double DEFAULT NULL",
                "`i_edm_a_76` double DEFAULT NULL",
                "`i_edm_a_77` double DEFAULT NULL",
                "`i_edm_a_78` double DEFAULT NULL",
                "`i_edm_a_79` double DEFAULT NULL",
                "`i_edm_a_80` double DEFAULT NULL",
                "`i_edm_a_81` double DEFAULT NULL",
                "`i_edm_a_82` double DEFAULT NULL",
                "`i_edm_a_83` double DEFAULT NULL",
                "`i_edm_a_84` double DEFAULT NULL",
                "`i_edm_a_85` double DEFAULT NULL",
                "`i_edm_a_86` double DEFAULT NULL",
                "`i_edm_a_87` double DEFAULT NULL",
                "`i_edm_a_88` double DEFAULT NULL",
                "`i_edm_a_89` double DEFAULT NULL",
                "`i_edm_a_90` double DEFAULT NULL",
                "`i_edm_a_91` double DEFAULT NULL",
                "`i_edm_a_92` double DEFAULT NULL",
                "`i_edm_a_93` double DEFAULT NULL",
                "`i_edm_a_94` double DEFAULT NULL",
                "`i_edm_a_95` double DEFAULT NULL",
                "`i_edm_a_96` double DEFAULT NULL",
                "`t_h_a` decimal(11,2) DEFAULT NULL",
                "`t_h_b` decimal(11,2) DEFAULT NULL",
                "`tt_h_a` decimal(11,2) DEFAULT NULL",
                "`tt_h_b` decimal(11,2) DEFAULT NULL",
                "`t_v_a` decimal(11,2) DEFAULT NULL",
                "`t_v_b` decimal(11,2) DEFAULT NULL",
                "`tt_v_a` decimal(11,2) DEFAULT NULL",
                "`tt_v_b` decimal(11,2) DEFAULT NULL",
                "`th_v_a_1` varchar(255) NOT NULL",
                "`th_v_a_2` varchar(255) NOT NULL",
                "`th_v_a_3` varchar(255) NOT NULL",
                "`th_v_a_4` varchar(255) NOT NULL",
                "`th_v_a_5` decimal(10,2) NOT NULL",
                "`th_v_a_6` varchar(255) NOT NULL",
                "`th_v_a_7` decimal(5,2) NOT NULL",
                "`th_v_a_8` decimal(5,2) NOT NULL",
                "`th_v_a_9` decimal(5,3) NOT NULL",
                "`th_v_a_10` decimal(5,2) NOT NULL",
                "`th_v_a_11` decimal(5,2) NOT NULL",
                "`th_v_a_12` decimal(5,2) NOT NULL",
                "`th_v_a_13` varchar(255) NOT NULL",
                "`th_v_a_14` varchar(255) NOT NULL",
                "`th_h_a` decimal(11,2) NOT NULL",
                "`th_h_b` decimal(11,2) NOT NULL",
                "`thh_h_a` decimal(11,2) NOT NULL",
                "`thh_h_b` decimal(11,2) NOT NULL",
                "`th_v_a` decimal(11,2) NOT NULL",
                "`th_v_b` decimal(11,2) NOT NULL",
                "`th_v_c` decimal(11,2) NOT NULL",
                "`thh_v_a` decimal(11,2) NOT NULL",
                "`thh_v_b` decimal(11,2) NOT NULL",
                "`thh_v_c` decimal(11,2) NOT NULL",
                "`th_h_a1` decimal(11,2) NOT NULL",
                "`th_h_b1` decimal(11,2) NOT NULL",
                "`thh_h_a1` decimal(11,2) NOT NULL",
                "`thh_h_b1` decimal(11,2) NOT NULL",
                "`th_v_a1` decimal(11,2) NOT NULL",
                "`th_v_b1` decimal(11,2) NOT NULL",
                "`thh_v_a1` decimal(11,2) NOT NULL",
                "`thh_v_b1` decimal(11,2) NOT NULL",
                "`thh_v_c1` decimal(11,2) NOT NULL",
                "`i_backsight_a` int(11) DEFAULT NULL",
                "`i_foresight_b` int(11) DEFAULT NULL",
                "`i_backsight_c` int(11) DEFAULT NULL",
                "`i_foresight_d` int(11) DEFAULT NULL",
                "`ii_backsight_a` int(11) DEFAULT NULL",
                "`ii_foresight_b` int(11) DEFAULT NULL",
                "`ii_backsight_c` int(11) DEFAULT NULL",
                "`ii_foresight_d` int(11) DEFAULT NULL",
                "`iii_backsight_a` int(11) DEFAULT NULL",
                "`iii_foresight_b` int(11) DEFAULT NULL",
                "`iii_backsight_c` int(11) DEFAULT NULL",
                "`iii_foresight_d` int(11) DEFAULT NULL",
                "`iv_backsight_a` int(11) DEFAULT NULL",
                "`iv_foresight_b` int(11) DEFAULT NULL",
                "`iv_backsight_c` int(11) DEFAULT NULL",
                "`iv_foresight_d` int(11) DEFAULT NULL",
                "`v_backsight_a` int(11) DEFAULT NULL",
                "`v_foresight_b` int(11) DEFAULT NULL",
                "`v_backsight_c` int(11) DEFAULT NULL",
                "`v_foresight_d` int(11) DEFAULT NULL",
                "`vi_backsight_a` int(11) DEFAULT NULL",
                "`vi_foresight_b` int(11) DEFAULT NULL",
                "`vi_backsight_c` int(11) DEFAULT NULL",
                "`vi_foresight_d` int(11) DEFAULT NULL",
                "`vii_backsight_a` int(11) DEFAULT NULL",
                "`vii_foresight_b` int(11) DEFAULT NULL",
                "`vii_backsight_c` int(11) DEFAULT NULL",
                "`vii_foresight_d` int(11) DEFAULT NULL",
                "`viii_backsight_a` int(11) DEFAULT NULL",
                "`viii_foresight_b` int(11) DEFAULT NULL",
                "`viii_backsight_c` int(11) DEFAULT NULL",
                "`viii_foresight_d` int(11) DEFAULT NULL",
                "`ix_backsight_a` int(11) DEFAULT NULL",
                "`ix_foresight_b` int(11) DEFAULT NULL",
                "`ix_backsight_c` int(11) DEFAULT NULL",
                "`ix_foresight_d` int(11) DEFAULT NULL",
                "`x_backsight_a` int(11) DEFAULT NULL",
                "`x_foresight_b` int(11) DEFAULT NULL",
                "`x_backsight_c` int(11) DEFAULT NULL",
                "`x_foresight_d` int(11) DEFAULT NULL",
                "`i_backsight_e` int(11) DEFAULT NULL",
                "`i_foresight_f` int(11) DEFAULT NULL",
                "`i_backsight_g` int(11) DEFAULT NULL",
                "`i_foresight_h` int(11) DEFAULT NULL",
                "`ii_backsight_e` int(11) DEFAULT NULL",
                "`ii_foresight_f` int(11) DEFAULT NULL",
                "`ii_backsight_g` int(11) DEFAULT NULL",
                "`ii_foresight_h` int(11) DEFAULT NULL",
                "`iii_backsight_e` int(11) DEFAULT NULL",
                "`iii_foresight_f` int(11) DEFAULT NULL",
                "`iii_backsight_g` int(11) DEFAULT NULL",
                "`iii_foresight_h` int(11) DEFAULT NULL",
                "`iv_backsight_e` int(11) DEFAULT NULL",
                "`iv_foresight_f` int(11) DEFAULT NULL",
                "`iv_backsight_g` int(11) DEFAULT NULL",
                "`iv_foresight_h` int(11) DEFAULT NULL",
                "`v_backsight_e` int(11) DEFAULT NULL",
                "`v_foresight_f` int(11) DEFAULT NULL",
                "`v_backsight_g` int(11) DEFAULT NULL",
                "`v_foresight_h` int(11) DEFAULT NULL",
                "`vi_backsight_e` int(11) DEFAULT NULL",
                "`vi_foresight_f` int(11) DEFAULT NULL",
                "`vi_backsight_g` int(11) DEFAULT NULL",
                "`vi_foresight_h` int(11) DEFAULT NULL",
                "`vii_backsight_e` int(11) DEFAULT NULL",
                "`vii_foresight_f` int(11) DEFAULT NULL",
                "`vii_backsight_g` int(11) DEFAULT NULL",
                "`vii_foresight_h` int(11) DEFAULT NULL",
                "`viii_backsight_e` int(11) DEFAULT NULL",
                "`viii_foresight_f` int(11) DEFAULT NULL",
                "`viii_backsight_g` int(11) DEFAULT NULL",
                "`viii_foresight_h` int(11) DEFAULT NULL",
                "`ix_backsight_e` int(11) DEFAULT NULL",
                "`ix_foresight_f` int(11) DEFAULT NULL",
                "`ix_backsight_g` int(11) DEFAULT NULL",
                "`ix_foresight_h` int(11) DEFAULT NULL",
                "`x_backsight_e` int(11) DEFAULT NULL",
                "`x_foresight_f` int(11) DEFAULT NULL",
                "`x_backsight_g` int(11) DEFAULT NULL",
                "`x_foresight_h` int(11) DEFAULT NULL",
                "`lv_v_a_1` varchar(255) DEFAULT NULL",
                "`lv_v_a_2` varchar(255) DEFAULT NULL",
                "`lv_v_a_3` varchar(255) DEFAULT NULL",
                "`lv_v_a_4` varchar(255) DEFAULT NULL",
                "`lv_v_a_5` double DEFAULT NULL",
                "`lv_v_a_6` double DEFAULT NULL",
                "`lv_v_a_7` varchar(255) DEFAULT NULL",
                "`lv_v_a_8` double DEFAULT NULL",
                "`lv_v_a_9` double DEFAULT NULL",
                "`ls_v_a_1` varchar(255) DEFAULT NULL",
                "`ls_v_a_2` varchar(255) DEFAULT NULL",
                "`ls_v_a_3` varchar(255) DEFAULT NULL",
                "`ls_v_a_4` varchar(255) DEFAULT NULL",
                "`ls_v_a_5` decimal(10,2) DEFAULT NULL",
                "`ls_v_a_6` decimal(10,2) DEFAULT NULL",
                "`ls_v_a_7` varchar(255) DEFAULT NULL",
                "`ls_v_a_8` decimal(5,2) DEFAULT NULL",
                "`ls_v_a_9` decimal(6,2) DEFAULT NULL",
                "`hh_bsa1` int DEFAULT NULL",
                "`hh_fsa1` int DEFAULT NULL",
                "`hl_bsa1` int DEFAULT NULL",
                "`hl_fsa1` int DEFAULT NULL",
                "`hh_bsa2` int DEFAULT NULL",
                "`hh_fsa2` int DEFAULT NULL",
                "`vl_bsa1` int DEFAULT NULL",
                "`vl_fsa1` int DEFAULT NULL",
                "`hh_bsa3` int DEFAULT NULL",
                "`hh_fsa3` int DEFAULT NULL",
                "`hl_bsa2` int DEFAULT NULL",
                "`hl_fsa2` int DEFAULT NULL",
                "`hh_bsa4` int DEFAULT NULL",
                "`hh_fsa4` int DEFAULT NULL",
                "`vl_bsa2` int DEFAULT NULL",
                "`vl_fsa2` int DEFAULT NULL",
            ];

            foreach ($missingColumns as $colDef) {
                if (!$CI->db->field_exists(trim(explode(' ', $colDef)[0], '`'), $table)) {
                    $CI->db->query("ALTER TABLE `$table` ADD COLUMN $colDef;");
                }
            }
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
        } else {
            // Check if the 'report_file' column exists in the 'field_report' table
            if (!$CI->db->field_exists('report_files', db_prefix() . 'service_rental_agreement')) {
                // Add the 'report_file' column if it does not exist
                $CI->db->query("ALTER TABLE " . db_prefix() . "service_rental_agreement 
                ADD COLUMN `report_files` text DEFAULT NULL;");
            }

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
        } else {
            // Check if the 'report_file' column exists in the 'field_report' table
            if (!$CI->db->field_exists('report_files', db_prefix() . 'service_request')) {
                // Add the 'report_file' column if it does not exist
                $CI->db->query("ALTER TABLE " . db_prefix() . "service_request 
                ADD COLUMN `report_files` text DEFAULT NULL;");
            }
            // Check if the 'dropped_off_by' column exists in the 'service_request' table
            if (!$CI->db->field_exists('dropped_off_by', db_prefix() . 'service_request')) {
                // Add the 'dropped_off_by' column if it does not exist
                $CI->db->query("ALTER TABLE " . db_prefix() . "service_request 
                ADD COLUMN `dropped_off_by` varchar(255) DEFAULT NULL;");
            }

            // Check if the 'dropped_off_date' column exists in the 'service_request' table
            if (!$CI->db->field_exists('dropped_off_date', db_prefix() . 'service_request')) {
                // Add the 'dropped_off_date' column if it does not exist
                $CI->db->query("ALTER TABLE " . db_prefix() . "service_request 
                ADD COLUMN `dropped_off_date` date DEFAULT NULL;");
            }

            // Check if the 'dropped_off_signature' column exists in the 'service_request' table
            if (!$CI->db->field_exists('dropped_off_signature', db_prefix() . 'service_request')) {
                // Add the 'dropped_off_signature' column if it does not exist
                $CI->db->query("ALTER TABLE " . db_prefix() . "service_request 
                ADD COLUMN `dropped_off_signature` varchar(255) DEFAULT NULL;");
            }

            // Check if the 'dropped_off_id_number' column exists in the 'service_request' table
            if (!$CI->db->field_exists('dropped_off_id_number', db_prefix() . 'service_request')) {
                // Add the 'dropped_off_id_number' column if it does not exist
                $CI->db->query("ALTER TABLE " . db_prefix() . "service_request 
                ADD COLUMN `dropped_off_id_number` varchar(255) DEFAULT NULL;");
            }

            // Check if the 'req_received_by' column exists in the 'service_request' table
            if (!$CI->db->field_exists('req_received_by', db_prefix() . 'service_request')) {
                // Add the 'req_received_by' column if it does not exist
                $CI->db->query("ALTER TABLE " . db_prefix() . "service_request 
                ADD COLUMN `req_received_by` varchar(255) DEFAULT NULL;");
            }
            // Check if 'received_date' column exists
            if (!$CI->db->field_exists('received_date', db_prefix() . 'service_request')) {
                $CI->db->query("ALTER TABLE " . db_prefix() . "service_request 
                    ADD COLUMN `received_date` DATETIME DEFAULT NULL;");
            }

            // Check if 'received_signature' column exists
            if (!$CI->db->field_exists('received_signature', db_prefix() . 'service_request')) {
                $CI->db->query("ALTER TABLE " . db_prefix() . "service_request 
                    ADD COLUMN `received_signature` TEXT DEFAULT NULL;");
            }

            // Check if 'received_id_number' column exists
            if (!$CI->db->field_exists('received_id_number', db_prefix() . 'service_request')) {
                $CI->db->query("ALTER TABLE " . db_prefix() . "service_request 
                    ADD COLUMN `received_id_number` VARCHAR(255) DEFAULT NULL;");
            }

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
            // Create the field_report table if it doesn't exist
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
        } else {
            // Check if the 'report_file' column exists in the 'field_report' table
            if (!$CI->db->field_exists('report_files', db_prefix() . 'field_report')) {
                // Add the 'report_file' column if it does not exist
                $CI->db->query("ALTER TABLE " . db_prefix() . "field_report 
                ADD COLUMN `report_files` VARCHAR(255) DEFAULT NULL;");
            }

        }


        if (!$CI->db->table_exists(db_prefix() . 'warranty')) {
            $CI->db->query("CREATE TABLE " . db_prefix() . "warranty (
                `id` int(11) NOT NULL AUTO_INCREMENT PRIMARY KEY,
                `name` varchar(255) NOT NULL,
                `service_code` varchar(50) NOT NULL,
                `commodity_code` varchar(255) NOT NULL,
                `serial_number` varchar(255) NOT NULL,
                `service_type_code` varchar(50) NOT NULL,
                `date_sold` date NOT NULL,
                `warranty_days_remaining` int(11) NOT NULL,
                `warranty_end_date` date NOT NULL,
                `description` text DEFAULT NULL,
                `quantity_unit` varchar(50) NOT NULL,
                `penalty_rental_price` int(11) NOT NULL,
                `rental_serial` varchar(255) NOT NULL,
                `rental_duration_check` varchar(255) DEFAULT NULL,
                `rental_status` int(11) DEFAULT NULL,
                `serviceid` int(11) DEFAULT NULL
            ) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci;");
        }
        



            if (!$CI->db->table_exists('tblservices_module')) {
                // Create the tblservices_module table if it doesn't exist
                $CI->db->query("CREATE TABLE `tblservices_module` (
                    `serviceid` bigint(20) NOT NULL,
                    `service_code` varchar(50) NOT NULL,
                    `service_type_code` varchar(50) NOT NULL,
                    `name` varchar(50) NOT NULL,
                    `price` decimal(11,2) DEFAULT NULL,
                    `penalty_rental_price` decimal(11,2) DEFAULT NULL,
                    `rental_serial` varchar(100) DEFAULT NULL,
                    `warranty_days_remaining` int(11) DEFAULT NULL,
                    `warranty_end_date` date DEFAULT NULL,
                    `warranty_status` int(11) DEFAULT NULL,
                    `date_sold` date DEFAULT NULL,
                    `serial` varchar(100) DEFAULT NULL,
                    `rental_status` varchar(10) DEFAULT NULL COMMENT '''Hired'',''Not-Hired''',
                    `rental_duration_check` enum('hours','days','weeks','months','years') NOT NULL,
                    `description` text DEFAULT NULL,
                    `rental_accessories` text DEFAULT NULL,
                    `status` tinyint(4) NOT NULL DEFAULT 1,
                    `quantity_unit` varchar(100) DEFAULT NULL
                ) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci;");
                } else {
                    // Check and add missing columns if necessary
                    $fields_to_check = [
                        'warranty_days_remaining' => "INT(11) NULL",
                        'warranty_end_date' => "DATE NULL",
                        'warranty_status' => "INT(11) NULL",
                        'date_sold' => "DATE NULL",
                        'serial' => "VARCHAR(100) NULL"
                    ];
                
                    foreach ($fields_to_check as $field_name => $field_definition) {
                        if (!$CI->db->field_exists($field_name, 'tblservices_module')) {
                            $CI->db->query("ALTER TABLE tblservices_module ADD COLUMN `$field_name` $field_definition;");
                        }
                    }
                }

                // File transfer logic
                $files_to_copy = [
                    [
                        'source' => FCPATH . 'modules/bizit_services_msl/uploads/file_uploads/invoice_preview_template.php',
                        'destination' => FCPATH . 'application/views/admin/invoices/invoice_preview_template.php'
                    ],
                    [
                        'source' => FCPATH . 'modules/bizit_services_msl/uploads/file_uploads/invoice_preview_html.php',
                        'destination' => FCPATH . 'application/views/admin/invoices/invoice_preview_html.php'
                    ],
                    [
                        'source' => FCPATH . 'modules/bizit_services_msl/uploads/file_uploads/Invoices_model.php',
                        'destination' => FCPATH . 'application/models/Invoices_model.php'
                    ],
                    
                    [
                        'source' => FCPATH . 'modules/bizit_services_msl/uploads/file_uploads/Invoice_pdf.php',
                        'destination' => FCPATH . 'application/libraries/pdf/Invoice_pdf.php'
                    ],
                    [
                        'source' => FCPATH . 'modules/bizit_services_msl/uploads/file_uploads/Invoices.php',
                        'destination' => FCPATH . 'application/controllers/admin/Invoices.php'
                    ],
                ];

                foreach ($files_to_copy as $file) {
                    $source_file = $file['source'];
                    $destination_file = $file['destination'];

                    if (file_exists($source_file)) {
                        if (file_exists($destination_file)) {
                            if (!unlink($destination_file)) {
                                log_message('error', "Failed to delete existing file at {$destination_file}");
                                continue; // Skip copy if unable to delete
                            }
                        }
                        if (copy($source_file, $destination_file)) {
                            log_message('info', "File successfully copied to {$destination_file}");
                        } else {
                            log_message('error', "Failed to copy file to {$destination_file}");
                        }
                    } else {
                        log_message('error', "Source file {$source_file} does not exist.");
                    }
                }


                // Create `tblchecklist1`
                if (!$CI->db->table_exists('tblchecklist1')) {
                    $CI->db->query("CREATE TABLE tblchecklist1 (
                        `id` bigint(20) NOT NULL AUTO_INCREMENT PRIMARY KEY,
                        `service_request_id` bigint(20) NOT NULL,
                        `item` varchar(255) NOT NULL,
                        `status` varchar(5) NOT NULL
                    ) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci;");
                }


                $query = $CI->db->query("SELECT COLUMN_NAME, CHARACTER_MAXIMUM_LENGTH 
                         FROM INFORMATION_SCHEMA.COLUMNS 
                         WHERE TABLE_NAME = 'tblstaff_permissions' 
                         AND COLUMN_NAME = 'feature' 
                         AND TABLE_SCHEMA = DATABASE();");

                $result = $query->row();

                if ($result && $result->CHARACTER_MAXIMUM_LENGTH < 255) {
                    // Modify only if `feature` is shorter than 255 characters
                    $CI->db->query("ALTER TABLE tblstaff_permissions MODIFY COLUMN `feature` VARCHAR(255) NOT NULL;");
                } else {
                    log_message('error', 'Column `feature` already has length 255 or does not exist.');
                }


                // Create `tblcollection1`
                if (!$CI->db->table_exists('tblcollection1')) {
                    $CI->db->query("CREATE TABLE tblcollection1 (
                        `id` bigint(20) NOT NULL AUTO_INCREMENT PRIMARY KEY,
                        `service_request_id` bigint(20) NOT NULL,
                        `released_by` varchar(255) NOT NULL,
                        `released_date` date NOT NULL,
                        `released_id_number` varchar(50) NOT NULL,
                        `collected_by` varchar(255) NOT NULL,
                        `collected_date` date NOT NULL,
                        `collected_id_number` varchar(50) NOT NULL
                    ) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci;");

                }

                // Create `tblinspection_requests`
                if (!$CI->db->table_exists('tblinspection_requests')) {
                    $CI->db->query("CREATE TABLE tblinspection_requests (
                        `id` int(11) NOT NULL AUTO_INCREMENT PRIMARY KEY,
                        `service_request_id` bigint(20) NOT NULL,
                        `inspection_type` varchar(50) NOT NULL,
                        `remarks_condition` text DEFAULT NULL,
                        `inspection_item` varchar(100) NOT NULL
                    ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;");
                }

                // Create `tblservice_request_accessories`
              
                if (!$CI->db->table_exists(db_prefix() . 'service_request_accessories')) {
                    $CI->db->query("CREATE TABLE " . db_prefix() . "service_request_accessories (
                        `id` int(11) NOT NULL AUTO_INCREMENT PRIMARY KEY,
                        `service_request_id` bigint(20) NULL,
                        `accessory_id` int(11) NULL,
                        `price` decimal(10,2) NULL,
                        KEY `service_request_id` (`service_request_id`)
                    ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;");
                }
                
        }

        public function down()
        {
            $CI =& get_instance();

            // Optionally, remove the copied file during rollback
            $destination_file = FCPATH . 'application\views\admin\invoices\invoice_preview_template.php';
            if (file_exists($destination_file)) {
                if (unlink($destination_file)) {
                    log_message('info', "File {$destination_file} successfully deleted during rollback.");
                } else {
                    log_message('error', "Failed to delete file {$destination_file} during rollback.");
                }
            }// Optionally add rollback logic or leave it empty if not needed
        }

}