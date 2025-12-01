<?php defined('BASEPATH') or exit('No direct script access allowed'); ?>
<?php init_head(); ?>
<div id="wrapper">
    <div class="content">
        <div class="row">
            <div class="col-md-12">
                
                <div class="panel_s">
                    <div class="panel-body">
                        <h4 class="no-margin"><?php echo $title; ?></h4>
                        <hr class="hr-panel-heading" />
                        
                        <div class="row mbot15">
                            <div class="col-md-3 col-xs-6 border-right">
                                <h3 class="bold no-margin text-danger"><?php echo $widgets['cal_overdue']; ?></h3>
                                <span class="text-muted"><?php echo _l('cal_overdue'); ?></span>
                            </div>
                            <div class="col-md-3 col-xs-6 border-right">
                                <h3 class="bold no-margin text-warning"><?php echo $widgets['cal_approaching']; ?></h3>
                                <span class="text-muted"><?php echo _l('cal_approaching'); ?></span>
                            </div>
                            <div class="col-md-3 col-xs-6 border-right">
                                <h3 class="bold no-margin text-info"><?php echo $widgets['rental_open']; ?></h3>
                                <span class="text-muted"><?php echo _l('rental_open'); ?></span>
                            </div>
                            <div class="col-md-3 col-xs-6">
                                <h3 class="bold no-margin text-success"><?php echo $widgets['report_draft']; ?></h3>
                                <span class="text-muted"><?php echo _l('report_draft'); ?></span>
                            </div>
                        </div>
                        <hr />

                        <?php echo form_open(admin_url('services/reports_dashboard'), ['method'=>'post']); ?>
                        <div class="row">
                            <div class="col-md-4">
                                <?php echo render_date_input('start_date', 'Start Date', _d($start_date)); ?>
                            </div>
                            <div class="col-md-4">
                                <?php echo render_date_input('end_date', 'End Date', _d($end_date)); ?>
                            </div>
                            <div class="col-md-4">
                                <button type="submit" class="btn btn-info mtop25"><?php echo _l('filter'); ?></button>
                            </div>
                        </div>
                        <?php echo form_close(); ?>
                    </div>
                </div>

                <div class="row">
                    <div class="col-md-6">
                        <div class="panel_s">
                            <div class="panel-body">
                                <h4 class="bold no-margin text-info"><i class="fa fa-cogs"></i> Most Popular Services</h4>
                                <hr />
                                <div class="table-responsive">
                                    <table class="table table-striped">
                                        <thead>
                                            <tr>
                                                <th>Service Name</th>
                                                <th class="text-right">Requests</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <?php if(empty($analytics['services'])){ ?>
                                                <tr><td colspan="2" class="text-center">No data available</td></tr>
                                            <?php } else { foreach($analytics['services'] as $s) { ?>
                                                <tr>
                                                    <td><?php echo $s['name']; ?></td>
                                                    <td class="text-right bold"><?php echo $s['total_requests']; ?></td>
                                                </tr>
                                            <?php } } ?>
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        </div>
                    </div>
                    
                    <div class="col-md-6">
                        <div class="panel_s">
                            <div class="panel-body">
                                <h4 class="bold no-margin text-success"><i class="fa fa-truck"></i> Most Rented Equipment</h4>
                                <hr />
                                <div class="table-responsive">
                                    <table class="table table-striped">
                                        <thead>
                                            <tr>
                                                <th>Equipment</th>
                                                <th class="text-right">Days Rented</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <?php if(empty($analytics['rentals'])){ ?>
                                                <tr><td colspan="2" class="text-center">No data available</td></tr>
                                            <?php } else { foreach($analytics['rentals'] as $r) { ?>
                                                <tr>
                                                    <td><?php echo $r['name']; ?></td>
                                                    <td class="text-right bold"><?php echo $r['total_days']; ?></td>
                                                </tr>
                                            <?php } } ?>
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="panel_s">
                    <div class="panel-body">
                        <h4 class="bold no-margin">Staff Performance & Allowances</h4>
                        <hr class="hr-panel-heading" />
                        
                        <div class="table-responsive">
                            <table class="table dt-table" data-order-col="5" data-order-type="desc">
                                <thead>
                                    <tr>
                                        <th>Staff Member</th>
                                        <th>Services (Units)</th>
                                        <th>Service Allowance</th>
                                        <th>Rentals (Days)</th>
                                        <th>Rental Allowance</th>
                                        <th>Total Earnings</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php foreach($performance_data as $row): ?>
                                    <tr>
                                        <td>
                                            <a href="<?php echo admin_url('staff/member/'.$row['staffid']); ?>">
                                                <?php echo staff_profile_image($row['staffid'], ['staff-profile-image-small', 'mright5']); ?>
                                                <?php echo $row['full_name']; ?>
                                            </a>
                                        </td>
                                        <td><?php echo $row['service_units']; ?></td>
                                        <td><?php echo app_format_money($row['service_allowance'], get_currency_symbol()); ?></td>
                                        <td><?php echo $row['rental_days']; ?></td>
                                        <td><?php echo app_format_money($row['rental_allowance'], get_currency_symbol()); ?></td>
                                        <td class="bold text-success"><?php echo app_format_money($row['total_allowance'], get_currency_symbol()); ?></td>
                                    </tr>
                                    <?php endforeach; ?>
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