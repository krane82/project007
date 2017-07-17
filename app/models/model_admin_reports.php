<?php

class Model_Admin_Reports extends Model

{

  private $sql_sources = "SELECT `lf`.`id` as `id`, `lf`.`full_name` as `Full name`, lf.email, lf.phone, DATE_FORMAT(FROM_UNIXTIME(`l`.`datetime`), \"%e %b %Y\" ) AS `Date`,

`lf`.`state` as `state`,

`c`.`name` as `Source`,

 `lf`.`address`, `lf`.`city`,  `lf`.`state`,  `lf`.`postcode`, `lf`.`suburb`, `lf`.`system_size`, `lf`.`roof_type`, `lf`.`electricity`, `lf`.`house_age`, `lf`.`house_type`, `lf`.`system_for`, `lf`.`note`

FROM `leads` as `l`

LEFT JOIN `campaigns` as c ON `l`.`campaign_id` = `c`.`id`

LEFT JOIN `leads_lead_fields_rel` as `lf` ON `lf`.`id`=`l`.`id`

WHERE 1=1 AND (`l`.`datetime` BETWEEN 1488027600 AND 1488891600)";



  public function getLeadSources()

  {

    $con = $this->db();

    $LeadSources = array();

    $sql = "SELECT `source`, `name` FROM `campaigns`";

    $res = $con->query($sql);

    while ($row = $res->fetch_assoc()) {

      $LeadSources[] = $row;

    }

    $con->close();

    return $LeadSources;

  }



  public function getClients()

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



  public function getRejected()

  {

    $con = $this->db();

    $client = $_REQUEST["client"];

    $start = strtotime($_REQUEST["start"]);

    $end = strtotime($_REQUEST["end"]) + 86400;

    $timestamp = time();

    $state = $_REQUEST["State"];

    if(empty($_REQUEST["start"])){

      $start = strtotime("midnight", $timestamp);

      $end = strtotime("tomorrow", $start) - 1;

    }

    // get approved rejected leads and sum

    $sql = 'SELECT lf.id as `id`, lf.full_name as `Full name`, lf.email, lf.phone, DATE_FORMAT(FROM_UNIXTIME(`ld`.`timedate`), "%e %b %Y %h:%i:%s" ) AS `Date`, lr.reason as `Rejection reason`,';

    $sql .= ' `lf`.`address`, `lf`.`suburb`,  `lf`.`state`, `lf`.`postcode`, `lf`.`system_size`, `lf`.`roof_type`, `lf`.`electricity`, `lf`.`house_age`, `lf`.`house_type`, `lf`.`system_for`, `lf`.`note`';

    $sql .= ' FROM `leads_delivery` as ld ';

    $sql .= ' LEFT JOIN `clients` as c ON ld.client_id = c.id';

    $sql .= ' LEFT JOIN `leads_rejection` as lr ON lr.id = ld.id';

    $sql .= ' LEFT JOIN `leads_lead_fields_rel` as lf ON lf.id = lr.lead_id';

    $sql .= ' WHERE lr.approval = 0';

    $sql .= ' AND (ld.timedate BETWEEN '.$start.' AND '.$end.')';

    if (!($client == 0)) {

      $sql .= ' AND ld.client_id =' . $client;

    }

    if ($state) {

      $sql .= " AND lf.state = '$state'";

    }
    $res = $con->query($sql);

    $rejected = array();

    if ($res) {
      $prearr = array();
      $i=0;
      while($line = $res->fetch_assoc()){
        if(!$i)
        {
          $i=!$i;
          $prearr=array();
          foreach($line as $k=>$v){

            $prearr[] = $k;

          }
          $rejected[] = $prearr;
        }
        $line['address'] = str_replace(array("\r","\n"), "", $line['address']);

        $line['note'] = str_replace(array("\r","\n"), "", $line['note']);

        $line['phone']='Ph: '.$line['phone'];

        $rejected[] = $line;

      }

    } else {

      echo "No data\n";

    }

    return $rejected;

  }



  public function getReceived()

  {

    $source = $_REQUEST["source"];

    $state = $_REQUEST["State"];

    $start = strtotime($_REQUEST["start"]);

    $end = strtotime($_REQUEST["end"]) + 86400;

    $campaign_id = getCampaignID($source);

    $timestamp = time();

    if(empty($_REQUEST["start"])){

      $start = strtotime("midnight", $timestamp);

      $end = strtotime("tomorrow", $start) - 1;

    }

    $data = "(`l`.`datetime` BETWEEN $start AND $end)";

    $con = $this->db();



    $sql = 'SELECT lf.id as `id`, lf.full_name as `Full name`, lf.email, lf.phone, DATE_FORMAT(FROM_UNIXTIME(`l`.`datetime`), "%e %b %Y %h:%i:%s" ) AS `Date`, c.name as `Campaign`,';

    $sql .= ' `lf`.`address`, `lf`.`suburb`,  `lf`.`state`,  `lf`.`postcode`, `lf`.`system_size`, `lf`.`roof_type`, `lf`.`electricity`, `lf`.`house_age`, `lf`.`house_type`, `lf`.`system_for`, `lf`.`note`';

    $sql .= ' FROM `leads` as l ';

    $sql .= ' LEFT JOIN `leads_lead_fields_rel` as lf ON lf.id=l.id';

    $sql .= ' LEFT JOIN  `campaigns` as c ON c.id = l.campaign_id';

    $sql .= ' WHERE 1=1';

    $sql .= ' AND '. $data;

    if($campaign_id){

      $sql.= ' AND l.campaign_id = '.$campaign_id;

    }

    if ($state){

      $sql .= " AND lf.state = '$state'";

    }



    $res = $con->query($sql);

    $received = array();

    if ($res) {

      $col = $res->fetch_assoc();

      $prearr = array();

      foreach($col as $k=>$v){

        $prearr[] = $k;

      }

      $received[] = $prearr;

      $received[] = $col;

      while($line = $res->fetch_assoc()){

        $line['address'] = str_replace(array("\r","\n"), "", $line['address']);

        $line['note'] = str_replace(array("\r","\n"), "", $line['note']);

        $line['phone']='Ph: '.$line['phone'];

        $received[] = $line;

      }

    } else {

      echo "No data\n";

    }

    $con->close();

    return $received;

  }



