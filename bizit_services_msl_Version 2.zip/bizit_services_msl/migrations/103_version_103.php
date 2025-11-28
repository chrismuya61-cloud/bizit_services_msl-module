<?php

defined('BASEPATH') or exit('No direct script access allowed');

class Migration_Version_103 extends App_module_migration
{
    public function __construct()
    {
        parent::__construct();
    }

    public function up()
    {
        $CI = &get_instance();
        $my_routes_fname = APPPATH . "config/my_routes.php";
        $searchUrls = "\$route['service/(:any)/(:any)/(:any)']  = 'bizit_services_msl/services/\$1/\$2/\$3';";
        $alreadyExists = self::bizit_modFile($my_routes_fname, $searchUrls, "", true);
        if (!$alreadyExists) {
            $my_routes_searchFor = "\$route['admin/services/(:any)/(:any)/(:any)/(:any)']  = 'bizit_services_msl/admin/services/\$1/\$2/\$3/\$4';";
            $my_routes_replaceWith = "\$route['admin/services/(:any)/(:any)/(:any)/(:any)']  = 'bizit_services_msl/admin/services/\$1/\$2/\$3/\$4';\n\$route['service/(:any)/(:any)/(:any)']  = 'bizit_services_msl/services/\$1/\$2/\$3';";

            self::bizit_modFile($my_routes_fname, $my_routes_searchFor, $my_routes_replaceWith);
        }                
    }

    public function down()
    {
        $CI =& get_instance();

    }

/**
 * Modify system files
 * @param  mixed $fname
 * @param  mixed $searchF
 * @param  mixed $replaceW
 * @param  mixed $check
 * @return void
 */
private static function bizit_modFile($fname, $searchF, $replaceW, $check = false)
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

}

