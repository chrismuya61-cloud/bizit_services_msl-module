<?php init_head(); ?>
<div id="wrapper">
  <div class="content">
    <div class="row">
      <div class="col-md-12">
        <?php if(has_permission(BIZIT_SERVICES_MSL.'_rental_agreement','','create')){ ?>
        <div class="panel_s">
          <div class="panel-body _buttons">
            <a class="btn btn-default pull-right" href="<?php echo admin_url('services/rental_agreements'); ?>"><i class="fa fa-arrow-left"></i> Back</a>
          </div>
        </div>
        <?php } ?>
        <div class="panel_s">
          <?php echo form_open('admin/services/save_rental_agreement', array('id' => 'service_rental_agreement_form', 'enctype' => 'multipart/form-data')); ?>

          <div class="panel-body">
            <div class="clearfix"></div>
            <?php if(!empty($rental_agreement)) {?>
            <input type="hidden" value="<?php echo $rental_agreement->service_rental_agreement_id; ?>" name="edit_id">
            <?php } ?>
            <div class="row">
              <div class="col-md-6">
                <h4 class="no-margin">
                General Options
                </h4>
                <hr class="hr-panel-heading" />
                <div class="col-md-12">
                  <div class="form-group">
                    <label class="control-label">Service Rental Agreement No.</label>
                    <div class="input-group">
                      <span class="input-group-addon"><?= get_option('service_rental_agreement_prefix'); ?></span>
                      <input type="text" value="<?php echo $this->session->userdata('service_rental_agreement_code'); ?>" readonly="readonly" name="service_rental_agreement_code" class="form-control ">
                    </div>
                  </div>
                </div>
                <div class="col-md-12">
                  <div class="form-group client_id_selector">
                    <label for="clientid"><?php echo _l('expense_add_edit_customer'); ?></label>
                    <select id="clientid" name="clientid" data-live-search="true" data-width="100%" class="ajax-search" data-none-selected-text="<?php echo _l('dropdown_non_selected_tex'); ?>">
                      <?php $selected = (isset($rental_agreement) ? $rental_agreement->clientid : '');
                      if($selected == ''){
                      $selected = (isset($customer_id) ? $customer_id: '');
                      }
                      if($selected != ''){
                      $rel_data = get_relation_data('customer',$selected);
                      $rel_val = get_relation_values($rel_data,'customer');
                      echo '<option value="'.$rel_val['id'].'" selected>'.$rel_val['name'].'</option>';
                      } ?>
                    </select>
                  </div>
                </div>
                <div class="col-md-12">
                  
                  <div class="row">
                    <div class="col-md-6">
                      <?php $value = (isset($rental_agreement) ? _d($rental_agreement->start_date) : _d(date('Y-m-d'))); ?>
                      <?php echo render_date_input('start_date','rental_start_date',$value); ?>
                    </div>
                    <div class="col-md-6">
                      <?php $value = (isset($rental_agreement) ? _d($rental_agreement->end_date) : _d(date('Y-m-d'))); ?>
                      <?php echo render_date_input('end_date','rental_end_date',$value); ?>
                    </div>
                  </div>
                </div>
              </div>

              <div class="col-md-6">
                <h4 class="no-margin">
                Technical Options
                </h4>
                <hr class="hr-panel-heading" />
                <div class="col-md-12">
                  <div class="form-group">
                    <label class="control-label"><?= _l('site_name'); ?></label>
                    <div class="input-group">
                      <span class="input-group-addon"><i class="fa fa-map-marker"></i></span>
                      <input type="text" value="<?= (isset($rental_agreement) ? $rental_agreement->site_name : '') ?>" name="site_name" class="form-control" placeholder="Nairobi-Westlands area, Nanyuki town, Mombasa-Kilindini Port etc." required>
                    </div>
                  </div>
                </div>
                <div class="col-md-12">                  
                  <?php
                        $selected = '';
                         foreach($staff as $member){
                           if(isset($rental_agreement)){
                             if($rental_agreement->field_operator == $member['staffid']) {
                               $selected = $member['staffid'];
                             }
                           }
                          }
                          echo render_select('field_operator',$staff,array('staffid',array('firstname','lastname')),'field_operator',$selected, array("data-none-selected-text" => "No "._l("field_operator"), "required" => 'required'));
                        
                 ?>
                </div>
              </div>

            </div>
          </div>
          <div class="panel-body mtop10">
            <div class="table">
              <table class="table table-bordered table-striped" id="serviceFields">
                <thead>
                  <tr>
                    <th class="col-sm-3">Rental Product</th>
                    <th class="">Price</th>
                    <th class="col-sm-2 text-center"> <a  href="javascript:void(0);" class="addService btn btn-info "><i class="fa fa-plus"></i> Add More</a></th>
                  </tr>
                </thead>
                <tbody>
                  <?php if(!empty($rental_agreement_details)) {?>
                  <?php foreach($rental_agreement_details as $key => $v_rental_agreement){?>
                  <tr>
                    <td>
                      <div class="form-group form-group-bottom">
                        
                        <select name="serviceid[]" class="form-control selectpicker" data-live-search="true" data-width="100%" data-none-selected-text="Nothing selected" onchange="serviceid_add(this, <?= $key ?>);">
                          <?php if (!empty($all_services)): ?>
                          <?php foreach ($all_services as $v_service) : if($v_rental_agreement->serviceid == $v_service->serviceid) :?>
                          <option <?php echo $v_rental_agreement->serviceid == $v_service->serviceid ? 'selected':'' ?> value="<?php echo $v_service->serviceid; ?>">
                            <?php echo $v_service->name.' - '.$v_service->category_name;?>
                          </option>
                          <?php endif; endforeach; ?>
                          <?php endif; ?>
                        </select>
                      </div>
                    </td>
                    <td class="price_td price_td_<?= $key ?>"><div class="form-group form-group-bottom">
                      <div class="input-group">
                        <span class="input-group-addon">
                          <?php  echo $currency_symbol; ?>
                        </span>
                        <input class="form-control" value="<?php if(isset($v_rental_agreement)){ echo $v_rental_agreement->price; } ?>" placeholder="Price" name="service_price[]" type="text">
                      </div>
                    </div>
                  </td>
                  <td class="text-center">
                    <a href="<?php echo admin_url('services/delete_service_rental_agreement_price/' . $v_rental_agreement->service_rental_agreement_details_id.'/'.$this->session->userdata('service_rental_agreement_code')); ?>" class="btn-danger btn"><i class="fa fa-trash"></i></a>
                    <?php if(!empty($rental_agreement)) {?>
                    <input type="hidden" name="service_rental_agreement_details_id[]" value="<?php echo $v_rental_agreement->service_rental_agreement_details_id ?>">
                    <?php } ?>
                  </td>
                </tr>
                <?php } ?>
                <?php } else {?>
                <tr>
                  <td><div class="form-group form-group-bottom">
                    <select name="serviceid[]" class="form-control selectpicker" required data-live-search="true" data-width="100%" data-none-selected-text="Nothing selected" onchange="serviceid_add(this, 0);">
                      <option value=""></option>
                      <?php if (!empty($all_services_filtered)): ?>
                      <?php foreach ($all_services_filtered as $v_service) : ?>
                      <option value="<?php echo $v_service->serviceid; ?>">
                        <?php echo $v_service->name.' - '.$v_service->category_name;?>
                      </option>
                      <?php endforeach; ?>
                      <?php endif; ?>
                    </select>
                  </div>
                </td>
                <td class="price_td price_td_0"><div class="form-group form-group-bottom">
                  <div class="input-group">
                    <span class="input-group-addon">
                      <?php  echo $currency_symbol; ?>
                    </span>
                    <input class="form-control" placeholder="Price" name="service_price[]" type="text" required>
                  </div>
                </div></td>
              </tr>
              <?php } ?>
            </tbody>
          </table>
          <div class="row">
            <div class="col-md-12">
              <div class="form-group">
                <label class="col-sm-5 control-label" style="padding-top: 25px">Grand Total :</label>
                
                <div class="col-sm-7" id="grand_tt">
                  <h1><?php  echo $currency_symbol; ?> 0.0</h1>
                </div>
              </div>
            </div>
          </div>
        </div>
      </div>

    <div class="panel-body mtop10">
      <h4>Uploaded Files</h4>
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
              <?php 
              if (!empty($field_report_info->report_files)) {
                  // Decode the JSON string to get an array of files
                  $uploaded_files = json_decode($field_report_info->report_files, true);

                  if (is_array($uploaded_files) && count($uploaded_files) > 0) {
                      foreach ($uploaded_files as $index => $file) {
                          // Get the file name from the array or string
                          $file_name = is_array($file) && isset($file['name']) ? $file['name'] : $file;
              ?>
                          <tr>
                              <td>
                                  <div class="form-group form-group-bottom">
                                      <!-- Hidden input for existing file name -->
                                      <input type="hidden" name="report_files[]" value="<?= htmlspecialchars($file_name); ?>" />

                                      <!-- Link to the uploaded file -->
                                      <a href="<?= base_url('modules/bizit_services_msl/uploads/reports/' . $file_name); ?>" target="_blank">
                                          <i class="fa fa-file"></i> <?= htmlspecialchars($file_name); ?>
                                      </a>
                                  </div>
                              </td>
                              <td class="text-center">
                                  <a href="javascript:void(0);" class="btn btn-danger removeRow">
                                      <i class="fa fa-trash"></i>
                                  </a>
                              </td>
                          </tr>
              <?php 
                      }
                  } else { 
                      // If no valid files are found, show the file input row
              ?>
                      <tr>
                          <td>
                              <div class="form-group form-group-bottom">
                                  <input type="file" name="service_files[]" id="service_file_0" class="form-control" required>
                              </div>
                          </td>
                          <td class="text-center">
                              <a href="javascript:void(0);" class="btn btn-danger removeRow">
                                  <i class="fa fa-trash"></i>
                              </a>
                          </td>
                      </tr>
              <?php 
                  }
              } else { 
                  // If no files exist, show the file input row
              ?>
                  <tr>
                      <td>
                          <div class="form-group form-group-bottom">
                              <input type="file" name="service_files[]" id="service_file_0" class="form-control" required>
                          </div>
                      </td>
                      <td class="text-center">
                          <a href="javascript:void(0);" class="btn btn-danger removeRow">
                              <i class="fa fa-trash"></i>
                          </a>
                      </td>
                  </tr>
              <?php } ?>
          </tbody>
        </table>
      </div>
      <div class="panel-body mtop10">
        <div class="form-group">
          <label>Rental Agreement Note</label>
          <textarea class="form-control" name="rental_agreement_note" rows="3" placeholder="Enter ..."><?php if(isset($rental_agreement)) {echo $rental_agreement->rental_agreement_note;} ?></textarea>
        </div>
            <div class="btn-bottom-toolbar bottom-transaction text-right">
              <button class="btn-tr btn btn-info mleft10 text-right pull-right" id="submit">
              <?php echo _l('submit'); ?>
              </button>              
              <button class="btn-tr btn btn-default mleft10 text-right pull-right" id="reset_close">
              <?php echo _l('close'); ?>
              </button>
            </div>
      </div>
      <?php echo form_close(); ?>
    </div>
  </div>
