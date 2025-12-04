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
                            
                            <?php echo form_open(admin_url('services/sales_orders/save_signature/'.$order->id), ['id'=>'order-confirmation-form']); ?>
                            
                            <hr />
                            <div class="row">
                                <div class="col-md-12">
                                    <h4 class="bold">Order Confirmation Details</h4>
                                    <p class="text-muted">Please enter payment details and sign to confirm this order.</p>
                                    
                                    <div class="form-group">
                                        <label for="payment_details" class="control-label">Payment / Deposit Details</label>
                                        <textarea id="payment_details" name="payment_details" class="form-control" rows="4" placeholder="e.g. 50% Deposit Received via MPESA Ref: QXJ..."><?php echo $order->payment_details; ?></textarea>
                                    </div>
                                </div>
                            </div>

                            <div class="row mtop20">
                                <div class="col-md-6">
                                    <p class="bold">Client Signature</p>
                                    <div class="form-group">
                                        <input type="text" name="signed_by" class="form-control" value="<?php echo $order->signed_by; ?>" placeholder="Client Name (Text)">
                                    </div>
                                    
                                    <div class="signature-pad-box" style="border: 2px dashed #d1d5db; background: #f9fafb; padding: 10px; border-radius: 4px;">
                                        <?php if(empty($order->signature_image)){ ?>
                                            <div id="client_signature_pad" style="min-height: 150px;"></div>
                                            <input type="hidden" name="client_signature" id="client_signature_input">
                                            <button type="button" class="btn btn-default btn-xs mtop10" onclick="signature_pad_clear('client')"><i class="fa fa-refresh"></i> Clear</button>
                                        <?php } else { ?>
                                            <div class="text-center">
                                                <img src="<?php echo base_url('modules/bizit_services_msl/uploads/signatures/'.$order->signature_image); ?>" class="img-responsive" style="max-height: 150px;">
                                                <p class="text-muted mtop10">Signed Digital Image</p>
                                            </div>
                                        <?php } ?>
                                    </div>
                                </div>

                                <div class="col-md-6">
                                    <p class="bold">Authorized Staff Signature</p>
                                    
                                    <div class="signature-pad-box" style="border: 2px dashed #d1d5db; background: #f9fafb; padding: 10px; border-radius: 4px;">
                                        <?php if(empty($order->staff_signature)){ ?>
                                            <div id="staff_signature_pad" style="min-height: 150px;"></div>
                                            <input type="hidden" name="staff_signature" id="staff_signature_input">
                                            <button type="button" class="btn btn-default btn-xs mtop10" onclick="signature_pad_clear('staff')"><i class="fa fa-refresh"></i> Clear</button>
                                        <?php } else { ?>
                                            <div class="text-center">
                                                <img src="<?php echo $order->staff_signature; ?>" class="img-responsive" style="max-height: 150px;">
                                                <p class="text-muted mtop10">Staff Signed</p>
                                            </div>
                                        <?php } ?>
                                    </div>
                                </div>
                            </div>

                            <div class="row mtop30">
                                <div class="col-md-12 text-right">
                                    <input type="hidden" name="status" value="2"> 
                                    <button type="submit" class="btn btn-info">
                                        <i class="fa fa-check-circle"></i> Save Signatures & Confirm Order
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
<script src="<?php echo base_url('assets/plugins/jSignature/jSignature.min.js'); ?>"></script>
<script>
    $(document).ready(function(){
        // Initialize Client Pad
        if($('#client_signature_pad').length > 0) {
            $("#client_signature_pad").jSignature({
                'Decor-Color':'transparent', 
                'color': '#000', 
                'lineWidth': 2,
                'height': 150,
                'width': '100%'
            });
        }

        // Initialize Staff Pad
        if($('#staff_signature_pad').length > 0) {
            $("#staff_signature_pad").jSignature({
                'Decor-Color':'transparent', 
                'color': '#000', 
                'lineWidth': 2,
                'height': 150,
                'width': '100%'
            });
        }
        
        // Capture Data on Submit
        $('#order-confirmation-form').submit(function(e){
            // Capture Client Signature
            if($('#client_signature_pad').length > 0 && $("#client_signature_pad").jSignature("getData", "native").length > 0){
                // Export as base30 for compact storage
                $('#client_signature_input').val($("#client_signature_pad").jSignature("getData", "base30"));
            }
            
            // Capture Staff Signature
            if($('#staff_signature_pad').length > 0 && $("#staff_signature_pad").jSignature("getData", "native").length > 0){
                // Export as image data (SVG/PNG) string if needed, or base30
                // Here using base30 for consistency
                 $('#staff_signature_input').val($("#staff_signature_pad").jSignature("getData", "image/jsignature;base30"));
            }
        });
    });
    
    function signature_pad_clear(type){
        $("#"+type+"_signature_pad").jSignature("reset");
    }
</script>
</body>
</html>
