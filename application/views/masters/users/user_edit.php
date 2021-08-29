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
            <input type="text" name="uname" id="uname" class="width-100" value="<?php echo $data->uname; ?>"
                onkeyup="validCode(this)" autofocus required />
        </div>
        <div class="help-block col-xs-12 col-sm-reset inline red" id="uname-error"></div>
    </div>

    <div class="form-group">
        <label class="col-sm-3 control-label no-padding-right">ชื่อพนักงาน</label>
        <div class="col-xs-12 col-sm-4">
            <input type="text" name="emp" id="emp" class="width-100" value="<?php echo $data->name; ?>" required />
        </div>
        <div class="help-block col-xs-12 col-sm-reset inline red" id="emp-error"></div>
    </div>


    <div class="form-group">
        <label class="col-sm-3 control-label no-padding-right">กลุ่มผู้ใช้</label>
        <div class="col-xs-12 col-sm-4">
            <select class="form-control input-sm" name="ugroup" id="ugroup">
                <option value="user" <?php echo is_selected('user', $data->ugroup); ?>>User</option>
                <option value="admin" <?php echo is_selected('admin', $data->ugroup); ?>>Admin</option>
            </select>
        </div>
        <div class="help-block col-xs-12 col-sm-reset inline red" id="ugroup-error"></div>
    </div>

    

    <div class="form-group">
        <label class="col-sm-3 control-label no-padding-right"></label>
        <div class="col-xs-12 col-sm-4">
            <label>
                <input type="checkbox" class="ace" name="status" id="status" value="1"
                    <?php echo is_checked(1, $data->status); ?> />
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
                <button type="button" class="btn btn-sm btn-success" id="btn-save" onclick="update()"><i
                        class="fa fa-save"></i> Update</button>
            </p>
        </div>
        <div class="help-block col-xs-12 col-sm-reset inline">
            &nbsp;
        </div>
    </div>

    <input type="hidden" name="old_uname" id="old_uname" value="<?php echo $data->uname; ?>" />
    <input type="hidden" name="user_id" id="user_id" value="<?php echo $data->id; ?>" />
</form>

<script src="<?php echo base_url(); ?>scripts/users/users.js"></script>
<?php $this->load->view('include/footer'); ?>