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
                                    + '<div class="event-location">' + e.events[i].rental_no + '</div>'
                                    + '<div class="event-location">' + e.events[i].product_serial_code + '</div>'
                                    + '<div class="event-location">' + e.events[i].client + '</div>';

                if(e.events[i].status == 2){// confirmed
                    content += '<div class="event-location" style="color:maroon; font-weight:bold;">Equipment Returned</div>';
                }
                else{content += '<div class="event-location" style="color:green; font-weight:bold;">Equipment Hired</div>';}
                    content += '</div>';
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
       //style:'background',
        dataSource: [
        <?php 
        $count = 0;
         foreach ($rental_details as $rental_calender) {
            $start_date = new DateTime($rental_calender->start_date);
            $start_date_year = $start_date->format('Y');
            $start_date_month= $start_date->format('m');
            $start_date_day = $start_date->format('d');

            $end_date = new DateTime(!empty($rental_calender->actual_date_returned) ? $rental_calender->actual_date_returned : $rental_calender->end_date);
            $end_date_year = $end_date->format('Y');
            $end_date_month= $end_date->format('m');
            $end_date_day = $end_date->format('d');

        ?>
           {    id: <?php echo $count; ?>,
                name: '<?php echo $rental_calender->name; ?>',
                rental_no: '<b>Rental:</b> <?php echo get_option('service_rental_agreement_prefix').$rental_calender->service_rental_agreement_code; ?>',
                product_serial_code: '<b>Serial No:</b> #<?php echo $rental_calender->rental_serial; ?>',
                client: "<b>Client:</b> <?php echo get_company_name($rental_calender->clientid); ?>",
                status: '<?php echo $rental_calender->status; ?>',
                startDate: new Date(<?php echo $start_date_year; ?>, <?php echo $start_date_month; ?>, <?php echo $start_date_day; ?>),
                endDate: new Date(<?php echo $end_date_year; ?>, <?php echo $end_date_month; ?>, <?php echo $end_date_day; ?>),
            },  
        <?php
        $count++;
         }
        ?>  
        ]
    });
    
});

</script>
</body>
</html>
