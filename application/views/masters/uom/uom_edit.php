<?php $this->load->view('include/header'); ?>
<div class="row">
    <div class="col-sm-6 col-xs-6 padding-5">
        <h3 class="title"><i class="fa fa-home"></i> <?php echo $this->title; ?></h3>
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
        <label class="col-sm-3 control-label no-padding-right">หน่วยนับ</label>
        <div class="col-xs-12 col-sm-4">
            <input type="text" name="name" id="name" class="width-100" maxlength="100" value="<?php echo $name; ?>"
                autofocus required />
        </div>
        <div class="help-block col-xs-12 col-sm-reset inline red" id="name-error"></div>
    </div>


    <div class="divider-hidden">

    <input type="hidden" id="uom_id" value="<?php echo $id; ?>" />
    <input type="hidden" id="old_name" value="<?php echo $name; ?>" />

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
    <input type="text" class="hidden" name="x-form">
</form>

<script src="<?php echo base_url(); ?>scripts/masters/uom.js?v=<?php echo date('YmdHis'); ?>"></script>
<?php $this->load->view('include/footer'); ?>