<?php
defined('BASEPATH') or exit('No direct script access allowed');

/*
| -------------------------------------------------------------------------
| BIZIT SERVICES MSL MODULE ROUTES
| -------------------------------------------------------------------------
*/

// 1. Explicit Menu Routes
$route['admin/services']                                 = 'bizit_services_msl/admin/services/index';
$route['admin/services/requests']                        = 'bizit_services_msl/admin/services/requests';
$route['admin/services/staff_compensation_rates']        = 'bizit_services_msl/admin/services/staff_compensation_rates'; 
$route['admin/services/reports_dashboard']               = 'bizit_services_msl/admin/services/reports_dashboard';
$route['admin/services/category_manage']                 = 'bizit_services_msl/admin/services/category_manage';

// 2. Rental Routes
$route['admin/services/rental_agreements']               = 'bizit_services_msl/admin/services/rental_agreements';
$route['admin/services/new_rental_agreement/(:any)/(:any)'] = 'bizit_services_msl/admin/services/new_rental_agreement/$1/$2';
$route['admin/services/view_rental_agreement/(:any)']    = 'bizit_services_msl/admin/services/view_rental_agreement/$1';

// 3. Wildcard Routes (Level 1 - 4)
$route['admin/services/(:any)']                          = 'bizit_services_msl/admin/services/$1';
$route['admin/services/(:any)/(:any)']                   = 'bizit_services_msl/admin/services/$1/$2';
$route['admin/services/(:any)/(:any)/(:any)']            = 'bizit_services_msl/admin/services/$1/$2/$3';
$route['admin/services/(:any)/(:any)/(:any)/(:any)']     = 'bizit_services_msl/admin/services/$1/$2/$3/$4';

// 4. Client Routes
$route['services/report/(:any)/(:any)']                  = 'bizit_services_msl/services/report/$1/$2';
$route['services/field_report/(:any)/(:any)']            = 'bizit_services_msl/services/field_report/$1/$2';
$route['services/certificate/(:any)/(:any)']             = 'bizit_services_msl/services/certificate/$1/$2';