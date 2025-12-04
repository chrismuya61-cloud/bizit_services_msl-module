<?php
defined('BASEPATH') or exit('No direct script access allowed');
/*
Module Name: Bizit Services MSL
Description: Modular Service, Rental & Performance Management System (V3.3 Hybrid)
Version: 1.1.5
Author: Swivernet
*/
define('BIZIT_SERVICES_MSL', 'bizit_services_msl');

// ==========================================================
//  1. MODULE HOOKS
// ==========================================================

// Initialization & Permissions
hooks()->add_action('admin_init', 'bizit_services_msl_permissions');
hooks()->add_filter('admin_init', 'bizit_services_msl_module_init_menu');

// Assets (Scripts & Styles)
hooks()->add_action('before_js_scripts_render', 'bizit_services_msl_script');
hooks()->add_action('app_admin_head', 'bizit_services_add_head_components'); // Modern UI Injection
hooks()->add_action('app_admin_head', 'bizit_services_add_head_components');
function bizit_services_add_head_components(){
    echo '<link href="' . module_dir_url('bizit_services_msl', 'assets/css/modern_bizit.css') . '" rel="stylesheet">';
}

// V3 Automations (Invoices & Tech Logic)
hooks()->add_action('before_render_invoice_template', 'bizit_inject_tech_engineer_field');
hooks()->add_action('after_invoice_added', 'bizit_handle_invoice_tech_logic');
hooks()->add_action('after_invoice_updated', 'bizit_handle_invoice_tech_logic');
hooks()->add_action('after_cron_run', 'bizit_check_module_reminders');

// V1 Legacy Restorations (Data Filters & PDF Links)
hooks()->add_filter('bizit_invoices_data', 'bizit_services_msl_invoices_data');
hooks()->add_filter('bizit_estimates_data', 'bizit_services_msl_estimates_data');
hooks()->add_action('after_invoice_view_as_client_link', 'delivery_note_pdf_link');

// Load Helpers
$CI = &get_instance();
$CI->load->helper(BIZIT_SERVICES_MSL . '/' . BIZIT_SERVICES_MSL);


// ==========================================================
//  2. ASSET MANAGEMENT
// ==========================================================

function bizit_services_msl_script(){
    $CI = &get_instance();
    $CI->app_scripts->add(BIZIT_SERVICES_MSL.'-js', module_dir_url(BIZIT_SERVICES_MSL, 'assets/js/bizit_services_msl.js'));
}

function bizit_services_add_head_components(){
    echo '<link href="' . module_dir_url(BIZIT_SERVICES_MSL, 'assets/css/modern_bizit.css') . '" rel="stylesheet">';
}


// ==========================================================
//  3. PERMISSIONS (GRANULAR & APPROVALS)
// ==========================================================

function bizit_services_msl_permissions(){
    // Standard Granular Capabilities (View Own, Global, Create, Edit, Delete)
    $capabilities = [];
    $capabilities['capabilities'] = [
        'view_own' => _l('permission_view') . ' (' . _l('permission_own') . ')',
        'view'     => _l('permission_view') . ' (' . _l('permission_global') . ')',
        'create'   => _l('permission_create'),
        'edit'     => _l('permission_edit'),
        'delete'   => _l('permission_delete'),
    ];

    // 1. Core Services (Inventory)
    register_staff_capabilities(BIZIT_SERVICES_MSL, $capabilities, 'Bizit Services');
    
    // 2. Rental Agreements
    register_staff_capabilities(BIZIT_SERVICES_MSL.'_rental_agreement', $capabilities, 'Rental Agreements');
    
    // 3. Field Reports
    register_staff_capabilities(BIZIT_SERVICES_MSL.'_rental_agreement_field_report', $capabilities, 'Field Reports');
    
    // 4. Sales Orders (New Feature)
    register_staff_capabilities('bizit_services_msl_orders', $capabilities, 'Sales Orders');

    // 5. Service Approvals (Special Capability)
    $approval_cap = ['capabilities' => ['approve' => 'Can Approve Requests/Rentals']];
    register_staff_capabilities('bizit_services_msl_approvals', $approval_cap, 'Service Approvals');

    // 6. Compensation & Dashboard (View Only)
    $view_edit_caps = [
        'capabilities' => [
            'view' => _l('permission_view'),
            'edit' => _l('permission_edit')
        ]
    ];
    register_staff_capabilities(BIZIT_SERVICES_MSL.'_compensation_rates', $view_edit_caps, 'Staff Compensation');
    
    $view_only_caps = [
        'capabilities' => [
            'view' => _l('permission_view')
        ]
    ];
    register_staff_capabilities(BIZIT_SERVICES_MSL.'_reports_dashboard', $view_only_caps, 'Reports Dashboard');
}


