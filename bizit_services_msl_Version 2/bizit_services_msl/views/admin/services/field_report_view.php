<?php init_head(); ?>
<div id="wrapper">
	 <div class="content">
	    <div class="row">
	      <div class="col-md-12">	      	
	        <div class="panel_s">
	          <div class="panel-body _buttons">
                <?php if((is_admin() or get_staff_user_id() == $field_report_info->added_by or has_permission(BIZIT_SERVICES_MSL.'_rental_agreement_field_report','','edit') ) and $field_report_info->status  <= 3 and $field_report_info->status  != 1):?>
                    <a href="<?php echo admin_url('services/field_report/edit/'. $field_report_info->report_code); ?>" class="btn btn-info "><i class="glyphicon glyphicon-edit"></i> Edit</a>
                <?php elseif($field_report_info->status == 4): ?>
                    <a href="<?php echo admin_url('services/field_report/pdf/'. $field_report_info->report_code); ?>" class="btn btn-warning "><i class="fa fa-download"></i> Report PDF</a>
                <?php endif; ?>

             <a class="btn btn-default pull-right" href="<?php echo admin_url('services/view_rental_agreement/'.$service_info->service_rental_agreement_code); ?>"><i class="fa fa-arrow-left"></i> Back</a>
	          </div>
	      </div>
	      	<div class="panel_s">
	      		<div class="panel-body">
                      <div id="details" class="clearfix">
                        <div class="col-md-12">
                          <h4>Service Field Report 
                            <div class="address pull-right"><b style="font-size: 14px;">Report Status: </b>
                               <?php echo  $field_report_info->status == 0 ? '<span class="label label-warning">Incomplete Report</span>' : ($field_report_info->status == 1 ? '<span class="label label-danger">Report Cancelled</span>' : ($field_report_info->status == 2 ? '<span class="label label-primary">Awaiting Approval</span>' : ($field_report_info->status == 3 ? '<span class="label label-danger">Report Rejected</span>' : '<span class="label label-success">Report Completed & Approved</span>'))); ?> 
                              </div>
                           </h4>
                          <hr class="hr-panel-heading" />
                        </div>
                          <div id="client" class="col-md-6">
                              <span class="bold">CUSTOMER BILLING INFO:</span>
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
                          </div>
                          <div id="invoice" class="col-md-6 text-right">
                              <h4 class="bold"><a><?php echo get_option('service_rental_agreement_prefix').$field_report_info->report_code ?></a></h4>
                              <div class="date"><b><?= _l('rental_agreement_code'); ?>:</b> <?php echo get_option('service_rental_agreement_prefix').$service_info->service_rental_agreement_code ?></div>
                              <div class="date"><b><?= _l('rental_start_date'); ?>:</b> <?php echo _d($service_info->start_date ) ?></div>
                              <div class="date"><b><?= _l('rental_end_date'); ?>:</b> <?php echo _d($service_info->end_date ) ?></div>
                              <div class="date"><b><?= _l('received_by'); ?>:</b> <?php echo get_staff_full_name($service_info->received_by) ?></div>
                              <div class="date"><b><?= _l('field_operator'); ?>:</b> <?php echo get_staff_full_name($service_info->field_operator) ?></div>
                              <div class="date"><b>Date Created:</b> <?= _d($field_report_info->dateadded); ?></div> 
                          </div>
                      </div> 

                  <div class="clearfix mtop30"></div>
                    <style type="text/css">table td.total {} table thead th{font-weight: bold;}</style>
                    <div class="clearfix mtop30"></div>
                    <table class="table table-bordered table-striped dataTable">
                          <thead>
                              <tr>
                                  <th colspan="4" style="text-align:center; font-weight:500;">SITE INFORMATION</th>
                              </tr>

                          </thead>
                          <tbody>
                          <tr>
                              <td class="desc" style="text-align:center; font-weight:500;"><?= _l('site_name'); ?></td>
                              <td class="" style="text-align:center;"><?= $field_report_info->site_name; ?></td>
                              <td class="desc" style="text-align:center; font-weight:500;"><?= _l('survey_type'); ?></td>
                              <td class="" style="text-align:center;"><?= $field_report_info->survey_type; ?></td>
                            </tr>

                          <tr>
                              <td class="desc" style="text-align:center; font-weight:500;"><?= _l('survey_control_points_used'); ?></td>
                              <td class="" style="text-align:center;"><?= $field_report_info->control_points_used; ?></td>
                              <td class="desc" style="text-align:center; font-weight:500;">Equipment Used</td>
                              <td class="" style="text-align:center;">-</td>
                            </tr>
                          </tbody>                              
                    </table> 

                    <div class="clearfix mtop30"></div>
                    <table class="table table-bordered table-striped dataTable">
                          <thead>
                              <tr>
                                  <th  style="text-align:center; font-weight:500;">REMARKS</th>
                              </tr>

                          </thead>
                          <tbody>
                          <tr>
                              <td class="desc">
                                <label class="control-label" style="font-weight:500;"><?= _l('survey_report'); ?></label>
                                <p class="description"><?= $field_report_info->survey_report; ?></p>
                              </td>
                            </tr>
                          <tr>
                              <td class="desc">
                                <label class="control-label" style="font-weight:500;"><?= _l('equipment_report'); ?></label>
                                <p class="description"><?= $field_report_info->equipment_report; ?></p>
                              </td>
                            </tr>                          
                          <tr>
                              <td class="desc">
                                <label class="control-label" style="font-weight:500;"><?= _l('general_comments'); ?></label>
                                <p class="description"><?= $field_report_info->comments; ?></p>
                              </td>
                            </tr>

                                <!-- Add file display here -->
                                  <?php if (!empty($field_report_info->report_files)) { 
                                      // Decode the JSON string to get an array of files
                                      $uploaded_files = json_decode($field_report_info->report_files, true); 
                                  ?>
                                      <tr>
                                          <td class="desc">
                                              <label class="control-label" style="font-weight:500;"><?= _l('Uploaded File(s)'); ?></label>
                                              <?php if (is_array($uploaded_files) && count($uploaded_files) > 0) { ?>
                                                  <ul>
                                                      <?php foreach ($uploaded_files as $file) { ?>
                                                          <li>
                                                              <a href="<?= base_url('modules/bizit_services_msl/uploads/reports/' . $file); ?>" target="_blank" class="btn btn-default">
                                                                  <i class="fa fa-file"></i> <?= htmlspecialchars($file); ?>
                                                              </a>
                                                          </li>
                                                      <?php } ?>
                                                  </ul>
                                              <?php } else { ?>
                                                  <p>No files available.</p>
                                              <?php } ?>
                                          </td>
                                      </tr>
                                  <?php } ?>
                                </tbody>                              
                          </table> 
                    
                    <div class="clearfix mtop30"></div>
                    <hr>
                    <div class="row">
                        <div class="col-md-12">
                          
                          <?php if ($field_report_info->approved_by != null) { ?>
                            <?php if($field_report_info->approval_remarks != null){?>
                              <label class="control-label" style="font-weight:500;">Approval Remarks</label>
                              <p class="description"><?= $field_report_info->approval_remarks; ?></p>
                            <?php } ?>
                            <?= pdf_signatures($field_report_info->approved_by); ?>                    
                          <?php } ?>

                          <?php if ($field_report_info->rejected_by != null) { ?>
                            <?php if($field_report_info->rejection_remarks != null){?>
                              <label class="control-label text-danger" style="font-weight:500;">Rejection Remarks</label>
                              <p class="description"><?= $field_report_info->rejection_remarks; ?></p>
                            <?php } ?>
                            <?= pdf_signatures($field_report_info->rejected_by); ?>                    
                          <?php } ?>
                         
                         <?php if ($field_report_info->approved_by == null and $field_report_info->rejected_by == null and $report_approval) { ?>
                          <?php 
                              echo form_open('admin/services/manage_field_report_appr_rej',array('id'=>'aprv_rej_form'));
                              echo form_hidden('field_report_id', $field_report_info->field_report_id); 
                              echo form_hidden('report_code', $field_report_info->report_code); 
                              echo form_hidden('added_by', $field_report_info->added_by); 
                              echo form_hidden('site_name', $field_report_info->site_name); 
                          ?>
                             <div id="aprv_rej" class="form-group" data-toggle="tooltip" title="Approve or Reject Report">
                                <label>Approve or Reject Report</label><br>
                                <i class="fa fa-info-circle"></i> <small>Kindly select one option below and add a remark for accountability.</small>
                                <div class="clearfix mtop10"></div>
                               <div class="radio radio-primary radio-inline">
                                 <input type="radio" name="aprv_rej" id="approve" value="1">
                                 <label>Approve Report</label>
                               </div>
                               <div class="radio radio-primary radio-inline">
                                 <input type="radio" name="aprv_rej" id="reject" value="0">
                                 <label>Reject Report</label>
                               </div>
                             </div>
                             <div id="remark_area" class="form-group hide">
                                 <label class="remark_label"></label>
                                 <textarea class="remark_textarea form-control" name="aprv_rej_remark"></textarea>
                                 <div class="clearfix mbot20"></div>
                                 <button class="btn-tr btn btn-info text-right" id="submit">
                                  <?php echo _l('submit'); ?> 
                                  </button> 
                             </div> 
                             <?php echo form_close(); ?>
                         <?php } ?>

                         <div class="clearfix mbot30"></div>
                         
                        </div>
                    </div>     			
	      		</div>
	      	</div>
	      </div>
	    </div>
	</div>
</div>
<?php init_tail(); ?>
<script type="text/javascript">
  $('#aprv_rej input[type="radio"]').on("change", function(){
    var aprv_rej = $(this).val();
    $('#remark_area').removeClass('hide');
    if(aprv_rej === "1"){
      $('#remark_area .remark_label').text('Approval Remark');
    }else if(aprv_rej === "0"){
      $('#remark_area .remark_label').text('Rejection Remark');
    }
  });

  <?php if($report_approval) { ?>
   $(window).load(function() {
    $("html, body").animate({ scrollTop: $(document).height() }, 1000);
  });
  <?php } ?>
</script>
</body>
</html>