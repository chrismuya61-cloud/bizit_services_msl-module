<div class="modal fade" id="warranty_modal" role="dialog" aria-labelledby="myModalLabel"   data-backdrop="static">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                <h4 class="modal-title" id="myModalLabel">
                    <span class="add-title"><?php echo _l('New Warranty'); ?></span>
                </h4>
            </div>                   
            <?php echo form_open('admin/services/view_warranty', array('id' => 'warranty_form')); ?>
            <div class="modal-body">
                <div class="row">
                <div class="col-md-12">
    <div class="row">
        <div class="col-lg-4 col-md-6">
            <div class="form-group">
                <label for="name"><?php echo _l('Select Product'); ?></label>
                <select id="name" name="name" class="form-control" onchange="fetchServiceIdAndCode();">
                    <option value="" disabled selected><?php echo _l('Select a Product'); ?></option>
                    <?php foreach ($warranty_services as $item): ?>
                        <option 
                            value="<?php echo $item['name']; ?>" 
                            data-service-id="<?php echo $item['commodity_barcode']; ?>" 
                            data-service-code="<?php echo $item['service_code']; ?>" 
                            data-commodity-code="<?php echo $item['commodity_code']; ?>">
                            <?php echo $item['name']; ?>
                        </option>
                    <?php endforeach; ?>
                </select>
            </div>
        </div>

        <!-- Hidden field for commodity_barcode -->
        <?php echo form_hidden('serviceid', '', ['id' => 'serviceid']); ?>

        <script>
    function fetchServiceIdAndCode() {
        var selectedOption = document.querySelector('#name option:checked');
        
        // Retrieve attributes from the selected option
        var commodityBarcode = selectedOption ? selectedOption.getAttribute("data-service-id") : '';
        var serviceCode = selectedOption ? selectedOption.getAttribute("data-service-code") : '';
        var commodityCode = selectedOption ? selectedOption.getAttribute("data-commodity-code") : '';

        console.log('Commodity Barcode:', commodityBarcode);
        console.log('Service Code:', serviceCode);
        console.log('Commodity Code:', commodityCode);

        var serviceIdElement = document.getElementById("serviceid");
        var commodityCodeElement = document.getElementById("commodity_code");
        var serialNumberSelect = document.getElementById("serial_number_select");

        if (serviceIdElement) serviceIdElement.value = commodityBarcode;
        if (commodityCodeElement) commodityCodeElement.value = commodityCode;

        // Clear previous options from the select dropdown
        if (serialNumberSelect) {
            serialNumberSelect.innerHTML = '<option value="">Select Serial Number</option>';
        }

        // Fetch serial numbers via AJAX if commodityCode exists
        if (commodityCode) {
            $.ajax({
                url: "<?php echo base_url('admin/services/get_serial_numbers_by_commodity_code'); ?>",
                type: "POST",
                data: {commodity_code: commodityCode},
                dataType: "json",
                success: function (response) {
                    if (response.length > 0 && serialNumberSelect) {
                        response.forEach(function (item) {
                            var option = document.createElement('option');
                            option.value = item.serial_number;
                            option.textContent = item.serial_number;
                            serialNumberSelect.appendChild(option);
                        });
                    } else if (serialNumberSelect) {
                        var option = document.createElement('option');
                        option.value = "";
                        option.textContent = "No serial numbers available";
                        serialNumberSelect.appendChild(option);
                    }
                },
                error: function () {
                    if (serialNumberSelect) {
                        var option = document.createElement('option');
                        option.value = "";
                        option.textContent = "Error fetching serial numbers";
                        serialNumberSelect.appendChild(option);
                    }
                }
            });
        }
    }
</script>



        <div class="col-lg-4 col-md-6">
            <div class="form-group">
                <label class="control-label" for="commodity_code"><?php echo _l('Product Code'); ?></label>
                <input type="text" id="commodity_code" name="commodity_code" class="form-control" readonly placeholder="Product Code">
            </div>
        </div>
        <div class="col-lg-4 col-md-6">
    <div class="form-group">
        <label class="control-label" for="serial_number_select"><?php echo _l('Product Serial No.'); ?></label>
        <select id="serial_number_select" name="serial_number" class="form-control">
            <option value="">Select Serial Number</option>
        </select>
    </div>
</div>

        <!-- <script>
            function fetchServiceIdAndCode() {
                var selectedOption = document.querySelector('#name option:checked');
                var commodityCode = selectedOption ? selectedOption.getAttribute("data-commodity-code") : '';
                
                if (commodityCode) {
                    // Fetch serial numbers via AJAX
                    $.ajax({
                        url: "<?php echo base_url('admin/services/get_serial_numbers_by_commodity_code'); ?>",
                        type: "POST",
                        data: {commodity_code: commodityCode},
                        dataType: "json",
                        success: function (response) {
                            if (response.length > 0) {
                                var serialNumbers = response.map(item => item.serial_number).join(', ');
                                document.getElementById('service_code').value = serialNumbers;
                            } else {
                                document.getElementById('service_code').value = "No serial numbers available";
                            }
                        },
                        error: function () {
                            document.getElementById('service_code').value = "Error fetching serial numbers";
                        }
                    });
                }
            }
        </script> -->

    </div>
