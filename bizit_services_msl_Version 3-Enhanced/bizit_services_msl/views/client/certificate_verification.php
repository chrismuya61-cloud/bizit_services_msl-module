<?php defined('BASEPATH') or exit('No direct script access allowed'); ?>
<div class="mtop15 preview-top-wrapper">
    <div class="row">
        <div class="col-md-3">
            <div class="mbot30">
                <div class="invoice-html-logo">
                    <?php echo get_dark_company_logo(); ?>
                </div>
            </div>
        </div>
        <div class="clearfix"></div>
    </div>
    <div class="top" data-sticky data-sticky-class="preview-sticky-header" style="z-index: 99;">
        <div class="container preview-sticky-container">
            <div class="sm:tw-flex tw-justify-between -tw-mx-4">
                <div class="sm:tw-self-end">
                    <h3 class="bold tw-my-0 invoice-html-number">
                        <span class="sticky-visible hide tw-mb-2">
                            <?php echo get_option('service_request_prefix') . $service_request->service_request_code; ?>
                        </span>
                    </h3>
                    <span class="invoice-html-status">
                        <span class="label label-success s-status invoice-status">VERIFIED</span>
                    </span>
                </div>
                <div class="tw-flex tw-items-end tw-space-x-2 tw-mt-3 sm:tw-mt-0">
                    <?php if(is_client_logged_in()) { ?>
                    <a href="<?php echo site_url('clients/tickets'); ?>" class="btn btn-default action-button go-to-portal">
                        Go to Portal
                    </a>
                    <?php } ?>
                </div>
            </div>
        </div>
    </div>
    <div class="clearfix"></div>
    <div class="panel_s mtop20">
        <div class="panel-body">
            <div class="col-md-10 col-md-offset-1">
                <div class="row mtop20">
                    <div class="col-md-12 text-center">
                        <h4 class="bold mbot15">Certificate of Calibration</h4>
                        <p class="text-muted">This instrument has been calibrated and verified by <?php echo get_option('companyname'); ?>.</p>
                        <hr class="hr-panel-heading" />
                    </div>
                    <div class="col-md-6 border-right">
                        <h5 class="bold">Instrument Details</h5>
                        <table class="table table-condensed">
                            <tbody>
                                <tr><td>Make:</td><td><?php echo $service_request->item_make; ?></td></tr>
                                <tr><td>Model:</td><td><?php echo $service_request->item_model; ?></td></tr>
                                <tr><td>Type:</td><td><?php echo $service_request->item_type; ?></td></tr>
                                <tr><td>Serial No:</td><td><?php echo $service_request->serial_no; ?></td></tr>
                            </tbody>
                        </table>
                    </div>
                    <div class="col-md-6">
                         <h5 class="bold">Certificate Details</h5>
                         <table class="table table-condensed">
                            <tbody>
                                <tr><td>Certificate No:</td><td><?php echo get_option('service_request_prefix') . $service_request->service_request_code; ?></td></tr>
                                <tr><td>Date Calibrated:</td><td><?php echo _d($service_request->collection_date); ?></td></tr>
                                <tr><td>Valid Until:</td><td class="text-success bold"><?php echo _d(date('Y-m-d', strtotime('+1 year', strtotime($service_request->collection_date)))); ?></td></tr>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<script>
    $(function() {
        new Sticky('[data-sticky]');
    });
</script>