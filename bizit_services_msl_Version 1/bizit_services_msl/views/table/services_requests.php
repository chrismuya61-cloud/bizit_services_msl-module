<?php
defined('BASEPATH') OR exit('No direct script access allowed');

$aColumns     = array(
              'tblservice_request.service_request_code',
              'tblservice_request.clientid',
              'tblservice_request.item_type', 
              'tblservice_request.drop_off_date',   
              'tblservice_request.collection_date',
              'tblservice_request.received_by', 
              'tblservice_request.status'
    );
$sIndexColumn = "service_request_id";
$sTable       = "tblservice_request";

$join             = array();
$additionalSelect = array('service_request_id','invoice_rel_id');
$where = array();

$result           = data_tables_init($aColumns, $sIndexColumn, $sTable, $join, $where, $additionalSelect);
$output           = $result['output'];
$rResult          = $result['rResult'];

foreach ($rResult as $aRow) {
    $row = array();
    for ($i = 0; $i < count($aColumns); $i++) {
        if (strpos($aColumns[$i], 'as') !== false && !isset($aRow[$aColumns[$i]])) {
            $_data = $aRow[strafter($aColumns[$i], 'as ')];
        } else {
            $_data = $aRow[$aColumns[$i]];
        }

        if ($aColumns[$i] == 'tblservice_request.clientid') {
            $_data = get_company_name($aRow['tblservice_request.clientid']);
        }

        if ($aColumns[$i] == 'tblservice_request.received_by') {
            $_data = get_staff_full_name($aRow['tblservice_request.received_by']);
        }

        if ($aColumns[$i] == 'tblservice_request.service_request_code') {
            $_data = get_option('service_request_prefix') . $aRow['tblservice_request.service_request_code'];
            if(!empty($aRow['invoice_rel_id']) and $aRow['invoice_rel_id'] > 0){
                $_data .= ' <span class="label label-primary">Invoiced</span>';
            }
        }

        if ($aColumns[$i] == 'tblservice_request.drop_off_date') {
            $_data = _d($aRow['tblservice_request.drop_off_date']);
        }

        if ($aColumns[$i] == 'tblservice_request.collection_date') {
            $_data = _d($aRow['tblservice_request.collection_date']);
        }

        if ($aColumns[$i] == 'tblservice_request.status') {
            if ($aRow['tblservice_request.status'] == 0) {
                $_data = '<span class="label label-warning">Pending Service</span>';
            } elseif ($aRow['tblservice_request.status'] == 1) {
                $_data = '<span class="label label-danger">Canceled Service</span>';
            } elseif ($aRow['tblservice_request.status'] == 3) {
                // Now check properly for Accessory and invoice status
                if ($aRow['tblservice_request.item_type'] == "Accessory") {
                    $invoice_status = null;
                    $CI = &get_instance();
                    $CI->db->select('status')
                           ->from('tblinvoices')
                           ->where('id', $aRow['invoice_rel_id'])
                           ->limit(1);
                    $invoice = $CI->db->get()->row();
        
                    if ($invoice && $invoice->status == 2) {
                        $_data = '<span class="label label-success">Paid Service</span>';
                    } else {
                        $_data = '<span class="label label-primary">Service Completed</span>';
                    }
                } else {
                    $_data = '<span class="label label-primary">Service Completed</span>';
                }
            } else {
                $_data = '<span class="label label-success">Paid Service</span>';
            }
        }
        
        

        $row[] = $_data;
    }

    $options = '';
    $options .= icon_btn('services/view_request/' . $aRow['tblservice_request.service_request_code'], 'fa fa-eye', 'btn-success');

    // Fetch invoice status if invoice_rel_id exists
    $invoice_status = null;
    if (!empty($aRow['invoice_rel_id'])) {
        $CI = &get_instance();
        $CI->db->select('status')
               ->from('tblinvoices')
               ->where('id', $aRow['invoice_rel_id'])
               ->limit(1);
        $invoice = $CI->db->get()->row();

        if ($invoice) {
            $invoice_status = $invoice->status;
        }
    }

    // Add the "edit" button if no invoice or invoice status is not "sent" (status != 2)
    if (is_null($invoice_status) || $invoice_status != 2 || $invoice_status == 1 || $invoice_status == 6) {
        $options .= icon_btn('services/new_request/1/' . $aRow['tblservice_request.service_request_code'], 'fa fa-edit', 'btn-default');
    }

    $options .= icon_btn('services/request_pdf/' . $aRow['tblservice_request.service_request_code'], 'fa fa-download', 'btn-info');

    $check_report_gen = check_report($aRow['service_request_id']);
    if (!empty($check_report_gen)) {
        $options .= icon_btn('services/report/view/' . $aRow['tblservice_request.service_request_code'], 'fa fa-bar-chart', 'btn-danger', array('title' => 'Service Report'));
    }

    $row[] = $options;

    $output['aaData'][] = $row;
}
