<?php defined('BASEPATH') or exit('No direct script access allowed');

class Sales_orders_model extends App_Model
{
    public function __construct()
    {
        parent::__construct();
        $this->load->model('invoices_model');
        $this->load->model('estimates_model');
    }

    public function get($id = '')
    {
        if (is_numeric($id)) {
            $this->db->where('id', $id);
            $order = $this->db->get('tblsales_orders')->row();
            if ($order) {
                $order->items = $this->get_items($id);
            }
            return $order;
        }
        return $this->db->get('tblsales_orders')->result_array();
    }

    public function get_items($id)
    {
        return $this->db->where('rel_id', $id)->get('tblsales_order_items')->result_array();
    }

    // CONVERSION LOGIC: Estimate -> Sales Order
    public function convert_from_estimate($estimate_id)
    {
        $estimate = $this->estimates_model->get($estimate_id);
        if (!$estimate) return false;

        $data = [
            'order_number'    => get_option('next_sales_order_number') ?? 1,
            'prefix'          => 'SO-',
            'clientid'        => $estimate->clientid,
            'estimate_id'     => $estimate->id,
            'date'            => date('Y-m-d'),
            'currency'        => $estimate->currency,
            'subtotal'        => $estimate->subtotal,
            'total_tax'       => $estimate->total_tax,
            'total'           => $estimate->total,
            'adjustment'      => $estimate->adjustment,
            'discount_percent'=> $estimate->discount_percent,
            'discount_total'  => $estimate->discount_total,
            'status'          => 1, // Draft
            'client_note'     => $estimate->clientnote,
            'terms'           => $estimate->terms,
            'datecreated'     => date('Y-m-d H:i:s')
        ];

        $this->db->insert('tblsales_orders', $data);
        $order_id = $this->db->insert_id();

        if ($order_id) {
            // Map Items
            foreach ($estimate->items as $item) {
                $this->db->insert('tblsales_order_items', [
                    'rel_id'           => $order_id,
                    'description'      => $item['description'],
                    'long_description' => $item['long_description'],
                    'qty'              => $item['qty'],
                    'rate'             => $item['rate'],
                    'unit'             => $item['unit'],
                    'item_order'       => $item['item_order']
                ]);
            }
            log_activity('Estimate Converted to Sales Order [EstimateID: ' . $estimate_id . ', OrderID: ' . $order_id . ']');
            return $order_id;
        }
        return false;
    }

    // CONVERSION LOGIC: Sales Order -> Draft Invoice
    public function convert_to_invoice($order_id)
    {
        $order = $this->get($order_id);
        if (!$order) return false;

        // Prepare Invoice Data
        $newitems = [];
        $i = 1;
        foreach ($order->items as $item) {
            $newitems[$i] = [
                'order'            => $i,
                'description'      => $item['description'],
                'long_description' => $item['long_description'],
                'qty'              => $item['qty'],
                'unit'             => $item['unit'],
                'rate'             => $item['rate'],
                // Tax logic requires item_tax mapping if present in your system
            ];
            $i++;
        }

        $invoice_data = [
            'clientid'              => $order->clientid,
            'number'                => get_option('next_invoice_number'),
            'date'                  => date('Y-m-d'),
            'duedate'               => date('Y-m-d', strtotime('+30 days')),
            'currency'              => $order->currency,
            'newitems'              => $newitems,
            'subtotal'              => $order->subtotal,
            'total'                 => $order->total,
            'clientnote'            => $order->client_note,
            'terms'                 => $order->terms,
            'save_as_draft'         => true, // CRITICAL: Save as Draft
            'sales_order_id'        => $order_id // Custom field link if needed
        ];

        // Use Core Invoice Model
        $invoice_id = $this->invoices_model->add($invoice_data);

        if ($invoice_id) {
            // Mark Order as Invoiced
            $this->db->where('id', $order_id)->update('tblsales_orders', ['status' => 3]);
            return $invoice_id;
        }
        return false;
    }
    
    // Function to save signature and payment details
    public function update_confirmation_details($id, $data) {
        $this->db->where('id', $id);
        $this->db->update('tblsales_orders', $data);
        return $this->db->affected_rows() > 0;
    }
}
