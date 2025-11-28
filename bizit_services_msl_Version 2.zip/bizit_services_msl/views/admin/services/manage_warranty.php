<?php init_head(); ?>

<link rel="stylesheet" type="text/css" href="https://cdn.datatables.net/1.13.5/css/jquery.dataTables.min.css">
<?php if ($this->session->flashdata('success') || $this->session->flashdata('error')): ?>
    <div id="flash-message" style="position: fixed; top: 15%; right: 30px; transform: translateY(-50%); width: 300px; z-index: 1000;">
        <?php if ($this->session->flashdata('success')): ?>
            <div class="alert alert-success"><?= $this->session->flashdata('success'); ?></div>
        <?php elseif ($this->session->flashdata('error')): ?>
            <div class="alert alert-danger"><?= $this->session->flashdata('error'); ?></div>
        <?php endif; ?>
    </div>
    <script>
        setTimeout(function() {
            var flashMessage = document.getElementById('flash-message');
            if (flashMessage) {
                flashMessage.style.transition = "opacity 0.5s ease";
                flashMessage.style.opacity = 0;
                setTimeout(function() {
                    flashMessage.remove();
                }, 500);
            }
        }, 5000);
    </script>
<?php endif; ?>

<div id="wrapper">
    <div class="content">
        <div class="row">
            <div class="col-md-12">
                <div class="panel_s">
                    <div class="panel-body _buttons">
                        <div class="row">
                            <div class="col-md-6">
                                <?php if (has_permission(BIZIT_SERVICES_MSL, '', 'create')): ?>
                                    <a href="#" class="btn btn-info pull-left" data-toggle="modal" data-target="#warranty_modal">
                                        <i class="fa fa-plus"></i> <?= _l('New Warranty'); ?>
                                    </a>
                                <?php endif; ?>
                            </div>                           
                        </div>
                    </div>
                </div>

                <div class="panel_s table-responsive">
                    <div class="panel-body table-responsive">
                        <table id="warrantyTable" class="table table-bordered table-striped" width="100%">
                            <thead>
                                <tr>
                                    <th><?= _l('Product Code'); ?></th>
                                    <th><?= _l('Product Name'); ?></th>
                                    <th><?= _l('Product Serial No.'); ?></th>
                                    <th><?= _l('Date Sold'); ?></th>
                                    <th><?= _l('Warranty Days Remaining'); ?></th>
                                    <th><?= _l('Warranty End Date'); ?></th>
                                    <th><?= _l('Options'); ?></th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php foreach ($new_warranty_services as $service): ?>
                                    <tr>
                                        <td><?= $service['commodity_code']; ?></td>
                                        <td><?= $service['name']; ?></td>
                                        <td><?= $service['serial_number']; ?></td>
                                        <td><?= $service['date_sold']; ?></td>
                                        <td class="text-center"><?= $service['warranty_days_remaining']; ?></td>
                                        <td><?= $service['warranty_end_date']; ?></td>
                                        <td>
                                                <?php if (has_permission(BIZIT_SERVICES_MSL, '', 'view')): ?>
                                                    <a href="<?= base_url('admin/services/warranty_pdf/' . $service['serial_number'] . '?print=true'); ?>" class="btn btn-info" title="View PDF">
                                                        <i class="fa fa-download"></i>
                                                    </a>
                                                    <?php if (check_report($service['serviceid'])): ?>
                                                        <a href="<?= base_url('services/report/view/' . $service['serial_number']); ?>" class="btn btn-danger" title="Service Report">
                                                            <i class="fa fa-bar-chart"></i>
                                                        </a>
                                                    <?php endif; ?>
                                                <?php endif; ?>
                                                <?php if (has_permission(BIZIT_SERVICES_MSL, '', 'delete')): ?>
                                                    <a href="<?= base_url('admin/services/delete_warranty/' . $service['serial_number']); ?>" class="btn btn-danger _delete">
                                                        <i class="fa fa-remove"></i>
                                                    </a>
                                                <?php endif; ?>
                                            </td>

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

<?php $this->load->view('admin/services/modal_warranty'); ?>
<?php init_tail(); ?>


<script src="https://code.jquery.com/jquery-3.6.4.min.js"></script>
<script src="https://cdn.datatables.net/1.13.5/js/jquery.dataTables.min.js"></script>
<!-- <script src="../assets/builds/vendor-admin.js"></script> -->

<script>
    // Your custom script
    $(document).ready(function () {
        var table = $('#warrantyTable').DataTable();

        $('#searchWarranty').on('keyup', function () {
            table.search(this.value).draw();
        });
    });
</script>
</body>
</html>
