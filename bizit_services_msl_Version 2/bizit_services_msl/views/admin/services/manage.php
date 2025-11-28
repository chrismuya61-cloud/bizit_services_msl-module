<?php init_head(); ?>
<div id="wrapper">
  <div class="content">
    <div class="row">
      <div class="col-md-12">
        <?php if (has_permission(BIZIT_SERVICES_MSL, '', 'create')) { ?>
          <div class="panel_s">
            <div class="panel-body _buttons">
              <a href="#" class="btn btn-info pull-left" data-toggle="modal" data-target="#services_modal"><i class="fa fa-plus"></i> <?php echo _l('new_service'); ?></a>
              <a href="#" class="btn btn-info pull-left mleft5 new-service-btn" data-toggle="modal" data-target="#service_category_modal"><?php echo _l('service_type'); ?></a>
            </div>
          </div>
        <?php } ?>
        <div class="panel_s">
          <div class="panel-body">
            <div class="clearfix"></div>
            <?php render_datatable(array(
              _l('service_code'),
              _l('services_title'),
              _l('service_type'),
              _l('status'),
              _l('options'),
            ), 'services'); ?>
          </div>
        </div>
      </div>
    </div>
  </div>
</div>
<?php
/*Modals*/
$this->load->view('admin/services/modal_services');
$this->load->view('admin/services/modal_category');
/*Modals End*/
?>
<?php init_tail(); ?>
<script>
  $(function() {
    initDataTable('.table-services', window.location.href, [4], [4], 'undefined', [0, 'ASC']);
    initDataTable('.table-service_category', window.location.href + '/service_category', [3], [3], 'undefined', [0, 'ASC']);
  });
  $("[id=submit]").submit(function(e) {
    if (e.preventDefault()) {}

  });
  $("[id=reset_close]").click(function(event) {
    event.preventDefault()
    $("form").data("validator").resetForm();
  });
</script>
</body>

</html>