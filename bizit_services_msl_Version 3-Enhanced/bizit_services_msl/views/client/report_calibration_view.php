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
                           <?php echo get_option('service_request_prefix') . $service_info->service_request_code ?>
                        </span>
                    </h3>
                    <span class="invoice-html-status">
                        <span class="label label-success s-status invoice-status">CALIBRATED</span>
                    </span>
                </div>
                <div class="tw-flex tw-items-end tw-space-x-2 tw-mt-3 sm:tw-mt-0 hide">
                     </div>
            </div>
        </div>
    </div>
    <div class="clearfix"></div>
    <div class="panel_s mtop20">
        <div class="panel-body">
            <div class="row">
                <div class="col-md-6">
                    <h4 class="bold">Instrument Info</h4>
                    <p><strong>Type:</strong> <?php echo $service_info->item_type; ?></p>
                    <p><strong>Model:</strong> <?php echo $service_info->item_model; ?></p>
                    <p><strong>Serial:</strong> <?php echo $service_info->serial_no; ?></p>
                </div>
                <div class="col-md-6">
                    <h4 class="bold">Service Info</h4>
                    <p><strong>Date:</strong> <?php echo _d($service_info->collection_date); ?></p>
                    <p><strong>Next Due:</strong> <?php echo isset($calibration_info->next_calibration_date) ? _d($calibration_info->next_calibration_date) : 'N/A'; ?></p>
                </div>
            </div>
            <div class="clearfix mtop30"></div>
            <hr>
            <div class="row">
                <div class="col-md-12">
                    <label class="control-label" style="font-weight:500;">REPORT REMARKS</label>
                    <p class="description"><?php if (!empty($calibration_info)) { echo $calibration_info->calibration_remark; } ?></p>
                </div>
            </div>
        </div>
    </div>
</div>
<script>
  $(function() { new Sticky('[data-sticky]'); });
</script>