  public function getAccepted()

  {

    $con = $this->db();

    $client = $_REQUEST["client"];

    $start = strtotime($_REQUEST["start"]);

    $end = strtotime($_REQUEST["end"]) + 86400;

    $timestamp = time();

    $state = $_REQUEST["State"];



    if(empty($_REQUEST["start"])){

      $start = strtotime("midnight", $timestamp);

      $end = strtotime("tomorrow", $start) - 1;

    }

    // get approved leads and sum

    $sql = 'SELECT lf.id as `id`, lf.full_name as `Full name`, lf.email, lf.phone, DATE_FORMAT(FROM_UNIXTIME(`ld`.`timedate`), "%e %b %Y %h:%i:%s" ) AS `Date`, c.campaign_name as `Client Name`, c.email as `Client Email`, ';

    $sql .= ' `lf`.`address`, `lf`.`suburb`,  `lf`.`state`,  `lf`.`postcode`, `lf`.`system_size`, `lf`.`roof_type`, `lf`.`electricity`, `lf`.`house_age`, `lf`.`house_type`, `lf`.`system_for`, `lf`.`note`';

    $sql .= ' FROM `leads_delivery` as ld ';

    $sql .= ' LEFT JOIN `clients` as c ON ld.client_id = c.id';

    $sql .= ' LEFT JOIN `leads_rejection` as lr ON lr.id = ld.id';

    $sql .= ' LEFT JOIN `leads_lead_fields_rel` as lf ON lf.id = ld.lead_id';

    $sql .= ' WHERE (lr.approval=1 OR lr.approval=3)';

    $sql .= ' AND (ld.timedate BETWEEN '.$start.' AND '.$end.')';

    if (!($client == 0)) {

      $sql .= ' AND ld.client_id =' . $client;

    }

    if($state){

      $sql .= " AND lf.state = '$state'";

    }

    //return var_dump($sql);

    $res = $con->query($sql);

    $approved = array();

    if ($res) {


    $i=0;
      while($line = $res->fetch_assoc()){

        if(!$i)
        {
          $i=!$i;
          $prearr=array();
          foreach($line as $k=>$v){

            $prearr[] = $k;

          }
          $approved[] = $prearr;
        }
        $line['address'] = str_replace(array("\r","\n"), "", $line['address']);

        $line['note'] = str_replace(array("\r","\n"), "", $line['note']);

        $line['phone']='Ph: '.$line['phone'];

        $approved[] = $line;

      }

    } else {

      echo "No data\n";

    }

    return $approved;

  }



  public function getDistributed()

