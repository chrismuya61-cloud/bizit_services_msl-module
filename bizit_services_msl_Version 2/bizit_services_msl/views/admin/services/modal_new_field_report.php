<div class="modal fade" id="service_field_report_modal" role="dialog" aria-labelledby="myModalLabel"   data-backdrop="static">
    <div class="modal-dialog modal-md" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                <h4 class="modal-title" id="myModalLabel">
                    <span class="add-title"><?php echo _l('field_report_add_heading'); ?></span>
                </h4>
            </div>
            <div class="modal-body">                   
            <?php echo form_open('admin/services/manage_field_report',array('id'=>'service_field_report_form')); ?>
            <?php  
                echo form_hidden('rental_agreement_code', $service_info->service_rental_agreement_code); 
            ?>
                <div class="row">
                    <div class="col-md-12">
                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label class="control-label" for="report_code"><?php echo _l('field_report_code'); ?></label>
                                    <div class="input-group">
                                        <span class="input-group-addon"><?= get_option('service_rental_agreement_prefix'); ?></span>
                                        <input type="text" id="report_code" name="report_code" class="form-control" readonly="readonly">
                                    </div>
                                </div>
                            </div>                            
                            <div class="col-md-6">
                                <div class="form-group">
                                <label class="control-label" for="name"><?php echo _l('site_name'); ?></label>
                                <input type="text" id="site_name" name="site_name" class="form-control" value="<?= (isset($service_info) && $service_info->site_name != null ? $service_info->site_name : '') ?>" placeholder="Nairobi-Westlands area, Nanyuki town, Mombasa-Kilindini Port etc".>
                                </div>
                            </div>
                                <div class="clearfix mbot15"></div>
                        </div>              
            </div>
        </div> 
        <div class="modal-footer">       
            <button type="submit" class="btn btn-info" id="submit"><?php echo _l('submit'); ?></button>
            <button type="button" id="reset_close" class="btn btn-default" data-dismiss="modal"><?php echo _l('close'); ?></button>
        </div>
        <?php echo form_close(); ?>  
    </div>

</div>
</div>
</div>
