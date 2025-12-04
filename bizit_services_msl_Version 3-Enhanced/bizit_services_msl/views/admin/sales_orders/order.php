<?php defined('BASEPATH') or exit('No direct script access allowed'); ?>
<?php init_head(); ?>
<div id="wrapper">
    <div class="content">
        <div class="row">
            <div class="col-md-12">
                <div class="panel_s">
                    <div class="panel-body">
                        
                        <div class="row">
                            <div class="col-md-6">
                                <h4 class="no-margin font-bold"><?php echo $title; ?></h4>
                                <p>Created From Estimate: <a href="<?php echo admin_url('estimates/list_estimates/'.$order->estimate_id); ?>">#<?php echo $order->estimate_id; ?></a></p>
                            </div>
                            <div class="col-md-6 text-right">
                                <?php if($order->status == 2) { // Only Confirmed orders can be invoiced ?>
                                    <a href="<?php echo admin_url('services/sales_orders/convert_to_invoice/'.$order->id); ?>" class="btn btn-success">
                                        <i class="fa fa-file-text"></i> Convert to Draft Invoice
                                    </a>
                                <?php } elseif($order->status == 3) { ?>
                                    <span class="label label-info">Invoiced</span>
                                <?php } ?>
                            </div>
                        </div>
                        <hr />

                        <div class="table-responsive mtop20">
                            <table class="table items items-preview">
                                <thead>
                                    <tr>
                                        <th>Item</th>
                                        <th>Qty</th>
                                        <th>Rate</th>
                                        <th>Total</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php foreach($order->items as $item){ ?>
                                    <tr>
                                        <td>
                                            <span class="bold"><?php echo $item['description']; ?></span><br />
                                            <span class="text-muted"><?php echo $item['long_description']; ?></span>
                                        </td>
                                        <td><?php echo floatVal($item['qty']); ?></td>
                                        <td><?php echo app_format_money($item['rate'], get_currency($order->currency)); ?></td>
                                        <td><?php echo app_format_money($item['rate'] * $item['qty'], get_currency($order->currency)); ?></td>
                                    </tr>
                                    <?php } ?>
                                </tbody>
                                <tfoot>
                                    <tr>
                                        <td colspan="3" class="text-right bold">Subtotal</td>
                                        <td><?php echo app_format_money($order->subtotal, get_currency($order->currency)); ?></td>
                                    </tr>
                                    <tr>
                                        <td colspan="3" class="text-right bold">Total</td>
                                        <td><?php echo app_format_money($order->total, get_currency($order->currency)); ?></td>
                                    </tr>
                                </tfoot>
                            </table>
                        </div>

                        <?php if($order->status == 1 || $order->status == 2) { // Allow editing if Draft or Confirmed ?>
                        <?php echo form_open(admin_url('services/sales_orders/order/'.$order->id), ['id'=>'order-confirmation-form']); ?>
                        <hr />
                        <div class="row">
                            <div class="col-md-6">
                                <h4 class="bold">Order Confirmation Details</h4>
                                <p class="text-muted">Please enter payment details and sign to confirm this order.</p>
                                
                                <div class="form-group">
                                    <label for="payment_details" class="control-label">Payment / Deposit Details</label>
                                    <textarea id="payment_details" name="payment_details" class="form-control" rows="4" placeholder="e.g. 50% Deposit Received via MPESA Ref: QXJ..."><?php echo $order->payment_details; ?></textarea>
                                </div>
                            </div>
                            
                            <div class="col-md-6">
                                <h4 class="bold">Authorization</h4>
                                <div class="form-group">
                                    <label for="signed_by" class="control-label">Signed By (Name)</label>
                                    <input type="text" id="signed_by" name="signed_by" class="form-control" value="<?php echo $order->signed_by; ?>">
                                </div>
                                
                                <input type="hidden" name="status" value="2"> <button type="submit" class="btn btn-info pull-right">
                                    <i class="fa fa-check"></i> Confirm Order
                                </button>
                            </div>
                        </div>
                        <?php echo form_close(); ?>
                        <?php } ?>

                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<?php init_tail(); ?>
</body>
</html>