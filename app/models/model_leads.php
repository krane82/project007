<?php
class Model_Leads extends Model {
  private $api;
  function __construct($api)
  {
    $this->api = $api;
  }
  public function getLeadSources() {
    $con = $this->db();
    $LeadSources = array();
    $sql = "SELECT `source`, `name` FROM `campaigns`";
    $res = $con->query($sql);
    while ($row = $res->fetch_assoc()){
      $LeadSources[] = $row;
    }
    return $LeadSources;
  }
  public function getAllClients()
  {
    $con = $this->db();
    $clients = array();
    $sql = "SELECT `id`, `campaign_name` FROM `clients`";
    $res = $con->query($sql);
    while ($row = $res->fetch_assoc()) {
      $clients[] = $row;
    }
    $con->close();
    return $clients;
  }
  public function getConvForLead()
  {
    $con=$this->db();
    //return var_dump($_POST);
    $leadId=$_POST['lead_id'];
    $author=$_SESSION['user_id'];
    $sql="SELECT us.full_name, con.id, con.author, con.message, con.seen, con.time FROM lead_conversations con JOIN users us ON con.author=us.id WHERE con.lead_id='$leadId' ORDER BY con.time";
    $result=array();
    $res=$con->query($sql);
    if($res)
    {
      while($row=$res->fetch_assoc())
      {
        $result['conversations'][]=$row;
      }
      $whoSeen=explode(',',$result['conversations'][0]['seen']);
      if(!in_array($author,$whoSeen)) {
        $ins = $result['conversations'][0]['id'];
        $sql1 = "UPDATE `lead_conversations` SET `seen`=CONCAT(`seen`,'$author,') WHERE id='$ins'";
        $con->query($sql1);
      }
      $result['leadid']=$leadId;
      $result['author']=$author;
      return $result;
    }
    return false;
  }
  public function addConv()
  {
    $con=$this->db();
    $leadId=$_POST['leadId'];
    $userId=$_POST['userId'];
    $message=$_POST['message'];
    $message=$this->clean($message);
    $sql="INSERT INTO lead_conversations (lead_id, author, message, seen) VALUES ('$leadId', '$userId', '$message','$userId,')";
    $res=$con->query($sql);
    $sql1="SELECT id FROM lead_conversations WHERE lead_id='$leadId' LIMIT 1";
    $res1=$con->query($sql1);
    $result=$res1->fetch_assoc();
    $ins=$result['id'];
    if($ins)
    {
      $con->query("UPDATE lead_conversations SET seen='$userId,' WHERE id='$ins'");
    }
    if($res)
    {
      return true;
    }
    return false;
  }
  public function sendLeadsFromCSV()
  {
    $api=new Model_Api();
    $arr=$_POST['array'];
    $arr=urldecode($arr);
    $arr=unserialize($arr);
    $str='';
    foreach($arr as $item)
    {
      $str.=$api->proccess_lead($item).'<br>';
    }
    return $str;
  }
  private function getLeadInfo($id)
  {
    $con = $this->db();
    $sql = "SELECT * FROM leads_lead_fields_rel WHERE id='".$id."'";
    $res = $con->query($sql);
    if($res) {
      if ($r = $res->fetch_assoc()) return $r;
      return false;
    }
  }
  private function getClientById($id)
  {
    $con = $this->db();
    $sql = 'SELECT cc.id, c.email, c.full_name, cc.postcodes';
    $sql.= ' FROM `clients_criteria` as cc';
    $sql.= ' LEFT JOIN `clients` as c ON cc.id = c.id';
    $sql.= " WHERE cc.id=$id";
    $res = $con->query($sql);
    //return $sql;
    if ($res) {
      $client = $res->fetch_assoc();
    } else {
      return 'Db error when trying to get client email';
    }
    return $client;
  }
  public function senManyLeads($client_id, $start, $end, $state, $source)
  {
    $data=$this->getList($start, $end, $state, $source);
    $i=0;
    if($client_id!=0) {
      $c = $this->getClientById($client_id);
      foreach ($data as $lead_id) {
        if ($x = $this->senLeadToCurrent($client_id, $lead_id,$c)) print $x . '<br>';
        else {
          print 'Error!';
        }
        $i++;
      }
    }
    else {
      foreach ($data as $lead_id) {
        if ($x = $this->senLeadToAll($lead_id)
        ) print $x . '<br>';
        else {
          print 'error!';
        }
        $i++;
      }
    }
    print $i.' Leads done';
  }
  public function senOneLead($client_id,$lead_id, $camp_id=0, $reroute = false)
  {
    if($client_id!=0) {
      $c = $this->getClientById($client_id);
      if($reroute) {
      if ($x = $this->senLeadToCurrent($client_id, $lead_id, $c, $camp_id, $reroute = true)) print $x;
      else {            print 'Error1!';
      }
    } else {
          if ($x = $this->senLeadToCurrent($client_id, $lead_id, $c, $camp_id)) print $x;
          else {
            print 'Error2!';
          }
      }
    } else {
      if ($x = $this->senLeadToAll($lead_id)) print $x;
      else {
        print 'Error!';
      }
    }
  }
  private function senLeadToCurrent($client_id, $lead_id, $c, $camp_id=0, $reroute = false)
  {
    $receivers=$this->getLeadFromDelivered($lead_id);
    $counter = count($receivers);
    if($counter>=4) return 'This lead already sent 4 times';
    $leadInfo = $this->getLeadInfo($lead_id);
    $postcodes=explode(',',$c['postcodes']);
    $postcodesLen = count($postcodes);
    for ($i=0;$i<$postcodesLen;$i++){$postcodes[$i] = trim($postcodes[$i]);}
    if (!in_array($leadInfo['postcode'],$postcodes)) return 'This client is unmatched to receive this lead';
    if(in_array($client_id, $receivers )) return "This client already has this lead";

      $readyLeadInfo = prepareLeadInfo($leadInfo);

      if (!$reroute) {
        $camp_id = $this->checkClientsLimits($client_id, $leadInfo);
      }

      if($camp_id OR $reroute) {
        $delivery_id = $this->getLastDeliveryID() + 1;
        $sent = $this->sendToClient($c["email"], $readyLeadInfo, $c["full_name"],$delivery_id);
        if($sent) {
          $this->addToDeliveredTable($client_id, $lead_id, $readyLeadInfo, $camp_id);
          return "Lead $lead_id sent to ".$c['full_name'];
        } else {
          return "mail error: $sent";
        }
      } else {
        return "mail error: $sent";
      }
    }

