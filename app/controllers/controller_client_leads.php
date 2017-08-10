<?php



class Controller_client_leads extends Controller

{

  function __construct()

  {

    $this->model = new Model_Client_Leads();

    $this->view = new View();

  }



  function action_index()

  {

    $data["body_class"] = "page-header-fixed";

    session_start();

    if ( $_SESSION['user'] ==  md5('user')) {

      $data['notif_counter'] = $this->model->count_notifications($_SESSION['user_id']);
      $data['notifications'] = $this->model->get_new_notifications($_SESSION['user_id']);

      $this->model->update_notifications($_SESSION['user_id']);

      $this->view->generate('client_leads_view.php', 'client_template_view.php', $data);

    } else if ( $_SESSION['user'] ==  md5('manager')) {

      header('location:/manager');

    }

    else {

      // session_destroy();

      $this->view->generate('danied_view.php', 'client_template_view.php', $data);

      //Route::ErrorPage404();

    }

  }



 /*function action_reject_Lead(){

    session_start();

    $lead_id = $_POST["lead_id"];

    $client_id = $_SESSION["user_id"];

    $reason = $_POST["reject_reason"];

    $notes = $_POST["notes"];

    $notes=trim($notes);

    $notes=htmlspecialchars($notes);

    $notes=addslashes($notes);

    $con = $this->db();

    $now = time();

    $sql = "UPDATE `leads_rejection` SET approval='2', reason='$reason', note='$notes', date='$now' WHERE client_id=$client_id AND lead_id=$lead_id";

    //var_dump($sql);die();print $sql;

    if($con->query($sql)){

      echo "Success";

    } else {

      echo "sql error";//$sql;

      return;

    }

  }*/

  function action_reject_Lead(){

    session_start();

    $lead_id = $_POST["lead_id"];

    $client_id = $_SESSION["user_id"];

    $reason = $_POST["reject_reason"];

    $notes = $_POST["notes"];

    $notes=trim($notes);

    $notes=htmlspecialchars($notes);

    $notes=addslashes($notes);

    $con = $this->db();

    $now = time();

    $currentday = date("j");

    if($currentday < 15) {
       $twoWeeks = strtotime('first day of this month');
       //var_dump( $twoWeeks);
    }
    else {
       $twoWeeks = strtotime(date('Y-m-15'));
    }
    $sql = "UPDATE `leads_rejection` SET approval='2', reason='$reason', note='$notes', date='$now' WHERE client_id=$client_id AND lead_id=$lead_id";


    //var_dump($sql);
    //die();print $sql;

    if($con->query($sql)){

      echo "Success";

    } else {

      echo "sql error";//$sql;

      return;

    }

    $sql1="SELECT approval from leads_rejection where client_id='".$client_id."' AND date>'".$twoWeeks."'";
    //$sqll="SELECT sum(weekly) FROM client_campaigns WHERE client_id = '".$client_id."' AND date>'".$twoWeeks."'";
   
    $res=$con->query($sql1);
    // var_dump($res);
    $total = 0;
    $rejected = 0;
    if($res)
    {
      while($row=$res->fetch_assoc())
      {
        $total++;
        //var_dump($row);
        if($row['approval']=='0' || $row['approval']=='2' || $row['approval']=='4')
        {
          $rejected++;
        //  var_dump($rejected);
        }
      }
      $rejPercent=$rejected*100/$total;
      $rejPercenBeforet=($rejected-1)*100/$total;
      //var_dump('rejected: '.$rejected);
     //var_dump('total: '.$total);
     // var_dump($rejPercent);

      if($rejPercent>=30 && $rejPercenBeforet<30)
      {
         $this->model->rejectedMoreThan30($_SESSION['user_name']);
      }
    }
    
  }


  function action_downloadleads()

  {

    session_start();

    if($client = $_SESSION["user_id"])

    {

      $now = time();

      $filename =  'Leads_From_' . date("Y_m_d", $now);

      $data = $this->model->getLeadsForClient($client);

      header('Content-type: text/csv; charset=utf-8');

      header('Content-Disposition: attachment; filename=' . $filename .'.csv');

      $fp = fopen('php://output', 'w');

      foreach( $data as $line ) {

        fputcsv( $fp, $line );

      }

      fclose($fp);

    }

  }


