<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Users extends PS_Controller
{
	public $menu_code = 'DBUSER'; //--- Add/Edit Users
	public $menu_group_code = 'MASTER'; //--- System security
	public $title = 'เพิ่ม/แก้ไข ผู้ใช้งาน';

	public function __construct()
	{
		parent::__construct();
		$this->home = base_url().'masters/users';
	}



	public function index()
	{
			$filter = array(
				'uname' => get_filter('uname', 'username', ''),
				'name' => get_filter('name', 'emp_name', ''),
				'ugroup' => get_filter('ugroup', 'user_group', 'all'),
				'status' => get_filter('status', 'user_status', 'all')
			);

			//--- แสดงผลกี่รายการต่อหน้า
			$perpage = get_filter('set_rows', 'rows', 20);
			//--- หาก user กำหนดการแสดงผลมามากเกินไป จำกัดไว้แค่ 300
			if($perpage > 300)
			{
				$perpage = get_filter('rows', 'rows', 300);
			}

			$segment = 3; //-- url segment
			$rows = $this->user_model->count_rows($filter);

			//--- ส่งตัวแปรเข้าไป 4 ตัว base_url ,  total_row , perpage = 20, segment = 3
			$init	= pagination_config($this->home.'/index/', $rows, $perpage, $segment);

			$rs = $this->user_model->get_list($filter, $perpage, $this->uri->segment($segment));
			$filter['data'] = $rs;

			$this->pagination->initialize($init);

		$this->load->view('masters/users/users_list', $filter);
	}



	public function add_new()
	{
		if($this->isAdmin)
		{
			$this->load->view('masters/users/user_add');
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
			if($this->input->post('uname'))
			{
				$uname = trim($this->input->post('uname'));
				if(!empty($uname))
				{
					if(!$this->user_model->is_exists_uname($uname))
					{
						$arr = array(
							'uname' => $uname,
							'name' => trim($this->input->post('emp_name')),							
							'pwd' => password_hash(trim($this->input->post('pwd')), PASSWORD_DEFAULT),
							'uid' => md5(uniqid()),
							'ugroup' => $this->input->post('ugroup'),							
							'status' => $this->input->post('status')
						);

						if(! $this->user_model->add($arr))
						{
							$sc = FALSE;
							$this->error = "เพิ่มผู้ใช้งานไม่สำเร็จ";
						}

					}
					else 
					{
						$sc = FALSE;
						$this->error = "ชื่อผู้ใช้งานซ้ำ กรุณากำหนดใหม่";
					}
				}
				else
				{
					$sc = FALSE;
					$this->error = "Missing required parameter : User Name";
				}
				
			}
			else
			{
				$sc = FALSE;
				$this->error = "Missing Required Parameter";
			}
		}
		else
		{
			$sc = FALSE;
			$this->error = "Missing permission";
		}

		$this->_response($sc);
	}
	
	
	public function is_exists_uname()
	{
		$sc = TRUE;
		$uname = trim($this->input->post('uname'));
		$old_uname = trim($this->input->post('old_uname'));

		if($this->user_model->is_exists_uname($uname, $old_uname))
		{
			$sc = FALSE;
			$this->error = "User Name ซ้ำ";
		}

		$this->_response($sc);
	}




	public function edit($id)
	{
		if($this->isAdmin)
		{
			$rs = $this->user_model->get($id);
			if(!empty($rs))
			{
				$ds['data'] = $rs;
				$this->load->view('masters/users/user_edit', $ds);
			}
			else
			{
				$this->load->view('page_error');
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
			if($this->input->post('id') && $this->input->post('uname'))
			{
				$id = $this->input->post('id');
				$uname = trim($this->input->post('uname'));
				$old_uname = trim($this->input->post('old_uname'));

				if(!$this->user_model->is_exists_uname($uname, $old_uname))
				{
					$arr = array(
						'uname' => $uname,					
						'name' => trim($this->input->post('emp_name')),
						'ugroup' => $this->input->post('ugroup'),
						'status' => $this->input->post('status')
					);

					if(! $this->user_model->update($id, $arr))
					{
						$sc = FALSE;
						$this->error = "Update user failed";
					}
				}
				else 
				{
					$sc = FALSE; 
					$this->error = "ชื่อผู้ใช้งานซ้ำ กรุณากำหนดใหม่";
				}
				
			}
			else
			{
				$sc = FALSE;
				$this->error = "Missing required parameter";
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

			$user = $this->user_model->get($id);

			if(!empty($user))
			{				

				if($sc === TRUE)
				{
					if(! $this->user_model->delete($user->id))
					{
						$sc = FALSE;
						$this->error = "Delete Failed";
					}
				}

			}
			else
			{
				$sc = FALSE;
				$this->error = "Invalid User ID";
			}
		}
		else
		{
			$sc = FALSE;
			$this->error = "Missing Permission";
		}

		$this->_response($sc);
	}


	//---- Reset password by Administrator
	public function reset_password($id)
	{
		if($this->isAdmin)
		{
			$this->title = 'Reset Password';
			$rs = $this->user_model->get($id);
			if(!empty($rs))
			{
				$data['data'] = $rs;
				$this->load->view('masters/users/user_reset_pwd', $data);
			}
			else
			{
				$this->error_page();
			}
		}
		else
		{
			$this->deny_page();
		}

	}



	public function change_password()
	{
		$sc = TRUE;

		if($this->isAdmin)
		{
			if(!empty($this->input->post('id')) && !empty($this->input->post('pwd')))
			{
				$pwd = trim($this->input->post('pwd'));

				if(!empty($pwd))
				{
					$id = $this->input->post('id');
					$pwd = password_hash($pwd, PASSWORD_DEFAULT);

					$arr = array(
						'pwd' => $pwd
					);

					if( ! $this->user_model->update($id, $arr))
					{
						$sc = FALSE;
						$this->error = "Update Failed";
					}
				}
				else
				{
					$sc = FALSE;
					$this->error = "Password Can not be empty";
				}
			}
			else
			{
				$sc = FALSE;
				$this->error = "Missing required parameter !";
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

		$filter = array(
			'username',
			'emp_name',
			'user_group',
			'user_status'
		);

		clear_filter($filter);
		echo 'done';
	}

}//--- end class


?>