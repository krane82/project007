<?php

class Controller_Client_Campaigns extends Controller {



  function __construct() {

    include('app/models/model_api.php');

    $this->model = new Model_Client_Campaigns();

    $this->api = new Model_Api();

    $this->view = new View();

  }



  function action_index() {

      $data["body_class"] = "page-header-fixed";

      session_start();

      echo $_SESSION['user'];

      if ($_SESSION['user'] == md5('user')) {

          $data = $this->model->getMyCampaigns($_SESSION['user_id']);

          $this->view->generate('client_client_campaigns_view.php', 'client_template_view.php', $data);

      }

      if ($_SESSION['admin'] == md5('admin')) {

          $data = $this->model->getAllClients();

          $this->view->generate('client_campaigns_view.php', 'template_view.php', $data);

      }

  }



  function action_add_new_campaign() {

      session_start();
      if(isset($_POST['client']) && !empty($_POST['client']))
      {
          $client=$_POST['client'];
      }
      else
      {
          $client=$_SESSION['user_id'];
      }
      $newPostcodes = trim(strip_tags($_POST['newPostcodes']));

      $camp_name = trim(strip_tags($_POST['name']));

      $camp_weekly = trim(strip_tags($_POST['weekly']));
      $camp_coords = trim(strip_tags($_POST['coords']));
      $camp_radius = trim(strip_tags($_POST['radius']));
      $camp_nearest = trim(strip_tags($_POST['nearest']));

      $result = $this->model->add_new_campaign($client, $camp_name, $camp_weekly, $newPostcodes, $camp_coords, $camp_radius, $camp_nearest);

      echo $result;

  }



  function action_edit_campaign() {

      session_start();

      $newPostcodes = trim(strip_tags($_POST['newPostcodes']));

      $camp_id = trim(strip_tags($_POST['id']));

      $camp_name = trim(strip_tags($_POST['name']));

      $camp_weekly = trim(strip_tags($_POST['weekly']));
      
      $camp_coords = trim(strip_tags($_POST['coords']));
      
      $camp_radius = trim(strip_tags($_POST['radius']));
      
      $camp_nearest = trim(strip_tags($_POST['nearest']));

      if (isset($_POST['client']))

      {

        $client = trim(strip_tags($_POST['client']));

        $result = $this->model->edit_campaign($camp_id, $camp_name, $camp_weekly, $newPostcodes, $camp_coords, $camp_radius, $camp_nearest, $client );

      }

      else 

        $result = $this->model->edit_campaign($camp_id, $camp_name, $camp_weekly, $newPostcodes, $camp_coords, $camp_radius, $camp_nearest, $_SESSION['user_id']);

      echo $result;

  }



  function action_delete_campaign() {

      session_start();

      $camp_id = trim(strip_tags($_POST['id']));

      if (isset($_POST['client']))

      {

        $client = trim(strip_tags($_POST['client']));

        $result = $this->model->delete_campaign($camp_id, $client);

      }

      else 

        $result = $this->model->delete_campaign($camp_id, $_SESSION['user_id']);

      echo $result;

  }



  function action_send_leads() {

    session_start();

    $camp_id = trim(strip_tags($_POST['id']));

    

    if (isset($_POST['client']))

      {

        $client = trim(strip_tags($_POST['client']));

        $status = $this->model->getClientStatus($client);

        if ($status) {

            $result = $this->model->activate_campaign($camp_id, $client);

            $leads=$this->api->getSentLeads();

            foreach ($leads as $lead) {

                $resp = $this->api->sendToClientCamp($client, $lead);

                print $resp;

            }

        }

        else print 'Sorry, can not sent lead because status of client is not active';

      }

      else 

      {

          $status = $this->model->getClientStatus($_SESSION['user_id']);

          if ($status)

          {

            $result = $this->model->activate_campaign($camp_id, $_SESSION['user_id']);

            $leads=$this->api->getSentLeads();

            foreach ($leads as $lead) {

              $resp = $this->api->sendToClientCamp($_SESSION['user_id'], $lead);

              print $resp;

            }

//            echo $result;

          }

          else print 'Sorry, can not sent lead because your status of client is not active';

      }

  }







  function action_getListOfCampOneClient() {

    $client=$_POST['client'];

    $campAr = $this->model->getListOfCampOneClient($client);

    echo json_encode($campAr);



  }

function action_getCurrentCampaign()
{
    $campaign=$_POST['campaign'];
    $data=$this->model->getCurrentCampaign($campaign);
    print json_encode($data);
}

}

