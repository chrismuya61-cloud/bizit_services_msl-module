<?php defined('BASEPATH') or exit('No direct script access allowed'); ?>
<?php init_head(); ?>
<div id="wrapper">
    <div class="content">
        <div class="row">
            <div class="col-md-12">
                <div class="panel_s">
                    <div class="panel-body">
                        
                        <div class="_buttons">
                            <a href="#" class="btn btn-default pull-left display-block mright5" onclick="window.history.back(); return false;">
                                <i class="fa fa-arrow-left"></i> Back
                            </a>
                            <h4 class="no-margin pull-left" style="padding-top:6px; padding-left:10px;">
                                <?php echo $title; ?>
                            </h4>
                            <div class="clearfix"></div>
                        </div>
                        
                        <hr class="hr-panel-heading" />
                        
                        <div class="clearfix"></div>
                        <?php render_datatable(array(
                            _l('field_report_code'),
                            _l('site_name'),
                            _l('status'),
                            _l('approved_by'),
                            _l('field_report_dateadded'),
                            _l('options')
                        ), 'services_field_report'); ?>
                        
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<?php init_tail(); ?>
<script>
    $(function(){
        initDataTable('.table-services_field_report', window.location.href, [5], [5], 'undefined', [0, 'DESC']);
    });
</script>
</body>
</html>