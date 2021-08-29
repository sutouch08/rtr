<?php
class Stock_model extends CI_Model
{
  public function __construct()
  {
    parent::__construct();
  }

  public function get_stock($itemCode, $whsCode)
  {
    $rs = $this->ms
    ->select('OnHand, IsCommited, OnOrder, StockValue')
    ->where('ItemCode', $itemCode)
    ->where('WhsCode', $whsCode)
    ->get('OITW');

    if($rs->num_rows() > 0)
    {
      return $rs->row();
    }

    return NULL;
  }


  public function get_committed_stock($itemCode, $whsCode)
  {
    $rs = $this->ms
    ->select_sum('IsCommited', 'committed')
    ->where('ItemCode', $itemCode)
    ->where('WhsCode', $whsCode)
    ->get('OITW');

    if($rs->num_rows() === 1)
    {
      return $rs->row()->committed;
    }

    return 0;
  }


  public function get_stock_each_warehouse($itemCode, $whList = NULL)
  {
    $this->ms
    ->select('WhsCode')
    ->select('(OnHand - IsCommited) AS OnHandQty')
    ->where('ItemCode', $itemCode);

    if(!empty($whList) && is_array($whList))
    {
      $this->ms->where_in('WhsCode', $whList);
    }

    $this->ms->order_by('WhsCode', 'ASC');

    $rs = $this->ms->get('OITW');

    if($rs->num_rows() > 0)
    {
      return $rs->result();
    }

    return NULL;
  }



}
?>
