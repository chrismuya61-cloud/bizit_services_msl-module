<?php

defined('BASEPATH') or exit('No direct script access allowed');

/*
Module Name: Bizit Services MSL
Description: Module to run Bizit Services for MSL.
Version: 1.0.9
Requires at least: 2.3.*
Author: Swivernet
Author URI: https://tazamali.com
 */

define('BIZIT_SERVICES_MSL', 'bizit_services_msl');
define('SERVICES_NOT_TO_DELETE', ['003-0001', '004-0001', '004-0002', '004-0003', '004-0004', '005-0001', '006-0001', '006-0002']);

hooks()->add_action('admin_init', 'bizit_services_msl_permissions');
hooks()->add_filter('admin_init', 'bizit_services_msl_module_init_menu');
hooks()->add_action('before_js_scripts_render', 'bizit_services_msl_script', 10);
hooks()->add_filter('bizit_invoices_data', 'bizit_services_msl_invoices_data');
hooks()->add_filter('bizit_estimates_data', 'bizit_services_msl_estimates_data');

// Add moretabs to invoice "More" menu
hooks()->add_action('after_invoice_view_as_client_link', 'delivery_note_pdf_link');

/**
 * Load the module helper
 */
$CI = &get_instance();
$CI->load->helper(BIZIT_SERVICES_MSL . '/' . BIZIT_SERVICES_MSL);

$CI->app_css->add(BIZIT_SERVICES_MSL . '-css', module_dir_url(BIZIT_SERVICES_MSL, 'assets/css/' . BIZIT_SERVICES_MSL . '.css'));

/**
 * Load the models
 */
$CI->load->model(BIZIT_SERVICES_MSL . '/services_model');

/*
 * Load the libraries
 */
$CI->load->library(BIZIT_SERVICES_MSL . '/pdf');
$CI->load->library(BIZIT_SERVICES_MSL . '/Dpdf');

/**
 * JS Scripts
 */
function bizit_services_msl_script()
{
    $CI = &get_instance();
    $CI->app_scripts->add(BIZIT_SERVICES_MSL . '-js', module_dir_url(BIZIT_SERVICES_MSL, 'assets/js/' . BIZIT_SERVICES_MSL . '.js') . '?v=' . $CI->app_scripts->core_version(), 'admin', ['app-js']);
}

/**
 * Register activation module hook
 */
register_activation_hook(BIZIT_SERVICES_MSL, 'bizit_services_msl_module_activation_hook');

function bizit_services_msl_module_activation_hook()
{
    $CI = &get_instance();
    require_once __DIR__ . '/install.php';
}

/**
 * Register uninstall module hook
 */
register_uninstall_hook(BIZIT_SERVICES_MSL, 'bizit_services_msl_module_uninstall_hook');

function bizit_services_msl_module_uninstall_hook()
{
    $CI = &get_instance();
    require_once __DIR__ . '/uninstall.php';
}

/**
 * Register deactivation module hook
 */
register_deactivation_hook(BIZIT_SERVICES_MSL, 'bizit_services_msl_module_deactivation_hook');

function bizit_services_msl_module_deactivation_hook()
{
    $CI = &get_instance();
    require_once __DIR__ . '/deactivate.php';
}

/**
 * Register language files
 */
register_language_files(BIZIT_SERVICES_MSL, [BIZIT_SERVICES_MSL]);

/*
 * Bizit Services Permissions
 */
