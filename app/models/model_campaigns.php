<?php
class Model_Campaigns extends Model {

  public function get_data() {
    $con = new mysqli(DB_HOST, DB_USER, DB_PASS, DB_NAME);
    if ($con->connect_errno) {
      printf("Connect failed: %s\n", $con->connect_error);
      exit();
    }
    $query = "SELECT * FROM `campaigns`";
    if( $result = $con->query($query) ) {
      $table = "<table class=\"display table table-condensed table-striped table-hover table-bordered pull-left\">";
      $table .= "<tr> <th>ID</th><th>Source</th><th>Cost</th><th>Status</th></tr>";
      while ($row = $result->fetch_assoc()) {
        $table .= "<tr>";
        $table .= "<td>" . $row['id'] . "</td><td>" . $row['source'] . "</td><td>" . $row['cost'] . "<td>" . $row['status'] . "</td> ";
        $table .= "<tr>";
      }
      $table .= "</table>";
      $result->free();
    } else die ("Database access failed: " . $con->error);
      $con->close();
      return $table;
  }
  public function generateembed($campaign_id){
    $sql = "SELECT lead_fields.name, lead_fields.key, lead_fields.type, lead_fields.object, campaign_lead_fields_rel.is_active, campaign_lead_fields_rel.mandatory";
    $sql .= " FROM `campaign_lead_fields_rel`";
    $sql .= " LEFT JOIN `lead_fields` ON lead_fields.id = campaign_lead_fields_rel.field_id";
    $sql .= " WHERE campaign_lead_fields_rel.campaign_id=$campaign_id";
    $sql_source = "SELECT * FROM `campaigns` WHERE id='$campaign_id'";
    $con = $this->db();
    $res = $con->query($sql_source);
//    var_dump($sql_source);
    $source = $res->fetch_assoc();
    $source = $source["source"];
    $result = $con->query($sql);
    if($result) {
      $textarea = "<pre>\n ";
      $textareaspec = '<form action="' .__HOST__.  '/api/in" method="post" >' . "\n";
      $textareaspec .= '<input type="hidden" name="source" value="'.$source.'" />' ."\n";
      while ($row = $result->fetch_assoc()) {
        if ($row["is_active"]) {
          $textareaspec .= '<div class="form-group">' ."\n";
          $textareaspec .= '  <label for="' . $row["key"] . '">' . $row["name"] . '</label>' . "\n";
//          if ($row["type"] == "text") {
            $textareaspec .= '  <input type="text" class="form-control" name="' . $row["key"] . '" placeholder="' . $row["name"] . '" ' . (($row['mandatory'] == 1) ? 'required' : '') . ">\n";
//          } else {
//            $textareaspec .= $row["object"] ."\n";
//          }
          $textareaspec .= "</div>\n";
        }
      }
      $textareaspec .= '<input type="submit" class="btn btn-primary" value="SUBMIT">'."\n";
      $textareaspec .= "</form> \n";
      $textarea .= htmlspecialchars($textareaspec, ENT_QUOTES);
      $textarea .= " </pre>";
      $result->free();
    } else die ("Database access failed: " . $con->error);
//    $rows = $result->num_rows;
      $con->close();
      return $textarea;
  }

  public function generetefields($campaign_id) {
    $field_ids = array();
    $sql = 'SELECT id FROM `lead_fields`';
    $db = $this->db();
    $res = $db->query($sql);
    if ($res){
        while ($row = $res->fetch_array()){
            $field_ids[] = $row['id'];
        }
    }
    $sql = 'INSERT INTO `campaign_lead_fields_rel`';
    $sql.= ' (campaign_id, field_id)';
    $sql.= ' VALUES ';
    foreach ($field_ids as $field_id){
        $sql.= '('.$campaign_id.','.$field_id.'),';
    }
    $sql = rtrim($sql, ',');

    $db->query($sql);
    $db->close();
  }
  public function fields_model($campaign_id) {
    $sql = 'SELECT lead_fields.name, campaign_lead_fields_rel.is_active, campaign_lead_fields_rel.mandatory';
    $sql.= ' FROM `campaign_lead_fields_rel`';
    $sql.= ' LEFT JOIN `lead_fields` ON lead_fields.id = campaign_lead_fields_rel.field_id';
    $sql.= ' WHERE campaign_lead_fields_rel.campaign_id = '.$campaign_id;
    $table="";
    if( $result = $this->db()->query($sql) ) {
    $field_id = 1;
    while($r = $result->fetch_assoc()){
      $table.="<tr>";
      foreach($r as $k => $v) {
        $table.='<td>';
        if ($k == 'is_active'){
          $table.= '<input type="checkbox" attr-field-id="'.$field_id.'" attr-campaign-id="'.$campaign_id.'" value="'.$v.'" class="is_active" data-size="small" '.(($v == 1)?'checked':'').'>' ;
        } else if($k == 'mandatory') {
          $table.= '<input type="checkbox" attr-field-id="'.$field_id.'" attr-campaign-id="'.$campaign_id.'" value="'.$v.'" class="mandatory" data-size="small" data-on-text="YES" data-off-text="NO" '.(($v == 1)?'checked':'').'>' ;
        } else if($k == 'name') { $table.= $v; }
        $table.='</td>';
      }
      $table.="</tr>";
      $field_id++;
    }
    $this->db()->close();
    return $table;
    }
  }
  public function getPlans($id=false) {
    $con = $this->db();
    $start = ($_POST["st"])?strtotime($_POST["st"]):strtotime(date('Y-m-01'));
    $end = ($_POST["en"])?strtotime($_POST["en"]) + 86400:time();
    $sql = "SELECT ca.name, le.id, rel.state from campaigns as ca LEFT JOIN leads le on ca.id=le.campaign_id LEFT JOIN leads_lead_fields_rel rel on le.id=rel.id WHERE le.datetime between '".$start."' AND '".$end."'";
    //var_dump($sql);
    if($id) $sql.=" AND ca.id='".$id."'";
    $sql1="select * from campaigns";
    if($id) $sql1.=" WHERE id='".$id."'";
    $res1=$con->query($sql1);
    $res=$con->query($sql);
    $result=array();
        while ($row = $res->fetch_assoc()) {
      $result[$row['name']][$row['state']]['count']++;
      }
    while ($row1 = $res1->fetch_assoc()) {
      $result[$row1['name']]['id']=$row1['id'];
      $result[$row1['name']]['NSW']['plan']=$row1['NSW'];
      $result[$row1['name']]['QLD']['plan']=$row1['QLD'];
      $result[$row1['name']]['SA']['plan']=$row1['SA'];
      $result[$row1['name']]['TAS']['plan']=$row1['TAS'];
      $result[$row1['name']]['VIC']['plan']=$row1['VIC'];
      $result[$row1['name']]['WA']['plan']=$row1['WA'];
    }
    $con->close();
    return $result;
    //return $sql;
  }

