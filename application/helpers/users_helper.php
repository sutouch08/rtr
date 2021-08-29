<?php
function _check_login($uid = NULL)
{
  $CI =& get_instance();
  
  if($uid === NULL OR $CI->user_model->verify_uid($uid) === FALSE)
  {    
    redirect(base_url().'authentication');
    exit;
  }
  
}

 ?>
