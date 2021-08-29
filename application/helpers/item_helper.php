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



    function get_item_oum_text($id)
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
          $sc .= $i === 1 ? $rs->uom_name."(".$rs->rate.")" : ", ".$rs->uom_name."(".$rs->rate.")";
          $i++;
        }
      }

      return $sc;
    }


?>
