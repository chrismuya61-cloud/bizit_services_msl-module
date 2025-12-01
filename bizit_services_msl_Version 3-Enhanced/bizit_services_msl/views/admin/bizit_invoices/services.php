<div class="tab-pane fade col-md-12 gen_add_service" id="services_selector">
<?php
  $this->load->model('services_model');
  $service_category = $this->services_model->get_all_services_category_info([],true);
?>  
<div class="row">
    <div class="col-md-4">
      <div class="form-group mbot25">
        <select name="service_select" id="service_select" class="no-margin selectpicker" data-width="100%" data-none-selected-text="<?php echo _l('add_service'); ?>" data-live-search="true">
          <option value=""></option>
          <?php foreach ($service_category as $category) { ?>
            <option value="<?php echo $category['type_code']; ?>" data-subtext="<?php echo $category['type_code']; ?>"> <?php echo $category['name']; ?></option>
          <?php } ?>
        </select>
        <small class="text-info">Search by Service Category Name or Service Category Code</small>
      </div>
    </div>
    <div class="col-md-4">
      <div class="form-group mbot25">
        <select name="invoice_services" id="invoice_services" class="no-margin hide" data-width="100%" data-none-selected-text="<?php echo _l('get_services'); ?>" data-live-search="true">
          <option value=""></option>
        </select>
      </div>
    </div>
  </div>
</div>