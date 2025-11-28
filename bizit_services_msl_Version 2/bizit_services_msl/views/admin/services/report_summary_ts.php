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
                        <td class="align-middle text-left" style="padding: 10px; border: 1px solid #ccc;">INSTRUMENT MAKE<br>
                            <div class="form-group">
                                <div class="input-group">
                                    <input type="text" class="form-control required" autocomplete="on" placeholder="FOIF" id="_t_v_a_1" name="t_v_a_1" value="<?php if(!empty($calibration_info)) {echo $calibration_info->t_v_a_1;} ?>" required>
                                </div>
                            </div>
                        </td>
                        <td class="align-middle text-left" style="padding: 10px; border: 1px solid #ccc;">INSTRUMENT MODEL
                            <div class="form-group">
                                <div class="input-group text-center">
                                    <input type="text" class="form-control required" autocomplete="on" placeholder="AL132" name="t_v_a_2" value="<?php if(!empty($calibration_info)) {echo $calibration_info->t_v_a_2;} ?>" required>
                                </div>
                            </div>
                        </td>
                        <td class="align-middle text-left" style="padding: 10px; border: 1px solid #ccc;">INSTRUMENT SERIAL NO.
                            <div class="form-group">
                                <div class="input-group text-center">
                                    <input type="text" class="form-control required" autocomplete="on" placeholder="Z6692H99" name="t_v_a_3" value="<?php if(!empty($calibration_info)) {echo $calibration_info->t_v_a_3;} ?>" required>
                                </div>
                            </div>
                        </td>
                        <td class="align-middle text-left" style="padding: 10px; border: 1px solid #ccc;">INSTRUMENT CONDITION
                            <div class="form-group">
                                <div class="input-group text-center">
                                    <input type="text" class="form-control required" autocomplete="on" placeholder="USED" name="t_v_a_4" value="<?php if(!empty($calibration_info)) {echo $calibration_info->t_v_a_4;} ?>" required>
                                </div>
                            </div>
                        </td>
                    </tr>
                    <tr class="form-inline">
                        <td class="align-middle text-left" style="padding: 10px; border: 1px solid #ccc;">BASELINE TEST DISTANCE (M)
                            <div class="form-group">
                                <div class="input-group text-center">
                                    <input type="text" class="form-control required" autocomplete="on" placeholder="20" name="t_v_a_5" value="<?php if(!empty($calibration_info)) {echo $calibration_info->t_v_a_5;} ?>" required>
                                </div>
                            </div>
                        </td>
                        <td class="align-middle text-left" style="padding: 10px; border: 1px solid #ccc;">WEATHER CONDITION
                            <div class="form-group">
                                <div class="input-group text-center">
                                    <input type="text" class="form-control required" autocomplete="on" placeholder="CLOUDY" name="t_v_a_6" value="<?php if(!empty($calibration_info)) {echo $calibration_info->t_v_a_6;} ?>" required>
                                </div>
                            </div>
                        </td>
                        <td class="align-middle text-left" style="padding: 10px; border: 1px solid #ccc;">TEMPERATURE (°C)
                            <div class="form-group">
                                <div class="input-group text-center">
                                    <input type="text" class="form-control required" autocomplete="on" placeholder="32" name="t_v_a_7" value="<?php if(!empty($calibration_info)) {echo $calibration_info->t_v_a_7;} ?>" required>
                                </div>
                            </div>
                        </td>
                        <td class="align-middle text-left" style="padding: 10px; border: 1px solid #ccc;">AIR PRESSURE (hPa)
                            <div class="form-group">
                                <div class="input-group text-center">
                                    <input type="text" class="form-control required" autocomplete="on" placeholder="101" name="t_v_a_8" value="<?php if(!empty($calibration_info)) {echo $calibration_info->t_v_a_8;} ?>" required>
                                </div>
                            </div>
                        </td>
                    </tr>
                    <tr class="form-inline">
                        <td class="align-middle text-left" style="padding: 10px; border: 1px solid #ccc;">MANUFACTURER ANGLE ACCURACY (00'')
                            <div class="form-group">
                                <div class="input-group text-center">
                                    <input type="text" class="form-control required" autocomplete="on" placeholder="19.994" name="t_v_a_9" value="<?php if(!empty($calibration_info)) {echo $calibration_info->t_v_a_9;} ?>" required>
                                </div>
                            </div>
                        </td>
                        <td class="align-middle text-left" style="padding: 10px; border: 1px solid #ccc;">MANUFACTURER EDM ACCURACY (MM)
                            <div class="form-group">
                                <div class="input-group text-center">
                                    <input type="text" class="form-control required" autocomplete="on" placeholder="0.028" name="t_v_a_10" value="<?php if(!empty($calibration_info)) {echo $calibration_info->t_v_a_10;} ?>" required>
                                </div>
                            </div>
                        </td>
                        <td class="align-middle text-left" style="padding: 10px; border: 1px solid #ccc;">TARGET PRISM CONSTANT (MM)
                            <div class="form-group">
                                <div class="input-group text-center">
                                    <input type="text" class="form-control required" autocomplete="on" placeholder="15" name="t_v_a_11" value="<?php if(!empty($calibration_info)) {echo $calibration_info->t_v_a_11;} ?>" required>
                                </div>
                            </div>
                        </td>
                        <td colspan="2" class="align-middle text-left" style="padding: 10px; border: 1px solid #ccc;">TARGET PRISM POLE HEIGHT (M)
                            <div class="form-group">
                                <div class="input-group text-center">
                                    <input type="text" class="form-control required" autocomplete="on" placeholder="25" name="t_v_a_12" value="<?php if(!empty($calibration_info)) {echo $calibration_info->t_v_a_12;} ?>" required>
                                </div>
                            </div>
                        </td>
                    </tr>
                    <tr class="form-inline">
                        <td class="align-middle text-left" style="padding: 10px; border: 1px solid #ccc;">INSTRUMENT PRISM CONSTANT (MM)
                            <div class="form-group">
                                <div class="input-group text-center">
                                    <input type="text" class="form-control required" autocomplete="on" placeholder="2.2" name="t_v_a_13" value="<?php if(!empty($calibration_info)) {echo $calibration_info->t_v_a_13;} ?>" required>
                                </div>
                            </div>
                        </td>
                        <td class="align-middle text-left" style="padding: 10px; border: 1px solid #ccc;">INSTRUMENT HEIGHT (M)
                            <div class="form-group">
                                <div class="input-group text-center">
                                    <input type="text" class="form-control required" autocomplete="on" placeholder="2.2" name="t_v_a_14" value="<?php if(!empty($calibration_info)) {echo $calibration_info->t_v_a_14;} ?>" required>
                                </div>
                            </div>
                        </td>
                        <td class="align-middle text-left" style="padding: 10px; border: 1px solid #ccc;">TARGET MAKE
                            <div class="form-group">
                                <div class="input-group text-center">
                                    <input type="text" class="form-control required" autocomplete="on" placeholder="2.2" name="t_v_a_15" value="<?php if(!empty($calibration_info)) {echo $calibration_info->t_v_a_15;} ?>" required>
                                </div>
                            </div>
                        </td><td class="align-middle text-left" style="padding: 10px; border: 1px solid #ccc;">TARGET MODEL
                            <div class="form-group">
                                <div class="input-group text-center">
                                    <input type="text" class="form-control required" autocomplete="on" placeholder="2.2" name="t_v_a_16" value="<?php if(!empty($calibration_info)) {echo $calibration_info->t_v_a_16;} ?>" required>
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
            <b>HORIZONTAL CIRCLE INDEX ERROR</b>
            <table class="table" id="serviceFields">
              <thead>
                <tr>
                  <th  class="text-center" style="font-weight:500;">FACE</th>
                  <th  class="text-center" style="font-weight:500;">START A</th>
                  <th  class="text-center" style="font-weight:500;">END B</th>
                </tr>
              </thead>
              <tbody>
                <tr  class="form-inline">
                  <td class="text-center" style="font-weight:500;">I</td>
                  <td>
                    <div class="form-group col-sm-4">
                      <div class="input-group">
                        <div class="input-group-addon">Deg</div>
                        <input type="text"  class="form-control required" autocomplete="off" id="exampleInputAmount" placeholder="Degrees" name='i_h_a[]' readonly value="0">
                      </div>
                    </div>
                    <div class="form-group col-sm-4">
                      <div class="input-group">
                        <div class="input-group-addon">Min</div>
                        <input type="text"  class="form-control required" autocomplete="off" id="exampleInputAmount" placeholder="Minutes" name='i_h_a[]' readonly value="0">
                      </div>
                    </div>
                    <div class="form-group col-sm-4">
                      <div class="input-group">
                        <div class="input-group-addon">Sec</div>
                        <input type="text"  class="form-control required" autocomplete="off" id="exampleInputAmount" placeholder="Seconds" name='i_h_a[]' readonly value="0">
                      </div>
                    </div>
                  </td>
                  <td>
                    <div class="form-group col-sm-4">
                      <div class="input-group">
                        <div class="input-group-addon">Deg</div>
                        <input type="text"  class="form-control required" autocomplete="off" id="exampleInputAmount" placeholder="Degrees" name='i_h_b[]' readonly value="0">
                      </div>
                    </div>
                    <div class="form-group col-sm-4">
                      <div class="input-group">
                        <div class="input-group-addon">Min</div>
                        <input type="text"  class="form-control required" autocomplete="off" id="exampleInputAmount" placeholder="Minutes" name='i_h_b[]' readonly value="0">
                      </div>
                    </div>
                    <div class="form-group col-sm-4">
                      <div class="input-group">
                        <div class="input-group-addon">Sec</div>
                        <input type="text"  class="form-control required" autocomplete="off" id="exampleInputAmount" placeholder="Seconds" name='i_h_b[]' readonly value="0">
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
                        <input type="text"  class="form-control required" autocomplete="off" id="exampleInputAmount" placeholder="Degrees" name='ii_h_a[]' value="<?php if(!empty($calibration_info->ii_h_a)){echo dec2dms($calibration_info->ii_h_a)['deg']; } ?>">
                      </div>
                    </div>
                    <div class="form-group col-sm-4">
                      <div class="input-group">
                        <div class="input-group-addon">Min</div>
                        <input type="text"  class="form-control required" autocomplete="off" id="exampleInputAmount" placeholder="Minutes" name='ii_h_a[]' value="<?php if(!empty($calibration_info->ii_h_a)){echo dec2dms($calibration_info->ii_h_a)['min']; } ?>">
                      </div>
                    </div>
                    <div class="form-group col-sm-4">
                      <div class="input-group">
                        <div class="input-group-addon">Sec</div>
                        <input type="text"  class="form-control required" autocomplete="off" id="exampleInputAmount" placeholder="Seconds" name='ii_h_a[]' value="<?php if(!empty($calibration_info->ii_h_a)){echo round(dec2dms($calibration_info->ii_h_a)['sec']); } ?>">
                      </div>
                    </div>
                  </td>
                  <td>
                    <div class="form-group col-sm-4">
                      <div class="input-group">
                        <div class="input-group-addon">Deg</div>
                        <input type="text"  class="form-control required" autocomplete="off" id="exampleInputAmount" placeholder="Degrees" name='ii_h_b[]' value="<?php if(!empty($calibration_info->ii_h_b)){echo dec2dms($calibration_info->ii_h_b)['deg']; } ?>">
                      </div>
                    </div>
                    <div class="form-group col-sm-4">
                      <div class="input-group">
                        <div class="input-group-addon">Min</div>
                        <input type="text"  class="form-control required" autocomplete="off" id="exampleInputAmount" placeholder="Minutes" name='ii_h_b[]' value="<?php if(!empty($calibration_info->ii_h_b)){echo dec2dms($calibration_info->ii_h_b)['min']; } ?>">
                      </div>
                    </div>
                    <div class="form-group col-sm-4">
                      <div class="input-group">
                        <div class="input-group-addon">Sec</div>
                        <input type="text"  class="form-control required" autocomplete="off" id="exampleInputAmount" placeholder="Seconds" name='ii_h_b[]' value="<?php if(!empty($calibration_info->ii_h_b)){echo round(dec2dms($calibration_info->ii_h_b)['sec']); } ?>">
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
                  <th  class="text-center" style="font-weight:500;">START A</th>
                  <th  class="text-center" style="font-weight:500;">END B</th>
                </tr>
              </thead>
              <tbody>
                <tr  class="form-inline">
                  <td class="text-center" style="font-weight:500;">I</td>
                  <td>
                    <div class="form-group col-sm-4">
                      <div class="input-group">
                        <div class="input-group-addon">Deg</div>
                        <input type="text"  class="form-control required" autocomplete="off" id="exampleInputAmount" placeholder="Degrees" name='i_v_a[]' readonly value="90">
                      </div>
                    </div>
                    <div class="form-group col-sm-4">
                      <div class="input-group">
                        <div class="input-group-addon">Min</div>
                        <input type="text"  class="form-control required" autocomplete="off" id="exampleInputAmount" placeholder="Minutes" name='i_v_a[]' readonly value="0">
                      </div>
                    </div>
                    <div class="form-group col-sm-4">
                      <div class="input-group">
                        <div class="input-group-addon">Sec</div>
                        <input type="text"  class="form-control required" autocomplete="off" id="exampleInputAmount" placeholder="Seconds" name='i_v_a[]' readonly value="0">
                      </div>
                    </div>
                  </td>
                  <td>
                    <div class="form-group col-sm-4">
                      <div class="input-group">
                        <div class="input-group-addon">Deg</div>
                        <input type="text"  class="form-control required" autocomplete="off" id="exampleInputAmount" placeholder="Degrees" name='i_v_b[]' value="<?php if(!empty($calibration_info->i_v_b)){echo dec2dms($calibration_info->i_v_b)['deg']; } ?>">
                      </div>
                    </div>
                    <div class="form-group col-sm-4">
                      <div class="input-group">
                        <div class="input-group-addon">Min</div>
                        <input type="text"  class="form-control required" autocomplete="off" id="exampleInputAmount" placeholder="Minutes" name='i_v_b[]' value="<?php if(!empty($calibration_info->i_v_b)){echo dec2dms($calibration_info->i_v_b)['min']; } ?>">
                      </div>
                    </div>
                    <div class="form-group col-sm-4">
                      <div class="input-group">
                        <div class="input-group-addon">Sec</div>
                        <input type="text"  class="form-control required" autocomplete="off" id="exampleInputAmount" placeholder="Seconds" name='i_v_b[]' value="<?php if(!empty($calibration_info->i_v_b)){echo round(dec2dms($calibration_info->i_v_b)['sec']); } ?>">
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
                        <input type="text"  class="form-control required" autocomplete="off" id="exampleInputAmount" placeholder="Degrees" name='ii_v_a[]' value="<?php if(!empty($calibration_info->ii_v_a)){echo dec2dms($calibration_info->ii_v_a)['deg']; } ?>">
                      </div>
                    </div>
                    <div class="form-group col-sm-4">
                      <div class="input-group">
                        <div class="input-group-addon">Min</div>
                        <input type="text"  class="form-control required" autocomplete="off" id="exampleInputAmount" placeholder="Minutes" name='ii_v_a[]' value="<?php if(!empty($calibration_info->ii_v_a)){echo dec2dms($calibration_info->ii_v_a)['min']; } ?>">
                      </div>
                    </div>
                    <div class="form-group col-sm-4">
                      <div class="input-group">
                        <div class="input-group-addon">Sec</div>
                        <input type="text"  class="form-control required" autocomplete="off" id="exampleInputAmount" placeholder="Seconds" name='ii_v_a[]' value="<?php if(!empty($calibration_info->ii_v_a)){echo round(dec2dms($calibration_info->ii_v_a)['sec']); } ?>">
                      </div>
                    </div>
                  </td>
                  <td>
                    <div class="form-group col-sm-4">
                      <div class="input-group">
                        <div class="input-group-addon">Deg</div>
                        <input type="text"  class="form-control required" autocomplete="off" id="exampleInputAmount" placeholder="Degrees" name='ii_v_b[]' value="<?php if(!empty($calibration_info->ii_v_b)){echo dec2dms($calibration_info->ii_v_b)['deg']; } ?>">
                      </div>
                    </div>
                    <div class="form-group col-sm-4">
                      <div class="input-group">
                        <div class="input-group-addon">Min</div>
                        <input type="text"  class="form-control required" autocomplete="off" id="exampleInputAmount" placeholder="Minutes" name='ii_v_b[]' value="<?php if(!empty($calibration_info->ii_v_b)){echo dec2dms($calibration_info->ii_v_b)['min']; } ?>">
                      </div>
                    </div>
                    <div class="form-group col-sm-4">
                      <div class="input-group">
                        <div class="input-group-addon">Sec</div>
                        <input type="text"  class="form-control required" autocomplete="off" id="exampleInputAmount" placeholder="Seconds" name='ii_v_b[]' value="<?php if(!empty($calibration_info->ii_v_b)){echo round(dec2dms($calibration_info->ii_v_b)['sec']); } ?>">
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
            <b>PRE-CALIBRATION EDM CHECKS</b>
            <table class="table table-bordered" id="serviceFields">
              <thead>
                <tr>
                  <th class="text-center" style="font-weight:500;">Sequence</th>
                  <th class="text-center" style="font-weight:500;">Instrument</th>
                  <th class="text-center" style="font-weight:500;">Target</th>
                  <th class="text-center" style="font-weight:500;">Set</th>
                  <th class="text-center" style="font-weight:500;">FACE</th>
                  <th class="text-center" style="font-weight:500;">X</th>
                  <th class="text-center" style="font-weight:500;">Y</th>
                  <th class="text-center" style="font-weight:500;">Z</th>
                </tr>
              </thead>
              <tbody>
                <tr>
                  <td class="text-center" style="font-weight:500;">1<br><br> 2</td>
                  <td class="text-center" style="font-weight:500;"></td>
                  <td class="text-center" style="font-weight:500;">1<br><br> 2</td>
                  <td class="text-center" style="font-weight:500;"><br><br> 1</td>
                  <td class="text-center" style="font-weight:500;"><br><br> I</td>
                  <td>
                    <div class="form-group form-group-bottom">
                      <div class="input-group" style="margin-bottom:2px;">
                        <div class="input-group-addon"></div>
                        <input  class="form-control required" autocomplete="off" value="<?php if(!empty($calibration_info)) {echo $calibration_info->i_edm_a_1;} ?>"  name="i_edm_a_1" type="text">
                      </div>
                      <div class="input-group" style="margin-bottom:2px;">
                        <div class="input-group-addon"></div>
                        <input  class="form-control required" autocomplete="off" value="<?php if(!empty($calibration_info)) {echo $calibration_info->i_edm_a_2;} ?>"  name="i_edm_a_2" type="text">
                      </div>
                      
                    </div>
                  </td>
                  <td><div class="form-group form-group-bottom">
                    <div class="input-group" style="margin-bottom:2px;">
                      <div class="input-group-addon"></div>
                      <input  class="form-control required" autocomplete="off" value="<?php if(!empty($calibration_info)) {echo $calibration_info->i_edm_a_3;} ?>"  name="i_edm_a_3" type="text">
                    </div>
                    
                    <div class="input-group" style="margin-bottom:2px;">
                      <div class="input-group-addon"></div>
                      <input  class="form-control required" autocomplete="off" value="<?php if(!empty($calibration_info)) {echo $calibration_info->i_edm_a_4;} ?>"  name="i_edm_a_4" type="text">
                    </div>
                    
                  </div></td>
                  <td><div class="form-group form-group-bottom">
                    <div class="input-group" style="margin-bottom:2px;">
                      <div class="input-group-addon"></div>
                      <input  class="form-control required" autocomplete="off" value="<?php if(!empty($calibration_info)) {echo $calibration_info->i_edm_a_5;} ?>"  name="i_edm_a_5" type="text">
                    </div>
                    <div class="input-group" style="margin-bottom:2px;">
                      <div class="input-group-addon"></div>
                      <input  class="form-control required" autocomplete="off" value="<?php if(!empty($calibration_info)) {echo $calibration_info->i_edm_a_6;} ?>"  name="i_edm_a_6" type="text">
                    </div>
                    
                  </div></td>
                </tr>
                <tr>
                  <td class="text-center" style="font-weight:500;">3<br><br> 4</td>
                  <td class="text-center" style="font-weight:500;"></td>
                  <td class="text-center" style="font-weight:500;">1<br><br> 2</td>
                  <td class="text-center" style="font-weight:500;"><br><br> 2</td>
                  <td class="text-center" style="font-weight:500;"><br><br> II</td>
                  <td>
                    <div class="form-group form-group-bottom">
                      <div class="input-group" style="margin-bottom:2px;">
                        <div class="input-group-addon"></div>
                        <input  class="form-control required" autocomplete="off" value="<?php if(!empty($calibration_info)) {echo $calibration_info->i_edm_a_7;} ?>"  name="i_edm_a_7" type="text">
                      </div>
                      <div class="input-group" style="margin-bottom:2px;">
                        <div class="input-group-addon"></div>
                        <input  class="form-control required" autocomplete="off" value="<?php if(!empty($calibration_info)) {echo $calibration_info->i_edm_a_8;} ?>"  name="i_edm_a_8" type="text">
                      </div>
                      
                    </div>
                  </td>
                  <td><div class="form-group form-group-bottom">
                    <div class="input-group" style="margin-bottom:2px;">
                      <div class="input-group-addon"></div>
                      <input  class="form-control required" autocomplete="off" value="<?php if(!empty($calibration_info)) {echo $calibration_info->i_edm_a_9;} ?>"  name="i_edm_a_9" type="text">
                    </div>
                    <div class="input-group" style="margin-bottom:2px;">
                      <div class="input-group-addon"></div>
                      <input  class="form-control required" autocomplete="off" value="<?php if(!empty($calibration_info)) {echo $calibration_info->i_edm_a_10;} ?>"  name="i_edm_a_10" type="text">
                    </div>
                    
                  </div></td>
                  <td><div class="form-group form-group-bottom">
                    <div class="input-group" style="margin-bottom:2px;">
                      <div class="input-group-addon"></div>
                      <input  class="form-control required" autocomplete="off" value="<?php if(!empty($calibration_info)) {echo $calibration_info->i_edm_a_11;} ?>"  name="i_edm_a_11" type="text">
                    </div>
                    <div class="input-group" style="margin-bottom:2px;">
                      <div class="input-group-addon"></div>
                      <input  class="form-control required" autocomplete="off" value="<?php if(!empty($calibration_info)) {echo $calibration_info->i_edm_a_12;} ?>"  name="i_edm_a_12" type="text">
                    </div>
                    
                  </div></td>
                </tr> 
                <tr>
                  <td class="text-center" style="font-weight:500;">5<br><br> 6</td>
                  <td class="text-center" style="font-weight:500;">1</td>
                  <td class="text-center" style="font-weight:500;">1<br><br> 2</td>
                  <td class="text-center" style="font-weight:500;"><br><br> 3</td>
                  <td class="text-center" style="font-weight:500;"><br><br> I</td>                  
                  <td>
                    <div class="form-group form-group-bottom">
                      <div class="input-group" style="margin-bottom:2px;">
                        <div class="input-group-addon"></div>
                        <input  class="form-control required" autocomplete="off" value="<?php if(!empty($calibration_info)) {echo $calibration_info->i_edm_a_13;} ?>"  name="i_edm_a_13" type="text">
                      </div>
                      <div class="input-group" style="margin-bottom:2px;">
                        <div class="input-group-addon"></div>
                        <input  class="form-control required" autocomplete="off" value="<?php if(!empty($calibration_info)) {echo $calibration_info->i_edm_a_14;} ?>"  name="i_edm_a_14" type="text">
                      </div>
                      
                    </div>
                  </td>
                  <td><div class="form-group form-group-bottom">
                    <div class="input-group" style="margin-bottom:2px;">
                      <div class="input-group-addon"></div>
                      <input  class="form-control required" autocomplete="off" value="<?php if(!empty($calibration_info)) {echo $calibration_info->i_edm_a_15;} ?>"  name="i_edm_a_15" type="text">
                    </div>
                    <div class="input-group" style="margin-bottom:2px;">
                      <div class="input-group-addon"></div>
                      <input  class="form-control required" autocomplete="off" value="<?php if(!empty($calibration_info)) {echo $calibration_info->i_edm_a_16;} ?>"  name="i_edm_a_16" type="text">
                    </div>
                    
                  </div></td>
                  <td><div class="form-group form-group-bottom">
                    <div class="input-group" style="margin-bottom:2px;">
                      <div class="input-group-addon"></div>
                      <input  class="form-control required" autocomplete="off" value="<?php if(!empty($calibration_info)) {echo $calibration_info->i_edm_a_17;} ?>"  name="i_edm_a_17" type="text">
                    </div>
                    <div class="input-group" style="margin-bottom:2px;">
                      <div class="input-group-addon"></div>
                      <input  class="form-control required" autocomplete="off" value="<?php if(!empty($calibration_info)) {echo $calibration_info->i_edm_a_18;} ?>"  name="i_edm_a_18" type="text">
                    </div>
                    
                  </div></td>
                </tr>
                <tr>
                  <td class="text-center" style="font-weight:500;">7<br><br> 8</td>
                  <td class="text-center" style="font-weight:500;"></td>
                  <td class="text-center" style="font-weight:500;">1<br><br> 2</td>
                  <td class="text-center" style="font-weight:500;"><br><br> 4</td>
                  <td class="text-center" style="font-weight:500;"><br><br> II</td>                  

                  <td>
                    <div class="form-group form-group-bottom">
                      <div class="input-group" style="margin-bottom:2px;">
                        <div class="input-group-addon"></div>
                        <input  class="form-control required" autocomplete="off" value="<?php if(!empty($calibration_info)) {echo $calibration_info->i_edm_a_19;} ?>"  name="i_edm_a_19" type="text">
                      </div>
                      <div class="input-group" style="margin-bottom:2px;">
                        <div class="input-group-addon"></div>
                        <input  class="form-control required" autocomplete="off" value="<?php if(!empty($calibration_info)) {echo $calibration_info->i_edm_a_20;} ?>"  name="i_edm_a_20" type="text">
                      </div>
                      
                    </div>
                  </td>
                  <td><div class="form-group form-group-bottom">
                    <div class="input-group" style="margin-bottom:2px;">
                      <div class="input-group-addon"></div>
                      <input  class="form-control required" autocomplete="off" value="<?php if(!empty($calibration_info)) {echo $calibration_info->i_edm_a_21;} ?>"  name="i_edm_a_21" type="text">
                    </div>
                    <div class="input-group" style="margin-bottom:2px;">
                      <div class="input-group-addon"></div>
                      <input  class="form-control required" autocomplete="off" value="<?php if(!empty($calibration_info)) {echo $calibration_info->i_edm_a_22;} ?>"  name="i_edm_a_22" type="text">
                    </div>
                    
                  </div></td>
                  <td><div class="form-group form-group-bottom">
                    <div class="input-group" style="margin-bottom:2px;">
                      <div class="input-group-addon"></div>
                      <input  class="form-control required" autocomplete="off" value="<?php if(!empty($calibration_info)) {echo $calibration_info->i_edm_a_23;} ?>"  name="i_edm_a_23" type="text">
                    </div>
                    <div class="input-group" style="margin-bottom:2px;">
                      <div class="input-group-addon"></div>
                      <input  class="form-control required" autocomplete="off" value="<?php if(!empty($calibration_info)) {echo $calibration_info->i_edm_a_24;} ?>"  name="i_edm_a_24" type="text">
                    </div>
                    
                  </div></td>
                </tr> 
                <tr>
                  <td class="text-center" style="font-weight:500;">9<br><br> 10</td>
                  <td class="text-center" style="font-weight:500;"></td>
                  <td class="text-center" style="font-weight:500;">1<br><br> 2</td>
                  <td class="text-center" style="font-weight:500;"><br><br> 1</td>
                  <td class="text-center" style="font-weight:500;"><br><br> I</td> 
                  <td>
                    <div class="form-group form-group-bottom">
                      <div class="input-group" style="margin-bottom:2px;">
                        <div class="input-group-addon"></div>
                        <input  class="form-control required" autocomplete="off" value="<?php if(!empty($calibration_info)) {echo $calibration_info->i_edm_a_25;} ?>"  name="i_edm_a_25" type="text">
                      </div>
                      <div class="input-group" style="margin-bottom:2px;">
                        <div class="input-group-addon"></div>
                        <input  class="form-control required" autocomplete="off" value="<?php if(!empty($calibration_info)) {echo $calibration_info->i_edm_a_26;} ?>"  name="i_edm_a_26" type="text">
                      </div>
                      
                    </div>
                  </td>
                  <td><div class="form-group form-group-bottom">
                    <div class="input-group" style="margin-bottom:2px;">
                      <div class="input-group-addon"></div>
                      <input  class="form-control required" autocomplete="off" value="<?php if(!empty($calibration_info)) {echo $calibration_info->i_edm_a_27;} ?>"  name="i_edm_a_27" type="text">
                    </div>
                    <div class="input-group" style="margin-bottom:2px;">
                      <div class="input-group-addon"></div>
                      <input  class="form-control required" autocomplete="off" value="<?php if(!empty($calibration_info)) {echo $calibration_info->i_edm_a_28;} ?>"  name="i_edm_a_28" type="text">
                    </div>
                    
                  </div></td>
                  <td><div class="form-group form-group-bottom">
                    <div class="input-group" style="margin-bottom:2px;">
                      <div class="input-group-addon"></div>
                      <input  class="form-control required" autocomplete="off" value="<?php if(!empty($calibration_info)) {echo $calibration_info->i_edm_a_29;} ?>"  name="i_edm_a_29" type="text">
                    </div>
                    <div class="input-group" style="margin-bottom:2px;">
                      <div class="input-group-addon"></div>
                      <input  class="form-control required" autocomplete="off" value="<?php if(!empty($calibration_info)) {echo $calibration_info->i_edm_a_30;} ?>"  name="i_edm_a_30" type="text">
                    </div>
                    
                  </div></td>
                </tr>
                <tr>
                  <td class="text-center" style="font-weight:500;">11<br><br> 12</td>
                  <td class="text-center" style="font-weight:500;">2</td>
                  <td class="text-center" style="font-weight:500;">1<br><br> 2</td>
                  <td class="text-center" style="font-weight:500;"><br><br> 2</td>
                  <td class="text-center" style="font-weight:500;"><br><br> II</td>
                  <td>
                    <div class="form-group form-group-bottom">
                      <div class="input-group" style="margin-bottom:2px;">
                        <div class="input-group-addon"></div>
                        <input  class="form-control required" autocomplete="off" value="<?php if(!empty($calibration_info)) {echo $calibration_info->i_edm_a_31;} ?>"  name="i_edm_a_31" type="text">
                      </div>
                      <div class="input-group" style="margin-bottom:2px;">
                        <div class="input-group-addon"></div>
                        <input  class="form-control required" autocomplete="off" value="<?php if(!empty($calibration_info)) {echo $calibration_info->i_edm_a_32;} ?>"  name="i_edm_a_32" type="text">
                      </div>
                      
                    </div>
                  </td>
                  <td><div class="form-group form-group-bottom">
                    <div class="input-group" style="margin-bottom:2px;">
                      <div class="input-group-addon"></div>
                      <input  class="form-control required" autocomplete="off" value="<?php if(!empty($calibration_info)) {echo $calibration_info->i_edm_a_33;} ?>"  name="i_edm_a_33" type="text">
                    </div>
                    <div class="input-group" style="margin-bottom:2px;">
                      <div class="input-group-addon"></div>
                      <input  class="form-control required" autocomplete="off" value="<?php if(!empty($calibration_info)) {echo $calibration_info->i_edm_a_34;} ?>"  name="i_edm_a_34" type="text">
                    </div>
                    
                  </div></td>
                  <td><div class="form-group form-group-bottom">
                    <div class="input-group" style="margin-bottom:2px;">
                      <div class="input-group-addon"></div>
                      <input  class="form-control required" autocomplete="off" value="<?php if(!empty($calibration_info)) {echo $calibration_info->i_edm_a_35;} ?>"  name="i_edm_a_35" type="text">
                    </div>
                    <div class="input-group" style="margin-bottom:2px;">
                      <div class="input-group-addon"></div>
                      <input  class="form-control required" autocomplete="off" value="<?php if(!empty($calibration_info)) {echo $calibration_info->i_edm_a_36;} ?>"  name="i_edm_a_36" type="text">
                    </div>
                    
                  </div></td>
                </tr> 
                <tr>
                  <td class="text-center" style="font-weight:500;">13<br><br> 14</td>
                  <td class="text-center" style="font-weight:500;"></td>
                  <td class="text-center" style="font-weight:500;">1<br><br> 2</td>
                  <td class="text-center" style="font-weight:500;"><br><br> 3</td>
                  <td class="text-center" style="font-weight:500;"><br><br> I</td>
                  <td>
                    <div class="form-group form-group-bottom">
                      <div class="input-group" style="margin-bottom:2px;">
                        <div class="input-group-addon"></div>
                        <input  class="form-control required" autocomplete="off" value="<?php if(!empty($calibration_info)) {echo $calibration_info->i_edm_a_37;} ?>"  name="i_edm_a_37" type="text">
                      </div>
                      <div class="input-group" style="margin-bottom:2px;">
                        <div class="input-group-addon"></div>
                        <input  class="form-control required" autocomplete="off" value="<?php if(!empty($calibration_info)) {echo $calibration_info->i_edm_a_38;} ?>"  name="i_edm_a_38" type="text">
                      </div>
                      
                    </div>
                  </td>
                  <td><div class="form-group form-group-bottom">
                    <div class="input-group" style="margin-bottom:2px;">
                      <div class="input-group-addon"></div>
                      <input  class="form-control required" autocomplete="off" value="<?php if(!empty($calibration_info)) {echo $calibration_info->i_edm_a_39;} ?>"  name="i_edm_a_39" type="text">
                    </div>
                    <div class="input-group" style="margin-bottom:2px;">
                      <div class="input-group-addon"></div>
                      <input  class="form-control required" autocomplete="off" value="<?php if(!empty($calibration_info)) {echo $calibration_info->i_edm_a_40;} ?>"  name="i_edm_a_40" type="text">
                    </div>
                    
                  </div></td>
                  <td><div class="form-group form-group-bottom">
                    <div class="input-group" style="margin-bottom:2px;">
                      <div class="input-group-addon"></div>
                      <input  class="form-control required" autocomplete="off" value="<?php if(!empty($calibration_info)) {echo $calibration_info->i_edm_a_41;} ?>"  name="i_edm_a_41" type="text">
                    </div>
                    <div class="input-group" style="margin-bottom:2px;">
                      <div class="input-group-addon"></div>
                      <input  class="form-control required" autocomplete="off" value="<?php if(!empty($calibration_info)) {echo $calibration_info->i_edm_a_42;} ?>"  name="i_edm_a_42" type="text">
                    </div>
                    
                  </div></td>
                </tr>
                <tr>
                  <td class="text-center" style="font-weight:500;">15<br><br> 16</td>
                  <td class="text-center" style="font-weight:500;"></td>
                  <td class="text-center" style="font-weight:500;">1<br><br> 2</td>
                  <td class="text-center" style="font-weight:500;"><br><br> 4</td>
                  <td class="text-center" style="font-weight:500;"><br><br> II</td>
                  <td>
                    <div class="form-group form-group-bottom">
                      <div class="input-group" style="margin-bottom:2px;">
                        <div class="input-group-addon"></div>
                        <input  class="form-control required" autocomplete="off" value="<?php if(!empty($calibration_info)) {echo $calibration_info->i_edm_a_43;} ?>"  name="i_edm_a_43" type="text">
                      </div>
                      <div class="input-group" style="margin-bottom:2px;">
                        <div class="input-group-addon"></div>
                        <input  class="form-control required" autocomplete="off" value="<?php if(!empty($calibration_info)) {echo $calibration_info->i_edm_a_44;} ?>"  name="i_edm_a_44" type="text">
                      </div>
                      
                    </div>
                  </td>
                  <td><div class="form-group form-group-bottom">
                    <div class="input-group" style="margin-bottom:2px;">
                      <div class="input-group-addon"></div>
                      <input  class="form-control required" autocomplete="off" value="<?php if(!empty($calibration_info)) {echo $calibration_info->i_edm_a_45;} ?>"  name="i_edm_a_45" type="text">
                    </div>
                    <div class="input-group" style="margin-bottom:2px;">
                      <div class="input-group-addon"></div>
                      <input  class="form-control required" autocomplete="off" value="<?php if(!empty($calibration_info)) {echo $calibration_info->i_edm_a_46;} ?>"  name="i_edm_a_46" type="text">
                    </div>
                    
                  </div></td>
                  <td><div class="form-group form-group-bottom">
                    <div class="input-group" style="margin-bottom:2px;">
                      <div class="input-group-addon"></div>
                      <input  class="form-control required" autocomplete="off" value="<?php if(!empty($calibration_info)) {echo $calibration_info->i_edm_a_47;} ?>"  name="i_edm_a_47" type="text">
                    </div>
                    <div class="input-group" style="margin-bottom:2px;">
                      <div class="input-group-addon"></div>
                      <input  class="form-control required" autocomplete="off" value="<?php if(!empty($calibration_info)) {echo $calibration_info->i_edm_a_48;} ?>"  name="i_edm_a_48" type="text">
                    </div>
                    
                  </div></td>
                </tr>               
                
              </tbody>
            </table>
            <button type="button" class="btn btn-secondary prev-step">Previous</button>
            <button type="button" class="btn btn-primary next-step">ENTER POST-CALIBRATION CHECKS</button>  
          </div>

          <div class="form-section">        
            <b>HORIZONTAL CIRCLE INDEX ERROR</b>
            <table class="table" id="serviceFields">
              <thead>
              
                <tr>
                  <th  class="text-center" style="font-weight:500;">FACE</th>
                  <th  class="text-center" style="font-weight:500;">START A</th>
                  <th  class="text-center" style="font-weight:500;">END B</th>
                </tr>
              </thead>
              <tbody>
                <tr  class="form-inline">
                  <td class="text-center" style="font-weight:500;">I</td>
                  <td>
                    <div class="form-group col-sm-4">
                      <div class="input-group">
                        <div class="input-group-addon">Deg</div>
                        <input type="text"  class="form-control required" autocomplete="off" id="exampleInputAmount" placeholder="Degrees" name='t_h_a[]' readonly value="0">
                      </div>
                    </div>
                    <div class="form-group col-sm-4">
                      <div class="input-group">
                        <div class="input-group-addon">Min</div>
                        <input type="text"  class="form-control required" autocomplete="off" id="exampleInputAmount" placeholder="Minutes" name='t_h_a[]' readonly value="0">
                      </div>
                    </div>
                    <div class="form-group col-sm-4">
                      <div class="input-group">
                        <div class="input-group-addon">Sec</div>
                        <input type="text"  class="form-control required" autocomplete="off" id="exampleInputAmount" placeholder="Seconds" name='t_h_a[]' readonly value="0">
                      </div>
                    </div>
                  </td>
                  <td>
                    <div class="form-group col-sm-4">
                      <div class="input-group">
                        <div class="input-group-addon">Deg</div>
                        <input type="text"  class="form-control required" autocomplete="off" id="exampleInputAmount" placeholder="Degrees" name='t_h_b[]' readonly value="0">
                      </div>
                    </div>
                    <div class="form-group col-sm-4">
                      <div class="input-group">
                        <div class="input-group-addon">Min</div>
                        <input type="text"  class="form-control required" autocomplete="off" id="exampleInputAmount" placeholder="Minutes" name='t_h_b[]' readonly value="0">
                      </div>
                    </div>
                    <div class="form-group col-sm-4">
                      <div class="input-group">
                        <div class="input-group-addon">Sec</div>
                        <input type="text"  class="form-control required" autocomplete="off" id="exampleInputAmount" placeholder="Seconds" name='t_h_b[]' readonly value="0">
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
                        <input type="text"  class="form-control required" autocomplete="off" id="exampleInputAmount" placeholder="Degrees" name='tt_h_a[]' value="<?php if(!empty($calibration_info->tt_h_a)){echo dec2dms($calibration_info->tt_h_a)['deg']; } ?>">
                      </div>
                    </div>
                    <div class="form-group col-sm-4">
                      <div class="input-group">
                        <div class="input-group-addon">Min</div>
                        <input type="text"  class="form-control required" autocomplete="off" id="exampleInputAmount" placeholder="Minutes" name='tt_h_a[]' value="<?php if(!empty($calibration_info->tt_h_a)){echo dec2dms($calibration_info->tt_h_a)['min']; } ?>">
                      </div>
                    </div>
                    <div class="form-group col-sm-4">
                      <div class="input-group">
                        <div class="input-group-addon">Sec</div>
                        <input type="text"  class="form-control required" autocomplete="off" id="exampleInputAmount" placeholder="Seconds" name='tt_h_a[]' value="<?php if(!empty($calibration_info->tt_h_a)){echo round(dec2dms($calibration_info->tt_h_a)['sec']); } ?>">
                      </div>
                    </div>
                  </td>
                  <td>
                    <div class="form-group col-sm-4">
                      <div class="input-group">
                        <div class="input-group-addon">Deg</div>
                        <input type="text"  class="form-control required" autocomplete="off" id="exampleInputAmount" placeholder="Degrees" name='tt_h_b[]' value="<?php if(!empty($calibration_info->tt_h_b)){echo dec2dms($calibration_info->tt_h_b)['deg']; } ?>">
                      </div>
                    </div>
                    <div class="form-group col-sm-4">
                      <div class="input-group">
                        <div class="input-group-addon">Min</div>
                        <input type="text"  class="form-control required" autocomplete="off" id="exampleInputAmount" placeholder="Minutes" name='tt_h_b[]' value="<?php if(!empty($calibration_info->tt_h_b)){echo dec2dms($calibration_info->tt_h_b)['min']; } ?>">
                      </div>
                    </div>
                    <div class="form-group col-sm-4">
                      <div class="input-group">
                        <div class="input-group-addon">Sec</div>
                        <input type="text"  class="form-control required" autocomplete="off" id="exampleInputAmount" placeholder="Seconds" name='tt_h_b[]' value="<?php if(!empty($calibration_info->tt_h_b)){echo round(dec2dms($calibration_info->tt_h_b)['sec']); } ?>">
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
                  <th class="text-center" style="font-weight:500;">FACE</th>
                  <th class="text-center" style="font-weight:500;">START A</th>
                  <th class="text-center" style="font-weight:500;">END B</th>
                </tr>
              </thead>
              <tbody>
                <tr  class="form-inline">
                  <td class="text-center" style="font-weight:500;">I</td>
                  <td>
                    <div class="form-group col-sm-4">
                      <div class="input-group">
                        <div class="input-group-addon">Deg</div>
                        <input type="text"  class="form-control required" autocomplete="off" id="exampleInputAmount" placeholder="Degrees" name='t_v_a[]' readonly value="90">
                      </div>
                    </div>
                    <div class="form-group col-sm-4">
                      <div class="input-group">
                        <div class="input-group-addon">Min</div>
                        <input type="text"  class="form-control required" autocomplete="off" id="exampleInputAmount" placeholder="Minutes" name='t_v_a[]' readonly value="0">
                      </div>
                    </div>
                    <div class="form-group col-sm-4">
                      <div class="input-group">
                        <div class="input-group-addon">Sec</div>
                        <input type="text"  class="form-control required" autocomplete="off" id="exampleInputAmount" placeholder="Seconds" name='t_v_a[]' readonly value="0">
                      </div>
                    </div>
                  </td>
                  <td>
                    <div class="form-group col-sm-4">
                      <div class="input-group">
                        <div class="input-group-addon">Deg</div>
                        <input type="text"  class="form-control required" autocomplete="off" id="exampleInputAmount" placeholder="Degrees" name='t_v_b[]' value="<?php if(!empty($calibration_info->t_v_b)){echo dec2dms($calibration_info->t_v_b)['deg']; } ?>">
                      </div>
                    </div>
                    <div class="form-group col-sm-4">
                      <div class="input-group">
                        <div class="input-group-addon">Min</div>
                        <input type="text"  class="form-control required" autocomplete="off" id="exampleInputAmount" placeholder="Minutes" name='t_v_b[]' value="<?php if(!empty($calibration_info->t_v_b)){echo dec2dms($calibration_info->t_v_b)['min']; } ?>">
                      </div>
                    </div>
                    <div class="form-group col-sm-4">
                      <div class="input-group">
                        <div class="input-group-addon">Sec</div>
                        <input type="text"  class="form-control required" autocomplete="off" id="exampleInputAmount" placeholder="Seconds" name='t_v_b[]' value="<?php if(!empty($calibration_info->t_v_b)){echo round(dec2dms($calibration_info->t_v_b)['sec']); } ?>">
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
                        <input type="text"  class="form-control required" autocomplete="off" id="exampleInputAmount" placeholder="Degrees" name='tt_v_a[]' value="<?php if(!empty($calibration_info->tt_v_a)){echo dec2dms($calibration_info->tt_v_a)['deg']; } ?>">
                      </div>
                    </div>
                    <div class="form-group col-sm-4">
                      <div class="input-group">
                        <div class="input-group-addon">Min</div>
                        <input type="text"  class="form-control required" autocomplete="off" id="exampleInputAmount" placeholder="Minutes" name='tt_v_a[]' value="<?php if(!empty($calibration_info->tt_v_a)){echo dec2dms($calibration_info->tt_v_a)['min']; } ?>">
                      </div>
                    </div>
                    <div class="form-group col-sm-4">
                      <div class="input-group">
                        <div class="input-group-addon">Sec</div>
                        <input type="text"  class="form-control required" autocomplete="off" id="exampleInputAmount" placeholder="Seconds" name='tt_v_a[]' value="<?php if(!empty($calibration_info->tt_v_a)){echo round(dec2dms($calibration_info->tt_v_a)['sec']); } ?>">
                      </div>
                    </div>
                  </td>
                  <td>
                    <div class="form-group col-sm-4">
                      <div class="input-group">
                        <div class="input-group-addon">Deg</div>
                        <input type="text"  class="form-control required" autocomplete="off" id="exampleInputAmount" placeholder="Degrees" name='tt_v_b[]' value="<?php if(!empty($calibration_info->tt_v_b)){echo dec2dms($calibration_info->tt_v_b)['deg']; } ?>">
                      </div>
                    </div>
                    <div class="form-group col-sm-4">
                      <div class="input-group">
                        <div class="input-group-addon">Min</div>
                        <input type="text"  class="form-control required" autocomplete="off" id="exampleInputAmount" placeholder="Minutes" name='tt_v_b[]' value="<?php if(!empty($calibration_info->tt_v_b)){echo dec2dms($calibration_info->tt_v_b)['min']; } ?>">
                      </div>
                    </div>
                    <div class="form-group col-sm-4">
                      <div class="input-group">
                        <div class="input-group-addon">Sec</div>
                        <input type="text"  class="form-control required" autocomplete="off" id="exampleInputAmount" placeholder="Seconds" name='tt_v_b[]' value="<?php if(!empty($calibration_info->tt_v_b)){echo round(dec2dms($calibration_info->tt_v_b)['sec']); } ?>">
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
            <b>POST-CALIBRATION EDM CHECKS</b>
            <table class="table table-bordered" id="serviceFields">
              <thead>
                <tr>
                  <th class="text-center" style="font-weight:500;">Sequence</th>
                  <th class="text-center" style="font-weight:500;">Instrument</th>
                  <th class="text-center" style="font-weight:500;">Target</th>
                  <th class="text-center" style="font-weight:500;">Set</th>
                  <th  class="text-center" style="font-weight:500;">FACE</th>
                  <th  class="text-center" style="font-weight:500;">X</th>
                  <th  class="text-center" style="font-weight:500;">Y</th>
                  <th  class="text-center" style="font-weight:500;">Z</th>
                </tr>
              </thead>
              <tbody>
                <tr>
                  <td class="text-center" style="font-weight:500;">1<br><br> 2</td>
                  <td class="text-center" style="font-weight:500;"></td>
                  <td class="text-center" style="font-weight:500;">1<br><br> 2</td>
                  <td class="text-center" style="font-weight:500;"><br><br> 1</td>
                  <td class="text-center" style="font-weight:500;"><br><br> I</td>
                  <td>
                    <div class="form-group form-group-bottom">
                      <div class="input-group" style="margin-bottom:2px;">
                        <div class="input-group-addon"></div>
                        <input  class="form-control required" autocomplete="off" value="<?php if(!empty($calibration_info)) {echo $calibration_info->i_edm_a_49;} ?>"  name="i_edm_a_49" type="text">
                      </div>
                      <div class="input-group" style="margin-bottom:2px;">
                        <div class="input-group-addon"></div>
                        <input  class="form-control required" autocomplete="off" value="<?php if(!empty($calibration_info)) {echo $calibration_info->i_edm_a_50;} ?>"  name="i_edm_a_50" type="text">
                      </div>
                      
                    </div>
                  </td>
                  <td><div class="form-group form-group-bottom">
                    <div class="input-group" style="margin-bottom:2px;">
                      <div class="input-group-addon"></div>
                      <input  class="form-control required" autocomplete="off" value="<?php if(!empty($calibration_info)) {echo $calibration_info->i_edm_a_51;} ?>"  name="i_edm_a_51" type="text">
                    </div>
                    <div class="input-group" style="margin-bottom:2px;">
                      <div class="input-group-addon"></div>
                      <input  class="form-control required" autocomplete="off" value="<?php if(!empty($calibration_info)) {echo $calibration_info->i_edm_a_52;} ?>"  name="i_edm_a_52" type="text">
                    </div>
                    
                  </div></td>
                  <td><div class="form-group form-group-bottom">
                    <div class="input-group" style="margin-bottom:2px;">
                      <div class="input-group-addon"></div>
                      <input  class="form-control required" autocomplete="off" value="<?php if(!empty($calibration_info)) {echo $calibration_info->i_edm_a_53;} ?>"  name="i_edm_a_53" type="text">
                    </div>
                    <div class="input-group" style="margin-bottom:2px;">
                      <div class="input-group-addon"></div>
                      <input  class="form-control required" autocomplete="off" value="<?php if(!empty($calibration_info)) {echo $calibration_info->i_edm_a_54;} ?>"  name="i_edm_a_54" type="text">
                    </div>
                    
                  </div></td>
                </tr>
                <tr>
                  <td class="text-center" style="font-weight:500;">3<br><br> 4</td>
                  <td class="text-center" style="font-weight:500;"></td>
                  <td class="text-center" style="font-weight:500;">1<br><br> 2</td>
                  <td class="text-center" style="font-weight:500;"><br><br> 2</td>
                  <td class="text-center" style="font-weight:500;"><br><br> II</td>
                  <td>
                    <div class="form-group form-group-bottom">
                      <div class="input-group" style="margin-bottom:2px;">
                        <div class="input-group-addon"></div>
                        <input  class="form-control required" autocomplete="off" value="<?php if(!empty($calibration_info)) {echo $calibration_info->i_edm_a_55;} ?>"  name="i_edm_a_55" type="text">
                      </div>
                      <div class="input-group" style="margin-bottom:2px;">
                        <div class="input-group-addon"></div>
                        <input  class="form-control required" autocomplete="off" value="<?php if(!empty($calibration_info)) {echo $calibration_info->i_edm_a_56;} ?>"  name="i_edm_a_56" type="text">
                      </div>
                      
                    </div>
                  </td>
                  <td><div class="form-group form-group-bottom">
                    <div class="input-group" style="margin-bottom:2px;">
                      <div class="input-group-addon"></div>
                      <input  class="form-control required" autocomplete="off" value="<?php if(!empty($calibration_info)) {echo $calibration_info->i_edm_a_57;} ?>"  name="i_edm_a_57" type="text">
                    </div>
                    <div class="input-group" style="margin-bottom:2px;">
                      <div class="input-group-addon"></div>
                      <input  class="form-control required" autocomplete="off" value="<?php if(!empty($calibration_info)) {echo $calibration_info->i_edm_a_58;} ?>"  name="i_edm_a_58" type="text">
                    </div>
                    
                  </div></td>
                  <td><div class="form-group form-group-bottom">
                    <div class="input-group" style="margin-bottom:2px;">
                      <div class="input-group-addon"></div>
                      <input  class="form-control required" autocomplete="off" value="<?php if(!empty($calibration_info)) {echo $calibration_info->i_edm_a_59;} ?>"  name="i_edm_a_59" type="text">
                    </div>
                    <div class="input-group" style="margin-bottom:2px;">
                      <div class="input-group-addon"></div>
                      <input  class="form-control required" autocomplete="off" value="<?php if(!empty($calibration_info)) {echo $calibration_info->i_edm_a_60;} ?>"  name="i_edm_a_60" type="text">
                    </div>
                    
                  </div></td>
                </tr> 
                <tr>
                  <td class="text-center" style="font-weight:500;">5<br><br> 6</td>
                  <td class="text-center" style="font-weight:500;">1</td>
                  <td class="text-center" style="font-weight:500;">1<br><br> 2</td>
                  <td class="text-center" style="font-weight:500;"><br><br> 3</td>
                  <td class="text-center" style="font-weight:500;"><br><br> I</td>
                  <td>
                    <div class="form-group form-group-bottom">
                      <div class="input-group" style="margin-bottom:2px;">
                        <div class="input-group-addon"></div>
                        <input  class="form-control required" autocomplete="off" value="<?php if(!empty($calibration_info)) {echo $calibration_info->i_edm_a_61;} ?>"  name="i_edm_a_61" type="text">
                      </div>
                      <div class="input-group" style="margin-bottom:2px;">
                        <div class="input-group-addon"></div>
                        <input  class="form-control required" autocomplete="off" value="<?php if(!empty($calibration_info)) {echo $calibration_info->i_edm_a_62;} ?>"  name="i_edm_a_62" type="text">
                      </div>
                      
                    </div>
                  </td>
                  <td><div class="form-group form-group-bottom">
                    <div class="input-group" style="margin-bottom:2px;">
                      <div class="input-group-addon"></div>
                      <input  class="form-control required" autocomplete="off" value="<?php if(!empty($calibration_info)) {echo $calibration_info->i_edm_a_63;} ?>"  name="i_edm_a_63" type="text">
                    </div>
                    <div class="input-group" style="margin-bottom:2px;">
                      <div class="input-group-addon"></div>
                      <input  class="form-control required" autocomplete="off" value="<?php if(!empty($calibration_info)) {echo $calibration_info->i_edm_a_64;} ?>"  name="i_edm_a_64" type="text">
                    </div>
                    
                  </div></td>
                  <td><div class="form-group form-group-bottom">
                    <div class="input-group" style="margin-bottom:2px;">
                      <div class="input-group-addon"></div>
                      <input  class="form-control required" autocomplete="off" value="<?php if(!empty($calibration_info)) {echo $calibration_info->i_edm_a_65;} ?>"  name="i_edm_a_65" type="text">
                    </div>
                    <div class="input-group" style="margin-bottom:2px;">
                      <div class="input-group-addon"></div>
                      <input  class="form-control required" autocomplete="off" value="<?php if(!empty($calibration_info)) {echo $calibration_info->i_edm_a_66;} ?>"  name="i_edm_a_66" type="text">
                    </div>
                    
                  </div></td>
                </tr>
                <tr>
                  <td class="text-center" style="font-weight:500;">7<br><br> 8</td>
                  <td class="text-center" style="font-weight:500;"></td>
                  <td class="text-center" style="font-weight:500;">1<br><br> 2</td>
                  <td class="text-center" style="font-weight:500;"><br><br> 4</td>
                  <td class="text-center" style="font-weight:500;"><br><br> II</td>
                  <td>
                    <div class="form-group form-group-bottom">
                      <div class="input-group" style="margin-bottom:2px;">
                        <div class="input-group-addon"></div>
                        <input  class="form-control required" autocomplete="off" value="<?php if(!empty($calibration_info)) {echo $calibration_info->i_edm_a_67;} ?>"  name="i_edm_a_67" type="text">
                      </div>
                      <div class="input-group" style="margin-bottom:2px;">
                        <div class="input-group-addon"></div>
                        <input  class="form-control required" autocomplete="off" value="<?php if(!empty($calibration_info)) {echo $calibration_info->i_edm_a_68;} ?>"  name="i_edm_a_68" type="text">
                      </div>
                      
                    </div>
                  </td>
                  <td><div class="form-group form-group-bottom">
                    <div class="input-group" style="margin-bottom:2px;">
                      <div class="input-group-addon"></div>
                      <input  class="form-control required" autocomplete="off" value="<?php if(!empty($calibration_info)) {echo $calibration_info->i_edm_a_69;} ?>"  name="i_edm_a_69" type="text">
                    </div>
                    <div class="input-group" style="margin-bottom:2px;">
                      <div class="input-group-addon"></div>
                      <input  class="form-control required" autocomplete="off" value="<?php if(!empty($calibration_info)) {echo $calibration_info->i_edm_a_70;} ?>"  name="i_edm_a_70" type="text">
                    </div>
                    
                  </div></td>
                  <td><div class="form-group form-group-bottom">
                    <div class="input-group" style="margin-bottom:2px;">
                      <div class="input-group-addon"></div>
                      <input  class="form-control required" autocomplete="off" value="<?php if(!empty($calibration_info)) {echo $calibration_info->i_edm_a_71;} ?>"  name="i_edm_a_71" type="text">
                    </div>
                    <div class="input-group" style="margin-bottom:2px;">
                      <div class="input-group-addon"></div>
                      <input  class="form-control required" autocomplete="off" value="<?php if(!empty($calibration_info)) {echo $calibration_info->i_edm_a_72;} ?>"  name="i_edm_a_72" type="text">
                    </div>
                    
                  </div></td>
                </tr> 
                <tr>
                  <td class="text-center" style="font-weight:500;">9<br><br> 10</td>
                  <td class="text-center" style="font-weight:500;"></td>
                  <td class="text-center" style="font-weight:500;">1<br><br> 2</td>
                  <td class="text-center" style="font-weight:500;"><br><br> 1</td>
                  <td class="text-center" style="font-weight:500;"><br><br> I</td>
                  <td>
                    <div class="form-group form-group-bottom">
                      <div class="input-group" style="margin-bottom:2px;">
                        <div class="input-group-addon"></div>
                        <input  class="form-control required" autocomplete="off" value="<?php if(!empty($calibration_info)) {echo $calibration_info->i_edm_a_73;} ?>"  name="i_edm_a_73" type="text">
                      </div>
                      <div class="input-group" style="margin-bottom:2px;">
                        <div class="input-group-addon"></div>
                        <input  class="form-control required" autocomplete="off" value="<?php if(!empty($calibration_info)) {echo $calibration_info->i_edm_a_74;} ?>"  name="i_edm_a_74" type="text">
                      </div>
                      
                    </div>
                  </td>
                  <td><div class="form-group form-group-bottom">
                    <div class="input-group" style="margin-bottom:2px;">
                      <div class="input-group-addon"></div>
                      <input  class="form-control required" autocomplete="off" value="<?php if(!empty($calibration_info)) {echo $calibration_info->i_edm_a_75;} ?>"  name="i_edm_a_75" type="text">
                    </div>
                    <div class="input-group" style="margin-bottom:2px;">
                      <div class="input-group-addon"></div>
                      <input  class="form-control required" autocomplete="off" value="<?php if(!empty($calibration_info)) {echo $calibration_info->i_edm_a_76;} ?>"  name="i_edm_a_76" type="text">
                    </div>
                    
                  </div></td>
                  <td><div class="form-group form-group-bottom">
                    <div class="input-group" style="margin-bottom:2px;">
                      <div class="input-group-addon"></div>
                      <input  class="form-control required" autocomplete="off" value="<?php if(!empty($calibration_info)) {echo $calibration_info->i_edm_a_77;} ?>"  name="i_edm_a_77" type="text">
                    </div>
                    <div class="input-group" style="margin-bottom:2px;">
                      <div class="input-group-addon"></div>
                      <input  class="form-control required" autocomplete="off" value="<?php if(!empty($calibration_info)) {echo $calibration_info->i_edm_a_78;} ?>"  name="i_edm_a_78" type="text">
                    </div>
                    
                  </div></td>
                </tr>
                <tr>
                  <td class="text-center" style="font-weight:500;">11<br><br> 12</td>
                  <td class="text-center" style="font-weight:500;">2</td>
                  <td class="text-center" style="font-weight:500;">1<br><br> 2</td>
                  <td class="text-center" style="font-weight:500;"><br><br> 2</td>
                  <td class="text-center" style="font-weight:500;"><br><br> II</td>                  
                  <td>
                    <div class="form-group form-group-bottom">
                      <div class="input-group" style="margin-bottom:2px;">
                        <div class="input-group-addon"></div>
                        <input  class="form-control required" autocomplete="off" value="<?php if(!empty($calibration_info)) {echo $calibration_info->i_edm_a_79;} ?>"  name="i_edm_a_79" type="text">
                      </div>
                      <div class="input-group" style="margin-bottom:2px;">
                        <div class="input-group-addon"></div>
                        <input  class="form-control required" autocomplete="off" value="<?php if(!empty($calibration_info)) {echo $calibration_info->i_edm_a_80;} ?>"  name="i_edm_a_80" type="text">
                      </div>
                      
                    </div>
                  </td>
                  <td><div class="form-group form-group-bottom">
                    <div class="input-group" style="margin-bottom:2px;">
                      <div class="input-group-addon"></div>
                      <input  class="form-control required" autocomplete="off" value="<?php if(!empty($calibration_info)) {echo $calibration_info->i_edm_a_81;} ?>"  name="i_edm_a_81" type="text">
                    </div>
                    <div class="input-group" style="margin-bottom:2px;">
                      <div class="input-group-addon"></div>
                      <input  class="form-control required" autocomplete="off" value="<?php if(!empty($calibration_info)) {echo $calibration_info->i_edm_a_82;} ?>"  name="i_edm_a_82" type="text">
                    </div>
                    
                  </div></td>
                  <td><div class="form-group form-group-bottom">
                    <div class="input-group" style="margin-bottom:2px;">
                      <div class="input-group-addon"></div>
                      <input  class="form-control required" autocomplete="off" value="<?php if(!empty($calibration_info)) {echo $calibration_info->i_edm_a_83;} ?>"  name="i_edm_a_83" type="text">
                    </div>
                    <div class="input-group" style="margin-bottom:2px;">
                      <div class="input-group-addon"></div>
                      <input  class="form-control required" autocomplete="off" value="<?php if(!empty($calibration_info)) {echo $calibration_info->i_edm_a_84;} ?>"  name="i_edm_a_84" type="text">
                    </div>
                    
                  </div></td>
                </tr> 
                <tr>
                  <td class="text-center" style="font-weight:500;">13<br><br> 14</td>
                  <td class="text-center" style="font-weight:500;"></td>
                  <td class="text-center" style="font-weight:500;">1<br><br> 2</td>
                  <td class="text-center" style="font-weight:500;"><br><br> 3</td>
                  <td class="text-center" style="font-weight:500;"><br><br> I</td> 
                  <td>
                    <div class="form-group form-group-bottom">
                      <div class="input-group" style="margin-bottom:2px;">
                        <div class="input-group-addon"></div>
                        <input  class="form-control required" autocomplete="off" value="<?php if(!empty($calibration_info)) {echo $calibration_info->i_edm_a_85;} ?>"  name="i_edm_a_85" type="text">
                      </div>
                      <div class="input-group" style="margin-bottom:2px;">
                        <div class="input-group-addon"></div>
                        <input  class="form-control required" autocomplete="off" value="<?php if(!empty($calibration_info)) {echo $calibration_info->i_edm_a_86;} ?>"  name="i_edm_a_86" type="text">
                      </div>
                      
                    </div>
                  </td>
                  <td><div class="form-group form-group-bottom">
                    <div class="input-group" style="margin-bottom:2px;">
                      <div class="input-group-addon"></div>
                      <input  class="form-control required" autocomplete="off" value="<?php if(!empty($calibration_info)) {echo $calibration_info->i_edm_a_87;} ?>"  name="i_edm_a_87" type="text">
                    </div>
                    <div class="input-group" style="margin-bottom:2px;">
                      <div class="input-group-addon"></div>
                      <input  class="form-control required" autocomplete="off" value="<?php if(!empty($calibration_info)) {echo $calibration_info->i_edm_a_88;} ?>"  name="i_edm_a_88" type="text">
                    </div>
                    
                  </div></td>
                  <td><div class="form-group form-group-bottom">
                    <div class="input-group" style="margin-bottom:2px;">
                      <div class="input-group-addon"></div>
                      <input  class="form-control required" autocomplete="off" value="<?php if(!empty($calibration_info)) {echo $calibration_info->i_edm_a_89;} ?>"  name="i_edm_a_89" type="text">
                    </div>
                    <div class="input-group" style="margin-bottom:2px;">
                      <div class="input-group-addon"></div>
                      <input  class="form-control required" autocomplete="off" value="<?php if(!empty($calibration_info)) {echo $calibration_info->i_edm_a_90;} ?>"  name="i_edm_a_90" type="text">
                    </div>
                    
                  </div></td>
                </tr>
                <tr>
                  <td class="text-center" style="font-weight:500;">15<br><br> 16</td>
                  <td class="text-center" style="font-weight:500;"></td>
                  <td class="text-center" style="font-weight:500;">1<br><br> 2</td>
                  <td class="text-center" style="font-weight:500;"><br><br> 4</td>
                  <td class="text-center" style="font-weight:500;"><br><br> II</td> 
                  <td>
                    <div class="form-group form-group-bottom">
                      <div class="input-group" style="margin-bottom:2px;">
                        <div class="input-group-addon"></div>
                        <input  class="form-control required" autocomplete="off" value="<?php if(!empty($calibration_info)) {echo $calibration_info->i_edm_a_91;} ?>"  name="i_edm_a_91" type="text">
                      </div>
                      <div class="input-group" style="margin-bottom:2px;">
                        <div class="input-group-addon"></div>
                        <input  class="form-control required" autocomplete="off" value="<?php if(!empty($calibration_info)) {echo $calibration_info->i_edm_a_92;} ?>"  name="i_edm_a_92" type="text">
                      </div>
                      
                    </div>
                  </td>
                  <td><div class="form-group form-group-bottom">
                    <div class="input-group" style="margin-bottom:2px;">
                      <div class="input-group-addon"></div>
                      <input  class="form-control required" autocomplete="off" value="<?php if(!empty($calibration_info)) {echo $calibration_info->i_edm_a_93;} ?>"  name="i_edm_a_93" type="text">
                    </div>
                    <div class="input-group" style="margin-bottom:2px;">
                      <div class="input-group-addon"></div>
                      <input  class="form-control required" autocomplete="off" value="<?php if(!empty($calibration_info)) {echo $calibration_info->i_edm_a_94;} ?>"  name="i_edm_a_94" type="text">
                    </div>
                    
                  </div></td>
                  <td><div class="form-group form-group-bottom">
                    <div class="input-group" style="margin-bottom:2px;">
                      <div class="input-group-addon"></div>
                      <input  class="form-control required" autocomplete="off" value="<?php if(!empty($calibration_info)) {echo $calibration_info->i_edm_a_95;} ?>"  name="i_edm_a_95" type="text">
                    </div>
                    <div class="input-group" style="margin-bottom:2px;">
                      <div class="input-group-addon"></div>
                      <input  class="form-control required" autocomplete="off" value="<?php if(!empty($calibration_info)) {echo $calibration_info->i_edm_a_96;} ?>"  name="i_edm_a_96" type="text">
                    </div>
                    
                  </div></td>
                </tr>               
                
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
