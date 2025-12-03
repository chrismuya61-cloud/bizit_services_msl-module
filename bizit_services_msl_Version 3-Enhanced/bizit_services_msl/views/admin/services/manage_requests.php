<?php init_head(); ?>
<div id="wrapper">
  <div class="content">
    <div class="row">
      <div class="col-md-12">
        
        <div class="panel_s">
          <div class="panel-body">
            <div class="_buttons">
              <?php if(has_permission(BIZIT_SERVICES_MSL,'','create')){ ?>
                  <a href="<?= admin_url('services/new_request'); ?>" class="btn btn-info pull-left display-block">
                      <i class="fa fa-plus-circle"></i> <?php echo _l('new_service_request'); ?>
                  </a>
              <?php } ?>
              <div class="clearfix"></div>
            </div>
            
            <hr class="hr-panel-heading" />
            
            <div class="clearfix"></div>
            
            <div class="row mbot15">
                <div class="col-md-12">
                    <h4 class="no-margin"><?php echo _l('als_services_requests'); ?> List</h4>
                </div>
            </div>

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
    initDataTable('.table-services_requests', admin_url + 'services/requests', [7], [7],'undefined',[0,'DESC']);
  });
</script>
</body>
</html>
