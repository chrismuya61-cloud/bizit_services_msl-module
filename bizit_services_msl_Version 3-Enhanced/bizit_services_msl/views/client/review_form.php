<?php defined('BASEPATH') or exit('No direct script access allowed'); ?>
<div class="mtop15 preview-top-wrapper">
    <div class="row">
        <div class="col-md-3">
            <div class="mbot30"><div class="invoice-html-logo"><?php echo get_dark_company_logo(); ?></div></div>
        </div>
    </div>
    <div class="panel_s">
        <div class="panel-body">
            <h3 class="text-center bold">Service Feedback</h3>
            <h4 class="text-center text-info mbot20"><?php echo $title_text; ?></h4>
            <hr />
            <?php echo form_open(site_url('bizit_services_msl/reviews/rate/'.$this->uri->segment(4))); ?>
            <div class="form-group text-center mtop30">
                <label style="font-size: 16px;">How would you rate our service?</label><br>
                <?php foreach([5=>'Excellent', 4=>'Good', 3=>'Average', 2=>'Poor', 1=>'Very Poor'] as $k=>$v): ?>
                    <label class="radio-inline"><input type="radio" name="rating" value="<?php echo $k; ?>" <?php echo $k==5?'checked':''; ?>> <?php echo $k; ?> - <?php echo $v; ?></label>
                <?php endforeach; ?>
            </div>
            <div class="form-group mtop20">
                <label>Comments</label>
                <textarea name="comment" class="form-control" rows="5"></textarea>
            </div>
            <div class="text-center mtop20">
                <button type="submit" class="btn btn-success">Submit Review</button>
            </div>
            <?php echo form_close(); ?>
        </div>
    </div>
</div>