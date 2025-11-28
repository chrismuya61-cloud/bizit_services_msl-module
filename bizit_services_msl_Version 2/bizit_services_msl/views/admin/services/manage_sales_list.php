<?php init_head(); ?>
<div id="wrapper">
  <div class="content">
    <div class="row">
      <div class="col-md-12">
        <div class="panel_s">
          <div class="panel-body">
            <h4>Services Sales List</h4>
            <hr/>
            <div class="clearfix"></div>
            <?php render_datatable(array(
              _l('service_code'), 
              _l('services_title'), 
              _l('service_type'), 
              _l('selling_price'),
              ),'services_sales_list'); ?>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>

<?php init_tail(); ?>
<script>
  $(function(){
    initDataTable('.table-services_sales_list', admin_url + 'services/sales_list', [], [],'undefined',[0,'ASC']);
  });
</script>
</body>
</html>
