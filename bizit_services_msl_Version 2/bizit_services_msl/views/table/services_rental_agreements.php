<?php
defined('BASEPATH') OR exit('No direct script access allowed');

$aColumns = array(
	'tblservice_rental_agreement.service_rental_agreement_code',
	'tblservice_rental_agreement.clientid',
	'tblservice_rental_agreement.start_date',
	'tblservice_rental_agreement.end_date',
	'tblservice_rental_agreement.received_by',
	'tblservice_rental_agreement.status',
);
$sIndexColumn = "service_rental_agreement_id";
$sTable = "tblservice_rental_agreement";

$join = array();
$additionalSelect = array('service_rental_agreement_id', 'invoice_rel_id');
$where = array();

$result = data_tables_init($aColumns, $sIndexColumn, $sTable, $join, $where, $additionalSelect);
$output = $result['output'];
$rResult = $result['rResult'];

foreach ($rResult as $aRow) {

	$row = array();
	for ($i = 0; $i < count($aColumns); $i++) {

		if (strpos($aColumns[$i], 'as') !== false && !isset($aRow[$aColumns[$i]])) {
			$_data = $aRow[strafter($aColumns[$i], 'as ')];
		} else {
			$_data = $aRow[$aColumns[$i]];
		}

		if ($aColumns[$i] == 'tblservice_rental_agreement.clientid') {
			$_data = get_company_name($aRow['tblservice_rental_agreement.clientid']);
		}

		if ($aColumns[$i] == 'tblservice_rental_agreement.received_by') {
			$_data = get_staff_full_name($aRow['tblservice_rental_agreement.received_by']);
		}

		if ($aColumns[$i] == 'tblservice_rental_agreement.service_rental_agreement_code') {
			$_data = get_option('service_rental_agreement_prefix') . $aRow['tblservice_rental_agreement.service_rental_agreement_code'];
		}

		if ($aColumns[$i] == 'tblservice_rental_agreement.start_date') {
			$_data = _d($aRow['tblservice_rental_agreement.start_date']);
		}

		if ($aColumns[$i] == 'tblservice_rental_agreement.end_date') {
			$_data = _d($aRow['tblservice_rental_agreement.end_date']);
		}

		if ($aColumns[$i] == 'tblservice_rental_agreement.status') {
			if ($aRow['tblservice_rental_agreement.status'] == 0) {
				$_data = '<span class="label label-warning">Pending Rental</span>';
			} elseif ($aRow['tblservice_rental_agreement.status'] == 1) {
				$_data = '<span class="label label-danger">Canceled Rental</span>';
			} elseif ($aRow['tblservice_rental_agreement.status'] == 3) {
				$_data = '<span class="label label-primary">Pending-partially-paid Rental</span>';
			} else {
				$_data = '<span class="label label-success">Paid Rental</span>';
			}
		}

		$row[] = $_data;
	}

	$options = '';
	$options .= icon_btn('services/view_rental_agreement/' . $aRow['tblservice_rental_agreement.service_rental_agreement_code'], 'fa fa-eye', 'btn-success');
	if (empty($aRow['invoice_rel_id']) and has_permission(BIZIT_SERVICES_MSL.'_rental_agreement', '', 'edit')) {
		$options .= icon_btn('services/new_rental_agreement/1/' . $aRow['tblservice_rental_agreement.service_rental_agreement_code'], 'fa fa-edit', 'btn-default');
	}
	$options .= icon_btn('services/rental_agreement_pdf/' . $aRow['tblservice_rental_agreement.service_rental_agreement_code'], 'fa fa-download', 'btn-info');

	$row[] = $options;

	$output['aaData'][] = $row;
}
