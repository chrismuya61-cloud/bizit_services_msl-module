<?php init_head(); ?>

<style>
.inspection-grid {
    display: grid;
    grid-template-columns: 1fr 1fr; /* Two equal columns */
    gap: 10px; /* Space between items */
}

.inspection-item {
    display: flex;
    align-items: center;
    justify-content: space-between;
    border: 1px solid #ddd;
    padding: 10px;
    border-radius: 5px;
    background: #f9f9f9;
}

.inspection-item label {
    flex: 1;
}

.inspection-item input[type="checkbox"],
.inspection-item input[type="text"],
.inspection-item button {
    margin-left: 10px;
}
</style>

<div id="wrapper">
  <div class="content">
    <div class="row">
      <div class="col-md-12">
        <?php if(has_permission(BIZIT_SERVICES_MSL,'','create')){ ?>
        <div class="panel_s">
          <div class="panel-body _buttons">
            <a class="btn btn-default pull-right" href="<?php echo admin_url('services/requests'); ?>"><i class="fa fa-arrow-left"></i> Back</a>
          </div>
        </div>
        <?php } ?>
        <div class="panel_s">
        <?php echo form_open('admin/services/save_request', array('id' => 'service_request_form', 'enctype' => 'multipart/form-data')); ?>
          <div class="panel-body">
            <div class="clearfix"></div>
            <?php if(!empty($request)) {?>
            <input type="hidden" value="<?php echo $request->service_request_id; ?>" name="edit_id">
            <?php } ?>
            <div class="row">
              <div class="col-md-6">
                <h4 class="no-margin">
                General Options
                </h4>
                <hr class="hr-panel-heading" />
                <div class="col-md-12">
                  <div class="form-group">
                    <label class="control-label">Service Request No.</label>
                    <div class="input-group">
                      <span class="input-group-addon"><?= get_option('service_request_prefix'); ?></span>
                      <input type="text" value="<?php echo $this->session->userdata('service_request_code'); ?>" readonly="readonly" name="service_request_code" class="form-control ">
                    </div>
                  </div>
                </div>
                <div class="col-md-12">
                  <div class="form-group client_id_selector">
                    <label for="clientid"><?php echo _l('expense_add_edit_customer'); ?></label>
                    <select id="clientid" name="clientid" data-live-search="true" data-width="100%" class="ajax-search" data-none-selected-text="<?php echo _l('dropdown_non_selected_tex'); ?>">
                      <?php $selected = (isset($request) ? $request->clientid : '');
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
                      <?php $value = (isset($request) ? _d($request->drop_off_date) : _d(date('Y-m-d'))); ?>
                      <?php echo render_date_input('drop_off_date','drop_off_date',$value); ?>
                    </div>
                    <div class="col-md-6">
                      <?php $value = (isset($request) ? _d($request->collection_date) : _d(date('Y-m-d'))); ?>
                      <?php echo render_date_input('collection_date','collection_date',$value); ?>
                    </div>
                  </div>
                </div>
                <div class="col-md-12">
                  <div class="form-group">
                    <label for="">Condition</label>
                    <textarea class="form-control" name="condition"><?php if(isset($request)) {echo $request->condition;} ?></textarea>
                  </div>
                </div>
              </div>
              <div class="col-md-6">
                <h4 class="no-margin">
                Item Options
                </h4>
                <hr class="hr-panel-heading" />
                <div class="col-md-12">
                  <div class="form-group">
                    <label for="">Type</label>                  
                            <select name="type" class="form-control selectpicker" data-live-search="true" data-width="100%" data-none-selected-text="<?php echo _l('dropdown_non_selected_tex'); ?>">
                                <option value=""></option>
                                <?php $all_types = array('Accessory','Data Collector','GIS','GNSS','lasers','Level','Radio','Total Station','Theodolite' );
                                if (!empty($all_types)):?>
                                <?php foreach ($all_types as $v_types) : ?>
                                <option <?php if(!empty($request_details)) {echo $request->item_type == $v_types ? 'selected':''; }?> value="<?php echo $v_types; ?>">
                                    <?php echo $v_types;?>
                                </option>
                                <?php endforeach; ?>
                                <?php endif; ?>
                            </select>
                  </div>
                </div>
                <div class="col-md-12">
                  <div class="form-group">
                    <label for="">Model Series</label>
                    <input type="text" name="model" value="<?php if(isset($request)) {echo $request->item_model;} ?>"  class="form-control" placeholder="e.g PENTAX R200N">
                  </div>
                </div>
                <div class="col-md-12">
                  <div class="form-group">
                    <label for="">Make</label>
                    <input type="text" name="make" value="<?php if(isset($request)) {echo $request->item_make;} ?>"  class="form-control" placeholder="e.g Pentax, Foif" >
                  </div>
                </div>
                <div class="col-md-12">
                  <div class="form-group">
                    <label for="">Serial No.</label>
                    <input type="text" name="serial_no" value="<?php if(isset($request)) {echo $request->serial_no;} ?>"  class="form-control">
                  </div>
                </div>
              </div>
            </div>
          </div>
          <div class="panel-body mtop10">
              <div class="table">
              <input type="hidden" name="removed_pre_items" id="removed_pre_items" value="">
                      <table class="table table-bordered table-striped" id="inspectionFields">
                      <thead>
                          <tr>
                              <th class="col-md-2">Instrument Pre-Inspection</th>
                              <th class="col-md-4">Remarks/Condition</th>
                              <th class="col-md-3 text-center">
                                  <select id="inspectionSelector" name="inspectionSelector" class="form-control selectpicker" data-live-search="true" data-width="100%">
                                      <option value="" disabled selected>Select an inspection item</option>
                                                  <option value="gps_pole_bubble">GPS Pole Bubble</option>
                                                  <option value="bluetooth_function">Bluetooth Function</option>
                                                  <option value="satellite_function">Satellite Function</option>
                                                  <option value="power_system">Power System</option>
                                                  <option value="data_collector_software">Data Collector Software</option>
                                                  <option value="data_collector_keypads">Data Collector Keypads</option>
                                                  <option value="data_collector_screen">Data Collector Screen</option>
                                                  <option value="data_collector_charging_system">Data Collector Charging System</option>
                                                  <option value="file_transfer">File Transfer</option>
                                                  <option value="range_distance_base_rover">Range Distance Between Base/Rover</option>
                                                  <option value="eyepiece_lens">Eyepiece Lens</option>
                                                  <option value="focusing_lens">Focusing Lens</option>
                                                  <option value="objective_lens">Objective Lens</option>
                                                  <option value="focusing_knob_unit">Focusing Knob Unit</option>
                                                  <option value="foot_screws">Foot Screws</option>
                                                  <option value="horizontal_turning_screw">Horizontal Turning Screw</option>
                                                  <option value="base">Base</option>
                                                  <option value="center_shaft">Center Shaft</option>
                                                  <option value="circular_bubble">Circular Bubble</option>
                                                  <option value="compensator">Compensator</option>
                                                  <option value="cross_hairs">Cross Hairs</option>
                                                  <option value="cross_hairs_adjusting_screws">Cross Hairs Adjusting Screws</option>
                                                  <option value="telescope">Telescope</option>
                                                  <option value="eyepiece_lens">Eyepiece Lens</option>
                                                  <option value="focusing_lens">Focusing Lens</option>
                                                  <option value="objective_lens">Objective Lens</option>
                                                  <option value="horizontal_tangent_clamp">Horizontal Tangent Clamp</option>
                                                  <option value="vertical_tangent_clamp">Vertical Tangent Clamp</option>
                                                  <option value="horizontal_bubble">Horizontal Bubble</option>
                                                  <option value="tribrach_bubble">Tribrach Bubble</option>
                                                  <option value="tribrach_foot_screws">Tribrach Foot Screws</option>
                                                  <option value="handle">Handle</option>
                                                  <option value="screen_lcd">Screen LCD</option>
                                                  <option value="battery_voltage_capacity">Battery Voltage Capacity</option>
                                                  <option value="pointer_laser">Pointer Laser</option>
                                                  <option value="laser_plummet">Laser Plummet</option>
                                                  <option value="optical_plummet">Optical Plummet</option>
                                                  <option value="battery_terminals">Battery Terminals</option>
                                                  <option value="laser_beams">Laser Beams</option>
                                                  <option value="power_system">Power System</option>
                                                  <option value="compensator">Compensator</option>
                                                  <option value="on_off_switch">ON/OFF Switch</option>
                                                  <option value="hv_beam">H/V Beam</option>
                                                  <option value="bubble">Bubble</option>
                                  </select>
                                  <button type="button" class="btn btn-info addInspection" id="addPreItem">
                                      <i class="fa fa-plus"></i> Add More
                                  </button>
                              </th>
                          </tr>
                      </thead>
                      <tbody>
                          <?php if (!empty($pre_inspection_items)): ?>
                              <?php foreach ($pre_inspection_items as $inspection): ?>
                                <!-- <input type="hidden" id="removed_pre_items" name="removed_pre_items" value=""> -->

                                  <tr id="pre_row_<?php echo htmlspecialchars($inspection['inspection_item']); ?>">
                                      <td>
                                          <input type="hidden" name="inspection_items[]" value="<?php echo htmlspecialchars($inspection['inspection_item']); ?>">
                                          <?php echo ucfirst(str_replace('_', ' ', htmlspecialchars($inspection['inspection_item']))); ?>
                                      </td>
                                      <td>
                                          <input type="text" name="remarks[<?php echo htmlspecialchars($inspection['inspection_item']); ?>]" 
                                              class="form-control" 
                                              value="<?php echo htmlspecialchars($inspection['remarks_condition']); ?>" 
                                              placeholder="Remarks/Condition">
                                      </td>
                                      <td class="text-center">
                                          <button type="button" class="btn btn-danger" onclick="removeRow('pre', '<?php echo htmlspecialchars($inspection['inspection_item']); ?>')">
                                              <i class="fa fa-trash"></i> Remove
                                          </button>
                                      </td>
                                  </tr>
                              <?php endforeach; ?>
                          <?php endif; ?>
                      </tbody>

                  </table>
              </div>

              <!-- Dropped Off Section -->
              <div class="panel-body mtop10">
                  <table class="table table-bordered">
                      <tr>
                          <td style="width: 50%; vertical-align: top;">
                              <table class="table table-bordered table-striped">
                                  <tr>
                                      <td><strong>Dropped Off By</strong></td>
                                      <td>
                                          <input type="text" name="dropped_off_by" class="form-control" 
                                                value="<?= isset($request->dropped_off_by) ? $request->dropped_off_by : ''; ?>" 
                                                placeholder="Enter name" required>
                                      </td>
                                  </tr>
                                  <tr>
                                      <td><strong>Dated</strong></td>
                                      <td>
                                          <input type="date" name="dropped_off_date" class="form-control" 
                                                value="<?= isset($request->dropped_off_date) ? $request->dropped_off_date : ''; ?>" required>
                                      </td>
                                  </tr>
                                  <!-- <tr>
                                      <td><strong>Signature</strong></td>
                                      <td>
                                          <input type="text" name="dropped_off_signature" class="form-control" 
                                                value="<?= isset($request->dropped_off_signature) ? $request->dropped_off_signature : ''; ?>"
                                                placeholder="Enter signature">
                                      </td>
                                  </tr> -->
                                  <tr>
                                      <td><strong>ID/Phone No.</strong></td>
                                      <td>
                                          <input type="text" name="dropped_off_id_number" class="form-control" 
                                                value="<?= isset($request->dropped_off_id_number) ? $request->dropped_off_id_number : ''; ?>"
                                                placeholder="Enter ID Number" required>
                                      </td>
                                  </tr>
                              </table>
                          </td>

                          <!-- Received By Section -->
                          <td style="width: 50%; vertical-align: top;">
                              <table class="table table-bordered table-striped">
                                  <tr>
                                      <td><strong>Received By</strong></td>
                                      <td>
                                          <input type="text" name="req_received_by" class="form-control" 
                                                value="<?= isset($request->req_received_by) ? $request->req_received_by : ''; ?>" 
                                                placeholder="Enter name">
                                      </td>
                                  </tr>
                                  <tr>
                                      <td><strong>Dated</strong></td>
                                      <td>
                                          <input type="date" name="received_date" class="form-control" 
                                                value="<?= isset($request->received_date) ? $request->received_date : ''; ?>">
                                      </td>
                                  </tr>
                                  <!-- <tr>
                                      <td><strong>Signature</strong></td>
                                      <td>
                                          <input type="text" name="received_signature" class="form-control" 
                                                value="<?= isset($request->received_signature) ? $request->received_signature : ''; ?>"
                                                placeholder="Enter signature">
                                      </td>
                                  </tr>
                                  <tr>
                                      <td><strong>ID/Phone No.</strong></td>
                                      <td>
                                          <input type="text" name="received_id_number" class="form-control" 
                                                value="<?= isset($request->received_id_number) ? $request->received_id_number : ''; ?>"
                                                placeholder="Enter ID Number">
                                      </td>
                                  </tr> -->
                              </table>
                          </td>
                      </tr>
                  </table>
              </div>

              <!-- Post-Inspection Table -->
              <div class="table">
              <input type="hidden" id="removed_post_items" name="removed_post_items" value="">
                  <table class="table table-bordered table-striped" id="postInspectionFields">
                      <thead>
                          <tr>
                          <th class="col-md-2">Instrument Post-Inspection</th>
                              <th class="col-md-4">Remarks/Condition</th>
                              <th class="col-md-3 text-center">
                                  <select id="postInspectionSelector" name="postInspectionSelector" class="form-control selectpicker" data-live-search="true" data-width="100%">
                                      <!-- Add the post-inspection items as options here -->
                                      <option value="" disabled selected>Select an inspection item</option>
                                                  <option value="gps_pole_bubble">GPS Pole Bubble</option>
                                                  <option value="bluetooth_function">Bluetooth Function</option>
                                                  <option value="satellite_function">Satellite Function</option>
                                                  <option value="power_system">Power System</option>
                                                  <option value="data_collector_software">Data Collector Software</option>
                                                  <option value="data_collector_keypads">Data Collector Keypads</option>
                                                  <option value="data_collector_screen">Data Collector Screen</option>
                                                  <option value="data_collector_charging_system">Data Collector Charging System</option>
                                                  <option value="file_transfer">File Transfer</option>
                                                  <option value="range_distance_base_rover">Range Distance Between Base/Rover</option>
                                                  <option value="eyepiece_lens">Eyepiece Lens</option>
                                                  <option value="focusing_lens">Focusing Lens</option>
                                                  <option value="objective_lens">Objective Lens</option>
                                                  <option value="focusing_knob_unit">Focusing Knob Unit</option>
                                                  <option value="foot_screws">Foot Screws</option>
                                                  <option value="horizontal_turning_screw">Horizontal Turning Screw</option>
                                                  <option value="base">Base</option>
                                                  <option value="center_shaft">Center Shaft</option>
                                                  <option value="circular_bubble">Circular Bubble</option>
                                                  <option value="compensator">Compensator</option>
                                                  <option value="cross_hairs">Cross Hairs</option>
                                                  <option value="cross_hairs_adjusting_screws">Cross Hairs Adjusting Screws</option>
                                                  <option value="telescope">Telescope</option>
                                                  <option value="eyepiece_lens">Eyepiece Lens</option>
                                                  <option value="focusing_lens">Focusing Lens</option>
                                                  <option value="objective_lens">Objective Lens</option>
                                                  <option value="horizontal_tangent_clamp">Horizontal Tangent Clamp</option>
                                                  <option value="vertical_tangent_clamp">Vertical Tangent Clamp</option>
                                                  <option value="horizontal_bubble">Horizontal Bubble</option>
                                                  <option value="tribrach_bubble">Tribrach Bubble</option>
                                                  <option value="tribrach_foot_screws">Tribrach Foot Screws</option>
                                                  <option value="handle">Handle</option>
                                                  <option value="screen_lcd">Screen LCD</option>
                                                  <option value="battery_voltage_capacity">Battery Voltage Capacity</option>
                                                  <option value="pointer_laser">Pointer Laser</option>
                                                  <option value="laser_plummet">Laser Plummet</option>
                                                  <option value="optical_plummet">Optical Plummet</option>
                                                  <option value="battery_terminals">Battery Terminals</option>
                                                  <option value="laser_beams">Laser Beams</option>
                                                  <option value="power_system">Power System</option>
                                                  <option value="compensator">Compensator</option>
                                                  <option value="on_off_switch">ON/OFF Switch</option>
                                                  <option value="hv_beam">H/V Beam</option>
                                                  <option value="bubble">Bubble</option>
                                  </select>
                                  <button type="button" class="btn btn-info addInspection" id="addPostItem">
                                      <i class="fa fa-plus"></i> Add More
                                  </button>
                              </th>
                          </tr>
                      </thead>
                      <tbody>
                          <!-- Dynamic Rows Will Be Appended Here for Post-Inspection -->
                          <?php if (!empty($post_inspection_items)): ?>
                              <?php foreach ($post_inspection_items as $postinspection): ?>
                                  <tr id="post_row_<?php echo (is_array($postinspection) ? htmlspecialchars($postinspection['inspection_item']) : htmlspecialchars($postinspection->inspection_item)); ?>">
                                      <td>
                                          <input type="hidden" name="postinspection_items[]" value="<?php echo (is_array($postinspection) ? htmlspecialchars($postinspection['inspection_item']) : htmlspecialchars($postinspection->inspection_item)); ?>">
                                          <?php echo ucfirst(str_replace('_', ' ', (is_array($postinspection) ? htmlspecialchars($postinspection['inspection_item']) : htmlspecialchars($postinspection->inspection_item)))); ?>
                                      </td>
                                      <td>
                                          <input type="text" name="postremarks[<?php echo (is_array($postinspection) ? htmlspecialchars($postinspection['inspection_item']) : htmlspecialchars($postinspection->inspection_item)); ?>]" 
                                                class="form-control" 
                                                value="<?php echo (is_array($postinspection) ? htmlspecialchars($postinspection['remarks_condition']) : htmlspecialchars($postinspection->remarks_condition)); ?>" 
                                                placeholder="Remarks/Condition">
                                      </td>
                                      <td class="text-center">
                                          <button type="button" class="btn btn-danger" onclick="removeRow('post', '<?php echo (is_array($postinspection) ? htmlspecialchars($postinspection['inspection_item']) : htmlspecialchars($postinspection->inspection_item)); ?>')">
                                              <i class="fa fa-trash"></i> Remove
                                          </button>
                                      </td>
                                  </tr>
                              <?php endforeach; ?>
                          <?php endif; ?>
                      </tbody>
                  </table>
              </div>
          </div>

          <script>
              // Function to add rows dynamically for Pre-Inspection
              document.getElementById('addPreItem').addEventListener('click', function () {
                  const selector = document.getElementById('inspectionSelector');
                  const selectedValue = selector.value;
                  const selectedText = selector.options[selector.selectedIndex].text;

                  if (selectedValue) {
                      // Prevent duplicate entries
                      if (document.getElementById('pre_row_' + selectedValue)) {
                          alert('Item already added!');
                          return;
                      }

                      // Append new row to the Pre-Inspection table
                      const tableBody = document.querySelector('#inspectionFields tbody');
                      const row = document.createElement('tr');
                      row.id = 'pre_row_' + selectedValue;
                      row.innerHTML = `
                          <td>
                              <input type="hidden" name="inspection_items[]" value="${selectedValue}">
                              ${selectedText}
                          </td>
                          <td>
                              <input type="text" name="remarks[${selectedValue}]" class="form-control" placeholder="Remarks/Condition">
                          </td>
                          <td class="text-center">
                              <button type="button" class="btn btn-danger" onclick="removeRow('pre', '${selectedValue}')">
                                  <i class="fa fa-trash"></i> Remove
                              </button>
                          </td>
                      `;
                      tableBody.appendChild(row);
                  } else {
                      alert('Please select an item!');
                  }
              });

              // Function to add rows dynamically for Post-Inspection
              document.getElementById('addPostItem').addEventListener('click', function () {
                  const selector = document.getElementById('postInspectionSelector');
                  const selectedValue = selector.value;
                  const selectedText = selector.options[selector.selectedIndex].text;

                  if (selectedValue) {
                      // Prevent duplicate entries
                      if (document.getElementById('post_row_' + selectedValue)) {
                          alert('Item already added!');
                          return;
                      }

                      // Append new row to the Post-Inspection table
                      const tableBody = document.querySelector('#postInspectionFields tbody');
                      const row = document.createElement('tr');
                      row.id = 'post_row_' + selectedValue;
                      row.innerHTML = `
                          <td>
                              <input type="hidden" name="postinspection_items[]" value="${selectedValue}">
                              ${selectedText}
                          </td>
                          <td>
                              <input type="text" name="postremarks[${selectedValue}]" class="form-control" placeholder="Remarks/Condition">
                          </td>
                          <td class="text-center">
                              <button type="button" class="btn btn-danger" onclick="removeRow('post', '${selectedValue}')">
                                  <i class="fa fa-trash"></i> Remove
                              </button>
                          </td>
                      `;
                      tableBody.appendChild(row);
                  } else {
                      alert('Please select an item!');
                  }
              });

              // Function to remove a row (pre or post inspection)
              function removeRow(type, id) {
                  const row = document.getElementById(type + '_row_' + id);
                  if (row) {
                      row.remove();

                      // Add the removed ID to a hidden input for server-side processing
                      const removedItemsInput = document.getElementById('removed_' + type + '_items');
                      if (removedItemsInput) {
                          let removedItems = removedItemsInput.value ? removedItemsInput.value.split(',') : [];
                          removedItems.push(id);
                          removedItemsInput.value = removedItems.join(',');
                      }
                  }
              }
          </script>

          <div class="panel-body mtop10">
              <table class="table table-bordered">
                <tr>
                  <!-- Checklist Table -->
                  <td style="width: 50%; vertical-align: top;">
                    <table class="table table-bordered table-striped" id="checklistFields">
                      <thead>
                        <tr>
                          <th class="col-sm-3">Checklist Item</th>
                          <th class="col-sm-2">X/√</th>
                        </tr>
                      </thead>
                      <tbody>
                      <tr>
                            <td>Calibration Certificate Issued</td>
                            <td>
                              <select name="item_status[]" class="form-control selectpicker">
                                <option value="X" <?= isset($checklist_items[0]) && $checklist_items[0]['status'] == 'X' ? 'selected' : '' ?>>X</option>
                                <option value="√" <?= isset($checklist_items[0]) && $checklist_items[0]['status'] == '√' ? 'selected' : '' ?>>√</option>
                              </select>
                            </td>
                          </tr>
                          <tr>
                            <td>Calibration Sticker Issued</td>
                            <td>
                              <select name="item_status[]" class="form-control selectpicker">
                                <option value="X" <?= isset($checklist_items[1]) && $checklist_items[1]['status'] == 'X' ? 'selected' : '' ?>>X</option>
                                <option value="√" <?= isset($checklist_items[1]) && $checklist_items[1]['status'] == '√' ? 'selected' : '' ?>>√</option>
                              </select>
                            </td>
                          </tr>
                          <tr>
                            <td>Date of Next Calibration Advised</td>
                            <td>
                              <select name="item_status[]" class="form-control selectpicker">
                                <option value="X" <?= isset($checklist_items[2]) && $checklist_items[2]['status'] == 'X' ? 'selected' : '' ?>>X</option>
                                <option value="√" <?= isset($checklist_items[2]) && $checklist_items[2]['status'] == '√' ? 'selected' : '' ?>>√</option>
                              </select>
                            </td>
                          </tr>
                          <tr>
                            <td>Equipment in Good Condition</td>
                            <td>
                              <select name="item_status[]" class="form-control selectpicker">
                                <option value="X" <?= isset($checklist_items[3]) && $checklist_items[3]['status'] == 'X' ? 'selected' : '' ?>>X</option>
                                <option value="√" <?= isset($checklist_items[3]) && $checklist_items[3]['status'] == '√' ? 'selected' : '' ?>>√</option>
                              </select>
                            </td>
                          </tr>
                      </tbody>
                    </table>
                  </td>

                  <!-- Released By and Collected By Section -->
                  <td style="width: 50%; vertical-align: top;">
                    <table class="table table-bordered">
                      <tbody>
                      <tr>
                          <td><strong>Released By</strong></td>
                          <td><input type="text" name="released_by" class="form-control" placeholder="Enter name" value="<?= isset($collection_data->released_by) ? $collection_data->released_by : '' ?>"></td>
                        </tr>
                        <tr>
                          <td><strong>Dated</strong></td>
                          <td><input type="date" name="released_date" class="form-control" value="<?= isset($collection_data->released_date) ? $collection_data->released_date : '' ?>"></td>
                        </tr>
                        <tr class="hide">
                          <td><strong>ID/Phone No.</strong></td>
                          <td><input type="text" name="released_id_number" class="form-control" placeholder="Enter ID Number" value="<?= isset($collection_data->released_id_number) ? $collection_data->released_id_number : '' ?>"></td>
                        </tr>
                        <tr>
                          <td><strong>Collected By</strong></td>
                          <td><input type="text" name="collected_by" class="form-control" placeholder="Enter name" value="<?= isset($collection_data->collected_by) ? $collection_data->collected_by : '' ?>"></td>
                        </tr>
                        <tr>
                          <td><strong>Dated</strong></td>
                          <td><input type="date" name="collected_date" class="form-control" value="<?= isset($collection_data->collected_date) ? $collection_data->collected_date : '' ?>"></td>
                        </tr>
                        <tr>
                          <td><strong>ID/Phone No.</strong></td>
                          <td><input type="text" name="collected_id_number" class="form-control" placeholder="Enter ID Number" value="<?= isset($collection_data->collected_id_number) ? $collection_data->collected_id_number : '' ?>"></td>
                        </tr>

                      </tbody>
                    </table>
                  </td>
                </tr>
              </table>
          </div>

          <!-- Service type and the price start -->
          <div class="panel-body mtop10">
            <div class="table">
              <table class="table table-bordered table-striped" id="serviceFields">
                <thead>
                  <tr>
                    <th class="col-sm-3">Service Type</th>
                    <th class="">Price</th>
                    <th class="col-sm-2 text-center"> 
                      <a  href="javascript:void(0);" class="addService btn btn-info "><i class="fa fa-plus"></i> Add More</a></th>
                  </tr>
                </thead>
                <tbody>
                  <?php if(!empty($request_details)) {?>
                  <?php foreach($request_details as $key => $v_request){?>
                  <tr>
                    <td>
                      <div class="form-group form-group-bottom">
                        
                        <select name="serviceid[]" class="form-control selectpicker" data-live-search="true" data-width="100%" data-none-selected-text="Nothing selected" onchange="serviceid_add(this, <?= $key ?>);">
                          <option value=""></option>
                          <?php if (!empty($all_services)): ?>
                          <?php foreach ($all_services as $v_service) : ?>
                          <option <?php echo $v_request->serviceid == $v_service->serviceid ? 'selected':'' ?> value="<?php echo $v_service->serviceid; ?>">
                            <?php echo $v_service->name.' - '.$v_service->category_name;?>
                          </option>
                          <?php endforeach; ?>
                          <?php endif; ?>
                        </select>
                      </div>
                    </td>
                    <td class="price_td price_td_<?= $key ?>"><div class="form-group form-group-bottom">
                      <div class="input-group">
                        <span class="input-group-addon">
                          <?php  echo $currency_symbol; ?>
                        </span>
                        <input class="form-control" value="<?php if(isset($v_request)){ echo $v_request->price; } ?>" placeholder="Price" name="service_price[]" type="text">
                      </div>
                    </div>
                  </td>
                  <td class="text-center">
                    <a href="<?php echo admin_url('services/delete_service_price/' . $v_request->service_request_details_id.'/'.$this->session->userdata('service_request_code')); ?>" class="btn-danger btn"><i class="fa fa-trash"></i></a>
                    <?php if(!empty($request)) {?>
                    <input type="hidden" name="service_request_details_id[]" value="<?php echo $v_request->service_request_details_id ?>">
                    <?php } ?>
                  </td>
                </tr>
                <?php } ?>
                <?php } else {?>
                <tr>
                  <td><div class="form-group form-group-bottom">
                    <select name="serviceid[]" class="form-control selectpicker" required data-live-search="true" data-width="100%" data-none-selected-text="Nothing selected" onchange="serviceid_add(this, 0);">
                      <option value=""></option>
                      <?php if (!empty($all_services)): ?>
                      <?php foreach ($all_services as $v_service) : ?>
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
      <!-- Service type and the price end -->

      <!-- Product Type and Price Start -->
      <div class="panel-body mtop10">
          <div class="table">
              <table class="table table-bordered table-striped" id="addServiceAccessory">
                  <thead>
                      <tr>
                          <th class="col-sm-3">Equipment Spares</th>
                          <th>Price</th>
                          <th class="col-sm-2 text-center">
                              <a href="javascript:void(0);" class="addServiceAccessory btn btn-info">
                                  <i class="fa fa-plus"></i> Add Equipment Spares
                              </a>
                          </th>
                      </tr>
                  </thead>
                  <tbody id="accessory_table_body">
                <?php if (!empty($existing_accessories)): ?>
                    <?php foreach ($existing_accessories as $accessory): ?>
                        <tr>
                            <td>
                                <select name="accessoryserviceid[]" class="form-control">
                                    <option value="">Select Accessory</option>
                                    <?php if (!empty($all_services_accessories)): ?>
                                        <?php foreach ($all_services_accessories as $available_accessory): ?>
                                            <option value="<?php echo $available_accessory->id; ?>" <?php echo ($available_accessory->id == $accessory->accessory_id) ? 'selected' : ''; ?>>
                                                <?php echo $available_accessory->description; ?>
                                            </option>
                                        <?php endforeach; ?>
                                    <?php endif; ?>
                                </select>
                            </td>
                            <td class="text-center">
                            <div class="input-group">
                              <span class="input-group-addon">
                                <?php  echo $currency_symbol; ?>
                              </span>
                                <input type="text" name="accessoryservice_price[]" class="form-control accessory_price" value="<?php echo $accessory->price; ?>" readonly />
                            </div> 
                            </td>
                            <td class="text-center">
                                <button type="button" class="btn btn-danger removeRow"><i class="fa fa-trash"></i></button>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                    <?php else: ?>
                      <?php endif; ?>
                  </tbody>
                  </table>
                  <div class="row">
                      <div class="col-md-12">
                          <div class="form-group">
                              <label class="col-sm-5 control-label" style="padding-top: 25px">Grand Total :</label>
                              <div class="col-sm-7" id="accessory_grand_tt">
                                  <h1><?php echo $currency_symbol; ?> 0.0</h1>
                              </div>
                          </div>
                      </div>
                  </div>
              </div>
          </div>  
        <script>
        document.addEventListener('DOMContentLoaded', function () {
            document.querySelector('body').addEventListener('change', function (e) {
                if (e.target.matches('select[name="accessoryserviceid[]"]')) {
                    let Id = e.target.value;
                    let priceField = e.target.closest('tr').querySelector('input[name="accessoryservice_price[]"]');

                    // Fetch service data via AJAX
                    fetch("<?php echo base_url('admin/services/get_service_accessory_by_id/'); ?>" + Id)
                        .then(response => response.json())
                        .then(data => {
                            if (data.success) {
                                priceField.value = data.rate; // Populate price field
                            } else {
                                alert(data.message); // Show error message if not found
                                priceField.value = ''; // Clear price field
                            }
                        })
                        .catch(error => {
                            console.error('Error fetching data:', error);
                            priceField.value = '';
                        });
                }
            });
        });
        </script>
        <!-- Product Type and Price End -->

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
                        <?php 
                        if (!empty($service_file_info->report_files)) {
                            // Decode the JSON string to get an array of files
                            $uploaded_files = json_decode($service_file_info->report_files, true);
                            log_message('debug', 'Decoded uploaded_files: ' . print_r($uploaded_files, true));

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
                  <label>Service Note</label>
                  <textarea class="form-control" name="service_note" rows="3" placeholder="Enter ..."><?php if(isset($request)) {echo $request->service_note;} ?></textarea>
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
$("#service_request_form").validate({
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

$(document).ready(function() {
    // Calculate the grand total on page load
    updateAccessoryTotal();
// Add Accessory Row
$(".addServiceAccessory").click(function () {
    var row_count = $('#addServiceAccessory tbody tr').length;

    // Append the new accessory row
    $("#addServiceAccessory tbody").append(`
        <tr>
            <td>
                <div class="form-group form-group-bottom">
                    <select name="accessoryserviceid[]" class="form-control selectpicker accessory-select" required data-live-search="true" data-width="100%" data-none-selected-text="Nothing selected" onchange="accessoryid_add(this, ${row_count});">
                        <option value=""></option>
                        <?php if (!empty($all_services_accessories)): ?>
                            <?php foreach ($all_services_accessories as $service): ?>
                                <option value="<?php echo $service->id; ?>" data-price="<?php echo $service->rate; ?>">
                                    <?php echo $service->description; ?>
                                </option>
                            <?php endforeach; ?>
                        <?php endif; ?>
                    </select>
                </div>
            </td>
            <td class="accessory_price_td accessory_price_td_${row_count}">
                <div class="form-group form-group-bottom">
                    <div class="input-group">
                        <span class="input-group-addon"><?php echo $currency_symbol; ?></span>
                        <input class="form-control accessory_price" placeholder="Price" name="accessoryservice_price[]" type="text" readonly>
                    </div>
                </div>
            </td>
            <td class="text-center">
                <a href="javascript:void(0);" class="btn btn-danger removeServiceAccessory"><i class="fa fa-trash"></i></a>
            </td>
        </tr>
    `);

    // Refresh the selectpicker for the newly added row
    $('.selectpicker:last').selectpicker('refresh');

    // Update total accessory price
    updateAccessoryTotal();
});
});

function updateAccessoryTotal() {
    var accessory_grand_total = 0;

    // Loop through each row in the accessories table
    $("#addServiceAccessory tbody tr").each(function () {
        var price = $(this).find('.accessory_price').val();
        console.log('Price found:', price); // Debug log
        if (price) {
            accessory_grand_total += toNumber(price);
        }
    });

    console.log('Grand total:', accessory_grand_total); // Debug log

    // Update the grand total in the display
    $('#accessory_grand_tt h1').html("<?php echo $currency_symbol; ?> " + accessory_grand_total.toFixed(2));
}

function accessoryid_add(selectElement) {
    var selectedOption = $(selectElement).find('option:selected');
    var price = selectedOption.data('price'); // Get the price from the data attribute

    // Update the corresponding price field in the same row
    $(selectElement).closest('tr').find('.accessory_price').val(price ? price : '0.00');

    // Recalculate the grand total
    updateAccessoryTotal();
}


// // Remove Accessory Row
$("#addServiceAccessory").on('click', '.removeServiceAccessory', function() {
    $(this).closest('tr').remove();
    updateAccessoryTotal();
});
// Event delegation for removing a row (existing and new rows)
$("#addServiceAccessory").on('click', '.removeRow', function() {
    $(this).closest('tr').remove();
    updateAccessoryTotal(); // Recalculate the grand total after removal
});

// Handle Row Deletion
$('#accessory_table_body').on('click', '.removeRow', function () {
    var row = $(this).closest('tr'); // Get the closest row
    var accessory_id = row.data('accessory-id'); // Get the accessory ID if it exists

    if (accessory_id) {
        // Confirm deletion for existing accessory
        if (confirm('Are you sure you want to delete this accessory?')) {
            $.ajax({
                url: "<?php echo base_url('admin/services/delete_accessory'); ?>", // Backend URL
                type: 'POST',
                data: {
                      id: accessory_id,
                      '<?php echo $this->security->get_csrf_token_name(); ?>': '<?php echo $this->security->get_csrf_hash(); ?>'
                  },
                dataType: 'json',
                success: function (response) {
                    if (response.success) {
                        row.remove(); // Remove the row from the DOM
                        updateAccessoryTotal(); // Update totals if applicable
                        alert('Accessory deleted successfully.');
                    } else {
                        alert('Error: Could not delete accessory.');
                    }
                },
                error: function () {
                    alert('An error occurred. Please try again.');
                }
            });
        }
    } else {
        // Simply remove new rows (not saved to DB yet)
        row.remove();
        updateAccessoryTotal();
    }
});


// Update accessory total on input change
$(document).on('change paste keyup', 'td.accessory_price_td input', function() {
    updateAccessoryTotal();
});

// Utility functions
function toNumber(value) {
    return parseFloat(value) || 0;
}

function sumArray(arr) {
    return arr.reduce((a, b) => a + b, 0).toFixed(2);
}


$(".addService").click(function() {
    var row_count = $('#serviceFields tbody tr').length;
    $("#serviceFields").append(
        '<tr>\
        <td>\
        <div class="form-group form-group-bottom">\
          <select name="serviceid[]" class="form-control selectpicker required" data-live-search="true" data-width="100%" data-none-selected-text="Nothing selected" required onchange="serviceid_add(this, '+row_count+');">\
            <option value=""></option>\
            <?php if (!empty($all_services)): ?>\
            <?php foreach ($all_services as $v_service) : ?>\
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
              <?php echo $currency_symbol; ?>\
            </span>\
            <input class="form-control required" placeholder="Price" name="service_price[]" required type="text">\
          </div>\
        </div>\
        </td>\
        <td class="text-center"><a href="javascript:void(0);" class="remService btn-danger btn"><i class="fa fa-trash"></i></a></td>\
        </tr>'
    );
    $('#serviceFields').find('.selectpicker:last').selectpicker('refresh');
});


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