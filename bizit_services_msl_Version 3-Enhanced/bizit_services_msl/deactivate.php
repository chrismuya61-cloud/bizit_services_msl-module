<?php

defined('BASEPATH') or exit('No direct script access allowed');

/**
 * Deactivation Script for Bizit Services MSL (V3.2)
 * * CRITICAL: This script cleans up the "Version 1" core file modifications.
 * Version 3 uses Hooks and does not need to modify core files.
 */

// Helper to revert file changes
function bizit_revert_core_file($fname, $search, $replace)
{
    if (file_exists($fname)) {
        $content = file_get_contents($fname);
        if (strpos($content, $search) !== false) {
            $content = str_replace($search, $replace, $content);
            file_put_contents($fname, $content);
        }
    }
}

// 1. Revert Invoice View (Restores standard template loader)
$invoice_view = APPPATH . "views/admin/invoices/invoice.php";
$v1_code = "<?php \$this->load->view('admin/invoices/bizit_invoices/invoice_template'); ?>";
$core_code = "<?php \$this->load->view('admin/invoices/invoice_template'); ?>";
bizit_revert_core_file($invoice_view, $v1_code, $core_code);

// 2. Revert Invoices Controller (Restores standard data loading)
$invoice_controller = APPPATH . "controllers/admin/Invoices.php";
$v1_hook = "\$invoice_data = hooks()->apply_filters('bizit_invoices_data', \$this->input->post());";
$core_code_ctrl = "\$invoice_data = \$this->input->post();";
bizit_revert_core_file($invoice_controller, $v1_hook, $core_code_ctrl);

// 3. Revert Estimate Template
$estimate_view = APPPATH . "views/admin/estimates/estimate_template.php";
$v1_est = "<?php \$this->load->view('admin/estimates/bizit_estimates/_add_edit_items'); ?>";
$core_est = "<?php \$this->load->view('admin/estimates/_add_edit_items'); ?>";
bizit_revert_core_file($estimate_view, $v1_est, $core_est);

// 4. Clean up Routes (If V1 modified my_routes.php)
$routes_file = APPPATH . "config/my_routes.php";
if (file_exists($routes_file)) {
    $content = file_get_contents($routes_file);
    // Simple check to remove the block if it exists
    $pattern = '/\/\/Bizit Services URL.*admin\/services\/category_manage\';/s';
    $content = preg_replace($pattern, '', $content);
    file_put_contents($routes_file, $content);
}