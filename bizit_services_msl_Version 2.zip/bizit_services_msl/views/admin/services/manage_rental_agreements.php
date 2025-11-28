<?php init_head(); ?>
<div id="wrapper">
  <div class="content">
    <div class="row">
      <div class="col-md-12">
        <div class="panel_s">
          <div class="panel-body _buttons">
        <?php if(has_permission(BIZIT_SERVICES_MSL,'','create')){ ?>
            <a href="<?= admin_url('services/new_rental_agreement'); ?>" class="btn btn-info"><i class="fa fa-plus"></i> <?php echo _l('new_service_rental_agreement'); ?></a>
        <?php } ?>
            <a href="<?= admin_url('services/rental_calendar'); ?>" class="btn btn-default"><i class="fa fa-calendar"></i> <?php echo _l('rental_calendar'); ?></a>
          </div>
        </div>
        <div class="panel_s">
          <div class="panel-body">
            <div class="clearfix"></div>
              <?php render_datatable(array(
              _l('rental_agreement_code'), 
              _l('client'),  
              _l('rental_start_date'), 
              _l('rental_end_date'),
              _l('received_by'), 
              _l('status'),
              _l('options'),
              ),'services_rental_agreements'); ?>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>
<?php init_tail(); ?>
<script>
  $(function(){
    initDataTable('.table-services_rental_agreements', admin_url + 'services/rental_agreements', [6], [6],'undefined',[2,'DESC']);
  });
</script>
</body>
</html>