  private function senLeadToAll($lead_id)
  {
    $receivers=$this->getLeadFromDelivered($lead_id);
    $counter = count($receivers);
    if($counter>=4) return 'This lead already sent 4 times';
    $leadInfo = $this->getLeadInfo($lead_id);
    $state=$leadInfo['state'];
    if(!$clients = $this->api->getClients($leadInfo)) return "No clients matches for this lead, or they are inactive";
    $result = $this->sendToClients($clients, $lead_id, $leadInfo);
    return $result;
  }
  private function getLeadFromDelivered($id)
  {
    $con = $this->db();
    $sql="SELECT client_id FROM leads_delivery WHERE lead_id=".$id;
    $res=$con->query($sql);
    $result=array();
    if($res)
    {
      while ($row = $res->fetch_array())
      {
        $result[] = $row['client_id'];
      }
      return $result;
    }
    return false;
  }
  private function getList($start, $end, $state=false, $source=false)
  {
    $con = $this->db();
    $sql = "SELECT l.id FROM leads AS `l` LEFT JOIN `leads_lead_fields_rel` AS `lf` ON `lf`.`id`=`l`.`id` WHERE l.datetime BETWEEN '".$start."' AND '".$end."'";
    if ($state) $sql.=" AND lf.state='".$state."'";
    if ($source) $sql.=" AND lf.source='".$source."'";
    $res=$con->query($sql);
    //return $sql;
    if($res) {
      while ($row = $res->fetch_assoc()) {
        $result[] = $row['id'];
      }
      return $result;
    }
    return false;
  }
  private function sendToClients($clients, $lead_id ,$p){
    $receivers=$this->getLeadFromDelivered($lead_id);
    $counter = count($receivers);
    if($counter>=4)
    {
      return 'This lead already sent 4 times';
    }
    $readyLeadInfo = prepareLeadInfo($p);
    $delivery_id = $this->getLastDeliveryID();
    $sentTo = '';
    foreach ($clients as $c) {
      $id = $c["id"];
      if(in_array($id,$receivers))
      {
        $sentTo.="User $c[full_name] already has this lead<br>";
        continue;
      }
      $camp_id = $this->checkClientsLimits($id, $p);
      if($camp_id) {
        $sent = $this->sendToClient($c["email"], $readyLeadInfo, $c["full_name"],$delivery_id);
        if($sent) {
          $counter++;
          $sentTo .= "Lead #$lead_id sent to $c[full_name] : $c[email]<br>\n";
          $this->api->addToDeliveredTable($id, $lead_id, $readyLeadInfo, $camp_id);
          $delivery_id+=1;
          // return 'Lead sent and added to database';
        }
        else
        {
          $sentTo.= 'for some reason lead can not be sent<br>';
        }
      }
      else {
        $sentTo = "Weekly limit of client $c[full_name] is out<br>";
      }
    }
    return $sentTo;
  }
  private function getLastDeliveryID(){
    $sql = "SELECT `id` FROM leads_delivery ORDER BY `id` DESC LIMIT 1";
    $db  = DB::getInstance();
    $res = $db->get_row($sql);
    return $res[0];
  }
  private function sendToClient($mail, $p, $client_name, $track_id)
  {
    if($mail) {
      send_m($mail, $p, $client_name, $track_id, '', '');
      return TRUE;
    }
    return FALSE;
  }
  public function checkClientsLimits($id, $p)
  {
    $con = $this->db();
    $monday = strtotime("Monday this week");
    $dayNow = strtotime("today");
    $quanDays = ($dayNow - $monday)/86400;
    $sql1 = "select * from client_campaigns where postcodes like '%".$p['postcode']."%' and client_id=".$id;
    $res=$con->query($sql1);
    if ($res && $res->num_rows!=0)
    {
      while ($row=$res->fetch_assoc()) {
        $campAr[] = $row;
      }
      foreach ($campAr as $camp) {
        $sql2 = "select count(id) from leads_delivery where camp_id = ".$camp['id']." AND timedate BETWEEN '".$dayNow."' AND current_timestamp";
        $result=$con->query($sql2);
        if ($row=$result->fetch_assoc())
        {
          if($row['count(id)'] < (int)($camp['weekly']/5))
          {
            return $camp['id'];
          }
          else {echo "day limits for this company is already done";continue;} 
        }
      }
    }
    else 
    {
      echo "this client $id has no campaigns with such postcodes";
      return FALSE;
    }
  }
  private function getClients($post){
    $clients = array();
    $con = $this->db();
    if( !empty($post["state"]) || !empty($post["postcode"]) ){
      $sql = 'SELECT cc.id, c.email, c.full_name';
      $sql.= ' FROM `clients_criteria` as cc';
      $sql.= ' LEFT JOIN `clients` as c ON cc.id = c.id';
      $sql .= ' WHERE cc.states_filter LIKE "%' . $post["state"] . '%" AND cc.postcodes LIKE "%'.$post["postcode"].'%"';
      $sql .= ' AND c.status = 1';
      $sql .= ' ORDER BY c.lead_cost DESC';
    } else {
      return FALSE;
    }
    $res = $con->query($sql);
    if ($res) {
      while( $res->fetch_assoc())
      {
        foreach ($res as $k=>$v) {
          $clients["$k"] = $v;
        }
      }
    } else {
      echo "<br>no clients for this lead criteria<br>";
      return FALSE;
    }
    return $clients;
  }
  private function checkdata($post){
    $p = array();
    foreach ($post as $k => $v) {
      if ($k=="phone") {
        $p["phone"] = phone_valid($v);
      } else if($k=="postcode"){
        $p["postcode"] = (int)postcodes_valid($v);
      }
      else {
        $p["$k"] = trim($v);
      }
    }
    return $p;
  }
  private function clean($item)
  {
    $item=htmlspecialchars($item);
    $item=addslashes($item);
    $item=trim($item);
    return $item;
  }

  public function addToDeliveredTable($client_id, $lead_id, $p, $camp_id){
    $con = $this->db();
    $now = time();
    $sql = "INSERT INTO `leads_delivery` (lead_id, client_id, timedate, camp_id) VALUES ('".$lead_id."', '".$client_id."', '".$now."', '".$camp_id."')";
   // var_dump($sql);
    $sql_r = "INSERT INTO `leads_rejection` (lead_id, client_id, date, approval, camp_id) VALUES ('".$lead_id."', '".$client_id."', '".$now."', '1', '".$camp_id."')";
   // var_dump($sql_r);
    if($con->query($sql) && $con->query($sql_r)) { $delivered=1; }
    if($delivered){
      return TRUE;
    } else {
      return FALSE;
    }
  }
}
