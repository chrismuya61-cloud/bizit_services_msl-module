<?php defined('BASEPATH') or exit('No direct script access allowed'); ?>
<?php init_head(); ?>
<?php $this->load->helper(['invoices','number']); ?>
<div id="wrapper">
    <div class="content">
        <div class="row">
            <div class="col-md-12">
                <div class="panel_s">
                    <div class="panel-body">
                        <h4 class="page-header"><?php echo $title; ?></h4>
                        
                        <?php echo form_open(admin_url('services/staff_compensation_rates'), ['id' => 'rate-form']); ?>
                        <div class="row">
                             <div class="col-md-4">
                                <?php 
                                    $staff_for_select = [];
                                    foreach ($staff_members as $member) {
                                        $sid = is_object($member) ? $member->staffid : $member['staffid'];
                                        $fn = is_object($member) ? $member->firstname : $member['firstname'];
                                        $ln = is_object($member) ? $member->lastname : $member['lastname'];
                                        $staff_for_select[] = ['staffid' => $sid, 'firstname' => $fn, 'lastname' => $ln];
                                    }
                                    echo render_select('staffid[]', $staff_for_select, ['staffid', ['firstname', 'lastname']], 'Staff Member(s)', '', ['required' => true, 'multiple' => true]); 
                                ?>
                             </div>
                             <div class="col-md-4">
                                <?php 
                                    $services_grouped = [];
                                    foreach ($all_services as $service) {
                                        $s_code = is_object($service) ? $service->service_type_code : $service['service_type_code'];
                                        $s_id = is_object($service) ? $service->serviceid : $service['serviceid'];
                                        $s_name = is_object($service) ? $service->name : $service['name'];
                                        $s_scode = is_object($service) ? $service->service_code : $service['service_code'];
                                        
                                        $group_name = ($s_code == '001') ? 'Rental' : 'Service';
                                        $services_grouped[$group_name][] = ['id' => $s_id, 'name' => $s_name . ' (' . $s_scode . ')'];
                                    }
                                ?>
                                <div class="form-group">
                                    <label for="serviceid">Service / Rental Item</label>
                                    <select name="serviceid" class="selectpicker" data-width="100%" data-live-search="true">
                                        <?php foreach($services_grouped as $group => $items): ?>
                                            <optgroup label="<?php echo $group; ?>">
                                                <?php foreach($items as $item): ?>
                                                    <option value="<?php echo $item['id']; ?>"><?php echo $item['name']; ?></option>
                                                <?php endforeach; ?>
                                            </optgroup>
                                        <?php endforeach; ?>
                                    </select>
                                </div>
                             </div>
                             <div class="col-md-2">
                                <?php echo render_input('rate', 'Rate', '', 'number', ['step' => '0.01', 'required' => true]); ?>
                             </div>
                             <div class="col-md-2">
                                <button type="submit" class="btn btn-info mtop25">Save</button>
                             </div>
                        </div>
                        <?php echo form_close(); ?>
                        <hr>
                        <div class="table-responsive">
                            <table class="table dt-table">
                                <thead><tr><th>Staff</th><th>Service</th><th>Rate</th><th>Action</th></tr></thead>
                                <tbody>
                                <?php foreach ($rates as $r): ?>
                                    <tr>
                                        <td><?php echo $r['staff_name']; ?></td>
                                        <td><?php echo $r['service_name']; ?></td>
                                        <td><?php echo app_format_money($r['rate'], get_currency_symbol()); ?></td>
                                        <td></td>
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