  public function plans_update(){
    $con = $this->db();
    $sql="UPDATE campaigns SET ".$_POST['state']."='".$_POST['value']."' WHERE id='".$_POST['campaign']."'";
    print $sql;
    $con->query($sql);
  }
  public function getAllCampaigns()
  {
    $con=$this->db();
    $sql="SELECT id, name FROM campaigns";
    $res=$con->query($sql);
    if($res)
    {
      while($row=$res->fetch_assoc())
      {
        $result[] = $row;
      }
        return $result;
    }
    return false;
  }
  public function getDashboard($id,$begin,$end)
  {
    $begin=strtotime($begin);
    $end=strtotime($end)+86400;

    //reports
    $dashboard=[
    'totalLeadsCome'=>0,
    'totalRejections'=>0,
    'averageSalesPerLead'=>0,
    'grossIncome'=>0,
    'cost'=>0,
    'profitOnlyAccepted'=>0,
    'profitWithPendings'=>0];
    //end of reports
    //Temporary variables
    $tempAverage;
    $tempCost;
    $tempAcceptedRejectionsAmount;
    $tempPendingRejections;
    //End of temporary variables

    $con=$this->db();
    $sql="SELECT count(le.id), sum(cam.cost) FROM leads le left join campaigns cam on le.campaign_id=cam.id WHERE le.campaign_id='".$id."' and le.datetime between '".$begin."' and '".$end."'";
    $sql1="SELECT cam.cost as 'pay_to_affiliate', le.datetime as 'time_came_from_affiliate', rej.lead_id, rej.client_id, cli.lead_cost as 'cost_to_client', rej.approval from campaigns cam right join leads le on cam.id=le.campaign_id left join leads_rejection rej on le.id=rej.lead_id left join leads_delivery led on rej.id=led.id left join clients cli on rej.client_id=cli.id where cam.id='".$id."' and led.timedate between '".$begin."' and '".$end."'";
    $res=$con->query($sql);
    if($res)
    {
      //$dashboard['totalLeadsCome']=$res->num_rows();
      $row=mysqli_fetch_assoc($res);
      $dashboard['totalLeadsCome']=$row['count(le.id)'];
      $dashboard['cost']=$row['sum(cam.cost)'];
      //$tempCost=$row['sum(cam.cost)'];
    }
    $res1=$con->query($sql1);
    //return $sql1;
    if($res1)
    {
      while($row=$res1->fetch_assoc())
      {
      if($row['approval']=='0')
      {
        $dashboard['totalRejections']++;
        $tempAcceptedRejectionsAmount+=$row['cost_to_client'];
      }
      if($row['approval']=='1')
      {
        $tempAverage++;
        $dashboard['grossIncome']+=$row['cost_to_client'];
      }
      if($row['approval']=='2' || $row['approval']=='4')
      {
        $tempPendingRejections+=$row['cost_to_client'];
      }
    //return $row;
      }
      $dashboard['averageSalesPerLead']=$tempAverage/$dashboard['totalLeadsCome'];
      //$dashboard['cost']=$tempCost*$dashboard['totalLeadsCome'];
      $dashboard['profitOnlyAccepted']=$dashboard['grossIncome']-$dashboard['cost']-$tempAcceptedRejectionsAmount;
      $dashboard['profitWithPendings']=$dashboard['grossIncome']-$dashboard['cost']-$tempAcceptedRejectionsAmount-$tempPendingRejections;
      //return $tempAcceptedRejectionsAmount;
      return $dashboard;
    }
    return true;
  }
}
