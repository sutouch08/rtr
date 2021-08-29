
<div class="tab-pane fade" id="PRINT">
	<form id="printForm" method="post" action="<?php echo $this->home; ?>/update_config">
  	<div class="row">
    	<div class="col-sm-4">
        <span class="form-control left-label">รายการสูงสุดต่อหน้า</span>
      </div>
      <div class="col-sm-8">
        <input type="text" class="form-control input-sm input-small" name="ROW_PER_PAGE"  value="<?php echo $ROW_PER_PAGE; ?>" />
      </div>
      <div class="divider-hidden"></div>

      <div class="col-sm-4">
        <span class="form-control left-label">ความยาวตัวอักษรต่อบรรทัด</span>
      </div>
      <div class="col-sm-8">
        <input type="text" class="form-control input-sm input-small" name="TEXT_PER_ROW" value="<?php echo $TEXT_PER_ROW; ?>" />
      </div>
      <div class="divider-hidden"></div>


      <div class="col-sm-8 col-sm-offset-4">
				<?php if($this->isAdmin or $this->isSuperadmin) : ?>
        <button type="button" class="btn btn-sm btn-success input-small" onClick="updateConfig('printForm')">
          <i class="fa fa-save"></i> บันทึก
        </button>
				<?php endif; ?>
      </div>
      <div class="divider-hidden"></div>

  	</div><!--/ row -->
  </form>
</div>
