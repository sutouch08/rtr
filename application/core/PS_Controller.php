<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class PS_Controller extends CI_Controller
{
  public $ugroup;
  public $uid;
  public $user;
  public $isAdmin = FALSE;
  public $home;
	public $error;

  public function __construct()
  {
    parent::__construct();

    $this->uid = get_cookie('uid');
    //--- check is user has logged in ?
    _check_login($this->uid);

    $this->user = $this->user_model->get_user_by_uid($this->uid);
    //--- get permission for user
    $this->ugroup = $this->user->ugroup;
    $this->isAdmin = $this->ugroup === 'admin' ? TRUE : FALSE;

  }



  public function _response($sc = TRUE)
	{
		echo $sc === TRUE ? 'success' : $this->error;
	}


  public function deny_page()
  {
    return $this->load->view('deny_page');
  }


  public function error_page()
  {
    return $this->load->view('page_error');
  }
}

?>
