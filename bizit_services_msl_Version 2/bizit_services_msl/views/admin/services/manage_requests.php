<?php init_head(); ?>
<div id="wrapper">
  <div class="content">
    <div class="row">
      <div class="col-md-12">
        <?php if(has_permission(BIZIT_SERVICES_MSL,'','create')){ ?>
        <div class="panel_s">
          <div class="panel-body _buttons">
            <a href="<?= admin_url('services/new_request'); ?>" class="btn btn-info pull-left"><i class="fa fa-plus"></i> <?php echo _l('new_service_request'); ?></a>
          </div>

        </div>
        <?php } ?>
        <div class="panel_s">
          <div class="panel-body">
            <div class="clearfix"></div>
              <?php render_datatable(array(
              _l('request_code'), 
              _l('client'),  
              _l('item'), 
              _l('drop_off_date'), 
              _l('collection_date'),
              _l('received_by'),
              _l('status'),
              _l('options'),
              ),'services_requests'); ?>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>
<?php init_tail(); ?>
<script>
  $(function(){
    initDataTable('.table-services_requests', admin_url + 'services/requests', [7], [7],'undefined',[3,'DESC']);
  });
</script>
</body>
</html>
