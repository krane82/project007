<?php

/**

 * Created by PhpStorm.

 * User: Admin

 * Date: 11.04.2017

 * Time: 16:33

 */

class Controller_Terms extends Controller

{

    function __construct() {

        $this->view = new View();

        $this->model= new Model_Terms();

    }



    function action_index()

    {

        $data["body_class"] = "page-header-fixed";

        session_start();

        if ($_SESSION['admin'] == md5('admin')) {

            $data = $this->model->getAllClients();

            $this->view->generate('terms_view.php', 'template_view.php', $data);

        }

        else if ($_SESSION['user'] == md5('user')) {

            $data['item'] = $this->model->getMyTerms($_SESSION['user_id']);

            $data['notif_counter'] = $this->model->count_notifications($_SESSION['user_id']);

            $data['notifications'] = $this->model->get_new_notifications($_SESSION['user_id']);

            $this->view->generate('client_terms_view.php', 'client_template_view.php', $data);

        }

        else

      {

          session_destroy();

          $this->view->generate('danied_view.php', 'client_template_view.php', $data);

      }

    }



    function action_getListOfCurrent()

    {

        $client=$_POST['client'];

        print $this->model->getListOfCurrent($client);

    }

}