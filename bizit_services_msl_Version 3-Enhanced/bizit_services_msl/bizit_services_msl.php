<?php

defined('BASEPATH') or exit('No direct script access allowed');

/*
Module Name: Bizit Services MSL
Description: Complete Service, Rental, Compensation & Review Management (V3.2)
Version: 1.1.3
Author: Swivernet
Author URI: https://tazamali.com
*/

define('BIZIT_SERVICES_MSL', 'bizit_services_msl');
define('SERVICES_NOT_TO_DELETE', ['003-0001', '004-0001', '004-0002', '004-0003', '004-0004', '005-0001', '006-0001', '006-0002']);

// ------------------------------------------------------------------------
// HOOKS REGISTER
// ------------------------------------------------------------------------

// 1. Init Hooks
hooks()->add_action('admin_init', 'bizit_services_msl_permissions');
hooks()->add_filter('admin_init', 'bizit_services_msl_module_init_menu');
hooks()->add_action('before_js_scripts_render', 'bizit_services_msl_script');

// 2. V3 Automation Hooks
hooks()->add_action('before_render_invoice_template', 'bizit_inject_tech_engineer_field');
hooks()->add_action('after_invoice_added', 'bizit_handle_invoice_tech_logic');
hooks()->add_action('after_invoice_updated', 'bizit_handle_invoice_tech_logic');
hooks()->add_action('after_cron_run', 'bizit_check_module_reminders');

// 3. V1 Legacy Hooks (Restored)
hooks()->add_filter('bizit_invoices_data', 'bizit_services_msl_invoices_data');
hooks()->add_filter('bizit_estimates_data', 'bizit_services_msl_estimates_data');
hooks()->add_action('after_invoice_view_as_client_link', 'delivery_note_pdf_link');

$CI = &get_instance();
$CI->load->helper(BIZIT_SERVICES_MSL . '/' . BIZIT_SERVICES_MSL);

// ------------------------------------------------------------------------
// FUNCTIONS
// ------------------------------------------------------------------------

function bizit_services_msl_script(){
    $CI = &get_instance();
    $CI->app_scripts->add(BIZIT_SERVICES_MSL.'-js', module_dir_url(BIZIT_SERVICES_MSL, 'assets/js/bizit_services_msl.js'));
}

function bizit_services_msl_permissions(){
    $caps = ['view_own'=>'View (Own)', 'view'=>'View (Global)', 'create'=>'Create', 'edit'=>'Edit', 'delete'=>'Delete'];
    register_staff_capabilities(BIZIT_SERVICES_MSL, $caps, 'Bizit Services');
    register_staff_capabilities(BIZIT_SERVICES_MSL.'_rental_agreement', $caps, 'Rental Agreements');
    register_staff_capabilities(BIZIT_SERVICES_MSL.'_rental_agreement_field_report', $caps, 'Field Reports');
    register_staff_capabilities(BIZIT_SERVICES_MSL.'_compensation_rates', ['view'=>'View','edit'=>'Edit'], 'Staff Compensation');
    register_staff_capabilities(BIZIT_SERVICES_MSL.'_reports_dashboard', ['view'=>'View'], 'Reports Dashboard');
}

function bizit_services_msl_module_init_menu(){
    $CI = &get_instance();
    if (staff_can('view', BIZIT_SERVICES_MSL) || staff_can('view_own', BIZIT_SERVICES_MSL)) {
        $CI->app_menu->add_sidebar_menu_item('services', ['name'=>'Services', 'collapse'=>true, 'position'=>11, 'icon'=>'fa fa-wrench']);
        $CI->app_menu->add_sidebar_children_item('services', ['slug'=>'services-requests', 'name'=>'Service Requests', 'href'=>admin_url('services/requests'), 'position'=>1]);
        
        if(staff_can('view', BIZIT_SERVICES_MSL.'_rental_agreement') || staff_can('view_own', BIZIT_SERVICES_MSL.'_rental_agreement')) 
            $CI->app_menu->add_sidebar_children_item('services', ['slug'=>'services-hire', 'name'=>'Rental Agreements', 'href'=>admin_url('services/rental_agreements'), 'position'=>2]);
            
        if(staff_can('view', BIZIT_SERVICES_MSL.'_rental_agreement_field_report') || staff_can('view_own', BIZIT_SERVICES_MSL.'_rental_agreement_field_report')) 
            $CI->app_menu->add_sidebar_children_item('services', ['slug'=>'services-field-reports', 'name'=>'Field Reports', 'href'=>admin_url('services/field_reports'), 'position'=>3]);
            
        if(staff_can('view', BIZIT_SERVICES_MSL.'_compensation_rates')) 
            $CI->app_menu->add_sidebar_children_item('services', ['slug'=>'services-rates', 'name'=>'Staff Rates', 'href'=>admin_url('services/staff_compensation_rates'), 'position'=>4]);
            
        if(staff_can('view', BIZIT_SERVICES_MSL.'_reports_dashboard')) 
            $CI->app_menu->add_sidebar_children_item('services', ['slug'=>'services-dashboard', 'name'=>'Performance Dashboard', 'href'=>admin_url('services/reports_dashboard'), 'position'=>5]);
    }
}

