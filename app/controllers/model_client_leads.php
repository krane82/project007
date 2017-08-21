<?php
class Model_Client_Leads extends Model {

  public function getLeadsForClient($client)
  {
    $start = strtotime($_REQUEST["start"]);
    $end = strtotime($_REQUEST["end"]) + 86400;
    $timestamp = time();
    if(empty($_REQUEST["start"])){
      $start = strtotime("midnight", $timestamp);
      $end = strtotime("tomorrow", $start) - 1;
    }
    $con = $this->db();
    $sql = 'SELECT lf.id as `id`, lf.full_name as `Full name`, lf.email, lf.phone, DATE_FORMAT(FROM_UNIXTIME(`ld`.`timedate`), "%e %b %Y %H:%i" ) AS `Date`,';
    $sql .= ' `lf`.`address`,  `lf`.`state`,  `lf`.`postcode`, `lf`.`suburb`, `lf`.`system_size`, `lf`.`roof_type`, `lf`.`electricity`, `lf`.`house_age`, `lf`.`house_type`, `lf`.`system_for`, `lf`.`note`';
    $sql .= 'FROM `leads_delivery` as ld ';
    $sql .= ' LEFT JOIN `clients` as c ON ld.client_id = c.id';
    $sql .= ' LEFT JOIN `leads_rejection` as lr ON lr.lead_id = ld.lead_id AND lr.client_id = ld.client_id';
    $sql .= ' INNER JOIN `leads_lead_fields_rel` as lf ON lf.id = ld.lead_id';
    $sql .= ' WHERE 1=1';
    $sql .= ' AND (ld.timedate BETWEEN '.$start.' AND '.$end.')';
    $sql .= ' AND ld.client_id =' . $client;
    $sql .= ' AND `lr`.`approval` != 0';
    $res = $con->query($sql);
    $data = array();
    if ($res) {
      $col = $res->fetch_assoc();
      $prearr = array();

      foreach($col as $k=>$v){
        $prearr[] = $k;
      }

      $data[] = $prearr;
      $data[] = $col;

      while($line = $res->fetch_assoc()){
        $line['phone']='Ph: '.$line['phone'];
        $data[] = $line;
      }
    } else {
      echo "No data\n";
    }
    return $data;
  }

  public function count_notifications($id) {
      $con = $this->db();
      $sql = "SELECT count(*) FROM `leads_delivery` WHERE client_id = $id AND seen = 0";
      $res = $con->query($sql);
      if ($res) {
          $row = $res->fetch_row();
          $num = $row[0];
          return $num;
      }
  }

  public function update_notifications($id) {
      $con = $this->db();
      $sql = "UPDATE `leads_delivery` SET seen = 1 WHERE client_id = $id AND seen = 0";
      $res = $con->query($sql);
      return;
  }

  public function get_new_notifications($id) {
      $con = $this->db();
      $sql = "SELECT leads_delivery.timedate, leads_lead_fields_rel.suburb FROM leads_delivery JOIN leads_lead_fields_rel ON leads_delivery.lead_id = leads_lead_fields_rel.id WHERE leads_delivery.client_id = $id AND seen = 0 ORDER BY leads_delivery.timedate DESC LIMIT 8";
      $res = $con->query($sql);
      if ($res) {
          while ($row = $res->fetch_array(MYSQLI_ASSOC)) {
              $array[] = $row;
          }
          return $array;
      }
  }


}
