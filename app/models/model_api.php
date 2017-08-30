<?php
class Model_Api extends Model {
	public $debug_api = FALSE;
	public function proccess_lead($post, $counter=0, $addToTable=true, $leadId=0) {
		$p = $this->checkdata($post);
		//var_dump($phone);



		if(!empty($p["phone"]))
		{
			if ( strlen($p["phone"]) < 10 ) {
				return 'The number must be at least 10 numbers';
			}
			if($this->phoneReject($p["phone"]))
			{
				return 'This phone has been already sent in this week';
			}

		}

		else
		{
			return 'There is no phone entered';
		}



		/*if($this->checkPhone())
		{
			return 'The number must be at least 10 numbers';
		}*/

		if ($addToTable) {
			$lead_id = $this->addleadtotable($p);
		} else {
			$lead_id=$leadId;
		} if (!$lead_id) {
			return FALSE;
		}
		$clients = $this->getClients($p);
		$resp =  $this->sendToClients($clients, $lead_id, $p, $counter);
		//$this->sendLeadToInfusion($p);
		return $resp;
	}

	public function saveEzidebitCref($client,$cref)
	{
	$con=$this->db();
	$sql="INSERT INTO ezidebit_cref (client_id, cref) VALUES ('$client','$cref')";
	$con->query($sql);	
	}

	private function phoneReject($phone)
	{  // var_dump($phone);
		$con = $this->db();
		$stop=time()-86400*7;
		$sql="SELECT ler.id from leads_lead_fields_rel as ler left join leads le on ler.id=le.id WHERE ler.phone='".$phone."' AND le.datetime>'".$stop."'";
		//return $sql();
		$res=$con->query($sql);
		if($res->fetch_assoc()) return true;
		return false;
	}

/*	private function checkPhone($p["phone"])
	{

		if ( strlen($p["phone"]) < 10 ) {
			return true;
		}
		return false;
	}*/



