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
                            <?php echo get_option('service_rental_agreement_prefix').$field_report_info->report_code; ?>
                        </span>
                    </h3>
                    <span class="invoice-html-status">
                        <?php 
                        $status_label = 'default'; $status_text = 'DRAFT';
                        if($field_report_info->status == 2) { $status_label = 'info'; $status_text = 'SUBMITTED'; }
                        elseif($field_report_info->status == 4) { $status_label = 'success'; $status_text = 'APPROVED'; }
                        elseif($field_report_info->status == 3) { $status_label = 'danger'; $status_text = 'REJECTED'; }
                        ?>
                        <span class="label label-<?php echo $status_label; ?> s-status invoice-status"><?php echo $status_text; ?></span>
                    </span>
                </div>
            </div>
        </div>
    </div>
    <div class="clearfix"></div>
    <div class="panel_s mtop20">
        <div class="panel-body">
            <div class="col-md-12">
                <h4 class="bold">Field Report Details</h4>
                <hr class="hr-panel-heading" />
                <div class="row">
                    <div class="col-md-6">
                        <p><strong>Site Name:</strong> <?php echo $field_report_info->site_name; ?></p>
                        <p><strong>Date Created:</strong> <?php echo _d($field_report_info->dateadded); ?></p>
                    </div>
                    <div class="col-md-6">
                        <p><strong>Submitted By:</strong> <?php echo get_staff_full_name($field_report_info->submitted_by); ?></p>
                        <?php if($field_report_info->status == 4) { ?>
                            <p><strong>Approved By:</strong> <?php echo get_staff_full_name($field_report_info->approved_by); ?></p>
                        <?php } ?>
                    </div>
                </div>
                
                <div class="clearfix mtop20"></div>
                <h5 class="bold">Equipment List</h5>
                <div class="table-responsive">
                    <table class="table table-bordered table-striped">
                        <thead><tr><th>Item</th><th>Serial</th><th>Description</th></tr></thead>
                        <tbody>
                            <?php if(!empty($service_details)) { foreach($service_details as $d) { ?>
                                <tr><td><?php echo $d->name; ?></td><td><?php echo $d->rental_serial; ?></td><td><?php echo $d->description; ?></td></tr>
                            <?php } } ?>
                        </tbody>
                    </table>
                </div>

                <?php if($field_report_info->approval_remarks) { ?>
                    <div class="clearfix mtop20"></div>
                    <h5 class="bold">Remarks</h5>
                    <div class="alert alert-info"><?php echo $field_report_info->approval_remarks; ?></div>
                <?php } ?>
            </div>
        </div>
    </div>
</div>
<script>
    $(function() { new Sticky('[data-sticky]'); });
</script>