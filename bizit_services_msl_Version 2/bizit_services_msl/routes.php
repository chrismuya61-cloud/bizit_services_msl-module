<?php
defined('BASEPATH') or exit('No direct script access allowed');

/*
| -------------------------------------------------------------------------
| BIZIT SERVICES MSL MODULE ROUTES
| -------------------------------------------------------------------------
*/

// --- 1. Explicit Routes (Fixes 404s for Menu Items) ---
$route['admin/services']                                 = 'bizit_services_msl/admin/services/index';
$route['admin/services/requests']                        = 'bizit_services_msl/admin/services/requests';
$route['admin/services/staff_compensation_rates']        = 'bizit_services_msl/admin/services/staff_compensation_rates'; 
$route['admin/services/reports_dashboard']               = 'bizit_services_msl/admin/services/reports_dashboard';
$route['admin/services/field_reports']                   = 'bizit_services_msl/admin/services/field_reports'; // New Route
$route['admin/services/category_manage']                 = 'bizit_services_msl/admin/services/category_manage';

// --- 2. Deep Wildcard Routes (Restored from Original Logic) ---
// Level 1: admin/services/method (e.g. admin/services/manage)
$route['admin/services/(:any)']                          = 'bizit_services_msl/admin/services/$1';
// Level 2: admin/services/method/param1 (e.g. admin/services/delete/5)
$route['admin/services/(:any)/(:any)']                   = 'bizit_services_msl/admin/services/$1/$2';
// Level 3: admin/services/method/param1/param2 (e.g. admin/services/new_request/1/REQ-001)
$route['admin/services/(:any)/(:any)/(:any)']            = 'bizit_services_msl/admin/services/$1/$2/$3';
// Level 4: admin/services/method/param1/param2/param3 (CRITICAL RESTORATION)
$route['admin/services/(:any)/(:any)/(:any)/(:any)']     = 'bizit_services_msl/admin/services/$1/$2/$3/$4';

// --- 3. Client Side Routes ---
$route['services/report/(:any)/(:any)']                  = 'bizit_services_msl/services/report/$1/$2';
$route['services/field_report/(:any)/(:any)']            = 'bizit_services_msl/services/field_report/$1/$2';
$route['services/certificate/(:any)/(:any)']             = 'bizit_services_msl/services/certificate/$1/$2';