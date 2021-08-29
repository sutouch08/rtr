<?php
class Uom extends PS_Controller
{
	public $title = 'เพิ่ม/แก้ไข หน่วยนับสินค้า';
	public $menu_code = 'DBUOM';
	public $menu_group_code = 'MASTER';
	public $error;

    public function __construct()
    {
        parent::__construct();
        $this->load->model('masters/uom_model');
    }

    public function index()
    {
        $filter = array(
			'name' => get_filter('name', 'oum_name', '')
		);

		//--- แสดงผลกี่รายการต่อหน้า
		$perpage = get_filter('set_rows', 'rows', 20);
		//--- หาก user กำหนดการแสดงผลมามากเกินไป จำกัดไว้แค่ 300
		if($perpage > 300)
		{
			$perpage = get_filter('rows', 'rows', 300);
		}

		$segment = 3; //-- url segment

		$rows = $this->uom_model->count_rows($filter);

		//--- ส่งตัวแปรเข้าไป 4 ตัว base_url ,  total_row , perpage = 20, segment = 3
		$init	= pagination_config($this->home.'/index/', $rows, $perpage, $segment);

		$rs = $this->uom_model->get_list($filter, $perpage, $this->uri->segment($segment));
		$filter['data'] = $rs;

		$this->pagination->initialize($init);

        $this->load->view('masters/uom/uom_list', $filter);
    }




	public function add_new()
	{
		if($this->isAdmin)
		{
			$this->load->view('masters/uom/uom_add');
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
				if(!$this->uom_model->is_exists_name($name))
				{
					$arr = array(
						'name' => $name
					);

					if(!$this->uom_model->add($arr))
					{
						$sc = FALSE;
						$this->error = "เพิ่มหน่วยนับไม่สำเร็จ";
					}
				}
				else
				{
					$sc = FALSE;
					$this->error = "ชื่อหน่วยนับซ้ำ กรุณาใช้ชื่ออื่น";
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
			$name = trim($this->input->post('name'));
			$main_id = $this->input->post('main_id');
			if(!empty($name))
			{
				if(!$this->uom_model->is_exists_name($name))
				{
					$arr = array(
						'name' => $name
					);

					$id = $this->uom_model->add($arr);
					if(! $id)
					{
						$sc = FALSE;
						$this->error = "เพิ่มหน่วยนับไม่สำเร็จ";
					}
					else
					{
						$this->load->helper('item');
						$item = "<option value=''>กรุณาเลือก</option>";
						$item .= select_uom($id);

						$main_item = "<option value=''>กรุณาเลือก</option>";
						$main_item .= select_uom($main_id);
					}
				}
				else
				{
					$sc = FALSE;
					$this->error = "ชื่อหน่วยนับซ้ำ กรุณาใช้ชื่ออื่น";
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


		if($sc === TRUE)
		{
			$ds = array(
				'result' => 'success',
				'item' => $item,
				'main_item' => $main_item
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
			$ds = $this->uom_model->get($id);

			if(!empty($ds))
			{
				$this->load->view('masters/uom/uom_edit', $ds);
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
					if(!$this->uom_model->is_exists_name($name, $old_name))
					{
						$arr = array(
							'name' => $name
						);

						if(!$this->uom_model->update($id, $arr))
						{
							$sc = FALSE;
							$this->error = "ปรับปรุงช้อมูลไม่สำเร็จ";
						}
					}
					else
					{
						$sc = FALSE;
						$this->error = "ชื่อหน่วยนับซ้ำ กรุณาใช้ชื่ออื่น";
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
				if(!$this->uom_model->delete($id))
				{
					$sc = FALSE;
					$this->error = "ลบหน่วยนับไม่สำเร็จ";
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
		$filter = array('uom_name');

		clear_filter($filter);

		echo "done";
	}

}
?>
