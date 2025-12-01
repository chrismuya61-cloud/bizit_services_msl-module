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
                        <span class="label label-success s-status invoice-status">VERIFIED</span>
                    </span>
                </div>
                <div class="tw-flex tw-items-end tw-space-x-2 tw-mt-3 sm:tw-mt-0 hide">
                    <?php echo form_open($this->uri->uri_string()); ?>
                    <button type="submit" name="invoicepdf" value="invoicepdf" class="btn btn-default action-button">
                        <i class='fa-regular fa-file-pdf'></i>
                        <?php echo _l('clients_invoice_html_btn_download'); ?>
                    </button>
                    <?php echo form_close(); ?>
                </div>
            </div>
        </div>
    </div>
</div>
<div class="clearfix"></div>
<div class="panel_s tw-mt-6">
    <div class="panel-body">
        <div id="details" class="clearfix">
            <div class="col-md-12">
                <h4>This Certificate is a valid certificate from Measurement Systems Ltd</h4>
                <hr class="hr-panel-heading" />
            </div>
            <div id="invoice" class="col-md-6">
                <div class="date"><b>Make:</b> <?php echo $service_info->item_make ?></div>
                <div class="date"><b>Model:</b> <?php echo $service_info->item_model ?></div>
                <div class="date"><b>Certificate No.:</b> <?php echo get_option('service_request_prefix') . $service_info->service_request_code ?></div>
                <?php $date = strtotime($service_info->collection_date);?>
                <div class="date"><b>Valid Until:</b> <?php echo _d(date('Y-m-d', strtotime('+1 year', $date))); ?></div>
            </div>
        </div>
    </div>
</div>
<script>
    $(function() {
        new Sticky('[data-sticky]');
        var $payNowTop = $('.pay-now-top');
        if ($payNowTop.length && !$('#pay_now').isInViewport()) {
            $payNowTop.removeClass('hide');
            $('.pay-now-top').on('click', function(e) {
                e.preventDefault();
                $('html,body').animate({
                        scrollTop: $("#online_payment_form").offset().top
                    },
                    'slow');
            });
        }

        $('#online_payment_form').appFormValidator();

        var online_payments = $('.online-payment-radio');
        if (online_payments.length == 1) {
            online_payments.find('input').prop('checked', true);
        }
    });
</script>