  {

    $con = $this->db();



    $client = $_REQUEST["client"];

    $start = strtotime($_REQUEST["start"]);

    $end = strtotime($_REQUEST["end"]) + 86400;

    $timestamp = time();

    $state = $_REQUEST["State"];

    if(empty($_REQUEST["start"])){

      $start = strtotime("midnight", $timestamp);

      $end = strtotime("tomorrow", $start) - 1;

    }

    $sql="SELECT `led`.`id`, `lef`.`full_name` as 'Full name', `lef`.`email`,
     `lef`.`phone`, DATE_FORMAT(FROM_UNIXTIME(`led`.`timedate`), \"%e %b %Y %h:%i:%s\" )
      AS `Date`, `cli`.`campaign_name` as 'Client Name',  `lef`.`address`, `lef`.`suburb`,
       `lef`.`state`,  `lef`.`postcode`, `lef`.`system_size`, `lef`.`roof_type`,
        `lef`.`electricity`, `lef`.`house_age`, `lef`.`house_type`, `lef`.`system_for`, `lef`.`note`,
         DATE_FORMAT(FROM_UNIXTIME(`led`.`open_time`), \"%e %b %Y\" ) as `Open time`, `rej`.`approval` as `Status`
     FROM leads_delivery led LEFT JOIN leads_rejection rej on led.id=rej.id left join clients cli on led.client_id=cli.id
     left join leads_lead_fields_rel lef on led.lead_id=lef.id WHERE led.timedate BETWEEN '".$start."' AND '".$end."'
     ";



//    $sql = 'SELECT lf.id as `id`, lf.full_name as `Full name`, lf.email, lf.phone, DATE_FORMAT(FROM_UNIXTIME(`ld`.`timedate`), "%e %b %Y %h:%i:%s" ) AS `Date`, c.campaign_name as `Client Name`,';
//
//    $sql .= ' `lf`.`address`, `lf`.`suburb`, `lf`.`state`,  `lf`.`postcode`, `lf`.`system_size`, `lf`.`roof_type`, `lf`.`electricity`, `lf`.`house_age`, `lf`.`house_type`, `lf`.`system_for`, `lf`.`note`';
//
//    $sql .= ', DATE_FORMAT(FROM_UNIXTIME(`ld`.`open_time`), "%e %b %Y" ) as `Open time`, `lr`.`approval` as `Status` ';
//
//    $sql .= 'FROM `leads_delivery` as ld ';
//
//    $sql .= ' LEFT JOIN `clients` as c ON ld.client_id = c.id';
//
//    $sql .= ' LEFT JOIN `leads_rejection` as lr ON lr.lead_id = ld.lead_id AND lr.client_id = ld.client_id';
//
//    $sql .= ' INNER JOIN `leads_lead_fields_rel` as lf ON lf.id = ld.lead_id';
//
//    $sql .= ' WHERE 1=1';
//
//    $sql .= ' AND (ld.timedate BETWEEN '.$start.' AND '.$end.')';


//return var_dump($sql);
    if (!($client == 0)) {

      $sql .= ' AND led.client_id =' . $client;

    }

    if($state){

      $sql .= " AND lef.state = '$state'";

    }
    
    $res = $con->query($sql);

    $distributed = array();

//    if ($res) {
//
//      $col = $res->fetch_assoc();
//
//      $prearr = array();
//
//      foreach($col as $k=>$v){
//
//        $prearr[] = $k;
//
//      }

//
//    reset($res);
//      $distributed[] = $prearr;

//      $distributed[] = $col;
      $i=false;
      if($res){
      while($line = $res->fetch_assoc()){

        if(!$i)
        {
          $i=!$i;
          $prearr=array();
          foreach($line as $k=>$v){

            $prearr[] = $k;

          }
          $distributed[] = $prearr;
        }
        if($line['Open time']=='1 Jan 1970') $line['Open time']='Still not open :(';

        $line['address'] = str_replace(array("\r","\n"), "", $line['address']);

        $line['note'] = str_replace(array("\r","\n"), "", $line['note']);

        $line['phone']='Ph: '.$line['phone'];

        $line['Status']=formatReject($line['Status']);

        $distributed[] = $line;

      }

    } else {

      echo "No data\n";

    }



    return $distributed;

  }







  private function formStatView($string, $icon_class, $fn='fncsv', $color='')

  {

    $v = '

      <div class="col-md-2 '. $color .'">

        <div onclick="'.$fn.'()" class="panel panel-white pdten" style="cursor: pointer;">

      <i class="fa fa-'.$icon_class.' icon" aria-hidden="true"></i>' . $string .'

      </div> </div>';

    return $v;

  }




    public function getAverageReports()
    {
      $con = $this->db();

      $client = $_POST["client"];

      $state = $_REQUEST["State"];

      if (!empty($_POST["start"])) {

        $start = strtotime($_POST["start"]);

        $end = strtotime($_POST["end"]) + 86400;

      }
      $result = ['distributed' => 0,
          'accepted' => 0,
          'rejected' => 0,
          'rejectedPercent' => 0,
          'revenue' => 0,
          'pending' => 0];
      $sql = "SELECT led.id, rej.approval, cli.lead_cost
 FROM leads_delivery led left join leads_rejection rej
  on led.id=rej.id left join clients cli
   on led.client_id=cli.id left join leads_lead_fields_rel le on led.lead_id=le.id where led.timedate between '" . $start . "' and '" . $end . "'";
      if ($state) {
        $sql .= " and le.state='" . $state . "'";
      }
      if ($client) {
        $sql .= " and led.client_id='" . $client . "'";
      }
      //return print($sql);
      $res = $con->query($sql);
      if ($res) {
        while ($row = $res->fetch_assoc()) {
          $result['distributed']++;
          if ($row['approval'] == '1' || $row['approval'] == '3') {
            $result['accepted']++;
          }
          if ($row['approval'] == '0') {
            $result['rejected']++;
          }
          if ($row['approval'] != '0') {
            $result['revenue'] += $row['lead_cost'];
          }
          if ($row['approval'] == '2' || $row['approval'] == '4') {
            $result['pending']++;
          }
        }
        $result['rejectedPercent'] = $result['pending'] * 100 / $result['distributed'];
        // return $result;
        $ds = $result['distributed'] . " leads <br>Distributed";
        $acs = $result['accepted'] . " leads Accepted by clients";
        $ras = $result['rejected'] . " leads Rejected <br>by clients";
        $rejectedPercent = round($result['rejectedPercent'],2) . '% Rejected<br> by clients';
        $rev = $result['revenue'] . '$<br>Lead Revenue';
        $pend_rej = 'Pending<br>rejections: ' . $result['pending'];
        // $total_revenue = $distributednew["amount"]*$average;

        //  $total_revenue = $distributednew['client_cost'] - $rejectnew['client_cost'] - $resultrej['client_cost'];


        //  $ds =  $distributednew["amount"] . " leads <br>Distributed";

        // $ds_beg = "leads <br>Distributed 1 to 15 ";

        //  $acs = $approvednew['amount']. " leads Accepted by clients";

        //   $ras = $rejectnew["amount"] . " leads Rejected <br>by clients";

        //  $trs = $approvednew["total_cost"] . " total Revenue";

        //  $rejectedPercent =  number_format($rejectedP * 100, 0) . '% Rejected<br> by clients';

        //   $rev = $total_revenue ? $total_revenue . " $<br>Lead Revenue" : 0 . " $<br>Lead Revenue";

        echo $this->formStatView($ds, 'users', 'getDistributed');

        echo $this->formStatView($acs, 'check', 'getAccepted');

        echo $this->formStatView($ras, 'window-close', 'getRejected');

        echo $this->formStatView($rejectedPercent, 'window-close', 'getRejected');

        echo $this->formStatView($rev, 'shopping-cart');

        echo $this->formStatView($pend_rej, 'user');

        // echo $this->formStatView($ds_beg, 'users', 'getDistributed');

        if (!empty($_POST["start"])) {

          $uq = http_build_query(array(

              'start' => strtotime($_REQUEST["start"]),

              'end' => strtotime($_REQUEST["end"]),

              'client' => $_REQUEST["client"],

              'state' => $_REQUEST['State']

          ));

          echo "<div class='clearfix'></div><a href='downloadAcceptedRejected?$uq' class='btn btn-primary'>Download Accepted or Rejected leads</a>";

        }
      }
      return false;
    }
//  public function getAverageReports()
//
//  {
//
//    $con = $this->db();
//
//    $client = $_POST["client"];
//
//    $state = $_REQUEST["State"];
//
//    if(!empty($_POST["start"])){
//
//      $start = strtotime($_POST["start"]);
//
//      $end = strtotime($_POST["end"]) + 86400;
//
//    }
//
//    else {
//
//      $timestamp = time();
//
//      $start = strtotime("midnight", $timestamp);
//
//      $end = strtotime("tomorrow", $start) - 1;
//
//    }
//
//
//
//    // get approved leads and sum
//
//    $sql = 'SELECT COUNT(*) as amount, SUM(c.lead_cost) as total_cost  FROM `leads_delivery` as ld ';
//
//    $sql .= ' LEFT JOIN `clients` as c ON ld.client_id = c.id';
//
//    $sql .= ' LEFT JOIN `leads_rejection` as lr ON lr.lead_id = ld.lead_id AND lr.client_id = ld.client_id';
//
//    $sql .= ' LEFT JOIN `leads_lead_fields_rel` as lf ON lf.id=ld.lead_id';
//    //Here was an issue with accepted leads. I changed it
//    //from lr.approval=1 to
//    //lr.approval=1 or lr.approval=3
//    $sql .= ' WHERE lr.approval = 1 or lr.approval=3'; //  OR lr.approval IS NULL )
//
//    $sql .= ' AND (ld.timedate BETWEEN '.$start.' AND '.$end.')';
//
//    if (!($client == 0)) {
//
//      $sql .= ' AND ld.client_id =' . $client;
//
//    }
//
//    if($state){
//
//      $sql .= " AND lf.state = '$state'";
//
//    }
//
//
//
//    $res = $con->query($sql);
//
//    $approved = array();
//
//
//
//    if ($res) {
//
//      $approved = $res->fetch_assoc();
//
//    } else {
//
//      echo "No data\n";
//
//    }
//
//
//
////    $sqlnew = 'SELECT COUNT(*) as amount, SUM(c.lead_cost) as total_cost FROM `leads_rejection` as lj ';
//
////    $sqlnew .= ' LEFT JOIN `clients` as c ON lj.client_id = c.id';
//
////    $sqlnew .= ' WHERE (lj.approval = 1 OR lj.approval IS NULL)';
//
////    $sqlnew .= ' AND (lj.date BETWEEN '.$start.' AND '.$end.')';
//
//    $sqlnew = 'SELECT COUNT(*) as amount FROM `leads_delivery` as ld ';
//
//    $sqlnew .= ' INNER JOIN `leads_rejection` as lr ON lr.lead_id = ld.lead_id AND lr.client_id = ld.client_id';
//
//    $sqlnew .= ' LEFT JOIN `clients` as c ON lr.client_id = c.id';
//
//    $sqlnew .= ' LEFT JOIN `leads_lead_fields_rel` as lf ON lf.id=ld.lead_id';
//
//    $sqlnew .= ' WHERE (lr.approval = 1 OR lr.approval IS NULL)';
//
//    $sqlnew .= ' AND (ld.timedate BETWEEN '.$start.' AND '.$end.')';
//
//
//
//    if (!($client == 0)) {
//
//      $sqlnew .= ' AND lr.client_id =' . $client;
//
//    }
//
//    if($state){
//
//      $sqlnew .= " AND lf.state = '$state'";
//
//    }
//
//
//
//    $resam = $con->query($sqlnew);
//
//    if($resam){
//
//      $approvednew = $resam->fetch_assoc();
//
//    }
//
//
//
//    $sql2 = 'SELECT COUNT(*) as amount, SUM(c.lead_cost) as client_cost FROM `leads_delivery` as ld ';
//
//    $sql2 .= ' LEFT JOIN `leads_rejection` as lr ON lr.lead_id = ld.lead_id AND lr.client_id = ld.client_id';
//
//    $sql2 .= ' LEFT JOIN `leads_lead_fields_rel` as lf ON lf.id=ld.lead_id';
//
//    $sql2 .= ' LEFT JOIN `clients` as c ON c.id=ld.client_id';
//
//    $sql2 .= ' WHERE lr.approval=0';
//
//    $sql2 .= ' AND (lr.date BETWEEN '.$start.' AND '.$end.')';
//
//    if ($client != 0) {
//
//      $sql2 .= ' AND ld.client_id =' . $client;
//
//    }
//
//    if($state){
//
//      $sql2 .= " AND lf.state = '$state'";
//
//    }
//
//
//
//    $res = $con->query($sql2);
//
//    if ($res) {
//
//      $rejected = $res->fetch_assoc();
//
//    } else {
//
//      echo "0";
//
//    }
//
//
//
////      $sqlrej = 'SELECT COUNT(*) as amount FROM `leads_rejection` as lr ';
//
////      $sqlrej .= ' LEFT JOIN `clients` as c ON lr.client_id = c.id';
//
////      $sqlrej .= ' WHERE lr.approval=0';
//
////      $sqlrej .= ' AND (lr.date BETWEEN '.$start.' AND '.$end.')';
//
//    $sqlrej = 'SELECT COUNT(*) as amount, SUM(c.lead_cost) as client_cost FROM `leads_delivery` as ld ';
//
//    $sqlrej .= ' LEFT JOIN `leads_rejection` as lr ON lr.lead_id = ld.lead_id AND lr.client_id = ld.client_id';
//
//    $sqlrej .= ' LEFT JOIN `clients` as c ON lr.client_id = c.id';
//
//    $sqlrej .= ' LEFT JOIN `leads_lead_fields_rel` as lf ON lf.id=ld.lead_id';
//
//    $sqlrej .= ' WHERE lr.approval=0';
//
//    $sqlrej .= ' AND (ld.timedate BETWEEN '.$start.' AND '.$end.')';
//
//    if ($client != 0) {
//
//      $sqlrej .= ' AND lr.client_id =' . $client;
//
//    }
//
//    if($state){
//
//      $sqlrej .= " AND lf.state = '$state'";
//
//    }
//
//    $resrej = $con->query($sqlrej);
//
//    if($resrej){
//
//      $rejectnew = $resrej->fetch_assoc();
//
//    }
//
//
//
////  Distributed
//
//    $sqlDistributed  = 'SELECT SUM(c.cost) as camp_cost FROM `leads_delivery` as ld';
//
//    $sqlDistributed .= ' INNER JOIN `leads` as l ON l.id=ld.lead_id';
//
//    $sqlDistributed .= ' INNER JOIN `campaigns` as c ON c.id = l.campaign_id';
//
//    $sqlDistributed .= ' LEFT JOIN `leads_lead_fields_rel` as lf ON lf.id=ld.lead_id';
//
//    $sqlDistributed .= ' WHERE 1=1 AND (ld.timedate BETWEEN '.$start.' AND '.$end.')';
//
//
//
////      $sqlDistributednew  = 'SELECT COUNT(*) as amount FROM `leads_rejection` as lj';
//
////      $sqlDistributednew .= ' LEFT JOIN `clients` as c ON lj.client_id = c.id';
//
////      $sqlDistributednew .= ' WHERE `approval` >= 0 AND (lj.date BETWEEN '.$start.' AND '.$end.')';
//
//
//
//
//
//    $sqlDistributednew  = ' SELECT COUNT(*) as amount, SUM(c.lead_cost) as client_cost FROM `leads_delivery` as ld';
//
//    $sqlDistributednew .= ' INNER JOIN `leads_rejection` as l ON l.lead_id = ld.lead_id AND l.client_id = ld.client_id';
//
//    $sqlDistributednew .= ' LEFT JOIN `clients` as c ON l.client_id = c.id';
//
//    $sqlDistributednew .= ' LEFT JOIN `leads_lead_fields_rel` as lf ON lf.id=ld.lead_id';
//
//    $sqlDistributednew .= ' WHERE l.approval >= 0 AND (ld.timedate BETWEEN '.$start.' AND '.$end.')';
//
//
//
//    if (!($client == 0)) {
//
//      $sqlDistributednew .= ' AND l.client_id =' . $client;
//
//    }
//
//    if($state){
//
//      $sqlDistributednew .= " AND lf.state = '$state'";
//
//    }
//
//    $resultdis = $con->query($sqlDistributednew);
//
//    if($resultdis){
//
//      $distributednew = $resultdis->fetch_assoc();
//
//    }
//
//
//
//
//
//    if (!($client == 0)) {
//
//      $sqlDistributed .= ' AND ld.client_id =' . $client;
//
//    }
//
//    if($state){
//
//      $sqlDistributed .= " AND lf.state = '$state'";
//
//    }
//
//
//
//    $res = $con->query($sqlDistributed);
//
//    if($res){
//
//      $distributed = $res->fetch_assoc();
//
//    }
//
//
//
//    if($rejectnew["amount"]){
//
//      $rejectedP = $rejectnew["amount"] / $distributednew["amount"];
//
//    } else {
//
//      $rejectedP = 0;
//
//    }
//
//
//
//
//
//
//
//    $sqlleadrej  = ' SELECT COUNT(*) as pendrej, SUM(c.lead_cost) as client_cost FROM `leads_delivery` as ld';
//
//    $sqlleadrej .= ' INNER JOIN `leads_rejection` as l ON l.lead_id = ld.lead_id AND l.client_id = ld.client_id';
//
//    $sqlleadrej .= ' LEFT JOIN `clients` as c ON l.client_id = c.id';
//
//    $sqlleadrej .= ' LEFT JOIN `leads_lead_fields_rel` as lf ON lf.id=ld.lead_id';
//
////    $sqlleadrej = "SELECT COUNT(*) as pendrej FROM `leads_rejection` as lj";
//
////    $sqlleadrej .= ' LEFT JOIN `clients` as c ON lj.client_id = c.id';
////Here were an issue with counting of pending rejections, I changed it from
//    //IN (2, 3, 4) to IN (2, 4)
//    $sqlleadrej .=" WHERE l.approval IN (2, 4) AND (ld.timedate BETWEEN '$start' AND '$end')";
//
//    if (!($client == 0)) {
//
//      $sqlleadrej .= ' AND l.client_id =' . $client;
//
//    }
//
//    if($state){
//
//      $sqlleadrej .= " AND lf.state = '$state'";
//
//    }
//
//
//
//    $resrej = $con->query($sqlleadrej);
//
//
//
//    if($resrej){
//
//      $resultrej = $resrej->fetch_assoc();
//
//    }
//
//
//
//    $sqlrenews  = ' SELECT COUNT(ld.id) as amount, SUM(c.lead_cost) as client_cost FROM `leads_delivery` as ld';
//
//    $sqlrenews .= ' INNER JOIN `leads_rejection` as l ON l.lead_id = ld.lead_id AND l.client_id = ld.client_id';
//
//    $sqlrenews .= ' INNER JOIN `clients` as c ON c.id=ld.client_id';
//
//    $sqlrenews .= ' WHERE l.approval >= 0 AND (ld.timedate BETWEEN '.$start.' AND '.$end.')';
//
//    $result_new = $con->query($sqlrenews);
//
//    if($result_new){
//
//      $result_news = $result_new->fetch_assoc();
//
//    }
//
//
//
//    if($result_news['amount'] == 0){
//
//      $average = 0;
//
//    }else{
//
//      $average = round($result_news['client_cost'] / $result_news['amount'], 2);
//
//    }
//
//
//
////    dd($sql);
//
//    $pend_rej = "Pending rejections: ".$resultrej['pendrej'];
//
//    // $total_revenue = $distributednew["amount"]*$average;
//
//    $total_revenue = $distributednew['client_cost'] - $rejectnew['client_cost'] - $resultrej['client_cost'];
//
//
//
//    $ds =  $distributednew["amount"] . " leads <br>Distributed";
//
//    // $ds_beg = "leads <br>Distributed 1 to 15 ";
//
//    $acs = $approvednew['amount']. " leads Accepted by clients";
//
//    $ras = $rejectnew["amount"] . " leads Rejected <br>by clients";
//
//    $trs = $approvednew["total_cost"] . " total Revenue";
//
//    $rejectedPercent =  number_format($rejectedP * 100, 0) . '% Rejected<br> by clients';
//
//    $rev = $total_revenue ? $total_revenue . " $<br>Lead Revenue" : 0 . " $<br>Lead Revenue";
//
//    echo $this->formStatView($ds, 'users', 'getDistributed');
//
//    echo $this->formStatView($acs, 'check', 'getAccepted');
//
//    echo $this->formStatView($ras, 'window-close', 'getRejected');
//
//    echo $this->formStatView($rejectedPercent, 'window-close', 'getRejected');
//
//    echo $this->formStatView($rev, 'shopping-cart');
//
//    echo $this->formStatView($pend_rej, 'user');
//
//    // echo $this->formStatView($ds_beg, 'users', 'getDistributed');
//
//    if(!empty($_POST["start"])) {
//
//      $uq = http_build_query(array(
//
//          'start'   => strtotime($_REQUEST["start"]),
//
//          'end'     => strtotime($_REQUEST["end"]),
//
//          'client'  => $_REQUEST["client"],
//
//          'state'   => $_REQUEST['State']
//
//      ));
//
//      echo "<div class='clearfix'></div><a href='downloadAcceptedRejected?$uq' class='btn btn-primary'>Download Accepted or Rejected leads</a>";
//
//    }
//
//  }



  public function downloadAcceptedRejected()

  {



    $con = $this->db();

    $client = $_REQUEST["client"];

    $start = $_REQUEST["start"];

    $end = $_REQUEST["end"] + 86400;

    $timestamp = time();

    $state = $_REQUEST["state"];



    if(empty($_REQUEST["start"])){

      $start = strtotime("midnight", $timestamp);

      $end = strtotime("tomorrow", $start) - 1;

    }

    // get approved leads and sum

    $sql = 'SELECT lf.id as `id`, lf.full_name as `Full name`, lf.email, lf.phone, DATE_FORMAT(FROM_UNIXTIME(`ld`.`timedate`), "%e %b %Y %h:%i:%s" ) AS `Date`, c.campaign_name as `Client Name`, c.email as `Client Email`, ';

    $sql .= ' `lf`.`address`, `lf`.`suburb`,  `lf`.`state`,  `lf`.`postcode`, `lf`.`system_size`, `lf`.`roof_type`, `lf`.`electricity`, `lf`.`house_age`, `lf`.`house_type`, `lf`.`system_for`, `lf`.`note`';

    $sql .= ' ,`lr`.`approval` as `Rejected/Accepted`';

    $sql .= ' FROM `leads_delivery` as ld ';

    $sql .= ' LEFT JOIN `clients` as c ON ld.client_id = c.id';

    $sql .= ' LEFT JOIN `leads_rejection` as lr ON lr.id = ld.id';

    $sql .= ' LEFT JOIN `leads_lead_fields_rel` as lf ON lf.id = ld.lead_id';

//    $sql .= ' WHERE (lr.approval > 0 OR lr.approval IS NULL)';

    $sql .= ' WHERE (ld.timedate BETWEEN '.$start.' AND '.$end.')';

    if (!($client == 0)) {

      $sql .= ' AND ld.client_id =' . $client;

    }

    if ($state) {

      $sql .= ' AND lf.state =' . " '$state'";

    }



    $res = $con->query($sql);

    $approved = array();

    if ($res) {

    $i=false;
      while($line = $res->fetch_assoc()){
        if(!$i) {
          $i=!$i;
          $prearr = array();

          foreach ($line as $k => $v) {

            $prearr[] = $k;
          }
          $approved[] = $prearr;
        }

          $line['address'] = str_replace(array("\r","\n"), "", $line['address']);

        $line['note'] = str_replace(array("\r","\n"), "", $line['note']);

        $line["Rejected/Accepted"] = formatReject($line["Rejected/Accepted"]);

        $approved[] = $line;

      }

    } else {

      echo "No data\n";

    }

    return $approved;

  }



  public function getAverageReportsNew()

  {

    $con = $this->db();

//      if(empty($_POST)){

//        $now = time();

//        $st = new DateTime(date('Y-m-01', $now));

//        $start = $st->getTimestamp();

//        $st->modify("+14 days");

//        $end = $st->getTimestamp();

//

//        if( $now < $end ) {

//

//        } else {

//          $start = $end;

//          $end = strtotime(date("Y-m-t", $now));

//        }

//      }else{

    $start = $_POST['start'];

    $end = $_POST['end'];



    $start = strtotime($start);

    $end = strtotime($end) + 86400;



//      }

    $begs = date('Y-m-d', $start);

    $ends = date('Y-m-d', $end);



    $sql = 'SELECT COUNT(*) as amount, SUM(c.lead_cost) as total_cost  FROM `leads_delivery` as ld ';

    $sql .= ' LEFT JOIN `clients` as c ON ld.client_id = c.id';

    $sql .= ' LEFT JOIN `leads_rejection` as lr ON lr.lead_id = ld.lead_id AND lr.client_id = ld.client_id';

    $sql .= ' WHERE (lr.approval = 1 OR lr.approval IS NULL)';

    $sql .= ' AND (ld.timedate BETWEEN '.$start.' AND '.$end.')';



    $sqlnew = 'SELECT COUNT(*) as amount FROM `leads_delivery` as ld ';

    $sqlnew .= ' INNER JOIN `leads_rejection` as lr ON lr.lead_id = ld.lead_id AND lr.client_id = ld.client_id';

    $sqlnew .= ' WHERE (lr.approval = 1 OR lr.approval IS NULL)';

    $sqlnew .= ' AND (ld.timedate BETWEEN '.$start.' AND '.$end.')';



    $resam = $con->query($sqlnew);

    if($resam){

      $approvednew = $resam->fetch_assoc();

    }

//    if (!($client == 0)) {

//      $sql .= ' AND ld.client_id =' . $client;

//    }



    $res = $con->query($sql);

    $approved = array();



    if ($res) {

      $approved = $res->fetch_assoc();

    } else {

      echo "No data\n";

    }



    $sql2 = 'SELECT COUNT(*) as amount, SUM(c.lead_cost) as client_cost FROM `leads_delivery` as ld ';

    $sql2 .= ' LEFT JOIN `leads_rejection` as lr ON lr.lead_id = ld.lead_id AND lr.client_id = ld.client_id';

    $sql2 .= ' LEFT JOIN `clients` as c ON c.id=ld.client_id';

    $sql2 .= ' WHERE lr.approval=0';

    $sql2 .= ' AND (ld.timedate BETWEEN '.$start.' AND '.$end.')';

//    if ($client != 0) {

//      $sql2 .= ' AND ld.client_id =' . $client;

//    }

    $res = $con->query($sql2);

    if ($res) {

      $rejected = $res->fetch_assoc();

    } else {

      echo "0";

    }

//  Distributed

    $sqlDistributed  = 'SELECT SUM(c.cost) as camp_cost FROM `leads_delivery` as ld';

    $sqlDistributed .= ' INNER JOIN `leads` as l ON l.id=ld.lead_id';

    $sqlDistributed .= ' INNER JOIN `campaigns` as c ON c.id = l.campaign_id';

    $sqlDistributed .= ' WHERE 1=1 AND (ld.timedate BETWEEN '.$start.' AND '.$end.')';



    $sqlDistributednew  = ' SELECT COUNT(*) as amount, SUM(c.lead_cost) as client_cost FROM `leads_delivery` as ld';

    $sqlDistributednew .= ' INNER JOIN `leads_rejection` as l ON l.lead_id = ld.lead_id AND l.client_id = ld.client_id';

    $sqlDistributednew .= ' LEFT JOIN `clients` as c ON c.id=ld.client_id';

    $sqlDistributednew .= ' WHERE l.approval >= 0 AND (ld.timedate BETWEEN '.$start.' AND '.$end.')';

    $resultdis = $con->query($sqlDistributednew);

    if($resultdis){

      $distributednew = $resultdis->fetch_assoc();

    }

//    var_dump($sqlDistributednew);

//    exit();







//    if (!($client == 0)) {

//      $sqlDistributed .= ' AND ld.client_id =' . $client;

//    }



    $res = $con->query($sqlDistributed);

    if($res){

      $distributed = $res->fetch_assoc();

    }

    // $date_now = date('F j, Y', $now);

    // var_dump($date_now);

    // var_dump(date('m/d/Y H:i:s'));

    // $start_date = date('d', $start);

    // if($start_date<16){

    //   $mes = "<h4>Information behind period 2017-02-01 - 2017-02-15</h4>";

    // }else{

    //   $mes = "<h4>Information behind period 2017-02-16 - 2017-02-28</h4>";

    // }



//    $mes = "<h4>Information behind period ".$begs." to ".$ends."</h4>";



    $sqlCountLids = "SELECT COUNT(ld.id) as amount FROM `leads` as ld";

    $sqlCountLids .= " WHERE 1=1 AND (ld.datetime BETWEEN ".$start." AND ".$end.")";





    $res = $con->query($sqlCountLids);

    if($res){

      $CountLids = $res->fetch_assoc();

    }



    $sqlCoastLids = "SELECT SUM(c.cost) as cost FROM `campaigns` as c";

    $sqlCoastLids .= " LEFT JOIN `leads` as ld ON ld.campaign_id = c.id";

    $sqlCoastLids .= " WHERE 1=1 AND (ld.datetime BETWEEN ".$start." AND ".$end.")";

    $res = $con->query($sqlCoastLids);

    if($res){

      $CoastLids = $res->fetch_assoc();

    }



    $sqlquery = "SELECT SUM(c.lead_cost) as cost FROM `leads_delivery` as ld";

    $sqlquery .= " LEFT JOIN `clients` as c ON ld.client_id = c.id";

    $sqlquery .= " WHERE 1=1 AND (ld.timedate BETWEEN ".$start." AND ".$end.")";

    $res = $con->query($sqlquery);

    if($res){

      $income = $res->fetch_assoc();

    }



    $resCoast = $income['cost'] - $CoastLids['cost'];



    // $sqlAver  = 'SELECT COUNT(ld.id) as amount, SUM(c.lead_cost) as client_cost FROM `leads_delivery` as ld';

    // $sqlAver .= ' INNER JOIN `clients` as c ON c.id=ld.client_id';

    // $sqlAver .= ' WHERE 1=1 AND (ld.timedate BETWEEN '.$start.' AND '.$end.')';



    $sqlrenews  = ' SELECT COUNT(ld.id) as amount, SUM(c.lead_cost) as client_cost FROM `leads_delivery` as ld';

    $sqlrenews .= ' INNER JOIN `leads_rejection` as l ON l.lead_id = ld.lead_id AND l.client_id = ld.client_id';

    $sqlrenews .= ' INNER JOIN `clients` as c ON c.id=ld.client_id';

    $sqlrenews .= ' WHERE l.approval >= 0 AND (ld.timedate BETWEEN '.$start.' AND '.$end.')';

    $result_new = $con->query($sqlrenews);

    if($result_new){

      $result_news = $result_new->fetch_assoc();

    }



    if($result_news['amount'] == 0){

      $average = 0;

    }else{

      $average = round($result_news['client_cost'] / $result_news['amount'], 2);

    }

    // var_dump($average);

    // var_dump($result_news);

    // exit();





    $sqlrenew  = ' SELECT COUNT(ld.id) as amount, SUM(c.lead_cost) as client_cost FROM `leads_delivery` as ld';

    $sqlrenew .= ' INNER JOIN `leads_rejection` as l ON l.lead_id = ld.lead_id AND l.client_id = ld.client_id';

    $sqlrenew .= ' INNER JOIN `clients` as c ON c.id=ld.client_id';

    $sqlrenew .= ' WHERE l.approval > 0 AND (ld.timedate BETWEEN '.$start.' AND '.$end.')';



    $res = $con->query($sqlrenew);

    if($res){

      $result = $res->fetch_assoc();

    }





    if($CountLids['amount'] == 0){

      $average_sales = 0;

    }else{

      $average_sales = round($distributednew["amount"] / $CountLids['amount'], 2);

    }

    if($distributednew["amount"] == 0){

      $rejectedP = 0;

    }else{

      $rejectedP = $rejected["amount"] / $distributednew["amount"];

    }



    $sqlleadrej = "SELECT COUNT(*) as pendrej, SUM(c.lead_cost) as client_cost FROM `leads_delivery` as ld";

    $sqlleadrej .= ' INNER JOIN `leads_rejection` as l ON l.lead_id = ld.lead_id AND l.client_id = ld.client_id';

    $sqlleadrej .= ' LEFT JOIN `clients` as c ON c.id=ld.client_id';

    $sqlleadrej .=" WHERE l.approval IN (2, 3, 4) AND (ld.timedate BETWEEN '$start' AND '$end')";



    $resrej = $con->query($sqlleadrej);



    if($resrej){

      $resultrej = $resrej->fetch_assoc();

    }



    $today = time();

    $today = date("m/d/Y", $today);

    $tod = strtotime($today);

    $today = strtotime($today.'+1 day');





    $sqlleadrejtoday = "SELECT COUNT(*) as pendrejt FROM `leads_delivery` as ld";

    $sqlleadrejtoday .= " INNER JOIN `leads_rejection` as l ON l.lead_id = ld.lead_id AND l.client_id = ld.client_id";

    $sqlleadrejtoday .=" WHERE l.approval IN (2, 3, 4) AND (ld.timedate BETWEEN '$tod' AND '$today')";



    // $sqlleadrejtoday = "SELECT COUNT(id) as pendtoday FROM `leads_rejection`";

    // $sqlleadrejtoday .=" WHERE `approval` IN (2, 3, 4) AND `date`= '$today'";



    $resrejtoday = $con->query($sqlleadrejtoday);



    if($resrejtoday){

      $resultrejtoday = $resrejtoday->fetch_assoc();

    }



    // $total_revenue = $distributednew["amount"]*$average;

    $total_revenue = $distributednew['client_cost'] - $rejected['client_cost'] - $resultrej['client_cost'];



//    $profit = $result['client_cost'];

    // $profit = $total_revenue - $CoastLids['cost'] - ($rejected["amount"]*$average);

    $profit = $total_revenue - $CoastLids['cost'];





    $ds =  $distributednew["amount"] . " leads <br>distributed";

    $acs = $approvednew['amount']. " leads <br>accepted";

    $ras = $rejected["amount"] . " leads <br>rejected";

    //$trs = $approved["total_cost"] . " total Revenue";

    $rejectedPercent =  number_format($rejectedP * 100, 0) . '% of leads <br>are rejected';

    $rev = $total_revenue ? "$".$total_revenue . " <br>total revenue" : "$0 <br>total revenue";

    // $rev = $income["cost"] ? "$".$income["cost"] . " <br>total revenue" : "$0 <br>total revenue";

    $countld = $CountLids['amount']. " leads <br>generated in total";

    $coastld = "$".$CoastLids['cost']. " <br> is the total<br> lead cost";

    $totalcost ="$".$profit. " <br>total profit";

    // $totalcost ="$".$resCoast. " <br>total profit";

    $totalAverage ="$".$average. " average <br>lead sale price";

    $av_sel ="Leads are sold an average of ". $average_sales. " times";

    $pend_rej = "Pending rejections: ".$resultrej['pendrej'];

    $pend_rejtoday = "Pending rejections for today:".$resultrejtoday['pendrejt'];



    echo $this->formStatView($ds, 'users', 'getDistributed');

    echo $this->formStatView($acs, 'check', 'getAccepted');

    echo $this->formStatView($ras, 'window-close', 'getRejected');

    echo $this->formStatView($rejectedPercent, 'window-close', 'getRejected');

    echo $this->formStatView($rev, 'shopping-cart');

    echo $this->formStatView($countld, 'users');

    echo $this->formStatView($coastld, '');

    echo $this->formStatView($totalcost, 'shopping-cart');

    echo $this->formStatView($totalAverage, 'user');

    echo $this->formStatView($av_sel, 'user');

    echo $this->formStatView($pend_rej, 'user');

    echo $this->formStatView($pend_rejtoday, 'user');



  }





  public function getSourceAverage()

  {

    $state = $_REQUEST["State"];

    $source = $_POST["source"];

    $start = strtotime($_POST["start"]);

    $end = strtotime($_POST["end"]) + 86400;

    $campaign_id = getCampaignID($source);

    $data = "(`l`.`datetime` BETWEEN $start AND $end)";



    $con = $this->db();

    $sql = 'SELECT SUM(c.cost) as total_cost, COUNT(c.id) as amount, lf.state as state FROM `leads` as l';

    $sql .= ' LEFT JOIN `campaigns` as c ON l.campaign_id = c.id';

    $sql .= ' LEFT JOIN `leads_lead_fields_rel` as lf ON lf.id=l.id';

    $sql .= ' WHERE 1=1';

    $sql .= ' AND '. $data;

    if ($campaign_id){

      $sql.= ' AND l.campaign_id = '.$campaign_id;

    }

    if ($state){

      $sql .= " AND lf.state = '$state'";

    }



    $res = $con->query($sql);

    if($res){

      $d = $res->fetch_assoc();

    }

    if($d) {

      $amount = $d["amount"] . " <br>Leads Received";

      $cost = $d["total_cost"] . "$ <br>Total Leads Cost";

      echo $this->formStatView($amount, 'users', 'getReceived');

      echo $this->formStatView($cost, 'shopping-cart', 'getReceived');

    } else {

      echo "No data for this ctriteria.";

    }

  }



  public function getPending()

  {



  }

}

