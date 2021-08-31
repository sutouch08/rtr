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
						$uom_item = json_decode($this->input->post('uom_items'));

						$arr = array(
							'name' => $name,
							'barcode' => $barcode,
							'uom_id' => $uom_id,
							'main_uom_id' => empty($main_uom_id) ? $uom_id : $main_uom_id,
							'item_group_id' => trim($this->input->post('item_group_id')),
							'price' => empty($this->input->post('price')) ? 0.00 : $this->input->post('price'),
							'status' => empty($this->input->post('status')) ? 0 : 1,
							'add_user' => $this->user->id
						);

						$id = $this->item_model->add($arr);

						if(! $id)
						{
							$sc = FALSE;
							$this->error = "เพิ่มรายการสินค้าไม่สำเร็จ";
						}
						else
						{
							//--- insert SKU uom_item
							$arr = array(
								'item_id' => $id,
								'uom_id' => $uom_id,
								'rate' => 1
							);

							if(! $this->uom_item_model->add($arr))
							{
								$sc = FALSE;
								$this->error = "Insert SKU failed";
							}

							//--- insert Main uom_item

							if($sc === TRUE && !empty($main_uom_id) && $main_uom_id != $uom_id)
							{
								$arr = array(
									'item_id' => $id,
									'uom_id' => $main_uom_id,
									'rate' => empty($rate) ? 1 : $rate
								);

								if(!$this->uom_item_model->add($arr))
								{
									$sc = FALSE;
									$this->error = "Insert Main uom failed";
								}
							}


							//--- insert other uom_item
							if($sc === TRUE && !empty($uom_item))
							{
								foreach($uom_item as $rs)
								{
									//---- if not same as sku and not same as main uom
									if($rs->id != $uom_id && $rs->id != $main_uom_id)
									{
										$arr = array(
											'item_id' => $id,
											'uom_id' => $rs->id,
											'rate' => $rs->rate
										);

										$this->uom_item_model->add($arr);
									}
								}
							}

							if($sc === TRUE)
							{
								if(!empty($_FILES['image']))
								{
									$this->do_upload($_FILES['image'], $id);
								}
							}
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
				$ds->uom_items = $this->uom_item_model->get_item_uom($id);
				$ds->image = get_image_path($id, 'medium');
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
							$this->db->trans_begin();

							$uom_id = trim($this->input->post('uom_id'));
							$main_uom_id = $this->input->post('main_uom_id');
							$rate = $this->input->post('rate');
							$uom_item = json_decode($this->input->post('uom_items'));

							$arr = array(
								'name' => $name,
								'barcode' => $barcode,
								'uom_id' => $uom_id,
								'main_uom_id' => empty($main_uom_id) ? $uom_id : $main_uom_id,
								'item_group_id' => trim($this->input->post('item_group_id')),
								'price' => empty($this->input->post('price')) ? 0.00 : $this->input->post('price'),
								'status' => empty($this->input->post('status')) ? 0 : 1,
								'update_user' => $this->user->id
							);

							if(!$this->item_model->update($id, $arr))
							{
								$sc = FALSE;
								$this->error = "ปรับปรุงช้อมูลไม่สำเร็จ";
							}

							if($sc === TRUE)
							{
								//--- Drop uom item
								if(! $this->uom_item_model->drop_current_item_uom($id))
								{
									$sc = FALSE;
									$this->error = "Delete current uom items failed";
								}
								else
								{
									//--- insert SKU uom_item
									$arr = array(
										'item_id' => $id,
										'uom_id' => $uom_id,
										'rate' => 1
									);

									if(! $this->uom_item_model->add($arr))
									{
										$sc = FALSE;
										$this->error = "Insert SKU failed";
									}

									//--- insert Main uom_item

									if($sc === TRUE && !empty($main_uom_id) && $main_uom_id != $uom_id)
									{
										$arr = array(
											'item_id' => $id,
											'uom_id' => $main_uom_id,
											'rate' => empty($rate) ? 1 : $rate
										);

										if(!$this->uom_item_model->add($arr))
										{
											$sc = FALSE;
											$this->error = "Insert Main uom failed";
										}
									}


									//--- insert other uom_item
									if($sc === TRUE && !empty($uom_item))
									{
										foreach($uom_item as $rs)
										{
											//---- if not same as sku and not same as main uom
											if($rs->id != $uom_id && $rs->id != $main_uom_id)
											{
												$arr = array(
													'item_id' => $id,
													'uom_id' => $rs->id,
													'rate' => $rs->rate
												);

												$this->uom_item_model->add($arr);
											}
										}
									}


									if($sc === TRUE)
									{
										if(!empty($_FILES['image']))
										{
											$this->do_upload($_FILES['image'], $id);
										}
									}
								}

							}

							if($sc === TRUE)
							{
								$this->db->trans_commit();
							}
							else
							{
								$this->db->trans_rollback();
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
				$this->db->trans_begin();
				if(!$this->item_model->delete($id))
				{
					$sc = FALSE;
					$this->error = "ลบสินค้าไม่สำเร็จ";
				}
				else
				{
					if(!$this->uom_item_model->drop_current_item_uom($id))
					{
						$sc = FALSE;
						$this->error = "Drop uom item failed";
					}
					else
					{
						$this->delete_product_image($id);
					}
				}

				if($sc === TRUE)
				{
					$this->db->trans_commit();
				}
				else
				{
					$this->db->trans_rollback();
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
				'uom_items_name' => get_item_uom_text($id, $rs->uom_id, $rs->main_uom_id),
				'rate' => round($rs->rate, 2),
				'status' => $rs->status == 1 ? 'Active' : 'Disactive',
				'image' => get_image_path($rs->id, 'large')
			);

			echo json_encode($arr);
		}
		else
		{
			echo "ไม่พบข้อมูลสินค้า";
		}
	}


	public function do_upload($file, $item_id)
	{
		$sc = TRUE;
		$this->load->library('upload');

		$img_name 	= $item_id; //-- ตั้งชื่อรูปตาม item id
		$image_path = $this->config->item('image_path').'products/';
		$use_size 	= array('mini', 'default', 'medium', 'large'); //---- ใช้ทั้งหมด 4 ขนาด
		$image 	= new Upload($file);

		if( $image->uploaded )
		{
			foreach($use_size as $size)
			{
				$imagePath = $image_path.$size.'/'; //--- แต่ละ folder
				$img	= $this->getImageSizeProperties($size); //--- ได้ $img['prefix'] , $img['size'] กลับมา
				$image->file_new_name_body = $img['prefix'] . $img_name; 		//--- เปลี่ยนชือ่ไฟล์ตาม prefix + id_image
				$image->image_resize			 = TRUE;		//--- อนุญาติให้ปรับขนาด
				$image->image_retio_fill	 = TRUE;		//--- เติกสีให้เต็มขนาดหากรูปภาพไม่ได้สัดส่วน
				$image->file_overwrite		 = TRUE;		//--- เขียนทับไฟล์เดิมได้เลย
				$image->auto_create_dir		 = TRUE;		//--- สร้างโฟลเดอร์อัตโนมัติ กรณีที่ไม่มีโฟลเดอร์
				$image->image_x					   = $img['size'];		//--- ปรับขนาดแนวตั้ง
				$image->image_y					   = $img['size'];		//--- ปรับขนาดแนวนอน
				$image->image_background_color	= "#FFFFFF";		//---  เติมสีให้ตามี่กำหนดหากรูปภาพไม่ได้สัดส่วน
				$image->image_convert			= 'jpg';		//--- แปลงไฟล์

				$image->process($imagePath);						//--- ดำเนินการตามที่ได้ตั้งค่าไว้ข้างบน

				if( ! $image->processed )	//--- ถ้าไม่สำเร็จ
				{
					$sc = FALSE;
					$this->error = $image->error;
				}
			} //--- end foreach
		} //--- end if

		$image->clean();	//--- เคลียร์รูปภาพออกจากหน่วยความจำ

		return $sc;
	}

	public function getImageSizeProperties($size)
	{
		$sc = array();
		switch($size)
		{
			case "mini" :
			$sc['prefix']	= "product_mini_";
			$sc['size'] 	= 60;
			break;
			case "default" :
			$sc['prefix'] 	= "product_default_";
			$sc['size'] 	= 125;
			break;
			case "medium" :
			$sc['prefix'] 	= "product_medium_";
			$sc['size'] 	= 250;
			break;
			case "large" :
			$sc['prefix'] 	= "product_large_";
			$sc['size'] 	= 1500;
			break;
			default :
			$sc['prefix'] 	= "";
			$sc['size'] 	= 300;
			break;
		}//--- end switch
		return $sc;
	}



	public function delete_product_image($id)
	{
		$path = $this->config->item('image_file_path').'products/';
		$use_size = array('mini', 'default', 'medium', 'large', 'large');
		foreach($use_size as $size)
		{
			$file = $path.$size.'/product_'.$size.'_'.$id.'.jpg';
			if(file_exists($file))
			{
				unlink($file);
			}
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
