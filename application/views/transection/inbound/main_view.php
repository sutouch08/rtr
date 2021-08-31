<?php $this->load->view('include/header'); ?>

<div class="row" style="margin-top:30px;">
  <div class="col-sm-12">
    <?php if(!empty($warehouse_list)) : ?>
        <?php foreach($warehouse_list as $rs) : ?>
          <a href="#" class="btn btn-app btn-primary no-radius" onclick="selectWarehouse(<?php echo $rs->id; ?>)">
            <i class="ace-icon fa fa-home bigger-230"></i>
            <?php echo $rs->name; ?>
          </a>
        <?php endforeach; ?>

        <?php else : ?>
        <div class="well">
            <h4 class="green smaller lighter">กรุณาสร้างคลัง</h4>
        </div>
        <?php endif; ?>
  </div>
</div>

<script>
  function selectWarehouse(id) {
    window.location.href = "<?php echo $this->home; ?>add/"+id;
  }
</script>


<?php $this->load->view('include/footer'); ?>