</div>

                            
                                                       
                            <div class="col-lg-4 col-md-6">
                                <div class="form-group">
                                    <label class="control-label" for="date_sold"><?php echo _l('Date Sold'); ?></label>
                                    <input type="date" id="date_sold" name="date_sold" class="form-control" placeholder="Date Sold">
                                </div>
                            </div>
                            <div class="col-lg-4 col-md-6">
                                <div class="form-group">
                                    <label class="control-label" for="warranty_days_remaining"><?php echo _l('Warranty Days Remaining'); ?></label>
                                    <input type="number" id="warranty_days_remaining" name="warranty_days_remaining" class="form-control" readonly placeholder="Warranty Days Remaining">
                                </div>
                            </div>
                            <div class="col-lg-4 col-md-6">
                                <div class="form-group">
                                    <label class="control-label" for="warranty_end_date"><?php echo _l('Warranty End Date'); ?></label>
                                    <input type="date" id="warranty_end_date" name="warranty_end_date" class="form-control" readonly placeholder="Warranty End Date">
                                </div>
                            </div>

                            <script>
                                document.getElementById('date_sold').addEventListener('change', function() {
                                    const dateSold = new Date(this.value);
                                    if (!isNaN(dateSold.getTime())) {
                                        // Calculate the warranty end date (1 year later)
                                        const warrantyEndDate = new Date(dateSold);
                                        warrantyEndDate.setFullYear(warrantyEndDate.getFullYear() + 1);

                                        // Set the end date in the input field
                                        document.getElementById('warranty_end_date').value = warrantyEndDate.toISOString().split('T')[0];

                                        // Calculate remaining days
                                        const today = new Date();
                                        const remainingDays = Math.floor((warrantyEndDate - today) / (1000 * 60 * 60 * 24));
                                        document.getElementById('warranty_days_remaining').value = Math.max(remainingDays, 0);
                                    }
                                });
                            </script>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <?php echo render_textarea('description','description'); ?>
                                </div>
                            </div>
                           <div class="quantity_unit_area hide">                            
                                <div class="col-md-6">
                                    <div class="form-group">
                                    <label class="control-label" for="service_unit">Quantity Unit</label>
                                    <input type="text" id="quantity_unit" name="quantity_unit" class="form-control" placeholder="e.g. PCs, Sevices etc.">
                                    </div>
                                </div> 
                           </div>
                           <div class="rental_row hide">
                                <div class="col-md-6">
                                    <div class="form-group">
                                    <label class="control-label" for="name"><?php echo _l('penalty_price'); ?></label>
                                    <div class="input-group">
                                        <span class="input-group-addon">
                                        <?php echo $currency_symbol; ?></span>
                                     <input type="text" id="penalty_rental_price" name="penalty_rental_price" class="form-control" placeholder="Rental Extra Duration Price">
                                    </div>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group">
                                    <label class="control-label" for="name"><?php echo _l('rental_serial'); ?></label>
                                    <input type="text" id="rental_serial" name="rental_serial" class="form-control" placeholder="Serial Number">
                                    </div>
                                </div>
                                <div class="col-md-6">
                                 <div class="form-group">
                                    <label class="control-label" for="rental_duration_check"><?php echo _l('rental_duration_check'); ?></label>
                                    <select class="selectpicker display-block" data-width="100%" name="rental_duration_check" id="rental_duration_check">
                                        <option value="hours">Hourly</option>
                                        <option value="days">Daily</option>
                                        <option value="weeks">Weekly</option>
                                        <option value="months">Monthly</option>
                                        <option value="years">Annually</option>
                                    </select>
                                </div>
                                </div>
                                
                                <div class="col-md-6">
                                 <div class="form-group">
                                    <label class="control-label" for="rental_status"><?php echo _l('rental_status'); ?></label>
                                    <select class="selectpicker display-block" data-width="100%" name="rental_status" id="rental_status">
                                        <option value="Not-Hired">Not Hired</option>
                                        <option value="Hired">Hired</option>
                                    </select>
                                </div>
                                </div>
                           </div>
                                
                        </div>               
            </div>
        </div> 
        <div class="clearfix mbot10"></div>
        <div class="modal-footer">
        <button type="button" id="reset_close" class="btn btn-default" data-dismiss="modal"><?php echo _l('close'); ?></button>
        <button type="submit" class="btn btn-info" id="submit"><?php echo _l('submit'); ?></button>       
        </div>
    </div>
        <?php echo form_close(); ?> 
    
</div>
</div>
</div>
