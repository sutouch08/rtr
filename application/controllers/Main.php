<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Main extends PS_Controller
{
	public $title = 'Welcome';
	public $menu_code = '';
	public $menu_group_code = '';
	public $error;

	public function __construct()
	{
		parent::__construct();
		$this->load->model('masters/warehouse_model');
	}


	public function index()
	{
		$ds = array(
			'warehouse_list' => $this->warehouse_model->get_all_active_warehouse()
		);

		$this->load->view('main_view', $ds);
	}

} //--- end class