function bizit_services_msl_permissions()
{
    $capabilities = [];

    $capabilities['capabilities'] = [
        'view'   => _l('permission_view') . '(' . _l('permission_global') . ')',
        'create' => _l('permission_create'),
        'edit'   => _l('permission_edit'),
        'delete' => _l('permission_delete'),
    ];

    register_staff_capabilities(BIZIT_SERVICES_MSL, $capabilities, 'Bizit Services');
    register_staff_capabilities(BIZIT_SERVICES_MSL . '_rental_agreement_field_report', $capabilities, 'Bizit Services Rental Agreement Field Report');
    register_staff_capabilities(BIZIT_SERVICES_MSL . '_rental_agreement', $capabilities, 'Bizit Services Rental Agreement');

    // NEW PERMISSIONS FOR COMPENSATION AND REPORTS
    register_staff_capabilities(BIZIT_SERVICES_MSL . '_compensation_rates', ['view' => _l('permission_view'), 'edit' => _l('permission_edit')], 'Staff Compensation Rates');
    register_staff_capabilities(BIZIT_SERVICES_MSL . '_reports_dashboard', ['view' => _l('permission_view')], 'Bizit Services Reports Dashboard');
}

/*
 * Init Bizit Services module menu items in menu in admin_init hook
 */
function bizit_services_msl_module_init_menu()
{
    $CI = &get_instance();
    if (staff_can('view', BIZIT_SERVICES_MSL)) {
        
        if (is_admin()) {
            $CI->app_menu->add_setup_menu_item('services', [
                'slug'     => 'services',
                'name'     => _l('bizit_services_msl'),
                'href'     => admin_url('services'),
                'position' => 21,
            ]);
        }

        // Sidebar Main Item: Services
        $CI->app_menu->add_sidebar_menu_item('services', [
            'collapse' => true,
            'name'     => _l('als_services'),
            'position' => 11,
            'icon'     => 'fa fa-wrench',
            'badge'    => [],
        ]);

        // Submenu: Service Requests
        $CI->app_menu->add_sidebar_children_item('services', [
            'slug'     => 'services-requests',
            'name'     => _l('als_services_requests'),
            'href'     => admin_url('services/requests'),
            'position' => 1,
        ]);

        // Submenu: Rental Agreements
        if (staff_can('view', BIZIT_SERVICES_MSL . '_rental_agreement')) {
            $CI->app_menu->add_sidebar_children_item('services', [
                'slug'     => 'services-hire',
                'name'     => _l('als_services_for_hire'),
                'href'     => admin_url('services/rental_agreements'),
                'position' => 2,
            ]);
        }

        // NEW Submenu: Staff Compensation Rates
        if (staff_can('view', BIZIT_SERVICES_MSL . '_compensation_rates')) {
            $CI->app_menu->add_sidebar_children_item('services', [
                'slug'     => 'services-rates',
                'name'     => 'Staff Compensation Rates',
                'href'     => admin_url('services/staff_compensation_rates'),
                'position' => 3,
            ]);
        }

        // NEW Submenu: Reports Dashboard
        if (staff_can('view', BIZIT_SERVICES_MSL . '_reports_dashboard')) {
            $CI->app_menu->add_sidebar_children_item('services', [
                'slug'     => 'services-reports',
                'name'     => 'Reports Dashboard',
                'href'     => admin_url('services/reports_dashboard'),
                'position' => 4,
            ]);
        }
    }
}

/**
 * bizit_services_msl_invoices_data
 */
function bizit_services_msl_invoices_data($invoice_data)
{
    $to_remove = ['service_select', 'invoice_services'];
    foreach ($to_remove as $val) {
        unset($invoice_data[$val]);
    }
    return $invoice_data;
}

/**
 * bizit_services_msl_estimates_data
 */
function bizit_services_msl_estimates_data($estimate_data)
{
    $to_remove = ['service_select', 'invoice_services'];
    foreach ($to_remove as $val) {
        unset($estimate_data[$val]);
    }
    return $estimate_data;
}

/**
 * Add Delivery Note and Inventory Checklist PDF links to invoice view
 */
function delivery_note_pdf_link($invoice)
{
    echo '<li>
        <a href="' . admin_url('services/delivery_note/' . $invoice->id) . '" target="_blank">'
            . _l('Delivery Note') .
        '</a>
    </li>';

    echo '<li>
        <a href="' . admin_url('services/inventory_checklist/' . $invoice->id) . '" target="_blank">'
            . _l('Inventory Checklist') .
        '</a>
    </li>';

    return;
}