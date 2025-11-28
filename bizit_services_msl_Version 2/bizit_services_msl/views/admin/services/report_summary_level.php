<?php echo form_open('admin/services/save_calibration',array('id'=>"service_calibration_report_form",'method'=>'post'));?>
   <div class="">
        <div class="box-body">
            <div class="row">
                <div class="col-md-12">
                <div class="table-responsive">
                <b>INSTRUMENT INFORMATION REPORT ENTRY</b>
                    <table class="table" id="serviceFields">
                        <hr>
                        <tbody>
                            <tr class="form-inline">
                                <td class="align-middle text-left" style="padding: 10px; border: 1px solid #ccc;">INSTRUMENT MAKE
                                    <div class="form-group">
                                        <div class="input-group">
                                            <input type="text" class="form-control required" autocomplete="on" placeholder="FOIF" id="_lv_v_a_1" name="lv_v_a_1" value="<?php if(!empty($calibration_info) && !empty($calibration_info->lv_v_a_1)) {echo $calibration_info->lv_v_a_1;} else {echo $service_info->item_make;} ?>" required>
                                        </div>
                                    </div>
                                </td>
                                <td class="align-middle text-left" style="padding: 10px; border: 1px solid #ccc;">INSTRUMENT MODEL
                                    <div class="form-group">
                                        <div class="input-group text-center">
                                            <input type="text" class="form-control required" autocomplete="on" placeholder="AL132" name="lv_v_a_2" value="<?php if(!empty($calibration_info) && !empty($calibration_info->lv_v_a_2)) {echo $calibration_info->lv_v_a_2;} else {echo $service_info->item_model;} ?>" required>
                                        </div>
                                    </div>
                                </td>
                                <td class="align-middle text-left" style="padding: 10px; border: 1px solid #ccc;">INSTRUMENT SERIAL NO.
                                    <div class="form-group">
                                        <div class="input-group text-center">
                                            <input type="text" class="form-control required" autocomplete="on" placeholder="SGT57589" name="lv_v_a_3" value="<?php if(!empty($calibration_info) && !empty($calibration_info->lv_v_a_3)) {echo $calibration_info->lv_v_a_3;} else {echo $service_info->serial_no;} ?>" required>
                                        </div>
                                    </div>
                                </td>
                                <td class="align-middle text-left" style="padding: 10px; border: 1px solid #ccc;">INSTRUMENT CONDITION
                                    <div class="form-group">
                                        <div class="input-group text-center">
                                            <input type="text" class="form-control required" autocomplete="on" placeholder="USED" name="lv_v_a_4" value="<?php if(!empty($calibration_info) && !empty($calibration_info->lv_v_a_1)) {echo $calibration_info->lv_v_a_4;} else {echo $service_info->condition;} ?>" required>
                                        </div>
                                    </div>
                                </td>
                            </tr>
                            <tr class="form-inline">
                                <td class="align-middle text-left" style="padding: 10px; border: 1px solid #ccc;">TEST DISTANCE (M)
                                    <div class="form-group">
                                        <div class="input-group text-center">
                                            <input type="text" class="form-control required" autocomplete="on" placeholder="60" name="lv_v_a_5" value="<?php if(!empty($calibration_info)) {echo $calibration_info->lv_v_a_5;} ?>" required>
                                        </div>
                                    </div>
                                </td>
                                <td class="align-middle text-left" style="padding: 10px; border: 1px solid #ccc;">MANUFACTURER ACCURACY (00'')
                                    <div class="form-group">
                                        <div class="input-group text-center">
                                            <input type="text" class="form-control required" autocomplete="on" placeholder="1.5" name="lv_v_a_6" value="<?php if(!empty($calibration_info)) {echo $calibration_info->lv_v_a_6;} ?>" required>
                                        </div>
                                    </div>
                                </td>
                                <td class="align-middle text-left" style="padding: 10px; border: 1px solid #ccc;">WEATHER CONDITION
                                    <div class="form-group">
                                        <div class="input-group text-center">
                                            <input type="text" class="form-control required" autocomplete="on" placeholder="SUNNY" name="lv_v_a_7" value="<?php if(!empty($calibration_info)) {echo $calibration_info->lv_v_a_7;} ?>" required>
                                        </div>
                                    </div>
                                </td>
                                <td class="align-middle text-left" style="padding: 10px; border: 1px solid #ccc;">TEMPERATURE (°C)
                                    <div class="form-group">
                                        <div class="input-group text-center">
                                            <input type="text" class="form-control required" autocomplete="on" placeholder="20" name="lv_v_a_8" value="<?php if(!empty($calibration_info)) {echo $calibration_info->lv_v_a_8;} ?>" required>
                                        </div>
                                    </div>
                                </td>
                            </tr>
                            <tr class="form-inline">
                                <td class="align-middle text-left" style="padding: 10px; border: 1px solid #ccc;">AIR PRESSURE (hPa)
                                    <div class="form-group">
                                        <div class="input-group text-center">
                                            <input type="text" class="form-control required" autocomplete="on" placeholder="101" name="lv_v_a_9" value="<?php if(!empty($calibration_info)) {echo $calibration_info->lv_v_a_9;} ?>" required>
                                        </div>
                                    </div>
                                </td>
                            </tr>                           
                        </tbody>
                    </table>
                    
                    <div class="table">
                        <h5><b>PRE-CALIBRATION CHECKS</b></h5>
                        <table class="table" id="serviceFields">
                            <thead>
                                <tr>
                                    <th  class="text-center" style="font-weight:500;"></th>
                                    <th  class="text-center" style="font-weight:500;">Observations(d1(mm))</th>
                                    <th  class="text-center" style="font-weight:500;"></th>
                                    <th  class="text-center" style="font-weight:500;">Observations(d2(mm))</th>
                                    <th  class="text-center" style="font-weight:500;"></th>
                                </tr>
                                <tr>
                                    <th  class="text-center" style="font-weight:500;">SET UP</th>
                                    <th  class="text-center" style="font-weight:500;">BS(mm)</th>
                                    <th  class="text-center" style="font-weight:500;">FS(mm)</th>
                                    <th  class="text-center" style="font-weight:500;">BS(mm)</th>
                                    <th  class="text-center" style="font-weight:500;">FS(mm)</th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr>
                                    <td class="text-center" style="font-weight:500;">1</td>
                                    <td>
                                        <div class="form-group form-group-bottom">
                                            <input class="form-control required" value="<?php if(!empty($calibration_info)) {echo $calibration_info->i_backsight_a;} ?>"  name="i_backsight_a" type="text">
                                        </div>
                                    </td>
                                    <td>
                                        <div class="form-group form-group-bottom">
                                            <input class="form-control required" value="<?php if(!empty($calibration_info)) {echo $calibration_info->i_foresight_b;} ?>"  name="i_foresight_b" type="text">
                                        </div>
                                    </td>
                                    <td>
                                        <div class="form-group form-group-bottom">
                                            <input class="form-control required" value="<?php if(!empty($calibration_info)) {echo $calibration_info->i_backsight_c;} ?>"  name="i_backsight_c" type="text">
                                        </div>
                                    </td>
                                    <td>
                                        <div class="form-group form-group-bottom">
                                            <input class="form-control required" value="<?php if(!empty($calibration_info)) {echo $calibration_info->i_foresight_d;} ?>"  name="i_foresight_d" type="text">
                                        </div>
                                    </td>
                                </tr>
                                <tr>
                                    <td class="text-center" style="font-weight:500;">2</td>
                                    <td>
                                        <div class="form-group form-group-bottom">
                                            <input class="form-control required" value="<?php if(!empty($calibration_info)) {echo $calibration_info->ii_backsight_a;} ?>"  name="ii_backsight_a" type="text">
                                        </div>
                                    </td>
                                    <td>
                                        <div class="form-group form-group-bottom">
                                            <input class="form-control required" value="<?php if(!empty($calibration_info)) {echo $calibration_info->ii_foresight_b;} ?>"  name="ii_foresight_b" type="text">
                                        </div>
                                    </td>
                                    <td>
                                        <div class="form-group form-group-bottom">
                                            <input class="form-control required" value="<?php if(!empty($calibration_info)) {echo $calibration_info->ii_backsight_c;} ?>"  name="ii_backsight_c" type="text">
                                        </div>
                                    </td>
                                    <td>
                                        <div class="form-group form-group-bottom">
                                            <input class="form-control required" value="<?php if(!empty($calibration_info)) {echo $calibration_info->ii_foresight_d;} ?>"  name="ii_foresight_d" type="text">
                                        </div>
                                    </td>
                                </tr>
                                <tr>
                                    <td class="text-center" style="font-weight:500;">3</td>
                                    <td>
                                        <div class="form-group form-group-bottom">
                                            <input class="form-control required" value="<?php if(!empty($calibration_info)) {echo $calibration_info->iii_backsight_a;} ?>"  name="iii_backsight_a" type="text">
                                        </div>
                                    </td>
                                    <td>
                                        <div class="form-group form-group-bottom">
                                            <input class="form-control required" value="<?php if(!empty($calibration_info)) {echo $calibration_info->iii_foresight_b;} ?>"  name="iii_foresight_b" type="text">
                                        </div>
                                    </td>
                                    <td>
                                        <div class="form-group form-group-bottom">
                                            <input class="form-control required" value="<?php if(!empty($calibration_info)) {echo $calibration_info->iii_backsight_c;} ?>"  name="iii_backsight_c" type="text">
                                        </div>
                                    </td>
                                    <td>
                                        <div class="form-group form-group-bottom">
                                            <input class="form-control required" value="<?php if(!empty($calibration_info)) {echo $calibration_info->iii_foresight_d;} ?>"  name="iii_foresight_d" type="text">
                                        </div>
                                    </td>
                                </tr>
                                <tr>
                                    <td class="text-center" style="font-weight:500;">4</td>
                                    <td>
                                        <div class="form-group form-group-bottom">
                                            <input class="form-control required" value="<?php if(!empty($calibration_info)) {echo $calibration_info->iv_backsight_a;} ?>"  name="iv_backsight_a" type="text">
                                        </div>
                                    </td>
                                    <td>
                                        <div class="form-group form-group-bottom">
                                            <input class="form-control required" value="<?php if(!empty($calibration_info)) {echo $calibration_info->iv_foresight_b;} ?>"  name="iv_foresight_b" type="text">
                                        </div>
                                    </td>
                                    <td>
                                        <div class="form-group form-group-bottom">
                                            <input class="form-control required" value="<?php if(!empty($calibration_info)) {echo $calibration_info->iv_backsight_c;} ?>"  name="iv_backsight_c" type="text">
                                        </div>
                                    </td>
                                    <td>
                                        <div class="form-group form-group-bottom">
                                            <input class="form-control required" value="<?php if(!empty($calibration_info)) {echo $calibration_info->iv_foresight_d;} ?>"  name="iv_foresight_d" type="text">
                                        </div>
                                    </td>
                                </tr>
                                <tr>
                                    <td class="text-center" style="font-weight:500;">5</td>
                                    <td>
                                        <div class="form-group form-group-bottom">
                                            <input class="form-control required" value="<?php if(!empty($calibration_info)) {echo $calibration_info->v_backsight_a;} ?>"  name="v_backsight_a" type="text">
                                        </div>
                                    </td>
                                    <td>
                                        <div class="form-group form-group-bottom">
                                            <input class="form-control required" value="<?php if(!empty($calibration_info)) {echo $calibration_info->v_foresight_b;} ?>"  name="v_foresight_b" type="text">
                                        </div>
                                    </td>
                                    <td>
                                        <div class="form-group form-group-bottom">
                                            <input class="form-control required" value="<?php if(!empty($calibration_info)) {echo $calibration_info->v_backsight_c;} ?>"  name="v_backsight_c" type="text">
                                        </div>
                                    </td>
                                    <td>
                                        <div class="form-group form-group-bottom">
                                            <input class="form-control required" value="<?php if(!empty($calibration_info)) {echo $calibration_info->v_foresight_d;} ?>"  name="v_foresight_d" type="text">
                                        </div>
                                    </td>
                                </tr>
                                <tr>
                                    <td class="text-center" style="font-weight:500;">6</td>
                                    <td>
                                        <div class="form-group form-group-bottom">
                                            <input class="form-control required" value="<?php if(!empty($calibration_info)) {echo $calibration_info->vi_backsight_a;} ?>"  name="vi_backsight_a" type="text">
                                        </div>
                                    </td>
                                    <td>
                                        <div class="form-group form-group-bottom">
                                            <input class="form-control required" value="<?php if(!empty($calibration_info)) {echo $calibration_info->vi_foresight_b;} ?>"  name="vi_foresight_b" type="text">
                                        </div>
                                    </td>
                                    <td>
                                        <div class="form-group form-group-bottom">
                                            <input class="form-control required" value="<?php if(!empty($calibration_info)) {echo $calibration_info->vi_backsight_c;} ?>"  name="vi_backsight_c" type="text">
                                        </div>
                                    </td>
                                    <td>
                                        <div class="form-group form-group-bottom">
                                            <input class="form-control required" value="<?php if(!empty($calibration_info)) {echo $calibration_info->vi_foresight_d;} ?>"  name="vi_foresight_d" type="text">
                                        </div>
                                    </td>
                                </tr>
                                <tr>
                                    <td class="text-center" style="font-weight:500;">7</td>
                                    <td>
                                        <div class="form-group form-group-bottom">
                                            <input class="form-control required" value="<?php if(!empty($calibration_info)) {echo $calibration_info->vii_backsight_a;} ?>"  name="vii_backsight_a" type="text">
                                        </div>
                                    </td>
                                    <td>
                                        <div class="form-group form-group-bottom">
                                            <input class="form-control required" value="<?php if(!empty($calibration_info)) {echo $calibration_info->vii_foresight_b;} ?>"  name="vii_foresight_b" type="text">
                                        </div>
                                    </td>
                                    <td>
                                        <div class="form-group form-group-bottom">
                                            <input class="form-control required" value="<?php if(!empty($calibration_info)) {echo $calibration_info->vii_backsight_c;} ?>"  name="vii_backsight_c" type="text">
                                        </div>
                                    </td>
                                    <td>
                                        <div class="form-group form-group-bottom">
                                            <input class="form-control required" value="<?php if(!empty($calibration_info)) {echo $calibration_info->vii_foresight_d;} ?>"  name="vii_foresight_d" type="text">
                                        </div>
                                    </td>
                                </tr>
                                <tr>
                                    <td class="text-center" style="font-weight:500;">8</td>
                                    <td>
                                        <div class="form-group form-group-bottom">
                                            <input class="form-control required" value="<?php if(!empty($calibration_info)) {echo $calibration_info->viii_backsight_a;} ?>"  name="viii_backsight_a" type="text">
                                        </div>
                                    </td>
                                    <td>
                                        <div class="form-group form-group-bottom">
                                            <input class="form-control required" value="<?php if(!empty($calibration_info)) {echo $calibration_info->viii_foresight_b;} ?>"  name="viii_foresight_b" type="text">
                                        </div>
                                    </td>
                                    <td>
                                        <div class="form-group form-group-bottom">
                                            <input class="form-control required" value="<?php if(!empty($calibration_info)) {echo $calibration_info->viii_backsight_c;} ?>"  name="viii_backsight_c" type="text">
                                        </div>
                                    </td>
                                    <td>
                                        <div class="form-group form-group-bottom">
                                            <input class="form-control required" value="<?php if(!empty($calibration_info)) {echo $calibration_info->viii_foresight_d;} ?>"  name="viii_foresight_d" type="text">
                                        </div>
                                    </td>
                                </tr>
                                <tr>
                                    <td class="text-center" style="font-weight:500;">9</td>
                                    <td>
                                        <div class="form-group form-group-bottom">
                                            <input class="form-control required" value="<?php if(!empty($calibration_info)) {echo $calibration_info->ix_backsight_a;} ?>"  name="ix_backsight_a" type="text">
                                        </div>
                                    </td>
                                    <td>
                                        <div class="form-group form-group-bottom">
                                            <input class="form-control required" value="<?php if(!empty($calibration_info)) {echo $calibration_info->ix_foresight_b;} ?>"  name="ix_foresight_b" type="text">
                                        </div>
                                    </td>
                                    <td>
                                        <div class="form-group form-group-bottom">
                                            <input class="form-control required" value="<?php if(!empty($calibration_info)) {echo $calibration_info->ix_backsight_c;} ?>"  name="ix_backsight_c" type="text">
                                        </div>
                                    </td>
                                    <td>
                                        <div class="form-group form-group-bottom">
                                            <input class="form-control required" value="<?php if(!empty($calibration_info)) {echo $calibration_info->ix_foresight_d;} ?>"  name="ix_foresight_d" type="text">
                                        </div>
                                    </td>
                                </tr>
                                <tr>
                                    <td class="text-center" style="font-weight:500;">10</td>
                                    <td>
                                        <div class="form-group form-group-bottom">
                                            <input class="form-control required" value="<?php if(!empty($calibration_info)) {echo $calibration_info->x_backsight_a;} ?>"  name="x_backsight_a" type="text">
                                        </div>
                                    </td>
                                    <td>
                                        <div class="form-group form-group-bottom">
                                            <input class="form-control required" value="<?php if(!empty($calibration_info)) {echo $calibration_info->x_foresight_b;} ?>"  name="x_foresight_b" type="text">
                                        </div>
                                    </td>
                                    <td>
                                        <div class="form-group form-group-bottom">
                                            <input class="form-control required" value="<?php if(!empty($calibration_info)) {echo $calibration_info->x_backsight_c;} ?>"  name="x_backsight_c" type="text">
                                        </div>
                                    </td>
                                    <td>
                                        <div class="form-group form-group-bottom">
                                            <input class="form-control required" value="<?php if(!empty($calibration_info)) {echo $calibration_info->x_foresight_d;} ?>"  name="x_foresight_d" type="text">
                                        </div>
                                    </td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                    <div class="table">
                    <h5><b>POST-CALIBRATION CHECKS</b></h5>
                        <table class="table" id="serviceFields">
                            <thead>
                                <tr>
                                    <th  class="text-center" style="font-weight:500;"></th>
                                    <th  class="text-center" style="font-weight:500;">Observations(d1(mm))</th>
                                    <th  class="text-center" style="font-weight:500;"></th>
                                    <th  class="text-center" style="font-weight:500;">Observations(d2(mm))</th>
                                    <th  class="text-center" style="font-weight:500;"></th>
                                </tr>
                                <tr>
                                    <th  class="text-center" style="font-weight:500;">SET UP</th>
                                    <th  class="text-center" style="font-weight:500;">BS(mm)</th>
                                    <th  class="text-center" style="font-weight:500;">FS(mm)</th>
                                    <th  class="text-center" style="font-weight:500;">BS(mm)</th>
                                    <th  class="text-center" style="font-weight:500;">FS(mm)</th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr>
                                    <td class="text-center" style="font-weight:500;">1</td>
                                    <td>
                                        <div class="form-group form-group-bottom">
                                            <input class="form-control required" value="<?php if(!empty($calibration_info)) {echo $calibration_info->i_backsight_e;} ?>"  name="i_backsight_e" type="text">
                                        </div>
                                    </td>
                                    <td>
                                        <div class="form-group form-group-bottom">
                                            <input class="form-control required" value="<?php if(!empty($calibration_info)) {echo $calibration_info->i_foresight_f;} ?>"  name="i_foresight_f" type="text">
                                        </div>
                                    </td>
                                    <td>
                                        <div class="form-group form-group-bottom">
                                            <input class="form-control required" value="<?php if(!empty($calibration_info)) {echo $calibration_info->i_backsight_g;} ?>"  name="i_backsight_g" type="text">
                                        </div>
                                    </td>
                                    <td>
                                        <div class="form-group form-group-bottom">
                                            <input class="form-control required" value="<?php if(!empty($calibration_info)) {echo $calibration_info->i_foresight_h;} ?>"  name="i_foresight_h" type="text">
                                        </div>
                                    </td>
                                </tr>
                                <tr>
                                    <td class="text-center" style="font-weight:500;">2</td>
                                    <td>
                                        <div class="form-group form-group-bottom">
                                            <input class="form-control required" value="<?php if(!empty($calibration_info)) {echo $calibration_info->ii_backsight_e;} ?>"  name="ii_backsight_e" type="text">
                                        </div>
                                    </td>
                                    <td>
                                        <div class="form-group form-group-bottom">
                                            <input class="form-control required" value="<?php if(!empty($calibration_info)) {echo $calibration_info->ii_foresight_f;} ?>"  name="ii_foresight_f" type="text">
                                        </div>
                                    </td>
                                    <td>
                                        <div class="form-group form-group-bottom">
                                            <input class="form-control required" value="<?php if(!empty($calibration_info)) {echo $calibration_info->ii_backsight_g;} ?>"  name="ii_backsight_g" type="text">
                                        </div>
                                    </td>
                                    <td>
                                        <div class="form-group form-group-bottom">
                                            <input class="form-control required" value="<?php if(!empty($calibration_info)) {echo $calibration_info->ii_foresight_h;} ?>"  name="ii_foresight_h" type="text">
                                        </div>
                                    </td>
                                </tr>
                                <tr>
                                    <td class="text-center" style="font-weight:500;">3</td>
                                    <td>
                                        <div class="form-group form-group-bottom">
                                            <input class="form-control required" value="<?php if(!empty($calibration_info)) {echo $calibration_info->iii_backsight_e;} ?>"  name="iii_backsight_e" type="text">
                                        </div>
                                    </td>
                                    <td>
                                        <div class="form-group form-group-bottom">
                                            <input class="form-control required" value="<?php if(!empty($calibration_info)) {echo $calibration_info->iii_foresight_f;} ?>"  name="iii_foresight_f" type="text">
                                        </div>
                                    </td>
                                    <td>
                                        <div class="form-group form-group-bottom">
                                            <input class="form-control required" value="<?php if(!empty($calibration_info)) {echo $calibration_info->iii_backsight_g;} ?>"  name="iii_backsight_g" type="text">
                                        </div>
                                    </td>
                                    <td>
                                        <div class="form-group form-group-bottom">
                                            <input class="form-control required" value="<?php if(!empty($calibration_info)) {echo $calibration_info->iii_foresight_h;} ?>"  name="iii_foresight_h" type="text">
                                        </div>
                                    </td>
                                </tr>
                                <tr>
                                    <td class="text-center" style="font-weight:500;">4</td>
                                    <td>
                                        <div class="form-group form-group-bottom">
                                            <input class="form-control required" value="<?php if(!empty($calibration_info)) {echo $calibration_info->iv_backsight_e;} ?>"  name="iv_backsight_e" type="text">
                                        </div>
                                    </td>
                                    <td>
                                        <div class="form-group form-group-bottom">
                                            <input class="form-control required" value="<?php if(!empty($calibration_info)) {echo $calibration_info->iv_foresight_f;} ?>"  name="iv_foresight_f" type="text">
                                        </div>
                                    </td>
                                    <td>
                                        <div class="form-group form-group-bottom">
                                            <input class="form-control required" value="<?php if(!empty($calibration_info)) {echo $calibration_info->iv_backsight_g;} ?>"  name="iv_backsight_g" type="text">
                                        </div>
                                    </td>
                                    <td>
                                        <div class="form-group form-group-bottom">
                                            <input class="form-control required" value="<?php if(!empty($calibration_info)) {echo $calibration_info->iv_foresight_h;} ?>"  name="iv_foresight_h" type="text">
                                        </div>
                                    </td>
                                </tr>
                                <tr>
                                    <td class="text-center" style="font-weight:500;">5</td>
                                    <td>
                                        <div class="form-group form-group-bottom">
                                            <input class="form-control required" value="<?php if(!empty($calibration_info)) {echo $calibration_info->v_backsight_e;} ?>"  name="v_backsight_e" type="text">
                                        </div>
                                    </td>
                                    <td>
                                        <div class="form-group form-group-bottom">
                                            <input class="form-control required" value="<?php if(!empty($calibration_info)) {echo $calibration_info->v_foresight_f;} ?>"  name="v_foresight_f" type="text">
                                        </div>
                                    </td>
                                    <td>
                                        <div class="form-group form-group-bottom">
                                            <input class="form-control required" value="<?php if(!empty($calibration_info)) {echo $calibration_info->v_backsight_g;} ?>"  name="v_backsight_g" type="text">
                                        </div>
                                    </td>
                                    <td>
                                        <div class="form-group form-group-bottom">
                                            <input class="form-control required" value="<?php if(!empty($calibration_info)) {echo $calibration_info->v_foresight_h;} ?>"  name="v_foresight_h" type="text">
                                        </div>
                                    </td>
                                </tr>
                                <tr>
                                    <td class="text-center" style="font-weight:500;">6</td>
                                    <td>
                                        <div class="form-group form-group-bottom">
                                            <input class="form-control required" value="<?php if(!empty($calibration_info)) {echo $calibration_info->vi_backsight_e;} ?>"  name="vi_backsight_e" type="text">
                                        </div>
                                    </td>
                                    <td>
                                        <div class="form-group form-group-bottom">
                                            <input class="form-control required" value="<?php if(!empty($calibration_info)) {echo $calibration_info->vi_foresight_f;} ?>"  name="vi_foresight_f" type="text">
                                        </div>
                                    </td>
                                    <td>
                                        <div class="form-group form-group-bottom">
                                            <input class="form-control required" value="<?php if(!empty($calibration_info)) {echo $calibration_info->vi_backsight_g;} ?>"  name="vi_backsight_g" type="text">
                                        </div>
                                    </td>
                                    <td>
                                        <div class="form-group form-group-bottom">
                                            <input class="form-control required" value="<?php if(!empty($calibration_info)) {echo $calibration_info->vi_foresight_h;} ?>"  name="vi_foresight_h" type="text">
                                        </div>
                                    </td>
                                </tr>
                                <tr>
                                    <td class="text-center" style="font-weight:500;">7</td>
                                    <td>
                                        <div class="form-group form-group-bottom">
                                            <input class="form-control required" value="<?php if(!empty($calibration_info)) {echo $calibration_info->vii_backsight_e;} ?>"  name="vii_backsight_e" type="text">
                                        </div>
                                    </td>
                                    <td>
                                        <div class="form-group form-group-bottom">
                                            <input class="form-control required" value="<?php if(!empty($calibration_info)) {echo $calibration_info->vii_foresight_f;} ?>"  name="vii_foresight_f" type="text">
                                        </div>
                                    </td>
                                    <td>
                                        <div class="form-group form-group-bottom">
                                            <input class="form-control required" value="<?php if(!empty($calibration_info)) {echo $calibration_info->vii_backsight_g;} ?>"  name="vii_backsight_g" type="text">
                                        </div>
                                    </td>
                                    <td>
                                        <div class="form-group form-group-bottom">
                                            <input class="form-control required" value="<?php if(!empty($calibration_info)) {echo $calibration_info->vii_foresight_h;} ?>"  name="vii_foresight_h" type="text">
                                        </div>
                                    </td>
                                </tr>
                                <tr>
                                    <td class="text-center" style="font-weight:500;">8</td>
                                    <td>
                                        <div class="form-group form-group-bottom">
                                            <input class="form-control required" value="<?php if(!empty($calibration_info)) {echo $calibration_info->viii_backsight_e;} ?>"  name="viii_backsight_e" type="text">
                                        </div>
                                    </td>
                                    <td>
                                        <div class="form-group form-group-bottom">
                                            <input class="form-control required" value="<?php if(!empty($calibration_info)) {echo $calibration_info->viii_foresight_f;} ?>"  name="viii_foresight_f" type="text">
                                        </div>
                                    </td>
                                    <td>
                                        <div class="form-group form-group-bottom">
                                            <input class="form-control required" value="<?php if(!empty($calibration_info)) {echo $calibration_info->viii_backsight_g;} ?>"  name="viii_backsight_g" type="text">
                                        </div>
                                    </td>
                                    <td>
                                        <div class="form-group form-group-bottom">
                                            <input class="form-control required" value="<?php if(!empty($calibration_info)) {echo $calibration_info->viii_foresight_h;} ?>"  name="viii_foresight_h" type="text">
                                        </div>
                                    </td>
                                </tr>
                                <tr>
                                    <td class="text-center" style="font-weight:500;">9</td>
                                    <td>
                                        <div class="form-group form-group-bottom">
                                            <input class="form-control required" value="<?php if(!empty($calibration_info)) {echo $calibration_info->ix_backsight_e;} ?>"  name="ix_backsight_e" type="text">
                                        </div>
                                    </td>
                                    <td>
                                        <div class="form-group form-group-bottom">
                                            <input class="form-control required" value="<?php if(!empty($calibration_info)) {echo $calibration_info->ix_foresight_f;} ?>"  name="ix_foresight_f" type="text">
                                        </div>
                                    </td>
                                    <td>
                                        <div class="form-group form-group-bottom">
                                            <input class="form-control required" value="<?php if(!empty($calibration_info)) {echo $calibration_info->ix_backsight_g;} ?>"  name="ix_backsight_g" type="text">
                                        </div>
                                    </td>
                                    <td>
                                        <div class="form-group form-group-bottom">
                                            <input class="form-control required" value="<?php if(!empty($calibration_info)) {echo $calibration_info->ix_foresight_h;} ?>"  name="ix_foresight_h" type="text">
                                        </div>
                                    </td>
                                </tr>
                                <tr>
                                    <td class="text-center" style="font-weight:500;">10</td>
                                    <td>
                                        <div class="form-group form-group-bottom">
                                            <input class="form-control required" value="<?php if(!empty($calibration_info)) {echo $calibration_info->x_backsight_e;} ?>"  name="x_backsight_e" type="text">
                                        </div>
                                    </td>
                                    <td>
                                        <div class="form-group form-group-bottom">
                                            <input class="form-control required" value="<?php if(!empty($calibration_info)) {echo $calibration_info->x_foresight_f;} ?>"  name="x_foresight_f" type="text">
                                        </div>
                                    </td>
                                    <td>
                                        <div class="form-group form-group-bottom">
                                            <input class="form-control required" value="<?php if(!empty($calibration_info)) {echo $calibration_info->x_backsight_g;} ?>"  name="x_backsight_g" type="text">
                                        </div>
                                    </td>
                                    <td>
                                        <div class="form-group form-group-bottom">
                                            <input class="form-control required" value="<?php if(!empty($calibration_info)) {echo $calibration_info->x_foresight_h;} ?>"  name="x_foresight_h" type="text">
                                        </div>
                                    </td>
                                </tr>
                            </tbody>
                        </table>
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
