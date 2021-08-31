<?php $this->load->view('include/header'); ?>
<div class="row">
    <div class="col-sm-6 col-xs-6 padding-5">
        <h4 class="title"><?php echo $this->title; ?></h4>
    </div>
    <div class="col-sm-6 col-xs-6 padding-5">
        <p class="pull-right top-p">
            <?php if($this->isAdmin) : ?>
            <button type="button" class="btn btn-sm btn-success" onclick="goAdd()"><i class="fa fa-plus"></i> เพิ่มใหม่</button>
            <?php endif; ?>
        </p>
    </div>
</div><!-- End Row -->
<hr class="padding-5" />
<form id="searchForm" method="post" action="<?php echo current_url(); ?>">
    <div class="row">
        <div class="col-sm-2 col-xs-6 padding-5">
            <label>สินค้า</label>
            <input type="text" class="form-control input-sm text-center search-box" name="name"
                value="<?php echo $name; ?>" />
        </div>
        <div class="col-sm-2 col-xs-6 padding-5">
            <label>บาร์โค้ด</label>
            <input type="text" class="form-control input-sm text-center search-box" name="barcode"
                value="<?php echo $barcode; ?>" />
        </div>
        <div class="col-sm-2 col-xs-6 padding-5">
            <label>กลุ่มสินค้า</label>
            <select class="form-control input-sm" name="item_group" onchange="getSearch()">
              <option value="all">ทั้งหมด</option>
              <?php echo select_item_group($item_group); ?>
            </select>
        </div>

        <div class="col-sm-1 col-1-harf col-xs-6 padding-5">
          <label>สถานะ</label>
          <select class="form-control input-sm" name="status" onchange="getSearch()">
            <option value="all">ทั้งหมด</option>
            <option value="1" <?php echo is_selected('1', $status); ?>>Active</option>
            <option value="0" <?php echo is_selected('0', $status); ?>>Inactive</option>
          </select>
        </div>

        <div class="col-sm-1 col-xs-6 padding-5">
            <label class="display-block not-show">buton</label>
            <button type="submit" class="btn btn-xs btn-primary btn-block"><i class="fa fa-search"></i> Search</button>
        </div>
        <div class="col-sm-1 col-xs-6 padding-5">
            <label class="display-block not-show">buton</label>
            <button type="button" class="btn btn-xs btn-warning btn-block" onclick="clearFilter()"><i
                    class="fa fa-retweet"></i> Reset</button>
        </div>
    </div>
    <hr class="margin-top-15 padding-5">
</form>
<?php echo $this->pagination->create_links(); ?>

<div class="row">
    <div class="col-sm-12 col-xs-12 padding-5 table-responsive">
        <table class="table table-striped table-hover table-bordered dataTable">
            <thead>
                <tr>
                   <th class="width-5 text-center">#</th>
                   <th class="width-10">บาร์โค้ด</th>
                   <th class="">สินค้า</th>
                   <th class="width-15 text-center">หน่วยนับ</th>
                   <th class="width-10 text-center">ราคา</th>
                   <th class="width-15 text-center">กลุ่มสินค้า</th>
                   <th class="width-5 text-center">สถานะ</th>
                   <th class="width-10 text-center">การกระทำ</th>
                </tr>
            </thead>
            <tbody>
                <?php if(!empty($data)) : ?>
                <?php $no = $this->uri->segment(3) + 1; ?>
                <?php foreach($data as $rs) : ?>
                <tr>
                    <td class="middle text-center no"><?php echo $no; ?></td>
                    <td class="middle"><?php echo $rs->barcode; ?></td>
                    <td class="middle"><?php echo $rs->name; ?></td>
                    <td class="middle text-center"><?php echo uom_item_label($rs->id, $rs->uom_id, $rs->main_uom_id); ?></td>
                    <td class="middle text-center"><?php echo number($rs->price, 2); ?></td>
                    <td class="middle text-center"><?php echo $rs->group_name; ?></td>
                    <td class="middle text-center"><?php echo is_active($rs->status); ?></td>
                    <td class="text-right">
                        <?php if($this->isAdmin) : ?>
                          <button type="button" class="btn btn-minier btn-info" onclick="viewDetail(<?php echo $rs->id; ?>)">
                            <i class="fa fa-eye"></i>
                          </button>
                        <button type="button" class="btn btn-minier btn-warning" onclick="goEdit(<?php echo $rs->id; ?>)">
                            <i class="fa fa-pencil"></i>
                        </button>
                        <button type="button" class="btn btn-minier btn-danger"
                            onclick="getDelete(<?php echo $rs->id; ?>, '<?php echo $rs->name; ?>')">
                            <i class="fa fa-trash"></i>
                        </button>
                        <?php endif; ?>
                    </td>
                </tr>
                <?php $no++; ?>
                <?php endforeach; ?>
                <?php endif; ?>
            </tbody>
        </table>
    </div>
</div>


<div class="modal fade" id="detailModal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
    <div class="modal-dialog" style="max-width:400px;">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
            </div>
            <div class="modal-body" id="detailBody">

            </div>
        </div>
    </div>
</div>

<script id="detailTemplate" type="text/x-handlebarsTemplate">
  <div class="row">
    <div class="col-sm-12">
      <table class="table table-bordered">
        <tr><td class="width-30">ชื่อสินค้า</td><td class="width-70">{{name}}</td></tr>
        <tr><td class="width-30">บาร์โค้ด</td><td class="width-70">{{barcode}}</td></tr>
        <tr><td class="width-30">ราคา</td><td class="width-70">{{price}}</td></tr>
        <tr><td class="width-30">กลุ่มสินค้า</td><td class="width-70">{{group_name}}</td></tr>
        <tr><td class="width-30">หน่วยนับ</td><td class="width-70">{{uom_name}}</td></tr>
        <tr><td class="width-30">หน่วยหลัก</td><td class="width-70">{{main_uom_name}}</td></tr>
        <tr><td class="width-30">อัตรส่วน</td><td class="width-70">1 {{main_uom_name}} = {{rate}} {{uom_name}}</td></tr>
        <tr><td class="width-30">สถานะ</td><td class="width-70">{{status}}</td></tr>
      </table>
    </div>
  </div>
</script>

<script src="<?php echo base_url(); ?>scripts/masters/item.js"></script>

<?php $this->load->view('include/footer'); ?>
