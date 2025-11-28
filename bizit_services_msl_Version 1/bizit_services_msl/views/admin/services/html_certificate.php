<html>
<meta name="referrer" content="no-referrer" />
<head>
  <link href='http://fonts.googleapis.com/css?family=Denk+One' rel='stylesheet' type='text/css'>
  <style>
     @page {
      margin: 0in;
    }

    body {
      font-family: Arial, sans-serif;
      padding: 10px 10px 10px 10px;
      width: 100%;
      height: 100%;
      position: relative;
    }

    .bgg {
      position: absolute;
      opacity: 0.2;
      z-index: -1;
      width: 40%;
      height: 100%;
      background-image: url(<?php echo module_dir_url(BIZIT_SERVICES_MSL, 'assets/images/certificates/logo_bg.jpg'); ?>);
      background-position: center;
      background-repeat: no-repeat;
      background-size: contain;
    }

    #column1 {
      left: 4%; /* Adjust the left position of the first column */
    }

    #column2 {
      right: 6.5%; /* Adjust the right position of the second column */
    }

  .frame {
        width: 100%;
        background-image: url(<?php echo module_dir_url(BIZIT_SERVICES_MSL, 'assets/images/certificates/cert_frame.png'); ?>);
        background-size: contain;
        background-repeat: no-repeat;
        height: 750px;
        min-height:750px;
      }

    .cert_content {
      _background-color: #f30;
      padding: 5px 15px;
    }

    /*Old css*/
    h1 {
      text-align: center;
      font-size: 28px;
      font-family: georgia, sans-serif;
    }

    p {
      width: 420px;
      margin: 3px auto;
      text-align: center;
      font-size: 10px;
    }

    .signature {
      margin-top: 4em;
      text-align: right;
    }

    .system_info {
      border-top: #555 solid 2px;
      border-bottom: #555 solid 2px;
      padding: 20px 0;
    }
    .logoms {
      width: 200px;
      height: 2px;
      background-image: url(<?php echo module_dir_url(BIZIT_SERVICES_MSL, 'assets/images/certificates/logoms.png'); ?>);
      background-repeat: no-repeat;
      background-position: center;
      background-size: contain;
      padding-left: 60%;
     

    }

    .seal {
      width: 100px;
      height: 100px;
      background-image: url(<?php echo module_dir_url(BIZIT_SERVICES_MSL, 'assets/images/certificates/seal.png'); ?>);
      background-repeat: no-repeat;
      background-position: center;
      background-size: contain;
    }

    .seal h5 {
      font-size: 26px;
      text-align: center;
      font-family: georgia, sans-serif;
      margin: 10px 0 5px 0;
    }

    .seal h3 {
      font-size: 38px;
      text-align: center;
      font-family: georgia, sans-serif;
      margin: 0;
    }

    
    .table {
      border-collapse: collapse !important;
    }

    .table-bordered thead>tr {
      background-color: #dedede !important;
      font-weight: bold;
    }

    .table-bordered th,
    .table-bordered td {
      border: 1px solid #ccc !important;
    }

    .table-bordered tr .no-border {
      border: none !important;
    }

    .table {
      width: 100%;
      max-width: 100%;
      margin-bottom: 20px;
    }

    .table>thead>tr>th,
    .table>thead>tr>td,
    .table>tbody>tr>th,
    .table>tbody>tr>td,
    .table>tfoot>tr>th,
    .table>tfoot>tr>td {
      padding: 5px;
      vertical-align: middle;
      font-size: 10px;
    }

    .text-center {
      text-align: center;
    }

    /*old css*/
  </style>

</head>

<body>
  <!-- <div class="bgg"></div> -->
  <div class="bgg" id="column1"></div>
  <div class="bgg" id="column2"></div>
  <table width="100%">
    <tr>
      <?php for ($i = 0; $i <= 1; $i++) { ?>
        <td class="frame">
          <div class="cert_content">
            <h1>CERTIFICATE <i>of</i> CALIBRATION</h1>
            <p style="color:#555" style="font-size:14px;">
              <b><emp>THIS CERTIFIES THAT</emp></b>
            </p>

            <p class="system_info"><b>
              MAKE: <?php echo "" . $service_request->item_make; ?><br>
              MODEL: <?php echo "" . $service_request->item_model; ?><br>
              SERIAL NO.: <?php echo "" . $service_request->serial_no; ?>
           </b></p>

            <p>
              <b style="color:#777">HAS BEEN SUCESSFULLY CALIBRATED</b><br>
            </p>
            <p style="font-size:14px;"><b>WE HEREBY CONFIRM THAT THE ABOVE EQUIPMENT HAS BEEN TESTED, CALIBRATED AND CONFORMS TO MANUFACTURER'S SPECIFICATIONS. THE EQUIPMENT WAS CALIBRATED TO THE APPROPRIATE STANDARDS WITH TRACEABILITY TO ISO 17123-8:2015 (E).</b></p>
            </p>
            <table border="0">
              <tr>
                <td class="seal">
                    <?php
                    $date = strtotime($service_request->collection_date);
                    ?>
                    <h5><?php echo date("M", $date) . ', ' . date("d", $date); ?></h5>
                    <h3><?php echo date("Y", $date); ?></h3>
                </td>
                <td>
                  <div id="signature">
                    <table border="0" style="">
                      <tbody>
                        <tr>
                          <td class="text-center" style="padding-top:40px;"><b>x_______________</b></td>
                          <td class="text-center" style="padding-top:40px;"><b>_______________</b></td>
                        </tr>
                        <tr>
                          <td class="text-center" style="font-size:11px;"><b><i>SIGNED, General Manager</i></b></td>
                          <td class="text-center" style="font-size:11px;"><b><i>Service Engineer</i></b></td>
                        </tr>
                        <tr>
                          <td colspan="2" style="font-size:12px; text-align:right; padding-top:20px;"><b>CERTIFICATE VALIDITY:<?php echo _d(date('Y-m-d', strtotime('+1 year', $date))); ?> <br> CERTIFICATE REFERENCE:<?php echo get_option('service_request_prefix') . $service_request->service_request_code; ?></b> </td>
                        </tr>
                       
                      </tbody>
                    </table>
                  </div>
                </td>
              </tr>
              <tr style="padding-left: 40%;">
                <td class="logoms" style=""></td>
                <td style="text-align: right; padding-right: 2%;">
                  <img src="data:image/png;base64,<?php echo $qr_code_base64; ?>" alt="QR Code">
              </td>
             </tr>
             <tr></tr>
            </table>
          </div>
        </td>
      <?php } ?>
    </tr>
  </table>

</body>

</html>