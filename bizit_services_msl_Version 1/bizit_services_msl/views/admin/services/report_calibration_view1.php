<?php init_head(); ?>
<div id="wrapper">
	 <div class="content">
	    <div class="row">
	      <div class="col-md-12">	      	
	        <div class="panel_s">
	          <div class="panel-body _buttons">
                <?php if($service_info->status == 0):?>
                    <a href="<?php echo base_url() ?>admin/services/report/edit/<?php echo $service_request_code ?>" class="btn btn-info "><i class="glyphicon glyphicon-edit"></i> Edit</a>
                <?php endif; ?>
                    <a href="<?php echo base_url() ?>admin/services/report/pdf/<?php echo $service_request_code ?>" class="btn btn-warning "><i class="fa fa-download"></i> Report PDF</a>

             <a class="btn btn-default pull-right" href="<?php echo admin_url('services/requests'); ?>"><i class="fa fa-arrow-left"></i> Back</a>
	          </div>
	      </div>
	      	<div class="panel_s">
	      		<div class="panel-body">
                      <div id="details" class="clearfix">
                        <div class="col-md-12">
                          <h4>Service Request Report</h4>
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

                              <div class="bold" style="margin-top:20px;">INSTRUMENT INFO:</div>                            
                              <div class="address"><b>Type:</b> <?php echo $service_info->item_type ?></div>
                              <div class="address"><b>Make:</b> <?php echo $service_info->item_make ?></div>
                              <div class="address"><b>Model:</b> <?php echo $service_info->item_model ?></div>
                              <div class="email"><b>Serial:</b> <?php echo $service_info->serial_no ?></div>
                          </div>
                          <div id="invoice" class="col-md-6 text-right">
                              <h4 class="bold"><a><?php echo get_option('service_request_prefix').$service_info->service_request_code ?></a></h4>
                              <div class="date"><b>Drop Off Date:</b> <?php echo _d($service_info->drop_off_date ) ?></div>
                              <div class="date"><b>Collection Date:</b> <?php echo _d($service_info->collection_date ) ?></div>
                              <div class="date"><b>Sales Person:</b> <?php echo get_staff_full_name($service_info->received_by) ?></div>
                          </div>
                      </div> 

                  <div class="clearfix mtop30"></div>
                    <style type="text/css">table td.total {} table thead th{font-weight: bold;}</style>
                    <?php if($service_info->item_type == 'Level'){ ?>
                    <table class="table table-bordered table-striped dataTable">
                        <thead>
                        <tr>
                            <th class="desc" style="text-align:center; font-weight:500;">SETUP</th>
                            <th class="total" style="text-align:center; font-weight:500;">BACKSIGHT A</th>
                            <th class="desc" style="text-align:center; font-weight:500;">FORESIGHT B</th>
                            <th class="total" style="text-align:center; font-weight:500;">DIFF (A-B)</th>
                            <th class="desc" style="text-align:center; font-weight:500;">ERROR</th>
                            <th class="total" style="text-align:center; font-weight:500;">CORRECTED<br> FORESIGHT B</th>
                        </tr>
                        </thead>
                        <tbody>
                            <?php 
                                $i_backsight_a = $calibration_info->i_backsight_a;
                                $i_foresight_b = $calibration_info->i_foresight_b;
                                $ii_backsight_a = $calibration_info->ii_backsight_a;
                                $ii_foresight_b = $calibration_info->ii_foresight_b;
                                $iii_backsight_a = $calibration_info->iii_backsight_a;
                                $iii_foresight_b = $calibration_info->iii_foresight_b;
                                $iv_backsight_a = $calibration_info->iv_backsight_a;
                                $iv_foresight_b = $calibration_info->iv_foresight_b;
                                $diff_i = $i_backsight_a-$i_foresight_b;
                                $diff_ii = $ii_backsight_a-$ii_foresight_b;
                                $diff_iii = $iii_backsight_a-$iii_foresight_b;
                                $diff_iv = $iv_backsight_a-$iv_foresight_b;
                                $error_ii = round($diff_i - $diff_ii, 3);
                                $error_iv = round($diff_iii - $diff_iv, 3);
                            ?>
                            <tr>
                                <td class="desc" style="text-align:center; font-weight:500;">I</td>
                                <td class="total" style="text-align:center;"><?php echo $i_backsight_a;?></td>
                                <td class="desc" style="text-align:center;"><?php echo $i_foresight_b; ?></td>
                                <td class="total" style="text-align:center;"><?php echo $diff_i; ?></td>
                                <td class="desc" style="text-align:center;"></td>
                                <td class="total" style="text-align:center;"></td>
                            </tr>
                            <tr>
                                <td class="desc" style="text-align:center; font-weight:500;">II</td>
                                <td class="total" style="text-align:center;"><?php echo $ii_backsight_a; ?></td>
                                <td class="desc" style="text-align:center;"><?php echo $ii_foresight_b; ?></td>
                                <td class="total" style="text-align:center;"><?php echo $diff_ii; ?></td>
                                <td class="desc" style="text-align:center;"><?php echo $error_ii; ?></td>
                                <td class="total" style="text-align:center;"><?php echo $ii_foresight_b + ($error_ii-$error_iv); ?></td>
                            </tr>
                            <tr>
                                <td class="desc" style="text-align:center; font-weight:500;">III</td>
                                <td class="total" style="text-align:center;"><?php echo $iii_backsight_a; ?></td>
                                <td class="desc" style="text-align:center;"><?php echo $iii_foresight_b; ?></td>
                                <td class="total" style="text-align:center;"><?php echo $diff_iii; ?></td>
                                <td class="desc" style="text-align:center;"></td>
                                <td class="total" style="text-align:center;"></td>
                            </tr>
                            <tr>
                                <td class="desc" style="text-align:center; font-weight:500;">IV</td>
                                <td class="total" style="text-align:center;"><?php echo $iv_backsight_a; ?></td>
                                <td class="desc" style="text-align:center;"><?php echo $iv_foresight_b; ?></td>
                                <td class="total" style="text-align:center;"><?php echo $diff_iv; ?></td>
                                <td class="desc" style="text-align:center;"><?php echo $error_iv; ?></td>
                                <td class="total" style="text-align:center;"><?php echo $iv_foresight_b + ($error_ii-$error_iv); ?></td>
                            </tr>

                        </tbody>
                        <tfoot>

                        </tfoot>
                    </table>
                   <?php } else if($service_info->item_type == 'Total Station' or $service_info->item_type == 'Theodolite'){ ?>
                    <table class="table table-bordered table-striped dataTable">
                          <thead>
                              <tr>
                                  <th></th>
                                  <th colspan="2" style="text-align:center; font-weight:500;" class="total">HORIZONTAL CIRCLE INDEX ERROR</th>
                                  <th colspan="2" style="text-align:center; font-weight:500;">VERTICAL CIRCLE INDEX ERROR</th>
                              </tr>
                          <tr>
                              <th class="desc" style="text-align:center; font-weight:500;">FACE</th>
                              <th class="total" style="text-align:center; font-weight:500;">START A</th>
                              <th  class="total" style="text-align:center; font-weight:500;">END B</th>
                              <th class="desc" style="text-align:center; font-weight:500;">START A</th>
                              <th class="desc" style="text-align:center; font-weight:500;">END B</th>
                          </tr>
                          </thead>
                          <tbody>
                              <?php
                                  $i_h_a = $calibration_info->i_h_a;
                                  $ii_h_a = $calibration_info->ii_h_a;
                                  $sum_h_a = $i_h_a+$ii_h_a;
                                  $d_error_h_a = (dms2dec(180,0,0)-$sum_h_a);

                                  $i_h_b = $calibration_info->i_h_b;
                                  $ii_h_b = $calibration_info->ii_h_b;                                
                                  $sum_h_b = $i_h_b+$ii_h_b;
                                  $d_error_h_b = (dms2dec(180,0,0)-$sum_h_b);

                                  $i_v_a = $calibration_info->i_v_a;
                                  $ii_v_a = $calibration_info->ii_v_a;                                
                                  $sum_v_a = $i_v_a+$ii_v_a;
                                  $d_error_v_a = (dms2dec(360,0,0)-$sum_v_a);

                                  $i_v_b = $calibration_info->i_v_b;
                                  $ii_v_b = $calibration_info->ii_v_b;
                                  $sum_v_b = $i_v_b+$ii_v_b;
                                  $d_error_v_b = (dms2dec(360,0,0)-$sum_v_b);

                                  $i_edm_a_1 = number_format(round($calibration_info->i_edm_a_1, 3), 3);
                                  $i_edm_a_2 = number_format(round($calibration_info->i_edm_a_2, 3), 3);
                                  $i_edm_a_3 = number_format(round($calibration_info->i_edm_a_3, 3), 3);
                                  $f_i_edm_a = ($i_edm_a_1 + $i_edm_a_2 + $i_edm_a_3)/3;

                                  $i_edm_b_1 = number_format(round($calibration_info->i_edm_b_1, 3), 3);
                                  $i_edm_b_2 = number_format(round($calibration_info->i_edm_b_2, 3), 3);
                                  $i_edm_b_3 = number_format(round($calibration_info->i_edm_b_3, 3), 3);
                                  $f_i_edm_b = ($i_edm_b_1 + $i_edm_b_2 + $i_edm_b_3)/3;

                                  $ii_edm_a_1 = number_format(round($calibration_info->ii_edm_a_1, 3), 3);
                                  $ii_edm_a_2 = number_format(round($calibration_info->ii_edm_a_2, 3), 3);
                                  $ii_edm_a_3 = number_format(round($calibration_info->ii_edm_a_3, 3), 3);
                                  $f_ii_edm_a = ($ii_edm_a_1 + $ii_edm_a_2 + $ii_edm_a_3)/3;

                                  $ii_edm_b_1 = number_format(round($calibration_info->ii_edm_b_1, 3), 3);
                                  $ii_edm_b_2 = number_format(round($calibration_info->ii_edm_b_2, 3), 3);
                                  $ii_edm_b_3 = number_format(round($calibration_info->ii_edm_b_3, 3), 3);
                                  $f_ii_edm_b = ($ii_edm_b_1 + $ii_edm_b_2 + $ii_edm_b_3)/3;

                                  $f_i_ii_edm_a = ($f_i_edm_a + $f_ii_edm_a)/2;
                                  $f_i_ii_edm_b = ($f_i_edm_b + $f_ii_edm_b)/2;
                               ?>
                              <tr>
                                  <td class="desc" style="text-align:center; font-weight:500;"><b>I</b></td>
                                  <td class="total" style="text-align:center;"><?php echo dec2dms_full($i_h_a); ?></td>
                                  <td  class="total" style="text-align:center;"><?php echo dec2dms_full($i_h_b); ?></td>
                                  <td class="desc" style="text-align:center;"><?php echo dec2dms_full($i_v_a); ?></td>
                                  <td class="desc" style="text-align:center;"><?php echo dec2dms_full($i_v_b); ?></td>
                              </tr>
                              <tr>
                                  <td class="desc" style="text-align:center; font-weight:500;"><b>II</b></td>
                                  <td class="total" style="text-align:center;"><?php echo dec2dms_full($ii_h_a); ?></td>
                                  <td  class="total" style="text-align:center;"><?php echo dec2dms_full($ii_h_b); ?></td>
                                  <td class="desc" style="text-align:center;"><?php echo dec2dms_full($ii_v_a); ?></td>
                                  <td class="desc" style="text-align:center;"><?php echo dec2dms_full($ii_v_b); ?></td>
                              </tr>
                              <tr>
                                  <td></td>
                                  <td colspan="2" class="text-center total"><b>Half Circle is 180&deg; 00' 00"</b></td>
                                  <td colspan="2" class="text-center"><b>Half Circle is 360&deg; 00' 00"</b></td>
                              </tr>
                              <tr>
                                  <td class="desc" style="text-align:center; font-weight:500;"><b>SUM(I+II)</b></td>
                                  <td class="total" style="text-align:center;"><?php echo dec2dms_full($sum_h_a); ?></td>
                                  <td  class="total" style="text-align:center;"><?php echo dec2dms_full($sum_h_b); ?></td>
                                  <td class="desc" style="text-align:center;"><?php echo dec2dms_full($sum_v_a); ?></td>
                                  <td class="desc" style="text-align:center;"><?php echo dec2dms_full($sum_v_b); ?></td>
                              </tr>
                              <tr>
                                  <td></td>
                                  <td colspan="2" class="text-center total"><b>(180&deg; 00' 00" - SUM(A+B))/2</b></td>
                                  <td colspan="2" class="text-center"><b>(360&deg; 00' 00" - SUM(A+B))/2</b></td>
                              </tr>
                              <tr>
                                  <td class="desc" style="text-align:center; font-weight:500;"><b>DOUBLE ERROR</b></td>
                                  <td class="total" style="text-align:center;"><?php echo dec2dms_full($d_error_h_a); ?></td>
                                  <td  class="total" style="text-align:center;"><?php echo dec2dms_full($d_error_h_b); ?></td>
                                  <td class="desc" style="text-align:center;"><?php echo dec2dms_full($d_error_v_a); ?></td>
                                  <td class="desc" style="text-align:center;"><?php echo dec2dms_full($d_error_v_b); ?></td>
                              </tr>
                          </tbody>
                    </table>
                    <div class="clearfix mtop30"></div>
                    <table class="table table-bordered table-striped dataTable">
                          <thead>
                              <tr>
                                  <th colspan="3" style="text-align:center; font-weight:500;">EDM CHECKS</th>
                              </tr>
                          <tr>
                              <th class="desc" style="text-align:center; font-weight:500;">FACE</th>
                              <th class="total" style="text-align:center; font-weight:500;">START A</th>
                              <th class="desc" style="text-align:center; font-weight:500;">END B</th>
                          </tr>
                          </thead>
                          <tbody>
                              <tr>
                                  <td class="desc" style="text-align:center; font-weight:500;" rowspan='3'><b>I</b></td>
                                  <td class="total" style="text-align:center;"><?php echo $i_edm_a_1; ?></td>
                                  <td class="desc" style="text-align:center;"><?php echo $i_edm_b_1; ?></td>
                              </tr>
                              <tr>
                                  <td class="total" style="text-align:center;"><?php echo $i_edm_a_2; ?></td>
                                  <td class="desc" style="text-align:center;"><?php echo $i_edm_b_2; ?></td>
                              </tr>
                              <tr>
                                  <td class="total" style="text-align:center;"><?php echo $i_edm_a_3; ?></td>
                                  <td class="desc" style="text-align:center;"><?php echo $i_edm_b_3; ?></td>
                              </tr>
                              <tr>
                                  <td class="desc" style="text-align:center; font-weight:500;" rowspan='3'><b>II</b></td>
                                  <td class="total" style="text-align:center;"><?php echo $ii_edm_a_1; ?></td>
                                  <td class="desc" style="text-align:center;"><?php echo $ii_edm_b_1; ?></td>
                              </tr>
                              <tr>
                                  <td class="total" style="text-align:center;"><?php echo $ii_edm_a_2; ?></td>
                                  <td class="desc" style="text-align:center;"><?php echo $ii_edm_b_2; ?></td>
                              </tr>
                              <tr>
                                  <td class="total" style="text-align:center;"><?php echo $ii_edm_a_3; ?></td>
                                  <td class="desc" style="text-align:center;"><?php echo $ii_edm_b_3; ?></td>
                              </tr>
                              <tr>
                                  <td class="desc" style="text-align:center; font-weight:500;"><b>EDM FACE I AVERAGE</b></td>
                                  <td class="total" style="text-align:center;"><?php echo round($f_i_edm_a,2); ?></td>
                                  <td class="desc" style="text-align:center;"><?php echo round($f_i_edm_b,2); ?></td>
                              </tr>
                              <tr>
                                  <td class="desc" style="text-align:center; font-weight:500;"><b>EDM FACE II AVERAGE</b></td>
                                  <td class="total" style="text-align:center;"><?php echo round($f_ii_edm_a,2); ?></td>
                                  <td class="desc" style="text-align:center;"><?php echo round($f_ii_edm_b,2); ?></td>
                              </tr>
                              <tr>
                                  <td class="desc" style="text-align:center; font-weight:500;"><b>EDM FACE I&II MEAN</b></td>
                                  <td class="total" style="text-align:center;"><?php echo round($f_i_ii_edm_a,2); ?></td>
                                  <td class="desc" style="text-align:center;"><?php echo round($f_i_ii_edm_b,2); ?></td>
                              </tr>
                          </tbody>
                    </table>
                   <?php } ?>	 
                    
                    <div class="clearfix mtop30"></div>
                    <hr>
                    <div class="row">
                        <div class="col-md-12">
                            <label class="control-label" style="font-weight:500;">REPORT REMARKS</label>
                            <p class="description"><?php if(!empty($calibration_info)) {echo $calibration_info->calibration_remark;} ?></p>
                        </div>
                    </div>     			
	      		</div>
	      	</div>
	      </div>
	    </div>
	</div>
</div>
<?php init_tail(); ?>
</body>
</html>