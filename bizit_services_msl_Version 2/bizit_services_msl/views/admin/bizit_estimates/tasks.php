<div class="tab-pane fade col-md-12 gen_add_tasks" id="tasks_selector">
    <div class="row">
        <?php if (!isset($invoice_from_project) && isset($billable_tasks)) {
        ?>
            <div class="col-md-4">
                <div class="form-group select-placeholder input-group-select form-group-select-task_select popover-250">
                    <div class="input-group input-group-select">
                        <select name="task_select" data-live-search="true" id="task_select" class="selectpicker no-margin _select_input_group" data-width="100%" data-none-selected-text="<?php echo _l('bill_tasks'); ?>">
                            <option value=""></option>
                            <?php foreach ($billable_tasks as $task_billable) { ?>
                                <option value="<?php echo $task_billable['id']; ?>" <?php if ($task_billable['started_timers'] == true) { ?>disabled class="text-danger important" data-subtext="<?php echo _l('invoice_task_billable_timers_found'); ?>" <?php } else {
                                                                                                                                                                                                                                                            $task_rel_data = get_relation_data($task_billable['rel_type'], $task_billable['rel_id']);
                                                                                                                                                                                                                                                            $task_rel_value = get_relation_values($task_rel_data, $task_billable['rel_type']);
                                                                                                                                                                                                                                                            ?> data-subtext="<?php echo $task_billable['rel_type'] == 'project' ? '' : $task_rel_value['name']; ?>" <?php } ?>><?php echo $task_billable['name']; ?></option>
                            <?php } ?>
                        </select>
                        <div class="input-group-addon input-group-addon-bill-tasks-help">
                            <?php
                            if (isset($invoice) && !empty($invoice->project_id)) {
                                $help_text = _l('showing_billable_tasks_from_project') . ' ' . get_project_name_by_id($invoice->project_id);
                            } else {
                                $help_text = _l('invoice_task_item_project_tasks_not_included');
                            }
                            echo '<span class="pointer popover-invoker" data-container=".form-group-select-task_select"
                      data-trigger="click" data-placement="top" data-toggle="popover" data-content="' . $help_text . '">
                      <i class="fa fa-question-circle"></i></span>';
                            ?>
                        </div>
                    </div>
                </div>
            </div>
        <?php } ?>
        <div class="col-md-<?php if (!isset($invoice_from_project)) {
                                echo 5;
                            } else {
                                echo 8;
                            } ?> text-right show_quantity_as_wrapper">
            <div class="mtop10">
                <span><?php echo _l('show_quantity_as'); ?> </span>
                <div class="radio radio-primary radio-inline">
                    <input type="radio" value="1" id="sq_1" name="show_quantity_as" data-text="<?php echo _l('invoice_table_quantity_heading'); ?>" <?php if (isset($invoice) && $invoice->show_quantity_as == 1) {
                                                                                                                                                        echo 'checked';
                                                                                                                                                    } else if (!isset($hours_quantity) && !isset($qty_hrs_quantity)) {
                                                                                                                                                        echo 'checked';
                                                                                                                                                    } ?>>
                    <label for="sq_1"><?php echo _l('quantity_as_qty'); ?></label>
                </div>
                <div class="radio radio-primary radio-inline">
                    <input type="radio" value="2" id="sq_2" name="show_quantity_as" data-text="<?php echo _l('invoice_table_hours_heading'); ?>" <?php if (isset($invoice) && $invoice->show_quantity_as == 2 || isset($hours_quantity)) {
                                                                                                                                                        echo 'checked';
                                                                                                                                                    } ?>>
                    <label for="sq_2"><?php echo _l('quantity_as_hours'); ?></label>
                </div>
                <div class="radio radio-primary radio-inline">
                    <input type="radio" value="3" id="sq_3" name="show_quantity_as" data-text="<?php echo _l('invoice_table_quantity_heading'); ?>/<?php echo _l('invoice_table_hours_heading'); ?>" <?php if (isset($invoice) && $invoice->show_quantity_as == 3 || isset($qty_hrs_quantity)) {
                                                                                                                                                                                                            echo 'checked';
                                                                                                                                                                                                        } ?>>
                    <label for="sq_3"><?php echo _l('invoice_table_quantity_heading'); ?>/<?php echo _l('invoice_table_hours_heading'); ?></label>
                </div>
            </div>
        </div>
    </div>
</div>