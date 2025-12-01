<div class="modal fade" id="services_modal" role="dialog" aria-labelledby="myModalLabel"   data-backdrop="static">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                <h4 class="modal-title" id="myModalLabel">
                    <span class="edit-title"><?php echo _l('category_edit_heading'); ?></span>
                    <span class="add-title"><?php echo _l('category_add_heading'); ?></span>
                </h4>
            </div>                   
            <?php echo form_open('admin/services/manage',array('id'=>'service_form')); ?>
            <div class="modal-body">
                <div class="row">
                    <div class="col-md-12">
            <?php echo form_hidden('serviceid'); ?>
                        <div class="row">
                            <div class="col-lg-4 col-md-6">
                             <div class="form-group">
                                <label class="control-label" for="category"><?php echo _l('service_category'); ?></label>
                                <select class="selectpicker display-block" data-width="100%" id="service_type_code" name="service_type_code" data-live-search="true" data-none-selected-text="<?php echo _l('no_category'); ?>">
                                    <option value=""></option>
                                    <?php foreach($service_categories as $key => $category){ ?>
                                    <option value="<?php echo $category['type_code']; ?>"><?php echo $category['name']; ?></option>
                                    <?php } ?>
                                </select>
                            </div>
                            </div>
                            <div class="col-lg-4 col-md-6">
                                <div class="form-group">
                                <label class="control-label" for="service_code"><?php echo _l('service_code'); ?></label>
                                <input type="text" id="service_code" name="service_code" class="form-control" readonly placeholder="Service Code">
                                </div>
                            </div>                            
                            <div class="col-lg-4 col-md-6">
                                <div class="form-group">
                                <label class="control-label" for="name"><?php echo _l('name'); ?></label>
                                <input type="text" id="name" name="name" class="form-control" placeholder="Service Name">
                                </div>
                            </div>

                            <div class="col-md-6">
                                <div class="form-group">
                                <label class="control-label" for="name"><?php echo _l('price'); ?></label>
                                <div class="input-group">
                                    <span class="input-group-addon">
                                    <?php echo $currency_symbol; ?></span>
                                <input type="text" id="price" name="price" class="form-control" placeholder="Price">
                                </div>
                                </div>
                            </div>

                            <div class="col-md-6">
                                <div class="form-group">
                                    <?php echo render_textarea('description','description'); ?>
                                </div>
                            </div>
                           <div class="quantity_unit_area hide">                            
                                <div class="col-md-6">
                                    <div class="form-group">
                                    <label class="control-label" for="service_unit">Quantity Unit</label>
                                    <input type="text" id="quantity_unit" name="quantity_unit" class="form-control" placeholder="e.g. PCs, Sevices etc.">
                                    </div>
                                </div> 
                           </div>
                           <div class="rental_row hide">
                                <div class="col-md-6">
                                    <div class="form-group">
                                    <label class="control-label" for="name"><?php echo _l('penalty_price'); ?></label>
                                    <div class="input-group">
                                        <span class="input-group-addon">
                                        <?php echo $currency_symbol; ?></span>
                                     <input type="text" id="penalty_rental_price" name="penalty_rental_price" class="form-control" placeholder="Rental Extra Duration Price">
                                    </div>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group">
                                    <label class="control-label" for="name"><?php echo _l('rental_serial'); ?></label>
                                    <input type="text" id="rental_serial" name="rental_serial" class="form-control" placeholder="Serial Number">
                                    </div>
                                </div>
                                
                                <div class="col-md-6">
                                 <div class="form-group">
                                    <label class="control-label" for="rental_duration_check"><?php echo _l('rental_duration_check'); ?></label>
                                    <select class="selectpicker display-block" data-width="100%" name="rental_duration_check" id="rental_duration_check">
                                        <option value="hours">Hourly</option>
                                        <option value="days">Daily</option>
                                        <option value="weeks">Weekly</option>
                                        <option value="months">Monthly</option>
                                        <option value="years">Annually</option>
                                    </select>
                                </div>
                                </div>
                                
                                <div class="col-md-6">
                                 <div class="form-group">
                                    <label class="control-label" for="rental_status"><?php echo _l('rental_status'); ?></label>
                                    <select class="selectpicker display-block" data-width="100%" name="rental_status" id="rental_status">
                                        <option value="Not-Hired">Not Hired</option>
                                        <option value="Hired">Hired</option>
                                    </select>
                                </div>
                                </div>
                           </div>
                                
                        </div>               
            </div>
        </div> 
        <div class="clearfix mbot10"></div>
        <div class="modal-footer">
        <button type="button" id="reset_close" class="btn btn-default" data-dismiss="modal"><?php echo _l('close'); ?></button>
        <button type="submit" class="btn btn-info" id="submit"><?php echo _l('submit'); ?></button>       
        </div>
    </div>
        <?php echo form_close(); ?> 
    
</div>
</div>
</div>
