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
    $data["body_class"] = "page-header-fixed";
    $this->view->generate('payment_view.php','client_template_view.php',$data);
}
}