	public function sendToClients($clients, $lead_id ,$p, $counter){
		$sended = '';
		foreach ($clients as $c ) {
			$client_id = $c["id"];
			//here will be checking if is already delivered to current client
			$clients = explode(',', $p['clients']);
			if (in_array($client_id, $clients)) continue;
			$clCampId = $this->checkClientsLimits($client_id,$p);
			if($clCampId AND $counter < 4) {
				$readyLeadInfo = prepareLeadInfo($p);
				$delivery_id = $this->getLastDeliveryID() + 1;
				$linkToReject=$this->formLink($client_id, $delivery_id);
				//var_dump($linkToReject);die();
				$sent = $this->sendToClient($c["email"], $readyLeadInfo, $c["full_name"], $delivery_id, $linkToReject);
				if($sent) {
					$counter++;
					$this->addToDeliveredTable($client_id, $lead_id, $readyLeadInfo, $clCampId);
					$sended .= "$c[full_name] : $c[email] \n <br>";
				}
			}
		}
		return "Sent to $counter clients \n";// . $sended;
	}
	public function sendToClientCamp($client_id, $p, $counter=0){
		$sended = '';
		$con = $this->db();
		$sql="SELECT count(client_id) FROM leads_delivery WHERE lead_id=".$p['id'];
		$res=$con->query($sql);
		if($row = $res->fetch_assoc()) $counter = $row["count(client_id)"];
		$con = $this->db();
		$sql="SELECT * from clients WHERE id=".$client_id;
		$res=$con->query($sql);
		if($row = $res->fetch_assoc()) $c = $row;
		$clients = explode(',', $p['clients']);
		if (in_array($client_id, $clients)) continue;
		$clCampId = $this->checkClientsLimits($client_id,$p);
		if($clCampId AND $counter < 4) {
			$readyLeadInfo = prepareLeadInfo($p);
			$delivery_id = $this->getLastDeliveryID() + 1;
			$linkToReject=$this->formLink($client_id, $delivery_id);
			$sent = $this->sendToClient($c["email"], $readyLeadInfo, $c["full_name"], $delivery_id, $linkToReject);
			if($sent) {
//          var_dump($p['id']);die;
				echo (int)$this->addToDeliveredTable($client_id, $p['id'], $p, $clCampId);
				$sended .= "$c[full_name] : $c[email] \n <br>";
			}
		}
		return $sended;
	}
	public function formLink($client, $delivery)
	{
		$link=__HOST__.'/leadreject/index?';
		$hash=$client.'&'.$delivery;
		$link.=base64_encode($hash);
		return $link;
	}
	public function getLastDeliveryID(){
		$sql = "SELECT `id` FROM leads_delivery ORDER BY `id` DESC LIMIT 1";
		$db  = DB::getInstance();
		$res = $db->get_row($sql);
		return $res[0];
	}
	public function addToDeliveredTable($client_id, $lead_id, $p, $camp_id){
		$con = $this->db();
		$now = time();
		$sql = "INSERT INTO `leads_delivery` (lead_id, client_id, timedate, camp_id) VALUES ('".$lead_id."', '".$client_id."', '".$now."', '".$camp_id."')";
		$sql_r = "INSERT INTO `leads_rejection` (lead_id, client_id, date, approval, camp_id) VALUES ('".$lead_id."', '".$client_id."', '".$now."', '1', '".$camp_id."')";
		if($con->query($sql) && $con->query($sql_r)) { $delivered=1; }
		if($delivered){
			return TRUE;
		} else {
			return FALSE;
		}
	}
	public function sendToClient($mail, $p, $client_name, $track_id, $linkToReject)
	{
		if($mail) {
			send_m($mail, $p, $client_name, $track_id, '',$linkToReject);
			return TRUE;
		}
		return FALSE;
	}
	public function checkClientsLimits($id, $p)
	{
		$con = $this->db();
		$monday = strtotime("Monday this week");
		$dayNow = strtotime("today");
		// $quanDays = ($monday-$dayNow)/86400;
		$sql1 = "select * from client_campaigns where postcodes like '%".$p['postcode']."%' and client_id=".$id;
		$res=$con->query($sql1);
		if ($res && $res->num_rows)
		{
			while ($row=$res->fetch_assoc()) {
				$campAr[] = $row;
			}
			foreach ($campAr as $camp) {
				$sql2 = "select count(id) from leads_delivery where camp_id = ".$camp['id']." AND timedate BETWEEN '".$dayNow."' AND current_timestamp";
				$result=$con->query($sql2);
				if ($row=$result->fetch_assoc())
				{
					if($row['count(id)'] < (int)($camp['weekly']))
					{
//              echo $camp['id'];
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
	public function getClients($post){
		$clients = array();
		$con = $this->db();
		if(!empty($post["postcode"]) ){
			$sql = 'SELECT cc.id, c.email, c.full_name';
			$sql.= ' FROM `clients_criteria` as cc';
			$sql.= ' LEFT JOIN `clients` as c ON cc.id = c.id';
			$sql .= ' WHERE ( cc.postcodes LIKE "%'.$post["postcode"].'%" )';
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
		if($this->debug_api) {
			// LOGS
			// #start buffering (all activity to the buffer)
			ob_start();
			$fileName = __FILE__ . "/logs/";
			$fileName .= date("Y-m-d-h-i-s");
			$fileName .= "log.html";
			$myfile = fopen($fileName, "w");
			echo '<!DOCTYPE html>
						<html>
						<head>
							<title>LOG</title>
						</head>
						<body>';
			echo "CLIENTS : \n";
			var_dump($clients);
			echo "===========\n";
			echo "===========POST===========\n";
			var_dump($_POST);
			echo "\n=================\n";
		}
		// GET destribution ORDER
		$now = time();
		$st = new DateTime(date('Y-m-01', $now));
		$start = $st->getTimestamp();
		$st->modify("+14 days");
		$end = $st->getTimestamp();
		if( $now < $end ) {
			// do nothing
		} else {
			$start = $end;
			$end = strtotime(date("Y-m-t", $now));
		}
		$sql =  'SELECT c.id as id, IFNULL(c.lead_cost,0) as lead_cost, IF(COUNT(*)=0,0,SUM(IF(lr.approval=0,1,0))/COUNT(*)) as percentage, ((COUNT(*) - SUM(IF(lr.approval=0,1,0)))*c.lead_cost) as revenue FROM `leads_delivery` as ld';
		$sql .= ' INNER JOIN `leads_rejection` as lr ON lr.lead_id = ld.lead_id AND lr.client_id = ld.client_id';
		$sql .= ' INNER JOIN clients as c ON ld.client_id=c.id';
		$sql .= ' WHERE `ld`.`timedate` BETWEEN '.$start.' AND '.$end.'';
		$sql .= ' GROUP BY ld.client_id';
		$sql .= ' ORDER BY revenue DESC, percentage ASC, lead_cost DESC';
		$res = $con->query($sql);
		if ($res) {
			while( $res->fetch_assoc())
			{
				foreach ($res as $v) {
					$order[] = $v["id"];
				}
			}
		}
		if(count($order)){
			usort($clients, function ($a, $b) use ($order) {
				$pos_a = array_search($a['id'], $order);
				$pos_b = array_search($b['id'], $order);
				return $pos_a - $pos_b;
			});
			if($this->debug_api) {
				echo "\n===============$order================\n";
				var_dump($order);
				echo "\n================================\n";
				echo "\n======CLIENTS SORTED===========\n";
				var_dump($clients);
				echo "\n================================\n";
				echo "</body></html>";
				# dump buffered $classvar to $outStringVar
				$outStringVar = ob_get_contents();
				fwrite($myfile, $outStringVar);
				fclose($myfile);
				# clean the buffer & stop buffering output
				ob_end_clean();
				// END LOGS
			}
			// function custom_compare($a, $b){
			//   global $order;
			//   $key_a = array_search($a["id"], $order);
			//   $key_b = array_search($b["id"], $order);
			//   if($key_a === false && $key_b === false) { // both items are dont cares
			//       return 0;                      // a == b
			//   }
			//   else if ($key_a === false) {           // $a is a dont care item
			//       return 1;                      // $a > $b
			//   }
			//   else if ($key_b === false) {           // $b is a dont care item
			//       return -1;                     // $a < $b
			//   }
			//   else {
			//       return $key_a - $key_b;
			//   }
			// }
			// usort($clients, "custom_compare");
		}
		// Returnig ordered clients
		return $clients;
	}
	public function checkdata($post){
		$p = array();
//    if ( empty($post["full_name"]) || empty($post["email"]) || empty($post["phone"]) || empty($post["address"]) || empty("state") || empty("postcode") || empty($post["suburb"]) )
//    {
//      return FALSE;
//    }
		foreach ($post as $k => $v) {
			if ($k=="phone") {
				$p["phone"] = phone_valid($v);
			} else if($k=="postcode"){
				$p["postcode"] = postcodes_valid($v);
				if(!$p["postcode"]) return;
			} else {
				$p["$k"] = trim($v);
			}
		}
		$p["state"] = $this->giveMeState($p["postcode"]);
		return $p;
	}
	public function giveMeState($code)
	{
		$con=$this->db();
		$sql="SELECT * FROM states_postcodes";
		$res=$con->query($sql);
		while ($row=$res->fetch_assoc())
		{
			$result[$row['state']][]=explode(':',$row['postcodes']);
		}
		foreach ($result as $item=>$key)
		{
			foreach ($key as $key1)
			{
				if ($code>=(int)$key1[0] && $code<=(int)$key1[1]) return $item;
			}
		}
		return 'Unmatched';
	}
	public function addleadtotable($post)
	{
		$con = $this->db();
		if ($con->connect_errno) {
			printf("Connect failed: %s\n", $con->connect_error);
			exit();
		}
		$id = getCampaignID($post['source']);
		if(!$id){
			return FALSE;
		}
		$now = time();
		$addlead = "INSERT INTO `leads` (campaign_id, datetime) VALUES ($id, $now)";
		$con->query($addlead);
		$lastid = $con->insert_id;
		$tt = "INSERT INTO `leads_lead_fields_rel` (id, ";
		$col = "";
		foreach ( $post as $k => $v) {
			$col .= "$k" . ", ";
		}
		$col = substr($col, 0, -2);
		$tt2 = ") VALUES ($lastid, ";
		$val = "";
		foreach ($post as $k => $v) {
			$v = trim($v);
			$val .= "'$v'" . ", ";
		}
		$val = substr($val, 0, -2);
		$tt3 = ")";
		$lead_fields_query = $tt . $col . $tt2 . $val . $tt3;
		$con->query($lead_fields_query);
		$con->close();
		return $lastid;
	}
	//  private function prepareLeadInfo($p){
//    $campaign_id = getCampaignID($p["source"]);
//    $readyLeadInfo = array();
//    $con = $this->db();
//
//    // query to get fields from campaign
//    $sql = 'SELECT lead_fields.id, lead_fields.key, lead_fields.name, campaign_lead_fields_rel.is_active, campaign_lead_fields_rel.mandatory';
//    $sql.= ' FROM `campaign_lead_fields_rel`';
//    $sql.= ' LEFT JOIN `lead_fields` ON lead_fields.id = campaign_lead_fields_rel.field_id';
//    $sql.= ' WHERE campaign_lead_fields_rel.campaign_id ='.$campaign_id;
//    $res = $con->query($sql);
//    if ($res) {
//      while( $res->fetch_assoc())
//      {
//        foreach ($res as $r) {
//          $key = $r["key"];
//          if($r["is_active"]){
//            $preArray["key"] = $key;
//            $preArray["val"] =  $p["$key"];
//            $preArray["field_name"] = $r["name"];
//            $readyLeadInfo[] = $preArray;
//            unset($preArray);
//          }
//        }
//      }
//    } else {
//      echo "<br>Something WRONG<br>";
//      return FALSE;
//    }
//    return $readyLeadInfo;
//  }
//Mironenko method - returns list of sent leads with count of delivers
	public function getSentLeads()
	{
		//this variable is for show how many days from query do we need, will be saved in DB and added from interface
		include "model_settings.php";
		$settings=new Model_Settings();
		$data=$settings->getSettings();
		$con = $this->db();
		//
		$range = time() - ($data['days'] * 86400);
		$sql = "SELECT le_fi.*, count(led.lead_id) as 'count', group_concat(led.client_id) as 'clients' FROM leads lea left join leads_lead_fields_rel le_fi on lea.id=le_fi.id left join leads_delivery led on lea.id=led.lead_id where lea.datetime>'" . $range . "'
    group by(le_fi.id)";
//    var_dump($sql);die;
		$res=$con->query($sql);
		$result = array();
		while ($row = $res->fetch_assoc()) {
			//if($row['count']<4) $result[] = $row;
			$result[] = $row;
		}
		return $result;
	}
	public function sendLeadToInfusion($lead)
	{
		session_start();
		include_once "model_infusionsoft.php";
		$infusionSoft=new Model_Infusionsoft();
		$infusionSoft->sendClient($lead);
	}
}