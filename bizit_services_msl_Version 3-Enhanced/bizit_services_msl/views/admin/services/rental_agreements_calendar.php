<?php init_head(); ?>
<div id="wrapper">
  <div class="content">
    <div class="row">
      <div class="col-md-12">

        <div class="panel_s">
          <div class="panel-body _buttons">
            <a class="btn btn-default pull-right" href="<?php echo admin_url('services/rental_agreements'); ?>"><i class="fa fa-arrow-left"></i> Back</a>
          </div>
        </div>

          <div class="panel_s">
              <div class="panel-body mtop10">

<link href="<?php echo module_dir_url(BIZIT_SERVICES_MSL, 'assets/plugins/calendar/calender.css'); ?>" rel="stylesheet" type="text/css" />

<div id="calendar"></div>


              </div>
          </div>
      </div>
    </div>
  </div>
</div>
<?php init_tail(); ?>
<script src="<?php echo module_dir_url(BIZIT_SERVICES_MSL, 'assets/plugins/calendar/calender.min.js'); ?>" type="text/javascript" charset="utf-8"></script>
<script type="text/javascript">
$(function() {
    var currentYear = new Date().getFullYear();

    $('#calendar').calendar({ 
        enableContextMenu: true,
        enableRangeSelection: true,
        mouseOnDay: function(e) {
            if(e.events.length > 0) {
                var content = '';
                
                for(var i in e.events) {
                    content += '<div class="event-tooltip-content"><div class="bg_cal" style="background:' + e.events[i].color + '"></div>'
                                    + '<div class="event-name" style="color:' + e.events[i].color + '">' + e.events[i].name + '</div>'
                                    + '<div class="event-location">' + e.events[i].identifier + '</div>'
                                    + '<div class="event-location">' + e.events[i].product_serial_code + '</div>'
                                    + '<div class="event-location">' + e.events[i].client + '</div>'
                                    + '<div class="event-location" style="font-weight:bold; color:' + e.events[i].color + '">' + e.events[i].status_label + '</div>'
                                    + '</div>';
                }
            
                $(e.element).popover({ 
                    trigger: 'manual',
                    container: 'body',
                    html:true,
                    content: content
                });
                
                $(e.element).popover('show');
            }
        },
        mouseOutDay: function(e) {
            if(e.events.length > 0) {
                $(e.element).popover('hide');
            }
        },
        dayContextMenu: function(e) {
            $(e.element).popover('hide');
        },
        dataSource: [
        <?php 
        $count = 0;
        
        // 1. RENTAL AGREEMENTS
        if(isset($rental_details)){
            foreach ($rental_details as $rental) {
                $start = new DateTime($rental->start_date);
                $end = new DateTime(!empty($rental->actual_date_returned) ? $rental->actual_date_returned : $rental->end_date);
                
                // Color Logic: Maroon for Returned (2), Green for Active
                $color = ($rental->status == 2) ? 'maroon' : 'green';
                $status_label = ($rental->status == 2) ? 'Equipment Returned' : 'Equipment Hired';

                echo "{
                    id: 'rent_" . $count++ . "',
                    name: 'RENTAL: " . addslashes($rental->name) . "',
                    identifier: '<b>Rental #:</b> " . get_option('service_rental_agreement_prefix') . $rental->service_rental_agreement_code . "',
                    product_serial_code: '<b>Serial:</b> #" . $rental->rental_serial . "',
                    client: '<b>Client:</b> " . addslashes($rental->client_name) . "',
                    status_label: '" . $status_label . "',
                    color: '" . $color . "',
                    startDate: new Date(" . $start->format('Y, m, d') . "),
                    endDate: new Date(" . $end->format('Y, m, d') . "),
                },";
            }
        }

        // 2. SERVICE REQUESTS (Enhanced Feature)
        if(isset($service_request_details)){
            foreach ($service_request_details as $req) {
                $start = new DateTime($req->start_date);
                $end = new DateTime($req->end_date);
                
                // Color Logic: Blue for Active, Orange for Completed/Collected
                // Assuming status 2 = ready/complete
                $color = ($req->status == 2) ? 'orange' : '#1e90ff'; 
                $status_label = ($req->status == 2) ? 'Service Complete' : 'In Progress';

                echo "{
                    id: 'serv_" . $count++ . "',
                    name: 'SERVICE: " . addslashes($req->name) . "',
                    identifier: '<b>Request #:</b> " . get_option('service_request_prefix') . $req->service_request_code . "',
                    product_serial_code: '<b>Serial:</b> " . $req->serial_no . "',
                    client: '<b>Client:</b> " . addslashes($req->client_name) . "',
                    status_label: '" . $status_label . "',
                    color: '" . $color . "',
                    startDate: new Date(" . $start->format('Y, m, d') . "),
                    endDate: new Date(" . $end->format('Y, m, d') . "),
                },";
            }
        }
        ?>  
        ]
    });
});
</script>
</body>
</html>