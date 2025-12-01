<?php echo form_open('admin/services/save_calibration',array('id'=>"service_calibration_report_form",'method'=>'post'));?>
   <div class="">
        <div class="box-body">
            <div class="row">
                <div class="col-md-12">
                <div class="table-responsive">
                <button><b>INSTRUMENT INFORMATION REPORT ENTRY</b></button>
                    <table class="table" id="serviceFields">
                        <hr>
                        <tbody>
                            <tr class="form-inline">
                                <td class="align-middle text-left" style="padding: 10px; border: 1px solid #ccc;">INSTRUMENT MAKE
                                    <div class="form-group">
                                        <div class="input-group">
                                            <input type="text" class="form-control required" autocomplete="on" placeholder="BOSCH" id="_ls_v_a_1" name="ls_v_a_1" value="<?php if(!empty($calibration_info)) {echo $calibration_info->ls_v_a_1;} ?>" required>
                                        </div>
                                    </div>
                                </td>
                                <td class="align-middle text-left" style="padding: 10px; border: 1px solid #ccc;">INSTRUMENT MODEL<br>
                                    <div class="form-group">
                                        <div class="input-group text-center">
                                            <input type="text" class="form-control required" autocomplete="on" placeholder="GLL2" name="ls_v_a_2" value="<?php if(!empty($calibration_info)) {echo $calibration_info->ls_v_a_2;} ?>" required>
                                        </div>
                                    </div>
                                </td>
                                <td class="align-middle text-left" style="padding: 10px; border: 1px solid #ccc;">INSTRUMENT SERIAL NO.
                                    <div class="form-group">
                                        <div class="input-group text-center">
                                            <input type="text" class="form-control required" autocomplete="on" placeholder="SGT57589" name="ls_v_a_3" value="<?php if(!empty($calibration_info)) {echo $calibration_info->ls_v_a_3;} ?>" required>
                                        </div>
                                    </div>
                                </td>
                                <td class="align-middle text-left" style="padding: 10px; border: 1px solid #ccc;">INSTRUMENT CONDITION
                                    <div class="form-group">
                                        <div class="input-group text-center">
                                            <input type="text" class="form-control required" autocomplete="on" placeholder="USED" name="ls_v_a_4" value="<?php if(!empty($calibration_info)) {echo $calibration_info->ls_v_a_4;} ?>" required>
                                        </div>
                                    </div>
                                </td>
                            </tr>
                            <tr class="form-inline">
                                <td class="align-middle text-left" style="padding: 10px; border: 1px solid #ccc;">TEST DISTANCE (M)
                                    <div class="form-group">
                                        <div class="input-group text-center">
                                            <input type="text" class="form-control required" autocomplete="on" placeholder="60" name="ls_v_a_5" value="<?php if(!empty($calibration_info)) {echo $calibration_info->ls_v_a_5;} ?>" required>
                                        </div>
                                    </div>
                                </td>
                                <td class="align-middle text-left" style="padding: 10px; border: 1px solid #ccc;">MANUFACTURER EDM ACCURACY (MM)
                                    <div class="form-group">
                                        <div class="input-group text-center">
                                            <input type="text" class="form-control required" autocomplete="on" placeholder="1.5" name="ls_v_a_6" value="<?php if(!empty($calibration_info)) {echo $calibration_info->ls_v_a_6;} ?>" required>
                                        </div>
                                    </div>
                                </td>
                                <td class="align-middle text-left" style="padding: 10px; border: 1px solid #ccc;">WEATHER CONDITION
                                    <div class="form-group">
                                        <div class="input-group text-center">
                                            <input type="text" class="form-control required" autocomplete="on" placeholder="SUNNY" name="ls_v_a_7" value="<?php if(!empty($calibration_info)) {echo $calibration_info->ls_v_a_7;} ?>" required>
                                        </div>
                                    </div>
                                </td>
                                <td class="align-middle text-left" style="padding: 10px; border: 1px solid #ccc;">TEMPERATURE (°C)
                                    <div class="form-group">
                                        <div class="input-group text-center">
                                            <input type="text" class="form-control required" autocomplete="on" placeholder="20" name="ls_v_a_8" value="<?php if(!empty($calibration_info)) {echo $calibration_info->ls_v_a_8;} ?>" required>
                                        </div>
                                    </div>
                                </td>
                            </tr>
                            <tr class="form-inline">
                                <td class="align-middle text-left" style="padding: 10px; border: 1px solid #ccc;">AIR PRESSURE (hPa)
                                    <div class="form-group">
                                        <div class="input-group text-center">
                                            <input type="text" class="form-control required" autocomplete="on" placeholder="101" name="ls_v_a_9" value="<?php if(!empty($calibration_info)) {echo $calibration_info->ls_v_a_9;} ?>" required>
                                        </div>
                                    </div>
                                </td>
                            </tr>                           
                        </tbody>
                    </table>
                    
                    <div class="table">
                    <button><h5><b>PRE-CALIBRATION CHECKS</b></h5></button>
                    <table class="table" id="serviceFields">
                            <thead>
                                <tr>
                                <th colspan="3" class="text-center" style="font-weight:500;">HORIZONTAL HEIGHT (UP AND DOWN DEVIATION) ACCURACY ERROR</th>
                                <th colspan="3" class="text-center" style="font-weight:500;">HORIZONTAL LEVEL (SIDE TO SIDE INCLINATION) ACCURACY ERROR</th>
                                </tr>
                                <tr>
                                <th class="text-center" style="font-weight:500;"></th>
                                <th class="text-center" style="font-weight:500;">OBSERVATIONS</th>
                                <th class="text-center" style="font-weight:500;"></th>
                                <th class="text-center" style="font-weight:500;"></th>
                                <th class="text-center" style="font-weight:500;">OBSERVATIONS</th>

                                </tr>
                                <tr>
                                    <th class="text-center" style="font-weight:500;">Station</th>
                                    <th class="text-center" style="font-weight:500;">BS A(mm)</th>
                                    <th class="text-center" style="font-weight:500;">FS B(mm)</th>
                                    <th class="text-center" style="font-weight:500;">Station</th>
                                    <th class="text-center" style="font-weight:500;">A(mm)</th>
                                    <th class="text-center" style="font-weight:500;">B(mm)</th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr>
                                    <td class="text-center" style="font-weight:500;">1</td>
                                    <td>
                                        <div class="form-group form-group-bottom">
                                            <input class="form-control required" id="hh_bsa1" value="<?php if(!empty($calibration_info)) {echo $calibration_info->hh_bsa1;} ?>" name="hh_bsa1" type="text" oninput="calculateDifference()">
                                        </div>
                                    </td>
                                    <td>
                                        <div class="form-group form-group-bottom">
                                            <input class="form-control required" id="hh_fsa1" value="<?php if(!empty($calibration_info)) {echo $calibration_info->hh_fsa1;} ?>" name="hh_fsa1" type="text" oninput="calculateFSADifference()">
                                        </div>
                                    </td>
                                    <td class="text-center" style="font-weight:500;">1</td>
                                    <td>
                                        <div class="form-group form-group-bottom">
                                            <input class="form-control required" value="<?php if(!empty($calibration_info)) {echo $calibration_info->hl_bsa1;} ?>" name="hl_bsa1" type="text">
                                        </div>
                                    </td>
                                    <td>
                                        <div class="form-group form-group-bottom">
                                            <input class="form-control required" value="<?php if(!empty($calibration_info)) {echo $calibration_info->hl_fsa1;} ?>" name="hl_fsa1" type="text">
                                        </div>
                                    </td>
                                </tr>
                                <tr>
                                    <td class="text-center" style="font-weight:500;">2</td>
                                    <td>
                                        <div class="form-group form-group-bottom">
                                            <input class="form-control required" id="hh_bsa2" value="<?php if(!empty($calibration_info)) {echo $calibration_info->hh_bsa2;} ?>" name="hh_bsa2" type="text" oninput="calculateDifference()">
                                        </div>
                                    </td>
                                    <td>
                                        <div class="form-group form-group-bottom">
                                            <input class="form-control required" id="hh_fsa2" value="<?php if(!empty($calibration_info)) {echo $calibration_info->hh_fsa2;} ?>" name="hh_fsa2" type="text" oninput="calculateFSADifference()">
                                        </div>
                                    </td>
                                    <th colspan="3" class="text-center" style="font-weight:500;">VERTICAL LEVEL (SIDE TO SIDE INCLINATION) ACCURACY ERROR<br>OBSERVATIONS </th>

                                </tr>
                                <tr>
                                    <td class="text-center" style="font-weight:500;">Difference (mm)</td>
                                    <td>
                                        <div class="form-group form-group-bottom">
                                            <input class="form-control required" id="difference_bsa" value="" name="difference_bsa" type="text"  disabled>
                                        </div>
                                    </td>
                                    <td>
                                        <div class="form-group form-group-bottom">
                                            <input class="form-control required" id="difference_fsb" value=""  name="difference_fsb" type="text"  disabled>
                                        </div>
                                    </td>
                                    <td class="text-center" style="font-weight:500;">1</td>
                                    <td>
                                        <div class="form-group form-group-bottom">
                                            <input class="form-control required" value="<?php if(!empty($calibration_info)) {echo $calibration_info->vl_bsa1;} ?>" name="vl_bsa1" type="text">
                                        </div>
                                    </td>
                                    <td>
                                        <div class="form-group form-group-bottom">
                                            <input class="form-control required" value="<?php if(!empty($calibration_info)) {echo $calibration_info->vl_fsa1;} ?>" name="vl_fsa1" type="text">
                                        </div>
                                    </td>
                                </tr>
                               
                            </tbody>
                    </table>

                    <script>
                        function calculateDifference() {
                            // Get the values from the input fields
                            let hh_bsa1 = parseFloat(document.getElementById('hh_bsa1').value) || 0;
                            let hh_bsa2 = parseFloat(document.getElementById('hh_bsa2').value) || 0;
                            
                            // Calculate the difference
                            let difference = hh_bsa1 - hh_bsa2;
                            
                            // Display the result in the difference input field
                            document.getElementById('difference_bsa').value = difference.toFixed(0);
                        }

                        function calculateFSADifference() {
                            // Get the values from the input fields
                            let hh_fsa1 = parseFloat(document.getElementById('hh_fsa1').value) || 0;
                            let hh_fsa2 = parseFloat(document.getElementById('hh_fsa2').value) || 0;
                            
                            // Calculate the difference
                            let difference = hh_fsa1 - hh_fsa2;
                            
                            // Display the result in the difference input field
                            document.getElementById('difference_fsb').value = difference.toFixed(0);
                        }
                    </script>

                    </div>
                    <div class="table">
                    <button><h5><b>POST-CALIBRATION CHECKS</b></h5></button>
                    <table class="table" id="serviceFields">
                            <thead>
                                <tr>
                                <th colspan="3" class="text-center" style="font-weight:500;">HORIZONTAL HEIGHT (UP AND DOWN DEVIATION) ACCURACY ERROR</th>
                                <th colspan="3" class="text-center" style="font-weight:500;">HORIZONTAL LEVEL (SIDE TO SIDE INCLINATION) ACCURACY ERROR</th>
                                </tr>
                                <tr>
                                <th class="text-center" style="font-weight:500;"></th>
                                <th class="text-center" style="font-weight:500;">OBSERVATIONS</th>
                                <th class="text-center" style="font-weight:500;"></th>
                                <th class="text-center" style="font-weight:500;"></th>
                                <th class="text-center" style="font-weight:500;">OBSERVATIONS</th>

                                </tr>
                                <tr>
                                    <th class="text-center" style="font-weight:500;">Station</th>
                                    <th class="text-center" style="font-weight:500;">BS A(mm)</th>
                                    <th class="text-center" style="font-weight:500;">FS B(mm)</th>
                                    <th class="text-center" style="font-weight:500;">Station</th>
                                    <th class="text-center" style="font-weight:500;">BS A(mm)</th>
                                    <th class="text-center" style="font-weight:500;">FS B(mm)</th>
                                </tr>
                            </thead>
                            <tbody>
                            <tr>
                                    <td class="text-center" style="font-weight:500;">1</td>
                                    <td>
                                        <div class="form-group form-group-bottom">
                                            <input class="form-control required" id="hh_bsa3" value="<?php if(!empty($calibration_info)) {echo $calibration_info->hh_bsa3;} ?>" name="hh_bsa3" type="text" oninput="calculateBSADifference()">
                                        </div>
                                    </td>
                                    <td>
                                        <div class="form-group form-group-bottom">
                                            <input class="form-control required" id="hh_fsa3" value="<?php if(!empty($calibration_info)) {echo $calibration_info->hh_fsa3;} ?>" name="hh_fsa3" type="text" oninput="calculateFSADifference()">
                                        </div>
                                    </td>
                                    <td class="text-center" style="font-weight:500;">1</td>
                                    <td>
                                        <div class="form-group form-group-bottom">
                                            <input class="form-control required" value="<?php if(!empty($calibration_info)) {echo $calibration_info->hl_bsa2;} ?>" name="hl_bsa2" type="text">
                                        </div>
                                    </td>
                                    <td>
                                        <div class="form-group form-group-bottom">
                                            <input class="form-control required" value="<?php if(!empty($calibration_info)) {echo $calibration_info->hl_fsa2;} ?>" name="hl_fsa2" type="text">
                                        </div>
                                    </td>
                                </tr>
                                <tr>
                                    <td class="text-center" style="font-weight:500;">2</td>
                                    <td>
                                        <div class="form-group form-group-bottom">
                                            <input class="form-control required" id="hh_bsa4" value="<?php if(!empty($calibration_info)) {echo $calibration_info->hh_bsa4;} ?>" name="hh_bsa4" type="text" oninput="calculateBSADifference()">
                                        </div>
                                    </td>
                                    <td>
                                        <div class="form-group form-group-bottom">
                                            <input class="form-control required" id="hh_fsa4" value="<?php if(!empty($calibration_info)) {echo $calibration_info->hh_fsa4;} ?>" name="hh_fsa4" type="text" oninput="calculateFSADifference()">
                                        </div>
                                    </td>
                                    <th colspan="3" class="text-center" style="font-weight:500;">VERTICAL LEVEL (SIDE TO SIDE INCLINATION) ACCURACY ERROR<br>OBSERVATIONS </th>

                                </tr>
                                <tr>
                                    <td class="text-center" style="font-weight:500;">Difference (mm)</td>
                                    <td>
                                        <div class="form-group form-group-bottom">
                                            <input class="form-control required" id="difference_bsa1" value="" name="difference_bsa1" type="text" disabled>
                                        </div>
                                    </td>
                                    <td>
                                        <div class="form-group form-group-bottom">
                                            <input class="form-control required" id="difference_fsb1" value="" name="difference_fsb1" type="text" disabled>
                                        </div>
                                    </td>
                                    <td class="text-center" style="font-weight:500;">1</td>
                                    <td>
                                        <div class="form-group form-group-bottom">
                                            <input class="form-control required" value="<?php if(!empty($calibration_info)) {echo $calibration_info->vl_bsa2;} ?>" name="vl_bsa2" type="text">
                                        </div>
                                    </td>
                                    <td>
                                        <div class="form-group form-group-bottom">
                                            <input class="form-control required" value="<?php if(!empty($calibration_info)) {echo $calibration_info->vl_fsa2;} ?>" name="vl_fsa2" type="text">
                                        </div>
                                    </td>
                                </tr>
                                <!-- <tr style="background-color: ;">
                                    <td class="text-center" style="font-weight:500;">Distance (mm)</td>
                                    <td colspan="2">
                                        <div class="form-group form-group-bottom">
                                            <input class="form-control required" value="-1" name="distance" type="text" style="background-color: lightgreen;" disabled>
                                        </div>
                                    </td>
                                </tr> -->
                            </tbody>
                    </table>

                        <script>
                            function calculateBSADifference() {
                                const bsa3 = parseFloat(document.getElementById('hh_bsa3').value) || 0;
                                const bsa4 = parseFloat(document.getElementById('hh_bsa4').value) || 0;
                                document.getElementById('difference_bsa1').value = (bsa3 - bsa4).toFixed(0);
                            }

                            function calculateFSADifference() {
                                const fsa3 = parseFloat(document.getElementById('hh_fsa3').value) || 0;
                                const fsa4 = parseFloat(document.getElementById('hh_fsa4').value) || 0;
                                document.getElementById('difference_fsb1').value = (fsa3 - fsa4).toFixed(0);
                            }
                        </script>
                    </div>
                </div>
            </div>
             <hr>
            <div class="row">
                <div class="form-group col-md-12">
                    <label class="control-label" style="font-weight:500;">REPORT REMARKS</label>
                    <textarea class="form-control" name="calibration_remark"><?php if(!empty($calibration_info)) {echo $calibration_info->calibration_remark;} ?></textarea>
                </div>
            </div>
        </div>
        <!-- /.box-body -->
    </div>
    <div class="btn-bottom-toolbar bottom-transaction text-right hide">
        <button class="btn-tr btn btn-info mleft10 text-right pull-right" id="submit">Generate Report</button>
        </button>
    </div>
    <!-- hidden field -->
    <input type="hidden" value="<?php echo $service_request_code; ?>" name="service_code">
    <input type="hidden" value="<?php echo $service_info->item_type; ?>" name="calibration_instrument">
    <?php if(!empty($calibration_info)) {?>
    <input type="hidden" value="<?php echo $calibration_info->calibration_id; ?>" name="edit_id">
    <?php } ?>
</form>
