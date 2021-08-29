<?php $this->load->view('include/header'); ?>
<div class="row">
    <div class="col-sm-8 col-xs-8 padding-5">
        <h4 class="title"><?php echo $this->title; ?></h4>
    </div>
    <div class="col-sm-4 col-xs-4 padding-5">
      <p class="pull-right top-p">
        <button type="button" class="btn btn-xs btn-warning" onclick="goBack()"><i class="fa fa-arrow-left"></i> Back</button>
      </p>
    </div>
</div><!-- End Row -->
<hr class="padding-5 margin-bottom-30" />

<form class="form-horizontal">
    <div class="form-group">
        <label class="col-sm-3 col-xs-12 control-label no-padding-right">ชื่อสินค้า</label>
        <div class="col-xs-12 col-sm-4">
            <input type="text" name="name" id="name"
            class="width-100" maxlength="250" value="<?php echo $name; ?>"
            placeholder="Item name"
            autofocus  required />
        </div>

    </div>


    <div class="form-group">
        <label class="col-sm-3 col-xs-12 control-label no-padding-right">บาร์โค้ด</label>
        <div class="col-xs-12 col-sm-2">
          <input type="text" class="form-control input-sm" id="barcode" name="barcode" value="<?php echo $barcode; ?>" placeholder="Barcode"/>
        </div>
    </div>

    <div class="form-group">
        <label class="col-sm-3 col-xs-12 control-label no-padding-right">ราคา</label>
        <div class="col-xs-12 col-sm-2">
            <input type="number" name="price" id="price" class="form-control input-sm " value="<?php echo $price; ?>" />
        </div>
    </div>


    <div class="form-group">
        <label class="col-sm-3 col-xs-12 control-label no-padding-right">กลุ่มสินค้า</label>
        <div class="col-xs-10 col-sm-2">
            <select class="form-control input-sm" name="item_group_id" id="item_group_id">
              <option value="" disabled>กรุณาเลือก</option>
              <?php echo select_item_group($item_group_id); ?>
            </select>
        </div>
        <div class="col-xs-2 col-sm-1">
          <button type="button" class="btn btn-xs btn-primary btn-block" onclick="newItemGroup()"><i class="fa fa-plus"></i></button>
        </div>
    </div>

    <div class="form-group">
        <label class="col-sm-3 col-xs-12 control-label no-padding-right">หน่วยนับ</label>
        <div class="col-xs-10 col-sm-2">
            <select class="form-control input-sm" name="uom_id" id="uom_id" onchange="changeLabel()">
              <option value="" disabled>กรุณาเลือก</option>
              <?php echo select_uom($uom_id); ?>
            </select>
        </div>
        <div class="col-xs-2 col-sm-1">
          <button type="button" class="btn btn-xs btn-primary btn-block" onclick="newUom()"><i class="fa fa-plus"></i></button>
        </div>
    </div>

    <div class="form-group">
        <label class="col-sm-3 col-xs-12 control-label no-padding-right">หน่วยหลัก</label>
        <div class="col-xs-12 col-sm-2 margin-bottom-10">
            <select class="form-control input-sm" name="main_uom_id" id="main_uom_id" onchange="changeLabel()">
              <option value="" disabled>กรุณาเลือก</option>
              <?php echo select_uom($main_uom_id); ?>
            </select>
        </div>
        <div class="col-xs-12 col-sm-4">
          <span><span id="main_uom_label"> 1 <?php echo $main_uom_name; ?> = </span>
            <input type="number" class="form-control input-sm input-small inline text-center" name="rate" id="rate" value="<?php echo $rate; ?>" placeholder="ตัวคูณ"/>
            <span id="sku_label" style="padding-left:10px;"> <?php echo $uom_name; ?></span>
          </span>
        </div>

    </div>

    <div class="form-group">
        <label class="col-sm-3 col-xs-12 control-label no-padding-right">สถานะ</label>
        <div class="col-xs-12 col-sm-2 col-md-2 col-lg-1">
          <select class="form-control input-sm" id="status" name="status">
            <option value="1" <?php echo is_selected('1', $status); ?>>Active</option>
            <option value="0" <?php echo is_selected('0', $status); ?>>Disactive</option>
          </select>
        </div>
    </div>
    <div class="divider-hidden" id="divider"> </div>
    <div class="divider-hidden" id="divider"> </div>
    <div class="divider-hidden" id="divider"> </div>

    <div class="form-group">
        <label class="col-sm-3 col-xs-12 control-label no-padding-right"></label>
        <div class="col-xs-12 col-sm-2">
            <button type="button" class="btn btn-sm btn-success btn-block" onclick="update()"><i class="fa fa-save"></i> Update</button>
        </div>
    </div>

    <input type="hidden" id="id" value="<?php echo $id; ?>">
</form>


<div class="modal fade" id="itemGroupModal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
    <div class="modal-dialog" style="max-width:300px;">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
                <h4 class="modal-title-site text-center" id="title">เพิ่มกลุ่มสินค้า</h4>
            </div>
            <div class="modal-body">
            <div class="row">
							<div class="col-sm-12 col-xs-12">
								<label>ชื่อกลุ่มสินค้า</label>
								<input type="text" class="form-control input-sm" id="itemGroupName" maxlength="100" value=""  />
							</div>
            </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-sm btn-success" onClick="addNewItemGroup()" ><i class="fa fa-save"></i> เพิ่ม</button>
            </div>
        </div>
    </div>
</div>


<div class="modal fade" id="uomModal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
    <div class="modal-dialog" style="max-width:300px;">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
                <h4 class="modal-title-site text-center" id="title">เพิ่มหน่วยนับ</h4>
            </div>
            <div class="modal-body">
            <div class="row">
							<div class="col-sm-12 col-xs-12">
								<label>หน่วยนับ</label>
								<input type="text" class="form-control input-sm" id="uomName" maxlength="100" value=""  />
							</div>
            </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-sm btn-success" onClick="addNewUom()" ><i class="fa fa-save"></i> เพิ่ม</button>
            </div>
        </div>
    </div>
</div>

<script id="tag-template" type="text/x-handlebarsTemplate">
  <label  style="padding:10px; background-color:lightgreen;" id="tag-{{no}}">
    {{name}}  ({{rate}})
    <a class="pointer bold" onclick="removeTag({{no}})" style="margin-left:15px;">
      <i class="fa fa-trash red"></i>
    </a>
  </label>
  <input type="hidden" class="uom-item" name="uom_item" id="uom-item-{{no}}" value="{{id}}" data-rate="{{rate}}">
</script>

<script src="<?php echo base_url(); ?>scripts/masters/item.js?v=<?php echo date('Ymd'); ?>"></script>
<?php $this->load->view('include/footer'); ?>
