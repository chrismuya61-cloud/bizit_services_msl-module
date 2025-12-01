<?php defined('BASEPATH') or exit('No direct script access allowed');

/*
|--------------------------------------------------------------------------
| BIZIT SERVICES MSL ROUTES (Version 3.2)
|--------------------------------------------------------------------------
*/

// ------------------------------------------------------------------------
// ADMIN ROUTES
// ------------------------------------------------------------------------

// Main Entry Point
$route['admin/services'] = 'bizit_services_msl/admin/services/index';

// Specific Shortcuts (to ensure they work cleanly)
$route['admin/services/requests']                 = 'bizit_services_msl/admin/services/requests';
$route['admin/services/rental_agreements']        = 'bizit_services_msl/admin/services/rental_agreements';
$route['admin/services/field_reports']            = 'bizit_services_msl/admin/services/field_reports';
$route['admin/services/staff_compensation_rates'] = 'bizit_services_msl/admin/services/staff_compensation_rates';
$route['admin/services/reports_dashboard']        = 'bizit_services_msl/admin/services/reports_dashboard';

// Dynamic Admin Routes (Handles new_request/1/CODE etc.)
// We use generic wildcards to catch all method calls and their parameters
$route['admin/services/(:any)']                   = 'bizit_services_msl/admin/services/$1';
$route['admin/services/(:any)/(:any)']            = 'bizit_services_msl/admin/services/$1/$2';
$route['admin/services/(:any)/(:any)/(:any)']     = 'bizit_services_msl/admin/services/$1/$2/$3';
$route['admin/services/(:any)/(:any)/(:any)/(:any)'] = 'bizit_services_msl/admin/services/$1/$2/$3/$4';

// ------------------------------------------------------------------------
// CLIENT PORTAL ROUTES (Public / Client Facing)
// ------------------------------------------------------------------------

// 1. Reviews System
$route['bizit_services_msl/reviews/rate/(:any)'] = 'bizit_services_msl/reviews/rate/$1'; // $1 = Token
$route['bizit_services_msl/reviews/thank_you']   = 'bizit_services_msl/reviews/thank_you';

// 2. Verification & Reports (QR Code Destinations)
// These match the V3 Client Controller signatures: public function method($code)
$route['services/certificate/(:any)']  = 'bizit_services_msl/services/certificate/$1'; // $1 = Request Code
$route['services/field_report/(:any)'] = 'bizit_services_msl/services/field_report/$1'; // $1 = Report Code
$route['services/report/(:any)']       = 'bizit_services_msl/services/report/$1';       // $1 = Calibration Code