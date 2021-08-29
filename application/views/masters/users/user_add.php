<?php $this->load->view('include/header'); ?>
<div class="row">
    <div class="col-sm-6 col-xs-6 padding-5">
        <h3 class="title"><i class="fa fa-user"></i> <?php echo $this->title; ?></h3>
    </div>
    <div class="col-sm-6 col-xs-6 padding-5">
        <p class="pull-right top-p">
            <button type="button" class="btn btn-sm btn-warning" onclick="goBack()"><i class="fa fa-arrow-left"></i>
                Back</button>
        </p>
    </div>
</div><!-- End Row -->
<hr class="padding-5 margin-bottom-30" />

<form class="form-horizontal">
    <div class="form-group">
        <label class="col-sm-3 control-label no-padding-right">ชื่อผู้ใช้งาน</label>
        <div class="col-xs-12 col-sm-4">
            <input type="text" name="uname" id="uname" class="width-100" value="" onkeyup="validCode(this)" autofocus
                required />
        </div>
        <div class="help-block col-xs-12 col-sm-reset inline red" id="uname-error"></div>
    </div>

    <div class="form-group">
        <label class="col-sm-3 control-label no-padding-right">ชื่อพนักงาน</label>
        <div class="col-xs-12 col-sm-4">
            <input type="text" name="emp" id="emp" class="width-100" value="" required />
        </div>
        <div class="help-block col-xs-12 col-sm-reset inline red" id="emp-error"></div>
    </div>

    <div class="form-group">
        <label class="col-sm-3 control-label no-padding-right">รหัสผ่าน</label>
        <div class="col-xs-12 col-sm-4">
            <input type="password" name="pwd" id="pwd" class="width-100" value="" required />
        </div>
        <div class="help-block col-xs-12 col-sm-reset inline red" id="pwd-error"></div>
    </div>

    <div class="form-group">
        <label class="col-sm-3 control-label no-padding-right">ยืนยันรหัสผ่าน</label>
        <div class="col-xs-12 col-sm-4">
            <input type="password" name="cfpwd" id="cfpwd" class="width-100" value="" required />
        </div>
        <div class="help-block col-xs-12 col-sm-reset inline red" id="cfpwd-error"></div>
    </div>

    <div class="form-group">
        <label class="col-sm-3 control-label no-padding-right">กลุ่มผู้ใช้</label>
        <div class="col-xs-12 col-sm-4">
            <select class="form-control input-sm" name="ugroup" id="ugroup">
                <option value="user">User</option>
                <option value="admin">Admin</option>
            </select>
        </div>
        <div class="help-block col-xs-12 col-sm-reset inline red" id="ugroup-error"></div>
    </div>


    <div class="form-group">
        <label class="col-sm-3 control-label no-padding-right"></label>
        <div class="col-xs-12 col-sm-4">
            <label>
                <input type="checkbox" class="ace" name="status" id="status" value="1" checked />
                <span class="lbl">&nbsp; &nbsp;Active</span>
            </label>
        </div>
    </div>

    <div class="divider-hidden">

    </div>
    <div class="form-group">
        <label class="col-sm-3 control-label no-padding-right"></label>
        <div class="col-xs-12 col-sm-4">
            <p class="pull-right">
                <button type="button" class="btn btn-sm btn-success" id="btn-save" onclick="saveAdd()"><i
                        class="fa fa-save"></i> Add</button>
            </p>
        </div>
        <div class="help-block col-xs-12 col-sm-reset inline">
            &nbsp;
        </div>
    </div>
    <input type="hidden" name="emp_id" id="emp_id" />

</form>

<script src="<?php echo base_url(); ?>scripts/users/users.js"></script>
<?php $this->load->view('include/footer'); ?>