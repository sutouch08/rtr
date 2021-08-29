<?php
class Item_group extends PS_Controller
{
	public $title = 'เพิ่ม/แก้ไข กลุ่มสินค้า';
	public $menu_code = 'DBITGP';
	public $menu_group_code = 'MASTER';
	public $error;

    public function __construct()
    {
        parent::__construct();
        $this->load->model('masters/item_group_model');
    }

    public function index()
    {
        $filter = array(
			'name' => get_filter('name', 'item_group_name', '')
		);

		//--- แสดงผลกี่รายการต่อหน้า
		$perpage = get_filter('set_rows', 'rows', 20);
		//--- หาก user กำหนดการแสดงผลมามากเกินไป จำกัดไว้แค่ 300
		if($perpage > 300)
		{
			$perpage = get_filter('rows', 'rows', 300);
		}

		$segment = 3; //-- url segment

		$rows = $this->item_group_model->count_rows($filter);

		//--- ส่งตัวแปรเข้าไป 4 ตัว base_url ,  total_row , perpage = 20, segment = 3
		$init	= pagination_config($this->home.'/index/', $rows, $perpage, $segment);

		$rs = $this->item_group_model->get_list($filter, $perpage, $this->uri->segment($segment));
		$filter['data'] = $rs;

		$this->pagination->initialize($init);

        $this->load->view('masters/item_group/item_group_list', $filter);
    }




	public function add_new()
	{
		if($this->isAdmin)
		{
			$this->load->view('masters/item_group/item_group_add');
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
				if(!$this->item_group_model->is_exists_name($name))
				{
					$arr = array(
						'name' => $name
					);

					if(!$this->item_group_model->add($arr))
					{
						$sc = FALSE;
						$this->error = "เพิ่มกลุ่มสินค้าไม่สำเร็จ";
					}
				}
				else
				{
					$sc = FALSE;
					$this->error = "ชื่อกลุ่มสินค้าซ้ำ กรุณาใช้ชื่ออื่น";
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


	public function remote_add()
	{
		$sc = TRUE;
		$ds = array();
		if($this->isAdmin)
		{
			if($this->input->post('name'))
			{
				$name = trim($this->input->post('name'));
				if(!$this->item_group_model->is_exists_name($name))
				{
					$arr = array(
						'name' => $name
					);

					$id = $this->item_group_model->add($arr);

					if(!$id)
					{
						$sc = FALSE;
						$this->error = "เพิ่มกลุ่มสินค้าไม่สำเร็จ";
					}
					else
					{
						$this->load->helper('item');
						$result = select_item_group($id);
					}
				}
				else
				{
					$sc = FALSE;
					$this->error = "ชื่อกลุ่มสินค้าซ้ำ กรุณาใช้ชื่ออื่น";
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
			$this->error = "Missing permission";
		}

		if($sc === TRUE)
		{
			$ds = array(
				'result' => 'success',
				'item' => $result
			);
		}
		else
		{
			$ds = array(
				'result' => 'failed',
				'message' => $this->error
			);
		}


		echo json_encode($ds);
	}



	public function edit($id)
	{
		if($this->isAdmin)
		{
			$ds = $this->item_group_model->get($id);

			if(!empty($ds))
			{
				$this->load->view('masters/item_group/item_group_edit', $ds);
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
				$old_name = trim($this->input->post('old_name'));


				if(!empty($name))
				{
					if(!$this->item_group_model->is_exists_name($name, $old_name))
					{
						$arr = array(
							'name' => $name
						);

						if(!$this->item_group_model->update($id, $arr))
						{
							$sc = FALSE;
							$this->error = "ปรับปรุงช้อมูลไม่สำเร็จ";
						}
					}
					else
					{
						$sc = FALSE;
						$this->error = "ชื่อกลุ่มสินค้าซ้ำ กรุณาใช้ชื่ออื่น";
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
				if(!$this->item_group_model->delete($id))
				{
					$sc = FALSE;
					$this->error = "ลบกลุ่มสินค้าไม่สำเร็จ";
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


	public function clear_filter()
	{
		$filter = array('item_group_name');

		clear_filter($filter);

		echo "done";
	}

}
?>
