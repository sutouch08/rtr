<?php
class Warehouse extends PS_Controller
{
	public $title = 'เพิ่ม/แก้ไข คลังสินค้า';
	public $menu_code = 'DBWHS';
	public $menu_group_code = 'MASTER';
	public $error;

    public function __construct()
    {
        parent::__construct();
        $this->load->model('masters/warehouse_model');
    }

    public function index()
    {
        $filter = array(
			'name' => get_filter('name', 'wh_name', ''),
			'status' => get_filter('status', 'wh_status', 'all')
		);

		//--- แสดงผลกี่รายการต่อหน้า
		$perpage = get_filter('set_rows', 'rows', 20);
		//--- หาก user กำหนดการแสดงผลมามากเกินไป จำกัดไว้แค่ 300
		if($perpage > 300)
		{
			$perpage = get_filter('rows', 'rows', 300);
		}

		$segment = 3; //-- url segment

		$rows = $this->warehouse_model->count_rows($filter);

		//--- ส่งตัวแปรเข้าไป 4 ตัว base_url ,  total_row , perpage = 20, segment = 3
		$init	= pagination_config($this->home.'/index/', $rows, $perpage, $segment);

		$rs = $this->warehouse_model->get_list($filter, $perpage, $this->uri->segment($segment));
		$filter['data'] = $rs;

		$this->pagination->initialize($init);

        $this->load->view('masters/warehouse/warehouse_list', $filter);
    }




	public function add_new()
	{
		if($this->isAdmin)
		{
			$this->load->view('masters/warehouse/warehouse_add');
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
				if(!$this->warehouse_model->is_exists_name($name))
				{
					$arr = array(
						'name' => $name,
						'status' => $this->input->post('status')
					);

					if(!$this->warehouse_model->add($arr))
					{
						$sc = FALSE;
						$this->error = "เพิ่มคลังไม่สำเร็จ";
					}
				}
				else 
				{
					$sc = FALSE;
					$this->error = "ชื่อคลังซ้ำ กรุณาใช้ชื่ออื่น";
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
			$ds = $this->warehouse_model->get($id);

			if(!empty($ds))
			{
				$this->load->view('masters/warehouse/warehouse_edit', $ds);
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
				$status = $this->input->post('status');

				if(!empty($name))
				{
					if(!$this->warehouse_model->is_exists_name($name, $old_name))
					{
						$arr = array(
							'name' => $name,
							'status' => $status
						);

						if(!$this->warehouse_model->update($id, $arr))
						{
							$sc = FALSE;
							$this->error = "ปรับปรุงช้อมูลไม่สำเร็จ";
						}
					}
					else 
					{
						$sc = FALSE;
						$this->error = "ชื่อคลังซ้ำ กรุณาใช้ชื่ออื่น";
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
				if(!$this->warehouse_model->delete($id))
				{
					$sc = FALSE;
					$this->error = "ลบคลังไม่สำเร็จ";
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
		$filter = array('wh_name', 'wh_status');

		clear_filter($filter);

		echo "done";
	}

}
?>