<?php defined('BASEPATH') or exit('No direct script access allowed'); ?>
<?php init_head(); ?>
<div id="wrapper">
    <div class="content">
        <div class="row">
            <div class="col-md-12">
                <div class="panel_s">
                    <div class="panel-body">
                        <div class="_buttons">
                            <h4 class="no-margin"><?php echo _l('Sales Orders'); ?></h4>
                        </div>
                        <div class="clearfix"></div>
                        <hr class="hr-panel-heading" />
                        
                        <div class="table-responsive">
                            <table class="table dt-table" data-order-col="0" data-order-type="desc">
                                <thead>
                                    <tr>
                                        <th><?php echo _l('Order #'); ?></th>
                                        <th><?php echo _l('Client'); ?></th>
                                        <th><?php echo _l('Date'); ?></th>
                                        <th><?php echo _l('Total'); ?></th>
                                        <th><?php echo _l('Status'); ?></th>
                                        <th><?php echo _l('Options'); ?></th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php foreach($orders as $order){ ?>
                                    <tr>
                                        <td>
                                            <a href="<?php echo admin_url('services/sales_orders/order/' . $order['id']); ?>">
                                                <?php echo $order['prefix'] . str_pad($order['order_number'], 4, '0', STR_PAD_LEFT); ?>
                                            </a>
                                        </td>
                                        <td><?php echo get_company_name($order['clientid']); ?></td>
                                        <td><?php echo _d($order['date']); ?></td>
                                        <td><?php echo app_format_money($order['total'], get_currency($order['currency'])); ?></td>
                                        <td>
                                            <?php 
                                            $status_name = 'Draft';
                                            $label = 'default';
                                            if($order['status'] == 2) { $status_name = 'Confirmed'; $label = 'success'; }
                                            if($order['status'] == 3) { $status_name = 'Invoiced'; $label = 'info'; }
                                            ?>
                                            <span class="label label-<?php echo $label; ?>"><?php echo $status_name; ?></span>
                                        </td>
                                        <td>
                                            <a href="<?php echo admin_url('services/sales_orders/order/' . $order['id']); ?>" class="btn btn-default btn-icon"><i class="fa fa-eye"></i></a>
                                        </td>
                                    </tr>
                                    <?php } ?>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<?php init_tail(); ?>
</body>
</html>