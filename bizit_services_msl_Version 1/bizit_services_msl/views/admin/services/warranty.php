<?php init_head(); ?>
<div id="wrapper">
  <div class="content">
    <div class="row">
      <div class="col-md-12">
        <div class="panel_s">
          <div class="panel-body _buttons">
        <?php if(has_permission(BIZIT_SERVICES_MSL, '', 'create')) { ?>
            <a href="<?= admin_url('services/new_warranty'); ?>" class="btn btn-info"><i class="fa fa-plus"></i> <?php echo _l('new_service_warranty'); ?></a>
        <?php } ?>
            <a href="<?= admin_url('services/warranty_calendar'); ?>" class="btn btn-default"><i class="fa fa-calendar"></i> <?php echo _l('warranty_calendar'); ?></a>
          </div>
        </div>

        <div class="panel_s">
          <div class="panel-body">
            <div class="clearfix"></div>
              <?php 
              // Render the warranty DataTable with the necessary columns
              render_datatable(array(
                _l('product_name'),         // Product name column
                _l('serial_no'),            // Serial number column
                _l('product_category'),     // Product category column
                _l('date_sold'),            // Date sold column
                _l('days_remaining'),       // Days remaining on warranty column
                _l('end_date'),             // Warranty end date column
                _l('options'),              // Options column (e.g., edit, delete)
              ), 'services_warranty'); 
              ?>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>
<?php init_tail(); ?>

<script>
  // Initialize the DataTable for warranties
  $(function(){
    initDataTable('.table-services_warranty', admin_url + 'services/warranty', [6], [6], 'undefined', [3, 'DESC']);
  });
</script>
</body>
</html>
