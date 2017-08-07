<?php
class Model_Approvals extends Model {

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

public function deleteFile()
{
  $con = $this->db();
  $filepath=$_POST['path'];
  //return var_dump($_POST);
  $sql="UPDATE leads_rejection SET audiofile=NULL WHERE audiofile='$filepath'";
  if(is_file($filepath))
  {
    unlink($filepath);
  }
  $res = $con->query($sql);
  return $sql;
}
public function selectAllDataOfDeclinedLead($leadId, $clientId)
{
  $con=$this->db();
  $sql="SELECT ler.address, ler.full_name as 'cliName', ler.email as 'cliEmail', ler.phone, rej.reason, rej.note, rej.decline_reason, rej.audiofile, cli.full_name as 'usName', cli.email as 'usEmail' from leads_rejection rej left join leads_lead_fields_rel ler on rej.lead_id=ler.id left join clients cli on rej.client_id=cli.id where rej.lead_id='".$leadId."' and rej.client_id='".$clientId."'";
  $res=$con->query($sql);
  if($res) {
    $result = $res->fetch_assoc();
  return $result;
  }
  return false;
}
public function sendEmail($data)
{
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

  $mail->AddReplyTo("leads@energysmart.com.au", "Leadpoint CRM");

  $mail->SetFrom('leads@energysmart.com.au', 'Leadpoint CRM');

  $mail->AddAddress($data["usEmail"], $data["usName"]);

  $mail->Subject = "One of your rejection leads is declined by Leadpoint admin";

  $mail->AltBody = "To view the message, please use an HTML compatible email viewer!";  // optional, comment out and test

  $content ='
    <h2>Hello, '.strstr($data["usName"],' ',true).'</h2>
    <h3>Here is the information why your decline is not approved</h3>
    <table style="border-collapse:collapse">
    <tr><td style="border: solid 1px black; font-size:16px; font-weight:bold; background-color:aliceblue">Address</td><td style="border: solid 1px black; font-size:16px">'.$data['address'].'</td></tr>
    <tr><td style="border: solid 1px black; font-size:16px; font-weight:bold; background-color:aliceblue">Client\'s name</td><td style="border: solid 1px black; font-size:16px">'.$data['cliName'].'</td ></tr>
    <tr><td style="border: solid 1px black; font-size:16px; font-weight:bold; background-color:aliceblue">Client\'s email</td><td style="border: solid 1px black; font-size:16px">'.$data['cliEmail'].'</td></tr>
    <tr><td style="border: solid 1px black; font-size:16px; font-weight:bold; background-color:aliceblue">Client\'s phone</td><td style="border: solid 1px black; font-size:16px">'.$data['phone'].'</td></tr>
    <tr><td style="border: solid 1px black; font-size:16px; font-weight:bold; background-color:aliceblue">Declining reason</td><td style="border: solid 1px black; font-size:16px">'.$data['reason'].'</td></tr>
    <tr><td style="border: solid 1px black; font-size:16px; font-weight:bold; background-color:aliceblue">Notes</td><td style="border: solid 1px black; font-size:16px">'.$data['note'].'</td></tr>
    <tr><td style="border: solid 1px black; font-size:16px; font-weight:bold; background-color:aliceblue">Reason of disapproving of decline</td><td style="border: solid 1px black; font-size:16px; font-weight: bold; background-color: antiquewhite">'.$data['decline_reason'].'</td></tr>
    </table>
    ';
  if($data['audiofile'])
  {
    $content.='<h3>You also have an audio attachment for this lead</h3>';
  }
  $mail->msgHTML($content);
  $emailSending = "";
  if (!$mail->Send()) {
    echo $emailSending = "Mailer Error: " . $mail->ErrorInfo;
    return FALSE;
  } else {
//    echo $emailSending = "Message sent!";
    return TRUE;
  }
}

  public function getApprovals()
  {
    //$memcache = new Memcache;
    //$memcache->connect(__HOST__, 11211) or die ("Не могу подключиться");
    $con=new PDO('mysql:host='.DB_HOST.';dbname='.DB_NAME.';charset=utf8;',DB_USER,DB_PASS);
    $stamp=strtotime('-1 month');
    $sql="select `a`.`lead_id`, `c`.`campaign_name`,
 DATE_FORMAT(FROM_UNIXTIME(`ld`.`timedate`), \"%e.%m.%Y %h:%i:%s %p\" ),
 DATE_FORMAT(FROM_UNIXTIME(`a`.`date`), \"%e.%m.%Y %h:%i:%s %p\" ),
  `a`.`reason`,`a`.`note`, `a`.`decline_reason`,`a`.`approval`,
  `a`.`audiofile`, `a`.`id`,`a`.`client_id`,
   (select seen from lead_conversations where lead_id=a.id limit 1) as seen
    FROM `leads_rejection` AS `a` INNER JOIN `leads_delivery` as `ld` ON
     (`a`.`id`=`ld`.`id` ) INNER JOIN clients as c ON a.client_id=c.id
      where `a`.`approval` != 1 AND `ld`.`timedate`>'".$stamp."'";
    $statement=$con->prepare($sql);
    if($statement->execute()) {
      $result = $statement->fetchAll(PDO::FETCH_NUM);
      return $result;
    }
    return false;
  }
}