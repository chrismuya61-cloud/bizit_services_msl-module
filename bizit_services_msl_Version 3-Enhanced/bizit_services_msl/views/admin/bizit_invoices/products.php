<div class="tab-pane fade active in col-md-12 gen_add_product" id="products_selector">
  <ul class="nav nav-tabs" style="border-top:none;">
    <style>
      @media (max-width: 768px) {
        .nav-tabs>li {
          width: auto;
        }
      }
    </style>

    <li class="active "><a data-toggle="tab" href="#search-product" aria-expanded="true"><i class="fa fa-search"></i> Search Product</a></li>
    <li class="hide"><a data-toggle="tab" href="#scan-barcode" id="scan-barcode-btn" aria-expanded="false"><i class="fa fa-barcode"></i> Scan Barcode</a></li>

  </ul>
  <div class="tab-content">

    <div id="search-product" class="tab-pane fade active in">
      <div class="col-md-4">
        <div class="form-group mbot25">
          <div class="items-select-wrapper">
            <select name="item_select" class="selectpicker no-margin<?php if ($ajaxItems == true) {
                                                                      echo ' ajax-search';
                                                                    } ?><?php if (has_permission('items', '', 'create')) {
                                                                                                                            echo ' _select_input_group';
                                                                                                                          } ?>" data-width="false" id="item_select" data-none-selected-text="<?php echo _l('add_product'); ?>" data-live-search="true">
              <option value=""></option>
              <?php foreach ($items as $group_id => $_items) { ?>
                <optgroup data-group-id="<?php echo $group_id; ?>" label="<?php echo $_items[0]['group_name']; ?>">
                  <?php foreach ($_items as $item) { ?>
                    <option value="<?php echo $item['id']; ?>" data-subtext="<?php echo strip_tags(mb_substr($item['long_description'], 0, 200)) . '...'; ?>">(<?php echo app_format_number($item['rate']);; ?>) <?php echo $item['description']; ?></option>
                  <?php } ?>
                </optgroup>
              <?php } ?>
            </select>
          </div>
          <small class="text-info">Search by Product Name</small>
        </div>
      </div>
      <div class="col-md-4">
        <div class="form-group mbot25">
          <select id="product_serials" class="no-margin hide" data-width="100%" data-none-selected-text="<?php echo _l('get_product_serial'); ?>" data-live-search="true">
            <option value=""></option>
          </select>
        </div>
      </div>
    </div>

    <div id="scan-barcode" class="tab-pane fade">
      <div class="row">

        <div class="col-md-5">
          <div class="form-group col-md-12">
            <div class="input-group">
              <span class="input-group-addon"><i class="fa fa-barcode"></i> BARCODE</span> <!-- autofocus : can be added -->
              <input type="text" class="form-control" placeholder="Scan Product Barcode" onkeydown="return false" data-sale="invoice">
              <script type="text/javascript">
                var start_typing = false;
              </script>
              <a href="javascript:;" onclick="(function(){
                    if(start_typing == false){
                        $('#scan-barcode input').attr({'onkeydown': 'return true', 'placeholder': 'Start typing Serial No.'}).focus().attr('name', 'barcodeType');
                        $('#start_typing').css({'background-color': '#fc2d42', 'color' : 'white', 'border-color' : '#fc2d42'}).attr({'data-original-title' : 'Switch to scanning mode'});
                        start_typing = true;
                      }
                    else{
                        $('#scan-barcode input').attr({'onkeydown': 'return false', 'placeholder': 'Scan Product Barcode'}).focus();//.attr('name', 'barcode');
                        $('#start_typing').css({'background-color': '#0492d2', 'color' : 'white', 'border-color' : '#0492d2'}).attr({'data-original-title' : 'Switch to typing mode'});
                        start_typing = false;
                    }
                        return false;
                    })();return false;" id="start_typing" class="input-group-addon btn" style="background-color: #0492d2; color: white; border-color: #0492d2;" data-toggle="tooltip" title="Switch to typing mode"><i class="fa fa-keyboard-o"></i></a>
            </div>
          </div>
          <!-- /input-group -->
        </div>
        <!-- /.col-lg-6 -->

      </div>
    </div>

  </div>
</div>