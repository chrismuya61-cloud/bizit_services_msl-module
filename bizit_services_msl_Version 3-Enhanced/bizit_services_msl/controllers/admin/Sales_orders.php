<?php defined('BASEPATH') or exit('No direct script access allowed');

class Sales_orders extends AdminController
{
    public function __construct()
    {
        parent::__construct();
        $this->load->model('bizit_services_msl/sales_orders_model');
    }

    // List Sales Orders
    public function index()
    {
        if (!has_permission('estimates', '', 'view')) access_denied('Sales Orders');
        $data['title'] = 'Sales Orders';
        $data['orders'] = $this->sales_orders_model->get();
        $this->load->view('admin/sales_orders/manage', $data);
    }

    // Convert Estimate to Order
    public function convert_estimate($id)
    {
        if (!has_permission('estimates', '', 'create')) access_denied('Sales Orders');
        $order_id = $this->sales_orders_model->convert_from_estimate($id);
        
        if ($order_id) {
            set_alert('success', 'Sales Order Created Successfully');
            redirect(admin_url('services/sales_orders/order/' . $order_id));
        } else {
            set_alert('warning', 'Failed to convert Estimate');
            redirect(admin_url('estimates/list_estimates/' . $id));
        }
    }

    // View/Edit Order (Add Signature & Payment Details)
    public function order($id)
    {
        if ($this->input->post()) {
            $data = $this->input->post();
            // Handle Signature Upload logic here (base64 or file)
            // Save Payment Details
            $success = $this->sales_orders_model->update_confirmation_details($id, $data);
            if($success) {
                 set_alert('success', 'Order Confirmed');
                 // Auto-convert to invoice if requested?
            }
            redirect(admin_url('services/sales_orders/order/' . $id));
        }
        
        $data['order'] = $this->sales_orders_model->get($id);
        $data['title'] = 'Sales Order #' . $data['order']->prefix . $data['order']->order_number;
        $this->load->view('admin/sales_orders/order', $data);
    }

    // Final Step: Convert to Invoice
    public function convert_to_invoice($id)
    {
        if (!has_permission('invoices', '', 'create')) access_denied('Invoices');
        
        $invoice_id = $this->sales_orders_model->convert_to_invoice($id);
        if ($invoice_id) {
            set_alert('success', 'Draft Invoice Created from Sales Order');
            redirect(admin_url('invoices/list_invoices/' . $invoice_id));
        } else {
            set_alert('warning', 'Failed to create Invoice');
            redirect(admin_url('services/sales_orders/order/' . $id));
        }
    }
}
