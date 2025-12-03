<?php defined('BASEPATH') or exit('No direct script access allowed'); ?>

<div class="container mtop20">
    <div class="row">
        <div class="col-md-8 col-md-offset-2">
            
            <div class="panel_s">
                <div class="panel-body text-center" style="border-top: 5px solid #84c529;">
                    <div class="mbot20">
                         <?php echo get_dark_company_logo(); ?>
                    </div>
                    
                    <h1 class="bold mbot5" style="font-size: 24px;">Certificate Verification</h1>
                    <p class="text-muted">Calibration Record Validation System</p>
                    
                    <div class="mtop20">
                        <span class="label label-success" style="font-size: 100%; padding: 8px 15px; text-transform: uppercase; letter-spacing: 1px;">
                            <i class="fa fa-check-circle"></i> Verified Authentic
                        </span>
                    </div>
                </div>
            </div>

            <div class="panel_s">
                <div class="panel-heading">
                    <span class="bold">Instrument Information</span>
                    <span class="pull-right text-muted">
                        Ref: <?php echo get_option('service_request_prefix') . $service_request->service_request_code; ?>
                    </span>
                </div>
                <div class="panel-body">
                    <div class="row">
                        <div class="col-md-6">
                            <table class="table table-striped">
                                <tbody>
                                    <tr>
                                        <td class="text-muted">Make / Manufacturer</td>
                                        <td class="bold"><?php echo $service_request->item_make; ?></td>
                                    </tr>
                                    <tr>
                                        <td class="text-muted">Model</td>
                                        <td class="bold"><?php echo $service_request->item_model; ?></td>
                                    </tr>
                                    <tr>
                                        <td class="text-muted">Equipment Type</td>
                                        <td class="bold"><?php echo $service_request->item_type; ?></td>
                                    </tr>
                                    <tr>
                                        <td class="text-muted">Serial Number</td>
                                        <td class="bold"><?php echo $service_request->serial_no; ?></td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>
                        <div class="col-md-6">
                            <div class="alert alert-info text-center">
                                <h5 class="bold no-margin">Calibration Status</h5>
                            </div>
                            <table class="table">
                                <tbody>
                                    <tr>
                                        <td class="text-muted">Date Calibrated</td>
                                        <td class="bold text-right"><?php echo _d($service_request->collection_date); ?></td>
                                    </tr>
                                    <tr>
                                        <td class="text-muted">Valid Until</td>
                                        <td class="bold text-success text-right">
                                            <?php echo _d(date('Y-m-d', strtotime('+1 year', strtotime($service_request->collection_date)))); ?>
                                        </td>
                                    </tr>
                                </tbody>
                            </table>
                            
                            <?php if(is_client_logged_in()) { ?>
                                <a href="<?php echo site_url('clients/tickets'); ?>" class="btn btn-default btn-block mtop10">
                                    <i class="fa fa-life-ring"></i> Open Support Ticket
                                </a>
                            <?php } ?>
                        </div>
                    </div>
                </div>
                <div class="panel-footer text-muted text-center" style="font-size: 12px;">
                    This digital record confirms the instrument listed above has been serviced and calibrated by <?php echo get_option('companyname'); ?>.
                </div>
            </div>

        </div>
    </div>
</div>