  function action_getLeads(){

    session_start();

    $user_id = $_SESSION["user_id"];

    $table = 'leads_rejection';

    $primaryKey = 'id';

    $now = time();

    $columns = array(

      array( 'db' => '`a`.`lead_id`', 'dt' => 0, 'field' => 'lead_id'  ),

      array( 'db' => '`llf`.`full_name`', 'dt' => 1, 'field' => 'full_name'  ),

      array('db'=>'`a`.`date`',       'dt' => 2, 'formatter' => function( $d ) {

        return date('d/m/Y h:i:s A', $d);

      }, 'field'=>'date'),

        array( 'db' => '`llf`.`state`', 'dt' => 3, 'field' => 'state'  ),

        array( 'db' => '`llf`.`postcode`', 'dt' => 4, 'field' => 'postcode'  ),



        array( 'db' => '`a`.`approval`',  'dt' => 5, 'formatter'=>function($d){

        switch ($d) {

          case 0:

            return "<span class=\"bg-primary pdfive\">Rejection Approved</span>";

            break;

          case 1:

            return "<span class=\"bg-success pdfive\">Approved</span>";

            break;

          case 2:

            return "<span class=\"bg-warning pdfive\">Rejection requested</span>";

            break;

          case 3:

            return "<span class=\"bg-danger pdfive\">Rejection not Approved</span>";

            break;

          case 4:

            return "<span class=\"bg-info pdfive\">More info required</span>";

            break;

          default:

            return "";

        }

      },'field'=> 'approval' ),

      array('db'=> '`a`.`id`', 'dt'=>6, 'formatter'=>function($d, $row){

        if( strtotime('+8 days', $row[2]) < time()){

          return 'Out of 8 days';

        } else

        if( $row[5] == 1 ) {

          $button="<a href='#' class='btn leadreject btn-warning' data-time='".strtotime('+7 days', $row[2])."000' attr-lead-id='$row[0]' attr-client='$row[1]' data-gb='$row[3]'";

        if( strtotime('+2 days', $row[2]) < time())

        {

          $button.=" data-permission='1'";

        }

        $button.="> Reject</a>";

          return $button;

        } if($row[5] == 4) {

          return "<a href='#' class='btn leadreject btn-danger' data-info='true' attr-lead-id='$row[0]' attr-client='$row[1]' data-gb='$row[3]'> Provide more info</a>";

        } else {

         return ""; // silence is golden: <button type=\"button\" class=\"btn btn-warning\" disabled=\"disabled\">Reject </button>";

        }

      }, 'field'=>'id' ),

      array('db'=>'`a`.`id`', 'dt'=>7, 'formatter'=>function($d, $row){

        if(!($row[5] == 0 OR $row[5] == 3)){

          return "<a href='#' class='viewLeadInfo btn btn-primary' attr-lead-id='$row[0]' data-toggle=\"modal\" data-target=\"#LeadInfo\"  >View</a>";

        }

      }),

      array( 'db' => '`a`.`decline_reason`',  'dt' => 8, 'formatter'=>function($d, $row) {

        if ($row[5] > 1) {

          return "<a href='#' class='RejectionDetails btn btn-primary' attr-lead-id='$row[0]' data-toggle=\"modal\" data-target=\"#LeadInfo\"  >View</a>";

        }

      },

        'field' => 'decline_reason')

    );



    $sql_details = array(

        'user' => DB_USER,

        'pass' => DB_PASS,

        'db'   => DB_NAME,

        'host' => DB_HOST

    );



    $joinQuery = "FROM `{$table}` AS `a` LEFT JOIN `clients` AS `c` ON (`a`.`client_id` = `c`.`id`) LEFT JOIN `leads_lead_fields_rel` as `llf` ON ( `a`.`lead_id` = `llf`.`id` ) ";

    $joinQuery .= " LEFT JOIN `leads` as `l` ON `l`.`id`=`a`.`lead_id` ";

    $joinQuery .=" JOIN `leads_delivery` `lld` on `a`.`lead_id` = `lld`.`lead_id` ";

    $where = "`a`.`client_id` = $user_id AND lld.client_id='".$user_id."'";



    echo json_encode(

        SSP::simple( $_POST, $sql_details, $table, $primaryKey, $columns, $joinQuery, $where )

    );

  }



  function action_LeadInfo(){

    if($id = $_POST["id"]){

      $con = $this->db();

      $sql = "SELECT * from `leads_lead_fields_rel` WHERE id=$id";

      if($res = $con->query($sql)){

        $leadinfo = $res->fetch_assoc();

        $prepearedinfo = prepareLeadInfo($leadinfo);

        $content = '<table class="table">';

        foreach ($prepearedinfo as $v){

          $content .= '<tr><td>'.$v["field_name"].'</td><td>'.$v["val"].'</td></tr>';

        }

        $content .= '</table>';

        echo $content;

      }

    } else {

      echo "lead not found";

    }

  }



  function action_rejectInfo(){

    session_start();

    if($id = $_POST["id"]){

      $con = $this->db();

      $client_id = $_SESSION["user_id"];

      $sql = "SELECT reason, note, decline_reason, audiofile from `leads_rejection` WHERE lead_id=$id AND client_id=$client_id";

      //print_r($sql);



      if($res = $con->query($sql)){

        $leadInfo = $res->fetch_assoc();

        $content = '<table class="table">';

        $content.="<tr><td>Rejection reason: </td><td>".$leadInfo['reason']."</td></tr>";

        $content.="<tr><td>Notes: </td><td>".$leadInfo['note']."</td></tr>";

        $content.="<tr><td>Decline Reason: </td><td>".$leadInfo['decline_reason']."</td></tr>";

        if($leadInfo['audiofile'])

        {

        $content.="<tr><td><form method='POST' action='". __HOST__ ."/docs/audios/download.php'>";

        $content.="<input type='hidden' name='file' value='".basename($leadInfo['audiofile'])."'>";

        $content.="<input type='submit' class='btn btn-sm btn-success' value='Download file'></form></td></tr>";

        }

        $content .= '</table>';

        echo $content;

      }

    } else {

      echo "lead not found";

    }

  }



  function action_logout()

  {

    session_start();

    session_destroy();

    header('Location:/login');

  }



}

