<?php
class Model_Profile extends Model {
  private   $form_keys = array(
            'id' => '',
            'campaign_name' => 'Campaign name',
            'email' => 'Email',
            'password' => 'Password',
            'full_name' => 'Full name',
            'phone' => 'Phone',
            'city' => 'City',
            'state' => 'State',
            'country' => 'Country',
            'lead_cost' => 'Lead cost',
            'postcodes' => 'Postcodes matches',
            'states_filter' => 'States filter',
            'xero_id' => 'Xero id',
            'xero_name' => 'Xero name',
            'weekly' => 'Weekly caps'
      );

  public function UserChangeNotif($p, $before=''){
     $previousData=urldecode($before);
    $previousData=unserialize($previousData);
     //var_dump($previousData);
      $profile = '<h2>Client "'.$p["campaign_name"].'" have change their profile information</h2>';
    $profile .= "<table style='width:50%'>";
    // prepeare info
    $profile .= '<tr><td><b>Field Name</b></td><td><b>Previous</b></td><td><b>Current</b></td></tr>';
    foreach ($p as $k => $v) {
      if( $k == "id" || $k == "lead_cost" || $k == "xero_id" || $k == "xero_name" || $k == "coords" || $k=="previousData"){
        // blank
      }elseif ($k=="password") {
          $profile.= "<tr>";
          $profile.= "<td>".$this->form_keys["$k"]."</td>";
          // var_dump($v); exit();
          if($v===''){ $v= 'password not changed'; }
          $profile.= "<td></td>";
          $profile.=  "<td>$v</td>";
          $profile.= "</tr>";
      } else {
          $profile.= "<tr>";
          $profile.= "<td>".$this->form_keys["$k"]."</td>";
          $profile.= "<td>".$previousData[0]["$k"]."</td>";
          if ($previousData[0]["$k"]!=$v)
          {
              $profile.= "<td style='background-color:yellow'>";
          }
          else
          {
              $profile.=  "<td>";
          }
          $profile.="$v</td>";
          $profile.= "</tr>";
      }
    }
    $profile .= "</table>";
    $mail = new PHPMailer;
    //  $mail->isSendmail();
    $mail->IsSMTP(); // telling the class to use SMTP
    $path = $_SERVER['DOCUMENT_ROOT'];
    $path .= "/credentials.php";
    require_once($path);

    $mail->IsSMTP(); // telling the class to use SMTP
    $mail->SMTPAuth   = true;                  // enable SMTP authentication
    $mail->Host       = MAIL_HOST;    // sets the SMTP server
    $mail->Port       = MAIL_PORT;                    
    $mail->Username   = MAIL_USER;    // SMTP account username
    $mail->Password   = MAIL_PASS;        // SMTP account password

    $mail->isHTML(true);

    $mail->AddReplyTo("info@energysmart.com.au", "Energy Smart Notification");

    $mail->SetFrom('info@energysmart.com.au', 'Energy Smart Notification');

    $mail->AddAddress(ADMINEMAIL, 'Joash Boyton');
    $mail->AddAddress('ariel@energysmart.com.au', 'Ariel');
    $mail->AddAddress('Emma@energysmart.com.au', 'Emma Boyton');
    $mail->AddAddress('Jarrad@energysmart.com.au', 'Jarrad van de Laarschot');

    $mail->Subject = 'Client "'.$p["campaign_name"].'" have change their profile information';

    $mail->AltBody = "To view the message, please use an HTML compatible email viewer!";
    $mail->msgHTML($profile);
    $emailSending = "";

    if (!$mail->Send()) {
      echo $emailSending = "Mailer Error: " . $mail->ErrorInfo;
      return FALSE;
    } else {
      return "Success";
    }
  }

  public function get_profile_data($id) {
      $sql = ' SELECT clients.id, clients.email, clients.campaign_name, clients.full_name, clients.phone, clients.city, clients.state, clients.country, clients.lead_cost, clients_criteria.postcodes, clients_criteria.states_filter, clients_billing.xero_id, clients_billing.xero_name, clients_criteria.weekly';
      $sql.= ' FROM `clients`';
      $sql.= ' LEFT JOIN `clients_billing` ON clients.id = clients_billing.id';
      $sql.= ' LEFT JOIN `clients_criteria` ON clients.id = clients_criteria.id';
      $sql.= ' LEFT JOIN `users` ON clients.id = users.id';
      $sql.= ' WHERE clients.id = '.$id;
      $profile = '<div class="success">';
      $con = $this->db();
      $res = $con->query($sql);
      while($row = $res->fetch_assoc()){
        foreach ($row as $k => $v) {
            if($k == "id"){
                $profile.= "<input type='hidden' name='$k' value='$v' />";
            }elseif ($k == "password") {
                $profile.= "<input type='password' name='$k' value=''/>";
            }elseif ($k == "postcodes") {
                continue;
//                $profile.= "<div class='form-group'>";
//                $profile.= "<label for='$k'>".$this->form_keys["$k"]."</label>";
//                $profile.=  "<textarea class='form-control' id='$k' readonly='readonly' name='postcodes' type='text'>$v</textarea>";
//                $profile.= "</div>";
            } elseif($k == "lead_cost" || $k == "xero_id" || $k == "xero_name" ) {

            } else {
                $profile.= "<div class='form-group'>";
                $profile.= "<label for='$k'>".$this->form_keys["$k"]."</label>";
                $profile.= '<input class="form-control" type="text" name="'.$k.'" value="'.$v.'" readonly="readonly" > ' ;
                $profile.= "</div>";
            }
        }
      }
      $profile .= "</div>";
      return $profile;
  }
  public function checkdata($post){
    $p = array();
    foreach ($post as $k => $v) {
      if ($k=="phone") {
        $p["phone"] = phone_valid($v);
      } else if($k=="postcode") {
        $p["postcode"] = postcodes_valid($v);
      }
      else {
        $p["$k"] = trim($v);
      }
    }
    return $p;
  }
}

