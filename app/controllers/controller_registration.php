<?php

class Controller_Registration extends Controller {

    public function __construct()
    {
        $this->model = new Model_registration();
        $this->view = new View();
    }

    public function action_index()
    {
        $data = [''];
        $this->view->generate('registration/registration_view.php', 'registration/registration_template.php', $data);
    }

    public function action_submit()
    {
        if (isset($_POST)){
            $register = $this->model->store_user($this->clear_post($_POST));
            $store = $this->model->store_client($this->clear_post($_POST), $register);
            $user_email = $this->model->get_email_client($register);
            $storeFile = $this->model->store_file($register, $user_email);
            if($store && $register){
                session_start();
                $_SESSION['user'] =  md5('user');
                $_SESSION['user_id'] =  $register;
                header('Location:/client/dashboard');
            }
        }
    }

    private function clear_post($cl_post)
    {
        $data = array();
        foreach ($cl_post as $key => $value){
            $value = strip_tags($value);
            $value = trim($value);
            $data[$key]=$value;
        }

        return $data;
    }
}
