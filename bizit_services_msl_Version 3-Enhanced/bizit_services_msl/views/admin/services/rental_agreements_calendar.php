<?php init_head(); ?>
<div id="wrapper"><div class="content"><div class="row"><div class="col-md-12">
<div class="panel_s"><div class="panel-body _buttons"><a class="btn btn-default pull-right" href="<?php echo admin_url('services/rental_agreements'); ?>"><i class="fa fa-arrow-left"></i> Back</a></div></div>
<div class="panel_s"><div class="panel-body mtop10"><div id="calendar"></div></div></div></div></div></div></div>
<?php init_tail(); ?>
<script src="<?php echo module_dir_url(BIZIT_SERVICES_MSL, 'assets/plugins/calendar/calender.min.js'); ?>"></script>
<script>
$(function() {
    $('#calendar').calendar({ 
        enableContextMenu: true, enableRangeSelection: true,
        mouseOnDay: function(e) {
            if(e.events.length > 0) {
                var content = '';
                for(var i in e.events) {
                    content += '<div class="event-tooltip-content"><div class="bg_cal" style="background:' + e.events[i].color + '"></div><div class="event-name" style="color:' + e.events[i].color + '">' + e.events[i].name + '</div><div class="event-location">' + e.events[i].identifier + '</div></div>';
                }
                $(e.element).popover({ trigger: 'manual', container: 'body', html:true, content: content }).popover('show');
            }
        },
        mouseOutDay: function(e) { $(e.element).popover('hide'); },
        dataSource: [
        <?php 
        if(isset($rental_details)){ foreach ($rental_details as $r) {
            $s = new DateTime($r->start_date); $e = new DateTime(!empty($r->actual_date_returned) ? $r->actual_date_returned : $r->end_date);
            $c = ($r->status == 2) ? 'maroon' : 'green';
            echo "{id: 'r_".$r->service_rental_agreement_id."', name: 'RENTAL: ".addslashes($r->name)."', identifier: '".$r->service_rental_agreement_code."', color: '$c', startDate: new Date(".$s->format('Y, m, d')."), endDate: new Date(".$e->format('Y, m, d').")},";
        }}
        if(isset($service_request_details)){ foreach ($service_request_details as $r) {
            $s = new DateTime($r->start_date); $e = new DateTime($r->end_date);
            $c = ($r->status == 2) ? 'orange' : '#1e90ff';
            echo "{id: 's_".$r->service_request_id."', name: 'SERVICE: ".addslashes($r->name)."', identifier: '".$r->service_request_code."', color: '$c', startDate: new Date(".$s->format('Y, m, d')."), endDate: new Date(".$e->format('Y, m, d').")},";
        }}
        ?>  
        ]
    });
});
</script>
