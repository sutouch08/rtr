<?php
class Inbound extends PS_Controller
{
  public $menu_code = "INBOUND";
  public $menu_group_code = "TRANST";

  public function __construct()
  {
    parent::__construct();

    $this->home = base_url()."transection/inbound/";
    $this->load->model('masters/warehouse_model');
    $this->load->model('masters/item_model');
    $this->load->model('masters/item_group_model');
    $this->load->model('masters/uom_item_model');
    $this->load->model('transection/inbound_model');
  }


  public function index()
  {
    $this->title = "เลือกสาขา";
    $ds = array(
      'warehouse_list' => $this->warehouse_model->get_all_active_warehouse()
    );

    $this->load->view('transection/inbound/main_view', $ds);
  }


  public function add(wh_id)
  {
    
  }
}

 ?>
