<?php
class Uom_model extends CI_Model
{

  public function __construct()
  {
    parent::__construct();
  }


  public function get($id)
  {
    $rs = $this->db->where('id', $id)->get('uom');
    if($rs->num_rows() === 1)
    {
      return $rs->row(); 
    }

    return NULL;
  }


  public function get_name($id)
  {
    $rs = $this->db->where('id', $id)->get('uom');
    if($rs->num_rows() === 1)
    {
      return $rs->row()->name;
    }

    return NULL;
  }



  public function add(array $ds = array())
  {
    if(!empty($ds))
    {
      $rs = $this->db->insert('uom', $ds);
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
      return $this->db->where('id', $id)->update('uom', $ds);
    }

    return FALSE;
  }


  public function delete($id)
  {
    return $this->db->where('id', $id)->delete('uom');
  }


  public function count_rows(array $ds = array())
  {
    if(!empty($ds['name']))
    {
      $this->db->like('name', $ds['name']);
    }

    return $this->db->count_all_results('uom');
  }


  public function get_list(array $ds = array(), $perpage = 20, $offset = 0)
  {
    if(!empty($ds['name']))
    {
      $this->db->like('name', $ds['name']);
    }

    $rs = $this->db->order_by('name', 'ASC')->limit($perpage, $offset)->get('uom');

    if($rs->num_rows() > 0)
    {
      return $rs->result();
    }


    return NULL;
  }



  public function is_exists_name($name, $old_name = NULL)
  {
    $this->db->select('id')->where('name', $name);

    if(!empty($old_name))
    {
      $this->db->where('name !=', $old_name);
    }

    $rs = $this->db->get('uom');

    if($rs->num_rows() > 0)
    {
      return TRUE;
    }

    return FALSE;
  }


  public function get_all_uom_list()
  {
    $rs = $this->db->get('uom');

    if($rs->num_rows() > 0)
    {
      return $rs->result();
    }

    return NULL;
  }


}
?>
