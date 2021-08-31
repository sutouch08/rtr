<?php

    function select_item_group($id = NULL)
    {
      $sc = "";
      $ci =& get_instance();
      $ci->load->model('masters/item_group_model');
      $options = $ci->item_group_model->get_all_item_group_list();

      if(!empty($options))
      {
        foreach($options as $rs)
        {
          $sc .= "<option value='{$rs->id}' ".is_selected($rs->id, $id).">{$rs->name}</option>";
        }
      }

      return $sc;
    }



    function select_uom($id = NULL)
    {
      $sc = "";
      $ci =& get_instance();
      $ci->load->model('masters/uom_model');

      $uom = $ci->uom_model->get_all_uom_list();

      if(!empty($uom))
      {
        foreach($uom as $rs)
        {
          $sc .= "<option value='{$rs->id}' ".is_selected($rs->id, $id).">{$rs->name}</option>";
        }
      }

      return $sc;
    }



    function get_item_uom_text($id, $uom_id, $main_id)
    {
      $sc = "";
      $ci =& get_instance();
      $ci->load->model('uom_item_model');
      $uom = $ci->uom_item_model->get_item_uom($id);

      if(!empty($uom))
      {
        $i = 1;
        foreach($uom as $rs)
        {
          if($rs->uom_id != $uom_id && $rs->uom_id != $main_id)
          {
            $sc .= $i === 1 ? $rs->uom_name."(".$rs->rate.")" : ", ".$rs->uom_name."(".$rs->rate.")";
            $i++;
          }

        }
      }

      return $sc;
    }


    function uom_item_label($id, $uom_id, $main_id)
    {
      $sc = "";
      $ci =& get_instance();
      $ci->load->model('uom_item_model');
      $uom = $ci->uom_item_model->get_item_uom($id);

      if(!empty($uom))
      {

        foreach($uom as $rs)
        {
          $sc .= "<span class='label label-info label-white margin-right-5'>";
          if($rs->uom_id == $uom_id)
          {
            $sc .= "<i class='ace-icon fa fa-check bigger-120'></i>";
          }
          else if($rs->uom_id == $main_id)
          {
            $sc .= "<i class='ace-icon fa fa-star bigger-120'></i>";
          }
          if($rs->uom_id != $uom_id)
          {
            $sc .= $rs->uom_name."(".round($rs->rate, 2).")";
          }
          else
          {
            $sc .= $rs->uom_name;
          }

          $sc .= "</span>";
        }
      }

      return $sc;
    }


    function get_image_path($id, $size = 'default')
    {
      $CI =& get_instance();
      $path = $CI->config->item('image_path').'products/';
      $no_image_path = base_url().$path.$size.'/no_image_'.$size.'.jpg';
    	$image_path = base_url().$path.$size.'/product_'.$size.'_'.$id.'.jpg';
    	$file = $CI->config->item('image_file_path').'products/'.$size.'/product_'.$size.'_'.$id.'.jpg';

    	return file_exists($file) ? $image_path : $no_image_path;

    }


    function has_image($id, $size = 'default')
    {
      $CI =& get_instance();
    	$file = $CI->config->item('image_file_path').'products/'.$size.'/product_'.$size.'_'.$id.'.jpg';

    	return file_exists($file);
    }


    function delete_product_image($id)
    {
      $CI =& get_instance();
      $path = $CI->config->item('image_file_path').'products/';
      $use_size = array('mini', 'default', 'medium', 'large');
      foreach($use_size as $size)
      {
        $image_path = $path.$size.'/product_'.$size.'_'.$id.'.jpg';
        unlink($image_path);
      }
    }



    function no_image_path($size)
    {
      $CI =& get_instance();
      $path = $CI->config->item('image_path')."products/";
      $no_image_path = base_url().$path.$size.'/no_image_'.$size.'.jpg';
      return $no_image_path;
    }





?>
