<?php $this->load->view('include/header'); ?>

<div class="row" style="margin-top:30px;">
    <?php if(!empty($warehouse_list)) : ?>
        <?php foreach($warehouse_list as $rs) : ?>
            <div class="col-lg-3 col-md-4 col-sm-6 col-xs-12">
                <button type="button" class="btn btn-lg btn-primary btn-block margin-bottom-20" 
                onclick="goToWarehouse(<?php echo $rs->id; ?>)"><i class="fa fa-home fa-3x"></i> <br/><?php echo $rs->name; ?></button>
            </div>
        <?php endforeach; ?>
    
        <?php else : ?>
        <div class="well">
            <h4 class="green smaller lighter">กรุณาสร้างคลัง</h4>
        </div>
        <?php endif; ?>
    </div>

</div>
</div>


<?php $this->load->view('include/footer'); ?>