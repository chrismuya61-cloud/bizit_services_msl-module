<?php init_head(); ?>

<div id="wrapper">
  <div class="content">
    <div class="row">
      <div class="col-md-12">
        <?php if (has_permission(BIZIT_SERVICES_MSL . '_rental_agreement', '', 'create')) { ?>
          <div class="panel_s">
            <div class="panel-body _buttons">

              <?php
              // Initialize variables
              $cat_check = []; // Array to hold service type IDs
              $check_report_gen = null; // Variable for report generation check
              $rentalAgreementId = null; // Initialize rentalAgreementId to null

              // Populate $cat_check with service type IDs
              foreach ($service_details as $v_details) {
                if ($v_details->service_rental_agreement_id == $service_info->service_rental_agreement_id) {
                  $cat_check[] = $v_details->service_typeid; // Add the service type ID to the array
                }
              }

              //echo json_encode($cat_check); // Output the array for debugging


              // Check if the invoice status is set and equals 2
              if (isset($invoice_status->status) && $invoice_status->status == 2) {
                $rentalAgreementId = $service_info->service_rental_agreement_id; // Set the variable
                echo "<script>var rentalAgreementId = " . json_encode($rentalAgreementId) . ";</script>";
              } else {
                echo "<script>var rentalAgreementId = null;</script>"; // Set to null if condition is not met
              }
              ?>


              <?php
              if ($service_info->status != 1) {
                if (in_array('1', $cat_check)) {
                  if ($service_info->status == 2) {
                    $check_report_gen = check_report($service_info->service_rental_agreement_id, 'field_report');
                    if (empty($check_report_gen)) { ?>
                      <div class="btn-group" role="group">
                        <a href="<?php echo base_url(); ?>admin/services/field_report/1/<?php echo $service_info->service_rental_agreement_code; ?>" class="btn btn-primary">
                          <i class="fa fa-pencil"></i> New Field Report
                        </a>
                      </div>
                    <?php } else { ?>
                      <div class="btn-group" role="group">
                        <a href="<?php echo base_url(); ?>admin/services/field_report/view/<?php echo $check_report_gen->report_code; ?>" class="btn btn-primary">
                          <i class="fa fa-eye"></i> View Field Report
                        </a>
                      </div>
                    <?php } ?>
                  <?php } ?>
                <?php } ?>

                <div class="btn-group" role="group">
                  <a href="<?php echo admin_url('services/rental_agreement_pdf/' . $service_info->service_rental_agreement_code); ?>" class="btn btn-info">
                    <i class="fa fa-download"></i> Rental Agreement Form
                  </a>
                </div>

                <?php if ($service_info->status == 2) { ?>
                  <div class="btn-group hide" role="group">
                    <a onclick="print_receipt(<?php echo $service_info->service_rental_agreement_code; ?>,'rental_agreement')" class="btn btn-default">
                      <i class="fa fa-print"></i> Print Receipt
                    </a>
                    <a href="<?php echo admin_url('services/pdf_rental_agreement_receipt/' . $service_info->service_rental_agreement_code); ?>" class="btn btn-danger" data-toggle='tooltip' data-placement='top' title='Download Receipt'>
                      <i class="fa fa-download"></i>
                    </a>
                  </div>
                <?php } ?>

                <?php if ($service_info->status == 0 && (empty($service_info->invoice_rel_id) || $service_info->invoice_rel_id == 0) && has_permission(BIZIT_SERVICES_MSL . '_rental_agreement', '', 'edit')) { ?>
                  <div class="btn-group" role="group">
                    <a href="<?php echo admin_url('services/rental_agreement_invoice_generation/' . $service_info->service_rental_agreement_code); ?>" class="btn btn-warning">
                      <i class="fa fa-edit"></i> Raise Invoice
                    </a>
                  </div>
                <?php } ?>

                <?php if (!empty($service_info->invoice_rel_id) && $service_info->invoice_rel_id > 0 && has_permission(BIZIT_SERVICES_MSL . '_rental_agreement', '', 'view')) { ?>
                  <div class="btn-group" role="group">
                    <a target="_blank" href="<?php echo admin_url('invoices/list_invoices#' . $service_info->invoice_rel_id); ?>" class="btn btn-success">
                      <i class="fa fa-eye"></i> View Invoice
                    </a>
                  </div>

                  <?php if ($service_info->status != 2 && empty($service_info->actual_date_returned) && has_permission(BIZIT_SERVICES_MSL . '_rental_agreement', '', 'edit')) { ?>
                    <div class="btn-group" role="group">
                      <button class="btn btn-default" data-toggle="modal" data-target="#return_rental_modal">
                        <i class="fa fa-rotate-left"></i> Receive Hired Equipments
                      </button>
                    </div>
                  <?php } ?>

                  <?php if (!empty($service_info->actual_date_returned) && !empty($service_info->field_operator) && $service_info->field_operator == get_staff_user_id() && has_permission(BIZIT_SERVICES_MSL . '_rental_agreement_field_report', '', 'create')) { ?>
                    <div class="btn-group" role="group">
                      <a href="#" data-toggle="modal" data-target="#service_field_report_modal" class="btn btn-primary">
                        <i class="fa fa-pencil"></i> Field Report Entry
                      </a>
                    </div>
                  <?php } ?>
                <?php } ?>
              <?php } ?>

              <a class="btn btn-default pull-right" href="<?php echo admin_url('services/rental_agreements'); ?>">
                <i class="fa fa-arrow-left"></i> Back
              </a>

            </div>
          </div>
        <?php } ?>
        <div class="panel_s">
          <div class="panel-body mtop10">
            <div class="ribbon project-status-ribbon-2" id="ribbon_project_1">
              <?php if ($service_info->status != 2 and !empty($service_info->invoice_rel_id) and $service_info->invoice_rel_id > 0) { ?>
                <span style="background:#f40303">Invoiced</span>
              <?php } ?>

              <?php if ($service_info->status == 2) { ?>
                <span style="background:#0eb114">Fully Paid</span>
              <?php } ?>

            </div>
            <div id="details" class="clearfix">
              <div class="col-md-12">
                <h4>Service Rental Agreement View</h4>
                <hr class="hr-panel-heading" />
              </div>
              <div id="client" class="col-md-6">
                <span class="bold">CUSTOMER BILLING INFO:</span>
                <address>
                  <span class="bold"><a href="<?php echo admin_url('clients/client/' . $service_rental_agreement_client->userid); ?>" target="_blank">
                      <?php
                      if ($service_rental_agreement_client->show_primary_contact == 1) {
                        $pc_id = get_primary_contact_user_id($service_info->clientid);
                        if ($pc_id) {
                          echo get_contact_full_name($pc_id) . '<br />';
                        }
                      }
                      echo $service_rental_agreement_client->company; ?></a></span><br>
                  <?php echo $service_rental_agreement_client->billing_street; ?><br>
                  <?php
                  if (!empty($service_rental_agreement_client->billing_city)) {
                    echo $service_rental_agreement_client->billing_city;
                  }
                  if (!empty($service_rental_agreement_client->billing_state)) {
                    echo ', ' . $service_rental_agreement_client->billing_state;
                  }
                  $billing_country = get_country_short_name($service_rental_agreement_client->billing_country);
                  if (!empty($billing_country)) {
                    echo '<br />' . $billing_country;
                  }
                  if (!empty($service_rental_agreement_client->billing_zip)) {
                    echo ', ' . $service_rental_agreement_client->billing_zip;
                  }
                  if (!empty($service_rental_agreement_client->vat)) {
                    echo '<br /><b>' . _l('invoice_vat') . '</b>: ' . $service_rental_agreement_client->vat;
                  }
                  // Check for customer custom fields which is checked show on pdf
                  $pdf_custom_fields = get_custom_fields('customers', array('show_on_pdf' => 1));
                  if (count($pdf_custom_fields) > 0) {
                    echo '<br />';
                    foreach ($pdf_custom_fields as $field) {
                      $value = get_custom_field_value($service_info->clientid, $field['id'], 'customers');
                      if ($value == '') {
                        continue;
                      }
                      echo '<b>' . $field['name'] . '</b>: ' . $value . '<br />';
                    }
                  }
                  ?>
                </address>
              </div>
              <div id="invoice" class="col-md-6 text-right">
                <h4 class="bold"><a><?php echo get_option('service_rental_agreement_prefix') . $service_info->service_rental_agreement_code ?></a></h4>
                <div class="date"><b>Start Date:</b> <?php echo _d($service_info->start_date) ?></div>
                <div class="date"><b>End Date:</b> <?php echo _d($service_info->end_date) ?></div>
                <div class="date"><b>Sales Person:</b> <?php echo get_staff_full_name($service_info->received_by) ?></div>
                <?php if ($service_info->site_name != null) {  ?>
                  <div class="date site_name_area"><b><?= _l('site_name') ?></b> <?php echo $service_info->site_name; ?> </div>
                <?php } ?>
                <?php if ($service_info->field_operator != null) {  ?>
                  <div class="date field_operator_area"><b><?= _l('field_operator') ?></b> <?php echo get_staff_full_name($service_info->field_operator);
                                                                                            if (has_permission(BIZIT_SERVICES_MSL . '_rental_agreement', '', 'edit')) { ?> <a onclick="change_operator('selector')" style="cursor: pointer;"><i class="fa fa-edit"></i></a> <?php } ?></div>
                  <?php echo form_open('admin/services/service_rental_agreement_reasign_field_operator', array('class' => "field_operator_edit_area hide", 'method' => "post")); ?>
                  <!-- field operator rental-->
                  <div class="form-group">
                    <label class="control-label bold"><?= _l('field_operator'); ?></label>
                    <div class="input-group">
                      <select name="field_operator" class="form-control selectpicker" data-live-search="true" data-width="100%" data-none-selected-text="No <?= _l('field_operator'); ?>" id="field_operator">
                        <option value=""></option>
                        <?php
                        $selected = '';
                        foreach ($staff as $member) {
                          if (isset($service_info)) {
                            echo "<option value='" . $member['staffid'] . "'";
                            echo $service_info->field_operator == $member['staffid'] ? 'selected' : '';
                            echo ">" . get_staff_full_name($member['staffid']) . "</option>";
                          }
                        }
                        ?>
                      </select>
                      <span class="input-group-btn">
                        <button type="submit" class="btn btn-info" style="padding: 8.5px 12px;">Update</button>
                      </span>
                    </div>
                  </div>
                  <input type="hidden" name="service_rental_agreement_id" value="<?php echo $service_info->service_rental_agreement_id ?>">
                  </form>
                <?php } ?>

                <?php if (($service_info->status == 0 or $service_info->status == 3) and empty($service_info->invoice_rel_id)) { ?>
                  <?php echo form_open('admin/services/service_rental_agreement_re_confirmation', array('method' => 'post')); ?>
                  <!-- pending rental-->
                  <div class="form-group">
                    <label class="control-label bold">Service Status</label>
                    <div class="input-group">
                      <select name="status" class="form-control selectpicker" data-live-search="true" data-width="100%" data-none-selected-text="Nothing selected" id="order_confirmation">
                        <?php if ($service_info->status != 3) { ?>
                          <option value="2" <?php echo $service_info->status == 2 ? 'selected' : '' ?>>Paid Rental</option>
                          <option value="1" <?php echo $service_info->status == 1 ? 'selected' : '' ?>>Canceled Rental</option>
                          <option value="0" <?php echo $service_info->status == 0 ? 'selected' : '' ?>>Pending Rental</option>
                        <?php } ?>
                      </select>
                      <span class="input-group-btn">
                        <button type="submit" class="btn btn-info" style="padding: 8.5px 12px;">Update</button>
                      </span>
                    </div>
                  </div>
                  <input type="hidden" name="service_rental_agreement_id" value="<?php echo $service_info->service_rental_agreement_id ?>">
                  <input type="hidden" name="service_rental_agreement_code" value="<?php echo $service_info->service_rental_agreement_code ?>">
                  </form>

                <?php } elseif ($service_info->status == 1) { ?>
                  <!-- cancel Order-->
                  <div class="date"><b>Service Status:</b> <?php echo 'Canceled Rental' ?></div>
                <?php } elseif ($service_info->status == 2) { ?>
                  <!-- confirm Order-->
                  <div class="date"><b>Service Status:</b> <?php echo 'Paid Rental' ?></div>
                <?php } elseif ($service_info->status == 3) { ?>
                  <!-- confirm Order-->
                  <div class="date"><b>Service Status:</b> <?php echo 'Pending-partially-paid Rental' ?></div>
                <?php } ?>

                <?php if ($service_info->discounted_days > 0) { ?>
                  <div class="date"><b>Discounted Days:</b> <?php echo floatVal($service_info->discounted_days) ?> days</div>
                <?php } ?>
                <?php if (!empty($service_info->actual_date_returned)) { ?>
                  <div class="date"><b>Actual Return Date:</b> <?php echo _d($service_info->actual_date_returned) ?></div>
                <?php } ?>

              </div>
            </div>
            <table class="table table-bordered table-striped">
              <thead>
                <tr>
                  <th class="desc text-center">#</th>
                  <th class="text-left">EQUIPMENT</th>
                  <th class="desc  text-left">SERVICE</th>
                  <th class="text-left ">QUANTITY</th>
                  <th class="text-right ">RATE</th>
                  <th class="text-right ">TOTAL</th>
                </tr>
              </thead>
              <tbody>
                <?php $counter = 1; ?>
                <?php $grand_total = 0;
                $rate = 0; ?>
                <?php $rental_duration_check = null; ?>
                <?php foreach ($service_details as $v_services): ?>
                  <tr>
                    <td class="desc text-center"><?php echo $counter ?></td>
                    <td class="text-left"><?php echo $v_services->name ?></td>
                    <td class="desc text-left"><?php echo $v_services->category_name ?></td>
                    <td class="desc text-left"><?php echo $rental_days . ' ' . $v_services->rental_duration_check ?></td>
                    <td class="text-right"><?php echo app_format_money($v_services->price, '') ?></td>
                    <td class="text-right"><?php echo app_format_money($v_services->price * $rental_days, '') ?></td>
                  </tr>
                  <?php $counter++;
                  $grand_total += ($v_services->price * $rental_days);
                  $rate += $v_services->penalty_rental_price;
                  $rental_duration_check = $v_services->rental_duration_check; ?>
                <?php endforeach; ?>

                <?php if ($service_info->extra_days > 0) { ?>
                  <tr>
                    <td class="desc text-center"><?php echo $counter ?></td>
                    <td class="text-left" colspan="2">Extra Days <br />Accumulated Equipment cost for the extra days</td>
                    <td class="desc text-left"><?php echo floatVal($service_info->extra_days) . ' ' . $rental_duration_check ?></td>
                    <td class="text-right"><?php echo app_format_money($rate, '') ?></td>
                    <td class="text-right"><?php echo app_format_money($rate * $service_info->extra_days, '') ?></td>
                  </tr>
                <?php $grand_total = $grand_total +  ($rate * $service_info->extra_days);
                } ?>
              </tbody>
              <tfoot>
                <tr>
                  <td colspan="5" class="text-right">GRAND TOTAL</td>
                  <td class="text-right"><?php echo app_format_money($grand_total, $currency_symbol) ?></td>
                </tr>

                <?php if (!empty($service_info->report_files)) {
                  // Decode the JSON string to get an array of files
                  $uploaded_files = json_decode($service_info->report_files, true);
                ?>
                  <tr>
                    <td colspan="6" class="desc">
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

              </tfoot>
            </table>
          </div>
        </div>

        <?php if ($reports_count > 0) { ?>
          <div class="panel_s">
            <div class="panel-body">
              <div class="clearfix"></div>
              <?php render_datatable(array(
                _l('field_report_code'),
                _l('site_name'),
                _l('status'),
                _l('approved_by'),
                _l('field_report_dateadded'),
                _l('options')
              ), 'services_field_report'); ?>
            </div>
          </div>
        <?php } ?>

      </div>
    </div>
  </div>
</div>
<div class="modal fade" id="return_rental_modal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
  <div class="modal-dialog modal-sm" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
        <h4 class="modal-title" id="myModalLabel">Receive Hired Equipments</h4>
      </div>
      <?php echo form_open(admin_url('services/return_rental/' . $service_info->service_rental_agreement_code . '/' . $service_info->invoice_rel_id), array('id' => 'return_rental_form')); ?>
      <div class="modal-body">

        <div class="row">
          <div class="col-md-12">
            <?php $value = (isset($service_info) ? _d($service_info->actual_date_returned) : _d(date('Y-m-d'))); ?>
            <?php echo render_date_input('actual_date_returned', 'rental_actual_date_returned', $value); ?>
            <hr />
            <div class="form-group">
              <label class="control-label">Extra Days</label>
              <div class="input-group">
                <input type="number" class="form-control" step="any" name="extra_days" value="<?php if (isset($service_info)) {
                                                                                                echo $service_info->extra_days;
                                                                                              } ?>">
                <span class="input-group-addon">Days</span>
              </div>
            </div>
            <div class="text-center">
              <h3 style="margin:10px 0;">OR</h3>
            </div>
            <div class="form-group">
              <label class="control-label">Discounted Days</label>
              <div class="input-group">
                <input type="number" class="form-control" step="any" name="discounted_days" max="<?php echo $actual_rental_days; ?>" value="<?php if (isset($service_info)) {
                                                                                                                                              echo $service_info->discounted_days;
                                                                                                                                            } ?>">
                <span class="input-group-addon">Days</span>
              </div>
            </div>
          </div>
        </div>
      </div>
      <div class="modal-footer">
        <button type="submit" name="switch_to_inv" value="true" class="btn btn-default">Save & Switch to Invoice</button>
        <button type="submit" class="btn btn-info">Save</button>
      </div>
      <?php echo form_close(); ?>
    </div>
  </div>
