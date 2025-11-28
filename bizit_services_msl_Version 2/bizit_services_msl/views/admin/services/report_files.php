<div class="row">
  <div class="col-md-12">
    <?php echo form_open_multipart(admin_url('services/upload_file/field_report/'.$field_report_info->field_report_id.'/1'),array('class'=>'dropzone','id'=>'service-reports-files-upload')); ?>
    <input type="file" name="file" multiple />
    <?php echo form_close(); ?>
    <div class="text-right pull-right">
      <div id="dropbox-chooser" style="margin-top:-25px;"></div>
    </div>
    <div class="clearfix mtop25"></div>
    <?php render_datatable(array(
     _l('project_file_filename'), 
     _l('project_file__filetype'),
     _l('project_file_uploaded_by'),
     _l('project_file_dateadded'),
     _l('options')
   ),'services_report_files'); ?>

   <?php echo form_close(); ?>

   <script type="text/javascript"> 
    initDataTable('.table-services_report_files', admin_url + "services/manage_files/field_report/<?= $field_report_info->field_report_id; ?>", [4], [4],'undefined',[0,'ASC']);
    function delete_services_report_file(id){
     $.get(admin_url + "services/delete_file/"+id+"/field_report", function(response) {
      response = JSON.parse(response);
      if (response.success == true) {
        alert_float('success', response.message);
        $('.table-services_report_files').DataTable().ajax.reload();
      } else {
        alert_float('warning', response.message);
      }
    });
   }
 </script>
</div>
</div>
