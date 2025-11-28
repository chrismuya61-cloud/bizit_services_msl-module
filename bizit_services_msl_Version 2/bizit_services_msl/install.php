<?php

defined('BASEPATH') or exit('No direct script access allowed');

//check if the module has been registered, if not run all migrations
$CI->db->update(db_prefix() . 'modules', ['installed_version' => '0.0.0'], ['module_name' => BIZIT_SERVICES_MSL]);
if ($CI->db->affected_rows() == 0) {
	$CI->db->insert(db_prefix() . 'modules', ['module_name' => BIZIT_SERVICES_MSL, 'installed_version' => '0.0.0']);
}

$CI->app_modules->upgrade_database(BIZIT_SERVICES_MSL);

// Handle any options or additional logic like file modifications, etc.
add_option('service_request_prefix', 'REQ-');
add_option('service_rental_agreement_prefix', 'ARG-');

/**
 * Modify system files
 * @param  mixed $fname
 * @param  mixed $searchF
 * @param  mixed $replaceW
 * @param  mixed $check
 * @return void
 */
function bizit_modFile($fname, $searchF, $replaceW, $check = false)
{
	$fhandle = fopen($fname, "r");
	$content = fread($fhandle, filesize($fname));
	if (strstr($content, $searchF)) {
		if ($check) {
			return true;
			fclose($fhandle);
		}
		$content = str_replace($searchF, $replaceW, $content);
		$fhandle = fopen($fname, "w");
		fwrite($fhandle, $content);
	}
	fclose($fhandle);
}


/**
 * ################  UPDATE CORE FILES #####################
 */
//Invoices
$fname1 = APPPATH . "views/admin/invoices/invoice.php";
$searchF1 = "<?php \$this->load->view('admin/invoices/invoice_template'); ?>";
$replaceW1 = "<?php \$this->load->view('admin/invoices/bizit_invoices/invoice_template'); ?>";
bizit_modFile($fname1, $searchF1, $replaceW1);

$fname1 = APPPATH . "controllers/admin/Invoices.php";
$searchF1 = "\$invoice_data = \$this->input->post();";
$replaceW1 = "\$invoice_data = hooks()->apply_filters('bizit_invoices_data', \$this->input->post());";
bizit_modFile($fname1, $searchF1, $replaceW1);

//move directory
$destination_path = APPPATH . 'views/admin/invoices/bizit_invoices/';
$source_path = module_dir_path(BIZIT_SERVICES_MSL, 'views/admin/bizit_invoices/');
copyfolder($source_path, $destination_path);

//Estimates
$fname1 = APPPATH . "views/admin/estimates/estimate_template.php";
$searchF1 = "<?php \$this->load->view('admin/estimates/_add_edit_items'); ?>";
$replaceW1 = "<?php \$this->load->view('admin/estimates/bizit_estimates/_add_edit_items'); ?>";
bizit_modFile($fname1, $searchF1, $replaceW1);

$fname1 = APPPATH . "controllers/admin/Estimates.php";
$searchF1 = "\$estimate_data = \$this->input->post();";
$replaceW1 = "\$estimate_data = hooks()->apply_filters('bizit_estimates_data', \$this->input->post());";
bizit_modFile($fname1, $searchF1, $replaceW1);

//move directory
$destination_path = APPPATH . 'views/admin/estimates/bizit_estimates/';
$source_path = module_dir_path(BIZIT_SERVICES_MSL, 'views/admin/bizit_estimates/');
copyfolder($source_path, $destination_path);


##-----------------ROUTES-----------------##

/**
 * my_routes.php file edit
 * @var [type]
 */
$my_routes_fname = APPPATH . "config/my_routes.php";


//create file if not existing
if (!is_file($my_routes_fname)) {
	$contents = "<?php\n\ndefined('BASEPATH') or exit('No direct script access allowed');";
	file_put_contents($my_routes_fname, $contents);
}

//Update file
if (is_file($my_routes_fname)) {
	//Initial: search for urls
	$searchUrls = "//Bizit Services URL";
	$alreadyExists = bizit_modFile($my_routes_fname, $searchUrls, "", true);
	if (!$alreadyExists) {
		$my_routes_searchFor = "defined('BASEPATH') or exit('No direct script access allowed');";
		$my_routes_replaceWith = "defined('BASEPATH') or exit('No direct script access allowed');\n\n//Bizit Services URL\n\$route['admin/services']  = 'bizit_services_msl/admin/services';\n\$route['admin/services/(:any)']  = 'bizit_services_msl/admin/services/\$1';\n\$route['admin/services/(:any)/(:any)']  = 'bizit_services_msl/admin/services/\$1/\$2';\n\$route['admin/services/(:any)/(:any)/(:any)']  = 'bizit_services_msl/admin/services/\$1/\$2/\$3';\n\$route['admin/services/(:any)/(:any)/(:any)/(:any)']  = 'bizit_services_msl/admin/services/\$1/\$2/\$3/\$4';";

		bizit_modFile($my_routes_fname, $my_routes_searchFor, $my_routes_replaceWith);
	}
}

