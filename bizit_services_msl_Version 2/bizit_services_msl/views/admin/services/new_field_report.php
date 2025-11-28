<?php init_head(); ?>
<div id="wrapper">
  <div class="content">
    <div class="row">
      <div class="col-md-12">
        <?php if(!empty($field_report_info) and (has_permission(BIZIT_SERVICES_MSL.'_rental_agreement_field_report', '', 'create') or has_permission(BIZIT_SERVICES_MSL.'_rental_agreement_field_report', '', 'edit'))) { 
         echo form_open('admin/services/manage_field_report',array('id'=>'service_field_report_form', 'enctype'=>'multipart/form-data'));

         // echo form_open('admin/services/manage_field_report',array('id'=>'service_field_report_form'));
          echo form_hidden('field_report_id', $field_report_info->field_report_id); 
          echo form_hidden('report_code', $field_report_info->report_code); 
        ?>
        <div class="panel_s">
          <div class="panel-body _buttons">
            <a href="<?php echo admin_url('services/field_report/view/'. $field_report_info->report_code); ?>" class="btn btn-info "><i class="fa fa-eye"></i> View Report</a>
            <a class="btn btn-default pull-right" href="<?php echo admin_url('services/view_rental_agreement/'.$service_info->service_rental_agreement_code); ?>"><i class="fa fa-arrow-left"></i> Back</a>
          </div>
            <div class="panel-body mtop10">


            <div class="row">
              <div class="col-md-6">

                                <?php if($field_report_info->status == 0 or $field_report_info->status == 3 or $field_report_info->status == 2){ ?>

                                  <i class="fa fa-info-circle"></i> <small>Update your report status accordingly.</small>
                                  <div class="form-group">
                                          <div class="input-group">
                                            <span class="input-group-addon" style="height:36px;">Report Status</span>
                                              <select name="status" class="form-control selectpicker" data-live-search="true" data-width="90%" data-none-selected-text="Nothing selected" id="order_confirmation">
                                                 <?php if($field_report_info->status == 3) { ?>
                                                  <option value="3" <?php echo $field_report_info->status ==3? 'selected':''?> >Report Rejected</option>
                                                 <?php } ?>
                                                 <option value="4" <?php echo $field_report_info->status ==4? 'selected':''?> >Report Completed & Approved</option>
                                                 <option value="2" <?php echo $field_report_info->status ==2? 'selected':''?> >Request report approval</option>
                                                  <option value="1" <?php echo $field_report_info->status ==1? 'selected':''?> >Cancel report</option>
                                                  <option value="0" <?php echo $field_report_info->status ==0? 'selected':''?>>Incomplete report</option>
                                              </select>
                                            </div>
                                      </div>

                              <?php } else{ ?>
                              <div class="address mbot20"><b>Report Status: </b>
                               <?php echo $field_report_info->status == 1 ? '<span class="label label-danger">Report Cancelled</span>' : ($field_report_info->status == 2 ? '<span class="label label-primary">Awaiting Approval</span>' : ($field_report_info->status == 3 ? '<span class="label label-danger">Report Rejected</span>' : '<span class="label label-success">Report Completed & Approved</span>')); ?> 
                              </div>
                             <?php } ?>
              </div>
            </div>

              <ul class="nav nav-tabs" role="tablist">
                <li role="presentation" class="active">
                  <a href="#general_info" aria-controls="general" role="tab" data-toggle="tab"><?= _l('general_info'); ?></a>
                </li>
                <li role="presentation">
                  <a href="#survey_info" aria-controls="e-sign_approvals" role="tab" data-toggle="tab"><?= _l('survey_info'); ?></a>
                </li> 

                <li role="presentation">
                  <a href="#report_files" aria-controls="e-sign_approvals" role="tab" data-toggle="tab"><?=_l('media_files')?></a>
                </li>               
              </ul>
              <div class="tab-content mtop30">
                <div role="tabpanel" class="tab-pane active" id="general_info">
                  <div class="row">
                    <div class="col-md-12">
                      <div id="details" class="clearfix">
                          <div id="client" class="col-md-6">
                              <span class="bold">CUSTOMER INFO:</span>
                                  <address>
                                     <span class="bold"><a href="<?php echo admin_url('clients/client/'.$service_request_client->userid); ?>" target="_blank">
                                      <?php
                                      if($service_request_client->show_primary_contact == 1){
                                        $pc_id = get_primary_contact_user_id($service_info->clientid);
                                        if($pc_id){
                                          echo get_contact_full_name($pc_id) .'<br />';
                                        }
                                      }
                                      echo $service_request_client->company; ?></a></span><br>
                                      <?php echo $service_request_client->billing_street; ?><br>
                                      <?php
                                      if(!empty($service_request_client->billing_city)){
                                       echo $service_request_client->billing_city;
                                     }
                                     if(!empty($service_request_client->billing_state)){
                                       echo ', '.$service_request_client->billing_state;
                                     }
                                     $billing_country = get_country_short_name($service_request_client->billing_country);
                                     if(!empty($billing_country)){
                                       echo '<br />'.$billing_country;
                                     }
                                     if(!empty($service_request_client->billing_zip)){
                                       echo ', '.$service_request_client->billing_zip;
                                     }
                                     if(!empty($service_request_client->vat)){
                                       echo '<br /><b>'. _l('invoice_vat') .'</b>: '. $service_request_client->vat;
                                     }
                                     // Check for customer custom fields which is checked show on pdf
                                     $pdf_custom_fields = get_custom_fields('customers',array('show_on_pdf'=>1));
                                     if(count($pdf_custom_fields) > 0){
                                       echo '<br />';
                                       foreach($pdf_custom_fields as $field){
                                         $value = get_custom_field_value($service_info->clientid,$field['id'],'customers');
                                         if($value == ''){continue;}
                                         echo '<b>'.$field['name'] . '</b>: ' . $value . '<br />';
                                       }
                                     }
                                     ?>
                                  </address>

                              <div class="bold" style="margin-top:20px;">REPORT INFO:</div>
                              <div class="address"><b>Date Created:</b> <?= _d($field_report_info->dateadded); ?></div>                          
                               
                          </div>
                          <div id="invoice" class="col-md-6 text-right">
                              <h4 class="bold"><a><?php echo get_option('service_rental_agreement_prefix').$field_report_info->report_code ?></a></h4>
                              <div class="date"><b><?= _l('rental_agreement_code'); ?>:</b> <?php echo get_option('service_rental_agreement_prefix').$service_info->service_rental_agreement_code ?></div>
                              <div class="date"><b><?= _l('rental_start_date'); ?>:</b> <?php echo _d($service_info->start_date ) ?></div>
                              <div class="date"><b><?= _l('rental_end_date'); ?>:</b> <?php echo _d($service_info->end_date ) ?></div>
                              <div class="date"><b><?= _l('received_by'); ?>:</b> <?php echo get_staff_full_name($service_info->received_by) ?></div>
                              <div class="date"><b><?= _l('field_operator'); ?>:</b> <?php echo get_staff_full_name($service_info->field_operator) ?></div>
                          </div>
                      </div> 
                    </div>
                  </div>
                </div>                
                <div role="tabpanel" class="tab-pane" id="survey_info">
                  <div class="row">
                    <div class="col-md-6">
                      <div class="form-group">
                          <label class="control-label" for="name"><?php echo _l('site_name'); ?></label>
                          <input type="text" id="site_name" name="site_name" class="form-control" value="<?= (isset($field_report_info) && $field_report_info->site_name != null ? $field_report_info->site_name : '') ?>" placeholder="Nairobi-Westlands area, Nanyuki town, Mombasa-Kilindini Port etc".>
                      </div>
                      <div class="form-group">
                        <label><?= _l('survey_type'); ?></label>
                        <input class="form-control" type="text" name="survey_type" placeholder="e.g. Topographical survey" value="<?= (isset($field_report_info) && $field_report_info->survey_type != null ? $field_report_info->survey_type : '') ?>">
                      </div>
                      <div class="form-group">
                        <label><?= _l('survey_control_points_used'); ?></label>
                        <input class="form-control" type="text" name="control_points_used" placeholder="e.g. Averaged control points" value="<?= (isset($field_report_info) && $field_report_info->control_points_used != null ? $field_report_info->control_points_used : '') ?>">
                      </div>
                    </div>
                    <div class="col-md-6">
                      <div class="form-group">
                        <label><?= _l('survey_report'); ?></label>
                        <textarea class="form-control" name="survey_report" rows="3" placeholder="Enter <?= _l('survey_report'); ?> ..."><?php if(isset($field_report_info)) {echo $field_report_info->survey_report;} ?></textarea>
                      </div>
                      <div class="form-group">
                        <label><?= _l('equipment_report'); ?></label>
                        <textarea class="form-control" name="equipment_report" rows="3" placeholder="Enter <?= _l('equipment_report'); ?> ..."><?php if(isset($field_report_info)) {echo $field_report_info->equipment_report;} ?></textarea>
                      </div>
                      <div class="form-group">
                        <label><?= _l('general_comments'); ?></label>
                        <textarea class="form-control" name="comments" rows="3" placeholder="Enter comments ..."><?php if(isset($field_report_info)) {echo $field_report_info->comments;} ?></textarea>
                      </div>

                    </div>
                  </div>
                </div>                               
                <div role="tabpanel" class="tab-pane" id="report_files">
                    <!-- File Upload Field Below -->
                    <div class="panel-body mtop10">
                      <table class="table table-bordered table-striped" id="fileUploadFields">
                        <thead>
                          <tr>
                            <th class="col-sm-10">Upload File</th>
                            <th class="col-sm-2 text-center">
                              <a href="javascript:void(0);" class="addFile btn btn-info">
                                <i class="fa fa-plus"></i> Add File
                              </a>
                            </th>
                          </tr>
                        </thead>
                        <tbody>
                        <?php if (!empty($field_report_info->report_files)) { 
                            // Decode the JSON string to get an array of files
                            $uploaded_files = json_decode($field_report_info->report_files, true); 
                            if (is_array($uploaded_files) && count($uploaded_files) > 0) {
                                foreach ($uploaded_files as $index => $file) { ?>
                                    <tr>
                                        <td>
                                            <div class="form-group form-group-bottom">
                                                <label class="control-label" style="font-weight:500;"><?= _l('Uploaded File'); ?></label>
                                                <a href="<?= base_url('modules/bizit_services_msl/uploads/reports/' . $file); ?>" target="_blank" class="btn btn-default">
                                                    <i class="fa fa-file"></i> <?= htmlspecialchars($file); ?>
                                                </a>
                                                <!-- Hidden input to retain the file in case the user doesn't upload new files -->
                                                <input type="hidden" name="report_files[]" value="<?= htmlspecialchars($file); ?>">
                                            </div>
                                        </td>
                                        <td class="text-center">
                                            <!-- Button to remove uploaded file (implement functionality as needed) -->
                                            <a href="javascript:void(0);" class="btn btn-danger removeRow">
                                                <i class="fa fa-trash"></i>
                                            </a>
                                        </td>
                                    </tr>
                              <?php   }
                                  } 
                              } ?>

                                <!-- Add a new upload row -->
                                <tr>
                                    <td>
                                        <div class="form-group form-group-bottom">
                                            <input type="file" name="service_files[]" id="service_file_0" class="form-control">
                                        </div>
                                    </td>
                                    <td class="text-center">
                                        <a href="javascript:void(0);" class="btn btn-danger removeRow">
                                            <i class="fa fa-trash"></i>
                                        </a>
                                    </td>
                                </tr>
                            </tbody>
                          </table>
                        </div>
                    </div>
                  </div> 
          
                 <!-- Submission button -->
                <div class="btn-bottom-toolbar bottom-transaction text-right">
                    <button type="submit" class="btn-tr btn btn-info mleft10 text-right pull-right" id="submit">
                        <?php echo _l('submit'); ?>
                    </button>   
                </div> `           
            </div>     
        </div>

        <?php echo form_close(); } ?>

      </div>
    </div>
    <div class="btn-bottom-pusher"></div>
  </div>
</div>
<?php init_tail(); ?>
<script>

$(function(){
  $("#service_field_report_form").validate({
    rules: {
      report_file: {
        extension: "pdf|doc|docx|xls|xlsx|jpg|jpeg|png",
        filesize: 2048, // 2MB in kilobytes
      }
    },
    messages: {
      report_file: {
        extension: "Please upload a valid file (PDF, DOC, JPG, PNG, etc.).",
        filesize: "File size must be less than 2MB."
      }
    }
  });
});

let fileCount = 0;

// Add new file upload row
document.querySelector('.addFile').addEventListener('click', function () {
    fileCount++;
    let newRow = `
      <tr>
        <td>
          <div class="form-group form-group-bottom">
            <input type="file" name="service_files[]" id="service_file_${fileCount}" class="form-control">
          </div>
        </td>
        <td class="text-center">
          <a href="javascript:void(0);" class="btn btn-danger removeRow">
            <i class="fa fa-trash"></i>
          </a>
        </td>
      </tr>`;
    document.querySelector('#fileUploadFields tbody').insertAdjacentHTML('beforeend', newRow);
});

// Remove row for file upload
document.querySelector('#fileUploadFields').addEventListener('click', function (event) {
    if (event.target.closest('.removeRow')) {
        event.target.closest('tr').remove();
    }
});

$(function(){
  $("#service_calibration_report_form").validate();
//initDataTable('.table-services_requests', admin_url + 'services/requests', [6], [6],'undefined',[0,'ASC']);
//initDataTable('.table-service_category', admin_url + 'services/service_category', [3], [3],'undefined',[0,'ASC']);
});
/*
$("[id=submit]").submit(function(e) {
if (e.preventDefault()) {
}
});
$("[id=reset_close]").click(function(event) {
event.preventDefault()
$("form").data("validator").resetForm();
});*/
</script>
</body>
</html>