<?php
class Uom_item_model extends CI_Model
{

  public function __construct()
  {
    parent::__construct();
  }

  //--- get item uom by id item
  public function get_item_uom($id)
  {
    $rs = $this->db
    ->select('uom_item.*')
    ->select('uom.name AS uom_name')
    ->from('uom_item')
    ->join('uom', 'uom_item.uom_id = uom.id', 'left')
    ->where('item_id', $id)
    ->get();

    if($rs->num_rows() > 0)
    {
      return $rs->result();
    }

    return NULL;
  }



  public function add(array $ds = array())
  {
    if(!empty($ds))
    {
      return $this->db->insert('uom_item', $ds);
    }

    return FALSE;
  }



  public function drop_current_item_uom($id)
  {
    return $this->db->where('item_id', $id)->delete('uom_item');
  }



}
?>
