<?php
class User_model extends CI_Model
{
  public function __construct()
  {
    parent::__construct();
  }


  public function get($id)
  {
    $rs = $this->db->where('id', $id)->get('user');
    if($rs->num_rows() === 1)
    {
      return $rs->row();
    }

    return NULL;
  }



  public function add(array $ds = array())
  {
    return $this->db->insert('user', $ds);
  }


  public function update($id, array $ds = array())
  {
    if(!empty($ds))
    {
      $this->db->where('id', $id);

      return $this->db->update('user', $ds);
    }

    return FALSE;
  }

  public function delete($id)
  {
    return $this->db->where('id', $id)->delete('user');
  }


  function count_rows(array $ds = array())
  {
    if(!empty($ds['uname']))
    {
      $this->db->like('uname', $ds['uname']);
    }

    if(!empty($ds['name']))
    {
      $this->db->like('name', $ds['name']);
    }

    if(!empty($ds['ugroup']) && $ds['ugroup'] !== 'all')
    {
      $this->db->where('ugroup', $ds['ugroup']);
    }

    if(isset($ds['status']) && $ds['status'] != "all")
    {
      $this->db->where('status', $ds['status']);
    }

    return $this->db->count_all_results('user');
  }





  function get_list(array $ds = array(), $perpage = 20, $offset = 0)
  {

    if(!empty($ds['uname']))
    {
      $this->db->like('uname', $ds['uname']);
    }

    if(!empty($ds['name']))
    {
      $this->db->like('name', $ds['name']);
    }

    if(!empty($ds['ugroup']) && $ds['ugroup'] !== 'all')
    {
      $this->db->where('ugroup', $ds['ugroup']);
    }

    if(isset($ds['status']) && $ds['status'] != "all")
    {
      $this->db->where('status', $ds['status']);
    }

    $this->db->order_by('uname', 'ASC')->limit($perpage, $offset);

    $rs = $this->db->get('user');

    if($rs->num_rows() > 0)
    {
      return $rs->result();
    }

    return NULL;
  }



  public function get_user_by_uid($uid)
  {
    $rs = $this->db->where('uid', $uid)->get('user');
    if($rs->num_rows() === 1)
    {
      return $rs->row();
    }

    return FALSE;
  }


  public function get_user_group($uid)
  {
    $rs = $this->db->select('ugroup')->where('uid', $uid)->get('user');
    if($rs->num_rows() === 1)
    {
      return $rs->row()->ugroup;
    }

    return NULL;
  }


  
  public function get_user_credentials($uname)
  {
    $this->db->where('uname', $uname);
    $rs = $this->db->get('user');

    if($rs->num_rows() === 1)
    {
      return $rs->row();
    }

    return FALSE;
  }


  public function verify_uid($uid)
  {
    $rs = $this->db->select('uid')->where('uid', $uid)->where('status', 1)->get('user');
    return $rs->num_rows() === 1 ? TRUE : FALSE;
  }


  public function is_exists_uname($uname, $old_uname = NULL)
  {
    $this->db->where('uname', $uname);

    if(!empty($old_uname))
    {
      $this->db->where('uname !=', $old_uname);
    }

    $rs = $this->db->get('user');

    if($rs->num_rows() > 0)
    {
      return TRUE;
    }

    return FALSE;
  }


  public function change_password($id, $pwd)
  {
    $this->db->set('pwd', $pwd);
    $this->db->where('id', $id);
    return $this->db->update('user');
  }


} //---- End class

 ?>
