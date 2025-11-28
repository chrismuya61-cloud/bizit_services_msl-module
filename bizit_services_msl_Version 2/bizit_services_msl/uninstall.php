<?php

defined('BASEPATH') or exit('No direct script access allowed');

/**
 * Modify system files
 * @param  [type]  $fname    [description]
 * @param  [type]  $searchF  [description]
 * @param  [type]  $replaceW [description]
 * @return [bool]            [description]
 */
function bizit_modFile($fname, $searchF, $replaceW)
{
    $fhandle = fopen($fname, "r");
    $content = fread($fhandle, filesize($fname));
    if (strstr($content, $searchF)) {
        $content = str_replace($searchF, $replaceW, $content);
        $fhandle = fopen($fname, "w");
        fwrite($fhandle, $content);
    }
    fclose($fhandle);
    return true;
}


/**
 * ################  UPDATE CORE FILES #####################
 */

$fname1 = APPPATH . "views/admin/invoices/invoice.php";
$searchF1 = "<?php \$this->load->view('admin/invoices/bizit_invoices/invoice_template'); ?>";
$replaceW1 = "<?php \$this->load->view('admin/invoices/invoice_template'); ?>";
bizit_modFile($fname1, $searchF1, $replaceW1);

$fname1 = APPPATH . "controllers/admin/Invoices.php";
$searchF = "\$invoice_data = hooks()->apply_filters('bizit_invoices_data', \$this->input->post());";
$replaceW11 = "\$invoice_data = \$this->input->post();";
bizit_modFile($fname1, $searchF1, $replaceW1);

$fname1 = APPPATH . "views/admin/estimates/estimate_template.php";
$searchF1 = "<?php \$this->load->view('admin/estimates/bizit_estimates/_add_edit_items'); ?>";
$replaceW1 = "<?php \$this->load->view('admin/estimates/_add_edit_items'); ?>";
bizit_modFile($fname1, $searchF1, $replaceW1);

$fname1 = APPPATH . "controllers/admin/Estimates.php";
$searchF1 = "\$estimate_data = hooks()->apply_filters('bizit_estimates_data', \$this->input->post());";
$replaceW1 = "\$estimate_data = \$this->input->post();";
bizit_modFile($fname1, $searchF1, $replaceW1);
