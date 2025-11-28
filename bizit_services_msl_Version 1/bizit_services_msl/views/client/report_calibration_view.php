<?php defined('BASEPATH') or exit('No direct script access allowed'); ?>

<div class="mtop15 preview-top-wrapper">
  <div class="row">
    <div class="col-md-3">
      <div class="mbot30">
        <div class="invoice-html-logo">
          <?php echo get_dark_company_logo(); ?>
        </div>
      </div>
    </div>
    <div class="clearfix"></div>
  </div>
  <div class="top" data-sticky data-sticky-class="preview-sticky-header" style="z-index: 99;">
    <div class="container preview-sticky-container">
      <div class="sm:tw-flex tw-justify-between -tw-mx-4">
        <div class="sm:tw-self-end">
          <h3 class="bold tw-my-0 invoice-html-number">
            <span class="sticky-visible hide tw-mb-2">
             <?php echo get_option('service_request_prefix') . $service_info->service_request_code ?>
            </span>
          </h3>
          <span class="invoice-html-status">
            <span class="label label-success s-status invoice-status">VERIFIED</span>
          </span>
        </div>
        <div class="tw-flex tw-items-end tw-space-x-2 tw-mt-3 sm:tw-mt-0 hide">
          <?php echo form_open($this->uri->uri_string()); ?>
          <button type="submit" name="invoicepdf" value="invoicepdf" class="btn btn-default action-button">
            <i class='fa-regular fa-file-pdf'></i>
            <?php echo _l('clients_invoice_html_btn_download'); ?>
          </button>
          <?php echo form_close(); ?>
        </div>
      </div>
    </div>
  </div>
</div>
<div class="clearfix"></div>

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

  table {
    width: 100%;
    border-collapse: collapse;
    margin: 20px 0;
    font-size: 12px;
    text-align: left;
  }

  th,
  td {
    border: 1px solid #ddd;
    padding: 8px;
    text-align: center;
  }

  th {
    background-color: #f4f4f4;
    font-weight: bold;
  }

  td.empty-row {
    background-color: #f9f9f9;
  }

  tr:nth-child(even) {
    background-color: #f2f2f2;
  }

  tr:hover {
    background-color: #ddd;
  }
</style>
<div class="panel_s tw-mt-6">
  <div class="panel-body">
    <div id="details" class="clearfix">
      <div class="col-md-12">
        <h4>Service Request Report</h4>
        <hr class="hr-panel-heading" />
      </div>
      <div id="client" class="col-md-6">
        <span class="bold">CUSTOMER BILLING INFO:</span>
        <address>
          <span class="bold"><a href="#" target="_blank">
              <?php
              if ($service_request_client->show_primary_contact == 1) {
                $pc_id = get_primary_contact_user_id($service_info->clientid);
                if ($pc_id) {
                  echo get_contact_full_name($pc_id) . '<br />';
                }
              }
              echo $service_request_client->company; ?></a></span><br>
          <?php echo $service_request_client->billing_street; ?><br>
          <?php
          if (!empty($service_request_client->billing_city)) {
            echo $service_request_client->billing_city;
          }
          if (!empty($service_request_client->billing_state)) {
            echo ', ' . $service_request_client->billing_state;
          }
          $billing_country = get_country_short_name($service_request_client->billing_country);
          if (!empty($billing_country)) {
            echo '<br />' . $billing_country;
          }
          if (!empty($service_request_client->billing_zip)) {
            echo ', ' . $service_request_client->billing_zip;
          }
          if (!empty($service_request_client->vat)) {
            echo '<br /><b>' . _l('invoice_vat') . '</b>: ' . $service_request_client->vat;
          }
          // Check for customer custom fields which is checked show on pdf
          $pdf_custom_fields = get_custom_fields('customers', array('show_on_pdf' => 1));
          if (count($pdf_custom_fields) > 0) {
            echo '<br />';
            foreach ($pdf_custom_fields as $field) {
              $value = get_custom_field_value($service_info->clientid, $field['id'], 'customers');
              if ($value == '') {
                continue;
              }
              echo '<b>' . $field['name'] . '</b>: ' . $value . '<br />';
            }
          }
          ?>
        </address>

        <div class="bold" style="margin-top:20px;">INSTRUMENT INFO:</div>
        <div class="address"><b>Type:</b> <?php echo $service_info->item_type ?></div>
        <div class="address"><b>Make:</b> <?php echo $service_info->item_make ?></div>
        <div class="address"><b>Model:</b> <?php echo $service_info->item_model ?></div>
        <div class="email"><b>Serial:</b> <?php echo $service_info->serial_no ?></div>
      </div>
      <div id="invoice" class="col-md-6 text-right">
        <h4 class="bold"><a><?php echo get_option('service_request_prefix') . $service_info->service_request_code ?></a></h4>
        <div class="date"><b>Drop Off Date:</b> <?php echo _d($service_info->drop_off_date) ?></div>
        <div class="date"><b>Collection Date:</b> <?php echo _d($service_info->collection_date) ?></div>
        <div class="date"><b>Sales Person:</b> <?php echo get_staff_full_name($service_info->received_by) ?></div>
      </div>
    </div>

    <div class="clearfix mtop30"></div>
    <style type="text/css">
      table td.total {}

      table thead th {
        font-weight: bold;
      }
    </style>
    <?php if ($service_info->item_type == 'Level') { ?>
      <div class="form-section current">
        <b>INSTRUMENT INFORMATION REPORT ENTRY</b>
        <table class="table" id="serviceFields">
          <hr>
          <tbody>
            <tr class="form-inline">
              <td class="align-middle text-left" style="padding: 10px; border: 1px solid #ccc;">INSTRUMENT MAKE
                <div class="form-group">
                  <div class="input-group">
                    <input type="text" class="form-control required" autocomplete="on" placeholder="FOIF" id="_lv_v_a_1" name="lv_v_a_1" value="<?php if (!empty($calibration_info)) {
                                                                                                                                                  echo $calibration_info->lv_v_a_1;
                                                                                                                                                } ?>" required disabled>
                  </div>
                </div>
              </td>
              <td class="align-middle text-left" style="padding: 10px; border: 1px solid #ccc;">INSTRUMENT MODEL
                <div class="form-group">
                  <div class="input-group text-center">
                    <input type="text" class="form-control required" autocomplete="on" placeholder="AL132" name="lv_v_a_2" value="<?php if (!empty($calibration_info)) {
                                                                                                                                    echo $calibration_info->lv_v_a_2;
                                                                                                                                  } ?>" required disabled>
                  </div>
                </div>
              </td>
              <td class="align-middle text-left" style="padding: 10px; border: 1px solid #ccc;">INSTRUMENT SERIAL NO.
                <div class="form-group">
                  <div class="input-group text-center">
                    <input type="text" class="form-control required" autocomplete="on" placeholder="SGT57589" name="lv_v_a_3" value="<?php if (!empty($calibration_info)) {
                                                                                                                                        echo $calibration_info->lv_v_a_3;
                                                                                                                                      } ?>" required disabled>
                  </div>
                </div>
              </td>
              <td class="align-middle text-left" style="padding: 10px; border: 1px solid #ccc;">INSTRUMENT CONDITION
                <div class="form-group">
                  <div class="input-group text-center">
                    <input type="text" class="form-control required" autocomplete="on" placeholder="USED" name="lv_v_a_4" value="<?php if (!empty($calibration_info)) {
                                                                                                                                    echo $calibration_info->lv_v_a_4;
                                                                                                                                  } ?>" required disabled>
                  </div>
                </div>
              </td>
            </tr>
            <tr class="form-inline">
              <td class="align-middle text-left" style="padding: 10px; border: 1px solid #ccc;">TEST DISTANCE (M)
                <div class="form-group">
                  <div class="input-group text-center">
                    <input type="text" class="form-control required" autocomplete="on" placeholder="60" name="lv_v_a_5" value="<?php if (!empty($calibration_info)) {
                                                                                                                                  echo $calibration_info->lv_v_a_5;
                                                                                                                                } ?>" required disabled>
                  </div>
                </div>
              </td>
              <td class="align-middle text-left" style="padding: 10px; border: 1px solid #ccc;">MANUFACTURER ACCURACY (00'')
                <div class="form-group">
                  <div class="input-group text-center">
                    <input type="text" class="form-control required" autocomplete="on" placeholder="1.5" name="lv_v_a_6" value="<?php if (!empty($calibration_info)) {
                                                                                                                                  echo $calibration_info->lv_v_a_6;
                                                                                                                                } ?>" required disabled>
                  </div>
                </div>
              </td>
              <td class="align-middle text-left" style="padding: 10px; border: 1px solid #ccc;">WEATHER CONDITION
                <div class="form-group">
                  <div class="input-group text-center">
                    <input type="text" class="form-control required" autocomplete="on" placeholder="SUNNY" name="lv_v_a_7" value="<?php if (!empty($calibration_info)) {
                                                                                                                                    echo $calibration_info->lv_v_a_7;
                                                                                                                                  } ?>" required disabled>
                  </div>
                </div>
              </td>
              <td class="align-middle text-left" style="padding: 10px; border: 1px solid #ccc;">TEMPERATURE (°C)
                <div class="form-group">
                  <div class="input-group text-center">
                    <input type="text" class="form-control required" autocomplete="on" placeholder="20" name="lv_v_a_8" value="<?php if (!empty($calibration_info)) {
                                                                                                                                  echo $calibration_info->lv_v_a_8;
                                                                                                                                } ?>" required disabled>
                  </div>
                </div>
              </td>
            </tr>
            <tr class="form-inline">
              <td class="align-middle text-left" style="padding: 10px; border: 1px solid #ccc;">AIR PRESSURE (hPa)
                <div class="form-group">
                  <div class="input-group text-center">
                    <input type="text" class="form-control required" autocomplete="on" placeholder="101" name="lv_v_a_9" value="<?php if (!empty($calibration_info)) {
                                                                                                                                  echo $calibration_info->lv_v_a_9;
                                                                                                                                } ?>" required disabled>
                  </div>
                </div>
              </td>
            </tr>
          </tbody>
        </table>

        <b>PRE-CALIBRATION REPORT</b><br><br>
        <table class="table table-bordered table-striped dataTable">
          <thead>

            <tr>
              <th class="desc" style="text-align:center; font-weight:500;"></th>
              <th class="total" style="text-align:center; font-weight:500;">BACKSIGHT A</th>
              <th class="desc" style="text-align:center; font-weight:500;">FORESIGHT A</th>
              <th class="total" style="text-align:center; font-weight:500;">d1(mm) <br> Elevation (mm)</th>
              <th class="desc" style="text-align:center; font-weight:500;">Residual from <br> Mean (mm)</th>
              <th class="total" style="text-align:center; font-weight:500;">Squared <br> Residuals (mm)</th>
              <th class="total" style="text-align:center; font-weight:500;">BACKSIGHT B</th>
              <th class="desc" style="text-align:center; font-weight:500;">FORESIGHT B</th>
              <th class="total" style="text-align:center; font-weight:500;">d2(mm) <br> Elevation (mm)</th>
              <th class="desc" style="text-align:center; font-weight:500;">Residual from <br> Mean (mm)</th>
              <th class="total" style="text-align:center; font-weight:500;">Squared <br> Residuals (mm)</th>
            </tr>
          </thead>
          <tbody>
            <?php
            $i_backsight_a = $calibration_info->i_backsight_a;
            $i_foresight_b = $calibration_info->i_foresight_b;
            $i_backsight_c = $calibration_info->i_backsight_c;
            $i_foresight_d = $calibration_info->i_foresight_d;

            $ii_backsight_a = $calibration_info->ii_backsight_a;
            $ii_foresight_b = $calibration_info->ii_foresight_b;
            $ii_backsight_c = $calibration_info->ii_backsight_c;
            $ii_foresight_d = $calibration_info->ii_foresight_d;

            $iii_backsight_a = $calibration_info->iii_backsight_a;
            $iii_foresight_b = $calibration_info->iii_foresight_b;
            $iii_backsight_c = $calibration_info->iii_backsight_c;
            $iii_foresight_d = $calibration_info->iii_foresight_d;

            $iv_backsight_a = $calibration_info->iv_backsight_a;
            $iv_foresight_b = $calibration_info->iv_foresight_b;
            $iv_backsight_c = $calibration_info->iv_backsight_c;
            $iv_foresight_d = $calibration_info->iv_foresight_d;

            $v_backsight_a = $calibration_info->v_backsight_a;
            $v_foresight_b = $calibration_info->v_foresight_b;
            $v_backsight_c = $calibration_info->v_backsight_c;
            $v_foresight_d = $calibration_info->v_foresight_d;

            $vi_backsight_a = $calibration_info->vi_backsight_a;
            $vi_foresight_b = $calibration_info->vi_foresight_b;
            $vi_backsight_c = $calibration_info->vi_backsight_c;
            $vi_foresight_d = $calibration_info->vi_foresight_d;

            $vii_backsight_a = $calibration_info->vii_backsight_a;
            $vii_foresight_b = $calibration_info->vii_foresight_b;
            $vii_backsight_c = $calibration_info->vii_backsight_c;
            $vii_foresight_d = $calibration_info->vii_foresight_d;

            $viii_backsight_a = $calibration_info->viii_backsight_a;
            $viii_foresight_b = $calibration_info->viii_foresight_b;
            $viii_backsight_c = $calibration_info->viii_backsight_c;
            $viii_foresight_d = $calibration_info->viii_foresight_d;

            $ix_backsight_a = $calibration_info->ix_backsight_a;
            $ix_foresight_b = $calibration_info->ix_foresight_b;
            $ix_backsight_c = $calibration_info->ix_backsight_c;
            $ix_foresight_d = $calibration_info->ix_foresight_d;

            $x_backsight_a = $calibration_info->x_backsight_a;
            $x_foresight_b = $calibration_info->x_foresight_b;
            $x_backsight_c = $calibration_info->x_backsight_c;
            $x_foresight_d = $calibration_info->x_foresight_d;



            $diff_i = $i_backsight_a - $i_foresight_b;
            $diff_ii = $i_backsight_c - $i_foresight_d;

            $diff_iii = $ii_backsight_a - $ii_foresight_b;
            $diff_iv = $ii_backsight_c - $ii_foresight_d;

            $diff_v = $iii_backsight_a - $iii_foresight_b;
            $diff_vi = $iii_backsight_c - $iii_foresight_d;

            $diff_vii = $iv_backsight_a - $iv_foresight_b;
            $diff_viii = $iv_backsight_c - $iv_foresight_d;

            $diff_ix = $v_backsight_a - $v_foresight_b;
            $diff_x = $v_backsight_c - $v_foresight_d;

            $diff_xi = $vi_backsight_a - $vi_foresight_b;
            $diff_xii = $vi_backsight_c - $vi_foresight_d;

            $diff_xiii = $vii_backsight_a - $vii_foresight_b;
            $diff_xiv = $vii_backsight_c - $vii_foresight_d;

            $diff_xv = $viii_backsight_a - $viii_foresight_b;
            $diff_xvi = $viii_backsight_c - $viii_foresight_d;

            $diff_xvii = $ix_backsight_a - $ix_foresight_b;
            $diff_xviii = $ix_backsight_c - $ix_foresight_d;

            $diff_xix = $x_backsight_a - $x_foresight_b;
            $diff_xx = $x_backsight_c - $x_foresight_d;

            // Calculate the sum of the specified diff variables
            $sumdiffs1 = $diff_i + $diff_iii + $diff_v + $diff_vii + $diff_ix + $diff_xi + $diff_xiii + $diff_xv + $diff_xvii + $diff_xix;
            $sumdiffs2 = $diff_ii + $diff_iv + $diff_vi + $diff_viii + $diff_x + $diff_xii + $diff_xiv + $diff_xvi + $diff_xviii + $diff_xx;

            $sum_a = $i_backsight_a + $ii_backsight_a + $iii_backsight_a + $iv_backsight_a + $v_backsight_a +
              $vi_backsight_a + $vii_backsight_a + $viii_backsight_a + $ix_backsight_a + $x_backsight_a;

            $sum_b = $i_foresight_b + $ii_foresight_b + $iii_foresight_b + $iv_foresight_b + $v_foresight_b +
              $vi_foresight_b + $vii_foresight_b + $viii_foresight_b + $ix_foresight_b + $x_foresight_b;

            $sum_c = $i_backsight_c + $ii_backsight_c + $iii_backsight_c + $iv_backsight_c + $v_backsight_c +
              $vi_backsight_c + $vii_backsight_c + $viii_backsight_c + $ix_backsight_c + $x_backsight_c;

            $sum_d = $i_foresight_d + $ii_foresight_d + $iii_foresight_d + $iv_foresight_d + $v_foresight_d +
              $vi_foresight_d + $vii_foresight_d + $viii_foresight_d + $ix_foresight_d + $x_foresight_d;

            // Calculate the result of E44 divided by 10
            $d1result = $sumdiffs1 / 10;
            $d2result = $sumdiffs2 / 10;

            // Calculate the result of E45 minus E34
            $d1elevationresult1 = $d1result - $diff_i;
            $d2elevationresult1 = $d2result - $diff_ii;
            $d3elevationresult1 = $d1result - $diff_iii;
            $d4elevationresult1 = $d2result - $diff_iv;

            // Calculate the result of E45 minus E34
            $d1elevationresult2 = $d1result - $diff_v;
            $d2elevationresult2 = $d2result - $diff_vi;
            $d3elevationresult2 = $d1result - $diff_vii;
            $d4elevationresult2 = $d2result - $diff_viii;

            // Calculate the result of E45 minus E34
            $d1elevationresult3 = $d1result - $diff_ix;
            $d2elevationresult3 = $d2result - $diff_x;
            $d3elevationresult3 = $d1result - $diff_xi;
            $d4elevationresult3 = $d2result - $diff_xii;

            // Calculate the result of E45 minus E34
            $d1elevationresult4 = $d1result - $diff_xiii;
            $d2elevationresult4 = $d2result - $diff_xiv;
            $d3elevationresult4 = $d1result - $diff_xv;
            $d4elevationresult4 = $d2result - $diff_xvi;

            // Calculate the result of E45 minus E34
            $d1elevationresult5 = $d1result - $diff_xvii;
            $d2elevationresult5 = $d2result - $diff_xviii;
            $d3elevationresult5 = $d1result - $diff_xix;
            $d4elevationresult5 = $d2result - $diff_xx;


            // Calculate the square of F34
            $srd1elevationresult1 = $d1elevationresult1 ** 2;
            $srd2elevationresult1 = $d2elevationresult1 ** 2;
            $srd3elevationresult1 = $d3elevationresult1 ** 2;
            $srd4elevationresult1 = $d4elevationresult1 ** 2;

            // Calculate the square of F34
            $srd1elevationresult2 = $d1elevationresult2 ** 2;
            $srd2elevationresult2 = $d2elevationresult2 ** 2;
            $srd3elevationresult2 = $d3elevationresult2 ** 2;
            $srd4elevationresult2 = $d4elevationresult2 ** 2;

            // Calculate the square of F34
            $srd1elevationresult3 = $d1elevationresult3 ** 2;
            $srd2elevationresult3 = $d2elevationresult3 ** 2;
            $srd3elevationresult3 = $d3elevationresult3 ** 2;
            $srd4elevationresult3 = $d4elevationresult3 ** 2;

            // Calculate the square of F34
            $srd1elevationresult4 = $d1elevationresult4 ** 2;
            $srd2elevationresult4 = $d2elevationresult4 ** 2;
            $srd3elevationresult4 = $d3elevationresult4 ** 2;
            $srd4elevationresult4 = $d4elevationresult4 ** 2;

            // Calculate the square of F34
            $srd1elevationresult5 = $d1elevationresult5 ** 2;
            $srd2elevationresult5 = $d2elevationresult5 ** 2;
            $srd3elevationresult5 = $d3elevationresult5 ** 2;
            $srd4elevationresult5 = $d4elevationresult5 ** 2;


            // Calculate the sum of the specified elevation results up to the fifth iteration
            $sumElevationResults =
              $d1elevationresult1 + $d3elevationresult1 +
              $d1elevationresult2 + $d3elevationresult2 +
              $d1elevationresult3 + $d3elevationresult3 +
              $d1elevationresult4 + $d3elevationresult4 +
              $d1elevationresult5 + $d3elevationresult5;

            $sumElevationResults2 =
              $d2elevationresult1 + $d4elevationresult1 +
              $d2elevationresult2 + $d4elevationresult2 +
              $d2elevationresult3 + $d4elevationresult3 +
              $d2elevationresult4 + $d4elevationresult4 +
              $d2elevationresult5 + $d4elevationresult5;

            // Calculate the sum of the specified elevation results up to the fifth iteration
            $sumsrdelevationresult =
              $srd1elevationresult1 + $srd3elevationresult1 +
              $srd1elevationresult2 + $srd3elevationresult2 +
              $srd1elevationresult3 + $srd3elevationresult3 +
              $srd1elevationresult4 + $srd3elevationresult4 +
              $srd1elevationresult5 + $srd3elevationresult5;

            $sumsrdelevationresult1 =
              $srd2elevationresult1 + $srd4elevationresult1 +
              $srd2elevationresult2 + $srd4elevationresult2 +
              $srd2elevationresult3 + $srd4elevationresult3 +
              $srd2elevationresult4 + $srd4elevationresult4 +
              $srd2elevationresult5 + $srd4elevationresult5;

            // Calculate the result of G92 divided by 9
            $result = $sumsrdelevationresult / 9;
            $roundedResult = round($result, 4);
            $result1 = $sumsrdelevationresult1 / 9;
            $roundedResult1 = round($result1, 4);


            $standardDeviation = $d1result - $d2result;


            $aceptablesd = 2.5 * $roundedResult;
            $aceptablesd = round($aceptablesd, 4);
            $aceptablesd1 = 2.5 * $roundedResult1;
            $aceptablesd1 = round($aceptablesd1, 4);

            // Perform the comparison
            $absolute = $standardDeviation < $aceptablesd;
            $absolute1 = $standardDeviation < $aceptablesd1;

            // Determine the background color based on the comparison result
            $backgroundColor = $absolute ? 'lightgreen' : '#FFCCCC'; // Green for true, faded red for false
            $backgroundColor1 = $absolute1 ? 'lightgreen' : '#FFCCCC'; // Green for true, faded red for false

            $elevationtestline1 = $sum_a - $sum_b;
            $elevationtestline2 = $sum_c - $sum_d;



            // Results for each condition
            $lresult1 = ($sumdiffs1 == $elevationtestline1 && $sumdiffs2 == $elevationtestline2) ? 'Pass' : 'Fail';
            $lresult2 = ($sumElevationResults == -0 && $sumElevationResults2 == 0) ? 'Pass' : 'Fail';
            $lresult3 = ($standardDeviation < $aceptablesd) ? 'Pass' : 'Fail';
            $lresult4 = ($standardDeviation < $aceptablesd1) ? 'Pass' : 'Fail';

            // Determine the row color based on results
            $rowColor = ($lresult1 == 'Pass' && $lresult3 == 'Pass' && $lresult4 == 'Pass') ? 'lightgreen' : 'lightcoral'; // lightcoral for faded red
            ?>

            <tr>
              <td class="desc" style="text-align:center; font-weight:500;">1</td>
              <td class="total" style="text-align:center;"><?php echo $i_backsight_a; ?></td>
              <td class="desc" style="text-align:center;"><?php echo $i_foresight_b; ?></td>
              <td class="total" style="text-align:center;"><?php echo $diff_i; ?></td>
              <td class="desc" style="text-align:center;"><?php echo round($d1elevationresult1, 4); ?></td>
              <td class="desc" style="text-align:center;"><?php echo round($srd1elevationresult1, 4); ?></td>
              <td class="total" style="text-align:center;"><?php echo $i_backsight_c; ?></td>
              <td class="desc" style="text-align:center;"><?php echo $i_foresight_d; ?></td>
              <td class="total" style="text-align:center;"><?php echo $diff_ii; ?></td>
              <td class="desc" style="text-align:center;"><?php echo round($d2elevationresult1, 4); ?></td>
              <td class="desc" style="text-align:center;"><?php echo round($srd2elevationresult1, 4); ?></td>

            </tr>
            <tr>
              <td class="desc" style="text-align:center; font-weight:500;">2</td>
              <td class="total" style="text-align:center;"><?php echo $ii_backsight_a; ?></td>
              <td class="desc" style="text-align:center;"><?php echo $ii_foresight_b; ?></td>
              <td class="total" style="text-align:center;"><?php echo $diff_iii; ?></td>
              <td class="desc" style="text-align:center;"><?php echo round($d3elevationresult1, 4); ?></td>
              <td class="desc" style="text-align:center;"><?php echo round($srd3elevationresult1, 4); ?></td>
              <td class="total" style="text-align:center;"><?php echo $ii_backsight_c; ?></td>
              <td class="desc" style="text-align:center;"><?php echo $ii_foresight_d; ?></td>
              <td class="total" style="text-align:center;"><?php echo $diff_iv; ?></td>
              <td class="desc" style="text-align:center;"><?php echo round($d4elevationresult1, 4); ?></td>
              <td class="desc" style="text-align:center;"><?php echo round($srd4elevationresult1, 4); ?></td>
            </tr>

            <tr>
              <td class="desc" style="text-align:center; font-weight:500;">3</td>
              <td class="total" style="text-align:center;"><?php echo $iii_backsight_a; ?></td>
              <td class="desc" style="text-align:center;"><?php echo $iii_foresight_b; ?></td>
              <td class="total" style="text-align:center;"><?php echo $diff_v; ?></td>
              <td class="desc" style="text-align:center;"><?php echo round($d1elevationresult2, 4); ?></td>
              <td class="desc" style="text-align:center;"><?php echo round($srd1elevationresult2, 4); ?></td>
              <td class="total" style="text-align:center;"><?php echo $iii_backsight_c; ?></td>
              <td class="desc" style="text-align:center;"><?php echo $iii_foresight_d; ?></td>
              <td class="total" style="text-align:center;"><?php echo $diff_vi; ?></td>
              <td class="desc" style="text-align:center;"><?php echo round($d2elevationresult2, 4); ?></td>
              <td class="desc" style="text-align:center;"><?php echo round($srd2elevationresult2, 4); ?></td>
            </tr>

            <tr>
              <td class="desc" style="text-align:center; font-weight:500;">4</td>
              <td class="total" style="text-align:center;"><?php echo $iv_backsight_a; ?></td>
              <td class="desc" style="text-align:center;"><?php echo $iv_foresight_b; ?></td>
              <td class="total" style="text-align:center;"><?php echo $diff_vii; ?></td>
              <td class="desc" style="text-align:center;"><?php echo round($d3elevationresult2, 4); ?></td>
              <td class="desc" style="text-align:center;"><?php echo round($srd3elevationresult2, 4); ?></td>
              <td class="total" style="text-align:center;"><?php echo $iv_backsight_c; ?></td>
              <td class="desc" style="text-align:center;"><?php echo $iv_foresight_d; ?></td>
              <td class="total" style="text-align:center;"><?php echo $diff_viii; ?></td>
              <td class="desc" style="text-align:center;"><?php echo round($d4elevationresult2, 4); ?></td>
              <td class="desc" style="text-align:center;"><?php echo round($srd4elevationresult2, 4); ?></td>
            </tr>

            <tr>
              <td class="desc" style="text-align:center; font-weight:500;">5</td>
              <td class="total" style="text-align:center;"><?php echo $v_backsight_a; ?></td>
              <td class="desc" style="text-align:center;"><?php echo $v_foresight_b; ?></td>
              <td class="total" style="text-align:center;"><?php echo $diff_ix; ?></td>
              <td class="desc" style="text-align:center;"><?php echo round($d1elevationresult3, 4); ?></td>
              <td class="desc" style="text-align:center;"><?php echo round($srd1elevationresult3, 4); ?></td>
              <td class="total" style="text-align:center;"><?php echo $v_backsight_c; ?></td>
              <td class="desc" style="text-align:center;"><?php echo $v_foresight_d; ?></td>
              <td class="total" style="text-align:center;"><?php echo $diff_x; ?></td>
              <td class="desc" style="text-align:center;"><?php echo round($d2elevationresult3, 4); ?></td>
              <td class="desc" style="text-align:center;"><?php echo round($srd2elevationresult3, 4); ?></td>
            </tr>

            <tr>
              <td class="desc" style="text-align:center; font-weight:500;">6</td>
              <td class="total" style="text-align:center;"><?php echo $vi_backsight_a; ?></td>
              <td class="desc" style="text-align:center;"><?php echo $vi_foresight_b; ?></td>
              <td class="total" style="text-align:center;"><?php echo $diff_xi; ?></td>
              <td class="desc" style="text-align:center;"><?php echo round($d3elevationresult3, 4); ?></td>
              <td class="desc" style="text-align:center;"><?php echo round($srd3elevationresult3, 4); ?></td>
              <td class="total" style="text-align:center;"><?php echo $vi_backsight_c; ?></td>
              <td class="desc" style="text-align:center;"><?php echo $vi_foresight_d; ?></td>
              <td class="total" style="text-align:center;"><?php echo $diff_xii; ?></td>
              <td class="desc" style="text-align:center;"><?php echo round($d4elevationresult3, 4); ?></td>
              <td class="desc" style="text-align:center;"><?php echo round($srd4elevationresult3, 4); ?></td>
            </tr>

            <tr>
              <td class="desc" style="text-align:center; font-weight:500;">7</td>
              <td class="total" style="text-align:center;"><?php echo $vii_backsight_a; ?></td>
              <td class="desc" style="text-align:center;"><?php echo $vii_foresight_b; ?></td>
              <td class="total" style="text-align:center;"><?php echo $diff_xiii; ?></td>
              <td class="desc" style="text-align:center;"><?php echo round($d1elevationresult4, 4); ?></td>
              <td class="desc" style="text-align:center;"><?php echo round($srd1elevationresult4, 4); ?></td>
              <td class="total" style="text-align:center;"><?php echo $vii_backsight_c; ?></td>
              <td class="desc" style="text-align:center;"><?php echo $vii_foresight_d; ?></td>
              <td class="total" style="text-align:center;"><?php echo $diff_xiv; ?></td>
              <td class="desc" style="text-align:center;"><?php echo round($d2elevationresult4, 4); ?></td>
              <td class="desc" style="text-align:center;"><?php echo round($srd2elevationresult4, 4); ?></td>
            </tr>

            <tr>
              <td class="desc" style="text-align:center; font-weight:500;">8</td>
              <td class="total" style="text-align:center;"><?php echo $viii_backsight_a; ?></td>
              <td class="desc" style="text-align:center;"><?php echo $viii_foresight_b; ?></td>
              <td class="total" style="text-align:center;"><?php echo $diff_xv; ?></td>
              <td class="desc" style="text-align:center;"><?php echo round($d3elevationresult4, 4); ?></td>
              <td class="desc" style="text-align:center;"><?php echo round($srd3elevationresult4, 4); ?></td>
              <td class="total" style="text-align:center;"><?php echo $viii_backsight_c; ?></td>
              <td class="desc" style="text-align:center;"><?php echo $viii_foresight_d; ?></td>
              <td class="total" style="text-align:center;"><?php echo $diff_xvi; ?></td>
              <td class="desc" style="text-align:center;"><?php echo round($d4elevationresult4, 4); ?></td>
              <td class="desc" style="text-align:center;"><?php echo round($srd4elevationresult4, 4); ?></td>
            </tr>

            <tr>
              <td class="desc" style="text-align:center; font-weight:500;">9</td>
              <td class="total" style="text-align:center;"><?php echo $ix_backsight_a; ?></td>
              <td class="desc" style="text-align:center;"><?php echo $ix_foresight_b; ?></td>
              <td class="total" style="text-align:center;"><?php echo $diff_xvii; ?></td>
              <td class="desc" style="text-align:center;"><?php echo round($d1elevationresult5, 4); ?></td>
              <td class="desc" style="text-align:center;"><?php echo round($srd1elevationresult5, 4); ?></td>
              <td class="total" style="text-align:center;"><?php echo $ix_backsight_c; ?></td>
              <td class="desc" style="text-align:center;"><?php echo $ix_foresight_d; ?></td>
              <td class="total" style="text-align:center;"><?php echo $diff_xviii; ?></td>
              <td class="desc" style="text-align:center;"><?php echo round($d2elevationresult5, 4); ?></td>
              <td class="desc" style="text-align:center;"><?php echo round($srd2elevationresult5, 4); ?></td>
            </tr>

            <tr>
              <td class="desc" style="text-align:center; font-weight:500;">10</td>
              <td class="total" style="text-align:center;"><?php echo $x_backsight_a; ?></td>
              <td class="desc" style="text-align:center;"><?php echo $x_foresight_b; ?></td>
              <td class="total" style="text-align:center;"><?php echo $diff_xix; ?></td>
              <td class="desc" style="text-align:center;"><?php echo round($d3elevationresult5, 4); ?></td>
              <td class="desc" style="text-align:center;"><?php echo round($srd3elevationresult5, 4); ?></td>
              <td class="total" style="text-align:center;"><?php echo $x_backsight_c; ?></td>
              <td class="desc" style="text-align:center;"><?php echo $x_foresight_d; ?></td>
              <td class="total" style="text-align:center;"><?php echo $diff_xx; ?></td>
              <td class="desc" style="text-align:center;"><?php echo round($d4elevationresult5, 4); ?></td>
              <td class="desc" style="text-align:center;"><?php echo round($srd4elevationresult5, 4); ?></td>
            </tr>
            <tr style="background-color: lightgrey;">
              <td><b>Sum</b></td>
              <td><?php echo $sum_a; ?></td>
              <td><?php echo $sum_b; ?></td>
              <td><?php echo $sumdiffs1; ?></td>
              <td><?php echo round($sumElevationResults, 4); ?></td>
              <td><?php echo round($sumsrdelevationresult, 4); ?></td>
              <td><?php echo $sum_c; ?></td>
              <td><?php echo $sum_d; ?></td>
              <td><?php echo $sumdiffs2; ?></td>
              <td><?php echo round($sumElevationResults2, 4); ?></td>
              <td><?php echo round($sumsrdelevationresult1, 4); ?></td>
            </tr>
            <tr style="background-color: lightgrey;">
              <td></td>
              <td><b>Mean</b></td>
              <td>d1</td>
              <td><?php echo $d1result; ?></td>
              <td></td>
              <td></td>
              <td></td>
              <td>d2</td>
              <td><?php echo $d2result; ?></td>
              <td></td>
              <td></td>
            </tr>
            <tr style="background-color: lightgrey;">
              <td></td>
              <td></td>
              <td></td>
              <td>Standard deviation (mm)</td>
              <td>s</td>
              <td><?php echo round($roundedResult, 2); ?></td>
              <td></td>
              <td></td>
              <td>Standard deviation (mm)</td>
              <td>s</td>
              <td><?php echo round($roundedResult1, 2); ?></td>
            </tr>
            <tr style="background-color: lightgrey;">
              <td></td>
              <td></td>
              <td></td>
              <td>Meaned Elevation differences (mm)</td>
              <td>d1-d2</td>
              <td><?php echo number_format($standardDeviation, 2, '.', ''); ?></td>
              <td></td>
              <td></td>
              <td>Meaned Elevation differences (mm)</td>
              <td>d1-d2</td>
              <td><?php echo number_format($standardDeviation, 2, '.', ''); ?></td>
            </tr>
            <tr style="background-color: lightgrey;">
              <td></td>
              <td></td>
              <td></td>
              <td>Acceptable Standard Deviation (mm)</td>
              <td>2.5 x s</td>
              <td><?php echo number_format($aceptablesd, 2, '.', ''); ?></td>
              <td></td>
              <td></td>
              <td>Acceptable Standard Deviation (mm)</td>
              <td>2.5 x s</td>
              <td><?php echo number_format($aceptablesd1, 2, '.', ''); ?></td>
            </tr>
            <tr style="background-color: lightgrey;">
              <td></td>
              <td></td>
              <td></td>
              <td>Absolute</td>
              <td>(d1-d2) &lt; 2.5*s</td>
              <td style="background-color: <?php echo $backgroundColor; ?>; font-weight: bold; color: <?php echo $absolute ? 'white' : 'black'; ?>;">
                <?php echo $absolute ? 'Passed' : 'Failed'; ?>
              </td>
              <td></td>
              <td></td>
              <td>Absolute</td>
              <td>(d1-d2) &lt; 2.5*s</td>
              <td style="background-color: <?php echo $backgroundColor1; ?>; font-weight: bold; color: <?php echo $absolute1 ? 'white' : 'black'; ?>;">
                <?php echo $absolute1 ? 'Passed' : 'Failed'; ?>
              </td>
            </tr>


          </tbody>
          <tfoot>

          </tfoot>
        </table>
      </div>
      <button type="button" class="btn btn-primary next-step">VIEW POST-CALIBRATION REPORT</button>
      <div>

        <div class="form-section">
          <b>POST-CALIBRATION REPORT</b><br><br>
          <table class="table table-bordered table-striped dataTable">
            <thead>

              <tr>
                <th class="desc" style="text-align:center; font-weight:500;"></th>
                <th class="total" style="text-align:center; font-weight:500;">BACKSIGHT A</th>
                <th class="desc" style="text-align:center; font-weight:500;">FORESIGHT A</th>
                <th class="total" style="text-align:center; font-weight:500;">d1(mm) <br> Elevation (mm)</th>
                <th class="desc" style="text-align:center; font-weight:500;">Residual from <br> Mean (mm)</th>
                <th class="total" style="text-align:center; font-weight:500;">Squared <br> Residuals (mm)</th>
                <th class="total" style="text-align:center; font-weight:500;">BACKSIGHT B</th>
                <th class="desc" style="text-align:center; font-weight:500;">FORESIGHT B</th>
                <th class="total" style="text-align:center; font-weight:500;">d2(mm) <br> Elevation (mm)</th>
                <th class="desc" style="text-align:center; font-weight:500;">Residual from <br> Mean (mm)</th>
                <th class="total" style="text-align:center; font-weight:500;">Squared <br> Residuals (mm)</th>
              </tr>
            </thead>
            <tbody>
              <?php
              $i_backsight_e = $calibration_info->i_backsight_e;
              $i_foresight_f = $calibration_info->i_foresight_f;
              $i_backsight_g = $calibration_info->i_backsight_g;
              $i_foresight_h = $calibration_info->i_foresight_h;

              $ii_backsight_e = $calibration_info->ii_backsight_e;
              $ii_foresight_f = $calibration_info->ii_foresight_f;
              $ii_backsight_g = $calibration_info->ii_backsight_g;
              $ii_foresight_h = $calibration_info->ii_foresight_h;

              $iii_backsight_e = $calibration_info->iii_backsight_e;
              $iii_foresight_f = $calibration_info->iii_foresight_f;
              $iii_backsight_g = $calibration_info->iii_backsight_g;
              $iii_foresight_h = $calibration_info->iii_foresight_h;

              $iv_backsight_e = $calibration_info->iv_backsight_e;
              $iv_foresight_f = $calibration_info->iv_foresight_f;
              $iv_backsight_g = $calibration_info->iv_backsight_g;
              $iv_foresight_h = $calibration_info->iv_foresight_h;

              $v_backsight_e = $calibration_info->v_backsight_e;
              $v_foresight_f = $calibration_info->v_foresight_f;
              $v_backsight_g = $calibration_info->v_backsight_g;
              $v_foresight_h = $calibration_info->v_foresight_h;

              $vi_backsight_e = $calibration_info->vi_backsight_e;
              $vi_foresight_f = $calibration_info->vi_foresight_f;
              $vi_backsight_g = $calibration_info->vi_backsight_g;
              $vi_foresight_h = $calibration_info->vi_foresight_h;

              $vii_backsight_e = $calibration_info->vii_backsight_e;
              $vii_foresight_f = $calibration_info->vii_foresight_f;
              $vii_backsight_g = $calibration_info->vii_backsight_g;
              $vii_foresight_h = $calibration_info->vii_foresight_h;

              $viii_backsight_e = $calibration_info->viii_backsight_e;
              $viii_foresight_f = $calibration_info->viii_foresight_f;
              $viii_backsight_g = $calibration_info->viii_backsight_g;
              $viii_foresight_h = $calibration_info->viii_foresight_h;

              $ix_backsight_e = $calibration_info->ix_backsight_e;
              $ix_foresight_f = $calibration_info->ix_foresight_f;
              $ix_backsight_g = $calibration_info->ix_backsight_g;
              $ix_foresight_h = $calibration_info->ix_foresight_h;

              $x_backsight_e = $calibration_info->x_backsight_e;
              $x_foresight_f = $calibration_info->x_foresight_f;
              $x_backsight_g = $calibration_info->x_backsight_g;
              $x_foresight_h = $calibration_info->x_foresight_h;

              $diff_1 = $i_backsight_e - $i_foresight_f;
              $diff_2 = $i_backsight_g - $i_foresight_h;

              $diff_3 = $ii_backsight_e - $ii_foresight_f;
              $diff_4 = $ii_backsight_g - $ii_foresight_h;

              $diff_5 = $iii_backsight_e - $iii_foresight_f;
              $diff_6 = $iii_backsight_g - $iii_foresight_h;

              $diff_7 = $iv_backsight_e - $iv_foresight_f;
              $diff_8 = $iv_backsight_g - $iv_foresight_h;

              $diff_9 = $v_backsight_e - $v_foresight_f;
              $diff_10 = $v_backsight_g - $v_foresight_h;

              $diff_11 = $vi_backsight_e - $vi_foresight_f;
              $diff_12 = $vi_backsight_g - $vi_foresight_h;

              $diff_13 = $vii_backsight_e - $vii_foresight_f;
              $diff_14 = $vii_backsight_g - $vii_foresight_h;

              $diff_15 = $viii_backsight_e - $viii_foresight_f;
              $diff_16 = $viii_backsight_g - $viii_foresight_h;

              $diff_17 = $ix_backsight_e - $ix_foresight_f;
              $diff_18 = $ix_backsight_g - $ix_foresight_h;

              $diff_19 = $x_backsight_e - $x_foresight_f;
              $diff_20 = $x_backsight_g - $x_foresight_h;


              // Calculate the sum of the specified diff variables
              $sumdiffs3 = $diff_1 + $diff_3 + $diff_5 + $diff_7 + $diff_9 + $diff_11 + $diff_13 + $diff_15 + $diff_17 + $diff_19;
              $sumdiffs4 = $diff_2 + $diff_4 + $diff_6 + $diff_8 + $diff_10 + $diff_12 + $diff_14 + $diff_16 + $diff_18 + $diff_20;

              $sum_e = $i_backsight_e + $ii_backsight_e + $iii_backsight_e + $iv_backsight_e + $v_backsight_e +
                $vi_backsight_e + $vii_backsight_e + $viii_backsight_e + $ix_backsight_e + $x_backsight_e;

              $sum_f = $i_foresight_f + $ii_foresight_f + $iii_foresight_f + $iv_foresight_f + $v_foresight_f +
                $vi_foresight_f + $vii_foresight_f + $viii_foresight_f + $ix_foresight_f + $x_foresight_f;

              $sum_g = $i_backsight_g + $ii_backsight_g + $iii_backsight_g + $iv_backsight_g + $v_backsight_g +
                $vi_backsight_g + $vii_backsight_g + $viii_backsight_g + $ix_backsight_g + $x_backsight_g;

              $sum_h = $i_foresight_h + $ii_foresight_h + $iii_foresight_h + $iv_foresight_h + $v_foresight_h +
                $vi_foresight_h + $vii_foresight_h + $viii_foresight_h + $ix_foresight_h + $x_foresight_h;


              // Calculate the result of E44 divided by 10
              $d3result = $sumdiffs3 / 10;
              $d4result = $sumdiffs4 / 10;

              // Calculate the result of E45 minus E34
              $d1elevationresult6 = $d3result - $diff_1;
              $d2elevationresult6 = $d4result - $diff_2;
              $d3elevationresult6 = $d3result - $diff_3;
              $d4elevationresult6 = $d4result - $diff_4;

              $d1elevationresult7 = $d3result - $diff_5;
              $d2elevationresult7 = $d4result - $diff_6;
              $d3elevationresult7 = $d3result - $diff_7;
              $d4elevationresult7 = $d4result - $diff_8;

              $d1elevationresult8 = $d3result - $diff_9;
              $d2elevationresult8 = $d4result - $diff_10;
              $d3elevationresult8 = $d3result - $diff_11;
              $d4elevationresult8 = $d4result - $diff_12;

              $d1elevationresult9 = $d3result - $diff_13;
              $d2elevationresult9 = $d4result - $diff_14;
              $d3elevationresult9 = $d3result - $diff_15;
              $d4elevationresult9 = $d4result - $diff_16;

              $d1elevationresult10 = $d3result - $diff_17;
              $d2elevationresult10 = $d4result - $diff_18;
              $d3elevationresult10 = $d3result - $diff_19;
              $d4elevationresult10 = $d4result - $diff_20;

              // Calculate the square of F34
              $srd1elevationresult6 = $d1elevationresult6 ** 2;
              $srd2elevationresult6 = $d2elevationresult6 ** 2;
              $srd3elevationresult6 = $d3elevationresult6 ** 2;
              $srd4elevationresult6 = $d4elevationresult6 ** 2;

              // Calculate the square of F34
              $srd1elevationresult7 = $d1elevationresult7 ** 2;
              $srd2elevationresult7 = $d2elevationresult7 ** 2;
              $srd3elevationresult7 = $d3elevationresult7 ** 2;
              $srd4elevationresult7 = $d4elevationresult7 ** 2;

              // Calculate the square of F34
              $srd1elevationresult8 = $d1elevationresult8 ** 2;
              $srd2elevationresult8 = $d2elevationresult8 ** 2;
              $srd3elevationresult8 = $d3elevationresult8 ** 2;
              $srd4elevationresult8 = $d4elevationresult8 ** 2;

              // Calculate the square of F34
              $srd1elevationresult9 = $d1elevationresult9 ** 2;
              $srd2elevationresult9 = $d2elevationresult9 ** 2;
              $srd3elevationresult9 = $d3elevationresult9 ** 2;
              $srd4elevationresult9 = $d4elevationresult9 ** 2;

              // Calculate the square of F34
              $srd1elevationresult10 = $d1elevationresult10 ** 2;
              $srd2elevationresult10 = $d2elevationresult10 ** 2;
              $srd3elevationresult10 = $d3elevationresult10 ** 2;
              $srd4elevationresult10 = $d4elevationresult10 ** 2;



              // Calculate the sum of the specified elevation results up to the fifth iteration
              $sumElevationResults3 =
                $d1elevationresult6 + $d3elevationresult6 +
                $d1elevationresult7 + $d3elevationresult7 +
                $d1elevationresult8 + $d3elevationresult8 +
                $d1elevationresult9 + $d3elevationresult9 +
                $d1elevationresult10 + $d3elevationresult10;

              $sumElevationResults4 =
                $d2elevationresult6 + $d4elevationresult6 +
                $d2elevationresult7 + $d4elevationresult7 +
                $d2elevationresult8 + $d4elevationresult8 +
                $d2elevationresult9 + $d4elevationresult9 +
                $d2elevationresult10 + $d4elevationresult10;

              // Calculate the sum of the specified elevation results up to the fifth iteration
              $sumsrdelevationresult3 =
                $srd1elevationresult6 + $srd3elevationresult6 +
                $srd1elevationresult7 + $srd3elevationresult7 +
                $srd1elevationresult8 + $srd3elevationresult8 +
                $srd1elevationresult9 + $srd3elevationresult9 +
                $srd1elevationresult10 + $srd3elevationresult10;

              $sumsrdelevationresult4 =
                $srd2elevationresult6 + $srd4elevationresult6 +
                $srd2elevationresult7 + $srd4elevationresult7 +
                $srd2elevationresult8 + $srd4elevationresult8 +
                $srd2elevationresult9 + $srd4elevationresult9 +
                $srd2elevationresult10 + $srd4elevationresult10;

              // Calculate the result of G92 divided by 9
              $result3 = $sumsrdelevationresult3 / 9;
              $roundedResult3 = round($result3, 4);
              $result4 = $sumsrdelevationresult4 / 9;
              $roundedResult4 = round($result4, 4);


              $standardDeviation2 = $d3result - $d4result;


              $aceptablesd3 = 2.5 * $roundedResult3;
              // $aceptablesd3 = pow(2.5, $roundedResult3);
              $aceptablesd3 = round($aceptablesd3, 4);
              // $aceptablesd4 = pow(2.5, $roundedResult4);
              $aceptablesd4 = 2.5 * $roundedResult4;
              $aceptablesd4 = round($aceptablesd4, 4);

              // Perform the comparison
              $absolute3 = $standardDeviation2 < $aceptablesd3;
              $absolute4 = $standardDeviation2 < $aceptablesd4;

              // Determine the background color based on the comparison result
              $backgroundColor3 = $absolute3 ? 'lightgreen' : '#FFCCCC'; // Green for true, faded red for false
              $backgroundColor4 = $absolute4 ? 'lightgreen' : '#FFCCCC'; // Green for true, faded red for false

              $elevationtestline3 = $sum_e - $sum_f;
              $elevationtestline4 = $sum_g - $sum_h;



              // Results for each condition
              $lresult5 = ($sumdiffs3 == $elevationtestline3 && $sumdiffs4 == $elevationtestline4) ? 'Pass' : 'Fail';
              $lresult6 = ($sumElevationResults3 == 0 && $sumElevationResults4 == 0) ? 'Pass' : 'Fail';
              $lresult7 = ($standardDeviation2 < $aceptablesd3) ? 'Pass' : 'Fail';
              $lresult8 = ($standardDeviation2 < $aceptablesd4) ? 'Pass' : 'Fail';

              // Determine the row color based on results
              $rowColor2 = ($lresult5 == 'Pass' && $lresult7 == 'Pass'  && $lresult8 == 'Pass') ? 'lightgreen' : 'lightcoral'; // lightcoral for faded red
              ?>

              <tr>
                <td class="desc" style="text-align:center; font-weight:500;">1</td>
                <td class="total" style="text-align:center;"><?php echo $i_backsight_e; ?></td>
                <td class="desc" style="text-align:center;"><?php echo $i_foresight_f; ?></td>
                <td class="total" style="text-align:center;"><?php echo $diff_1; ?></td>
                <td class="desc" style="text-align:center;"><?php echo round($d1elevationresult6, 4); ?></td>
                <td class="desc" style="text-align:center;"><?php echo round($srd1elevationresult6, 4); ?></td>
                <td class="total" style="text-align:center;"><?php echo $i_backsight_g; ?></td>
                <td class="desc" style="text-align:center;"><?php echo $i_foresight_h; ?></td>
                <td class="total" style="text-align:center;"><?php echo $diff_2; ?></td>
                <td class="desc" style="text-align:center;"><?php echo round($d2elevationresult6, 4); ?></td>
                <td class="desc" style="text-align:center;"><?php echo round($srd2elevationresult6, 4); ?></td>
              </tr>

              <tr>
                <td class="desc" style="text-align:center; font-weight:500;">2</td>
                <td class="total" style="text-align:center;"><?php echo $ii_backsight_e; ?></td>
                <td class="desc" style="text-align:center;"><?php echo $ii_foresight_f; ?></td>
                <td class="total" style="text-align:center;"><?php echo $diff_3; ?></td>
                <td class="desc" style="text-align:center;"><?php echo round($d3elevationresult6, 4); ?></td>
                <td class="desc" style="text-align:center;"><?php echo round($srd3elevationresult6, 4); ?></td>
                <td class="total" style="text-align:center;"><?php echo $ii_backsight_g; ?></td>
                <td class="desc" style="text-align:center;"><?php echo $ii_foresight_h; ?></td>
                <td class="total" style="text-align:center;"><?php echo $diff_4; ?></td>
                <td class="desc" style="text-align:center;"><?php echo round($d4elevationresult6, 4); ?></td>
                <td class="desc" style="text-align:center;"><?php echo round($srd4elevationresult6, 4); ?></td>
              </tr>

              <tr>
                <td class="desc" style="text-align:center; font-weight:500;">3</td>
                <td class="total" style="text-align:center;"><?php echo $iii_backsight_e; ?></td>
                <td class="desc" style="text-align:center;"><?php echo $iii_foresight_f; ?></td>
                <td class="total" style="text-align:center;"><?php echo $diff_5; ?></td>
                <td class="desc" style="text-align:center;"><?php echo round($d1elevationresult7, 4); ?></td>
                <td class="desc" style="text-align:center;"><?php echo round($srd1elevationresult7, 4); ?></td>
                <td class="total" style="text-align:center;"><?php echo $iii_backsight_g; ?></td>
                <td class="desc" style="text-align:center;"><?php echo $iii_foresight_h; ?></td>
                <td class="total" style="text-align:center;"><?php echo $diff_6; ?></td>
                <td class="desc" style="text-align:center;"><?php echo round($d2elevationresult7, 4); ?></td>
                <td class="desc" style="text-align:center;"><?php echo round($srd2elevationresult7, 4); ?></td>
              </tr>

              <tr>
                <td class="desc" style="text-align:center; font-weight:500;">4</td>
                <td class="total" style="text-align:center;"><?php echo $iv_backsight_e; ?></td>
                <td class="desc" style="text-align:center;"><?php echo $iv_foresight_f; ?></td>
                <td class="total" style="text-align:center;"><?php echo $diff_7; ?></td>
                <td class="desc" style="text-align:center;"><?php echo round($d3elevationresult7, 4); ?></td>
                <td class="desc" style="text-align:center;"><?php echo round($srd3elevationresult7, 4); ?></td>
                <td class="total" style="text-align:center;"><?php echo $iv_backsight_g; ?></td>
                <td class="desc" style="text-align:center;"><?php echo $iv_foresight_h; ?></td>
                <td class="total" style="text-align:center;"><?php echo $diff_8; ?></td>
                <td class="desc" style="text-align:center;"><?php echo round($d4elevationresult7, 4); ?></td>
                <td class="desc" style="text-align:center;"><?php echo round($srd4elevationresult7, 4); ?></td>
              </tr>

              <tr>
                <td class="desc" style="text-align:center; font-weight:500;">5</td>
                <td class="total" style="text-align:center;"><?php echo $v_backsight_e; ?></td>
                <td class="desc" style="text-align:center;"><?php echo $v_foresight_f; ?></td>
                <td class="total" style="text-align:center;"><?php echo $diff_9; ?></td>
                <td class="desc" style="text-align:center;"><?php echo round($d1elevationresult8, 4); ?></td>
                <td class="desc" style="text-align:center;"><?php echo round($srd1elevationresult8, 4); ?></td>
                <td class="total" style="text-align:center;"><?php echo $v_backsight_g; ?></td>
                <td class="desc" style="text-align:center;"><?php echo $v_foresight_h; ?></td>
                <td class="total" style="text-align:center;"><?php echo $diff_10; ?></td>
                <td class="desc" style="text-align:center;"><?php echo round($d2elevationresult8, 4); ?></td>
                <td class="desc" style="text-align:center;"><?php echo round($srd2elevationresult8, 4); ?></td>
              </tr>
              <tr>
                <td class="desc" style="text-align:center; font-weight:500;">6</td>
                <td class="total" style="text-align:center;"><?php echo $vi_backsight_e; ?></td>
                <td class="desc" style="text-align:center;"><?php echo $vi_foresight_f; ?></td>
                <td class="total" style="text-align:center;"><?php echo $diff_11; ?></td>
                <td class="desc" style="text-align:center;"><?php echo round($d3elevationresult8, 4); ?></td>
                <td class="desc" style="text-align:center;"><?php echo round($srd3elevationresult8, 4); ?></td>
                <td class="total" style="text-align:center;"><?php echo $vi_backsight_g; ?></td>
                <td class="desc" style="text-align:center;"><?php echo $vi_foresight_h; ?></td>
                <td class="total" style="text-align:center;"><?php echo $diff_12; ?></td>
                <td class="desc" style="text-align:center;"><?php echo round($d4elevationresult8, 4); ?></td>
                <td class="desc" style="text-align:center;"><?php echo round($srd4elevationresult8, 4); ?></td>
              </tr>
              <tr>
                <td class="desc" style="text-align:center; font-weight:500;">7</td>
                <td class="total" style="text-align:center;"><?php echo $vii_backsight_e; ?></td>
                <td class="desc" style="text-align:center;"><?php echo $vii_foresight_f; ?></td>
                <td class="total" style="text-align:center;"><?php echo $diff_13; ?></td>
                <td class="desc" style="text-align:center;"><?php echo round($d1elevationresult9, 4); ?></td>
                <td class="desc" style="text-align:center;"><?php echo round($srd1elevationresult9, 4); ?></td>
                <td class="total" style="text-align:center;"><?php echo $vii_backsight_g; ?></td>
                <td class="desc" style="text-align:center;"><?php echo $vii_foresight_h; ?></td>
                <td class="total" style="text-align:center;"><?php echo $diff_14; ?></td>
                <td class="desc" style="text-align:center;"><?php echo round($d2elevationresult9, 4); ?></td>
                <td class="desc" style="text-align:center;"><?php echo round($srd2elevationresult9, 4); ?></td>
              </tr>
              <tr>
                <td class="desc" style="text-align:center; font-weight:500;">8</td>
                <td class="total" style="text-align:center;"><?php echo $viii_backsight_e; ?></td>
                <td class="desc" style="text-align:center;"><?php echo $viii_foresight_f; ?></td>
                <td class="total" style="text-align:center;"><?php echo $diff_15; ?></td>
                <td class="desc" style="text-align:center;"><?php echo round($d3elevationresult9, 4); ?></td>
                <td class="desc" style="text-align:center;"><?php echo round($srd3elevationresult9, 4); ?></td>
                <td class="total" style="text-align:center;"><?php echo $viii_backsight_g; ?></td>
                <td class="desc" style="text-align:center;"><?php echo $viii_foresight_h; ?></td>
                <td class="total" style="text-align:center;"><?php echo $diff_16; ?></td>
                <td class="desc" style="text-align:center;"><?php echo round($d4elevationresult9, 4); ?></td>
                <td class="desc" style="text-align:center;"><?php echo round($srd4elevationresult9, 4); ?></td>
              </tr>
              <tr>
                <td class="desc" style="text-align:center; font-weight:500;">9</td>
                <td class="total" style="text-align:center;"><?php echo $ix_backsight_e; ?></td>
                <td class="desc" style="text-align:center;"><?php echo $ix_foresight_f; ?></td>
                <td class="total" style="text-align:center;"><?php echo $diff_17; ?></td>
                <td class="desc" style="text-align:center;"><?php echo round($d1elevationresult10, 4); ?></td>
                <td class="desc" style="text-align:center;"><?php echo round($srd1elevationresult10, 4); ?></td>
                <td class="total" style="text-align:center;"><?php echo $ix_backsight_g; ?></td>
                <td class="desc" style="text-align:center;"><?php echo $ix_foresight_h; ?></td>
                <td class="total" style="text-align:center;"><?php echo $diff_18; ?></td>
                <td class="desc" style="text-align:center;"><?php echo round($d2elevationresult10, 4); ?></td>
                <td class="desc" style="text-align:center;"><?php echo round($srd2elevationresult10, 4); ?></td>
              </tr>
              <tr>
                <td class="desc" style="text-align:center; font-weight:500;">10</td>
                <td class="total" style="text-align:center;"><?php echo $x_backsight_e; ?></td>
                <td class="desc" style="text-align:center;"><?php echo $x_foresight_f; ?></td>
                <td class="total" style="text-align:center;"><?php echo $diff_19; ?></td>
                <td class="desc" style="text-align:center;"><?php echo round($d3elevationresult10, 4); ?></td>
                <td class="desc" style="text-align:center;"><?php echo round($srd3elevationresult10, 4); ?></td>
                <td class="total" style="text-align:center;"><?php echo $x_backsight_g; ?></td>
                <td class="desc" style="text-align:center;"><?php echo $x_foresight_h; ?></td>
                <td class="total" style="text-align:center;"><?php echo $diff_20; ?></td>
                <td class="desc" style="text-align:center;"><?php echo round($d4elevationresult10, 4); ?></td>
                <td class="desc" style="text-align:center;"><?php echo round($srd4elevationresult10, 4); ?></td>
              </tr>

              <tr style="background-color: lightgrey;">
                <td><b>Sum</b></td>
                <td><?php echo $sum_e; ?></td>
                <td><?php echo $sum_f; ?></td>
                <td><?php echo $sumdiffs3; ?></td>
                <td><?php echo round($sumElevationResults3, 4); ?></td>
                <td><?php echo round($sumsrdelevationresult3, 4); ?></td>
                <td><?php echo $sum_g; ?></td>
                <td><?php echo $sum_h; ?></td>
                <td><?php echo $sumdiffs4; ?></td>
                <td><?php echo round($sumElevationResults4, 4); ?></td>
                <td><?php echo round($sumsrdelevationresult4, 4); ?></td>
              </tr>
              <tr style="background-color: lightgrey;">
                <td></td>
                <td><b>Mean</b></td>
                <td>d1</td>
                <td><?php echo $d3result; ?></td>
                <td></td>
                <td></td>
                <td></td>
                <td>d2</td>
                <td><?php echo $d4result; ?></td>
                <td></td>
                <td></td>
              </tr>
              <tr style="background-color: lightgrey;">
                <td></td>
                <td></td>
                <td></td>
                <td>Standard deviation (mm)</td>
                <td>s</td>
                <td><?php echo round($roundedResult3, 2); ?></td>
                <td></td>
                <td></td>
                <td>Standard deviation (mm)</td>
                <td>s</td>
                <td><?php echo round($roundedResult4, 2); ?></td>
              </tr>
              <tr style="background-color: lightgrey;">
                <td></td>
                <td></td>
                <td></td>
                <td>Meaned Elevation differences (mm)</td>
                <td>d1-d2</td>
                <td><?php echo number_format($standardDeviation2, 2, '.', ''); ?></td>
                <td></td>
                <td></td>
                <td>Meaned Elevation differences (mm)</td>
                <td>d1-d2</td>
                <td><?php echo number_format($standardDeviation2, 2, '.', ''); ?></td>
              </tr>
              <tr style="background-color: lightgrey;">
                <td></td>
                <td></td>
                <td></td>
                <td>Acceptable Standard Deviation (mm)</td>
                <td>2.5 x s</td>
                <td><?php echo number_format($aceptablesd3, 2, '.', ''); ?></td>
                <td></td>
                <td></td>
                <td>Acceptable Standard Deviation (mm)</td>
                <td>2.5 x s</td>
                <td><?php echo number_format($aceptablesd4, 2, '.', ''); ?></td>
              </tr>
              <tr style="background-color: lightgrey;">
                <td></td>
                <td></td>
                <td></td>
                <td>Absolute</td>
                <td>(d1-d2) &lt; 2.5*s</td>
                <td style="background-color: <?php echo $backgroundColor3; ?>; font-weight: bold; color: <?php echo $absolute3 ? 'white' : 'black'; ?>;">
                  <?php echo $absolute3 ? 'Passed' : 'Failed'; ?>
                </td>
                <td></td>
                <td></td>
                <td>Absolute</td>
                <td>(d1-d2) &lt; 2.5*s</td>
                <td style="background-color: <?php echo $backgroundColor4; ?>; font-weight: bold; color: <?php echo $absolute4 ? 'white' : 'black'; ?>;">
                  <?php echo $absolute4 ? 'Passed' : 'Failed'; ?>
                </td>
              </tr>
            </tbody>
            <tfoot>

            </tfoot>
          </table>
        </div>
        <button type="button" class="btn btn-secondary prev-step">VIEW PRE-CALIBRATION REPORT</button>
      </div>
    <?php } else if ($service_info->item_type == 'Total Station') { ?>
      <div class="form-section current">
        <b>INSTRUMENT INFORMATION</b>
        <table class="table" id="serviceFields">
          <hr>
          <tbody>
            <tr class="form-inline">
              <td class="align-middle text-left" style="padding: 10px; border: 1px solid #ccc;">INSTRUMENT MAKE
                <div class="form-group">
                  <div class="input-group">
                    <input type="text" class="form-control required" autocomplete="on" placeholder="FOIF" id="_t_v_a_1" name="t_v_a_1" value="<?php if (!empty($calibration_info)) {
                                                                                                                                                echo $calibration_info->t_v_a_1;
                                                                                                                                              } ?>" disabled>
                  </div>
                </div>
              </td>
              <td class="align-middle text-left" style="padding: 10px; border: 1px solid #ccc;">INSTRUMENT MODEL
                <div class="form-group">
                  <div class="input-group text-center">
                    <input type="text" class="form-control required" autocomplete="on" placeholder="AL132" name="t_v_a_2" value="<?php if (!empty($calibration_info)) {
                                                                                                                                    echo $calibration_info->t_v_a_2;
                                                                                                                                  } ?>" disabled>
                  </div>
                </div>
              </td>
              <td class="align-middle text-left" style="padding: 10px; border: 1px solid #ccc;">INSTRUMENT SERIAL NO.
                <div class="form-group">
                  <div class="input-group text-center">
                    <input type="text" class="form-control required" autocomplete="on" placeholder="Z6692H99" name="t_v_a_3" value="<?php if (!empty($calibration_info)) {
                                                                                                                                      echo $calibration_info->t_v_a_3;
                                                                                                                                    } ?>" disabled>
                  </div>
                </div>
              </td>
              <td class="align-middle text-left" style="padding: 10px; border: 1px solid #ccc;">INSTRUMENT CONDITION
                <div class="form-group">
                  <div class="input-group text-center">
                    <input type="text" class="form-control required" autocomplete="on" placeholder="USED" name="t_v_a_4" value="<?php if (!empty($calibration_info)) {
                                                                                                                                  echo $calibration_info->t_v_a_4;
                                                                                                                                } ?>" disabled>
                  </div>
                </div>
              </td>
            </tr>
            <tr class="form-inline">
              <td class="align-middle text-left" style="padding: 10px; border: 1px solid #ccc;">BASELINE TEST DISTANCE (M)
                <div class="form-group">
                  <div class="input-group text-center">
                    <input type="text" class="form-control required" autocomplete="on" placeholder="20" name="t_v_a_5" value="<?php if (!empty($calibration_info)) {
                                                                                                                                echo $calibration_info->t_v_a_5;
                                                                                                                              } ?>" disabled>
                  </div>
                </div>
              </td>
              <td class="align-middle text-left" style="padding: 10px; border: 1px solid #ccc;">WEATHER CONDITION
                <div class="form-group">
                  <div class="input-group text-center">
                    <input type="text" class="form-control required" autocomplete="on" placeholder="CLOUDY" name="t_v_a_6" value="<?php if (!empty($calibration_info)) {
                                                                                                                                    echo $calibration_info->t_v_a_6;
                                                                                                                                  } ?>" disabled>
                  </div>
                </div>
              </td>
              <td class="align-middle text-left" style="padding: 10px; border: 1px solid #ccc;">TEMPERATURE (°C)
                <div class="form-group">
                  <div class="input-group text-center">
                    <input type="text" class="form-control required" autocomplete="on" placeholder="32" name="t_v_a_7" value="<?php if (!empty($calibration_info)) {
                                                                                                                                echo $calibration_info->t_v_a_7;
                                                                                                                              } ?>" disabled>
                  </div>
                </div>
              </td>
              <td class="align-middle text-left" style="padding: 10px; border: 1px solid #ccc;">AIR PRESSURE (hPa)
                <div class="form-group">
                  <div class="input-group text-center">
                    <input type="text" class="form-control required" autocomplete="on" placeholder="101" name="t_v_a_8" value="<?php if (!empty($calibration_info)) {
                                                                                                                                  echo $calibration_info->t_v_a_8;
                                                                                                                                } ?>" disabled>
                  </div>
                </div>
              </td>
            </tr>
            <tr class="form-inline">
              <td class="align-middle text-left" style="padding: 10px; border: 1px solid #ccc;">MANUFACTURER ANGLE ACCURACY (00'')
                <div class="form-group">
                  <div class="input-group text-center">
                    <input type="text" class="form-control required" autocomplete="on" placeholder="19.994" name="t_v_a_9" value="<?php if (!empty($calibration_info)) {
                                                                                                                                    echo $calibration_info->t_v_a_9;
                                                                                                                                  } ?>" disabled>
                  </div>
                </div>
              </td>
              <td class="align-middle text-left" style="padding: 10px; border: 1px solid #ccc;">MANUFACTURER EDM ACCURACY (MM)
                <div class="form-group">
                  <div class="input-group text-center">
                    <input type="text" class="form-control required" autocomplete="on" placeholder="0.028" name="t_v_a_10" value="<?php if (!empty($calibration_info)) {
                                                                                                                                    echo $calibration_info->t_v_a_10;
                                                                                                                                  } ?>" disabled>
                  </div>
                </div>
              </td>
              <td class="align-middle text-left" style="padding: 10px; border: 1px solid #ccc;">TARGET PRISM CONSTANT (MM)
                <div class="form-group">
                  <div class="input-group text-center">
                    <input type="text" class="form-control required" autocomplete="on" placeholder="15" name="t_v_a_11" value="<?php if (!empty($calibration_info)) {
                                                                                                                                  echo $calibration_info->t_v_a_11;
                                                                                                                                } ?>" disabled>
                  </div>
                </div>
              </td>
              <td colspan="2" class="align-middle text-left" style="padding: 10px; border: 1px solid #ccc;">TARGET PRISM POLE HEIGHT (M)
                <div class="form-group">
                  <div class="input-group text-center">
                    <input type="text" class="form-control required" autocomplete="on" placeholder="25" name="t_v_a_12" value="<?php if (!empty($calibration_info)) {
                                                                                                                                  echo $calibration_info->t_v_a_12;
                                                                                                                                } ?>" disabled>
                  </div>
                </div>
              </td>
            </tr>
            <tr class="form-inline">
              <td class="align-middle text-left" style="padding: 10px; border: 1px solid #ccc;">INSTRUMENT PRISM CONSTANT (MM)
                <div class="form-group">
                  <div class="input-group text-center">
                    <input type="text" class="form-control required" autocomplete="on" placeholder="2.2" name="t_v_a_13" value="<?php if (!empty($calibration_info)) {
                                                                                                                                  echo $calibration_info->t_v_a_13;
                                                                                                                                } ?>" disabled>
                  </div>
                </div>
              </td>
              <td class="align-middle text-left" style="padding: 10px; border: 1px solid #ccc;">INSTRUMENT HEIGHT (M)
                <div class="form-group">
                  <div class="input-group text-center">
                    <input type="text" class="form-control required" autocomplete="on" placeholder="2.2" name="t_v_a_14" value="<?php if (!empty($calibration_info)) {
                                                                                                                                  echo $calibration_info->t_v_a_14;
                                                                                                                                } ?>" disabled>
                  </div>
                </div>
              </td>
              <td class="align-middle text-left" style="padding: 10px; border: 1px solid #ccc;">TARGET MAKE
                <div class="form-group">
                  <div class="input-group text-center">
                    <input type="text" class="form-control required" autocomplete="on" placeholder="2.2" name="t_v_a_15" value="<?php if (!empty($calibration_info)) {
                                                                                                                                  echo $calibration_info->t_v_a_15;
                                                                                                                                } ?>" disabled>
                  </div>
                </div>
              </td>
              <td class="align-middle text-left" style="padding: 10px; border: 1px solid #ccc;">TARGET MODEL
                <div class="form-group">
                  <div class="input-group text-center">
                    <input type="text" class="form-control required" autocomplete="on" placeholder="2.2" name="t_v_a_16" value="<?php if (!empty($calibration_info)) {
                                                                                                                                  echo $calibration_info->t_v_a_16;
                                                                                                                                } ?>" disabled>
                  </div>
                </div>
              </td>
            </tr>
          </tbody>
        </table>
        <b>PRE-CALIBRATION INDEX ERROR CHECKS</b><br><br>
        <table class="table table-bordered table-striped dataTable">
          <thead>
            <tr>
              <th></th>
              <th colspan="2" style="text-align:center; font-weight:500;" class="total">HORIZONTAL CIRCLE INDEX ERROR</th>
              <th colspan="2" style="text-align:center; font-weight:500;">VERTICAL CIRCLE INDEX ERROR</th>
            </tr>
            <tr>
              <th class="desc" style="text-align:center; font-weight:500;">FACE</th>
              <th class="total" style="text-align:center; font-weight:500;">START A</th>
              <th class="total" style="text-align:center; font-weight:500;">END B</th>
              <th class="desc" style="text-align:center; font-weight:500;">START A</th>
              <th class="desc" style="text-align:center; font-weight:500;">END B</th>
            </tr>
          </thead>
          <tbody>
            <?php
            $i_h_a = $calibration_info->i_h_a;
            $ii_h_a = $calibration_info->ii_h_a;
            $sum_h_a = $i_h_a + $ii_h_a;
            $d_error_h_a = (dms2dec(180, 0, 0) - $sum_h_a);

            $i_h_b = $calibration_info->i_h_b;
            $ii_h_b = $calibration_info->ii_h_b;
            $sum_h_b = $i_h_b + $ii_h_b;
            $d_error_h_b = (dms2dec(180, 0, 0) - $sum_h_b);

            $i_v_a = $calibration_info->i_v_a;
            $ii_v_a = $calibration_info->ii_v_a;
            $sum_v_a = $i_v_a + $ii_v_a;
            $d_error_v_a = (dms2dec(360, 0, 0) - $sum_v_a);

            $i_v_b = $calibration_info->i_v_b;
            $ii_v_b = $calibration_info->ii_v_b;
            $sum_v_b = $i_v_b + $ii_v_b;
            $d_error_v_b = (dms2dec(360, 0, 0) - $sum_v_b);

            $d_error_v_b1_5 = $d_error_v_a / 2;

            $i_edm_a_1 = number_format(round($calibration_info->i_edm_a_1, 3), 3);
            $i_edm_a_2 = number_format(round($calibration_info->i_edm_a_2, 3), 3);
            $i_edm_a_3 = number_format(round($calibration_info->i_edm_a_3, 3), 3);
            $f_i_edm_a = ($i_edm_a_1 + $i_edm_a_2 + $i_edm_a_3) / 3;

            $i_edm_b_3 = number_format(round($calibration_info->i_edm_b_3, 3), 3);
            $i_edm_b_4 = number_format(round($calibration_info->i_edm_b_4, 3), 3);
            $i_edm_b_3 = number_format(round($calibration_info->i_edm_b_3, 3), 3);
            $f_i_edm_b = ($i_edm_b_1 + $i_edm_b_2 + $i_edm_b_3) / 3;

            $ii_edm_a_1 = number_format(round($calibration_info->ii_edm_a_1, 3), 3);
            $ii_edm_a_2 = number_format(round($calibration_info->ii_edm_a_2, 3), 3);
            $ii_edm_a_3 = number_format(round($calibration_info->ii_edm_a_3, 3), 3);
            $f_ii_edm_a = ($ii_edm_a_1 + $ii_edm_a_2 + $ii_edm_a_3) / 3;

            $ii_edm_b_1 = number_format(round($calibration_info->ii_edm_b_1, 3), 3);
            $ii_edm_b_2 = number_format(round($calibration_info->ii_edm_b_2, 3), 3);
            $ii_edm_b_3 = number_format(round($calibration_info->ii_edm_b_3, 3), 3);
            $f_ii_edm_b = ($ii_edm_b_1 + $ii_edm_b_2 + $ii_edm_b_3) / 3;

            $f_i_ii_edm_a = ($f_i_edm_a + $f_ii_edm_a) / 2;
            $f_i_ii_edm_b = ($f_i_edm_b + $f_ii_edm_b) / 2;

            $t_v_a_10 = $calibration_info->t_v_a_10;



            $result2 = '';

            // Check the condition and set the result accordingly
            if ($d_error_v_b1_5 > $t_v_a_10 && $d_error_v_b1_5 > $t_v_a_10) {
              // Fail case
              $result2 = '<td colspan="4" class="total" style="text-align:center; background-color: rgba(255, 0, 0, 0.3); color: black;"><b>FAILED</b></td>';
            } else {
              // Pass case
              $result2 = '<td colspan="4" class="total" style="text-align:center; background-color: rgba(0, 255, 0, 0.3); color: black;"><b>PASSED</b></td>';
            }


            ?>
            <tr>
              <td class="desc" style="text-align:center; font-weight:500;"><b>I</b></td>
              <td class="total" style="text-align:center;"><?php echo dec2dms_full($i_h_a); ?></td>
              <td class="total" style="text-align:center;"><?php echo dec2dms_full($i_h_b); ?></td>
              <td class="desc" style="text-align:center;"><?php echo dec2dms_full($i_v_a); ?></td>
              <td class="desc" style="text-align:center;"><?php echo dec2dms_full($i_v_b); ?></td>
            </tr>
            <tr>
              <td class="desc" style="text-align:center; font-weight:500;"><b>II</b></td>
              <td class="total" style="text-align:center;"><?php echo dec2dms_full($ii_h_a); ?></td>
              <td class="total" style="text-align:center;"><?php echo dec2dms_full($ii_h_b); ?></td>
              <td class="desc" style="text-align:center;"><?php echo dec2dms_full($ii_v_a); ?></td>
              <td class="desc" style="text-align:center;"><?php echo dec2dms_full($ii_v_b); ?></td>
            </tr>
            <tr>
              <td></td>
              <td colspan="2" class="text-center total"><b>Half Circle is 180&deg; 00' 00"</b></td>
              <td colspan="2" class="text-center"><b>Half Circle is 360&deg; 00' 00"</b></td>
            </tr>
            <tr>
              <td class="desc" style="text-align:center; font-weight:500;"><b>SUM(I+II)</b></td>
              <td class="total" style="text-align:center;"><?php echo dec2dms_full($sum_h_a); ?></td>
              <td class="total" style="text-align:center;"><?php echo dec2dms_full($sum_h_b); ?></td>
              <td class="desc" style="text-align:center;"><?php echo dec2dms_full($sum_v_a); ?></td>
              <td class="desc" style="text-align:center;"><?php echo dec2dms_full($sum_v_b); ?></td>
            </tr>
            <tr>
              <td></td>
              <td colspan="2" class="text-center total"><b>(180&deg; 00' 00" - SUM(A+B))/2</b></td>
              <td colspan="2" class="text-center"><b>(360&deg; 00' 00" - SUM(A+B))/2</b></td>
            </tr>
            <tr>
              <td class="desc" style="text-align:center; font-weight:500;"><b>DOUBLE ERROR</b></td>
              <td class="total" style="text-align:center;"><?php echo dec2dms_full($d_error_h_a); ?></td>
              <td class="total" style="text-align:center;"><?php echo dec2dms_full($d_error_h_b); ?></td>
              <td class="desc" style="text-align:center;"><?php echo dec2dms_full($d_error_v_a); ?></td>
              <td class="desc" style="text-align:center;"><?php echo dec2dms_full($d_error_v_b); ?></td>
            </tr>
            <tr>
              <td class="desc" style="text-align:center; font-weight:500;"><b>Index Error (Double error/2)</b></td>
              <td class="total" style="text-align:center;"><?php echo dec2dms_full($d_error_h_a); ?></td>
              <td class="total" style="text-align:center;"><?php echo dec2dms_full($d_error_h_b); ?></td>
              <td class="desc" style="text-align:center;"><?php echo dec2dms_full($d_error_v_b1_5); ?>;</td>
              <td class="desc" style="text-align:center;"><?php echo dec2dms_full($d_error_v_b); ?></td>
            </tr>
            <tr>
              <td class="desc" style="text-align:center; font-weight:500;"><b></b></td>
              <!-- Output the stored result here -->
              <?php echo $result2; ?>
            </tr>
          </tbody>
        </table><br>
        <b>PRE-CALIBRATION EDM CHECKS</b>
        <table class="table" id="serviceFields" borde>
          <thead>
            <tr>
              <th class="text-center" style="font-weight:500;"></th>
              <th class="text-center" style="font-weight:500;"></th>
              <th class="text-center" style="font-weight:500;"></th>
              <th class="text-center" style="font-weight:500;"></th>
              <th class="text-center" style="font-weight:500;"></th>
              <th class="text-center" style="font-weight:500;"></th>
              <th class="text-center" style="font-weight:500;">Residuals from Mean(mm)</th>
              <th class="text-center" style="font-weight:500;"></th>
              <th class="text-center" style="font-weight:500;">Squared meaned residuals(mm)</th>
              <th class="text-center" style="font-weight:500;"></th>



            </tr>
            <tr>
              <th class="text-center" style="font-weight:500;">FACE</th>
              <th class="text-center" style="font-weight:500;">X</th>
              <th class="text-center" style="font-weight:500;">Y</th>
              <th class="text-center" style="font-weight:500;">Z</th>
              <th class="text-center" style="font-weight:500;">Distance</th>
              <th class="text-center" style="font-weight:500;">Elevation</th>
              <th class="text-center" style="font-weight:500;">Distance</th>
              <th class="text-center" style="font-weight:500;">Elevation</th>
              <th class="text-center" style="font-weight:500;">Distance</th>
              <th class="text-center" style="font-weight:500;">Elevation</th>



            </tr>
          </thead>
          <tbody>
            <?php

            $i_edm_a_1 = $calibration_info->i_edm_a_1;
            $i_edm_a_2 = $calibration_info->i_edm_a_2;
            $i_edm_a_3 = $calibration_info->i_edm_a_3;
            $i_edm_a_4 = $calibration_info->i_edm_a_4;
            $i_edm_a_5 = $calibration_info->i_edm_a_5;
            $i_edm_a_6 = $calibration_info->i_edm_a_6;
            $i_edm_a_7 = $calibration_info->i_edm_a_7;
            $i_edm_a_8 = $calibration_info->i_edm_a_8;
            $i_edm_a_9 = $calibration_info->i_edm_a_9;
            $i_edm_a_10 = $calibration_info->i_edm_a_10;
            $i_edm_a_11 = $calibration_info->i_edm_a_11;
            $i_edm_a_12 = $calibration_info->i_edm_a_12;
            $i_edm_a_13 = $calibration_info->i_edm_a_13;
            $i_edm_a_14 = $calibration_info->i_edm_a_14;
            $i_edm_a_15 = $calibration_info->i_edm_a_15;
            $i_edm_a_16 = $calibration_info->i_edm_a_16;
            $i_edm_a_17 = $calibration_info->i_edm_a_17;
            $i_edm_a_18 = $calibration_info->i_edm_a_18;
            $i_edm_a_19 = $calibration_info->i_edm_a_19;
            $i_edm_a_20 = $calibration_info->i_edm_a_20;
            $i_edm_a_21 = $calibration_info->i_edm_a_21;
            $i_edm_a_22 = $calibration_info->i_edm_a_22;
            $i_edm_a_23 = $calibration_info->i_edm_a_23;
            $i_edm_a_24 = $calibration_info->i_edm_a_24;
            $i_edm_a_25 = $calibration_info->i_edm_a_25;
            $i_edm_a_26 = $calibration_info->i_edm_a_26;
            $i_edm_a_27 = $calibration_info->i_edm_a_27;
            $i_edm_a_28 = $calibration_info->i_edm_a_28;
            $i_edm_a_29 = $calibration_info->i_edm_a_29;
            $i_edm_a_30 = $calibration_info->i_edm_a_30;
            $i_edm_a_31 = $calibration_info->i_edm_a_31;
            $i_edm_a_32 = $calibration_info->i_edm_a_32;
            $i_edm_a_33 = $calibration_info->i_edm_a_33;
            $i_edm_a_34 = $calibration_info->i_edm_a_34;
            $i_edm_a_35 = $calibration_info->i_edm_a_35;
            $i_edm_a_36 = $calibration_info->i_edm_a_36;
            $i_edm_a_37 = $calibration_info->i_edm_a_37;
            $i_edm_a_38 = $calibration_info->i_edm_a_38;
            $i_edm_a_39 = $calibration_info->i_edm_a_39;
            $i_edm_a_40 = $calibration_info->i_edm_a_40;
            $i_edm_a_41 = $calibration_info->i_edm_a_41;
            $i_edm_a_42 = $calibration_info->i_edm_a_42;
            $i_edm_a_43 = $calibration_info->i_edm_a_43;
            $i_edm_a_44 = $calibration_info->i_edm_a_44;
            $i_edm_a_45 = $calibration_info->i_edm_a_45;
            $i_edm_a_46 = $calibration_info->i_edm_a_46;
            $i_edm_a_47 = $calibration_info->i_edm_a_47;
            $i_edm_a_48 = $calibration_info->i_edm_a_48;

            $t_v_a_10 = $calibration_info->t_v_a_10;




            $dresult1 = sqrt(pow(($i_edm_a_1 - $i_edm_a_2), 2) + pow(($i_edm_a_3 - $i_edm_a_4), 2));
            $dresult1 = number_format($dresult1, 4);
            $eresult1 = $i_edm_a_5 - $i_edm_a_6;

            $dresult2 = sqrt(pow(($i_edm_a_7 - $i_edm_a_8), 2) + pow(($i_edm_a_9 - $i_edm_a_10), 2));
            $dresult2 = number_format($dresult2, 4);
            $eresult2 = $i_edm_a_11 - $i_edm_a_12;

            $dresult3 = sqrt(pow(($i_edm_a_13 - $i_edm_a_14), 2) + pow(($i_edm_a_15 - $i_edm_a_16), 2));
            $dresult3 = number_format($dresult3, 4);
            $eresult3 = $i_edm_a_17 - $i_edm_a_18;

            $dresult4 = sqrt(pow(($i_edm_a_19 - $i_edm_a_20), 2) + pow(($i_edm_a_21 - $i_edm_a_22), 2));
            $dresult4 = number_format($dresult4, 4);
            $eresult4 = $i_edm_a_23 - $i_edm_a_24;

            $dresult5 = sqrt(pow(($i_edm_a_25 - $i_edm_a_26), 2) + pow(($i_edm_a_27 - $i_edm_a_28), 2));
            $dresult5 = number_format($dresult5, 4);
            $eresult5 = $i_edm_a_29 - $i_edm_a_30;

            $dresult6 = sqrt(pow(($i_edm_a_31 - $i_edm_a_32), 2) + pow(($i_edm_a_33 - $i_edm_a_34), 2));
            $dresult6 = number_format($dresult6, 4);
            $eresult6 = $i_edm_a_35 - $i_edm_a_36;

            $dresult7 = sqrt(pow(($i_edm_a_37 - $i_edm_a_38), 2) + pow(($i_edm_a_39 - $i_edm_a_40), 2));
            $dresult7 = number_format($dresult7, 4);
            $eresult7 = $i_edm_a_41 - $i_edm_a_42;

            $dresult8 = sqrt(pow(($i_edm_a_43 - $i_edm_a_44), 2) + pow(($i_edm_a_45 - $i_edm_a_46), 2));
            $dresult8 = number_format($dresult8, 4);
            $eresult8 = $i_edm_a_47 - $i_edm_a_48;

            $mean_total = ($dresult1 + $dresult2 + $dresult3 + $dresult4 + $dresult5 + $dresult6 + $dresult7 + $dresult8) / 8;
            $elevation_total = ($eresult1 + $eresult2 + $eresult3 + $eresult4 + $eresult5 + $eresult6 + $eresult7 + $eresult8) / 8;

            $mean_distance1 = ($dresult1 - $mean_total) * 1000;
            $mean_distance2 = ($dresult2 - $mean_total) * 1000;
            $mean_distance3 = ($dresult3 - $mean_total) * 1000;
            $mean_distance4 = ($dresult4 - $mean_total) * 1000;
            $mean_distance5 = ($dresult5 - $mean_total) * 1000;
            $mean_distance6 = ($dresult6 - $mean_total) * 1000;
            $mean_distance7 = ($dresult7 - $mean_total) * 1000;
            $mean_distance8 = ($dresult8 - $mean_total) * 1000;

            $elevation_distance1 = ($eresult1 - $elevation_total) * 1000;
            $elevation_distance2 = ($eresult2 - $elevation_total) * 1000;
            $elevation_distance3 = ($eresult3 - $elevation_total) * 1000;
            $elevation_distance4 = ($eresult4 - $elevation_total) * 1000;
            $elevation_distance5 = ($eresult5 - $elevation_total) * 1000;
            $elevation_distance6 = ($eresult6 - $elevation_total) * 1000;
            $elevation_distance7 = ($eresult7 - $elevation_total) * 1000;
            $elevation_distance8 = ($eresult8 - $elevation_total) * 1000;

            $standard_error1 = -$mean_distance1 / 2;
            $standard_error2 = -$elevation_distance6 / 2;

            $smdistance1 = pow($mean_distance1, 2);
            $smdistance2 = pow($mean_distance2, 2);
            $smdistance3 = pow($mean_distance3, 2);
            $smdistance4 = pow($mean_distance4, 2);
            $smdistance5 = pow($mean_distance5, 2);
            $smdistance6 = pow($mean_distance6, 2);
            $smdistance7 = pow($mean_distance7, 2);
            $smdistance8 = pow($mean_distance8, 2);

            $total_smdistance = $smdistance1 + $smdistance2 + $smdistance3 + $smdistance4 + $smdistance5 + $smdistance6 + $smdistance7 + $smdistance8;

            $smelevation1 = pow($elevation_distance1, 2);
            $smelevation2 = pow($elevation_distance2, 2);
            $smelevation3 = pow($elevation_distance3, 2);
            $smelevation4 = pow($elevation_distance4, 2);
            $smelevation5 = pow($elevation_distance5, 2);
            $smelevation6 = pow($elevation_distance6, 2);
            $smelevation7 = pow($elevation_distance7, 2);
            $smelevation8 = pow($elevation_distance8, 2);

            $total_smelevation = $smelevation1 + $smelevation2 + $smelevation3 + $smelevation4 + $smelevation5 + $smelevation6 + $smelevation7 + $smelevation8;

            $dfresult1 = $total_smdistance / 15;
            $dfresult2 = $total_smelevation / 15;

            ?>
            <tr>
              <td class="text-center" style="font-weight:500;">I</td>
              <td>
                <div class="form-group form-group-bottom">
                  <div class="input-group" style="margin-bottom:2px;">
                    <div class="input-group-addon"></div>
                    <input class="form-control required" autocomplete="off" value="<?php if (!empty($calibration_info)) {
                                                                                      echo $calibration_info->i_edm_a_1;
                                                                                    } ?>" name="i_edm_a_1" type="text" disabled>
                  </div>
                  <div class="input-group" style="margin-bottom:2px;">
                    <div class="input-group-addon"></div>
                    <input class="form-control required" autocomplete="off" value="<?php if (!empty($calibration_info)) {
                                                                                      echo $calibration_info->i_edm_a_2;
                                                                                    } ?>" name="i_edm_a_2" type="text" disabled>
                  </div>

                </div>
              </td>
              <td>
                <div class="form-group form-group-bottom">
                  <div class="input-group" style="margin-bottom:2px;">
                    <div class="input-group-addon"></div>
                    <input class="form-control required" autocomplete="off" value="<?php if (!empty($calibration_info)) {
                                                                                      echo $calibration_info->i_edm_a_3;
                                                                                    } ?>" name="i_edm_a_3" type="text" disabled>
                  </div>
                  <div class="input-group" style="margin-bottom:2px;">
                    <div class="input-group-addon"></div>
                    <input class="form-control required" autocomplete="off" value="<?php if (!empty($calibration_info)) {
                                                                                      echo $calibration_info->i_edm_a_4;
                                                                                    } ?>" name="i_edm_a_4" type="text" disabled>
                  </div>

                </div>
              </td>
              <td>
                <div class="form-group form-group-bottom">
                  <div class="input-group" style="margin-bottom:2px;">
                    <div class="input-group-addon"></div>
                    <input class="form-control required" autocomplete="off" value="<?php if (!empty($calibration_info)) {
                                                                                      echo $calibration_info->i_edm_a_5;
                                                                                    } ?>" name="i_edm_a_5" type="text" disabled>
                  </div>
                  <div class="input-group" style="margin-bottom:2px;">
                    <div class="input-group-addon"></div>
                    <input class="form-control required" autocomplete="off" value="<?php if (!empty($calibration_info)) {
                                                                                      echo $calibration_info->i_edm_a_6;
                                                                                    } ?>" name="i_edm_a_6" type="text" disabled>
                  </div>

                </div>
              </td>

              <td>
                <div class="form-group form-group-bottom">
                  <div class="input-group" style="margin-bottom:2px;">
                    <div class="input-group-addon"></div>
                    <input class="form-control required" autocomplete="off" value="<?php if (!empty($calibration_info)) {
                                                                                      echo $dresult1;
                                                                                    } ?>" name="i_edm_a_5" type="text" disabled>
                  </div>
                  <div class="input-group" style="margin-bottom:2px;">
                    <div class="input-group-addon"></div>
                    <input class="form-control required" autocomplete="off" value="<?php if (!empty($calibration_info)) {
                                                                                      echo $calibration_info->hidden;
                                                                                    } ?>" name="i_edm_a_6" type="text" disabled>
                  </div>

                </div>
              </td>
              <td>
                <div class="form-group form-group-bottom">
                  <div class="input-group" style="margin-bottom:2px;">
                    <div class="input-group-addon"></div>
                    <input class="form-control required" autocomplete="off" value="<?php if (!empty($calibration_info)) {
                                                                                      echo $eresult1;
                                                                                    } ?>" name="i_edm_a_5" type="text" disabled>
                  </div>
                  <div class="input-group" style="margin-bottom:2px;">
                    <div class="input-group-addon"></div>
                    <input class="form-control required" autocomplete="off" value="<?php if (!empty($calibration_info)) {
                                                                                      echo $calibration_info->hidden;
                                                                                    } ?>" name="i_edm_a_6" type="text" disabled>
                  </div>

                </div>
              </td>
              <td>
                <div class="form-group form-group-bottom">
                  <div class="input-group" style="margin-bottom:2px;">
                    <input class="form-control required" autocomplete="off" value="<?php if (!empty($calibration_info)) {
                                                                                      echo number_format($mean_distance1, 4);
                                                                                    } ?>" name="i_edm_a_5" type="text" disabled>
                  </div>
                  <div class="input-group" style="margin-bottom:2px;">
                    <input class="form-control required" autocomplete="off" value="<?php if (!empty($calibration_info)) {
                                                                                      echo $calibration_info->hidden;
                                                                                    } ?>" name="i_edm_a_6" type="text" disabled>
                  </div>

                </div>
              </td>
              <td>
                <div class="form-group form-group-bottom">
                  <div class="input-group" style="margin-bottom:2px;">
                    <input class="form-control required" autocomplete="off" value="<?php if (!empty($calibration_info)) {
                                                                                      echo number_format($elevation_distance1, 4);
                                                                                    } ?>" name="i_edm_a_5" type="text" disabled>
                  </div>
                  <div class="input-group" style="margin-bottom:2px;">
                    <input class="form-control required" autocomplete="off" value="<?php if (!empty($calibration_info)) {
                                                                                      echo $calibration_info->hidden;
                                                                                    } ?>" name="i_edm_a_6" type="text" disabled>
                  </div>

                </div>
              </td>
              <td>
                <div class="form-group form-group-bottom">
                  <div class="input-group" style="margin-bottom:2px;">
                    <input class="form-control required" autocomplete="off" value="<?php if (!empty($calibration_info)) {
                                                                                      echo number_format($smdistance1, 4);
                                                                                    } ?>" name="i_edm_a_5" type="text" disabled>
                  </div>
                  <div class="input-group" style="margin-bottom:2px;">
                    <input class="form-control required" autocomplete="off" value="<?php if (!empty($calibration_info)) {
                                                                                      echo $calibration_info->hidden;
                                                                                    } ?>" name="i_edm_a_6" type="text" disabled>
                  </div>

                </div>
              </td>
              <td>
                <div class="form-group form-group-bottom">
                  <div class="input-group" style="margin-bottom:2px;">
                    <input class="form-control required" autocomplete="off" value="<?php if (!empty($calibration_info)) {
                                                                                      echo number_format($smelevation1, 4);
                                                                                    }   ?>" name="i_edm_a_5" type="text" disabled>
                  </div>
                  <div class="input-group" style="margin-bottom:2px;">
                    <input class="form-control required" autocomplete="off" value="<?php if (!empty($calibration_info)) {
                                                                                      echo $calibration_info->hidden;
                                                                                    } ?>" name="i_edm_a_6" type="text" disabled>
                  </div>

                </div>
              </td>
            </tr>
            <tr>
              <td class="text-center" style="font-weight:500;">II</td>
              <td>
                <div class="form-group form-group-bottom">
                  <div class="input-group" style="margin-bottom:2px;">
                    <div class="input-group-addon"></div>
                    <input class="form-control required" autocomplete="off" value="<?php if (!empty($calibration_info)) {
                                                                                      echo $calibration_info->i_edm_a_7;
                                                                                    } ?>" name="i_edm_a_7" type="text" disabled>
                  </div>
                  <div class="input-group" style="margin-bottom:2px;">
                    <div class="input-group-addon"></div>
                    <input class="form-control required" autocomplete="off" value="<?php if (!empty($calibration_info)) {
                                                                                      echo $calibration_info->i_edm_a_8;
                                                                                    } ?>" name="i_edm_a_8" type="text" disabled>
                  </div>

                </div>
              </td>
              <td>
                <div class="form-group form-group-bottom">
                  <div class="input-group" style="margin-bottom:2px;">
                    <div class="input-group-addon"></div>
                    <input class="form-control required" autocomplete="off" value="<?php if (!empty($calibration_info)) {
                                                                                      echo $calibration_info->i_edm_a_9;
                                                                                    } ?>" name="i_edm_a_9" type="text" disabled>
                  </div>
                  <div class="input-group" style="margin-bottom:2px;">
                    <div class="input-group-addon"></div>
                    <input class="form-control required" autocomplete="off" value="<?php if (!empty($calibration_info)) {
                                                                                      echo $calibration_info->i_edm_a_10;
                                                                                    } ?>" name="i_edm_a_10" type="text" disabled>
                  </div>

                </div>
              </td>
              <td>
                <div class="form-group form-group-bottom">
                  <div class="input-group" style="margin-bottom:2px;">
                    <div class="input-group-addon"></div>
                    <input class="form-control required" autocomplete="off" value="<?php if (!empty($calibration_info)) {
                                                                                      echo $calibration_info->i_edm_a_11;
                                                                                    } ?>" name="i_edm_a_11" type="text" disabled>
                  </div>
                  <div class="input-group" style="margin-bottom:2px;">
                    <div class="input-group-addon"></div>
                    <input class="form-control required" autocomplete="off" value="<?php if (!empty($calibration_info)) {
                                                                                      echo $calibration_info->i_edm_a_12;
                                                                                    } ?>" name="i_edm_a_12" type="text" disabled>
                  </div>

                </div>
              </td>
              <td>
                <div class="form-group form-group-bottom">
                  <div class="input-group" style="margin-bottom:2px;">
                    <div class="input-group-addon"></div>
                    <input class="form-control required" autocomplete="off" value="<?php if (!empty($calibration_info)) {
                                                                                      echo $dresult2;
                                                                                    } ?>" name="i_edm_a_5" type="text" disabled>
                  </div>
                  <div class="input-group" style="margin-bottom:2px;">
                    <div class="input-group-addon"></div>
                    <input class="form-control required" autocomplete="off" value="<?php if (!empty($calibration_info)) {
                                                                                      echo $calibration_info->hidden;
                                                                                    } ?>" name="i_edm_a_6" type="text" disabled>
                  </div>

                </div>
              </td>
              <td>
                <div class="form-group form-group-bottom">
                  <div class="input-group" style="margin-bottom:2px;">
                    <div class="input-group-addon"></div>
                    <input class="form-control required" autocomplete="off" value="<?php if (!empty($calibration_info)) {
                                                                                      echo $eresult2;
                                                                                    } ?>" name="i_edm_a_5" type="text" disabled>
                  </div>
                  <div class="input-group" style="margin-bottom:2px;">
                    <div class="input-group-addon"></div>
                    <input class="form-control required" autocomplete="off" value="<?php if (!empty($calibration_info)) {
                                                                                      echo $calibration_info->hidden;
                                                                                    } ?>" name="i_edm_a_6" type="text" disabled>
                  </div>

                </div>
              </td>

              </td>
              <td>
                <div class="form-group form-group-bottom">
                  <div class="input-group" style="margin-bottom:2px;">
                    <input class="form-control required" autocomplete="off" value="<?php if (!empty($calibration_info)) {
                                                                                      echo number_format($mean_distance2, 4);
                                                                                    } ?>" name="i_edm_a_5" type="text" disabled>
                  </div>
                  <div class="input-group" style="margin-bottom:2px;">
                    <input class="form-control required" autocomplete="off" value="<?php if (!empty($calibration_info)) {
                                                                                      echo $calibration_info->hidden;
                                                                                    } ?>" name="i_edm_a_6" type="text" disabled>
                  </div>

                </div>
              </td>
              <td>
                <div class="form-group form-group-bottom">
                  <div class="input-group" style="margin-bottom:2px;">
                    <input class="form-control required" autocomplete="off" value="<?php if (!empty($calibration_info)) {
                                                                                      echo number_format($elevation_distance2, 4);
                                                                                    } ?>" name="i_edm_a_5" type="text" disabled>
                  </div>
                  <div class="input-group" style="margin-bottom:2px;">
                    <input class="form-control required" autocomplete="off" value="<?php if (!empty($calibration_info)) {
                                                                                      echo $calibration_info->hidden;
                                                                                    } ?>" name="i_edm_a_6" type="text" disabled>
                  </div>

                </div>
              </td>
              <td>
                <div class="form-group form-group-bottom">
                  <div class="input-group" style="margin-bottom:2px;">
                    <input class="form-control required" autocomplete="off" value="<?php if (!empty($calibration_info)) {
                                                                                      echo number_format($smdistance2, 4);
                                                                                    } ?>" name="i_edm_a_5" type="text" disabled>
                  </div>
                  <div class="input-group" style="margin-bottom:2px;">
                    <input class="form-control required" autocomplete="off" value="<?php if (!empty($calibration_info)) {
                                                                                      echo $calibration_info->hidden;
                                                                                    } ?>" name="i_edm_a_6" type="text" disabled>
                  </div>

                </div>
              </td>
              <td>
                <div class="form-group form-group-bottom">
                  <div class="input-group" style="margin-bottom:2px;">
                    <input class="form-control required" autocomplete="off" value="<?php if (!empty($calibration_info)) {
                                                                                      echo number_format($smelevation2, 4);
                                                                                    }   ?>" name="i_edm_a_5" type="text" disabled>
                  </div>
                  <div class="input-group" style="margin-bottom:2px;">
                    <input class="form-control required" autocomplete="off" value="<?php if (!empty($calibration_info)) {
                                                                                      echo $calibration_info->hidden;
                                                                                    } ?>" name="i_edm_a_6" type="text" disabled>
                  </div>

                </div>
              </td>
            </tr>
            <tr>
              <td class="text-center" style="font-weight:500;">I</td>
              <td>
                <div class="form-group form-group-bottom">
                  <div class="input-group" style="margin-bottom:2px;">
                    <div class="input-group-addon"></div>
                    <input class="form-control required" autocomplete="off" value="<?php if (!empty($calibration_info)) {
                                                                                      echo $calibration_info->i_edm_a_13;
                                                                                    } ?>" name="i_edm_a_13" type="text" disabled>
                  </div>
                  <div class="input-group" style="margin-bottom:2px;">
                    <div class="input-group-addon"></div>
                    <input class="form-control required" autocomplete="off" value="<?php if (!empty($calibration_info)) {
                                                                                      echo $calibration_info->i_edm_a_14;
                                                                                    } ?>" name="i_edm_a_14" type="text" disabled>
                  </div>

                </div>
              </td>
              <td>
                <div class="form-group form-group-bottom">
                  <div class="input-group" style="margin-bottom:2px;">
                    <div class="input-group-addon"></div>
                    <input class="form-control required" autocomplete="off" value="<?php if (!empty($calibration_info)) {
                                                                                      echo $calibration_info->i_edm_a_15;
                                                                                    } ?>" name="i_edm_a_15" type="text" disabled>
                  </div>
                  <div class="input-group" style="margin-bottom:2px;">
                    <div class="input-group-addon"></div>
                    <input class="form-control required" autocomplete="off" value="<?php if (!empty($calibration_info)) {
                                                                                      echo $calibration_info->i_edm_a_16;
                                                                                    } ?>" name="i_edm_a_16" type="text" disabled>
                  </div>

                </div>
              </td>
              <td>
                <div class="form-group form-group-bottom">
                  <div class="input-group" style="margin-bottom:2px;">
                    <div class="input-group-addon"></div>
                    <input class="form-control required" autocomplete="off" value="<?php if (!empty($calibration_info)) {
                                                                                      echo $calibration_info->i_edm_a_17;
                                                                                    } ?>" name="i_edm_a_17" type="text" disabled>
                  </div>
                  <div class="input-group" style="margin-bottom:2px;">
                    <div class="input-group-addon"></div>
                    <input class="form-control required" autocomplete="off" value="<?php if (!empty($calibration_info)) {
                                                                                      echo $calibration_info->i_edm_a_18;
                                                                                    } ?>" name="i_edm_a_18" type="text" disabled>
                  </div>

                </div>
              </td>

              <td>
                <div class="form-group form-group-bottom">
                  <div class="input-group" style="margin-bottom:2px;">
                    <div class="input-group-addon"></div>
                    <input class="form-control required" autocomplete="off" value="<?php if (!empty($calibration_info)) {
                                                                                      echo $dresult3;
                                                                                    } ?>" name="i_edm_a_5" type="text" disabled>
                  </div>
                  <div class="input-group" style="margin-bottom:2px;">
                    <div class="input-group-addon"></div>
                    <input class="form-control required" autocomplete="off" value="<?php if (!empty($calibration_info)) {
                                                                                      echo $calibration_info->hidden;
                                                                                    } ?>" name="i_edm_a_6" type="text" disabled>
                  </div>

                </div>
              </td>
              <td>
                <div class="form-group form-group-bottom">
                  <div class="input-group" style="margin-bottom:2px;">
                    <div class="input-group-addon"></div>
                    <input class="form-control required" autocomplete="off" value="<?php if (!empty($calibration_info)) {
                                                                                      echo $eresult3;
                                                                                    } ?>" name="i_edm_a_5" type="text" disabled>
                  </div>
                  <div class="input-group" style="margin-bottom:2px;">
                    <div class="input-group-addon"></div>
                    <input class="form-control required" autocomplete="off" value="<?php if (!empty($calibration_info)) {
                                                                                      echo $calibration_info->hidden;
                                                                                    } ?>" name="i_edm_a_6" type="text" disabled>
                  </div>

                </div>
              </td>

              <td>
                <div class="form-group form-group-bottom">
                  <div class="input-group" style="margin-bottom:2px;">
                    <input class="form-control required" autocomplete="off" value="<?php if (!empty($calibration_info)) {
                                                                                      echo number_format($mean_distance3, 4);
                                                                                    } ?>" name="i_edm_a_5" type="text" disabled>
                  </div>
                  <div class="input-group" style="margin-bottom:2px;">
                    <input class="form-control required" autocomplete="off" value="<?php if (!empty($calibration_info)) {
                                                                                      echo $calibration_info->hidden;
                                                                                    } ?>" name="i_edm_a_6" type="text" disabled>
                  </div>

                </div>
              </td>
              <td>
                <div class="form-group form-group-bottom">
                  <div class="input-group" style="margin-bottom:2px;">
                    <input class="form-control required" autocomplete="off" value="<?php if (!empty($calibration_info)) {
                                                                                      echo number_format($elevation_distance3, 4);
                                                                                    } ?>" name="i_edm_a_5" type="text" disabled>
                  </div>
                  <div class="input-group" style="margin-bottom:2px;">
                    <input class="form-control required" autocomplete="off" value="<?php if (!empty($calibration_info)) {
                                                                                      echo $calibration_info->hidden;
                                                                                    } ?>" name="i_edm_a_6" type="text" disabled>
                  </div>

                </div>
              </td>
              <td>
                <div class="form-group form-group-bottom">
                  <div class="input-group" style="margin-bottom:2px;">
                    <input class="form-control required" autocomplete="off" value="<?php if (!empty($calibration_info)) {
                                                                                      echo number_format($smdistance3, 4);
                                                                                    } ?>" name="i_edm_a_5" type="text" disabled>
                  </div>
                  <div class="input-group" style="margin-bottom:2px;">
                    <input class="form-control required" autocomplete="off" value="<?php if (!empty($calibration_info)) {
                                                                                      echo $calibration_info->hidden;
                                                                                    } ?>" name="i_edm_a_6" type="text" disabled>
                  </div>

                </div>
              </td>
              <td>
                <div class="form-group form-group-bottom">
                  <div class="input-group" style="margin-bottom:2px;">
                    <input class="form-control required" autocomplete="off" value="<?php if (!empty($calibration_info)) {
                                                                                      echo number_format($smelevation3, 4);
                                                                                    }   ?>" name="i_edm_a_5" type="text" disabled>
                  </div>
                  <div class="input-group" style="margin-bottom:2px;">
                    <input class="form-control required" autocomplete="off" value="<?php if (!empty($calibration_info)) {
                                                                                      echo $calibration_info->hidden;
                                                                                    } ?>" name="i_edm_a_6" type="text" disabled>
                  </div>

                </div>
              </td>
            </tr>
            <tr>
              <td class="text-center" style="font-weight:500;">II</td>
              <td>
                <div class="form-group form-group-bottom">
                  <div class="input-group" style="margin-bottom:2px;">
                    <div class="input-group-addon"></div>
                    <input class="form-control required" autocomplete="off" value="<?php if (!empty($calibration_info)) {
                                                                                      echo $calibration_info->i_edm_a_19;
                                                                                    } ?>" name="i_edm_a_19" type="text" disabled>
                  </div>
                  <div class="input-group" style="margin-bottom:2px;">
                    <div class="input-group-addon"></div>
                    <input class="form-control required" autocomplete="off" value="<?php if (!empty($calibration_info)) {
                                                                                      echo $calibration_info->i_edm_a_20;
                                                                                    } ?>" name="i_edm_a_20" type="text" disabled>
                  </div>

                </div>
              </td>
              <td>
                <div class="form-group form-group-bottom">
                  <div class="input-group" style="margin-bottom:2px;">
                    <div class="input-group-addon"></div>
                    <input class="form-control required" autocomplete="off" value="<?php if (!empty($calibration_info)) {
                                                                                      echo $calibration_info->i_edm_a_21;
                                                                                    } ?>" name="i_edm_a_21" type="text" disabled>
                  </div>
                  <div class="input-group" style="margin-bottom:2px;">
                    <div class="input-group-addon"></div>
                    <input class="form-control required" autocomplete="off" value="<?php if (!empty($calibration_info)) {
                                                                                      echo $calibration_info->i_edm_a_22;
                                                                                    } ?>" name="i_edm_a_22" type="text" disabled>
                  </div>

                </div>
              </td>
              <td>
                <div class="form-group form-group-bottom">
                  <div class="input-group" style="margin-bottom:2px;">
                    <div class="input-group-addon"></div>
                    <input class="form-control required" autocomplete="off" value="<?php if (!empty($calibration_info)) {
                                                                                      echo $calibration_info->i_edm_a_23;
                                                                                    } ?>" name="i_edm_a_23" type="text" disabled>
                  </div>
                  <div class="input-group" style="margin-bottom:2px;">
                    <div class="input-group-addon"></div>
                    <input class="form-control required" autocomplete="off" value="<?php if (!empty($calibration_info)) {
                                                                                      echo $calibration_info->i_edm_a_24;
                                                                                    } ?>" name="i_edm_a_24" type="text" disabled>
                  </div>

                </div>
              </td>

              <td>
                <div class="form-group form-group-bottom">
                  <div class="input-group" style="margin-bottom:2px;">
                    <div class="input-group-addon"></div>
                    <input class="form-control required" autocomplete="off" value="<?php if (!empty($calibration_info)) {
                                                                                      echo $dresult4;
                                                                                    } ?>" name="i_edm_a_5" type="text" disabled>
                  </div>
                  <div class="input-group" style="margin-bottom:2px;">
                    <div class="input-group-addon"></div>
                    <input class="form-control required" autocomplete="off" value="<?php if (!empty($calibration_info)) {
                                                                                      echo $calibration_info->hidden;
                                                                                    } ?>" name="i_edm_a_6" type="text" disabled>
                  </div>

                </div>
              </td>
              <td>
                <div class="form-group form-group-bottom">
                  <div class="input-group" style="margin-bottom:2px;">
                    <div class="input-group-addon"></div>
                    <input class="form-control required" autocomplete="off" value="<?php if (!empty($calibration_info)) {
                                                                                      echo $eresult4;
                                                                                    } ?>" name="i_edm_a_5" type="text" disabled>
                  </div>
                  <div class="input-group" style="margin-bottom:2px;">
                    <div class="input-group-addon"></div>
                    <input class="form-control required" autocomplete="off" value="<?php if (!empty($calibration_info)) {
                                                                                      echo $calibration_info->hidden;
                                                                                    } ?>" name="i_edm_a_6" type="text" disabled>
                  </div>

                </div>
              </td>

              <td>
                <div class="form-group form-group-bottom">
                  <div class="input-group" style="margin-bottom:2px;">
                    <input class="form-control required" autocomplete="off" value="<?php if (!empty($calibration_info)) {
                                                                                      echo number_format($mean_distance4, 4);
                                                                                    } ?>" name="i_edm_a_5" type="text" disabled>
                  </div>
                  <div class="input-group" style="margin-bottom:2px;">
                    <input class="form-control required" autocomplete="off" value="<?php if (!empty($calibration_info)) {
                                                                                      echo $calibration_info->hidden;
                                                                                    } ?>" name="i_edm_a_6" type="text" disabled>
                  </div>

                </div>
              </td>
              <td>
                <div class="form-group form-group-bottom">
                  <div class="input-group" style="margin-bottom:2px;">
                    <input class="form-control required" autocomplete="off" value="<?php if (!empty($calibration_info)) {
                                                                                      echo number_format($elevation_distance4, 4);
                                                                                    } ?>" name="i_edm_a_5" type="text" disabled>
                  </div>
                  <div class="input-group" style="margin-bottom:2px;">
                    <input class="form-control required" autocomplete="off" value="<?php if (!empty($calibration_info)) {
                                                                                      echo $calibration_info->hidden;
                                                                                    } ?>" name="i_edm_a_6" type="text" disabled>
                  </div>

                </div>
              </td>
              <td>
                <div class="form-group form-group-bottom">
                  <div class="input-group" style="margin-bottom:2px;">
                    <input class="form-control required" autocomplete="off" value="<?php if (!empty($calibration_info)) {
                                                                                      echo number_format($smdistance4, 4);
                                                                                    } ?>" name="i_edm_a_5" type="text" disabled>
                  </div>
                  <div class="input-group" style="margin-bottom:2px;">
                    <input class="form-control required" autocomplete="off" value="<?php if (!empty($calibration_info)) {
                                                                                      echo $calibration_info->hidden;
                                                                                    } ?>" name="i_edm_a_6" type="text" disabled>
                  </div>

                </div>
              </td>
              <td>
                <div class="form-group form-group-bottom">
                  <div class="input-group" style="margin-bottom:2px;">
                    <input class="form-control required" autocomplete="off" value="<?php if (!empty($calibration_info)) {
                                                                                      echo number_format($smelevation4, 4);
                                                                                    }   ?>" name="i_edm_a_5" type="text" disabled>
                  </div>
                  <div class="input-group" style="margin-bottom:2px;">
                    <input class="form-control required" autocomplete="off" value="<?php if (!empty($calibration_info)) {
                                                                                      echo $calibration_info->hidden;
                                                                                    } ?>" name="i_edm_a_6" type="text" disabled>
                  </div>

                </div>
              </td>
            </tr>
            <tr>
              <td class="text-center" style="font-weight:500;">I</td>
              <td>
                <div class="form-group form-group-bottom">
                  <div class="input-group" style="margin-bottom:2px;">
                    <div class="input-group-addon"></div>
                    <input class="form-control required" autocomplete="off" value="<?php if (!empty($calibration_info)) {
                                                                                      echo $calibration_info->i_edm_a_25;
                                                                                    } ?>" name="i_edm_a_25" type="text" disabled>
                  </div>
                  <div class="input-group" style="margin-bottom:2px;">
                    <div class="input-group-addon"></div>
                    <input class="form-control required" autocomplete="off" value="<?php if (!empty($calibration_info)) {
                                                                                      echo $calibration_info->i_edm_a_26;
                                                                                    } ?>" name="i_edm_a_26" type="text" disabled>
                  </div>

                </div>
              </td>
              <td>
                <div class="form-group form-group-bottom">
                  <div class="input-group" style="margin-bottom:2px;">
                    <div class="input-group-addon"></div>
                    <input class="form-control required" autocomplete="off" value="<?php if (!empty($calibration_info)) {
                                                                                      echo $calibration_info->i_edm_a_27;
                                                                                    } ?>" name="i_edm_a_27" type="text" disabled>
                  </div>
                  <div class="input-group" style="margin-bottom:2px;">
                    <div class="input-group-addon"></div>
                    <input class="form-control required" autocomplete="off" value="<?php if (!empty($calibration_info)) {
                                                                                      echo $calibration_info->i_edm_a_28;
                                                                                    } ?>" name="i_edm_a_28" type="text" disabled>
                  </div>

                </div>
              </td>
              <td>
                <div class="form-group form-group-bottom">
                  <div class="input-group" style="margin-bottom:2px;">
                    <div class="input-group-addon"></div>
                    <input class="form-control required" autocomplete="off" value="<?php if (!empty($calibration_info)) {
                                                                                      echo $calibration_info->i_edm_a_29;
                                                                                    } ?>" name="i_edm_a_29" type="text" disabled>
                  </div>
                  <div class="input-group" style="margin-bottom:2px;">
                    <div class="input-group-addon"></div>
                    <input class="form-control required" autocomplete="off" value="<?php if (!empty($calibration_info)) {
                                                                                      echo $calibration_info->i_edm_a_30;
                                                                                    } ?>" name="i_edm_a_30" type="text" disabled>
                  </div>

                </div>
              </td>
              <td>
                <div class="form-group form-group-bottom">
                  <div class="input-group" style="margin-bottom:2px;">
                    <div class="input-group-addon"></div>
                    <input class="form-control required" autocomplete="off" value="<?php if (!empty($calibration_info)) {
                                                                                      echo $dresult5;
                                                                                    } ?>" name="i_edm_a_5" type="text" disabled>
                  </div>
                  <div class="input-group" style="margin-bottom:2px;">
                    <div class="input-group-addon"></div>
                    <input class="form-control required" autocomplete="off" value="<?php if (!empty($calibration_info)) {
                                                                                      echo $calibration_info->hidden;
                                                                                    } ?>" name="i_edm_a_6" type="text" disabled>
                  </div>

                </div>
              </td>
              <td>
                <div class="form-group form-group-bottom">
                  <div class="input-group" style="margin-bottom:2px;">
                    <div class="input-group-addon"></div>
                    <input class="form-control required" autocomplete="off" value="<?php if (!empty($calibration_info)) {
                                                                                      echo $eresult5;
                                                                                    } ?>" name="i_edm_a_5" type="text" disabled>
                  </div>
                  <div class="input-group" style="margin-bottom:2px;">
                    <div class="input-group-addon"></div>
                    <input class="form-control required" autocomplete="off" value="<?php if (!empty($calibration_info)) {
                                                                                      echo $calibration_info->hidden;
                                                                                    } ?>" name="i_edm_a_6" type="text" disabled>
                  </div>

                </div>
              </td>
              <td>
                <div class="form-group form-group-bottom">
                  <div class="input-group" style="margin-bottom:2px;">
                    <input class="form-control required" autocomplete="off" value="<?php if (!empty($calibration_info)) {
                                                                                      echo number_format($mean_distance5, 4);
                                                                                    } ?>" name="i_edm_a_5" type="text" disabled>
                  </div>
                  <div class="input-group" style="margin-bottom:2px;">
                    <input class="form-control required" autocomplete="off" value="<?php if (!empty($calibration_info)) {
                                                                                      echo $calibration_info->hidden;
                                                                                    } ?>" name="i_edm_a_6" type="text" disabled>
                  </div>

                </div>
              </td>
              <td>
                <div class="form-group form-group-bottom">
                  <div class="input-group" style="margin-bottom:2px;">
                    <input class="form-control required" autocomplete="off" value="<?php if (!empty($calibration_info)) {
                                                                                      echo number_format($elevation_distance5, 4);
                                                                                    } ?>" name="i_edm_a_5" type="text" disabled>
                  </div>
                  <div class="input-group" style="margin-bottom:2px;">
                    <input class="form-control required" autocomplete="off" value="<?php if (!empty($calibration_info)) {
                                                                                      echo $calibration_info->hidden;
                                                                                    } ?>" name="i_edm_a_6" type="text" disabled>
                  </div>

                </div>
              </td>
              <td>
                <div class="form-group form-group-bottom">
                  <div class="input-group" style="margin-bottom:2px;">
                    <input class="form-control required" autocomplete="off" value="<?php if (!empty($calibration_info)) {
                                                                                      echo number_format($smdistance5, 4);
                                                                                    } ?>" name="i_edm_a_5" type="text" disabled>
                  </div>
                  <div class="input-group" style="margin-bottom:2px;">
                    <input class="form-control required" autocomplete="off" value="<?php if (!empty($calibration_info)) {
                                                                                      echo $calibration_info->hidden;
                                                                                    } ?>" name="i_edm_a_6" type="text" disabled>
                  </div>

                </div>
              </td>
              <td>
                <div class="form-group form-group-bottom">
                  <div class="input-group" style="margin-bottom:2px;">
                    <input class="form-control required" autocomplete="off" value="<?php if (!empty($calibration_info)) {
                                                                                      echo number_format($smelevation5, 4);
                                                                                    }   ?>" name="i_edm_a_5" type="text" disabled>
                  </div>
                  <div class="input-group" style="margin-bottom:2px;">
                    <input class="form-control required" autocomplete="off" value="<?php if (!empty($calibration_info)) {
                                                                                      echo $calibration_info->hidden;
                                                                                    } ?>" name="i_edm_a_6" type="text" disabled>
                  </div>

                </div>
              </td>
            </tr>
            <tr>
              <td class="text-center" style="font-weight:500;">II</td>
              <td>
                <div class="form-group form-group-bottom">
                  <div class="input-group" style="margin-bottom:2px;">
                    <div class="input-group-addon"></div>
                    <input class="form-control required" autocomplete="off" value="<?php if (!empty($calibration_info)) {
                                                                                      echo $calibration_info->i_edm_a_31;
                                                                                    } ?>" name="i_edm_a_31" type="text" disabled>
                  </div>
                  <div class="input-group" style="margin-bottom:2px;">
                    <div class="input-group-addon"></div>
                    <input class="form-control required" autocomplete="off" value="<?php if (!empty($calibration_info)) {
                                                                                      echo $calibration_info->i_edm_a_32;
                                                                                    } ?>" name="i_edm_a_32" type="text" disabled>
                  </div>

                </div>
              </td>
              <td>
                <div class="form-group form-group-bottom">
                  <div class="input-group" style="margin-bottom:2px;">
                    <div class="input-group-addon"></div>
                    <input class="form-control required" autocomplete="off" value="<?php if (!empty($calibration_info)) {
                                                                                      echo $calibration_info->i_edm_a_33;
                                                                                    } ?>" name="i_edm_a_33" type="text" disabled>
                  </div>
                  <div class="input-group" style="margin-bottom:2px;">
                    <div class="input-group-addon"></div>
                    <input class="form-control required" autocomplete="off" value="<?php if (!empty($calibration_info)) {
                                                                                      echo $calibration_info->i_edm_a_34;
                                                                                    } ?>" name="i_edm_a_34" type="text" disabled>
                  </div>

                </div>
              </td>
              <td>
                <div class="form-group form-group-bottom">
                  <div class="input-group" style="margin-bottom:2px;">
                    <div class="input-group-addon"></div>
                    <input class="form-control required" autocomplete="off" value="<?php if (!empty($calibration_info)) {
                                                                                      echo $calibration_info->i_edm_a_35;
                                                                                    } ?>" name="i_edm_a_35" type="text" disabled>
                  </div>
                  <div class="input-group" style="margin-bottom:2px;">
                    <div class="input-group-addon"></div>
                    <input class="form-control required" autocomplete="off" value="<?php if (!empty($calibration_info)) {
                                                                                      echo $calibration_info->i_edm_a_36;
                                                                                    } ?>" name="i_edm_a_36" type="text" disabled>
                  </div>

                </div>
              </td>
              <td>
                <div class="form-group form-group-bottom">
                  <div class="input-group" style="margin-bottom:2px;">
                    <div class="input-group-addon"></div>
                    <input class="form-control required" autocomplete="off" value="<?php if (!empty($calibration_info)) {
                                                                                      echo $dresult6;
                                                                                    } ?>" name="i_edm_a_5" type="text" disabled>
                  </div>
                  <div class="input-group" style="margin-bottom:2px;">
                    <div class="input-group-addon"></div>
                    <input class="form-control required" autocomplete="off" value="<?php if (!empty($calibration_info)) {
                                                                                      echo $calibration_info->hidden;
                                                                                    } ?>" name="i_edm_a_6" type="text" disabled>
                  </div>

                </div>
              </td>
              <td>
                <div class="form-group form-group-bottom">
                  <div class="input-group" style="margin-bottom:2px;">
                    <div class="input-group-addon"></div>
                    <input class="form-control required" autocomplete="off" value="<?php if (!empty($calibration_info)) {
                                                                                      echo $eresult6;
                                                                                    } ?>" name="i_edm_a_5" type="text" disabled>
                  </div>
                  <div class="input-group" style="margin-bottom:2px;">
                    <div class="input-group-addon"></div>
                    <input class="form-control required" autocomplete="off" value="<?php if (!empty($calibration_info)) {
                                                                                      echo $calibration_info->hidden;
                                                                                    } ?>" name="i_edm_a_6" type="text" disabled>
                  </div>

                </div>
              </td>
              <td>
                <div class="form-group form-group-bottom">
                  <div class="input-group" style="margin-bottom:2px;">
                    <input class="form-control required" autocomplete="off" value="<?php if (!empty($calibration_info)) {
                                                                                      echo number_format($mean_distance6, 4);
                                                                                    } ?>" name="i_edm_a_5" type="text" disabled>
                  </div>
                  <div class="input-group" style="margin-bottom:2px;">
                    <input class="form-control required" autocomplete="off" value="<?php if (!empty($calibration_info)) {
                                                                                      echo $calibration_info->hidden;
                                                                                    } ?>" name="i_edm_a_6" type="text" disabled>
                  </div>

                </div>
              </td>
              <td>
                <div class="form-group form-group-bottom">
                  <div class="input-group" style="margin-bottom:2px;">
                    <input class="form-control required" autocomplete="off" value="<?php if (!empty($calibration_info)) {
                                                                                      echo number_format($elevation_distance6, 4);
                                                                                    } ?>" name="i_edm_a_5" type="text" disabled>
                  </div>
                  <div class="input-group" style="margin-bottom:2px;">
                    <input class="form-control required" autocomplete="off" value="<?php if (!empty($calibration_info)) {
                                                                                      echo $calibration_info->hidden;
                                                                                    } ?>" name="i_edm_a_6" type="text" disabled>
                  </div>

                </div>
              </td>
              <td>
                <div class="form-group form-group-bottom">
                  <div class="input-group" style="margin-bottom:2px;">
                    <input class="form-control required" autocomplete="off" value="<?php if (!empty($calibration_info)) {
                                                                                      echo number_format($smdistance6, 4);
                                                                                    } ?>" name="i_edm_a_5" type="text" disabled>
                  </div>
                  <div class="input-group" style="margin-bottom:2px;">
                    <input class="form-control required" autocomplete="off" value="<?php if (!empty($calibration_info)) {
                                                                                      echo $calibration_info->hidden;
                                                                                    } ?>" name="i_edm_a_6" type="text" disabled>
                  </div>

                </div>
              </td>
              <td>
                <div class="form-group form-group-bottom">
                  <div class="input-group" style="margin-bottom:2px;">
                    <input class="form-control required" autocomplete="off" value="<?php if (!empty($calibration_info)) {
                                                                                      echo number_format($smelevation6, 4);
                                                                                    }   ?>" name="i_edm_a_5" type="text" disabled>
                  </div>
                  <div class="input-group" style="margin-bottom:2px;">
                    <input class="form-control required" autocomplete="off" value="<?php if (!empty($calibration_info)) {
                                                                                      echo $calibration_info->hidden;
                                                                                    } ?>" name="i_edm_a_6" type="text" disabled>
                  </div>

                </div>
              </td>
            </tr>
            <tr>
              <td class="text-center" style="font-weight:500;">I</td>
              <td>
                <div class="form-group form-group-bottom">
                  <div class="input-group" style="margin-bottom:2px;">
                    <div class="input-group-addon"></div>
                    <input class="form-control required" autocomplete="off" value="<?php if (!empty($calibration_info)) {
                                                                                      echo $calibration_info->i_edm_a_37;
                                                                                    } ?>" name="i_edm_a_37" type="text" disabled>
                  </div>
                  <div class="input-group" style="margin-bottom:2px;">
                    <div class="input-group-addon"></div>
                    <input class="form-control required" autocomplete="off" value="<?php if (!empty($calibration_info)) {
                                                                                      echo $calibration_info->i_edm_a_38;
                                                                                    } ?>" name="i_edm_a_38" type="text" disabled>
                  </div>

                </div>
              </td>
              <td>
                <div class="form-group form-group-bottom">
                  <div class="input-group" style="margin-bottom:2px;">
                    <div class="input-group-addon"></div>
                    <input class="form-control required" autocomplete="off" value="<?php if (!empty($calibration_info)) {
                                                                                      echo $calibration_info->i_edm_a_39;
                                                                                    } ?>" name="i_edm_a_39" type="text" disabled>
                  </div>
                  <div class="input-group" style="margin-bottom:2px;">
                    <div class="input-group-addon"></div>
                    <input class="form-control required" autocomplete="off" value="<?php if (!empty($calibration_info)) {
                                                                                      echo $calibration_info->i_edm_a_40;
                                                                                    } ?>" name="i_edm_a_40" type="text" disabled>
                  </div>

                </div>
              </td>
              <td>
                <div class="form-group form-group-bottom">
                  <div class="input-group" style="margin-bottom:2px;">
                    <div class="input-group-addon"></div>
                    <input class="form-control required" autocomplete="off" value="<?php if (!empty($calibration_info)) {
                                                                                      echo $calibration_info->i_edm_a_41;
                                                                                    } ?>" name="i_edm_a_41" type="text" disabled>
                  </div>
                  <div class="input-group" style="margin-bottom:2px;">
                    <div class="input-group-addon"></div>
                    <input class="form-control required" autocomplete="off" value="<?php if (!empty($calibration_info)) {
                                                                                      echo $calibration_info->i_edm_a_42;
                                                                                    } ?>" name="i_edm_a_42" type="text" disabled>
                  </div>

                </div>
              </td>
              <td>
                <div class="form-group form-group-bottom">
                  <div class="input-group" style="margin-bottom:2px;">
                    <div class="input-group-addon"></div>
                    <input class="form-control required" autocomplete="off" value="<?php if (!empty($calibration_info)) {
                                                                                      echo $dresult7;
                                                                                    } ?>" name="i_edm_a_5" type="text" disabled>
                  </div>
                  <div class="input-group" style="margin-bottom:2px;">
                    <div class="input-group-addon"></div>
                    <input class="form-control required" autocomplete="off" value="<?php if (!empty($calibration_info)) {
                                                                                      echo $calibration_info->hidden;
                                                                                    } ?>" name="i_edm_a_6" type="text" disabled>
                  </div>

                </div>
              </td>
              <td>
                <div class="form-group form-group-bottom">
                  <div class="input-group" style="margin-bottom:2px;">
                    <div class="input-group-addon"></div>
                    <input class="form-control required" autocomplete="off" value="<?php if (!empty($calibration_info)) {
                                                                                      echo $eresult7;
                                                                                    } ?>" name="i_edm_a_5" type="text" disabled>
                  </div>
                  <div class="input-group" style="margin-bottom:2px;">
                    <div class="input-group-addon"></div>
                    <input class="form-control required" autocomplete="off" value="<?php if (!empty($calibration_info)) {
                                                                                      echo $calibration_info->hidden;
                                                                                    } ?>" name="i_edm_a_6" type="text" disabled>
                  </div>

                </div>
              </td>
              <td>
                <div class="form-group form-group-bottom">
                  <div class="input-group" style="margin-bottom:2px;">
                    <input class="form-control required" autocomplete="off" value="<?php if (!empty($calibration_info)) {
                                                                                      echo number_format($mean_distance7, 4);
                                                                                    } ?>" name="i_edm_a_5" type="text" disabled>
                  </div>
                  <div class="input-group" style="margin-bottom:2px;">
                    <input class="form-control required" autocomplete="off" value="<?php if (!empty($calibration_info)) {
                                                                                      echo $calibration_info->hidden;
                                                                                    } ?>" name="i_edm_a_6" type="text" disabled>
                  </div>

                </div>
              </td>
              <td>
                <div class="form-group form-group-bottom">
                  <div class="input-group" style="margin-bottom:2px;">
                    <input class="form-control required" autocomplete="off" value="<?php if (!empty($calibration_info)) {
                                                                                      echo number_format($elevation_distance7, 4);
                                                                                    } ?>" name="i_edm_a_5" type="text" disabled>
                  </div>
                  <div class="input-group" style="margin-bottom:2px;">
                    <input class="form-control required" autocomplete="off" value="<?php if (!empty($calibration_info)) {
                                                                                      echo $calibration_info->hidden;
                                                                                    } ?>" name="i_edm_a_6" type="text" disabled>
                  </div>

                </div>
              </td>
              <td>
                <div class="form-group form-group-bottom">
                  <div class="input-group" style="margin-bottom:2px;">
                    <input class="form-control required" autocomplete="off" value="<?php if (!empty($calibration_info)) {
                                                                                      echo number_format($smdistance7, 4);
                                                                                    } ?>" name="i_edm_a_5" type="text" disabled>
                  </div>
                  <div class="input-group" style="margin-bottom:2px;">
                    <input class="form-control required" autocomplete="off" value="<?php if (!empty($calibration_info)) {
                                                                                      echo $calibration_info->hidden;
                                                                                    } ?>" name="i_edm_a_6" type="text" disabled>
                  </div>

                </div>
              </td>
              <td>
                <div class="form-group form-group-bottom">
                  <div class="input-group" style="margin-bottom:2px;">
                    <input class="form-control required" autocomplete="off" value="<?php if (!empty($calibration_info)) {
                                                                                      echo number_format($smelevation7, 4);
                                                                                    }   ?>" name="i_edm_a_5" type="text" disabled>
                  </div>
                  <div class="input-group" style="margin-bottom:2px;">
                    <input class="form-control required" autocomplete="off" value="<?php if (!empty($calibration_info)) {
                                                                                      echo $calibration_info->hidden;
                                                                                    } ?>" name="i_edm_a_6" type="text" disabled>
                  </div>

                </div>
              </td>
            </tr>
            <tr>
              <td class="text-center" style="font-weight:500;">II</td>
              <td>
                <div class="form-group form-group-bottom">
                  <div class="input-group" style="margin-bottom:2px;">
                    <div class="input-group-addon"></div>
                    <input class="form-control required" autocomplete="off" value="<?php if (!empty($calibration_info)) {
                                                                                      echo $calibration_info->i_edm_a_43;
                                                                                    } ?>" name="i_edm_a_43" type="text" disabled>
                  </div>
                  <div class="input-group" style="margin-bottom:2px;">
                    <div class="input-group-addon"></div>
                    <input class="form-control required" autocomplete="off" value="<?php if (!empty($calibration_info)) {
                                                                                      echo $calibration_info->i_edm_a_44;
                                                                                    } ?>" name="i_edm_a_44" type="text" disabled>
                  </div>

                </div>
              </td>
              <td>
                <div class="form-group form-group-bottom">
                  <div class="input-group" style="margin-bottom:2px;">
                    <div class="input-group-addon"></div>
                    <input class="form-control required" autocomplete="off" value="<?php if (!empty($calibration_info)) {
                                                                                      echo $calibration_info->i_edm_a_45;
                                                                                    } ?>" name="i_edm_a_45" type="text" disabled>
                  </div>
                  <div class="input-group" style="margin-bottom:2px;">
                    <div class="input-group-addon"></div>
                    <input class="form-control required" autocomplete="off" value="<?php if (!empty($calibration_info)) {
                                                                                      echo $calibration_info->i_edm_a_46;
                                                                                    } ?>" name="i_edm_a_46" type="text" disabled>
                  </div>

                </div>
              </td>
              <td>
                <div class="form-group form-group-bottom">
                  <div class="input-group" style="margin-bottom:2px;">
                    <div class="input-group-addon"></div>
                    <input class="form-control required" autocomplete="off" value="<?php if (!empty($calibration_info)) {
                                                                                      echo $calibration_info->i_edm_a_47;
                                                                                    } ?>" name="i_edm_a_47" type="text" disabled>
                  </div>
                  <div class="input-group" style="margin-bottom:2px;">
                    <div class="input-group-addon"></div>
                    <input class="form-control required" autocomplete="off" value="<?php if (!empty($calibration_info)) {
                                                                                      echo $calibration_info->i_edm_a_48;
                                                                                    } ?>" name="i_edm_a_48" type="text" disabled>
                  </div>

                </div>
              </td>
              <td>
                <div class="form-group form-group-bottom">
                  <div class="input-group" style="margin-bottom:2px;">
                    <div class="input-group-addon"></div>
                    <input class="form-control required" autocomplete="off" value="<?php if (!empty($calibration_info)) {
                                                                                      echo $dresult8;
                                                                                    } ?>" name="i_edm_a_5" type="text" disabled>
                  </div>
                  <div class="input-group" style="margin-bottom:2px;">
                    <div class="input-group-addon"></div>
                    <input class="form-control required" autocomplete="off" value="<?php if (!empty($calibration_info)) {
                                                                                      echo $calibration_info->hidden;
                                                                                    } ?>" name="i_edm_a_6" type="text" disabled>
                  </div>

                </div>
              </td>
              <td>
                <div class="form-group form-group-bottom">
                  <div class="input-group" style="margin-bottom:2px;">
                    <div class="input-group-addon"></div>
                    <input class="form-control required" autocomplete="off" value="<?php if (!empty($calibration_info)) {
                                                                                      echo $eresult8;
                                                                                    } ?>" name="i_edm_a_5" type="text" disabled>
                  </div>
                  <div class="input-group" style="margin-bottom:2px;">
                    <div class="input-group-addon"></div>
                    <input class="form-control required" autocomplete="off" value="<?php if (!empty($calibration_info)) {
                                                                                      echo $calibration_info->hidden;
                                                                                    } ?>" name="i_edm_a_6" type="text" disabled>
                  </div>

                </div>
              </td>
              <td>
                <div class="form-group form-group-bottom">
                  <div class="input-group" style="margin-bottom:2px;">
                    <input class="form-control required" autocomplete="off" value="<?php if (!empty($calibration_info)) {
                                                                                      echo number_format($mean_distance8, 4);
                                                                                    } ?>" name="i_edm_a_5" type="text" disabled>
                  </div>
                  <div class="input-group" style="margin-bottom:2px;">
                    <input class="form-control required" autocomplete="off" value="<?php if (!empty($calibration_info)) {
                                                                                      echo $calibration_info->hidden;
                                                                                    } ?>" name="i_edm_a_6" type="text" disabled>
                  </div>

                </div>
              </td>
              <td>
                <div class="form-group form-group-bottom">
                  <div class="input-group" style="margin-bottom:2px;">
                    <input class="form-control required" autocomplete="off" value="<?php if (!empty($calibration_info)) {
                                                                                      echo number_format($elevation_distance8, 4);
                                                                                    } ?>" name="i_edm_a_5" type="text" disabled>
                  </div>
                  <div class="input-group" style="margin-bottom:2px;">
                    <input class="form-control required" autocomplete="off" value="<?php if (!empty($calibration_info)) {
                                                                                      echo $calibration_info->hidden;
                                                                                    } ?>" name="i_edm_a_6" type="text" disabled>
                  </div>

                </div>
              </td>
              <td>
                <div class="form-group form-group-bottom">
                  <div class="input-group" style="margin-bottom:2px;">
                    <input class="form-control required" autocomplete="off" value="<?php if (!empty($calibration_info)) {
                                                                                      echo number_format($smdistance8, 4);
                                                                                    } ?>" name="i_edm_a_5" type="text" disabled>
                  </div>
                  <div class="input-group" style="margin-bottom:2px;">
                    <input class="form-control required" autocomplete="off" value="<?php if (!empty($calibration_info)) {
                                                                                      echo $calibration_info->hidden;
                                                                                    } ?>" name="i_edm_a_6" type="text" disabled>
                  </div>

                </div>
              </td>
              <td>
                <div class="form-group form-group-bottom">
                  <div class="input-group" style="margin-bottom:2px;">
                    <input class="form-control required" autocomplete="off" value="<?php if (!empty($calibration_info)) {
                                                                                      echo number_format($smelevation8, 4);
                                                                                    }   ?>" name="i_edm_a_5" type="text" disabled>
                  </div>
                  <div class="input-group" style="margin-bottom:2px;">
                    <input class="form-control required" autocomplete="off" value="<?php if (!empty($calibration_info)) {
                                                                                      echo $calibration_info->hidden;
                                                                                    } ?>" name="i_edm_a_6" type="text" disabled>
                  </div>

                </div>
              </td>
            </tr>

          </tbody>
          <tr style="background-color: #d3d3d3;">
            <th></th>
            <th></th>
            <th></th>
            <th>Mean</th>
            <th><?php if (!empty($mean_total)) {
                  echo number_format($mean_total, 4);
                } ?></th>
            <th><?php if (!empty($elevation_total)) {
                  echo number_format($elevation_total, 4);
                } ?></th>
            <th></th>
            <th>Sum</th>
            <th><?php if (!empty($elevation_total)) {
                  echo number_format($total_smdistance, 4);
                } ?></th>
            <th><?php if (!empty($elevation_total)) {
                  echo number_format($total_smelevation, 4);
                } ?></th>
          </tr>
          <tr>
            <th></th>
            <th></th>
            <th></th>
            <th></th>
            <th></th>
            <th>Standard Errors (Max & Absolute)/2</th>
            <th><?php if (!empty($standard_error1)) {
                  echo number_format($standard_error1, 4);
                } ?></th>
            <th><?php if (!empty($standard_error2)) {
                  echo number_format($standard_error2, 4);
                } ?></th>
            <th></th>
            <th></th>
          </tr>
          <tr>
            <th></th>
            <th></th>
            <th></th>
            <th></th>
            <th></th>
            <th></th>
            <th></th>
            <th>Degree of Freedom</th>
            <th><?php if (!empty($dfresult1)) {
                  echo number_format($dfresult1, 4);
                } ?></th>
            <th><?php if (!empty($dfresult2)) {
                  echo number_format($dfresult2, 4);
                } ?></th>
          <tr>
            <th></th>
            <th></th>
            <th></th>
            <th></th>
            <th></th>
            <th></th>
            <th></th>
            <th></th>
            <th></th>
            <th style="
                                    <?php
                                    if ($standard_error1 < $t_v_a_10 && $standard_error2 < $t_v_a_10 && $dfresult1 < $t_v_a_10 && $dfresult2 < $t_v_a_10) {
                                      echo 'background-color: lightgreen; color: white;';
                                    } else {
                                      echo 'background-color: red; color: white;';
                                    }
                                    ?>
                                ">
              <?php
              if ($standard_error1 < $t_v_a_10 && $standard_error2 < $t_v_a_10 && $dfresult1 < $t_v_a_10 && $dfresult2 < $t_v_a_10) {
                echo 'PASSED';
              } else {
                echo 'FAILED';
              }
              ?>
            </th>
          </tr>
        </table>
        <button type="button" class="btn btn-primary next-step">VIEW POST-CALIBRATION CHECKS</button>
      </div>

      <div class="form-section">
        <b>POST-CALIBRATION INDEX ERROR CHECKS</b><br><br>
        <table class="table table-bordered table-striped dataTable">
          <thead>
            <tr>
              <th></th>
              <th colspan="2" style="text-align:center; font-weight:500;" class="total">HORIZONTAL CIRCLE INDEX ERROR</th>
              <th colspan="2" style="text-align:center; font-weight:500;">VERTICAL CIRCLE INDEX ERROR</th>
            </tr>
            <tr>
              <th class="desc" style="text-align:center; font-weight:500;">FACE</th>
              <th class="total" style="text-align:center; font-weight:500;">START A</th>
              <th class="total" style="text-align:center; font-weight:500;">END B</th>
              <th class="desc" style="text-align:center; font-weight:500;">START A</th>
              <th class="desc" style="text-align:center; font-weight:500;">END B</th>
            </tr>
          </thead>
          <tbody>
            <?php
            $t_h_a = $calibration_info->t_h_a;
            $tt_h_a = $calibration_info->tt_h_a;
            $sum_h_a2 = $t_h_a + $tt_h_a;
            $d_error_h_a2 = (dms2dec(180, 0, 0) - $sum_h_a2);

            $t_h_b = $calibration_info->t_h_b;
            $tt_h_b = $calibration_info->tt_h_b;
            $sum_h_b2 = $t_h_b + $tt_h_b;
            $d_error_h_b2 = (dms2dec(180, 0, 0) - $sum_h_b2);

            $t_v_a = $calibration_info->t_v_a;
            $tt_v_a = $calibration_info->tt_v_a;
            $sum_v_a2 = $t_v_a + $tt_v_a;
            $d_error_v_a2 = (dms2dec(360, 0, 0) - $sum_v_a2);

            $t_v_b = $calibration_info->t_v_b;
            $tt_v_b = $calibration_info->tt_v_b;
            $sum_v_b2 = $t_v_b + $tt_v_b;
            $d_error_v_b2 = (dms2dec(360, 0, 0) - $sum_v_b2);

            $d_error_v_b1_6 = $d_error_v_a2 / 2;


            $i_edm_a_12 = number_format(round($calibration_info->i_edm_a_12, 3), 3);
            $i_edm_a_22 = number_format(round($calibration_info->i_edm_a_22, 3), 3);
            $i_edm_a_32 = number_format(round($calibration_info->i_edm_a_32, 3), 3);
            $f_i_edm_a2 = ($i_edm_a_12 + $i_edm_a_22 + $i_edm_a_32) / 3;

            $i_edm_b_12 = number_format(round($calibration_info->i_edm_b_12, 3), 3);
            $i_edm_b_22 = number_format(round($calibration_info->i_edm_b_22, 3), 3);
            $i_edm_b_32 = number_format(round($calibration_info->i_edm_b_32, 3), 3);
            $f_i_edm_b2 = ($i_edm_b_12 + $i_edm_b_22 + $i_edm_b_32) / 3;

            $ii_edm_a_12 = number_format(round($calibration_info->ii_edm_a_12, 3), 3);
            $ii_edm_a_22 = number_format(round($calibration_info->ii_edm_a_22, 3), 3);
            $ii_edm_a_32 = number_format(round($calibration_info->ii_edm_a_32, 3), 3);
            $f_ii_edm_a2 = ($ii_edm_a_12 + $ii_edm_a_22 + $ii_edm_a_32) / 3;

            $ii_edm_b_12 = number_format(round($calibration_info->ii_edm_b_12, 3), 3);
            $ii_edm_b_22 = number_format(round($calibration_info->ii_edm_b_22, 3), 3);
            $ii_edm_b_32 = number_format(round($calibration_info->ii_edm_b_32, 3), 3);
            $f_ii_edm_b2 = ($ii_edm_b_12 + $ii_edm_b_22 + $ii_edm_b_32) / 3;

            $f_i_ii_edm_a2 = ($f_i_edm_a2 + $f_ii_edm_a2) / 2;
            $f_i_ii_edm_b2 = ($f_i_edm_b2 + $f_ii_edm_b2) / 2;

            $t_v_a_10 = $calibration_info->t_v_a_10;

            $result3 = '';

            // Check the condition and set the result accordingly
            if ($d_error_v_b1_6 > $t_v_a_10 && $d_error_v_b1_6 > $t_v_a_10) {
              // Fail case
              $result3 = '<td colspan="4" class="total" style="text-align:center; background-color: rgba(255, 0, 0, 0.3); color: black;"><b>FAILED</b></td>';
            } else {
              // Pass case
              $result3 = '<td colspan="4" class="total" style="text-align:center; background-color: rgba(0, 255, 0, 0.3); color: black;"><b>PASSED</b></td>';
            }
            ?>
            <tr>
              <td class="desc" style="text-align:center; font-weight:500;"><b>I</b></td>
              <td class="total" style="text-align:center;"><?php echo dec2dms_full($t_h_a); ?></td>
              <td class="total" style="text-align:center;"><?php echo dec2dms_full($t_h_b); ?></td>
              <td class="desc" style="text-align:center;"><?php echo dec2dms_full($t_v_a); ?></td>
              <td class="desc" style="text-align:center;"><?php echo dec2dms_full($t_v_b); ?></td>
            </tr>
            <tr>
              <td class="desc" style="text-align:center; font-weight:500;"><b>II</b></td>
              <td class="total" style="text-align:center;"><?php echo dec2dms_full($tt_h_a); ?></td>
              <td class="total" style="text-align:center;"><?php echo dec2dms_full($tt_h_b); ?></td>
              <td class="desc" style="text-align:center;"><?php echo dec2dms_full($tt_v_a); ?></td>
              <td class="desc" style="text-align:center;"><?php echo dec2dms_full($tt_v_b); ?></td>
            </tr>
            <tr>
              <td></td>
              <td colspan="2" class="text-center total"><b>Half Circle is 180&deg; 00' 00"</b></td>
              <td colspan="2" class="text-center"><b>Half Circle is 360&deg; 00' 00"</b></td>
            </tr>
            <tr>
              <td class="desc" style="text-align:center; font-weight:500;"><b>SUM(I+II)</b></td>
              <td class="total" style="text-align:center;"><?php echo dec2dms_full($sum_h_a2); ?></td>
              <td class="total" style="text-align:center;"><?php echo dec2dms_full($sum_h_b2); ?></td>
              <td class="desc" style="text-align:center;"><?php echo dec2dms_full($sum_v_a2); ?></td>
              <td class="desc" style="text-align:center;"><?php echo dec2dms_full($sum_v_b2); ?></td>
            </tr>
            <tr>
              <td></td>
              <td colspan="2" class="text-center total"><b>(180&deg; 00' 00" - SUM(A+B))/2</b></td>
              <td colspan="2" class="text-center"><b>(360&deg; 00' 00" - SUM(A+B))/2</b></td>
            </tr>
            <tr>
              <td class="desc" style="text-align:center; font-weight:500;"><b>DOUBLE ERROR</b></td>
              <td class="total" style="text-align:center;"><?php echo dec2dms_full($d_error_h_a2); ?></td>
              <td class="total" style="text-align:center;"><?php echo dec2dms_full($d_error_h_b2); ?></td>
              <td class="desc" style="text-align:center;"><?php echo dec2dms_full($d_error_v_a2); ?></td>
              <td class="desc" style="text-align:center;"><?php echo dec2dms_full($d_error_v_b2); ?></td>
            </tr>
            <tr>
              <td class="desc" style="text-align:center; font-weight:500;"><b>Index Error (Double error/2)</b></td>
              <td class="total" style="text-align:center;"><?php echo dec2dms_full($d_error_h_a2); ?></td>
              <td class="total" style="text-align:center;"><?php echo dec2dms_full($d_error_h_b2); ?></td>
              <td class="desc" style="text-align:center;"><?php echo dec2dms_full($d_error_v_b1_6); ?>;</td>
              <td class="desc" style="text-align:center;"><?php echo dec2dms_full($d_error_v_b2); ?></td>
            </tr>
            <tr>
              <td class="desc" style="text-align:center; font-weight:500;"><b></b></td>
              <!-- Output the stored result here -->
              <?php echo $result3; ?>
            </tr>
          </tbody>
        </table><br>
        <b>POST-CALIBRATION EDM CHECKS</b>
        <table class="table" id="serviceFields">
          <thead>
            <tr>
              <th class="text-center" style="font-weight:500;"></th>
              <th class="text-center" style="font-weight:500;"></th>
              <th class="text-center" style="font-weight:500;"></th>
              <th class="text-center" style="font-weight:500;"></th>
              <th class="text-center" style="font-weight:500;"></th>
              <th class="text-center" style="font-weight:500;"></th>
              <th class="text-center" style="font-weight:500;">Residuals from Mean(mm)</th>
              <th class="text-center" style="font-weight:500;"></th>
              <th class="text-center" style="font-weight:500;">Squared meaned residuals(mm)</th>
              <th class="text-center" style="font-weight:500;"></th>
            </tr>
            <tr>
              <th class="text-center" style="font-weight:500;">FACE</th>
              <th class="text-center" style="font-weight:500;">X</th>
              <th class="text-center" style="font-weight:500;">Y</th>
              <th class="text-center" style="font-weight:500;">Z</th>
              <th class="text-center" style="font-weight:500;">Distance</th>
              <th class="text-center" style="font-weight:500;">Elevation</th>
              <th class="text-center" style="font-weight:500;">Distance</th>
              <th class="text-center" style="font-weight:500;">Elevation</th>
              <th class="text-center" style="font-weight:500;">Distance</th>
              <th class="text-center" style="font-weight:500;">Elevation</th>
            </tr>
          </thead>
          <tbody>
            <?php
            $i_edm_a_49 = $calibration_info->i_edm_a_49;
            $i_edm_a_50 = $calibration_info->i_edm_a_50;
            $i_edm_a_51 = $calibration_info->i_edm_a_51;
            $i_edm_a_52 = $calibration_info->i_edm_a_52;
            $i_edm_a_53 = $calibration_info->i_edm_a_53;
            $i_edm_a_54 = $calibration_info->i_edm_a_54;
            $i_edm_a_55 = $calibration_info->i_edm_a_55;
            $i_edm_a_56 = $calibration_info->i_edm_a_56;
            $i_edm_a_57 = $calibration_info->i_edm_a_57;
            $i_edm_a_58 = $calibration_info->i_edm_a_58;
            $i_edm_a_59 = $calibration_info->i_edm_a_59;
            $i_edm_a_60 = $calibration_info->i_edm_a_60;
            $i_edm_a_61 = $calibration_info->i_edm_a_61;
            $i_edm_a_62 = $calibration_info->i_edm_a_62;
            $i_edm_a_63 = $calibration_info->i_edm_a_63;
            $i_edm_a_64 = $calibration_info->i_edm_a_64;
            $i_edm_a_65 = $calibration_info->i_edm_a_65;
            $i_edm_a_66 = $calibration_info->i_edm_a_66;
            $i_edm_a_67 = $calibration_info->i_edm_a_67;
            $i_edm_a_68 = $calibration_info->i_edm_a_68;
            $i_edm_a_69 = $calibration_info->i_edm_a_69;
            $i_edm_a_70 = $calibration_info->i_edm_a_70;
            $i_edm_a_71 = $calibration_info->i_edm_a_71;
            $i_edm_a_72 = $calibration_info->i_edm_a_72;
            $i_edm_a_73 = $calibration_info->i_edm_a_73;
            $i_edm_a_74 = $calibration_info->i_edm_a_74;
            $i_edm_a_75 = $calibration_info->i_edm_a_75;
            $i_edm_a_76 = $calibration_info->i_edm_a_76;
            $i_edm_a_77 = $calibration_info->i_edm_a_77;
            $i_edm_a_78 = $calibration_info->i_edm_a_78;
            $i_edm_a_79 = $calibration_info->i_edm_a_79;
            $i_edm_a_80 = $calibration_info->i_edm_a_80;
            $i_edm_a_81 = $calibration_info->i_edm_a_81;
            $i_edm_a_82 = $calibration_info->i_edm_a_82;
            $i_edm_a_83 = $calibration_info->i_edm_a_83;
            $i_edm_a_84 = $calibration_info->i_edm_a_84;
            $i_edm_a_85 = $calibration_info->i_edm_a_85;
            $i_edm_a_86 = $calibration_info->i_edm_a_86;
            $i_edm_a_87 = $calibration_info->i_edm_a_87;
            $i_edm_a_88 = $calibration_info->i_edm_a_88;
            $i_edm_a_89 = $calibration_info->i_edm_a_89;
            $i_edm_a_90 = $calibration_info->i_edm_a_90;
            $i_edm_a_91 = $calibration_info->i_edm_a_91;
            $i_edm_a_92 = $calibration_info->i_edm_a_92;
            $i_edm_a_93 = $calibration_info->i_edm_a_93;
            $i_edm_a_94 = $calibration_info->i_edm_a_94;
            $i_edm_a_95 = $calibration_info->i_edm_a_95;
            $i_edm_a_96 = $calibration_info->i_edm_a_96;

            $t_v_a_10 = $calibration_info->t_v_a_10;

            $dresult9 = sqrt(pow(($i_edm_a_49 - $i_edm_a_50), 2) + pow(($i_edm_a_51 - $i_edm_a_52), 2));
            $dresult9 = number_format($dresult9, 4);
            $eresult9 = $i_edm_a_53 - $i_edm_a_54;

            $dresult10 = sqrt(pow(($i_edm_a_55 - $i_edm_a_56), 2) + pow(($i_edm_a_57 - $i_edm_a_58), 2));
            $dresult10 = number_format($dresult10, 4);
            $eresult10 = $i_edm_a_59 - $i_edm_a_60;

            $dresult11 = sqrt(pow(($i_edm_a_61 - $i_edm_a_62), 2) + pow(($i_edm_a_63 - $i_edm_a_64), 2));
            $dresult11 = number_format($dresult11, 4);
            $eresult11 = $i_edm_a_65 - $i_edm_a_66;

            $dresult12 = sqrt(pow(($i_edm_a_67 - $i_edm_a_68), 2) + pow(($i_edm_a_69 - $i_edm_a_70), 2));
            $dresult12 = number_format($dresult12, 4);
            $eresult12 = $i_edm_a_71 - $i_edm_a_72;

            $dresult13 = sqrt(pow(($i_edm_a_73 - $i_edm_a_74), 2) + pow(($i_edm_a_75 - $i_edm_a_76), 2));
            $dresult13 = number_format($dresult13, 4);
            $eresult13 = $i_edm_a_77 - $i_edm_a_78;

            $dresult14 = sqrt(pow(($i_edm_a_79 - $i_edm_a_80), 2) + pow(($i_edm_a_81 - $i_edm_a_82), 2));
            $dresult14 = number_format($dresult14, 4);
            $eresult14 = $i_edm_a_83 - $i_edm_a_84;

            $dresult15 = sqrt(pow(($i_edm_a_85 - $i_edm_a_86), 2) + pow(($i_edm_a_87 - $i_edm_a_88), 2));
            $dresult15 = number_format($dresult15, 4);
            $eresult15 = $i_edm_a_89 - $i_edm_a_90;

            $dresult16 = sqrt(pow(($i_edm_a_91 - $i_edm_a_92), 2) + pow(($i_edm_a_93 - $i_edm_a_94), 2));
            $dresult16 = number_format($dresult16, 4);
            $eresult16 = $i_edm_a_95 - $i_edm_a_96;

            $mean_total1 = ($dresult9 + $dresult10 + $dresult11 + $dresult12 + $dresult13 + $dresult14 + $dresult15 + $dresult16) / 8;
            $elevation_total1 = ($eresult9 + $eresult10 + $eresult11 + $eresult12 + $eresult13 + $eresult14 + $eresult15 + $eresult16) / 8;

            $mean_distance9 = ($dresult9 - $mean_total1) * 1000;
            $mean_distance10 = ($dresult10 - $mean_total1) * 1000;
            $mean_distance11 = ($dresult11 - $mean_total1) * 1000;
            $mean_distance12 = ($dresult12 - $mean_total1) * 1000;
            $mean_distance13 = ($dresult13 - $mean_total1) * 1000;
            $mean_distance14 = ($dresult14 - $mean_total1) * 1000;
            $mean_distance15 = ($dresult15 - $mean_total1) * 1000;
            $mean_distance16 = ($dresult16 - $mean_total1) * 1000;

            $elevation_distance9 = ($eresult9 - $elevation_total1) * 1000;
            $elevation_distance10 = ($eresult10 - $elevation_total1) * 1000;
            $elevation_distance11 = ($eresult11 - $elevation_total1) * 1000;
            $elevation_distance12 = ($eresult12 - $elevation_total1) * 1000;
            $elevation_distance13 = ($eresult13 - $elevation_total1) * 1000;
            $elevation_distance14 = ($eresult14 - $elevation_total1) * 1000;
            $elevation_distance15 = ($eresult15 - $elevation_total1) * 1000;
            $elevation_distance16 = ($eresult16 - $elevation_total1) * 1000;

            $standard_error3 = -$mean_distance9 / 2;
            $standard_error4 = -$elevation_distance14 / 2;

            $smdistance9 = pow($mean_distance9, 2);
            $smdistance10 = pow($mean_distance10, 2);
            $smdistance11 = pow($mean_distance11, 2);
            $smdistance12 = pow($mean_distance12, 2);
            $smdistance13 = pow($mean_distance13, 2);
            $smdistance14 = pow($mean_distance14, 2);
            $smdistance15 = pow($mean_distance15, 2);
            $smdistance16 = pow($mean_distance16, 2);

            $total_smdistance1 = $smdistance9 + $smdistance10 + $smdistance11 + $smdistance12 + $smdistance13 + $smdistance14 + $smdistance15 + $smdistance16;

            $smelevation9 = pow($elevation_distance9, 2);
            $smelevation10 = pow($elevation_distance10, 2);
            $smelevation11 = pow($elevation_distance11, 2);
            $smelevation12 = pow($elevation_distance12, 2);
            $smelevation13 = pow($elevation_distance13, 2);
            $smelevation14 = pow($elevation_distance14, 2);
            $smelevation15 = pow($elevation_distance15, 2);
            $smelevation16 = pow($elevation_distance16, 2);

            $total_smelevation1 = $smelevation9 + $smelevation10 + $smelevation11 + $smelevation12 + $smelevation13 + $smelevation14 + $smelevation15 + $smelevation16;

            $dfresult3 = $total_smdistance1 / 15;
            $dfresult4 = $total_smelevation1 / 15;


            ?>

            <tr>
              <td class="text-center" style="font-weight:500;">I</td>
              <td>
                <div class="form-group form-group-bottom">
                  <div class="input-group" style="margin-bottom:2px;">
                    <div class="input-group-addon"></div>
                    <input class="form-control required" autocomplete="off" value="<?php if (!empty($calibration_info)) {
                                                                                      echo $calibration_info->i_edm_a_49;
                                                                                    } ?>" name="i_edm_a_49" type="text" disabled>
                  </div>
                  <div class="input-group" style="margin-bottom:2px;">
                    <div class="input-group-addon"></div>
                    <input class="form-control required" autocomplete="off" value="<?php if (!empty($calibration_info)) {
                                                                                      echo $calibration_info->i_edm_a_50;
                                                                                    } ?>" name="i_edm_a_50" type="text" disabled>
                  </div>

                </div>
              </td>
              <td>
                <div class="form-group form-group-bottom">
                  <div class="input-group" style="margin-bottom:2px;">
                    <div class="input-group-addon"></div>
                    <input class="form-control required" autocomplete="off" value="<?php if (!empty($calibration_info)) {
                                                                                      echo $calibration_info->i_edm_a_51;
                                                                                    } ?>" name="i_edm_a_51" type="text" disabled>
                  </div>
                  <div class="input-group" style="margin-bottom:2px;">
                    <div class="input-group-addon"></div>
                    <input class="form-control required" autocomplete="off" value="<?php if (!empty($calibration_info)) {
                                                                                      echo $calibration_info->i_edm_a_52;
                                                                                    } ?>" name="i_edm_a_52" type="text" disabled>
                  </div>

                </div>
              </td>
              <td>
                <div class="form-group form-group-bottom">
                  <div class="input-group" style="margin-bottom:2px;">
                    <div class="input-group-addon"></div>
                    <input class="form-control required" autocomplete="off" value="<?php if (!empty($calibration_info)) {
                                                                                      echo $calibration_info->i_edm_a_53;
                                                                                    } ?>" name="i_edm_a_53" type="text" disabled>
                  </div>
                  <div class="input-group" style="margin-bottom:2px;">
                    <div class="input-group-addon"></div>
                    <input class="form-control required" autocomplete="off" value="<?php if (!empty($calibration_info)) {
                                                                                      echo $calibration_info->i_edm_a_54;
                                                                                    } ?>" name="i_edm_a_54" type="text" disabled>
                  </div>

                </div>
              </td>

              <td>
                <div class="form-group form-group-bottom">
                  <div class="input-group" style="margin-bottom:2px;">
                    <div class="input-group-addon"></div>
                    <input class="form-control required" autocomplete="off" value="<?php if (!empty($calibration_info)) {
                                                                                      echo $dresult9;
                                                                                    } ?>" name="i_edm_a_5" type="text" disabled>
                  </div>
                  <div class="input-group" style="margin-bottom:2px;">
                    <div class="input-group-addon"></div>
                    <input class="form-control required" autocomplete="off" value="<?php if (!empty($calibration_info)) {
                                                                                      echo $calibration_info->hidden;
                                                                                    } ?>" name="i_edm_a_6" type="text" disabled>
                  </div>

                </div>
              </td>
              <td>
                <div class="form-group form-group-bottom">
                  <div class="input-group" style="margin-bottom:2px;">
                    <div class="input-group-addon"></div>
                    <input class="form-control required" autocomplete="off" value="<?php if (!empty($calibration_info)) {
                                                                                      echo $eresult9;
                                                                                    } ?>" name="i_edm_a_5" type="text" disabled>
                  </div>
                  <div class="input-group" style="margin-bottom:2px;">
                    <div class="input-group-addon"></div>
                    <input class="form-control required" autocomplete="off" value="<?php if (!empty($calibration_info)) {
                                                                                      echo $calibration_info->hidden;
                                                                                    } ?>" name="i_edm_a_6" type="text" disabled>
                  </div>

                </div>
              </td>

              <td>
                <div class="form-group form-group-bottom">
                  <div class="input-group" style="margin-bottom:2px;">
                    <input class="form-control required" autocomplete="off" value="<?php if (!empty($calibration_info)) {
                                                                                      echo number_format($mean_distance9, 4);
                                                                                    } ?>" name="i_edm_a_5" type="text" disabled>
                  </div>
                  <div class="input-group" style="margin-bottom:2px;">
                    <input class="form-control required" autocomplete="off" value="<?php if (!empty($calibration_info)) {
                                                                                      echo $calibration_info->hidden;
                                                                                    } ?>" name="i_edm_a_6" type="text" disabled>
                  </div>

                </div>
              </td>
              <td>
                <div class="form-group form-group-bottom">
                  <div class="input-group" style="margin-bottom:2px;">
                    <input class="form-control required" autocomplete="off" value="<?php if (!empty($calibration_info)) {
                                                                                      echo number_format($elevation_distance9, 4);
                                                                                    } ?>" name="i_edm_a_5" type="text" disabled>
                  </div>
                  <div class="input-group" style="margin-bottom:2px;">
                    <input class="form-control required" autocomplete="off" value="<?php if (!empty($calibration_info)) {
                                                                                      echo $calibration_info->hidden;
                                                                                    } ?>" name="i_edm_a_6" type="text" disabled>
                  </div>

                </div>
              </td>
              <td>
                <div class="form-group form-group-bottom">
                  <div class="input-group" style="margin-bottom:2px;">
                    <input class="form-control required" autocomplete="off" value="<?php if (!empty($calibration_info)) {
                                                                                      echo number_format($smdistance9, 4);
                                                                                    } ?>" name="i_edm_a_5" type="text" disabled>
                  </div>
                  <div class="input-group" style="margin-bottom:2px;">
                    <input class="form-control required" autocomplete="off" value="<?php if (!empty($calibration_info)) {
                                                                                      echo $calibration_info->hidden;
                                                                                    } ?>" name="i_edm_a_6" type="text" disabled>
                  </div>

                </div>
              </td>
              <td>
                <div class="form-group form-group-bottom">
                  <div class="input-group" style="margin-bottom:2px;">
                    <input class="form-control required" autocomplete="off" value="<?php if (!empty($calibration_info)) {
                                                                                      echo number_format($smelevation9, 4);
                                                                                    }   ?>" name="i_edm_a_5" type="text" disabled>
                  </div>
                  <div class="input-group" style="margin-bottom:2px;">
                    <input class="form-control required" autocomplete="off" value="<?php if (!empty($calibration_info)) {
                                                                                      echo $calibration_info->hidden;
                                                                                    } ?>" name="i_edm_a_6" type="text" disabled>
                  </div>

                </div>
              </td>
            </tr>
            <tr>
              <td class="text-center" style="font-weight:500;">II</td>
              <td>
                <div class="form-group form-group-bottom">
                  <div class="input-group" style="margin-bottom:2px;">
                    <div class="input-group-addon"></div>
                    <input class="form-control required" autocomplete="off" value="<?php if (!empty($calibration_info)) {
                                                                                      echo $calibration_info->i_edm_a_55;
                                                                                    } ?>" name="i_edm_a_55" type="text" disabled>
                  </div>
                  <div class="input-group" style="margin-bottom:2px;">
                    <div class="input-group-addon"></div>
                    <input class="form-control required" autocomplete="off" value="<?php if (!empty($calibration_info)) {
                                                                                      echo $calibration_info->i_edm_a_56;
                                                                                    } ?>" name="i_edm_a_56" type="text" disabled>
                  </div>

                </div>
              </td>
              <td>
                <div class="form-group form-group-bottom">
                  <div class="input-group" style="margin-bottom:2px;">
                    <div class="input-group-addon"></div>
                    <input class="form-control required" autocomplete="off" value="<?php if (!empty($calibration_info)) {
                                                                                      echo $calibration_info->i_edm_a_57;
                                                                                    } ?>" name="i_edm_a_57" type="text" disabled>
                  </div>
                  <div class="input-group" style="margin-bottom:2px;">
                    <div class="input-group-addon"></div>
                    <input class="form-control required" autocomplete="off" value="<?php if (!empty($calibration_info)) {
                                                                                      echo $calibration_info->i_edm_a_58;
                                                                                    } ?>" name="i_edm_a_58" type="text" disabled>
                  </div>

                </div>
              </td>
              <td>
                <div class="form-group form-group-bottom">
                  <div class="input-group" style="margin-bottom:2px;">
                    <div class="input-group-addon"></div>
                    <input class="form-control required" autocomplete="off" value="<?php if (!empty($calibration_info)) {
                                                                                      echo $calibration_info->i_edm_a_59;
                                                                                    } ?>" name="i_edm_a_59" type="text" disabled>
                  </div>
                  <div class="input-group" style="margin-bottom:2px;">
                    <div class="input-group-addon"></div>
                    <input class="form-control required" autocomplete="off" value="<?php if (!empty($calibration_info)) {
                                                                                      echo $calibration_info->i_edm_a_60;
                                                                                    } ?>" name="i_edm_a_60" type="text" disabled>
                  </div>

                </div>
              </td>

              <td>
                <div class="form-group form-group-bottom">
                  <div class="input-group" style="margin-bottom:2px;">
                    <div class="input-group-addon"></div>
                    <input class="form-control required" autocomplete="off" value="<?php if (!empty($calibration_info)) {
                                                                                      echo $dresult10;
                                                                                    } ?>" name="i_edm_a_5" type="text" disabled>
                  </div>
                  <div class="input-group" style="margin-bottom:2px;">
                    <div class="input-group-addon"></div>
                    <input class="form-control required" autocomplete="off" value="<?php if (!empty($calibration_info)) {
                                                                                      echo $calibration_info->hidden;
                                                                                    } ?>" name="i_edm_a_6" type="text" disabled>
                  </div>

                </div>
              </td>
              <td>
                <div class="form-group form-group-bottom">
                  <div class="input-group" style="margin-bottom:2px;">
                    <div class="input-group-addon"></div>
                    <input class="form-control required" autocomplete="off" value="<?php if (!empty($calibration_info)) {
                                                                                      echo $eresult10;
                                                                                    } ?>" name="i_edm_a_5" type="text" disabled>
                  </div>
                  <div class="input-group" style="margin-bottom:2px;">
                    <div class="input-group-addon"></div>
                    <input class="form-control required" autocomplete="off" value="<?php if (!empty($calibration_info)) {
                                                                                      echo $calibration_info->hidden;
                                                                                    } ?>" name="i_edm_a_6" type="text" disabled>
                  </div>

                </div>
              </td>

              <td>
                <div class="form-group form-group-bottom">
                  <div class="input-group" style="margin-bottom:2px;">
                    <input class="form-control required" autocomplete="off" value="<?php if (!empty($calibration_info)) {
                                                                                      echo number_format($mean_distance10, 4);
                                                                                    } ?>" name="i_edm_a_5" type="text" disabled>
                  </div>
                  <div class="input-group" style="margin-bottom:2px;">
                    <input class="form-control required" autocomplete="off" value="<?php if (!empty($calibration_info)) {
                                                                                      echo $calibration_info->hidden;
                                                                                    } ?>" name="i_edm_a_6" type="text" disabled>
                  </div>

                </div>
              </td>
              <td>
                <div class="form-group form-group-bottom">
                  <div class="input-group" style="margin-bottom:2px;">
                    <input class="form-control required" autocomplete="off" value="<?php if (!empty($calibration_info)) {
                                                                                      echo number_format($elevation_distance10, 4);
                                                                                    } ?>" name="i_edm_a_5" type="text" disabled>
                  </div>
                  <div class="input-group" style="margin-bottom:2px;">
                    <input class="form-control required" autocomplete="off" value="<?php if (!empty($calibration_info)) {
                                                                                      echo $calibration_info->hidden;
                                                                                    } ?>" name="i_edm_a_6" type="text" disabled>
                  </div>

                </div>
              </td>
              <td>
                <div class="form-group form-group-bottom">
                  <div class="input-group" style="margin-bottom:2px;">
                    <input class="form-control required" autocomplete="off" value="<?php if (!empty($calibration_info)) {
                                                                                      echo number_format($smdistance10, 4);
                                                                                    } ?>" name="i_edm_a_5" type="text" disabled>
                  </div>
                  <div class="input-group" style="margin-bottom:2px;">
                    <input class="form-control required" autocomplete="off" value="<?php if (!empty($calibration_info)) {
                                                                                      echo $calibration_info->hidden;
                                                                                    } ?>" name="i_edm_a_6" type="text" disabled>
                  </div>

                </div>
              </td>
              <td>
                <div class="form-group form-group-bottom">
                  <div class="input-group" style="margin-bottom:2px;">
                    <input class="form-control required" autocomplete="off" value="<?php if (!empty($calibration_info)) {
                                                                                      echo number_format($smelevation10, 4);
                                                                                    }   ?>" name="i_edm_a_5" type="text" disabled>
                  </div>
                  <div class="input-group" style="margin-bottom:2px;">
                    <input class="form-control required" autocomplete="off" value="<?php if (!empty($calibration_info)) {
                                                                                      echo $calibration_info->hidden;
                                                                                    } ?>" name="i_edm_a_6" type="text" disabled>
                  </div>

                </div>
              </td>
            </tr>
            <tr>
              <td class="text-center" style="font-weight:500;">I</td>
              <td>
                <div class="form-group form-group-bottom">
                  <div class="input-group" style="margin-bottom:2px;">
                    <div class="input-group-addon"></div>
                    <input class="form-control required" autocomplete="off" value="<?php if (!empty($calibration_info)) {
                                                                                      echo $calibration_info->i_edm_a_61;
                                                                                    } ?>" name="i_edm_a_61" type="text" disabled>
                  </div>
                  <div class="input-group" style="margin-bottom:2px;">
                    <div class="input-group-addon"></div>
                    <input class="form-control required" autocomplete="off" value="<?php if (!empty($calibration_info)) {
                                                                                      echo $calibration_info->i_edm_a_62;
                                                                                    } ?>" name="i_edm_a_62" type="text" disabled>
                  </div>

                </div>
              </td>
              <td>
                <div class="form-group form-group-bottom">
                  <div class="input-group" style="margin-bottom:2px;">
                    <div class="input-group-addon"></div>
                    <input class="form-control required" autocomplete="off" value="<?php if (!empty($calibration_info)) {
                                                                                      echo $calibration_info->i_edm_a_63;
                                                                                    } ?>" name="i_edm_a_63" type="text" disabled>
                  </div>
                  <div class="input-group" style="margin-bottom:2px;">
                    <div class="input-group-addon"></div>
                    <input class="form-control required" autocomplete="off" value="<?php if (!empty($calibration_info)) {
                                                                                      echo $calibration_info->i_edm_a_64;
                                                                                    } ?>" name="i_edm_a_64" type="text" disabled>
                  </div>

                </div>
              </td>
              <td>
                <div class="form-group form-group-bottom">
                  <div class="input-group" style="margin-bottom:2px;">
                    <div class="input-group-addon"></div>
                    <input class="form-control required" autocomplete="off" value="<?php if (!empty($calibration_info)) {
                                                                                      echo $calibration_info->i_edm_a_65;
                                                                                    } ?>" name="i_edm_a_65" type="text" disabled>
                  </div>
                  <div class="input-group" style="margin-bottom:2px;">
                    <div class="input-group-addon"></div>
                    <input class="form-control required" autocomplete="off" value="<?php if (!empty($calibration_info)) {
                                                                                      echo $calibration_info->i_edm_a_66;
                                                                                    } ?>" name="i_edm_a_66" type="text" disabled>
                  </div>

                </div>
              </td>

              <td>
                <div class="form-group form-group-bottom">
                  <div class="input-group" style="margin-bottom:2px;">
                    <div class="input-group-addon"></div>
                    <input class="form-control required" autocomplete="off" value="<?php if (!empty($calibration_info)) {
                                                                                      echo $dresult11;
                                                                                    } ?>" name="i_edm_a_5" type="text" disabled>
                  </div>
                  <div class="input-group" style="margin-bottom:2px;">
                    <div class="input-group-addon"></div>
                    <input class="form-control required" autocomplete="off" value="<?php if (!empty($calibration_info)) {
                                                                                      echo $calibration_info->hidden;
                                                                                    } ?>" name="i_edm_a_6" type="text" disabled>
                  </div>

                </div>
              </td>
              <td>
                <div class="form-group form-group-bottom">
                  <div class="input-group" style="margin-bottom:2px;">
                    <div class="input-group-addon"></div>
                    <input class="form-control required" autocomplete="off" value="<?php if (!empty($calibration_info)) {
                                                                                      echo $eresult11;
                                                                                    } ?>" name="i_edm_a_5" type="text" disabled>
                  </div>
                  <div class="input-group" style="margin-bottom:2px;">
                    <div class="input-group-addon"></div>
                    <input class="form-control required" autocomplete="off" value="<?php if (!empty($calibration_info)) {
                                                                                      echo $calibration_info->hidden;
                                                                                    } ?>" name="i_edm_a_6" type="text" disabled>
                  </div>

                </div>
              </td>
              <td>
                <div class="form-group form-group-bottom">
                  <div class="input-group" style="margin-bottom:2px;">
                    <input class="form-control required" autocomplete="off" value="<?php if (!empty($calibration_info)) {
                                                                                      echo number_format($mean_distance11, 4);
                                                                                    } ?>" name="i_edm_a_5" type="text" disabled>
                  </div>
                  <div class="input-group" style="margin-bottom:2px;">
                    <input class="form-control required" autocomplete="off" value="<?php if (!empty($calibration_info)) {
                                                                                      echo $calibration_info->hidden;
                                                                                    } ?>" name="i_edm_a_6" type="text" disabled>
                  </div>

                </div>
              </td>
              <td>
                <div class="form-group form-group-bottom">
                  <div class="input-group" style="margin-bottom:2px;">
                    <input class="form-control required" autocomplete="off" value="<?php if (!empty($calibration_info)) {
                                                                                      echo number_format($elevation_distance11, 4);
                                                                                    } ?>" name="i_edm_a_5" type="text" disabled>
                  </div>
                  <div class="input-group" style="margin-bottom:2px;">
                    <input class="form-control required" autocomplete="off" value="<?php if (!empty($calibration_info)) {
                                                                                      echo $calibration_info->hidden;
                                                                                    } ?>" name="i_edm_a_6" type="text" disabled>
                  </div>

                </div>
              </td>
              <td>
                <div class="form-group form-group-bottom">
                  <div class="input-group" style="margin-bottom:2px;">
                    <input class="form-control required" autocomplete="off" value="<?php if (!empty($calibration_info)) {
                                                                                      echo number_format($smdistance11, 4);
                                                                                    } ?>" name="i_edm_a_5" type="text" disabled>
                  </div>
                  <div class="input-group" style="margin-bottom:2px;">
                    <input class="form-control required" autocomplete="off" value="<?php if (!empty($calibration_info)) {
                                                                                      echo $calibration_info->hidden;
                                                                                    } ?>" name="i_edm_a_6" type="text" disabled>
                  </div>

                </div>
              </td>
              <td>
                <div class="form-group form-group-bottom">
                  <div class="input-group" style="margin-bottom:2px;">
                    <input class="form-control required" autocomplete="off" value="<?php if (!empty($calibration_info)) {
                                                                                      echo number_format($smelevation11, 4);
                                                                                    }   ?>" name="i_edm_a_5" type="text" disabled>
                  </div>
                  <div class="input-group" style="margin-bottom:2px;">
                    <input class="form-control required" autocomplete="off" value="<?php if (!empty($calibration_info)) {
                                                                                      echo $calibration_info->hidden;
                                                                                    } ?>" name="i_edm_a_6" type="text" disabled>
                  </div>

                </div>
              </td>
            </tr>
            <tr>
              <td class="text-center" style="font-weight:500;">II</td>
              <td>
                <div class="form-group form-group-bottom">
                  <div class="input-group" style="margin-bottom:2px;">
                    <div class="input-group-addon"></div>
                    <input class="form-control required" autocomplete="off" value="<?php if (!empty($calibration_info)) {
                                                                                      echo $calibration_info->i_edm_a_67;
                                                                                    } ?>" name="i_edm_a_67" type="text" disabled>
                  </div>
                  <div class="input-group" style="margin-bottom:2px;">
                    <div class="input-group-addon"></div>
                    <input class="form-control required" autocomplete="off" value="<?php if (!empty($calibration_info)) {
                                                                                      echo $calibration_info->i_edm_a_68;
                                                                                    } ?>" name="i_edm_a_68" type="text" disabled>
                  </div>

                </div>
              </td>
              <td>
                <div class="form-group form-group-bottom">
                  <div class="input-group" style="margin-bottom:2px;">
                    <div class="input-group-addon"></div>
                    <input class="form-control required" autocomplete="off" value="<?php if (!empty($calibration_info)) {
                                                                                      echo $calibration_info->i_edm_a_69;
                                                                                    } ?>" name="i_edm_a_69" type="text" disabled>
                  </div>
                  <div class="input-group" style="margin-bottom:2px;">
                    <div class="input-group-addon"></div>
                    <input class="form-control required" autocomplete="off" value="<?php if (!empty($calibration_info)) {
                                                                                      echo $calibration_info->i_edm_a_70;
                                                                                    } ?>" name="i_edm_a_70" type="text" disabled>
                  </div>

                </div>
              </td>
              <td>
                <div class="form-group form-group-bottom">
                  <div class="input-group" style="margin-bottom:2px;">
                    <div class="input-group-addon"></div>
                    <input class="form-control required" autocomplete="off" value="<?php if (!empty($calibration_info)) {
                                                                                      echo $calibration_info->i_edm_a_71;
                                                                                    } ?>" name="i_edm_a_71" type="text" disabled>
                  </div>
                  <div class="input-group" style="margin-bottom:2px;">
                    <div class="input-group-addon"></div>
                    <input class="form-control required" autocomplete="off" value="<?php if (!empty($calibration_info)) {
                                                                                      echo $calibration_info->i_edm_a_72;
                                                                                    } ?>" name="i_edm_a_72" type="text" disabled>
                  </div>

                </div>
              </td>

              <td>
                <div class="form-group form-group-bottom">
                  <div class="input-group" style="margin-bottom:2px;">
                    <div class="input-group-addon"></div>
                    <input class="form-control required" autocomplete="off" value="<?php if (!empty($calibration_info)) {
                                                                                      echo $dresult12;
                                                                                    } ?>" name="i_edm_a_5" type="text" disabled>
                  </div>
                  <div class="input-group" style="margin-bottom:2px;">
                    <div class="input-group-addon"></div>
                    <input class="form-control required" autocomplete="off" value="<?php if (!empty($calibration_info)) {
                                                                                      echo $calibration_info->hidden;
                                                                                    } ?>" name="i_edm_a_6" type="text" disabled>
                  </div>

                </div>
              </td>
              <td>
                <div class="form-group form-group-bottom">
                  <div class="input-group" style="margin-bottom:2px;">
                    <div class="input-group-addon"></div>
                    <input class="form-control required" autocomplete="off" value="<?php if (!empty($calibration_info)) {
                                                                                      echo $eresult12;
                                                                                    } ?>" name="i_edm_a_5" type="text" disabled>
                  </div>
                  <div class="input-group" style="margin-bottom:2px;">
                    <div class="input-group-addon"></div>
                    <input class="form-control required" autocomplete="off" value="<?php if (!empty($calibration_info)) {
                                                                                      echo $calibration_info->hidden;
                                                                                    } ?>" name="i_edm_a_6" type="text" disabled>
                  </div>

                </div>
              </td>

              <td>
                <div class="form-group form-group-bottom">
                  <div class="input-group" style="margin-bottom:2px;">
                    <input class="form-control required" autocomplete="off" value="<?php if (!empty($calibration_info)) {
                                                                                      echo number_format($mean_distance12, 4);
                                                                                    } ?>" name="i_edm_a_5" type="text" disabled>
                  </div>
                  <div class="input-group" style="margin-bottom:2px;">
                    <input class="form-control required" autocomplete="off" value="<?php if (!empty($calibration_info)) {
                                                                                      echo $calibration_info->hidden;
                                                                                    } ?>" name="i_edm_a_6" type="text" disabled>
                  </div>

                </div>
              </td>
              <td>
                <div class="form-group form-group-bottom">
                  <div class="input-group" style="margin-bottom:2px;">
                    <input class="form-control required" autocomplete="off" value="<?php if (!empty($calibration_info)) {
                                                                                      echo number_format($elevation_distance12, 4);
                                                                                    } ?>" name="i_edm_a_5" type="text" disabled>
                  </div>
                  <div class="input-group" style="margin-bottom:2px;">
                    <input class="form-control required" autocomplete="off" value="<?php if (!empty($calibration_info)) {
                                                                                      echo $calibration_info->hidden;
                                                                                    } ?>" name="i_edm_a_6" type="text" disabled>
                  </div>

                </div>
              </td>
              <td>
                <div class="form-group form-group-bottom">
                  <div class="input-group" style="margin-bottom:2px;">
                    <input class="form-control required" autocomplete="off" value="<?php if (!empty($calibration_info)) {
                                                                                      echo number_format($smdistance12, 4);
                                                                                    } ?>" name="i_edm_a_5" type="text" disabled>
                  </div>
                  <div class="input-group" style="margin-bottom:2px;">
                    <input class="form-control required" autocomplete="off" value="<?php if (!empty($calibration_info)) {
                                                                                      echo $calibration_info->hidden;
                                                                                    } ?>" name="i_edm_a_6" type="text" disabled>
                  </div>

                </div>
              </td>
              <td>
                <div class="form-group form-group-bottom">
                  <div class="input-group" style="margin-bottom:2px;">
                    <input class="form-control required" autocomplete="off" value="<?php if (!empty($calibration_info)) {
                                                                                      echo number_format($smelevation12, 4);
                                                                                    }   ?>" name="i_edm_a_5" type="text" disabled>
                  </div>
                  <div class="input-group" style="margin-bottom:2px;">
                    <input class="form-control required" autocomplete="off" value="<?php if (!empty($calibration_info)) {
                                                                                      echo $calibration_info->hidden;
                                                                                    } ?>" name="i_edm_a_6" type="text" disabled>
                  </div>

                </div>
              </td>
            </tr>
            <tr>
              <td class="text-center" style="font-weight:500;">I</td>
              <td>
                <div class="form-group form-group-bottom">
                  <div class="input-group" style="margin-bottom:2px;">
                    <div class="input-group-addon"></div>
                    <input class="form-control required" autocomplete="off" value="<?php if (!empty($calibration_info)) {
                                                                                      echo $calibration_info->i_edm_a_73;
                                                                                    } ?>" name="i_edm_a_73" type="text" disabled>
                  </div>
                  <div class="input-group" style="margin-bottom:2px;">
                    <div class="input-group-addon"></div>
                    <input class="form-control required" autocomplete="off" value="<?php if (!empty($calibration_info)) {
                                                                                      echo $calibration_info->i_edm_a_74;
                                                                                    } ?>" name="i_edm_a_74" type="text" disabled>
                  </div>

                </div>
              </td>
              <td>
                <div class="form-group form-group-bottom">
                  <div class="input-group" style="margin-bottom:2px;">
                    <div class="input-group-addon"></div>
                    <input class="form-control required" autocomplete="off" value="<?php if (!empty($calibration_info)) {
                                                                                      echo $calibration_info->i_edm_a_75;
                                                                                    } ?>" name="i_edm_a_75" type="text" disabled>
                  </div>
                  <div class="input-group" style="margin-bottom:2px;">
                    <div class="input-group-addon"></div>
                    <input class="form-control required" autocomplete="off" value="<?php if (!empty($calibration_info)) {
                                                                                      echo $calibration_info->i_edm_a_76;
                                                                                    } ?>" name="i_edm_a_76" type="text" disabled>
                  </div>

                </div>
              </td>
              <td>
                <div class="form-group form-group-bottom">
                  <div class="input-group" style="margin-bottom:2px;">
                    <div class="input-group-addon"></div>
                    <input class="form-control required" autocomplete="off" value="<?php if (!empty($calibration_info)) {
                                                                                      echo $calibration_info->i_edm_a_77;
                                                                                    } ?>" name="i_edm_a_77" type="text" disabled>
                  </div>
                  <div class="input-group" style="margin-bottom:2px;">
                    <div class="input-group-addon"></div>
                    <input class="form-control required" autocomplete="off" value="<?php if (!empty($calibration_info)) {
                                                                                      echo $calibration_info->i_edm_a_78;
                                                                                    } ?>" name="i_edm_a_78" type="text" disabled>
                  </div>

                </div>
              </td>

              <td>
                <div class="form-group form-group-bottom">
                  <div class="input-group" style="margin-bottom:2px;">
                    <div class="input-group-addon"></div>
                    <input class="form-control required" autocomplete="off" value="<?php if (!empty($calibration_info)) {
                                                                                      echo $dresult13;
                                                                                    } ?>" name="i_edm_a_5" type="text" disabled>
                  </div>
                  <div class="input-group" style="margin-bottom:2px;">
                    <div class="input-group-addon"></div>
                    <input class="form-control required" autocomplete="off" value="<?php if (!empty($calibration_info)) {
                                                                                      echo $calibration_info->hidden;
                                                                                    } ?>" name="i_edm_a_6" type="text" disabled>
                  </div>

                </div>
              </td>
              <td>
                <div class="form-group form-group-bottom">
                  <div class="input-group" style="margin-bottom:2px;">
                    <div class="input-group-addon"></div>
                    <input class="form-control required" autocomplete="off" value="<?php if (!empty($calibration_info)) {
                                                                                      echo $eresult13;
                                                                                    } ?>" name="i_edm_a_5" type="text" disabled>
                  </div>
                  <div class="input-group" style="margin-bottom:2px;">
                    <div class="input-group-addon"></div>
                    <input class="form-control required" autocomplete="off" value="<?php if (!empty($calibration_info)) {
                                                                                      echo $calibration_info->hidden;
                                                                                    } ?>" name="i_edm_a_6" type="text" disabled>
                  </div>

                </div>
              </td>
              <td>
                <div class="form-group form-group-bottom">
                  <div class="input-group" style="margin-bottom:2px;">
                    <input class="form-control required" autocomplete="off" value="<?php if (!empty($calibration_info)) {
                                                                                      echo number_format($mean_distance13, 4);
                                                                                    } ?>" name="i_edm_a_5" type="text" disabled>
                  </div>
                  <div class="input-group" style="margin-bottom:2px;">
                    <input class="form-control required" autocomplete="off" value="<?php if (!empty($calibration_info)) {
                                                                                      echo $calibration_info->hidden;
                                                                                    } ?>" name="i_edm_a_6" type="text" disabled>
                  </div>

                </div>
              </td>
              <td>
                <div class="form-group form-group-bottom">
                  <div class="input-group" style="margin-bottom:2px;">
                    <input class="form-control required" autocomplete="off" value="<?php if (!empty($calibration_info)) {
                                                                                      echo number_format($elevation_distance13, 4);
                                                                                    } ?>" name="i_edm_a_5" type="text" disabled>
                  </div>
                  <div class="input-group" style="margin-bottom:2px;">
                    <input class="form-control required" autocomplete="off" value="<?php if (!empty($calibration_info)) {
                                                                                      echo $calibration_info->hidden;
                                                                                    } ?>" name="i_edm_a_6" type="text" disabled>
                  </div>

                </div>
              </td>
              <td>
                <div class="form-group form-group-bottom">
                  <div class="input-group" style="margin-bottom:2px;">
                    <input class="form-control required" autocomplete="off" value="<?php if (!empty($calibration_info)) {
                                                                                      echo number_format($smdistance13, 4);
                                                                                    } ?>" name="i_edm_a_5" type="text" disabled>
                  </div>
                  <div class="input-group" style="margin-bottom:2px;">
                    <input class="form-control required" autocomplete="off" value="<?php if (!empty($calibration_info)) {
                                                                                      echo $calibration_info->hidden;
                                                                                    } ?>" name="i_edm_a_6" type="text" disabled>
                  </div>

                </div>
              </td>
              <td>
                <div class="form-group form-group-bottom">
                  <div class="input-group" style="margin-bottom:2px;">
                    <input class="form-control required" autocomplete="off" value="<?php if (!empty($calibration_info)) {
                                                                                      echo number_format($smelevation13, 4);
                                                                                    }   ?>" name="i_edm_a_5" type="text" disabled>
                  </div>
                  <div class="input-group" style="margin-bottom:2px;">
                    <input class="form-control required" autocomplete="off" value="<?php if (!empty($calibration_info)) {
                                                                                      echo $calibration_info->hidden;
                                                                                    } ?>" name="i_edm_a_6" type="text" disabled>
                  </div>

                </div>
              </td>
            </tr>
            <tr>
              <td class="text-center" style="font-weight:500;">II</td>
              <td>
                <div class="form-group form-group-bottom">
                  <div class="input-group" style="margin-bottom:2px;">
                    <div class="input-group-addon"></div>
                    <input class="form-control required" autocomplete="off" value="<?php if (!empty($calibration_info)) {
                                                                                      echo $calibration_info->i_edm_a_79;
                                                                                    } ?>" name="i_edm_a_79" type="text" disabled>
                  </div>
                  <div class="input-group" style="margin-bottom:2px;">
                    <div class="input-group-addon"></div>
                    <input class="form-control required" autocomplete="off" value="<?php if (!empty($calibration_info)) {
                                                                                      echo $calibration_info->i_edm_a_80;
                                                                                    } ?>" name="i_edm_a_80" type="text" disabled>
                  </div>

                </div>
              </td>
              <td>
                <div class="form-group form-group-bottom">
                  <div class="input-group" style="margin-bottom:2px;">
                    <div class="input-group-addon"></div>
                    <input class="form-control required" autocomplete="off" value="<?php if (!empty($calibration_info)) {
                                                                                      echo $calibration_info->i_edm_a_81;
                                                                                    } ?>" name="i_edm_a_81" type="text" disabled>
                  </div>
                  <div class="input-group" style="margin-bottom:2px;">
                    <div class="input-group-addon"></div>
                    <input class="form-control required" autocomplete="off" value="<?php if (!empty($calibration_info)) {
                                                                                      echo $calibration_info->i_edm_a_82;
                                                                                    } ?>" name="i_edm_a_82" type="text" disabled>
                  </div>

                </div>
              </td>
              <td>
                <div class="form-group form-group-bottom">
                  <div class="input-group" style="margin-bottom:2px;">
                    <div class="input-group-addon"></div>
                    <input class="form-control required" autocomplete="off" value="<?php if (!empty($calibration_info)) {
                                                                                      echo $calibration_info->i_edm_a_83;
                                                                                    } ?>" name="i_edm_a_83" type="text" disabled>
                  </div>
                  <div class="input-group" style="margin-bottom:2px;">
                    <div class="input-group-addon"></div>
                    <input class="form-control required" autocomplete="off" value="<?php if (!empty($calibration_info)) {
                                                                                      echo $calibration_info->i_edm_a_84;
                                                                                    } ?>" name="i_edm_a_84" type="text" disabled>
                  </div>

                </div>
              </td>

              <td>
                <div class="form-group form-group-bottom">
                  <div class="input-group" style="margin-bottom:2px;">
                    <div class="input-group-addon"></div>
                    <input class="form-control required" autocomplete="off" value="<?php if (!empty($calibration_info)) {
                                                                                      echo $dresult14;
                                                                                    } ?>" name="i_edm_a_5" type="text" disabled>
                  </div>
                  <div class="input-group" style="margin-bottom:2px;">
                    <div class="input-group-addon"></div>
                    <input class="form-control required" autocomplete="off" value="<?php if (!empty($calibration_info)) {
                                                                                      echo $calibration_info->hidden;
                                                                                    } ?>" name="i_edm_a_6" type="text" disabled>
                  </div>

                </div>
              </td>
              <td>
                <div class="form-group form-group-bottom">
                  <div class="input-group" style="margin-bottom:2px;">
                    <div class="input-group-addon"></div>
                    <input class="form-control required" autocomplete="off" value="<?php if (!empty($calibration_info)) {
                                                                                      echo $eresult14;
                                                                                    } ?>" name="i_edm_a_5" type="text" disabled>
                  </div>
                  <div class="input-group" style="margin-bottom:2px;">
                    <div class="input-group-addon"></div>
                    <input class="form-control required" autocomplete="off" value="<?php if (!empty($calibration_info)) {
                                                                                      echo $calibration_info->hidden;
                                                                                    } ?>" name="i_edm_a_6" type="text" disabled>
                  </div>

                </div>
              </td>
              <td>
                <div class="form-group form-group-bottom">
                  <div class="input-group" style="margin-bottom:2px;">
                    <input class="form-control required" autocomplete="off" value="<?php if (!empty($calibration_info)) {
                                                                                      echo number_format($mean_distance14, 4);
                                                                                    } ?>" name="i_edm_a_5" type="text" disabled>
                  </div>
                  <div class="input-group" style="margin-bottom:2px;">
                    <input class="form-control required" autocomplete="off" value="<?php if (!empty($calibration_info)) {
                                                                                      echo $calibration_info->hidden;
                                                                                    } ?>" name="i_edm_a_6" type="text" disabled>
                  </div>

                </div>
              </td>
              <td>
                <div class="form-group form-group-bottom">
                  <div class="input-group" style="margin-bottom:2px;">
                    <input class="form-control required" autocomplete="off" value="<?php if (!empty($calibration_info)) {
                                                                                      echo number_format($elevation_distance14, 4);
                                                                                    } ?>" name="i_edm_a_5" type="text" disabled>
                  </div>
                  <div class="input-group" style="margin-bottom:2px;">
                    <input class="form-control required" autocomplete="off" value="<?php if (!empty($calibration_info)) {
                                                                                      echo $calibration_info->hidden;
                                                                                    } ?>" name="i_edm_a_6" type="text" disabled>
                  </div>

                </div>
              </td>
              <td>
                <div class="form-group form-group-bottom">
                  <div class="input-group" style="margin-bottom:2px;">
                    <input class="form-control required" autocomplete="off" value="<?php if (!empty($calibration_info)) {
                                                                                      echo number_format($smdistance14, 4);
                                                                                    } ?>" name="i_edm_a_5" type="text" disabled>
                  </div>
                  <div class="input-group" style="margin-bottom:2px;">
                    <input class="form-control required" autocomplete="off" value="<?php if (!empty($calibration_info)) {
                                                                                      echo $calibration_info->hidden;
                                                                                    } ?>" name="i_edm_a_6" type="text" disabled>
                  </div>

                </div>
              </td>
              <td>
                <div class="form-group form-group-bottom">
                  <div class="input-group" style="margin-bottom:2px;">
                    <input class="form-control required" autocomplete="off" value="<?php if (!empty($calibration_info)) {
                                                                                      echo number_format($smelevation14, 4);
                                                                                    }   ?>" name="i_edm_a_5" type="text" disabled>
                  </div>
                  <div class="input-group" style="margin-bottom:2px;">
                    <input class="form-control required" autocomplete="off" value="<?php if (!empty($calibration_info)) {
                                                                                      echo $calibration_info->hidden;
                                                                                    } ?>" name="i_edm_a_6" type="text" disabled>
                  </div>

                </div>
              </td>
            </tr>
            <tr>
              <td class="text-center" style="font-weight:500;">I</td>
              <td>
                <div class="form-group form-group-bottom">
                  <div class="input-group" style="margin-bottom:2px;">
                    <div class="input-group-addon"></div>
                    <input class="form-control required" autocomplete="off" value="<?php if (!empty($calibration_info)) {
                                                                                      echo $calibration_info->i_edm_a_85;
                                                                                    } ?>" name="i_edm_a_85" type="text" disabled>
                  </div>
                  <div class="input-group" style="margin-bottom:2px;">
                    <div class="input-group-addon"></div>
                    <input class="form-control required" autocomplete="off" value="<?php if (!empty($calibration_info)) {
                                                                                      echo $calibration_info->i_edm_a_86;
                                                                                    } ?>" name="i_edm_a_86" type="text" disabled>
                  </div>

                </div>
              </td>
              <td>
                <div class="form-group form-group-bottom">
                  <div class="input-group" style="margin-bottom:2px;">
                    <div class="input-group-addon"></div>
                    <input class="form-control required" autocomplete="off" value="<?php if (!empty($calibration_info)) {
                                                                                      echo $calibration_info->i_edm_a_87;
                                                                                    } ?>" name="i_edm_a_87" type="text" disabled>
                  </div>
                  <div class="input-group" style="margin-bottom:2px;">
                    <div class="input-group-addon"></div>
                    <input class="form-control required" autocomplete="off" value="<?php if (!empty($calibration_info)) {
                                                                                      echo $calibration_info->i_edm_a_88;
                                                                                    } ?>" name="i_edm_a_88" type="text" disabled>
                  </div>

                </div>
              </td>
              <td>
                <div class="form-group form-group-bottom">
                  <div class="input-group" style="margin-bottom:2px;">
                    <div class="input-group-addon"></div>
                    <input class="form-control required" autocomplete="off" value="<?php if (!empty($calibration_info)) {
                                                                                      echo $calibration_info->i_edm_a_89;
                                                                                    } ?>" name="i_edm_a_89" type="text" disabled>
                  </div>
                  <div class="input-group" style="margin-bottom:2px;">
                    <div class="input-group-addon"></div>
                    <input class="form-control required" autocomplete="off" value="<?php if (!empty($calibration_info)) {
                                                                                      echo $calibration_info->i_edm_a_90;
                                                                                    } ?>" name="i_edm_a_90" type="text" disabled>
                  </div>

                </div>
              </td>

              <td>
                <div class="form-group form-group-bottom">
                  <div class="input-group" style="margin-bottom:2px;">
                    <div class="input-group-addon"></div>
                    <input class="form-control required" autocomplete="off" value="<?php if (!empty($calibration_info)) {
                                                                                      echo $dresult15;
                                                                                    } ?>" name="i_edm_a_5" type="text" disabled>
                  </div>
                  <div class="input-group" style="margin-bottom:2px;">
                    <div class="input-group-addon"></div>
                    <input class="form-control required" autocomplete="off" value="<?php if (!empty($calibration_info)) {
                                                                                      echo $calibration_info->hidden;
                                                                                    } ?>" name="i_edm_a_6" type="text" disabled>
                  </div>

                </div>
              </td>
              <td>
                <div class="form-group form-group-bottom">
                  <div class="input-group" style="margin-bottom:2px;">
                    <div class="input-group-addon"></div>
                    <input class="form-control required" autocomplete="off" value="<?php if (!empty($calibration_info)) {
                                                                                      echo $eresult15;
                                                                                    } ?>" name="i_edm_a_5" type="text" disabled>
                  </div>
                  <div class="input-group" style="margin-bottom:2px;">
                    <div class="input-group-addon"></div>
                    <input class="form-control required" autocomplete="off" value="<?php if (!empty($calibration_info)) {
                                                                                      echo $calibration_info->hidden;
                                                                                    } ?>" name="i_edm_a_6" type="text" disabled>
                  </div>

                </div>
              </td>
              <td>
                <div class="form-group form-group-bottom">
                  <div class="input-group" style="margin-bottom:2px;">
                    <input class="form-control required" autocomplete="off" value="<?php if (!empty($calibration_info)) {
                                                                                      echo number_format($mean_distance15, 4);
                                                                                    } ?>" name="i_edm_a_5" type="text" disabled>
                  </div>
                  <div class="input-group" style="margin-bottom:2px;">
                    <input class="form-control required" autocomplete="off" value="<?php if (!empty($calibration_info)) {
                                                                                      echo $calibration_info->hidden;
                                                                                    } ?>" name="i_edm_a_6" type="text" disabled>
                  </div>

                </div>
              </td>
              <td>
                <div class="form-group form-group-bottom">
                  <div class="input-group" style="margin-bottom:2px;">
                    <input class="form-control required" autocomplete="off" value="<?php if (!empty($calibration_info)) {
                                                                                      echo number_format($elevation_distance15, 4);
                                                                                    } ?>" name="i_edm_a_5" type="text" disabled>
                  </div>
                  <div class="input-group" style="margin-bottom:2px;">
                    <input class="form-control required" autocomplete="off" value="<?php if (!empty($calibration_info)) {
                                                                                      echo $calibration_info->hidden;
                                                                                    } ?>" name="i_edm_a_6" type="text" disabled>
                  </div>

                </div>
              </td>
              <td>
                <div class="form-group form-group-bottom">
                  <div class="input-group" style="margin-bottom:2px;">
                    <input class="form-control required" autocomplete="off" value="<?php if (!empty($calibration_info)) {
                                                                                      echo number_format($smdistance15, 4);
                                                                                    } ?>" name="i_edm_a_5" type="text" disabled>
                  </div>
                  <div class="input-group" style="margin-bottom:2px;">
                    <input class="form-control required" autocomplete="off" value="<?php if (!empty($calibration_info)) {
                                                                                      echo $calibration_info->hidden;
                                                                                    } ?>" name="i_edm_a_6" type="text" disabled>
                  </div>

                </div>
              </td>
              <td>
                <div class="form-group form-group-bottom">
                  <div class="input-group" style="margin-bottom:2px;">
                    <input class="form-control required" autocomplete="off" value="<?php if (!empty($calibration_info)) {
                                                                                      echo number_format($smelevation15, 4);
                                                                                    }   ?>" name="i_edm_a_5" type="text" disabled>
                  </div>
                  <div class="input-group" style="margin-bottom:2px;">
                    <input class="form-control required" autocomplete="off" value="<?php if (!empty($calibration_info)) {
                                                                                      echo $calibration_info->hidden;
                                                                                    } ?>" name="i_edm_a_6" type="text" disabled>
                  </div>

                </div>
              </td>
            </tr>
            <tr>
              <td class="text-center" style="font-weight:500;">II</td>
              <td>
                <div class="form-group form-group-bottom">
                  <div class="input-group" style="margin-bottom:2px;">
                    <div class="input-group-addon"></div>
                    <input class="form-control required" autocomplete="off" value="<?php if (!empty($calibration_info)) {
                                                                                      echo $calibration_info->i_edm_a_91;
                                                                                    } ?>" name="i_edm_a_91" type="text" disabled>
                  </div>
                  <div class="input-group" style="margin-bottom:2px;">
                    <div class="input-group-addon"></div>
                    <input class="form-control required" autocomplete="off" value="<?php if (!empty($calibration_info)) {
                                                                                      echo $calibration_info->i_edm_a_92;
                                                                                    } ?>" name="i_edm_a_92" type="text" disabled>
                  </div>

                </div>
              </td>
              <td>
                <div class="form-group form-group-bottom">
                  <div class="input-group" style="margin-bottom:2px;">
                    <div class="input-group-addon"></div>
                    <input class="form-control required" autocomplete="off" value="<?php if (!empty($calibration_info)) {
                                                                                      echo $calibration_info->i_edm_a_93;
                                                                                    } ?>" name="i_edm_a_93" type="text" disabled>
                  </div>
                  <div class="input-group" style="margin-bottom:2px;">
                    <div class="input-group-addon"></div>
                    <input class="form-control required" autocomplete="off" value="<?php if (!empty($calibration_info)) {
                                                                                      echo $calibration_info->i_edm_a_94;
                                                                                    } ?>" name="i_edm_a_94" type="text" disabled>
                  </div>

                </div>
              </td>
              <td>
                <div class="form-group form-group-bottom">
                  <div class="input-group" style="margin-bottom:2px;">
                    <div class="input-group-addon"></div>
                    <input class="form-control required" autocomplete="off" value="<?php if (!empty($calibration_info)) {
                                                                                      echo $calibration_info->i_edm_a_95;
                                                                                    } ?>" name="i_edm_a_95" type="text" disabled>
                  </div>
                  <div class="input-group" style="margin-bottom:2px;">
                    <div class="input-group-addon"></div>
                    <input class="form-control required" autocomplete="off" value="<?php if (!empty($calibration_info)) {
                                                                                      echo $calibration_info->i_edm_a_96;
                                                                                    } ?>" name="i_edm_a_96" type="text" disabled>
                  </div>

                </div>
              </td>

              <td>
                <div class="form-group form-group-bottom">
                  <div class="input-group" style="margin-bottom:2px;">
                    <div class="input-group-addon"></div>
                    <input class="form-control required" autocomplete="off" value="<?php if (!empty($calibration_info)) {
                                                                                      echo $dresult16;
                                                                                    } ?>" name="i_edm_a_5" type="text" disabled>
                  </div>
                  <div class="input-group" style="margin-bottom:2px;">
                    <div class="input-group-addon"></div>
                    <input class="form-control required" autocomplete="off" value="<?php if (!empty($calibration_info)) {
                                                                                      echo $calibration_info->hidden;
                                                                                    } ?>" name="i_edm_a_6" type="text" disabled>
                  </div>

                </div>
              </td>
              <td>
                <div class="form-group form-group-bottom">
                  <div class="input-group" style="margin-bottom:2px;">
                    <div class="input-group-addon"></div>
                    <input class="form-control required" autocomplete="off" value="<?php if (!empty($calibration_info)) {
                                                                                      echo $eresult16;
                                                                                    } ?>" name="i_edm_a_5" type="text" disabled>
                  </div>
                  <div class="input-group" style="margin-bottom:2px;">
                    <div class="input-group-addon"></div>
                    <input class="form-control required" autocomplete="off" value="<?php if (!empty($calibration_info)) {
                                                                                      echo $calibration_info->hidden;
                                                                                    } ?>" name="i_edm_a_6" type="text" disabled>
                  </div>

                </div>
              </td>

              <td>
                <div class="form-group form-group-bottom">
                  <div class="input-group" style="margin-bottom:2px;">
                    <input class="form-control required" autocomplete="off" value="<?php if (!empty($calibration_info)) {
                                                                                      echo number_format($mean_distance16, 4);
                                                                                    } ?>" name="i_edm_a_5" type="text" disabled>
                  </div>
                  <div class="input-group" style="margin-bottom:2px;">
                    <input class="form-control required" autocomplete="off" value="<?php if (!empty($calibration_info)) {
                                                                                      echo $calibration_info->hidden;
                                                                                    } ?>" name="i_edm_a_6" type="text" disabled>
                  </div>

                </div>
              </td>
              <td>
                <div class="form-group form-group-bottom">
                  <div class="input-group" style="margin-bottom:2px;">
                    <input class="form-control required" autocomplete="off" value="<?php if (!empty($calibration_info)) {
                                                                                      echo number_format($elevation_distance16, 4);
                                                                                    } ?>" name="i_edm_a_5" type="text" disabled>
                  </div>
                  <div class="input-group" style="margin-bottom:2px;">
                    <input class="form-control required" autocomplete="off" value="<?php if (!empty($calibration_info)) {
                                                                                      echo $calibration_info->hidden;
                                                                                    } ?>" name="i_edm_a_6" type="text" disabled>
                  </div>

                </div>
              </td>
              <td>
                <div class="form-group form-group-bottom">
                  <div class="input-group" style="margin-bottom:2px;">
                    <input class="form-control required" autocomplete="off" value="<?php if (!empty($calibration_info)) {
                                                                                      echo number_format($smdistance16, 4);
                                                                                    } ?>" name="i_edm_a_5" type="text" disabled>
                  </div>
                  <div class="input-group" style="margin-bottom:2px;">
                    <input class="form-control required" autocomplete="off" value="<?php if (!empty($calibration_info)) {
                                                                                      echo $calibration_info->hidden;
                                                                                    } ?>" name="i_edm_a_6" type="text" disabled>
                  </div>

                </div>
              </td>
              <td>
                <div class="form-group form-group-bottom">
                  <div class="input-group" style="margin-bottom:2px;">
                    <input class="form-control required" autocomplete="off" value="<?php if (!empty($calibration_info)) {
                                                                                      echo number_format($smelevation16, 4);
                                                                                    }   ?>" name="i_edm_a_5" type="text" disabled>
                  </div>
                  <div class="input-group" style="margin-bottom:2px;">
                    <input class="form-control required" autocomplete="off" value="<?php if (!empty($calibration_info)) {
                                                                                      echo $calibration_info->hidden;
                                                                                    } ?>" name="i_edm_a_6" type="text" disabled>
                  </div>

                </div>
              </td>
            </tr>


            <tr style="background-color: #d3d3d3;">
              <th></th>
              <th></th>
              <th></th>
              <th>Mean</th>
              <th><?php if (!empty($mean_total1)) {
                    echo number_format($mean_total1, 4);
                  } ?></th>
              <th><?php if (!empty($elevation_total1)) {
                    echo number_format($elevation_total1, 4);
                  } ?></th>
              <th></th>
              <th>Sum</th>
              <th><?php if (!empty($elevation_total)) {
                    echo number_format($total_smdistance1, 4);
                  } ?></th>
              <th><?php if (!empty($elevation_total)) {
                    echo number_format($total_smelevation1, 4);
                  } ?></th>
            </tr>
            <tr>
              <th></th>
              <th></th>
              <th></th>
              <th></th>
              <th></th>
              <th>Standard Errors (Max & Absolute)/2</th>
              <th><?php if (!empty($standard_error3)) {
                    echo number_format($standard_error3, 4);
                  } ?></th>
              <th><?php if (!empty($standard_error4)) {
                    echo number_format($standard_error4, 4);
                  } ?></th>
              <th></th>
              <th></th>
            </tr>
            <tr>
              <th></th>
              <th></th>
              <th></th>
              <th></th>
              <th></th>
              <th></th>
              <th></th>
              <th>Degree of Freedom</th>
              <th><?php if (!empty($dfresult3)) {
                    echo number_format($dfresult3, 4);
                  } ?></th>
              <th><?php if (!empty($dfresult4)) {
                    echo number_format($dfresult4, 4);
                  } ?></th>
            <tr>
              <th></th>
              <th></th>
              <th></th>
              <th></th>
              <th></th>
              <th></th>
              <th></th>
              <th></th>
              <th></th>
              <th style="
                                    <?php
                                    if ($standard_error3 < $t_v_a_10 && $standard_error4 < $t_v_a_10 && $dfresult3 < $t_v_a_10 && $dfresult4 < $t_v_a_10) {
                                      echo 'background-color: lightgreen; color: white;';
                                    } else {
                                      echo 'background-color: red; color: white;';
                                    }
                                    ?>
                                ">
                <?php
                if ($standard_error3 < $t_v_a_10 && $standard_error3 < $t_v_a_10 && $dfresult4 < $t_v_a_10 && $dfresult4 < $t_v_a_10) {
                  echo 'PASSED';
                } else {
                  echo 'FAILED';
                }
                ?>
              </th>
            </tr>
          </tbody>
        </table>
        <button type="button" class="btn btn-secondary prev-step">VIEW PRE-CALIBRATION CHECKS</button>
      </div>
      <div class="clearfix mtop30"></div>
    <?php } else if ($service_info->item_type == 'Theodolite') { ?>

      <b>INSTRUMENT INFORMATION</b>
      <table class="table" id="serviceFields">
        <hr>
        <tbody>
          <tr class="form-inline">
            <td class="align-middle text-left" style="padding: 10px; border: 1px solid #ccc;">INSTRUMENT MAKE
              <div class="form-group">
                <div class="input-group">
                  <input type="text" class="form-control required" autocomplete="on" placeholder="FOIF" id="_th_v_a_1" name="th_v_a_1" value="<?php if (!empty($calibration_info)) {
                                                                                                                                                echo $calibration_info->th_v_a_1;
                                                                                                                                              } ?>" disabled>
                </div>
              </div>
            </td>
            <td class="align-middle text-left" style="padding: 10px; border: 1px solid #ccc;">INSTRUMENT MODEL
              <div class="form-group">
                <div class="input-group text-center">
                  <input type="text" class="form-control required" autocomplete="on" placeholder="AL132" name="th_v_a_2" value="<?php if (!empty($calibration_info)) {
                                                                                                                                  echo $calibration_info->th_v_a_2;
                                                                                                                                } ?>" disabled>
                </div>
              </div>
            </td>
            <td class="align-middle text-left" style="padding: 10px; border: 1px solid #ccc;">INSTRUMENT SERIAL NO.
              <div class="form-group">
                <div class="input-group text-center">
                  <input type="text" class="form-control required" autocomplete="on" placeholder="Z6692H99" name="th_v_a_3" value="<?php if (!empty($calibration_info)) {
                                                                                                                                      echo $calibration_info->th_v_a_3;
                                                                                                                                    } ?>" disabled>
                </div>
              </div>
            </td>
            <td class="align-middle text-left" style="padding: 10px; border: 1px solid #ccc;">INSTRUMENT CONDITION
              <div class="form-group">
                <div class="input-group text-center">
                  <input type="text" class="form-control required" autocomplete="on" placeholder="USED" name="th_v_a_4" value="<?php if (!empty($calibration_info)) {
                                                                                                                                  echo $calibration_info->th_v_a_4;
                                                                                                                                } ?>" disabled>
                </div>
              </div>
            </td>
          </tr>
          <tr class="form-inline">
            <td class="align-middle text-left" style="padding: 10px; border: 1px solid #ccc;">BASELINE TEST DISTANCE (M)
              <div class="form-group">
                <div class="input-group text-center">
                  <input type="text" class="form-control required" autocomplete="on" placeholder="20" name="th_v_a_5" value="<?php if (!empty($calibration_info)) {
                                                                                                                                echo $calibration_info->th_v_a_5;
                                                                                                                              } ?>" disabled>
                </div>
              </div>
            </td>
            <td class="align-middle text-left" style="padding: 10px; border: 1px solid #ccc;">WEATHER CONDITION
              <div class="form-group">
                <div class="input-group text-center">
                  <input type="text" class="form-control required" autocomplete="on" placeholder="CLOUDY" name="th_v_a_6" value="<?php if (!empty($calibration_info)) {
                                                                                                                                    echo $calibration_info->th_v_a_6;
                                                                                                                                  } ?>" disabled>
                </div>
              </div>
            </td>
            <td class="align-middle text-left" style="padding: 10px; border: 1px solid #ccc;">TEMPERATURE (°C)
              <div class="form-group">
                <div class="input-group text-center">
                  <input type="text" class="form-control required" autocomplete="on" placeholder="32" name="th_v_a_7" value="<?php if (!empty($calibration_info)) {
                                                                                                                                echo $calibration_info->th_v_a_7;
                                                                                                                              } ?>" disabled>
                </div>
              </div>
            </td>
            <td class="align-middle text-left" style="padding: 10px; border: 1px solid #ccc;">AIR PRESSURE (hPa)
              <div class="form-group">
                <div class="input-group text-center">
                  <input type="text" class="form-control required" autocomplete="on" placeholder="101" name="th_v_a_8" value="<?php if (!empty($calibration_info)) {
                                                                                                                                echo $calibration_info->th_v_a_8;
                                                                                                                              } ?>" disabled>
                </div>
              </div>
            </td>
          </tr>
          <tr class="form-inline">
            <td class="align-middle text-left" style="padding: 10px; border: 1px solid #ccc;">MANUFACTURER ACCURACY (00'')
              <div class="form-group">
                <div class="input-group text-center">
                  <input type="text" class="form-control required" autocomplete="on" placeholder="19.994" name="th_v_a_9" value="<?php if (!empty($calibration_info)) {
                                                                                                                                    echo $calibration_info->th_v_a_9;
                                                                                                                                  } ?>" disabled>
                </div>
              </div>
            </td>

            <td class="align-middle text-left" style="padding: 10px; border: 1px solid #ccc;">TARGET PRISM CONSTANT (MM)
              <div class="form-group">
                <div class="input-group text-center">
                  <input type="text" class="form-control required" autocomplete="on" placeholder="15" name="th_v_a_10" value="<?php if (!empty($calibration_info)) {
                                                                                                                                echo $calibration_info->th_v_a_10;
                                                                                                                              } ?>" disabled>
                </div>
              </div>
            </td>
            <td class="align-middle text-left" style="padding: 10px; border: 1px solid #ccc;">INSTRUMENT PRISM CONSTANT (MM)
              <div class="form-group">
                <div class="input-group text-center">
                  <input type="text" class="form-control required" autocomplete="on" placeholder="2.2" name="th_v_a_11" value="<?php if (!empty($calibration_info)) {
                                                                                                                                  echo $calibration_info->th_v_a_11;
                                                                                                                                } ?>" disabled>
                </div>
              </div>
            </td>
            <td class="align-middle text-left" style="padding: 10px; border: 1px solid #ccc;">INSTRUMENT HEIGHT (M)
              <div class="form-group">
                <div class="input-group text-center">
                  <input type="text" class="form-control required" autocomplete="on" placeholder="2.2" name="th_v_a_12" value="<?php if (!empty($calibration_info)) {
                                                                                                                                  echo $calibration_info->th_v_a_12;
                                                                                                                                } ?>" disabled>
                </div>
              </div>
            </td>
          </tr>
          <tr class="form-inline">
            <td class="align-middle text-left" style="padding: 10px; border: 1px solid #ccc;">TARGET MAKE
              <div class="form-group">
                <div class="input-group text-center">
                  <input type="text" class="form-control required" autocomplete="on" placeholder="2.2" name="th_v_a_13" value="<?php if (!empty($calibration_info)) {
                                                                                                                                  echo $calibration_info->th_v_a_13;
                                                                                                                                } ?>" disabled>
                </div>
              </div>
            </td>
            <td class="align-middle text-left" style="padding: 10px; border: 1px solid #ccc;">TARGET MODEL
              <div class="form-group">
                <div class="input-group text-center">
                  <input type="text" class="form-control required" autocomplete="on" placeholder="2.2" name="th_v_a_14" value="<?php if (!empty($calibration_info)) {
                                                                                                                                  echo $calibration_info->th_v_a_14;
                                                                                                                                } ?>" disabled>
                </div>
              </div>
            </td>
          </tr>
        </tbody>
      </table>
      <b>PRE-CALIBRATION INDEX ERROR CHECKS</b><br><br>
      <table class="table table-bordered table-striped dataTable">
        <thead>
          <tr>
            <th></th>
            <th colspan="2" style="text-align:center; font-weight:500;" class="total">HORIZONTAL CIRCLE INDEX ERROR</th>
            <th colspan="2" style="text-align:center; font-weight:500;">VERTICAL CIRCLE INDEX ERROR</th>
          </tr>
          <tr>
            <th class="desc" style="text-align:center; font-weight:500;">FACE</th>
            <th class="total" style="text-align:center; font-weight:500;">START A</th>
            <th class="total" style="text-align:center; font-weight:500;">END B</th>
            <th class="desc" style="text-align:center; font-weight:500;">START A</th>
            <th class="desc" style="text-align:center; font-weight:500;">END B</th>
          </tr>
        </thead>
        <tbody>
          <?php
          $th_h_a = $calibration_info->th_h_a;
          $thh_h_a = $calibration_info->thh_h_a;
          $sum_h_a = $th_h_a + $thh_h_a;
          $d_error_h_a = (dms2dec(180, 0, 0) - $sum_h_a);

          $th_h_b = $calibration_info->th_h_b;
          $thh_h_b = $calibration_info->thh_h_b;
          $sum_h_b = $th_h_b + $thh_h_b;
          $d_error_h_b = (dms2dec(180, 0, 0) - $sum_h_b);

          $th_v_a = $calibration_info->th_v_a;
          $thh_v_b = $calibration_info->thh_v_b;
          $sum_v_a = $th_v_a + $thh_v_b;
          $d_error_v_a = (dms2dec(360, 0, 0) - $sum_v_a);
          $d_error_v_a_2 = $d_error_v_a / 2;

          $thh_v_a = $calibration_info->thh_v_a;
          $thh_v_c = $calibration_info->thh_v_c;
          $sum_v_b = $thh_v_a + $thh_v_c;
          $d_error_v_b = (dms2dec(360, 0, 0) - $sum_v_b);

          $i_edm_a_1 = number_format(round($calibration_info->i_edm_a_1, 3), 3);
          $i_edm_a_2 = number_format(round($calibration_info->i_edm_a_2, 3), 3);
          $i_edm_a_3 = number_format(round($calibration_info->i_edm_a_3, 3), 3);
          $f_i_edm_a = ($i_edm_a_1 + $i_edm_a_2 + $i_edm_a_3) / 3;

          $i_edm_b_3 = number_format(round($calibration_info->i_edm_b_3, 3), 3);
          $i_edm_b_4 = number_format(round($calibration_info->i_edm_b_4, 3), 3);
          $i_edm_b_3 = number_format(round($calibration_info->i_edm_b_3, 3), 3);
          $f_i_edm_b = ($i_edm_b_1 + $i_edm_b_2 + $i_edm_b_3) / 3;

          $ii_edm_a_1 = number_format(round($calibration_info->ii_edm_a_1, 3), 3);
          $ii_edm_a_2 = number_format(round($calibration_info->ii_edm_a_2, 3), 3);
          $ii_edm_a_3 = number_format(round($calibration_info->ii_edm_a_3, 3), 3);
          $f_ii_edm_a = ($ii_edm_a_1 + $ii_edm_a_2 + $ii_edm_a_3) / 3;

          $ii_edm_b_1 = number_format(round($calibration_info->ii_edm_b_1, 3), 3);
          $ii_edm_b_2 = number_format(round($calibration_info->ii_edm_b_2, 3), 3);
          $ii_edm_b_3 = number_format(round($calibration_info->ii_edm_b_3, 3), 3);
          $f_ii_edm_b = ($ii_edm_b_1 + $ii_edm_b_2 + $ii_edm_b_3) / 3;

          $f_i_ii_edm_a = ($f_i_edm_a + $f_ii_edm_a) / 2;
          $f_i_ii_edm_b = ($f_i_edm_b + $f_ii_edm_b) / 2;

          $th_v_a_9 = $calibration_info->th_v_a_9;


          $result = '';

          // Check the condition and set the result accordingly
          if ($d_error_v_a_2 > $th_v_a_9 && $d_error_v_a > $th_v_a_9) {
            // Fail case
            $result = '<td colspan="4" class="total" style="text-align:center; background-color: rgba(255, 0, 0, 0.3); color: black;"><b>FAILED</b></td>';
          } else {
            // Pass case
            $result = '<td colspan="4" class="total" style="text-align:center; background-color: rgba(0, 255, 0, 0.3); color: black;"><b>PASSED</b></td>';
          }
          ?>
          <tr>
            <td class="desc" style="text-align:center; font-weight:500;"><b>I</b></td>
            <td class="total" style="text-align:center;"><?php echo dec2dms_full($th_h_a); ?></td>
            <td class="total" style="text-align:center;"><?php echo dec2dms_full($th_h_b); ?></td>
            <td class="desc" style="text-align:center;"><?php echo dec2dms_full($th_v_a); ?></td>
            <td class="desc" style="text-align:center;"><?php echo dec2dms_full($thh_v_a); ?></td>
          </tr>
          <tr>
            <td class="desc" style="text-align:center; font-weight:500;"><b>II</b></td>
            <td class="total" style="text-align:center;"><?php echo dec2dms_full($thh_h_a); ?></td>
            <td class="total" style="text-align:center;"><?php echo dec2dms_full($thh_h_b); ?></td>
            <td class="desc" style="text-align:center;"><?php echo dec2dms_full($thh_v_b); ?></td>
            <td class="desc" style="text-align:center;"><?php echo dec2dms_full($thh_v_c); ?></td>
          </tr>
          <tr>
            <td></td>
            <td colspan="2" class="text-center total"><b>Half Circle is 180&deg; 00' 00"</b></td>
            <td colspan="2" class="text-center"><b>Half Circle is 360&deg; 00' 00"</b></td>
          </tr>
          <tr>
            <td class="desc" style="text-align:center; font-weight:500;"><b>SUM(I+II)</b></td>
            <td class="total" style="text-align:center;"><?php echo dec2dms_full($sum_h_a); ?></td>
            <td class="total" style="text-align:center;"><?php echo dec2dms_full($sum_h_b); ?></td>
            <td class="desc" style="text-align:center;"><?php echo dec2dms_full($sum_v_a); ?></td>
            <td class="desc" style="text-align:center;"><?php echo dec2dms_full($sum_v_b); ?></td>
          </tr>
          <tr>
            <td></td>
            <td colspan="2" class="text-center total"><b>(180&deg; 00' 00" - SUM(A+B))/2</b></td>
            <td colspan="2" class="text-center"><b>(360&deg; 00' 00" - SUM(A+B))/2</b></td>
          </tr>
          <tr>
            <td class="desc" style="text-align:center; font-weight:500;"><b>Double Error</b></td>
            <td class="total" style="text-align:center;"><?php echo dec2dms_full($d_error_h_a); ?></td>
            <td class="total" style="text-align:center;"><?php echo dec2dms_full($d_error_h_b); ?></td>
            <td class="desc" style="text-align:center;"><?php echo dec2dms_full($d_error_v_a); ?></td>
            <td class="desc" style="text-align:center;"><?php echo dec2dms_full($d_error_v_b); ?></td>
          </tr>
          <tr>
            <td class="desc" style="text-align:center; font-weight:500;"><b>Index Error (Double error/2)</b></td>
            <td class="total" style="text-align:center;"><?php echo dec2dms_full($d_error_h_a); ?></td>
            <td class="total" style="text-align:center;"><?php echo dec2dms_full($d_error_h_b); ?></td>
            <td class="desc" style="text-align:center;"><?php echo dec2dms_full($d_error_v_a_2); ?></td>
            <td class="desc" style="text-align:center;"><?php echo dec2dms_full($d_error_v_b); ?></td>
          </tr>
          <tr>
            <td class="desc" style="text-align:center; font-weight:500;"><b></b></td>
            <!-- Output the stored result here -->
            <?php echo $result; ?>
          </tr>

        </tbody>
      </table><br>

      <b>POST-CALIBRATION INDEX ERROR CHECKS</b><br><br>
      <table class="table table-bordered table-striped dataTable">
        <thead>
          <tr>
            <th></th>
            <th colspan="2" style="text-align:center; font-weight:500;" class="total">HORIZONTAL CIRCLE INDEX ERROR</th>
            <th colspan="2" style="text-align:center; font-weight:500;">VERTICAL CIRCLE INDEX ERROR</th>
          </tr>
          <tr>
            <th class="desc" style="text-align:center; font-weight:500;">FACE</th>
            <th class="total" style="text-align:center; font-weight:500;">START A</th>
            <th class="total" style="text-align:center; font-weight:500;">END B</th>
            <th class="desc" style="text-align:center; font-weight:500;">START A</th>
            <th class="desc" style="text-align:center; font-weight:500;">END B</th>
          </tr>
        </thead>
        <tbody>
          <?php
          $th_h_a1 = $calibration_info->th_h_a1;
          $thh_h_a1 = $calibration_info->thh_h_a1;
          $sum_h_a1 = $th_h_a1 + $thh_h_a1;
          $d_error_h_a1 = (dms2dec(180, 0, 0) - $sum_h_a1);

          $th_h_b1 = $calibration_info->th_h_b1;
          $thh_h_b1 = $calibration_info->thh_h_b1;
          $sum_h_b1 = $th_h_b1 + $thh_h_b1;
          $d_error_h_b1 = (dms2dec(180, 0, 0) - $sum_h_b1);

          $th_v_a1 = $calibration_info->th_v_a1;
          $thh_v_b1 = $calibration_info->thh_v_b1;
          $sum_v_a1 = $th_v_a1 + $thh_v_b1;
          $d_error_v_a1 = (dms2dec(360, 0, 0) - $sum_v_a1);

          $thh_v_a1 = $calibration_info->thh_v_a1;
          $thh_v_c1 = $calibration_info->thh_v_c1;
          $sum_v_b1 = $thh_v_a1 + $thh_v_c1;
          $d_error_v_b1 = (dms2dec(360, 0, 0) - $sum_v_b1);
          $d_error_v_b1_4 = $d_error_v_a1 / 2;


          $i_edm_a_1 = number_format(round($calibration_info->i_edm_a_1, 3), 3);
          $i_edm_a_2 = number_format(round($calibration_info->i_edm_a_2, 3), 3);
          $i_edm_a_3 = number_format(round($calibration_info->i_edm_a_3, 3), 3);
          $f_i_edm_a = ($i_edm_a_1 + $i_edm_a_2 + $i_edm_a_3) / 3;

          $i_edm_b_1 = number_format(round($calibration_info->i_edm_b_1, 3), 3);
          $i_edm_b_2 = number_format(round($calibration_info->i_edm_b_2, 3), 3);
          $i_edm_b_3 = number_format(round($calibration_info->i_edm_b_3, 3), 3);
          $f_i_edm_b = ($i_edm_b_1 + $i_edm_b_2 + $i_edm_b_3) / 3;

          $ii_edm_a_1 = number_format(round($calibration_info->ii_edm_a_1, 3), 3);
          $ii_edm_a_2 = number_format(round($calibration_info->ii_edm_a_2, 3), 3);
          $ii_edm_a_3 = number_format(round($calibration_info->ii_edm_a_3, 3), 3);
          $f_ii_edm_a = ($ii_edm_a_1 + $ii_edm_a_2 + $ii_edm_a_3) / 3;

          $ii_edm_b_1 = number_format(round($calibration_info->ii_edm_b_1, 3), 3);
          $ii_edm_b_2 = number_format(round($calibration_info->ii_edm_b_2, 3), 3);
          $ii_edm_b_3 = number_format(round($calibration_info->ii_edm_b_3, 3), 3);
          $f_ii_edm_b = ($ii_edm_b_1 + $ii_edm_b_2 + $ii_edm_b_3) / 3;

          $f_i_ii_edm_a = ($f_i_edm_a + $f_ii_edm_a) / 2;
          $f_i_ii_edm_b = ($f_i_edm_b + $f_ii_edm_b) / 2;

          $th_v_a_9 = $calibration_info->th_v_a_9;



          $result1 = '';

          // Check the condition and set the result accordingly
          if ($d_error_v_b1_4 > $th_v_a_9 && $d_error_v_b1_4 > $th_v_a_9) {
            // Fail case
            $result1 = '<td colspan="4" class="total" style="text-align:center; background-color: rgba(255, 0, 0, 0.3); color: black;"><b>FAILED</b></td>';
          } else {
            // Pass case
            $result1 = '<td colspan="4" class="total" style="text-align:center; background-color: rgba(0, 255, 0, 0.3); color: black;"><b>PASSED</b></td>';
          }
          ?>
          <tr>
            <td class="desc" style="text-align:center; font-weight:500;"><b>I</b></td>
            <td class="total" style="text-align:center;"><?php echo dec2dms_full($th_h_a1); ?></td>
            <td class="total" style="text-align:center;"><?php echo dec2dms_full($th_h_b1); ?></td>
            <td class="desc" style="text-align:center;"><?php echo dec2dms_full($th_v_a1); ?></td>
            <td class="desc" style="text-align:center;"><?php echo dec2dms_full($thh_v_a1); ?></td>
          </tr>
          <tr>
            <td class="desc" style="text-align:center; font-weight:500;"><b>II</b></td>
            <td class="total" style="text-align:center;"><?php echo dec2dms_full($thh_h_a1); ?></td>
            <td class="total" style="text-align:center;"><?php echo dec2dms_full($thh_h_b1); ?></td>
            <td class="desc" style="text-align:center;"><?php echo dec2dms_full($thh_v_b1); ?></td>
            <td class="desc" style="text-align:center;"><?php echo dec2dms_full($thh_v_c1); ?></td>
          </tr>
          <tr>
            <td></td>
            <td colspan="2" class="text-center total"><b>Half Circle is 180&deg; 00' 00"</b></td>
            <td colspan="2" class="text-center"><b>Half Circle is 360&deg; 00' 00"</b></td>
          </tr>
          <tr>
            <td class="desc" style="text-align:center; font-weight:500;"><b>SUM(I+II)</b></td>
            <td class="total" style="text-align:center;"><?php echo dec2dms_full($sum_h_a1); ?></td>
            <td class="total" style="text-align:center;"><?php echo dec2dms_full($sum_h_b1); ?></td>
            <td class="desc" style="text-align:center;"><?php echo dec2dms_full($sum_v_a1); ?></td>
            <td class="desc" style="text-align:center;"><?php echo dec2dms_full($sum_v_b1); ?></td>
          </tr>
          <tr>
            <td></td>
            <td colspan="2" class="text-center total"><b>(180&deg; 00' 00" - SUM(A+B))/2</b></td>
            <td colspan="2" class="text-center"><b>(360&deg; 00' 00" - SUM(A+B))/2</b></td>
          </tr>
          <tr>
            <td class="desc" style="text-align:center; font-weight:500;"><b>Double Error</b></td>
            <td class="total" style="text-align:center;"><?php echo dec2dms_full($d_error_h_a1); ?></td>
            <td class="total" style="text-align:center;"><?php echo dec2dms_full($d_error_h_b1); ?></td>
            <td class="desc" style="text-align:center;"><?php echo dec2dms_full($d_error_v_a1); ?></td>
            <td class="desc" style="text-align:center;"><?php echo dec2dms_full($d_error_v_b1); ?></td>
          </tr>
          <tr>
            <td class="desc" style="text-align:center; font-weight:500;"><b>Index Error (Double error/2)</b></td>
            <td class="total" style="text-align:center;"><?php echo dec2dms_full($d_error_h_a1); ?></td>
            <td class="total" style="text-align:center;"><?php echo dec2dms_full($d_error_h_b1); ?></td>
            <td class="desc" style="text-align:center;"><?php echo dec2dms_full($d_error_v_b1_4); ?>;</td>
            <td class="desc" style="text-align:center;"><?php echo dec2dms_full($d_error_v_b1); ?></td>
          </tr>
          <tr>
            <td class="desc" style="text-align:center; font-weight:500;"><b></b></td>
            <!-- Output the stored result here -->
            <?php echo $result1; ?>
          </tr>
        </tbody>
      </table><br>


    <?php } else if ($service_info->item_type == 'GNSS') { ?>
      <div class="form-section current table-responsive">
        <?php
        // Assuming the form is submitted via POST
        $r_v_a_1 = $calibration_info->r_v_a_1;
        $r_v_a_2 = $calibration_info->r_v_a_2;
        $r_v_a_3 = $calibration_info->r_v_a_3;
        $r_v_a_4 = $calibration_info->r_v_a_4;
        $r_v_a_5 = $calibration_info->r_v_a_5;
        $r_v_a_6 = $calibration_info->r_v_a_6;
        $r_v_a_7 = $calibration_info->r_v_a_7;
        $r_v_a_8 = $calibration_info->r_v_a_8;
        $r_v_a_9 = $calibration_info->r_v_a_9;
        $r_v_a_10 = $calibration_info->r_v_a_10;
        $r_v_a_11 = $calibration_info->r_v_a_11;
        $r_v_a_12 = $calibration_info->r_v_a_12;
        $r_v_a_13 = $calibration_info->r_v_a_13;
        ?>
        <table class="table" id="serviceFields">
          <hr>
          <p>
          <h6 class="btn btn-secondary" disabled><b>INSTRUMENT INFORMATION</b></h6>
          </p>
          <tbody>
            <tr class="form-inline">
              <td class="align-middle text-left" style="padding: 10px; border: 1px solid #ccc;">INSTRUMENT MAKE
                <div class="form-group">
                  <div class="input-group">
                    <input type="text" class="form-control required" autocomplete="on" placeholder="" id="_r_v_a_1" name="r_v_a_1" value="<?php echo ($r_v_a_1); ?>" disabled>
                  </div>
                </div>
              </td>
              <td class="align-middle text-left" style="padding: 10px; border: 1px solid #ccc;">INSTRUMENT MODEL
                <div class="form-group">
                  <div class="input-group text-center">
                    <input type="text" class="form-control required" autocomplete="on" placeholder="AL132" name="r_v_a_2" value="<?php echo ($r_v_a_2); ?>" disabled>
                  </div>
                </div>
              </td>
              <td class="align-middle text-left" style="padding: 10px; border: 1px solid #ccc;">INSTRUMENT SERIAL NO.
                <div class="form-group">
                  <div class="input-group text-center">
                    <input type="text" class="form-control required" autocomplete="on" placeholder="Z6692H99" name="r_v_a_3" value="<?php echo ($r_v_a_3); ?>" disabled>
                  </div>
                </div>
              </td>
              <td class="align-middle text-left" style="padding: 10px; border: 1px solid #ccc;">INSTRUMENT CONDITION
                <div class="form-group">
                  <div class="input-group text-center">
                    <input type="text" class="form-control required" autocomplete="on" placeholder="USED" name="r_v_a_4" value="<?php echo ($r_v_a_4); ?>" disabled>
                  </div>
                </div>
              </td>
            </tr>
            <tr class="form-inline">
              <td class="align-middle text-left" style="padding: 10px; border: 1px solid #ccc;">TEST DISTANCE (M)
                <div class="form-group">
                  <div class="input-group text-center">
                    <input type="text" class="form-control required" autocomplete="on" placeholder="20" name="r_v_a_5" value="<?php echo ($r_v_a_5); ?>" disabled>
                  </div>
                </div>
              </td>
              <td class="align-middle text-left" style="padding: 10px; border: 1px solid #ccc;">WEATHER CONDITION
                <div class="form-group">
                  <div class="input-group text-center">
                    <input type="text" class="form-control required" autocomplete="on" placeholder="CLOUDY" name="r_v_a_6" value="<?php echo ($r_v_a_6); ?>" disabled>
                  </div>
                </div>
              </td>
              <td class="align-middle text-left" style="padding: 10px; border: 1px solid #ccc;">TEMPERATURE (°C)
                <div class="form-group">
                  <div class="input-group text-center">
                    <input type="text" class="form-control required" autocomplete="on" placeholder="32" name="r_v_a_7" value="<?php echo ($r_v_a_7); ?>" disabled>
                  </div>
                </div>
              </td>
              <td class="align-middle text-left" style="padding: 10px; border: 1px solid #ccc;">AIR PRESSURE (hPa)
                <div class="form-group">
                  <div class="input-group text-center">
                    <input type="text" class="form-control required" autocomplete="on" placeholder="101" name="r_v_a_8" value="<?php echo ($r_v_a_8); ?>" disabled>
                  </div>
                </div>
              </td>
            </tr>
            <tr class="form-inline">
              <td class="align-middle text-left" style="padding: 10px; border: 1px solid #ccc;">BASELINE HORIZONTAL DISTANCE (M)
                <div class="form-group">
                  <div class="input-group text-center">
                    <input type="text" class="form-control required" autocomplete="on" placeholder="19.994" name="r_v_a_9" value="<?php echo ($r_v_a_9); ?>" disabled>
                  </div>
                </div>
              </td>
              <td class="align-middle text-left" style="padding: 10px; border: 1px solid #ccc;">BASELINE ELEVATION ACCURACY (MM)
                <div class="form-group">
                  <div class="input-group text-center">
                    <input type="text" class="form-control required" autocomplete="on" placeholder="0.028" name="r_v_a_10" value="<?php echo ($r_v_a_10); ?>" disabled>
                  </div>
                </div>
              </td>
              <td class="align-middle text-left" style="padding: 10px; border: 1px solid #ccc;">MANUFACTURER HRMS ACCURACY (MM)
                <div class="form-group">
                  <div class="input-group text-center">
                    <input type="text" class="form-control required" autocomplete="on" placeholder="15" name="r_v_a_11" value="<?php echo ($r_v_a_11); ?>" disabled>
                  </div>
                </div>
              </td>
              <td colspan="2" class="align-middle text-left" style="padding: 10px; border: 1px solid #ccc;">MANUFACTURER VRMS ACCURACY (MM)
                <div class="form-group">
                  <div class="input-group text-center">
                    <input type="text" class="form-control required" autocomplete="on" placeholder="25" name="r_v_a_12" value="<?php echo ($r_v_a_12); ?>" disabled>
                  </div>
                </div>
              </td>
            </tr>
            <tr class="form-inline">
              <td class="align-middle text-left" style="padding: 10px; border: 1px solid #ccc;">INSTRUMENT HEIGHT (M)
                <div class="form-group">
                  <div class="input-group text-center">
                    <input type="text" class="form-control required" autocomplete="on" placeholder="2.2" name="r_v_a_13" value="<?php echo ($r_v_a_13); ?>" disabled>
                  </div>
                </div>
              </td>
            </tr>
          </tbody>
        </table>

        <table class="table table-bordered table-striped dataTable">
          <h6 class="btn btn-secondary" disabled><b>PRE-CALIBRATION CHECKS</b></h6>
          <thead>
            <tr>
              <th></th>
              <td></td>
              <td></td>
              <td></td>
              <td></td>
              <td></td>
              <td></td>
              <td></td>
              <td></td>
              <td colspan="6" font-weight:500;><b>BY CO0RDINATES</b></td>
            </tr>
            <tr>
              <th>Start Time</th>
              <td><?php echo !empty($calibration_info->start_time_1) ? date('H:i A', strtotime($calibration_info->start_time_1)) : 'N/A'; ?></td>
              <td></td>
              <td></td>
              <td></td>
              <td></td>
              <td></td>
              <th colspan="2" style="text-align:center; font-weight:500; float:right;">'MEAS'</th>
              <td></td>

              <td colspan="2" font-weight:500;><b>RESIDUALS FROM MEAN</b></td>
              <td></td>
              <td colspan="2" font-weight:500;><b>RESIDUALS FROM MEAN SQUARED</b></td>
              <td></td>
            </tr>
            <tr>
              <th class="text-left" style="font-weight:500;">Series</th>
              <th class="text-left" style="font-weight:500;">Seq. No.</th>
              <th class="text-left" style="font-weight:500;">Set</th>
              <th class="text-left" style="font-weight:500;">Rover Point </th>
              <th class="text-left" style="font-weight:500;">Eastings</th>
              <th class="text-left" style="font-weight:500;">Northings</th>
              <th class="text-left" style="font-weight:500;">Elevation</th>
              <th class="text-left" style="font-weight:500;">HD(m)</th>
              <th class="text-left" style="font-weight:500;">EA(mm)</th>
              <th class="text-left" style="font-weight:500;">Eastings</th>
              <th class="text-left" style="font-weight:500;">Northings</th>
              <th class="text-left" style="font-weight:500;">Elevation</th>
              <th class="text-left" style="font-weight:500;">Eastings</th>
              <th class="text-left" style="font-weight:500;">Northings</th>
              <th class="text-left" style="font-weight:500;">Elevation</th>

            </tr>
          </thead>
          <tbody>
            <?php
            $i_v_a_1 = $calibration_info->i_v_a_1;
            $i_v_a_2 = $calibration_info->i_v_a_2;
            $i_v_a_3 = $calibration_info->i_v_a_3;
            $ii_v_a_1 = $calibration_info->ii_v_a_1;
            $ii_v_a_2 = $calibration_info->ii_v_a_2;
            $ii_v_a_3 = $calibration_info->ii_v_a_3;
            $iii_v_a_1 = $calibration_info->iii_v_a_1;
            $iii_v_a_2 = $calibration_info->iii_v_a_2;
            $iii_v_a_3 = $calibration_info->iii_v_a_3;
            $iv_v_a_1 = $calibration_info->iv_v_a_1;
            $iv_v_a_2 = $calibration_info->iv_v_a_2;
            $iv_v_a_3 = $calibration_info->iv_v_a_3;
            $v_v_a_1 = $calibration_info->v_v_a_1;
            $v_v_a_2 = $calibration_info->v_v_a_2;
            $v_v_a_3 = $calibration_info->v_v_a_3;
            $vi_v_a_1 = $calibration_info->vi_v_a_1;
            $vi_v_a_2 = $calibration_info->vi_v_a_2;
            $vi_v_a_3 = $calibration_info->vi_v_a_3;
            $vii_v_a_1 = $calibration_info->vii_v_a_1;
            $vii_v_a_2 = $calibration_info->vii_v_a_2;
            $vii_v_a_3 = $calibration_info->vii_v_a_3;
            $viii_v_a_1 = $calibration_info->viii_v_a_1;
            $viii_v_a_2 = $calibration_info->viii_v_a_2;
            $viii_v_a_3 = $calibration_info->viii_v_a_3;
            $xi_v_a_1 = $calibration_info->xi_v_a_1;
            $xi_v_a_2 = $calibration_info->xi_v_a_2;
            $xi_v_a_3 = $calibration_info->xi_v_a_3;
            $x_v_a_1 = $calibration_info->x_v_a_1;
            $x_v_a_2 = $calibration_info->x_v_a_2;
            $x_v_a_3 = $calibration_info->x_v_a_3;

            $sumValue1a = round(sqrt(pow(($i_v_a_1 - $ii_v_a_1), 2) + pow(($i_v_a_2 - $ii_v_a_2), 2)), 4);
            $sumValue2a = round(sqrt(pow(($iii_v_a_1 - $iv_v_a_1), 2) + pow(($iii_v_a_2 - $iv_v_a_2), 2)), 4);
            $sumValue3a = round(sqrt(pow(($v_v_a_1 - $vi_v_a_1), 2) + pow(($v_v_a_2 - $vi_v_a_2), 2)), 4);
            $sumValue4a = round(sqrt(pow(($vii_v_a_1 - $viii_v_a_1), 2) + pow(($vii_v_a_2 - $viii_v_a_2), 2)), 4);
            $sumValue5a = round(sqrt(pow(($xi_v_a_1 - $x_v_a_1), 2) + pow(($xi_v_a_2 - $x_v_a_2), 2)), 4);

            $sumValue6a = round($ii_v_a_3 - $i_v_a_3, 4);
            $sumValue7a = round($iv_v_a_3 - $iii_v_a_3, 4);
            $sumValue8a = round($vi_v_a_3 - $v_v_a_3, 4);
            $sumValue9a = round($viii_v_a_3 - $vii_v_a_3, 4);
            $sumValue10a = round($x_v_a_3 - $xi_v_a_3, 4);

            $sumValue12 = round($i_v_a_2 + $iii_v_a_2 + $v_v_a_2 + $vii_v_a_2 + $xi_v_a_2) / 5;
            $sumValue13 = round($i_v_a_3 + $iii_v_a_3 + $v_v_a_3 + $vii_v_a_3 + $xi_v_a_3) / 5;

            $sumValue14 = round($ii_v_a_1 + $iv_v_a_1 + $vi_v_a_1 + $viii_v_a_1 + $x_v_a_1) / 5;
            $sumValue15 = round($ii_v_a_2 + $iv_v_a_2 + $vi_v_a_2 + $viii_v_a_2 + $x_v_a_2) / 5;
            $sumValue16 = round($ii_v_a_3 + $iv_v_a_3 + $vi_v_a_3 + $viii_v_a_3 + $x_v_a_3) / 5;

            $sumValue17 = round(($sumValue1a + $sumValue2a + $sumValue3a + $sumValue4a + $sumValue5a) / 5, 3);
            $sumValue18 = round(($sumValue6a + $sumValue7a + $sumValue8a + $sumValue9a + $sumValue10a) / 5, 3);

            $sumValue19 = round(($sumValue17) / 5, 3);
            $sumValue20 = round(($sumValue18) / 5, 3);

            session_start();  // Start the session
            $sumValue11a = $_SESSION['sumValue11a'];
            $sumValue12a = $_SESSION['sumValue12a'];
            $sumValue13a = $_SESSION['sumValue13a'];
            $sumValue14a = $_SESSION['sumValue14a'];
            $sumValue15a = $_SESSION['sumValue15a'];
            $sumValue16a = $_SESSION['sumValue16a'];

            // Residual Easting and Squared Values for set 1
            $residualEasting1a = ($sumValue11a - $i_v_a_1) * 1000;
            $residualEasting1b = ($sumValue12a - $i_v_a_2) * 1000;
            $residualEasting1c = ($sumValue13a - $i_v_a_3) * 1000;
            $squaredValue1a = $residualEasting1a ** 2;
            $squaredValue1b = $residualEasting1b ** 2;
            $squaredValue1c = $residualEasting1c ** 2;

            // Residual Easting and Squared Values for set 2
            $residualEasting2a = ($sumValue14a - $ii_v_a_1) * 1000;
            $residualEasting2b = ($sumValue15a - $ii_v_a_2) * 1000;
            $residualEasting2c = ($sumValue16a - $ii_v_a_3) * 1000;
            $squaredValue2a = $residualEasting2a ** 2;
            $squaredValue2b = $residualEasting2b ** 2;
            $squaredValue2c = $residualEasting2c ** 2;

            // Residual Easting and Squared Values for set 3
            $residualEasting3a = ($sumValue11a - $iii_v_a_1) * 1000;
            $residualEasting3b = ($sumValue12a - $iii_v_a_2) * 1000;
            $residualEasting3c = ($sumValue13a - $iii_v_a_3) * 1000;
            $squaredValue3a = $residualEasting3a ** 2;
            $squaredValue3b = $residualEasting3b ** 2;
            $squaredValue3c = $residualEasting3c ** 2;

            // Residual Easting and Squared Values for set 4
            $residualEasting4a = ($sumValue14a - $iv_v_a_1) * 1000;
            $residualEasting4b = ($sumValue15a - $iv_v_a_2) * 1000;
            $residualEasting4c = ($sumValue16a - $iv_v_a_3) * 1000;
            $squaredValue4a = $residualEasting4a ** 2;
            $squaredValue4b = $residualEasting4b ** 2;
            $squaredValue4c = $residualEasting4c ** 2;

            // Residual Easting and Squared Values for set 5
            $residualEasting5a = ($sumValue11a - $v_v_a_1) * 1000;
            $residualEasting5b = ($sumValue12a - $v_v_a_2) * 1000;
            $residualEasting5c = ($sumValue13a - $v_v_a_3) * 1000;
            $squaredValue5a = $residualEasting5a ** 2;
            $squaredValue5b = $residualEasting5b ** 2;
            $squaredValue5c = $residualEasting5c ** 2;

            // Residual Easting and Squared Values for set 6
            $residualEasting6a = ($sumValue14a - $vi_v_a_1) * 1000;
            $residualEasting6b = ($sumValue15a - $vi_v_a_2) * 1000;
            $residualEasting6c = ($sumValue16a - $vi_v_a_3) * 1000;
            $squaredValue6a = $residualEasting6a ** 2;
            $squaredValue6b = $residualEasting6b ** 2;
            $squaredValue6c = $residualEasting6c ** 2;

            // Residual Easting and Squared Values for set 7
            $residualEasting7a = ($sumValue11a - $vii_v_a_1) * 1000;
            $residualEasting7b = ($sumValue12a - $vii_v_a_2) * 1000;
            $residualEasting7c = ($sumValue13a - $vii_v_a_3) * 1000;
            $squaredValue7a = $residualEasting7a ** 2;
            $squaredValue7b = $residualEasting7b ** 2;
            $squaredValue7c = $residualEasting7c ** 2;

            // Residual Easting and Squared Values for set 8
            $residualEasting8a = ($sumValue14a - $viii_v_a_1) * 1000;
            $residualEasting8b = ($sumValue15a - $viii_v_a_2) * 1000;
            $residualEasting8c = ($sumValue16a - $viii_v_a_3) * 1000;
            $squaredValue8a = $residualEasting8a ** 2;
            $squaredValue8b = $residualEasting8b ** 2;
            $squaredValue8c = $residualEasting8c ** 2;

            // Residual Easting and Squared Values for set 9
            $residualEasting9a = ($sumValue11a - $xi_v_a_1) * 1000;
            $residualEasting9b = ($sumValue12a - $xi_v_a_2) * 1000;
            $residualEasting9c = ($sumValue13a - $xi_v_a_3) * 1000;
            $squaredValue9a = $residualEasting9a ** 2;
            $squaredValue9b = $residualEasting9b ** 2;
            $squaredValue9c = $residualEasting9c ** 2;

            // Residual Easting and Squared Values for set 10
            $residualEasting10a = ($sumValue14a - $x_v_a_1) * 1000;
            $residualEasting10b = ($sumValue15a - $x_v_a_2) * 1000;
            $residualEasting10c = ($sumValue16a - $x_v_a_3) * 1000;
            $squaredValue10a = $residualEasting10a ** 2;
            $squaredValue10b = $residualEasting10b ** 2;
            $squaredValue10c = $residualEasting10c ** 2;

            ?>
            <tr>
              <td class="" style="text-align:center;"></td>
              <td class="" style="text-align:center;">1</td>
              <td class="" style="text-align:center;">1</td>
              <td class="" style="text-align:center;">1</td>
              <td class="" style="text-align:center;"><?php echo round($i_v_a_1, 3); ?></td>
              <td class="" style="text-align:center;"><?php echo round($i_v_a_2, 3); ?></td>
              <td class="" style="text-align:center;"><?php echo round($i_v_a_3, 3); ?></td>
              <td class="" style="text-align:center;"></td>
              <td class="" style="text-align:center;"></td>
              <td class="" style="text-align:center;"><?php echo round($residualEasting1a, 3); ?></td>
              <td class="" style="text-align:center;"><?php echo round($residualEasting1b, 3); ?></td>
              <td class="" style="text-align:center;"><?php echo round($residualEasting1c, 3); ?></td>
              <td class="" style="text-align:center;"><?php echo round($squaredValue1a, 3); ?></td>
              <td class="" style="text-align:center;"><?php echo round($squaredValue1b, 3); ?></td>
              <td class="" style="text-align:center;"><?php echo round($squaredValue1c, 3); ?></td>

            </tr>
            <tr>
              <td class="" style="text-align:center;"></td>
              <td class="" style="text-align:center;">2</td>
              <td class="" style="text-align:center;">1</td>
              <td class="" style="text-align:center;">2</td>
              <td class="" style="text-align:center;"><?php echo ($ii_v_a_1); ?></td>
              <td class="" style="text-align:center;"><?php echo ($ii_v_a_2); ?></td>
              <td class="" style="text-align:center;"><?php echo ($ii_v_a_3); ?></td>
              <td class="" style="text-align:center;"><?php echo ($sumValue1a); ?></td>
              <td class="" style="text-align:center;"><?php echo ($sumValue6a); ?></td>
              <td class="" style="text-align:center;"><?php echo round($residualEasting2a, 3); ?></td>
              <td class="" style="text-align:center;"><?php echo round($residualEasting2b, 3); ?></td>
              <td class="" style="text-align:center;"><?php echo round($residualEasting2c, 3); ?></td>
              <td class="" style="text-align:center;"><?php echo round($squaredValue2a, 3); ?></td>
              <td class="" style="text-align:center;"><?php echo round($squaredValue2b, 3); ?></td>
              <td class="" style="text-align:center;"><?php echo round($squaredValue2c, 3); ?></td>

            </tr>
            <tr>
              <td class="" style="text-align:center;"></td>
              <td class="" style="text-align:center;">3</td>
              <td class="" style="text-align:center;">2</td>
              <td class="" style="text-align:center;">1</td>
              <td class="" style="text-align:center;"><?php echo ($iii_v_a_1); ?></td>
              <td class="" style="text-align:center;"><?php echo ($iii_v_a_2); ?></td>
              <td class="" style="text-align:center;"><?php echo ($iii_v_a_3); ?></td>
              <td class="" style="text-align:center;"></td>
              <td class="" style="text-align:center;"></td>
              <td class="" style="text-align:center;"><?php echo round($residualEasting3a, 3); ?></td>
              <td class="" style="text-align:center;"><?php echo round($residualEasting3b, 3); ?></td>
              <td class="" style="text-align:center;"><?php echo round($residualEasting3c, 3); ?></td>
              <td class="" style="text-align:center;"><?php echo round($squaredValue3a, 3); ?></td>
              <td class="" style="text-align:center;"><?php echo round($squaredValue3b, 3); ?></td>
              <td class="" style="text-align:center;"><?php echo round($squaredValue3c, 3); ?></td>
            </tr>
            <tr>
              <td class="" style="text-align:center;"></td>
              <td class="" style="text-align:center;">4</td>
              <td class="" style="text-align:center;">2</td>
              <td class="" style="text-align:center;">2</td>
              <td class="" style="text-align:center;"><?php echo ($iv_v_a_1); ?></td>
              <td class="" style="text-align:center;"><?php echo ($iv_v_a_2); ?></td>
              <td class="" style="text-align:center;"><?php echo ($iv_v_a_3); ?></td>
              <td class="" style="text-align:center;"><?php echo ($sumValue2a); ?></td>
              <td class="" style="text-align:center;"><?php echo ($sumValue7a); ?></td>
              <td class="" style="text-align:center;"><?php echo round($residualEasting4a, 3); ?></td>
              <td class="" style="text-align:center;"><?php echo round($residualEasting4b, 3); ?></td>
              <td class="" style="text-align:center;"><?php echo round($residualEasting4c, 3); ?></td>
              <td class="" style="text-align:center;"><?php echo round($squaredValue4a, 3); ?></td>
              <td class="" style="text-align:center;"><?php echo round($squaredValue4b, 3); ?></td>
              <td class="" style="text-align:center;"><?php echo round($squaredValue4c, 3); ?></td>
            </tr>
            <tr>
              <td class="" style="text-align:center;">1</td>
              <td class="" style="text-align:center;">5</td>
              <td class="" style="text-align:center;">3</td>
              <td class="" style="text-align:center;">1</td>
              <td class="" style="text-align:center;"><?php echo ($v_v_a_1); ?></td>
              <td class="" style="text-align:center;"><?php echo ($v_v_a_2); ?></td>
              <td class="" style="text-align:center;"><?php echo ($v_v_a_3); ?></td>
              <td class="" style="text-align:center;"></td>
              <td class="" style="text-align:center;"></td>
              <td class="" style="text-align:center;"><?php echo round($residualEasting5a, 3); ?></td>
              <td class="" style="text-align:center;"><?php echo round($residualEasting5b, 3); ?></td>
              <td class="" style="text-align:center;"><?php echo round($residualEasting5c, 3); ?></td>
              <td class="" style="text-align:center;"><?php echo round($squaredValue5a, 3); ?></td>
              <td class="" style="text-align:center;"><?php echo round($squaredValue5b, 3); ?></td>
              <td class="" style="text-align:center;"><?php echo round($squaredValue5c, 3); ?></td>
            </tr>
            <tr>
              <td class="" style="text-align:center;"></td>
              <td class="" style="text-align:center;">6</td>
              <td class="" style="text-align:center;">3</td>
              <td class="" style="text-align:center;">2</td>
              <td class="" style="text-align:center;"><?php echo ($vi_v_a_1); ?></td>
              <td class="" style="text-align:center;"><?php echo ($vi_v_a_2); ?></td>
              <td class="" style="text-align:center;"><?php echo ($vi_v_a_3); ?></td>
              <td class="" style="text-align:center;"><?php echo ($sumValue3a); ?></td>
              <td class="" style="text-align:center;"><?php echo ($sumValue8a); ?></td>
              <td class="" style="text-align:center;"><?php echo round($residualEasting6a, 3); ?></td>
              <td class="" style="text-align:center;"><?php echo round($residualEasting6b, 3); ?></td>
              <td class="" style="text-align:center;"><?php echo round($residualEasting6c, 3); ?></td>
              <td class="" style="text-align:center;"><?php echo round($squaredValue6a, 3); ?></td>
              <td class="" style="text-align:center;"><?php echo round($squaredValue6b, 3); ?></td>
              <td class="" style="text-align:center;"><?php echo round($squaredValue6c, 3); ?></td>
            </tr>
            <tr>
              <td class="" style="text-align:center;"></td>
              <td class="" style="text-align:center;">7</td>
              <td class="" style="text-align:center;">4</td>
              <td class="" style="text-align:center;">1</td>
              <td class="" style="text-align:center;"><?php echo ($vii_v_a_1); ?></td>
              <td class="" style="text-align:center;"><?php echo ($vii_v_a_2); ?></td>
              <td class="" style="text-align:center;"><?php echo ($vii_v_a_3); ?></td>
              <td class="" style="text-align:center;"></td>
              <td class="" style="text-align:center;"></td>
              <td class="" style="text-align:center;"><?php echo round($residualEasting7a, 3); ?></td>
              <td class="" style="text-align:center;"><?php echo round($residualEasting7b, 3); ?></td>
              <td class="" style="text-align:center;"><?php echo round($residualEasting7c, 3); ?></td>
              <td class="" style="text-align:center;"><?php echo round($squaredValue7a, 3); ?></td>
              <td class="" style="text-align:center;"><?php echo round($squaredValue7b, 3); ?></td>
              <td class="" style="text-align:center;"><?php echo round($squaredValue7c, 3); ?></td>
            </tr>
            <tr>
              <td class="" style="text-align:center;"></td>
              <td class="" style="text-align:center;">8</td>
              <td class="" style="text-align:center;">4</td>
              <td class="" style="text-align:center;">2</td>
              <td class="" style="text-align:center;"><?php echo ($viii_v_a_1); ?></td>
              <td class="" style="text-align:center;"><?php echo ($viii_v_a_2); ?></td>
              <td class="" style="text-align:center;"><?php echo ($viii_v_a_3); ?></td>
              <td class="" style="text-align:center;"><?php echo ($sumValue4a); ?></td>
              <td class="" style="text-align:center;"><?php echo ($sumValue9a); ?></td>
              <td class="" style="text-align:center;"><?php echo round($residualEasting8a, 3); ?></td>
              <td class="" style="text-align:center;"><?php echo round($residualEasting8b, 3); ?></td>
              <td class="" style="text-align:center;"><?php echo round($residualEasting8c, 3); ?></td>
              <td class="" style="text-align:center;"><?php echo round($squaredValue8a, 3); ?></td>
              <td class="" style="text-align:center;"><?php echo round($squaredValue8b, 3); ?></td>
              <td class="" style="text-align:center;"><?php echo round($squaredValue8c, 3); ?></td>
            </tr>
            <tr>
              <td class="" style="text-align:center;"></td>
              <td class="" style="text-align:center;">9</td>
              <td class="" style="text-align:center;">5</td>
              <td class="" style="text-align:center;">1</td>
              <td class="" style="text-align:center;"><?php echo ($xi_v_a_1); ?></td>
              <td class="" style="text-align:center;"><?php echo ($xi_v_a_2); ?></td>
              <td class="" style="text-align:center;"><?php echo ($xi_v_a_3); ?></td>
              <td class="" style="text-align:center;"></td>
              <td class="" style="text-align:center;"></td>
              <td class="" style="text-align:center;"><?php echo round($residualEasting9a, 3); ?></td>
              <td class="" style="text-align:center;"><?php echo round($residualEasting9b, 3); ?></td>
              <td class="" style="text-align:center;"><?php echo round($residualEasting9c, 3); ?></td>
              <td class="" style="text-align:center;"><?php echo round($squaredValue9a, 3); ?></td>
              <td class="" style="text-align:center;"><?php echo round($squaredValue9b, 3); ?></td>
              <td class="" style="text-align:center;"><?php echo round($squaredValue9c, 3); ?></td>
            </tr>
            <tr>
              <td class="" style="text-align:center;"></td>
              <td class="" style="text-align:center;">10</td>
              <td class="" style="text-align:center;">5</td>
              <td class="" style="text-align:center;">2</td>
              <td class="" style="text-align:center;"><?php echo ($x_v_a_1); ?></td>
              <td class="" style="text-align:center;"><?php echo ($x_v_a_2); ?></td>
              <td class="" style="text-align:center;"><?php echo ($x_v_a_3); ?></td>
              <td class="" style="text-align:center;"><?php echo ($sumValue5a); ?></td>
              <td class="" style="text-align:center;"><?php echo ($sumValue10a); ?></td>
              <td class="" style="text-align:center;"><?php echo round($residualEasting10a, 3); ?></td>
              <td class="" style="text-align:center;"><?php echo round($residualEasting10b, 3); ?></td>
              <td class="" style="text-align:center;"><?php echo round($residualEasting10c, 3); ?></td>
              <td class="" style="text-align:center;"><?php echo round($squaredValue10a, 3); ?></td>
              <td class="" style="text-align:center;"><?php echo round($squaredValue10b, 3); ?></td>
              <td class="" style="text-align:center;"><?php echo round($squaredValue10c, 3); ?></td>
            </tr>
            <tr>
              <td>Stop Time</td>
              <td><?php echo !empty($calibration_info->stop_time_1) ? date('H:i A', strtotime($calibration_info->stop_time_1)) : 'N/A'; ?></td>
              <td></td>
              <td></td>
              <td></td>
              <td></td>
              <th colspan="2" style="text-align:center; font-weight:500; float:right;"></th>
              <td></td>
              <td></td>
            </tr>

          </tbody>
        </table>

        <table class="table table-bordered table-striped dataTable">
          <thead>
            <tr>
              <th></th>
              <td></td>
              <td></td>
              <td></td>
              <td></td>
              <td></td>
              <td></td>
              <td></td>
              <td></td>
              <td colspan="6" font-weight:500;><b>BY CO0RDINATES</b></td>
            </tr>
            <tr>
              <th>Start Time</th>
              <td colspan="2"><?php echo !empty($calibration_info->start_time_2) ? date('H:i A', strtotime($calibration_info->start_time_2)) : 'N/A'; ?></td>
              <td></td>
              <th></th>
              <th></th>
              <td></td>
              <th colspan="2" style="text-align:center; font-weight:500; float:right;">'MEAS'</th>
              <td></td>

              <td colspan="2" font-weight:500;><b>RESIDUALS FROM MEAN</b></td>
              <td></td>
              <td colspan="2" font-weight:500;><b>RESIDUALS FROM MEAN SQUARED</b></td>
              <td></td>
            </tr>
            <tr>
              <th class="text-left" style="font-weight:500;">Series</th>
              <th class="text-left" style="font-weight:500;">Seq. No.</th>
              <th class="text-left" style="font-weight:500;">Set</th>
              <th class="text-left" style="font-weight:500;">Rover Point </th>
              <th class="text-left" style="font-weight:500;">Eastings</th>
              <th class="text-left" style="font-weight:500;">Northings</th>
              <th class="text-left" style="font-weight:500;">Elevation</th>
              <th class="text-left" style="font-weight:500;">HD(m)</th>
              <th class="text-left" style="font-weight:500;">EA(mm)</th>
              <th class="text-left" style="font-weight:500;">Eastings</th>
              <th class="text-left" style="font-weight:500;">Northings</th>
              <th class="text-left" style="font-weight:500;">Elevation</th>
              <th class="text-left" style="font-weight:500;">Eastings</th>
              <th class="text-left" style="font-weight:500;">Northings</th>
              <th class="text-left" style="font-weight:500;">Elevation</th>

            </tr>
          </thead>
          <tbody>
            <?php
            $i_v_b_1 = $calibration_info->i_v_b_1;
            $i_v_b_2 = $calibration_info->i_v_b_2;
            $i_v_b_3 = $calibration_info->i_v_b_3;
            $ii_v_b_1 = $calibration_info->ii_v_b_1;
            $ii_v_b_2 = $calibration_info->ii_v_b_2;
            $ii_v_b_3 = $calibration_info->ii_v_b_3;
            $iii_v_b_1 = $calibration_info->iii_v_b_1;
            $iii_v_b_2 = $calibration_info->iii_v_b_2;
            $iii_v_b_3 = $calibration_info->iii_v_b_3;
            $iv_v_b_1 = $calibration_info->iv_v_b_1;
            $iv_v_b_2 = $calibration_info->iv_v_b_2;
            $iv_v_b_3 = $calibration_info->iv_v_b_3;
            $v_v_b_1 = $calibration_info->v_v_b_1;
            $v_v_b_2 = $calibration_info->v_v_b_2;
            $v_v_b_3 = $calibration_info->v_v_b_3;
            $vi_v_b_1 = $calibration_info->vi_v_b_1;
            $vi_v_b_2 = $calibration_info->vi_v_b_2;
            $vi_v_b_3 = $calibration_info->vi_v_b_3;
            $vii_v_b_1 = $calibration_info->vii_v_b_1;
            $vii_v_b_2 = $calibration_info->vii_v_b_2;
            $vii_v_b_3 = $calibration_info->vii_v_b_3;
            $viii_v_b_1 = $calibration_info->viii_v_b_1;
            $viii_v_b_2 = $calibration_info->viii_v_b_2;
            $viii_v_b_3 = $calibration_info->viii_v_b_3;
            $xi_v_b_1 = $calibration_info->xi_v_b_1;
            $xi_v_b_2 = $calibration_info->xi_v_b_2;
            $xi_v_b_3 = $calibration_info->xi_v_b_3;
            $x_v_b_1 = $calibration_info->x_v_b_1;
            $x_v_b_2 = $calibration_info->x_v_b_2;
            $x_v_b_3 = $calibration_info->x_v_b_3;

            $sumValue1b = round(sqrt(pow(($i_v_b_1 - $ii_v_b_1), 2) + pow(($i_v_b_2 - $ii_v_b_2), 2)), 4);
            $sumValue2b = round(sqrt(pow(($iii_v_b_1 - $iv_v_b_1), 2) + pow(($iii_v_b_2 - $iv_v_b_2), 2)), 4);
            $sumValue3b = round(sqrt(pow(($v_v_b_1 - $vi_v_b_1), 2) + pow(($v_v_b_2 - $vi_v_b_2), 2)), 4);
            $sumValue4b = round(sqrt(pow(($vii_v_b_1 - $viii_v_b_1), 2) + pow(($vii_v_b_2 - $viii_v_b_2), 2)), 4);
            $sumValue5b = round(sqrt(pow(($xi_v_b_1 - $x_v_b_1), 2) + pow(($xi_v_b_2 - $x_v_b_2), 2)), 4);

            $sumValue6b = round($ii_v_b_3 - $i_v_b_3, 4);
            $sumValue7b = round($iv_v_b_3 - $iii_v_b_3, 4);
            $sumValue8b = round($vi_v_b_3 - $v_v_b_3, 4);
            $sumValue9b = round($viii_v_b_3 - $vii_v_b_3, 4);
            $sumValue10b = round($x_v_b_3 - $xi_v_b_3, 4);

            $sumValue12 = round($i_v_b_2 + $iii_v_b_2 + $v_v_b_2 + $vii_v_b_2 + $xi_v_b_2) / 5;
            $sumValue13 = round($i_v_b_3 + $iii_v_b_3 + $v_v_b_3 + $vii_v_b_3 + $xi_v_b_3) / 5;

            $sumValue14 = round($ii_v_b_1 + $iv_v_b_1 + $vi_v_b_1 + $viii_v_b_1 + $x_v_b_1) / 5;
            $sumValue15 = round($ii_v_a_2 + $iv_v_a_2 + $vi_v_a_2 + $viii_v_a_2 + $x_v_a_2) / 5;
            $sumValue16 = round($ii_v_b_3 + $iv_v_b_3 + $vi_v_b_3 + $viii_v_b_3 + $x_v_b_3) / 5;

            $sumValue17 = round(($sumValue1b + $sumValue2b + $sumValue3b + $sumValue4b + $sumValue5b) / 5, 3);
            $sumValue18 = round(($sumValue6b + $sumValue7b + $sumValue8b + $sumValue9b + $sumValue10b) / 5, 3);

            $sumValue19 = round(($sumValue17) / 5, 3);
            $sumValue20 = round(($sumValue18) / 5, 3);


            session_start();  // Start the session
            $sumValue11a = $_SESSION['sumValue11a'];
            $sumValue12a = $_SESSION['sumValue12a'];
            $sumValue13a = $_SESSION['sumValue13a'];
            $sumValue14a = $_SESSION['sumValue14a'];
            $sumValue15a = $_SESSION['sumValue15a'];
            $sumValue16a = $_SESSION['sumValue16a'];


            // Residual Easting and Squared Values for set 11
            $residualEasting11a = ($sumValue11a - $i_v_b_1) * 1000;
            $residualEasting11b = ($sumValue12a - $i_v_b_2) * 1000;
            $residualEasting11c = ($sumValue13a - $i_v_b_3) * 1000;
            $squaredValue11a = $residualEasting11a ** 2;
            $squaredValue11b = $residualEasting11b ** 2;
            $squaredValue11c = $residualEasting11c ** 2;

            // Residual Easting and Squared Values for set 12
            $residualEasting12a = ($sumValue14a - $ii_v_b_1) * 1000;
            $residualEasting12b = ($sumValue15a - $ii_v_b_2) * 1000;
            $residualEasting12c = ($sumValue16a - $ii_v_b_3) * 1000;
            $squaredValue12a = $residualEasting12a ** 2;
            $squaredValue12b = $residualEasting12b ** 2;
            $squaredValue12c = $residualEasting12c ** 2;

            // Residual Easting and Squared Values for set 13
            $residualEasting13a = ($sumValue11a - $iii_v_b_1) * 1000;
            $residualEasting13b = ($sumValue12a - $iii_v_b_2) * 1000;
            $residualEasting13c = ($sumValue13a - $iii_v_b_3) * 1000;
            $squaredValue13a = $residualEasting13a ** 2;
            $squaredValue13b = $residualEasting13b ** 2;
            $squaredValue13c = $residualEasting13c ** 2;

            // Residual Easting and Squared Values for set 14
            $residualEasting14a = ($sumValue14a - $iv_v_b_1) * 1000;
            $residualEasting14b = ($sumValue15a - $iv_v_b_2) * 1000;
            $residualEasting14c = ($sumValue16a - $iv_v_b_3) * 1000;
            $squaredValue14a = $residualEasting14a ** 2;
            $squaredValue14b = $residualEasting14b ** 2;
            $squaredValue14c = $residualEasting14c ** 2;

            // Residual Easting and Squared Values for set 15
            $residualEasting15a = ($sumValue11a - $v_v_b_1) * 1000;
            $residualEasting15b = ($sumValue12a - $v_v_b_2) * 1000;
            $residualEasting15c = ($sumValue13a - $v_v_b_3) * 1000;
            $squaredValue15a = $residualEasting15a ** 2;
            $squaredValue15b = $residualEasting15b ** 2;
            $squaredValue15c = $residualEasting15c ** 2;

            // Residual Easting and Squared Values for set 16
            $residualEasting16a = ($sumValue14a - $vi_v_b_1) * 1000;
            $residualEasting16b = ($sumValue15a - $vi_v_b_2) * 1000;
            $residualEasting16c = ($sumValue16a - $vi_v_b_3) * 1000;
            $squaredValue16a = $residualEasting16a ** 2;
            $squaredValue16b = $residualEasting16b ** 2;
            $squaredValue16c = $residualEasting16c ** 2;

            // Residual Easting and Squared Values for set 17
            $residualEasting17a = ($sumValue11a - $vii_v_b_1) * 1000;
            $residualEasting17b = ($sumValue12a - $vii_v_b_2) * 1000;
            $residualEasting17c = ($sumValue13a - $vii_v_b_3) * 1000;
            $squaredValue17a = $residualEasting17a ** 2;
            $squaredValue17b = $residualEasting17b ** 2;
            $squaredValue17c = $residualEasting17c ** 2;

            // Residual Easting and Squared Values for set 18
            $residualEasting18a = ($sumValue14a - $viii_v_b_1) * 1000;
            $residualEasting18b = ($sumValue15a - $viii_v_b_2) * 1000;
            $residualEasting18c = ($sumValue16a - $viii_v_b_3) * 1000;
            $squaredValue18a = $residualEasting18a ** 2;
            $squaredValue18b = $residualEasting18b ** 2;
            $squaredValue18c = $residualEasting18c ** 2;

            // Residual Easting and Squared Values for set 19
            $residualEasting19a = ($sumValue11a - $xi_v_b_1) * 1000;
            $residualEasting19b = ($sumValue12a - $xi_v_b_2) * 1000;
            $residualEasting19c = ($sumValue13a - $xi_v_b_3) * 1000;
            $squaredValue19a = $residualEasting19a ** 2;
            $squaredValue19b = $residualEasting19b ** 2;
            $squaredValue19c = $residualEasting19c ** 2;

            // Residual Easting and Squared Values for set 20
            $residualEasting20a = ($sumValue14a - $x_v_b_1) * 1000;
            $residualEasting20b = ($sumValue15a - $x_v_b_2) * 1000;
            $residualEasting20c = ($sumValue16a - $x_v_b_3) * 1000;
            $squaredValue20a = $residualEasting20a ** 2;
            $squaredValue20b = $residualEasting20b ** 2;
            $squaredValue20c = $residualEasting20c ** 2;


            ?>
            <tr>
              <td class="" style="text-align:center;"></td>
              <td class="" style="text-align:center;">11</td>
              <td class="" style="text-align:center;">1</td>
              <td class="" style="text-align:center;">1</td>
              <td class="" style="text-align:center;"><?php echo round($i_v_b_1, 3); ?></td>
              <td class="" style="text-align:center;"><?php echo ($i_v_b_2); ?></td>
              <td class="" style="text-align:center;"><?php echo ($i_v_b_3); ?></td>
              <td class="" style="text-align:center;"></td>
              <td class="" style="text-align:center;"></td>
              <td class="" style="text-align:center;"><?php echo round($residualEasting11a, 3); ?></td>
              <td class="" style="text-align:center;"><?php echo round($residualEasting11b, 3); ?></td>
              <td class="" style="text-align:center;"><?php echo round($residualEasting11c, 3); ?></td>
              <td class="" style="text-align:center;"><?php echo round($squaredValue11a, 3); ?></td>
              <td class="" style="text-align:center;"><?php echo round($squaredValue11b, 3); ?></td>
              <td class="" style="text-align:center;"><?php echo round($squaredValue11c, 3); ?></td>
            </tr>
            <tr>
              <td class="" style="text-align:center;"></td>
              <td class="" style="text-align:center;">12</td>
              <td class="" style="text-align:center;">1</td>
              <td class="" style="text-align:center;">2</td>
              <td class="" style="text-align:center;"><?php echo ($ii_v_b_1); ?></td>
              <td class="" style="text-align:center;"><?php echo ($ii_v_b_2); ?></td>
              <td class="" style="text-align:center;"><?php echo ($ii_v_b_3); ?></td>
              <td class="" style="text-align:center;"><?php echo ($sumValue1b); ?></td>
              <td class="" style="text-align:center;"><?php echo ($sumValue6b); ?></td>
              <td class="" style="text-align:center;"><?php echo round($residualEasting12a, 3); ?></td>
              <td class="" style="text-align:center;"><?php echo round($residualEasting12b, 3); ?></td>
              <td class="" style="text-align:center;"><?php echo round($residualEasting12c, 3); ?></td>
              <td class="" style="text-align:center;"><?php echo round($squaredValue12a, 3); ?></td>
              <td class="" style="text-align:center;"><?php echo round($squaredValue12b, 3); ?></td>
              <td class="" style="text-align:center;"><?php echo round($squaredValue12c, 3); ?></td>
            </tr>
            <tr>
              <td class="" style="text-align:center;"></td>
              <td class="" style="text-align:center;">13</td>
              <td class="" style="text-align:center;">2</td>
              <td class="" style="text-align:center;">1</td>
              <td class="" style="text-align:center;"><?php echo ($iii_v_b_1); ?></td>
              <td class="" style="text-align:center;"><?php echo ($iii_v_b_2); ?></td>
              <td class="" style="text-align:center;"><?php echo ($iii_v_b_3); ?></td>
              <td class="" style="text-align:center;"></td>
              <td class="" style="text-align:center;"></td>
              <td class="" style="text-align:center;"><?php echo round($residualEasting13a, 3); ?></td>
              <td class="" style="text-align:center;"><?php echo round($residualEasting13b, 3); ?></td>
              <td class="" style="text-align:center;"><?php echo round($residualEasting13c, 3); ?></td>
              <td class="" style="text-align:center;"><?php echo round($squaredValue13a, 3); ?></td>
              <td class="" style="text-align:center;"><?php echo round($squaredValue13b, 3); ?></td>
              <td class="" style="text-align:center;"><?php echo round($squaredValue13c, 3); ?></td>
            </tr>
            <tr>
              <td class="" style="text-align:center;"></td>
              <td class="" style="text-align:center;">14</td>
              <td class="" style="text-align:center;">2</td>
              <td class="" style="text-align:center;">2</td>
              <td class="" style="text-align:center;"><?php echo ($iv_v_b_1); ?></td>
              <td class="" style="text-align:center;"><?php echo ($iv_v_b_2); ?></td>
              <td class="" style="text-align:center;"><?php echo ($iv_v_b_3); ?></td>
              <td class="" style="text-align:center;"><?php echo ($sumValue2b); ?></td>
              <td class="" style="text-align:center;"><?php echo ($sumValue7b); ?></td>
              <td class="" style="text-align:center;"><?php echo round($residualEasting14a, 3); ?></td>
              <td class="" style="text-align:center;"><?php echo round($residualEasting14b, 3); ?></td>
              <td class="" style="text-align:center;"><?php echo round($residualEasting14c, 3); ?></td>
              <td class="" style="text-align:center;"><?php echo round($squaredValue14a, 3); ?></td>
              <td class="" style="text-align:center;"><?php echo round($squaredValue14b, 3); ?></td>
              <td class="" style="text-align:center;"><?php echo round($squaredValue14c, 3); ?></td>
            </tr>
            <tr>
              <td class="" style="text-align:center;">2</td>
              <td class="" style="text-align:center;">15</td>
              <td class="" style="text-align:center;">3</td>
              <td class="" style="text-align:center;">1</td>
              <td class="" style="text-align:center;"><?php echo ($v_v_b_1); ?></td>
              <td class="" style="text-align:center;"><?php echo ($v_v_b_2); ?></td>
              <td class="" style="text-align:center;"><?php echo ($v_v_b_3); ?></td>
              <td class="" style="text-align:center;"></td>
              <td class="" style="text-align:center;"></td>
              <td class="" style="text-align:center;"><?php echo round($residualEasting15a, 3); ?></td>
              <td class="" style="text-align:center;"><?php echo round($residualEasting15b, 3); ?></td>
              <td class="" style="text-align:center;"><?php echo round($residualEasting15c, 3); ?></td>
              <td class="" style="text-align:center;"><?php echo round($squaredValue15a, 3); ?></td>
              <td class="" style="text-align:center;"><?php echo round($squaredValue15b, 3); ?></td>
              <td class="" style="text-align:center;"><?php echo round($squaredValue15c, 3); ?></td>
            </tr>
            <tr>
              <td class="" style="text-align:center;"></td>
              <td class="" style="text-align:center;">16</td>
              <td class="" style="text-align:center;">3</td>
              <td class="" style="text-align:center;">2</td>
              <td class="" style="text-align:center;"><?php echo ($vi_v_b_1); ?></td>
              <td class="" style="text-align:center;"><?php echo ($vi_v_b_2); ?></td>
              <td class="" style="text-align:center;"><?php echo ($vi_v_b_3); ?></td>
              <td class="" style="text-align:center;"><?php echo ($sumValue3b); ?></td>
              <td class="" style="text-align:center;"><?php echo ($sumValue8b); ?></td>
              <td class="" style="text-align:center;"><?php echo round($residualEasting16a, 3); ?></td>
              <td class="" style="text-align:center;"><?php echo round($residualEasting16b, 3); ?></td>
              <td class="" style="text-align:center;"><?php echo round($residualEasting16c, 3); ?></td>
              <td class="" style="text-align:center;"><?php echo round($squaredValue16a, 3); ?></td>
              <td class="" style="text-align:center;"><?php echo round($squaredValue16b, 3); ?></td>
              <td class="" style="text-align:center;"><?php echo round($squaredValue16c, 3); ?></td>
            </tr>
            <tr>
              <td class="" style="text-align:center;"></td>
              <td class="" style="text-align:center;">17</td>
              <td class="" style="text-align:center;">4</td>
              <td class="" style="text-align:center;">1</td>
              <td class="" style="text-align:center;"><?php echo ($vii_v_b_1); ?></td>
              <td class="" style="text-align:center;"><?php echo ($vii_v_b_2); ?></td>
              <td class="" style="text-align:center;"><?php echo ($vii_v_b_3); ?></td>
              <td class="" style="text-align:center;"></td>
              <td class="" style="text-align:center;"></td>
              <td class="" style="text-align:center;"><?php echo round($residualEasting17a, 3); ?></td>
              <td class="" style="text-align:center;"><?php echo round($residualEasting17b, 3); ?></td>
              <td class="" style="text-align:center;"><?php echo round($residualEasting17c, 3); ?></td>
              <td class="" style="text-align:center;"><?php echo round($squaredValue17a, 3); ?></td>
              <td class="" style="text-align:center;"><?php echo round($squaredValue17b, 3); ?></td>
              <td class="" style="text-align:center;"><?php echo round($squaredValue17c, 3); ?></td>
            </tr>
            <tr>
              <td class="" style="text-align:center;"></td>
              <td class="" style="text-align:center;">18</td>
              <td class="" style="text-align:center;">4</td>
              <td class="" style="text-align:center;">2</td>
              <td class="" style="text-align:center;"><?php echo ($viii_v_b_1); ?></td>
              <td class="" style="text-align:center;"><?php echo ($viii_v_b_2); ?></td>
              <td class="" style="text-align:center;"><?php echo ($viii_v_b_3); ?></td>
              <td class="" style="text-align:center;"><?php echo ($sumValue4b); ?></td>
              <td class="" style="text-align:center;"><?php echo ($sumValue9b); ?></td>
              <td class="" style="text-align:center;"><?php echo round($residualEasting18a, 3); ?></td>
              <td class="" style="text-align:center;"><?php echo round($residualEasting18b, 3); ?></td>
              <td class="" style="text-align:center;"><?php echo round($residualEasting18c, 3); ?></td>
              <td class="" style="text-align:center;"><?php echo round($squaredValue18a, 3); ?></td>
              <td class="" style="text-align:center;"><?php echo round($squaredValue18b, 3); ?></td>
              <td class="" style="text-align:center;"><?php echo round($squaredValue18c, 3); ?></td>
            </tr>
            <tr>
              <td class="" style="text-align:center;"></td>
              <td class="" style="text-align:center;">19</td>
              <td class="" style="text-align:center;">5</td>
              <td class="" style="text-align:center;">1</td>
              <td class="" style="text-align:center;"><?php echo ($xi_v_b_1); ?></td>
              <td class="" style="text-align:center;"><?php echo ($xi_v_b_2); ?></td>
              <td class="" style="text-align:center;"><?php echo ($xi_v_b_3); ?></td>
              <td class="" style="text-align:center;"></td>
              <td class="" style="text-align:center;"></td>
              <td class="" style="text-align:center;"><?php echo round($residualEasting19a, 3); ?></td>
              <td class="" style="text-align:center;"><?php echo round($residualEasting19b, 3); ?></td>
              <td class="" style="text-align:center;"><?php echo round($residualEasting19c, 3); ?></td>
              <td class="" style="text-align:center;"><?php echo round($squaredValue19a, 3); ?></td>
              <td class="" style="text-align:center;"><?php echo round($squaredValue19b, 3); ?></td>
              <td class="" style="text-align:center;"><?php echo round($squaredValue19c, 3); ?></td>
            </tr>
            <tr>
              <td class="" style="text-align:center;"></td>
              <td class="" style="text-align:center;">20</td>
              <td class="" style="text-align:center;">5</td>
              <td class="" style="text-align:center;">2</td>
              <td class="" style="text-align:center;"><?php echo ($x_v_b_1); ?></td>
              <td class="" style="text-align:center;"><?php echo ($x_v_b_2); ?></td>
              <td class="" style="text-align:center;"><?php echo ($x_v_b_3); ?></td>
              <td class="" style="text-align:center;"><?php echo ($sumValue5b); ?></td>
              <td class="" style="text-align:center;"><?php echo ($sumValue10b); ?></td>
              <td class="" style="text-align:center;"><?php echo round($residualEasting20a, 3); ?></td>
              <td class="" style="text-align:center;"><?php echo round($residualEasting20b, 3); ?></td>
              <td class="" style="text-align:center;"><?php echo round($residualEasting20c, 3); ?></td>
              <td class="" style="text-align:center;"><?php echo round($squaredValue20a, 3); ?></td>
              <td class="" style="text-align:center;"><?php echo round($squaredValue20b, 3); ?></td>
              <td class="" style="text-align:center;"><?php echo round($squaredValue20c, 3); ?></td>
            </tr>
            <tr>
              <td>Stop Time</td>
              <td><?php echo !empty($calibration_info->stop_time_2) ? date('H:i A', strtotime($calibration_info->stop_time_2)) : 'N/A'; ?></td>
              <td></td>
              <td></td>
              <td></td>
              <td></td>
              <th colspan="2" style="text-align:center; font-weight:500; float:right;"></th>
            </tr>
          </tbody>
        </table>

        <table class="table table-bordered table-striped dataTable">
          <thead>
            <tr>
              <th></th>
              <td></td>
              <td></td>
              <td></td>
              <td></td>
              <td></td>
              <td></td>
              <td></td>
              <td></td>
              <td colspan="6" font-weight:500;><b>BY CO0RDINATES</b></td>
            </tr>
            <tr>
              <th>Start Time</th>
              <td><?php echo !empty($calibration_info->start_time_3) ? date('H:i A', strtotime($calibration_info->start_time_3)) : 'N/A'; ?></td>
              <td></td>
              <td></td>
              <td></td>
              <td></td>
              <td></td>
              <th colspan="2" style="text-align:center; font-weight:500; float:right;">'MEAS'</th>
              <td></td>

              <td colspan="2" font-weight:500;><b>RESIDUALS FROM MEAN</b></td>
              <td></td>
              <td colspan="2" font-weight:500;><b>RESIDUALS FROM MEAN SQUARED</b></td>
              <td></td>
            </tr>
            <tr>
              <th class="text-left" style="font-weight:500;">Series</th>
              <th class="text-left" style="font-weight:500;">Seq. No.</th>
              <th class="text-left" style="font-weight:500;">Set</th>
              <th class="text-left" style="font-weight:500;">Rover Point </th>
              <th class="text-left" style="font-weight:500;">Eastings</th>
              <th class="text-left" style="font-weight:500;">Northings</th>
              <th class="text-left" style="font-weight:500;">Elevation</th>
              <th class="text-left" style="font-weight:500;">HD(m)</th>
              <th class="text-left" style="font-weight:500;">EA(mm)</th>
              <th class="text-left" style="font-weight:500;">Eastings</th>
              <th class="text-left" style="font-weight:500;">Northings</th>
              <th class="text-left" style="font-weight:500;">Elevation</th>
              <th class="text-left" style="font-weight:500;">Eastings</th>
              <th class="text-left" style="font-weight:500;">Northings</th>
              <th class="text-left" style="font-weight:500;">Elevation</th>

            </tr>
          </thead>
          <tbody>
            <?php
            $i_v_c_1 = $calibration_info->i_v_c_1;
            $i_v_c_2 = $calibration_info->i_v_c_2;
            $i_v_c_3 = $calibration_info->i_v_c_3;
            $ii_v_c_1 = $calibration_info->ii_v_c_1;
            $ii_v_c_2 = $calibration_info->ii_v_c_2;
            $ii_v_c_3 = $calibration_info->ii_v_c_3;
            $iii_v_c_1 = $calibration_info->iii_v_c_1;
            $iii_v_c_2 = $calibration_info->iii_v_c_2;
            $iii_v_c_3 = $calibration_info->iii_v_c_3;
            $iv_v_c_1 = $calibration_info->iv_v_c_1;
            $iv_v_c_2 = $calibration_info->iv_v_c_2;
            $iv_v_c_3 = $calibration_info->iv_v_c_3;
            $v_v_c_1 = $calibration_info->v_v_c_1;
            $v_v_c_2 = $calibration_info->v_v_c_2;
            $v_v_c_3 = $calibration_info->v_v_c_3;
            $vi_v_c_1 = $calibration_info->vi_v_c_1;
            $vi_v_c_2 = $calibration_info->vi_v_c_2;
            $vi_v_c_3 = $calibration_info->vi_v_c_3;
            $vii_v_c_1 = $calibration_info->vii_v_c_1;
            $vii_v_c_2 = $calibration_info->vii_v_c_2;
            $vii_v_c_3 = $calibration_info->vii_v_c_3;
            $viii_v_c_1 = $calibration_info->viii_v_c_1;
            $viii_v_c_2 = $calibration_info->viii_v_c_2;
            $viii_v_c_3 = $calibration_info->viii_v_c_3;
            $xi_v_c_1 = $calibration_info->xi_v_c_1;
            $xi_v_c_2 = $calibration_info->xi_v_c_2;
            $xi_v_c_3 = $calibration_info->xi_v_c_3;
            $x_v_c_1 = $calibration_info->x_v_c_1;
            $x_v_c_2 = $calibration_info->x_v_c_2;
            $x_v_c_3 = $calibration_info->x_v_c_3;

            $r_v_a_10 = $calibration_info->r_v_a_10;
            $r_v_a_11 = $calibration_info->r_v_a_11;
            $r_v_a_12 = $calibration_info->r_v_a_12;

            $sumValue1c = round(sqrt(pow(($i_v_c_1 - $ii_v_c_1), 2) + pow(($i_v_c_2 - $ii_v_c_2), 2)), 4);
            $sumValue2c = round(sqrt(pow(($iii_v_c_1 - $iv_v_c_1), 2) + pow(($iii_v_c_2 - $iv_v_c_2), 2)), 4);
            $sumValue3c = round(sqrt(pow(($v_v_c_1 - $vi_v_c_1), 2) + pow(($v_v_c_2 - $vi_v_c_2), 2)), 4);
            $sumValue4c = round(sqrt(pow(($vii_v_c_1 - $viii_v_c_1), 2) + pow(($vii_v_c_2 - $viii_v_c_2), 2)), 4);
            $sumValue5c = round(sqrt(pow(($xi_v_c_1 - $x_v_c_1), 2) + pow(($xi_v_c_2 - $x_v_c_2), 2)), 4);

            $sumValue6c = round($ii_v_c_3 - $i_v_c_3, 4);
            $sumValue7c = round($iv_v_c_3 - $iii_v_c_3, 4);
            $sumValue8c = round($vi_v_c_3 - $v_v_c_3, 4);
            $sumValue9c = round($viii_v_c_3 - $vii_v_c_3, 4);
            $sumValue10c = round($x_v_c_3 - $xi_v_c_3, 4);

            $sumValue11a = round(($i_v_a_1 + $iii_v_a_1 + $v_v_a_1 + $vii_v_a_1 + $xi_v_a_1 + $i_v_b_1 + $iii_v_b_1 + $v_v_b_1 + $vii_v_b_1 + $xi_v_b_1 + $i_v_c_1 + $iii_v_c_1 + $v_v_c_1 + $vii_v_c_1 + $xi_v_c_1) / 15, 3);
            $sumValue12a = round(($i_v_a_2 + $iii_v_a_2 + $v_v_a_2 + $vii_v_a_2 + $xi_v_a_2 + $i_v_b_2 + $iii_v_b_2 + $v_v_b_2 + $vii_v_b_2 + $xi_v_b_2 + $i_v_c_2 + $iii_v_c_2 + $v_v_c_2 + $vii_v_c_2 + $xi_v_c_2) / 15, 3);
            $sumValue13a = round(($i_v_a_3 + $iii_v_a_3 + $v_v_a_3 + $vii_v_a_3 + $xi_v_a_3 + $i_v_b_3 + $iii_v_b_3 + $v_v_b_3 + $vii_v_b_3 + $xi_v_b_3 + $i_v_c_3 + $iii_v_c_3 + $v_v_c_3 + $vii_v_c_3 + $xi_v_c_3) / 15, 3);

            $sumValue14a = round(($ii_v_a_1 + $iv_v_a_1 + $vi_v_a_1 + $viii_v_a_1 + $x_v_a_1 + $ii_v_b_1 + $iv_v_b_1 + $vi_v_b_1 + $viii_v_b_1 + $x_v_b_1 + $ii_v_c_1 + $iv_v_c_1 + $vi_v_c_1 + $viii_v_c_1 + $x_v_c_1) / 15, 3);
            $sumValue15a = round(($ii_v_a_2 + $iv_v_a_2 + $vi_v_a_2 + $viii_v_a_2 + $x_v_a_2 + $ii_v_a_2 + $iv_v_a_2 + $vi_v_a_2 + $viii_v_a_2 + $x_v_a_2 + $ii_v_c_2 + $iv_v_c_2 + $vi_v_c_2 + $viii_v_c_2 + $x_v_c_2) / 15, 3);
            $sumValue16a = round(($ii_v_a_3 + $iv_v_a_3 + $vi_v_a_3 + $viii_v_a_3 + $x_v_a_3 + $ii_v_b_3 + $iv_v_b_3 + $vi_v_b_3 + $viii_v_b_3 + $x_v_b_3 + $ii_v_c_3 + $iv_v_c_3 + $vi_v_c_3 + $viii_v_c_3 + $x_v_c_3) / 15, 3);

            $sumValue17 = round($sumValue1a + $sumValue2a + $sumValue3a + $sumValue4a + $sumValue5a + $sumValue1b + $sumValue2b + $sumValue3b + $sumValue4b + $sumValue5b + $sumValue1c + $sumValue2c + $sumValue3c + $sumValue4c + $sumValue5c, 2);
            $sumValue18 = round($sumValue6a + $sumValue7a + $sumValue8a + $sumValue9a + $sumValue10a + $sumValue6b + $sumValue7b + $sumValue8b + $sumValue9b + $sumValue10b + $sumValue6c + $sumValue7c + $sumValue8c + $sumValue9c + $sumValue10c, 2);

            // $sumValue19 = round(($sumValue17) / 15, 3);
            // $sumValue20 = round(($sumValue18) / 15, 3);
            $sumValue19 = round(($sumValue17) / 15, 3);
            // $sumValue19 = 19.995;
            $sumValue20 = round(($sumValue18) / 15, 3);
            // $sumValue20 = 0.028;



            session_start();  // Start the session
            $_SESSION['sumValue11a'] = $sumValue11a;
            $_SESSION['sumValue12a'] = $sumValue12a;
            $_SESSION['sumValue13a'] = $sumValue13a;
            $_SESSION['sumValue14a'] = $sumValue14a;
            $_SESSION['sumValue15a'] = $sumValue15a;
            $_SESSION['sumValue16a'] = $sumValue16a;


            // Residual Easting and Squared Values for set 21
            $residualEasting21a = ($sumValue11a - $i_v_c_1) * 1000;
            $residualEasting21b = ($sumValue12a - $i_v_c_2) * 1000;
            $residualEasting21c = ($sumValue13a - $i_v_c_3) * 1000;
            $squaredValue21a = $residualEasting21a ** 2;
            $squaredValue21b = $residualEasting21b ** 2;
            $squaredValue21c = $residualEasting21c ** 2;

            // Residual Easting and Squared Values for set 22
            $residualEasting22a = ($sumValue14a - $ii_v_c_1) * 1000;
            $residualEasting22b = ($sumValue15a - $ii_v_c_2) * 1000;
            $residualEasting22c = ($sumValue16a - $ii_v_c_3) * 1000;
            $squaredValue22a = $residualEasting22a ** 2;
            $squaredValue22b = $residualEasting22b ** 2;
            $squaredValue22c = $residualEasting22c ** 2;

            // Residual Easting and Squared Values for set 23
            $residualEasting23a = ($sumValue11a - $iii_v_c_1) * 1000;
            $residualEasting23b = ($sumValue12a - $iii_v_c_2) * 1000;
            $residualEasting23c = ($sumValue13a - $iii_v_c_3) * 1000;
            $squaredValue23a = $residualEasting23a ** 2;
            $squaredValue23b = $residualEasting23b ** 2;
            $squaredValue23c = $residualEasting23c ** 2;

            // Residual Easting and Squared Values for set 24
            $residualEasting24a = ($sumValue14a - $iv_v_c_1) * 1000;
            $residualEasting24b = ($sumValue15a - $iv_v_c_2) * 1000;
            $residualEasting24c = ($sumValue16a - $iv_v_c_3) * 1000;
            $squaredValue24a = $residualEasting24a ** 2;
            $squaredValue24b = $residualEasting24b ** 2;
            $squaredValue24c = $residualEasting24c ** 2;

            // Residual Easting and Squared Values for set 25
            $residualEasting25a = ($sumValue11a - $v_v_c_1) * 1000;
            $residualEasting25b = ($sumValue12a - $v_v_c_2) * 1000;
            $residualEasting25c = ($sumValue13a - $v_v_c_3) * 1000;
            $squaredValue25a = $residualEasting25a ** 2;
            $squaredValue25b = $residualEasting25b ** 2;
            $squaredValue25c = $residualEasting25c ** 2;

            // Residual Easting and Squared Values for set 26
            $residualEasting26a = ($sumValue14a - $vi_v_c_1) * 1000;
            $residualEasting26b = ($sumValue15a - $vi_v_c_2) * 1000;
            $residualEasting26c = ($sumValue16a - $vi_v_c_3) * 1000;
            $squaredValue26a = $residualEasting26a ** 2;
            $squaredValue26b = $residualEasting26b ** 2;
            $squaredValue26c = $residualEasting26c ** 2;

            // Residual Easting and Squared Values for set 27
            $residualEasting27a = ($sumValue11a - $vii_v_c_1) * 1000;
            $residualEasting27b = ($sumValue12a - $vii_v_c_2) * 1000;
            $residualEasting27c = ($sumValue13a - $vii_v_c_3) * 1000;
            $squaredValue27a = $residualEasting27a ** 2;
            $squaredValue27b = $residualEasting27b ** 2;
            $squaredValue27c = $residualEasting27c ** 2;

            // Residual Easting and Squared Values for set 28
            $residualEasting28a = ($sumValue14a - $viii_v_c_1) * 1000;
            $residualEasting28b = ($sumValue15a - $viii_v_c_2) * 1000;
            $residualEasting28c = ($sumValue16a - $viii_v_c_3) * 1000;
            $squaredValue28a = $residualEasting28a ** 2;
            $squaredValue28b = $residualEasting28b ** 2;
            $squaredValue28c = $residualEasting28c ** 2;

            // Residual Easting and Squared Values for set 29
            $residualEasting29a = ($sumValue11a - $xi_v_c_1) * 1000;
            $residualEasting29b = ($sumValue12a - $xi_v_c_2) * 1000;
            $residualEasting29c = ($sumValue13a - $xi_v_c_3) * 1000;
            $squaredValue29a = $residualEasting29a ** 2;
            $squaredValue29b = $residualEasting29b ** 2;
            $squaredValue29c = $residualEasting29c ** 2;

            // Residual Easting and Squared Values for set 30
            $residualEasting30a = ($sumValue14a - $x_v_c_1) * 1000;
            $residualEasting30b = ($sumValue15a - $x_v_c_2) * 1000;
            $residualEasting30c = ($sumValue16a - $x_v_c_3) * 1000;
            $squaredValue30a = $residualEasting30a ** 2;
            $squaredValue30b = $residualEasting30b ** 2;
            $squaredValue30c = $residualEasting30c ** 2;

            // Sum of squared values from $squaredValue1a up to $squaredValue30a
            $sumSquaredValues1A = $squaredValue1a + $squaredValue2a + $squaredValue3a + $squaredValue4a + $squaredValue5a +
              $squaredValue6a + $squaredValue7a + $squaredValue8a + $squaredValue9a + $squaredValue10a +
              $squaredValue11a + $squaredValue12a + $squaredValue13a + $squaredValue14a + $squaredValue15a +
              $squaredValue16a + $squaredValue17a + $squaredValue18a + $squaredValue19a + $squaredValue20a +
              $squaredValue21a + $squaredValue22a + $squaredValue23a + $squaredValue24a + $squaredValue25a +
              $squaredValue26a + $squaredValue27a + $squaredValue28a + $squaredValue29a + $squaredValue30a;

            // Sum of squared values from $squaredValue1b up to $squaredValue30b
            $sumSquaredValues1B = $squaredValue1b + $squaredValue2b + $squaredValue3b + $squaredValue4b + $squaredValue5b +
              $squaredValue6b + $squaredValue7b + $squaredValue8b + $squaredValue9b + $squaredValue10b +
              $squaredValue11b + $squaredValue12b + $squaredValue13b + $squaredValue14b + $squaredValue15b +
              $squaredValue16b + $squaredValue17b + $squaredValue18b + $squaredValue19b + $squaredValue20b +
              $squaredValue21b + $squaredValue22b + $squaredValue23b + $squaredValue24b + $squaredValue25b +
              $squaredValue26b + $squaredValue27b + $squaredValue28b + $squaredValue29b + $squaredValue30b;

            // Sum of squared values from $squaredValue1c up to $squaredValue30c
            $sumSquaredValues1C = $squaredValue1c + $squaredValue2c + $squaredValue3c + $squaredValue4c + $squaredValue5c +
              $squaredValue6c + $squaredValue7c + $squaredValue8c + $squaredValue9c + $squaredValue10c +
              $squaredValue11c + $squaredValue12c + $squaredValue13c + $squaredValue14c + $squaredValue15c +
              $squaredValue16c + $squaredValue17c + $squaredValue18c + $squaredValue19c + $squaredValue20c +
              $squaredValue21c + $squaredValue22c + $squaredValue23c + $squaredValue24c + $squaredValue25c +
              $squaredValue26c + $squaredValue27c + $squaredValue28c + $squaredValue29c + $squaredValue30c;

            $squaredeastingresult1 = sqrt($sumSquaredValues1A / 28);
            $squaredeastingresult2 = sqrt($sumSquaredValues1B / 28);
            $squaredeastingresult3 = sqrt($sumSquaredValues1C / 28);

            // Calculate the result
            $standarddeviationresult1 = sqrt(pow($squaredeastingresult1, 2) + pow($squaredeastingresult2, 2));

            // Determine the background color based on the pass/fail status
            $background_color = (
              (abs($sumValue19 - $r_v_a_9) <= 0.003) &&
              (abs($sumValue20 - $r_v_a_10) <= 0.003) &&
              ($standarddeviationresult1 < $r_v_a_11) &&
              ($squaredeastingresult3 < $r_v_a_12)
            ) ? 'lightgreen' : 'rgba(255, 0, 0, 0.3)';


            $overall_check = (
              (abs($sumValue19 - $r_v_a_9) <= 0.003) &&
              (abs($sumValue20 - $r_v_a_10) <= 0.003) &&
              ($standarddeviationresult1 < $r_v_a_11) &&
              ($squaredeastingresult3 < $r_v_a_12)
            ) ? 'PASSED' : 'FAILED';

            ?>
            <tr>
              <td class="" style="text-align:center;"></td>
              <td class="" style="text-align:center;">21</td>
              <td class="" style="text-align:center;">1</td>
              <td class="" style="text-align:center;">1</td>
              <td class="" style="text-align:center;"><?php echo round($i_v_c_1, 3); ?></td>
              <td class="" style="text-align:center;"><?php echo ($i_v_c_2); ?></td>
              <td class="" style="text-align:center;"><?php echo ($i_v_c_3); ?></td>
              <td class="" style="text-align:center;"></td>
              <td class="" style="text-align:center;"></td>
              <td class="" style="text-align:center;"><?php echo round($residualEasting21a, 3); ?></td>
              <td class="" style="text-align:center;"><?php echo round($residualEasting21b, 3); ?></td>
              <td class="" style="text-align:center;"><?php echo round($residualEasting21c, 3); ?></td>
              <td class="" style="text-align:center;"><?php echo round($squaredValue21a, 3); ?></td>
              <td class="" style="text-align:center;"><?php echo round($squaredValue21b, 3); ?></td>
              <td class="" style="text-align:center;"><?php echo round($squaredValue21c, 3); ?></td>
            </tr>
            <tr>
              <td class="" style="text-align:center;"></td>
              <td class="" style="text-align:center;">22</td>
              <td class="" style="text-align:center;">1</td>
              <td class="" style="text-align:center;">2</td>
              <td class="" style="text-align:center;"><?php echo ($ii_v_c_1); ?></td>
              <td class="" style="text-align:center;"><?php echo ($ii_v_c_2); ?></td>
              <td class="" style="text-align:center;"><?php echo ($ii_v_c_3); ?></td>
              <td class="" style="text-align:center;"><?php echo ($sumValue1c); ?></td>
              <td class="" style="text-align:center;"><?php echo ($sumValue6c); ?></td>
              <td class="" style="text-align:center;"><?php echo round($residualEasting22a, 3); ?></td>
              <td class="" style="text-align:center;"><?php echo round($residualEasting22b, 3); ?></td>
              <td class="" style="text-align:center;"><?php echo round($residualEasting22c, 3); ?></td>
              <td class="" style="text-align:center;"><?php echo round($squaredValue22a, 3); ?></td>
              <td class="" style="text-align:center;"><?php echo round($squaredValue22b, 3); ?></td>
              <td class="" style="text-align:center;"><?php echo round($squaredValue22c, 3); ?></td>
            </tr>
            <tr>
              <td class="" style="text-align:center;"></td>
              <td class="" style="text-align:center;">23</td>
              <td class="" style="text-align:center;">2</td>
              <td class="" style="text-align:center;">1</td>
              <td class="" style="text-align:center;"><?php echo ($iii_v_c_1); ?></td>
              <td class="" style="text-align:center;"><?php echo ($iii_v_c_2); ?></td>
              <td class="" style="text-align:center;"><?php echo ($iii_v_c_3); ?></td>
              <td class="" style="text-align:center;"></td>
              <td class="" style="text-align:center;"></td>
              <td class="" style="text-align:center;"><?php echo round($residualEasting23a, 3); ?></td>
              <td class="" style="text-align:center;"><?php echo round($residualEasting23b, 3); ?></td>
              <td class="" style="text-align:center;"><?php echo round($residualEasting23c, 3); ?></td>
              <td class="" style="text-align:center;"><?php echo round($squaredValue23a, 3); ?></td>
              <td class="" style="text-align:center;"><?php echo round($squaredValue23b, 3); ?></td>
              <td class="" style="text-align:center;"><?php echo round($squaredValue23c, 3); ?></td>
            </tr>
            <tr>
              <td class="" style="text-align:center;"></td>
              <td class="" style="text-align:center;">24</td>
              <td class="" style="text-align:center;">2</td>
              <td class="" style="text-align:center;">2</td>
              <td class="" style="text-align:center;"><?php echo ($iv_v_c_1); ?></td>
              <td class="" style="text-align:center;"><?php echo ($iv_v_c_2); ?></td>
              <td class="" style="text-align:center;"><?php echo ($iv_v_c_3); ?></td>
              <td class="" style="text-align:center;"><?php echo ($sumValue2c); ?></td>
              <td class="" style="text-align:center;"><?php echo ($sumValue7c); ?></td>
              <td class="" style="text-align:center;"><?php echo round($residualEasting24a, 3); ?></td>
              <td class="" style="text-align:center;"><?php echo round($residualEasting24b, 3); ?></td>
              <td class="" style="text-align:center;"><?php echo round($residualEasting24c, 3); ?></td>
              <td class="" style="text-align:center;"><?php echo round($squaredValue24a, 3); ?></td>
              <td class="" style="text-align:center;"><?php echo round($squaredValue24b, 3); ?></td>
              <td class="" style="text-align:center;"><?php echo round($squaredValue24c, 3); ?></td>
            </tr>
            <tr>
              <td class="" style="text-align:center;">3</td>
              <td class="" style="text-align:center;">25</td>
              <td class="" style="text-align:center;">3</td>
              <td class="" style="text-align:center;">1</td>
              <td class="" style="text-align:center;"><?php echo ($v_v_c_1); ?></td>
              <td class="" style="text-align:center;"><?php echo ($v_v_c_2); ?></td>
              <td class="" style="text-align:center;"><?php echo ($v_v_c_3); ?></td>
              <td class="" style="text-align:center;"></td>
              <td class="" style="text-align:center;"></td>
              <td class="" style="text-align:center;"><?php echo round($residualEasting25a, 3); ?></td>
              <td class="" style="text-align:center;"><?php echo round($residualEasting25b, 3); ?></td>
              <td class="" style="text-align:center;"><?php echo round($residualEasting25c, 3); ?></td>
              <td class="" style="text-align:center;"><?php echo round($squaredValue25a, 3); ?></td>
              <td class="" style="text-align:center;"><?php echo round($squaredValue25b, 3); ?></td>
              <td class="" style="text-align:center;"><?php echo round($squaredValue25c, 3); ?></td>
            </tr>
            <tr>
              <td class="" style="text-align:center;"></td>
              <td class="" style="text-align:center;">26</td>
              <td class="" style="text-align:center;">3</td>
              <td class="" style="text-align:center;">2</td>
              <td class="" style="text-align:center;"><?php echo ($vi_v_c_1); ?></td>
              <td class="" style="text-align:center;"><?php echo ($vi_v_c_2); ?></td>
              <td class="" style="text-align:center;"><?php echo ($vi_v_c_3); ?></td>
              <td class="" style="text-align:center;"><?php echo ($sumValue3c); ?></td>
              <td class="" style="text-align:center;"><?php echo ($sumValue8c); ?></td>
              <td class="" style="text-align:center;"><?php echo round($residualEasting26a, 3); ?></td>
              <td class="" style="text-align:center;"><?php echo round($residualEasting26b, 3); ?></td>
              <td class="" style="text-align:center;"><?php echo round($residualEasting26c, 3); ?></td>
              <td class="" style="text-align:center;"><?php echo round($squaredValue26a, 3); ?></td>
              <td class="" style="text-align:center;"><?php echo round($squaredValue26b, 3); ?></td>
              <td class="" style="text-align:center;"><?php echo round($squaredValue26c, 3); ?></td>
            </tr>
            <tr>
              <td class="" style="text-align:center;"></td>
              <td class="" style="text-align:center;">27</td>
              <td class="" style="text-align:center;">4</td>
              <td class="" style="text-align:center;">1</td>
              <td class="" style="text-align:center;"><?php echo ($vii_v_c_1); ?></td>
              <td class="" style="text-align:center;"><?php echo ($vii_v_c_2); ?></td>
              <td class="" style="text-align:center;"><?php echo ($vii_v_c_3); ?></td>
              <td class="" style="text-align:center;"></td>
              <td class="" style="text-align:center;"></td>
              <td class="" style="text-align:center;"><?php echo round($residualEasting27a, 3); ?></td>
              <td class="" style="text-align:center;"><?php echo round($residualEasting27b, 3); ?></td>
              <td class="" style="text-align:center;"><?php echo round($residualEasting27c, 3); ?></td>
              <td class="" style="text-align:center;"><?php echo round($squaredValue27a, 3); ?></td>
              <td class="" style="text-align:center;"><?php echo round($squaredValue27b, 3); ?></td>
              <td class="" style="text-align:center;"><?php echo round($squaredValue27c, 3); ?></td>
            </tr>
            <tr>
              <td class="" style="text-align:center;"></td>
              <td class="" style="text-align:center;">28</td>
              <td class="" style="text-align:center;">4</td>
              <td class="" style="text-align:center;">2</td>
              <td class="" style="text-align:center;"><?php echo ($viii_v_c_1); ?></td>
              <td class="" style="text-align:center;"><?php echo ($viii_v_c_2); ?></td>
              <td class="" style="text-align:center;"><?php echo ($viii_v_c_3); ?></td>
              <td class="" style="text-align:center;"><?php echo ($sumValue4c); ?></td>
              <td class="" style="text-align:center;"><?php echo ($sumValue9c); ?></td>
              <td class="" style="text-align:center;"><?php echo round($residualEasting28a, 3); ?></td>
              <td class="" style="text-align:center;"><?php echo round($residualEasting28b, 3); ?></td>
              <td class="" style="text-align:center;"><?php echo round($residualEasting28c, 3); ?></td>
              <td class="" style="text-align:center;"><?php echo round($squaredValue28a, 3); ?></td>
              <td class="" style="text-align:center;"><?php echo round($squaredValue28b, 3); ?></td>
              <td class="" style="text-align:center;"><?php echo round($squaredValue28c, 3); ?></td>
            </tr>
            <tr>
              <td class="" style="text-align:center;"></td>
              <td class="" style="text-align:center;">29</td>
              <td class="" style="text-align:center;">5</td>
              <td class="" style="text-align:center;">1</td>
              <td class="" style="text-align:center;"><?php echo ($xi_v_c_1); ?></td>
              <td class="" style="text-align:center;"><?php echo ($xi_v_c_2); ?></td>
              <td class="" style="text-align:center;"><?php echo ($xi_v_c_3); ?></td>
              <td class="" style="text-align:center;"></td>
              <td class="" style="text-align:center;"></td>
              <td class="" style="text-align:center;"><?php echo round($residualEasting29a, 3); ?></td>
              <td class="" style="text-align:center;"><?php echo round($residualEasting29b, 3); ?></td>
              <td class="" style="text-align:center;"><?php echo round($residualEasting29c, 3); ?></td>
              <td class="" style="text-align:center;"><?php echo round($squaredValue29a, 3); ?></td>
              <td class="" style="text-align:center;"><?php echo round($squaredValue29b, 3); ?></td>
              <td class="" style="text-align:center;"><?php echo round($squaredValue29c, 3); ?></td>
            <tr>
              <td class="" style="text-align:center;"></td>
              <td class="" style="text-align:center;">30</td>
              <td class="" style="text-align:center;">5</td>
              <td class="" style="text-align:center;">2</td>
              <td class="" style="text-align:center;"><?php echo ($x_v_c_1); ?></td>
              <td class="" style="text-align:center;"><?php echo ($x_v_c_2); ?></td>
              <td class="" style="text-align:center;"><?php echo ($x_v_c_3); ?></td>
              <td class="" style="text-align:center;"><?php echo ($sumValue5c); ?></td>
              <td class="" style="text-align:center;"><?php echo ($sumValue10c); ?></td>
              <td class="" style="text-align:center;"><?php echo round($residualEasting30a, 3); ?></td>
              <td class="" style="text-align:center;"><?php echo round($residualEasting30b, 3); ?></td>
              <td class="" style="text-align:center;"><?php echo round($residualEasting30c, 3); ?></td>
              <td class="" style="text-align:center;"><?php echo round($squaredValue30a, 3); ?></td>
              <td class="" style="text-align:center;"><?php echo round($squaredValue30b, 3); ?></td>
              <td class="" style="text-align:center;"><?php echo round($squaredValue30c, 3); ?></td>
            </tr>

            <tr>
              <th>Stop Time</th>
              <td><?php echo !empty($calibration_info->stop_time_3) ? date('H:i A', strtotime($calibration_info->stop_time_3)) : 'N/A'; ?></td>
              <th></th>
              <th></th>
              <th></th>
              <th></th>
              <th></th>
              <th></th>
              <th></th>
              <th></th>
              <th></th>
              <th></th>
              <th colspan="3">SUM</th>
            </tr>
            <tr>
              <td></td>
              <td></td>
              <td></td>
              <th>Averaged</th>
              <td class="" style="text-align:center;"><?php echo ($sumValue11a); ?></td>
              <td class="" style="text-align:center;"><?php echo ($sumValue12a); ?></td>
              <td class="" style="text-align:center;"><?php echo ($sumValue13a); ?></td>
              <th colspan="2">SUM</th>
              <td></td>
              <td></td>
              <td></td>
              <td><?php echo number_format($sumSquaredValues1A, 2, '.', ''); ?></td>
              <td><?php echo number_format($sumSquaredValues1B, 2, '.', ''); ?></td>
              <td><?php echo number_format($sumSquaredValues1C, 2, '.', ''); ?></td>
            </tr>
            <tr>
              <td></td>
              <td></td>
              <td></td>
              <td></td>
              <td class="" style="text-align:center;"><?php echo ($sumValue14a); ?></td>
              <td class="" style="text-align:center;"><?php echo ($sumValue15a); ?></td>
              <td class="" style="text-align:center;"><?php echo ($sumValue16a); ?></td>
              <td class="" style="text-align:center;"><?php echo ($sumValue17); ?></td>
              <td class="" style="text-align:center;"><?php echo round($sumValue18, 4); ?></td>
              <td></td>
              <td></td>
              <td></td>
              <td class="" style="text-align:center;"><?php echo number_format($squaredeastingresult1, 2); ?></td>
              <td class="" style="text-align:center;"><?php echo number_format($squaredeastingresult2, 2); ?></td>
              <td class="" style="text-align:center;"><?php echo number_format($squaredeastingresult3, 2); ?></td>

            </tr>
            <tr class="">
              <td></td>
              <td></td>
              <td></td>
              <th colspan="4" style="text-align: right;">Horizontal Distance/Elevation Accuracy</th>
              <td class="" style="text-align:center;"><?php echo round($sumValue19, 4); ?></td>
              <td class="" style="text-align:center;"><?php echo round($sumValue20, 4); ?></td>

              <th colspan="3" style="text-align: right;">Standard deviation</th>
              <!-- <td></td> -->
              <td colspan="2"><b><?php echo number_format($standarddeviationresult1, 0); ?></b></td>
              <td><b><?php echo number_format($squaredeastingresult3, 0); ?></b></td>
            </tr>
            <tr class="">
              <?php
              echo '<tr class="">
                                    <td></td><td></td><td></td><td></td><td></td><td></td><td></td><td></td>
                                    <td colspan="5"></td>
                                    <td colspan="2" style="text-align: center; background-color: ' . $background_color . ';">' . $overall_check . '</td>
                                </tr>';
              ?>
            </tr>
          </tbody>
        </table>

        <button type="button" class="btn btn-primary next-step">VIEW POST-CALIBRATION CHECKS</button>
      </div>

      <div class="form-section table-responsive">
        <h6 class="btn btn-secondary" disabled><b>POST-CALIBRATION CHECKS</b></h6>
        <table class="table table-bordered table-striped dataTable">
          <thead>
            <tr>
              <th>Start Time</th>
              <td><?php echo !empty($calibration_info->start_time_4) ? date('H:i A', strtotime($calibration_info->start_time_4)) : 'N/A'; ?></td>
              <td></td>
              <td></td>
              <th></th>
              <th></th>
              <td></td>
              <th colspan="2" style="text-align:center; font-weight:500; float:right;">'MEAS'</th>
              <td></td>

              <td colspan="2" font-weight:500;><b>RESIDUALS FROM MEAN</b></td>
              <td></td>
              <td colspan="2" font-weight:500;><b>RESIDUALS FROM MEAN SQUARED</b></td>
              <td></td>
            </tr>
            <tr>
              <th class="text-left" style="font-weight:500;">Series</th>
              <th class="text-left" style="font-weight:500;">Seq. No.</th>
              <th class="text-left" style="font-weight:500;">Set</th>
              <th class="text-left" style="font-weight:500;">Rover Point </th>
              <th class="text-left" style="font-weight:500;">Eastings</th>
              <th class="text-left" style="font-weight:500;">Northings</th>
              <th class="text-left" style="font-weight:500;">Elevation</th>
              <th class="text-left" style="font-weight:500;">HD(m)</th>
              <th class="text-left" style="font-weight:500;">EA(mm)</th>
              <th class="text-left" style="font-weight:500;">Eastings</th>
              <th class="text-left" style="font-weight:500;">Northings</th>
              <th class="text-left" style="font-weight:500;">Elevation</th>
              <th class="text-left" style="font-weight:500;">Eastings</th>
              <th class="text-left" style="font-weight:500;">Northings</th>
              <th class="text-left" style="font-weight:500;">Elevation</th>
            </tr>
          </thead>
          <tbody>
            <?php
            $i_v_aa_1 = $calibration_info->i_v_aa_1;
            $i_v_aa_2 = $calibration_info->i_v_aa_2;
            $i_v_aa_3 = $calibration_info->i_v_aa_3;
            $ii_v_aa_1 = $calibration_info->ii_v_aa_1;
            $ii_v_aa_2 = $calibration_info->ii_v_aa_2;
            $ii_v_aa_3 = $calibration_info->ii_v_aa_3;
            $iii_v_aa_1 = $calibration_info->iii_v_aa_1;
            $iii_v_aa_2 = $calibration_info->iii_v_aa_2;
            $iii_v_aa_3 = $calibration_info->iii_v_aa_3;
            $iv_v_aa_1 = $calibration_info->iv_v_aa_1;
            $iv_v_aa_2 = $calibration_info->iv_v_aa_2;
            $iv_v_aa_3 = $calibration_info->iv_v_aa_3;
            $v_v_aa_1 = $calibration_info->v_v_aa_1;
            $v_v_aa_2 = $calibration_info->v_v_aa_2;
            $v_v_aa_3 = $calibration_info->v_v_aa_3;
            $vi_v_aa_1 = $calibration_info->vi_v_aa_1;
            $vi_v_aa_2 = $calibration_info->vi_v_aa_2;
            $vi_v_aa_3 = $calibration_info->vi_v_aa_3;
            $vii_v_aa_1 = $calibration_info->vii_v_aa_1;
            $vii_v_aa_2 = $calibration_info->vii_v_aa_2;
            $vii_v_aa_3 = $calibration_info->vii_v_aa_3;
            $viii_v_aa_1 = $calibration_info->viii_v_aa_1;
            $viii_v_aa_2 = $calibration_info->viii_v_aa_2;
            $viii_v_aa_3 = $calibration_info->viii_v_aa_3;
            $xi_v_aa_1 = $calibration_info->xi_v_aa_1;
            $xi_v_aa_2 = $calibration_info->xi_v_aa_2;
            $xi_v_aa_3 = $calibration_info->xi_v_aa_3;
            $x_v_aa_1 = $calibration_info->x_v_aa_1;
            $x_v_aa_2 = $calibration_info->x_v_aa_2;
            $x_v_aa_3 = $calibration_info->x_v_aa_3;

            $sumValue1aa = round(sqrt(pow(($i_v_aa_1 - $ii_v_aa_1), 2) + pow(($i_v_aa_2 - $ii_v_aa_2), 2)), 4);
            $sumValue2aa = round(sqrt(pow(($iii_v_aa_1 - $iv_v_aa_1), 2) + pow(($iii_v_aa_2 - $iv_v_aa_2), 2)), 4);
            $sumValue3aa = round(sqrt(pow(($v_v_aa_1 - $vi_v_aa_1), 2) + pow(($v_v_aa_2 - $vi_v_aa_2), 2)), 4);
            $sumValue4aa = round(sqrt(pow(($vii_v_aa_1 - $viii_v_aa_1), 2) + pow(($vii_v_aa_2 - $viii_v_aa_2), 2)), 4);
            $sumValue5aa = round(sqrt(pow(($xi_v_aa_1 - $x_v_aa_1), 2) + pow(($xi_v_aa_2 - $x_v_aa_2), 2)), 4);

            $sumValue6aa = round($ii_v_aa_3 - $i_v_aa_3, 4);
            $sumValue7aa = round($iv_v_aa_3 - $iii_v_aa_3, 4);
            $sumValue8aa = round($vi_v_aa_3 - $v_v_aa_3, 4);
            $sumValue9aa = round($viii_v_aa_3 - $vii_v_aa_3, 4);
            $sumValue10aa = round($x_v_aa_3 - $xi_v_aa_3, 4);

            $sumValue12 = round($i_v_aa_2 + $iii_v_aa_2 + $v_v_aa_2 + $vii_v_aa_2 + $xi_v_aa_2) / 5;
            $sumValue13 = round($i_v_aa_3 + $iii_v_aa_3 + $v_v_aa_3 + $vii_v_aa_3 + $xi_v_aa_3) / 5;

            $sumValue14 = round($ii_v_aa_1 + $iv_v_aa_1 + $vi_v_aa_1 + $viii_v_aa_1 + $x_v_aa_1) / 5;
            $sumValue15 = round($ii_v_aa_2 + $iv_v_aa_2 + $vi_v_aa_2 + $viii_v_aa_2 + $x_v_aa_2) / 5;
            $sumValue16 = round($ii_v_aa_3 + $iv_v_aa_3 + $vi_v_aa_3 + $viii_v_aa_3 + $x_v_aa_3) / 5;

            $sumValue17 = round(($sumValue1aa + $sumValue2aa + $sumValue3aa + $sumValue4aa + $sumValue5aa) / 5, 3);
            $sumValue18 = round(($sumValue6aa + $sumValue7aa + $sumValue8aa + $sumValue9aa + $sumValue10aa) / 5, 3);

            $sumValue19 = round(($sumValue17) / 5, 3);
            $sumValue20 = round(($sumValue18) / 5, 3);

            session_start();  // Start the session
            $sumValue11b = $_SESSION['sumValue11b'];
            $sumValue12b = $_SESSION['sumValue12b'];
            $sumValue13b = $_SESSION['sumValue13b'];
            $sumValue14b = $_SESSION['sumValue14b'];
            $sumValue15b = $_SESSION['sumValue15b'];
            $sumValue16b = $_SESSION['sumValue16b'];

            // Residual Easting and Squared Values for set 1
            $residualEasting1aa = ($sumValue11b - $i_v_aa_1) * 1000;
            $residualEasting1bb = ($sumValue12b - $i_v_aa_2) * 1000;
            $residualEasting1cc = ($sumValue13b - $i_v_aa_3) * 1000;
            $squaredValue1aa = $residualEasting1aa ** 2;
            $squaredValue1bb = $residualEasting1bb ** 2;
            $squaredValue1cc = $residualEasting1cc ** 2;

            // Residual Easting and Squared Values for set 2
            $residualEasting2aa = ($sumValue14b - $ii_v_aa_1) * 1000;
            $residualEasting2bb = ($sumValue15b - $ii_v_aa_2) * 1000;
            $residualEasting2cc = ($sumValue16b - $ii_v_aa_3) * 1000;
            $squaredValue2aa = $residualEasting2aa ** 2;
            $squaredValue2bb = $residualEasting2bb ** 2;
            $squaredValue2cc = $residualEasting2cc ** 2;

            // Residual Easting and Squared Values for set 3
            $residualEasting3aa = ($sumValue11b - $iii_v_aa_1) * 1000;
            $residualEasting3bb = ($sumValue12b - $iii_v_aa_2) * 1000;
            $residualEasting3cc = ($sumValue13b - $iii_v_aa_3) * 1000;
            $squaredValue3aa = $residualEasting3aa ** 2;
            $squaredValue3bb = $residualEasting3bb ** 2;
            $squaredValue3cc = $residualEasting3cc ** 2;

            // Residual Easting and Squared Values for set 4
            $residualEasting4aa = ($sumValue14b - $iv_v_aa_1) * 1000;
            $residualEasting4bb = ($sumValue15b - $iv_v_aa_2) * 1000;
            $residualEasting4cc = ($sumValue16b - $iv_v_aa_3) * 1000;
            $squaredValue4aa = $residualEasting4aa ** 2;
            $squaredValue4bb = $residualEasting4bb ** 2;
            $squaredValue4cc = $residualEasting4cc ** 2;

            // Residual Easting and Squared Values for set 5
            $residualEasting5aa = ($sumValue11b - $v_v_aa_1) * 1000;
            $residualEasting5bb = ($sumValue12b - $v_v_aa_2) * 1000;
            $residualEasting5cc = ($sumValue13b - $v_v_aa_3) * 1000;
            $squaredValue5aa = $residualEasting5aa ** 2;
            $squaredValue5bb = $residualEasting5bb ** 2;
            $squaredValue5cc = $residualEasting5cc ** 2;

            // Residual Easting and Squared Values for set 6
            $residualEasting6aa = ($sumValue14b - $vi_v_aa_1) * 1000;
            $residualEasting6bb = ($sumValue15b - $vi_v_aa_2) * 1000;
            $residualEasting6cc = ($sumValue16b - $vi_v_aa_3) * 1000;
            $squaredValue6aa = $residualEasting6aa ** 2;
            $squaredValue6bb = $residualEasting6bb ** 2;
            $squaredValue6cc = $residualEasting6cc ** 2;

            // Residual Easting and Squared Values for set 7
            $residualEasting7aa = ($sumValue11b - $vii_v_aa_1) * 1000;
            $residualEasting7bb = ($sumValue12b - $vii_v_aa_2) * 1000;
            $residualEasting7cc = ($sumValue13b - $vii_v_aa_3) * 1000;
            $squaredValue7aa = $residualEasting7aa ** 2;
            $squaredValue7bb = $residualEasting7bb ** 2;
            $squaredValue7cc = $residualEasting7cc ** 2;

            // Residual Easting and Squared Values for set 8
            $residualEasting8aa = ($sumValue14b - $viii_v_aa_1) * 1000;
            $residualEasting8bb = ($sumValue15b - $viii_v_aa_2) * 1000;
            $residualEasting8cc = ($sumValue16b - $viii_v_aa_3) * 1000;
            $squaredValue8aa = $residualEasting8aa ** 2;
            $squaredValue8bb = $residualEasting8bb ** 2;
            $squaredValue8cc = $residualEasting8cc ** 2;

            // Residual Easting and Squared Values for set 9
            $residualEasting9aa = ($sumValue11b - $xi_v_aa_1) * 1000;
            $residualEasting9bb = ($sumValue12b - $xi_v_aa_2) * 1000;
            $residualEasting9cc = ($sumValue13b - $xi_v_aa_3) * 1000;
            $squaredValue9aa = $residualEasting9aa ** 2;
            $squaredValue9bb = $residualEasting9bb ** 2;
            $squaredValue9cc = $residualEasting9cc ** 2;

            // Residual Easting and Squared Values for set 10
            $residualEasting10aa = ($sumValue14b - $x_v_aa_1) * 1000;
            $residualEasting10bb = ($sumValue15b - $x_v_aa_2) * 1000;
            $residualEasting10cc = ($sumValue16b - $x_v_aa_3) * 1000;
            $squaredValue10aa = $residualEasting10aa ** 2;
            $squaredValue10bb = $residualEasting10bb ** 2;
            $squaredValue10cc = $residualEasting10cc ** 2;

            ?>
            <tr>
              <td class="" style="text-align:center;"></td>
              <td class="" style="text-align:center;">1</td>
              <td class="" style="text-align:center;">1</td>
              <td class="" style="text-align:center;">1</td>
              <td class="" style="text-align:center;"><?php echo round($i_v_aa_1, 3); ?></td>
              <td class="" style="text-align:center;"><?php echo ($i_v_aa_2); ?></td>
              <td class="" style="text-align:center;"><?php echo ($i_v_aa_3); ?></td>
              <td class="" style="text-align:center;"></td>
              <td class="" style="text-align:center;"></td>
              <td class="" style="text-align:center;"><?php echo round($residualEasting1aa, 3); ?></td>
              <td class="" style="text-align:center;"><?php echo round($residualEasting1bb, 3); ?></td>
              <td class="" style="text-align:center;"><?php echo round($residualEasting1cc, 3); ?></td>
              <td class="" style="text-align:center;"><?php echo round($squaredValue1aa, 3); ?></td>
              <td class="" style="text-align:center;"><?php echo round($squaredValue1bb, 3); ?></td>
              <td class="" style="text-align:center;"><?php echo round($squaredValue1cc, 3); ?></td>
            </tr>
            <tr>
              <td class="" style="text-align:center;"></td>
              <td class="" style="text-align:center;">2</td>
              <td class="" style="text-align:center;">1</td>
              <td class="" style="text-align:center;">2</td>
              <td class="" style="text-align:center;"><?php echo ($ii_v_aa_1); ?></td>
              <td class="" style="text-align:center;"><?php echo ($ii_v_aa_2); ?></td>
              <td class="" style="text-align:center;"><?php echo ($ii_v_aa_3); ?></td>
              <td class="" style="text-align:center;"><?php echo ($sumValue1aa); ?></td>
              <td class="" style="text-align:center;"><?php echo ($sumValue6aa); ?></td>
              <td class="" style="text-align:center;"><?php echo round($residualEasting2aa, 3); ?></td>
              <td class="" style="text-align:center;"><?php echo round($residualEasting2bb, 3); ?></td>
              <td class="" style="text-align:center;"><?php echo round($residualEasting2cc, 3); ?></td>
              <td class="" style="text-align:center;"><?php echo round($squaredValue2aa, 3); ?></td>
              <td class="" style="text-align:center;"><?php echo round($squaredValue2bb, 3); ?></td>
              <td class="" style="text-align:center;"><?php echo round($squaredValue2cc, 3); ?></td>
            </tr>
            <tr>
              <td class="" style="text-align:center;"></td>
              <td class="" style="text-align:center;">3</td>
              <td class="" style="text-align:center;">2</td>
              <td class="" style="text-align:center;">1</td>
              <td class="" style="text-align:center;"><?php echo ($iii_v_aa_1); ?></td>
              <td class="" style="text-align:center;"><?php echo ($iii_v_aa_2); ?></td>
              <td class="" style="text-align:center;"><?php echo ($iii_v_aa_3); ?></td>
              <td class="" style="text-align:center;"></td>
              <td class="" style="text-align:center;"></td>
              <td class="" style="text-align:center;"><?php echo round($residualEasting3aa, 3); ?></td>
              <td class="" style="text-align:center;"><?php echo round($residualEasting3bb, 3); ?></td>
              <td class="" style="text-align:center;"><?php echo round($residualEasting3cc, 3); ?></td>
              <td class="" style="text-align:center;"><?php echo round($squaredValue3aa, 3); ?></td>
              <td class="" style="text-align:center;"><?php echo round($squaredValue3bb, 3); ?></td>
              <td class="" style="text-align:center;"><?php echo round($squaredValue3cc, 3); ?></td>
            </tr>
            <tr>
              <td class="" style="text-align:center;"></td>
              <td class="" style="text-align:center;">4</td>
              <td class="" style="text-align:center;">2</td>
              <td class="" style="text-align:center;">2</td>
              <td class="" style="text-align:center;"><?php echo ($iv_v_aa_1); ?></td>
              <td class="" style="text-align:center;"><?php echo ($iv_v_aa_2); ?></td>
              <td class="" style="text-align:center;"><?php echo ($iv_v_aa_3); ?></td>
              <td class="" style="text-align:center;"><?php echo ($sumValue2aa); ?></td>
              <td class="" style="text-align:center;"><?php echo ($sumValue7aa); ?></td>
              <td class="" style="text-align:center;"><?php echo round($residualEasting4aa, 3); ?></td>
              <td class="" style="text-align:center;"><?php echo round($residualEasting4bb, 3); ?></td>
              <td class="" style="text-align:center;"><?php echo round($residualEasting4cc, 3); ?></td>
              <td class="" style="text-align:center;"><?php echo round($squaredValue4aa, 3); ?></td>
              <td class="" style="text-align:center;"><?php echo round($squaredValue4bb, 3); ?></td>
              <td class="" style="text-align:center;"><?php echo round($squaredValue4cc, 3); ?></td>
            </tr>
            <tr>
              <td class="" style="text-align:center;">1</td>
              <td class="" style="text-align:center;">5</td>
              <td class="" style="text-align:center;">3</td>
              <td class="" style="text-align:center;">1</td>
              <td class="" style="text-align:center;"><?php echo ($v_v_aa_1); ?></td>
              <td class="" style="text-align:center;"><?php echo ($v_v_aa_2); ?></td>
              <td class="" style="text-align:center;"><?php echo ($v_v_aa_3); ?></td>
              <td class="" style="text-align:center;"></td>
              <td class="" style="text-align:center;"></td>
              <td class="" style="text-align:center;"><?php echo round($residualEasting5aa, 3); ?></td>
              <td class="" style="text-align:center;"><?php echo round($residualEasting5bb, 3); ?></td>
              <td class="" style="text-align:center;"><?php echo round($residualEasting5cc, 3); ?></td>
              <td class="" style="text-align:center;"><?php echo round($squaredValue5aa, 3); ?></td>
              <td class="" style="text-align:center;"><?php echo round($squaredValue5bb, 3); ?></td>
              <td class="" style="text-align:center;"><?php echo round($squaredValue5cc, 3); ?></td>
            </tr>
            <tr>
              <td class="" style="text-align:center;"></td>
              <td class="" style="text-align:center;">6</td>
              <td class="" style="text-align:center;">3</td>
              <td class="" style="text-align:center;">2</td>
              <td class="" style="text-align:center;"><?php echo ($vi_v_aa_1); ?></td>
              <td class="" style="text-align:center;"><?php echo ($vi_v_aa_2); ?></td>
              <td class="" style="text-align:center;"><?php echo ($vi_v_aa_3); ?></td>
              <td class="" style="text-align:center;"><?php echo ($sumValue3aa); ?></td>
              <td class="" style="text-align:center;"><?php echo ($sumValue8aa); ?></td>
              <td class="" style="text-align:center;"><?php echo round($residualEasting6aa, 3); ?></td>
              <td class="" style="text-align:center;"><?php echo round($residualEasting6bb, 3); ?></td>
              <td class="" style="text-align:center;"><?php echo round($residualEasting6cc, 3); ?></td>
              <td class="" style="text-align:center;"><?php echo round($squaredValue6aa, 3); ?></td>
              <td class="" style="text-align:center;"><?php echo round($squaredValue6bb, 3); ?></td>
              <td class="" style="text-align:center;"><?php echo round($squaredValue6cc, 3); ?></td>
            </tr>
            <tr>
              <td class="" style="text-align:center;"></td>
              <td class="" style="text-align:center;">7</td>
              <td class="" style="text-align:center;">4</td>
              <td class="" style="text-align:center;">1</td>
              <td class="" style="text-align:center;"><?php echo ($vii_v_aa_1); ?></td>
              <td class="" style="text-align:center;"><?php echo ($vii_v_aa_2); ?></td>
              <td class="" style="text-align:center;"><?php echo ($vii_v_aa_3); ?></td>
              <td class="" style="text-align:center;"></td>
              <td class="" style="text-align:center;"></td>
              <td class="" style="text-align:center;"><?php echo round($residualEasting7aa, 3); ?></td>
              <td class="" style="text-align:center;"><?php echo round($residualEasting7bb, 3); ?></td>
              <td class="" style="text-align:center;"><?php echo round($residualEasting7cc, 3); ?></td>
              <td class="" style="text-align:center;"><?php echo round($squaredValue7aa, 3); ?></td>
              <td class="" style="text-align:center;"><?php echo round($squaredValue7bb, 3); ?></td>
              <td class="" style="text-align:center;"><?php echo round($squaredValue7cc, 3); ?></td>
            </tr>
            <tr>
              <td class="" style="text-align:center;"></td>
              <td class="" style="text-align:center;">8</td>
              <td class="" style="text-align:center;">4</td>
              <td class="" style="text-align:center;">2</td>
              <td class="" style="text-align:center;"><?php echo ($viii_v_aa_1); ?></td>
              <td class="" style="text-align:center;"><?php echo ($viii_v_aa_2); ?></td>
              <td class="" style="text-align:center;"><?php echo ($viii_v_aa_3); ?></td>
              <td class="" style="text-align:center;"><?php echo ($sumValue4aa); ?></td>
              <td class="" style="text-align:center;"><?php echo ($sumValue9aa); ?></td>
              <td class="" style="text-align:center;"><?php echo round($residualEasting8aa, 3); ?></td>
              <td class="" style="text-align:center;"><?php echo round($residualEasting8bb, 3); ?></td>
              <td class="" style="text-align:center;"><?php echo round($residualEasting8cc, 3); ?></td>
              <td class="" style="text-align:center;"><?php echo round($squaredValue8aa, 3); ?></td>
              <td class="" style="text-align:center;"><?php echo round($squaredValue8bb, 3); ?></td>
              <td class="" style="text-align:center;"><?php echo round($squaredValue8cc, 3); ?></td>
            </tr>
            <tr>
              <td class="" style="text-align:center;"></td>
              <td class="" style="text-align:center;">9</td>
              <td class="" style="text-align:center;">5</td>
              <td class="" style="text-align:center;">1</td>
              <td class="" style="text-align:center;"><?php echo ($xi_v_aa_1); ?></td>
              <td class="" style="text-align:center;"><?php echo ($xi_v_aa_2); ?></td>
              <td class="" style="text-align:center;"><?php echo ($xi_v_aa_3); ?></td>
              <td class="" style="text-align:center;"></td>
              <td class="" style="text-align:center;"></td>
              <td class="" style="text-align:center;"><?php echo round($residualEasting9aa, 3); ?></td>
              <td class="" style="text-align:center;"><?php echo round($residualEasting9bb, 3); ?></td>
              <td class="" style="text-align:center;"><?php echo round($residualEasting9cc, 3); ?></td>
              <td class="" style="text-align:center;"><?php echo round($squaredValue9aa, 3); ?></td>
              <td class="" style="text-align:center;"><?php echo round($squaredValue9bb, 3); ?></td>
              <td class="" style="text-align:center;"><?php echo round($squaredValue9cc, 3); ?></td>
            </tr>
            <tr>
              <td class="" style="text-align:center;"></td>
              <td class="" style="text-align:center;">10</td>
              <td class="" style="text-align:center;">5</td>
              <td class="" style="text-align:center;">2</td>
              <td class="" style="text-align:center;"><?php echo ($x_v_aa_1); ?></td>
              <td class="" style="text-align:center;"><?php echo ($x_v_aa_2); ?></td>
              <td class="" style="text-align:center;"><?php echo ($x_v_aa_3); ?></td>
              <td class="" style="text-align:center;"><?php echo ($sumValue5aa); ?></td>
              <td class="" style="text-align:center;"><?php echo ($sumValue10aa); ?></td>
              <td class="" style="text-align:center;"><?php echo round($residualEasting10aa, 3); ?></td>
              <td class="" style="text-align:center;"><?php echo round($residualEasting10bb, 3); ?></td>
              <td class="" style="text-align:center;"><?php echo round($residualEasting10cc, 3); ?></td>
              <td class="" style="text-align:center;"><?php echo round($squaredValue10aa, 3); ?></td>
              <td class="" style="text-align:center;"><?php echo round($squaredValue10bb, 3); ?></td>
              <td class="" style="text-align:center;"><?php echo round($squaredValue10cc, 3); ?></td>
            </tr>
            <tr>
              <td>Stop Time</td>
              <td><?php echo !empty($calibration_info->stop_time_4) ? date('H:i A', strtotime($calibration_info->stop_time_4)) : 'N/A'; ?></td>
              <td></td>
              <th></th>
              <th></th>
              <td></td>
              <th colspan="2" style="text-align:center; font-weight:500; float:right;"></th>
            </tr>

          </tbody>
        </table>

        <table class="table table-bordered table-striped dataTable">
          <thead>
            <tr>
              <th>Start Time</th>
              <td><?php echo !empty($calibration_info->start_time_5) ? date('H:i A', strtotime($calibration_info->start_time_5)) : 'N/A'; ?></td>
              <td></td>
              <td></td>
              <th></th>
              <th></th>
              <td></td>
              <th colspan="2" style="text-align:center; font-weight:500; float:right;">'MEAS'</th>
              <td></td>

              <td colspan="2" font-weight:500;><b>RESIDUALS FROM MEAN</b></td>
              <td></td>
              <td colspan="2" font-weight:500;><b>RESIDUALS FROM MEAN SQUARED</b></td>
              <td></td>
            </tr>
            <tr>
              <th class="text-left" style="font-weight:500;">Series</th>
              <th class="text-left" style="font-weight:500;">Seq. No.</th>
              <th class="text-left" style="font-weight:500;">Set</th>
              <th class="text-left" style="font-weight:500;">Rover Point </th>
              <th class="text-left" style="font-weight:500;">Eastings</th>
              <th class="text-left" style="font-weight:500;">Northings</th>
              <th class="text-left" style="font-weight:500;">Elevation</th>
              <th class="text-left" style="font-weight:500;">HD(m)</th>
              <th class="text-left" style="font-weight:500;">EA(mm)</th>
              <th class="text-left" style="font-weight:500;">Eastings</th>
              <th class="text-left" style="font-weight:500;">Northings</th>
              <th class="text-left" style="font-weight:500;">Elevation</th>
              <th class="text-left" style="font-weight:500;">Eastings</th>
              <th class="text-left" style="font-weight:500;">Northings</th>
              <th class="text-left" style="font-weight:500;">Elevation</th>
            </tr>
          </thead>
          <tbody>
            <?php
            $i_v_bb_1 = $calibration_info->i_v_bb_1;
            $i_v_bb_2 = $calibration_info->i_v_bb_2;
            $i_v_bb_3 = $calibration_info->i_v_bb_3;
            $ii_v_bb_1 = $calibration_info->ii_v_bb_1;
            $ii_v_bb_2 = $calibration_info->ii_v_bb_2;
            $ii_v_bb_3 = $calibration_info->ii_v_bb_3;
            $iii_v_bb_1 = $calibration_info->iii_v_bb_1;
            $iii_v_bb_2 = $calibration_info->iii_v_bb_2;
            $iii_v_bb_3 = $calibration_info->iii_v_bb_3;
            $iv_v_bb_1 = $calibration_info->iv_v_bb_1;
            $iv_v_bb_2 = $calibration_info->iv_v_bb_2;
            $iv_v_bb_3 = $calibration_info->iv_v_bb_3;
            $v_v_bb_1 = $calibration_info->v_v_bb_1;
            $v_v_bb_2 = $calibration_info->v_v_bb_2;
            $v_v_bb_3 = $calibration_info->v_v_bb_3;
            $vi_v_bb_1 = $calibration_info->vi_v_bb_1;
            $vi_v_bb_2 = $calibration_info->vi_v_bb_2;
            $vi_v_bb_3 = $calibration_info->vi_v_bb_3;
            $vii_v_bb_1 = $calibration_info->vii_v_bb_1;
            $vii_v_bb_2 = $calibration_info->vii_v_bb_2;
            $vii_v_bb_3 = $calibration_info->vii_v_bb_3;
            $viii_v_bb_1 = $calibration_info->viii_v_bb_1;
            $viii_v_bb_2 = $calibration_info->viii_v_bb_2;
            $viii_v_bb_3 = $calibration_info->viii_v_bb_3;
            $xi_v_bb_1 = $calibration_info->xi_v_bb_1;
            $xi_v_bb_2 = $calibration_info->xi_v_bb_2;
            $xi_v_bb_3 = $calibration_info->xi_v_bb_3;
            $x_v_bb_1 = $calibration_info->x_v_bb_1;
            $x_v_bb_2 = $calibration_info->x_v_bb_2;
            $x_v_bb_3 = $calibration_info->x_v_bb_3;

            $sumValue1bb = round(sqrt(pow(($i_v_bb_1 - $ii_v_bb_1), 2) + pow(($i_v_bb_2 - $ii_v_bb_2), 2)), 4);
            $sumValue2bb = round(sqrt(pow(($iii_v_bb_1 - $iv_v_bb_1), 2) + pow(($iii_v_bb_2 - $iv_v_bb_2), 2)), 4);
            $sumValue3bb = round(sqrt(pow(($v_v_bb_1 - $vi_v_bb_1), 2) + pow(($v_v_bb_2 - $vi_v_bb_2), 2)), 4);
            $sumValue4bb = round(sqrt(pow(($vii_v_bb_1 - $viii_v_bb_1), 2) + pow(($vii_v_bb_2 - $viii_v_bb_2), 2)), 4);
            $sumValue5bb = round(sqrt(pow(($xi_v_bb_1 - $x_v_bb_1), 2) + pow(($xi_v_bb_2 - $x_v_bb_2), 2)), 4);

            $sumValue6bb = round($ii_v_bb_3 - $i_v_bb_3, 4);
            $sumValue7bb = round($iv_v_bb_3 - $iii_v_bb_3, 4);
            $sumValue8bb = round($vi_v_bb_3 - $v_v_bb_3, 4);
            $sumValue9bb = round($viii_v_bb_3 - $vii_v_bb_3, 4);
            $sumValue10bb = round($x_v_bb_3 - $xi_v_bb_3, 4);

            $sumValue12 = round($i_v_bb_2 + $iii_v_bb_2 + $v_v_bb_2 + $vii_v_bb_2 + $xi_v_bb_2) / 5;
            $sumValue13 = round($i_v_bb_3 + $iii_v_bb_3 + $v_v_bb_3 + $vii_v_bb_3 + $xi_v_bb_3) / 5;

            $sumValue14 = round($ii_v_bb_1 + $iv_v_bb_1 + $vi_v_bb_1 + $viii_v_bb_1 + $x_v_bb_1) / 5;
            $sumValue15 = round($ii_v_bb_2 + $iv_v_bb_2 + $vi_v_bb_2 + $viii_v_bb_2 + $x_v_bb_2) / 5;
            $sumValue16 = round($ii_v_bb_3 + $iv_v_bb_3 + $vi_v_bb_3 + $viii_v_bb_3 + $x_v_bb_3) / 5;

            $sumValue17 = round(($sumValue1bb + $sumValue2bb + $sumValue3bb + $sumValue4bb + $sumValue5bb) / 5, 3);
            $sumValue18 = round(($sumValue6bb + $sumValue7bb + $sumValue8bb + $sumValue9bb + $sumValue10bb) / 5, 3);

            $sumValue19 = round(($sumValue17) / 5, 3);
            $sumValue20 = round(($sumValue18) / 5, 3);

            session_start();  // Start the session
            $sumValue11b = $_SESSION['sumValue11b'];
            $sumValue12b = $_SESSION['sumValue12b'];
            $sumValue13b = $_SESSION['sumValue13b'];
            $sumValue14b = $_SESSION['sumValue14b'];
            $sumValue15b = $_SESSION['sumValue15b'];
            $sumValue16b = $_SESSION['sumValue16b'];

            // Residual Easting and Squared Values for set 11
            $residualEasting11aa = ($sumValue11b - $i_v_bb_1) * 1000;
            $residualEasting11bb = ($sumValue12b - $i_v_bb_2) * 1000;
            $residualEasting11cc = ($sumValue13b - $i_v_bb_3) * 1000;
            $squaredValue11aa = $residualEasting11aa ** 2;
            $squaredValue11bb = $residualEasting11bb ** 2;
            $squaredValue11cc = $residualEasting11cc ** 2;

            // Residual Easting and Squared Values for set 12
            $residualEasting12aa = ($sumValue14b - $ii_v_bb_1) * 1000;
            $residualEasting12bb = ($sumValue15b - $ii_v_bb_2) * 1000;
            $residualEasting12cc = ($sumValue16b - $ii_v_bb_3) * 1000;
            $squaredValue12aa = $residualEasting12aa ** 2;
            $squaredValue12bb = $residualEasting12bb ** 2;
            $squaredValue12cc = $residualEasting12cc ** 2;

            // Residual Easting and Squared Values for set 13
            $residualEasting13aa = ($sumValue11b - $iii_v_bb_1) * 1000;
            $residualEasting13bb = ($sumValue12b - $iii_v_bb_2) * 1000;
            $residualEasting13cc = ($sumValue13b - $iii_v_bb_3) * 1000;
            $squaredValue13aa = $residualEasting13aa ** 2;
            $squaredValue13bb = $residualEasting13bb ** 2;
            $squaredValue13cc = $residualEasting13cc ** 2;

            // Residual Easting and Squared Values for set 14
            $residualEasting14aa = ($sumValue14b - $iv_v_bb_1) * 1000;
            $residualEasting14bb = ($sumValue15b - $iv_v_bb_2) * 1000;
            $residualEasting14cc = ($sumValue16b - $iv_v_bb_3) * 1000;
            $squaredValue14aa = $residualEasting14aa ** 2;
            $squaredValue14bb = $residualEasting14bb ** 2;
            $squaredValue14cc = $residualEasting14cc ** 2;

            // Residual Easting and Squared Values for set 15
            $residualEasting15aa = ($sumValue11b - $v_v_bb_1) * 1000;
            $residualEasting15bb = ($sumValue12b - $v_v_bb_2) * 1000;
            $residualEasting15cc = ($sumValue13b - $v_v_bb_3) * 1000;
            $squaredValue15aa = $residualEasting15aa ** 2;
            $squaredValue15bb = $residualEasting15bb ** 2;
            $squaredValue15cc = $residualEasting15cc ** 2;

            // Residual Easting and Squared Values for set 16
            $residualEasting16aa = ($sumValue14b - $vi_v_bb_1) * 1000;
            $residualEasting16bb = ($sumValue15b - $vi_v_bb_2) * 1000;
            $residualEasting16cc = ($sumValue16b - $vi_v_bb_3) * 1000;
            $squaredValue16aa = $residualEasting16aa ** 2;
            $squaredValue16bb = $residualEasting16bb ** 2;
            $squaredValue16cc = $residualEasting16cc ** 2;

            // Residual Easting and Squared Values for set 17
            $residualEasting17aa = ($sumValue11b - $vii_v_bb_1) * 1000;
            $residualEasting17bb = ($sumValue12b - $vii_v_bb_2) * 1000;
            $residualEasting17cc = ($sumValue13b - $vii_v_bb_3) * 1000;
            $squaredValue17aa = $residualEasting17aa ** 2;
            $squaredValue17bb = $residualEasting17bb ** 2;
            $squaredValue17cc = $residualEasting17cc ** 2;

            // Residual Easting and Squared Values for set 18
            $residualEasting18aa = ($sumValue14b - $viii_v_bb_1) * 1000;
            $residualEasting18bb = ($sumValue15b - $viii_v_bb_2) * 1000;
            $residualEasting18cc = ($sumValue16b - $viii_v_bb_3) * 1000;
            $squaredValue18aa = $residualEasting18aa ** 2;
            $squaredValue18bb = $residualEasting18bb ** 2;
            $squaredValue18cc = $residualEasting18cc ** 2;

            // Residual Easting and Squared Values for set 19
            $residualEasting19aa = ($sumValue11b - $xi_v_bb_1) * 1000;
            $residualEasting19bb = ($sumValue12b - $xi_v_bb_2) * 1000;
            $residualEasting19cc = ($sumValue13b - $xi_v_bb_3) * 1000;
            $squaredValue19aa = $residualEasting19aa ** 2;
            $squaredValue19bb = $residualEasting19bb ** 2;
            $squaredValue19cc = $residualEasting19cc ** 2;

            // Residual Easting and Squared Values for set 20
            $residualEasting20aa = ($sumValue14b - $x_v_bb_1) * 1000;
            $residualEasting20bb = ($sumValue15b - $x_v_bb_2) * 1000;
            $residualEasting20cc = ($sumValue16b - $x_v_bb_3) * 1000;
            $squaredValue20aa = $residualEasting20aa ** 2;
            $squaredValue20bb = $residualEasting20bb ** 2;
            $squaredValue20cc = $residualEasting20cc ** 2;


            ?>
            <tr>
              <td class="" style="text-align:center;"></td>
              <td class="" style="text-align:center;">11</td>
              <td class="" style="text-align:center;">1</td>
              <td class="" style="text-align:center;">1</td>
              <td class="" style="text-align:center;"><?php echo round($i_v_bb_1, 3); ?></td>
              <td class="" style="text-align:center;"><?php echo ($i_v_bb_2); ?></td>
              <td class="" style="text-align:center;"><?php echo ($i_v_bb_3); ?></td>
              <td class="" style="text-align:center;"></td>
              <td class="" style="text-align:center;"></td>
              <td class="" style="text-align:center;"><?php echo round($residualEasting11aa, 3); ?></td>
              <td class="" style="text-align:center;"><?php echo round($residualEasting11bb, 3); ?></td>
              <td class="" style="text-align:center;"><?php echo round($residualEasting11cc, 3); ?></td>
              <td class="" style="text-align:center;"><?php echo round($squaredValue11aa, 3); ?></td>
              <td class="" style="text-align:center;"><?php echo round($squaredValue11bb, 3); ?></td>
              <td class="" style="text-align:center;"><?php echo round($squaredValue11cc, 3); ?></td>
            </tr>
            <tr>
              <td class="" style="text-align:center;"></td>
              <td class="" style="text-align:center;">12</td>
              <td class="" style="text-align:center;">1</td>
              <td class="" style="text-align:center;">2</td>
              <td class="" style="text-align:center;"><?php echo ($ii_v_bb_1); ?></td>
              <td class="" style="text-align:center;"><?php echo ($ii_v_bb_2); ?></td>
              <td class="" style="text-align:center;"><?php echo ($ii_v_bb_3); ?></td>
              <td class="" style="text-align:center;"><?php echo ($sumValue1bb); ?></td>
              <td class="" style="text-align:center;"><?php echo ($sumValue6bb); ?></td>
              <td class="" style="text-align:center;"><?php echo round($residualEasting12aa, 3); ?></td>
              <td class="" style="text-align:center;"><?php echo round($residualEasting12bb, 3); ?></td>
              <td class="" style="text-align:center;"><?php echo round($residualEasting12cc, 3); ?></td>
              <td class="" style="text-align:center;"><?php echo round($squaredValue12aa, 3); ?></td>
              <td class="" style="text-align:center;"><?php echo round($squaredValue12bb, 3); ?></td>
              <td class="" style="text-align:center;"><?php echo round($squaredValue12cc, 3); ?></td>
            </tr>
            <tr>
              <td class="" style="text-align:center;"></td>
              <td class="" style="text-align:center;">13</td>
              <td class="" style="text-align:center;">2</td>
              <td class="" style="text-align:center;">1</td>
              <td class="" style="text-align:center;"><?php echo ($iii_v_bb_1); ?></td>
              <td class="" style="text-align:center;"><?php echo ($iii_v_bb_2); ?></td>
              <td class="" style="text-align:center;"><?php echo ($iii_v_bb_3); ?></td>
              <td class="" style="text-align:center;"></td>
              <td class="" style="text-align:center;"></td>
              <td class="" style="text-align:center;"><?php echo round($residualEasting13aa, 3); ?></td>
              <td class="" style="text-align:center;"><?php echo round($residualEasting13bb, 3); ?></td>
              <td class="" style="text-align:center;"><?php echo round($residualEasting13cc, 3); ?></td>
              <td class="" style="text-align:center;"><?php echo round($squaredValue13aa, 3); ?></td>
              <td class="" style="text-align:center;"><?php echo round($squaredValue13bb, 3); ?></td>
              <td class="" style="text-align:center;"><?php echo round($squaredValue13cc, 3); ?></td>
            </tr>
            <tr>
              <td class="" style="text-align:center;"></td>
              <td class="" style="text-align:center;">14</td>
              <td class="" style="text-align:center;">2</td>
              <td class="" style="text-align:center;">2</td>
              <td class="" style="text-align:center;"><?php echo ($iv_v_bb_1); ?></td>
              <td class="" style="text-align:center;"><?php echo ($iv_v_bb_2); ?></td>
              <td class="" style="text-align:center;"><?php echo ($iv_v_bb_3); ?></td>
              <td class="" style="text-align:center;"><?php echo ($sumValue2bb); ?></td>
              <td class="" style="text-align:center;"><?php echo ($sumValue7bb); ?></td>
              <td class="" style="text-align:center;"><?php echo round($residualEasting14aa, 3); ?></td>
              <td class="" style="text-align:center;"><?php echo round($residualEasting14bb, 3); ?></td>
              <td class="" style="text-align:center;"><?php echo round($residualEasting14cc, 3); ?></td>
              <td class="" style="text-align:center;"><?php echo round($squaredValue14aa, 3); ?></td>
              <td class="" style="text-align:center;"><?php echo round($squaredValue14bb, 3); ?></td>
              <td class="" style="text-align:center;"><?php echo round($squaredValue14cc, 3); ?></td>
            </tr>
            <tr>
              <td class="" style="text-align:center;">2</td>
              <td class="" style="text-align:center;">15</td>
              <td class="" style="text-align:center;">3</td>
              <td class="" style="text-align:center;">1</td>
              <td class="" style="text-align:center;"><?php echo ($v_v_bb_1); ?></td>
              <td class="" style="text-align:center;"><?php echo ($v_v_bb_2); ?></td>
              <td class="" style="text-align:center;"><?php echo ($v_v_bb_3); ?></td>
              <td class="" style="text-align:center;"></td>
              <td class="" style="text-align:center;"></td>
              <td class="" style="text-align:center;"><?php echo round($residualEasting15aa, 3); ?></td>
              <td class="" style="text-align:center;"><?php echo round($residualEasting15bb, 3); ?></td>
              <td class="" style="text-align:center;"><?php echo round($residualEasting15cc, 3); ?></td>
              <td class="" style="text-align:center;"><?php echo round($squaredValue15aa, 3); ?></td>
              <td class="" style="text-align:center;"><?php echo round($squaredValue15bb, 3); ?></td>
              <td class="" style="text-align:center;"><?php echo round($squaredValue15cc, 3); ?></td>
            </tr>
            <tr>
              <td class="" style="text-align:center;"></td>
              <td class="" style="text-align:center;">16</td>
              <td class="" style="text-align:center;">3</td>
              <td class="" style="text-align:center;">2</td>
              <td class="" style="text-align:center;"><?php echo ($vi_v_bb_1); ?></td>
              <td class="" style="text-align:center;"><?php echo ($vi_v_bb_2); ?></td>
              <td class="" style="text-align:center;"><?php echo ($vi_v_bb_3); ?></td>
              <td class="" style="text-align:center;"><?php echo ($sumValue3bb); ?></td>
              <td class="" style="text-align:center;"><?php echo ($sumValue8bb); ?></td>
              <td class="" style="text-align:center;"><?php echo round($residualEasting16aa, 3); ?></td>
              <td class="" style="text-align:center;"><?php echo round($residualEasting16bb, 3); ?></td>
              <td class="" style="text-align:center;"><?php echo round($residualEasting16cc, 3); ?></td>
              <td class="" style="text-align:center;"><?php echo round($squaredValue16aa, 3); ?></td>
              <td class="" style="text-align:center;"><?php echo round($squaredValue16bb, 3); ?></td>
              <td class="" style="text-align:center;"><?php echo round($squaredValue16cc, 3); ?></td>
            </tr>
            <tr>
              <td class="" style="text-align:center;"></td>
              <td class="" style="text-align:center;">17</td>
              <td class="" style="text-align:center;">4</td>
              <td class="" style="text-align:center;">1</td>
              <td class="" style="text-align:center;"><?php echo ($vii_v_bb_1); ?></td>
              <td class="" style="text-align:center;"><?php echo ($vii_v_bb_2); ?></td>
              <td class="" style="text-align:center;"><?php echo ($vii_v_bb_3); ?></td>
              <td class="" style="text-align:center;"></td>
              <td class="" style="text-align:center;"></td>
              <td class="" style="text-align:center;"><?php echo round($residualEasting17aa, 3); ?></td>
              <td class="" style="text-align:center;"><?php echo round($residualEasting17bb, 3); ?></td>
              <td class="" style="text-align:center;"><?php echo round($residualEasting17cc, 3); ?></td>
              <td class="" style="text-align:center;"><?php echo round($squaredValue17aa, 3); ?></td>
              <td class="" style="text-align:center;"><?php echo round($squaredValue17bb, 3); ?></td>
              <td class="" style="text-align:center;"><?php echo round($squaredValue17cc, 3); ?></td>
            </tr>
            <tr>
              <td class="" style="text-align:center;"></td>
              <td class="" style="text-align:center;">18</td>
              <td class="" style="text-align:center;">4</td>
              <td class="" style="text-align:center;">2</td>
              <td class="" style="text-align:center;"><?php echo ($viii_v_bb_1); ?></td>
              <td class="" style="text-align:center;"><?php echo ($viii_v_bb_2); ?></td>
              <td class="" style="text-align:center;"><?php echo ($viii_v_bb_3); ?></td>
              <td class="" style="text-align:center;"><?php echo ($sumValue4bb); ?></td>
              <td class="" style="text-align:center;"><?php echo ($sumValue9bb); ?></td>
              <td class="" style="text-align:center;"><?php echo round($residualEasting18aa, 3); ?></td>
              <td class="" style="text-align:center;"><?php echo round($residualEasting18bb, 3); ?></td>
              <td class="" style="text-align:center;"><?php echo round($residualEasting18cc, 3); ?></td>
              <td class="" style="text-align:center;"><?php echo round($squaredValue18aa, 3); ?></td>
              <td class="" style="text-align:center;"><?php echo round($squaredValue18bb, 3); ?></td>
              <td class="" style="text-align:center;"><?php echo round($squaredValue18cc, 3); ?></td>
            </tr>
            <tr>
              <td class="" style="text-align:center;"></td>
              <td class="" style="text-align:center;">19</td>
              <td class="" style="text-align:center;">5</td>
              <td class="" style="text-align:center;">1</td>
              <td class="" style="text-align:center;"><?php echo ($xi_v_bb_1); ?></td>
              <td class="" style="text-align:center;"><?php echo ($xi_v_bb_2); ?></td>
              <td class="" style="text-align:center;"><?php echo ($xi_v_bb_3); ?></td>
              <td class="" style="text-align:center;"></td>
              <td class="" style="text-align:center;"></td>
              <td class="" style="text-align:center;"><?php echo round($residualEasting19aa, 3); ?></td>
              <td class="" style="text-align:center;"><?php echo round($residualEasting19bb, 3); ?></td>
              <td class="" style="text-align:center;"><?php echo round($residualEasting19cc, 3); ?></td>
              <td class="" style="text-align:center;"><?php echo round($squaredValue19aa, 3); ?></td>
              <td class="" style="text-align:center;"><?php echo round($squaredValue19bb, 3); ?></td>
              <td class="" style="text-align:center;"><?php echo round($squaredValue19cc, 3); ?></td>
            </tr>
            <tr>
              <td class="" style="text-align:center;"></td>
              <td class="" style="text-align:center;">20</td>
              <td class="" style="text-align:center;">5</td>
              <td class="" style="text-align:center;">2</td>
              <td class="" style="text-align:center;"><?php echo ($x_v_bb_1); ?></td>
              <td class="" style="text-align:center;"><?php echo ($x_v_bb_2); ?></td>
              <td class="" style="text-align:center;"><?php echo ($x_v_bb_3); ?></td>
              <td class="" style="text-align:center;"><?php echo ($sumValue5bb); ?></td>
              <td class="" style="text-align:center;"><?php echo ($sumValue10bb); ?></td>
              <td class="" style="text-align:center;"><?php echo round($residualEasting20aa, 3); ?></td>
              <td class="" style="text-align:center;"><?php echo round($residualEasting20bb, 3); ?></td>
              <td class="" style="text-align:center;"><?php echo round($residualEasting20cc, 3); ?></td>
              <td class="" style="text-align:center;"><?php echo round($squaredValue20aa, 3); ?></td>
              <td class="" style="text-align:center;"><?php echo round($squaredValue20bb, 3); ?></td>
              <td class="" style="text-align:center;"><?php echo round($squaredValue20cc, 3); ?></td>
            </tr>
            <tr>
              <td>Stop Time</td>
              <td><?php echo !empty($calibration_info->stop_time_5) ? date('H:i A', strtotime($calibration_info->stop_time_5)) : 'N/A'; ?></td>
              <td></td>
              <th></th>
              <th></th>
              <td></td>
              <th colspan="2" style="text-align:center; font-weight:500; float:right;"></th>
            </tr>
          </tbody>
        </table>

        <table class="table table-bordered table-striped dataTable">
          <thead>
            <tr>
              <th>Start Time</th>
              <td><?php echo !empty($calibration_info->start_time_6) ? date('H:i A', strtotime($calibration_info->start_time_6)) : 'N/A'; ?></td>
              <td></td>
              <td></td>
              <th></th>
              <th></th>
              <td></td>
              <th colspan="2" style="text-align:center; font-weight:500; float:right;">'MEAS'</th>
              <td></td>

              <td colspan="2" font-weight:500;><b>RESIDUALS FROM MEAN</b></td>
              <td></td>
              <td colspan="2" font-weight:500;><b>RESIDUALS FROM MEAN SQUARED</b></td>
              <td></td>
            </tr>
            <tr>
              <th class="text-left" style="font-weight:500;">Series</th>
              <th class="text-left" style="font-weight:500;">Seq. No.</th>
              <th class="text-left" style="font-weight:500;">Set</th>
              <th class="text-left" style="font-weight:500;">Rover Point </th>
              <th class="text-left" style="font-weight:500;">Eastings</th>
              <th class="text-left" style="font-weight:500;">Northings</th>
              <th class="text-left" style="font-weight:500;">Elevation</th>
              <th class="text-left" style="font-weight:500;">HD(m)</th>
              <th class="text-left" style="font-weight:500;">EA(mm)</th>
              <th class="text-left" style="font-weight:500;">Eastings</th>
              <th class="text-left" style="font-weight:500;">Northings</th>
              <th class="text-left" style="font-weight:500;">Elevation</th>
              <th class="text-left" style="font-weight:500;">Eastings</th>
              <th class="text-left" style="font-weight:500;">Northings</th>
              <th class="text-left" style="font-weight:500;">Elevation</th>
            </tr>
          </thead>
          <tbody>
            <?php
            $i_v_cc_1 = $calibration_info->i_v_cc_1;
            $i_v_cc_2 = $calibration_info->i_v_cc_2;
            $i_v_cc_3 = $calibration_info->i_v_cc_3;
            $ii_v_cc_1 = $calibration_info->ii_v_cc_1;
            $ii_v_cc_2 = $calibration_info->ii_v_cc_2;
            $ii_v_cc_3 = $calibration_info->ii_v_cc_3;
            $iii_v_cc_1 = $calibration_info->iii_v_cc_1;
            $iii_v_cc_2 = $calibration_info->iii_v_cc_2;
            $iii_v_cc_3 = $calibration_info->iii_v_cc_3;
            $iv_v_cc_1 = $calibration_info->iv_v_cc_1;
            $iv_v_cc_2 = $calibration_info->iv_v_cc_2;
            $iv_v_cc_3 = $calibration_info->iv_v_cc_3;
            $v_v_cc_1 = $calibration_info->v_v_cc_1;
            $v_v_cc_2 = $calibration_info->v_v_cc_2;
            $v_v_cc_3 = $calibration_info->v_v_cc_3;
            $vi_v_cc_1 = $calibration_info->vi_v_cc_1;
            $vi_v_cc_2 = $calibration_info->vi_v_cc_2;
            $vi_v_cc_3 = $calibration_info->vi_v_cc_3;
            $vii_v_cc_1 = $calibration_info->vii_v_cc_1;
            $vii_v_cc_2 = $calibration_info->vii_v_cc_2;
            $vii_v_cc_3 = $calibration_info->vii_v_cc_3;
            $viii_v_cc_1 = $calibration_info->viii_v_cc_1;
            $viii_v_cc_2 = $calibration_info->viii_v_cc_2;
            $viii_v_cc_3 = $calibration_info->viii_v_cc_3;
            $xi_v_cc_1 = $calibration_info->xi_v_cc_1;
            $xi_v_cc_2 = $calibration_info->xi_v_cc_2;
            $xi_v_cc_3 = $calibration_info->xi_v_cc_3;
            $x_v_cc_1 = $calibration_info->x_v_cc_1;
            $x_v_cc_2 = $calibration_info->x_v_cc_2;
            $x_v_cc_3 = $calibration_info->x_v_cc_3;

            $sumValue1cc = round(sqrt(pow(($i_v_cc_1 - $ii_v_cc_1), 2) + pow(($i_v_cc_2 - $ii_v_cc_2), 2)), 4);
            $sumValue2cc = round(sqrt(pow(($iii_v_cc_1 - $iv_v_cc_1), 2) + pow(($iii_v_cc_2 - $iv_v_cc_2), 2)), 4);
            $sumValue3cc = round(sqrt(pow(($v_v_cc_1 - $vi_v_cc_1), 2) + pow(($v_v_cc_2 - $vi_v_cc_2), 2)), 4);
            $sumValue4cc = round(sqrt(pow(($vii_v_cc_1 - $viii_v_cc_1), 2) + pow(($vii_v_cc_2 - $viii_v_cc_2), 2)), 4);
            $sumValue5cc = round(sqrt(pow(($xi_v_cc_1 - $x_v_cc_1), 2) + pow(($xi_v_cc_2 - $x_v_cc_2), 2)), 4);

            $sumValue6cc = round($ii_v_cc_3 - $i_v_cc_3, 4);
            $sumValue7cc = round($iv_v_cc_3 - $iii_v_cc_3, 4);
            $sumValue8cc = round($vi_v_cc_3 - $v_v_cc_3, 4);
            $sumValue9cc = round($viii_v_cc_3 - $vii_v_cc_3, 4);
            $sumValue10cc = round($x_v_cc_3 - $xi_v_cc_3, 4);

            $sumValue11b = round(($i_v_aa_1 + $iii_v_aa_1 + $v_v_aa_1 + $vii_v_aa_1 + $xi_v_aa_1 + $i_v_bb_1 + $iii_v_bb_1 + $v_v_bb_1 + $vii_v_bb_1 + $xi_v_bb_1 + $i_v_cc_1 + $iii_v_cc_1 + $v_v_cc_1 + $vii_v_cc_1 + $xi_v_cc_1) / 15, 4);
            $sumValue12b = round(($i_v_aa_2 + $iii_v_aa_2 + $v_v_aa_2 + $vii_v_aa_2 + $xi_v_aa_2 + $i_v_bb_2 + $iii_v_bb_2 + $v_v_bb_2 + $vii_v_bb_2 + $xi_v_bb_2 + $i_v_cc_2 + $iii_v_cc_2 + $v_v_cc_2 + $vii_v_cc_2 + $xi_v_cc_2) / 15, 4);
            $sumValue13b = round(($i_v_aa_3 + $iii_v_aa_3 + $v_v_aa_3 + $vii_v_aa_3 + $xi_v_aa_3 + $i_v_bb_3 + $iii_v_bb_3 + $v_v_bb_3 + $vii_v_bb_3 + $xi_v_bb_3 + $i_v_cc_3 + $iii_v_cc_3 + $v_v_cc_3 + $vii_v_cc_3 + $xi_v_cc_3) / 15, 4);

            $sumValue14b = round(($ii_v_aa_1 + $iv_v_aa_1 + $vi_v_aa_1 + $viii_v_aa_1 + $x_v_aa_1 + $ii_v_bb_1 + $iv_v_bb_1 + $vi_v_bb_1 + $viii_v_bb_1 + $x_v_bb_1 + $ii_v_cc_1 + $iv_v_cc_1 + $vi_v_cc_1 + $viii_v_cc_1 + $x_v_cc_1) / 15, 4);
            $sumValue15b = round(($ii_v_aa_2 + $iv_v_aa_2 + $vi_v_aa_2 + $viii_v_aa_2 + $x_v_aa_2 + $ii_v_bb_2 + $iv_v_bb_2 + $vi_v_bb_2 + $viii_v_bb_2 + $x_v_bb_2 + $ii_v_cc_2 + $iv_v_cc_2 + $vi_v_cc_2 + $viii_v_cc_2 + $x_v_cc_2) / 15, 4);
            $sumValue16b = round(($ii_v_aa_3 + $iv_v_aa_3 + $vi_v_aa_3 + $viii_v_aa_3 + $x_v_aa_3 + $ii_v_bb_3 + $iv_v_bb_3 + $vi_v_bb_3 + $viii_v_bb_3 + $x_v_bb_3 + $ii_v_cc_3 + $iv_v_cc_3 + $vi_v_cc_3 + $viii_v_cc_3 + $x_v_cc_3) / 15, 4);

            $sumValue171 = round($sumValue1aa + $sumValue2aa + $sumValue3aa + $sumValue4aa + $sumValue5aa + $sumValue1bb + $sumValue2bb + $sumValue3bb + $sumValue4bb + $sumValue5bb + $sumValue1cc + $sumValue2cc + $sumValue3cc + $sumValue4cc + $sumValue5cc, 2);
            $sumValue181 = round($sumValue6aa + $sumValue7aa + $sumValue8aa + $sumValue9aa + $sumValue10aa + $sumValue6bb + $sumValue7bb + $sumValue8bb + $sumValue9bb + $sumValue10bb + $sumValue6cc + $sumValue7cc + $sumValue8cc + $sumValue9cc + $sumValue10cc, 2);

            $r_v_a_10 = $calibration_info->r_v_a_10;
            $r_v_a_11 = $calibration_info->r_v_a_11;
            $r_v_a_12 = $calibration_info->r_v_a_12;

            $sumValue191 = round(($sumValue171) / 15, 4);
            // $sumValue19 = 19.995;
            $sumValue201 = round(($sumValue181) / 15, 4);
            // $sumValue20 = 0.028;

            // // Determine the background color based on the pass/fail status
            //   $background_color1 = (
            //     (abs($sumValue191 - $r_v_a_9) <= 0.003) &&
            //     (abs($sumValue201 - $r_v_a_10) <= 0.003)
            //   ) ? 'lightgreen' : 'rgba(255, 0, 0, 0.3)';


            // Comparison logic
            // $horizontal_distance_pass = (abs($sumValue19 - $r_v_a_9) <= 0.003) ? 'PASSED' : 'FAILED';
            //   $horizontal_distance_pass1 = (
            //     (abs($sumValue19 - $r_v_a_9) <= 0.003) &&
            //     (abs($sumValue20 - $r_v_a_10) <= 0.003)
            //   ) ? 'PASSED' : 'FAILED';

            //   $deviation_color1 = (
            //     ($standarddeviationresult2 < $r_v_a_11) && 
            //     ($squaredeastingresult6 < $r_v_a_12)
            //   ) ? 'lightgreen' : 'rgba(255, 0, 0, 0.3)';

            //   $deviation_pass1 = (
            //     ($standarddeviationresult2 < $r_v_a_11) && 
            //     ($squaredeastingresult6 < $r_v_a_12)
            //  ) ? 'PASSED' : 'FAILED';

            session_start();  // Start the session
            $_SESSION['sumValue11b'] = $sumValue11b;
            $_SESSION['sumValue12b'] = $sumValue12b;
            $_SESSION['sumValue13b'] = $sumValue13b;
            $_SESSION['sumValue14b'] = $sumValue14b;
            $_SESSION['sumValue15b'] = $sumValue15b;
            $_SESSION['sumValue16b'] = $sumValue16b;

            // Residual Easting and Squared Values for set 21
            $residualEasting21aa = ($sumValue11b - $i_v_cc_1) * 1000;
            $residualEasting21bb = ($sumValue12b - $i_v_cc_2) * 1000;
            $residualEasting21cc = ($sumValue13b - $i_v_cc_3) * 1000;
            $squaredValue21aa = $residualEasting21aa ** 2;
            $squaredValue21bb = $residualEasting21bb ** 2;
            $squaredValue21cc = $residualEasting21cc ** 2;

            // Residual Easting and Squared Values for set 22
            $residualEasting22aa = ($sumValue14b - $ii_v_cc_1) * 1000;
            $residualEasting22bb = ($sumValue15b - $ii_v_cc_2) * 1000;
            $residualEasting22cc = ($sumValue16b - $ii_v_cc_3) * 1000;
            $squaredValue22aa = $residualEasting22aa ** 2;
            $squaredValue22bb = $residualEasting22bb ** 2;
            $squaredValue22cc = $residualEasting22cc ** 2;

            // Residual Easting and Squared Values for set 23
            $residualEasting23aa = ($sumValue11b - $iii_v_cc_1) * 1000;
            $residualEasting23bb = ($sumValue12b - $iii_v_cc_2) * 1000;
            $residualEasting23cc = ($sumValue13b - $iii_v_cc_3) * 1000;
            $squaredValue23aa = $residualEasting23aa ** 2;
            $squaredValue23bb = $residualEasting23bb ** 2;
            $squaredValue23cc = $residualEasting23cc ** 2;

            // Residual Easting and Squared Values for set 24
            $residualEasting24aa = ($sumValue14b - $iv_v_cc_1) * 1000;
            $residualEasting24bb = ($sumValue15b - $iv_v_cc_2) * 1000;
            $residualEasting24cc = ($sumValue16b - $iv_v_cc_3) * 1000;
            $squaredValue24aa = $residualEasting24aa ** 2;
            $squaredValue24bb = $residualEasting24bb ** 2;
            $squaredValue24cc = $residualEasting24cc ** 2;

            // Residual Easting and Squared Values for set 25
            $residualEasting25aa = ($sumValue11b - $v_v_cc_1) * 1000;
            $residualEasting25bb = ($sumValue12b - $v_v_cc_2) * 1000;
            $residualEasting25cc = ($sumValue13b - $v_v_cc_3) * 1000;
            $squaredValue25aa = $residualEasting25aa ** 2;
            $squaredValue25bb = $residualEasting25bb ** 2;
            $squaredValue25cc = $residualEasting25cc ** 2;

            // Residual Easting and Squared Values for set 26
            $residualEasting26aa = ($sumValue14b - $vi_v_cc_1) * 1000;
            $residualEasting26bb = ($sumValue15b - $vi_v_cc_2) * 1000;
            $residualEasting26cc = ($sumValue16b - $vi_v_cc_3) * 1000;
            $squaredValue26aa = $residualEasting26aa ** 2;
            $squaredValue26bb = $residualEasting26bb ** 2;
            $squaredValue26cc = $residualEasting26cc ** 2;

            // Residual Easting and Squared Values for set 27
            $residualEasting27aa = ($sumValue11b - $vii_v_cc_1) * 1000;
            $residualEasting27bb = ($sumValue12b - $vii_v_cc_2) * 1000;
            $residualEasting27cc = ($sumValue13b - $vii_v_cc_3) * 1000;
            $squaredValue27aa = $residualEasting27aa ** 2;
            $squaredValue27bb = $residualEasting27bb ** 2;
            $squaredValue27cc = $residualEasting27cc ** 2;

            // Residual Easting and Squared Values for set 28
            $residualEasting28aa = ($sumValue14b - $viii_v_cc_1) * 1000;
            $residualEasting28bb = ($sumValue15b - $viii_v_cc_2) * 1000;
            $residualEasting28cc = ($sumValue16b - $viii_v_cc_3) * 1000;
            $squaredValue28aa = $residualEasting28aa ** 2;
            $squaredValue28bb = $residualEasting28bb ** 2;
            $squaredValue28cc = $residualEasting28cc ** 2;

            // Residual Easting and Squared Values for set 29
            $residualEasting29aa = ($sumValue11b - $xi_v_cc_1) * 1000;
            $residualEasting29bb = ($sumValue12b - $xi_v_cc_2) * 1000;
            $residualEasting29cc = ($sumValue13b - $xi_v_cc_3) * 1000;
            $squaredValue29aa = $residualEasting29aa ** 2;
            $squaredValue29bb = $residualEasting29bb ** 2;
            $squaredValue29cc = $residualEasting29cc ** 2;

            // Residual Easting and Squared Values for set 30
            $residualEasting30aa = ($sumValue14b - $x_v_cc_1) * 1000;
            $residualEasting30bb = ($sumValue15b - $x_v_cc_2) * 1000;
            $residualEasting30cc = ($sumValue16b - $x_v_cc_3) * 1000;
            $squaredValue30aa = $residualEasting30aa ** 2;
            $squaredValue30bb = $residualEasting30bb ** 2;
            $squaredValue30cc = $residualEasting30cc ** 2;

            // Sum of squared values from $squaredValue1aa up to $squaredValue30aa
            $sumSquaredValues2A = $squaredValue1aa + $squaredValue2aa + $squaredValue3aa + $squaredValue4aa + $squaredValue5aa +
              $squaredValue6aa + $squaredValue7aa + $squaredValue8aa + $squaredValue9aa + $squaredValue10aa +
              $squaredValue11aa + $squaredValue12aa + $squaredValue13aa + $squaredValue14aa + $squaredValue15aa +
              $squaredValue16aa + $squaredValue17aa + $squaredValue18aa + $squaredValue19aa + $squaredValue20aa +
              $squaredValue21aa + $squaredValue22aa + $squaredValue23aa + $squaredValue24aa + $squaredValue25aa +
              $squaredValue26aa + $squaredValue27aa + $squaredValue28aa + $squaredValue29aa + $squaredValue30aa;

            // Sum of squared values from $squaredValue1bb up to $squaredValue30bb
            $sumSquaredValues2B = $squaredValue1bb + $squaredValue2bb + $squaredValue3bb + $squaredValue4bb + $squaredValue5bb +
              $squaredValue6bb + $squaredValue7bb + $squaredValue8bb + $squaredValue9bb + $squaredValue10bb +
              $squaredValue11bb + $squaredValue12bb + $squaredValue13bb + $squaredValue14bb + $squaredValue15bb +
              $squaredValue16bb + $squaredValue17bb + $squaredValue18bb + $squaredValue19bb + $squaredValue20bb +
              $squaredValue21bb + $squaredValue22bb + $squaredValue23bb + $squaredValue24bb + $squaredValue25bb +
              $squaredValue26bb + $squaredValue27bb + $squaredValue28bb + $squaredValue29bb + $squaredValue30bb;
            // Sum of squared values from $squaredValue1cc up to $squaredValue30cc
            $sumSquaredValues2C = $squaredValue1cc + $squaredValue2cc + $squaredValue3cc + $squaredValue4cc + $squaredValue5cc +
              $squaredValue6cc + $squaredValue7cc + $squaredValue8cc + $squaredValue9cc + $squaredValue10cc +
              $squaredValue11cc + $squaredValue12cc + $squaredValue13cc + $squaredValue14cc + $squaredValue15cc +
              $squaredValue16cc + $squaredValue17cc + $squaredValue18cc + $squaredValue19cc + $squaredValue20cc +
              $squaredValue21cc + $squaredValue22cc + $squaredValue23cc + $squaredValue24cc + $squaredValue25cc +
              $squaredValue26cc + $squaredValue27cc + $squaredValue28cc + $squaredValue29cc + $squaredValue30cc;

            $squaredeastingresult4 = sqrt($sumSquaredValues2A / 28);
            $squaredeastingresult5 = sqrt($sumSquaredValues2B / 28);
            $squaredeastingresult6 = sqrt($sumSquaredValues2C / 28);

            // Calculate the result
            $standarddeviationresult2 = sqrt(pow($squaredeastingresult4, 2) + pow($squaredeastingresult5, 2));


            // Determine the background color based on the pass/fail status
            $background_color1 = (
              (abs($sumValue191 - $r_v_a_9) <= 0.003) &&
              (abs($sumValue201 - $r_v_a_10) <= 0.003) &&
              ($standarddeviationresult2 < $r_v_a_11) &&
              ($squaredeastingresult6 < $r_v_a_12)
            ) ? 'lightgreen' : 'rgba(255, 0, 0, 0.3)';


            // Comparison logic
            // $horizontal_distance_pass = (abs($sumValue19 - $r_v_a_9) <= 0.003) ? 'PASSED' : 'FAILED';

            // $horizontal_distance_pass = (
            //   (abs($sumValue19 - $r_v_a_9) <= 0.003) &&
            //   (abs($sumValue20 - $r_v_a_10) <= 0.003)
            // ) ? 'PASSED' : 'FAILED';

            $overall_check1 = (
              (abs($sumValue191 - $r_v_a_9) <= 0.003) &&
              (abs($sumValue201 - $r_v_a_10) <= 0.003) &&
              ($standarddeviationresult2 < $r_v_a_11) &&
              ($squaredeastingresult6 < $r_v_a_12)
            ) ? 'PASSED' : 'FAILED';
            ?>
            <tr>
              <td class="" style="text-align:center;"></td>
              <td class="" style="text-align:center;">21</td>
              <td class="" style="text-align:center;">1</td>
              <td class="" style="text-align:center;">1</td>
              <td class="" style="text-align:center;"><?php echo round($i_v_cc_1, 3); ?></td>
              <td class="" style="text-align:center;"><?php echo ($i_v_cc_2); ?></td>
              <td class="" style="text-align:center;"><?php echo ($i_v_cc_3); ?></td>
              <td class="" style="text-align:center;"></td>
              <td class="" style="text-align:center;"></td>
              <td class="" style="text-align:center;"><?php echo round($residualEasting21aa, 3); ?></td>
              <td class="" style="text-align:center;"><?php echo round($residualEasting21bb, 3); ?></td>
              <td class="" style="text-align:center;"><?php echo round($residualEasting21cc, 3); ?></td>
              <td class="" style="text-align:center;"><?php echo round($squaredValue21aa, 3); ?></td>
              <td class="" style="text-align:center;"><?php echo round($squaredValue21bb, 3); ?></td>
              <td class="" style="text-align:center;"><?php echo round($squaredValue21cc, 3); ?></td>
            </tr>
            <tr>
              <td class="" style="text-align:center;"></td>
              <td class="" style="text-align:center;">22</td>
              <td class="" style="text-align:center;">1</td>
              <td class="" style="text-align:center;">2</td>
              <td class="" style="text-align:center;"><?php echo ($ii_v_cc_1); ?></td>
              <td class="" style="text-align:center;"><?php echo ($ii_v_cc_2); ?></td>
              <td class="" style="text-align:center;"><?php echo ($ii_v_cc_3); ?></td>
              <td class="" style="text-align:center;"><?php echo ($sumValue1cc); ?></td>
              <td class="" style="text-align:center;"><?php echo ($sumValue6cc); ?></td>
              <td class="" style="text-align:center;"><?php echo round($residualEasting22aa, 3); ?></td>
              <td class="" style="text-align:center;"><?php echo round($residualEasting22bb, 3); ?></td>
              <td class="" style="text-align:center;"><?php echo round($residualEasting22cc, 3); ?></td>
              <td class="" style="text-align:center;"><?php echo round($squaredValue22aa, 3); ?></td>
              <td class="" style="text-align:center;"><?php echo round($squaredValue22bb, 3); ?></td>
              <td class="" style="text-align:center;"><?php echo round($squaredValue22cc, 3); ?></td>
            </tr>
            <tr>
              <td class="" style="text-align:center;"></td>
              <td class="" style="text-align:center;">23</td>
              <td class="" style="text-align:center;">2</td>
              <td class="" style="text-align:center;">1</td>
              <td class="" style="text-align:center;"><?php echo ($iii_v_cc_1); ?></td>
              <td class="" style="text-align:center;"><?php echo ($iii_v_cc_2); ?></td>
              <td class="" style="text-align:center;"><?php echo ($iii_v_cc_3); ?></td>
              <td class="" style="text-align:center;"></td>
              <td class="" style="text-align:center;"></td>
              <td class="" style="text-align:center;"><?php echo round($residualEasting23aa, 3); ?></td>
              <td class="" style="text-align:center;"><?php echo round($residualEasting23bb, 3); ?></td>
              <td class="" style="text-align:center;"><?php echo round($residualEasting23cc, 3); ?></td>
              <td class="" style="text-align:center;"><?php echo round($squaredValue23aa, 3); ?></td>
              <td class="" style="text-align:center;"><?php echo round($squaredValue23bb, 3); ?></td>
              <td class="" style="text-align:center;"><?php echo round($squaredValue23cc, 3); ?></td>
            </tr>
            <tr>
              <td class="" style="text-align:center;"></td>
              <td class="" style="text-align:center;">24</td>
              <td class="" style="text-align:center;">2</td>
              <td class="" style="text-align:center;">2</td>
              <td class="" style="text-align:center;"><?php echo ($iv_v_cc_1); ?></td>
              <td class="" style="text-align:center;"><?php echo ($iv_v_cc_2); ?></td>
              <td class="" style="text-align:center;"><?php echo ($iv_v_cc_3); ?></td>
              <td class="" style="text-align:center;"><?php echo ($sumValue2cc); ?></td>
              <td class="" style="text-align:center;"><?php echo ($sumValue7cc); ?></td>
              <td class="" style="text-align:center;"><?php echo round($residualEasting24aa, 3); ?></td>
              <td class="" style="text-align:center;"><?php echo round($residualEasting24bb, 3); ?></td>
              <td class="" style="text-align:center;"><?php echo round($residualEasting24cc, 3); ?></td>
              <td class="" style="text-align:center;"><?php echo round($squaredValue24aa, 3); ?></td>
              <td class="" style="text-align:center;"><?php echo round($squaredValue24bb, 3); ?></td>
              <td class="" style="text-align:center;"><?php echo round($squaredValue24cc, 3); ?></td>
            </tr>
            <tr>
              <td class="" style="text-align:center;">3</td>
              <td class="" style="text-align:center;">25</td>
              <td class="" style="text-align:center;">3</td>
              <td class="" style="text-align:center;">1</td>
              <td class="" style="text-align:center;"><?php echo ($v_v_cc_1); ?></td>
              <td class="" style="text-align:center;"><?php echo ($v_v_cc_2); ?></td>
              <td class="" style="text-align:center;"><?php echo ($v_v_cc_3); ?></td>
              <td class="" style="text-align:center;"></td>
              <td class="" style="text-align:center;"></td>
              <td class="" style="text-align:center;"><?php echo round($residualEasting25aa, 3); ?></td>
              <td class="" style="text-align:center;"><?php echo round($residualEasting25bb, 3); ?></td>
              <td class="" style="text-align:center;"><?php echo round($residualEasting25cc, 3); ?></td>
              <td class="" style="text-align:center;"><?php echo round($squaredValue25aa, 3); ?></td>
              <td class="" style="text-align:center;"><?php echo round($squaredValue25bb, 3); ?></td>
              <td class="" style="text-align:center;"><?php echo round($squaredValue25cc, 3); ?></td>
            </tr>
            <tr>
              <td class="" style="text-align:center;"></td>
              <td class="" style="text-align:center;">26</td>
              <td class="" style="text-align:center;">3</td>
              <td class="" style="text-align:center;">2</td>
              <td class="" style="text-align:center;"><?php echo ($vi_v_cc_1); ?></td>
              <td class="" style="text-align:center;"><?php echo ($vi_v_cc_2); ?></td>
              <td class="" style="text-align:center;"><?php echo ($vi_v_cc_3); ?></td>
              <td class="" style="text-align:center;"><?php echo ($sumValue3cc); ?></td>
              <td class="" style="text-align:center;"><?php echo ($sumValue8cc); ?></td>
              <td class="" style="text-align:center;"><?php echo round($residualEasting26aa, 3); ?></td>
              <td class="" style="text-align:center;"><?php echo round($residualEasting26bb, 3); ?></td>
              <td class="" style="text-align:center;"><?php echo round($residualEasting26cc, 3); ?></td>
              <td class="" style="text-align:center;"><?php echo round($squaredValue26aa, 3); ?></td>
              <td class="" style="text-align:center;"><?php echo round($squaredValue26bb, 3); ?></td>
              <td class="" style="text-align:center;"><?php echo round($squaredValue26cc, 3); ?></td>
            </tr>
            <tr>
              <td class="" style="text-align:center;"></td>
              <td class="" style="text-align:center;">27</td>
              <td class="" style="text-align:center;">4</td>
              <td class="" style="text-align:center;">1</td>
              <td class="" style="text-align:center;"><?php echo ($vii_v_cc_1); ?></td>
              <td class="" style="text-align:center;"><?php echo ($vii_v_cc_2); ?></td>
              <td class="" style="text-align:center;"><?php echo ($vii_v_cc_3); ?></td>
              <td class="" style="text-align:center;"></td>
              <td class="" style="text-align:center;"></td>
              <td class="" style="text-align:center;"><?php echo round($residualEasting27aa, 3); ?></td>
              <td class="" style="text-align:center;"><?php echo round($residualEasting27bb, 3); ?></td>
              <td class="" style="text-align:center;"><?php echo round($residualEasting27cc, 3); ?></td>
              <td class="" style="text-align:center;"><?php echo round($squaredValue27aa, 3); ?></td>
              <td class="" style="text-align:center;"><?php echo round($squaredValue27bb, 3); ?></td>
              <td class="" style="text-align:center;"><?php echo round($squaredValue27cc, 3); ?></td>
            </tr>
            <tr>
              <td class="" style="text-align:center;"></td>
              <td class="" style="text-align:center;">28</td>
              <td class="" style="text-align:center;">4</td>
              <td class="" style="text-align:center;">2</td>
              <td class="" style="text-align:center;"><?php echo ($viii_v_cc_1); ?></td>
              <td class="" style="text-align:center;"><?php echo ($viii_v_cc_2); ?></td>
              <td class="" style="text-align:center;"><?php echo ($viii_v_cc_3); ?></td>
              <td class="" style="text-align:center;"><?php echo ($sumValue4cc); ?></td>
              <td class="" style="text-align:center;"><?php echo ($sumValue9cc); ?></td>
              <td class="" style="text-align:center;"><?php echo round($residualEasting28aa, 3); ?></td>
              <td class="" style="text-align:center;"><?php echo round($residualEasting28bb, 3); ?></td>
              <td class="" style="text-align:center;"><?php echo round($residualEasting28cc, 3); ?></td>
              <td class="" style="text-align:center;"><?php echo round($squaredValue28aa, 3); ?></td>
              <td class="" style="text-align:center;"><?php echo round($squaredValue28bb, 3); ?></td>
              <td class="" style="text-align:center;"><?php echo round($squaredValue28cc, 3); ?></td>
            </tr>
            <tr>
              <td class="" style="text-align:center;"></td>
              <td class="" style="text-align:center;">29</td>
              <td class="" style="text-align:center;">5</td>
              <td class="" style="text-align:center;">1</td>
              <td class="" style="text-align:center;"><?php echo ($xi_v_cc_1); ?></td>
              <td class="" style="text-align:center;"><?php echo ($xi_v_cc_2); ?></td>
              <td class="" style="text-align:center;"><?php echo ($xi_v_cc_3); ?></td>
              <td class="" style="text-align:center;"></td>
              <td class="" style="text-align:center;"></td>
              <td class="" style="text-align:center;"><?php echo round($residualEasting29aa, 3); ?></td>
              <td class="" style="text-align:center;"><?php echo round($residualEasting29bb, 3); ?></td>
              <td class="" style="text-align:center;"><?php echo round($residualEasting29cc, 3); ?></td>
              <td class="" style="text-align:center;"><?php echo round($squaredValue29aa, 3); ?></td>
              <td class="" style="text-align:center;"><?php echo round($squaredValue29bb, 3); ?></td>
              <td class="" style="text-align:center;"><?php echo round($squaredValue29cc, 3); ?></td>
            </tr>
            <tr>
              <td class="" style="text-align:center;"></td>
              <td class="" style="text-align:center;">30</td>
              <td class="" style="text-align:center;">5</td>
              <td class="" style="text-align:center;">2</td>
              <td class="" style="text-align:center;"><?php echo ($x_v_cc_1); ?></td>
              <td class="" style="text-align:center;"><?php echo ($x_v_cc_2); ?></td>
              <td class="" style="text-align:center;"><?php echo ($x_v_cc_3); ?></td>
              <td class="" style="text-align:center;"><?php echo ($sumValue5cc); ?></td>
              <td class="" style="text-align:center;"><?php echo ($sumValue10cc); ?></td>
              <td class="" style="text-align:center;"><?php echo round($residualEasting30aa, 3); ?></td>
              <td class="" style="text-align:center;"><?php echo round($residualEasting30bb, 3); ?></td>
              <td class="" style="text-align:center;"><?php echo round($residualEasting30cc, 3); ?></td>
              <td class="" style="text-align:center;"><?php echo round($squaredValue30aa, 3); ?></td>
              <td class="" style="text-align:center;"><?php echo round($squaredValue30bb, 3); ?></td>
              <td class="" style="text-align:center;"><?php echo round($squaredValue30cc, 3); ?></td>
            </tr>

            <tr>
              <th>Stop Time</th>
              <td><?php echo !empty($calibration_info->stop_time_6) ? date('H:i A', strtotime($calibration_info->stop_time_6)) : 'N/A'; ?></td>
              <th></th>
              <th></th>
              <th></th>
              <th></th>
              <th></th>
              <th></th>
              <th></th>
              <th></th>
              <th></th>
              <th></th>
              <th colspan="3">SUM</th>
            </tr>
            <tr>
              <td></td>
              <td></td>
              <td></td>
              <th>Averaged</th>
              <td class="" style="text-align:center;"><?php echo ($sumValue11b); ?></td>
              <td class="" style="text-align:center;"><?php echo ($sumValue12b); ?></td>
              <td class="" style="text-align:center;"><?php echo ($sumValue13b); ?></td>
              <th colspan="2">SUM</th>
              <td></td>
              <td></td>
              <td></td>
              <td><?php echo number_format($sumSquaredValues2A, 2, '.', ''); ?></td>
              <td><?php echo number_format($sumSquaredValues2B, 2, '.', ''); ?></td>
              <td><?php echo number_format($sumSquaredValues2C, 2, '.', ''); ?></td>

            </tr>
            <tr>
              <td></td>
              <td></td>
              <td></td>
              <td></td>
              <td class="" style="text-align:center;"><?php echo ($sumValue14b); ?></td>
              <td class="" style="text-align:center;"><?php echo ($sumValue15b); ?></td>
              <td class="" style="text-align:center;"><?php echo ($sumValue16b); ?></td>
              <td class="" style="text-align:center;"><?php echo ($sumValue171); ?></td>
              <td class="" style="text-align:center;"><?php echo round($sumValue181, 4); ?></td>
              <td></td>
              <td></td>
              <td></td>
              <td class="" style="text-align:center;"><?php echo number_format($squaredeastingresult4, 2); ?></td>
              <td class="" style="text-align:center;"><?php echo number_format($squaredeastingresult5, 2); ?></td>
              <td class="" style="text-align:center;"><?php echo number_format($squaredeastingresult6, 2); ?></td>

            </tr>
            <tr class="">
              <td></td>
              <td></td>
              <td></td>
              <th colspan="4" style="text-align: right;">Horizontal Distance/Elevation Accuracy</th>
              <td class="" style="text-align:center;"><?php echo round($sumValue191, 4); ?></td>
              <td class="" style="text-align:center;"><?php echo round($sumValue201, 3); ?></td>
              <th colspan="3" style="text-align: right;">Standard deviation</th>
              <!-- <td></td> -->
              <td colspan="2"><b><?php echo number_format($standarddeviationresult1, 0); ?></b></td>
              <td><b><?php echo number_format($squaredeastingresult6, 0); ?></b></td>
            </tr>
            <tr class="">
              <?php
              echo '<tr class="">
                                    <td></td><td></td><td></td><td></td><td></td><td></td><td></td><td></td>
                                    <td colspan="5"></td>
                                    <td colspan="2" style="text-align: center; background-color: ' . $background_color1 . ';">' . $overall_check1 . '</td>
                                </tr>';
              ?>
            </tr>





          </tbody>
        </table>
        <button type="button" class="btn btn-primary prev-step">VIEW PRE-CALIBRATION CHECKS</button>
        <button type="button" class="btn btn-primary next-step">Next</button>
      </div>

    <?php } else if ($service_info->item_type == 'lasers') { ?>
      <div class="form-section current">
        <button type="button" class="btn btn-secondary">INSTRUMENT INFORMATION REPORT</button>
        <table class="table" id="serviceFields">
          <hr>
          <tbody>
            <tr class="form-inline">
              <td class="align-middle text-left" style="padding: 10px; border: 1px solid #ccc;">INSTRUMENT MAKE
                <div class="form-group">
                  <div class="input-group">
                    <input type="text" class="form-control required" autocomplete="on" placeholder="BOSCH" id="_ls_v_a_1" name="ls_v_a_1" value="<?php if (!empty($calibration_info)) {
                                                                                                                                                    echo $calibration_info->ls_v_a_1;
                                                                                                                                                  } ?>" required disabled>
                  </div>
                </div>
              </td>
              <td class="align-middle text-left" style="padding: 10px; border: 1px solid #ccc;">INSTRUMENT MODEL<br>
                <div class="form-group">
                  <div class="input-group text-center">
                    <input type="text" class="form-control required" autocomplete="on" placeholder="GLL2" name="ls_v_a_2" value="<?php if (!empty($calibration_info)) {
                                                                                                                                    echo $calibration_info->ls_v_a_2;
                                                                                                                                  } ?>" required disabled>
                  </div>
                </div>
              </td>
              <td class="align-middle text-left" style="padding: 10px; border: 1px solid #ccc;">INSTRUMENT SERIAL NO.
                <div class="form-group">
                  <div class="input-group text-center">
                    <input type="text" class="form-control required" autocomplete="on" placeholder="SGT57589" name="ls_v_a_3" value="<?php if (!empty($calibration_info)) {
                                                                                                                                        echo $calibration_info->ls_v_a_3;
                                                                                                                                      } ?>" required disabled>
                  </div>
                </div>
              </td>
              <td class="align-middle text-left" style="padding: 10px; border: 1px solid #ccc;">INSTRUMENT CONDITION
                <div class="form-group">
                  <div class="input-group text-center">
                    <input type="text" class="form-control required" autocomplete="on" placeholder="USED" name="ls_v_a_4" value="<?php if (!empty($calibration_info)) {
                                                                                                                                    echo $calibration_info->ls_v_a_4;
                                                                                                                                  } ?>" required disabled>
                  </div>
                </div>
              </td>
            </tr>
            <tr class="form-inline">
              <td class="align-middle text-left" style="padding: 10px; border: 1px solid #ccc;">TEST DISTANCE (M)
                <div class="form-group">
                  <div class="input-group text-center">
                    <input type="text" class="form-control required" autocomplete="on" placeholder="60" name="ls_v_a_5" value="<?php if (!empty($calibration_info)) {
                                                                                                                                  echo $calibration_info->ls_v_a_5;
                                                                                                                                } ?>" required disabled>
                  </div>
                </div>
              </td>
              <td class="align-middle text-left" style="padding: 10px; border: 1px solid #ccc;">MANUFACTURER EDM ACCURACY (MM)
                <div class="form-group">
                  <div class="input-group text-center">
                    <input type="text" class="form-control required" autocomplete="on" placeholder="1.5" name="ls_v_a_6" value="<?php if (!empty($calibration_info)) {
                                                                                                                                  echo $calibration_info->ls_v_a_6;
                                                                                                                                } ?>" required disabled>
                  </div>
                </div>
              </td>
              <td class="align-middle text-left" style="padding: 10px; border: 1px solid #ccc;">WEATHER CONDITION
                <div class="form-group">
                  <div class="input-group text-center">
                    <input type="text" class="form-control required" autocomplete="on" placeholder="SUNNY" name="ls_v_a_7" value="<?php if (!empty($calibration_info)) {
                                                                                                                                    echo $calibration_info->ls_v_a_7;
                                                                                                                                  } ?>" required disabled>
                  </div>
                </div>
              </td>
              <td class="align-middle text-left" style="padding: 10px; border: 1px solid #ccc;">TEMPERATURE (°C)
                <div class="form-group">
                  <div class="input-group text-center">
                    <input type="text" class="form-control required" autocomplete="on" placeholder="20" name="ls_v_a_8" value="<?php if (!empty($calibration_info)) {
                                                                                                                                  echo $calibration_info->ls_v_a_8;
                                                                                                                                } ?>" required disabled>
                  </div>
                </div>
              </td>
            </tr>
            <tr class="form-inline">
              <td class="align-middle text-left" style="padding: 10px; border: 1px solid #ccc;">AIR PRESSURE (hPa)
                <div class="form-group">
                  <div class="input-group text-center">
                    <input type="text" class="form-control required" autocomplete="on" placeholder="101" name="ls_v_a_9" value="<?php if (!empty($calibration_info)) {
                                                                                                                                  echo $calibration_info->ls_v_a_9;
                                                                                                                                } ?>" required disabled>
                  </div>
                </div>
              </td>
            </tr>
          </tbody>
        </table>

        <div class="table">
          <button type="button" class="btn btn-secondary">PRE-CALIBRATION CHECKS REPORT</button>
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
                <th class="text-center" style="font-weight:500;"></th>

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
              <?php
              // Assuming the form is submitted via POST
              $hh_bsa1 = $calibration_info->hh_bsa1;
              $hh_bsa2 = $calibration_info->hh_bsa2;
              $hh_fsa1 = $calibration_info->hh_fsa1;
              $hh_fsa2 = $calibration_info->hh_fsa2;
              $hl_bsa1 = $calibration_info->hl_bsa1;
              $hl_fsa1 = $calibration_info->hl_fsa1;
              $vl_bsa1 = $calibration_info->vl_bsa1;
              $vl_fsa1 = $calibration_info->vl_fsa1;


              $ls_v_a_6 = $calibration_info->ls_v_a_6;



              $lsresult1 = $hh_bsa1 - $hh_bsa2;
              $lsresult2 = $hh_fsa1 - $hh_fsa2;
              $lsresult7 = $hl_bsa1 - $hl_fsa1;
              $lsresult8 = $vl_bsa1 - $vl_fsa1;

              $lsdistance1 = $lsresult1 - $lsresult2;

              // Determine the color based on the condition
              $hhbackgroundColor = ($lsdistance1 < $ls_v_a_6) ? 'lightgreen' : 'lightcoral'; // Green if Pass, Red if Fail
              // Determine the color based on the condition
              $hvlbackgroundColor = ($lsresult7 < $ls_v_a_6 && $lsresult8 < $ls_v_a_6) ? 'lightgreen' : 'lightcoral'; // Green if both conditions are true, Red if either is false

              ?>
              <tr>
                <td class="text-center" style="font-weight:500;">1</td>
                <td>
                  <div class="form-group form-group-bottom">
                    <input class="form-control required" value="<?php if (!empty($calibration_info)) {
                                                                  echo $calibration_info->hh_bsa1;
                                                                } ?>" name="hh_bsa1" type="text" disabled>
                  </div>
                </td>
                <td>
                  <div class="form-group form-group-bottom">
                    <input class="form-control required" value="<?php if (!empty($calibration_info)) {
                                                                  echo $calibration_info->hh_fsa1;
                                                                } ?>" name="hh_fsa1" type="text" disabled>
                  </div>
                </td>
                <td class="text-center" style="font-weight:500;">1</td>
                <td>
                  <div class="form-group form-group-bottom">
                    <input class="form-control required" value="<?php if (!empty($calibration_info)) {
                                                                  echo $calibration_info->hl_bsa1;
                                                                } ?>" name="hl_bsa1" type="text" disabled>
                  </div>
                </td>
                <td>
                  <div class="form-group form-group-bottom">
                    <input class="form-control required" value="<?php if (!empty($calibration_info)) {
                                                                  echo $calibration_info->hl_fsa1;
                                                                } ?>" name="hl_fsa1" type="text" disabled>
                  </div>
                </td>
              </tr>
              <tr>
                <td class="text-center" style="font-weight:500;">2</td>
                <td>
                  <div class="form-group form-group-bottom">
                    <input class="form-control required" value="<?php if (!empty($calibration_info)) {
                                                                  echo $calibration_info->hh_bsa2;
                                                                } ?>" name="hh_bsa2" type="text" disabled>
                  </div>
                </td>
                <td>
                  <div class="form-group form-group-bottom">
                    <input class="form-control required" value="<?php if (!empty($calibration_info)) {
                                                                  echo $calibration_info->hh_fsa2;
                                                                } ?>" name="hh_fsa2" type="text" disabled>
                  </div>
                </td>
                <th colspan="3" class="text-center" style="font-weight:500;">VERTICAL LEVEL (SIDE TO SIDE INCLINATION) ACCURACY ERROR<br>OBSERVATIONS </th>

              </tr>
              <tr>
                <td class="text-center" style="font-weight:500;">Difference (mm)</td>
                <td>
                  <div class="form-group form-group-bottom">
                    <input class="form-control required" value="<?php echo $lsresult1; ?>" name="lsresult1" type="text" disabled>
                  </div>
                </td>
                <td>
                  <div class="form-group form-group-bottom">
                    <input class="form-control required" value="<?php echo $lsresult2; ?>" name="hh_bsa2" type="text" disabled>
                  </div>
                </td>
                <td class="text-center" style="font-weight:500;">1</td>
                <td>
                  <div class="form-group form-group-bottom">
                    <input class="form-control required" value="<?php if (!empty($calibration_info)) {
                                                                  echo $calibration_info->vl_bsa1;
                                                                } ?>" name="vl_bsa1" type="text" disabled>
                  </div>
                </td>
                <td>
                  <div class="form-group form-group-bottom">
                    <input class="form-control required" value="<?php if (!empty($calibration_info)) {
                                                                  echo $calibration_info->vl_fsa1;
                                                                } ?>" name="vl_fsa1" type="text" disabled>
                  </div>
                </td>
              </tr>
              <tr style="background-color: ;">
                <td class="text-center" style="font-weight:500;">Distance (mm)</td>

                <td colspan="2">
                  <div class="form-group form-group-bottom">
                    <input class="form-control required text-center" value="<?php echo (isset($lsresult1) && isset($lsresult2)) ? ($lsresult1 - $lsresult2) : ''; ?>" name="distance" type="text" style="background-color: <?php echo $hhbackgroundColor; ?>;" disabled>
                  </div>
                </td>

                <td class="text-center" style="font-weight:500;">Distance E (mm)</td>
                <td colspan="2">
                  <div class="form-group form-group-bottom">
                    <input class="form-control required text-center" value="<?php echo (isset($calibration_info->vl_bsa1) && isset($calibration_info->vl_fsa1)) ? ($calibration_info->vl_bsa1 - $calibration_info->vl_fsa1) : ''; ?>" name="distance" type="text" style="background-color: <?php echo $hvlbackgroundColor; ?>;" disabled>
                  </div>
                </td>
              </tr>
            </tbody>
          </table>

        </div>
        <div class="table">
          <button type="button" class="btn btn-secondary">POST-CALIBRATION CHECKS REPORT </button>
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
                <th class="text-center" style="font-weight:500;"></th>

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

              <?php
              // Assuming the form is submitted via POST
              $hh_bsa3 = $calibration_info->hh_bsa3;
              $hh_bsa4 = $calibration_info->hh_bsa4;
              $hh_fsa3 = $calibration_info->hh_fsa3;
              $hh_fsa4 = $calibration_info->hh_fsa4;
              $hl_bsa2 = $calibration_info->hl_bsa2;
              $hl_fsa2 = $calibration_info->hl_fsa2;
              $vl_bsa2 = $calibration_info->vl_bsa2;
              $vl_fsa2 = $calibration_info->vl_fsa2;

              $ls_v_a_6 = $calibration_info->ls_v_a_6;



              $lsresult3 = $hh_bsa3 - $hh_bsa4;
              $lsresult4 = $hh_fsa3 - $hh_fsa4;
              $lsresult5 = $hl_bsa2 - $hl_fsa2;
              $lsresult6 = $vl_bsa2 - $vl_fsa2;

              $lsdistance2 = $lsresult3 - $lsresult4;

              // Determine the color based on the condition
              $hhbackgroundColor1 = ($lsdistance2 < $ls_v_a_6) ? 'lightgreen' : 'lightcoral'; // Green if Pass, Red if Fail
              // Determine the color based on the condition
              $hvlbackgroundColor1 = ($lsresult5 < $ls_v_a_6 && $lsresult6 < $ls_v_a_6) ? 'lightgreen' : 'lightcoral'; // Green if both conditions are true, Red if either is false

              ?>

              <tr>
                <td class="text-center" style="font-weight:500;">1</td>
                <td>
                  <div class="form-group form-group-bottom">
                    <input class="form-control required" value="<?php if (!empty($calibration_info)) {
                                                                  echo $calibration_info->hh_bsa3;
                                                                } ?>" name="hh_bsa3" type="text" disabled>
                  </div>
                </td>
                <td>
                  <div class="form-group form-group-bottom">
                    <input class="form-control required" value="<?php if (!empty($calibration_info)) {
                                                                  echo $calibration_info->hh_fsa3;
                                                                } ?>" name="hh_fsa3" type="text" disabled>
                  </div>
                </td>
                <td class="text-center" style="font-weight:500;">1</td>
                <td>
                  <div class="form-group form-group-bottom">
                    <input class="form-control required" value="<?php if (!empty($calibration_info)) {
                                                                  echo $calibration_info->hl_bsa2;
                                                                } ?>" name="hl_bsa2" type="text" disabled>
                  </div>
                </td>
                <td>
                  <div class="form-group form-group-bottom">
                    <input class="form-control required" value="<?php if (!empty($calibration_info)) {
                                                                  echo $calibration_info->hl_fsa2;
                                                                } ?>" name="hl_fsa2" type="text" disabled>
                  </div>
                </td>
              </tr>
              <tr>
                <td class="text-center" style="font-weight:500;">2</td>
                <td>
                  <div class="form-group form-group-bottom">
                    <input class="form-control required" value="<?php if (!empty($calibration_info)) {
                                                                  echo $calibration_info->hh_bsa4;
                                                                } ?>" name="hh_bsa4" type="text" disabled>
                  </div>
                </td>
                <td>
                  <div class="form-group form-group-bottom">
                    <input class="form-control required" value="<?php if (!empty($calibration_info)) {
                                                                  echo $calibration_info->hh_fsa4;
                                                                } ?>" name="hh_fsa4" type="text" disabled>
                  </div>
                </td>
                <th colspan="3" class="text-center" style="font-weight:500;">VERTICAL LEVEL (SIDE TO SIDE INCLINATION) ACCURACY ERROR<br> OBSERVATIONS </th>

              </tr>
              <tr>
                <td class="text-center" style="font-weight:500;">Difference (mm)</td>
                <td>
                  <div class="form-group form-group-bottom">
                    <input class="form-control required" value="<?php echo $lsresult3; ?>" name="lsresult1" type="text" disabled>
                  </div>
                </td>
                <td>
                  <div class="form-group form-group-bottom">
                    <input class="form-control required" value="<?php echo $lsresult4; ?>" name="hh_bsa2" type="text" disabled>
                  </div>
                </td>
                <td class="text-center" style="font-weight:500;">1</td>
                <td>
                  <div class="form-group form-group-bottom">
                    <input class="form-control required" value="<?php if (!empty($calibration_info)) {
                                                                  echo $calibration_info->vl_bsa2;
                                                                } ?>" name="vl_bsa2" type="text" disabled>
                  </div>
                </td>
                <td>
                  <div class="form-group form-group-bottom">
                    <input class="form-control required" value="<?php if (!empty($calibration_info)) {
                                                                  echo $calibration_info->vl_fsa2;
                                                                } ?>" name="vl_fsa2" type="text" disabled>
                  </div>
                </td>
              </tr>
              <tr style="background-color: ;">
                <td class="text-center" style="font-weight:500;">Distance (mm)</td>
                <td colspan="2">
                  <div class="form-group form-group-bottom">
                    <input class="form-control required text-center" value="<?php echo (isset($lsresult3) && isset($lsresult4)) ? ($lsresult3 - $lsresult4) : ''; ?>" name="distance" type="text" style="background-color: <?php echo $hhbackgroundColor1; ?>;" disabled>
                  </div>
                </td>
                <td class="text-center" style="font-weight:500;">Distance E (mm)</td>
                <td colspan="2">
                  <div class="form-group form-group-bottom">
                    <input class="form-control required text-center" value="<?php echo (isset($calibration_info->vl_bsa2) && isset($calibration_info->vl_fsa2)) ? ($calibration_info->vl_bsa2 - $calibration_info->vl_fsa2) : ''; ?>" name="distance" type="text" style="background-color: <?php echo $hvlbackgroundColor1; ?>;" disabled>
                  </div>
                </td>
              </tr>
            </tbody>
          </table>
        </div>
      <?php } ?>

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


      <div class="clearfix mtop30"></div>
      <hr>

      <div class="row">
        <div class="col-md-12">
          <label class="control-label" style="font-weight:500;">REPORT REMARKS</label>
          <p class="description"><?php if (!empty($calibration_info)) {
                                    echo $calibration_info->calibration_remark;
                                  } ?></p>
        </div>
      </div>
      </div>
  </div>
</div>
<script>
  $(function() {
    new Sticky('[data-sticky]');
    var $payNowTop = $('.pay-now-top');
    if ($payNowTop.length && !$('#pay_now').isInViewport()) {
      $payNowTop.removeClass('hide');
      $('.pay-now-top').on('click', function(e) {
        e.preventDefault();
        $('html,body').animate({
            scrollTop: $("#online_payment_form").offset().top
          },
          'slow');
      });
    }

    $('#online_payment_form').appFormValidator();

    var online_payments = $('.online-payment-radio');
    if (online_payments.length == 1) {
      online_payments.find('input').prop('checked', true);
    }
  });
</script>