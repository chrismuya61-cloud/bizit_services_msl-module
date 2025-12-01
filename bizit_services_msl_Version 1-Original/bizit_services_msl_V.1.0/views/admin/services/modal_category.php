<div class="modal fade" id="service_category_modal" role="dialog" aria-labelledby="myModalLabel"   data-backdrop="static">
    <div class="modal-dialog modal-md" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                <h4 class="modal-title" id="myModalLabel">
                    <span class="edit-title"><?php echo _l('category_edit_heading'); ?></span>
                    <span class="add-title"><?php echo _l('category_add_heading'); ?></span>
                </h4>
            </div>
            <div class="modal-body">
                <div class="row">
                    <div class="col-md-12">                   
            <?php echo form_open('admin/services/category_manage',array('id'=>'service_category_form')); ?>
            <?php echo form_hidden('service_typeid'); ?>
                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                <label class="control-label" for="type_code"><?php echo _l('service_type_code'); ?></label>
                                <input type="text" id="type_code" name="type_code" class="form-control">
                                </div>
                            </div>                            
                            <div class="col-md-6">
                                <div class="form-group">
                                <label class="control-label" for="name"><?php echo _l('name'); ?></label>
                                <input type="text" id="name" name="name" class="form-control">
                                </div>
                            </div>
                                <div class="clearfix mbot15"></div>
                            <div class="col-md-12 text-right">
                              <button type="submit" class="btn btn-info" id="submit"><?php echo _l('submit'); ?></button>
                            </div>
                        </div>
        <?php echo form_close(); ?>                
            </div>
                <div class="col-md-12" style="padding-top:10px; margin-top:10px; border-top:#efefef 1px solid;">
                    <div class="clearfix"></div>
                        <?php render_datatable(array(
                          _l('service_type_code'),
                          _l('name'),
                          _l('status'),
                          _l('options'),
                          ),'service_category'); 
                        ?>
                </div>
        </div> 
        <div class="modal-footer">       
        <button type="button" id="reset_close" class="btn btn-default" data-dismiss="modal"><?php echo _l('close'); ?></button>
        </div>
    </div>
    
</div>
</div>
</div>