</div>

<?php
if (isset($service_info)) {
  $this->load->view('admin/services/modal_new_field_report.php', $service_info);
}
?>
<?php init_tail(); ?>
<script>
  $(document).ready(function() {
    function updateRentalAgreementStatus(serviceRentalAgreementId) {
      var ajaxUrl = '<?= base_url("admin/services/update_status") ?>';
      console.log('AJAX URL:', ajaxUrl); // Log the AJAX URL
      console.log('Function called with ID:', serviceRentalAgreementId); // Log the ID

      $.ajax({
        url: ajaxUrl,
        type: 'POST',
        data: {
          service_rental_agreement_id: serviceRentalAgreementId,
          status: 2, // Set the status you want to update
          '<?php echo $this->security->get_csrf_token_name(); ?>': '<?php echo $this->security->get_csrf_hash(); ?>' // CSRF protection
        },
        success: function(response) {
          console.log('Response:', response); // Log the response
          // alert('Status updated successfully!'); // Show success alert
        },
        error: function(xhr, status, error) {
          console.error('Error:', error); // Log the error for debugging
          alert('Failed to update status!'); // Show error alert
        }
      });
    }

    // Check if rentalAgreementId is defined and call the function
    if (rentalAgreementId !== null) { // Check for null instead of undefined
      updateRentalAgreementStatus(rentalAgreementId);
    } else {
      console.log('rentalAgreementId is not set.'); // Log if the ID is not set
    }
  });
</script>


<script>
  $(function() {
    initDataTable('.table-services_field_report', admin_url + 'services/view_rental_agreement/<?php echo $service_info->service_rental_agreement_code; ?>/1', [5], [5], 'undefined', [0, 'ASC']);
    // initDataTable('.table-service_category', admin_url + 'services/service_category', [3], [3],'undefined',[0,'ASC']);
  });
</script>
</body>

</html>