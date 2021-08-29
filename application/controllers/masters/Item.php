<?php
class Item extends PS_Controller
{
	public $title = 'เพิ่ม/แก้ไข รายการสินค้า';
	public $menu_code = 'DBITEM';
	public $menu_group_code = 'MASTER';
	public $error;

    public function __construct()
    {
        parent::__construct();
        $this->load->model('masters/item_group_model');
        $this->load->model('masters/item_model');
        $this->load->model('masters/uom_model');
				$this->load->model('masters/uom_item_model');

				$this->load->helper('item');
    }

    public function index()
    {
      $filter = array(
				'name' => get_filter('name', 'item_name', ''),
				'barcode' => get_filter('barcode', 'item_barcode', ''),
        'item_group' => get_filter('item_group', 'item_group', 'all'),
        'status' => get_filter('status', 'item_status', 'all')
			);

		//--- แสดงผลกี่รายการต่อหน้า
		$perpage = get_filter('set_rows', 'rows', 20);
		//--- หาก user กำหนดการแสดงผลมามากเกินไป จำกัดไว้แค่ 300
		if($perpage > 300)
		{
			$perpage = get_filter('rows', 'rows', 300);
		}

		$segment = 3; //-- url segment

		$rows = $this->item_model->count_rows($filter);

		//--- ส่งตัวแปรเข้าไป 4 ตัว base_url ,  total_row , perpage = 20, segment = 3
		$init	= pagination_config($this->home.'/index/', $rows, $perpage, $segment);

		$rs = $this->item_model->get_list($filter, $perpage, $this->uri->segment($segment));

		$filter['data'] = $rs;

		$this->pagination->initialize($init);

        $this->load->view('masters/item/item_list', $filter);
    }




	public function add_new()
	{
		if($this->isAdmin)
		{
			$this->load->view('masters/item/item_add');
		}
		else
		{
			$this->deny_page();
		}
	}



	public function add()
	{
		$sc = TRUE;

		if($this->isAdmin)
		{
			$name = trim($this->input->post('name'));
			if(!empty($name))
			{
				if(!$this->item_model->is_exists_name($name))
				{
					$barcode = trim($this->input->post('barcode'));
					$is_exists_barcode = FALSE;

					if($barcode !== NULL)
					{
						$is_exists_barcode = $this->item_model->is_exists_barcode($barcode);
					}

					if(!$is_exists_barcode)
					{
						$uom_id = trim($this->input->post('uom_id'));
						$main_uom_id = $this->input->post('main_uom_id');
						$rate = $this->input->post('rate');

						$arr = array(
							'name' => $name,
							'barcode' => $barcode,
							'uom_id' => $uom_id,
							'main_uom_id' => empty($main_uom_id) ? $uom_id : $main_uom_id,
							'rate' => empty($rate) ? 1 : $rate,
							'item_group_id' => trim($this->input->post('item_group_id')),
							'price' => empty($this->input->post('price')) ? 0.00 : $this->input->post('price'),
							'status' => empty($this->input->post('status')) ? 0 : 1,
							'add_user' => $this->user->id,
						);

						if(!$this->item_model->add($arr))
						{
							$sc = FALSE;
							$this->error = "เพิ่มรายการสินค้าไม่สำเร็จ";
						}
					}
					else
					{
						$sc = FALSE;
						$this->error = "บาร์โค้ดซ้ำ กรุณาตรวจสอบ";
					}
				}
				else
				{
					$sc = FALSE;
					$this->error = "ชื่อสินค้าซ้ำ กรุณาใช้ชื่ออื่น";
				}
			}
			else
			{
				$sc = FALSE;
				$this->error = "Missing Required Parameter: name";
			}
		}
		else
		{
			$sc = FALSE;
			$this->error = "Missing Permission";
		}


		$this->_response($sc);
	}



	public function edit($id)
	{
		if($this->isAdmin)
		{
			$ds = $this->item_model->get($id);

			if(!empty($ds))
			{
				$this->load->view('masters/item/item_edit', $ds);
			}
			else
			{
				$this->page_error();
			}
		}
		else
		{
			$this->deny_page();
		}
	}



	public function update()
	{
		$sc = TRUE;

		if($this->isAdmin)
		{
			if($this->input->post('id'))
			{
				$id = $this->input->post('id');
				$name = trim($this->input->post('name'));

				if(!empty($name))
				{
					if(!$this->item_model->is_exists_name($name, $id))
					{
						$barcode = trim($this->input->post('barcode'));
						$is_exists_barcode = FALSE;

						if($barcode !== NULL)
						{
							$is_exists_barcode = $this->item_model->is_exists_barcode($barcode, $id);
						}

						if(!$is_exists_barcode)
						{
							$uom_id = trim($this->input->post('uom_id'));
							$main_uom_id = $this->input->post('main_uom_id');
							$rate = $this->input->post('rate');

							$arr = array(
								'name' => $name,
								'barcode' => $barcode,
								'uom_id' => $uom_id,
								'main_uom_id' => empty($main_uom_id) ? $uom_id : $main_uom_id,
								'rate' => empty($rate) ? 1 : $rate,
								'item_group_id' => trim($this->input->post('item_group_id')),
								'price' => empty($this->input->post('price')) ? 0.00 : $this->input->post('price'),
								'status' => empty($this->input->post('status')) ? 0 : 1,
								'update_user' => $this->user->id,
							);

							if(!$this->item_model->update($id, $arr))
							{
								$sc = FALSE;
								$this->error = "ปรับปรุงช้อมูลไม่สำเร็จ";
							}
						}
						else
						{
							$sc = FALSE;
							$this->error = "บาร์โค้ดซ้ำ กรุณาตรวจสอบ";
						}

					}
					else
					{
						$sc = FALSE;
						$this->error = "ชื่อสินค้าซ้ำ กรุณาใช้ชื่ออื่น";
					}
				}
				else
				{
					$sc = FALSE;
					$this->error = "Missing required parameter: name";
				}
			}
			else
			{
				$sc = FALSE;
				$this->error = "Missing required parameter : id";
			}
		}
		else
		{
			$sc = FALSE;
			$this->error = "Missing Permission";
		}

		$this->_response($sc);
	}



	public function delete()
	{
		$sc = TRUE;

		if($this->isAdmin)
		{
			$id = $this->input->post('id');
			if(!empty($id))
			{
				if(!$this->item_model->delete($id))
				{
					$sc = FALSE;
					$this->error = "ลบสินค้าไม่สำเร็จ";
				}
			}
			else
			{
				$sc = FALSE;
				$this->error = "Missing required parameter : id";
			}
		}
		else
		{
			$sc = FALSE;
			$this->error = "Missing Permission";
		}

		$this->_response($sc);
	}



	public function get_detail($id)
	{
		$rs = $this->item_model->get($id);

		if(!empty($rs))
		{
			$arr = array(
				'name' => $rs->name,
				'barcode' => $rs->barcode,
				'price' => $rs->price,
				'group_name' => $rs->item_group_name,
				'uom_name' => $rs->uom_name,
				'main_uom_name' => $rs->main_uom_name,
				'rate' => round($rs->rate, 2),
				'status' => $rs->status == 1 ? 'Active' : 'Disactive'
			);

			echo json_encode($arr);
		}
		else
		{
			echo "ไม่พบข้อมูลสินค้า";
		}
	}


	public function clear_filter()
	{
		$filter = array('item_group', 'item_name', 'item_barcode', 'item_status');

		clear_filter($filter);

		echo "done";
	}

}
?>