</div>
<div class="btn-bottom-pusher"></div>
</div>
</div>
<?php init_tail(); ?>
<script>
$(function(){
$("[id=submit]").submit(function(e) {
if (e.preventDefault()) {
}
});
$("[id=reset_close]").click(function(event) {
event.preventDefault()
$("form").data("validator").resetForm();
});
});
</script>
<script lang="javascript">
//***************** Service Category Start *****************//
$(".addService").click(function() {
  var row_count = $('#serviceFields tbody tr').length;
$("#serviceFields").append(
'<tr>\
<td>\
<div class="form-group form-group-bottom">\
  <select name="serviceid[]" class="form-control selectpicker required" data-live-search="true" data-width="100%" data-none-selected-text="Nothing selected" required onchange="serviceid_add(this, '+row_count+');">\
    <option value=""></option>\
    <?php if (!empty($all_services_filtered)): ?>\
    <?php foreach ($all_services_filtered as $v_service) : ?>\
    <option value="<?php echo $v_service->serviceid; ?>">\
      <?php echo $v_service->name.' - '.$v_service->category_name; ?>\
    </option>\
    <?php endforeach; ?>\
    <?php endif; ?>\
  </select>\
</div>\
</td>\
<td class="price_td price_td_'+row_count+'">\
<div class="form-group form-group-bottom">\
  <div class="input-group">\
    <span class="input-group-addon">\
      <?php  echo $currency_symbol; ?>
    </span>\
    <input class="form-control required" placeholder="Price" name="service_price[]" required type="text">\
  </div>\
</div>\
</td>\
<td class="text-center"><a href="javascript:void(0);" class="remService btn-danger btn"><i class="fa fa-trash"></i></a></td>\
</tr>'
);
$('.selectpicker:last').selectpicker('refresh');
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


//***************** Service Category Start *****************//
$().ready(function() {
// validate signup form on keyup and submit
$("#service_rental_agreement_form").validate({
rules: {
type: "required",
make: "required",
condition: "required",
model: "required",
serial_no: "required",
clientid: "required",
/*,
product_name: {
required: true
},
type: {
required: true
},
price: {
required: true,
number: true
}*/
},
highlight: function(element) {
$(element).closest('.form-group').addClass('has-error');
},
unhighlight: function(element) {
$(element).closest('.form-group').removeClass('has-error');
},
errorElement: 'span',
errorClass: 'help-block',
errorPlacement: function(error, element) {
if (element.parent('.input-group').length) {
error.insertAfter(element.parent());
} else {
error.insertAfter(element);
}
},
messages: {
product_name: {
required: "Please enter Product Name"
}
}
});
});
function formatCurrency(total) {
if(total < 0) {
total = Math.abs(total);
}
return parseFloat(total, 10).toFixed(2).replace(/(\d)(?=(\d{3})+\.)/g, "$1,").toString();
}
function toNumber(str) {
return str*1;
}
function sumArray(array) {
for (
var
index = 0,              // The iterator
length = array.length,  // Cache the array length
sum = 0;                // The total amount
index < length;         // The "for"-loop condition
sum += array[index++]   // Add number on each iteration
);

return formatCurrency(sum);
}
//on add service
$(".addService").click(function() {
var grand_total = [];
$("#serviceFields tbody tr td.price_td input").each(function (index)
{
grand_total[index] =  toNumber($(this).val());
//console.log(index + ": " + $(this).val());
});
$('#grand_tt h1').html("<?php echo $currency_symbol; ?> "+sumArray(grand_total));
});
//on page load
$(function() {
var grand_total = [];
$("#serviceFields tbody tr td.price_td input").each(function (index)
{
grand_total[index] =  toNumber($(this).val());
//console.log(index + ": " + $(this).val());
});
$('#grand_tt h1').html("<?php echo $currency_symbol; ?> "+sumArray(grand_total));
});
//on change
$(document).on('change paste keyup','td.price_td input',function() {
var grand_total = [];
$("td.price_td input").each(function (index)
{
grand_total[index] = toNumber($(this).val());
//console.log(index + ": " + $(this).val());
});
$('#grand_tt h1').html("<?php echo $currency_symbol; ?> "+sumArray(grand_total));
});
//Remove Service Fields
$("#serviceFields").on('click', '.remService', function() {
$(this).parent().parent().remove();
var grand_total = [];
$("td.price_td input").each(function (index)
{
grand_total[index] =  toNumber($(this).val());
//console.log(index + ": " + $(this).val());
});
$('#grand_tt h1').html("<?php echo $currency_symbol; ?> "+sumArray(grand_total));
});

function serviceid_add(sel, num){
   // get the index of the selected option 
 var idx = sel.selectedIndex; 
 // get the value of the selected option 
 var serviceid = sel.options[idx].value; 
   $.get(admin_url + "services/get_service_by_id/" + serviceid, function(data) {
       //console.log(data);
       $('#serviceFields tbody tr .price_td_'+num+' input').val(data.price).change();
   });
}
</script>
</body>
</html>