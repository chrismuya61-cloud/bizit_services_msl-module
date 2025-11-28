<?php init_head(); ?>
<div id="wrapper">
    <div class="content">
        <div class="row">
            <div class="col-md-12">
                <?php if (has_permission(BIZIT_SERVICES_MSL, '', 'create')) { ?>
                    <div class="panel_s">
                        <div class="panel-body _buttons">

                            <?php
                            $cat_check = []; // Array to hold service type IDs
                            $check_report_gen = null;
                            foreach ($service_details as $v_details) :
                                if ($v_details->service_request_id == $service_info->service_request_id):
                                    $cat_check[] = $v_details->service_typeid;
                                endif;
                            endforeach;

                            if ($service_info->status != 1) {
                                if (in_array('2', $cat_check)) {
                                    $check_report_gen = check_report($service_info->service_request_id);

                                    // Fetch the invoice status by comparing tblservice_request's invoice_rel_id with tblinvoices's id
                                    $invoice_status = $this->db->select('status')
                                        ->where('id', $service_info->invoice_rel_id)  // Compare invoice_rel_id with id in tblinvoices
                                        ->get('tblinvoices')
                                        ->row();

                                    if (!empty($check_report_gen)) {
                                        if ($invoice_status && $invoice_status->status == 2) {
                                            // If invoice status is 2, update the status of tblservice_request to 2
                                            $this->db->where('service_request_code', $service_info->service_request_code)
                                                ->update('tblservice_request', ['status' => 2]); ?>
                                            <div class="btn-group" role="group">
                                                <a href="<?php echo base_url() ?>admin/services/certificate_pdf/<?php echo $service_info->service_request_code ?>" class="btn btn-success">
                                                    <i class="fa fa-download"></i> Certificate
                                                </a>
                                            </div>
                                        <?php } ?>
                                        <div class="btn-group" role="group">
                                            <a href="<?php echo base_url() ?>admin/services/report/view/<?php echo $service_info->service_request_code ?>" class="btn btn-primary "><i class="fa fa-pencil"></i> Report View</a>
                                        </div>
                                        <?php
                                    } else {
                                        if (in_array($service_info->item_type, services_model::CALIBRATION_ITEMS)) {
                                        ?>
                                            <div class="btn-group" role="group">
                                                <a href="<?php echo base_url() ?>admin/services/report/1/<?php echo $service_info->service_request_code ?>" class="btn btn-primary "><i class="fa fa-pencil"></i> Report Entry</a>
                                            </div>
                                <?php
                                        }
                                    }
                                }
                                ?>

                                <div class="btn-group" role="group">
                                    <a href="<?php echo admin_url('services/request_pdf/' . $service_info->service_request_code); ?>" class="btn btn-info "><i class="fa fa-download"></i> Request Form</a>
                                </div>
                                <?php if ($service_info->status == 2) { ?>
                                    <div class="btn-group hide" role="group">
                                        <a onclick="print_receipt(<?php echo $service_info->service_request_code; ?>,'request')" class="btn btn-default "><i class="fa fa-print"></i> Print Receipt</a>
                                        <a href="<?php echo admin_url('services/pdf_request_receipt/' . $service_info->service_request_code) ?>" class="btn btn-danger " data-toggle='tooltip' data-placement='top' title='Download Receipt'><i class="fa fa-download"></i></a>
                                    </div>
                                <?php } ?>
                                <?php if ($service_info->status == 3 and (empty($service_info->invoice_rel_id) or $service_info->invoice_rel_id == 0)) { ?>
                                    <div class="btn-group" role="group">
                                        <a href="<?php echo admin_url('services/request_invoice_generation/' . $service_info->service_request_code); ?>" class="btn btn-warning "><i class="fa fa-edit"></i> Raise Invoice</a>
                                    </div>
                                <?php } ?>
                                <?php if (!empty($service_info->invoice_rel_id) and $service_info->invoice_rel_id > 0) { ?>
                                    <div class="btn-group" role="group">
                                        <a target="_blank" href="<?php echo admin_url('invoices/list_invoices#' . $service_info->invoice_rel_id); ?>" class="btn btn-success "><i class="fa fa-eye"></i> View Invoice</a>
                                    </div>
                                <?php } ?>
                            <?php } ?>

                            <a class="btn btn-default pull-right" href="<?php echo admin_url('services/requests'); ?>"><i class="fa fa-arrow-left"></i> Back</a>
                        </div>
                    </div>
                <?php } ?>
                <div class="panel_s">
                    <div class="panel-body mtop10">
                        <div class="ribbon project-status-ribbon-2" id="ribbon_project_1">
                            <?php
                            $show_paid = false;
                            if ($service_info->item_type == 'Accessory' && !empty($service_info->invoice_rel_id)) {
                                $CI = &get_instance();
                                $CI->db->select('status')
                                    ->from('tblinvoices')
                                    ->where('id', $service_info->invoice_rel_id)
                                    ->limit(1);
                                $invoice = $CI->db->get()->row();

                                if ($invoice && $invoice->status == 2) {
                                    $show_paid = true;
                                }
                            }
                            ?>

                            <?php if ($service_info->status == 2 || $show_paid) { ?>
                                <span style="background:#0eb114">Fully Paid</span>
                            <?php } elseif (!empty($service_info->invoice_rel_id) && $service_info->invoice_rel_id > 0) { ?>
                                <span style="background:#f40303">Invoiced</span>
                            <?php } ?>


                        </div>
                        <div id="details" class="clearfix">
                            <div class="col-md-12">
                                <h4>Service Request View</h4>
                                <hr class="hr-panel-heading" />
                            </div>
                            <div id="client" class="col-md-6">
                                <span class="bold">CUSTOMER BILLING INFO:</span>
                                <address>
                                    <span class="bold"><a href="<?php echo admin_url('clients/client/' . $service_request_client->userid); ?>" target="_blank">
                                            <?php
                                            if ($service_request_client->show_primary_contact == 1) {
                                                $pc_id = get_primary_contact_user_id($service_info->clientid);
                                                if ($pc_id) {
                                                    echo get_contact_full_name($pc_id) . '<br />';
                                                }
                                            }
                                            echo $service_request_client->company; ?></a></span><br>
                                    <?php echo $service_request_client->billing_street; ?><br>
                                    <?php
                                    if (!empty($service_request_client->billing_city)) {
                                        echo $service_request_client->billing_city;
                                    }
                                    if (!empty($service_request_client->billing_state)) {
                                        echo ', ' . $service_request_client->billing_state;
                                    }
                                    $billing_country = get_country_short_name($service_request_client->billing_country);
                                    if (!empty($billing_country)) {
                                        echo '<br />' . $billing_country;
                                    }
                                    if (!empty($service_request_client->billing_zip)) {
                                        echo ', ' . $service_request_client->billing_zip;
                                    }
                                    if (!empty($service_request_client->vat)) {
                                        echo '<br /><b>' . _l('invoice_vat') . '</b>: ' . $service_request_client->vat;
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

                                <div class="bold" style="margin-top:20px;">INSTRUMENT INFO:</div>
                                <div class="address"><b>Type:</b> <?php echo $service_info->item_type ?></div>
                                <div class="address"><b>Make:</b> <?php echo $service_info->item_make ?></div>
                                <div class="address"><b>Model:</b> <?php echo $service_info->item_model ?></div>
                                <div class="email"><b>Serial:</b> <?php echo $service_info->serial_no ?></div>
                            </div>
                            <div id="invoice" class="col-md-6 text-right">
                                <h4 class="bold"><a><?php echo get_option('service_request_prefix') . $service_info->service_request_code ?></a></h4>
                                <div class="date"><b>Drop Off Date:</b> <?php echo _d($service_info->drop_off_date) ?></div>
                                <div class="date"><b>Collection Date:</b> <?php echo _d($service_info->collection_date) ?></div>
                                <div class="date"><b>Sales Person:</b> <?php echo get_staff_full_name($service_info->received_by) ?></div>

                                <?php if (($service_info->status == 0 or $service_info->status == 3) and empty($service_info->invoice_rel_id)) { ?>
                                    <?php echo form_open('admin/services/service_re_confirmation', array('method' => 'post')); ?>
                                    <!-- pending rental-->
                                    <div class="form-group">
                                        <label class="control-label bold">Service Status</label>
                                        <div class="input-group">
                                            <select name="status" class="form-control selectpicker" data-live-search="true" data-width="100%" data-none-selected-text="Nothing selected" id="order_confirmation">
                                                <option value="3" <?php echo $service_info->status == 3 ? 'selected' : '' ?>>Service Completed</option>
                                                <?php if ($service_info->status != 3) { ?>
                                                    <option class="hide" value="2" <?php echo $service_info->status == 2 ? 'selected' : '' ?>>Paid Service</option>
                                                    <option value="1" <?php echo $service_info->status == 1 ? 'selected' : '' ?>>Canceled Service</option>
                                                    <option value="0" <?php echo $service_info->status == 0 ? 'selected' : '' ?>>Pending Service</option>
                                                <?php } ?>
                                            </select>
                                            <span class="input-group-btn">
                                                <button type="submit" class="btn btn-info" style="padding: 8.5px 12px;">Update</button>
                                            </span>
                                        </div>
                                    </div>
                                    <input type="hidden" name="service_request_id" value="<?php echo $service_info->service_request_id ?>">
                                    <input type="hidden" name="service_request_code" value="<?php echo $service_info->service_request_code ?>">
                                    </form>

                                <?php } elseif ($service_info->status == 1) { ?>
                                    <!-- cancel Order-->
                                    <div class="date"><b>Service Status:</b> <?php echo 'Canceled Service' ?></div>
                                <?php } elseif ($service_info->status == 2) { ?>
                                    <!-- confirm Order-->
                                    <div class="date"><b>Service Status:</b> <?php echo 'Paid Service' ?></div>
                                <?php } elseif ($service_info->status == 3) { ?>
                                    <!-- confirm Order-->
                                    <div class="date"><b>Service Status:</b> <?php echo 'Completed Service' ?></div>
                                <?php } ?>
                            </div>
                        </div>
                        <table class="table table-bordered table-striped">
                            <!-- Main Table Header -->
                            <thead>
                                <tr>
                                    <th class="desc text-center">#</th>
                                    <th class="text-left">EQUIPMENT</th>
                                    <th class="desc text-left">SERVICE</th>
                                    <th class="text-right">TOTAL</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php
                                $counter = 1;
                                $grand_total = 0;
                                foreach ($service_details as $v_services):
                                    $grand_total += $v_services->price;
                                ?>
                                    <tr>
                                        <td class="desc text-center"><?php echo $counter++; ?></td>
                                        <td class="text-left"><?php echo htmlspecialchars($service_info->item_type); ?></td>
                                        <td class="desc text-left"><?php echo htmlspecialchars($v_services->category_name); ?></td>
                                        <td class="text-right"><?php echo app_format_money($v_services->price, $currency_symbol); ?></td>
                                    </tr>
                                <?php endforeach; ?>
                            </tbody>
                            <tfoot>
                                <tr>
                                    <td colspan="3" class="text-right"><strong>GRAND TOTAL</strong></td>
                                    <td class="text-right"><?php echo app_format_money($grand_total, $currency_symbol); ?></td>
                                </tr>
                            </tfoot>
                        </table>

                        <!-- Accessory Table -->
                        <?php if (!empty($existing_accessories)): ?>
                            <table class="table table-bordered table-striped">
                                <thead>
                                    <tr>
                                        <th class="desc text-center">#</th>
                                        <th class="text-left" colspan="2">ACCESSORY</th>
                                        <th class="text-right">PRICE</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php
                                    $counter = 1;
                                    $accessory_total = 0;
                                    foreach ($existing_accessories as $accessory):
                                        $accessory_total += $accessory->price;
                                    ?>
                                        <tr>
                                            <td class="desc text-center"><?php echo $counter++; ?></td>
                                            <td class="text-left" colspan="2"><?php echo !empty($accessory->description) ? htmlspecialchars($accessory->description) : ""; ?></td>
                                            <td class="text-right"><?php echo app_format_money($accessory->price, $currency_symbol); ?></td>
                                        </tr>
                                    <?php endforeach; ?>
                                </tbody>
                                <tfoot>
                                    <tr>
                                        <td colspan="3" class="text-right"><strong>GRAND TOTAL</strong></td>
                                        <td class="text-right"><?php echo app_format_money($accessory_total, $currency_symbol); ?></td>
                                    </tr>
                                </tfoot>
                            </table>
                        <?php else: ?>
                            <p class="text-center">No Accessories Found</p>
                        <?php endif; ?>

                        <!-- Pre-Inspection Table -->
                        <table class="table table-bordered">
                            <thead>
                                <tr>
                                    <th class="desc text-center">#</th>
                                    <th class="text-left">INSPECTION ITEM</th>
                                    <th class="text-left">TYPE</th>
                                    <th class="text-left">REMARKS</th>
                                </tr>
                            </thead>
                            <tbody>
                                <!-- Pre-Inspection Section -->
                                <tr>
                                    <td colspan="4" class="text-center"><strong>Instrument Pre-Inspection</strong></td>
                                </tr>
                                <?php if (!empty($pre_inspection_items)): ?>
                                    <?php
                                    $counter = 1;
                                    foreach ($pre_inspection_items as $item):
                                        // Remove underscores and capitalize each word
                                        $formatted_item = ucwords(str_replace('_', ' ', htmlspecialchars($item->inspection_item)));
                                    ?>
                                        <tr>
                                            <td class="desc text-center"><?php echo $counter++; ?></td>
                                            <td class="text-left"><?php echo $formatted_item; ?></td>
                                            <td class="text-left"><?php echo htmlspecialchars(ucwords(str_replace('_', ' ', $item->inspection_type))); ?></td>
                                            <td class="text-left"><?php echo htmlspecialchars($item->remarks_condition); ?></td>
                                        </tr>
                                    <?php endforeach; ?>
                                <?php else: ?>
                                    <tr>
                                        <td colspan="4" class="text-center">No pre-inspection items available.</td>
                                    </tr>
                                <?php endif; ?>
                            </tbody>
                        </table>

                        <div class="panel-body mtop10">
                            <table class="table table-bordered">
                                <tr>
                                    <td style="width: 50%; vertical-align: top;">
                                        <table class="table table-bordered table-striped">
                                            <thead>
                                                <tr>
                                                    <th colspan="2" class="text-center">DROPPED OFF INFORMATION</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                <tr>
                                                    <td><strong>Dropped Off By</strong></td>
                                                    <td><?= htmlspecialchars($dropped_off_by); ?></td>
                                                </tr>
                                                <tr>
                                                    <td><strong>Dated</strong></td>
                                                    <td><?= htmlspecialchars($dropped_off_date); ?></td>
                                                </tr>
                                                <!-- <tr>
                                                        <td><strong>Signature</strong></td>
                                                        <td><?= htmlspecialchars($dropped_off_signature); ?></td>
                                                    </tr> -->
                                                <tr>
                                                    <td><strong>ID/Phone No.</strong></td>
                                                    <td><?= htmlspecialchars($dropped_off_id_number); ?></td>
                                                </tr>
                                            </tbody>
                                        </table>
                                    </td>
                                    <td style="width: 50%; vertical-align: top;">
                                        <table class="table table-bordered table-striped">
                                            <thead>
                                                <tr>
                                                    <th colspan="2" class="text-center">RECEIVED INFORMATION</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                <tr>
                                                    <td><strong>Received By</strong></td>
                                                    <td><?= htmlspecialchars($received_by); ?></td>
                                                </tr>
                                                <tr>
                                                    <td><strong>Dated</strong></td>
                                                    <td><?= htmlspecialchars($received_date); ?></td>
                                                </tr>
                                                <!-- <tr>
                                                        <td><strong>Signature</strong></td>
                                                        <td><?= htmlspecialchars($received_signature); ?></td>
                                                    </tr>
                                                    <tr>
                                                        <td><strong>ID/Phone No.</strong></td>
                                                        <td><?= htmlspecialchars($received_id_number); ?></td>
                                                    </tr> -->
                                            </tbody>
                                        </table>
                                    </td>
                                </tr>
                            </table>
                        </div>


                        <!-- Post-Inspection Table -->
                        <table class="table table-bordered">
                            <thead>
                                <tr>
                                    <th class="desc text-center">#</th>
                                    <th class="text-left">INSPECTION ITEM</th>
                                    <th class="text-left">TYPE</th>
                                    <th class="text-left">REMARKS</th>
                                </tr>
                            </thead>
                            <tbody>
                            <tbody>
                                <!-- Post-Inspection Section -->
                                <tr>
                                    <td colspan="4" class="text-center"><strong>Instrument Post-Inspection</strong></td>
                                </tr>
                                <?php if (!empty($post_inspection_items)): ?>
                                    <?php
                                    $counter = 1;
                                    foreach ($post_inspection_items as $item):
                                    ?>
                                        <tr>
                                            <td class="desc text-center"><?php echo $counter++; ?></td>
                                            <td class="text-left"><?php echo htmlspecialchars(ucwords(str_replace('_', ' ', $item->inspection_item))); ?></td>
                                            <td class="text-left"><?php echo htmlspecialchars(ucwords(str_replace('_', ' ', $item->inspection_type))); ?></td>
                                            <td class="text-left"><?php echo htmlspecialchars($item->remarks_condition); ?></td>
                                        </tr>
                                    <?php endforeach; ?>
                                <?php else: ?>
                                    <tr>
                                        <td colspan="4" class="text-center">No post-inspection items available.</td>
                                    </tr>
                                <?php endif; ?>
                            </tbody>


                        </table>
                        <div class="panel-body mtop10">
                            <table class="table table-bordered">
                                <tr>
                                    <!-- Checklist Table -->
                                    <td style="width: 50%; vertical-align: top;">
                                        <table class="table table-bordered table-striped" id="checklistFields">
                                            <thead>
                                                <tr>
                                                    <th class="col-sm-3">CHECKLIST ITEM</th>
                                                    <th class="col-sm-2">X/√</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                <!-- Loop through Checklist Items -->
                                                <?php if (isset($checklist_items) && !empty($checklist_items)): ?>
                                                    <?php foreach ($checklist_items as $item): ?>
                                                        <tr>
                                                            <td><?= $item->item ?></td>
                                                            <td><span><?= $item->status ?: 'N/A' ?></span></td>
                                                        </tr>
                                                    <?php endforeach; ?>
                                                <?php else: ?>
                                                    <tr>
                                                        <td colspan="2">No checklist items available.</td>
                                                    </tr>
                                                <?php endif; ?>
                                            </tbody>
                                        </table>
                                    </td>

                                    <!-- Released By and Collected By Section -->
                                    <td style="width: 50%; vertical-align: top;">
                                        <table class="table table-bordered">
                                            <tbody>
                                                <!-- Released By Section -->
                                                <tr>
                                                    <td><strong>Released By</strong></td>
                                                    <td><span><?= isset($released_info->released_by) ? $released_info->released_by : 'N/A' ?></span></td>
                                                </tr>
                                                <tr>
                                                    <td><strong>Dated</strong></td>
                                                    <td><span><?= isset($released_info->released_date) ? $released_info->released_date : 'N/A' ?></span></td>
                                                </tr>
                                                <tr>
                                                    <td><strong>ID Number</strong></td>
                                                    <td><span><?= isset($released_info->released_id_number) ? $released_info->released_id_number : 'N/A' ?></span></td>
                                                </tr>

                                                <!-- Collected By Section -->
                                                <!-- <tr>
                                                            <td><strong>Collected By</strong></td>
                                                            <td><span><?= isset($released_info->collected_by) ? $released_info->collected_by : 'N/A' ?></span></td>
                                                        </tr> -->
                                                <!-- <tr>
                                                            <td><strong>Dated</strong></td>
                                                            <td><span><?= isset($released_info->collected_date) ? $released_info->collected_date : 'N/A' ?></span></td>
                                                        </tr>
                                                        <tr>
                                                            <td><strong>ID Number</strong></td>
                                                            <td><span><?= isset($released_info->collected_id_number) ? $released_info->collected_id_number : 'N/A' ?></span></td>
                                                        </tr> -->
                                            </tbody>
                                        </table>
                                    </td>
                                </tr>
                            </table>
                        </div>

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
            </div>
        </div>
    </div>
    <?php init_tail(); ?>
    <script>
        $(function() {
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