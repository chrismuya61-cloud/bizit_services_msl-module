<?php defined('BASEPATH') or exit('No direct script access allowed'); ?>
<?php init_head(); ?>
<div id="wrapper">
    <div class="content">
        <div class="row">
            <div class="col-md-12">
                <div class="panel_s">
                    <div class="panel-body">
                        <h4 class="no-margin"><?php echo $title; ?></h4>
                        <hr class="hr-panel-heading" />
                        
                        <div class="clearfix"></div>
                        
                        <?php render_datatable(array(
                            'Service / Item',
                            'Code',
                            'Category',
                            'Price',
                            'Date Created'
                        ),'services_sales_list'); ?>
                        
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<?php init_tail(); ?>
<script>
    $(function(){
        initDataTable('.table-services_sales_list', window.location.href, [], [], 'undefined', [4, 'DESC']);
    });
</script>
</body>
</html>