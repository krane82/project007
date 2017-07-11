<?php
class Model_Clients extends Model {

  public function get_data() {
    $table = "<table id='clients' class=\"display table table-condensed table-striped table-hover table-bordered clients responsive pull-left\">";
      $table .= "<thead><tr><th>ID</th><th>Campaign name</th><th>Email</th><th>Full Name</th><th>Lead Cost</th><th>Delivery</th><th>Status</th><th>Actions</th></tr></thead>";
      $table .= "</table>";
      return $table;
  }
  public function getCoords()
  {
    $con = $this->db();
    $sql="SELECT cliCamp.coords FROM clients cli LEFT JOIN client_campaigns cliCamp on cli.id=cliCamp.client_id WHERE cli.status='1'";
    $res=$con->query($sql);
    //return $sql;
    $result=array();
    if ($res)
    {
      while($row=$res->fetch_array())
      {
        if($row[0])
        {
          $result[] = explode(':',$row[0]);
        }
      }
      return $result;
    }
    return false;
  }
public function getCover()
{
  $con = $this->db();
  $sql="SELECT cliCamp.postcodes FROM clients cli LEFT JOIN client_campaigns cliCamp on cli.id=cliCamp.client_id WHERE cli.status='1'";
  $res=$con->query($sql);
  $result=array();
  $result1=array();
if($res)
{
  while($row=$res->fetch_array())
  {
    $arr=explode(',',$row[0]);
    $arr=array_unique($arr);
    foreach($arr as $item)
    {
      $result[trim($item)]++;
    }
  }
  foreach($result as $key=>$item)
  {
    $result1[$item][]=$key;
  }
  ksort($result1);
  return $result1;
}
  return false;
}

}
