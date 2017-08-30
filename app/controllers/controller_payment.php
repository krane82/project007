<?php

/**
 * Created by PhpStorm.
 * User: Admin
 * Date: 31.07.2017
 * Time: 10:56
 */
class Controller_Payment extends Controller
{

    function __construct()
{
        $this->model = new Model_Payment();
        $this->view = new View();
}

    public function action_index()
{
    session_start();
    $user=md5('leadpoint'.$_SESSION['user_id'].'leadpoint');
    $data['client']=$user;
    $data['cref']=$this->model->isPayer($user);
    $this->view->generate('payment_view.php','client_template_view.php',$data);
}
}