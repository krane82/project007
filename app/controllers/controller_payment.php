<?php

/**
 * Created by PhpStorm.
 * User: Admin
 * Date: 31.07.2017
 * Time: 10:56
 */
class Controller_Payment extends Controller
{
public function action_index()
{
    session_start();
    $user=$_SESSION['user_id'];
    $data=$this->model->isPayer($user);
    $this->view->generate('payment_view.php','client_template_view.php',$data);
}
}