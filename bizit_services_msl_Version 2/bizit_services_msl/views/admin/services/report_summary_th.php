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
                                    <input type="text" class="form-control required" autocomplete="on" placeholder="FOIF" id="_th_v_a_1" name="th_v_a_1" value="<?php if(!empty($calibration_info)) {echo $calibration_info->th_v_a_1;} ?>" required>
                                </div>
                            </div>
                        </td>
                        <td class="align-middle text-left" style="padding: 10px; border: 1px solid #ccc;">INSTRUMENT MODEL
                            <div class="form-group">
                                <div class="input-group text-center">
                                    <input type="text" class="form-control required" autocomplete="on" placeholder="AL132" name="th_v_a_2" value="<?php if(!empty($calibration_info)) {echo $calibration_info->th_v_a_2;} ?>" required>
                                </div>
                            </div>
                        </td>
                        <td class="align-middle text-left" style="padding: 10px; border: 1px solid #ccc;">INSTRUMENT SERIAL NO.
                            <div class="form-group">
                                <div class="input-group text-center">
                                    <input type="text" class="form-control required" autocomplete="on" placeholder="Z6692H99" name="th_v_a_3" value="<?php if(!empty($calibration_info)) {echo $calibration_info->th_v_a_3;} ?>" required>
                                </div>
                            </div>
                        </td>
                        <td class="align-middle text-left" style="padding: 10px; border: 1px solid #ccc;">INSTRUMENT CONDITION
                            <div class="form-group">
                                <div class="input-group text-center">
                                    <input type="text" class="form-control required" autocomplete="on" placeholder="USED" name="th_v_a_4" value="<?php if(!empty($calibration_info)) {echo $calibration_info->th_v_a_4;} ?>" required>
                                </div>
                            </div>
                        </td>
                    </tr>
                    <tr class="form-inline">
                        <td class="align-middle text-left" style="padding: 10px; border: 1px solid #ccc;">BASELINE TEST DISTANCE (M)
                            <div class="form-group">
                                <div class="input-group text-center">
                                    <input type="text" class="form-control required" autocomplete="on" placeholder="20" name="th_v_a_5" value="<?php if(!empty($calibration_info)) {echo $calibration_info->th_v_a_5;} ?>" required>
                                </div>
                            </div>
                        </td>
                        <td class="align-middle text-left" style="padding: 10px; border: 1px solid #ccc;">WEATHER CONDITION
                            <div class="form-group">
                                <div class="input-group text-center">
                                    <input type="text" class="form-control required" autocomplete="on" placeholder="CLOUDY" name="th_v_a_6" value="<?php if(!empty($calibration_info)) {echo $calibration_info->th_v_a_6;} ?>" required>
                                </div>
                            </div>
                        </td>
                        <td class="align-middle text-left" style="padding: 10px; border: 1px solid #ccc;">TEMPERATURE (°C)
                            <div class="form-group">
                                <div class="input-group text-center">
                                    <input type="text" class="form-control required" autocomplete="on" placeholder="32" name="th_v_a_7" value="<?php if(!empty($calibration_info)) {echo $calibration_info->th_v_a_7;} ?>" required>
                                </div>
                            </div>
                        </td>
                        <td class="align-middle text-left" style="padding: 10px; border: 1px solid #ccc;">AIR PRESSURE (hPa)
                            <div class="form-group">
                                <div class="input-group text-center">
                                    <input type="text" class="form-control required" autocomplete="on" placeholder="101" name="th_v_a_8" value="<?php if(!empty($calibration_info)) {echo $calibration_info->th_v_a_8;} ?>" required>
                                </div>
                            </div>
                        </td>
                    </tr>
                    <tr class="form-inline">
                        <td class="align-middle text-left" style="padding: 10px; border: 1px solid #ccc;">MANUFACTURER ACCURACY (00'')
                            <div class="form-group">
                                <div class="input-group text-center">
                                    <input type="text" class="form-control required" autocomplete="on" placeholder="19.994" name="th_v_a_9" value="<?php if(!empty($calibration_info)) {echo $calibration_info->th_v_a_9;} ?>" required>
                                </div>
                            </div>
                        </td>
                        
                        <td class="align-middle text-left" style="padding: 10px; border: 1px solid #ccc;">TARGET PRISM CONSTANT (MM)
                            <div class="form-group">
                                <div class="input-group text-center">
                                    <input type="text" class="form-control required" autocomplete="on" placeholder="15" name="th_v_a_10" value="<?php if(!empty($calibration_info)) {echo $calibration_info->th_v_a_10;} ?>" required>
                                </div>
                            </div>
                        </td>
                        <td class="align-middle text-left" style="padding: 10px; border: 1px solid #ccc;">INSTRUMENT PRISM CONSTANT (MM)
                            <div class="form-group">
                                <div class="input-group text-center">
                                    <input type="text" class="form-control required" autocomplete="on" placeholder="2.2" name="th_v_a_11" value="<?php if(!empty($calibration_info)) {echo $calibration_info->th_v_a_11;} ?>" required>
                                </div>
                            </div>
                        </td>
                        <td class="align-middle text-left" style="padding: 10px; border: 1px solid #ccc;">INSTRUMENT HEIGHT (M)
                            <div class="form-group">
                                <div class="input-group text-center">
                                    <input type="text" class="form-control required" autocomplete="on" placeholder="2.2" name="th_v_a_12" value="<?php if(!empty($calibration_info)) {echo $calibration_info->th_v_a_12;} ?>" required>
                                </div>
                            </div>
                        </td>
                    </tr>
                    <tr class="form-inline">                        
                        <td class="align-middle text-left" style="padding: 10px; border: 1px solid #ccc;">TARGET MAKE
                            <div class="form-group">
                                <div class="input-group text-center">
                                    <input type="text" class="form-control required" autocomplete="on" placeholder="2.2" name="th_v_a_13" value="<?php if(!empty($calibration_info)) {echo $calibration_info->th_v_a_13;} ?>" required>
                                </div>
                            </div>
                        </td><td class="align-middle text-left" style="padding: 10px; border: 1px solid #ccc;">TARGET MODEL
                            <div class="form-group">
                                <div class="input-group text-center">
                                    <input type="text" class="form-control required" autocomplete="on" placeholder="2.2" name="th_v_a_14" value="<?php if(!empty($calibration_info)) {echo $calibration_info->th_v_a_14;} ?>" required>
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

          <div class="form-section">
            <button><b>PRE-CALIBRATION INDEX ERROR CHECKS</b></button><br><br>
            <b>HORIZONTAL CIRCLE INDEX ERROR</b>
            <table class="table" id="serviceFields">
              <thead>
                <tr>
                  <th  class="text-center" style="font-weight:500;">FACE</th>
                  <th  class="text-center" style="font-weight:500;">START A-B</th>
                  <th  class="text-center" style="font-weight:500;">START A-C</th>
                </tr>
              </thead>
              <tbody>
                <tr  class="form-inline">
                  <td class="text-center" style="font-weight:500;">I</td>
                  <td>
                    <div class="form-group col-sm-4">
                      <div class="input-group">
                        <div class="input-group-addon">Deg</div>
                        <input type="text"  class="form-control required" autocomplete="off" id="exampleInputAmount" placeholder="Degrees" name='th_h_a[]' readonly value="0">
                      </div>
                    </div>
                    <div class="form-group col-sm-4">
                      <div class="input-group">
                        <div class="input-group-addon">Min</div>
                        <input type="text"  class="form-control required" autocomplete="off" id="exampleInputAmount" placeholder="Minutes" name='th_h_a[]' readonly value="0">
                      </div>
                    </div>
                    <div class="form-group col-sm-4">
                      <div class="input-group">
                        <div class="input-group-addon">Sec</div>
                        <input type="text"  class="form-control required" autocomplete="off" id="exampleInputAmount" placeholder="Seconds" name='th_h_a[]' readonly value="0">
                      </div>
                    </div>
                  </td>
                  <td>
                    <div class="form-group col-sm-4">
                      <div class="input-group">
                        <div class="input-group-addon">Deg</div>
                        <input type="text"  class="form-control required" autocomplete="off" id="exampleInputAmount" placeholder="Degrees" name='th_h_b[]' readonly value="0">
                      </div>
                    </div>
                    <div class="form-group col-sm-4">
                      <div class="input-group">
                        <div class="input-group-addon">Min</div>
                        <input type="text"  class="form-control required" autocomplete="off" id="exampleInputAmount" placeholder="Minutes" name='th_h_b[]' readonly value="0">
                      </div>
                    </div>
                    <div class="form-group col-sm-4">
                      <div class="input-group">
                        <div class="input-group-addon">Sec</div>
                        <input type="text"  class="form-control required" autocomplete="off" id="exampleInputAmount" placeholder="Seconds" name='th_h_b[]' readonly value="0">
                      </div>
                    </div>
                  </td>
                </tr>
                <tr  class="form-inline">
                  <td class="text-center" style="font-weight:500;">II</td>
                  <td>
                    <div class="form-group col-sm-4">
                      <div class="input-group">
                        <div class="input-group-addon">Deg</div>
                        <input type="text"  class="form-control required" autocomplete="off" id="exampleInputAmount" placeholder="Degrees" name='thh_h_a[]' value="<?php if(!empty($calibration_info->thh_h_a)){echo dec2dms($calibration_info->thh_h_a)['deg']; } ?>">
                      </div>
                    </div>
                    <div class="form-group col-sm-4">
                      <div class="input-group">
                        <div class="input-group-addon">Min</div>
                        <input type="text"  class="form-control required" autocomplete="off" id="exampleInputAmount" placeholder="Minutes" name='thh_h_a[]' value="<?php if(!empty($calibration_info->thh_h_a)){echo dec2dms($calibration_info->thh_h_a)['min']; } ?>">
                      </div>
                    </div>
                    <div class="form-group col-sm-4">
                      <div class="input-group">
                        <div class="input-group-addon">Sec</div>
                        <input type="text"  class="form-control required" autocomplete="off" id="exampleInputAmount" placeholder="Seconds" name='thh_h_a[]' value="<?php if(!empty($calibration_info->thh_h_a)){echo round(dec2dms($calibration_info->thh_h_a)['sec']); } ?>">
                      </div>
                    </div>
                  </td>
                  <td>
                    <div class="form-group col-sm-4">
                      <div class="input-group">
                        <div class="input-group-addon">Deg</div>
                        <input type="text"  class="form-control required" autocomplete="off" id="exampleInputAmount" placeholder="Degrees" name='thh_h_b[]' value="<?php if(!empty($calibration_info->thh_h_b)){echo dec2dms($calibration_info->thh_h_b)['deg']; } ?>">
                      </div>
                    </div>
                    <div class="form-group col-sm-4">
                      <div class="input-group">
                        <div class="input-group-addon">Min</div>
                        <input type="text"  class="form-control required" autocomplete="off" id="exampleInputAmount" placeholder="Minutes" name='thh_h_b[]' value="<?php if(!empty($calibration_info->thh_h_b)){echo dec2dms($calibration_info->thh_h_b)['min']; } ?>">
                      </div>
                    </div>
                    <div class="form-group col-sm-4">
                      <div class="input-group">
                        <div class="input-group-addon">Sec</div>
                        <input type="text"  class="form-control required" autocomplete="off" id="exampleInputAmount" placeholder="Seconds" name='thh_h_b[]' value="<?php if(!empty($calibration_info->thh_h_b)){echo round(dec2dms($calibration_info->thh_h_b)['sec']); } ?>">
                      </div>
                    </div>
                  </td>
                </tr>
                
              </tbody>
            </table>
            
            <b>VERTICAL CIRCLE INDEX ERROR</b>
            <table class="table" id="serviceFields">
              <thead>
                <tr>
                  <th  class="text-center" style="font-weight:500;">FACE</th>
                  <th  class="text-center" style="font-weight:500;">START A-B</th>
                  <th  class="text-center" style="font-weight:500;">START A-C</th>
                </tr>
              </thead>
              <tbody>
                <tr  class="form-inline">
                  <td class="text-center" style="font-weight:500;">I</td>
                  <td>
                    <div class="form-group col-sm-4">
                      <div class="input-group">
                        <div class="input-group-addon">Deg</div>
                        <input type="text"  class="form-control required" autocomplete="off" id="exampleInputAmount" placeholder="Degrees" name='th_v_a[]' readonly value="90">
                      </div>
                    </div>
                    <div class="form-group col-sm-4">
                      <div class="input-group">
                        <div class="input-group-addon">Min</div>
                        <input type="text"  class="form-control required" autocomplete="off" id="exampleInputAmount" placeholder="Minutes" name='th_v_a[]' readonly value="0">
                      </div>
                    </div>
                    <div class="form-group col-sm-4">
                      <div class="input-group">
                        <div class="input-group-addon">Sec</div>
                        <input type="text"  class="form-control required" autocomplete="off" id="exampleInputAmount" placeholder="Seconds" name='th_v_a[]' readonly value="0">
                      </div>
                    </div>
                  </td>
                  <td>
                    <div class="form-group col-sm-4">
                      <div class="input-group">
                        <div class="input-group-addon">Deg</div>
                        <input type="text"  class="form-control required" autocomplete="off" id="exampleInputAmount" placeholder="Degrees" name='thh_v_a[]' value="<?php if(!empty($calibration_info->thh_v_a)){echo dec2dms($calibration_info->thh_v_a)['deg']; } ?>">
                      </div>
                    </div>
                    <div class="form-group col-sm-4">
                      <div class="input-group">
                        <div class="input-group-addon">Min</div>
                        <input type="text"  class="form-control required" autocomplete="off" id="exampleInputAmount" placeholder="Minutes" name='thh_v_a[]' value="<?php if(!empty($calibration_info->thh_v_a)){echo dec2dms($calibration_info->thh_v_a)['min']; } ?>">
                      </div>
                    </div>
                    <div class="form-group col-sm-4">
                      <div class="input-group">
                        <div class="input-group-addon">Sec</div>
                        <input type="text"  class="form-control required" autocomplete="off" id="exampleInputAmount" placeholder="Seconds" name='thh_v_a[]' value="<?php if(!empty($calibration_info->thh_v_a)){echo round(dec2dms($calibration_info->thh_v_a)['sec']); } ?>">
                      </div>
                    </div>
                  </td>
                </tr>
                <tr  class="form-inline">
                  <td class="text-center" style="font-weight:500;">II</td>
                  <td>
                    <div class="form-group col-sm-4">
                      <div class="input-group">
                        <div class="input-group-addon">Deg</div>
                        <input type="text"  class="form-control required" autocomplete="off" id="exampleInputAmount" placeholder="Degrees" name='thh_v_b[]' value="<?php if(!empty($calibration_info->thh_v_b)){echo dec2dms($calibration_info->thh_v_b)['deg']; } ?>">
                      </div>
                    </div>
                    <div class="form-group col-sm-4">
                      <div class="input-group">
                        <div class="input-group-addon">Min</div>
                        <input type="text"  class="form-control required" autocomplete="off" id="exampleInputAmount" placeholder="Minutes" name='thh_v_b[]' value="<?php if(!empty($calibration_info->thh_v_b)){echo dec2dms($calibration_info->thh_v_b)['min']; } ?>">
                      </div>
                    </div>
                    <div class="form-group col-sm-4">
                      <div class="input-group">
                        <div class="input-group-addon">Sec</div>
                        <input type="text"  class="form-control required" autocomplete="off" id="exampleInputAmount" placeholder="Seconds" name='thh_v_b[]' value="<?php if(!empty($calibration_info->thh_v_b)){echo round(dec2dms($calibration_info->thh_v_b)['sec']); } ?>">
                      </div>
                    </div>
                  </td>
                  <td>
                    <div class="form-group col-sm-4">
                      <div class="input-group">
                        <div class="input-group-addon">Deg</div>
                        <input type="text"  class="form-control required" autocomplete="off" id="exampleInputAmount" placeholder="Degrees" name='thh_v_c[]' value="<?php if(!empty($calibration_info->thh_v_c)){echo dec2dms($calibration_info->thh_v_c)['deg']; } ?>">
                      </div>
                    </div>
                    <div class="form-group col-sm-4">
                      <div class="input-group">
                        <div class="input-group-addon">Min</div>
                        <input type="text"  class="form-control required" autocomplete="off" id="exampleInputAmount" placeholder="Minutes" name='thh_v_c[]' value="<?php if(!empty($calibration_info->thh_v_c)){echo dec2dms($calibration_info->thh_v_c)['min']; } ?>">
                      </div>
                    </div>
                    <div class="form-group col-sm-4">
                      <div class="input-group">
                        <div class="input-group-addon">Sec</div>
                        <input type="text"  class="form-control required" autocomplete="off" id="exampleInputAmount" placeholder="Seconds" name='thh_v_c[]' value="<?php if(!empty($calibration_info->thh_v_c)){echo round(dec2dms($calibration_info->thh_v_c)['sec']); } ?>">
                      </div>
                    </div>
                  </td>
                </tr>
                
              </tbody>
            </table>
            <button type="button" class="btn btn-secondary prev-step">Previous</button>
            <button type="button" class="btn btn-primary next-step">Next</button>         
          </div>

          

          <div class="form-section"> 
            <button><b>POST-CALIBRATION INDEX ERROR CHECKS</b></button><br><br>       
            <b>HORIZONTAL CIRCLE INDEX ERROR</b>
            <table class="table" id="serviceFields">
              <thead>
              
                <tr>
                  <th  class="text-center" style="font-weight:500;">FACE</th>
                  <th  class="text-center" style="font-weight:500;">START A-B</th>
                  <th  class="text-center" style="font-weight:500;">START A-C</th>
                </tr>
              </thead>
              <tbody>
                <tr  class="form-inline">
                  <td class="text-center" style="font-weight:500;">I</td>
                  <td>
                    <div class="form-group col-sm-4">
                      <div class="input-group">
                        <div class="input-group-addon">Deg</div>
                        <input type="text"  class="form-control required" autocomplete="off" id="exampleInputAmount" placeholder="Degrees" name='th_h_a1[]' readonly value="0">
                      </div>
                    </div>
                    <div class="form-group col-sm-4">
                      <div class="input-group">
                        <div class="input-group-addon">Min</div>
                        <input type="text"  class="form-control required" autocomplete="off" id="exampleInputAmount" placeholder="Minutes" name='th_h_a1[]' readonly value="0">
                      </div>
                    </div>
                    <div class="form-group col-sm-4">
                      <div class="input-group">
                        <div class="input-group-addon">Sec</div>
                        <input type="text"  class="form-control required" autocomplete="off" id="exampleInputAmount" placeholder="Seconds" name='th_h_a1[]' readonly value="0">
                      </div>
                    </div>
                  </td>
                  <td>
                    <div class="form-group col-sm-4">
                      <div class="input-group">
                        <div class="input-group-addon">Deg</div>
                        <input type="text"  class="form-control required" autocomplete="off" id="exampleInputAmount" placeholder="Degrees" name='th_h_b1[]' readonly value="0">
                      </div>
                    </div>
                    <div class="form-group col-sm-4">
                      <div class="input-group">
                        <div class="input-group-addon">Min</div>
                        <input type="text"  class="form-control required" autocomplete="off" id="exampleInputAmount" placeholder="Minutes" name='th_h_b1[]' readonly value="0">
                      </div>
                    </div>
                    <div class="form-group col-sm-4">
                      <div class="input-group">
                        <div class="input-group-addon">Sec</div>
                        <input type="text"  class="form-control required" autocomplete="off" id="exampleInputAmount" placeholder="Seconds" name='th_h_b1[]' readonly value="0">
                      </div>
                    </div>
                  </td>
                </tr>
                <tr  class="form-inline">
                  <td class="text-center" style="font-weight:500;">II</td>
                  <td>
                    <div class="form-group col-sm-4">
                      <div class="input-group">
                        <div class="input-group-addon">Deg</div>
                        <input type="text"  class="form-control required" autocomplete="off" id="exampleInputAmount" placeholder="Degrees" name='thh_h_a1[]' value="<?php if(!empty($calibration_info->thh_h_a1)){echo dec2dms($calibration_info->thh_h_a1)['deg']; } ?>">
                      </div>
                    </div>
                    <div class="form-group col-sm-4">
                      <div class="input-group">
                        <div class="input-group-addon">Min</div>
                        <input type="text"  class="form-control required" autocomplete="off" id="exampleInputAmount" placeholder="Minutes" name='thh_h_a1[]' value="<?php if(!empty($calibration_info->thh_h_a1)){echo dec2dms($calibration_info->thh_h_a1)['min']; } ?>">
                      </div>
                    </div>
                    <div class="form-group col-sm-4">
                      <div class="input-group">
                        <div class="input-group-addon">Sec</div>
                        <input type="text"  class="form-control required" autocomplete="off" id="exampleInputAmount" placeholder="Seconds" name='thh_h_a1[]' value="<?php if(!empty($calibration_info->thh_h_a1)){echo round(dec2dms($calibration_info->thh_h_a1)['sec']); } ?>">
                      </div>
                    </div>
                  </td>
                  <td>
                    <div class="form-group col-sm-4">
                      <div class="input-group">
                        <div class="input-group-addon">Deg</div>
                        <input type="text"  class="form-control required" autocomplete="off" id="exampleInputAmount" placeholder="Degrees" name='thh_h_b1[]' value="<?php if(!empty($calibration_info->thh_h_b1)){echo dec2dms($calibration_info->thh_h_b1)['deg']; } ?>">
                      </div>
                    </div>
                    <div class="form-group col-sm-4">
                      <div class="input-group">
                        <div class="input-group-addon">Min</div>
                        <input type="text"  class="form-control required" autocomplete="off" id="exampleInputAmount" placeholder="Minutes" name='thh_h_b1[]' value="<?php if(!empty($calibration_info->thh_h_b1)){echo dec2dms($calibration_info->thh_h_b1)['min']; } ?>">
                      </div>
                    </div>
                    <div class="form-group col-sm-4">
                      <div class="input-group">
                        <div class="input-group-addon">Sec</div>
                        <input type="text"  class="form-control required" autocomplete="off" id="exampleInputAmount" placeholder="Seconds" name='thh_h_b1[]' value="<?php if(!empty($calibration_info->thh_h_b1)){echo round(dec2dms($calibration_info->thh_h_b1)['sec']); } ?>">
                      </div>
                    </div>
                  </td>
                </tr>
                
              </tbody>
            </table>
            <b>VERTICAL CIRCLE INDEX ERROR</b>
            <table class="table" id="serviceFields">
              <thead>
                <tr>
                  <th  class="text-center" style="font-weight:500;">FACE</th>
                  <th  class="text-center" style="font-weight:500;">START A-B</th>
                  <th  class="text-center" style="font-weight:500;">START A-C</th>
                </tr>
              </thead>
              <tbody>
                <tr  class="form-inline">
                  <td class="text-center" style="font-weight:500;">I</td>
                  <td>
                    <div class="form-group col-sm-4">
                      <div class="input-group">
                        <div class="input-group-addon">Deg</div>
                        <input type="text"  class="form-control required" autocomplete="off" id="exampleInputAmount" placeholder="Degrees" name='th_v_a1[]' readonly value="90">
                      </div>
                    </div>
                    <div class="form-group col-sm-4">
                      <div class="input-group">
                        <div class="input-group-addon">Min</div>
                        <input type="text"  class="form-control required" autocomplete="off" id="exampleInputAmount" placeholder="Minutes" name='th_v_a1[]' readonly value="0">
                      </div>
                    </div>
                    <div class="form-group col-sm-4">
                      <div class="input-group">
                        <div class="input-group-addon">Sec</div>
                        <input type="text"  class="form-control required" autocomplete="off" id="exampleInputAmount" placeholder="Seconds" name='th_v_a1[]' readonly value="0">
                      </div>
                    </div>
                  </td>
                  <td>
                    <div class="form-group col-sm-4">
                      <div class="input-group">
                        <div class="input-group-addon">Deg</div>
                        <input type="text"  class="form-control required" autocomplete="off" id="exampleInputAmount" placeholder="Degrees" name='thh_v_a1[]' value="<?php if(!empty($calibration_info->thh_v_a1)){echo dec2dms($calibration_info->thh_v_a1)['deg']; } ?>">
                      </div>
                    </div>
                    <div class="form-group col-sm-4">
                      <div class="input-group">
                        <div class="input-group-addon">Min</div>
                        <input type="text"  class="form-control required" autocomplete="off" id="exampleInputAmount" placeholder="Minutes" name='thh_v_a1[]' value="<?php if(!empty($calibration_info->thh_v_a1)){echo dec2dms($calibration_info->thh_v_a1)['min']; } ?>">
                      </div>
                    </div>
                    <div class="form-group col-sm-4">
                      <div class="input-group">
                        <div class="input-group-addon">Sec</div>
                        <input type="text"  class="form-control required" autocomplete="off" id="exampleInputAmount" placeholder="Seconds" name='thh_v_a1[]' value="<?php if(!empty($calibration_info->thh_v_a1)){echo round(dec2dms($calibration_info->thh_v_a1)['sec']); } ?>">
                      </div>
                    </div>
                  </td>
                </tr>
                <tr  class="form-inline">
                  <td class="text-center" style="font-weight:500;">II</td>
                  <td>
                    <div class="form-group col-sm-4">
                      <div class="input-group">
                        <div class="input-group-addon">Deg</div>
                        <input type="text"  class="form-control required" autocomplete="off" id="exampleInputAmount" placeholder="Degrees" name='thh_v_b1[]' value="<?php if(!empty($calibration_info->thh_v_b1)){echo dec2dms($calibration_info->thh_v_b1)['deg']; } ?>">
                      </div>
                    </div>
                    <div class="form-group col-sm-4">
                      <div class="input-group">
                        <div class="input-group-addon">Min</div>
                        <input type="text"  class="form-control required" autocomplete="off" id="exampleInputAmount" placeholder="Minutes" name='thh_v_b1[]' value="<?php if(!empty($calibration_info->thh_v_b1)){echo dec2dms($calibration_info->thh_v_b1)['min']; } ?>">
                      </div>
                    </div>
                    <div class="form-group col-sm-4">
                      <div class="input-group">
                        <div class="input-group-addon">Sec</div>
                        <input type="text"  class="form-control required" autocomplete="off" id="exampleInputAmount" placeholder="Seconds" name='thh_v_b1[]' value="<?php if(!empty($calibration_info->thh_v_b1)){echo round(dec2dms($calibration_info->thh_v_b1)['sec']); } ?>">
                      </div>
                    </div>
                  </td>
                  <td>
                    <div class="form-group col-sm-4">
                      <div class="input-group">
                        <div class="input-group-addon">Deg</div>
                        <input type="text"  class="form-control required" autocomplete="off" id="exampleInputAmount" placeholder="Degrees" name='thh_v_c1[]' value="<?php if(!empty($calibration_info->thh_v_c1)){echo dec2dms($calibration_info->thh_v_c1)['deg']; } ?>">
                      </div>
                    </div>
                    <div class="form-group col-sm-4">
                      <div class="input-group">
                        <div class="input-group-addon">Min</div>
                        <input type="text"  class="form-control required" autocomplete="off" id="exampleInputAmount" placeholder="Minutes" name='thh_v_c1[]' value="<?php if(!empty($calibration_info->thh_v_c1)){echo dec2dms($calibration_info->thh_v_c1)['min']; } ?>">
                      </div>
                    </div>
                    <div class="form-group col-sm-4">
                      <div class="input-group">
                        <div class="input-group-addon">Sec</div>
                        <input type="text"  class="form-control required" autocomplete="off" id="exampleInputAmount" placeholder="Seconds" name='thh_v_c1[]' value="<?php if(!empty($calibration_info->thh_v_c1)){echo round(dec2dms($calibration_info->thh_v_c1)['sec']); } ?>">
                      </div>
                    </div>
                  </td>
                </tr>
                
              </tbody>
            </table>
            <button type="button" class="btn btn-secondary prev-step">Previous</button>
            <button type="button" class="btn btn-primary hide" disabled>CLICK GENERATE REPORT</button>         
          </div>

          
          
        </div>

          <script src="https://code.jquery.com/jquery-3.5.1.min.js"></script>
            <script>
                $(document).ready(function() {
                    var $sections = $('.form-section');

                    function navigateTo(index) {
                        $sections
                            .removeClass('current')
                            .eq(index)
                            .addClass('current');
                        
                        $('.prev-step').toggle(index > 0);
                        var atTheEnd = index >= $sections.length - 1;
                        $('.next-step').toggle(!atTheEnd);
                        $('button[type=submit]').toggle(atTheEnd);
                    }

                    function curIndex() {
                        return $sections.index($sections.filter('.current'));
                    }

                    $('.next-step').click(function() {
                        navigateTo(curIndex() + 1);
                    });

                    $('.prev-step').click(function() {
                        navigateTo(curIndex() - 1);
                    });

                    navigateTo(0);
                });
            </script>

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
