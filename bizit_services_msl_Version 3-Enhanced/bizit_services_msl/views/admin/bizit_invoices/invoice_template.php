<?php defined('BASEPATH') or exit('No direct script access allowed'); ?>
<div class="panel_s<?php if (!isset($invoice) || (isset($invoice) && count($invoices_to_merge) == 0 && (isset($invoice) && !isset($invoice_from_project) && count($expenses_to_bill) == 0 || $invoice->status == Invoices_model::STATUS_CANCELLED))) { echo ' hide'; } ?>" id="invoice_top_info">
   <div class="panel-body">
      <div class="row">
         <div id="merge" class="col-md-6">
            <?php if (isset($invoice)) { $this->load->view('admin/invoices/merge_invoice', array('invoices_to_merge' => $invoices_to_merge)); } ?>
         </div>
         <?php if (!isset($invoice_from_project)) { ?>
            <div id="expenses_to_bill" class="col-md-6">
               <?php if (isset($invoice) && $invoice->status != Invoices_model::STATUS_CANCELLED) { $this->load->view('admin/invoices/bill_expenses', array('expenses_to_bill' => $expenses_to_bill)); } ?>
            </div>
         <?php } ?>
      </div>
   </div>
</div>
<div class="panel_s invoice accounting-template">
   <div class="additional"></div>
   <div class="panel-body">
      <?php if (isset($invoice)) { echo format_invoice_status($invoice->status); echo '<hr class="hr-panel-heading" />'; } ?>
      <?php hooks()->do_action('before_render_invoice_template', $invoice ?? null); ?>
      <?php if (isset($invoice)) { echo form_hidden('merge_current_invoice', $invoice->id); } ?>
      </div>
   
   <div class="panel-body mtop10">      
      <div class="row">
         <div class="col-md-12">
            <ul class="nav nav-tabs" style="border-top:none;">
            <li class="active "><a data-toggle="tab" href="#products_selector" aria-expanded="true"><?php echo _l('invoice_product_sales'); ?></a></li>
            <li><a data-toggle="tab" href="#services_selector" aria-expanded="true"><?php echo _l('invoice_services'); ?></a></li>
            <li><a data-toggle="tab" href="#tasks_selector" aria-expanded="true"><?php echo _l('bill_tasks'); ?></a></li>
            </ul>
         </div>
      </div>
      <div class="row tab-content">
          <?php $this->load->view('admin/invoices/bizit_invoices/products'); $this->load->view('admin/invoices/bizit_invoices/services'); $this->load->view('admin/invoices/bizit_invoices/tasks'); ?>
      </div>

      <div class="row">
         <div class="col-md-12 mbot10 text-right">
            <button type="button" class="btn btn-info btn-xs" onclick="add_section_header();"><i class="fa fa-plus"></i> Add Section Header</button>
         </div>
      </div>

      <div class="table-responsive s_table">
         <table class="table invoice-items-table items table-main-invoice-edit has-calculations no-mtop">
            <thead><tr>
                <th></th>
                <th width="20%" align="left"><?php echo _l('invoice_table_item_heading'); ?></th>
                <th width="25%" align="left"><?php echo _l('invoice_table_item_description'); ?></th>
                <th width="10%" align="right"><?php echo _l('invoice_table_quantity_heading'); ?></th>
                <th width="15%" align="right"><?php echo _l('invoice_table_rate_heading'); ?></th>
                <th width="20%" align="right"><?php echo _l('invoice_table_tax_heading'); ?></th>
                <th width="10%" align="right"><?php echo _l('invoice_table_amount_heading'); ?></th>
                <th align="center"><i class="fa fa-cog"></i></th>
            </tr></thead>
            <tbody>
               <tr class="main">
                   <td></td><td><textarea name="description" class="form-control" rows="4" placeholder="<?php echo _l('item_description_placeholder'); ?>"></textarea></td><td><textarea name="long_description" rows="4" class="form-control" placeholder="<?php echo _l('item_long_description_placeholder'); ?>"></textarea></td><td><input type="number" name="quantity" min="0" value="1" class="form-control" placeholder="<?php echo _l('item_quantity_placeholder'); ?>"><input type="text" placeholder="<?php echo _l('unit'); ?>" name="unit" class="form-control input-transparent text-right"></td><td><input type="number" name="rate" class="form-control" placeholder="<?php echo _l('item_rate_placeholder'); ?>"></td><td><?php $default_tax = unserialize(get_option('default_tax')); $select = '<select class="selectpicker display-block tax main-tax" data-width="100%" name="taxname" multiple data-none-selected-text="' . _l('no_tax') . '">'; foreach ($taxes as $tax) { $selected = ''; if (is_array($default_tax)) { if (in_array($tax['name'] . '|' . $tax['taxrate'], $default_tax)) { $selected = ' selected '; } } $select .= '<option value="' . $tax['name'] . '|' . $tax['taxrate'] . '"' . $selected . 'data-taxrate="' . $tax['taxrate'] . '" data-taxname="' . $tax['name'] . '" data-subtext="' . $tax['name'] . '">' . $tax['taxrate'] . '%</option>'; } $select .= '</select>'; echo $select; ?></td><td></td><td><button type="button" onclick="add_item_to_table('undefined','undefined',<?php echo (isset($invoice) ? 'true' : 'undefined'); ?>); return false;" class="btn pull-right btn-info"><i class="fa fa-check"></i></button></td>
               </tr>
               <?php if (isset($invoice) || isset($add_items)) {
                  $i = 1; $items_indicator = 'newitems';
                  if (isset($invoice)) { $add_items = $invoice->items; $items_indicator = 'items'; }
                  foreach ($add_items as $item) {
                     // --- RESTORED HEADER RENDER ---
                     if(isset($item['unit']) && $item['unit'] == 'SECTION') {
                         echo '<tr class="sortable item section-header-row" style="background-color: #f8f9fa;">';
                         echo '<td class="dragger">'.form_hidden($items_indicator.'['.$i.'][itemid]', $item['id']).'<input type="hidden" class="order" name="'.$items_indicator.'['.$i.'][order]"></td>';
                         echo '<td colspan="6" class="bold"><input type="text" name="'.$items_indicator.'['.$i.'][description]" class="form-control" style="font-weight:bold; background:transparent; border:none;" value="'.clear_textarea_breaks($item['description']).'">';
                         echo '<input type="hidden" name="'.$items_indicator.'['.$i.'][unit]" value="SECTION"><input type="hidden" name="'.$items_indicator.'['.$i.'][qty]" value="0"><input type="hidden" name="'.$items_indicator.'['.$i.'][rate]" value="0"></td>';
                         echo '<td><a href="#" class="btn btn-danger pull-left" onclick="delete_item(this,'.$item['id'].'); return false;"><i class="fa fa-times"></i></a></td></tr>';
                     } else {
                        // Standard Row Logic
                        $table_row = '<tr class="sortable item">';
                        $table_row .= '<td class="dragger">';
                        if (!is_numeric($item['qty'])) { $item['qty'] = 1; }
                        $invoice_item_taxes = get_invoice_item_taxes($item['id']);
                        if ($item['id'] == 0) { $invoice_item_taxes = $item['taxname']; $manual = true; }
                        $table_row .= form_hidden('' . $items_indicator . '[' . $i . '][itemid]', $item['id']);
                        $amount = $item['rate'] * $item['qty']; $amount = app_format_number($amount);
                        $table_row .= '<input type="hidden" class="order" name="' . $items_indicator . '[' . $i . '][order]">';
                        $table_row .= '</td>';
                        $table_row .= '<td class="bold description"><textarea name="' . $items_indicator . '[' . $i . '][description]" class="form-control" rows="5">' . clear_textarea_breaks($item['description']) . '</textarea></td>';
                        $table_row .= '<td><textarea name="' . $items_indicator . '[' . $i . '][long_description]" class="form-control" rows="5">' . clear_textarea_breaks($item['long_description']) . '</textarea></td>';
                        $table_row .= '<td><input type="number" min="0" onblur="calculate_total();" onchange="calculate_total();" data-quantity name="' . $items_indicator . '[' . $i . '][qty]" value="' . $item['qty'] . '" class="form-control">';
                        $unit_placeholder = ''; if (!$item['unit']) { $unit_placeholder = _l('unit'); $item['unit'] = ''; }
                        $table_row .= '<input type="text" placeholder="' . $unit_placeholder . '" name="' . $items_indicator . '[' . $i . '][unit]" class="form-control input-transparent text-right" value="' . $item['unit'] . '">';
                        $table_row .= '</td>';
                        $table_row .= '<td class="rate"><input type="number" data-toggle="tooltip" title="' . _l('numbers_not_formatted_while_editing') . '" onblur="calculate_total();" onchange="calculate_total();" name="' . $items_indicator . '[' . $i . '][rate]" value="' . $item['rate'] . '" class="form-control"></td>';
                        $table_row .= '<td class="taxrate">' . $this->misc_model->get_taxes_dropdown_template('' . $items_indicator . '[' . $i . '][taxname][]', $invoice_item_taxes, 'invoice', $item['id'], true, $manual) . '</td>';
                        $table_row .= '<td class="amount" align="right">' . $amount . '</td>';
                        $table_row .= '<td><a href="#" class="btn btn-danger pull-left" onclick="delete_item(this,' . $item['id'] . '); return false;"><i class="fa fa-times"></i></a></td>';
                        $table_row .= '</tr>';
                        echo $table_row;
                     }
                     $i++;
                  }
               } ?>
            </tbody>
         </table>
      </div>
      <div class="col-md-8 col-md-offset-4">
         <table class="table text-right">
            <tbody>
               <tr id="subtotal">
                  <td><span class="bold"><?php echo _l('invoice_subtotal'); ?> :</span></td><td class="subtotal"></td>
               </tr>
               <tr id="discount_area">
                  <td><div class="row"><div class="col-md-7"><span class="bold"><?php echo _l('invoice_discount'); ?></span></div><div class="col-md-5"><div class="input-group" id="discount-total"><input type="number" value="<?php echo (isset($invoice) ? $invoice->discount_percent : 0); ?>" class="form-control pull-left input-discount-percent<?php if (isset($invoice) && !is_sale_discount($invoice, 'percent') && is_sale_discount_applied($invoice)) { echo ' hide'; } ?>" min="0" max="100" name="discount_percent"><input type="number" data-toggle="tooltip" data-title="<?php echo _l('numbers_not_formatted_while_editing'); ?>" value="<?php echo (isset($invoice) ? $invoice->discount_total : 0); ?>" class="form-control pull-left input-discount-fixed<?php if (!isset($invoice) || (isset($invoice) && !is_sale_discount($invoice, 'fixed'))) { echo ' hide'; } ?>" min="0" name="discount_total"><div class="input-group-addon"><div class="dropdown"><a class="dropdown-toggle" href="#" id="dropdown_menu_tax_total_type" data-toggle="dropdown" aria-haspopup="true" aria-expanded="true"><span class="discount-total-type-selected"><?php if (!isset($invoice) || isset($invoice) && (is_sale_discount($invoice, 'percent') || !is_sale_discount_applied($invoice))) { echo '%'; } else { echo _l('discount_fixed_amount'); } ?></span><span class="caret"></span></a><ul class="dropdown-menu" id="discount-total-type-dropdown" aria-labelledby="dropdown_menu_tax_total_type"><li><a href="#" class="discount-total-type discount-type-percent<?php if (!isset($invoice) || (isset($invoice) && is_sale_discount($invoice, 'percent')) || (isset($invoice) && !is_sale_discount_applied($invoice))) { echo ' selected'; } ?>">%</a></li><li><a href="#" class="discount-total-type discount-type-fixed<?php if (isset($invoice) && is_sale_discount($invoice, 'fixed')) { echo ' selected'; } ?>"><?php echo _l('discount_fixed_amount'); ?></a></li></ul></div></div></div></div></div></td><td class="discount-total"></td>
               </tr>
               <tr>
                  <td><div class="row"><div class="col-md-7"><span class="bold"><?php echo _l('invoice_adjustment'); ?></span></div><div class="col-md-5"><input type="number" data-toggle="tooltip" data-title="<?php echo _l('numbers_not_formatted_while_editing'); ?>" value="<?php if (isset($invoice)) { echo $invoice->adjustment; } else { echo 0; } ?>" class="form-control pull-left" name="adjustment"></div></div></td><td class="adjustment"></td>
               </tr>
               <tr>
                  <td><span class="bold"><?php echo _l('invoice_total'); ?> :</span></td><td class="total"></td>
               </tr>
            </tbody>
         </table>
      </div>
      <div id="removed-items"></div>
   </div>
   </div>