// ==========================================================
//  4. MENU GENERATION
// ==========================================================

function bizit_services_msl_module_init_menu(){
    $CI = &get_instance();
    
    // Check Main Permission (Global View or Own View)
    if (staff_can('view', BIZIT_SERVICES_MSL) || staff_can('view_own', BIZIT_SERVICES_MSL)) {
        $CI->app_menu->add_sidebar_menu_item('services', ['name'=>'Services', 'collapse'=>true, 'position'=>11, 'icon'=>'fa fa-wrench']);
        
        // 1. Requests
        $CI->app_menu->add_sidebar_children_item('services', ['slug'=>'services-requests', 'name'=>'Service Requests', 'href'=>admin_url('services/requests'), 'position'=>1]);
        
        // 2. Sales Orders (New Feature)
        if(staff_can('view', 'bizit_services_msl_orders') || staff_can('view_own', 'bizit_services_msl_orders')) {
            $CI->app_menu->add_sidebar_children_item('services', ['slug'=>'services-orders', 'name'=>'Sales Orders', 'href'=>admin_url('services/sales_orders'), 'position'=>2]);
        }

        // 3. Rental Agreements
        if(staff_can('view', BIZIT_SERVICES_MSL.'_rental_agreement') || staff_can('view_own', BIZIT_SERVICES_MSL.'_rental_agreement')) {
            $CI->app_menu->add_sidebar_children_item('services', ['slug'=>'services-hire', 'name'=>'Rental Agreements', 'href'=>admin_url('services/rental_agreements'), 'position'=>3]);
        }
            
        // 4. Sales List
        $CI->app_menu->add_sidebar_children_item('services', ['slug'=>'services-sales-list', 'name'=>'Sales List', 'href'=>admin_url('services/sales_list'), 'position'=>4]);

        // 5. Field Reports
        if(staff_can('view', BIZIT_SERVICES_MSL.'_rental_agreement_field_report') || staff_can('view_own', BIZIT_SERVICES_MSL.'_rental_agreement_field_report')) {
            $CI->app_menu->add_sidebar_children_item('services', ['slug'=>'services-field-reports', 'name'=>'Field Reports', 'href'=>admin_url('services/field_reports'), 'position'=>5]);
        }
        
        // 6. Compensation
        if(staff_can('view', BIZIT_SERVICES_MSL.'_compensation_rates')) {
            $CI->app_menu->add_sidebar_children_item('services', ['slug'=>'services-rates', 'name'=>'Staff Rates', 'href'=>admin_url('services/staff_compensation_rates'), 'position'=>6]);
        }
        
        // 7. Dashboard
        if(staff_can('view', BIZIT_SERVICES_MSL.'_reports_dashboard')) {
            $CI->app_menu->add_sidebar_children_item('services', ['slug'=>'services-dashboard', 'name'=>'Performance Dashboard', 'href'=>admin_url('services/reports_dashboard'), 'position'=>7]);
        }
    }
}


// ==========================================================
//  5. V3 AUTOMATION LOGIC (Technical Engineer)
// ==========================================================

function bizit_inject_tech_engineer_field($invoice){
    $CI = &get_instance(); 
    $CI->load->model('staff_model');
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


// ==========================================================
//  6. LEGACY HELPERS (RESTORED FROM V1)
// ==========================================================

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
