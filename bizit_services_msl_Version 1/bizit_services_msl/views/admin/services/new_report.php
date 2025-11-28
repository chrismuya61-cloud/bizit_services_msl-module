<?php init_head(); ?>
<div id="wrapper">
  <div class="content">
    <div class="row">
      <div class="col-md-12">
        <?php if (!empty($calibration_info)) { ?>
          <div class="panel_s">
            <div class="panel-body _buttons">
              <a href="<?php echo admin_url('services/report/view/' . $service_request_code); ?>" class="btn btn-info "><i class="fa fa-eye"></i> View Report</a>
            </div>
          <?php } ?>
          <div class="panel_s">
            <div class="panel-body mtop10">
              <?php if ($service_info->item_type == 'Level') {
                $this->load->view('admin/services/report_summary_level');
              }
              ?>
              <?php if ($service_info->item_type == 'GNSS') {
                $this->load->view('admin/services/report_summary_gps');
              }
              ?>
              <?php if ($service_info->item_type == 'Total Station') {
                $this->load->view('admin/services/report_summary_ts');
              }
              ?>
              <?php if ($service_info->item_type == 'Theodolite') {
                $this->load->view('admin/services/report_summary_th');
              }
              ?>
              <?php if ($service_info->item_type == 'lasers') {
                $this->load->view('admin/services/report_summary_lasers');
              }
              ?>
            </div>
          </div>
          </div>
      </div>
    </div>
    <div class="btn-bottom-pusher"></div>
  </div>
</div>
<?php init_tail(); ?>
<script>
  $(function() {
    $("#service_calibration_report_form").validate();
    $('#service_calibration_report_form').on('change input', 'input, textarea, select', function() {
      var form = $('#service_calibration_report_form');
      var formData = form.serialize();
      $.ajax({
        url: form.attr('action'),
        method: form.attr('method') || 'POST',
        data: formData + '&autosave=1',
        success: function(response) {
            if (!$('.float-alert').length) {
              alert_float('success', 'Autosaved Successfully');
            }
        }
      });
    });
  });
</script>
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
</body>

</html>