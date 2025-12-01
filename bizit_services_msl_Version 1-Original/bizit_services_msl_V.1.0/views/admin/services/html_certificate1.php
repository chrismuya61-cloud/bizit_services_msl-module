<html>

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
      background-image: url(<?php echo module_dir_url(BIZIT_SERVICES_MSL, 'assets/images/certificates/cert_bg.png'); ?>);
      background-position: center center;
      background-repeat: no-repeat;
      position: absolute;
      opacity: 0.2;
      z-index: -1000;
      width: 100%;
      height: 100%;
    }

    .frame {
      width: 50%;
      background-image: url(<?php echo module_dir_url(BIZIT_SERVICES_MSL, 'assets/images/certificates/cert_frame.png'); ?>);
      background-size: contain;
      background-repeat: no-repeat;
      height: 750px;
    }

    .cert_content {
      _background-color: #f30;
      padding: 50px 50px;
    }

    /*Old css*/
    h1 {
      text-align: center;
      font-size: 24px;
      font-family: georgia, sans-serif;
    }

    p {
      width: 300px;
      margin: 10px auto;
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

    .seal {
      width: 150px;
      height: 150px;
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
  <div class="bgg"></div>
  <table width="100%">
    <tr>
      <?php for ($i = 0; $i <= 1; $i++) { ?>
        <td class="frame">
          <div class="cert_content">
            <h1>CERTIFICATE <i>of</i> CALIBRATION</h1>
            <p style="color:#555">
              <b>THIS CERTIFIES THAT</b>
            </p>

            <p class="system_info">
              MAKE: <?php echo "" . $service_request->item_make; ?><br><br>
              MODEL: <?php echo "" . $service_request->item_model; ?><br><br>
              SERIAL NO.: <?php echo "" . $service_request->serial_no; ?>
            </p>

            <p>
              <b style="color:#555">HAS BEEN SUCESSFULLY CALIBRATED</b>
            </p>
            <p>WE HEREBY CONFIRM THAT THE ABOVE EQUIPMENT HAS BEEN TESTED AND CALIBRATED AND CONFORMS TO MANUFACTURER'S SPECIFICATIONS. THE TEST EQUIPMENT USED WAS CALIBRATED TO THE APPROPRIATE STANDARDS WITH TRACEABILITY TO KEBS OR NATIONAL STANDARDS.</p>

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
                  <div id="sign">
                    <table border="0">
                      <tbody>
                        <tr>
                          <td class="text-center" style="padding-top:40px;"><b>......................</b></td>
                          <td class="text-center" style="padding-top:40px;"><b>......................</b></td>
                        </tr>
                        <tr>
                          <td class="text-center" style="font-size:10px;"><b><i>General Manager</i></b></td>
                          <td class="text-center" style="font-size:10px;"><b><i>Service Engineer</i></b></td>
                        </tr>
                        <tr>
                          <td colspan="2" style="font-size:10px; text-align:right; padding-top:20px;"><b>Valid Till:</b> <?php echo _d(date('Y-m-d', strtotime('+1 year', $date))); ?></td>
                        </tr>
                        <tr>
                          <td colspan="2"><?php echo pdf_logo_url(); ?></td>
                        </tr>
                      </tbody>
                    </table>
                  </div>
                </td>
              </tr>
            </table>
          </div>
        </td>
      <?php } ?>
    </tr>
  </table>
</body>

</html>