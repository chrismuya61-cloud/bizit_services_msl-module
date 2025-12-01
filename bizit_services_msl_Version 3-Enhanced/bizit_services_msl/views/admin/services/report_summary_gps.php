<?php echo form_open('admin/services/save_calibration',array('id'=>"service_calibration_report_form",'method'=>'post'));?>
  <div class="">
    <div class="box-body">
      <div class="row">
        <div class="col-md-12">
          <style type="text/css">
          .form-group .input-group-addon {
          background-color: #eee;
          }

          .form-section {
            display: none;
        }
        .form-section.current {
            display: block;
        }
          
          </style>
          <!-- Table 1 -->
          <div class="form-section current">
            <div class="table-responsive">
            <b>INSTRUMENT INFORMATION REPORT ENTRY</b>
            <table class="table" id="serviceFields">
                <hr>
                <tbody>
                    <tr class="form-inline">
                        <td class="align-middle text-left" style="padding: 10px; border: 1px solid #ccc;">INSTRUMENT MAKE
                            <div class="form-group">
                                <div class="input-group">
                                    <input type="text" class="form-control required" autocomplete="on" placeholder="" id="_r_v_a_1" name="r_v_a_1" value="<?php if(!empty($calibration_info)) {echo $calibration_info->r_v_a_1;} ?>" required>
                                </div>
                            </div>
                        </td>
                        <td class="align-middle text-left" style="padding: 10px; border: 1px solid #ccc;">INSTRUMENT MODEL
                            <div class="form-group">
                                <div class="input-group text-center">
                                    <input type="text" class="form-control required" autocomplete="on" placeholder="AL132" name="r_v_a_2" value="<?php if(!empty($calibration_info)) {echo $calibration_info->r_v_a_2;} ?>" required>
                                </div>
                            </div>
                        </td>
                        <td class="align-middle text-left" style="padding: 10px; border: 1px solid #ccc;">INSTRUMENT SERIAL NO.
                            <div class="form-group">
                                <div class="input-group text-center">
                                    <input type="text" class="form-control required" autocomplete="on" placeholder="Z6692H99" name="r_v_a_3" value="<?php if(!empty($calibration_info)) {echo $calibration_info->r_v_a_3;} ?>" required>
                                </div>
                            </div>
                        </td>
                        <td class="align-middle text-left" style="padding: 10px; border: 1px solid #ccc;">INSTRUMENT CONDITION
                            <div class="form-group">
                                <div class="input-group text-center">
                                    <input type="text" class="form-control required" autocomplete="on" placeholder="USED" name="r_v_a_4" value="<?php if(!empty($calibration_info)) {echo $calibration_info->r_v_a_4;} ?>" required>
                                </div>
                            </div>
                        </td>
                    </tr>
                    <tr class="form-inline">
                        <td class="align-middle text-left" style="padding: 10px; border: 1px solid #ccc;">TEST DISTANCE (M)
                            <div class="form-group">
                                <div class="input-group text-center">
                                    <input type="text" class="form-control required" autocomplete="on" placeholder="20" name="r_v_a_5" value="<?php if(!empty($calibration_info)) {echo $calibration_info->r_v_a_5;} ?>" required>
                                </div>
                            </div>
                        </td>
                        <td class="align-middle text-left" style="padding: 10px; border: 1px solid #ccc;">WEATHER CONDITION
                            <div class="form-group">
                                <div class="input-group text-center">
                                    <input type="text" class="form-control required" autocomplete="on" placeholder="CLOUDY" name="r_v_a_6" value="<?php if(!empty($calibration_info)) {echo $calibration_info->r_v_a_6;} ?>" required>
                                </div>
                            </div>
                        </td>
                        <td class="align-middle text-left" style="padding: 10px; border: 1px solid #ccc;">TEMPERATURE (°C)
                            <div class="form-group">
                                <div class="input-group text-center">
                                    <input type="text" class="form-control required" autocomplete="on" placeholder="32" name="r_v_a_7" value="<?php if(!empty($calibration_info)) {echo $calibration_info->r_v_a_7;} ?>" required>
                                </div>
                            </div>
                        </td>
                        <td class="align-middle text-left" style="padding: 10px; border: 1px solid #ccc;">AIR PRESSURE (hPa)
                            <div class="form-group">
                                <div class="input-group text-center">
                                    <input type="text" class="form-control required" autocomplete="on" placeholder="101" name="r_v_a_8" value="<?php if(!empty($calibration_info)) {echo $calibration_info->r_v_a_8;} ?>" required>
                                </div>
                            </div>
                        </td>
                    </tr>
                    <tr class="form-inline">
                        <td class="align-middle text-left" style="padding: 10px; border: 1px solid #ccc;">BASELINE HORIZONTAL DISTANCE (M)
                            <div class="form-group">
                                <div class="input-group text-center">
                                    <input type="text" class="form-control required" autocomplete="on" placeholder="19.994" name="r_v_a_9" value="<?php if(!empty($calibration_info)) {echo $calibration_info->r_v_a_9;} ?>" required>
                                </div>
                            </div>
                        </td>
                        <td class="align-middle text-left" style="padding: 10px; border: 1px solid #ccc;">BASELINE ELEVATION ACCURACY (MM)
                            <div class="form-group">
                                <div class="input-group text-center">
                                    <input type="text" class="form-control required" autocomplete="on" placeholder="0.028" name="r_v_a_10" value="<?php if(!empty($calibration_info)) {echo $calibration_info->r_v_a_10;} ?>" required>
                                </div>
                            </div>
                        </td>
                        <td class="align-middle text-left" style="padding: 10px; border: 1px solid #ccc;">MANUFACTURER HRMS ACCURACY (MM)
                            <div class="form-group">
                                <div class="input-group text-center">
                                    <input type="text" class="form-control required" autocomplete="on" placeholder="15" name="r_v_a_11" value="<?php if(!empty($calibration_info)) {echo $calibration_info->r_v_a_11;} ?>" required>
                                </div>
                            </div>
                        </td>
                        <td colspan="2" class="align-middle text-left" style="padding: 10px; border: 1px solid #ccc;">MANUFACTURER VRMS ACCURACY (MM)
                            <div class="form-group">
                                <div class="input-group text-center">
                                    <input type="text" class="form-control required" autocomplete="on" placeholder="25" name="r_v_a_12" value="<?php if(!empty($calibration_info)) {echo $calibration_info->r_v_a_12;} ?>" required>
                                </div>
                            </div>
                        </td>
                    </tr>
                    <tr class="form-inline">
                        <td class="align-middle text-left" style="padding: 10px; border: 1px solid #ccc;">INSTRUMENT HEIGHT (M)
                            <div class="form-group">
                                <div class="input-group text-center">
                                    <input type="text" class="form-control required" autocomplete="on" placeholder="2.2" name="r_v_a_13" value="<?php if(!empty($calibration_info)) {echo $calibration_info->r_v_a_13;} ?>" required>
                                </div>
                            </div>
                        </td>
                    </tr>
                </tbody>
            </table>
            </div>
            <button type="button" class="btn btn-primary next-step">Next</button>
            </div>
            <hr>
            <!-- Table A2 -->
            <div class="form-section">
            <b>PRE-CALIBRATION CHECKS REPORT ENTRY 1</b>
            <table class="table" id="serviceFields">
                <thead>
                    <tr>
                        <th class="text-left" style="font-weight:500;">Series</th>
                        <th class="text-left" style="font-weight:500;">Seq. No.</th>
                        <th class="text-left" style="font-weight:500;">Set</th>
                        <th class="text-left" style="font-weight:500;">Rover Point </th>
                        <th class="text-left" style="font-weight:500;">Eastings</th>
                        <th class="text-left" style="font-weight:500;">Northings</th>
                        <th class="text-left" style="font-weight:500;">Elevation</th>
                        <th class="text-left" style="font-weight:500;">Horizontal Distance (m)</th>
                        <th class="text-left" style="font-weight:500;">Elevation Accuracy (mm)</th>
                    </tr>
                </thead>
                <tbody>
                    <tr class="form-inline">
                        <td class="align-middle text-center" style="font-weight:500;"></td>
                        <td class="align-middle text-center">
                            <div class="form-group ">
                                <div class="input-group text-center">
                                <input type="text"  class="form-control required" autocomplete="off" id="exampleInputAmount" placeholder="2" name='ii_v_cc[]' value="START TIME" disabled>
                                </div>
                            </div>
                        </td>
                        <td class="align-middle text-center">
                            <div class="form-group">
                                <div class="input-group text-center">
                                    <input type="time" class="form-control required" autocomplete="off" id="start_time_1" name="start_time_1" value="<?php if(!empty($calibration_info)) {echo date('H:i', strtotime($calibration_info->start_time_1));} ?>">
                                </div>
                            </div>
                        </td>

                        <td class="align-middle text-left">
                        <div class="form-group">
                                <div class="input-group">
                                <input type="text"  class="form-control required" autocomplete="off" id="exampleInputAmount" placeholder="" name='ii_v_cc[]' value="" disabled>
                                </div>
                            </div>
                        </td>
                        <td class="align-middle text-left">
                        <div class="form-group">
                                <div class="input-group">
                                <input type="text"  class="form-control required" autocomplete="off" id="_i_v_cc_1" placeholder="" name='i_v_cc_1' value="" disabled>
                                </div>
                            </div>
                        </td>
                        <td class="align-middle text-left">
                        <div class="form-group">
                                <div class="input-group">
                                <input type="text"  class="form-control required" autocomplete="off" id="_i_v_cc_2" placeholder="" name='i_v_cc_2' value="" disabled>
                                </div>
                            </div>
                        </td>
                        <td class="align-middle text-left">
                            <div class="form-group">
                                <div class="input-group">
                                <input type="text"  class="form-control required" autocomplete="off" id="_i_v_cc_3" placeholder="" name='i_v_cc_3' value="" disabled>
                                </div>
                            </div>
                        </td>
                        <td class="align-middle text-left">
                        <div class="form-group">
                                <div class="input-group">
                                <input type="text"  class="form-control required" autocomplete="off" id="exampleInputAmount" placeholder="" name='ii_v_cc[]' value="" disabled>
                                </div>
                            </div>
                        </td>
                        <td class="align-middle text-left">
                        <div class="form-group">
                                <div class="input-group">
                                <input type="text"  class="form-control required" autocomplete="off" id="exampleInputAmount" placeholder="" name='ii_v_cc[]' value="" disabled>
                                </div>
                            </div>
                        </td>
                    </tr>
                    <tr class="form-inline">
                        <td class="align-middle text-center" style="font-weight:500;"></td>
                        <td class="align-middle text-center">
                            <div class="form-group ">
                                <div class="input-group text-center">
                                <input type="text"  class="form-control required" autocomplete="off" id="exampleInputAmount" placeholder="1" name='ii_v_a[]' value="1" disabled>
                                </div>
                            </div>
                        </td>
                        <td class="align-middle text-left">
                            <div class="form-group">
                                <div class="input-group">
                                <input type="text"  class="form-control required" autocomplete="off" id="exampleInputAmount" placeholder="" name='ii_v_a[]' value="1" disabled>
                                </div>
                            </div>
                        </td>
                        <td class="align-middle text-left">
                        <div class="form-group">
                                <div class="input-group">
                                <input type="text"  class="form-control required" autocomplete="off" id="exampleInputAmount" placeholder="" name='ii_v_a[]' value="1" disabled>
                                </div>
                            </div>
                        </td>
                        <td class="align-middle text-left">
                        <div class="form-group">
                                <div class="input-group">
                                <input type="text"  class="form-control required" autocomplete="off" id="_i_v_a_1" placeholder="" name='i_v_a_1' value="<?php if(!empty($calibration_info)) {echo $calibration_info->i_v_a_1;} ?>" required >
                                </div>
                            </div>
                        </td>
                        <td class="align-middle text-left">
                        <div class="form-group">
                                <div class="input-group">
                                <input type="text"  class="form-control required" autocomplete="off" id="_i_v_a_2" placeholder="" name='i_v_a_2' value="<?php if(!empty($calibration_info)) {echo $calibration_info->i_v_a_2;} ?>" required>
                                </div>
                            </div>
                        </td>
                        <td class="align-middle text-left">
                            <div class="form-group">
                                <div class="input-group">
                                <input type="text"  class="form-control required" autocomplete="off" id="_i_v_a_3" placeholder="" name='i_v_a_3' value="<?php if(!empty($calibration_info)) {echo $calibration_info->i_v_a_3;} ?>" required>
                                </div>
                            </div>
                        </td>
                        <td class="align-middle text-left">
                        <div class="form-group">
                                <div class="input-group">
                                <input type="text"  class="form-control required" autocomplete="off" id="exampleInputAmount" placeholder="" name='ii_v_a[]' value="" disabled>
                                </div>
                            </div>
                        </td>
                        <td class="align-middle text-left">
                        <div class="form-group">
                                <div class="input-group">
                                <input type="text"  class="form-control required" autocomplete="off" id="exampleInputAmount" placeholder="" name='ii_v_a[]' value="" disabled>
                                </div>
                            </div>
                        </td>
                    </tr>
                    <tr class="form-inline">
                        <td class="align-middle text-center" style="font-weight:500;"></td>
                        <td class="align-middle text-center">
                            <div class="form-group ">
                                <div class="input-group text-center">
                                <input type="text"  class="form-control required" autocomplete="off" id="exampleInputAmount" placeholder="1" name='ii_v_a[]' value="2" disabled>
                                </div>
                            </div>
                        </td>
                        <td class="align-middle text-left">
                            <div class="form-group">
                                <div class="input-group">
                                <input type="text"  class="form-control required" autocomplete="off" id="exampleInputAmount" placeholder="" name='ii_v_a[]' value="1" disabled>
                                </div>
                            </div>
                        </td>
                        <td class="align-middle text-left">
                        <div class="form-group">
                                <div class="input-group">
                                <input type="text"  class="form-control required" autocomplete="off" id="exampleInputAmount" placeholder="" name='ii_v_a[]' value="2" disabled>
                                </div>
                            </div>
                        </td>
                        <td class="align-middle text-left">
                        <div class="form-group">
                                <div class="input-group">
                                <input type="text"  class="form-control required" autocomplete="off" id="_ii_v_a_1" placeholder="" name='ii_v_a_1' value="<?php if(!empty($calibration_info)) {echo $calibration_info->ii_v_a_1;} ?>" >
                                </div>
                            </div>
                        </td>
                        <td class="align-middle text-left">
                        <div class="form-group">
                                <div class="input-group">
                                <input type="text"  class="form-control required" autocomplete="off" id="_ii_v_a_2" placeholder="" name='ii_v_a_2' value="<?php if(!empty($calibration_info)) {echo $calibration_info->ii_v_a_2;} ?>" >
                                </div>
                            </div>
                        </td>
                        <td class="align-middle text-left">
                            <div class="form-group">
                                <div class="input-group">
                                <input type="text"  class="form-control required" autocomplete="off" id="_ii_v_a_3" placeholder="" name='ii_v_a_3' value="<?php if(!empty($calibration_info)) {echo $calibration_info->ii_v_a_3;} ?>" >
                                </div>
                            </div>
                        </td>
                        <td class="align-middle text-left">
                        <div class="form-group">
                                <div class="input-group">
                                <input type="text"  class="form-control required" autocomplete="off" id="exampleInputAmount" placeholder="" name='ii_v_a[]' value="" disabled>
                                </div>
                            </div>
                        </td>
                        <td class="align-middle text-left">
                        <div class="form-group">
                                <div class="input-group">
                                <input type="text"  class="form-control required" autocomplete="off" id="exampleInputAmount" placeholder="" name='ii_v_a[]' value="" disabled>
                                </div>
                            </div>
                        </td>
                    </tr>
                    <tr class="form-inline">
                        <td class="align-middle text-center" style="font-weight:500;"></td>
                        <td class="align-middle text-center">
                            <div class="form-group ">
                                <div class="input-group text-center">
                                <input type="text"  class="form-control required" autocomplete="off" id="exampleInputAmount" placeholder="1" name='ii_v_a[]' value="3" disabled>
                                </div>
                            </div>
                        </td>
                        <td class="align-middle text-left">
                            <div class="form-group">
                                <div class="input-group">
                                <input type="text"  class="form-control required" autocomplete="off" id="exampleInputAmount" placeholder="" name='ii_v_a[]' value="2" disabled>
                                </div>
                            </div>
                        </td>
                        <td class="align-middle text-left">
                        <div class="form-group">
                                <div class="input-group">
                                <input type="text"  class="form-control required" autocomplete="off" id="exampleInputAmount" placeholder="" name='ii_v_a[]' value="1" disabled>
                                </div>
                            </div>
                        </td>
                        <td class="align-middle text-left">
                        <div class="form-group">
                                <div class="input-group">
                                <input type="text"  class="form-control required" autocomplete="off" id="_iii_v_a_1" placeholder="" name='iii_v_a_1' value="<?php if(!empty($calibration_info)) {echo $calibration_info->iii_v_a_1;} ?>" >
                                </div>
                            </div>
                        </td>
                        <td class="align-middle text-left">
                        <div class="form-group">
                                <div class="input-group">
                                <input type="text"  class="form-control required" autocomplete="off" id="_iii_v_a_2" placeholder="" name='iii_v_a_2' value="<?php if(!empty($calibration_info)) {echo $calibration_info->iii_v_a_2;} ?>" >
                                </div>
                            </div>
                        </td>
                        <td class="align-middle text-left">
                            <div class="form-group">
                                <div class="input-group">
                                <input type="text"  class="form-control required" autocomplete="off" id="_iii_v_a_3" placeholder="" name='iii_v_a_3' value="<?php if(!empty($calibration_info)) {echo $calibration_info->iii_v_a_3;} ?>" >
                                </div>
                            </div>
                        </td>
                        <td class="align-middle text-left">
                        <div class="form-group">
                                <div class="input-group">
                                <input type="text"  class="form-control required" autocomplete="off" id="exampleInputAmount" placeholder="" name='ii_v_a[]' value="" disabled>
                                </div>
                            </div>
                        </td>
                        <td class="align-middle text-left">
                        <div class="form-group">
                                <div class="input-group">
                                <input type="text"  class="form-control required" autocomplete="off" id="exampleInputAmount" placeholder="" name='ii_v_a[]' value="" disabled>
                                </div>
                            </div>
                        </td>
                    </tr>
                    <tr class="form-inline">
                        <td class="align-middle text-center" style="font-weight:500;"></td>
                        <td class="align-middle text-center">
                            <div class="form-group ">
                                <div class="input-group text-center">
                                <input type="text"  class="form-control required" autocomplete="off" id="exampleInputAmount" placeholder="" name='ii_v_a[]' value="4" disabled>
                                </div>
                            </div>
                        </td>
                        <td class="align-middle text-left">
                            <div class="form-group">
                                <div class="input-group">
                                <input type="text"  class="form-control required" autocomplete="off" id="exampleInputAmount" placeholder="" name='ii_v_a[]' value="2" disabled>
                                </div>
                            </div>
                        </td>
                        <td class="align-middle text-left">
                        <div class="form-group">
                                <div class="input-group">
                                <input type="text"  class="form-control required" autocomplete="off" id="exampleInputAmount" placeholder="" name='ii_v_a[]' value="2" disabled>
                                </div>
                            </div>
                        </td>
                        <td class="align-middle text-left">
                        <div class="form-group">
                                <div class="input-group">
                                <input type="text"  class="form-control required" autocomplete="off" id="_iv_v_a_1" placeholder="" name='iv_v_a_1' value="<?php if(!empty($calibration_info)) {echo $calibration_info->iv_v_a_1;} ?>" >
                                </div>
                            </div>
                        </td>
                        <td class="align-middle text-left">
                        <div class="form-group">
                                <div class="input-group">
                                <input type="text"  class="form-control required" autocomplete="off" id="_iv_v_a_2" placeholder="" name='iv_v_a_2' value="<?php if(!empty($calibration_info)) {echo $calibration_info->iv_v_a_2;} ?>" >
                                </div>
                            </div>
                        </td>
                        <td class="align-middle text-left">
                            <div class="form-group">
                                <div class="input-group">
                                <input type="text"  class="form-control required" autocomplete="off" id="_iv_v_a_3" placeholder="" name='iv_v_a_3' value="<?php if(!empty($calibration_info)) {echo $calibration_info->iv_v_a_3;} ?>" >
                                </div>
                            </div>
                        </td>
                        <td class="align-middle text-left">
                        <div class="form-group">
                                <div class="input-group">
                                <input type="text"  class="form-control required" autocomplete="off" id="exampleInputAmount" placeholder="" name='ii_v_a[]' value="" disabled>
                                </div>
                            </div>
                        </td>
                        <td class="align-middle text-left">
                        <div class="form-group">
                                <div class="input-group">
                                <input type="text"  class="form-control required" autocomplete="off" id="exampleInputAmount" placeholder="" name='ii_v_a[]' value="" disabled>
                                </div>
                            </div>
                        </td>
                    </tr>
                    <tr class="form-inline">
                        <td class="align-middle text-center" style="font-weight:500;">1</td>
                        <td class="align-middle text-center">
                            <div class="form-group ">
                                <div class="input-group text-center">
                                <input type="text"  class="form-control required" autocomplete="off" id="exampleInputAmount" placeholder="" name='ii_v_a[]' value="5" disabled>
                                </div>
                            </div>
                        </td>
                        <td class="align-middle text-left">
                            <div class="form-group">
                                <div class="input-group">
                                <input type="text"  class="form-control required" autocomplete="off" id="exampleInputAmount" placeholder="" name='ii_v_a[]' value="3" disabled>
                                </div>
                            </div>
                        </td>
                        <td class="align-middle text-left">
                        <div class="form-group">
                                <div class="input-group">
                                <input type="text"  class="form-control required" autocomplete="off" id="exampleInputAmount" placeholder="" name='ii_v_a[]' value="1" disabled>
                                </div>
                            </div>
                        </td>
                        <td class="align-middle text-left">
                        <div class="form-group">
                                <div class="input-group">
                                <input type="text"  class="form-control required" autocomplete="off" id="_v_v_a_1" placeholder="" name='v_v_a_1' value="<?php if(!empty($calibration_info)) {echo $calibration_info->v_v_a_1;} ?>" >
                                </div>
                            </div>
                        </td>
                        <td class="align-middle text-left">
                        <div class="form-group">
                                <div class="input-group">
                                <input type="text"  class="form-control required" autocomplete="off" id="_v_v_a_2" placeholder="" name='v_v_a_2' value="<?php if(!empty($calibration_info)) {echo $calibration_info->v_v_a_2;} ?>" >
                                </div>
                            </div>
                        </td>
                        <td class="align-middle text-left">
                            <div class="form-group">
                                <div class="input-group">
                                <input type="text"  class="form-control required" autocomplete="off" id="_v_v_a_3" placeholder="" name='v_v_a_3' value="<?php if(!empty($calibration_info)) {echo $calibration_info->v_v_a_3;} ?>" >
                                </div>
                            </div>
                        </td>
                        <td class="align-middle text-left">
                        <div class="form-group">
                                <div class="input-group">
                                <input type="text"  class="form-control required" autocomplete="off" id="exampleInputAmount" placeholder="" name='ii_v_a[]' value="" disabled>
                                </div>
                            </div>
                        </td>
                        <td class="align-middle text-left">
                        <div class="form-group">
                                <div class="input-group">
                                <input type="text"  class="form-control required" autocomplete="off" id="exampleInputAmount" placeholder="" name='ii_v_a[]' value="" disabled>
                                </div>
                            </div>
                        </td>
                    </tr>
                    <tr class="form-inline">
                        <td class="align-middle text-center" style="font-weight:500;"></td>
                        <td class="align-middle text-center">
                            <div class="form-group ">
                                <div class="input-group text-center">
                                <input type="text"  class="form-control required" autocomplete="off" id="exampleInputAmount" placeholder="" name='ii_v_a[]' value="6" disabled>
                                </div>
                            </div>
                        </td>
                        <td class="align-middle text-left">
                            <div class="form-group">
                                <div class="input-group">
                                <input type="text"  class="form-control required" autocomplete="off" id="exampleInputAmount" placeholder="" name='ii_v_a[]' value="3" disabled>
                                </div>
                            </div>
                        </td>
                        <td class="align-middle text-left">
                        <div class="form-group">
                                <div class="input-group">
                                <input type="text"  class="form-control required" autocomplete="off" id="exampleInputAmount" placeholder="" name='ii_v_a[]' value="2" disabled>
                                </div>
                            </div>
                        </td>
                        <td class="align-middle text-left">
                        <div class="form-group">
                                <div class="input-group">
                                <input type="text"  class="form-control required" autocomplete="off" id="_vi_v_a_1" placeholder="" name='vi_v_a_1' value="<?php if(!empty($calibration_info)) {echo $calibration_info->vi_v_a_1;} ?>" >
                                </div>
                            </div>
                        </td>
                        <td class="align-middle text-left">
                        <div class="form-group">
                                <div class="input-group">
                                <input type="text"  class="form-control required" autocomplete="off" id="_vi_v_a_2" placeholder="" name='vi_v_a_2' value="<?php if(!empty($calibration_info)) {echo $calibration_info->vi_v_a_2;} ?>" >
                                </div>
                            </div>
                        </td>
                        <td class="align-middle text-left">
                            <div class="form-group">
                                <div class="input-group">
                                <input type="text"  class="form-control required" autocomplete="off" id="_vi_v_a_3" placeholder="" name='vi_v_a_3' value="<?php if(!empty($calibration_info)) {echo $calibration_info->vi_v_a_3;} ?>" >
                                </div>
                            </div>
                        </td>
                        <td class="align-middle text-left">
                        <div class="form-group">
                                <div class="input-group">
                                <input type="text"  class="form-control required" autocomplete="off" id="exampleInputAmount" placeholder="" name='ii_v_a[]' value="" disabled>
                                </div>
                            </div>
                        </td>
                        <td class="align-middle text-left">
                        <div class="form-group">
                                <div class="input-group">
                                <input type="text"  class="form-control required" autocomplete="off" id="exampleInputAmount" placeholder="" name='ii_v_a[]' value="" disabled>
                                </div>
                            </div>
                        </td>
                    </tr>
                    <tr class="form-inline">
                        <td class="align-middle text-center" style="font-weight:500;"></td>
                        <td class="align-middle text-center">
                            <div class="form-group ">
                                <div class="input-group text-center">
                                <input type="text"  class="form-control required" autocomplete="off" id="exampleInputAmount" placeholder="" name='ii_v_a[]' value="7" disabled>
                                </div>
                            </div>
                        </td>
                        <td class="align-middle text-left">
                            <div class="form-group">
                                <div class="input-group">
                                <input type="text"  class="form-control required" autocomplete="off" id="exampleInputAmount" placeholder="" name='ii_v_a[]' value="4" disabled>
                                </div>
                            </div>
                        </td>
                        <td class="align-middle text-left">
                        <div class="form-group">
                                <div class="input-group">
                                <input type="text"  class="form-control required" autocomplete="off" id="exampleInputAmount" placeholder="" name='ii_v_a[]' value="1" disabled>
                                </div>
                            </div>
                        </td>
                        <td class="align-middle text-left">
                        <div class="form-group">
                                <div class="input-group">
                                <input type="text"  class="form-control required" autocomplete="off" id="_vii_v_a_1" placeholder="" name='vii_v_a_1' value="<?php if(!empty($calibration_info)) {echo $calibration_info->vii_v_a_1;} ?>" >
                                </div>
                            </div>
                        </td>
                        <td class="align-middle text-left">
                        <div class="form-group">
                                <div class="input-group">
                                <input type="text"  class="form-control required" autocomplete="off" id="_vii_v_a_2" placeholder="" name='vii_v_a_2' value="<?php if(!empty($calibration_info)) {echo $calibration_info->vii_v_a_2;} ?>" >
                                </div>
                            </div>
                        </td>
                        <td class="align-middle text-left">
                            <div class="form-group">
                                <div class="input-group">
                                <input type="text"  class="form-control required" autocomplete="off" id="_vii_v_a_3" placeholder="" name='vii_v_a_3' value="<?php if(!empty($calibration_info)) {echo $calibration_info->vii_v_a_3;} ?>" >
                                </div>
                            </div>
                        </td>
                        <td class="align-middle text-left">
                        <div class="form-group">
                                <div class="input-group">
                                <input type="text"  class="form-control required" autocomplete="off" id="exampleInputAmount" placeholder="" name='ii_v_a[]' value="" disabled>
                                </div>
                            </div>
                        </td>
                        <td class="align-middle text-left">
                        <div class="form-group">
                                <div class="input-group">
                                <input type="text"  class="form-control required" autocomplete="off" id="exampleInputAmount" placeholder="" name='ii_v_a[]' value="" disabled>
                                </div>
                            </div>
                        </td>
                    </tr>
                    <tr class="form-inline">
                        <td class="align-middle text-center" style="font-weight:500;"></td>
                        <td class="align-middle text-center">
                            <div class="form-group ">
                                <div class="input-group text-center">
                                <input type="text"  class="form-control required" autocomplete="off" id="exampleInputAmount" placeholder="" name='ii_v_a[]' value="8" disabled>
                                </div>
                            </div>
                        </td>
                        <td class="align-middle text-left">
                            <div class="form-group">
                                <div class="input-group">
                                <input type="text"  class="form-control required" autocomplete="off" id="exampleInputAmount" placeholder="" name='ii_v_a[]' value="4" disabled>
                                </div>
                            </div>
                        </td>
                        <td class="align-middle text-left">
                        <div class="form-group">
                                <div class="input-group">
                                <input type="text"  class="form-control required" autocomplete="off" id="exampleInputAmount" placeholder="" name='ii_v_a[]' value="2" disabled>
                                </div>
                            </div>
                        </td>
                        <td class="align-middle text-left">
                        <div class="form-group">
                                <div class="input-group">
                                <input type="text"  class="form-control required" autocomplete="off" id="_viii_v_a_1" placeholder="" name='viii_v_a_1' value="<?php if(!empty($calibration_info)) {echo $calibration_info->viii_v_a_1;} ?>" >
                                </div>
                            </div>
                        </td>
                        <td class="align-middle text-left">
                        <div class="form-group">
                                <div class="input-group">
                                <input type="text"  class="form-control required" autocomplete="off" id="_viii_v_a_2" placeholder="" name='viii_v_a_2' value="<?php if(!empty($calibration_info)) {echo $calibration_info->viii_v_a_2;} ?>" >
                                </div>
                            </div>
                        </td>
                        <td class="align-middle text-left">
                            <div class="form-group">
                                <div class="input-group">
                                <input type="text"  class="form-control required" autocomplete="off" id="viii_v_a_3" placeholder="" name='viii_v_a_3' value="<?php if(!empty($calibration_info)) {echo $calibration_info->viii_v_a_3;} ?>" >
                                </div>
                            </div>
                        </td>
                        <td class="align-middle text-left">
                        <div class="form-group">
                                <div class="input-group">
                                <input type="text"  class="form-control required" autocomplete="off" id="exampleInputAmount" placeholder="" name='ii_v_a[]' value="" disabled>
                                </div>
                            </div>
                        </td>
                        <td class="align-middle text-left">
                        <div class="form-group">
                                <div class="input-group">
                                <input type="text"  class="form-control required" autocomplete="off" id="exampleInputAmount" placeholder="" name='ii_v_a[]' value="" disabled>
                                </div>
                            </div>
                        </td>
                    </tr>
                    <tr class="form-inline">
                        <td class="align-middle text-center" style="font-weight:500;"></td>
                        <td class="align-middle text-center">
                            <div class="form-group ">
                                <div class="input-group text-center">
                                <input type="text"  class="form-control required" autocomplete="off" id="exampleInputAmount" placeholder="" name='ii_v_a[]' value="9" disabled>
                                </div>
                            </div>
                        </td>
                        <td class="align-middle text-left">
                            <div class="form-group">
                                <div class="input-group">
                                <input type="text"  class="form-control required" autocomplete="off" id="exampleInputAmount" placeholder="" name='ii_v_a[]' value="5" disabled>
                                </div>
                            </div>
                        </td>
                        <td class="align-middle text-left">
                        <div class="form-group">
                                <div class="input-group">
                                <input type="text"  class="form-control required" autocomplete="off" id="exampleInputAmount" placeholder="" name='ii_v_a[]' value="1" disabled>
                                </div>
                            </div>
                        </td>
                        <td class="align-middle text-left">
                        <div class="form-group">
                                <div class="input-group">
                                <input type="text"  class="form-control required" autocomplete="off" id="_xi_v_a_1" placeholder="" name='xi_v_a_1' value="<?php if(!empty($calibration_info)) {echo $calibration_info->xi_v_a_1;} ?>" >
                                </div>
                            </div>
                        </td>
                        <td class="align-middle text-left">
                        <div class="form-group">
                                <div class="input-group">
                                <input type="text"  class="form-control required" autocomplete="off" id="_xi_v_a_2" placeholder="" name='xi_v_a_2' value="<?php if(!empty($calibration_info)) {echo $calibration_info->xi_v_a_2;} ?>" >
                                </div>
                            </div>
                        </td>
                        <td class="align-middle text-left">
                            <div class="form-group">
                                <div class="input-group">
                                <input type="text"  class="form-control required" autocomplete="off" id="_xi_v_a_3" placeholder="" name='xi_v_a_3' value="<?php if(!empty($calibration_info)) {echo $calibration_info->xi_v_a_3;} ?>" >
                                </div>
                            </div>
                        </td>
                        <td class="align-middle text-left">
                        <div class="form-group">
                                <div class="input-group">
                                <input type="text"  class="form-control required" autocomplete="off" id="exampleInputAmount" placeholder="" name='ii_v_a[]' value="" disabled>
                                </div>
                            </div>
                        </td>
                        <td class="align-middle text-left">
                        <div class="form-group">
                                <div class="input-group">
                                <input type="text"  class="form-control required" autocomplete="off" id="exampleInputAmount" placeholder="" name='ii_v_a[]' value="" disabled>
                                </div>
                            </div>
                        </td>
                    </tr>
                    <tr class="form-inline">
                        <td class="align-middle text-center" style="font-weight:500;"></td>
                        <td class="align-middle text-center">
                            <div class="form-group ">
                                <div class="input-group text-center">
                                <input type="text"  class="form-control required" autocomplete="off" id="exampleInputAmount" placeholder="" name='ii_v_a[]' value="10" disabled>
                                </div>
                            </div>
                        </td>
                        <td class="align-middle text-left">
                            <div class="form-group">
                                <div class="input-group">
                                <input type="text"  class="form-control required" autocomplete="off" id="exampleInputAmount" placeholder="" name='ii_v_a[]' value="5" disabled>
                                </div>
                            </div>
                        </td>
                        <td class="align-middle text-left">
                        <div class="form-group">
                                <div class="input-group">
                                <input type="text"  class="form-control required" autocomplete="off" id="exampleInputAmount" placeholder="" name='ii_v_a[]' value="2" disabled>
                                </div>
                            </div>
                        </td>
                        <td class="align-middle text-left">
                        <div class="form-group">
                                <div class="input-group">
                                <input type="text"  class="form-control required" autocomplete="off" id="_x_v_a_1" placeholder="" name='x_v_a_1' value="<?php if(!empty($calibration_info)) {echo $calibration_info->x_v_a_1;} ?>" >
                                </div>
                            </div>
                        </td>
                        <td class="align-middle text-left">
                        <div class="form-group">
                                <div class="input-group">
                                <input type="text"  class="form-control required" autocomplete="off" id="_x_v_a_2" placeholder="" name='x_v_a_2' value="<?php if(!empty($calibration_info)) {echo $calibration_info->x_v_a_2;} ?>" >
                                </div>
                            </div>
                        </td>
                        <td class="align-middle text-left">
                            <div class="form-group">
                                <div class="input-group">
                                <input type="text"  class="form-control required" autocomplete="off" id="_x_v_a_3" placeholder="" name='x_v_a_3' value="<?php if(!empty($calibration_info)) {echo $calibration_info->x_v_a_3;} ?>" >
                                </div>
                            </div>
                        </td>
                        <td class="align-middle text-left">
                        <div class="form-group">
                                <div class="input-group">
                                <input type="text"  class="form-control required" autocomplete="off" id="exampleInputAmount" placeholder="" name='ii_v_a[]' value="" disabled>
                                </div>
                            </div>
                        </td>
                        <td class="align-middle text-left">
                        <div class="form-group">
                                <div class="input-group">
                                <input type="text"  class="form-control required" autocomplete="off" id="exampleInputAmount" placeholder="" name='xi_v_a1' value="" disabled>
                                </div>
                            </div>
                        </td>
                    </tr>
                    <tr class="form-inline">
                        <td class="align-middle text-center" style="font-weight:500;"></td>
                        <td class="align-middle text-center">
                            <div class="form-group ">
                                <div class="input-group text-center">
                                <input type="text"  class="form-control required" autocomplete="off" id="exampleInputAmount" placeholder="2" name='ii_v_cc[]' value="STOP TIME" disabled>
                                </div>
                            </div>
                        </td>
                        <td class="align-middle text-center">
                            <div class="form-group">
                                <div class="input-group">
                                    <input type="time" class="form-control required" id="stop_time_1" name="stop_time_1" autocomplete="off" value="<?php echo !empty($calibration_info->stop_time_1) ? date('H:i', strtotime($calibration_info->stop_time_1)) : ''; ?>">
                                </div>
                            </div>
                        </td>

                        <td class="align-middle text-left">
                        <div class="form-group">
                                <div class="input-group">
                                <input type="text"  class="form-control required" autocomplete="off" id="exampleInputAmount" placeholder="" name='ii_v_cc[]' value="" disabled>
                                </div>
                            </div>
                        </td>
                        <td class="align-middle text-left">
                        <div class="form-group">
                                <div class="input-group">
                                <input type="text"  class="form-control required" autocomplete="off" id="_i_v_cc_1" placeholder="" name='i_v_cc_1' value="" disabled>
                                </div>
                            </div>
                        </td>
                        <td class="align-middle text-left">
                        <div class="form-group">
                                <div class="input-group">
                                <input type="text"  class="form-control required" autocomplete="off" id="_i_v_cc_2" placeholder="" name='i_v_cc_2' value="" disabled>
                                </div>
                            </div>
                        </td>
                        <td class="align-middle text-left">
                            <div class="form-group">
                                <div class="input-group">
                                <input type="text"  class="form-control required" autocomplete="off" id="_i_v_cc_3" placeholder="" name='i_v_cc_3' value="" disabled>
                                </div>
                            </div>
                        </td>
                        <td class="align-middle text-left">
                        <div class="form-group">
                                <div class="input-group">
                                <input type="text"  class="form-control required" autocomplete="off" id="exampleInputAmount" placeholder="" name='ii_v_cc[]' value="" disabled>
                                </div>
                            </div>
                        </td>
                        <td class="align-middle text-left">
                        <div class="form-group">
                                <div class="input-group">
                                <input type="text"  class="form-control required" autocomplete="off" id="exampleInputAmount" placeholder="" name='ii_v_cc[]' value="" disabled>
                                </div>
                            </div>
                        </td>
                    </tr>
                    <!-- Add more rows as needed -->
                </tbody>               
            </table>
            <button type="button" class="btn btn-secondary prev-step">Previous</button>
            <button type="button" class="btn btn-primary next-step">Next</button>
            </div>
            
            
            <!-- Table A3 -->
            <div class="form-section">
            <b>PRE-CALIBRATION CHECKS REPORT ENTRY 2</b>
            <table class="table" id="serviceFields">
                <thead>
                    <tr>
                        <th class="text-left" style="font-weight:500;">Series</th>
                        <th class="text-left" style="font-weight:500;">Seq. No.</th>
                        <th class="text-left" style="font-weight:500;">Set</th>
                        <th class="text-left" style="font-weight:500;">Rover Point </th>
                        <th class="text-left" style="font-weight:500;">Eastings</th>
                        <th class="text-left" style="font-weight:500;">Northings</th>
                        <th class="text-left" style="font-weight:500;">Elevation</th>
                        <th class="text-left" style="font-weight:500;">Horizontal Distance (m)</th>
                        <th class="text-left" style="font-weight:500;">Elevation Accuracy (mm)</th>
                    </tr>
                </thead>
                <tbody>
                    <tr class="form-inline">
                        <td class="align-middle text-center" style="font-weight:500;"></td>
                        <td class="align-middle text-center">
                            <div class="form-group ">
                                <div class="input-group text-center">
                                <input type="text"  class="form-control required" autocomplete="off" id="exampleInputAmount" placeholder="2" name='ii_v_cc[]' value="START TIME" disabled>
                                </div>
                            </div>
                        </td>
                        <td class="align-middle text-center">
                            <div class="form-group">
                                <div class="input-group text-center">
                                    <input type="time" class="form-control required" autocomplete="off" id="start_time_2" name="start_time_2" value="<?php if(!empty($calibration_info)) {echo date('H:i', strtotime($calibration_info->start_time_2));} ?>">
                                </div>
                            </div>
                        </td>
                        <td class="align-middle text-left">
                        <div class="form-group">
                                <div class="input-group">
                                <input type="text"  class="form-control required" autocomplete="off" id="exampleInputAmount" placeholder="" name='ii_v_cc[]' value="" disabled>
                                </div>
                            </div>
                        </td>
                        <td class="align-middle text-left">
                        <div class="form-group">
                                <div class="input-group">
                                <input type="text"  class="form-control required" autocomplete="off" id="_i_v_cc_1" placeholder="" name='i_v_cc_1' value="" disabled>
                                </div>
                            </div>
                        </td>
                        <td class="align-middle text-left">
                        <div class="form-group">
                                <div class="input-group">
                                <input type="text"  class="form-control required" autocomplete="off" id="_i_v_cc_2" placeholder="" name='i_v_cc_2' value="" disabled>
                                </div>
                            </div>
                        </td>
                        <td class="align-middle text-left">
                            <div class="form-group">
                                <div class="input-group">
                                <input type="text"  class="form-control required" autocomplete="off" id="_i_v_cc_3" placeholder="" name='i_v_cc_3' value="" disabled>
                                </div>
                            </div>
                        </td>
                        <td class="align-middle text-left">
                        <div class="form-group">
                                <div class="input-group">
                                <input type="text"  class="form-control required" autocomplete="off" id="exampleInputAmount" placeholder="" name='ii_v_cc[]' value="" disabled>
                                </div>
                            </div>
                        </td>
                        <td class="align-middle text-left">
                        <div class="form-group">
                                <div class="input-group">
                                <input type="text"  class="form-control required" autocomplete="off" id="exampleInputAmount" placeholder="" name='ii_v_cc[]' value="" disabled>
                                </div>
                            </div>
                        </td>
                    </tr>
                    <tr class="form-inline">
                        <td class="align-middle text-center" style="font-weight:500;"></td>
                        <td class="align-middle text-center">
                            <div class="form-group ">
                                <div class="input-group text-center">
                                <input type="text"  class="form-control required" autocomplete="off" id="exampleInputAmount" placeholder="2" name='ii_v_b[]' value="11" disabled>
                                </div>
                            </div>
                        </td>
                        <td class="align-middle text-left">
                            <div class="form-group">
                                <div class="input-group">
                                <input type="text"  class="form-control required" autocomplete="off" id="exampleInputAmount" placeholder="" name='ii_v_b[]' value="1" disabled>
                                </div>
                            </div>
                        </td>
                        <td class="align-middle text-left">
                        <div class="form-group">
                                <div class="input-group">
                                <input type="text"  class="form-control required" autocomplete="off" id="exampleInputAmount" placeholder="" name='ii_v_b[]' value="1" disabled>
                                </div>
                            </div>
                        </td>
                        <td class="align-middle text-left">
                        <div class="form-group">
                                <div class="input-group">
                                <input type="text"  class="form-control required" autocomplete="off" id="_i_v_b_1" placeholder="" name='i_v_b_1' value="<?php echo isset($calibration_info->i_v_b_1) ? $calibration_info->i_v_b_1 : ''; ?>" required>
                                </div>
                            </div>
                        </td>
                        <td class="align-middle text-left">
                        <div class="form-group">
                                <div class="input-group">
                                <input type="text"  class="form-control required" autocomplete="off" id="_i_v_b_2" placeholder="" name='i_v_b_2' value="<?php if(!empty($calibration_info)) {echo $calibration_info->i_v_b_2;} ?>" required>
                                </div>
                            </div>
                        </td>
                        <td class="align-middle text-left">
                            <div class="form-group">
                                <div class="input-group">
                                <input type="text"  class="form-control required" autocomplete="off" id="_i_v_b_3" placeholder="" name='i_v_b_3' value="<?php if(!empty($calibration_info)) {echo $calibration_info->i_v_b_3;} ?>" required>
                                </div>
                            </div>
                        </td>
                        <td class="align-middle text-left">
                        <div class="form-group">
                                <div class="input-group">
                                <input type="text"  class="form-control required" autocomplete="off" id="exampleInputAmount" placeholder="" name='ii_v_b[]' value="" disabled>
                                </div>
                            </div>
                        </td>
                        <td class="align-middle text-left">
                        <div class="form-group">
                                <div class="input-group">
                                <input type="text"  class="form-control required" autocomplete="off" id="exampleInputAmount" placeholder="" name='ii_v_b[]' value="" disabled>
                                </div>
                            </div>
                        </td>
                    </tr>
                    <tr class="form-inline">
                        <td class="align-middle text-center" style="font-weight:500;"></td>
                        <td class="align-middle text-center">
                            <div class="form-group ">
                                <div class="input-group text-center">
                                <input type="text"  class="form-control required" autocomplete="off" id="exampleInputAmount" placeholder="1" name='ii_v_b[]' value="12" disabled>
                                </div>
                            </div>
                        </td>
                        <td class="align-middle text-left">
                            <div class="form-group">
                                <div class="input-group">
                                <input type="text"  class="form-control required" autocomplete="off" id="exampleInputAmount" placeholder="" name='ii_v_b[]' value="1" disabled>
                                </div>
                            </div>
                        </td>
                        <td class="align-middle text-left">
                        <div class="form-group">
                                <div class="input-group">
                                <input type="text"  class="form-control required" autocomplete="off" id="exampleInputAmount" placeholder="" name='ii_v_b[]' value="2" disabled>
                                </div>
                            </div>
                        </td>
                        <td class="align-middle text-left">
                        <div class="form-group">
                                <div class="input-group">
                                <input type="text"  class="form-control required" autocomplete="off" id="_ii_v_b_1" placeholder="" name='ii_v_b_1' value="<?php if(!empty($calibration_info)) {echo $calibration_info->ii_v_b_1;} ?>" >
                                </div>
                            </div>
                        </td>
                        <td class="align-middle text-left">
                        <div class="form-group">
                                <div class="input-group">
                                <input type="text"  class="form-control required" autocomplete="off" id="_ii_v_b_2" placeholder="" name='ii_v_b_2' value="<?php if(!empty($calibration_info)) {echo $calibration_info->ii_v_b_2;} ?>" >
                                </div>
                            </div>
                        </td>
                        <td class="align-middle text-left">
                            <div class="form-group">
                                <div class="input-group">
                                <input type="text"  class="form-control required" autocomplete="off" id="_ii_v_b_3" placeholder="" name='ii_v_b_3' value="<?php if(!empty($calibration_info)) {echo $calibration_info->ii_v_b_3;} ?>" >
                                </div>
                            </div>
                        </td>
                        <td class="align-middle text-left">
                        <div class="form-group">
                                <div class="input-group">
                                <input type="text"  class="form-control required" autocomplete="off" id="exampleInputAmount" placeholder="" name='ii_v_b[]' value="" disabled>
                                </div>
                            </div>
                        </td>
                        <td class="align-middle text-left">
                        <div class="form-group">
                                <div class="input-group">
                                <input type="text"  class="form-control required" autocomplete="off" id="exampleInputAmount" placeholder="" name='ii_v_b[]' value="" disabled>
                                </div>
                            </div>
                        </td>
                    </tr>
                    <tr class="form-inline">
                        <td class="align-middle text-center" style="font-weight:500;"></td>
                        <td class="align-middle text-center">
                            <div class="form-group ">
                                <div class="input-group text-center">
                                <input type="text"  class="form-control required" autocomplete="off" id="exampleInputAmount" placeholder="1" name='ii_v_b[]' value="13" disabled>
                                </div>
                            </div>
                        </td>
                        <td class="align-middle text-left">
                            <div class="form-group">
                                <div class="input-group">
                                <input type="text"  class="form-control required" autocomplete="off" id="exampleInputAmount" placeholder="" name='ii_v_b[]' value="2" disabled>
                                </div>
                            </div>
                        </td>
                        <td class="align-middle text-left">
                        <div class="form-group">
                                <div class="input-group">
                                <input type="text"  class="form-control required" autocomplete="off" id="exampleInputAmount" placeholder="" name='ii_v_b[]' value="1" disabled>
                                </div>
                            </div>
                        </td>
                        <td class="align-middle text-left">
                        <div class="form-group">
                                <div class="input-group">
                                <input type="text"  class="form-control required" autocomplete="off" id="_iii_v_b_1" placeholder="" name='iii_v_b_1' value="<?php if(!empty($calibration_info)) {echo $calibration_info->iii_v_b_1;} ?>" >
                                </div>
                            </div>
                        </td>
                        <td class="align-middle text-left">
                        <div class="form-group">
                                <div class="input-group">
                                <input type="text"  class="form-control required" autocomplete="off" id="_iii_v_b_2" placeholder="" name='iii_v_b_2' value="<?php if(!empty($calibration_info)) {echo $calibration_info->iii_v_b_2;} ?>" >
                                </div>
                            </div>
                        </td>
                        <td class="align-middle text-left">
                            <div class="form-group">
                                <div class="input-group">
                                <input type="text"  class="form-control required" autocomplete="off" id="_iii_v_b_3" placeholder="" name='iii_v_b_3' value="<?php if(!empty($calibration_info)) {echo $calibration_info->iii_v_b_3;} ?>" >
                                </div>
                            </div>
                        </td>
                        <td class="align-middle text-left">
                        <div class="form-group">
                                <div class="input-group">
                                <input type="text"  class="form-control required" autocomplete="off" id="exampleInputAmount" placeholder="" name='ii_v_b[]' value="" disabled>
                                </div>
                            </div>
                        </td>
                        <td class="align-middle text-left">
                        <div class="form-group">
                                <div class="input-group">
                                <input type="text"  class="form-control required" autocomplete="off" id="exampleInputAmount" placeholder="" name='ii_v_b[]' value="" disabled>
                                </div>
                            </div>
                        </td>
                    </tr>
                    <tr class="form-inline">
                        <td class="align-middle text-center" style="font-weight:500;"></td>
                        <td class="align-middle text-center">
                            <div class="form-group ">
                                <div class="input-group text-center">
                                <input type="text"  class="form-control required" autocomplete="off" id="exampleInputAmount" placeholder="" name='ii_v_b[]' value="14" disabled>
                                </div>
                            </div>
                        </td>
                        <td class="align-middle text-left">
                            <div class="form-group">
                                <div class="input-group">
                                <input type="text"  class="form-control required" autocomplete="off" id="exampleInputAmount" placeholder="" name='ii_v_b[]' value="2" disabled>
                                </div>
                            </div>
                        </td>
                        <td class="align-middle text-left">
                        <div class="form-group">
                                <div class="input-group">
                                <input type="text"  class="form-control required" autocomplete="off" id="exampleInputAmount" placeholder="" name='ii_v_b[]' value="2" disabled>
                                </div>
                            </div>
                        </td>
                        <td class="align-middle text-left">
                        <div class="form-group">
                                <div class="input-group">
                                <input type="text"  class="form-control required" autocomplete="off" id="_iv_v_b_1" placeholder="" name='iv_v_b_1' value="<?php if(!empty($calibration_info)) {echo $calibration_info->iv_v_b_1;} ?>" >
                                </div>
                            </div>
                        </td>
                        <td class="align-middle text-left">
                        <div class="form-group">
                                <div class="input-group">
                                <input type="text"  class="form-control required" autocomplete="off" id="_iv_v_b_2" placeholder="" name='iv_v_b_2' value="<?php if(!empty($calibration_info)) {echo $calibration_info->iv_v_b_2;} ?>" >
                                </div>
                            </div>
                        </td>
                        <td class="align-middle text-left">
                            <div class="form-group">
                                <div class="input-group">
                                <input type="text"  class="form-control required" autocomplete="off" id="_iv_v_b_3" placeholder="" name='iv_v_b_3' value="<?php if(!empty($calibration_info)) {echo $calibration_info->iv_v_b_3;} ?>" >
                                </div>
                            </div>
                        </td>
                        <td class="align-middle text-left">
                        <div class="form-group">
                                <div class="input-group">
                                <input type="text"  class="form-control required" autocomplete="off" id="exampleInputAmount" placeholder="" name='ii_v_b[]' value="" disabled>
                                </div>
                            </div>
                        </td>
                        <td class="align-middle text-left">
                        <div class="form-group">
                                <div class="input-group">
                                <input type="text"  class="form-control required" autocomplete="off" id="exampleInputAmount" placeholder="" name='ii_v_b[]' value="" disabled>
                                </div>
                            </div>
                        </td>
                    </tr>
                    <tr class="form-inline">
                        <td class="align-middle text-center" style="font-weight:500;">2</td>
                        <td class="align-middle text-center">
                            <div class="form-group ">
                                <div class="input-group text-center">
                                <input type="text"  class="form-control required" autocomplete="off" id="exampleInputAmount" placeholder="" name='ii_v_b[]' value="15" disabled>
                                </div>
                            </div>
                        </td>
                        <td class="align-middle text-left">
                            <div class="form-group">
                                <div class="input-group">
                                <input type="text"  class="form-control required" autocomplete="off" id="exampleInputAmount" placeholder="" name='ii_v_b[]' value="3" disabled>
                                </div>
                            </div>
                        </td>
                        <td class="align-middle text-left">
                        <div class="form-group">
                                <div class="input-group">
                                <input type="text"  class="form-control required" autocomplete="off" id="exampleInputAmount" placeholder="" name='ii_v_b[]' value="1" disabled>
                                </div>
                            </div>
                        </td>
                        <td class="align-middle text-left">
                        <div class="form-group">
                                <div class="input-group">
                                <input type="text"  class="form-control required" autocomplete="off" id="_v_v_b_1" placeholder="" name='v_v_b_1' value="<?php if(!empty($calibration_info)) {echo $calibration_info->v_v_b_1;} ?>" >
                                </div>
                            </div>
                        </td>
                        <td class="align-middle text-left">
                        <div class="form-group">
                                <div class="input-group">
                                <input type="text"  class="form-control required" autocomplete="off" id="_v_v_b_2" placeholder="" name='v_v_b_2' value="<?php if(!empty($calibration_info)) {echo $calibration_info->v_v_b_2;} ?>" >
                                </div>
                            </div>
                        </td>
                        <td class="align-middle text-left">
                            <div class="form-group">
                                <div class="input-group">
                                <input type="text"  class="form-control required" autocomplete="off" id="_v_v_b_3" placeholder="" name='v_v_b_3' value="<?php if(!empty($calibration_info)) {echo $calibration_info->v_v_b_3;} ?>" >
                                </div>
                            </div>
                        </td>
                        <td class="align-middle text-left">
                        <div class="form-group">
                                <div class="input-group">
                                <input type="text"  class="form-control required" autocomplete="off" id="exampleInputAmount" placeholder="" name='ii_v_b[]' value="" disabled>
                                </div>
                            </div>
                        </td>
                        <td class="align-middle text-left">
                        <div class="form-group">
                                <div class="input-group">
                                <input type="text"  class="form-control required" autocomplete="off" id="exampleInputAmount" placeholder="" name='ii_v_b[]' value="" disabled>
                                </div>
                            </div>
                        </td>
                    </tr>
                    <tr class="form-inline">
                        <td class="align-middle text-center" style="font-weight:500;"></td>
                        <td class="align-middle text-center">
                            <div class="form-group ">
                                <div class="input-group text-center">
                                <input type="text"  class="form-control required" autocomplete="off" id="exampleInputAmount" placeholder="" name='ii_v_b[]' value="16" disabled>
                                </div>
                            </div>
                        </td>
                        <td class="align-middle text-left">
                            <div class="form-group">
                                <div class="input-group">
                                <input type="text"  class="form-control required" autocomplete="off" id="exampleInputAmount" placeholder="" name='ii_v_b[]' value="3" disabled>
                                </div>
                            </div>
                        </td>
                        <td class="align-middle text-left">
                        <div class="form-group">
                                <div class="input-group">
                                <input type="text"  class="form-control required" autocomplete="off" id="exampleInputAmount" placeholder="" name='ii_v_b[]' value="2" disabled>
                                </div>
                            </div>
                        </td>
                        <td class="align-middle text-left">
                        <div class="form-group">
                                <div class="input-group">
                                <input type="text"  class="form-control required" autocomplete="off" id="_vi_v_b_1" placeholder="" name='vi_v_b_1' value="<?php if(!empty($calibration_info)) {echo $calibration_info->vi_v_b_1;} ?>" >
                                </div>
                            </div>
                        </td>
                        <td class="align-middle text-left">
                        <div class="form-group">
                                <div class="input-group">
                                <input type="text"  class="form-control required" autocomplete="off" id="_vi_v_b_2" placeholder="" name='vi_v_b_2' value="<?php if(!empty($calibration_info)) {echo $calibration_info->vi_v_b_2;} ?>" >
                                </div>
                            </div>
                        </td>
                        <td class="align-middle text-left">
                            <div class="form-group">
                                <div class="input-group">
                                <input type="text"  class="form-control required" autocomplete="off" id="_vi_v_b_3" placeholder="" name='vi_v_b_3' value="<?php if(!empty($calibration_info)) {echo $calibration_info->vi_v_b_3;} ?>" >
                                </div>
                            </div>
                        </td>
                        <td class="align-middle text-left">
                        <div class="form-group">
                                <div class="input-group">
                                <input type="text"  class="form-control required" autocomplete="off" id="exampleInputAmount" placeholder="" name='ii_v_b[]' value="" disabled>
                                </div>
                            </div>
                        </td>
                        <td class="align-middle text-left">
                        <div class="form-group">
                                <div class="input-group">
                                <input type="text"  class="form-control required" autocomplete="off" id="exampleInputAmount" placeholder="" name='ii_v_b[]' value="" disabled>
                                </div>
                            </div>
                        </td>
                    </tr>
                    <tr class="form-inline">
                        <td class="align-middle text-center" style="font-weight:500;"></td>
                        <td class="align-middle text-center">
                            <div class="form-group ">
                                <div class="input-group text-center">
                                <input type="text"  class="form-control required" autocomplete="off" id="exampleInputAmount" placeholder="" name='ii_v_b[]' value="17" disabled>
                                </div>
                            </div>
                        </td>
                        <td class="align-middle text-left">
                            <div class="form-group">
                                <div class="input-group">
                                <input type="text"  class="form-control required" autocomplete="off" id="exampleInputAmount" placeholder="" name='ii_v_b[]' value="4" disabled>
                                </div>
                            </div>
                        </td>
                        <td class="align-middle text-left">
                        <div class="form-group">
                                <div class="input-group">
                                <input type="text"  class="form-control required" autocomplete="off" id="exampleInputAmount" placeholder="" name='ii_v_b[]' value="1" disabled>
                                </div>
                            </div>
                        </td>
                        <td class="align-middle text-left">
                        <div class="form-group">
                                <div class="input-group">
                                <input type="text"  class="form-control required" autocomplete="off" id="_vii_v_b_1" placeholder="" name='vii_v_b_1' value="<?php if(!empty($calibration_info)) {echo $calibration_info->vii_v_b_1;} ?>" >
                                </div>
                            </div>
                        </td>
                        <td class="align-middle text-left">
                        <div class="form-group">
                                <div class="input-group">
                                <input type="text"  class="form-control required" autocomplete="off" id="_vii_v_b_2" placeholder="" name='vii_v_b_2' value="<?php if(!empty($calibration_info)) {echo $calibration_info->vii_v_b_2;} ?>" >
                                </div>
                            </div>
                        </td>
                        <td class="align-middle text-left">
                            <div class="form-group">
                                <div class="input-group">
                                <input type="text"  class="form-control required" autocomplete="off" id="_vii_v_b_3" placeholder="" name='vii_v_b_3' value="<?php if(!empty($calibration_info)) {echo $calibration_info->vii_v_b_3;} ?>" >
                                </div>
                            </div>
                        </td>
                        <td class="align-middle text-left">
                        <div class="form-group">
                                <div class="input-group">
                                <input type="text"  class="form-control required" autocomplete="off" id="exampleInputAmount" placeholder="" name='ii_v_b[]' value="" disabled>
                                </div>
                            </div>
                        </td>
                        <td class="align-middle text-left">
                        <div class="form-group">
                                <div class="input-group">
                                <input type="text"  class="form-control required" autocomplete="off" id="exampleInputAmount" placeholder="" name='ii_v_b[]' value="" disabled>
                                </div>
                            </div>
                        </td>
                    </tr>
                    <tr class="form-inline">
                        <td class="align-middle text-center" style="font-weight:500;"></td>
                        <td class="align-middle text-center">
                            <div class="form-group ">
                                <div class="input-group text-center">
                                <input type="text"  class="form-control required" autocomplete="off" id="exampleInputAmount" placeholder="" name='ii_v_b[]' value="18" disabled>
                                </div>
                            </div>
                        </td>
                        <td class="align-middle text-left">
                            <div class="form-group">
                                <div class="input-group">
                                <input type="text"  class="form-control required" autocomplete="off" id="exampleInputAmount" placeholder="" name='ii_v_b[]' value="4" disabled>
                                </div>
                            </div>
                        </td>
                        <td class="align-middle text-left">
                        <div class="form-group">
                                <div class="input-group">
                                <input type="text"  class="form-control required" autocomplete="off" id="exampleInputAmount" placeholder="" name='ii_v_b[]' value="2" disabled>
                                </div>
                            </div>
                        </td>
                        <td class="align-middle text-left">
                        <div class="form-group">
                                <div class="input-group">
                                <input type="text"  class="form-control required" autocomplete="off" id="_viii_v_b_1" placeholder="" name='viii_v_b_1' value="<?php if(!empty($calibration_info)) {echo $calibration_info->viii_v_b_1;} ?>" >
                                </div>
                            </div>
                        </td>
                        <td class="align-middle text-left">
                        <div class="form-group">
                                <div class="input-group">
                                <input type="text"  class="form-control required" autocomplete="off" id="_viii_v_b_2" placeholder="" name='viii_v_b_2' value="<?php if(!empty($calibration_info)) {echo $calibration_info->viii_v_b_2;} ?>" >
                                </div>
                            </div>
                        </td>
                        <td class="align-middle text-left">
                            <div class="form-group">
                                <div class="input-group">
                                <input type="text"  class="form-control required" autocomplete="off" id="viii_v_b_3" placeholder="" name='viii_v_b_3' value="<?php if(!empty($calibration_info)) {echo $calibration_info->viii_v_b_3;} ?>" >
                                </div>
                            </div>
                        </td>
                        <td class="align-middle text-left">
                        <div class="form-group">
                                <div class="input-group">
                                <input type="text"  class="form-control required" autocomplete="off" id="exampleInputAmount" placeholder="" name='ii_v_b[]' value="" disabled>
                                </div>
                            </div>
                        </td>
                        <td class="align-middle text-left">
                        <div class="form-group">
                                <div class="input-group">
                                <input type="text"  class="form-control required" autocomplete="off" id="exampleInputAmount" placeholder="" name='ii_v_b[]' value="" disabled>
                                </div>
                            </div>
                        </td>
                    </tr>
                    <tr class="form-inline">
                        <td class="align-middle text-center" style="font-weight:500;"></td>
                        <td class="align-middle text-center">
                            <div class="form-group ">
                                <div class="input-group text-center">
                                <input type="text"  class="form-control required" autocomplete="off" id="exampleInputAmount" placeholder="" name='ii_v_b[]' value="19" disabled>
                                </div>
                            </div>
                        </td>
                        <td class="align-middle text-left">
                            <div class="form-group">
                                <div class="input-group">
                                <input type="text"  class="form-control required" autocomplete="off" id="exampleInputAmount" placeholder="" name='ii_v_b[]' value="5" disabled>
                                </div>
                            </div>
                        </td>
                        <td class="align-middle text-left">
                        <div class="form-group">
                                <div class="input-group">
                                <input type="text"  class="form-control required" autocomplete="off" id="exampleInputAmount" placeholder="" name='ii_v_b[]' value="1" disabled>
                                </div>
                            </div>
                        </td>
                        <td class="align-middle text-left">
                        <div class="form-group">
                                <div class="input-group">
                                <input type="text"  class="form-control required" autocomplete="off" id="_xi_v_b_1" placeholder="" name='xi_v_b_1' value="<?php if(!empty($calibration_info)) {echo $calibration_info->xi_v_b_1;} ?>" >
                                </div>
                            </div>
                        </td>
                        <td class="align-middle text-left">
                        <div class="form-group">
                                <div class="input-group">
                                <input type="text"  class="form-control required" autocomplete="off" id="_xi_v_b_2" placeholder="" name='xi_v_b_2' value="<?php if(!empty($calibration_info)) {echo $calibration_info->xi_v_b_2;} ?>" >
                                </div>
                            </div>
                        </td>
                        <td class="align-middle text-left">
                            <div class="form-group">
                                <div class="input-group">
                                <input type="text"  class="form-control required" autocomplete="off" id="_xi_v_b_3" placeholder="" name='xi_v_b_3' value="<?php if(!empty($calibration_info)) {echo $calibration_info->xi_v_b_3;} ?>" >
                                </div>
                            </div>
                        </td>
                        <td class="align-middle text-left">
                        <div class="form-group">
                                <div class="input-group">
                                <input type="text"  class="form-control required" autocomplete="off" id="exampleInputAmount" placeholder="" name='ii_v_b[]' value="" disabled>
                                </div>
                            </div>
                        </td>
                        <td class="align-middle text-left">
                        <div class="form-group">
                                <div class="input-group">
                                <input type="text"  class="form-control required" autocomplete="off" id="exampleInputAmount" placeholder="" name='ii_v_b[]' value="" disabled>
                                </div>
                            </div>
                        </td>
                    </tr>
                    <tr class="form-inline">
                        <td class="align-middle text-center" style="font-weight:500;"></td>
                        <td class="align-middle text-center">
                            <div class="form-group ">
                                <div class="input-group text-center">
                                <input type="text"  class="form-control required" autocomplete="off" id="exampleInputAmount" placeholder="" name='ii_v_b[]' value="20" disabled>
                                </div>
                            </div>
                        </td>
                        <td class="align-middle text-left">
                            <div class="form-group">
                                <div class="input-group">
                                <input type="text"  class="form-control required" autocomplete="off" id="exampleInputAmount" placeholder="" name='ii_v_b[]' value="5" disabled>
                                </div>
                            </div>
                        </td>
                        <td class="align-middle text-left">
                        <div class="form-group">
                                <div class="input-group">
                                <input type="text"  class="form-control required" autocomplete="off" id="exampleInputAmount" placeholder="" name='ii_v_b[]' value="2" disabled>
                                </div>
                            </div>
                        </td>
                        <td class="align-middle text-left">
                        <div class="form-group">
                                <div class="input-group">
                                <input type="text"  class="form-control required" autocomplete="off" id="_x_v_b_1" placeholder="" name='x_v_b_1' value="<?php if(!empty($calibration_info)) {echo $calibration_info->x_v_b_1;} ?>" >
                                </div>
                            </div>
                        </td>
                        <td class="align-middle text-left">
                        <div class="form-group">
                                <div class="input-group">
                                <input type="text"  class="form-control required" autocomplete="off" id="_x_v_b_2" placeholder="" name='x_v_b_2' value="<?php if(!empty($calibration_info)) {echo $calibration_info->x_v_b_2;} ?>" >
                                </div>
                            </div>
                        </td>
                        <td class="align-middle text-left">
                            <div class="form-group">
                                <div class="input-group">
                                <input type="text"  class="form-control required" autocomplete="off" id="_x_v_b_3" placeholder="" name='x_v_b_3' value="<?php if(!empty($calibration_info)) {echo $calibration_info->x_v_b_3;} ?>" >
                                </div>
                            </div>
                        </td>
                        <td class="align-middle text-left">
                        <div class="form-group">
                                <div class="input-group">
                                <input type="text"  class="form-control required" autocomplete="off" id="exampleInputAmount" placeholder="" name='ii_v_b[]' value="" disabled>
                                </div>
                            </div>
                        </td>
                        <td class="align-middle text-left">
                        <div class="form-group">
                                <div class="input-group">
                                <input type="text"  class="form-control required" autocomplete="off" id="exampleInputAmount" placeholder="" name='xi_v_b[]' value="" disabled>
                                </div>
                            </div>
                        </td>
                    </tr>
                    <tr class="form-inline">
                        <td class="align-middle text-center" style="font-weight:500;"></td>
                        <td class="align-middle text-center">
                            <div class="form-group ">
                                <div class="input-group text-center">
                                <input type="text"  class="form-control required" autocomplete="off" id="exampleInputAmount" placeholder="2" name='ii_v_cc[]' value="STOP TIME" disabled>
                                </div>
                            </div>
                        </td>
                        <td class="align-middle text-center">
                            <div class="form-group">
                                <div class="input-group text-center">
                                    <input type="time" class="form-control required" autocomplete="off" id="stop_time_2" name="stop_time_2" value="<?php if(!empty($calibration_info)) {echo date('H:i', strtotime($calibration_info->stop_time_2));} ?>">
                                </div>
                            </div>
                        </td>
                        <td class="align-middle text-left">
                        <div class="form-group">
                                <div class="input-group">
                                <input type="text"  class="form-control required" autocomplete="off" id="exampleInputAmount" placeholder="" name='ii_v_cc[]' value="" disabled>
                                </div>
                            </div>
                        </td>
                        <td class="align-middle text-left">
                        <div class="form-group">
                                <div class="input-group">
                                <input type="text"  class="form-control required" autocomplete="off" id="_i_v_cc_1" placeholder="" name='i_v_cc_1' value="" disabled>
                                </div>
                            </div>
                        </td>
                        <td class="align-middle text-left">
                        <div class="form-group">
                                <div class="input-group">
                                <input type="text"  class="form-control required" autocomplete="off" id="_i_v_cc_2" placeholder="" name='i_v_cc_2' value="" disabled>
                                </div>
                            </div>
                        </td>
                        <td class="align-middle text-left">
                            <div class="form-group">
                                <div class="input-group">
                                <input type="text"  class="form-control required" autocomplete="off" id="_i_v_cc_3" placeholder="" name='i_v_cc_3' value="" disabled>
                                </div>
                            </div>
                        </td>
                        <td class="align-middle text-left">
                        <div class="form-group">
                                <div class="input-group">
                                <input type="text"  class="form-control required" autocomplete="off" id="exampleInputAmount" placeholder="" name='ii_v_cc[]' value="" disabled>
                                </div>
                            </div>
                        </td>
                        <td class="align-middle text-left">
                        <div class="form-group">
                                <div class="input-group">
                                <input type="text"  class="form-control required" autocomplete="off" id="exampleInputAmount" placeholder="" name='ii_v_cc[]' value="" disabled>
                                </div>
                            </div>
                        </td>
                    </tr>
                    <!-- Add more rows as needed -->
                </tbody>               
            </table>
            <button type="button" class="btn btn-secondary prev-step">Previous</button>
            <button type="button" class="btn btn-primary next-step">Next</button>
            </div>
            
            
            <!-- Table A4 -->
            <div class="form-section">
            <b>PRE-CALIBRATION CHECKS REPORT ENTRY 3</b>
            <table class="table" id="serviceFields">
                <thead>
                    <tr>
                        <th class="text-left" style="font-weight:500;">Series</th>
                        <th class="text-left" style="font-weight:500;">Seq. No.</th>
                        <th class="text-left" style="font-weight:500;">Set</th>
                        <th class="text-left" style="font-weight:500;">Rover Point </th>
                        <th class="text-left" style="font-weight:500;">Eastings</th>
                        <th class="text-left" style="font-weight:500;">Northings</th>
                        <th class="text-left" style="font-weight:500;">Elevation</th>
                        <th class="text-left" style="font-weight:500;">Horizontal Distance (m)</th>
                        <th class="text-left" style="font-weight:500;">Elevation Accuracy (mm)</th>
                    </tr>
                </thead>
                <tbody>
                    <tr class="form-inline">
                        <td class="align-middle text-center" style="font-weight:500;"></td>
                        <td class="align-middle text-center">
                            <div class="form-group ">
                                <div class="input-group text-center">
                                <input type="text"  class="form-control required" autocomplete="off" id="exampleInputAmount" placeholder="2" name='ii_v_cc[]' value="START TIME" disabled>
                                </div>
                            </div>
                        </td>
                        <td class="align-middle text-center">
                            <div class="form-group">
                                <div class="input-group">
                                    <input type="time" class="form-control required" id="start_time_3" name="start_time_3" autocomplete="off" value="<?php echo !empty($calibration_info->start_time_3) ? date('H:i', strtotime($calibration_info->stop_time_1)) : ''; ?>">
                                </div>
                            </div>
                        </td>
                        <td class="align-middle text-left">
                        <div class="form-group">
                                <div class="input-group">
                                <input type="text"  class="form-control required" autocomplete="off" id="exampleInputAmount" placeholder="" name='ii_v_cc[]' value="" disabled>
                                </div>
                            </div>
                        </td>
                        <td class="align-middle text-left">
                        <div class="form-group">
                                <div class="input-group">
                                <input type="text"  class="form-control required" autocomplete="off" id="_i_v_cc_1" placeholder="" name='i_v_cc_1' value="" disabled>
                                </div>
                            </div>
                        </td>
                        <td class="align-middle text-left">
                        <div class="form-group">
                                <div class="input-group">
                                <input type="text"  class="form-control required" autocomplete="off" id="_i_v_cc_2" placeholder="" name='i_v_cc_2' value="" disabled>
                                </div>
                            </div>
                        </td>
                        <td class="align-middle text-left">
                            <div class="form-group">
                                <div class="input-group">
                                <input type="text"  class="form-control required" autocomplete="off" id="_i_v_cc_3" placeholder="" name='i_v_cc_3' value="" disabled>
                                </div>
                            </div>
                        </td>
                        <td class="align-middle text-left">
                        <div class="form-group">
                                <div class="input-group">
                                <input type="text"  class="form-control required" autocomplete="off" id="exampleInputAmount" placeholder="" name='ii_v_cc[]' value="" disabled>
                                </div>
                            </div>
                        </td>
                        <td class="align-middle text-left">
                        <div class="form-group">
                                <div class="input-group">
                                <input type="text"  class="form-control required" autocomplete="off" id="exampleInputAmount" placeholder="" name='ii_v_cc[]' value="" disabled>
                                </div>
                            </div>
                        </td>
                    </tr>
                    <tr class="form-inline">
                        <td class="align-middle text-center" style="font-weight:500;"></td>
                        <td class="align-middle text-center">
                            <div class="form-group ">
                                <div class="input-group text-center">
                                <input type="text"  class="form-control required" autocomplete="off" id="exampleInputAmount" placeholder="2" name='ii_v_c[]' value="21" disabled>
                                </div>
                            </div>
                        </td>
                        <td class="align-middle text-left">
                            <div class="form-group">
                                <div class="input-group">
                                <input type="text"  class="form-control required" autocomplete="off" id="exampleInputAmount" placeholder="" name='ii_v_c[]' value="1" disabled>
                                </div>
                            </div>
                        </td>
                        <td class="align-middle text-left">
                        <div class="form-group">
                                <div class="input-group">
                                <input type="text"  class="form-control required" autocomplete="off" id="exampleInputAmount" placeholder="" name='ii_v_c[]' value="1" disabled>
                                </div>
                            </div>
                        </td>
                        <td class="align-middle text-left">
                        <div class="form-group">
                                <div class="input-group">
                                <input type="text"  class="form-control required" autocomplete="off" id="_i_v_c_1" placeholder="" name='i_v_c_1' value="<?php if(!empty($calibration_info)) {echo $calibration_info->i_v_c_1;} ?>" required >
                                </div>
                            </div>
                        </td>
                        <td class="align-middle text-left">
                        <div class="form-group">
                                <div class="input-group">
                                <input type="text"  class="form-control required" autocomplete="off" id="_i_v_c_2" placeholder="" name='i_v_c_2' value="<?php if(!empty($calibration_info)) {echo $calibration_info->i_v_c_2;} ?>" required>
                                </div>
                            </div>
                        </td>
                        <td class="align-middle text-left">
                            <div class="form-group">
                                <div class="input-group">
                                <input type="text"  class="form-control required" autocomplete="off" id="_i_v_c_3" placeholder="" name='i_v_c_3' value="<?php if(!empty($calibration_info)) {echo $calibration_info->i_v_c_3;} ?>" required>
                                </div>
                            </div>
                        </td>
                        <td class="align-middle text-left">
                        <div class="form-group">
                                <div class="input-group">
                                <input type="text"  class="form-control required" autocomplete="off" id="exampleInputAmount" placeholder="" name='ii_v_c[]' value="" disabled>
                                </div>
                            </div>
                        </td>
                        <td class="align-middle text-left">
                        <div class="form-group">
                                <div class="input-group">
                                <input type="text"  class="form-control required" autocomplete="off" id="exampleInputAmount" placeholder="" name='ii_v_c[]' value="" disabled>
                                </div>
                            </div>
                        </td>
                    </tr>
                    <tr class="form-inline">
                        <td class="align-middle text-center" style="font-weight:500;"></td>
                        <td class="align-middle text-center">
                            <div class="form-group ">
                                <div class="input-group text-center">
                                <input type="text"  class="form-control required" autocomplete="off" id="exampleInputAmount" placeholder="1" name='ii_v_c[]' value="22" disabled>
                                </div>
                            </div>
                        </td>
                        <td class="align-middle text-left">
                            <div class="form-group">
                                <div class="input-group">
                                <input type="text"  class="form-control required" autocomplete="off" id="exampleInputAmount" placeholder="" name='ii_v_c[]' value="1" disabled>
                                </div>
                            </div>
                        </td>
                        <td class="align-middle text-left">
                        <div class="form-group">
                                <div class="input-group">
                                <input type="text"  class="form-control required" autocomplete="off" id="exampleInputAmount" placeholder="" name='ii_v_c[]' value="2" disabled>
                                </div>
                            </div>
                        </td>
                        <td class="align-middle text-left">
                        <div class="form-group">
                                <div class="input-group">
                                <input type="text"  class="form-control required" autocomplete="off" id="_ii_v_c_1" placeholder="" name='ii_v_c_1' value="<?php if(!empty($calibration_info)) {echo $calibration_info->ii_v_c_1;} ?>" >
                                </div>
                            </div>
                        </td>
                        <td class="align-middle text-left">
                        <div class="form-group">
                                <div class="input-group">
                                <input type="text"  class="form-control required" autocomplete="off" id="_ii_v_c_2" placeholder="" name='ii_v_c_2' value="<?php if(!empty($calibration_info)) {echo $calibration_info->ii_v_c_2;} ?>" >
                                </div>
                            </div>
                        </td>
                        <td class="align-middle text-left">
                            <div class="form-group">
                                <div class="input-group">
                                <input type="text"  class="form-control required" autocomplete="off" id="_ii_v_c_3" placeholder="" name='ii_v_c_3' value="<?php if(!empty($calibration_info)) {echo $calibration_info->ii_v_c_3;} ?>" >
                                </div>
                            </div>
                        </td>
                        <td class="align-middle text-left">
                        <div class="form-group">
                                <div class="input-group">
                                <input type="text"  class="form-control required" autocomplete="off" id="exampleInputAmount" placeholder="" name='ii_v_c[]' value="" disabled>
                                </div>
                            </div>
                        </td>
                        <td class="align-middle text-left">
                        <div class="form-group">
                                <div class="input-group">
                                <input type="text"  class="form-control required" autocomplete="off" id="exampleInputAmount" placeholder="" name='ii_v_c[]' value="" disabled>
                                </div>
                            </div>
                        </td>
                    </tr>
                    <tr class="form-inline">
                        <td class="align-middle text-center" style="font-weight:500;"></td>
                        <td class="align-middle text-center">
                            <div class="form-group ">
                                <div class="input-group text-center">
                                <input type="text"  class="form-control required" autocomplete="off" id="exampleInputAmount" placeholder="1" name='ii_v_c[]' value="23" disabled>
                                </div>
                            </div>
                        </td>
                        <td class="align-middle text-left">
                            <div class="form-group">
                                <div class="input-group">
                                <input type="text"  class="form-control required" autocomplete="off" id="exampleInputAmount" placeholder="" name='ii_v_c[]' value="2" disabled>
                                </div>
                            </div>
                        </td>
                        <td class="align-middle text-left">
                        <div class="form-group">
                                <div class="input-group">
                                <input type="text"  class="form-control required" autocomplete="off" id="exampleInputAmount" placeholder="" name='ii_v_c[]' value="1" disabled>
                                </div>
                            </div>
                        </td>
                        <td class="align-middle text-left">
                        <div class="form-group">
                                <div class="input-group">
                                <input type="text"  class="form-control required" autocomplete="off" id="_iii_v_c_1" placeholder="" name='iii_v_c_1' value="<?php if(!empty($calibration_info)) {echo $calibration_info->iii_v_c_1;} ?>" >
                                </div>
                            </div>
                        </td>
                        <td class="align-middle text-left">
                        <div class="form-group">
                                <div class="input-group">
                                <input type="text"  class="form-control required" autocomplete="off" id="_iii_v_c_2" placeholder="" name='iii_v_c_2' value="<?php if(!empty($calibration_info)) {echo $calibration_info->iii_v_c_2;} ?>" >
                                </div>
                            </div>
                        </td>
                        <td class="align-middle text-left">
                            <div class="form-group">
                                <div class="input-group">
                                <input type="text"  class="form-control required" autocomplete="off" id="_iii_v_c_3" placeholder="" name='iii_v_c_3' value="<?php if(!empty($calibration_info)) {echo $calibration_info->iii_v_c_3;} ?>" >
                                </div>
                            </div>
                        </td>
                        <td class="align-middle text-left">
                        <div class="form-group">
                                <div class="input-group">
                                <input type="text"  class="form-control required" autocomplete="off" id="exampleInputAmount" placeholder="" name='ii_v_c[]' value="" disabled>
                                </div>
                            </div>
                        </td>
                        <td class="align-middle text-left">
                        <div class="form-group">
                                <div class="input-group">
                                <input type="text"  class="form-control required" autocomplete="off" id="exampleInputAmount" placeholder="" name='ii_v_c[]' value="" disabled>
                                </div>
                            </div>
                        </td>
                    </tr>
                    <tr class="form-inline">
                        <td class="align-middle text-center" style="font-weight:500;"></td>
                        <td class="align-middle text-center">
                            <div class="form-group ">
                                <div class="input-group text-center">
                                <input type="text"  class="form-control required" autocomplete="off" id="exampleInputAmount" placeholder="" name='ii_v_c[]' value="24" disabled>
                                </div>
                            </div>
                        </td>
                        <td class="align-middle text-left">
                            <div class="form-group">
                                <div class="input-group">
                                <input type="text"  class="form-control required" autocomplete="off" id="exampleInputAmount" placeholder="" name='ii_v_c[]' value="2" disabled>
                                </div>
                            </div>
                        </td>
                        <td class="align-middle text-left">
                        <div class="form-group">
                                <div class="input-group">
                                <input type="text"  class="form-control required" autocomplete="off" id="exampleInputAmount" placeholder="" name='ii_v_c[]' value="2" disabled>
                                </div>
                            </div>
                        </td>
                        <td class="align-middle text-left">
                        <div class="form-group">
                                <div class="input-group">
                                <input type="text"  class="form-control required" autocomplete="off" id="_iv_v_c_1" placeholder="" name='iv_v_c_1' value="<?php if(!empty($calibration_info)) {echo $calibration_info->iv_v_c_1;} ?>" >
                                </div>
                            </div>
                        </td>
                        <td class="align-middle text-left">
                        <div class="form-group">
                                <div class="input-group">
                                <input type="text"  class="form-control required" autocomplete="off" id="_iv_v_c_2" placeholder="" name='iv_v_c_2' value="<?php if(!empty($calibration_info)) {echo $calibration_info->iv_v_c_2;} ?>" >
                                </div>
                            </div>
                        </td>
                        <td class="align-middle text-left">
                            <div class="form-group">
                                <div class="input-group">
                                <input type="text"  class="form-control required" autocomplete="off" id="_iv_v_c_3" placeholder="" name='iv_v_c_3' value="<?php if(!empty($calibration_info)) {echo $calibration_info->iv_v_c_3;} ?>" >
                                </div>
                            </div>
                        </td>
                        <td class="align-middle text-left">
                        <div class="form-group">
                                <div class="input-group">
                                <input type="text"  class="form-control required" autocomplete="off" id="exampleInputAmount" placeholder="" name='ii_v_c[]' value="" disabled>
                                </div>
                            </div>
                        </td>
                        <td class="align-middle text-left">
                        <div class="form-group">
                                <div class="input-group">
                                <input type="text"  class="form-control required" autocomplete="off" id="exampleInputAmount" placeholder="" name='ii_v_c[]' value="" disabled>
                                </div>
                            </div>
                        </td>
                    </tr>
                    <tr class="form-inline">
                        <td class="align-middle text-center" style="font-weight:500;">3</td>
                        <td class="align-middle text-center">
                            <div class="form-group ">
                                <div class="input-group text-center">
                                <input type="text"  class="form-control required" autocomplete="off" id="exampleInputAmount" placeholder="" name='ii_v_c[]' value="25" disabled>
                                </div>
                            </div>
                        </td>
                        <td class="align-middle text-left">
                            <div class="form-group">
                                <div class="input-group">
                                <input type="text"  class="form-control required" autocomplete="off" id="exampleInputAmount" placeholder="" name='ii_v_c[]' value="3" disabled>
                                </div>
                            </div>
                        </td>
                        <td class="align-middle text-left">
                        <div class="form-group">
                                <div class="input-group">
                                <input type="text"  class="form-control required" autocomplete="off" id="exampleInputAmount" placeholder="" name='ii_v_c[]' value="1" disabled>
                                </div>
                            </div>
                        </td>
                        <td class="align-middle text-left">
                        <div class="form-group">
                                <div class="input-group">
                                <input type="text"  class="form-control required" autocomplete="off" id="_v_v_c_1" placeholder="" name='v_v_c_1' value="<?php if(!empty($calibration_info)) {echo $calibration_info->v_v_c_1;} ?>" >
                                </div>
                            </div>
                        </td>
                        <td class="align-middle text-left">
                        <div class="form-group">
                                <div class="input-group">
                                <input type="text"  class="form-control required" autocomplete="off" id="_v_v_c_2" placeholder="" name='v_v_c_2' value="<?php if(!empty($calibration_info)) {echo $calibration_info->v_v_c_2;} ?>" >
                                </div>
                            </div>
                        </td>
                        <td class="align-middle text-left">
                            <div class="form-group">
                                <div class="input-group">
                                <input type="text"  class="form-control required" autocomplete="off" id="_v_v_c_3" placeholder="" name='v_v_c_3' value="<?php if(!empty($calibration_info)) {echo $calibration_info->v_v_c_3;} ?>" >
                                </div>
                            </div>
                        </td>
                        <td class="align-middle text-left">
                        <div class="form-group">
                                <div class="input-group">
                                <input type="text"  class="form-control required" autocomplete="off" id="exampleInputAmount" placeholder="" name='ii_v_c[]' value="" disabled>
                                </div>
                            </div>
                        </td>
                        <td class="align-middle text-left">
                        <div class="form-group">
                                <div class="input-group">
                                <input type="text"  class="form-control required" autocomplete="off" id="exampleInputAmount" placeholder="" name='ii_v_c[]' value="" disabled>
                                </div>
                            </div>
                        </td>
                    </tr>
                    <tr class="form-inline">
                        <td class="align-middle text-center" style="font-weight:500;"></td>
                        <td class="align-middle text-center">
                            <div class="form-group ">
                                <div class="input-group text-center">
                                <input type="text"  class="form-control required" autocomplete="off" id="exampleInputAmount" placeholder="" name='ii_v_c[]' value="26" disabled>
                                </div>
                            </div>
                        </td>
                        <td class="align-middle text-left">
                            <div class="form-group">
                                <div class="input-group">
                                <input type="text"  class="form-control required" autocomplete="off" id="exampleInputAmount" placeholder="" name='ii_v_c[]' value="3" disabled>
                                </div>
                            </div>
                        </td>
                        <td class="align-middle text-left">
                        <div class="form-group">
                                <div class="input-group">
                                <input type="text"  class="form-control required" autocomplete="off" id="exampleInputAmount" placeholder="" name='ii_v_c[]' value="2" disabled>
                                </div>
                            </div>
                        </td>
                        <td class="align-middle text-left">
                        <div class="form-group">
                                <div class="input-group">
                                <input type="text"  class="form-control required" autocomplete="off" id="_vi_v_c_1" placeholder="" name='vi_v_c_1' value="<?php if(!empty($calibration_info)) {echo $calibration_info->vi_v_c_1;} ?>" >
                                </div>
                            </div>
                        </td>
                        <td class="align-middle text-left">
                        <div class="form-group">
                                <div class="input-group">
                                <input type="text"  class="form-control required" autocomplete="off" id="_vi_v_c_2" placeholder="" name='vi_v_c_2' value="<?php if(!empty($calibration_info)) {echo $calibration_info->vi_v_c_2;} ?>" >
                                </div>
                            </div>
                        </td>
                        <td class="align-middle text-left">
                            <div class="form-group">
                                <div class="input-group">
                                <input type="text"  class="form-control required" autocomplete="off" id="_vi_v_c_3" placeholder="" name='vi_v_c_3' value="<?php if(!empty($calibration_info)) {echo $calibration_info->vi_v_c_3;} ?>" >
                                </div>
                            </div>
                        </td>
                        <td class="align-middle text-left">
                        <div class="form-group">
                                <div class="input-group">
                                <input type="text"  class="form-control required" autocomplete="off" id="exampleInputAmount" placeholder="" name='ii_v_c[]' value="" disabled>
                                </div>
                            </div>
                        </td>
                        <td class="align-middle text-left">
                        <div class="form-group">
                                <div class="input-group">
                                <input type="text"  class="form-control required" autocomplete="off" id="exampleInputAmount" placeholder="" name='ii_v_c[]' value="" disabled>
                                </div>
                            </div>
                        </td>
                    </tr>
                    <tr class="form-inline">
                        <td class="align-middle text-center" style="font-weight:500;"></td>
                        <td class="align-middle text-center">
                            <div class="form-group ">
                                <div class="input-group text-center">
                                <input type="text"  class="form-control required" autocomplete="off" id="exampleInputAmount" placeholder="" name='ii_v_c[]' value="27" disabled>
                                </div>
                            </div>
                        </td>
                        <td class="align-middle text-left">
                            <div class="form-group">
                                <div class="input-group">
                                <input type="text"  class="form-control required" autocomplete="off" id="exampleInputAmount" placeholder="" name='ii_v_c[]' value="4" disabled>
                                </div>
                            </div>
                        </td>
                        <td class="align-middle text-left">
                        <div class="form-group">
                                <div class="input-group">
                                <input type="text"  class="form-control required" autocomplete="off" id="exampleInputAmount" placeholder="" name='ii_v_c[]' value="1" disabled>
                                </div>
                            </div>
                        </td>
                        <td class="align-middle text-left">
                        <div class="form-group">
                                <div class="input-group">
                                <input type="text"  class="form-control required" autocomplete="off" id="_vii_v_c_1" placeholder="" name='vii_v_c_1' value="<?php if(!empty($calibration_info)) {echo $calibration_info->vii_v_c_1;} ?>" >
                                </div>
                            </div>
                        </td>
                        <td class="align-middle text-left">
                        <div class="form-group">
                                <div class="input-group">
                                <input type="text"  class="form-control required" autocomplete="off" id="_vii_v_c_2" placeholder="" name='vii_v_c_2' value="<?php if(!empty($calibration_info)) {echo $calibration_info->vii_v_c_2;} ?>" >
                                </div>
                            </div>
                        </td>
                        <td class="align-middle text-left">
                            <div class="form-group">
                                <div class="input-group">
                                <input type="text"  class="form-control required" autocomplete="off" id="_vii_v_c_3" placeholder="" name='vii_v_c_3' value="<?php if(!empty($calibration_info)) {echo $calibration_info->vii_v_c_3;} ?>" >
                                </div>
                            </div>
                        </td>
                        <td class="align-middle text-left">
                        <div class="form-group">
                                <div class="input-group">
                                <input type="text"  class="form-control required" autocomplete="off" id="exampleInputAmount" placeholder="" name='ii_v_c[]' value="" disabled>
                                </div>
                            </div>
                        </td>
                        <td class="align-middle text-left">
                        <div class="form-group">
                                <div class="input-group">
                                <input type="text"  class="form-control required" autocomplete="off" id="exampleInputAmount" placeholder="" name='ii_v_c[]' value="" disabled>
                                </div>
                            </div>
                        </td>
                    </tr>
                    <tr class="form-inline">
                        <td class="align-middle text-center" style="font-weight:500;"></td>
                        <td class="align-middle text-center">
                            <div class="form-group ">
                                <div class="input-group text-center">
                                <input type="text"  class="form-control required" autocomplete="off" id="exampleInputAmount" placeholder="" name='ii_v_c[]' value="28" disabled>
                                </div>
                            </div>
                        </td>
                        <td class="align-middle text-left">
                            <div class="form-group">
                                <div class="input-group">
                                <input type="text"  class="form-control required" autocomplete="off" id="exampleInputAmount" placeholder="" name='ii_v_c[]' value="4" disabled>
                                </div>
                            </div>
                        </td>
                        <td class="align-middle text-left">
                        <div class="form-group">
                                <div class="input-group">
                                <input type="text"  class="form-control required" autocomplete="off" id="exampleInputAmount" placeholder="" name='ii_v_c[]' value="2" disabled>
                                </div>
                            </div>
                        </td>
                        <td class="align-middle text-left">
                        <div class="form-group">
                                <div class="input-group">
                                <input type="text"  class="form-control required" autocomplete="off" id="_viii_v_c_1" placeholder="" name='viii_v_c_1' value="<?php if(!empty($calibration_info)) {echo $calibration_info->viii_v_c_1;} ?>" >
                                </div>
                            </div>
                        </td>
                        <td class="align-middle text-left">
                        <div class="form-group">
                                <div class="input-group">
                                <input type="text"  class="form-control required" autocomplete="off" id="_viii_v_c_2" placeholder="" name='viii_v_c_2' value="<?php if(!empty($calibration_info)) {echo $calibration_info->viii_v_c_2;} ?>" >
                                </div>
                            </div>
                        </td>
                        <td class="align-middle text-left">
                            <div class="form-group">
                                <div class="input-group">
                                <input type="text"  class="form-control required" autocomplete="off" id="viii_v_c_3" placeholder="" name='viii_v_c_3' value="<?php if(!empty($calibration_info)) {echo $calibration_info->viii_v_c_3;} ?>" >
                                </div>
                            </div>
                        </td>
                        <td class="align-middle text-left">
                        <div class="form-group">
                                <div class="input-group">
                                <input type="text"  class="form-control required" autocomplete="off" id="exampleInputAmount" placeholder="" name='ii_v_c[]' value="" disabled>
                                </div>
                            </div>
                        </td>
                        <td class="align-middle text-left">
                        <div class="form-group">
                                <div class="input-group">
                                <input type="text"  class="form-control required" autocomplete="off" id="exampleInputAmount" placeholder="" name='ii_v_c[]' value="" disabled>
                                </div>
                            </div>
                        </td>
                    </tr>
                    <tr class="form-inline">
                        <td class="align-middle text-center" style="font-weight:500;"></td>
                        <td class="align-middle text-center">
                            <div class="form-group ">
                                <div class="input-group text-center">
                                <input type="text"  class="form-control required" autocomplete="off" id="exampleInputAmount" placeholder="" name='ii_v_c[]' value="29" disabled>
                                </div>
                            </div>
                        </td>
                        <td class="align-middle text-left">
                            <div class="form-group">
                                <div class="input-group">
                                <input type="text"  class="form-control required" autocomplete="off" id="exampleInputAmount" placeholder="" name='ii_v_c[]' value="5" disabled>
                                </div>
                            </div>
                        </td>
                        <td class="align-middle text-left">
                        <div class="form-group">
                                <div class="input-group">
                                <input type="text"  class="form-control required" autocomplete="off" id="exampleInputAmount" placeholder="" name='ii_v_c[]' value="1" disabled>
                                </div>
                            </div>
                        </td>
                        <td class="align-middle text-left">
                        <div class="form-group">
                                <div class="input-group">
                                <input type="text"  class="form-control required" autocomplete="off" id="_xi_v_c_1" placeholder="" name='xi_v_c_1' value="<?php if(!empty($calibration_info)) {echo $calibration_info->xi_v_c_1;} ?>" >
                                </div>
                            </div>
                        </td>
                        <td class="align-middle text-left">
                        <div class="form-group">
                                <div class="input-group">
                                <input type="text"  class="form-control required" autocomplete="off" id="_xi_v_c_2" placeholder="" name='xi_v_c_2' value="<?php if(!empty($calibration_info)) {echo $calibration_info->xi_v_c_2;} ?>" >
                                </div>
                            </div>
                        </td>
                        <td class="align-middle text-left">
                            <div class="form-group">
                                <div class="input-group">
                                <input type="text"  class="form-control required" autocomplete="off" id="_xi_v_c_3" placeholder="" name='xi_v_c_3' value="<?php if(!empty($calibration_info)) {echo $calibration_info->xi_v_c_3;} ?>" >
                                </div>
                            </div>
                        </td>
                        <td class="align-middle text-left">
                        <div class="form-group">
                                <div class="input-group">
                                <input type="text"  class="form-control required" autocomplete="off" id="exampleInputAmount" placeholder="" name='ii_v_c[]' value="" disabled>
                                </div>
                            </div>
                        </td>
                        <td class="align-middle text-left">
                        <div class="form-group">
                                <div class="input-group">
                                <input type="text"  class="form-control required" autocomplete="off" id="exampleInputAmount" placeholder="" name='ii_v_c[]' value="" disabled>
                                </div>
                            </div>
                        </td>
                    </tr>
                    <tr class="form-inline">
                        <td class="align-middle text-center" style="font-weight:500;"></td>
                        <td class="align-middle text-center">
                            <div class="form-group ">
                                <div class="input-group text-center">
                                <input type="text"  class="form-control required" autocomplete="off" id="exampleInputAmount" placeholder="" name='ii_v_c[]' value="30" disabled>
                                </div>
                            </div>
                        </td>
                        <td class="align-middle text-left">
                            <div class="form-group">
                                <div class="input-group">
                                <input type="text"  class="form-control required" autocomplete="off" id="exampleInputAmount" placeholder="" name='ii_v_c[]' value="5" disabled>
                                </div>
                            </div>
                        </td>
                        <td class="align-middle text-left">
                        <div class="form-group">
                                <div class="input-group">
                                <input type="text"  class="form-control required" autocomplete="off" id="exampleInputAmount" placeholder="" name='ii_v_c[]' value="2" disabled>
                                </div>
                            </div>
                        </td>
                        <td class="align-middle text-left">
                        <div class="form-group">
                                <div class="input-group">
                                <input type="text"  class="form-control required" autocomplete="off" id="_x_v_c_1" placeholder="" name='x_v_c_1' value="<?php if(!empty($calibration_info)) {echo $calibration_info->x_v_c_1;} ?>" >
                                </div>
                            </div>
                        </td>
                        <td class="align-middle text-left">
                        <div class="form-group">
                                <div class="input-group">
                                <input type="text"  class="form-control required" autocomplete="off" id="_x_v_c_2" placeholder="" name='x_v_c_2' value="<?php if(!empty($calibration_info)) {echo $calibration_info->x_v_c_2;} ?>" >
                                </div>
                            </div>
                        </td>
                        <td class="align-middle text-left">
                            <div class="form-group">
                                <div class="input-group">
                                <input type="text"  class="form-control required" autocomplete="off" id="_x_v_c_3" placeholder="" name='x_v_c_3' value="<?php if(!empty($calibration_info)) {echo $calibration_info->x_v_c_3;} ?>" >
                                </div>
                            </div>
                        </td>
                        <td class="align-middle text-left">
                        <div class="form-group">
                                <div class="input-group">
                                <input type="text"  class="form-control required" autocomplete="off" id="exampleInputAmount" placeholder="" name='ii_v_c[]' value="" disabled>
                                </div>
                            </div>
                        </td>
                        <td class="align-middle text-left">
                        <div class="form-group">
                                <div class="input-group">
                                <input type="text"  class="form-control required" autocomplete="off" id="exampleInputAmount" placeholder="" name='xi_v_c[]' value="" disabled>
                                </div>
                            </div>
                        </td>
                    </tr>
                    <tr class="form-inline">
                        <td class="align-middle text-center" style="font-weight:500;"></td>
                        <td class="align-middle text-center">
                            <div class="form-group ">
                                <div class="input-group text-center">
                                <input type="text"  class="form-control required" autocomplete="off" id="exampleInputAmount" placeholder="2" name='ii_v_cc[]' value="STOP TIME" disabled>
                                </div>
                            </div>
                        </td>
                        <td class="align-middle text-center">
                            <div class="form-group">
                                <div class="input-group">
                                    <input type="time" class="form-control required" id="stop_time_3" name="stop_time_3" autocomplete="off" value="<?php echo !empty($calibration_info->stop_time_3) ? date('H:i', strtotime($calibration_info->stop_time_1)) : ''; ?>">
                                </div>
                            </div>
                        </td>
                        <td class="align-middle text-left">
                        <div class="form-group">
                                <div class="input-group">
                                <input type="text"  class="form-control required" autocomplete="off" id="exampleInputAmount" placeholder="" name='ii_v_cc[]' value="" disabled>
                                </div>
                            </div>
                        </td>
                        <td class="align-middle text-left">
                        <div class="form-group">
                                <div class="input-group">
                                <input type="text"  class="form-control required" autocomplete="off" id="_i_v_cc_1" placeholder="" name='i_v_cc_1' value="" disabled>
                                </div>
                            </div>
                        </td>
                        <td class="align-middle text-left">
                        <div class="form-group">
                                <div class="input-group">
                                <input type="text"  class="form-control required" autocomplete="off" id="_i_v_cc_2" placeholder="" name='i_v_cc_2' value="" disabled>
                                </div>
                            </div>
                        </td>
                        <td class="align-middle text-left">
                            <div class="form-group">
                                <div class="input-group">
                                <input type="text"  class="form-control required" autocomplete="off" id="_i_v_cc_3" placeholder="" name='i_v_cc_3' value="" disabled>
                                </div>
                            </div>
                        </td>
                        <td class="align-middle text-left">
                        <div class="form-group">
                                <div class="input-group">
                                <input type="text"  class="form-control required" autocomplete="off" id="exampleInputAmount" placeholder="" name='ii_v_cc[]' value="" disabled>
                                </div>
                            </div>
                        </td>
                        <td class="align-middle text-left">
                        <div class="form-group">
                                <div class="input-group">
                                <input type="text"  class="form-control required" autocomplete="off" id="exampleInputAmount" placeholder="" name='ii_v_cc[]' value="" disabled>
                                </div>
                            </div>
                        </td>
                    </tr>
                    <!-- Add more rows as needed -->
                </tbody>               
            </table>
                <button type="button" class="btn btn-secondary prev-step">Previous</button>
                <button type="button" class="btn btn-primary next-step">ENTER POST-CALIBRATION CHECKS</button>
            </div>

             <!-- Table A2 -->
            <div class="form-section">
            <b>POST-CALIBRATION CHECKS REPORT ENTRY 1</b>
            <table class="table" id="serviceFields">
                <thead>
                    <tr>
                        <th class="text-left" style="font-weight:500;">Series</th>
                        <th class="text-left" style="font-weight:500;">Seq. No.</th>
                        <th class="text-left" style="font-weight:500;">Set</th>
                        <th class="text-left" style="font-weight:500;">Rover Point </th>
                        <th class="text-left" style="font-weight:500;">Eastings</th>
                        <th class="text-left" style="font-weight:500;">Northings</th>
                        <th class="text-left" style="font-weight:500;">Elevation</th>
                        <th class="text-left" style="font-weight:500;">Horizontal Distance (m)</th>
                        <th class="text-left" style="font-weight:500;">Elevation Accuracy (mm)</th>
                    </tr>
                </thead>
                <tbody>
                    <tr class="form-inline">
                        <td class="align-middle text-center" style="font-weight:500;"></td>
                        <td class="align-middle text-center">
                            <div class="form-group ">
                                <div class="input-group text-center">
                                <input type="text"  class="form-control required" autocomplete="off" id="exampleInputAmount" placeholder="2" name='ii_v_cc[]' value="START TIME" disabled>
                                </div>
                            </div>
                        </td>
                        
                        <td class="align-middle text-center">
                            <div class="form-group">
                                <div class="input-group">
                                    <input type="time" class="form-control required" id="start_time_4" name="start_time_4" autocomplete="off" value="<?php echo !empty($calibration_info->start_time_4) ? date('H:i', strtotime($calibration_info->start_time_4)) : ''; ?>">
                                </div>
                            </div>
                        </td>
                        <td class="align-middle text-left">
                        <div class="form-group">
                                <div class="input-group">
                                <input type="text"  class="form-control required" autocomplete="off" id="exampleInputAmount" placeholder="" name='ii_v_cc[]' value="" disabled>
                                </div>
                            </div>
                        </td>
                        <td class="align-middle text-left">
                        <div class="form-group">
                                <div class="input-group">
                                <input type="text"  class="form-control required" autocomplete="off" id="_i_v_cc_1" placeholder="" name='i_v_cc_1' value="" disabled>
                                </div>
                            </div>
                        </td>
                        <td class="align-middle text-left">
                        <div class="form-group">
                                <div class="input-group">
                                <input type="text"  class="form-control required" autocomplete="off" id="_i_v_cc_2" placeholder="" name='i_v_cc_2' value="" disabled>
                                </div>
                            </div>
                        </td>
                        <td class="align-middle text-left">
                            <div class="form-group">
                                <div class="input-group">
                                <input type="text"  class="form-control required" autocomplete="off" id="_i_v_cc_3" placeholder="" name='i_v_cc_3' value="" disabled>
                                </div>
                            </div>
                        </td>
                        <td class="align-middle text-left">
                        <div class="form-group">
                                <div class="input-group">
                                <input type="text"  class="form-control required" autocomplete="off" id="exampleInputAmount" placeholder="" name='ii_v_cc[]' value="" disabled>
                                </div>
                            </div>
                        </td>
                        <td class="align-middle text-left">
                        <div class="form-group">
                                <div class="input-group">
                                <input type="text"  class="form-control required" autocomplete="off" id="exampleInputAmount" placeholder="" name='ii_v_cc[]' value="" disabled>
                                </div>
                            </div>
                        </td>
                    </tr>
                    <tr class="form-inline">
                        <td class="align-middle text-center" style="font-weight:500;"></td>
                        <td class="align-middle text-center">
                            <div class="form-group ">
                                <div class="input-group text-center">
                                <input type="text"  class="form-control required" autocomplete="off" id="exampleInputAmount" placeholder="1" name='ii_v_aa[]' value="1" disabled>
                                </div>
                            </div>
                        </td>
                        <td class="align-middle text-left">
                            <div class="form-group">
                                <div class="input-group">
                                <input type="text"  class="form-control required" autocomplete="off" id="exampleInputAmount" placeholder="" name='ii_v_aa[]' value="1" disabled>
                                </div>
                            </div>
                        </td>
                        <td class="align-middle text-left">
                        <div class="form-group">
                                <div class="input-group">
                                <input type="text"  class="form-control required" autocomplete="off" id="exampleInputAmount" placeholder="" name='ii_v_aa[]' value="1" disabled>
                                </div>
                            </div>
                        </td>
                        <td class="align-middle text-left">
                        <div class="form-group">
                                <div class="input-group">
                                <input type="text"  class="form-control required" autocomplete="off" id="_i_v_aa_1" placeholder="" name='i_v_aa_1' value="<?php if(!empty($calibration_info)) {echo $calibration_info->i_v_aa_1;} ?>" required >
                                </div>
                            </div>
                        </td>
                        <td class="align-middle text-left">
                        <div class="form-group">
                                <div class="input-group">
                                <input type="text"  class="form-control required" autocomplete="off" id="_i_v_aa_2" placeholder="" name='i_v_aa_2' value="<?php if(!empty($calibration_info)) {echo $calibration_info->i_v_aa_2;} ?>" required>
                                </div>
                            </div>
                        </td>
                        <td class="align-middle text-left">
                            <div class="form-group">
                                <div class="input-group">
                                <input type="text"  class="form-control required" autocomplete="off" id="_i_v_aa_3" placeholder="" name='i_v_aa_3' value="<?php if(!empty($calibration_info)) {echo $calibration_info->i_v_aa_3;} ?>" required>
                                </div>
                            </div>
                        </td>
                        <td class="align-middle text-left">
                        <div class="form-group">
                                <div class="input-group">
                                <input type="text"  class="form-control required" autocomplete="off" id="exampleInputAmount" placeholder="" name='ii_v_aa[]' value="" disabled>
                                </div>
                            </div>
                        </td>
                        <td class="align-middle text-left">
                        <div class="form-group">
                                <div class="input-group">
                                <input type="text"  class="form-control required" autocomplete="off" id="exampleInputAmount" placeholder="" name='ii_v_aa[]' value="" disabled>
                                </div>
                            </div>
                        </td>
                    </tr>
                    <tr class="form-inline">
                        <td class="align-middle text-center" style="font-weight:500;"></td>
                        <td class="align-middle text-center">
                            <div class="form-group ">
                                <div class="input-group text-center">
                                <input type="text"  class="form-control required" autocomplete="off" id="exampleInputAmount" placeholder="1" name='ii_v_aa[]' value="2" disabled>
                                </div>
                            </div>
                        </td>
                        <td class="align-middle text-left">
                            <div class="form-group">
                                <div class="input-group">
                                <input type="text"  class="form-control required" autocomplete="off" id="exampleInputAmount" placeholder="" name='ii_v_aa[]' value="1" disabled>
                                </div>
                            </div>
                        </td>
                        <td class="align-middle text-left">
                        <div class="form-group">
                                <div class="input-group">
                                <input type="text"  class="form-control required" autocomplete="off" id="exampleInputAmount" placeholder="" name='ii_v_aa[]' value="2" disabled>
                                </div>
                            </div>
                        </td>
                        <td class="align-middle text-left">
                        <div class="form-group">
                                <div class="input-group">
                                <input type="text"  class="form-control required" autocomplete="off" id="_ii_v_aa_1" placeholder="" name='ii_v_aa_1' value="<?php if(!empty($calibration_info)) {echo $calibration_info->ii_v_aa_1;} ?>" >
                                </div>
                            </div>
                        </td>
                        <td class="align-middle text-left">
                        <div class="form-group">
                                <div class="input-group">
                                <input type="text"  class="form-control required" autocomplete="off" id="_ii_v_aa_2" placeholder="" name='ii_v_aa_2' value="<?php if(!empty($calibration_info)) {echo $calibration_info->ii_v_aa_2;} ?>" >
                                </div>
                            </div>
                        </td>
                        <td class="align-middle text-left">
                            <div class="form-group">
                                <div class="input-group">
                                <input type="text"  class="form-control required" autocomplete="off" id="_ii_v_aa_3" placeholder="" name='ii_v_aa_3' value="<?php if(!empty($calibration_info)) {echo $calibration_info->ii_v_aa_3;} ?>" >
                                </div>
                            </div>
                        </td>
                        <td class="align-middle text-left">
                        <div class="form-group">
                                <div class="input-group">
                                <input type="text"  class="form-control required" autocomplete="off" id="exampleInputAmount" placeholder="" name='ii_v_aa[]' value="" disabled>
                                </div>
                            </div>
                        </td>
                        <td class="align-middle text-left">
                        <div class="form-group">
                                <div class="input-group">
                                <input type="text"  class="form-control required" autocomplete="off" id="exampleInputAmount" placeholder="" name='ii_v_aa[]' value="" disabled>
                                </div>
                            </div>
                        </td>
                    </tr>
                    <tr class="form-inline">
                        <td class="align-middle text-center" style="font-weight:500;"></td>
                        <td class="align-middle text-center">
                            <div class="form-group ">
                                <div class="input-group text-center">
                                <input type="text"  class="form-control required" autocomplete="off" id="exampleInputAmount" placeholder="1" name='ii_v_aa[]' value="3" disabled>
                                </div>
                            </div>
                        </td>
                        <td class="align-middle text-left">
                            <div class="form-group">
                                <div class="input-group">
                                <input type="text"  class="form-control required" autocomplete="off" id="exampleInputAmount" placeholder="" name='ii_v_aa[]' value="2" disabled>
                                </div>
                            </div>
                        </td>
                        <td class="align-middle text-left">
                        <div class="form-group">
                                <div class="input-group">
                                <input type="text"  class="form-control required" autocomplete="off" id="exampleInputAmount" placeholder="" name='ii_v_aa[]' value="1" disabled>
                                </div>
                            </div>
                        </td>
                        <td class="align-middle text-left">
                        <div class="form-group">
                                <div class="input-group">
                                <input type="text"  class="form-control required" autocomplete="off" id="_iii_v_aa_1" placeholder="" name='iii_v_aa_1' value="<?php if(!empty($calibration_info)) {echo $calibration_info->iii_v_aa_1;} ?>" >
                                </div>
                            </div>
                        </td>
                        <td class="align-middle text-left">
                        <div class="form-group">
                                <div class="input-group">
                                <input type="text"  class="form-control required" autocomplete="off" id="_iii_v_aa_2" placeholder="" name='iii_v_aa_2' value="<?php if(!empty($calibration_info)) {echo $calibration_info->iii_v_aa_2;} ?>" >
                                </div>
                            </div>
                        </td>
                        <td class="align-middle text-left">
                            <div class="form-group">
                                <div class="input-group">
                                <input type="text"  class="form-control required" autocomplete="off" id="_iii_v_aa_3" placeholder="" name='iii_v_aa_3' value="<?php if(!empty($calibration_info)) {echo $calibration_info->iii_v_aa_3;} ?>" >
                                </div>
                            </div>
                        </td>
                        <td class="align-middle text-left">
                        <div class="form-group">
                                <div class="input-group">
                                <input type="text"  class="form-control required" autocomplete="off" id="exampleInputAmount" placeholder="" name='ii_v_aa[]' value="" disabled>
                                </div>
                            </div>
                        </td>
                        <td class="align-middle text-left">
                        <div class="form-group">
                                <div class="input-group">
                                <input type="text"  class="form-control required" autocomplete="off" id="exampleInputAmount" placeholder="" name='ii_v_aa[]' value="" disabled>
                                </div>
                            </div>
                        </td>
                    </tr>
                    <tr class="form-inline">
                        <td class="align-middle text-center" style="font-weight:500;"></td>
                        <td class="align-middle text-center">
                            <div class="form-group ">
                                <div class="input-group text-center">
                                <input type="text"  class="form-control required" autocomplete="off" id="exampleInputAmount" placeholder="" name='ii_v_aa[]' value="4" disabled>
                                </div>
                            </div>
                        </td>
                        <td class="align-middle text-left">
                            <div class="form-group">
                                <div class="input-group">
                                <input type="text"  class="form-control required" autocomplete="off" id="exampleInputAmount" placeholder="" name='ii_v_aa[]' value="2" disabled>
                                </div>
                            </div>
                        </td>
                        <td class="align-middle text-left">
                        <div class="form-group">
                                <div class="input-group">
                                <input type="text"  class="form-control required" autocomplete="off" id="exampleInputAmount" placeholder="" name='ii_v_aa[]' value="2" disabled>
                                </div>
                            </div>
                        </td>
                        <td class="align-middle text-left">
                        <div class="form-group">
                                <div class="input-group">
                                <input type="text"  class="form-control required" autocomplete="off" id="_iv_v_aa_1" placeholder="" name='iv_v_aa_1' value="<?php if(!empty($calibration_info)) {echo $calibration_info->iv_v_aa_1;} ?>" >
                                </div>
                            </div>
                        </td>
                        <td class="align-middle text-left">
                        <div class="form-group">
                                <div class="input-group">
                                <input type="text"  class="form-control required" autocomplete="off" id="_iv_v_aa_2" placeholder="" name='iv_v_aa_2' value="<?php if(!empty($calibration_info)) {echo $calibration_info->iv_v_aa_2;} ?>" >
                                </div>
                            </div>
                        </td>
                        <td class="align-middle text-left">
                            <div class="form-group">
                                <div class="input-group">
                                <input type="text"  class="form-control required" autocomplete="off" id="_iv_v_aa_3" placeholder="" name='iv_v_aa_3' value="<?php if(!empty($calibration_info)) {echo $calibration_info->iv_v_aa_3;} ?>" >
                                </div>
                            </div>
                        </td>
                        <td class="align-middle text-left">
                        <div class="form-group">
                                <div class="input-group">
                                <input type="text"  class="form-control required" autocomplete="off" id="exampleInputAmount" placeholder="" name='ii_v_aa[]' value="" disabled>
                                </div>
                            </div>
                        </td>
                        <td class="align-middle text-left">
                        <div class="form-group">
                                <div class="input-group">
                                <input type="text"  class="form-control required" autocomplete="off" id="exampleInputAmount" placeholder="" name='ii_v_aa[]' value="" disabled>
                                </div>
                            </div>
                        </td>
                    </tr>
                    <tr class="form-inline">
                        <td class="align-middle text-center" style="font-weight:500;">1</td>
                        <td class="align-middle text-center">
                            <div class="form-group ">
                                <div class="input-group text-center">
                                <input type="text"  class="form-control required" autocomplete="off" id="exampleInputAmount" placeholder="" name='ii_v_aa[]' value="5" disabled>
                                </div>
                            </div>
                        </td>
                        <td class="align-middle text-left">
                            <div class="form-group">
                                <div class="input-group">
                                <input type="text"  class="form-control required" autocomplete="off" id="exampleInputAmount" placeholder="" name='ii_v_aa[]' value="3" disabled>
                                </div>
                            </div>
                        </td>
                        <td class="align-middle text-left">
                        <div class="form-group">
                                <div class="input-group">
                                <input type="text"  class="form-control required" autocomplete="off" id="exampleInputAmount" placeholder="" name='ii_v_aa[]' value="1" disabled>
                                </div>
                            </div>
                        </td>
                        <td class="align-middle text-left">
                        <div class="form-group">
                                <div class="input-group">
                                <input type="text"  class="form-control required" autocomplete="off" id="_v_v_aa_1" placeholder="" name='v_v_aa_1' value="<?php if(!empty($calibration_info)) {echo $calibration_info->v_v_aa_1;} ?>" >
                                </div>
                            </div>
                        </td>
                        <td class="align-middle text-left">
                        <div class="form-group">
                                <div class="input-group">
                                <input type="text"  class="form-control required" autocomplete="off" id="_v_v_aa_2" placeholder="" name='v_v_aa_2' value="<?php if(!empty($calibration_info)) {echo $calibration_info->v_v_aa_2;} ?>" >
                                </div>
                            </div>
                        </td>
                        <td class="align-middle text-left">
                            <div class="form-group">
                                <div class="input-group">
                                <input type="text"  class="form-control required" autocomplete="off" id="_v_v_aa_3" placeholder="" name='v_v_aa_3' value="<?php if(!empty($calibration_info)) {echo $calibration_info->v_v_aa_3;} ?>" >
                                </div>
                            </div>
                        </td>
                        <td class="align-middle text-left">
                        <div class="form-group">
                                <div class="input-group">
                                <input type="text"  class="form-control required" autocomplete="off" id="exampleInputAmount" placeholder="" name='ii_v_aa[]' value="" disabled>
                                </div>
                            </div>
                        </td>
                        <td class="align-middle text-left">
                        <div class="form-group">
                                <div class="input-group">
                                <input type="text"  class="form-control required" autocomplete="off" id="exampleInputAmount" placeholder="" name='ii_v_aa[]' value="" disabled>
                                </div>
                            </div>
                        </td>
                    </tr>
                    <tr class="form-inline">
                        <td class="align-middle text-center" style="font-weight:500;"></td>
                        <td class="align-middle text-center">
                            <div class="form-group ">
                                <div class="input-group text-center">
                                <input type="text"  class="form-control required" autocomplete="off" id="exampleInputAmount" placeholder="" name='ii_v_aa[]' value="6" disabled>
                                </div>
                            </div>
                        </td>
                        <td class="align-middle text-left">
                            <div class="form-group">
                                <div class="input-group">
                                <input type="text"  class="form-control required" autocomplete="off" id="exampleInputAmount" placeholder="" name='ii_v_aa[]' value="3" disabled>
                                </div>
                            </div>
                        </td>
                        <td class="align-middle text-left">
                        <div class="form-group">
                                <div class="input-group">
                                <input type="text"  class="form-control required" autocomplete="off" id="exampleInputAmount" placeholder="" name='ii_v_aa[]' value="2" disabled>
                                </div>
                            </div>
                        </td>
                        <td class="align-middle text-left">
                        <div class="form-group">
                                <div class="input-group">
                                <input type="text"  class="form-control required" autocomplete="off" id="_vi_v_aa_1" placeholder="" name='vi_v_aa_1' value="<?php if(!empty($calibration_info)) {echo $calibration_info->vi_v_aa_1;} ?>" >
                                </div>
                            </div>
                        </td>
                        <td class="align-middle text-left">
                        <div class="form-group">
                                <div class="input-group">
                                <input type="text"  class="form-control required" autocomplete="off" id="_vi_v_aa_2" placeholder="" name='vi_v_aa_2' value="<?php if(!empty($calibration_info)) {echo $calibration_info->vi_v_aa_2;} ?>" >
                                </div>
                            </div>
                        </td>
                        <td class="align-middle text-left">
                            <div class="form-group">
                                <div class="input-group">
                                <input type="text"  class="form-control required" autocomplete="off" id="_vi_v_aa_3" placeholder="" name='vi_v_aa_3' value="<?php if(!empty($calibration_info)) {echo $calibration_info->vi_v_aa_3;} ?>" >
                                </div>
                            </div>
                        </td>
                        <td class="align-middle text-left">
                        <div class="form-group">
                                <div class="input-group">
                                <input type="text"  class="form-control required" autocomplete="off" id="exampleInputAmount" placeholder="" name='ii_v_aa[]' value="" disabled>
                                </div>
                            </div>
                        </td>
                        <td class="align-middle text-left">
                        <div class="form-group">
                                <div class="input-group">
                                <input type="text"  class="form-control required" autocomplete="off" id="exampleInputAmount" placeholder="" name='ii_v_aa[]' value="" disabled>
                                </div>
                            </div>
                        </td>
                    </tr>
                    <tr class="form-inline">
                        <td class="align-middle text-center" style="font-weight:500;"></td>
                        <td class="align-middle text-center">
                            <div class="form-group ">
                                <div class="input-group text-center">
                                <input type="text"  class="form-control required" autocomplete="off" id="exampleInputAmount" placeholder="" name='ii_v_aa[]' value="7" disabled>
                                </div>
                            </div>
                        </td>
                        <td class="align-middle text-left">
                            <div class="form-group">
                                <div class="input-group">
                                <input type="text"  class="form-control required" autocomplete="off" id="exampleInputAmount" placeholder="" name='ii_v_aa[]' value="4" disabled>
                                </div>
                            </div>
                        </td>
                        <td class="align-middle text-left">
                        <div class="form-group">
                                <div class="input-group">
                                <input type="text"  class="form-control required" autocomplete="off" id="exampleInputAmount" placeholder="" name='ii_v_aa[]' value="1" disabled>
                                </div>
                            </div>
                        </td>
                        <td class="align-middle text-left">
                        <div class="form-group">
                                <div class="input-group">
                                <input type="text"  class="form-control required" autocomplete="off" id="_vii_v_aa_1" placeholder="" name='vii_v_aa_1' value="<?php if(!empty($calibration_info)) {echo $calibration_info->vii_v_aa_1;} ?>" >
                                </div>
                            </div>
                        </td>
                        <td class="align-middle text-left">
                        <div class="form-group">
                                <div class="input-group">
                                <input type="text"  class="form-control required" autocomplete="off" id="_vii_v_aa_2" placeholder="" name='vii_v_aa_2' value="<?php if(!empty($calibration_info)) {echo $calibration_info->vii_v_aa_2;} ?>" >
                                </div>
                            </div>
                        </td>
                        <td class="align-middle text-left">
                            <div class="form-group">
                                <div class="input-group">
                                <input type="text"  class="form-control required" autocomplete="off" id="_vii_v_aa_3" placeholder="" name='vii_v_aa_3' value="<?php if(!empty($calibration_info)) {echo $calibration_info->vii_v_aa_3;} ?>" >
                                </div>
                            </div>
                        </td>
                        <td class="align-middle text-left">
                        <div class="form-group">
                                <div class="input-group">
                                <input type="text"  class="form-control required" autocomplete="off" id="exampleInputAmount" placeholder="" name='ii_v_aa[]' value="" disabled>
                                </div>
                            </div>
                        </td>
                        <td class="align-middle text-left">
                        <div class="form-group">
                                <div class="input-group">
                                <input type="text"  class="form-control required" autocomplete="off" id="exampleInputAmount" placeholder="" name='ii_v_aa[]' value="" disabled>
                                </div>
                            </div>
                        </td>
                    </tr>
                    <tr class="form-inline">
                        <td class="align-middle text-center" style="font-weight:500;"></td>
                        <td class="align-middle text-center">
                            <div class="form-group ">
                                <div class="input-group text-center">
                                <input type="text"  class="form-control required" autocomplete="off" id="exampleInputAmount" placeholder="" name='ii_v_aa[]' value="8" disabled>
                                </div>
                            </div>
                        </td>
                        <td class="align-middle text-left">
                            <div class="form-group">
                                <div class="input-group">
                                <input type="text"  class="form-control required" autocomplete="off" id="exampleInputAmount" placeholder="" name='ii_v_aa[]' value="4" disabled>
                                </div>
                            </div>
                        </td>
                        <td class="align-middle text-left">
                        <div class="form-group">
                                <div class="input-group">
                                <input type="text"  class="form-control required" autocomplete="off" id="exampleInputAmount" placeholder="" name='ii_v_aa[]' value="2" disabled>
                                </div>
                            </div>
                        </td>
                        <td class="align-middle text-left">
                        <div class="form-group">
                                <div class="input-group">
                                <input type="text"  class="form-control required" autocomplete="off" id="_viii_v_aa_1" placeholder="" name='viii_v_aa_1' value="<?php if(!empty($calibration_info)) {echo $calibration_info->viii_v_aa_1;} ?>" >
                                </div>
                            </div>
                        </td>
                        <td class="align-middle text-left">
                        <div class="form-group">
                                <div class="input-group">
                                <input type="text"  class="form-control required" autocomplete="off" id="_viii_v_aa_2" placeholder="" name='viii_v_aa_2' value="<?php if(!empty($calibration_info)) {echo $calibration_info->viii_v_aa_2;} ?>" >
                                </div>
                            </div>
                        </td>
                        <td class="align-middle text-left">
                            <div class="form-group">
                                <div class="input-group">
                                <input type="text"  class="form-control required" autocomplete="off" id="viii_v_aa_3" placeholder="" name='viii_v_aa_3' value="<?php if(!empty($calibration_info)) {echo $calibration_info->viii_v_aa_3;} ?>" >
                                </div>
                            </div>
                        </td>
                        <td class="align-middle text-left">
                        <div class="form-group">
                                <div class="input-group">
                                <input type="text"  class="form-control required" autocomplete="off" id="exampleInputAmount" placeholder="" name='ii_v_aa[]' value="" disabled>
                                </div>
                            </div>
                        </td>
                        <td class="align-middle text-left">
                        <div class="form-group">
                                <div class="input-group">
                                <input type="text"  class="form-control required" autocomplete="off" id="exampleInputAmount" placeholder="" name='ii_v_aa[]' value="" disabled>
                                </div>
                            </div>
                        </td>
                    </tr>
                    <tr class="form-inline">
                        <td class="align-middle text-center" style="font-weight:500;"></td>
                        <td class="align-middle text-center">
                            <div class="form-group ">
                                <div class="input-group text-center">
                                <input type="text"  class="form-control required" autocomplete="off" id="exampleInputAmount" placeholder="" name='ii_v_aa[]' value="9" disabled>
                                </div>
                            </div>
                        </td>
                        <td class="align-middle text-left">
                            <div class="form-group">
                                <div class="input-group">
                                <input type="text"  class="form-control required" autocomplete="off" id="exampleInputAmount" placeholder="" name='ii_v_aa[]' value="5" disabled>
                                </div>
                            </div>
                        </td>
                        <td class="align-middle text-left">
                        <div class="form-group">
                                <div class="input-group">
                                <input type="text"  class="form-control required" autocomplete="off" id="exampleInputAmount" placeholder="" name='ii_v_aa[]' value="1" disabled>
                                </div>
                            </div>
                        </td>
                        <td class="align-middle text-left">
                        <div class="form-group">
                                <div class="input-group">
                                <input type="text"  class="form-control required" autocomplete="off" id="_xi_v_aa_1" placeholder="" name='xi_v_aa_1' value="<?php if(!empty($calibration_info)) {echo $calibration_info->xi_v_aa_1;} ?>" >
                                </div>
                            </div>
                        </td>
                        <td class="align-middle text-left">
                        <div class="form-group">
                                <div class="input-group">
                                <input type="text"  class="form-control required" autocomplete="off" id="_xi_v_aa_2" placeholder="" name='xi_v_aa_2' value="<?php if(!empty($calibration_info)) {echo $calibration_info->xi_v_aa_2;} ?>" >
                                </div>
                            </div>
                        </td>
                        <td class="align-middle text-left">
                            <div class="form-group">
                                <div class="input-group">
                                <input type="text"  class="form-control required" autocomplete="off" id="_xi_v_aa_3" placeholder="" name='xi_v_aa_3' value="<?php if(!empty($calibration_info)) {echo $calibration_info->xi_v_aa_3;} ?>" >
                                </div>
                            </div>
                        </td>
                        <td class="align-middle text-left">
                        <div class="form-group">
                                <div class="input-group">
                                <input type="text"  class="form-control required" autocomplete="off" id="exampleInputAmount" placeholder="" name='ii_v_aa[]' value="" disabled>
                                </div>
                            </div>
                        </td>
                        <td class="align-middle text-left">
                        <div class="form-group">
                                <div class="input-group">
                                <input type="text"  class="form-control required" autocomplete="off" id="exampleInputAmount" placeholder="" name='ii_v_aa[]' value="" disabled>
                                </div>
                            </div>
                        </td>
                    </tr>
                    <tr class="form-inline">
                        <td class="align-middle text-center" style="font-weight:500;"></td>
                        <td class="align-middle text-center">
                            <div class="form-group ">
                                <div class="input-group text-center">
                                <input type="text"  class="form-control required" autocomplete="off" id="exampleInputAmount" placeholder="" name='ii_v_aa[]' value="10" disabled>
                                </div>
                            </div>
                        </td>
                        <td class="align-middle text-left">
                            <div class="form-group">
                                <div class="input-group">
                                <input type="text"  class="form-control required" autocomplete="off" id="exampleInputAmount" placeholder="" name='ii_v_aa[]' value="5" disabled>
                                </div>
                            </div>
                        </td>
                        <td class="align-middle text-left">
                        <div class="form-group">
                                <div class="input-group">
                                <input type="text"  class="form-control required" autocomplete="off" id="exampleInputAmount" placeholder="" name='ii_v_aa[]' value="2" disabled>
                                </div>
                            </div>
                        </td>
                        <td class="align-middle text-left">
                        <div class="form-group">
                                <div class="input-group">
                                <input type="text"  class="form-control required" autocomplete="off" id="_x_v_aa_1" placeholder="" name='x_v_aa_1' value="<?php if(!empty($calibration_info)) {echo $calibration_info->x_v_aa_1;} ?>" >
                                </div>
                            </div>
                        </td>
                        <td class="align-middle text-left">
                        <div class="form-group">
                                <div class="input-group">
                                <input type="text"  class="form-control required" autocomplete="off" id="_x_v_aa_2" placeholder="" name='x_v_aa_2' value="<?php if(!empty($calibration_info)) {echo $calibration_info->x_v_aa_2;} ?>" >
                                </div>
                            </div>
                        </td>
                        <td class="align-middle text-left">
                            <div class="form-group">
                                <div class="input-group">
                                <input type="text"  class="form-control required" autocomplete="off" id="_x_v_aa_3" placeholder="" name='x_v_aa_3' value="<?php if(!empty($calibration_info)) {echo $calibration_info->x_v_aa_3;} ?>" >
                                </div>
                            </div>
                        </td>
                        <td class="align-middle text-left">
                        <div class="form-group">
                                <div class="input-group">
                                <input type="text"  class="form-control required" autocomplete="off" id="exampleInputAmount" placeholder="" name='ii_v_aa[]' value="" disabled>
                                </div>
                            </div>
                        </td>
                        <td class="align-middle text-left">
                        <div class="form-group">
                                <div class="input-group">
                                <input type="text"  class="form-control required" autocomplete="off" id="exampleInputAmount" placeholder="" name='ii_v_aa[]' value="" disabled>
                                </div>
                            </div>
                        </td>
                    </tr>
                    <tr class="form-inline">
                        <td class="align-middle text-center" style="font-weight:500;"></td>
                        <td class="align-middle text-center">
                            <div class="form-group ">
                                <div class="input-group text-center">
                                <input type="text"  class="form-control required" autocomplete="off" id="exampleInputAmount" placeholder="2" name='ii_v_cc[]' value="STOP TIME" disabled>
                                </div>
                            </div>
                        </td>
                      
                        <td class="align-middle text-center">
                            <div class="form-group">
                                <div class="input-group">
                                    <input type="time" class="form-control required" id="stop_time_4" name="stop_time_4" autocomplete="off" value="<?php echo !empty($calibration_info->stop_time_4) ? date('H:i', strtotime($calibration_info->stop_time_4)) : ''; ?>">
                                </div>
                            </div>
                        </td>
                        <td class="align-middle text-left">
                        <div class="form-group">
                                <div class="input-group">
                                <input type="text"  class="form-control required" autocomplete="off" id="exampleInputAmount" placeholder="" name='ii_v_cc[]' value="" disabled>
                                </div>
                            </div>
                        </td>
                        <td class="align-middle text-left">
                        <div class="form-group">
                                <div class="input-group">
                                <input type="text"  class="form-control required" autocomplete="off" id="_i_v_cc_1" placeholder="" name='i_v_cc_1' value="" disabled>
                                </div>
                            </div>
                        </td>
                        <td class="align-middle text-left">
                        <div class="form-group">
                                <div class="input-group">
                                <input type="text"  class="form-control required" autocomplete="off" id="_i_v_cc_2" placeholder="" name='i_v_cc_2' value="" disabled>
                                </div>
                            </div>
                        </td>
                        <td class="align-middle text-left">
                            <div class="form-group">
                                <div class="input-group">
                                <input type="text"  class="form-control required" autocomplete="off" id="_i_v_cc_3" placeholder="" name='i_v_cc_3' value="" disabled>
                                </div>
                            </div>
                        </td>
                        <td class="align-middle text-left">
                        <div class="form-group">
                                <div class="input-group">
                                <input type="text"  class="form-control required" autocomplete="off" id="exampleInputAmount" placeholder="" name='ii_v_cc[]' value="" disabled>
                                </div>
                            </div>
                        </td>
                        <td class="align-middle text-left">
                        <div class="form-group">
                                <div class="input-group">
                                <input type="text"  class="form-control required" autocomplete="off" id="exampleInputAmount" placeholder="" name='ii_v_cc[]' value="" disabled>
                                </div>
                            </div>
                        </td>
                    </tr>
                    <!-- Add more rows as needed -->
                </tbody>               
            </table>
            <button type="button" class="btn btn-secondary prev-step">Previous</button>
            <button type="button" class="btn btn-primary next-step">Next</button>
            </div>
            
            
            <!-- Table A3 -->
            <div class="form-section">
            <b>POST-CALIBRATION CHECKS REPORT ENTRY 2</b>   
            <table class="table" id="serviceFields">
                <thead>
                    <tr>
                        <th class="text-left" style="font-weight:500;">Series</th>
                        <th class="text-left" style="font-weight:500;">Seq. No.</th>
                        <th class="text-left" style="font-weight:500;">Set</th>
                        <th class="text-left" style="font-weight:500;">Rover Point </th>
                        <th class="text-left" style="font-weight:500;">Eastings</th>
                        <th class="text-left" style="font-weight:500;">Northings</th>
                        <th class="text-left" style="font-weight:500;">Elevation</th>
                        <th class="text-left" style="font-weight:500;">Horizontal Distance (m)</th>
                        <th class="text-left" style="font-weight:500;">Elevation Accuracy (mm)</th>
                    </tr>
                </thead>
                <tbody>
                    <tr class="form-inline">
                        <td class="align-middle text-center" style="font-weight:500;"></td>
                        <td class="align-middle text-center">
                            <div class="form-group ">
                                <div class="input-group text-center">
                                <input type="text"  class="form-control required" autocomplete="off" id="exampleInputAmount" placeholder="2" name='ii_v_cc[]' value="START TIME" disabled>
                                </div>
                            </div>
                        </td>
                        
                        <td class="align-middle text-center">
                            <div class="form-group">
                                <div class="input-group">
                                    <input type="time" class="form-control required" id="start_time_5" name="start_time_5" autocomplete="off" value="<?php echo !empty($calibration_info->start_time_5) ? date('H:i', strtotime($calibration_info->start_time_5)) : ''; ?>">
                                </div>
                            </div>
                        </td>
                        <td class="align-middle text-left">
                        <div class="form-group">
                                <div class="input-group">
                                <input type="text"  class="form-control required" autocomplete="off" id="exampleInputAmount" placeholder="" name='ii_v_cc[]' value="" disabled>
                                </div>
                            </div>
                        </td>
                        <td class="align-middle text-left">
                        <div class="form-group">
                                <div class="input-group">
                                <input type="text"  class="form-control required" autocomplete="off" id="_i_v_cc_1" placeholder="" name='i_v_cc_1' value="" disabled>
                                </div>
                            </div>
                        </td>
                        <td class="align-middle text-left">
                        <div class="form-group">
                                <div class="input-group">
                                <input type="text"  class="form-control required" autocomplete="off" id="_i_v_cc_2" placeholder="" name='i_v_cc_2' value="" disabled>
                                </div>
                            </div>
                        </td>
                        <td class="align-middle text-left">
                            <div class="form-group">
                                <div class="input-group">
                                <input type="text"  class="form-control required" autocomplete="off" id="_i_v_cc_3" placeholder="" name='i_v_cc_3' value="" disabled>
                                </div>
                            </div>
                        </td>
                        <td class="align-middle text-left">
                        <div class="form-group">
                                <div class="input-group">
                                <input type="text"  class="form-control required" autocomplete="off" id="exampleInputAmount" placeholder="" name='ii_v_cc[]' value="" disabled>
                                </div>
                            </div>
                        </td>
                        <td class="align-middle text-left">
                        <div class="form-group">
                                <div class="input-group">
                                <input type="text"  class="form-control required" autocomplete="off" id="exampleInputAmount" placeholder="" name='ii_v_cc[]' value="" disabled>
                                </div>
                            </div>
                        </td>
                    </tr>
                    <tr class="form-inline">
                        <td class="align-middle text-center" style="font-weight:500;"></td>
                        <td class="align-middle text-center">
                            <div class="form-group ">
                                <div class="input-group text-center">
                                <input type="text"  class="form-control required" autocomplete="off" id="exampleInputAmount" placeholder="2" name='ii_v_bb[]' value="11" disabled>
                                </div>
                            </div>
                        </td>
                        <td class="align-middle text-left">
                            <div class="form-group">
                                <div class="input-group">
                                <input type="text"  class="form-control required" autocomplete="off" id="exampleInputAmount" placeholder="" name='ii_v_bb[]' value="1" disabled>
                                </div>
                            </div>
                        </td>
                        <td class="align-middle text-left">
                        <div class="form-group">
                                <div class="input-group">
                                <input type="text"  class="form-control required" autocomplete="off" id="exampleInputAmount" placeholder="" name='ii_v_bb[]' value="1" disabled>
                                </div>
                            </div>
                        </td>
                        <td class="align-middle text-left">
                        <div class="form-group">
                                <div class="input-group">
                                <input type="text"  class="form-control required" autocomplete="off" id="_i_v_bb_1" placeholder="" name='i_v_bb_1' value="<?php echo isset($calibration_info->i_v_bb_1) ? $calibration_info->i_v_b_1 : ''; ?>" required>
                                </div>
                            </div>
                        </td>
                        <td class="align-middle text-left">
                        <div class="form-group">
                                <div class="input-group">
                                <input type="text"  class="form-control required" autocomplete="off" id="_i_v_bb_2" placeholder="" name='i_v_bb_2' value="<?php if(!empty($calibration_info)) {echo $calibration_info->i_v_bb_2;} ?>" required>
                                </div>
                            </div>
                        </td>
                        <td class="align-middle text-left">
                            <div class="form-group">
                                <div class="input-group">
                                <input type="text"  class="form-control required" autocomplete="off" id="_i_v_bb_3" placeholder="" name='i_v_bb_3' value="<?php if(!empty($calibration_info)) {echo $calibration_info->i_v_bb_3;} ?>" required>
                                </div>
                            </div>
                        </td>
                        <td class="align-middle text-left">
                        <div class="form-group">
                                <div class="input-group">
                                <input type="text"  class="form-control required" autocomplete="off" id="exampleInputAmount" placeholder="" name='ii_v_bb[]' value="" disabled>
                                </div>
                            </div>
                        </td>
                        <td class="align-middle text-left">
                        <div class="form-group">
                                <div class="input-group">
                                <input type="text"  class="form-control required" autocomplete="off" id="exampleInputAmount" placeholder="" name='ii_v_bb[]' value="" disabled>
                                </div>
                            </div>
                        </td>
                    </tr>
                    <tr class="form-inline">
                        <td class="align-middle text-center" style="font-weight:500;"></td>
                        <td class="align-middle text-center">
                            <div class="form-group ">
                                <div class="input-group text-center">
                                <input type="text"  class="form-control required" autocomplete="off" id="exampleInputAmount" placeholder="1" name='ii_v_bb[]' value="12" disabled>
                                </div>
                            </div>
                        </td>
                        <td class="align-middle text-left">
                            <div class="form-group">
                                <div class="input-group">
                                <input type="text"  class="form-control required" autocomplete="off" id="exampleInputAmount" placeholder="" name='ii_v_bb[]' value="1" disabled>
                                </div>
                            </div>
                        </td>
                        <td class="align-middle text-left">
                        <div class="form-group">
                                <div class="input-group">
                                <input type="text"  class="form-control required" autocomplete="off" id="exampleInputAmount" placeholder="" name='ii_v_bb[]' value="2" disabled>
                                </div>
                            </div>
                        </td>
                        <td class="align-middle text-left">
                        <div class="form-group">
                                <div class="input-group">
                                <input type="text"  class="form-control required" autocomplete="off" id="_ii_v_bb_1" placeholder="" name='ii_v_bb_1' value="<?php if(!empty($calibration_info)) {echo $calibration_info->ii_v_bb_1;} ?>" >
                                </div>
                            </div>
                        </td>
                        <td class="align-middle text-left">
                        <div class="form-group">
                                <div class="input-group">
                                <input type="text"  class="form-control required" autocomplete="off" id="_ii_v_bb_2" placeholder="" name='ii_v_bb_2' value="<?php if(!empty($calibration_info)) {echo $calibration_info->ii_v_bb_2;} ?>" >
                                </div>
                            </div>
                        </td>
                        <td class="align-middle text-left">
                            <div class="form-group">
                                <div class="input-group">
                                <input type="text"  class="form-control required" autocomplete="off" id="_ii_v_bb_3" placeholder="" name='ii_v_bb_3' value="<?php if(!empty($calibration_info)) {echo $calibration_info->ii_v_bb_3;} ?>" >
                                </div>
                            </div>
                        </td>
                        <td class="align-middle text-left">
                        <div class="form-group">
                                <div class="input-group">
                                <input type="text"  class="form-control required" autocomplete="off" id="exampleInputAmount" placeholder="" name='ii_v_bb[]' value="" disabled>
                                </div>
                            </div>
                        </td>
                        <td class="align-middle text-left">
                        <div class="form-group">
                                <div class="input-group">
                                <input type="text"  class="form-control required" autocomplete="off" id="exampleInputAmount" placeholder="" name='ii_v_bb[]' value="" disabled>
                                </div>
                            </div>
                        </td>
                    </tr>
                    <tr class="form-inline">
                        <td class="align-middle text-center" style="font-weight:500;"></td>
                        <td class="align-middle text-center">
                            <div class="form-group ">
                                <div class="input-group text-center">
                                <input type="text"  class="form-control required" autocomplete="off" id="exampleInputAmount" placeholder="1" name='ii_v_bb[]' value="13" disabled>
                                </div>
                            </div>
                        </td>
                        <td class="align-middle text-left">
                            <div class="form-group">
                                <div class="input-group">
                                <input type="text"  class="form-control required" autocomplete="off" id="exampleInputAmount" placeholder="" name='ii_v_bb[]' value="2" disabled>
                                </div>
                            </div>
                        </td>
                        <td class="align-middle text-left">
                        <div class="form-group">
                                <div class="input-group">
                                <input type="text"  class="form-control required" autocomplete="off" id="exampleInputAmount" placeholder="" name='ii_v_bb[]' value="1" disabled>
                                </div>
                            </div>
                        </td>
                        <td class="align-middle text-left">
                        <div class="form-group">
                                <div class="input-group">
                                <input type="text"  class="form-control required" autocomplete="off" id="_iii_v_bb_1" placeholder="" name='iii_v_bb_1' value="<?php if(!empty($calibration_info)) {echo $calibration_info->iii_v_bb_1;} ?>" >
                                </div>
                            </div>
                        </td>
                        <td class="align-middle text-left">
                        <div class="form-group">
                                <div class="input-group">
                                <input type="text"  class="form-control required" autocomplete="off" id="_iii_v_bb_2" placeholder="" name='iii_v_bb_2' value="<?php if(!empty($calibration_info)) {echo $calibration_info->iii_v_bb_2;} ?>" >
                                </div>
                            </div>
                        </td>
                        <td class="align-middle text-left">
                            <div class="form-group">
                                <div class="input-group">
                                <input type="text"  class="form-control required" autocomplete="off" id="_iii_v_bb_3" placeholder="" name='iii_v_bb_3' value="<?php if(!empty($calibration_info)) {echo $calibration_info->iii_v_bb_3;} ?>" >
                                </div>
                            </div>
                        </td>
                        <td class="align-middle text-left">
                        <div class="form-group">
                                <div class="input-group">
                                <input type="text"  class="form-control required" autocomplete="off" id="exampleInputAmount" placeholder="" name='ii_v_bb[]' value="" disabled>
                                </div>
                            </div>
                        </td>
                        <td class="align-middle text-left">
                        <div class="form-group">
                                <div class="input-group">
                                <input type="text"  class="form-control required" autocomplete="off" id="exampleInputAmount" placeholder="" name='ii_v_bb[]' value="" disabled>
                                </div>
                            </div>
                        </td>
                    </tr>
                    <tr class="form-inline">
                        <td class="align-middle text-center" style="font-weight:500;"></td>
                        <td class="align-middle text-center">
                            <div class="form-group ">
                                <div class="input-group text-center">
                                <input type="text"  class="form-control required" autocomplete="off" id="exampleInputAmount" placeholder="" name='ii_v_bb[]' value="14" disabled>
                                </div>
                            </div>
                        </td>
                        <td class="align-middle text-left">
                            <div class="form-group">
                                <div class="input-group">
                                <input type="text"  class="form-control required" autocomplete="off" id="exampleInputAmount" placeholder="" name='ii_v_bb[]' value="2" disabled>
                                </div>
                            </div>
                        </td>
                        <td class="align-middle text-left">
                        <div class="form-group">
                                <div class="input-group">
                                <input type="text"  class="form-control required" autocomplete="off" id="exampleInputAmount" placeholder="" name='ii_v_bb[]' value="2" disabled>
                                </div>
                            </div>
                        </td>
                        <td class="align-middle text-left">
                        <div class="form-group">
                                <div class="input-group">
                                <input type="text"  class="form-control required" autocomplete="off" id="_iv_v_bb_1" placeholder="" name='iv_v_bb_1' value="<?php if(!empty($calibration_info)) {echo $calibration_info->iv_v_bb_1;} ?>" >
                                </div>
                            </div>
                        </td>
                        <td class="align-middle text-left">
                        <div class="form-group">
                                <div class="input-group">
                                <input type="text"  class="form-control required" autocomplete="off" id="_iv_v_bb_2" placeholder="" name='iv_v_bb_2' value="<?php if(!empty($calibration_info)) {echo $calibration_info->iv_v_bb_2;} ?>" >
                                </div>
                            </div>
                        </td>
                        <td class="align-middle text-left">
                            <div class="form-group">
                                <div class="input-group">
                                <input type="text"  class="form-control required" autocomplete="off" id="_iv_v_bb_3" placeholder="" name='iv_v_bb_3' value="<?php if(!empty($calibration_info)) {echo $calibration_info->iv_v_bb_3;} ?>" >
                                </div>
                            </div>
                        </td>
                        <td class="align-middle text-left">
                        <div class="form-group">
                                <div class="input-group">
                                <input type="text"  class="form-control required" autocomplete="off" id="exampleInputAmount" placeholder="" name='ii_v_bb[]' value="" disabled>
                                </div>
                            </div>
                        </td>
                        <td class="align-middle text-left">
                        <div class="form-group">
                                <div class="input-group">
                                <input type="text"  class="form-control required" autocomplete="off" id="exampleInputAmount" placeholder="" name='ii_v_bb[]' value="" disabled>
                                </div>
                            </div>
                        </td>
                    </tr>
                    <tr class="form-inline">
                        <td class="align-middle text-center" style="font-weight:500;">2</td>
                        <td class="align-middle text-center">
                            <div class="form-group ">
                                <div class="input-group text-center">
                                <input type="text"  class="form-control required" autocomplete="off" id="exampleInputAmount" placeholder="" name='ii_v_bb[]' value="15" disabled>
                                </div>
                            </div>
                        </td>
                        <td class="align-middle text-left">
                            <div class="form-group">
                                <div class="input-group">
                                <input type="text"  class="form-control required" autocomplete="off" id="exampleInputAmount" placeholder="" name='ii_v_bb[]' value="3" disabled>
                                </div>
                            </div>
                        </td>
                        <td class="align-middle text-left">
                        <div class="form-group">
                                <div class="input-group">
                                <input type="text"  class="form-control required" autocomplete="off" id="exampleInputAmount" placeholder="" name='ii_v_bb[]' value="1" disabled>
                                </div>
                            </div>
                        </td>
                        <td class="align-middle text-left">
                        <div class="form-group">
                                <div class="input-group">
                                <input type="text"  class="form-control required" autocomplete="off" id="_v_v_bb_1" placeholder="" name='v_v_bb_1' value="<?php if(!empty($calibration_info)) {echo $calibration_info->v_v_bb_1;} ?>" >
                                </div>
                            </div>
                        </td>
                        <td class="align-middle text-left">
                        <div class="form-group">
                                <div class="input-group">
                                <input type="text"  class="form-control required" autocomplete="off" id="_v_v_bb_2" placeholder="" name='v_v_bb_2' value="<?php if(!empty($calibration_info)) {echo $calibration_info->v_v_bb_2;} ?>" >
                                </div>
                            </div>
                        </td>
                        <td class="align-middle text-left">
                            <div class="form-group">
                                <div class="input-group">
                                <input type="text"  class="form-control required" autocomplete="off" id="_v_v_bb_3" placeholder="" name='v_v_bb_3' value="<?php if(!empty($calibration_info)) {echo $calibration_info->v_v_bb_3;} ?>" >
                                </div>
                            </div>
                        </td>
                        <td class="align-middle text-left">
                        <div class="form-group">
                                <div class="input-group">
                                <input type="text"  class="form-control required" autocomplete="off" id="exampleInputAmount" placeholder="" name='ii_v_bb[]' value="" disabled>
                                </div>
                            </div>
                        </td>
                        <td class="align-middle text-left">
                        <div class="form-group">
                                <div class="input-group">
                                <input type="text"  class="form-control required" autocomplete="off" id="exampleInputAmount" placeholder="" name='ii_v_bb[]' value="" disabled>
                                </div>
                            </div>
                        </td>
                    </tr>
                    <tr class="form-inline">
                        <td class="align-middle text-center" style="font-weight:500;"></td>
                        <td class="align-middle text-center">
                            <div class="form-group ">
                                <div class="input-group text-center">
                                <input type="text"  class="form-control required" autocomplete="off" id="exampleInputAmount" placeholder="" name='ii_v_bb[]' value="16" disabled>
                                </div>
                            </div>
                        </td>
                        <td class="align-middle text-left">
                            <div class="form-group">
                                <div class="input-group">
                                <input type="text"  class="form-control required" autocomplete="off" id="exampleInputAmount" placeholder="" name='ii_v_bb[]' value="3" disabled>
                                </div>
                            </div>
                        </td>
                        <td class="align-middle text-left">
                        <div class="form-group">
                                <div class="input-group">
                                <input type="text"  class="form-control required" autocomplete="off" id="exampleInputAmount" placeholder="" name='ii_v_bb[]' value="2" disabled>
                                </div>
                            </div>
                        </td>
                        <td class="align-middle text-left">
                        <div class="form-group">
                                <div class="input-group">
                                <input type="text"  class="form-control required" autocomplete="off" id="_vi_v_bb_1" placeholder="" name='vi_v_bb_1' value="<?php if(!empty($calibration_info)) {echo $calibration_info->vi_v_bb_1;} ?>" >
                                </div>
                            </div>
                        </td>
                        <td class="align-middle text-left">
                        <div class="form-group">
                                <div class="input-group">
                                <input type="text"  class="form-control required" autocomplete="off" id="_vi_v_bb_2" placeholder="" name='vi_v_bb_2' value="<?php if(!empty($calibration_info)) {echo $calibration_info->vi_v_bb_2;} ?>" >
                                </div>
                            </div>
                        </td>
                        <td class="align-middle text-left">
                            <div class="form-group">
                                <div class="input-group">
                                <input type="text"  class="form-control required" autocomplete="off" id="_vi_v_bb_3" placeholder="" name='vi_v_bb_3' value="<?php if(!empty($calibration_info)) {echo $calibration_info->vi_v_bb_3;} ?>" >
                                </div>
                            </div>
                        </td>
                        <td class="align-middle text-left">
                        <div class="form-group">
                                <div class="input-group">
                                <input type="text"  class="form-control required" autocomplete="off" id="exampleInputAmount" placeholder="" name='ii_v_bb[]' value="" disabled>
                                </div>
                            </div>
                        </td>
                        <td class="align-middle text-left">
                        <div class="form-group">
                                <div class="input-group">
                                <input type="text"  class="form-control required" autocomplete="off" id="exampleInputAmount" placeholder="" name='ii_v_bb[]' value="" disabled>
                                </div>
                            </div>
                        </td>
                    </tr>
                    <tr class="form-inline">
                        <td class="align-middle text-center" style="font-weight:500;"></td>
                        <td class="align-middle text-center">
                            <div class="form-group ">
                                <div class="input-group text-center">
                                <input type="text"  class="form-control required" autocomplete="off" id="exampleInputAmount" placeholder="" name='ii_v_bb[]' value="17" disabled>
                                </div>
                            </div>
                        </td>
                        <td class="align-middle text-left">
                            <div class="form-group">
                                <div class="input-group">
                                <input type="text"  class="form-control required" autocomplete="off" id="exampleInputAmount" placeholder="" name='ii_v_bb[]' value="4" disabled>
                                </div>
                            </div>
                        </td>
                        <td class="align-middle text-left">
                        <div class="form-group">
                                <div class="input-group">
                                <input type="text"  class="form-control required" autocomplete="off" id="exampleInputAmount" placeholder="" name='ii_v_bb[]' value="1" disabled>
                                </div>
                            </div>
                        </td>
                        <td class="align-middle text-left">
                        <div class="form-group">
                                <div class="input-group">
                                <input type="text"  class="form-control required" autocomplete="off" id="_vii_v_bb_1" placeholder="" name='vii_v_bb_1' value="<?php if(!empty($calibration_info)) {echo $calibration_info->vii_v_bb_1;} ?>" >
                                </div>
                            </div>
                        </td>
                        <td class="align-middle text-left">
                        <div class="form-group">
                                <div class="input-group">
                                <input type="text"  class="form-control required" autocomplete="off" id="_vii_v_bb_2" placeholder="" name='vii_v_bb_2' value="<?php if(!empty($calibration_info)) {echo $calibration_info->vii_v_bb_2;} ?>" >
                                </div>
                            </div>
                        </td>
                        <td class="align-middle text-left">
                            <div class="form-group">
                                <div class="input-group">
                                <input type="text"  class="form-control required" autocomplete="off" id="_vii_v_bb_3" placeholder="" name='vii_v_bb_3' value="<?php if(!empty($calibration_info)) {echo $calibration_info->vii_v_bb_3;} ?>" >
                                </div>
                            </div>
                        </td>
                        <td class="align-middle text-left">
                        <div class="form-group">
                                <div class="input-group">
                                <input type="text"  class="form-control required" autocomplete="off" id="exampleInputAmount" placeholder="" name='ii_v_bb[]' value="" disabled>
                                </div>
                            </div>
                        </td>
                        <td class="align-middle text-left">
                        <div class="form-group">
                                <div class="input-group">
                                <input type="text"  class="form-control required" autocomplete="off" id="exampleInputAmount" placeholder="" name='ii_v_bb[]' value="" disabled>
                                </div>
                            </div>
                        </td>
                    </tr>
                    <tr class="form-inline">
                        <td class="align-middle text-center" style="font-weight:500;"></td>
                        <td class="align-middle text-center">
                            <div class="form-group ">
                                <div class="input-group text-center">
                                <input type="text"  class="form-control required" autocomplete="off" id="exampleInputAmount" placeholder="" name='ii_v_bb[]' value="18" disabled>
                                </div>
                            </div>
                        </td>
                        <td class="align-middle text-left">
                            <div class="form-group">
                                <div class="input-group">
                                <input type="text"  class="form-control required" autocomplete="off" id="exampleInputAmount" placeholder="" name='ii_v_bb[]' value="4" disabled>
                                </div>
                            </div>
                        </td>
                        <td class="align-middle text-left">
                        <div class="form-group">
                                <div class="input-group">
                                <input type="text"  class="form-control required" autocomplete="off" id="exampleInputAmount" placeholder="" name='ii_v_bb[]' value="2" disabled>
                                </div>
                            </div>
                        </td>
                        <td class="align-middle text-left">
                        <div class="form-group">
                                <div class="input-group">
                                <input type="text"  class="form-control required" autocomplete="off" id="_viii_v_bb_1" placeholder="" name='viii_v_bb_1' value="<?php if(!empty($calibration_info)) {echo $calibration_info->viii_v_bb_1;} ?>" >
                                </div>
                            </div>
                        </td>
                        <td class="align-middle text-left">
                        <div class="form-group">
                                <div class="input-group">
                                <input type="text"  class="form-control required" autocomplete="off" id="_viii_v_bb_2" placeholder="" name='viii_v_bb_2' value="<?php if(!empty($calibration_info)) {echo $calibration_info->viii_v_bb_2;} ?>" >
                                </div>
                            </div>
                        </td>
                        <td class="align-middle text-left">
                            <div class="form-group">
                                <div class="input-group">
                                <input type="text"  class="form-control required" autocomplete="off" id="viii_v_bb_3" placeholder="" name='viii_v_bb_3' value="<?php if(!empty($calibration_info)) {echo $calibration_info->viii_v_bb_3;} ?>" >
                                </div>
                            </div>
                        </td>
                        <td class="align-middle text-left">
                        <div class="form-group">
                                <div class="input-group">
                                <input type="text"  class="form-control required" autocomplete="off" id="exampleInputAmount" placeholder="" name='ii_v_bb[]' value="" disabled>
                                </div>
                            </div>
                        </td>
                        <td class="align-middle text-left">
                        <div class="form-group">
                                <div class="input-group">
                                <input type="text"  class="form-control required" autocomplete="off" id="exampleInputAmount" placeholder="" name='ii_v_bb[]' value="" disabled>
                                </div>
                            </div>
                        </td>
                    </tr>
                    <tr class="form-inline">
                        <td class="align-middle text-center" style="font-weight:500;"></td>
                        <td class="align-middle text-center">
                            <div class="form-group ">
                                <div class="input-group text-center">
                                <input type="text"  class="form-control required" autocomplete="off" id="exampleInputAmount" placeholder="" name='ii_v_bb[]' value="19" disabled>
                                </div>
                            </div>
                        </td>
                        <td class="align-middle text-left">
                            <div class="form-group">
                                <div class="input-group">
                                <input type="text"  class="form-control required" autocomplete="off" id="exampleInputAmount" placeholder="" name='ii_v_bb[]' value="5" disabled>
                                </div>
                            </div>
                        </td>
                        <td class="align-middle text-left">
                        <div class="form-group">
                                <div class="input-group">
                                <input type="text"  class="form-control required" autocomplete="off" id="exampleInputAmount" placeholder="" name='ii_v_bb[]' value="1" disabled>
                                </div>
                            </div>
                        </td>
                        <td class="align-middle text-left">
                        <div class="form-group">
                                <div class="input-group">
                                <input type="text"  class="form-control required" autocomplete="off" id="_xi_v_bb_1" placeholder="" name='xi_v_bb_1' value="<?php if(!empty($calibration_info)) {echo $calibration_info->xi_v_bb_1;} ?>" >
                                </div>
                            </div>
                        </td>
                        <td class="align-middle text-left">
                        <div class="form-group">
                                <div class="input-group">
                                <input type="text"  class="form-control required" autocomplete="off" id="_xi_v_bb_2" placeholder="" name='xi_v_bb_2' value="<?php if(!empty($calibration_info)) {echo $calibration_info->xi_v_bb_2;} ?>" >
                                </div>
                            </div>
                        </td>
                        <td class="align-middle text-left">
                            <div class="form-group">
                                <div class="input-group">
                                <input type="text"  class="form-control required" autocomplete="off" id="_xi_v_bb_3" placeholder="" name='xi_v_bb_3' value="<?php if(!empty($calibration_info)) {echo $calibration_info->xi_v_bb_3;} ?>" >
                                </div>
                            </div>
                        </td>
                        <td class="align-middle text-left">
                        <div class="form-group">
                                <div class="input-group">
                                <input type="text"  class="form-control required" autocomplete="off" id="exampleInputAmount" placeholder="" name='ii_v_bb[]' value="" disabled>
                                </div>
                            </div>
                        </td>
                        <td class="align-middle text-left">
                        <div class="form-group">
                                <div class="input-group">
                                <input type="text"  class="form-control required" autocomplete="off" id="exampleInputAmount" placeholder="" name='ii_v_bb[]' value="" disabled>
                                </div>
                            </div>
                        </td>
                    </tr>
                    <tr class="form-inline">
                        <td class="align-middle text-center" style="font-weight:500;"></td>
                        <td class="align-middle text-center">
                            <div class="form-group ">
                                <div class="input-group text-center">
                                <input type="text"  class="form-control required" autocomplete="off" id="exampleInputAmount" placeholder="" name='ii_v_bb[]' value="20" disabled>
                                </div>
                            </div>
                        </td>
                        <td class="align-middle text-left">
                            <div class="form-group">
                                <div class="input-group">
                                <input type="text"  class="form-control required" autocomplete="off" id="exampleInputAmount" placeholder="" name='ii_v_bb[]' value="5" disabled>
                                </div>
                            </div>
                        </td>
                        <td class="align-middle text-left">
                        <div class="form-group">
                                <div class="input-group">
                                <input type="text"  class="form-control required" autocomplete="off" id="exampleInputAmount" placeholder="" name='ii_v_bb[]' value="2" disabled>
                                </div>
                            </div>
                        </td>
                        <td class="align-middle text-left">
                        <div class="form-group">
                                <div class="input-group">
                                <input type="text"  class="form-control required" autocomplete="off" id="_x_v_bb_1" placeholder="" name='x_v_bb_1' value="<?php if(!empty($calibration_info)) {echo $calibration_info->x_v_bb_1;} ?>" >
                                </div>
                            </div>
                        </td>
                        <td class="align-middle text-left">
                        <div class="form-group">
                                <div class="input-group">
                                <input type="text"  class="form-control required" autocomplete="off" id="_x_v_bb_2" placeholder="" name='x_v_bb_2' value="<?php if(!empty($calibration_info)) {echo $calibration_info->x_v_bb_2;} ?>" >
                                </div>
                            </div>
                        </td>
                        <td class="align-middle text-left">
                            <div class="form-group">
                                <div class="input-group">
                                <input type="text"  class="form-control required" autocomplete="off" id="_x_v_bb_3" placeholder="" name='x_v_bb_3' value="<?php if(!empty($calibration_info)) {echo $calibration_info->x_v_bb_3;} ?>" >
                                </div>
                            </div>
                        </td>
                        <td class="align-middle text-left">
                        <div class="form-group">
                                <div class="input-group">
                                <input type="text"  class="form-control required" autocomplete="off" id="exampleInputAmount" placeholder="" name='ii_v_bb[]' value="" disabled>
                                </div>
                            </div>
                        </td>
                        <td class="align-middle text-left">
                        <div class="form-group">
                                <div class="input-group">
                                <input type="text"  class="form-control required" autocomplete="off" id="exampleInputAmount" placeholder="" name='xi_v_bb[]' value="" disabled>
                                </div>
                            </div>
                        </td>
                    </tr>
                    <tr class="form-inline">
                        <td class="align-middle text-center" style="font-weight:500;"></td>
                        <td class="align-middle text-center">
                            <div class="form-group ">
                                <div class="input-group text-center">
                                <input type="text"  class="form-control required" autocomplete="off" id="exampleInputAmount" placeholder="2" name='ii_v_cc[]' value="STOP TIME" disabled>
                                </div>
                            </div>
                        </td>
                        
                        <td class="align-middle text-center">
                            <div class="form-group">
                                <div class="input-group">
                                    <input type="time" class="form-control required" id="stop_time_5" name="stop_time_5" autocomplete="off" value="<?php echo !empty($calibration_info->stop_time_5) ? date('H:i', strtotime($calibration_info->stop_time_5)) : ''; ?>">
                                </div>
                            </div>
                        </td>
                        <td class="align-middle text-left">
                        <div class="form-group">
                                <div class="input-group">
                                <input type="text"  class="form-control required" autocomplete="off" id="exampleInputAmount" placeholder="" name='ii_v_cc[]' value="" disabled>
                                </div>
                            </div>
                        </td>
                        <td class="align-middle text-left">
                        <div class="form-group">
                                <div class="input-group">
                                <input type="text"  class="form-control required" autocomplete="off" id="_i_v_cc_1" placeholder="" name='i_v_cc_1' value="" disabled>
                                </div>
                            </div>
                        </td>
                        <td class="align-middle text-left">
                        <div class="form-group">
                                <div class="input-group">
                                <input type="text"  class="form-control required" autocomplete="off" id="_i_v_cc_2" placeholder="" name='i_v_cc_2' value="" disabled>
                                </div>
                            </div>
                        </td>
                        <td class="align-middle text-left">
                            <div class="form-group">
                                <div class="input-group">
                                <input type="text"  class="form-control required" autocomplete="off" id="_i_v_cc_3" placeholder="" name='i_v_cc_3' value="" disabled>
                                </div>
                            </div>
                        </td>
                        <td class="align-middle text-left">
                        <div class="form-group">
                                <div class="input-group">
                                <input type="text"  class="form-control required" autocomplete="off" id="exampleInputAmount" placeholder="" name='ii_v_cc[]' value="" disabled>
                                </div>
                            </div>
                        </td>
                        <td class="align-middle text-left">
                        <div class="form-group">
                                <div class="input-group">
                                <input type="text"  class="form-control required" autocomplete="off" id="exampleInputAmount" placeholder="" name='ii_v_cc[]' value="" disabled>
                                </div>
                            </div>
                        </td>
                    </tr>
                    <!-- Add more rows as needed -->
                </tbody>               
            </table>
            <button type="button" class="btn btn-secondary prev-step">Previous</button>
            <button type="button" class="btn btn-primary next-step">Next</button>
            </div>
            
            <!-- Table A4 -->
            <div class="form-section">
            <b>POST-CALIBRATION CHECKS REPORT ENTRY 3</b>
            <table class="table" id="serviceFields">
                <thead>
                
                    <tr>
                        <th class="text-left" style="font-weight:500;">Series</th>
                        <th class="text-left" style="font-weight:500;">Seq. No.</th>
                        <th class="text-left" style="font-weight:500;">Set</th>
                        <th class="text-left" style="font-weight:500;">Rover Point </th>
                        <th class="text-left" style="font-weight:500;">Eastings</th>
                        <th class="text-left" style="font-weight:500;">Northings</th>
                        <th class="text-left" style="font-weight:500;">Elevation</th>
                        <th class="text-left" style="font-weight:500;">Horizontal Distance (m)</th>
                        <th class="text-left" style="font-weight:500;">Elevation Accuracy (mm)</th>
                    </tr>
                </thead>
                <tbody>
                    <tr class="form-inline">
                        <td class="align-middle text-center" style="font-weight:500;"></td>
                        <td class="align-middle text-center">
                            <div class="form-group ">
                                <div class="input-group text-center">
                                <input type="text"  class="form-control required" autocomplete="off" id="exampleInputAmount" placeholder="2" name='ii_v_cc[]' value="START TIME" disabled>
                                </div>
                            </div>
                        </td>
                        
                        <td class="align-middle text-center">
                            <div class="form-group">
                                <div class="input-group">
                                    <input type="time" class="form-control required" id="start_time_6" name="start_time_6" autocomplete="off" value="<?php echo !empty($calibration_info->start_time_6) ? date('H:i', strtotime($calibration_info->start_time_6)) : ''; ?>">
                                </div>
                            </div>
                        </td>
                        <td class="align-middle text-left">
                        <div class="form-group">
                                <div class="input-group">
                                <input type="text"  class="form-control required" autocomplete="off" id="exampleInputAmount" placeholder="" name='ii_v_cc[]' value="" disabled>
                                </div>
                            </div>
                        </td>
                        <td class="align-middle text-left">
                        <div class="form-group">
                                <div class="input-group">
                                <input type="text"  class="form-control required" autocomplete="off" id="_i_v_cc_1" placeholder="" name='i_v_cc_1' value="" disabled>
                                </div>
                            </div>
                        </td>
                        <td class="align-middle text-left">
                        <div class="form-group">
                                <div class="input-group">
                                <input type="text"  class="form-control required" autocomplete="off" id="_i_v_cc_2" placeholder="" name='i_v_cc_2' value="" disabled>
                                </div>
                            </div>
                        </td>
                        <td class="align-middle text-left">
                            <div class="form-group">
                                <div class="input-group">
                                <input type="text"  class="form-control required" autocomplete="off" id="_i_v_cc_3" placeholder="" name='i_v_cc_3' value="" disabled>
                                </div>
                            </div>
                        </td>
                        <td class="align-middle text-left">
                        <div class="form-group">
                                <div class="input-group">
                                <input type="text"  class="form-control required" autocomplete="off" id="exampleInputAmount" placeholder="" name='ii_v_cc[]' value="" disabled>
                                </div>
                            </div>
                        </td>
                        <td class="align-middle text-left">
                        <div class="form-group">
                                <div class="input-group">
                                <input type="text"  class="form-control required" autocomplete="off" id="exampleInputAmount" placeholder="" name='ii_v_cc[]' value="" disabled>
                                </div>
                            </div>
                        </td>
                    </tr>
                    <tr class="form-inline">
                        <td class="align-middle text-center" style="font-weight:500;"></td>
                        <td class="align-middle text-center">
                            <div class="form-group ">
                                <div class="input-group text-center">
                                <input type="text"  class="form-control required" autocomplete="off" id="exampleInputAmount" placeholder="2" name='ii_v_cc[]' value="21" disabled>
                                </div>
                            </div>
                        </td>
                        <td class="align-middle text-left">
                            <div class="form-group">
                                <div class="input-group">
                                <input type="text"  class="form-control required" autocomplete="off" id="exampleInputAmount" placeholder="" name='ii_v_cc[]' value="1" disabled>
                                </div>
                            </div>
                        </td>
                        <td class="align-middle text-left">
                        <div class="form-group">
                                <div class="input-group">
                                <input type="text"  class="form-control required" autocomplete="off" id="exampleInputAmount" placeholder="" name='ii_v_cc[]' value="1" disabled>
                                </div>
                            </div>
                        </td>
                        <td class="align-middle text-left">
                        <div class="form-group">
                                <div class="input-group">
                                <input type="text"  class="form-control required" autocomplete="off" id="_i_v_cc_1" placeholder="" name='i_v_cc_1' value="<?php if(!empty($calibration_info)) {echo $calibration_info->i_v_cc_1;} ?>" required >
                                </div>
                            </div>
                        </td>
                        <td class="align-middle text-left">
                        <div class="form-group">
                                <div class="input-group">
                                <input type="text"  class="form-control required" autocomplete="off" id="_i_v_cc_2" placeholder="" name='i_v_cc_2' value="<?php if(!empty($calibration_info)) {echo $calibration_info->i_v_cc_2;} ?>" required>
                                </div>
                            </div>
                        </td>
                        <td class="align-middle text-left">
                            <div class="form-group">
                                <div class="input-group">
                                <input type="text"  class="form-control required" autocomplete="off" id="_i_v_cc_3" placeholder="" name='i_v_cc_3' value="<?php if(!empty($calibration_info)) {echo $calibration_info->i_v_cc_3;} ?>" required>
                                </div>
                            </div>
                        </td>
                        <td class="align-middle text-left">
                        <div class="form-group">
                                <div class="input-group">
                                <input type="text"  class="form-control required" autocomplete="off" id="exampleInputAmount" placeholder="" name='ii_v_cc[]' value="" disabled>
                                </div>
                            </div>
                        </td>
                        <td class="align-middle text-left">
                        <div class="form-group">
                                <div class="input-group">
                                <input type="text"  class="form-control required" autocomplete="off" id="exampleInputAmount" placeholder="" name='ii_v_cc[]' value="" disabled>
                                </div>
                            </div>
                        </td>
                    </tr>
                    <tr class="form-inline">
                        <td class="align-middle text-center" style="font-weight:500;"></td>
                        <td class="align-middle text-center">
                            <div class="form-group ">
                                <div class="input-group text-center">
                                <input type="text"  class="form-control required" autocomplete="off" id="exampleInputAmount" placeholder="1" name='ii_v_cc[]' value="22" disabled>
                                </div>
                            </div>
                        </td>
                        <td class="align-middle text-left">
                            <div class="form-group">
                                <div class="input-group">
                                <input type="text"  class="form-control required" autocomplete="off" id="exampleInputAmount" placeholder="" name='ii_v_cc[]' value="1" disabled>
                                </div>
                            </div>
                        </td>
                        <td class="align-middle text-left">
                        <div class="form-group">
                                <div class="input-group">
                                <input type="text"  class="form-control required" autocomplete="off" id="exampleInputAmount" placeholder="" name='ii_v_cc[]' value="2" disabled>
                                </div>
                            </div>
                        </td>
                        <td class="align-middle text-left">
                        <div class="form-group">
                                <div class="input-group">
                                <input type="text"  class="form-control required" autocomplete="off" id="_ii_v_cc_1" placeholder="" name='ii_v_cc_1' value="<?php if(!empty($calibration_info)) {echo $calibration_info->ii_v_cc_1;} ?>" >
                                </div>
                            </div>
                        </td>
                        <td class="align-middle text-left">
                        <div class="form-group">
                                <div class="input-group">
                                <input type="text"  class="form-control required" autocomplete="off" id="_ii_v_cc_2" placeholder="" name='ii_v_cc_2' value="<?php if(!empty($calibration_info)) {echo $calibration_info->ii_v_cc_2;} ?>" >
                                </div>
                            </div>
                        </td>
                        <td class="align-middle text-left">
                            <div class="form-group">
                                <div class="input-group">
                                <input type="text"  class="form-control required" autocomplete="off" id="_ii_v_cc_3" placeholder="" name='ii_v_cc_3' value="<?php if(!empty($calibration_info)) {echo $calibration_info->ii_v_cc_3;} ?>" >
                                </div>
                            </div>
                        </td>
                        <td class="align-middle text-left">
                        <div class="form-group">
                                <div class="input-group">
                                <input type="text"  class="form-control required" autocomplete="off" id="exampleInputAmount" placeholder="" name='ii_v_cc[]' value="" disabled>
                                </div>
                            </div>
                        </td>
                        <td class="align-middle text-left">
                        <div class="form-group">
                                <div class="input-group">
                                <input type="text"  class="form-control required" autocomplete="off" id="exampleInputAmount" placeholder="" name='ii_v_cc[]' value="" disabled>
                                </div>
                            </div>
                        </td>
                    </tr>
                    <tr class="form-inline">
                        <td class="align-middle text-center" style="font-weight:500;"></td>
                        <td class="align-middle text-center">
                            <div class="form-group ">
                                <div class="input-group text-center">
                                <input type="text"  class="form-control required" autocomplete="off" id="exampleInputAmount" placeholder="1" name='ii_v_cc[]' value="23" disabled>
                                </div>
                            </div>
                        </td>
                        <td class="align-middle text-left">
                            <div class="form-group">
                                <div class="input-group">
                                <input type="text"  class="form-control required" autocomplete="off" id="exampleInputAmount" placeholder="" name='ii_v_cc[]' value="2" disabled>
                                </div>
                            </div>
                        </td>
                        <td class="align-middle text-left">
                        <div class="form-group">
                                <div class="input-group">
                                <input type="text"  class="form-control required" autocomplete="off" id="exampleInputAmount" placeholder="" name='ii_v_cc[]' value="1" disabled>
                                </div>
                            </div>
                        </td>
                        <td class="align-middle text-left">
                        <div class="form-group">
                                <div class="input-group">
                                <input type="text"  class="form-control required" autocomplete="off" id="_iii_v_cc_1" placeholder="" name='iii_v_cc_1' value="<?php if(!empty($calibration_info)) {echo $calibration_info->iii_v_cc_1;} ?>" >
                                </div>
                            </div>
                        </td>
                        <td class="align-middle text-left">
                        <div class="form-group">
                                <div class="input-group">
                                <input type="text"  class="form-control required" autocomplete="off" id="_iii_v_cc_2" placeholder="" name='iii_v_cc_2' value="<?php if(!empty($calibration_info)) {echo $calibration_info->iii_v_cc_2;} ?>" >
                                </div>
                            </div>
                        </td>
                        <td class="align-middle text-left">
                            <div class="form-group">
                                <div class="input-group">
                                <input type="text"  class="form-control required" autocomplete="off" id="_iii_v_cc_3" placeholder="" name='iii_v_cc_3' value="<?php if(!empty($calibration_info)) {echo $calibration_info->iii_v_cc_3;} ?>" >
                                </div>
                            </div>
                        </td>
                        <td class="align-middle text-left">
                        <div class="form-group">
                                <div class="input-group">
                                <input type="text"  class="form-control required" autocomplete="off" id="exampleInputAmount" placeholder="" name='ii_v_cc[]' value="" disabled>
                                </div>
                            </div>
                        </td>
                        <td class="align-middle text-left">
                        <div class="form-group">
                                <div class="input-group">
                                <input type="text"  class="form-control required" autocomplete="off" id="exampleInputAmount" placeholder="" name='ii_v_cc[]' value="" disabled>
                                </div>
                            </div>
                        </td>
                    </tr>
                    <tr class="form-inline">
                        <td class="align-middle text-center" style="font-weight:500;"></td>
                        <td class="align-middle text-center">
                            <div class="form-group ">
                                <div class="input-group text-center">
                                <input type="text"  class="form-control required" autocomplete="off" id="exampleInputAmount" placeholder="" name='ii_v_cc[]' value="24" disabled>
                                </div>
                            </div>
                        </td>
                        <td class="align-middle text-left">
                            <div class="form-group">
                                <div class="input-group">
                                <input type="text"  class="form-control required" autocomplete="off" id="exampleInputAmount" placeholder="" name='ii_v_cc[]' value="2" disabled>
                                </div>
                            </div>
                        </td>
                        <td class="align-middle text-left">
                        <div class="form-group">
                                <div class="input-group">
                                <input type="text"  class="form-control required" autocomplete="off" id="exampleInputAmount" placeholder="" name='ii_v_cc[]' value="2" disabled>
                                </div>
                            </div>
                        </td>
                        <td class="align-middle text-left">
                        <div class="form-group">
                                <div class="input-group">
                                <input type="text"  class="form-control required" autocomplete="off" id="_iv_v_cc_1" placeholder="" name='iv_v_cc_1' value="<?php if(!empty($calibration_info)) {echo $calibration_info->iv_v_cc_1;} ?>" >
                                </div>
                            </div>
                        </td>
                        <td class="align-middle text-left">
                        <div class="form-group">
                                <div class="input-group">
                                <input type="text"  class="form-control required" autocomplete="off" id="_iv_v_cc_2" placeholder="" name='iv_v_cc_2' value="<?php if(!empty($calibration_info)) {echo $calibration_info->iv_v_cc_2;} ?>" >
                                </div>
                            </div>
                        </td>
                        <td class="align-middle text-left">
                            <div class="form-group">
                                <div class="input-group">
                                <input type="text"  class="form-control required" autocomplete="off" id="_iv_v_cc_3" placeholder="" name='iv_v_cc_3' value="<?php if(!empty($calibration_info)) {echo $calibration_info->iv_v_cc_3;} ?>" >
                                </div>
                            </div>
                        </td>
                        <td class="align-middle text-left">
                        <div class="form-group">
                                <div class="input-group">
                                <input type="text"  class="form-control required" autocomplete="off" id="exampleInputAmount" placeholder="" name='ii_v_cc[]' value="" disabled>
                                </div>
                            </div>
                        </td>
                        <td class="align-middle text-left">
                        <div class="form-group">
                                <div class="input-group">
                                <input type="text"  class="form-control required" autocomplete="off" id="exampleInputAmount" placeholder="" name='ii_v_cc[]' value="" disabled>
                                </div>
                            </div>
                        </td>
                    </tr>
                    <tr class="form-inline">
                        <td class="align-middle text-center" style="font-weight:500;">3</td>
                        <td class="align-middle text-center">
                            <div class="form-group ">
                                <div class="input-group text-center">
                                <input type="text"  class="form-control required" autocomplete="off" id="exampleInputAmount" placeholder="" name='ii_v_cc[]' value="25" disabled>
                                </div>
                            </div>
                        </td>
                        <td class="align-middle text-left">
                            <div class="form-group">
                                <div class="input-group">
                                <input type="text"  class="form-control required" autocomplete="off" id="exampleInputAmount" placeholder="" name='ii_v_cc[]' value="3" disabled>
                                </div>
                            </div>
                        </td>
                        <td class="align-middle text-left">
                        <div class="form-group">
                                <div class="input-group">
                                <input type="text"  class="form-control required" autocomplete="off" id="exampleInputAmount" placeholder="" name='ii_v_cc[]' value="1" disabled>
                                </div>
                            </div>
                        </td>
                        <td class="align-middle text-left">
                        <div class="form-group">
                                <div class="input-group">
                                <input type="text"  class="form-control required" autocomplete="off" id="_v_v_cc_1" placeholder="" name='v_v_cc_1' value="<?php if(!empty($calibration_info)) {echo $calibration_info->v_v_cc_1;} ?>" >
                                </div>
                            </div>
                        </td>
                        <td class="align-middle text-left">
                        <div class="form-group">
                                <div class="input-group">
                                <input type="text"  class="form-control required" autocomplete="off" id="_v_v_cc_2" placeholder="" name='v_v_cc_2' value="<?php if(!empty($calibration_info)) {echo $calibration_info->v_v_cc_2;} ?>" >
                                </div>
                            </div>
                        </td>
                        <td class="align-middle text-left">
                            <div class="form-group">
                                <div class="input-group">
                                <input type="text"  class="form-control required" autocomplete="off" id="_v_v_cc_3" placeholder="" name='v_v_cc_3' value="<?php if(!empty($calibration_info)) {echo $calibration_info->v_v_cc_3;} ?>" >
                                </div>
                            </div>
                        </td>
                        <td class="align-middle text-left">
                        <div class="form-group">
                                <div class="input-group">
                                <input type="text"  class="form-control required" autocomplete="off" id="exampleInputAmount" placeholder="" name='ii_v_cc[]' value="" disabled>
                                </div>
                            </div>
                        </td>
                        <td class="align-middle text-left">
                        <div class="form-group">
                                <div class="input-group">
                                <input type="text"  class="form-control required" autocomplete="off" id="exampleInputAmount" placeholder="" name='ii_v_cc[]' value="" disabled>
                                </div>
                            </div>
                        </td>
                    </tr>
                    <tr class="form-inline">
                        <td class="align-middle text-center" style="font-weight:500;"></td>
                        <td class="align-middle text-center">
                            <div class="form-group ">
                                <div class="input-group text-center">
                                <input type="text"  class="form-control required" autocomplete="off" id="exampleInputAmount" placeholder="" name='ii_v_cc[]' value="26" disabled>
                                </div>
                            </div>
                        </td>
                        <td class="align-middle text-left">
                            <div class="form-group">
                                <div class="input-group">
                                <input type="text"  class="form-control required" autocomplete="off" id="exampleInputAmount" placeholder="" name='ii_v_cc[]' value="3" disabled>
                                </div>
                            </div>
                        </td>
                        <td class="align-middle text-left">
                        <div class="form-group">
                                <div class="input-group">
                                <input type="text"  class="form-control required" autocomplete="off" id="exampleInputAmount" placeholder="" name='ii_v_cc[]' value="2" disabled>
                                </div>
                            </div>
                        </td>
                        <td class="align-middle text-left">
                        <div class="form-group">
                                <div class="input-group">
                                <input type="text"  class="form-control required" autocomplete="off" id="_vi_v_cc_1" placeholder="" name='vi_v_cc_1' value="<?php if(!empty($calibration_info)) {echo $calibration_info->vi_v_cc_1;} ?>" >
                                </div>
                            </div>
                        </td>
                        <td class="align-middle text-left">
                        <div class="form-group">
                                <div class="input-group">
                                <input type="text"  class="form-control required" autocomplete="off" id="_vi_v_cc_2" placeholder="" name='vi_v_cc_2' value="<?php if(!empty($calibration_info)) {echo $calibration_info->vi_v_cc_2;} ?>" >
                                </div>
                            </div>
                        </td>
                        <td class="align-middle text-left">
                            <div class="form-group">
                                <div class="input-group">
                                <input type="text"  class="form-control required" autocomplete="off" id="_vi_v_cc_3" placeholder="" name='vi_v_cc_3' value="<?php if(!empty($calibration_info)) {echo $calibration_info->vi_v_cc_3;} ?>" >
                                </div>
                            </div>
                        </td>
                        <td class="align-middle text-left">
                        <div class="form-group">
                                <div class="input-group">
                                <input type="text"  class="form-control required" autocomplete="off" id="exampleInputAmount" placeholder="" name='ii_v_cc[]' value="" disabled>
                                </div>
                            </div>
                        </td>
                        <td class="align-middle text-left">
                        <div class="form-group">
                                <div class="input-group">
                                <input type="text"  class="form-control required" autocomplete="off" id="exampleInputAmount" placeholder="" name='ii_v_cc[]' value="" disabled>
                                </div>
                            </div>
                        </td>
                    </tr>
                    <tr class="form-inline">
                        <td class="align-middle text-center" style="font-weight:500;"></td>
                        <td class="align-middle text-center">
                            <div class="form-group ">
                                <div class="input-group text-center">
                                <input type="text"  class="form-control required" autocomplete="off" id="exampleInputAmount" placeholder="" name='ii_v_cc[]' value="27" disabled>
                                </div>
                            </div>
                        </td>
                        <td class="align-middle text-left">
                            <div class="form-group">
                                <div class="input-group">
                                <input type="text"  class="form-control required" autocomplete="off" id="exampleInputAmount" placeholder="" name='ii_v_cc[]' value="4" disabled>
                                </div>
                            </div>
                        </td>
                        <td class="align-middle text-left">
                        <div class="form-group">
                                <div class="input-group">
                                <input type="text"  class="form-control required" autocomplete="off" id="exampleInputAmount" placeholder="" name='ii_v_cc[]' value="1" disabled>
                                </div>
                            </div>
                        </td>
                        <td class="align-middle text-left">
                        <div class="form-group">
                                <div class="input-group">
                                <input type="text"  class="form-control required" autocomplete="off" id="_vii_v_cc_1" placeholder="" name='vii_v_cc_1' value="<?php if(!empty($calibration_info)) {echo $calibration_info->vii_v_cc_1;} ?>" >
                                </div>
                            </div>
                        </td>
                        <td class="align-middle text-left">
                        <div class="form-group">
                                <div class="input-group">
                                <input type="text"  class="form-control required" autocomplete="off" id="_vii_v_cc_2" placeholder="" name='vii_v_cc_2' value="<?php if(!empty($calibration_info)) {echo $calibration_info->vii_v_cc_2;} ?>" >
                                </div>
                            </div>
                        </td>
                        <td class="align-middle text-left">
                            <div class="form-group">
                                <div class="input-group">
                                <input type="text"  class="form-control required" autocomplete="off" id="_vii_v_cc_3" placeholder="" name='vii_v_cc_3' value="<?php if(!empty($calibration_info)) {echo $calibration_info->vii_v_cc_3;} ?>" >
                                </div>
                            </div>
                        </td>
                        <td class="align-middle text-left">
                        <div class="form-group">
                                <div class="input-group">
                                <input type="text"  class="form-control required" autocomplete="off" id="exampleInputAmount" placeholder="" name='ii_v_cc[]' value="" disabled>
                                </div>
                            </div>
                        </td>
                        <td class="align-middle text-left">
                        <div class="form-group">
                                <div class="input-group">
                                <input type="text"  class="form-control required" autocomplete="off" id="exampleInputAmount" placeholder="" name='ii_v_cc[]' value="" disabled>
                                </div>
                            </div>
                        </td>
                    </tr>
                    <tr class="form-inline">
                        <td class="align-middle text-center" style="font-weight:500;"></td>
                        <td class="align-middle text-center">
                            <div class="form-group ">
                                <div class="input-group text-center">
                                <input type="text"  class="form-control required" autocomplete="off" id="exampleInputAmount" placeholder="" name='ii_v_cc[]' value="28" disabled>
                                </div>
                            </div>
                        </td>
                        <td class="align-middle text-left">
                            <div class="form-group">
                                <div class="input-group">
                                <input type="text"  class="form-control required" autocomplete="off" id="exampleInputAmount" placeholder="" name='ii_v_cc[]' value="4" disabled>
                                </div>
                            </div>
                        </td>
                        <td class="align-middle text-left">
                        <div class="form-group">
                                <div class="input-group">
                                <input type="text"  class="form-control required" autocomplete="off" id="exampleInputAmount" placeholder="" name='ii_v_cc[]' value="2" disabled>
                                </div>
                            </div>
                        </td>
                        <td class="align-middle text-left">
                        <div class="form-group">
                                <div class="input-group">
                                <input type="text"  class="form-control required" autocomplete="off" id="_viii_v_cc_1" placeholder="" name='viii_v_cc_1' value="<?php if(!empty($calibration_info)) {echo $calibration_info->viii_v_cc_1;} ?>" >
                                </div>
                            </div>
                        </td>
                        <td class="align-middle text-left">
                        <div class="form-group">
                                <div class="input-group">
                                <input type="text"  class="form-control required" autocomplete="off" id="_viii_v_cc_2" placeholder="" name='viii_v_cc_2' value="<?php if(!empty($calibration_info)) {echo $calibration_info->viii_v_cc_2;} ?>" >
                                </div>
                            </div>
                        </td>
                        <td class="align-middle text-left">
                            <div class="form-group">
                                <div class="input-group">
                                <input type="text"  class="form-control required" autocomplete="off" id="viii_v_cc_3" placeholder="" name='viii_v_cc_3' value="<?php if(!empty($calibration_info)) {echo $calibration_info->viii_v_cc_3;} ?>" >
                                </div>
                            </div>
                        </td>
                        <td class="align-middle text-left">
                        <div class="form-group">
                                <div class="input-group">
                                <input type="text"  class="form-control required" autocomplete="off" id="exampleInputAmount" placeholder="" name='ii_v_cc[]' value="" disabled>
                                </div>
                            </div>
                        </td>
                        <td class="align-middle text-left">
                        <div class="form-group">
                                <div class="input-group">
                                <input type="text"  class="form-control required" autocomplete="off" id="exampleInputAmount" placeholder="" name='ii_v_cc[]' value="" disabled>
                                </div>
                            </div>
                        </td>
                    </tr>
                    <tr class="form-inline">
                        <td class="align-middle text-center" style="font-weight:500;"></td>
                        <td class="align-middle text-center">
                            <div class="form-group ">
                                <div class="input-group text-center">
                                <input type="text"  class="form-control required" autocomplete="off" id="exampleInputAmount" placeholder="" name='ii_v_cc[]' value="29" disabled>
                                </div>
                            </div>
                        </td>
                        <td class="align-middle text-left">
                            <div class="form-group">
                                <div class="input-group">
                                <input type="text"  class="form-control required" autocomplete="off" id="exampleInputAmount" placeholder="" name='ii_v_cc[]' value="5" disabled>
                                </div>
                            </div>
                        </td>
                        <td class="align-middle text-left">
                        <div class="form-group">
                                <div class="input-group">
                                <input type="text"  class="form-control required" autocomplete="off" id="exampleInputAmount" placeholder="" name='ii_v_cc[]' value="1" disabled>
                                </div>
                            </div>
                        </td>
                        <td class="align-middle text-left">
                        <div class="form-group">
                                <div class="input-group">
                                <input type="text"  class="form-control required" autocomplete="off" id="_xi_v_cc_1" placeholder="" name='xi_v_cc_1' value="<?php if(!empty($calibration_info)) {echo $calibration_info->xi_v_cc_1;} ?>" >
                                </div>
                            </div>
                        </td>
                        <td class="align-middle text-left">
                        <div class="form-group">
                                <div class="input-group">
                                <input type="text"  class="form-control required" autocomplete="off" id="_xi_v_cc_2" placeholder="" name='xi_v_cc_2' value="<?php if(!empty($calibration_info)) {echo $calibration_info->xi_v_cc_2;} ?>" >
                                </div>
                            </div>
                        </td>
                        <td class="align-middle text-left">
                            <div class="form-group">
                                <div class="input-group">
                                <input type="text"  class="form-control required" autocomplete="off" id="_xi_v_cc_3" placeholder="" name='xi_v_cc_3' value="<?php if(!empty($calibration_info)) {echo $calibration_info->xi_v_cc_3;} ?>" >
                                </div>
                            </div>
                        </td>
                        <td class="align-middle text-left">
                        <div class="form-group">
                                <div class="input-group">
                                <input type="text"  class="form-control required" autocomplete="off" id="exampleInputAmount" placeholder="" name='ii_v_cc[]' value="" disabled>
                                </div>
                            </div>
                        </td>
                        <td class="align-middle text-left">
                        <div class="form-group">
                                <div class="input-group">
                                <input type="text"  class="form-control required" autocomplete="off" id="exampleInputAmount" placeholder="" name='ii_v_cc[]' value="" disabled>
                                </div>
                            </div>
                        </td>
                    </tr>
                    <tr class="form-inline">
                        <td class="align-middle text-center" style="font-weight:500;"></td>
                        <td class="align-middle text-center">
                            <div class="form-group ">
                                <div class="input-group text-center">
                                <input type="text"  class="form-control required" autocomplete="off" id="exampleInputAmount" placeholder="" name='ii_v_cc[]' value="30" disabled>
                                </div>
                            </div>
                        </td>
                        <td class="align-middle text-left">
                            <div class="form-group">
                                <div class="input-group">
                                <input type="text"  class="form-control required" autocomplete="off" id="exampleInputAmount" placeholder="" name='ii_v_cc[]' value="5" disabled>
                                </div>
                            </div>
                        </td>
                        <td class="align-middle text-left">
                        <div class="form-group">
                                <div class="input-group">
                                <input type="text"  class="form-control required" autocomplete="off" id="exampleInputAmount" placeholder="" name='ii_v_cc[]' value="2" disabled>
                                </div>
                            </div>
                        </td>
                        <td class="align-middle text-left">
                        <div class="form-group">
                                <div class="input-group">
                                <input type="text"  class="form-control required" autocomplete="off" id="_x_v_cc_1" placeholder="" name='x_v_cc_1' value="<?php if(!empty($calibration_info)) {echo $calibration_info->x_v_cc_1;} ?>" >
                                </div>
                            </div>
                        </td>
                        <td class="align-middle text-left">
                        <div class="form-group">
                                <div class="input-group">
                                <input type="text"  class="form-control required" autocomplete="off" id="_x_v_cc_2" placeholder="" name='x_v_cc_2' value="<?php if(!empty($calibration_info)) {echo $calibration_info->x_v_cc_2;} ?>" >
                                </div>
                            </div>
                        </td>
                        <td class="align-middle text-left">
                            <div class="form-group">
                                <div class="input-group">
                                <input type="text"  class="form-control required" autocomplete="off" id="_x_v_cc_3" placeholder="" name='x_v_cc_3' value="<?php if(!empty($calibration_info)) {echo $calibration_info->x_v_cc_3;} ?>" >
                                </div>
                            </div>
                        </td>
                        <td class="align-middle text-left">
                        <div class="form-group">
                                <div class="input-group">
                                <input type="text"  class="form-control required" autocomplete="off" id="exampleInputAmount" placeholder="" name='ii_v_cc[]' value="" disabled>
                                </div>
                            </div>
                        </td>
                        <td class="align-middle text-left">
                        <div class="form-group">
                                <div class="input-group">
                                <input type="text"  class="form-control required" autocomplete="off" id="exampleInputAmount" placeholder="" name='xi_v_cc[]' value="" disabled>
                                </div>
                            </div>
                        </td>
                    </tr>
                    <tr class="form-inline">
                        <td class="align-middle text-center" style="font-weight:500;"></td>
                        <td class="align-middle text-center">
                            <div class="form-group ">
                                <div class="input-group text-center">
                                <input type="text"  class="form-control required" autocomplete="off" id="exampleInputAmount" placeholder="2" name='ii_v_cc[]' value="STOP TIME" disabled>
                                </div>
                            </div>
                        </td>
                       
                        <td class="align-middle text-center">
                            <div class="form-group">
                                <div class="input-group">
                                    <input type="time" class="form-control required" id="stop_time_6" name="stop_time_6" autocomplete="off" value="<?php echo !empty($calibration_info->stop_time_6) ? date('H:i', strtotime($calibration_info->stop_time_6)) : ''; ?>">
                                </div>
                            </div>
                        </td>
                        <td class="align-middle text-left">
                        <div class="form-group">
                                <div class="input-group">
                                <input type="text"  class="form-control required" autocomplete="off" id="exampleInputAmount" placeholder="" name='ii_v_cc[]' value="" disabled>
                                </div>
                            </div>
                        </td>
                        <td class="align-middle text-left">
                        <div class="form-group">
                                <div class="input-group">
                                <input type="text"  class="form-control required" autocomplete="off" id="_i_v_cc_1" placeholder="" name='i_v_cc_1' value="" disabled>
                                </div>
                            </div>
                        </td>
                        <td class="align-middle text-left">
                        <div class="form-group">
                                <div class="input-group">
                                <input type="text"  class="form-control required" autocomplete="off" id="_i_v_cc_2" placeholder="" name='i_v_cc_2' value="" disabled>
                                </div>
                            </div>
                        </td>
                        <td class="align-middle text-left">
                            <div class="form-group">
                                <div class="input-group">
                                <input type="text"  class="form-control required" autocomplete="off" id="_i_v_cc_3" placeholder="" name='i_v_cc_3' value="" disabled>
                                </div>
                            </div>
                        </td>
                        <td class="align-middle text-left">
                        <div class="form-group">
                                <div class="input-group">
                                <input type="text"  class="form-control required" autocomplete="off" id="exampleInputAmount" placeholder="" name='ii_v_cc[]' value="" disabled>
                                </div>
                            </div>
                        </td>
                        <td class="align-middle text-left">
                        <div class="form-group">
                                <div class="input-group">
                                <input type="text"  class="form-control required" autocomplete="off" id="exampleInputAmount" placeholder="" name='ii_v_cc[]' value="" disabled>
                                </div>
                            </div>
                        </td>
                    </tr>
                    <!-- Add more rows as needed -->
                </tbody>               
            </table>
                <button type="button" class="btn btn-secondary prev-step">Previous</button>
                <button type="submit" class="btn btn-primary hide" disabled>Click Generate Report Button</button>
            </div>

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
  
  <div class="btn-bottom-toolbar bottom-transaction text-right">
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
