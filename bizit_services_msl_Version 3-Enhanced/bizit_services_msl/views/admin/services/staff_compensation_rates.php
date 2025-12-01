<?php defined('BASEPATH') or exit('No direct script access allowed'); ?>
<?php init_head(); ?>
<div id="wrapper">
    <div class="content">
        <div class="row">
            <div class="col-md-12">
                <div class="panel_s">
                    <div class="panel-body">
                        <h4 class="no-margin"><?php echo $title; ?></h4>
                        <hr class="hr-panel-heading" />
                        
                        <div class="col-md-12">
                            <p class="text-muted mbot15">Assign allowance rates to staff members for specific services or rental equipment.</p>
                        </div>

                        <?php echo form_open(admin_url('services/staff_compensation_rates')); ?>
                        <div class="row">
                            <div class="col-md-4">
                                <?php 
                                echo render_select('staffid[]', $staff_members, ['staffid', ['firstname', 'lastname']], 'Select Staff', '', ['multiple'=>true, 'data-actions-box'=>true], [], '', '', false); 
                                ?>
                            </div>
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label for="serviceid" class="control-label">Service / Rental Item</label>
                                    <select name="serviceid" class="selectpicker" data-width="100%" data-live-search="true" title="Select Item...">
                                        <?php foreach($all_services as $s) { ?>
                                            <option value="<?php echo $s->serviceid; ?>" data-subtext="<?php echo $s->service_code; ?>">
                                                <?php echo $s->name . ' (' . $s->category_name . ')'; ?>
                                            </option>
                                        <?php } ?>
                                    </select>
                                </div>
                            </div>
                            <div class="col-md-2">
                                <?php echo render_input('rate', 'Rate Amount', '', 'number', ['step'=>'0.01']); ?>
                            </div>
                            <div class="col-md-2">
                                <button type="submit" class="btn btn-info display-block" style="margin-top:25px;">Save Rate</button>
                            </div>
                        </div>
                        <?php echo form_close(); ?>
                        
                        <div class="clearfix"></div>
                        <hr />

                        <h4 class="bold mbot15">Current Rates</h4>
                        <div class="table-responsive">
                            <table class="table dt-table" data-order-col="0" data-order-type="asc">
                                <thead>
                                    <tr>
                                        <th>Staff Member</th>
                                        <th>Service / Item</th>
                                        <th>Allowance Rate</th>
                                        <th>Type</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php foreach ($rates as $r): ?>
                                        <tr>
                                            <td>
                                                <?php echo staff_profile_image($r['staffid'], ['staff-profile-image-small', 'mright5']); ?>
                                                <?php echo $r['staff_name']; ?>
                                            </td>
                                            <td><?php echo $r['service_name']; ?></td>
                                            <td class="bold"><?php echo app_format_money($r['rate'], get_currency_symbol()); ?></td>
                                            <td><span class="label label-default"><?php echo ucfirst($r['allowance_type']); ?></span></td>
                                        </tr>
                                    <?php endforeach; ?>
                                </tbody>
                            </table>
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