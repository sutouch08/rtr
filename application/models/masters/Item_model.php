<?php
class Item_model extends CI_Model
{

  public function __construct()
  {
    parent::__construct();
  }


  public function get($id)
  {
    $this->db
    ->select('item.*')
    ->select('item_group.name AS item_group_name')
    ->select('uom1.name AS uom_name')
    ->select('uom2.name AS main_uom_name')
    ->from('item')
    ->join('item_group', 'item.item_group_id = item_group.id', 'left')
    ->join('uom AS uom1', 'item.uom_id = uom1.id', 'left')
    ->join('uom AS uom2', 'item.main_uom_id = uom2.id', 'left');

    $rs = $this->db->where('item.id', $id)->get();
    
    if($rs->num_rows() === 1)
    {
      return $rs->row();
    }

    return NULL;
  }



  public function add(array $ds = array())
  {
    if(!empty($ds))
    {
      $rs = $this->db->insert('item', $ds);
      if($rs)
      {
        return $this->db->insert_id();
      }
    }

    return FALSE;
  }


  public function update($id, array $ds = array())
  {
    if(!empty($ds))
    {
      return $this->db->where('id', $id)->update('item', $ds);
    }

    return FALSE;
  }


  public function delete($id)
  {
    return $this->db->where('id', $id)->delete('item');
  }


  public function count_rows(array $ds = array())
  {
    $this->db
    ->from('item')
    ->join('uom', 'item.uom_id = uom.id', 'left')
    ->join('item_group', 'item.item_group_id = item_group.id', 'left')
    ->join('user', 'item.update_user = user.id', 'left');

    if(!empty($ds['name']))
    {
      $this->db->like('item.name', $ds['name']);
    }

    if(isset($ds['barcode']) && $ds['barcode'] !== "")
    {
      $this->db->like('item.barcode', $ds['barcode']);
    }

    if(!empty($ds['item_group']) && $ds['item_group'] != "all")
    {
      $this->db->where('item.item_group_id', $ds['item_group']);
    }

    if(isset($ds['status']) && $ds['status'] != "all")
    {
      $this->db->where('item.status', $ds['status']);
    }

    return $this->db->count_all_results();
  }


  public function get_list(array $ds = array(), $perpage = 20, $offset = 0)
  {
    $this->db
    ->select('item.*')
    ->select('item_group.name as group_name')
    ->select('uom1.name AS uom_name')
    ->select('uom2.name AS main_uom_name')
    ->select('user.name as user_name')
    ->from('item')
    ->join('uom AS uom1', 'item.uom_id = uom1.id', 'left')
    ->join('uom AS uom2', 'item.main_uom_id = uom2.id', 'left')
    ->join('item_group', 'item.item_group_id = item_group.id', 'left')
    ->join('user', 'item.update_user = user.id', 'left');

    if(!empty($ds['name']))
    {
      $this->db->like('item.name', $ds['name']);
    }

    if(isset($ds['barcode']) && $ds['barcode'] !== "")
    {
      $this->db->like('item.barcode', $ds['barcode']);
    }

    if(!empty($ds['item_group']) && $ds['item_group'] != "all")
    {
      $this->db->where('item.item_group_id', $ds['item_group']);
    }

    if(isset($ds['status']) && $ds['status'] != "all")
    {
      $this->db->where('item.status', $ds['status']);
    }

    $rs = $this->db->order_by('name', 'ASC')->limit($perpage, $offset)->get();

    if($rs->num_rows() > 0)
    {
      return $rs->result();
    }


    return NULL;
  }



  public function is_exists_name($name, $id = NULL)
  {
    $this->db->select('id')->where('name', $name);

    if(!empty($id))
    {
      $this->db->where('id !=', $id);
    }

    $rs = $this->db->get('item');

    if($rs->num_rows() > 0)
    {
      return TRUE;
    }

    return FALSE;
  }


  public function get_all_item_list()
  {
    $rs = $this->db->get('item');

    if($rs->num_rows() > 0)
    {
      return $rs->result();
    }

    return NULL;
  }


  public function get_item_by_barcode($barcode = NULL)
  {
    if($barcode !== NULL)
    {
      $rs = $this->db->where('barcode', $barcode);

      if($rs->num_rows() === 1)
      {
        return $rs->row();
      }
    }

    return NULL;
  }


  public function is_exists_barcode($barcode = NULL, $id = NULL)
  {
    if($barcode !== NULL && $barcode !== "")
    {
      if(!empty($id))
      {
        $this->db->where('id !=', $id);
      }

      $rs = $this->db->where('barcode', $barcode)->count_all_results('item');

      if($rs > 0)
      {
        return TRUE;
      }
    }

    return FALSE;
  }


}
?>