// V3 Automation
function bizit_inject_tech_engineer_field($invoice){
    $CI = &get_instance(); $CI->load->model('staff_model');
    $staff = $CI->staff_model->get('', ['active'=>1]);
    $val = isset($invoice->technical_engineer_id) ? $invoice->technical_engineer_id : '';
    $opts = '<option value=""></option>';
    foreach($staff as $s) $opts .= '<option value="'.$s['staffid'].'" '.($val==$s['staffid']?'selected':'').'>'.$s['firstname'].' '.$s['lastname'].'</option>';
    echo '<script>$(function(){ var t=$("select[name=\'sale_agent\']"); if(t.length){ t.closest(".form-group").after(\'<div class="form-group"><label>Technical Support Engineer</label><select name="technical_engineer_id" class="selectpicker" data-width="100%" data-live-search="true">'.$opts.'</select></div>\'); $("select[name=\'technical_engineer_id\']").selectpicker("refresh"); } });</script>';
}

function bizit_handle_invoice_tech_logic($id){
    $CI = &get_instance();
    $tech = $CI->input->post('technical_engineer_id');
    if($tech){
        $CI->db->where('id',$id)->update('tblinvoices',['technical_engineer_id'=>$tech]);
        $inv = $CI->db->where('id',$id)->get('tblinvoices')->row();
        if(empty($inv->support_ticket_id)){
            $CI->load->model('tickets_model');
            $data = ['subject'=>'Support - Inv #'.$id, 'department'=>1, 'priority'=>2, 'userid'=>$inv->clientid, 'contactid'=>get_primary_contact_user_id($inv->clientid), 'assigned'=>$tech, 'message'=>'Auto-generated support ticket for Invoice #'.$id, 'date'=>date('Y-m-d H:i:s')];
            $CI->db->insert('tbltickets', $data);
            $CI->db->where('id',$id)->update('tblinvoices', ['support_ticket_id'=>$CI->db->insert_id()]);
        }
        if(empty($inv->service_review_token)) $CI->db->where('id',$id)->update('tblinvoices', ['service_review_token'=>md5(uniqid(rand(),true))]);
    }
}

function bizit_check_module_reminders(){
    if(function_exists('bizit_check_calibration_reminders')) bizit_check_calibration_reminders();
    if(function_exists('bizit_check_field_report_reminders')) bizit_check_field_report_reminders();
}

// V1 Legacy Restorations
function bizit_services_msl_invoices_data($invoice_data){
    $to_remove = ['service_select', 'invoice_services'];
    foreach ($to_remove as $val) { unset($invoice_data[$val]); }
    return $invoice_data;
}

function bizit_services_msl_estimates_data($estimate_data){
    $to_remove = ['service_select', 'invoice_services'];
    foreach ($to_remove as $val) { unset($estimate_data[$val]); }
    return $estimate_data;
}

function delivery_note_pdf_link($invoice){
    echo '<li><a href="' . admin_url('services/delivery_note/' . $invoice->id) . '" target="_blank">' . _l('delivery_note') . '</a></li>';
    echo '<li><a href="' . admin_url('services/inventory_checklist/' . $invoice->id) . '" target="_blank">' . _l('inventory_checklist') . '</a></li>';
}